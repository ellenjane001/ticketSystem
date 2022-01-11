const block = 'block'
const none = 'none'
const bold = 'bold'
const normal = 'normal'


let username = document.getElementById("user").value;
let dateNtime = document.getElementById("dateNtime").value;
let action = 'Logged out';
let val = 'logout';

function logout() {

    $.ajax({
        url: "accounts.php",
        method: "post",
        data: {
            username: username,
            requestType: val,
            dateNtime: dateNtime,
            action: action
        },
        datatype: JSON,
        success: function (data) {
            console.log(data);
            $('#stat').html(data);
        }

    });
}

function dropDown() {
    document.getElementById("dropDown").classList.toggle("show");
}
function refresh() {
    document.body.style.cursor = 'progress';

    var timer = setTimeout(function () {
        location.reload();
    }, 100)

}
function showRadio(radioStat) {
    var selectedValue = radioStat.value;

    if (selectedValue === "0") {
        document.getElementById("valueStat").style.display = block;
        document.getElementById("valueCategory").style.display = none;
        document.getElementById("valuePriority").style.display = none;
        document.getElementById('labelStat').style.fontWeight = bold;
        document.getElementById('labelCtgry').style.fontWeight = normal;
    } else if (selectedValue === "1") {
        document.getElementById("valueCategory").style.display = block;
        document.getElementById("valuePriority").style.display = none;
        document.getElementById("valueStat").style.display = none;
        document.getElementById('labelCtgry').style.fontWeight = bold;
        document.getElementById('labelStat').style.fontWeight = normal;
        document.getElementById('labelPriority').style.fontWeight = normal;
    } else {
        document.getElementById("valuePriority").style.display = block;
        document.getElementById("valueCategory").style.display = none;
        document.getElementById("valueStat").style.display = none;
        document.getElementById('labelCtgry').style.fontWeight = normal;
        document.getElementById('labelStat').style.fontWeight = normal;
        document.getElementById('labelPriority').style.fontWeight = bold;
    }
}

function showOther(radioOther) {
    var selectedValue = radioOther.value;
    if (selectedValue === "0") {
        document.getElementById('textOther').style.display = block;
    } else {
        document.getElementById('textOther').style.display = none;
    }
}

function showInput(radioOther) {
    var selectedValue = radioOther.value;
    if (selectedValue === "other") {
        document.getElementById('otherCtgry').style.display = block;
    } else if (selectedValue === "") {
        document.getElementById('otherCtgry').style.display = none;
    }
}
function showSelect(radioSelect) {
    var selectedValue = radioSelect.classList[0];
    if (selectedValue === "select") {
        document.getElementById('selectDate').style.display = block;
    } else if (selectedValue === "") {
        document.getElementById('selectDate').style.display = none;
    }
}

function exportToCSV(value) {
    let uName = value.classList[0];

    let date = document.getElementById('dateNTime').value;
    console.log(date);
    $.ajax({
        url: "tickets.php",
        method: "POST",
        data: {
            username: uName,
            dateNTime: date,
            action: "exported data to CSV",
            request_type: 'print',
        },
        success: function (data) {
            $('#message').html(data);
            var timer = setTimeout(function () {
                location.reload();
            }, 100)

        }
    })
}

function sendInput(value) {

    let x = document.getElementById('selectOther');
    x.value = value.value;
}
let query = '';
let stat = '';
let rCategory = '';
let priority = '';
let sort = '';
let show = '';
let user = '';

function loadData(value, value1) {
    // console.log(value, value1);
    $.ajax({
        url: "tickets.php",
        method: "POST",
        data: {
            query: value,
            stat: value,
            category: value,
            priority: value,
            sort: value,
            user: value,
            show: value1,
            request_type: 'show',
        },
        success: function (data) {
            $('#message').html(data);

        }
    });
}
function getValue(value) {

    if (value === undefined) {
        type = 'show';
        loadData(type);
    }
    else {
        if (value.name === "search") {
            query = value.value;
            show = 'search';
            // console.log('hey')
            loadData(query, show)
        } else if (value.name === "ticketStatRadio") {
            stat = value.value;
            show = 'status';
            loadData(stat, show)
        } else if (value.name === 'radioCtgry') {
            rCategory = value.value;
            show = 'category';
            loadData(rCategory, show)
        } else if (value.name === 'ticketPrior') {
            priority = value.value;
            show = 'priority';
            loadData(priority, show)
        } else if (value.name === 'sort') {
            sort = value.value;
            show = 'sort';
            loadData(sort, show);
        } else if (value.name === 'userRadio') {
            // console.log(value.value);
            user = value.value;
            show = 'user';
            loadData(user, show);
        }
    }
}

function getHistoryLogFieldsetValue(value) {
    var datePickerFrom = document.querySelector('#dateFrom');
    var timePickerFrom = document.querySelector('#timeFrom');

    var datePickerTo = document.querySelector('#dateTo')
    var timePickerTo = document.querySelector('#timeTo')

    if (value === undefined) {
        loadAccounts();
    } else {
        // console.log(value.classList[0]);
        if (value.name === 'HistoryLogsRadio_user') {
            params = value.value

            show = 'userHistory';

            loadAccounts(show, params);
        } else if (value.classList[0] === 'showData') {

            let dateTimeFrom = datePickerFrom.value + " " + timePickerFrom.value + ":00";
            let dateTimeTo = datePickerTo.value + " " + timePickerTo.value + ":00"
            let date_time_from = dateTimeFrom.toString();
            let date_time_to = dateTimeTo.toString();
            // console.log(params)
            show = 'select'
            loadAccounts(show, date_time_from, date_time_to, params);
            // }
        } else if (value.name === 'HistoryLogsRadio_event') {
            console.log(value.value)
            parameter = value.value;
            show = 'eventHistory'
            loadAccounts(show, parameter, params)
        }


        else {
            console.log('no value')
        }
    }
}
function loadAccounts(value, datefrom, dateto, user) {
    // console.log(value1)
    $.ajax({
        url: "accounts.php",
        method: "POST",
        data: {
            query: value,
            search: datefrom,
            search2: dateto,
            searchUser: user,
            request: 'user'
        },
        success: function (data) {
            $('#data').html(data);
        }
    });
}

function back() {
    window.location.href = 'viewTickets.php';
}

function pageLoader(value) {

    console.log(value.id);
    let id = value.id;
    let url = 'viewSingleTicket.php?'
    var id_get = 'id=' + id;
    window.location.href = url + id_get;


}

function createTicket() {
    var ticketNumber = document.getElementById('ticketNumber').value;
    var user = document.getElementById('user').value;
    var problem = document.getElementById("issue").value;
    var category = document.getElementById("category").value;
    var priority = document.getElementById("priority").value;
    var dateNTime = document.getElementById("dateNTime").value;
    var assignedTo = document.getElementById("assignedTo").value;
    var due = document.getElementById("due").value;
    console.log(ticketNumber, problem, category, priority,
        assignedTo, due);

    if (problem === ""
        || category === ""
        || priority === "") {
        console.log("must input request values")
        var timer = setTimeout(function () {
            location.reload();
        }, 300)
    } else {
        $.ajax({
            url: "tickets.php",
            method: "post",
            data: {
                ticketNum: ticketNumber,
                username: user,
                problem: problem,
                category: category,
                priority: priority,
                dateNTime: dateNTime,
                assignedTo: assignedTo,
                due: due,
                status: 'open',
                action: 'added new ticket',
                request_type: 'create'
            },
            datatype: JSON,
            success: function (data, status) {
                console.log(status);
                $('.message').html(data);
                $('#submitBtn').prop('disabled', true);
                $('#priority').prop('disabled', true);
                $('#ticketNumber').prop('disabled', true);
                $('#issue').prop('disabled', true);
                $('#assignedTo').prop('disabled', true);
                $('#due').prop('disabled', true);
                $('#category').prop('disabled', true);
                var timer = setTimeout(function () {
                    location.reload();
                }, 1500)
            }


        })
    }
}

function formEnabler() {
    $('#priority').prop('disabled', false);
    $('#ticketNumber').prop('disabled', false);
    $('#issue').prop('disabled', false);
    $('#assignedTo').prop('disabled', false);
    $('#due').prop('disabled', false);
    $('#submitBtn').prop('disabled', false);
}


$(document).ready(function () {
    $('#priority').prop('disabled', true);
    $('#ticketNumber').prop('disabled', true);
    $('#issue').prop('disabled', true);
    $('#assignedTo').prop('disabled', true);
    $('#due').prop('disabled', true);
    $('#submitBtn').prop('disabled', true);

    getValue();
    getHistoryLogFieldsetValue();
    // loadTicketLogs();

    $('#submitBtn').click(function (e) {

        e.preventDefault();
        var ticketNumber = document.getElementById('ticketNumber').value;
        var user = document.getElementById('user').value;
        var problem = document.getElementById("issue").value;
        var category = document.getElementById("category").value;
        var priority = document.getElementById("priority").value;
        var dateNTime = document.getElementById("dateNTime").value;
        var assignedTo = document.getElementById("assignedTo").value;
        var due = document.getElementById("due").value;
        console.log(ticketNumber, problem, category, priority,
            assignedTo, due);

        if (problem === ""
            || category === ""
            || priority === "") {
            console.log("must input request values")
            var timer = setTimeout(function () {
                location.reload();
            }, 300)
        } else {
            $.ajax({
                url: "tickets.php",
                method: "post",
                data: {
                    ticketNum: ticketNumber,
                    username: user,
                    problem: problem,
                    category: category,
                    priority: priority,
                    dateNTime: dateNTime,
                    assignedTo: assignedTo,
                    due: due,
                    status: 'open',
                    action: 'added new ticket',
                    request_type: 'create'
                },
                datatype: JSON,
                success: function (data, status) {
                    console.log(status);
                    $('.message').html(data);
                    $('#submitBtn').prop('disabled', true);
                    $('#priority').prop('disabled', true);
                    $('#ticketNumber').prop('disabled', true);
                    $('#issue').prop('disabled', true);
                    $('#assignedTo').prop('disabled', true);
                    $('#due').prop('disabled', true);
                    $('#category').prop('disabled', true);
                    var timer = setTimeout(function () {
                        location.reload();
                    }, 1500)
                }


            })
        }
    });
})