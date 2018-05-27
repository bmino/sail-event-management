<?php
include('../include/require_admin.php');
$team_name = $_SESSION['team_name'];
?>
<html>
<head>
<title>Events By Swimmer</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//INCLUDES NAV BAR
include('../include/nav_bar.php');

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');

//Opens File
$fh = fopen('../text/' . $team_name . '_events_by_swimmer.txt', 'w');
$file_path = '../text/' . $team_name . '_events_by_swimmer.txt';
$file_name = 'events_by_swimmer_' . date("m/d/Y",time()-3660*4 . '.txt');

//Doc Heading
echo "
<table width='100%' align='center' border='1' class='NotificationBox'>
	<tr>
		<th>Events By Swimmer
			<a href='$file_path' download='$file_name'>
			<img src='../include/images/download.png' width='55' height='55' alt='Download' style='float:right;'>
			</a>
		</th>
	</tr>
</table>
";

//File Heading
fwrite($fh, "-----------------\n");
fwrite($fh, "Events By Swimmer\n");
fwrite($fh, "-----------------\n\n");
$current_date = date("D m/d h:ia",time()-3660*4);
fwrite($fh, "$current_date\n");

/////////////
//File Body//
/////////////

echo "<div class='TextualOutput'>\n";

//Gets Data From Events Table
$data = mysql_query("SELECT FirstName,LastName,Age,Gender,ShortFree,IM,Breast,LongFree,Back,Fly FROM Events WHERE Team='$team_name' AND Age <= 19 ORDER BY Gender,Age,LastName,FirstName ASC") or die(mysql_error());

while($rowdata = mysql_fetch_array($data,MYSQL_ASSOC))
{
	//Displays Male and Female Heading
	if(!isset($displayed_gender_male))
	{
		if($rowdata['Gender'] == 'M')
		{
			echo "<br><br>\n\n<b><u>Male:</u></b><br>\n";
			fwrite($fh, "\n\nMale:\n");
			$displayed_gender_male = TRUE;
		}
	}
	if(!isset($displayed_gender_female))
	{
		if($rowdata['Gender'] == 'F')
		{
			echo "<br><br>\n\n<b><u>Female:</u></b><br>\n";
			fwrite($fh, "\n\nFemale:\n");
			$displayed_gender_female = TRUE;
		}
	}
	
	//Begins Loop Setup
	$poss_events = array('ShortFree','IM','Breast','LongFree','Back','Fly');
	$full_name_events = array('Short Free','Individual Medley','Breaststroke','Long Free','Backstroke','Butterfly');
	
	//Displays Swimmer Name
	if(	$rowdata['ShortFree'] == 1 or
		$rowdata['IM'] == 1 or
		$rowdata['Breast'] == 1 or 
		$rowdata['LongFree'] == 1 or 
		$rowdata['Back'] == 1 or 
		$rowdata['Fly'] == 1)
		{
			echo "<br>\n<b>".$rowdata['LastName'].', '.$rowdata['FirstName']."</b><br>\n";
			fwrite($fh, "\n".$rowdata['LastName'].', '.$rowdata['FirstName']."\n");
		}
	
	//Displays Events
	for($i=0; $i<count($poss_events); $i++)
	{
		if($rowdata["$poss_events[$i]"] != 0)
		{
			if($rowdata['Age'] <= 8) 							{$age_boost = 1;}
			if($rowdata['Age'] >= 9  and $rowdata['Age'] <= 10) {$age_boost = 3;}
			if($rowdata['Age'] >= 11 and $rowdata['Age'] <= 12) {$age_boost = 5;}
			if($rowdata['Age'] >= 13 and $rowdata['Age'] <= 14) {$age_boost = 7;}
			if($rowdata['Age'] >= 15)							{$age_boost = 9;}
			if($rowdata['Gender'] == 'M') {$gender_boost = 1;}
			else {$gender_boost = 0;}
			$event_number = 10+$i*10+$age_boost+$gender_boost;
			//Prints The Event
			echo '#'.$event_number." $full_name_events[$i]<br>\n";
			fwrite($fh, '#'.$event_number." $full_name_events[$i]\n");
		}
	}
}

echo "</div>\n";

//Closes File
fclose($fh);
?>
</body>
</html>
