<?php
require_once 'core/config.php';
require_once 'core/function.php';

$conn = connect();
if(isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    $cookieId = $_COOKIE['id'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE id='$cookieId' LIMIT 1");
    $user = mysqli_fetch_assoc($query);
    if($user['hash'] !== $_COOKIE['hash']) {
        mysqli_query($conn, "UPDATE users SET hash= '$hash' WHERE  id= '$rowId'");
        setcookie('id', $rowId, time() - 10, "/");
        setcookie('hash', $hash, time() - 10, "/");
        header("Location: login.php");
    }
}
else {
    setcookie('id', $rowId, time() - 10, "/");
    setcookie('hash', $hash, time() - 10, "/");
    header("Location: login.php");
}

?>

<h1>Admin Panel</h1>
<?php
echo "Welcome ".$user['login'];
?>
<p><a href="logout.php">Logout</a></p>
