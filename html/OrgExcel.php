    <html>
    <head>
        <link rel='stylesheet prefetch'
            href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css'>
        <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto'>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/html2canvas.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" href="css/gChart.css">
    </head>
    <body>
        <?php include_once('nav.html'); ?>
        <div id="chart_div"></div>
        <?php echo  "<script>let root_json='" . escapeshellcmd( file_get_contents('orgdata.json')) . "';</script>"; ?>
        <script type="text/javascript" src="js/gOrg.js"></script>
    </body>

    <script>
function sPrint() {
    let w = document.body.scrollWidth;
    let h = document.body.scrollHeight;
    html2canvas(document.querySelector("#chart_div")).then(canvas => {
        var myWindow = window.open("", "", `width=${w},height=${h}`);
        myWindow.document.body.appendChild(canvas);
    }, {
        width: w,
        height: h
    });
}
    </script>

    </html>