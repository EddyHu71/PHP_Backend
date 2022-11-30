<?php
include '../data.php';
include '../conn.php';
$now=date("Y-m-d H:i:s");

$data='API belum tersedia';
$url='http://guritadigital.co.id/horas/';

//POST datas
if(isset($_POST['post'])) {
  $post=$_POST['post'];

  //user register
  if ($post == 'register') {
    $email=$_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode("invalid_email");
      exit();
    }
    $cek = $db->fetchwhere("data_user", "email = '$email'");
    $itung=count($cek);

    if($itung>0){
      echo json_encode("email_registered");
      exit();
    }
    
    $username=$_POST['username'];
    
    $cek = $db->fetchwhere("data_user", "username = '$username");
    $itung=$count($cek);
    if($itung>0){
      echo json_encode("username_registered");
      exit();
    }
    
    $pass=$_POST['pass'];
    $nama=$_POST['nama'];
    $phone=$_POST['phone'];
    // $avatar=$_POST['avatar'];
    
    // else{
    
    // $alamat=$_POST['alamat'];

    $masukkan=array(
    "email"=>$email,
    "password"=>$pass,
    "nama"=>$nama,
    "username"=>$username,
    "phone"=>$phone,
    // "jenis_kelamin"=>$jenis_kelamin,
    // "alamat"=>$alamat,
    // "avatar"=>$avatar,
    "join_date"=>$now,
      );
    $insertmember=$db->insert("data_user",$masukkan);
  	
    if ($insertmember) {
      echo json_encode("ok");
    } else {
      echo json_encode("fail");
    }
  }

  //user login
  if ($post == 'login') {
    $email=$_POST['email'];
    // $username=$_POST['username'];
    $pass=$_POST['pass'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //   echo json_encode("invalid_email");
    //   exit();
        $cek = $db->fetchwhere("data_user", "username = '$email'");
        $itung=count($cek);
        
        if ($itung<1) {
          echo json_encode("invalid_username");
          exit();
        }
        // $loginby="username";
    } else {
        $cek = $db->fetchwhere("data_user", "email = '$email'");
        $itung=count($cek);
    
        if($itung<1){
          echo json_encode("invalid_email");
          exit();
        }
        // $loginby="email";
    }
    
    foreach($cek as $ceks){
        $uid=$ceks['id'];
        $uemail=$ceks['email'];
        $uname=$ceks['username'];
        $passdb=$ceks['password'];
    }
    if($pass != $passdb){
        echo json_encode("invalid_pass");
        exit();
    }
    else{
    // echo json_encode("ok") ;
    // echo json_encode($uid);
    
    $datas=array(
        "user_id"=>$uid,
        "email"=>$uemail,
        "username"=>$uname,
        "pass"=>$passdb,
        // "loginby"=>$loginby
    );
    echo json_encode($datas);
    }
  }

  //user update profile
  if ($post == 'update_profile') {
    $id=$_POST['id'];
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $uname=$_POST['username'];
    $nama=$_POST['nama'];
    // $jeniskelamin=$_POST['jeniskelamin'];
    $phone=$_POST['phone'];
    // $address=$_POST['address'];
    $update_date=$now;

    if($uname==''){
        echo json_encode("Username is empty");
        exit();
    }
    else if($nama==''){
        echo json_encode("Name is empty");
        exit();
    }
    else if ($email==''){
        echo json_encode("Email is empty");
        exit();
    }
    else if ($pass=='') {
        echo json_encode("Password is empty");
        exit();
    }
    // else if($jeniskelamin==''){
    //     echo json_encode("Gender is not selected");
    // }
    else if($phone==''){
        echo json_encode("Phone number is empty");
        exit();
    }
    // else if($address=='')
    // {
    //     echo json_encode("Address is empty");
    //     exit();
    // }
    
    $whereu=array("id"=>$id);
      $updateu=array(
          "password"=>$pass,
          "username"=>$uname,
          "nama"=>$nama,
        //   "avatar"=>$avatar,
        //   "jenis_kelamin"=>$jeniskelamin,
          "phone"=>$phone,
          // "photo"=>$filepath,
        //   "address"=>$address,
          "update_date"=>$update_date,
      );
      $a=$db->update("data_user",$whereu,$updateu);
      if($a){
          echo json_encode("ok");
      }
      else{
          echo json_encode("fail");
      }

    // if(empty($_FILES['file'])){
    //   $whereu=array("email"=>$email);
    //   $updateu=array(
    //       "password"=>$pass,
    //       "username"=>$uname,
    //       "nama"=>$name,
    //       "jenis_kelamin"=>$jeniskelamin,
    //       "phone"=>$phone,
    //       // "photo"=>$filepath,
    //       "address"=>$address,
    //       "update_date"=>$update_date,
    //   );
    //   $a=$db->update("data_user",$whereu,$updateu);
    //   if($a){
    //       echo json_encode("ok");
    //   }
    //   else{
    //       echo json_encode("fail");
    //   }
    // }
    // else{
    //   $cekfoto=$db->fetchwhere("data_user", "id='$id'");
    //   if(count($cekfoto)>0) {
    //     foreach ($cekfoto as $abc) {
    //       // $oldpath="../".$abc['avatar'];
    //       $oldpath=$abc['photo'];
    //     }
    //   } else {
    //     $oldpath='';
    //   }
    //   $imgpath='';
    //   $mainimg =$_FILES['file'];
    //   $dir="file/";
    //   $orimainimgname=$mainimg['name'];
    //   $fileSplit=explode(".",$orimainimgname);
    //   $fileExt=end($fileSplit);
    //   $random=rand(100,999);
    //   $newname="$id-$uname-pp-$random";
    //   $uploading=imguploadprocesswithreplace($oldpath,$mainimg,$dir,$newname);
    //   if($uploading=='big'){
    //       // echo "ERROR: Image size is more than 2 MB.";
    //       echo json_encode("image_over_2mb");
    //       exit();
    //   }else if($uploading=='ext'){
    //       // echo "ERROR: File is not JPG or PNG";
    //       echo json_encode("wrong_file_format");
    //   }else if($uploading=='err'){
    //       // echo "ERROR: An error occured while processing file. Please try again";
    //       echo json_encode("process_error");
    //   }else if($uploading=='err2'){
    //       // echo "ERROR: File not uploaded. Try again.";
    //       echo json_encode("not_uploaded");
    //   }else if($uploading=='ok'){
    //       $imgpath=$dir.$newname.".".$fileExt;
    //   }
    //   // $filepath ="file/".$_FILES['file']["name"];
    //   // move_uploaded_file($files["tmp_name"],$filepath;
    //   if($uploading){
    //     $file = $_FILES['file'];

    //     $whereu=array("email"=>$email);
    //     $updateu=array(
    //         "password"=>$pass,
    //         "username"=>$uname,
    //         "nama"=>$fname,
    //         "jenis_kelamin"=>$jeniskelamin,
    //         "phone"=>$phone,
    //         "photo"=>$imgpath,
    //         "address"=>$address,
    //         "update_date"=>$update_date,
    //     );
    //     $a=$db->update("data_user",$whereu,$updateu);
    //     if($a){
    //         echo json_encode("ok");
    //     }
    //     else{
    //         echo json_encode("fail");
    //     }
    //   }
    //   else {
    //     echo json_encode("img_upload_error");
    //   }
    // }
  }

  //new laporan
  if ($post == 'new_laporan') {
    $uid = $_POST['user_id'];
    $cat_id=$_POST['category_id'];
    $username=$_POST['username'];
    $judul=$_POST['judul'];
    $description=$_POST['description'];
    $lokasi=$_POST['lokasi'];
    $solusi=$_POST['solusi'];

    $filepath ="file/$uid".rand(100,999).$_FILES["file"]["name"];
    move_uploaded_file($_FILES["file"]["tmp_name"],$url.$filepath);

    $date=date('dmy',strtotime($now));

    $random=rand(100,999);
    $code="LP/$cat_id/$date/$random/$uid";
    $cekgenerate=$db->fetchwhere("laporan","kode_laporan='$code'");
    $hitung=count($cekgenerate);
    while($hitung > 0){
        $random=rand(100,999);
        $code="LP/$cat_id/$date/$random/$uid";
        $cekgenerate->fetchwhere("laporan","kode_laporan='$code'");
        $hitung=count($cekgenerate);
    }

    $masukkan=array(
      // "username"=>$username,
      "id_category"=>$cat_id,
      "judul"=>$judul,
      "description"=>$description,
      "lokasi"=>$lokasi,
      "solusi"=>$solusi,
      "kode_laporan"=>$code,
      "date"=>$now,
      "file"=>$url.$filepath,
    );
    $insertlaporan=$db->insert("laporan",$masukkan);
    if ($insertlaporan) {
      echo json_encode("ok");
    } else {
      echo json_encode("fail");
    }
  }

  //new bantuan / galang dana / campaign
  if ($post == 'new_bantuan') {
    $uid = $_POST['user_id'];
    // $username=$_POST['username'];
    $cat_id=$_POST['category_id'];
    $judul=$_POST['judul'];
    $target=$_POST['target'];
    // $pilihan=$_POST['pilihanpembuat'];
    $namapembuat=$_POST['namapembuat'];
    $description=$_POST['description'];
    $bataswaktu=$_POST['bataswaktu'];

    $filepath ="bantuan/$uid".rand(100,999).$_FILES["file"]["name"];
    move_uploaded_file($_FILES["file"]["tmp_name"],$url.$filepath);

    $cdatetime=new DateTime($now);
    $formatted_date= $cdatetime->format('Y-m-d');
    $cnow=strtotime($formatted_date);
    $cdtime=strtotime('+'.$bataswaktu.' day', $cnow);
    $enddate=date('Y-m-d',$cdtime);

    $masukkan=array(
      // "username"=>$username,
      // "kode_bantuan"=>$code,
      "kategori_id"=>$cat_id,
      "user_id"=>$uid,
      "judul"=>$judul,
      "namapembuat"=>$namapembuat,
      "targetbantuan"=>$target,
      "targetwaktu"=>$bataswaktu,
      // "pilihanpembuat"=>$pilihan,
      "deskripsi"=>$description,
      "img"=>$url.$filepath,
      "date"=>$now,
      "last_date"=>$enddate

    );
    $insertbantuan=$db->insert("bantuan",$masukkan);
    if ($insertbantuan) {
      echo json_encode("ok");
    } else {
      echo json_encode("fail");
    }
  }

  //post new update for a bantuan / galang dana / campaign
  if ($post == 'updatebantuan') {
    $bantuan_id=$_POST['bantuan_id'];
  	$deskripsi=$_POST['deskripsi'];

  	$insrep=array(
  		"bantuan_id"=>$bantuan_id,
  		"deskripsi"=>$deskripsi,
  		"date"=>$now
  	);

  	$postUpdate=$db->insert("ubahbantuan", $insrep);

    if ($postUpdate) {
      echo json_encode("ok");
    } else {
      echo json_encode("fail");
    }
  }

  //new donasi / ikut donasi (user side)
  if ($post == 'new_donasi') {
	$bantuan_id=$_POST['bantuan_id'];
	$user_id=$_POST['user_id'];

    $nama=$_POST['nama'];
    $e=$_POST['email'];
    if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
        echo json_encode("invalid_email");
        exit();
    }
    $metode=$_POST['metode'];
    $jumlah=$_POST['jumlah'];
    // $anonim=$_POST['anonim'];
    $komen=$_POST['komen'];

    $random = rand(100,999);
    $date=date('dmY');

    $masukkan=array(
      // "transaction_code"=>$transaction_code,
      "bantuan_id"=>$bantuan_id,
      "user_id"=>$user_id,
      // "username"=>$username,
      "donatur"=>$nama,
      // "anonim"=>$anonim,
      "jumlah"=>$jumlah,
      "kode_unik"=>$random,
      "metode"=>$metode,
      "email"=>$e,
      "komen"=>$komen,
      "date"=>$now
    );
    $insKonfirmDonasi=$db->insertreturnid("donasi",$masukkan);

    if ($insKonfirmDonasi) {
      $tr_code="DN/$insKonfirmDonasi";
      $upd=array("transaction_code"=>$tr_code);
      $where=array("id"=>$insKonfirmDonasi);
      $konfDonasi=$db->update("donasi",$where,$upd);
      if($konfDonasi) {
        // echo "?id=$insKonfirmDonasi";
        echo json_encode("ok");
      } else {
        echo json_encode("fail");
      }
    } else {
      echo json_encode("fail");
    }
  }

  //update donasi foto
  if ($post == 'update_donasi_foto') {
    $id=$_POST['id'];
    if(empty($_FILES['file'])){
        echo json_encode("photo_error");
        exit();
    }
    $cek=$db->fetchwhere("donasi", "id = '$id'");
    $itung=count($cek);

    if ($itung < 1) {
      echo json_encode("invalid_id");
    } else {
      foreach($cek as $ceks) {
        $uid = $ceks['user_id'];
      }

      $filepath ="donasi/$uid".rand(100,999).$_FILES["file"]["name"];
      move_uploaded_file($_FILES["file"]["tmp_name"],$url.$filepath);

      $where=array("transaction_code"=>$code);
      $update=array(
        "foto_bukti"=>$url.$filepath,
        "status"=>1,
      );
      $updateDonasi=$db->update("donasi",$where,$update);
      if($updateDonasi){
        echo json_encode("ok");
      } else {
        echo json_encode("fail");
      }
    }
  }

}

?>
