<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Request Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            text-align: center;
        }

 
        .user-info {
            margin-top: 20px;
        }

        .user-info h2 {
            font-size: 18px;
            color: #333;
        }

        .user-info p {
            font-size: 16px;
            color: #555;
        }

        .help-request {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #2196F3;
        }

        .help-request pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">



    <div class="user-info">
        <h2>User Information</h2>
        <p><strong>Name:</strong> {{ $data->name }}</p>
        <p><strong>Email:</strong> {{ $data->email }}</p>
        <p><strong>User ID:</strong> {{ $data->id }}</p>
    </div>

    <div class="help-request">
        <h2>Your Help Request</h2>
        <pre>
            {{ $data->message }}
        </pre>
    </div>

    <div class="footer">
        <p>&copy; 2025 [Your Company]. All rights reserved.</p>
    </div>
</div>

</body>
</html>
