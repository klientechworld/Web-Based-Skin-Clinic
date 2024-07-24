<?php

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}


if ($_POST) {
    //import database
    include("../connection.php");
    // $title = $_POST["title"];
    // $docid = $_POST["docid"];
    // $nop = $_POST["nop"];
    // $date = $_POST["date"];
    // $time = $_POST["time"];
    extract($_POST);
    $stmt = $database->prepare("INSERT INTO schedule(`title`,`price`,`nop`)VALUES(?,?,?) ");
    $stmt->bind_param("sii", $title, $price, $nop);
    $stmt->execute();
    if ($stmt) {
        header("location: schedule.php?action=session-added&title=$title");
    }
}
