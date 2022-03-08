<?php


/**
 * Tạo kết nối đến MySQL
 * @return mysqli
 */
function db_conn(): mysqli
{
    $servername = 'localhost';
//    $username = 'vcth4nh';
//    $password = 'vcth4nh';
//    $db = 'prog5';

//  DB trên www.000webhost.com
    $username = 'id18462295_vcth4nh';
    $password = 'id18462295_Vcth4nh';
    $db = 'id18462295_prog5';

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

/**
 * Kiểm tra có sẵn kết nối đến MySQL không
 * @return mysqli
 * Trả về kết nối đó nếu tồn tại hoặc kết nối mới nếu không tồn tại
 */
function db_exist_conn(): mysqli
{
    global $conn;
    if (!empty($conn)) return $conn;
    $conn = db_conn();
    return $conn;

}

/**
 * Truy vấn đến MySQL với lệnh là tham số được truyền vào
 * @param $sql
 * Lệnh để truy vấn
 * @return mysqli_result|bool
 */
function db_query($sql): mysqli_result|bool
{
//    echo json_encode($sql) . "<br>";
    return db_exist_conn()->query($sql);
}

/**
 * Kiểm tra xem có tồn tại username không
 * @param $username
 * @return bool
 * Trả về false nếu không hoặc true nếu có
 */
function exist_uname($username): bool
{
    return db_query(SqlQuery::exist_uname($username))->num_rows !== 0;
}

/**
 * Kiểm tra có tồn tại uid (id của user) tương ứng với role (giáo viên hoặc học sinh) không
 * @param $uid
 * @param $role
 * @return bool
 * Trả về false nếu không hoặc true nếu có
 */
function exist_uid($uid, $role): bool
{
    return db_query(SqlQuery::exist_uid($uid, $role))->num_rows !== 0;
}

/**
 * Kiểm tra có bị trùng username với người khác không
 * @param $uid
 * uid của người cần kiểm tra
 * @param $username
 * username của người cần kiểm tra
 * @return bool
 * Trả về false nếu không hoặc true nếu có
 */
function exist_uname_with_diff_uid($uid, $username): bool
{
    return db_query(SqlQuery::exist_uname_with_diff_uid($uid, $username))->num_rows !== 0;
}

/**
 * Kiểm tra exer_id (id của file bài tập) có tồn tại không
 * @param $exer_id
 * @return bool
 * Trả về false nếu không hoặc true nếu có
 */
function exist_exer_id($exer_id): bool
{
    $result = db_query(SqlQuery::exist_exer($exer_id));
    return $result->num_rows === 1;
}

/**
 * Kiểm tra msg_id (id của tin nhắn) có tồn tại không
 * @param $msg_id
 * @return bool
 * Trả về false nếu không hoặc true nếu có
 */
function exist_msg_id($msg_id): bool
{
    $result = db_query(SqlQuery::exist_msg($msg_id));
    return $result->num_rows === 1;
}

/**
 * Trả về các lệnh SQL
 */
class SqlQuery
{
    static function get_users_list($student_id, $user_type): string
    {
        if (empty($student_id))
            return match ($user_type) {
                TEACHER => 'SELECT * FROM users where teacher',
                STUDENT => 'SELECT * FROM users where not teacher',
                default => 'SELECT * FROM users',
            };
        else return "SELECT * FROM users where not teacher and uid=$student_id";
    }

    static function get_user_info($username): string
    {
        return "SELECT * FROM users WHERE username='$username'";
    }

    static function update_stu_info($uid, $fullname, $phone, $email, $username, $password, $teacher = 'false'): string
    {
        if (empty($password)) {
            return match ($_SESSION['role']) {
                TEACHER => "UPDATE users SET fullname='$fullname', phone='$phone', email='$email', username='$username' WHERE uid=$uid AND teacher=$teacher",
                STUDENT => "UPDATE users SET phone='$phone', email='$email' WHERE uid=$uid",
                default => die('FATAL'),
            };
        } else {
            return match ($_SESSION['role']) {
                TEACHER => "UPDATE users SET fullname = '$fullname', phone = '$phone', email = '$email', username = '$username', password = '$password' WHERE uid = $uid and teacher = $teacher",
                STUDENT => "UPDATE users SET phone = '$phone', email = '$email', password = '$password' WHERE uid = $uid",
                default => die('FATAL'),
            };
        }

    }

    static function exist_uid($uid, $role): string
    {
        return match ($role) {
            TEACHER => "SELECT 1 FROM users WHERE uid = $uid and teacher = true",
            STUDENT => "SELECT 1 FROM users WHERE uid = $uid and teacher=false",
            ALL => "SELECT 1 FROM users WHERE uid = $uid",
            default => die("FATAL")
        };
    }

    static function exist_uname_with_diff_uid($uid, $username): string
    {
        return "SELECT 1 FROM users WHERE username = '$username' and uid != $uid";
    }

    static function add_user($fullname, $phone, $email, $username, $password, $teacher): string
    {
        return "INSERT INTO users(username, password, fullname, phone, email, teacher)
                VALUE('$username', '$password', '$fullname', '$phone', '$email', $teacher)";
    }

    public static function uid_from_uname($username): string
    {
        return "SELECT uid FROM users where username = '$username'";
    }

    public static function exist_uname($username): string
    {
        return "SELECT 1 FROM users WHERE username = '$username'";

    }

    public static function get_ava($uid): string
    {
        return "SELECT avatar FROM users WHERE uid = $uid";
    }

    public static function upload_ava($avatar_location, $uid): string
    {
        return "UPDATE users SET avatar = '$avatar_location' where uid = $uid";
    }

    public static function info_from_uid($uid): string
    {
        return "SELECT * FROM users WHERE uid = $uid";
    }

    public static function delete_student($student_id): string
    {
        return "DELETE FROM users WHERE uid = $student_id AND teacher=false";
    }

    public static function send_msg($send_id, $recv_id, $msg): string
    {
        return "INSERT INTO messages(send_id,recv_id, text)
                VALUE($send_id, $recv_id, '$msg')";
    }

    public static function edit_msg($msg_id, $msg): string
    {
        return "UPDATE messages SET text='$msg' WHERE msg_id=$msg_id";
    }

    public static function msg_history($send_id, $recv_id): string
    {
        return "SELECT * FROM messages WHERE send_id = $send_id and recv_id = $recv_id";
    }

    public static function delete_msg($msg_id): string
    {
        return "DELETE FROM messages WHERE msg_id=$msg_id AND send_id=" . $_SESSION['uid'];
    }

    public static function recv_msg($recv_id): string
    {
        return "SELECT * FROM messages WHERE recv_id = $recv_id";
    }

    public static function upload_exer($file_location, $file_name): string
    {
        return "INSERT INTO exercises(location,original_name)
                VALUE ('$file_location','$file_name')";
    }

    const list_exer = 'SELECT * FROM exercises';

    public static function get_file_original_name($location, $db_name): string
    {
        return "SELECT original_name FROM $db_name WHERE location='$location'";
    }

    public static function get_exer_location($exer_id): string
    {
        return "SELECT location FROM exercises WHERE exer_id=$exer_id";
    }

    public static function delete_exer($exer_id): string
    {
        return "DELETE FROM exercises WHERE exer_id=$exer_id";
    }

    public static function exist_exer($exer_id): string
    {
        return "SELECT 1 FROM exercises WHERE exer_id=$exer_id";
    }

    public static function upload_ans($uid, $exer_id, $file_location, $file_name): string
    {
        return "INSERT INTO submitted(uid,exer_id,location,original_name)
                VALUE ($uid,$exer_id,'$file_location','$file_name')";
    }

    public static function list_submitted($exer_id): string
    {
        return "SELECT * FROM submitted WHERE exer_id=$exer_id";
    }

    public static function exist_msg($msg_id): string
    {
        return "SELECT 1 FROM messages WHERE msg_id=$msg_id";
    }

    public static function upload_quiz($quiz_hint): string
    {
        return "INSERT INTO challs(hint)
                VALUE ('$quiz_hint')";
    }

    const get_chall_list = "SELECT * FROM challs";


}
