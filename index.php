<?php

require_once 'tools/common.php';
$db = connect();
//si un utilisateur est connécté et que l'on reçoit le paramètre "lougout" via URL, on le déconnecte
if(isset($_GET['logout']) && isset($_SESSION['user'])){
	//la fonction unset() détruit une variable ou une partie de tableau. ici on détruit la session user
	//unset($_SESSION["user"]);
	session_destroy();

}
//selection des 3 derniers articles PUBLIés ET dont la publish_date est inférieure ou égale à la date du jour
$query = $db->query('SELECT *
	FROM article
	WHERE published_at <= NOW() AND is_published = 1
	ORDER BY published_at DESC
	LIMIT 3');
$homeArticles=$query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Homepage - Mon premier blog !</title>
		<?php require 'partials/head_assets.php'; ?>
	</head>
	<body class="index-body">
		<div class="container-fluid">

			<?php require 'partials/header.php'; ?>

			<div class="row my-3 index-content">

				<?php require 'partials/nav.php'; ?>

				<main class="col-9">
					<section class="latest_articles">
						<header class="mb-4"><h1>Les 3 derniers articles :</h1></header>

						<!-- les trois derniers articles -->

						<?php foreach($homeArticles as $key => $article): ?>
						<article class="mb-4">
							<h2><?php echo $article['title']; ?></h2>
							<span class="article-date">
								<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
								<?php echo strftime("%A %e %B %Y", strtotime($article['published_at'])); ?>
							</span>
							<div class="article-content">
								<?php echo $article['summary']; ?>
							</div>
                            <?php if(!empty($article['image'])) : ?>
                            <div class="col-12 col-md-4 col-lg-3">
                                <img class="img-fluid" src="admin/<?= $article['image'] ?>" alt="#">
                            </div>
                            <?php endif ?>
							<a href="article.php?article_id=<?php echo $article['id']; ?>">> Lire l'article</a>
						</article>
						<?php endforeach; ?>

					</section>
					<div class="text-right">
						<a href="article_list.php">> Tous les articles</a>
					</div>
				</main>
			</div>

			<?php require 'partials/footer.php'; ?>

		</div>
	</body>
</html>
