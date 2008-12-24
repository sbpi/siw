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
        $p_minimo_pesquisas,$p_minimo_participantes,$p_minimo_propostas_validas,$p_certame,
        $p_enquadramento_inicial,$p_enquadramento_final,$p_contrato,$p_ativo,$p_padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putLCModalidade';
     $params=array('p_operacao'                  =>array($operacao,                                    B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                B_INTEGER,        32),
                   'p_cliente'                   =>array(tvl($p_cliente),                              B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                 B_VARCHAR,        60),
                   'p_sigla'                     =>array(tvl($p_sigla),                                B_VARCHAR,         3),
                   'p_descricao'                 =>array(tvl($p_descricao),                            B_VARCHAR,      2000),
                   'p_fundamentacao'             =>array(tvl($p_fundamentacao),                        B_VARCHAR,      2000),
                   'p_minimo_pesquisas'          =>array(tvl($p_minimo_pesquisas),                     B_INTEGER,         32),
                   'p_minimo_participantes'      =>array(tvl($p_minimo_participantes),                 B_INTEGER,         32),
                   'p_minimo_propostas_validas'  =>array(tvl($p_minimo_propostas_validas),             B_INTEGER,         32),
                   'p_certame'                   =>array(tvl($p_certame),                              B_VARCHAR,         1),  
                   'p_enquadramento_inicial'     =>array(toNumber(tvl($p_enquadramento_inicial)),      B_NUMERIC,      18,2),  
                   'p_enquadramento_final'       =>array(toNumber(tvl($p_enquadramento_final)),        B_NUMERIC,      18,2),
                   'p_contrato'                  =>array($p_contrato,                                  B_VARCHAR,         1),
                   'p_ativo'                     =>array($p_ativo,                                     B_VARCHAR,         1),
                   'p_padrao'                    =>array($p_padrao,                                    B_VARCHAR,         1)
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
