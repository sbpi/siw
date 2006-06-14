<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicAcesso
*
* { Description :- 
*    Recupera o nível de acesso que um usuário tem para uma solicitação
* }
*/

class db_getSolicAcesso {
   function getInstanceOf($dbms, $p_solicitacao, $p_usuario, $p_acesso, $p_cliente, $p_usuario:endsub, $p_cliente, $p_sq_pessoa, $p_cpf, $p_cnpj:endsub) {
     $sql=$strschema.'ACESSO';
     $params=array('p_acesso'                    =>array(null,                                             B_INTEGER,        32),
                   'p_solicitacao'               =>array($p_solicitacao,                                   B_INTEGER,        32),
                   'p_usuario'                   =>array($p_usuario,                                       B_INTEGER,        32),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultData()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}
?>
