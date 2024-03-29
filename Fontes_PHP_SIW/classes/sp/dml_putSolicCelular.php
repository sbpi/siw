<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicCelular
*
* { Description :- 
*    Mant�m a tabela de solicita��es de celulares.
* }
*/

class dml_putSolicCelular {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_menu, $p_unidade, $p_solicitante, $p_cadastrador, 
        $p_descricao, $p_justificativa, $p_inicio, $p_fim, $p_data_hora, $p_cidade, $p_destino, &$p_chave_nova, $p_copia) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putsolicCelular';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_copia'                     =>array(tvl($p_copia),                                    B_INTEGER,        32),
                   'p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_unidade'                   =>array(tvl($p_unidade),                                  B_INTEGER,        32),
                   'p_solicitante'               =>array(tvl($p_solicitante),                              B_INTEGER,        32),
                   'p_cadastrador'               =>array(tvl($p_cadastrador),                              B_INTEGER,        32),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_justificativa'             =>array(tvl($p_justificativa),                            B_VARCHAR,      2000),
                   'p_inicio'                    =>array(tvl($p_inicio),                                   B_DATE,           32),
                   'p_fim'                       =>array(tvl($p_fim),                                      B_DATE,           32),
                   'p_data_hora'                 =>array(tvl($p_data_hora),                                B_VARCHAR,         1),
                   'p_cidade'                    =>array(tvl($p_cidade),                                   B_INTEGER,        32),
                   'p_destino'                   =>array(tvl($p_destino),                                  B_INTEGER,        32),
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
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
