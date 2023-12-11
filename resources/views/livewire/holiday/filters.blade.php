<div class="flex gap-4 mb-4 items-end flex-col sm:flex-row sm:p-0 p-4">
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
			option-label="cod"
			option-value="id"
		/>
	@endif

	<x-button primary label="{{ \Str::ucfirst(__('general.search')) }}" class="max-h-10 sm:w-auto w-full" wire:click="search"/>
</div>