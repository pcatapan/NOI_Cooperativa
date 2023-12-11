<div>
    @if (session()->has('message'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
            <div class="flex gap-4">
                <div class="py-1">
                    <x-icon name="check-circle" class="w-5 h-5" />
                </div>
                <div>
                    {{ session('message') }}
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

			@include('components.legend')

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
				<div wire:ignore id='calendar'></div>
            </div>
        </div>
    </div>
</div>

<script> 
if (!localStorage.getItem('alreadyReloaded')) {
	// Imposta un flag per segnalare che la pagina è stata ricaricata
	localStorage.setItem('alreadyReloaded', true);

	// Ricarica la pagina
	window.location.reload();
} else {
	initializeCalendar();
	document.addEventListener('livewire:initialized', function () {
		initializeCalendar();
	});
}
		
function initializeCalendar() {
	var calendarEl = document.getElementById('calendar');

	var calendar = new FullCalendar.Calendar(calendarEl, {
		timeZone: 'Italy/Rome',
		locale: 'it',
		firstDay: 1, 
		initialView: 'timeGridWeek',
		headerToolbar: {
			left: 'prev,next',
			center: 'title',
			right: 'timeGridWeek,timeGridDay' // user can switch between the two
		},
		buttonText: {
			today: 'oggi',
			month: 'mese',
			week: 'settimana',  // tradotto in italiano
			day: 'giorno'  // tradotto in italiano
		},
		slotLabelFormat: {
			hour: '2-digit',
			minute: '2-digit',
			omitZeroMinute: false,
			meridiem: 'short'
		},
		allDaySlot: false,
		views: {
			timeGridWeek: { // name of view
				titleFormat: { year: 'numeric', month: 'long', day: '2-digit' }
				// other view-specific options here
				}
		},
		events: @json($events),
	});
	calendar.render();

	setTimeout(function() {
		calendar.updateSize();
	}, 10);
}
</script>