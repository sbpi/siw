<?php
header('Expires: '.-1500);
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
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_get10PercentDays_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData_IS.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/dml_putTarefaGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putRespTarefa_IS.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putDemandaEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putTarefaLimite.php');
include_once($w_dir_volta.'classes/sp/dml_putDemandaConc.php');
include_once($w_dir_volta.'funcoes/selecaoAcao.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTarefa.php');
include_once('visualtarefa.php');
// =========================================================================
//  /tarefas.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho 
// Descricao: Gerencia o módulo de tarefas
// Mail     : celso@sbpi.com.br
// Criacao  : 17/08/2006 16:30
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
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);
// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'tarefas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = lower($_REQUEST['p_ordena']);
if ($SG=='ISTAANEXO' || $SG=='ISTARESP') {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='') $O='L';
}elseif ($SG=='ISTAENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem  
  if ($P1==3) $O='P';
  else        $O='L';
} switch ($O) {
  case 'I':     $w_TP=$TP.' - Inclusão';        break;
  case 'A':     $w_TP=$TP.' - Alteração';       break;
  case 'E':     $w_TP=$TP.' - Exclusão';        break;
  case 'P':     $w_TP=$TP.' - Filtragem';       break;
  case 'C':     $w_TP=$TP.' - Cópia';           break;
  case 'V':     $w_TP=$TP.' - Envio';           break;
  case 'H':     $w_TP=$TP.' - Herança';         break;
  default:      $w_TP=$TP.' - Listagem';        break;
} 
$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=RetornaMenu($w_cliente,$SG);
$w_ano=RetornaAno();
$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_atividade    = upper($_REQUEST['p_atividade']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_assunto      = upper($_REQUEST['p_assunto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = upper($_REQUEST['p_sqcc']);
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualização resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='L') {
    if (!(strpos(upper($R),'GR_')===false) || !(strpos(upper($R),'PROJETO')===false)) {
      $w_filtro='';
      if ($p_projeto>'') {
        $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$p_projeto,'ISACGERAL');
        foreach($RS as $row){$RS=$row; break;}
        if (Nvl(f($RS,'cd_acao'),'')>'')  $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Ação <td><font size=1>[<b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações da ação.">'.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').' - '.f($RS,'nm_ppa').' ('.f($RS,'ds_unidade').')</a></b>]';
        else                              $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Ação <td><font size=1>[<b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações da ação.">'.f($RS,'titulo').'</a></b>]';
      }
      if ($p_chave>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Atividade nº <td><font size=1>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'')    $w_filtro=$w_filtro.' <tr valign="top"><td align="right"><font size=1>Prazo para conclusão até<td><font size=1>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceof($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Responsável <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceof($dbms,$p_unidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade responsável <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceof($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Executor <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceof($dbms,$p_uorg_resp);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade atual <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'') {
        $sql = new db_getCountryData; $RS = $sql->getInstanceof($dbms,$p_pais);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>País <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $sql = new db_getRegionData; $RS = $sql->getInstanceof($dbms,$p_regiao);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Região <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $sql = new db_getStateData; $RS = $sql->getInstanceof($dbms,$p_pais,$p_uf);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Estado <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $sql = new db_getCityData; $RS = $sql->getInstanceof($dbms,$p_cidade);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Cidade <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>'')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Prioridade <td><font size=1>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
      if ($p_proponente>'')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Parceria externa <td><font size=1>[<b>'.$p_proponente.'</b>]';
      if ($p_assunto>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Descrição <td><font size=1>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Responsável <td><font size=1>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Data recebimento <td><font size=1>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Limite conclusão <td><font size=1>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')     $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')       $w_filtro='<table border=0><tr valign="top"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>'.$w_filtro.'</ul></tr></table>';
    } 
    $RS = new db_getLinkData; $RS = $RS->getInstanceof($dbms,$w_cliente,'ISTCAD');
    if ($w_copia>'') {
      // Se for cópia, aplica o filtro sobre todas as tarefas visíveis pelo usuário
      $sql = new db_getSolicList_IS; $RS = $sql->getInstanceof($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_projeto,$p_atividade,null,null,null,null,$w_ano);
    } else {
      $sql = new db_getSolicList_IS; $RS = $sql->getInstanceof($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
              $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
              $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
              $p_chave,$p_assunto,$p_pais,$p_regiao,$p_uf,$p_cidade,$p_usu_resp,
              $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_projeto,$p_atividade,null,null,null,null,$w_ano);
    }
    if (Nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'ordem','asc','phpdt_fim','asc','prioridade','asc');
    }
  } 
  Cabecalho();
  head();
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de atividades</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (!(strpos('CP',$O)===false)) {
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      Validate('p_chave','Número da tarefa','','','1','18','','0123456789');
      Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
      Validate('p_proponente','Parcerias externas','','','2','90','1','');
      Validate('p_assunto','Detalhamento','','','2','90','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da página
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('CP',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.p_projeto.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="1">');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e não for resultado de busca para cópia
      if ($w_submenu>'') {
        $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
        foreach($RS1 as $row){$RS1=$row; break;}
        ShowHTML('<tr><td><font size="1">');
        ShowHTML('    <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        //ShowHTML '    <a accesskey=''C'' class=''SS'' href=''' & w_dir & w_pagina & par & '&R=' & w_pagina & par & '&O=C&P1=' & P1 & '&P2=' & P2 & '&P3=1&P4=' & P4 & '&TP=' & TP & '&SG=' & SG & MontaFiltro('GET') & '''><u>C</u>opiar</a>'
      } else {
        ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'PROJETO')===false)) {
      if ($w_copia>'') {
        // Se for cópia
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nº','sq_siw_solicitacao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ação','nm_projeto').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Responsavel SISPLAM','solicitante').'</font></td>');
    if ($P1!=2) ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Usuário atual','nm_exec').'</font></td>');
    if ($P1==1 || $P1==2) {
      // Se for cadastramento ou mesa de trabalho
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Tarefa','titulo').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Fim previsto','fim').'</font></td>');
    } else {
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Parcerias','proponente').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Tarefa','titulo').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Fim previsto','fim').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Valor','valor').'</font></td>');
      ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Fase atual','nm_tramite').'</font></td>');
    } 
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap><font size="1">');
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">');
          elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">');
          else                                ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">');
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">');
          else                                ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">');
        } 
        ShowHTML('        <A class="HL" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>');
        ShowHTML('        <td><font size="1"><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($row,'sq_solic_pai').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($row,'nm_projeto').'</a></td>');
        ShowHTML('        <td><font size="1">'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        if ($P1!=2) {
          // Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
          if (Nvl(f($row,'nm_exec'),'---')>'---') ShowHTML('        <td><font size="1">'.ExibePessoa('../',$w_cliente,f($row,'executor'),$TP,f($row,'nm_exec')).'</td>');
          else                                   ShowHTML('        <td><font size="1">---</td>');
        } 
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          ShowHTML('        <td><font size="1">'.Nvl(f($row,'proponente'),'---').'</td>');
        } 
        // Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        // Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        if ($_REQUEST['p_tamanho']=='N') {
          ShowHTML('        <td><font size="1">'.Nvl(f($row,'titulo'),'-').'</td>');
        } else {
          if (f($row,'sg_tramite')=='CA') ShowHTML('        <td><font size="1"><strike>'.Nvl(f($row,'titulo'),'-').'</strike></td>');
          else                           ShowHTML('        <td><font size="1">'.Nvl(f($row,'titulo'),'-').'</td>');
        } 
        ShowHTML('        <td align="center"><font size="1">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>');
        if ($P1!=1 && $P1!=2) {
          // Se não for cadastramento nem mesa de trabalho
          if (f($row,'sg_tramite')=='AT') {
            ShowHTML('        <td align="right"><font size="1">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>');
            $w_parcial=$w_parcial+f($row,'custo_real');
          } else {
            ShowHTML('        <td align="right"><font size="1">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
            $w_parcial=$w_parcial+f($row,'valor');
          } 
          ShowHTML('        <td nowrap><font size="1">'.f($row,'nm_tramite').'</td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        if ($P1!=3 && $P1!=5) {
          // Se não for acompanhamento
          if ($w_copia>'') {
            // Se for listagem para cópia
            $sql = new db_getLinkSubMenu; $row1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
            //ShowHTML '          <a accesskey=''I'' class=''HL'' href=''' & w_dir & w_pagina & 'Geral&R=' & w_pagina & par & '&O=I&SG=' & RS1('sigla') & '&w_menu=' & w_menu & '&P1=' & P1 & '&P2=' & P2 & '&P3=' & P3 & '&P4=' & P4 & '&TP=' & TP & '&w_copia=' & RS('sq_siw_solicitacao') & MontaFiltro('GET') & '''>Copiar</a>&nbsp;'
          } elseif ($P1==1) {
            // Se for cadastramento
            if ($w_submenu>'') ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informações cadastrais da tarefa" TARGET="menu">AL</a>&nbsp;');
            else               ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informações cadastrais da tarefa">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclusão da tarefa.">EX</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Encaminhamento da tarefa.">EN</A>&nbsp');
          } elseif ($P1==2 || $P1==6) {
            // Se for execução
            if ($w_usuario==f($row,'executor')) {
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anotações para a tarefa, sem enviá-la.">AN</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a tarefa para outro responsável.">EN</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execução da tarefa.">CO</A>&nbsp');
            } else {
              if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a tarefa para outro responsável.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
          }
        } else {
          if (Nvl(f($row,'solicitante'),0)   == $w_usuario || 
              Nvl(f($row,'titular'),0)       == $w_usuario || 
              Nvl(f($row,'substituto'),0)    == $w_usuario || 
              Nvl(f($row,'tit_exec'),0)      == $w_usuario || 
              Nvl(f($row,'subst_exec'),0)    == $w_usuario) {
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia a tarefa para outro responsável.">EN</A>&nbsp');
          } else {
            ShowHTML('          ---&nbsp');
          } 
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
      if ($P1!=1 && $P1!=2) {
        // Se não for cadastramento nem mesa de trabalho
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma página
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=7 align="right"><font size="1"><b>Total desta página&nbsp;</font></td>');
          ShowHTML('          <td align="right"><font size="1"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</font></td>');
          ShowHTML('          <td colspan=2><font size="1">&nbsp;</font></td>');
          ShowHTML('        </tr>');
        } 
        // Se for a última página da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total = $w_total + f($row,'custo_real');
            else                            $w_total = $w_total + f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=7 align="right"><font size="1"><b>Total da listagem&nbsp;</font></td>');
          ShowHTML('          <td align="right"><font size="1"><b>'.number_format($w_total,2,',','.').'&nbsp;</font></td>');
          ShowHTML('          <td colspan=2><font size="1">&nbsp;</font></td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('CP',$O)===false)) {
    if ($O=='C') {
      // Se for cópia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar a tarefa que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for cópia, cria parâmetro para facilitar a recuperação dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    // Recupera dados da opção Projetos
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação da tarefa na relação.',$w_cliente,$w_ano,null,null,null,null,'p_projeto','ACAO',null,$p_projeto);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se não for cadastramento ou se for cópia
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><font size="1"><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td valign="top"><font size="1"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>sável:','N','Selecione o responsável pela tarefa na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor responsável:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Responsável atua<u>l</u>:','L','Selecione o responsável atual pela tarefa na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde a tarefa se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>aís:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egião:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta tarefa.',$p_prioridade,null,'p_prioridade',null,null);
      ShowHTML('          <td valign="top"><font size="1"><b>Parcerias exter<u>n</u>as:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><font size="1"><b><U>D</U>etalhamento:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><font size="1"><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><font size="1"><b>Iní<u>c:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      ShowHTML('          <td valign="top"><font size="1"><b>Limi<u>t</u>e para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
      if ($O!='C') {
        // Se não for cópia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><font size="1"><b>Exibe somente atividades em atraso?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
        else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Descrição<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Parcerias externas');
    elseif ($p_ordena=='INICIO')    ShowHTML('          <option value="assunto">Descrição<option value="inicio" SELECTED>Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Parcerias externas');
    elseif ($p_ordena=='NM_TRAMITE')ShowHTML('          <option value="assunto">Descrição<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Parcerias externas');
    elseif ($p_ordena=='PRIORIDADE')ShowHTML('          <option value="assunto">Descrição<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Parcerias externas');
    elseif ($p_ordena=='PROPONENTE')ShowHTML('          <option value="assunto">Descrição<option value="inicio">Data de recebimento<option value="">Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Parcerias externas');
    else                            ShowHTML('          <option value="assunto">Descrição<option value="inicio">Data de recebimento<option value="" SELECTED>Data limite para conclusão<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Parcerias externas');
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for cópia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar cópia">');
    } else {
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
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
  
  if ($w_ano==substr(FormataDataEdicao(time()),6,4)) $w_sugestao = FormataDataEdicao(time());
  else                                               $w_sugestao = '';
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_proponente       = $_REQUEST['w_proponente'];
    $w_sq_unidade_resp  = $_REQUEST['w_sq_unidade_resp'];
    $w_titulo           = $_REQUEST['w_titulo'];
    $w_assunto          = $_REQUEST['w_assunto'];
    $w_prioridade       = $_REQUEST['w_prioridade'];
    $w_aviso            = $_REQUEST['w_aviso'];
    $w_dias             = $_REQUEST['w_dias'];
    $w_ordem            = $_REQUEST['w_ordem'];
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
    $w_projeto          = $_REQUEST['w_projeto'];
    $w_atividade        = $_REQUEST['w_atividade'];
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
      // Recupera os dados da tarefa
      if ($w_copia>'') {
        $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_copia,$SG);
      } else {
        $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,$SG);
      } 
      foreach($RS as $row){$RS=$row; break;}
      if (count($RS)>0) {
        $w_proponente         = f($RS,'proponente');
        $w_sq_unidade_resp    = f($RS,'sq_unidade_resp');
        $w_titulo             = f($RS,'titulo');
        $w_assunto            = f($RS,'assunto');
        $w_prioridade         = f($RS,'prioridade');
        $w_aviso              = f($RS,'aviso_prox_conc');
        $w_dias               = f($RS,'dias_aviso');
        $w_ordem              = Nvl(f($RS,'ordem'),0);
        $w_inicio_real        = f($RS,'inicio_real');
        $w_fim_real           = f($RS,'fim_real');
        $w_concluida          = f($RS,'concluida');
        $w_data_conclusao     = f($RS,'data_conclusao');
        $w_nota_conclusao     = f($RS,'nota_conclusao');
        $w_custo_real         = f($RS,'custo_real');
        $w_projeto            = f($RS,'sq_solic_pai');
        $w_projeto_ant        = f($RS,'sq_solic_pai');
        $w_chave_pai          = f($RS,'sq_solic_pai');
        $w_chave_aux          = null;
        $w_sq_menu            = f($RS,'sq_menu');
        $w_sq_unidade         = f($RS,'sq_unidade');
        $w_sq_tramite         = f($RS,'sq_siw_tramite');
        $w_solicitante        = f($RS,'solicitante');
        $w_cadastrador        = f($RS,'cadastrador');
        $w_executor           = f($RS,'executor');
        $w_descricao          = f($RS,'descricao');
        $w_justificativa      = f($RS,'justificativa');
        $w_inicio             = FormataDataEdicao(f($RS,'inicio'));
        $w_fim                = FormataDataEdicao(f($RS,'fim'));
        $w_inclusao           = f($RS,'inclusao');
        $w_ultima_alteracao   = f($RS,'ultima_alteracao');
        $w_conclusao          = f($RS,'conclusao');
        $w_valor              = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao            = f($RS,'opiniao');
        $w_data_hora          = f($RS,'data_hora');
        $w_pais               = f($RS,'sq_pais');
        $w_uf                 = f($RS,'co_uf');
        $w_cidade             = f($RS,'sq_cidade_origem');
        $w_palavra_chave      = f($RS,'palavra_chave');
      } 
    } 
  } 
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_projeto','Ação','SELECT',1,1,18,'','0123456789');
    Validate('w_titulo','Tarefa','1',1,3,100,'1','1');
    Validate('w_assunto','Descrição','1',1,5,2000,'1','1');
    Validate('w_solicitante','Responsável SISPLAM','SELECT',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Área de planejamento','SELECT',1,1,18,'','0123456789');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_fim','Limite para conclusão','DATA',1,10,10,'','0123456789/');        break;
      case 2: Validate('w_fim','Limite para conclusão','DATAHORA',1,17,17,'','0123456789/');    break;
      case 3:
        Validate('w_inicio','Início previsto','DATA',1,10,10,'','0123456789/');
        Validate('w_fim','Fim previsto','DATA',1,10,10,'','0123456789/');
        CompData('w_inicio','Início previsto','<=','w_fim','Fim previsto');
      break;
      case 4:
        Validate('w_inicio','Data de recebimento','DATAHORA',1,17,17,'','0123456789/,: ');
        Validate('w_fim','Limite para conclusão','DATAHORA',1,17,17,'','0123456789/,: ');
        CompData('w_inicio','Data de recebimento','<=','w_fim','Limite para conclusão');
      break;
    } 
    Validate('w_valor','Recurso programado','VALOR','1',4,18,'','0123456789.,');
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    Validate('w_proponente','Parcerias externas','','',2,90,'1','1');
    if (f($RS_Menu,'descricao')=='S') Validate('w_descricao','Resultados esperados','1',1,5,2000,'1','1');
    if (f($RS_Menu,'justificativa')=='S') Validate('w_justificativa','Observações','1','',5,2000,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if     ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.w_projeto.focus()\';');
  } elseif (!(strpos('EV',$O)===false)) {
    BodyOpen(null);
  } else {
    BodyOpen('onLoad=\'document.Form.w_projeto.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_pais=='') {
      // Carrega os valores padrão para país, estado e cidade
      $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = f($RS,'sq_cidade_padrao');
    } 
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_projeto_ant" value="'.$w_projeto_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_atividade_ant" value="'.$w_atividade_ant.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_ordem" value="0">');
    //Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.f($RS,'sq_cidade_padrao').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="1"><b>Identificação</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação da tarefa, bem como para o controle de sua execução.</font></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    // Recupera dados da opção Ações
    ShowHTML('      <tr>');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação a qual a tarefa está vinculada.',$w_cliente,$w_ano,null,null,null,null,'w_projeto','ACAO',null,$w_projeto);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>T</u>arefa:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" title="Informe o nome da tarefa."></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Des<u>c</u>rição:</b><br><textarea '.$w_Disabled.' accesskey="c" name="w_assunto" class="STI" ROWS=5 cols=75 title="Descreva, de forma detalhada, o que é realizado na tarefa.">'.$w_assunto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('Respo<u>n</u>sável SISPLAM:','N','Selecione o nome da pessoa responsável pelas informações no SISPLAM.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade_IS('<U>Á</U>rea planejamento:','A','Selecione a área da secretaria ou órgão responsável pela tarefa',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,'PLANEJAMENTO');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('              <td valign="top"><font size="1"><b>Iní<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,$w_sugestao).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('              <td valign="top"><font size="1"><b>Fim previs<u>t</u>o:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('              <td valign="top"><font size="1"><b><u>R</u>ecurso programado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o recurso programado para a execução da tarefa."></td>');
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta tarefa.',$w_prioridade,null,'w_prioridade',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Informar quais são os parceiros externos na execução da tarefa (campo opcional)."></td>');
    if (f($RS_Menu,'descricao')=='S' || f($RS_Menu,'justificativa')=='S') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="1"><b>Informações adicionais</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td><font size=1>Os dados deste bloco visam orientar os executores da tarefa.</font></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      if (f($RS_Menu,'descricao')=='S') ShowHTML('      <tr><td valign="top"><font size="1"><b>Res<u>u</u>ltados esperados:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os resultados físicos esperados com a execução da tarefa.">'.$w_descricao.'</TEXTAREA></td>');
      if (f($RS_Menu,'justificativa')=='S') ShowHTML('      <tr><td valign="top"><font size="1"><b>Obse<u>r</u>vações:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Informe as observações pertinentes (campo não obrigatório)">'.$w_justificativa.'</TEXTAREA></td>');
    } 
    // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $RS = new db_getMenuData; $RS = $RS->getInstanceof($dbms,$w_menu);
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de cadastramento do limite orçamentário da tarefa
// -------------------------------------------------------------------------
function Limite() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_readonly   = '';
  $w_erro       = '';
  if (!(strpos('A',$O)===false)) {
    // Recupera os dados da ação
    $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      if (Nvl(f($RS,'cd_acao'),'')=='') {
        ScriptOpen('JavaScript');
        ShowHTML(' alert(\'Para tarefa não orçamentária, não é permitido inserir limite orçamentário!\');');
        ShowHTML(' history.back(1);');
        ScriptClose();
      } 
      $w_valor      = number_format(Nvl(f($RS,'custo_real'),0),2,',','.');
      $w_chave_pai  = f($RS,'sq_solic_pai');
      $w_sq_tramite = f($RS,'sq_siw_tramite');
      $w_limite     = f($RS,'limite_orcamento');
      $w_sq_unidade = f($RS,'sq_unidade_resp');
    } 
  } 
  Cabecalho();
  head();
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='A') {
    Validate('w_valor','Limite orçamentário','VALOR','1',4,18,'','0123456789.,');
    CompValor('w_valor','Limite orçamentário','>','0,00','zero');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  } elseif ($O=='P') {
    Validate('w_chave_pai','Ação PPA','SELECT','',1,18,'','0123456789');
    Validate('w_chave','Tarefa','SELECT','1',1,18,'','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_valor.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('A',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_pai" value="'.$w_chave_pai.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.$w_sq_unidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_tramite" value="'.$w_sq_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    // Recupera os dados da tarefa
    $sql = new db_getSolicData_IS; $RS1 = $sql->getInstanceOf($dbms,$w_chave,$SG);
    foreach ($RS1 as $row){$RS1=$row; break;}
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>TAREFA: '.$w_chave.' - '.f($RS1,'titulo').'</b></font></div></td></tr>');
    ShowHTML('      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    // Identificação da tarefa
    ShowHTML('      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DA TAREFA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>');
    if ($w_chave_pai>'') {
      // Recupera os dados da ação
      $sql = new db_getSolicData_IS; $RS2 = $sql->getInstanceOf($dbms,$w_chave_pai,'ISACGERAL');
      foreach ($RS2 as $row){$RS2=$row; break;}
      // Se a ação no PPA for informada, exibe.
      ShowHTML('   <tr><td><font size="1"><b>Programa:</b></font></td>');
      ShowHTML('       <td><font size="1">'.f($RS2,'cd_ppa_pai').' - '.f($RS2,'nm_ppa_pai').'</font></div></td></tr>');
      ShowHTML('   <tr><td><font size="1"><b>Ação:</b></font></td>');
      ShowHTML('       <td><font size="1">'.f($RS2,'cd_acao').' - '.f($RS2,'nm_ppa').'</font></div></td></tr>');
      ShowHTML('   <tr><td><font size="1"><b>Unidade:</b></font></td>');
      ShowHTML('       <td><font size="1">'.f($RS2,'cd_unidade').' - '.f($RS2,'ds_unidade').'</font></div></td></tr>');
      ShowHTML('   <tr><td width="30%"><b>Descrição:</b></font></td>');
      ShowHTML('       <td><div align="justify">'.Nvl(f($RS1,'assunto'),'-').'</font></div></td></tr>');
      ShowHTML('   <tr><td><b>Recurso Programado '.$w_ano.':</b></font></td>');
      ShowHTML('       <td>R$ '.number_format(f($RS1,'valor'),2,',','.').'</font></td></tr>');
      ShowHTML('   <tr><td><b>Área Planejamento:</b></font></td>');
      ShowHTML('       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_resp'),f($RS1,'sq_unidade_resp'),$TP).'</font></td></tr>');
      ShowHTML('   <tr><td><b>Responsável SISPLAM:</b></font></td>');
      ShowHTML('       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol_comp')).'</font></td></tr>');
      ShowHTML('   <tr><td><b>Início Previsto:</b></font></td>');
      ShowHTML('       <td>'.FormataDataEdicao(f($RS1,'inicio')).'</font></td></tr>');
      ShowHTML('   <tr><td><b>Fim Previsto:</b></font></td>');
      ShowHTML('       <td>'.FormataDataEdicao(f($RS1,'fim')).'</font></td></tr>');
      ShowHTML('   <tr><td><b>Prioridade:</b></font></td>');
      ShowHTML('       <td>'.RetornaPrioridade(f($RS1,'prioridade')).'</font></td></tr>');
      ShowHTML('   <tr><td><b>Parecerias Externas:</b></font></td>');
      ShowHTML('       <td>'.Nvl(f($RS1,'proponente'),'-').'</font></td></tr>');
      ShowHTML('   <tr><td><b>Fase Atual:</b></font></td>');
      ShowHTML('       <td>'.Nvl(f($RS1,'nm_tramite'),'-').'</font></td></tr>');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>L</u>mite orçamentário:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          <td valign="top">');
    ShowHTML('            <table width="99%" border="0">');
    ShowHTML('              <tr><td valign="top"><b>Limite da unidade:</b><br>'.number_format(Nvl($w_limite,0),2,',','.').'</td>');
    $w_limite_tarefa=0;
    //$RS1 = new db_getLinkData; $RS1 = $RS1->getInstanceOf($dbms,$w_cliente,'ISTCAD');
    $sql = new db_getSolicList_IS; $RS1 = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'ISTCAD',3,
            null,null,null,null,null,null,
            $w_sq_unidade,null,null,null,
            null,null,null,null,null,null,null,
            null,null,null,null,null,null,null,null,null,null,$w_ano);
    if (count($RS1)>0) {
      foreach($RS as $row) {
        $sql = new db_getSolicData_IS; $RS2 = $sql->getInstanceOf($dbms,f($RS1,'sq_solic_pai'),'ISACGERAL');
        foreach($RS2 as $row2){$RS2=$row2; break;}
        if (Nvl(f($RS2,'cd_acao'),'')>'') $w_limite_tarefa = $w_limite_tarefa + Nvl(f($RS1,'custo_real'),0);
      } 
    } 
    ShowHTML('                  <td valign="top"><b>Limite utilizado:</b><br>'.number_format(Nvl($w_limite_tarefa,0),2,',','.').'</td>');
    ShowHTML('                  <td valign="top"><b>Saldo disponível:</b><br>'.number_format(number_format(Nvl($w_limite,0),2,',','.')-number_format(Nvl($w_limite_tarefa,0),2,',','.'),2,',','.').'</td>');
    ShowHTML('              </table>');
    ShowHTML('      <tr><td><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2"><hr>');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&O=P&SG='.$SG.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'A');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="'.$w_troca.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,'ISACAD');
    ShowHTML('<tr valign="top">');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação da tarefa na relação.',$w_cliente,$w_ano,null,null,null,null,'w_chave_pai','ACAO','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\'P\'; document.Form.w_troca.value=\'w_chave\'; document.Form.target=\'\'; document.Form.submit();"',$w_chave_pai);
    ShowHTML('<tr valign="top">');
    SelecaoTarefa('<u>T</u>arefa:','T',null,$w_cliente,$w_ano,$w_chave,'w_chave',Nvl($w_chave_pai,0),null);
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
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
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina dos responsaveis
// -------------------------------------------------------------------------
function Responsaveis() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_nm_responsavel = $_REQUEST['w_nm_responsavel'];
  $w_fn_responsavel = $_REQUEST['w_fn_responsavel'];
  $w_em_responsavel = $_REQUEST['w_em_responsavel'];
  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
  } elseif (!(strpos('A',$O)===false)) {
    $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,$SG);
    foreach($RS as $row){$RS=$row; break;}
    $w_nm_responsavel   = f($RS,'nm_responsavel');
    $w_fn_responsavel   = f($RS,'fn_responsavel');
    $w_em_responsavel   = f($RS,'em_responsavel');
    $w_titulo           = f($RS,'titulo');
  } 
  Cabecalho();
  head();
  if (!(strpos('A',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('A',$O)===false)) {
      Validate('w_nm_responsavel','Nome','','1','3','60','1','1');
      Validate('w_fn_responsavel','Telefone','1','','7','20','1','1');
      Validate('w_em_responsavel','Email','','','3','60','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nm_responsavel.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td align="center" colspan=3>&nbsp;');
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Tipo</font></td>');
    ShowHTML('          <td><font size="1"><b>Nome</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td><font size="1">Tarefa</td>');
      ShowHTML('        <td><font size="1">'.f($RS,'titulo').'</td>');
      ShowHTML('        <td align="top" nowrap><font size="1">');
      ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave_aux='.f($RS,'sq_siw_solicitacao').'">Responsável</A>&nbsp');
      ShowHTML('        </td>');
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('A',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><font size="2"><b>Tarefa: </b>'.$w_titulo.' </b>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Responsável pela tarefa: </b>');
    ShowHTML('      <tr><td><font size="1"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nm_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_nm_responsavel.'" title="Informe um responsável pela tarefa."></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>T</u>elefone:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fn_responsavel" class="STI" SIZE="15" MAXLENGTH="14" VALUE="'.$w_fn_responsavel.'" title="Informe o telefone do responsável pela tarefa."></td>');
    ShowHTML('      <tr><td><font size="1"><b><u>E</u>mail:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_em_responsavel" class="STI" SIZE="50" MAXLENGTH="60" VALUE="'.$w_em_responsavel.'" title="Informe o email do responsável pela tarefa."></td>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  if ($w_troca>'') {
    // Se for recarga da página 
    $w_nome     = $_REQUEST['w_nome'];
    $w_descricao= $_REQUEST['w_descricao'];
    $w_caminho  = $_REQUEST['w_caminho'];
  }
  elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceof($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    // Recupera os dados do endereço informado 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceof($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach($RS as $row){$RS=$row; break;}
    $w_nome         = f($RS,'nome');
    $w_descricao    = f($RS,'descricao');
    $w_caminho      = f($RS,'chave_aux');
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Título','1','1','1','255','1','1');
      Validate('w_descricao','Descrição','1','1','1','1000','1','1');
      if ($O=='I') Validate('w_caminho','Arquivo','','1','5','255','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Título</font></td>');
    ShowHTML('          <td><font size="1"><b>Descrição</font></td>');
    ShowHTML('          <td><font size="1"><b>Tipo</font></td>');
    ShowHTML('          <td><font size="1"><b>KB</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>');
        ShowHTML('        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'tipo').'</td>');
        ShowHTML('        <td align="right"><font size="1">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
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
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
      $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    } 
    ShowHTML('      <tr><td><font size="1"><b><u>T</u>ítulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="Informe o tíulo do arquivo."></td>');
    ShowHTML('      <tr><td><font size="1"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="Descreva o conteúdo do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.">');
    if ($w_caminho>'') ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
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
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de visualização
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') $w_logo='/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  if ($w_tipo=='WORD') HeaderWord($_REQUEST['orientacao']); else Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualização de Tarefa</TITLE>');
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo!='WORD') BodyOpenClean(null);
  ShowHTML('<div align="center">');
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<tr><td width="100%"><div align="center"><b><font size="1">Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></font></div>');
  else                               $P4=1;
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  ShowHTML('<tr><td colspan="2">');
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><DIV ALIGN="LEFT"><IMG src="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"></DIV></TD>');
  ShowHTML('<TD><DIV ALIGN="RIGHT"><FONT SIZE=4 COLOR="#000000"><B>');
  ShowHTML('Visualização de Tarefa');
  ShowHTML('</B></FONT></DIV></TD></TR>');
  ShowHTML('</TABLE></TD></TR>');
  // Chama a rotina de visualização dos dados da tarefa, na opção 'Listagem'
  ShowHTML(VisualTarefa($w_chave,'L',$w_usuario,$P4,'sim','sim','sim','sim','sim','sim'));
  ShowHTML('</table>');
  if ($w_tipo>'' && $w_tipo!='WORD') ShowHTML('<div align="center"><b><font size="1">Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></font></div>');
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Rotina de exclusão
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da tarefa, na opção 'Listagem'
  ShowHTML(VisualTarefa($w_chave,'V',$w_usuario,$P4,'sim','sim','sim','nao','sim','nao'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISTAGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,'ISTAGERAL');
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');
  if ($w_troca>'') {
    // Se for recarga da página
    $w_tramite      = $_REQUEST['w_tramite'];
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,'ISTAGERAL');
    foreach($RS as $row){$RS=$row; break;}
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  }
  // Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceof($dbms,$w_novo_tramite);
  foreach($RS as $row){$RS=$row; break;}
  $w_sg_tramite=f($RS,'sigla');
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se não for encaminhamento e nem o sub-menu do cadastramento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da tarefa, na opção 'Listagem'
  ShowHTML(VisualTarefa($w_chave,'V',$w_usuario,$P4,'sim','sim','sim','nao','sim','nao'));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ISTAENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan="2">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento
    SelecaoFase('<u>F</u>ase da tarefa:','F','Se deseja alterar a fase atual da tarefa, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite=='CI') SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a tarefa.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    else                     SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a tarefa.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } else {
    SelecaoFase('<u>F</u>ase da tarefa:','F','Se deseja alterar a fase atual da tarefa, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário para a tarefa.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><font size="1"><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinatário deve fazer quando receber a tarefa.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    // Se não for cadastramento, volta para a listagem
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } elseif ($P1==1 && $w_tipo=='Volta') {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  if ($w_troca>'') {
    // Se for recarga da página
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da tarefa, na opção 'Listagem'
  ShowHTML(VisualTarefa($w_chave,'V',$w_usuario,$P4,'sim','sim','sim','sim','sim','nao'));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=ISTAENVIO&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,'ISTAGERAL');
  foreach($RS as $row){$RS=$row; break;}
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><font size="1"><b>A<u>n</u>otação:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anotação desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
  $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$w_chave,'ISTAGERAL');
  if (count($RS)>0) {
    foreach($RS as $row) {
      $w_tramite = f($row,'sq_siw_tramite');
      if (Nvl(f($row,'cd_acao'),'')>'') {
        $w_custo_real = Nvl(f($row,'custo_real'),0);
        $w_ppa='S';
      } else {
        $w_ppa='N';
      } 
    }
  }
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
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (!(strpos('V',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_inicio_real','Início da execução','DATA',1,10,10,'','0123456789/');
    Validate('w_fim_real','Término da execução','DATA',1,10,10,'','0123456789/');
    CompData('w_inicio_real','Início da execução','<=','w_fim_real','Término da execução');
    CompData('w_fim_real','Término da execução','<=',FormataDataEdicao(time()),'data atual');
    if ($w_ppa=='N') Validate('w_custo_real','Rercurso executado','VALOR','1',4,18,'','0123456789.,');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_inicio_real.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align="center">');
  ShowHTML('<table width="95%" border="0" cellspacing="3">');
  // Chama a rotina de visualização dos dados da tarefa, na opção 'Listagem'
  ShowHTML(VisualTarefa($w_chave,'L',$w_usuario,$P4,'sim','nao','sim','sim','sim','nao'));
  ShowHTML('</table>');
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG=GDCONC&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  ShowHTML('              <td valign="top"><font size="1"><b>Iní<u>c</u>io da execução:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de início da execução da tarefa.(Usar formato dd/mm/aaaa)"></td>');
  ShowHTML('              <td valign="top"><font size="1"><b><u>T</u>érmino da execução:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de término da execução da tarefa.(Usar formato dd/mm/aaaa)"></td>');
  if ($w_ppa=='N') {
    ShowHTML('              <td valign="top"><font size="1"><b><u>R</u>ecurso executado:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor que foi efetivamente gasto com a execução da tarefa."></td>');
  } else {
    ShowHTML('              <td valign="top"><font size="1">&nbsp;</td>');
    ShowHTML('<INPUT type="hidden" name="w_custo_real" value="'.$w_custo_real.'">');
  } 
  ShowHTML('          </table>');
  ShowHTML('      <tr><td valign="top"><font size="1"><b>Nota d<u>e</u> conclusão:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Insira informações relevantes sobre a conclusão da tarefa.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor.">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Concluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de preparação para envio de e-mail relativo a tarefas
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetro: p_solic: número de identificação da solicitação. 
//            p_tipo:  1 - Inclusão
//                     2 - Tramitação
//                     3 - Conclusão
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    $w_html='<HTML>'.$crlf; 
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)       $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUSÃO DE TAREFA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITAÇÃO DE TAREFA</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUSÃO DE TAREFA</b></font><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
    // Recupera os dados da tarefa
    $sql = new db_getSolicData_IS; $RSM = $sql->getInstanceOf($dbms,$p_solic,'ISTAGERAL');
    foreach($RSM as $row){$RSM=$row; break;}
    $w_nome='Tarefa '.f($RSM,'sq_siw_solicitacao');
    $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td valign="top"><font size="1">Tarefa: <b>'.f($RSM,'titulo').'</b></td>';
    $w_html.=$crlf.'      <tr><td valign="top"><font size="1">Ação: <b>'.f($RSM,'nm_projeto').'</b></td>';
    $w_html.=$crlf.'      <tr><td><font size=1>Detalhamento: <b>'.CRLF2BR(f($RSM,'assunto')).'</b></font></td></tr>';
    // Identificação da tarefa
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>EXTRATO DA TAREFA</td>';
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td><font size="1">Responsável pelo monitoramento:<br><b>'.f($RSM,'nm_sol').'</b></td>';
    $w_html.=$crlf.'          <td><font size="1">Área planejamento:<br><b>'.f($RSM,'nm_unidade_resp').'</b></td>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td><font size="1">Data de recebimento:<br><b>'.FormataDataEdicao(f($RSM,'inicio')).' </b></td>';
    $w_html.=$crlf.'          <td><font size="1">Limite para conclusão:<br><b>'.FormataDataEdicao(f($RSM,'fim')).' </b></td>';
    $w_html.=$crlf.'          <td><font size="1">Prioridade:<br><b>'.RetornaPrioridade(f($RSM,'prioridade')).' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Informações adicionais
    if (Nvl(f($RSM,'descricao'),'')>'') $w_html.=$crlf.'      <tr><td valign="top"><font size="1">Resultados da tarefa:<br><b>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
    $w_html.=$crlf.'    </table>';
    $w_html.=$crlf.'</tr>';
    // Dados da conclusão da tarefa, se ela estiver nessa situação
    if (f($RSM,'concluida')=='S' && Nvl(f($RSM,'data_conclusao'),'')>'') {
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>DADOS DA CONCLUSÃO</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td><font size="1">Início da execução:<br><b>'.FormataDataEdicao(f($RSM,'inicio_real')).' </b></td>';
      $w_html.=$crlf.'          <td><font size="1">Término da execução:<br><b>'.FormataDataEdicao(f($RSM,'fim_real')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      $w_html.=$crlf.'      <tr><td valign="top"><font size="1">Nota de conclusão:<br><b>'.CRLF2BR(f($RSM,'nota_conclusao')).' </b></td>';
    } 
    if ($p_tipo==2) {
      // Se for tramitação
      // Encaminhamentos
      $sql = new db_getSolicLog; $RS = $sql->getInstanceof($dbms,$p_solic,null,null,'LISTA');   
      $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
      foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>ÚLTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf.'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td><font size="1">De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html.=$crlf.'          <td><font size="1">Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html.=$crlf.'          <tr valign="top"><td colspan=2><font size="1">Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      // Configura o destinatário da tramitação como destinatário da mensagem
      $sql = new db_getPersonData; $RS = $sql->getInstanceof($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>OUTRAS INFORMAÇÕES</td>';
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Para acessar o sistema use o endereço: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </font></td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><font size=2>'.$crlf;
    $w_html.='         Dados da ocorrência:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html .= '         <li>Responsável: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .= '         <li>Data do servidor: <b>'.date('d/m/Y, H:i:s',toDate(time())).'</b></li>'.$crlf;
    $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html.='         </ul>'.$crlf;
    $w_html.='      </font></td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;
    // Recupera o e-mail do responsável
    if(f($RSM,'st_sol')=='S') {
      $sql = new db_getPersonData; $RS = $sql->getInstanceof($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor responsável
    $sql = new db_getUorgResp; $RS = $sql->getInstanceof($dbms,f($RSM,'sq_unidade'));
    foreach ($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
    if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
    // Prepara os dados necessários ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclusão ou Conclusão
      if ($p_tipo==1) $w_assunto='Inclusão - '.$w_nome;
      else            $w_assunto='Conclusão - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramitação
      $w_assunto='Tramitação - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATENÇÃO: não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    }
  }
}
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen(null);
  switch ($SG) {
    case 'ISTAGERAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Se for operação de exclusão, verifica se é necessário excluir os arquivos físicos
        if ($O=='E') {
          $sql = new db_getSolicLog; $RS = $sql->getInstanceof($dbms,$_REQUEST['w_chave'],null,null,'LISTA');
          // Mais de um registro de log significa que deve ser cancelada, e não excluída.
          // Nessa situação, não é necessário excluir os arquivos.
          if (count($RS)<=1) {
            $sql = new db_getSolicAnexo; $RS = $sql->getInstanceof($dbms,$_REQUEST['w_chave'],null,$w_cliente);
            foreach($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            } 
          } 
        } 
        //Recupera 10  dos dias de prazo da tarefa, para emitir o alerta  
        $sql = new db_get10PercentDays_IS; $RS = $sql->getInstanceof($dbms,$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        foreach($RS as $row){$RS=$row; break;}
        if ($w_dias<1) $w_dias=1;
        $w_dias = f($RS,'dias');
        $SQL = new dml_putTarefaGeral; $SQL->getInstanceOf($dbms,$O,
            $_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],$_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],
            $w_usuario,null,$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_ordem'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_valor'],
            $_REQUEST['w_data_hora'],$_REQUEST['w_sq_unidade_resp'],$_REQUEST['w_titulo'],$_REQUEST['w_assunto'],$_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$w_dias,
            $_REQUEST['w_cidade'],$_REQUEST['w_palavra_chave'],null,null,null,null,null,null,null,
            $_REQUEST['w_projeto'],$_REQUEST['w_atividade'],$_REQUEST['w_projeto_ant'],$_REQUEST['w_atividade_ant'],&$w_chave_nova,$w_copia);
        ScriptOpen('JavaScript');
        if ($O=='I') {
          // Exibe mensagem de gravação com sucesso
          ShowHTML('  alert(\'Tarefa '.$w_chave_nova.' cadastrada com sucesso!\');');
          // Recupera os dados para montagem correta do menu
          $RS1 = new db_getMenuData; $RS1 = $RS1->getInstanceOf($dbms,$w_menu);
          ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET')).'\';');
        } elseif ($O=='E') {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        } else {
          // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          $RS1 = new db_getLinkData; $RS1 = $RS1->getInstanceOf($dbms,$w_cliente,$SG);
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        } 
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
    break;
    case 'VLRTGERAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putTarefaLimite; $SQL->getInstanceOf($dbms,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_sq_tramite'],$_REQUEST['w_valor']);
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = new db_getLinkData; $RS = $RS->getInstanceOF($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=P&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
    break;
    case 'ISTARESP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putRespTarefa_IS; $SQL->getInstanceOf($dbms,$_REQUEST['w_chave'],
         $_REQUEST['w_nm_responsavel'],$_REQUEST['w_fn_responsavel'],$_REQUEST['w_em_responsavel']);
         ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu
        $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
    break;
    case 'ISTAANEXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
       if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            $w_tamanho = $Field['size'];            
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se já há um nome para o arquivo, mantém 
              if ($_REQUEST['w_atual']>'') {
                $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
                foreach ($RS as $row) {
                  if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                  if (!(strpos(f($row,'caminho'),'.')===false)) {
                    $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
                  } else {
                    $w_file = basename(f($row,'caminho'));
                  }
                }
              } else {
                $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
               if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
                }
              } 
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }elseif(nvl($Field['name'],'')!=''){
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Atenção: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
              ScriptClose();
              retornaFormulario('w_caminho');
              exit();
            } 
          } 
          // Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
          if ($O=='E' && $_REQUEST['w_atual']>'') {
            $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
            foreach ($RS as $row) {
              if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
            }
          } 
        $SQL = new dml_putSolicArquivo; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
          ScriptClose();
          exit();
        } 
        ScriptOpen('JavaScript');
        // Recupera a sigla do serviço pai, para fazer a chamada ao menu 
        $RS = new db_getLinkData; $RS = $RS->getInstanceOf($dbms,$w_cliente,$SG);
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
    break;
    case 'ISTAENVIO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        // Trata o recebimento de upload ou dados 
        if ((false!==(strpos(upper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(upper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
          // Se foi feito o upload de um arquivo 
          if (UPLOAD_ERR_OK==0) {
            $w_maximo = $_REQUEST['w_upload_maximo'];
            foreach ($_FILES as $Chv => $Field) {
              if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              if ($Field['size'] > 0) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                if ($Field['size'] > $w_maximo) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_observacao');
                  exit();
                } 
                // Se já há um nome para o arquivo, mantém 
                $w_file = basename($Field['tmp_name']);
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
                }
                $w_tamanho = $Field['size'];
                $w_tipo    = $Field['type'];
                $w_nome    = $Field['name'];
                if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
              } 
            } 
            $SQL = new dml_putDemandaEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
                $w_file,$w_tamanho,$w_tipo,$w_nome);
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!\');');
            ScriptClose();
          } 
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
          ScriptClose();
        } else {
          $SQL = new dml_putDemandaEnvio; $SQL->getInstanceof($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
            $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
            null,null,null,null);
          // Envia e-mail comunicando de tramitação
          SolicMail($_REQUEST['w_chave'],2);
          if ($P1==1) {
            // Se for envio da fase de cadastramento, remonta o menu principal
            // Recupera os dados para montagem correta do menu
            $RS = new db_getMenuData; $RS = $RS->getInstanceOf($dbms,$w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET')).'\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
            ScriptClose();
          } 
        } 
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
    break;
    case 'GDCONC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $sql = new db_getSolicData_IS; $RS = $sql->getInstanceof($dbms,$_REQUEST['w_chave'],'ISTAGERAL');
        foreach($RS as $row){$RS=$row; break;}
        if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATENÇÃO: Outro usuário já encaminhou esta tarefa para outra fase de execução!\');');
          ScriptClose();
        } else {
          // Se foi feito o upload de um arquivo  
          if (UPLOAD_ERR_OK==0) {
            $w_maximo = $_REQUEST['w_upload_maximo'];
            foreach ($_FILES as $Chv => $Field) {
              if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              }
              if ($Field['size'] > 0) {
                // Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                if ($Field['size'] > $w_maximo) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'Atenção: o tamanho máximo do arquivo não pode exceder '.($w_maximo/1024).' KBytes!\');');
                  ScriptClose();
                  retornaFormulario('w_observacao');
                  exit();
                } 
                // Se já há um nome para o arquivo, mantém 
                $w_file = basename($Field['tmp_name']);
                if (!(strpos($Field['name'],'.')===false)) {
                  $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
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
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          } 
          $SQL = new dml_putDemandaConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],$_REQUEST['w_custo_real'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          // Envia e-mail comunicando a conclusão
          SolicMail($_REQUEST['w_chave'],3);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
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
    case 'INICIAL': Inicial();      break;
    case 'GERAL':   Geral();        break;
    case 'LIMITE':  Limite();       break;
    case 'RESP':    Responsaveis(); break;
    case 'ANEXO':   Anexos();       break;
    case 'VISUAL':  Visual();       break;
    case 'EXCLUIR': Excluir();      break;
    case 'ENVIO':   Encaminhamento();break;
    case 'TRAMITE': Tramitacao();   break;
    case 'ANOTACAO':Anotar();       break;
    case 'CONCLUIR':Concluir();     break;
    case 'GRAVA':   Grava();        break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
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
  } 
}
?>