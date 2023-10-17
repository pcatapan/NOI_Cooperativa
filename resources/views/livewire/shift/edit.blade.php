<div class="bg-white dark:bg-gray-800 w-full p-4 rounded shadow-lg">
	{{-- È uno straordinario --}}
	<div class="w-full flex justify-end">
		<x-toggle
			left-label="{{ \Str::ucfirst(__('shift.is_extraordinary')) }}"
			wire:model="isExtraordinary"
			md
		/>
	</div>
	<div class="gap-2 flex flex-col">

		{{-- Data --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="w-full">
				<x-datetime-picker
					label="{{ \Str::ucfirst(__('shift.date')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.date_placeholder')) }}"
					display-format="YYYY-MM-DD"
					wire:model.defer="date"
					without-time="true"
				/>
			</div>
		</div>

		{{-- Ora di inizio e ora di fine --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="sm:w-1/2 w-full">
				<x-time-picker
					label="{{ \Str::ucfirst(__('shift.start_time')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.start_time_placeholder')) }}"
					format="24"
					wire:model.defer="start"
				/>
			</div>
			<div class="sm:w-1/2 w-full">
				<x-time-picker
					label="{{ \Str::ucfirst(__('shift.end_time')) }}"
					placeholder="{{ \Str::ucfirst(__('shift.end_time_placeholder')) }}"
					format="24"
					wire:model.defer="end"
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

	<div class="space-x-2 flex justify-end mt-5">
		<x-button class="bg-red-500 hover:bg-red-700 dark:text-white text-black font-bold py-2 px-4 rounded" flat label="{{ __('general.cancel') }}" wire:click="cancel"/>
		<x-button class="dark:text-white outline dark::ring-offset-white text-black ring-offset-black dark:outline-white outline-black" flat label="{{ __('general.confirm') }}" wire:click="confirm"/>
	</div>
</div>