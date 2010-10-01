<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putLCSituacao
*
* { Description :- 
*    Mantém a tabela de situações de um certame
* }
*/

class dml_putLCSituacao {
   function getInstanceOf($dbms,$operacao,$p_chave, $p_cliente, $p_nome, $p_descricao, $p_ativo, $p_padrao, $p_publicar) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putLCSituacao';
     $params=array('p_operacao'          =>array($operacao,                                B_VARCHAR,         1),
                   'p_chave'             =>array(tvl($p_chave),                            B_INTEGER,        32),
                   'p_cliente'           =>array(tvl($p_cliente),                          B_INTEGER,        32),
                   'p_nome'              =>array(tvl($p_nome),                             B_VARCHAR,        60),
                   'p_descricao'         =>array(tvl($p_descricao),                        B_VARCHAR,      2000),
                   'p_ativo'             =>array($p_ativo,                                 B_VARCHAR,         1),
                   'p_padrao'            =>array($p_padrao,                                B_VARCHAR,         1),
                   'p_publicar'          =>array($p_publicar,                              B_VARCHAR,         1)
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
