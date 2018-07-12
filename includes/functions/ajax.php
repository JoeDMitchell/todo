<?php
// Our include
include 'functions.php';

if( isset($_POST['form']) ) {
    $formData = $_POST['form'];
} else {
    $formData = 0;
}

// SAVE FILE
$file = '../../tasks.json';

// CHECK FILE HAS BEEN CALLED CORRECTLY
if ($formData !== 0){

	// MAKE POSTED FORM AN ARRAY
	$postedForm = array();
	parse_str($formData, $postedForm);

	// GET POSTED VALUES
	if (isset($postedForm['todo'])){
		$done = $postedForm['todo'];
	} else {
		$done = 0;
	}

	if (isset($postedForm['time'])){
		$time = $postedForm['time'];
	} else {
		$time = 0;
	}

	if (isset($postedForm['task'])){
		$new = $postedForm['task'];
	} else {
		$new = 0;
	}

	// SAVE THE DATA AND OUTPUT IT
	$data = saveData($done,$time,$new,$file);

} else {

	// NOTHING MAY HAVE BEEN POSTED IF RAN FOR THE FIRST TIME
	$data = file_get_contents($file);
	$data = get_object_vars(json_decode($data));

}

// ECHO OUT THE CONTENT FUNCTION
echo getTheContent($data);


?>