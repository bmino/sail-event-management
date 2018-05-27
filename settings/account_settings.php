<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$account_ID = $_SESSION['ID']; 
?>
<html>
<head>
<title>Account Information</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<?php
if($_SESSION['admin'] == 1)
{
	//Nav Bar
	include('../include/nav_bar.php');
}
?>
<table width="850pt" height="280pt" border="1" align="center" class="BoxesRedirect">
<tr>
    <th>Account Settings</th>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr>
    	<td><form name="Address_Info" action="account_contact.php" method="post">
        		<input type="submit" value="Personal Info"></form></td>
    	<td><form name="Change_Password" action="account_password.php" method="post">
        		<input type="submit" value="Change Password"></form></td>
  	</tr>
  	<tr>
    	<td><form name="Set_Recovery_Questions" action="set_recovery_questions.php" method="post">
        		<input type="submit" value="Recovery Questions"></form></td>
    	<td><form name="Change_Username" action="account_username.php" method="post">
        		<input type="submit" value="Change Username"></form></td>
 	</tr>
  	<tr>
    	<td><form name="Return_To_Home" action="../login_process.php" method="post">
        		<input type="submit" value="Return Home"></form></td>
    	<td><form name="Logout" action="../index.php" method="post">
        		<input type="submit" value="Logout"></form></td>
 	</tr>
	</table>
	</td>
</tr>
</table>
</body>
</html>