<?php
// barcode.php

// Include the Barcode class
require_once 'Barcode.php';

// Check if it's an AJAX request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['text'])) {
    // Get the text from the POST data
    $text = trim($_POST['text']);
    $filename = trim($_POST['filename']);
    
    // Create an instance of the Barcode class
    $barcode = new Barcode();

    // Generate the barcode
    ob_start(); // Start output buffering to capture image data
    $barcodeUrl = $barcode->generate($text, $filename); // Generate the barcode
    //$barcodeUrl = $barcodeImageData = ob_get_clean(); // Get the generated barcode image data

    // // Encode the image data to base64
    // $barcodeImageDataBase64 = base64_encode($barcodeImageData);

    // // Return the base64 encoded image data as response
    // echo $barcodeImageDataBase64;

    // $barcodeGenerator = new Barcode();
	// 	$barcodeUrl = $barcodeGenerator->generate();
		
		echo $barcodeUrl;
} else {
    // Handle the case if it's not an AJAX request or the text is not provided
    echo "Error: Text not provided or invalid request.";
}
?>