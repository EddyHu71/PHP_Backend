<?php
include '../data.php';
include '../conn.php';
date_default_timezone_set("Asia/Jakarta");
$now=date("d-m-Y H:i:s");

$data='API belum tersedia';
$url='http://guritadigital.co.id/horas/';

//POST datas
if(isset($_POST['post'])) {
  $post=$_POST['post'];
  //user register
    if ($post == "user_register") {
        //echo $now;
        $email=$_POST['email_register'];
        //$username = $_POST['username_register'];
        $nama = $_POST['nama_register'];
        // $email = $_POST['email_register'];
        $phone = $_POST['phone_register'];
        $pass = $_POST['password_register'];
        $phone = $_POST['phone_register'];
        $avatar = $_POST['avatar_register'];
        //$file_name = $_POST['nama_file_avatar'];
        $validasi = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($validasi < 0) {
          echo json_encode("invalid_email");
          exit();
        }
        $cekemail = $db->fetchwhere("table_user", "email = '$email'");
        if(count($cekemail)>0){
          echo json_encode("email_registered");
          exit();
        }
    
        //$username=$_POST['username'];
    
        // $cekusername = $db->fetchwhere("data_user", "username = '$username");
        // if(count($cekusername)>0){
        //   echo json_encode("username_registered");
        //   exit();
        // }
        $masukkan=array(
        "email"=>$email,
        "password"=>$pass,
        "nama"=>$nama,
        //"username"=>$username,
        "phone"=>$phone,
        "photo"=>$avatar,
        // "nama_file_avatar" =>$file_name,
        // "jenis_kelamin"=>$jenis_kelamin,
        // "alamat"=>$alamat,
        // "avatar"=>$avatar,
        "join_date"=>$now,
          );
        $insertmember=$db->insert("table_user",$masukkan);
        if (count($insertmember) > 0) {
          echo json_encode("register_berhasil");
          exit();
        } else {
          echo json_encode("register_gagal");
          exit();
        }
    }

    //user login
    if ($post == "login_user") {
      //echo $post;
      $deviceid = $_POST['device_id_login'];
      $phones = $_POST['phone_login'];
      $password = $_POST['pass_login'];
      $validasi = filter_var($username, FILTER_VALIDATE_EMAIL);
      //echo count($validasi);
      if (count($validasi) > 0) {
          $cek = $db->fetchwhere("table_user", "phone='$phones' && password='$password'");
          //echo count($cek);
          if (count($cek) > 0) {
              //echo $deviceid;
              foreach($cek as $ceks) {
                  $idUser = $ceks['id'];
                  $nama = $ceks['nama'];
                  $photo = $ceks['nama_file_avatar'];
                  //$user_name = $ceks['username'];
                  $email = $ceks['email'];
                  $phone = $ceks['phone'];
                  $address = $ceks['address'];
                  $password = $ceks['password'];
              }
                  $wheres = array("id"=>$idUser);
                  $fields = array("status_user"=>"1", "device_id" => $deviceid);
                  $updates = $db->update("table_user", $wheres, $fields);
                  //echo count($updates);
                if (count($updates) > 0) {
                    $data = array(
                      "status" => "login",
                      "id_user" => $idUser,
                      "device_id" => $deviceid,
                      "nama" => $nama,
                      "photo" => $photo,
                      "username" => $user_name,
                      "email" => $email,
                      "address" => $address,
                      "password" => $password,
                      );
                    echo json_encode($data);
                } else {
                    echo json_encode("gagal_login");
                    exit();
                }
          } else {
              echo json_encode("tidak_bisa_login");
              exit();
          }
      } else {
          $cek = $db->fetchwhere("data_user", "username='$username' && password='$password'");
          echo count($cek);
          if ($count($cek) > 0) {
              foreach($cek as $ceks) {
                  $idUser = $ceks['id'];
                  $deviceid = $ceks['device_id'];
                  $nama = $ceks['nama'];
                  $photo = $ceks['nama_file_avatar'];
                  $user_name = $ceks['username'];
                  $email = $ceks['email'];
                  $phone = $ceks['phone'];
                  $address = $ceks['address'];
                  $password = $ceks['password'];
                  //$status_login = $ceks['status_user'];
              }
                  $wheres = array("id"=>$idUser);
                  $fields = array("status_user"=>"1", "device_id" => $deviceid);
                  $updates = $db->update("data_user", $wheres, $fields);
              if (count($updates) > 0) {
                  $data = array(
                      "status" => "login",
                      "id_user" => $idUser,
                      "device_id" => $deviceid,
                      "nama" => $nama,
                      "photo" => $photo,
                      "username" => $user_name,
                      "email" => $email,
                      "address" => $address,
                      "password" => $password,
                      "status_user" => $status_login,
                );
                echo json_encode($data);
              } else {
                  echo json_encode("gagal_login");
              }
          } else {
              echo json_encode("username_tidak_terdaftar");
              exit();
          }
      }
  }
  
    //logout
    if ($post == "logout") {
        $userid = $_POST['id_user_logout'];
        //"status_user=0"
        $data = array('status_user'=>'0');
        $datawhere = array ('id'=>$userid);
        $cekupdate = $db->update("data_user", $datawhere, $data);
        if (count($cekupdate) > 0) {
            echo json_encode("logout_berhasil");
        } else {
           echo json_encode("logout_gagal");
        }
    }
    
    //lupa password
    if ($post == "lupa_password_berhasil") {
      $email = $_POST['email_user'];
      $cek = $db->fetchwhere('data_user', "email = '$email'");
      if (count($cek)) {
          $update = $db->update("data_user", "password='$passwordbaru'");
          if ($update) {
              echo json_encode("update_password_berhasil");
          } else {
             echo json_encode("update_password_gagal");              
          }
      } else {
          echo json_encode("data_user_invalid");
      }
    }
  
      //user update profile
    if ($post == 'update_profile') {
        $id=$_POST['id'];
        $email=$_POST['email'];
        $pass=$_POST['pass'];
        $uname=$_POST['username'];
        $nama=$_POST['nama'];
        $avatar=$_POST['avatar'];
        if ($avatar < 1 && $avatar > 9) {
            echo json_encode("invalid_avatar");
        }
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
              "avatar"=>$avatar,
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

      //new pesan (ke admin)
    if ($post == 'message_admin') {
        $uid = $_POST['user_id'];
        $nama = $_POST['nama'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
    
        $ins=array(
          "user_id"=>$uid,
          "nama"=>$nama,
          "subject"=>$subject,
          "message"=>$message,
          "date"=>$now
        );
    
        $insert=$db->insert("email",$ins);
        if ($insert) {
          echo json_encode("ok");
        } else {
          echo json_encode("fail");
        }
      }
      
    if ($post == 'check_device_id') {
        $deviceid = $_POST['user_device_id'];
        $username = $_POST['user_username'];
        $cek = $db->fetchwhere("data_user", "device_id='$deviceid'");
        if (count($cek) > 0) {
            $idUser = $cek[0]['id'];
            $status_login = $cek[0]['status_user'];
            $username = $cek[0]['username'];
            $nama = $cek[0]['nama'];
            $phone = $cek[0]['phone'];
            $nama_file_avatar = $cek[0]['nama_file_avatar'];
            $address = $cek[0]['address'];
            $email = $cek[0]['email'];
            $password = $cek[0]['password'];
            $hasil = array(
                "status_login" => $status_login,
                "id_user" => $idUser,
                "username" => $username,
                "nama" => $nama,
                "email" => $email,
                "photo" => $nama_file_avatar,
                "phone" => $phone,
                "address" => $address,
                "password" => $password,
            );
            echo json_encode($hasil);
        } else {
            echo json_encode("tidak_ada");
            exit();
        }
    }

    if ($post == "donasi_user") {
        $data = [];
        $uid = $_POST['id_user_donasi'];
        $cek = $db->fetchwhere("data_user", "id='$uid'");
        if (count($cek) > 0) {
            $cekdonasi = $db->fetchwhere("bantuan", "user_id='$uid'");
            if (count($cekdonasi) > 0) {
                foreach($cekdonasi as $ceks) {
                    $id = $ceks['id'];
                    $iduserdonasi = $ceks['id'];
                    $judul = $ceks['judul'];
                    $deskripsi = $ceks['deskripsi'];
                    $gambar = $ceks['img'];
                    $date = $ceks['date'];
                    $last_date= $ceks['last_date'];
                    $status_bantuan=$ceks['status_bantuan'];
                $datas = array(
                    "id_donasi" => $id,
                    "id_user_donasi" => $iduserdonasi,
                    "image_user_donasi" => $gambar,
                    "title_user_donasi" => $judul,
                    "desc_user_donasi" => $deskripsi,
                    "date_user_donasi" => $date,
                    "lastdate_user_donasi" => $last_date,
                    "status_donasi" => $status_bantuan,
                );
                array_push($data, $datas);
                }
            }
            echo json_encode($data);
        } else {
            echo json_encode("user_tidak_ada");
        }
    }
    
    if ($post == "tambah_donasi_user") {
        $idUser = $_POSTT['id_user'];
        $cekuser = $db->fetchwhere("data_user", "id='$idUser'");
        if (count($cekuser) > 0) {
            $datas = array (
                "judul_donasi" => $_POST['judul_donasi'],
                "deskripsi_donasi" => $_POST['deskripsi_donasi'],
                "awal_tanggal_donasi" => $_POST['awal_tanggal_donasi'],
                "akhir_tanggal_donasi" => $_POST['akhir_tanggal_donasi']
                );
            $tambahdonasi = $db->insert("data_donasi", $datas);
            if (count($tambahdonasi) > 0) {
                $datas = array(
                    "judul_pesan" => "Penggalangan Donasi",
                    "image_pesan" => $_POST['gambar_pesan'],
                    "tanggal_pesan" => $now, 
                    "deskripsi_pesan"=> "Donasi anda berhasil ditambahkan",
                    "user_id_pesan" => $idUser,
                    );
                $kirimpesan = $db->insert("pesan", "$datas");
                echo json_encode("donasi_berhasil_ditambahkan");
            } else {
                echo json_encode("donasi_gagal_ditambahkan");
            }
        }
    }
    
    if ($post == "edit_donasi_user") {
        $idUser = $_POST['id_user'];
        $idDonasi = $_POST['id_donasi'];
        $cekUser = $db->fetchwhere("data_donasi", "id_donasi='$idUser' && id_user_donasi = '$idDonasi'");
        if (count($cekUser) > 0) {
            $datas = array (
                );
        }
        
    }
    
    if ($post == "hapus_donasi_user") {
        $idUser = $_POST['id_user'];
        $idDonasi = $_POST['id_donasi'];
        $cekuser = $db->fetchwhere("data_donasi", "id_user_donasi = '$idUser'");
        if (count($cekUser) > 0) {
            $hapus = $db->delete("data_donasi", "id_donasi = '$idDonasi'");
            if (count($hapus) > 0) {
                echo json_encode("delete_donasi_berhasil");
            } else {
                echo json_encode("delete_donasi_gagal");
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
  
      if ($post == "tambah_user_donasi") {
          $id_donasi = $_POST['id_user_donasi'];
          $file_photo_donasi = $_POST['photo_file_donasi'];
          $nama_photo_donasi = $_POST['photo_user_donasi'];
          $judul_donasi = $_POST['judul_donasi'];
          $deskripsi_donasi = $_POST['deskripsi_donasi'];
          $awalTanggal_donasi = $_POST['awal_tanggal_donasi'];
          $akhirTanggal_donasi = $_POST['akhir_tanggal_donasi'];
          $realphotodonasi = base64_decode($file_photo_donasi);
          
          file_put_contents($nama_photo_donasi, $realphotodonasi);
          $masukkan = array(
            "id_user_donasi" => $id_donasi,
            "judul_donasi" => $judul_donasi,
            //"photo_donasi" => $realphotodonasi,
            "deskripsi_donasi" => $deskripsi_donasi,
            "awal_tanggal_donasi" => $awalTanggal_donasi,
            "akhir_tanggal_donasi" => $akhirTanggal_donasi,
          );
          $insertdonasi=$db->insert("data_donasi",$masukkan);
          if ($insertdonasi) {
              $datas = array(
                  "judul_pesan" => "Tambah donasi",
                  "image_pesan" => "",
                  "deskripsi_pesan" => "Donasi berhasil ditambahkan",
                  "tanggal_pesan" => $now,
                  "user_id_pesan" => $id_donasi,
                  );
              $kirimpesan = $db->insert("pesan", $datas);
              if (count($kirimpesan) > 0) {
                  echo json_encode("tambah_donasi_berhasil");
              }
          } else {
              echo json_encode("tambah_donasi_gagal");
          }
      }
      if ($post == "update_user_donasi") {
          $id_donasi = $_POST['id_donasi'];
          $username_donasi = $_POST["username_user_donasi"];
          $id_user_donasi = $_POST['id_user_donasi'];
          $nama_donasi = $_POST['nama_user_donasi'];
          $judul_donasi = $_POST['judul_user_donasi'];
          $deskripsi_donasi = $_POST['deskripsi_user_donasi'];
          $email_donasi = $_POST['email_user_donasi'];
          
          $cek = $db->fetchwhere("donasi", "id_donasi = '$id_donasi'");
          if ($cek) {
              $where = array("id_donasi" => $id_donasi);
              $update = array(
                    "judul_donasi" => $judul_donasi,
                    "deskripsi_donasi" => $deskripsi_donasi,
                    "id_user_donasi" => $id_user_donasi,
                    "judul_donasi" => $judul_donasi,
                    "deskripsi_donasi" => $deskripsi_donasi,
                    "awal_tanggal_donasi" => $awal_tanggal_donasi,
                    "akhir_tanggal_donasi" => $akhir_tanggal_donasi,
                );
              $update_donasi = $db->update("donasi", $where, $update);
              if ($update_donasi) {
                  echo json_encode("update_donasi_berhasil");
              } else {
                  echo json_encode("update_donasi_gagal");
              }
          }
      }
  
    if ($post == "delete_user_donasi") {
          $id_donasi = $_POST['id_donasi_user'];
          $cek = $db->delete("");
          
    }
    
    if ($post == "laporan_user") {
        $data=[];
        $userid = $_POST['user_id_laporan'];
        $cekuser = $db->fetchwhere("data_user", "id='$userid'");
        if (count($cekuser) > 0) {
            $fetchlaporan = $db->fetchwhere("laporan", "id_laporan_user='$userid'");
            if (count($fetchlaporan) > 0) {
                foreach($fetchlaporan as $lapor) {
                    $idlaporan = $lapor['id_laporan'];
                    $gambarlaporan = $lapor['file'];
                    $judullaporan = $lapor['judul'];
                    $deskripsilaporan = $lapor['description'];
                    $lokasi = $lapor['lokasi'];
                    $tanggallaporan = $lapor['date'];
                    $statuslaporan = $lapor['status_laporan'];
                    $datas = array(
                        "id_laporan" => $idlaporan,
                        "gambar_laporan" => $gambarlaporan,
                        "judul_laporan" => $judullaporan,
                        "deskripsi_laporan" => $deskripsilaporan,
                        "tanggal_laporan" => $tanggallaporan,
                        "lokasi_laporan" => $lokasi,
                        "status_laporan" => $statuslaporan,
                    );
                    array_push($data, $datas);
                }
                echo json_encode($data);
            } else {
                echo json_encode("laporan_tidak_ada");
            }
        } else {
            echo json_encode("user_tidak_ada");
        }
    }
    
    if ($post == "tambah_laporan_user") {
        $userid = $_POST['id_laporan_user'];
        $categories = $_POST['id_kategori_user'];
        $judul_laporan = $_POST['judul_laporan_user'];
        $photo_laporan = $_POST['file'];
        $tempat_laporan = $_POST['tempat_laporan_user'];
        $deskripsi = $_POST['deskripsi_laporan_user'];
        $kategori = $_POST['kategori_laporan_user'];
        
        $datas = array(
            "id_category" => $categories,
            "user_id" => $userid,
            "judul" => $judul_laporan,
            "description" => $deskripsi,
            "lokasi" => $lokasi,
            "status_laporan" => 0,
            
            );
        $tambahlaporan = $db->insert("laporan", $datas);
        if (count($tambahlaporan) > 0) {
            $datapesan = array(
                "judul_pesan" => "Laporan",
                //"image_pesan" => $photo_laporan,
                "deskripsi_pesan" => "Laporan anda berhasil diterima",
                "tanggal_pesan" => $now,
                "user_id_pesan" => $userid,
                );
            $tambahpesan = $db->insert("pesan", $datapesan);
            echo json_encode("tambah_laporan_berhasil");
        } else {
            echo json_encode("tambah_laporan_gagal");
        }
    }
    if ($post == "request_pesan_user") {
        $data=[];
        $userid = $_POST['user_id_pesan'];
        $cekuser = $db->fetchwhere("data_user", "id='$userid'");
        if (count($cekuser) > 0) {
            $fetchpesan = $db->fetchwhere("pesan", "user_id_pesan='$userid'");
            // echo count($fetchpesan);
            if (count($fetchpesan) > 0) {
                foreach($fetchpesan as $pesan) {
                    $idpesan = $pesan['id_pesan'];
                    $judulpesan = $pesan['judul_pesan'];
                    $imagepesan = $pesan['image_pesan'];
                    $deskripsipesan = $pesan['deskripsi_pesan'];
                    $tanggalpesan = $pesan['tanggal_pesan'];
                    $datas = array(
                        "id_message" => $idpesan,
                        "title_message"=> $judulpesan,
                        "image_message"=>$imagepesan,
                        "description_message"=>$deskripsipesan,
                        "date_message" =>$tanggalpesan,
                    );
                    array_push($data, $datas);
                }
                echo json_encode($data);
            } else {
                echo json_encode("pesan_kosong");
                exit();
            }
        } else {
            echo json_encode("user_tidak_ada");
            exit();
        }
    }
    
    if ($post == "hapus_pesan") {
        $idpesan = $_POST['id_pesan'];
        $idUser = $_POST['id_pesan_user'];
        $cekuser = $db->fetchwhere("data_user", "id = '$idUser'");
        if (count($cekuser) > 0) {
            $datas = array(
                "id_pesan" => $idpesan,
            );
            $hapus = $db->delete("pesan", $datas);
            if (count($hapus) > 0) {
                echo json_encode("hapus_pesan_berhasil");
            } else {
                echo json_encode("hapus_pesan_gagal");
            }
        }
    }
    
    if ($post == "daftar_yayasan") {
        // echo $post;
        $nama = $_POST['nama_yayasan'];
        $username = $_POST['username_yayasan'];
        $email = $_POST['email_yayasan'];
        $telepon = $_POST['telepon_yayasan'];
        $alamat = $_POST['alamat_yayasan'];
        $jlhanggota = $_POST['jumlah_anggota_yayasan'];
        $validasiemail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $cekemail = $db->fetchwhere("yayasan", "email ='$email'");
        $cekusername = $db->fetchwhere("yayasan", "username = '$username'");
        $ceknama = $db->fetchwhere("yayasan", "nama = '$nama'");
        
        if ($validasiemail < 0) {
            echo json_encode("email_tidak_valid");
            exit();
        }
        if (count($cekemail) > 0) {
            echo json_encode("email_registered");
            exit();
        }
        if (count($cekusername) > 0) {
            echo json_encode("username_registered");
            exit();
        }
        
        if (count($ceknama) > 0) {
            echo json_encode("name_registered");
            exit();
        }
        
        $datas = array(
            "nama" => $nama,
            "username" => $username,
            "email" => $email,
            "alamat" => $alamat,
            "deskripsi" => $deskripsi,
            "date" => $now,
            
        );
        
        $tambahyayasan = $db->insert("yayasan", $datas);
        if (count($tambahyayasan) > 0) {
            echo json_encode("daftar_yayasan_berhasil");
        } else {
            echo json_encode("daftar_yayasan_gagal");
        }
    }
    
    if ($post == "add_report") {
        $idUser = $_POST['id_user_pengguna'];
        $idKategori = $_POST['id_kategori_pengguna'];
        $judul = $_POST['judul_laporan'];
        $deskripsi = $_POST['deskripsi_laporan'];
        //$kategori = $_POST['kategori_laporan'];
        $tempat = $_POST['tempat_laporan'];
        
        $cekpengguna = $db->fetchwhere("data_user", "id=$idUser");
        $cekkategori = $db->fetchwhere("category", "id=$idKategori");
        //echo count($cekpengguna);
        //echo count($cekkategori);
        if ((count($cekpengguna) > 0) && count($cekkategori) > 0) {
            $datas = array(
                "user_id" => $idUser,
                "judul" => $judul,
                "description" => $deskripsi,
                "lokasi" => $tempat,
                "id_category" => $idKategori,
                "date"=> $now,
                "status_laporan"=> "0"
                );
            $tambahreport = $db->insert("laporan", $datas);
            //echo count($tambahreport);
            if (count($tambahreport) > 0) {
                $datapesan = array(
                    "judul_pesan" => "Laporan",
                    "deskripsi_pesan" => "Laporan anda telah kami terima",
                    "tanggal_pesan" => $now,
                    "user_id_pesan" => $idUser,
                    "image_pesan" => "",
                    );
                $pesan = $db->insert("pesan", $datapesan);
                if (count($pesan) > 0) {
                    echo json_encode("laporan_berhasil");
                    exit();
                } else {
                    echo json_encode("laporan_gagal");
                    exit();
                }
            } else {
                echo json_encode("laporan_gagal");
            }
        }
    }
}
?>
