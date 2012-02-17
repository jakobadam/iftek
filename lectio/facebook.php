<?php

require_once("conf/config.php");


function fbInit($code){
    $access_token = fbGetAccessToken($code); 
    $user = fbInitUser($access_token); 
    return $user;
}

function fbInitUser($access_token){
    $graph_url = "https://graph.facebook.com/me?access_token=" . $access_token;
    $user = json_decode(file_get_contents($graph_url));
    return $user;
}

function fbGetUser(){
    global $user;
    return $user;
}

function fbGetLoginURL(){
    
    //https://graph.facebook.com/oauth/authorize?client_id=202423869273&redirect_uri=http%3A%2F%2Fwww.endomondo.com%2Ffacebook%2Fconnect%3Faction%3Dsignin&scope=publish_stream,offline_access,email"
    
    // CSRF protection
    // http://en.wikipedia.org/wiki/Cross-site_request_forgery    
    $unique_rand_id = md5(uniqid(rand(), TRUE));
    $_SESSION['state'] = $unique_rand_id;
    
    $dialog_url = "http://graph.facebook.com/oauth/authorize?client_id=" . APP_ID 
    . "&redirect_uri=" . urlencode(APP_URL) 
    . "&state=" . $unique_rand_id . "&scope=" . APP_PERMISSIONS;
    
    return $dialog_url;
}

function fbGetLogoutURL(){
    
}

function fbGetAccessToken($code){
    
    // CSRF check
    if($_REQUEST['state'] != $_SESSION['state']){
        echo('The state does not match. You may be a victim of CSRF.');
        die();   
    }
    
    $token_url = "https://graph.facebook.com/oauth/access_token?" 
    . "client_id=" . APP_ID 
    . "&redirect_uri=" . urlencode(APP_URL) 
    . "&client_secret=" . APP_SECRET 
    . "&code=" . $code;

    $response = file_get_contents($token_url);
    
    $params = null;
    parse_str($response, $params);
    
    return $params['access_token'];
}

function fbSetAccessToken(){
    
}



function fbPost($msg){
    global $user;
    
    $user_feed_url = 'https://graph.facebook.com/' . $user->id . '/feed' . '?app_id=' . $app_id;
    $data = array('access_token'=>$params['access_token'], 'message'=>$msg);
    
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $user_feed_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    
    $response = curl_exec($ch);
    if($response == false){
        echo(curl_error($ch));
        die();   
    }
    curl_close($ch);
    return $result;
}

?>