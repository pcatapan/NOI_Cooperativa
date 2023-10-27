<div class="bg-white dark:bg-gray-800 w-full p-4 rounded">
	{{-- È uno straordinario --}}
	<div class="w-full flex justify-end">
		<x-toggle
			left-label="{{ \Str::ucfirst(__('shift.is_extraordinary')) }}"
			wire:model="isExtraordinary"
			md
		/>
	</div>
	<div class="gap-2 flex flex-col">
		{{-- Selezione dipendenti e Selezione cantiere --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="sm:w-1/2 w-full relative">
				<x-select
					label="{{ \Str::ucfirst(__('shift.employee')) }}"
					wire:model.defer="employee"
					placeholder="{{ __('shift.employee_placeholder') }}"
					:async-data="route('api.employee_by_responsable', ['responsable' => $id])"
					option-label="name"
					option-value="id"
					required
				/>
			</div>
			<div class="sm:w-1/2 w-full">
				<x-select
					label="{{ \Str::ucfirst(__('shift.worksite')) }}"
					wire:model.defer="worksite"
					placeholder="{{ __('shift.worksite_placeholder') }}"
					:async-data="route('api.worksite_by_responsable', ['responsable' => $userEmployee])"
					option-label="cod"
					option-value="id"
					required
				/>
			</div>     
		</div>

		{{-- Data, Ora di inizio e ora di fine --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="sm:w-1/3 w-full">
				<x-datetime-picker
					label="{{ \Str::ucfirst(__('shift.date')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.date_placeholder')) }}"
					display-format="YYYY-MM-DD"
					wire:model.defer="date"
					without-time="true"
					required
				/>
			</div>
			<div class="sm:w-1/3 w-full">
				<x-time-picker
					label="{{ \Str::ucfirst(__('shift.start_time')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.start_time_placeholder')) }}"
					format="24"
					wire:model.defer="startTime"
					required
				/>
			</div>
			<div class="sm:w-1/3 w-full">
				<x-time-picker
					label="{{ \Str::ucfirst(__('shift.end_time')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.end_time_placeholder')) }}"
					format="24"
					wire:model.defer="endTime"
					required
				/>
			</div>
		</div>

		{{-- Note --}}
		<div class="w-full">
			<div class="w-full">
				<x-textarea
					label="{{ \Str::ucfirst(__('shift.notes')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.notes_placeholder')) }}"
					right-icon="pencil"
					rows="2"
					wire:model="note"
				/>
			</div>
		</div>
		
	</div>

	<div class="flex justify-between gap-x-4 mt-4">
		<x-button flat label="{{ \Str::ucfirst(__('general.cancel')) }}" x-on:click="close" />

		<div class="flex">
			<x-button primary label="{{ \Str::ucfirst(__('general.save')) }}" wire:click="createShift" />
		</div>
	</div>
</div>