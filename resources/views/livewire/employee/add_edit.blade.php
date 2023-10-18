<x-slot name="header">
	@if(!$employee)
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

					{{-- Nome, Cognome e Ruolo --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
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
						<div class="w-1/3">
							<label for="surname" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.surname')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
									</svg>
								</div>
								<input required type="text" wire:model="surname" id="surname" placeholder="{{ __('employee.placeholder_surname') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('surname') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<x-native-select
								label="{{ \Str::ucfirst(__('employee.role')) }}"
								:options="[
									['name' => 'Dipendente', 'id' => 'employee'],
									['name' => 'Responsabile', 'id' => 'responsible'],
								]"
								option-label="name"
								option-value="id"
								wire:model="role"
								required
							/>
							@error('role') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Email, Numero di Telefono e Data di nascita --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.email')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="at-symbol" class="w-5 h-5" />
								</div>
								<input required type="email" wire:model="email" id="email" placeholder="{{ __('employee.placeholder_email') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('email') <span class="error">{{ $message }}</span> @enderror
						</div>
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
							<x-datetime-picker
								label="{{ \Str::ucfirst(__('employee.birth_date')) }}"
								placeholder="{{ __('employee.birth_date') }}"
								parse-format="YYYY-MM-DD"
								wire:model="date_birth"
								without-time="true"
							/>
						</div>
					</div>

					{{-- Job, Data di Assunzione e Azienda --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="job" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.job')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="briefcase" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="job" id="job" placeholder="{{ __('employee.placeholder_job') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
						</div>
						<div class="w-1/3">
							<x-datetime-picker
								label="{{ \Str::ucfirst(__('employee.hiring_date')) }}"
								placeholder=""
								parse-format="YYYY-MM-DD"
								wire:model="date_of_hiring"
								class="mb-2"
								without-time="true"
							/>
						</div>
						<div class="w-1/3 relative">
							<x-icon name="plus-circle" class="w-5 h-5 absolute right-0 text-white z-50 cursor-pointer" id="create_company" wire:click="ModalCreateCompany"/>
							<x-select
								label="{{ \Str::ucfirst(__('employee.company')) }}"
								placeholder="{{ __('employee.placeholder_company') }}"
								wire:model.defer="company"
								:options="$companies"
								option-label="label"
								option-value="value">
							</x-select>
							@error('company') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Matricola, Codice Fiscale e Codice Inps --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="number_serial" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.number_serial')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="identification" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="number_serial" id="number_serial" placeholder="{{ __('employee.placeholder_number_serial') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('number_serial') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="fiscal_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.fiscal_code')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="finger-print" class="w-5 h-5" />
								</div>
								<input required type="text" wire:model="fiscal_code" id="fiscal_code" placeholder="{{ __('employee.placeholder_fiscal_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('fiscal_code') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="inps_number" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.inps_number')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="credit-card" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="inps_number" id="inps_number" placeholder="{{ __('employee.placeholder_inps_number') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('inps_number') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Iban, Monte ore settimanali, permessi settimanali --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/3">
							<label for="iban" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.iban')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="currency-euro" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="iban" id="iban" placeholder="{{ __('employee.placeholder_iban') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('iban') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="work_hour_week_by_contract" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.work_hour_week_by_contract')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="clock" class="w-5 h-5" />
								</div>
								<input type="number" wire:model="work_hour_week_by_contract" id="work_hour_week_by_contract" placeholder="{{ __('employee.placeholder_work_hour_week_by_contract') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('work_hour_week_by_contract') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/3">
							<label for="permission_hour_by_contract" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.permission_hour_by_contract')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="clock" class="w-5 h-5" />
								</div>
								<input type="number" wire:model="permission_hour_by_contract" id="permission_hour_by_contract" placeholder="{{ __('employee.placeholder_permission_hour_by_contract') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('permission_hour_by_contract') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Indirizzo e Città --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							<label for="address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.address')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="home" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="address" id="address" placeholder="{{ __('employee.placeholder_address') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('address') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/2">
							<label for="city" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.city')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="office-building" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="city" id="city" placeholder="{{ __('employee.placeholder_city') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('city') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>
					
					{{-- Provincia e CAP --}}
					<div class="w-full flex flex-row gap-9">
						<div class="w-1/2">
							<label for="province" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.province')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
									<x-icon name="template" class="w-5 h-5" />
								</div>
								<input type="text" wire:model="province" id="province" placeholder="{{ __('employee.placeholder_province') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('province') <span class="error">{{ $message }}</span> @enderror
						</div>
						<div class="w-1/2">
							<label for="zip_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.zip_code')) }}</label>
							<div class="relative rounded-md  shadow-sm ">
								<input type="text" wire:model="zip_code" id="zip_code" placeholder="{{ __('employee.placeholder_zip_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
							</div>
							@error('zip_code') <span class="error">{{ $message }}</span> @enderror
						</div>
					</div>

					{{-- Password e Conferma Password --}}
					@if(!$employee)
						<div class="w-full flex flex-row gap-9">
							<div class="w-1/2">
								<x-inputs.password required wire:model="password" label="{{ \Str::ucfirst(__('employee.password')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							</div>
							<div class="w-1/2">
								<x-inputs.password required wire:model="password_confirmation" label="{{ \Str::ucfirst(__('employee.password_confirmation')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							</div>
						</div>
					@endif

					<div class="flex w-full sm:justify-center mt-4">
						@if($employee)
							<x-button class="w-full sm:w-1/2" type="submit" lg icon="check" label="{{ \Str::ucfirst(__('general.edit')) }}"/>
						@else
							<x-button class="w-full sm:w-1/2" type="submit" lg icon="plus" label="{{ \Str::ucfirst(__('general.add')) }}"/>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Modale di creazione Azienda --}}
	<x-modal.card title="{{ \Str::ucfirst(__('company.create')) }}" blur wire:model.defer="showModalCreateCompany">
		<form wire:submit="createCompany" class="flex flex-col gap-4">
			
			{{-- Nome e Partita IVA --}}
			<div class="w-full flex flex-row gap-9">
				<div class="w-1/2">
					<label for="company_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.name')) }}</label>
					<input required type="text" wire:model="company_name" id="company_name" placeholder="{{ __('company.placeholder_name') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					@error('company_name') <span class="error">{{ $message }}</span> @enderror
				</div>
				<div class="w-1/2">
					<label for="vat_number" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.vat_number')) }}</label>
					<input required type="text" wire:model="vat_number" id="vat_number" placeholder="{{ __('company.placeholder_vat_number') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					@error('vat_number') <span class="error">{{ $message }}</span> @enderror
				</div>
			</div>

			{{-- Pec, Numero di Telefono --}}
			<div class="w-full flex flex-row gap-9">
				<div class="w-1/2">
					<label for="pec" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.pec')) }}</label>
					<div class="relative rounded-md  shadow-sm ">
						<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
							<x-icon name="at-symbol" class="w-5 h-5" />
						</div>
						<input required type="pec" wire:model="pec" id="pec" placeholder="{{ __('company.placeholder_pec') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					</div>
					@error('pec') <span class="error">{{ $message }}</span> @enderror
				</div>
				<div class="w-1/2">
					<label for="company_phone" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.phone')) }}</label>
					<div class="relative rounded-md  shadow-sm ">
						<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
							<x-icon name="phone" class="w-5 h-5" />
						</div>
						<input type="tel" wire:model="company_phone" id="company_phone" placeholder="{{ __('company.placeholder_phone') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					</div>
					@error('company_phone') <span class="error">{{ $message }}</span> @enderror
				</div>
			</div>

			{{-- Indirizzo e Città --}}
			<div class="w-full flex flex-row gap-9">
				<div class="w-full flex flex-row gap-9">
					<div class="w-1/2">
						<label for="company_address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.address')) }}</label>
						<div class="relative rounded-md  shadow-sm ">
							<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
								<x-icon name="home" class="w-5 h-5" />
							</div>
							<input type="text" wire:model="company_address" id="company_address" placeholder="{{ __('company.placeholder_address') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
						</div>
						@error('company_address') <span class="error">{{ $message }}</span> @enderror
					</div>
					<div class="w-1/2">
						<label for="company_city" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.city')) }}</label>
						<div class="relative rounded-md  shadow-sm ">
							<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
								<x-icon name="office-building" class="w-5 h-5" />
							</div>
							<input type="text" wire:model="company_city" id="company_city" placeholder="{{ __('company.placeholder_city') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
						</div>
						@error('company_city') <span class="error">{{ $message }}</span> @enderror
					</div>
				</div>
			</div>

			{{-- Provincia e CAP --}}
			<div class="w-full flex flex-row gap-9">
				<div class="w-1/2">
					<label for="company_province" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.province')) }}</label>
					<div class="relative rounded-md  shadow-sm ">
						<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
							<x-icon name="template" class="w-5 h-5" />
						</div>
						<input type="text" wire:model="company_province" id="company_province" placeholder="{{ __('company.placeholder_province') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					</div>
					@error('company_province') <span class="error">{{ $message }}</span> @enderror
				</div>
				<div class="w-1/2">
					<label for="company_zip_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('company.zip_code')) }}</label>
					<div class="relative rounded-md  shadow-sm ">
						<input type="text" wire:model="company_zip_code" id="company_zip_code" placeholder="{{ __('company.placeholder_zip_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
					</div>
					@error('company_zip_code') <span class="error">{{ $message }}</span> @enderror
				</div>
			</div>

		</form>
	
		<x-slot name="footer">
			<div class="flex justify-between gap-x-4">
				<x-button flat label="{{ \Str::ucfirst(__('general.cancel')) }}" x-on:click="close" />
	
				<div class="flex">
					<x-button primary label="{{ \Str::ucfirst(__('general.save')) }}" wire:click="createCompany" />
				</div>
			</div>
		</x-slot>
	</x-modal.card>
</div>
