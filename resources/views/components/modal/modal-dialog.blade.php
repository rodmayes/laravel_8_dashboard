@props(['id' => null, 'maxWidth' => null])

<x-modal.modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="flex items-start justify-between text-lg font-semibold dark:text-white">
                {{ $title }}
        </div>
        <div class="mt-4 text-sm text-gray-600 dark:text-gray-200">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
        {{ $footer }}
    </div>
</x-modal.modal>
