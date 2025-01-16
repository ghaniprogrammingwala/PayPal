<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - PayPal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        .login-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #0070ba;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 15px;
            border: 1px solid #d1d4d9;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #0070ba;
            box-shadow: 0 0 0 2px rgba(0, 112, 186, 0.1);
        }

        input:hover {
            border-color: #0070ba;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #0070ba;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .login-btn:hover {
            background-color: #005ea6;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-btn:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .signup-link {
            margin-top: 20px;
            color: #0070ba;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
            color: #005ea6;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Log In to PayPal</h1>
        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="login-btn">Log In</button>
            <div>
                <a href="signup.php" class="signup-link">Don't have an account? Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>
