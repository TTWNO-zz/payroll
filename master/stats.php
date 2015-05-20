<?php
function stats(){
  $db = new MySQLDatabase($myhost,
        $myusername,
        $mypassword,
        $mydatabase);

  $today_start = date("");

  $query = "SELECT
             date_format(from_unixtime(`unixTimestamp`), '%l:%i %p') as `time`,
            `IO` as `io`
            FROM `$name`
            WHERE
            ";

}
?>
