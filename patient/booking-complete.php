<?php

//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}


//import database
include("../connection.php");
$userrow = $database->query("select * from patient where pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];


// if ($_POST) {
//     if (isset($_POST["booknow"])) {
//         $apponum = $_POST["apponum"];
//         $scheduleid = $_POST["scheduleid"];
//         $date = $_POST["date"];
//         $scheduleid = $_POST["scheduleid"];
//         $sql2 = "insert into appointment(pid,apponum,scheduleid,appodate) values ($userid,$apponum,$scheduleid,'$date')";
//         $result = $database->query($sql2);
//         //echo $apponom;
//         header("location: appointment.php?action=booking-added&id=" . $apponum . "&titleget=none");
//     }
// }

if (isset($_POST['shedulesubmit'])) {
    extract($_POST);

    $stmt = $database->prepare("SELECT COUNT(*) as total FROM appointment WHERE scheduleid = ?");
    $stmt->bind_param('i', $bookingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $totalAppointments = $data['total'];

    $reference_no = sprintf("OC-%03d-%02d", $bookingID, $totalAppointments + 1);

    $insertStmt = $database->prepare("INSERT INTO appointment (reference, pid, doctor_id, scheduleid, appodate, time_selected) VALUES (?, ?,?,  ?, ?, ?)");
    $insertStmt->bind_param('siiiss', $reference_no, $userid, $doctor_id, $bookingID, $date, $timeInput);
    $insertStmt->execute();

    if ($insertStmt) {
        header("Location: appointment.php?action=booking-added&id=" . $bookingID . "&titleget=none");
        exit();
    } else {
        echo "Error: Could not add the appointment.";
    }
}
