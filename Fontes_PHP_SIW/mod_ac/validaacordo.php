<?php
// =========================================================================
// Rotina de validação dos dados do acordo
// -------------------------------------------------------------------------

function ValidaAcordo($l_cliente,$l_chave,$l_sg1,$l_sg2,$l_sg3,$l_sg4,$l_tramite) {
  extract($GLOBALS);
  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. Nem gestores podem encaminhar a solicitação
  // 1 - Erro de regra de negócio. Apenas gestores podem encaminhar a solicitação
  // 2 - Alerta. O sistema indica uma situação não desejável mas permite que o usuário
  //     encaminhe o projeto
  //-----------------------------------------------------------------------------------
  // Cria recordsets e variáveis de trabalho.
  // l_rs1 até l_rs4 são recordsets que podem ser usados para armazenar dados de blocos
  // de dados específicos da solicitação que está sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // compõem a solicitação
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicitação
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$l_chave,$l_sg1);
  // Se a solicitação informada não existir, abandona a execução
  if (count($l_rs_solic)==0) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 
  $l_erro='';
  $l_tipo='';  
  if (f($l_rs_solic,'ativo')=='S') {
    if (substr(f($l_rs_solic,'sigla'),0,3)=='GCR' && f($l_rs_solic,'cd_modalidade')!='F') {
      $l_erro=$l_erro.'<li>Para contrato de receita, o tipo deve ser de fornecimento.';
      $l_tipo=0;
    } elseif (substr(f($l_rs_solic,'sigla'),0,3)=='GCA' && f($l_rs_solic,'cd_modalidade')!='I') {
      $l_erro=$l_erro.'<li>Para ACT, o tipo deve ser de parceria institucional.';
      $l_tipo=0;
    } elseif (substr(f($l_rs_solic,'sigla'),0,3)=='GCB' && f($l_rs_solic,'cd_modalidade')!='E') {
      $l_erro=$l_erro.'<li>Para contrato de bolsista, o tipo deve ser de emprego.';
      $l_tipo=0;
    } elseif (substr(f($l_rs_solic,'sigla'),0,3)=='GCD' && (f($l_rs_solic,'cd_modalidade')=='F'&&f($l_rs_solic,'cd_modalidade')=='I')) {
      $l_erro=$l_erro.'<li>Para contrato de despesa, o tipo não pode ser de fornecimento e não pode ser de parceria institucional.';
      $l_tipo=0;      
    } elseif (substr(f($l_rs_solic,'sigla'),0,3)=='GCP' && f($l_rs_solic,'cd_modalidade')!='I') {
      $l_erro=$l_erro.'<li>Para contrato de parceria, o tipo deve ser de parceria institucional.';
      $l_tipo=0;
    }    
  }
  if(Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==1 && Nvl(f($l_rs_solic,'pessoa_fisica'),'')=='N') {
      $l_erro=$l_erro.'<li>Tipo do contrato, não perimite a contração de pessoa física.';
      $l_tipo=0;
  } elseif (Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==2 && Nvl(f($l_rs_solic,'pessoa_juridica'),'')=='N') {
      $l_erro=$l_erro.'<li>Tipo do contrato, não perimite a contração de pessoa jurídica.';
      $l_tipo=0;
  }
  // Verifica se o cliente tem o módulo de acordos contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$l_cliente,null,'AC');
  if (count($l_rs_modulo)>0) $l_acordo='S'; else $l_acordo='N';
  
  // Recupera o trâmite atual da solicitação
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));

  // Recupera os dados da outra parte
  $l_rs1 = db_getBenef::getInstanceOf($dbms,$l_cliente,Nvl(f($l_rs_solic,'outra_parte'),0),null,null,null,null,null,null,null,null,null,null,null,null);
  if (($l_rs1==0)) {
    $l_existe_rs1=0; 
  } else {
    $l_existe_rs1=count($l_rs1);
    foreach($l_rs1 as $row) {
      $l_rs1 = $row;
      break;
    }
  }

  // Recupera os dados do preposto
  $l_rs2 = db_getBenef::getInstanceOf($dbms,$l_cliente,Nvl(f($l_rs_solic,'preposto'),0),null,null,null,null,null,null,null,null,null,null,null,null);
  if (count($l_rs2)==0) {
    $l_existe_rs2=0; 
  } else {
    $l_existe_rs2=count($l_rs2);
    foreach($l_rs2 as $row) {
      $l_rs2 = $row;
      break;
    }
  }

  // Recupera os dados das parcelas
  $l_rs3 = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,'VALIDA',null,null,null,null,null,null,null);
  if (count($l_rs3)==0) {
    $l_existe_rs3=0; 
  } else {
    $l_existe_rs3=count($l_rs3);
  }

  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Validações para a outra parte e preposto
      // Verifica se foi indicada a outra parte
      if ($l_existe_rs1==0) {
        if (substr($l_sg1,0,3)!='GCB')$l_erro.='<li>A outra parte não foi informada'; 
        else                          $l_erro.='<li>O bolsista não foi informado';
        $l_tipo=0;
      } else {
        // Validação do preposto
        // Não há preposto para contratos com pessoa física
        if (Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==1) {
          // Se outra parte for pessoa física, não pode ter preposto
          if ($l_existe_rs2>0) {
            $l_erro.='<li>Quando a outra parte é pessoa física não pode haver preposto.';
            $l_tipo=0;
          } 
        } else {
          // Validaçao para pessoa jurídica
          if (!(Nvl(f($l_rs_solic,'sq_tipo_pessoa'),0)==Nvl(f($l_rs1,'sq_tipo_pessoa'),0))) {
            // A outra parte deve ser do tipo informado na tela de dados gerais
            $l_erro.='<li>A outra parte não é do tipo informado na tela de dados gerais.';
            $l_tipo=0; 
          } 
  
          // Se outra parte for pessoa jurídica, deve ter preposto
          if ($l_existe_rs2==0) {
            $l_erro.='<li>O preposto não foi informado.';
            $l_tipo=0;
          } else {
            if (!(Nvl(f($l_rs2,'sq_tipo_pessoa'),0)==1)) {
              // O preposto deve ser pessoa física
              $l_erro.='<li>O preposto deve ser pessoa física.';
              $l_tipo=0;
            } 
          } 
        } 
      }

      // Verifica os dados bancários
      $l_erro_banco = 0;
      if (substr(f($l_rs_solic,'sigla'),0,3)!='GCR' && substr(f($l_rs_solic,'sigla'),0,3)!='GCZ') {
        if (!(strpos('CREDITO,DEPOSITO',f($l_rs_solic,'sg_forma_pagamento'))===false)) {
          if (substr(f($l_rs_solic,'sigla'),0,3)=='GCD') {
            if (nvl(f($l_rs_solic,'sq_agencia'),'')=='' || nvl(f($l_rs_solic,'numero_conta'),'')=='') $l_erro_banco = 1;
          }
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
        $l_erro=$l_erro.'<li>Dados bancários incompletos. Acesse a opção "Outra parte", confira os dados e grave a tela.';
        $l_tipo=0;
      }

      // Verifica as parcelas
      if (substr(f($l_rs_solic,'sigla'),0,3)!='GCA' && substr(f($l_rs_solic,'sigla'),0,3)!='GCZ') {
        if ($l_existe_rs3==0) {
          $l_erro.='<li>É obrigatório informar pelo menos uma parcela';
          $l_tipo=0;
        } else {
          // Verifica se a soma das parcelas é igual ao valor total do acordo
          $l_valor_pacelas=0.00;
          foreach($l_rs3 as $row) { $l_valor_parcelas += f($row,'valor'); }
          if (round(f($l_rs_solic,'valor')-$l_valor_parcelas,2)!=0) {
            $l_erro.='<li>Valor do acordo ('.formatNumber(f($l_rs_solic,'valor')).') difere da soma das parcelas ('.formatNumber($l_valor_parcelas).')';
            $l_tipo=0;
          }
        }
      }  
  // Este bloco faz verificações em solicitações que estão em fases posteriores ao
  // cadastramento inicial
      if (count($l_rs_tramite)>0) {
        if (Nvl(f($l_rs_tramite,'ordem'),'---')>'1') {
          $l_erro=$l_erro; 
        } 
      } 
  // Configura a variável de retorno com o tipo de erro e a mensagem
  $l_erro = $l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
}
?>
