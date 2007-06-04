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
  if (f($l_rs_solic,'ativo')=='S') {
    if (substr(f($l_rs_solic,'sigla'),0,3)=='FNR' && f($l_rs_solic,'receita')=='N') {
      $l_erro=$l_erro.'<li>Para lançamentos de receita, o tipo de lançamento deve ser de receita.';
      $l_tipo=0;
    } elseif (substr(f($l_rs_solic,'sigla'),0,3)=='FND' && f($l_rs_solic,'despesa')=='N') {
      $l_erro=$l_erro.'<li>Para lançamentos de despesa, o tipo de lançamento deve ser de despesa.';
      $l_tipo=0;
    }
  }
  // 2 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
  $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'DOCS');
  if (count($l_rs1)<=0) {
    $l_existe_rs1=0;
  } else { 
    $l_existe_rs1=count($l_rs1);    
    foreach($l_rs1 as $l_row) {
      if((nvl(f($l_rs_solic,'tipo_rubrica'),'')!='')&&(nvl(f($l_rs_solic,'tipo_rubrica'),0)<>5)) {
        $l_rs2 = db_getLancamentoRubrica::getInstanceOf($dbms,null,f($l_row,'sq_lancamento_doc'),null,null);
        if (count($l_rs2)<=0) $l_existe_rs2=0; else $l_existe_rs2=count($l_rs2);
        if($l_existe_rs2>0) {
          $l_valor_rubrica=0;
          foreach($l_rs2 as $l_row2) {
            $l_valor_rubrica += f($l_row2,'valor'); 
          }
          if (((f($l_row,'valor')!=$l_valor_rubrica) && count($l_rs2)!=0) && f($l_rs_tramite,'ativo')=='S') {
            $l_erro=$l_erro.'<li>'.f($l_row,'nm_tipo_documento').' - '.f($l_row,'numero').': Soma dos valores das rubricas(<b>R$ '.formatNumber(Nvl($l_valor_rubrica,0)).'</b>) difere do valor do documento(<b>R$ '.formatNumber(Nvl(f($l_row,'valor'),0)).'</b>).';
            $l_tipo=0;
          }          
        }
      } elseif (nvl(f($l_rs_solic,'tipo_rubrica'),'')!='') {
        $l_rs2 = db_getLancamentoItem::getInstanceOf($dbms,null,f($l_row,'sq_lancamento_doc'),null,null,null);
        if (count($l_rs2)<=0) $l_existe_rs2=0; else $l_existe_rs2=count($l_rs2);
        if (((f($l_row,'valor')!=f($l_row,'total_item')) && count($l_rs2)!=0) && f($l_rs_tramite,'ativo')=='S') {
          $l_erro=$l_erro.'<li>'.f($l_row,'nm_tipo_documento').' - '.f($l_row,'numero').': Soma dos valores dos itens(<b>R$ '.formatNumber(Nvl(f($l_row,'total_item'),0)).'</b>) difere do valor do documento(<b>R$ '.formatNumber(Nvl(f($l_row,'valor'),0)).'</b>).';
          $l_tipo=0;
        }
      }
    }
    if (f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc') && f($l_rs_tramite,'ativo')=='S') {
      $l_erro=$l_erro.'<li>O valor do lançamento (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor'),0)).'</b>) difere da soma dos valores dos documentos (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor_doc'),0)).'</b>).';
      $l_tipo=0;
    }
  }

  // 3 - Se o lançamento está ligado a acordo com nota, ele deve ter pelo menos uma nota e a parcela ao qual está ligado também.
  if (f($l_rs_solic,'notas_acordo')>0 && f($l_rs_solic,'qtd_nota')==0) {
    $l_erro=$l_erro.'<li>Este lançamento deve ter pelo menos uma nota.';
    $l_tipo=0;
  }

  if (f($l_rs_solic,'notas_acordo')>0 && f($l_rs_solic,'notas_parcela')==0) {
    $l_erro=$l_erro.'<li>A parcela deste lançamento deve ter pelo menos uma nota.';
    $l_tipo=0;
  }

  // 4 - Se o lançamento tem notas, verifica se o valor do lançamento é igual à soma dos valores das notas
  $l_rs3 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'NOTA');
  if (count($l_rs3)<=0) {
    $l_existe_rs3=0;
  } else { 
    $l_existe_rs3=count($l_rs3);    
    if (f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_nota') && f($l_rs_tramite,'ativo')=='S') {
      $l_erro=$l_erro.'<li>O valor do lançamento (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor'),0)).'</b>) difere da soma dos valores das notas (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor_nota'),0)).'</b>).';
      $l_tipo=0;
    }
  }

  if (nvl(f($l_rs_solic,'sq_projeto'),'')>'' && nvl(f($l_rs_solic,'tipo_rubrica'),'')<>1) {
    $l_rs_rubrica = db_getSolicRubrica::getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),null,null,null,null,null,null,null,null);
    if (count($l_rs_rubrica)>0) {
      $l_rs_menu = db_getLinkData::getInstanceOf($dbms,$w_cliente,'FNREVENT');
      $l_rs_tipo = db_getLancamentoProjeto::getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),f($l_rs_menu,'sq_menu'),null);
      foreach($l_rs_tipo as $l_row){$l_rs_tipo=$l_row; break;}
      if (count($l_rs_tipo)>0) {
        if (f($l_rs_tipo,'sg_tramite')<>'AT') {
          $l_erro=$l_erro.'<li>Para a execução de novos lançamentos para o projeto <b>'.f($l_rs_solic,'nm_projeto').'</b>, o lançamento de dotação inicial deve estar liquidado.';
          $l_tipo=0;        
        }
      }
    }
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
    if (f($l_rs_tramite,'ordem')>1) {
      $l_erro=$l_erro;
      if (Nvl(f($l_rs_tramite,'sigla'),'---')=='EE') {
        // 4 - Recupera os documentos associados ao lançamento
        $l_rs1 = db_getLancamentoDoc::getInstanceOf($dbms,$l_chave,null,'DOCS');
        if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
        if ($l_existe_rs1==0) {
          // 5 - Verifica se foi informado pelo menos um documento
          $l_erro=$l_erro.'<li>Não foram informados documentos para o lançamento. Informe pelo menos um.';
          $l_tipo=0;
        } else {
          if ($l_existe_rs2==0 && (nvl(f($l_rs_solic,'tipo_rubrica'),'')!='')) {
            // 7 - Verifica se foi informado pelo menos um item no documento
            $l_erro=$l_erro.'<li>Não foram informados itens para o documento. Informe pelo menos um.';
            $l_tipo=0;
          } 
        }
      } elseif ((Nvl(f($l_rs_tramite,'sigla'),'---')=='AT')&&(Nvl(f($l_rs_solic,'tipo_rubrica'),0)==1)) {
        // Verifica se o tipo de movimentação é dotação inicial e se for nao pode haver retorno 
        // para fases anteriores se houver outros lancamentos ativos.
        $l_rs1 = db_getLancamentoProjeto::getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),f($l_rs_solic,'sq_menu'),'LANCAMENTOS');
        if(count($l_rs1)>0) {
          $l_erro=$l_erro.'<li>O envio deste lançamento só pode ser feito após o cancelamento de todos os outros lançamentos deste projeto.';
          $l_tipo=0;
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