<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSF
*
* { Description :- 
*    Recupera a lista de clientes do SIW
* }
*/

class db_getSF {
   function getInstanceOf($dbms, $p_restricao, $p_ctcc, $p_pessoa, $p_cpf, $p_cnpj, $p_nome, $p_documento, $p_inicio, $p_fim, $p_comprovante, $p_inicio_nf, $p_fim_nf) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'SF_Consulta';
     $params=array('p_restricao'                 =>array(tvl($p_restricao),                                B_VARCHAR,        20),
                   'p_ctcc'                      =>array(tvl($p_ctcc),                                     B_INTEGER,        32),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_cpf'                       =>array($p_cpf,                                           B_VARCHAR,        14),
                   'p_cnpj'                      =>array(tvl($p_cnpj),                                     B_VARCHAR,        18),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        40),
                   'p_documento'                 =>array(tvl($p_documento),                                B_VARCHAR,        15),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_comprovante'               =>array(tvl($p_comprovante),                              B_VARCHAR,        10),
                   'p_inicio_nf'                 =>array(tvl($p_inicio_nf),                                B_DATE,           32),
                   'p_fim_nf'                    =>array(tvl($p_fim_nf),                                   B_DATE,           32),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
