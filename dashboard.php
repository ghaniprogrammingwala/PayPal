<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PayPal</title>
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

        .dashboard-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .dashboard-container:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        h1 {
            color: #0070ba;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .balance {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .dashboard-btn {
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
            text-transform: uppercase;
        }

        .dashboard-btn:hover {
            background-color: #005ea6;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-btn:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .logout-btn {
            background-color: #e5e5e5;
            color: #333;
        }

        .logout-btn:hover {
            background-color: #d5d5d5;
        }

        @media (max-width: 480px) {
            .dashboard-container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <div class="balance">Balance: $<?php echo number_format($user['balance'], 2); ?></div>
        <div class="btn-group">
            <button class="dashboard-btn" onclick="location.href='send_payment.php'">Send Payment</button>
            <button class="dashboard-btn logout-btn" onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
</body>
</html>
