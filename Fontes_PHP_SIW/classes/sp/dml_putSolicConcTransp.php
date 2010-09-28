<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSolicConcTransp
*
* { Description :- 
*    Conclui a solicitação
* }
*/

class dml_putSolicConcTransp {
   function getInstanceOf($dbms, $p_menu, $p_chave, $p_pessoa, $p_recebedor, $p_tramite, $p_executor, $p_observacao, $p_valor, $p_caminho, $p_tamanho, $p_tipo, $p_nome_original,
                          $p_sq_veiculo, $p_hodometro_saida, $p_hodometro_chegada, $p_horario_saida, $p_horario_chegada, $p_parcial) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTSOLICCONCTRANSP';
     $params=array('p_menu'                      =>array($p_menu,                                          B_INTEGER,        32),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_pessoa'                    =>array($p_pessoa,                                        B_INTEGER,        32),
                   'p_recebedor'                 =>array($p_recebedor,                                     B_INTEGER,        32),
                   'p_tramite'                   =>array($p_tramite,                                       B_INTEGER,        32),
                   'p_executor'                  =>array($p_executor,                                      B_INTEGER,        32),
                   'p_observacao'                =>array(tvl($p_observacao),                               B_VARCHAR,      2000),
                   'p_valor'                     =>array(toNumber(tvl($p_valor)),                          B_NUMERIC,      18,2),
                   'p_caminho'                   =>array(tvl($p_caminho),                                  B_VARCHAR,       255),
                   'p_tamanho'                   =>array(tvl($p_tamanho),                                  B_INTEGER,        32),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,       100),
                   'p_nome_original'             =>array(tvl($p_nome_original),                            B_VARCHAR,       255),
                   'p_sq_veiculo'                =>array(tvl($p_sq_veiculo),                               B_INTEGER,        7),
                   'p_hodometro_saida'           =>array(tvl($p_hodometro_saida),                          B_INTEGER,        7),
                   'p_hodometro_chegada'         =>array(tvl($p_hodometro_chegada),                        B_INTEGER,        32),
                   'p_horario_saida'             =>array(tvl($p_horario_saida),                            B_VARCHAR,        17),
                   'p_horario_chegada'           =>array(tvl($p_horario_chegada),                          B_VARCHAR,        17),
                   'p_parcial'                   =>array(tvl($p_parcial),                                  B_VARCHAR,         1)
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
