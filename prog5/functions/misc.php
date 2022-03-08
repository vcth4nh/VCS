<?php
const TEACHER = 1;
const STUDENT = 2;
const NO_CONN = false;


/**
 * Khởi tạo PHP sessio
 * @param $db
 * Khởi tạo kết nối đến MySQL nếu $db là true (mặc định)
 * @return string
 * Trả về session id
 */
function start_session($db = true): string
{
    if (session_id() === '') {
        session_start();
    }
    if ($db) {
        require_once 'database.php';
        global $conn;
        $conn = db_exist_conn();
    }
    return session_id();
}

/**
 * Lưu tất cả thông tin của user đang đăng nhập vào $_SESSION
 * @return void
 */
function set_session_info()
{
    require_once "functions/database.php";
    $result = db_query(SqlQuery::info_from_uid($_SESSION['uid']))->fetch_assoc();
    foreach ($result as $key => $value) {
        $_SESSION[$key] = $value;
    }
}

/**
 * Kiểm tra người dùng đã đăng nhập chưa
 * @return bool
 * Trả về true nếu đã đăng nhập, hoặc chuyển hướng về trang login nếu chưa đăng nhập
 */
function check_login(): bool
{
    if (!isset($_SESSION['role']))
        header('Location: login.php');
    return true;
}

/**
 * Chuyển hướng về trang chủ của học sinh nếu là học sinh
 * Chuyển hướng về trang chủ của giáo viên nếu là giáo viên
 * @return void
 */
function check_role()
{
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === TEACHER) {
            header('Location: ./teacher.php');
        } elseif ($_SESSION['role'] === STUDENT) {
            header('Location: ./student.php');
        }
    }
}

/**
 * Kiểm tra người đang đăng nhập có phải học sinh không
 * Nếu không thì chuyển hướng về trang đăng nhập
 * hoặc trang chủ của giáo viên
 * @return void
 */
function is_student()
{
    check_login();
    if ($_SESSION['role'] === TEACHER)
        header('Location: ./teacher.php');
}

/**
 * Kiểm tra người đang đăng nhập có phải giáo viên không
 * Nếu không thì chuyển hướng về trang đăng nhập
 * hoặc trang chủ của học sinh
 * @return void
 */
function is_teacher()
{
    check_login();
    if ($_SESSION['role'] === STUDENT)
        header('Location: ./student.php');
}

function xss($string): string
{
    return htmlspecialchars($string);
}


$Broadcast = array(
    'AUTH_EMPTY' => 'Tài khoản hoặc mật khẩu trống',
    'AUTH_INVALID' => 'Tài khoản hoặc mật khẩu không hợp lệ',
    'QUERY' => 'Cập nhật thất bại',
    'DB_duplicated' => 'Tên đăng nhập đã tồn tại',
    'DB_success' => 'Thành công',
    'DB_failed' => "Có lỗi xảy ra",
    'DB_id' => '',
    'VAL_id' => '',
    'VAL_fullname' => 'Tên không hợp lệ',
    'VAL_phone' => 'Số điện thoại chỉ bao gồm 5-15 kí tự số và dấu +',
    'VAL_email' => 'Email không hợp lệ',
    'VAL_username' => 'Username must start with letter, has 5-32 letters and numbers only',
    'VAL_password' => 'Mật khẩu trống',
    'UPLOAD_OK' => 'Tải lên thành công',
    'UPLOAD_failed' => 'Tải lên thất bại',
    'UPLOAD_upload' => 'Có lỗi trong quá trình tải lên',
    'UPLOAD_max_1MB' => 'File vượt quá 1MB',
    'UPLOAD_max_2MB' => 'File vượt quá 2MB',
    'UPLOAD_max_5MB' => 'File vượt quá 5MB',
    'UPLOAD_not_img' => 'Chỉ được upload file ảnh',
    'UPLOAD_not_txt' => 'Chỉ được upload file .txt',
    'UPLOAD_empty_txt' => 'File không có nội dung',
    'UPLOAD_exist_file' => 'File đã tồn tại',
    'UPLOAD_no_hint' => 'Không có gợi ý',
    'EXER_OK' => 'Thực thi thành công',
    'EXER_failed' => 'Thực thi thất bại'
);
