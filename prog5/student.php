<?php
require_once 'functions/misc.php';
require_once 'functions/upload.php';
require_once 'functions/manage_users.php';
require_once 'functions/received_msg.php';
require_once 'functions/exercises.php';

start_session();
is_student();

// Hiển thị avatar
function get_avatar()
{
    $result = db_query(SqlQuery::get_ava($_SESSION['uid']));
    if ($result->num_rows === 1) {
        $image_location = $result->fetch_assoc()['avatar'];
        if (!empty($image_location)) {
            $image_location = AVATAR_FOLDER . $image_location;
            echo "<img src='" . $image_location . "' alt='student's avatar' class='avatar'/>\n";
        } else echo "<p>Chưa có ảnh đại diện</p>\n";
    } else die("FATAL");
}

// Xử lí yêu cầu upload avatar
if (!empty($_POST['upload_ava']) and $_SESSION['role'] === STUDENT) {
    upload_ava();
}

// Xử lí yêu cầu chỉnh sửa thông tin cá nhân
if (isset($_POST['update_user_info'])) {
    $dbOK = false;
    $id = $_SESSION['uid'];
    $phone = POST::phone();
    $email = POST::email();
    $password = POST::password($id);
    if (!isset($validation))
        update_stu_info($id, null, $phone, $email, null, $password);
}

// Xử lí khi có yêu cầu nộp bài làm
if (!empty($_POST['upload_ans']) and $_SESSION['role'] === STUDENT) {
    $exer_id = POST::exer_id();
    if (!empty($exer_id)) {
        $GLOBALS['exer_id'] = $exer_id;
        upload_exer(SUBMIT_FOLDER);
    }
}

set_session_info();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Welcome student</title>
</head>


<body>
<ul class="nav-bar">
    <li><a href="student.php" class="active">Trang chủ</a></li>
    <li><a href="userslist.php">Danh sách người dùng</a></li>
    <li><a href="challs.php">Challenges</a></li>
    <li class="right">
        <form action="./logout.php" method="post" class="logout">
            <button type="submit" name="logout" value="logout">Đăng xuất</button>
        </form>
    </li>
    <li class="right"><p>Chào <?php echo $_SESSION['fullname'] ?></p></li>
</ul>

<div class="full-width-container row">
    <div class="column left">
        <div id='avatar'>
            <h2 class="no-margin-top">Avatar</h2>
            <?php get_avatar(); ?>
            <div style="display: inline-block; vertical-align: top;">
                <form action="" method="post" class="no-margin-bottom" enctype="multipart/form-data">
                    <p class="no-margin-bottom no-margin-top"><b>Thay ảnh đại diện mới</b></p>
                    <input type="file" name="file" id="upload-avatar">
                    <button type="submit" name="upload_ava" value="upload_ava" class="small-btn">Tải lên</button>
                </form>
                <p class="error"><?php upload_noti(AVATAR_FOLDER) ?></p>
            </div>
        </div>
        <div id='exer'>
            <h2>Bài tập</h2>
            <p class="error"><?php upload_noti(SUBMIT_FOLDER) ?></p>
            <div class="box-exer">
                <?php display_exer($_SESSION['role']); ?>
            </div>
        </div>
    </div>
    <div id="recv-msg" class="msg-box column right msg-box-long">
        <?php received_msg() ?>
    </div>

</div>
<div id='personal-info' class="full-width-container">
    <hr>
    <h2>Personal information</h2>
    <p class="error"><?php display_update_noti(); ?></p>
    <div id='personal-info-table'>
        <?php list_users(UPDATE_STUDENT, $_SESSION['uid']); ?>
    </div>
</div>

<script>
    function change_color(exer_id) {
        document.getElementById(exer_id).querySelector(':scope label').style = 'background-color: #F3C5C5;'
    }
</script>
</body>
