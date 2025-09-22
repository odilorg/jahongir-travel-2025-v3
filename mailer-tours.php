<?php




use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);
$mail -> CharSet = "UTF-8";
$alert = "";
$tour_code="";

if (isset($_POST['submit'])){

    $secretKey = "6Ld3L10aAAAAAHy5Lx0-Zr28iiFncHoND-DK262_";
	$responseKey = $_POST['g-recaptcha-response'];
	$UserIP = $_SERVER['REMOTE_ADDR'];
	$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$UserIP";
	
    $response = file_get_contents($url);
    $response = json_decode($response);
    

    if ($response->success) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $hotel = $_POST['hotel_to_pickup'];
        $d_date = $_POST['departure_date'];
        $adults = $_POST['number_adults'];
        $kids = $_POST['number_children'];
        $tour_name = $_POST['tour_name'];
        $tour_code = $_POST['tour_code'];
        


    try {
     
    $mail->SMTPDebug = 0;
    $mail->isSMTP(); // Send using SMTP
    $mail->Host = 'smtp.titan.email'; // Set the SMTP server to send through
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'booking@jahongir-premium.uz'; // SMTP username
    $mail->Password = "65GGhhgg&^HHG"; // SMTP password
   $mail->SMTPSecure = "tls"; 
   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
 
   //Recipients
    $mail->setFrom('booking@jahongir-premium.uz', 'Jahongir Premium');
    $mail->addAddress('odilorg@gmail.com', 'Odiljon'); // Add a recipient
//    $mail->addBCC('zafarorg@gmail.com');            // Name is optional
    $mail->addReplyTo('booking@jahongir-premium.uz', 'Jahongir Premium');
    // $mail->addCC('cc@example.com');
       
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Tour - ".$tour_name;
        $mail->Body    = "<h3>First Name  : $first_name <br> 
                              Last name : $last_name      <br>
                              Email     : $email      <br>
                              Hotel to pickup : $hotel      <br>
                              Departure date : $d_date      <br>
                              Number of adults : $adults      <br>
                              Number of children : $kids      <br>
                              Tour code : $tour_code      <br>
                              Tour name: $tour_name    </h3>";

        $mail->send();
        $telegramData = "Booking
        First Name: $first_name
        Last name: $last_name      
        Email: $email      
        Hotel to pickup: $hotel      
        Departure date: $d_date      
        Number of adults: $adults      
        Number of children: $kids      
        Tour code: $tour_code      
        Tour name: $tour_name   ";
        //php code to send the message to Telegram Channel
        $apiToken = "5019025912:AAHKN5y-YHQbFkVJy-BtEpWioufOgQAQ1uA";
        $data = [
            'chat_id' => '-1001570852064', 
            'text' => $telegramData
        ];
        $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );   
        
        $alert = '<div class="mail-success-tour">
                  <span>Message Sent! We will contact you soon</span>
                  </div>';


        } catch (Exception $e) {
            $alert = '<div class="mail-error-tour">
            <span>'.$e->getMessage().'</span>
            </div>';  
        }
    }else {
        $alert = '<div class="mail-error-tour">
        <span>Invalid captcha. Try again</span>
        </div>';  
    }

    




}



