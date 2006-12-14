<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getConvOutraParte
*
* { Description :- 
*    Recupera os dados de uma solicitacao
* }
*/

class db_getConvOutraParte {
   function getInstanceOf($dbms, $p_chave, $p_chave_aux, $p_outra_parte, $p_tipo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_GETCONVOUTRAPARTE';
     $params=array('p_chave'                     =>array($l_chave,                                         B_INTEGER,        32),
                   'p_chave_aux'                 =>array($l_chave_aux,                                     B_INTEGER,        32),
                   'p_outra_parte'               =>array($l_outra_parte,                                   B_INTEGER,        32),
                   'p_tipo'                      =>array($l_tipo,                                          B_INTEGER,         1),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultArray()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}
?>
