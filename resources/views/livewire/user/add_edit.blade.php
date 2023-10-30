<x-slot name="header">
	@if(!$user)
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('employee.create')) }}
		</h2>
	@else
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ \Str::ucfirst(__('employee.edit')) . ' - ' . $name }}
		</h2>
	@endif
</x-slot>

<div class="py-12">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-gray-800">
		<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
			<div class="p-6 bg-white border-gray-200 dark:bg-gray-800">
				<form wire:submit="store" class="flex flex-col gap-8">
					@csrf

					{{-- Nome, Cognome --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							<label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.name')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
									</svg>
								</div>
								<input required type="text" wire:model="name" id="name" placeholder="{{ __('employee.placeholder_name') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('name') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/2">
							<label for="surname" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.surname')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
									</svg>
								</div>
								<input required type="text" name="surname" wire:model="surname" id="surname" placeholder="{{ __('employee.placeholder_surname') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('surname') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Email --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-full">
							<label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.email')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="at-symbol" class="w-5 h-5" />
								</div>
								<input required type="email" wire:model="email" id="email" placeholder="{{ __('employee.placeholder_email') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('email') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>
					
					{{-- Password e Conferma Password --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							@if(!$user)
								<x-inputs.password required wire:model="password" name="password" label="{{ \Str::ucfirst(__('employee.password')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							@else
								<x-inputs.password wire:model="password" name="password" label="{{ \Str::ucfirst(__('employee.password')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							@endif
						</div>
						<div class="w-1/2">
							@if(!$user)
								<x-inputs.password required wire:model="password_confirmation" name="password_confirmation" label="{{ \Str::ucfirst(__('employee.password_confirmation')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							@else
								<x-inputs.password wire:model="password_confirmation" name="password_confirmation" label="{{ \Str::ucfirst(__('employee.password_confirmation')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							@endif
						</div>
					</div>

					<div class="flex w-full sm:justify-center mt-4">
					@if($user)
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
