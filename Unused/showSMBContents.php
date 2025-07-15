<?php
$smbServer = "nebaservice";
$smbShare = getenv('SMB_SHARE');
$smbUsername = "your_smb_username"; 
$smbPassword = "your_smb_password"; 

// comando que lista o conteúdo da pasta
$command = "smbclient //$smbServer/$smbShare -U $smbUsername%$smbPassword -c 'ls'";

// execução do comando
$output = shell_exec($command);

// verifica se o comando foi executado
if ($output === null) {
    die("Failed to connect to the SMB share. Please check your credentials and server details.");
}

// mostra os conteúdos da pasta
echo "<h1>Contents of SMB Share: //$smbServer/$smbShare</h1>";
echo "<pre>$output</pre>";
?>