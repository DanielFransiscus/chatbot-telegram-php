<?php

require 'function.php';




function run()
{

  $TOKEN = "5831470744:AAE5GC1KAxGSp5SjYtXZCvih6L7QxeUWOs0";
  $apiURL = "https://api.telegram.org/bot$TOKEN";
  $update = json_decode(file_get_contents("php://input"), TRUE);


  $message = $update["message"]["text"]; //tidak perlu di implode

  $username = $update["message"]['chat']["username"];

  $data1 = array(
    'chat_id' => $update["message"]["chat"]["id"],
    'text' => "Selamat datang @$username\n" . "Terdapat 5 fitur yang disajikan bot ini yaitu :\n" . "Fitur\n" . "1. Tambah data mahasiswa\n2. Ubah data mahasiswa \n3. Hapus data mahasiswa\n4. Tampil semua data pelanggan\n5. Cari mahasiswa berdasarkan id \n6. Help\nKetik /help untuk melihat perintah",
    'parse_mode' => 'HTML'
  );
  $data2 = array(
    'chat_id' => $update["message"]["chat"]["id"],
    'text' => "Daftar perintah 
    \n1. Tambah data mahasiswa = /insert,{nama},{umur},{alamat}
    \n2. Ubah data mahasiswa = /update,{id},{nama},{umur},{alamat}
    \n3. Hapus data mahasiswa = /delete,{id}
    \n4. Tampil semua data pelanggan = /select-all
    \n5. Cari mahasiswa berdasarkan id = /select,{id}",
    'parse_mode' => 'HTML'
  );




  $conn = mysqli_connect("localhost", "root", "", "sample");
  // Check connection


  $queries1 = http_build_query($data1);
  $queries2 = http_build_query($data2);


  $str_arr = explode(",", $message);


  $jumlah = count($str_arr);

  if (false !== strpos($message, "/start")) {

    // file_get_contents($apiURL . "/sendMessage?$queries1");
    http_request($apiURL . "/sendMessage?$queries1");
  }

  if (false !== strpos($message, "/help")) {

    // file_get_contents($apiURL . "/sendMessage?$queries2");
    http_request($apiURL . "/sendMessage?$queries2");
  }


  $nama = $str_arr[1];
  $umur = $str_arr[2];
  $alamat = $str_arr[3];


  if (false !== strpos($message, '/insert') && $jumlah == 4) {



    $sql = "INSERT INTO mahasiswa (id, nama, umur, alamat)
    VALUES ('', '$nama','$umur', '$alamat')";

    if (mysqli_query($conn, $sql)) {
      $data3 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' => "Data berhasil disimpan",
        'parse_mode' => 'HTML'
      );
      $queries3 = http_build_query($data3);
      http_request($apiURL . "/sendMessage?$queries3");
      mysqli_close($conn);
    } else {
      $data3 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' =>  "Data gagal disimpan. Terjadi Kesalahan pada SQL",
        'parse_mode' => 'HTML'
      );
      $queries3 = http_build_query($data3);
      http_request($apiURL . "/sendMessage?$queries3");
    }
  }
  $id = $str_arr[1];
  if (false !== strpos($message, '/delete') && $jumlah == 2) {

    $sql = "DELETE FROM mahasiswa WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' => "Data berhasil dihapus",
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
      mysqli_close($conn);
    } else {
      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' =>  "Data gagal dihapus. Terjadi Kesalahan pada SQL",
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
    }
  }


  $ids = $str_arr[1];
  $namas = $str_arr[2];
  $umurs = $str_arr[3];
  $alamats = $str_arr[4];
  if (false !== strpos($message, '/update') && $jumlah == 5) {

    $sql5 = "UPDATE mahasiswa SET nama='$namas',umur = '$umurs',alamat = '$alamats' WHERE id=$ids";
    if (mysqli_query($conn, $sql5)) {
      $data5 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' => "Data berhasil ubah",
        'parse_mode' => 'HTML'
      );
      $queries5 = http_build_query($data5);
      http_request($apiURL . "/sendMessage?$queries5");
      mysqli_close($conn);
    } else {
      $data5 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' =>  "Data gagal diubah. Terjadi Kesalahan pada SQL",
        'parse_mode' => 'HTML'
      );
      $queries5 = http_build_query($data5);
      http_request($apiURL . "/sendMessage?$queries5");
    }
  }


  if (false !== strpos($message, '/select-all')) {
    $sql = "SELECT * FROM mahasiswa";
    $result = mysqli_query($conn, $sql);
    $m = "";
    if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while ($row = mysqli_fetch_assoc($result)) {
        $m .= "id: " . $row['id'] . PHP_EOL;
        $m .= "nama: " . $row['nama'] . PHP_EOL;
        $m .= "umur: " . $row['umur'] . PHP_EOL;
        $m .= "alamat: " . $row['alamat'] . PHP_EOL;
        $m .= "\n";
      }
      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' =>  $m,
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
    } else {

      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' => '0 result',
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
    }
  }




  if (false !== strpos($message, '/select') && $jumlah == 2) {
    $sql = "SELECT * FROM mahasiswa where id=$id";
    $result = mysqli_query($conn, $sql);
    $m = "";
    if (mysqli_num_rows($result) == 1) {
      // output data of each row
      $row = mysqli_fetch_assoc($result);
      $m .= "id: " . $row['id'] . PHP_EOL;
      $m .= "nama: " . $row['nama'] . PHP_EOL;
      $m .= "umur: " . $row['umur'] . PHP_EOL;
      $m .= "alamat: " . $row['alamat'] . PHP_EOL;
      $m .= "\n";

      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' =>  $m,
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
    } else {

      $data4 = array(
        'chat_id' => $update["message"]["chat"]["id"],
        'text' => '0 result',
        'parse_mode' => 'HTML'
      );
      $queries4 = http_build_query($data4);
      http_request($apiURL . "/sendMessage?$queries4");
    }
  }
}

run();
