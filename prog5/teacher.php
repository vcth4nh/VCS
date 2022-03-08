<?php
require_once "functions/misc.php";
require_once "functions/manage_users.php";
require_once "functions/received_msg.php";
require_once "functions/upload.php";
require_once "functions/exercises.php";

start_session();
is_teacher();

// Xử lí yêu cầu cập nhật thông tin người dùng
if (isset($_POST['uid']))
    manage_user();

// Xử lí yêu cầu đăng bài tập mới
if (isset($_POST['upload_exer']) and $_SESSION['role'] === TEACHER) {
    upload_exer(EXERCISE_FOLDER);
}

// Xử lí yêu cầu xóa bài tập
if (isset($_POST['delete_exer']) and $_SESSION['role'] === TEACHER) {
    delete_exer();
}

set_session_info();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Welcome teacher</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>$(document).ready(function () {
            let submittedList = $("#submitted-list")
            $(".open-submitted").click(function (e) {
                e.preventDefault();
                $("#submitted-list iframe").attr("src", $(this).attr('href'));
                $("#submitted-list").fadeIn('slow');
            });

            $("#submitted-list .close-submitted").click(function () {
                $("#submitted-list").fadeOut('slow');
            });
            submittedList.click(function () {
                $("#submitted-list").fadeOut('slow');
            });
        });
    </script>
</head>


<body>
<ul class="nav-bar">
    <li><a href="teacher.php" class="active">Trang chủ</a></li>
    <li><a href="userslist.php">Danh sách người dùng</a></li>
    <li><a href="challs.php">Challenges</a></li>
    <li class="right">
        <form action="./logout.php" method="post" class="logout">
            <button type="submit" name="logout" value="logout">Đăng xuất</button>
        </form>
    </li>
    <li class="right"><p>Chào <?= xss($_SESSION['fullname']) ?></p></li>
</ul>

<div class="full-width-container row">
    <div id='exer' class="column left">
        <h2>Bài tập</h2>
        <?php exer_noti(); ?>
        <div class="box-exer">
            <?php display_exer($_SESSION['role']); ?>
        </div>
        <div id="submitted-list">
            <div class="popup page-centered">
                <a href="#" class="close-submitted">X</a>
                <iframe src=""></iframe>
            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <p class="no-margin-bottom"><b>Đăng bài tập mới</b></p>
            <input type="file" name="file" id="upload-exer">
            <button type="submit" name="upload_exer" value="upload_exer" class="small-btn">Tải lên</button>
        </form>
        <p class="error"><?php upload_noti(EXERCISE_FOLDER) ?></p>
    </div>
    <div id="recv-msg" class="msg-box column right">
        <?php received_msg() ?>
    </div>
</div>

<div class="full-width-container">
    <div id="buttons-add-user">
        <button class="btn-50" onclick="add_new_student()">Thêm học sinh mới</button>
        <button class="btn-50" onclick="add_new_teacher()">Thêm giáo viên mới</button>
        <br>
    </div>
</div>
<div id="list-all-users">
    <div id="list-and-update-all-student" class="full-width-container">
        <hr>
        <h2>Cập nhật thông tin học sinh</h2>
        <p class="error"><?php display_update_noti(); ?></p>
        <?php list_users(UPDATE_STUDENT); ?>
        <!--TODO js form validation-->
    </div>
    <div id="list-all-teacher" class="full-width-container">
        <hr>
        <h2>Danh sách giáo viên</h2>
        <?php list_users(DISPLAY_TEACHER); ?>
    </div>
</div>
<script>
    function change_button_to_table(table) {
        document.getElementById("buttons-add-user").replaceWith(table);
    }

    function create_form() {
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "./teacher.php");
        form.setAttribute("id", "new_form")
        document.getElementsByTagName("body")[0].appendChild(form);
    }

    function cell(field, type = "text") {
        let cell = document.createElement("td");
        let input;
        if (field === "button") {
            input = document.createElement("button");
            input.setAttribute("type", type);
            input.innerHTML = "Gửi";
        } else {
            input = document.createElement("input");
            input.setAttribute("type", type);
            input.setAttribute("name", field);
        }
        input.setAttribute("form", "new_form");
        cell.append(input);

        return cell;
    }

    function add_cells(id_value) {
        let tmp_row = [];

        let id = document.createElement("input");
        id.setAttribute("type", "hidden");
        id.setAttribute("name", "uid");
        id.setAttribute("value", id_value);
        id.setAttribute("form", "new_form");
        tmp_row.push(id);

        tmp_row.push(cell("fullname"));
        tmp_row.push(cell("phone"));
        tmp_row.push(cell("email"));
        tmp_row.push(cell("username"));
        tmp_row.push(cell("password", "password"))
        tmp_row.push(cell("button", "submit"));

        return tmp_row;
    }


    function add_new_student(id_value = "new_student") {
        create_form()
        let table = document.createElement("table");
        table.insertRow().innerHTML = "<th>Họ và tên</th><th>Số điện thoại</th><th>Email</th><th>Tên đăng nhập</th><th>Mật khẩu</th><th>Cập nhật</th>";
        let new_profile = table.insertRow();
        new_profile.append(...add_cells(id_value));
        change_button_to_table(table);
    }

    function add_new_teacher() {
        add_new_student("new_teacher")
    }

    function cfDel(user) {
        return confirm('Xác nhận xóa ' + user);
    }

    function enable_input(formID) {
        let row = document.getElementById(formID);
        let el = row.querySelectorAll(":scope input[type=text], :scope input[type=password]");
        console.log(el);
        for (let i = 0; i < el.length; i++) {
            console.log(el[i]);
            el[i].disabled = false;
        }
        row.querySelector(':scope #submit-edit').style.display = 'block';
        row.querySelector(':scope #edit').style.display = 'none';
    }
</script>
</body>


</html>
