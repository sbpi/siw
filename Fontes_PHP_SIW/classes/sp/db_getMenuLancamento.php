<?php
extract($GLOBALS);
include_once($w_dir_volta . "classes/db/DatabaseQueriesFactory.php");

/**
 * class db_getMenuLancamento
 *
 * { Description :- 
 *    Recupera os serviços a que a forma e pagamento está vinculada
 * }
 */
class db_getMenuLancamento {

  function getInstanceOf($dbms, $tipo_lancamento, $p_menu) {
    extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');
    $sql = $strschema . 'SP_getMenuLancamento';
    $params=array("p_tipo_lancamento"   =>array($tipo_lancamento,    B_INTEGER,   32),
                  "p_menu"             =>array($p_menu,            B_INTEGER,   32),
                  "p_result"           =>array(null,               B_CURSOR,    -1)
                 );
    $lql = new DatabaseQueriesFactory;
    $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
    $l_error_reporting = error_reporting();
    error_reporting(0);
    if (!$l_rs->executeQuery()) {
      error_reporting($l_error_reporting);
      TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);
    } else {
      error_reporting($l_error_reporting);
      if ($l_rs = $l_rs->getResultData()) {
        return $l_rs;
      } else {
        return array();
      }
    }
  }

}

?>
