<?php

// Kan kun køres via test.php ellers skal stierne prefixes med 

require_once('lectio.php');

function testLectioGetWeekYear(){
    // mktime(hour,minute,second,month,day,year,is_dst)
     
    $first_of_march_2012 = mktime(0, 0, 0, 3, 1, 2012);  
    
    $expected = '092012';
    $week_year = lectioGetWeekYear($first_of_march_2012);
    
    if($week_year != $expected){
        echo('FEJL testLectionGetWeekYear<br>');
        echo('Forventede:' . $expected . ' men var: ' . $week_year . '<br>');
    }
    else{
        echo('OK testLectionGetWeekYear<br>');    
    }
}

function testLectioGetSchemaURL(){
    $student_id = '1637126536';
    $first_of_march_2012 = mktime(0, 0, 0, 2, 20, 2012);
    $url = lectioGetSchemaURL($student_id, $first_of_march_2012);
    $expected = 'https://www.lectio.dk/lectio/256/SkemaNy.aspx?type=elev&elevid=1637126536&week=082012';
    if($expected != $url){
        echo('FEJL testLectioGetSchemaURL<br>');
    }
    else{
        echo('OK testLectioGetSchemaURL<br>');
    }
}

function testLectioSantize(){           
    $html = '
                l&#248;r 20/2 2. modul, 
                uge 8
            ';
    
    $expected = 'lør 20/2 2. modul, uge 8';
    $sanitized = lectioSantize($html);
    if($sanitized != $expected){
        echo('FEJL testLectioSantize<br>');
        echo('Forventede: ' . $expected . ' men er: ' . $sanitized . '<br>');
    }
    else{
        echo('OK testLectioSantize<br>');
    }  
}

function testLectioParseActivity(){
    $activity_url = 'test/activity.html';
    $html = file_get_html($activity_url);

    $activity = lectioParseActivity($html);
    
    $expected_status = 'Afholdt';
    $expected_time = 'ma 6/2 2. modul, uge 6';
    $expected_homework = 'Vejledningsbazar på Times Square Fravær: Føres af jeres ekspertlærer. Medbring et papir far første modul i dag, indholdende: - Afgrænset sag - Idé til problemformulering (spørgsmål eller påstand) - Idéer til materiale og metode - Forslag til fag, som kan afprøves i basaren.';
    $expected_class = '3s at';
    
    if($activity['status'] != $expected_status){
        echo('FEJL testLectioParseActivity<br>');
        echo('Forventede: ' . $expected_status . ' men er: ' . $activity['status']);
    }  
    else if($activity['time'] != $expected_time){
        echo('FEJL testLectioParseActivity<br>');
        echo('Forventede: ' . $expected_time . ' men er: ' . $activity['time']);
    }
    else if($activity['homework'] != $expected_homework){
        echo('FEJL testLectioParseActivity<br>');
        echo('Forventede: ' . $expected_homework . ' men er: ' . $activity['homework']);
    }
    else if($activity['class'] != $expected_class){
        echo('FEJL testLectioParseActivity<br>');
        echo('Forventede: ' . $expected_class . ' men er: ' . $activity['class']);
    }
    else{
        echo('OK testLectioParseActivity<br>');        
    }
}

function testLectioGetActivities(){
    $student_id = '1637126536';
    $feb_22_2012 = mktime(0, 0, 0, 2, 22, 2012);
    
    // https://www.lectio.dk/lectio/256/SkemaNy.aspx?type=elev&elevid=1637126536&week=082012
    $activities = lectioGetActivities($student_id, $feb_22_2012);
    
    $expected_starts_with = 'Hold: 3g Fy Tidspunkt:';
    $activity = $activities[0];
    
    if(count($activities) != 3){
        echo('FEJL testLectioGetActivities<br>');
        echo('Forventede 3 aktiviter, men der er: ' . count($activities) . '<br>');
    }
    // fysik
    else if($activity['status'] != 'Afholdt'){
        echo('FEJL testLectioGetActivities<br>');
        echo('Forventede status var afholdt, men var:' . $activity['status']);
    }
    else if($activity['time'] != 'on 22/2 1. modul, uge 8'){
        echo('FEJL testLectioGetActivities<br>');
        echo('Forventede at tiden er: on 22/2 1. modul, uge 8, men den var:' . $activity['time']);
    }
    else if($activity['class'] != '3g Fy'){
        echo('FEJL testLectioGetActivities<br>');
        echo('Forventede at holdet er 3g Fy, men det var: ' . $activity['class']);
    }
    else{
        echo('OK testLectioGetActivities<br>');
    }
}

function testParserWithForeignCharacters(){
    $expected = 'æøå';
    $html = "<h1>$expected</h1>";
    $dom = str_get_html($html);
    $actual = $dom->root->text();
   
    if($actual != $expected){
        echo('FEJL testParserWithForeignCharacters<br>');
        echo('Forventede: ' . $expected . ' men var: ' . $actual . '<br>');
    }
    else{
        echo('OK testParserWithForeignCharacters<br>');
    }
}

testLectioSantize();
testParserWithForeignCharacters();
testLectioGetWeekYear();
testLectioGetSchemaURL();
testLectioParseActivity();
testLectioGetActivities();

?>