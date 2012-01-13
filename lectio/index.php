<?php

require_once("conf/config.php");

$app_id = APP_ID;
$app_secret = APP_SECRET;
$my_url = APP_URL;

session_start();
$code = $_REQUEST["code"];

if(empty($code)) {
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
    //CSRF protection
    $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id 
    . "&redirect_uri=" . urlencode($my_url) 
    . "&state=" . $_SESSION['state'] . "&scope=publish_stream,sms,offline_access";

    echo("<script> top.location.href='" . $dialog_url . "'</script>");
}

if($_REQUEST['state'] == $_SESSION['state']) {
    $token_url = "https://graph.facebook.com/oauth/access_token?" 
    . "client_id=" . $app_id 
    . "&redirect_uri=" . urlencode($my_url) 
    . "&client_secret=" . $app_secret 
    . "&code=" . $code;

    $response = @file_get_contents($token_url);
    $params = null;
    
    parse_str($response, $params);
 
    $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'] ;
    $user = json_decode(file_get_contents($graph_url));
    
    $user_feed_url = 'https://graph.facebook.com/' . $user->id . '/feed' . '?app_id=' . $app_id;
    $data = array('access_token'=>$params['access_token'], 'message'=>'wii');
    
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $user_feed_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    print_r($result);
    
} else {
    echo("The state does not match. You may be a victim of CSRF.");
}?>
