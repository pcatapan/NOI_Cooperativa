<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('navigation.shifts_not_validated') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				@if (Illuminate\Support\Facades\Auth::user()->role === App\Enums\UserRoleEnum::RESPONSIBLE->value)
					@foreach(Illuminate\Support\Facades\Auth::user()->employee->worksitesAsResponsible as $worksite)
						<h2 class="font-semibold text-center mt-2 text-xl text-gray-800 dark:text-gray-200 leading-tight">
							Tutti i turni da validare del cantiere {{ $worksite->cod }}
						</h2>
						<livewire:shift.shift-not-validated-table :worksite="$worksite"/>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</x-app-layout>
