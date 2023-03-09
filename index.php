<?php
error_reporting(0);

require 'function.php';



$pattern = $str_arr[0];
$n = $str_arr[1];
$u = $str_arr[2];
$a = $str_arr[3];
$i = $str_arr[4];
$id = $str_arr[1];
$namaFile = $str_arr[1];

if ($pattern == '/start') {
  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => "Selamat datang " . $username . "\n" . "Ketik /help untuk melihat perintah",
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/help') {
  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => "Daftar perintah 
      \n1. Tambah data mahasiswa = /insert,{nama},{umur},{alamat}
      \n2. Ubah data mahasiswa = /update,{nama},{umur},{alamat},{id}
      \n3. Hapus data mahasiswa = /delete,{id}
      \n4. Tampil semua data mahasiswa = /select-all
      \n5. Cari mahasiswa berdasarkan id = /cari,{id}
      \n6. Laporan = /laporan,{nama file tanpa extension}
      \n7. Kalender Akademik Gunadarma = /scrap-baak
      \n8. Jadwal Perkuliahan Gunadarma = /scrap-jadwal,{kelas atau dosen}
    
      \nGunakan perintah tanpa tanda kurung kurawal",
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/insert') {
  if ($jumlah != 4) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => tambah($n, $u, $a),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/update') {
  if ($jumlah != 5) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => ubah($n, $u, $a, $i),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/cari') {
  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => cari($id),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/select-all') {
  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => selectAll(),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/delete') {
  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => hapus($id),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/scrap-baak') {
  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => scrap_baak(),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/scrap-jadwal') {
  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => scrap_jadwal($n),
    'parse_mode' => 'HTML'
  ));
} elseif ($pattern == '/laporan') {
  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $chat_id,
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
  reply(array(
    'chat_id' =>  $chat_id,
    'text' => "Tekan link di bawah\n" . getLink($namaFile),
    'parse_mode' => 'HTML'
  ));
} else {
  reply(array(
    'chat_id' => $update["message"]["chat"]["id"],
    'text' => "Maaf perintah " . $message . "\ntidak ada",
    'parse_mode' => 'HTML'
  ));
}
