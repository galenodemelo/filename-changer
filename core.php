<?php
define('ZIP_PATH', __DIR__ );

$danfeCodes = explode(",", filter_input(INPUT_POST, "danfe-code"));
$customerNames = explode(",", filter_input(INPUT_POST, "customer-name"));

$files = $_FILES["file-input"];
$countFiles = count($files["name"]);

$zipName = "NFE_Renomeadas_(" . date("d-m-Y H-i-s") . ").zip";
$zipFile = ZIP_PATH . "/" . $zipName;
$zip = new ZipArchive();
if (!$zip->open($zipFile, ZIPARCHIVE::CREATE)) exit("Não foi possível criar o arquivo ZIP");

// Rename all the files
for ($i = 0; $i < $countFiles; $i++) {
	$newName = "NFE_{$danfeCodes[$i]}_{$customerNames[$i]}.pdf";
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