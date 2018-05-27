<?php
include('../include/require_admin.php');
?>
<html>
<head>
<title>SEAL Settings</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php
//Nav Bar
include('../include/nav_bar.php');

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//
////Should Process Form Yes/No
//

if(isset($_POST['from_seal_settings']))
{
	//Gathers Information From Form
	//Also Adds Leading Zero's When Needed
	$year = date("Y");
	$start_dead_time_month = sprintf("%02d",$_POST['StartDeadTime_Month']);
	$start_dead_time_day = sprintf("%02d",$_POST['StartDeadTime_Day']);
	$start_dead_time_hour = sprintf("%02d",$_POST['StartDeadTime_Hour']);
	$start_dead_time_minute = sprintf("%02d",$_POST['StartDeadTime_Minute']);
	$start_dead_time_ampm = $_POST['StartDeadTime_AmPm'];
	
	$end_dead_time_month = sprintf("%02d",$_POST['EndDeadTime_Month']);
	$end_dead_time_day = sprintf("%02d",$_POST['EndDeadTime_Day']);
	$end_dead_time_hour = sprintf("%02d",$_POST['EndDeadTime_Hour']);
	$end_dead_time_minute = sprintf("%02d",$_POST['EndDeadTime_Minute']);
	$end_dead_time_ampm = $_POST['EndDeadTime_AmPm'];
	
	//Calculates Hours Based on AM and PM
	if ($start_dead_time_ampm == 'PM' and $start_dead_time_hour != 12) {$start_dead_time_hour = $start_dead_time_hour + 12;}
	if ($end_dead_time_ampm == 'PM' and $end_dead_time_hour != 12) {$end_dead_time_hour = $end_dead_time_hour + 12;}
	if ($end_dead_time_ampm == 'AM' and $end_dead_time_hour == 12) {$end_dead_time_hour = 00;}
	if ($start_dead_time_ampm == 'AM' and $start_dead_time_hour == 12) {$start_dead_time_hour = 00;}
	
	//Gets New Parent Privilege
	$new_parent_priv = $_POST['ParentPriv'];
	
	
	//Update Dead Times and Parent Privilege
	$team_name = $_SESSION['team_name'];
	mysql_query("UPDATE Settings SET
	StartDeadTime = '$year-$start_dead_time_month-$start_dead_time_day $start_dead_time_hour:$start_dead_time_minute:00',
	ParentsPriv = '$new_parent_priv',
	EndDeadTime = '$year-$end_dead_time_month-$end_dead_time_day $end_dead_time_hour:$end_dead_time_minute:00'
	WHERE Team='$team_name'") or die(mysql_error());
	
	
	$current_query = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
	$updated_settings = mysql_fetch_array($current_query,MYSQL_ASSOC);
	$new_start_time = $updated_settings['StartDeadTime'];
	$new_end_time = $updated_settings['EndDeadTime'];
	
    //Notifies User
	echo "<script>alert('Dead time successfully updated!'); window.location.href = 'seal_settings.php';</script>";
	exit;
}


//
////Begins Normal Form
//
$team_name = $_SESSION['team_name'];
$settings2 = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
$settings = mysql_fetch_array($settings2,MYSQL_ASSOC);
	$start_dead_time = $settings['StartDeadTime'];
	$end_dead_time = $settings['EndDeadTime'];
	
	//Gets Current Start Dead Time Pieces
	$current_start_dead_time = date_parse($start_dead_time);
	$current_end_dead_time = date_parse($end_dead_time);
	$current_start_dead_time_m = $current_start_dead_time['month'];
	$current_start_dead_time_d = $current_start_dead_time['day'];
	$current_start_dead_time_h = $current_start_dead_time['hour'];
	$current_start_dead_time_mi = $current_start_dead_time['minute'];
	$current_start_dead_time_ampm = 'AM';
	if ($current_start_dead_time_h >= 13)
	{
		$current_start_dead_time_ampm = 'PM';
		$current_start_dead_time_h = $current_start_dead_time_h - 12;
	}
	//Gets Current End Dead Time Pieces
	$current_end_dead_time_m = $current_end_dead_time['month'];
	$current_end_dead_time_d = $current_end_dead_time['day'];
	$current_end_dead_time_h = $current_end_dead_time['hour'];
	$current_end_dead_time_mi = $current_end_dead_time['minute'];
	$current_end_dead_time_ampm = 'AM';
	if ($current_end_dead_time_h >= 13)
	{
		$current_end_dead_time_ampm = 'PM';
		$current_end_dead_time_h = $current_end_dead_time_h - 12;
	}
	
	//Gets SEAL Parent Privilege
	$parent_privilege = $settings['ParentsPriv'];
	$ParentPrivProperty_Full = ''; $ParentPrivProperty_View = ''; $ParentPrivProperty_None = '';
	if($parent_privilege == 'Full') {$ParentPrivProperty_Full = "selected='selected'";}
	elseif($parent_privilege == 'View') {$ParentPrivProperty_View = "selected='selected'";}
	elseif($parent_privilege == 'None') {$ParentPrivProperty_None = "selected='selected'";}
	
	
function display_select($select_form_name,$default_value,$values,$current_time)
{
	echo "<select name='$select_form_name' style='width:auto;'>\n";
	if ($default_value != '') {echo "<option>$default_value</option>\n";}
	for ($i = 0; $i < count($values); $i++)
	{
		$property = '';
		if ($values[$i] == $current_time) {$property = "selected='selected'";}
		echo "<option value='$values[$i]' $property>$values[$i]</option>\n";
	}
	echo "</select>\n";
}

//Defines Lists
$months = array();
for ($array_part_i = 1; $array_part_i <= 12; $array_part_i++) {array_push($months, $array_part_i);}
$days = array();
for ($array_part_i = 1; $array_part_i <= 31; $array_part_i++) {array_push($days, $array_part_i);}
$hours = array();
for ($array_part_i = 1; $array_part_i <= 12; $array_part_i++) {array_push($hours, $array_part_i);}
$minutes = array();
for ($array_part_i = 0; $array_part_i <= 59; $array_part_i++) {array_push($minutes, sprintf("%02d",$array_part_i));}
$ampm = array('AM','PM');
?>

<form action="seal_settings.php" method="post">
<table width="900pt" align="center" border="1" class="InputsBox">
<tr>
	<th>SEAL Settings</th>
</tr>
<tr>
	<td>
	<table width="100%" height="100%" border="0">
	<tr>
		<td width='400pt'>Start Dead Time:</td>
		<td align='right'><?php 	display_select('StartDeadTime_Month','M',$months,$current_start_dead_time_m);
					display_select('StartDeadTime_Day','D',$days,$current_start_dead_time_d);
					display_select('StartDeadTime_Hour','h',$hours,$current_start_dead_time_h);
					display_select('StartDeadTime_Minute','m',$minutes,$current_start_dead_time_mi);
					display_select('StartDeadTime_AmPm','',$ampm,$current_start_dead_time_ampm);?>
		</td>
	</tr>
	<tr>
		<td width='400pt'>End Dead Time:</td>
		<td align='right'><?php	display_select('EndDeadTime_Month','M',$months,$current_end_dead_time_m);
					display_select('EndDeadTime_Day','D',$days,$current_end_dead_time_d);
					display_select('EndDeadTime_Hour','h',$hours,$current_end_dead_time_h);
					display_select('EndDeadTime_Minute','m',$minutes,$current_end_dead_time_mi);
					display_select('EndDeadTime_AmPm','',$ampm,$current_end_dead_time_ampm);?>
		</td>
	</tr>
	<tr>
		<td width='400pt'>Parent Privilege</td>
		<td align='right'>	<select name='ParentPriv' style='width:auto;'>
								<option value='Full' <?php echo $ParentPrivProperty_Full;?>>Full Access</option>
								<option value='View' <?php echo $ParentPrivProperty_View;?>>View Only</option>
								<option value='None' <?php echo $ParentPrivProperty_None;?>>No Access</option>
							</select>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr height="50pt">
	<td>
	<input type="submit" name="from_seal_settings" value="Update" style="width:100%;height:50pt;font-size:30pt;"></td>
</tr>
</table>
</form>
</body>
</html>