<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putDocumentoGeral
*
* { Description :- 
*    Mantém a tabela principal de documentos
* }
*/

class dml_putDocumentoGeral {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_copia, $p_menu, $p_unidade, $p_unid_autua, $p_solicitante, 
        $p_cadastrador, $p_solic_pai, $p_vinculo, $p_processo, $p_circular, $p_especie_documento, $p_doc_original, 
        $p_inicio, $p_volumes, $p_dt_autuacao, $p_copias, $p_natureza_documento, $p_fim, $p_data_recebimento, 
        $p_interno, $p_pessoa_origem, $p_pessoa_interes, $p_cidade, $p_assunto, $p_descricao, $p_observacao,
        &$p_chave_nova, &$p_codigo_interno) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putDocumentoGeral';
     $params=array('p_operacao'                 =>array($operacao,                                  B_VARCHAR,         1),
                   'p_chave'                    =>array(tvl($p_chave),                              B_INTEGER,        32),
                   'p_copia'                    =>array(tvl($p_copia),                              B_INTEGER,        32),
                   'p_menu'                     =>array($p_menu,                                    B_INTEGER,        32),
                   'p_unidade'                  =>array(tvl($p_unidade),                            B_INTEGER,        32),
                   'p_unid_autua'               =>array(tvl($p_unid_autua),                         B_INTEGER,        32),
                   'p_solicitante'              =>array(tvl($p_solicitante),                        B_INTEGER,        32),
                   'p_cadastrador'              =>array(tvl($p_cadastrador),                        B_INTEGER,        32),
                   'p_solic_pai'                =>array(tvl($p_solic_pai),                          B_INTEGER,        32),
                   'p_vinculo'                  =>array(tvl($p_vinculo),                            B_INTEGER,        32),
                   'p_processo'                 =>array(tvl($p_processo),                           B_VARCHAR,         1),
                   'p_circular'                 =>array(tvl($p_circular),                           B_VARCHAR,         1),
                   'p_especie_documento'        =>array(tvl($p_especie_documento),                  B_INTEGER,        32),
                   'p_doc_original'             =>array(tvl($p_doc_original),                       B_VARCHAR,        30),
                   'p_inicio'                   =>array(tvl($p_inicio),                             B_DATE,           32),
                   'p_volumes'                  =>array(tvl($p_volumes),                            B_INTEGER,        32),
                   'p_dt_autuacao'              =>array(tvl($p_dt_autuacao),                        B_DATE,           32),
                   'p_copias'                   =>array(tvl($p_copias),                             B_INTEGER,        32),
                   'p_natureza_documento'       =>array(tvl($p_natureza_documento),                 B_INTEGER,        32),
                   'p_fim'                      =>array(tvl($p_fim),                                B_DATE,           32),
                   'p_data_recebimento'         =>array(tvl($p_data_recebimento),                   B_DATE,           32),
                   'p_interno'                  =>array(tvl($p_interno),                            B_VARCHAR,         1),
                   'p_pessoa_origem'            =>array(tvl($p_pessoa_origem),                      B_INTEGER,        32),
                   'p_pessoa_interes'           =>array(tvl($p_pessoa_interes),                     B_INTEGER,        32),
                   'p_cidade'                   =>array(tvl($p_cidade),                             B_INTEGER,        32),
                   'p_assunto'                  =>array(tvl($p_assunto),                            B_INTEGER,        32),
                   'p_descricao'                =>array(tvl($p_descricao),                          B_VARCHAR,      2000),
                   'p_observacao'               =>array(tvl($p_observacao),                         B_VARCHAR,      2000),
                   'p_chave_nova'               =>array(&$p_chave_nova,                             B_INTEGER,        32),
                   'p_codigo_interno'           =>array(&$p_codigo_interno,                         B_VARCHAR,        30)
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
