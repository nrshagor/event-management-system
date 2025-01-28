<?php
require_once __DIR__ . '/../models/Attendee.php';

class AttendeeController
{
    private $attendeeModel;

    public function __construct($pdo)
    {
        $this->attendeeModel = new AttendeeModel($pdo);
    }
    // Register Attendee
    public function registerAttendee($event_id, $user_name, $email)
    {
        if (empty($event_id) || empty($user_name) || empty($email)) {
            return "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        $eventDetails = $this->attendeeModel->getEventDetails($event_id);
        if (!$eventDetails) {
            return "Event not found.";
        }

        if ($eventDetails['total_registered'] >= $eventDetails['capacity']) {
            return "Event is fully booked.";
        }

        if ($this->attendeeModel->checkIfEmailExists($event_id, $email)) {
            return "You are already registered for this event.";
        }

        if ($this->attendeeModel->addAttendee($event_id, $user_name, $email)) {
            return "Registration successful!";
        }

        return "Registration failed. Please try again.";
    }
    // Get List of Attendees
    public function listAttendees($event_id, $limit, $offset, $search = '')
    {
        if (empty($event_id)) {
            return "Event ID is required.";
        }

        return $this->attendeeModel->getAttendeesByEvent($event_id, $limit, $offset, $search);
    }
    // Count of Attendees
    public function countAttendees($event_id, $search = '')
    {
        if (empty($event_id)) {
            return 0;
        }

        return $this->attendeeModel->getAttendeeCount($event_id, $search);
    }
}
