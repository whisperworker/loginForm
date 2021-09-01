<?php
require_once 'core/config.php';
require_once 'core/function.php';

$conn = connect();

if(isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = mysqli_query($conn, "SELECT id, password FROM users WHERE email='$email' LIMIT 1");
    $row = mysqli_fetch_assoc($query);
    $rowId = $row['id'];
    if($row['password'] == md5($_POST['password'])) {
        $hash = generateHash(32);
        mysqli_query($conn, "UPDATE users SET hash= '$hash' WHERE  id= '$rowId'");
        setcookie('id', $row['id'], time() + 30*24*60*60);
        setcookie('hash', $hash, time() + 30*24*60*60);
        header("Location: admin.php");
        exit();
    }
    else {

    }
}

?>

<form action="" method="post">
    <div>
        email: <input type="email" name="email">
    </div>
    <div>
        password:<input type="password" name="password">
    </div>
    <input type="submit" value="login">
</form>

