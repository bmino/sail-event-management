<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
if(isset($_SESSION['ID'])) {$account_ID = $_SESSION['ID'];}
?>
<html>
<head>
<title>Username Change</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php

//
////Process Form Yes/No
//

if(isset($_POST['from_account_username']))
{
	//CONNECTS TO MYSQL
	include('../include/connect_mysql.php');
	
	//Gets Data From Form
	$new_username = mysql_real_escape_string($_POST['new_username']);
	$new_username_confirm = mysql_real_escape_string($_POST['new_username_confirm']);

	//Dialog Creating Function
	function CreateNotify($action, $message, $button)
	{?>
		<form action="<?php echo $action;?>" method='post'>
		<table border='1' width='700pt' height='200pt' style='top:50%;left:50%;margin-left:-350;margin-top:-100;position:absolute;' class="NotificationBox">
			<tr>
				<th>Username Change</th>
			</tr>
			<tr>
				<td><?php echo $message;?></td>
			</tr>
			<tr height='50pt'>
				<td>
					<input type='submit' value="<?php echo $button;?>" style='width:100%;height:50pt;font-size:22pt;'>
				</td>
			</tr>
		</table>
		</form><?php
	}


	//Checks if New Usernames are Identical
	if ($new_username != $new_username_confirm)
	{
		//Notify: NOT Matching Usernames
		CreateNotify('account_username.php', 'Confirm username does not match!', 'Retry');
		exit;
	}

	
	///////////////////
	//Update Username//
	///////////////////
	$count_existing_username_match = mysql_query("SELECT COUNT(Username) FROM Accounts WHERE Username='$new_username'") or die(mysql_error());
	$existing_usernames = mysql_fetch_array($count_existing_username_match);
	
	//Is The Username Already In Use?
	if ($existing_usernames[0] != 0) //Already In Use
	{
		//Notify: Not Updated
		CreateNotify('account_username.php', 'Sorry, that username already exists!', 'Create Another');
	}
	elseif($existing_usernames[0] == 0) //Available
	{
		//Actually Updates
		mysql_query("UPDATE Accounts SET Username='$new_username' WHERE ID='$account_ID'") or die(mysql_error());
		$_SESSION['username'] = $new_username;
		
		//Notify: Successful Update
		CreateNotify('../login_process.php', 'Your username was successfully changed!', 'Return To Home');
	}
	exit;
}


//
////Form Begins Here
//


//Include Nav Bar If From Menu
if($_SESSION['admin'] == 1)
{
	//Nav Bar
	include('../include/nav_bar.php');
}
?>
<form name="Username Change" action="account_username.php" method="post">
<table width="900pt" height="350pt" align="center" border="1" class="InputsBox">
<tr>
	<th>Username Change</th>
</tr>
<tr>
	<td>
	<table width="100%" height="100%" border="0">
    <tr>
		<td width='450pt'>Current Username:</td>
		<td align='center'><input type="text" name="current_username" value="<?php echo $username;?>" readonly></td>
	</tr>
	<tr>
		<td width='450pt'>New Username:</td>
		<td align='center'><input type="text" name="new_username" autofocus></td>
	</tr>
	<tr>
		<td width='450pt'>New Username:</td>
		<td align='center'><input type="text" name="new_username_confirm"></td>
	</tr>
	</table>
	</td>
</tr>
<tr height="62pt">
	<td>
	<a href='account_settings.php'><input type="button" value="Cancel" style="width:50%;height:60pt;font-size:30pt;"></a><input type="submit" value="Update" style="width:50%;height:60pt;font-size:30pt;" name="from_account_username"></td>
</tr>
</table>
</form>
</body>
</html>