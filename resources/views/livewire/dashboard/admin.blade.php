<div>
	<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('general.welcome', ['name' => $name]) }}
        </h2>
    </x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg flex flex-col gap-8">
				<div class="flex flex-row gap-8">
					{{-- Card Dipendeti  --}}
					<div class="w-1/3">
						<x-card shadow-md class="p-2 m-2">
							<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
								<span>{{__('general.number_of_elements', ['elements' => __('navigation.employees')])}}</span>
								<span>{{ $numberEmployees }}</span>
							</div>
							<x-slot name="footer">
								<div class="flex justify-end">
									<x-button outline primary label="{{__('general.add')}}" icon="plus" href="{{route('employee.add_edit')}}" teal/>
								</div>
							</x-slot>
						</x-card>
					</div>

					{{-- Card Cantieri --}}
					<div class="w-1/3">
						<x-card shadow-md class="p-2 m-2">
							<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
								<span>{{__('general.number_of_elements', ['elements' => __('navigation.worksites')])}}</span>
								<span>{{ $numberWorksites }}</span>
							</div>
							<x-slot name="footer">
								<div class="flex justify-end">
									<x-button outline primary label="{{__('general.add')}}" icon="plus" href="{{route('worksite.add_edit')}}" teal/>
								</div>
							</x-slot>
						</x-card>
					</div>

					{{-- Card Azienda --}}
					<div class="w-1/3">
						<x-card shadow-md class="p-2 m-2">
							<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
								<span>{{__('general.number_of_elements', ['elements' => __('navigation.companies')])}}</span>
								<span>{{ $numberCompanies }}</span>
							</div>
							<x-slot name="footer">
								<div class="flex justify-end">
									<x-button outline primary label="{{__('general.add')}}" icon="plus" href="{{route('company.add_edit')}}" teal/>
								</div>
							</x-slot>
						</x-card>
					</div>
				</div>

				{{-- Card Turni – Validazioni --}}
				<div class="flex flex-row gap-8">
					{{-- Card Turni validati  --}}
					<div class="w-1/2">
						<x-card shadow-md class="p-2 m-2">
							<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
								<span>{{__('general.number_of_elements', ['elements' => __('turni validati')])}}</span>
								<span>{{ $shiftValidated }}</span>
							</div>
						</x-card>
					</div>

					{{-- Card Turni da validare --}}
					<div class="w-1/2">
						<x-card shadow-md class="p-2 m-2">
							<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
								<span>{{__('general.number_of_elements', ['elements' => 'turni da validare'])}}</span>
								<span>{{ $shiftNotValidated }}</span>
							</div>
						</x-card>
					</div>
				</div>

				{{-- Alert --}}
				<div class="flex flex-row gap-8">
					<x-card shadow-md class="p-2 m-2 items-center">
						<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
							<span>{{__('general.number_of_elements', ['elements' => 'cantieri oltre la soglia ordinaria'])}}</span>
							@if($worksiteOverLimitOrdinaryCount > 0)
								<x-button primary label="{{__('general.view')}}" wire:click="toggleModalOrdinary"/>
							@endif

							<div class="flex items-center gap-1">
								<span>{{ $worksiteOverLimitOrdinaryCount }}</span>
								@if($worksiteOverLimitOrdinaryCount > 0)
									<x-icon name="exclamation-circle" class="w-5 h-5 text-red-500" solid />
								@endif
							</div>
						</div>
					</x-card>

					<x-card shadow-md class="p-2 m-2 items-center">
						<div class="flex justify-between text-gray-700 dark:text-white font-semibold">
							<span>{{__('general.number_of_elements', ['elements' => 'cantieri oltre la soglia straordinaria'])}}</span>
							@if($worksiteOverLimitExtraordinaryCount > 0)
								<x-button primary label="{{__('general.view')}}" wire:click="toggleModalExtraordinary"/>
							@endif
							<div class="flex items-center gap-1">
								<span>{{ $worksiteOverLimitExtraordinaryCount }}</span>
								@if($worksiteOverLimitExtraordinaryCount > 0)
									<x-icon name="exclamation-circle" class="w-5 h-5 text-red-500" solid />
								@endif
							</div>
						</div>
					</x-card>
				</div>
			</div>
		</div>
	</div>

	{{-- Modale cantieri sopra limite ordinario --}}
	<x-modal.card title="{{ \Str::ucfirst(__('general.title', ['title' => 'Cantieri sopra il limite ordinario'])) }}" blur wire:model.defer="openModalOrdinary" align="center">
		<table class="min-w-max w-full mt-2">
			<thead>
				<tr>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('worksite.cod')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.hours_worked')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.hours_config')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.diff')) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($worksiteOverLimitOrdinary as $element)
					<tr>
						<td class="dark:text-gray-300">{{ $element->cod }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours_worked }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours_worked - $element->total_hours }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
    </x-modal.card>

	{{-- Modale cantieri sopra limite straordinario --}}
	<x-modal.card title="{{ \Str::ucfirst(__('general.title', ['title' => 'Cantieri sopra il limite ordinario'])) }}" blur wire:model.defer="openModalExtraordinary" align="center">
		<table class="min-w-max w-full mt-2">
			<thead>
				<tr>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('worksite.cod')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.hours_worked')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.hours_config')) }}</th>
					<th class="text-left dark:text-white">{{ \Str::ucfirst(__('report.diff')) }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($worksiteOverLimitExtraordinary as $element)
					<tr>
						<td class="dark:text-gray-300">{{ $element->cod }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours_worked }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours }}</td>
						<td class="dark:text-gray-300">{{ $element->total_hours_worked - $element->total_hours }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
    </x-modal.card>
</div>