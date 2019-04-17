<!DOCTYPE html>
<html lang="fr">
	<head>

		<title>Administration des articles - Mon premier blog !</title>

		<?php require '../partials/head_assets.php'; ?>

	</head>
	<body class="index-body">
		<div class="container-fluid">

			<?php require '../partials/header.php'; ?>

			<div class="row my-3 index-content">

				<?php require '../partials/nav.php'; ?>

				<section class="col-9">
					<header class="pb-3">
                        <h4><?php if (isset($article)): ?> Modifier <?php else : ?> Ajouter <?php endif; ?> un Article</h4>
                    </header>

					<?php if(isset($message) AND !empty($message)): //si un message a été généré plus haut, l'afficher ?>
                    <?php foreach ($message as $key => $msg) : ?>
					<div class="btn bg-danger text-white">
						<?= $msg; ?>
					</div>
                    <?php endforeach ?>
					<?php endif; ?>

					<form action="<?php if (isset($article)): ?>article-form.php?article_id=<?php echo $_GET['article_id'];?>&action=<?php echo $_GET['action'] ?><?php else : ?>article-form.php<?php endif; ?>" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="title">Titre :</label>
							<input class="form-control" type="text" <?php if (isset($article)): ?> value="<?php echo $article[1];?>" <?php else:?>placeholder="Titre"<?php endif ?> name="title" id="title" />
						</div>
						<div class="form-group">
							<label for="summary">Résumé :</label>
							<input class="form-control" type="text" <?php if (isset($article)): ?> value="<?php echo $article[4];?>" <?php else:?>placeholder="résumé"<?php endif; ?> name="summary" id="summary" />
						</div>
						<div class="form-group">
							<label for="content">Contenu :</label>
                            <?php if (!isset($article)): ?>
							<textarea class="form-control" name="content" id="content"  placeholder="contenu" ></textarea>
                            <?php else: ?>
                            <textarea class="form-control" name="content" id="content"><?php echo $article[3];?></textarea>
                            <?php endif  ?>
						</div>

						<div class="form-group">
							<label for="published_at">Date de publication :</label>
							<input class="form-control" type="date" value="<?php if (isset($article)){echo $article[2];} ?>" name="published_at" id="published_at" />
						</div>

						<div class="form-group">
							<label for="category_id"> Catégorie :</label>
							<select class="form-control" name="category_id" id="categories">
								<?php foreach($categories as $key => $category) : ?>
									<option value="<?php echo $category['id']; ?>"> <?php echo $category['name']; ?> </option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="is_published"> Publié ?</label>
							<select class="form-control" name="is_published" id="is_published">
								<option value="0">Non</option>
								<option value="1">Oui</option>
							</select>
						</div>

                        <div class="form-group">
                            <input type="file" name="my_file" id="my_file" value="">
                        </div>

                        <div class="text-right">
                            <?php if(isset($article)): ?>
                                <input class="btn btn-success" type="submit" name="update" value="Modifier">
                            <?php else: ?>
                                <input class="btn btn-success" type="submit" name="save" value="Enregistrer">
                            <?php endif; ?>
                        </div>
                        <?php if (isset($article)):?>
                            <input style="display: none;" value="<?php echo $article['id']; ?>" name="id" id="id">
                            <input style="display: none;" value="<?php echo $article['image']; ?>" name="pics" id="pics">
                        <?php endif; ?>
					</form>

				</section>
			</div>
            <script>
                function hidden() {
                    let btn = document.getElementById('btn')
                    if (btn !== null) {
                        btn.style.display = "none"

                    }
                }
                setTimeout(hidden,3000)
            </script>
		</div>

  </body>
</html>