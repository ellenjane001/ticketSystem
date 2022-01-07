<?php
require_once('components/header.php');
require_once('components/nav.php');
if (!(isset($_SESSION['accountInfo']))) {
    echo "Please <a href=index.php>login</a> to use this page ";
    exit;
}
if ($_SESSION['accountInfo']['type'] != 'admin') {
    echo " <script>window.location.assign('home.php')</script>";
    exit;
}

require_once('viewTickets.php'); ?>


<?php
require_once('components/footer.php');
?>
<script>
    function remove() {
        var element = document.getElementById("home");
        element.classList.remove("active");
        var element = document.getElementById("createTickets");
        element.classList.remove("active");
        var element = document.getElementById("viewTickets");
        element.classList.remove("active");
    }

    function CSSchange() {
        var element = document.getElementById("reports");
        element.classList.add("active");
    }

    remove();
    CSSchange();
</script>