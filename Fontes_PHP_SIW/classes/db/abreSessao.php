<?
include_once("ConnectionManagerFactory.php");
/**
* class abreSessao
*
* { Description :- 
*    This class returns a dbms connection pointing to de target database
* }
*/

class abreSessao {
   function getInstanceOf($DB_TYPE) {
     $dbms = ConnectionManagerFactory::getInstanceOf($DB_TYPE);
     $dbms->doConnection();
     $dbms->selectDatabase();
     if ($DB_TYPE==MSSQL) { ini_set('mssql.datetimeconvert', 0);}
     if ($DB_TYPE==PGSQL) { pg_query($dbms->getConnectionHandle(), "set client_encoding to 'LATIN1'"); }
     return $dbms->getConnectionHandle();
   }
}    
?>
