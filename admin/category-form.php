<?php
require_once '../tools/common.php';

isAdmin();

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'une catégorie
if(isset($_POST['save'])){
    $query = $db->prepare('INSERT INTO category (name, description) VALUES (?, ?)');
    $newCategory = $query->execute([
		htmlspecialchars($_POST['name']),
		htmlspecialchars($_POST['description'])
	]);

    if($newCategory){
        header('location:category-list.php');
        exit;
    }
    else {
        $message = "Impossible d'enregistrer la nouvelle categorie...";
    }
}
if (isset($_GET['action']) AND isset($_GET['category_id']) AND $_GET['action'] == 'edit') {
    $query = $db->prepare('SELECT * FROM category WHERE id = ?');
    $query->execute(
        [
            $_GET['category_id']
        ]
    );
    $category = $query->fetch();
}
if (isset($_POST['update'])){
    $queryupdate = $db->prepare('UPDATE category
                                              SET name = :new_name, description = :new_description 
                                              WHERE id = :up_id');
    $result = $queryupdate->execute(
        [
            'new_name' => $_POST['name'],
            'new_description' => $_POST['description'],
            'up_id' => $_POST['id']
        ]
    );
    if ($result) {
        header('Location: category-list.php?updatecomplete=1');
        exit();
    } else {
        $message = "T'es une merde";
    }

}
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
					<header class="pb-3">
                        <h4><?php if (isset($article)): ?> Modifier <?php else : ?> Ajouter <?php endif; ?> une Catégorie</h4>
					</header>

					<?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
					<div class="btn bg-danger text-white">
						<?= $message; ?>
					</div>
					<?php endif; ?>

					<form action="category-form.php" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name">Nom :</label>
							<input class="form-control"  type="text" <?php if (isset($category)): ?> value="<?php echo $category['name'];?>" <?php else:?>placeholder="Nom"<?php endif; ?> name="name" id="name" />
						</div>
						<div class="form-group">
							<label for="description">Description : </label>
							<input class="form-control" type="text" <?php if (isset($category)): ?> value="<?php echo $category['description'];?>" <?php else:?>placeholder="Description"<?php endif; ?> name="description" id="description" />
						</div>
						<div class="text-right">
                            <div class="text-right">
                                <?php if(isset($category)): ?>
                                    <input class="btn btn-success" type="submit" name="update" value="Modifier">
                                <?php else: ?>
                                    <input class="btn btn-success" type="submit" name="save" value="Enregistrer">
                                <?php endif; ?>
                            </div>
                            <?php if (isset($category)):?>
                                <input style="display: none;" value="<?php echo $category['id']; ?>" name="id" id="id">
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
