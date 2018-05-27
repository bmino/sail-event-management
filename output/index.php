<?php
include('../include/require_admin.php');
?>
<html>
<head>
<title>View Selector</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//INCLUDES NAV BAR
include('../include/nav_bar.php');
?>

<table align="center" width="85%" border="1" class="BoxesRedirect">
<tr>
	<th>View Selector</th>
</tr>
<tr>
	<td>
        <table width="100%" border='0'>
        <tr>
            <td><form action="events_by_swimmer.php" method="post">
                <input type="submit" value="Events By Swimmer">
                </form></td>
            <td><form action="outputEXCEL.php" method="post">
                <input type="submit" value="Meet Entry">
                </form></td>
        </tr>
        <tr>
            <td><form action="events_by_event.php" method="post">
                <input type="submit" value="Events By Event">
                </form></td>
            <td><form action="missing_swimmers.php" method="post">
                <input type="submit" value="Absentees">
                </form></td>
        </tr>
        <tr>
            <td></td>
            <td><form action="contact_info.php" method="post">
                <input type="submit" value="Contact Info">
                </form></td>
        </tr>
        </table>
    </td>
</tr>
</table>


</body>
</html>
