<?php
include('../include/require_admin.php');
$team_name = $_SESSION['team_name'];
?>
<html>
<head>
<title>Missing Swimmers</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
//INCLUDES NAV BAR
include('../include/nav_bar.php');

//CONNECTS TO MYSQL
include('../include/connect_mysql.php');


//Opens File
$fh = fopen('../text/' . $team_name . '_missing_swimmers.txt', 'w');
$file_path = '../text/' . $team_name . '_missing_swimmers.txt';
$file_name = 'missing_swimmers_' . date("m/d/Y",time()-3660*4 . '.txt');

//Doc Heading
echo "
<table width='100%' align='center' border='1' class='NotificationBox'>
	<tr>
		<th>Missing Swimmers
			<a href='$file_path' download='$file_name'>
			<img src='../include/images/download.png' width='55' height='55' alt='Download' style='float:right;'>
			</a>
		</th>
	</tr>
</table>
";

//File Heading
fwrite($fh, "----------------\n");
fwrite($fh, "Missing Swimmers\n");
fwrite($fh, "----------------\n\n");
$current_date = date("D m/d h:ia",time()-3660*4);
fwrite($fh, "$current_date\n");

/////////////
//File Body//
/////////////

echo "<div class='TextualOutput'>\n";

//Gets Data From Events Table
$data = mysql_query("SELECT FirstName,LastName,Age,Gender,ShortFree,IM,Breast,LongFree,Back,Fly FROM Events WHERE
						Team='$team_name' AND
						Age <= 19 AND
						ShortFree = 0 AND
						IM = 0 AND
						Breast = 0 AND
						LongFree = 0 AND
						Back = 0 AND
						Fly = 0
						ORDER BY Gender,Age,LastName,FirstName ASC") or die(mysql_error());

while($rowdata = mysql_fetch_array($data,MYSQL_ASSOC))
{
	//Displays Age Group Headings
	if(!isset($displayed_group_male_8u))
	{
		if($rowdata['Gender'] == 'M' and $rowdata['Age'] <= 8)
		{
			echo "<br>\n<b><u>".'Boys 8&amp;U:'."</u></b><br>\n";
			fwrite($fh, "\n".'Boys 8&U:'."\n");
			$displayed_group_male_8u = TRUE;
		}
	}
	if(!isset($displayed_group_female_8u))
	{
		if($rowdata['Gender'] == 'F' and $rowdata['Age'] <= 8)
		{
			echo "<br>\n<b><u>".'Girls 8&amp;U:'."</u></b><br>\n";
			fwrite($fh, "\n".'Girls 8&U:'."\n");
			$displayed_group_female_8u = TRUE;
		}
	}
	
	if(!isset($displayed_group_male_9_10))
	{
		if($rowdata['Age'] >= 9 and $rowdata['Age'] <= 10 and $rowdata['Gender'] == 'M')
		{
			echo "<br>\n<b><u>Boys 9-10:</u></b><br>\n";
			fwrite($fh, "\nBoys 9-10:\n");
			$displayed_group_male_9_10 = TRUE;
		}
	}
	if(!isset($displayed_group_female_9_10))
	{
		if($rowdata['Age'] >= 9 and $rowdata['Age'] <= 10 and $rowdata['Gender'] == 'F')
		{
			echo "<br>\n<b><u>Girls 9-10:</u></b><br>\n";
			fwrite($fh, "\nGirls 9-10:\n");
			$displayed_group_female_9_10 = TRUE;
		}
	}
	
	if(!isset($displayed_group_male_11_12))
	{
		if($rowdata['Age'] >= 11 and $rowdata['Age'] <= 12 and $rowdata['Gender'] == 'M')
		{
			echo "<br>\n<b><u>Boys 11-12:</u></b><br>\n";
			fwrite($fh, "\nBoys 11-12:\n");
			$displayed_group_male_11_12 = TRUE;
		}
	}
	if(!isset($displayed_group_female_11_12))
	{
		if($rowdata['Age'] >= 11 and $rowdata['Age'] <= 12 and $rowdata['Gender'] == 'F')
		{
			echo "<br>\n<b><u>Girls 11-12:</u></b><br>\n";
			fwrite($fh, "\nGirls 11-12:\n");
			$displayed_group_female_11_12 = TRUE;
		}
	}
	
	if(!isset($displayed_group_male_13_14))
	{
		if($rowdata['Age'] >= 13 and $rowdata['Age'] <= 14 and $rowdata['Gender'] == 'M')
		{
			echo "<br>\n<b><u>Boys 13-14:</u></b><br>\n";
			fwrite($fh, "\nBoys 13-14:\n");
			$displayed_group_male_13_14 = TRUE;
		}
	}
	if(!isset($displayed_group_female_13_14))
	{
		if($rowdata['Age'] >= 13 and $rowdata['Age'] <= 14 and $rowdata['Gender'] == 'F')
		{
			echo "<br>\n<b><u>Girls 13-14:</u></b><br>\n";
			fwrite($fh, "\nGirls 13-14:\n");
			$displayed_group_female_13_14 = TRUE;
		}
	}
	
	if(!isset($displayed_group_male_15_18))
	{
		if($rowdata['Age'] >= 15 and $rowdata['Age'] <= 19 and $rowdata['Gender'] == 'M')
		{
			echo "<br>\n<b><u>Boys 15-18:</u></b><br>\n";
			fwrite($fh, "\nBoys 15-18:\n");
			$displayed_group_male_15_18 = TRUE;
		}
	}
	if(!isset($displayed_group_female_15_18))
	{
		if($rowdata['Age'] >= 15 and $rowdata['Age'] <= 19 and $rowdata['Gender'] == 'F')
		{
			echo "<br>\n<b><u>Girls 15-18:</u></b><br>\n";
			fwrite($fh, "\nGirls 15-18:\n");
			$displayed_group_female_15_18 = TRUE;
		}
	}


	//Displays Swimmer Name
	echo $rowdata['LastName'].', '.$rowdata['FirstName']."<br>\n";
	fwrite($fh, $rowdata['LastName'].', '.$rowdata['FirstName']."\n");
	
}

echo "</div>\n";

//Closes File
fclose($fh);
?>
</body>
</html>
