<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putCVHist
*
* { Description :- 
*    Mantém os dados de identificacao do colaborador
* }
*/

class dml_putCVHist {
   function getInstanceOf($dbms, $operacao, $p_chave, $p_residencia_outro_pais, $p_mudanca_nacionalidade, 
        $p_mudanca_nacionalidade_medida, $p_emprego_seis_meses, $p_impedimento_viagem_aerea, $p_objecao_informacoes, 
        $p_prisao_envolv_justica, $p_motivo_prisao, $p_fato_relevante_vida, $p_servidor_publico, $p_servico_publico_inicio, 
        $p_servico_publico_fim, $p_atividades_civicas, $p_familiar) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $sql=$strschema.'SP_PUTCVHIST';
     $params=array('p_operacao'                  =>array($operacao,                                        B_VARCHAR,         1),
                   'p_chave'                     =>array($p_chave,                                         B_INTEGER,        32),
                   'p_residencia_outro_pais'     =>array($p_residencia_outro_pais,                         B_VARCHAR,         1),
                   'p_mudanca_nacionalidade'     =>array($p_mudanca_nacionalidade,                         B_VARCHAR,         1),
                   'p_mudanca_nacionalidade_medida'=>array(tvl($p_mudanca_nacionalidade_medida),           B_VARCHAR,       255),
                   'p_emprego_seis_meses'        =>array($p_emprego_seis_meses,                            B_VARCHAR,         1),
                   'p_impedimento_viagem_aerea'  =>array($p_impedimento_viagem_aerea,                      B_VARCHAR,         1),
                   'p_objecao_informacoes'       =>array($p_objecao_informacoes,                           B_VARCHAR,         1),
                   'p_prisao_envolv_justica'     =>array($p_prisao_envolv_justica,                         B_VARCHAR,         1),
                   'p_motivo_prisao'             =>array(tvl($p_motivo_prisao),                            B_VARCHAR,       255),
                   'p_fato_relevante_vida'       =>array(tvl($p_fato_relevante_vida),                      B_VARCHAR,       255),
                   'p_servidor_publico'          =>array($p_servidor_publico,                              B_VARCHAR,         1),
                   'p_servico_publico_inicio'    =>array(tvl($p_servico_publico_inicio),                   B_DATE,           32),
                   'p_servico_publico_fim'       =>array(tvl($p_servico_publico_fim),                      B_DATE,           32),
                   'p_atividades_civicas'        =>array(tvl($p_atividades_civicas),                       B_VARCHAR,       255),
                   'p_familiar'                  =>array($p_familiar,                                      B_VARCHAR,         1)
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
