<?php
require_once('components/header.php');
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
            <div class="panel-filter-child1"></div>
            <div class="panel-filter-child2">
                <fieldset class='filter-fieldset'>
                    <legend>Filter by
                    </legend>

                    <div>
                        <?php
                        $result = $accounts->getUsers();
                        $stmt = $result->fetchAll(PDO::FETCH_ASSOC); ?>

                        <span>USER</span>
                        <br>
                        <?php foreach ($stmt as $row) : ?>
                            <input type="radio" name="HistoryLogsRadio_user" class="select" id="<?= $row['username'] ?>" value="<?= $row['username'] ?>" onclick="getHistoryLogFieldsetValue(this), showSelect(this)">
                            <label for="<?= $row['username'] ?>"><?= $row['username'] ?></label> <br>

                            <div style="display:none;" id="selectDate">
                                <span>FROM</span>
                                <br>
                                <?php
                                $style = "width: 150px";
                                ?>
                                <input style="<?= $style ?>" type="date" class="showData" name="showDateFrom" id="dateFrom" max="" onchange="getHistoryLogFieldsetValue(this)">
                                <input type="time" class="showData" name="showTimeFrom" id="timeFrom" max="" onchange="getHistoryLogFieldsetValue(this)">
                                <br>
                                <span>TO</span>
                                <br>
                                <input style="<?= $style ?>" type="date" class="showData" name="showDateTo" id="dateTo" max="" onchange="getHistoryLogFieldsetValue(this)">
                                <input type="time" class="showData" name="showTimeTo" id="timeTo" max="" onchange="getHistoryLogFieldsetValue(this)">
                                <br>
                            </div>

                        <?php


                        endforeach; ?>
                        <span>EVENT</span>
                        <?php

                        $results = $historyLogs->selectEvents();
                        $stmts = $results->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($stmts as $data) : ?>

                            <br>
                            <input type="radio" name="HistoryLogsRadio_event" id="" value="<?= $data['event'] ?>" onclick="getHistoryLogFieldsetValue(this)">
                            <label for=""><?= $data['event'] ?></label>
                        <?php
                        endforeach; ?>


                    </div>

                </fieldset>

            </div>


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