<?php
$Database = new Database\Database;
require_once "helper.php";
$Templater = new Templater\Templater;
$Router = new Router\Router;
if (isset($_SESSION["id"]))
{
    function unsetExcept($keys) {
        foreach ($_SESSION as $key => $value)
            if (!in_array($key, $keys))
                unset($_SESSION[$key]);
    }
    $__AQUA_SESSION = $Database->query("SELECT * FROM `users` WHERE id = ".$_SESSION["id"]);
    unsetExcept(["flash_message"]);
    if (mysqli_num_rows($__AQUA_SESSION))
    {
        foreach (mysqli_fetch_assoc($__AQUA_SESSION) as $key => $value)
        {
            $_SESSION[$key] = $value;
        }
    }
}