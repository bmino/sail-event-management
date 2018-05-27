<?php
include('../include/require_overlord.php');
?>
<html>
<head>
<title>Log: Logins</title>
<link rel="stylesheet" type="text/css" href="../settings/stylesheet.css">
</head>
<body>
<?php
if (isset($_POST['clear_log']))
{
    $fh = fopen('../text/log.txt','w');
	fclose($fh);
}
$file_name = 'master_log_' . date("m/d/Y",time()-3660*4);
?>
<table border='0' align="center" class="InputsBox">
<tr>
	<td>
        <table width='100%' border='1'>
        <tr>
            <th>Login Records
            	<a href='../text/log.txt' download='<?php echo $file_name; ?>'>
				<img src='../include/images/download.png' width='55' height='55' alt='Download' style='float:right;'>
				</a>
                </th>
        </tr>
        </table>
    </td>
</tr>

<tr>
<td>
    <table width='100%' border='1'>
    <tr align='center'>
        <td><span class='LogDisplayTitle'>Day</span></td>
        <td><span class='LogDisplayTitle'>Date</span></td>
        <td><span class='LogDisplayTitle'>Time</span></td>
        <td><span class='LogDisplayTitle'>Result</span></td>
        <td><span class='LogDisplayTitle'>Username</span></td>
        <td><span class='LogDisplayTitle'>Password</span></td>
        <td><span class='LogDisplayTitle'>Team</span></td>
    </tr>
    <?php
    //Fills Log_Data Array With Log Information
    $log_data = array();
    if($fh = fopen('../text/log.txt','r'))
    {
        while (!feof($fh))
        {
            $log_data[] = fgets($fh,9999);
        }
    }
    
    //Fills Table With Log_Data Array
    foreach($log_data as $line)
    {
        if(str_replace("\n",'',$line) == '') {break;}
		$fields = preg_split("/[\t]/", $line);
		
		//Splits Date Into Chunks And Adds To Fields
		$split_date = explode(' ', $fields[0]);
		$split_date = array_reverse($split_date);
		unset($fields[0]);
		foreach($split_date as $individual_date_field) {array_unshift($fields, $individual_date_field);}
		
		//Chooses Color For Row
		if($fields[3] == 'SUCCESS') {echo "\t<tr bgcolor='#00FF00'>\n";}
        elseif($fields[3] == 'FAILURE') {echo "\t<tr bgcolor='#FF0000'>\n";}
		else {echo "\t<tr>\n";}
		
        foreach($fields as $field)
        {
			$field = str_replace("\n",'',$field); 
			echo "\t\t<td><span class='LogDisplay'>".$field."</span></td>\n";
        }
        echo "\t</tr>\n";
    }
    ?>
    </table>
</td>
</tr>


<tr>
<td><form action='view_log.php' method='post'><input type='submit' value='Clear' name='clear_log' onClick="return confirm('Are you sure you want to clear the log?')" style="height:40pt;width:25%;font-size:22pt;"></form><a href='overwatch.php'><input type='button' value='Return Home' style="height:40pt;width:75%;font-size:22pt;"></a></td>
</tr>

</table>

</body>
</html>