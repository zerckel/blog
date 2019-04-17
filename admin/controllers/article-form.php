<?php

require_once '../../tools/common.php';

isAdmin();

require('../model/article-form.php');
//sauvegarde
function upload($wich){
    $message = array();
    $result = isset($_FILES['my_file'])? files(): false;
    if($result !== "mauvaise extension"){
        $destination = $result;
    }else{
        $message[0] = $result;
    }
    if($wich === $_POST['save']){
        $results = isset($destination)? $results = insert($destination) : $results = insert()  ;
    }else{
        $results = isset($destination)? $results = modified($destination) : $results = modified()  ;
    }
    $message[1] = doesItWork($results);

    return $message;
}

$message = isset($_POST['save']) ? upload($_POST['save']) : false;

//modification
$article = isset($_GET['action']) AND isset($_GET['article_id']) AND $_GET['action'] === 'edit'? $article = update($_GET['article_id']): false;

$message = isset($_POST['update'])? upload($_POST['update']) : false;

$categories = getCat();
require('../view/article-form.php');


