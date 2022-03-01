<?php
const AVATAR_FOLDER = 'avatars/';

function check_img()
{
    $check = getimagesize($_FILES["new_avatar"]["tmp_name"]);
    if ($check === false) {
        global $file;
        $file['type'] = true;
    }
}

function check_size()
{
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        global $file;
        $file['size'] = 0;
    }
}

function generate_name(): string
{
    do {
        $ava_name = uniqid($_SESSION['username'], true);
    } while (in_array($ava_name, scandir(AVATAR_FOLDER)));
    return $ava_name;
}

function upload_avatar()
{
    check_img();
    check_size();
    global $file;
    print_r($file);
    $uploadOk = false;

    if (empty($file)) {
        $file_name = $_FILES['new_avatar']['name'];
        $tmp_file = $_FILES['new_avatar']['tmp_name'];
        $image_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        echo $image_extension;
        $avatar_location = AVATAR_FOLDER . generate_name() . $image_extension;
        if (move_uploaded_file($tmp_file, $avatar_location))
            $uploadOk = true;
    }

    if ($uploadOk) {
        $uploadOk = false;
        $conn = db_conn();
        $sql = SqlQuery::upload_ava(basename($avatar_location), $_SESSION['username']);
        $result = $conn->query($sql);
        if ($result) {
            $uploadOk = true;
        }
    }
}

