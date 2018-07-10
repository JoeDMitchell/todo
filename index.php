<?php 
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

	$currentTasks = file_get_contents($file);
	$currentTasks = json_decode($currentTasks);
	if ($currentTasks){

		$currentTaskArray = $currentTasks->tasks;

		$newTaskArray = array();
		$exists = 0;

		foreach ($currentTaskArray as $key => $val){

			if ($key == $new){

				$newTaskArray[$key] = 1;
				$exists = 1;

			} else {

				$newTaskArray[$key] = 0;

			}
			
		}

		if (is_array($done)){
			foreach ($done as $taskDone){
				$newTaskArray[$taskDone] = 1;
			}
		}

		if ($exists == 0){
			if ($new != '' && $new != 0){
				$newTaskArray[$new] = 0;
			}
		}

	} else {

		$newTaskArray = array($new => 0);

	}

	if ($time > 0){
		$data = array('time' => $time);
	} else {
		$data = array('time' => $currentTasks->time);
	}

	$data['tasks'] = $newTaskArray;

	$json_data = json_encode($data);
	file_put_contents($file, $json_data);

} else {

	$data = file_get_contents($file);
	$data = get_object_vars(json_decode($data));

}

include 'includes/static/header.php'; 
include 'includes/functions/functions.php'; ?>

<div class="container" id="main-content">
	<div class="row">
		<div class="col">
			<h1>Time To Do</h1>
			<form action="" method="post">
				<label for="time">How long do you have?</label>
				<input type="number" name="time" id="time" placeholder="mins">
				<label for="time">What's to do?</label>
				<input type="text" id="task" name="task">
				<ul class="todo">
					<?php foreach($data['tasks'] as $key => $value){

						$slug = slugify($key);
						?>

						<li>
							<input type="checkbox" name="todo[]" value="<?php echo $key; ?>" id="<?php echo $slug; ?>" <?php if ($value == 1){ echo 'checked="checked"'; } ?>>
							<label for="<?php echo $slug; ?>"><?php echo $key; ?></label>
						</li>

					<?php } ?>
				</ul>
				<input type="submit" value="Save" id="save-list">
			</form>
		</div>
	</div>
</div>

<?php include 'includes/static/footer.php'; ?>