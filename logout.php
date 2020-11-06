<?php
    session_start(); // Start The Session\

    session_unset(); // unset The Data

    session_destroy(); // Destroy The Session

    header('Location: index.php');

    exit();