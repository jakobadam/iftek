<?php

include("conf/conf.php");
include("models/user.php");

$conf = new Conf();

$conn = mysql_connect($conf->db_url, $conf->db_user, $conf->db_pwd);
$db = mysql_select_db($conf->db_name, $conn);

$query = "SELECT * FROM users";
$result = mysql_query($query);

$users = array();

while($row = mysql_fetch_array($result)){    
    $user = new User();
    $user->id = $row['id'];
    $user->email = $row['email'];
    $user->name = $row['name'];
    $user->name = $row['created_at'];
    $user->name = $row['updated_at'];
    $user->name = $row['password_hash'];
    $user->name = $row['password_salt'];
}  

print_r($users);

echo('foo');

?>
