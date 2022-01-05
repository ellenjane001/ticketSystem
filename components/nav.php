<!--nav bar  -->
<div id="stat"></div>
<div class="main-header">
    <div class="user">
        <span id="time" style="font-size: medium;"></span>
        <br>
        <a class="account" style="font-size: medium;"> <?php echo $_SESSION['accountInfo']['username'] ?></a>
        &nbsp; <a id="logout" href="index.php?logout" onclick="logout()">Logout</a>

    </div>
    <div id="navbar">
        <input type="hidden" id="dateNtime" name="date" value="<?= date('Y-m-d H:i') ?>">
        <input type="hidden" id="user" name="username" value="<?= $_SESSION['accountInfo']['username'] ?>">
        <!-- <a id="userHome" href="userHome.php" style="display: none;">Home</a> -->
        <a class="active" href="home.php" id="home">Home</a>
        <a id="createTickets" href="createTicket.php"> Request Ticket</a>
        <a id="viewTickets" href="viewTickets.php">View Tickets</a>
        <a id="reports" href="reports.php">Reports</a>
        <a id="historyLogs" href="historyLogs.php">History Logs</a>

    </div>
</div>
