<?php
require_once 'config.php';
require_once 'upload_conf.php';
start_session();
is_student();
$username = $_SESSION['username'];
function get_avatar()
{
    global $username;
    $conn = db_conn();
    $sql = SqlQuery::get_ava($username);
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $image_location = $result->fetch_assoc()['avatar'];
        if (!empty($image_location)) {
            $image_location = AVATAR_FOLDER . $image_location;
            list($width, $height, $mine_type) = getimagesize($image_location);
            $rescaled_height = $height * $width / 120;
            $base64_image = base64_encode(file_get_contents($image_location));
            echo "<img src='data:$mine_type;base64,$base64_image' alt='student's avatar' style='width:300px;height:" . $rescaled_height . ";'/>\n";
        } else echo "<p>No avatar</p>\n";
    } else die("FATAL");
}

if (isset($_POST['submit'])) {
    upload_avatar();
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
    <meta charset="UTF-8">
    <title>Welcome student</title>
</head>


<body>
<form action="./logout.php" method="POST">
    <input type="submit" name="logout" value="logout"/>
</form>

<div id='avatar'>
    <h1>Avatar</h1>
    <?php get_avatar(); ?>
    <form action="student.php" method="POST" enctype="multipart/form-data">
        <p>Upload new avatar</p>
        <input type="file" name="new_avatar" id="new_avatar">
        <button type="submit" name="submit" value="submit">Upload avatar</button>
    </form>
</div>


</body>
