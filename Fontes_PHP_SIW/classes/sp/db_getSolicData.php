<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getSolicData
*
* { Description :- 
*    Recupera os dados de uma solicitacao
* }
*/

class db_getSolicData {
   function getInstanceOf($dbms, $p_chave, $p_restricao, $p_restricao, $p_tipo, $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante, $p_unidade, $p_prioridade, $p_ativo, $p_proponente, $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_acao_ppa, $p_orprior) {
     $sql=$strschema.'SP_GETSOLICDATA';
     $params=array('p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_restricao'                 =>array($p_restricao,                                     B_VARCHAR,        20),
                   'p_result'                    =>array(null,                                             B_CURSOR,         -1)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); error_reporting(0); if(!$l_rs->executeQuery()) { error_reporting($l_error_reporting); TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); }
     else {
       error_reporting($l_error_reporting); 
        if ($l_rs = $l_rs->getResultArray()) {
          return $l_rs;
        } else {
          return array();
        }
     }
   }
}
?>
