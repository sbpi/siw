<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta . 'constants.inc');
include_once($w_dir_volta . 'jscript.php');
include_once($w_dir_volta . 'funcoes.php');
include_once($w_dir_volta . 'classes/db/abreSessao.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta . 'classes/sp/db_getLinkData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuData.php');
include_once($w_dir_volta . 'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta . 'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta . 'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta . 'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta . 'classes/sp/db_getPersonData.php');
include_once($w_dir_volta . 'classes/sp/db_getCcData.php');
include_once($w_dir_volta . 'classes/sp/db_getBankData.php');
include_once($w_dir_volta . 'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta . 'classes/sp/db_getUorgList.php');
include_once($w_dir_volta . 'classes/sp/db_getUorgData.php');
include_once($w_dir_volta . 'classes/sp/db_getCountryData.php');
include_once($w_dir_volta . 'classes/sp/db_getRegionData.php');
include_once($w_dir_volta . 'classes/sp/db_getStateData.php');
include_once($w_dir_volta . 'classes/sp/db_getCityData.php');
include_once($w_dir_volta . 'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta . 'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta . 'classes/sp/db_getTramiteResp.php');
include_once($w_dir_volta . 'classes/sp/db_getTramiteSolic.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicList.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicData.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicRelAnexo.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta . 'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta . 'classes/sp/db_getSolicViagem.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Fatura.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Alteracao.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Deslocamento.php');
include_once($w_dir_volta . 'classes/sp/db_getDescontoAgencia.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Vinculacao.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Financeiro.php');
include_once($w_dir_volta . 'classes/sp/db_getPDParametro.php');
include_once($w_dir_volta . 'classes/sp/db_getMoeda.php');
include_once($w_dir_volta . 'classes/sp/db_getBenef.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Bilhete.php');
include_once($w_dir_volta . 'classes/sp/db_getPD_Reembolso.php');
include_once($w_dir_volta . 'classes/sp/db_getFNParametro.php');
include_once($w_dir_volta . 'classes/sp/db_getUserMail.php');
include_once($w_dir_volta . 'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta . 'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta . 'classes/sp/dml_putViagemGeral.php');
include_once($w_dir_volta . 'classes/sp/dml_putViagemOutra.php');
include_once($w_dir_volta . 'classes/sp/dml_putViagemEnvio.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Contas.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Reembolso.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_ReembValor.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Cotacao.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Dados.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Deslocamento.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Alteracao.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Bilhete.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Atividade.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Missao.php');
include_once($w_dir_volta . 'classes/sp/dml_putPD_Diaria.php');
include_once($w_dir_volta . 'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta . 'classes/sp/dml_putSolicRelAnexo.php');
include_once($w_dir_volta . 'classes/sp/dml_putDemandaEnvio.php');
include_once($w_dir_volta . 'classes/sp/dml_putDemandaConc.php');
include_once($w_dir_volta . 'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta . 'funcoes/retornaCadastrador_PD.php');
include_once($w_dir_volta . 'funcoes/selecaoServico.php');
include_once($w_dir_volta . 'funcoes/selecaoSolic.php');
include_once($w_dir_volta . 'funcoes/selecaoProjeto.php');
include_once($w_dir_volta . 'funcoes/selecaoEtapa.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoPCD.php');
include_once($w_dir_volta . 'funcoes/selecaoVinculo.php');
include_once($w_dir_volta . 'funcoes/selecaoPessoa.php');
include_once($w_dir_volta . 'funcoes/selecaoUnidade.php');
include_once($w_dir_volta . 'funcoes/selecaoCC.php');
include_once($w_dir_volta . 'funcoes/selecaoEtapa.php');
include_once($w_dir_volta . 'funcoes/selecaoCiaTrans.php');
include_once($w_dir_volta . 'funcoes/selecaoPais.php');
include_once($w_dir_volta . 'funcoes/selecaoRegiao.php');
include_once($w_dir_volta . 'funcoes/selecaoEstado.php');
include_once($w_dir_volta . 'funcoes/selecaoCidade.php');
include_once($w_dir_volta . 'funcoes/selecaoFase.php');
include_once($w_dir_volta . 'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta . 'funcoes/selecaoSexo.php');
include_once($w_dir_volta . 'funcoes/selecaoBanco.php');
include_once($w_dir_volta . 'funcoes/selecaoAgencia.php');
include_once($w_dir_volta . 'funcoes/selecaoMeioTransporte.php');
include_once($w_dir_volta . 'funcoes/selecaoCategoriaDiaria.php');
include_once($w_dir_volta . 'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta . 'funcoes/selecaoRubrica.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoCumprimento.php');
include_once($w_dir_volta . 'funcoes/selecaoTipoUtilBilhete.php');
include_once($w_dir_volta . 'funcoes/selecaoMoeda.php');
include_once('visualviagem.php');
include_once('validaviagem.php');

// =========================================================================
//  /viagem.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o seviço de viagens
// Mail     : celso@sbpi.com.br
// Criacao  : 05/10/2005, 11:19
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
//
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = C   : Cancelamento
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] != 'Sim') {
  EncerraSessao();
}


// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);

$w_assinatura = upper($_REQUEST['w_assinatura']);
$w_pagina = 'viagem.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pd/';
$w_troca = $_REQUEST['w_troca'];
if (strpos('PDANEXO,PDDIARIA,PDTRECHO,PDVINC', nvl($SG, 'nulo')) !== false) {
  if ($O != 'I' && $_REQUEST['w_chave_aux'] == '' && $_REQUEST['w_demanda'] == '' && $_REQUEST['w_trechos'] == '' && $_REQUEST['w_troca'] == '')
    $O = 'L';
} elseif (strpos($SG, 'ENVIO') !== false) {
  $O = 'V';
} elseif ($O == '') {
  // Se for acompanhamento, entra na filtragem
  if ($P1 == 3 || $SG == 'PDALTERA')
    $O = 'P'; else
    $O='L';
}

switch ($O) {
  case 'I': $w_TP = $TP . ' - Inclusão';
    break;
  case 'A': $w_TP = $TP . ' - Alteração';
    break;
  case 'E': $w_TP = $TP . ' - Exclusão';
    break;
  case 'P': $w_TP = $TP . ' - Filtragem';
    break;
  case 'C': $w_TP = $TP . ' - Cópia';
    break;
  case 'V': $w_TP = $TP . ' - Envio';
    break;
  case 'H': $w_TP = $TP . ' - Herança';
    break;
  default: $w_TP = $TP . ' - Listagem';
    break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.

$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);
$w_ano = RetornaAno();

//Garante que o usuário logado não foi alterado por abertura da aplicação em outra janela do navegador
if ($w_usuario!=$_SESSION['SQ_PESSOA']) EncerraSessao();

$w_copia = $_REQUEST['w_copia'];
$p_projeto = upper($_REQUEST['p_projeto']);
$p_atividade = upper($_REQUEST['p_atividade']);
$p_ativo = upper($_REQUEST['p_ativo']);
$p_solicitante = upper($_REQUEST['p_solicitante']);
$p_prioridade = upper($_REQUEST['p_prioridade']);
$p_unidade = upper($_REQUEST['p_unidade']);
$p_proponente = upper($_REQUEST['p_proponente']);
$p_sq_prop = upper($_REQUEST['p_sq_prop']);
$p_ordena = lower($_REQUEST['p_ordena']);
$p_ini_i = upper($_REQUEST['p_ini_i']);
$p_ini_f = upper($_REQUEST['p_ini_f']);
$p_fim_i = upper($_REQUEST['p_fim_i']);
$p_fim_f = upper($_REQUEST['p_fim_f']);
$p_atraso = upper($_REQUEST['p_atraso']);
$p_codigo = upper($_REQUEST['p_codigo']);
$p_chave = upper($_REQUEST['p_chave']);
$p_assunto = upper($_REQUEST['p_assunto']);
$p_pais = upper($_REQUEST['p_pais']);
$p_regiao = upper($_REQUEST['p_regiao']);
$p_uf = upper($_REQUEST['p_uf']);
$p_cidade = upper($_REQUEST['p_cidade']);
$p_usu_resp = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp = upper($_REQUEST['p_uorg_resp']);
$p_palavra = upper($_REQUEST['p_palavra']);
$p_prazo = upper($_REQUEST['p_prazo']);
$p_fase = explodeArray($_REQUEST['p_fase']);
$p_sqcc = upper($_REQUEST['p_sqcc']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $SG);
if (count($RS) > 0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}

// Recupera a configuração do serviço
if ($P2 > 0) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms, $P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms, $w_menu);
}

// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu, 'ultimo_nivel') == 'S') {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu_pai'));
}

$w_cadgeral = RetornaCadastrador_PD(f($RS_Menu, 'sq_menu'), $w_usuario);

// Verifica se o cliente tem o módulo de protocolo e arquivo
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, 'PA');
if (count($RS) > 0)
  $w_mod_pa = 'S'; else
  $w_mod_pa='N';

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms, $w_cliente);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_tipo = $_REQUEST['w_tipo'];

  if ($O == 'L') {
    if (strpos(upper($R), 'GR_') !== false || strpos(upper($R), 'PROJETO') !== false) {
      $w_filtro = '';
      if ($p_projeto > '') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $p_projeto, 'PJGERAL');
        $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave=' . $p_projeto . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '" title="Exibe as informações do projeto.">' . f($RS, 'titulo') . '</a></b>]';
      }
      if ($p_atividade > '') {
        $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms, $p_projeto, $p_atividade, 'REGISTRO', null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_filtro .= '<tr valign="top"><td align="right">Etapa <td>[<b>' . f($RS, 'titulo') . '</b>]';
      }
      if ($p_codigo > '')
        $w_filtro .= '<tr valign="top"><td align="right">Código <td>[<b>' . $p_codigo . '</b>]';
      if ($p_assunto > '')
        $w_filtro .= '<tr valign="top"><td align="right">Descrição <td>[<b>' . $p_assunto . '</b>]';
      if ($p_solicitante > '') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_solicitante, null, null);
        $w_filtro .= '<tr valign="top"><td align="right">Responsável <td>[<b>' . f($RS, 'nome_resumido') . '</b>]';
      }
      if ($p_unidade > '') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms, $p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Unidade proponente <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_proponente > '')
        $w_filtro .= '<tr valign="top"><td align="right">Beneficiário<td>[<b>' . $p_proponente . '</b>]';
      if ($p_palavra > '')
        $w_filtro .= '<tr valign="top"><td align="right">CPF beneficiário <td>[<b>' . $p_palavra . '</b>]';
      if ($p_sq_prop > '') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_sq_prop, null, null);
        $w_filtro .= '<tr valign="top"><td align="right">Beneficiário<td>[<b>' . f($RS, 'nome_resumido') . '</b>]';
      }
      if ($p_pais > '') {
        $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms, $p_pais);
        $w_filtro .= '<tr valign="top"><td align="right">País <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_regiao > '') {
        $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms, $p_regiao);
        $w_filtro .= '<tr valign="top"><td align="right">Região <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_uf > '') {
        $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms, $p_pais, $p_uf);
        $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_cidade > '') {
        $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms, $p_cidade);
        $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_usu_resp > '') {
        $sql = new db_getCiaTrans; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_usu_resp, null, null, null, null, null, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_filtro .= '<tr valign="top"><td align="right">Companhia de viagem<td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_ini_i > '')
        $w_filtro .= '<tr valign="top"><td align="right">Mês <td>[<b>' . $p_ini_i . '</b>]';
      if ($p_ativo == 'S')
        $w_filtro .= '<tr valign="top"><td align="right">Conformidade <td>[<b>Somente solicitações fora do prazo</b>]';
      if ($p_atraso == 'S')
        $w_filtro .= '<tr valign="top"><td align="right">Situação <td>[<b>Somente pendente de prestação de contas</b>]';
      if ($w_filtro > '')
        $w_filtro = '<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>';
    }

    if ($SG == 'PDALTERA') {
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
    } else {
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $SG);
    }

    if ($w_copia > '') {
      // Se for cópia, aplica o filtro sobre todas as missões visíveis pelo usuário
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, $SG, 3,
                      $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                      $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                      $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                      $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, $p_sq_prop);
    } else {
      if (Nvl($_REQUEST['p_agrega'], '') == 'GRPDCIAVIAGEM' || Nvl($_REQUEST['p_agrega'], '') == 'GRPDCIDADE' || Nvl($_REQUEST['p_agrega'], '') == 'GRPDDATA') {
        $sql = new db_getSolicViagem; $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, Nvl($_REQUEST['p_agrega'], $SG), 3,
                        $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante, $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, $p_uorg_resp, $p_palavra, $p_prazo,
                        $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, $p_orprior);
      } else {
        $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, Nvl($_REQUEST['p_agrega'], $SG), $P1,
                        $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                        $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, $p_codigo, $p_sq_prop);
      }
    }

    if (nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'ordem', 'asc', 'fim', 'desc', 'prioridade', 'asc');
    } else {
      $RS = SortArray($RS, 'ordem', 'asc', 'fim', 'desc', 'prioridade', 'asc');
    }
  }
  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 45 : 30);
    CabecalhoWord($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  } elseif ($w_tipo=='EXCEL') {
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0,1,6);
    $w_embed = 'WORD';
  } elseif ($w_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 60 : 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de ' . f($RS_Menu, 'nome'), $w_pag);
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    cabecalho();
    head();
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    if ($P1 == 2 || $P1 == 3)
      ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=' . $w_dir_volta . MontaURL('MESA') . '">');
    ShowHTML('<title>' . $conSgSistema . ' - Listagem de Viagens</title>');
    ScriptOpen('Javascript');
    Modulo();
    FormataCPF();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP', $O) !== false) {
      if ($P1 != 1 || $O == 'C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_codigo', 'Código', '', '', '2', '60', '1', '1');
        Validate('p_assunto', 'Assunto', '', '', '2', '90', '1', '1');
        Validate('p_proponente', 'Beneficiário', '', '', '2', '60', '1', '');
        Validate('p_palavra', 'CPF', 'CPF', '', '14', '14', '', '0123456789-.');
        Validate('p_ini_i', 'Primeira saída', 'DATA', '', '10', '10', '', '0123456789/');
        Validate('p_ini_f', 'Último retorno', 'DATA', '', '10', '10', '', '0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i', 'Primeira saída', '<=', 'p_ini_f', 'Último retorno');
      }
      Validate('P4', 'Linhas por página', '1', '1', '1', '4', '', '0123456789');
    }
    ValidateClose();
    ScriptClose();
    ShowHTML('</head>');
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    if ($w_embed == 'WORD') {
      // Se for Word
      BodyOpenWord();
    } elseif ($w_troca > '') {
      // Se for recarga da página
      BodyOpen('onLoad="document.Form.' . $w_troca . '.focus();\'');
    } elseif (strpos('CP', $O) !== false) {
      BodyOpen('onLoad="document.Form.p_projeto.focus();"');
    } elseif ($P1 == 2) {
      BodyOpen(null);
    } else {
      BodyOpen('onLoad="this.focus();"');
    }
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    if ((strpos(upper($R), 'GR_')) === false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 4);
    }
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td nowrap>');
    if ($P1 == 1 && $w_copia == '') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_submenu > '') {
        $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['SG']);
        foreach ($RS1 as $row) {
          $RS1 = $row;
          break;
        }
        ShowHTML('<tr><td nowrap>');
        ShowHTML('    <a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'Geral&R=' . $w_pagina . $par . '&O=I&SG=' . f($RS1, 'sigla') . '&w_menu=' . $w_menu . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
        ShowHTML('    <a accesskey="C" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>C</u>opiar</a>');
      } else {
        ShowHTML('<tr><td><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
      }
    }
    if ((strpos(upper($R), 'GR_')) === false && $P1 != 6 && $w_embed != 'WORD') {
      if ($w_copia > '') {
        // Se for cópia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
          ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
        }
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
          ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
        }
      }
    }
    ShowHTML('    <td colspan=2 nowrap align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if ($w_embed != 'WORD') {
      ShowHTML('          <td><b>' . LinkOrdena('Nº', 'codigo_interno') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Vinc.', 'dados_pai') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Beneficiário', 'nm_prop_res') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Início', 'inicio') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Fim', 'fim') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Objetivo/assunto/evento', 'descricao') . '</td>');
      if ($P1 > 1)
        ShowHTML('          <td><b>' . LinkOrdena('Fase atual', 'nm_tramite') . '</td>');
    } else {
      ShowHTML('          <td><b>Nº</td>');
      ShowHTML('          <td><b>Vinc.</td>');
      ShowHTML('          <td><b>Beneficiário</td>');
      ShowHTML('          <td><b>Início</td>');
      ShowHTML('          <td><b>Fim</td>');
      ShowHTML('          <td><b>Objetivo/assunto/evento</td>');
      if ($P1 > 1)
        ShowHTML('          <td><b>Fase atual</td>');
    }
    if ($_SESSION['INTERNO'] == 'S' && $w_embed != 'WORD')
      ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = 0;
      if ($w_embed != 'WORD') {
        $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      } else {
        $RS1 = $RS;
      }
//      exibeArray($RS1);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row, 'sigla'), f($row, 'inicio'), f($row, 'fim'), f($row, 'inicio_real'), f($row, 'fim_real'), f($row, 'aviso_prox_conc'), f($row, 'aviso'), f($row, 'sg_tramite'), null));
        if ($w_embed != 'WORD')
          ShowHTML('        <A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_volta=volta&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'codigo_interno') . '&nbsp;</a>');
        else
          ShowHTML('        ' . f($row, 'codigo_interno') . '');
        if (Nvl(f($row, 'dados_pai'), '') != '')
          ShowHTML('        <td>' . exibeSolic($w_dir, f($row, 'sq_solic_pai'), f($row, 'dados_pai'), 'N', $w_embed) . '</td>');
        else
          ShowHTML('        <td>---</td>');
        if ($w_embed != 'WORD')
          ShowHTML('        <td>' . ExibePessoa('../', $w_cliente, f($row, 'sq_prop'), $TP, f($row, 'nm_prop_res')) . '</td>');
        else
          ShowHTML('        <td>' . f($row, 'nm_prop_res') . '</td>');
        ShowHTML('        <td align="center">&nbsp;' . Nvl(FormataDataEdicao(f($row, 'inicio'), 5), '-') . '</td>');
        ShowHTML('        <td align="center">&nbsp;' . Nvl(FormataDataEdicao(f($row, 'fim'), 5), '-') . '</td>');
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($_REQUEST['p_tamanho'] == 'N') {
          ShowHTML('        <td>' . Nvl(f($row, 'descricao'), '-') . '</td>');
        } else {
          if (strlen(Nvl(f($row, 'descricao'), '-')) > 50) {
            $w_descricao = substr(Nvl(f($row, 'descricao'), '-'), 0, strpos(f($row,'descricao'),' ',40 )) . '...'; 
          } else {
            $w_descricao=Nvl(f($row, 'descricao'), '-');
          }
          if (f($row, 'sg_tramite') == 'CA') {
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'descricao')) . '"><strike>' . $w_descricao . '</strike></td>');
          } else {
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'descricao')) . '">' . $w_descricao . '</td>');
          }
        }
        if ($P1 > 1)
          ShowHTML('        <td>' . f($row, 'nm_tramite') . '</td>');
        if ($_SESSION['INTERNO'] == 'S' && $w_embed != 'WORD') {
          ShowHTML('        <td class="remover" nowrap>');
          if ($P1 != 3 && $P1 != 5 && $P1 != 6) {
            // Se não for acompanhamento
            if ($w_copia > '') {
              // Se for listagem para cópia
              $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['SG']);
              foreach ($RS as $row1) {
                $RS = $row1;
                break;
              }
              ShowHTML('          <a accesskey="I" class="HL" href="' . $w_dir . $w_pagina . 'Geral&R=' . $w_pagina . $par . '&O=I&SG=' . f($row1, 'sigla') . '&w_menu=' . $w_menu . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&w_copia=' . f($row, 'sq_siw_solicitacao') . MontaFiltro('GET') . '">Copiar</a>&nbsp;');
            } elseif ($P1 == 1) {
              // Se for cadastramento
              if ($w_submenu > '') {
                ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&R=' . $w_pagina . $par . '&SG=' . $SG . '&TP=' . $TP . '&w_documento=' . f($row, 'codigo_interno') . MontaFiltro('GET') . '" title="Alteração de informações cadastrais" TARGET="menu">AL</a>&nbsp;');
              } else {
                ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Alteração de informações cadastrais">AL</A>&nbsp');
              }
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Excluir&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exclusão.">EX</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Envio&R=' . $w_pagina . $par . '&O=V&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Encaminhamento para outra fase.">EN</A>&nbsp');
            } elseif ($P1 == 2) {
              // Se for execução
              if (f($row, 'sg_tramite') != 'PC')
                ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Anotacao&R=' . $w_pagina . $par . '&O=V&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Registra anotações para a solicitação, sem enviá-la.">AN</A>&nbsp');
              if (f($row, 'sg_tramite') == 'DF' || f($row, 'sg_tramite') == 'AE') {
                // Se cotação ou emissão de bilhetes, é possível alterar os dados da solicitação original
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'AltSolic&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Solicitação&SG=ALTSOLIC') . '\',\'AltSolic\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Alterar dados da solicitação.">SL</A>&nbsp');
              }
              if (f($row, 'sg_tramite') == 'DF') {
                if (f($row, 'internacional') == 'S')
                  ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'InformarCotacao&R=' . $w_pagina . $par . '&O=I&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Informar dados das passagens&SG=COTPASS') . '\',\'Passagens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar a cotação das passagens.">CT</A>&nbsp');
              } elseif (f($row, 'sg_tramite') == 'AE' || f($row, 'sg_tramite') == 'AC') {
                if (f($row, 'internacional') == 'S')
                  ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'InformarCotacao&R=' . $w_pagina . $par . '&O=I&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Informar dados das passagens&SG=COTPASS') . '\',\'Passagens\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar a cotação das passagens.">CT</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Bilhetes&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Bilhetes&SG=INFBIL') . '\',\'Bilhetes\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os bilhetes emitidos pela agência de viagens.">BL</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Diarias_Solic&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Diarias&SG=PDDIARIA') . '\',\'Diarias\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os dados das diárias.">DI</A>&nbsp');
              } elseif (f($row, 'sg_tramite') == 'PC') {
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'PrestarContas&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Prestação de contas&SG=PDCONTAS') . '\',\'Contas\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os dados da prestação de contas.">Prestar contas</A>&nbsp');
              } elseif (f($row, 'sg_tramite') == 'VP') {
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Bilhetes&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Bilhetes&SG=INFBIL') . '\',\'Bilhetes\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os bilhetes emitidos pela agência de viagens.">BL</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Diarias_Solic&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Diarias&SG=PDDIARIA') . '\',\'Diarias\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os dados das diárias.">DI</A>&nbsp');
                ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Reembolso&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Reembolso&SG=PDREEMB') . '\',\'Contas\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os dados do reembolso, se necessário.">RB</A>&nbsp');
              } elseif (f($row, 'sg_tramite') == 'PD') {
                //ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'PagDiaria&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Diarias&SG=PDDIARIA').'\',\'Diarias\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar o pagamento de diárias.">Informar</A>&nbsp');
              }
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'envio&R=' . $w_pagina . $par . '&O=V&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
              if (f($row, 'sg_tramite') == 'EE') {
                ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Concluir&R=' . $w_pagina . $par . '&O=V&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Conclui a execução da solicitação.">CO</A>&nbsp');
              }
            } elseif ($SG == 'PDALTERA') {
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Alteracoes&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Registro&SG=' . $SG) . '\',\'Contas\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Registrar alterações.">Registrar</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\'' . montaURL_JS(null, $conRootSIW . $w_dir . $w_pagina . 'Bilhetes&R=' . $w_pagina . $par . '&O=L&w_menu=' . $w_menu . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . ' - Bilhetes&SG=INFBIL') . '\',\'Bilhetes\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes,resizable=yes\');" title="Informar os bilhetes emitidos pela agência de viagens.">Bilhetes</A>&nbsp');
            }
          } else {
            if (RetornaGestor(f($row, 'sq_siw_solicitacao'), $w_usuario) == 'S') {
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'envio&R=' . $w_pagina . $par . '&O=V&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Envia a solicitação para outro responsável.">EN</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
            }
          }
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_embed != 'WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R > '') {
        MontaBarra($w_dir . $w_pagina . $par . '&R=' . $R . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . '&w_copia=' . $w_copia, ceil(count($RS) / $P4), $P3, $P4, count($RS));
      } else {
        MontaBarra($w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . '&w_copia=' . $w_copia, ceil(count($RS) / $P4), $P3, $P4, count($RS));
      }
      ShowHTML('</tr>');
    }
  } elseif (strpos('CP', $O) !== false) {
    if ($O == 'C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify">Para selecionar a solicitação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    }
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O == 'C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    }
    ShowHTML('     <tr valign="top">');
    $sql = new db_getLinkData; $RSF = $sql->getInstanceOf($dbms, $w_cliente, 'PJCAD');
    $sql = new db_getLinkData; $RSC = $sql->getInstanceOf($dbms, $w_cliente, 'PDINICIAL');
    SelecaoSolic('Pro<u>j</u>eto:', 'J', 'Selecione o projeto da atividade na relação.', $w_cliente, $p_projeto, f($RSF, 'sq_menu'), f($RSC, 'sq_menu'), 'p_projeto', f($RSF, 'sigla'), null, null, '<BR />', 2);
    ShowHTML('     </tr>');
    ShowHTML('     <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1 != 1 || $O == 'C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('   <tr valign="top">');
      ShowHTML('     <td valign="top"><b><U>C</U>ódigo da viagem:<br><INPUT ACCESSKEY="C" ' . $w_Disabled . ' class="STI" type="text" name="p_codigo" size="20" maxlength="60" value="' . $p_codigo . '"></td>');
      ShowHTML('     <td valign="top"><b><U>D</U>escrição:<br><INPUT ACCESSKEY="D" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="' . $p_assunto . '"></td>');
      ShowHTML('   <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:', 'N', 'Selecione o responsável pela solicitação na relação.', $p_solicitante, null, 'p_solicitante', 'USUARIOS');
      SelecaoUnidade('<U>U</U>nidade proponente:', 'U', 'Selecione a unidade proponente da solicitação', $p_unidade, null, 'p_unidade', 'VIAGEM', null);
      ShowHTML('   <tr>');
      ShowHTML('     <td valign="top"><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="P" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="60" value="' . $p_proponente . '"></td>');
      ShowHTML('     <td valign="top"><b>CP<u>F</u> do beneficiário:<br><INPUT ACCESSKEY="F" TYPE="text" class="sti" NAME="p_palavra" VALUE="' . $p_palavra . '" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('   <tr>');
      SelecaoPais('Pa<u>í</u>s destino:', 'I', null, $p_pais, null, 'p_pais', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egião destino:', 'R', null, $p_regiao, $p_pais, 'p_regiao', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('   <tr>');
      SelecaoEstado('E<u>s</u>tado destino:', 'S', null, $p_uf, $p_pais, $p_regiao, 'p_uf', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade destino:', 'C', null, $p_cidade, $p_pais, $p_uf, 'p_cidade', null, null);
      ShowHTML('   <tr>');
      SelecaoTipoPCD('Ti<u>p</u>o:', 'P', null, $p_ativo, 'p_ativo', null, null);
      SelecaoCiaTrans('Cia. Via<u>g</u>em', 'R', 'Selecione a companhia de transporte desejada.', $w_cliente, $p_usu_resp, null, 'p_usu_resp', 'S', null);
      ShowHTML('   <tr>');
      ShowHTML('     <td valign="top"><b>Pri<u>m</u>eira saída e Último retorno:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O != 'C') {
        // Se não for cópia
        ShowHTML(' <tr>');
        SelecaoFaseCheck('Recuperar fases:', 'S', null, $p_fase, $P2, 'p_fase', null, null);
      }
    }
    ShowHTML('      <tr>');
    ShowHTML('        <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" ' . $w_Disabled . ' class="STI" type="text" name="P4" size="4" maxlength="4" value="' . $P4 . '"></td></tr>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('    <tr><td align="center" colspan="3">');
    ShowHTML('          <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O == 'C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';" name="Botao" value="Remover filtro">');
    }
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();

  if ($w_tipo == 'PDF')
    RodapePdf();
  else
    Rodape();
}

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_readonly = '';
  $w_erro = '';

  // Verifica as possibilidades de vinculação do serviço
  $sql = new db_getMenuRelac; $RS_Relac = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), 'S', 'S', 'S', 'SERVICO');
  if (f($RS_Menu, 'solicita_cc') == 'N' && count($RS_Relac) == 1) {
    foreach ($RS_Relac as $row) {
      $w_sq_menu_relac = f($row, 'sq_menu');
      break;
    }
  }

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca > '') {
    // Se for recarga da página
    $w_sq_prop = $_REQUEST['w_sq_prop'];
    $w_sq_prop_nm = $_REQUEST['w_sq_prop_nm'];
    $w_sq_menu_relac = $_REQUEST['w_sq_menu_relac'];
    $w_sq_unidade_resp = $_REQUEST['w_sq_unidade_resp'];
    $w_assunto = $_REQUEST['w_assunto'];
    $w_proponente = $_REQUEST['w_proponente'];
    $w_prioridade = $_REQUEST['w_prioridade'];
    $w_aviso = $_REQUEST['w_aviso'];
    $w_dias = $_REQUEST['w_dias'];
    $w_inicio_real = $_REQUEST['w_inicio_real'];
    $w_inicio_atual = $_REQUEST['w_inicio_real'];
    $w_fim_real = $_REQUEST['w_fim_real'];
    $w_concluida = $_REQUEST['w_concluida'];
    $w_data_conclusao = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
    $w_custo_real = $_REQUEST['w_custo_real'];
    $w_atividade = $_REQUEST['w_atividade'];
    $w_chave_pai = $_REQUEST['w_chave_pai'];
    $w_chave_aux = $_REQUEST['w_chave_aux'];
    $w_sq_menu = $_REQUEST['w_sq_menu'];
    $w_sq_unidade = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite = $_REQUEST['w_sq_tramite'];
    $w_solicitante = $_REQUEST['w_solicitante'];
    $w_cadastrador = $_REQUEST['w_cadastrador'];
    $w_executor = $_REQUEST['w_executor'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_inicio = $_REQUEST['w_inicio'];
    $w_fim = $_REQUEST['w_fim'];
    $w_inclusao = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao = $_REQUEST['w_conclusao'];
    $w_opiniao = $_REQUEST['w_opiniao'];
    $w_data_hora = $_REQUEST['w_data_hora'];
    $w_uf = $_REQUEST['w_uf'];
    $w_tipo_missao = $_REQUEST['w_tipo_missao'];
    $w_passagem = $_REQUEST['w_passagem'];
    $w_diaria = $_REQUEST['w_diaria'];
    $w_hospedagem = $_REQUEST['w_hospedagem'];
    $w_veiculo = $_REQUEST['w_veiculo'];
    $w_financeiro = $_REQUEST['w_financeiro'];
    $w_rubrica = $_REQUEST['w_rubrica'];
    $w_lancamento = $_REQUEST['w_lancamento'];
  } else {
    if (strpos('AEV', $O) !== false || $w_copia > '') {
      // Recupera os dados da solicitação
      if ($w_copia > '') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_copia, $SG);
      } else {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
      }
      if (count($RS) > 0) {
        $w_sq_unidade_resp = f($RS, 'sq_unidade_resp');
        $w_assunto = f($RS, 'assunto');
        $w_proponente = f($RS, 'proponente');
        $w_prioridade = f($RS, 'prioridade');
        $w_aviso = f($RS, 'aviso_prox_conc');
        $w_dias = f($RS, 'dias_aviso');
        $w_inicio_real = f($RS, 'inicio_real');
        $w_fim_real = f($RS, 'fim_real');
        $w_concluida = f($RS, 'concluida');
        $w_data_conclusao = f($RS, 'data_conclusao');
        $w_nota_conclusao = f($RS, 'nota_conclusao');
        $w_custo_real = f($RS, 'custo_real');
        $w_chave_pai = f($RS, 'sq_solic_pai');
        $w_chave_aux = null;
        $w_sq_menu = f($RS, 'sq_menu');
        $w_sq_unidade = f($RS, 'sq_unidade');
        $w_sq_tramite = f($RS, 'sq_siw_tramite');
        $w_solicitante = f($RS, 'solicitante');
        $w_cadastrador = f($RS, 'cadastrador');
        $w_executor = f($RS, 'executor');
        $w_descricao = f($RS, 'descricao');
        $w_tipo_missao = f($RS, 'tp_missao');
        $w_passagem = f($RS, 'passagem');
        $w_diaria = f($RS, 'diaria');
        $w_hospedagem = f($RS, 'hospedagem');
        $w_veiculo = f($RS, 'veiculo');
        $w_financeiro = f($RS, 'sq_pdvinculo_bilhete');
        $w_rubrica = f($RS, 'sq_projeto_rubrica');
        $w_lancamento = f($RS, 'sq_tipo_lancamento');
        $w_inicio = FormataDataEdicao(f($RS, 'inicio'));
        if (strpos('AEV', $O) !== false) {
          $w_inicio_atual = FormataDataEdicao(f($RS, 'inicio'));
        }
        $w_fim = FormataDataEdicao(f($RS, 'fim'));
        $w_inclusao = f($RS, 'inclusao');
        $w_ultima_alteracao = f($RS, 'ultima_alteracao');
        $w_conclusao = f($RS, 'conclusao');
        $w_opiniao = f($RS, 'opiniao');
        $w_data_hora = f($RS, 'data_hora');
        $w_cpf = f($RS, 'cpf');
        $w_nm_prop = f($RS, 'nm_prop');
        $w_nm_prop_res = f($RS, 'nm_prop_res');
        $w_sexo = f($RS, 'sexo');
        $w_vinculo = f($RS, 'sq_tipo_vinculo');
        $w_uf = f($RS, 'co_uf');
        $w_sq_prop = f($RS, 'sq_prop');
        $w_dados_pai = explode('|@|', f($RS, 'dados_pai'));
        $w_sq_menu_relac = $w_dados_pai[3];
        if (nvl($w_sqcc, '') != '')
          $w_sq_menu_relac = 'CLASSIF';
      }
    }
  }

  // Se não puder cadastrar para outros, carrega os dados do usuário logado
  if ($w_cadgeral == 'N') {
    $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, $_SESSION['USERNAME'], null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
    if (count($RS) > 0) {
      foreach ($RS as $row) {
        $RS = $row;
        break;
      }
      $w_cpf = f($RS, 'cpf');
      $w_sq_prop = f($RS, 'sq_pessoa');
      $w_nm_prop = f($RS, 'nm_pessoa');
      $w_nm_prop_res = f($RS, 'nome_resumido');
      $w_sexo = f($RS, 'sexo');
      $w_vinculo = f($RS, 'sq_tipo_vinculo');
    }
  }

  if (nvl($w_sq_menu_relac, 0) > 0) {
    $sql = new db_getMenuData; $RS_Relac = $sql->getInstanceOf($dbms, $w_sq_menu_relac);
  }

  // Recupera as possibilidades de vinculação financeira
  $sql = new db_getPD_Financeiro; $RS_Financ = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, 'S', null, null, null);

  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ShowHTML('function botoes() {');
  if ($O == 'I') {
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  } else {
    ShowHTML('  document.Form.Botao.disabled = true;');
  }
  ShowHTML('}');
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_sq_menu_relac', 'Vinculação', 'SELECT', 1, 1, 18, '1', '1');
    if (nvl($w_sq_menu_relac, '') > '') {
      Validate('w_sq_menu_relac', 'Vincular a', 'SELECT', 1, 1, 18, 1, 1);
      if ($w_sq_menu_relac == 'CLASSIF') {
        Validate('w_sqcc', 'Classificação', 'SELECT', 1, 1, 18, 1, 1);
      } else {
        Validate('w_chave_pai', 'Vinculação', 'SELECT', 1, 1, 18, 1, 1);
      }
    }
    Validate('w_descricao', 'Objetivo/assunto/evento', '1', 1, 5, 2000, '1', '1');
    if ($w_cadgeral == 'S') {
      Validate('w_sq_unidade_resp', 'Unidade proponente', 'SELECT', 1, 1, 18, '', '0123456789');
    }
    //Validate('w_tipo_missao','Tipo da solicitação','SELECT',1,1,1,'1','');
    Validate('w_proponente', 'Contato na ausência', '1', 1, 2, 90, '1', '1');
    Validate('w_assunto', 'Agenda da solicitação', '1', '1', 5, 2000, '1', '1');
    ShowHTML('  if (theForm.w_diaria.selectedIndex==0 && (theForm.w_hospedagem[0].checked || theForm.w_veiculo[0].checked)) {');
    ShowHTML('     alert("Se houver despesa com hospedagem, é necessário informar a categoria das diárias!");');
    ShowHTML('     theForm.w_diaria.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    if ($O == 'I' && $w_cadgeral == 'S')
      Validate('w_sq_prop_nm', 'Beneficiário', 'HIDDEN', 1, 5, 100, '1', '1');
    if ($w_chave_pai > '' && $w_passagem == 'S') {
      if (count($RS_Financ) > 1) {
        Validate('w_rubrica', 'Rubrica', 'SELECT', 1, 1, 18, '', '1');
        Validate('w_lancamento', 'Tipo de lançamento', 'SELECT', 1, 1, 18, '', '1');
      }
    }
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad="this.focus();"');
  } elseif (strpos('EV', $O) !== false) {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_descricao.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV', $O) !== false) {
    if (strpos('EV', $O) !== false) {
      $w_Disabled = ' DISABLED ';
      if ($O == 'V')
        $w_Erro = Validacao($w_sq_solicitacao, $sg);
    }
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="' . $w_copia . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="' . f($RS_Menu, 'data_hora') . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_atual" value="' . $w_inicio_atual . '">');
    ShowHTML('<INPUT type="hidden" name="w_atividade_ant" value="' . $w_atividade_ant . '">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
    //ShowHTML('<INPUT type="hidden" name="w_sq_prop" value="'.$w_sq_prop.'">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4">Os dados deste bloco serão utilizados para identificação da solicitação, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('          <tr valign="top">');

    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, f($RS_Menu, 'sq_menu'), null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', 'S', 'S', 'S');
    if (Nvl($w_sq_menu_relac, '') != '') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac == 'CLASSIF') {
        SelecaoSolic('Classificação:', null, null, $w_cliente, $w_sqcc, $w_sq_menu_relac, null, 'w_sqcc', 'SIWSOLIC', null);
      } else {
        //if(f($RS_Relac,'sg_modulo')=='PR') {
        //SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_etapa\'; document.Form.submit();"');
        //ShowHTML('      <tr>');
        //SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_rubrica,$w_chave_pai,null,'w_rubrica','RUBRICAS',null);
        //ShowHTML('      </tr>');
        //} else {
        SelecaoSolic('Vinculação:', null, null, $w_cliente, $w_chave_pai, $w_sq_menu_relac, f($RS_Menu, 'sq_menu'), 'w_chave_pai', f($RS_Relac, 'sigla'), 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_chave_pai\'; document.Form.submit();"');
        if (f($RS_Relac, 'sg_modulo') == 'PR' && nvl($w_chave_pai, '') != '') {
          // Exibe saldos das rubricas
          $sql = new db_getPD_Financeiro; $RS_Fin = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, null, null, null, 'ORCAM_SIT');
          $RS_Fin = SortArray($RS_Fin, 'cd_rubrica', 'asc', 'nm_rubrica', 'asc', 'nm_lancamento', 'asc');
          ShowHTML('<tr><td colspan=3><b>Disponibilidade orçamentária:</b>');
          ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
          ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '" align="center">');
          ShowHTML('          <td><b>Rubrica</td>');
          ShowHTML('          <td><b>Descrição</td>');
          ShowHTML('          <td><b>% Executado</td>');
          ShowHTML('         <td><b>Saldo (R$)</td>');
          ShowHTML('        </tr>');
          if (count($RS_Fin) <= 0) {
            ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
          } else {
            $RS_Fin = array_slice($RS_Fin, (($P3 - 1) * $P4), $P4);
            foreach ($RS_Fin as $row) {
              $w_cor = $conTrBgColor;
              ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
              ShowHTML('        <td>' . Nvl(f($row, 'cd_rubrica'), '&nbsp;') . '&nbsp;' . Nvl(f($row, 'nm_rubrica'), '&nbsp;') . '</td>');
              ShowHTML('        <td>' . Nvl(f($row, 'descricao'), '&nbsp;') . '</td>');
              ShowHTML('        <td align="center">' . formatNumber(f($row, 'perc_exec')) . '</td>');
              ShowHTML('        <td align="center">' . formatNumber(f($row, 'saldo')) . '</td>');
              ShowHTML('      </tr>');
            }
          }
          ShowHTML('      </center>');
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
        }
        //}
      }
    }
    ShowHTML('      <tr><td colspan="4" valign="top"><b><u>O</u>bjetivo/assunto a ser tratado/evento:</b><br><textarea ' . $w_Disabled . ' accesskey="O" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva, de forma detalhada, os objetivos a serem atingidos.">' . $w_descricao . '</TEXTAREA></td>');
    ShowHTML('      <tr valign="top">');
    if ($w_sq_unidade_resp == '') {
      // Recupera todos os registros para a listagem
      $sql = new db_getUorgList; $RS = $sql->getInstanceOf($dbms, $w_cliente, $_SESSION['LOTACAO'], 'VIAGEMUNID', null, null, $w_ano);
      if (count($RS) > 0) {
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_sq_unidade_resp = f($RS, 'sq_unidade');
        if ($w_cadgeral == 'N') {
          ShowHTML('<INPUT type="hidden" name="w_sq_unidade_resp" value="' . $w_sq_unidade_resp . '">');
        } else {
          SelecaoUnidade('<U>U</U>nidade proponente:', 'U', 'Selecione a unidade proponente da solicitação', $w_sq_unidade_resp, null, 'w_sq_unidade_resp', 'VIAGEM', null);
        }
      } else {
        if ($w_cadgeral == 'N') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Sua lotação não está ligada a nenhuma unidade proponente. Entre em contato com os gestores do sistema!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } else {
          SelecaoUnidade('<U>U</U>nidade proponente:', 'U', 'Selecione a unidade proponente da solicitação', $w_sq_unidade_resp, null, 'w_sq_unidade_resp', 'VIAGEM', null);
        }
      }
    } else {
      if ($w_cadgeral == 'N') {
        ShowHTML('<INPUT type="hidden" name="w_sq_unidade_resp" value="' . $w_sq_unidade_resp . '">');
      } else {
        SelecaoUnidade('<U>U</U>nidade proponente:', 'U', 'Selecione a unidade proponente da solicitação', $w_sq_unidade_resp, null, 'w_sq_unidade_resp', 'VIAGEM', null);
      }
    }
    //SelecaoTipoPCD('Ti<u>p</u>o:','P',null,$w_tipo_missao,'w_tipo_missao',null,null);
    ShowHTML('<INPUT type="hidden" name="w_tipo_missao" value="I">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="' . nvl($w_inicio, formataDataEdicao(time())) . '">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="' . nvl($w_fim, formataDataEdicao(time())) . '">');
    if ($O == 'I' && $w_cadgeral == 'S') {
      ShowHTML('      <tr>');
      SelecaoPessoaOrigem('<u>B</u>eneficiário:', 'P', 'Clique na lupa para selecionar o beneficiário.', nvl($w_sq_prop, $_SESSION['SQ_PESSOA']), null, 'w_sq_prop', 'NF,EF', null, null, 1, 'w_email');
    }
    ShowHTML('      <tr><td><b>Contato na au<u>s</u>ência:</b><br><input ' . $w_Disabled . ' accesskey="S" type="text" name="w_proponente" class="sti" SIZE="60" MAXLENGTH="90" VALUE="' . $w_proponente . '" title="Indique pessoa para contato durante os dias de ausência."></td>');
    ShowHTML('      <tr><td colspan="4" valign="top"><b>A<u>g</u>enda:</b><br><textarea ' . $w_Disabled . ' accesskey="G" name="w_assunto" class="STI" ROWS=5 cols=75 title="Agenda das atividades durante todos os dias em que estiver ausente.">' . $w_assunto . '</TEXTAREA></td>');

    ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Despesas envolvidas com a solicitação</td></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4">Nas opções abaixo, marque "Sim" se esta solicitação necessitar do tipo de despesa.</td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4"><table border="0" width="100%">');
    ShowHTML('        <tr valign="top">');
    MontaRadioSN('<b>Bilhetes?</b>', $w_passagem, 'w_passagem', null, null, ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_passagem\'; document.Form.submit();"');
    SelecaoCategoriaDiaria('Categoria das d<u>i</u>árias:', 'I', 'Selecione a categoria das diárias.', $w_cliente, $w_diaria, null, 'w_diaria', 'S', null);
    MontaRadioSN('<b>Hospedagem?</b>', $w_hospedagem, 'w_hospedagem');
    MontaRadioNS('<b>Locação de veículo?</b>', $w_veiculo, 'w_veiculo');
    if ($w_chave_pai > '' && $w_passagem == 'S') {
      if (count($RS_Financ) > 1) {
        ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Dados para Pagamento dos Bilhetes</td></td></tr>');
        ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rubrica, $w_chave_pai, 'B', 'w_rubrica', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lancamento, null, $w_cliente, 'w_lancamento', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rubrica, 10, '0', STR_PAD_LEFT) . 'B', null, 3);
      } elseif (count($RS_Financ) == 1) {
        foreach ($RS_Financ as $row) {
          $RS_Financ = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_financeiro" value="' . f($RS_Financ, 'chave') . '">');
      }
    }
    ShowHTML('      </tr></table>');

    if ($O == 'W') {
      if ($w_cadgeral == 'S') {
        ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do Beneficiário</td></td></tr>');
        ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="4">Insira abaixo os dados do beneficiário. Após a gravação serão solicitados dados complementares sobre ele.</td></tr>');
        ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="4" valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('        <tr valign="top">');
        ShowHTML('            <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="' . $w_cpf . '" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);" onBlur="botoes(); document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_nm_prop\'; document.Form.submit();">');
        if ($w_sq_prop > '') {
          ShowHTML('            <td>Nome completo:<b><br>' . $w_nm_prop . '</td>');
          ShowHTML('            <td>Nome resumido:<b><br>' . $w_nm_prop_res . '</td>');
          if (Nvl($w_sexo, '') == '') {
            SelecaoSexo('Se<u>x</u>o:', 'X', null, $w_sexo, null, 'w_sexo', null, null);
          } else {
            ShowHTML('<INPUT type="hidden" name="w_sexo" value="' . $w_sexo . '">');
          }
          if (Nvl($w_vinculo, '') == '') {
            SelecaoVinculo('Tipo de <u>v</u>ínculo:', 'V', null, $w_vinculo, null, 'w_vinculo', 'S', 'Física', null);
          } else {
            ShowHTML('<INPUT type="hidden" name="w_vinculo" value="' . $w_vinculo . '">');
          }
        } else {
          ShowHTML('            <td><b><u>N</u>ome completo:</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="w_nm_prop" class="sti" SIZE="45" MAXLENGTH="60" VALUE="' . $w_nm_prop . '"></td>');
          ShowHTML('            <td><b><u>N</u>ome resumido:</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="w_nm_prop_res" class="sti" SIZE="15" MAXLENGTH="21" VALUE="' . $w_nm_prop_res . '"></td>');
          SelecaoSexo('Se<u>x</u>o:', 'X', null, $w_sexo, null, 'w_sexo', null, null);
          SelecaoVinculo('Tipo de <u>v</u>ínculo:', 'V', null, $w_vinculo, null, 'w_vinculo', 'S', 'Física', null);
        }
        ShowHTML('          </table>');
      } else {
        if ($w_sexo == 'N') {
          ShowHTML('<INPUT type="hidden" name="w_cpf" value="' . $w_cpf . '">');
          ShowHTML('<INPUT type="hidden" name="w_nm_prop" value="' . $w_nm_prop . '">');
          ShowHTML('<INPUT type="hidden" name="w_nm_prop_res" value="' . $w_nm_prop_res . '">');
          ShowHTML('<INPUT type="hidden" name="w_vinculo" value="' . $w_vinculo . '">');
          ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Dados do Beneficiário</td></td></tr>');
          ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="4">Confirme os dados abaixo, informando ou alterando o sexo, se necessário. Após a gravação serão solicitados dados complementares sobre ele.</td></tr>');
          ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="4" valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('        <tr valign="top">');
          ShowHTML('            <td>CPF:<b><br><font size="2">' . $w_cpf . '</b></td>');
          ShowHTML('            <td>Nome completo:<b><br><font size="2">' . $w_nm_prop . '</td>');
          ShowHTML('            <td>Nome resumido:<b><br><font size="2">' . $w_nm_prop_res . '</td>');
          SelecaoSexo('Se<u>x</u>o:', 'X', null, $w_sexo, null, 'w_sexo', null, null);
          ShowHTML('          </table>');
        } else {
          ShowHTML('<INPUT type="hidden" name="w_sexo" value="' . $w_sexo . '">');
        }
      }
    }
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="4">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O == 'I') {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms, $w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&w_copia=' . $w_copia . '&O=L&SG=' . f($RS, 'sigla') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '\';" name="Botao" value="Cancelar">');
    }
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de cadastramento da outra parte
// -------------------------------------------------------------------------
function OutraParte() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($O == '')
    $O = 'P';

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_cpf = $_REQUEST['w_cpf'];
  $w_cnpj = $_REQUEST['w_cnpj'];
  $w_sq_pessoa = nvl($_REQUEST['w_sq_pessoa'], $_REQUEST['w_sq_prop']);
  $w_pessoa_atual = $_REQUEST['w_pessoa_atual'];
  $w_tipo_pessoa = $_REQUEST['w_tipo_pessoa'];

  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);

  // Verifica se há necessidade de informar os dados para pagamento das diárias
  if (nvl(f($RS, 'diaria'), '') != '' || f($RS, 'hospedagem') != 'N' || f($RS, 'veiculo') != 'N') {
    $w_dados_pagamento = true;
  } else {
    $w_dados_pagamento = false;
  }
  if ($w_sq_pessoa == '' && strpos($_REQUEST['Botao'], 'Selecionar') === false) {
    $w_sq_pessoa = f($RS, 'sq_prop');
    $w_pessoa_atual = f($RS, 'sq_prop');
    $w_sq_forma_pag = f($RS, 'sq_forma_pagamento');
    $w_sq_banco = f($RS, 'sq_banco');
    $w_sq_agencia = f($RS, 'sq_agencia');
    $w_operacao = f($RS, 'operacao_conta');
    $w_nr_conta = f($RS, 'numero_conta');
    $w_sq_pais_estrang = f($RS, 'sq_pais_estrang');
    $w_aba_code = f($RS, 'aba_code');
    $w_swift_code = f($RS, 'swift_code');
    $w_endereco_estrang = f($RS, 'endereco_estrang');
    $w_banco_estrang = f($RS, 'banco_estrang');
    $w_agencia_estrang = f($RS, 'agencia_estrang');
    $w_cidade_estrang = f($RS, 'cidade_estrang');
    $w_informacoes = f($RS, 'informacoes');
    $w_codigo_deposito = f($RS, 'codigo_deposito');
    $w_sq_tipo_pessoa = f($RS, 'sq_tipo_pessoa');
  }
  if (Nvl($w_sq_pessoa, 0) == 0)
    $O = 'I'; else
    $O='A';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca > '') {
    // Se for recarga da página
    $w_chave = $_REQUEST['w_chave'];
    $w_chave_aux = $_REQUEST['w_chave_aux'];
    $w_nome = $_REQUEST['w_nome'];
    $w_nome_resumido = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_forma_pag = $_REQUEST['w_sq_forma_pag'];
    $w_sq_banco = $_REQUEST['w_sq_banco'];
    $w_sq_agencia = $_REQUEST['w_sq_agencia'];
    $w_operacao = $_REQUEST['w_operacao'];
    $w_nr_conta = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code = $_REQUEST['w_aba_code'];
    $w_swift_code = $_REQUEST['w_swift_code'];
    $w_endereco_estrang = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang = $_REQUEST['w_cidade_estrang'];
    $w_informacoes = $_REQUEST['w_informacoes'];
    $w_codigo_deposito = $_REQUEST['w_codigo_deposito'];
    $w_interno = $_REQUEST['w_interno'];
    $w_vinculo_ativo = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd = $_REQUEST['w_ddd'];
    $w_nr_telefone = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax = $_REQUEST['w_nr_fax'];
    $w_email = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro = $_REQUEST['w_logradouro'];
    $w_complemento = $_REQUEST['w_complemento'];
    $w_bairro = $_REQUEST['w_bairro'];
    $w_cep = $_REQUEST['w_cep'];
    $w_sq_cidade = $_REQUEST['w_sq_cidade'];
    $w_co_uf = $_REQUEST['w_co_uf'];
    $w_sq_pais = $_REQUEST['w_sq_pais'];
    $w_pd_pais = $_REQUEST['w_pd_pais'];
    $w_cpf = $_REQUEST['w_cpf'];
    $w_nascimento = $_REQUEST['w_nascimento'];
    $w_rg_numero = $_REQUEST['w_rg_numero'];
    $w_rg_emissor = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao = $_REQUEST['w_rg_emissao'];
    $w_passaporte = $_REQUEST['w_passaporte'];
    $w_sq_pais_passaporte = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo = $_REQUEST['w_sexo'];
    $w_cnpj = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual = $_REQUEST['w_inscricao_estadual'];
  } else {
    if (strpos($_REQUEST['Botao'], 'Alterar') === false && strpos($_REQUEST['Botao'], 'Procurar') === false && ($O == 'A' || $w_sq_pessoa > '' || $w_cpf > '' || $w_cnpj > '')) {
      // Recupera os dados do beneficiário em co_pessoa
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_sq_pessoa, null, $w_cpf, $w_cnpj, null, null, null, null, null, null, null, null, null, null, null, null, null);
      if (count($RS) > 0) {
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_sq_pessoa = f($RS, 'sq_pessoa');
        $w_tipo_pessoa = f($row, 'sq_tipo_pessoa');
        $w_nome = f($RS, 'nm_pessoa');
        $w_nome_resumido = f($RS, 'nome_resumido');
        $w_sq_pessoa_pai = f($RS, 'sq_pessoa_pai');
        $w_nm_tipo_pessoa = f($RS, 'nm_tipo_pessoa');
        $w_sq_tipo_vinculo = f($RS, 'sq_tipo_vinculo');
        $w_nm_tipo_vinculo = f($RS, 'nm_tipo_vinculo');
        $w_interno = f($RS, 'interno');
        $w_vinculo_ativo = f($RS, 'vinculo_ativo');
        $w_sq_pessoa_telefone = f($RS, 'sq_pessoa_telefone');
        $w_ddd = f($RS, 'ddd');
        $w_nr_telefone = f($RS, 'nr_telefone');
        $w_sq_pessoa_celular = f($RS, 'sq_pessoa_celular');
        $w_nr_celular = f($RS, 'nr_celular');
        $w_sq_pessoa_fax = f($RS, 'sq_pessoa_fax');
        $w_nr_fax = f($RS, 'nr_fax');
        $w_email = f($RS, 'email');
        $w_sq_pessoa_endereco = f($RS, 'sq_pessoa_endereco');
        $w_logradouro = f($RS, 'logradouro');
        $w_complemento = f($RS, 'complemento');
        $w_bairro = f($RS, 'bairro');
        $w_cep = f($RS, 'cep');
        $w_sq_cidade = f($RS, 'sq_cidade');
        $w_co_uf = f($RS, 'co_uf');
        $w_sq_pais = f($RS, 'sq_pais');
        $w_pd_pais = f($RS, 'pd_pais');
        $w_cpf = f($RS, 'cpf');
        $w_nascimento = FormataDataEdicao(f($RS, 'nascimento'));
        $w_rg_numero = f($RS, 'rg_numero');
        $w_rg_emissor = f($RS, 'rg_emissor');
        $w_rg_emissao = FormataDataEdicao(f($RS, 'rg_emissao'));
        $w_passaporte = f($RS, 'passaporte_numero');
        $w_sq_pais_passaporte = f($RS, 'sq_pais_passaporte');
        $w_sexo = f($RS, 'sexo');
        $w_cnpj = f($RS, 'cnpj');
        $w_inscricao_estadual = f($RS, 'inscricao_estadual');
      }
    }
  }

  // Recupera a sigla da forma de pagamento e, se necessário, recupera a conta padrão do beneficiário
  if (nvl($w_sq_forma_pag, '') != '') {
    $sql = new db_getFormaPagamento; $RS_Forma_Pag = $sql->getInstanceOf($dbms, $w_cliente, $w_sq_forma_pag, null, 'REGISTRO', null, null);
    foreach ($RS_Forma_Pag as $row) {
      $RS_Forma_Pag = $row;
      break;
    }
    $w_forma_pagamento = f($RS_Forma_Pag, 'sigla');

    if (strpos('CREDITO,DEPOSITO', $w_forma_pagamento) !== false) {

      if (Nvl($w_sq_banco, '') == '' && Nvl($w_nr_conta, '') == '') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_sq_pessoa, null, $w_cpf, $w_cnpj, null, null, null, null, null, null, null, null, null, null, null, null, null);
        if (count($RS) > 0) {

          foreach ($RS as $row) {
            $RS = $row;
            break;
          }
          if (Nvl(f($RS, 'nr_conta'), '') != '') {
            $w_sq_banco = f($RS, 'sq_banco');
            $w_sq_agencia = f($RS, 'sq_agencia');
            $w_operacao = f($RS, 'operacao');
            $w_nr_conta = f($RS, 'nr_conta');
          }
        }
      }
    }
  }


  // Recupera informação do campo operação do banco selecionado
  if (nvl($w_sq_banco, '') > '') {
    $sql = new db_getBankData; $RS_Banco = $sql->getInstanceOf($dbms, $w_sq_banco);
    $w_exige_operacao = f($RS_Banco, 'exige_operacao');
  }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (($w_sq_pessoa == '' && $w_cpf == '' && $w_cnpj == '' && $w_passaporte == '') || strpos($_REQUEST['Botao'], 'Procurar') !== false || strpos($_REQUEST['Botao'], 'Alterar') !== false) {
    // Se o beneficiário ainda não foi selecionado
    /* ShowHTML('  if (theForm.Botao.value == "Procurar") {');
      Validate('w_nome', 'Nome', '', '1', '4', '20', '1', '');
      ShowHTML('  theForm.Botao.value = "Procurar";');
      ShowHTML('}');
      ShowHTML('else {');
      Validate('w_cpf', 'CPF', 'CPF', '1', '14', '14', '', '0123456789-.');
      ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
      ShowHTML('}'); */
  } elseif ($O == 'I' || $O == 'A') {
    ShowHTML('  if (theForm.Botao.value.indexOf(\'Alterar\') >= 0) { return true; }');
    Validate('w_nome', 'Nome', '1', 1, 5, 60, '1', '1');
    Validate('w_nome_resumido', 'Nome resumido', '1', 1, 2, 21, '1', '1');
    Validate('w_sexo', 'Sexo', 'SELECT', 1, 1, 1, 'MF', '');
    if (nvl($w_cpf,'') == '') {
      Validate('w_cpf', 'CPF', 'CPF', '1', '14', '14', '', '0123456789-.');
    }
    if ($w_sq_tipo_vinculo == '') {
      Validate('w_sq_tipo_vinculo', 'Tipo de vínculo', 'SELECT', 1, 1, 18, '', '1');
    }
    if ($w_tipo_pessoa == 1) {
      Validate('w_rg_numero', 'Identidade', '1', 1, 2, 30, '1', '1');
      Validate('w_rg_emissao', 'Data de emissão', 'DATA', '', 10, 10, '', '0123456789/');
      Validate('w_rg_emissor', 'Órgão expedidor', '1', 1, 2, 30, '1', '1');
    } else {
      Validate('w_rg_numero', 'Identidade', '1', '', 2, 30, '1', '1');
      Validate('w_rg_emissao', 'Data de emissão', 'DATA', '', 10, 10, '', '0123456789/');
      Validate('w_rg_emissor', 'Órgão expedidor', '1', '', 2, 30, '1', '1');
      ShowHTML('  if ((theForm.w_rg_numero.value+theForm.w_rg_emissao.value+theForm.w_rg_emissor.value)!="" && (theForm.w_rg_numero.value=="" || theForm.w_rg_emissor.value=="")) {');
      ShowHTML('     alert(\'Os campos identidade, data de emissão e órgão emissor devem ser informados em conjunto!\\nDos três, apenas a data de emissão é opcional.\');');
      ShowHTML('     theForm.w_rg_numero.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
    }
    if ($w_tipo_pessoa == 1) {
      Validate('w_passaporte', 'Passaporte', '1', '', 1, 20, '1', '1');
      Validate('w_sq_pais_passaporte', 'País emissor', 'SELECT', '', 1, 10, '1', '1');
      ShowHTML('  if ((theForm.w_passaporte.value+theForm.w_sq_pais_passaporte[theForm.w_sq_pais_passaporte.selectedIndex].value)!="" && (theForm.w_passaporte.value=="" || theForm.w_sq_pais_passaporte.selectedIndex==0)) {');
      ShowHTML('     alert(\'Os campos passaporte e país emissor devem ser informados em conjunto!\');');
      ShowHTML('     theForm.w_passaporte.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
    } else {
      Validate('w_passaporte', 'Passaporte', '1', '1', 1, 20, '1', '1');
      Validate('w_sq_pais_passaporte', 'País emissor', 'SELECT', '1', 1, 10, '1', '1');
    }
    if ($w_tipo_pessoa == 1) {
      Validate('w_ddd', 'DDD', '1', '1', 2, 4, '', '0123456789');
      Validate('w_nr_telefone', 'Telefone', '1', 1, 7, 25, '1', '1');
      Validate('w_nr_fax', 'Fax', '1', '', 7, 25, '1', '1');
      Validate('w_nr_celular', 'Celular', '1', '', 7, 25, '1', '1');
    } else {
      Validate('w_ddd', 'DDD', '1', '', 2, 4, '', '0123456789');
      Validate('w_nr_telefone', 'Telefone', '1', '', 7, 25, '1', '1');
      Validate('w_nr_fax', 'Fax', '1', '', 7, 25, '1', '1');
      Validate('w_nr_celular', 'Celular', '1', '', 7, 25, '1', '1');
      ShowHTML('  if ((theForm.w_nr_telefone.value+theForm.w_nr_fax.value+theForm.w_nr_celular.value)!="" && theForm.w_ddd.value=="") {');
      ShowHTML('     alert(\'O campo DDD é obrigatório quando informar telefone, fax ou celular!\');');
      ShowHTML('     theForm.w_ddd.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_ddd.value!="" && theForm.w_nr_telefone.value=="") {');
      ShowHTML('     alert(\'Se informar o DDD, então informe obrigatoriamente o telefone!\\nFax e celular são opcionais.\');');
      ShowHTML('     theForm.w_nr_telefone.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
    }
    Validate('w_logradouro', 'Endereço', '1', '', 4, 60, '1', '1');
    Validate('w_complemento', 'Complemento', '1', '', 2, 20, '1', '1');
    Validate('w_bairro', 'Bairro', '1', '', 2, 30, '1', '1');
    Validate('w_sq_pais', 'País', 'SELECT', '1', 1, 10, '1', '1');
    Validate('w_co_uf', 'UF', 'SELECT', '1', 1, 10, '1', '1');
    Validate('w_sq_cidade', 'Cidade', 'SELECT', '1', 1, 10, '', '1');
    if (Nvl($w_pd_pais, 'S') == 'S') {
      Validate('w_cep', 'CEP', '1', '', 9, 9, '', '0123456789-');
    } else {
      Validate('w_cep', 'CEP', '1', '', 5, 9, '', '0123456789');
    }
    ShowHTML('  if (theForm.w_ddd.value!="" && (theForm.w_sq_pais.value=="" || theForm.w_co_uf.value=="" || theForm.w_sq_cidade.value=="")) {');
    ShowHTML('     alert(\'Se informar telefone, fax ou celular, então informe o país, estado e cidade!\');');
    ShowHTML('     theForm.w_sq_pais.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  if ((theForm.w_complemento.value+theForm.w_bairro.value+theForm.w_cep.value)!="" && theForm.w_logradouro.value=="") {');
    ShowHTML('     alert(\'O campo logradouro é obrigatório quando informar os campos complemento, bairro ou CEP!\');');
    ShowHTML('     theForm.w_logradouro.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  if (theForm.w_logradouro.value!="" && theForm.w_cep.value=="") {');
    ShowHTML('     alert(\'O campo CEP é obrigatório quando informar o endereço da pessoa!\');');
    ShowHTML('     theForm.w_cep.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('w_email', 'E-Mail', '1', '1', 4, 60, '1', '1');
    ShowHTML('  if ((theForm.w_ddd.value+theForm.w_logradouro.value+theForm.w_email.value)!="" && (theForm.w_sq_pais.value=="" || theForm.w_co_uf.value=="" || theForm.w_sq_cidade.value=="")) {');
    ShowHTML('     alert(\'Se informar algum telefone, o endereço ou o e-mail da pessoa, então informe o país, estado e cidade!\');');
    ShowHTML('     theForm.w_sq_pais.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    if ($w_dados_pagamento) {
      Validate('w_sq_forma_pag', 'Forma de recebimento', 'SELECT', 1, 1, 18, '', '0123456789');
      if (strpos('CREDITO,DEPOSITO', $w_forma_pagamento) !== false) {
        Validate('w_sq_banco', 'Banco', 'SELECT', 1, 1, 10, '1', '1');
        Validate('w_sq_agencia', 'Agencia', 'SELECT', 1, 1, 10, '1', '1');
        if ($w_exige_operacao == 'S')
          Validate('w_operacao', 'Operação', '1', '1', 1, 6, '', '0123456789');
        Validate('w_nr_conta', 'Número da conta', '1', '1', 2, 30, 'ZXAzxa', '0123456789-');
      } elseif ($w_forma_pagamento == 'ORDEM') {
        Validate('w_sq_banco', 'Banco', 'SELECT', 1, 1, 10, '1', '1');
        Validate('w_sq_agencia', 'Agencia', 'SELECT', 1, 1, 10, '1', '1');
      } elseif ($w_forma_pagamento == 'EXTERIOR') {
        Validate('w_banco_estrang', 'Banco de destino', '1', '1', 1, 60, 1, 1);
        Validate('w_aba_code', 'Código ABA', '1', '', 1, 12, 1, 1);
        Validate('w_swift_code', 'Código SWIFT', '1', '', 1, 30, '', 1);
        Validate('w_endereco_estrang', 'Endereço da agência destino', '1', '', 3, 100, 1, 1);
        ShowHTML('  if (theForm.w_aba_code.value == \'\' && theForm.w_swift_code.value == \'\' && theForm.w_endereco_estrang.value == \'\') {');
        ShowHTML('     alert(\'Informe código ABA, código SWIFT ou endereço da agência!\');');
        ShowHTML('     document.Form.w_aba_code.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('w_agencia_estrang', 'Nome da agência destino', '1', '1', 1, 60, 1, 1);
        Validate('w_nr_conta', 'Número da conta', '1', 1, 1, 10, 1, 1);
        Validate('w_cidade_estrang', 'Cidade da agência', '1', '1', 1, 60, 1, 1);
        Validate('w_sq_pais_estrang', 'País da agência', 'SELECT', '1', 1, 18, 1, 1);
        Validate('w_informacoes', 'Informações adicionais', '1', '', 5, 200, 1, 1);
      }
    }
    if ($w_cadgeral == 'S') {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if (($w_sq_pessoa == '' && $w_cpf == '' && $w_cnpj == '' && $w_passaporte == '') || strpos($_REQUEST['Botao'], 'Alterar') !== false || strpos($_REQUEST['Botao'], 'Procurar') !== false) {
    // Se o beneficiário ainda não foi selecionado
    if (strpos($_REQUEST['Botao'], 'Procurar') !== false) {
      // Se está sendo feita busca por nome
      BodyOpenClean('onLoad="this.focus();"');
    } else {
      BodyOpenClean('onLoad="this.focus();"');
    }
  } elseif ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpenClean('onLoad="document.Form.w_nome.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IA', $O) !== false) {
    if (($w_sq_pessoa == '' && $w_cpf == '' && $w_cnpj == '' && $w_passaporte == '') || strpos($_REQUEST['Botao'], 'Alterar') !== false || strpos($_REQUEST['Botao'], 'Procurar') !== false) {
      // Se o beneficiário ainda não foi selecionado
      ShowHTML('<FORM action="' . $w_dir . $w_pagina . $par . '" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    }
    ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
    ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
    ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
    ShowHTML('<INPUT type="hidden" name="P4" value="' . $P4 . '">');
    ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
    ShowHTML('<INPUT type="hidden" name="SG" value="' . $SG . '">');
    ShowHTML('<INPUT type="hidden" name="R" value="' . $w_pagina . $par . '">');
    ShowHTML('<INPUT type="hidden" name="O" value="' . $O . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_cliente . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="' . $w_sq_pessoa . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="' . $w_tipo_pessoa . '">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="' . $w_pessoa_atual . '">');
    if (($w_sq_pessoa == '' && $w_cpf == '' && $w_cnpj == '' && $w_passaporte == '') || strpos($_REQUEST['Botao'], 'Alterar') !== false || strpos($_REQUEST['Botao'], 'Procurar') !== false) {
      $w_nome = $_REQUEST['w_nome'];
      if (strpos($_REQUEST['Botao'], 'Alterar') !== false) {
        $w_cpf = '';
        $w_cnpj = '';
        $w_nome = '';
      }
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('    <tr>');
      //SelecaoPessoaOrigem('<u>B</u>eneficiário:', 'P', 'Clique na lupa para selecionar o beneficiário.', nvl($w_sq_prop, $_SESSION['SQ_PESSOA']), null, 'w_sq_prop', 'NF,EF', null, 'onFocus="alert(document.Form.w_sq_prop.value);"', 1, 'w_email');
      AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
      ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="">');
      //SelecaoPessoaOrigem('<u>B</u>eneficiário:', 'P', 'Clique na lupa para selecionar o beneficiário.', nvl($w_sq_pessoa, $_SESSION['SQ_PESSOA']), null, 'w_sq_prop', 'NF,EF', null, 'onFocus="javascript:location.href=\'' . $w_dir . $w_pagina . $par . '&R=' . $R . '&w_sq_pessoa=\'+document.Form.w_sq_prop.value+\'' . '&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&Botao=Selecionar\'";"', 1, 'w_sq_prop');
      SelecaoPessoaOrigem('<u>B</u>eneficiário:', 'P', 'Clique na lupa para selecionar o beneficiário.', nvl($w_sq_pessoa, $_SESSION['SQ_PESSOA']), null, 'w_sq_prop', 'NF,EF', null, 'onFocus="javascript:document.Form.submit();"', 1, 'w_sq_prop');
      ShowHTML('</form>');
      //SelecaoPessoaOrigem('<u>B</u>eneficiário:', 'P', 'Clique na lupa para selecionar o beneficiário.', nvl($w_sq_prop, $_SESSION['SQ_PESSOA']), null, 'w_sq_prop', 'NF,EF', null, 'onFocus="alert(\"' . $w_dir . $w_pagina . $par . '&R=' . $R . '&O=A&w_cpf=' . f($row, 'cpf') . '&w_sq_pessoa=' . f($row, 'sq_pessoa') . '&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&Botao=Selecionar")"', 1, 'w_email');
      /*      ShowHTML('        <tr><td colspan=4>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
        ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="' . $w_cpf . '" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
        ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'">');
        ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
        ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
        ShowHTML('        <tr><td colspan=4>');
        ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="' . $w_nome . '" SIZE="20" MaxLength="20">');
        ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'">'); */
      ShowHTML('      </table>');
      if ($w_nome > '') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, $w_nome, 1, null, null, null, null, null, null, null, null, null, null, null);
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
        ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Nome resumido</td>');
        ShowHTML('          <td><b>CPF</td>');
        ShowHTML('          <td><b>Operações</td>');
        ShowHTML('        </tr>');
        if (count($RS) <= 0) {
          ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=4 align="center"><b>Não há pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach ($RS as $row) {
            ShowHTML('      <tr bgcolor="' . $conTrBgColor . '" valign="top">');
            ShowHTML('        <td>' . f($row, 'nm_pessoa') . '</td>');
            ShowHTML('        <td>' . f($row, 'nome_resumido') . '</td>');
            ShowHTML('        <td align="center">' . Nvl(f($row, 'cpf'), '---') . '</td>');
            ShowHTML('        <td nowrap>');
            ShowHTML('          <A class="hl" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $R . '&O=A&w_cpf=' . f($row, 'cpf') . '&w_sq_pessoa=' . f($row, 'sq_pessoa') . '&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&Botao=Selecionar">Selecionar</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }
        }
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      }
    } else {
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      if ($w_tipo_pessoa == 1)
        ShowHTML('          <td>CPF:<br><b><font size=2>' . $w_cpf);

      //ShowHTML('              <INPUT type="hidden" name="w_cpf" value="' . $w_cpf . '">');
      if (nvl($w_cpf, '') == '') {
        ShowHTML('              <INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="' . $w_cpf . '" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      }else{
        ShowHTML('              <INPUT type="hidden" name="w_cpf" value="' . $w_cpf . '">');
      }
      ShowHTML('          <tr valign="top">');
      if (strpos('AE', $O) !== false) {
        $readonly = ' readonly ';
      } else {
        $readonly = '';
      }
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input ' . $w_Disabled . $readonly . ' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="' . $w_nome . '"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input ' . $w_Disabled . $readonly . ' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="' . $w_nome_resumido . '"></td>');
      SelecaoSexo('Se<u>x</u>o:', 'X', null, $w_sexo, null, 'w_sexo', null, null);
      if (Nvl($w_sq_tipo_vinculo, '') == '') {
        SelecaoVinculo('Tipo de <u>v</u>ínculo:', 'V', null, $w_sq_tipo_vinculo, null, 'w_sq_tipo_vinculo', 'S', 'Física', null);
      } else {
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_vinculo" value="' . $w_sq_tipo_vinculo . '">');
      }
      ShowHTML('          <tr valign="top">');
      ShowHTML('            <td><b><u>I</u>dentidade:</b><br><input ' . $w_Disabled . ' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="' . $w_rg_numero . '"></td>');
      ShowHTML('            <td><b>Data de <u>e</u>missão:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_rg_emissao . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('            <td><b>Ór<u>g</u>ão emissor:</b><br><input ' . $w_Disabled . ' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_rg_emissor . '"></td>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('            <td><b>Passapo<u>r</u>te:</b><br><input ' . $w_Disabled . ' accesskey="R" type="text" name="w_passaporte" class="sti" SIZE="15" MAXLENGTH="15" VALUE="' . $w_passaporte . '"></td>');
      SelecaoPais('<u>P</u>aís emissor do passaporte:', 'P', null, $w_sq_pais_passaporte, null, 'w_sq_pais_passaporte', null, null);
      ShowHTML('          </table>');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Telefones</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="' . $w_ddd . '"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input ' . $w_Disabled . ' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="' . $w_nr_telefone . '"> ' . consultaTelefone($w_cliente) . '</td>');
      ShowHTML('          <td title="Se a outra parte informar um número de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input ' . $w_Disabled . ' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_nr_fax . '"></td>');
      ShowHTML('          <td title="Se a outra parte informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_nr_celular . '"></td>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td colspan=2><b>En<u>d</u>ereço:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_logradouro" class="sti" SIZE="50" MAXLENGTH="50" VALUE="' . $w_logradouro . '"></td>');
      ShowHTML('          <td><b>C<u>o</u>mplemento:</b><br><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_complemento" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_complemento . '"></td>');
      ShowHTML('          <td><b><u>B</u>airro:</b><br><input ' . $w_Disabled . ' accesskey="B" type="text" name="w_bairro" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_bairro . '"></td>');
      ShowHTML('          <tr valign="top">');
      SelecaoPais('<u>P</u>aís:', 'P', null, $w_sq_pais, null, 'w_sq_pais', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
      ShowHTML('          <td>');
      SelecaoEstado('E<u>s</u>tado:', 'S', null, $w_co_uf, $w_sq_pais, null, 'w_co_uf', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:', 'C', null, $w_sq_cidade, $w_sq_pais, $w_co_uf, 'w_sq_cidade', null, null);
      ShowHTML('          <tr valign="top">');
      if (Nvl($w_pd_pais, 'S') == 'S') {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="' . $w_cep . '" onKeyDown="FormataCEP(this,event);"></td>');
      } else {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="' . $w_cep . '"></td>');
      }
      ShowHTML('              <td colspan=3 title="Se informar um e-mail institucional, informe-o neste campo."><b>e-<u>M</u>ail:</b><br><input ' . $w_Disabled . ' accesskey="M" type="text" name="w_email" class="sti" SIZE="50" MAXLENGTH="60" VALUE="' . $w_email . '"></td>');
      ShowHTML('          </table>');
      if ($w_dados_pagamento) {
        ShowHTML('      <tr valign="top">');
        ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados para pagamento das diárias</td></td></tr>');
        ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
        SelecaoFormaPagamento('<u>F</u>orma de recebimento:', 'F', 'Selecione na lista a forma de recebimento desejada.', $w_sq_forma_pag, f($RS_Menu, 'sigla'), 'w_sq_forma_pag', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_sq_forma_pag\'; document.Form.submit();"', 2);
        if (strpos('CREDITO,DEPOSITO', $w_forma_pagamento) !== false) {
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          SelecaoBanco('<u>B</u>anco:', 'B', 'Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.', $w_sq_banco, null, 'w_sq_banco', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>ência:', 'A', 'Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.', $w_sq_agencia, Nvl($w_sq_banco, -1), 'w_sq_agencia', null, null);
          ShowHTML('      <tr valign="top">');
          if ($w_exige_operacao == 'S')
            ShowHTML('          <td title="Alguns bancos trabalham com o campo "Operação", além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco."><b>O<u>p</u>eração:</b><br><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="' . $w_operacao . '"></td>');
          ShowHTML('          <td title="Informe o número da conta bancária, colocando o dígito verificador, se existir, separado por um hífen. Exemplo: 11214-3. Se o banco não trabalhar com dígito verificador, informe apenas números. Exemplo: 10845550."><b>Número da con<u>t</u>a:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_nr_conta . '"></td>');
          ShowHTML('          </table>');
        } elseif ($w_forma_pagamento == 'ORDEM') {
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          SelecaoBanco('<u>B</u>anco:', 'B', 'Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.', $w_sq_banco, null, 'w_sq_banco', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
          SelecaoAgencia('A<u>g</u>ência:', 'A', 'Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.', $w_sq_agencia, Nvl($w_sq_banco, -1), 'w_sq_agencia', null, null);
        } elseif ($w_forma_pagamento == 'EXTERIOR') {
          ShowHTML('      <tr><td colspan="2"><b><font color="#BC3131">ATENÇÃO:</b> É obrigatório o preenchimento de um destes campos: Swift Code, ABA Code ou Endereço da Agência.</font></td></tr>');
          ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td title="Banco onde o crédito deve ser efetuado."><b><u>B</u>anco de crédito:</b><br><input ' . $w_Disabled . ' accesskey="B" type="text" name="w_banco_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="' . $w_banco_estrang . '"></td>');
          ShowHTML('          <td title="Código ABA da agência destino."><b>A<u>B</u>A code:</b><br><input ' . $w_Disabled . ' accesskey="B" type="text" name="w_aba_code" class="sti" SIZE="12" MAXLENGTH="12" VALUE="' . $w_aba_code . '"></td>');
          ShowHTML('          <td title="Código SWIFT da agência destino."><b>S<u>W</u>IFT code:</b><br><input ' . $w_Disabled . ' accesskey="W" type="text" name="w_swift_code" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_swift_code . '"></td>');
          ShowHTML('      <tr><td colspan=3 title="Endereço da agência."><b>E<u>n</u>dereço da agência:</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="w_endereco_estrang" class="sti" SIZE="80" MAXLENGTH="100" VALUE="' . $w_endereco_estrang . '"></td>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td colspan=2 title="Nome da agência destino."><b>Nome da a<u>g</u>ência:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_agencia_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="' . $w_agencia_estrang . '"></td>');
          ShowHTML('          <td title="Número da conta destino."><b>Número da con<u>t</u>a:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_nr_conta . '"></td>');
          ShowHTML('      <tr valign="top">');
          ShowHTML('          <td colspan=2 title="Cidade da agência destino."><b><u>C</u>idade:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_cidade_estrang" class="sti" SIZE="40" MAXLENGTH="60" VALUE="' . $w_cidade_estrang . '"></td>');
          SelecaoPais('<u>P</u>aís:', 'P', 'Selecione o país de destino', $w_sq_pais_estrang, null, 'w_sq_pais_estrang', null, null);
          ShowHTML('          </table>');
          ShowHTML('      <tr><td colspan=2 title="Se necessário, escreva informações adicionais relevantes para o pagamento."><b>Info<u>r</u>mações adicionais:</b><br><textarea ' . $w_Disabled . ' accesskey="R" name="w_informacoes" class="sti" ROWS=3 cols=75 >' . $w_informacoes . '</TEXTAREA></td>');
        }
      }
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      if ($w_cadgeral == 'S') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Alterar beneficiário" onClick="Botao.value=this.value; document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.submit();">');
      }
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    }
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de cadastramento da trechos
// -------------------------------------------------------------------------
function Trechos() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitação e do cliente
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, $SG);
  $w_cidade_padrao = f($RS_Cliente, 'sq_cidade_padrao');

  if ($P1 == 1 || strpos($R, 'ALTSOLIC') !== false)
    $w_tipo_reg = 'S'; else
    $w_tipo_reg = 'P';

  if ($w_troca > '') {
    // Se for recarga da página
    $w_pais_orig = $_REQUEST['w_pais_orig'];
    $w_uf_orig = $_REQUEST['w_uf_orig'];
    $w_cidade_orig = $_REQUEST['w_cidade_orig'];
    $w_pais_dest = $_REQUEST['w_pais_dest'];
    $w_uf_dest = $_REQUEST['w_uf_dest'];
    $w_cidade_dest = $_REQUEST['w_cidade_dest'];
    $w_data_saida = $_REQUEST['w_data_saida'];
    $w_hora_saida = $_REQUEST['w_hora_saida'];
    $w_data_chegada = $_REQUEST['w_data_chegada'];
    $w_hora_chegada = $_REQUEST['w_hora_chegada'];
    $w_passagem = $_REQUEST['w_passagem'];
    $w_meio_transp = $_REQUEST['w_meio_transp'];
    $w_valor_trecho = $_REQUEST['w_valor_trecho'];
    $w_cia_aerea = $_REQUEST['w_cia_area'];
    $w_codigo_voo = $_REQUEST['w_codigo_voo'];
    $w_compromisso = $_REQUEST['w_compromisso'];
    $w_aero_orig = $_REQUEST['w_aero_orig'];
    $w_aero_dest = $_REQUEST['w_aero_dest'];
  } elseif ($O == 'L') {
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  } elseif (strpos('AE', $O) !== false) {
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, $w_tipo_reg, $SG);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_pais_orig = f($RS, 'pais_orig');
    $w_uf_orig = f($RS, 'uf_orig');
    $w_cidade_orig = f($RS, 'cidade_orig');
    $w_pais_dest = f($RS, 'pais_dest');
    $w_uf_dest = f($RS, 'uf_dest');
    $w_cidade_dest = f($RS, 'cidade_dest');
    $w_data_saida = FormataDataEdicao(f($RS, 'phpdt_saida'));
    $w_hora_saida = substr(FormataDataEdicao(f($RS, 'phpdt_saida'), 2), 0, 5);
    $w_data_chegada = FormataDataEdicao(f($RS, 'phpdt_chegada'));
    $w_hora_chegada = substr(FormataDataEdicao(f($RS, 'phpdt_chegada'), 2), 0, 5);
    $w_passagem = f($RS, 'passagem');
    $w_meio_transp = f($RS, 'sq_meio_transporte');
    $w_valor_trecho = formatNumber(f($RS, 'valor_trecho'));
    $w_cia_aerea = f($RS, 'sq_cia_transporte');
    $w_codigo_voo = f($RS, 'codigo_voo');
    $w_compromisso = f($RS, 'compromisso');
    $w_aero_orig = f($RS, 'aeroporto_origem');
    $w_aero_dest = f($RS, 'aeroporto_destino');
  }
  if ($O == 'I') {
    if ($w_pais_orig == '') {
      $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
      $RS1 = SortArray($RS1, 'phpdt_saida', 'desc', 'phpdt_chegada', 'desc');
      if (count($RS1) == 0) {
        // Carrega os valores padrão para país, estado e cidade
        $w_pais_orig = f($RS_Cliente, 'sq_pais');
        $w_uf_orig = f($RS_Cliente, 'co_uf');
        $w_cidade_orig = f($RS_Cliente, 'sq_cidade_padrao');
        $w_pais_dest = f($RS_Cliente, 'sq_pais');
      } else {
        foreach ($RS1 as $row) {
          $RS1 = $row;
          break;
        }
        // Carrega os valores da última saída
        $w_pais_orig = f($RS1, 'pais_dest');
        $w_uf_orig = f($RS1, 'uf_dest');
        $w_cidade_orig = f($RS1, 'cidade_dest');
        $w_pais_dest = f($RS1, 'pais_dest');
      }
    }
  }

  $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
  $RS1 = SortArray($RS1, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  $w_tot_trechos = count($RS1);
  if (count($RS1)) {
    foreach ($RS1 as $row) {
      if (count($RS1 == 1) && f($row, 'sq_deslocamento') == $w_chave_aux) {
        $w_tot_trechos = 0;
      } else {
        $w_cidade_padrao = f($row, 'cidade_orig');
      }
      break;
    }
  }

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    Validate('w_pais_orig', 'País de origem', 'SELECT', 1, 1, 18, '', '1');
    Validate('w_uf_orig', 'UF de origem', 'SELECT', 1, 1, 2, '1', '');
    Validate('w_cidade_orig', 'Cidade de origem', 'SELECT', 1, 1, 18, '', '1');
    Validate('w_aero_orig', 'Aeroporto de origem', '', '', 1, 20, '1', '1');
    Validate('w_data_saida', 'Data de saída', 'DATA', '1', 10, 10, '', '0123456789/');
    Validate('w_hora_saida', 'Hora de saída', 'HORA', '1', 5, 5, '', '0123456789:');
    Validate('w_pais_dest', 'País de destino', 'SELECT', 1, 1, 18, '', '1');
    Validate('w_uf_dest', 'UF de destino', 'SELECT', 1, 1, 2, '1', '');
    Validate('w_cidade_dest', 'Cidade de destino', 'SELECT', 1, 1, 18, '', '1');
    Validate('w_aero_dest', 'Aeroporto de destino', '', '', 1, 20, '1', '1');
    Validate('w_data_chegada', 'Data de chegada', 'DATA', '1', 10, 10, '', '0123456789/');
    Validate('w_hora_chegada', 'Hora de chegada', 'HORA', '1', 5, 5, '', '0123456789:');
    ShowHTML('  if (theForm.w_pais_orig.selectedIndex == theForm.w_pais_dest.selectedIndex && theForm.w_uf_orig.selectedIndex == theForm.w_uf_dest.selectedIndex && theForm.w_cidade_orig.selectedIndex == theForm.w_cidade_dest.selectedIndex) {');
    ShowHTML('      alert(\'Cidades de origem e de destino não podem ser iguais!\'); ');
    ShowHTML('      theForm.w_cidade_dest.focus(); ');
    ShowHTML('      return (false); ');
    ShowHTML('  }');
    CompData('w_data_saida', 'Data de saída', '<=', 'w_data_chegada', 'Data de chegada');
    ShowHTML('  if (theForm.w_data_saida.value == theForm.w_data_chegada.value) {');
    CompHora('w_hora_saida', 'Hora de saída', '<', 'w_hora_chegada', 'Hora de chegada');
    ShowHTML('  }');
    Validate('w_meio_transp', 'Meio de transporte', 'SELECT', 1, 1, 18, '', '1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } elseif ($O == 'I' || $O == 'A') {
    BodyOpenClean('onLoad="document.Form.w_pais_orig.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($P1 == 1)
    ShowHTML('      <tr><td colspan=2 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131" size=2>ATENÇÃO:</b> O agente interno de viagens pesquisará disponibilidade de vôo, buscando a melhor opção de custo, levando em consideração os horários dos compromissos a serem realizados, o desgaste físico e o dispêndio de tempo para o designado.</font></td></tr>');
  if ($O == 'L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('          <td><b>Destino</td>');
    ShowHTML('          <td><b>Saída</td>');
    ShowHTML('          <td><b>Chegada</td>');
    ShowHTML('          <td><b>Compromisso<br>dia viagem</td>');
    ShowHTML('          <td><b>Transporte</td>');
    ShowHTML('          <td><b>Bilhete</td>');
    //ShowHTML('          <td><b>Valor</td>');
    //ShowHTML('          <td><b>Companhia</td>');
    //ShowHTML('          <td><b>Vôo</td>');
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_destino') . '</td>');
        ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_saida'), 6), 0, -3) . '</td>');
        ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 6), 0, -3) . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'nm_compromisso') . '</td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'nm_meio_transporte'), '---') . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'nm_passagem') . '</td>');
        //ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>');
        //ShowHTML('        <td>'.f($row,'nm_cia_transporte').'</td>');
        //ShowHTML('        <td align="center">'.f($row,'codigo_voo').'</td>');
        ShowHTML('        <td class="remover" nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exclusão do trecho." onClick="return(confirm(\'Confirma exclusão do trecho?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
  } elseif (strpos('IA', $O) !== false) {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_reg" value="' . $w_tipo_reg . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Origem</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>aís:', 'P', null, $w_pais_orig, null, 'w_pais_orig', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_uf_orig\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:', 'S', null, $w_uf_orig, $w_pais_orig, null, 'w_uf_orig', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_cidade_orig\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:', 'C', null, $w_cidade_orig, $w_pais_orig, $w_uf_orig, 'w_cidade_orig', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_cidade_orig\'; document.Form.submit();"');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>A<u>e</u>roporto:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_aero_orig" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_aero_orig . '"></td>');
    ShowHTML('          <td><b><u>S</u>aída:</b><br><input ' . $w_Disabled . ' accesskey="S" type="text" name="w_data_saida" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data_saida . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data_saida') . '</td>');
    ShowHTML('          <td><b><u>H</u>ora local:</b><br><input ' . $w_Disabled . ' accesskey="H" type="text" name="w_hora_saida" class="sti" SIZE="5" MAXLENGTH="5" VALUE="' . $w_hora_saida . '" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" ></td>');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Destino</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>aís:', 'P', null, $w_pais_dest, null, 'w_pais_dest', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_uf_dest\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:', 'S', null, $w_uf_dest, $w_pais_dest, null, 'w_uf_dest', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_cidade_dest\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:', 'C', null, $w_cidade_dest, $w_pais_dest, $w_uf_dest, 'w_cidade_dest', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_cidade_dest\'; document.Form.submit();"');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>A<u>e</u>roporto:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_aero_dest" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_aero_dest . '"></td>');
    ShowHTML('          <td><b><u>C</u>hegada:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_data_chegada" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data_chegada . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onFocus="if (document.Form.w_data_chegada.value==\'\') { document.Form.w_data_chegada.value = document.Form.w_data_saida.value; }"> ' . ExibeCalendario('Form', 'w_data_chegada') . '</td>');
    ShowHTML('          <td><b><u>H</u>ora local:</b><br><input ' . $w_Disabled . ' accesskey="H" type="text" name="w_hora_chegada" class="sti" SIZE="5" MAXLENGTH="5" VALUE="' . $w_hora_chegada . '" onKeyDown="FormataHora(this,event);" onKeyUp="SaltaCampo(this.form.name,this,5,event);" ></td>');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Dados adicionais</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    if (f($RS_Solic, 'passagem') == 'S' && $P1 != 1 && $w_passagem == 'S') {
      SelecaoMeioTransporte('<u>M</u>eio de transporte:', 'M', null, $w_cliente, $w_meio_transp, null, 'w_meio_transp', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_meio_transp\'; document.Form.submit();"');
    } else {
      SelecaoMeioTransporte('<u>M</u>eio de transporte:', 'M', null, $w_cliente, $w_meio_transp, null, 'w_meio_transp', null, null);
    }
    // Se for primeira saída ou último retorno, informa se há compromisso no dia da viagem
    if ($w_cidade_orig == $w_cidade_padrao || $w_cidade_dest == $w_cidade_padrao || $w_tot_trechos == 0) {
      ShowHTML('        <td colspan="4"><table width="97%" border="0" cellpadding=0 cellspacing=0>');
      MontaRadioNS('<b>Há compromisso relativo à viagem no dia da viagem?</b>', $w_compromisso, 'w_compromisso', null, null, null);
      ShowHTML('      </table>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_compromisso" value="S">');
    }
    if (f($RS_Solic, 'passagem') == 'S') {
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td colspan="5"><table width="97%" border="0" cellpadding=0 cellspacing=0>');
      ShowHTML('        <tr valign="top">');
      if ($P1 != 1 && $w_passagem == 'S' && strpos($R, 'PRESTARCONTAS') === false && strpos($R, 'ALTSOLIC') === false) {
        MontaRadioSN('<b>Adquire bilhete para o trecho?</b>', $w_passagem, 'w_passagem', null, null, 'onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_valor_trecho\'; document.Form.submit();"');
        ShowHTML('            <td><b><u>V</u>alor estimado do trecho (R$):</b><br><input type="text" accesskey="V" name="w_valor_trecho" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_trecho . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor estimado do bilhete deste trecho."></td>');
        SelecaoCiaTrans('<u>C</u>ompanhia cotada', 'C', 'Selecione a companhia de transporte onde foi feita a cotação.', $w_cliente, $w_cia_aerea, null, 'w_cia_aerea', $w_meio_transp, null);
        ShowHTML('            <td><b>Código do vô<u>o</u>:</b><br><input type="text" accesskey="O" name="w_codigo_voo" class="sti" SIZE="5" MAXLENGTH="30" VALUE="' . $w_codigo_voo . '" title="Informe o código do vôo cotado."></td>');
      } else {
        MontaRadioSN('<b>Adquire bilhete para o trecho?</b>', $w_passagem, 'w_passagem', null, null, null);
        ShowHTML('<INPUT type="hidden" name="w_valor_trecho" value="0,00">');
        ShowHTML('<INPUT type="hidden" name="w_cia_aerea" value="">');
        ShowHTML('<INPUT type="hidden" name="w_codigo_voo" value="">');
      }
      ShowHTML('      </table>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_passagem" value="N">');
      ShowHTML('<INPUT type="hidden" name="w_valor_trecho" value="0,00">');
      ShowHTML('<INPUT type="hidden" name="w_cia_aerea" value="">');
      ShowHTML('<INPUT type="hidden" name="w_codigo_voo" value="">');
    }
    ShowHTML('      <tr><td colspan="5"><table border="0" width="100%">');
    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('              <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de cadastramento de bilhetes
// -------------------------------------------------------------------------
function Bilhetes() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitação e do cliente
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');

  //exibeArray($RS_Solic);

  if (f($RS_Solic, 'sg_tramite') == 'AE')
    $w_tipo_reg = 'S'; else
    $w_tipo_reg = 'P';

  // Trechos da solicitação
  $sql = new db_getPD_Deslocamento; $RS_Trecho = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, 'COTPASS');
  $RS_Trecho = SortArray($RS_Trecho, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');

  //Percentual de desconto do bilhete
  $sql = new db_getDescontoAgencia;
  $RS_Desc = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, null, null, 'S');
  if (count($RS_Desc) == 1) {
    foreach ($RS_Desc as $row) {
      $RS_Desc = $row;
      break;
    }
    $desconto = f($RS_Desc,'desconto');
  }
  

  if ($w_troca > '') {
    // Se for recarga da página
    $w_cia_aerea = $_REQUEST['w_cia_area'];
    $w_data = $_REQUEST['w_data'];
    $w_numero = $_REQUEST['w_numero'];
    $w_trecho = $_REQUEST['w_trecho'];
    $w_valor_bil = $_REQUEST['w_valor_bil'];
    $w_valor_cheio = $_REQUEST['w_valor_cheio'];
    $w_valor_pta = $_REQUEST['w_valor_pta'];
    $w_valor_tax = $_REQUEST['w_valor_tax'];
    $w_rloc = $_REQUEST['w_rloc'];
    $w_classe = $_REQUEST['w_classe'];
    $w_tipo = $_REQUEST['w_tipo'];
    $w_utilizado = $_REQUEST['w_utilizado'];
    $w_faturado = $_REQUEST['w_faturado'];
    $w_observacao = $_REQUEST['w_observacao'];
  } elseif ($O == 'L') {
    $sql = new db_getPD_Bilhete; $RS = $sql->getInstanceOf($dbms, $w_chave, null, null, null, null, null, null, null);
    $RS = SortArray($RS, 'data', 'asc', 'nm_cia_transporte', 'asc', 'numero', 'asc');
  } elseif (strpos('AE', $O) !== false) {
    $sql = new db_getPD_Bilhete; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_cia_aerea = f($RS, 'sq_cia_transporte');
    $w_data = formataDataEdicao(f($RS, 'data'));
    $w_numero = f($RS, 'numero');
    $w_trecho = f($RS, 'trecho');
    $w_valor_bil = formatNumber(f($RS, 'valor_bilhete'));
    $w_valor_cheio = formatNumber(f($RS, 'valor_bilhete_cheio'));
    $w_valor_pta = formatNumber(f($RS, 'valor_pta'));
    $w_valor_tax = formatNumber(f($RS, 'valor_taxa_embarque'));
    $w_rloc = f($RS, 'rloc');
    $w_classe = f($RS, 'classe');
    $w_tipo = f($RS, 'tipo');
    $w_utilizado = f($RS, 'utilizado');
    $w_faturado = f($RS, 'faturado');
    $w_observacao = f($RS, 'observacao');
  }
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Bilhetes</title>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  toMoney();
  if (nvl($desconto, '') != '') {
    ShowHTML('  $(document).ready(function() {');
    ShowHTML('    $("#w_valor_bil").blur(function() {');
    ShowHTML('      var desconto = parseFloat(' . $desconto . ')');
    ShowHTML('      var bilhete_cheio = parseFloat($(this).val().replace(".","").replace(".","").replace(",","."))');
    ShowHTML('      var bilhete_desconto = (bilhete_cheio - (bilhete_cheio * (desconto / 100))).toFixed(2);');
    ShowHTML('      if(bilhete_desconto > 0){');
    ShowHTML('        $("#w_valor_cheio").val(toMoney(bilhete_desconto,"BR"));');
    ShowHTML('      }');
    ShowHTML('    });');
    ShowHTML('  });');
  }
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    Validate('w_data', 'Data de emissão', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_data', 'Data de saída', '<=', formataDataEdicao(time()), 'data atual');
    Validate('w_cia_aerea', 'Companhia cotada', 'SELECT', '1', 1, 18, '', '1');
    Validate('w_numero', 'Número', '', 1, 1, 10, '1', '1');
    Validate('w_classe', 'Classe', '', '1', 1, 1, '1', '1');
    Validate('w_trecho', 'Trecho', '', 1, 1, 60, '1', '1');
    Validate('w_rloc', 'Número vôo', '', '', 1, 6, '1', '1');
    Validate('w_valor_cheio', 'Valor do bilhete com desconto', 'VALOR', '1', 4, 18, '', '0123456789,.');
    Validate('w_valor_bil', 'Valor do bilhete', 'VALOR', '1', 4, 18, '', '0123456789,.');
    CompValor('w_valor_bil', 'Valor do bilhete', '>=', '0,00', 'zero');
    CompValor('w_valor_cheio', 'Valor do bilhete com desconto', '<=', 'w_valor_bil', 'valor do bilhete');
    Validate('w_valor_tax', 'Valor da taxa de embarque', 'VALOR', '1', 4, 18, '', '0123456789,.');
    CompValor('w_valor_tax', 'Valor da taxa de embarque', '>=', '0,00', 'zero');
    Validate('w_valor_pta', 'Valor da transmissão do pta', 'VALOR', '1', 4, 18, '', '0123456789,.');
    CompValor('w_valor_pta', 'Valor da transmissão do pta', '>=', '0,00', 'zero');
    Validate('w_utilizado', 'Utilização', 'SELECT', '1', 1, 18, '1', '');
    Validate('w_observacao', 'Observação', '', '', '1', '500', '1', '1');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  if (theForm["w_sq_deslocamento[]"].value==undefined) {');
    ShowHTML('     for (i=0; i < theForm["w_sq_deslocamento[]"].length; i++) {');
    ShowHTML('       if (theForm["w_sq_deslocamento[]"][i].checked) w_erro=false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     if (theForm["w_sq_deslocamento[]"].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    return confirm("Confirma gravação de bilhete sem trechos vinculados?"); ');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . (($w_numero == 'S') ? $w_troca : 'Botao[0]') . '.focus();"');
  } elseif ($O == 'I' || $O == 'A') {
    BodyOpenClean('onLoad="document.Form.w_data.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td>');
    ShowHTML('        <a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td rowspan=2><b>Emissão</td>');
    ShowHTML('          <td rowspan=2><b>Cia.</td>');
    ShowHTML('          <td rowspan=2><b>Número</td>');
    ShowHTML('          <td rowspan=2><b>Trecho</td>');
    ShowHTML('          <td rowspan=2><b>RLOC</td>');
    ShowHTML('          <td rowspan=2><b>Classe</td>');
    ShowHTML('          <td colspan=4><b>Valores</td>');
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Bilhete</td>');
    ShowHTML('          <td><b>Embarque</td>');
    ShowHTML('          <td><b>Taxas</td>');
    ShowHTML('          <td><b>Total</td>');
    ShowHTML('        </tr>');
    $w_tot_bil = 0;
    $w_tot_pta = 0;
    $w_tot_tax = 0;
    $w_tot_bilhete = 0;
    $w_total = 0;
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_tot_bilhete = f($row, 'valor_bilhete') + f($row, 'valor_pta') + f($row, 'valor_taxa_embarque');
        $w_tot_bil += f($row, 'valor_bilhete');
        $w_tot_pta += f($row, 'valor_pta');
        $w_tot_tax += f($row, 'valor_taxa_embarque');
        $w_total += $w_tot_bilhete;
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'data'), 5) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_cia_transporte') . '</td>');
        ShowHTML('        <td>' . f($row, 'numero') . '</td>');
        ShowHTML('        <td>' . f($row, 'trecho') . '</td>');
        ShowHTML('        <td>' . f($row, 'rloc') . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'classe') . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_bilhete')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_taxa_embarque')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_pta')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber($w_tot_bilhete) . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exclusão do trecho." onClick="return(confirm(\'Confirma exclusão do trecho?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
      ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
      ShowHTML('        <td align="right" colspan="6">Totais</td>');
      ShowHTML('        <td align="right">' . formatNumber($w_tot_bil) . '</td>');
      ShowHTML('        <td align="right">' . formatNumber($w_tot_tax) . '</td>');
      ShowHTML('        <td align="right">' . formatNumber($w_tot_pta) . '</td>');
      ShowHTML('        <td align="right">' . formatNumber($w_total) . '</td>');
      ShowHTML('        <td>&nbsp;</td>');
      ShowHTML('      </tr>');
    }
  } elseif (strpos('IA', $O) !== false) {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_deslocamento[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="' . nvl($w_tipo, 'S') . '">');
    ShowHTML('<INPUT type="hidden" name="w_faturado" value="' . nvl($w_faturado, 'N') . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="97%" border="0">');

    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b>Data de emis<u>s</u>ão:</b><br><input ' . $w_Disabled . ' accesskey="S" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data') . '</td>');
    SelecaoCiaTrans('<u>C</u>ompanhia', 'C', 'Selecione a companhia de transporte que emitiu o bilhete.', $w_cliente, $w_cia_aerea, null, 'w_cia_aerea', null, null);
    ShowHTML('        <td><b><u>N</u>úmero: (sem prefixo da companhia aérea)</b><br><input ' . $w_Disabled . ' accesskey="N" type="text" name="w_numero" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $w_numero . '"></td>');
    ShowHTML('        <td><b>C<u>l</u>asse:</b><br><input ' . $w_Disabled . ' accesskey="L" type="text" name="w_classe" class="sti" SIZE="3" MAXLENGTH="1" style="text-align:center; text-transform:uppercase;" VALUE="' . $w_classe . '"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td colspan=3><b><u>T</u>rechos:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="w_trecho" class="sti" SIZE="60" MAXLENGTH="60" style="text-transform:uppercase;" VALUE="' . $w_trecho . '"></td>');
    ShowHTML('        <td><b><u>R</u>LOC:</b><br><input ' . $w_Disabled . ' accesskey="R" type="text" name="w_rloc" class="sti" SIZE="6" MAXLENGTH="6" VALUE="' . $w_rloc . '"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b>$ <u>B</u>ilhete cheio:</b><br><input type="text" accesskey="V" name="w_valor_bil" id="w_valor_bil" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_bil . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do bilhete."></td>');
    ShowHTML('        <td><b>$ Bilhete <u>c</u>om desconto:</b><br><input type="text" accesskey="C" name="w_valor_cheio" id="w_valor_cheio" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_cheio . '" style="text-align:right;" onFocus="FormataValor(this,18,2,event);" title="Informe o valor do bilhete com desconto."></td>');
    ShowHTML('        <td><b>$ <u>T</u>axa de embarque:</b><br><input type="text" accesskey="T" name="w_valor_tax" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_tax . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do bilhete."></td>');
    ShowHTML('        <td><b>$ Ta<u>x</u>as:</b><br><input type="text" accesskey="X" name="w_valor_pta" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . nvl($w_valor_pta, '0,00') . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do bilhete."></td>');
    if ($w_tipo_reg == 'P') {
      showHTML('      <tr>');
      selecaoTipoUtilBilhete('<u>U</u>tilização:', 'u', null, $w_utilizado, null, 'w_utilizado', null, null);
      showHTML('      </tr>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_utilizado" value="' . nvl($w_utilizado, 'N') . '">');
    }
    ShowHTML('      <tr><td colspan=3><b><u>O</u>bservação:</b><br><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
    //Trechos contemlados no bilhete
    ShowHTML('      <tr><td colspan="5"><br><b>Trechos contemplados pelo bilhete:</b>');
    ShowHTML('      <tr><td colspan="5"><TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center" valign="top">');
    ShowHTML('          <td><b>&nbsp;</b></td>');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('          <td><b>Destino</td>');
    ShowHTML('          <td><b>Saída</td>');
    ShowHTML('          <td><b>Chegada</td>');
    ShowHTML('          <td><b>Transporte</td>');
    ShowHTML('        </tr>');
    foreach ($RS_Trecho as $row) {
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('      <tr bgcolor="' . $w_cor . '">');
      if (nvl($w_chave_aux, '_|_') == nvl(f($row, 'sq_bilhete'), '')) {
        ShowHTML('        <td width="1%" nowrap align="center"><input ' . $w_Disabled . ' type="checkbox" name="w_sq_deslocamento[]" value="' . f($row, 'sq_deslocamento') . '" CHECKED>');
      } else {
        ShowHTML('        <td width="1%" nowrap align="center"><input ' . $w_Disabled . ' type="checkbox" name="w_sq_deslocamento[]" value="' . f($row, 'sq_deslocamento') . '">');
      }
      ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
      ShowHTML('        <td>' . f($row, 'nm_destino') . '</td>');
      ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_saida'), 6), 0, -3) . '</td>');
      ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 6), 0, -3) . '</td>');
      ShowHTML('        <td align="center">' . nvl(f($row, 'nm_meio_transporte'), '---') . '</td>');
      ShowHTML('      </tr>');
    }
    ShowHTML('        </table>');

    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('              <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de ajustes nos deslocamentos e diárias
// -------------------------------------------------------------------------
function AltSolic() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitação e do cliente
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_nm_diaria   = f($RS_Solic, 'nm_diaria');
  $w_fim_semana  = nvl($_REQUEST['w_fim_semana'], f($RS_Solic, 'diaria_fim_semana'));
  $w_valor_comp  = nvl($_REQUEST['w_valor_comp'], f($RS_Solic, 'valor_complemento'));
  $w_qtd_comp    = nvl($_REQUEST['w_qtd_comp'], f($RS_Solic, 'complemento_qtd'));
  $w_tot_comp    = nvl($_REQUEST['w_tot_comp'], f($RS_Solic, 'complemento_valor'));
  $w_complemento = $_REQUEST['w_complemento'];

  if (($w_qtd_comp + $w_tot_comp) > 0) {
    $w_complemento = nvl($_REQUEST['w_complemento'],'S');
  }else{
    $w_complemento = nvl($_REQUEST['w_complemento'],'N');
  }
  
  ScriptOpen('JavaScript');
  toMoney();
  if (floatVal($w_valor_comp) > 0) {
    if (nvl($w_complemento, 'N') == 'S') {
      ShowHTML('  $(document).ready(function() {');
      ShowHTML('      var valor;');
      ShowHTML('      var quantidade;');
      ShowHTML('      var total = parseFloat(0).toFixed(2);');
      ShowHTML('      var decimal = "";');
      ShowHTML('      $("#w_qtd_comp").blur(function() {');
      ShowHTML('        valor = $("#w_valor_comp").val().replace(".","").replace(".","").replace(",",".")');
      ShowHTML('        quantidade = $("#w_qtd_comp").val().replace(".","").replace(".","").replace(",",".")');
      ShowHTML('        total = (valor * quantidade).toFixed(2);');
      ShowHTML('        if(quantidade > 0){');
      ShowHTML('          $("#w_tot_comp").val(toMoney(total,"BR"));');
      ShowHTML('        }else{');
      ShowHTML('          $("#w_tot_comp").val(parseFloat(0).toFixed(2));');
      ShowHTML('        }');
      ShowHTML('      });');
      ShowHTML('      $(\'form[name="Form"]\').submit(function() {');
      ShowHTML('        $("#w_qtd_comp").blur();');
      ShowHTML('        return true;');
      ShowHTML('      });');
      ShowHTML('    });');
      FormataValor();
      ValidateOpen('Validacao');
      Validate('w_qtd_comp', 'Quantidade do complemento', 'VALOR', '1', 3, 5, '1', '0123456789,');
      CompValor('w_qtd_comp', 'Quantidade do complemento', '>', '0,0', 'zero');
      //Validate('w_tot_comp', 'Valor total do complemento de diária', 'VALOR', '1', 4, 18, '1', '0123456789,.');
      ValidateClose();
    } else {
      //$w_valor_comp = '0,00';
      $w_qtd_comp = '0,0';
      $w_tot_comp = '0,00';
      $disabled = ' DISABLED ';
      ValidateOpen('Validacao');
      ValidateClose();
    }
  } else {
    ValidateOpen('Validacao');
    ValidateClose();
  }
  ScriptClose();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . (($w_numero == 'S') ? $w_troca : 'Botao[0]') . '.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="4">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');

  ShowHTML('<tr><td colspan="4"><br>&nbsp;');
  ShowHTML('<tr><td colspan="4"><a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
  ShowHTML('<tr><td colspan="4"><hr NOSHADE color=#000000 SIZE=1>&nbsp;');

  if (f($RS_Solic, 'internacional') == 'N' || $w_valor_comp > 0) {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PDDIARIAFS', $w_pagina . $par, 'A');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('       <input type="hidden" name="w_chave" value=' . $w_chave . '>');
    if ($w_valor_comp > 0) {
      ShowHTML('        <tr valign="top">');
      MontaRadioNS('<b>Pagar complemento de diária?</b>', $w_complemento, 'w_complemento', null, null, 'onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.submit();"');
      ShowHTML('          <td width="20%"><b>Valor complemento:</b><br><input ' . $disabled . ' class="STI" value="' . formatNumber($w_valor_comp, 2) . '" readonly maxlength="10" size="10" type="text" id="w_valor_comp" name="w_valor_comp" onKeyDown="FormataValor(this,18,2,event);" /></td>');
      ShowHTML('          <td><b>Quantidade:</b><br><input ' . $disabled . ' class="STI" maxlength="5" size="5" type="text" name="w_qtd_comp" value="' . formatNumber($w_qtd_comp, 1) . '" id="w_qtd_comp" onKeyDown="FormataValor(this,5,1,event);"/></td>');
      ShowHTML('          <td><b>Valor a ser pago:</b><br><input ' . $disabled . ' class="STI" maxlength="10" size="10" type="text" value="' . formatNumber($w_tot_comp, 2) . '" readonly name="w_tot_comp" id="w_tot_comp" onKeyDown="FormataValor(this,18,2,event);" /></td>');
      ShowHTML('        </tr>');
    }

    if (f($RS_Solic, 'internacional') == 'N') {
      ShowHTML('        <tr valign="top">');
      MontaRadioNS('<b>Pagar diárias em fim de semana?</b>', $w_fim_semana, 'w_fim_semana');
    } else {
      ShowHTML('       <input type="hidden" name="w_fim_semana" value="N">');
    }


    ShowHTML('        <tr valign="top"><td>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('</FORM>');
  }
  ShowHTML('<tr><td colspan="4"><b>Deslocamentos solicitados: (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', 'PDTRECHO');
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  ShowHTML('<tr><td colspan="4"><table width="100%" border="0">');
  ShowHTML('  <tr><td colspan="2">');
  ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
  ShowHTML('          <td><b>Origem</td>');
  ShowHTML('          <td><b>Destino</td>');
  ShowHTML('          <td><b>Saída</td>');
  ShowHTML('          <td><b>Chegada</td>');
  ShowHTML('          <td><b>Compromisso<br>dia viagem</td>');
  ShowHTML('          <td><b>Transporte</td>');
  ShowHTML('          <td><b>Bilhete</td>');
  ShowHTML('          <td><b>Operações</td>');
  ShowHTML('        </tr>');
  if (count($RS) <= 0) {
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>INFORME O ROTEIRO REALIZADO.</b></b></td></tr>');
  } else {
    foreach ($RS as $row) {
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
      ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
      ShowHTML('        <td>' . f($row, 'nm_destino') . '</td>');
      ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_saida'), 6), 0, -3) . '</td>');
      ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 6), 0, -3) . '</td>');
      ShowHTML('        <td align="center">' . f($row, 'nm_compromisso') . '</td>');
      ShowHTML('        <td align="center">' . nvl(f($row, 'nm_meio_transporte'), '---') . '</td>');
      ShowHTML('        <td align="center">' . f($row, 'nm_passagem') . '</td>');
      ShowHTML('        <td nowrap>');
      ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
      ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Exclusão do trecho." onClick="return(confirm(\'Confirma exclusão do trecho?\'));">EX</A>&nbsp');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    }
  }
  ShowHTML('</table>');

  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', 'PDDIARIA');
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  $i = 0;
  foreach ($RS as $row) {
    if ($i == 0)
      $w_inicio = f($row, 'saida');
    $w_fim = f($row, 'chegada');
    $i++;
  }
  reset($RS);

  ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
  ShowHTML('  function altera (solic, texto) {');
  ShowHTML('    document.Form1.w_chave.value=solic;');
  ShowHTML('    document.Form1.w_trechos.value=texto;');
  ShowHTML('    document.Form1.submit();');
  ShowHTML('  }');
  ShowHTML('</SCRIPT>');
  ShowHTML('<tr><td><br><b>Diárias solicitadas: (Categoria ' . $w_nm_diaria . ')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
  ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">Informe somente após registrar todos os deslocamentos. Qualquer alteração nos deslocamentos irá remover todos os dados informados para as diárias.</font></td>');
  if (count($RS) > 0) {
    $i = 1;
    foreach ($RS as $row) {
      $w_trechos[$i][1] = f($row, 'sq_diaria');
      $w_trechos[$i][2] = f($row, 'sq_deslocamento');
      $w_trechos[$i][3] = f($row, 'sq_deslocamento');
      $w_trechos[$i][4] = f($row, 'cidade_dest');
      $w_trechos[$i][5] = f($row, 'nm_destino');
      $w_trechos[$i][6] = f($row, 'phpdt_chegada');
      $w_trechos[$i][7] = f($row, 'phpdt_saida');
      $w_trechos[$i][8] = Nvl(f($row, 'quantidade'), 0);
      $w_trechos[$i][9] = Nvl(f($row, 'valor'), 0);
      $w_trechos[$i][10] = f($row, 'saida');
      $w_trechos[$i][11] = f($row, 'chegada');
      $w_trechos[$i][12] = f($row, 'diaria');
      $w_trechos[$i][13] = f($row, 'sg_moeda_diaria');
      $w_trechos[$i][14] = f($row, 'vl_diaria');
      $w_trechos[$i][15] = f($row, 'hospedagem');
      $w_trechos[$i][16] = Nvl(f($row, 'hospedagem_qtd'), 0);
      $w_trechos[$i][17] = Nvl(f($row, 'hospedagem_valor'), 0);
      $w_trechos[$i][18] = f($row, 'sg_moeda_hospedagem');
      $w_trechos[$i][19] = f($row, 'vl_diaria_hospedagem');
      $w_trechos[$i][20] = f($row, 'veiculo');
      $w_trechos[$i][21] = Nvl(f($row, 'veiculo_qtd'), 0);
      $w_trechos[$i][22] = Nvl(f($row, 'veiculo_valor'), 0);
      $w_trechos[$i][23] = f($row, 'sg_moeda_veiculo');
      $w_trechos[$i][24] = f($row, 'vl_diaria_veiculo');
      $w_trechos[$i][25] = f($row, 'sq_valor_diaria');
      $w_trechos[$i][26] = f($row, 'sq_diaria_hospedagem');
      $w_trechos[$i][27] = f($row, 'sq_diaria_veiculo');
      $w_trechos[$i][28] = f($row, 'justificativa_diaria');
      $w_trechos[$i][29] = f($row, 'justificativa_veiculo');
      $w_trechos[$i][30] = f($row, 'compromisso');
      $w_trechos[$i][31] = f($row, 'compromisso');
      $w_trechos[$i][32] = 'N';
      $w_trechos[$i][33] = 'N';
      $w_trechos[$i][34] = f($row, 'sq_fin_dia');
      $w_trechos[$i][35] = f($row, 'sq_rub_dia');
      $w_trechos[$i][36] = f($row, 'sq_lan_dia');
      $w_trechos[$i][37] = f($row, 'sq_fin_hsp');
      $w_trechos[$i][38] = f($row, 'sq_rub_hsp');
      $w_trechos[$i][39] = f($row, 'sq_lan_hsp');
      $w_trechos[$i][40] = f($row, 'sq_fin_vei');
      $w_trechos[$i][41] = f($row, 'sq_rub_vei');
      $w_trechos[$i][42] = f($row, 'sq_lan_vei');
      $w_trechos[$i][43] = f($row, 'hospedagem_checkin');
      $w_trechos[$i][44] = f($row, 'hospedagem_checkout');
      $w_trechos[$i][45] = f($row, 'hospedagem_observacao');
      $w_trechos[$i][46] = f($row, 'veiculo_retirada');
      $w_trechos[$i][47] = f($row, 'veiculo_devolucao');
      $w_trechos[$i][48] = f($row, 'saida_internacional');
      $w_trechos[$i][49] = f($row, 'chegada_internacional');
      $w_trechos[$i][50] = f($row, 'origem_nacional');
      $w_trechos[$i][51] = f($row, 'destino_nacional');
      // Cria array para guardar o valor total por moeda
      if ($w_trechos[$i][13] > '')
        $w_total[$w_trechos[$i][13]] = 0;
      if ($w_trechos[$i][18] > '')
        $w_total[$w_trechos[$i][18]] = 0;
      if ($w_trechos[$i][12] > '')
        $w_total[$w_trechos[$i][23]] = 0;
      if ($i == 1) {
        // Se a primeira saída for após as 18:00, deduz meia diária
        if (intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_saida'), 2))) > 180000) {
          $w_trechos[$i][32] = 'S';
        }
      } else {
        // Se a última chegada for até 12:00, deduz meia diária
        if ($i == count($RS) && intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_chegada'), 2))) <= 120000) {
          $w_trechos[$i - 1][33] = 'S';
        }
        $w_trechos[$i - 1][3] = f($row, 'sq_deslocamento');
        $w_trechos[$i - 1][7] = f($row, 'phpdt_saida');
        $w_trechos[$i - 1][31] = f($row, 'compromisso');
      }
      $i += 1;
    }
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('           <td><b>Destino</td>');
    ShowHTML('           <td><b>Chegada</td>');
    ShowHTML('           <td><b>Saída</td>');
    ShowHTML('           <td><b>Operações</td>');
    ShowHTML('         </tr>');
    $w_cor = $conTrBgColor;
    $j = $i;
    $i = 1;
    $w_diarias = 0;
    $w_locacoes = 0;
    $w_hospedagens = 0;
    $w_tot_local = 0;
    AbreForm('Form1', $w_dir . $w_pagina . 'diarias', 'POST', 'return true;', null, $P1, $P2, $P3, $P4, $TP, 'PDDIARIA', $w_pagina . $par, 'A');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('       <input type="hidden" name="w_chave" value="">');
    ShowHTML('       <input type="hidden" name="w_trechos" value="">');
    while ($i != ($j - 1)) {
      $w_max_hosp = floor(($w_trechos[$i][44] - $w_trechos[$i][43]) / 86400);
      $w_max_diaria = ceil(($w_trechos[$i][7] - $w_trechos[$i][6]) / 86400);
      $w_max_veiculo = ceil(($w_trechos[$i][47] - $w_trechos[$i][46]) / 86400);
      if (($i > 0 && $i < ($j - 1) && (($w_trechos[$i][51] == 'N' && toDate(FormataDataEdicao($w_trechos[$i][6])) == $w_fim) ||
              ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim)
              )
              ) ||
              ($w_max_hosp >= 0 &&
              $w_trechos[$i][48] == 0 &&
              $w_trechos[$i][49] == 0 &&
              ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim))
      ) {
        $w_diarias = nvl($w_trechos[$i][8], 0) * nvl($w_trechos[$i][9], 0);
        $w_locacoes = (-1 * nvl($w_trechos[$i][9], 0) * nvl($w_trechos[$i][22], 0) / 100 * nvl($w_trechos[$i][21], 0));
        $w_hospedagens = nvl($w_trechos[$i][16], 0) * nvl($w_trechos[$i][17], 0);

        if ($w_diarias > 0)
          $w_total[$w_trechos[$i][13]] += $w_diarias;
        if ($w_locacoes <> 0)
          $w_total[$w_trechos[$i][23]] += $w_locacoes;

        $w_tot_local = $w_diarias + $w_locacoes;

        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
        ShowHTML('       <td>' . $w_trechos[$i][5]);
        if ($w_trechos[$i][32] == 'S')
          ShowHTML('<br>Saída após 18:00');
        if ($w_trechos[$i][32] == 'S')
          ShowHTML('<br>Chegada até 12:00');
        if ($w_trechos[$i][30] == 'N')
          ShowHTML('<br>Sem compromisso na ida');
        if ($w_trechos[$i][31] == 'N')
          ShowHTML('<br>Sem compromisso na volta');
        ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][6], 4), 0, -3) . '</b></td>');
        ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][7], 4), 0, -3) . '</b></td>');
        ShowHTML('       <td>');
        ShowHTML('          <A class="HL" HREF="javascript:altera(' . f($row, 'sq_siw_solicitacao') . ',\'' . base64_encode(serialize($w_trechos[$i])) . '\');" title="Informa as diárias">' . ((nvl($w_trechos[$i][1], '') == '') ? '<blink><b><font color="RED">Informar</font></b></blink>' : 'Informar') . '</A>&nbsp');
        ShowHTML('       </td>');
      }
      $i += 1;
    }
    ShowHTML('       </FORM>');
    ShowHTML('        </table></td></tr>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de registro das alterações de viagem
// -------------------------------------------------------------------------
function RegistroAlteracao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitação e do cliente
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');

  if ($w_troca > '') {
    // Se for recarga da página
    $w_caminho = $_REQUEST['w_caminho'];
    $w_atual = $_REQUEST['w_atual'];
    $w_moeda_dia = $_REQUEST['w_moeda_dia'];
    $w_valor_dia = $_REQUEST['w_valor_dia'];
    $w_moeda_hsp = $_REQUEST['w_moeda_hsp'];
    $w_valor_hsp = $_REQUEST['w_valor_hsp'];
    $w_valor_tar = $_REQUEST['w_valor_tar'];
    $w_valor_tax = $_REQUEST['w_valor_tax'];
    $w_justificativa = $_REQUEST['w_justificativa'];
    $w_pessoa = $_REQUEST['w_pessoa'];
    $w_cargo = $_REQUEST['w_cargo'];
    $w_data = $_REQUEST['w_data'];
  } elseif ($O == 'L') {
    $sql = new db_getPD_Alteracao; $RS = $sql->getInstanceOf($dbms, $w_chave, null, null, null, null, null, null);
    $RS = SortArray($RS, 'autorizacao_data', 'asc', 'chave', 'asc');
  } elseif (strpos('AE', $O) !== false) {
    $sql = new db_getPD_Alteracao; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_caminho = f($RS, 'sq_siw_arquivo');
    $w_atual = f($RS, 'sq_siw_arquivo');
    $w_moeda_dia = f($RS, 'diaria_moeda');
    $w_valor_dia = formatNumber(f($RS, 'diaria_valor'));
    $w_moeda_hsp = f($RS, 'hospedagem_moeda');
    $w_valor_hsp = formatNumber(f($RS, 'hospedagem_valor'));
    $w_valor_tar = formatNumber(f($RS, 'bilhete_tarifa'));
    $w_valor_tax = formatNumber(f($RS, 'bilhete_taxa'));
    $w_justificativa = f($RS, 'justificativa');
    $w_pessoa = f($RS, 'autorizacao_pessoa');
    $w_cargo = f($RS, 'autorizacao_cargo');
    $w_data = formataDataEdicao(f($RS, 'autorizacao_data'));
    $w_total = formatNumber(nvl($w_valor_dia, 0) + nvl($w_valor_hsp, 0) + nvl($w_valor_tar, 0) + nvl($w_valor_tax, 0));
  }
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Alterações de viagem</title>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  if ($O != 'L') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataHora();
    FormataValor();
    ShowHTML('function calculaTotal() { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  var w_tarifa = replaceAll(replaceAll(obj.w_valor_tar.value,".",""),",",".");');
    ShowHTML('  var w_taxa   = replaceAll(replaceAll(obj.w_valor_tax.value,".",""),",",".");');
    ShowHTML('  var w_hosp   = replaceAll(replaceAll(obj.w_valor_hsp.value,".",""),",",".");');
    ShowHTML('  var w_diaria = replaceAll(replaceAll(obj.w_valor_dia.value,".",""),",",".");');
    ShowHTML('  var w_res = parseFloat(w_tarifa)+parseFloat(w_taxa)+parseFloat(w_hosp)+parseFloat(w_diaria);');
    ShowHTML('  if (w_res==0)     obj.w_total.value="0,00";');
    ShowHTML('  else if (w_res<0) obj.w_total.value = "-"+mascaraGlobal("[###.]###,##",w_res*100);');
    ShowHTML('  else              obj.w_total.value = mascaraGlobal("[###.]###,##",w_res*100);');
    ShowHTML('}');
    ValidateOpen('Validacao');
    if ($O == 'I' || $O == 'A') {
      Validate('w_valor_tar', 'Valor da transmissão do pta', 'VALOR', '1', 4, 18, '', '0123456789,.-');
      Validate('w_valor_tax', 'Valor da taxa de embarque', 'VALOR', '1', 4, 18, '', '0123456789,.-');
      Validate('w_valor_hsp', 'Dif. hospedagem', 'VALOR', '1', 4, 18, '', '0123456789,.-');
      Validate('w_valor_dia', 'Diárias', 'VALOR', '1', 4, 18, '', '0123456789,.-');
      Validate('w_justificativa', 'Justificativa', '', 1, 5, 2000, '1', '1');
      Validate('w_caminho', 'Arquivo', '', '', '5', '255', '1', '1');
      Validate('w_pessoa', 'Autorizador', 'SELECT', '1', 1, 18, '', '1');
      Validate('w_cargo', 'Cargo', '', 1, 1, 90, '1', '1');
      Validate('w_data', 'Data de autorização', 'DATA', '1', 10, 10, '', '0123456789/');
      CompData('w_data', 'Data de autorização', '<=', formataDataEdicao(time()), 'data atual');
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    }
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  }
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } elseif ($O == 'I' || $O == 'A') {
    BodyOpenClean('onLoad="document.Form.w_valor_tar.focus();"');
  } elseif ($O == 'E') {
    BodyOpenClean('onLoad="document.Form.w_assinatura.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td>');
    ShowHTML('        <a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td colspan=5><b>Diferenças</td>');
    ShowHTML('          <td colspan=3><b>Autorização</td>');
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Tarifas</td>');
    ShowHTML('          <td><b>Taxas</td>');
    ShowHTML('          <td><b>Hospedagens</td>');
    ShowHTML('          <td><b>Diárias</td>');
    ShowHTML('          <td><b>Total</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Cargo</td>');
    ShowHTML('          <td><b>Data</td>');
    ShowHTML('        </tr>');
    $w_tot_alt = 0;
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_tot_alt = f($row, 'diaria_valor') + f($row, 'hospedagem_valor') + f($row, 'bilhete_tarifa') + f($row, 'bilhete_taxa');
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'bilhete_tarifa')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'bilhete_taxa')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'hospedagem_valor')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'diaria_valor')) . '</td>');
        ShowHTML('        <td align="right">' . formatNumber($w_tot_alt) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_autorizador') . '</td>');
        ShowHTML('        <td>' . f($row, 'autorizacao_cargo') . '</td>');
        ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'autorizacao_data'), 5) . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Altera os dados do registro.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exclusão do registro.">EX</A>&nbsp');
        ShowHTML('          <A   onclick="window.open (\'' . montaURL_JS($w_dir, $w_pagina . 'ImprimeAlteracao' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=V&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=600, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;">Imprimir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
  } elseif (strpos('IAE', $O) !== false) {
    if ($O == 'E')
      $w_Disabled = ' DISABLED ';
    ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava&SG=' . $SG . '&O=' . $O . '" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
    ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
    ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
    ShowHTML('<INPUT type="hidden" name="P4" value="' . $P4 . '">');
    ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
    ShowHTML('<INPUT type="hidden" name="R" value="' . $R . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="' . $w_atual . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="97%" border="0">');

    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0"><b>Diferenças</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5">Informe os valores desta alteração de viagem somente para os campos onde houve diferença. Pelo menos um dos campos deve ser maior que zero.</td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>T</u>arifas bilhetes:</b><br><input type="text" accesskey="T" ' . $w_Disabled . ' name="w_valor_tar" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . nvl($w_valor_tar, '0,00') . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" onBlur="calculaTotal();" title="Informe o valor da diferença relativa a tarifas dos bilhetes."></td>');
    ShowHTML('        <td><b>Ta<u>x</u>as bilhetes:</b><br><input type="text" accesskey="X" ' . $w_Disabled . ' name="w_valor_tax" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . nvl($w_valor_tax, '0,00') . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" onBlur="calculaTotal();" title="Informe o valor da diferença relativa a taxas dos bilhetes."></td>');
    ShowHTML('        <td><b><u>H</u>ospedagens:</b><br><input type="text" accesskey="H" ' . $w_Disabled . ' name="w_valor_hsp" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . nvl($w_valor_hsp, '0,00') . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" onBlur="calculaTotal();" title="Informe o valor da diferença relativa a hospedagens."></td>');
    ShowHTML('        <td><b><u>D</u>iárias:</b><br><input type="text" accesskey="D" ' . $w_Disabled . ' name="w_valor_dia" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . nvl($w_valor_dia, '0,00') . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" onBlur="calculaTotal();" title="Informe o valor da diferença relativa a diárias."></td>');
    ShowHTML('        <td><b>Total:</b><br><input type="text" READONLY name="w_total" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_total . '" style="text-align:right;" title="Valor total da alteração."></td>');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0"><b>Justificativa e Autorização</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5">Informe a justificativa para a alteração da viagem e os dados do autorizador desta alteração de viagem.</td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5"><b><u>J</u>ustificativa:</b><br><textarea class="STI" accesskey="J" ' . $w_Disabled . ' name="w_justificativa" class="STI" ROWS=5 cols=75 title="Informe a justificativa para a alteração da viagem.">' . $w_justificativa . '</TEXTAREA></td>');

    ShowHTML('<tr><td colspan="5"><b>Arquivo contendo a justificativa (o tamanho máximo aceito para o arquivo é de ' . formatNumber((f($RS_Cliente, 'upload_maximo') / 1024), 0) . ' KBytes)</b></font></td></tr>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS_Cliente, 'upload_maximo') . '">');
    ShowHTML('<tr><td colspan="5"><input ' . $w_Disabled . ' type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
    if (nvl($w_atual, '') != '') {
      ShowHTML('&nbsp;' . LinkArquivo('HL', $w_cliente, $w_atual, '_blank', 'Clique para exibir o arquivo em outra janela.', 'Exibir', null));
      ShowHTML('&nbsp;<input ' . $w_Disabled . ' type="checkbox" ' . $w_Disabled . ' name="w_exclui_arquivo" value="S" ' . ((nvl($w_exclui_aruivo, 'nulo') != 'nulo') ? 'checked' : '') . '>  Remover arquivo atual');
    }

    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Autori<u>z</u>ador:', 'Z', 'Selecione o responsável pela autorização.', $w_pessoa, null, 'w_pessoa', 'USUARIOS');
    ShowHTML('        <td colspan=2><b><u>C</u>argo:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_cargo" class="sti" SIZE="40" MAXLENGTH="90" VALUE="' . $w_cargo . '"></td>');
    ShowHTML('        <td><b><u>D</u>ata:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data') . '</td>');
    ShowHTML('      <tr><td colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('              <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

function ImprimeAlteracao() {
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  include_once($w_dir_volta . 'classes/sp/db_getCustomerData.php');
  $sql = new db_getCustomerData; $RS_Logo = $sql->getInstanceOf($dbms, $w_cliente);

  if (f($RS_Logo, 'logo') > '') {
    $p_logo = 'img/logo' . substr(f($RS_Logo, 'logo'), (strpos(f($RS_Logo, 'logo'), '.') ? strpos(f($RS_Logo, 'logo'), '.') + 1 : 0) - 1, 30);
  }

  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');

  $sql = new db_getPD_Alteracao; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null, null, null, null);
  foreach ($RS as $row) {
    $RS = $row;
    break;
  }

  $w_caminho = f($RS, 'sq_siw_arquivo');
  $w_atual = f($RS, 'sq_siw_arquivo');
  $w_moeda = f($RS, 'sb_diaria_moeda');
  $w_moeda_dia = f($RS, 'diaria_moeda');
  $w_valor_dia = f($RS, 'diaria_valor');
  $w_moeda_hsp = f($RS, 'hospedagem_moeda');
  $w_valor_hsp = f($RS, 'hospedagem_valor');
  $w_valor_tar = f($RS, 'bilhete_tarifa');
  $w_valor_tax = f($RS, 'bilhete_taxa');
  $w_justificativa = f($RS, 'justificativa');
  $w_pessoa = f($RS, 'autorizacao_pessoa');
  $w_cargo = f($RS, 'autorizacao_cargo');
  $w_data = formataDataEdicao(f($RS, 'autorizacao_data'));

  Cabecalho();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  bodyOpen(null);
  ShowHTML('<center>');

  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr valign="middle">');
  ShowHTML('    <td rowspan=2 valign="middle" align="right" width="30%"><IMG ALIGN="center" SRC="' . LinkArquivo(null, $w_cliente, $p_logo, null, null, null, 'EMBED') . '"><p>');
  ShowHTML('    <td colspan=3 align="center" style="font-size:12px">Procedimento Operacional<br><br>POLÍTICA DE VIAGENS</td>');
  ShowHTML('  <tr valign=top>');
  ShowHTML('    <td align="center" style="font-size:12px">Código<br>PO_004</td>');
  ShowHTML('    <td align="center" style="font-size:12px">Revisão<br>06</td>');
  ShowHTML('    <td align="center" style="font-size:12px">Página<br>01</td>');
  ShowHTML('  </tr>');
  ShowHTML('</table>');

  ShowHTML('<table border=0 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr valign=top>');
  ShowHTML('    <td align="center" style="font-size:12px"><br>ANEXO IV - ALTERAÇÕES - Reemissão<br><br></td>');
  ShowHTML('</table>');

  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr><td colspan="2" style="font-size:12px"><b>' . f($RS_Solic, 'codigo_interno'));
  ShowHTML('  <tr valign=top>');
  ShowHTML('    <td style="font-size:10px">Nome: <b>' . f($RS1, 'nm_pessoa'));
  ShowHTML('    <td nowrap style="font-size:10px">CPF: <b>' . f($RS1, 'cpf'));
  ShowHTML('  </tr>');
  ShowHTML('</table>');

  ShowHTML('<br>');

  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr><td>Diferença tarifa bilhetes (1)<td style="font-size:10px" align="right"><b>' . formatNumber($w_valor_tar) . '&nbsp;&nbsp;&nbsp;');
  ShowHTML('  <tr><td>Diferença taxas bilhetes (2)<td style="font-size:10px" align="right"><b>' . formatNumber($w_valor_tax) . '&nbsp;&nbsp;&nbsp;');
  ShowHTML('  <tr><td>Diferença hospedagem (3)<td style="font-size:10px" align="right"><b>' . formatNumber($w_valor_hsp) . '&nbsp;&nbsp;&nbsp;');
  ShowHTML('  <tr><td>Diferença diárias (4)<td style="font-size:10px" align="right"><b>' . formatNumber($w_valor_dia) . '&nbsp;&nbsp;&nbsp;');
  ShowHTML('  <tr><td>Custo total (1+2+3+4)<td style="font-size:15px" align="right"><b>' . $w_moeda . ' ' . formatNumber($w_valor_tar + $w_valor_tax + $w_valor_hsp + $w_valor_dia) . '&nbsp;');
  ShowHTML('</table>');

  ShowHTML('<br>');

  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr><td style="font-size:10px">Justificativa:<br><b>' . CrLf2Br($w_justificativa) . '</td></tr>');
  ShowHTML('</table>');

  ShowHTML('<br>');

  ShowHTML('<table border=1 cellspacing=0 bordercolor=black  style="width:650px;"  >');
  ShowHTML('  <tr><td colspan="2" align="center" style="font-size:12px">AUTORIZAÇÃO');
  ShowHTML('  <tr><td colspan="2" style="font-size:10px">Nome: <b>' . f($RS, 'nm_autorizador'));
  ShowHTML('  <tr valign="top">');
  ShowHTML('    <td style="font-size:10px">Cargo: <b>' . f($RS, 'autorizacao_cargo'));
  ShowHTML('    <td style="font-size:10px">Data: <b>' . formataDataEdicao(f($RS, 'autorizacao_data')));
  ShowHTML('  <tr><td colspan="2" style="font-size:10px">Assinatura:<p>&nbsp</p>');
  ShowHTML('  </tr>');
  ShowHTML('</table>');


  ShowHTML('</center>');
}

// =========================================================================
// Rotina de vinculação a Tarefas e Demandas
// -------------------------------------------------------------------------
function Vinculacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_operacao = $_REQUEST['w_operacao'];
  $p_sigla = Nvl($_REQUEST['p_sigla'], 'GDPCAD');

  if ($O == 'L') {
    $sql = new db_getPD_Vinculacao; $RS = $sql->getInstanceOf($dbms, $w_chave, null, null);
    $RS = SortArray($RS, 'inicio', 'asc');
  }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  if ($O == 'I') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataHora();
    ValidateOpen('Validacao');
    if ($p_sigla == 'GDPCAD') {
      Validate('p_projeto', 'Projeto', 'SELECT', '1', '1', '18', '', '0123456789');
    }
    Validate('p_chave', 'Número da demanda', '', '', '1', '18', '', '0123456789');
    Validate('p_proponente', 'Proponente externo', '', '', '2', '90', '1', '');
    Validate('p_assunto', 'Detalhamento', '', '', '2', '90', '1', '1');
    Validate('p_fim_i', 'Conclusão inicial', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim_f', 'Conclusão final', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
    ShowHTML('     theForm.p_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_fim_i', 'Conclusão inicial', '<=', 'p_fim_f', 'Conclusão final');
    if ($p_sigla == 'GDPCAD') {
      ShowHTML('  if (theForm.p_projeto.value==\'\' && theForm.p_atividade.value==\'\' && theForm.p_chave.value==\'\' && theForm.p_proponente.value==\'\' && theForm.p_assunto.value==\'\' && theForm.p_pais.value==\'\' && theForm.p_uf.value==\'\' && theForm.p_cidade.value==\'\' && theForm.p_fim_i.value==\'\' && theForm.p_fim_f.value==\'\') {');
    } else {
      ShowHTML('  if (theForm.p_chave.value==\'\' && theForm.p_proponente.value==\'\' && theForm.p_assunto.value==\'\' && theForm.p_pais.value==\'\' && theForm.p_uf.value==\'\' && theForm.p_cidade.value==\'\' && theForm.p_fim_i.value==\'\' && theForm.p_fim_f.value==\'\') {');
    }
    ShowHTML('     alert (\'Você deve informar algum critério de busca!\');');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.w_operacao.value=\'LISTA\';');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    if (Nvl($w_operacao, '') > '') {
      ValidateOpen('Validacao1');
      ShowHTML('  if (theForm.Botao.value==\'Procurar\') {');
      Validate('p_assunto', 'Detalhamento', '', '1', '2', '90', '1', '1');
      ShowHTML('  } else {');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_demanda[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_demanda[]"].length; i++) {');
      ShowHTML('       if (theForm["w_demanda[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_demanda[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      if ($p_sigla == 'GDPCAD') {
        ShowHTML('    alert(\'Você deve selecionar pelo menos uma atividade!\'); ');
      } else {
        ShowHTML('    alert(\'Você deve selecionar pelo menos uma demanda eventual!\'); ');
      }
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao.disabled=true;');
      ValidateClose();
    }
  }
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } elseif ($O == 'I' && Nvl($p_assunto, '') == '') {
    BodyOpenClean('onLoad="document.Form.p_assunto.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&w_chave_aux=' . $w_chave_aux . '&&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Nº</td>');
    ShowHTML('          <td><b>Projeto</td>');
    ShowHTML('          <td><b>Detalhamento</td>');
    ShowHTML('          <td><b>Início</td>');
    ShowHTML('          <td><b>Fim</td>');
    ShowHTML('          <td><b>Situação</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td nowrap>');
        if (f($row, 'concluida') == 'N') {
          if (f($row, 'fim') < addDays(time(), -1)) {
            ShowHTML('           <img src="' . $conImgAtraso . '" border=0 width=15 heigth=15 align="center">');
          } elseif (f($row, 'aviso_prox_conc') == 'S' && (f($row, 'aviso') <= addDays(time(), -1))) {
            ShowHTML('           <img src="' . $conImgAviso . '" border=0 width=15 height=15 align="center">');
          } else {
            ShowHTML('           <img src="' . $conImgNormal . '" border=0 width=15 height=15 align="center">');
          }
        } else {
          if (f($row, 'fim') < Nvl(f($row, 'fim_real'), f($row, 'fim'))) {
            ShowHTML('           <img src="' . $conImgOkAtraso . '" border=0 width=15 heigth=15 align="center">');
          } else {
            ShowHTML('           <img src="' . $conImgOkNormal . '" border=0 width=15 height=15 align="center">');
          }
        }
        if (nvl(f($row, 'sq_projeto'), '') == '') {
          ShowHTML('        <A class="HL" TARGET="VISUAL" HREF="demanda.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'sq_siw_solicitacao') . '&nbsp;</a>');
        } else {
          ShowHTML('        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'sq_siw_solicitacao') . '&nbsp;</a>');
        }
        ShowHTML('        <td>' . nvl(f($row, 'nm_projeto'), '---') . '</td>');
        if (strlen(Nvl(f($row, 'assunto'), '-')) > 50)
          $w_assunto = substr(Nvl(f($row, 'assunto'), '-'), 0, 50) . '...'; else
          $w_assunto=Nvl(f($row, 'assunto'), '-');
        if (f($row, 'sg_tramite') == 'CA') {
          ShowHTML('        <td title="' . str_replace('\\r\\n', '\\n', str_replace('"', '"', str_replace('\'', '"', f($row, 'assunto')))) . '"><strike>' . $w_assunto . '</strike></td>');
        } else {
          ShowHTML('        <td title="' . str_replace('\\r\\n', '\\n', str_replace('"', '"', str_replace('\'', '"', f($row, 'assunto')))) . '">' . $w_assunto . '</td>');
        }
        if (f($row, 'concluida') == 'N') {
          ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'inicio')) . '</td>');
          ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'fim')) . '</td>');
        } else {
          ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'inicio_real')) . '</td>');
          ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'fim_real')) . '</td>');
        }
        ShowHTML('        <td>' . f($row, 'nm_tramite') . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'sq_solic_missao') . '&w_demanda=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Desvinculação da atividade/demanda eventual." onClick="return(confirm(\'Confirma desvinculação?\'));">Desvincular</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
  } elseif ($O == 'I') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.<br><br>Você pode fazer diversas procuras ou ainda clicar sobre o botão <i>Cancelar</i> para retornar à listagem das tarefas e demandas eventuais já vinculadas.</div><hr>');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><table border=0 cellpadding=0 cellspacing=0 width="100%">');
    ScriptOpen('JavaScript');
    ShowHTML('  function trocaForm(p_sigla) {');
    ShowHTML('    document.Form.action=\'' . $w_dir . $w_pagina . $par . '\';');
    ShowHTML('    document.Form.O.value=\'' . $O . '\';');
    ShowHTML('    document.Form.p_sigla.value=p_sigla;');
    ShowHTML('    document.Form.submit();');
    ShowHTML('  }');
    ScriptClose();
    ShowHTML('<b>Fazer busca em:</b> ');
    if (nvl($p_sigla, 'GDPCAD') == 'GDPCAD') {
      ShowHTML('              <input type="radio" name="p_sigla" value="GDPCAD" checked onclick="trocaForm(\'GDPCAD\');"> Tarefas <input type="radio" name="p_sigla" value="GDCAD" onclick="trocaForm(\'GDCAD\');"> Demandas eventuais');
    } else {
      ShowHTML('              <input type="radio" name="p_sigla" value="GDPCAD" onclick="trocaForm(\'GDPCAD\');"> Tarefas <input type="radio" name="p_sigla" value="GDCAD" checked onclick="trocaForm(\'GDCAD\');"> Demandas eventuais');
    }
    ShowHTML('         <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Critérios de Busca</td>');
    // Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
    if ($p_sigla == 'GDPCAD') {
      ShowHTML('      <tr><td colspan=3><table border=0 width="90%" cellspacing=0><tr valign="top">');
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PJCAD');
      SelecaoProjeto('Pro<u>j</u>eto:', 'J', 'Selecione o projeto da atividade na relação.', $p_projeto, $w_usuario, f($RS, 'sq_menu'), null, null, null, 'p_projeto', f($RS_Menu, 'sq_menu'), 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'p_atividade\'; document.Form.submit();"');
      ShowHTML('      </tr>');
      ShowHTML('      <tr>');
      SelecaoEtapa('Eta<u>p</u>a:', 'P', 'Se necessário, indique a etapa à qual esta atividade deve ser vinculada.', $p_atividade, $p_projeto, null, 'p_atividade', null, null);
      ShowHTML('      </tr>');
      ShowHTML('          </table>');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><font size="1"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" ' . $w_Disabled . ' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="' . $p_chave . '"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Detalh<U>a</U>mento:<br><INPUT ACCESSKEY="N" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="' . $p_assunto . '"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>aís:', 'P', null, $p_pais, null, 'p_pais', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:', 'S', null, $p_uf, $p_pais, null, 'p_uf', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:', 'C', null, $p_cidade, $p_pais, $p_uf, 'p_cidade', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b>Conclusão en<u>t</u>re:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim_i') . ' e <input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim_f') . '</td>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_sigla);
    SelecaoFaseCheck('Recuperar fases:', 'S', null, $p_fase, f($RS, 'sq_menu'), 'p_fase[]', null, null);
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('</FORM>');
    if (Nvl($w_operacao, '') > '') {
      AbreForm('Form1', $w_dir . $w_pagina . 'GRAVA', 'POST', 'return(Validacao1(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
      ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
      ShowHTML(MontaFiltro('POST'));
      // Recupera os registros
      $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_sigla);
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, f($RS, 'sigla'), 4,
                      $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                      $p_unidade, $p_prioridade, $p_ativo, $p_proponente,
                      $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                      $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
      $RS = SortArray($RS, 'assunto', 'asc');
      ShowHTML('<tr><td colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center" valign="top">');
      ShowHTML('          <td><b>&nbsp;</td>');
      ShowHTML('          <td><b>Nº</td>');
      if ($p_sigla == 'GDCAD') {
        ShowHTML('          <td><b>Demanda</td>');
      } else {
        ShowHTML('          <td><b>Projeto</td>');
        ShowHTML('          <td><b>Atividade</td>');
      }
      ShowHTML('          <td><b>Início</td>');
      ShowHTML('          <td><b>Fim</td>');
      ShowHTML('          <td><b>Situação</td>');
      ShowHTML('        </tr>');
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        foreach ($RS as $row) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td align="center"><input type="checkbox" name="w_demanda[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
          ShowHTML('        <td nowrap>');
          if (f($row, 'concluida') == 'N') {
            if (f($row, 'fim') < addDays(time(), -1)) {
              ShowHTML('           <img src="' . $conImgAtraso . '" border=0 width=15 heigth=15 align="center">');
            } elseif (f($row, 'aviso_prox_conc') == 'S' && (f($row, 'aviso') <= addDays(time(), -1))) {
              ShowHTML('           <img src="' . $conImgAviso . '" border=0 width=15 height=15 align="center">');
            } else {
              ShowHTML('           <img src="' . $conImgNormal . '" border=0 width=15 height=15 align="center">');
            }
          } else {
            if (f($row, 'fim') < Nvl(f($row, 'fim_real'), f($row, 'fim'))) {
              ShowHTML('           <img src="' . $conImgOkAtraso . '" border=0 width=15 heigth=15 align="center">');
            } else {
              ShowHTML('           <img src="' . $conImgOkNormal . '" border=0 width=15 height=15 align="center">');
            }
          }
          ShowHTML('        <A class="HL" HREF="' . $w_dir . 'tarefas.php?par=visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações da tarefa.">' . f($row, 'sq_siw_solicitacao') . '</a>');
          if ($p_sigla == 'GDPCAD')
            ShowHTML('        <td>' . f($row, 'nm_projeto') . '</td>');
          if (strlen(Nvl(f($row, 'assunto'), '-')) > 50)
            $w_assunto = substr(Nvl(f($row, 'assunto'), '-'), 0, 50) . '...'; else
            $w_assunto=Nvl(f($row, 'assunto'), '-');
          if (f($row, 'sg_tramite') == 'CA') {
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'assunto')) . '"><strike>' . $w_assunto . '</strike></td>');
          } else {
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'assunto')) . '">' . $w_assunto . '</td>');
          }
          if (f($row, 'concluida') == 'N') {
            ShowHTML('        <td align="center">' . Nvl(FormataDataEdicao(f($row, 'inicio')), '---') . '</td>');
            ShowHTML('        <td align="center">' . Nvl(FormataDataEdicao(f($row, 'fim')), '---') . '</td>');
          } else {
            ShowHTML('        <td align="center">' . Nvl(FormataDataEdicao(f($row, 'inicio_real')), '---') . '</td>');
            ShowHTML('        <td align="center">' . Nvl(FormataDataEdicao(f($row, 'fim_real')), '---') . '</td>');
          }
          ShowHTML('        <td>' . f($row, 'nm_tramite') . '</td>');
          ShowHTML('      </tr>');
        }
      }
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
      ShowHTML('  <tr><td align="center" colspan=3><input class="stb" type="submit" name="Botao" value="Vincular"></td></tr>');
      ShowHTML('</FORM>');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina para informação dos dados financeiros
// -------------------------------------------------------------------------
function DadosFinanceiros() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_adicional = Nvl(formatNumber(f($RS, 'valor_adicional')), 0);
  $w_desc_alimentacao = Nvl(formatNumber(f($RS, 'desconto_alimentacao')), 0);
  $w_desc_transporte = Nvl(formatNumber(f($RS, 'desconto_transporte')), 0);
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  if (theForm.w_aux_alimentacao[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-alimentação, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_alimentacao', 'Valor auxílio-alimentação', '>', '0,00', 'zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value!=\'0,00\' && theForm.w_vlr_alimentacao.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-alimentação, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_aux_transporte[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_transporte.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-transporte, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_transporte', 'Valor auxílio-transporte', '>', '0,00', 'zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_transporte.value!=\'0,00\' && theForm.w_vlr_transporte.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-transporte, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  var i,k;');
  ShowHTML('  for (k=0; k < theForm["w_qtd_diarias[]"].length; k++) {');
  ShowHTML('    var w_campo = \'theForm["w_qtd_diarias"][\'+k+\')"]\';');
  ShowHTML('    if((eval(w_campo + \'.value\')!=\'\')&&(eval(w_campo + \'.value\')==\'\')){');
  ShowHTML('      alert(\'Para cada quantidade de diárias informada, informe o valor unitário correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 5 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 5 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1)');
  ShowHTML('          {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid) {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Quantidade de diárias.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('    var V1, V2;');
  ShowHTML('    V1 = theForm["w_qtd_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V2 = theForm["w_maximo_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V1 = V1.toString().replace(\',\',\'.\'); ');
  ShowHTML('    V2 = V2.toString().replace(\',\',\'.\'); ');
  ShowHTML('    if(parseFloat(V1) > parseFloat(V2)){');
  ShowHTML('      alert(\'Quantidade informada  da \' + (k + 1) + \'ª cidade foi excedido(\'+theForm["w_maximo_diarias[]"][k].value + \').\');');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  for (k=0; k < theForm["w_vlr_diarias[]"].length; k++) {');
  ShowHTML('    if((theForm["w_vlr_diarias[]"][k].value!=\'\')&&(theForm["w_vlr_diarias[]"][k].value==\'\')){');
  ShowHTML('      alert(\'Para cada valor unitário da diária informado, informe a quantidade de diárias correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    var w_campo = \'theForm["w_vlr_diarias"][\'+k+\')"]\';');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 18 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 18 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,.\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1) {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid)  {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Valor unitário da diária.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad="this.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  if ($P1 != 1) {
    ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . ' (' . $w_chave . ')</td>');
    ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
    ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
    $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
    foreach ($RS1 as $row) {
      $RS1 = $row;
      break;
    }
    ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
    ShowHTML('          </TABLE></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('  </table>');
  }
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Benefícios recebidos pelo beneficiário</td>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS, 'valor_alimentacao'), 0) > 0) {
    MontaRadioSN('<b>Auxílio-Alimentação?</b>', $w_aux_alimentacao, 'w_aux_alimentacao');
  } else {
    MontaRadioNS('<b>Auxílio-Alimentação?</b>', $w_aux_alimentacao, 'w_aux_alimentacao');
  }
  ShowHTML('            <td><b>Valor R$: </b><input type="text" name="w_vlr_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . formatNumber(Nvl(f($RS, 'valor_alimentacao'), 0)) . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-alimentação."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS, 'valor_transporte'), 0) > 0) {
    MontaRadioSN('<b>Auxílio-Transporte?</b>', $w_aux_transporte, 'w_aux_transporte');
  } else {
    MontaRadioNS('<b>Auxílio-Transporte?</b>', $w_aux_transporte, 'w_aux_transporte');
  }
  ShowHTML('        <td><b>Valor R$: </b><input type="text" name="w_vlr_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . formatNumber(Nvl(f($RS, 'valor_transporte'), 0)) . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-transporte."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da viagem/cálculo das diárias</td>');
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', $SG);
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  if (count($RS) > 0) {
    $i = 1;
    foreach ($RS as $row) {
      $w_trechos[$i][1] = f($row, 'sq_diaria');
      $w_trechos[$i][2] = f($row, 'cidade_dest');
      $w_trechos[$i][3] = f($row, 'nm_destino');
      $w_trechos[$i][4] = substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 4), 0, -3);
      $w_trechos[$i][5] = substr(FormataDataEdicao(f($row, 'phpdt_saida'), 4), 0, -3);
      $w_trechos[$i][6] = formatNumber(Nvl(f($row, 'quantidade'), 0), 1, ',', '.');
      $w_trechos[$i][7] = formatNumber(Nvl(f($row, 'valor'), 0));
      $w_trechos[$i][8] = f($row, 'saida');
      $w_trechos[$i][9] = f($row, 'chegada');
      if ($i > 1) {
        $w_trechos[$i - 1][5] = substr(FormataDataEdicao(f($row, 'phpdt_saida'), 4), 0, -3);
      }
      $i += 1;
    }
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('         <td><b>Destino</td>');
    ShowHTML('         <td><b>Chegada</td>');
    ShowHTML('         <td><b>Saida</td>');
    ShowHTML('         <td><b>Quantidade de diárias</td>');
    ShowHTML('         <td><b>Valor unitário R$</td>');
    ShowHTML('         </tr>');
    $w_cor = $conTrBgColor;
    $j = $i;
    $i = 1;
    while ($i != ($j - 1)) {
      ShowHTML('<INPUT type="hidden" name="w_sq_diaria[]" value="' . $w_trechos[$i][1] . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="' . $w_trechos[$i][2] . '">');
      ShowHTML('<INPUT type="hidden" name="w_maximo_diarias[]" value="' . (intval($DateDiff['d'][$FormatDateTime[$w_trechos[$i][9]][2]][$FormatDateTime[Nvl($w_trechos[$i + 1][8], $w_trechos[$i][9])][2]]) + intval(1)) . '">');
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
      ShowHTML('       <td>' . $w_trechos[$i][3] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][4] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][5] . '</td>');
      ShowHTML('       <td align="right"><input type="text" name="w_qtd_diarias[]" class="sti" SIZE="10" MAXLENGTH="5" VALUE="' . $w_trechos[$i][6] . '" style="text-align:right;" onKeyDown="FormataValor(this,5,2,event);" title="Informe a quantidade de diárias para este destino."></td>');
      ShowHTML('       <td align="right"><input type="text" name="w_vlr_diarias[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_trechos[$i][7] . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor unitário das diárias para este destino."></td>');
      ShowHTML('     </tr>');
      $i += 1;
    }
    ShowHTML('        <tr><td valign="top" colspan="5" align="center" bgcolor="' . $conTrBgColor . '"><b>Outros valores</td>');
    ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>adicional:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_adicional" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_adicional . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor adicional."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-alimentação:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_desc_alimentacao . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-alimentação."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-transporte:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_desc_transporte . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-transporte."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        </table></td></tr>');
  }
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina para informação do pagamento das diárias
// -------------------------------------------------------------------------
function PagamentoDiaria() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_adicional = Nvl(formatNumber(f($RS, 'valor_adicional')), 0);
  $w_desc_alimentacao = Nvl(formatNumber(f($RS, 'desconto_alimentacao')), 0);
  $w_desc_transporte = Nvl(formatNumber(f($RS, 'desconto_transporte')), 0);
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Pagamento de diárias</title>');
  ScriptOpen('JavaScript');
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  if (theForm.w_aux_alimentacao[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-alimentação, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_alimentacao', 'Valor auxílio-alimentação', '>', '0,00', 'zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_alimentacao.value!=\'0,00\' && theForm.w_vlr_alimentacao.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-alimentação, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  if (theForm.w_aux_transporte[0].checked) {');
  ShowHTML('    if (theForm.w_vlr_transporte.value==\'\') {');
  ShowHTML('      alert(\'Se houver auxílio-transporte, informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  CompValor('w_vlr_transporte', 'Valor auxílio-transporte', '>', '0,00', 'zero');
  ShowHTML('  } else { ');
  ShowHTML('    if (theForm.w_vlr_transporte.value!=\'0,00\' && theForm.w_vlr_transporte.value!=\'\') {');
  ShowHTML('      alert(\'Se não houver auxílio-transporte, não informe o valor!\');');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  var i,k;');
  ShowHTML('  for (k=0; k < theForm["w_qtd_diarias[]"].length; k++) {');
  ShowHTML('    var w_campo = \'theForm["w_qtd_diarias"][\'+k+\')"]\';');
  ShowHTML('    if((eval(w_campo + \'.value\')!=\'\')&&(eval(w_campo + \'.value\')==\'\')){');
  ShowHTML('      alert(\'Para cada quantidade de diárias informada, informe o valor unitário correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 5 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 5 posições no campo Quantidade de diárias.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1)');
  ShowHTML('          {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid) {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Quantidade de diárias.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('    var V1, V2;');
  ShowHTML('    V1 = theForm["w_qtd_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V2 = theForm["w_maximo_diarias[]"][k].value.toString().replace(/\\$|\\./g,\'\');');
  ShowHTML('    V1 = V1.toString().replace(\',\',\'.\'); ');
  ShowHTML('    V2 = V2.toString().replace(\',\',\'.\'); ');
  ShowHTML('    if(parseFloat(V1) > parseFloat(V2)){');
  ShowHTML('      alert(\'Quantidade informada  da \' + (k + 1) + \'ª cidade foi excedido(\'+theForm["w_maximo_diarias[]"][k].value + \').\');');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  for (k=0; k < theForm["w_vlr_diarias[]"].length; k++) {');
  ShowHTML('    if((theForm["w_vlr_diarias[]"][k].value!=\'\')&&(theForm["w_vlr_diarias[]"][k].value==\'\')){');
  ShowHTML('      alert(\'Para cada valor unitário da diária informado, informe a quantidade de diárias correspondente!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    var w_campo = \'theForm["w_vlr_diarias"][\'+k+\')"]\';');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 18 && \' + w_campo + \'.value != ""\')) {');
  ShowHTML('      alert(\'Favor digitar no máximo 18 posições no campo Valor unitário da diária.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    var checkOK = \'0123456789,.\';');
  ShowHTML('    var checkStr = eval(w_campo + \'.value\');');
  ShowHTML('    var allValid = true;');
  ShowHTML('    for (i = 0;  i < checkStr.length;  i++) {');
  ShowHTML('      ch = checkStr.charAt(i);');
  ShowHTML('      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != \'\')) {');
  ShowHTML('        for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('          if (ch==checkOK.charAt(j)){');
  ShowHTML('            break;');
  ShowHTML('          } ');
  ShowHTML('          if (j==checkOK.length-1) {');
  ShowHTML('            allValid = false;');
  ShowHTML('            break;');
  ShowHTML('          }');
  ShowHTML('        }');
  ShowHTML('      }');
  ShowHTML('      if (!allValid)  {');
  ShowHTML('        alert(\'Favor digitar apenas números no campo Valor unitário da diária.\');');
  ShowHTML('        eval(w_campo + \'.focus()\');');
  ShowHTML('        theForm.Botao.disabled=false;');
  ShowHTML('        return (false);');
  ShowHTML('      }');
  ShowHTML('    } ');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad="this.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  if ($P1 != 1) {
    ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . ' (' . $w_chave . ')</td>');
    ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
    ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
    $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
    foreach ($RS1 as $row) {
      $RS1 = $row;
      break;
    }
    ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
    ShowHTML('          </TABLE></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('  </table>');
  }
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Benefícios recebidos pelo beneficiário</td>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS, 'valor_alimentacao'), 0) > 0) {
    MontaRadioSN('<b>Auxílio-Alimentação?</b>', $w_aux_alimentacao, 'w_aux_alimentacao');
  } else {
    MontaRadioNS('<b>Auxílio-Alimentação?</b>', $w_aux_alimentacao, 'w_aux_alimentacao');
  }
  ShowHTML('            <td><b>Valor R$: </b><input type="text" name="w_vlr_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . formatNumber(Nvl(f($RS, 'valor_alimentacao'), 0)) . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-alimentação."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr valign="top">');
  if (Nvl(f($RS, 'valor_transporte'), 0) > 0) {
    MontaRadioSN('<b>Auxílio-Transporte?</b>', $w_aux_transporte, 'w_aux_transporte');
  } else {
    MontaRadioNS('<b>Auxílio-Transporte?</b>', $w_aux_transporte, 'w_aux_transporte');
  }
  ShowHTML('        <td><b>Valor R$: </b><input type="text" name="w_vlr_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . formatNumber(Nvl(f($RS, 'valor_transporte'), 0)) . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do auxílio-transporte."></td>');
  ShowHTML('        </tr>');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da viagem/cálculo das diárias</td>');
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', $SG);
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  if (count($RS) > 0) {
    $i = 1;
    foreach ($RS as $row) {
      $w_trechos[$i][1] = f($row, 'sq_diaria');
      $w_trechos[$i][2] = f($row, 'cidade_dest');
      $w_trechos[$i][3] = f($row, 'nm_destino');
      $w_trechos[$i][4] = substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 4), 0, -3);
      $w_trechos[$i][5] = substr(FormataDataEdicao(f($row, 'phpdt_saida'), 4), 0, -3);
      $w_trechos[$i][6] = formatNumber(Nvl(f($row, 'quantidade'), 0), 1, ',', '.');
      $w_trechos[$i][7] = formatNumber(Nvl(f($row, 'valor'), 0));
      $w_trechos[$i][8] = f($row, 'saida');
      $w_trechos[$i][9] = f($row, 'chegada');
      if ($i > 1) {
        $w_trechos[$i - 1][5] = substr(FormataDataEdicao(f($row, 'phpdt_saida'), 4), 0, -3);
      }
      $i += 1;
    }
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('         <td><b>Destino</td>');
    ShowHTML('         <td><b>Chegada</td>');
    ShowHTML('         <td><b>Saida</td>');
    ShowHTML('         <td><b>Quantidade de diárias</td>');
    ShowHTML('         <td><b>Valor unitário R$</td>');
    ShowHTML('         </tr>');
    $w_cor = $conTrBgColor;
    $j = $i;
    $i = 1;
    while ($i != ($j - 1)) {
      ShowHTML('<INPUT type="hidden" name="w_sq_diaria[]" value="' . $w_trechos[$i][1] . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="' . $w_trechos[$i][2] . '">');
      ShowHTML('<INPUT type="hidden" name="w_maximo_diarias[]" value="' . (intval($DateDiff['d'][$FormatDateTime[$w_trechos[$i][9]][2]][$FormatDateTime[Nvl($w_trechos[$i + 1][8], $w_trechos[$i][9])][2]]) + intval(1)) . '">');
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
      ShowHTML('       <td>' . $w_trechos[$i][3] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][4] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][5] . '</td>');
      ShowHTML('       <td align="right"><input type="text" name="w_qtd_diarias[]" class="sti" SIZE="10" MAXLENGTH="5" VALUE="' . $w_trechos[$i][6] . '" style="text-align:right;" onKeyDown="FormataValor(this,5,2,event);" title="Informe a quantidade de diárias para este destino."></td>');
      ShowHTML('       <td align="right"><input type="text" name="w_vlr_diarias[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_trechos[$i][7] . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor unitário das diárias para este destino."></td>');
      ShowHTML('     </tr>');
      $i += 1;
    }
    ShowHTML('        <tr><td valign="top" colspan="5" align="center" bgcolor="' . $conTrBgColor . '"><b>Outros valores</td>');
    ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>adicional:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_adicional" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_adicional . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor adicional."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-alimentação:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_alimentacao" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_desc_alimentacao . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-alimentação."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrAlternateBgColor . '">');
    ShowHTML('          <td align="right" colspan="4"><b>desconto auxílio-transporte:</b></td>');
    ShowHTML('          <td align="right"><input type="text" name="w_desc_transporte" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_desc_transporte . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o desconto do auxílio-transporte."></td>');
    ShowHTML('        </tr>');
    ShowHTML('        </table></td></tr>');
  }
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina para informação das diárias
// -------------------------------------------------------------------------
function Diarias() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');

  if ($P1 == 1 || strpos($R, 'ALTSOLIC') !== false)
    $w_tipo_reg = 'S'; else
    $w_tipo_reg = 'P';

  // Verifica se a misão permite registro de diárias, hospedagens ou locações de veículos
  if (nvl(f($RS_Solic, 'diaria'), '') == '' && f($RS_Solic, 'hospedagem') == 'N' && f($RS_Solic, 'veiculo') == 'N') {
    Cabecalho();
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center>Na tela de dados gerais, esta solicitação foi indicada como não tendo diárias!</center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
    exit;
  }
  if ($w_troca > '') {
    $w_max_hosp = $_REQUEST['w_max_hosp'];
    $w_max_diaria = $_REQUEST['w_max_diaria'];
    $w_max_veiculo = $_REQUEST['w_max_veiculo'];
    $w_sq_diaria = $_REQUEST['w_sq_diaria'];
    $w_desloc_saida = $_REQUEST['w_desloc_saida'];
    $w_desloc_chegada = $_REQUEST['w_desloc_chegada'];
    $w_cidade_dest = $_REQUEST['w_cidade_dest'];
    $w_nm_destino = $_REQUEST['w_nm_destino'];
    $w_phpdt_chegada = $_REQUEST['w_phpdt_chegada'];
    $w_phpdt_saida = $_REQUEST['w_phpdt_saida'];
    $w_saida = $_REQUEST['w_saida'];
    $w_chegada = $_REQUEST['w_chegada'];
    $w_diaria = $_REQUEST['w_diaria'];
    $w_quantidade = $_REQUEST['w_quantidade'];
    $w_valor = $_REQUEST['w_valor'];
    $w_sg_moeda_diaria = $_REQUEST['w_sg_moeda_diaria'];
    $w_vl_diaria = $_REQUEST['w_vl_diaria'];
    $w_hospedagem = $_REQUEST['w_hospedagem'];
    $w_hospedagem_qtd = $_REQUEST['w_hospedagem_qtd'];
    $w_hospedagem_valor = $_REQUEST['w_hospedagem_valor'];
    $w_sg_moeda_hospedagem = $_REQUEST['w_sg_moeda_hospedagem'];
    $w_vl_diaria_hospedagem = $_REQUEST['w_vl_diaria_hospedagem'];
    $w_veiculo = $_REQUEST['w_veiculo'];
    $w_veiculo_qtd = $_REQUEST['w_veiculo_qtd'];
    $w_veiculo_valor = $_REQUEST['w_veiculo_valor'];
    $w_sg_moeda_veiculo = $_REQUEST['w_sg_moeda_veiculo'];
    $w_vl_diaria_veiculo = $_REQUEST['w_vl_diaria_veiculo'];
    $w_sq_valor_diaria = $_REQUEST['w_sq_valor_diaria'];
    $w_sq_diaria_hospedagem = $_REQUEST['w_sq_diaria_hospedagem'];
    $w_sq_diaria_veiculo = $_REQUEST['w_sq_diaria_veiculo'];
    $w_justificativa_diaria = $_REQUEST['w_justificativa_diaria'];
    $w_justificativa_veiculo = $_REQUEST['w_justificativa_veiculo'];
    $w_compromisso_chegada = $_REQUEST['w_compromisso_chegada'];
    $w_compromisso_saida = $_REQUEST['w_compromisso_saida'];
    $w_meia_ida = $_REQUEST['w_meia_ida'];
    $w_meia_volta = $_REQUEST['w_meia_volta'];
    $w_fin_dia = $_REQUEST['w_fin_dia'];
    $w_rub_dia = $_REQUEST['w_rub_dia'];
    $w_lan_dia = $_REQUEST['w_lan_dia'];
    $w_fin_hsp = $_REQUEST['w_fin_hsp'];
    $w_rub_hsp = $_REQUEST['w_rub_hsp'];
    $w_lan_hsp = $_REQUEST['w_lan_hsp'];
    $w_fin_vei = $_REQUEST['w_fin_vei'];
    $w_rub_vei = $_REQUEST['w_rub_vei'];
    $w_lan_vei = $_REQUEST['w_lan_vei'];
    $w_hos_in = $_REQUEST['w_hos_in'];
    $w_hos_out = $_REQUEST['w_hos_out'];
    $w_hos_observ = $_REQUEST['w_hos_observ'];
    $w_vei_ret = $_REQUEST['w_vei_ret'];
    $w_vei_dev = $_REQUEST['w_vei_dev'];
    $w_destino_nacional = $_REQUEST['w_destino_nacional'];
    $w_saida_internacional = $_REQUEST['w_saida_internacional'];
    $w_chegada_internacional = $_REQUEST['w_chegada_internacional'];
    $w_origem_nacional = $_REQUEST['w_origem_nacional'];
  } elseif ($O == 'L') {
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    $i = 0;
    foreach ($RS as $row) {
      if ($i == 0)
        $w_inicio = f($row, 'saida');
      $w_fim = f($row, 'chegada');
      $i++;
    }
    reset($RS);
  } elseif (strpos('AE', $O) !== false) {
    $w_trechos = unserialize(base64_decode($_REQUEST['w_trechos']));
    $w_sq_diaria = $w_trechos[1];
    $w_desloc_saida = $w_trechos[2];
    $w_desloc_chegada = $w_trechos[3];
    $w_cidade_dest = $w_trechos[4];
    $w_nm_destino = $w_trechos[5];
    $w_phpdt_chegada = $w_trechos[6];
    $w_phpdt_saida = $w_trechos[7];
    $w_saida = $w_trechos[10];
    $w_chegada = $w_trechos[11];
    $w_diaria = $w_trechos[12];
    $w_quantidade = formatNumber($w_trechos[8], 2);
    $w_valor = formatNumber($w_trechos[9]);
    $w_sg_moeda_diaria = $w_trechos[13];
    $w_vl_diaria = formatNumber($w_trechos[14]);
    $w_hospedagem = $w_trechos[15];
    $w_hospedagem_qtd = formatNumber($w_trechos[16], 1);
    $w_hospedagem_valor = formatNumber($w_trechos[17]);
    $w_sg_moeda_hospedagem = $w_trechos[18];
    $w_vl_diaria_hospedagem = formatNumber($w_trechos[19]);
    $w_veiculo = $w_trechos[20];
    $w_veiculo_qtd = formatNumber($w_trechos[21], 1);
    $w_veiculo_valor = formatNumber($w_trechos[22]);
    $w_sg_moeda_veiculo = $w_trechos[23];
    $w_vl_diaria_veiculo = $w_trechos[24];
    $w_sq_valor_diaria = $w_trechos[25];
    $w_sq_diaria_hospedagem = $w_trechos[26];
    $w_sq_diaria_veiculo = $w_trechos[27];
    $w_justificativa_diaria = $w_trechos[28];
    $w_justificativa_veiculo = $w_trechos[29];
    $w_compromisso_chegada = $w_trechos[30];
    $w_compromisso_saida = $w_trechos[31];
    $w_meia_ida = $w_trechos[32];
    $w_meia_volta = $w_trechos[33];
    $w_fin_dia = $w_trechos[34];
    $w_rub_dia = $w_trechos[35];
    $w_lan_dia = $w_trechos[36];
    $w_fin_hsp = $w_trechos[37];
    $w_rub_hsp = $w_trechos[38];
    $w_lan_hsp = $w_trechos[39];
    $w_fin_vei = $w_trechos[40];
    $w_rub_vei = $w_trechos[41];
    $w_lan_vei = $w_trechos[42];
    $w_hos_in = $w_trechos[43];
    $w_hos_out = $w_trechos[44];
    $w_hos_observ = $w_trechos[45];
    $w_vei_ret = $w_trechos[46];
    $w_vei_dev = $w_trechos[47];
    $w_saida_internacional = $w_trechos[48];
    $w_chegada_internacional = $w_trechos[49];
    $w_origem_nacional = $w_trechos[50];
    $w_destino_nacional = $w_trechos[51];
    $w_max_diaria = (toDate(formataDataEdicao($w_phpdt_saida)) - toDate(formataDataEdicao($w_phpdt_chegada))) / 86400;
    $w_max_hosp = ($w_hos_out - $w_hos_in) / 86400;
    $w_max_veiculo = ($w_vei_dev - $w_vei_ret) / 86400;



    // Reconfigura o máximo de diárias para o primeiro trecho
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_saida', 'asc');
    foreach ($RS as $row) {
      if (f($row, 'sq_deslocamento') == $w_desloc_saida) {
        if (f($row, 'saida') != f($row, 'chegada')) {
          $w_max_diaria += f($row, 'dias_deslocamento');
        } else {
          $w_max_diaria += 1;
        }
      }
      break;
    }

    if ($w_meia_ida == 'S')
      $w_max_diaria -= 0.5; elseif ($w_compromisso_chegada == 'N')
      $w_max_diaria -= 0.5;
    if ($w_meia_volta == 'S')
      $w_max_diaria -= 0.5; elseif ($w_compromisso_saida == 'N')
      $w_max_diaria -= 0.5;
  }
  // Recupera as possibilidades de vinculação financeira para diárias, hospedagens e locações de veículo
  $sql = new db_getPD_Financeiro; $RS_Fin_Dia = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, 'S', null, null, null, null, null, null, null);
  $sql = new db_getPD_Financeiro; $RS_Fin_Hsp = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, null, 'S', null, null, null, null, null, null);
  $sql = new db_getPD_Financeiro; $RS_Fin_Vei = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, null, null, 'S', null, null, null, null, null);

  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Diárias</title>');
  if ($O == 'L') {
    ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
    ShowHTML('  function altera (solic, texto) {');
    ShowHTML('    document.Form.w_chave.value=solic;');
    ShowHTML('    document.Form.w_trechos.value=texto;');
    ShowHTML('    document.Form.submit();');
    ShowHTML('  }');
    ShowHTML('</SCRIPT>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('function marcaDiaria() { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_diaria==undefined) return true;');
    ShowHTML('  if (obj.w_diaria[0].checked) {');
    ShowHTML('    $(obj.w_justificativa_diaria).attr("class","STI");');
    if (count($RS_Fin_Dia) > 1) {
      ShowHTML('    $(obj.w_rub_dia).attr("disabled","");');
      ShowHTML('    $(obj.w_rub_dia).attr("class","STIO");');
      ShowHTML('    $(obj.w_lan_dia).attr("disabled","");');
      ShowHTML('    $(obj.w_lan_dia).attr("class","STIO");');
    }
    ShowHTML('  } else {');
    ShowHTML('    $(obj.w_justificativa_diaria).attr("class","STIO");');
    if (count($RS_Fin_Dia) > 1) {
      ShowHTML('    $(obj.w_rub_dia).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_rub_dia).attr("class","STI");');
      ShowHTML('    $(obj.w_lan_dia).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_lan_dia).attr("class","STI");');
    }
    ShowHTML('    obj.w_justificativa_diaria.focus();');
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function marcaHospedagem() { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_hospedagem==undefined) return true;');
    ShowHTML('  if (obj.w_hospedagem[0].checked) {');
    ShowHTML('    $(obj.w_hos_in).attr("readonly","");');
    ShowHTML('    $(obj.w_hos_in).attr("class","STIO");');
    ShowHTML('    $(obj.w_hos_out).attr("readonly","");');
    ShowHTML('    $(obj.w_hos_out).attr("class","STIO");');
    ShowHTML('    if (obj.w_hos_in.value=="") {');
    ShowHTML('      obj.w_hos_in.value=obj.w_dt_chegada.value;');
    ShowHTML('      obj.w_hos_out.value=obj.w_dt_saida.value;');
    ShowHTML('    }');
    if (count($RS_Fin_Hsp) == 1) {
      ShowHTML('    $(obj.w_rub_hsp).attr("disabled","");');
      ShowHTML('    $(obj.w_rub_hsp).attr("class","STIO");');
      ShowHTML('    $(obj.w_lan_hsp).attr("disabled","");');
      ShowHTML('    $(obj.w_lan_hsp).attr("class","STIO");');
    }
    ShowHTML('    obj.w_hos_in.focus();');
    ShowHTML('  } else {');
    ShowHTML('    $(obj.w_hos_in).attr("readonly","readonly");');
    ShowHTML('    $(obj.w_hos_in).attr("class","STI");');
    ShowHTML('    $(obj.w_hos_out).attr("readonly","readonly");');
    ShowHTML('    $(obj.w_hos_out).attr("class","STI");');
    ShowHTML('    obj.w_hos_in.value="";');
    ShowHTML('    obj.w_hos_out.value="";');
    if (count($RS_Fin_Hsp) == 1) {
      ShowHTML('    $(obj.w_rub_hsp).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_rub_hsp).attr("class","STI");');
      ShowHTML('    $(obj.w_lan_hsp).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_lan_hsp).attr("class","STI");');
    }
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function marcaLocacao() { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_veiculo==undefined) return true;');
    ShowHTML('  if (obj.w_veiculo[0].checked) {');
    ShowHTML('    if (obj.w_vei_ret.value=="") {');
    ShowHTML('      obj.w_vei_ret.value=obj.w_dt_chegada.value;');
    ShowHTML('      obj.w_vei_dev.value=obj.w_dt_saida.value;');
    ShowHTML('    }');
    ShowHTML('    $(obj.w_vei_ret).attr("readonly","");');
    ShowHTML('    $(obj.w_vei_ret).attr("class","STIO");');
    ShowHTML('    $(obj.w_vei_dev).attr("readonly","");');
    ShowHTML('    $(obj.w_vei_dev).attr("class","STIO");');
    ShowHTML('    $(obj.w_justificativa_veiculo).attr("readonly","");');
    ShowHTML('    $(obj.w_justificativa_veiculo).attr("class","STIO");');
    if (count($RS_Fin_Vei) == 1) {
      ShowHTML('    $(obj.w_rub_vei).attr("disabled","");');
      ShowHTML('    $(obj.w_rub_vei).attr("class","STIO");');
      ShowHTML('    $(obj.w_lan_vei).attr("disabled","");');
      ShowHTML('    $(obj.w_lan_vei).attr("class","STIO");');
    }
    ShowHTML('    obj.w_vei_ret.focus();');
    ShowHTML('  } else {');
    ShowHTML('    $(obj.w_vei_ret).attr("readonly","readonly");');
    ShowHTML('    $(obj.w_vei_ret).attr("class","STI");');
    ShowHTML('    $(obj.w_vei_dev).attr("readonly","readonly");');
    ShowHTML('    $(obj.w_vei_dev).attr("class","STI");');
    ShowHTML('    $(obj.w_justificativa_veiculo).attr("readonly","readonly");');
    ShowHTML('    $(obj.w_justificativa_veiculo).attr("class","STI");');
    ShowHTML('    obj.w_vei_ret.value="";');
    ShowHTML('    obj.w_vei_dev.value="";');
    ShowHTML('    obj.w_justificativa_veiculo.value="";');
    if (count($RS_Fin_Vei) == 1) {
      ShowHTML('    $(obj.w_rub_vei).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_rub_vei).attr("class","STI");');
      ShowHTML('    $(obj.w_lan_vei).attr("disabled","disabled");');
      ShowHTML('    $(obj.w_lan_vei).attr("class","STI");');
    }
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function calculaDiaria(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_diaria[0].checked) {');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    var w_res = parseFloat(w_val*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_valor.value="0,00";');
    ShowHTML('    else obj.w_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function calculaHospedagem(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_hospedagem[0].checked) {');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria_hospedagem.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    w_res = parseFloat(w_val*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_hospedagem_valor.value="0,00";');
    ShowHTML('    else obj.w_hospedagem_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('  }');
    ShowHTML('}');
    ShowHTML('function calculaLocacao(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('  if (obj.w_veiculo[0].checked) {');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria.value;');
    ShowHTML('    var w_per = obj.w_vl_diaria_veiculo.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    w_per = replaceAll(w_per,".","");');
    ShowHTML('    w_per = replaceAll(w_per,",",".");');
    ShowHTML('    w_res = parseFloat(w_val*w_per*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_veiculo_valor.value="0,00";');
    ShowHTML('    else obj.w_veiculo_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('  }');
    ShowHTML('}');

    FormataData();
    CheckBranco();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (nvl(f($RS_Solic, 'diaria'), '') != '') {
      ShowHTML('  if (theForm.w_diaria[0].checked) {');
      if (count($RS_Fin_Dia) > 1) {
        ShowHTML('    if(theForm.w_rub_dia.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar a rubrica para pagamento de diárias!");');
        ShowHTML('      theForm.w_rub_dia.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_rub_dia', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
        ShowHTML('    if(theForm.w_lan_dia.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de diárias!");');
        ShowHTML('      theForm.w_lan_dia.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_lan_dia', 'Tipo de lançamento para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
      }
      ShowHTML('  } else {');
      Validate('w_justificativa_diaria', 'Observações / Justificativa para não pagamento de diárias', '', '1', 3, 500, '1', '1');
      ShowHTML('  }');
    }
    if (nvl($w_destino_nacional, '') == 'S') {
      ShowHTML('  if (theForm.w_hospedagem[0].checked) {');
      Validate('w_hos_in', 'Data de check in', 'DATA', '1', 10, 10, '', '0123456789/');
      CompData('w_hos_in', 'Data de check in', '>=', 'w_dt_chegada', 'Chegada à localidade');
      Validate('w_hos_out', 'Data de check out', 'DATA', '1', 10, 10, '', '0123456789/');
      CompData('w_hos_out', 'Data de check out', '>=', 'w_hos_in', 'Data de check in');
      CompData('w_hos_in', 'Data de check out', '<=', 'w_dt_saida', 'Saída da localidade');
      Validate('w_hos_observ', 'Observações / Justificativa para não pagamento de hospedagem', '1', 1, 5, 255, '1', '1');
      ShowHTML('  var w_data, w_data1, w_data2;');
      ShowHTML('  w_data = theForm.w_hos_in.value;');
      ShowHTML('  w_data = w_data.substr(3,2) + "/" + w_data.substr(0,2) + "/" + w_data.substr(6,4);');
      ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
      ShowHTML('  w_data = theForm.w_hos_out.value;');
      ShowHTML('  w_data = w_data.substr(3,2) + "/" + w_data.substr(0,2) + "/" + w_data.substr(6,4);');
      ShowHTML('  w_data2= new Date(Date.parse(w_data));');
      ShowHTML('  var MinMilli = 1000 * 60;');
      ShowHTML('  var HrMilli = MinMilli * 60;');
      ShowHTML('  var DyMilli = HrMilli * 24;');
      ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
      ShowHTML('  theForm.w_hospedagem_qtd.value=Days+",0";');
      if (count($RS_Fin_Hsp) > 1) {
        ShowHTML('    if(theForm.w_rub_hsp.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar a rubrica para pagamento de hospedagens!");');
        ShowHTML('      theForm.w_rub_hsp.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_rub_hsp', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
        ShowHTML('    if(theForm.w_lan_hsp.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de hospedagens!");');
        ShowHTML('      theForm.w_lan_hsp.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_lan_hsp', 'Tipo de lançamento para pagamento de hospedagens', 'SELECT', '', 1, 18, '', '1');
      }
      ShowHTML('  } else {');
      Validate('w_hos_observ', 'Observações / Justificativa para não pagamento de hospedagem', '1', '1', 3, 500, '1', '1');
      ShowHTML('  }');
    }

    // Validação para locação de veículo
    ShowHTML('  if (theForm.w_veiculo[0].checked) {');
    Validate('w_vei_ret', 'Data de retirada', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_vei_ret', 'Data de retirada', '>=', 'w_dt_chegada', 'Chegada à localidade');
    Validate('w_vei_dev', 'Data de devolução', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_vei_dev', 'Data de devolução', '>=', 'w_vei_ret', 'Data de retirada');
    CompData('w_vei_dev', 'Data de retirada', '<=', 'w_dt_saida', 'Saída da localidade');
    Validate('w_justificativa_veiculo', 'Justificativa para locação de veículo', '', '', 3, 500, '1', '1');
    ShowHTML('    if(theForm.w_justificativa_veiculo.value=="") {');
    ShowHTML('      alert("Favor informar a justificativa para locação de veículo!");');
    ShowHTML('      theForm.w_justificativa_veiculo.focus();');
    ShowHTML('      return (false);');
    ShowHTML('    }');
    ShowHTML('  w_data = theForm.w_vei_ret.value;');
    ShowHTML('  w_data = w_data.substr(3,2) + "/" + w_data.substr(0,2) + "/" + w_data.substr(6,4);');
    ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
    ShowHTML('  w_data = theForm.w_vei_dev.value;');
    ShowHTML('  w_data = w_data.substr(3,2) + "/" + w_data.substr(0,2) + "/" + w_data.substr(6,4);');
    ShowHTML('  w_data2= new Date(Date.parse(w_data));');
    ShowHTML('  var MinMilli = 1000 * 60;');
    ShowHTML('  var HrMilli = MinMilli * 60;');
    ShowHTML('  var DyMilli = HrMilli * 24;');
    ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli))+1;');
    ShowHTML('  var Qtd  = parseFloat(theForm.w_quantidade.value.replace(",","."));');
    ShowHTML('  theForm.w_veiculo_qtd.value=Days+",0";');

    if (count($RS_Fin_Vei) > 1) {
      ShowHTML('    if(theForm.w_rub_vei.selectedIndex==0) {');
      ShowHTML('      alert("Favor informar a rubrica para pagamento de locações de veículo!");');
      ShowHTML('      theForm.w_rub_vei.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      Validate('w_rub_vei', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
      ShowHTML('    if(theForm.w_lan_vei.selectedIndex==0) {');
      ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de locações de veículo!");');
      ShowHTML('      theForm.w_lan_vei.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      Validate('w_lan_vei', 'Tipo de lançamento para pagamento de locações de veículo', 'SELECT', '', 1, 18, '', '1');
    }
    ShowHTML('  }');

    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($O != 'L') {
    BodyOpen('onLoad="this.focus(); marcaDiaria(); marcaHospedagem(); marcaLocacao();"');
  } else {
    BodyOpen('onLoad="this.focus();"');
  }
  if ($P1 == 1) {
    ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center>');
  } else {
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
    $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
    foreach ($RS1 as $row) {
      $RS1 = $row;
      break;
    }
    ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
    ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
    ShowHTML('            <tr><td colspan="4">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
    ShowHTML('          </TABLE></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('  </table>');
  }
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('      <table width="99%" border="0">');
    ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Cálculo das diárias (Categoria ' . f($RS_Solic, 'nm_diaria') . ')</td>');
    if (count($RS) > 0) {
      $i = 1;
      foreach ($RS as $row) {
        $w_trechos[$i][1] = f($row, 'sq_diaria');
        $w_trechos[$i][2] = f($row, 'sq_deslocamento');
        $w_trechos[$i][3] = f($row, 'sq_deslocamento');
        $w_trechos[$i][4] = f($row, 'cidade_dest');
        $w_trechos[$i][5] = f($row, 'nm_destino');
        $w_trechos[$i][6] = f($row, 'phpdt_chegada');
        $w_trechos[$i][7] = f($row, 'phpdt_saida');
        $w_trechos[$i][8] = Nvl(f($row, 'quantidade'), 0);
        $w_trechos[$i][9] = Nvl(f($row, 'valor'), 0);
        $w_trechos[$i][10] = f($row, 'saida');
        $w_trechos[$i][11] = f($row, 'chegada');
        $w_trechos[$i][12] = f($row, 'diaria');
        $w_trechos[$i][13] = f($row, 'sg_moeda_diaria');
        $w_trechos[$i][14] = f($row, 'vl_diaria');
        $w_trechos[$i][15] = f($row, 'hospedagem');
        $w_trechos[$i][16] = Nvl(f($row, 'hospedagem_qtd'), 0);
        $w_trechos[$i][17] = Nvl(f($row, 'hospedagem_valor'), 0);
        $w_trechos[$i][18] = f($row, 'sg_moeda_hospedagem');
        $w_trechos[$i][19] = f($row, 'vl_diaria_hospedagem');
        $w_trechos[$i][20] = f($row, 'veiculo');
        $w_trechos[$i][21] = Nvl(f($row, 'veiculo_qtd'), 0);
        $w_trechos[$i][22] = Nvl(f($row, 'veiculo_valor'), 0);
        $w_trechos[$i][23] = f($row, 'sg_moeda_veiculo');
        $w_trechos[$i][24] = f($row, 'vl_diaria_veiculo');
        $w_trechos[$i][25] = f($row, 'sq_valor_diaria');
        $w_trechos[$i][26] = f($row, 'sq_diaria_hospedagem');
        $w_trechos[$i][27] = f($row, 'sq_diaria_veiculo');
        $w_trechos[$i][28] = f($row, 'justificativa_diaria');
        $w_trechos[$i][29] = f($row, 'justificativa_veiculo');
        $w_trechos[$i][30] = f($row, 'compromisso');
        $w_trechos[$i][31] = f($row, 'compromisso');
        $w_trechos[$i][32] = 'N';
        $w_trechos[$i][33] = 'N';
        $w_trechos[$i][34] = f($row, 'sq_fin_dia');
        $w_trechos[$i][35] = f($row, 'sq_rub_dia');
        $w_trechos[$i][36] = f($row, 'sq_lan_dia');
        $w_trechos[$i][37] = f($row, 'sq_fin_hsp');
        $w_trechos[$i][38] = f($row, 'sq_rub_hsp');
        $w_trechos[$i][39] = f($row, 'sq_lan_hsp');
        $w_trechos[$i][40] = f($row, 'sq_fin_vei');
        $w_trechos[$i][41] = f($row, 'sq_rub_vei');
        $w_trechos[$i][42] = f($row, 'sq_lan_vei');
        $w_trechos[$i][43] = f($row, 'hospedagem_checkin');
        $w_trechos[$i][44] = f($row, 'hospedagem_checkout');
        $w_trechos[$i][45] = f($row, 'hospedagem_observacao');
        $w_trechos[$i][46] = f($row, 'veiculo_retirada');
        $w_trechos[$i][47] = f($row, 'veiculo_devolucao');
        $w_trechos[$i][48] = f($row, 'saida_internacional');
        $w_trechos[$i][49] = f($row, 'chegada_internacional');
        $w_trechos[$i][50] = f($row, 'origem_nacional');
        $w_trechos[$i][51] = f($row, 'destino_nacional');
        // Cria array para guardar o valor total por moeda
        if ($w_trechos[$i][13] > '')
          $w_total[$w_trechos[$i][13]] = 0;
        if ($w_trechos[$i][18] > '')
          $w_total[$w_trechos[$i][18]] = 0;
        if ($w_trechos[$i][12] > '')
          $w_total[$w_trechos[$i][23]] = 0;
        if ($i == 1) {
          // Se a primeira saída for após as 18:00, deduz meia diária
          if (intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_saida'), 2))) > 180000) {
            $w_trechos[$i][32] = 'S';
          }
        } else {
          // Se a última chegada for até 12:00, deduz meia diária
          if ($i == count($RS) && intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_chegada'), 2))) <= 120000) {
            $w_trechos[$i - 1][33] = 'S';
          }
          $w_trechos[$i - 1][3] = f($row, 'sq_deslocamento');
          $w_trechos[$i - 1][7] = f($row, 'phpdt_saida');
          $w_trechos[$i - 1][31] = f($row, 'compromisso');
        }
        $i += 1;
      }
      ShowHTML('     <tr><td align="center" colspan="2">');
      ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('           <td><b>Destino</td>');
      ShowHTML('           <td><b>Chegada</td>');
      ShowHTML('           <td><b>Saída</td>');
      ShowHTML('           <td><b>Operações</td>');
      ShowHTML('         </tr>');
      $w_cor = $conTrBgColor;
      $j = $i;
      $i = 1;
      $w_diarias = 0;
      $w_locacoes = 0;
      $w_hospedagens = 0;
      $w_tot_local = 0;
      AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return true;', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, 'A');
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('       <input type="hidden" name="w_chave" value="">');
      ShowHTML('       <input type="hidden" name="w_trechos" value="">');

      while ($i != ($j - 1)) {
        $w_max_hosp = floor(($w_trechos[$i][44] - $w_trechos[$i][43]) / 86400);
        $w_max_diaria = ceil(($w_trechos[$i][7] - $w_trechos[$i][6]) / 86400);
        $w_max_veiculo = ceil(($w_trechos[$i][47] - $w_trechos[$i][46]) / 86400);

        if (($i > 0 && $i < ($j - 1) && (($w_trechos[$i][51] == 'N' && toDate(FormataDataEdicao($w_trechos[$i][6])) == $w_fim) ||
                ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim)
                )
                ) ||
                ($w_max_hosp >= 0 &&
                $w_trechos[$i][48] == 0 &&
                $w_trechos[$i][49] == 0 &&
                ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim))
        ) {
          $w_diarias = nvl($w_trechos[$i][8], 0) * nvl($w_trechos[$i][9], 0);
          $w_locacoes = (-1 * nvl($w_trechos[$i][9], 0) * nvl($w_trechos[$i][22], 0) / 100 * nvl($w_trechos[$i][21], 0));
          $w_hospedagens = nvl($w_trechos[$i][16], 0) * nvl($w_trechos[$i][17], 0);

          if ($w_diarias > 0)
            $w_total[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes <> 0)
            $w_total[$w_trechos[$i][23]] += $w_locacoes;

          $w_tot_local = $w_diarias + $w_locacoes;

          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
          ShowHTML('       <td>' . $w_trechos[$i][5]);
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Saída após 18:00');
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Chegada até 12:00');
          if ($w_trechos[$i][30] == 'N')
            ShowHTML('<br>Sem compromisso na ida');
          if ($w_trechos[$i][31] == 'N')
            ShowHTML('<br>Sem compromisso na volta');
          ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][6], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][7], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td>');
          ShowHTML('          <A class="HL" HREF="javascript:altera(' . f($row, 'sq_siw_solicitacao') . ',\'' . base64_encode(serialize($w_trechos[$i])) . '\');" title="Informa as diárias">Informar</A>&nbsp');
          ShowHTML('       </td>');
        }
        $i += 1;
      }
      ShowHTML('       </FORM>');
      ShowHTML('        </table></td></tr>');
    }
  } else {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, ((nvl($R, '') != '') ? $R : $w_pagina . $par), $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria" value="' . $w_sq_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_reg" value="' . $w_tipo_reg . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cidade" value="' . $w_cidade_dest . '">');
    ShowHTML('<INPUT type="hidden" name="w_desloc_saida" value="' . $w_desloc_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_desloc_chegada" value="' . $w_desloc_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_hosp" value="' . $w_max_hosp . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_diaria" value="' . $w_max_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_veiculo" value="' . $w_max_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_valor_diaria" value="' . $w_sq_valor_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria_hospedagem" value="' . $w_sq_diaria_hospedagem . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria_veiculo" value="' . $w_sq_diaria_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_compromisso_chegada" value="' . $w_compromisso_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_compromisso_saida" value="' . $w_compromisso_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_meia_ida" value="' . $w_meia_ida . '">');
    ShowHTML('<INPUT type="hidden" name="w_meia_volta" value="' . $w_meia_volta . '">');
    ShowHTML('<INPUT type="hidden" name="w_cidade_dest" value="' . $w_cidade_dest . '">');
    ShowHTML('<INPUT type="hidden" name="w_nm_destino" value="' . $w_nm_destino . '">');
    ShowHTML('<INPUT type="hidden" name="w_phpdt_chegada" value="' . $w_phpdt_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_phpdt_saida" value="' . $w_phpdt_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_dt_chegada" value="' . formataDataEdicao($w_phpdt_chegada) . '">');
    ShowHTML('<INPUT type="hidden" name="w_dt_saida" value="' . formataDataEdicao($w_phpdt_saida) . '">');
    ShowHTML('<INPUT type="hidden" name="w_saida" value="' . $w_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_chegada" value="' . $w_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_diaria" value="' . $w_sg_moeda_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_hospedagem" value="' . $w_sg_moeda_hospedagem . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_veiculo" value="' . $w_sg_moeda_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_destino_nacional" value="' . $w_destino_nacional . '">');
    ShowHTML('<INPUT type="hidden" name="w_chegada_internacional" value="' . $w_chegada_internacional . '">');
    ShowHTML('<INPUT type="hidden" name="w_origem" value="SOLIC">');

    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '"><td><table border=0 width="100%">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>Cidade:<br><b>' . $w_nm_destino . '</b></td>');
    ShowHTML('            <td>Chegada:<br><b>' . substr(FormataDataEdicao($w_phpdt_chegada, 4), 0, -3) . '</b></td>');
    ShowHTML('            <td>Saída:<br><b>' . substr(FormataDataEdicao($w_phpdt_saida, 4), 0, -3) . '</b></td>');
    ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');

    // Define as quantidades conforme regras
    $w_quantidade = formatNumber($w_max_diaria, 2);
    $w_hospedagem_qtd = formatNumber($w_max_hosp, 1);
    $w_veiculo_qtd = formatNumber($w_max_veiculo, 1);

    // Calcula os valores a serem pagos
    $w_valor = formatNumber($w_quantidade * $w_vl_diaria);
    $w_hospedagem_valor = formatNumber($w_hospedagem_qtd * $w_vl_diaria_hospedagem);
    $w_veiculo_valor = formatNumber($w_veiculo_qtd * $w_vl_diaria_veiculo * $w_vl_diaria / 100);

    if (nvl($w_sq_valor_diaria, '') != '' && nvl(f($RS_Solic, 'diaria'), '') != '') {
      ShowHTML('          <tr valign="top">');
      MontaRadioSN('<b>Diárias?</b>', $w_diaria, 'w_diaria', 'Informe Sim se desejar pagamento das diárias.', null, 'onClick="marcaDiaria()"');
      ShowHTML('            <td colspan="3" valign="top"><b>Observações / <u>J</u>ustificativa para o não pagamento de diárias:</b><br><textarea ' . (($w_diaria == 'N') ? 'class="STIO"' : 'class="STI"') . ' accesskey="J" name="w_justificativa_diaria" class="STI" ROWS=5 cols=75 title="Informe observações que julgar necessárias. Se não desejar pagamento de diárias, informe o motivo.">' . $w_justificativa_diaria . '</TEXTAREA></td>');
      ShowHTML('<INPUT type="hidden" name="w_vl_diaria" value="' . $w_vl_diaria . '">');
      ShowHTML('<INPUT type="hidden" name="w_quantidade" value="' . $w_quantidade . '">');
      ShowHTML('<INPUT type="hidden" name="w_valor" value="' . $w_valor . '">');
      if (count($RS_Fin_Dia) > 1) {
        ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('          <tr><td>');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_dia, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_dia', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_dia\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_dia, null, $w_cliente, 'w_lan_dia', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_dia, 10, '0', STR_PAD_LEFT) . 'D', null);
        ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
      } elseif (count($RS_Fin_Dia) == 1) {
        foreach ($RS_Fin_Dia as $row) {
          $RS_Fin_Dia = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_dia" value="' . f($RS_Fin_Dia, 'chave') . '">');
      }
    }
    $w_Disabled = '';
    if (nvl($w_destino_nacional, '') == 'S') {
      ShowHTML('          <tr valign="top">');
      MontaRadioNS('<b>Hospedagem?</b>', $w_hospedagem, 'w_hospedagem', 'Informe Sim se desejar pagamento das hospedagens.', null, 'onClick="marcaHospedagem()"');
      ShowHTML('          <td><b><u>C</u>heck in:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_hos_in" ' . (($w_hospedagem == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' SIZE="10" MAXLENGTH="10" VALUE="' . formataDataEdicao($w_hos_in) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data_saida') . '</td>');
      ShowHTML('          <td><b><u>C</u>heck out:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="w_hos_out" ' . (($w_hospedagem == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' SIZE="10" MAXLENGTH="10" VALUE="' . formataDataEdicao($w_hos_out) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data_saida') . '</td>');
      ShowHTML('        <tr><td><td colspan="3" valign="top"><b><u>O</u>bservações / Justificativa para o não pagamento de hospedagem:</b><br><textarea class="STI" accesskey="O" name="w_hos_observ" ROWS=3 cols=75 title="Informe observações que julgar necessárias. Se não desejar pagamento de hospedagens, informe o motivo.">' . $w_hos_observ . '</TEXTAREA></td>');
      ShowHTML('<INPUT type="hidden" name="w_vl_diaria_hospedagem" value="' . $w_vl_diaria_hospedagem . '">');
      ShowHTML('<INPUT type="hidden" name="w_hospedagem_qtd" value="' . $w_hospedagem_qtd . '">');
      ShowHTML('<INPUT type="hidden" name="w_hospedagem_valor" value="' . $w_hospedagem_valor . '">');
      if (count($RS_Fin_Hsp) > 1) {
        ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('          <tr><td>');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_hsp, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_hsp', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_hsp\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_hsp, null, $w_cliente, 'w_lan_hsp', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_hsp, 10, '0', STR_PAD_LEFT) . 'D', null);
        ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
      } elseif (count($RS_Fin_Hsp) == 1) {
        foreach ($RS_Fin_Hsp as $row) {
          $RS_Fin_Hsp = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_hsp" value="' . f($RS_Fin_Hsp, 'chave') . '">');
      }
    }
    // Tratamento para locação de veículos
    $w_Disabled = '';
    ShowHTML('          <tr valign="top">');
    MontaRadioNS('<b>Veículo?</b>', $w_veiculo, 'w_veiculo', 'Informe Sim se desejar locação de veículo.', null, 'onClick="marcaLocacao()"');
    ShowHTML('          <td><b><u>R</u>etirada:</b><br><input accesskey="C" type="text" name="w_vei_ret" ' . (($w_veiculo == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' SIZE="10" MAXLENGTH="10" VALUE="' . formataDataEdicao($w_vei_ret) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data_saida') . '</td>');
    ShowHTML('          <td><b><u>D</u>evolução:</b><br><input accesskey="C" type="text" name="w_vei_dev" ' . (($w_veiculo == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' SIZE="10" MAXLENGTH="10" VALUE="' . formataDataEdicao($w_vei_dev) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> ' . ExibeCalendario('Form', 'w_data_saida') . '</td>');
    ShowHTML('          <tr><td><td colspan="3" valign="top"><b><u>J</u>ustificativa para locação de veículo:</b><br><textarea ' . (($w_veiculo == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' accesskey="J" name="w_justificativa_veiculo" class="STI" ROWS=5 cols=75 title="É obrigatório justificar, neste campo, a necessidade de locação de veículo. Caso contrário, deixe este campo em branco.">' . $w_justificativa_veiculo . '</TEXTAREA></td>');
    ShowHTML('<INPUT type="hidden" name="w_vl_diaria_veiculo" value="' . $w_vl_diaria_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_veiculo_qtd" value="' . $w_veiculo_qtd . '">');
    ShowHTML('<INPUT type="hidden" name="w_veiculo_valor" value="' . $w_veiculo_valor . '">');
    if (count($RS_Fin_Vei) > 1) {
      ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
      ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('          <tr><td>');
      SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_vei, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_vei', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_vei\'; document.Form.submit();"');
      SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_vei, null, $w_cliente, 'w_lan_vei', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_vei, 10, '0', STR_PAD_LEFT) . 'D', null);
      ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
    } elseif (count($RS_Fin_Vei) == 1) {
      foreach ($RS_Fin_Vei as $row) {
        $RS_Fin_Vei = $row;
        break;
      }
      ShowHTML('<INPUT type="hidden" name="w_fin_vei" value="' . f($RS_Fin_Vei, 'chave') . '">');
    }

    ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');
    ShowHTML('          <tr><td align="center" colspan=4>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('        </table>');
    ShowHTML('</FORM>');
  }
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina para solicitação das diárias
// -------------------------------------------------------------------------
function Diarias_Solic() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');

  if (f($RS_Solic, 'sg_tramite') != 'VP')
    $w_tipo_reg = 'S'; else
    $w_tipo_reg = 'P';

  // Verifica se a misão permite registro de diárias, hospedagens ou locações de veículos
  if (nvl(f($RS_Solic, 'diaria'), '') == '' && f($RS_Solic, 'hospedagem') == 'N' && f($RS_Solic, 'veiculo') == 'N') {
    Cabecalho();
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center>Na tela de dados gerais, esta solicitação foi indicada como não tendo diárias!</center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
    exit;
  }
  if ($w_troca > '') {
    $w_max_hosp = $_REQUEST['w_max_hosp'];
    $w_max_diaria = $_REQUEST['w_max_diaria'];
    $w_sq_diaria = $_REQUEST['w_sq_diaria'];
    $w_desloc_saida = $_REQUEST['w_desloc_saida'];
    $w_desloc_chegada = $_REQUEST['w_desloc_chegada'];
    $w_cidade_dest = $_REQUEST['w_cidade_dest'];
    $w_nm_destino = $_REQUEST['w_nm_destino'];
    $w_phpdt_chegada = $_REQUEST['w_phpdt_chegada'];
    $w_phpdt_saida = $_REQUEST['w_phpdt_saida'];
    $w_saida = $_REQUEST['w_saida'];
    $w_chegada = $_REQUEST['w_chegada'];
    $w_diaria = $_REQUEST['w_diaria'];
    $w_quantidade = $_REQUEST['w_quantidade'];
    $w_valor = $_REQUEST['w_valor'];
    $w_sg_moeda_diaria = $_REQUEST['w_sg_moeda_diaria'];
    $w_vl_diaria = $_REQUEST['w_vl_diaria'];
    $w_hospedagem = $_REQUEST['w_hospedagem'];
    $w_hospedagem_qtd = $_REQUEST['w_hospedagem_qtd'];
    $w_hospedagem_valor = $_REQUEST['w_hospedagem_valor'];
    $w_sg_moeda_hospedagem = $_REQUEST['w_sg_moeda_hospedagem'];
    $w_vl_diaria_hospedagem = $_REQUEST['w_vl_diaria_hospedagem'];
    $w_veiculo = $_REQUEST['w_veiculo'];
    $w_veiculo_qtd = $_REQUEST['w_veiculo_qtd'];
    $w_veiculo_valor = $_REQUEST['w_veiculo_valor'];
    $w_sg_moeda_veiculo = $_REQUEST['w_sg_moeda_veiculo'];
    $w_vl_diaria_veiculo = $_REQUEST['w_vl_diaria_veiculo'];
    $w_sq_valor_diaria = $_REQUEST['w_sq_valor_diaria'];
    $w_sq_diaria_hospedagem = $_REQUEST['w_sq_diaria_hospedagem'];
    $w_sq_diaria_veiculo = $_REQUEST['w_sq_diaria_veiculo'];
    $w_justificativa_diaria = $_REQUEST['w_justificativa_diaria'];
    $w_justificativa_veiculo = $_REQUEST['w_justificativa_veiculo'];
    $w_compromisso_chegada = $_REQUEST['w_compromisso_chegada'];
    $w_compromisso_saida = $_REQUEST['w_compromisso_saida'];
    $w_meia_ida = $_REQUEST['w_meia_ida'];
    $w_meia_volta = $_REQUEST['w_meia_volta'];
    $w_fin_dia = $_REQUEST['w_fin_dia'];
    $w_rub_dia = $_REQUEST['w_rub_dia'];
    $w_lan_dia = $_REQUEST['w_lan_dia'];
    $w_fin_hsp = $_REQUEST['w_fin_hsp'];
    $w_rub_hsp = $_REQUEST['w_rub_hsp'];
    $w_lan_hsp = $_REQUEST['w_lan_hsp'];
    $w_fin_vei = $_REQUEST['w_fin_vei'];
    $w_rub_vei = $_REQUEST['w_rub_vei'];
    $w_lan_vei = $_REQUEST['w_lan_vei'];
    $w_hos_in = $_REQUEST['w_hos_in'];
    $w_hos_out = $_REQUEST['w_hos_out'];
    $w_hos_observ = $_REQUEST['w_hos_observ'];
    $w_vei_ret = $_REQUEST['w_vei_ret'];
    $w_vei_dev = $_REQUEST['w_vei_dev'];
    $w_calc_dia_qtd = $_REQUEST['w_calc_dia_qtd'];
    $w_calc_dia_txt = $_REQUEST['w_calc_dia_txt'];
    $w_calc_hsp_qtd = $_REQUEST['w_calc_hsp_qtd'];
    $w_calc_hsp_txt = $_REQUEST['w_calc_hsp_txt'];
    $w_calc_vei_qtd = $_REQUEST['w_calc_vei_qtd'];
    $w_calc_vei_txt = $_REQUEST['w_calc_vei_txt'];
  } elseif ($O == 'L') {
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    $i = 0;
    foreach ($RS as $row) {
      if ($i == 0)
        $w_inicio = f($row, 'saida');
      $w_fim = f($row, 'chegada');
      $i++;
    }
    reset($RS);
  } elseif (strpos('AE', $O) !== false) {
    $w_trechos = unserialize(base64_decode($_REQUEST['w_trechos']));
    $w_sq_diaria = $w_trechos[1];
    $w_desloc_saida = $w_trechos[2];
    $w_desloc_chegada = $w_trechos[3];
    $w_cidade_dest = $w_trechos[4];
    $w_nm_destino = $w_trechos[5];
    $w_phpdt_chegada = $w_trechos[6];
    $w_phpdt_saida = $w_trechos[7];
    $w_saida = $w_trechos[10];
    $w_chegada = $w_trechos[11];
    $w_diaria = $w_trechos[12];
    $w_quantidade = formatNumber($w_trechos[8], 2);
    $w_valor = formatNumber($w_trechos[8] * $w_trechos[9]);
    $w_sg_moeda_diaria = $w_trechos[13];
    $w_vl_diaria = formatNumber($w_trechos[14]);
    $w_hospedagem = $w_trechos[15];
    $w_hospedagem_qtd = formatNumber($w_trechos[16], 1);
    $w_hospedagem_valor = formatNumber($w_trechos[16] * $w_trechos[19]);
    $w_sg_moeda_hospedagem = $w_trechos[18];
    $w_vl_diaria_hospedagem = formatNumber($w_trechos[19]);
    $w_veiculo = $w_trechos[20];
    $w_veiculo_qtd = formatNumber($w_trechos[21], 1);
    $w_veiculo_valor = formatNumber($w_trechos[21] * $w_trechos[24] * $w_trechos[14] / 100);
    $w_sg_moeda_veiculo = $w_trechos[23];
    $w_vl_diaria_veiculo = $w_trechos[24];
    $w_sq_valor_diaria = $w_trechos[25];
    $w_sq_diaria_hospedagem = $w_trechos[26];
    $w_sq_diaria_veiculo = $w_trechos[27];
    $w_justificativa_diaria = $w_trechos[28];
    $w_justificativa_veiculo = $w_trechos[29];
    $w_compromisso_chegada = $w_trechos[30];
    $w_compromisso_saida = $w_trechos[31];
    $w_meia_ida = $w_trechos[32];
    $w_meia_volta = $w_trechos[33];
    $w_fin_dia = $w_trechos[34];
    $w_rub_dia = $w_trechos[35];
    $w_lan_dia = $w_trechos[36];
    $w_fin_hsp = $w_trechos[37];
    $w_rub_hsp = $w_trechos[38];
    $w_lan_hsp = $w_trechos[39];
    $w_fin_vei = $w_trechos[40];
    $w_rub_vei = $w_trechos[41];
    $w_lan_vei = $w_trechos[42];
    $w_hos_in = $w_trechos[43];
    $w_hos_out = $w_trechos[44];
    $w_hos_observ = $w_trechos[45];
    $w_vei_ret = $w_trechos[46];
    $w_vei_dev = $w_trechos[47];
    $w_destino_nacional = $w_trechos[48];
    $w_calc_dia_qtd = $w_trechos[52];
    $w_calc_dia_txt = $w_trechos[53];
    $w_calc_hsp_qtd = $w_trechos[54];
    $w_calc_hsp_txt = $w_trechos[55];
    $w_calc_vei_qtd = $w_trechos[56];
    $w_calc_vei_txt = $w_trechos[57];

    $w_max_diaria = floor((toDate(formataDataEdicao($w_phpdt_saida)) - toDate(formataDataEdicao($w_phpdt_chegada))) / 86400);
    $w_max_hosp = ceil((toDate(formataDataEdicao($w_hos_out)) - toDate(formataDataEdicao($w_hos_in))) / 86400);
    $w_max_veiculo = ceil((toDate(formataDataEdicao($w_vei_dev)) - toDate(formataDataEdicao($w_vei_ret))) / 86400);

    // Reconfigura o máximo de diárias para o primeiro trecho
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_saida', 'asc');
    foreach ($RS as $row) {
      if (f($row, 'saida') != f($row, 'chegada'))
        $w_max_diaria += f($row, 'dias_deslocamento');
      break;
    }

    // Reconfigura o máximo de diárias para o último trecho
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_tipo_reg, $SG);
    $RS = SortArray($RS, 'phpdt_chegada', 'desc');
    if (count($RS) > 2) {
      foreach ($RS as $row) {
        if (f($row, 'sq_deslocamento') == $w_desloc_chegada)
          $w_max_diaria += 1;
        break;
      }
    }

    if ($w_meia_ida == 'S')
      $w_max_diaria -= 0.5; elseif ($w_compromisso_chegada == 'N')
      $w_max_diaria -= 0.5;
    if ($w_meia_volta == 'S')
      $w_max_diaria -= 0.5; elseif ($w_compromisso_saida == 'N')
      $w_max_diaria -= 0.5;
  }
  // Recupera as possibilidades de vinculação financeira para diárias, hospedagens e locações de veículo
  $sql = new db_getPD_Financeiro; $RS_Fin_Dia = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, 'S', null, null, null, null, null, null, null);
  $sql = new db_getPD_Financeiro; $RS_Fin_Hsp = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, null, 'S', null, null, null, null, null, null);
  $sql = new db_getPD_Financeiro; $RS_Fin_Vei = $sql->getInstanceOf($dbms, $w_cliente, null, f($RS_Solic, 'sq_solic_pai'), null, null, null, null, 'S', null, null, null, null, null);

  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Diárias</title>');
  if ($O == 'L') {
    ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
    ShowHTML('  function altera (solic, texto) {');
    ShowHTML('    document.Form.w_chave.value=solic;');
    ShowHTML('    document.Form.w_trechos.value=texto;');
    ShowHTML('    document.Form.submit();');
    ShowHTML('  }');
    ShowHTML('</SCRIPT>');
  } else {
    ScriptOpen('JavaScript');
    toMoney();
    ShowHTML('function calculaDiaria(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    var w_res = parseFloat(w_val*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_valor.value="0,00";');
    ShowHTML('    else obj.w_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('}');
    ShowHTML('function calculaHospedagem(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria_hospedagem.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    w_res = parseFloat(w_val*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_hospedagem_valor.value="0,00";');
    ShowHTML('    else obj.w_hospedagem_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('}');
    ShowHTML('function calculaLocacao(valor) { ');
    ShowHTML('  var obj=document.Form;');
    ShowHTML('    var w_qtd = replaceAll(valor,".","");');
    ShowHTML('    w_qtd = replaceAll(w_qtd,",",".");');
    ShowHTML('    var w_val = obj.w_vl_diaria.value;');
    ShowHTML('    var w_per = obj.w_vl_diaria_veiculo.value;');
    ShowHTML('    w_val = replaceAll(w_val,".","");');
    ShowHTML('    w_val = replaceAll(w_val,",",".");');
    ShowHTML('    w_per = replaceAll(w_per,".","");');
    ShowHTML('    w_per = replaceAll(w_per,",",".");');
    ShowHTML('    w_res = parseFloat(w_val*w_per*w_qtd,2);');
    ShowHTML('    if (w_res==0) obj.w_veiculo_valor.value="0,00";');
    ShowHTML('    else obj.w_veiculo_valor.value = toMoney(w_res,\'BR\');');
    ShowHTML('}');

    FormataValor();
    ValidateOpen('Validacao');
    if ($w_diaria == 'S') {
      Validate('w_quantidade', 'Quantidade de diárias', 'VALOR', '', 3, 5, '', '0123456789,');
      ShowHTML('    if(theForm.w_quantidade.value=="") {');
      ShowHTML('      alert("Favor informar a quantidade de diárias!");');
      ShowHTML('      theForm.w_quantidade.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      //CompValor('w_quantidade','Quantidade de diárias','>','0,0','zero');
      //CompValor('w_quantidade','Quantidade de diárias','<=',formatNumber($w_max_hosp,1),formatNumber($w_max_hosp,1));
      ShowHTML('    if(theForm.w_quantidade.value!=theForm.w_calc_dia_qtd.value && theForm.w_calc_dia_txt.value=="") {');
      ShowHTML('      alert("Informe o motivo da quantidade de diárias ser diferente do valor calculado: ' . formatNumber($w_calc_dia_qtd, 2) . '");');
      ShowHTML('      theForm.w_calc_dia_txt.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      /*
        if ($w_max_hosp!=$w_max_diaria) {
        ShowHTML('    if(parseFloat(theForm.w_quantidade.value.replace(",","."))>parseFloat(theForm.w_max_diaria.value.replace(",",".")) && theForm.w_justificativa_diaria.value=="") {');
        ShowHTML('      alert("A quantidade de diárias solicitada ("+theForm.w_quantidade.value+") é maior que a permitida ("+theForm.w_max_diaria.value.replace(".",",")+").\\nÉ obrigatório justificar!");');
        ShowHTML('      theForm.w_justificativa_diaria.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_justificativa_diaria','Justificativa para diária','','',3,500,'1','1');
        }
       */
      if (count($RS_Fin_Dia) > 1) {
        ShowHTML('    if(theForm.w_rub_dia.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar a rubrica para pagamento de diárias!");');
        ShowHTML('      theForm.w_rub_dia.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_rub_dia', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
        ShowHTML('    if(theForm.w_lan_dia.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de diárias!");');
        ShowHTML('      theForm.w_lan_dia.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_lan_dia', 'Tipo de lançamento para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
      }
    }
    if ($w_hospedagem == 'S') {
      Validate('w_vl_diaria_hospedagem', 'Valor da hospedagem', 'VALOR', '', 4, 10, '', '0123456789,');
      Validate('w_hospedagem_qtd', 'Quantidade de hospedagens', 'VALOR', '', 3, 5, '', '0123456789,');
      ShowHTML('    if(theForm.w_vl_diaria_hospedagem.value=="") {');
      ShowHTML('      alert("Favor informar o valor da hospedagem!");');
      ShowHTML('      theForm.w_vl_diaria_hospedagem.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      CompValor('w_vl_diaria_hospedagem', 'Valor da hospedagem', '>', '0,0', 'zero');
      ShowHTML('    if(theForm.w_vl_diaria_hospedagem.value=="") {');
      ShowHTML('      alert("Favor informar o valor da hospedagem!");');
      ShowHTML('      theForm.w_vl_diaria_hospedagem.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      //CompValor('w_hospedagem_qtd','Quantidade de hospedagens','>','0,0','zero');
      //CompValor('w_hospedagem_qtd','Quantidade de hospedagens','<=',formatNumber($w_max_hosp,1),formatNumber($w_max_hosp,1));
      ShowHTML('    if(theForm.w_hospedagem_qtd.value!=theForm.w_calc_hsp_qtd.value && theForm.w_calc_hsp_txt.value=="") {');
      ShowHTML('      alert("Informe o motivo da quantidade de hospedagens ser diferente do valor calculado: ' . formatNumber($w_calc_hsp_qtd, 1) . '");');
      ShowHTML('      theForm.w_calc_hsp_txt.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      if (count($RS_Fin_Hsp) > 1) {
        ShowHTML('    if(theForm.w_rub_hsp.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar a rubrica para pagamento de hospedagens!");');
        ShowHTML('      theForm.w_rub_hsp.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_rub_hsp', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
        ShowHTML('    if(theForm.w_lan_hsp.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de hospedagens!");');
        ShowHTML('      theForm.w_lan_hsp.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_lan_hsp', 'Tipo de lançamento para pagamento de hospedagens', 'SELECT', '', 1, 18, '', '1');
      }
    }
    if ($w_veiculo == 'S') {
      Validate('w_veiculo_qtd', 'Quantidade de locações', 'VALOR', '', 3, 5, '', '0123456789,');
      ShowHTML('    if(theForm.w_veiculo_qtd.value=="") {');
      ShowHTML('      alert("Favor informar a quantidade de locações!");');
      ShowHTML('      theForm.w_veiculo_qtd.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      //CompValor('w_veiculo_qtd','Quantidade de locações','>','0,0','zero');
      //CompValor('w_veiculo_qtd','Quantidade de locações','<=',formatNumber($w_max_hosp,1),formatNumber($w_max_hosp,1));
      ShowHTML('    if(theForm.w_veiculo_qtd.value!=theForm.w_calc_vei_qtd.value && theForm.w_calc_vei_txt.value=="") {');
      ShowHTML('      alert("Informe o motivo da quantidade de diárias de veículo ser diferente do valor calculado: ' . formatNumber($w_calc_vei_qtd, 1) . '");');
      ShowHTML('      theForm.w_calc_vei_txt.focus();');
      ShowHTML('      return (false);');
      ShowHTML('    }');
      if (count($RS_Fin_Vei) > 1) {
        ShowHTML('    if(theForm.w_rub_vei.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar a rubrica para pagamento de locações de veículo!");');
        ShowHTML('      theForm.w_rub_vei.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_rub_vei', 'Rubrica para pagamento de diárias', 'SELECT', '', 1, 18, '', '1');
        ShowHTML('    if(theForm.w_lan_vei.selectedIndex==0) {');
        ShowHTML('      alert("Favor informar o tipo de lançamento para pagamento de locações de veículo!");');
        ShowHTML('      theForm.w_lan_vei.focus();');
        ShowHTML('      return (false);');
        ShowHTML('    }');
        Validate('w_lan_vei', 'Tipo de lançamento para pagamento de locações de veículo', 'SELECT', '', 1, 18, '', '1');
      }
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($O != 'L') {
    BodyOpen('onLoad="this.focus();"');
  } else {
    BodyOpen('onLoad="this.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  if ($P1 != 1) {
    ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
    ShowHTML('      <table border=1 width="100%">');
    ShowHTML('        <tr><td valign="top" colspan="2">');
    ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
    ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
    ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
    $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
    foreach ($RS1 as $row) {
      $RS1 = $row;
      break;
    }
    ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
    ShowHTML('          </TABLE></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('  </table>');
  }
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('      <table width="99%" border="0">');
    if ($P1 != 1)
      ShowHTML('        <tr><td><a accesskey="F" class="ss" href="javascript:window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Cálculo das diárias (Categoria ' . f($RS_Solic, 'nm_diaria') . ')</td>');
    if (count($RS) > 0) {
      $i = 1;
      foreach ($RS as $row) {
        $w_trechos[$i][1] = f($row, 'sq_diaria');
        $w_trechos[$i][2] = f($row, 'sq_deslocamento');
        $w_trechos[$i][3] = f($row, 'sq_deslocamento');
        $w_trechos[$i][4] = f($row, 'cidade_dest');
        $w_trechos[$i][5] = f($row, 'nm_destino');
        $w_trechos[$i][6] = f($row, 'phpdt_chegada');
        $w_trechos[$i][7] = f($row, 'phpdt_saida');
        $w_trechos[$i][8] = Nvl(f($row, 'quantidade'), 0);
        $w_trechos[$i][9] = Nvl(f($row, 'valor'), 0);
        $w_trechos[$i][10] = f($row, 'saida');
        $w_trechos[$i][11] = f($row, 'chegada');
        $w_trechos[$i][12] = f($row, 'diaria');
        $w_trechos[$i][13] = f($row, 'sg_moeda_diaria');
        $w_trechos[$i][14] = f($row, 'vl_diaria');
        $w_trechos[$i][15] = f($row, 'hospedagem');
        $w_trechos[$i][16] = Nvl(f($row, 'hospedagem_qtd'), 0);
        $w_trechos[$i][17] = Nvl(f($row, 'hospedagem_valor'), 0);
        $w_trechos[$i][18] = f($row, 'sg_moeda_hospedagem');
        $w_trechos[$i][19] = Nvl(f($row, 'hospedagem_valor'), f($row, 'vl_diaria_hospedagem'));
        $w_trechos[$i][20] = f($row, 'veiculo');
        $w_trechos[$i][21] = Nvl(f($row, 'veiculo_qtd'), 0);
        $w_trechos[$i][22] = Nvl(f($row, 'veiculo_valor'), 0);
        $w_trechos[$i][23] = f($row, 'sg_moeda_veiculo');
        $w_trechos[$i][24] = f($row, 'vl_diaria_veiculo');
        $w_trechos[$i][25] = f($row, 'sq_valor_diaria');
        $w_trechos[$i][26] = f($row, 'sq_diaria_hospedagem');
        $w_trechos[$i][27] = f($row, 'sq_diaria_veiculo');
        $w_trechos[$i][28] = f($row, 'justificativa_diaria');
        $w_trechos[$i][29] = f($row, 'justificativa_veiculo');
        $w_trechos[$i][30] = f($row, 'compromisso');
        $w_trechos[$i][31] = f($row, 'compromisso');
        $w_trechos[$i][32] = 'N';
        $w_trechos[$i][33] = 'N';
        $w_trechos[$i][34] = f($row, 'sq_fin_dia');
        $w_trechos[$i][35] = f($row, 'sq_rub_dia');
        $w_trechos[$i][36] = f($row, 'sq_lan_dia');
        $w_trechos[$i][37] = f($row, 'sq_fin_hsp');
        $w_trechos[$i][38] = f($row, 'sq_rub_hsp');
        $w_trechos[$i][39] = f($row, 'sq_lan_hsp');
        $w_trechos[$i][40] = f($row, 'sq_fin_vei');
        $w_trechos[$i][41] = f($row, 'sq_rub_vei');
        $w_trechos[$i][42] = f($row, 'sq_lan_vei');
        $w_trechos[$i][43] = f($row, 'hospedagem_checkin');
        $w_trechos[$i][44] = f($row, 'hospedagem_checkout');
        $w_trechos[$i][45] = f($row, 'hospedagem_observacao');
        $w_trechos[$i][46] = f($row, 'veiculo_retirada');
        $w_trechos[$i][47] = f($row, 'veiculo_devolucao');
        $w_trechos[$i][48] = f($row, 'destino_nacional');
        $w_trechos[$i][49] = f($row, 'saida_internacional');
        $w_trechos[$i][50] = f($row, 'chegada_internacional');
        $w_trechos[$i][51] = f($row, 'origem_nacional');
        $w_trechos[$i][52] = f($row, 'calculo_diaria_qtd');
        $w_trechos[$i][53] = f($row, 'calculo_diaria_texto');
        $w_trechos[$i][54] = f($row, 'calculo_hospedagem_qtd');
        $w_trechos[$i][55] = f($row, 'calculo_hospedagem_texto');
        $w_trechos[$i][56] = f($row, 'calculo_veiculo_qtd');
        $w_trechos[$i][57] = f($row, 'calculo_veiculo_texto');

        // Cria array para guardar o valor total por moeda
        if ($w_trechos[$i][13] > '')
          $w_total[$w_trechos[$i][13]] = 0;
        if ($w_trechos[$i][18] > '')
          $w_total[$w_trechos[$i][18]] = 0;
        if ($w_trechos[$i][12] > '')
          $w_total[$w_trechos[$i][23]] = 0;
        if ($i == 1) {
          // Se a primeira saída for após as 18:00, deduz meia diária
          if (intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_saida'), 2))) > 180000) {
            $w_trechos[$i][32] = 'S';
          }
        } else {
          // Se a última chegada for até 12:00, deduz meia diária
          if ($i == count($RS) && intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_chegada'), 2))) <= 120000) {
            $w_trechos[$i - 1][33] = 'S';
          }
          $w_trechos[$i - 1][3] = f($row, 'sq_deslocamento');
          $w_trechos[$i - 1][7] = f($row, 'phpdt_saida');
          $w_trechos[$i - 1][31] = f($row, 'compromisso');
        }
        $i += 1;
      }
      ShowHTML('     <tr><td align="center" colspan="2">');
      ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('           <td><b>Destino</td>');
      ShowHTML('           <td><b>Chegada</td>');
      ShowHTML('           <td><b>Saída</td>');
      ShowHTML('           <td><b>Operações</td>');
      ShowHTML('         </tr>');
      $w_cor = $conTrBgColor;
      $j = $i;
      $i = 1;
      $w_diarias = 0;
      $w_locacoes = 0;
      $w_hospedagens = 0;
      $w_tot_local = 0;
      AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return true;', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'A');
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('       <input type="hidden" name="w_chave" value="">');
      ShowHTML('       <input type="hidden" name="w_trechos" value="">');
      while ($i < count($w_trechos)) {
        $w_max_hosp = ceil((toDate(formataDataEdicao($w_trechos[$i][7])) - toDate(formataDataEdicao($w_trechos[$i][6]))) / 86400);
        if (($i > 0 && $i < ($j - 1) && (($w_trechos[$i][48] == 'N' && toDate(FormataDataEdicao($w_trechos[$i][6])) == $w_fim) ||
                ($w_trechos[$i][51] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim)
                )
                ) ||
                ($w_max_hosp >= 0 &&
                $w_trechos[$i][49] == 0 &&
                $w_trechos[$i][50] == 0 &&
                ($w_trechos[$i][51] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim))
        ) {
          $w_diarias = nvl($w_trechos[$i][8], 0) * nvl($w_trechos[$i][9], 0);
          $w_locacoes = (-1 * nvl($w_trechos[$i][9], 0) * nvl($w_trechos[$i][22], 0) / 100 * nvl($w_trechos[$i][21], 0));
          $w_hospedagens = nvl($w_trechos[$i][16], 0) * nvl($w_trechos[$i][17], 0);

          if ($w_diarias > 0)
            $w_total[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes <> 0)
            $w_total[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_total[$w_trechos[$i][18]] += $w_hospedagens;

          $w_tot_local = $w_diarias + $w_locacoes;

          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
          ShowHTML('       <td' . (($P1 != 1) ? ' rowspan="2"' : '') . '>' . (($P1 != 1) ? '<b>' : '') . $w_trechos[$i][5]);
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Saída após 18:00');
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Chegada até 12:00');
          if ($w_trechos[$i][30] == 'N')
            ShowHTML('<br>Sem compromisso na ida');
          if ($w_trechos[$i][31] == 'N')
            ShowHTML('<br>Sem compromisso na volta');
          if ($P1 != 1)
            ShowHTML('<br>' . $w_trechos[$i][13] . ' ' . formatNumber($w_tot_local));
          ShowHTML('       <td align="center">' . (($P1 != 1) ? '<b>' : '') . substr(FormataDataEdicao($w_trechos[$i][6], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td align="center">' . (($P1 != 1) ? '<b>' : '') . substr(FormataDataEdicao($w_trechos[$i][7], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td' . (($P1 != 1) ? ' rowspan="2"' : '') . '>');
          ShowHTML('          <A class="HL" HREF="javascript:altera(' . f($row, 'sq_siw_solicitacao') . ',\'' . base64_encode(serialize($w_trechos[$i])) . '\');" title="Informa as diárias">Informar</A>&nbsp');
          ShowHTML('       </td>');
          if ($P1 != 1) {
            ShowHTML('     <tr bgcolor="' . $w_cor . '"><td colspan="2"><table width="100%" border=1>');
            ShowHTML('       <tr valign="top" align="center">');
            ShowHTML('         <td>Item');
            ShowHTML('         <td width="20%">Quantidade');
            ShowHTML('         <td width="20%">$ Unitário');
            ShowHTML('         <td width="20%">$ Total');
            ShowHTML('       </tr>');
            if ($w_trechos[$i][25] > '' && nvl(f($RS_Solic, 'diaria'), '') != '' && $w_trechos[$i][8] > 0) {
              ShowHTML('       <tr valign="top">');
              ShowHTML('         <td>Diária (' . $w_trechos[$i][13] . ')</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_trechos[$i][8], 2) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_trechos[$i][9]) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_diarias, 2) . '</td>');
              ShowHTML('       </tr>');
            }
            if ($w_trechos[$i][27] > '' && f($RS_Solic, 'veiculo') == 'S' && $w_trechos[$i][21] > 0) {
              ShowHTML('       <tr valign="top">');
              ShowHTML('         <td>Veículo (' . $w_trechos[$i][23] . ') -' . formatNumber($w_trechos[$i][24], 0) . '%</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_trechos[$i][21], 1) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber(-1 * $w_trechos[$i][9] * $w_trechos[$i][22] / 100) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_locacoes, 1) . '</td>');
              ShowHTML('       </tr>');
            }
            if ($w_trechos[$i][26] > '' && f($RS_Solic, 'hospedagem') == 'S' && $w_trechos[$i][16] > 0) {
              ShowHTML('       <tr valign="top">');
              ShowHTML('         <td>Hospedagem (' . $w_trechos[$i][18] . ')</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_trechos[$i][16], 1) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_trechos[$i][17]) . '</td>');
              ShowHTML('         <td align="right">' . formatNumber($w_hospedagens, 1) . '</td>');
              ShowHTML('       </tr>');
            }
            ShowHTML('     </tr></table>');
          }
        }
        $i += 1;
      }
      ShowHTML('        </table></td></tr>');
      if ($P1 != 1) {
        ShowHTML('     <tr><td align="center"><b>TOTAL SOLICITADO:');
        foreach ($w_total as $k => $v) {
          ShowHTML('       &nbsp;&nbsp;&nbsp;&nbsp;' . $k . ' ' . formatNumber($v));
        }
        ShowHTML('     </tr>');
      }
    }
  } else {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria" value="' . $w_sq_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_reg" value="' . $w_tipo_reg . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cidade" value="' . $w_cidade_dest . '">');
    ShowHTML('<INPUT type="hidden" name="w_desloc_saida" value="' . $w_desloc_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_desloc_chegada" value="' . $w_desloc_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_hosp" value="' . $w_max_hosp . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_diaria" value="' . $w_max_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_max_veiculo" value="' . $w_max_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_valor_diaria" value="' . $w_sq_valor_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria_hospedagem" value="' . $w_sq_diaria_hospedagem . '">');
    ShowHTML('<INPUT type="hidden" name="w_sq_diaria_veiculo" value="' . $w_sq_diaria_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_compromisso_chegada" value="' . $w_compromisso_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_compromisso_saida" value="' . $w_compromisso_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_meia_ida" value="' . $w_meia_ida . '">');
    ShowHTML('<INPUT type="hidden" name="w_meia_volta" value="' . $w_meia_volta . '">');
    ShowHTML('<INPUT type="hidden" name="w_cidade_dest" value="' . $w_cidade_dest . '">');
    ShowHTML('<INPUT type="hidden" name="w_nm_destino" value="' . $w_nm_destino . '">');
    ShowHTML('<INPUT type="hidden" name="w_phpdt_chegada" value="' . $w_phpdt_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_phpdt_saida" value="' . $w_phpdt_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_saida" value="' . $w_saida . '">');
    ShowHTML('<INPUT type="hidden" name="w_chegada" value="' . $w_chegada . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_diaria" value="' . $w_sg_moeda_diaria . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_hospedagem" value="' . $w_sg_moeda_hospedagem . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_moeda_veiculo" value="' . $w_sg_moeda_veiculo . '">');
    ShowHTML('<INPUT type="hidden" name="w_origem" value="AGENTE">');
    ShowHTML('<INPUT type="hidden" name="w_calc_dia_qtd" value="' . formatNumber($w_calc_dia_qtd, 2) . '">');
    ShowHTML('<INPUT type="hidden" name="w_calc_hsp_qtd" value="' . formatNumber($w_calc_hsp_qtd, 1) . '">');
    ShowHTML('<INPUT type="hidden" name="w_calc_vei_qtd" value="' . formatNumber($w_calc_vei_qtd, 1) . '">');

    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '"><td><table border=0 width="100%">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td>Cidade:<br><b>' . $w_nm_destino . '</b></td>');
    ShowHTML('            <td>Chegada:<br><b>' . substr(FormataDataEdicao($w_phpdt_chegada, 4), 0, -3) . '</b></td>');
    ShowHTML('            <td>Saída:<br><b>' . substr(FormataDataEdicao($w_phpdt_saida, 4), 0, -3) . '</b></td>');
    ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');
    ShowHTML('<INPUT type="hidden" name="w_diaria" value="' . $w_diaria . '">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td><b>Diárias:</b></td>');
    if ($w_diaria == 'S') {
      If (nvl($w_justificativa_diaria, '') != '') {
        ShowHTML('            <td colspan="3">Observações:<br><b>' . $w_justificativa_diaria . '</td>');
        ShowHTML('          <tr><td><td colspan=3><hr height="1"></td></tr>');
        ShowHTML('          <tr valign="top"><td>');
      }
      ShowHTML('            <td><b>Valor base (' . $w_sg_moeda_diaria . '):</b><br><input type="text" READONLY name="w_vl_diaria" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_vl_diaria . '" style="text-align:right;" title="Valor cheio da diária."></td>');
      ShowHTML('            <td><b>Quantidade:</b><br><input type="text" ' . (($w_diaria == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' name="w_quantidade" SIZE="5" MAXLENGTH="5" VALUE="' . (($w_diaria == 'N') ? '0,0' : $w_quantidade) . '" onBlur="calculaDiaria(this.value);" style="text-align:right;" onKeyDown="FormataValor(this,5,2,event);" title="Informe a quantidade de diárias para este local."></td>');
      ShowHTML('            <td><b>Valor a ser pago (' . $w_sg_moeda_diaria . '):</b><br><input type="text" READONLY name="w_valor" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . (($w_diaria == 'N') ? '0,00' : $w_valor) . '" style="text-align:right;" title="Valor cheio da diária."></td>');
      ShowHTML('<INPUT type="hidden" name="w_justificativa_diaria" value="' . $w_justificativa_diaria . '">');
      ShowHTML('          <tr valign="top"><td>');
      ShowHTML('            <td colspan="3" valign="top"><b><u>M</u>otivo da alteração da quantidade calculada de diárias:</b><br><textarea class="STI" accesskey="J" name="w_calc_dia_txt" class="STI" ROWS=5 cols=75 title="Informe o motivo da alteração da quantidade calculada de diárias.">' . $w_calc_dia_txt . '</TEXTAREA></td>');
      if (count($RS_Fin_Dia) > 1) {
        ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('          <tr><td>');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_dia, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_dia', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_dia\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_dia, null, $w_cliente, 'w_lan_dia', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_dia, 10, '0', STR_PAD_LEFT) . 'D', null);
        ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
      } elseif (count($RS_Fin_Dia) == 1) {
        foreach ($RS_Fin_Dia as $row) {
          $RS_Fin_Dia = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_dia" value="' . f($RS_Fin_Dia, 'chave') . '">');
      }
    } else {
      ShowHTML('            <td colspan="4"><b>Beneficiário indicou que não deseja diárias na localidade. Justificativa: <b>' . $w_justificativa_diaria . '</b></td></td></tr>');
    }
    ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');
    ShowHTML('<INPUT type="hidden" name="w_hospedagem" value="' . $w_hospedagem . '">');
    if ($w_destino_nacional == 'S') {
      ShowHTML('          <tr valign="top">');
      ShowHTML('            <td><b>Hospedagem:</b></td>');
      if ($w_hospedagem == 'S' && $w_destino_nacional == 'S') {
        ShowHTML('            <td>Check in:<br><b>' . formataDataEdicao($w_hos_in) . '</td>');
        ShowHTML('            <td>Check out:<br><b>' . formataDataEdicao($w_hos_out) . '</td>');
        If (nvl($w_hos_observ, '') != '')
          ShowHTML('          <tr><td><td colspan="3">Observações:<br><b>' . $w_hos_observ . '</td>');
        ShowHTML('          <tr><td><td colspan=3><hr height="1"></td></tr>');
        ShowHTML('          <tr valign="top"><td>');
        ShowHTML('            <td><b>Valor (' . $w_sg_moeda_hospedagem . '):</b><br><input type="text" ' . (($w_hospedagem == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' name="w_vl_diaria_hospedagem" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_vl_diaria_hospedagem . '" onblur="calculaHospedagem(document.Form.w_hospedagem_qtd.value);" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Valor da hospedagem."></td>');
        ShowHTML('            <td><b>Quantidade:</b><br><input type="text" ' . (($w_hospedagem == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' name="w_hospedagem_qtd" SIZE="5" MAXLENGTH="5" VALUE="' . $w_hospedagem_qtd . '" onblur="calculaHospedagem(this.value);" style="text-align:right;" onKeyDown="FormataValor(this,5,1,event);" title="Informe a quantidade de hospedagens para este local."></td>');
        ShowHTML('            <td><b>Valor a ser pago (' . $w_sg_moeda_hospedagem . '):</b><br><input type="text" READONLY name="w_hospedagem_valor" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_hospedagem_valor . '" style="text-align:right;" title="Valor cheio da hospedagem."></td>');
        ShowHTML('<INPUT type="hidden" name="w_hos_in" value="' . formataDataEdicao($w_hos_in) . '">');
        ShowHTML('<INPUT type="hidden" name="w_hos_out" value="' . formataDataEdicao($w_hos_out) . '">');
        ShowHTML('<INPUT type="hidden" name="w_hos_observ" value="' . $w_hos_observ . '">');
        ShowHTML('          <tr valign="top"><td>');
        ShowHTML('            <td colspan="3" valign="top"><b><u>M</u>otivo da alteração da quantidade calculada de hospedagens:</b><br><textarea class="STI" accesskey="J" name="w_calc_hsp_txt" class="STI" ROWS=5 cols=75 title="Informe o motivo da alteração da quantidade calculada de hospedagens.">' . $w_calc_hsp_txt . '</TEXTAREA></td>');
        if (count($RS_Fin_Hsp) > 1) {
          ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
          ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
          ShowHTML('          <tr><td>');
          SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_hsp, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_hsp', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_hsp\'; document.Form.submit();"');
          SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_hsp, null, $w_cliente, 'w_lan_hsp', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_hsp, 10, '0', STR_PAD_LEFT) . 'D', null);
          ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
        } elseif (count($RS_Fin_Hsp) == 1) {
          foreach ($RS_Fin_Hsp as $row) {
            $RS_Fin_Hsp = $row;
            break;
          }
          ShowHTML('<INPUT type="hidden" name="w_fin_hsp" value="' . f($RS_Fin_Hsp, 'chave') . '">');
        }
      } else {
        ShowHTML('            <td colspan="4"><b>Beneficiário indicou que não deseja hospedagem na localidade. Justificativa: <b>' . $w_hos_observ . '</b></td></td></tr>');
      }
      ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');
    }
    ShowHTML('<INPUT type="hidden" name="w_veiculo" value="' . $w_veiculo . '">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('            <td><b>Veículo:</b></td>');
    if ($w_veiculo == 'S') {
      ShowHTML('            <td>Retirada:<br><b>' . formataDataEdicao($w_vei_ret) . '</td>');
      ShowHTML('            <td>Devolução:<br><b>' . formataDataEdicao($w_vei_dev) . '</td>');
      If (nvl($w_justificativa_veiculo, '') != '')
        ShowHTML('          <tr><td><td colspan="3">Justificativa:<br><b>' . $w_justificativa_veiculo . '</td>');
      ShowHTML('          <tr><td><td colspan=3><hr height="1"></td></tr>');
      ShowHTML('          <tr valign="top"><td>');
      ShowHTML('            <td><b>Desconto na diária (%):</b><br><input type="text" READONLY name="w_vl_diaria_veiculo" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_vl_diaria_veiculo . '" style="text-align:center;" title="Percentual de desconto da diária."></td>');
      ShowHTML('            <td><b>Quantidade:</b><br><input type="text" ' . (($w_veiculo == 'S') ? 'class="STIO"' : 'READONLY class="STI"') . ' name="w_veiculo_qtd" SIZE="5" MAXLENGTH="5" VALUE="' . $w_veiculo_qtd . '" style="text-align:right;" onBlur="calculaLocacao(this.value);" onKeyDown="FormataValor(this,5,1,event);" title="Informe a quantidade de hospedagens para este local."></td>');
      ShowHTML('            <td><b>Valor a ser abatido (' . $w_sg_moeda_veiculo . '):</b><br><input type="text" READONLY name="w_veiculo_valor" class="STIH" SIZE="10" MAXLENGTH="18" VALUE="' . $w_veiculo_valor . '" style="text-align:right;" title="Valor cheio da veiculo."></td>');
      ShowHTML('<INPUT type="hidden" name="w_vei_ret" value="' . formataDataEdicao($w_vei_ret) . '">');
      ShowHTML('<INPUT type="hidden" name="w_vei_dev" value="' . formataDataEdicao($w_vei_dev) . '">');
      ShowHTML('<INPUT type="hidden" name="w_justificativa_veiculo" value="' . $w_justificativa_veiculo . '">');
      ShowHTML('          <tr valign="top"><td>');
      ShowHTML('            <td colspan="3" valign="top"><b><u>M</u>otivo da alteração da quantidade calculada de locações de veículo:</b><br><textarea class="STI" accesskey="J" name="w_calc_vei_txt" class="STI" ROWS=5 cols=75 title="Informe o motivo da alteração da quantidade calculada de locações de veículo.">' . $w_calc_vei_txt . '</TEXTAREA></td>');

      if (count($RS_Fin_Vei) > 1) {
        ShowHTML('          <tr><td><td colspan="3"><b>Dados para Pagamento</td></td></tr>');
        ShowHTML('          <tr><td><td colspan="3" align="center" height="1" bgcolor="#000000"></td></tr>');
        ShowHTML('          <tr><td>');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_vei, f($RS_Solic, 'sq_solic_pai'), 'D', 'w_rub_vei', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_vei\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_vei, null, $w_cliente, 'w_lan_vei', 'PDSV' . str_pad(f($RS_Solic, 'sq_solic_pai'), 10, '0', STR_PAD_LEFT) . str_pad($w_rub_vei, 10, '0', STR_PAD_LEFT) . 'D', null);
        ShowHTML('<INPUT type="hidden" name="w_tipo_despesa" value="D">');
      } elseif (count($RS_Fin_Vei) == 1) {
        foreach ($RS_Fin_Vei as $row) {
          $RS_Fin_Vei = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_vei" value="' . f($RS_Fin_Vei, 'chave') . '">');
      }
    } else {
      ShowHTML('            <td colspan="4"><b>Beneficiário indicou que não deseja locação de veículo na localidade.</td></td></tr>');
    }

    ShowHTML('          <tr><td colspan=4><hr height="1"></td></tr>');
    ShowHTML('          <tr><td align="center" colspan=4>');
    if ($w_diaria == 'S' || $w_hospedagem == 'S' || $w_veiculo == 'S'

      )ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('        </table>');
    ShowHTML('</FORM>');
  }
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo = upper(trim($_REQUEST['w_tipo']));

  if ($w_tipo == 'PDF') {
    headerpdf('Visualização de ' . f($RS_Menu, 'nome'), $w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='EXCEL') {
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0,1,8);
    $w_embed = 'WORD';
  } elseif ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<title>' . $conSgSistema . ' - Visualização de viagem</title>');
    ShowHTML('</head>');
    ShowHTML('<base HREF="' . $conRootSIW . '">');
    BodyOpenClean('onLoad="this.focus();" ');
    if ($w_tipo != 'WORD')
      CabecalhoRelatorio($w_cliente, 'Visualização de ' . f($RS_Menu, 'nome'), 4, $w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><b><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave, 'L', $w_usuario, $P1, $w_embed));
  if ($w_embed!='WORD') {
    ShowHTML('<center><b><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
    ScriptOpen('JavaScript');
    ShowHTML('  var comando, texto;');
    ShowHTML('  if (window.name!="content") {');
    ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
    ShowHTML('  }');
    ScriptClose();
  }
  if ($w_tipo=='PDF') RodapePDF();
  else                Rodape();
}

// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  if ($w_troca > '') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  }

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if ($O == 'E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    if ($P1 != 1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave, 'V', $w_usuario, $P1, $P4));
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PDIDENT', $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de tramitação
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'], '');
  $w_tramite    = $_REQUEST['w_tramite'];

  if ($w_troca > '') {
    // Se for recarga da página    
    $w_sg_tramite         = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite    = $_REQUEST['w_tramite'];
    $w_destinatario       = $_REQUEST['w_destinatario'];
    $w_envio              = $_REQUEST['w_envio'];
    $w_despacho           = $_REQUEST['w_despacho'];
    $w_justificativa      = $_REQUEST['w_justificativa'];
    $w_justif_dia_util    = $_REQUEST['w_justif_dia_util'];
    $w_prazo              = $_REQUEST['w_prazo'];
    $w_antecedencia       = $_REQUEST['w_antecedencia'];
    $w_envio_regular      = $_REQUEST['w_envio_regular'];
    $w_fim_semana         = $_REQUEST['w_fim_semana'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
    $w_inicio           = f($RS, 'inicio');
    $w_justificativa    = f($RS, 'justificativa');
    $w_prazo            = f($RS, 'limite_envio');
    $w_antecedencia     = f($RS, 'dias_antecedencia');
    $w_envio_regular    = f($RS, 'envio_regular');
    $w_justif_dia_util  = f($RS, 'justificativa_dia_util');
    if (f($RS, 'sg_tramite') == 'CI') {
      $w_tramite = f($RS, 'sq_siw_tramite');
    }
    $w_fim_semana       = f($RS, 'fim_semana');
  }

  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms, $w_tramite);
  $w_sg_tramite = f($RS, 'sigla');
  $w_ativo = f($RS, 'ativo');

  if ($w_sg_tramite != 'CI') {
    //Verifica a fase anterior para a caixa de seleção da fase.
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_tramite, $w_chave, 'DEVFLUXO', null);
    $RS = SortArray($RS, 'ordem', 'desc');
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_novo_tramite = f($RS, 'sq_siw_tramite');
  }

  // Se for envio, executa verificações nos dados da solicitação
  if ($O == 'V')
    $w_erro = ValidaViagem($w_cliente, $w_chave, $SG, 'PDGERAL', null, null, $w_tramite);

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  if (substr(Nvl($w_erro, 'nulo'), 0, 1) != '0' || $w_sg_tramite != 'CI') {
    if ($w_sg_tramite == 'CI') {
      if (mktime(0, 0, 0, date(m), date(d), date(Y)) > $w_prazo) {
        Validate('w_justificativa', 'Justificativa', '', '1', '1', '2000', '1', '1');
      }
      if ($w_fim_semana == 'S') {
        Validate('w_justif_dia_util', 'Justificativa', '1', '1', 5, 2000, '1', '1');
      }
    } else {
      if ($w_sg_tramite == 'EE' || $w_ativo == 'N') {
        Validate('w_despacho', 'Despacho', '1', '1', '1', '2000', '1', '1');
      } else {
        Validate('w_despacho', 'Despacho', '', '', '1', '2000', '1', '1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert(\'Informe o despacho apenas se for devolução para a fase anterior!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert(\'Informe um despacho descrevendo o motivo da devolução!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        if (Nvl(substr($w_erro, 0, 1), '') == '1' || substr(Nvl($w_erro, 'nulo'), 0, 1) == '2') {
          if (mktime(0, 0, 0, date(m), date(d), date(Y)) > $w_prazo) {
            Validate('w_justificativa', 'Justificativa', '', '', '1', '2000', '1', '1');
            ShowHTML('if (theForm.w_envio[0].checked && theForm.w_justificativa.value==\'\') {');
            ShowHTML('     alert(\'Informe uma justificativa para o não cumprimento do prazo regulamentar!\');');
            ShowHTML('     theForm.w_justificativa.focus();');
            ShowHTML('     return false;');
            ShowHTML('}');
          }
        }
      }
    }
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
  }
  if ($P1 != 1 || ( $P1 == 1 && $w_tipo == 'Volta')) {
    // Se não for encaminhamento e nem o sub-menu do cadastramento
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  } else {
    ShowHTML('  theForm.Botao.disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpen('onLoad="this.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave, 'V', $w_usuario, $P1, $P4));
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PDENVIO', $w_pagina . $par, $O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . $w_tramite . '">');
  ShowHTML('<INPUT type="hidden" name="w_prazo" value="' . $w_prazo . '">');
  ShowHTML('<INPUT type="hidden" name="w_antecedencia" value="' . $w_antecedencia . '">');
  ShowHTML('<INPUT type="hidden" name="w_envio_regular" value="' . $w_envio_regular . '">');
  ShowHTML('<INPUT type="hidden" name="w_fim_semana" value="' . $w_fim_semana . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
  if ($w_sg_tramite == 'CI') {
    if (substr(Nvl($w_erro, 'nulo'), 0, 1) != '0') {
      // Se cadastramento inicial
      ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
      // Se a data de início da viagem não respeitar os dias de antecedência, exige justificativa.
      if (mktime(0, 0, 0, date(m), date(d), date(Y)) > $w_prazo) {
        ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de ' . $w_antecedencia . ' dias:</b><br><textarea ' . $w_Disabled . ' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a ' . FormataDataEdicao($w_envio_regular) . ', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">' . $w_justificativa . '</TEXTAREA></td>');
      }
      if ($w_fim_semana == 'S') {
        ShowHTML('      <tr><td colspan="4" valign="top"><b><u>J</u>ustificativa para viagem contendo fim de semana/feriado:</b><br><textarea ' . $w_Disabled . ' accesskey="J" name="w_justif_dia_util" class="STI" ROWS=5 cols=75 title="Justifique a necessidade da viagem abranger fim de semana/feriado.">' . $w_justif_dia_util . '</TEXTAREA></td>');
      }
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    }
  } else {
    ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');
    if (substr(Nvl($w_erro, 'nulo'), 0, 1) == '0' || $w_sg_tramite == 'EE' || $w_ativo == 'N') {
      ShowHTML('              <input DISABLED class="STR" type="radio" name="w_envio" value="S"> Enviar para a próxima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
      ShowHTML('<INPUT type="hidden" name="w_envio" value="S">');
    } else {
      if (Nvl($w_envio, 'N') == 'N') {
        ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="w_envio" value="N" checked> Enviar para a próxima fase <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="w_envio" value="S"> Devolver para a fase anterior');
      } else {
        ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="w_envio" value="N"> Enviar para a próxima fase <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
      }
    }
    ShowHTML('    <tr>');
    SelecaoFase('<u>F</u>ase: (válido apenas se for devolução)', 'F', 'Se deseja devolver a solicitação, selecione a fase para a qual deseja devolvê-la.', $w_novo_tramite, $w_tramite, $w_chave, 'w_novo_tramite', 'DEVFLUXO', null);
    ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolução):</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a solicitação.">' . $w_despacho . '</TEXTAREA></td>');
    if (!(substr(Nvl($w_erro, 'nulo'), 0, 1) == '0' || $w_sg_tramite == 'EE' || $w_ativo == 'N')) {
      if (substr(Nvl($w_erro, 'nulo'), 0, 1) == '1' || substr(Nvl($w_erro, 'nulo'), 0, 1) == '2') {
        if (mktime(0, 0, 0, date(m), date(d), date(Y)) > $w_prazo) {
          ShowHTML('    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de ' . $w_antecedencia . ' dias:</b><br><textarea ' . $w_Disabled . ' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o início da viagem for anterior a ' . FormataDataEdicao($w_envio_regular) . ', justifique o motivo do não cumprimento do prazo regulamentar para o pedido.">' . $w_justificativa . '</TEXTAREA></td>');
        }
      }
    }
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  }
  if ($P1 != 1) {
    // Se não for cadastramento, volta para a listagem
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
  } elseif ($P1 == 1 && $w_tipo == 'Volta') {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  if ($w_troca > '') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if ($O == 'V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao', 'Anotação', '', '1', '1', '2000', '1', '1');
    Validate('w_caminho', 'Arquivo', '', '', '5', '255', '1', '1');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    if ($P1 != 1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpen('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_observacao.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave, 'L', $w_usuario, $P1, $P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava&SG=PDENVIO&O=' . $O . '&w_menu=' . $w_menu . '" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
  ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
  ShowHTML('<INPUT type="hidden" name="P4" value="' . $P4 . '">');
  ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
  ShowHTML('<INPUT type="hidden" name="R" value="' . $w_pagina . $par . '">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . f($RS, 'sq_siw_tramite') . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de ' . (f($RS_Cliente, 'upload_maximo') / 1024) . ' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS_Cliente, 'upload_maximo') . '">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea ' . $w_Disabled . ' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">' . $w_observacao . '</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input ' . $w_Disabled . ' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  if ($w_troca > '') {
    // Se for recarga da página
    $w_inicio_real = $_REQUEST['w_inicio_real'];
    $w_fim_real = $_REQUEST['w_fim_real'];
    $w_concluida = $_REQUEST['w_concluida'];
    $w_data_conclusao = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
    $w_custo_real = $_REQUEST['w_custo_real'];
  }

  //Recupera a data da primeira saída
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', 'DADFIN');
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  if (!count($RS) <= 0) {
    $w_inicio_real = f($RS, 'saida');
    foreach ($RS as $row) {
      $w_custo_real += Nvl(f($row, 'quantidade'), 0) * Nvl(f($row, 'valor'), 0);
      $w_fim_real = f($row, 'chegada');
    }
  }

  //Recupera os dados da solicitacao de passagens e diárias
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, substr($SG, 0, 3) . 'GERAL');
  $w_tramite = f($RS, 'sq_siw_tramite');
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if ($O == 'V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_cliente == 10135 && $w_mod_pa == 'S') Validate('w_nota_conclusao', 'Observações sobre o acondicionamento', '1', '1', 1, 2000, '1', '1');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da solicitação, na opção 'Listagem'
  ShowHTML(VisualViagem($w_chave, 'L', $w_usuario, $P1, $P4));
  ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava&SG=PDCONC&O=' . $O . '&w_menu=' . $w_menu . '" name="Form" onSubmit="return(Validacao(this));" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
  ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
  ShowHTML('<INPUT type="hidden" name="P4" value="' . $P4 . '">');
  ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
  ShowHTML('<INPUT type="hidden" name="R" value="' . $w_pagina . $par . '">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . $w_tramite . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="100%" border="0">');
  if ($w_cliente == 10135 && $w_mod_pa == 'S') {
    //Se ABDI, vincula a viagem com o módulo de protocolo
    ShowHTML('    <tr><td colspan=3><font size=2><b>DADOS DO ARQUIVAMENTO SETORIAL</b></font></td></tr>');
    ShowHTML('    <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('    <tr><td width="30%">Data do arquivamento:<td colspan=2><b>' . formataDataEdicao(time()) . '</b></td></tr>');
    ShowHTML('    <tr><td width="30%">Unidade arquivadora:<td colspan=2><b>' . f($RS_Menu, 'nm_unidade') . '</b></td></tr>');
    ShowHTML('    <tr><td width="30%">Usuário arquivador:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
    ShowHTML('    <tr valign="top"><td width="30%">Acondicionamento:<td title="Descreva de forma objetiva onde o documento encontra-se no arquivo setorial."><textarea ' . $w_Disabled . ' accesskey="O" name="w_nota_conclusao" class="STI" ROWS=5 cols=75>' . $w_nota_conclusao . '</TEXTAREA></td>');
    ShowHTML('    <tr><td colspan=3>&nbsp;</td></tr>');
  }
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina para informação dos dados da viagem
// -------------------------------------------------------------------------
function InformarPassagens() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];

  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_valor_passagem = formatNumber(f($RS, 'valor_passagem'));
  $w_pta = f($RS, 'pta');
  $w_emissao_bilhete = FormataDataEdicao(f($RS, 'emissao_bilhete'));
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  var i,k;');
  ShowHTML('  for (k=0; k < theForm["w_sq_cia_transporte[]"].length; k++) {');
  ShowHTML('    var w_campo = \'theForm["w_sq_cia_transporte[]"][\'+k+\']\';');
  ShowHTML('    if(eval(w_campo + \'.value\')==\'\'){');
  ShowHTML('      alert(\'Informe a companhia de transporte para cada trecho!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('  }');
  ShowHTML('  for (k=0; k < theForm["w_codigo_voo[]"].length; k++) {');
  ShowHTML('    if(theForm["w_codigo_voo[]"][k].value==\'\'){');
  ShowHTML('      alert(\'Informe os códigos de vôos para cada trecho!\'); ');
  ShowHTML('      return false;');
  ShowHTML('    }');
  ShowHTML('    var w_campo = \'theForm["w_codigo_voo[]"][\'+k+\']\';');
  ShowHTML('    if (eval(w_campo + \'.value.length < 3 && \' + w_campo + \'.value != ""\')){');
  ShowHTML('      alert(\'Favor digitar pelo menos 3 posições no campo Código do vôo.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('    if (eval(w_campo + \'.value.length > 30 && \' + w_campo + \'.value != ""\')){');
  ShowHTML('      alert(\'Favor digitar no máximo 30 posições no campo Código do vôo.\');');
  ShowHTML('      eval(w_campo + \'.focus()\');');
  ShowHTML('      theForm.Botao.disabled=false;');
  ShowHTML('      return (false);');
  ShowHTML('    }');
  ShowHTML('  }');
  Validate('w_pta', 'Número do PTA/Ticket', '', '1', '1', '100', '1', '1');
  Validate('w_valor_passagem', 'Valor das passagens', 'VALOR', '1', 4, 18, '', '0123456789.,');
  Validate('w_emissao_bilhete', 'Data da emissão', 'DATA', '1', '10', '10', '', '0123456789/');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad="this.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . ' (' . $w_chave . ')</td>');
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Bilhete de passagem</td>');
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'S', $SG);
  $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
  if (count($RS) > 0) {
    $i = 1;
    foreach ($RS as $row) {
      $w_trechos[$i][1] = f($row, 'sq_deslocamento');
      $w_trechos[$i][2] = f($row, 'cidade_dest');
      $w_trechos[$i][10] = f($row, 'nm_origem');
      $w_trechos[$i][3] = f($row, 'nm_destino');
      $w_trechos[$i][4] = substr(FormataDataEdicao(f($row, 'phpdt_saida'), 3), 0, -3);
      $w_trechos[$i][5] = substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 3), 0, -3);
      $w_trechos[$i][6] = f($row, 'sq_cia_transporte');
      $w_trechos[$i][7] = f($row, 'codigo_voo');
      $w_trechos[$i][8] = f($row, 'saida');
      $w_trechos[$i][9] = f($row, 'chegada');
      $i += 1;
    }
    ShowHTML('     <tr><td align="center" colspan="2">');
    ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('         <td><b>Origem</td>');
    ShowHTML('         <td><b>Destino</td>');
    ShowHTML('         <td><b>Saida</td>');
    ShowHTML('         <td><b>Chegada</td>');
    ShowHTML('         <td><b>Cia. transporte</td>');
    ShowHTML('         <td><b>Código vôo</td>');
    ShowHTML('         </tr>');
    $w_cor = $conTrBgColor;
    $j = $i;
    $i = 1;
    while ($i != $j) {
      ShowHTML('<INPUT type="hidden" name="w_sq_deslocamento[]" value="' . $w_trechos[$i][1] . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="' . $w_trechos[$i][2] . '">');
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('     <tr valign="middle" bgcolor="' . $w_cor . '">');
      ShowHTML('       <td>' . $w_trechos[$i][10] . '</td>');
      ShowHTML('       <td>' . $w_trechos[$i][3] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][4] . '</td>');
      ShowHTML('       <td align="center">' . $w_trechos[$i][5] . '</td>');

      SelecaoCiaTrans('', '', 'Selecione a companhia de transporte para este destino.', $w_cliente, $w_trechos[$i][6], null, 'w_sq_cia_transporte[]', null, 'S');
      ShowHTML('       <td align="left"><input type="text" name="w_codigo_voo[]" class="sti" SIZE="10" MAXLENGTH="30" VALUE="' . $w_trechos[$i][7] . '"  title="Informe o código do vôo para este destino."></td>');
      ShowHTML('     </tr>');
      $i += 1;
    }
    ShowHTML('        </tr>');
    ShowHTML('        </table></td></tr>');
  }
  ShowHTML('        <tr><td colspan="2"><b>Nº do PTA/Ticket: </b><input type="text" name="w_pta" class="sti" SIZE="100" MAXLENGTH="100" VALUE="' . $w_pta . '" title="Informe o número do bilhete(PTA/eTicket)."></td>');
  ShowHTML('        <tr><td><b>Data da emissão: </b><input type="text" name="w_emissao_bilhete" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_emissao_bilhete . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
  ShowHTML('            <td><b>Valor das passagens R$: </b><input type="text" name="w_valor_passagem" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_passagem . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total das passagens."></td>');
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina para informação da cotação dos bilhetes
// -------------------------------------------------------------------------
function InformarCotacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_menu = $_REQUEST['w_menu'];

  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_valor = nvl($_REQUEST['w_valor'], formatNumber(f($RS, 'cotacao_valor')));
  $w_observacao = nvl($_REQUEST['w_observacao'], f($RS, 'cotacao_observacao'));
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Informar cotação</title>');
  ScriptOpen('JavaScript');
  CheckBranco();
  SaltaCampo();
  FormataValor();
  ValidateOpen('Validacao');
  Validate('w_valor', 'Valor estimado', 'VALOR', '1', 4, 18, '', '0123456789,.');
  CompValor('w_valor', 'Valor estimado', '>', '0,00', 'zero');
  Validate('w_observacao', 'Observações', '1', '', 2, 2000, '1', '1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad="this.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . ' (' . $w_chave . ')</td>');
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Cotação de Bilhetes</td>');
  ShowHTML('    <tr><td><b><u>V</u>alor:<br><input type="text" accesskey="V" name="w_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor estimado dos bilhetes."></td>');
  ShowHTML('    <tr><td><b><u>O</u>bservação:</b><br><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75 title="OPCIONAL. Registre observações que julgar relevantes.">' . $w_observacao . '</TEXTAREA></td>');
  ShowHTML('    <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('    <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

/*
  // =========================================================================
  // Rotina para informação da cotação dos bilhetes
  // -------------------------------------------------------------------------
  function InformarCotacao() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_menu   = $_REQUEST['w_menu'];

  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,'PDGERAL');
  $w_valor_passagem     = formatNumber(f($RS,'valor_passagem'));
  $w_pta                = f($RS,'pta');
  $w_emissao_bilhete    = FormataDataEdicao(f($RS,'emissao_bilhete'));
  Cabecalho();
  head();
  ShowHTML('<title>'.$conSgSistema.' - Informar cotação</title>');
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataValor();
  ValidateOpen('Validacao');
  ShowHTML('  var i,k;');
  ShowHTML('  for (ind=1; ind < theForm["w_sq_cia_transporte[]"].length; ind++) {');
  Validate('["w_sq_cia_transporte[]"][ind]','Companhia de transporte','SELECT','1',1,18,'','0123456789');
  ShowHTML('  }');
  ShowHTML('  for (ind=1; ind < theForm["w_codigo_voo[]"].length; ind++) {');
  Validate('["w_codigo_voo[]"][ind]','Código do vôo','','1',3,30,'1','1');
  ShowHTML('  }');
  ShowHTML('  w_tot = 0;');
  ShowHTML('  for (ind=1; ind < theForm["w_valor_trecho[]"].length; ind++) {');
  Validate('["w_valor_trecho[]"][ind]','Valor estimado','VALOR','1',4,18,'','0123456789,.');
  ShowHTML('  w_tot = w_tot + parseFloat(replaceAll(replaceAll(theForm["w_valor_trecho[]"][ind].value,".",""),",","."));');
  ShowHTML('  }');
  ShowHTML('  if (w_tot==0) {');
  ShowHTML('    alert("Pelo menos um dos trechos deve ter valor maior que zero!");');
  ShowHTML('    return false;');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="this.focus();"');
  ShowHTML('<b><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('            <tr><td>Número:<b><br>'.f($RS,'codigo_interno').' ('.$w_chave.')</td>');
  ShowHTML('                <td>Primeira saída:<br><b>'.date('d/m/y, H:i',f($RS,'phpdt_inicio')).' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>'.date('d/m/y, H:i',f($RS,'phpdt_fim')).' </b></td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null, null, null, null, null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>'.f($RS1,'nm_pessoa').'</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('      <table width="99%" border="0">');
  ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Cotação de Trechos</td>');
  $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms,$w_chave,null,'S',$SG);
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
  $i = 1;
  foreach($RS as $row) {
  $w_trechos[$i][1]  = f($row,'sq_deslocamento');
  $w_trechos[$i][2]  = f($row,'cidade_dest');
  $w_trechos[$i][10]  = f($row,'nm_origem');
  $w_trechos[$i][3]  = f($row,'nm_destino');
  $w_trechos[$i][4]  = substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3);
  $w_trechos[$i][5]  = substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3);
  $w_trechos[$i][6]  = f($row,'sq_cia_transporte');
  $w_trechos[$i][7]  = f($row,'codigo_voo');
  $w_trechos[$i][8]  = f($row,'saida');
  $w_trechos[$i][9]  = f($row,'chegada');
  $w_trechos[$i][11] = f($row,'nm_meio_transporte');
  $w_trechos[$i][12] = formatNumber(f($row,'valor_trecho'));
  $w_trechos[$i][13] = f($row,'sq_meio_transporte');
  $w_total += f($row,'valor_trecho');
  $i += 1;
  }
  ShowHTML('     <tr><td align="center" colspan="2">');
  ShowHTML('       <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('         <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('         <td><b>Origem</td>');
  ShowHTML('         <td><b>Destino</td>');
  ShowHTML('         <td><b>Saida</td>');
  ShowHTML('         <td><b>Chegada</td>');
  ShowHTML('         <td><b>Meio</td>');
  ShowHTML('         <td><b>Cia.</td>');
  ShowHTML('         <td><b>Código vôo</td>');
  ShowHTML('         <td><b>Valor</td>');
  ShowHTML('         </tr>');
  $w_cor=$conTrBgColor;
  $j = $i;
  $i = 1;
  ShowHTML('<INPUT type="hidden" name="w_sq_deslocamento[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sq_cia_transporte[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_codigo_voo[]" value="">');
  ShowHTML('<INPUT type="hidden" name="w_valor_trecho[]" value="">');
  while($i!=$j) {
  ShowHTML('<INPUT type="hidden" name="w_sq_deslocamento[]" value="'.$w_trechos[$i][1].'">');
  ShowHTML('<INPUT type="hidden" name="w_sq_cidade[]" value="'.$w_trechos[$i][2].'">');
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  ShowHTML('     <tr valign="middle" bgcolor="'.$w_cor.'">');
  ShowHTML('       <td>'.$w_trechos[$i][10].'</td>');
  ShowHTML('       <td>'.$w_trechos[$i][3].'</td>');
  ShowHTML('       <td align="center">'.$w_trechos[$i][4].'</td>');
  ShowHTML('       <td align="center">'.$w_trechos[$i][5].'</td>');
  ShowHTML('       <td align="center">'.$w_trechos[$i][11].'</td>');

  SelecaoCiaTrans('','','Selecione a companhia de transporte para este destino.',$w_cliente,$w_trechos[$i][6],null,'w_sq_cia_transporte[]',$w_trechos[$i][13],null);
  ShowHTML('       <td align="left"><input type="text" name="w_codigo_voo[]" class="sti" SIZE="10" MAXLENGTH="30" VALUE="'.$w_trechos[$i][7].'"  title="Informe o código do vôo para este destino."></td>');
  ShowHTML('       <td><input type="text" accesskey="V" name="w_valor_trecho[]" class="sti" SIZE="10" MAXLENGTH="18" VALUE="'.$w_trechos[$i][12].'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor estimado do bilhete deste trecho."></td>');
  ShowHTML('     </tr>');
  $i += 1;
  }
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  ShowHTML('     <tr><td align="center" colspan="8" height="1" bgcolor="#000000">');
  ShowHTML('      <tr valign="top" bgcolor="'.$w_cor.'"><td colspan="7" align="right"><b>Total</b><td><input readonly type="text" name="w_total" class="stih" style="background-color:'.$w_cor.'; border: 0; text-align:right; font-weight: bold;" SIZE="9" MAXLENGTH="18" VALUE="'.formatNumber($w_total).'">');
  ShowHTML('        </table></td></tr>');
  }
  ShowHTML('        <tr><td align="center" colspan="2">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="window.close();" name="Botao" value="Fechar">');
  ShowHTML('      </table>');
  ShowHTML('    </td>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
  }
 */

// =========================================================================
// Rotina de preparação para envio de e-mail relativo a missões
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação.
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic, $p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms, $p_solic, 'PDGERAL');
  if (f($RS, 'envia_mail_tramite') == 'S' && (f($RS_Menu, 'envia_email') == 'S') && (f($RSM, 'envia_mail') == 'S')) {
    $l_solic = $p_solic;
    $l_menu = f($RSM, 'sq_menu');
    $w_destinatarios = '';
    $w_resultado = '';
    $w_anexos = array();

    // Recupera os dados da solicitação
    $w_sg_tramite = f($RSM, 'sg_tramite');
    $w_nome = f($RSM, 'codigo_interno');
    $w_cumprimento = f($RSM, 'cumprimento');

    $w_html = '<HTML>' . $crlf;
    $w_html .= BodyOpenMail(null) . $crlf;
    $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">' . $crlf;
    $w_html .= '<tr bgcolor="' . $conTrBgColor . '"><td align="center">' . $crlf;
    $w_html .= '    <table width="97%" border="0">' . $crlf;
    if ($p_tipo == 1) {
      $w_html .= '      <tr valign="top"><td align="center"><b>INCLUSÃO</b><br><br><td></tr>' . $crlf;
    } elseif ($w_sg_tramite == 'PC' && $w_cumprimento != 'C') {
      $w_html .= '      <tr valign="top"><td align="center"><b>PRESTAÇÃO DE CONTAS</b><br><br><td></tr>' . $crlf;
    } elseif ($p_tipo == 2) {
      $w_html .= '      <tr valign="top"><td align="center"><b>TRAMITAÇÃO</b><br><br><td></tr>' . $crlf;
    } elseif ($p_tipo == 3) {
      $w_html .= '      <tr valign="top"><td align="center"><b>CONCLUSÃO</b><br><br><td></tr>' . $crlf;
    }
    if ($w_sg_tramite == 'PC' && $w_cumprimento != 'C') {
      if ($w_cliente == 10135) { //ABDI
        $w_html .= '      <tr valign="top"><td><b><font color="#BC3131">ATENÇÃO:<br>É necessário elaborar o relatório de viagem e entregar os bilhetes de embarque, conforme PO 059.</font></b><br><br><td></tr>' . $crlf;
      } else {
        $w_html .= '      <tr valign="top"><td><b><font color="#BC3131">ATENÇÃO:<br>Conforme Portaria Nº 47/MPO 29/04/2003 – DOU 30/04/2003, é necessário elaborar o relatório de viagem e entregar os bilhetes de embarque.<br><br>Use o arquivo anexo para elaborar seu relatório de viagem e entregue-o assinado ao setor competente, juntamente com os bilhetes.</font></b><br><br><td></tr>' . $crlf;
      }
    } else {
      $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: Esta é uma mensagem de envio automático. Não responda esta mensagem.</font></b><br><br><td></tr>' . $crlf;
    }
    $w_html .= $crlf . '<tr bgcolor="' . $conTrBgColor . '"><td align="center">';
    $w_html .= $crlf . '    <table width="99%" border="0">';
    // Identificação da solicitação
    $w_html .= $crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO</td>';
    $w_html .= $crlf . '      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= $crlf . '          <tr valign="top">';
    $w_html .= $crlf . '            <td>Beneficiário:<br><b>' . f($RSM, 'nm_prop') . '</b></td>';
    $w_html .= $crlf . '            <td>Unidade proponente:<br><b>' . f($RSM, 'nm_unidade_resp') . '</b></td>';
    $w_html .= $crlf . '          <tr valign="top">';
    $w_html .= $crlf . '            <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RSM, 'phpdt_inicio')) . ' </b></td>';
    $w_html .= $crlf . '            <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RSM, 'phpdt_fim')) . ' </b></td>';
    $w_html .= $crlf . '          </table>';
    // Informações adicionais
    if (Nvl(f($RSM, 'descricao'), '') > '')
      $w_html .= $crlf . '      <tr><td valign="top">Objetivo/assunto a ser tratado/evento:<br><b>' . CRLF2BR(f($RSM, 'descricao')) . ' </b></td>';
    $w_html .= $crlf . '    </table>';
    $w_html .= $crlf . '</tr>';

    //Recupera o último log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms, $p_solic, null, null, 'LISTA');
    $RS = SortArray($RS, 'phpdt_data', 'desc', 'despacho', 'desc');
    foreach ($RS as $row) {
      $RS = $row;
      if (strpos(f($row, 'despacho'), '*** Nova versão') === false)
        break;
    }
    $w_data_encaminhamento = f($RS, 'phpdt_data');
    if ($p_tipo == 2) {
      $w_html .= $crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html .= $crlf . '      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= $crlf . '          <tr><td>De:<br><b>' . f($RS, 'responsavel') . '</b></td>';
      if (Nvl(f($RS, 'despacho'), '') != '') {
        $w_html.=$crlf . '          <tr><td>Despacho:<br><b>' . CRLF2BR(f($RS, 'despacho')) . ' </b></td>';
      }
      $w_html .= $crlf . '          </table>';
    }
    $w_html .= $crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
    $w_html .= '      <tr valign="top"><td>' . $crlf;
    $w_html .= '         Para acessar o sistema use o endereço: <b><a class="SS" href="' . f($RS, 'logradouro') . '" target="_blank">' . f($RS, 'Logradouro') . '</a></b></li>' . $crlf;
    $w_html .= '      </td></tr>' . $crlf;
    $w_html .= '      <tr valign="top"><td>' . $crlf;
    $w_html .= '         Dados da ocorrência:<br>' . $crlf;
    $w_html .= '         <ul>' . $crlf;
    $w_html .= '         <li>Responsável: <b>' . $_SESSION['NOME'] . '</b></li>' . $crlf;
    $w_html .= '         <li>Data: <b>' . date('d/m/Y, H:i:s', $w_data_encaminhamento) . '</b></li>' . $crlf;
    $w_html .= '         <li>IP de origem: <b>' . $_SERVER['REMOTE_ADDR'] . '</b></li>' . $crlf;
    $w_html .= '         </ul>' . $crlf;
    $w_html .= '      </td></tr>' . $crlf;
    $w_html .= '    </table>' . $crlf;
    $w_html .= '</td></tr>' . $crlf;
    $w_html .= '</table>' . $crlf;
    $w_html .= '</BODY>' . $crlf;
    $w_html .= '</HTML>' . $crlf;
    // Prepara os dados necessários ao envio
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
    if ($p_tipo == 1 || $p_tipo == 3) {
      // Inclusão ou Conclusão
      if ($p_tipo == 1)
        $w_assunto = 'Inclusão - ' . $w_nome; else
        $w_assunto='Encerramento - ' . $w_nome;
    } elseif ($w_sg_tramite == 'EE') {
      // Prestação de contas
      $w_assunto = 'Prestação de Contas - ' . $w_nome;
    } elseif ($p_tipo == 2) {
      // Tramitação
      $w_assunto = 'Tramitação - ' . $w_nome;
    }
    // Configura os destinatários da mensagem
    $sql = new db_getTramiteResp; $RS = $sql->getInstanceOf($dbms, $p_solic, null, null);
    if (!count($RS) <= 0) {
      foreach ($RS as $row) {
        $sql = new DB_GetUserMail; $RS_Mail = $sql->getInstanceOf($dbms, $l_menu, f($row, 'sq_pessoa'), $w_cliente, null);
        foreach ($RS_Mail as $row_mail) {
          $RS_Mail = $row_mail;
        }
        if (($p_tipo == 2 && f($RS_Mail, 'tramitacao') == 'S') || ($p_tipo == 3 && f($RS_Mail, 'conclusao') == 'S')) {
          $w_destinatarios .= f($RS_Mail, 'email') . '|' . f($RS_Mail, 'nome') . '; ';
        }
      }
    }
    if (f($RSM, 'st_sol') == 'S') {
      // Recupera o e-mail do responsável
      $sql = new DB_GetUserMail; $RS_Mail = $sql->getInstanceOf($dbms, $l_menu, f($RSM, 'solicitante'), $w_cliente, null);
      foreach ($RS_Mail as $row_mail) {
        $RS_Mail = $row_mail;
      }
      if (($p_tipo == 2 && f($RS_Mail, 'tramitacao') == 'S') || ($p_tipo == 3 && f($RS_Mail, 'conclusao') == 'S')) {
        $w_destinatarios .= f($RS_Mail, 'email') . '|' . f($RS_Mail, 'nome') . '; ';
      }
    }
    if (f($RSM, 'st_prop') == 'S') {
      // Recupera o e-mail do beneficiário
      $sql = new DB_GetUserMail; $RS_Mail = $sql->getInstanceOf($dbms, $l_menu, f($RSM, 'sq_prop'), $w_cliente, null);
      foreach ($RS_Mail as $row_mail) {
        $RS_Mail = $row_mail;
      }
      if (($p_tipo == 2 && f($RS_Mail, 'tramitacao') == 'S') || ($p_tipo == 3 && f($RS_Mail, 'conclusao') == 'S')) {
        $w_destinatarios .= f($RS_Mail, 'email') . '|' . f($RS_Mail, 'nome') . '; ';
      }
    }
    // Executa o envio do e-mail
    if ($w_destinatarios > '')
      $w_resultado = EnviaMail($w_assunto, $w_html, $w_destinatarios, $w_anexos);

    if ($w_sg_tramite == 'xx') {
      // Remove o arquivo temporário
      if (!unlink($w_file)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATENÇÃO: não foi possível remover o arquivo temporário.\\n' . $w_file . '\');');
        ScriptClose();
      }
    }
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado > '') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\\n' . $w_resultado . '\');');
      ScriptClose();
    }
  }
}

function relAnexo() {
  extract($GLOBALS);
  global $w_Disabled;
  //exibeArray($_REQUEST);
  $w_chave       = $_REQUEST['w_chave'];
  $w_tipo_reg    = $_REQUEST['w_tipo_reg'];
  $w_cumprimento = $_REQUEST['w_cumprimento'];
  $SG = upper($_REQUEST['SG']);
  $par = upper($_REQUEST['par']);

  
  Cabecalho();
  head();
  ScriptOpen('JavaScript');
  ShowHTML('$(document).ready(function() {');
  ShowHTML('  $("#upload").uploadify({');
  ShowHTML('    "uploader": "' . $conRootSIW . 'classes/uploadify/uploadify.swf",');
  ShowHTML('      "script": "' . $conRootSIW . 'funcoes/upload.php",');
  ShowHTML('      "sizeLimit": "' . f($RS_Cliente, 'upload_maximo') . '",');
  //ShowHTML('      "script": "' . $conRootSIW . 'classes/uploadify/uploadify.php",');
  //ShowHTML('      "folder": "' . $conRootSIW . 'classes/uploadify/uploads-folder",');
  ShowHTML('      "buttonText": "Selecionar",');
  ShowHTML('      "scriptData": {"w_caminho":"' . DiretorioCliente($w_cliente) . '", "w_cumprimento":"' . $w_cumprimento . '", "w_chave":"' . $w_chave . '", "w_tipo_reg":"' . $w_tipo_reg . '", "w_cliente":"' . $w_cliente . '", "dbms":"' . $_SESSION['DBMS'] . '", "sid":"' . session_id() . '"},');
  ShowHTML('      "onAllComplete" : function(event,data) {alert(data.filesUploaded  + " arquivos(" + data.allBytesLoaded + " bytes) adicionados com sucesso.");document.location.href="' . montaURL_JS($w_dir, $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG='.$SG.'&w_chave=' . $w_chave . '&O=L' . '&w_cumprimento=' . $w_cumprimento) . '";},');
  //ShowHTML('      "onComplete" : function(event, queueID, fileObj, response, data) {alert(fileObj.name + response + data);},');
  ShowHTML('      "multi": "true",');
  ShowHTML('      "cancelImg": "' . $conRootSIW . 'classes/uploadify/cancel.png"');
  ShowHTML('  });');
  ShowHTML('});');
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpenClean('onLoad="this.focus();"');
  ShowHTML('<table width="100%" border="0" cellpadding="10" cellspacing="0">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Anexos</td></td></tr>');
  ShowHTML('      <tr valign="middle"><td colspan="5" align="left" height="1" bgcolor="#ffffff">Para adicionar anexos, clique em <b>selecionar</b>, localize os arquivos que deseja anexar, em seguida pressione o botão <b>Anexar arquivos</b>.');
  ShowHTML('          <br><br>Observações:<ul style="line-height:150%">');
  ShowHTML('<li>Pode-se usar a tecla <b>Ctrl</b> para selecionar mais de um arquivo no mesmo diretório.</li>');
  ShowHTML('<li>O botão <b>Limpar fila</b> limpa a fila de arquivos selecionados(ainda não anexados), caso se deseje descarta-los.</li>');
  ShowHTML('<li>O botão <img border="0" src="images/cancel.png"> para excluir arquivos específicos da lista.</li>');
  ShowHTML('</ul></td></tr>');
  ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr valign="top">');
  ShowHTML('<tr>');
  ShowHTML('<td align="center" bgcolor="#f5f5f5"><br>');
  ShowHTML('<br><input type="file" id="upload"><br>');
  ShowHTML('</td>');
  ShowHTML('</tr>');
  ShowHTML('<tr>');
  ShowHTML('<tr>');
  ShowHTML('<td align="center" bgcolor="#f5f5f5">');
  ShowHTML('  <button class="stb" onclick="javascript:$(\'#upload\').uploadifyUpload()">Anexar arquivos</button>');
  ShowHTML('  <button class="stb" onclick="javascript:$(\'#upload\').uploadifyClearQueue()">Limpar fila</button>');
  ShowHTML('  <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R. '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG='.$SG.'&w_chave=' . $w_chave . '&w_tipo_reg=' . $w_tipo_reg . '&w_cumprimento=' . $w_cumprimento . '&O=L') . '\';" name="Botao" value="Cancelar">');
  ShowHTML('');
  ShowHTML('</td>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
}

// -------------------------------------------------------------------------
// Rotina de anexos
// -------------------------------------------------------------------------
function Anexo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];
  $w_troca = $_REQUEST['w_troca'];
  if ($w_troca > '') {
    // Se for recarga da página
    $w_nome = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho = $_REQUEST['w_caminho'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente);
    $RS = SortArray($RS, 'nome', 'asc');
  } elseif (strpos('AEV', $O) !== false && $w_troca == '') {
    // Recupera os dados do endereço informado
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, $w_cliente);
    foreach ($RS as $row) {
      $w_nome = f($row, 'nome');
      $w_descricao = f($row, 'descricao');
      $w_caminho = f($row, 'chave_aux');
      break;
    }
  }
  Cabecalho();
  head();
  if (strpos('IAEP', $O) !== false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA', $O) !== false) {
      Validate('w_nome', 'Título', '1', '1', '1', '255', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '1', '1000', '1', '1');
      if ($O == 'I') {
        Validate('w_caminho', 'Arquivo', '', '1', '5', '255', '1', '1');
      }
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } elseif ($O == 'I') {
    BodyOpenClean('onLoad="document.Form.w_nome.focus();"');
  } elseif ($O == 'A') {
    BodyOpenClean('onLoad="document.Form.w_descricao.focus();"');
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . LinkArquivo('HL', $w_cliente, f($row, 'chave_aux'), '_blank', 'Clique para exibir o arquivo em outra janela.', f($row, 'nome'), null) . '</td>');
        ShowHTML('        <td>' . Nvl(f($row, 'descricao'), '---') . '</td>');
        ShowHTML('        <td>' . f($row, 'tipo') . '</td>');
        ShowHTML('        <td align="right">' . round(f($row, 'tamanho') / 1024, 1) . '&nbsp;</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=E&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV', $O) !== false) {
    if (strpos('EV', $O) !== false)
      $w_Disabled = ' DISABLED ';
    ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava&SG=' . $SG . '&O=' . $O . '" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
    ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
    ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
    ShowHTML('<INPUT type="hidden" name="P4" value="' . $P4 . '">');
    ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
    ShowHTML('<INPUT type="hidden" name="R" value="' . $R . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="' . $w_caminho . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O == 'I' || $O == 'A') {
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de ' . (f($RS_Cliente, 'upload_maximo') / 1024) . ' KBytes</b></font>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS_Cliente, 'upload_maximo') . '">');
    }
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="' . $w_nome . '" title="OBRIGATÓRIO. Informe um título para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea ' . $w_Disabled . ' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">' . $w_descricao . '</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input ' . $w_Disabled . ' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho > '') {
      ShowHTML('              <b>' . LinkArquivo('SS', $w_cliente, $w_caminho, '_blank', 'Clique para exibir o arquivo atual.', 'Exibir', null) . '</b>');
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O == 'I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      }
    }
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    //ShowHTML ' history.go(-1);'
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de prestação de contas
// -------------------------------------------------------------------------
function PrestarContas() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo_reg = '1';
  $w_readonly = '';
  $w_erro = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_chave_pai = f($RS, 'sq_solic_pai');
  $w_diarias = f($RS, 'diaria');
  $w_nm_diaria = f($RS, 'nm_diaria');
  $w_cumprimento_bd = f($RS, 'cumprimento');
  $w_reembolso_bd = f($RS, 'reembolso');

  // Recupera as possibilidades de vinculação financeira do reembolso
  $sql = new db_getPD_Financeiro; $RS_Financ = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, null, 'S', null, null);

  // Recupera as possibilidades de vinculação financeira da devolução de valores
  $sql = new db_getPD_Financeiro; $RS_Fin_Dev = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, null, null, 'S', null);

  if ($w_troca > '') {
    // Se for recarga da página
    $w_caminho = $_REQUEST['w_caminho'];
    $w_cumprimento = $_REQUEST['w_cumprimento'];
    $w_sq_bilhete = $_REQUEST['w_sq_bilhete'];
    $w_tipo = $_REQUEST['w_tipo'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
    $w_atual = $_REQUEST['w_atual'];
    $w_financeiro = $_REQUEST['w_financeiro'];
    $w_rubrica = $_REQUEST['w_rubrica'];
    $w_lancamento = $_REQUEST['w_lancamento'];
    $w_reembolso = $_REQUEST['w_reembolso'];
    $w_valor = $_REQUEST['w_valor'];
    $w_observacao = $_REQUEST['w_observacao'];
    $w_ressarcimento = $_REQUEST['w_ressarcimento'];
    $w_ressarcimento_data = $_REQUEST['w_ressarcimento_data'];
    $w_ressarcimento_valor = $_REQUEST['w_ressarcimento_valor'];
    $w_ressarcimento_observacao = $_REQUEST['w_ressarcimento_observacao'];
    $w_rub_dev = $_REQUEST['w_rub_dev'];
    $w_lan_dev = $_REQUEST['w_lan_dev'];
    $w_fin_dev = $_REQUEST['w_fin_dev'];
    $w_relatorio = $_REQUEST['w_relatorio'];
    $w_deposito = $_REQUEST['w_deposito'];
  } else {
    $w_cumprimento = f($RS, 'cumprimento');
    $w_nota_conclusao = f($RS, 'nota_conclusao');
    $w_atual = f($RS, 'sq_relatorio_viagem');
    $w_financeiro = f($RS, 'sq_pdvinculo_reembolso');
    $w_rubrica = f($RS, 'sq_rubrica_reemb');
    $w_lancamento = f($RS, 'sq_lancamento_reemb');
    $w_reembolso = f($RS, 'reembolso');
    $w_valor = formatNumber(f($RS, 'reembolso_valor'));
    $w_observacao = f($RS, 'reembolso_observacao');
    $w_ressarcimento = f($RS, 'ressarcimento');
    $w_ressarcimento_data = FormataDataEdicao(f($RS, 'ressarcimento_data'));
    $w_ressarcimento_valor = formatNumber(f($RS, 'ressarcimento_valor'));
    $w_ressarcimento_observacao = f($RS, 'ressarcimento_observacao');
    $w_rub_dev = f($RS, 'sq_rubrica_ressarc');
    $w_lan_dev = f($RS, 'sq_lancamento_ressarc');
    $w_fin_dev = f($RS, 'sq_pdvinculo_ressarcimento');
    $w_relatorio = f($RS, 'relatorio');
    $w_deposito = f($RS, 'deposito_identificado');
  }
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Prestação de contas</title>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  ValidateOpen('Validacao');
  Validate('w_cumprimento', 'Tipo de cumprimento', 'SELECT', '1', 1, 1, '1', '1');
  if (nvl($w_cumprimento, '') != '') {
    if ($w_cumprimento == 'P' || $w_cumprimento == 'C') {
      Validate('w_nota_conclusao', 'Motivo', '', '1', 1, 2000, '1', '1');
      if ($w_cumprimento == 'C' && $w_ressarcimento == 'S') {
        Validate('w_ressarcimento_data', 'Data de devolução', 'DATA', '1', 10, 10, '', '0123456789/');
        Validate('w_deposito', 'Código do depósito identificado', '', '', 1, 20, '1', 1);
        Validate('w_ressarcimento_valor', 'Valor da devolução', '', '1', 1, 18, '', '0123456789,.');
        CompValor('w_ressarcimento_valor', 'Valor da devolução', '>', '0,00', 'zero');
        if (count($RS_Fin_Dev) > 1) {
          Validate('w_rub_dev', 'Rubrica para crédito da devolução do valor', 'SELECT', '1', 1, 18, '', '1');
          Validate('w_lan_dev', 'Tipo de lançamento para devolução do valor', 'SELECT', '1', 1, 18, '', '1');
        }
        Validate('w_ressarcimento_observacao', 'Observação sobre a devolução', '', '1', 1, 2000, '1', '1');
      }
      //if ($w_cumprimento=='P') Validate('["w_tipo[]"]','Utilização','SELECT','1',1,1,'1','1');
    }
    if ($w_cumprimento != 'C' && $w_cumprimento != 'N') {
      Validate('w_relatorio', 'Relatório de viagem', '', '1', 1, 4000, '1', '1');
    }
    if ($w_reembolso == 'S' && count($RS_Financ) > 1) {
      Validate('w_rubrica', 'Rubrica para pagamento do reembolso', 'SELECT', '1', 1, 18, '', '1');
      Validate('w_lancamento', 'Tipo de lançamento para pagamento do reembolso', 'SELECT', '1', 1, 18, '', '1');
    }
    if ($w_cumprimento != 'C' && $w_ressarcimento == 'S') {
      Validate('w_ressarcimento_data', 'Data de devolução', 'DATA', '1', 10, 10, '', '0123456789/');
      Validate('w_deposito', 'Código do depósito identificado', '', '', 1, 20, '1', 1);
      Validate('w_ressarcimento_valor', 'Valor da devolução', '', '1', 1, 18, '', '0123456789,.');
      CompValor('w_ressarcimento_valor', 'Valor da devolução', '>', '0,00', 'zero');
      if (count($RS_Fin_Dev) > 1) {
        Validate('w_rub_dev', 'Rubrica para crédito da devolução do valor', 'SELECT', '1', 1, 18, '', '1');
        Validate('w_lan_dev', 'Tipo de lançamento para devolução do valor', 'SELECT', '1', 1, 18, '', '1');
      }
      Validate('w_ressarcimento_observacao', 'Observação sobre a devolução', '', '1', 1, 2000, '1', '1');
    }
    if ($w_cumprimento != 'C' && $w_cumprimento != 'N' && nvl($w_atual, '') != '') {
      ShowHTML('  if (theForm.w_caminho.value!="" && theForm.w_atual.value!="") {');
      ShowHTML('    alert("ATENÇÃO: Foi informado outro anexo do relatório de viagem.\nO ARQUIVO EXISTENTE SERÁ SUBSTITUÍDO!");');
      ShowHTML('  }');
    }
  }
  /*
    if ($w_cumprimento!='C' && $w_reembolso=='S') {
    Validate('w_valor','Valor do reembolso','','1',1,18,'','0123456789,.');
    CompValor('w_valor','Valor do reembolso','>','0,00','zero');
    Validate('w_observacao','Justificativa e memória de cálculo','','1',1,2000,'1','1');
    }
   */
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '' && $w_reembolso == 'S' && $w_ressarcimento == 'S' && nvl($w_cumprimento, '') != '' && $w_cumprimento != 'C') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpenClean('onLoad="document.Form.w_cumprimento.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . '</td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
  ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
  ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
  ShowHTML('<INPUT type="hidden" name="R" value="' . $w_pagina . $par . '">');
  ShowHTML('<INPUT type="hidden" name="SG" value="PDCONTAS">');
  ShowHTML('<INPUT type="hidden" name="O" value="' . $O . '">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_atual" value="' . $w_atual . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('  <table width="100%" border="0">');
  ShowHTML('    <tr valign="top">');
  SelecaoTipoCumprimento('Viagem al<u>t</u>erada:', 'T', 'Indique se houve alguma alteração no roteiro ou nos horários da viagem.', $w_cumprimento, null, 'w_cumprimento', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_cumprimento\'; document.Form.submit();"');
  ShowHTML('    </tr>');
  if ($w_cumprimento == 'I' || $w_cumprimento == 'P') {
    if ($w_cumprimento == 'P') {
      ShowHTML('    <tr><td colspan="2"><br><b>Motivo da alteração:</b></font></td></tr>');
      ShowHTML('    <tr><td colspan="2"><textarea ' . $w_Disabled . ' name="w_nota_conclusao" class="STI" ROWS=5 cols=75>' . $w_nota_conclusao . '</TEXTAREA></td>');
    }

    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms, $w_cliente);
    ShowHTML('      <tr><td colspan="2"><br><b>Relatório de viagem:</b></font></td></tr>');
    ShowHTML('      <tr><td colspan="2"><textarea ' . $w_Disabled . ' name="w_relatorio" class="STI" ROWS=5 cols=75>' . $w_relatorio . '</TEXTAREA></td>');

    ShowHTML('    <tr><td colspan="2"><br><b>Há reembolso?</b> ');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_reembolso" value="S" ' . (($w_reembolso == 'S') ? 'checked' : '') . '> Sim');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_reembolso" value="N" ' . (($w_reembolso == 'S') ? '' : 'checked') . '> Não');

    if ($w_reembolso == 'S') {
      if (count($RS_Financ) > 1) {
        ShowHTML('    <tr><td colspan="2"><br><b>Vinculação orçamentária-financeira</b></font></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rubrica, $w_chave_pai, 'B', 'w_rubrica', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lancamento, null, $w_cliente, 'w_lancamento', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rubrica, 10, '0', STR_PAD_LEFT) . 'B', null);
      } elseif (count($RS_Financ) == 1) {
        foreach ($RS_Financ as $row) {
          $RS_Financ = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_financeiro" value="' . f($RS_Financ, 'chave') . '">');
      }
    } else {
      ShowHTML('<INPUT type="hidden" name="w_valor" value="0,00">');
    }

    ShowHTML('    <tr><td colspan="2"><br><b>Há devolução de valores?</b> ');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="S" ' . (($w_ressarcimento == 'S') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_deposito\'; document.Form.submit();"> Sim');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="N" ' . ((nvl($w_ressarcimento, 'N') == 'N') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_ressarcimento_valor\'; document.Form.submit();"> Não');
    if ($w_ressarcimento == 'S') {
      // Recupera os dados do parâmetro
      $sql = new db_getFNParametro; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null);
      if (count($RS) > 0) {
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_devolucao = f($RS, 'texto_devolucao');
      }
      $sql = new db_getContaBancoList; $RSConta = $sql->getInstanceOf($dbms, $w_cliente, null, 'CONTADEV');
      if (count($RS) > 0) {
        $i = 0;
        foreach ($RSConta as $row) {
          if ($i == 0) {
            $contas = '<table cellpadding="2" cellspacing="0" bgcolor="#E5E5E5" style="color:#333333" border="1" width="100%">';
            $contas .= '<tr>';
            $contas .= '<td width="60%"><b>Banco</b></td>';
            $contas .= '<td width="20%"><b>Agência</b></td>';
            $contas .= '<td width="20%"><b>Conta</b></td>';
            $contas .= '</tr>';
          }
          $contas .= '<tr>';
          $contas .= '<td>' . f($row, 'banco') . '</td>';
          $contas .= '<td>' . f($row, 'agencia') . '</td>';
          $contas .= '<td>' . f($row, 'numero') . '</td>';
          $contas .= '</tr>';
          $i++;
        }
        if ($i > 0)
          $contas .= '</table>';
      }
      $w_ressarcimento_data = Nvl($w_ressarcimento_data, formataDataEdicao(Date('d/m/Y')));
      ShowHTML('    <tr><td colspan="2"><br><b>Dados da devolução<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
      if (nvl($contas, '') != '')
        ShowHTML('    <tr><td colspan=2 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131" size=2>ATENÇÃO:</b> ' . $w_devolucao . '<br>' . $contas . '</font></td></tr>');
      ShowHTML('    <tr><td colspan="2"><blockquote><TABLE BORDER="0">');
      ShowHTML('      <tr><td colspan="2"><b><u>D</u>ata:</b><br><input type="text" accesskey="I" name="w_ressarcimento_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_ressarcimento_data . '" title="Informe o a data da devolução." onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td><b>Código do depósito <u>i</u>dentificado:</b><br><input type="text" accesskey="I" name="w_deposito" class="sti" SIZE="20" MAXLENGTH="28" VALUE="' . $w_deposito . '" title="Informe o código do depósito identificado."></td>');
      ShowHTML('        <td><b><u>V</u>alor (R$):</b><br><input type="text" accesskey="V" name="w_ressarcimento_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_ressarcimento_valor . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da devolução."></td>');
      ShowHTML('      </tr>');
      if (count($RS_Fin_Dev) > 1) {
        ShowHTML('    <tr><td colspan="2"><br><b>Vinculação orçamentária-financeira</b></font></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_dev, $w_chave_pai, 'R', 'w_rub_dev', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_dev\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_dev, null, $w_cliente, 'w_lan_dev', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rub_dev, 10, '0', STR_PAD_LEFT) . 'R', null);
      } elseif (count($RS_Financ) == 1) {
        foreach ($RS_Fin_Dev as $row) {
          $RS_Fin_Dev = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_dev" value="' . f($RS_Fin_Dev, 'chave') . '">');
      }
      ShowHTML('      <tr><td colspan="2"><b>O<u>b</u>servação:</b><br><textarea ' . $w_Disabled . ' accesskey="B" name="w_ressarcimento_observacao" class="STI" ROWS=10 cols=75>' . $w_ressarcimento_observacao . '</TEXTAREA></td></tr>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_ressarcimento_valor" value="0,00">');
    }
    ShowHTML('    </table></blockquote>');
  } elseif ($w_cumprimento == 'C') {
    ShowHTML('<tr><td colspan="2"><br><b>Motivo do cancelamento:</b></font></td></tr>');
    ShowHTML('      <tr><td valign="top" colspan="2"><textarea ' . $w_Disabled . ' name="w_nota_conclusao" class="STI" ROWS=5 cols=75>' . $w_nota_conclusao . '</TEXTAREA></td>');
    ShowHTML('    <tr><td colspan="2"><br><b>Há devolução de valores?</b><br>');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="S" ' . (($w_ressarcimento == 'S') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_deposito\'; document.Form.submit();"> Sim');
    ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="N" ' . (($w_ressarcimento == 'N') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_ressarcimento_valor\'; document.Form.submit();"> Não');
    if ($w_ressarcimento == 'S') {
      // Recupera os dados do parâmetro
      $sql = new db_getFNParametro; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null);
      if (count($RS) > 0) {
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_devolucao = f($RS, 'texto_devolucao');
      }
      $sql = new db_getContaBancoList; $RSConta = $sql->getInstanceOf($dbms, $w_cliente, null, 'CONTADEV');
      if (count($RS) > 0) {
        $contas = '<table cellpadding="2" cellspacing="0" bgcolor="#E5E5E5" style="color:#333333" border="1" width="100%">';
        $contas .= '<tr>';
        $contas .= '<td width="60%"><b>Banco</b></td>';
        $contas .= '<td width="20%"><b>Agência</b></td>';
        $contas .= '<td width="20%"><b>Conta</b></td>';
        $contas .= '</tr>';
        foreach ($RSConta as $row) {
          $contas .= '<tr>';
          $contas .= '<td>' . f($row, 'banco') . '</td>';
          $contas .= '<td>' . f($row, 'agencia') . '</td>';
          $contas .= '<td>' . f($row, 'numero') . '</td>';
          $contas .= '</tr>';
        }
        $contas .= '</table>';
      }
      $w_ressarcimento_data = Nvl($w_ressarcimento_data, formataDataEdicao(Date('d/m/Y')));
      ShowHTML('    <tr><td colspan="2"><br><b>Dados da devolução<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
      ShowHTML('    <tr><td colspan=2 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131" size=2>ATENÇÃO:</b> ' . $w_devolucao . '<br>' . $contas . '</font></td></tr>');
      ShowHTML('    <tr><td colspan="2"><blockquote><TABLE BORDER="0">');
      ShowHTML('      <tr><td colspan="2"><b><u>D</u>ata:</b><br><input type="text" accesskey="I" name="w_ressarcimento_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_ressarcimento_data . '" title="Informe o a data da devolução." onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td><b>Código do depósito <u>i</u>dentificado:</b><br><input type="text" accesskey="I" name="w_deposito" class="sti" SIZE="20" MAXLENGTH="28" VALUE="' . $w_deposito . '" title="Informe o código do depósito identificado."></td>');
      ShowHTML('        <td><b><u>V</u>alor (R$):</b><br><input type="text" accesskey="V" name="w_ressarcimento_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_ressarcimento_valor . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da devolução."></td>');
      ShowHTML('      </tr>');
      if (count($RS_Fin_Dev) > 1) {
        ShowHTML('    <tr><td colspan="2"><br><b>Vinculação orçamentária-financeira</b></font></td></tr>');
        ShowHTML('      <tr valign="top">');
        SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_dev, $w_chave_pai, 'R', 'w_rub_dev', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_dev\'; document.Form.submit();"');
        SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_dev, null, $w_cliente, 'w_lan_dev', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rub_dev, 10, '0', STR_PAD_LEFT) . 'R', null);
      } elseif (count($RS_Financ) == 1) {
        foreach ($RS_Fin_Dev as $row) {
          $RS_Fin_Dev = $row;
          break;
        }
        ShowHTML('<INPUT type="hidden" name="w_fin_dev" value="' . f($RS_Fin_Dev, 'chave') . '">');
      }
      ShowHTML('      <tr><td colspan="2"><b>O<u>b</u>servação:</b><br><textarea ' . $w_Disabled . ' accesskey="B" name="w_ressarcimento_observacao" class="STI" ROWS=10 cols=75>' . $w_ressarcimento_observacao . '</TEXTAREA></td></tr>');
      ShowHTML('    </table></blockquote>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_ressarcimento_valor" value="0,00">');
    }
  }

  ShowHTML('    <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('    <tr><td align="center" colspan="2">');
  ShowHTML('        <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('        <input class="stb" type="button" onClick="javascript:window.close(); opener.focus();" name="Botao" value="Fechar">');
  ShowHTML('    </tr>');
  ShowHTML('    </table>');
  ShowHTML('</FORM>');

  if (nvl($w_cumprimento, '') != '' && $w_reembolso == 'S' && $w_reembolso_bd == 'S') {
    // Valores a serem reembolsados
    $sql = new db_getPD_Reembolso; $RS_Reembolso = $sql->getInstanceOf($dbms, $w_chave, null, null, null);
    $RS_Reembolso = SortArray($RS_Reembolso, 'sg_moeda', 'asc');

    ShowHTML('    <tr><td colspan="2"><br><br><b>Valores a serem reembolsados (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'ReembValor&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font>');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center" valign="top">');
    ShowHTML('          <td><b>Moeda</b></td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Justificativa</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS_Reembolso) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>INFORME OS VALORES A SEREM REEMBOLSADOS.</b></b></td></tr>');
    } else {
      foreach ($RS_Reembolso as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'nm_moeda') . ' (' . f($row, 'sg_moeda') . ')</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_solicitado')) . '&nbsp;&nbsp;&nbsp;</td>');
        ShowHTML('        <td>' . crlf2br(f($row, 'justificativa')) . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'ReembValor&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '" title="Altera os dados do valor.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '" title="Exclusão do valor de reembolso." onClick="return(confirm(\'Confirma exclusão do valor?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
  }

  if ($w_cumprimento == 'P' && $w_cumprimento_bd == 'P') {
    ShowHTML('<tr><td colspan="2"><br><br><b>Deslocamentos efetivamente cumpridos: (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'P', 'PDTRECHO');
    $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Origem</td>');
    ShowHTML('          <td><b>Destino</td>');
    ShowHTML('          <td><b>Saída</td>');
    ShowHTML('          <td><b>Chegada</td>');
    ShowHTML('          <td><b>Compromisso<br>dia viagem</td>');
    ShowHTML('          <td><b>Transporte</td>');
    ShowHTML('          <td><b>Bilhete</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>INFORME O ROTEIRO REALIZADO.</b></b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_destino') . '</td>');
        ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_saida'), 6), 0, -3) . '</td>');
        ShowHTML('        <td align="center">' . substr(FormataDataEdicao(f($row, 'phpdt_chegada'), 6), 0, -3) . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'nm_compromisso') . '</td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'nm_meio_transporte'), '---') . '</td>');
        ShowHTML('        <td align="center">' . f($row, 'nm_passagem') . '</td>');
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Exclusão do trecho." onClick="return(confirm(\'Confirma exclusão do trecho?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('</table>');
  }

  if (strpos('IPC', nvl($w_cumprimento, 'nulo')) !== false) {
    ShowHTML('<tr><td colspan="2"><br><br><b>Anexos do relatório (máximo de '.formatNumber((f($RS_Cliente, 'upload_maximo') / 1024), 0).' KBytes): (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'relAnexo&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&O=I&w_tipo_reg=' . $w_tipo_reg . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&w_cumprimento=' . $w_cumprimento . '&SG=PDTRECHO' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    $sql = new db_getPD_Deslocamento; //$RS = $sql->getInstanceOf($dbms, $w_chave, null, 'P', 'PDTRECHO');
    $sql = new db_getSolicRelAnexo; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente, $w_tipo_reg);
    //exibeArray($RS);
    $RS = SortArray($RS, 'nome', 'asc', 'tamanho', 'asc');
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td width="1%"><b>Nº</td>');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>NÃO HÁ ARQUIVOS EM ANEXO.</b></b></td></tr>');
    } else {
      $i = 1;
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center"><b>' . $i++ . '</b></td>');
        ShowHTML('        <td>' . LinkArquivo('HL', $w_cliente, f($row, 'chave_aux'), null, null, f($row,'nome'), null) . '</td>');
        //ShowHTML('        <td><a target="_blank" href="' . $conFileVirtual . $w_cliente . '/' . f($row, 'caminho') . '">' . f($row, 'nome') . '</a></td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'tipo'), '---') . '</td>');
        ShowHTML('        <td align="center">' . round(f($row, 'tamanho') / 1024, 1) . '</td>');
        ShowHTML('        <td align="center" nowrap>');
        //ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave_aux') . '&w_tipo_reg=' . f($row, 'tipo_reg') . '&w_chave=' . f($row, 'chave') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDRELANEXO' . MontaFiltro('GET') . '" title="Exclusão do arquivo." onClick="return(confirm(\'Confirma exclusão do arquivo?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('</table>');
  }

  if ($w_cumprimento == 'P' && $w_cumprimento_bd == 'P' && nvl($w_diarias, '') != '') {
    $sql = new db_getPD_Deslocamento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'P', 'PDDIARIA');
    $RS = SortArray($RS, 'phpdt_saida', 'asc', 'phpdt_chegada', 'asc');
    $i = 0;
    foreach ($RS as $row) {
      if ($i == 0)
        $w_inicio = f($row, 'saida');
      $w_fim = f($row, 'chegada');
      $i++;
    }
    reset($RS);

    ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
    ShowHTML('  function altera (solic, texto) {');
    ShowHTML('    document.Form1.w_chave.value=solic;');
    ShowHTML('    document.Form1.w_trechos.value=texto;');
    ShowHTML('    document.Form1.submit();');
    ShowHTML('  }');
    ShowHTML('</SCRIPT>');
    ShowHTML('<tr><td colspan="2"><br><br><b>Diárias efetivamente cumpridas: (Categoria ' . $w_nm_diaria . ')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('    <tr bgcolor="' . $conTrBgColor . '"><td colspan="2">');
    ShowHTML('      <table width="99%" border="0">');
    ShowHTML('        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">Informe somente após registrar todos os deslocamentos. Qualquer alteração nos deslocamentos irá remover todos os dados informados para as diárias.</font></td>');
    if (count($RS) > 0) {
      $i = 1;
      foreach ($RS as $row) {
        $w_trechos[$i][1] = f($row, 'sq_diaria');
        $w_trechos[$i][2] = f($row, 'sq_deslocamento');
        $w_trechos[$i][3] = f($row, 'sq_deslocamento');
        $w_trechos[$i][4] = f($row, 'cidade_dest');
        $w_trechos[$i][5] = f($row, 'nm_destino');
        $w_trechos[$i][6] = f($row, 'phpdt_chegada');
        $w_trechos[$i][7] = f($row, 'phpdt_saida');
        $w_trechos[$i][8] = Nvl(f($row, 'quantidade'), 0);
        $w_trechos[$i][9] = Nvl(f($row, 'valor'), 0);
        $w_trechos[$i][10] = f($row, 'saida');
        $w_trechos[$i][11] = f($row, 'chegada');
        $w_trechos[$i][12] = f($row, 'diaria');
        $w_trechos[$i][13] = f($row, 'sg_moeda_diaria');
        $w_trechos[$i][14] = f($row, 'vl_diaria');
        $w_trechos[$i][15] = f($row, 'hospedagem');
        $w_trechos[$i][16] = Nvl(f($row, 'hospedagem_qtd'), 0);
        $w_trechos[$i][17] = Nvl(f($row, 'hospedagem_valor'), 0);
        $w_trechos[$i][18] = f($row, 'sg_moeda_hospedagem');
        $w_trechos[$i][19] = f($row, 'vl_diaria_hospedagem');
        $w_trechos[$i][20] = f($row, 'veiculo');
        $w_trechos[$i][21] = Nvl(f($row, 'veiculo_qtd'), 0);
        $w_trechos[$i][22] = Nvl(f($row, 'veiculo_valor'), 0);
        $w_trechos[$i][23] = f($row, 'sg_moeda_veiculo');
        $w_trechos[$i][24] = f($row, 'vl_diaria_veiculo');
        $w_trechos[$i][25] = f($row, 'sq_valor_diaria');
        $w_trechos[$i][26] = f($row, 'sq_diaria_hospedagem');
        $w_trechos[$i][27] = f($row, 'sq_diaria_veiculo');
        $w_trechos[$i][28] = f($row, 'justificativa_diaria');
        $w_trechos[$i][29] = f($row, 'justificativa_veiculo');
        $w_trechos[$i][30] = f($row, 'compromisso');
        $w_trechos[$i][31] = f($row, 'compromisso');
        $w_trechos[$i][32] = 'N';
        $w_trechos[$i][33] = 'N';
        $w_trechos[$i][34] = f($row, 'sq_fin_dia');
        $w_trechos[$i][35] = f($row, 'sq_rub_dia');
        $w_trechos[$i][36] = f($row, 'sq_lan_dia');
        $w_trechos[$i][37] = f($row, 'sq_fin_hsp');
        $w_trechos[$i][38] = f($row, 'sq_rub_hsp');
        $w_trechos[$i][39] = f($row, 'sq_lan_hsp');
        $w_trechos[$i][40] = f($row, 'sq_fin_vei');
        $w_trechos[$i][41] = f($row, 'sq_rub_vei');
        $w_trechos[$i][42] = f($row, 'sq_lan_vei');
        $w_trechos[$i][43] = f($row, 'hospedagem_checkin');
        $w_trechos[$i][44] = f($row, 'hospedagem_checkout');
        $w_trechos[$i][45] = f($row, 'hospedagem_observacao');
        $w_trechos[$i][46] = f($row, 'veiculo_retirada');
        $w_trechos[$i][47] = f($row, 'veiculo_devolucao');
        $w_trechos[$i][48] = f($row, 'saida_internacional');
        $w_trechos[$i][49] = f($row, 'chegada_internacional');
        $w_trechos[$i][50] = f($row, 'origem_nacional');
        $w_trechos[$i][51] = f($row, 'destino_nacional');
        // Cria array para guardar o valor total por moeda
        if ($w_trechos[$i][13] > '')
          $w_total[$w_trechos[$i][13]] = 0;
        if ($w_trechos[$i][18] > '')
          $w_total[$w_trechos[$i][18]] = 0;
        if ($w_trechos[$i][12] > '')
          $w_total[$w_trechos[$i][23]] = 0;
        if ($i == 1) {
          // Se a primeira saída for após as 18:00, deduz meia diária
          if (intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_saida'), 2))) > 180000) {
            $w_trechos[$i][32] = 'S';
          }
        } else {
          // Se a última chegada for até 12:00, deduz meia diária
          if ($i == count($RS) && intVal(str_replace(':', '', formataDataEdicao(f($row, 'phpdt_chegada'), 2))) <= 120000) {
            $w_trechos[$i - 1][33] = 'S';
          }
          $w_trechos[$i - 1][3] = f($row, 'sq_deslocamento');
          $w_trechos[$i - 1][7] = f($row, 'phpdt_saida');
          $w_trechos[$i - 1][31] = f($row, 'compromisso');
        }
        $i += 1;
      }
      ShowHTML('     <tr><td align="center" colspan="2">');
      ShowHTML('       <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('         <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('           <td><b>Destino</td>');
      ShowHTML('           <td><b>Chegada</td>');
      ShowHTML('           <td><b>Saída</td>');
      ShowHTML('           <td><b>Operações</td>');
      ShowHTML('         </tr>');
      $w_cor = $conTrBgColor;
      $j = $i;
      $i = 1;
      $w_diarias = 0;
      $w_locacoes = 0;
      $w_hospedagens = 0;
      $w_tot_local = 0;
      AbreForm('Form1', $w_dir . $w_pagina . 'diarias', 'POST', 'return true;', null, $P1, $P2, $P3, $P4, $TP, 'PDDIARIA', $w_pagina . $par, 'A');
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('       <input type="hidden" name="w_chave" value="">');
      ShowHTML('       <input type="hidden" name="w_trechos" value="">');
      while ($i != ($j - 1)) {
        $w_max_hosp = floor(($w_trechos[$i][44] - $w_trechos[$i][43]) / 86400);
        $w_max_diaria = ceil(($w_trechos[$i][7] - $w_trechos[$i][6]) / 86400);
        $w_max_veiculo = ceil(($w_trechos[$i][47] - $w_trechos[$i][46]) / 86400);
        if (($i > 0 && $i < ($j - 1) && (($w_trechos[$i][51] == 'N' && toDate(FormataDataEdicao($w_trechos[$i][6])) == $w_fim) ||
                ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim)
                )
                ) ||
                ($w_max_hosp >= 0 &&
                $w_trechos[$i][48] == 0 &&
                $w_trechos[$i][49] == 0 &&
                ($w_trechos[$i][50] == 'S' || toDate(FormataDataEdicao($w_trechos[$i][6])) != $w_fim))
        ) {
          $w_diarias = nvl($w_trechos[$i][8], 0) * nvl($w_trechos[$i][9], 0);
          $w_locacoes = (-1 * nvl($w_trechos[$i][9], 0) * nvl($w_trechos[$i][22], 0) / 100 * nvl($w_trechos[$i][21], 0));
          $w_hospedagens = nvl($w_trechos[$i][16], 0) * nvl($w_trechos[$i][17], 0);

          if ($w_diarias > 0)
            $w_total[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes <> 0)
            $w_total[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_total[$w_trechos[$i][18]] += $w_hospedagens;

          $w_tot_local = $w_diarias + $w_locacoes;

          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('     <tr valign="top" bgcolor="' . $w_cor . '">');
          ShowHTML('       <td>' . $w_trechos[$i][5]);
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Saída após 18:00');
          if ($w_trechos[$i][32] == 'S')
            ShowHTML('<br>Chegada até 12:00');
          if ($w_trechos[$i][30] == 'N')
            ShowHTML('<br>Sem compromisso na ida');
          if ($w_trechos[$i][31] == 'N')
            ShowHTML('<br>Sem compromisso na volta');
          ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][6], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td align="center">' . substr(FormataDataEdicao($w_trechos[$i][7], 4), 0, -3) . '</b></td>');
          ShowHTML('       <td>');
          ShowHTML('          <A class="HL" HREF="javascript:altera(' . f($row, 'sq_siw_solicitacao') . ',\'' . base64_encode(serialize($w_trechos[$i])) . '\');" title="Informa as diárias">' . ((nvl($w_trechos[$i][1], '') == '') ? '<blink><b><font color="RED">Informar</font></b></blink>' : 'Informar') . '</A>&nbsp');
          ShowHTML('       </td>');
        }
        $i += 1;
      }
      ShowHTML('       </FORM>');
      ShowHTML('        </table></td></tr>');
    }
  }
  ShowHTML('  </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de registro do reembolso
// -------------------------------------------------------------------------
function Reembolso() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  $w_tipo_reg = '2';
  $w_readonly = '';
  $w_erro = '';

  // Recupera os dados da solicitação
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $w_chave, 'PDGERAL');
  $w_chave_pai = f($RS, 'sq_solic_pai');
  $w_or_tramite = f($RS, 'or_tramite');

  // Recupera as possibilidades de vinculação financeira
  $sql = new db_getPD_Financeiro; $RS_Financ = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, null, 'S', null, null);

  // Recupera as possibilidades de vinculação financeira da devolução de valores
  $sql = new db_getPD_Financeiro; $RS_Fin_Dev = $sql->getInstanceOf($dbms, $w_cliente, null, $w_chave_pai, null, null, null, null, null, null, null, null, 'S', null);

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca > '') {
    // Se for recarga da página
    $w_reembolso = $_REQUEST['w_reembolso'];
    $w_reembolso_bd = $_REQUEST['w_reembolso_bd'];
    $w_valor = $_REQUEST['w_valor'];
    $w_observacao = $_REQUEST['w_observacao'];
    $w_ressarcimento = $_REQUEST['w_ressarcimento'];
    $w_ressarcimento_valor = $_REQUEST['w_ressarcimento_valor'];
    $w_ressarcimento_observacao = $_REQUEST['w_ressarcimento_observacao'];
    $w_rub_dev = $_REQUEST['w_rub_dev'];
    $w_lan_dev = $_REQUEST['w_lan_dev'];
    $w_fin_dev = $_REQUEST['w_fin_dev'];
    $w_atual = $_REQUEST['w_atual'];
    $w_financeiro = $_REQUEST['w_financeiro'];
    $w_rubrica = $_REQUEST['w_rubrica'];
    $w_lancamento = $_REQUEST['w_lancamento'];
    $w_deposito = $_REQUEST['w_deposito'];
    $w_ressarcimento_data = $_REQUEST['w_ressarcimento_data'];
  } else {
    $w_reembolso = f($RS, 'reembolso');
    $w_reembolso_bd = f($RS, 'reembolso');
    $w_valor = formatNumber(f($RS, 'reembolso_valor'));
    $w_observacao = f($RS, 'reembolso_observacao');
    $w_ressarcimento = f($RS, 'ressarcimento');
    $w_ressarcimento_valor = formatNumber(f($RS, 'ressarcimento_valor'));
    $w_ressarcimento_observacao = f($RS, 'ressarcimento_observacao');
    $w_rub_dev = f($RS, 'sq_rubrica_ressarc');
    $w_lan_dev = f($RS, 'sq_lancamento_ressarc');
    $w_fin_dev = f($RS, 'sq_pdvinculo_ressarcimento');
    $w_atual = f($RS, 'sq_arquivo_comprovante');
    $w_financeiro = f($RS, 'sq_pdvinculo_reembolso');
    $w_rubrica = f($RS, 'sq_rubrica_reemb');
    $w_lancamento = f($RS, 'sq_lancamento_reemb');
    $w_deposito = f($RS, 'deposito_identificado');
    $w_ressarcimento_data = FormataDataEdicao(f($RS, 'ressarcimento_data'));
  }
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Dados do reembolso</title>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataValor();
  ValidateOpen('Validacao');
  if ($w_ressarcimento == 'S') {
    Validate('w_ressarcimento_data', 'Data de devolução', 'DATA', '1', 10, 10, '', '0123456789/');
    Validate('w_deposito', 'Código do depósito identificado', '', '', 1, 20, '1', 1);
    Validate('w_ressarcimento_valor', 'Valor da devolução', '', '1', 1, 18, '', '0123456789,.');
    CompValor('w_ressarcimento_valor', 'Valor da devolução', '>', '0,00', 'zero');
    if (count($RS_Fin_Dev) > 1) {
      Validate('w_rub_dev', 'Rubrica para crédito da devolução do valor', 'SELECT', '1', 1, 18, '', '1');
      Validate('w_lan_dev', 'Tipo de lançamento para devolução do valor', 'SELECT', '1', 1, 18, '', '1');
    }
    Validate('w_ressarcimento_observacao', 'Observação sobre a devolução', '', '1', 1, 2000, '1', '1');
  }
  if ($w_reembolso == 'S' && count($RS_Financ) > 1) {
    Validate('w_rubrica', 'Rubrica para pagamento do reembolso', 'SELECT', '1', 1, 18, '', '1');
    Validate('w_lancamento', 'Tipo de lançamento para pagamento do reembolso', 'SELECT', '1', 1, 18, '', '1');
  }
//  ShowHTML('  if (theForm.w_caminho.value!="" && theForm.w_atual.value!="") {');
//  ShowHTML('    alert("ATENÇÃO: Foi informado outro anexo do relatório de viagem.\nO ARQUIVO EXISTENTE SERÁ SUBSTITUÍDO!");');
//  ShowHTML('  }');
  
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '' && $w_reembolso == 'S' && $w_ressarcimento == 'S') {
    BodyOpenClean('onLoad="document.Form.' . $w_troca . '.focus();"');
  } else {
    BodyOpenClean('onLoad="document.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('  <table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS, 'codigo_interno') . '</td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS, 'phpdt_fim')) . ' </b></td>');
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  ShowHTML('  </table>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P1" value="' . $P1 . '">');
  ShowHTML('<INPUT type="hidden" name="P2" value="' . $P2 . '">');
  ShowHTML('<INPUT type="hidden" name="P3" value="' . $P3 . '">');
  ShowHTML('<INPUT type="hidden" name="TP" value="' . $TP . '">');
  ShowHTML('<INPUT type="hidden" name="R" value="' . $w_pagina . $par . '">');
  ShowHTML('<INPUT type="hidden" name="SG" value="' . $SG . '">');
  ShowHTML('<INPUT type="hidden" name="O" value="' . $O . '">');
  ShowHTML('<INPUT type="hidden" name="w_reembolso_bd" value="' . $w_reembolso_bd . '">');
  ShowHTML('<INPUT type="hidden" name="w_atual" value="' . $w_atual . '">');

  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('  <table width="100%" border="0">');
  ShowHTML('    <tr><td colspan="2"><b>Há reembolso?</b><br>');
  ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_reembolso" value="S" ' . (($w_reembolso == 'S') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_deposito\'; document.Form.submit();"> Sim');
  ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_reembolso" value="N" ' . (($w_reembolso == 'S') ? '' : 'checked') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_deposito\'; document.Form.submit();"> Não');
  if ($w_reembolso == 'S') {
    if (count($RS_Financ) > 1) {
      ShowHTML('    <tr><td colspan="2"><br><br><b>Vinculação orçamentária-financeira<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rubrica, $w_chave_pai, 'B', 'w_rubrica', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rubrica\'; document.Form.submit();"');
      SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lancamento, null, $w_cliente, 'w_lancamento', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rubrica, 10, '0', STR_PAD_LEFT) . 'B', null);
    } elseif (count($RS_Financ) == 1) {
      foreach ($RS_Financ as $row) {
        $RS_Financ = $row;
        break;
      }
      ShowHTML('<INPUT type="hidden" name="w_financeiro" value="' . f($RS_Financ, 'chave') . '">');
    }
  } else {
    ShowHTML('<INPUT type="hidden" name="w_valor" value="0,00">');
  }
  ShowHTML('    <tr><td colspan="2"><b>Há devolução de valores?</b><br>');
  ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="S" ' . (($w_ressarcimento == 'S') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_deposito\'; document.Form.submit();"> Sim');
  ShowHTML('      <input ' . $w_Disabled . ' type="radio" name="w_ressarcimento" value="N" ' . (($w_ressarcimento == 'N') ? 'checked' : '') . ' onClick="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_ressarcimento_valor\'; document.Form.submit();"> Não');
  if ($w_ressarcimento == 'S') {
    // Recupera os dados do parâmetro
    $sql = new db_getFNParametro; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, null);
    if (count($RS) > 0) {
      foreach ($RS as $row) {
        $RS = $row;
        break;
      }
      $w_devolucao = f($RS, 'texto_devolucao');
    }
    $sql = new db_getContaBancoList; $RSConta = $sql->getInstanceOf($dbms, $w_cliente, null, 'CONTADEV');
    if (count($RS) > 0) {
      $contas = '<table cellpadding="2" cellspacing="0" bgcolor="#E5E5E5" style="color:#333333" border="1" width="100%">';
      $contas .= '<tr>';
      $contas .= '<td width="60%"><b>Banco</b></td>';
      $contas .= '<td width="20%"><b>Agência</b></td>';
      $contas .= '<td width="20%"><b>Conta</b></td>';
      $contas .= '</tr>';
      foreach ($RSConta as $row) {
        $contas .= '<tr>';
        $contas .= '<td>' . f($row, 'banco') . '</td>';
        $contas .= '<td>' . f($row, 'agencia') . '</td>';
        $contas .= '<td>' . f($row, 'numero') . '</td>';
        $contas .= '</tr>';
      }
      $contas .= '</table>';
    }
    $w_ressarcimento_data = Nvl($w_ressarcimento_data, formataDataEdicao(Date('d/m/Y')));
    ShowHTML('    <tr><td colspan="2"><br><br><b>Dados da devolução<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td colspan=2 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131" size=2>ATENÇÃO:</b> ' . $w_devolucao . '<br>' . $contas . '</font></td></tr>');
    ShowHTML('    <tr><td colspan="2"><b><u>D</u>ata:</b><br><input type="text" accesskey="I" name="w_ressarcimento_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $w_ressarcimento_data . '" title="Informe o a data da devolução." onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('    <tr><td colspan="2"><b>Código do depósito <u>i</u>dentificado:</b><br><input type="text" accesskey="I" name="w_deposito" class="sti" SIZE="20" MAXLENGTH="28" VALUE="' . $w_deposito . '" title="Informe o código do depósito identificado."></td>');
    ShowHTML('    <tr><td colspan="2"><b><u>V</u>alor (R$):</b><br><input type="text" accesskey="V" name="w_ressarcimento_valor" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_ressarcimento_valor . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da devolução."></td>');
    if (count($RS_Fin_Dev) > 1) {
      ShowHTML('    <tr><td colspan="2"><br><br><b>Vinculação orçamentária-financeira</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      SelecaoRubrica('<u>R</u>ubrica:', 'R', 'Selecione a rubrica do projeto.', $w_rub_dev, $w_chave_pai, 'R', 'w_rub_dev', 'PDFINANC', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_rub_dev\'; document.Form.submit();"');
      SelecaoTipoLancamento('<u>T</u>ipo de lancamento:', 'T', 'Selecione na lista o tipo de lançamento adequado.', $w_lan_dev, null, $w_cliente, 'w_lan_dev', 'PDSV' . str_pad($w_chave_pai, 10, '0', STR_PAD_LEFT) . str_pad($w_rub_dev, 10, '0', STR_PAD_LEFT) . 'R', null);
    } elseif (count($RS_Fin_Dev) == 1) {
      foreach ($RS_Fin_Dev as $row) {
        $RS_Fin_Dev = $row;
        break;
      }
      ShowHTML('<INPUT type="hidden" name="w_fin_dev" value="' . f($RS_Fin_Dev, 'chave') . '">');
    }
    ShowHTML('    <tr><td colspan="2"><b>O<u>b</u>servação:</b><br><textarea ' . $w_Disabled . ' accesskey="B" name="w_ressarcimento_observacao" class="STI" ROWS=10 cols=75>' . $w_ressarcimento_observacao . '</TEXTAREA></td>');
  } else {
    ShowHTML('<INPUT type="hidden" name="w_ressarcimento_valor" value="0,00">');
  }


    ShowHTML('<tr><td colspan="2"><br><br><b>Arquivos contendo comprovantes (máximo de ' . formatNumber((f($RS_Cliente, 'upload_maximo') / 1024), 0) . ' KBytes): (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'relAnexo&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&w_tipo_reg=' . $w_tipo_reg . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&w_cumprimento=' . $w_cumprimento . '&SG=PDTRECHO' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    $sql = new db_getPD_Deslocamento; //$RS = $sql->getInstanceOf($dbms, $w_chave, null, 'P', 'PDTRECHO');
    $sql = new db_getSolicRelAnexo;
    $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente, $w_tipo_reg);

    $RS = SortArray($RS, 'nome', 'asc', 'tamanho', 'asc');
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td width="1%"><b>Nº</td>');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>NÃO HÁ ARQUIVOS EM ANEXO.</b></b></td></tr>');
    } else {
      $i = 1;

      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center"><b>' . $i++ . '</b></td>');
        ShowHTML('        <td>' . LinkArquivo('HL', $w_cliente, f($row, 'chave_aux'), null, null, f($row,'nome'), null) . '</td>');
        //ShowHTML('        <td><a target="_blank" href="' . $conFileVirtual . $w_cliente . '/' . f($row, 'caminho') . '">' . f($row, 'nome') . '</a></td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'tipo'), '---') . '</td>');
        ShowHTML('        <td align="center">' . round(f($row, 'tamanho') / 1024, 1) . '</td>');
        ShowHTML('        <td align="center" nowrap>');
        //ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'trechos&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'sq_deslocamento') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDTRECHO' . MontaFiltro('GET') . '" title="Altera os dados do trecho.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave_aux') . '&w_tipo_reg=' . f($row, 'tipo_reg'). '&w_chave=' . f($row, 'chave') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDRELANEXO' . MontaFiltro('GET') . '" title="Exclusão do arquivo." onClick="return(confirm(\'Confirma exclusão do arquivo?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('</table>');


//  ShowHTML('<tr><td colspan="2"><br><b>Arquivo contendo comprovantes (máximo de ' . formatNumber((f($RS_Cliente, 'upload_maximo') / 1024), 0) . ' KBytes)</b></font></td></tr>');
//  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS_Cliente, 'upload_maximo') . '">');
//  ShowHTML('<tr><td colspan="2"><input ' . $w_Disabled . ' type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="' . $w_caminho . '" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
//  if (nvl($w_atual, '') != '') {
//    ShowHTML('&nbsp;' . LinkArquivo('HL', $w_cliente, $w_atual, '_blank', 'Clique para exibir o arquivo em outra janela.', 'Exibir', null));
//    ShowHTML('&nbsp;<input ' . $w_Disabled . ' type="checkbox" name="w_exclui_arquivo" value="S" ' . ((nvl($w_exclui_aruivo, 'nulo') != 'nulo') ? 'checked' : '') . '>  Remover arquivo atual');
//  }




  ShowHTML('    <tr><td align="center" colspan="2" height="1" bgcolor="#000000"></TD></TR>');
  ShowHTML('    <tr><td align="center" colspan="2"><br/>');
  ShowHTML('        <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('        <input class="stb" type="button" onClick="javascript:window.close(); opener.focus();" name="Botao" value="Fechar">');
  ShowHTML('    </tr>');

  ShowHTML('  </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');

  if ($w_reembolso == 'S' && $w_reembolso_bd == 'S') {
    // Valores a serem reembolsados
    $sql = new db_getPD_Reembolso; $RS_Reembolso = $sql->getInstanceOf($dbms, $w_chave, null, null, null);
    $RS_Reembolso = SortArray($RS_Reembolso, 'sg_moeda', 'asc');

    ShowHTML('    <tr><td><br><br><b>Valores a serem reembolsados (<a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'ReembValor&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '"><u>I</u>ncluir</a>)<hr NOSHADE color=#000000 SIZE=1></b></font>');
    ShowHTML('      <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td colspan=3><b>Solicitação</b></td>');
    ShowHTML('          <td colspan=2><b>Autorização</b></td>');
    ShowHTML('          <td rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center" valign="top">');
    ShowHTML('          <td><b>Moeda</b></td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Justificativa</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Observação</td>');
    ShowHTML('        </tr>');
    if (count($RS_Reembolso) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=8 align="center"><font color="#BC3131"><b>INFORME OS VALORES A SEREM REEMBOLSADOS.</b></b></td></tr>');
    } else {
      foreach ($RS_Reembolso as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'sg_moeda') . ' (' . f($row, 'nm_moeda') . ')</td>');
        ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_solicitado')) . '&nbsp;&nbsp;&nbsp;</td>');
        ShowHTML('        <td>' . crlf2br(f($row, 'justificativa')) . '</td>');
        if ($w_or_tramite <= 11) {
          // No trâmite de prestação de contas
          ShowHTML('        <td align="center" colspan="2">&nbsp;</td>');
        } elseif ($w_or_tramite == 12 && f($row, 'valor_autorizado') == 0 && f($row, 'observacao') == '') {
          // No trâmite de verificação da prestação de contas mas sem valor informado.
          ShowHTML('        <td align="center" colspan="2">Em análise</td>');
        } else {
          // No trâmite de verificação da prestação de contas e com valor informado, ou em trâmite posterior a PC
          ShowHTML('        <td align="right">' . formatNumber(f($row, 'valor_autorizado')) . '</td>');
          ShowHTML('        <td>' . nvl(crlf2br(f($row, 'observacao')), '---') . '</td>');
        }
        ShowHTML('        <td nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'ReembValor&R=' . $w_pagina . $par . '&O=A&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '" title="Altera os dados do valor.">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Grava&R=' . $w_pagina . $par . '&O=E&w_chave_aux=' . f($row, 'chave') . '&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDVALRB' . MontaFiltro('GET') . '" title="Exclusão do valor de reembolso." onClick="return(confirm(\'Confirma exclusão do valor?\'));">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
  }

  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de cadastramento de valores de reembolso
// -------------------------------------------------------------------------
function ReembolsoValor() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro = '';
  $w_chave = $_REQUEST['w_chave'];
  $w_chave_aux = $_REQUEST['w_chave_aux'];

  // Recupera os dados da solicitação e do cliente
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, $SG);
  $w_cidade_padrao = f($RS_Cliente, 'sq_cidade_padrao');

  // Se viagem nacional, seleciona BRL automaticamente.
  if (f($RS_Solic, 'internacional') == 'N') {
    $sql = new db_getMoeda; $RS_Moeda = $sql->getInstanceOf($dbms, null, null, null, null, 'BRL');
    foreach ($RS_Moeda as $row) {
      $RS_Moeda = $row;
      break;
    }
    $w_moeda_padrao = f($RS_Moeda, 'sq_moeda');
  } else {
    $w_moeda_padrao = '';
  }

  if (f($RS_Solic, 'sg_tramite') == 'PC')
    $w_tipo_reg = 'S'; else
    $w_tipo_reg = 'P';

  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_moeda = $_REQUEST['w_moeda'];
    $w_valor_solicitado = $_REQUEST['w_valor_solicitado'];
    $w_justificativa = $_REQUEST['w_justificativa'];
    $w_valor_autorizado = $_REQUEST['w_valor_autorizado'];
    $w_observacao = $_REQUEST['w_observacao'];
  } elseif (strpos('AE', $O) !== false) {
    $sql = new db_getPD_Reembolso; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_moeda = f($RS, 'sq_moeda');
    $w_sg_moeda = f($RS, 'sg_moeda');
    $w_valor_solicitado = formatNumber(f($RS, 'valor_solicitado'));
    $w_justificativa = f($RS, 'justificativa');
    $w_valor_autorizado = formatNumber(f($RS, 'valor_autorizado'));
    $w_observacao = f($RS, 'observacao');
  }
  Cabecalho();
  head();
  ShowHTML('<title>' . $conSgSistema . ' - Cadastro de valor de reembolso</title>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  FormataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    if ($O == 'I' || $w_tipo_reg == 'S') {
      Validate('w_moeda', 'Moeda', 'SELECT', 1, 1, 18, '', '1');
      Validate('w_valor_solicitado', 'Valor solicitado', 'VALOR', '1', 4, 10, '', '0123456789.,');
      CompValor('w_valor_solicitado', 'Valor solicitado', '>', '0,00', 'zero');
      Validate('w_justificativa', 'Justificativa', '', '1', 1, 1000, '1', '1');
    }
    if ($w_tipo_reg == 'P') {
      Validate('w_valor_autorizado', 'Valor autorizado', 'VALOR', '1', 4, 10, '', '0123456789.,');
      Validate('w_observacao', 'Observação', '', '', 1, 1000, '1', '1');
      ShowHTML('  if (theForm.w_valor_solicitado.value!=theForm.w_valor_autorizado.value && theForm.w_observacao.value=="") {');
      ShowHTML('      alert("Registre o motivo do valor solicitado ter sido alterado!"); ');
      ShowHTML('      theForm.w_observacao.focus(); ');
      ShowHTML('      return (false); ');
      ShowHTML('  }');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  if ($w_troca > '') {
    BodyOpenClean('onLoad="document.Form.' . (($P1 != 1 && $w_passagem == 'S') ? $w_troca : 'Botao[0]') . '.focus();"');
  } elseif ($O == 'I' || $O == 'A') {
    if ($O == 'I' || $w_tipo_reg == 'S') {
      if (f($RS_Solic, 'internacional') == 'N') {
        BodyOpenClean('onLoad="document.Form.w_valor_solicitado.focus();"');
      } else {
        BodyOpenClean('onLoad="document.Form.w_moeda.focus();"');
      }
    } else {
      BodyOpenClean('onLoad="document.Form.w_valor_autorizado.focus();"');
    }
  } else {
    BodyOpenClean('onLoad="this.focus();"');
  }
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('    <tr><td align="center" bgcolor="#FAEBD7" colspan="2">');
  ShowHTML('      <table border=1 width="100%">');
  ShowHTML('        <tr><td valign="top" colspan="2">');
  ShowHTML('          <TABLE border=0 WIDTH="100%" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
  ShowHTML('            <tr><td>Número:<b><br>' . f($RS_Solic, 'codigo_interno') . '</td>');
  $sql = new db_getBenef; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, Nvl(f($RS_Solic, 'sq_prop'), 0), null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null);
  foreach ($RS1 as $row) {
    $RS1 = $row;
    break;
  }
  ShowHTML('                <td>Primeira saída:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_inicio')) . ' </b></td>');
  ShowHTML('                <td>Último retorno:<br><b>' . date('d/m/y, H:i', f($RS_Solic, 'phpdt_fim')) . ' </b></td>');
  ShowHTML('            <tr><td colspan="3">Beneficiário:<b><br>' . f($RS1, 'nm_pessoa') . '</td></tr>');
  ShowHTML('          </TABLE></td></tr>');
  ShowHTML('      </table>');
  if (strpos('IA', $O) !== false) {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="' . $w_chave_aux . '">');
    ShowHTML('<INPUT type="hidden" name="w_sg_tramite" value="' . f($RS_Solic, 'sg_tramite') . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_reg" value="' . $w_tipo_reg . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O == 'I' || $w_tipo_reg == 'S') {
      ShowHTML('      <tr valign="top">');
      if (f($RS_Solic, 'internacional') == 'N') {
        ShowHTML('<INPUT type="hidden" name="w_moeda" value="' . $w_moeda_padrao . '">');
        ShowHTML('        <td><b>Unidade monetária:</b><br>' . f($RS_Moeda, 'nome') . '</td>');
      } else {
        selecaoMoeda('<u>U</u>nidade monetária:', 'U', 'Selecione a unidade monetária na relação.', $w_moeda, null, 'w_moeda', 'PDRB', null);
      }
      ShowHTML('        <td><b><u>V</u>alor:</b><br><input type="text" ' . $w_Disabled . ' accesskey="V" name="w_valor_solicitado" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_solicitado . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
      ShowHTML('      </tr>');
      ShowHTML('      <tr><td colspan="2"><b><u>J</u>ustificativa:</b><br><textarea ' . $w_Disabled . ' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Justifique o valor solicitado.">' . $w_justificativa . '</TEXTAREA></td>');
    }
    if ($w_tipo_reg == 'P') {
      if ($O != 'I') {
        ShowHTML('<INPUT type="hidden" name="w_moeda" value="' . $w_moeda . '">');
        ShowHTML('<INPUT type="hidden" name="w_valor_solicitado" value="' . $w_valor_solicitado . '">');
        ShowHTML('<INPUT type="hidden" name="w_justificativa" value="' . $w_justificativa . '">');
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td nowrap><b>Valor solicitado:</b><br>' . $w_sg_moeda . ' ' . $w_valor_solicitado . '</td>');
        ShowHTML('        <td><b>Justificativa:</b><br>' . crlf2br($w_justificativa) . '</TEXTAREA></td>');
        ShowHTML('      </tr>');
      }
      ShowHTML('      <tr><td colspan="2"><b><u>V</u>alor autorizado:</b><br><input type="text" ' . $w_Disabled . ' accesskey="V" name="w_valor_autorizado" class="sti" SIZE="10" MAXLENGTH="18" VALUE="' . $w_valor_autorizado . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
      ShowHTML('      <tr><td colspan="2"><b><u>O</u>bservação: (obrigatório quando valor solicitado for alterado)</b><br><textarea ' . $w_Disabled . ' accesskey="J" name="w_observacao" class="STI" ROWS=5 cols=75 title="Se o valor autorizado for diferente do valor solicitado, é obrigatório informar o motivo. Caso contrário, este campo é opcional.">' . $w_observacao . '</TEXTAREA></td>');
    }
    ShowHTML('      <tr><td colspan="5"><table border="0" width="100%">');
    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
    ShowHTML('              <input class="stb" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Reembolso&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDREEMB&w_chave=' . $w_chave . '&O=L') . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);


  $w_file = '';
  $w_tamanho = '';
  $w_tipo = '';
  $w_nome = '';
  Cabecalho();
  ShowHTML('</head>');
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'PDIDENT':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'E' && f($RS_Menu,'cancela_sem_tramite')=='N') {
          $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, $w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'))) {
              unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
            }
          }
        }
        $SQL = new dml_putViagemGeral; $SQL->getInstanceOf($dbms, $O, $w_cliente,
                        $_REQUEST['w_chave'], $_REQUEST['w_menu'], $_SESSION['LOTACAO'], $_REQUEST['w_sq_unidade_resp'],
                        $_REQUEST['w_sq_prop'], $_SESSION['SQ_PESSOA'], $_REQUEST['w_tipo_missao'], $_REQUEST['w_descricao'],
                        $_REQUEST['w_assunto'], $_REQUEST['w_justif_dia_util'], $_REQUEST['w_inicio'], $_REQUEST['w_fim'],
                        $_REQUEST['w_data_hora'], $_REQUEST['w_aviso'], $_REQUEST['w_dias'], $_REQUEST['w_chave_pai'],
                        $_REQUEST['w_demanda'], $_REQUEST['w_inicio_atual'], $_REQUEST['w_passagem'],
                        $_REQUEST['w_diaria'], $_REQUEST['w_hospedagem'], $_REQUEST['w_veiculo'], $_REQUEST['w_proponente'],
                        $_REQUEST['w_financeiro'], $_REQUEST['w_rubrica'], $_REQUEST['w_lancamento'],
                        &$w_chave_nova, $w_copia, &$w_codigo);
        if ($O == 'I') {
          // Recupera os dados para montagem correta do menu
          $sql = new db_getMenuData; $RS1 = $sql->getInstanceOf($dbms, $w_menu);
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'' . $w_codigo . ' cadastrada com sucesso!\');');
          ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=A&w_chave=' . $w_chave_nova . '&w_documento=' . $w_codigo . '&R=' . $R . '&SG=' . f($RS1, 'sigla') . '&TP=' . RemoveTP($TP)) . '\';');
          ScriptClose();
        } elseif ($O == 'E') {
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          ScriptClose();
        } else {
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $SG);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS1, 'link') . '&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDOUTRA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putViagemOutra; $SQL->getInstanceOf($dbms, $O, $SG,
                        $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_sq_pessoa'],
                        $_REQUEST['w_cpf'], $_REQUEST['w_nome'], $_REQUEST['w_nome_resumido'],
                        $_REQUEST['w_sexo'], $_REQUEST['w_sq_tipo_vinculo'],
                        $_REQUEST['w_rg_numero'], $_REQUEST['w_rg_emissao'], $_REQUEST['w_rg_emissor'],
                        $_REQUEST['w_passaporte'], $_REQUEST['w_sq_pais_passaporte'], $_REQUEST['w_logradouro'],
                        $_REQUEST['w_complemento'], $_REQUEST['w_bairro'], $_REQUEST['w_sq_cidade'],
                        $_REQUEST['w_cep'], $_REQUEST['w_email'],
                        $_REQUEST['w_ddd'], $_REQUEST['w_nr_telefone'], $_REQUEST['w_nr_fax'],
                        $_REQUEST['w_nr_celular'], $_REQUEST['w_sq_agencia'], $_REQUEST['w_operacao'],
                        $_REQUEST['w_nr_conta'], $_REQUEST['w_sq_pais_estrang'], $_REQUEST['w_aba_code'],
                        $_REQUEST['w_swift_code'], $_REQUEST['w_endereco_estrang'], $_REQUEST['w_banco_estrang'],
                        $_REQUEST['w_agencia_estrang'], $_REQUEST['w_cidade_estrang'], $_REQUEST['w_informacoes'],
                        $_REQUEST['w_codigo_deposito'], $_REQUEST['w_sq_forma_pag']);

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDRELANEXO':
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($O == 'E') {
          $sql = new db_getSolicRelAnexo; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $w_cliente, $_REQUEST['w_tipo_reg']);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
            //echo($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
              unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
          }
          $SQL = new dml_putSolicRelAnexo; $SQL->getInstanceOf($dbms, $O, $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_tipo_reg'], $_REQUEST['w_nome'], $_REQUEST['w_descricao'], $w_file, $w_tamanho, $w_tipo, $w_nome);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=' . $O . '&w_cumprimento=' . $_REQUEST['w_cumprimento'] . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDDIARIA':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ($_POST['w_sq_diaria'] > '')
          $w_operacao = 'A'; else
          $w_operacao = 'I';

        $SQL = new dml_putPD_Diaria; $SQL->getInstanceOf($dbms, $w_operacao, $_REQUEST['w_chave'], $_POST['w_sq_diaria'],
                        $_POST['w_sq_cidade'], $_POST['w_diaria'], Nvl($_POST['w_quantidade'], 0), Nvl($_POST['w_vl_diaria'], 0),
                        $_POST['w_hospedagem'], $_POST['w_hospedagem_qtd'], $_POST['w_vl_diaria_hospedagem'],
                        $_POST['w_veiculo'], $_POST['w_veiculo_qtd'], $_POST['w_vl_diaria_veiculo'],
                        $_POST['w_desloc_chegada'], $_POST['w_desloc_saida'], $_POST['w_sq_valor_diaria'],
                        $_POST['w_sq_diaria_hospedagem'], $_POST['w_sq_diaria_veiculo'], $_POST['w_justificativa_diaria'],
                        $_POST['w_justificativa_veiculo'], $_POST['w_rub_dia'], $_POST['w_lan_dia'], $_POST['w_fin_dia'],
                        $_POST['w_rub_hsp'], $_POST['w_lan_hsp'], $_POST['w_fin_hsp'],
                        $_POST['w_rub_vei'], $_POST['w_lan_vei'], $_POST['w_fin_vei'],
                        $_REQUEST['w_hos_in'], $_REQUEST['w_hos_out'], $_REQUEST['w_hos_observ'],
                        $_REQUEST['w_vei_ret'], $_REQUEST['w_vei_dev'], $_REQUEST['w_tipo_reg'], $_REQUEST['w_origem'],
                        $_REQUEST['w_calc_dia_txt'], $_REQUEST['w_calc_hsp_txt'], $_REQUEST['w_calc_vei_txt']);

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDDIARIAFS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Dados;
        $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_fim_semana'], nvl($_REQUEST['w_qtd_comp'], '0,0'), nvl($_REQUEST['w_valor_comp'], '0,00'), nvl($_REQUEST['w_tot_comp'], '0,00'));
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'AltSolic&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&w_menu=' . $_REQUEST['w_menu'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDTRECHO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Deslocamento; $SQL->getInstanceOf($dbms, $O,
                        $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'],
                        $_REQUEST['w_cidade_orig'], $_REQUEST['w_data_saida'], $_REQUEST['w_hora_saida'],
                        $_REQUEST['w_cidade_dest'], $_REQUEST['w_data_chegada'], $_REQUEST['w_hora_chegada'],
                        $_REQUEST['w_cia_aerea'], $_REQUEST['w_codigo_voo'], $_REQUEST['w_passagem'],
                        $_REQUEST['w_meio_transp'], $_REQUEST['w_valor_trecho'], $_REQUEST['w_compromisso'],
                        $_REQUEST['w_aero_orig'], $_REQUEST['w_aero_dest'], $_REQUEST['w_tipo_reg']);
        ScriptOpen('JavaScript');
        if ($P1 == 1) {
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS1, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        } else {
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        }

        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'INFBIL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Bilhete; $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'],
                        $_REQUEST['w_cia_aerea'], null, null, $_REQUEST['w_data'], $_REQUEST['w_numero'], $_REQUEST['w_trecho'],
                        $_REQUEST['w_rloc'], $_REQUEST['w_classe'], $_REQUEST['w_valor_cheio'], $_REQUEST['w_valor_bil'], $_REQUEST['w_valor_tax'],
                        $_REQUEST['w_valor_pta'], explodeArray($_REQUEST['w_sq_deslocamento']), $_REQUEST['w_tipo'],
                        $_REQUEST['w_utilizado'], $_REQUEST['w_faturado'], $_REQUEST['w_observacao']);
        ScriptOpen('JavaScript');
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDVINC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Atividade; 
        if ($O == 'I') {
          for ($i = 0; $i <= count($_POST['w_demanda']) - 1; $i = $i + 1) {
            if (Nvl($_POST['w_demanda'][$i], '') > '') {
              $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_POST['w_demanda'][$i]);
            }
          }
        } elseif ($O == 'E') {
          $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_demanda']);
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '&w_chave=' . $_REQUEST['w_chave']) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'DADFIN':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Missao; $SQL->getInstanceOf($dbms, null, $_REQUEST['w_chave'], Nvl($_REQUEST['w_vlr_alimentacao'], 0), Nvl($_REQUEST['w_vlr_transporte'], 0), Nvl($_REQUEST['w_adicional'], 0),
                        Nvl($_REQUEST['w_desc_alimentacao'], 0), Nvl($_REQUEST['w_desc_trasnporte'], 0), null, null, null, null);
        $SQL = new dml_putPD_Diaria; 
        for ($i = 0; $i <= count($_POST['w_sq_diaria']) - 1; $i = $i + 1) {
          if ($_POST['w_sq_diaria'][$i] > '') {
            $SQL->getInstanceOf($dbms, 'A', $_REQUEST['w_chave'], $_POST['w_sq_diaria'][$i], $_POST['w_sq_cidade'][$i],
                            Nvl($_POST['w_qtd_diarias'][$i], 0), Nvl($_POST['w_vlr_diarias'][$i], 0));
          } else {
            $SQL->getInstanceOf($dbms, 'I', $_REQUEST['w_chave'], null, $_POST['w_sq_cidade'][$i],
                            Nvl($_POST['w_qtd_diarias'][$i], 0), Nvl($_POST['w_vlr_diarias'][$i], 0));
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'DadosFinanceiros&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&w_menu=' . $_REQUEST['w_menu'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'INFPASS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Missao; $SQL->getInstanceOf($dbms, null, $_REQUEST['w_chave'], null, null, null,
                        null, null, $_REQUEST['w_pta'], $_REQUEST['w_emissao_bilhete'], $_REQUEST['w_valor_passagem'], $SG);
        $SQL = new dml_putPD_Deslocamento; 
        for ($i = 0; $i <= count($_POST['w_sq_deslocamento']) - 1; $i = $i + 1) {
          $SQL->getInstanceOf($dbms, 'P', $_REQUEST['w_chave'], $_POST['w_sq_deslocamento'][$i], null, null, null, null, null, null,
                          $_POST['w_sq_cia_transporte'][$i], Nvl($_POST['w_codigo_voo'][$i], 0), null, null, null, null, null, null, null);
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Informarpassagens&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&w_menu=' . $_REQUEST['w_menu'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'COTPASS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $SQL = new dml_putPD_Cotacao; $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], Nvl($_POST['w_valor'], 0), $_POST['w_observacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'InformarCotacao&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&w_menu=' . $_REQUEST['w_menu'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDANEXO' :
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if (UPLOAD_ERR_OK === 0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          $w_tamanho = 0;
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            $w_tamanho = $Field['size'];
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              // Se já há um nome para o arquivo, mantém
              if ($_REQUEST['w_atual'] > '') {
                $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_atual'], $w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
                    unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
                  if (strpos(f($row, 'caminho'), '.') !== false) {
                    $w_file = substr(basename(f($row, 'caminho')), 0, (strpos(basename(f($row, 'caminho')), '.') ? strpos(basename(f($row, 'caminho')), '.') + 1 : 0) - 1) . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 30);
                  } else {
                    $w_file = basename(f($row, 'caminho'));
                  }
                }
              } else {
                $w_file = str_replace('.tmp', '', basename($Field['tmp_name']));
                if (strpos($Field['name'], '.') !== false) {
                  $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
                }
              }

              $w_tipo = $Field['type'];
              $w_nome = $Field['name'];
              if ($w_file > '') {
                move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
              }
            } elseif (nvl($Field['name'], '') != '') {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
          }
          // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.
          if ($O == 'E' && $_REQUEST['w_atual'] > '') {
            $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_atual'], $w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
                unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
            }
          }
          $SQL = new dml_putSolicArquivo; $SQL->getInstanceOf($dbms, $O, $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_nome'], $_REQUEST['w_descricao'], $w_file, $w_tamanho, $w_tipo, $w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDCONTAS' :
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if (UPLOAD_ERR_OK === 0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              // Se já há um nome para o arquivo, mantém
              if ($_REQUEST['w_atual'] > '') {
                $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDGERAL');
                if (file_exists($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo')))
                  unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo'));
                if (strpos(f($RS, 'cm_arquivo'), '.') !== false) {
                  $w_file = substr(basename(f($RS, 'cm_arquivo')), 0, (strpos(basename(f($RS, 'cm_arquivo')), '.') ? strpos(basename(f($RS, 'cm_arquivo')), '.') + 1 : 0) - 1) . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 30);
                } else {
                  $w_file = basename(f($RS, 'cm_arquivo'));
                }
              } else {
                $w_file = str_replace('.tmp', '', basename($Field['tmp_name']));
                if (strpos($Field['name'], '.') !== false) {
                  $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
                }
              }
              $w_tamanho = $Field['size'];
              $w_tipo = $Field['type'];
              $w_nome = $Field['name'];
              if ($w_file > '') {
                move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
              }
            } elseif (nvl($Field['name'], '') != '') {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
          }
          // Se for remoção do arquivo do disco.
          if ($_REQUEST['w_exclui_arquivo'] > '' || $_REQUEST['w_cumprimento'] == 'C') {
            $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDGERAL');
            if (file_exists($conFilePhysical . $w_cliente . '/' . nvl(f($RS, 'cm_arquivo'), 'x')))
              unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo'));
          }

          // Grava dados da missão
          $SQL = new dml_putPD_Contas; $SQL->getInstanceOf($dbms,
                          $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_cumprimento'], $_REQUEST['w_nota_conclusao'], $_REQUEST['w_relatorio'],
                          $_REQUEST['w_atual'], $_REQUEST['w_exclui_arquivo'], 'Relatório de viagem',
                          'Anexo do relatório de viagem (' . $_REQUEST['w_chave'] . ')',
                          $w_file, $w_tamanho, $w_tipo, $w_nome);

          // Grava dados do reembolso
          $SQL = new dml_putPD_Reembolso; $SQL->getInstanceOf($dbms,
                          $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_reembolso'], $_REQUEST['w_deposito'],
                          $_REQUEST['w_valor'], $_REQUEST['w_observacao'],
                          $_REQUEST['w_financeiro'], $_REQUEST['w_rubrica'], $_REQUEST['w_lancamento'], $_REQUEST['w_ressarcimento'],
                          $_REQUEST['w_ressarcimento_data'], $_REQUEST['w_ressarcimento_valor'], $_REQUEST['w_ressarcimento_observacao'],
                          $_REQUEST['w_fin_dev'], $_REQUEST['w_rub_dev'], $_REQUEST['w_lan_def'], null, null, null, null, null);

          /*
            // Grava dados dos bilhetes
            $SQL = new dml_putPD_Bilhete; 
            for ($i=0; $i<=count($_POST['w_sq_bilhete'])-1; $i=$i+1) {
            if (Nvl($_POST['w_sq_bilhete'][$i],'')>'') {
            $SQL->getInstanceOf($dbms,'C',$_REQUEST['w_chave'],$_POST['w_sq_bilhete'][$i],
            null,null,null,null,null,null,null,null,null,null,null,null,null,$_POST['w_tipo'][$i],null);
            }
            }
           */
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDALTERA' :
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if (UPLOAD_ERR_OK === 0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              // Se já há um nome para o arquivo, mantém
              if ($_REQUEST['w_atual'] > '') {
                $sql = new db_getPD_Alteracao; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], null, null, null, null, null);
                foreach ($RS as $row) {
                  $RS = $row;
                  break;
                }
                if (file_exists($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo')))
                  unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo'));
                if (strpos(f($RS, 'cm_arquivo'), '.') !== false) {
                  $w_file = substr(basename(f($RS, 'cm_arquivo')), 0, (strpos(basename(f($RS, 'cm_arquivo')), '.') ? strpos(basename(f($RS, 'cm_arquivo')), '.') + 1 : 0) - 1) . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 30);
                } else {
                  $w_file = basename(f($RS, 'cm_arquivo'));
                }
              } else {
                $w_file = str_replace('.tmp', '', basename($Field['tmp_name']));
                if (strpos($Field['name'], '.') !== false) {
                  $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
                }
              }
              $w_tamanho = $Field['size'];
              $w_tipo = $Field['type'];
              $w_nome = $Field['name'];
              if ($w_file > '') {
                move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
              }
            } elseif (nvl($Field['name'], '') != '') {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
          }
          // Se for remoção do arquivo do disco.
          if ($_REQUEST['w_exclui_arquivo'] > '' || $O == 'E') {
            $sql = new db_getPD_Alteracao; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], null, null, null, null, null);
            foreach ($RS as $row) {
              $RS = $row;
              break;
            }
            if (nvl(f($RS, 'cm_arquivo'), '') != '') {
              if (file_exists($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo')))
                unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo'));
            }
          }

          // Grava dados da missão
          $SQL = new dml_putPD_Alteracao; $SQL->getInstanceOf($dbms, $O, $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'],
                          $_REQUEST['w_valor_tar'], $_REQUEST['w_valor_tax'], $_REQUEST['w_valor_hsp'], $_REQUEST['w_valor_dia'],
                          $_REQUEST['w_justificativa'], $_REQUEST['w_pessoa'], $_REQUEST['w_cargo'], $_REQUEST['w_data'],
                          $_REQUEST['w_exclui_arquivo'], $w_file, $w_tamanho, $w_tipo, $w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDREEMB' :
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if (UPLOAD_ERR_OK === 0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              // Se já há um nome para o arquivo, mantém
              if ($_REQUEST['w_atual'] > '') {
                $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDGERAL');
                if (file_exists($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo_comprovante')))
                  unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo_comprovante'));
                if (strpos(f($RS, 'cm_arquivo_comprovante'), '.') !== false) {
                  $w_file = substr(basename(f($RS, 'cm_arquivo_comprovante')), 0, (strpos(basename(f($RS, 'cm_arquivo_comprovante')), '.') ? strpos(basename(f($RS, 'cm_arquivo_comprovante')), '.') + 1 : 0) - 1) . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 30);
                } else {
                  $w_file = basename(f($RS, 'cm_arquivo_comprovante'));
                }
              } else {
                $w_file = str_replace('.tmp', '', basename($Field['tmp_name']));
                if (strpos($Field['name'], '.') !== false) {
                  $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
                }
              }
              $w_tamanho = $Field['size'];
              $w_tipo = $Field['type'];
              $w_nome = $Field['name'];
              if ($w_file > '') {
                move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
              }
            } elseif (nvl($Field['name'], '') != '') {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
          }
          // Se for remoção do arquivo do disco.
          if ($_REQUEST['w_exclui_arquivo'] > '' || $_REQUEST['w_cumprimento'] == 'C') {
            $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDGERAL');
            if (file_exists($conFilePhysical . $w_cliente . '/' . nvl(f($RS, 'cm_arquivo_comprovante'), 'x')))
              unlink($conFilePhysical . $w_cliente . '/' . f($RS, 'cm_arquivo_comprovante'));
          }
          // Grava dados do reembolso
          $SQL = new dml_putPD_Reembolso; $SQL->getInstanceOf($dbms,
                          $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_reembolso'], $_REQUEST['w_deposito'], $_REQUEST['w_valor'],
                          $_REQUEST['w_observacao'], $_REQUEST['w_financeiro'], $_REQUEST['w_rubrica'], $_REQUEST['w_lancamento'],
                          $_REQUEST['w_ressarcimento'], $_REQUEST['w_ressarcimento_data'], $_REQUEST['w_ressarcimento_valor'],
                          $_REQUEST['w_ressarcimento_observacao'], $_REQUEST['w_fin_dev'], $_REQUEST['w_rub_dev'], $_REQUEST['w_lan_def'],
                          $_REQUEST['w_exclui_arquivo'], $w_file, $w_tamanho, $w_tipo, $w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        }

        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDVALRB' :
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        // Verifica se a moeda indicada já foi informada em outro lançamento
        $sql = new db_getPD_Reembolso; $RS_Reembolso = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, null, null);
        foreach ($RS_Reembolso as $row) {
          if (f($row, 'sq_moeda') == $_REQUEST['w_moeda'] && (f($row, 'chave') != nvl($_REQUEST['w_chave_aux'], 0))) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Moeda já informada. Se necessário, some os valores!\');');
            ScriptClose();
            retornaFormulario('w_moeda');
            exit();
          }
          break;
        }

        // Grava dados do reembolso
        $SQL = new dml_putPD_ReembValor; $SQL->getInstanceOf($dbms, $O,
                        $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_moeda'], $_REQUEST['w_valor_solicitado'],
                        $_REQUEST['w_justificativa'], $_REQUEST['w_valor_autorizado'], $_REQUEST['w_observacao']);

        ScriptOpen('JavaScript');
        if ($_REQUEST['w_sg_tramite'] == 'PC') {
          // Volta para tela de prestação de contas
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'PrestarContas&O=A&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDREEMB' . MontaFiltro('GET')) . '\';');
        } else {
          // Volta para tela de reembolso
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Reembolso&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=PDREEMB' . MontaFiltro('GET')) . '\';');
        }

        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        if ((false !== (strpos(upper($_SERVER['HTTP_CONTENT_TYPE']), 'MULTIPART/FORM-DATA'))) || (false !== (strpos(upper($_SERVER['CONTENT_TYPE']), 'MULTIPART/FORM-DATA')))) {
          // Verifica se outro usuário já enviou a solicitação
          $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDINICIAL');
          if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite'] && f($RS, 'sg_tramite') != 'CI') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          } else {
            // Se foi feito o upload de um arquivo
            if (UPLOAD_ERR_OK == 0) {
              $w_maximo = $_REQUEST['w_upload_maximo'];
              foreach ($_FILES as $Chv => $Field) {
                if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
                  // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_observacao');
                  exit();
                }
                if ($Field['size'] > 0) {
                  // Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
                  if ($Field['size'] > $w_maximo) {
                    ScriptOpen('JavaScript');
                    ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!\');');
                    ScriptClose();
                    retornaFormulario('w_observacao');
                    exit();
                  }
                  // Se já há um nome para o arquivo, mantém
                  $w_file = basename($Field['tmp_name']);
                  if (strpos($Field['name'], '.') !== false) {
                    $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
                  }
                  $w_tamanho = $Field['size'];
                  $w_tipo = $Field['type'];
                  $w_nome = $Field['name'];
                  if ($w_file > '')
                    move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
                } elseif (nvl($Field['name'], '') != '') {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_caminho');
                  exit();
                }
              }
              $SQL = new dml_putDemandaEnvio; $SQL->getInstanceOf($dbms, $w_menu, $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                              $_REQUEST['w_novo_tramite'], 'N', $_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],
                              $w_file, $w_tamanho, $w_tipo, $w_nome);
            } else {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
              ScriptClose();
            }
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
            ScriptClose();
          }
        } else {
          $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PDINICIAL');
          if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          } else {
            // Verifica o próximo trâmite
            if ($_REQUEST['w_envio'] == 'N') {
              $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite'], null, 'PROXIMO', null);
            } else {
              $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite'], null, 'ANTERIOR', null);
            }
            foreach ($RS as $row) {
              $RS = $row;
              break;
            }
            $sql = new db_getTramiteSolic; $RS1 = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], f($RS, 'sq_siw_tramite'), null, null);
            if (count($RS1) <= 0) {
              foreach ($RS1 as $row) {
                $RS1 = $row;
                break;
              }
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite "' . f($RS, 'nome') . '"!\');');
              ScriptClose();
              retornaFormulario('w_despacho');
            }
            if ($_REQUEST['w_envio'] == 'N') {
              $SQL = new dml_putViagemEnvio; $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], null,
                              $_REQUEST['w_envio'], $_REQUEST['w_despacho'], $_REQUEST['w_justificativa'], $_REQUEST['w_justif_dia_util']);
            } else {
              $SQL = new dml_putViagemEnvio; $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], $_REQUEST['w_novo_tramite'],
                              $_REQUEST['w_envio'], $_REQUEST['w_despacho'], $_REQUEST['w_justificativa'], $_REQUEST['w_justif_dia_util']);
            }
            if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
              $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
              $w_sg_tramite = f($RS, 'sigla');
              if ($w_sg_tramite == 'CI' || ($w_sg_tramite == 'DF' || $w_sg_tramite == 'AE' || $w_sg_tramite == 'PC' || $w_sg_tramite == 'VP')) {
                $w_html = VisualViagem($_REQUEST['w_chave'], 'L', $w_usuario, $P1, '1');
                CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
              }
            }
            // Envia e-mail comunicando de tramitação
            SolicMail($_REQUEST['w_chave'], 2);
            if ($P1 == 1) {
              // Se for envio da fase de cadastramento, remonta o menu principal
              // Recupera os dados para montagem correta do menu
              $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms, $w_menu);
              ScriptOpen('JavaScript');
              ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG=' . f($RS, 'sigla') . '&TP=' . RemoveTP(RemoveTP($TP)) . MontaFiltro('GET')) . '\';');
              ScriptClose();
            } else {
              // Volta para a listagem
              ScriptOpen('JavaScript');
              ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
              ScriptClose();
            }
          }
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    case 'PDCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $SG);
        if (f($RS, 'concluida') == 'S') {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já concluiu esta solicitação!\');');
          ScriptClose();
          exit();
        } else {
          $SQL = new dml_putDemandaConc; $SQL->getInstanceOf($dbms, $w_menu, $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], $_REQUEST['w_inicio_real'], $_REQUEST['w_fim_real'], $_REQUEST['w_nota_conclusao'], $_REQUEST['w_custo_real'],
                          $w_file, $w_tamanho, $w_tipo, $w_nome);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: ' . $SG . '\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
      break;
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
    case 'ALTERACOES': RegistroAlteracao();
      break;
    case 'IMPRIMEALTERACAO': ImprimeAlteracao();
      break;
    case 'INICIAL': Inicial();
      break;
    case 'GERAL': Geral();
      break;
    case 'OUTRA': OutraParte();
      break;
    case 'TRECHOS': Trechos();
      break;
    case 'BILHETES': Bilhetes();
      break;
    case 'VINCULACAO': Vinculacao();
      break;
    case 'DADOSFINANCEIROS': DadosFinanceiros();
      break;
    case 'PAGDIARIA': PagamentoDiaria();
      break;
    case 'DIARIAS': Diarias();
      break;
    case 'ALTSOLIC': AltSolic();
      break;
    case 'DIARIAS_SOLIC': Diarias_Solic();
      break;
    case 'PRESTARCONTAS': PrestarContas();
      break;
    case 'REEMBOLSO': Reembolso();
      break;
    case 'REEMBVALOR': ReembolsoValor();
      break;
    case 'VISUAL': Visual();
      break;
    case 'EXCLUIR': Excluir();
      break;
    case 'ENVIO': Encaminhamento();
      break;
    case 'ANOTACAO': Anotar();
      break;
    case 'CONCLUIR': Concluir();
      break;
    case 'INFORMARPASSAGENS': InformarPassagens();
      break;
    case 'INFORMARCOTACAO': InformarCotacao();
      break;
    case 'ANEXO': Anexo();
      break;
    case 'RELANEXO': relAnexo();
      break;
    case 'GRAVA': Grava();
      break;
    default:
      Cabecalho();
      ShowHTML('<base HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
      break;
  }
}

?>