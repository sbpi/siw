<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_ReembValor
*
* { Description :- 
*    Grava valor de reembolso de viagem.
* }
*/

class dml_putPD_ReembValor {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_chave_aux, $p_moeda, $p_valor_solicitado, $p_justificativa, 
        $p_valor_autorizado, $p_observacao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_ReembValor';
     $params=array('p_operacao'                           =>array($operacao,                             B_VARCHAR,         1),
                   'p_chave'                              =>array(tvl($p_chave),                         B_INTEGER,        32),
                   'p_chave_aux'                          =>array(tvl($p_chave_aux),                     B_INTEGER,        32),
                   'p_moeda'                              =>array(tvl($p_moeda),                         B_INTEGER,        32),
                   'p_valor_solicitado'                   =>array(toNumber(tvl($p_valor_solicitado)),    B_NUMERIC,        18,2),
                   'p_justificativa'                      =>array(tvl($p_justificativa),                 B_VARCHAR,      1000),
                   'p_valor_autorizado'                   =>array(toNumber(tvl($p_valor_autorizado)),    B_NUMERIC,        18,2),
                   'p_observacao'                         =>array(tvl($p_observacao),                    B_VARCHAR,      1000)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
