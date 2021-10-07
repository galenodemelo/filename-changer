<?php
define('ZIP_PATH', __DIR__ );

$extraData = json_decode(filter_input(INPUT_POST, "extra-data"));

$files = $_FILES["file-input"];
$countFiles = count($files["name"]);

$zipName = "NFE_Renomeadas_(" . date("d-m-Y H-i-s") . ").zip";
$zipFile = ZIP_PATH . "/" . $zipName;
$zip = new ZipArchive();
if (!$zip->open($zipFile, ZIPARCHIVE::CREATE)) exit("Não foi possível criar o arquivo ZIP");

// Rename all the files
for ($i = 0; $i < $countFiles; $i++) {
    $filename = $files["name"][$i];
    $key = array_search($filename, array_column($extraData, "filename"));
    $data = $extraData[$key];

	$newName = "NFE_{$data['danfeCode']}_{$data['customerName']}.pdf";
    $zip->addFile($files["tmp_name"][$i], $newName);
}

$zip->close();

$fileLength = filesize($zipFile);

header("Content-type: application/zip");
header("Content-disposition: filename={$zipName}");
header("Content-length: {$fileLength}");
readfile($zipFile);
unlink($zipFile);
exit;