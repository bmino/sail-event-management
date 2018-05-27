<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
if(isset($_SESSION['ID'])) {$account_ID = $_SESSION['ID'];}
?>
<html>
<head>
<title>Password Change</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style>
input[type='password'] {width:95%;}
</style>
</head>
<body>
<?php

//
////Process Form Yes/No
//

if(isset($_POST['from_account_password']))
{
	$new_password = $_POST['new_password'];
	$new_password_confirm = $_POST['new_password_confirm'];
	
	//If Temporary Password In Use
	if(!isset($_POST['old_password']))
	{
		//Changes Old Password Variable
		$old_password = $password;
		$_SESSION['not_need_old_pass'] = TRUE;
	}
	//If NO Temporary Password In Use
	else
	{
		$old_password = $_POST['old_password'];
	}
	
	//CONNECTS TO MYSQL
	include('../include/connect_mysql.php');
	$validate_query = mysql_query("SELECT * FROM Accounts WHERE ID='$account_ID' AND Password='$old_password'") or die(mysql_error());
	$validation = mysql_fetch_array($validate_query,MYSQL_ASSOC);
	$user = $validation['Username'];
	
	//Checks if User/Pass is Found
	if (!$validation)
	{
		//Notifies User
		echo "<script>alert('Current password is not correct!'); window.location.href = 'account_password.php';</script>";
		exit;
	}
	
	//Checks if New Passwords are Identical
	elseif ($new_password != $new_password_confirm)
	{
		//Notifies User
		echo "<script>alert('Confirm password does not match!'); window.location.href = 'account_password.php';</script>";
		exit;
	}
	
	//Update Password
	mysql_query("UPDATE Accounts SET Password='$new_password', TempPass='' WHERE ID='$account_ID'") or die(mysql_error());
	$_SESSION['password'] = $new_password;
	
	//Allows Old Password Box To Appear Next Time
	if(isset($_SESSION['not_need_old_pass'])) unset($_SESSION['not_need_old_pass']);

	//Notifies User of Success
	echo "<script>alert('Password successfully changed!'); window.location.href = '../login_process.php';</script>";
	exit;
}


//
////Form Begins Here
//


//Include Nav Bar If From Menu
if($_SESSION['admin'] != 37 and $_SESSION['admin'] != 0)
{
	//Nav Bar
	include('../include/nav_bar.php');
}

//If Temporary Password In Use
if(isset($_POST['temp_pass_fill']))
{
	//Stops Promp of Old Password
	$_SESSION['not_need_old_pass'] = TRUE;
}
	
?>
<form name="Password Change" action="account_password.php" method="post" autocomplete='off'>
<table width="800pt" height="350pt" align="center" border="1" class="InputsBox">
<tr>
	<th>Password Change</th>
</tr>
<tr>
	<td>
	<table width="100%" height="100%" border="0">
	<?php if(!isset($_SESSION['not_need_old_pass']))
	{?>
    <tr>
		<td width='400pt'>Current Password:</td>
		<td align='center'><input type="password" name="old_password" autofocus autocomplete='off'></td>
	</tr>
	<?php
	//Allows Old Password Box To Appear Next Time
	if(isset($_SESSION['not_need_old_pass'])) unset($_SESSION['not_need_old_pass']);
    }?>
	
    <tr>
		<td width='400pt'>New Password:</td>
		<td align='center'><input type="password" name="new_password"></td>
	</tr>
	<tr>
		<td width='400pt'>New Password:</td>
		<td align='center'><input type="password" name="new_password_confirm"></td>
	</tr>
	</table>
	</td>
</tr>
<tr height="62pt">
	<td>
	<input type="hidden" value="yes" name="from_account_password">
	<a href='account_settings.php'><input type="button" value="Cancel" style="width:50%;height:60pt;font-size:30pt;"></a><input type="submit" value="Update" style="width:50%;height:60pt;font-size:30pt;"></td>
</tr>
</table>
</form>
</body>
</html>