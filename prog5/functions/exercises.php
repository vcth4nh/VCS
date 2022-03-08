<?php
require_once 'functions/manage_users.php';
require_once 'functions/upload.php';

/**
 * Hiển thị bảng bài tập, mặc định có 3 cột: "Tên file", "Thời gian đăng" và "Tải"
 * Với mỗi role sẽ một số cột khác nhau:
 * Giáo viên: có thêm cột "Chi tiết và "Xóa"
 * Học sinh: có thêm cột "Nộp bài"
 * @param $role
 * Tham số này mang giá trị STUDENT (tương ứng với Học sinh)
 * và TEACHER (tương ứng với giáo viên)
 * @return void
 */
function display_exer($role)
{
    $result = db_query(SqlQuery::list_exer);
    if ($result->num_rows > 0) {
        $form_arr = array();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo "<table id='table-exer'>\n";
        $table_header = "<tr><th class='file-name'>Tên file</th><th class='upload-time'>Thời gian đăng</th><th class='download'>Tải</th>";
        $table_header .= match ($role) {
            TEACHER => "<th class='detail'>Chi tiết</th><th class='delete'>Xóa</th></tr>",
            STUDENT => "<th class='upload'>Nộp bài</th></tr>"
        };
        echo $table_header . "\n";
        $upload = match ($role) {
            STUDENT => true,
            TEACHER => false
        };
        foreach ($result as $res_row) {
            $form_arr[] = prepare_form('exer_' . $res_row['exer_id'], $upload);
            echo exer_row($res_row);
        }
        echo "</table>";
        export_form($form_arr);
    }
}

/**
 * Trả về từng hàng của bảng bài tập
 * @param $res_row
 * Từng hàng của kết quả truy vấn SQL
 * @return string
 */
function exer_row($res_row): string
{
    $file_name = htmlspecialchars($res_row['original_name']);
    $tb_row = "<tr id='{$res_row['exer_id']}'>";
    $tb_row .= "<td><div class='td-file-name'>$file_name</div></td>\n";
    $tb_row .= "<td class='center'>" . date_format(date_create($res_row['post_time']), 'G:i d/m/Y') . "</td>\n";
    $tb_row .= download_link($res_row['location'], EXERCISE);
    $tb_row .= match ($_SESSION['role']) {
            TEACHER => "<td class='center'><div class='link'><a class='open-submitted' href='view-submitted.php?exer_id={$res_row['exer_id']}'>Xem</a></div></td>" .
                "<td><button type='submit' name= 'delete_exer' value='delete_exer' style='float: none; margin: auto' onclick='return cfDel(\"{$res_row['original_name']}\")' form='exer_{$res_row['exer_id']}'>Xóa</button></td>" .
                "<input type='hidden' name='exer_id' value='{$res_row['exer_id']}' form='exer_{$res_row['exer_id']}'></tr>",
            STUDENT => "<td><label><input type='file' onclick='change_color(\"{$res_row['exer_id']}\")' name='file' form='exer_{$res_row['exer_id']}'>Upload</label>" .
                "<input type='hidden' name='exer_id' value='{$res_row['exer_id']}' form='exer_{$res_row['exer_id']}'>" .
                "<button type='submit' name='upload_ans' value='upload_ans' form='exer_{$res_row['exer_id']}'>Nộp</button></td></tr>"
        } . "\n";
    return $tb_row;
}

/**
 * Trả về link download của file đề bài hoặc file bài làm
 * @param $location
 * Tên của file trong database
 * @param $folder
 * Folder chứa file
 * @return string
 */
function download_link($location, $folder): string
{
    return "<td class='center'><a href='download.php?file=$location&folder=$folder'>Tải</a></td>\n";
}

/**
 * Xóa bài tập trong MySQL database và trong ổ nhớ
 * @return void
 */
function delete_exer()
{
    $exer_id = POST::exer_id();
    global $exerOK;
    $exerOK = false;
    if ($exer_id !== null) {
        $location = db_query(SqlQuery::get_exer_location($exer_id));
        $location = EXERCISE_FOLDER . $location->fetch_assoc()['location'];
        unlink($location);
        $exerOK = db_query(SqlQuery::delete_exer($exer_id));
    }
}

/**
 * Hiển thị thông báo liên quan đến bài tập
 * (upload bài tập, upload bài làm, xóa bài tập)
 * @return void
 */
function exer_noti()
{
    global $exerOK, $Broadcast;
    echo match ($exerOK) {
        true => '<p class="success">' . $Broadcast['EXER_OK'] . "</p>\n",
        false => '<p class="error">' . $Broadcast['EXER_failed'] . "</p>\n",
        default => '',
    };
}

/**
 * Lấy exer_id được thực hiện từ phương thức GET
 * Kiểm tra xem giá trị có hợp lệ không (kiểm tra có tồn tại exer_id đó trong DB không)
 * @return int|null
 * Trả về exer_id nếu giá trị nhận vào hợp lệ
 * Trả về null nếu giá trị nhận vào hợp lệ
 */
function GET_exer_id(): ?int
{
    if (!isset($_GET['exer_id'])) return null;
    $exer_id = check_id($_GET['exer_id']);
    if ($exer_id === null or !exist_exer_id($exer_id)) return null;
    return $exer_id;
}

/**
 * Hiển thị bảng học sinh đã nộp bài của bài tập
 * Bao gồm các cột: Tên học sinh, Thời gian nộp và Tải bài làm
 * Nếu chưa có ai hộp thì thông báo "Chưa có học sinh nộp"
 * @param $exer_id
 * exer_id của bài tập cần hiển thị
 * @return void
 */
function show_submitted_table($exer_id)
{
    $result = db_query(SqlQuery::list_submitted($exer_id));
    if ($result->num_rows > 0) {
        $result = $result->fetch_all(MYSQLI_ASSOC);
        echo "<table id='table-submitted'>";
        echo "<tr><th>Tên học sinh</th><th>Thời gian nộp</th><th>Tải bài làm</th></tr>";
        foreach ($result as $row) {
            echo "<tr id='{$row['sub_id']}'>";
            $fullname = db_query(SqlQuery::info_from_uid($row['uid']));
            if ($fullname->num_rows !== 1)
                die("FATAL");
            echo "<td>" . $fullname->fetch_assoc()['fullname'] . "</td>";
            echo "<td>" . date_format(date_create($row['post_time']), 'G:i d/m/Y') . "</td>";
            echo download_link($row['location'], SUBMIT);
        }
    } else echo '<p class="page-centered error">Chưa có học sinh nộp</p>';
}
