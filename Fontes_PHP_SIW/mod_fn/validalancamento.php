<?php
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
  $sql = new db_getSolicData; $l_rs_solic = $sql->getInstanceOf($dbms,$l_chave,$p_sg1);
  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------
  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)<=0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 
  // Verifica se o cliente tem o módulo financeiro contratado
  $sql = new db_getSiwCliModLis; $l_rs_modulo = $sql->getInstanceOf($dbms,$p_cliente,null,'FN');
  if (count($l_rs_modulo)<=0) $l_financeiro='S'; else $l_financeiro='N';
  $l_erro='';
  $l_tipo='';
  // Recupera o trâmite atual da solicitação
  $sql = new db_getTramiteData; $l_rs_tramite = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));
  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento independente da fase e em alguns casos quando a fase for
  // diferente de conclusão.
  // 1 - Verifica se o valor do lançamento é maior que zero
  // 2 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
  //-----------------------------------------------------------------------------
  // 1 - Verifica se o valor do lançamento é maior que zero
  if (f($l_rs_solic,'valor')==0) {
    $l_erro.='<li>O lançamento não pode ter valor zero.';
    $l_tipo=0;
  }

  // 2 - Verifica se o valor do lançamento é igual à soma dos valores dos documentos
  $sql = new db_getLancamentoDoc; $l_rs1 = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,'DOCS');
  if (count($l_rs1)<=0) {
    $l_existe_rs1=0;
  } else {
    $l_existe_rs1=count($l_rs1);
    $l_item = false;
    if (nvl(f($l_rs_solic,'sq_projeto_rubrica'),0)!=0) $l_item = true;
    foreach($l_rs1 as $l_row) {
      if (nvl(f($l_rs_solic,'sq_projeto_rubrica'),0)==0 && nvl(f($l_rs_solic,'tipo_rubrica'),0)!=4 && nvl(f($l_rs_solic,'tipo_rubrica'),0)!=5) {
        $sql = new db_getLancamentoRubrica; $l_rs2 = $sql->getInstanceOf($dbms,null,f($l_row,'sq_lancamento_doc'),null,null);
        if (count($l_rs2)<=0) $l_existe_rs2=0; else $l_existe_rs2=count($l_rs2);
        if($l_existe_rs2>0) {
          $l_valor_rubrica=0;
          foreach($l_rs2 as $l_row2) $l_valor_rubrica += f($l_row2,'valor'); 
          if (((f($l_row,'valor')!=$l_valor_rubrica) && count($l_rs2)!=0) && f($l_rs_tramite,'ativo')=='S') {
            $l_erro.='<li>'.f($l_row,'nm_tipo_documento').' - '.f($l_row,'numero').': Soma dos valores das rubricas(<b>R$ '.formatNumber(Nvl($l_valor_rubrica,0)).'</b>) difere do valor do documento(<b>R$ '.formatNumber(Nvl(f($l_row,'valor'),0)).'</b>).';
            $l_tipo=0;
          }          
        }
      } elseif ($l_item || nvl(f($l_rs_solic,'tipo_rubrica'),'')!='') {
        if (f($l_row,'detalha_item')=='S') $l_item = true;
        $sql = new db_getLancamentoItem; $l_rs2 = $sql->getInstanceOf($dbms,null,f($l_row,'sq_lancamento_doc'),null,null,null);
        if (count($l_rs2)<=0) $l_existe_rs2=0; else $l_existe_rs2=count($l_rs2);
        if (((f($l_row,'valor')!=f($l_row,'total_item')) && count($l_rs2)!=0) && f($l_rs_tramite,'ativo')=='S') {
          $l_erro.='<li>'.f($l_row,'nm_tipo_documento').' - '.f($l_row,'numero').': Soma dos valores dos itens(<b>R$ '.formatNumber(Nvl(f($l_row,'total_item'),0)).'</b>) difere do valor do documento(<b>R$ '.formatNumber(Nvl(f($l_row,'valor'),0)).'</b>).';
          $l_tipo=0;
        }
      }
    }
    if (f($l_rs_solic,'sigla')!='FNDVIA' && f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_doc') && f($l_rs_tramite,'ativo')=='S') {
      $l_erro.='<li>O valor do lançamento (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor'),0)).'</b>) difere da soma dos valores dos documentos (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor_doc'),0)).'</b>).';
      $l_tipo=0;
    }
  }

  // 3 - Se o lançamento está ligado a acordo com nota, ele deve ter pelo menos uma nota e a parcela ao qual está ligado também.
  if (f($l_rs_solic,'notas_acordo')>0 && f($l_rs_solic,'qtd_nota')==0) {
    $l_erro.='<li>Este lançamento deve ter pelo menos uma nota.';
    $l_tipo=0;
  }

  if (f($l_rs_solic,'notas_acordo')>0 && f($l_rs_solic,'notas_parcela')==0) {
    $l_erro.='<li>A parcela deste lançamento deve ter pelo menos uma nota.';
    $l_tipo=0;
  }

  // 4 - Se o lançamento tem notas, verifica se o valor do lançamento é igual à soma dos valores das notas
  $sql = new db_getLancamentoDoc; $l_rs3 = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,'NOTA');
  if (count($l_rs3)<=0) {
    $l_existe_rs3=0;
  } else { 
    $l_existe_rs3=count($l_rs3);    
    if (f($l_rs_solic,'valor')!=f($l_rs_solic,'valor_nota') && f($l_rs_tramite,'ativo')=='S') {
      $l_erro.='<li>O valor do lançamento (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor'),0)).'</b>) difere da soma dos valores das notas (<b>R$ '.formatNumber(Nvl(f($l_rs_solic,'valor_nota'),0)).'</b>).';
      $l_tipo=0;
    }
  }

  // 5 - Pagamento de contrato só pode ser enviado se o saldo do contrato for maior ou igual ao valor do pagamento
  if (f($l_rs_solic,'sigla')=='FNDCONT') {
    // Recupera os dados do contrato
    $v_dados_pai = explode('|@|',f($l_rs_solic,'dados_pai'));
    $sql = new db_getSolicData; $l_rs_cont = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_solic_pai'),$v_dados_pai[5]);
    if (f($l_rs_solic,'valor')>f($l_rs_cont,'saldo_contrato') && f($l_rs_tramite,'ativo')=='S') {
      $l_erro.='<li>Saldo de <b>'.$v_dados_pai[1].' (R$ '.formatNumber(f($l_rs_cont,'saldo_contrato')).')</b> não é suficiente para pagamento do valor deste lançamento (<b>R$ '.formatNumber(f($l_rs_solic,'valor')).'</b>).';
      $l_tipo=0;
    }
  }
  
  if (nvl(f($l_rs_solic,'sq_projeto'),'')>'' && nvl(f($l_rs_solic,'tipo_rubrica'),'')<>1) {
    $sql = new db_getSolicRubrica; $l_rs_rubrica = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),null,null,null,null,null,null,null,null);
    if (count($l_rs_rubrica)>0) {
      $sql = new db_getLinkData; $l_rs_menu = $sql->getInstanceOf($dbms,$w_cliente,'FNREVENT');
      $sql = new db_getLancamentoProjeto; $l_rs_tipo = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),f($l_rs_menu,'sq_menu'),null);
      foreach($l_rs_tipo as $l_row){$l_rs_tipo=$l_row; break;}
      if (count($l_rs_tipo)>0) {
        if (f($l_rs_tipo,'sg_tramite')<>'AT') {
          $l_erro.='<li>Para a execução de novos lançamentos para o projeto <b>'.f($l_rs_solic,'nm_projeto').'</b>, o lançamento de dotação inicial deve estar liquidado.';
          $l_tipo=0;        
        }
      }
    }
  }  
  if (count($l_rs_tramite)>0) {
    // Recupera os dados da pessoa
    $sql = new db_getBenef; $l_rs1 = $sql->getInstanceOf($dbms,$p_cliente,Nvl(f($l_rs_solic,'pessoa'),0),null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
    if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
    foreach ($l_rs1 as $row){$l_rs1 = $row; break;}
    if ($l_existe_rs1==0) {
      // Verifica se foi indicada a pessoa
      $l_erro.='<li>A pessoa não foi informada';
      $l_tipo=0;
    } else {
      if (!(Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==Nvl(f($l_rs1,'sq_tipo_pessoa'),0))) {
        // Verifica se a pessoa informada é do tipo indicada no cadastro do lançamento 
        $l_erro.='<li>A pessoa não é do tipo informado na tela de dados gerais.';
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
      $l_erro.='<li>Dados bancários incompletos. Acesse a operação "'.(($P2==1) ? 'Ajustar beneficiário' : 'Pessoa').'", confira os dados e grave a tela.';
      $l_tipo=0;
    }

    // 4 - Recupera os documentos associados ao lançamento
    if (substr(f($l_rs_solic,'sigla'),0,3)=='FND') {
      $sql = new db_getLancamentoDoc; $l_rs1 = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,'DOCS');
      if (count($l_rs1)<=0) $l_existe_rs1=0; else $l_existe_rs1=count($l_rs1);
      if ($l_existe_rs1==0) {
        // 5 - Verifica se foi informado pelo menos um documento
        $l_erro.='<li>Não foram informados documentos para o lançamento. Acesse a operação "'.(($P2==1) ? 'Ajustar documentos' : 'Docs').'" e informe pelo menos um.';
        $l_tipo=0;

      /*
      } else {
        if ($l_item && $l_existe_rs2==0 && (nvl(f($l_rs_solic,'sq_projeto_rubrica'),0)!=0 || nvl(f($l_rs_solic,'tipo_rubrica'),'')!='')) {
          // 7 - Verifica se foi informado pelo menos um item no documento
          $l_erro.='<li>Não foram informados itens para o documento. Acesse a operação "'.(($P2==1) ? 'Ajustar documentos' : 'Itens do documento').'" e informe pelo menos um.';
          $l_tipo=0;
        } 
       */
      }
    }
    
  // Este bloco faz verificações em solicitações que estão em fases posteriores ao
  // cadastramento inicial
  if (f($l_rs_tramite,'ordem')>1 || f($l_rs_solic,'sigla')=='FNDREEMB') {
      if ((f($l_rs_tramite,'sigla')=='AT')&&(Nvl(f($l_rs_solic,'tipo_rubrica'),0)==1)) {
        // Verifica se o tipo de movimentação é dotação inicial e se for nao pode haver retorno 
        // para fases anteriores se houver outros lancamentos ativos.
        $sql = new db_getLancamentoProjeto; $l_rs1 = $sql->getInstanceOf($dbms,f($l_rs_solic,'sq_projeto'),f($l_rs_solic,'sq_menu'),'LANCAMENTOS');
        if(count($l_rs1)>0) {
          $l_erro.='<li>O envio deste lançamento só pode ser feito após o cancelamento de todos os outros lançamentos deste projeto.';
          $l_tipo=0;
        }
      }
      // Se pagamento de viagem, verifica se há prestação de contas pendente
      if ((f($l_rs_tramite,'sigla')=='EE' || f($l_rs_tramite,'sigla')=='PP') && f($l_rs_solic,'sigla')=='FNDVIA') {
        // Recupera a vinculação do lançamento financeiro
        $sql = new db_getLinkData; $l_rsm = $sql->getInstanceOf($dbms,$w_cliente,'PDINICIAL');
        $sql = new db_getSolicList; $l_rs1 = $sql->getInstanceOf($dbms,f($l_rsm,'sq_menu'),$w_usuario,f($l_rsm,'sigla'),5,
            null,null,null,null,'S',null,null,null,null,null,null, null, null,null,null,null,null,null,null,null,null,null,null,null,null, 
            f($l_rs_solic,'pessoa'));
        $l_rs1 = SortArray($l_rs1,'codigo_interno','asc');
        if(count($l_rs1)>0) {
          $w_pendencia = '';
          foreach($l_rs1 as $row) {
            if (f($row,'atraso_pc')=='S' && f($row,'sq_siw_solicitacao')!=nvl(f($l_rs_solic,'sq_solic_pai'),0)) {
              $w_pendencia.=', '.f($row,'codigo_interno');
            }
          }
          if ($w_pendencia!='') {
            $l_erro.='<li>Pagamento bloqueado em função de prestação de contas pendente: <b>'.substr($w_pendencia,2).'</b>.';
            $l_tipo=0;
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