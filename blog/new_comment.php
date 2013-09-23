<?php
    require("controller.php");

    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo("Denne handling er kun lovlig med POST");
        die();
    }

    login_required();
	// NOTE: user_id sættes ved login, og er derfor altid tilstede her.
    $user_id = $_SESSION['user_id'];

    $comment = ''; // OPGAVE: Læs værdien af kommentaren ud fra POST
    $post_id = $_GET['id'];
    
    // OPGAVE: Tilpas nedenstående SQL, så det passer til din comments tabel
    $sql = "INSERT INTO comments (post_id, user_id, comment, is_removed) VALUES (?,?,?,0)";
    $stm = db_query($sql, array($post_id, $user_id, $comment));

    // Vis fejl hvis kommentar ikke kunne indsættes
    if (!$stm) {
        die('Fejl ved indsættelse: ' . mysql_error());
    }

    flash('Ny kommentar oprettet!');
    
    // OPGAVE: Hvad gør nedenstående linje, og hvorfor?
    header('Location: post.php?id=' . $post_id);
?>