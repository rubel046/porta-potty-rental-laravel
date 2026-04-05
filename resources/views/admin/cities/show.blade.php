@extends('admin.layout')
@section('title', $city->name)
@section('page-title', $city->name)

@section('content')
<div class="grid lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        {{-- JSON Import Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-indigo-50 to-white">
                <div>
                    <h2 class="font-bold text-gray-800 text-lg">Import JSON Content</h2>
                    <p class="text-sm text-gray-500">Paste JSON from external API to generate pages</p>
                </div>
                <button type="button" onclick="toggleJsonPanel()" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    {{ isset($showJsonPanel) && $showJsonPanel ? 'Hide' : 'Show' }} Import
                </button>
            </div>
            <div id="json-panel" class="{{ isset($showJsonPanel) && $showJsonPanel ? '' : 'hidden' }} p-6">
                <form method="POST" action="{{ route('admin.cities.import-json', $city) }}">
                    @csrf
                    <div class="mb-4 flex gap-2">
                        <button type="button" onclick="loadSampleJson()" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Load Sample Format
                        </button>
                        <button type="button" onclick="copySampleJson(event)" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm hover:bg-emerald-100 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Copy JSON
                        </button>
                        <a href="{{ route('admin.cities.sample-json', $city) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm hover:bg-blue-100 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            View
                        </a>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Paste JSON Content</label>
                        <textarea name="json_content" id="json-content" rows="12" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-mono bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder='{"service_pages": [{"service_type": "general", "slug": "...", "h1_title": "...", ...}]}'></textarea>
                        <p class="text-xs text-gray-500 mt-2">Supports: service_pages (with h1_title, meta_title, meta_description, content), faqs (question, answer, service_type), testimonials (customer_name, content, rating, service_type)</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Import Content
                        </button>
                        <button type="button" onclick="clearJsonContent()" class="px-4 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                            Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Generation Status --}}
        @if($generationStatus === 'processing')
            <div class="bg-white rounded-xl shadow-sm border border-indigo-200 p-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-indigo-700">
                        Generating content...
                        @if($currentType)
                            <span class="text-indigo-500">({{ ucfirst($currentType) }})</span>
                        @endif
                    </span>
                    <span class="text-sm text-indigo-600 font-bold" id="progress-percent-top">{{ $generationProgress }}%</span>
                </div>
                <div class="w-full bg-indigo-100 rounded-full h-3">
                    <div class="bg-purple-600 h-3 rounded-full transition-all duration-300" style="width: {{ $generationProgress }}%"></div>
                </div>
                @if($startedAt)
                    <p class="text-xs text-indigo-400 mt-2">Started: {{ \Carbon\Carbon::parse($startedAt)->diffForHumans() }}</p>
                @endif
            </div>
        @elseif($generationStatus === 'completed')
            <div class="bg-white rounded-xl shadow-sm border border-green-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-700">Content generation completed!</p>
                        <p class="text-xs text-green-600">{{ $city->servicePages->count() }} pages, FAQs & testimonials ready</p>
                    </div>
                </div>
                @if(!empty($generationErrors))
                    <div class="mt-3 p-2 bg-yellow-50 rounded border border-yellow-100">
                        <p class="text-xs text-yellow-700">Some pages had issues: {{ implode(', ', $generationErrors) }}</p>
                    </div>
                @endif
            </div>
        @elseif($generationStatus === 'failed')
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-red-700">Content generation failed</p>
                        <p class="text-xs text-red-500">Check logs or try again</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Service Pages</h2>
                <div class="flex gap-2">
                    @if($city->servicePages->count() > 0)
                        <button type="submit" form="bulk-delete-form" onclick="return confirm('Delete selected pages?')" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed" id="delete-selected-btn" disabled>
                            Delete Selected
                        </button>
                    @endif
                    <form method="POST" action="{{ route('admin.cities.generate-pages', $city) }}" id="generate-form">
                        @csrf
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700" id="generate-btn">Generate Content</button>
                    </form>
                </div>
            </div>

            <form id="bulk-delete-form" method="POST" action="{{ route('admin.service-pages.bulk-destroy') }}">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="pb-2 w-8"><input type="checkbox" id="select-all"></th><th class="pb-2">Type</th><th class="pb-2">Slug</th><th class="pb-2">Views</th><th class="pb-2">Calls</th><th class="pb-2 w-24">Actions</th></tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($city->servicePages as $page)
                            <tr>
                                <td class="py-2"><input type="checkbox" name="page_ids[]" value="{{ $page->id }}" class="page-checkbox rounded"></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $page->service_type }}</span>
                                </td>
                                <td class="py-2 text-xs font-mono">{{ Str::limit($page->slug, 30) }}</td>
                                <td class="py-2">{{ number_format($page->views) }}</td>
                                <td class="py-2">{{ number_format($page->calls_generated) }}</td>
                                <td class="py-2">
                                    <div class="flex gap-1">
                                        <a href="{{ url($page->slug) }}" target="_blank" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="View Public Page">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.service-pages.edit', $page) }}" class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-center text-gray-400">No pages generated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($city->servicePages->count() > 0)
                    <div class="mt-4 text-sm text-gray-500">
                        {{ $city->servicePages->count() }} pages
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">City Details</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">City</dt><dd class="font-medium">{{ $city->name }}</dd></div>
                <div><dt class="text-gray-500">State</dt><dd class="font-medium">{{ $city->state?->name }}</dd></div>
                <div><dt class="text-gray-500">Population</dt><dd>{{ $city->population ? number_format($city->population) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Area Codes</dt><dd>{{ $city->area_codes ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt>
                    <dd>@if($city->is_active)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</dd>
                </div>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.cities.edit', $city) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Phone Numbers</h2>
            </div>
            @forelse($city->phoneNumbers as $number)
                <div class="p-3 bg-gray-50 rounded-lg mb-2">
                    <div class="font-mono text-sm">{{ $number->number }}</div>
                    <div class="text-xs text-gray-500">{{ $number->total_calls }} calls</div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No phone numbers assigned</p>
            @endforelse
            <a href="{{ route('admin.phone-numbers.index', ['city_id' => $city->id]) }}" class="text-blue-600 text-sm hover:text-blue-700">Manage Numbers →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-3">Quick Stats</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Service Pages</span>
                    <span class="font-semibold text-gray-800">{{ $city->servicePages->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">FAQs</span>
                    <span class="font-semibold text-gray-800">{{ $city->faqs->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Testimonials</span>
                    <span class="font-semibold text-gray-800">{{ $city->testimonials->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Calls</span>
                    <span class="font-semibold text-gray-800">{{ $city->callLogs->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleJsonPanel() {
        const panel = document.getElementById('json-panel');
        panel.classList.toggle('hidden');
    }

    function clearJsonContent() {
        document.getElementById('json-content').value = '';
    }

    async function loadSampleJson() {
        try {
            const response = await fetch('{{ route('admin.cities.sample-json', $city) }}');
            const data = await response.json();
            document.getElementById('json-content').value = JSON.stringify(data, null, 2);
        } catch (error) {
            alert('Failed to load sample JSON');
        }
    }

    async function copySampleJson(event) {
        try {
            const response = await fetch('{{ route('admin.cities.sample-json', $city) }}');
            const data = await response.json();
            const jsonString = JSON.stringify(data, null, 2);
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(jsonString);
                
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Copied!';
                btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'hover:bg-emerald-100');
                btn.classList.add('bg-green-600', 'text-white');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.add('bg-emerald-50', 'text-emerald-700', 'hover:bg-emerald-100');
                    btn.classList.remove('bg-green-600', 'text-white');
                }, 2000);
            } else {
                throw new Error('Clipboard API not available');
            }
        } catch (error) {
            console.error('Copy error:', error);
            
            // Fallback: Download as file
            try {
                const response = await fetch('{{ route('admin.cities.sample-json', $city) }}');
                const data = await response.json();
                const jsonString = JSON.stringify(data, null, 2);
                
                const blob = new Blob([jsonString], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = '{{ $city->slug }}-seo-data.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                alert('JSON downloaded as file. You can now edit it and paste it back here to import.');
            } catch (downloadError) {
                alert('Failed to copy or download JSON. Please click "View" to open the JSON in a new tab, then copy manually (Ctrl+C / Cmd+C).');
            }
        }
    }

    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.page-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButton();
    });

    document.querySelectorAll('.page-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.page-checkbox:checked');
        const btn = document.getElementById('delete-selected-btn');
        btn.disabled = checked.length === 0;
        btn.textContent = checked.length > 0 ? 'Delete Selected (' + checked.length + ')' : 'Delete Selected';
    }

    // Handle generation form submission
    document.getElementById('generate-form')?.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to generate content? This will create 8 service pages, FAQs, and testimonials.\n\nExisting pages will be updated if they have the same slug.')) {
            e.preventDefault();
            return;
        }
        
        const btn = document.getElementById('generate-btn');
        btn.disabled = true;
        btn.textContent = 'Starting...';
        
        document.getElementById('generation-progress').classList.remove('hidden');
        
        checkGenerationProgress();
    });

    function checkGenerationProgress() {
        fetch('{{ route('admin.cities.generation-progress', $city) }}')
            .then(res => res.json())
            .then(data => {
                const progressBar = document.getElementById('progress-bar');
                const progressPercent = document.getElementById('progress-percent');
                const statusText = document.querySelector('#generation-progress .text-indigo-700');
                
                progressBar.style.width = data.progress + '%';
                progressPercent.textContent = data.progress + '%';
                
                if (data.current_type && statusText) {
                    const typeName = data.current_type.charAt(0).toUpperCase() + data.current_type.slice(1);
                    statusText.innerHTML = 'Generating content... <span class="text-indigo-500">(' + typeName + ')</span>';
                }
                
                if (data.status === 'processing') {
                    setTimeout(checkGenerationProgress, 2000);
                } else if (data.status === 'completed') {
                    progressPercent.textContent = 'Completed!';
                    progressBar.classList.remove('bg-purple-600');
                    progressBar.classList.add('bg-green-600');
                    if (statusText) statusText.textContent = 'Generation complete!';
                    const infoText = document.querySelector('#generation-progress .text-indigo-500, #generation-progress .text-indigo-500 ~ p');
                    if (infoText) infoText.textContent = 'Refresh the page to see the new content.';
                    setTimeout(() => location.reload(), 2000);
                } else if (data.status === 'failed') {
                    progressPercent.textContent = 'Failed';
                    document.querySelector('#generation-progress').classList.remove('bg-indigo-50');
                    document.querySelector('#generation-progress').classList.add('bg-red-50', 'border-red-100');
                }
            })
            .catch(() => {
                setTimeout(checkGenerationProgress, 5000);
            });
    }
</script>
@if($generationStatus === 'processing')
<script>
    setTimeout(() => location.reload(), 5000);
</script>
@endif
@endsection
