<?php
require_once 'functions/misc.php';
require_once 'functions/manage_users.php';

start_session();
check_login();

// Xử lí yêu cầu gửi tin nhắn
if (!empty($_POST['recv_id']) && !empty($_POST['send_msg'])) {
    global $msg, $not_exist;
    $msg = validate(($_POST['send_msg']));
    $send_id = $_SESSION['uid'];
    $recv_id = POST::uid('recv_id');
    global $dbOK;
    $dbOK = false;
    if ($recv_id !== $send_id && $not_exist !== true) {
        $dbOK = db_query(SqlQuery::send_msg($send_id, $recv_id, $msg));
    }
}

$msg_history = '';
if (!empty($_POST['view_history']) && !empty($_POST['recv_id'])) {
    $send_id = $_SESSION['uid'];
    $recv_id = POST::uid('recv_id');
    if ($recv_id !== $send_id && $recv_id !== null) {
        global $empty_history;
        $empty_history = true;
        $result = db_query(SqlQuery::msg_history($send_id, $recv_id));
        if ($result->num_rows > 0) {
            $empty_history = false;
            foreach ($result as $row) {
                $msg_history .= "<div class='full-width-container'>\n";
                $msg_history .= "<hr>";
                $msg_history .= "<h4>Gửi lúc {$row['recv_time']}</h4>\n";
                $text = htmlspecialchars($row['text'], ENT_QUOTES);
                $msg_history .= "<form method='post' action=''>\n" .
                    "<textarea name='send_msg' spellcheck='false'>$text</textarea><br>\n" .
                    "<input type='hidden' name='recv_id' value='$recv_id'>\n" .
                    "<button type='submit' class='small-btn'>Gửi</button>\n" .
                    "<button type='submit' class='small-btn delete' name='delete_msg' value='delete_msg' form='delete-msg'>Xóa</button>" .
                    "</form>\n" .
                    "<input type='hidden' name='msg_id' value='{$row['msg_id']}' form='delete-msg'>" .
                    "<form id='delete-msg' action='' method='post'></form>";
                $msg_history .= "</div>\n";
            }
        }
    }
}

if (isset($_POST['delete_msg'])) {
    $msg_id = POST::msg_id();
    global $dbOK;
    $dbOK = false;
    if ($msg_id !== null) {
        $dbOK = db_query(SqlQuery::delete_msg($msg_id));
    }
}

function msg_result()
{
    global $dbOK, $empty_history, $msg;
    echo match ($dbOK) {
        true => "<p class='success'>Thực thi thành công</p>\n",
        false => "<p class='error'>Thực thi thất bại, vui lòng thử lại<br>Tin nhắn đã gửi:<br></p>\n" .
            "<pre class='error'>$msg</pre>\n",
        default => ''
    };
    if ($empty_history === true) {
        echo "<p class='error'>Lịch sử trống</p>";
    }
}

set_session_info();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Danh sách người dùng</title>
</head>


<body>
<ul class="nav-bar">
    <li><a href="teacher.php">Trang chủ</a></li>
    <li><a href="userslist.php" class="active">Danh sách người dùng</a></li>
    <li><a href="challs.php">Challenges</a></li>
    <li class="right">
        <form action="./logout.php" method="post" class="logout">
            <button type="submit" name="logout" value="logout">Đăng xuất</button>
        </form>
    </li>
    <li class="right"><p>Chào <?php echo $_SESSION['fullname'] ?></p></li>
</ul>

<p><?php msg_result() ?></p>

<div id="list-all-users">
    <div id="list-all-student" class="full-width-container">
        <h2>Danh sách học sinh</h2>
        <?php list_users(MSG_STUDENT); ?>
    </div>
    <div id="list-all-teacher" class="full-width-container">
        <hr>
        <h2>Danh sách giáo viên</h2>
        <?php list_users(MSG_TEACHER); ?>
    </div>
</div>
<div id="handle-msg" class="full-width-container">
    <form method="post" action="">
        <label>
            <span id="send-to"></span><br>
            <textarea name="send_msg"></textarea>
        </label>
        <input type="hidden" name="recv_id" value=""><br>
        <button type="submit" class="small-btn">Gửi</button>
    </form>
</div>

<div id="msg-history" class="full-width-container">
    <?php echo $msg_history; ?>
</div>
<script>
    <?php if (!empty($msg_history)) echo 'toBottom();'; ?>
    function toBottom() {
        window.scrollTo(0, document.body.scrollHeight);
    }

    function sendMessage(fullname, recv_id) {
        document.querySelector("#handle-msg #send-to").innerText = "Gửi đến " + fullname;
        document.querySelector("#handle-msg input").value = recv_id;
        document.getElementById('handle-msg').style.display = 'unset';
    }
</script>
</body>