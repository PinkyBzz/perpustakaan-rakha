@props(['status'])

@php
    $colors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'return_requested' => 'bg-blue-100 text-blue-800',
        'returned' => 'bg-gray-100 text-gray-800',
    ];

    $label = \Illuminate\Support\Str::title(str_replace('_', ' ', $status));
@endphp

<span {{ $attributes->merge(['class' => 'px-3 py-1 rounded-full text-xs font-semibold ' . ($colors[$status] ?? 'bg-gray-100 text-gray-800')]) }}>
    {{ $label }}
</span>
