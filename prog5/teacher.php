<?php
require_once "config.php";
start_session();
//is_teacher();
function table_cell($row, $field): string
{
    $value = htmlspecialchars($row["$field"], ENT_QUOTES);
    return "<td><input name='$field' type='text' placeholder='$value' value='$value' form='form_{$row['id']}'></td>";
}

function prepare_form($form_id): string
{
    return "<form method='POST' action='./teacher.php' id='form_$form_id'></form>";
}

function export_form()
{
    global $form_arr;
    foreach ($form_arr as $form) {
        echo $form . "\n";
    }
}

function get_stu_info()
{
    $conn = db_conn();
    $sql = SqlQuery::get_student_list();
    $result = $conn->query($sql);
    if (!$result) return;

    $result = $result->fetch_all(MYSQLI_ASSOC);
    if (count($result) > 0) {
        global $form_arr;
        $form_arr = array();
        echo "<table id='student_list'>\n";
        echo "<tr><th>Full Name</th><th>Phone number</th><th>Email</th><th>Username</th><th>Password</th><th>Edit</th><th></th></tr>\n";
        foreach ($result as $row) {
            $form_arr[] = prepare_form($row['id']);
            echo "<tr id='{$row['id']}'>\n";
            echo "<input name='id' type='hidden' value='{$row['id']}' form='form_{$row['id']}'>\n";
            echo table_cell($row, 'fullname') . "\n";
            echo table_cell($row, "phone") . "\n";
            echo table_cell($row, "email") . "\n";
            echo table_cell($row, "username") . "\n";
            echo "<td><input name='password' type='text' value='' form='form_{$row['id']}'></td>\n";
            echo "<td><button type='submit' form='form_{$row['id']}'>Submit</button></td>\n";
            echo "<td>" . db_result($row['id']) . "</td>\n";
            echo "</form></tr>\n";
        }
        echo '</table>';
    }
    export_form();
}


function db_result($id)
{
    global $ERR, $db;
    if ($id != $_POST['id']) return '';
    if (isset($db['success']))
        if ($db['success'])
            return "✅";
        else {
            return $ERR['QUERY'];

        }
    return '';
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    var_dump($_POST);
    $db['success'] = false;

    $id = POST::id();
    $username = POST::username();

    $fullname = POST::fullname();
    $phone = POST::phone();
    $email = POST::email();
    $password = POST::password($id, HASH);

    if (!isset($validation)) {
        $conn = db_conn();
        if ($id === -1 or $id === -2) {
            $exist = is_exist('', $username, $conn);
            if ($exist) {
                $db['duplicated'] = true;
            } else {
                if ($id === -2) {
                    global $added_teacher;
                    $added_teacher = false;
                }

                if ($id === -1) $sql = SqlQuery::add_user($fullname, $phone, $email, $username, $password);
                else $sql = SqlQuery::add_user($fullname, $phone, $email, $username, $password, true);

                $result = $conn->query($sql);
                if ($result)
                    $db['success'] = true;
                if ($id === 1) {
                    $sql = SqlQuery::id_from_username($username);
                    $_POST['id'] = $conn->query($sql)->fetch_all()['id'];
                    echo $_POST['id'];
                } else {
                    $added_teacher = true;
                }
            }
        } else {
            $exist = is_exist($id, $username, $conn);
            if ($exist === 1)
                $db['id'] = true;
            elseif ($exist === 2)
                $db['duplicated'] = true;
            else {
                $sql = SqlQuery::update($id, $fullname, $phone, $email, $username, $password);
                $result = $conn->query($sql);
                if ($result)
                    $db['success'] = true;

            }
        }

    }
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <title>Welcome teacher</title>
</head>


<body>
<h1>Edit student's information</h1>
<div id="buttons_add_user">
    <button onclick="add_new_student()">Add new student</button>
    <button onclick="add_new_teacher()">Add new teacher</button>
    <br>
</div>
<?php show_notice();
get_stu_info(); ?>
<!--TODO js form validation-->

<script>
    function delete_button() {
        document.getElementById("buttons_add_user").remove();
    }

    function create_form() {
        let form = document.createElement("form");
        form.setAttribute("method", "POST");
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
            input.innerHTML = "Submit";
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
        id.setAttribute("name", "id");
        id.setAttribute("value", id_value);
        id.setAttribute("form", "new_form");
        tmp_row.push(id);

        tmp_row.push(cell("fullname"));
        tmp_row.push(cell("phone"));
        tmp_row.push(cell("email"));
        tmp_row.push(cell("username"));
        tmp_row.push(cell("password", "password"))
        tmp_row.push(cell("button", "submit"));
        tmp_row.push(document.createElement("td"));

        return tmp_row;
    }

    function add_new_student(id_value = "new_student") {
        create_form()
        let table = document.getElementById("student_list");
        let row = table.insertRow(1);
        row.append(...add_cells(id_value));
        delete_button();
    }

    function add_new_teacher() {
        add_new_student("new_teacher")
    }
</script>
</body>


</html>
