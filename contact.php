<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start a new session or resume an existing one
    session_start();

    // Sanitize input data to remove potentially harmful characters
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If email is invalid, show an alert and go back to the previous page
        echo "<script type='text/javascript'>alert('Invalid email format. Please provide a valid email address.');
            window.history.go(-1);
            </script>";
        exit;
    }



    // Define sender email and other configuration
    $senderEmail = $email;
    $timeWindow = 3600; // Time window in seconds (1 hour)
    $maxSubmissionsEmail = 2; // Maximum allowed submissions per email
    $maxSubmissionsIP = 2; // Maximum allowed submissions per IP address

    // Generate unique identifiers for email and IP
    $senderIdentifierEmail = md5($senderEmail);
    $senderIP = $_SERVER['REMOTE_ADDR'];
    $senderIdentifierIP = md5($senderIP);

    // Track submissions per email
    if (!isset($_SESSION[$senderIdentifierEmail])) {
        $_SESSION[$senderIdentifierEmail] = 1;
    } else {
        $_SESSION[$senderIdentifierEmail]++;
    }

    // Check if email submissions exceed the limit
    if ($_SESSION[$senderIdentifierEmail] > $maxSubmissionsEmail) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    // Track submissions per IP
    if (!isset($_SESSION[$senderIdentifierIP])) {
        $_SESSION[$senderIdentifierIP] = 1;
    } else {
        $_SESSION[$senderIdentifierIP]++;
    }

    // Check if IP submissions exceed the limit
    if ($_SESSION[$senderIdentifierIP] > $maxSubmissionsIP) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    // Set or reset the timeout session
    if (!isset($_SESSION['timeout'])) {
        $_SESSION['timeout'] = time() + $timeWindow;
    } elseif ($_SESSION['timeout'] < time()) {
        unset($_SESSION[$senderIdentifierEmail]);
        unset($_SESSION[$senderIdentifierIP]);
        $_SESSION['timeout'] = time() + $timeWindow;
    }

    // Close the session to write changes
    session_write_close();

    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script type='text/javascript'>alert('Please fill in all required fields.');
            window.history.go(-1);
            </script>";
        exit;
    }



    // Define the recipient email
    $toEmail = "navmarg.pvt.ltd@gmail.com";

    // Define the email subject
    $subject = "Message from Navmarg Website !";

    // Define the email body 
    $mailBody = "Name: " . $name . "\n\n";
    $mailBody .= "Email: " . $email . "\n\n";
    $mailBody .= "Message: \n" . $message . "\n";

    // Send mail 
    $mailSent = mail($toEmail, $subject, $mailBody);

    // Check if the email was sent successfully
    if ($mailSent) {
        // Success: Show an alert and go back to the previous page
        echo "<script type='text/javascript'>alert('Hi " . $name . ", thank you for reaching out. We will get back to you shortly.');
            window.history.go(-1);
            </script>";
    } else {
        // Failure: Log the error and show an alert
        error_log("Mail function failed. From: $email, To: $toEmail, Subject: $subject, Content: $mailBody");
        echo "<script type='text/javascript'>alert('Unable to send email, please contact " . $toEmail . "');
            window.history.go(-1);
            </script>";
    }
}
?>
