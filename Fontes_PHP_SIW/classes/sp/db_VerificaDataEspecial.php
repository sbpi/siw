<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_verificaDataEspecial
*
* { Description :- 
*    Verifica se há expediente na data informada
* }
*/

class db_verificaDataEspecial {
   function getInstanceOf($dbms, $p_data, $p_cliente, $p_pais, $p_uf, $p_cidade, $p_expediente) {
     $sql=$strschema.'VERIFICADATAESPECIAL';
     $params=array('p_expediente'                =>array(null,                                             B_VARCHAR,         1),
                   'p_data'                      =>array($p_data,                                          B_DATE,           32),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_pais'                      =>array(tvl($p_pais),                                     B_INTEGER,        32),
                   'p_uf'                        =>array(tvl($p_uf),                                       B_VARCHAR,         2),
                   'p_cidade'                    =>array(tvl($p_cidade),                                   B_INTEGER,        32),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
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
