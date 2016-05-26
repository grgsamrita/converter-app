<!DOCTYPE html>
<head>
	<title>File converter app</title>

	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<script type="text/javascript" src="assets/js/jquery.js"></script>
	
</head>
</body>
<table width="600" align="center">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<tr>
	<td colspan="2px"><h1>File Converter</h1></td>
</tr>
<tr>
<td width="50%"><input type="radio" checked="checked" name="convert" id="convert1" value="csvtojson">CSV to JSON</td>
<td width="50%"><input type="radio" name="convert" id="convert2" value="jsontocsv">JSON to CSV</td>
</tr>
<tr>
	<td colspan="2px">
		<div id="csvtojson">
			<label>Choose csv file</label>
			<input type="file" name="csvtojson" id="csv"/><br/>
			<input type="submit" name="submit-csv" class="btn btn-default" style="background-color:lightgrey;" id="submit-csv"/>
		</div>
		<div id="jsontocsv">
			<label >Choose json file</label>
			<input type="file" name="jsontocsv" id="json"/><br/>
			<input type="submit" name="submit-json" class="btn btn-default" style="background-color:lightgrey;" id="submit-json" />
		</div>
	</td>
</tr>


</form>
</table>
<?php
	include('converter/convert-tojson.php');
	include('converter/convert-tocsv.php');
?>
</body>
<script>
$(document).ready(function(){
	$('#csvtojson').show();
	$('#jsontocsv').hide();
	$("#convert2").click(function(){
		$('#jsontocsv').show();
		$('#csvtojson').hide();
	});
	$("#convert1").click(function(){
		$('#jsontocsv').hide();
		$('#csvtojson').show();
	});
});
</script>
</html>