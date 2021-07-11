<?php
require_once('dbconnect.php');

$request_type = $_SERVER["REQUEST_METHOD"];

if ($request_type === 'POST') { print("POST"); }
if ($request_type === 'GET') { print("GET"); }
if ($request_type === 'PUT') { print("PUT"); }


?>
