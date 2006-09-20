<?
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putEsquema
*
* { Description :- 
*    Mantem a tabela de esquemas para importacao
* }
*/

class dml_putEsquema {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_sq_esquema, $p_sq_modulo, $p_nome, $p_descricao, $p_tipo, $p_ativo, $p_formato, $p_ws_servidor, $p_ws_url, $p_ws_acao, $p_ws_mensagem, $p_no_raiz, $p_bd_hostname, $p_bd_username, $p_bd_password, $p_tx_delimitador) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'SP_PUTESQUEMA';
     $params=array('p_operacao'                  =>array(tvl($operacao),                                   B_VARCHAR,        10),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_sq_esquema'                =>array(tvl($p_sq_esquema),                               B_INTEGER,        32),
                   'p_sq_modulo'                 =>array(tvl($p_sq_modulo),                                B_INTEGER,        32),
                   'p_nome'                      =>array(tvl($p_nome),                                     B_VARCHAR,        60),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,       500),
                   'p_tipo'                      =>array(tvl($p_tipo),                                     B_VARCHAR,         1),
                   'p_ativo'                     =>array(tvl($p_ativo),                                    B_VARCHAR,         1),
                   'p_formato'                   =>array(tvl($p_formato),                                  B_VARCHAR,         1),
                   'p_ws_servidor'               =>array(tvl($p_ws_servidor),                              B_VARCHAR,       100),
                   'p_ws_url'                    =>array(tvl($p_ws_url),                                   B_VARCHAR,       100),
                   'p_ws_acao'                   =>array(tvl($p_ws_acao),                                  B_VARCHAR,       100),
                   'p_ws_mensagem'               =>array(tvl($p_ws_mensagem),                              B_VARCHAR,      4000),
                   'p_no_raiz'                   =>array(tvl($p_no_raiz),                                  B_VARCHAR,        50),
                   'p_bd_hostname'               =>array(tvl($p_bd_hostname),                              B_VARCHAR,        50),
                   'p_bd_username'               =>array(tvl($p_bd_username),                              B_VARCHAR,        50),
                   'p_bd_password'               =>array(tvl($p_bd_password),                              B_VARCHAR,        50),
                   'p_tx_delimitador'            =>array(tvl($p_tx_delimitador),                           B_VARCHAR,         5)
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