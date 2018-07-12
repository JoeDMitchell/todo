<?php 
include 'includes/functions/functions.php';

// SAVE FILE
$file = 'tasks.json';

if ($_POST){

	// GET THE POSTED VALUES
	if (isset($_POST['todo'])){
		$done = $_POST['todo'];
	} else {
		$done = 0;
	}

	if (isset($_POST['time'])){
		$time = $_POST['time'];
	} else {
		$time = 0;
	}

	if (isset($_POST['task'])){
		$new = $_POST['task'];
	} else {
		$new = 0;
	}

	// SAVE THE POSTED VALUES AND OUTPUT THEM
	$data = saveData($done,$time,$new,$file);

} else {

	// IF NOTHING POSTED, GET THE EXISTING DATA
	$data = file_get_contents($file);

	if ($data){
		$data = get_object_vars(json_decode($data));
	} else {

		// IF NO DATA, SET VALUE TO 0
		$data = 0;
	}

}

include 'includes/static/header.php'; ?>

<div class="container" id="main-content">
	<div class="row">
		<div class="col">

			<div class="changeable-content">

				<?php 
				// ECHO OUT THE CONTENT FUNCTION
				echo getTheContent($data);
				?>
				

			</div>
		</div>
	</div>
</div>

<?php include 'includes/static/footer.php'; ?>