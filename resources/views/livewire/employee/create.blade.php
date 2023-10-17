<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Str::ucfirst(__('employee.employee_create')) }}
        </h2>
    </x-slot>

    <div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 dark:bg-gray-800">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 bg-white border-gray-200 dark:bg-gray-800">
					<form action="{{ route('user.store') }}" method="POST" class="flex flex-col gap-8" x-data="{ passwordStr:'', passwordConfirmStr:'' }">
						@csrf
						<x-errors />

						{{-- Nome, Cognome e Ruolo --}}
						<div class="w-full flex flex-row gap-9">
							<div class="w-1/3">
								<label for="name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.name')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
										</svg>
									</div>
									<input required type="text" name="name" id="name" placeholder="{{ __('employee.placeholder_name') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<label for="surname" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.surname')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
										</svg>
									</div>
									<input required type="text" name="surname" id="surname" placeholder="{{ __('employee.placeholder_surname') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
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
									name="role"
								/>
							</div>
						</div>

						{{-- Email, Numero di Telefono e Data di nascita --}}
						<div class="w-full flex flex-row gap-9">
							<div class="w-1/3">
								<label for="email" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.email')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="at-symbol" class="w-5 h-5" />
									</div>
									<input required type="email" name="email" id="email" placeholder="{{ __('employee.placeholder_email') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<label for="phone" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.phone')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="phone" class="w-5 h-5" />
									</div>
									<input type="tel" name="phone" id="phone" placeholder="{{ __('employee.placeholder_phone') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<x-datetime-picker
									label="{{ \Str::ucfirst(__('employee.birth_date')) }}"
									placeholder=""
									parse-format="DD-MM-YYYY"
									name="birth_date"
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
									<input required type="text" name="job" id="job" placeholder="{{ __('employee.placeholder_job') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<x-datetime-picker
									label="{{ \Str::ucfirst(__('employee.hiring_date')) }}"
									placeholder=""
									parse-format="DD-MM-YYYY"
									name="hiring_date"
									class="mb-2"
									without-time="true"
								/>
							</div>
							<div class="w-1/3 relative">
								<x-icon name="plus-circle" class="w-5 h-5 absolute right-0 text-white z-999 cursor-pointer" id="create_company" onclick="openModalCreateCompany()"/>
								<x-select
									label="{{ \Str::ucfirst(__('employee.company')) }}"
									placeholder="{{ __('employee.placeholder_company') }}"
									name="company">
									@foreach($companySelect as $company)
										<x-select.option label="{{ $company->name }}" value="{{ $company->id }}" />
									@endforeach
								</x-select>
							</div>
						</div>

						{{-- Matricola, Codice fiscale e Matricola Inps --}}
						<div class="w-full flex flex-row gap-9">
							<div class="w-1/3">
								<label for="number_serial" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.number_serial')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="identification" class="w-5 h-5" />
									</div>
									<input type="text" name="number_serial" id="number_serial" placeholder="{{ __('employee.placeholder_number_serial') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<label for="fiscal_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.fiscal_code')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="finger-print" class="w-5 h-5" />
									</div>
									<input required type="text" name="fiscal_code" id="fiscal_code" placeholder="{{ __('employee.placeholder_fiscal_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/3">
								<label for="inps_number" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.inps_number')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="credit-card" class="w-5 h-5" />
									</div>
									<input type="text" name="inps_number" id="inps_number" placeholder="{{ __('employee.placeholder_inps_number') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
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
									<input type="text" name="address" id="address" placeholder="{{ __('employee.placeholder_address') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/2">
								<label for="city" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.city')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-secondary-400">
										<x-icon name="office-building" class="w-5 h-5" />
									</div>
									<input type="text" name="city" id="city" placeholder="{{ __('employee.placeholder_city') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
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
									<input type="text" name="province" id="province" placeholder="{{ __('employee.placeholder_province') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
							<div class="w-1/2">
								<label for="zip_code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Str::ucfirst(__('employee.zip_code')) }}</label>
								<div class="relative rounded-md  shadow-sm ">
									<input type="text" name="zip_code" id="zip_code" placeholder="{{ __('employee.placeholder_zip_code') }}" class="placeholder-secondary-400 dark:bg-secondary-800 dark:text-secondary-400 dark:placeholder-secondary-500 border border-secondary-300 focus:ring-primary-500 focus:border-primary-500 dark:border-secondary-600 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm pl-8">
								</div>
							</div>
						</div>

						{{-- Password e Conferma Password --}}
						<div class="w-full flex flex-row gap-9">
							<div class="w-1/2">
								<x-inputs.password required name="password" label="{{ \Str::ucfirst(__('employee.password')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							</div>
							<div class="w-1/2">
								<x-inputs.password required name="password_confirmation" label="{{ \Str::ucfirst(__('employee.password_confirmation')) }}" placeholder="{{ __('employee.placeholder_password') }}" />
							</div>
						</div>

						<div class="flex w-full sm:justify-center mt-4">
							<x-button class="w-full sm:w-1/2" type="submit" lg icon="check" label="{{ \Str::ucfirst(__('general.save')) }}" x-bind:disabled="passwordStr != passwordConfirmStr" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
