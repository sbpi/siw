<?php
// =========================================================================
// Rotina de valida��o dos dados do afastamento
// -------------------------------------------------------------------------
function ValidaAfastamento($l_cliente,$l_chave,$l_sq_contrato_colaborador,$l_dt_ini,$l_dt_fim,$l_periodo_ini,$l_periodo_fim,$l_dias) {
  extract($GLOBALS);
  // Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade.
  // 1 - Erro de regra de neg�cio.
  //-----------------------------------------------------------------------------------
  // Cria recordsets e vari�veis de trabalho.
  // l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  // de dados espec�ficos do afastamento que est� sendo validado.
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

  // Verifica se h� afastamento cadastrado no periodo informado
  $l_rs_afast = db_getAfastamento::getInstanceOf($dbms,$l_cliente,null,null,null,$l_sq_contrato_colaborador,$l_dt_ini,$l_dt_fim,null,null,$l_chave,null);
  if ((count($l_rs_afast)>0)) {
    foreach ($l_rs_afast as $row) {
      $l_erro = $l_erro.'<li>No per�odo informado, existe <b>'.f($row,'nm_tipo_afastamento').' ('.FormataDataEdicao(f($row,'inicio_data')).'-'.f($row,'inicio_periodo').' a '.FormataDataEdicao(f($row,'fim_data')).'-'.f($row,'fim_periodo').')</b>.</li>';
    } 
  } 
  //-----------------------------------------------------------------------------------
  // Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  return $l_erro;
} 
?>


