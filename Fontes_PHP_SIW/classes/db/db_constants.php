<?php
// MSSql 2000 SP 3 Database Constants
define("MSSQL_SERVER_NAME", "NETUNO\NETUNO");
define("MSSQL_DB_USERID", "SIW");
define("MSSQL_DB_PASSWORD", "SIW");
define("MSSQL_DATABASE_NAME", "SIW_DB");
define("MSSQL_VERSION_TEXT", "MS SQL Server 2000 SP 3");

// Oracle8 Database Constants
define("ORA8_SERVER_NAME", "XE");
define("ORA8_DB_USERID", "SIW");
define("ORA8_DB_PASSWORD", "SIW");
define("ORA8_DATABASE_NAME", "SIW");
define("ORA8_VERSION_TEXT", "Oracle Server 8.1.7");

// Oracle9 Database Constants
define("ORA9_SERVER_NAME", "XE");
define("ORA9_DB_USERID", "SIW");
define("ORA9_DB_PASSWORD", "SIW");
define("ORA9_DATABASE_NAME", "SIW");
define("ORA9_VERSION_TEXT", "Oracle Server 10g");

// Oracle9 Database Constants
//define("ORA10_SERVER_NAME", "mercurio");
define("ORA10_SERVER_NAME", "XE");
define("ORA10_DB_USERID", "ABDI_VENUS");
define("ORA10_DB_PASSWORD", "ABDI_VENUS");
define("ORA10_DATABASE_NAME", "ABDI_VENUS");
define("ORA10_VERSION_TEXT", "Oracle Server 10g");

// OracleHM Database Constants
define("ORAHM_SERVER_NAME", "XE");
define("ORAHM_DB_USERID", "INTEGRA");
define("ORAHM_DB_PASSWORD", "INTEGRA");
define("ORAHM_DATABASE_NAME", "INTEGRA");
define("ORAHM_VERSION_TEXT", "Oracle Server 10g");

// PGSQL 8.0 Database Constants
define("PGSQL_SERVER_NAME", "127.0.0.1");
define("PGSQL_DB_USERID", "siw");
define("PGSQL_DB_PASSWORD", "siw");
define("PGSQL_DATABASE_NAME", "siw_db");
define("PGSQL_VERSION_TEXT", "PostgreSQL 8.3.1");

switch ($_SESSION["DBMS"]) {
   case 6 : {
      define("DATABASE_NAME", ORAHM_DATABASE_NAME);
      define("DATABASE_VERSION", ORAHM_VERSION_TEXT);
      define("B_VARCHAR", 2);
      define("B_NUMERIC", 1);
      define("B_CURSOR", OCI_B_CURSOR);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "||");
      break;
   }
   case 5 : {
      define("DATABASE_NAME", ORA10_DATABASE_NAME);
      define("DATABASE_VERSION", ORA10_VERSION_TEXT);
      define("B_VARCHAR", 2);
      define("B_NUMERIC", 1);
      define("B_CURSOR", OCI_B_CURSOR);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "||");
      break;
   }
   case 4 : {
      define("DATABASE_NAME", PGSQL_DATABASE_NAME);
      define("DATABASE_VERSION", PGSQL_VERSION_TEXT);
      define("B_VARCHAR", 2);
      define("B_NUMERIC", 1);
      define("B_CURSOR", -1);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "||");
      break;
   }
   case 3 : {
      define("DATABASE_NAME", ORA8_DATABASE_NAME);
      define("DATABASE_VERSION", ORA8_VERSION_TEXT);
      define("B_VARCHAR", 2);
      define("B_NUMERIC", 1);
      define("B_CURSOR", OCI_B_CURSOR);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "||");
      break;
   }
   case 2 : {
      define("DATABASE_NAME", MSSQL_DATABASE_NAME);
      define("DATABASE_VERSION", MSSQL_VERSION_TEXT);
      define("B_VARCHAR", SQLVARCHAR);
      define("B_DATE", SQLINT8);
      define("B_NUMERIC", SQLFLT8);
      define("B_INTEGER", SQLINT4);
      define("B_CURSOR", -1);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "+");
      break;
   }
   case 1 : {
      define("DATABASE_NAME", ORA9_DATABASE_NAME);
      define("DATABASE_VERSION", ORA9_VERSION_TEXT);
      define("B_VARCHAR", 2);
      define("B_NUMERIC", 1);
      define("B_CURSOR", OCI_B_CURSOR);
      define("B_REQUIRED", true);
      define("B_OPTIONAL", false);
      define("C", "||");
      break;
   }
}
?>
