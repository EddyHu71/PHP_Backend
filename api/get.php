<?php
include '../data.php';
include '../conn.php';
$now=date("Y-m-d H:i:s");

$data='API belum tersedia';

//GET or FETCH datas
if(isset($_GET['get'])) {
  $get=$_GET['get'];

  //single avatar
  if ($get == 'single_avatar') {
    $avatar_id=$_GET['avatar_id'];
    $cek=$db->fetchwhere("avatar", "id = '$avatar_id");
    foreach ($cek as $ava) {
        $avatar = $ava['img'];
    }
  }
  
  //all avatars
  if ($get == 'avatar_all') {
      $data=[];
      $cek=$db->fetch("avatar");
      foreach ($cek as $ava) {
          $avatar_id = $ava['id'];
          $avatar = $ava['img'];
          
          $datas=array(
             "avatar_id"=>$avatar_id,
             "img"=>$avatar
          );
          array_push($data, $datas);
      }
      echo json_encode($data);
  }

  //single user data / profile
  if ($get == 'user_data') {
    $id=$_GET['id'];
    // $search=$_GET['search'];
    // if ($search == 'username') {
    //     $username=$_GET['username'];
    //     $cek=$db->fetchwhere("data_user", "username = '$username'");
    // } else if ($search == 'email') {
    //     $email=$_GET['email'];
    //     $cek=$db->fetchwhere("data_user", "email = '$email'");
    // }

    // $account=$_GET['account'];
    // if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {
    //     $cek = $db->fetchwhere("data_user", "username = '$account'");
    //     $itung=count($cek);

    //     if ($itung<1) {
    //       echo json_encode("invalid_username");
    //       exit();
    //     }
    // } else {
    //     $cek = $db->fetchwhere("data_user", "email = '$account'");
    //     $itung=count($cek);

    //     if($itung<1){
    //       echo json_encode("invalid_email");
    //       exit();
    //     }
    // }

    $data=[];
    $cek=$db->fetchwhere("data_user", "id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("doesnt_exist");
    }else{
      foreach($cek as $ceks){
        $userid=$ceks['id'];
        $email=$ceks['email'];
        $password=$ceks['password'];
        $nama=$ceks['nama'];
        $username=$ceks['username'];
        $nohp=$ceks['phone'];
        $ava=$ceks['avatar'];
        $cav=$db->fetchwhere("avatar", "id = '$ava");
        foreach ($cav as $av) {
            $avatar = $av['img'];
        }
        // $jeniskelamin=$ceks['jenis_kelamin'];
        // $photo=$ceks['photo'];
        // $avatar=$ceks['avatar'];
        // $address=$ceks['address'];
        $join_date=$ceks['join_date'];

        $datas=array(
          "user_id"=>$userid,
          "email"=>$email,
          "password"=>$password,
          "nama"=>$nama,
          "username"=>$username,
          "nohp"=>$nohp,
          "avatar"=>$avatar,
        //   "avatar"=>$avatar,
        //   "jeniskelamin"=>$jeniskelamin,
        //   "photo"=>$photo,
        //   "alamat"=>$address,
          "join_date"=>$join_date
        );
      }
      echo json_encode($datas);
    }
  }

  //get all messages in inbox (reply dari admin)
  if ($get == 'message_all') {
    $id=$_GET['id'];

    $data=[];
    $cek=$db->fetchwhere("replyemail", "user_id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("empty");
    }else{
      foreach($cek as $ceks){
        $reply_id=$ceks['id'];
        $msg_id=$ceks['message_id'];
        $cekmsg=$db->fetchwhere("email", "id = '$msg_id'");
        foreach ($cekmsg as $cmsg) {
          $msg_title=$cmsg['subject'];
          $msg_user=$cmsg['message'];
          $msg_date=$cmsg['date'];
        }
        // $uid=$ceks['user_id'];
        $aid=$ceks['admin_id'];
        $admin=$db->fetchwhere("admin", "id = '$aid'");
        foreach ($admin as $adm) {
          $admin_name=$adm['nama'];
        }
        $msg_admin=$ceks['message'];
        $date=$ceks['date'];

        $datas=array(
          "reply_id"=>$reply_id,
          "message_id"=>$msg_id,
          // "user_id"=>$uid,
          "judul"=>$msg_title,
          "pesan_user"=>$msg_user,
          "pesan_date"=>$msg_date,
          "admin_name"=>$admin_name,
          "balasan"=>$msg_admin,
          "reply_date"=>$date
        );
        array_push($data, $datas);
      }
      echo json_encode($data);
    }
  }

  //single message detail
  if ($get == 'message_data') {
    //ini ID dari reply_id di message-all
    $id=$_GET['id'];

    $data=[];
    $cek=$db->fetchwhere("replyemail", "id = '$id'");
    $itung=count($cek);

    if($itung<1){
      echo json_encode("doesnt_exist");
    }else{
      foreach($cek as $ceks){
        $msg_id=$ceks['message_id'];
        $cekmsg=$db->fetchwhere("email", "id = '$msg_id'");
        foreach ($cekmsg as $cmsg) {
          $msg_title=$cmsg['subject'];
          $msg_user=$cmsg['message'];
          $msg_date=$cmsg['date'];
        }
        // $uid=$ceks['user_id'];
        $aid=$ceks['admin_id'];
        $admin=$db->fetchwhere("admin", "id = '$aid'");
        foreach ($admin as $adm) {
          $admin_name=$adm['nama'];
        }
        $msg_admin=$ceks['message'];
        $date=$ceks['date'];

        $datas=array(
          "message_id"=>$msg_id,
          // "user_id"=>$uid,
          "judul"=>$msg_title,
          "pesan_user"=>$msg_user,
          "pesan_date"=>$msg_date,
          "admin_name"=>$admin_name,
          "balasan"=>$msg_admin,
          "reply_date"=>$date
        );
      }
      echo json_encode($datas);
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
        $berita_id=$ceks['id'];
        $judul=$ceks['judul'];
        $description=$ceks['description'];
        $lokasi=$ceks['lokasi'];
        $file=$ceks['file'];
        $date=$ceks['date'];

        $datas=array(
          "berita_id"=>$berita_id,
          "judul"=>$judul,
          "deskripsi"=>$description,
          "penulis"=>$admin,
          "lokasi"=>$lokasi,
          "cover_photo"=>$file,
          "tgl_publish"=>$date
        );
      }
      echo json_encode($datas);
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
      $berita_id=$ceks['id'];
      $judul=$ceks['judul'];
      $description=$ceks['description'];
      $lokasi=$ceks['lokasi'];
      $file=$ceks['file'];
      $date=$ceks['date'];

      $datas=array(
        "berita_id"=>$berita_id,
        "judul"=>$judul,
        "deskripsi"=>$description,
        "penulis"=>$admin,
        "lokasi"=>$lokasi,
        "cover_photo"=>$file,
        "tgl_publish"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
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
      echo json_encode($datas);
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
    echo json_encode($data);
  }

  //all bantuan kategori
  if ($get == 'bantuan_kategori') {
    $data=[];
    $cek=$db->fetch("kategori_bantuan");

  	foreach ($cek as $ceks) {
  		$datas=array(
  			"id"=>$ceks['id'],
  			"nama"=>$ceks['nama'],
            "icon"=>$ceks['icon']
  		);
  		array_push($data, $datas);
  	}
  	echo json_encode($data);
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
        "current"=>$currentrp,
        "percent"=>$percent,
        "coverimg"=>$coverimg,
        "submission_date"=>$submit,
        "sisa_hari"=>$dateDiff,
        "nama"=>$starter

      );
      array_push($data, $datas);
    }
    echo json_encode($data);
  }

  //all personal donasi history
  if ($get == 'personal_donasi_history') {
    $uid=$_GET['id'];

    $data=[];
    $status_msg='';
    $cek = $db->fetchwhere("donasi", "user_id = '$uid'");
    foreach ($cek as $ceks) {
      $bantuan_id=$ceks['bantuan_id'];
      $bantuan = $db->fetchwhere("bantuan", "bantuan_id = '$bantuan_id'");
      foreach ($bantuan as $bantuans) {
          $judul=$bantuans['judul'];
      }
      $jumlah=$ceks['jumlah'];
      $status=$ceks['status'];
      if ($status == 0) {
          $status_msg = "Menunggu Foto";
      } else if ($status == 1) {
          $status_msg = "Menunggu Konfirmasi Penerimaan";
      } else {
          $status_msg = "Diterima";
      }
      $date=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        // "bantuan_id"=>$bantuan_id,
        "judul"=>$judul,
        "jumlah"=>$jumlah,
        "status"=>$status,
        "status_msg"=>$status_msg,
        "tgl_donasi"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
  }

  //all donasi history of specific bantuan/campaign
  if ($get == 'all_donasi_history') {
    $id=$_GET['id'];

    $data=[];
    $cek = $db->fetchwhere("donasi", "bantuan_id = '$id' AND status = 2");
    foreach ($cek as $ceks) {
    //   $bantuan_id=$ceks['bantuan_id'];
      $jumlah=$ceks['jumlah'];
      $atas_nama=$ceks['atas_nama'];
      $catatan=$ceks['catatan'];
    //   $status=$ceks['status'];
      $date=date("d m Y",strtotime($ceks['date']));

      $datas=array(
        "jumlah"=>$jumlah,
        "atas_nama"=>$atas_nama,
        "catatan"=>$catatan,
        // "status"=>$status,
        "tgl_donasi"=>$date
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
  }

  //donasi method / donasi procedure with ID as identifier
  if ($get == 'donasi_procedure') {
    // $code=$_GET['code'];
    $id=$_GET['id'];
    $cek=$db->fetchwhere("donasi", "id = '$id'");
	foreach ($cek as $ceks) {
		$method = $ceks['metode'];
// 		$tf = "Rp ".number_format($ceks['jumlah_transfer'],0,'','.');
        $jumlah = $ceks['jumlah'];
        $kode_unik = $ceks['kode_unik'];
        $tf = $jumlah + $kode_unik;

		$datas=array(
		    "method"=>$method,
		    "jumlah"=>$jumlah,
		    "kode_unik"=>$kode_unik,
		    "transfer_amount"=>$tf
		);
	}
	echo json_encode($datas);
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
      echo json_encode($datas);
    }
  }

  //all laporan kategori
  if ($get == 'laporan_kategori') {
    $data=[];
    $cek=$db->fetch("category");

    foreach ($cek as $ceks) {
      $datas=array(
        "id"=>$ceks['id'],
        "nama"=>$ceks['nama'],
        "icon"=>$ceks['icon']
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
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
    echo json_encode($data);
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
    echo json_encode($data);
  }

    if ($get == "jlh_anggota") {
        $data = [];
        $cek = $db->fetch("kategori_anggota");
        
        foreach($cek as $ceks) {
            $datas=array(
                "id"=>$ceks['id_kategori_anggota'],
                "range_anggota"=>$ceks['range_anggota'],
            );
            array_push($data, $datas);
        }
        echo json_encode($data);
    }

  //all yayasan kategori
  if ($get == 'yayasan_kategori') {
    $data=[];
    $cek=$db->fetch("kategori_yayasan");

    foreach ($cek as $ceks) {
      $datas=array(
        "id"=>$ceks['id'],
        "nama"=>$ceks['nama_kategori'],
        "alamat"=>$ceks['alamat_kategori']
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
  }

  //all bantuan kategori
  if ($get == 'sliders') {
    $data=[];
    $cek=$db->fetch("slider");

    foreach ($cek as $ceks) {
      $datas=array(
        "id"=>$ceks['id'],
        "nama"=>$ceks['nama'],
        "img"=>$ceks['img']
      );
      array_push($data, $datas);
    }
    echo json_encode($data);
  }

}
?>
