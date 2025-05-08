<div>
    <x-modal.modal-dialog id="add-numbers-modal">
        <x-slot:title>
            Add numbers modal
        </x-slot>
        <x-slot:content>
            <!-- Calendar -->
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
                <div id="datepicker-inline" inline-datepicker data-date="{{ now()->format('m/d/Y') }}"></div>
            </div>
            <!-- Numbers -->
            <div>
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numbers (Input 6 numbers between 1-49)</label>
                <div class="flex rounded-lg bg-white dark:bg-gray-700 shadow-lg p-4 shadow-md">
                    <div class="grid grid-cols-7 gap-2">
                        @for($col = 0; $col < 7; $col++)
                            <div class="space-y-1">
                                @for($row = 1; $row <= 7; $row++)
                                    @php
                                        $i = $col * 7 + $row;
                                        if ($i > 49) break;
                                    @endphp
                                    <button type="button" wire:click:live="toggleNumber({{ $i }})"
                                            class="w-9 h-9 flex items-center justify-center text-sm font-medium text-center rounded-lg focus:ring-4 hover:bg-blue-800 hover:text-white focus:outline-none focus:ring-blue-300
                                                dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 border mr-2
                                                {{ in_array($i, $numbers) ? 'bg-blue-500 text-white dark:bg-gray-800 dark:text-white' : 'bg-white text-gray-700 dark:bg-gray-200 dark:text-black' }}">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </x-slot>
        <x-slot:footer>
            <x-inputs.button-outlined data-modal-hide="add-numbers-modal" type="button" class="mr-2">
                {{ trans('global.cancel') }}
            </x-inputs.button-outlined>
            <x-inputs.button-primary type="submit" wire:click="save">
                {{ trans('global.save') }} <div wire:loading><x-loading-icon/></div>
            </x-inputs.button-primary>
        </x-slot>
    </x-modal.modal-dialog>
</div>
