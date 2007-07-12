<?php
include_once('DatabaseQueries.php');
include_once('DBTypes.php');


/**
* class DatabaseQueriesFactory
*
* { Description :- 
*  This class is a factory returning an object of specified Database to execute queries/procs.
* }
*/

class DatabaseQueriesFactory {
  function getInstanceOf($query, $conHandle, $params) {
    extract($GLOBALS);
    // Se a gera��o de log estiver ativada, registra.
    if ($conLog && strpos(strtoupper($query),'SP_PUT')!==false) {
      // Define o caminho fisico do diret�rio e do arquivo de log
      $l_caminho = $conLogPath;
      $l_arquivo = $l_caminho.$_SESSION['P_CLIENTE'].'/'.date(Ymd).'.log';

      // Verifica a necessidade de cria��o dos diret�rios de log
      if (!file_exists($l_caminho)) mkdir($l_caminho);
      if (!file_exists($l_caminho.$_SESSION['P_CLIENTE'])) mkdir($l_caminho.$_SESSION['P_CLIENTE']);
      
      // Abre o arquivo de log
      $l_log = @fopen($l_arquivo, 'a');
      
      fwrite($l_log, '['.date(ymd.'_'.Gis.'_'.time()).']'.$crlf);
      fwrite($l_log, 'Usu�rio: '.$_SESSION['NOME_RESUMIDO'].' ('.$_SESSION['SQ_PESSOA'].')'.$crlf);
      fwrite($l_log, 'IP     : '.$_SERVER['REMOTE_ADDR'].$crlf);
      fwrite($l_log, 'Comando: '.$query.$crlf);
      if (is_array($params)) {
        $l_par = $crlf;
        foreach ($params as $k => $v) $l_par .= '   '.$k.' ['.$v[0].']'.$crlf;
        fwrite($l_log, 'Par�metros: '.$l_par.$crlf);
      }
      // Fecha o arquivo e o diret�rio de log
      @fclose($l_log);
      @closedir($l_caminho); 
    }

    if (@oci_server_version($conHandle)) {
      switch($_SESSION['DBMS']) {
        case ORA8  :
          if (empty($params)) return new OraDatabaseQueries($query, $conHandle); 
          else  return new OraDatabaseQueryProc($query, $conHandle, $params); 
          break;
        case ORA9  :
          if (empty($params)) return new OraDatabaseQueries($query, $conHandle); 
          else  return new OraDatabaseQueryProc($query, $conHandle, $params); 
          break;
        case ORA10  :
          if (empty($params)) return new OraDatabaseQueries($query, $conHandle); 
          else  return new OraDatabaseQueryProc($query, $conHandle, $params); 
          break;
        }
    } elseif (is_array(pg_version($conHandle))) {
      if (empty($params)) return new PgSqlDatabaseQueries($query, $conHandle);
      else return new PgSqlDatabaseQueryProc($query, $conHandle, $params); 
    } else {
      if (empty($params)) return new MSSqlDatabaseQueries($query, $conHandle); 
      else  return new MSSqlDatabaseQueryProc($query, $conHandle, $params); 
    }
  }
}
?>
