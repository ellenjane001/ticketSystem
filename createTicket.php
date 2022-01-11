<?php
require_once('components/header.php');
require_once('database/dbconn.php');
require_once('components/nav.php');
require_once('tickets.php');
$database = new Database();
$conn = $database->getConnection();
$tickets = new TicketMonitoring($conn);
$val = $_SESSION['accountInfo']['username'];
// exit(print_r($_SESSION['accountInfo']['type']));
// $ticket=$tickets->getUserTicketCount()
?>

<div class="main">
    <div class="message"></div>
    <div class="panel">
        <div id="userDashboard" class="panel-userDashboard">
            <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio quasi, dolorum fugiat provident doloribus in!</p> -->
            <div class="panel-userDashboard-header">
                <h1> Hi <?php echo $_SESSION['accountInfo']['username'] ?></h1>
            </div>
            <div class="panel-userDashboard-item">
                <span>Tickets</span>
                <br>
                <span><?php
                        $allStat = 'all';
                        $allTickets = $tickets->getUserTicketCount($allStat, $val);
                        $row = $allTickets->fetch(PDO::FETCH_OBJ);
                        echo $row->total;
                        ?></span>
            </div>
            <div class="panel-userDashboard-content">

                <div class="panel-userDashboard-content-col">
                    <span>Open</span>
                    <br>
                    <p id="open"><?php
                                    $openStat = 'open';
                                    $openTickets = $tickets->getUserTicketCount($openStat, $val);
                                    $row = $openTickets->fetch(PDO::FETCH_OBJ);
                                    echo $row->total;
                                    ?></p>
                </div>
                <div class="panel-userDashboard-content-col">
                    <span>Pending</span>
                    <br>
                    <p id="pending"><?php
                                    $pendingStat = 'pending';
                                    $pendingTickets = $tickets->getUserTicketCount($pendingStat, $val);
                                    $row = $pendingTickets->fetch(PDO::FETCH_OBJ);
                                    echo $row->total;
                                    ?></p>
                </div>
                <div class="panel-userDashboard-content-col">
                    <span>Resolved</span>
                    <br>
                    <p id="resolved"><?php
                                        $resolvedStat = 'resolved';
                                        $resolvedTickets = $tickets->getUserTicketCount($resolvedStat, $val);
                                        $row = $resolvedTickets->fetch(PDO::FETCH_OBJ);
                                        echo $row->total;
                                        ?></p>
                </div>

            </div>
            <div class="panel-userDashboard-content-bottom">
                <div class="panel-userDashboard-content-chart">
                    <div id="donut_single1" class="donut_single1">

                    </div>
                </div>
                <div class="panel-userDashboard-content-bottom-list">
                    <span>List of requests</span>
                    <p>
                    <table class="panel-userDashboard-content-bottom-table">
                        <thead>
                            <tr>
                                <th>TicketNumber</th>
                                <th>problem</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $requestTicket = "tickets";

                            $requestTickets = $tickets->getUserTicketCount($requestTicket, $val);
                            $rows = $requestTickets->fetchAll(PDO::FETCH_ASSOC);
                            // echo $rows;
                            foreach ($rows as $data) : ?>

                                <tr>
                                    <td>
                                        -<?= $data['ticketNumber'] ?>-
                                    </td>
                                    <td>
                                        -<?= $data['issue_problem'] ?>-
                                    </td>
                                    <td>
                                        -<?= $data['status'] ?>-
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                    <!-- <span><?= $total = count($data); ?></span> -->
                    </p>

                </div>
            </div>
        </div>
        <div class="panel-addTicket">
            <div class="panel-addTicket-content">
                <div id="createHeader">
                    <h1 style="font-size: 30px;">Create Request Ticket</h1>
                </div>
                <div class="form">
                    <form method="post">
                        <div>
                            <label for="category">Request Category</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="dateNTime">Date & Time:</label>
                            <input type="hidden" name="dateNtime" id="dateNTime" value="<?= date('Y-m-d H:i') ?>">
                            <span id="dateNTime"><?= date('Y-m-d H:i') ?></span>
                            <br>
                            <select class="dropdown" name="category" id="category" onclick="showInput(this)" onchange="formEnabler()">
                                <option value="" selected disabled>category</option>
                                <option value="hardware">Hardware</option>
                                <option value="software">Software</option>
                                <option value="network">network</option>
                                <option value="email">email</option>
                                <option value="maintenance">maintenance</option>
                                <option id="selectOther" value="other">other</option>
                            </select>
                        </div>
                        <div class="values" id="otherCtgry">
                            <input type="text" name="other" id="other" placeholder="type here" onkeyup="sendInput(this)">
                        </div>

                        <div>
                            <input class="addRequest" id="user" type="hidden" value="<?= $_SESSION['accountInfo']['username'] ?>">
                            <label for="issue">
                                Problem
                            </label><br>

                            <textarea name="issue" id="issue" cols="50" rows="5" placeholder="type problem request here"></textarea>
                        </div>

                        <div>
                            <label for="priority">Priority</label>
                            <select class="dropdown addRequest" name="priority" id="priority">
                                <option value="" selected disabled></option>
                                <option value="high">high</option>
                                <option value="medium">medium</option>
                                <option value="low">low</option>
                            </select>
                            &nbsp;
                            <label for="assignedTo">Assign to</label>
                            <!-- <input class="addRequest" type="text" id="assignedTo"> -->
                            <select class="dropdown addRequest" name="assignedTo" id="assignedTo">
                                <option value="" selected disabled></option>
                                <option value="it1">Tech1</option>
                                <option value="it2">Tech2</option>
                                <option value="it2">Tech3</option>
                            </select>
                        </div>
                        <br>
                        <div> <label for="due">Due date (optional) </label>
                            <input class="addRequest" type="date" id="due">
                            <br>
                        </div>

                        <br>
                        <div><button class="addRequest" type="button" id="submitBtn" onclick="createTicket()">Submit Ticket</button></div> <br>
                        <input type="text" value="<?= uniqid() ?>" id="ticketNumber" style="display: none;">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php
if (!(isset($_SESSION['accountInfo']))) {
    echo "Please <a href=index.php>login</a> to use this page ";
    echo " <script>window.location.assign('index.php')</script>";
    exit;
} else if ($_SESSION['accountInfo']['type'] != 'admin') {

    echo "<script>
    let element= document.getElementById('reports');
    element.classList.add('hide');
    let element2= document.getElementById('historyLogs');
    element2.classList.add('hide');
    </script>";

    if ($_SESSION['accountInfo']['type'] === 'user') {
        echo "<script>
    let element4= document.getElementById('viewTickets');
    element4.classList.add('hide');
    let element5= document.getElementById('home');
    element5.classList.add('hide');
    let div1= document.getElementById('createTickets');
    div1.classList.add('hide');
    let div= document.getElementById('userDashboard');
    div.classList.add('show');
    </script>";
    }
}
require_once('components/footer.php');
?>
<script src="js/addTicket.js"></script>
<script src="js/chart.js"></script>