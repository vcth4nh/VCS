<?php
/**
 * Hiển thị tin nhắn đến
 * @return void
 */
function received_msg()
{
    $result = db_query(SqlQuery::recv_msg($_SESSION['uid']));
    if ($result->num_rows !== 0) {
        $result = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($result as $row) {
            $sender = db_query(SqlQuery::info_from_uid($row['send_id']))->fetch_assoc()['fullname'];
            echo "<hr>";
            echo "<h4 class='no-margin-top'>$sender gửi lúc {$row['recv_time']}</h4>\n";
            echo "<p class='no-margin-top'>" . xss($row['text']) . "</p>";
        }
    } else echo "<h4>Không có tin nhắn đến</h4>\n";

}