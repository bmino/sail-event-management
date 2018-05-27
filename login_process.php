<?php
session_start();
if(!isset($_SESSION['username'])) {$_SESSION['username'] = $_POST['username'];}
$username = $_SESSION['username'];
if(!isset($_SESSION['password'])) {$_SESSION['password'] = $_POST['password'];}
$password = $_SESSION['password'];
if(isset($_POST['team_select']))
{
	$team_name = $_POST['team_select'];
	$_SESSION['team_name'] = $team_name;
}
else {$team_name = $_SESSION['team_name'];}
?>
<html>
<head>
<title>2013 Parent Portal</title>
<link rel="stylesheet" type="text/css" href="settings/stylesheet.css">
<script>
function checkboxlimit(checkgroup, limit){
    var checkgroup=checkgroup
    var limit=limit
    for (var i=0; i<checkgroup.length; i++){
        checkgroup[i].onclick=function(){
        var checkedcount=0
        for (var i=0; i<checkgroup.length; i++)
            checkedcount+=(checkgroup[i].checked)? 1 : 0
        if (checkedcount>limit){
            alert("You can select a maximum of "+limit+" events")
            this.checked=false
            }
        }
    }
}
</script>

</head>
<body>
<?php
//CONNECTS TO MYSQL
include('include/connect_mysql.php');

//Validates Login Credentials
$query = mysql_query("SELECT * FROM Accounts WHERE Username='$username' AND Password='$password' AND Team='$team_name'") or die(mysql_error());
$query_result = mysql_fetch_array($query,MYSQL_ASSOC);
$_SESSION['admin'] = $query_result['AdminPriv'];
$_SESSION['ID'] = $query_result['ID'];
$account_ID = $query_result['ID'];
$LoginEmail = $query_result['Email'];
$temppass = $query_result['TempPass'];


//Get Settings
$settings2 = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
$settings = mysql_fetch_array($settings2,MYSQL_ASSOC);
	$malecolor = $settings['ColorMale'];
	$femalecolor = $settings['ColorFemale'];
	$rowheight = $settings['EventsRowHeight'] . 'pt';
	$sortbythis = $settings['EventsSortBy'];
	$fontsize = $settings['EventsFontSize'] . 'pt';
	$start_dead_time = $settings['StartDeadTime'];
	$end_dead_time = $settings['EndDeadTime'];
	$ParentPriv = $settings['ParentsPriv'];
	$AMES_Allowed = $settings['AllowAMES'];

//Checks For Existing Username
$user_exists = NULL;
$query_exists = mysql_query("SELECT * FROM Accounts WHERE Username='$username' AND Team='$team_name'") or die(mysql_error());
$query_exists_result = mysql_fetch_array($query_exists,MYSQL_ASSOC);
$user_exists = $query_exists_result['Username'];
$Q1_set = $query_exists_result['RecoveryQ1'];
$Q2_set = $query_exists_result['RecoveryQ2'];

//Kicks Out If Invalid User/Pass
if (!$query_result or $query_result == NULL)
{
	//Display Incorrect Login Message
	?>
    <table width="600pt" height="300pt" style="top:50%;left:50%;margin-left:-300;margin-top:-150;position:absolute;" border="1" class="InputsBox">
    <tr>
        <th>Invalid Login</th>
    </tr>
    <tr height="180pt">
        <td>
        <table width="100%" height="100%" border="0">
        <tr height="50%">
            <td width='9em'>Username:</td>
            <td><input type="text" name="username" value="<?php echo $username;?>" style="width:95%;" disabled="disabled"></td>
        </tr>
        <tr height="50%">
            <td width='9em'>Password:</td>
            <td><input type="password" name="password" value="<?php echo str_repeat('*',strlen($password));?>" style="width:95%;" disabled="disabled"></td>
        </tr>
        </table>
        </td>
    </tr>
    <tr height="60pt">
        <td>
            <table width="100%" height="100%" border='0'>
            <tr><?php
			if ($user_exists != NULL and (isset($Q1_set) or isset($Q2_set)))
			{
				$user_ID_query = mysql_query("SELECT ID FROM Accounts WHERE Username='$username' and Team='$team_name'") or die(mysql_error());
				$user_ID_result = mysql_fetch_array($user_ID_query,MYSQL_ASSOC);
				$user_ID = $user_ID_result['ID'];?>
				<td><form action="settings/recovery_questions.php" method="post">
                    <input type='hidden' value="<?php echo $user_ID;?>" name='temp_ID'>
					<input type='submit' value='Forgot Password' style='width:auto;height:50pt;font-size:22pt;'></form></td>
				<td><form action="index.php" method="post"><input type='submit' value='Try Again' style='width:auto;min-width:100%;height:50pt;font-size:22pt;'></form></td>
				</tr>
				</table><?php
			}
			else
			{?>
				<td><form action="index.php" method="post"><input type='submit' value='Try Again' style='width:100%;height:50pt;font-size:22pt;'></form></td>
				</tr>
				</table><?php
			}?>
        </td>
    </tr>
    </table>
    <?php
	
	//Logs Incorrect Login Attempt
	if (isset($_POST['fromloginscreen']) and $_SESSION['admin'] != 37)
	{
		$fh = fopen('text/log.txt', 'a');
		$current_date = date("D m/d h:ia",time()-3660*4);
		fwrite($fh, "$current_date\tFAILURE\t$username\t$password\t$team_name\n");
		fclose($fh);
	}
	exit;
}


//Function To Create Notification Window
function restricted_notifier($title, $message)
{
	echo"
	<table border='1' width='700pt' height='200pt' style='top:50%;left:50%;margin-left:-350;margin-top:-100;position:absolute;' class='NotificationBox'>
	<tr>
		<th>$title</th>
	</tr>
	<tr>
		<td>$message</td>
    </tr>
	<tr>
    	<td height='50pt'><form action='index.php' method='post'><input type='submit' value='Return to Login' style='width:100%;height:50pt;font-size:22pt;'></form></td>
	</tr>
	</table>";	
}


//Checks For Good Status
if($settings['Status'] != 'Good')
{
	//Unsets Credentials
	$_SESSION['admin'] = -1;
	if(isset($_SESSION['username'])) unset($_SESSION['username']);
	if(isset($_SESSION['password'])) unset($_SESSION['password']);
	if(isset($_SESSION['ID'])) unset($_SESSION['ID']);
	//Display Message
	restricted_notifier('Restricted Access!', 'Current Team Status: ['.$settings['Status'].']');
	exit;
}

//Logs Correct Login
if (isset($_POST['fromloginscreen']) and $_SESSION['admin'] != 37)
{
	$fh = fopen('text/log.txt', 'a');
	$current_date = date("D m/d h:ia",time()-3660*4);
	fwrite($fh, "$current_date\tSUCCESS\t$username\t$password\t$team_name\n");
	fclose($fh);
}

//Checks if AMES Is Allowed For Team
if(($AMES_Allowed == 0 or $AMES_Allowed == '0') and ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 37))
{
	$_SESSION['admin'] = -1;
	restricted_notifier('Parent Portal Access', 'Parent Portal is not enabled for your team.');
	exit;
}
elseif($ParentPriv == 'None' and ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 37))
{
	$_SESSION['admin'] = -1;
	restricted_notifier('Parent Portal Access', 'Your coach has disabled SEAL access.');
	exit;
}
//Checks If Temp Pass In Use
if(isset($_POST['change_temp_pass_later'])) {$_SESSION['request_update'] = FALSE;}
if($temppass == 'yes' and $_SESSION['request_update'] == TRUE)
{
	$query_for_ID = mysql_query("SELECT ID FROM Accounts WHERE Username='$username' AND Team='$team_name'") or die(mysql_error());
	$query_for_ID_result = mysql_fetch_array($query_for_ID,MYSQL_ASSOC);
	$temp_ID = $query_for_ID_result['ID'];
	?>
    <table width='700pt' height='160pt' border='1' style='top:50%;left:50%;margin-left:-350;margin-top:-80;position:absolute;' class="NotificationBox">
    <tr>
    	<th>Temporary Password Detected!</th>
    </tr>
    <tr height="50pt">
    	<td><form action="settings/account_password.php" method="post"><input type="submit" value="Set New Password" style="height:50pt;width:50%;font-size:22pt;" name='temp_pass_fill'></form><form action="login_process.php" method="post"><input type="submit" value="Change Later" style="height:50pt;width:50%;font-size:22pt;" name='change_temp_pass_later'></form>
		</td>
    </tr>
    </table>
    <?php
	exit;
}

//Redirects Admin User
if($_SESSION['admin'] == 1)
{
	echo "<script>window.location.href = 'masterlist.php'</script>";
	exit;
}
if($_SESSION['admin'] == 37)
{
	echo "<script>window.location.href = 'admin/overwatch.php'</script>";
	exit;
}


//Parent Login Nav Bar ?>
<div width="100%" style="height:60pt;" align="right">
    <a href="settings/account_settings.php"><input type="submit" value="Account Info" style="font-size:30pt;width:auto;height:100%;"></a>
	<a href="index.php"><input type="button" value="Logout" style="font-size:30pt;width:auto;height:100%;"></a>
</div>
<?php

////////////////////////////////////
////Process Checkboxes From Form////
////////////////////////////////////

//Check If During Dead Time
$current_date_check = date('Y-m-d H:i:s',time()-3660*4);
if ($current_date_check < $start_dead_time or $current_date_check > $end_dead_time)
{
	if (isset($_POST['events_submit_button']))
	{
		$possible_events = array('ShortFree','IM','Breast','LongFree','Back','Fly');
		$result = mysql_query("SELECT * FROM Events WHERE Email='$LoginEmail' AND Team='$team_name'") or die(mysql_error());
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
						mysql_query("UPDATE Events SET $p_event=1 WHERE ID='$ID'") or die(mysql_error());
					}
					if(in_array($p_event,$events) == FALSE and $sql_data["$p_event"] == 1)
					{
						//Unset mySQL;
						mysql_query("UPDATE Events SET $p_event=0 WHERE ID='$ID'") or die(mysql_error());
					}
				}
				//If No Events Checked, Looks if Last Box Was Unchecked
				elseif($sql_data["$p_event"] == 1)
				{
					mysql_query("UPDATE Events SET $p_event=0 WHERE ID='$ID'") or die(mysql_error());
				}
			}
		}
	}
}


$render_submit_button = TRUE;
$current_date_check = date('Y-m-d H:i:s',time()-3660*4);
/////////////////////////////////////////////////
///////////////////BEGIN TABLE///////////////////
/////////////////////////////////////////////////
echo "<form id='eform' name='eform' action='login_process.php' method='post'>";
echo "
<table width='100%' border='1'>
 <tr height='35px'>
 <th width='34%'><h1>Swimmer</h1></th>
 <th width='11%'><h1>SF</h1></th>
 <th width='11%'><h1>IM</h1></th>
 <th width='11%'><h1>BR</h1></th>
 <th width='11%'><h1>LF</h1></th>
 <th width='11%'><h1>BCK</h1></th>
 <th width='11%'><h1>FLY</h1></th>
 </tr>";

//Gets Family Swimmers
$family_query = mysql_query("SELECT * FROM Events WHERE Email='$LoginEmail' AND (Email!='' OR Email!=NULL) AND Team='$team_name' ORDER BY Age ASC") or die(mysql_error());
while($rowdata = mysql_fetch_array($family_query,MYSQL_ASSOC))
{
	$insname = "Swimmer" . $rowdata['ID'] . "[]";
	$style = '"width:100%;height:100%;"';
	
	//Gender Color
	echo "<tr height='$rowheight' style='font-size:$fontsize;'>";
	if ($rowdata['Gender'] == 'F') echo "<td width='132' bgcolor='$femalecolor'>";
	elseif ($rowdata['Gender'] == 'M') echo "<td width='132' bgcolor='$malecolor'>";
	else echo "<td width='132'>";
	
	//Name Data
	echo $rowdata['FirstName'];
	echo "</td>\n";
	
	//Events Checkboxes
	$EventList = array('ShortFree','IM','Breast','LongFree','Back','Fly');
	foreach($EventList as $EventName)
	{
		echo '<td align="center">';
		if ($rowdata[$EventName] == 1) {$property = "checked='checked'";}
		else $property = '';
		

		//Sets Checkboxes as UNEDITABLE
		if (($current_date_check > $start_dead_time and $current_date_check < $end_dead_time) or ($ParentPriv == 'View'))
		{
			$property .= " disabled = 'disabled'";
			$render_submit_button = FALSE;
		}
		
		echo "<input type='checkbox' style=$style name='$insname' value='$EventName' $property>";
		echo "</td>\n";
	}
	//Limits Checks To 3
	?><script>checkboxlimit(document.forms.eform["<?php echo $insname; ?>"],3)</script><?php
	echo "</tr>\n";
}
echo "</table>\n";


//Submit Button
if ($render_submit_button == TRUE) {echo "<input type='submit' name='events_submit_button' VALUE='Submit' style='font-size:32pt;height:100px;width:100%;'>";}
else {echo "<a href='login_process.php'><input type='button' name='fake_events_submit_button' VALUE='Submit' style='font-size:32pt;height:100px;width:100%;'></a>";}


echo "</form>";
?>


</body>
</html>