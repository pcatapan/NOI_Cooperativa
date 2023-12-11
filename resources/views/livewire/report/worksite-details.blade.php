<div class="bg-white dark:bg-gray-800 w-full p-4 rounded overflow-scroll">
	<div class="flex w-full justify-between">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ \Str::ucfirst(__('report.details_title', ['name' => $name])) }}</h2>
		<button wire:click="cancel" class="modal-close cursor-pointer top-2 right-2 text-gray-700 dark:text-gray-300 text-xl" id="closeModal">&times;</button>
	</div>

	{{-- Tabella con i dettagli delle presenze --}}
	@include('components.legend')

	<div class="w-full overflow-x-auto hidden md:block">
		<table class="min-w-max w-full">
			<thead>
				<tr>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.employee')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.date')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.time_entry')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.time_exit')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.worked')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.extraordinary')) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($presences as $presence)
					<tr>
						<td class="dark:text-gray-300">{{ $presence['employee'] }}</td>
						<td class="dark:text-gray-300 {{ $presence['holiday'] }}">{{ $presence['date'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['start'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['end'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['worked'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['extraordinary'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		{{-- Tabella Mobile --}}
		<div class="md:hidden flex flex-col space-y-2">
			@foreach ($presences as $presence)
				<div class="bg-white dark:bg-gray-800 rounded p-2 shadow flex flex-col">
					<div class="text-sm dark:text-white flex justify-between">Dipendente: <span class="dark:text-gray-300">{{ $presence['employee'] }}</span></div>
					<div class="text-sm dark:text-white flex justify-between">Data: <span class="dark:text-gray-300">{{ $presence['date'] }}</span></div>
					<div class="text-sm dark:text-white flex justify-between">Ora di Entrata: <span class="dark:text-gray-300">{{ $presence['start'] }}</span></div>
					<div class="text-sm dark:text-white flex justify-between">Ora di Uscita: <span class="dark:text-gray-300">{{ $presence['end'] }}</span></div>
					<div class="text-sm dark:text-white flex justify-between">Ore Lavorate: <span class="dark:text-gray-300">{{ $presence['worked'] }}</span></div>
				</div>
			@endforeach
		</div>
	</div>

</div>