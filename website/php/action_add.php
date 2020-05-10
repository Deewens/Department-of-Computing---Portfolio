<?php

session_start();

if(empty($_SESSION['login'])) {
    header('Location: index.php');
}

include '../database/database_connect.php';

$title = $subject = $language = $promo = $summary = "";
$dataLang = $dataPromo = "";

if(!empty($_POST['title'] AND !empty($_POST['summary']) AND !empty($_POST['subject']) AND !empty($_POST['language'])) AND !empty($_POST['promotion'])) {
    $title = test_input($_POST['title']);
    $summary = test_input($_POST['summary']);
    $subject = test_input($_POST['subject']);
    $language = test_input($_POST['language']);
    $promo = test_input($_POST['promotion']);
	
	$subject = nl2br($subject);

    $queryLang = $db->prepare('SELECT id, language FROM project_language WHERE language = ?');
    $queryLang->execute(array($language));

    $dataLang = $queryLang->fetch();
    echo $dataLang['id'] . '<br />';


    $queryPromo = $db->prepare('SELECT id, promo_name FROM promotion_type WHERE promo_name = ?');
    $queryPromo->execute(array($promo));

    $dataPromo = $queryPromo->fetch();
    echo $dataPromo['id'];

    $query = $db->prepare('INSERT INTO article(title, id_language, id_promo, summary, subject) VALUES(:title, :id_language, :id_promo, :summary, :subject)');
    $query->execute(array(
        'title' => $title,
        'id_language' => $dataLang['id'],
        'id_promo' => $dataPromo['id'],
        'summary' => $summary,
        'subject' => $subject
    ));

    $queryPromo->closeCursor();
    $queryLang->closeCursor();
    $query->closeCursor();

    header('Location: ../index.php');
}
else {
    header('Location: ../addProject.php?error=true');
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}