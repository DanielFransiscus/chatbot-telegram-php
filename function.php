<?php
define("TOKEN", "");

require_once('tcpdf/tcpdf.php');
$apiURL = "https://api.telegram.org/bot" . TOKEN;
$update = json_decode(file_get_contents("php://input"), TRUE);
$chat_id = $update["message"]["chat"]["id"];
$message = $update["message"]["text"]; //tidak perlu di implode
$username = $update["message"]['chat']["username"];
$update["message"]["chat"]["id"];

$str_arr = explode(",", $message);

$jumlah = count($str_arr);

$conn = mysqli_connect("localhost", "root", "", "sample");

function getLink($nama)
{
  global $conn;
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  // Set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Author');
  $pdf->SetTitle('PDF Title');
  // Add a page
  $pdf->AddPage();
  $html = '';
  // Define the HTML for the table
  $html .= '
<table border="1" cellspacing="0" cellpadding="5">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Umur</th>
      <th>Alamat</th>
    </tr>
  </thead>';
  $sql = "SELECT * FROM mahasiswa";
  $result = mysqli_query($conn, $sql);
  $html .= '<tbody>';
  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
      $html .= '<tr>';
      $html .= "<td>" . $row['id'] . "</td>";
      $html .= "<td>" . $row['nama'] . "</td>";
      $html .= "<td>" . $row['umur'] . "</td>";
      $html .= "<td>" . $row['alamat'] . "</td>";
      $html .= '</tr>';
    }
  } else {
    $html = "0 results";
  }
  $html .= '</tbody>';
  $html .= '</table>';
  $pdf->writeHTML($html, true, false, false, false, '');
  $pdf->Output(__DIR__  . '/upload/' . $nama . '.pdf', 'F');
  $link  = "https://7106-180-244-192-216.ap.ngrok.io/telebot/upload/" . $nama . ".pdf";
  return $link;
}




function scrapBAAK()
{
  $html = file_get_contents('https://baak.gunadarma.ac.id/');
  preg_match_all('/<table[^>]*class="table table-custom table-primary bordered-table table-striped table-fixed stacktable small-only"[^>]*>(.*?)<\/table>/is', $html, $matches);
  $table = $matches[1][0];
  preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $table, $matches);
  $rows = $matches[1];
  $data = [];
  foreach ($rows as $row) {
    preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $row, $cols);
    $rowData = $cols[1];
    $data[] = $rowData;
  }
  $m = "";
  foreach ($data as $d) {
    $m .=  "<b>" . $d['0'] . "</b>" . PHP_EOL;
    $m .= $d['1'];
    $m .=  $d['2'];
    $m .= $d['3'];
    $m .=  $d['4'];
    $m .= $d['5'];
    $m .= $d['6'];
    $m .= $d['7'];
    $m .= $d['8'];
    $m .= $d['9'];
    $m .= $d['10'];
    $m .= $d['11'];
    $m .= $d['12'];

    $m .= "\n";
  }
  $m .= "Informasi lebih lanjut kunjungi situs https://baak.gunadarma.ac.id/";

  return $m;
}
function selectAll()
{
  global $conn;
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
  } else {
    $m = "0 results";
  }
  return $m;
}

function hapus($id)
{
  global $conn;
  $sql = "DELETE FROM mahasiswa WHERE id=$id";
  if (mysqli_query($conn, $sql)) {
    $m = "Data berhasil dihapus";
  } else {
    $m = "Data gagal dihapus";
  }
  return $m;
}

function cari($id)
{
  global $conn;
  $m = "";
  $sql = "SELECT * FROM mahasiswa WHERE id = $id ";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    $m .= "id: " . $row['id'] . PHP_EOL;
    $m .= "nama: " . $row['nama'] . PHP_EOL;
    $m .= "umur: " . $row['umur'] . PHP_EOL;
    $m .= "alamat: " . $row['alamat'] . PHP_EOL;
    $m .= "\n";
  } else {
    $m = "0 result";
  }
  return $m;
}


function tambah($nama, $umur, $alamat)
{
  global $conn;

  $query = "INSERT INTO mahasiswa
				VALUES
			  ('', '$nama', '$umur', '$alamat')
			";
  if (mysqli_query($conn, $query)) {
    $m = "Data berhasil disimpan";
  } else {
    $m = "Data gagal disimpan";
  }
  return $m;
}

function ubah($nama, $umur, $alamat, $id)
{
  global $conn;


  $query = "UPDATE mahasiswa SET
				nama = '$nama',
				umur = '$umur',
				alamat = '$alamat'
			  WHERE id = $id
			";

  if (mysqli_query($conn, $query)) {
    $m = "Data berhasil diubah";
  } else {
    $m = "Data gagal diubah";
  }
  return $m;
}



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


function reply($data)

{
  global $apiURL;
  $queries = http_build_query($data);
  http_request($apiURL . "/sendMessage?$queries");
}

// http_request($apiURL . "/sendMessage?$queries");

$bot1 = function () {
  global $pattern, $message, $jumlah, $update;
  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah " . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/start")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => "Selamat datang " . $update["message"]['chat']["username"] . "\n" . "Ketik /help untuk melihat perintah",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};

$bot2 = function () {
  global $pattern, $message, $jumlah, $update;

  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/help")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => "Daftar perintah 
      \n1. Tambah data mahasiswa = /insert,{nama},{umur},{alamat}
      \n2. Ubah data mahasiswa = /update,{nama},{umur},{alamat},{id}
      \n3. Hapus data mahasiswa = /delete,{id}
      \n4. Tampil semua data pelanggan = /select-all
      \n5. Cari mahasiswa berdasarkan id = /select,{id}
      \nGunakan perintah tanpa tanda kurung kurawal",
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};


$bot3 = function () {
  global $pattern, $message, $jumlah, $update, $n, $u, $a;

  if ($jumlah != 4) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/insert")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => tambah($n, $u, $a),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};

$bot4 = function () {
  global $pattern, $message, $jumlah, $update, $n, $u, $a, $i;

  if ($jumlah != 5) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/update")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => ubah($n, $u, $a, $i),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};


$bot5 = function () {
  global $pattern, $message, $jumlah, $update, $id;

  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/cari")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => cari($id),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};

$bot6 = function () {
  global $pattern, $message, $jumlah, $update;

  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/select-all")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => selectAll(),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};

$bot7 = function () {
  global $pattern, $message, $jumlah, $update, $id;

  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/delete")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => hapus($id),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};

$bot8 = function () {
  global $pattern, $message, $jumlah, $update, $id;

  if ($jumlah != 1) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/scrap-baak")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => scrapBAAK(),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};


$bot9 = function () {
  global $pattern, $message, $jumlah, $update, $namaFile;

  if ($jumlah != 2) {
    reply(array(
      'chat_id' => $update["message"]["chat"]["id"],
      'text' => "Maaf perintah" . $message . "\ntidak ada",
      'parse_mode' => 'HTML'
    ));
    exit();
  }

  if (false !== strpos($pattern, "/laporan")) {
    reply(array(
      'chat_id' =>  $update["message"]["chat"]["id"],
      'text' => "Tekan link di bawah\n" . getLink($namaFile),
      'parse_mode' => 'HTML'
    ));
    exit();
  }
};
