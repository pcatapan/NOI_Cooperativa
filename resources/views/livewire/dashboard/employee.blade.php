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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
	<script> 
		document.addEventListener('DOMContentLoaded', function () {
			console.log("DOM è stato caricato");
			var calendarEl = document.getElementById('calendar');
			console.log("Elemento calendario:", calendarEl);
			var calendar = new FullCalendar.Calendar(calendarEl, {
				timeZone: 'Italy/Rome',
				locale: 'it',
				firstDay: 1, 
				initialView: 'timeGridWeek',
				slotMinTime: '8:00:00',
				slotMaxTime: '20:00:00',
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
			}, 100);
		});
	</script>
@endpush