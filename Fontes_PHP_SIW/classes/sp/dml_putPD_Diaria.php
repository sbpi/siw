<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putPD_Diaria
*
* { Description :- 
*    Grava os dados das diárias
* }
*/

class dml_putPD_Diaria {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_sq_diaria, $p_sq_cidade, $p_diaria, $p_quantidade, $p_valor,
            $p_hospedagem, $p_hospedagem_qtd, $p_hospedagem_valor, $p_veiculo, $p_veiculo_qtd, $p_veiculo_valor, 
            $p_deslocamento_chegada, $p_deslocamento_saida, $p_sq_valor_diaria, $p_sq_diaria_hospedagem, 
            $p_sq_diaria_veiculo, $p_justificativa_diaria, $p_justificativa_veiculo, 
            $p_rub_dia, $p_lan_dia, $p_fin_dia, $p_rub_hsp, $p_lan_hsp, $p_fin_hsp, $p_rub_vei, $p_lan_vei, $p_fin_vei,
            $p_hos_in, $p_hos_out, $p_hos_observ, $p_vei_ret, $p_vei_dev, $p_tipo, $p_origem, $p_texto_diaria,
            $p_texto_hospedagem, $p_texto_veiculo) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_putPDDiaria';
     $params=array('p_operacao'               =>array($operacao,                            B_VARCHAR,         1),
                   'p_chave'                  =>array($p_chave,                             B_INTEGER,        32),
                   'p_sq_diaria'              =>array(tvl($p_sq_diaria),                    B_INTEGER,        32),
                   'p_sq_cidade'              =>array(tvl($p_sq_cidade),                    B_INTEGER,        32),
                   'p_diaria'                 =>array(tvl($p_diaria),                       B_VARCHAR,         1),
                   'p_quantidade'             =>array(toNumber(tvl($p_quantidade)),         B_NUMERIC,       5,1),
                   'p_valor'                  =>array(toNumber(tvl($p_valor)),              B_NUMERIC,      18,2),
                   'p_hospedagem'             =>array(nvl($p_hospedagem,'N'),               B_VARCHAR,         1),
                   'p_hospedagem_qtd'         =>array(toNumber(tvl($p_hospedagem_qtd)),     B_NUMERIC,       5,1),
                   'p_hospedagem_valor'       =>array(toNumber(tvl($p_hospedagem_valor)),   B_NUMERIC,      18,2),
                   'p_veiculo'                =>array(nvl($p_veiculo,'N'),                  B_VARCHAR,         1),
                   'p_veiculo_qtd'            =>array(toNumber(tvl($p_veiculo_qtd)),        B_NUMERIC,       5,1),
                   'p_veiculo_valor'          =>array(toNumber(tvl($p_veiculo_valor)),      B_NUMERIC,      18,2),
                   'p_deslocamento_chegada'   =>array(tvl($p_deslocamento_chegada),         B_INTEGER,        32),
                   'p_deslocamento_saida'     =>array(tvl($p_deslocamento_saida),           B_INTEGER,        32),
                   'p_sq_valor_diaria'        =>array(tvl($p_sq_valor_diaria),              B_INTEGER,        32),
                   'p_sq_valor_diaria'        =>array(tvl($p_sq_valor_diaria),              B_INTEGER,        32),
                   'p_sq_diaria_hospedagem'   =>array(tvl($p_sq_diaria_hospedagem),         B_INTEGER,        32),
                   'p_sq_diaria_veiculo'      =>array(tvl($p_sq_diaria_veiculo),            B_INTEGER,        32),
                   'p_justificativa_diaria'   =>array(tvl($p_justificativa_diaria),         B_VARCHAR,       500),
                   'p_justificativa_veiculo'  =>array(tvl($p_justificativa_veiculo),        B_VARCHAR,       500),
                   'p_rub_dia'                =>array(tvl($p_rub_dia),                      B_INTEGER,        32),
                   'p_lan_dia'                =>array(tvl($p_lan_dia),                      B_INTEGER,        32),
                   'p_fin_dia'                =>array(tvl($p_fin_dia),                      B_INTEGER,        32),
                   'p_rub_hsp'                =>array(tvl($p_rub_hsp),                      B_INTEGER,        32),
                   'p_lan_hsp'                =>array(tvl($p_lan_hsp),                      B_INTEGER,        32),
                   'p_fin_hsp'                =>array(tvl($p_fin_hsp),                      B_INTEGER,        32),
                   'p_rub_vei'                =>array(tvl($p_rub_vei),                      B_INTEGER,        32),
                   'p_lan_vei'                =>array(tvl($p_lan_vei),                      B_INTEGER,        32),
                   'p_fin_vei'                =>array(tvl($p_fin_vei),                      B_INTEGER,        32),
                   'p_hos_in'                 =>array(tvl($p_hos_in),                       B_DATE,           32),
                   'p_hos_out'                =>array(tvl($p_hos_out),                      B_DATE,           32),
                   'p_hos_observ'             =>array(tvl($p_hos_observ),                   B_VARCHAR,       500),
                   'p_vei_ret'                =>array(tvl($p_vei_ret),                      B_DATE,           32),
                   'p_vei_dev'                =>array(tvl($p_vei_dev),                      B_DATE,           32),
                   'p_tipo'                   =>array(nvl($p_tipo,'S'),                     B_VARCHAR,         1),
                   'p_origem'                 =>array(nvl($p_origem,'S'),                   B_VARCHAR,        10),
                   'p_texto_diaria'           =>array(tvl($p_texto_diaria),                 B_VARCHAR,       500),
                   'p_texto_hospedagem'       =>array(tvl($p_texto_hospedagem),             B_VARCHAR,       500),
                   'p_texto_veiculo'          =>array(tvl($p_texto_veiculo),                B_VARCHAR,       500)
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
