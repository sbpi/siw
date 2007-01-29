<?
// =========================================================================
// Rotina de valida��o dos dados do colaborador
// -------------------------------------------------------------------------
function ValidaColaborador($p_cliente,$p_sq_pessoa,$p_sq_contrato_colaborador,$p_encerramento) {
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
  // $l_rs_afast is of type "ADODB.RecordSet"
  // $l_rs_ferias is of type "ADODB.RecordSet"
  // $l_rs_viagem is of type "ADODB.RecordSet"
  // $l_rs1 is of type "ADODB.RecordSet"
  // $l_rs2 is of type "ADODB.RecordSet"
  // $l_rs3 is of type "ADODB.RecordSet"
  // $l_rs4 is of type "ADODB.RecordSet"
  $l_erro = '';
  $l_cont = 0;
  //-----------------------------------------------------------------------------------
  // Esta primeira parte verifica o afastamento
  //-----------------------------------------------------------------------------------
  // Verifica se h� afastamento cadastrado para este colaborador
  $l_rs_afast = db_getAfastamento::getInstanceOf($dbms,$p_cliente,null,null,null,$p_sq_contrato_colaborador,$p_encerramento,$p_encerramento,null,null,null,null);
  if ((count($l_rs_afast)>0)) {
    foreach ($l_rs_afast as $row) {
      $l_cont+=1;
    } 
  } 
  //-----------------------------------------------------------------------------------
  // Esta segunda parte verifica as viagens
  //-----------------------------------------------------------------------------------
  // Verifica se h� viagens cadastradas para este colaborador
  $l_rs_viagem = db_getViagemBenef::getInstanceOf($dbms,null,$p_cliente,$p_sq_pessoa,null,null,null,$p_encerramento,$p_encerramento,null);
  if (!($l_rs_viagem==0)) {
    foreach ($l_rs_viagem as $row) {
      if (Nvl(f($row,'sq_viagem'),'')>'') {
        $l_cont+=1;
      } 
    } 
  } 
  //----------------------------------------------------------------------------------
  // Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  if ($l_cont>0) {
    if (Nvl($p_encerramento,'')>'') {
      $l_erro = $l_erro.'<li>Colaborador n�o pode ser encerrado por estar vinculado a afastamentos, f�rias ou viagens.</li>';
    } else {
      $l_erro = $l_erro.'<li>Colaborador n�o pode ser exclu�do por estar vinculado a afastamentos, f�rias ou viagens.</li>';
    } 
  } 
  return $l_erro;
}
?>