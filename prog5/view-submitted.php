<?php
require_once 'functions/misc.php';
require_once 'functions/exercises.php';

start_session();
is_teacher();

$exer_id = GET_exer_id();
if (!$exer_id)
    die('Error');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<header>
    <link rel="stylesheet" type="text/css" href="style.css">
</header>
<body>
<?php show_submitted_table($exer_id) ?>
</body>