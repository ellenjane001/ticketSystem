    <?php
    require_once('components/header.php');
    require_once('database/dbconn.php');
    require_once('tickets.php');

    $database = new Database();
    $db = $database->getConnection();
    $id = $_GET['id'];
    // $row = $ticketlog->fetchAll(PDO::FETCH_ASSOC);
    $tickets = new TicketMonitoring($db);
    $ticket = $tickets->getSingleTicket($id);
    $rows = $ticket->fetch(PDO::FETCH_ASSOC);
    ?>

    <!-- <link rel="stylesheet" href="css/tableStyle.css"> -->
    <link rel="stylesheet" href="css/tableStyle.css">

    <div class="parent">
        <div class="columns">
            <button type="button" id="backBtn">Back</button>
            <br>
            <input id="id_num" type="hidden" value="<?= $rows['ticket_id']; ?>">
            <input id="user" type="hidden" value="<?= $_SESSION['accountInfo']['username'] ?>">
            <label for="ticketNum">Ticket Number:</label>
            <input id="ticketNum" type="text" value="<?= $rows['ticketNumber']; ?>" disabled>
            <!-- <span id="ticketNum"><?= $rows['ticketNumber']; ?></span> -->
            <br><label for="status">Status</label>
            <span><?= $rows['status']; ?></span>
            <br>
            <label for="issue">
                ISSUE
            </label>
            <p>
                <?= $rows['issue_problem']; ?>
            </p>
            <button type="button" id="AddActionBtn" style="font-size: 12px;"> &#9536; resolution</button>
        </div>

        <div class="tblArea column">
            <div id="message"></div>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <div id="message"></div>
                <div>
                    <span class="close">&larr;</span>
                    <br>
                    <label for="dateNTime">Date & Time:</label>
                    <input type="hidden" name="dateNtime" id="dateNtime" value="<?= date('Y-m-d H:i') ?>">
                    <span id="dateNTime"><?= date('Y-m-d H:i') ?></span>
                    <!-- <p>Some text in the Modal..</p> -->
                    <br>
                    <label for="addAction">Add Resolution</label>
                    <input type="text" name="addAction" id="addAction" style="border: 0.5px solid;"><br><br>
                    <label for="details">details:</label>
                    <textarea name="details" id="actionDetails" cols="30" rows="3"></textarea>
                    <br>
                    <label for="postStatus">Post Status</label>

                    <select name="postStatus" id="postStatus">
                        <option value="open">Open</option>
                        <option value="resolved">Resolved</option>
                        <option value="pending">Pending</option>
                    </select>

                    <label for="actionBy">Action by</label>
                    <select name="actionBy" id="postAction">
                        <option value="IT1">IT1</option>
                        <option value="IT2">IT2</option>
                        <option value="IT3">IT3</option>
                    </select>

                    <br>
                    <button type="button" id="updateBtn">Submit Ticket</button>
                    <br>
                </div>
            </div>

        </div>
    </div>
    <?php
    require_once('components/footer.php');
    ?>
    <script src="js/updateTicket.js"></script>