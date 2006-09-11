<?php
include_once('DatabaseQueries.php');
include_once('DBTypes.php');


/**
* class DatabaseQueriesFactory
*
* { Description :- 
*	This class is a factory returning an object of specified Database to execute queries/procs.
* }
*/

class DatabaseQueriesFactory {
   function getInstanceOf($query, $conHandle, $params) {
      switch($_SESSION['DBMS']) {
         case MSSQL : {
		    if (empty($params)) { return new MSSqlDatabaseQueries($query, $conHandle); }
			else { return new MSSqlDatabaseQueryProc($query, $conHandle, $params); }
			break;
		 }
		 case ORA8  : {
		    if (empty($params)) { return new OraDatabaseQueries($query, $conHandle); }
			else { return new OraDatabaseQueryProc($query, $conHandle, $params); }
			break;
		 }
		 case ORA9  : {
		    if (empty($params)) { return new OraDatabaseQueries($query, $conHandle); }
			else { return new OraDatabaseQueryProc($query, $conHandle, $params); }
			break;
		 }
		 case ORA10  : {
		    if (empty($params)) { return new OraDatabaseQueries($query, $conHandle); }
			else { return new OraDatabaseQueryProc($query, $conHandle, $params); }
			break;
		 }
		 case PGSQL : {
		    if (empty($params)) { return new PgSqlDatabaseQueries($query, $conHandle); }
			else { return new PgSqlDatabaseQueryProc($query, $conHandle, $params); }
		 }
      }
   }
}	
?>
