<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=projet_tutore2', 'root', '');
} catch (PDOException $e) {
    print "Error : " . $e->getMessage() . "<br />";
    die();
}