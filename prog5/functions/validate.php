<?php
require_once 'functions/database.php';
const HASH = 1;
const PLAIN = 2;


class POST
{
    /**
     * Lấy uid từ phương thức POST
     * @param $uid
     * Mặc định là null, trả về $_POST['uid']
     * Nếu có tham số truyền vào sẽ trả về $_POST[$uid]
     * @return int|null
     * Trả về id của user nếu thành công hoặc null nếu thất bại
     */
    static function uid($uid = null): ?int
    {
        if ($uid === null) {
            if (!isset($_POST['uid'])) return null;
            return check_user_id($_POST['uid']);
        }

        $uid = check_id(($_POST[$uid]));
        if (!exist_uid($uid, ALL)) {
            global $not_exist;
            $not_exist = true;
            return null;
        }
        return $uid;
    }

    /**
     * Lấy và kiểm tra id của message có tồn tại không
     * @return int|null
     * Trả về id của tin nhắn được escape nếu thành công, null nếu thật bại
     */
    static function msg_id(): ?int
    {
        if (empty($_POST['msg_id'])) return null;
        $msg_id = validate($_POST['msg_id']);
        if (!exist_msg_id($msg_id)) return null;
        return $msg_id;
    }

    /**
     * Lấy và kiểm tra id của bài tập có tồn tại không
     * @return int|null
     * Trả về id của bài tập được escape nếu thành công, null nếu thật bại
     */
    static function exer_id(): ?int
    {
        if (empty($_POST['exer_id'])) return null;
        $exer_id = validate($_POST['exer_id']);
        if (!exist_exer_id($exer_id)) return null;
        return $exer_id;
    }

    /**
     * Lấy và kiểm tra chall_id (id của challange)
     * @return string|null
     * Trả về chall_id nếu không rỗng
     * Trả về null nếu rỗng
     */
    public static function chall_id(): ?string
    {
        if (empty($_POST['chall_id'])) return null;
        return validate($_POST['chall_id']);
    }

    /**
     * Lấy và kiểm tra username có hợp lệ không
     * @return string|null
     * Trả về username nếu hợp lệ
     * Trả về null và báo lỗi nếu username không hợp lệ
     */
    static function username(): ?string
    {
        if (!isset($_POST['username'])) {
            global $db;
            $db['failed'] = true;
            return null;
        }
        return check_username($_POST['username']);
    }

    /**
     * Lấy password được gửi đến server
     * @param $uid
     * Mặc định là null, nếu uid là -1 (tương ứng với thêm học sinh) hoặc -2 (tương ứng với thêm giáo viên)
     * sẽ trả vê null và báo lỗi nếu password trống.
     * @param $option
     * Mặc định là HASH, trả về password đã được hash.
     * Nếu tham số nhận vào là PLAIN sẽ trả về password dạng plain text
     * @return string|null
     */
    static function password($uid = null, $option = HASH): ?string
    {
        if (!isset($_POST['password'])) {
            global $db;
            $db['failed'] = true;
            return null;
        }
        $password = $_POST['password'];
        if ($option === HASH)
            return check_password($uid, $password);
        elseif ($option === PLAIN)
            return $password;
        else die("FATAL");
    }

    /**
     * Lấy và kiểm tra tên có hợp lệ không
     * @return string|null
     * Trả về tên nếu hợp lệ
     * Trả về null và báo lỗi nếu tên không hợp lệ
     */
    static function fullname(): ?string
    {
        if (!isset($_POST['fullname'])) {
            global $db;
            $db['failed'] = true;
            return null;
        }
        return check_fullname($_POST['fullname']);
    }

    /**
     * Lấy và kiểm tra số điện thoại có hợp lệ không
     * @return string|null
     * Trả về số điện thoại nếu hợp lệ hoặc string rỗng nếu không có số điện thoại
     * Trả về null và bóa lỗi nếu không hợp lệ
     */
    static function phone(): ?string
    {
        if (!isset($_POST['phone'])) {
            global $db;
            $db['failed'] = true;
            return null;
        }
        return check_phone($_POST['phone']);
    }

    /**
     * Lấy và kiểm tra email có hợp lệ không
     * @return string|null
     * Trả về email nếu hợp lệ hoặc string rỗng nếu không có email
     * Trả về null và bóa lỗi nếu không hợp lệ
     */
    static function email(): ?string
    {
        if (!isset($_POST['email'])) {
            global $db;
            $db['failed'] = true;
            return null;
        }
        return check_email($_POST['email']);
    }

    /**
     * Lấy và kiểm tra gợi ý của challenge có rỗng không
     * @return string|null
     * Trả về gợi ý nếu không rỗng
     * Trả về null nếu rỗng
     */
    public static function chall_hint(): ?string
    {
        if (empty($_POST['chall_hint'])) return null;
        return validate($_POST['chall_hint']);
    }

    /**
     * Lấy và kiểm tra câu trả lời cho challenge của người dùng có rỗng không
     * @return string|null
     * Trả về câu trả lời nếu không rỗng
     * Trả về null nếu rỗng
     */
    public static function chall_ans(): ?string
    {
        if (empty($_POST['chall_ans'])) return null;
        return validate($_POST['chall_ans']);
    }
}

/**
 * Trim và escape tham số nhận vào
 * @param $string
 * @return string|null
 * Trả về sâu đã được trim
 */
function validate($string): ?string
{
    return mysqli_real_escape_string(db_exist_conn(), trim($string));
}

/**
 * Phần dưới để kiểm tra tính hợp lệ của các loại id, username, password, fullname, email và phone
 */
function check_user_id($uid): ?int
{
    if ($uid === 'new_student') {
        return -1;
    } elseif ($uid === 'new_teacher') {
        return -2;
    }
    return check_id($uid);
}

function check_id($id): ?int
{
    if (only_digit($id)) {
        return $id;
    } else {
        global $validation;
        $validation['id'] = true;
        return null;
    }
}

function only_digit($str): bool|int
{
    return preg_match('/^\d+$/', $str);
}

function check_fullname($fullname): ?string
{
    if (empty($fullname)) {
        global $validation;
        $validation['fullname'] = true;
        return null;
    }
    return validate($fullname);
}


function check_phone($phone): ?string
{
    if (empty($phone)) return $phone;

    if (preg_match("/^\+?\d{5,15}$/", $phone,)) {
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
    if (preg_match('/^[A-Za-z][A-Za-z0-9]{4,31}$/', $username)) {
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