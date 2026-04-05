<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LogViewerController extends Controller
{
    protected string $logPath;

    public function __construct()
    {
        $this->logPath = storage_path('logs/laravel.log');
    }

    public function index(Request $request)
    {
        $logs = $this->getLogs($request);
        $perPage = (int) $request->get('per_page', 100);
        $currentPage = (int) $request->get('page', 1);
        $totalLogs = count($logs);
        $totalPages = ceil($totalLogs / $perPage);
        $paginatedLogs = array_slice($logs, ($currentPage - 1) * $perPage, $perPage);

        $logFiles = $this->getLogFiles();

        $levels = [
            'info' => 'INFO',
            'warning' => 'WARNING',
            'error' => 'ERROR',
            'critical' => 'CRITICAL',
        ];

        $stats = $this->getLogStats();

        return view('admin.logs.index', compact(
            'logs', 'logFiles', 'levels', 'stats',
            'perPage', 'currentPage', 'totalPages', 'totalLogs', 'paginatedLogs'
        ));
    }

    public function show(Request $request, ?string $date = null)
    {
        $logs = $this->getLogs($request, $date);

        return view('admin.logs.show', compact('logs', 'date'));
    }

    public function clear()
    {
        if (File::exists($this->logPath)) {
            File::put($this->logPath, '');
        }

        return redirect()->back()->with('success', 'Log file cleared successfully!');
    }

    public function download()
    {
        if (! File::exists($this->logPath)) {
            return redirect()->back()->with('error', 'Log file not found.');
        }

        return response()->download($this->logPath, 'laravel.log');
    }

    protected function getLogs(Request $request, ?string $date = null): array
    {
        if (! File::exists($this->logPath)) {
            return [];
        }

        $content = File::get($this->logPath);
        $lines = explode("\n", $content);

        $logs = [];
        $entry = [];

        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})]\s+(\w+)\.(\w+):\s+(.*)$/', $line, $matches)) {
                if (! empty($entry)) {
                    $logs[] = $entry;
                }

                $entry = [
                    'timestamp' => $matches[1],
                    'level' => strtolower($matches[3]),
                    'env' => $matches[2],
                    'message' => $matches[4],
                    'context' => [],
                ];
            } elseif (! empty($entry) && Str::startsWith($line, '[')) {
                if (preg_match('/^\[(.*?)\]\s+(.*)$/', $line, $contextMatch)) {
                    $entry['context'][$contextMatch[1]] = $contextMatch[2];
                }
            } elseif (! empty($entry)) {
                $entry['message'] .= "\n".$line;
            }
        }

        if (! empty($entry)) {
            $logs[] = $entry;
        }

        $logs = array_reverse($logs);

        if ($request->filled('level') && $request->level !== 'all') {
            $logs = array_filter($logs, fn ($log) => $log['level'] === $request->level);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $logs = array_filter($logs, fn ($log) => str_contains(strtolower($log['message']), $search));
        }

        if ($request->filled('env')) {
            $logs = array_filter($logs, fn ($log) => $log['env'] === $request->env);
        }

        return array_values($logs);
    }

    protected function getLogFiles(): array
    {
        $files = File::files(storage_path('logs'));
        $logFiles = [];

        foreach ($files as $file) {
            if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename(), $matches)) {
                $logFiles[] = [
                    'date' => $matches[1],
                    'path' => $file->getPathname(),
                ];
            }
        }

        return array_reverse($logFiles);
    }

    protected function getLogStats(): array
    {
        $logs = $this->getLogs(new Request);
        $stats = [
            'total' => count($logs),
            'info' => 0,
            'warning' => 0,
            'error' => 0,
            'critical' => 0,
        ];

        foreach ($logs as $log) {
            $level = $log['level'];
            if (isset($stats[$level])) {
                $stats[$level]++;
            }
        }

        return $stats;
    }
}
