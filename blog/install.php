<?php

require_once("dao/user_dao.php");
require_once("base_controller.php");
require_once("forms/signup_form.php");

$users = User_DAO::get_all_users();

if(count($users) == 0){
    $form = new SignupForm($_POST);

    if($form->validate_on_submit()){
        $user = new User();
        $form->populate_obj($user);
        User_DAO::add($user);
        flash("Oprettede bruger $user->name");
        $_SESSION['user_id'] = User_DAO::lastInsertId();
        header('Location: ' . url_root());
    }
    else{
        // Hvis der ikke er nogen bruger endnu gå til signup_form.html
        echo(render("signup_form.html", array('form'=>$form)));    
    }
}
else{
    flash_error("Konto allerede oprettet!");
    echo(render("base.html"));
}
