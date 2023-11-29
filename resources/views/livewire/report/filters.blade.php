<div class="flex gap-4 mb-4 items-end flex-col sm:flex-row sm:p-0 p-4">
	{{-- Dal --}}
	<x-datetime-picker
		label="{{ \Str::ucfirst(__('report.from_date')) }}"
		placeholder="{{ \Str::ucfirst(__('shift.date_placeholder')) }}"
		display-format="DD/MM/YYYY"
		wire:model.defer="from_date"
		without-time="true"
	/>

	{{-- Al --}}
	<x-datetime-picker
		label="{{ \Str::ucfirst(__('report.to_date')) }}"
		placeholder="{{ \Str::ucfirst(__('shift.date_placeholder')) }}"
		display-format="DD/MM/YYYY"
		wire:model.defer="to_date"
		without-time="true"
	/>

	{{-- Seleziona dipendenrte --}}
	@if (!$notShowEmployee)
		<x-select
			label="{{ \Str::ucfirst(__('shift.employee')) }}"
			wire:model.defer="employee"
			placeholder="{{ \Str::ucfirst(__('employee.placeholder_name')) }}"
			:async-data="route('api.employee')"
			option-label="name"
			option-value="id"
		/>
	@endif

	{{-- Seleziona cantiere --}}
	@if ($isResponsible)
		<x-select
			label="{{ \Str::ucfirst(__('report.worksite')) }}"
			wire:model.defer="worksite"
			placeholder="{{ \Str::ucfirst(__('worksite.placeholder_name')) }}"
			:async-data="route('api.worksite_by_responsable', ['responsable' => $userId])"
			option-label="cod"
			option-value="id"
		/>
	@else
		<x-select
			label="{{ \Str::ucfirst(__('report.worksite')) }}"
			wire:model.defer="worksite"
			placeholder="{{ \Str::ucfirst(__('worksite.placeholder_name')) }}"
			:async-data="route('api.worksite')"
			option-label="name"
			option-value="id"
		/>
	@endif

	{{-- Seleziona Azienda --}}
	<x-select
		label="{{ \Str::ucfirst(__('report.company')) }}"
		wire:model.defer="company"
		placeholder="{{ \Str::ucfirst(__('company.placeholder_name')) }}"
		:async-data="route('api.companies')"
		option-label="name"
		option-value="id"
	/>

	<x-button primary label="{{ \Str::ucfirst(__('general.search')) }}" class="max-h-10 sm:w-auto w-full" wire:click="search"/>
</div>