<?php
// require_once('components/header.php');
// require_once('database/dbconn.php');
require_once('accounts.php');
require_once('components/nav.php');
$database = new Database();
$conn = $database->getConnection();
$historyLogs = new historyLogs($conn);
$accounts = new Accounts($conn);

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
    <div class="panel">

        <div class="panel-filter">
            <!-- <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. At rerum aut odio officia aliquid voluptatem.</p> -->
            <fieldset class='filter-fieldset'>
                <legend>Filter by
                </legend>
                <!-- <span>USER</span> -->
                <div>
                    <?php
                    $result = $accounts->getUsers();
                    $stmt = $result->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($stmt as $row) : ?>
                        <input type="radio" name="HistoryLogsRadio_user" id="<?= $row['username'] ?>" value="<?= $row['username'] ?>" onclick="getHistoryLogFieldsetValue(this)">
                        <label for="<?= $row['username'] ?>"><?= $row['username'] ?></label> <br>
                    <?php


                    endforeach;
                    ?>

                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>SHOW data</legend>
                <input type="radio" class="showData" name="showData" id="" onclick="getHistoryLogFieldsetValue(this)">
                <label for="">ALL</label><br>
                <input type="radio" class="showData" name="showData" id="" value="select" onclick="showSelect(this)">
                <label for="">Select date</label>
                <div style="display:none;" id="selectDate">
                    <span>FROM</span>
                    <input type="date" class="showData" name="showDateFrom" id="dateFrom" max="" onchange="getHistoryLogFieldsetValue(this)">
                    <br>
                    <span>TO</span>
                    <input type="date" class="showData" name="showDateTo" id="dateTo" max="" onchange="getHistoryLogFieldsetValue(this)">
                </div>
            </fieldset>
        </div>
        <div class="panel-historylog">
            <div>
                <div>
                    <h1 style="display:flex; justify-content:center;">HISTORY LOGS</h1>
                </div>
            </div>
            <div class="historyLogs">
                <div id="data"></div>
            </div>
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