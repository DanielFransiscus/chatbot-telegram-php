<?php

require 'function.php';
$pattern = $str_arr[0];
$n = $str_arr[1];
$u = $str_arr[2];
$a = $str_arr[3];
$i = $str_arr[4];
$id = $str_arr[1];
$namaFile = $str_arr[1];





if ($pattern == '/start') {
  $bot1();
} elseif ($pattern == '/help') {
  $bot2();
} elseif ($pattern == '/insert') {
  $bot3();
} elseif ($pattern == '/update') {
  $bot4();
} elseif ($pattern == '/cari') {
  $bot5();
} elseif ($pattern == '/select-all') {
  $bot6();
} elseif ($pattern == '/delete') {
  $bot7();
} elseif ($pattern == '/scrap-baak') {
  $bot8();
} elseif ($pattern == '/laporan') {
  $bot9();
} else {
  reply(array(
    'chat_id' => $update["message"]["chat"]["id"],
    'text' => "Maaf perintah " . $message . "\ntidak ada",
    'parse_mode' => 'HTML'
  ));
}









// $bot1("/help", array(
//   'chat_id' => $update["message"]["chat"]["id"],
//   'text' => "Daftar perintah 
//     \n1. Tambah data mahasiswa = /insert,{nama},{umur},{alamat}
//     \n2. Ubah data mahasiswa = /update,{id},{nama},{umur},{alamat}
//     \n3. Hapus data mahasiswa = /delete,{id}
//     \n4. Tampil semua data pelanggan = /select-all
//     \n5. Cari mahasiswa berdasarkan id = /select,{id}",
//   'parse_mode' => 'HTML'
// ), 1);


// $bot1("/insert", array(
//   'chat_id' =>  $update["message"]["chat"]["id"],
//   'text' => tambah($n, $u, $a),
//   'parse_mode' => 'HTML'
// ), 4);
