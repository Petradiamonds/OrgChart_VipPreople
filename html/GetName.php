<?php
        //SQL Connect and generate JSON
        $sql = "SELECT DISTINCT Name,PositionCode
                FROM    Employee.OrganizationalHierarchyView
                WHERE Name IS NOT NULL
                ORDER BY Name;";

        $sqlargs = array();
        require_once 'config/db_query.php'; 
        $rootRS =  sqlQuery($sql,$sqlargs);
        
        $Names = [];
        foreach ($rootRS[0] as $rec) {
            array_push($Names,$rec);
        }

        print(json_encode($Names));
?>