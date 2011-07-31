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
            
            // Print alle posts
            foreach ($posts as $post) {   
                echo $post->title . "<br />\n" . $post->created_at . "<br />\n" . $post->body . "<br />\n<br />\n";
            }
            
            // Ret alle posts
            /*
            foreach ($posts as $post) {
                $post->title = $post->title . "1";
                Post_DAO::update_post($post);
            }
            
            // Print alle posts igen
            $posts = Post_DAO::get_all_posts();
            foreach ($posts as $post) {   
                echo $post->title . "<br />\n" . $post->created_at . "<br />\n" . $post->body . "<br />\n<br />\n";
            }
            */
        ?>
    </body>
</html>
