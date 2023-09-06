<div wire:ignore>
    <div x-data="datepicker(@entangle($attributes->wire('model')))" class="relative">
        <div class="flex flex-col">
            <label>Date</label>
            <div class="flex items-center gap-2">
                <input type="text" x-ref="myDatepicker" x-model="value">
                <span class="cursor-pointer underline" x-on:click="reset">
                <x-icon.x></x-icon.x>
            </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datepicker', (model) => ({
                value: model,
                init(){
                    this.pickr = flatpickr(this.$refs.myDatepicker, {})
                    this.$watch('value', function(newValue){
                        this.pickr.setDate(newValue);
                    }.bind(this));
                },
                reset(){
                    this.value = null;
                }
            }))
        })
    </script>
@endpush
