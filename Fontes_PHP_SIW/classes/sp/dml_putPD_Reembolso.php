<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Reembolso
*
* { Description :- 
*    Grava os dados do reembolso de viagem ao proposto.
* }
*/

class dml_putPD_Reembolso {
   function getInstanceOf($dbms, $p_cliente, $p_chave, $p_reembolso, $p_deposito, $p_valor, $p_observacao,  
        $p_financeiro, $p_rubrica, $p_lancamento) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPD_Reembolso';
     $params=array('p_cliente'              =>array($p_cliente,                           B_INTEGER,        32),
                   'p_chave'                =>array(tvl($p_chave),                        B_INTEGER,        32),
                   'p_reembolso'            =>array(tvl($p_reembolso),                    B_VARCHAR,         1),
                   'p_deposito'             =>array(tvl($p_deposito),                     B_VARCHAR,        20),
                   'p_valor'                =>array(toNumber(tvl($p_valor)),              B_NUMERIC,        18,2),
                   'p_observacao'           =>array(tvl($p_observacao),                   B_VARCHAR,      2000),
                   'p_financeiro'           =>array(tvl($p_financeiro),                   B_INTEGER,        32),
                   'p_rubrica'              =>array(tvl($p_rubrica),                      B_INTEGER,        32),
                   'p_lancamento'           =>array(tvl($p_lancamento),                   B_INTEGER,        32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
