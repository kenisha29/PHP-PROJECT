<?php
include("config.php");
if (isset($_POST["add"])) {
    $med = $_POST["med"];
    $units = $_POST["units"];
    $res = mysqli_query($con, "SELECT * FROM `medicines` WHERE `m_id` = '$med'");
    if (mysqli_num_rows($res) == 1) {
        if (isset($_SESSION["cart"][$med])) {
            $_SESSION["cart"][$med] += $units;
        } else {
            $_SESSION["cart"][$med] = $units;
        }
        echo $units;
    } else {
        echo "Error";
    }
    exit();
}

if (isset($_POST["update"])) {
    $med = $_POST["med"];
    $units = $_POST["units"];
    $res = mysqli_query($con, "SELECT * FROM `medicines` WHERE `m_id` = '$med'");
    if (mysqli_num_rows($res) == 1) {
        $_SESSION["cart"][$med] = $units;
        echo $units;
    } else {
        echo "Error";
    }
    exit();
}

if (isset($_POST["delete"])) {
    $med = $_POST["med"];
    if (isset($_SESSION["cart"][$med])) {
        unset($_SESSION["cart"][$med]);
    }
    echo 1;
    exit();
}
