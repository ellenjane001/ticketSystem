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
} ?>

<div class="main">
    <div class="view">
        <div class="header">
            <h1 style="display:flex; justify-content:center;">REPORTS</h1>
        </div>
        <div class="wrapper">
            <div class="filter items item1">
                <input type="search" placeholder="search" name="search" id="query" onkeyup="getValue(this)">
                <br>
                <fieldset>
                    <legend>Filter</legend>

                    <input type="radio" value="0" name="filterField" id="ticketStat" onclick="showRadio(this)">
                    <label for="ticketStat" id='labelStat'>Ticket Status</label>
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
                    <label for="ticketCtgry" id="labelCtgry">Ticket Category</label>
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
                        <input type="text" name="other" id="textOther" style="display: none;" placeholder="type here" onkeyup="getValue(this)"><br>
                        <!-- <input type="submit" id="btn"> -->
                        <!-- </form> -->
                        <?php
                        // exit
                        // (print_r($_POST['other']));
                        ?>
                    </div>
                    <br>
                    <input type="radio" value="2" name="filterField" id="ticketPriority" onclick="showRadio(this)">
                    <label for="ticketPriority" id="labelPriority">Ticket Priority</label>
                    <div id="valuePriority" class="values">
                        <input type="radio" name="ticketPrior" id="low" value="low" onclick="getValue(this)">
                        <label for="low">low</label><br>
                        <input type="radio" name="ticketPrior" id="medium" value="medium" onclick="getValue(this)">
                        <label for="medium">medium</label><br>
                        <input type="radio" name="ticketPrior" id="high" value="high" onclick="getValue(this)">
                        <label for="high">high</label><br>
                    </div>
                </fieldset>


                <button onclick="refresh()">Refresh</button>


            </div>
            <!-- <div class="space"></div> -->
            <div class="tblArea items">
                <div>
                    <div id="message"></div>
                </div>
                <div class="space"></div>
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