<?php
require_once "validate.php";
require_once "database.php";
function start_session(): string
{
    if (session_id() === '') {
        session_start();
    }
    return session_id();

}

function check_role()
{
    if (isset($_SESSION['teacher'])) {
        if ($_SESSION['teacher']) {
            header('Location: ./teacher.php');
        } else {
            header('Location: ./student.php');
        }
    } else {
        session_destroy();
    }
}

function check_session()
{
    check_role();
    header('Location: ./index.php');
}

function is_student()
{
    if (isset($_SESSION['teacher'])) {
        if ($_SESSION['teacher']) {
            header('Location: ./teacher.php');
        }
    } else header('Location: ./index.php');
}

function is_teacher()
{
    if (isset($_SESSION['teacher'])) {
        if (!$_SESSION['teacher']) {
            header('Location: ./student.php');
        }
    } else header('Location: ./index.php');
}

//function valid($param): string
//{
//    if (empty($param))
//        return true;
//    return false;
//}


$ERR['EMPTY'] = "Username and password are required<br>";
$ERR['INVALID'] = "Username or password is invalid<br>";
$ERR['QUERY'] = "Update failed<br>";
$ERR['DB_duplicated'] = "Username already exist<br>";
$ERR['DB_id'] = "";
$ERR['VAL_id'] = "";
$ERR['VAL_phone'] = "Phone number has 5-15 digits<br>";
$ERR['VAL_email'] = "Wrong email format<br>";
$ERR['VAL_username'] = "Username must start with letter, has 6-32 letters and numbers only<br>";
$ERR['VAL_password'] = "Password cannot be empty<br>";