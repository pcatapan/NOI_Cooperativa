<div class="bg-white dark:bg-gray-800 w-full p-4 rounded">
	<div class="flex w-full justify-between mb-4">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ \Str::ucfirst(__('report.details_title', ['date' => $date, 'name' => $name])) }}</h2>
		<button wire:click="cancel" class="modal-close cursor-pointer top-2 right-2 text-gray-700 dark:text-gray-300 text-xl" id="closeModal">&times;</button>
	</div>

	{{-- Tabella con i dettagli delle presenze --}}
	<div class="w-full mt-3">
		<table class="w-full">
			<thead>
				<tr>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.worksite')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.time_etry')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.time_exit')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.worked')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.extraordinary')) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($presences as $presence)
					<tr>
						<td class="dark:text-gray-300">{{ $presence['worksite'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['start'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['end'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['worked'] }}</td>
						<td class="dark:text-gray-300">{{ $presence['extraordinary'] }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

</div>