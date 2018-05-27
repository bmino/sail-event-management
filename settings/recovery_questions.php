<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$team_name = $_SESSION['team_name'];
if(isset($_SESSION['ID'])) {$account_ID = $_SESSION['ID'];}
?>
<html>
<head>
<title>Password Recovery</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');


//
//Should Process Form or No?
//

if(isset($_POST['recovery_questions_submitted']))
{
	if(isset($_POST['temp_ID'])) {$account_ID = $_POST['temp_ID'];}
	
	//Gets Form Data
	$given_answer1 = mysql_real_escape_string($_POST['recovery_ans_1']);
	$given_answer2 = mysql_real_escape_string($_POST['recovery_ans_2']);
	
	$questions_query = mysql_query("SELECT RecoveryA1, RecoveryA2 FROM Accounts WHERE ID='$account_ID'") or die(mysql_error());
	$questions_result = mysql_fetch_array($questions_query,MYSQL_ASSOC);
	$set_answer1 = mysql_real_escape_string($questions_result['RecoveryA1']);
	$set_answer2 = mysql_real_escape_string($questions_result['RecoveryA2']);
	
	if($given_answer1 == $set_answer1 and $given_answer2 == $set_answer2)
	{
		//Changes Password To Temporary Password
		$length = 6;
		$a_z = "abcdefghjklmnpqrstuvwxyz";
		$temp_pass = '';
		for($letter=0; $letter < ($length/2); $letter++)
		{
			$letter_apendix = rand(0,strlen($a_z)-1);
    		$temp_pass .= $a_z[$letter_apendix];
		}
		for($digit=0; $digit < ($length/2); $digit++)
		{
			$temp_pass .= rand(1,9);
		}
		mysql_query("UPDATE Accounts SET Password='$temp_pass',TempPass='yes' WHERE ID='$account_ID'") or die(mysql_error());
		$_SESSION['password'] = $temp_pass;
		?>
		<form action="../index.php" method="post">
		<table border='1' width='700pt' height='250pt' style='top:50%;left:50%;margin-left:-350;margin-top:-125;position:absolute;' class="NotificationBox">
			<tr>
				<th>Password Change</th>
			</tr>
			<tr>
				<td>
					<?php echo "Your temporary password is:<br>$temp_pass";?>
				</td>
			</tr>
			<tr height='50pt'>
				<td><input type='submit' value='Return To Login' style='width:100%;height:50pt;font-size:22pt;'></td>
			</tr>
		</table>
		</form>
		<?php
	}
	else
	{
		//Does NOT Change Password
		?>
		<table border='1' width='700pt' height='250pt' style='top:50%;left:50%;margin-left:-350;margin-top:-125;position:absolute;' class="NotificationBox">
			<tr>
				<th>Password Change</th>
			</tr>
			<tr>
				<td>Your password has NOT been reset!</td>
			</tr>
			<tr height='50pt'>
			  <td><form action='../index.php' method='post'><input type='submit' value='Return To Login' style='width:50%;height:50pt;font-size:22pt;'></form><form action='recovery_questions.php' method='post'><input type="hidden" value="<?php echo $account_ID;?>" name="temp_ID"><input type='submit' value='Try Again' style='width:50%;height:50pt;font-size:22pt;'></form></td>
			</tr>
		</table>
		</form>
		<?php
	}
	exit;
}



//
////FORM BEGINS
//


if(isset($_POST['temp_ID'])) {$account_ID = $_POST['temp_ID'];}

//Gets Recovery Questions and Answers
$settings_query = mysql_query("SELECT * FROM Accounts WHERE ID='$account_ID'") or die(mysql_error());
$settings_result = mysql_fetch_array($settings_query,MYSQL_ASSOC);
$RecoveryQ1 = $settings_result['RecoveryQ1'];
$RecoveryA1 = $settings_result['RecoveryA1'];
$RecoveryQ2 = $settings_result['RecoveryQ2'];
$RecoveryA2 = $settings_result['RecoveryA2'];


//Makes Sure Recovery Questions Have Been Set
if(($RecoveryQ1 != NULL and $RecoveryQ1 != '') or ($RecoveryQ2 != NULL and $RecoveryQ2 != ''))
{
?>
<form name="RecoveryQuestions" action="recovery_questions.php" method="post">
<table width="98%" height="350pt" align="center" style="top:50%;margin-top:-175;position:absolute;" border="1" class="InputsBox">
<tr>
    <th>Password Recovery</th>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr>
    	<td style='font-size:20pt'><?php echo $RecoveryQ1;?></td>
    	<td><input type="text" name="recovery_ans_1" style="width:95%;" autofocus></td>
  	</tr>
  	<tr>
    	<td style='font-size:20pt'><?php echo $RecoveryQ2;?></td>
    	<td><input type="text" name="recovery_ans_2" style="width:95%;"></td>
 	</tr>
	</table>
	</td>
</tr>
<tr height="62pt">
	<td>
    <input type="hidden" value="<?php echo $account_ID;?>" name="temp_ID">
    <input type="submit" value="Submit" style="width:100%;height:60pt;font-size:30pt;" name="recovery_questions_submitted"></td>
</tr>
</table>
</form>
<?php
}
else
{
	echo "Recovery questions NOT set!";
	?>
    <table border='1' width='700pt' height='250pt' style='top:50%;left:50%;margin-left:-350;margin-top:-125;position:absolute;' class="NotificationBox">
    <tr>
        <th>Password Change</th>
    </tr>
    <tr>
        <td>Recovery questions NOT set!</td>
    </tr>
    <tr height='50pt'>
      <td><form action='../index.php' method='post'><input type='submit' value='Return To Login' style='width:50%;height:50pt;font-size:22pt;'></form><form action='recovery_questions.php' method='post'><input type="hidden" value="<?php echo $account_ID;?>" name="temp_ID"><input type='submit' value='Try Again' style='width:50%;height:50pt;font-size:22pt;'></form></td>
    </tr>
</table>
<?php
}
?>
</body>
</html>