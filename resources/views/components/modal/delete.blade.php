<div class="modal-content bg-white dark:bg-gray-800 w-full p-4 rounded shadow-lg">
	<div class="flex w-full justify-between mb-4">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $confirmationTitle }}</h2>
		<button wire:click="cancel" class="modal-close cursor-pointer top-2 right-2 text-gray-700 dark:text-gray-300 text-xl" id="closeModal">&times;</button>
	</div>

	<p class=" text-gray-700 dark:text-gray-300">{{ $confirmationDescription }}</p>

	<div class="space-x-2 flex justify-end mt-3">
		<x-button class="bg-red-500 hover:bg-red-700 dark:text-white text-black font-bold py-2 px-4 rounded" flat label="{{ __('general.cancel') }}" wire:click="cancel"/>
		<x-button class="dark:text-white outline dark::ring-offset-white text-black ring-offset-black dark:outline-white outline-black" flat label="{{ __('general.confirm') }}" wire:click="confirm"/>
	</div>
</div>