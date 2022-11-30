<?php
date_default_timezone_set('Asia/Jakarta');
include '../data.php';
include '../conn.php';
include "classes/class.phpmailer.php"; // include the class file name

$data='API belum tersedia';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if ($email) {
        if(isset($_POST["user_name"])){
            $kueri = $db->fetchwhere("data_user", "email = '$email'");
            
        	if ($kueri) {
        	    try {
        		$email = "eddyhu71@gmail.com";
        		//$email = "handywij0982@gmail.com";
        		$mail  = new PHPMailer(true);
        		//$mail->IsSMTP();
        		$mail->Host = "mail.acun.roamx.site"; //Hostname of the mail server
        		$mail->Port = 587; //Port of the SMTP like to be 25, 80, 465 or 587
        		//	$mail->SMTPAuth = true; //Whether to use SMTP authentication
        		$mail->Username = "test@acun.roamx.site"; //Username for SMTP authentication any valid email created in your domain
        		$mail->Password = "123456"; //Password for SMTP authentication
        		//$mail->AddReplyTo("eddyhu71@gmail.com"); //reply-to address
        		//$mail->AddAddress("eddyhu71@gmail.com", $_POST['name_feedback']);
        		$mail->AddAddress($email, $_POST['user_name']); 
        		$mail->AddCC('eddyhu71@gmail.com', 'Ganti Kata Kunci');
        		$mail->SetFrom($_POST['user_email'], "Ganti Kata Kunci"); //From address of the mail
        		// put your while loop here like below,
        		$mail->Subject = "Ganti Kaca Kunci"; //Subject od your mail
        	//To address who will receive this email
        		// $_POST['message_feedback']
        		$date = date('Y/m/d H:i:s');
        		$hash = sha256($date);
        		$isi = '<p>Kepada pengguna '.$_POST['user_name'].'</p>';
        		$isi.='<p>Untuk mengganti kata sandi anda yang lama, silahkan klik link di bawah ini.</p><br />';
        		$isi.='https://guritadigital.co.id/user/change_password'.$hash;
        		$isi.='<br /><p>Link yang dikirim akan kadaluarsa dalam waktu 24 jam. Harap segera diganti. Jika anda tidak merasa meminta mengganti kata sandi, silakan abaikan email. Terima kasih.';
        		$mail->Body = $isi;
        		//$mail->Body = "Username : ". $_POST['user_name'].". Email : ".$_POST['user_email'].". </br>Discord ID : ".$_POST['user_discord_id'].".</br> Message : ".$_POST['user_message'].". "; //Put your body of the message you can place html code here
        		//$mail->AltBody = "This is body in plain text";
        		$send = $mail->Send();
            		if ($send) {
            			//echo "lupa_password_berhasil";
            			echo json_encode("forget_berhasil");
            			//header("Location : roamx.site/acun");
            		} else {
            			//echo "lupa_password_gagal";
            			echo json_encode("forget_gagal");
            			//header("Location : roamx.site/acun");
            		}
            	} catch (Exception $e) {
            		echo "Error 404";
            	}
        	} else {
        	    echo json_encode("email_tidak_ada");
        	}
        }
    } else {
        echo json_encode("invalid_email");
    }
}

if(isset($_POST["name_distribution"])){
 // call the class
	try {
	$email = "hw_mdn@yahoo.co.id";
    $mail_distribusi = new PHPMailer(true);
	//$mail->IsSMTP();
    $mail_distribusi->Host = "mail.acun.roamx.site"; //Hostname of the mail server
    $mail_distribusi->Port = 587; //Port of the SMTP like to be 25, 80, 465 or 587
    //	$mail->SMTPAuth = true; //Whether to use SMTP authentication
    $mail_distribusi->Username = "test@acun.roamx.site"; //Username for SMTP authentication any valid email created in your domain
    $mail_distribusi->Password = "123456"; //Password for SMTP authentication
    //$mail->AddReplyTo("eddyhu71@gmail.com"); //reply-to address
	//$mail->AddAddress("eddyhu71@gmail.com", $_POST['name_feedback']);
	$mail_distribusi->AddAddress($email, $_POST['name_distribution']); 
	$mail_distribusi->AddCC('eddyhu71@gmail.com', 'Info Distribution');
    $mail_distribusi->SetFrom($_POST['email_distribution'], "Info Milibit"); //From address of the mail
    // put your while loop here like below,
    $mail_distribusi->Subject = $_POST['discord_distribution']; //Subject od your mail
//To address who will receive this email
	// $_POST['message_feedback']
    $mail_distribusi->Body = "Name : ".$_POST['name_distribution'].". Discord ID :".$_POST['discord_distribution'].". Community : ".$_POST['community_distribution'].". Email : ". $_POST['email_distribution']. ". Message : ".$_POST['message_distribution'].".</br></br></br> Platform : ".$_POST['platform_distribution'].; //Put your body of the message you can place html code here
	$mail_distribusi->AltBody = "This is body in plain text";
    $send = $mail_distribusi->Send();
	if ($send) {
		echo "distribution_berhasil";
		//header("Location : roamx.site/acun");
	} else {
		echo "distribution_gagal";
		//header("Location : roamx.site/acun");
	}
	} catch (Exception $e) {
		echo "Error 404";
	}
}
?>
