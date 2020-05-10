<?php

session_start();

if (empty($_SESSION['login'])) {
    header('Location: index.php');
}

include '../database/database_connect.php';

$title = $subject = $language = $promo = $summary = "";
$dataLang = $dataPromo = "";


$query = $db->prepare('DELETE FROM article WHERE id=:idp');
$query->execute(array(
    'idp' => $_GET['id']
));

$query->closeCursor();

header('Location: ../index.php');