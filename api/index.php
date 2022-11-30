<?php
include '../data.php';
include '../conn.php';
$now=date("Y-m-d H:i:s");

$data='API belum tersedia';

//GET or FETCH datas
if(isset($_POST['get'])) {
  $get=$_POST['get'];

  //single user data / profile
  if ($get == 'user_data') {
    $id=$_GET['id'];

    $data=[];
    $cek=$db->fetchwhere("data_user", "id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("doesnt_exist");
    }else{
      foreach($cek as $ceks){
        $email=$ceks['email'];
        $password=$ceks['password'];
        $nama=$ceks['nama'];
        $jeniskelamin=$ceks['jenis_kelamin'];
        $nohp=$ceks['phone'];
        $photo=$ceks['photo'];
        $address=$ceks['address'];
        $join_date=$ceks['join_date'];

        $datas=array(
          "email"=>$email,
          "password"=>$password,
          "nama"=>$nama,
          "jeniskelamin"=>$jeniskelamin,
          "nohp"=>$nohp,
          "photo"=>$photo,
          "alamat"=>$address,
          "join_date"=>$join_date
        );
      }
      echo json_encode ($datas);
    }
  }

  //single berita
  if ($get == 'berita_data') {
    $id=$_GET['id'];

    $data=[];
    $cek=$db->fetchwhere("berita", "id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("doesnt_exist");
    }else{
      foreach($cek as $ceks){
        $admin_id=$ceks['admin_id'];
        $cekadmin=$db->fetchwhere("admin", "id = '$admin_id'");
        foreach ($cekadmin as $cadmin) {
          $admin = $cadmin['nama'];
        }
        $judul=$ceks['judul'];
        $description=$ceks['description'];
        $lokasi=$ceks['lokasi'];
        $file=$ceks['file'];
        $date=$ceks['date'];

        $datas=array(
          "judul"=>$judul,
          "deskripsi"=>$description,
          "penulis"=>$admin,
          "lokasi"=>$lokasi,
          "cover_photo"=>$file,
          "tgl_publish"=>$date
        );
      }
      echo json_encode ($datas);
    }
  }

  //all berita
  if ($get == 'berita_all') {
    $data=[];
    $cek=$db->fetch("berita");

    foreach($cek as $ceks){
      $admin_id=$ceks['admin_id'];
      $cekadmin=$db->fetchwhere("admin", "id = '$admin_id'");
      foreach ($cekadmin as $cadmin) {
        $admin = $cadmin['nama'];
      }
      $judul=$ceks['judul'];
      $description=$ceks['description'];
      $lokasi=$ceks['lokasi'];
      $file=$ceks['file'];
      $date=$ceks['date'];

      $datas=array(
        "judul"=>$judul,
        "deskripsi"=>$description,
        "penulis"=>$admin,
        "lokasi"=>$lokasi,
        "cover_photo"=>$file,
        "tgl_publish"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //single bantuan data
  if ($get == 'bantuan_data') {
    $id=$_GET['id'];

    $data=[];
    $cek=$db->fetchwhere("bantuan", "id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("bantuan_doesnt_exist");
    }else{
      foreach($cek as $ceks){
        $bantuan_id=$ceks['id'];
        $judul=$ceks['judul'];
        $deskripsi=$ceks['deskripsi'];

        $starter = $ceks['namapembuat'];
        if ($starter == NULL) {
          $starter = 'Anonim';
        }
        $target = $ceks['targetbantuan'];
        $current=$ceks['current'];
        $currentrp="Rp ".number_format($current,0,'','.');
        $absolute=abs(round($current/$target*100));
        $percent = $absolute . '%';

        if($ceks['img']!='') {
          $imgsplit=explode("||", $ceks['img']);
          for ($i=0; $i<count($imgsplit);$i++) {
            if ($i==0) {
              $coverimg=$imgsplit[$i];
            }
          }
        }
        $submit = date("d M Y", strtotime($ceks['date']));
        $cdeadline = $ceks['last_date'];

        $date1=date("d-m-Y",strtotime($now));
        $date2=date("d-m-Y",strtotime($cdeadline));

        $diff=strtotime($date2) - strtotime($date1);
        $dateDiff=abs(round($diff/86400));
        if ($diff < 0) {
          $dateDiff = 0;
        } else {
          $dateDiff= abs(round($diff/86400));
        }

        $datas=array(
          "bantuan_id"=>$bantuan_id,
          "judul"=>$judul,
          "nama"=>$starter,
          "submission_date"=>$submit,
          "deskripsi"=>$deskripsi,
          "current"=>$currentrp,
          "percent"=>$percent,
          "coverimg"=>$coverimg,
          "sisa_hari"=>$dateDiff,

        );
      }
      echo json_encode ($datas);
    }
  }

  //update / postingan Author dari specific bantuan
  if ($get == 'update_bantuan') {
    $bantuan_id=$_POST['bantuan_id'];
    $cek=$db->fetchwhereorder("ubahbantuan", "bantuan_id='$bantuan_id'", "date asc");
    $posts='';
    foreach ($cek as $ceks) {
      $post = $ceks['deskripsi'];
      $submit_time = $ceks['date'];

      $datas=array(
        "post"=>$post,
        "submit_time"=>$submit_time
      );

      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all bantuan kategori
  if ($get == 'bantuan_kategori') {
    $data=[];
    $cek=$db->fetch("kategori_bantuan");

  	foreach ($cek as $ceks) {
  		$datas=array(
  			"id"=>$ceks['id'],
  			"nama"=>$ceks['nama']
  		);
  		array_push($data, $datas);
  	}
  	echo json_encode ($data);
  }

  //all bantuan OR all bantuan of specific kategori by everyone
  if ($get == 'bantuan_all') {
    if(isset($_GET['id'])) {
      $id=$_GET['id'];
    } else {
      $id = 0;
    }

    $data=[];
    if ($id == 0 || $id == NULL) {
      $cek=$db->fetchwhere("bantuan", "status_bantuan = 1");
    } else {
      $cek=$db->fetchwhere("bantuan", "status_bantuan = 1 AND kategori_id = '$id'");
    }

    foreach($cek as $ceks){
      $bantuan_id=$ceks['id'];
      $judul=$ceks['judul'];
      $deskripsi=$ceks['deskripsi'];
      $starter = $ceks['namapembuat'];
      if ($starter == NULL) {
        $starter = 'Anonim';
      }
      $target = $ceks['targetbantuan'];
      $current=$ceks['current'];
      $currentrp="Rp ".number_format($current,0,'','.');
      $absolute=abs(round($current/$target*100));
      $percent = $absolute . '%';

      if($ceks['img']!='') {
        $imgsplit=explode("||", $ceks['img']);
        for ($i=0; $i<count($imgsplit);$i++) {
          if ($i==0) {
            $coverimg=$imgsplit[$i];
          }
        }
      }
      // $submit = date("d M Y", strtotime($ceks['date']));
      $cdeadline = $ceks['last_date'];

      $date1=date("d-m-Y",strtotime($now));
      $date2=date("d-m-Y",strtotime($cdeadline));

      $diff=strtotime($date2) - strtotime($date1);
      $dateDiff=abs(round($diff/86400));
      if ($diff < 0) {
        $dateDiff = 0;
      } else {
        $dateDiff= abs(round($diff/86400));
      }

      $datas=array(
        "bantuan_id"=>$bantuan_id,
        "judul"=>$judul,
        "current"=>$currentrp,
        "percent"=>$percent,
        "coverimg"=>$coverimg,
        "sisa_hari"=>$dateDiff,
        "nama"=>$starter

      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all personal donasi history
  if ($get == 'personal_donasi_history') {
    $uid=$_GET['id'];

    $data=[];
    $cek = $db->fetchwhere("donasi", "user_id = '$uid'");
    foreach ($cek as $ceks) {
      $bantuan_id=$ceks['bantuan_id'];
      $jumlah=$ceks['jumlah'];
      // $lstatus=$ceks['status_laporan'];
      $date=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        "bantuan_id"=>$bantuan_id,
        "jumlah"=>$jumlah,
        // "status"=>$lstatus,
        "tgl_donasi"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all donasi history of specific bantuan/campaign
  if ($get == 'all_donasi_history') {
    $id=$_GET['id'];

    $data=[];
    $cek = $db->fetchwhere("donasi", "bantuan_id = '$id'");
    foreach ($cek as $ceks) {
      $bantuan_id=$ceks['bantuan_id'];
      $jumlah=$ceks['jumlah'];
      $atas_nama=$ceks['atas_nama'];
      $catatan=$ceks['catatan'];
      // $lstatus=$ceks['status_laporan'];
      $date=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        "bantuan_id"=>$bantuan_id,
        "jumlah"=>$jumlah,
        "atas_nama"=>$atas_nama,
        "catatan"=>$catatan,
        // "status"=>$lstatus,
        "tgl_donasi"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //single laporan data
  if ($get == 'laporan_data') {
    $id=$_GET['id'];
    $data=[];
    $cek = $db->fetchwhere("laporan", "id = '$id' ");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("doesnt_exist");
    }else{
      foreach ($cek as $ceks) {
        $lid=$ceks['id'];
        $lkategori_id=$ceks['id_category'];
        $lkode=$ceks['kode_laporan'];
        $ljudul=$ceks['judul'];
        $ldescription=$ceks['description'];
        $lfile=$ceks['file'];
        $lsolusi=$ceks['solusi'];
        $lstatus=$ceks['status_laporan'];
        $ldate=date("d m Y",strtotime($ceks['date']));

        $datas=array(
          "laporan_id"=>$lid,
          "kategori_laporan"=>$lkategori_id,
          "kode_laporan"=>$lkode,
          "judul"=>$ljudul,
          "deskripsi"=>$ldescription,
          "file"=>$lfile,
          "solusi"=>$lsolusi,
          "status"=>$lstatus,
          "tgl_lapor"=>$ldate
        );
        // array_push($data, $datas);
      }
      echo json_encode ($datas);
    }
  }

  //all laporan kategori
  if ($get == 'laporan_kategori') {
    $data=[];
    $cek=$db->fetch("category");

    foreach ($cek as $ceks) {
      $datas=array(
        "id"=>$ceks['id'],
        "nama"=>$ceks['nama']
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all laporan OR all laporan of specific kategori by everyone
  if ($get == 'laporan_all') {
    $data=[];

    if(isset($_GET['id'])) {
      $id=$_GET['id'];
    } else {
      $id = 0;
    }

    $data=[];
    if ($id == 0 || $id == NULL) {
      $cek=$db->fetchwhere("bantuan", "status_laporan > 0");
    } else {
      $cek=$db->fetchwhere("bantuan", "status_laporan > 0 AND id_category = '$id'");
    }

    $cek = $db->fetchwhere("laporan", "status_laporan > 0 ");
    foreach ($cek as $ceks) {
      $lid=$ceks['id'];
      $lkategori_id=$ceks['id_category'];
      $lkode=$ceks['kode_laporan'];
      $ljudul=$ceks['judul'];
      $ldescription=$ceks['description'];
      $lfile=$ceks['file'];
      $lsolusi=$ceks['solusi'];
      $lstatus=$ceks['status_laporan'];
      $ldate=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        "laporan_id"=>$lid,
        "kategori_laporan"=>$lkategori_id,
        "kode_laporan"=>$lkode,
        "judul"=>$ljudul,
        "deskripsi"=>$ldescription,
        "file"=>$lfile,
        "solusi"=>$lsolusi,
        "status"=>$lstatus,
        "tgl_lapor"=>$ldate
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all personal laporan history
  if ($get == 'personal_laporan_history') {
    $uid=$_GET['id'];

    //status laporan
    $data=[];
    $cek = $db->fetchwhere("laporan", "user_id = '$uid'");
    foreach ($cek as $ceks) {
      $lkode=$ceks['kode_laporan'];
      $ljudul=$ceks['judul'];
      $lstatus=$ceks['status_laporan'];
      $ldate=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        "kode_laporan"=>$lkode,
        "judul"=>$ljudul,
        "status"=>$lstatus,
        "tgl_lapor"=>$ldate
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

  //all yayasan kategori
  if ($get == 'yayasan_kategori') {
    $data=[];
    $cek=$db->fetch("kategori_yayasan");

    foreach ($cek as $ceks) {
      $datas=array(
        "id"=>$ceks['id'],
        "nama"=>$ceks['namakategori']
      );
      array_push($data, $datas);
    }
    echo json_encode ($data);
  }

}

//POST datas
if(isset($_POST['post'])) {
  $post=$_POST['post'];

  //user register
  if ($post == 'register') {
    $email=$_POST['email'];
    $pass=$_POST['pass'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode("invalid_email");
      exit();
    }
    $cek = $db->fetchwhere("data_user", "email = '$email'");
    $itung=count($cek);

    if($itung>0){
      echo json_encode("email_registered");
      exit();
    }else{
      $nama=$_POST['nama'];
      $username=$_POST['username'];
      $phone=$_POST['phone'];
      $jenis_kelamin=$_POST['jenis_kelamin'];
      $alamat=$_POST['alamat'];

      $masukkan=array(
  			"email"=>$email,
        "password"=>$pass,
  			"nama"=>$nama,
        "username"=>$username,
        "phone"=>$phone,
        "jenis_kelamin"=>$jenis_kelamin,
        "alamat"=>$alamat,
        "join_date"=>$now,
  		);
      $insertmember=$db->insert("data_user",$masukkan);
  	}
    if ($insertmember) {
      echo json_encode("ok");
    } else {
      echo json_encode("fail");
    }
  }

  //user login
  if ($post == 'login') {
    $email=$_POST['email'];
    $pass=$_POST['pass'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode("invalid_email");
      exit();
    }
    $cek = $db->fetchwhere("data_user", "email = '$email'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("invalid_email");
    }else{
      foreach($cek as $ceks){
        $uid=$ceks['id'];
        $uemail=$ceks['email'];
        $passdb=$ceks['password'];
      }
      if($pass != $passdb){
        echo json_encode("wrong_pass");
      }
      else{
        echo json_encode("ok") ;
      }
    }
  }

  //user update profile
  if ($post == 'update_profile') {
    $id=$_POST['id'];
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $uname=$_POST['uname'];
    $fname=$_POST['name'];
    $jeniskelamin=$_POST['jeniskelamin'];
    $phone=$_POST['phone'];
    $address=$_POST['address'];
    $update_date=$now;

    if($uname==''){
        echo "Username is empty";
    }
    else if($fname==''){
        echo "Firstname is empty";
    }
    else if($jeniskelamin==''){
        echo "Gender is not selected";
    }
    else if($phone==''){
        echo "Phone is empty";
    }
    else if($address=='')
    {
        echo "Address is empty";
    }

    if(empty($_FILES['file'])){
      $whereu=array("email"=>$email);
      $updateu=array(
          "password"=>$ps,
          "username"=>$uname,
          "nama"=>$name,
          "jenis_kelamin"=>$jeniskelamin,
          "phone"=>$phone,
          // "photo"=>$filepath,
          "address"=>$address,
          "update_date"=>$update_date,
      );
      $a=$db->update("data_user",$whereu,$updateu);
      if($a){
          echo json_encode("ok");
          // header("Location:profile_user.php");
      }
      else{
          echo json_encode("failed");
      }
    }
    else{
      $cekfoto=$db->fetchwhere("data_user", "id='$id'");
      if(count($cekfoto)>0) {
        foreach ($cekfoto as $abc) {
          // $oldpath="../".$abc['avatar'];
          $oldpath=$abc['photo'];
        }
      } else {
        $oldpath='';
      }
      $imgpath='';
      $mainimg =$_FILES['file'];
      $dir="file/";
      $orimainimgname=$mainimg['name'];
      $fileSplit=explode(".",$orimainimgname);
      $fileExt=end($fileSplit);
      $random=rand(100,999);
      $newname="$id-$uname-pp-$random";
      $uploading=imguploadprocesswithreplace($oldpath,$mainimg,$dir,$newname);
      if($uploading=='big'){
          // echo "ERROR: Image size is more than 2 MB.";
          echo json_encode("image_over_2mb");
          exit();
      }else if($uploading=='ext'){
          // echo "ERROR: File is not JPG or PNG";
          echo json_encode("wrong_file_format");
      }else if($uploading=='err'){
          // echo "ERROR: An error occured while processing file. Please try again";
          echo json_encode("process_error");
      }else if($uploading=='err2'){
          // echo "ERROR: File not uploaded. Try again.";
          echo json_encode("not_uploaded");
      }else if($uploading=='ok'){
          $imgpath=$dir.$newname.".".$fileExt;
      }
      // $filepath ="file/".$_FILES['file']["name"];
      // move_uploaded_file($files["tmp_name"],$filepath;
      if($uploading){
        $file = $_FILES['file'];

        $whereu=array("email"=>$email);
        $updateu=array(
            "password"=>$pass,
            "username"=>$uname,
            "nama"=>$fname,
            "jenis_kelamin"=>$jeniskelamin,
            "phone"=>$phone,
            "photo"=>$imgpath,
            "address"=>$address,
            "update_date"=>$update_date,
        );
        $a=$db->update("data_user",$whereu,$updateu);
        if($a){
            echo json_encode("ok");
        }
        else{
            echo json_encode("failed");
        }
      }
      else {
        echo json_encode("img_upload_error");
      }
    }
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
    move_uploaded_file($_FILES["file"]["tmp_name"],"../".$filepath);

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
      "file"=>$filepath,
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
    move_uploaded_file($_FILES["file"]["tmp_name"],"../".$filepath);

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
      "img"=>$filepath,
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

  //new donasi
  if ($post == 'new_donasi') {
    $table="donasi";
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

    $random = rand(100,105);
    // $transaction_code='DN/'.date("dmY").'/'.$donasi_id.'/'.$user_id.'/'.$random;
    $date=date('dmY');
    $transaction_code="DN/$date/$bantuan_id/$user_id/$random";
    $cek=$db->fetchwhere($table, "transaction_code='$transaction_code'");
    $itung=count($cek);

    while (count($cek) > 0) {
      $random = rand(100,999);
      $transaction_code="DN/$date/$bantuan_id/$user_id/$random";

      $cek=$db->fetchwhere($table, "transaction_code='$transaction_code'");
      $itung=count($cek);
    }

    $trans_amt = $jumlah + $random;

    $masukkan=array(
      "transaction_code"=>$transaction_code,
      "bantuan_id"=>$bantuan_id,
      "user_id"=>$user_id,
      // "username"=>$username,
      "donatur"=>$nama,
      // "anonim"=>$anonim,
      "jumlah"=>$jumlah,
      "jumlah_transfer"=>$trans_amt,
      "metode"=>$metode,
      "email"=>$e,
      "komen"=>$komen,
      "date"=>$now
    );
    $insKonfirmDonasi=$db->insert("donasi",$masukkan);
    // echo 1;
    // echo "'/qlue-sh/summary.php?method=$method&tf=$trans_amt";

    if ($insKonfirmDonasi) {
      echo "method=$metode&tf=$trans_amt";
      // echo json_encode("ok");
    } else {
      echo json_encode("fail");
      // echo $transaction_code;
    }
  }

  //update donasi foto
  if ($post == 'update_donasi_foto') {
    $code=$_POST['code'];
    if(empty($_FILES['file'])){
        echo json_encode("photo_error");
        exit();
    }
    $cek=$db->fetchwhere("donasi", "transaction_code = '$code'");
    $itung=count($cek);

    if ($itung < 1) {
      echo json_encode("code_doesnt_exist");
    } else {
      foreach($cek as $ceks) {
        $uid = $ceks['user_id'];
      }

      $filepath ="donasi/$uid".rand(100,999).$_FILES["file"]["name"];
      move_uploaded_file($_FILES["file"]["tmp_name"],"../".$filepath);

      $where=array("transaction_code"=>$code);
      $update=array(
        "foto_bukti"=>$filepath,
        "status"=>1,
      );
      $updateDonasi=$db->update("donasi",$where,$update);
      if($updateDonasi){
        echo json_encode("ok");
      } else {
        echo json_encode("failed");
      }
    }
  }

}

//To do

//GET
if(isset($_POST['gett'])) {
  $get = $_POST['gett'];


}

//POST
if (isset($_POST['postt'])) {
  $post = $_POST['postt'];

  //kayaknya belum perlu
  if ($post == 'editlaporan') {

  }

  //hmm, perlu?
  if ($post == 'editbantuan') {

  }

  if ($post == 'upgradeakun') {

  }




}
?>
