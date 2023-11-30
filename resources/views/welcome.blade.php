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


        .login-container {
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: #ffc0ad;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #271c19;
            width: 300px;
            margin: 40px auto;
        }

        .login-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #fffffe;
        }

        .login-input {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #271c19;
            width: 100%;
        }

        .login-submit {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: #271C19;
            color: #fff;
            cursor: pointer;
            width: 60%;
            margin-top: 10px;
        }

        .login-submit:hover {
            background-color: #55423d;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

    </style>
</head>


<body>

    <div class="header">
        Welcome to the Customer Portal
    </div>

    <div class="btn-container" id="btn-container">
        <button class="btn" onclick="showLoginForm()">Log In</button>
        <button class="btn">Contact Us</button>
    </div>

    <div class="login-container" id="login-form">
        <div class="login-title">Log In</div>
        <input type="text" id="username" class="login-input" placeholder="Username">
        <input type="password" id="password" class="login-input" placeholder="Password">
        <button class="login-submit" onclick="handleLogin()">Submit</button>
        <div id="login-error" class="error-message"></div>
    </div>

    <script>
        function showLoginForm() {
            document.getElementById('btn-container').style.display = 'none';
            document.getElementById('login-form').style.display = 'flex';
        }
    
        function handleLogin() {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        fetch('/oauth/token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                grant_type: 'password',
                client_id: '3',
                client_secret: 'MQAciS7nIkv8Xe5PxJcE5DcZBFrTnsxAWNkSOgny',
                username: username,
                password: password,
                scope: ''
            })
        })
        .then(response => {
            console.log(response.ok);
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(json => {
                    throw new Error(json.error_description || 'Login failed');
                });
            }
        })
        .then(data => {
            if (data.access_token) {
                window.location.href = '/loads';
            } else {
                throw new Error('Login failed: Invalid server response.');
            }
        })
        .catch(error => {
            const errorDiv = document.getElementById('login-error');
            errorDiv.textContent = error.message;
            errorDiv.style.display = 'block';
        });
    }
</script>

</body>

</html>