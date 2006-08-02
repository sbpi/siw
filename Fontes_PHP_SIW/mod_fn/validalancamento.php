<? 
// =========================================================================
// Rotina de validação dos dados do lançamento financeiro
// -------------------------------------------------------------------------
function ValidaLancamento($p_cliente,$l_chave,$p_sg1,$p_sg2,$p_sg3,$p_sg4,$p_tramite) {
  extract($GLOBALS);
  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem encaminhar a solicitação
  // 1 - Erro de regra de negócio. Apenas gestores podem encaminhar a solicitação
  // 2 - Alerta. O sistema indica uma situação não desejável mas permite que o usuário
  //     encaminhe o lançamento
  //-----------------------------------------------------------------------------------
  // Cria recordsets e variáveis de trabalho.
  // l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  // de dados específicos da solicitação que está sendo validada.
  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // compõem a solicitação
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicitação
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$l_chave,$p_sg1);
  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------
  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)<=0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 
  // Verifica se o cliente tem o módulo financeiro contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$p_cliente,null,'FN');
  if (count($l_rs_modulo)<=0) $l_financeiro='S'; else $l_financeiro='N';
  $l_erro='';
  $l_tipo='';
  // Recupera o trâmite atual da solicitação
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento independente da fase e em alguns casos quando a fase for
  // diferente de conclusão.
  // 1 - Verifica se o valor do lançamento é maior que zero
  // 2 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
  //-----------------------------------------------------------------------------
  // 1 - Verifica se o valor do lançamento é maior que zero
  if (f($l_rs_solic,'valor')==0) {
    $l_erro=$l_erro.'<li>O lançamento não pode ter valor zero.';
    $l_tipo=0;
  } 
  // 2 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
  $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'LISTA');
  if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
  if (((f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc')) && count($l_rs1)!=0) && Nvl(f($l_rs_tramite,'ordem'),'---')<='2') {
    $l_erro=$l_erro.'<li>O valor do lançamento (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor'),0),2,',','.').'</b>) difere da soma dos valores dos documentos (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor_doc'),0),2,',','.').'</b>).';
    $l_tipo=0;
  } 

  if (count($l_rs_tramite)>0) {
    // Recupera os dados da pessoa
    $l_rs1 = db_getBenef::getInstanceOf($dbms,$p_cliente,Nvl(f($l_rs_solic,'pessoa'),0),null,null,null,null,null,null);
    if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
    foreach ($l_rs1 as $row){$l_rs1 = $row; break;}
    if ($l_existe_rs1==0) {
      // Verifica se foi indicada a pessoa
      $l_erro=$l_erro.'<li>A pessoa não foi informada';
      $l_tipo=0;
    } else {
      if (!(Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==Nvl(f($l_rs1,'sq_tipo_pessoa'),0))) {
        // Verifica se a pessoa informada é do tipo indicada no cadastro do lançamento 
        $l_erro=$l_erro.'<li>A pessoa não é do tipo informado na tela de dados gerais.';
        $l_tipo=0;
      } 
    } 

    // Verifica os dados bancários
    $l_erro_banco = 0;
    if (substr(f($l_rs_solic,'sigla'),0,3)=='FND') {
      if (!(strpos('CREDITO,DEPOSITO',f($l_rs_solic,'sg_forma_pagamento'))===false)) {
        if (nvl(f($l_rs_solic,'sq_agencia'),'')=='' || nvl(f($l_rs_solic,'numero_conta'),'')=='') $l_erro_banco = 1;
      } elseif (f($l_rs_solic,'sg_forma_pagamento')=='ORDEM') {
        if (nvl(f($l_rs_solic,'sq_agencia'),'')=='') $l_erro_banco = 1;
      } elseif (f($l_rs_solic,'sg_forma_pagamento')=='EXTERIOR') {
        if (nvl(f($l_rs_solic,'banco_estrang'),'')=='' || 
            nvl(f($l_rs_solic,'agencia_estrang'),'')=='' ||
            nvl(f($l_rs_solic,'numero_conta'),'')=='' ||
            nvl(f($l_rs_solic,'cidade_estrang'),'')=='' ||
            nvl(f($l_rs_solic,'sq_pais_estrang'),'')==''
           ) $l_erro_banco = 1;
     }
    } 
    if ($l_erro_banco==1) {
      $l_erro=$l_erro.'<li>Dados bancários incompletos. Acesse a operação "Pessoa", confira os dados e grave a tela.';
      $l_tipo=0;
    }


  // Este bloco faz verificações em solicitações que estão em fases posteriores ao
  // cadastramento inicial
    if (Nvl(f($l_rs_tramite,'ordem'),0)>2) {
      $l_erro=$l_erro;
      if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE') {
        // 4 - Recupera os documentos associados ao lançamento
        $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'LISTA');
        if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
        if ($l_existe_rs1==0) {
          // 5 - Verifica se foi informado pelo menos um documento
          $l_erro=$l_erro.'<li>Não foram informados documentos para o lançamento. Informe pelo menos um.';
          $l_tipo=0;
        } else {
          // 6 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
          if (f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc')) {
            $l_erro=$l_erro.'<li>O valor do lançamento (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor'),0),2,',','.').'</b>) difere da soma dos valores dos documentos (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor_doc'),0),2,',','.').'</b>).';
            $l_tipo=1;
          } 
        }
      } 
    } 
  } 
  $l_erro=$l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  return $l_erro;
} 
?>