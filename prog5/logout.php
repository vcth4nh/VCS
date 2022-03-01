<?php
require_once "config.php";
start_session();
if (isset($_POST["logout"])) {
    session_destroy();
    header('Location: ./index.php');
} else check_session();