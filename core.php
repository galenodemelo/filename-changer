<?php
define('PDF_PATH', __DIR__ . "/pdf");
define('ZIP_PATH', __DIR__ . "/zip");

$danfeCodes = explode(",", filter_input(INPUT_POST, "danfe-code"));
$customerNames = explode(",", filter_input(INPUT_POST, "customer-name"));

$files = $_FILES["file-input"];
$countFiles = count($files["name"]);

// Rename all the files
for ($i = 0; $i < $countFiles; $i++) {
	$files["name"][$i] = "NFE_{$danfeCodes[$i]}_{$customerNames[$i]}.pdf";
}

// Create a ZIP file if there"s more than 1 file
if ($countFiles > 1) {
	$zipName = "NFE_Renomeadas_(" . date("d-m-Y H-i-s") . ").zip";
	$zipFile = ZIP_PATH . "/" . $zipName;
	$zip = new ZipArchive;
	$zip->open($zipFile, ZIPARCHIVE::CREATE);

	foreach ($files["name"] as $file) {
		$pdfFile = PDF_PATH . "/" . $file;
		$zip->addFile($pdfFile, $file);
	}
	$zip->close();

	$fileLength = filesize($zipFile);

	header("Content-type: application/zip");
	header("Content-disposition: filename={$zipName}");
	header("Content-length: {$fileLength}");
	readfile($zipFile);

	exit;
} else {
	header("Content-type: application/pdf");
	header("Content-length: " . filesize($files['tmp_name'][0]));
	header("Content-disposition: attachment; filename={$files['name'][0]}");
	@readfile($files['tmp_name'][0]);

	unlink(PDF_PATH . "/" . $files['name'][0]);
	exit;
}
