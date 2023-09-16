<div>
    <div wire:ignore class="col-12">
        <select class="select2 form-control w-100" data-minimum-results-for-search="Infinity" data-placeholder="{{ __('Select your option') }}" {{ $attributes }}>
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
                width: '100%',
                placeholder: '{{ __('Select your option') }}',
                allowClear: {!! isset($attributes['multiple']) ? 'true' : 'false' !!},
                tags: {!! isset($attributes['tags']) ? 'true' : 'false' !!},
            }).on('select2:select select2:unselect', function(e){
                var elm = e.params.data.element;
                $elm = jQuery(elm);
                $t = jQuery(this);
                $t.append($elm);
                $t.trigger('change.select2');
                let data = $(this).select2("val")
                @this.set('{{ $attributes['wire:model'] }}', data);
            });
        });
    </script>
@endpush
