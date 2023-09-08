<div wire:ignore>
    <div class="form-group">
        <div class="input-group date" id="{{ $attributes['id'] }}">
            <input class="form-control flatpickr flatpickr-input" type="text" {{ $attributes }} data-input>
            <div class="input-group-append" >
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
            $('.flatpickr-input').datetimepicker({
                locale: 'es',
                //format: 'YYYY-MM-DD',
                format: 'DD-MM-YYYY',
                extraFormats: ['YYYY-MM-DD']
            }).on('dp.change', function (e) {
                var formatedValue = e.date.format("YYYY-MM-DD");
                $("#{{$attributes['id']}}").val(formatedValue);
                @this.set('{{ $attributes['wire:model'] }}', formatedValue)
            });
        });
    </script>
@endpush
