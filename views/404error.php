<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            padding: 50px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 80px;
            color: #FF6347;
            margin: 0;
        }

        p {
            font-size: 20px;
            color: #555;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            font-size: 18px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            margin: 20px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>404</h1>
        <p class="message">Sorry, the page you are looking for could not be found.</p>
        <a href="javascript:history.back()">Go Back to Previous Page</a>
    </div>

</body>
</html>
