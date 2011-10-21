<?php

extract($GLOBALS);
include_once($w_dir_volta . "classes/db/DatabaseQueriesFactory.php");

/**
 * class db_getModList
 *
 * { Description :- 
 *    Recupera a lista de módulos
 * }
 */
class db_getModList {

  function getInstanceOf($dbms) {
    extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');
    $sql = $strschema . 'sp_getModList';
    $params = array("p_result" => array(null, B_CURSOR, -1));
    $sql = new db_exec;
    $par = $sql->normalize($params);
    extract($par, EXTR_OVERWRITE);
    
    $SQL = "select sq_modulo, nome, objetivo_geral, sigla, ordem from siw_modulo;";
    
    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
  }

}

?>
