<?php
const HASH = 1;
const PLAIN = 2;
class POST
{
    static function id()
    {
        return check_id($_POST['id']);
    }

    static function username(): ?string
    {
        return check_username($_POST['username']);
    }

    static function password($id = null, $option = PLAIN)
    {
        $password = $_POST['password'];
        if ($option === HASH)
            return check_password($id, $password);
        elseif ($option === PLAIN)
            return $password;
        else die("FATAL");

    }

    static function fullname(): string
    {
        return check_name($_POST['fullname']);
    }

    static function phone(): string
    {
        return check_phone($_POST['phone']);
    }

    static function email(): ?string
    {
        return check_email($_POST['email']);
    }
}

function validate($string): string
{

    return mysqli_real_escape_string(exist_conn(), trim($string));
}

function show_notice()
{
    global $ERR, $db, $validation, $added_teacher;
    $err = '';
    foreach ($db as $field => $value) {
        $err .= $ERR["DB_$field"];
    }
    foreach ($validation as $field => $value) {
        $err .= $ERR["VAL_$field"];
    }
    if ($added_teacher) echo "Added new teacher<br>";
    elseif ($added_teacher === false) echo "Failed to add new teacher<br>";

    echo substr($err, 0, -2) . '<br>';
}

function check_id($id)
{
    if ($id === 'new_student') {
        return -1;
    } elseif ($id === 'new_teacher') {
        return -2;
    }

    if (is_numeric($id)) {
        return $id;
    } else {
        global $validation;
        $validation['id'] = true;
        return null;
    }
}

function check_name($name): string
{
    return validate($name);
}


function check_phone($phone): ?string
{
    if (empty($phone)) return $phone;

    if (is_numeric($phone) and 5 <= strlen($phone) and strlen($phone) <= 15) {
        return validate($phone);
    } else {
        global $validation;
        $validation['phone'] = true;
        return null;
    }
}

function check_email($email): ?string
{
    if (empty($email)) return $email;

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return validate($email);
    } else {
        global $validation;
        $validation['email'] = true;
        return null;
    }
}

function check_username($username): ?string
{
    if (preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $username)) {
        return validate($username);
    } else {
        global $validation;
        $validation['username'] = true;
        return null;
    }
}

function check_password($id, $password)
{
    if (empty($password)) {
        if ($id === -1 or $id === -2) {
            global $validation;
            $validation['password'] = true;
            return null;
        } else return $password;
    }
    return password_hash($password, PASSWORD_DEFAULT);

}

function all_valid(): bool
{
    $params = func_get_args();
    foreach ($params as $each_param) {
        if (empty($each_param)) {
            return false;
        }
    }
    return true;
}
