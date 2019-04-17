<?php
require_once '../tools/common.php';

isAdmin();

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'utilisateur
if(isset($_POST['save'])){

    $query = $db->prepare('INSERT INTO user (firstname, lastname, password, email, is_admin, bio) VALUES (?, ?, ?, ?, ?, ?)');
    $newUser = $query->execute([
			htmlspecialchars($_POST['firstname']),
			htmlspecialchars($_POST['lastname']),
			hash('md5', $_POST['password']),
			htmlspecialchars($_POST['email']),
			$_POST['is_admin'],
			htmlspecialchars($_POST['bio']),
		]);

	//redirection après enregistrement
	//si $newUser alors l'enregistrement a fonctionné
	if($newUser){
		header('location:user-list.php');
		exit;
    }
	else{ //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
		$message = "Impossible d'enregistrer le nouvel utilisateur...";
	}
}
if (isset($_GET['action']) AND isset($_GET['user_id']) AND $_GET['action'] == 'edit') {
    $query = $db->prepare('SELECT * FROM user WHERE id = ?');
    $query->execute(
        [
            $_GET['user_id']
        ]
    );
    $user = $query->fetch();
}
if (isset($_POST['update'])){
    $queryupdate = $db->prepare('UPDATE user 
                                              SET firstname = :new_firstname, lastname = :new_lastname, email = :new_email, password = :new_password, is_admin = :new_admin, bio = :new_bio
                                              WHERE id = :up_id');
    $result = $queryupdate->execute(
        [
            'new_firstname' => $_POST['firstname'],
            'new_lastname' => $_POST['lastname'],
            'new_email' => $_POST['email'],
            'new_password' => md5($_POST['password']),
            'new_admin' => $_POST['is_admin'],
            'new_bio' => $_POST['bio'],
            'up_id' => $_POST['id']
        ]
    );

    if ($result) {
        header('Location: user-list.php?updatecomplete=1');
        exit();
    } else {
        $message = "t'es une merde";
    }

}
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
					<header class="pb-3">
                        <h4><?php if (isset($user)): ?> Modifier <?php else : ?> Ajouter <?php endif; ?> un Utilisateur</h4>
					</header>

					<?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
					<div class="bg-danger text-white">
						<?= $message; ?>
					</div>
					<?php endif; ?>

					<form action="user-form.php" method="post">
						<div class="form-group">
							<label for="firstname">Prénom :</label>
							<input class="form-control"  type="text"  id="firstname" name="firstname" <?php if (isset($user)): ?> value="<?php echo $user['firstname'];?>" <?php else:?>placeholder="Prénom"<?php endif; ?> />
						</div>
						<div class="form-group">
							<label for="lastname">Nom de famille : </label>
							<input class="form-control"  type="text" <?php if (isset($user)): ?> value="<?php echo $user['lastname'];?>" <?php else:?>placeholder="Nom de famille"<?php endif; ?> name="lastname" id="lastname" />
						</div>
						<div class="form-group">
							<label for="email">Email :</label>
							<input class="form-control"  type="email" <?php if (isset($user)): ?> value="<?php echo $user['email'];?>" <?php else:?>placeholder="email"<?php endif; ?> name="email" id="email" />
						</div>
						<div class="form-group">
							<label for="password">Password : </label>
							<input class="form-control" type="password" placeholder="Mot de passe" name="password" id="password" />
						</div>
						<div class="form-group">
							<label for="bio">Biographie :</label>
                            <?php if (!isset($user)): ?>
                                <textarea class="form-control" name="bio" id="bio"  placeholder="Sa vie son oeuvre..." ></textarea>
                            <?php else: ?>
                                <textarea class="form-control" name="bio" id="bio"><?php echo $user['bio'];?></textarea>
                            <?php endif  ?>
						</div>
						<div class="form-group">
							<label for="is_admin"> Admin ?</label>
							<select class="form-control" name="is_admin" id="is_admin">
								<option value="0">Non</option>
								<option value="1">Oui</option>
							</select>
						</div>
						<div class="text-right">
                            <?php if(isset($user)): ?>
                                <input class="btn btn-success" type="submit" name="update" value="Modifier">
                            <?php else: ?>
                                <input class="btn btn-success" type="submit" name="save" value="Enregistrer">
                            <?php endif; ?>
                        </div>
                        <?php if (isset($user)):?>
                            <input style="display: none;" value="<?php echo $user['id']; ?>" name="id" id="id">
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
