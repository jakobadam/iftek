<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            
            include("dao/post_dao.php");
            
            
            
            $posts = Post_DAO::get_all_posts();
            
            if (count($posts) == 0) {
                Post_DAO::add_post("test", "test2", true, 1);
                $posts = Post_DAO::get_all_posts();
            }
            
            for ($i = 1; $i <= 10; $i++) {
                echo $i;
            }
            foreach ($posts as $post) {   
                echo "Value: " . $post->title . "<br />\n";   
            }
        ?>
    </body>
</html>
