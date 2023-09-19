<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    


// Extract POST variables

 $name= $_POST['name'];
 $email = $_POST['Email'];
 $phone = $_POST['Phone'];
 $message = $_POST['Message'];
 $subject = "Message from Navmarg Website !";

 $to ="xxxxxxxxxxx@gmail.com";  // change receiving email id 
 
 $content = "Name : ". $name. "\r\nContact email : ". $email. "\r\nPhone number :". $phone. "\r\n  \r\nMessage : \r\n \r\n".$message ; // name [break] email [break] message
 


// check input fields
if ( empty($name)|| empty($email)|| empty($message))
{
echo"<script type='text/javascript'>alert('Please fill all correct');
    window.history.go(-1);
    </script>";
}
else 
{ mail($to,$subject,$content);

    echo"<script type='text/javascript'>alert('Your message sent succesfully ');
    window.history.go(-1);
    </script>";
}
}


?>
