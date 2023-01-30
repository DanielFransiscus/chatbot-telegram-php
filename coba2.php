<?php

// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

require_once('function.php');

// Create a new instance of TCPDF
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

// Write the HTML to the PDF
$pdf->writeHTML($html, true, false, false, false, '');


// Add some text


// Save the PDF to a file

$pdf->Output(__DIR__  . '/upload/dataku888.pdf', 'F');
