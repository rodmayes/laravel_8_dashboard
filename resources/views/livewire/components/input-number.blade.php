<div class="max-w-xs mx-auto">
    <label for="counter-input" class="form-label" @if($required) required @endif>{{ $label }}:</label>
    <div class="relative flex items-center">
        <button type="button" id="decrement-button" data-input-counter-decrement="counter-input" wire:click="$set('number',{{ $number-- }})"
                class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
            <i class="fa fa-minus"></i>
        </button>
        <input type="text" id="counter-input" data-input-counter placeholder="" wire:model="number" required
               class="flex-shrink-0 text-gray-900 dark:text-white border-0 bg-transparent text-sm font-normal focus:outline-none focus:ring-0 max-w-[2.5rem] text-center"/>
        <button type="button" id="increment-button" data-input-counter-increment="counter-input" wire:click="$set('number',{{ $number++ }})"
                class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 inline-flex items-center justify-center border border-gray-300 rounded-md h-5 w-5 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>
