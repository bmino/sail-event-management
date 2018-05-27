<?php
include('../include/require_overlord.php');
if(isset($_SESSION['Add_Team_ButtonText'])) {unset($_SESSION['Add_Team_ButtonText']);}
?>
<html>
<head>
<title>Overwatch</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>


<table width="75%" border='0' align="center" class="BoxesRedirect">
<tr>
	<td>
        <table width='100%' border='0'>
        <tr>
            <th>SuperAdmin Account</th>
        </tr>
        </table>
    </td>
</tr>
<tr>
	<td>
        <table width='100%' border='0' class='BoxesRedirect'>
        <tr>
            <td><form action="add_team.php" method="post">
                <input type="submit" value="Add Team">
                </form></td>
            <td><form action="../settings/account_settings.php" method="post">
                <input type="submit" value="Account Info">
                </form></td>
        </tr>
        <tr>
            <td><form action="manage_teams.php" method="post">
                <input type="submit" value="Manage Teams">
                </form></td>
            <td><form action="view_log.php" method="post">
                <input type="submit" value="View Log">
                </form></td>
        </tr>
        <tr>
            <td></td>
            <td><form action="../index.php" method="post">
                <input type="submit" value="Logout">
                </form></td>
        </tr>
		</table>
	</td>
</tr>
</table>


</body>
</html>
