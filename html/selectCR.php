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
                        <img src="img/Logo2.png" class="img-fluid rounded" style="max-height: 200px;" alt="Header">
                    </div>
                </div>
            </div>

            <div class="container">
                <h2 class="bg-success text-center rounded">Company Rule Selection</h2>
            </div>
            <div class="row justify-content-md-center">
                <div class="col">.</div>
                <div class="col-md-auto">
                    <form class="mb-5" action="selectItem.php">
                        <?php
                            //SQL Connect and generate JSON
                            $sql = "SELECT CompanyRuleDescription,CompanyRuleID FROM Employee.OrganizationalHierarchyView
                                    WHERE (EmployeeCode != '')
                                    GROUP BY CompanyRuleDescription,CompanyRuleID";

                            $sqlargs = array();
                            require_once 'config/db_query.php'; 
                            $rootRS =  sqlQuery($sql,$sqlargs);

                            foreach ($rootRS[0] as $rec) {
                                    print('<input type="checkbox" name="CR[]" id="'.$rec['CompanyRuleID'].'"> <label for="'.$rec['CompanyRuleID'].'">'.$rec['CompanyRuleDescription'].'</label><br>');
                            }
                        ?>
                        <input class="btn btn-outline-primary btn-lg my-2" type="submit" value="Next">
                    </form>
                </div>
                <div class="col">.</div>
            </div>
        </section>


    </div>
    <!-- Page End -->

    <!-- Start of Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- end of Bootstrap JS -->

</body>

</html>