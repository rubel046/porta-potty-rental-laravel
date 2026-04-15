@extends('admin.layout')
@section('title', isset($category) ? "Edit: {$category->name}" : 'New Blog Category')
@section('page-title', isset($category) ? "Edit: {$category->name}" : 'New Blog Category')

@section('content')
<form method="POST"
      action="{{ isset($category) ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}"
      class="max-w-2xl"
      x-data="{ manuallyEdited: false }"
      @name-updated.window="if (!manuallyEdited) { $el.querySelector('#slug').value = $event.detail.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').trim() }">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="card p-6 space-y-6">
        <div>
            <label class="form-label">Name *</label>
            <input type="text" name="name" id="name" class="form-input"
                   value="{{ old('name', $category->name ?? '') }}"
                   placeholder="Pricing & Costs" required
                   x-on:input="if (!manuallyEdited) $dispatch('name-updated', $event.target.value)">
        </div>

        <div>
            <label class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-input font-mono text-sm"
                   value="{{ old('slug', $category->slug ?? '') }}"
                   placeholder="pricing-costs"
                   x-on:input="manuallyEdited = true">
            <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate</p>
        </div>

        <div>
            <label class="form-label">Description</label>
            <textarea name="description" rows="2" class="form-input"
                      placeholder="Brief description for this category...">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div x-data="{ open: false, selected: '{{ old('icon', $category->icon ?? '📁') }}' }" class="relative">
                <label class="form-label">Icon</label>
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-left flex items-center justify-between bg-white hover:bg-gray-50">
                    <span x-text="selected"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <input type="hidden" name="icon" :value="selected">
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-y-auto" style="max-height: 200px;">
                    <div class="flex flex-wrap gap-1 p-2">
                        @foreach(['📁','💰','🎉','🏗️','💒','📋','📰','🚨','🏠','✨','🧼','☀️','📍','🔧','📦','🚚','⏰','⭐','🎯','📞','💵','🛠️','🏢','🌳','🚗','🏆','📱','💻','🌟','🎪','🎨','🎬','🎤','🔔','📊','📈','💡','🔥','❄️','🌧️','☔','🌈','🌺','🌻','🌼','🍃','🦋','🐝','💧','🚿','🧽','🧴','🧻','🚽','🚾','🛁','🛀','👷','🦺','📐','📏','🗺️','🏞️','🏕️','🏖️','🏜️','🎠','🎡','🎢','📅','📆','🗓️','📌','🏷️','💲','🪙','🔖','📦','📝','🔍','🔎','🔬','🧪','💉','💊','🩺','🏥','🏨','🏩','🏪','🏬','🏭','🏯','🏰','🗼','🗽','🗿','🌉','🌃','🌄','🌅','🌆','🌇','♨️','🎢','🚣','🥽','🦺','🧑‍🔧','🧑‍🏭','🧑‍🎨','🧑‍🎭','🧑‍🚀','🧑‍⚕️','🧑‍⚖️','🧑‍🌾','🧑‍🍳','🧑‍🔬','🧑‍💻','🧑‍💼','👥','🏃','🚶','🚴','🏊','⛹️','🏋️','🧘','🎯','🎱','🎮','🎲','🎸','🎺','🎻','🥁','🪘','🎹','🎚️','🎛️','📻','📺','📠','📷','📹','📸','🖥️','🖨️','⌨️','🖱️','💾','💿','📀','🔋','🔌','💻'] as $icon)
                        <button type="button" @click="selected = '{{ $icon }}'; open = false" class="p-1 text-lg hover:bg-gray-100 rounded-md transition shrink-0" :class="selected === '{{ $icon }}' ? 'bg-green-100' : ''">{{ $icon }}</button>
                        @endforeach
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">Click to select</p>
            </div>

            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" class="form-input" min="0"
                       value="{{ old('sort_order', isset($category) ? $category->sort_order : ($nextSortOrder ?? 0)) }}">
            </div>
        </div>

        <div class="flex items-center gap-2 pt-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="rounded border-gray-300">
            <span class="text-sm text-gray-700">Active</span>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit" class="btn-primary">
            {{ isset($category) ? 'Update Category' : 'Create Category' }}
        </button>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn-secondary">
            Cancel
        </a>
    </div>
</form>
@endsection