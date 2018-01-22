<?php session_start();
session_destroy();

die("<script>location.href = 'index.php'</script>");
?>
