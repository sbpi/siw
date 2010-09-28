<?php
// =========================================================================
// Rotina de validação dos dados do afastamento
// -------------------------------------------------------------------------
function ValidaAfastamento($l_cliente,$l_chave,$l_sq_contrato_colaborador,$l_dt_ini,$l_dt_fim,$l_periodo_ini,$l_periodo_fim,$l_dias) {
  extract($GLOBALS);
  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade.
  // 1 - Erro de regra de negócio.
  //-----------------------------------------------------------------------------------
  // Cria recordsets e variáveis de trabalho.
  // l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  // de dados específicos do afastamento que está sendo validado.
  //-----------------------------------------------------------------------------------
  // $l_rs_afast is of type 'ADODB.RecordSet'
  // $l_rs1 is of type 'ADODB.RecordSet'
  // $l_rs2 is of type 'ADODB.RecordSet'
  // $l_rs3 is of type 'ADODB.RecordSet'
  // $l_rs4 is of type 'ADODB.RecordSet'
  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega o afastamento
  //-----------------------------------------------------------------------------------
  $l_erro = '';

  // Verifica se há afastamento cadastrado no periodo informado
  $l_rs_afast = db_getAfastamento::getInstanceOf($dbms,$l_cliente,null,null,null,$l_sq_contrato_colaborador,$l_dt_ini,$l_dt_fim,null,null,$l_chave,null);
  if ((count($l_rs_afast)>0)) {
    foreach ($l_rs_afast as $row) {
      $l_erro = $l_erro.'<li>No período informado, existe <b>'.f($row,'nm_tipo_afastamento').' ('.FormataDataEdicao(f($row,'inicio_data')).'-'.f($row,'inicio_periodo').' a '.FormataDataEdicao(f($row,'fim_data')).'-'.f($row,'fim_periodo').')</b>.</li>';
    } 
  } 
  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  return $l_erro;
} 
?>


