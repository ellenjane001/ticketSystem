// var element = document.getElementById("createTickets");
// var viewTickets = document.getElementById("viewTickets");
// var reports = document.getElementById("reports");
function remove() {
    var element = document.getElementById("home");
    element.classList.remove("active");
}

function CSSchange() {
    var element = document.getElementById("createTickets");
    element.classList.add("active");
}
remove();
CSSchange();
