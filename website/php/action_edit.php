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


    $queryPromo = $db->prepare('SELECT id, promo_name FROM promotion_type WHERE promo_name = ?');
    $queryPromo->execute(array($promo));

    $dataPromo = $queryPromo->fetch();

    $query = $db->prepare('UPDATE article SET title = :title, id_language = :id_language, id_promo = :id_promo, summary = :summary, subject = :subject WHERE id = :idp');
    $query->execute(array(
        'title' => $title,
        'id_language' => $dataLang['id'],
        'id_promo' => $dataPromo['id'],
        'summary' => $summary,
        'subject' => $subject,
        'idp' => $_GET['id']
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