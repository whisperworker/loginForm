<?php
setcookie('id', $rowId, time() - 10, "/");
setcookie('hash', $hash, time() - 10, "/");
header("Location: login.php");