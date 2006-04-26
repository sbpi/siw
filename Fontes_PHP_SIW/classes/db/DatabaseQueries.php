<?php

/**
* class DatabaseQueries
*
* { Description :- 
*    This class is the base class for executing database queries
* }
*/

class DatabaseQueries
{
    var $conHandle;
    var $query;
    var $result;
    var $resultData;
    var $stmt;
    var $num_rows;
    
    /**
    * Method DatabaseQueries::getResultSet()
    *
    * { Description :- 
    *    This class is the base class for executing database queries.
    * }
    */
    
    function getResultSet() { return $this->result; }

    /**
    * Method DatabaseQueries::getResultData()
    *
    * { Description :- 
    *    Returns all data from a result set.
    * }
    */
    
    function getResultData() { return $this->resultData; }

    /**
    * Method DatabaseQueries::getNumRows()
    *
    * { Description :- 
    *    This class returns the number of rows found in a query or 
    *   returned by a stored procedure
    * }
    */
    
    function getNumRows() { return $this->num_rows; }
}

/**
* class MSSqlDatabaseQueries
*
* { Description :- 
*    This class is the sub class for executing MSSql database queries.
* }
*/

class MSSqlDatabaseQueries extends DatabaseQueries {    
    
    /**
    * Method MSSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function MSSqlDatabaseQueries($query, $conHandle) {
        $this->query = $query;
        $this->conHandle = $conHandle;
    }
    
    /**
    * Method MSSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function executeQuery() {
        if(!($this->result = mssql_query($this->query, $this->conHandle))) { return false; }
        else { 
           if(is_resource($this->result)) { $this-> num_rows = mssql_num_rows($this->result); }
           else { $this-> num_rows =  -1; }
           return true;     
        }
    }
    
    /**
    * Method MSSqlDatabaseQueries::getResultArray()
    *
    * { Description :- 
    *    This method returns the one row from the resultset
    * }
    */
    
    function getResultArray() {
        if(is_resource($this->result)) { return mssql_fetch_array($this->result); }
        else { return null; }
    }

    function getResultData() {
        if(is_resource($this->result)) { return $this->resultData = mssql_fetch_all($this->result); }
        else { return null; }
    }
}

/**
* class MSSqlDatabaseQueryProc
*
* { Description :- 
*    This class is the sub of MSSqlDatabaseQuery class for executing MSSql database Procedures.
*    $proc -- Procedure Name.
*    $conHandle -- Connection Handle.
*    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
*                                        @edited is input paramter,
*                                        $edited is the value of Input Parameter @edited,
*                                        SQLCHAR is a the MSSQL Constant for CHAR column type,
*                                        false indicates @edited is not an output parameter.        
*                
* }                            
*/

class MSSqlDatabaseQueryProc extends MSSqlDatabaseQueries {
    var $params;
    var $paramName;
    var $paramValue;
    var $paramType;    
    var $paramLength;

    /* Method MSSqlDatabaseQueryProc(). Constructor.
    *
    * { Description :- 
    *    This class is the sub of MSSqlDatabaseQuery class for executing MSSql database Procedures.
    *    $proc -- Procedure Name.
    *    $conHandle -- Connection Handle.
    *    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
    *                                        @edited is input paramter,
    *                                        $edited is the value of Input Parameter @edited,
    *                                        SQLCHAR is a the MSSQL Constant for CHAR column type,
    *                                        false indicates @edited is not an output parameter.        
    *                
    * }                            
    */    
    
    function MSSqlDatabaseQueryProc($proc, $conHandle, $params) {
        $this->query = $proc;
        $this->conHandle = $conHandle;
        $this->params = $params;
    }
    
    /**
    * Method MSSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */    
    
    function executeQuery() {
        $this->stmt = mssql_init("$this->query",$this->conHandle);
        
        foreach($this->params as $paramName=>$value) {
            foreach($value as $paramValue=>$paramType) {
                if (!($value[1]==B_CURSOR)) { 
                   if ($value[1]==B_VARCHAR) {
                      mssql_bind($this->stmt, "@$paramName", $value[0], $value[1], false, false, $value[2]); 
                   }
                   else mssql_bind($this->stmt, "@$paramName", $value[0], $value[1], false, false); 
                }
                 break;
            }
        }
        
        if(!($this->result = mssql_execute($this->stmt))) { return false; }
        else {
           if(is_resource($this->result)) { $this->num_rows = mssql_num_rows($this->result); }
           else { $this->num_rows = -1; }
           return true;
        }
    }
    
    function getResultData() {
        if(is_resource($this->result)) { return $this->resultData = mssql_fetch_all($this->result); }
        else { return null; }
    }
} 

/**
* class OraDatabaseQueries
*
* { Description :- 
*    This class is the sub class for executing Oracle database queries.
* }
*/

class OraDatabaseQueries extends DatabaseQueries {    
    
    /**
    * Method OraDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function OraDatabaseQueries($query, $conHandle) {
        $this->query = $query;
        $this->conHandle = $conHandle;
    }
    
    /**
    * Method OraDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function executeQuery() {
        if(!($this->result = oci_parse($this->conHandle, $this->query))) { return false; }
        else { 
           if(is_resource($this->result)) { 
              oci_execute($this->result);
              $this->num_rows = oci_fetch_all($this->result, $this->resultData, 0, -1,OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW);
              oci_execute($this->result);
           }
           else { $this->num_rows = -1; }

           return true; 
        }
    }
    
    /**
    * Method OraDatabaseQueries::getResultArray()
    *
    * { Description :- 
    *    This method returns the one row from the resultset
    * }
    */
    
    function getResultArray() {
        if(is_resource($this->result)) { return oci_fetch_array($this->result, OCI_BOTH+OCI_RETURN_NULLS); }
        else { return null; }
    }

}

/**
* class OraDatabaseQueryProc
*
* { Description :- 
*    This class is the sub of OraDatabaseQuery class for executing Oracle database Procedures.
*    $proc -- Procedure Name.
*    $conHandle -- Connection Handle.
*    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
*                                        @edited is input paramter,
*                                        $edited is the value of Input Parameter @edited,
*                                        SQLCHAR is a the Oracle Constant for CHAR column type,
*                                        false indicates @edited is not an output parameter.        
*                
* }                            
*/

class OraDatabaseQueryProc extends OraDatabaseQueries {
    var $params;
    var $paramName;
    var $paramValue;
    var $paramType;    
    var $paramLength;
    
    /* Method OraDatabaseQueryProc(). Constructor.
    *
    * { Description :- 
    *    This class is the sub of OraDatabaseQuery class for executing Oracle database Procedures.
    *    $proc -- Procedure Name.
    *    $conHandle -- Connection Handle.
    *    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
    *                                        @edited is input paramter,
    *                                        $edited is the value of Input Parameter @edited,
    *                                        SQLCHAR is a the Oracle Constant for CHAR column type,
    *                                        false indicates @edited is not an output parameter.        
    *                
    * }                            
    */    
    
    function OraDatabaseQueryProc($proc, $conHandle, $params) {
        $this->query = $proc;
        $this->conHandle = $conHandle;
        $this->params = $params;
    }
    
    /**
    * Method OraDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */    
    
    function executeQuery() {
        $par = "";
        $cursor = false;
        foreach($this->params as $paramName=>$value) {
            foreach($value as $paramValue=>$paramType) { 
              $par .= ", :$paramName"; 
              if ($paramName == 'p_result') $cursor = true;
              break; 
            }
        }
        $par = substr($par, 1);

        if ($cursor) {
           $this->result = oci_new_cursor($this->conHandle);
           $this->stmt = oci_parse($this->conHandle, "begin $this->query ($par); end;");

           foreach($this->params as $paramName=>$value) {
               foreach($value as $paramValue=>$paramType) {
                   if($value[1]!=B_CURSOR)
                      oci_bind_by_name($this->stmt, $paramName, $value[0], $value[2]); 
                   else {
                      oci_bind_by_name($this->stmt, $paramName, &$this->result, $value[2], OCI_B_CURSOR);
                   }
                   break;
               }
           }

           if(!(oci_execute($this->stmt))) { return false; }
           else {
              oci_execute($this->result);
              if(is_resource($this->result)) { 
                 $this->num_rows = oci_fetch_all($this->result, $this->resultData, 0, -1,OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW);
              }
              else { $this->num_rows = -1; }
              oci_execute($this->stmt);
              oci_execute($this->result);
           }
        } else {
           $this->result = oci_parse($this->conHandle, "begin $this->query ($par); end;");

           foreach($this->params as $paramName=>$value) {
               foreach($value as $paramValue=>$paramType) { 
                  oci_bind_by_name($this->result, $paramName, $value[0], $value[2]); 
                  break;
               }
           }

           if(is_resource($this->result)) oci_execute($this->result); else $this->num_rows = -1; 
        }
        
        return true;
    }

} 

class PgSqlDatabaseQueries extends DatabaseQueries {    
    
    /**
    * Method PgSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function PgSqlDatabaseQueries($query, $conHandle) {
        $this->query = $query;
        $this->conHandle = $conHandle;
    }
    
    /**
    * Method PgSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */
    
    function executeQuery() {
        if(!($this->result = pg_query($this->conHandle, $this->query))) { return false; }
        else { 
           if(is_resource($this->result)) { $this->num_rows = pg_num_rows($this->result); }
           else { $this->num_rows = -1; }
           return true;     
        }
    }
    
    /**
    * Method PgSqlDatabaseQueries::getResultArray()
    *
    * { Description :- 
    *    This method returns the one row from the resultset
    * }
    */
    
    function getResultArray() {
        if(is_resource($this->result)) { return pg_fetch_array($this->result); }
        else { return null; }
    }


    function getResultData() {
        if(is_resource($this->result)) { $this->resultData = pg_fetch_all($this->result); }
        else { return null; }
    }
}

/**
* class PgSqlDatabaseQueryProc
*
* { Description :- 
*    This class is the sub of PgSqlDatabaseQuery class for executing PgSql database Procedures.
*    $proc -- Procedure Name.
*    $conHandle -- Connection Handle.
*    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
*                                        @edited is input paramter,
*                                        $edited is the value of Input Parameter @edited,
*                                        SQLCHAR is a the PgSql Constant for CHAR column type,
*                                        false indicates @edited is not an output parameter.        
*                
* }                            
*/

class PgSqlDatabaseQueryProc extends PgSqlDatabaseQueries {
    var $params;
    var $paramName;
    var $paramValue;
    var $paramType;    
    var $paramLength;

    /* Method PgSqlDatabaseQueryProc(). Constructor.
    *
    * { Description :- 
    *    This class is the sub of PgSqlDatabaseQuery class for executing PgSql database Procedures.
    *    $proc -- Procedure Name.
    *    $conHandle -- Connection Handle.
    *    $params -- Associative array  eg. array("@edited"=>array($edited=>SQLCHAR, false));
    *                                        @edited is input paramter,
    *                                        $edited is the value of Input Parameter @edited,
    *                                        SQLCHAR is a the PgSql Constant for CHAR column type,
    *                                        false indicates @edited is not an output parameter.        
    *                
    * }                            
    */    
    
    
    function PgSqlDatabaseQueryProc($proc, $conHandle, $params) {
        $this->query = $proc;
        $this->conHandle = $conHandle;
        $this->params = $params;
    }
    
    /**
    * Method PgSqlDatabaseQueries::executeQuery()
    *
    * { Description :- 
    *    This method executes the the query.
    * }
    */    
    
    function executeQuery() {
        $par = "";
        $cursor = false;

        foreach($this->params as $paramName=>$value) {
            foreach($value as $paramValue=>$paramType) {
                if (!($value[1]==B_CURSOR)) { 
                   if (!isset($value[0]) || $value[0]=='') { $par .= ", null"; }
                   elseif ($value[1]==B_VARCHAR) { $par .= ", '$value[0]'"; }
                   else { $par .= ", $value[0]"; }
                } else {
                  $cursor = true;
                }
                 break;
            }
        }
        if ($cursor) {
          if ($par=="") {
                $par = "rollback; begin; select $this->query ('p_result'); fetch all in p_result;";
          } else {
                $par = "rollback; begin; select $this->query (".substr($par, 1).", 'p_result'); fetch all in p_result;";
          }
        } else {
          if ($par=="") {
                $par = "rollback; begin; select $this->query; commit;";
          } else {
                $par = "rollback; begin; select $this->query (".substr($par, 1).'); commit;';
          }
        }
        // echo $par;

        $this->result = pg_query($this->conHandle, $par);
        if(is_resource($this->result)) { 
           $this->num_rows = pg_num_rows($this->result); 
           return true;
        }
        else { 
           $this->num_rows = -1; 
           return false;
        }
    }

    function getResultData() {

        if(is_resource($this->result)) { return $this->resultData = pg_fetch_all($this->result); }
        else { return null; }
    }
} 
?>