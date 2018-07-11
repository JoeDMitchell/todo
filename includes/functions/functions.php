<?php

function slugify($text) {
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function saveData($done,$time,$new,$file){

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
            if ($new != '' && $new !== 0){
                $newTaskArray[$new] = 0;
            }
        }

    } else {

        $newTaskArray = array($new => 0);

    }

    if ($time > 0){

        $startTime = new DateTime();
        $data = array('time' => $time, 'startTime' => $startTime->getTimestamp());

    } else {

        $data = array('time' => $currentTasks->time, 'startTime' => $currentTasks->startTime);
        $data['tasks'] = $newTaskArray;

    }

    $json_data = json_encode($data);
    file_put_contents($file, $json_data);

    return $data;

}

function calcTimeRemaining($totalTime,$startTime){
    //$ts = $startTime;
    //$start = new DateTime("@$ts");
    //$start->add(new DateInterval('PT' . $totalTime . 'M'));

    $start = intVal($startTime) + (intVal($totalTime) * 60);

    $now = new DateTime();
    $nowStamp = $now->getTimestamp();



    $interval = $start - $nowStamp;
    //$seconds = $interval->format('%s');

    if ($interval < 1){
        return -1;
    } else {
        return getNiceDuration($interval);
    }
    

}

function getNiceDuration($durationInSeconds) {

  $duration = '';
  $days = floor($durationInSeconds / 86400);
  $durationInSeconds -= $days * 86400;
  $hours = floor($durationInSeconds / 3600);
  $durationInSeconds -= $hours * 3600;
  $minutes = floor($durationInSeconds / 60);
  $seconds = $durationInSeconds - $minutes * 60;

  if($days > 0) {
    $duration .= $days . ' days';
  }
  if($hours > 0) {
    $duration .= ' ' . $hours . ' hours';
  }
  if($minutes > 0) {
    $duration .= ' ' . $minutes . ' minutes';
  }
  if($seconds > 0) {
    $duration .= ' ' . $seconds . ' seconds';
  }
  return $duration;
}


function getTheContent($data){

    if (!$data['time']){
        return '<form action="" method="post" id="to-do-form">
        <label for="time">How long do you have?</label>
        <input type="number" name="time" id="time" placeholder="mins">
        <input type="submit" value="Start" id="add-time">
        </form>';
    }

    if ($data){
        $remaining = calcTimeRemaining($data['time'],$data['startTime']);
    } else {
        $remaining = -1;
    }

    if ($remaining > -1){ 
        $content = '<div class="time-remaining">
            Time Remaining: <span class="time-remaining__time">'.$remaining.'</span>
        </div>';
    } else {
        $content = '';
    }

    if (isset($data['tasks'])){
        $totalTasks = 0;
        $totalDone = 0;

        foreach($data['tasks'] as $value){
            $totalTasks++;
            if ($value == 1){
                $totalDone++;
            }
        }

        $percent = ($totalDone / $totalTasks) * 100;

        $content .= '<div class="progress"><span class="progress__bar" style="width:'.$percent.'%;"></span></div>';
    }
    
    $content .= '<form action="" method="post" id="to-do-form">
        <label for="time">What\'s to do?</label>
        <input type="text" id="task" name="task">
        <input type="submit" value="Add" id="add-list">
        <ul class="todo">';

    if (isset($data['tasks'])){

        foreach($data['tasks'] as $key => $value){

            //if ($key !== 0){

                $slug = slugify($key);

                $content .= '<li>
                    <input type="checkbox" name="todo[]" value="'.$key.'" id="'.$slug.'"';

                if ($value == 1){ 
                    $content .= ' checked="checked"'; 
                } 

                $content .= '>
                    <label for="'.$slug.'">'.$key.'</label>
                </li>';

            //}

        }

    }

    $content .= '</ul>
        <input type="submit" value="Save" id="save-list">
    </form>';

    return $content;
}

?>