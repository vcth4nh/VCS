<?php
const AVATAR_FOLDER = 'avatars/';
const EXERCISE_FOLDER = 'exercises/';
const SUBMIT_FOLDER = 'submitted/';
const CHALLS_FOLDER = 'challs/';
const EXERCISE = '1';
const SUBMIT = '2';
const IMG = 1;
const TXT = 'text/plain';
const ALL_TYPE = 3;
const _1MB = 1048576;

/**
 * Kiểm tra định dạng của file upload
 * @param $type
 * Định dạng đúng của file, nếu địng dạng file khác $type sẽ gọi mảng $upload_err để lưu lỗi
 * @return void
 */
function check_type($type)
{
    switch ($type) {
        case IMG:
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check === false) {
                global $upload_err;
                $upload_err['not_img'] = true;
            }
            break;
        case TXT:
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            switch (finfo_file($finfo, $_FILES["file"]["tmp_name"])) {
                case TXT:
                    break;
                case 'application/x-empty':
                    global $upload_err;
                    $upload_err['empty_txt'] = true;
                    break;
                default:
                    global $upload_err;
                    $upload_err['not_txt'] = true;
                    break;
            }
            finfo_close($finfo);
            break;
    }
}

/**
 * Kiểm tra dung lượng của file upload
 * @param $sizeMB
 * Dung lượng lớn nhất của file, nếu file có dung lượng lớn hơn thì gọi mảng $upload_err để lưu lỗi
 * @return void
 */
function check_size($sizeMB)
{
    if ($_FILES["file"]["size"] > $sizeMB * _1MB) {
        global $upload_err;
        $upload_err["max_{$sizeMB}MB"] = true;
    }

}

/**
 * Kiểm tra tình trạng của file upload.
 * @return bool
 * Trả về true nếu nhận file thành công.
 * Trả về false nếu nhận file thất bại.
 */
function check_upload(): bool
{
    if (!isset($_FILES["file"]) or $_FILES["file"]['error'] != 0) {
        global $upload_err;
        $upload_err['upload'] = true;
        return false;
    }
    return true;
}


/**
 * Tạo tên ngẫu nhiên và độc nhất cho file
 * @param $folder
 * Folder dự định để lưu file
 * @return string
 * Trả về tên mới được khởi tạo của file
 */
function generate_name($folder): string
{
    do {
        $file_name = uniqid($_SESSION['uid'], true);
    } while (file_exists($folder . $file_name));
    return $file_name;
}


/**
 * Hoàn thành quá trình upload.
 * File được đổi tên ngẫu nhiên bằng generate_name(). Tên file cũ được lưu trong DB
 * @param $folder
 * Folder dự định để lưu file
 * @return void
 */
function finalize_upload($folder)
{
    global $uploadOK;
    $uploadOK = false;

    $original_name = $_FILES['file']['name'];
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);

    $server_file_name = generate_name($folder) . ".$extension";
    $tmp_file = $_FILES['file']['tmp_name'];

    if (move_uploaded_file($tmp_file, $folder . $server_file_name)) {
        $original_name = validate($original_name);
        $sql = match ($folder) {
            AVATAR_FOLDER => SqlQuery::upload_ava($server_file_name, $_SESSION['uid']),
            EXERCISE_FOLDER => SqlQuery::upload_exer($server_file_name, $original_name),
            SUBMIT_FOLDER => SqlQuery::upload_ans($_SESSION['uid'], $GLOBALS['exer_id'], $server_file_name, $original_name),
            default => die("FATAL")
        };
        $result = db_query($sql);
        if ($result) {
            $uploadOK = true;
        }
    }
}

/**
 * Xử lí upload ảnh đại diện
 * @return void
 */
function upload_ava()
{
    global $uploaded_to;
    $uploaded_to = AVATAR_FOLDER;
    if (!check_upload()) return;
    check_type(IMG);
    check_size(1);
    global $upload_err;

    if (empty($upload_err)) {
        finalize_upload(AVATAR_FOLDER);
    }
}

/**
 * Xử lí upload file bài tập hoặc file bài làm
 * @param $folder
 * Folder dự định để lưu file
 * @return void
 */
function upload_exer($folder)
{
    global $uploaded_to;
    $uploaded_to = $folder;
    if (!check_upload()) return;
    check_type(ALL_TYPE);
    check_size(5);
    global $upload_err;

    if (empty($upload_err)) {
        finalize_upload($folder);
    }
}

/**
 * Xử lí upload challenge
 * @param $hint
 * Gợi ý của challenge
 * @return void
 */
function upload_chall($hint)
{
    global $uploaded_to;
    $uploaded_to = CHALLS_FOLDER;
    if (!check_upload()) return;
    check_type(TXT);
    check_size(2);
    global $upload_err;

    if (empty($upload_err)) {
        global $uploadOK;
        $uploadOK = false;

        $conn = db_exist_conn();
        $hint = $conn->real_escape_string($hint);
        if ($conn->query(SqlQuery::upload_quiz($hint)) === false) return;
        $chall_id = $conn->insert_id;

        $server_file_name = $chall_id . '_' . $_FILES['file']['name'];
        $tmp_file = $_FILES['file']['tmp_name'];

        if (move_uploaded_file($tmp_file, CHALLS_FOLDER . $server_file_name))
            $uploadOK = true;
    }
}

/**
 * Thông báo kết quả thành công hoặc thất bại khi upload file
 * Nếu thất bại thì hiện thêm lí do
 * @param $folder
 * @return void
 */
function upload_noti($folder)
{
    global $uploaded_to;
    if ($folder !== $uploaded_to)
        return;
    global $uploadOK, $upload_err, $Broadcast;
    echo match ($uploadOK) {
        true => "<p class='success'>" . $Broadcast['UPLOAD_OK'] . "</p>",
        false => $Broadcast['UPLOAD_failed'],
        default => ''
    };
    $err = '';
    if (!empty($upload_err))
        foreach ($upload_err as $field => $value) {
            $err .= $Broadcast["UPLOAD_$field"] . "<br>";
        }
    if (!empty($err))
        echo substr($err, 0, -2);
}

