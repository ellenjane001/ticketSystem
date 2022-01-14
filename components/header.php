<?php
session_start();
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Montserrat:wght@300&family=Raleway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/tableStyle.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Help Desk</title>
</head>

<body>
    <div class="main-container">
        <input type="hidden" name="dateNtime" id="dateNTime" value="<?= date('Y-m-d H:i') ?>">