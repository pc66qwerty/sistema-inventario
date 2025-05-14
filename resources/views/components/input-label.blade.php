@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
           @if($required) required @endif
           class="mt-1 block w-full rounded-2xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 shadow-sm transition focus:ring-blue-500 focus:border-blue-500">
</div>
