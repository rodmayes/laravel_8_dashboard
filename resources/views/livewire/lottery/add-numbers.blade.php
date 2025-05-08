<x-drawer id="drawer-add-numbers-sidebar" position="right" width="80">
    <x-slot:title>
        Add lottery numbers
    </x-slot:title>

    <form action="#">
        <div class="space-y-4">
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
                                    <button type="button" wire:click="toggleNumber({{ $i }})"
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
        </div>
    </form>

    <x-slot:footer>
        <div class="bottom-0 left-0 flex justify-center w-full pb-4 mt-4 space-x-4 sm:absolute sm:px-4 sm:mt-0">
            <button
                type="button"
                data-drawer-dismiss="drawer-add-numbers-sidebar"
                aria-controls="drawer-add-numbers-sidebar"
                class="ml-2 mr-2 w-full justify-center text-gray-600 inline-flex items-center hover:text-white border border-gray-600 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
            >
                Cancelar
            </button>
            <button type="button" class="mr-2 w-full justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                Guardar
            </button>
        </div>
    </x-slot:footer>
</x-drawer>
