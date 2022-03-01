<?php
require_once "config.php";
start_session();
function role()
{
    if (isset($_SESSION['teacher'])) {

        if ($_SESSION['teacher']) {
            echo 'Welcome teacher';
        } else {
            echo 'Welcome student';
        }
    } else header('Location: ./index.php');
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php role(); ?></title>
</head>
<body>

Hello <?php echo $_SESSION['name']; ?> <br>

</body>
</html>