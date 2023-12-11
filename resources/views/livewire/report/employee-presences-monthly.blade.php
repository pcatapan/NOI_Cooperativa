<x-slot name="header">
	<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
		{{ __('navigation.report_presences') }}
	</h2>
</x-slot>

<div class="py-12 d-flex flex-col">
	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="shadow-sm sm:rounded-lg">
			<div class="flex items-center justify-center">
				<!-- Freccia sinistra per il mese precedente -->
				<button class="mr-2" wire:click="previousMonth">
					<x-icon name="arrow-circle-left" class="w-10 h-10 dark:text-white" />
				</button>

				<!-- Nome del mese corrente -->
				<span class="text-2xl font-bold dark:text-white">{{ \Str::ucfirst($monthTranslate) }}</span>

				<!-- Freccia destra per il mese successivo -->
				<button class="ml-2" wire:click="nextMonth">
					<x-icon name="arrow-circle-right" class="w-10 h-10 dark:text-white" />
				</button>
			</div>
		</div>
	</div>

	<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="flex justify-between">
			@include('components.legend')

			<div class="flex sm:items-center gap-4 sm:flex-row flex-col justify-center">
				<p class="dark:text-white">Totale ore lavorate: {{ round($totalMinutesWorked / 60) }}</p>
				<p class="dark:text-white">Totale ore straordinarie: {{ round($totalMinutesExtraordinary / 60) }}</p>
				<p class="dark:text-white">Totale ore permessi: {{ round($totalMinutesAbsence / 60) }}</p>
			</div>
		</div>

		<div class="w-full overflow-x-auto hidden md:block">
			<table class="min-w-max w-full">
				<thead>
					<tr>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('report.date') }}</th>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('report.time_entry') }}</th>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('report.time_exit') }}</th>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('report.worked') }}</th>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('shift.type') }}</th>
						<th class="px-4 py-2 dark:text-white border-b-2">{{ __('report.worksite') }}</th>
					</tr>
				</thead>
				<tbody>
					@if ($presences->isEmpty())
						<tr>
							<td class="px-4 py-2 text-center dark:text-white" colspan="6">{{ __('report.empty') }}</td>
						</tr>
					@else
						@foreach ($presences as $presence)
							<tr>
								<td class="border-b px-4 py-2 text-center dark:text-white {{ $presence->holiday }}">{{ $presence->date->format('d/m') }}</td>
								<td class="border-b px-4 py-2 text-center dark:text-white">{{ $presence->time_entry }}</td>
								<td class="border-b px-4 py-2 text-center dark:text-white">{{ $presence->time_exit }}</td>
								<td class="border-b px-4 py-2 text-center dark:text-white">{{ round($presence->minutes_worked / 60) }}</td>
								<td class="border-b px-4 py-2 text-center dark:text-white {{$presence->type }}">{{ $presence->type == 'ordinary' ? __('shift.ordinary') : __('shift.extraordinary') }}</td>
								<td class="border-b px-4 py-2 text-center dark:text-white">{{ $presence->worksite_cod }}</td>
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>

		{{-- Tabella Mobile --}}
		<div class="md:hidden flex flex-col space-y-2">
			@if($presences->isEmpty())
				<div class="bg-white dark:bg-gray-800 rounded p-2 shadow flex flex-col">
					<div class="text-sm dark:text-white flex justify-between">Nessun dato presente</div>
				</div>
			@else
				@foreach ($presences as $presence)
					<div class="bg-white dark:bg-gray-800 rounded p-2 shadow flex flex-col">
						<div class="text-sm dark:text-white flex justify-between">Data: <span class="dark:text-gray-300">{{ $presence->date->format('d/m') }}</span></div>
						<div class="text-sm dark:text-white flex justify-between">Ora di Entrata: <span class="dark:text-gray-300">{{ $presence->time_entry }}</span></div>
						<div class="text-sm dark:text-white flex justify-between">Ora di Uscita: <span class="dark:text-gray-300">{{ $presence->time_exit }}</span></div>
						<div class="text-sm dark:text-white flex justify-between">Ore Lavorate: <span class="dark:text-gray-300">{{ round($presence->minutes_worked / 60) }}</span></div>
						<div class="text-sm dark:text-white flex justify-between">Tipo: <span class="dark:text-gray-300">{{ $presence->type == 'ordinary' ? __('shift.ordinary') : __('shift.extraordinary') }}</span></div>
						<div class="text-sm dark:text-white flex justify-between">Cantiere: <span class="dark:text-gray-300">{{ $presence->worksite_cod }}</span></div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>