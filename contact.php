<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script type='text/javascript'>alert('Invalid email format. Please provide a valid email address.');
            window.history.go(-1);
            </script>";
        exit;
    }

    $senderEmail = $email;
    $timeWindow = 3600;
    $maxSubmissionsEmail = 2;
    $maxSubmissionsIP = 2;

    $senderIdentifierEmail = md5($senderEmail);
    $senderIP = $_SERVER['REMOTE_ADDR'];
    $senderIdentifierIP = md5($senderIP);

    if (!isset($_SESSION[$senderIdentifierEmail])) {
        $_SESSION[$senderIdentifierEmail] = 1;
    } else {
        $_SESSION[$senderIdentifierEmail]++;
    }

    if ($_SESSION[$senderIdentifierEmail] > $maxSubmissionsEmail) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    if (!isset($_SESSION[$senderIdentifierIP])) {
        $_SESSION[$senderIdentifierIP] = 1;
    } else {
        $_SESSION[$senderIdentifierIP]++;
    }

    if ($_SESSION[$senderIdentifierIP] > $maxSubmissionsIP) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    if (!isset($_SESSION['timeout'])) {
        $_SESSION['timeout'] = time() + $timeWindow;
    } elseif ($_SESSION['timeout'] < time()) {
        unset($_SESSION[$senderIdentifierEmail]);
        unset($_SESSION[$senderIdentifierIP]);
        $_SESSION['timeout'] = time() + $timeWindow;
    }

    session_write_close();

    if (empty($name) || empty($email) || empty($message)) {
        echo "<script type='text/javascript'>alert('Please fill in all required fields.');
            window.history.go(-1);
            </script>";
        exit;
    }

    $toEmail = "navmarg.pvt.ltd@gmail.com";
    $mailHeaders = "From: " . $name . "<" . $email . ">\r\n";
    $mailBody = "Name: " . $name . "\n";
    $mailBody .= "Email: " . $email . "\n";
    $mailBody .= "Message: " . $message . "\n";

    if (mail($toEmail, "Message from Navmarg Website !", $mailBody, $mailHeaders)) {
        echo "<script type='text/javascript'>alert('Hi " . $name . ", thank you for reaching out. We will get back to you shortly.');
            window.history.go(-1);
            </script>";
    } else {
        error_log("Mail function failed. From: $email, To: $toEmail, Content: $mailBody, Headers: $mailHeaders");
        echo "<script type='text/javascript'>alert('Unable to send email, please contact " . $toEmail . "');
            window.history.go(-1);
            </script>";
    }
}
?>
