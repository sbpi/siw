<?php
// =========================================================================
// Rotina de valida��o dos dados da miss�o
// -------------------------------------------------------------------------
function ValidaViagem($v_cliente,$v_chave,$v_sg1,$v_sg2,$v_sg3,$v_sg4,$v_tramite) {
  extract($GLOBALS);

  // Se n�o encontrar erro, esta fun��o retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. A solicita��o s� pode ser devolvida
  // 1 - Erro de regra de neg�cio. Apenas gestores podem encaminhar a solicita��o
  // 2 - Alerta. O sistema indica uma situa��o n�o desej�vel mas permite que o usu�rio
  //     encaminhe o projeto
  //-----------------------------------------------------------------------------------
  // Cria recordsets e vari�veis de trabalho.
  // l_rs1 at� l_rs4 s�o recordsets que podem ser usados para armazenar dados de blocos
  // de dados espec�ficos da solicita��o que est� sendo validada.
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------------
  // Esta primeira parte carrega recordsets com os diferentes blocos de dados que
  // comp�em a solicita��o
  //-----------------------------------------------------------------------------------
  // Recupera os dados da solicita��o
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$v_chave,$v_sg1);

  // Se a solicita��o informada n�o existir, abandona a execu��o
  if (($l_rs_solic==0)) {
    return '0<li>N�o existe registro no banco de dados com o n�mero informado.';
  } 

  // Verifica se o cliente tem o m�dulo de viagens contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$v_cliente,null,'PD');
  if (!($l_rs_modulo==0)) $l_viagem='S'; else $l_viagem='N';

  $l_erro   = '';
  $l_tipo   = '';

  // Recupera o tr�mite atual da solicita��o
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));

  // Recupera os dados do benefici�rio
  $l_rs1 = db_getBenef::getInstanceOf($dbms,$v_cliente,Nvl(f($l_rs_solic,'sq_prop'),0),null,null,null,null,null,null,null,null,null,null,null,null);
  $l_existe_rs1 = count($l_rs1);
  if ($l_existe_rs1>0) {
    foreach($l_rs1 as $l_row) { $l_rs1 = $l_row; break; }
  }

  // Recupera os par�metros do m�dulo de viagem
  $l_rs2 = db_getPDParametro::getInstanceOf($dbms,$v_cliente,null,null); 
  $l_existe_rs2 = count($l_rs2);

  // Recupera os deslocamentos da viagem
  $l_rs3 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave, null, 'S', $v_sg2);
  $l_rs3 = SortArray($l_rs3,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  $l_existe_rs3 = count($l_rs3);

  // Recupera as vincula��es da viagem
  $l_rs4 = db_getPD_Vinculacao::getInstanceOf($dbms,$v_chave,null,null);
  $l_existe_rs4 = count($l_rs4);

  // Recupera as di�rias da solicita��o de viagem
  $l_rs5 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave, null, 'S', 'PDDIARIA');
  $l_existe_rs5 = count($l_rs5);
  $l_rs5 = SortArray($l_rs5,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  $l_i = 0;
  foreach($l_rs5 as $row) {
    if ($l_i==0) $w_inicio_s = f($row,'saida');
    $w_fim_s = f($row,'chegada');
    $l_i++;
  }
  reset($l_rs5);

  // Recupera as di�rias da presta��o de contas de viagem
  $l_rs6 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave, null, 'P', 'PDDIARIA');
  $l_existe_rs6 = count($l_rs6);
  $l_rs6 = SortArray($l_rs6,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  $l_i = 0;
  foreach($l_rs6 as $row) {
    if ($l_i==0) $w_inicio_p = f($row,'saida');
    $w_fim_p = f($row,'chegada');
    $l_i++;
  }
  reset($l_rs6);
  
  //-----------------------------------------------------------------------------------
  // O bloco abaixo faz as valida��es na solicita��o que n�o s�o poss�veis de fazer
  // atrav�s do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verifica��es de integridade de dados da solicita��o, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se foi indicado o benefici�rio e se seus dados est�o completos
      if (Nvl(f($l_rs_tramite,'ordem'),'---')=='1') {
          // Verifica se foi indicado o benefici�rio
          if ($l_existe_rs1==0) {
            $l_erro .= '<li>O benefici�rio n�o foi informado';
            $l_tipo  = 0;
          } else {
            // Verifica se o benefici�rio tem os dados banc�rios cadastrados
            if (nvl(f($l_rs_solic,'sq_forma_pagamento'),'')=='') {
              $l_erro .= '<li>Dados banc�rios precisam ser confirmados. Acesse a tela do benefici�rio e clique no bot�o "Gravar"';
              $l_tipo  = 0;
            } 
          } 
    
          if (f($l_rs_solic,'fim_semana')=='S' and nvl(f($l_rs_solic,'justificativa_dia_util'),'')=='') {
            $l_erro .= '<li>N�o foi informada a justificativa para viagem abrangendo fim de semana/feriado.';
            if ($l_tipo=='') $l_tipo = 2;
          }
          
          if ((mktime(0,0,0,date(m),date(d),date(Y))>f($l_rs_solic,'limite_envio')) && nvl(f($l_rs_solic,'justificativa'),'')=='') {
            $l_erro .= '<li>N�o foi informada a justificativa para n�o cumprimento dos '.f($l_rs_solic,'dias_antecedencia').' dias de anteced�ncia do pedido, a ser informada no momento do envio da solicita��o.';
            if ($l_tipo=='') $l_tipo = 2;
          }
      } 
/**
*       // Verifica se a viagem foi vinculada a pelo menos uma tarefa
*       if ($l_existe_rs4==0) {
*          $l_erro .= '<li>� obrigat�rio vincular a pelo menos uma atividade ou demanda eventual.';
*          $l_tipo  = 0;
*       } 
*/
    if (f($l_rs_tramite,'sigla')=='CI' || f($l_rs_tramite,'sigla')=='DF' || f($l_rs_tramite,'sigla')=='AE') {
          // Cadastramento inicial, cota��o de viagem e emiss�o de bilhetes deve verificar deslocamentos e di�rias
      
          // Verifica se foram cadastrados pelo menos 2 deslocamentos
          if ($l_existe_rs3<2) {
            $l_erro .= '<li>� obrigat�rio informar pelo menos 2 deslocamentos.';
            $l_tipo  = 0;
          } else {
            // Verifica o sequenciamento dos deslocamentos
            $i = 0;
            foreach ($l_rs3 as $row) {
              if ($i==0) {
                $w_data_atual   = f($row,'phpdt_chegada');
                $w_cidade_atual = f($row,'cidade_dest');
              } else {
                if ($w_data_atual >= f($row,'phpdt_saida')) {
                  $l_erro .= '<li>N�o pode haver sobreposi��o de per�odos entre diferentes deslocamentos - verifique a data de sa�da e de chegada de cada deslocamento.';
                  $l_tipo  = 0;
                  break;
                } 
                if($w_cidade_atual!=f($row,'cidade_orig')) {
                  $l_erro .= '<li>A cidade de destino de um deslocamento deve ser a de origem do deslocamento seguinte.';
                  $l_tipo  = 0;
                  break;
                }
                $w_data_atual   = f($row,'phpdt_chegada');
                $w_cidade_atual = f($row,'cidade_dest');                
              }
              $i++;
            }
          }
    
          if (nvl(f($l_rs_solic,'diaria'),'')!='' || f($l_rs_solic,'hospedagem')=='S'|| f($l_rs_solic,'veiculo')=='S') {
            $w_cont = 0;
            $l_i    = 1;
            foreach ($l_rs5 as $row) {
              if ($l_i < count($l_rs5)) {
                // descarta o �ltimo registro 
                if (nvl(f($row,'diaria'),'')=='' && f($row,'saida_internacional')==0 && f($row,'chegada_internacional')==0 && (f($row,'origem_nacional')=='S' || toDate(FormataDataEdicao(f($row,'phpdt_chegada')))!=$w_fim_s)) {
                  $w_cont++;
                }
              }
              $l_i++;
            }
            if ($w_cont>0) {
              $l_erro .= '<li>Voc� deve indicar as di�rias de cada localidade.';
              $l_tipo = 0;
            }
          }
    }      

    // Este bloco faz verifica��es em solicita��es que est�o em fases posteriores ao cadastramento inicial
    if (Nvl(f($l_rs_tramite,'ordem'),'---')>'1') {
        // Verifica se o in�cio da miss�o atende ao n�mero de dias de anteced�ncia regulamentares. 
        // Se n�o atender, deve ser informada justificativa.
        if (!(strpos('CH,DF,EA',Nvl(f($l_rs_tramite,'sigla'),'CH'))===false)) {

        } 

        if (f($l_rs_tramite,'sigla')=='DF') {
          $l_rs5 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave,null,'S',f($l_rs_tramite,'sigla'));
          foreach($l_rs5 as $row) {$l_rs5 = $row; break;}
          if (f($l_rs5,'existe')>0) {
            $l_erro .= '<li>� obrigat�rio informar a cota��o de menor valor.';
            $l_tipo  = 0;
          }
        }

        if (f($l_rs_tramite,'sigla')=='AE' || f($l_rs_tramite,'sigla')=='VP') {
          if (f($l_rs_solic,'passagem')=='S') {
            $l_rs5 = db_getPD_Bilhete::getInstanceOf($dbms,$v_chave,null,null,null,null,null,null,null);
            if (count($l_rs5)==0) {
              $l_erro .= '<li>� obrigat�rio informar os bilhetes.';
              $l_tipo  = 0;
            }
            
            if (f($l_rs_tramite,'sigla')=='AE' || (f($l_rs_tramite,'sigla')=='VP' && f($l_rs_solic,'cumprimento')!='C')) {
              // Pelo menos um bilhete deve ter trechos vinculados
              $RS_Trecho = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave,null,((f($l_rs_tramite,'sigla')=='AE') ? 'S' : 'P'),null);
              
              // Verifica se h� algum deslocamento dispon�vel para vincula��o a novos bilhetes
              $w_trecho = false;
              foreach ($RS_Trecho as $row) {
                if (nvl(f($row,'sq_bilhete'),'')!='') $w_trecho = true;
              }
              if (!$w_trecho) {
                $l_erro .= '<li>Pelo menos um bilhete deve ter trechos vinculados.';
                $l_tipo  = 0;
              }
            }
          } 
           
          if (f($l_rs_tramite,'sigla')=='VP') {
            if (f($l_rs_solic,'reembolso')=='S') {
              // Valores a serem reembolsados
              $RS_Reembolso = db_getPD_Reembolso::getInstanceOf($dbms,$v_chave,null,null,null);

              if (count($RS_Reembolso)==0) {
                $l_erro .= '<li>� obrigat�rio informar os valores a serem reembolsados.';
                $l_tipo  = 0;
              } else {
                $w_erro = false;
                foreach ($RS_Reembolso as $row) {
                if (f($row,'valor_autorizado')==0 && nvl(f($row,'observacao'),'')=='') {
                    $w_erro = true;
                    break;
                  }
                }
                if ($w_erro) {
                  $l_erro .= '<li>� necess�rio autorizar todos os valores solicitados para reembolso.';
                  $l_tipo  = 0;
                }
              }
            }
          }
        } 

        if (f($l_rs_tramite,'sigla')=='PC') {
          if (f($l_rs_solic,'cumprimento')=='N') {
            $l_erro .= '<li>� obrigat�rio informar se a viagem foi cumprida e, em caso positivo, os dados da presta��o de contas.';
            $l_tipo  = 0;
          }

          if (f($l_rs_solic,'cumprimento')=='P' && (nvl(f($l_rs_solic,'diaria'),'')!='' || f($l_rs_solic,'hospedagem')=='S'|| f($l_rs_solic,'veiculo')=='S')) {
            $w_cont = 0;
            $l_i    = 1;
            foreach ($l_rs6 as $row) {
              if ($l_i < count($l_rs6)) {
                // descarta o �ltimo registro 
                if (nvl(f($row,'diaria'),'')=='' && f($row,'saida_internacional')==0 && f($row,'chegada_internacional')==0 && (f($row,'origem_nacional')=='S' || toDate(FormataDataEdicao(f($row,'phpdt_chegada')))!=$w_fim_p)) {
                  $w_cont++;
                }
              }
              $l_i++;
            }
            if ($w_cont>0) {
              $l_erro .= '<li>Voc� deve indicar as di�rias de cada localidade.';
              $l_tipo = 0;
            }
          }

          if (f($l_rs_solic,'reembolso')=='S') {
            // Valores a serem reembolsados
            $RS_Reembolso = db_getPD_Reembolso::getInstanceOf($dbms,$v_chave,null,null,null);
            if (count($RS_Reembolso)==0) {
              $l_erro .= '<li>� obrigat�rio informar os valores a serem reembolsados.';
              $l_tipo  = 0;
            }
          }
        }

        $l_erro = $l_erro;
    } 
  $l_erro = $l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Ap�s as verifica��es feitas, devolve cadeia vazia se n�o encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
} 
?>
