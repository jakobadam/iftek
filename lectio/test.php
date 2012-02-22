<?php

require_once('base_controller.php');
require_once('facebook.php');
require_once('lectio.php');

function testSaveUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $obj = json_decode(file_get_contents('db/1'));   
    if($token != $obj->access_token){
        echo('FEJL testSaveUser<br>'); 
    }
    else{
        echo('OK testSaveUser<br>');
    }
}

function testGetLocalUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $local_user = fbGetLocalUser($user->id);
    if(!property_exists($local_user, 'access_token')){
        echo('FEJL testGetLocalUser<br>');
    }
    else if($local_user->access_token != 'foobar'){
        echo('FEJL testGetLocalUser<br>');
    }
    else{
        echo('OK testGetLocalUser<br>');
    }
}

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

function testLectioParseActivity(){
    $activity_url = '/lectio/256/aktivitet/aktivitetinfo.aspx?id=3671690080&prevurl=SkemaNy.aspx?type=elev&elevid=1637126536&week=082012';
    $expected_starts_with = 'Hold: 3s EN Tidspunkt:'; 
    
    $activity_html = lectioParseActivity($activity_url); 
    
    $activity_sub_string = substr($activity_html, 0, strlen($expected_starts_with));
    
    if($expected_starts_with != $activity_sub_string){
        echo('FEJL testLectioParseActivity<br>');
        echo('Expected: <br>' . $expected_starts_with . ' var: <br>' . $activity_sub_string);
    }
    else{
        echo('OK testLectioParseActivity<br>');
    }
}

function testLectioGetActivities(){
    $student_id = '1637126536';
    $feb_22_2012 = mktime(0, 0, 0, 2, 22, 2012);
    $activities_html = lectioGetActivities($student_id, $feb_22_2012);
    
    $expected_starts_with = 'Hold: 3g Fy Tidspunkt:';     
    $activities_sub_string = substr($activities_html, 0, strlen($expected_starts_with));
    
    if($activities_sub_string != $expected_starts_with){
        echo('FEJL testLectioGetActivities<br>');
    }
    else{
        echo('OK testLectioGetActivities<br>');
    }
}


testSaveUser();
testGetLocalUser();
testLectioGetWeekYear();
testLectioGetSchemaURL();
testLectioParseActivity();
testLectioGetActivities();

?>