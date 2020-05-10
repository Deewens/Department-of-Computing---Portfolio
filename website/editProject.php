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

$query = $db->prepare('
SELECT a.id, title, pl.language, id_promo, promo_name, summary, subject 
FROM article a, project_language pl, promotion_type pt
WHERE a.id_language=pl.id
AND a.id_promo=pt.id
AND a.id=:idp');
$query->execute(array(
    'idp' => $_GET['id']
));

$result = $query->fetch();


?>

    <DOCTYPE html>
    <html>
    <head>
        <title>Projet Tutoré</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/styleadd.css" />
        <link rel="stylesheet" href="styles/wbbtheme.css" />
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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

    <h1>Edition d'un projet</h1>

    <div class="container">
        <form action="php/action_edit.php?id=<?php echo $result['id'] ?>" method="POST">
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
                    <input type="text" id="title" name="title" placeholder="Donnez un titre explicite..." value="<?php echo $result['title'] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="type">Langage</label>
                </div>
                <div class="col-75">
                    <select id="language" name="language" ?>">
                        <?php

                        while ($data = $queryLang->fetch())
                        {
                            if($result['language'] == $data['language']) {
                                echo '<option selected="selected" value="' . $data['language'] . '">' . $data['language'] . '</option>';
                            }
                            else {
                                echo '<option value="' . $data['language'] . '">' . $data['language'] . '</option>';
                            }
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
                        if($result['promo_name'] == $data['promo_name']) {
                            echo '<input type="radio" name="promotion" value="' . $data['promo_name'] . '" checked>' . $data['promo_name'] . '<br />';
                        }
                        else {
                            echo '<input type="radio" name="promotion" value="' . $data['promo_name'] . '">' . $data['promo_name'] . '<br />';
                        }
                    }

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="summary">Résumé</label>
                </div>
                <div class="col-75">
                    <textarea id="summary" name="summary" rows="4" cols="50" placeholder="Le résumé qui sera visible sur l'accueil... (ne peut pas faire plus de 400 caractères)" maxlength="400" style="height:200px; width: 40%;"><?php echo $result['summary'] ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-25">
                    <label for="subject">Description</label>
                </div>
                <div class="col-75">
                    <textarea id="editor" name="subject" placeholder="Donnez la description du projet..." style="height:500px"><?php echo $result['subject'] ?></textarea>
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Editer">
            </div>
        </form>

        <div class="w3-panel w3-yellow">
            <h3 class="w3-center">ATTENTION</h3>
            <p class="w3-center"><button class="btn danger" onclick="confirmBox()">Supprimer le projet</button></p>
        </div>
    </div>

    <script>
        function confirmBox() {
            var txt;
            var r = confirm("En cliquant sur Ok, vous supprimez l'article.\nCETTE ACTION EST IRREVERSIBLE !\n");
            if (r == true) {
                window.location = 'php/action_delete.php?id=<?php echo $_GET['id'] ?>';
            }
        }
    </script>

    </body>
    </html>

<?php

$queryPromo->closeCursor();
$queryLang->closeCursor();

?>