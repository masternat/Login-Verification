<?php 
session_start();
include('dbcon.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

function resend_email_verify($name,$email,$verify_token){
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
    $mail->Subject = "Resend - Email Verification from Funda of Web IT";

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

if(isset($_POST['resend_email_verify_btn']))
{
    if(!empty(trim($_POST['email'])))
    {
        $email=mysqli_real_escape_string($con,$_POST['email']);
        $checkemail_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($con, $checkemail_query);

        if(mysqli_num_rows($checkemail_query_run) > 0)
        {
            $row=mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status'] == "0")
            {
                $name = $row['name'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];
                resend_email_verify($name,$email,$verify_token);
                $_SESSION['status'] = "Verification Email link has been sent to your email address.!";
                header("Location: login.php");
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Email already verified. Please Login";
                header("Location: login.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Email is not registered. Please Register now.!";
            header("Location: register.php");
            exit(0);
        }
    }
    else {
        $_SESSION['status'] = "Please enter the email field";
        header("Location: resend-email-verification.php");
        exit(0);
    }
}

?>