<div class="w-2/3 sm:w-full p-4 border border-gray-200 bg-white rounded-t-xl dark:border-gray-600 dark:bg-gray-700">
    <h1 class="h3">
        Edit User
        <img src="{{ asset($user->getAvatar()) }}" class="float-right rounded w-36 h-36" wire:model="image">
        <div class="grid grid-cols-1 float-right">
            <button type="button" wire:click="refreshData"
                    class="float-right px-3 py-2 text-xs text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
                <i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Data
            </button>
            <button type="button" wire:click="refreshData" data-modal-target="modal-avatar" data-modal-toggle="modal-avatar"
                    class="float-right px-3 py-2 text-xs text-indigo-400 hover:text-white border border-indigo-400 hover:bg-indigo-500 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
                <i class="fa fa-image" title="Refresh avatar"></i> Change avatar
            </button>
        </div>
    </h1>
    <form wire:submit.prevent="submit" class="pt-3">
        <div class="w-full">
                    <h1 class="h5 align-content-end">App data</h1>
                    <div wire:loading wire:target="refreshData">
                        Processing Refresh...
                    </div>
        </div>
        <div class="w-full grid grid-cols-3">
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
                <x-input name="password" id="password" wire:model.defer="password" type="password">
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
        <div class="w-full mt-6">
            <h1 class="h5">
                Playtomic data
                <button
                    class="float-right px-3 py-2 text-xs text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                    wire:click="refreshToken" type="button"><i class="fa fa-sync" title="Refresh Playtomic Token"></i> Refresh Playtomic Token</button>
                <div wire:loading wire:target="refreshToken">
                    Processing Refresh...
                </div>

            </h1>
        </div>
        <div class="w-full grid grid-cols-2">
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
        </div>

        <!-- Playtomic Token -->
        <div class="grid grid-cols-1">
            <div class="m-2 {{ $errors->has('user.playtomic_token') ? 'invalid' : '' }}">
                <div class="">
                    <div class="mb-2 flex justify-between items-center">
                        <label class="form-label" for="playtomic_token">{{ trans('cruds.user.fields.playtomic_token') }}</label>
                    </div>
                    <div class="relative bg-gray-50 rounded-lg dark:bg-gray-700 p-2">
                        <div class="overflow-scroll max-h-full">
                            <p id="code-block-token" class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre">{{ $this->user->playtomic_token }}</p>
                        </div>
                        <div class="absolute top-2 end-2 bg-gray-50 dark:bg-gray-700">
                            <button data-copy-to-clipboard-target="code-block-token" data-copy-to-clipboard-content-type="innerHTML" data-copy-to-clipboard-html-entities="true" type="button"
                                    class="text-gray-900 dark:text-gray-400 m-0.5 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 rounded-lg py-2 px-2.5 inline-flex items-center justify-center bg-white border-gray-200 border">
                            <span id="default-message" class="inline-flex items-center">
                                <i class="fa fa-clipboard mr-1"></i>
                                <span class="text-xs font-semibold"></span>
                            </span>
                            <span id="success-message" class="hidden inline-flex items-center">
                            <svg class="w-3 h-3 text-blue-700 dark:text-blue-500 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                            <span class="text-xs font-semibold text-blue-700 dark:text-blue-500">Copied</span>
                        </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-danger">
                    {{ $errors->first('user.playtomic_token') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.playtomic_token_helper') }}
                </div>
            </div>
        </div>
        <!-- End Playtomic Token -->

        <!-- Playtomic Token Refresh -->
        <div class="grid grid-cols-1">
            <div class="m-2 {{ $errors->has('user.playtomic_token_refresh') ? 'invalid' : '' }}">
                <div class="">
                    <div class="mb-2 flex justify-between items-center">
                        <label class="form-label" for="playtomic_token">{{ trans('cruds.user.fields.playtomic_refresh_token') }}</label>
                    </div>
                    <div class="relative bg-gray-50 rounded-lg dark:bg-gray-700 p-2">
                        <div class="overflow-scroll max-h-full">
                            <p id="code-block-token-refresh" class="text-sm text-gray-500 dark:text-gray-400 whitespace-pre">{{ $this->user->playtomic_refresh_token }}</p>
                        </div>
                        <div class="absolute top-2 end-2 bg-gray-50 dark:bg-gray-700">
                            <button data-copy-to-clipboard-target="code-block-token-refresh" data-copy-to-clipboard-content-type="innerHTML" data-copy-to-clipboard-html-entities="true" type="button"
                                    class="text-gray-900 dark:text-gray-400 m-0.5 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 rounded-lg py-2 px-2.5 inline-flex items-center justify-center bg-white border-gray-200 border">
                            <span id="default-message" class="inline-flex items-center">
                                <i class="fa fa-clipboard mr-1"></i>
                                <span class="text-xs font-semibold"></span>
                            </span>
                                <span id="success-message" class="hidden inline-flex items-center">
                            <svg class="w-3 h-3 text-blue-700 dark:text-blue-500 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                            <span class="text-xs font-semibold text-blue-700 dark:text-blue-500">Copied</span>
                        </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-danger">
                    {{ $errors->first('user.playtomic_refresh_token') }}
                </div>
                <div class="help-block">
                    {{ trans('cruds.user.fields.playtomic_token_helper') }}
                </div>
                <!-- End Playtomic Token Refresh -->
            </div>
        </div>
        <div class="w-full items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
            <button type="submit" class="float-right text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ trans('global.save') }}
            </button>
            <a href="{{ route('user_management.users.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                {{ trans('global.cancel') }}
            </a>
        </div>
    </form>
</div>

<div id="modal-avatar" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full" wire:ignore.self>
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Crop avatar
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal-avatar">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                @error('image')
                <div class="alert alert-danger show">{{ $message }}</div>
                @enderror

                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, WEBP or GIF (MAX. 800x400px)</p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden"/>
                    </label>
                </div>

            @if(session('success_msg'))
                    <div class="card-footer">
                        <div class="alert alert-success show">{{ session('success_msg') }}</div>
                        @if(session('uploaded_img'))<img src="{{session('uploaded_img')}}" height="50px" width="50px">@endif
                    </div>
                @endif
            </div>
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <a href=""
                   type="button" class="text-white bg-teal-400 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{ trans('global.edit') }}
                </a>
                <button data-modal-hide="modal-avatar" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    {{ trans('global.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ijaboCropTool.min.css') }}" />
@endpush
@push('scripts')
    <script src="{{ asset('js/ijaboCropTool.min.js') }}"></script>
    <script>
        $('#dropzone-file').ijaboCropTool({
            fileName: 'avatar',
            preview : '.image-previewer',
            setRatio:1,
            allowedExtensions: ['jpg', 'jpeg','png','webp'],
            buttonsText:['CROP','QUIT'],
            buttonsColor:['#30bf7d','#ee5155', -15],
            processUrl:'{{route('admin.user.set-avatar', $user)}}',
            withCSRF: ['_token','{{ csrf_token() }}'],
            onSuccess:function(message, element, status){
                $('#modal-avatar').hide();
                $('.ijabo-cropper-modal').hide();
                $('[modal-backdrop]').remove();
            },
            onError:function(message, element, status){
                $('#modal-avatar').hide();
                $('.ijabo-cropper-modal').hide();
                $('[modal-backdrop]').remove();
            }
        });
    </script>
@endpush
