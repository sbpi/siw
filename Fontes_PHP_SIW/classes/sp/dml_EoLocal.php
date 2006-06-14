<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_eOLocal
*
* { Description :- 
*    Manipula registros de EO_Localizacao
* }
*/

class dml_eOLocal {
   function getInstanceOf($dbms, $operacao, $chave, $sq_pessoa_endereco, $sq_unidade, $nome, $fax, $telefone, $ramal, $telefone2, $ativo) {
     $sql=$strschema.'SP_PUTEOLOCAL';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array(tvl($chave),                                      B_INTEGER,        32),
                   'p_sq_pessoa_endereco'        =>array(tvl($sq_pessoa_endereco),                         B_INTEGER,        32),
                   'p_sq_unidade'                =>array($sq_unidade,                                      B_INTEGER,        32),
                   'p_nome'                      =>array($nome,                                            B_VARCHAR,        30),
                   'p_fax'                       =>array($fax,                                             B_VARCHAR,        12),
                   'p_telefone'                  =>array($telefone,                                        B_VARCHAR,        12),
                   'p_ramal'                     =>array($ramal,                                           B_VARCHAR,         6),
                   'p_telefone2'                 =>array($telefone2,                                       B_VARCHAR,        12),
                   'p_ativo'                     =>array($ativo,                                           B_VARCHAR,         1)
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
