<?php
include('../include/require_overlord.php');
?>
<html>
<head>
<title>Add/Drop Team</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//Does If Form Has Been Submitted
if(isset($_POST['submitted_add_team']))
{
	//Get Information From Form
	$teamname = mysql_real_escape_string($_POST['TeamName']);
	$adminname = mysql_real_escape_string($_POST['AdminName']);
	$adminpass = mysql_real_escape_string($_POST['AdminPass']);
	
	//Checks If Team Name Already Exists
	$teams = array();
	$teams_query = mysql_query("SELECT DISTINCT Team FROM Accounts WHERE AdminPriv='1'") or die(mysql_error());
	while($team = mysql_fetch_array($teams_query))
	{
		$teams[] = $team[0];
	}
	
	if(in_array($teamname,$teams))
	{
		?>
        <form action="add_team.php" method="post">
        <table border='1' width='700pt' height='200pt' style='top:50%;left:50%;margin-left:-350;margin-top:-100;position:absolute;' class="NotificationBox">
            <tr>
                <th>Add Team Error</th>
            </tr>
            <tr>
                <td>
                    [<?php echo $teamname;?>] already entered!
                </td>
            </tr>
            <tr height='50pt'>
                <td>
                    <input type='submit' value='Return To Entry' style='width:100%;height:50pt;font-size:22pt;'>
                </td>
            </tr>
        </table>
        </form>
		<?php
	}
	else
	{
		//Create New Team Admin
		mysql_query("INSERT INTO Accounts (Username,Password,AdminPriv,Team) VALUES ('$adminname','$adminpass','1','$teamname')") or die(mysql_error());
		mysql_query("INSERT INTO Settings (Team) VALUES ('$teamname')") or die(mysql_error());
		?>
		<form action="add_team.php" method="post">
        <table border='1' width='700pt' height='200pt' style='top:50%;left:50%;margin-left:-350;margin-top:-100;position:absolute;' class="NotificationBox">
            <tr>
                <th>Add Team</th>
            </tr>
            <tr>
                <td>
                    [<?php echo $teamname;?>] succesfully entered!
                </td>
            </tr>
            <tr height='50pt'>
                <td>
                    <input type='submit' value='Return To Entry' style='width:100%;height:50pt;font-size:22pt;'>
                </td>
            </tr>
        </table>
        </form>
        <?php
	}
	
	//Display 'Done' Instead of 'Cancel'
	$_SESSION['Add_Team_ButtonText'] = 'Done';
	exit;
}




if(!isset($_SESSION['Add_Team_ButtonText'])) {$_SESSION['Add_Team_ButtonText'] = 'Cancel';}
?>
<form action="add_team.php" method="post" autocomplete="off">
<table width="900pt" border="1" align="center" class="InputsBox">
  <tr height='80pt'>
    <th>Team Entry</th>
  </tr>
  <tr>
    <td>
        <table width="100%" height="100%" border="0">
        <tr>
          <td width="350pt">Team:</td>
          <td align="left"><input type="text" name="TeamName" style="width:98%;" autofocus></td>
        </tr>
        <tr>
          <td width="350pt">Admin User:</td>
          <td align="left"><input type="text" name="AdminName" style="width:98%;"></td>
        </tr>
        <tr>
          <td width="350pt">Admin Pass:</td>
          <td align="left"><input type="password" name="AdminPass" style="width:98%;"></td>
        </tr>
        </table>
        <table height="100%" width="100%">
        	<tr>
            	<td><a href="overwatch.php"><input type="button" value="<?php echo $_SESSION['Add_Team_ButtonText']; ?>" style="height:60pt;width:50%;font-size:22pt;"></a><input type="submit" name="submitted_add_team" value="Update" style="height:60pt;width:50%;font-size:22pt;"></td>
            </tr>
        </table>
	</td>
  </tr>
</table>
</form>

</body>
</html>