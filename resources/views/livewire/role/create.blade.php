<div class="w-2/3 p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
    <h1 class="h3">Create Role</h1>
    <form wire:submit.prevent="submit" class="pt-3">
        <div class="w-full">
            <div class="{{ $errors->has('role.title') ? 'invalid' : '' }}">
                <label class="form-label required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                <x-input name="title" id="title" required wire:model.defer="role.title"/>
                <div class="validation-message">
                    {{ $errors->first('role.title') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.role.fields.title_helper') }}
                </div>
            </div>
            <div class="{{ $errors->has('permissions') ? 'invalid' : '' }}">
                <label class="form-label required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <div class="w-full">
                    <div id="accordion-collapse" data-accordion="collapse">
                        @foreach($listsForFields['permissions'] as $key => $section)
                            <h2 id="accordion-collapse-heading-{{ $loop->index }}">
                                <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                        data-accordion-target="#accordion-collapse-body-{{ $loop->index }}" aria-expanded="false" aria-controls="accordion-collapse-body-{{ $loop->index }}">
                                    <span>{{ ucwords($key) }}</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-collapse-body-{{ $loop->index }}" class="hidden" aria-labelledby="accordion-collapse-heading-{{ $loop->index }}">
                                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">
                                    <ul class=" grid grid-cols-5 items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @forelse($section as $permission)
                                            <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                                                <div class="flex items-center ps-3">
                                                    <input id="{{$permission['title']}}-list" type="checkbox" value="{{ $permission['id'] }}" @if(in_array($permission['id'], $permissions)) checked @endif wire:model.defer="permissions"
                                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                                    <label for="{{$permission['title']}}-list" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $permission['label'] }}</label>
                                                </div>
                                            </li>
                                        @empty
                                            <p>No entries</p>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="validation-message">
                    {{ $errors->first('permissions') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.role.fields.permissions_helper') }}
                </div>
            </div>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ trans('global.save') }}
            </button>
            <a href="{{ route('user_management.roles.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.cancel') }}
            </a>
        </div>
    </form>
</div>
