<?php
unset($_SESSION['exwid']);
unset($_SESSION['exwpassword']);
unset($_SESSION['username']);
header('Location: /');
exit();
?>