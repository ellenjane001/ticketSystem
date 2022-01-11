<?php
require_once('components/header.php');
require_once('database/dbconn.php');
require_once('accounts.php');
require_once('components/nav.php');


$database = new Database();
$conn = $database->getConnection();

$accounts = new Accounts($conn);
if (!(isset($_SESSION['accountInfo']))) {
    echo "Please <a href=index.php>login</a> to use this page ";
    echo " <script>window.location.assign('index.php')</script>";
    exit;
}
if ($_SESSION['accountInfo']['type'] != 'admin') {
    //echo " <script>window.location.assign('home.php')</script>";

    echo "<script>
    console.log('hey');
    let element= document.getElementById('reports');
    element.classList.add('hide');
    let element2= document.getElementById('historyLogs');
    element2.classList.add('hide');
    </script>";
}
?>
<div class="main">
    <div class="view">
        <div class="view-header">
            <h1 style="display:flex; justify-content:center;">TICKETS</h1>
        </div>
        <div class="wrapper">
            <div id="filterSection" class="filter items item1">
                <input type="search" placeholder="search" name="search" id="query" onkeyup="getValue(this)">
                <br>
                <fieldset id="filterField">
                    <legend><img class="wrapper-image" src="srcs/icons8-filter-16.png"> Filter </legend>

                    <input type="radio" value="0" name="filterField" id="ticketStat" onclick="showRadio(this)">
                    <label for="ticketStat" id='labelStat'> <img class="wrapper-image" src="srcs/icons8-combi-ticket-30.png" alt=""> Ticket Status</label>
                    <div id="valueStat" class="values">
                        <input type="radio" name="ticketStatRadio" id="open" value="open" onclick="getValue(this)">
                        <label for=" open">open</label><br>
                        <input type="radio" name="ticketStatRadio" id="pending" value="pending" onclick="getValue(this)">
                        <label for=" pending">pending</label><br>
                        <input type="radio" name="ticketStatRadio" id="resolved" value="resolved" onclick="getValue(this)">
                        <label for=" resolved">resolved</label><br>
                    </div>
                    <br>
                    <input type="radio" value="1" name="filterField" id="ticketCtgry" onclick="showRadio(this)">
                    <label for="ticketCtgry" id="labelCtgry"><img class="wrapper-image" src="srcs/icons8-combi-ticket-30.png" alt=""> Ticket Category</label>
                    <div id="valueCategory" class="values">
                        <input type="radio" name="radioCtgry" id="hardware" value="hardware" onclick="getValue(this) ">
                        <label for="hardware">hardware</label><br>
                        <input type="radio" name="radioCtgry" id="software" value="software" onclick="getValue(this)">
                        <label for="software">software</label><br>
                        <input type="radio" name="radioCtgry" id="network" value="network" onclick="getValue(this)">
                        <label for=" network">network</label><br>
                        <input type="radio" name="radioCtgry" id="email" value="email" onclick="getValue(this)">
                        <label for=" email">email</label><br>
                        <input type="radio" name="radioCtgry" id="maintenance" value="maintenance" onclick="getValue(this)">
                        <label for=" maintenance">maintenance</label><br>
                        <input type="radio" name="radioCtgry" value="0" id="other" onclick="showOther(this)">
                        <label for="other">other</label><br>
                        <!-- <form method="post" action="" id="textOther" style="display: none; align-content:center;"> -->
                        <input type="text" name="radioCtgry" id="textOther" style="display: none;" placeholder="type here" onkeyup="getValue(this)"><br>

                    </div>
                    <br>
                    <input type="radio" value="2" name="filterField" id="ticketPriority" onclick="showRadio(this)">
                    <label for="ticketPriority" id="labelPriority"><img class="wrapper-image" src="srcs/icons8-combi-ticket-30.png" alt=""> Ticket Priority</label>
                    <div id="valuePriority" class="values">
                        <input type="radio" name="ticketPrior" id="low" value="low" onclick="getValue(this)">
                        <label for="low">low</label><br>
                        <input type="radio" name="ticketPrior" id="medium" value="medium" onclick="getValue(this)">
                        <label for="medium">medium</label><br>
                        <input type="radio" name="ticketPrior" id="high" value="high" onclick="getValue(this)">
                        <label for="high">high</label><br>
                    </div>
                </fieldset>
                <fieldset id="sortField">
                    <legend> <img class="wrapper-image" src="srcs/icons8-sort-24.png" alt=""> SORT BY</legend>
                    <div id="valueAsc" class="">
                        <input type="radio" name="sort" id="asc" value="asc" onclick="getValue(this)">
                        <label for="asc">Ascending</label> <br>
                        <input type="radio" name="sort" id="desc" value="desc" onclick="getValue(this)">
                        <label for="desc">Descending</label>
                    </div>
                </fieldset>
                <fieldset>
                    <legend> <img class="wrapper-image" src="srcs/icons8-user-24.png" alt="">user</legend>
                    <div>
                        <?php
                        $result = $accounts->getUsers();
                        $stmt = $result->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($stmt as $row) : ?>
                            <input type="radio" name="userRadio" id="<?= $row['username'] ?>" value="<?= $row['username'] ?>" onclick="getValue(this)">
                            <label for="<?= $row['username'] ?>"><?= $row['username'] ?></label> <br>
                        <?php


                        endforeach;
                        ?>

                    </div>
                </fieldset>

                <button onclick="refresh()">Refresh</button>


            </div>
            <!-- <div class="space"></div> -->
            <div class="tblArea items">
                <div>
                    <div id="message"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
require_once('components/footer.php');
?>
<script>
    function remove() {
        var element = document.getElementById("home");
        element.classList.remove("active");
        var element = document.getElementById("createTickets");
        element.classList.remove("active");
    }

    function CSSchange() {
        var element = document.getElementById("viewTickets");
        element.classList.add("active");
    }

    remove();
    CSSchange();
</script>