<?
echo 'LD_LIBRARY_PATH ==>'.getenv("LD_LIBRARY_PATH");
echo '<br>ORACLE_HOME ==>'.getenv("ORACLE_HOME");
echo '<BR>NLS_LANG==>'.getenv("NLS_LANG");
echo '<br>TNS_ADMIN ==>'.getenv("TNS_ADMIN");
if (oci_new_connect('siw','siw')) echo 
'===============> 
conexão ok'; else echo '===============> conexão com problemas';
$comando = '$ORACLE_HOME/bin/sqlplus system/xyz345aix @/var/www/html/siw_files/10135/tmp/recompila.sql;';
$comando = '/var/www/html/siw_files/10135/tmp/aplica_patch-linux.sh;';
$comando = 'set;';
$result = shell_exec($comando);
if(!$result) {
 echo("<br>Não foi possível executar o comando");
} else {
  echo('<br><br><b>Comando executado: ['.$comando.']</b><pre>'.$result.'</pre>');
}
?>
