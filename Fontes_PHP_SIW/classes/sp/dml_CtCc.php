<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_CtCc
*
* { Description :- 
*    Manipula registros de CT_CC
* }
*/

class dml_CtCc {
   function getInstanceOf($dbms, $operacao, $chave, $sq_cc_pai, $cliente, $nome, $descricao, $sigla, $receita, $regular, $ativo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCtCc';
     $params=array('p_operacao' =>array($operacao,       B_VARCHAR,      1),
                   'chave'      =>array(tvl($chave),     B_INTEGER,     32),
                   'sq_cc_pai'  =>array(tvl($sq_cc_pai), B_INTEGER,     32),
                   'cliente'    =>array(tvl($cliente),   B_INTEGER,     32),
                   'nome'       =>array(tvl($nome),      B_VARCHAR,     60),
                   'descricao'  =>array(tvl($descricao), B_VARCHAR,    500),
                   'sigla'      =>array(tvl($sigla),     B_VARCHAR,     20),
                   'receita'    =>array(tvl($receita),   B_VARCHAR,      1),
                   'regular'    =>array(tvl($regular),   B_VARCHAR,      1),
                   'ativo'      =>array(tvl($ativo),     B_VARCHAR,      1)
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
