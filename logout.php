<?php
<<<<<<< Updated upstream
session_start();
=======
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
>>>>>>> Stashed changes
session_destroy();
header("Location: index.php");
exit();
?>
