@php
    $classes = 'min-w-full divide-y divide-gray-200 dark:divide-gray-700';
@endphp

<table {{ $attributes->merge(['class' => $classes]) }}>
    @isset($tableHeader)
        <thead class="bg-gray-50 dark:bg-gray-800">
            {{ $tableHeader }}
        </thead>
    @endisset
    @isset($tableBody)
        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
            {{ $tableBody }}
        </tbody>
    @endisset
    {{ $slot }}
</table>
