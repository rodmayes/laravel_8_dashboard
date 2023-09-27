<div class="w-2/3 p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
        <h1 class="h3">Edit User</h1>
        <form wire:submit.prevent="submit" class="pt-3">
            <div class="w-full">
                        <h1 class="h5">App data
                        <button type="button" wire:click="refreshData"
                                class="px-3 py-2 text-xs text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
                            <i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Data
                        </button>
                        </h1>
                        <div wire:loading wire:target="refreshData">
                            Processing Refresh...
                        </div>
            </div>
                <div class="grid grid-cols-3">
                    <div class="{{ $errors->has('user.name') ? 'invalid' : '' }} mr-2">
                        <label class="form-label required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                        <x-input name="name" id="name" required wire:model.defer="user.name"/>
                        <div class="text-danger">
                            {{ $errors->first('user.name') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.name_helper') }}
                        </div>
                    </div>

                    <div class="{{ $errors->has('user.email') ? 'invalid' : '' }} mr-2">
                        <label class="form-label required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                        <x-input  name="email" id="email" required wire:model.defer="user.email"/>
                        <div class="text-danger">
                            {{ $errors->first('user.email') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.email_helper') }}
                        </div>
                    </div>
                    <div class="{{ $errors->has('user.password') ? 'invalid' : '' }}">
                        <label class="form-label" for="password">{{ trans('cruds.user.fields.password') }}</label>
                        <x-input name="password" id="password" wire:model.defer="password">
                            <x-slot name="append">
                                <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                    <x-button
                                        wire:click="storePassword"
                                        class="h-full rounded-r-md"
                                        icon="save"
                                        primary
                                        flat
                                        squared
                                    />
                                </div>
                            </x-slot>
                        </x-input>
                        <div class="text-danger">
                            {{ $errors->first('user.password') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.password_helper') }}
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group {{ $errors->has('roles') ? 'invalid' : '' }}">
                        <label class="form-label required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                        <x-select
                            placeholder="Select many roles"
                            multiselect
                            :options="$this->listsForFields['roles']"
                            wire:model.defer="roles"
                            option-value="id"
                            option-label="title"
                        />
                        <div class="text-danger">
                            {{ $errors->first('roles') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.roles_helper') }}
                        </div>
                    </div>
                </div>
                <div class="w-full mt-2">
                    <h1 class="h5">
                        Playtomic data
                        <button
                            class="px-3 py-2 text-xs text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                            wire:click="refreshToken" type="button"><i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Playtomic Token</button>
                        <div wire:loading wire:target="refreshToken">
                            Processing Refresh...
                        </div>

                    </h1>
                </div>
                <div class="grid grid-cols-2">
                    <div class="{{ $errors->has('user.playtomic_id') ? 'invalid' : '' }} mr-2">
                        <label class="form-label" for="playtomic_id">{{ trans('playtomic.clubs.fields.playtomic_id') }}</label>
                        <x-input name="playtomic_id" id="playtomic_id" wire:model.defer="user.playtomic_id"/>
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_id') }}
                        </div>
                        <div class="help-block">
                            {{ trans('playtomic.clubs.fields.playtomic_id_helper') }}
                        </div>
                    </div>
                    <div class="{{ $errors->has('user.playtomic_password') ? 'invalid' : '' }}">
                        <label class="form-label required" for="playtomic_password">{{ trans('cruds.user.fields.playtomic_password') }}</label>
                        <x-input name="playtomic_password" id="v" wire:model.defer="playtomic_password">
                            <x-slot name="append">
                                <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                                    <x-button
                                        wire:click="storePlaytomicPassword"
                                        class="h-full rounded-r-md"
                                        icon="save"
                                        primary
                                        flat
                                        squared
                                    />
                                </div>
                            </x-slot>
                        </x-input>
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_password') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.playtomic_password_helper') }}
                        </div>
                    </div>
                    <div class="w-full m-2 {{ $errors->has('user.playtomic_token') ? 'invalid' : '' }}">
                        <label class="form-label" for="playtomic_token">{{ trans('cruds.user.fields.playtomic_token') }}</label>
                        <p>{{ $this->user->playtomic_token }}</p>
                        <div class="text-danger">
                            {{ $errors->first('user.playtomic_token') }}
                        </div>
                        <div class="help-block">
                            {{ trans('cruds.user.fields.playtomic_token_helper') }}
                        </div>
                    </div>
                </div>
                <div class="w-full m-2 {{ $errors->has('user.playtomic_refresh_token') ? 'invalid' : '' }}">
                    <label class="form-label" for="playtomic_refresh_token">{{ trans('cruds.user.fields.playtomic_refresh_token') }}</label>
                    <p>{{ $this->user->playtomic_refresh_token }}</p>
                    <div class="text-danger">
                        {{ $errors->first('user.playtomic_refresh_token') }}
                    </div>
                    <div class="help-block">
                        {{ trans('cruds.user.fields.playtomic_token_helper') }}
                    </div>
                </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="text-white bg-green-700 hover:bg-green-200 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.save') }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </a>
            </div>
        </form>
    </div>
