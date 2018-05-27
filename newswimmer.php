<?php
include('include/require_admin.php');
?>
<html>
<head>
<title>Add Swimmer</title>
<link rel="stylesheet" type="text/css" href="settings/stylesheet.css">
<script type="text/javascript">
function validateForm()
{
var first=document.forms["NewSwimmerForm"]["firstnameF"].value;
var last=document.forms["NewSwimmerForm"]["lastnameF"].value;
var age=document.forms["NewSwimmerForm"]["ageF"].value;
var email=document.forms["NewSwimmerForm"]["emailF"].value;
if (first==null || first=="")
  {
  alert("First name must be filled out");
  return false;
  }
if (first.length > 15)
  {
  alert("First name is too long");
  return false;
  }
if (last==null || last=="")
  {
  alert("Last name must be filled out");
  return false;
  }
if (last.length > 15)
  {
  alert("Last name is too long");
  return false;
  }
if (age==null || age=="")
  {
  alert("Age must be filled out");
  return false;
  }
if (email==null || email=="")
  {
  alert("Email must be filled out");
  return false;
  }
}
</script>
</head>
<body>
<?php
//Nav Bar
include('include/nav_bar.php');

if(isset($_POST['from_new_swimmer']))
{
	//CONNECTS TO MYSQL
	include('include/connect_mysql.php');
	
	//Gets Information From Form
	if (isset($_POST['firstnameF']))
		{$First = mysql_real_escape_string(trim($_POST['firstnameF']));}
	if (isset($_POST['lastnameF']))
		{$Last  = mysql_real_escape_string(trim($_POST['lastnameF']));}
	if (isset($_POST['ageF']))
		{$Age = mysql_real_escape_string(trim($_POST['ageF']));}
	if (isset($_POST['genderF']))
		{$Gender = mysql_real_escape_string(trim($_POST['genderF']));}
	if (isset($_POST['emailF']))
		{$Email = mysql_real_escape_string(trim($_POST['emailF']));}
	$team_name = $_SESSION['team_name'];


	
	//Searches for Duplicate
	function IsSwimmerEntered($First,$Last,$Team,$Email)
	{
		$query = mysql_query("SELECT * FROM Events WHERE Team='$Team' AND Email='$Email'") or die(mysql_error());
		while ($row_return = mysql_fetch_array($query,MYSQL_ASSOC))
		{
			if ($First == $row_return['FirstName'] and $Last == $row_return['LastName'])
			{
				return $row_return['ID'];
			}
		}
	}
	
	//Searches for Account Matching Swimmer
	function CreateAccount($Last,$Team,$Email)
	{
		//Checks If Account Is Already Created
		$query = mysql_query("SELECT Email FROM Accounts WHERE Team='$Team'") or die(mysql_error());
		while($EmailList = mysql_fetch_array($query,MYSQL_ASSOC))
		{
			if ($EmailList['Email'] == $Email)
			{
				//Ends Search If Found
				return FALSE;
			}
		}
		//Create Account
		mysql_query("INSERT INTO Accounts (Username, Password, LastName, Team, Email) VALUES ('$Email', '', '$Last', '$Team', '$Email')") or die(mysql_error());
		return TRUE;
	}
	
	
	
	if(!IsSwimmerEntered($First,$Last,$team_name,$Email))
	{
		//Enter Swimmer
		mysql_query("INSERT INTO Events (FirstName, LastName, Age, Gender, Team, Email) VALUES ('$First','$Last','$Age','$Gender', '$team_name', '$Email')") or die(mysql_error());
		CreateAccount($Last,$team_name,$Email);
		printf("<script>location.href='newswimmer.php'</script>");
	}
	else
	{
		$ID = IsSwimmerEntered($First,$Last,$team_name,$Email);
		echo"
		<table width='600pt' align='center' border='1' class='NotificationBox'>
		<tr>
			<th>Edit Swimmer</th>
		</tr>
		<tr>
			<td align='center'>
				$First $Last is already entered!
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
		<tr align='center' valign='middle' height='52pt'>
			<td>
				<form action='newswimmer.php' method='post'>
				<input type='submit' value='Cancel' style='width:100%;height:50pt;font-size:30pt;'>
				</form>
			</td>
		</tr>
		</table>
		";
	}
	exit;
}
?>

<form action="newswimmer.php" method="post" name="NewSwimmerForm" onSubmit="return validateForm()">
<table width="750pt" border="1" align="center" class="InputsBox">
  <tr>
    <th>Swimmer Entry</th>
  </th>
  <tr>
    <td>
        <table width="100%" height="100%" border="0">
        <tr>
          <td width="300pt">First Name:</td>
          <td align="left"><input type="text" name="firstnameF" style="width:98%;" autofocus></td>
        </tr>
        <tr>
          <td width="300pt">Last Name:</td>
          <td align="left"><input type="text" name="lastnameF" style="width:98%;"></td>
        </tr>
        <tr>
          <td width="300pt">Gender:</td>
          <td align="left">
          <label><input type="radio" name="genderF" value="M" width="50pt" style="vertical-align:middle" checked="checked">Male</label>
          <label><input type="radio" name="genderF" value="F" width="50pt" style="vertical-align:middle">Female</label></td>
        </tr>
        <tr>
          <td width="300pt">Age:</td>
          <td align="left"><input type="number" name="ageF" style="width:130pt;"></td>
        </tr>
        <tr>
          <td width="300pt">Email:</td>
          <td align="left"><input type="email" name="emailF" style="width:98%;"></td>
        </tr>
        </table>
        <table height="100%" width="100%">
        	<tr align="right">
            	<td><input type="submit" value="Update" style="height:50pt;width:100%;font-size:22pt;" name="from_new_swimmer"></td>
            </tr>
        </table>
	</td>
  </tr>
</table>
</form>
</body>
</html>