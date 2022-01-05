<?php
require_once('components/header.php');
?>

<div id="parent">
    <div id="message">

    </div>
    <div id="divLogin">
        <form action="" method="post" id="login">
            <h1>Login</h1>
            <input type="hidden" id="dateNtime" name="dateNTime" value="<?= date('Y-m-d H:i') ?>">
            <input type="text" class="logInput" name="username" placeholder="Username" id="uname"> <br>
            <input type="password" class="logInput" name="password" placeholder="Password" id="pwd" autocomplete="on">
            <br>
            <button type="button" id="loginBtn">SIGN IN</button>

        </form>
    </div>
</div>
</div>
<?php

require_once('components/footer.php');
if (isset($_GET['logout'])) {
    session_destroy();
    session_unset();
    //exit(print_r($_SESSION['accountInfo']));
    header("Location: index.php");
}
// remove all session variables


// destroy the session
// session_destroy();

?>
<script src="js/loginScript.js"></script>