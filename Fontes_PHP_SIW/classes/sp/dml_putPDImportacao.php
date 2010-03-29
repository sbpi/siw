<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPDImportacao
*
* { Description :- 
*    Grava dados da importa��o de faturas eletr�nicas da ag�ncia de viagens
* }
*/

class dml_putPDImportacao {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_cliente, $p_sq_pessoa, $p_tipo, $p_data_arquivo, 
          $p_arquivo_recebido, $p_caminho_recebido, $p_tamanho_recebido, $p_tipo_recebido, 
          $p_arquivo_registro, $p_caminho_registro, $p_tamanho_registro, $p_tipo_registro, 
          $p_registros, $p_importados, $p_rejeitados, $p_nome_recebido, $p_nome_registro, 
          $p_chave_nova) {

     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPDImportacao';
     $params=array('p_operacao'          => array($operacao,                      B_VARCHAR,         1),
                   'p_chave'             => array(tvl($p_chave),                  B_INTEGER,        32),
                   'p_cliente'           => array(tvl($p_cliente),                B_INTEGER,        32),
                   'p_sq_pessoa'         => array(tvl($p_sq_pessoa),              B_INTEGER,        32),
                   'p_tipo'              => array(tvl($p_tipo),                   B_INTEGER,         1),
                   'p_data_arquivo'      => array(tvl($p_data_arquivo),           B_VARCHAR,        17),
                   'p_arquivo_recebido'  => array(tvl($p_arquivo_recebido),       B_VARCHAR,       255),
                   'p_caminho_recebido'  => array(tvl($p_caminho_recebido),       B_VARCHAR,       255),
                   'p_tamanho_recebido'  => array(tvl($p_tamanho_recebido),       B_INTEGER,        32),
                   'p_tipo_recebido'     => array(tvl($p_tipo_recebido),          B_VARCHAR,        60),
                   'p_arquivo_registro'  => array(tvl($p_arquivo_registro),       B_VARCHAR,       255),
                   'p_caminho_registro'  => array(tvl($p_caminho_registro),       B_VARCHAR,       255),
                   'p_tamanho_registro'  => array(tvl($p_tamanho_registro),       B_INTEGER,        32),
                   'p_tipo_registro'     => array(tvl($p_tipo_registro),          B_VARCHAR,        60),
                   'p_registros'         => array(tvl($p_registros),              B_INTEGER,        32),
                   'p_importados'        => array(tvl($p_importados),             B_INTEGER,        32),
                   'p_rejeitados'        => array(tvl($p_rejeitados),             B_INTEGER,        32),
                   'p_nome_recebido'     => array(tvl($p_nome_recebido),          B_VARCHAR,       255),
                   'p_nome_registro'     => array(tvl($p_nome_registro),          B_VARCHAR,       255),
                   'p_chave_nova'        =>array(&$p_chave_nova,                  B_INTEGER,        32)
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
