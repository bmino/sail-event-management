<?php
include('include/require_admin.php');
?>
<html>
<head>
<title>Master List</title>
<link rel="stylesheet" type="text/css" href="settings/stylesheet.css">
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('include/connect_mysql.php');


//
////Should Process Form Yes/No
//

if(isset($_POST['delete_request']))
{
	//Gets Email of Swimmer Being Deleted
	$ID = $_POST['delete_request'];
	$get_email_query = mysql_query("SELECT Email FROM Events WHERE ID='$ID'") or die(mysql_error());
	$email_result = mysql_fetch_array($get_email_query,MYSQL_ASSOC);
	$email = $email_result['Email'];
	
	//Deletes Swimmer
	mysql_query("DELETE FROM Events WHERE ID='$ID'") or die(mysql_error());
	
	//Deletes Account If No Swimmers Remain
	$was_last_swimmer_query = mysql_query("SELECT Count(*) FROM Events WHERE Email='$email'") or die(mysql_error());
	$was_last_swimmer_result = mysql_fetch_array($was_last_swimmer_query,MYSQL_BOTH);
	$remaining_swimmers = $was_last_swimmer_result[0];
	
	//Deletes Account
	if ($remaining_swimmers == 0) {mysql_query("DELETE FROM Accounts WHERE Email='$email'") or die(mysql_error());}
	
	?><script type="text/javascript">alert("Swimmer succesfully deleted");</script><?php
}

if(isset($_POST['change_request']))
{
	$ID = $_POST['change_request'];
	echo "
	<table width='600pt' height='360pt' align='center' border='1' class='NotificationBox' style='top:50%;left:50%;margin-left:-300;margin-top:-180;position:absolute;'>
		<tr>
			<th>Update Request</th>
		</tr>
		<tr>
			<td align='center'>
				What would you like to do?
			</td>
		</tr>
		<tr>
			<td align='center' valign='middle' height='52pt'>
				<form action='editswimmer.php' method='post'>
				<input type='hidden' name='edit_request' value='$ID'>
				<input type='submit' value='Edit' style='width:100%;height:50pt;font-size:30pt;'>
				</form>
			</td>
		</tr>
		<tr>
			<td align='center' valign='middle' height='52pt'>
				<form action='masterlist.php' method='post'>
				<input type='hidden' name='delete_request' value='$ID'>
				<input type='submit' value='Delete' style='width:100%;height:50pt;font-size:30pt;'>
				</form>
			</td>
		</tr>
		<tr align='center' valign='middle' height='52pt'>
			<td>
				<form action='masterlist.php' method='post'>
				<input type='submit' value='Cancel' style='width:100%;height:50pt;font-size:30pt;'>
				</form>
			</td>
		</tr>
		</table>";
		exit;
}
	
if(isset($_POST['from_masterlist']))
{
	$team_name = $_SESSION['team_name'];
    $result = mysql_query("SELECT * FROM Events WHERE Team='$team_name'") or die(mysql_error());
    $possible_events = array('ShortFree','IM','Breast','LongFree','Back','Fly');
    
    
    
    //Gets Swimmers From mySQL
    while($sql_data = mysql_fetch_array($result,MYSQL_ASSOC))
    {
        //Gets Events From Form
        $insname = 'Swimmer' . $sql_data['ID'];
        if(isset($_POST["$insname"])) {$events = $_POST["$insname"];}
        else {$events = array();}
        foreach($possible_events as $p_event)
        {
            $ID = $sql_data['ID'];
            //Checks if Any Events Were Checked
            if(sizeof($events) > 0)
            {
                if(in_array($p_event,$events) == TRUE and (!isset($sql_data["$p_event"]) or $sql_data["$p_event"] == 0))
                {
                    //Set mySQL;
                    //echo "Setting Swimmer#" . $ID . " " . $p_event . " to 1"; echo "<br>";
                    mysql_query("UPDATE Events SET $p_event=1 WHERE ID='$ID'") or die(mysql_error());
                }
                if(in_array($p_event,$events) == FALSE and $sql_data["$p_event"] == 1)
                {
                    //Unset mySQL;
                    //echo "Setting Swimmer" . $ID . " " . $p_event . " to 0"; echo "<br>";
                    mysql_query("UPDATE Events SET $p_event=0 WHERE ID='$ID'") or die(mysql_error());
                }
            }
            //If No Events Checked, Looks if Last Box Was Unchecked
            elseif($sql_data["$p_event"] == 1)
            {
                //echo "Setting Swimmer" . $ID . " " . $p_event . " to 0"; echo "<br>";
                mysql_query("UPDATE Events SET $p_event=0 WHERE ID='$ID'") or die(mysql_error());
            }
        }
    }
}




//
////Begin Normal Form
//

//INCLUDES NAV BAR
include('include/nav_bar.php');
?>
<form action="masterlist.php" method="post">
<table width="100%" border="1" cellspacing="2pt" cellpadding="2pt">
	<tr height="35px">
        <th width="50%"><h1>Swimmer</h1></th>
        <th width="8%"><h1>SF</h1></th>
        <th width="8%"><h1>IM</h1></th>
        <th width="8%"><h1>BR</h1></th>
        <th width="8%"><h1>LF</h1></th>
        <th width="8%"><h1>BCK</h1></th>
        <th width="8%"><h1>FLY</h1></th>
	</tr>
<?php
$team_name = $_SESSION['team_name'];

//Gets Settings
$settings2 = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
$settings = mysql_fetch_array($settings2,MYSQL_ASSOC);
	$malecolor = $settings['ColorMale'];
	$femalecolor = $settings['ColorFemale'];
	$rowheight = $settings['EventsRowHeight'] . 'pt';
	$sortbythis = $settings['EventsSortBy'];
	$name_display_option = $settings['EventsNameDisplay'];
	$fontsize = $settings['EventsFontSize'] . 'pt';
	
//Populates Table
$result = mysql_query("SELECT * FROM Events WHERE Team='$team_name' ORDER BY $sortbythis ASC") or die(mysql_error());
while($rowdata = mysql_fetch_array($result,MYSQL_ASSOC))
{
	$insname = "Swimmer" . $rowdata['ID'] . "[]";
	
	
	echo "<tr height='$rowheight' style='font-size:$fontsize;'>";
	//Gender Color
	if ($rowdata['Gender'] == 'F') echo "<td width='132' bgcolor='$femalecolor'>";
	elseif ($rowdata['Gender'] == 'M') echo "<td width='132' bgcolor='$malecolor'>";
	else echo "<td width='132'>";
	
	//Name Data
	if ($name_display_option == "First,Last")
	echo $rowdata['FirstName'] . " " . $rowdata['LastName'];
	if ($name_display_option == "Last,First")
	echo $rowdata['LastName'] . ", " . $rowdata['FirstName'];
	if ($name_display_option == "First,Last (Age)")
	echo $rowdata['FirstName'] . " " . $rowdata['LastName'] . " (" . $rowdata['Age'] . ")";
	if ($name_display_option == "Last,First (Age)")
	echo $rowdata['LastName'] . ", " . $rowdata['FirstName'] . " (" . $rowdata['Age'] . ")";
	
	//Change Option
	echo "<button class='ChangeBox' value='".$rowdata['ID']."' name='change_request' width='40px' height='40px'><img src='include/images/edit.png' width='40px' height='40px' alt='Edit'></button>\n";
	echo "</td>\n";
	
	//Events Data
	$EventList = array('ShortFree','IM','Breast','LongFree','Back','Fly');
	foreach($EventList as $EventName)
	{
		echo '<td>';
		if ($rowdata[$EventName] == 1) {$checked = 'checked';}
		else $checked = '';
		echo "<input type='checkbox' class='EventsCheckbox' name='$insname' value='$EventName' $checked>";
		echo "</td>\n";
	}
	echo "</tr>\n";
}
?>
</table>

<table width='100%' border='0'>
<tr><td>
<input type="submit" value="Submit" style="font-size:35pt;height:100px;width:100%;" name="from_masterlist">
</td></tr>
</table>
</form>

</body>
</html>
