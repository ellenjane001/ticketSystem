<?php
require_once('components/header.php');
require_once('database/dbconn.php');
require_once('accounts.php');
require_once('tickets.php');
require_once('components/nav.php');
if (!(isset($_SESSION['accountInfo']))) {
    echo "Please <a href=index.php>login</a> to use this page ";
    echo " <script>window.location.assign('index.php')</script>";
    exit;
} else {
    if ($_SESSION['accountInfo']['type'] != 'admin') {
        echo "<script>
    // console.log('hey');
    let element= document.getElementById('reports');
    element.classList.add('hide');
    let element2= document.getElementById('historyLogs');
    element2.classList.add('hide');
    </script>";
        if ($_SESSION['accountInfo']['type'] != 'it') {
            echo "<script>
    // console.log('hey2');
    let element4= document.getElementById('viewTickets');
    element4.classList.add('hide');
    window.location.assign('createTicket.php')
    </script>";
        }
    }
}

$database = new Database();
$conn = $database->getConnection();

$accounts = new Accounts($conn);
$result = $accounts->getCount();
$stmt = $result->fetch(PDO::FETCH_OBJ);

// --------------------------------

$data = new TicketMonitoring($conn);
$result1 = $data->getCount();
$data1 = $result1->fetch(PDO::FETCH_OBJ);

$val = array("OPEN", "RESOLVED", "PENDING");
?>
<div class="container main">
    <div class="header">
        <h1 style="text-align:center;">DASHBOARD</h1>
    </div>
    <div class="panel1">
        <!-- <div class="textFormat col-1">
         <h2>Hi <?php echo $_SESSION['accountInfo']['username'] ?>!</h2>
        <p id="time"></p>
    </div> -->
        <div class="textFormat col-2">
            <h2>Number of Users</h2>
            <h2 id="usercount"><?php echo $stmt->total; ?></h2>
        </div>
        <div class="textFormat col-3">
            <h2>Number of Tickets</h2>
            <h2><?php echo $data1->total; ?></h2>
        </div>
    </div>
    <div class="panel2">
        <div class="textFormat c1" id="donut_single">
            <h2>Chart</h2>
        </div>
        <div class="textFormat c2">
            <h2>Open</h2>
            <p id="open" class="dashboard-items">
                <strong><?php $status = $data->getTicketCount($val[0]);
                        $stat = $status->fetch(PDO::FETCH_OBJ);
                        echo $stat->total; ?></strong>
            </p>
        </div>
        <div class="textFormat c3">
            <h2>Resolved</h2>
            <p id="resolved" class="dashboard-items">
                <strong><?php $status = $data->getTicketCount($val[1]);
                        $stat = $status->fetch(PDO::FETCH_OBJ);
                        echo $stat->total; ?></strong>
            </p>
        </div>
        <div class="textFormat c4">
            <h2>Pending</h2>
            <p id="pending" class="dashboard-items">
                <strong><?php $status = $data->getTicketCount($val[2]);
                        $stat = $status->fetch(PDO::FETCH_OBJ);
                        echo $stat->total; ?></strong>
            </p>
        </div>
    </div>
</div>

</div>
<?php

require_once('components/footer.php'); ?>
<script src="js/chart.js"></script>