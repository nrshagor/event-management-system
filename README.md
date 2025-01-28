# Project Title: Event Management System

## Project Overview

The Event Management System is a web-based application developed in pure PHP to allow users to create, manage, and view events, as well as register attendees and generate event reports. This system provides a seamless way to organize events and manage attendees efficiently.

## Features

#### Core Functionalities

1. User Authentication

- Secure registration and login system using password hashing.

- Role-based access control (Admin, User, Super Admin).

2. Event Management

- Create, update, view, and delete events.

- Upload event images with validation.

- Pagination, sorting, and searching for event listings.

3. Attendee Management

- Register attendees for events.

- Prevent duplicate registrations and enforce maximum event capacity.

4. Event Dashboard

- Displays events in a paginated, sortable, and filterable format.

5. Event Reports

- Download attendee lists for specific events in CSV format (Admin-only feature).

6. Search Functionality

- Search across events and attendees.

7. AJAX Integration

- Enhance user experience with AJAX-based operations (e.g., event creation, updates, and registration).

8. JSON API Endpoint

- Fetch event details programmatically via a JSON API.

## Technical Details

#### Technologies Used

- Backend: PHP (No frameworks)

- Database: MySQL

- Frontend: HTML, CSS (Bootstrap), JavaScript (AJAX)

- Other Libraries: jQuery, FullCalendar

#### Security Features

- Input sanitization using htmlspecialchars.

- Password hashing with password_hash and password_verify.

- SQL injection prevention using prepared statements.

#### Database Design

Tables:

1. Users: Stores user details and roles.

2. Events: Stores event information, including name, description, date, location, capacity, and image.

3. Attendees: Stores attendee information, linked to events.

## Installation Instructions

#### Prerequisites

- PHP 7.4 or higher.

- MySQL database.

- A web server (e.g., Apache or Nginx).

## Setup Steps

Clone the Repository

```bash
git clone https://github.com/nrshagor/event-management-system.git
```

Navigate to the Project Directory

```bash
cd event-management-system
```

Setup the Database

- Import the database/event_management.sql file into your MySQL database.

```bash
mysql -u root -p event_management < event-management-system/database/event_management.sql
```

Configure Environment Variables

- Edit the .env file located in the database directory with your database credentials:

```bash
DB_HOST=localhost
DB_NAME=event_management
DB_USER=root
DB_PASS=your_password
BASE_URL=http://localhost/event-management-system/
```

#### Run the Application

Start your local server and access the application at `http://localhost/event-management-system/`

#### Optional: Seed the Database

Run the `database/seed` php script to add sample users and events.

## Live Demo

#### Live URL: `https://event-management.nrshagor.com/`

#### Credentials for Testing:

Admin:

```
Email: admin@example.com

Password: admin123
```

User:

```
Email: user1@example.com

Password: password123
```

## JSON API

#### GET Event Details with login:

`https://event-management.nrshagor.com/public/api/event_details.php?event_id={event_id}`

#### GET Event Details without login:

`http://localhost/event-management-system/event_details.php?event_id={event_id}`

#### GET All Event without login:

`http://localhost/event-management-system/fetch_events.php`

## 🚀 About Me

Greetings! I'm Noore Rabbi Shagor, widely known as N R Shagor, a seasoned and enthusiastic Full-Stack web developer with a multifaceted skill set and a proven track record of successful projects. Let me take you through the journey that defines who I am.
Visit my portfolio [nrshagor.com](https://nrshagor.com/)
