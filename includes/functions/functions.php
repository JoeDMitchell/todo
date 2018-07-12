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

    // GET THE EXISTING DATA
    $currentTasks = file_get_contents($file);
    $currentTasks = json_decode($currentTasks);

    // IF THERE ARE EXISTING TASKS
    if (isset($currentTasks->tasks)){

        $currentTaskArray = $currentTasks->tasks;

        $newTaskArray = array();
        $exists = 0;

        // ITERATE THROUGH EXISTING TASKS
        foreach ($currentTaskArray as $key => $val){

            if ($key == $new){

                // SAVE NEW DONE VALUES
                $newTaskArray[$key] = 1;
                $exists = 1;

            } else {

                // SAVE NEW NOT DONE VALUES
                $newTaskArray[$key] = 0;

            }
          
        }

        // SET THE DONE OR NOT DONE TASKS BACK UP
        if (is_array($done)){
            foreach ($done as $taskDone){
                $newTaskArray[$taskDone] = 1;
            }
        }

        // IF NEW TASK DOESN'T ALREADY EXIST ADD IT
        if ($exists == 0){
            if ($new != '' && $new !== 0){
                $newTaskArray[$new] = 0;
            }
        }

    } else {

        // NO TASKS SO IGNORE
        $newTaskArray = array();

    }

    /// IF A TIME HAS BEEN SET, SET UP THE CURRENT TIME AND MINUTES TO COUNT DOWN FROM
    if ($time > 0){

        $startTime = new DateTime();
        $data = array('time' => $time, 'startTime' => $startTime->getTimestamp());

    } else {

        // IF A TIME HAS BEEN PREVIOUSLY SET BUT NOT CHANGED, GET THAT AND SET IT UP
        if ($currentTasks){
           $data = array('time' => $currentTasks->time, 'startTime' => $currentTasks->startTime);
           $data['tasks'] = $newTaskArray; 
        } else {

            // OTHERWISE IGNORE
            $data = 0;
        }
        

    }

    // IF THERE IS DATA, SAVE IT!
    if ($data != 0){
        $json_data = json_encode($data);
        file_put_contents($file, $json_data);
    }

    // RETURN THE DATA
    return $data;

}

function calcTimeRemaining($totalTime,$startTime){

    // WORK OUT THE REMAINING TIME IN SECONDS
    $start = intVal($startTime) + (intVal($totalTime) * 60);

    // GET THE CURRENT DATETIME
    $now = new DateTime();
    $nowStamp = $now->getTimestamp();

    // MATHS THE DIFFERENCE
    $interval = max(0,$start - $nowStamp);

    // RETURN THE DIFFERENCE STYLISED
    return getNiceDuration($interval);

}

function getNiceDuration($durationInSeconds) {

    // BORROWED FUNCTION FROM STACK OVERFLOW - WORK OUT THE TIMES IN A USEABLE FORMAT
    $duration = '';
    $days = max(0,floor($durationInSeconds / 86400));
    $durationInSeconds -= $days * 86400;
    $hours = max(0,floor($durationInSeconds / 3600));
    $durationInSeconds -= $hours * 3600;
    $minutes = max(0,floor($durationInSeconds / 60));
    $seconds = max(0,$durationInSeconds - $minutes * 60);

    // OUTPUT AS NEEDED, MINS AND HOURS
    $duration .= '<span class="time-remaining__number" id="time-remaining__hours">' . sprintf("%02d", $hours) . '</span><span class="time-remaining__breaker">:</span>';
    
    $duration .= '<span class="time-remaining__number" id="time-remaining__mins">'. sprintf("%02d", $minutes) .'</span>';

    return array($duration, ($hours*60)+$minutes);
}


function getTheContent($data){

    // IF THERE ISN'T ANYTHING SAVED, OUTPUT THE TIME INPUT AND NOTHING ELSE
    if (!$data){
        return '<form action="" method="post" id="to-do-form">
        <label for="time" class="hidden">How long do you have?</label>
        <input type="number" name="time" id="time" placeholder="How long do you have? (mins)" autocomplete="off">
        <input type="submit" value="Start" id="add-time">
        </form>';
    }

    // IF THERE IS SOMETHING SAVED, OUTPUT THE REMAINING TIME
    if ($data){
        $remaining = calcTimeRemaining($data['time'],$data['startTime']);
    } else {
        $remaining = -1;
    }

    // SHOULD THE REMAINING TIME BE FLASHING AS 0 OR NOT??
    $remainingCount = $remaining[1];

    if (intval($remainingCount) == 0){
        $content = '<div class="time-remaining is-done">'.$remaining[0].'</div>';
    } elseif (intval($remainingCount) > -1){ 
        $content = '<div class="time-remaining">'.$remaining[0].'</div>';
    } else {
        $content = '';
    }
    
    // OUTPUT TO DO FORM INPUTS E.T.C.
    $content .= '<form action="" method="post" id="to-do-form">
        <label for="time" class="hidden">What\'s to do?</label>
        <input type="text" id="task" name="task" placeholder="Add a task" autocomplete="off">
        <input type="submit" value="Add" id="add-list">';

    // OUTPUT THE PROGRESS BAR
    $content .= '<div class="progress"><span class="progress__bar"></span></div>';

    // OUTPUT THE LIST
    $content .='<ul class="todo">';

    // IF TASKS ARE SET, OUTPUT THEM!
    if (isset($data['tasks'])){

        if (is_array($data['tasks']) || is_object($data['tasks'])){

            foreach($data['tasks'] as $key => $value){

                // CONVERT THE TO DO ITEM INTO A SLUG
                $slug = slugify($key);

                $content .= '<li>
                    <input type="checkbox" name="todo[]" value="'.$key.'" id="'.$slug.'"';

                // IF SAVED AS DONE, MARK AS DONE
                if (intval($value) == 1){ 
                    $content .= ' checked="checked"'; 
                } 

                $content .= '>
                    <label for="'.$slug.'">'.$key.'</label>
                </li>';

            }

        }

    }

    // CLOSE OFF THE FORM
    $content .= '</ul>
        <input type="submit" value="Save" id="save-list" class="hidden">
    </form>';

    return $content;
}

?>