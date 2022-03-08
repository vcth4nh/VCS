<?php
require_once 'functions/misc.php';
require_once 'functions/validate.php';
start_session();
check_role();

// Kiểm tra tài khoản và mật khẩu có đúng không
if (isset($_POST['username']) and isset($_POST['password'])) {
    if (!empty($_POST['username']) and !empty($_POST['password'])) {
        $username = POST::username();
        $password = POST::password(null, PLAIN);

        $result = db_query(SqlQuery::get_user_info($username));
        if ($result->num_rows === 1) {
            $result = $result->fetch_array(MYSQLI_ASSOC);
            if (password_verify($password, $result['password'])) {
                start_session();
                require_once 'functions/manage_users.php';
                $_SESSION['uid'] = $result['uid'];
                if ($result['teacher']) {
                    $_SESSION['role'] = TEACHER;
                    header("Location: ./teacher.php");
                } else {
                    $_SESSION['role'] = STUDENT;
                    header("Location: ./student.php");
                }
            } else $auth['invalid'] = true;
        } else $auth['invalid'] = true;
    } else $auth['empty'] = true;
}


/**
 * Hiện kết quả đăng nhập
 * @return string
 */
function auth_result(): string
{
    global $auth, $Broadcast;
    if (isset($auth['invalid'])) {
        return $Broadcast['AUTH_INVALID'];
    }
    if (isset($auth['empty'])) {
        return $Broadcast['AUTH_EMPTY'];
    }
    return '';
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        form {
            border: 3px solid #694E4E;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

    </style>
    <title>Quản lý lớp học</title>
</head>


<body>

<form class="page-centered" action="" method="post">
    <div class="container">
        <p class="error"><?php echo auth_result(); ?></p>
        <label>
            <b>Tên đăng nhập</b>
            <input type="text" name="username" placeholder="Nhập tên đăng nhập"><br>
        </label>
        <label>
            <b>Mật khẩu</b>
            <input type="password" name="password" placeholder="Nhập mật khẩu"><br>
        </label>
        <button class="btn1" type="submit" value="Login">Login</button>
    </div>
</form>
</body>


</html>