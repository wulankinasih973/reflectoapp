<div class="p-6 bg-white rounded-lg shadow-lg flex items-center gap-4">
    
    {{-- ICON --}}
    @if(!empty($icon))
        <i class="{{ $icon }} text-3xl text-gray-700"></i>
    @endif

    {{-- TEXT --}}
    <div>
        <p class="text-gray-500 text-sm">{{ $title }}</p>
        <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
    </div>
</div>
