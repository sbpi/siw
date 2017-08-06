<?php
include_once("ConnectionManager.php");
include_once("DBTypes.php");

/**
* class DatabaseQueriesFactory
*
* { Description :-
*  This class is a factory returning an connection Manager object for the specified database(MySQL/MSSQL).
* }
*/

class ConnectionManagerFactory {
   function getInstanceOf($DBType="",$DB_CHARSET="") {
      switch($DBType) {
         case MSSQL : return new MSSqlConnectionManager($DB_CHARSET);    break;
         case ORA8  : return new Ora8ConnectionManager($DB_CHARSET);     break;
         case ORA9  : return new Ora9ConnectionManager($DB_CHARSET);     break;
         case ORA10 : return new Ora10ConnectionManager($DB_CHARSET);    break;
         case ORAHM : return new OraHMConnectionManager($DB_CHARSET);    break;
         case PGSQL : return new PgSqlConnectionManager($DB_CHARSET);    break;
      }
   }
}
?>
