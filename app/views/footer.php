</div> <!-- Closing container -->

<footer class="bg-white text-center text-dark py-3 footer">
    <p class="mb-0">© <?= date('Y') ?> Event Management. All Rights Reserved. || This system is made with <i class="fa-regular fa-heart text-danger"></i> by <a href="https://nrshagor.com/" target="_blank">nrshagor.com </a></p>
</footer>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: 'fetch_events.php', // Your events fetching URL
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short' // Use 'short' for AM/PM formatting like "10:00 AM"
            },
            eventDisplay: 'block', // Ensure the event name is shown properly
            eventContent: function(info) {
                return {
                    html: `<b>${info.event.title}</b><br>${info.event.start.toLocaleTimeString([], {hour: 'numeric', minute: '2-digit', hour12: true})}`
                };
            },
            eventClick: function(info) {
                fetch('event_details.php?event_id=' + info.event.id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('eventTitle').innerText = data.event.name;
                            document.getElementById('eventDate').innerText = 'Date: ' + data.event.date;
                            document.getElementById('eventLocation').innerText = 'Location: ' + data.event.location;
                            document.getElementById('eventImage').src = data.event.image_url;
                            document.getElementById('eventDescription').innerText = data.event.description;

                            let eventClosed = data.event.closed;
                            let registered = parseInt(data.event.attendees);
                            let capacity = parseInt(data.event.capacity);
                            let remainingSeats = data.event.remaining;
                            let progressPercentage = (registered / capacity) * 100;

                            document.getElementById('eventProgressBar').style.width = progressPercentage + '%';
                            document.getElementById('eventProgressBar').innerText = registered + ' / ' + capacity + ' Registered';

                            if (eventClosed) {
                                document.getElementById('eventCapacity').innerText = 'This event is closed.';
                                document.getElementById('eventRegisterLink').innerText = 'Event Closed';
                                document.getElementById('eventRegisterLink').classList.remove('btn-primary');
                                document.getElementById('eventRegisterLink').classList.add('btn-danger');
                                document.getElementById('eventRegisterLink').setAttribute('disabled', true);
                                document.getElementById('eventRegisterLink').href = '#';
                            } else {
                                document.getElementById('eventCapacity').innerText = remainingSeats + ' spots left!';
                                document.getElementById('eventRegisterLink').innerText = 'Register Now';
                                document.getElementById('eventRegisterLink').classList.remove('btn-danger');
                                document.getElementById('eventRegisterLink').classList.add('btn-primary');
                                document.getElementById('eventRegisterLink').href = 'register_attendee.php?event_id=' + info.event.id;
                                document.getElementById('eventRegisterLink').removeAttribute('disabled');
                            }

                            var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
                            myModal.show();
                        } else {
                            alert('Event not found');
                        }
                    })
                    .catch(error => console.error('Error fetching event details:', error));
            }
        });

        calendar.render();
    });
</script>

</body>

</html>