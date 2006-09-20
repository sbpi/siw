<?
echo 'LD_LIBRARY_PATH ==>'.getenv("LD_LIBRARY_PATH");
echo '<br>ORACLE_HOME ==>'.getenv("ORACLE_HOME");
echo '<BR>NLS_LANG==>'.getenv("NLS_LANG");
echo '<br>TNS_ADMIN ==>'.getenv("TNS_ADMIN");
if (oci_new_connect('siw','siw')) echo 
'===============> 
conexão ok'; else echo '===============> conexão com problemas';
?>
