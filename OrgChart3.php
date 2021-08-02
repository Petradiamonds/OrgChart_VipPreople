    <?php
    
    $LV = 0;
    if (isset($_POST['LV'])){
        $LV = $_POST['LV'];
    }
    
    function AddChildren($root,$id){
        // get children
        $PositionCode = $root[$id]['PositionCode'];
        $sql = "SELECT DISTINCT 
                    ID,
                    ReportsToID,
                    PositionCode,
                    PositionLongDescription,
                    VacancyDate,
                    Name,
                    PositionStatus,
                    HierarchyNameG AS Department,
                    JobTitle,
                    CompanyCode,
                    CompanyDisplay,
                    CompanyRuleDescription,
                    EmployeeCode,
                    ReportsToPositionCode, 
                    ReportsToEmployeeName,
                    ReportToEmployeeCode,
                    ReportsToCompanyCode
                FROM      Employee.OrganizationalHierarchyView
                WHERE     ReportsToPositionCode = :Ps;";
        $sqlargs = array('Ps'=>$PositionCode);
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);
        $root = array_merge($root,$rootRS[0]);
        return($root);
    }

    function AddChildrenLv2($root,$id){
        // get children
        $PositionCode = $root[$id]['PositionCode'];
        $sql = "SELECT DISTINCT 
                    ID,
                    ReportsToID,
                    PositionCode,
                    PositionLongDescription,
                    VacancyDate,
                    Name,
                    PositionStatus,
                    HierarchyNameG AS Department,
                    JobTitle,
                    CompanyCode,
                    CompanyDisplay,
                    CompanyRuleDescription,
                    EmployeeCode,
                    ReportsToPositionCode, 
                    ReportsToEmployeeName,
                    ReportToEmployeeCode,
                    ReportsToCompanyCode
                FROM      Employee.OrganizationalHierarchyView
                WHERE     ReportsToPositionCode = :Ps;";
        $sqlargs = array('Ps'=>$PositionCode);
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);


        @ $tmp = $rootRS[0][0]['ReportsToID'];
        $newRs = [];
        foreach ($rootRS[0] as $row) {
            $row['ReportsToID'] = $tmp;
            array_push($newRs,$row);
            $tmp = $row['ID'];
        }
        
        return($newRs);
    }

// If Company number and level is set
if (isset($_GET['PositionCode']) && isset($_GET['LV_DEEP']) )
    {
        $LV_DEEP = $_GET['LV_DEEP'];
        $PositionCode = $_GET['PositionCode'];
        //SQL Connect and generate JSON for root.
        $sql = "SELECT TOP(1) 
                    ID,
                    ReportsToID,
                    PositionCode,
                    PositionLongDescription,
                    VacancyDate,
                    Name,
                    PositionStatus,
                    HierarchyNameG AS Department,
                    JobTitle,
                    CompanyCode,
                    CompanyDisplay,
                    CompanyRuleDescription,
                    EmployeeCode,
                    ReportsToPositionCode, 
                    ReportsToEmployeeName,
                    ReportToEmployeeCode,
                    ReportsToCompanyCode
                FROM    Employee.OrganizationalHierarchyView
                WHERE   (PositionCode = :Ps);";
        $sqlargs = array('Ps'=>$PositionCode);
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);


        // just manager and report to
        if ($LV_DEEP > 0){
            $root0 = AddChildren($rootRS[0],0);
            $root0[0]['ReportsToID'] = '';
        }

        if (($LV == "1") || ($LV == "2")){
            // 1 lv deeper
            $root1 = [];
            if ($LV_DEEP > 1){
                for ($i=0; $i < count($root0); $i++) {
                    $tmp = AddChildrenLv2($root0,$i);
                    $root1 = array_merge($root1,$tmp);
                }
            }
        }else{
            $root1 = [];
            if ($LV_DEEP > 1){
                for ($i=0; $i < count($root0); $i++) {
                    $tmp = AddChildren($root0,$i);
                    $root1 = array_merge($root1,$tmp);
                }
            }
        }
        
        if (($LV == "2") || ($LV == "3")){
            $root2 = [];
            if ($LV_DEEP > 2){
                for ($i=0; $i < count($root1); $i++) { 
                    $tmp = AddChildrenLv2($root1,$i);
                    $root2 = array_merge($root2,$tmp);
                }
            }
        }else{
            $root2 = [];
            if ($LV_DEEP > 2){
                for ($i=0; $i < count($root1); $i++) { 
                    $tmp = AddChildren($root1,$i);
                    $root2 = array_merge($root2,$tmp);
                }
            }
        }
        // join all LVs
        $root = array_merge($root0,$root1,$root2);

        $root_json = [];
        for ($i=0; $i < count($root); $i++) { 

            $name = ($root[$i]['VacancyDate'])? "Vacant":$root[$i]['Name'];

            $formatted =    "<div style='font-weight:bold;'>".$name." - ".$root[$i]['PositionCode']."</div>"
                            .$root[$i]['JobTitle'];

            if ($name == "Vacant"){
                $formatted =    "<div id='green' style='color:red;font-weight:bold;'>".$name." - ".$root[$i]['PositionCode']."</div>"
                            .$root[$i]['JobTitle'];
                            // .$root[$i]['Department'];
            }

                if ($name == "Mr WP Van Molendorff"){
                $formatted =    "<div id='red' style='font-weight:bold;'>".$name." - ".$root[$i]['PositionCode']."</div>"
                            .$root[$i]['JobTitle'];
                            // .$root[$i]['Department'];
            }

                if ($root[$i]['JobTitle'] == "Engineering Foreman"){
                $formatted =    "<div id='blue' style='color:blue;font-weight:bold;'>".$name." - ".$root[$i]['PositionCode']."</div>"
                            .$root[$i]['JobTitle'];
                            // .$root[$i]['Department'];
            }


            array_push($root_json,
            [
                [
                    "v" => $root[$i]['ID'],
                    "f"=>   $formatted
                ],
                $root[$i]['ReportsToID'],
                $root[$i]['PositionLongDescription']
            ]
            );
        }
    }

?>

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
        <?php echo  "<script>let root_json='" . json_encode($root_json,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . "';</script>"; ?>
        <script type="text/javascript" src="js/gOrg.js"></script>
    </body>

    <script>
function sPrint() {
    html2canvas(document.querySelector("#chart_div")).then(canvas => {
        // document.body.appendChild(canvas.toDataURL();)
        var myWindow = window.open("", "", "width=347.7200,height=755.9100");
        myWindow.document.body.appendChild(canvas);
    }, {
        width: 2000,
        height: 1800
    });
}
    </script>

    </html>