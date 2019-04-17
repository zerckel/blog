<?php
//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article

function insert ($destination = ''){
    $db = connect();
        $query = $db->prepare('INSERT INTO article (title, content, summary, published_at, is_published, image) VALUES (?, ?, ?, ?, ?, ?)');
        $newArticle = $query->execute(
            [
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['content']),
                htmlspecialchars($_POST['summary']),
                htmlspecialchars($_POST['published_at']),
                ctype_digit($_POST['is_published']),
                htmlspecialchars($destination)
            ]
        );
        $Last_id = $db->lastInsertId();
        $query = $db->prepare('INSERT INTO article_category (article_id, category_id) VALUES (?, ?)');
        $result = $query->execute(
            [
                $Last_id,
                $_POST['category_id']
            ]
        );
        return $results = array($newArticle, $result);
    }

function doesItWork($results){
    //redirection après enregistrement
    //si $newArticle alors l'enregistrement a fonctionné
    if($results[1] AND $results[0]){
        //redirection après enregistrement
        if ($_POST['update']){
            header('Location: article-list.php?updatecomplete=1');
        }else{
            header('location:article-list.php');

        }
        exit;
    }
    else{ //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel article...";
        return $message;
    }
}

function files(){
    if(isset($_FILES['my_file'])){
        $allowed_extensions = array( 'jpg' , 'jpeg' , 'gif' , 'png' , 'zip' );
        $my_file_extension = pathinfo( $_FILES['my_file']['name'] , PATHINFO_EXTENSION);

        if ( in_array($my_file_extension , $allowed_extensions) ){

            do {
                $new_file_name = rand();
                $destination = 'files/' . $new_file_name . '.' . $my_file_extension;
            } while (file_exists($destination));
            move_uploaded_file( $_FILES['my_file']['tmp_name'], $destination);
            if(isset($_POST['pics'])){
                unlink($_POST['pics']);
                if (!empty($destination)){$destination = $_POST['pics'];}
            }
            return $destination;
        }
        else{
            $message = "mauvaise extension";
            return $message;
        }
    }
}

function update($article_id){
    $db = connect();
    //variable avec les infos de l'article
    if ($article_id) {
        $query = $db->prepare('SELECT * FROM article WHERE id = ?');
        $query->execute(
            [
                $article_id
            ]
        );
        $article = $query->fetch();
        return $article;
    }
}

function modified($destination = ""){
    $db = connect();
    $queryupdate = $db->prepare('UPDATE article
                                           SET title = :new_title, summary = :new_summary, content = :new_content, published_at = :new_date, is_published = :new_published, image = :new_image 
                                           WHERE id = :up_id');
    $result = $queryupdate->execute(
        [
            'new_title' => $_POST['title'],
            'new_summary' => $_POST['summary'],
            'new_content' => $_POST['content'],
            'new_date' => $_POST['published_at'],
            'new_published' => $_POST['is_published'],
            'new_image'=> $destination,
            'up_id' => $_POST['id']
        ]
    );
    $queryupdate = $db->prepare('UPDATE article_category
                                              SET category_id = :new_category
                                              WHERE article_id = :up_id');
    $update = $queryupdate->execute(
        [
            'new_category' => $_POST['category_id'],
            'up_id' => $_POST['id']

        ]
    );
    return $results = array($update, $result);
}
//selection des catégories pour SELECT list plus bas
function getCat(){
    $db = connect();
    $queryCategory = $db ->query('SELECT * FROM category');
    $categories = $queryCategory->fetchAll();
    return $categories;
}


