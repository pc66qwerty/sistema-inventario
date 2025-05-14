@props(['name', 'type' => 'text', 'placeholder' => '', 'step' => null, 'span' => '', 'required' => false])

<input name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
       @if($step) step="{{ $step }}" @endif
       @if($required) required @endif
       class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100 px-3 py-2 shadow-sm w-full {{ $span }}">
