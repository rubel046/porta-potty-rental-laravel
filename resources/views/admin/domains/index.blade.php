@extends('admin.layout')

@section('title', 'Manage Domains')
@section('page-title', 'Manage Domains')

@section('content')
    <div x-data="domainManager()">
        <div class="max-w-5xl mx-auto">
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Domains</h2>
                    <p class="text-sm text-gray-500 mt-1">Switch between websites or manage domain settings</p>
                </div>
                <button @click="openModal('create')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Domain
                </button>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($domains as $domain)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition relative overflow-hidden">
                        @if($currentDomain && $currentDomain->id === $domain->id)
                            <div class="absolute top-0 right-0 px-3 py-1 text-xs font-semibold text-white"
                                 style="background-color: {{ $domain->primary_color }};">
                                Current
                            </div>
                        @endif

                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg font-bold shadow-sm"
                                style="background-color: {{ $domain->primary_color }};">
                                {{ strtoupper(substr($domain->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $domain->name }}</h3>
                                <p class="text-sm text-gray-500 truncate">{{ $domain->domain }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full"
                                     style="width: {{ $domain->domain_cities_count > 0 ? min(100, $domain->domain_cities_count / 100) : 0 }}%; background-color: {{ $domain->primary_color }};"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-600">{{ number_format($domain->domain_cities_count) }} cities</span>
                        </div>

                        <div class="flex items-center gap-2 mb-4 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                            </svg>
                            <span>{{ number_format($domain->domain_states_count) }} states</span>
                        </div>

                        <div class="flex items-center gap-2 mb-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $domain->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <span
                                class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $domain->is_active ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                            {{ $domain->is_active ? 'Active' : 'Inactive' }}
                        </span>
                            <span class="text-xs text-gray-400">#{{ $domain->id }}</span>
                        </div>

                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('admin.domains.switch', $domain) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full px-3 py-2 text-sm font-medium rounded-lg transition flex items-center justify-center gap-2"
                                        style="background-color: {{ $domain->primary_color }}; color: white;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    {{ $currentDomain && $currentDomain->id === $domain->id ? 'Current Site' : 'Switch To' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.domains.sync', $domain) }}"
                                  onsubmit="return confirm('Sync new cities and states for {{ $domain->name }}?')">
                                @csrf
                                <button type="submit"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Sync new cities & states">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </form>
                            <button @click="editDomain({{ $domain->id }})"
                                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition"
                                    title="Edit"
                                    data-domain-id="{{ $domain->id }}"
                                    data-name="{{ htmlspecialchars($domain->name, ENT_QUOTES) }}"
                                    data-domain="{{ htmlspecialchars($domain->domain, ENT_QUOTES) }}"
                                    data-business-name="{{ htmlspecialchars($domain->business_name ?? '', ENT_QUOTES) }}"
                                    data-primary-keyword="{{ htmlspecialchars($domain->primary_keyword ?? '', ENT_QUOTES) }}"
                                    data-secondary-keywords="{{ htmlspecialchars(collect($domain->secondary_keywords ?? [])->implode(', '), ENT_QUOTES) }}"
                                    data-primary-service="{{ htmlspecialchars($domain->primary_service ?? '', ENT_QUOTES) }}"
                                    data-service-types="{{ htmlspecialchars(collect($domain->service_types ?? [])->implode(', '), ENT_QUOTES) }}"
                                    data-tagline="{{ htmlspecialchars($domain->tagline ?? '', ENT_QUOTES) }}"
                                    data-cta-phone="{{ htmlspecialchars($domain->cta_phone ?? '', ENT_QUOTES) }}"
                                    data-color="{{ $domain->primary_color }}"
                                    data-active="{{ $domain->is_active ? '1' : '0' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            @if($domain->cities()->count() === 0)
                                <form method="POST" action="{{ route('admin.domains.destroy', $domain) }}"
                                      onsubmit="return confirm('Delete this domain?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No domains yet</h3>
                        <p class="mt-2 text-sm text-gray-500">Get started by adding your first domain.</p>
                        <button @click="openModal('create')"
                                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Add Your First Domain
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click="showModal = false" class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form
                        :action="editMode ? '/admin/domains/' + currentDomain.id : '{{ route('admin.domains.store') }}'"
                        method="POST">
                        @csrf
                        <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'"
                               x-model="editMode ? 'PUT' : 'POST'">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6"
                                x-text="editMode ? 'Edit Domain' : 'Add New Domain'"></h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Domain Name</label>
                                    <input type="text" name="name" x-model="currentDomain.name" required
                                           placeholder="My Website"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Domain (without
                                        https://)</label>
                                    <input type="text" name="domain" x-model="currentDomain.domain" required
                                           placeholder="example.com"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                    <p class="mt-1 text-xs text-gray-500">Enter the domain without http:// or
                                        https://</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Business Name</label>
                                    <input type="text" name="business_name" x-model="currentDomain.business_name"
                                           placeholder="My Business LLC"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary
                                        Keyword</label>
                                    <input type="text" name="primary_keyword" x-model="currentDomain.primary_keyword"
                                           placeholder="porta potty rental"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                    <p class="mt-1 text-xs text-gray-500">Main service keyword for SEO</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Secondary
                                        Keywords</label>
                                    <textarea name="secondary_keywords_text"
                                              x-model="currentDomain.secondary_keywords_text"
                                              placeholder="portable toilet rental, event restroom rental, construction toilets"
                                              rows="2"
                                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Comma-separated keywords</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary
                                        Service</label>
                                    <input type="text" name="primary_service" x-model="currentDomain.primary_service"
                                           placeholder="porta potty"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                    <p class="mt-1 text-xs text-gray-500">Used for service type labels</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Service Types</label>
                                    <textarea name="service_types_text" x-model="currentDomain.service_types_text"
                                              placeholder="general, construction, wedding, event, luxury, party, emergency, residential"
                                              rows="2"
                                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Comma-separated service types</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tagline</label>
                                    <input type="text" name="tagline" x-model="currentDomain.tagline"
                                           placeholder="Your Trusted Portable Restroom Experts"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA Phone</label>
                                    <input type="text" name="cta_phone" x-model="currentDomain.cta_phone"
                                           placeholder="(888) 555-0199"
                                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Primary Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" x-model="currentDomain.primary_color"
                                               class="h-12 w-20 rounded-lg cursor-pointer border-2 border-gray-200 p-1">
                                        <input type="text" name="primary_color" x-model="currentDomain.primary_color"
                                               placeholder="#3B82F6"
                                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 border font-mono text-sm">
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" x-model="currentDomain.is_active" value="1"
                                           class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label class="ml-3 block text-sm text-gray-700">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                    class="inline-flex w-full justify-center rounded-lg border border-transparent px-4 py-2.5 text-sm font-semibold text-white shadow-sm sm:w-auto sm:ml-3"
                                    :style="'background-color: ' + currentDomain.primary_color">
                                <span x-text="editMode ? 'Update Domain' : 'Create Domain'"></span>
                            </button>
                            <button type="button" @click="showModal = false"
                                    class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm sm:mt-0 sm:w-auto hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function domainManager() {
            return {
                showModal: false,
                editMode: false,
                currentDomain: {
                    id: null,
                    name: '',
                    domain: '',
                    business_name: '',
                    primary_keyword: '',
                    secondary_keywords_text: '',
                    primary_service: '',
                    service_types_text: '',
                    tagline: '',
                    cta_phone: '',
                    primary_color: '#3B82F6',
                    is_active: true
                },
                openModal(mode) {
                    this.editMode = (mode === 'create');
                    if (mode === 'create') {
                        this.currentDomain = {
                            id: null,
                            name: '',
                            domain: '',
                            business_name: '',
                            primary_keyword: '',
                            secondary_keywords_text: '',
                            primary_service: '',
                            service_types_text: '',
                            tagline: '',
                            cta_phone: '',
                            primary_color: '#3B82F6',
                            is_active: true
                        };
                    }
                    this.showModal = true;
                },
                editDomain(id) {
                    const btn = document.querySelector(`[data-domain-id="${id}"]`);
                    this.editMode = true;
                    this.currentDomain = {
                        id: id,
                        name: btn.dataset.name || '',
                        domain: btn.dataset.domain || '',
                        business_name: btn.dataset.businessName || '',
                        primary_keyword: btn.dataset.primaryKeyword || '',
                        secondary_keywords_text: btn.dataset.secondaryKeywords || '',
                        primary_service: btn.dataset.primaryService || '',
                        service_types_text: btn.dataset.serviceTypes || '',
                        tagline: btn.dataset.tagline || '',
                        cta_phone: btn.dataset.ctaPhone || '',
                        primary_color: btn.dataset.color || '#3B82F6',
                        is_active: btn.dataset.active === '1'
                    };
                    this.showModal = true;
                }
            }
        }
    </script>
@endsection
