<?php
require_once('components/header.php');
// require_once('database/dbconn.php');
require_once('accounts.php');
require_once('components/nav.php');
$database = new Database();
$conn = $database->getConnection();
$historyLogs = new historyLogs($conn);


if (!(isset($_SESSION['accountInfo']))) {
    echo "Please <a href=index.php>login</a> to use this page ";
    exit;
}

if ($_SESSION['accountInfo']['type'] != 'admin') {
    echo " <script>window.location.assign('home.php')</script>";
    exit;
}
?>

<div class="main">
    <div class="view">
        <div>
            <div>
                <h1 style="display:flex; justify-content:center;">HISTORY LOGS</h1>
            </div>
        </div>
        <div class="historyLogs">
            <?php

            $query = $historyLogs->showHistoryLogs();
            $row = $query->fetchAll(PDO::FETCH_ASSOC);
            // exit(print_r($row));
            $temp = "<table class='historyLogs-table'>";
            $temp .= "<thead>";
            $temp .= "<tr>";
            $temp .= "<th class='id'>id</th>";
            $temp .= "<th>user</th>";
            $temp .= "<th>event</th>";
            $temp .= "<th>date and time</th></tr>";
            $temp .= "</thead>";
            $temp .= "<tbody>";
            foreach ($row as $data) :
                $temp .= "<tr >";

                $temp .= "<td class='id'>" . $data["id"] . "</td>";
                $temp .= "<td >" . $data["user"] . "</td>";
                $temp .= "<td >" . $data["event"] . "</td>";
                $temp .= "<td >" . $data["dateNtime"] . "</td>";
                $temp .= "</tr>";
            endforeach;
            $temp .= "</tbody>";
            $temp .= "</table>";
            echo $temp;
            ?>
        </div>
    </div>
</div>
</div>
<?php

require_once('components/footer.php'); ?>
<script>
    function remove() {
        var element = document.getElementById("home");
        element.classList.remove("active");
        var element = document.getElementById("createTickets");
        element.classList.remove("active");
        var element = document.getElementById("viewTickets");
        element.classList.remove("active");
        var element = document.getElementById("reports");
        element.classList.remove("active");
    }

    function CSSchange() {
        var element = document.getElementById("historyLogs");
        element.classList.add("active");
    }

    remove();
    CSSchange();
</script>