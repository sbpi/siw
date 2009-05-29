<?php
// =========================================================================
// Rotina de validação dos dados da missão
// -------------------------------------------------------------------------
function ValidaViagem($v_cliente,$v_chave,$v_sg1,$v_sg2,$v_sg3,$v_sg4,$v_tramite) {
  extract($GLOBALS);

  // Se não encontrar erro, esta função retorna cadeia fazia.
  // Se o retorno for diferente de cadeia vazia, o primeiro byte indica o tipo de erro
  // 0 - Erro de integridade. A solicitação só pode ser devolvida
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
  $l_rs_solic = db_getSolicData::getInstanceOf($dbms,$v_chave,$v_sg1);

  // Se a solicitação informada não existir, abandona a execução
  if (($l_rs_solic==0)) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  } 

  // Verifica se o cliente tem o módulo de viagens contratado
  $l_rs_modulo = db_getSiwCliModLis::getInstanceOf($dbms,$v_cliente,null,'PD');
  if (!($l_rs_modulo==0)) $l_viagem='S'; else $l_viagem='N';

  $l_erro   = '';
  $l_tipo   = '';

  // Recupera o trâmite atual da solicitação
  $l_rs_tramite = db_getTramiteData::getInstanceOf($dbms,f($l_rs_solic,'sq_siw_tramite'));

  // Recupera os dados do beneficiário
  $l_rs1 = db_getBenef::getInstanceOf($dbms,$v_cliente,Nvl(f($l_rs_solic,'sq_prop'),0),null,null,null,null,null,null,null,null,null,null,null,null);
  $l_existe_rs1 = count($l_rs1);
  if ($l_existe_rs1>0) {
    foreach($l_rs1 as $l_row) { $l_rs1 = $l_row; break; }
  }

  // Recupera os parâmetros do módulo de viagem
  $l_rs2 = db_getPDParametro::getInstanceOf($dbms,$v_cliente,null,null); 
  $l_existe_rs2 = count($l_rs2);

  // Recupera os deslocamentos da viagem
  $l_rs3 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave, null, 'S', $v_sg2);
  $l_rs3 = SortArray($l_rs3,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  $l_existe_rs3 = count($l_rs3);

  // Recupera as vinculações da viagem
  $l_rs4 = db_getPD_Vinculacao::getInstanceOf($dbms,$v_chave,null,null);
  $l_existe_rs4 = count($l_rs4);

  // Recupera as diárias da solicitação de viagem
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

  // Recupera as diárias da prestação de contas de viagem
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
  // O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
  // através do JavaScript por envolver mais de uma tela
  //-----------------------------------------------------------------------------------

  //-----------------------------------------------------------------------------
  // Verificações de integridade de dados da solicitação, feitas sempre que houver
  // um encaminhamento.
  //-----------------------------------------------------------------------------

  // Verifica se foi indicado o beneficiário e se seus dados estão completos
      if (Nvl(f($l_rs_tramite,'ordem'),'---')=='1') {
          // Verifica se foi indicado o beneficiário
          if ($l_existe_rs1==0) {
            $l_erro .= '<li>O beneficiário não foi informado';
            $l_tipo  = 0;
          } else {
            // Verifica se o beneficiário tem os dados bancários cadastrados
            if (nvl(f($l_rs_solic,'sq_forma_pagamento'),'')=='') {
              $l_erro .= '<li>Dados bancários precisam ser confirmados. Acesse a tela do beneficiário e clique no botão "Gravar"';
              $l_tipo  = 0;
            } 
          } 
    
          if (f($l_rs_solic,'fim_semana')=='S' and nvl(f($l_rs_solic,'justificativa_dia_util'),'')=='') {
            $l_erro .= '<li>Não foi informada a justificativa para viagem abrangendo fim de semana/feriado.';
            if ($l_tipo=='') $l_tipo = 2;
          }
          
          if ((mktime(0,0,0,date(m),date(d),date(Y))>f($l_rs_solic,'limite_envio')) && nvl(f($l_rs_solic,'justificativa'),'')=='') {
            $l_erro .= '<li>Não foi informada a justificativa para não cumprimento dos '.f($l_rs_solic,'dias_antecedencia').' dias de antecedência do pedido, a ser informada no momento do envio da solicitação.';
            if ($l_tipo=='') $l_tipo = 2;
          }
      } 
/**
*       // Verifica se a viagem foi vinculada a pelo menos uma tarefa
*       if ($l_existe_rs4==0) {
*          $l_erro .= '<li>É obrigatório vincular a pelo menos uma atividade ou demanda eventual.';
*          $l_tipo  = 0;
*       } 
*/
    if (f($l_rs_tramite,'sigla')=='CI' || f($l_rs_tramite,'sigla')=='DF' || f($l_rs_tramite,'sigla')=='AE') {
          // Cadastramento inicial, cotação de viagem e emissão de bilhetes deve verificar deslocamentos e diárias
      
          // Verifica se foram cadastrados pelo menos 2 deslocamentos
          if ($l_existe_rs3<2) {
            $l_erro .= '<li>É obrigatório informar pelo menos 2 deslocamentos.';
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
                  $l_erro .= '<li>Não pode haver sobreposição de períodos entre diferentes deslocamentos - verifique a data de saída e de chegada de cada deslocamento.';
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
                // descarta o último registro 
                if (nvl(f($row,'diaria'),'')=='' && f($row,'saida_internacional')==0 && f($row,'chegada_internacional')==0 && (f($row,'origem_nacional')=='S' || toDate(FormataDataEdicao(f($row,'phpdt_chegada')))!=$w_fim_s)) {
                  $w_cont++;
                }
              }
              $l_i++;
            }
            if ($w_cont>0) {
              $l_erro .= '<li>Você deve indicar as diárias de cada localidade.';
              $l_tipo = 0;
            }
          }
    }      

    // Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
    if (Nvl(f($l_rs_tramite,'ordem'),'---')>'1') {
        // Verifica se o início da missão atende ao número de dias de antecedência regulamentares. 
        // Se não atender, deve ser informada justificativa.
        if (!(strpos('CH,DF,EA',Nvl(f($l_rs_tramite,'sigla'),'CH'))===false)) {

        } 

        if (f($l_rs_tramite,'sigla')=='DF') {
          $l_rs5 = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave,null,'S',f($l_rs_tramite,'sigla'));
          foreach($l_rs5 as $row) {$l_rs5 = $row; break;}
          if (f($l_rs5,'existe')>0) {
            $l_erro .= '<li>É obrigatório informar a cotação de menor valor.';
            $l_tipo  = 0;
          }
        }

        if (f($l_rs_tramite,'sigla')=='AE' || f($l_rs_tramite,'sigla')=='VP') {
          if (f($l_rs_solic,'passagem')=='S') {
            $l_rs5 = db_getPD_Bilhete::getInstanceOf($dbms,$v_chave,null,null,null,null,null,null,null);
            if (count($l_rs5)==0) {
              $l_erro .= '<li>É obrigatório informar os bilhetes.';
              $l_tipo  = 0;
            }
            
            if (f($l_rs_tramite,'sigla')=='AE' || (f($l_rs_tramite,'sigla')=='VP' && f($l_rs_solic,'cumprimento')!='C')) {
              // Pelo menos um bilhete deve ter trechos vinculados
              $RS_Trecho = db_getPD_Deslocamento::getInstanceOf($dbms,$v_chave,null,((f($l_rs_tramite,'sigla')=='AE') ? 'S' : 'P'),null);
              
              // Verifica se há algum deslocamento disponível para vinculação a novos bilhetes
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
                $l_erro .= '<li>É obrigatório informar os valores a serem reembolsados.';
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
                  $l_erro .= '<li>É necessário autorizar todos os valores solicitados para reembolso.';
                  $l_tipo  = 0;
                }
              }
            }
          }
        } 

        if (f($l_rs_tramite,'sigla')=='PC') {
          if (f($l_rs_solic,'cumprimento')=='N') {
            $l_erro .= '<li>É obrigatório informar se a viagem foi cumprida e, em caso positivo, os dados da prestação de contas.';
            $l_tipo  = 0;
          }

          if (f($l_rs_solic,'cumprimento')=='P' && (nvl(f($l_rs_solic,'diaria'),'')!='' || f($l_rs_solic,'hospedagem')=='S'|| f($l_rs_solic,'veiculo')=='S')) {
            $w_cont = 0;
            $l_i    = 1;
            foreach ($l_rs6 as $row) {
              if ($l_i < count($l_rs6)) {
                // descarta o último registro 
                if (nvl(f($row,'diaria'),'')=='' && f($row,'saida_internacional')==0 && f($row,'chegada_internacional')==0 && (f($row,'origem_nacional')=='S' || toDate(FormataDataEdicao(f($row,'phpdt_chegada')))!=$w_fim_p)) {
                  $w_cont++;
                }
              }
              $l_i++;
            }
            if ($w_cont>0) {
              $l_erro .= '<li>Você deve indicar as diárias de cada localidade.';
              $l_tipo = 0;
            }
          }

          if (f($l_rs_solic,'reembolso')=='S') {
            // Valores a serem reembolsados
            $RS_Reembolso = db_getPD_Reembolso::getInstanceOf($dbms,$v_chave,null,null,null);
            if (count($RS_Reembolso)==0) {
              $l_erro .= '<li>É obrigatório informar os valores a serem reembolsados.';
              $l_tipo  = 0;
            }
          }
        }

        $l_erro = $l_erro;
    } 
  $l_erro = $l_tipo.$l_erro;
  //-----------------------------------------------------------------------------------
  // Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
  // para ser usada com a tag <UL>.
  //-----------------------------------------------------------------------------------

  return $l_erro;
} 
?>
