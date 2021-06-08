google.charts.load('current', {
    packages: ["orgchart"]
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = new google.visualization.DataTable();
    var dataRows = JSON.parse(root_json);

    data.addColumn('string', 'Id');
    data.addColumn('string', 'PId');
    data.addColumn('string', 'ToolTip');
    data.addRows(dataRows);

    // Create the chart.
    var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    var options = {
        'allowCollapse': true,
        'allowHtml': true,
        'size': 'small' //'small', 'medium' or 'large'
    };
    chart.draw(data, options);
}