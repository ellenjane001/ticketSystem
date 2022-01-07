
const ticketNum = document.getElementById("ticketNum").value;
const uname = document.getElementById("user").value;
const dateNTime = document.getElementById("dateNtime").value;
let addAction = document.getElementById("addAction").value;
let postStatus = document.getElementById("postStatus").value;
let actionDetails = document.getElementById("actionDetails").value;
let actionBy = document.getElementById("postAction").value;

// console.log('stat' + postStatus, 'ticket' + ticketNum, 'username' + uname, 'date' + dateNTime, 'action' + addAction, 'action details' + actionDetails);
// var postStatus = e.options[e.selectedIndex].value;
// let addAction = document.getElementById



function buttonEnabler() {
    document.getElementById('updateBtn').disabled = false;

}

function updateTicket() {
    let postStatus = document.getElementById("postStatus").value;
    let actionDetails = document.getElementById("actionDetails").value;
    let actionBy = document.getElementById("postAction").value;
    let addAction = document.getElementById("addAction").value;
    let id_num = document.getElementById("id_num").value;
    console.log('stat' + postStatus, 'ticket' + ticketNum, 'username' + uname, 'date' + dateNTime, 'action' + addAction, 'action details' + actionDetails);
    // setValue();
    if (addAction === ""
        || actionDetails === ""
        || postStatus === ""
        || actionBy === "") {
        console.log('input values')
    } else {
        $.ajax({
            url: 'tickets.php',
            method: 'post',
            data: {
                username: uname,
                id: id_num,
                dateNTime: dateNTime,
                ticketNum: ticketNum,
                addAction: addAction,
                actionDetails: actionDetails,
                status: postStatus,
                actionBy: actionBy,
                action: 'Updated Ticket',
                request_type: 'update'
            },
            datatype: JSON,
            success: function (data) {
                $('.data').html(data);
                // addTickets();
                document.getElementById('updateBtn').disabled = true;
                console.log('success')
                // location.reload();
            }
        })
    }
}
document.addEventListener("DOMContentLoaded", function (event) {
    // event.preventDefault();
    // console.log('hi')
    $.ajax({
        url: "tickets.php",
        method: "POST",
        data: {
            id: ticketNum,
            request_type: 'showSingle'
        },
        //dataType: JSON,
        success: function (data) {
            console.log(data)
            $('.message').html(data);
        }
    });
});

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("AddActionBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function () {
    modal.style.display = "block";
    document.getElementById('updateBtn').disabled = true;
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}