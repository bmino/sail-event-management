<?php
include('../include/require_admin.php');
$team_name = $_SESSION['team_name'];

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//Filename For Download
$filename = "AMES_meet_entry_" . date('Ymd') . ".xls";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/ms-excel");


$settings2 = mysql_query("SELECT * FROM Settings WHERE Team='$team_name'") or die(mysql_error());
$settings = mysql_fetch_array($settings2,MYSQL_ASSOC);
	$malecolor = $settings['ColorMale'];
	$femalecolor = $settings['ColorFemale'];
	$rowheight = $settings['EventsRowHeight'] . 'px';
	$sortbythis = $settings['EventsSortBy'];
	$name_display_option = $settings['EventsNameDisplay'];

$result = mysql_query("SELECT FirstName,LastName,Age,Gender,ShortFree,IM,Breast,LongFree,Back,Fly FROM Events WHERE Team='$team_name' ORDER BY $sortbythis ASC") or die(mysql_error());



//Display Fields
$col_displayed = false;
while($row = mysql_fetch_row($result))
{
	foreach($row as $field)
		{
			//Display Column Titles
			if($col_displayed == false)
			{
				for($column = 0; $column < count($row); $column++)
				{
					echo mysql_field_name($result, $column)."\t";
				}
				echo "\r\n";
				$col_displayed = true;
			}
			
			//Displays Regular Fields
			if($field == '1') echo 'X';
			elseif($field == '0') echo '';
			else echo $field;
			echo "\t";
		}
	echo "\r\n";
}
?>