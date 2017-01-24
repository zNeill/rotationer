<p style="text-align: right;" class="noprint"><a href="javascript:window.print()">Print<span class="glyphicon glyphicon-print" style="margin-left: 6px;" aria-hidden="true"></span></a></p>
 <h3>Rotation Appointments</h3>
<p>Below you will find appointments for Trade Show Group #<?= $tsnum?> (<?= $tsgroup ?>) and Breakout Group #<?= $bonum ?> (<?= $bogroup ?>). </p>
<p>This information is provided as a reference; the authoritative source of your final schedule is your event app and/or the schedule you receive at the event.</p>
 <div class='container-fluid'>

	<table class="table table-striped table-condensed table-bordered table-hover">
	<thead>
	<tr>
		<th class="col-sm-4">Time</th>
		<th class="col-sm-3">Type</th>
		<th class="col-sm-5">Meeting With</th>
	</tr>
	</thead>
	<?php foreach ($appts as $appt): ?>
	<tr>
		<td class="col-sm-4 small"><?= $appt['time'] ?></td>
		<td class="col-sm-3 small"><?php 
	switch($appt['group_type'])
	{
		case asso:
			echo "Associate Trade Show";
			break;
		case assobr:
			echo "Associate Breakout";
			break;
		case cust:
			echo "Customer Trade Show";
			break;
		case custbr:
			echo "Customer Breakout";
			break;
		default:
			echo "Info Error";
	}
			?></td>
		<td class="col-sm-5 small"><?= $appt['supplier_name']?></td>
	</tr>
	<?php endforeach; ?>
	</table>

</div>
