<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Define the sender's email address (you can also use the user's IP address for more control)
    $senderEmail = $_POST['email'];

    // Define the time window and maximum allowed submissions for both email and IP address
    $timeWindow = 3600; // 1 hour (in seconds)
    $maxSubmissionsEmail = 2; // Maximum allowed submissions by email in the time window
    $maxSubmissionsIP = 2; // Maximum allowed submissions by IP address in the time window

    // Generate a unique identifier for the sender based on their email
    $senderIdentifierEmail = md5($senderEmail);

    // Get the sender's IP address
    $senderIP = $_SERVER['REMOTE_ADDR'];

    // Generate a unique identifier for the sender based on their IP address
    $senderIdentifierIP = md5($senderIP);

    // Start or resume the session
    session_start();

    // Check if the sender's email identifier is stored in a session variable
    if (!isset($_SESSION[$senderIdentifierEmail])) {
        // If the email identifier is not set, initialize it with 1 submission
        $_SESSION[$senderIdentifierEmail] = 1;
    } else {
        // If the email identifier is already set, increment the submission count
        $_SESSION[$senderIdentifierEmail]++;
    }

    // Check if the sender has exceeded the maximum allowed email submissions
    if ($_SESSION[$senderIdentifierEmail] > $maxSubmissionsEmail) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    // Check if the sender's IP identifier is stored in a session variable
    if (!isset($_SESSION[$senderIdentifierIP])) {
        // If the IP identifier is not set, initialize it with 1 submission
        $_SESSION[$senderIdentifierIP] = 1;
    } else {
        // If the IP identifier is already set, increment the submission count
        $_SESSION[$senderIdentifierIP]++;
    }

    // Check if the sender has exceeded the maximum allowed IP submissions
    if ($_SESSION[$senderIdentifierIP] > $maxSubmissionsIP) {
        echo "<script type='text/javascript'>alert('You have exceeded the maximum allowed submissions. Please try again later.');
            window.history.go(-1);
            </script>";
        exit;
    }

    // Set a timeout for both the email and IP identifiers to expire after the defined time window
    if (!isset($_SESSION['timeout'])) {
        $_SESSION['timeout'] = time() + $timeWindow;
    } elseif ($_SESSION['timeout'] < time()) {
        // If the time window has expired, reset the email and IP identifiers and timeout
        unset($_SESSION[$senderIdentifierEmail]);
        unset($_SESSION[$senderIdentifierIP]);
        $_SESSION['timeout'] = time() + $timeWindow;
    }

    // Continue with the rest of your script (input validation, spam checks, sending email, etc.)

    // Close the session
    session_write_close();

    // Extract POST variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $subject = "Message from Navmarg Website !";

    // Validate input data
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        echo "<script type='text/javascript'>alert('Please fill in all required fields.');
            window.history.go(-1);
            </script>";
        exit;
    }

  

    // Send the email
    $to = "abhijeetkr.sci@gmail.com"; // Change receiving email id
    $content = "Name: " . $name . "\r\nContact email: " . $email . "\r\nPhone number: " . $phone . "\r\n\r\nMessage:\r\n\r\n" . $message;
    $headers = "From: " . $email . "\r\n";

    if (mail($to, $subject, $content, $headers)) {
        echo "<script type='text/javascript'>alert('Your message sent successfully.');
            window.history.go(-1);
            </script>";
    } else {
        echo "<script type='text/javascript'>alert('There was an error sending your message. Please try again later.');
            window.history.go(-1);
            </script>";
    }
}
?>
