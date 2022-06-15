<?php


// $opts = getopt('tof:');
// if (!isset($opts['f'])) {
//     die("Missing required parameter -f\n");
// }

// $payload = $opts['f'];

$payload = "1' where id=1;--";

echo "contact_number='{$payload}' where id=1";
