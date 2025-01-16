<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_email = $_POST['email'];
    $amount = $_POST['amount'];
    // Validate amount
    if ($amount <= 0) {
        echo "<script>alert('Invalid amount'); window.location.href='send_payment.php';</script>";
        exit;
    }
    // Fetch sender details
    $stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->bind_param('i', $sender_id);
    $stmt->execute();
    $sender = $stmt->get_result()->fetch_assoc();
    if ($sender['balance'] < $amount) {
        echo "<script>alert('Insufficient balance'); window.location.href='send_payment.php';</script>";
        exit;
    }
    // Fetch receiver details
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $receiver_email);
    $stmt->execute();
    $receiver = $stmt->get_result()->fetch_assoc();
    if (!$receiver) {
        echo "<script>alert('Receiver not found'); window.location.href='send_payment.php';</script>";
        exit;
    }
    $receiver_id = $receiver['id'];
    // Process the payment
    $conn->begin_transaction();
    try {
        // Deduct from sender
        $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->bind_param('di', $amount, $sender_id);
        $stmt->execute();
        // Add to receiver
        $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->bind_param('di', $amount, $receiver_id);
        $stmt->execute();
        // Record transaction
        $stmt = $conn->prepare("INSERT INTO transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)");
        $stmt->bind_param('iid', $sender_id, $receiver_id, $amount);
        $stmt->execute();
        $conn->commit();
        echo "<script>alert('Payment successful!'); window.location.href='dashboard.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Payment failed: " . $e->getMessage() . "'); window.location.href='send_payment.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Payment - PayPal Clone</title>
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
        .payment-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .payment-container:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        h1 {
            color: #0070ba;
            margin-bottom: 30px;
            font-weight: 500;
        }
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .payment-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .payment-input:focus {
            outline: none;
            border-color: #0070ba;
        }
        .payment-btn {
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
        .payment-btn:hover {
            background-color: #005ea6;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .payment-btn:active {
            transform: translateY(1px);
            box-shadow: none;
        }
        @media (max-width: 480px) {
            .payment-container {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Send Payment</h1>
        <form method="POST" class="payment-form">
            <input type="email" name="email" placeholder="Recipient's Email" class="payment-input" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" class="payment-input" required>
            <button type="submit" class="payment-btn">Send Payment</button>
        </form>
    </div>
</body>
</html>
