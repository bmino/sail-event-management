<?php
session_start();
if ($_SESSION['admin'] != 0 and $_SESSION['admin'] != 1 and $_SESSION['admin'] != 37) {printf("<script>location.href='/bf/index.php'</script>"); exit;}
?>