<?php

function delete ($article_id = false){
    $db = connect();
    if($article_id ) {
        $query = $db->prepare('DELETE FROM article WHERE id= ?');
        $query->execute(
            [
                $article_id
            ]
        );
        header('Location: article-list.php?deletecomplete=1');
        exit();
    }
    if (isset($_GET['deletecomplete']) == 1){
        $message = 'Suppression effectué';
        return $message;

    }
    if (isset($_GET['updatecomplete']) == 1){
        $message = 'Modification effectué';
        return $message;
    }
}

$db = connect();
//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM article ORDER BY id DESC');
$articles = $query->fetchall();