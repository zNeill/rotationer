<?php

//standard stuff
require("helpers.php");
require("connection.php");

//if no key present, yell at user and stop execution
if(empty($_GET['rKey'])){
	tell("danger","You are missing the requisite URL variables to access this page!");
}

//prepare key for lookup
$userKey = substr($_GET['rKey'],0,11);
//print("<h3>user key: " . $userKey . "</h3>");

//get supplier info
$getSupInfo = $db->prepare("SELECT * FROM `suppliers` WHERE `key` = ?");
$getSupInfo->execute([$userKey]);
$supplierInfo = $getSupInfo->fetchAll(PDO::FETCH_ASSOC);

if(count($supplierInfo) < 1) {
	tell("danger","Invalid Access Key!");
}

$supplierInfo = $supplierInfo[0];
$supInfo = print_r($supplierInfo, true);



$getSchedule = $db->prepare("
	SELECT * FROM `schedule`
	LEFT JOIN `groups` ON `schedule`.`group_id` = `groups`.`group_id`
	LEFT JOIN `time_slots` ON `schedule`.`time_id` = `time_slots`.`time_id`
	WHERE `schedule`.`supplier_id` = :sid
	ORDER BY  `time_slots`.`sort_order` ASC
	");
$getSchedule->execute(array("sid" => $supplierInfo['supplier_id']));
$userSchedule = $getSchedule->fetchAll(PDO::FETCH_ASSOC);

//$skedgie = print_r($userSchedule, true);
//tell("success","Sup Info: <BR><BR> " . $supInfo . "<br> Schedule: <br><br>" . $skedgie);
render("schedule.php",["appts" => $userSchedule, "info" => $supplierInfo]);




?>