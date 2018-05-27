<?php
include('../include/require_admin.php');
$team_name = $_SESSION['team_name'];
?>
<html>
<head>
<title>Contact Info</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//INCLUDES NAV BAR
include('../include/nav_bar.php');

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//Opens File
$fh = fopen('../text/' . $team_name . '_contact_info.txt', 'w');
$file_path = '../text/' . $team_name . '_contact_info.txt';
$file_name = 'contact_info_' . date("m/d/Y",time()-3660*4 . '.txt');

//Doc Heading
echo "
<table width='100%' align='center' border='1' class='NotificationBox'>
	<tr>
		<th>Contact Info
			<a href='$file_path' download='$file_name'>
			<img src='../include/images/download.png' width='55' height='55' alt='Download' style='float:right;'>
			</a>
		</th>
	</tr>
</table>
";

//File Heading
fwrite($fh, "------------\n");
fwrite($fh, "Contact Info\n");
fwrite($fh, "------------\n\n");
$current_date = date("D m/d h:ia",time()-3660*4);
fwrite($fh, "$current_date\n\n");


/////////////
//File Body//
/////////////


echo "<div class='TextualOutput'>\n";

//Table Initializers
$display_items = array('LastName','Email','Phone','Street','City','Zipcode');
$display_names = array('Last Name','Email','Phone','Street','City','Zipcode');

//Begin Table
echo "<table align='center'>\n";
echo "<tr>\n";
	//Table Headers
	foreach ($display_names as $header)
	{
		echo "\t<th>$header</th>\n";
	}
echo "<tr>\n";

//Display Contact Information
$contact_information = mysql_query("SELECT * FROM Accounts WHERE Team='$team_name' AND AdminPriv=0 ORDER BY LastName,Email") or die(mysql_error());
while ($contact = mysql_fetch_array($contact_information,MYSQL_ASSOC))
{
	echo "<tr>\n";
	foreach ($display_items as $display)
	{
		echo "\t<td>".$contact["$display"]."</td>\n";
	}
	echo "</tr>\n";
}

echo "</div>\n";

?>
</body>
</html>









