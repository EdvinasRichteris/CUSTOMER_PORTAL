<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Welcome to Customer Portal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #55423d;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #271C19;
            color: #fffffe;
            padding: 35px 0;
            text-align: center;
            font-size: 3em;
            font-weight: bold;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 60px);
            gap: 30px;
        }

        .btn {
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.2em;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffc0ad;
            color: #271c19;
        }

        .btn:hover {
            transform: scale(1.05);
        }

    </style>
</head>

<body>

    <div class="header">
        Welcome to the Customer Portal
    </div>

    <div class="btn-container">
        <button class="btn">Log In</button>
        <button class="btn">Contact Us</button>
    </div>

</body>

</html>