<?php

require_once '../tools/common.php';

isAdmin();

if(isset($_GET['action']) AND isset($_GET['category_id']) AND $_GET['action'] == 'delete' ) {
    $query = $db->prepare('DELETE FROM category WHERE id= ?');
    $result = $query->execute(
        [
            $_GET['article_id']
        ]
    );
    header('Location: article-list.php?deletecomplete=1');
    exit();
}
if (isset($_GET['deletecomplete']) == 1){
    $message = 'Suppression effectué';
}
if (isset($_GET['updatecomplete']) == 1){
    $message = 'Modification effectué';
}
//séléctionner toutes les catégories pour affichage de la liste
$query = $db->query('SELECT * FROM category ORDER BY id DESC');
$categories = $query->fetchall();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Administration des catégories - Mon premier blog !</title>
		<?php require 'partials/head_assets.php'; ?>
	</head>
	<body class="index-body">
		<div class="container-fluid">

			<?php require 'partials/header.php'; ?>
			<div class="row my-3 index-content">
				<?php require 'partials/nav.php'; ?>
				<section class="col-9">
					<header class="pb-4 d-flex justify-content-between">
						<h4>Liste des catégories</h4>
						<a class="btn btn-primary" href="category-form.php">Ajouter une catégorie</a>
					</header>
                    <?php if(isset($message)):?>
                    <div id="btn" class="btn bg-success text-white">
                        <?= $message; ?>
                    </div>
                    <?php endif; ?>
                    <table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Description</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if($categories): ?>
							<?php foreach($categories as $category): ?>
							<tr>
								<th><?= $category['id']; ?></th>
								<td><?= $category['name']; ?></td>
								<td><?= $category['description']; ?></td>
								<td>
										<a href="category-form.php?category_id=<?php echo $category['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
										<a onclick="return confirm('Are you sure?')" href="category-list.php?category_id=<?php echo $category['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>
								</td>
							</tr>
							<?php endforeach; ?>
							<?php else: ?>
								Aucune catégorie enregistrée.
							<?php endif; ?>
						</tbody>
					</table>
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
