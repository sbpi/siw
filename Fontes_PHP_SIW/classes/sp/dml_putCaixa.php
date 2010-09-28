<?php
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putEtapaArquivo
*
* { Description :- 
*    Mantém a tabela de arquivos de etapa
* }
*/	                  
class dml_putCaixa {
   function getInstanceOf($dbms, $operacao, $p_cliente, $p_chave,$p_sq_unidade ,$p_sq_arquivo_local,$p_assunto,$p_descricao ,
           $p_data_limite,$p_numero,$p_intermediario,$p_destinacao_final,$p_arquivo_data,$p_arquivo_guia_numero,$p_arquivo_guia_ano,
           $p_elimin_data,$p_elimin_guia_numero,$p_elimin_guia_ano, $p_chave_nova) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putCaixa';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_cliente'                   =>array($p_cliente,                                       B_INTEGER,        32),
                   'p_chave'                     =>array(tvl($p_chave),                                    B_INTEGER,        32),
                   'p_sq_unidade'                =>array(tvl($p_sq_unidade),                               B_INTEGER,        32),
                   'p_sq_arquivo_local'          =>array(tvl($p_sq_arquivo_local),                         B_INTEGER,        32),
      		    	   'p_assunto'                   =>array(tvl($p_assunto),                                  B_VARCHAR,       500),
                   'p_descricao'                 =>array(tvl($p_descricao),                                B_VARCHAR,      2000),
                   'p_data_limite'               =>array(tvl($p_data_limite),                              B_DATE,           32),
                   'p_numero'                    =>array(tvl($p_numero),                                   B_INTEGER,        32),
			        	   'p_intermediario'             =>array(tvl($p_intermediario),                            B_VARCHAR,        40),
        				   'p_destinacao_final'          =>array(tvl($p_destinacao_final),                         B_VARCHAR,        40),
                   'p_arquivo_data'              =>array(tvl($p_arquivo_data),                             B_DATE,			     32),
                   'p_arquivo_guia_numero'       =>array(tvl($p_arquivo_guia_numero),                      B_INTEGER,        32),
		   	      	   'p_arquivo_guia_ano'          =>array(tvl($p_arquivo_guia_ano),                         B_INTEGER,        32),
                   'p_elimin_data'               =>array(tvl($p_elimin_data),                              B_DATE,			     32),
                   'p_elimin_guia_numero'        =>array(tvl($p_elimin_guia_numero),                       B_INTEGER,        32),
                   'p_elimin_guia_ano'           =>array(tvl($p_elimin_guia_ano),                          B_INTEGER,        32),   
                   'p_chave_nova'                =>array(&$p_chave_nova,                                   B_INTEGER,        32)                
                  );
     $l_rs = new DatabaseQueriesFactory; $l_rs = $l_rs->getInstanceOf($sql, $dbms, $params, DB_TYPE);
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
