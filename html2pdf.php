<?php
/* needs https://github.com/mikehaertl/phpwkhtmltopdf */
use mikehaertl\wkhtmlto\Pdf;
require __DIR__ . '/vendor/autoload.php';

$post = json_decode(file_get_contents('php://input'), true);
$urls = array();
foreach($post as $elem) {
  $urls[] = preg_replace("/^(.*)#.*$/", "$1", trim($elem["url"]));
}
$urls = array_unique($urls);

if (!isset($urls) || !count($urls)) {
  exit("no urls supplied");
}

$zip = new ZipArchive();
$filepath = "/tmp/";
$filename = "html2pdf.zip";

if ($zip->open($filepath.$filename, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
  exit("cannot open ".$filepath.$filename);
}

$failed = array();

$i = 0;
foreach($urls as $url) {
  if (preg_match(
      "@\b((ftp|https?)://[-\w]+(\.\w[-\w]*)+|(?:[a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)+(?: com\b|edu\b|biz\b|gov\b|in(?:t|fo)\b|mil\b|net\b|org\b|[a-z][a-z]\b))(\:\d+)?(/[^.!,?;\"'<>()\[\]{}\s\x7F-\xFF]*(?:[.!,?]+[^.!,?;\"'<>()\[\]{}\s\x7F-\xFF]+)*)?@iS",
      $url)
      ) {
    $pdf = new Pdf($url);
    $pdf->setOptions(array("load-error-handling" => "ignore", "no-stop-slow-scripts", "ignoreWarnings" => true));
    $content = file_get_contents($url);
    preg_match_all("#<title>(.*)</title>#ms", $content, $matches);
    $title = (empty($matches[1][0]) ? "document_".$i++.".pdf" : html_entity_decode($matches[1][0]).".pdf");
    $title = preg_replace("/[\s]/", "_", $title);
    $zip->addFromString($title, $pdf->toString());
  } else {
    $failed[] = $url;
  }
}
if (count($failed)) {
  $zip->addFromString("failed.txt", join("\n", $failed));
}
$zip->close();

header("Pragma: public", true);
header("Expires: 0", true);
header('Cache-Control: no-cache', true);
header("Content-Description: File Transfer", true);
header("Content-type: application/zip", true);
header("Content-Disposition: attachment; filename=\"" . basename($filepath.$filename) . "\"", true);
header("Content-Transfer-Encoding: Binary", true);
header("Content-Length: " . filesize($filepath.$filename), true);
readfile($filepath.$filename);
ignore_user_abort(true);
if (connection_aborted()) {
    unlink($filepath.$filename);
}

