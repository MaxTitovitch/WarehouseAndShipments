$('.input-daterange input').each(function() {
    $(this).datepicker('clearDates');
});

google.charts.load('current', {'packages':['corechart']});
getChartData();

function convertDate (date) {
    date = new Date(date);
    let month = (date.getMonth() + 1) + ''
    let day = date.getDate() + ''
    return `${month.length !== 1 ? month : '0' + month}/${day.length !== 1 ? day : '0' + day}/${date.getFullYear()}`;
}

function getChartData (dates = null) {
    $.ajax({
        data: dates,
        type: 'GET',
        url: `/api/chart-data`,
        success: (data) => {
            if(Object.keys(data.balance).length > 0 && Object.keys(data.ordersShipments).length > 0) {
                showChartBalance(data)
                showChart(data);
                $('#date-range')[0].value = data.dates.date_start + ' - ' + data.dates.date_end;
                $('#date-range').eq(0).daterangepicker({
                    opens: 'right',
                    startDate: convertDate(data.dates.date_start),
                    endDate: convertDate(data.dates.date_end)
                }, function (start, end, label) {
                    $('#date-range')[0].value = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                    getChartData({
                        date_start: $('#date-range')[0].value.split(' - ')[0],
                        date_end: $('#date-range')[0].value.split(' - ')[1],
                    });
                });
            }
        },
        error: (data) => {
            console.log(data)
        }
    })
}

function showChartBalance (data) {
    google.charts.setOnLoadCallback(drawChartBalance);
    let balance = data.balance;

    function drawChartBalance () {
        let arrayData = [['Date', 'Balance']];
        Object.keys(balance).forEach((key) => {
            arrayData.push([
              key, balance[key]
            ]);
        })
        var data = google.visualization.arrayToDataTable(arrayData);

        var options = {
            title: 'Balance',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart_balance'));

        chart.draw(data, options);
    }
}

function showChart (data) {
    google.charts.setOnLoadCallback(drawChart);
    let ordersShipments = data.ordersShipments;

    function drawChart() {
        let arrayData = [['Date', 'Orders', 'Shipments']];
        Object.keys(ordersShipments).forEach((key) => {
            arrayData.push([
                key, ordersShipments[key]['orders'],ordersShipments[key]['shipments']
            ]);
        })
        var data = google.visualization.arrayToDataTable(arrayData);

        var options = {
            title: 'Orders and Shipments',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
}
