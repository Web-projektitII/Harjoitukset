<?php
/* curl-tiedostosiirto testipalvelimelta */
//phpinfo();

//$dataFile      = '/path/to/file';
$dataFile      = 'readme.txt';
$sftpServer    = 'test.rebex.net';
$sftpUsername  = 'demo';
$sftpPassword  = 'password';
$sftpPort      = 22;
//$sftpRemoteDir = '/files';
$sftpRemoteDir = '/';
 
//$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . basename($dataFile));
//$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort);
$ch = curl_init('sftp://' . $sftpServer . ':' . $sftpPort . $sftpRemoteDir . '/' . basename($dataFile));

 
//$fh = fopen($dataFile, 'r');
 
if (true) {
    curl_setopt($ch, CURLOPT_USERPWD, $sftpUsername . ':' . $sftpPassword);
    //curl_setopt($ch, CURLOPT_UPLOAD, true);
    curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_INFILE, $fh);
    //curl_setopt($ch, CURLOPT_INFILESIZE, filesize($dataFile));
    //curl_setopt($ch, CURLOPT_VERBOSE, true);
 
    //$verbose = fopen('php://temp', 'w+');
    //curl_setopt($ch, CURLOPT_STDERR, $verbose);
 
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
 
    if ($response) {
        echo "response:$response,Success";
    } else {
        echo "Failure";
        //rewind($verbose);
        //$verboseLog = stream_get_contents($verbose);
        //echo "Verbose information:\n" . $verboseLog . "\n";
    }
}
?>