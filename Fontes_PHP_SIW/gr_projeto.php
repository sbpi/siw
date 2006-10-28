<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getLinkSubMenu.php');
include_once('classes/sp/db_getCcData.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getUorgData.php');
include_once('classes/sp/db_getCountryData.php');
include_once('classes/sp/db_getRegionData.php');
include_once('classes/sp/db_getStateData.php');
include_once('classes/sp/db_getCityData.php');
include_once('classes/sp/db_getSolicList.php');
include_once('classes/sp/db_getTramiteList.php');
include_once('funcoes/selecaoCC.php');
include_once('funcoes/selecaoPessoa.php');
include_once('funcoes/selecaoUnidade.php');
include_once('funcoes/selecaoPais.php');
include_once('funcoes/selecaoRegiao.php');
include_once('funcoes/selecaoEstado.php');
include_once('funcoes/selecaoCidade.php');
include_once('funcoes/selecaoPrioridade.php');
include_once('funcoes/selecaoFaseCheck.php');

// =========================================================================
//  /GR_Projeto.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia o m�dulo de projetos
// Mail     : alex@sbpi.com.br
// Criacao  : 15/10/2003 12:25
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = L   : Listagem
//                   = P   : Filtragem
//                   = V   : Gera��o de gr�fico
//                   = W   : Gera��o de documento no formato MS-Word (Office 2003)

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }


// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = $_REQUEST['P1'];
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'gr_projeto.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = '';
$w_dir_volta    = '';
$w_troca        = strtoupper($_REQUEST['w_troca']);

if ($O=='') $O='P';

switch ($O) {
  case 'V': $w_TP=$TP.' - Gr�fico'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = $P2;

$p_tipo         = strtoupper($_REQUEST['p_tipo']);
$p_ativo        = strtoupper($_REQUEST['p_ativo']);
$p_solicitante  = strtoupper($_REQUEST['p_solicitante']);
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
$p_usu_resp     = strtoupper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = strtoupper($_REQUEST['p_uorg_resp']);
$p_palavra      = strtoupper($_REQUEST['p_palavra']);
$p_prazo        = strtoupper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_agrega       = strtoupper($_REQUEST['p_agrega']);
$p_tamanho      = strtoupper($_REQUEST['p_tamanho']);
$p_sqcc         = strtoupper($_REQUEST['p_sqcc']);
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
$p_pais         = strtoupper($_REQUEST['p_pais']);
$p_regiao       = strtoupper($_REQUEST['p_regiao']);
$p_uf           = strtoupper($_REQUEST['p_uf']);
$p_cidade       = strtoupper($_REQUEST['p_cidade']);
$p_prioridade   = strtoupper($_REQUEST['p_prioridade']);

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configura��o do servi�o
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Pesquisa gerencial
// -------------------------------------------------------------------------
function Gerencial() {
  extract($GLOBALS);

  if ($O=='L' || $O=='V' || $O=='W') {

    $w_filtro='';
    if ($p_sqcc>'') {
      $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
      $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
    } 

    if ($p_chave>'')  $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Projeto n� <td>[<b>'.$p_chave.'</b>]';
    if ($p_prazo>'') $w_filtro=$w_filtro.' <tr valign="top"><td align="right">Prazo para conclus�o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
    if ($p_solicitante>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Respons�vel <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_unidade>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_unidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade respons�vel <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_usu_resp>'') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
    } 
    if ($p_uorg_resp>'') {
      $RS = db_getUorgData::getInstanceOf($dbms,$p_uorg_resp);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_pais>'') {
      $RS = db_getCountryData::getInstanceOf($dbms,$p_pais);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Pa�s <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_regiao>'') {
      $RS = db_getRegionData::getInstanceOf($dbms,$p_regiao);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Regi�o <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uf>'') {
      $RS = db_getStateData::getInstanceOf($dbms,$p_pais,$p_uf);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_cidade>'') {
      $RS = db_getCityData::getInstanceOf($dbms,$p_cidade);
      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_prioridade>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
    if ($p_proponente>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Proponente <td>[<b>'.$p_proponente.'</b>]';
    if ($p_assunto>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
    if ($p_palavra>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
    if ($p_ini_i>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Limite conclus�o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'') $w_filtro='<table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table>';

    $RS1 = db_getSolicList::getInstanceOf($dbms,$P2,$w_usuario,$p_agrega,4,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente, 
        $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, 
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);

    switch ($p_agrega) {
      case 'GRPRPROJ':
        $w_TP = $TP.' - Por projeto';
        $RS1  = SortArray($RS1,'titulo','asc');
        break;
      case 'GRPRPROP':
        $w_TP = $TP.' - Por proponente';
        $RS1  = SortArray($RS1,'proponente','asc');
        break;
      case 'GRPRRESP':
        $w_TP = $TP.' - Por respons�vel';
        $RS1  = SortArray($RS1,'nm_solic_ind','asc');
        break;
      case 'GRPRRESPATU':
        $w_TP = $TP.' - Por executor';
        $RS1  = SortArray($RS1,'nm_exec_ind','asc');
        break;
      case 'GRPRCC':
        $w_TP = $TP.' - Por classifica��o';
        $RS1  = SortArray($RS1,'sg_cc','asc');
        break;
      case 'GRPRSETOR':
        $w_TP = $TP.' - Por setor respons�vel';
        $RS1  = SortArray($RS1,'nm_unidade_resp','asc');
        break;
      case 'GRPRPRIO':
        $w_TP = $TP.' - Por prioridade';
        $RS1  = SortArray($RS1,'nm_prioridade','asc');
        break;
      case 'GRPRLOCAL':
        $w_TP = $TP.' - Por UF';
        $RS1  = SortArray($RS1,'nm_prioridade','asc');
        break;
    } 
  } 

  if ($O=='W') {
    HeaderWord(null);
    $w_pag=1;
    $w_linha=0.00;
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
      Validate('p_chave','N�mero do projeto','','','1','18','','0123456789');
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
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  for (i=0; i < theForm["p_fase[]"].length; i++) {');
      ShowHTML('    if (theForm["p_fase[]"][i].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Voc� deve informar pelo menos uma fase!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');      
      CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
      Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
      Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
      ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
      ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
      ShowHTML('     theForm.p_fim_i.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      ValidateClose();
      ScriptClose();
    } else {
      ShowHTML('<TITLE>'.$w_TP.'</TITLE>');
    } 
    ShowHTML('</HEAD>'); 
    if ($w_Troca>'') {
      // Se for recarga da p�gina
      BodyOpen('onLoad=\'document.Form.'.$w_Troca.'.focus();\'');
    } elseif ($O=='P') {
      if ($P1==1) {
        // Se for cadastramento
        BodyOpen('onLoad=\'document.Form.p_ordena.focus()\';');
      } else {
        BodyOpen('onLoad=\'document.Form.p_agrega.focus()\';');
      } 
    } else {
      BodyOpenClean('onLoad=document.focus();');
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
      if (MontaFiltro('GET')>'') {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</a></font>');
      } else {
        ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ImprimeCabecalho();
    if (count($RS1)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      if ($O=='L') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (filtro, cad, exec, conc, atraso) {');
        ShowHTML('    if (filtro != -1) {');
        switch ($p_agrega) {
          case 'GRPRPROJ':      ShowHTML('      document.Form.p_projeto.value=filtro;');        break;
          case 'GRPRPROP':      ShowHTML('      document.Form.p_proponente.value=filtro;');     break;
          case 'GRPRRESP':      ShowHTML('      document.Form.p_solicitante.value=filtro;');    break;
          case 'GRPRRESPATU':   ShowHTML('      document.Form.p_usu_resp.value=filtro;');       break;
          case 'GRPRCC':        ShowHTML('      document.Form.p_sqcc.value=filtro;');           break;
          case 'GRPRSETOR':     ShowHTML('      document.Form.p_unidade.value=filtro;');        break;
          case 'GRPRPRIO':      ShowHTML('      document.Form.p_prioridade.value=filtro;');     break;
          case 'GRPRLOCAL':     ShowHTML('      document.Form.p_uf.value=filtro;');             break;
        } 
        ShowHTML('    }');
        switch ($p_agrega) {
          case 'GRPRPROJ':      ShowHTML('    else document.Form.p_projeto.value=\''.$_REQUEST['p_projeto'].'\';');         break;
          case 'GRPRPROP':      ShowHTML('    else document.Form.p_proponente.value=\''.$_REQUEST['p_proponente'].'\';');   break;
          case 'GRPRRESP':      ShowHTML('    else document.Form.p_solicitante.value=\''.$_REQUEST['p_solicitante'].'\';'); break;
          case 'GRPRRESPATU':   ShowHTML('    else document.Form.p_usu_resp.value=\''.$_REQUEST['p_usu_resp'].'\';');       break;
          case 'GRPRCC':        ShowHTML('    else document.Form.p_sqcc.value=\''.$_REQUEST['p_sqcc'].'\';');               break;
          case 'GRPRSETOR':     ShowHTML('    else document.Form.p_unidade.value=\''.$_REQUEST['p_unidade'].'\';');         break;
          case 'GRPRPRIO':      ShowHTML('    else document.Form.p_prioridade.value=\''.$_REQUEST['p_prioridade'].'\';');   break;
          case 'GRPRLOCAL':     ShowHTML('    else document.Form.p_uf.value=\''.$_REQUEST['p_uf'].'\';');                   break;
        } 
        $RS2 = db_getTramiteList::getInstanceOf($dbms,$P2,null,null);
        $RS2  = SortArray($RS2,'ordem','asc');
        $w_fase_exec = '';
        foreach($RS2 as $row) {
          if (f($row,'sigla')=='CI') {
            $w_fase_cad = f($row,'sq_siw_tramite');
          } elseif (f($row,'sigla')=='AT') {
            $w_fase_conc = f($row,'sq_siw_tramite');
          } elseif (f($row,'ativo')=='S') {
            $w_fase_exec = $w_fase_exec.','.f($row,'sq_siw_tramite');
          } 
        } 
        ShowHTML('    if (cad >= 0) document.Form.p_fase.value='.$w_fase_cad.';');
        ShowHTML('    if (exec >= 0) document.Form.p_fase.value=\''.substr($w_fase_exec,1,100).'\';');
        ShowHTML('    if (conc >= 0) document.Form.p_fase.value='.$w_fase_conc.';');
        ShowHTML('    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value=\''.$p_fase.'\'; ');
        ShowHTML('    if (atraso >= 0) document.Form.p_atraso.value=\'S\'; else document.Form.p_atraso.value=\''.$_REQUEST['p_atraso'].'\'; ');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $RS2 = db_getMenuData::getInstanceOf($dbms,$P2);
        AbreForm('Form',f($RS2,'link'),'POST','return(Validacao(this));','Projeto',3,$P2,f($RS2,'P3'),null,$w_TP,f($RS2,'sigla'),$w_pagina.$par,'L');
        ShowHTML(MontaFiltro('POST'));
        switch ($p_agrega) {
          case 'GRPRPROJ':      if ($_REQUEST['p_projeto']=='')     ShowHTML('<input type="Hidden" name="p_projeto" value="">');      break;
          case 'GRPRPROP':      if ($_REQUEST['p_proponente']=='')  ShowHTML('<input type="Hidden" name="p_proponente" value="">');   break;
          case 'GRPRRESP':      if ($_REQUEST['p_solicitante']=='') ShowHTML('<input type="Hidden" name="p_solicitante" value="">');  break;
          case 'GRPRRESPATU':   if ($_REQUEST['p_usu_resp']=='')    ShowHTML('<input type="Hidden" name="p_usu_resp" value="">');     break;
          case 'GRPRCC':        if ($_REQUEST['p_sqcc']=='')        ShowHTML('<input type="Hidden" name="p_sqcc" value="">');         break;
          case 'GRPRSETOR':     if ($_REQUEST['p_unidade']=='')     ShowHTML('<input type="Hidden" name="p_unidade" value="">');      break;
          case 'GRPRPRIO':      if ($_REQUEST['p_prioridade']=='')  ShowHTML('<input type="Hidden" name="p_prioridade" value="">');   break;
          case 'GRPRLOCAL':     if ($_REQUEST['p_uf']=='')          ShowHTML('<input type="Hidden" name="p_uf" value="">');           break;
        } 
      } 

      $w_nm_quebra='';
      $w_qt_quebra=0.00;
      $t_solic=0.00;
      $t_cad=0.00;
      $t_tram=0.00;
      $t_conc=0.00;
      $t_atraso=0.00;
      $t_aviso=0.00;
      $t_valor=0.00;
      $t_acima=0.00;
      $t_custo=0.00;
      $t_totcusto=0.00;
      $t_totsolic=0.00;
      $t_totcad=0.00;
      $t_tottram=0.00;
      $t_totconc=0.00;
      $t_totatraso=0.00;
      $t_totaviso=0.00;
      $t_totvalor=0.00;
      $t_totacima=0.00;
      foreach($RS1 as $row) {
        switch ($p_agrega) {
          case 'GRPRPROJ':
            if ($w_nm_quebra!=f($row,'titulo')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha = $w_linha + 2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'titulo'));
              } 
              $w_nm_quebra=f($row,'titulo');
              $w_chave=f($row,'sq_siw_solicitacao');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRPROP':
            if ($w_nm_quebra!=f($row,'proponente')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));
              } 
              $w_nm_quebra=f($row,'proponente');
              $w_chave=f($row,'proponente');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRRESP':
            if ($w_nm_quebra!=f($row,'nm_solic')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));
              } 
              $w_nm_quebra=f($row,'nm_solic');
              $w_chave=f($row,'solicitante');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRRESPATU':
            if ($w_nm_quebra!=f($row,'nm_exec')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));
              } 
              $w_nm_quebra=f($row,'nm_exec');
              $w_chave=f($row,'executor');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRCC':
            if ($w_nm_quebra!=f($row,'sg_cc')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));
              } 
              $w_nm_quebra=f($row,'sg_cc');
              $w_chave=f($row,'sq_cc');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRSETOR':
            if ($w_nm_quebra!=f($row,'nm_unidade_resp')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));
              } 
              $w_nm_quebra=f($row,'nm_unidade_resp');
              $w_chave=f($row,'sq_unidade_resp');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRPRIO':
            if ($w_nm_quebra!=f($row,'nm_prioridade')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prioridade'));
              } 
              $w_nm_quebra=f($row,'nm_prioridade');
              $w_chave=f($row,'prioridade');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
          case 'GRPRLOCAL':
            if ($w_nm_quebra!=f($row,'co_uf')) {
              if ($w_qt_quebra>0) {
                ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
                $w_linha=$w_linha+2;
              } 
              if ($O!='W' || ($O=='W' && $w_linha<=25)) {
                // Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));
              } 
              $w_nm_quebra=f($row,'co_uf');
              $w_chave=f($row,'co_uf');
              $w_qt_quebra=0.00;
              $t_solic=0.00;
              $t_cad=0.00;
              $t_tram=0.00;
              $t_conc=0.00;
              $t_atraso=0.00;
              $t_aviso=0.00;
              $t_valor=0.00;
              $t_acima=0.00;
              $t_custo=0.00;
              $w_linha=$w_linha+1;
            } 
            break;
        } 
        if ($O=='W' && $w_linha>25) {
          // Se for gera��o de MS-Word, quebra a p�gina
          ShowHTML('    </table>');
          ShowHTML('  </td>');
          ShowHTML('</tr>');
          ShowHTML('</table>');
          ShowHTML('</center></div>');
          ShowHTML('    <br style="page-break-after:always">');
          $w_linha=0.00;
          $w_pag=$w_pag+1;
          CabecalhoWord($w_cliente,$w_TP,$w_pag);
          if ($w_filtro>'') ShowHTML($w_filtro);
          ShowHTML('<div align=center><center>');
          ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
          ImprimeCabecalho();
          switch ($p_agrega) {
            case 'GRPRPROJ':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'titulo'));           break;
            case 'GRPRPROP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'proponente'));       break;
            case 'GRPRRESP':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_solic'));         break;
            case 'GRPRRESPATU':     ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_exec'));          break;
            case 'GRPRCC':          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'sg_cc'));            break;
            case 'GRPRSETOR':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_unidade_resp'));  break;
            case 'GRPRPRIO':        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'nm_prioridade'));    break;
            case 'GRPRLOCAL':       ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top"><td><b>'.f($row,'co_uf'));            break;
          } 
          $w_linha = $w_linha+1;
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
          $t_conc=$t_conc+1;
          $t_totconc=$t_totconc+1;
          if (f($row,'valor')<f($row,'custo_real')) {
            $t_acima    = $t_acima+1;
            $t_totacima = $t_totacima+1;
          } 
        } 
        $t_solic        = $t_solic + 1;
        $t_valor        = $t_valor + Nvl(f($row,'valor'),0);
        $t_custo        = $t_custo + Nvl(f($row,'custo_real'),0);
        $t_totvalor     = $t_totvalor + Nvl(f($row,'valor'),0);
        $t_totcusto     = $t_totcusto + Nvl(f($row,'custo_real'),0);
        $t_totsolic     = $t_totsolic + 1;
        $w_qt_quebra    = $w_qt_quebra + 1;
      } 
      ImprimeLinha($t_solic,$t_cad,$t_tram,$t_conc,$t_atraso,$t_aviso,$t_valor,$t_custo,$t_acima,$w_chave);
      ShowHTML('      <tr bgcolor="#DCDCDC" valign="top" align="right">');
      ShowHTML('          <td><b>Totais</td>');
      ImprimeLinha($t_totsolic,$t_totcad,$t_tottram,$t_totconc,$t_totatraso,$t_totaviso,$t_totvalor,$t_totcusto,$t_totacima,-1);
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    if (count($RS1)>0 && $p_tipo=='N') {
      // Coloca o gr�fico somente se o usu�rio desejar
      ShowHTML('<tr><td align="center" height=20>');
      ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Barra&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      ShowHTML('<tr><td align="center" height=20>');
      if (($t_totcad+$t_tottram)>0) {
        ShowHTML('<tr><td align="center"><IMG SRC="'.$conPHP4.'geragrafico.php?p_genero=M&p_objeto='.f($RS_Menu,'nome').'&p_tipo='.$SG.'&p_grafico=Pizza&p_tot='.$t_totsolic.'&p_cad='.$t_totcad.'&p_tram='.$t_tottram.'&p_conc='.$t_totconc.'&p_atraso='.$t_totatraso.'&p_aviso='.$t_totaviso.'&p_acima='.$t_totacima.'">');
      } 
    } 
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    // Exibe par�metros de apresenta��o
    ShowHTML('         <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Par�metros de Apresenta��o</td>');
    ShowHTML('         <tr valign="top"><td colspan=2><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY="A" '.$w_Disabled.' class="STS" name="p_agrega" size="1">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      switch ($p_agrega) {
        case 'GRPRCC':      ShowHTML('          <option value="GRPRCC" selected>Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRPRIO':    ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO" selected>Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRPROJ':    ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ" selected>Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRPROP':    ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP" selected>Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRRESPATU': ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU" selected>Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRSETOR':   ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR" selected>Setor respons�vel<option value="GRPRLOCAL">UF');       break;
        case 'GRPRLOCAL':   ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL" selected>UF');       break;
        default:            ShowHTML('          <option value="GRPRCC">Classifica��o<option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP" selected>Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');       break;
      } 
    } else {
      switch ($p_agrega) {
        case 'GRPRCC':      ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');            break;
        case 'GRPRPRIO':    ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO" selected>Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');   break;
        case 'GRPRPROJ':    ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ" selected>Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');   break;
        case 'GRPRPROP':    ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP" selected>Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');   break;
        case 'GRPRRESPATU': ShowHTML('          <option value="GRPRRESPATU" selected>Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');   break;
        case 'GRPRSETOR':   ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR" selected>Setor respons�vel<option value="GRPRLOCAL">UF');   break;
        case 'GRPRLOCAL':   ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP">Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL" selected>UF');   break;
        default:            ShowHTML('          <option value="GRPRRESPATU">Executor<option value="GRPRPRIO">Prioridade<option value="GRPRPROJ">Projeto<option value="GRPRPROP">Proponente<option value="GRPRRESP" selected>Respons�vel<option value="GRPRSETOR">Setor respons�vel<option value="GRPRLOCAL">UF');   break;
      } 
    } 
    ShowHTML('          </select></td>');
    MontaRadioNS('<b>Inibe exibi��o do gr�fico?</b>',$p_tipo,'p_tipo');
    MontaRadioSN('<b>Limita tamanho do assunto?</b>',$p_tamanho,'p_tamanho');
    ShowHTML('           </table>');
    ShowHTML('         </tr>');
    ShowHTML('         <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Crit�rios de Busca</td>');
    if (f($RS_Menu,'solicita_cc')=='S') {
      ShowHTML('      <tr><td colspan=2><table border=0 width="90%" cellspacing=0><tr valign="top">');
      SelecaoCC('C<u>l</u>assifica��o:','L','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
      ShowHTML('          </table>');
    } 
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>N�mero do pro<U>j</U>eto:<br><INPUT ACCESSKEY="J" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
    ShowHTML('          <td valign="top"><b><U>D</U>ias para a data limite:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo projeto na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
    SelecaoUnidade('Setor respons�vel:',null,null,$p_unidade,null,'p_unidade',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('E<u>x</u>ecutor:','X','Selecione o executor do projeto na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
    SelecaoUnidade('Setor atual:',null,'Selecione a unidade onde o projeto se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
    SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
    ShowHTML('      <tr>');
    SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,'N','p_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.target=\'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
    SelecaoCidade('C<u>i</u>dade:','I',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
    ShowHTML('      <tr>');
    SelecaoPrioridade('Prioridad<u>e</u>:','E','Informe a prioridade deste projeto.',$p_prioridade,null,'p_prioridade',null,null);
    ShowHTML('          <td valign="top"><b>Pr<U>o</U>ponente externo:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>T</U>�tulo:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
    ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="V" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>In�<u>c</u>io previsto entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('          <td valign="top"><b><u>T</u>�rmino previsto entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="U" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b>Exibe somente projetos em atraso?</b><br>');
    if ($p_atraso=='S') {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> N�o');
    } else {
      ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> N�o');
    } 
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
    case 'GRPRPROJ':    ShowHTML('          <td><b>Projeto</td>');              break;
    case 'GRPRPROP':    ShowHTML('          <td><b>Proponente</td>');           break;
    case 'GRPRRESP':    ShowHTML('          <td><b>Respons�vel</td>');          break;
    case 'GRPRRESPATU': ShowHTML('          <td><b>Executor</td>');             break;
    case 'GRPRCC':      ShowHTML('          <td><b>Classifica��o</td>');        break;
    case 'GRPRSETOR':   ShowHTML('          <td><b>Setor respons�vel</td>');    break;
    case 'GRPRPRIO':    ShowHTML('          <td><b>Prioridade</td>');           break;
    case 'GRPRLOCAL':   ShowHTML('          <td><b>UF</td>');                   break;
  } 
  ShowHTML('          <td><b>Total</td>');
  ShowHTML('          <td><b>Cad.</td>');
  ShowHTML('          <td><b>Exec.</td>');
  ShowHTML('          <td><b>Conc.</td>');
  ShowHTML('          <td><b>Atraso</td>');
  ShowHTML('          <td><b>Aviso</td>');
  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td><b>$ Prev.</td>');
    ShowHTML('          <td><b>$ Real</td>');
    ShowHTML('          <td><b>Real > Previsto</td>');
  } 
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina de impressao da linha resumo
// -------------------------------------------------------------------------
function ImprimeLinha($l_solic,$l_cad,$l_tram,$l_conc,$l_atraso,$l_aviso,$l_valor,$l_custo,$l_acima,$l_chave) {
  extract($GLOBALS);
  if ($O=='L')                  ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, -1);" onMouseOver="window.status=\'Exibe os projetos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_solic,0,',','.').'</a>&nbsp;</td>');                             else ShowHTML('          <td align="right">'.number_format($l_solic,0,',','.').'&nbsp;</td>');
  if ($l_cad>0 && $O=='L')      ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', 0, -1, -1, -1);" onMouseOver="window.status=\'Exibe os projetos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_cad,0,',','.').'</a>&nbsp;</td>');                                else ShowHTML('          <td align="right">'.number_format($l_cad,0,',','.').'&nbsp;</td>');
  if ($l_tram>0 && $O=='L')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, 0, -1, -1);" onMouseOver="window.status=\'Exibe os projetos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_tram,0,',','.').'</a>&nbsp;</td>');                               else ShowHTML('          <td align="right">'.number_format($l_tram,0,',','.').'&nbsp;</td>');
  if ($l_conc>0 && $O=='L')     ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, 0, -1);" onMouseOver="window.status=\'Exibe os projetos.\'; return true" onMouseOut="window.status=\'\'; return true">'.number_format($l_conc,0,',','.').'</a>&nbsp;</td>');                               else ShowHTML('          <td align="right">'.number_format($l_conc,0,',','.').'&nbsp;</td>');
  if ($l_atraso>0 && $O=='L')   ShowHTML('          <td align="right"><a class="hl" href="javascript:lista(\''.$l_chave.'\', -1, -1, -1, 0);" onMouseOver="window.status=\'Exibe os projetos.\'; return true" onMouseOut="window.status=\'\'; return true"><font color="red"><b>'.number_format($l_atraso,0,',','.').'</font></a>&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.number_format($l_atraso,0,',','.').'&nbsp;</td>');
  if ($l_aviso>0 && $O=='L')    ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_aviso,0,',','.').'</font>&nbsp;</td>');                                                                                                                                                                                           else ShowHTML('          <td align="right"><b>'.number_format($l_aviso,0,',','.').'&nbsp;</td>');
  if ($_SESSION['INTERNO']=='S') {
    ShowHTML('          <td align="right">'.number_format($l_valor,2,',','.').'&nbsp;</td>');
    ShowHTML('          <td align="right">'.number_format($l_custo,2,',','.').'&nbsp;</td>');
    if ($l_acima>0) ShowHTML('          <td align="right"><font color="red"><b>'.number_format($l_acima,0,',','.').'</font>&nbsp;</td>'); else ShowHTML('          <td align="right"><b>'.number_format($l_acima,0,',','.').'&nbsp;</td>');
  } 
  ShowHTML('        </tr>');
} 

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);

  switch ($par) {
  case 'GERENCIAL':   Gerencial(); break;
  default:
    Cabecalho();
    BodyOpen('onLoad=document.focus();');
    Estrutura_Topo_Limpo();
    Estrutura_Menu();
    Estrutura_Corpo_Abre();
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  } 
} 
?>
