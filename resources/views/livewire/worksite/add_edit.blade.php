<x-slot name="header">
	@if(!$worksite)
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('worksite.create')) }}
		</h2>
	@else
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('worksite.edit')) . ' - ' . $cod }}
		</h2>
	@endif
</x-slot>

<div class="py-12">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-gray-800">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="p-6 bg-white border-gray-200 dark:bg-gray-800">
				<form wire:submit="store" class="flex flex-col gap-8">
					@csrf

					{{-- Cod --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-full">
							<label for="cod" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.cod')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="hashtag" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="cod" id="cod" placeholder="{{ __('worksite.placeholder_cod') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('cod') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Indirizzo, Città, Provincia e CAP --}}
					<div class="w-full flex flex-row sm:gap-9 gap-3">
						<div class="w-1/3">
							<label for="address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.address')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
									<x-icon name="home" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="address" id="address" placeholder="{{ __('worksite.placeholder_address') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2">
							</div>
							@error('address') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/4">
							<label for="city" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.city')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
									<x-icon name="office-building" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="city" id="city" placeholder="{{ __('worksite.placeholder_city') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2">
							</div>
							@error('city') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/4">
							<label for="province" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.province')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
									<x-icon name="template" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="province" id="province" placeholder="{{ __('worksite.placeholder_province') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2">
							</div>
							@error('province') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/4">
							<label for="zip_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.zip_code')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<input type="text" wire:model="zip_code" id="zip_code" placeholder="{{ __('worksite.placeholder_zip_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-1">
							</div>
							@error('zip_code') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>
					
					{{-- Azienda, Responsabile e Dipendenti --}}
					<div class="w-full flex flex-row gap-9">
						<x-select
							label="{{ \Str::ucfirst(__('worksite.company')) }}"
							wire:model.defer="id_company"
							placeholder="{{ __('worksite.placeholder_company') }}"
							:async-data="route('api.companies')"
							option-label="name"
							option-value="id"
							class="w-1/3"
						/>

						<x-select
							label="{{ \Str::ucfirst(__('worksite.responsible')) }}"
							wire:model.defer="id_responsable"
							placeholder="{{ __('worksite.placeholder_responsible') }}"
							:async-data="route('api.responsible')"
							option-label="name"
							option-value="id"
							class="w-1/3"
						/>

						<x-select
							label="{{ \Str::ucfirst(__('worksite.employees')) }}"
							wire:model.defer="employees"
							placeholder="{{ __('worksite.placeholder_employees') }}"
							:async-data="route('api.employee')"
							option-label="name"
							option-value="id"
							:multiselect="true"
							class="w-1/3"
						/>
					</div>

					{{-- Ore totali e Totale Straordinari --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							<label for="total_hours" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.total_hours')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
									<x-icon name="clock" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="total_hours" id="total_hours" placeholder="{{ __('worksite.placeholder_total_hours') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2">
							</div>
							@error('total_hours') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/2">
							<label for="total_hours_extraordinary" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.total_hours_extraordinary')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
									<x-icon name="clock" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="total_hours_extraordinary" id="total_hours_extraordinary" placeholder="{{ __('worksite.placeholder_total_hours_extraordinary') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('total_hours_extraordinary') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Descrizione --}}
					<div class="w-full">
						<label for="description" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.description')) }}</label>
						<div class="relative rounded-md  shadow-sm ">
							<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
								<x-icon name="document-text" class="w-5 h-5" />
							</div>
							<textarea required wire:model="description" id="description" placeholder="{{ __('worksite.placeholder_description') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-textarea block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2"></textarea>
						</div>
						@error('description') <span class="error">{{ $message }}</span> @enderror
					</div>

					{{-- Note --}}
					<div class="w-full">
						<label for="notes" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('worksite.notes')) }}</label>
						<div class="relative rounded-md  shadow-sm ">
							<div class="absolute inset-y-0 left-0 pl-2.5 sm:flex hidden items-center pointer-events-none text-secondary-400">
								<x-icon name="document-text" class="w-5 h-5" />
							</div>
							<textarea wire:model="notes" id="notes" placeholder="{{ __('worksite.placeholder_notes') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-textarea block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm sm:pl-8 pl-2"></textarea>
						</div>
						@error('notes') <span class="error">{{ $message }}</span> @enderror
					</div>

					<div class="flex w-full sm:justify-center mt-4">
						@if($worksite)
							<x-button class="w-full sm:w-1/2" type="submit" lg icon="check" label="{{ \Str::ucfirst(__('general.edit')) }}"/>
						@else
							<x-button class="w-full sm:w-1/2" type="submit" lg icon="plus" label="{{ \Str::ucfirst(__('general.add')) }}"/>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
