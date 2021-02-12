<?php

function AddChildren($root){
        // get children
        $CN = $root['CN'];
        $root['children'] = [];
        $sql = "SELECT
            vVIPData.Operation As Operation1,
            vVIPData.CompanyNumber,
            vVIPData.KnownAs,
            vVIPData.FirstNames,
            vVIPData.LastName,
            vVIPData.JobTitle,
            vVIPData.ReportsToEmployeeDisplay,
            vVIPData.ReportToEmployeeCode,
            vVIPData.TerminationDate,
            vVIPData.Department
            From
            vVIPData
            WHERE ReportToEmployeeCode = '$CN';";
        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $children =  sqlQuery($sql,$sqlargs);

        // loop tru children
        foreach ($children[0] as $child) {
            $child  = [
            "name" => $child['KnownAs'] . ' ' . $child['LastName'],
            "CN" => $child['CompanyNumber'],
            "imageUrl" => "img/Profile-Icon.png",
            "area" => $child['Department'],
            "profileUrl"=> "http://example.com/employee/profile",
            "office"=> "office name here",
            "tags"=> $child['CompanyNumber'],
            "isLoggedUser"=> false,
            "unit"=> [
                "type"=> "CostCentre",
                "value"=> "GCXL1000123"
            ],
            "positionName" => $child['JobTitle']
            ];
            array_push($root['children'],$child);
        }
        return($root);
}


// If Company number and level is set
if (isset($_GET['CN']) && isset($_GET['LV_DEEP']) )
    {
        $CN = $_GET['CN'];
        $LV_DEEP = $_GET['LV_DEEP'];

        //SQL Connect and generate JSON for root.
        $sql = "SELECT
                vVIPData.Operation As Operation1,
                vVIPData.CompanyNumber,
                vVIPData.KnownAs,
                vVIPData.FirstNames,
                vVIPData.LastName,
                vVIPData.JobTitle,
                vVIPData.ReportsToEmployeeDisplay,
                vVIPData.ReportToEmployeeCode,
                vVIPData.TerminationDate,
                vVIPData.Department
                From
                vVIPData
                WHERE CompanyNumber = '$CN';";
        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);

        $root = [
            "name" => $rootRS[0][0]['KnownAs'] . ' ' . $rootRS[0][0]['LastName'],
            "CN" => $rootRS[0][0]['CompanyNumber'],
            "imageUrl" => "img/Profile-Icon.png",
            "area" => $rootRS[0][0]['Department'],
            "profileUrl"=> "http://example.com/employee/profile",
            "office"=> "office name here",
            "tags"=> $rootRS[0][0]['CompanyNumber'],
            "isLoggedUser"=> false,
            "unit"=> [
                "type"=> "business",
                "value"=> "GCXL1000123"
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