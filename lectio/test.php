<?php

require_once('facebook.php');
require_once('lectio.php');

function testSaveUser(){
    $user = json_decode('{"id":1}');
    $token = 'foobar';
    $user->access_token = $token;
    fbSaveUser($user);
    
    $obj = json_decode(file_get_contents('db/1'));   
    if($token != $obj->access_token){
        echo('FEJL test_save_user<br>'); 
    }
    else{
        echo('OK test_save_user<br>');
    }
}

function testLectioGetWeekYear(){
    // mktime(hour,minute,second,month,day,year,is_dst)
     
    $first_of_march_2012 = mktime(0, 0, 0, 3, 1, 2012);  
    
    $expected = '092012';
    $week_year = lectioGetWeekYear($first_of_march_2012);
    
    if($week_year != $expected){
        echo('FEJL test_lectio_get_week_year<br>');
        echo('Forventede:' . $expected . ' men var: ' . $week_year . '<br>');
    }
    else{
        echo('OK test_lectio_get_week_year');    
    }
}

function testLectioGetSchemaURL(){
    $student_id = '1637126536';
    $first_of_march_2012 = mktime(0, 0, 0, 2, 20, 2012);
    $url = lectioGetSchemaURL($student_id, $first_of_march_2012);
    $expected = 'https://www.lectio.dk/lectio/256/SkemaNy.aspx?type=elev&elevid=1637126536&week=082012';
    if($expected != $url){
        echo('FEJL test_lectio_get_schema_url<br>');
    }
    else{
        echo('OK test_lectio_get_schema_url');
    }
}

function testLectioParseActivity(){
    $activity_url = '/lectio/256/aktivitet/aktivitetinfo.aspx?id=3671690080&prevurl=SkemaNy.aspx?type=elev&elevid=1637126536&week=082012';
    echo lectioParseActivity($activity_url);
    
}

function testLectioGetActivities(){
    $student_id = '1637126536';
    $first_of_march_2012 = mktime(0, 0, 0, 2, 20, 2012);
    echo lectioGetActivities($student_id, $first_of_march_2012);
}

testLectioParseActivity();

//testLectioGetActivities();

//test_save_user();
//test_lectio_get_week_year();
//test_lectio_get_schema_url();
//test_lectio_parser();

?>