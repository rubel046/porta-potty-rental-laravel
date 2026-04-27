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
        $logType = $request->get('file', 'laravel');
        $perPage = min((int) $request->get('per_page', 100), 500);
        $currentPage = max(1, (int) $request->get('page', 1));
        $levelFilter = $request->get('level', 'all');
        $searchFilter = $request->get('search', '');

        $result = $this->getLogsWithStats($logType, $levelFilter, $searchFilter, $currentPage, $perPage);

        $logFiles = $this->getLogFiles();

        $levels = [
            'info' => 'INFO',
            'warning' => 'WARNING',
            'error' => 'ERROR',
            'critical' => 'CRITICAL',
        ];

        return view('admin.logs.index', compact(
            'logFiles', 'levels',
            'perPage', 'currentPage', 'logType'
        ) + [
            'logs' => $result['logs'],
            'stats' => $result['stats'],
            'paginatedLogs' => $result['logs'],
            'totalLogs' => $result['stats']['total'],
            'totalPages' => ceil($result['stats']['total'] / $perPage),
        ]);
    }

    protected function getLogsWithStats(string $logType, string $levelFilter, string $searchFilter, int $currentPage, int $perPage): array
    {
        $filename = match ($logType) {
            'blog-generation' => 'blog-generation.log',
            'city-page-generation' => 'city-page-generation.log',
            'worker' => 'worker.log',
            'calls' => 'calls-*.log',
            'google-indexing' => 'google-indexing*.log',
            'laravel' => 'laravel.log',
            default => 'laravel.log',
        };

        $files = [];

        if (str_contains($filename, '*')) {
            $files = File::glob(storage_path('logs/'.$filename));
        } else {
            $logPath = storage_path('logs/'.$filename);
            if (File::exists($logPath)) {
                $files = [$logPath];
            }
        }

        if (empty($files)) {
            return [
                'logs' => [],
                'stats' => ['total' => 0, 'info' => 0, 'warning' => 0, 'error' => 0, 'critical' => 0],
            ];
        }

        $stats = [
            'total' => 0,
            'info' => 0,
            'warning' => 0,
            'error' => 0,
            'critical' => 0,
        ];

        $filteredLogs = [];
        $entry = [];
        $offset = ($currentPage - 1) * $perPage;
        $needed = $perPage;

        foreach ($files as $file) {
            $handle = fopen($file, 'r');
            if ($handle === false) {
                continue;
            }

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s+(.*)$/', $line, $matches)) {
                    if (! empty($entry)) {
                        $this->processLogEntry($entry, $stats, $levelFilter, $searchFilter, $filteredLogs, $offset, $needed);
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
                $this->processLogEntry($entry, $stats, $levelFilter, $searchFilter, $filteredLogs, $offset, $needed);
                $entry = [];
            }

            fclose($handle);
        }

        $filteredLogs = array_reverse($filteredLogs);

        return [
            'logs' => $filteredLogs,
            'stats' => $stats,
        ];
    }

    protected function processLogEntry(array $entry, array &$stats, string $levelFilter, string $searchFilter, array &$filteredLogs, int $offset, int $needed): void
    {
        $stats['total']++;
        $level = $entry['level'];
        if (isset($stats[$level])) {
            $stats[$level]++;
        }

        $levelMatch = $levelFilter === 'all' || $entry['level'] === $levelFilter;
        $searchMatch = empty($searchFilter) || str_contains(strtolower($entry['message']), strtolower($searchFilter));

        if ($levelMatch && $searchMatch) {
            $count = count($filteredLogs);
            if ($count < $offset + $needed) {
                $filteredLogs[] = $entry;
            }
        }
    }

    protected function getLogsByType(string $logType, Request $request): array
    {
        $filename = match ($logType) {
            'blog-generation' => 'blog-generation.log',
            'city-page-generation' => 'city-page-generation.log',
            'worker' => 'worker.log',
            'calls' => 'calls-*.log',
            'google-indexing' => 'google-indexing*.log',
            'laravel' => 'laravel.log',
            default => 'laravel.log',
        };

        $logs = $this->getLogContent($filename);

        if ($request->filled('level') && $request->level !== 'all') {
            $logs = array_filter($logs, fn ($log) => $log['level'] === $request->level);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $logs = array_filter($logs, fn ($log) => str_contains(strtolower($log['message']), $search));
        }

        return array_values($logs);
    }

    protected function getLogStatsByType(string $logType): array
    {
        $logs = $this->getLogsByType($logType, new Request);
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

    public function show(Request $request, ?string $date = null)
    {
        $logs = $this->getLogs($request, $date);

        return view('admin.logs.show', compact('logs', 'date'));
    }

    public function clear(Request $request)
    {
        $logType = $request->get('file', 'laravel');
        $filename = match ($logType) {
            'blog-generation' => 'blog-generation.log',
            'city-page-generation' => 'city-page-generation.log',
            'worker' => 'worker.log',
            'calls' => 'calls-'.now()->format('Y-m-d').'.log',
            'google-indexing' => 'google-indexing.log',
            default => 'laravel.log',
        };

        $logPath = storage_path('logs/'.$filename);
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return redirect()->back()->with('success', 'Log file cleared successfully!');
    }

    public function download(Request $request)
    {
        $logType = $request->get('file', 'laravel');
        $filename = match ($logType) {
            'blog-generation' => 'blog-generation.log',
            'city-page-generation' => 'city-page-generation.log',
            'worker' => 'worker.log',
            'calls' => 'calls-'.now()->format('Y-m-d').'.log',
            'google-indexing' => 'google-indexing.log',
            default => 'laravel.log',
        };

        $logPath = storage_path('logs/'.$filename);
        if (! File::exists($logPath)) {
            return redirect()->back()->with('error', 'Log file not found.');
        }

        return response()->download($logPath, $filename);
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

        $patterns = [
            'laravel' => '/laravel\.log/',
            'blog-generation' => '/blog-generation\.log/',
            'city-page-generation' => '/city-page-generation\.log/',
            'worker' => '/worker\.log/',
            'calls' => '/calls-\d{4}-\d{2}-\d{2}\.log/',
            'google-indexing' => '/google-indexing(?:-\w+)?\.log/',
        ];

        foreach ($files as $file) {
            $filename = $file->getFilename();
            foreach ($patterns as $type => $pattern) {
                if (preg_match($pattern, $filename)) {
                    $logFiles[] = [
                        'name' => $filename,
                        'type' => $type,
                        'path' => $file->getPathname(),
                        'date' => preg_match('/(\d{4}-\d{2}-\d{2})/', $filename, $m) ? $m[1] : date('Y-m-d', $file->getMTime()),
                    ];
                    break;
                }
            }
        }

        usort($logFiles, fn ($a, $b) => $b['date'].$b['name'] <=> $a['date'].$a['name']);

        return $logFiles;
    }

    protected function getLogContent(string $filename): array
    {
        $logs = [];
        $files = [];

        if (str_contains($filename, '*')) {
            $files = File::glob(storage_path('logs/'.$filename));
        } else {
            $logPath = storage_path('logs/'.$filename);
            if (File::exists($logPath)) {
                $files = [$logPath];
            }
        }

        if (empty($files)) {
            return [];
        }

        $fileLogs = [];
        $entry = [];

        foreach ($files as $file) {
            $content = File::get($file);
            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s+(.*)$/', $line, $matches)) {
                    if (! empty($entry)) {
                        $fileLogs[] = $entry;
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
                $fileLogs[] = $entry;
                $entry = [];
            }
        }

        return array_reverse($fileLogs);
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
