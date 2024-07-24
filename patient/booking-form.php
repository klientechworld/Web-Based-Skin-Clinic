<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">

    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
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


    //echo $userid;
    //echo $username;

    date_default_timezone_set('Asia/Kolkata');

    $today = date('Y-m-d');


    //echo $userid;
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home ">
                        <a href="index.php" class="non-style-link-menu ">
                            <div>
                                <p class="menu-text">Home</p>
                        </a>
        </div></a>
        </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor">
                <a href="doctors.php" class="non-style-link-menu">
                    <div>
                        <p class="menu-text">All Doctors</p>
                </a>
    </div>
    </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
            <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active">
                <div>
                    <p class="menu-text">Scheduled Sessions</p>
                </div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment">
            <a href="appointment.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">My Bookings</p>
            </a></div>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Settings</p>
            </a></div>
        </td>
    </tr>

    </table>
    </div>


    <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
            <tr>
                <td width="13%">
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                            <font class="tn-in-text">Back</font>
                        </button></a>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                        Today's Date
                    </p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php
                        echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>


            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div id="popup1" class="overlay">
                            <div class="popup">
                                <center>
                                    <a class="close" href="schedule.php">&times;</a>
                                    <?php
                                    $appID = $_GET['bookingid'];
                                    $currentDate = date('Y-m-d');

                                    $sched = $database->prepare("SELECT s.* FROM schedule s WHERE s.scheduleid = ?");
                                    $sched->bind_param('i', $appID);
                                    $sched->execute();
                                    $res = $sched->get_result();
                                    $data = $res->fetch_assoc();

                                    $title = $data['title'];
                                    $price = $data['price'];
                                    $nop = $data['nop'];

                                    $countAppointments = $database->prepare("SELECT COUNT(a.scheduleid) AS 
                                    TOTAL_TODAY FROM appointment a WHERE a.scheduleid = ? ");
                                    $countAppointments->bind_param('i', $appID);
                                    $countAppointments->execute();
                                    $resCount = $countAppointments->get_result();
                                    $countData = $resCount->fetch_assoc();

                                    $totalToday = $countData['TOTAL_TODAY'];

                                    $remainingPatients = $nop - $totalToday;
                                    ?>
                                    <div style="display: flex;justify-content: center;">
                                        <div class="abc">
                                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;"><?= $title ?></p><br>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="label-td" colspan="2">
                                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Price: <?= $price ?></p><br>
                                                    </td>
                                                </tr>

                                                <form action="booking-complete.php" method="POST">
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input type="hidden" name="bookingID" value="<?= $_GET['bookingid']; ?>">
                                                            <label for="docid" class="form-label">Select a Doctor </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <select class="input-text form-select" name="doctor_id" id="">
                                                                <option value="">Select here...</option>
                                                                <?php
                                                                $stmt = $database->prepare("SELECT * FROM doctor");
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                foreach ($result as $row) {
                                                                    echo "<option value=" . $row['docid'] . ">" . $row['docname'] . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="docid" class="form-label">Select Date </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input class="input-text" id="dateInput" name="date" type="date">
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="docid" class="form-label">Schedule Time(10:00 AM - 5:30 PM) </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input class="input-text" id="timeInput" name="timeInput" type="time">
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <label for="nop" class="form-label">Number of Patients/Appointment Numbers : </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label-td" colspan="2">
                                                            <input disabled type="text" class="input-text" value="<?php echo $totalToday . ' out of ' . $nop; ?>"><br>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <?php if ($totalToday === $nop) : ?>
                                                                <p>Total Appointment is Out of Order</p>
                                                            <?php else : ?>
                                                                <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </form>
                                            </table>
                                        </div>
                                    </div>

                                </center>
                                <br><br>
                            </div>
                        </div>
                    </center>
                </td>
            </tr>

        </table>
    </div>
    </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var dateInput = document.getElementById("dateInput");
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });
    </script>
</body>

</html>