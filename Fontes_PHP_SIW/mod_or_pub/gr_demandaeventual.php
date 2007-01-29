<?
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
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
// =========================================================================
//  /gr_demandaeventual.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal do Santos
// Descricao: Gerencia o módulo de demandas
// Mail     : billy@sbpi.com.br
// Criacao  : 16/09/2006 15:35
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Geração de gráfico
//                   = W   : Geração de documento no formato MS-Word (Office 2003)
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$w_troca        = $_REQUEST['w_troca'];
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_tipo         = strtoupper($_REQUEST['p_tipo']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);
$p_unidade      = strtoupper($_REQUEST['p_unidade']);
$p_proponente   = strtoupper($_REQUEST['p_proponente']);
$p_ordena       = strtoupper($_REQUEST['p_ordena']);
$p_ini_i        = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f        = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i        = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f        = strtoupper($_REQUEST['p_fim_f']);
$p_atraso       = strtoupper($_REQUEST['p_atraso']);
$p_chave        = strtoupper($_REQUEST['p_chave']);
$p_assunto      = strtoupper($_REQUEST['p_assunto']);
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);
$p_agrega       = strtoupper($_REQUEST['p_agrega']);
$p_tamanho      = strtoupper($_REQUEST['p_tamanho']);
$par            = strtoupper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = strtoupper($_REQUEST['SG']);
$R              = strtoupper($_REQUEST['R']);
$O              = strtoupper($_REQUEST['O']);
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_demandaeventual.php?par=';
$w_dir          = 'mod_or_pub/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';
if ($O=='') $O='P';
switch ($O) {
  case 'V':    $w_TP=$TP.' - Gráfico';      break;
  case 'P':    $w_TP=$TP.' - Filtragem';    break;
  default:     $w_TP=$TP.' - Listagem';     break;
} 
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado. 
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu    = $P2;
// Recupera a configuração do serviço
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='L' || $O=='V' || $O=='W') {
    $w_filtro='';
    if ($p_projeto>'') {
      $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Ação <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_sqcc>'') {
      $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Classificação <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Demanda nº <td>[<b>'.$p_chave.'</b>]';
    if ($p_prazo>'')      $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Prazo para conclusão até<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';  
    if ($p_solicitante>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>''){
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade responsável <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'')  $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';     
    if ($p_proponente>'')  $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Parceria externa <td>[<b>'.$p_proponente.'</b>]';   
    if ($p_assunto>'')     $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_assunto.'</b>]'; 
    if ($p_palavra>'')     $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Responsável <td>[<b>'.$p_palavra.'</b>]';    
    if ($p_ini_i>'')       $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; 
    if ($p_fim_i>'')       $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Limite conclusão <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S')    $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Situação <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'')      $w_filtro = '<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';
    switch ($p_agrega) {
      case 'GRDMPROJ':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
                $w_TP=$TP.' - Por projeto';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
        break;
      case 'GRDMPROP':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,'GRDMPROP',5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
                $w_TP=$TP.' - Por proponente';
        $RS1 = SortArray($RS1,'proponente','asc');
        break;
      case 'GRDMRESP':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
                $w_TP=$TP.' - Por responsável';
        $RS1 = SortArray($RS1,'nm_solic','asc');
        break;
      case 'GRDMRESPATU':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,'GRDMRESPATU',5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
                $w_TP=$TP.' - Por executor';
        $RS1 = SortArray($RS1,'nm_exec','asc');
        break;
      case 'GRDMCC':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente);
                $w_TP=$TP.' - Por classificação';
        $RS1 = SortArray($RS1,'sg_cc','asc');
        break;
      case 'GRDMSETOR':
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
                $w_TP=$TP.' - Por setor responsável';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRDMPRIO':
        $w_TP=$TP.' - Por prioridade';
        $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null,null,null);
        $RS1 = SortArray($RS1,'nm_prioridade','asc');
        break;
      case 'GRDMAREA':
        $w_TP=$TP.' - Por área envolvida';
        $RS1 = db_getSolicGRA::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,5,
                $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
                $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
                $p_chave,$p_assunto,null,null,null,null,$p_usu_resp,
                $p_uorg_resp,$p_palavra,$p_prazo,$p_fase,$p_sqcc,$p_projeto,null);
        $RS1 = SortArray($RS1,'nm_envolv','asc');
        break;
    } 
  } 
  if ($O=='W') {
    HeaderWord(null);
    $w_pag   = 1;
    $w_linha = 0;
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);    
  } else {
    Cabecalho();
    ShowHTML('<HEAD>');
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      ValidateOpen('Validacao');
      Validate('p_chave','Número da demanda','','','1','18','','0123456789');
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
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Você deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
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
      if ($SG=='PROJETO') {
        ShowHTML('  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value == \'GRDMETAPA\' && theForm.p_projeto.selectedIndex == 0) {');
        ShowHTML('     alert (\'A agregação por etapa exige a seleção de um projeto!\');');
        ShowHTML('     theForm.p_projeto.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      } 
      ValidateClose();
      ScriptClose();
    } else ShowHTML('<TITLE>'.$w_TP.'</TITLE>'); 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
    } elseif (!(strpos('P',$O)===false)) {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=this.focus();');
    } 
    if ($O=='L') {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      if ($w_filtro>'') ShowHTML($w_filtro);   
    } else {
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
    } 
  } 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L' || $O=='W') {
    if ($O=='L') {
      ShowHTML('<tr><td>');
      if (MontaFiltro('GET')>'') ShowHTML('                        <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      else                       howHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');  
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRDMPROJ':            ShowHTML('      document.Form.p_projeto.value=filtro;');            break;
          case 'GRDMPROP':            ShowHTML('      document.Form.p_proponente.value=filtro;');         break;
          case 'GRDMRESP':            ShowHTML('      document.Form.p_solicitante.value=filtro;');        break;
          case 'GRDMRESPATU':         ShowHTML('      document.Form.p_usu_resp.value=filtro;');           break;
          case 'GRDMCC':              ShowHTML('      document.Form.p_sqcc.value=filtro;');               break;
          case 'GRDMSETOR':           ShowHTML('      document.Form.p_unidade.value=filtro;');            break;
          case 'GRDMPRIO':            ShowHTML('      document.Form.p_prioridade.value=filtro;');         break;
          case 'GRDMAREA':            ShowHTML('      document.Form.p_area.value=filtro;');               break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRDMPROJ':            ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');            break;
          case 'GRDMPROP':            ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');      break;
          case 'GRDMRESP':            ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';');    break;
          case 'GRDMRESPATU':         ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');          break;
          case 'GRDMCC':              ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');                  break;
          case 'GRDMSETOR':           ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');            break;
          case 'GRDMPRIO':            ShowHTML('    else document.Form.p_prioridade.value=\''.$_REQUEST['p_prioridade'].'\';');      break;
          case 'GRDMAREA':            ShowHTML('    else document.Form.p_area.value=\''.$_REQUEST['p_area'].'\';');                  break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec = '';
        foreach($RS2 as $row2) {
          if (f($row2,'sigla')=='CI')        $w_fase_cad  = f($row2,'sq_siw_tramite');
          elseif (f($row2,'sigla')=='AT')    $w_fase_conc = f($row2,'sq_siw_tramite');
          elseif (f($row2,'ativo')=='S')     $w_fase_exec = $w_fase_exec.','.f($row2,'sq_siw_tramite');
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        ShowHTML('<BASE HREF="'.$conRootSIW.'">');
        $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));  
        switch ($p_agrega) {
          case 'GRDMPROJ':            if ($_REQUEST['p_projeto']=='')     ShowHTML('<input type="Hidden" name="p_projeto" value="">');            break;
          case 'GRDMPROP':            if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');         break;
          case 'GRDMRESP':            if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');        break;
          case 'GRDMRESPATU':         if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');           break;
          case 'GRDMCC':              if ($_REQUEST['p_sqcc']=='')        ShowHTML('<input type="Hidden" name="p_sqcc" value="">');               break;
          case 'GRDMSETOR':           if ($_REQUEST['p_unidade']=='')     ShowHTML('<input type="Hidden" name="p_unidade" value="">');            break;
          case 'GRDMPRIO':            if ($_REQUEST['p_prioridade']=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');         break;
          case 'GRDMAREA':            if ($_REQUEST['p_area']=='')        ShowHTML('<input type="Hidden" name="p_area" value="">');               break;
        } 
      } 
      $w_nm_quebra  = '';
      $w_qt_quebra  = 0.00;
      $t_solic      = 0.00;
      $t_cad        = 0.00;
      $t_tram       = 0.00;
      $t_conc       = 0.00;
      $t_atraso     = 0.00;
      $t_aviso      = 0.00;
      $t_valor      = 0.00;
      $t_acima      = 0.00;
      $t_custo      = 0.00;
      $t_totcusto   = 0.00;
      $t_totsolic   = 0.00;
      $t_totcad     = 0.00;
      $t_tottram    = 0.00;
      $t_totconc    = 0.00;
      $t_totatraso  = 0.00;
      $t_totaviso   = 0.00;
      $t_totvalor   = 0.00;
      $t_totacima   = 0.00;
      foreach ($RS1 as $row) {
        switch ($p_agrega) {
          case 'GRDMPROJ':            if ($w_nm_quebra!=f($row,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));
              } 
              $w_nm_quebra  = f($row,'nm_projeto');
              $w_chave      = f($row,'sq_solic_pai');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMPROP':
            if ($w_nm_quebra!=f($row,'proponente')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));
              } 
              $w_nm_quebra  = f($row,'proponente');
              $w_chave      = f($row,'proponente');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMRESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));
              } 
              $w_nm_quebra  = f($row,'nm_solic');
              $w_chave      = f($row,'solicitante');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+= 1;
            } 

            break;
          case 'GRDMRESPATU':
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));
              } 
              $w_nm_quebra  = f($row,'nm_exec');
              $w_chave      = f($row,'executor');
              $w_qt_quebra  = 0.00 ;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMCC':
            if ($w_nm_quebra!=f($row,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));
              } 
              $w_nm_quebra  = f($row,'sg_cc');
              $w_chave      = f($row,'sq_cc');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMSETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              } 
              $w_nm_quebra  = f($row,'nm_unidade_resp');
              $w_chave      = f($row,'sq_unidade_resp');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMPRIO':
            if ($w_nm_quebra!=f($row,'nm_prioridade')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prioridade'));
              } 
              $w_nm_quebra  = f($row,'nm_prioridade');
              $w_chave      = f($row,'prioridade');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
          case 'GRDMAREA':
            if ($w_nm_quebra!=f($row,'nm_envolv')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_envolv'));
              } 
              $w_nm_quebra  = f($row,'nm_envolv');
              $w_chave      = f($row,'sq_unidade');
              $w_qt_quebra  = 0.00;
              $t_solic      = 0.00;
              $t_cad        = 0.00;
              $t_tram       = 0.00;
              $t_conc       = 0.00;
              $t_atraso     = 0.00;
              $t_aviso      = 0.00;
              $t_valor      = 0.00;
              $t_acima      = 0.00;
              $t_custo      = 0.00;
              $w_linha+=1;
            } 
            break;
        } 
        if ($O=='W' && $w_linha>25) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=0;
          $w_pag+=1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);          
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRDMPROJ':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_projeto'));           break;
            case 'GRDMPROP':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));           break;
            case 'GRDMRESP':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));             break;
            case 'GRDMRESPATU':   ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));              break;
            case 'GRDMCC':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));                break;
            case 'GRDMSETOR':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));      break;
            case 'GRDMPRIO':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prioridade'));        break;
            case 'GRDMAREA':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_envolv'));            break;
          } 
          $w_linha+=1;
        } 
        if (f($row,'concluida')=='N') {
          if (f($row,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row,'aviso_prox_conc') == 'S' && (f($row,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row,'or_tramite')==1) {
            $t_cad      = $t_cad+1;
            $t_totcad   = $t_totcad+1;
          } else {
            $t_tram     = $t_tram+1;
            $t_tottram  = $t_tottram+1;
          } 
        } else {
          $t_conc       =   $t_conc+1;
          $t_totconc    =   $t_totconc+1;
          if (Nvl(f($row,'valor'),0)<Nvl(f($row,'custo_real'),0)) {
            $t_acima=$t_acima+1;
            $t_totacima=$t_totacima+1;
          }
        } 
        $t_solic    = $t_solic + 1;
        $t_valor    = $t_valor + Nvl(f($row,'valor'),0);
        $t_custo    = $t_custo + Nvl(f($row,'custo_real'),0);
        $t_totvalor = $t_totvalor + Nvl(f($row,'valor'),0);
        $t_totcusto = $t_totcusto + Nvl(f($row,'custo_real'),0);
        $t_totsolic = $t_totsolic + 1;
        $w_qt_quebra= $w_qt_quebra + 1;
      }
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    } 
    ShowHTML('      </FORM>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_tipo=='N') {
      // Coloca o gráfico somente se o usuário desejar
      ShowHTML('<tr><td align="center" height=20>');
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0)  ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">'); 
    } 
  } elseif (!(strpos('P',$O)===false)) {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" >');
    //If p_agrega = 'GRDMAREA'        Then                             ShowHTML ' <option value=''GRDMAREA'' selected>Área envolvida'                   Else ShowHTML ' <option value=''GRDMAREA''>Área envolvida'                    End If
    if (f($RS_Menu,'solicita_cc')=='S') {
      if ($p_agrega=='GRDMCC')                  ShowHTML(' <option value="GRDMCC" selected>Classificação');           
      else                                      ShowHTML(' <option value="GRDMCC">Classificação');
    }
    if ($p_agrega=='GRDMPRIO')                  ShowHTML(' <option value="GRDMPRIO" selected>Prioridade');   
    else                                        ShowHTML(' <option value="GRDMPRIO">Prioridade');
    if ($p_agrega=='GRDMRESPATU')               ShowHTML(' <option value="GRDMRESPATU" selected>Executor');   
    else                                        ShowHTML(' <option value="GRDMRESPATU">Executor');
    if ($p_agrega=='GRDMPROP')                  ShowHTML(' <option value="GRDMPROP" selected>Parceria externa');     
    else                                        ShowHTML(' <option value="GRDMPROP">Parceria externa');
    if (Nvl($p_agrega,'GRDMRESP')=='GRDMRESP')  ShowHTML(' <option value="GRDMRESP" selected>Responsável monitoramento');     
    else                                        ShowHTML(' <option value="GRDMRESP">Responsável pelo monitoramento');
    if ($p_agrega=='GRDMSETOR')                 ShowHTML(' <option value="GRDMSETOR" selected>Setor responsável monitoramento');     
    else                                        ShowHTML(' <option value="GRDMSETOR">Setor responsável monitoramento');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_tipo,'p_tipo');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORCAD');
    SelecaoProjeto('Açã<u>o</u>:','O','Selecione a ação da tarefa na relação.',$p_projeto,$w_usuario,f($RS,'sq_menu'),'p_projeto','PJLIST',null);
    ShowHTML('</table>');
    if (f($RS_Menu,'solicita_cc')=='S') {
      ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
      ShowHTML('          </table>');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>Número da tare<U>f</U>a:<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Re<u>s</u>ponsável monitoramento:','S','Selecione o responsável pelo monitoramento na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('Setor responsável monitoramento:','Y',null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor da tarefa na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('<U>S</U>etor atual:','Y','Selecione a unidade onde a tarefa se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta demanda.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td valign="top"><b>Pa<U>r</U>ceria externa:<br><INPUT ACCESSKEY="R" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Detalha<U>m</U>ento:<br><INPUT ACCESSKEY="M" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td valign="top" colspan=2><b>R<U>e</U>sponsável:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('          <td valign="top"><b>Lim<u>i</u>te para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="I" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Exibe somente tarefas em atraso?</b><br>');
    if ($p_atraso=='S')    ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    else                   ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não'); 
    SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir" onClick="document.Form.target=\'\'; javascript:document.Form.O.value=\'L\';">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gerar Word" onClick="javascript:document.Form.O.value=\'W\'; document.Form.target=\'Word\'">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('</table>');
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
// Rotina de impressao do cabecalho
// -------------------------------------------------------------------------
function ImprimeCabecalho() {
  extract($GLOBALS);
  global $w_Disabled;
  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega)  {
    case 'GRDMPROJ':      ShowHTML('          <td><b>Projeto</td>');           break;
    case 'GRDMPROP':      ShowHTML('          <td><b>Proponente</td>');        break;
    case 'GRDMRESP':      ShowHTML('          <td><b>Responsável</td>');       break;
    case 'GRDMRESPATU':   ShowHTML('          <td><b>Executor</td>');          break;
    case 'GRDMCC':        ShowHTML('          <td><b>Classificação</td>');     break;
    case 'GRDMSETOR':     ShowHTML('          <td><b>Setor responsável</td>'); break;
    case 'GRDMPRIO':      ShowHTML('          <td><b>Prioridade</td>');        break;
    case 'GRDMAREA':      ShowHTML('          <td><b>Área envolvida</td>');    break;
    case 'GRDMINTER':     ShowHTML('          <td><b>Interessado</td>');       break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cad.</td>');
  ShowHTML('          <td><b>Exec.</td>');
  ShowHTML('          <td><b>Conc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  ShowHTML('          <td><b>Aviso</td>');
  ShowHTML('          <td><b>$ Prev.</td>');
  ShowHTML('          <td><b>$ Real</td>');
  ShowHTML('          <td><b>Real > Previsto</td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='L')                 ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');   
  else                         ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $O=='L')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');   
  else                         ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $O=='L')    ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');   
  else                         ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $O=='L')    ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');   
  else                         ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $O=='L')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</font></td>');   
  else                         ShowHTML('          <td align="right"><b>'.$l_atraso.'&nbsp;</td>');
  if ($l_aviso>0 && $O=='L')   ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</font></td>');   
  else                         ShowHTML('          <td align="right"><b>'.$l_aviso.'&nbsp;</td>');
  ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</td>');
  ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</td>');
  if ($l_acima>0)              ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_acima,0,',','.').'&nbsp;</font></td>');   
  else                         ShowHTML('          <td align="right"><b>'.$l_acima.'&nbsp;</td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch($par)  {
    case 'GERENCIAL':      Gerencial();      break;
    default:
      Cabecalho();
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
      break;
  } 
} 
?>