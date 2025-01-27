# Reservation System API

This is a reservation system API that allows users to manage events, seats, reservations, and tickets. The API includes JWT-based authentication for securing user actions.

## Features

- **Authentication**
  - Register, login, refresh token, and logout API endpoints.
- **Event Operations**
  - CRUD operations for events (admin-only for creation, update, and deletion).
- **Seat Operations**
  - Block and release seats for events and venues.
- **Reservation Operations**
  - Create, view, and confirm reservations, as well as cancel them.
- **Ticket Operations**
  - View tickets, download tickets as PDF, and transfer tickets.

## Database Structure

- **events**
  - id, name, description, venue_id, start_date, end_date, status, created_at, updated_at
- **venues**
  - id, name, address, capacity, created_at, updated_at
- **seats**
  - id, venue_id, section, row, number, status, price, created_at, updated_at
- **reservations**
  - id, user_id, event_id, status, total_amount, expires_at, created_at, updated_at
- **reservation_items**
  - id, reservation_id, seat_id, price, created_at, updated_at
- **tickets**
  - id, reservation_id, seat_id, ticket_code, status, created_at, updated_at

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/EkinAkkaya0/reservationapi.git
    cd reservationapi
    ```

2. Install the dependencies:
    ```bash
    composer install
    npm install
    ```

3. Generate the application key:
    ```bash
    php artisan key:generate
    ```

4. Set up the database:
    - Create a MySQL/PostgreSQL database and configure the `.env` file with your database credentials.
    - Run the migrations:
      ```bash
      php artisan migrate
      ```

5. (Optional) Seed the database with sample data:
    ```bash
    php artisan db:seed
    ```

6. Serve the application:
    ```bash
    php artisan serve
    ```
    Your API will be available at `http://localhost:8000`.

## API Endpoints

### Authentication

- **POST /api/auth/register** - Register a new user.
- **POST /api/auth/login** - Login and get a JWT token.
- **POST /api/auth/refresh** - Refresh JWT token.
- **POST /api/auth/logout** - Logout and invalidate the JWT token.

### Event Operations

- **GET /api/events** - List all events.
- **GET /api/events/{id}** - Get details of a specific event.
- **POST /api/events** - Create a new event (Admin only).
- **PUT /api/events/{id}** - Update an event (Admin only).
- **DELETE /api/events/{id}** - Delete an event (Admin only).

### Seat Operations

- **GET /api/events/{id}/seats** - List all seats for an event.
- **GET /api/venues/{id}/seats** - List all seats for a venue.
- **POST /api/seats/block** - Block seats for an event.
- **DELETE /api/seats/release** - Release blocked seats.

### Reservation Operations

- **POST /api/reservations** - Create a new reservation.
- **GET /api/reservations** - Get a list of all reservations.
- **GET /api/reservations/{id}** - Get details of a specific reservation.
- **POST /api/reservations/{id}/confirm** - Confirm a reservation.
- **DELETE /api/reservations/{id}** - Cancel a reservation.

### Ticket Operations

- **GET /api/tickets** - List all tickets.
- **GET /api/tickets/{id}** - Get details of a specific ticket.
- **GET /api/tickets/{id}/download** - Download the ticket as a PDF.
- **POST /api/tickets/{id}/transfer** - Transfer a ticket to another user.

## API Authentication

All endpoints require authentication using JWT. You can obtain a JWT token by logging in with the **POST /api/auth/login** endpoint. After logging in, use the token to authenticate further requests by including it in the `Authorization` header like so:
```bash
    Authorization: Bearer YOUR_TOKEN_HERE
```

## Testing the API with Postman

To test the API, you can import the provided Postman collection.

1. Download the Postman collection from https://drive.google.com/drive/folders/19rjc5e44FqjHoWqGC8KfHYp2rOtFc2dP?usp=sharing.
2. Import it into your Postman application.
3. Follow the instructions for each endpoint in the collection.

