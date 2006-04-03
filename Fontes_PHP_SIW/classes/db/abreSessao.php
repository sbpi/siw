<?
include_once("classes/db/ConnectionManagerFactory.php");
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
     return $dbms->getConnectionHandle();
   }
}    
?>