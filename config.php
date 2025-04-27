<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["cart"])) {
  $_SESSION["cart"] = array();
}
$con = mysqli_connect("localhost", "root", "", "omd");

define("WEBSITE_SHORT_NAME", "OMD");

function post_string($key, $default = "")
{
  if (isset($_POST[$key]) && is_string($_POST[$key])) {
    return strval($_POST[$key]);
  } else {
    return $default;
  }
}

function post_number($key, $default = "")
{
  if (isset($_POST[$key]) && is_string($_POST[$key])) {
    return intval($_POST[$key]);
  } else {
    return $default;
  }
}
function post_float($key, $default = "")
{
  if (isset($_POST[$key]) && is_string($_POST[$key])) {
    return number_format(floatval($_POST[$key]), 2);
  } else {
    return $default;
  }
}

function get_number($key, $default = "")
{
  if (isset($_GET[$key]) && is_string($_GET[$key])) {
    return intval($_GET[$key]);
  } else {
    return $default;
  }
}

function print_not_empty($test, $default = "default.jpg")
{
  if (!empty($test)) {
    echo  $test;
  } else {
    echo  $default;
  }
}

function print_selected($t1, $t2)
{
  if ($t1 == $t2) {
    echo " selected ";
  }
}

function print_active($t1, $t2)
{
  if ($t1 == $t2) {
    echo " active ";
  }
}

$order_status_list = array(
  "0"=>"Select Status",
  "1"=>"Pending",
  "2"=>"Accepted",
  "3"=>"Out of Delivery",
  "4"=>"Completed",
  "5"=>"Order Cancellation",
);