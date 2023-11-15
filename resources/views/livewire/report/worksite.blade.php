<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('navigation.reports') }}
        </h2>
    </x-slot>

    <div class="py-12 d-flex flex-col">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow-sm sm:rounded-lg">
				 <livewire:report.filters :not-show-employee="true"/>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				 <livewire:report.report-worksite-table/>
            </div>
        </div>
    </div>
</x-app-layout>
