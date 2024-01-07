document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      locale: 'fr',
      headerToolbar:{
        start : "prev,next",
        center : "title",
        end : "dayGridMonth,timeGridWeek"
      },
      events : '{{ data|raw }}',
    });
    calendar.render();
  });