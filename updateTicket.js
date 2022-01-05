$(document).ready(function () {
    let id_num = document.getElementById("id_num").value;
    let ticketNum = document.getElementById("ticketNum").value;
    let user = document.getElementById("user").value;
    let dateNTime = document.getElementById("dateNtime").value;

    load_data();

    function load_data(id) {
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
                $('#message').html(data);
            }
        });

    }




    $('#backBtn').click(function () {
        window.location.href = 'viewTickets.php';
    });

    $('#updateBtn').click(function (e) {
        e.preventDefault();
        //var id = document.getElementById("id_num").value;
        let addAction = document.getElementById("addAction").value;
        let actionDetails = document.getElementById("actionDetails").value;
        let postStatus = document.getElementById("postStatus").value;
        let actionBy = document.getElementById("postAction").value;
        console.log(actionBy);

        $.ajax({
            url: 'tickets.php',
            method: 'post',
            data: {
                user: user,
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
                $('#message').html(data);
                // addTickets();
                location.reload();
            }

        })




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