<div>
    @if (session()->has('success'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
            <div class="flex gap-4">
                <div class="py-1">
                    <x-icon name="check-circle" class="w-5 h-5" />
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('general.welcome', ['name' => $name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg flex flex-col gap-8">
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
				<div class="flex flex-row gap-8 mb-6">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<p class="p-4 text-xl text-white">{{ __('shift.shift_validation') }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<livewire:shift.shift-today-table/>
            </div>
        </div>
        <x-icon name="plus" class="w-10 h-10 fixed rounded-full shadow-lg text-black z-50 cursor-pointer bottom-4 right-4 bg-white" id="create_shift" wire:click="openModalCreateShift"/>
    </div>

    <x-modal.card title="{{ \Str::ucfirst(__('shift.shift')) }}" blur wire:model.defer="createShiftModal" align="center">

        {{-- È uno straordinario --}}
        <div class="w-full flex justify-end">
            <x-toggle
                left-label="{{ \Str::ucfirst(__('shift.is_extraordinary')) }}"
                wire:model="isExtraordinary"
                md
            />
        </div>
        <div class="gap-2 flex flex-col">
            @if (session()->has('error'))
                <div class="bg-red-500 border-t-4 border-red-500 rounded-b text-white px-4 py-3 shadow-md" role="alert">
                    <div class="flex gap-4">
                        <div class="py-1">
                            <x-icon name="check-circle" class="w-5 h-5" />
                        </div>
                        <div>
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            @endif
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

        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">
				<x-button flat label="{{ \Str::ucfirst(__('general.cancel')) }}" x-on:click="close" />
	
				<div class="flex">
					<x-button primary label="{{ \Str::ucfirst(__('general.save')) }}" wire:click="createShift" />
				</div>
			</div>
        </x-slot>
    </x-modal.card>

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