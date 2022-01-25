USE [CUL_People]
GO

/****** Object:  View [Employee].[OrganizationalHierarchyView]    Script Date: 2021/02/13 06:16:52 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [Employee].[OrganizationalHierarchyView]
	AS 
WITH PositionHeirarchy(PositionID, ReportsToPositionID, [Level], RelationShip)  
AS (SELECT HREnt.HREntityID, 
    HREnt.ReportsToHREntityID AS PARENTID, 
    1 AS [Level], 
    'S' as RelationShip
    FROM Job.HREntity HREnt
    WHERE HREnt.IsTemplate = 0 and HREnt.ReportsToHREntityID IS NOT NULL 
  UNION ALL 
    SELECT Ent.HREntityID,  
    Ent.ReportsToHREntityID,  
    [Level] + 1 AS [Level], 
    'S' as RelationShip
    FROM Job.HREntity Ent
    INNER JOIN PositionHeirarchy on Ent.ReportsToHREntityID = PositionHeirarchy.PositionID),

SuccessionEmployeeTable 
AS (SELECT ROW_NUMBER() over (partition by scp.[HREntityID] order by scp.[Rank]) AS rownum, scp.[EmployeeID], ed.EmployeeDisplay, scp.[HREntityID], scp.[Rank]
FROM [Employee].[SuccessionCareerPlan] scp
LEFT JOIN [Employee].[EmployeeDetail] ed ON ed.EmployeeID = scp.EmployeeID
WHERE scp.SuccessionTarget = 1)

SELECT 
    PositionHeirarchy.PositionID as ID,
    PositionHeirarchy.ReportsToPositionID as ReportsToID,
    HREnt.Code as PositionCode, 
    HREnt.LongDescription as PositionLongDescription,
	GE.EntityCode,
    GE.DisplayName as Name,
    CASE WHEN HREnt.PositionStatus = 'VT' then 'Vacant' else '' end as PositionStatus,
    JT.ShortDescription as JobTitle,
	HrEnt.VacancyDate,
    C.CompanyID,
	GEC.EntityCode as CompanyEntityCode,
	C.CompanyCode,
	GEC.DisplayName as CompanyDisplay,
	CR.CompanyRuleCode,
	CR.ShortDescription as CompanyRuleDescription,
    CP.ShortDescription as CriticalPosition,
	ISNULL(CR.CompanyRuleID, 0) as CompanyRuleID,
    E.EmployeeCode,
	GE.Gender as EmployeeGender,
	ETH.Code as EmployeeGroup,
    GEP.Photo as EmployeePhoto,
    EEQOL.ShortDescription as EmployeeEquityOccupationalLevel,
	EEQOC.ShortDescription as EmployeeEquityOccupationalCategory,
	EEQTOE.ShortDescription as EmployeeEquityTypeOfEmployment,
	EEQJF.ShortDescription as EmployeeEquityJobFunction,
	ESOL.ShortDescription as EmployeeSkillsOccupationalLevel,
	ESOC.ShortDescription as EmployeeSkillsOccupationalCategory,
	ESOFO.ShortDescription as EmployeeSkillsOFOCode,
	ESOFC.ShortDescription as EmployeeSkillsOFOCategoryCode,
	NoC.ShortDescription as NatureOfContract,
    JG.ShortDescription as JobGrade,
    JGen.ShortDescription as JobGeneral,
	GE.IDNumber,
    CASE WHEN Relationship = 'A' THEN '1' ELSE '0' END AS IsAssistant, 
    CASE WHEN Relationship = 'A' THEN 'Yes' ELSE 'No' END AS IsAssistantYesNo,
    Lof.ShortDescription as LevelOfWork,
    HREnt.ShortDescription as PositionShortDescription,    
    HREnt.PositionStatus as PositionStatusCode,
	NoP.ShortDescription as NatureOfPosition,
    ReportsToHREnt.Code as ReportsToPositionCode,
    PEQ.Gender as PositionEquityGender,
    PEQ.RacialGroup as PositionEquityRace,
    PEQOL.ShortDescription as PositionEquityOccupationalLevel,
    PEQOC.ShortDescription as PositionEquityOccupationalCategory,
    PEQTOE.ShortDescription as PositionEquityTypeOfEmployment,
    PEQJF.ShortDescription as PositionEquityJobFunction,
    PSOL.ShortDescription as PositionSkillsOccupationalLevel,
	PSOC.ShortDescription as PositionSkillsOccupationalCategory,
	PSOFO.ShortDescription as PositionSkillsOFOCode,
	PSOFC.ShortDescription as PositionSkillsOFOCategoryCode,
	EPR.StartDate as PositionStartDate,
	EPR.TimeAllocated as EmployeeTimeAllocated,
	Case When EPR.IsPrimary IS NULL then '' When EPR.IsPrimary = 1 Then 'Primary' ELSE 'Secondary' end as PrimaryPosition,
	PRT.LongDescription as EmployeePositionReasonType,
    Zone.Code as PositionZoneCode,
	RepE.CompanyID as ReportsToCompanyID,
	RepC.CompanyCode as ReportsToCompanyCode,
	RepE.EmployeeCode as ReportToEmployeeCode,
	RepGE.DisplayName as ReportsToEmployeeName,
	ERH.[HierarchyName],
	ERH.[HierarchyNameA],
	ERH.[HierarchyNameB],
	ERH.[HierarchyNameC],
	ERH.[HierarchyNameD],
	ERH.[HierarchyNameE],
	ERH.[HierarchyNameF],
	ERH.[HierarchyNameG],
	ERH.[HierarchyNameH],
	ERH.[HierarchyNameI],
	SuccessionEmployee1,SuccessionEmployee2,SuccessionEmployee3,SuccessionEmployee4,SuccessionEmployee5
FROM PositionHeirarchy 
	Left Join Job.HREntity HREnt on HREnt.HREntityID = PositionHeirarchy.PositionID  
	Left Join dbo.ZoneCode Zone on HREnt.ZoneCodeID = Zone.ZoneCodeID
	Left Join Job.HREntity ReportsToHREnt on ReportsToHREnt.HREntityID = PositionHeirarchy.ReportsToPositionID  
  Left Join Employee.EmployeePositionRel AS EPR ON EPR.HREntityID = HREnt.HREntityID AND EPR.IsPrimary = 1 AND EPR.PositionStatus = 'A' 
	Left Join Employee.Employee E on E.EmployeeID = EPR.EmployeeID
	Left Join Employee.EmployeeRule ER on ER.EmployeeID = E.EmployeeID AND ER.MainRel = 1
	Left Join Company.CompanyRule CR on CR.CompanyRuleID = ER.CompanyRuleID
	Left Join Company.Company C on E.CompanyID = C.CompanyID
	Left Join Entity.GenEntity GEC on C.GenEntityID = GEC.GenEntityID
	Left Join Entity.GenEntity GE on E.GenEntityID = GE.GenEntityID
	Left Join Entity.EthnicType ETH on GE.EthnicTypeID = ETH.EthnicTypeID
	Left Join Entity.GenEntityPhoto GEP on GE.GenEntityID = GEP.GenEntityID
	
  Left Join Employee.EmployeePositionRel RepEPR on RepEPR.HREntityID = ReportsToHREnt.HREntityID
	
  Left Join Employee.Employee RepE on RepE.EmployeeID = RepEPR.EmployeeID
	Left Join Company.Company RepC on RepE.CompanyID = RepC.CompanyID
	Left Join Entity.GenEntity RepGE on RepE.GenEntityID = RepGE.GenEntityID
	Left Join Employee.JobGrade JG on HREnt.JobGradeID = JG.JobGradeID
	Left Join Employee.JobTitleType JT on HREnt.JobTitleTypeID = JT.JobTitleTypeID
	Left Join Job.JobGeneral JGen on HREnt.JobGeneralID = JGen.JobGeneralID
	Left Join Job.LevelOfWork Lof on HREnt.LevelOfWorkID = Lof.LevelOfWorkID
	Left Join Job.CriticalPosition CP on HREnt.CriticalPositionID = CP.CriticalPositionID
	Left Join Employee.EmployeeReportingHierarchy erh ON erh.EmployeeID = e.EmployeeID
	Left Join Job.HREntityEquityInformation PEQ on HREnt.HREntityID = PEQ.HREntityID
	Left Join Job.HREntitySkillsInformation PSI on HREnt.HREntityID = PSI.HREntityID
	Left Join SkillsEquity.OccupationalLevelType PEQOL on PEQ.OccupationalLevelTypeID = PEQOL.OccupationalLevelTypeID
	Left Join SkillsEquity.OccupationalCategoryType PEQOC on PEQ.OccupationalCategoryTypeID = PEQOC.OccupationalCategoryTypeID
	Left Join Equity.EquityTypeOfEmploymentType PEQTOE on PEQ.EquityTypeOfEmploymentTypeID = PEQTOE.EquityTypeOfEmploymentTypeID
	Left Join Equity.EquityJobFunctionType PEQJF on PEQ.EquityJobFunctionTypeID = PEQJF.EquityJobFunctionTypeID
	Left Join SkillsEquity.OccupationalLevelType PSOL on PSI.OccupationalLevelTypeID = PSOL.OccupationalLevelTypeID
	Left Join SkillsEquity.OccupationalCategoryType PSOC on PSI.OccupationalCategoryTypeID = PSOC.OccupationalCategoryTypeID
	Left Join Skills.OFOCode PSOFO on PSI.OFOCodeID = PSOFO.OFOCodeID
	Left Join Skills.OFOCategoryType PSOFC on PSI.OFOCategoryTypeID = PSOFO.OFOCategoryTypeID
	Left Join Job.PositionReasonType PRT on EPR.PositionReasonTypeID = PRT.PositionReasonTypeID
	Left Join Employee.NatureOfContract NoP on HREnt.NatureOfPositionID = NoP.NatureOfContractID
	Left Join Employee.NatureOfContract NoC on E.NatureOfContractID = NoC.NatureOfContractID
	
	--Employee Equity
	Left Join Employee.EmployeeEquityInformation EEQI on E.EmployeeID = EEQI.EmployeeID
	Left Join SkillsEquity.OccupationalLevelType EEQOL on EEQI.OccupationalLevelTypeID = EEQOL.OccupationalLevelTypeID
	Left Join SkillsEquity.OccupationalCategoryType EEQOC on EEQI.OccupationalCategoryTypeID = EEQOC.OccupationalCategoryTypeID
	Left Join Equity.EquityTypeOfEmploymentType EEQTOE on EEQI.EquityTypeOfEmploymentTypeID = EEQTOE.EquityTypeOfEmploymentTypeID
	Left Join Equity.EquityJobFunctionType EEQJF on EEQI.EquityJobFunctionTypeID = EEQJF.EquityJobFunctionTypeID
	
	--Employee Skills
	Left Join Employee.EmployeeSkillsInformation ESI on E.EmployeeID = ESI.EmployeeID
	Left Join SkillsEquity.OccupationalLevelType ESOL on ESI.OccupationalLevelTypeID = ESOL.OccupationalLevelTypeID
	Left Join SkillsEquity.OccupationalCategoryType ESOC on ESI.OccupationalCategoryTypeID = ESOC.OccupationalCategoryTypeID
	Left Join Skills.OFOCode ESOFO on ESI.OFOCodeID = ESOFO.OFOCodeID
	Left Join Skills.OFOCategoryType ESOFC on ESI.OFOCategoryTypeID = ESOFC.OFOCategoryTypeID

	--Succession Plan
	Left Join (select [HREntityID],
	max(case rownum when 1 then [EmployeeDisplay] else null end) SuccessionEmployee1,
	max(case rownum when 2 then [EmployeeDisplay] else null end) SuccessionEmployee2,
	max(case rownum when 3 then [EmployeeDisplay] else null end) SuccessionEmployee3,
	max(case rownum when 4 then [EmployeeDisplay] else null end) SuccessionEmployee4,
	max(case rownum when 5 then [EmployeeDisplay] else null end) SuccessionEmployee5
	from SuccessionEmployeeTable 
	group by [HREntityID]) sett ON PositionHeirarchy.PositionID = sett.HREntityID

GO

