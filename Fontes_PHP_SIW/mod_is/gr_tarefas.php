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
include_once($w_dir_volta.'classes/sp/db_getSolicList_IS.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'funcoes/selecaoAcao.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade_IS.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
// =========================================================================
//  /gr_tarefas.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia o módulo de programas
// Mail     : celso@sbpi.com.br
// Criacao  : 17/08/2006 10:00
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
$w_pagina       = 'gr_tarefas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_is/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = strtolower($_REQUEST['p_ordena']);
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;
$w_ano      = RetornaAno();
// Declaração de variáveis
$p_projeto          = strtoupper($_REQUEST['p_projeto']);
$p_tipo             = strtoupper($_REQUEST['p_tipo']);
$p_ativo            = strtoupper($_REQUEST['p_ativo']);
$p_solicitante      = strtoupper($_REQUEST['p_solicitante']);
$p_prioridade       = strtoupper($_REQUEST['p_prioridade']);
$p_unidade          = strtoupper($_REQUEST['p_unidade']);
$p_proponente       = strtoupper($_REQUEST['p_proponente']);
$p_ini_i            = strtoupper($_REQUEST['p_ini_i']);
$p_ini_f            = strtoupper($_REQUEST['p_ini_f']);
$p_fim_i            = strtoupper($_REQUEST['p_fim_i']);
$p_fim_f            = strtoupper($_REQUEST['p_fim_f']);
$p_atraso           = strtoupper($_REQUEST['p_atraso']);
$p_chave            = strtoupper($_REQUEST['p_chave']);
$p_assunto          = strtoupper($_REQUEST['p_assunto']);
$p_usu_resp         = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp        = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra          = strtoupper($_REQUEST['p_palavra']);
$p_prazo            = strtoupper($_REQUEST['p_prazo']);
$p_fase             = explodeArray($_REQUEST['p_fase']);
$p_tamanho          = strtoupper($_REQUEST['p_tamanho']);
$p_agrega           = strtoupper($_REQUEST['p_agrega']);
if ($O=='') $O='P';
switch ($O) {
  case 'V': $w_TP=$TP.' - Gráfico';     break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
$w_cliente=RetornaCliente();
$w_usuario=RetornaUsuario();
$w_menu=$P2;
$w_ano=RetornaAno();
// Recupera a configuração do serviço
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
  
  $w_pag   = 1;
  $w_linha = 0;
  
  if ($O=='L' || $O=='V' || $O=='W' || $p_tipo=='WORD' || $p_tipo=='PDF') {
    $w_filtro='';
    if ($p_projeto>'') {
      $w_linha++;
      $RS = db_getSolicData_IS::getInstanceOf($dbms,$p_projeto,'ISACGERAL');
      foreach($RS as $row){$RS=$row; break;}
      $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
      if (Nvl(f($RS,'cd_acao'),'')>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Ação <td><font size=1>[<b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS2,'sigla').'" title="Exibe as informações da ação.">'.f($RS,'cd_unidade').'.'.f($RS,'cd_programa').'.'.f($RS,'cd_acao').' - '.f($RS,'nm_ppa').' ('.f($RS,'ds_unidade').')</a></b>]';
      else                             $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Ação <td><font size=1>[<b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS2,'sigla').'" title="Exibe as informações da ação.">'.f($RS,'titulo').'</a></b>]';
    } 
    if ($p_chave>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Demanda nº <td><font size=1>[<b>'.$p_chave.'</b>]'; }
    if ($p_prazo>'') { $w_linha++; $w_filtro=$w_filtro.' <tr valign="top"><td align="right"><font size=1>Prazo para conclusão até<td><font size=1>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]'; }
    if ($p_solicitante>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Responsável <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade responsável <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $w_linha++;
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Executor <td><font size=1>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $w_linha++;
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Unidade atual <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Prioridade <td><font size=1>[<b>'.RetornaPrioridade($p_prioridade).'</b>]'; }
    if ($p_proponente>'') { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Parceria externa <td><font size=1>[<b>'.$p_proponente.'</b>]'; }
    if ($p_assunto>'')    { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Detalhamento <td><font size=1>[<b>'.$p_assunto.'</b>]'; }
    if ($p_palavra>'')    { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Responsável <td><font size=1>[<b>'.$p_palavra.'</b>]'; }
    if ($p_ini_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Data recebimento <td><font size=1>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]'; }
    if ($p_fim_i>'')      { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Limite conclusão <td><font size=1>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]'; }
    if ($p_atraso=='S')   { $w_linha++; $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]'; }
    if ($w_filtro>'')     { $w_linha++; $w_filtro='<table border=0><tr valign="top"><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>'.$w_filtro.'</ul></tr></table>'; }
    $RS1 = db_getSolicList_IS::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,3,
            $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
            $p_unidade,$p_prioridade,$p_ativo,$p_proponente,$p_chave,$p_assunto,
            null,null,null,null,$p_usu_resp,$p_uorg_resp,$p_palavra,
            $p_prazo,$p_fase,$p_projeto,null,null,null,null,null,$w_ano);
    switch ($p_agrega) {
      case 'GRISTACAO':
        $w_TP=$TP.' - Por ação';
        $RS1 = SortArray($RS1,'nm_projeto','asc');
      break;
      case 'GRISTPROP':
        $w_TP=$TP.' - Por proponente';
        $RS1 = SortArray($RS1,'proponente','asc');
      break;
      case 'GRISTRESP':
        $w_TP=$TP.' - Por responsável';
        $RS1 = SortArray($RS1,'nm_solic','asc');
      break;
      case 'GRISTRESPATU':
        $w_TP=$TP.' - Por executor';
        $RS1 = SortArray($RS1,'nm_exec','asc');
      break;
      case 'GRISTSETOR':
        $w_TP=$TP.' - Por setor responsável';
        $RS1 = SortArray($RS1,'nm_unidade_resp','asc');
      break;
      case 'GRISTPRIO':
        $w_TP=$TP.' - Por prioridade';
        $RS1 = SortArray($RS1,'nm_prioridade','asc');
      break;
    } 
  } 
  $w_linha_filtro = $w_linha;
  if ($p_tipo == 'WORD') {
    HeaderWord($_REQUEST['orientacao']);
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 45: 30);
    CabecalhoWord($w_cliente,$w_TP,$w_pag);
    $w_embed = 'WORD';
    if ($w_filtro>'') ShowHTML($w_filtro);
  }elseif($p_tipo == 'PDF'){
    $w_linha_pag = ((nvl($_REQUEST['orientacao'],'PORTRAIT')=='PORTRAIT') ? 60: 35);
    $w_embed = 'WORD';
    HeaderPdf('Consulta de '.f($RS_Menu,'nome'),$w_pag);
    if ($w_filtro>'') ShowHTML($w_filtro);
  } else {
    $w_embed = 'HTML';
    Cabecalho();
    ShowHTML('<HEAD>');
    if ($O=='P') {
      ScriptOpen('Javascript');
      CheckBranco();
      FormataData();
      SaltaCampo();
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
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'') {
      // Se for recarga da página
      BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
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
      ShowHTML('<tr><td><font size="1">');
      if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
      else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRISTACAO':     ShowHTML('      document.Form.p_projeto.value=filtro;');    break;
          case 'GRISTPROP':     ShowHTML('      document.Form.p_proponente.value=filtro;'); break;
          case 'GRISTRESP':     ShowHTML('      document.Form.p_solicitante.value=filtro;');break;
          case 'GRISTRESPATU':  ShowHTML('      document.Form.p_usu_resp.value=filtro;');   break;
          case 'GRISTSETOR':    ShowHTML('      document.Form.p_unidade.value=filtro;');    break;
          case 'GRISTPRIO':     ShowHTML('      document.Form.p_prioridade.value=filtro;'); break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRISTACAO':     ShowHTML('    else document.Form.p_projeto.value=\''.$p_projeto.'\';');         break;
          case 'GRISTPROP':     ShowHTML('    else document.Form.p_proponente.value="'.$p_proponente.'";');     break;
          case 'GRISTRESP':     ShowHTML('    else document.Form.p_solicitante.value=\''.$p_solicitante.'\';'); break;
          case 'GRISTRESPATU':  ShowHTML('    else document.Form.p_usu_resp.value=\''.$p_usu_resp.'\';');       break;
          case 'GRISTSETOR':    ShowHTML('    else document.Form.p_unidade.value=\''.$p_unidade.'\';');         break;
          case 'GRISTPRIO':     ShowHTML('    else document.Form.p_prioridade.value=\''.$p_prioridade.'\';');   break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null);
        $RS2 = SortArray($RS2,'ordem','asc');
        $w_fase_exec = '';
        foreach ($RS2 as $row2) {
          if (f($row2,'sigla')=='CI')    $w_fase_cad  = f($row2,'sq_siw_tramite');
          elseif (f($row2,'sigla')=='AT')$w_fase_conc = f($row2,'sq_siw_tramite');
          elseif (f($row2,'ativo')=='S') $w_fase_exec = $w_fase_exec.','.f($row2,'sq_siw_tramite');
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$p_atraso.'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        ShowHTML('<BASE HREF="'.$conRootSIW.'">');
        $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Lista',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_dir.$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case 'GRISTACAO':     if ($p_projeto=='')     ShowHTML('<input type="Hidden" name="p_projeto" value="">');        break;
          case 'GRISTPROP':     if ($p_proponente=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');     break;
          case 'GRISTRESP':     if ($p_solicitante=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');    break;
          case 'GRISTRESPATU':  if ($p_usu_resp=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');       break;
          case 'GRISTSETOR':    if ($p_unidade=='')     ShowHTML('<input type="Hidden" name="p_unidade" value="">');        break;
          case 'GRISTPRIO':     if ($p_prioridade=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');     break;
        }
      }
      $w_nm_quebra  ='';
      $w_qt_quebra  =0.00;
      $t_solic      =0.00;
      $t_cad        =0.00;
      $t_tram       =0.00;
      $t_conc       =0.00;
      $t_atraso     =0.00;
      $t_aviso      =0.00;
      $t_valor      =0.00;
      $t_acima      =0.00;
      $t_custo      =0.00;
      $t_totcusto   =0.00;
      $t_totsolic   =0.00;
      $t_totcad     =0.00;
      $t_tottram    =0.00;
      $t_totconc    =0.00;
      $t_totatraso  =0.00;
      $t_totaviso   =0.00;
      $t_totvalor   =0.00;
      $t_totacima   =0.00;
      foreach($RS1 as $row1) {
        switch ($p_agrega) {
          case 'GRISTACAO':
            if ($w_nm_quebra!=f($row1,'nm_projeto')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_projeto'));
              } 
              $w_nm_quebra  =f($row1,'nm_projeto');
              $w_chave      =f($row1,'sq_solic_pai');
              $w_qt_quebra  =0.00;
              $t_solic      =0.00;
              $t_cad        =0.00;
              $t_tram       =0.00;
              $t_conc       =0.00;
              $t_atraso     =0.00;
              $t_aviso      =0.00;
              $t_valor      =0.00;
              $t_acima      =0.00;
              $t_custo      =0.00;
              $w_linha      +=1;
            } 
          break;
          case 'GRISTPROP':
            if ($w_nm_quebra!=f($row1,'proponente')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'proponente'));
              }
              $w_nm_quebra  =f($row1,'proponente');
              $w_chave      =f($row1,'proponente');
              $w_qt_quebra  =0.00;
              $t_solic      =0.00;
              $t_cad        =0.00;
              $t_tram       =0.00;
              $t_conc       =0.00;
              $t_atraso     =0.00;
              $t_aviso      =0.00;
              $t_valor      =0.00;
              $t_acima      =0.00;
              $t_custo      =0.00;
              $w_linha      +=1;
            } 
          break;
          case 'GRISTRESP':
            if ($w_nm_quebra!=f($row1,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_solic'));
              } 
              $w_nm_quebra  =f($row1,'nm_solic');
              $w_chave      =f($row1,'solicitante');
              $w_qt_quebra  =0.00;
              $t_solic      =0.00;
              $t_cad        =0.00;
              $t_tram       =0.00;
              $t_conc       =0.00;
              $t_atraso     =0.00;
              $t_aviso      =0.00;
              $t_valor      =0.00;
              $t_acima      =0.00;
              $t_custo      =0.00;
              $w_linha      +=1;
            }
          break;
          case 'GRISTRESPATU':
            if ($w_nm_quebra!=f($row1,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_exec'));
              } 
              $w_nm_quebra  =f($row1,'nm_exec');
              $w_chave      =f($row1,'executor');
              $w_qt_quebra  =0;
              $t_solic      =0;
              $t_cad        =0;
              $t_tram       =0;
              $t_conc       =0;
              $t_atraso     =0;
              $t_aviso      =0;
              $t_valor      =0;
              $t_acima      =0;
              $t_custo      =0;
              $w_linha      +=1;
            }
          break;
          case 'GRISTSETOR':
            if ($w_nm_quebra!=f($row1,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_unidade_resp'));
              } 
              $w_nm_quebra  =f($row1,'nm_unidade_resp');
              $w_chave      =f($row1,'sq_unidade_resp');
              $w_qt_quebra  =0.00;
              $t_solic      =0.00;
              $t_cad        =0.00;
              $t_tram       =0.00;
              $t_conc       =0.00;
              $t_atraso     =0.00;
              $t_aviso      =0.00;
              $t_valor      =0.00;
              $t_acima      =0.00;
              $t_custo      =0.00;
              $w_linha+=1;
            }
          break;
          case 'GRISTPRIO':
            if ($w_nm_quebra!=f($row1,'nm_prioridade')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
              }
              if ($O!='W' || ($O=='W' && $w_linha<=$w_linha_pag)) {
                // Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_prioridade'));
              } 
              $w_nm_quebra  =f($row1,'nm_prioridade');
              $w_chave      =f($row1,'prioridade');
              $w_qt_quebra  =0;
              $t_solic      =0;
              $t_cad        =0;
              $t_tram       =0;
              $t_conc       =0;
              $t_atraso     =0;
              $t_aviso      =0;
              $t_valor      =0;
              $t_acima      =0;
              $t_custo      =0;
              $w_linha+=1;
            }
          break;
        } 
        if ($O=='W' && $w_linha>$w_linha_pag) {
          // Se for geração de MS-Word, quebra a página
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          if ($p_tipo=='PDF') ShowHTML('    <pd4ml:page.break>');
          else                ShowHTML('    <br style="page-break-after:always">');
          $w_linha=$w_linha_filtro;
          $w_pag+=1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRISTACAO':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_projeto'));      break;
            case 'GRISTPROP':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'proponente'));      break;
            case 'GRISTRESP':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_solic'));        break;
            case 'GRISTRESPATU':    ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_exec'));         break;
            case 'GRISTSETOR':      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_unidade_resp')); break;
            case 'GRISTPRIO':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><font size=1><b>'.f($row1,'nm_prioridade'));   break;
          } 
          $w_linha+=1;
        } 
        if (f($row1,'concluida')=='N') {
          if (f($row1,'fim') < addDays(time(),-1)) {
            $t_atraso    = $t_atraso + 1;
            $t_totatraso = $t_totatraso + 1;
          } elseif (f($row1,'aviso_prox_conc') == 'S' && (f($row1,'aviso') <= addDays(time(),-1))) {
            $t_aviso    = $t_aviso+1;
            $t_totaviso = $t_totaviso+1;
          }
          if (f($row1,'or_tramite')==1) {
            $t_cad      = $t_cad + 1;
            $t_totcad   = $t_totcad + 1;
          } else {
            $t_tram     = $t_tram + 1;
            $t_tottram  = $t_tottram + 1;
          } 
        } else {
          $t_conc       = $t_conc + 1;
          $t_totconc    = $t_totconc + 1;
          if (Nvl(f($row1,'valor'),0)<Nvl(f($row1,'custo_real'),0)) {
            $t_acima        = $t_acima + 1;
            $t_totacima     = $t_totacima + 1;
          }
        } 
        $t_solic    = $t_solic + 1;
        $t_valor    = $t_valor + Nvl(f($row1,'valor'),0);
        $t_custo    = $t_custo + Nvl(f($row1,'custo_real'),0);
        $t_totvalor = $t_totvalor + Nvl(f($row1,'valor'),0);
        $t_totcusto = $t_totcusto + Nvl(f($row1,'custo_real'),0);
        $t_totsolic = $t_totsolic + 1;
        $w_qt_quebra= $w_qt_quebra + 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><font size="1"><b>Totais</font></td>');
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
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0) ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.$w_dir.'geragrafico.php?p_genero=F&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
    } 
  } elseif (!(strpos('P',$O)===false)) {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    // Exibe parâmetros de apresentação
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Parâmetros de Apresentação</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><font size="1"><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if ($p_agrega=='GRISTACAO')     ShowHTML(' <option value="GRISTACAO" selected>Ação');
    else                            ShowHTML(' <option value="GRISTACAO">Ação');
    if ($p_agrega=='GRISTPRIO')     ShowHTML(' <option value="GRISTPRIO" selected>Prioridade');
    else                            ShowHTML(' <option value="GRISTPRIO">Prioridade');
    if ($p_agrega=='GRISTRESPATU')  ShowHTML(' <option value="GRISTRESPATU" selected>Usuário atual');
    else                            ShowHTML(' <option value="GRISTRESPATU">Usuário atual');
    if ($p_agrega=='GRISTPROP')     ShowHTML(' <option value="GRISTPROP" selected>Parceria externa');
    else                            ShowHTML(' <option value="GRISTPROP">Parceria externa');
    if (Nvl($p_agrega,'GRISTRESP')=='GRISTRESP') ShowHTML(' <option value="GRISTRESP" selected>Responsável monitoramento');
    else                                         ShowHTML(' <option value="GRISTRESP">Responsável pelo monitoramento');
    if ($p_agrega=='GRISTSETOR')    ShowHTML(' <option value="GRISTSETOR" selected>Área de planejamento');
    else                            ShowHTML(' <option value="GRISTSETOR">Área de planejamento');
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibição do gráfico?</b>',$p_tipo,'p_tipo');
    MontaRadioSN('<b>Limita tamanho do detalhamento?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Critérios de Busca</td>');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    SelecaoAcao('Açã<u>o</u>:','O','Selecione a ação da tarefa na relação.',$w_cliente,$w_ano,null,null,null,null,'p_projeto','ACAO',null,$p_projeto);
    ShowHTML('</table>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><font size="1"><b>Número da tare<U>f</U>a:<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Re<u>s</u>ponsável monitoramento:','S','Selecione o responsável pelo monitoramento na relação.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade_IS('Área planejamento:',null,'Selecione a unidade responsável pela tarefa.',$p_unidade,null,'p_unidade',null,'PLANEJAMENTO');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor da tarefa na relação.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('<U>S</U>etor atual:','Y','Selecione a unidade onde a tarefa se encontra na relação.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade desta demanda.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td valign="top"><font size="1"><b>Pa<U>r</U>ceria externa:<br><INPUT ACCESSKEY="R" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b>Detalha<U>m</U>ento:<br><INPUT ACCESSKEY="M" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td valign="top" colspan=2><font size="1"><b>R<U>e</U>sponsável:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b>Data de re<u>c</u>ebimento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>Lim<u>i</u>te para conclusão entre:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"> e <input '.$w_Disabled.' accesskey="I" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Usar formato dd/mm/aaaa"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><font size="1"><b>Exibe somente tarefas em atraso?</b><br>');
    if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> Não');
    else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> Não');
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
  ShowHTML('<tr><td align="center">');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="#DCDCDC" align="center">');
  switch ($p_agrega) {
    case 'GRISTACAO':       ShowHTML('          <td><font size="1"><b>Ação</font></td>');               break;
    case 'GRISTPROP':       ShowHTML('          <td><font size="1"><b>Proponente</font></td>');         break;
    case 'GRISTRESP':       ShowHTML('          <td><font size="1"><b>Responsável</font></td>');        break;
    case 'GRISTRESPATU':    ShowHTML('          <td><font size="1"><b>Executor</font></td>');           break;
    case 'GRISTSETOR':      ShowHTML('          <td><font size="1"><b>Setor responsável</font></td>');  break;
    case 'GRISTPRIO':       ShowHTML('          <td><font size="1"><b>Prioridade</font></td>');         break;
  } 
  ShowHTML('          <td><font size="1"><b>Total</font></td>');
  ShowHTML('          <td><font size="1"><b>Prog.</font></td>');
  ShowHTML('          <td><font size="1"><b>Mon.</font></td>');
  ShowHTML('          <td><font size="1"><b>Conc.</font></td>');
  ShowHTML('          <td><font size="1"><b>Atraso</font></td>');
  ShowHTML('          <td><font size="1"><b>Aviso</font></td>');
  ShowHTML('          <td><font size="1"><b>$ Prev.</font></td>');
  ShowHTML('          <td><font size="1"><b>$ Real</font></td>');
  ShowHTML('          <td><font size="1"><b>Real > Previsto</font></td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  if ($O=='L')  ShowHTML('          <td align="right"><font size="1"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</font></td>');
  else          ShowHTML('          <td align="right"><font size="1">'.number_format($l_solic,0,',','.').'&nbsp;</font></td>');
  if ($l_cad>0 && $O=='L')  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.number_format($l_cad,0,',','.').'</a>&nbsp;</font></td>');
  else                      ShowHTML('          <td align="right"><font size="1">'.number_format($l_cad,0,',','.').'&nbsp;</font></td>');
  if ($l_tram>0 && $O=='L') ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.number_format($l_tram,0,',','.').'</a>&nbsp;</font></td>');
  else                      ShowHTML('          <td align="right"><font size="1">'.number_format($l_tram,0,',','.').'&nbsp;</font></td>');
  if ($l_conc>0 && $O=='L') ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1">'.number_format($l_conc,0,',','.').'</a>&nbsp;</font></td>');
  else                      ShowHTML('          <td align="right"><font size="1">'.number_format($l_conc,0,',','.').'&nbsp;</font></td>');
  if ($l_atraso>0 && $O=='L')ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe as tarefas.\'; return true" onMouseOut="window.status=\'\'; return true"><font size="1" color="red"><b>'.number_format($l_atraso,0,',','.').'</a>&nbsp;</font></td>');
  else                       ShowHTML('          <td align="right"><font size="1"><b>'.$l_atraso.'&nbsp;</font></td>');
  if ($l_aviso>0 && $O=='L')ShowHTML('          <td align="right"><font size="1" color="red"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</font></td>');
  else                      ShowHTML('          <td align="right"><font size="1"><b>'.$l_aviso.'&nbsp;</font></td>');
  ShowHTML('          <td align="right"><font size="1">'.number_format($l_valor,2,',','.').'&nbsp;</font></td>');
  ShowHTML('          <td align="right"><font size="1">'.number_format($l_custo,2,',','.').'&nbsp;</font></td>');
  if ($l_acima>0)   ShowHTML('          <td align="right"><font size="1" color="red"><b>'.number_format($l_acima,0,',','.').'&nbsp;</font></td>');
  else              ShowHTML('          <td align="right"><font size="1"><b>'.$l_acima.'&nbsp;</font></td>');
  ShowHTML('        </tr>');
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  // Verifica se o usuário tem lotação e localização
  switch ($par) {
    case 'GERENCIAL':   Gerencial();    break;
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