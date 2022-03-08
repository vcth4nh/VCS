<?php
require_once "functions/misc.php";
start_session();
if (isset($_POST["logout"])) {
    session_destroy();
    header('Location: ./login.php');
} else {
    check_login();
    check_role();
}
