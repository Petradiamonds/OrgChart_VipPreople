<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VIP Hierarchy Builder</title>

    <!-- Chrome/android APP settings -->
    <meta name="theme-color" content="#4287f5">
    <link rel="icon" href="img/Icon.png" sizes="192x192">
    <!-- end of Chrome/Android App Settings  -->

    <!-- Bootstrap // you can use hosted CDN here-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- end of bootstrap -->
</head>

<body class="bg-primary">
    <!-- Page Start -->
    <div class="pt-5 container bg-white rounded">

        <!-- NAV START -->
        <nav class="navbar navbar-dark bg-dark rounded">
            <a class="navbar-brand ml-1" href="index.php">
                <img src="img/icon.png" width="30" height="30" class="d-inline-block align-top  bg-white p-1 rounded"
                    alt="Logo">
                VIP Hierarchy Builder
            </a>
        </nav>
        <!-- NAV END -->

        <section>
            <div class="row bg-white">
                <div class="col-12 bg-white text-center">
                    <div class="bg-dark p-1 my-1 rounded" style="margin: auto;">
                        <img src="img/Logo3.jpg" class="img-fluid rounded" style="max-height: 200px;" alt="Header">
                    </div>
                </div>
            </div>

            <form action="selectCR.php" method="get">
                <div class="container">
                    <h2 class="bg-success text-center rounded">Chart Type</h2>
                </div>
                <div class="row px-3 py-2">
                    <div class="col text-center">
                        <input class="form-control btn btn-info" type="button" value="Linear"
                            onclick="document.location.href='OrgChart.html'">
                        <img style="width:100%" src="img/OrgLine.jpg" alt="Line">
                    </div>
                    <div class="col text-center">
                        <input class="form-control btn btn-info" type="submit" value="Wrapped">
                        <img style="width:80%" src="img/OrgWrap.png" alt="Wrap">
                    </div>
                </div>
            </form>
        </section>


    </div>
    <!-- Page End -->

    <!-- Start of Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- end of Bootstrap JS -->

</body>

</html>