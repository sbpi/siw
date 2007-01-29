<?
// =========================================================================
// Rotina de validação dos dados do colaborador
// -------------------------------------------------------------------------
function ValidaColaborador($p_cliente,$p_sq_pessoa,$p_sq_contrato_colaborador,$p_encerramento) {
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
  // Verifica se há afastamento cadastrado para este colaborador
  $l_rs_afast = db_getAfastamento::getInstanceOf($dbms,$p_cliente,null,null,null,$p_sq_contrato_colaborador,$p_encerramento,$p_encerramento,null,null,null,null);
  if ((count($l_rs_afast)>0)) {
    foreach ($l_rs_afast as $row) {
      $l_cont+=1;
    } 
  } 
  //-----------------------------------------------------------------------------------
  // Esta segunda parte verifica as viagens
  //-----------------------------------------------------------------------------------
  // Verifica se há viagens cadastradas para este colaborador
  $l_rs_viagem = db_getViagemBenef::getInstanceOf($dbms,null,$p_cliente,$p_sq_pessoa,null,null,null,$p_encerramento,$p_encerramento,null);
  if (!($l_rs_viagem==0)) {
    foreach ($l_rs_viagem as $row) {
      if (Nvl(f($row,'sq_viagem'),'')>'') {
        $l_cont+=1;
      } 
    } 
  } 
  //----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  if ($l_cont>0) {
    if (Nvl($p_encerramento,'')>'') {
      $l_erro = $l_erro.'<li>Colaborador não pode ser encerrado por estar vinculado a afastamentos, férias ou viagens.</li>';
    } else {
      $l_erro = $l_erro.'<li>Colaborador não pode ser excluído por estar vinculado a afastamentos, férias ou viagens.</li>';
    } 
  } 
  return $l_erro;
}
?>