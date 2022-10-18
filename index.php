<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calender</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php
        if ($_GET["offset"] == "" || $_GET["offset"] == "0"){
            $selectedMonth = '0';
        }
        else{
            $selectedMonth = $_GET["offset"];
        }
        
        $date_Y = date("Y", strtotime($selectedMonth . " month"));
        $date_m = date("m", strtotime($selectedMonth . " month"));
        $date_d = date("d", strtotime($selectedMonth . " month"));

        $currentDate = date('Y-m-d');
        
        $clickedDate = $_GET['clickedDate'] ?? $currentDate;

        $totalDaysM = cal_days_in_month(CAL_GREGORIAN, $date_m, $date_Y);


        $myfile = fopen("events.txt", "r") or die("Unable to open file!");
        if (filesize("events.txt") == 0){
            $eventArray = array();
        }
        else{
            $eventArray = preg_split("/\r\n|\n|\r/", fread($myfile,filesize("events.txt")));

        }
        fclose($myfile);
    ?>
    <div class="kalender">
        <div class="leftSide">
            <div id="underOne"></div>
            <div id="underTwo"></div>
            <div id="container">
                <div class="topBar">
                    <?php
                        echo '<a id="prev" href="?offset='. $_GET["offset"] - 1 .'&clickedDate='.$clickedDate.'">'.date('M', strtotime('-1month', strtotime(date($date_Y."-".$date_m."-".$date_d)))).'</a>'
                    ?>
                    <h1>
                        <?php 
                            echo date('M', strtotime($selectedMonth . " month")).' - '.$date_Y;
                        ?>
                    </h1>
                    <?php
                        echo '<a id="next" href="?offset='. $_GET["offset"] + 1 .'&clickedDate='.$clickedDate.'">'.date('M', strtotime('+1month', strtotime(date($date_Y."-".$date_m."-".$date_d)))).'</a>'
                    ?>
                </div>
                <div class="dateContainer">
                <div class="weekDay"></div>

                    <div class="weekDay">Mon</div>
                    <div class="weekDay">Tue</div>
                    <div class="weekDay">Wed</div>
                    <div class="weekDay">Thu</div>
                    <div class="weekDay">Fri</div>
                    <div class="weekDay">Sat</div>
                    <div class="weekDay red">Sun</div>
                    <?php
                        include 'namnsdag.php';

                        $elementsAdded = 0;
                        $weekElementsAdded = 0;
                        $weekElementsM = $date_m;

                        for ($i=0; $i < date('N', strtotime($date_Y.'-'.$date_m.'-01'))-1; $i++) { 
                            if ($elementsAdded % 7 == 0){
                                if($weekElementsAdded > $totalDaysM){
                                    $weekElementsM += 1;
                                    $weekElementsAdded = 0;
                                }
                                echo '<div class="week-dummy">w.'.date("W", strtotime($date_Y.'-'.$weekElementsM.'-'.$weekElementsAdded)).'</div>';
                                
                                $weekElementsAdded+=7;
                            }
                            echo '<div class="dummy"></div>';
                            $elementsAdded += 1;
                        }

                        for ($i=1; $i <= $totalDaysM ; $i++) { 
                            if ($elementsAdded % 7 == 0){
                                if($weekElementsAdded > $totalDaysM){
                                    $weekElementsM += 1;
                                    $weekElementsAdded = 0;
                                }
                                echo '<div class="week-dummy">w.'.date("W", strtotime($date_Y.'-'.$weekElementsM.'-'.$weekElementsAdded)).'</div>';
                                
                                $weekElementsAdded+=7;
                            }


                            $events_on_this_day = 0;

                            foreach ($eventArray as $key => $value) {
                                $values = explode('', $value);
                                
        
                                if ($values[0] == $date_Y.'-'.$date_m.'-'.$i){
                                    $events_on_this_day += 1;
                                }
                            }

                            if ($events_on_this_day > 0){
                                $events_on_this_day = '<div class="eventCounter">'. $events_on_this_day ." events</div>";
                            }
                            else{
                                $events_on_this_day = "";
                            }



                            if ($date_Y.'-'.$date_m.'-'.$i == $currentDate){
                                echo '<a href="?offset='.$selectedMonth.'&clickedDate=' . $date_Y.'-'.$date_m.'-'.$i . '" class="date active">' . $i .'<div class="nameD">'. implode(', ', $namnsdag[intval(date("z", strtotime($date_Y.'-'.$date_m.'-'.$i))) + 1]). '</div>'  . $events_on_this_day . '</a>';
                            }
                            else if ($date_Y.'-'.$date_m.'-'.$i == $clickedDate){
                                echo '<a href="?offset='.$selectedMonth.'&clickedDate=' . $date_Y.'-'.$date_m.'-'.$i . '" class="date clickedDate">' . $i .'<div class="nameD">'. implode(', ', $namnsdag[intval(date("z", strtotime($date_Y.'-'.$date_m.'-'.$i))) + 1]). '</div>' . $events_on_this_day . '</a>';
                            }
                            else{
                                echo '<a href="?offset='.$selectedMonth.'&clickedDate=' . $date_Y.'-'.$date_m.'-'.$i . '" class="date">' . $i .'<div class="nameD">'. implode(', ', $namnsdag[intval(date("z", strtotime($date_Y.'-'.$date_m.'-'.$i))) + 1]) .'</div>' . $events_on_this_day . '</a>';
                            }

                            $elementsAdded += 1;
                        }
                    ?>
                </div>
            </div>
            
        </div>
        <div class="rightSide">
            <div class="topBar">
                <?php
                    echo '<img class="monthIcon" src="./img/'.date('n',strtotime($clickedDate)).'.png" alt="icon for the current month">'
                ?>
                <h2 class="today">
                    <?php
                        echo date('l', strtotime($clickedDate));
                    ?>
                </h2>
                <p class="todayDate">
                    <?php
                        echo date('j', strtotime($clickedDate)).' '.date('F', strtotime($clickedDate)).' '.date('Y', strtotime($clickedDate));
                    ?>
                </p>
            </div>
            <p class="nameD">
                <?php 
                    echo implode(', ', $namnsdag[intval(date("z", strtotime($clickedDate))) + 1])
                ?>
            </p>
            <div class="midBar">
                

                <div class="eventInputBox">
                    <div class="eventTitleContainer">
                        <label for="title">Event Title:</label>
                        <input type="text" id="eventTitle" name="title" placeholder="ex. Birthday..."><br>
                    </div>

                    <div class="eventDescContainer">
                        <label for="description">Event Description:</label>
                        <textarea name="description" id="eventDesc" cols="10" rows="5" maxlength="120" placeholder="Short description here..."></textarea>
                    </div>
                    
                    <label for="time">Time for event:</label>
                    <input type="time" id="time" name="time" value=""><br><br>
                    <input type="submit" value="Submit" class="submitBtn">
                </div> 

                <script defer>
                    $(".submitBtn").click(function(){

                        if ($('#time').val().trim() == "" || $('#eventTitle').val().trim() == "") {
                            return;
                        }
                        else{
                            var eventInfo = "./addevent.php?date=<?php echo $clickedDate; ?>&time=" + $('#time').val() + "&title=" + $('#eventTitle').val() + "&description=" + $('#eventDesc').val();
                            $.get(eventInfo);
                            location.reload();
                        }
                    })
                </script>

                <?php
                    $isThereEvent = false;

                    foreach ($eventArray as $key => $value) {
                        $values = explode('', $value);
                        

                        if ($values[0] == $clickedDate){
                            // print_r($values);
                            $isThereEvent = true;
                            echo '<div class="event">
                                    <h3 class="eventTitel">
                                        <ul><li>'.$values[2].' : '.$values[1].'</li></ul>
                                    </h3>
                                    <p class="eventDecs">'.$values[3].'</p>
                                </div>';
                        }
                    }

                    if ($isThereEvent == false) {
                        echo '<h3 class="event">There are no events this day.</h3>';
                    }
                ?>
            </div>

            <div class="bottomBar">
                <div class="eventBtn">Add event</div>
                <p class="time">
                    <?php
                        echo date('H.i.s - e');
                    ?>
                </p>
            </div>
        </div>
    </div>

    <script>
        $('.eventBtn').click(function(){
            if ($('.eventInputBox').is(":hidden")){
                $('.event').hide();
                $('.eventInputBox').show();
                console.log('bruh');
                $('.eventBtn').text('Exit');
            }
            else{
                $('.eventInputBox').hide();
                $('.event').show();
                console.log('nay');
                $('.eventBtn').text('Add event');
                $('#eventTitle, #time, #eventDesc').val("");
            }
        })
    </script>
</body>
</html>