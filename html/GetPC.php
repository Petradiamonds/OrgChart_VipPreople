<?php
        //SQL Connect and generate JSON
        $sql = "SELECT DISTINCT PositionCode
                FROM    Employee.OrganizationalHierarchyView
                WHERE (EmployeeCode != '');";

        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);
        
        $Codes = [];
        foreach ($rootRS[0] as $rec) {
            array_push($Codes,$rec);
        }

        print(json_encode($Codes));
?>