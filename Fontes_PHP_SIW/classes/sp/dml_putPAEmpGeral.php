<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPAEmpGeral
*
* { Description :- 
*    Mantém a tabela principal de pedido de empréstimo de protocolos e caixas de arquivamento
* }
*/

class dml_putPAEmpGeral {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_copia, $p_menu, $p_unidade, $p_solicitante,$p_cadastrador,  
        $p_justificativa, $p_fim, $p_cidade) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); 
     $sql=$strschema.'sp_putPAEmpGeral';
     $params=array('p_operacao'             =>array($operacao,                    B_VARCHAR,         1),
                   'p_chave'                =>array(tvl($p_chave),                B_INTEGER,        32),
                   'p_copia'                =>array(tvl($p_copia),                B_INTEGER,        32),
                   'p_menu'                 =>array($p_menu,                      B_INTEGER,        32),
                   'p_unidade'              =>array(tvl($p_unidade),              B_INTEGER,        32),
                   'p_solicitante'          =>array(tvl($p_solicitante),          B_INTEGER,        32),
                   'p_cadastrador'          =>array(tvl($p_cadastrador),          B_INTEGER,        32),
                   'p_justificativa'        =>array(tvl($p_justificativa),        B_VARCHAR,      2000),
                   'p_fim'                  =>array(tvl($p_fim),                  B_DATE,           32),
                   'p_cidade'               =>array(tvl($p_cidade),               B_INTEGER,        32)
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
