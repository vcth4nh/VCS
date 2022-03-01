<?php
require_once "config.php";
start_session();
check_role();
if ($_SERVER['REQUEST_METHOD'] === "POST" and isset($_POST['username']) and isset($_POST['password'])) {
    if (!empty($_POST['username']) and !empty($_POST['password'])) {
        $username = POST::username();
        $password = POST::password();

        $conn = db_conn();
        $sql = SqlQuery::get_user_info($username);
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $result = $result->fetch_array(MYSQLI_ASSOC);
            if (password_verify($password, $result['password'])) {
                start_session();
                $_SESSION['name'] = $result['fullname'];
                $_SESSION['username'] = $result['username'];
                if ($result['teacher']) {
                    $_SESSION['teacher'] = true;
                    header("Location: ./teacher.php");
                } else {
                    $_SESSION['teacher'] = false;
                    header("Location: ./student.php");
                }
            }
        } else {
            $auth['invalid'] = true;
        }
    } else {
        $auth['empty'] = true;
    }
}

function auth_result()
{
    global $auth, $ERR;
    if (isset($auth['invalid'])) {
        return $ERR['INVALID'];
    }
    if (isset($auth['empty'])) {
        return $ERR['EMPTY'];
    }
    return '';
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <title>Website with PHP</title>
</head>


<body>
<form action="index.php" method="POST">
    <?php echo auth_result(); ?>
    <label>
        Username:
        <input type="text" name="username"><br>
    </label>
    <label>
        Password:
        <input type="password" name="password"><br>
    </label>
    <input type="submit" value="Login">
</form>
</body>


</html>