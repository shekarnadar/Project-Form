<?php
include "config.php";
$ip=$_SERVER['REMOTE_ADDR'];
$lastSubmissionTime=time()-60;
$sql="SELECT time_stamp FROM contact_form WHERE ip_address=? AND time_stamp >?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("si",$ip,$lastSubmissionTime);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows > 0)
{
    die("You Have Already Submitted a form recently. Please Try Again Later");
}

$recaptchaSecret="6Lex6dcpAAAAAOw7vAFQacaH0h9iOWayT7dQ3aia";
$recaptchaResponse=$_POST['g-recaptcha-response'];
$verifyURL="https://wwww.recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse";
$response=file_get_contents($verifyURL);
$responseKeys=json_decode($response, true);
if(intval($responseKeys['success'])!==1)
{
    die("CAPTCHA Verification Failed. Please Try again");
}


if($_SERVER['REQUEST_METHOD']=="POST")
{
    $full_name=$_POST['full_name'];
    $phone_number=$_POST['phone_number'];
    $email=$_POST['email'];
    $subject=$_POST['subject'];
    $message=$_POST['message'];

    if(empty($full_name) || empty($phone_number) || empty($email) || empty($subject) || empty($message))
    {
        die("All are required Fields");
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        die("Invalid Email Id");
    }
    if(!preg_match("/^[0-9]{10}$/",$phone_number))
    {
        die("Invalid Phone Number format");
    }

    $ipAddress=$_SERVER['REMOTE_ADDR'];
    $timestamp=date('Y-m-d H:i:s');
    $sql="INSERT INTO contact_form (full_name,phone_number,email,subject,message,ip_address,time_stamp) VALUES(?,?,?,?,?,?,?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("sssssss",$full_name,$phone_number,$email,$subject,$message,$ipAddress,$timestamp);
    if($stmt->execute())
    {
        $to="shekar.ndr@gmail.com";
        $subject="New Form Submission";
        $message="Name : $full_name\nPhone Number: $phone_number\nEmail: $email\nSubject: $subject\nMessage: $message\nIp Address: $ipAddress\nTime: $timestamp";
        $header="From: shekar.ndr@gmail.com";
        mail($to,$subject,$message,$header);
        echo "Form Submited Successfully";
    }
    else{
        echo "ERROR: ".$sql."<br>".$conn->error;
    }

    }
 $stmt->close();
 $stmt->close();
?>