<?php
session_start();
if ($_SESSION['admin'] != 'notify') {printf("<script>location.href='/bf/index.php'</script>"); exit;}
?>