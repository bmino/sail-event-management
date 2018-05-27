<?php
session_start();
$_SESSION['admin'] = -1;
if(isset($_SESSION['username'])) unset($_SESSION['username']);
if(isset($_SESSION['password'])) unset($_SESSION['password']);
if(isset($_SESSION['team_name'])) unset($_SESSION['team_name']);
if(isset($_SESSION['ID'])) unset($_SESSION['ID']);
$_SESSION['request_update'] = TRUE;
?>
<html>
<head>
<title>2014 Parent Portal</title>
<link rel="stylesheet" type="text/css" href="settings/stylesheet.css">
<script type="text/javascript">
function validateForm()
{
	var username=document.forms["LoginForm"]["username"].value;
	if (username==null || username=="")
  	{
  		alert("Username must be filled out");
  		return false;
  	}
}
</script>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('include/connect_mysql.php');

function display_teams()
{
	$teams_query = mysql_query("SELECT DISTINCT Team FROM Settings WHERE Team!='admin' AND Status!='Ban' ORDER BY Team ASC") or die(mysql_error());
	echo "<select name='team_select' style='width:auto;height:100%;margin:0px;'>\n";
	echo "<option value='admin' style='width:auto;' selected='selected'>Team Select</option>\n";
	while($team = mysql_fetch_array($teams_query))
	{
		echo "<option value='$team[0]'>$team[0]</option>\n";
	}
	echo "</select>\n";
}
?>

<form action="login_process.php" method="post" name="LoginForm" onSubmit="return validateForm();">
<table width="800pt" height="400pt" style="top:50%;left:50%;margin-left:-400;margin-top:-200;position:absolute;" align="center" border="1" class='Credentials'>
<tr>
    <th>SEAL Login Portal</th>
</tr>
<tr height="220pt">
    <td>
    <table width="100%" height="100%" border="0">
  	<tr height="32%">
    	<td width='9em'>Username:</td>
    	<td align='right'><input type="text" name="username" style="width:95%;" autofocus="autofocus"></td>
  	</tr>
  	<tr height="32%">
    	<td width='9em'>Password:</td>
    	<td align='right'><input type="password" name="password" style="width:95%;"></td>
 	</tr>
  	<tr height="36%">
    	<td></td>
    	<td align='right'><?php display_teams();?></td>
 	</tr>
	</table>
	</td>
</tr>
<tr height="60pt">
	<td><input type="submit" value="Login" style="width:100%;height:60pt;font-size:30pt;" name="fromloginscreen"></td>
</tr>
</table>
</form>


</body>
</html>