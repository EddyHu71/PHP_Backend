<?php
include_once "action.php";
$db=new Crud;
function checkdbbantuan($id,$j){
    $data=[];
    global $db;
    if($id==0){
        if($j==0){
            $datax=$db->fetchwhere("bantuan", "status_bantuan=1");
        }else if($j!=0){
            $datax=$db->fetchwhere("bantuan","kategori_id='$j' and status_bantuan=1");
        }
        }else{
        $datax=$db->fetchwhere("bantuan","id='$id' and status_bantuan=1");
        }
        foreach($datax as $dx){
            $id=$dx['id'];
            $user_id=$dx['user_id'];
            $kategori_id=$dx['kategori_id'];
            $judul=$dx['judul'];
            $deskripsi=$dx['deskripsi'];
            $img=$dx['img'];
            $date=$dx['date'];
            $nama=$dx['namapembuat'];
            $target=$dx['targetbantuan'];
            $waktu=$dx['targetwaktu'];
            $last_date=$dx['last_date'];
            $current=$dx['current'];
            $datas=array(
                "id"=>$id,
                "user_id"=>$user_id,
                "kategori_id"=>$kategori_id,
                "judul"=>$judul,
                "namapembuat"=>$nama,
                "deskripsi"=>$deskripsi,
                "img"=>$img,
                "targetbantuan"=>$target,
                "targetwaktu"=>$waktu,
                "last_date"=>$last_date,
                "date"=>$date,
                "current"=>$current,
                );
            array_push($data,$datas);
        }
        return json_encode($data);
}
function checkdbcategory($id){
    $data=[];
    global $db;
    if($id==0){
        $datax=$db->fetch("category");
    }else{
        $datax=$db->fetchwhere("category","id='$id'");
    }
    foreach($datax as $dx){
        $id=$dx['id'];
        $nama=$dx['nama'];
        $datas=array(
            "id"=>$id,
            "nama"=>$nama,
        );
        array_push($data,$datas);
    }
    return json_encode($data);
}
function checkdbcategorydonasi($id){
    $data=[];
    global $db;
    if($id==0){
        $datax=$db->fetch("kategori_bantuan");
    }else{
        $datax=$db->fetchwhere("kategori_bantuan","id='$id'");
    }
    foreach($datax as $dx){
        $id=$dx['id'];
        $nama=$dx['nama'];
        $datas=array(
            "id"=>$id,
            "nama"=>$nama,
        );
        array_push($data,$datas);
    }
    return json_encode($data);
}
function checkdbcategorylaporan($id){
    $data=[];
    global $db;
    if($id==0){
        $datax=$db->fetch("category");
    }else{
        $datax=$db->fetchwhere("category","id='$id'");
    }
    foreach($datax as $dx){
        $id=$dx['id'];
        $nama=$dx['nama'];
        $datas=array(
            "id"=>$id,
            "nama"=>$nama,
        );
        array_push($data,$datas);
    }
    return json_encode($data);
}
function checkdbbantuans($id,$j){
    $data=[];
    global $db;
    if($id==0){
        if($j==0){
            $datax=$db->fetchwhere("bantuan", "status_bantuan=1 limit 3");
        }else if($j!=0){
            $datax=$db->fetchwhere("bantuan","kategori_id='$j' and status_bantuan=1 limit 3");
        }
        }else{
        $datax=$db->fetchwhere("bantuan","id='$id' and status_bantuan=1 limit 3");
        }
        foreach($datax as $dx){
            $id=$dx['id'];
            $user_id=$dx['user_id'];
            $kategori_id=$dx['kategori_id'];
            $judul=$dx['judul'];
            $deskripsi=$dx['deskripsi'];
            $img=$dx['img'];
            $date=$dx['date'];
            $nama=$dx['namapembuat'];
            $target=$dx['targetbantuan'];
            $waktu=$dx['targetwaktu'];
            $last_date=$dx['last_date'];
            $current=$dx['current'];
            $datas=array(
                "id"=>$id,
                "user_id"=>$user_id,
                "kategori_id"=>$kategori_id,
                "judul"=>$judul,
                "namapembuat"=>$nama,
                "deskripsi"=>$deskripsi,
                "img"=>$img,
                "targetbantuan"=>$target,
                "targetwaktu"=>$waktu,
                "last_date"=>$last_date,
                "date"=>$date,
                "current"=>$current,
                );
            array_push($data,$datas);
        }
        return json_encode($data);
}



//kelvin functions

function getDonasi($kategori_id) {
  $data=[];
  global $db;
  $now=date("Y-m-d H:i:s");
  global $now;

  if ($kategori_id == 0) {
    $cek=$db->fetchwhere("bantuan", "status_bantuan = 1");
  } else {
    $cek=$db->fetchwhere("bantuan", "status_bantuan = 1 AND kategori_id = '$kategori_id'");
  }

  foreach($cek as $ceks){
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
      "donasi_id"=>$ceks['id'],
      "judul"=>$ceks['judul'],
      "current"=>$currentrp,
      "percent"=>$percent,
      "coverimg"=>$coverimg,
      "sisa_hari"=>$dateDiff,
      "nama"=>$starter
    );
    array_push($data, $datas);
  }
  return json_encode ($data);
}

function getKategoriDonasi () {
	$data=[];
  global $db;

	$cek=$db->fetch("kategori_bantuan");

	foreach ($cek as $ceks) {
		$datas=array(
			"id"=>$ceks['id'],
			"nama"=>$ceks['nama']
		);
		array_push($data, $datas);
	}
	return json_encode ($data);
}
?>
