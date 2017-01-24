<h1>Tradeshow</h1>
<form action="doit.php" method="POST">
 
   <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  
  
 
  <div class="form-group">
    <label for="type">Type</label>
    <select class="form-control" id="type" name="type">
		<?php foreach ($types as $type): ?>
			<option><?php echo $type; ?></option>
		<?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Make it Happen!</button>
</form>
<br/>
<br/>
<!---
<br/>
<hr/>
<br/>
<h1>Breakouts</h1>
<form action="dobreakouts.php" method="POST">
 
   <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  
  
 
  <div class="form-group">
    <label for="type">Type</label>
    <select class="form-control" id="type" name="type">
      <option>assobr</option>
      <option>custbr</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Make it Happen!</button>
</form>

--->