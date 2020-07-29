$('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});


$('#date-range').eq(0).daterangepicker({
    opens: 'right'
});

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChartBalance);
google.charts.setOnLoadCallback(drawChart);

function drawChartBalance() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Balance'],
        ['2004',  1000],
        ['2005',  1170],
        ['2006',  660],
        ['2007',  1030]
    ]);

    var options = {
        title: 'Balance',
        curveType: 'function',
        legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart_balance'));

    chart.draw(data, options);
}



function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Orders', 'Shipments'],
        ['2004',  1000,      400],
        ['2005',  1170,      460],
        ['2006',  660,       1120],
        ['2007',  1030,      540]
    ]);

    var options = {
        title: 'Orders and Shipments',
        curveType: 'function',
        legend: { position: 'bottom' }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}
