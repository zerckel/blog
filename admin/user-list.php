<?php

require_once '../tools/common.php';

isAdmin();

if(isset($_GET['action']) AND isset($_GET['user_id']) AND $_GET['action'] == 'delete' ) {
    $query = $db->prepare('DELETE FROM user WHERE id= ?');
    $result = $query->execute(
        [
            $_GET['user_id']
        ]
    );
    header('Location: user-list.php?deletecomplete=1');
    exit();
}
if (isset($_GET['deletecomplete']) == 1){
    $message = 'Suppression effectué';
}
if (isset($_GET['updatecomplete']) == 1){
    $message = 'Modification effectué';
}
//séléctionner tous les utilisateurs pour affichage de la liste
$query = $db->query('SELECT * FROM user ORDER BY id DESC');
$users = $query->fetchall();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Administration des utilisateurs - Mon premier blog !</title>
		<?php require 'partials/head_assets.php'; ?>
	</head>
	<body class="index-body">
		<div class="container-fluid">

			<?php require 'partials/header.php'; ?>

			<div class="row my-3 index-content">

				<?php require 'partials/nav.php'; ?>

				<section class="col-9">
					<header class="pb-4 d-flex justify-content-between">
						<h4>Liste des utilisateurs</h4>
						<a class="btn btn-primary" href="user-form.php">Ajouter un utilisateur</a>
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
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Admin</th>
							</tr>
						</thead>
						<tbody>

							<?php if($users): ?>
							<?php foreach($users as $user): ?>

							<tr>
								<th><?= $user['id']; ?></th>
								<td><?= $user['firstname']; ?></td>
								<td><?= $user['lastname']; ?></td>
								<td><?= $user['email']; ?></td>
								<td><?= $user['is_admin']; ?></td>
                                <td>
                                    <a href="user-form.php?user_id=<?php echo $user['id']; ?>&amp;action=edit" class="btn btn-warning">Modifier</a>
                                    <a onclick="return confirm('Are you sure?')" href="user-list.php?user_id=<?php echo $user['id']; ?>&amp;action=delete" class="btn btn-danger">Supprimer</a>
                                </td>
							</tr>

							<?php endforeach; ?>
							<?php else: ?>
								Aucun utilisateur enregistré.
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
