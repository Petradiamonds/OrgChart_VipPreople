<?php
//SQL Connect and generate JSON
$sql = "SELECT Distinct
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

$Names = [];
foreach ($rootRS[0] as $rec) {
        array_push($Names, $rec);
}

print(json_encode($Names));
