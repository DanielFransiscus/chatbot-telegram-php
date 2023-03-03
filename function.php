<?php
define("TOKEN", "5831470744:AAE5GC1KAxGSp5SjYtXZCvih6L7QxeUWOs0");

require_once('tcpdf/tcpdf.php');
$apiURL = "https://api.telegram.org/bot" . TOKEN;

$conn = mysqli_connect("localhost", "root", "", "sample");
$update = json_decode(file_get_contents("php://input"), TRUE);

error_reporting(0);

$chat_id = $update["message"]["chat"]["id"];

$username = $update["message"]['chat']["username"];
$message = $update["message"]["text"];
$str_arr = explode(",", $message);
$jumlah = count($str_arr);







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
  $link  = "https://167c-36-70-176-201.ap.ngrok.io/telebot/upload/" . $nama . ".pdf";
  //url saat ini
  return $link;
}




function scrap_baak()
{
  $html = file_get_contents('https://baak.gunadarma.ac.id/');
  preg_match_all('/<table[^>]*class="table table-custom table-primary bordered-table table-striped table-fixed stacktable small-only"[^>]*>(.*?)<\/table>/is', $html, $matches);
  $table = $matches[0][0];
  preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $table, $matches);
  $rows = $matches[0];
  $data = [];
  foreach ($rows as $row) {
    preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $row, $cols);
    $rowData = $cols[1];
    $data[] = $rowData;
  }
  $txt = "";

  $txt .= "Kalender Akademik";
  foreach ($data as $subArray) {
    foreach ($subArray as $value) {
      $txt .= $value . "" . PHP_EOL;
    }
    $txt .= "\n";
  }

  if (empty($txt)) {
    $txt .= "Tidak ditemukan";
  }
  $txt .= "Informasi lebih lanjut kunjungi situs https://baak.gunadarma.ac.id/";
  return $txt;
}

function scrap_jadwal($param)
{

  $newparam = urlencode($param);

  $url = 'https://baak.gunadarma.ac.id/jadwal/cariJadKul?teks=' . $newparam;

  $html = file_get_contents($url);
  preg_match_all('/<table[^>]*class="table table-custom table-primary table-fixed bordered-table stacktable large-only"[^>]*>(.*?)<\/table>/is', $html, $matches);
  $table = $matches[0][0];
  preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $table, $matches);
  $rows = $matches[0];
  $data = [];
  foreach ($rows as $row) {
    preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $row, $cols);
    $rowData = $cols[1];
    $data[] = $rowData;
  }
  $txt = "";
  foreach ($data as $subArray) {
    foreach ($subArray as $value) {
      $txt .= $value . "" . PHP_EOL;
    }
    $txt .= "\n";
  }

  if (empty($txt)) {
    $txt .= "Tidak ada dalam database untuk kategori kelas / dosen";
  }
  $txt .= "Informasi lebih lanjut kunjungi situs https://baak.gunadarma.ac.id/";
  return $txt;
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
