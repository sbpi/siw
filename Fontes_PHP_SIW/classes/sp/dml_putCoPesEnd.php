<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_PutCoPesEnd
*
* { Description :- 
*    Mant�m os endere�os da pessoa
* }
*/

class dml_PutCoPesEnd {
   function getInstanceOf($dbms, $operacao, $chave, $p_pessoa, $p_tipo_endereco, $p_logradouro, $p_complemento,
         $p_cidade, $p_bairro, $p_cep, $p_padrao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCoPesEnd';
     $params=array('operacao'           =>array($operacao,          B_VARCHAR,      1),
                   'chave'              =>array($chave,             B_NUMERIC,     32),
                   'p_pessoa'           =>array($p_pessoa,          B_NUMERIC,     32),
                   'p_logradouro'       =>array($p_logradouro,      B_VARCHAR,     60),
                   'p_complemento'      =>array($p_complemento,     B_VARCHAR,     20),
                   'p_tipo_endereco'    =>array($p_tipo_endereco,   B_VARCHAR,     15),
                   'p_cidade'           =>array($p_cidade,          B_NUMERIC,     32),
                   'p_cep'              =>array($p_cep,             B_VARCHAR,      9),
                   'p_bairro'           =>array($p_bairro,          B_VARCHAR,     30),
                   'p_padrao'           =>array($p_padrao,          B_VARCHAR,      1)
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
