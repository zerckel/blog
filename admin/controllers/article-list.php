<?php

require_once '../../tools/common.php';
isAdmin();
require('../model/article-list.php');

//suppression article
$article_id = isset($_GET['action']) AND isset($_GET['article_id']) AND $_GET['action'] == 'delete' ? $_GET['article_id'] : false;
$message = delete($article_id);
//

require('../view/article-list.php');

