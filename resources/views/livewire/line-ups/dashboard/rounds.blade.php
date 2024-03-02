<div>
    <div class="col-span-6 card flex flex-col">

        <div id="accordion-rounds" data-accordion="collapse" data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white">
            @forelse($rounds as $round)
            <h2 id="accordion-rounds-heading-{{$round->id}}">
                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-800 dark:border-gray-700 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-800 gap-3"
                        data-accordion-target="#accordion-rounds-body-{{$round->id}}" aria-expanded="false" aria-controls="accordion-rounds-body-{{$round->id}}">
                    <span>{{ trans('line-ups.rounds.title') }}{{ $round->round_number }} - {{ $round->match_day->format('d-m-Y') }}</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
            </h2>
            <div id="accordion-rounds-body-{{$round->id}}" class="hidden" aria-labelledby="accordion-rounds-heading-{{$round->id}}">
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                    <p class="mb-2 text-gray-500 dark:text-gray-400">Flowbite is an open-source library of interactive components built on top of Tailwind CSS including buttons, dropdowns, modals, navbars, and more.</p>
                </div>
            </div>
            @empty
                <h1>Empty rounds!</h1>
            @endforelse
        </div>
    </div>
</div>
