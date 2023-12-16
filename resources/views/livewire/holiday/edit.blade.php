<div class="bg-white dark:bg-gray-800 w-full p-4 rounded shadow-lg">
	<div class="gap-2 flex flex-col">

		{{-- Nome --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="w-full">
				<x-input
					label="{{ \Str::ucfirst(__('holiday.name')) }}"
					wire:model.defer="name"
				/>
			</div>
		</div>

		{{-- Data --}}
		<div class="w-full flex flex-col sm:flex-row sm:gap-9 gap-2">
			<div class="w-full">
				<x-datetime-picker
					x-data="{}"
					x-init="function() {const modalElement = document.getElementById('modal-container');if (modalElement) {modalElement.classList.remove('overflow-hidden');}}"
					label="{{ \Str::ucfirst(__('holiday.date')) }}"
					display-format="MM-DD"
					wire:model.defer="date"
					without-time="true"
				/>
			</div>
		</div>

		{{-- Cantieri --}}
		<div class="grid grid-cols-3 gap-4 mt-4">
			@foreach($worksites as $key => $worksite)
				<div class="flex items-center">
					<input type="checkbox" wire:model.defer="worksitesSelected" value="{{ $worksite->id }}" id="worksite-{{ $worksite->id }}" class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
					<label for="worksite-{{ $worksite->id }}" class="ml-3 text-sm font-medium dark:text-white">
						{{ $worksite->cod }}
					</label>
				</div>
			@endforeach
		</div>


	<div class="space-x-2 flex justify-end mt-5">
		<x-button class="bg-red-500 hover:bg-red-700 dark:text-white text-black font-bold py-2 px-4 rounded" flat label="{{ __('general.cancel') }}" wire:click="cancel"/>
		<x-button class="dark:text-white outline dark::ring-offset-white text-black ring-offset-black dark:outline-white outline-black" flat label="{{ __('general.confirm') }}" wire:click="confirm"/>
	</div>
</div>