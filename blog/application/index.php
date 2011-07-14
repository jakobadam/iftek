<?php

include("base_controller.php");

$routes = array(
	
		);
// include_once("dao/user_dao.php");
// 
// $user_dao = new User_DAO();
// $foo = $user_dao->get_all_users();
// 
// for($i = 0; $i < sizeof($foo); ++$i)
// {
    // $user = $foo[$i];
    // echo("i " . $user->id . " navn: " . $user->name);
// }
/*
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
    $user->created_at = $row['created_at'];
    $user->updated_at = $row['updated_at'];
    $user->password_hash = $row['password_hash'];
    $user->password_salt = $row['password_salt'];
}  

$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('post.html');
echo $template->render(array());
*/
?>
