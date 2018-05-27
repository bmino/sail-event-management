<?php
include('../include/require_login.php');
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$account_ID = $_SESSION['ID']; 
?>
<html>
<head>
<title>Contact Information</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style type="text/css">
input[type='text'],[type='email'],[type='number'] {width:98%;}
</style>
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//
////Should Process Form Yes/No
//

if(isset($_POST['from_account_contact']))
{
	//Gets Data From Form
	$PhoneNumber = mysql_real_escape_string($_POST['PhoneNumber']);
	$Email = mysql_real_escape_string($_POST['Email']);
	$Street = mysql_real_escape_string($_POST['Street']);
	$City = mysql_real_escape_string($_POST['City']);
	$Zipcode = mysql_real_escape_string($_POST['Zipcode']);
	
	//Updates Email of Current Children
		// 1) Get Pre-Updated Email From Login Account
		$email_query = mysql_query("SELECT Email FROM Accounts WHERE ID='$account_ID'") or die(mysql_query());
		$email_info = mysql_fetch_array($email_query,MYSQL_ASSOC);
		$MasterOldEmail = $email_info['Email'];
		
		// 2) Changes All Children's Email To Match New Master Email
		mysql_query("UPDATE Events SET Email='$Email' WHERE Email='$MasterOldEmail'") or die(mysql_error());

	//Update Information
	mysql_query("UPDATE Accounts SET Phone='$PhoneNumber',Street='$Street',City='$City',Zipcode='$Zipcode',Email='$Email' WHERE ID='$account_ID'") or die(mysql_error());
	
	
	
	echo "<script>alert('Contact information updated.'); window.location.href = 'account_settings.php';</script>";
	exit;
}


//
////Begins Normal Form
//


//Include Nav Bar If From Menu
if($_SESSION['admin'] == 1)
{
	//Nav Bar
	include('../include/nav_bar.php');
}

//Gets Already Set Information
$contact_query = mysql_query("SELECT * FROM Accounts WHERE ID='$account_ID'") or die(mysql_query());
$contact_info = mysql_fetch_array($contact_query,MYSQL_ASSOC);
$Email = $contact_info['Email'];
$Phone = $contact_info['Phone'];
$Street = $contact_info['Street'];
$City = $contact_info['City'];
$Zipcode = $contact_info['Zipcode'];
?>
<form name="Contact Process" action="account_contact.php" method="post">
<table width="100%" height="450pt" align="center" border="1" class="InputsBox">
<tr>
    <th>Update Contact Info</th>
</tr>
<tr>
    <td>
    <table width="100%" height="100%" border="0">
  	<tr>
    	<td width='400pt'>Phone Number:</td>
    	<td align='center'><input type="number" name="PhoneNumber" value="<?php echo $Phone; ?>"></td>
  	</tr>
  	<tr>
    	<td width='400pt'>Email Address:</td>
    	<td align='center'><input type="email" name="Email" value="<?php echo $Email; ?>"></td>
  	</tr>
  	<tr>
    	<td width='400pt'>Street:</td>
    	<td align='center'><input type="text" name="Street" value="<?php echo $Street; ?>"></td>
  	</tr>
  	<tr>
    	<td width='400pt'>City:</td>
    	<td align='center'><input type="text" name="City" value="<?php echo $City; ?>"></td>
  	</tr>
  	<tr>
    	<td width='400pt'>Zipcode:</td>
    	<td align='center'><input type="number" name="Zipcode" value="<?php echo $Zipcode; ?>"></td>
  	</tr>
	</table>
	</td>
</tr>
<tr height="50pt">
	<td>
    <a href='account_settings.php'><input type='button' value='Cancel' style="width:50%;height:50pt;font-size:30pt;"></a><input type="submit" value="Update" style="width:50%;height:50pt;font-size:30pt;" name="from_account_contact"></td>
</tr>
</table>
</form>
</body>
</html>