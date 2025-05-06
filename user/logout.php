<?php
session_start();
session_unset();
session_destroy();
session_start(); // restart session to set the message
$_SESSION['logout_success'] = "You have been logged out successfully!";
header("Location:index");
