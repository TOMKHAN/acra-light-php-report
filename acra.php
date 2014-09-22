<?php
	putenv("TZ=Europe/Zagreb");
	$current_date = date('d-m-Y_H:i:s');
    $fileName = $current_date.'_crash_report.txt';
    $file = fopen($fileName,'w') or die('Could not create report file: ' . $fileName);
    foreach($_POST as $key => $value) {
    $reportLine = $key." = ".$value."\n";
        fwrite($file, $reportLine) or die ('Could not write to report file ' . $reportLine);
    }
    fclose($file);
	$file_content = file_get_contents($fileName);
	if (strlen($file_content) == 0){
		unlink($fileName);
		echo "You shall not pass!";
	}else {
		$to  = "yourawesomemail@gmail.com, yourawesomemailno2@hotmail.com, yourawesomemailno3@gmail.com";
		$subject = "App crashed on ".$current_date;
		$message = "Crash report in attachment!";
		$file_open = fopen( $fileName, "r" );
		$size = filesize($fileName);
		$content = fread( $file_open, $size);
		$encoded_content = chunk_split( base64_encode($content));		
		$num = md5( time() );
		$header = "From:youradress@gmail.com\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; ";
		$header .= "boundary=$num\r\n";
		$header .= "--$num\r\n";
		$header .= "Content-Type: text/plain\r\n";
		$header .= "Content-Transfer-Encoding:8bit\r\n\n";
		$header .= "$message\r\n";
		$header .= "--$num\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
		$header .= "Content-Transfer-Encoding:base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"CrashReport_$current_date.txt\"\r\n\r\n";
		$header .= "$encoded_content\r\n";
		$header .= "--$num--";
		$retval = mail ( $to, $subject, "", $header );
		unlink($fileName);
		fclose($file_open);
	}
?>