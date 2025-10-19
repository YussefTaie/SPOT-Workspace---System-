<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Receipt</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif; /* You can change this to a more English-friendly font like sans-serif if needed */
            direction: ltr; /* Changed to left-to-right for English */
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 450px;
            background: white;
            margin: auto;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .info {
            margin-bottom: 12px;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
            color: #444;
        }

        .total {
            font-size: 20px;
            text-align: center;
            margin-top: 25px;
            border-top: 2px dashed #555;
            padding-top: 15px;
            font-weight: bold;
        }

        button {
            display: block;
            margin: 25px auto 0;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            cursor: pointer;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Client Receipt</h2>

        <div class="info"><span class="label">Name:</span> {{ $session->guest->fullname }}</div>
        <div class="info"><span class="label">Check-in Time:</span> {{ \Carbon\Carbon::parse($session->check_in)->format('h:i A') }}</div>
        <div class="info"><span class="label">Check-out Time:</span> {{ $session->check_out ? \Carbon\Carbon::parse($session->check_out)->format('h:i A') : 'In progress' }}</div>
        <div class="info"><span class="label">Duration:</span> {{ $duration->h }}h {{ $duration->i }}m</div>
        <div class="total">Total: {{ number_format($bill, 2) }} EGP</div>

        <button onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>
