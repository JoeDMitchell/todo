<?php
// Our include
include 'functions.php';

if( isset($_POST['formData']) ) {
    $formData = $_POST['formData'];
} else {
    $formData = 0;
}

$file = '../../tasks.json';

if ($formData !== 0){

	$postedForm = array();
	parse_str($formData, $postedForm);

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

	$data = saveData($done,$time,$new,$file);

} else {

	$data = file_get_contents($file);
	$data = get_object_vars(json_decode($data));

}

echo getTheContent($data);


?>