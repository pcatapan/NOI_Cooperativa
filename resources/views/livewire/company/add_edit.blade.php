<x-slot name="header">
	@if(!$company)
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('company.create')) }}
		</h2>
	@else
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('company.edit')) . ' - ' . $name }}
		</h2>
	@endif
</x-slot>

<div class="py-12">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-gray-800">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="p-6 bg-white border-gray-200 dark:bg-gray-800">
				<form wire:submit="store" class="flex flex-col gap-8">
					@csrf

					{{-- Nome, P.iva e PEC --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.name')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="user" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="name" id="name" placeholder="{{ __('company.placeholder_name') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('name') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="vat_number" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.vat_number')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="hashtag" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="vat_number" id="vat_number" placeholder="{{ __('company.placeholder_vat_number') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('vat_number') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="pec" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.pec')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="at-symbol" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="pec" id="pec" placeholder="{{ __('company.placeholder_pec') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('pec') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Telefono, Indirizzo e Città --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="phone" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.phone')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="phone" class="w-5 h-5" />
								</div>
								<input type="tel" wire:model="phone" id="phone" placeholder="{{ __('employee.placeholder_phone') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('phone') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.address')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="home" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="address" id="address" placeholder="{{ __('company.placeholder_address') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('address') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="city" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.city')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="office-building" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="city" id="city" placeholder="{{ __('company.placeholder_city') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('city') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>
					
					{{-- Provincia e CAP --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							<label for="province" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.province')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="template" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="province" id="province" placeholder="{{ __('company.placeholder_province') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('province') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/2">
							<label for="zip_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.zip_code')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<input type="text" wire:model="zip_code" id="zip_code" placeholder="{{ __('company.placeholder_zip_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('zip_code') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					<div class="flex w-full sm:justify-center mt-4">
						@if($company)
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
