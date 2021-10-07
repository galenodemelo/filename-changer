<?php
if (!empty($_FILES) && !empty($_POST['extra-data'])) {
	require './core.php';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8" />
	<title>Renomeador de DANFEs</title>

	<link rel="shortcut icon" href="https://img.icons8.com/cute-clipart/2x/edit.png" />
	<link rel="stylesheet" href="style.css" />
</head>

<body>
	<form action="" method="POST" enctype="multipart/form-data" target="_blank">
		<h1>
			Solte os arquivos abaixo para renomeá-los
		</h1>

		<div id="drop-container">
			Solte aqui
		</div>
		<input type="file" id="file-input" name="file-input[]" multiple />

		<ul id="files-list"></ul>

		<button type="submit" id="bto-submit">Renomear arquivos</button>
		<input type="hidden" id="extra-data" name="extra-data" />
	</form>
	<script src="js/build/pdf.js"></script>
	<script src="scripts.js"></script>
</body>

</html>