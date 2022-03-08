<?php
require_once 'functions/misc.php';
require_once 'functions/upload.php';
require_once 'functions/validate.php';

start_session();
check_login();

if (!empty($_GET['file']) and !empty($_GET['folder'])) {
    $location = validate($_GET['file']);
    list($db_name, $folder) = match ($_GET['folder']) {
        EXERCISE => array('exercises', EXERCISE_FOLDER),
        SUBMIT => array('submitted', SUBMIT_FOLDER),
        default => die('FATAL')
    };
    $file_original = db_query(SqlQuery::get_file_original_name($location, $db_name));
    if ($file_original->num_rows === 1) {
        $file_original = $file_original->fetch_assoc()['original_name'];
        $location = $folder . $location;
        if (!file_exists($location)) die("KHÔNG TỒN TẠI FILE");
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $location);
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime_type);
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($file_original));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($location));
        readfile($location);
        exit();
    } else die('FATAL');

}
