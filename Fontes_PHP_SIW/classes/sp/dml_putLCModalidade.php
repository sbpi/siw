<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLCModalidade
*
* { Description :- 
*    Mantém a tabela de modalidade do módulo contrato
* }
*/

class dml_putLCModalidade {
   function getInstanceOf($dbms,$operacao,$p_chave, $p_cliente, $p_nome, $p_sigla, $p_descricao, $p_fundamentacao,
        $p_ativo,$p_padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTLCMODALIDADE';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                                  B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_sigla'                     =>array(tvl($p_sigla),                                    B_VARCHAR,         3),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_fundamentacao'             =>array(tvl($p_fundamentacao),                            B_VARCHAR,      2000),
                   'p_ativo'                     =>array($p_ativo,                                         B_VARCHAR,         1),
                   'p_padrao'                    =>array($p_padrao,                                        B_VARCHAR,         1)
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
