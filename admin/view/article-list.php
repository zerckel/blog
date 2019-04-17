
<!DOCTYPE html>
<html>
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
            <header class="pb-4 d-flex justify-content-between">
                <h4>Liste des articles</h4>
                <a class="btn btn-primary" href="../model/article-form.php">Ajouter un article</a>
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
                    <th>Titre</th>
                    <th>Publié</th>
                </tr>
                </thead>
                <tbody>
                <?php if($articles): ?>
                    <?php foreach($articles as $article): ?>
                        <tr>
                            <th><?= $article['id']; ?></th>
                            <td><?= $article['title']; ?></td>
                            <td>
                                <?php echo ($article['is_published'] == 1) ? 'Oui' : 'Non'; ?>
                            </td>
                            <td>
                                <a href="article-form.php?article_id=<?php echo $article['id']; ?>&amp;action=edit" class="btn btn-warning">Modifier</a>
                                <a onclick="return confirm('Are you sure?')" href="article-list.php?article_id=<?php echo $article['id']; ?>&amp;action=delete" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucun article enregistré.
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