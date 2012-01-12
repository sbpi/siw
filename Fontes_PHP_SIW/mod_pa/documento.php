<?php
header('Expires: ' . -1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicPA.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getUnidade_PA.php');
include_once($w_dir_volta.'classes/sp/db_getDocumentoInter.php');
include_once($w_dir_volta.'classes/sp/db_getDocumentoAssunto.php');
include_once($w_dir_volta.'classes/sp/db_getProtocolo.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicInter.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoAssunto.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putCaixaEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoReceb.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoCaixa.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoArqSet.php');
include_once($w_dir_volta.'classes/sp/dml_putDocumentoDescarte.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoNaturezaDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEspecieDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoOrigem.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoAssunto.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoAssuntoRadio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDespacho.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoCaixa.php');
include_once('validadocumento.php');
include_once('visualdocumento.php');
include_once('visualGR.php');
include_once('visualGT.php');
// =========================================================================
//  /documento.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o módulo de protocolo e arquivos
// Mail     : celso@sbpi.com.br
// Criacao  : 09/08/2006 18:30
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
$w_pagina = 'documento.php?par=';
$w_Disabled = 'ENABLED';
$w_dir = 'mod_pa/';
$w_troca = $_REQUEST['w_troca'];
$p_ordena = lower($_REQUEST['p_ordena']);
$w_SG = upper($_REQUEST['w_SG']);

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }
// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos('PADOCANEXO,PAINTERESS,PADOCASS', $SG) !== false) {
  if ($O != 'I' && $O != 'E' && nvl($_REQUEST['w_chave_aux'], $_REQUEST['w_sq_pessoa']) == '')
    $O = 'L';
} elseif ($SG == 'PADENVIO') {
  $O = 'V';
} elseif ($O == '') {
  // Se for acompanhamento, entra na filtragem
  if ($P1 == 3 || $SG == 'PADTRAM' || $SG == 'PADRECEB' || $SG == 'PACLASSIF' || $SG == 'PAENVCEN')
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
  case 'R': $w_TP = $TP . ' - Recebimento';
    break;
  case 'H': $w_TP = $TP . ' - Herança';
    break;
  default:
    if ($par == 'BUSCAPROC')
      $w_TP = $TP . ' - Busca procedência'; else
      $w_TP=$TP . ' - Listagem';
    break;
}
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu = RetornaMenu($w_cliente, $SG);
$w_ano = RetornaAno();
$w_copia = $_REQUEST['w_copia'];

$p_numero_doc       = upper($_REQUEST['p_numero_doc']);
$p_atividade        = upper($_REQUEST['p_atividade']);
$p_ativo            = upper($_REQUEST['p_ativo']);
$p_solicitante      = upper($_REQUEST['p_solicitante']);
$p_prioridade       = upper($_REQUEST['p_prioridade']);
$p_proponente       = upper($_REQUEST['p_proponente']);
$p_ini_i            = upper($_REQUEST['p_ini_i']);
$p_ini_f            = upper($_REQUEST['p_ini_f']);
$p_fim_i            = upper($_REQUEST['p_fim_i']);
$p_fim_f            = upper($_REQUEST['p_fim_f']);
$p_atraso           = upper($_REQUEST['p_atraso']);
$p_assunto          = upper($_REQUEST['p_assunto']);
$p_pais             = upper($_REQUEST['p_pais']);
$p_regiao           = upper($_REQUEST['p_regiao']);
$p_uf               = upper($_REQUEST['p_uf']);
$p_tipo             = upper($_REQUEST['p_tipo']);
$p_cidade           = upper($_REQUEST['p_cidade']);
$p_usu_resp         = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp        = upper($_REQUEST['p_uorg_resp']);
$p_internas         = upper($_REQUEST['p_internas']);
$p_palavra          = upper($_REQUEST['p_palavra']);
$p_prazo            = upper($_REQUEST['p_prazo']);
$p_fase             = explodeArray($_REQUEST['p_fase']);
$p_sq_acao_ppa      = upper($_REQUEST['p_sq_acao_ppa']);
$p_processo         = upper($_REQUEST['p_processo']);
$p_empenho          = upper($_REQUEST['p_empenho']);

$p_protocolo        = $_REQUEST['p_protocolo'];
$p_chave            = explodeArray($_REQUEST['p_chave']);
if (!is_array($p_chave)) $p_chave = $_REQUEST['p_chave'];
$p_chave_aux        = $_REQUEST['p_chave_aux'];
$p_prefixo          = $_REQUEST['p_prefixo'];
$p_numero           = $_REQUEST['p_numero'];
$p_ano              = $_REQUEST['p_ano'];
$p_unid_autua       = $_REQUEST['p_unid_autua'];
$p_unid_posse       = $_REQUEST['p_unid_posse'];
$p_nu_guia          = $_REQUEST['p_nu_guia'];
$p_ano_guia         = $_REQUEST['p_ano_guia'];
$p_ini              = $_REQUEST['p_ini'];
$p_fim              = $_REQUEST['p_fim'];
$p_classif          = $_REQUEST['p_classif'];
$p_caixa            = $_REQUEST['p_caixa'];
$p_unidade          = $_REQUEST['p_unidade'];
$p_tipo_despacho    = nvl($_REQUEST['w_tipo_despacho'], $_REQUEST['p_tipo_despacho']);
$p_detalhamento     = $_REQUEST['p_detalhamento'];
if (nvl($p_classif, '') != '') {
  $sql = new db_getAssunto_PA;
  $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_classif, null, null, null, null, null, null, null, null, 'REGISTROS');
  foreach ($RS_Assunto as $row) { $RS_Assunto = $row; break; }
  $p_sq_acao_ppa = f($row,'codigo');
}
    

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu;
$RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $SG);
if (count($RS) > 0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2 > 0) {
  $sql = new db_getMenuData;
  $RS_Menu = $sql->getInstanceOf($dbms, $P2);
} else {
  $sql = new db_getMenuData;
  $RS_Menu = $sql->getInstanceOf($dbms, $w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu, 'ultimo_nivel') == 'S') {
  $sql = new db_getMenuData;
  $RS_Menu = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu_pai'));
}

$sql = new db_getParametro;
$RS_Parametro = $sql->getInstanceOf($dbms, $w_cliente, 'PA', null);
foreach ($RS_Parametro as $row) {
  $RS_Parametro = $row;
  break;
}

$sql = new db_getUnidade_PA;
$RS_PAUnidade = $sql->getInstanceOf($dbms, $w_cliente, $_SESSION['LOTACAO'], null, null);
foreach ($RS_PAUnidade as $row) {
  $RS_PAUnidade = $row;
  break;
}

// Verifica se usuário logado é da unidade central de protocolo ou gestor do módulo
if (nvl(f($RS_PAUnidade, 'sq_unidade_pai'), '') == '' || RetornaModMaster($w_cliente, $w_usuario, $w_menu) == 'S') {
  $w_gestor = true;
} else {
  $w_gestor = false;
}

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $p_uf;
  $w_tipo = $_REQUEST['w_tipo'];
  if ($O == 'L') {
    if (!(strpos(upper($R), 'GR_') === false)) {
      $w_filtro = '';
      if ($p_uf > '' || $p_tipo > '') {
        if ($p_tipo > '')
          $p_uf = (($p_tipo == 'P') ? 'S' : 'N');
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Busca por <td>[<b>' . (($p_uf == 'S') ? 'Processos' : 'Documentos') . '</b>]';
      }
      if ($p_numero_doc > '') {
        $w_filtro .= '<tr valign="top"><td align="right">Nº do documento <td>[<b>' . $p_numero_doc . '</b>]';
      }
      if ($p_processo > '') {
        $w_filtro .= '<tr valign="top"><td align="right">Interessado <td>[<b>' . $p_processo . '</b>]';
      }
      if ($p_solicitante > '') {
        $sql = new db_getEspecieDocumento_PA;
        $RS = $sql->getInstanceOf($dbms, $p_solicitante, $w_cliente, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Espécie documental <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_prioridade > '') {
        $sql = new db_getTipoDespacho_PA;
        $RS = $sql->getInstanceOf($dbms, $p_prioridade, $w_cliente, null, null, null, null);
        foreach ($RS as $row) {
          $RS = $row;
          break;
        }
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Último despacho<td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      $sql = new db_getCaixa;
      $RS = $sql->getInstanceOf($dbms, $p_chave, $w_cliente, $w_usuario,null, null, null, null, null, null, null, null, null,null,null,null,null);
      foreach ($RS as $row) {
        $w_linha++;
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_chave);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Caixa<td>[<b>'.f($row, 'numero').'/'.f($row, 'sg_unidade').'</b>]';
        break;
      }
      if ($p_atividade>''){
        $w_linha++;
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_atividade);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade arquivadora<td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uorg_resp > '') {
        $sql = new db_getUorgData;
        $RS = $sql->getInstanceOf($dbms, $p_uorg_resp);
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Unidade de posse<td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_pais > '' || $p_regiao > '' || $p_cidade > '') {
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Protocolo <td>[<b>' . (($p_pais > '') ? $p_pais : '*') . '.' . (($p_regiao > '') ? str_pad($p_regiao, 6, '0', STR_PAD_RIGHT) : '*') . '/' . (($p_cidade > '') ? $p_cidade : '*') . '</b>]';
      }
      if ($p_proponente > '') {
        if (is_numeric($p_proponente)) {
          $sql = new db_getPersonData;
          $RS = $sql->getInstanceOf($dbms, $w_cliente, $p_proponente, null, null);
          $w_filtro .= '<tr valign="top"><td align="right">Procedência externa <td>[<b>' . f($RS, 'nome_resumido') . '</b>]';
        } else {
          $w_filtro .= '<tr valign="top"><td align="right">Procedência externa <td>[<b>' . $p_proponente . '</b>]';
        }
      }
      if ($p_palavra > '') {
        $w_filtro .= '<tr valign="top"><td align="right">Interessado <td>[<b>' . $p_palavra . '</b>]';
      }
      if ($p_unidade > '') {
        $sql = new db_getUorgData;
        $RS = $sql->getInstanceOf($dbms, $p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Origem interna <td>[<b>' . f($RS, 'nome') . '</b>]';
      }
      if ($p_sq_acao_ppa > '') {
        $w_linha++;
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Código do assunto <td>[<b>' . $p_sq_acao_ppa . '</b>]';
      }
      if ($p_assunto > '') {
        $w_linha++;
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Detalhamento do assunto <td>[<b>' . $p_assunto . '</b>]';
      }
      if ($p_ini_i > '')
        $w_filtro .= '<tr valign="top"><td align="right">Data criação/recebimento entre <td>[<b>' . $p_ini_i . '-' . $p_ini_f . '</b>]';
      if ($p_fim_i > '')
        $w_filtro .= '<tr valign="top"><td align="right">Limite da tramitação entre <td>[<b>' . $p_fim_i . '-' . $p_fim_f . '</b>]';
      if ($p_atraso == 'S') {
        $w_linha++;
        $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasados</b>]';
      }
      if ($w_filtro > '')
        $w_filtro = '<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr></table>';
    }
    $sql = new db_getLinkData;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
    if ($w_copia > '') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $sql = new db_getSolicList;
      $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, Nvl($_REQUEST['p_agrega'], $SG), 3,
                      $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                      (($P1==1) ? '' : $p_unidade), $p_prioridade, $p_ativo, $p_proponente,
                      $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                      $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
                      $p_sq_acao_ppa, null, $p_empenho, $p_processo);
    } else {
      $sql = new db_getSolicList;
      $RS = $sql->getInstanceOf($dbms, f($RS, 'sq_menu'), $w_usuario, Nvl($_REQUEST['p_agrega'], $SG), $P1,
                      $p_ini_i, $p_ini_f, $p_fim_i, $p_fim_f, $p_atraso, $p_solicitante,
                      (($P1==1) ? '' : $p_unidade), $p_prioridade, $p_ativo, $p_proponente,
                      $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
                      $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade,
                      $p_sq_acao_ppa, null, $p_empenho, $p_processo);
    }
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'protocolo_completo', 'asc');
    } else {
      $RS = SortArray($RS, 'protocolo_completo', 'asc');
    }
  }
  if ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 45 : 30);
    CabecalhoWord($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  }elseif ($w_tipo == 'PDF') {
    $w_linha_pag = ((nvl($_REQUEST['orientacao'], 'PORTRAIT') == 'PORTRAIT') ? 60 : 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de ' . f($RS_Menu, 'nome'), $w_pag);
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    cabecalho();
    head();
    if ($P1 == 2)
      ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('<TITLE>' . $conSgSistema . ' - Listagem de processos e documentos</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataProtocolo();
    ValidateOpen('Validacao');
    if (strpos('CP', $O)!==false) {
      if ($P1 != 1 || $O == 'C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_numero_doc', 'Número de protocolo', '1', '', '20', '20', '', '0123456789./-');
        Validate('p_ini_i', 'Recebimento inicial', 'DATA', '', '10', '10', '', '0123456789/');
        Validate('p_ini_f', 'Recebimento final', 'DATA', '', '10', '10', '', '0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de início ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i', 'Recebimento inicial', '<=', 'p_ini_f', 'Recebimento final');
        Validate('p_fim_i', 'Conclusão inicial', 'DATA', '', '10', '10', '', '0123456789/');
        Validate('p_fim_f', 'Conclusão final', 'DATA', '', '10', '10', '', '0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i', 'Conclusão inicial', '<=', 'p_fim_f', 'Conclusão final');
      }
      Validate('P4', 'Linhas por página', '1', '1', '1', '4', '', '0123456789');
    }
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
    if ($w_troca > '') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
    } elseif ($O == 'I' || $O == 'A') {
      BodyOpen('onLoad=\'document.Form.w_tipo.focus()\';');
    } elseif ($O == 'E') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen(null);
    }
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    if ($w_embed != 'WORD') {
      if ((strpos(upper($R), 'GR_')) === false) {
        Estrutura_Texto_Abre();
      } else {
        CabecalhoRelatorio($w_cliente, 'Consulta de ' . f($RS_Menu, 'nome'), 4);
      }
    }
    if ($w_filtro > '')
      ShowHTML($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr>');
    if ($w_embed != 'WORD') {
      ShowHTML('<td><font size="2">');
      if ($P1 == 1 && $w_copia == '') {
        // Se for cadastramento e não for resultado de busca para cópia
        if ($w_submenu > '') {
          $sql = new db_getLinkSubMenu;
          $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
          foreach ($RS1 as $row) {
            $RS1 = $row;
            break;
          }
          ShowHTML('<tr><td>');
          ShowHTML('    <a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . 'Geral&R=' . $w_pagina . $par . '&O=I&SG=' . f($RS1, 'sigla') . '&w_menu=' . $w_menu . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
          //ShowHTML '    <a accesskey=''C'' class=''SS'' href=''' & w_dir & w_pagina & par & '&R=' & w_pagina & par & '&O=C&P1=' & P1 & '&P2=' & P2 & '&P3=1&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & MontaFiltro('GET') & '''><u>C</u>opiar</a>'
        } else {
          ShowHTML('<tr><td><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>I</u>ncluir</a>&nbsp;');
        }
      }
      if ((strpos(upper($R), 'GR_') === false)) {
        if ($w_copia > '') {
          // Se for cópia
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_'))
            ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else
            ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=C&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
        } else {
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_'))
            ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          else
            ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
        }
      }
    }
    ShowHTML('    <td align="right">');

    ShowHTML('    '.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . (($w_embed == 'WORD') ? 1 : $conTableBorder) . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if ($w_embed != 'WORD') {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . LinkOrdena('Protocolo', 'protocolo_completo') . '</td>');
      ShowHTML('          <td rowspan=2 width="50"><b>' . LinkOrdena('Tipo', 'nm_tipo_protocolo') . '</td>');
      ShowHTML('          <td rowspan=2 nowrap><b>' . LinkOrdena('Posse', 'sg_unidade_posse') . '</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>' . LinkOrdena('Assunto', 'cd_assunto') . '</td>');
      ShowHTML('          <td rowspan=2><b>' . LinkOrdena('Resumo', 'ds_assunto') . '</td>');
      if ($P1 == 1)
        ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('          <td><b>' . LinkOrdena('Espécie', 'nm_especie') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Nº', 'numero_original') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Data', 'inicio') . '</td>');
      ShowHTML('          <td><b>' . LinkOrdena('Procedência', 'nm_origem') . '</td>');
    } else {
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Tipo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Posse</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>Assunto</td>');
      ShowHTML('          <td rowspan=2><b>Resumo</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('          <td><b>Espécie</td>');
      ShowHTML('          <td><b>Nº</td>');
      ShowHTML('          <td><b>Data</td>');
      ShowHTML('          <td><b>Procedência</td>');
    }
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=12 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = 0;
      if ($w_embed != 'WORD')
        $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4); else
        $RS1 = $RS;
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td width="1%" nowrap>');
        if (f($row, 'sg_tramite') == 'CI') {
          if (Nvl(f($row, 'fim'), time()) < addDays(time(), -1)) {
            ShowHTML('           <img src="' . $conImgAtraso . '" title="Em cadastramento" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="' . $conImgNormal . '" title="Em cadastramento" border=0 width=10 height=10 align="center">');
          }
        } elseif (f($row, 'sg_tramite') == 'CA') {
          ShowHTML('           <img src="' . $conImgCancel . '" title="Cancelado" border=0 width=10 height=10 align="center">');
        } elseif (f($row, 'sg_tramite') == 'EL') {
          ShowHTML('           <img src="' . $conImgCancel . '" title="Eliminado" border=0 width=10 height=10 align="center">');
        } elseif (f($row, 'sg_tramite') == 'AS') {
          ShowHTML('           <img src="' . $conImgOkAcima . '" title="Arquivado Setorial" border=0 width=10 height=10 align="center">');
        } elseif (f($row, 'sg_tramite') == 'DE') {
          ShowHTML('           <img src="' . $conImgOkNormal . '" title="Enviado para destino externo" border=0 width=10 height=10 align="center">');
        } elseif (f($row, 'sg_tramite') == 'AT') {
          ShowHTML('           <img src="' . $conImgOkNormal . '" title="Arquivado Central" border=0 width=10 height=10 align="center">');
        } else {
          if (Nvl(f($row, 'fim'), time()) < addDays(time(), -1)) {
            ShowHTML('           <img src="' . $conImgStAtraso . '" title="Tramitando. Data limite excedida." border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="' . $conImgStNormal . '" title="Tramitando" border=0 width=10 height=10 align="center">');
          }
        }

        if ($w_embed != 'WORD')
          ShowHTML('        <A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        else
          ShowHTML('        ' . f($row, 'protocolo') . '');
        ShowHTML('        </td>');
        ShowHTML('        <td width="10">&nbsp;' . f($row, 'nm_tipo_protocolo') . '</td>');
        if ($w_embed != 'WORD')
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . ExibeUnidade('../', $w_cliente, f($row, 'sg_unidade_posse'), f($row, 'unidade_int_posse'), $TP) . '</td>');
        else
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'sg_unidade_posse') . '');

        ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;' . FormataDataEdicao(f($row, 'inicio'), 5) . '&nbsp;</td>');
        if (f($row, 'interno') == 'S') {
          if ($w_embed != 'WORD')
            ShowHTML('        <td width="1%" nowrap>&nbsp;' . ExibeUnidade('../', $w_cliente, f($row, 'nm_origem_resumido'), f($row, 'sq_origem'), $TP) . '</td>');
          else
            ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'nm_origem_resumido') . '');
        } else {
          if ($w_embed != 'WORD')
            ShowHTML('        <td>' . ExibePessoa(null, $w_cliente, f($row, 'sq_origem'), $TP, f($row, 'nm_origem_resumido')) . '</td>');
          else
            ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'nm_origem_resumido') . '');
        }
        if ($w_embed != 'WORD')
          ShowHTML('        <td width="50" title="' . f($row, 'ds_assunto') . '">&nbsp;' . ExibeAssunto('../', $w_cliente, f($row, 'cd_assunto'), f($row, 'sq_assunto'), $TP) . '</td>');
        else
          ShowHTML('        <td width="50" title="' . f($row, 'ds_assunto') . '">&nbsp;' . f($row, 'cd_assunto') . '</td>');
        if ($_REQUEST['p_tamanho'] == 'N') {
          ShowHTML('        <td>' . Nvl(f($row, 'descricao'), '-') . '</td>');
        } else {
          if (strlen(Nvl(f($row, 'descricao'), '-')) > 50)
            $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 50) . '...'; else
            $w_titulo=Nvl(f($row, 'descricao'), '-');
          if (f($row, 'sg_tramite') == 'CA')
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'descricao')) . '"><strike>' . $w_titulo . '</strike></td>');
          else
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'descricao')) . '">' . $w_titulo . '</td>');
        }
        if ($w_embed != 'WORD' && $P1 == 1) {
          ShowHTML('        <td class="remover" align="top" nowrap>');
          // Se não for acompanhamento
          if ($w_copia > '') {
            // Se for listagem para cópia
            $sql = new db_getLinkSubMenu;
            $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['SG']);
          } elseif ($P1 == 1) {
            // Se for cadastramento, não permite alteração de cópias
            if (nvl(f($row,'copias'),0)==0) {
              ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&R=' . $w_pagina . $par . '&SG=' . $SG . '&TP=' . $TP . '&w_documento=' . f($row, 'protocolo') . MontaFiltro('GET') . '" title="Altera as informações cadastrais do documento" TARGET="menu">AL</a>&nbsp;');
            } else {
              ShowHTML('          <A class="HL" HREF="" onClick="alert(\'Não é possível alterar cópia. Altere o protocolo original!\'); return false;" title="Não é possível alterar cópia. Altere o protocolo original.">AL</a>&nbsp;');
            }
            if (nvl(f($row,'qtd_vinculado'),0)==0) {
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'Excluir&R=' . $w_pagina . $par . '&O=E&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exclusão do documento.">EX</A>&nbsp');
            } else {
              ShowHTML('          <A class="HL" HREF="" onClick="alert(\'Não é possível excluir protocolo com cópias. Exclua primeiro as cópias!\'); return false;" title="Exclua primeiro as cópias deste protocolo.">EX</a>&nbsp;');
            }
            if (nvl(f($row,'copias'),0)>0) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&O=A&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_menu=' . f($row, 'sq_menu') . '&R=' . $w_pagina . $par . '&SG=PADENVIO&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&w_documento=' . f($row, 'protocolo') . MontaFiltro('GET') . '" title="Trâmite original da cópia">Tramitar cópia</a>&nbsp;');
            }
          }
          ShowHTML('        </td>');
        }
        ShowHTML('      </tr>');
      }
    }
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
    }
    ShowHTML('</tr>');
  } elseif (strpos('CP', $O)!==false) {
    if ($P1 != 1) {
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } elseif ($O == 'C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify"><font size=2>Para selecionar a ação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    }
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O == 'C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    }
    if ($P1 != 1 || $O == 'C') {
      // Se não for cadastramento ou se for cópia
      // Recupera dados da opçãa açãos
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b><u>P</u>rotocolo:</b><br><input ' . $w_Disabled . ' accesskey="P" type="text" name="p_numero_doc" class="sti" SIZE="20" MAXLENGTH="20" VALUE="' . $p_numero_doc . '" onKeyDown="FormataProtocolo(this,event);"></td>');
      ShowHTML('          <td valign="top"><b>Nº original do documento:<br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="p_empenho" size="40" maxlength="90" value="' . $p_empenho . '"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoAssuntoRadio('Assun<u>t</u>o:', 'T', 'Clique na lupa para selecionar o assunto do documento.', $p_assunto, null, 'p_assunto', 'FOLHA', null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('<u>P</u>essoa de origem:', 'P', 'Selecione a pessoa de origem.', $p_solicitante, null, 'p_solicitante', 'USUARIOS');
      SelecaoUnidade('<U>U</U>nidade de origem:', 'U', null, $p_unidade, null, 'p_unidade', null, null);
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de criação/recebimento entre:</b><br><input ' . $w_Disabled . ' type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><b>Limite da tramitação entre:</b><br><input ' . $w_Disabled . ' type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input ' . $w_Disabled . ' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O != 'C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente protocolos em atraso?</b><br>');
        if ($p_atraso == 'S')
          ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        else
          ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:', 'S', null, $p_fase, $P2, 'p_fase', null, null);
      }
    }
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" ' . $w_Disabled . ' class="STS" name="p_ordena" size="1">');
    if ($p_ordena == 'ASSUNTO')
      ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena == 'INICIO')
      ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena == 'NM_TRAMITE'
      )ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite" SELECTED>Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena == 'PRIORIDADE'
      )ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente">Proponente externo');
    elseif ($p_ordena == 'PROPONENTE'
      )ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="">Data de término<option value="nm_tramite">Fase atual<option value="proponente" SELECTED>Proponente externo');
    else
      ShowHTML('          <option value="assunto">Assunto<option value="inicio">Data de início<option value="" SELECTED>Data de término<option value="nm_tramite">Fase atual<option value="prioridade">Proponente externo');
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" ' . $w_Disabled . ' class="STI" type="text" name="P4" size="4" maxlength="4" value="' . $P4 . '"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  $w_chave    = $_REQUEST['w_chave'];
  $w_processo = nvl($_REQUEST['w_processo'], 'N');
  $w_circular = nvl($_REQUEST['w_circular'], 'N');
  $w_readonly = '';
  $w_erro = '';

  // Verifica se a unidade de lotação do usuário está cadastrada na relação de unidades do módulo
  $sql = new db_getUorgList;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, $_SESSION['LOTACAO'], 'PAUNID', null, null, $w_ano);
  if (count($RS) == 0) {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("ATENÇÃO: Sua lotação não está cadastrada na tabelas de unidades do módulo de protocolo e arquivo.\nEntre em contato com os gestores do sistema!");');
    ShowHTML('  history.back(1);');
    ScriptClose();
    exit;
  }

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca > '' && $O != 'E') {
    // Se for recarga da página
    $w_doc_original = $_REQUEST['w_doc_original'];
    $w_especie_documento = $_REQUEST['w_especie_documento'];
    $w_natureza_documento = $_REQUEST['w_natureza_documento'];
    $w_data_documento = $_REQUEST['w_data_documento'];
    $w_fim = $_REQUEST['w_fim'];
    $w_interno = $_REQUEST['w_interno'];
    $w_tipo_pessoa = $_REQUEST['w_tipo_pessoa'];
    $w_pais = $_REQUEST['w_pais'];
    $w_uf = $_REQUEST['w_uf'];
    $w_cidade = $_REQUEST['w_cidade'];
    $w_data_recebimento = $_REQUEST['w_data_recebimento'];
    $w_nm_assunto = $_REQUEST['w_nm_assunto'];
    $w_pessoa_interes = $_REQUEST['w_pessoa_interes'];
    $w_assunto = $_REQUEST['w_assunto'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_copias = $_REQUEST['w_copias'];
    $w_volumes = $_REQUEST['w_volumes'];
    $w_un_autuacao = $_REQUEST['w_un_autuacao'];
    $w_dt_autuacao = $_REQUEST['w_dt_autuacao'];
    $w_nm_pessoa_origem = $_REQUEST['w_nm_pessoa_origem'];
    $w_pessoa_origem = $_REQUEST['w_pessoa_origem'];
    $w_sq_unidade = $_REQUEST['w_sq_unidade'];
  } else {
    if (!(strpos('AEV', $O) === false) || $w_copia > '') {
      // Recupera os dados da ação
      if ($w_copia > '') {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $w_copia, $SG);
      } else {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $w_chave, $SG);
      }
      if (count($RS) > 0) {
        $w_processo = f($RS, 'processo');
        $w_doc_original = f($RS, 'numero_original');
        $w_especie_documento = f($RS, 'sq_especie_documento');
        $w_natureza_documento = f($RS, 'sq_natureza_documento');
        $w_data_documento = formataDataEdicao(f($RS, 'inicio'));
        $w_fim = formataDataEdicao(f($RS, 'fim'));
        $w_interno = f($RS, 'interno');
        $w_tipo_pessoa = f($RS, 'sq_tipo_pessoa');
        $w_pais = f($RS, 'sq_pais');
        $w_uf = f($RS, 'co_uf');
        $w_cidade = f($RS, 'sq_cidade_origem');
        $w_data_recebimento = formataDataEdicao(f($RS, 'data_recebimento'));
        $w_pessoa_interes = f($RS, 'pessoa_interes');
        $w_assunto = f($RS, 'sq_assunto');
        $w_descricao = f($RS, 'descricao');
        $w_copias = f($RS, 'copias');
        $w_volumes = f($RS, 'volumes');
        $w_un_autuacao = f($RS, 'unidade_autuacao');
        $w_dt_autuacao = formataDataEdicao(f($RS, 'data_autuacao'));
        $w_nm_pessoa_origem = f($RS, 'nm_pessoa_origem');
        $w_pessoa_origem = f($RS, 'pessoa_origem');
        $w_sq_unidade = f($RS, 'sq_unidade');
      }
    }
  }

  // Configura variáveis de controle para processos e circulares
  if (nvl($w_especie_documento, '') != '') {
    $sql = new db_getEspecieDocumento_PA;
    $RS = $sql->getInstanceOf($dbms, $w_especie_documento, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    if (f($RS, 'sigla') == 'PROC') {
      $w_processo = 'S';
      $w_circular = 'N';
    } elseif (strpos(upper(f($RS, 'nome')), 'CIRCULAR') !== false) {
      $w_processo = 'N';
      $w_circular = 'S';
    } else {
      $w_processo = 'N';
      $w_circular = 'N';
    }
    // Carrega assunto padrão do documento
    if ($O == 'I' || (nvl($w_assunto, '') == '' && nvl(f($RS, 'sq_assunto'), '') != ''))
      $w_assunto = f($RS, 'sq_assunto');
  }

  if (nvl($w_assunto, '') == '') {
    // Só pode haver um registro para classificação provisória
    $sql = new db_getAssunto_PA;
    $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, null, null, null, null, null, null, null, null, null, 'PROVISORIO');
    foreach ($RS_Assunto as $row) {
      $RS_Assunto = $row;
      break;
    }
    if (count($RS_Assunto) > 0)
      $w_assunto = f($RS_Assunto, 'sq_assunto');
  }

  // Somente gestores do módulo e lotados na unidade de protocolo podem cadastrar documentos externos.
  if ($w_gestor == false or nvl($w_interno,'')=='') $w_interno = 'S';

  Cabecalho();
  head();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ShowHTML('function telaDocumento(tipo) {');
  ShowHTML('  document.Form.w_troca.value=\'w_especie_documento\';');
  ShowHTML('  document.Form.action=\'' . $w_dir . $w_pagina . $par . '\';');
  ShowHTML('  document.Form.O.value=\'' . $O . '\';');
  ShowHTML('  document.Form.submit();');
  ShowHTML('}');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O == 'I' || $O == 'A') {
    Validate('w_especie_documento', 'Espécie documental', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_doc_original', 'Nº do documento', '1', '1', 1, 30, '1', '1');
    Validate('w_data_documento', 'Data do documento', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_data_documento', 'Data do documento', '<=', FormataDataEdicao(time()), 'data atual');
    if ($w_gestor)
      Validate('w_interno', 'Procedência', 'SELECT', 1, 1, 1, 'SN', '');
    if ($w_interno == 'N') {
      Validate('w_pais', 'País', 'SELECT', 1, 1, 18, '', '0123456789');
      Validate('w_uf', 'Estado', 'SELECT', 1, 1, 3, '1', '1');
      Validate('w_cidade', 'Cidade', 'SELECT', 1, 1, 18, '', '0123456789');
      Validate('w_pessoa_origem', 'Pessoa de origem', 'HIDDEN', 1, 1, 18, '', '0123456789');
      Validate('w_pessoa_interes', 'Interessado principal', 'HIDDEN', 1, 1, 18, '', '0123456789');
    } else {
      Validate('w_sq_unidade', 'Unidade de origem', 'SELECT', 1, 1, 18, '', '0123456789');
    }
    Validate('w_data_recebimento', 'Data de criação/recebimento', 'DATA', '1', 10, 10, '', '0123456789/');
    CompData('w_data_recebimento', 'Data de criação/recebimento', '>=', 'w_data_documento', 'Data do documento');
    CompData('w_data_recebimento', 'Data de criação/recebimento', '<=', FormataDataEdicao(time()), 'data atual');
    if ($w_processo == 'S') {
      Validate('w_dt_autuacao', 'Data de autuação', 'DATA', '1', 10, 10, '', '0123456789/');
      Validate('w_volumes', 'Nº de volumes', '1', '1', 1, 18, '', '0123456789');
      CompValor('w_volumes', 'Nº de volumes', '>', 0, 'zero');
//    } elseif ($w_circular == 'S') {
//      Validate('w_copias', 'Nº de cópias', '1', '1', 1, 18, '', '0123456789');
//      CompValor('w_copias', 'Nº de cópias', '>', 2, 'dois');
    }
    Validate('w_fim', 'Data limite para conclusão', 'DATA', '', 10, 10, '', '0123456789/');
    CompData('w_fim', 'Data limite para conclusão', '>=', 'w_data_documento', 'Data do documento');
    Validate('w_assunto', 'Classificação', 'HIDDEN', 1, 1, 18, '', '0123456789');
    Validate('w_descricao', 'Detalhamento do assunto', '1', '1', 1, 2000, '1', '1');
    if ($O=='I' || nvl($w_copia,0)>0) Validate('w_copias', 'Nº de cópias', '1', '1', 1, 18, '', '0123456789');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif (!(strpos('EV', $O) === false)) {
    BodyOpen('onLoad=\'this.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_especie_documento.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV', $O) === false)) {
    if ($w_pais == '') {
      // Carrega os valores padrão para país, estado e cidade
      $sql = new db_getCustomerData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente);
      $w_pais = f($RS, 'sq_pais');
      $w_uf = f($RS, 'co_uf');
      $w_cidade = f($RS, 'sq_cidade_padrao');
    }
    if ($O == 'E')
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="' . $w_copia . '">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="' . f($RS_Menu, 'data_hora') . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . f($RS_Menu, 'sq_menu') . '">');
    ShowHTML('<INPUT type="hidden" name="w_processo" value="' . $w_processo . '">');
    ShowHTML(MontaFiltro('POST'));
    if ($w_interno == 'S' and $w_processo == 'S')
      ShowHTML('<INPUT type="hidden" name="w_un_autuacao" value="' . f($RS_Menu, 'sq_unid_executora') . '">');
    ShowHTML('<INPUT type="hidden" name="w_circular" value="' . $w_circular . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>IDENTIFICAÇÃO</b></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $w_especie_documento, null, 'w_especie_documento', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_doc_original\'; document.Form.submit();"');
    ShowHTML('           <td title="Informe o número do documento de origem."><b>Número:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_doc_original" size="20" maxlength="30" value="' . $w_doc_original . '" ></td>');
    ShowHTML('           <td title="Informe a data do documento de origem."><b>D<u>a</u>ta:</b><br><input ' . $w_Disabled . ' accesskey="A" type="text" name="w_data_documento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_data_documento . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data original do documento.">' . ExibeCalendario('Form', 'w_data_documento') . '</td>');
    if ($w_gestor) {
      selecaoOrigem('<u>O</u>rigem:', 'O', 'Indique se a origem é interna ou externa.', $w_interno, null, 'w_interno', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    }
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=4><b>PROCEDÊNCIA</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    if ($w_interno == 'N') {
      ShowHTML('       <tr valign="top">');
      SelecaoPais('<u>P</u>aís:', 'P', "Selecione o país de procedência.", $w_pais, null, 'w_pais', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
      SelecaoEstado('E<u>s</u>tado:', 'S', "Selecione o estado de procedência.", $w_uf, $w_pais, null, 'w_uf', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
      ShowHTML('          <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoCidade('<u>C</u>idade:', 'C', "Selecione a cidade de procedência.", $w_cidade, $w_pais, $w_uf, 'w_cidade', null, null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>P</u>essoa de origem:', 'P', 'Clique na lupa para selecionar a pessoa de origem.', $w_pessoa_origem, null, 'w_pessoa_origem', null, null, null);
      ShowHTML('           </table>');
      ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
      SelecaoPessoaOrigem('<u>I</u>nteressado principal:', 'I', 'Clique na lupa para selecionar o interessado principal.', $w_pessoa_interes, null, 'w_pessoa_interes', null, null, null);
      ShowHTML('           </table>');
    } else {
      ShowHTML('      <tr>');
      SelecaoUnidade('<U>U</U>nidade de origem:', 'U', 'Selecione a unidade de origem.', nvl($w_sq_unidade, $_SESSION['LOTACAO']), $w_usuario, 'w_sq_unidade', 'CADPA', null,5);
    }
    ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=5><b>DADOS COMPLEMENTARES</b></td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('           <td valign="top" title="Informe a data de criação ou de recebimento."><b><u>D</u>ata de criação/recebimento:</b><br><input ' . $w_Disabled . ' accesskey="D" type="text" name="w_data_recebimento" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . nvl($w_data_recebimento, $w_data_documento) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_data_recebimento') . '</td>');
    if ($w_processo == 'S') {
      ShowHTML('           <td title="Data de autuação do processo."><b>Data de autuação:</b><br><input ' . $w_Disabled . ' type="text" name="w_dt_autuacao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_dt_autuacao . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_fim') . '</td>');
      ShowHTML('           <td title="Informe quantos volumes compõem o processo."><b>Nº de volumes:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_volumes" size="3" maxlength="3" value="' . $w_volumes . '" ></td>');
    } elseif ($w_circular == 'S') {
      //ShowHTML('           <td title="Informe o número de cópias da circular."><b>Nº de cópias:</b><br><INPUT ' . $w_Disabled . ' class="STI" type="text" name="w_copias" size="5" maxlength="18" value="' . $w_copias . '" ></td>');
    }
    selecaoNaturezaDocumento('<u>N</u>atureza:', 'N', 'Indique a natureza do documento.', $w_natureza_documento, null, 'w_natureza_documento', null, null);
    ShowHTML('           <td title="OPCIONAL. Limite para término da tramitação do documento."><b>Data limite:</b><br><input ' . $w_Disabled . ' type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_fim') . '</td>');
    ShowHTML('      <tr><td colspan=5><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $w_assunto, null, 'w_assunto', 'FOLHA', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.O.value=\'' . $O . '\'; document.Form.w_troca.value=\'w_descricao\'; document.Form.submit();"');
    ShowHTML('           </table>');
    if (nvl($w_assunto, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_assunto, null, null, null, null, null, null, null, null, 'REGISTROS');
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center">');
      ShowHTML('          <TABLE WIDTH="100%" BORDER="0">');
      ShowHTML('            <tr bgcolor="#DADADA">');
      ShowHTML('              <td><b>Código</td>');
      ShowHTML('              <td><b>Descrição</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observação</td>');
      ShowHTML('            </tr>');
      foreach ($RS as $row) {
        ShowHTML('            <tr valign="top" bgcolor="#DADADA">');
        ShowHTML('              <td width="1%" nowrap>' . f($row, 'codigo') . '</td>');
        ShowHTML('              <td>');
        ShowHTML('                ' . f($row, 'descricao'));
        if (nvl(f($row, 'ds_assunto_pai'), '') != '') {
          echo '<br>';
          if (nvl(f($row, 'ds_assunto_bis'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_bis')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_avo'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_avo')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_pai'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_pai')));
        }
        ShowHTML('              <td>' . nvl(f($row, 'detalhamento'), '---') . '</td>');
        ShowHTML('              <td>' . nvl(f($row, 'observacao'), '---') . '</td>');
      }
      ShowHTML('            </table></tr>');
    }
    ShowHTML('      <tr><td colspan="5" title="Descreva de forma objetiva o conteúdo do documento."><b>D<u>e</u>talhamento:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_descricao" class="STI" ROWS=5 cols=75>' . $w_descricao . '</TEXTAREA></td>');
    if ($O=='I' || nvl($w_copia,0)>0) {
      ShowHTML('      <tr><td colspan=5>&nbsp;</td></tr>');
      ShowHTML('      <tr><td colspan=5 align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan=5><b>GERAÇÃO AUTOMÁTICA DE CÓPIAS</b> :<li>Se desejado, informe o número de cópias a serem geradas para este protocolo.<li>Cada cópia gerada terá tramitação independente e fará referência ao protocolo original.<li>As cópias geradas ficarão disponíveis para envio na tela de registro.</td></tr>');
      ShowHTML('      <tr><td colspan="5"><b>Gerar o protocolo e mais <INPUT ' . $w_Disabled . ' style="text-align:right;" class="STI" type="text" name="w_copias" size="5" maxlength="18" value="' . nvl($w_copias,0) . '"> cópias</td>');
    }
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O == 'I') {
      $sql = new db_getMenuData;
      $RS = $sql->getInstanceOf($dbms, $w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $R . '&w_copia=' . $w_copia . '&O=L&SG=' . f($RS, 'sigla') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '\';" name="Botao" value="Cancelar">');
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
  Rodape();
}

// =========================================================================
// Rotina de interessados
// -------------------------------------------------------------------------
function Interessados() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca > '') {
    // Se for recarga da página
    $w_principal = $_REQUEST['w_principal'];
    $w_nm_pessoa = $_REQUEST['w_nm_pessoa'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getDocumentoInter;
    $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'N', null);
    $RS = SortArray($RS, 'principal', 'desc', 'nome', 'asc');
  } elseif (!(strpos('AEV', $O) === false) && $w_troca == '') {
    // Recupera os dados do registro informado
    $sql = new db_getDocumentoInter;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, 'N', null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_nm_pessoa = f($RS, 'nome_resumido');
    $w_principal = f($RS, 'principal');
  }

  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA', $O) !== false) {
      Validate('w_chave_aux', 'Interessado', 'HIDDEN', '1', '1', '18', '', '1');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen(null);
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Insira cada um dos interessados complementares, lembrando que o interessado principal já foi cadastrado na tela de identificação.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>CPF/CNPJ</td>');
    ShowHTML('          <td><b>RG/Inscrição estadual</td>');
    ShowHTML('          <td><b>Passaporte</td>');
    ShowHTML('          <td><b>Sexo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td>' . f($row, 'nome') . '</td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'identificador_principal'), '---') . '</td>');
        ShowHTML('        <td>' . nvl(f($row, 'identificador_secundario'), '---') . '</td>');
        ShowHTML('        <td>' . nvl(f($row, 'nr_passaporte'), '---') . '</td>');
        ShowHTML('        <td align="center">' . nvl(f($row, 'nm_sexo'), '---') . '</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'GRAVA&R=' . $w_pagina . $par . '&O=E&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '" onClick="return confirm(\'Confirma desvinculação do pessoa ao documento?\');">Desvincular</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_principal" value="N">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoPessoaOrigem('<u>I</u>nteressado:', 'I', 'Clique na lupa para selecionar o interessado.', $w_chave_aux, null, 'w_chave_aux', null, null, null);
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I')
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Rotina de assuntos
// -------------------------------------------------------------------------
function Assuntos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  if ($w_troca > '') {
    // Se for recarga da página
    $w_chave_aux = $_REQUEST['w_chave_aux'];
    $w_principal = $_REQUEST['w_principal'];
    $w_nm_assunto = $_REQUEST['w_nm_assunto'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getDocumentoAssunto;
    $RS = $sql->getInstanceOf($dbms, $w_chave, null, 'N', null);
    $RS = SortArray($RS, 'descricao', 'asc');
  } elseif (!(strpos('AEV', $O) === false) && $w_troca == '') {
    // Recupera os dados do assunto informado
    $sql = new db_getDocumentoAssunto;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, 'N', null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_principal = f($RS, 'principal');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false))
      Validate('w_chave_aux', 'Assunto', '1', '1', '1', '100', '1', '1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen(null);
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se este documento tiver assuntos complementares, insira cada um deles.');
    ShowHTML('  <li>Para alterar o assunto principal do documento, use a tela de identificação.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=I&w_chave=' . $w_chave . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>Código</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Detalhamento</td>');
    ShowHTML('          <td><b>Observação</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td width="1%" nowrap>' . f($row, 'codigo') . '</td>');
        ShowHTML('        <td>');
        ShowHTML('                ' . f($row, 'descricao'));
        if (nvl(f($row, 'ds_assunto_pai'), '') != '') {
          echo '<br>';
          if (nvl(f($row, 'ds_assunto_bis'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_bis')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_avo'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_avo')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_pai'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_pai')));
        }
        ShowHTML('        <td>' . nvl(lower(f($row, 'detalhamento')), '---') . '</td>');
        ShowHTML('        <td>' . nvl(lower(f($row, 'observacao')), '---') . '</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . 'GRAVA&R=' . $w_pagina . $par . '&O=E&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '" onClick="return confirm(\'Confirma desvinculação do assunto ao documento?\');">Desvincular</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, $O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_principal" value="N">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    SelecaoAssuntoRadio('Assun<u>t</u>o:', 'T', null, $w_chave_aux, null, 'w_chave_aux', 'FOLHA', null);
    if (nvl($w_chave_aux, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave_aux, null, null, null, null, null, null, null, null, 'REGISTROS');
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td>');
      ShowHTML('          <TABLE WIDTH="100%" bgcolor="' . $conTrAlternateBgColor . '" BORDER="1" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('            <tr align="center">');
      ShowHTML('              <td><b>Código</td>');
      ShowHTML('              <td><b>Descrição</td>');
      ShowHTML('              <td><b>Detalhamento</td>');
      ShowHTML('              <td><b>Observação</td>');
      ShowHTML('            </tr>');
      foreach ($RS as $row) {
        ShowHTML('            <tr valign="top">');
        ShowHTML('              <td width="1%" nowrap>' . f($row, 'codigo') . '</td>');
        ShowHTML('              <td>');
        ShowHTML('                ' . f($row, 'descricao'));
        if (nvl(f($row, 'ds_assunto_pai'), '') != '') {
          echo '<br>';
          if (nvl(f($row, 'ds_assunto_bis'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_bis')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_avo'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_avo')) . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_pai'), '') != '')
            ShowHTML(lower(f($row, 'ds_assunto_pai')));
        }
        ShowHTML('              <td>' . nvl(f($row, 'detalhamento'), '---') . '</td>');
        ShowHTML('              <td>' . nvl(f($row, 'observacao'), '---') . '</td>');
      }
      ShowHTML('            </table></tr>');
    }
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O == 'E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O == 'I')
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 
function Anexos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '') {
    // Se for recarga da página 
    $w_nome       = $_REQUEST['w_nome'];
    $w_descricao  = $_REQUEST['w_descricao'];
    $w_caminho    = $_REQUEST['w_caminho'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getSolicAnexo;
    $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente);
    $RS = SortArray($RS, 'nome', 'asc');
  } elseif (!(strpos('AEV', $O) === false) && $w_troca == '') {
    // Recupera os dados do endereço informado 
    $sql = new db_getSolicAnexo;
    $RS = $sql->getInstanceOf($dbms, $w_chave, $w_chave_aux, $w_cliente);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $w_nome = f($RS, 'nome');
    $w_descricao = f($RS, 'descricao');
    $w_caminho = f($RS, 'chave_aux');
  }
  Cabecalho();
  head();
  if (!(strpos('IAEP', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA', $O) === false)) {
      Validate('w_nome', 'Título', '1', '1', '1', '255', '1', '1');
      Validate('w_descricao', 'Descrição', '1', '1', '1', '1000', '1', '1');
      if ($O == 'I')
        Validate('w_caminho', 'Arquivo', '', '1', '5', '255', '1', '1');
    }
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'I') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O == 'A') {
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  } else {
    BodyOpen(null);
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Se necessário, insira cada um dos arquivos vinculados ao documento.');
    ShowHTML('  <li>Os arquivos devem estar em seu computador e podem ser de qualquer formato.');
    ShowHTML('  </ul></b></font></td>');
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
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=A&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=E&w_chave=' . $w_chave . '&w_chave_aux=' . f($row, 'chave_aux') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . '">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
    }
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV', $O) === false)) {
    if (!(strpos('EV', $O) === false))
      $w_Disabled = ' DISABLED ';
    ShowHTML('<FORM action="' . $w_dir . $w_pagina . 'Grava&SG=' . $SG . '&O=' . $O . '&UploadID=' . $UploadID . '" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
      $sql = new db_getCustomerData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de ' . (f($RS, 'upload_maximo') / 1024) . ' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="' . f($RS, 'upload_maximo') . '">');
    }
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="' . $w_nome . '" title="Informe o tíulo do arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea ' . $w_Disabled . ' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="Descreva o conteúdo do arquivo.">' . $w_descricao . '</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input ' . $w_Disabled . ' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho > '')
      ShowHTML('              <b>' . LinkArquivo('SS', $w_cliente, $w_caminho, '_blank', 'Clique para exibir o arquivo atual.', 'Exibir', null) . '</b>');
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
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Rotina de visualização do novo layout de relatórios
// -------------------------------------------------------------------------
function Visual($w_chave=null, $w_o=null, $w_usuario=null, $w_p1=null, $w_tipo=null, $w_identificacao=null, $w_responsavel=null, $w_assunto_princ=null, $w_orcamentaria=null, $w_indicador=null, $w_recurso=null, $w_interessado=null, $w_anexo=null, $w_meta=null, $w_ocorrencia=null, $w_consulta=null) {
  extract($GLOBALS);
  $w_chave    = nvl($w_chave, $_REQUEST['w_chave']);
  $w_tipo     = nvl($w_tipo, upper(trim($_REQUEST['w_tipo'])));
  $w_formato  = nvl($w_formato, upper(trim($_REQUEST['w_formato'])));
  if ($O == 'T') {
    $w_identificacao = upper(nvl($w_identificacao, 'S'));
    $w_responsavel = upper(nvl($w_responsavel, 'S'));
    $w_assunto_princ = upper(nvl($w_qualitativa, 'S'));
    $w_orcamentaria = upper(nvl($w_orcamentaria, 'S'));
    $w_indicador = upper(nvl($w_indicador, 'S'));
    $w_recurso = upper(nvl($w_recurso, 'S'));
    $w_interessado = upper(nvl($w_interessado, 'S'));
    $w_anexo = upper(nvl($w_anexo, 'S'));
    $w_meta = upper(nvl($w_meta, 'S'));
    $w_ocorrencia = upper(nvl($w_ocorrencia, 'S'));
    $w_consulta = upper(nvl($w_consulta, 'N'));
  } else {
    $w_identificacao = upper(nvl($w_identificacao, 'S'));
    $w_responsavel = upper(nvl($w_responsavel, 'N'));
    $w_assunto_princ = upper(nvl($w_qualitativa, 'S'));
    $w_orcamentaria = upper(nvl($w_orcamentaria, 'N'));
    $w_indicador = upper(nvl($w_indicador, 'N'));
    $w_recurso = upper(nvl($w_recurso, 'N'));
    $w_interessado = upper(nvl($w_interessado, 'N'));
    $w_anexo = upper(nvl($w_anexo, 'N'));
    $w_meta = upper(nvl($w_meta, 'N'));
    $w_ocorrencia = upper(nvl($w_ocorrencia, 'S'));
    $w_consulta = upper(nvl($w_consulta, 'N'));
  }
  // Recupera o logo do cliente a ser usado nas listagens
  $sql = new db_getCustomerData;
  $RS = $sql->getInstanceOf($dbms, $w_cliente);
  if (f($RS, 'logo') > '') $w_logo = '/img/logo' . substr(f($RS, 'logo'), (strpos(f($RS, 'logo'), '.') ? strpos(f($RS, 'logo'), '.') + 1 : 0) - 1, 30);
  if ($w_tipo == 'PDF') {
    headerpdf(f($RS_Menu, 'nome'), $w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='EXCEL') {
    HeaderExcel($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualização de '.f($RS_Menu,'nome'),0,1,6);
    $w_embed = 'WORD';
  } elseif ($w_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente, f($RS_Menu, 'nome'), 0);
    $w_embed = 'WORD';
  } else {
    $sql = new db_getLinkData;
    $RS_Cab = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
    Cabecalho();
    head();
    ShowHTML('<TITLE>' . $conSgSistema . ' - ' . f($RS_Cab, 'nome') . '</TITLE>');
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</head>');
    BodyOpenClean('onLoad="this.focus()"; ');
    if ($w_embed != 'WORD') CabecalhoRelatorio($w_cliente, f($RS_Cab, 'nome'), 4, $w_chave);
    $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualDocumento($w_chave, $w_o, $w_usuario, $w_p1, $w_embed, $w_identificacao, $w_assunto_princ, $w_orcamentaria, $w_indicador, $w_recurso, $w_interessado, $w_anexo, $w_meta, $w_ocorrencia, $w_consulta));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if ($w_tipo=='PDF') RodapePDF();
  else                Rodape();
}

// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if (!(strpos('E', $O) === false)) {
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
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualDocumento($w_chave, 'V', $w_usuario, $w_p1, $w_formato, 'S', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N'));
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PADGERAL', $w_pagina . $par, $O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, f($RS_Menu, 'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . f($RS, 'sq_siw_tramite') . '">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS, 'unidade_int_posse') . '">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS, 'pessoa_ext_posse') . '">');
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
  $w_pede_unid  = false;

  if ($w_troca > '') {
    // Se for recarga da página
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_novo_tramite     = $_REQUEST['w_novo_tramite'];
    $w_tipo_despacho    = $_REQUEST['w_tipo_despacho'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }

  $sql = new db_getSolicData;
  $RS_Solic = $sql->getInstanceOf($dbms, $w_chave, f($RS_Menu, 'sigla'));
  if (nvl(f($RS_Solic,'copias'),'')=='') $w_copia = false; else $w_copia = true;

  // Recupera os dados da unidade central de protocolo
  $sql = new db_getUorgList;
  $RS_Prot = $sql->getInstanceOf($dbms, $w_cliente, null, 'MOD_PA_PROT', null, null, $w_ano);
  foreach ($RS_Prot as $row) { $RS_Prot = $row; break; }

  if ($w_tipo_despacho == f($RS_Parametro, 'despacho_autuar')) {
    $w_envia_protocolo = 'S';
  } else {
    $w_envia_protocolo = 'N';
  }

  if ($w_tipo_despacho == f($RS_Parametro, 'despacho_desmembrar')) $w_desmembrar = 'S'; else $w_desmembrar = 'N';

  // Verifica se pode ser feito envio externo. Se não puder, nem mostra opção ao usuário
  if ($w_envia_protocolo =='S' || $w_desmembrar=='S' || $w_gestor==false ||
      $w_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial') ||
      $w_tipo_despacho == f($RS_Parametro, 'despacho_anexar') ||
      $w_tipo_despacho == f($RS_Parametro, 'despacho_apensar') ||
      $w_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')
     )
     $w_somente_interno = true;
  else
     $w_somente_interno = false;

  // Configura o valor default do destino do envio
  $w_interno = nvl($w_interno,'S');

  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if (strpos('V', $O) !== false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataProtocolo();
    ValidateOpen('Validacao');
    Validate('w_tipo_despacho', 'Despacho', 'SELECT', 1, 1, 18, '', '0123456789');
    if (nvl($w_tipo_despacho,'')!='') {
      if ($w_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $w_tipo_despacho == f($RS_Parametro, 'despacho_anexar')) {
        Validate('w_protocolo_nm', 'ao processo', 'hidden', '1', '20', '20', '', '0123456789./-');
        ShowHTML('  if (theForm.w_protocolo_nm.value == theForm.w_numero.value) {');
        ShowHTML('    alert("Não é possível juntar um protocolo a ele mesmo!"); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
      }
      if ($w_envia_protocolo == 'N' && $w_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial') && $w_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
        Validate('w_retorno_limite', 'Prazo de resposta', 'DATA', '', 10, 10, '', '0123456789/');
        CompData('w_retorno_limite', 'Prazo de resposta', '>=', FormataDataEdicao(time()), 'data atual');
        Validate('w_dias', 'Dias para encaminhamento', '1', '', 1, 3, '', '0123456789');
        ShowHTML('  if (theForm.w_aviso[0].checked) {');
        ShowHTML('     if (theForm.w_dias.value == \'\') {');
        ShowHTML('        alert("Informe a partir de quantos dias após o envio você deseja ser avisado!");');
        ShowHTML('        theForm.w_dias.focus();');
        ShowHTML('        return false;');
        ShowHTML('     }');
        ShowHTML('  }');
        ShowHTML('  else {');
        ShowHTML('     theForm.w_dias.value = \'\';');
        ShowHTML('  }');
      }
      if ($w_envia_protocolo == 'N') {
        if (!$w_somente_interno) Validate('w_interno', 'Tipo da unidade/pessoa', 'SELECT', 1, 1, 1, 'SN', '');
        if ($w_interno == 'N') {
          Validate('w_pessoa_destino', 'Pessoa de destino', 'HIDDEN', 1, 1, 18, '', '0123456789');
          Validate('w_unidade_externa', 'Unidade externa', '', '', 2, 60, '1', '1');
        } else {
          if ($w_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial') && $w_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
            Validate('w_sq_unidade', 'Unidade de destino', 'SELECT', 1, 1, 18, '', '0123456789');
          }
        }
      }
      if ($w_desmembrar == 'S') {
        Validate('w_despacho', 'Protocolos a serem desmembrados', '', '1', '1', '2000', '1', '1');
      } elseif ($w_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
        Validate('w_despacho', 'Observações sobre o acondicionamento do protocolo', '', '1', '1', '70', '1', '1');
      } elseif ($w_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
        Validate('w_despacho', 'Detalhamento do despacho', '', '1', '1', '2000', '1', '1');
      }
      Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    }
    if (nvl($w_tipo_despacho,'')!='' && $w_copia) {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } elseif (nvl($w_tipo_despacho,'')!='' || $w_copia) {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    if (strpos($w_troca,'[')===false) {
      BodyOpen('onLoad="document.Form.' . $w_troca . '.focus();"');
    } else {
      BodyOpen('onLoad="document.Form[\'' . substr($w_troca,0,strpos($w_troca,'[')).'[]\']'.substr($w_troca,strpos($w_troca,'[')) . '.focus();"');
    }
  } else {
    BodyOpen('onLoad="document.Form.w_tipo_despacho.focus();"');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PADENVIO', $w_pagina . $par, $O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS_Solic, 'unidade_int_posse') . '">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS_Solic, 'pessoa_ext_posse') . '">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . f($RS_Solic, 'sq_siw_tramite') . '">');
  ShowHTML('<INPUT type="hidden" name="w_copia" value="' . (($w_copia) ? 'S' : 'N') . '">');
  ShowHTML('<table width="95%" border="0" cellspacing="3"><tr><td>');
  ShowHTML(VisualDocumento($w_chave, 'V', $w_usuario, $w_p1, $w_formato, 'S', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N'));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%"><tr valign="top">');
  ShowHTML('<INPUT type="hidden" name="w_arq_setorial" value="'.(($w_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) ? 'S' : 'N').'">');
  ShowHTML('<INPUT type="hidden" name="w_descartar" value="'.(($w_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) ? 'S' : 'N').'">');
  ShowHTML('      <tr><td colspan=3><b>Unidade remetente: ' . f($RS_Solic, 'nm_unid_origem') . '</b><hr size=1 noshade /></td>');
  ShowHTML('     <tr valign="top">');
  selecaoTipoDespacho('Des<u>p</u>acho:', 'P', 'Selecione o despacho desejado.', $w_cliente, $w_tipo_despacho, null, 'w_tipo_despacho', 'SELECAOCAD', 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_tipo_despacho\'; document.Form.submit();"');
  if ($w_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $w_tipo_despacho == f($RS_Parametro, 'despacho_anexar')) {
    SelecaoProtocolo('ao <U>p</U>rocesso:', 'U', 'Selecione o processo ao qual o protocolo será juntado.', $w_protocolo, $w_sq_unidade, 'w_protocolo', 'JUNTADA', null);
    ShowHTML('<INPUT type="hidden" name="w_numero" value="' . f($RS_Solic, 'protocolo_completo') . '">');
  }
  if (nvl($w_tipo_despacho,'')!='') {
    if ($w_envia_protocolo == 'N' && $w_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial') && $w_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TRÂMITE</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('           <td title="Informe a data limite para que o destinatário encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_retorno_limite . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_retorno_limite') . '</td>');
      MontaRadioNS('<b>Emite alerta de não encaminhamento?</b>', $w_aviso, 'w_aviso');
      ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias após esta tramitação?<br><INPUT ACCESSKEY="D" ' . $w_Disabled . ' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="' . $w_dias . '" title="A partir de quantos dias após este encaminhamento o sistema deve emitir o alerta."></td>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
      ShowHTML('<INPUT type="hidden" name="w_dias" value="0">');
    }
    if ($w_envia_protocolo == 'S') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO: ' . upper(f($RS_Prot, 'nome')) . '</b></font></td></tr>');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . f($RS_Prot, 'sq_unidade') . '">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    } elseif ($w_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DADOS DO ARQUIVAMENTO</b></font></td></tr>');
      ShowHTML('    <tr valign="top"><td>');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . f($RS_Solic,'unidade_int_posse') . '">');
      ShowHTML('    <tr><td width="30%">Usuário arquivador:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
      ShowHTML('    <tr><td>Unidade arquivadora:<td colspan=2><b>' . f($RS_Solic,'nm_unidade_posse') . '</b></td></tr>');
    } elseif ($w_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan="3" bgcolor="#f0f0f0" align=justify><font size="2"><b>DADOS DO DESCARTE</b></font></td></tr>');
      ShowHTML('    <tr valign="top"><td>');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . f($RS_Solic,'unidade_int_posse') . '">');
      ShowHTML('    <tr><td width="30%">Usuário responsável:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
      ShowHTML('    <tr><td>Unidade responsável:<td colspan=2><b>' . f($RS_Solic,'nm_unidade_posse') . '</b></td></tr>');
    } else {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      if ($w_somente_interno) {
        ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      } else {
        selecaoOrigem('<u>T</u>ipo da unidade/pessoa:', 'T', 'Indique se a unidade ou pessoa é interna ou externa.', $w_interno, null, 'w_interno', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
      }
      if ($w_interno == 'N') {
        SelecaoPessoaOrigem('<u>P</u>essoa de destino:', 'P', 'Clique na lupa para selecionar a pessoa de destino.', $w_pessoa_destino, null, 'w_pessoa_destino', null, null, null, 2);
        ShowHTML('    <tr><td><td colspan="2"><b>U<U>n</U>idade externa: (Informe apenas para pessoas jurídicas)<br><INPUT ACCESSKEY="N" ' . $w_Disabled . ' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="' . $w_unidade_externa . '"></td>');
      } else {
        if ($w_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $w_tipo_despacho == f($RS_Parametro, 'despacho_anexar') || $w_tipo_despacho == f($RS_Parametro, 'despacho_desmembrar')) {
          if (nvl(f($RS_Solic, 'unidade_int_posse'), '') == '') {
            SelecaoUnidade('<U>U</U>nidade de destino:', 'U', 'Selecione a unidade de destino.', nvl($w_sq_unidade, f($RS_Solic, 'unidade_int_posse')), $w_usuario, 'w_sq_unidade', 'CADPA', null);
          } else {
            $sql = new db_getUorgData;
            $RS = $sql->getInstanceOf($dbms, f($RS_Solic, 'unidade_int_posse'));
            ShowHTML('    <tr><td align="left" colspan="2"><input type="hidden" name="w_sq_unidade" value="' . f($RS, 'sq_unidade') . '"/><big><b>' . f($RS, 'nome') . '</b></big><br></td></tr>');
          }
        } else {
          SelecaoUnidade('<U>U</U>nidade de destino:', 'U', 'Selecione a unidade de destino.', $w_sq_unidade, null, 'w_sq_unidade', 'MOD_PA', null);
        }
        ShowHTML('      <tr>' . (($w_somente_interno) ? '' : '<td>') . '<td colspan="3"><font color="#BC3131"><b>Se unidade de destino igual à de origem, não há emissão de guia de remessa e o recebimento é automático.</b></font></td></tr>');
      }
    }
    if ($w_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('<INPUT type="hidden" name="w_despacho" value="DESCARTE DE PROTOCOLO.">');
    } elseif ($w_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial')) {
      ShowHTML('    <tr><td colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a ação a ser executada pelo destinatário.">' . $w_despacho . '</TEXTAREA></td>');
    } else {
      ShowHTML('    <tr><td colspan="3"><b>A<U>c</U>ondicionamento setorial:<br><INPUT ' . $w_Disabled . ' ACCESSKEY="C" class="STI" type="text" name="w_despacho" size="70" maxlength="70" value="' . $w_despacho . '">');
      ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'' . $conRootSIW . 'mod_pa/documento.php?par=TextoSetorial&p_campo=w_despacho&SG=' . $SG . '&TP=' . $TP . '&p_unidade=' . $p_unid_posse. '\',\'Texto\',\'top=10,left=10,width=780,height=550,toolbar=no,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o assunto."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
    }
    ShowHTML('      <tr><td align="LEFT" colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  }
  ShowHTML('    <tr><td align="center" colspan=3><hr>');
  if (nvl($w_tipo_despacho,'')!='') ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($w_copia) {
    $sql = new db_getLinkData;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, 'PADCAD');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&O=L&SG=' . f($RS, 'sigla') . '&P1=' . f($RS, 'p1') . '&P2=' . f($RS, 'p2') . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '\';" name="Botao" value="Cancelar">');
  }
  ShowHTML('      </table>');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</FORM>');
  Rodape();
}

// =========================================================================
// Rotina de anotação
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if (!(strpos('V', $O) === false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao', 'Anotação', '', '1', '1', '2000', '1', '1');
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
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualDocumento($w_chave, 'V', $w_usuario, $w_p1, $w_formato, 'S', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N'));
  ShowHTML('<HR>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PADENVIO', $w_pagina . $par, $O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, f($RS_Menu, 'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . f($RS, 'sq_siw_tramite') . '">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS, 'unidade_int_posse') . '">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS, 'pessoa_ext_posse') . '">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('    <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea ' . $w_Disabled . ' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">' . $w_observacao . '</TEXTAREA></td>');
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
  Rodape();
}

// =========================================================================
// Rotina de conclusão
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '') {
    // Se for recarga da página
    $w_inicio_real = $_REQUEST['w_inicio_real'];
    $w_fim_real = $_REQUEST['w_fim_real'];
    $w_concluida = $_REQUEST['w_concluida'];
    $w_data_conclusao = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao = $_REQUEST['w_nota_conclusao'];
    $w_custo_real = $_REQUEST['w_custo_real'];
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="' . $conRefreshSec . '; URL=../' . MontaURL('MESA') . '">');
  if (!(strpos('V', $O) === false)) {
    ScriptOpen('JavaScript');
    checkbranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_inicio_real', 'Início da execução', 'DATA', 1, 10, 10, '', '0123456789/');
    Validate('w_fim_real', 'Término da execução', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_inicio_real', 'Início da execução', '<=', 'w_fim_real', 'Término da execução');
    CompData('w_fim_real', 'Término da execução', '<=', FormataDataEdicao(time()), 'data atual');
    Validate('w_custo_real', 'Recurso executado', 'VALOR', '1', 4, 18, '', '0123456789.,');
    Validate('w_nota_conclusao', 'Nota de conclusão', '', '1', '1', '2000', '1', '1');
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
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_inicio_real.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da ação, na opção 'Listagem'
  ShowHTML(VisualDocumento($w_chave, 'V', $w_usuario, $w_p1, $w_formato, 'S', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N'));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'PADCONC', $w_pagina . $par, $O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $sql = new db_getSolicData;
  $RS = $sql->getInstanceOf($dbms, $w_chave, f($RS_Menu, 'sigla'));
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="' . f($RS, 'sq_siw_tramite') . '">');
  ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS, 'unidade_int_posse') . '">');
  ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS, 'pessoa_ext_posse') . '">');
  if (Nvl(f($RS, 'cd_programa'), '') > '') {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input readonly ' . $w_Disabled . ' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . Nvl($w_inicio_real, '01/01/' . $w_ano) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input readonly ' . $w_Disabled . ' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . Nvl($w_fim_real, '31/12/' . $w_ano) . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
  } else {
    ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="' . Nvl($w_inicio_real, '01/01/' . $w_ano) . '" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_inicio_real . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
    ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="' . Nvl($w_fim_real, '31/12/' . $w_ano) . '" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_fim_real . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)"></td>');
  }
  ShowHTML('              <td valign="top"><b><u>R</u>ecurso executado:</b><br><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="' . $w_custo_real . '" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor que foi efetivamente gasto com a execução do programa."></td>');
  ShowHTML('          </table>');
  ShowHTML('    <tr><td valign="top"><b>Nota d<u>e</u> conclusão:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Insira informações relevantes sobre o encerramento do exercício.">' . $w_nota_conclusao . '</TEXTAREA></td>');
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
  Rodape();
}

// =========================================================================
// Rotina de preparação para envio de e-mail comunicando a recusa de uma guia de tramitação
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: l_o         : tipo da recusa: S - protocolos; T - caixa. 
//            l_unid      :  unidade de origem da guia
//            l_numero    : número da guia
//            l_ano       : ano da guia
//            l_observacao: texto com o motivo da recusa (opcional)
// -------------------------------------------------------------------------
function MailRecusa($l_o, $l_unid, $l_numero, $l_ano, $l_observacao) {
  extract($GLOBALS);
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $sql = new db_getCustomerData;
  $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
  if (f($RS, 'envia_mail_tramite') == 'S' && f($RS_Menu, 'envia_email') == 'S') {
    $w_assunto = 'Guia ' . $l_numero . '/' . $l_ano . ' - Recusa de recebimento';
    $w_destinatarios = '';
    $w_resultado = '';
    $w_html = '<HTML>' . $crlf;
    $w_html.=BodyOpenMail(null) . $crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">' . $crlf;
    $w_html.='<tr bgcolor="' . $conTrBgColor . '"><td align="center">' . $crlf;
    $w_html.='<tr bgcolor="' . $conTrBgColor . '"><td align="center">' . $crlf;
    $w_html.='    <table width="97%" border="0">' . $crlf;
    $w_html.='      <tr><td align="center"><font size=2><b>GUIA RECUSADA</b></font><br><br><td></tr>' . $crlf;
    $w_html.='      <tr><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>' . $crlf;
    $w_html.=$crlf . '<tr bgcolor="' . $conTrBgColor . '"><td align="center">';
    $w_html.=$crlf . '    <table width="99%" border="0">';
    $w_html.=$crlf . '      <tr><td>';

    // Chama a rotina de visualização dos protocolos da guia
    $w_html.=$crlf . VisualGR(null, $l_numero, $l_ano, f($RS_Menu, 'sq_menu'), 'RECUSA');

    // Configura o remetente da tramitação como destinatário da mensagem
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'RECEBIDO', null, null, null, null, null,
                    $l_unid, null, $l_numero, $l_ano, null, null, 2, null, null, null, null, null, null, null, null);
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    $l_pessoa = f($RS, 'cadastrador');
    $sql = new db_getPersonData;
    $RS = $sql->getInstanceOf($dbms, $w_cliente, nvl($l_pessoa, 0), null, null);
    $w_destinatarios = f($RS, 'email') . '|' . f($RS, 'nome') . '; ';
    $w_html.=$crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite;
    $RS = $sql->getInstanceOf($dbms, $w_cliente);
    $w_html.='      <tr valign="top"><td><font size=2>' . $crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="' . f($RS, 'logradouro') . '" target="_blank">' . f($RS, 'Logradouro') . '</a></b></li>' . $crlf;
    $w_html.='      </font></td></tr>' . $crlf;
    $w_html.='      <tr valign="top"><td><font size=2>' . $crlf;
    $w_html.='         Dados da ocorrência:<br>' . $crlf;
    $w_html.='         <ul>' . $crlf;
    $w_html.='         <li>Responsável pela recusa: <b>' . $_SESSION['NOME'] . '</b></li>' . $crlf;
    $w_html.='         <li>Observação: <b>' . $l_observacao . '</b></li>' . $crlf;
    $w_html.='         <li>Data: <b>' . date('d/m/Y, H:i:s', time()) . '</b></li>' . $crlf;
    $w_html.='         <li>IP de origem: <b>' . $_SERVER['REMOTE_ADDR'] . '</b></li>' . $crlf;
    $w_html.='         </ul>' . $crlf;
    $w_html.='      </font></td></tr>' . $crlf;
    $w_html.='    </table>' . $crlf;
    $w_html.='</td></tr>' . $crlf;
    $w_html.='</table>' . $crlf;
    $w_html.='</BODY>' . $crlf;
    $w_html.='</HTML>' . $crlf;
  
    if ($w_destinatarios > '') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto, $w_html, $w_destinatarios, null);
    }
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado > '') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: não foi possível proceder o envio do e-mail.\n' . $w_resultado . '");');
      ScriptClose();
    }
  }
}

// =========================================================================
// Rotina de preparação para envio de e-mail relativo a programas
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic, $p_tipo) {
  extract($GLOBALS);
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $sql = new db_getCustomerData;
  $RS = $sql->getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData;
  $RSM = $sql->getInstanceOf($dbms, $p_solic, f($RS_Menu, 'sigla'));
  if (f($RS, 'envia_mail_tramite') == 'S' && (f($RS_Menu, 'envia_email') == 'S') && (f($RSM, 'envia_mail') == 'S')) {
    $l_solic = $p_solic;
    $w_destinatarios = '';
    $w_resultado = '';
    $w_html = '<HTML>' . $crlf;
    $w_html.=$BodyOpenMail[null] . $crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">' . $crlf;
    $w_html.='<tr bgcolor="' . $conTrBgColor . '"><td align="center">' . $crlf;
    $w_html.='    <table width="97%" border="0">' . $crlf;
    if ($p_tipo == 1)
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>' . $crlf;
    elseif ($p_tipo == 2)
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE PROGRAMA</b></font><br><br><td></tr>' . $crlf;
    elseif ($p_tipo == 3)
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>' . $crlf;
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>' . $crlf;
    // Recupera os dados da ação
    $w_nome = 'Programa ' . f($RSM, 'titulo');
    $w_html.=$crlf . '<tr bgcolor="' . $conTrBgColor . '"><td align="center">';
    $w_html.=$crlf . '    <table width="99%" border="0">';
    $w_html.=$crlf . '      <tr><td><font size=2>Programa: <b>' . f($RSM, 'titulo') . '</b></font></td>';
    // Identificação da ação
    $w_html.=$crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO PROGRAMA</td>';
    $w_html.=$crlf . '      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf . '          <tr valign="top">';
    $w_html.=$crlf . '          <td>Responsável pelo monitoramento:<br><b>' . f($RSM, 'nm_sol') . '</b></td>';
    $w_html.=$crlf . '          <td>Área de planejamento:<br><b>' . f($RSM, 'nm_unidade_resp') . '</b></td>';
    $w_html.=$crlf . '          <tr valign="top">';
    $w_html.=$crlf . '          <td>Data de início:<br><b>' . $FormataDataEdicao[f($RSM, 'inicio')] . ' </b></td>';
    $w_html.=$crlf . '          <td>Data de término:<br><b>' . $FormataDataEdicao[f($RSM, 'fim')] . ' </b></td>';
    $w_html.=$crlf . '          </table>';
    // Informações adicionais
    if (Nvl(f($RSM, 'descricao'), '') > '')
      $w_html.=$crlf . '      <tr><td valign="top">Resultados esperados:<br><b>' . CRLF2BR(f($RSM, 'descricao')) . ' </b></td>';
    $w_html.=$crlf . '    </table>';
    $w_html.=$crlf . '</tr>';
    // Dados da conclusão do programa, se ele estiver nessa situação
    if (f($RSM, 'concluida') == 'S' && Nvl(f($RSM, 'data_conclusao'), '') > '') {
      $w_html.=$crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCLUSÃO</td>';
      $w_html.=$crlf . '      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf . '          <tr valign="top">';
      $w_html.=$crlf . '          <td>Início da execução:<br><b>' . $FormataDataEdicao[f($RSM, 'inicio_real')] . ' </b></td>';
      $w_html.=$crlf . '          <td>Término da execução:<br><b>' . $FormataDataEdicao[f($RSM, 'fim_real')] . ' </b></td>';
      $w_html.=$crlf . '          </table>';
      $w_html.=$crlf . '      <tr><td valign="top">Nota de conclusão:<br><b>' . CRLF2BR(f($RSM, 'nota_conclusao')) . ' </b></td>';
    }
    if ($p_tipo == 2) {
      // Se for tramitação
      // Encaminhamentos
      $sql = new db_getSolicLog;
      $RS = $sql->getInstanceOf($dbms, $p_solic, null, null, 'LISTA');
      $RS = SortArray($RS, 'phpdt_data', 'desc', 'despacho', 'desc');
      foreach ($RS as $row) {
        $RS = $row;
        if (strpos(f($row, 'despacho'), '*** Nova versão') === false)
          break;
      }
      $w_html.=$crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf . '      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf . '          <tr valign="top">';
      $w_html.=$crlf . '          <td>De:<br><b>' . f($RS, 'responsavel') . '</b></td>';
      $w_html.=$crlf . '          <td>Para:<br><b>' . f($RS, 'destinatario') . '</b></td>';
      $w_html.=$crlf . '          <tr valign="top"><td colspan=2>Despacho:<br><b>' . CRLF2BR(Nvl(f($RS, 'despacho'), '---')) . ' </b></td>';
      $w_html.=$crlf . '          </table>';
      // Configura o destinatário da tramitação como destinatário da mensagem
      $sql = new db_getPersonData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, nvl(f($RS, 'sq_pessoa_destinatario'), 0), null, null);
      $w_destinatarios = f($RS, 'email') . '|' . f($RS, 'nome') . '; ';
    }
    $w_html.=$crlf . '      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite;
    $RS = $sql->getInstanceOf($dbms, $w_cliente);
    $w_html.='      <tr valign="top"><td><font size=2>' . $crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="' . f($RS, 'logradouro') . '" target="_blank">' . f($RS, 'Logradouro') . '</a></b></li>' . $crlf;
    $w_html.='      </font></td></tr>' . $crlf;
    $w_html.='      <tr valign="top"><td><font size=2>' . $crlf;
    $w_html.='         Dados da ocorrência:<br>' . $crlf;
    $w_html.='         <ul>' . $crlf;
    $w_html.='         <li>Responsável: <b>' . $_SESSION['NOME'] . '</b></li>' . $crlf;
    $w_html .= '         <li>Data: <b>' . date('d/m/Y, H:i:s', time()) . '</b></li>' . $crlf;
    $w_html.='         <li>IP de origem: <b>' . $_SERVER['REMOTE_ADDR'] . '</b></li>' . $crlf;
    $w_html.='         </ul>' . $crlf;
    $w_html.='      </font></td></tr>' . $crlf;
    $w_html.='    </table>' . $crlf;
    $w_html.='</td></tr>' . $crlf;
    $w_html.='</table>' . $crlf;
    $w_html.='</BODY>' . $crlf;
    $w_html.='</HTML>' . $crlf;
    if (f($RSM, 'st_sol') == 'S') {
      // Recupera o e-mail do responsável
      $sql = new db_getPersonData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, f($RSM, 'solicitante'), null, null);
      $w_destinatarios .= f($RS, 'email') . '|' . f($RS, 'nome') . '; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor responsável
    $sql = new db_getUorgResp;
    $RS = $sql->getInstanceOf($dbms, f($RSM, 'sq_unidade'));
    foreach ($RS as $row) {
      $RS = $row;
      break;
    }
    if (f($RS, 'st_titular') == 'S')
      $w_destinatarios .= f($RS, 'email_titular') . '|' . f($RS, 'nm_titular') . '; ';
    if (f($RS, 'st_substituto') == 'S')
      $w_destinatarios .= f($RS, 'email_substituto') . '|' . f($RS, 'nm_substituto') . '; ';
    // Recuperar o e-mail dos interessados
    $sql = new db_getSolicInter;
    $RS = $sql->getInstanceOf($dbms, $p_solic, null, 'LISTA');
    foreach ($RS as $row) {
      if (f($row, 'ativo') == 'S' && f($row, 'envia_email') == 'S')
        $w_destinatarios .= f($row, 'email') . '|' . f($row, 'nome') . '; ';
    }
    // Prepara os dados necessários ao envio
    if ($p_tipo == 1 || $p_tipo == 3) {
      // Inclusão ou Conclusão
      if ($p_tipo == 1)
        $w_assunto = 'Inclusão - ' . $w_nome; else
        $w_assunto = 'Conclusão - ' . $w_nome;
    } elseif ($p_tipo == 2) {
      // Tramitação
      $w_assunto = 'Tramitação - ' . $w_nome;
    }
    if ($w_destinatarios > '') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto, $w_html, $w_destinatarios, null);
    }
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado > '') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: não foi possível proceder o envio do e-mail.\n' . $w_resultado . '");');
      ScriptClose();
    }
  }
}

// =========================================================================
// Rotina de busca de assuntos
// -------------------------------------------------------------------------
function BuscaAssunto() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano      = $_REQUEST['w_ano'];
  $w_nome     = upper($_REQUEST['w_nome']);
  $w_codigo   = upper($_REQUEST['w_codigo']);
  $w_cliente  = $_REQUEST['w_cliente'];
  $chaveAux   = $_REQUEST['chaveAux'];
  $restricao  = $_REQUEST['restricao'];
  $campo      = $_REQUEST['campo'];

  $sql = new db_getAssunto_PA;
  $RS = $sql->getInstanceOf($dbms, $w_cliente, $chave, null, $w_codigo, $w_nome, null, null, null, null, 'S', 'BUSCA');
  $RS = SortArray($RS, 'provisorio', 'desc', 'codigo', 'asc', 'descricao', 'asc');
  Cabecalho();
  ShowHTML('<TITLE>Seleção de assunto</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_codigo, l_nome, l_chave) {');
  ShowHTML("     opener.document.Form." . $campo . "_nm.value=l_codigo + ' - ' + l_nome;");
  ShowHTML('     opener.document.Form.' . $campo . '.value=l_chave;');
  ShowHTML('     opener.document.Form.' . $campo . '_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if (count($RS) > 200 || ($w_nome > '' || $w_codigo > '')) {
    ValidateOpen('Validacao');
    Validate('w_nome', 'Nome', '1', '', '4', '30', '1', '1');
    Validate('w_codigo', 'codigo', '1', '', '2', '10', '1', '1');
    ShowHTML('  if (theForm.w_nome.value == \'\' && theForm.w_codigo.value == \'\') {');
    ShowHTML('     alert (\'Informe um valor para o nome ou para o código!\');');
    ShowHTML('     theForm.w_nome.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  }
  ScriptClose();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if (count($RS) > 200 || ($w_nome > '' || $w_codigo > '')) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  } else {
    BodyOpen('onLoad=this.focus();');
  }
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if (count($RS) > 200 || ($w_nome > '' || $w_codigo > '')) {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this))', null, $P1, $P2, $P3, $P4, $TP, $SG, null, null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="' . $w_cliente . '">');
    ShowHTML('<INPUT type="hidden" name="chaveAux" value="' . $chaveAux . '">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="' . $restricao . '">');
    ShowHTML('<INPUT type="hidden" name="campo" value="' . $campo . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td><div align="justify"><b><ul>Instruções</b>:<li>Informe parte do nome do assunto ou o código.<li>Quando a relação for exibida, selecione o assunto desejada clicando sobre a palavra <i>"Selecionar"</i> ao seu lado.<li>Após informar o nome da unidade, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td valign="top"><b>Parte da <U>d</U>escrição do assunto:<br><INPUT ACCESSKEY="D" ' . $w_Disabled . ' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="' . $w_nome . '">');
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>C</U>ódigo:<br><INPUT ACCESSKEY="S" ' . $w_Disabled . ' class="sti" type="text" name="w_codigo" size="10" maxlength="10" value="' . $w_codigo . '">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
    if ($w_nome > '' || $w_codigo > '') {
      ShowHTML('<tr><td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" border=0>');
      if (count($RS) <= 0) {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td>');
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
        ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('            <td><b>Código</td>');
        ShowHTML('            <td><b>Descrição</td>');
        ShowHTML('            <td><b>Detalhamento</td>');
        ShowHTML('            <td><b>Observação</td>');
        ShowHTML('            <td class="remover"><b>Operações</td>');
        ShowHTML('          </tr>');
        foreach ($RS as $row) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('            <td width="1%" nowrap>' . f($row, 'codigo') . '</td>');
          ShowHTML('            <td>');
          ShowHTML('                ' . f($row, 'descricao'));
          if (nvl(f($row, 'ds_assunto_pai'), '') != '') {
            echo '<br>';
            if (nvl(f($row, 'ds_assunto_bis'), '') != '')
              ShowHTML(lower(f($row, 'ds_assunto_bis')) . ' &rarr; ');
            if (nvl(f($row, 'ds_assunto_avo'), '') != '')
              ShowHTML(lower(f($row, 'ds_assunto_avo')) . ' &rarr; ');
            if (nvl(f($row, 'ds_assunto_pai'), '') != '')
              ShowHTML(lower(f($row, 'ds_assunto_pai')));
          }
          ShowHTML('            </td>');
          ShowHTML('            <td>' . nvl(lower(f($row, 'detalhamento')), '---') . '</td>');
          ShowHTML('            <td>' . nvl(f($row, 'observacao'), '---') . '</td>');
          ShowHTML('            <td class="remover"><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\'' . f($row, 'codigo') . '\', \'' . f($row, 'descricao') . '\', ' . f($row, 'chave') . ');">Selecionar</a>');
        }
        ShowHTML('        </table></tr>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      }
    }
  } else {
    ShowHTML('<tr><td align="right">'.exportaOffice().'<b>Registros: ' . count($RS));
    ShowHTML('<tr><td colspan=6>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
      ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('            <td rowspan=2><b>Código</td>');
      ShowHTML('            <td rowspan=2><b>Descrição</td>');
      ShowHTML('            <td rowspan=2><b>Detalhamento</td>');
      ShowHTML('            <td rowspan=2><b>Observação</td>');
      ShowHTML('            <td colspan=2><b>Prazos de Guarda</td>');
      ShowHTML('            <td rowspan=2><b>Destinação Final</td>');
      ShowHTML('          </tr>');
      ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('            <td><b>Corrente</td>');
      ShowHTML('            <td><b>Intermediária</td>');
      ShowHTML('          </tr>');
      $w_atual = '';
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        if ($w_atual != nvl(f($row, 'ds_assunto_pai'), '')) {
          $w_cor = $w_cor = $conTrAlternateBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('            <td><b>' . f($row, 'cd_assunto_pai') . '</b></td>');
          ShowHTML('            <td colspan="7"><b>');
          if (nvl(f($row, 'ds_assunto_bis'), '') != '')
            ShowHTML(f($row, 'ds_assunto_bis') . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_avo'), '') != '')
            ShowHTML(f($row, 'ds_assunto_avo') . ' &rarr; ');
          if (nvl(f($row, 'ds_assunto_pai'), '') != '')
            ShowHTML(f($row, 'ds_assunto_pai'));
          ShowHTML('            </b></td>');
          ShowHTML('      </tr>');
          $w_atual = f($row, 'ds_assunto_pai');
        }
        $w_cor = $w_cor = $conTrBgColor;
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('            <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\'' . f($row, 'codigo') . '\', \'' . f($row, 'descricao') . '\', ' . f($row, 'chave') . ');">' . f($row, 'codigo') . '</a></td>');
        ShowHTML('            <td>' . f($row, 'descricao'));
        ShowHTML('            </td>');
        ShowHTML('            <td>' . nvl(lower(f($row, 'detalhamento')), '---') . '</td>');
        ShowHTML('            <td>' . nvl(f($row, 'observacao'), '---') . '</td>');
        if (f($row, 'sg_corrente_guarda') == 'NAPL')
          ShowHTML('            <td align="center">---</td>'); else
          ShowHTML('            <td align="center" ' . ((f($row, 'sg_corrente_guarda') != 'ANOS') ? 'title="' . f($row, 'ds_corrente_guarda') . '"' : '') . '>' . f($row, 'guarda_corrente') . '</td>');
        if (f($row, 'sg_intermed_guarda') == 'NAPL')
          ShowHTML('            <td align="center">---</td>'); else
          ShowHTML('            <td align="center" ' . ((f($row, 'sg_intermed_guarda') != 'ANOS') ? 'title="' . f($row, 'ds_intermed_guarda') . '"' : '') . '>' . f($row, 'guarda_intermed') . '</td>');
        if (f($row, 'sg_final_guarda') == 'NAPL')
          ShowHTML('            <td align="center">---</td>'); else
          ShowHTML('            <td align="center" title="' . f($row, 'ds_destinacao_final') . '">' . f($row, 'sg_destinacao_final') . '</td>');
      }
      ShowHTML('        </table></tr>');
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
    }
  }
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  rodape();
}

// =========================================================================
// Executa a tramitação de protocolos
// -------------------------------------------------------------------------
function Tramitacao() {
  extract($GLOBALS);
  global $w_Disabled;
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }

  if ($w_troca > '') {
    // Se for recarga da página
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }

  if ($p_tipo_despacho > '') {
    $sql = new db_getTipoDespacho_PA;
    $RS = $sql->getInstanceOf($dbms, $p_tipo_despacho, $w_cliente, null, null, null, null);
    foreach ($RS as $row) { $RS = $row; break; }
    $w_nm_tipo_despacho = f($RS, 'nome');
  }

  // Recupera os dados da unidade central de protocolo
  $sql = new db_getUorgList;
  $RS_Prot = $sql->getInstanceOf($dbms, $w_cliente, null, 'MOD_PA_PROT', null, null, $w_ano);
  foreach ($RS_Prot as $row) { $RS_Prot = $row; break; }

  // Recupera os dados da unidade central de arquivo
  $sql = new db_getUorgData; $RS_Arq = $sql->getInstanceOf($dbms, f($RS_Parametro, 'arquivo_central'));

  if ($p_tipo_despacho == f($RS_Parametro, 'despacho_autuar') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')
     )
    $w_envia_protocolo = 'S'; 
  else
    $w_envia_protocolo = 'N';

  if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) $w_envia_arquivo = 'S'; else $w_envia_arquivo = 'N';

  if ($p_tipo_despacho == f($RS_Parametro, 'despacho_desmembrar')) $w_desmembrar = 'S'; else $w_desmembrar = 'N';

  // Verifica se pode ser feito envio externo. Se não puder, nem mostra opção ao usuário
  if ($w_envia_protocolo =='S' || $w_envia_arquivo=='S' || $w_desmembrar=='S' || $w_gestor==false ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_anexar') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_apensar') ||
      $p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')
     )
     $w_somente_interno = true;
  else
     $w_somente_interno = false;

  if ($O == 'L') {
    // Configura o valor default do destino do envio
    $w_interno = nvl($w_interno,'S');
    
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) {
      // Recupera caixas para transferência
      $sql = new db_getCaixa;
      $RS = $sql->getInstanceOf($dbms, $p_chave, $w_cliente, $w_usuario, nvl($p_unid_posse, $_SESSION['LOTACAO']), null, null, null, null, null, null, null, null,null,null,null,'TRAMITE');
      if (Nvl($p_ordena, '') > '') {
        $lista = explode(',', str_replace(' ', ',', $p_ordena));
        $RS = SortArray($RS, $lista[0], $lista[1], 'numero', 'asc');
      } else {
        $RS = SortArray($RS, 'numero', 'asc');
      }
      $w_existe = count($RS);

      if (count($w_chave) > 0) {
        $i = 0;
        foreach ($w_chave as $k => $v) {
          foreach ($RS as $row) {
            if ($w_chave[$i] == f($row, 'sq_caixa')) {
              $w_marcado[f($row, 'sq_caixa')] = 'ok';
              break;
            }
          }
          $i += 1;
        }
        reset($RS);
      }
    } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
      // Recupera caixas para transferência
      $sql = new db_getCaixa;
      $RS = $sql->getInstanceOf($dbms, $p_chave, $w_cliente, $w_usuario, $p_unid_posse, null, null, null, null, null, null, null, null,null,null,null,'DEVOLVE');
      if (Nvl($p_ordena, '') > '') {
        $lista = explode(',', str_replace(' ', ',', $p_ordena));
        $RS = SortArray($RS, $lista[0], $lista[1], 'numero', 'asc');
      } else {
        $RS = SortArray($RS, 'sg_unidade', 'asc', 'numero', 'asc');
      }
      $w_existe = count($RS);

      if (count($w_chave) > 0) {
        $i = 0;
        foreach ($w_chave as $k => $v) {
          foreach ($RS as $row) {
            if ($w_chave[$i] == f($row, 'sq_caixa')) {
              $w_marcado[f($row, 'sq_caixa')] = 'ok';
              break;
            }
          }
          $i += 1;
        }
        reset($RS);
      }
    } else {
      if (nvl($p_assunto, '') != '') {
        $sql = new db_getAssunto_PA;
        $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_assunto, null, null, null, null, null, null, null, null, 'REGISTROS');
        foreach ($RS_Assunto as $row) {
          $RS_Assunto = $row;
          break;
        }
        $p_sq_acao_ppa = f($row,'codigo');
      }

      // Recupera todos os registros para a listagem
      $sql = new db_getProtocolo;
      $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux,
                      $p_prefixo, $p_numero, $p_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, $p_ini, $p_fim, 2,
                      $p_tipo_despacho, $p_empenho, $p_solicitante, $p_unidade, $p_proponente, $p_sq_acao_ppa, $p_detalhamento, $p_processo);
      if (Nvl($p_ordena, '') > '') {
        $lista = explode(',', str_replace(' ', ',', $p_ordena));
        $RS = SortArray($RS, $lista[0], $lista[1], 'prefixo', 'asc', 'ano', 'desc', 'numero_documento', 'asc');
      } else {
        $RS = SortArray($RS, 'prefixo', 'asc', 'ano', 'desc', 'numero_documento', 'asc');
      }
      $w_existe = count($RS);

      if (count($w_chave) > 0) {
        $i = 0;
        foreach ($w_chave as $k => $v) {
          foreach ($RS as $row) {
            if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
              $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
              break;
            }
          }
          $i += 1;
        }
        reset($RS);
      }
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_tipo_despacho', 'Despacho', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    ShowHTML('  if ((theForm.p_numero.value!="" && theForm.p_ano.value=="") || (theForm.p_numero.value=="" && theForm.p_ano.value!="")) {');
    ShowHTML('     alert ("Para pesquisa pelo protocolo informe pelo menos o número e o ano!");');
    ShowHTML('     theForm.p_ano.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    Validate('p_unid_posse', 'Unidade de posse', 'SELECT', '', '1', '18', '', '0123456789');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    //Validate('p_sq_acao_ppa', 'Código do assunto', '', '', '1', '10', '1', '1');
    Validate('p_detalhamento', 'Detalhamento do assunto/Despacho', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($O == 'L') {
    ScriptOpen('JavaScript');
    ShowHTML('  $(document).ready(function() {');
    ShowHTML('    $("#marca_todos").click(function() {');
    ShowHTML('      var checked = this.checked;');
    ShowHTML('      $(".item").each(function() {');
    ShowHTML('        this.checked = checked;');
    ShowHTML('      });');
    ShowHTML('    });');
    ShowHTML('  });');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var chave; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  var w_envia_outra=false; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('       chave=theForm["w_chave[]"][i].value; ');
    ShowHTML('       if (theForm["w_mesma_lotacao["+chave+"]"].value==\'N\') {');
    ShowHTML('         w_envia_outra=true; ');
    ShowHTML('       }');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral') || $p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
      ShowHTML('    alert("Você deve informar pelo menos uma caixa!"); ');
    } else {
      ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
    }
    ShowHTML('    return false;');
    ShowHTML('  }');
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $p_tipo_despacho == f($RS_Parametro, 'despacho_anexar')) {
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=false; ');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm.w_protocolo_nm.value == theForm["w_lista[]"][i].value && theForm["w_chave[]"][i].checked) {');
      ShowHTML('       w_erro=true; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("Não é possível juntar um protocolo a ele mesmo!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
    }
    if ($w_envia_protocolo == 'N' && $p_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial') && $p_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
      Validate('w_retorno_limite', 'Prazo de resposta', 'DATA', '', 10, 10, '', '0123456789/');
      CompData('w_retorno_limite', 'Prazo de resposta', '>=', FormataDataEdicao(time()), 'data atual');
      Validate('w_dias', 'Dias para encaminhamento', '1', '', 1, 3, '', '0123456789');
      ShowHTML('  if (theForm.w_aviso[0].checked) {');
      ShowHTML('     if (theForm.w_dias.value == \'\') {');
      ShowHTML('        alert("Informe a partir de quantos dias após o envio você deseja ser avisado!");');
      ShowHTML('        theForm.w_dias.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     theForm.w_dias.value = \'\';');
      ShowHTML('  }');
    }  
    if ($w_envia_protocolo == 'N') {
      if (!$w_somente_interno) Validate('w_interno', 'Tipo da unidade/pessoa', 'SELECT', 1, 1, 1, 'SN', '');
      if ($w_interno == 'N') {
        Validate('w_pessoa_destino', 'Pessoa de destino', 'HIDDEN', 1, 1, 18, '', '0123456789');
        Validate('w_unidade_externa', 'Unidade externa', '', '', 2, 60, '1', '1');
      } elseif ($p_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
        if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
          Validate('w_sq_unidade', 'Unidade arquivadora', 'SELECT', 1, 1, 18, '', '0123456789');
        } else {
          Validate('w_sq_unidade', 'Unidade de destino', 'SELECT', 1, 1, 18, '', '0123456789');
        }
      }
    }
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $p_tipo_despacho == f($RS_Parametro, 'despacho_anexar')) {
      Validate('w_protocolo_nm', 'ao processo', 'hidden', '1', '20', '20', '', '0123456789./-');
    }
    if ($w_desmembrar == 'S') {
      Validate('w_despacho', 'Protocolos a serem desmembrados', '', '1', '1', '2000', '1', '1');
    } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
      ShowHTML('  var unid_arq = 0;');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
      ShowHTML('       w_erro=false; ');
      ShowHTML('       chave=theForm["w_chave[]"][i].value; ');
      ShowHTML('       if (unid_arq>0 && (theForm["w_unid_origem["+chave+"]"].value!=unid_arq || theForm["w_unid_origem["+chave+"]"].value!=theForm.w_sq_unidade[theForm.w_sq_unidade.selectedIndex].value)) {');
      ShowHTML('         w_erro=true; ');
      ShowHTML('       }');
      ShowHTML('       unid_arq = theForm["w_unid_origem["+chave+"]"].value; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (unid_arq!=theForm.w_sq_unidade[theForm.w_sq_unidade.selectedIndex].value) {');
      ShowHTML('    w_erro=true; ');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("As caixas selecionadas devem estar de posse da mesma unidade, que deve ser igual à unidade arquivadora selecionada!"); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_despacho', 'Observações sobre o acondicionamento do protocolo', '', '1', '1', '70', '1', '1');
    } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
      ShowHTML('  var unid_arq = 0;');
      ShowHTML('  w_erro=false; ');
      ShowHTML('  w_alerta=false; ');
      ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
      ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
      ShowHTML('       chave=theForm["w_chave[]"][i].value; ');
      ShowHTML('       if (unid_arq>0 && (theForm["w_unid_origem["+chave+"]"].value!=unid_arq || theForm["w_unid_origem["+chave+"]"].value!=theForm.w_sq_unidade[theForm.w_sq_unidade.selectedIndex].value)) {');
      ShowHTML('         w_erro=true; ');
      ShowHTML('       }');
      ShowHTML('       unid_arq = theForm["w_unid_origem["+chave+"]"].value; ');
      ShowHTML('    }');
      ShowHTML('  }');
      ShowHTML('  if (unid_arq!=theForm.w_sq_unidade[theForm.w_sq_unidade.selectedIndex].value) {');
      ShowHTML('    w_alerta=true; ');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert("As caixas selecionadas ser da mesma unidade arquivadora!"); ');
      ShowHTML('    return false;');
      ShowHTML('  } else if (w_alerta) {');
      ShowHTML('    alert("ALERTA: A unidade de destino é diferente da unidade arquivadora das caixas selecionadas!\nO sistema não impedirá o envio mas certifique-se que o destino está correto."); ');
      ShowHTML('    theForm.w_sq_unidade.focus();');
      ShowHTML('  }');
      Validate('w_despacho', 'Detalhamento do despacho', '', '1', '1', '2000', '1', '1');
    } elseif ($p_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
      Validate('w_despacho', 'Detalhamento do despacho', '', '1', '1', '2000', '1', '1');
    }
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
      ShowHTML('  if (w_envia_outra) {');
      ShowHTML('    if (!confirm("Você selecionou protocolos que estão de posse de outras unidades!\nCONFIRMA O ARQUIVAMENTO?")) return false;');
      ShowHTML('  } else {');
      ShowHTML('    if (!confirm(\'Confirma o arquivamento dos protocolos selecionados?\')) return false;');
      ShowHTML('  }');
    } elseif ($p_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('  if (w_envia_outra) {');
      ShowHTML('    if (!confirm("Você selecionou protocolos que estão de posse de outras unidades!\nCONFIRMA O ENVIO?")) return false;');
      ShowHTML('  } else {');
      ShowHTML('    if (!confirm(\'Confirma a geração de guia de tramitação APENAS para ' . (($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral') || $p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) ? 'as caixas selecionadas' : 'os documentos selecionados') . '?\')) return false;');
      ShowHTML('  }');
    }
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_tipo_despacho.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    if (nvl($p_numero,'')!='' && nvl($p_ano,'')!='') {
      ShowHTML('  <li>PROTOCOLOS ARQUIVADOS SETORIALMENTE SERÃO AUTOMATICAMENTE DESARQUIVADOS.');
      ShowHTML('  <li>PROTOCOLOS DESCARTADOS SERÃO AUTOMATICAMENTE RECUPERADOS.');
    } else {
      ShowHTML('  <li>PROTOCOLOS JUNTADOS NÃO PODEM SER ENVIADOS NEM DESCARTADOS.');
    }
    ShowHTML('  <li>Se o trâmite for para pessoa jurídica, não se esqueça de informar para qual unidade dessa entidade você está enviando.');
    ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão <i>Gerar Guia de Tramitação</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr valign="top"><td nowrap>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td colspan=2 align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral') || $p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
      if (count($RS)) {
        ShowHTML('          <td align="center"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
      } else {
        ShowHTML('          <td align="center">&nbsp;</td>');
      }
      ShowHTML('          <td width="1%" nowrap><b>Caixa</td>');
      ShowHTML('          <td><b>Unidade</td>');
      ShowHTML('          <td><b>Data Limite</td>');
      ShowHTML('          <td><b>Intermediário</td>');
      ShowHTML('          <td><b>Destinação final</td>');
      ShowHTML('        </tr>');
    } else {
      if (count($RS)) {
        ShowHTML('          <td rowspan="2" align="center"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
      } else {
        ShowHTML('          <td rowspan="2" align="center">&nbsp;</td>');
      }
      ShowHTML('          <td rowspan=2><b>' . linkOrdena('Posse', 'sg_unidade_posse', 'Form') . '</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>');
      ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Tipo', 'nm_tipo', 'Form') . '</td>');
      ShowHTML('          <td colspan=4><b>Documento original</td>');
      ShowHTML('          <td rowspan=2><b>' . linkOrdena('Resumo', 'descricao', 'Form') . '</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
      ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie', 'Form') . '</td>');
      ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original', 'Form') . '</td>');
      ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio', 'Form') . '</td>');
      ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc', 'Form') . '</td>');
      ShowHTML('        </tr>');
    }
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
    ShowHTML('<input type="hidden" name="w_lista[]" value=""></td>');
    ShowHTML('<INPUT type="hidden" name="w_unidade_posse" value="' . f($RS_Solic, 'unidade_int_posse') . '">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_posse" value="' . f($RS_Solic, 'pessoa_ext_posse') . '">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_despacho" value="' . $p_tipo_despacho . '">');
    if (nvl($_REQUEST['p_ordena'], '') == '') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
    ShowHTML('<INPUT type="hidden" name="w_arq_central" value="'.(($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) ? 'S' : 'N').'">');
    ShowHTML('<INPUT type="hidden" name="w_desarq_central" value="'.(($p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) ? 'S' : 'N').'">');
    ShowHTML('<INPUT type="hidden" name="w_arq_setorial" value="'.(($p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) ? 'S' : 'N').'">');
    ShowHTML('<INPUT type="hidden" name="w_descartar" value="'.(($p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) ? 'S' : 'N').'">');
    ShowHTML(MontaFiltro('POST'));
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan="9" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      $w_outra_unidade = false;
      foreach ($RS as $row) {
        /*
        if (f($row, 'st_mesma_lotacao') == 'N') {
          ShowHTML('      <tr bgcolor="' . $conTrBgColorLightRed1 . '" valign="top">');
          $w_outra_unidade = true;
        } else {
        */
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        /*
        }
        */
        if ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) {
          ShowHTML('        <td align="center">');
          ShowHTML('          <INPUT type="hidden" name="w_unid_origem[' . f($row, 'sq_caixa') . ']" value="' . f($row, 'sq_unidade') . '">');
          ShowHTML('          <INPUT type="hidden" name="w_mesma_lotacao[' . f($row, 'sq_caixa') . ']" value="' . f($row, 'st_mesma_lotacao') . '">');
          ShowHTML('          <input class="item" type="CHECKBOX" ' . ((nvl($w_marcado[f($row, 'sq_caixa')], '') != '') ? 'CHECKED' : '') . ' name="w_chave[]" value="' . f($row, 'sq_caixa') . '"></td>');
          ShowHTML('        </td>');
          ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;<A onclick="window.open (\'' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_formato=WORD&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">' . f($row, 'numero') . '/' . f($row, 'sg_unidade') . '</a>&nbsp;');
          ShowHTML('        <td>' . f($row, 'nm_unidade') . '</td>');
          ShowHTML('        <td align="center">' . formataDataEdicao(f($row, 'data_limite')) . '</td>');
          ShowHTML('        <td align="center">' . f($row, 'intermediario') . '</td>');
          ShowHTML('        <td>' . f($row, 'destinacao_final') . '</td>');
        } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
          ShowHTML('        <td align="center">');
          ShowHTML('          <INPUT type="hidden" name="w_unid_origem[' . f($row, 'sq_caixa') . ']" value="' . f($row, 'sq_unidade') . '">');
          ShowHTML('          <INPUT type="hidden" name="w_mesma_lotacao[' . f($row, 'sq_caixa') . ']" value="' . f($row, 'st_mesma_lotacao') . '">');
          ShowHTML('          <input class="item" type="CHECKBOX" ' . ((nvl($w_marcado[f($row, 'sq_caixa')], '') != '') ? 'CHECKED' : '') . ' name="w_chave[]" value="' . f($row, 'sq_caixa') . '"></td>');
          ShowHTML('        </td>');
          ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;<A onclick="window.open (\'' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_formato=WORD&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">' . f($row, 'numero') . '/' . f($row, 'sg_unidade') . '</a>&nbsp;');
          ShowHTML('        <td>' . f($row, 'nm_unidade') . '</td>');
          ShowHTML('        <td align="center">' . formataDataEdicao(f($row, 'data_limite')) . '</td>');
          ShowHTML('        <td align="center">' . f($row, 'intermediario') . '</td>');
          ShowHTML('        <td>' . f($row, 'destinacao_final') . '</td>');
        } else {
          // Valida cada documento
          $w_erro = ValidaDocumento($w_cliente, f($row, 'sq_siw_solicitacao'), 'PADCAD', null, null, null, f($row, 'sg_tramite'));
          if (nvl($w_erro, '') != '') {
            $w_msg = substr($w_erro, 1);
            $w_tipo = substr($w_erro, 0, 1);
          } else {
            $w_msg = '';
            $w_tipo = '';
          }
          ShowHTML('        <td class="remover" align="center" width="1%" nowrap>');
          if ($w_tipo == '') {
            ShowHTML('          <INPUT type="hidden" name="w_tramite[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'sq_siw_tramite') . '">');
            ShowHTML('          <INPUT type="hidden" name="w_unid_origem[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_int_posse') . '">');
            ShowHTML('          <INPUT type="hidden" name="w_unid_autua[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'unidade_autuacao') . '">');
            ShowHTML('          <INPUT type="hidden" name="w_mesma_lotacao[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'st_mesma_lotacao') . '">');
            ShowHTML('          <INPUT type="hidden" name="w_lista[]" value="' . f($row, 'protocolo') . '">');
            if (nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') {
              ShowHTML('          <input class="item" type="CHECKBOX" CHECKED name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '"></td>');
            } else {
              if (in_array(f($row, 'sq_siw_solicitacao'), $itens)) {
                ShowHTML('          <input class="item" type="CHECKBOX" CHECKED  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '"></td>');
              } else {
                ShowHTML('          <input class="item" type="CHECKBOX"  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '"></td>');
              }
            }
          }
          ShowHTML('        </td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . ExibeUnidade('../', $w_cliente, f($row, 'sg_unidade_posse'), f($row, 'unidade_int_posse'), $TP) . '</td>');
          ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
          ShowHTML('        <td width="10">&nbsp;' . f($row, 'nm_tipo') . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
          ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
          ShowHTML('        <td width="1%" nowrap>&nbsp;' . formataDataEdicao(f($row, 'inicio'), 5) . '&nbsp;</td>');
          ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
          if ($w_tipo == '') {
            if (strlen(Nvl(f($row, 'descricao'), '-')) > 50) $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 50) . '...'; 
            else $w_titulo=Nvl(f($row, 'descricao'), '-');
            ShowHTML('        <td title="' . htmlspecialchars(f($row, 'descricao')) . '">'.((f($row, 'sg_tramite') == 'CA') ? '<strike>' . $w_titulo . '</strike>' : $w_titulo) . '</td>');
          } else {
            ShowHTML('        <td><font color="#BC3131"><b>' . $w_msg . '</b></font></td>');
          }
        }
        ShowHTML('      </tr>');
        $i += 1;
      }
    }
    if ($w_outra_unidade) ShowHTML('      <tr><td colspan="9"><b>ATENÇÃO: Linha na cor vermelha indica que o protocolo está de posse de unidade diferente da sua!');
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    if ($w_envia_protocolo == 'N' && $p_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial') && $p_tipo_despacho != f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>NOVO TRÂMITE</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('           <td nowrap title="Informe a data limite para que o destinatário encaminhe o documento."><b>Praz<u>o</u> de resposta:</b><br><input ' . $w_Disabled . ' accesskey="O" type="text" name="w_retorno_limite" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $w_retorno_limite . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'w_retorno_limite') . '</td>');
      MontaRadioNS('<b>Emite alerta de não encaminhamento?</b>', $w_aviso, 'w_aviso');
      ShowHTML('           <td valign="top"><b>Quantos <U>d</U>ias após esta tramitação?<br><INPUT ACCESSKEY="D" ' . $w_Disabled . ' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="' . $w_dias . '" title="A partir de quantos dias após este encaminhamento o sistema deve emitir o alerta."></td>');
      ShowHTML('      </tr>');
      ShowHTML('      <tr><td colspan=3>&nbsp;</td></tr>');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
      ShowHTML('<INPUT type="hidden" name="w_dias" value="0">');
    }
    if ($w_envia_arquivo == 'S') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO: ' . upper(f($RS_Arq, 'nome')) . '</b></font></td></tr>');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . f($RS_Arq, 'sq_unidade') . '">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    } elseif ($w_envia_protocolo == 'S') {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO: ' . upper(f($RS_Prot, 'nome')) . '</b></font></td></tr>');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . f($RS_Prot, 'sq_unidade') . '">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="S">');
    } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqsetorial')) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DADOS DO ARQUIVAMENTO</b></font></td></tr>');
      ShowHTML('    <tr valign="top">');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      ShowHTML('    <tr><td>Usuário arquivador:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
      SelecaoUnidade('Unidade ar<U>q</U>uivadora:', 'Q', 'Selecione o arquivo setorial.', nvl($w_sq_unidade,$p_unid_posse), $w_usuario, 'w_sq_unidade', 'CADPA', null,3,'<td>');
      ShowHTML('    <tr><td><b>A<U>c</U>ondicionamento:<td title="Descreva de forma objetiva onde o documento encontra-se no arquivo setorial."><INPUT ' . $w_Disabled . ' ACCESSKEY="C" class="STI" type="text" name="w_despacho" size="70" maxlength="70" value="' . $w_despacho . '">');
      ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'' . $conRootSIW . 'mod_pa/documento.php?par=TextoSetorial&p_campo=w_despacho&SG=' . $SG . '&TP=' . $TP . '&p_unidade=' . $p_unid_posse. '\',\'Texto\',\'top=10,left=10,width=780,height=550,toolbar=no,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o assunto."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
      ShowHTML('    <tr><td><b><U>A</U>ssinatura Eletrônica:<td><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('   <tr><td align="center" colspan=3><hr>');
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Arquivar">');
    } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) {
      ShowHTML('    <tr><td colspan="3">&nbsp;</td></tr>');
      ShowHTML('    <tr><td colspan="3" bgcolor="#f0f0f0" align=justify><font size="2"><b>DADOS DO DESCARTE</b></font></td></tr>');
      ShowHTML('    <tr valign="top"><td>');
      ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="' . $p_unid_posse . '">');
      ShowHTML('    <tr><td width="30%">Usuário responsável:<td colspan=2><b>' . $_SESSION['NOME'] . '</b></td></tr>');
    } else {
      ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>DESTINO:</b></font></td></tr>');
      ShowHTML('      <tr valign="top">');
      if ($w_somente_interno) {
        ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
      } else {
        selecaoOrigem('<u>T</u>ipo da unidade/pessoa:', 'T', 'Indique se a unidade ou pessoa é interna ou externa.', $w_interno, null, 'w_interno', null, 'onChange="document.Form.action=\'' . $w_dir . $w_pagina . $par . '\'; document.Form.w_troca.value=\'w_interno\'; document.Form.submit();"');
      }
      if ($w_interno == 'N') {
        SelecaoPessoaOrigem('<u>P</u>essoa de destino:', 'P', 'Clique na lupa para selecionar a pessoa de destino.', $w_pessoa_destino, null, 'w_pessoa_destino', null, null, null, 3);
        ShowHTML('    <tr><td><td colspan="2"><b>U<U>n</U>idade externa: (Informe apenas para pessoas jurídicas)<br><INPUT ACCESSKEY="N" ' . $w_Disabled . ' class="STI" type="text" name="w_unidade_externa" size="30" maxlength="60" value="' . $w_unidade_externa . '"></td>');
      } else {
        if ($p_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $p_tipo_despacho == f($RS_Parametro, 'despacho_anexar') || $p_tipo_despacho == f($RS_Parametro, 'despacho_desmembrar')) {
          if(nvl($p_unid_posse,'')==''){
            SelecaoUnidade('<U>U</U>nidade de destino:', 'U', 'Selecione a unidade de destino.', nvl($w_sq_unidade,$p_unid_posse), $w_usuario, 'w_sq_unidade', 'CADPA', null, 3);
          }else{
            $sql = new db_getUorgData;
            $RS = $sql->getInstanceOf($dbms, $p_unid_posse);
            ShowHTML('    <tr><td align="left" colspan="2"><input type="hidden" name="w_sq_unidade" value="'.$p_unid_posse.'"/><big><b>' . f($RS, 'nome').'</b></big><br></td></tr>');
          }
        } else {
          SelecaoUnidade('<U>U</U>nidade de destino:', 'U', 'Selecione a unidade de destino.', $w_sq_unidade, null, 'w_sq_unidade', 'MOD_PA', null, 3);
        }
        ShowHTML('      <tr>' . (($w_somente_interno) ? '' : '<td>') . '<td colspan="3"><font color="#BC3131"><b>Se unidade de destino igual à de origem, não há emissão de guia de remessa e o recebimento é automático.</b></font></td></tr>');
      }
    }
    if ($p_tipo_despacho != f($RS_Parametro, 'despacho_arqsetorial')) {
      if ($p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) {
        ShowHTML('<INPUT type="hidden" name="w_despacho" value="DESCARTE DE PROTOCOLO.">');
      } else {
        ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
        ShowHTML('      <tr><td colspan="3" bgcolor="#f0f0f0" align=justify><font size="2"><b>DESPACHO: ' . $w_nm_tipo_despacho . '</b></font></td></tr>');
        if ($p_tipo_despacho == f($RS_Parametro, 'despacho_apensar') || $p_tipo_despacho == f($RS_Parametro, 'despacho_anexar')) {
          SelecaoProtocolo('ao <U>p</U>rocesso:', 'U', 'Selecione o processo ao qual o protocolo será juntado.', $w_protocolo, $p_unid_posse, 'w_protocolo', 'JUNTADA', null, 3);
          //ShowHTML('        <td><b>ao <u>p</u>rocesso:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_protocolo" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_protocolo.'" onKeyDown="FormataProtocolo(this,event);"></td>');
        }

        if ($w_desmembrar == 'S') {
          ShowHTML('    <tr><td valign="top" colspan=3><b>Protocolos a serem d<u>e</u>smembrados:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Relacione o(s) protocolo(s) a ser(em) desmembrado(s).">' . $w_despacho . '</TEXTAREA></td>');
        } else {
          ShowHTML('    <tr valign="top"><td colspan=3><b>Detalhamento do d<u>e</u>spacho:</b><br><textarea ' . $w_Disabled . ' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Detalhe a ação a ser executada pelo destinatário.">' . nvl($w_despacho, (($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral')) ? 'Arquivar no Arquivo Central.' : '')) . '</TEXTAREA></td>');
        }
      }
      ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('   <tr><td align="center" colspan=3><hr>');
      if ($p_tipo_despacho == f($RS_Parametro, 'despacho_eliminar')) {
        ShowHTML('   <input class="STB" type="submit" name="Botao" value="Descartar">');
      } elseif ($p_tipo_despacho == f($RS_Parametro, 'despacho_arqcentral') || $p_tipo_despacho == f($RS_Parametro, 'despacho_desarqcentral')) {
        ShowHTML('   <input class="STB" type="submit" name="Botao" value="Gerar Guia de Transferência">');
      } else {
        ShowHTML('   <input class="STB" type="submit" name="Botao" value="Gerar Guia de Tramitação">');
      }
    }
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  <B>ORIENTAÇÃO:</B><ul>');
    ShowHTML('  <li><b>INFORME O DESPACHO A SER UTILIZADO PARA ESTE ENVIO DE PROTOCOLO(S).</b>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todos os protocolos que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr valign="top">');
    selecaoTipoDespacho('Des<u>p</u>acho a ser usado para o envio:', 'P', 'Selecione o despacho a ser utilizado para os documentos relacionados na próxima tela.', $w_cliente, $p_tipo_despacho, null, 'p_tipo_despacho', 'SELECAO', null);
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade de posse do protocolo (somente unidades que o usuário tem acesso):', 'U', 'Selecione a unidade de posse.', nvl($p_unid_posse,$_SESSION['LOTACAO']), $w_usuario, 'p_unid_posse', 'CADPA', null,2);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="' . $p_sq_acao_ppa . '"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_detalhamento" size="40" maxlength="30" value="' . $p_detalhamento . '"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_assunto, null, 'p_assunto', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Prepara transferência de protocolos para o arquivo central
// -------------------------------------------------------------------------
function TramitCentral() {
  extract($GLOBALS);
  global $w_Disabled;
  global $p_unid_posse;
  
  $p_unid_posse = nvl($p_unid_posse,$_SESSION['LOTACAO']);
  
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }

  if ($w_troca > '') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
    $w_opcao            = $_REQUEST['w_opcao'];
    $w_local            = $_REQUEST['w_local'];
  }

  // Verifica se a unidade de lotação do usuário está cadastrada na relação de unidades do módulo
  $sql = new db_getUorgList; $RS_Prot = $sql->getInstanceOf($dbms, $w_cliente, null, 'MOD_PA_PROT', null, null, $w_ano);
  foreach ($RS_Prot as $row) { $RS_Prot = $row; break; }

  if ($O == 'L') {
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux,$p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia,$p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, $p_solicitante, 
                  $p_unidade, $p_proponente,$p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'protocolo_ordena', 'asc');
    } else {
      $RS = SortArray($RS, 'protocolo_ordena', 'asc');
    }
    $w_existe = count($RS);

    if (count($w_chave) > 0) {
      $i = 0;
      foreach ($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
            $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i += 1;
      }
      reset($RS);
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    Validate('p_unid_posse', 'Unidade de posse', 'SELECT', '1', '1', '18', '', '1');
    //Validate('p_sq_acao_ppa', 'Código do assunto', 'HIDDEN', '', '1', '10', '1', '1');
    Validate('p_assunto', 'Detalhamento do assunto/Despacho', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    ShowHTML('  $(document).ready(function() {');
    ShowHTML('    $("#marca_todos").click(function() {');
    ShowHTML('      var checked = this.checked;');
    ShowHTML('      $(".item").each(function() {');
    ShowHTML('        this.checked = checked;');
    ShowHTML('      });');
    ShowHTML('    });');
    ShowHTML('  });');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=0; i < theForm.w_opcao.length; i++) {');
    ShowHTML('    if (theForm.w_opcao[i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve informar uma das ações disponíveis!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    ShowHTML('  if (theForm.w_opcao[0].checked) {');
    ShowHTML('     if (theForm.w_caixa.selectedIndex>0 || theForm.w_pasta.value!="" || theForm.w_local.value!="") {');
    ShowHTML('        alert("A opção selecionada não permite informar caixa, pasta nem acondicionamento!"); ');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  } else if (theForm.w_opcao[1].checked) {');
    Validate('w_caixa', 'Caixa para arquivamento', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_pasta', 'Pasta', '', 1, 1, 20, '1', '1');
    ShowHTML('    if (theForm.w_local.value!="") {');
    ShowHTML('       alert("A opção selecionada não permite informar acondicionamento!"); ');
    ShowHTML('       return false;');
    ShowHTML('    }');
    ShowHTML('  } else if (theForm.w_opcao[2].checked) {');
    ShowHTML('     if (theForm.w_caixa.selectedIndex>0 || theForm.w_pasta.value!="") {');
    ShowHTML('        alert("A opção selecionada não permite informar caixa nem pasta!"); ');
    ShowHTML('        return false;');
    ShowHTML('     }');
    Validate('w_local', 'Acondicionamento', '1', 1, 1, 70, '1', '1');
    ShowHTML('  }');
    ShowHTML('  var w_teste = ""; ');
    ShowHTML('  for (i=1; i < theForm["w_assunto[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('      if (theForm["w_prov[]"][i].value == "S") {');
    ShowHTML('        var w_teste = w_teste + "\n" + theForm["w_codigo[]"][i].value; ');
    ShowHTML('      }');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_teste != "") {');
    ShowHTML('     alert("Não é permitido arquivar o(s) seguinte(s) protocolo(s) sem assunto: \n" + w_teste ) ');
    ShowHTML('     return false; ');
    ShowHTML('  }');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    ShowHTML('  if (!confirm("Confirma a execução da ação selecionada com os dados informados?")) return false;');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_prefixo.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    ShowHTML('  <li>MARQUE APENAS OS PROTOCOLOS A SEREM ACONDICIONADOS NA MESMA CAIXA E PASTA.');
    ShowHTML('  <li>A qualquer momento você poderá alterar os dados informados, desde que ainda não tenha feito o envio para o Arquivo.');
    ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão <i>Acondicionar</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan=2 width="1%" nowrap>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td colspan=2 align="right"><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=4>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if (count($RS)) {
      ShowHTML('          <td rowspan="2" align="center"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
    } else {
      ShowHTML('          <td rowspan="2" align="center">&nbsp;</td>');
    }
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Protocolo', 'protocolo_ordena', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Tipo', 'nm_tipo', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Assunto', 'cd_assunto', 'Form') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Detalhamento', 'descricao', 'Form') . '</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td colspan=2><b>Arq. setorial</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Prazo Guarda', 'data_limite_doc', 'Form') . '</td>');
    ShowHTML('          <td colspan=2><b>Acondicionamento</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Observação', 'observacao_setorial', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Até', 'data_setorial', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Caixa', 'nr_caixa', 'Form') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Pasta', 'pasta', 'Form') . '</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=13 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
      ShowHTML('<input type="hidden" name="w_chave[]" value="">');
      ShowHTML('<input type="hidden" name="w_assunto[]" value=""></td>');
      ShowHTML('<input type="hidden" name="w_prov[]" value=""></td>');
      ShowHTML('<input type="hidden" name="w_codigo[]" value=""></td>');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
      if (nvl($_REQUEST['p_ordena'], '') == '') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
      ShowHTML(MontaFiltro('POST'));
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        if(f($row, 'provisorio') == 'S'){
          $w_cor = $conTrBgColorLightYellow1;
        }
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center" width="1%" nowrap>');
        if (nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') {
          ShowHTML('          <input type="CHECKBOX" class="item" CHECKED name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
        } else {
          ShowHTML('          <input type="CHECKBOX" class="item" name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
        }
        ShowHTML('<input type="hidden" name="w_assunto[]" value="' . f($row, 'cd_assunto') . '">');
        ShowHTML('<input type="hidden" name="w_prov[]" value="' . f($row, 'provisorio') . '">');
        ShowHTML('<input type="hidden" name="w_codigo[]" value="' . f($row, 'protocolo') . '">');
        ShowHTML('        </td>');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        ShowHTML('        <td width="10">&nbsp;' . f($row, 'nm_tipo') . '</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'cd_assunto') . '</td>');
        ShowHTML('        <td>' . wordwrap(f($row,'descricao'),45,'<br />',true) . '</td>');
        ShowHTML('        <td>&nbsp;' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td width="1%" nowrap>&nbsp;' . formataDataEdicao(f($row, 'inicio'), 5) . '&nbsp;</td>');
        ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
        if (strlen(Nvl(f($row, 'observacao_setorial'), '-')) > 50)
          $w_titulo = substr(Nvl(f($row, 'observacao_setorial'), '-'), 0, 50) . '...'; 
        else
          $w_titulo=Nvl(f($row, 'observacao_setorial'), '-');
        ShowHTML('        <td title="' . htmlspecialchars(f($row, 'observacao_setorial')) . '">' . $w_titulo . '</td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;' . formataDataEdicao(f($row, 'data_setorial'), 5) . '</td>');
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;' . f($row, 'data_limite_doc') . '</td>');

        if (nvl(f($row, 'nr_caixa'), '') != '') {
          ShowHTML('        <td align="center" width="1%" nowrap>&nbsp;<A HREF="' . montaURL_JS($w_dir, 'relatorio.php?par=ConteudoCaixa' . '&R=' . $w_pagina . 'IMPRIMIR' . '&O=L&w_chave=' . f($row, 'sq_caixa') . '&w_espelho=s&w_formato=WORD&orientacao=PORTRAIT&&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa." target="caixa">' . f($row, 'nr_caixa') . '/' . f($row, 'sg_unid_caixa') . '</a>&nbsp;');
        } else {
          ShowHTML('        <td width="1%" nowrap align="center">&nbsp;</td>');
        }
        ShowHTML('        <td width="1%" nowrap align="center">&nbsp;' . nvl(f($row, 'pasta'), '---') . '</td>');
        ShowHTML('      </tr>');
        $i += 1;
      }
      ShowHTML('    </table>');
      ShowHTML('      <tr><td colspan="4">&nbsp;</td></tr>');
      ShowHTML('      <tr><td colspan="4"><input class="STR" type="radio" name="w_opcao" value="A"'.(($w_opcao=='A') ? ' checked' : '').'> Apagar informações para arquivo central');
      ShowHTML('      <tr><td colspan="4"><input class="STR" type="radio" name="w_opcao" value="C"'.(($w_opcao=='C') ? ' checked' : '').'> Gravar informações para arquivo central');
      ShowHTML('      <tr><td width="5%">');
      SelecaoCaixa('<u>C</u>aixa:', 'C', "Selecione a caixa para arquivamento.", $w_caixa, $w_cliente, $p_unid_posse, 'w_caixa', 'PREPARA', null);
      ShowHTML('        <td><b><U>P</U>asta:<br><INPUT ACCESSKEY="P" ' . $w_Disabled . ' class="STI" type="text" name="w_pasta" size="10" maxlength="20" value="' . $w_pasta . '"></td>');
      ShowHTML('      <tr><td colspan="4"><input class="STR" type="radio" name="w_opcao" value="D"> Gravar os dados do acondicionamento');
      ShowHTML('      <tr><td><td colspan="3"><b>A<U>c</U>ondicionamento setorial:<br><INPUT ACCESSKEY="P" class="STI" type="text" name="w_local" size="70" maxlength="80" value="' . $w_local . '">');
      ShowHTML('          <a class="ss" HREF="javascript:this.status.value;" onClick="window.open(\'' . $conRootSIW . 'mod_pa/documento.php?par=TextoSetorial&p_campo=w_local&SG=' . $SG . '&TP=' . $TP . '&p_unidade=' . $p_unid_posse. '\',\'Texto\',\'top=10,left=10,width=780,height=550,toolbar=no,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o assunto."><img src="images/Folder/Explorer.gif" border=0 align=top height=15 width=15></a>');
      ShowHTML('    <tr><td colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('   <tr><td align="center" colspan=4><hr>');
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Acondicionar">');
      ShowHTML('</FORM>');
    }
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Para pesquisa por período é obrigatório informar as datas de início e término.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas todas as guias que você tem acesso.');
    ShowHTML('  </b></font></td>');
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_posse, $w_usuario, 'p_unid_posse', 'CADPA', null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" '.$w_Disabled.' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="'.$p_sq_acao_ppa.'"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Rotina de busca de texto para acondicionamento setorial
// -------------------------------------------------------------------------
function TextoSetorial() {
    extract($GLOBALS);
    global $w_Disabled;
    $p_unidade = $_REQUEST['p_unidade'];
    $p_campo   = $_REQUEST['p_campo'];
    $p_nome    = $_REQUEST['p_nome'];

    $sql = new db_getSolicPA; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'TEXTOSET',1,
        null,null,null,null,null,null, $p_unidade,null,null,$p_nome, null, null, null, null, null, null, null,
        null, null, null, null, null, null, null, null, null, null, null);
    $RS = SortArray($RS, 'ordena', 'asc');

    Cabecalho();
    ShowHTML('<TITLE>Seleção de texto</TITLE>');
    head();
    Estrutura_CSS($w_cliente);
    ScriptOpen('JavaScript');
    ShowHTML('  function volta(l_nome, l_chave) {');
    ShowHTML('     opener.document.Form.'.$p_campo.'.value=l_chave;');
    ShowHTML('     window.close();');
    ShowHTML('     opener.focus();');
    ShowHTML('   }');
    ValidateOpen('Validacao');
    Validate('p_nome','Nome','1','','3','100','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</head>');
    BodyOpen('onLoad=\'document.Form.p_nome.focus();\'');
    Estrutura_Texto_Abre();
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
    ShowHTML('<INPUT type="hidden" name="p_campo" value="'.$p_campo.'">');
    ShowHTML('<INPUT type="hidden" name="p_unidade" value="'.$p_unidade.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="justify"><b><ul>Instruções</b>:');
    ShowHTML('  <li>Na relação exibida, selecione o texto desejado clicando sobre o link <i>Selecionar</i>.');
    ShowHTML('  <li>Se desejar filtrar a lista, informe uma parte do texto e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.');
    ShowHTML('  </ul>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=2><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_nome" size="50" maxlength="100" value="'.$p_nome.'">');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</form>');
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" border=0>');
    if (count($RS)==0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
        ShowHTML('        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('            <td><b>Textos da unidade</font></td>');
        ShowHTML('            <td class="remover" ><b>Operações</font></td>');
        ShowHTML('          </tr>');
        foreach($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('            <td>'.f($row,'texto').'</td>');
            ShowHTML('            <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'texto').'\', \''.f($row,'texto').'\');">Selecionar</a>');
        }
        ShowHTML('        </table></tr>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
    }
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
    Estrutura_Texto_Fecha();
}

// =========================================================================
// Classifica protocolos
// -------------------------------------------------------------------------
function Classificacao() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($w_troca > '') {
    // Se for recarga da página
    $w_chave            = $_REQUEST['w_chave'];
    $w_retorno_limite   = $_REQUEST['w_retorno_limite'];
    $w_interno          = $_REQUEST['w_interno'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_pessoa_destino   = $_REQUEST['w_pessoa_destino'];
    $w_unidade_externa  = $_REQUEST['w_unidade_externa'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_protocolo        = $_REQUEST['w_protocolo'];
  }

  // Verifica se a unidade de lotação do usuário está cadastrada na relação de unidades do módulo
  $sql = new db_getUorgList;
  $RS_Prot = $sql->getInstanceOf($dbms, $w_cliente, null, 'MOD_PA_PROT', null, null, $w_ano);
  foreach ($RS_Prot as $row) { $RS_Prot = $row; break; }

  if ($O == 'L') {
    if (nvl($p_classif, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_classif, null, null, null, null, null, null, null, null, 'REGISTROS');
      foreach ($RS_Assunto as $row) {
        $RS_Assunto = $row;
        break;
      }
      $p_sq_acao_ppa = f($row,'codigo');
    }

    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, $SG, $p_chave, $p_chave_aux,$p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia,$p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, $p_solicitante, 
                  $p_unidade, $p_proponente,$p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'inicio', 'asc', 'ano', 'asc', 'prefixo', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'inicio', 'asc', 'ano', 'asc', 'prefixo', 'asc', 'protocolo', 'asc');
    }
    $w_existe = count($RS);

    if (count($w_chave) > 0) {
      $i = 0;
      foreach ($w_chave as $k => $v) {
        foreach ($RS as $row) {
          if ($w_chave[$i] == f($row, 'sq_siw_solicitacao')) {
            $w_marcado[f($row, 'sq_siw_solicitacao')] = 'ok';
            break;
          }
        }
        $i += 1;
      }
      reset($RS);
    }
  }
  Cabecalho();
  head();
  if ($O == 'P') {
    ScriptOpen('JavaScript');
    FormataProtocolo();
    FormataData();
    SaltaCampo();
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
    Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
    Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
    Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
    ShowHTML('     theForm.p_ini.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
    Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
//    Validate('p_sq_acao_ppa', 'Código do assunto', '', '', '1', '10', '1', '1');
    Validate('p_assunto', 'Detalhamento do assunto/Despacho', '', '', '4', '90', '1', '1');
    Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  } elseif ($w_existe) {
    ScriptOpen('JavaScript');
    ShowHTML('  $(document).ready(function() {');
    ShowHTML('    $("#marca_todos").click(function() {');
    ShowHTML('      var checked = this.checked;');
    ShowHTML('      $(".item").each(function() {');
    ShowHTML('        this.checked = checked;');
    ShowHTML('      });');
    ShowHTML('    });');
    ShowHTML('  });');
    FormataProtocolo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
    ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
    ShowHTML('       w_erro=false; ');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve informar pelo menos um protocolo!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    Validate('w_assunto', 'Classificação', 'HIDDEN', 1, 1, 18, '', '0123456789');
    Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
    ShowHTML('  theForm.Botao.disabled=true;');
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.p_prefixo.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    ShowHTML('  <li>INFORME O ASSUNTO SOMENTE DOS PROTOCOLOS DESEJADOS.');
    ShowHTML('  <li>A qualquer momento você poderá alterar o assunto informado para um protocolo, informando seu número na tela de filtragem.');
    ShowHTML('  <li>Informe sua assinatura eletrônica e clique sobre o botão <i>Classificar</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td colspan=2>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right"><b>Registros: ' . count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    if (count($RS)) {
      ShowHTML('          <td rowspan="2" align="center" width="1%"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></td>');
    } else {
      ShowHTML('          <td rowspan="2" align="center" width="1%">&nbsp;</td>');
    }
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Resumo', 'descricao') . '</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Tipo', 'nm_tipo') . '</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Protocolo', 'protocolo') . '</td>');
    ShowHTML('          <td rowspan=2 width="1%" nowrap><b>' . linkOrdena('Assunto', 'cd_assunto') . '</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . linkOrdena('Espécie', 'nm_especie') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Nº', 'numero_original') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Data', 'inicio') . '</td>');
    ShowHTML('          <td><b>' . linkOrdena('Procedência', 'nm_origem_doc') . '</td>');
    ShowHTML('        </tr>');
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<input type="hidden" name="w_chave[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML(montaFiltro('POST'));
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=12 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
        $w_unidade = f($row, 'sq_unidade_posse');
        ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
        ShowHTML('        <td align="center" width="1%" nowrap>');
        if (nvl($w_marcado[f($row, 'sq_siw_solicitacao')], '') != '') {
          ShowHTML('          <input class="item" type="CHECKBOX" CHECKED name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
        } else {
          ShowHTML('          <input class="item" type="CHECKBOX" name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
        }
        ShowHTML('        </td>');
        if (strlen(Nvl(f($row, 'descricao'), '-')) > 500)
          $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 500) . '...'; else
          $w_titulo=Nvl(f($row, 'descricao'), '-');
        if (f($row, 'sg_tramite') == 'CA')
          ShowHTML('        <td title="' . ((strlen(Nvl(f($row, 'descricao'), '-')) > 500) ? htmlspecialchars(f($row, 'descricao')) : '') . '"><strike>' . $w_titulo . '</strike></td>');
        else
          ShowHTML('        <td title="' . ((strlen(Nvl(f($row, 'descricao'), '-')) > 500) ? htmlspecialchars(f($row, 'descricao')) : '') . '">' . $w_titulo . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_tipo') . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
        ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
        ShowHTML('        <td>' . formataDataEdicao(f($row, 'inicio'), 5) . '</td>');
        ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
        ShowHTML('        <td align="center" width="1%" nowrap><A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
        ShowHTML('        <td>' . f($row, 'cd_assunto') . '</td>');
        ShowHTML('      </tr>');
        $i += 1;
      }
    }
    ShowHTML('    </table>');
    ShowHTML('      <tr><td colspan="3">&nbsp;</td></tr>');
    ShowHTML('      <tr><td colspan="3"  bgcolor="#f0f0f0" align=justify><font size="2"><b>ACONDICIONAMENTO</b></font></td></tr>');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $w_assunto, null, 'w_assunto', 'FOLHA', null);
    ShowHTML('    <tr><td colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('   <tr><td align="center" colspan=3><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Classificar">');
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>U</U>nidade que detém a posse do protocolo:', 'U', 'Selecione a unidade de posse.', $p_unid_posse, null, 'p_unid_posse', 'MOD_PA', null);
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="' . $p_sq_acao_ppa . '"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Acusa o recebimento de guias de tramitação
// -------------------------------------------------------------------------
function Recebimento() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_unid_autua = $_REQUEST['w_unid_autua'];
  $w_unid_prot  = $_REQUEST['w_unid_prot'];
  $w_nu_guia    = $_REQUEST['w_nu_guia'];
  $w_ano_guia   = $_REQUEST['w_ano_guia'];

  $w_observacao = $_REQUEST['w_observacao'];

  if ($O == 'L') {
    if (nvl($p_classif, '') != '') {
      $sql = new db_getAssunto_PA;
      $RS_Assunto = $sql->getInstanceOf($dbms, $w_cliente, $p_classif, null, null, null, null, null, null, null, null, 'REGISTROS');
      foreach ($RS_Assunto as $row) {
        $RS_Assunto = $row;
        break;
      }
      $p_sq_acao_ppa = f($row, 'codigo');
    }

    // Recupera todos os registros para a listagem
    $sql = new db_getProtocolo;
    $RS = $sql->getInstanceOf($dbms, $P2, $w_usuario, $SG, $p_chave, $p_chave_aux,$p_prefixo, $p_numero, $p_ano, 
                  $p_unid_autua, $p_unid_posse, $w_nu_guia, $w_ano_guia,$p_ini, $p_fim, 2, $p_tipo_despacho, $p_empenho, $p_solicitante, 
                  $p_unidade, $p_proponente,$p_sq_acao_ppa, $p_assunto, $p_processo);
    if (Nvl($p_ordena, '') > '') {
      $lista = explode(',', str_replace(' ', ',', $p_ordena));
      $RS = SortArray($RS, $lista[0], $lista[1], 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    } else {
      $RS = SortArray($RS, 'sg_unid_dest', 'asc', 'sg_unid_origem', 'asc', 'ano_guia', 'desc', 'nu_guia', 'asc', 'protocolo', 'asc');
    }
  } elseif ($O == 'R') {
    // Recupera os protocolos da guia
    $sql = new db_getProtocolo;
    $RS_Dados = $sql->getInstanceOf($dbms, $P2, $w_usuario, $SG, null, null,
                    null, null, null, null, $p_unid_posse, $w_nu_guia, $w_ano_guia, null, null, 2, null, null, null,
                    null, null, null, null, null);

    if (count($RS_Dados)==0) {
      $erro = true; // Outro usuário já recebeu a guia
    } else {
      $erro = false;
      foreach ($RS_Dados as $row) {
        $RS_Dados = $row;
        break;
      }
      $w_interno = f($RS_Dados, 'interno');
    }
  }

  Cabecalho();
  head();
  if ($O == 'R' || $O == 'S' || $O == 'T' || $O == 'U' || $O == 'P') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($O == 'R' || $O == 'S' || $O == 'T' || $O == 'U') {
      if (!$erro) {
        if ($w_interno == 'N' && ($O == 'R' || $O == 'T')) {
          Validate('w_observacao', 'Observações sobre o envio externo', '1', '1', '5', '2000', '1', '1');
        }
        if ($O == 'S' || $O == 'U') {
          Validate('w_observacao', 'Observações sobre a recusa', '1', '', '5', '2000', '1', '1');
        }
        Validate('w_assinatura', 'Assinatura Eletrônica', '1', '1', '6', '30', '1', '1');
        ShowHTML('  theForm.Botao[0].disabled=true;');
        ShowHTML('  theForm.Botao[1].disabled=true;');
      } else {
        ShowHTML('  theForm.Botao.disabled=true;');
      }
    } else {
      Validate('w_nu_guia', 'Número da guia', '1', '', '1', '10', '0', '0123456789');
      Validate('w_ano_guia', 'Ano da guia', '1', '', '4', '4', '0', '0123456789');
      ShowHTML('  if ((theForm.w_nu_guia.value!="" && theForm.w_ano_guia.value=="") || (theForm.w_nu_guia.value=="" && theForm.w_ano_guia.value!="")) {');
      ShowHTML('     alert ("Se desejar informar a quia, informe o número e o ano!");');
      ShowHTML('     theForm.w_nu_guia.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('p_prefixo', 'Prefixo', '1', '', '5', '5', '', '0123456789');
      Validate('p_numero', 'Número', '1', '', '1', '6', '', '0123456789');
      Validate('p_ano', 'Ano', '1', '', '4', '4', '', '0123456789');
      ShowHTML('  if ((theForm.p_numero.value!="" && theForm.p_ano.value=="") || (theForm.p_numero.value=="" && theForm.p_ano.value!="")) {');
      ShowHTML('     alert ("Para pesquisa pelo protocolo informe pelo menos o número e o ano!");');
      ShowHTML('     theForm.p_ano.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('p_unid_posse', 'Unidade de posse', 'SELECT', '', '1', '18', '', '0123456789');
      Validate('p_proponente', 'Origem externa', '', '', '2', '90', '1', '');
      //Validate('p_sq_acao_ppa', 'Código do assunto', '', '', '1', '10', '1', '1');
      Validate('p_assunto', 'Detalhamento do assunto/Despacho', '', '', '4', '90', '1', '1');
      Validate('p_processo', 'Interessado', '', '', '2', '90', '1', '1');
      Validate('p_ini', 'Início', 'DATA', '', '10', '10', '', '0123456789/');
      Validate('p_fim', 'Término', 'DATA', '', '10', '10', '', '0123456789/');
      ShowHTML('  if ((theForm.p_ini.value != \'\' && theForm.p_fim.value == \'\') || (theForm.p_ini.value == \'\' && theForm.p_fim.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas ou nenhuma delas!\');');
      ShowHTML('     theForm.p_ini.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_ini', 'Início', '<=', 'p_fim', 'Término');
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad=\'document.Form.' . $w_troca . '.focus()\';');
  } elseif (!$erro && ($O == 'R' || $O == 'S' || $O == 'T' || $O == 'U')) {
    if ($w_interno == 'N' || $O == 'S' || $O == 'U')
      BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
    else
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif ($O == 'P') {
    BodyOpen('onLoad=\'document.Form.w_nu_guia.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td colspan=3 bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('  <li>Selecione a guia desejada para recebimento, clicando sobre a operação <i>Receber</i>.');
    ShowHTML('  </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td>');
    if ($w_nu_guia || $w_ano_guia || $p_unid_posse) {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=P&P1=' . $P1 . '&P2=' . $P2 . '&P3=1&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '"><u>F</u>iltrar (Inativo)</a>');
    }
    ShowHTML('    <td align="right">');
    ShowHTML('   '.(($w_tipo!='WORD') ? exportaOffice() : '').' <b>Protocolos: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Destino', 'sg_unid_dest') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Última Procedência', 'sg_unid_origem') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Guia', 'guia_tramite') . '</td>');
    ShowHTML('          <td rowspan=2><b>' . linkOrdena('Despacho', 'nm_despacho') . '</td>');
    ShowHTML('          <td rowspan=2><b>Envio</td>');
    ShowHTML('          <td rowspan=2><b>Protocolo</td>');
    ShowHTML('          <td colspan=4><b>Documento original</td>');
    ShowHTML('          <td class="remover" rowspan=2><b>Operações</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('          <td><b>' . LinkOrdena('Espécie', 'nm_especie') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Nº', 'numero_original') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Procedência', 'nm_origem_doc') . '</td>');
    ShowHTML('          <td><b>' . LinkOrdena('Assunto', 'descricao') . '</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan="11" align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3 - 1) * $P4), $P4);
      $w_atual = '';
      $w_outra_unidade = false;
      $w_caixa = '';
      foreach ($RS1 as $row) {
        if ($w_atual == '' || ($w_atual != f($row, 'guia_tramite'))) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td title="' . f($row, 'nm_unid_dest') . '">' . f($row, 'sg_unid_dest') . '</td>');
          ShowHTML('        <td title="' . f($row, 'nm_unid_origem') . '">' . f($row, 'sg_unid_origem') . '</td>');
          ShowHTML('        <td>' . f($row, 'guia_tramite') . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_despacho') . '</td>');
          ShowHTML('        <td align="center">' . formataDataEdicao(f($row, 'phpdt_envio'), 6) . '</td>');
          if (nvl(f($row,'sq_caixa'),'')=='') {
            ShowHTML('        <td align="center" nowrap><A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
            ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
            ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
            ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
            if (strlen(Nvl(f($row, 'descricao'), '-')) > 50)
              $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 50) . '...'; else
              $w_titulo=Nvl(f($row, 'descricao'), '-');
            if (f($row, 'sg_tramite') == 'CA')
              ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '"><strike>' . $w_titulo . '</strike></td>');
            else
              ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '">' . $w_titulo . '</td>');
          } else {
            ShowHTML('        <td colspan=5>Caixa ' . f($row, 'numero_caixa') . '</td>');
          }
          ShowHTML('        <td class="remover" align="top">');
          if (nvl(f($row, 'despacho_arqcentral'), '') == '') {
            ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=R&w_unid_autua=' . f($row, 'unidade_origem') . '&w_unid_prot=' . f($row, 'unidade_autuacao') . '&w_nu_guia=' . f($row, 'nu_guia') . '&w_ano_guia=' . f($row, 'ano_guia') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . montaFiltro('GET') . '"' . (($w_outra_unidade) ? ' onClick="return(confirm(\'O destino da guia é uma unidade diferente da sua!\nCONFIRMA O RECEBIMENTO?\'));"' : '') . '>Receber</A>&nbsp');
          } else {
            ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=T&w_unid_autua=' . f($row, 'unidade_origem') . '&w_unid_prot=' . f($row, 'unidade_autuacao') . '&w_nu_guia=' . f($row, 'nu_guia') . '&w_ano_guia=' . f($row, 'ano_guia') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . montaFiltro('GET') . '"' . (($w_outra_unidade) ? ' onClick="return(confirm(\'O destino da guia é uma unidade diferente da sua!\nCONFIRMA O RECEBIMENTO?\'));"' : '') . '>Receber</A>&nbsp');
          }
          if (!$w_outra_unidade) {
            if (nvl(f($row, 'despacho_arqcentral'), '') == '') {
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=S&w_unid_autua=' . f($row, 'unidade_origem') . '&w_unid_prot=' . f($row, 'unidade_autuacao') . '&w_nu_guia=' . f($row, 'nu_guia') . '&w_ano_guia=' . f($row, 'ano_guia') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . montaFiltro('GET') . '">Recusar</A>&nbsp');
            } else {
              ShowHTML('          <A class="HL" HREF="' . $w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=U&w_unid_autua=' . f($row, 'unidade_origem') . '&w_unid_prot=' . f($row, 'unidade_autuacao') . '&w_nu_guia=' . f($row, 'nu_guia') . '&w_ano_guia=' . f($row, 'ano_guia') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . montaFiltro('GET') . '">Recusar</A>&nbsp');
            }
          }
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
          $w_atual = f($row, 'guia_tramite');
        } elseif (f($row,'sq_caixa')=='') {
          if ($w_outra_unidade) {
            ShowHTML('      <tr bgcolor="' . $conTrBgColorLightRed1 . '" valign="top">');
          } else {
            ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          }
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('        <td align="center">' . formataDataEdicao(f($row, 'phpdt_envio'), 6) . '</td>');
          ShowHTML('        <td align="center" nowrap><A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" target="visualdoc" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
          ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
          ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_origem_doc') . '</td>');
          if (strlen(Nvl(f($row, 'descricao'), '-')) > 50) {
            $w_titulo = substr(Nvl(f($row, 'descricao'), '-'), 0, 50) . '...'; 
          } else {
            $w_titulo=Nvl(f($row, 'descricao'), '-');
          }
          if (f($row, 'sg_tramite') == 'CA') {
            ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '"><strike>' . $w_titulo . '</strike></td>');
          } else {
            ShowHTML('        <td width="50%" title="' . htmlspecialchars(f($row, 'descricao')) . '">' . $w_titulo . '</td>');
          }
          ShowHTML('        <td>&nbsp;</td>');
          ShowHTML('      </tr>');
        }
      }
    }
    if ($w_outra_unidade) ShowHTML('      <tr><td colspan="11"><b>ATENÇÃO: Linha na cor vermelha indica que o protocolo está de posse de unidade diferente da sua!');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir . $w_pagina . $par . '&R=' . $w_pagina . $par . '&O=' . $O . '&P1=' . $P1 . '&P2=' . $P2 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET'), ceil(count($RS) / $P4), $P3, $P4, count($RS));
    ShowHTML('</tr>');
  } elseif ($O == 'R' || $O == 'S') {
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($erro) {
      ShowHTML('<HR>');
      AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
      ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="' . $w_unid_autua . '">');
      ShowHTML('<INPUT type="hidden" name="w_unid_prot" value="' . $w_unid_prot . '">');
      ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="' . $w_nu_guia . '">');
      ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="' . $w_ano_guia . '">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML(montaFiltro('POST'));
      ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('  ATENÇÃO:<ul>');
      ShowHTML('  <li>Outro usuário recebeu esta guia. Clique no botão "Abandonar" para voltar à tela de filtragem de recebimentos.');
      ShowHTML('  </ul></b></font></td>');
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
      ShowHTML('  <table width="97%" border="0">');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('</FORM>');
    } else {
      // Chama a rotina de visualização dos protocolos da guia
      ShowHTML(VisualGR($p_unid_posse, $w_nu_guia, $w_ano_guia, f($RS_Menu, 'sq_menu'), 'TELA'));
      ShowHTML('<HR>');
      AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
      ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="' . $w_unid_autua . '">');
      ShowHTML('<INPUT type="hidden" name="w_unid_prot" value="' . $w_unid_prot . '">');
      ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="' . $w_nu_guia . '">');
      ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="' . $w_ano_guia . '">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML(montaFiltro('POST'));
      ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('  ATENÇÃO:<ul>');
      if ($O == 'R') {
        ShowHTML('  <li>Verifique cada um dos protocolos antes de assinar o recebimento, pois não será possível reverter esta ação.');
        ShowHTML('  <li>O recebimento da guia implica no recebimento de todos os seus protocolos, não sendo possível o recebimento parcial.');
      } else {
        ShowHTML('  <li>Antes de recusar o recebimento, verifique com atenção se essa é realmente sua intenção.');
        ShowHTML('  <li>Não será possível reverter esta ação.');
      }
      ShowHTML('  </ul></b></font></td>');
      ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
      ShowHTML('  <table width="97%" border="0">');
      if ($w_interno == 'N')
        ShowHTML('      <tr><td colspan="4" title="Informe os dados do envio do protocolo."><b><u>O</u>bservação sobre o envio externo:</b><br><textarea ' . $w_Disabled . ' accesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
      if ($O == 'S') {
        ShowHTML('    <tr><td colspan=5 align="center"><hr>');
        ShowHTML('      <tr><td colspan="5" title="OPCIONAL. Se desejar, registre observações sobre a recusa."><b><u>O</u>bservações sobre a recusa:</b><br><textarea ccesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
      }
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      if ($O == 'R') {
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Receber">');
      } else {
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Recusar">');
      }
    }
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O == 'T' || $O == 'U') {
    ShowHTML('<tr><td align="center" colspan=3>');
    // Chama a rotina de visualização dos protocolos da guia
    ShowHTML(VisualGT($p_unid_posse, $w_nu_guia, $w_ano_guia, f($RS_Menu, 'sq_menu'), 'TELA'));

    ShowHTML('<HR>');
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML('<INPUT type="hidden" name="w_unid_autua" value="' . $w_unid_autua . '">');
    ShowHTML('<INPUT type="hidden" name="w_nu_guia" value="' . $w_nu_guia . '">');
    ShowHTML('<INPUT type="hidden" name="w_ano_guia" value="' . $w_ano_guia . '">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML(montaFiltro('POST'));
    ShowHTML('<tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
    ShowHTML('  ATENÇÃO:<ul>');
    if ($O == 'T') {
      ShowHTML('  <li>Verifique cada uma das caixas, pastas e protocolos antes de assinar o recebimento, pois não será possível reverter esta ação.');
      ShowHTML('  <li>O recebimento da guia implica no recebimento de todas as suas caixas, pastas e protocolos, não sendo possível o recebimento parcial.');
    } else {
      ShowHTML('  <li>Antes de recusar o recebimento, verifique com atenção se essa é realmente sua intenção.');
      ShowHTML('  <li>Não será possível reverter esta ação.');
    }
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    if ($O == 'U') {
      ShowHTML('    <tr><td colspan=5 align="center"><hr>');
      ShowHTML('      <tr><td colspan="5" title="OPCIONAL. Se desejar, registre observações sobre a recusa do envio."><b><u>O</u>bservações sobre a recusa:</b><br><textarea ccesskey="O" name="w_observacao" class="STI" ROWS=5 cols=75>' . $w_observacao . '</TEXTAREA></td>');
    }
    ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    if ($O == 'T') {
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Receber">');
    } else {
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Recusar">');
    }
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';" name="Botao" value="Abandonar">');
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<tr><td bgcolor="' . $conTrBgColorLightBlue2 . '"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  <B>ORIENTAÇÃO:</B><ul>');
    ShowHTML('  <li>Informe quaisquer critérios de busca e clique sobre o botão <i>Aplicar filtro</i>.');
    ShowHTML('  <li>Clicando sobre o botao <i>Aplicar filtro</i> sem informar nenhum critério de busca, serão exibidas apenas as guias que você tem acesso.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr><td><br><font size="2"><b>FILTRAGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    ShowHTML('      <tr><td><b>Número e ano da guia de remessa:<br><INPUT class="STI" type="text" name="w_nu_guia" size="10" maxlength="10" style="text-align:right;" value="' . $w_nu_guia . '">/<INPUT class="STI" type="text" name="w_ano_guia" size="4" maxlength="4" value="' . $w_ano_guia . '"></td>');
    ShowHTML('      <tr valign="top">');
//    SelecaoUnidade('<U>U</U>nidade emissora da guia:', 'U', 'Selecione a unidade emissora da guia.', $w_unid_autua, null, 'w_unid_autua', 'MOD_PA_PROT', null);
    SelecaoUnidade('<U>U</U>nidade de posse do protocolo (somente unidades que o usuário tem acesso):', 'U', 'Selecione a unidade de posse.', nvl($p_unid_posse,$_SESSION['LOTACAO']), $w_usuario, 'p_unid_posse', 'CADPA', null,2);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_prefixo" size="6" maxlength="5" value="' . $p_prefixo . '">.<INPUT class="STI" type="text" name="p_numero" style="text-align:right;" size="7" maxlength="6" value="' . $p_numero . '">/<INPUT class="STI" type="text" name="p_ano" size="4" maxlength="4" value="' . $p_ano . '"></td>');
    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="p_empenho" size="10" maxlength="30" value="' . $p_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $p_solicitante, null, 'p_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="p_ini" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_ini . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_ini') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="p_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $p_unidade, null, 'p_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="' . $p_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
//    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_sq_acao_ppa" size="10" maxlength="10" value="' . $p_sq_acao_ppa . '"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="p_assunto" size="40" maxlength="30" value="' . $p_assunto . '"></td>');
//    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="p_processo" size="30" maxlength="30" value="' . $p_processo . '"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoAssuntoRadio('C<u>l</u>assificação:', 'L', 'Clique na lupa para selecionar a classificação do documento.', $p_classif, null, 'p_classif', 'FOLHA', null, '2');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  }
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Rotina de busca de protocolos
// -------------------------------------------------------------------------
function BuscaProtocolo() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_ano = $_REQUEST['w_ano'];
  $w_cliente = $_REQUEST['w_cliente'];
  $chaveAux = $_REQUEST['chaveAux'];
  $restricao = $_REQUEST['restricao'];
  $campo = $_REQUEST['campo'];

  $l_exibe = upper($_REQUEST['exibe']);
  $l_tipo = upper($_REQUEST['l_tipo']);
  $l_chave_pai = upper($_REQUEST['l_chave_pai']);
  $l_atividade = upper($_REQUEST['l_atividade']);
  $l_graf = upper($_REQUEST['l_graf']);
  $l_ativo = upper($_REQUEST['l_ativo']);
  $l_solicitante = upper($_REQUEST['l_solicitante']);
  $l_prioridade = upper($_REQUEST['l_prioridade']);
  $l_unidade = upper($_REQUEST['l_unidade']);
  $l_proponente = upper($_REQUEST['l_proponente']);
  $l_ordena = lower($_REQUEST['l_ordena']);
  $l_ini_i = upper($_REQUEST['l_ini_i']);
  $l_ini_f = upper($_REQUEST['l_ini_f']);
  $l_fim_i = upper($_REQUEST['l_fim_i']);
  $l_fim_f = upper($_REQUEST['l_fim_f']);
  $l_atraso = upper($_REQUEST['l_atraso']);
  $l_chave = upper($_REQUEST['l_chave']);
  $l_assunto = upper($_REQUEST['l_assunto']);
  $l_pais = upper($_REQUEST['l_pais']);
  $l_regiao = upper($_REQUEST['l_regiao']);
  $l_uf = upper($_REQUEST['l_uf']);
  $l_cidade = upper($_REQUEST['l_cidade']);
  $l_usu_resp = upper($_REQUEST['l_usu_resp']);
  $l_uorg_resp = upper($_REQUEST['l_uorg_resp']);
  $l_processo = upper($_REQUEST['l_processo']);
  $l_prazo = upper($_REQUEST['l_prazo']);
  $l_fase = explodeArray($_REQUEST['l_fase']);
  $l_sqcc = upper($_REQUEST['l_sqcc']);
  $l_agrega = upper($_REQUEST['l_agrega']);
  $l_tamanho = upper($_REQUEST['l_tamanho']);
  $l_sq_menu_relac = upper($_REQUEST['l_sq_menu_relac']);
  $l_sq_acao_ppa = upper($_REQUEST['l_sq_acao_ppa']);
  $l_chave_pai = upper($_REQUEST['l_chave_pai']);
  $l_empenho = lower($_REQUEST['l_empenho']);
  
  $l_filtro = false;
  if (nvl($l_ini_i.$l_ini_f.$l_fim_i.$l_fim_f.$l_solicitante.$l_unidade.$l_prioridade.$l_ativo.$l_proponente.$l_chave.$l_assunto.$l_pais.$l_regiao.$l_uf.$l_cidade.$l_usu_resp.$l_uorg_resp.$l_palavra.$l_prazo.$l_sqcc.$l_chave_pai.$l_atividade.$l_sq_acao_ppa.$l_empenho.$l_processo,'')!='') $l_filtro = true;

  // Se juntada, busca somente processos
  if ($restricao == 'JUNTADA') $l_uf = 'S';

  if ($l_filtro) {
    $sql = new db_getSolicList;
    $RS1 = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, substr('PAD'.$restricao,0,10), 5,
                  $l_ini_i, $l_ini_f, $l_fim_i, $l_fim_f, $l_atraso, $l_solicitante,
                  $l_unidade, $l_prioridade, $l_ativo, $l_proponente,
                  $l_chave, $l_assunto, $l_pais, $l_regiao, $l_uf, $l_cidade, $l_usu_resp,
                  $l_uorg_resp, $l_palavra, $l_prazo, $l_fase, $l_sqcc, $l_chave_pai, $l_atividade, $l_sq_acao_ppa, null,
                  $l_empenho, $l_processo);
    $RS1 = SortArray($RS1, 'protocolo', 'asc');
  }
  
  Cabecalho();
  head();
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('<TITLE>Seleção de protocolo</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_protocolo, l_chave) {');
  ShowHTML("     opener.document.Form." . $campo . "_nm.value=l_protocolo.replace('\'','\"');");
  ShowHTML('     opener.document.Form.' . $campo . '.value=l_chave;');
  ShowHTML('     opener.document.Form.' . $campo . '_nm.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  if ((!is_array($RS1)) || count($RS1) > 50 || $l_exibe > '') {
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    Validate('l_pais', 'Prefixo', '', '', '2', '6', '', '0123456789');
    Validate('l_regiao', 'Número', '', '', '1', '7', '', '0123456789');
    Validate('l_cidade', 'Ano', '', '', '4', '4', '', '0123456789');
    if (nvl($chaveAux,'')!='') {
      Validate('l_uorg_resp', 'Unidade de posse', 'SELECT', '1', '1', '18', '', '1');
    }
    Validate('l_proponente', 'Origem externa', '', '', '2', '90', '1', '');
    Validate('l_sq_acao_ppa', 'Código do assunto', '', '', '1', '10', '1', '1');
    Validate('l_assunto', 'Detalhamento do assunto/Despacho', '', '', '2', '90', '1', '1');
    Validate('l_processo', 'Palavras-chave', '', '', '2', '90', '1', '1');
    Validate('l_ini_i', 'Recebimento inicial', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('l_ini_f', 'Recebimento final', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.l_ini_i.value != \'\' && theForm.l_ini_f.value == \'\') || (theForm.l_ini_i.value == \'\' && theForm.l_ini_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
    ShowHTML('     theForm.l_ini_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  var i; ');
    ShowHTML('  var w_erro=true; ');
    ShowHTML('  for (i=0; i < theForm["l_fase[]"].length; i++) {');
    ShowHTML('    if (theForm["l_fase[]"][i].checked) w_erro=false;');
    ShowHTML('  }');
    ShowHTML('  if (w_erro) {');
    ShowHTML('    alert("Você deve informar pelo menos uma fase!"); ');
    ShowHTML('    return false;');
    ShowHTML('  }');
    CompData('l_ini_i', 'Recebimento inicial', '<=', 'l_ini_f', 'Recebimento final');
    Validate('l_fim_i', 'Conclusão inicial', 'DATA', '', '10', '10', '', '0123456789/');
    Validate('l_fim_f', 'Conclusão final', 'DATA', '', '10', '10', '', '0123456789/');
    ShowHTML('  if ((theForm.l_fim_i.value != \'\' && theForm.l_fim_f.value == \'\') || (theForm.l_fim_i.value == \'\' && theForm.l_fim_f.value != \'\')) {');
    ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
    ShowHTML('     theForm.l_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    CompData('l_fim_i', 'Conclusão inicial', '<=', 'l_fim_f', 'Conclusão final');
    ShowHTML('  theForm.exibe.value=true;');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
  }
  ScriptClose();
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
  ShowHTML('    <table width="100%" border="0">');
  if ((!is_Array($RS1)) || count($RS1) > 50 || $l_exibe > '') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this))', null, $P1, $P2, $P3, $P4, $TP, $SG, null, null);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="' . $w_cliente . '">');
    ShowHTML('<INPUT type="hidden" name="chaveAux" value="' . $chaveAux . '">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="' . $restricao . '">');
    ShowHTML('<INPUT type="hidden" name="campo" value="' . $campo . '">');
    ShowHTML('<INPUT type="hidden" name="exibe" value="">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td colspan=2><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="l_pais" size="6" maxlength="5" value="' . $l_pais . '">.<INPUT class="STI" type="text" name="l_regiao" style="text-align:right;" size="7" maxlength="6" value="' . $l_regiao . '">/<INPUT class="STI" type="text" name="l_cidade" size="4" maxlength="4" value="' . $l_cidade . '"></td>');
    if (nvl($restricao,'') != 'JUNTADA') { 
      ShowHTML('          <td><b>Buscar por?</b><br>');
      ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="l_uf" value="S" '.(($l_uf == 'S') ? 'checked': '').'> Processo');
      ShowHTML('              <input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="l_uf" value="N" '.(($l_uf == 'N') ? 'checked': '').'> Documento');
      ShowHTML('              <input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="l_uf" value="" '.((nvl($l_uf,'')=='') ? 'checked': '').'> Ambos');
    }

    ShowHTML('      <tr valign="top">');
    if (nvl($chaveAux,'')!='') {
      SelecaoUnidade('<U>U</U>nidade de posse:', 'U', 'Selecione a Unidade de posse do protocolo na relação.', nvl($l_uorg_resp,$chaveAux), $w_usuario, 'l_uorg_resp', 'CADPA', null);  
    } else {
      SelecaoUnidade('<U>U</U>nidade de posse:', 'U', 'Selecione a Unidade de posse do protocolo na relação.', $l_uorg_resp, null, 'l_uorg_resp', null, null);
    }
    selecaoTipoDespacho('Último des<u>p</u>acho:', 'P', 'Selecione o despacho desejado.', $w_cliente, $l_prioridade, null, 'l_prioridade', 'SELECAO', null);

    ShowHTML('      <tr valign="top"><td colspan="2"><b>Documento original:</b><table width="100%" cellpadding=0 cellspacing=3 style="border: 1px solid rgb(0,0,0);"><tr><td width="50%"><td></tr><tr valign="top">');
    ShowHTML('          <td><b>Número:<br><INPUT class="STI" type="text" name="l_empenho" size="10" maxlength="30" value="' . $l_empenho . '">');
    selecaoEspecieDocumento('<u>E</u>spécie documental:', 'E', 'Selecione a espécie do documento.', $l_solicitante, null, 'l_solicitante', null, null);
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><u>C</u>riado/Recebido entre:</b><br><input ' . $w_Disabled . ' accesskey="C" type="text" name="l_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $l_ini_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'l_ini_i') . ' e <input ' . $w_Disabled . ' accesskey="C" type="text" name="l_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $l_ini_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'l_ini_f') . '</td>');
    ShowHTML('          <td><b>Limi<u>t</u>e para tramitação entre:</b><br><input ' . $w_Disabled . ' accesskey="T" type="text" name="l_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $l_fim_i . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'l_fim_i') . ' e <input ' . $w_Disabled . ' accesskey="T" type="text" name="l_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="' . $l_fim_f . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'l_fim_f') . '</td>');
    ShowHTML('      <tr valign="top">');
    SelecaoUnidade('<U>O</U>rigem interna:', 'O', null, $l_unidade, null, 'l_unidade', null, null);
    ShowHTML('          <td><b>Orig<U>e</U>m externa:<br><INPUT ACCESSKEY="E" ' . $w_Disabled . ' class="STI" type="text" name="l_proponente" size="25" maxlength="90" value="' . $l_proponente . '"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Código do <U>a</U>ssunto:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="l_sq_acao_ppa" size="10" maxlength="10" value="' . $l_sq_acao_ppa . '"></td>');
    ShowHTML('          <td><b>Detalhamento do <U>a</U>ssunto/Despacho:<br><INPUT ACCESSKEY="A" ' . $w_Disabled . ' class="STI" type="text" name="l_assunto" size="40" maxlength="30" value="' . $l_assunto . '"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>I</U>nteressado:<br><INPUT ACCESSKEY="I" ' . $w_Disabled . ' class="STI" type="text" name="l_processo" size="30" maxlength="30" value="' . $l_processo . '"></td>');
    ShowHTML('        </tr></table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Apenas protocolos com data limite excedida?</b><br>');
    if ($l_atraso == 'S') {
      ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="l_atraso" value="S" checked> Sim <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="l_atraso" value="N"> Não');
    } else {
      ShowHTML('              <input ' . $w_Disabled . ' class="STR" type="radio" name="l_atraso" value="S"> Sim <br><input ' . $w_Disabled . ' class="STR" class="STR" type="radio" name="l_atraso" value="N" checked> Não');
    }
    SelecaoFaseCheck('Recuperar fases:', 'S', null, $l_fase, $w_menu, 'l_fase[]', null, null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    if ($l_exibe > '') {
      ShowHTML('<tr><td align="right"><b>Registros: ' . count($RS1));
      ShowHTML('<tr><td>');
      ShowHTML('    <TABLE class="tudo" WIDTH="100%" border=0>');
      if (count($RS1) <= 0) {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td>');
        AbreForm('Form1', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
        ShowHTML('<INPUT type="hidden" name="w_cliente" value="' . $w_cliente . '">');
        ShowHTML('<INPUT type="hidden" name="chaveAux" value="' . $chaveAux . '">');
        ShowHTML('<INPUT type="hidden" name="restricao" value="' . $restricao . '">');
        ShowHTML('<INPUT type="hidden" name="campo" value="' . $campo . '">');
        ShowHTML('<INPUT type="hidden" name="exibe" value="">');
        ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
        ShowHTML(MontaFiltro('POST'));
        ShowHTML('        <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
        ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Protocolo</b></td>');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Tipo</b></td>');
        ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Posse</b></td>');
        ShowHTML('            <td colspan=4><b>Documento original</td>');
        ShowHTML('            <td class="remover" rowspan=2><b>Operações</td>');
        ShowHTML('          </tr>');
        ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
        ShowHTML('            <td><b>Espécie</b></td>');
        ShowHTML('            <td><b>Nº</b></td>');
        ShowHTML('            <td><b><b>Data</b></td>');
        ShowHTML('            <td><b><b>Procedência</b></td>');
        ShowHTML('          </tr>');
        foreach ($RS1 as $row) {
          $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
          ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
          ShowHTML('        <td nowrap>');
          if (nvl(f($row, 'conclusao'), 'nulo') == 'nulo') {
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
          if ($w_tipo != 'WORD')
            ShowHTML('        <A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
          else
            ShowHTML('        ' . f($row, 'protocolo') . '');
          ShowHTML('        <td width="1%" nowrap>' . f($row, 'nm_tipo_protocolo') . '</td>');
          ShowHTML('        <td width="1%" nowrap>' . ExibeUnidade('../', $w_cliente, f($row, 'sg_unidade_posse'), f($row, 'unidade_int_posse'), $TP) . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
          ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
          ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'inicio')) . '</td>');
          ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
          ShowHTML('        <td class="remover"><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\'' . f($row, 'protocolo_completo') . '\', \'' . f($row, 'protocolo_completo') . '\');">Selecionar</a>');
        }
        ShowHTML('        </table></tr>');
        ShowHTML('    </table>');
        ShowHTML('</FORM>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
      }
    }
  } elseif (count($RS1)) {
    ShowHTML('<tr><td align="right"><b>Registros: ' . count($RS1));
    ShowHTML('<tr><td colspan=6>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td>');
    AbreForm('Form1', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, null, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="' . $w_cliente . '">');
    ShowHTML('<INPUT type="hidden" name="chaveAux" value="' . $chaveAux . '">');
    ShowHTML('<INPUT type="hidden" name="restricao" value="' . $restricao . '">');
    ShowHTML('<INPUT type="hidden" name="campo" value="' . $campo . '">');
    ShowHTML('<INPUT type="hidden" name="exibe" value="">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML(montaFiltro('POST'));
    ShowHTML('        <TABLE WIDTH="100%" bgcolor="' . $conTableBgColor . '" BORDER="' . $conTableBorder . '" CELLSPACING="' . $conTableCellSpacing . '" CELLPADDING="' . $conTableCellPadding . '" BorderColorDark="' . $conTableBorderColorDark . '" BorderColorLight="' . $conTableBorderColorLight . '">');
    ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Protocolo</b></td>');
    ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Tipo</b></td>');
    ShowHTML('            <td rowspan=2 width="1%" nowrap><b>Posse</b></td>');
    ShowHTML('            <td colspan=4><b>Documento original</td>');
    ShowHTML('            <td class="remover" rowspan=2><b>Operações</td>');
    ShowHTML('          </tr>');
    ShowHTML('          <tr bgcolor="' . $conTrBgColor . '" align="center">');
    ShowHTML('            <td><b>Espécie</b></td>');
    ShowHTML('            <td><b>Nº</b></td>');
    ShowHTML('            <td><b><b>Data</b></td>');
    ShowHTML('            <td><b><b>Procedência</b></td>');
    ShowHTML('          </tr>');
    foreach ($RS1 as $row) {
      $w_cor = ($w_cor == $conTrBgColor || $w_cor == '') ? $w_cor = $conTrAlternateBgColor : $w_cor = $conTrBgColor;
      ShowHTML('      <tr bgcolor="' . $w_cor . '" valign="top">');
      ShowHTML('        <td nowrap>');
      if (nvl(f($row, 'conclusao'), 'nulo') == 'nulo') {
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
      if ($w_tipo != 'WORD')
        ShowHTML('        <A class="HL" HREF="' . $w_dir . $w_pagina . 'Visual&R=' . $w_pagina . $par . '&O=L&w_chave=' . f($row, 'sq_siw_solicitacao') . '&w_tipo=Volta&P1=2&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET') . '" title="Exibe as informações deste registro.">' . f($row, 'protocolo') . '&nbsp;</a>');
      else
        ShowHTML('        ' . f($row, 'protocolo') . '');
      ShowHTML('        <td width="1%" nowrap>' . f($row, 'nm_tipo_protocolo') . '</td>');
      ShowHTML('        <td width="1%" nowrap>' . ExibeUnidade('../', $w_cliente, f($row, 'sg_unidade_posse'), f($row, 'unidade_int_posse'), $TP) . '</td>');
      ShowHTML('        <td>' . f($row, 'nm_especie') . '</td>');
      ShowHTML('        <td>' . f($row, 'numero_original') . '</td>');
      ShowHTML('        <td align="center">' . FormataDataEdicao(f($row, 'inicio')) . '</td>');
      ShowHTML('        <td>' . f($row, 'nm_origem') . '</td>');
      ShowHTML('        <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\'' . f($row, 'protocolo') . '\', \'' . f($row, 'protocolo') . '\');">Selecionar</a>');
    }
    ShowHTML('        </table></tr>');
    ShowHTML('    </table>');
    ShowHTML('</FORM>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } else {
    ShowHTML('      <tr bgcolor="' . $conTrBgColor . '"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
  }
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
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
  ShowHTML('<BASE HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  BodyOpen(null);
  
  if ($SG == 'PADGERAL') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      if ($O == 'E' && f($RS_Menu, 'cancela_sem_tramite') == 'N') {
        $sql = new db_getSolicLog;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, null, 'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e não excluída.
        // Nessa situação, não é necessário excluir os arquivos.
        if (count($RS) <= 1) {
          $sql = new db_getSolicAnexo;
          $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], null, $w_cliente);
          foreach ($RS as $row) { {
              if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
                unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
            }
          }
        }
      }
      $SQL = new dml_putDocumentoGeral;
      if (nvl($w_copia,0)==0) {
        $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $w_copia, $_REQUEST['w_menu'],
              nvl($_REQUEST['w_sq_unidade'], $_SESSION['LOTACAO']), nvl($_REQUEST['w_sq_unidade'], $_SESSION['LOTACAO']),
              nvl($_REQUEST['w_pessoa_origem'], $_SESSION['SQ_PESSOA']), $_SESSION['SQ_PESSOA'], $_REQUEST['w_solic_pai'],
              $_REQUEST['w_vinculo'], $_REQUEST['w_processo'], $_REQUEST['w_circular'], $_REQUEST['w_especie_documento'],
              $_REQUEST['w_doc_original'], $_REQUEST['w_data_documento'], $_REQUEST['w_volumes'], $_REQUEST['w_dt_autuacao'],
              null, $_REQUEST['w_natureza_documento'], $_REQUEST['w_fim'], $_REQUEST['w_data_recebimento'],
              $_REQUEST['w_interno'], $_REQUEST['w_pessoa_origem'], $_REQUEST['w_pessoa_interes'], $_REQUEST['w_cidade'],
              $_REQUEST['w_assunto'], $_REQUEST['w_descricao'], $_REQUEST['w_observacao'], &$w_chave_nova, &$w_codigo);      
      } else {
        $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $w_copia, $_REQUEST['w_menu'],
              nvl($_REQUEST['w_sq_unidade'], $_SESSION['LOTACAO']), nvl($_REQUEST['w_un_autuacao'], $_SESSION['LOTACAO']),
              nvl($_REQUEST['w_pessoa_origem'], $_SESSION['SQ_PESSOA']), $_SESSION['SQ_PESSOA'], $_REQUEST['w_solic_pai'],
              $_REQUEST['w_vinculo'], $_REQUEST['w_processo'], $_REQUEST['w_circular'], $_REQUEST['w_especie_documento'],
              $_REQUEST['w_doc_original'], $_REQUEST['w_data_documento'], $_REQUEST['w_volumes'], $_REQUEST['w_dt_autuacao'],
              1, $_REQUEST['w_natureza_documento'], $_REQUEST['w_fim'], $_REQUEST['w_data_recebimento'],
              $_REQUEST['w_interno'], $_REQUEST['w_pessoa_origem'], $_REQUEST['w_pessoa_interes'], $_REQUEST['w_cidade'],
              $_REQUEST['w_assunto'], $_REQUEST['w_descricao'], $_REQUEST['w_observacao'], &$w_chave_nova, &$w_codigo);
        $w_chave_nova = $w_copia;
      }
      // Grava cópias
      if (nvl($_REQUEST['w_copias'],0)>0) {
        $w_vinculo = $w_chave_nova;
        for ($i=1; $i<=$_REQUEST['w_copias']; ++$i) {
          $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $w_copia, $_REQUEST['w_menu'],
                  nvl($_REQUEST['w_sq_unidade'], $_SESSION['LOTACAO']), nvl($_REQUEST['w_un_autuacao'], $_SESSION['LOTACAO']),
                  nvl($_REQUEST['w_pessoa_origem'], $_SESSION['SQ_PESSOA']), $_SESSION['SQ_PESSOA'], $_REQUEST['w_solic_pai'],
                  $w_vinculo, $_REQUEST['w_processo'], $_REQUEST['w_circular'], $_REQUEST['w_especie_documento'],
                  $_REQUEST['w_doc_original'], $_REQUEST['w_data_documento'], $_REQUEST['w_volumes'], $_REQUEST['w_dt_autuacao'],
                  $i, $_REQUEST['w_natureza_documento'], $_REQUEST['w_fim'], $_REQUEST['w_data_recebimento'],
                  $_REQUEST['w_interno'], $_REQUEST['w_pessoa_origem'], $_REQUEST['w_pessoa_interes'], $_REQUEST['w_cidade'],
                  $_REQUEST['w_assunto'], $_REQUEST['w_descricao'], $_REQUEST['w_observacao'], &$w_nova_chave, &$w_codigo_interno);
        }
      }

      ScriptOpen('JavaScript');
      if ($O == 'I' || $_REQUEST['w_codigo'] != $_REQUEST['w_codigo_atual']) {
        // Exibe mensagem de gravação com sucesso
        if ($_REQUEST['w_codigo_atual'] == '') {
          if (nvl($w_copia,0)==0) {
            if (nvl($_REQUEST['w_copias'],0)==0) {
              ShowHTML('  alert("Documento cadastrado com sucesso!");');
            } else {
              ShowHTML('  alert("Documento cadastrado com sucesso!\nForam geradas outras '.$_REQUEST['w_copias'].' cópias deste protocolo.");');
            }
          } else {
            if (nvl($_REQUEST['w_copias'],0)==0) {
              ShowHTML('  alert("Cópia gerada com sucesso!");');
            } else {
              ShowHTML('  alert("Cópia gerada com sucesso!\nForam geradas outras '.$_REQUEST['w_copias'].' cópias do protocolo original.");');
            }
          }
        } else {
          $TP = removeTP($TP);
        }

        // Recupera os dados para montagem correta do menu
        $sql = new db_getMenuData;
        $RS1 = $sql->getInstanceOf($dbms, $w_menu);
        ShowHTML('  parent.menu.location=\'' . montaURL_JS('', 'menu.php?par=ExibeDocs&O=A&w_chave=' . $w_chave_nova . '&w_documento=' . $w_codigo . '&R=' . $R . '&SG=' . f($RS1, 'sigla') . '&TP=' . $TP) . '\';');
      } elseif ($O == 'E') {
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
      } else {
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $sql = new db_getLinkData;
        $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS1, 'link') . '&O=' . $O . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG == 'PADOCANEXO') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      // Se foi feito o upload de um arquivo  
      if (UPLOAD_ERR_OK == 0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error'] == UPLOAD_ERR_OK || $Field['error'] == UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!");');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Atenção: o tamanho máximo do arquivo não pode exceder ' . ($w_maximo / 1024) . ' KBytes!");');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            }
            // Se já há um nome para o arquivo, mantém
            if ($_REQUEST['w_atual'] > '') {
              $sql = new db_getSolicAnexo;
              $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_atual'], $w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
                  unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
                if (!(strpos(f($row, 'caminho'), '.') === false)) {
                  $w_file = substr(basename(f($row, 'caminho')), 0, (strpos(basename(f($row, 'caminho')), '.') ? strrpos(basename(f($row, 'caminho')), '.') + 1 : 0) - 1) . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 30);
                } else {
                  $w_file = basename(f($row, 'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp', '', basename($Field['tmp_name']));
              if (!(strpos($Field['name'], '.') === false)) {
                $w_file = $w_file . substr($Field['name'], (strrpos($Field['name'], '.') ? strrpos($Field['name'], '.') + 1 : 0) - 1, 10);
              }
            }
            $w_tamanho = $Field['size'];
            $w_tipo = $Field['type'];
            $w_nome = $Field['name'];
            if ($w_file > '')
              move_uploaded_file($Field['tmp_name'], DiretorioCliente($w_cliente) . '/' . $w_file);
          }elseif (nvl($Field['name'], '') != '') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!");');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
        }
        // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.
        if ($O == 'E' && $_REQUEST['w_atual'] > '') {
          $sql = new db_getSolicAnexo;
          $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $_REQUEST['w_atual'], $w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical . $w_cliente . '/' . f($row, 'caminho')))
              unlink($conFilePhysical . $w_cliente . '/' . f($row, 'caminho'));
          }
        }
        $SQL = new dml_putSolicArquivo;
        $SQL->getInstanceOf($dbms, $O,
                $w_cliente, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_nome'], $_REQUEST['w_descricao'],
                $w_file, $w_tamanho, $w_tipo, $w_nome);
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!");');
        ScriptClose();
        retornaFormulario('w_caminho');
        exit();
      }
      ScriptOpen('JavaScript');
      // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
      $sql = new db_getLinkData;
      $RS = $sql->getInstanceOf($dbms, $w_cliente, $SG);
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG == 'PAINTERESS') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoInter;
      $SQL->getInstanceOf($dbms, $O, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG == 'PADOCASS') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $SQL = new dml_putDocumentoAssunto;
      $SQL->getInstanceOf($dbms, $O, null, $_REQUEST['w_chave'], $_REQUEST['w_chave_aux'], $_REQUEST['w_principal']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif (strpos($SG, 'ENVIO') !== false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], f($RS_Menu, 'sigla'));
      if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite'] || nvl(f($RS, 'unidade_int_posse'), '') != nvl($_REQUEST['w_unidade_posse'], '') || nvl(f($RS, 'pessoa_ext_posse'), '') != nvl($_REQUEST['w_pessoa_posse'], '')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já tramitou este documento!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
        exit;
      } else {
        if (nvl($_REQUEST['w_arq_setorial'], '') == 'S') {
          $sql = new db_getSolicData;
          $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PADCAD');
          if (f($RS, 'sg_tramite') == 'CI') {
            $w_html = VisualDocumento($_REQUEST['w_chave'], 'T', $_SESSION['SQ_PESSOA'], $P1, 'WORD', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'N');
            CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), f($RS, 'sq_siw_tramite'));
          }

          $SQL = new dml_putDocumentoEnvio;
          $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                  $_REQUEST['w_interno'], $_REQUEST['w_unidade_posse'], $_REQUEST['w_sq_unidade'], $_REQUEST['w_pessoa_destino'],
                  $_REQUEST['w_tipo_despacho'], $w_prefixo, $w_numero, $w_ano, $_REQUEST['w_despacho'], $_REQUEST['w_aviso'], $_REQUEST['w_dias'],
                  $_REQUEST['w_retorno_limite'], $_REQUEST['w_pessoa_destino_nm'], $_REQUEST['w_unidade_externa'],
                  &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);

          $SQL = new dml_putDocumentoArqSet; $SQL->getInstanceOf($dbms, $_REQUEST['w_chave'], $_SESSION['SQ_PESSOA'], $_REQUEST['w_despacho']);
          ScriptOpen('JavaScript');
          ShowHTML('  alert("Arquivamento setorial realizado com sucesso!");');
          if ($P1 == 1) {
            // Se for envio da fase de cadastramento, remonta o menu principal
            ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG='.f($RS_Menu, 'sigla').'&TP=' . RemoveTP(RemoveTP($TP))) . '\';');
          }
          ScriptClose();
          exit;
        } elseif (nvl($_REQUEST['w_pessoa_destino'], '') != '') {
          // Se o destino for pessoa jurídica, pede unidade da pessoa
          $sql = new db_getBenef;
          $RS_Destino = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_pessoa_destino'], null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
          foreach ($RS_Destino as $row) {
            $RS_Destino = $row;
            break;
          }
          if (upper(f($RS_Destino, 'nm_tipo_pessoa')) == 'JURÍDICA' && nvl($_REQUEST['w_unidade_externa'], '') == '') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Unidade externa é obrigatória quando o destino é uma pessoa jurídica!");');
            ScriptClose();
            retornaFormulario('w_unidade_externa');
            exit;
          }
        }

        // Se for informado um processo, verifica se ele existe
        if (nvl($_REQUEST['w_protocolo'], '') != '') {
          $w_prefixo  = substr($_REQUEST['w_protocolo'], 0, 5);
          $w_numero   = substr($_REQUEST['w_protocolo'], 6, 6);
          $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4);
          $sql = new db_getProtocolo;
          $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'EXISTE', $p_chave, $p_chave_aux,
                          $w_prefixo, $w_numero, $w_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, 
                          $p_ini, $p_fim, 2, null, null, null, null,null, null, null, null);
          $w_existe = 0;
          foreach ($RS as $row) {
            if (f($row, 'processo') == 'S') {
              $w_existe = 1;
              break;
            }
          }

          if ($w_existe == 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: O processo informado não existe!");');
            ScriptClose();
            retornaFormulario('w_protocolo');
            exit;
          }
        }
        $SQL = new dml_putDocumentoEnvio;
        $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                $_REQUEST['w_interno'], $_REQUEST['w_unidade_posse'], $_REQUEST['w_sq_unidade'], $_REQUEST['w_pessoa_destino'],
                $_REQUEST['w_tipo_despacho'], $w_prefixo, $w_numero, $w_ano, $_REQUEST['w_despacho'], $_REQUEST['w_aviso'], $_REQUEST['w_dias'],
                $_REQUEST['w_retorno_limite'], $_REQUEST['w_pessoa_destino_nm'], $_REQUEST['w_unidade_externa'],
                &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);

        // Grava baseline
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], 'PADCAD');
        if (f($RS, 'sg_tramite') == 'CI') {
          $w_html = VisualDocumento($_REQUEST['w_chave'], 'T', $_SESSION['SQ_PESSOA'], $P1, 'WORD', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'N');
          CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), f($RS, 'sq_siw_tramite'));
        }
        
        ScriptOpen('JavaScript');
        if (nvl($w_nu_guia, '') == '') {
          ShowHTML('  alert("O protocolo já está disponível na sua unidade.\nSe unidade de origem e de destino são iguais, o recebimento é automático!");');
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG=PADCAD&TP=' . RemoveTP(RemoveTP($TP))) . '\';');
        } else {
          ShowHTML('  alert("Tramitação realizada com sucesso!\nImprima a guia de tramitação na próxima tela.");');
          ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG=RELPATRAM&TP=' . RemoveTP(RemoveTP($TP)) . '&p_unid_receb=' . $w_sq_unidade . '&p_nu_guia=' . $w_nu_guia . '&p_ano_guia=' . $w_ano_guia) . '\';');
        }
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif (strpos($SG, 'PADTRAM') !== false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      if (nvl($_REQUEST['w_arq_central'], '') == 'S' || nvl($_REQUEST['w_desarq_central'], '') == 'S') {
        $w_devolucao = ((nvl($_REQUEST['w_desarq_central'], '') == 'S') ? 'S' : 'N');
        $SQL = new dml_putCaixaEnvio;
        for ($i = 1; $i < count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $_POST['w_chave'][$i], $w_usuario,
                    $_REQUEST['w_interno'], $_POST['w_unid_origem'][$_POST['w_chave'][$i]], $_REQUEST['w_sq_unidade'],
                    $_REQUEST['w_tipo_despacho'], $_REQUEST['w_despacho'],
                    &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
          }
        }
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Transferência realizada com sucesso!\nImprima a guia de transferência na próxima tela.");');
        $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'RELPATRANS');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&SG=' . f($RS, 'sigla') . '&TP=' . RemoveTP(RemoveTP($TP)) . '&p_devolucao='.$w_devolucao.'&p_nu_guia=' . $w_nu_guia . '&p_ano_guia=' . $w_ano_guia) . '\';');
        ScriptClose();
      } elseif (nvl($_REQUEST['w_arq_setorial'], '') == 'S') {
        $SQL = new dml_putDocumentoArqSet;
        for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA'], $_REQUEST['w_despacho']);
          }
        }
  
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Arquivamento setorial realizado com sucesso!");');
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $sql = new db_getLinkData;
        $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, f($RS1, 'link').'&O=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } elseif (nvl($_REQUEST['w_descartar'], '') == 'S') {
        $SQL = new dml_putDocumentoDescarte;
        for ($i = 0; $i <= count($_POST['w_chave']) - 1; $i = $i + 1) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $_SESSION['SQ_PESSOA'], $_REQUEST['w_despacho']);
          }
        }
  
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Descarte realizado com sucesso!");');
        // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        $sql = new db_getLinkData;
        $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir, f($RS1, 'link').'&O=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        // Se o destino for pessoa jurídica, pede unidade da pessoa
        if (nvl($_REQUEST['w_pessoa_destino'], '') != '') {
          $sql = new db_getBenef;
          $RS_Destino = $sql->getInstanceOf($dbms, $w_cliente, $_REQUEST['w_pessoa_destino'], null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
          foreach ($RS_Destino as $row) {
            $RS_Destino = $row;
            break;
          }
          if (upper(f($RS_Destino, 'nm_tipo_pessoa')) == 'JURÍDICA' && nvl($_REQUEST['w_unidade_externa'], '') == '') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: Unidade externa é obrigatória quando o destino é uma pessoa jurídica!");');
            ScriptClose();
            retornaFormulario('w_unidade_externa');
            exit;
          }
        }

        // Se for informado um processo, verifica se ele existe
        if (nvl($_REQUEST['w_protocolo'], '') != '') {
          $w_prefixo  = substr($_REQUEST['w_protocolo'], 0, 5);
          $w_numero   = substr($_REQUEST['w_protocolo'], 6, 6);
          $w_ano      = substr($_REQUEST['w_protocolo'], 13, 4);
          $sql = new db_getProtocolo;
          $RS = $sql->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $w_usuario, 'EXISTE', $p_chave, $p_chave_aux,
                          $w_prefixo, $w_numero, $w_ano, $p_unid_autua, $p_unid_posse, $p_nu_guia, $p_ano_guia, 
                          $p_ini, $p_fim, 2, null, null, null, null,null, null, null, null);
          $w_existe = 0;
          foreach ($RS as $row) {
            if (f($row, 'processo') == 'S') {
              $w_existe = 1;
              break;
            }
          }

          if ($w_existe == 0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATENÇÃO: O processo informado não existe!");');
            ScriptClose();
            retornaFormulario('w_protocolo');
            exit;
          }
        }
        $SQL = new dml_putDocumentoEnvio;
        for ($i = 1; $i < count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, f($RS_Menu, 'sq_menu'), $_POST['w_chave'][$i], $w_usuario,
                    $_POST['w_tramite'][$_POST['w_chave'][$i]], $_REQUEST['w_interno'],
                    $_POST['w_unid_origem'][$_POST['w_chave'][$i]], $_REQUEST['w_sq_unidade'], $_REQUEST['w_pessoa_destino'],
                    $_REQUEST['w_tipo_despacho'], $w_prefixo, $w_numero, $w_ano, $_REQUEST['w_despacho'], $_REQUEST['w_aviso'],
                    $_REQUEST['w_dias'], $_REQUEST['w_retorno_limite'], $_REQUEST['w_pessoa_destino_nm'],
                    $_REQUEST['w_unidade_externa'], &$w_nu_guia, &$w_ano_guia, &$w_unidade_autuacao);
          }
        }
        ScriptOpen('JavaScript');
        if (nvl($w_nu_guia, '') == '') {
          ShowHTML('  alert("O protocolo já está disponível na sua unidade.\nSe unidade de origem e de destino são iguais, o recebimento é automático!");');
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $sql = new db_getLinkData;
          $RS1 = $sql->getInstanceOf($dbms, $w_cliente, $SG);
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS1, 'link') . '&O=&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG) . '\';');
        } else {
          ShowHTML('  alert("Tramitação realizada com sucesso!\nImprima a guia de tramitação na próxima tela.");');
          $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms, $w_cliente, 'RELPATRAM');
          ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&SG=' . f($RS, 'sigla') . '&TP=' . RemoveTP(RemoveTP($TP)) . '&p_unid_receb=' . $w_sq_unidade . '&p_nu_guia=' . $w_nu_guia . '&p_ano_guia=' . $w_ano_guia) . '\';');
        }
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif ($SG == 'PAENVCEN') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      // Verifica se é necessário criar uma nova caixa

      if ($_REQUEST['w_opcao']=='D') {
        $SQL = new dml_putDocumentoArqSet;
        for ($i = 1; $i <= count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_POST['w_chave'][$i], $w_usuario, $_REQUEST['w_local']);
          }
        }
      } else {
        $w_caixa = $_REQUEST['w_caixa'];
        if ($_REQUEST['w_caixa'] === '0') {
          $SQL = new dml_putCaixa;
          $SQL->getInstanceOf($dbms, 'I', $w_cliente, null, $_REQUEST['p_unid_posse'], null, null, null,
                  null, null, null, null, null, null, null, null, null, null, &$w_caixa);
        }

        $SQL = new dml_putDocumentoCaixa;
        for ($i = 1; $i < count($_POST['w_chave']); $i++) {
          if (Nvl($_POST['w_chave'][$i], '') > '') {
            $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_POST['w_chave'][$i], $w_usuario, $w_caixa, $_REQUEST['w_pasta']);
          }
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'TramitCentral&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif ($SG == 'PACLASSIF') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $sql = new db_getSolicData;
      $SQL = new dml_putDocumentoAssunto;
      for ($i = 1; $i < count($_POST['w_chave']); $i++) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $RS_Assunto = $sql->getInstanceOf($dbms, $_POST['w_chave'][$i], 'PADCAD');
          $SQL->getInstanceOf($dbms, 'E', $_SESSION['SQ_PESSOA'], $_POST['w_chave'][$i], f($RS_Assunto, 'sq_assunto'), 'S');
          $SQL->getInstanceOf($dbms, 'I', $_SESSION['SQ_PESSOA'], $_POST['w_chave'][$i], $_REQUEST['w_assunto'], 'S');
        }
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $w_pagina . 'Classif&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
      exit;
    }
  } elseif (strpos($SG, 'RECEB') !== false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $sql = new db_getProtocolo;

      $RS = $sql->getInstanceOf($dbms, $w_menu, $w_usuario, 'RECEBIDO', null, null, null, null, null,
                      $_REQUEST['w_unid_prot'], null, $_REQUEST['w_nu_guia'], $_REQUEST['w_ano_guia'], null, null, 2, null, null, null,
                      null, null, null, null, null);
      if (count($RS) == 0) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já recebeu esta guia!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        if (($O == 'S' || $O == 'U')) {
          MailRecusa($O, $_REQUEST['w_unid_autua'], $_REQUEST['w_nu_guia'], $_REQUEST['w_ano_guia'], $_REQUEST['w_observacao']);
        }

        $SQL = new dml_putDocumentoReceb;
        $SQL->getInstanceOf($dbms, $O, $w_usuario, $_REQUEST['w_unid_autua'], $_REQUEST['w_nu_guia'], $_REQUEST['w_ano_guia'], $_REQUEST['w_observacao']);

        ScriptOpen('JavaScript');
        ShowHTML('  alert("Protocolos da guia ' . (($O == 'S' || $O == 'U') ? 'recusados' : 'recebidos') . ' com sucesso!");');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, $R . '&O='.((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ? 'L' : 'O').'&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . $SG . MontaFiltro('GET')) . '\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif (strpos($SG, 'CONC') !== false) {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'], upper($_REQUEST['w_assinatura'])) || $w_assinatura == '') {
      $sql = new db_getSolicData;
      $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], f($RS_Menu, 'sigla'));
      if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite'] || nvl(f($RS, 'unidade_int_posse'), '') != nvl($_REQUEST['w_unidade_posse'], '') || nvl(f($RS, 'pessoa_ext_posse'), '') != nvl($_REQUEST['w_pessoa_posse'], '')) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATENÇÃO: Outro usuário já tramitou este documento!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } else {
        $SQL = new dml_putDocumentoConc;
        $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'], $_REQUEST['w_inicio_real'], $_REQUEST['w_fim_real'], $_REQUEST['w_nota_conclusao'], $_REQUEST['w_custo_real']);
        // Envia e-mail comunicando a conclusão
        //SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Assinatura Eletrônica inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados não encontrado: ' . $SG . '");');
    ScriptClose();
    exibevariaveis();
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':         Inicial();          break;
    case 'GERAL':           Geral();            break;
    case 'INTERESS':        Interessados();     break;
    case 'ASSUNTOS':        Assuntos();         break;
    case 'VISUAL':          Visual();           break;
    case 'VISUALE':         VisualE();          break;
    case 'EXCLUIR':         Excluir();          break;
    case 'ENVIO':           Encaminhamento();   break;
    case 'ANEXO':           Anexos();           break;
    case 'ANOTACAO':        Anotar();           break;
    case 'CONCLUIR':        Concluir();         break;
    case 'BUSCAASSUNTO':    BuscaAssunto();     break;
    case 'TRAMIT':          Tramitacao();       break;
    case 'TRAMITCENTRAL':   TramitCentral();    break;
    case 'CLASSIF':         Classificacao();    break;
    case 'RECEB':           Recebimento();      break;
    case 'BUSCAPROTOCOLO':  BuscaProtocolo();   break;
    case 'TEXTOSETORIAL':   TextoSetorial();    break;
    case 'GRAVA':           Grava();            break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<div align=center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></div>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  }
}
?>