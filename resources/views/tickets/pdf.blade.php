<!DOCTYPE html>
<html>
<head>
    <title>Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Event Ticket</h1>
        </div>
        <div class="info">
            <p><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>
            <p><strong>Seat:</strong> Section {{ $seat->row }} - Seat {{ $seat->number }}</p>
            <p><strong>Event ID:</strong> {{ $reservation->event_id }}</p>
            <p><strong>Status:</strong> {{ $ticket->status }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>
