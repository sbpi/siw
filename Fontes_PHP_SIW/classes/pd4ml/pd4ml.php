<?php

if ($_SERVER["HTTPS"] != "on") {
	// MS IE needs to cache PDF obtained by HTTPS.
	header('Pragma: no-cache');
	header('Expires: -10000');
}

if (array_key_exists('url', $_POST)) {

	header('Content-type: application/pdf');
	header('Content-disposition: inline');
	//header('Content-disposition: attachment; filename=arquivo.pdf');

	// UNIX version
	passthru('java -Xmx512m -Djava.awt.headless=true -cp .:pd4ml_demo.jar Pd4Php \'' . $_POST['url'] . '\' 800 A4 2>&1');
	// Windows version
	//passthru('java -Xmx512m -cp .;pd4ml_demo.jar Pd4Php ' . $_POST['url'] . ' 800 A4');
    unlink($_POST['filename']);
    
    
    
    

} else {
	echo 'invalid usage';
}
?>
