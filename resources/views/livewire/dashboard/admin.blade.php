<div>
	<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('general.welcome', ['name' => $name]) }}
        </h2>
    </x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
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
			</div>
		</div>
	</div>
</div>