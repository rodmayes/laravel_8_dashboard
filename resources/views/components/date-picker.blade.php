<div wire:ignore>
    @if(isset($attributes['inline']))
        <div style="overflow:hidden;">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div id="{{ $attributes['id'] }}" wire:model="{{$attributes['wire:model']}}" class="flatpickr-input"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="form-group">
            <div class="input-group date" id="{{ $attributes['id'] }}">
                <input class="form-control flatpickr flatpickr-input" type="text" {{ $attributes }} data-input>
                <div class="input-group-append" >
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
            $('.flatpickr-input').datetimepicker({
                defaultDate: '{!! isset($attributes['value']) ? $attributes['value'] : null !!}',
                locale: 'es',
                //format: 'YYYY-MM-DD',
                format: 'DD-MM-YYYY',
                extraFormats: ['YYYY-MM-DD', 'DD-MM-YYYY'],
                showTodayButton: true,
                showClear: true,
                showClose: true,
                inline: {!! isset($attributes['inline']) ? 'true' : 'false' !!},
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-caret-up',
                    down: 'fa fa-caret-down',
                    previous: 'fa fa-angle-left',
                    next: 'fa fa-angle-right',
                    today: 'fa fa-calendar-day',
                    clear: 'fa fa-trash',
                    close: 'fa fa-plus'
                },
            }).on('dp.change', function (e) {
                var formatedValue = e.date.format("DD-MM-YYYY");
               // $("#{{$attributes['id']}}").setValue(formatedValue);
                $("#{{$attributes['id']}}").val(formatedValue);
                @this.set('{{ $attributes['wire:model'] }}', formatedValue)
            });
        });
    </script>
@endpush
