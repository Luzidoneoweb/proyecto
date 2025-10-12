<?php
// csrf.php  (incluir en register.php y login.php)
session_start();
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function csrf_token(){
    return $_SESSION['csrf_token'];
}
function verify_csrf($token){
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
