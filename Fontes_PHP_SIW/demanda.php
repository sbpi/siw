<?
session_start();
$w_dir_volta    = '';
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getLinkData.php');
include_once('classes/sp/db_getLinkSubMenu.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getCustomerSite.php');
include_once('classes/sp/db_getUorgResp.php');
include_once('classes/sp/db_getCcData.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getUorgData.php');
include_once('classes/sp/db_getCountryData.php');
include_once('classes/sp/db_getRegionData.php');
include_once('classes/sp/db_getStateData.php');
include_once('classes/sp/db_getCityData.php');
include_once('classes/sp/db_getSolicList.php');
include_once('classes/sp/db_getSolicAcesso.php');
include_once('classes/sp/db_getSolicData.php');
include_once('classes/sp/db_getSolicAnexo.php');
include_once('classes/sp/db_getSolicInter.php');
include_once('classes/sp/db_getSolicAreas.php');
include_once('classes/sp/db_getSolicLog.php');
include_once('classes/sp/db_getTramiteData.php');
include_once('classes/sp/db_getTramiteList.php');
include_once('classes/sp/db_getSiwCliModLis.php');
include_once('funcoes/selecaoCC.php');
include_once('funcoes/selecaoPessoa.php');
include_once('funcoes/selecaoUnidade.php');
include_once('funcoes/selecaoPais.php');
include_once('funcoes/selecaoRegiao.php');
include_once('funcoes/selecaoEstado.php');
include_once('funcoes/selecaoCidade.php');
include_once('funcoes/selecaoPrioridade.php');
include_once('funcoes/selecaoFaseCheck.php');
include_once('funcoes/selecaoFase.php');
include_once('funcoes/selecaoTipoVisao.php');
include_once('funcoes/selecaoSolicResp.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_putDemandaGeral.php');
include_once('classes/sp/dml_putSolicArquivo.php');
include_once('classes/sp/dml_putDemandaInter.php');
include_once('classes/sp/dml_putDemandaAreas.php');
include_once('classes/sp/dml_putDemandaEnvio.php');
include_once('classes/sp/dml_putDemandaConc.php');
include_once('visualdemanda.php');

// =========================================================================
//  /Demanda.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de demandas
// Mail     : alex@sbpi.com.br
// Criacao  : 15/10/2003 12:25
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
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'demanda.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = '';
$w_troca        = $_REQUEST['w_troca'];

if ($SG=='GDANEXO' || $SG=='GDINTERESS' || $SG=='GDAREAS') {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='') $O = 'L';
}
elseif ($SG=='GDENVIO') $O = 'V';
elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem  
  if ($P1==3) $O = 'P'; else $O = 'L';
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$w_copia        = $_REQUEST['w_copia'];
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_assunto      = strtoupper($_REQUEST['p_assunto']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 

// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);

  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if ((!(strpos(strtoupper($R),'GR_')===false)) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_sqcc>'') {
        $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro = $w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>''){
        $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'') {
        $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">País <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Região <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
      if ($p_proponente>'') $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Proponente <td>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')    $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 

    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GDCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, null, null, null, null);
    } else {
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, null, null, null, null);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'fim','asc','prioridade','asc');
    } else {
      $RS = SortArray($RS,'fim','asc','prioridade','asc');
    }
  }

  if ($w_tipo=='WORD') {
    HeaderWord(); 
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML("<meta http-equiv=\"Refresh\" content=\"300; URL=".MontaURL("MESA")."\">");
    ShowHTML("<TITLE>".$conSgSistema." - Listagem de demandas</TITLE>");
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (!(strpos('CP',$O)===false)) {
      if ($P1!=1 || $O=='C') {
        // Se não for cadastramento ou se for cópia
        Validate('p_chave','Número da demanda','','','1','18','','0123456789');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_proponente','Proponente externo','','','2','90','1','');
        Validate('p_assunto','Assunto','','','2','90','1','1');
        Validate('p_palavra','Palavras-chave','','','2','90','1','1');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de recebimento ou nenhuma delas!\');');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclusão inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclusão final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclusão ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclusão inicial','<=','p_fim_f','Conclusão final');
      } 
      Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  if ($w_troca>'') {
    // Se for recarga da página
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_smtp_server.focus();\'');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se for cadastramento
      BodyOpenClean('onLoad=\'document.Form.p_chave.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.p_ordena.focus()\';');
    } 
  } else {
    BodyOpenClean('onLoad=document.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_submenu>'') {
        ShowHTML('<tr><td>');
        $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
        foreach($RS1 as $row) {
          ShowHTML('    <a accesskey="I" class="SS" href="'.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
          break;
        }
        ShowHTML('    <a accesskey="C" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      } else {
        ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if ((strpos(strtoupper($R),'GR_')===false) && $P1!=6 && $w_tipo!='WORD') {
      if ($w_copia>'') {
        // Se for cópia
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } else {
        if (MontaFiltro('GET')>'') {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        } else {
          ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
        } 
      } 
    } 
    ShowHTML('    <td align="right">');
    if ($w_tipo!='WORD') {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a target="MetaWord" href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td><b>'.LinkOrdena('Nº','sq_siw_solicitacao').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Responsável','nm_solic').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>'.LinkOrdena('Executor','nm_exec').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Proponente','proponente').'</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td><b>'.LinkOrdena('Detalhamento','assunto').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('Fim previsto','fim').'</td>');
      } else {
        ShowHTML('          <td><b>'.LinkOrdena('Detalhamento','assunto').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('Fim previsto','fim').'</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
        ShowHTML('          <td><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      } 
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Operações</td>');
    } else {
      ShowHTML('          <td><b>Nº</td>');
      ShowHTML('          <td><b>Responsável</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Executor</td>');
      ShowHTML('          <td><b>Proponente</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td><b>Detalhamento</td>');
        ShowHTML('          <td><b>Fim previsto</td>');
      } else {
        ShowHTML('          <td><b>Detalhamento</td>');
        ShowHTML('          <td><b>Fim previsto</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Valor</td>');
        ShowHTML('          <td><b>Fase atual</td>');
      } 
    } 
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if ($w_tipo!='WORD') {
          if (f($row,'concluida')=='N') {
            if (f($row,'fim')<addDays(time(),-1)) {
              ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
            } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
              ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
            } else {
              ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
            } 
          } else {
            if (f($row,'sg_tramite')=='CA') {
              ShowHTML('           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">');            
            } elseif (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
              ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');              
            } else {
              ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
            } 
          } 
          ShowHTML('        <A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>');
          ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
          if ($_SESSION['INTERNO']=='S') {
            if (Nvl(f($row,'nm_exec'),'---')!='---') {
              ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
            } else {
              ShowHTML('        <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
            } 
          } 
        } else {
          ShowHTML('        '.f($row,'sq_siw_solicitacao'));
          ShowHTML('        <td>'.f($row,'nm_solic').'</td>');
          // Coloca o nome do executor somente se não for cadastramento nem mesa de trabalho, para economizar na largura.
          if ($_SESSION['INTERNO']=='S') {
            if (Nvl(f($row,'nm_exec'),'---')!='---') {
              ShowHTML('        <td>'.f($row,'nm_exec').'</td>');
            } else {
              ShowHTML('        <td>'.Nvl(f($row,'nm_exec'),'---').'</td>');
            } 
          } 
        } 
        ShowHTML('        <td>'.Nvl(f($row,'proponente'),'---').'</td>');
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td>'.Nvl(f($row,'assunto'),'-').'</td>');
        } else {
          if ($w_tipo!='WORD' && strlen(Nvl(f($row,'assunto'),'-'))>50) $w_titulo = substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_titulo = Nvl(f($row,'assunto'),'-');
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
          } else {
            ShowHTML('        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.htmlspecialchars($w_titulo).'</td>');
          } 
        } 
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>');
        // Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
        if ($P1!=1 && $P1!=2) {
          if ($_SESSION['INTERNO']=='S') {
            if (f($row,'sg_tramite')=='AT') {
              ShowHTML('        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>');
              $w_parcial = $w_parcial + f($row,'custo_real');
            } else {
              ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
              $w_parcial=$w_parcial+f($row,'valor');
            } 
          } 
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        if ($_SESSION['INTERNO']=='S' && $w_tipo!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3) {
            // Se não for acompanhamento
            if ($w_copia>'') {
              // Se for listagem para cópia
              $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              foreach($RS1 as $row1) { 
                ShowHTML('          <a accesskey="I" class="HL" href="'.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;'); 
                break;
              }
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') {
                ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informações cadastrais da demanda" TARGET="menu">Alterar</a>&nbsp;');
              } else {
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da demanda">Alterar</A>&nbsp');
              } 
              ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da demanda.">Excluir</A>&nbsp');
            } elseif ($P1==2) {
              // Se for execução
              if ($w_usuario==f($row,'executor')) {
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a demanda, sem enviá-la.">Anotar</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a demanda para outro responsável.">Enviar</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da demanda.">Concluir</A>&nbsp');
              } else {
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a demanda para outro responsável.">Enviar</A>&nbsp');
              } 
            } 
          } else {
            if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
                Nvl(f($row,'titular'),0)==$w_usuario || 
                Nvl(f($row,'substituto'),0)==$w_usuario
               ) {
              ShowHTML('          <A class="HL" HREF="'.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a demanda para outro responsável.">Enviar</A>&nbsp');
            } else {
              ShowHTML('          ---&nbsp');
            } 
          } 
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
      // Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
      if ($P1!=1 && $P1!=2 && $_SESSION['INTERNO']=='S') {
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total desta página&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') {
              $w_total = $w_total + f($row,'custo_real');
            } else {
              $w_total = $w_total + f($row,'valor');
            } 
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total da listagem&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_total,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif (!(strpos('CP',$O)===false)) {
    if ($O=='C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar a demanda que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    }
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assificação:','L','Selecione a classificação desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela demanda na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pela demanda na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde a demanda se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,'N','p_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta demanda.',$p_prioridade,null,'p_prioridade',null,null);
      ShowHTML('          <td valign="top"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('          <td valign="top"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente demandas em atraso?</b><br>');
        if ($p_atraso=='S') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        } 
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_Ordena=='ASSUNTO') {
      ShowHTML('          <option value="assunto" SELECTED>Detalhamento<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='INICIO') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio" SELECTED>Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='NM_TRAMITE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PRIORIDADE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Proponente externo');
    } elseif ($p_Ordena=='PROPONENTE') {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Proponente externo');
    } else {
      ShowHTML('          <option value="assunto">Detalhamento<option value="inicio">Data de recebimento<option value="" SELECTED>Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 

// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_proponente       = $_REQUEST['w_proponente'];
    $w_sq_unidade_resp  = $_REQUEST['w_sq_unidade_resp'];
    $w_assunto          = $_REQUEST['w_assunto'];
    $w_prioridade       = $_REQUEST['w_prioridade'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
    $w_chave            = $_REQUEST['w_chave'];
    $w_chave_pai        = $_REQUEST['w_chave_pai'];
    $w_chave_aux        = $_REQUEST['w_chave_aux'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
    $w_sq_unidade       = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite       = $_REQUEST['w_sq_tramite'];
    $w_solicitante      = $_REQUEST['w_solicitante'];
    $w_cadastrador      = $_REQUEST['w_cadastrador'];
    $w_executor         = $_REQUEST['w_executor'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_inclusao         = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao        = $_REQUEST['w_conclusao'];
    $w_valor            = $_REQUEST['w_valor'];
    $w_opiniao          = $_REQUEST['w_opiniao'];
    $w_data_hora        = $_REQUEST['w_data_hora'];
    $w_pais             = $_REQUEST['w_pais'];
    $w_uf               = $_REQUEST['w_uf'];
    $w_cidade           = $_REQUEST['w_cidade'];
    $w_palavra_chave    = $_REQUEST['w_palavra_chave'];
    $w_sqcc             = $_REQUEST['w_sqcc'];
  } else {
    if (!(strpos('AEV',$O)===false) || $w_copia>'') {
      // Recupera os dados da demanda
      if ($w_copia>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG); 
      } else { 
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      }
      if (count($RS)>0) {
        $w_proponente       = f($RS,'proponente');
        $w_sq_unidade_resp  = f($RS,'sq_unidade_resp');
        $w_assunto          = f($RS,'assunto');
        $w_prioridade       = f($RS,'prioridade');
        $w_aviso            = f($RS,'aviso_prox_conc');
        $w_dias             = f($RS,'dias_aviso');
        $w_inicio_real      = f($RS,'inicio_real');
        $w_fim_real         = f($RS,'fim_real');
        $w_concluida        = f($RS,'concluida');
        $w_data_conclusao   = f($RS,'data_conclusao');
        $w_nota_conclusao   = f($RS,'nota_conclusao');
        $w_custo_real       = f($RS,'custo_real');
        $w_chave_pai        = f($RS,'sq_solic_pai');
        $w_chave_aux        = null;
        $w_sq_menu          = f($RS,'sq_menu');
        $w_sq_unidade       = f($RS,'sq_unidade');
        $w_sq_tramite       = f($RS,'sq_siw_tramite');
        $w_solicitante      = f($RS,'solicitante');
        $w_cadastrador      = f($RS,'cadastrador');
        $w_executor         = f($RS,'executor');
        $w_descricao        = f($RS,'descricao');
        $w_justificativa    = f($RS,'justificativa');
        $w_inicio           = FormataDataEdicao(f($RS,'inicio'));
        $w_fim              = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao         = f($RS,'inclusao');
        $w_ultima_alteracao = f($RS,'ultima_alteracao');
        $w_conclusao        = f($RS,'conclusao');
        $w_valor            = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao          = f($RS,'opiniao');
        $w_data_hora        = f($RS,'data_hora');
        $w_sqcc             = f($RS,'sq_cc');
        $w_pais             = f($RS,'sq_pais');
        $w_uf               = f($RS,'co_uf');
        $w_cidade           = f($RS,'sq_cidade_origem');
        $w_palavra_chave    = f($RS,'palavra_chave');
      } 
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_assunto','Detalhamento','1',1,5,2000,'1','1');
    if (f($RS_Menu,'solicita_cc')=='S') {
      Validate('w_sqcc','Classificação','SELECT',1,1,18,'','0123456789');
    } 
    Validate('w_solicitante','Solicitante','HIDDEN',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Setor responsável','HIDDEN',1,1,18,'','0123456789');
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_fim','Limite para conclusão','DATA',1,10,10,'','0123456789/');        break;
      case 2: Validate('w_fim','Limite para conclusão','DATAHORA',1,17,17,'','0123456789/');    break;
      case 3: 
        Validate('w_inicio','Data de recebimento','DATA',1,10,10,'','0123456789/');       
        Validate('w_fim','Limite para conclusão','DATA',1,10,10,'','0123456789/');
        CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão');
        break;
    case 4:
        Validate('w_inicio','Data de recebimento','DATAHORA',1,17,17,'','0123456789/,: ');
        Validate('w_fim','Limite para conclusão','DATAHORA',1,17,17,'','0123456789/,: ');
        CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão');
        break;
    } 
    Validate('w_valor','Orçamento disponível','VALOR','1',4,18,'','0123456789.,');
    Validate('w_palavra_chave','Palavras-chave','','',2,90,'1','1');
    Validate('w_proponente','Proponente externo','','',2,90,'1','1');
    Validate('w_pais','País','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    if (f($RS_Menu,'descricao')=='S') {
      Validate('w_descricao','Resultados da demanda','1',1,5,2000,'1','1');
    } 
    if (f($RS_Menu,'justificativa')=='S') {
      Validate('w_justificativa','Recomendações superiores','1',1,5,2000,'1','1');
    } 
    Validate('w_dias','Dias de alerta','1','',1,2,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!\');');
    ShowHTML('        theForm.w_dias.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    ShowHTML('  else {');
    ShowHTML('     theForm.w_dias.value = \'\';');
    ShowHTML('  }');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpenClean('onLoad=\'document.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assunto.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_pais=='') {
      // Carrega os valores padrão para país, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais     = f($RS,'sq_pais');
      $w_uf       = f($RS,'co_uf');
      $w_cidade   = f($RS,'sq_cidade_padrao');
    } 
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') {
        $w_Erro = Validacao($w_sq_solicitacao,$SG);
      } 
    } 
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco serão utilizados para identificação da demanda, bem como para o controle de sua execução.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b>Detalh<u>a</u>mento:</b><br><textarea '.$w_Disabled.' accesskey="A" name="w_assunto" class="STI" ROWS=5 cols=75 title="Escreva um texto de detalhamento para esta demanda">'.$w_assunto.'</TEXTAREA></td>');
    if (f($RS_Menu,'solicita_cc')=='S') {
      ShowHTML('          <tr>');
      SelecaoCC('C<u>l</u>assificação:','L','Selecione um dos itens relacionados.',$w_sqcc,null,'w_sqcc','SIWSOLIC');
      ShowHTML('          </tr>');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o solicitante da demanda na relação.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor responsável:','S','Selecione o setor responsável pela execução da demanda',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,null);
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta demanda.',$w_prioridade,null,'w_prioridade',null,null);
    ShowHTML('          <tr>');
    switch (f($RS_Menu,'data_hora')) {
      case 1: ShowHTML('              <td valign="top"><b>Limi<u>t</u>e para conclusão:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Data limite para que a execução da demanda esteja concluída."></td>');           break;
      case 2: ShowHTML('              <td valign="top"><b>Limi<u>t</u>e para conclusão:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora limite para que a execução da demanda esteja concluída."></td>');  break;
      case 3: 
        ShowHTML('              <td valign="top"><b>Data de re<u>c</u>ebimento:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" title="Data de recebimento da demanda."></td>'); 
        ShowHTML('              <td valign="top"><b>Limi<u>t</u>e para conclusão:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Data limite para que a execução da demanda esteja concluída."></td>');
        break;
      case 4:
        ShowHTML('              <td valign="top"><b>Data de re<u>c</u>ebimento:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora de recebimento da demanda."></td>');
        ShowHTML('              <td valign="top"><b>Limi<u>t</u>e para conclusão:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora limite para que a execução da demanda esteja concluída."></td>');
        break;
    } 
    ShowHTML('              <td valign="top"><b>O<u>r</u>çamento disponível:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o orçamento disponível para execução da demanda, ou zero se não for o caso."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Pa<u>l</u>avras-chave:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="w_palavra_chave" size="90" maxlength="90" value="'.$w_palavra_chave.'" title="Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identificação desta demanda."></td>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Identificação do proponente</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco identificam o proponente externo e sua localização, sendo utilizados para consultas gerenciais por distribuição geográfica.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b>Nome do proponent<u>e</u> externo:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Proponente externo da demanda. Preencha apenas se houver."></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>aís:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,'N','w_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('          </table>');
    if (f($RS_Menu,'descricao')=='S' || f($RS_Menu,'justificativa')=='S') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Informações adicionais</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores da demanda.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      if (f($RS_Menu,'descricao')=='S') {
        ShowHTML('      <tr><td valign="top"><b>Res<u>u</u>ltados da demanda:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os resultados esperados após a execução da demanda.">'.$w_descricao.'</TEXTAREA></td>');
      } 
      if (f($RS_Menu,'justificativa')=='S') {
        ShowHTML('      <tr><td valign="top"><b><u>R</u>ecomendações superiores:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Relacione as recomendações a serem seguidas na execução da demanda.">'.$w_justificativa.'</TEXTAREA></td>');
      } 
    } 
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Alerta de atraso</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão da demanda.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('          <tr>');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td valign="top"><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="2" maxlength="2" value="'.$w_dias.'" title="Número de dias para emissão do alerta de proximidade da data limite para conclusão da demanda."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'\';" name="Botao" value="Cancelar">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
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
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Título','1','1','1','255','1','1');
      Validate('w_descricao','Descrição','1','1','1','1000','1','1');
      if ($O=='I') {
        Validate('w_caminho','Arquivo','','1','5','255','1','1');
      } 
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_descricao.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Título</td>');
    ShowHTML('          <td><b>Descrição</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
        ShowHTML('        <td>'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td>'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    ShowHTML('<FORM action="'.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_caminho.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I' || $O=='A') {
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b></font>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    }  
    ShowHTML('      <tr><td><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGATÓRIO. Informe um título para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGATÓRIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclusão do registro?\');">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);' 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
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
  $w_troca      = $_REQUEST['w_troca']; 
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tipo_visao   = $_REQUEST['w_tipo_visao'];
    $w_envia_email  = $_REQUEST['w_envia_email'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome_resumido','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {
      $w_nome        = f($row,'nome_resumido');
      $w_tipo_visao  = f($row,'tipo_visao');
      $w_envia_email = f($row,'envia_email');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Pessoa','HIDDEN','1','1','10','','1');
      Validate('w_tipo_visao','Tipo de visão','SELECT','1','1','10','','1');
    }  
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_chave_aux.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Pessoa</td>');
    ShowHTML('          <td><b>Visao</td>');
    ShowHTML('          <td><b>Envia e-mail</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>');
        ShowHTML('        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione o interessado na relação!',$w_chave_aux,null,'w_chave_aux','USUARIOS');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Pessoa:</b><br>'.$w_nome.'</td>');
    } 
    SelecaoTipoVisao('<u>T</u>ipo de visão:','T','Selecione o tipo de visão que o interessado terá desta demanda.',$w_tipo_visao,null,'w_tipo_visao',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Envia e-mail ao interessado quando houver encaminhamento?</b>',$w_envia_email,'w_envia_email');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
       ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de áreas envolvidas
// -------------------------------------------------------------------------
function Areas() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_papel = $_REQUEST['w_papel'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {
      $w_nome  = f($row,'nome');
      $w_papel = f($row,'papel');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Área/Instituição','HIDDEN','1','1','10','','1');
      Validate('w_papel','Papel desempenhado','','1','1','2000','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Área/Instituição</td>');
    ShowHTML('          <td><b>Papel</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
       ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'papel').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>Á</U>rea/Instituição:','A',null,$w_chave_aux,null,'w_chave_aux',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Área/Instituição:</b><br>'.$w_nome.'</td>');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>P</u>apel desempenhado:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_papel" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela área ou instituição na execução da demanda.">'.$w_papel.'</TEXTAREA></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
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
  $w_tipo  = strtoupper(trim($_REQUEST['w_tipo']));

  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de demanda</TITLE>');
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=\'document.focus()\'; ');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  ShowHTML('Visualização de Demanda');
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.DataHora().'</font></B></TD></TR>');
  ShowHTML('</B></TD></TR></TABLE>');
  ShowHTML('<HR>');
  if ($w_tipo>'') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back();">aqui</a> para voltar à tela anterior</b></center>');
  } 
  // Chama a rotina de visualização dos dados da demanda, na opção 'Listagem'
  ShowHTML(VisualDemanda($w_chave,'L',$w_usuario));
  if ($w_tipo>'') {
    ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back();">aqui</a> para voltar à tela anterior</b></center>');
  } 
  Rodape();
} 

// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if (!(strpos('E',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da demanda, na opção 'Listagem'
  ShowHTML(VisualDemanda($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'GDGERAL',$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'GDGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite      = $_REQUEST['w_tramite'];
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'GDGERAL');
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $RS = db_getTramiteData::getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');
  if ($w_ativo == 'N') {
    $RS = db_getTramiteList::getInstanceOf($dbms, $w_menu, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) {
      $w_novo_tramite = f($row,'sq_siw_tramite');
      $w_sg_tramite   = f($row,'sigla');
      break;
    }   
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da demanda, na opção 'Listagem'
  ShowHTML(VisualDemanda($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'GDENVIO',$R,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento
    SelecaoFase('<u>F</u>ase da demanda:','F','Se deseja alterar a fase atual da demanda, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para a demanda na relação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoPessoa('<u>D</u>estinatário:','D','Selecione um destinatário para a demanda na relação.',$w_destinatario,null,'w_destinatario','USUARIOS');
    } 
  } else {
    SelecaoFase('<u>F</u>ase da demanda:','F','Se deseja alterar a fase atual da demanda, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinatário:','D','Selecione um destinatário para a demanda na relação.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela área ou instituição na execução da demanda.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    // Se não for cadastramento
    // Volta para a listagem
    $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';" name="Botao" value="Abandonar">');
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

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anotação','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>'); 
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da demanda, na opção 'Listagem'
  ShowHTML(VisualDemanda($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_pagina.'Grava&SG=GDENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'GDGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/'); break;
      case 2: Validate('w_fim_real','Término da execução','DATAHORA',1,17,17,'','0123456789/'); break;
      case 3: 
        Validate('w_inicio_real','Início da execução','DATA',1,10,10,'','0123456789/');
        Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/');
        CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
        CompData('w_fim_real','Término da execução','<=',FormataDataEdicao(time()),'data atual');
        break;
      case 4:
        Validate('w_inicio_real','Início da execução','DATAHORA',1,17,17,'','0123456789/,: ');
        Validate('w_fim_real','Término da execução','DATAHORA',1,17,17,'','0123456789/,: ');
        CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
        break;
    } 
    Validate('w_custo_real','Custo real','VALOR','1',4,18,'','0123456789.,');
    Validate('w_nota_conclusao','Nota de conclusão','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se não for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_inicio_real.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualização dos dados da demanda, na opção 'Listagem'
  ShowHTML(VisualDemanda($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  ShowHTML('<FORM name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_pagina.'Grava&SG=GDCONC&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'GDGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATENÇÃO: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</font></b>.</td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  switch (f($RS_Menu,'data_hora')) {
    case 1: ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de término da execução da demanda."></td>');          break;
    case 2: ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data/hora de término da execução da demanda."></td>'); break;
    case 3:
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" title="Informe a data/hora de início da execução da demanda."></td>');
      ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de término da execução da demanda."></td>');
      break;
    case 4:
      ShowHTML('              <td valign="top"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data/hora de início da execução da demanda."></td>');
      ShowHTML('              <td valign="top"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data de término da execução da demanda."></td>');
      break;
  } 
  ShowHTML('              <td valign="top"><b>Custo <u>r</u>eal:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o orçamento disponível para execução da demanda, ou zero se não for o caso."></td>');
  ShowHTML('          </table>');
  ShowHTML('      <tr><td valign="top"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  if ($P1!=1) {
    // Se não for cadastramento
    ShowHTML('      <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
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
// Rotina de preparação para envio de e-mail relativo a demandas eventuais
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;

  $l_solic          = $p_solic;
  $w_destinatarios  = '';
  $w_resultado      = '';
  $w_html='<HTML>'.chr(13);
  $w_html.=BodyOpenMail(null).chr(13);
  $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.chr(13);
  $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.chr(13);
  $w_html.='    <table width="97%" border="0">'.chr(13);
  if ($p_tipo==1) {
    $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE DEMANDA</b><br><br><td></tr>'.chr(13);
  } elseif ($p_tipo==2) {
    $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE DEMANDA</b><br><br><td></tr>'.chr(13);
  } elseif ($p_tipo==3) {
    $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE DEMANDA</b><br><br><td></tr>'.chr(13);
  } 
  $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b><br><br><td></tr>'.chr(13);
  // Recupera os dados da demanda
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,'GDGERAL');
  $w_nome='Demanda '.f($RSM,'sq_siw_solicitacao');
  $w_html.=chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
  $w_html.=chr(13).'    <table width="99%" border="0">';
  $w_html.=chr(13).'      <tr><td>Detalhamento: <b>'.$p_solic.'<br>'.CRLF2BR(f($RSM,'assunto')).'</b></td></tr>';
  // Identificação da demanda
  $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DA DEMANDA</td>';
  // Se a classificação foi informada, exibe.
  if (nvl(f($RSM,'sq_cc'),'')>'') {
    $w_html.=chr(13).'      <tr><td valign="top">Classificação:<br><b>'.f($RSM,'cc_nome').' </b></td>';
  } 
  $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $w_html.=chr(13).'          <tr valign="top">';
  $w_html.=chr(13).'          <td>Responsável:<br><b>'.f($RSM,'nm_sol').'</b></td>';
  $w_html.=chr(13).'          <td>Unidade responsável:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
  $w_html.=chr(13).'          <tr valign="top">';
  $w_html.=chr(13).'          <td>Data de recebimento:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
  $w_html.=chr(13).'          <td>Limite para conclusão:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
  $w_html.=chr(13).'          <td>Prioridade:<br><b>'.RetornaPrioridade(f($RSM,'prioridade')).' </b></td>';
  $w_html.=chr(13).'          </table>';
  // Informações adicionais
  if (Nvl(f($RSM,'descricao'),'')>'') {
    $w_html.=chr(13).'      <tr><td valign="top">Resultados da demanda:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
  }
  $w_html.=chr(13).'    </table>';
  $w_html.=chr(13).'</tr>';
  // Dados da conclusão da demanda, se ela estiver nessa situação
  if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DA CONCLUSÃO</td>';
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>Início da execução:<br><b>'.FormataDataEdicao(f($RSM,'inicio_real')).' </b></td>';
    $w_html.=chr(13).'          <td>Término da execução:<br><b>'.FormataDataEdicao(f($RSM,'fim_real')).' </b></td>';
    $w_html.=chr(13).'          </table>';
    $w_html.=chr(13).'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RSM,'nota_conclusao')).' </b></td>';
  } 
  //Recupera o último log
  $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
  $RS = SortArray($RS,'phpdt_data','desc');
  foreach ($RS as $row) { $RS = $row; break; }
  $w_data_encaminhamento = f($RS,'phpdt_data');
  // Exibe dados do encaminhamento apenas se for tramitação. Se for conclusão, apenas recupera o momento de registro.
  if ($p_tipo==2) {
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>ÚLTIMO ENCAMINHAMENTO</td>';
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
    $w_html.=chr(13).'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
    $w_html.=chr(13).'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
    $w_html.=chr(13).'          </table>';
    // Se for tramitação, configura o destinatário da tramitação como destinatário da mensagem
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
    $w_destinatarios = f($RS,'email').'; ';
  } 
  $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMAÇÕES</td>';
  $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $w_html.='      <tr valign="top"><td>'.chr(13);
  $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.chr(13);
  $w_html.='      </td></tr>'.chr(13);
  $w_html.='      <tr valign="top"><td>'.chr(13);
  $w_html.='         Dados da ocorrência:<br>'.chr(13);
  $w_html.='         <ul>'.chr(13);
  $w_html.='         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.chr(13);
  $w_html.='         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.chr(13);
  $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_HOST'].'</b></li>'.chr(13);
  $w_html.='         </ul>'.chr(13);
  $w_html.='      </td></tr>'.chr(13);
  $w_html.='    </table>'.chr(13);
  $w_html.='</td></tr>'.chr(13);
  $w_html.='</table>'.chr(13);
  $w_html.='</BODY>'.chr(13);
  $w_html.='</HTML>'.chr(13);

  // Recupera o e-mail do responsável
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
  if (strpos($w_destinatarios,f($RS,'email').'; ')===false) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';

  // Recupera o e-mail do titular e do substituto pelo setor responsável
  $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
  if ((strpos($w_destinatarios,f($RS,'email_titular').'; ')===false)    && Nvl(f($RS,'email_titular'),'nulo')!='nulo')    $w_destinatarios=$w_destinatarios.f($RS,'email_titular').'; ';
  if ((strpos($w_destinatarios,f($RS,'email_substituto').'; ')===false) && Nvl(f($RS,'email_substituto'),'nulo')!='nulo') $w_destinatarios=$w_destinatarios.f($RS,'email_substituto').'; ';

  // Prepara os dados necessários ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  if ($p_tipo==1 || $p_tipo==3) {
    // Inclusão ou Conclusão
    if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome; else $w_assunto='Conclusão - '.$w_nome;
  } elseif ($p_tipo==2) {
    // Tramitação
    $w_assunto='Tramitação - '.$w_nome;
  } 
  if ($w_destinatarios>'') {
    // Executa o envio do e-mail
    $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
  } 
  // Se ocorreu algum erro, avisa da impossibilidade de envio
  if ($w_resultado>'') {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
    ScriptClose();
  } 
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  $w_file    = '';
  $w_tamanho = '';
  $w_tipo    = '';
  $w_nome    = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=document.focus();');
  switch ($SG) {
    case 'GDGERAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
        if ($O=='E') {
          $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
          // Mais de um registro de log significa que deve ser cancelada, e não excluída.
          // Nessa situação, não é necessário excluir os arquivos.
          if (count($RS)<=1) {
            $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
            foreach($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            } 
          } 
        } 
        dml_putDemandaGeral::getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],
            $_SESSION['SQ_PESSOA'],null,$_REQUEST['w_sqcc'],$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],'0',$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor'],
            $_REQUEST['w_data_hora'], $_REQUEST['w_sq_unidade_resp'], $_REQUEST['w_assunto'], $_REQUEST['w_prioridade'], $_REQUEST['w_aviso'], $_REQUEST['w_dias'],
            $_REQUEST['w_cidade'], $_REQUEST['w_palavra_chave'],null, null, null, null, null, null, null,
            null, null, null, null, &$w_chave_nova, $w_copia);
        if ($O=='I') {
          // Envia e-mail comunicando a inclusão
          SolicMail(Nvl($_REQUEST['w_chave'],$w_chave_nova),1);
          // Recupera os dados para montagem correta do menu
          $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ScriptOpen('JavaScript');
          ShowHTML('  parent.menu.location=\'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET').'\';');
        } elseif ($O=='E') {
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.$R.'&O=L&R='.$R.'&SG=GDCAD&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'\';');
        } else {
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $RS1 = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        } 
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'GDINTERESS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putDemandaInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_tipo_visao'],$_REQUEST['w_envia_email']);
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'GDAREAS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putDemandaAreas::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_papel']);
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'GDANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.back(1);');
                ScriptClose();
                exit();
              } 
              // Se já há um nome para o arquivo, mantém 
              if ($_REQUEST['w_atual']>'') {
                $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                  if (!(strpos(f($row,'caminho'),'.')===false)) {
                    $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,30);
                  } else {
                    $w_file = basename(f($row,'caminho'));
                  }
                }
              } else {
                $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.'.'.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
                }
              } 
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
          if ($O=='E' && $_REQUEST['w_atual']>'') {
            $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
          dml_putSolicArquivo::getInstanceOf($dbms,$O,
            $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],
            $w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'GDENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Trata o recebimento de upload ou dados 
        if (!(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA')===false)) {
          // Se foi feito o upload de um arquivo 
          if (UPLOAD_ERR_OK==0) {
            $w_maximo = $_REQUEST['w_upload_maximo'];
            foreach ($_FILES as $Chv => $Field) {
              if ($Field['size'] > 0) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                if ($Field['size'] > $w_maximo) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ShowHTML('  history.back(1);');
                  ScriptClose();
                  exit();
                } 
                // Se já há um nome para o arquivo, mantém 
                $w_file = basename($Field['tmp_name']);
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.'.'.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
                }
                $w_tamanho = $Field['size'];
                $w_tipo    = $Field['type'];
                $w_nome    = $Field['name'];
                if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
              } 
            } 
            dml_putDemandaEnvio::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
                $w_file,$w_tamanho,$w_tipo,$w_nome);
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
            ScriptClose();
          } 
          ScriptOpen('JavaScript');
          // Volta para a listagem 
          $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
          ScriptClose();
        } else {
          dml_putDemandaEnvio::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
            $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
            null,null,null,null);
          // Envia e-mail comunicando a inclusão
          SolicMail($_REQUEST['w_chave'],2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          if ($P1==1) {
            // Recupera os dados para montagem correta do menu
            $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET').'\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            // Volta para a listagem 
            $RS = db_getMenuData::getInstanceOf($dbms,$_REQUEST['w_menu']);
            ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.RemoveTP($TP).'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
            ScriptClose();
          } 
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'GDCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'GDGERAL');
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta demanda para outra fase de execução!\');');
          ScriptClose();
        } else {
          // Se foi feito o upload de um arquivo  
          if (UPLOAD_ERR_OK==0) {
            $w_maximo = $_REQUEST['w_upload_maximo'];
            foreach ($_FILES as $Chv => $Field) {
              if ($Field['size'] > 0) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                if ($Field['size'] > $w_maximo) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ShowHTML('  history.back(1);');
                  ScriptClose();
                  exit();
                } 
                // Se já há um nome para o arquivo, mantém 
                $w_file = basename($Field['tmp_name']);
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.'.'.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
                }
                $w_tamanho = $Field['size'];
                $w_tipo    = $Field['type'];
                $w_nome    = $Field['name'];
                if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
              } 
            } 
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
            exit();
          } 
          dml_putDemandaConc::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave'],3);
          ScriptOpen('JavaScript');
          // Volta para a listagem
          $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
          ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';');
          ScriptClose();
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
  case 'INICIAL':       Inicial();        break;
  case 'GERAL':         Geral();          break;
  case 'ANEXO':         Anexos();         break;
  case 'INTERESS':      Interessados();   break;
  case 'AREAS':         Areas();          break;
  case 'VISUAL':        Visual();         break;
  case 'EXCLUIR':       Excluir();        break;
  case 'ENVIO':         Encaminhamento(); break;
  case 'TRAMITE':       Tramitacao();     break;
  case 'ANOTACAO':      Anotar();         break;
  case 'CONCLUIR':      Concluir();       break;
  case 'GRAVA':         Grava();          break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
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
  } 
} 
?>


