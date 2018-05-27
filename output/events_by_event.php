<?php
include('../include/require_admin.php');
$team_name = $_SESSION['team_name'];
?>
<html>
<head>
<title>Events By Event</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//INCLUDES NAV BAR
include('../include/nav_bar.php');

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//Opens File
$fh = fopen('../text/' . $team_name . '_events_by_event.txt', 'w');
$file_path = '../text/' . $team_name . '_events_by_event.txt';
$file_name = 'events_by_event_' . date("m/d/Y",time()-3660*4 . '.txt');

//Doc Heading
echo "
<table width='100%' align='center' border='1' class='NotificationBox'>
	<tr>
		<th>Events By Event
			<a href='$file_path' download='$file_name'>
			<img src='../include/images/download.png' width='55' height='55' alt='Download' style='float:right;'>
			</a>
		</th>
	</tr>
</table>
";

//File Heading
fwrite($fh, "---------------\n");
fwrite($fh, "Events By Event\n");
fwrite($fh, "---------------\n\n");
$current_date = date("D m/d h:ia",time()-3660*4);
fwrite($fh, "$current_date\n\n");

function get_event_number($age, $gender, $event)
{
	$event_number = 0;
	if ($event == 'ShortFree') {$event_number = $event_number + 10;}
	if ($event == 'IM') {$event_number = $event_number + 20;}
	if ($event == 'Breast') {$event_number = $event_number + 30;}
	if ($event == 'LongFree') {$event_number = $event_number + 40;}
	if ($event == 'Back') {$event_number = $event_number + 50;}
	if ($event == 'Fly') {$event_number = $event_number + 60;}
	
	if ($age == '8&amp;U') {$event_number = $event_number + 1;}
	if ($age == '9-10') {$event_number = $event_number + 3;}
	if ($age == '11-12') {$event_number = $event_number + 5;}
	if ($age == '13-14') {$event_number = $event_number + 7;}
	if ($age == '15-18') {$event_number = $event_number + 9;}
	
	if ($gender == 'M') {$event_number = $event_number + 1;}
	
	return '#'.$event_number.' ';
}



/////////////
//File Body//
/////////////

$events_list = array('ShortFree', 'IM', 'Breast', 'LongFree', 'Back', 'Fly');
$age_group_list = array('8&amp;U', '9-10', '11-12', '13-14', '15-18');
$genders_list = array('F', 'M');
echo "<div class='TextualOutput'>\n";

foreach($events_list as $short_event)
{
	foreach($age_group_list as $agegroup)
	{
		foreach($genders_list as $gender)
		{
			if ($agegroup == '8&amp;U') {$group_query = 'Age<=8';}
			if ($agegroup == '9-10') {$group_query = '(Age=9 OR Age=10)';}
			if ($agegroup == '11-12') {$group_query = '(Age=11 OR Age=12)';}
			if ($agegroup == '13-14') {$group_query = '(Age=13 OR Age=14)';}
			if ($agegroup == '15-18') {$group_query = 'Age>=15';}
			if ($gender == 'M') {$full_gender = 'Boys';}
			if ($gender == 'F') {$full_gender = 'Girls';}
			$data = mysql_query("SELECT * FROM Events WHERE Team = '$team_name' AND Age <= 19 AND $short_event = 1 AND $group_query ORDER BY LastName,FirstName ASC") or die(mysql_error());
			
			//Adds Event To File
			fwrite($fh, get_event_number($agegroup, $gender, $short_event)."$full_gender " . str_replace('&amp;','&',$agegroup) . " $short_event\n");
			
			//Writes Event To Page
			echo "<b>".get_event_number($agegroup, $gender, $short_event) . "$full_gender " . $agegroup . " $short_event</b><br>\n";
			
			
			while($rowdata = mysql_fetch_array($data,MYSQL_ASSOC))
			{
				if ($gender == $rowdata['Gender'] and $rowdata["$short_event"] == 1)
				{
					//Adds Swimmer To File
					fwrite($fh, $rowdata['LastName'].', '.$rowdata['FirstName']."\n");
			
					//Adds Swimmer To Page
					echo $rowdata['LastName'].', '.$rowdata['FirstName']."<br>";
				}
			}
			fwrite($fh, "\n");
			echo "<br>";
		}
	}
}
echo "</div>\n";

//Closes File
fclose($fh);
?>
</body>
</html>