@props(['items_footer'])

<nav class="flex items-center justify-between p-4 " aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> <span class="font-semibold text-gray-900 dark:text-white">{{$items_footer->currentPage()}}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $items_footer->total() }}</span></span>
@if(isset($selectedCount))
    <p class="text-sm leading-5">
        {{ $selectedCount }}
        {{ __('Entries selected') }}
    </p>
@endif
<ul class="inline-flex -space-x-px text-sm h-8">
    <li>
        <a href="{{$items_footer->previousPageUrl()}}" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
    </li>
    @foreach($items_footer->getUrlRange(1,ceil($items_footer->total()/$items_footer->perPage())) as $index => $page)
        <li>
            <a href="{{$items_footer->url($index)}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{$index}}</a>
        </li>
    @endforeach
    <li>
        <a href="{{$items_footer->nextPageUrl()}}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
    </li>
</ul>
</nav>
