<div>
    <div wire:ignore class="col-12">
        <select class="select2 form-control" data-minimum-results-for-search="Infinity" data-placeholder="{{ __('Select your option') }}" {{ $attributes }}>
            @if(!isset($attributes['multiple']))
                <option></option>
            @endif
            @foreach($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
            let el = $('#{{ $attributes['id'] }}');

            el.select2({
                placeholder: '{{ __('Select your option') }}',
                allowClear: !el.attr('multiple')
            }).on('change', function (e) {
                let data = $(this).select2("val")
                @this.set('{{ $attributes['wire:model'] }}', data)
            });

/*            Livewire.hook('message.processed', (message, component) => {
                initSelect()
            });
*/

        });
    </script>
@endpush
