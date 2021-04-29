<?php
session_start();
if(count($_SESSION) > 0)
{
  foreach($_SESSION as $k => $v)
  {
    unset($_SESSION[$k]);
  }
  session_destroy();

  setcookie("PHPSESSID", "", time()-3600, "/");

  header("Location: index.php");
}
?>