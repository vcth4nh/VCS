<?php
require_once 'functions/misc.php';
require_once 'functions/database.php';
require_once 'functions/upload.php';
require_once 'functions/validate.php';

start_session();
check_login();

// Xử lí yêu cầu đăng challenge mới
if (isset($_POST['upload_chall']) and $_SESSION['role'] === TEACHER) {
    $hint = POST::chall_hint();
    if ($hint !== null)
        upload_chall($hint);
    else {
        global $upload_err, $uploaded_to;
        $uploaded_to = CHALLS_FOLDER;
        $upload_err['no_hint'] = true;
    }
}

// Xử lí yêu cầu kiểm tra đáp án của challenge
if (isset($_POST['upload_ans'])) {
    $ans = POST::chall_ans();
    $chall_id = POST::chall_id();
    $chall_id_ = $chall_id . '_';
    $file_location = CHALLS_FOLDER . $chall_id_ . $ans . '.txt';
    if ($ans && $chall_id && file_exists($file_location)) {
        $file_content = file_get_contents($file_location);
    }
}

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Challenges</title>
</head>


<body>
<ul class="nav-bar">
    <li><a href="teacher.php">Trang chủ</a></li>
    <li><a href="userslist.php">Danh sách người dùng</a></li>
    <li><a href="challs.php" class="active">Challenges</a></li>
    <li class="right">
        <form action="./logout.php" method="post" class="logout">
            <button type="submit" name="logout" value="logout">Đăng xuất</button>
        </form>
    </li>
    <li class="right"><p>Chào <?php echo $_SESSION['fullname'] ?></p></li>
</ul>

<!-- Thông báo câu trả lời của người dùng đúng hay sai -->
<?php
if (isset($file_location)) {
    if (!isset($file_content))
        echo "<p class='error'>Trả lời câu {$_POST['i']} sai</p>";
    else echo "<p class='success'>Trả lời câu {$_POST['i']} đúng</p>";
}
?>

<!-- Nếu là giáo viên thì được phép đăng challenge mới -->
<?php if ($_SESSION['role'] === TEACHER): ?>
    <p class="error"><?php upload_noti(CHALLS_FOLDER) ?></p>
    <form action="" method="post" enctype="multipart/form-data">
        <p class="no-margin-bottom"><b>Đăng challenge mới</b></p>
        <input type="file" name="file" id="upload-chall"><br>
        <label>
            <textarea spellcheck="false" name="chall_hint" placeholder="Nhập hint cho challenge"></textarea>
        </label><br>
        <button type="submit" name="upload_chall" value="upload_chall" class="small-btn">Đăng</button>
    </form>
<?php endif; ?>

<!-- Lấy thông tin các challenge -->
<?php
$result = db_query(SqlQuery::get_chall_list);
if ($result->num_rows === 0) {
    echo "<p class='error page-centered big-font'>Không có challenge</p>\n";
    echo "</body>";
    die();
}
$result = $result->fetch_all(MYSQLI_ASSOC);
$i = 1;
?>

<!-- In các challenges-->
<?php foreach ($result as $row): ?>
    <hr>
    <h4>Challenge <?= $i; ?>:</h4>
    <label>
        <span><?= $row['hint'] ?></span><br>
        <span class="small-font">Đăng lúc <?= date_format(date_create($row['post_time']), 'G:i \n\g\à\y d/m/Y'); ?></span>
        <?php if (empty($file_content) || $chall_id != $row['chall_id']): ?>
            <form action="" method="post">
                <input name="chall_id" type="hidden" value="<?= $row['chall_id'] ?>">
                <textarea class="long-input" placeholder="Nhập đáp án" name="chall_ans"></textarea><br>
                <input type="hidden" name="i" value="<?= $i++ ?>">
                <button type="submit" name="upload_ans" value="upload_ans" class="small-btn">Gửi</button>
            </form>
        <?php else: ?>
            <p id="here" class="success chall-content"><?= $file_content ?></p>
        <?php endif; ?>
    </label>

<?php endforeach; ?>
<script>
    document.getElementById('here').scrollIntoView();
</script>
</body>