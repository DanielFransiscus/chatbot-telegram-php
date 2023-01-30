<?php

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
var_dump($data);
