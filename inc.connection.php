<?php
ob_start(); // Start output buffering
session_start();
include('inc.functions.php');

// Database connection
$hostname = 'localhost';
$username = 'root'; 
$password = '';
$database = 'MyWeb17Jun2025_db';

// Create connection
if($conn = mysqli_connect($hostname, $username, $password, $database)) {
    echo "$database connected successfully";
}