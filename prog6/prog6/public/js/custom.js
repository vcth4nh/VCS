// function change_button_to_table(table) {
//     document.getElementById("buttons-add-user").replaceWith(table);
// }

function htmlToElement(html) {
    let template = document.createElement('template');
    html = html.trim();
    template.innerHTML = html;
    return template.content.firstChild;
}

function create_form(formId, action = 'post') {
    let form = document.createElement("form");
    form.setAttribute("method", action);
    form.setAttribute("action", "");
    form.setAttribute("id", 'form_' + formId)
    let csrf = htmlToElement('<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').getAttribute('content') + '"/>');
    form.appendChild(csrf)
    document.getElementsByTagName("body")[0].append(form);
}

// function cell(field, type = "text") {
//     let cell = document.createElement("td");
//     let input;
//     if (field === "button") {
//         input = document.createElement("button");
//         input.setAttribute("type", type);
//         input.innerHTML = "Gửi";
//     } else {
//         input = document.createElement("input");
//         input.setAttribute("type", type);
//         input.setAttribute("name", field);
//     }
//     input.setAttribute("form", "new_form");
//     cell.append(input);
//
//     return cell;
// }
//
// function add_cells(id_value) {
//     let tmp_row = [];
//
//     let id = document.createElement("input");
//     id.setAttribute("type", "hidden");
//     id.setAttribute("name", "uid");
//     id.setAttribute("value", id_value);
//     id.setAttribute("form", "new_form");
//     tmp_row.push(id);
//
//     tmp_row.push(cell("fullname"));
//     tmp_row.push(cell("phone"));
//     tmp_row.push(cell("email"));
//     tmp_row.push(cell("username"));
//     tmp_row.push(cell("password", "password"))
//     tmp_row.push(cell("button", "submit"));
//
//     return tmp_row;
// }
//
//
// function add_new_student(id_value = "new_student") {
//     create_form()
//     let table = document.createElement("table");
//     table.insertRow().innerHTML = "<th>Họ và tên</th><th>Số điện thoại</th><th>Email</th><th>Tên đăng nhập</th><th>Mật khẩu</th><th>Cập nhật</th>";
//     let new_profile = table.insertRow();
//     new_profile.append(...add_cells(id_value));
//     change_button_to_table(table);
// }
//
// function add_new_teacher() {
//     add_new_student("new_teacher")
// }

function cfDelUser(user, uid, formID) {
    let form = document.querySelector('#delete-form form');
    form.id = 'form_' + formID;
    form.querySelector('input[name=uid]').value = uid;

    return confirm('Xác nhận xóa học sinh ' + user);
}

function cfDelExer(original_name, exer_id, formID) {
    let form = document.querySelector('#delete-exer form');
    form.id = 'form_' + formID;
    form.querySelector('input[name=exer_id]').value = exer_id;

    return confirm('Xác nhận xóa bài tập ' + original_name);
}

function editUser(formID,role) {
    let row = document.getElementById(formID);
    let el;
    if (role === 'teacher'){
        el = row.querySelectorAll(":scope input[type=text], :scope input[type=password]");
    } else  el = row.querySelectorAll(":scope input[name=email], :scope input[name=phone], :scope input[name=password]");
    console.log(el);
    for (let i = 0; i < el.length; i++) {
        el[i].disabled = false;
    }
    displaySubmitButton(row)
    create_form(formID)
}

function sendMessage(fullname, recv_uid) {
    document.querySelector("#send-msg #send-to").innerText = "Gửi đến " + fullname;
    document.querySelector("#send-msg input").value = recv_uid;
    document.getElementById('send-msg').style.display = 'unset';
}

function displaySubmitButton(element) {
    console.log(element.querySelector(':scope #edit-button'));
    console.log(element.querySelector(':scope #submit-button'));
    element.querySelector(':scope #edit-button').style.display = 'none';
    element.querySelector(':scope #submit-button').style.display = 'inline-block';
}

function editMgs(msg_id) {
    msg_id = 'msg_' + msg_id;
    let msg = document.getElementById(msg_id);
    msg.getElementsByTagName('textarea')[0].disabled = false;
    displaySubmitButton(msg);
}

function showSubmitted(href) {
    let element = document.getElementById('submitted-list');
    element.hidden = false;
    element.querySelector(':scope iframe').src = href
}

function showUpload(exer_id) {
    let element = document.getElementById('upload-submit');
    element.hidden = false;
    element.querySelector(':scope input[name=exer_id]').value =exer_id
}
