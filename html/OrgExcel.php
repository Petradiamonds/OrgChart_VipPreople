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
        <nav class="navbar navbar-expand-lg navbar-light bg-light p-0 no-print">
            <div class="container-fluid">
                <img class="py-1" style="width: 80px;" src="img/Logo.png" alt="Logo">
                <!-- Selections -->
                <ul class="navbar-nav  pt-3">
                    <li class="nav-item pr-1">
                    <label class="btn btn-lg bg-info float-left align-middle">
                        <span class="align-middle pr-1 fas fa-file-import"></span>Import file
                        <input type="file" id="file-input" hidden>
                    </label>
                    </li>
                </ul>
                <!-- Buttons -->
                <ul class="navbar-nav">
                    <li class="nav-item pr-1">
                        <button class="btn btn-sm btn-primary" onclick="sPrint()"><span class='icon'>
                                <i class="fa fa-print" aria-hidden="true"></i> Print </span></button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="chart_div"></div>
        <?php echo  "<script>let root_json='" . escapeshellcmd( file_get_contents('OrgDataBlank.json')) . "';</script>"; ?>
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
    <script src="js/xlsx.full.min.js"></script>
    <script src="js/importOrg.js"></script>

    </html>