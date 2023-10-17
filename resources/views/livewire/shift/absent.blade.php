<div class="modal-content bg-white dark:bg-gray-800 w-full p-4 rounded shadow-lg">
	<div class="flex w-full justify-between mb-4">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ \Str::ucfirst(__('shift.modal_absent_title')) }}</h2>
		<button wire:click="cancel" class="modal-close cursor-pointer top-2 right-2 text-gray-700 dark:text-gray-300 text-xl" id="closeModal">&times;</button>
	</div>

	<p class=" text-gray-700 dark:text-gray-300">{{ \Str::ucfirst(__('shift.modal_absent_text', ['name' => $user->name, 'surname' => $user->surname])) }}</p>

	<div class="w-full mt-3">
		<x-native-select
			label="{{ \Str::ucfirst(__('shift.absent_type')) }}"
			:options="[
				['name' => 'Ferie', 'value' => 'holidays'],
				['name' => 'Permessi', 'value' => 'permit'],
				['name' => 'Malattia', 'value' => 'illness'],
				['name' => 'Maternità', 'value' => 'maternity'],
				['name' => 'Paternità', 'value' => 'paternity'],
				['name' => 'Infortunio', 'value' => 'injury'],
				['name' => 'Altro', 'value' => 'other'],
			]"
			option-label="name"
			option-value="value"
			wire:model="typeAbsent"
		/>
		@error('typeAbsent') <span class="error">{{ $message }}</span> @enderror
	</div>

	<div class="flex flex-col mt-4">
		<x-textarea wire:model="note" label="{{ \Str::ucfirst(__('shift.modal_absent_note')) }}" rows="4"/>
		@error('note') <span class="text-red-500">{{ $message }}</span>@enderror
	</div>

	<div class="space-x-2 flex justify-end mt-3">
		<x-button class="bg-red-500 hover:bg-red-700 dark:text-white text-black font-bold py-2 px-4 rounded" flat label="{{ __('general.cancel') }}" wire:click="cancel"/>
		<x-button class="dark:text-white outline dark::ring-offset-white text-black ring-offset-black dark:outline-white outline-black" flat label="{{ __('general.confirm') }}" wire:click="confirm"/>
	</div>
</div>