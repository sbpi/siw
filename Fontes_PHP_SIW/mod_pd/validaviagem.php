<?php

// =========================================================================
// Rotina de validação dos dados da missão
// -------------------------------------------------------------------------
function ValidaViagem($v_cliente, $v_chave, $v_sg1, $v_sg2, $v_sg3, $v_sg4, $v_tramite) {
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
  $sql = new db_getSolicData;
  $l_rs_solic = $sql->getInstanceOf($dbms, $v_chave, $v_sg1);

// Se a solicitação informada não existir, abandona a execução
  if (($l_rs_solic == 0)) {
    return '0<li>Não existe registro no banco de dados com o número informado.';
  }

// Verifica se o cliente tem o módulo de viagens contratado
  $sql = new db_getSiwCliModLis;
  $l_rs_modulo = $sql->getInstanceOf($dbms, $v_cliente, null, 'PD');
  if (!($l_rs_modulo == 0))
    $l_viagem = 'S'; else
    $l_viagem = 'N';

  $l_erro = '';
  $l_tipo = '';

// Recupera o trâmite atual da solicitação
  $sql = new db_getTramiteData;
  $l_rs_tramite = $sql->getInstanceOf($dbms, f($l_rs_solic, 'sq_siw_tramite'));

// Recupera os dados do beneficiário
  $sql = new db_getBenef;
  $l_rs1 = $sql->getInstanceOf($dbms, $v_cliente, Nvl(f($l_rs_solic, 'sq_prop'), 0), null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
  $l_existe_rs1 = count($l_rs1);
  if ($l_existe_rs1 > 0) {
    foreach ($l_rs1 as $l_row) {
      $l_rs1 = $l_row;
      break;
    }
  }

// Recupera os parâmetros do módulo de viagem
  $sql = new db_getPDParametro;
  $l_rs2 = $sql->getInstanceOf($dbms, $v_cliente, null, null);
  $l_existe_rs2 = count($l_rs2);

// Recupera os deslocamentos da viagem
  $sql = new db_getPD_Deslocamento;
  $l_rs3 = $sql->getInstanceOf($dbms, $v_chave, null, 'S', $v_sg2);
  $l_rs3 = SortArray($l_rs3, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  $l_existe_rs3 = count($l_rs3);

// Recupera as vinculações da viagem
  $sql = new db_getPD_Vinculacao;
  $l_rs4 = $sql->getInstanceOf($dbms, $v_chave, null, null);
  $l_existe_rs4 = count($l_rs4);

  
  
  if (nvl(f($l_rs_solic, 'diaria'), '') == '' && f($l_rs_solic, 'hospedagem') == 'N' && f($l_rs_solic, 'veiculo') == 'N') {
    //Viagem sem pagamento de diárias/hospedagem/veículo
    $l_existe_diaria = 0;
    $l_existe_rs5 = 0;
    $l_existe_rs6 = 0;
  } else {  
    $l_existe_diaria = 1;
    // Recupera as diárias da solicitação de viagem
    $sql = new db_getPD_Deslocamento;
    $l_rs5 = $sql->getInstanceOf($dbms, $v_chave, null, 'S', 'PDDIARIA');
    $l_existe_rs5 = count($l_rs5);
    $l_rs5 = SortArray($l_rs5, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    $l_i = 0;
    foreach ($l_rs5 as $row) {
      if ($l_i == 0) $w_inicio_s = f($row, 'saida');
      $w_fim_s = f($row, 'chegada');
      $l_i++;
    }
    reset($l_rs5);

  // Recupera as diárias da prestação de contas de viagem
    $sql = new db_getPD_Deslocamento;
    $l_rs6 = $sql->getInstanceOf($dbms, $v_chave, null, 'P', 'PDDIARIA');
    $l_existe_rs6 = count($l_rs6);
    $l_rs6 = SortArray($l_rs6, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    $l_i = 0;
    foreach ($l_rs6 as $row) {
      if ($l_i == 0)
        $w_inicio_p = f($row, 'saida');
      $w_fim_p = f($row, 'chegada');
      $l_i++;
    }
    reset($l_rs6);
  }

//-----------------------------------------------------------------------------------
// O bloco abaixo faz as validações na solicitação que não são possíveis de fazer
// através do JavaScript por envolver mais de uma tela
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------
// Verificações de integridade de dados da solicitação, feitas sempre que houver
// um encaminhamento.
//-----------------------------------------------------------------------------
// Verifica se foi indicado o beneficiário e se seus dados estão completos
  if (Nvl(f($l_rs_tramite, 'ordem'), '---') == '1') {
// Verifica se foi indicado o beneficiário
    if ($l_existe_rs1 == 0) {
      $l_erro .= '<li>O beneficiário não foi informado';
      $l_tipo = 0;
    } else {

// Verifica se o beneficiário tem os dados mínimos cadastrados
      if(nvl(f($l_rs1, 'sq_tipo_pessoa'), '') == 1 && nvl(f($l_rs1, 'cpf'), '') == ''){
        $l_erro .= '<li>CPF do beneficiário da viagem não informado. Entre em contato com os gestores.';
        $l_tipo = 0;          
      }elseif(nvl(f($l_rs1, 'sq_tipo_pessoa'), '') == 1 && nvl(f($l_rs1, 'cpf'), '') == ''){
        $l_erro .= '<li>Código do beneficiário estrangeiro não informado. Entre em contato com os gestores.';
        $l_tipo = 0;                  
      }
      
      if (nvl(f($l_rs1, 'email'), '') == '' || nvl(f($l_rs1, 'sexo'), '') == '' ||
              (nvl(f($l_rs1, 'sq_tipo_pessoa'), '') == 1 && ( nvl(f($l_rs1, 'rg_numero'), '') == '' || nvl(f($l_rs1, 'ddd'), '') == '')) ||
              (nvl(f($l_rs1, 'sq_tipo_pessoa'), '') == 3 && ( nvl(f($l_rs1, 'passaporte_numero'), '') == '' || nvl(f($l_rs1, 'sq_pais_passaporte'), '') == ''))
      ) {
        $l_erro .= '<li>Beneficiário da viagem com dados incompletos. Acesse a tela do beneficiário, informe os dados obrigatórios e clique no botão "Gravar"';
        $l_tipo = 0;
      }

      if (strpos('CREDITO,DEPOSITO',f($l_rs_solic,'sg_forma_pagamento'))!==false) {
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
    
    if (nvl(f($l_rs_solic,'sg_forma_pagamento'),'')=='' && $l_existe_diaria) {
      $l_erro .= '<li>Dados para pagamento das diárias precisam ser informados. Acesse a tela do beneficiário e clique no botão "Gravar"';
      $l_tipo=0;
    } elseif ($l_erro_banco==1) {
      $l_erro .= '<li>Dados bancários precisam ser confirmados. Acesse a tela do beneficiário e clique no botão "Gravar"';
      $l_tipo=0;
    }
  }
  /**
   *       // Verifica se a viagem foi vinculada a pelo menos uma tarefa
   *       if ($l_existe_rs4==0) {
   *          $l_erro .= '<li>É obrigatório vincular a pelo menos uma atividade ou demanda eventual.';
   *          $l_tipo  = 0;
   *       }
   */
  if (f($l_rs_tramite, 'sigla') == 'CI') {
// Cadastramento inicial

    if (f($l_rs_solic, 'passagem') == 'N') {
      $l_erro1 = 0;
    } else {
      $l_erro1 = 1;
    }
    foreach ($l_rs3 as $row) {
      if (f($l_rs_solic, 'passagem') == 'S') {
        if (f($row, 'passagem') == 'S') {
          $l_erro1 = 0;
          break;
        }
      } else {
        if (f($row, 'passagem') == 'S') {
          $l_erro1 = 2;
          break;
        }
      }
    }
// Verifica se foram cadastrados pelo menos 2 deslocamentos
    if ($l_erro1 == 1) {
      $l_erro .= '<li>Na tela de dados gerais foi informada a necessidade de passagens, mas nenhum deslocamento confirma essa necessidade.';
      $l_tipo = 0;
    } elseif ($l_erro1 == 2) {
      $l_erro .= '<li>Na tela de dados gerais foi informado que não há necessidade de passagens, mas pelo menos um deslocamento indica essa necessidade.';
      $l_tipo = 0;
    }

    if ($l_existe_diaria) {
// Se foi informado que há diária/hospedagem/locação de veículo, pelo menos um deslocamento deve informar a quantidade de diárias
      if (f($l_rs_solic, 'diaria') != '')
        $w_erro_diaria = true;
      if (f($l_rs_solic, 'hospedagem') == 'S')
        $w_erro_hospedagem = true;
      if (f($l_rs_solic, 'veiculo') == 'S')
        $w_erro_veiculo = true;
      $w_destino_nacional = false;

      $w_cont = 0;
      $l_i = 1;
      foreach ($l_rs5 as $row) {
        if ($l_i < count($l_rs5)) {
// descarta o último registro
          if (nvl(f($row, 'diaria'), '') == '' && f($row, 'saida_internacional') == 0 && f($row, 'chegada_internacional') == 0 && (f($row, 'origem_nacional') == 'S' || toDate(FormataDataEdicao(f($row, 'phpdt_chegada'))) != $w_fim_s)) {
            $w_cont++;
          }
          if (f($row, 'diaria') == 'S')
            $w_erro_diaria = false;
          if (f($row, 'hospedagem') == 'S')
            $w_erro_hospedagem = false;
          if (f($row, 'veiculo') == 'S')
            $w_erro_veiculo = false;
          if (f($row, 'destino_nacional') == 'S')
            $w_destino_nacional = true;
        }
        $l_i++;
      }
      if ($w_cont > 0) {
        $l_erro .= '<li>Você deve indicar as diárias de cada localidade sempre que terminar de incluir os deslocamentos ou se alterar qualquer um deles.';
        $l_tipo = 0;
      }
      if ($w_erro_diaria && $w_erro_hospedagem && $w_destino_nacional) {
        $l_erro .= '<li>Informe as localidades em que deseja recebimento de diárias ou altere essa necessidade na tela de dados gerais.';
        $l_tipo = 0;
      }
      if ($w_erro_hospedagem && $w_destino_nacional) {
        $l_erro .= '<li>Informe as localidades em que deseja hospedagens ou altere essa necessidade na tela de dados gerais.';
        $l_tipo = 0;
      }
      if ($w_erro_veiculo) {
        $l_erro .= '<li>Informe as localidades em que deseja locação de veículo ou altere essa necessidade na tela de dados gerais.';
        $l_tipo = 0;
      }
    }
  }


// Verifica se foram cadastrados pelo menos 2 deslocamentos
  if ($l_existe_rs3 < 2) {
    $l_erro .= '<li>É obrigatório informar pelo menos 2 deslocamentos.';
    $l_tipo = 0;
  } else {
// Verifica o sequenciamento dos deslocamentos
    $i = 0;
    foreach ($l_rs3 as $row) {
      if ($i == 0) {
        $w_data_atual = f($row, 'phpdt_chegada');
        $w_cidade_atual = f($row, 'cidade_dest');
      } else {
        if ($w_data_atual >= f($row, 'phpdt_saida')) {
          $l_erro .= '<li>Não pode haver sobreposição de períodos entre diferentes deslocamentos - verifique a data de saída e de chegada de cada deslocamento.';
          $l_tipo = 0;
          break;
        }
        if ($w_cidade_atual != f($row, 'cidade_orig')) {
          $l_erro .= '<li>A cidade de destino de um deslocamento deve ser a de origem do deslocamento seguinte.';
          $l_tipo = 0;
          break;
        }
        $w_data_atual = f($row, 'phpdt_chegada');
        $w_cidade_atual = f($row, 'cidade_dest');
      }
      $i++;
    }
  }
    
  if (f($l_rs_tramite, 'sigla') == 'CI') {    
    if (f($l_rs_solic, 'fim_semana') == 'S' and nvl(f($l_rs_solic, 'justificativa_dia_util'), '') == '') {
      $l_erro .= '<li>Não foi informada a justificativa para viagem abrangendo fim de semana/feriado, <b><u>a ser informada no momento do envio da solicitação</u></b>.';
      if ($l_tipo == '')
        $l_tipo = 2;
    }
    if ((mktime(0, 0, 0, date(m), date(d), date(Y)) > f($l_rs_solic, 'limite_envio')) && nvl(f($l_rs_solic, 'justificativa'), '') == '') {
      $l_erro .= '<li>Não foi informada a justificativa para não cumprimento dos ' . f($l_rs_solic, 'dias_antecedencia') . ' dias úteis de antecedência do pedido, <b><u>a ser informada no momento do envio da solicitação</b></u>.';
      if ($l_tipo == '')
        $l_tipo = 2;
    }
  }

// Este bloco faz verificações em solicitações que estão em fases posteriores ao cadastramento inicial
  if (Nvl(f($l_rs_tramite, 'ordem'), '---') > '1') {
// Verifica se o início da missão atende ao número de dias de antecedência regulamentares.
// Se não atender, deve ser informada justificativa.
    if (!(strpos('CH,DF,EA', Nvl(f($l_rs_tramite, 'sigla'), 'CH')) === false)) {
      
    }

    if (f($l_rs_tramite, 'sigla') == 'DF') {
      if (f($l_rs_solic, 'passagem') == 'S' && ($v_cliente==17305 || ($v_cliente!=17305 && f($l_rs_solic, 'internacional')=='S')) && f($l_rs_solic, 'cotacao_valor') == 0) {
        $l_erro .= '<li>É obrigatório informar a cotação de menor valor.';
        $l_tipo = 0;
      }
    }

    if (f($l_rs_tramite, 'sigla') == 'AE' || f($l_rs_tramite, 'sigla') == 'VP') {
      if (f($l_rs_solic, 'passagem') == 'S') {
        $sql = new db_getPD_Bilhete;
        $l_rs5 = $sql->getInstanceOf($dbms, $v_chave, null, null, null, null, null, null, null);
        if (count($l_rs5) == 0) {
          $l_erro .= '<li>É obrigatório informar os bilhetes.';
          $l_tipo = 0;
        }

        if (f($l_rs_tramite, 'sigla') == 'AE' || (f($l_rs_tramite, 'sigla') == 'VP' && f($l_rs_solic, 'cumprimento') != 'C')) {
// Pelo menos um bilhete deve ter trechos vinculados
          $sql = new db_getPD_Deslocamento;
          $RS_Trecho = $sql->getInstanceOf($dbms, $v_chave, null, ((f($l_rs_tramite, 'sigla') == 'AE') ? 'S' : 'P'), null);

// Verifica se há algum deslocamento disponível para vinculação a novos bilhetes
          $w_trecho = false;
          foreach ($RS_Trecho as $row) {
            if (nvl(f($row, 'sq_bilhete'), '') != '')
              $w_trecho = true;
          }
          if (!$w_trecho) {
            $l_erro .= '<li>Pelo menos um bilhete deve ter trechos vinculados.';
            $l_tipo = 0;
          }
        }
      }

      if (f($l_rs_tramite, 'sigla') == 'VP') {
        if (f($l_rs_solic, 'reembolso') == 'S') {
// Valores a serem reembolsados
          $sql = new db_getPD_Reembolso;
          $RS_Reembolso = $sql->getInstanceOf($dbms, $v_chave, null, null, null);

          if (count($RS_Reembolso) == 0) {
            $l_erro .= '<li>É obrigatório informar os valores a serem reembolsados.';
            $l_tipo = 0;
          } else {
            $w_erro = false;
            foreach ($RS_Reembolso as $row) {
              if (f($row, 'valor_autorizado') == 0 && nvl(f($row, 'observacao'), '') == '') {
                $w_erro = true;
                break;
              }
            }
            if ($w_erro) {
              $l_erro .= '<li>É necessário autorizar todos os valores solicitados para reembolso.';
              $l_tipo = 0;
            }
          }
        }
      }
    }

    if (f($l_rs_tramite, 'sigla') == 'PC') {
      if (f($l_rs_solic, 'cumprimento') == 'N') {
        $l_erro .= '<li>É obrigatório informar se a viagem foi cumprida e, em caso positivo, os dados da prestação de contas.';
        $l_tipo = 0;
      }

      if (f($l_rs_solic, 'cumprimento') == 'P' && $l_existe_diaria) {
        $w_cont = 0;
        $l_i = 1;
        foreach ($l_rs6 as $row) {
          if ($l_i < count($l_rs6)) {
// descarta o último registro
            if (nvl(f($row, 'diaria'), '') == '' && f($row, 'saida_internacional') == 0 && f($row, 'chegada_internacional') == 0 && (f($row, 'origem_nacional') == 'S' || toDate(FormataDataEdicao(f($row, 'phpdt_chegada'))) != $w_fim_p)) {
              $w_cont++;
            }
          }
          $l_i++;
        }
        if ($w_cont > 0) {
          $l_erro .= '<li>Você deve indicar as diárias de cada localidade.';
          $l_tipo = 0;
        }
      }

      if (f($l_rs_solic, 'reembolso') == 'S') {
// Valores a serem reembolsados
        $sql = new db_getPD_Reembolso;
        $RS_Reembolso = $sql->getInstanceOf($dbms, $v_chave, null, null, null);
        if (count($RS_Reembolso) == 0) {
          $l_erro .= '<li>É obrigatório informar os valores a serem reembolsados.';
          $l_tipo = 0;
        }
      }
    }

    $l_erro = $l_erro;
  }
  $l_erro = $l_tipo . $l_erro;
//-----------------------------------------------------------------------------------
// Após as verificações feitas, devolve cadeia vazia se não encontrou erros, ou string
// para ser usada com a tag <UL>.
//-----------------------------------------------------------------------------------

  return $l_erro;
}

?>
