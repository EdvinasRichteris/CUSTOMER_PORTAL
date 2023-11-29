<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Invoice Details</title>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div id="app">
        <invoice-details></invoice-details>
    </div>

    <script src="http://localhost:5173/resources/js/app.js" type="module"></script>

</body>

</html>