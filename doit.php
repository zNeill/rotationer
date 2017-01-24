<?php
require("helpers.php");
require("connection.php");
ini_set('display_errors', '3');

//I realize this method of password verification is super insecure - it really doesn't matter however for this application!
if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['password'] == "bananas1109" && !empty($_POST['type']) ) {

	//FYI -- Types are 'asso' or 'cust'
	
	//pull list of all groups by type
	$group_pull = $db->prepare("SELECT group_id FROM groups WHERE group_type = ?");
	$group_pull->execute(array($_POST['type']));
	$groups = $group_pull->fetchAll(PDO::FETCH_COLUMN);
		
	//pull list of all times by type
	$time_pull = $db->prepare("SELECT time_id FROM time_slots WHERE group_type = ?");
	$time_pull->execute(array($_POST['type']));
	$times = $time_pull->fetchAll(PDO::FETCH_COLUMN);
	
	$tempstring = $_POST['type'];
	$last2 = substr($tempstring, -2, 2);
	
	if($last2 == "br") {
		$suppliers = $db->query("SELECT supplier_id FROM suppliers WHERE breakouts = 'yes'")->fetchAll(PDO::FETCH_COLUMN);
		//$string = print_r($suppliers, true);
		//tell("success", $string);
	} 
	else {
		//comment this line to enable trade show assignments
		tell("warning", "Sorry, You can't redo the Trade Show assignments!");
		
		$suppliers = $db->query("SELECT supplier_id FROM suppliers")->fetchAll(PDO::FETCH_COLUMN);
		$string = print_r($suppliers, true);
		tell("success", $string);
	}
	
	
	

	foreach($groups as $group) {
		
		$pull_faves = $db->prepare("SELECT supplier_id FROM groups_love_suppliers WHERE group_id = ?");
		$pull_faves->execute(array($group));
		$faves = $pull_faves->fetchAll(PDO::FETCH_COLUMN);
		
		$pull_hates = $db->prepare("SELECT supplier_id FROM groups_hate_suppliers WHERE group_id = ?");
		$pull_hates->execute(array($group));
		$hates = $pull_hates->fetchAll(PDO::FETCH_COLUMN);
		
		foreach($times as $time)
		{
			//i know this isn't necessary, but i learned to program in C so bear with me for loving unnecessary delcarations
			$matches = [];
			
			$pull_busy_suppliers = $db->prepare("SELECT supplier_id FROM schedule WHERE time_id = ?");
			$pull_busy_suppliers->execute(array($time));
			$busy_suppliers = $pull_busy_suppliers->fetchAll(PDO::FETCH_COLUMN);
			
			$pull_already_booked = $db->prepare("SELECT supplier_ID FROM schedule WHERE group_id = ?");
			$pull_already_booked->execute(array($group));
			$already_booked = $pull_already_booked->fetchAll(PDO::FETCH_COLUMN);
			
					
			foreach($suppliers as $supplier)
			{
				if(!in_array($supplier,$already_booked) && !in_array($supplier,$busy_suppliers) ) { //!in_array($supplier,$hates)
					$matches[] = $supplier;
				}
			}

			$pick = "none";
			
			//if already cascades, add royal to faves and versa.
			
			if($last2 != 'br') {
				if(in_array("royalpaper",$already_booked) && in_array("cascades",$matches))
				{
					$pick = "cascades";
					print("<ul><li>Group $group : picked $pick at $time due to already having royalpaper</li></ul>");
				}

				if(in_array("cascades",$already_booked) && in_array("royalpaper",$matches))
				{
					$pick = "royalpaper";
					print("<ul><li>Group $group : picked $pick at $time due to already having cascades</li></ul>");
				}
			}
			

			
			

			if($pick == "none") {
				foreach($faves as $fave) {
					if(in_array($fave,$matches)) {
						$pick = $fave;
						print("<ul><li>Group $group : picked $pick at $time due to being in the favorites array</li></ul>");
						break;
					}
				}
			}

			
			
			if($pick == "none") {
				$thecount = count($matches)-1;
					if($thecount < 1) {
						tell("warning", "couldn't find any matches for $group at $time");
					}
				
				
				$index = rand(0,$thecount);
				
				$pick = $matches[0];
								
				print("<ul><li>Group $group : picked $pick at $time from the matches array index $index.</li></ul>");
				
			}
			
			$add_schedule = $db->prepare("INSERT INTO schedule(time_id,supplier_id,group_id) VALUES(?,?,?)");
			$add_schedule->execute(array($time,$pick,$group));
		
				
			
		}
		
	}
	
	tell("success","Everything seems to have been dandy! Check the database!");
	

}else{
	tell("danger","Either you didn't fill out a field, entered the wrong password, or tried to GET this file!");
}

	
?>
