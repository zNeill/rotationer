<?php

//standard stuff
require("helpers.php");
require("connection.php");
include('ChromePhp.php'); // for logging

//if no key present, yell at user and stop execution
if(empty($_GET['type']) || empty($_GET['bogid']) || empty($_GET['tsgid'])){
	tell("danger","You are missing the requisite URL variables to access this page!");
}

//Change numerical values to database friendly values
if($_GET['type'] === 'cust') {
	$bog = "CB" . $_GET['bogid'];
	$tsg = "C" . $_GET['tsgid'];

}
elseif($_GET['type'] === 'asso') {
	$bog = "AB" . $_GET['bogid'];
	$tsg = "A" . $_GET['tsgid'];
} else {
	tell("danger","Sorry, there's something wrong with the link used to access this page. <br> Error Description: Invalid type provided via GET.");
}
ChromePhp::log('The BOG is ' . $bog . ' and the TSG is ' . $tsg);


//get ts and bo group info
$getTsInfo = $db->prepare("SELECT * FROM `groups` WHERE `group_id` = ?");
$getTsInfo->execute([$tsg]);
$tsInfo = $getTsInfo->fetchAll(PDO::FETCH_ASSOC);

$getboInfo = $db->prepare("SELECT * FROM `groups` WHERE `group_id` = ?");
$getboInfo->execute([$bog]);
$boInfo = $getboInfo->fetchAll(PDO::FETCH_ASSOC);


//If invalid report error
if(count($tsInfo) < 1) {
	tell("danger","Invalid Tradeshow group provided!");
}
if(count($boInfo) < 1) {
	tell("danger","Invalid Breakout group provided!");
}

// Now just the first record please
$boInfo = $boInfo[0];
$tsInfo = $tsInfo[0];



$getSchedule = $db->prepare("
SELECT * FROM `schedule`
    LEFT JOIN `suppliers` ON `schedule`.`supplier_id` = `suppliers`.`supplier_id`
    LEFT JOIN `groups` ON `schedule`.`group_id` = `groups`.`group_id`
    LEFT JOIN `time_slots` ON `schedule`.`time_id` = `time_slots`.`time_id`
    WHERE `schedule`.`group_id` = :tsg OR `schedule`.`group_id` = :bog
    ORDER BY  `time_slots`.`sort_order` ASC
	");
$getSchedule->execute(array("tsg" => $tsg, "bog" => $bog));
$userSchedule = $getSchedule->fetchAll(PDO::FETCH_ASSOC);

//$skedgie = print_r($userSchedule, true);
//tell("success","Sup Info: <BR><BR> " . $supInfo . "<br> Schedule: <br><br>" . $skedgie);
render("schedule_internal.php",["appts" => $userSchedule, "tsgroup" => $tsInfo['group_name'], "bogroup" => $boInfo['group_name'], "tsnum" => $_GET['tsgid'], "bonum" => $_GET['bogid']]);




?>