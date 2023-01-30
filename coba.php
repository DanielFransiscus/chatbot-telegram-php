<?php
define("TOKEN", "5831470744:AAE5GC1KAxGSp5SjYtXZCvih6L7QxeUWOs0");


$apiURL = "https://api.telegram.org/bot$TOKEN";
$update = json_decode(file_get_contents("php://input"), TRUE);

$chat_id = $update["message"]["chat"]["id"];
$message = $update["message"]["text"]; //tidak perlu di implode
$username = $update["message"]['chat']["username"];
$update["message"]["chat"]["id"];

function http_request($url)
{
  // persiapkan curl
  $ch = curl_init();
  // set url 
  curl_setopt($ch, CURLOPT_URL, $url);
  // set user agent    
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  // return the transfer as a string 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  // $output contains the output string 
  $output = curl_exec($ch);
  // tutup curl 
  curl_close($ch);
  // mengembalikan hasil curl
  return $output;
}



function reply($text, $data)

{
  global $apiURL;
  $queries = http_build_query($data);
  http_request($apiURL . "/sendMessage?$queries");
}

$bot = function ($pattern, $queries) {
  global $update;
  if (false !== strpos($pattern, "/start")) {
    // http_request($apiURL . "/sendMessage?$queries");
    $data = array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Selamat datang" . $update['message']['chat']['username'] . "\n" . "Terdapat 5 fitur yang disajikan bot ini yaitu :\n" . "Fitur\n" . "1. Tambah data mahasiswa\n2. Ubah data mahasiswa \n3. Hapus data mahasiswa\n4. Tampil semua data pelanggan\n5. Cari mahasiswa berdasarkan id \n6. Help\nKetik /help untuk melihat perintah",
      'parse_mode' => 'HTML'
    );
    reply('Selamat pagi', $data);
  }
};

$bot("/start", "daniel");
