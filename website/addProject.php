<?php

session_start();

if(empty($_SESSION['login'])) {
    header('Location: index.php');
}

include 'database/database_connect.php';

$queryLang = $db->prepare('SELECT id, language FROM project_language ORDER BY language');
$queryLang->execute();

$queryPromo = $db->prepare('SELECT id, promo_name FROM promotion_type');
$queryPromo->execute();


?>

<DOCTYPE html>
<html>
<head>
    <title>Projet Tutoré</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/styleadd.css" />
    <link rel="stylesheet" href="styles/wbbtheme.css" />
    <script src="js/jquery.js"></script>
    <script src="js/jquery.wysibb.min.js"></script>
    <script>
        $(function() {
            var options = {
                buttons: "bold,italic,underline,|,img,|,fontsize"
            }
            $("#editor").wysibb(options);
        })
    </script>
</head>
<body>

<h1>Ajout d'un projet</h1>

<div class="container">
    <form action="php/action_add.php" method="POST">
        <?php
        if(!empty($_GET['error'])) {
            $error = $_GET['error'];
            if($error) {
                echo 'Vous devez remplir tout les champs !';
            }
        }
        ?>
        <div class="row">
            <div class="col-25">
                <label for="title">Titre du projet</label>
            </div>
            <div class="col-75">
                <input type="text" id="title" name="title" placeholder="Donnez un titre explicite...">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="type">Langage</label>
            </div>
            <div class="col-75">
                <select id="language" name="language">
                    <?php

                        while ($data = $queryLang->fetch())
                        {
                            echo '<option value="'. $data['language'] . '">' . $data['language'] . '</option>';
                        }

                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="promotion">Promotion</label>
            </div>
            <div class="col-75">
                <?php

                while($data = $queryPromo->fetch()) {
                    echo '<input type="radio" name="promotion" value="' . $data['promo_name'] . '" checked>' . $data['promo_name'] .'<br />';
                }

                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="summary">Résumé</label>
            </div>
            <div class="col-75">
                <textarea id="summary" name="summary" rows="4" cols="50" placeholder="Le résumé qui sera visible sur l'accueil... (ne peut pas faire plus de 400 caractères)" maxlength="400" style="height:200px; width: 40%;"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="subject">Description</label>
            </div>
            <div class="col-75">
                <textarea id="editor" name="subject" placeholder="Donnez la description du projet..." style="height:500px"></textarea>
            </div>
        </div>
        <div class="row">
            <input type="submit" value="Ajouter">
        </div>
    </form>
</div>

</body>
</html>

<?php

$queryPromo->closeCursor();
$queryLang->closeCursor();

?>