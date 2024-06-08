<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer();
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // SMTP::DEBUG_OFF = off (for production use)
    //SMTP::DEBUG_CLIENT = client messages
    //SMTP::DEBUG_SERVER =  client and server messages
    $mail->SMTPDebug = 0;

    //Set the hostname of the mail server
    $mail->Host = "smtp.zoho.com";

    //Set the encryption mechanism to use - STARTTLS or SMTPS
    $mail -> SMTPSecure = 'tls';
    // Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    $mail->Username = "ericose4peace@yahoo.ca";
    $mail->Password = "I&iaar1325";

    $mail->Port = 587;

    $mail->setFrom("bcontent@zohomail.com",$name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Email Verification from Funda of Web IT";

    $email_template = "
        <h2>You have Registered with Funda of Web IT</h2>
        <h5>Verify your email address to Login with the below given link</h5>
        <br/><br/>
        <a href='http://example:8080/verify-email.php?token=$verify_token'>Click Me</a>
        ";
        $mail->Body = $email_template;
        $mail->send();
        echo 'Message has been sent';
}


if(isset($_POST['register_btn']))
{
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $verify_token = md5(rand());

    // Email Exists or not
    $check_email_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con,$check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Email id already Exists";
        header("Location: register.php");
    }
    else{
        //Insert User / Register User Data
        $query = "INSERT INTO users(name,phone,email,password,verify_token) VALUES ('$name','$phone','$email','$password','$verify_token')";
        $query_run = mysqli_query($con,$query);

        if($query_run)
        {
            sendemail_verify("$name","$email","$verify_token");

            $_SESSION['status'] = "Registration Successful.! Verify your Email Address.";
            header("Location: register.php");
        }
        else
        {
            $_SESSION['status'] = "Registration Failed";
            header("Location: register.php");
        }
    }
}

?>  