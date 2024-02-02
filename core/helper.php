<?php
function enrcypt_password($password)
{
    return sha1(PASSWORD_SALT . $password . PASSWORD_SALT);
}
function generate_token($index)
{
    return $_SESSION["CSRF"][$index] = sha1(PASSWORD_SALT . "CSRF" . time() . "CSRF");
}
function get_token($index)
{
    return $_SESSION["CSRF"][$index] ?? NULL;
}
function expire_token($index)
{
    if(isset($_SESSION["CSRF"][$index])) {
        unset($_SESSION["CSRF"][$index]);
    }
}