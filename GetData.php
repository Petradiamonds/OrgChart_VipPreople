<?php
function AddChildren($root){
        // get children
        $PositionCode = $root['PositionCode'];
        $root['children'] = [];
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
                WHERE     ReportsToPositionCode = '$PositionCode';";
        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $children =  sqlQuery($sql,$sqlargs);

        // loop tru children
        foreach ($children[0] as $child) {
            $child  = [
            "name" => ($child['Name'] ? $child['Name'] : 'Vacant'),
            "CN" => $child['EmployeeCode'],
            "PositionCode" => $child['PositionCode'],
            "imageUrl" => "img/Profile-Icon.png",
            "area" => $child['Department'],
            "profileUrl"=> "http://example.com/employee/profile",
            "office"=> "office name here",
            "tags"=> $child['PositionCode'],
            "isLoggedUser"=> false,
            "unit"=> [
                "type"=> "business",
                "value"=> $child['VacancyDate']." ".$child['PositionStatus'],
            ],
            "positionName" => $child['JobTitle'],
            ];
            array_push($root['children'],$child);
        }
        return($root);
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
                WHERE   (PositionCode = '$PositionCode');";
        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);
        // var_dump($rootRS);

        $root = [
            "name" => $rootRS[0][0]['Name'],
            "CN" => $rootRS[0][0]['EmployeeCode'],
            "PositionCode" => $rootRS[0][0]['PositionCode'],
            "imageUrl" => "img/Profile-Icon.png",
            "area" => $rootRS[0][0]['Department'],
            "profileUrl"=> "http://example.com/employee/profile",
            "office"=> "office name here",
            "tags"=> $rootRS[0][0]['PositionCode'],
            "isLoggedUser"=> false,
            "unit"=> [
                "type"=> "business",
                "value"=> $rootRS[0][0]['VacancyDate']." ".$rootRS[0][0]['PositionStatus'],
            ],
            "positionName" => $rootRS[0][0]['JobTitle'],
            "children"=> []
        ];

        // just manager and report to
        if ($LV_DEEP > 0){
            $root = AddChildren($root);
        }

        // 1 lv deeper
        if ($LV_DEEP > 1){
            for ($i=0; $i < count($root['children']); $i++) { 
                $root['children'][$i] = AddChildren($root['children'][$i]);
            }
        }

        if ($LV_DEEP > 2){
            for ($i=0; $i < count($root['children']); $i++) { 
                for ($j=0; $j < count($root['children'][$i]['children']); $j++) { 
                    $root['children'][$i]['children'][$j] = AddChildren($root['children'][$i]['children'][$j]);
                }
            }
        }


        print(json_encode($root));
    }
?>