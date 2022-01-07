<?php
require_once('components/header.php');



?>
<div class="vst-panel">
    <button type="button" id="backBtn" onclick="back()">Back</button><br>

    <span>TICKET Number</span><input type="text" name="" id="ticketNum" value="<?= $_GET['id'] ?>" disabled> <br>

    <input type="hidden" name="" id="user" value="<?= $_SESSION['accountInfo']['username'] ?>">
    <button type="button" id="AddActionBtn" style="font-size: 12px;"> &#9536; resolution</button>
</div>
<div class="message">

</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="data"></div>
        <div>
            <span class="close">&larr;</span>
            <br>
            <label for="dateNTime">Date & Time:</label>
            <input type="hidden" name="dateNtime" id="dateNtime" value="<?= date('Y-m-d H:i') ?>">
            <span id="dateNTime"><?= date('Y-m-d H:i') ?></span>
            <!-- <p>Some text in the Modal..</p> -->
            <br>
            <label for="addAction">Add Resolution</label>
            <input type="text" name="addAction" id="addAction" style="border: 0.5px solid;" onkeyup="buttonEnabler()"><br><br>
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
            <select name="actionBy" id="postAction" onclick="buttonEnabler()">
                <option value="IT1">IT1</option>
                <option value="IT2">IT2</option>
                <option value="IT3">IT3</option>
            </select>

            <br>
            <button type="button" id="updateBtn" onclick="updateTicket()">Submit Ticket</button>
            <br>
        </div>
    </div>




    <?php
    require_once('components/footer.php');
    ?>
    <script src="js/updateTicket.js"></script>