<?php require_once 'tools/common.php';
$db = connect();
//si une catégorie est demandée
if (isset($_GET['category_id'])) {
	//si l'id envoyé n'est pas un nombre entier, je redirige
	if (!ctype_digit($_GET['category_id'])) {
		header('location:index.php');
		exit;
	}
	//selection des articles de la catégorie demandée
	$queryArticles = $db->prepare('SELECT ac.*, a.title as title, a.published_at as published_at, a.summary as summary, a.id as id
	  FROM article_category ac 
	  JOIN article a 
	  ON ac.article_id = a.id
	  WHERE category_id= ? AND published_at <= NOW()
	  ORDER BY published_at DESC ');
	$queryArticles->execute( array( $_GET['category_id'] ) );

	//selection des informations de la catégorie demandée
	$queryCategory = $db->prepare('SELECT * FROM category WHERE id = ?');
	$queryCategory->execute( array( $_GET['category_id'] ) );
	$selectedCategory = $queryCategory->fetch();

	//si la catégorie n'a pas été trouvé je redirige
	if ($selectedCategory == false) {
		header('location:index.php');
		exit;
	}
}
else {
	//si pas decatégorie demandée j'affiche tous les articles
	$queryArticles = $db->query('SELECT a.*, c.name as category_name 
                                          FROM article a
                                          JOIN article_category ac 
                                          ON a.id = ac.article_id
                                          JOIN category c 
                                          ON ac.category_id = c.id
                                          WHERE is_published = 1 AND published_at <= NOW()
                                          GROUP BY a.title
                                          ORDER BY published_at DESC');
}
//puis je récupère les données selon la requête générée avant
$articles = $queryArticles->fetchAll();
?>
<!DOCTYPE html>
<html>
 <head>

	 <title><?php if(isset($_GET['category_id'])): ?><?php echo $selectedCategory['name']; ?><?php else: ?>Tous les articles<?php endif; ?> - Mon premier blog !</title>
   <?php require 'partials/head_assets.php'; ?>

 </head>
 <body class="article-list-body">
	<div class="container-fluid">

		<?php require 'partials/header.php'; ?>

		<div class="row my-3 article-list-content">

			<?php require('partials/nav.php'); ?>

			<main class="col-9">
				<section class="all_articles">
					<header>
						<?php if(isset($_GET['category_id'])): ?>
						<h1 class="mb-4"><?php echo $selectedCategory['name']; ?></h1>
						<?php else: ?>
						<h1 class="mb-4">Tous les articles :</h1>
						<?php endif; ?>
					</header>
                    <div class="category-description mb-4">
					<?php if(isset($_GET['category_id'])): ?>
					<?php echo $selectedCategory['description']; ?>
					</div>
					<?php endif; ?>
					<!-- s'il y a des articles à afficher -->
					<?php if(count($articles) > 0): ?>
						<?php foreach($articles as $key => $article): ?>
							<?php if( !isset($_GET['category_id']) OR ( isset($_GET['category_id']) AND $article['category_id'] == $_GET['category_id'] ) ): ?>
							<article class="mb-4">
								<h2><?php echo $article['title']; ?></h2>
								<?php if( !isset($_GET['category_id'])): ?>
                                <div class="article-category"><?php echo $article['category_name'] ?></div>
								<?php endif; ?>
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
							<?php endif; ?>
						<?php endforeach; ?>
					<!-- s'il n'y a pas d'articles à afficher -->
					<?php else: ?>
						aucun article
					<?php endif; ?>
				</section>
			</main>

		</div>

		<?php require 'partials/footer.php'; ?>

	</div>
 </body>
</html>
