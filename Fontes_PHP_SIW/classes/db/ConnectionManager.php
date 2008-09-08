<?php
include_once('db_constants.php');

/**
* class ConnectionManager
*
* { Description :-
*  Class to establish connection and select database with a database server. This is the Base Class
* }
*/

class ConnectionManager {
   var $hostName;
   var $userName;
   var $passWord;
   var $conHandle;
   var $conVersion=DATABASE_VERSION;

   /**
   * Method ConnectionManager::getConnectionHandle()
   *
   * { Description :-
   *  This method returns the connection handle.
   * }
   */

   function getConnectionHandle() { return $this->conHandle; }

   function getVersion() { return $this->conVersion; }

}

/**
* class ConnectionManager
*
* { Description :-
*  Class to establish connection and select database with a database server. This is the sub class of ConnectionManager class
* }
*/

class PgSqlConnectionManager extends ConnectionManager {
   function PgSqlConnectionManager() {
      $this->hostName = PGSQL_SERVER_NAME;
      $this->userName = PGSQL_DB_USERID;
      $this->passWord = PGSQL_DB_PASSWORD;
   }

   function doConnection() {
      if(!($this->conHandle = Pg_Connect("host=$this->hostName user=$this->userName password=$this->passWord dbname=".PGSQL_DATABASE_NAME))) {
         die("Cannot Connect to Host");
      }
   }

   function selectDatabase() { null;}
}

class MSSqlConnectionManager extends ConnectionManager {
   function MSSqlConnectionManager() {
      $this->hostName = MSSQL_SERVER_NAME;
      $this->userName = MSSQL_DB_USERID;
      $this->passWord = MSSQL_DB_PASSWORD;
   }

   function doConnection() {
      if(!($this->conHandle = mssql_connect($this->hostName, $this->userName, $this->passWord))){
         die("Cannot Connect to Host");
      }
   }

   function selectDatabase() { 
       mssql_select_db(DATABASE_NAME, $this->conHandle);
   }
}

class Ora8ConnectionManager extends ConnectionManager {
   function Ora8ConnectionManager() {
      $this->hostName = ORA8_SERVER_NAME;
      $this->userName = ORA8_DB_USERID;
      $this->passWord = ORA8_DB_PASSWORD;
   }

   function doConnection() {
      if(!($this->conHandle = oci_new_connect($this->userName, $this->passWord, $this->hostName))){
         die("Cannot Connect to Host");
      }
   }

   function selectDatabase() { null;}
}

class Ora9ConnectionManager extends ConnectionManager {
   function Ora9ConnectionManager() {
      $this->hostName = ORA9_SERVER_NAME;
      $this->userName = ORA9_DB_USERID;
      $this->passWord = ORA9_DB_PASSWORD;
   }

   function doConnection() {
      $l_error_reporting = error_reporting(); //error_reporting(0);
//echo 'LD_LIBRARY_PATH ==>'.getenv("LD_LIBRARY_PATH");
//echo '<br>ORACLE_HOME ==>'.getenv("ORACLE_HOME");
//echo '<BR>ORACLE_BASE ==>'.getenv("ORACLE_BASE");
//echo '<BR>ORACLE_SID ==>'.getenv("ORACLE_SID");
//echo '<BR>NLS_LANG==>'.getenv("NLS_LANG");
      if(!($this->conHandle = oci_new_connect($this->userName, $this->passWord, $this->hostName,'WE8MSWIN1252'))) { 
         error_reporting($l_error_reporting); TrataErro($sql, oci_error(), $params, __FILE__, __LINE__, __CLASS__); 
      } else {
        error_reporting($l_error_reporting); 
      }
   }

   function selectDatabase() { null;}
}

class Ora10ConnectionManager extends ConnectionManager {
   function Ora10ConnectionManager() {
      $this->hostName = ORA10_SERVER_NAME;
      $this->userName = ORA10_DB_USERID;
      $this->passWord = ORA10_DB_PASSWORD;
   }

   function doConnection() {
      $l_error_reporting = error_reporting(); error_reporting(0);
      if(!($this->conHandle = oci_new_connect($this->userName, $this->passWord, $this->hostName))) { error_reporting($l_error_reporting); TrataErro($sql, oci_error(), $params, __FILE__, __LINE__, __CLASS__); }
      else {
        error_reporting($l_error_reporting); 
      }
   }

   function selectDatabase() { null;}
}
?>