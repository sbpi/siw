<? 
// =========================================================================
// Rotina de valida��o dos dados do lan�amento financeiro
// -------------------------------------------------------------------------
function ValidaLancamento($p_cliente,$l_chave,$p_sg1,$p_sg2,$p_sg3,$p_sg4,$p_tramite) {
  extract($GLOBALS);
  // Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem encaminhar a solicita��o
  // 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  // 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  //     encaminhe o lan�amento
  //-----------------------------------------------------------------------------------
  // Cria recordsets e vari�veis de trabalho.
  // l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  // de dados espec�ficos da solicita��o que est� sendo validada.
  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // comp�em a solicita��o
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicita��o
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$l_chave,$p_sg1);
  //-----------------------------------------------------------------------------
  // Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------
  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (count($l_rs_solic)<=0) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  } 
  // Verifica se o cliente tem o m�dulo financeiro contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$p_cliente,null,'FN');
  if (count($l_rs_modulo)<=0) $l_financeiro='S'; else $l_financeiro='N';
  $l_erro='';
  $l_tipo='';
  // Recupera o tr�mite atual da solicita��o
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  //-----------------------------------------------------------------------------
  // Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  // um encaminhamento independente da fase e em alguns casos quando a fase for
  // diferente de conclus�o.
  // 1 - Verifica se o valor do lan�amento � maior que zero
  // 2 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
  //-----------------------------------------------------------------------------
  // 1 - Verifica se o valor do lan�amento � maior que zero
  if (f($l_rs_solic,'valor')==0) {
    $l_erro=$l_erro.'<li>O lan�amento n�o pode ter valor zero.';
    $l_tipo=0;
  } 
  // 2 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
  $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'LISTA');
  if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
  if (((f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc')) && count($l_rs1)!=0) && Nvl(f($l_rs_tramite,'ordem'),'---')<='2') {
    $l_erro=$l_erro.'<li>O valor do lan�amento (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor'),0),2,',','.').'</b>) difere da soma dos valores dos documentos (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor_doc'),0),2,',','.').'</b>).';
    $l_tipo=0;
  } 

  if (count($l_rs_tramite)>0) {
    // Recupera os dados da pessoa
    $l_rs1 = db_getBenef::getInstanceOf($dbms,$p_cliente,Nvl(f($l_rs_solic,'pessoa'),0),null,null,null,null,null,null);
    if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
    foreach ($l_rs1 as $row){$l_rs1 = $row; break;}
    if ($l_existe_rs1==0) {
      // Verifica se foi indicada a pessoa
      $l_erro=$l_erro.'<li>A pessoa n�o foi informada';
      $l_tipo=0;
    } else {
      if (!(Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==Nvl(f($l_rs1,'sq_tipo_pessoa'),0))) {
        // Verifica se a pessoa informada � do tipo indicada no cadastro do lan�amento 
        $l_erro=$l_erro.'<li>A pessoa n�o � do tipo informado na tela de dados gerais.';
        $l_tipo=0;
      } 
    } 

    // Verifica os dados banc�rios
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
      $l_erro=$l_erro.'<li>Dados banc�rios incompletos. Acesse a opera��o "Pessoa", confira os dados e grave a tela.';
      $l_tipo=0;
    }


  // Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao
  // cadastramento inicial
    if (Nvl(f($l_rs_tramite,'ordem'),0)>2) {
      $l_erro=$l_erro;
      if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE') {
        // 4 - Recupera os documentos associados ao lan�amento
        $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'LISTA');
        if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
        if ($l_existe_rs1==0) {
          // 5 - Verifica se foi informado pelo menos um documento
          $l_erro=$l_erro.'<li>N�o foram informados documentos para o lan�amento. Informe pelo menos um.';
          $l_tipo=0;
        } else {
          // 6 - Verifica se o valor do lan�amento � igual � soma dos valores dos documentos
          if (f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc')) {
            $l_erro=$l_erro.'<li>O valor do lan�amento (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor'),0),2,',','.').'</b>) difere da soma dos valores dos documentos (<b>R$ '.number_format(Nvl(f($l_rs_solic,'valor_doc'),0),2,',','.').'</b>).';
            $l_tipo=1;
          } 
        }
      } 
    } 
  } 
  $l_erro=$l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------
  return $l_erro;
} 
?>