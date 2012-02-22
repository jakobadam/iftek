<?php

require_once("conf/config.php");

/**
 * Hent bruger data fra facebook.
 * 
 * @param $access_token fra login.
 */
function fbGetUser($access_token){
    $graph_url = "https://graph.facebook.com/me?access_token=" . $access_token;
    $user = json_decode(file_get_contents($graph_url));
    $user->access_token = $access_token;
    return $user;
}

function fbIsLoggedIn(){
    return isset($_SESSION['fbUser']);
}

function fbGetLoginURL(){    
    // CSRF protection
    // http://en.wikipedia.org/wiki/Cross-site_request_forgery    
    $unique_rand_id = md5(uniqid(rand(), TRUE));
    $_SESSION['state'] = $unique_rand_id;
    
    $dialog_url = "http://graph.facebook.com/oauth/authorize?client_id=" . APP_ID 
    . "&redirect_uri=" . urlencode(APP_REDIRECT_URL) 
    . "&state=" . $unique_rand_id . "&scope=" . APP_PERMISSIONS;
    
    return $dialog_url;
}

function fbGetLogoutURL(){
    
}

/**
 * Hent token fra facebook der skal benyttes til at foretage handlinger på
 * brugerens vegne.
 * 
 * @param $code midlertidig kode fået via facebook login.
 * @return token
 */
function fbGetAccessToken($code){
    
    // CSRF check
    if($_REQUEST['state'] != $_SESSION['state']){
        echo('The state does not match. You may be a victim of CSRF.');
        die();   
    }
    
    $token_url = "https://graph.facebook.com/oauth/access_token?" 
    . "client_id=" . APP_ID 
    . "&redirect_uri=" . urlencode(APP_REDIRECT_URL) 
    . "&client_secret=" . APP_SECRET 
    . "&code=" . $code;

    $response = file_get_contents($token_url);
    
    $params = null;
    parse_str($response, $params);
    
    return $params['access_token'];
}

function fbGetOrCreateLocalUser($access_token){
    $fb_user = fbGetUser($access_token);
    $user = fbGetLocalUser($fb_user->id);
    if($user == null){
        $fb_user->access_token = $access_token;
        fbSaveUser($fb_user);
        $user = $fb_user;
    }
    return $user;
}

function fbGetLocalUser($id){
    $user; 
    $path = DB_PATH . '/' . $id;
    $contents = file_get_contents($path);
    if($contents != null){
        $user = json_decode($contents);
    }
    return $user;
}

/**
 * Gem brugeren lokalt.
 * 
 * @param $user brugeren der skal gemmes.
 */
function fbSaveUser($user){
    $file_name = $user->id;
    $path = DB_PATH . '/' . $file_name;
    $fh = fopen($path, 'w');
    
    if(!$fh){
        echo "Could not open file: $path";
        die();
    }
    fwrite($fh, json_encode($user));
    fclose($fh);
}

/**
 * Post besked til brugers væg.
 * 
 * @param $user brugeren der hvis væg der skal postes til.
 * @param $msg beskeden der skal postes.
 * 
 * @return respons fra facebook.
 */
function fbPost($user, $msg){
    
    $user_feed_url = 'https://graph.facebook.com/' . $user->id . '/feed' . '?app_id=' . APP_ID;
    
    $data = array('access_token'=>$user->access_token, 'message'=>utf8_encode($msg));
    
    print_r($data);
    die();
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $user_feed_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    
    $response = curl_exec($ch);
    if($response == false){
        echo(curl_error($ch));
        die();   
    }
    curl_close($ch);
    return $response;
}

?>