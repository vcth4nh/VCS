<?php

class SqlQuery
{
    static function get_student_list(): string
    {
        return 'SELECT * FROM users where not teacher';
    }

    static function get_user_info($username): string
    {
        return "SELECT fullname, username, password, teacher FROM users WHERE username='$username'";
    }

    static function update($id, $fullname, $phone, $email, $username, $password, $teacher = 'false'): string
    {
        if (empty($password))
            return "UPDATE users SET fullname='$fullname', phone='$phone', email='$email', username='$username' WHERE id=$id AND teacher=$teacher";
        return "UPDATE users SET fullname='$fullname', phone='$phone', email='$email', username='$username', password='$password' WHERE id=$id AND teacher=$teacher";
    }

    static function check_id($id): string
    {
        return "SELECT 1 FROM users WHERE id=$id";
    }

    static function check_id_student($id): string
    {
        return "SELECT 1 FROM users WHERE id=$id and not teacher";
    }

    static function check_username_with_id($id, $username): string
    {
        return "SELECT 1 FROM users WHERE username='$username' and id!=$id";
    }

    static function add_user($fullname, $phone, $email, $username, $password, $teacher = 'false'): string
    {
        return "INSERT INTO users (username, password, fullname, phone, email, teacher)
                VALUE ('$username', '$password', '$fullname', '$phone', '$email', $teacher)";
    }

    public static function id_from_username($username): string
    {
        return "SELECT id FROM users where username='$username'";
    }

    public static function check_username($username): string
    {
        return "SELECT 1 FROM users WHERE username='$username'";

    }

    public static function get_ava($username): string
    {
        return "SELECT avatar FROM users WHERE username='$username'";
    }

    public static function upload_ava($avatar_location, $username): string
    {
        return "UPDATE users SET avatar='$avatar_location' where username='$username'";
    }
}

function db_conn(): mysqli
{
    $servername = 'localhost';
    $username = 'vcth4nh';
    $password = 'vcth4nh';
    $db = 'prog5';
    $try = 0;
    do {
        $conn = new mysqli($servername, $username, $password, $db);
        $try += 1;
    } while ($conn->connect_error and $try < 3);

    if ($conn->connect_error) {
        die ("Cannot connect to database");
    }
    return $conn;
}

function exist_conn(): mysqli
{
    global $conn;
    if (isset($conn)) return $conn;
    return db_conn();

}

function is_exist($id, $username, $conn = null): int
{
    if (!$conn) $conn = db_conn();
    $err = 0;

    if (empty($id)) {
        $sql = SqlQuery::check_username($username);
        $result = $conn->query($sql);
        if ($result->num_rows !== 0)
            $err = 3;
        return $err;
    }

    $sql = SqlQuery::check_id_student($id);
    $result = $conn->query($sql);
    if ($result->num_rows === 0)
        $err = 1;
    if (empty($username)) {
        return $err;
    }

    $sql = SqlQuery::check_username_with_id($id, $username);
    $result = $conn->query($sql);
    if ($result->num_rows !== 0)
        $err = 2;
    return $err;
}
