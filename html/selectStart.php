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
                <img src="img/icon.png" width="30" height="30" class="d-inline-block align-top  bg-white p-1 rounded" alt="Logo">
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
                <h2 class="bg-success text-center rounded">Chart Start Location</h2>
            </div>
            <form action="OrgChart.php" method="GET">
                <div class="row px-3 py-2">
                    <!-- Selections -->
                    <ul class="navbar-nav">
                        <li class="nav-item pr-1">
                            <select class="form-control-sm" id="LV_DEEP" name="LV_DEEP">
                                <option value="1">1 Level</option>
                                <option value="2">2 Level</option>
                                <option value="3">3 Level</option>
                            </select>
                            Hierarchy Report Levels Deep
                        </li>
                        <hr>
                        <?php
                        //SQL Connect and generate JSON
                        $sql = "SELECT DISTINCT EmployeeCode,PositionCode
                                FROM    Employee.OrganizationalHierarchyView
                                WHERE (EmployeeCode != '')
                                Order By EmployeeCode ASC;";

                        $sqlargs = array();
                        require_once 'config/db_query.php';
                        $rootRS =  sqlQuery($sql, $sqlargs);
                        ?>
                        <li class="nav-item pr-1">
                            <select class="form-control-sm" name="PositionCode" id="CN">
                                <option class="form-control-sm" Value="">Please Select</option>
                                <?php
                                foreach ($rootRS[0] as $rec) {
                                    echo '<option class="form-control-sm" Value="' . $rec["PositionCode"] . '">' . $rec["EmployeeCode"] . '</option>';
                                }
                                ?>
                            </select>
                            CompanyNumber
                        </li>

                        OR
                        <?php
                        //SQL Connect and generate JSON
                        $sql = "SELECT DISTINCT PositionCode
                                FROM    Employee.OrganizationalHierarchyView
                                WHERE (EmployeeCode != '')
                                Order By PositionCode ASC;";

                        $sqlargs = array();
                        require_once 'config/db_query.php';
                        $rootRS =  sqlQuery($sql, $sqlargs);
                        ?>
                        <li class="nav-item pr-1">
                            <select class="form-control-sm" name="Position" id="PC">
                                <option class="form-control-sm" Value="">Please Select</option>
                                <?php
                                foreach ($rootRS[0] as $rec) {
                                    echo '<option class="form-control-sm" Value="' . $rec["PositionCode"] . '">' . $rec["PositionCode"] . '</option>';
                                }
                                ?>
                            </select>
                            Position Code
                        </li>

                        OR
                        <?php
                        //SQL Connect and generate JSON
                        $sql = "Select Distinct
                        Employee.OrganizationalHierarchyView.PositionCode,
                        concat(Employee.EmployeeDetail.KnownAsName,' ',Employee.EmployeeDetail.LastName) as 'Name',
                        KnownAsName
                      From
                        Employee.OrganizationalHierarchyView Left Join
                        Employee.EmployeeDetail On Employee.EmployeeDetail.EmployeeCode =
                          Employee.OrganizationalHierarchyView.EmployeeCode
                      Where
                        Employee.EmployeeDetail.KnownAsName Is Not Null
                      Order By
                        Employee.EmployeeDetail.KnownAsName;";

                        $sqlargs = array();
                        require_once 'config/db_query.php';
                        $rootRS =  sqlQuery($sql, $sqlargs);
                        ?>
                        <li class="nav-item pr-1">
                            <select class="form-control-sm" id="CN" name="Employee">
                                <option class="form-control-sm" Value="">Please Select</option>
                                <?php
                                foreach ($rootRS[0] as $rec) {
                                    echo '<option class="form-control-sm" Value="' . $rec["PositionCode"] . '">' . $rec["Name"] . '</option>';
                                }
                                ?>
                            </select>
                            Employee Name
                        </li>
                    </ul>
                </div>
                <input class="btn btn-outline-primary btn-lg my-2" type="submit" value="Next">
            </form>
        </section>


    </div>
    <!-- Page End -->

    <!-- Start of Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- end of Bootstrap JS -->
    <script src="js/app.js"></script>

</body>

</html>