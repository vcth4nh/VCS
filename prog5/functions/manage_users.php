<?php
require_once 'functions/database.php';
require_once 'functions/validate.php';
require_once 'functions/misc.php';

const UPDATE = 1;
const DISPLAY = 2;
const MSG = 3;
const ACT_UPDATE = array('action' => UPDATE);
const ACT_DISPLAY = array('action' => DISPLAY);
const ACT_MSG = array('action' => MSG);


const ALL = 3;
const USER_TEACHER = array('user type' => TEACHER);
const USER_STUDENT = array('user type' => STUDENT);
const USER_ALL = array('user type' => ALL);

const DISPLAY_TEACHER = USER_TEACHER + ACT_DISPLAY;
const DISPLAY_STUDENT = USER_STUDENT + ACT_DISPLAY;
const DISPLAY_ALL = USER_ALL + ACT_DISPLAY;
const UPDATE_STUDENT = USER_STUDENT + ACT_UPDATE;
const MSG_TEACHER = USER_TEACHER + ACT_MSG;
const MSG_STUDENT = USER_STUDENT + ACT_MSG;

/**
 * Hiển thị thông tin thành viên
 * @param $option
 * Tham số này nhận các giá trị: DISPLAY_TEACHER, DISPLAY_STUDENT, DISPLAY_ALL,
 * UPDATE_STUDENT, MSG_TEACHER, MSG_STUDENT.
 * Trong đó
 *      DISPLAY sẽ hiện bảng thông tin cơ bản của thành viên, gồm các cột Tên, Số điện thoại và Email.
 *      UPDATE sẽ hiện bảng cập nhật thông tin cơ bản của thành viên, thêm với các cột  Username, Password, Xóa và Kết quả cập nhật
 *      MSG sẽ hiện bảng thông tin thông tin cơ bản của thành viên, thêm với cột Gửi tin nhắn và Xem tin nhắn
 * @param $student_id
 * Mặc định là null. Nếu được truyền tham số vào sẽ hiện thông tin của học sinh có uid tương ứng.
 * @return void
 */
function list_users($option, $student_id = null)
{
    $action = $option['action'];
    $result = db_query(SqlQuery::get_users_list($student_id, $option['user type']));
    if (!$result) return;
    $result = $result->fetch_all(MYSQLI_ASSOC);

    if (count($result) > 0) {
        $form_arr = array();
        $delete_col = '';
        if ($_SESSION['role'] === TEACHER)
            $delete_col = "<th class='delete'>Xóa</th>";

        echo "<table>\n";
        $table_header = '<tr><th>Họ và tên</th><th>Số điện thoại</th><th>Email</th>';
        $table_header .= match ($action) {
            UPDATE => "<th>Tên đăng nhập</th><th>Mật khẩu</th><th>Cập nhật</th>" . $delete_col . "<th class='small-cell'></th></tr>\n",
            MSG => "<th class='small-cell blue'>Nhắn tin</th><th class='small-cell blue'>Xem tin nhắn</tr>\n",
            default => "</tr>\n",
        };
        echo "$table_header\n";

        foreach ($result as $res_row) {
            echo "<tr id='{$res_row['uid']}'>\n";
            echo "<input name='uid' type='hidden' value='{$res_row['uid']}' form='form_{$res_row['uid']}'>\n";
            echo table_cell($res_row, $action, 'fullname') . "\n";
            echo table_cell($res_row, $action, "phone") . "\n";
            echo table_cell($res_row, $action, "email") . "\n";
            switch ($action) {
                case UPDATE:
                    $form_arr[] = prepare_form('form_' . $res_row['uid']);
                    echo table_cell($res_row, $action, "username") . "\n";
                    if ($_SESSION['role'] === TEACHER) {
                        echo "<td><input name='password' type='password' disabled value='' form='form_{$res_row['uid']}'></td>\n";
                        echo "<td><button id='edit' type='button' onclick='enable_input(\"{$res_row['uid']}\")'>Sửa</button>" .
                            "<button id='submit-edit' type='hidden' style='display: none' name= 'update_user_info' value='update_user_info' form='form_{$res_row['uid']}'>Gửi</button></td>\n";
                        echo "<td><button type='submit' name= 'delete_user_info' value='delete_user_info' onclick='return cfDel(\"{$res_row['fullname']}\")' form='form_{$res_row['uid']}'>Gửi</button></td>\n";

                    } else {
                        echo "<td><input name='password' type='password' value='' form='form_{$res_row['uid']}'></td>\n";
                        echo "<td><button id='submit-edit' type='submit' name= 'update_user_info' value='update_user_info' form='form_{$res_row['uid']}'>Gửi</button></td>\n";
                    }
                    echo "<td>" . update_result($res_row['uid']) . "</td>\n";
                    break;
                case MSG:
                    if ($res_row['uid'] != $_SESSION['uid']) {
                        $form_arr[] = prepare_form('msg_history_' . $res_row['uid']);
                        echo "<td ><button type='button' onclick='sendMessage(\"{$res_row['fullname']}\",\"{$res_row['uid']}\")' >Gửi</button></td>";
                        echo "<td><button type='submit' form='msg_history_{$res_row['uid']}'>Xem</button></td>";
                        echo "<input name='recv_id' value='{$res_row['uid']}' type='hidden' form='msg_history_{$res_row['uid']}'>";
                        echo "<input name='view_history' value='view_history' type='hidden' form='msg_history_{$res_row['uid']}'>";
                    }
            }
            echo "</tr>\n";
        }
        echo '</table>';
        if ($action === UPDATE or $action === MSG) export_form($form_arr);
    }
}


/**
 * Trả về từng hàng của bảng thành viên
 * @param $res_row
 * Từng hàng của kết quả truy vấn SQL
 * @param $action
 * Có thể là DISPLAY, UPDATE, MSG
 * Nếu $action là UPDATE thì các ô sẽ có thể chỉnh sửa để cập nhật thông tin,
 * nếu không thì chỉ hiện giá trị của các ô.
 * @param $field
 * @return string
 * Trả về từng hàng của bảng thành viên
 */
function table_cell($res_row, $action, $field): string
{
    $value = htmlspecialchars($res_row["$field"], ENT_QUOTES);
    if ($action === UPDATE) {
        if ($_SESSION['role'] === STUDENT and ($field !== 'fullname' and $field !== 'username'))
            return "<td><input name='$field' type='text' placeholder='$value' value='$value' form='form_{$res_row['uid']}'></td>";
        else return "<td><input name='$field' type='text' disabled placeholder='$value' value='$value' form='form_{$res_row['uid']}'></td>";
    } else return "<td>$value</td>";
}

/**
 * Trả về form POST
 * @param $form_id
 * id của form
 * @param bool $upload
 * Mặc định là false, trả về form POST với data được encode dạng application/x-www-form-urlencoded
 * Nếu là true, trả về form POST với data được encode dạng multipart/form-data để upload file
 * @return string
 */
function prepare_form($form_id, bool $upload = false): string
{
    if ($upload) return "<form method='post' action='' id='$form_id' enctype='multipart/form-data'></form>";
    return "<form method='post' action='' id='$form_id'></form>";
}

/**
 * Hiện form ra HTML body
 * @param $form_arr
 * Mảng gồm những form được chuẩn bị bằng prepare_form()
 * @return void
 */
function export_form($form_arr)
{
    foreach ($form_arr as $form) {
        echo $form . "\n";
    }
}

/**
 * Cập nhật thông tin học sinh
 * @param $uid
 * id của học sinh
 * @param $fullname
 * Tên đầy đủ của học sinh
 * @param $phone
 * Số điện thoại của học sinh
 * @param $email
 * Email của học sinh
 * @param $username
 * Tên đăng nhập của học sinh
 * @param $password
 * Mật khẩu của học sinh
 * @return void
 */
function update_stu_info($uid, $fullname, $phone, $email, $username, $password)
{
    global $db, $dbOK;
    if ($_SESSION['role'] === TEACHER) {
        if (!exist_uid($uid, STUDENT)) $db['id'] = true;
        if (exist_uname_with_diff_uid($uid, $username)) $db['duplicated'] = true;
    }
    if (!isset($db['id']) and !isset($db['duplicated']))
        $dbOK = db_query(SqlQuery::update_stu_info($uid, $fullname, $phone, $email, $username, $password));
}

/**
 * Thực hiện nhật thông tin học sinh, xóa học sinh, thêm học sinh/giáo viên
 * @return void
 */
function manage_user()
{
    global $db, $dbOK, $validation;
    $dbOK = false;

    $uid = POST::uid();
    if (isset($_POST['delete_user_info']) && $uid > 0) {
        $dbOK = db_query(SqlQuery::delete_student($uid));
        return;
    }

    $username = POST::username();
    $fullname = POST::fullname();
    $phone = POST::phone();
    $email = POST::email();
    $password = POST::password($uid);

    if (!isset($validation)) {
        if ($uid === -1) {
            global $added_student;
            $added_student = false;
            $exist = exist_uname($username) and exist_uid($uid, ALL);
            if ($exist)
                $db['duplicated'] = true;
            else
                $added_student = $dbOK = db_query(SqlQuery::add_user($fullname, $phone, $email, $username, $password, 'false'));

        } elseif ($uid === -2) {
            global $added_teacher;
            $added_teacher = false;
            $exist = exist_uname($username) and exist_uid($uid, ALL);
            if ($exist)
                $db['duplicated'] = true;
            else
                $added_teacher = $dbOK = db_query(SqlQuery::add_user($fullname, $phone, $email, $username, $password, 'true'));

        } else update_stu_info($uid, $fullname, $phone, $email, $username, $password);
    }
}

/**
 * Hiển thị kết quả cập nhật thông tin ở cột Kết quả cập nhật
 * @param $uid
 * uid của người dùng được cập nhật
 * @return string
 */
function update_result($uid): string
{
    global $dbOK;
    if (isset($_POST['uid']) and $uid != $_POST['uid']) return '';
    if ($dbOK === true)
        return "✅";
    elseif ($dbOK === false)
        return "❌";
    return '';
}

/**
 * Hiển thị thông báo thành công hoặc thất bại sau khi gửi cập nhật thông tin
 * Nếu thất bại thì hiển thị thêm lí do thât bại
 * @return void
 */
function display_update_noti()
{
    global $Broadcast, $db, $validation, $added_teacher, $added_student;
    $err = '';
    if (!empty($db))
        foreach ($db as $field => $value) {
            $err .= $Broadcast["DB_$field"] . '<br>';
        }
    if (!empty($validation))
        foreach ($validation as $field => $value) {
            $err .= $Broadcast["VAL_$field"] . '<br>';
        }

    if ($added_teacher === true) echo "<p class='success'>Thêm giáo viên thành công</p>";
    elseif ($added_teacher === false) $err .= "Thêm giáo viên thất bại" . '<br>';

    if ($added_student === true) echo "<p class='success'>Thêm học sinh thành công</p>";
    elseif ($added_student === false) $err .= "Thêm học sinh thất bại" . '<br>';

    if (!empty($err))
        echo substr($err, 0, -4);
}