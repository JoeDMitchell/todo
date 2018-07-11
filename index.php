<?php 
include 'includes/functions/functions.php';

$file = 'tasks.json';

if ($_POST){

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

	$data = saveData($done,$time,$new,$file);

} else {


	$data = file_get_contents($file);
	if ($data){
		$data = get_object_vars(json_decode($data));
	} else {
		$data = 0;
	}
	

}

include 'includes/static/header.php'; ?>

<div class="container" id="main-content">
	<div class="row">
		<div class="col">

			<div class="changeable-content">

				<?php 
				echo getTheContent($data);
				?>
				

			</div>
		</div>
	</div>
</div>

<?php include 'includes/static/footer.php'; ?>