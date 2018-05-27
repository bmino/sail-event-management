<?php
include('../include/require_overlord.php');
$team_name = $_SESSION['team_name'];
?>
<html>
<head>
<title>Manage Teams</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
<style type="text/css">
th {font-size:25pt;}
td {font-size:20pt;}
</style>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');


if(isset($_POST['Hold/Clear']))
{
	$settings_ID = $_POST['Hold/Clear'];
	$status_query = mysql_query("SELECT Status FROM Settings WHERE ID='$settings_ID'") or die(mysql_error());
	$status_result = mysql_fetch_array($status_query,MYSQL_ASSOC);
	if($status_result['Status'] == 'Good')
	{
		mysql_query("UPDATE Settings SET Status='Hold' WHERE ID='$settings_ID'") or die(mysql_error());
	}
	elseif($status_result['Status'] == 'Hold')
	{
		mysql_query("UPDATE Settings SET Status='Ban' WHERE ID='$settings_ID'") or die(mysql_error());
	}
	elseif($status_result['Status'] == 'Ban')
	{
		mysql_query("UPDATE Settings SET Status='Good' WHERE ID='$settings_ID'") or die(mysql_error());
	}
}

if(isset($_POST['AMES_Allowed']))
{
	$settings_ID = $_POST['AMES_Allowed'];
	$status_query = mysql_query("SELECT AllowAMES FROM Settings WHERE ID='$settings_ID'") or die(mysql_error());
	$status_result = mysql_fetch_array($status_query,MYSQL_ASSOC);
	if($status_result['AllowAMES'] == 0)
	{
		mysql_query("UPDATE Settings SET AllowAMES=1 WHERE ID='$settings_ID'") or die(mysql_error());
	}	
	else
	{
		mysql_query("UPDATE Settings SET AllowAMES=0 WHERE ID='$settings_ID'") or die(mysql_error());
	}
}

if(isset($_POST['deletion_submit']))
{
	$delete_team_name = $_POST['delete_team'];
	mysql_query("DELETE FROM Accounts WHERE Team='$delete_team_name'") or die(mysql_error());
	mysql_query("DELETE FROM Events WHERE Team='$delete_team_name'") or die(mysql_error());
	mysql_query("DELETE FROM Settings WHERE Team='$delete_team_name'") or die(mysql_error());
}


//////////////////////////////////////
////////////Function List/////////////
//////////////////////////////////////
function count_swimmers($team)
{
	$count_swimmers_query = mysql_query("SELECT COUNT(ID) FROM Events WHERE Team='$team'") or die(mysql_error());
	$swimmers = mysql_fetch_array($count_swimmers_query);
	return $swimmers[0];
}
function count_accounts($team)
{
	$count_accounts_query = mysql_query("SELECT COUNT(ID) FROM Accounts WHERE Team='$team'") or die(mysql_error());
	$accounts = mysql_fetch_array($count_accounts_query);
	return $accounts[0];
}

	
/////////////////////////////////////////////////
///////////////////BEGIN TABLE///////////////////
/////////////////////////////////////////////////
$row_height = '60pt';
?>
<table border='0' align='center'>
<tr><td>
<table border='1' align='center'>
<tr>
<th>Team</th>
<th>Admin</th>
<th>Kids</th>
<th>Acct.</th>
<th>Status</th>
<th>SEAL Setup</th>
<th>X</th>
</tr>
<?php
//Gets Teams
$team_query = mysql_query("SELECT * FROM Accounts WHERE AdminPriv='1' ORDER BY Team ASC") or die(mysql_error());
$team_status_query = mysql_query("SELECT * FROM Settings WHERE Team!='admin' ORDER BY Team ASC") or die(mysql_error());
while($rowdata = mysql_fetch_array($team_query,MYSQL_ASSOC))
{
	//Sets Row Color
	$team_status = mysql_fetch_array($team_status_query,MYSQL_ASSOC);
	if($team_status['Status'] == 'Good') {$color = '#00CC00';}//Green
	elseif($team_status['Status'] == 'Hold') {$color = '#0066FF';}//Blue
	elseif($team_status['Status'] == 'Ban') {$color = '#FF0000';}//Red
	else {$color = '#FFFFFF';}//White

	
	echo "<tr height='$row_height' bgcolor='$color'>\n";

	//Team Name
	echo "<td>";
	echo $rowdata['Team'];
	echo "</td>\n";
	
	//Admin Username
	echo "<td>";
	echo $rowdata['Username'];
	echo "</td>\n";
	
	//Team Swimmers
	echo "<td>";
	echo count_swimmers($rowdata['Team']);
	echo "</td>\n";
	
	//Team Accounts
	echo "<td>";
	echo count_accounts($rowdata['Team']);
	echo "</td>\n";
	
	//Status
	echo "<td>\n";
	$display_status = $team_status['Status'];
	$setting_ID = $team_status['ID'];
    echo "\t<form action='manage_teams.php' method='post'>\n";
	echo "\t<input type='hidden' name='Hold/Clear' value='$setting_ID'>\n";
    echo "\t<input type='submit' value='$display_status' style='min-width:auto;width:100%;height:100%;font-size:25pt;'>\n";
    echo "\t</form>\n";
	echo "</td>\n";
	
	//Parent Privilege
	$AdminAllow = $team_status['AllowAMES'];
	if($AdminAllow == 0) {$display_allow = 'No'; $align='right'; $parent_color = '#FF0000';}//Red
	else {$display_allow = 'Yes'; $align='left'; $parent_color = '#00CC00';}//Green
	echo "<td align='$align' bgcolor='$parent_color'>\n";
    echo "\t<form action='manage_teams.php' method='post'>\n";
	echo "\t<input type='hidden' name='AMES_Allowed' value='$setting_ID'>\n";
	echo "\t<input type='submit' name='current_priv' value='$display_allow' style='min-width:auto;width:50%;height:100%;font-size:25pt;'>\n";
    echo "\t</form>\n";
	echo "</td>\n";
	
	//Delete Team
	echo "<td align='center'>\n";
	echo "\t<form action='manage_teams.php' method='post'>\n";
	echo "\t<input type='hidden' name='delete_team' value='".$rowdata['Team']."'>\n";
	echo "\t<input type='submit' name='deletion_submit' value='X' style='width:100%;height:100%;font-size:25pt;'>\n";
    echo "\t</form>\n";
	echo "</td>\n";
	
	echo "</tr>\n";
}
?>
</table>
</td></tr>
<tr><td>
<a href='overwatch.php'><input type='button' value='Return To Home' style='min-width:auto;width:100%;min-height:auto;height:70pt;font-size:38pt;'></a>
</td></tr>
</table>

</body>
</html>