let resolved = document.getElementById('resolved').textContent;
let pending = document.getElementById('pending').textContent;
let openTicket = document.getElementById('open').textContent;
let a = parseInt(resolved);
let b = parseInt(pending);
let c = parseInt(openTicket);
// console.log(a, b, c);



google.charts.load('current', { 'packages': ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data = google.visualization.arrayToDataTable([
        ['Tickets', 'Amount given'],
        ['Open', c],
        ['Pending', b],
        ['Resolved', a]
    ]);

    var options = {
        pieHole: 0.5,
        pieSliceTextStyle: {
            color: 'black'
        },
        backgroundColor: { fill: 'transparent' },
        colors: ['#6998ab', '#b1d0e0', '#406882'],
        height: 250,
        width: 375
    };

    var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
    chart.draw(data, options);
}

google.charts.load('current', { 'packages': ['corechart'] });
google.charts.setOnLoadCallback(drawChart1);

function drawChart1() {

    var data1 = google.visualization.arrayToDataTable([
        ['Tickets', 'Amount given'],
        ['Open', c],
        ['Pending', b],
        ['Resolved', a]
    ]);

    var options1 = {
        pieHole: 0.5,
        pieSliceTextStyle: {
            color: 'black'
        },
        backgroundColor: { fill: 'transparent' },
        colors: ['#6998ab', '#b1d0e0', '#406882'],
        height: 325,
        width: 325,
    };


    var chart1 = new google.visualization.PieChart(document.getElementById('donut_single1'));
    chart1.draw(data1, options1);
}