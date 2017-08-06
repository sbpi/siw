<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getMTBem
*
* { Description :- 
*    Recupera os bens patrimoniais
* }
*/

class db_getMTBem {
   function getInstanceOf($dbms, $p_cliente, $p_usuario, $p_chave, $p_sqcc, 
        $p_projeto, $p_financeiro, $p_tipo, $p_material, $p_rgp, $p_descricao,
        $p_marca, $p_modelo, $p_observacao, $p_ativo, $p_almoxarifado, $p_endereco,
        $p_unidade, $p_localizacao, $p_situacao, $p_inicio, $p_fim, $p_restricao) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_getMTBem';  
     $params=array('p_cliente'            =>array($p_cliente,                        B_INTEGER,        32),
                   'p_usuario'            =>array($p_usuario,                        B_INTEGER,        32),
                   'p_chave'              =>array($p_chave,                          B_INTEGER,        32),
                   'p_sqcc'               =>array(tvl($p_sqcc),                      B_INTEGER,        32),
                   'p_projeto'            =>array(tvl($p_projeto),                   B_INTEGER,        32),
                   'p_financeiro'         =>array(tvl($p_financeiro),                B_VARCHAR,        30),
                   'p_tipo'               =>array($p_tipo,                           B_INTEGER,        32),
                   'p_material'           =>array(tvl($p_material),                  B_VARCHAR,        30),
                   'p_rgp'                =>array(tvl($p_rgp),                       B_INTEGER,        32),
                   'p_descricao'          =>array(tvl($p_descricao),                 B_VARCHAR,        90),
                   'p_marca'              =>array(tvl($p_marca),                     B_VARCHAR,        90),
                   'p_modelo'             =>array(tvl($p_modelo),                    B_VARCHAR,        90),
                   'p_observacao'         =>array(tvl($p_observacao),                B_VARCHAR,        90),
                   'p_ativo'              =>array(tvl($p_ativo),                     B_VARCHAR,        10),
                   'p_almoxarifado'       =>array(tvl($p_almoxarifado),              B_INTEGER,        32),
                   'p_endereco'           =>array(tvl($p_endereco),                  B_INTEGER,        32),
                   'p_unidade'            =>array(tvl($p_unidade),                   B_INTEGER,        32),
                   'p_localizacao'        =>array(tvl($p_localizacao),               B_INTEGER,        32),
                   'p_situacao'           =>array(tvl($p_situacao),                  B_INTEGER,        32),
                   'p_inicio'             =>array(tvl($p_inicio),                    B_DATE,           32),
                   'p_fim'                =>array(tvl($p_fim),                       B_DATE,           32),
                   'p_restricao'          =>array($p_restricao,                      B_VARCHAR,        20),                                 
                   'p_result'             =>array(null,                              B_CURSOR,         -1)
                  );
     $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
