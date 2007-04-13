<?
header('Expires: '.-1500);
session_start();
$w_dir_volta    = '';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicInter.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAreas.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getSolicEtpRec.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRecurso.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRestricao.php');
include_once($w_dir_volta.'classes/sp/db_getEtapaOrder.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData_IS.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php'); 
include_once($w_dir_volta.'funcoes/selecaoTipoRecurso.php');
include_once($w_dir_volta.'funcoes/selecaoPlanoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoObjetivoEstrategico.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoPrioridade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVisao.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoAcao.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoBaseGeografica.php');
include_once($w_dir_volta.'funcoes/selecaoInteresse.php');
include_once($w_dir_volta.'funcoes/selecaoInfluencia.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoInter.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoAreas.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEtapa.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoRec.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEtpRec.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putAtualizaEtapa.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoConc.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putProjetoDescritivo.php');
include_once($w_dir_volta.'classes/sp/dml_putRestricaoEtapa.php'); 
include_once($w_dir_volta.'visualprojeto.php');

// =========================================================================
//  /Projeto.php
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
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = C   : Cancelamento
//                   = E   : Exclus�o
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

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
$w_pagina       = 'projeto.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = '';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = strtoupper($_REQUEST['w_copia']);
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
if ($SG!='ETAPAREC') {
  $w_menu = RetornaMenu($w_cliente,$SG);
} else {
  $w_menu = RetornaMenu($w_cliente,$_REQUEST['w_SG']);
} 
if ($SG=='PJRECURSO' || $SG=='PJETAPA' || $SG=='PJINTERESS' || $SG=='PJAREAS' || $SG=='PJANEXO' || 
    $SG=='PJBETAPA' || $SG=='PJBINTERES' || $SG=='PJBAREAS' || $SG=='PJBANEXO' || $SG=='PJRUBRICA') {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif  ($SG=='PJENVIO' || $SG=='PJBENVIO') {             $O='V';
} elseif  (($SG=='PJVISUAL' || $SG=='PJBVISUAL') && $O=='A') { $O='L';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - C�pia'; break;
  case 'V': $w_TP=$TP.' - Envio'; break;
  case 'H': $w_TP=$TP.' - Heran�a'; break;
  default : $w_TP=$TP.' - Listagem';
} 
$p_projeto      = strtoupper($_REQUEST['p_projeto']);
$p_atividade    = strtoupper($_REQUEST['p_atividade']);
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
if ($SG!='ETAPAREC') $RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
else $RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$_REQUEST['w_SG']);
if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
}
// Recupera a configura��o do servi�o
if ($P2 > 0) $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
else $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
if (f($RS_Menu,'ultimo_nivel') == 'S') {
  // Se for sub-menu, pega a configura��o do pai
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 
Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualiza��o resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_tipo=$_REQUEST['w_tipo'];
  if ($O=='L') {
    if ((strpos(strtoupper($R),'GR_')!==false) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_projeto>'') {
        $RS = db_getSolicData::getInstanceOf($dbms,$p_projeto,'PJGERAL');
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Projeto <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_atividade>'') {
        $RS = db_getSolicEtapa::getInstanceOf($dbms,$p_projeto,$p_atividade,'REGISTRO',null);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      } 
      if ($p_sqcc>'') {
        $RS = db_getCCData::getInstanceOf($dbms,$p_sqcc);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Projeto n� <td>[<b>'.$p_chave.'</b>]';
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
      if ($p_assunto>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Assunto <td>[<b>'.$p_assunto.'</b>]';
      if ($p_palavra>'')    $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Palavras-chave <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Conclus�o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro=$w_filtro.'<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia > '') {   
      // Se for c�pia, aplica o filtro sobre todas os projeto vis�veis pelo usu�rio
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
    } else {
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, null, null);
    } 
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'fim','asc','prioridade','asc');
    } else {
      $RS = SortArray($RS,'fim','asc','prioridade','asc');
    }
  } 
  if ($w_tipo=='WORD') {
    HeaderWord();
  } else {
    cabecalho();
    ShowHTML('<HEAD>');
    if ($P1==2) ShowHTML ('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de projetos</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia
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
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert (\'Informe ambas as datas de conclus�o ou nenhuma delas!\');');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }    
  if ($w_troca > '') {
    // Se for recarga da p�gina
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_smtp_server.focus();\'');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif (strpos('CP',$O)!==false) {
    if ($P1!=1 || $O=='C') {
      // Se for cadastramento
      BodyOpenClean('onLoad=\'document.Form.p_chave.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.p_ordena.focus()\';');
    } 
  } else {
    BodyOpenClean('onLoad=this.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    ShowHTML('<tr><td>');
    if ($P1 == 1 && $w_copia == '') {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      if ($w_submenu > '') {
        $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);

        foreach($RS1 as $row) {
          if ($w_tipo!='WORD') ShowHTML('    <a accesskey="I" class="SS" href="'.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
          break;
        }
        if ($w_tipo!='WORD') ShowHTML('    <a accesskey="C" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar</a>');
      } else {
        if ($w_tipo!='WORD') ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      } 
    } 
    if ((strpos(strtoupper($R),'GR_'))===false && $P1!=6 && $w_tipo!='WORD') {
      if ($w_copia > '') {
        // Se for c�pia
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (MontaFiltro('GET')>'') ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    } 
    ShowHTML('    <td align="right">');
    if ($w_tipo!='WORD') {
      ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    } 
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('N�','sq_siw_solicitacao').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Projeto','titulo').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>'.LinkOrdena('Vincula��o','cd_vinculacao').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Respons�vel','nm_solic').'</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td colspan=2><b>Execu��o</td>');
      } else {
        ShowHTML('          <td colspan=2><b>Execu��o</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Valor','valor').'</td>');
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</td>');
      } 
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Opera��es</td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('De','inicio').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('At�','fim').'</td>');
      ShowHTML('        </tr>');
    } else {
      ShowHTML('          <td rowspan=2><b>N�</td>');
      ShowHTML('          <td rowspan=2><b>Projeto</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML ('          <td rowspan=2><b>Vincula��o</td>');
      ShowHTML('          <td rowspan=2><b>Respons�vel</td>');
      if ($P1==1 || $P1==2) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td colspan=2><b>Execu��o</td>');
      } else {
        ShowHTML('          <td colspan=2><b>Execu��o</td>');
        if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Valor</td>');
        ShowHTML('          <td rowspan=2><b>Fase atual</td>');
      } 
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>De</td>');
      ShowHTML('          <td><b>At�</td>');
      ShowHTML('        </tr>');    
    }
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial=0;
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>');
        if ($w_tipo!='WORD') {
          ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
          ShowHTML('        <A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>'.exibeImagemRestricao(f($row,'restricao'),'P'));
          // Verifica se foi enviado o par�metro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
          // Este par�metro � enviado pela tela de filtragem das p�ginas gerenciais
          if ($_REQUEST['p_tamanho']=='N') {
            ShowHTML('        <td>'.Nvl(f($row,'titulo'),'-').'</td>');
          } else {
            if ($w_tipo!='WORD' && strlen(Nvl(f($row,'titulo'),'-'))>50) $w_titulo=substr(Nvl(f($row,'titulo'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'titulo'),'-');
            if (f($row,'sg_tramite')=='CA') ShowHTML('        <td title="'.htmlspecialchars(f($row,'titulo')).'"><strike>'.htmlspecialchars($w_titulo).'</strike></td>');
            else                            ShowHTML('        <td title="'.htmlspecialchars(f($row,'titulo')).'">'.htmlspecialchars($w_titulo).'</td>');
          } 
          if ($_SESSION['INTERNO']=='S') {
            if (Nvl(f($row,'cd_vinculacao'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'cd_vinculacao')).'</td>');
            else                                     ShowHTML('        <td>---</td>');
          } 
          ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>');
        } else {
          ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
          ShowHTML('        '.f($row,'sq_siw_solicitacao'));
          ShowHTML('        <td>'.Nvl(f($row,'titulo'),'-').'</td>');
          if ($_SESSION['INTERNO']=='S') ShowHTML('        <td>'.f($row,'cd_vinculacao').'</td>');
          ShowHTML('        <td>'.f($row,'nm_solic').'</td>');
        }      
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio'),5).'</td>');
        ShowHTML('        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim'),5).'</td>');
        // Mostra o valor se o usu�rio for interno e n�o for cadastramento nem mesa de trabalho
        if ($P1!=1 && $P1!=2) {
          if ($_SESSION['INTERNO']=='S') {
            if (f($row,'sg_tramite')=='AT') {
              ShowHTML('        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>');
              $w_parcial += f($row,'custo_real');
            } else {
              ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
              $w_parcial += f($row,'valor');
            } 
          } 
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        if ($_SESSION['INTERNO']=='S'&& $w_tipo!='WORD') {
          ShowHTML('        <td align="top" nowrap>');
          if ($P1!=3) {
            // Se n�o for acompanhamento
            if ($w_copia > '') {
              // Se for listagem para c�pia
              $RS1 = db_getLinkSubMenu::getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
              foreach($RS1 as $row1) { $RS1 = $row1; break; }
              ShowHTML('          <a accesskey="I" class="HL" href="'.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($RS1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
            } elseif ($P1==1) {
              // Se for cadastramento
              if ($w_submenu>'') ShowHTML('          <A class="HL" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.'&w_documento=Nr. '.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'" title="Altera as informa��es cadastrais do projeto" TARGET="menu">Alterar</a>&nbsp;');
              else               ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do projeto">Alterar</A>&nbsp');
              ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do projeto.">Excluir</A>&nbsp');
            } elseif ($P1==2 || $P1==6) {
              // Se for execu��o ou consulta de usu�rio externo
              if ($w_usuario == f($row,'executor')) {
                if (Nvl(f($row,'solicitante'),0) == $w_usuario || 
                    Nvl(f($row,'titular'),0)     == $w_usuario || 
                    Nvl(f($row,'substituto'),0)  == $w_usuario || 
                    Nvl(f($row,'tit_exec'),0)    == $w_usuario || 
                    Nvl(f($row,'subst_exec'),0)  == $w_usuario || 
                    Nvl(f($row,'resp_etapa'),0)  >  0) {
                   ShowHTML('          <A class="HL" HREF="'.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as etapas do projeto." target="Etapas">EA</A>&nbsp');
                } else {
                   ShowHTML('          <A class="HL" HREF="'.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as etapas do projeto." target="Etapas">EA</A>&nbsp');
                }
                // Permite a visualiza��o ou manuten��o de riscos e problemas
                ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Riscos&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Riscos do projeto." target="Restricao">RS</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&w_problema=S&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Problema&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Problemas do projeto." target="Restricao">PB</A>&nbsp');

                // Coloca as opera��es dependendo do tr�mite
                if (f($row,'sg_tramite')=='EA' || f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es para o projeto, sem envi�-la.">AN</A>&nbsp');
                } 
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o projeto para outro respons�vel.">EN</A>&nbsp');
                if (f($row,'sg_tramite')=='EE') {
                  ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execu��o do projeto.">CO</A>&nbsp');
                } 
              } else {
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Consulta as etapas do projeto." target="Etapas">EA</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Riscos&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Consulta os riscos do projeto." target="Restricao">RS</A>&nbsp');
                ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&w_problema=S&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Problema&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Consulta os problemas do projeto." target="Restricao">PB</A>&nbsp');
                if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="HL" HREF="'.$w_pagina.'Envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o projeto para outro respons�vel.">EN</A>&nbsp');
                }
              } 
            } 
          } else {
            if (Nvl(f($row,'solicitante'),0)== $w_usuario || 
                Nvl(f($row,'titular'),0)    == $w_usuario || 
                Nvl(f($row,'substituto'),0) == $w_usuario || 
                Nvl(f($row,'resp_etapa'),0) >  0) {
              // Se o usu�rio for respons�vel por um projeto ou titular/substituto do setor respons�vel, 
              // pode enviar.
              if (Nvl(f($row,'solicitante'),0)  == $w_usuario || 
                  Nvl(f($row,'titular'),0)      == $w_usuario || 
                  Nvl(f($row,'substituto'),0)   == $w_usuario) {
                ShowHTML('          <A class="HL" HREF="'.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o projeto para outro respons�vel.">EN</A>&nbsp');
                if (f($row,'sg_tramite')!='AT') { 
                  ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Riscos&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Riscos do projeto." target="Restricao">RS</A>&nbsp');
                  ShowHTML('          <A class="HL" HREF="mod_pr/restricao.php?par=Restricao&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&w_problema=S&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'&TP='.$TP.' - Problema&SG=RESTSOLIC'.MontaFiltro('GET').'" title="Problemas do projeto." target="Restricao">PB</A>&nbsp');
                }
              } 
            } 
            ShowHTML('          <A class="HL" HREF="'.$w_pagina.'AtualizaEtapa&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Atualiza as etapas do projeto." target="Etapas">EA</A>&nbsp');
          } 
          ShowHTML('        </td>');         
        } 
        ShowHTML('      </tr>');
      } 
      // Mostra os valor se o usu�rio for interno e n�o for cadastramento nem mesa de trabalho
      if ($P1 != 1 && $P1 != 2 && $_SESSION['INTERNO'] == 'S') {
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'">');
          ShowHTML('          <td colspan=6 align="right"><b>Total desta p�gina&nbsp;</td>');
          ShowHTML('          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a �ltima p�gina da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
           foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total += f($row,'custo_real');
            else                            $w_total += f($row,'valor');
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
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {    
      if ($R > '') MontaBarra($dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
      else         MontaBarra($dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia.MontaFiltro('GET'),ceil(count($RS)/$P4),$P3,$P4,count($RS));
      ShowHTML('</tr>');
    }
  } elseif (strpos('CP',$O)!==false) {
    if ($P1!=1) ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    elseif ($O == 'C') // Se for c�pia 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar o projeto que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    if ($P1 != 1 || $O == 'C') { // Se n�o for cadastramento ou se for c�pia
      // Recupera dados da op��o Projetos
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assifica��o:','L','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('          </table>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td valign="top"><b>N�mero do <U>p</U>rojeto:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td valign="top"><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo projeto na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respons�vel atua<u>l</u>:','L','Selecione o respons�vel atual pelo projeto na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o projeto se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$p_prioridade,null,'p_prioridade',null,null);
      ShowHTML('          <td valign="top"><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b><U>T</U>�tulo:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_assunto" size="25" maxlength="90" value="'.$p_assunto.'"></td>');
      ShowHTML('          <td valign="top" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_palavra" size="25" maxlength="90" value="'.$p_palavra.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td valign="top"><b>In�<u>c</u>io previsto entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('          <td valign="top"><b><u>T</u>�rmino previsto entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);"></td>');
      if ($O!='C') { // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td valign="top"><b>Exibe somente projetos em atraso?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N"> N�o');
        else                ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="p_atraso" value="N" checked> N�o');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase[]',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='ASSUNTO')           ShowHTML('          <option value="assunto" SELECTED>Assunto<option value="inicio">In�cio previsto<option value="">Data T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='INICIO')        ShowHTML('          <option value="assunto">Assunto<option value="inicio" SELECTED>In�cio previsto<option value="">Data T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='NM_TRAMITE')    ShowHTML('          <option value="assunto">Assunto<option value="inicio">In�cio previsto<option value="">Data T�rmino previsto<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PRIORIDADE')    ShowHTML('          <option value="assunto">Assunto<option value="inicio">In�cio previsto<option value="">Data T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Prioridade<option value="proponente">Proponente externo');
    elseif ($p_ordena=='PROPONENTE')    ShowHTML('          <option value="assunto">Assunto<option value="inicio">In�cio previsto<option value="">Data T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente" SELECTED>Proponente externo');
    else                                ShowHTML('          <option value="assunto">Assunto<option value="inicio">In�cio previsto<option value="" SELECTED>Data T�rmino previsto<option value="nm_tramite">Fase atual<option value="prioridade">Prioridade<option value="proponente">Proponente externo');
    ShowHTML('          </select></td>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="2" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="2">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C')// Se for c�pia
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Abandonar c�pia">');
    else
      ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
  $w_copia      = $_REQUEST['w_copia'];
  $w_readonly   = '';
  $w_erro       = '';
  // Verifica se o cliente tem o m�dulo de acordos contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N'; 

  // Verifica se o cliente tem o m�dulo viagens contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N'; 

  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N'; 

  // Verifica se o cliente tem o m�dulo de planejamento estrat�gico
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 

  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_solic_pai                = $_REQUEST['w_solic_pai'];
    $w_chave_pai                = $_REQUEST['w_chave_pai'];    
    $w_proponente               = $_REQUEST['w_proponente'];
    $w_sq_unidade_resp          = $_REQUEST['w_sq_unidade_resp'];
    $w_titulo                   = $_REQUEST['w_titulo'];
    $w_prioridade               = $_REQUEST['w_prioridade'];
    $w_aviso                    = $_REQUEST['w_aviso'];
    $w_dias                     = $_REQUEST['w_dias'];
    $w_inicio_real              = $_REQUEST['w_inicio_real'];
    $w_fim_real                 = $_REQUEST['w_fim_real'];
    $w_concluida                = $_REQUEST['w_concluida'];
    $w_data_conclusao           = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao           = $_REQUEST['w_nota_conclusao'];
    $w_custo_real               = $_REQUEST['w_custo_real'];
    $w_vincula_contrato         = $_REQUEST['w_vincula_contrato'];
    $w_vincula_viagem           = $_REQUEST['w_vincula_viagem'];
    $w_chave                    = $_REQUEST['w_chave'];
    $w_chave_aux                = $_REQUEST['w_chave_aux'];
    $w_sq_menu                  = $_REQUEST['w_sq_menu'];
    $w_sq_unidade               = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite               = $_REQUEST['w_sq_tramite'];
    $w_solicitante              = $_REQUEST['w_solicitante'];
    $w_cadastrador              = $_REQUEST['w_cadastrador'];
    $w_executor                 = $_REQUEST['w_executor'];
    $w_inicio                   = $_REQUEST['w_inicio'];
    $w_fim                      = $_REQUEST['w_fim'];
    $w_inicio_etapa             = $_REQUEST['w_inicio_etapa'];
    $w_fim_etapa                = $_REQUEST['w_fim_etapa'];
    $w_inclusao                 = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao         = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao                = $_REQUEST['w_conclusao'];
    $w_valor                    = $_REQUEST['w_valor'];
    $w_opiniao                  = $_REQUEST['w_opiniao'];
    $w_data_hora                = $_REQUEST['w_data_hora'];
    $w_pais                     = $_REQUEST['w_pais'];
    $w_uf                       = $_REQUEST['w_uf'];
    $w_cidade                   = $_REQUEST['w_cidade'];
    $w_palavra_chave            = $_REQUEST['w_palavra_chave'];
    $w_sqcc                     = $_REQUEST['w_sqcc'];
    $w_sq_menu_relac            = $_REQUEST['w_sq_menu_relac'];
    $w_plano                    = $_REQUEST['w_plano'];
    $w_objetivo                 = $_REQUEST['w_objetivo'];
  } else {
    if (strpos('AEV',$O)!==false or nvl($w_copia,'')!='') {
      // Recupera os dados do projeto
      if ($w_copia > '')  {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,$SG); 
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,$SG);
      }
      if (count($RS)>0) {
        $w_solic_pai            = f($RS,'sq_solic_pai');
        $w_chave_pai            = f($RS,'sq_solic_pai');        
        $w_proponente           = f($RS,'proponente');
        $w_sq_unidade_resp      = f($RS,'sq_unidade_resp');
        $w_titulo               = f($RS,'titulo');
        $w_prioridade           = f($RS,'prioridade');
        $w_aviso                = f($RS,'aviso_prox_conc');
        $w_dias                 = f($RS,'dias_aviso');
        $w_inicio_real          = f($RS,'inicio_real');
        $w_fim_real             = f($RS,'fim_real');
        $w_concluida            = f($RS,'concluida');
        $w_data_conclusao       = f($RS,'data_conclusao');
        $w_nota_conclusao       = f($RS,'nota_conclusao');
        $w_custo_real           = f($RS,'custo_real');
        $w_vincula_contrato     = f($RS,'vincula_contrato');
        $w_vincula_viagem       = f($RS,'vincula_viagem');
        $w_chave_aux            = null;
        $w_sq_menu              = f($RS,'sq_menu');
        $w_sq_unidade           = f($RS,'sq_unidade');
        $w_sq_tramite           = f($RS,'sq_siw_tramite');
        $w_solicitante          = f($RS,'solicitante');
        $w_cadastrador          = f($RS,'cadastrador');
        $w_executor             = f($RS,'executor');
        $w_inicio               = FormataDataEdicao(f($RS,'inicio'));
        $w_fim                  = FormataDataEdicao(f($RS,'fim'));
        $w_inicio_etapa         = FormataDataEdicao(f($RS,'inicio_etapa'));
        $w_fim_etapa            = FormataDataEdicao(f($RS,'fim_etapa'));
        $w_inclusao             = f($RS,'inclusao');
        $w_ultima_alteracao     = f($RS,'ultima_alteracao');
        $w_conclusao            = f($RS,'conclusao');
        $w_valor                = number_format(f($RS,'valor'),2,',','.');
        $w_opiniao              = f($RS,'opiniao');
        $w_data_hora            = f($RS,'data_hora');
        $w_sqcc                 = f($RS,'sq_cc');
        $w_pais                 = f($RS,'sq_pais');
        $w_uf                   = f($RS,'co_uf');
        $w_cidade               = f($RS,'sq_cidade_origem');
        $w_palavra_chave        = f($RS,'palavra_chave');
        $w_sq_menu_relac        = f($RS,'sq_menu_pai');
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
        $w_plano                = f($RS,'sq_plano');
        $w_objetivo             = f($RS,'sq_peobjetivo');
      } 
    } 
  }
  if(nvl($w_sq_menu_relac,0)>0) $RS_Relac = db_getMenuData::getInstanceOf($dbms,$w_sq_menu_relac);
  cabecalho();
  ShowHTML('<HEAD>');
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_titulo','titulo','1',1,5,100,'1','1');
    // Trata as poss�veis vincula��es do projeto
    if($w_pe=='S') {
      ShowHTML('  if (theForm.w_plano.selectedIndex>0 && theForm.w_objetivo.selectedIndex==0) {');
      ShowHTML('    alert(\'Voc� deve indicar o objetivo!\');');
      ShowHTML('    theForm.w_objetivo.focus();');
      ShowHTML('    return false;');
      ShowHTML('  }');
    }
    if(nvl($w_sq_menu_relac,'')>'') {
      if ($w_sq_menu_relac=='CLASSIF') {
        ShowHTML('  if (theForm.w_sqcc.selectedIndex==0) {');
        ShowHTML('    alert(\'Voc� deve indicar a classifica��o!\');');
        ShowHTML('    theForm.w_sqcc.focus();');
        ShowHTML('    return false;');
        ShowHTML('  }');
      } else {
        ShowHTML('  if (theForm.w_solic_pai.selectedIndex==0) {');
        ShowHTML('    alert(\'Voc� deve indicar o documento!\');');
        ShowHTML('    theForm.w_solic_pai.focus();');
        ShowHTML('    return false;');
        ShowHTML('  }');
      }
    }
    Validate('w_solicitante','Respons�vel','HIDDEN',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Setor respons�vel','HIDDEN',1,1,18,'','0123456789');
    Validate('w_prioridade','Prioridade','SELECT',1,1,1,'','0123456789');
    switch (f($RS_Menu,'data_hora')) {
      case 1: Validate('w_fim','T�rmino previsto','DATA',1,10,10,'','0123456789/'); break;
      case 2: Validate('w_fim','T�rmino previsto','DATAHORA',1,17,17,'','0123456789/'); break;
      case 3: Validate('w_inicio','In�cio previsto','DATA',1,10,10,'','0123456789/');
              Validate('w_fim','T�rmino previsto','DATA',1,10,10,'','0123456789/');
              CompData('w_inicio','In�cio previsto','<=','w_fim','T�rmino previsto'); break;
      case 4: Validate('w_inicio','In�cio previsto','DATAHORA',1,17,17,'','0123456789/,: ');
              Validate('w_fim','T�rmino previsto','DATAHORA',1,17,17,'','0123456789/,: ');
              CompData('w_inicio','In�cio previsto','<=','w_fim','T�rmino previsto'); break;
    } 
    if (nvl($w_inicio_etapa,'')!='') {
      CompData('w_inicio','In�cio previsto','<=','w_inicio_etapa','In�cio da primeira etapa da estrutura anal�tica ('.$w_inicio_etapa.')');
      CompData('w_fim','T�rmino previsto','>=','w_fim_etapa','T�rmino da primeira etapa da estrutura anal�tica ('.$w_fim_etapa.')');
    }
    Validate('w_valor','Or�amento dispon�vel','VALOR','1',4,18,'','0123456789.,');
    Validate('w_palavra_chave','Palavras-chave','','',2,90,'1','1');
    Validate('w_proponente','Proponente externo','','',2,90,'1','1');
    Validate('w_pais','Pa�s','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    Validate('w_dias','Dias de alerta','1','',1,3,'','0123456789');
    ShowHTML('  if (theForm.w_aviso[0].checked) {');
    ShowHTML('     if (theForm.w_dias.value == \'\') {');
    ShowHTML('        alert(\'Informe a partir de quantos dias antes da data limite voc� deseja ser avisado de sua proximidade!\');');
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
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (strpos('EV',$O)!==false)BodyOpenClean('onLoad=\'this.focus()\';');
  else  BodyOpenClean('onLoad=\'document.Form.w_titulo.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if ($w_pais=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      $w_pais=f($RS,'sq_pais');
      $w_uf=f($RS,'co_uf');
      $w_cidade=f($RS,'sq_cidade_padrao');
    } 
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=$Validacao[$w_sq_solicitacao][$sg];
    } 
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_etapa" value="'.$w_inicio_etapa.'">');
    ShowHTML('<INPUT type="hidden" name="w_fim_etapa" value="'.$w_fim_etapa.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco ser�o utilizados para identifica��o do projeto, bem como para o controle de sua execu��o.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top"><b><u>T</u>�tulo:</b><br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" title="Informe um t�tulo para o projeto."></td>');
    // Verifica a que objetos o projeto pode ser vinculado
    ShowHTML('          <tr><td><table border=0 colspan=0 cellspan=0 width="100%">');
    if ($w_pe=='S') {
      ShowHTML('      <tr>');
      selecaoPlanoEstrategico('<u>P</u>lano estrat�gico:', 'P', 'Selecione o plano ao qual o programa est� vinculado.', $w_plano, $w_chave, 'w_plano', 'ULTIMO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_objetivo\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      selecaoObjetivoEstrategico('<u>O</u>bjetivo estrat�gico:', 'P', 'Selecione o objetivo estrat�gico ao qual o programa est� vinculado.', $w_objetivo, $w_plano, 'w_objetivo', 'ULTIMO', null);
    }
    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classifica��o:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
      } else {
        SelecaoSolic('Vincula��o:',null,null,$w_cliente,$w_solic_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_solic_pai',f($RS_Relac,'sigla'),null);
      }
    }
    ShowHTML('          </td></tr></table></td></tr>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo projeto na rela��o.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor respons�vel:','S','Selecione o setor respons�vel pela execu��o do projeto',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,null);
    SelecaoPrioridade('<u>P</u>rioridade:','P','Informe a prioridade deste projeto.',$w_prioridade,null,'w_prioridade',null,null);
    ShowHTML('          <tr valign="top">');
    switch (f($RS_Menu,'data_hora')) {
      case 1: ShowHTML('              <td valign="top"><b><u>T</u>�rmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Data limite para que a execu��o do projeto esteja conclu�do.">'.ExibeCalendario('Form','w_fim').'</td>'); break;
      case 2: ShowHTML('              <td valign="top"><b><u>T</u>�rmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora limite para que a execu��o do projeto esteja conclu�do."></td>'); break;
      case 3: ShowHTML('              <td valign="top"><b>In�<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" title="In�cio previsto da solicita��o.">'.ExibeCalendario('Form','w_inicio').'</td>');
              ShowHTML('              <td valign="top"><b><u>T</u>�rmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" title="Data limite para que a execu��o do projeto esteja conclu�do.">'.ExibeCalendario('Form','w_fim').'</td>'); break;
      case 4: ShowHTML('              <td valign="top"><b>In�<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora de in�cio previsto do projeto."></td>');
              ShowHTML('              <td valign="top"><b><u>T</u>�rmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim.'" onKeyDown="FormataDataHora(this,event);" title="Data/hora limite para que a execu��o do projeto esteja conclu�do."></td>'); break;
    } 
    ShowHTML('              <td><b>O<u>r</u>�amento dispon�vel:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o or�amento dispon�vel para execu��o do projeto, ou zero se n�o for o caso."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td><b>Pa<u>l</u>avras-chave:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="w_palavra_chave" size="90" maxlength="90" value="'.$w_palavra_chave.'" title="Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identifica��o deste projeto."></td>');
    ShowHTML('      <tr><td><b>Nome do proponent<u>e</u> externo:<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_proponente" size="90" maxlength="90" value="'.$w_proponente.'" title="Proponente externo do projeto. Preencha apenas se houver."></td>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Local da execu��o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco identificam o local onde o projeto ser� executado, sendo utilizados para consultas gerenciais por distribui��o geogr�fica.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('          </table>');
    if ($w_acordo=='S' || $w_viagem=='S') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Informa��es adicionais</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores do projeto.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0><tr valign="top">');
      if ($w_acordo=='S') MontaRadioNS('<b>Permite a vincula��o de contratos?</b>',Nvl($w_vincula_contrato,'N'),'w_vincula_contrato');
      if ($w_viagem=='S') MontaRadioNS('<b>Permite a vincula��o de viagens?</b>',Nvl($w_vincula_viagem,'N'),'w_vincula_viagem');
      ShowHTML('          </table>');
    } 
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Alerta de atraso</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade da data T�rmino previsto do projeto.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('          <tr valign="top">');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="N�mero de dias para emiss�o do alerta de proximidade da data T�rmino previsto do projeto."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I'){
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 


// =========================================================================
// Rotina dos descritivos
// -------------------------------------------------------------------------
function Descritivo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_readonly   = '';
  $w_erro       = '';
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca> '' && $O!='E') {
    // Se for recarga da p�gina
    $w_solic_pai                = $_REQUEST['w_solic_pai'];
    $w_chave_pai                = $_REQUEST['w_chave_pai'];    
    $w_objetivo_superior        = $_REQUEST['w_objetivo_superior'];
    $w_descricao                = $_REQUEST['w_descricao'];
    $w_justificativa            = $_REQUEST['w_justificativa'];
    $w_exclusoes                = $_REQUEST['w_exclusoes'];
    $w_premissas                = $_REQUEST['w_premissas'];
    $w_restricoes               = $_REQUEST['w_restricoes'];    
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do projeto
      if ($w_copia > '')  {   
        $RS = db_getSolicData::getInstanceOf($dbms,$w_copia,'PJGERAL'); 
      } else {
        $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
      }
      if (count($RS)>0) {
        $w_solic_pai            = f($RS,'sq_solic_pai');
        $w_chave_pai            = f($RS,'sq_solic_pai');        
        $w_objetivo_superior    = f($RS,'objetivo_superior');
        $w_descricao            = f($RS,'descricao');
        $w_justificativa        = f($RS,'justificativa');
        $w_exclusoes            = f($RS,'exclusoes');
        $w_premissas            = f($RS,'premissas');
        $w_restricoes           = f($RS,'restricoes');   
      } 
    } 
  }
  if(nvl($w_sq_menu_relac,0)>0) $RS_Relac = db_getMenuData::getInstanceOf($dbms,$w_sq_menu_relac);
  cabecalho();
  ShowHTML('<HEAD>');
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_objetivo_superior','Objetivo Superior','1','',5,2000,'1','1');
    Validate('w_descricao','Objetivos espec�ficos','1','',5,2000,'1','1');
    Validate('w_exclusoes','Exclus�es','1','',5,2000,'1','1');
    Validate('w_premissas','Premissas','1','',5,2000,'1','1');
    Validate('w_restricoes','Restri��es','1','',5,2000,'1','1');          
    Validate('w_justificativa','Observa��es','1','',5,2000,'1','1');  
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (strpos('EV',$O)!==false)BodyOpenClean('onLoad=\'this.focus()\';');
  else  BodyOpenClean('onLoad=\'document.Form.w_objetivo_superior.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro=$Validacao[$w_sq_solicitacao][$sg];
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
    ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><b>Descritivos</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco visam orientar os executores do projeto.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><b><u>O</u>bjetivo superior:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objetivo_superior" class="STI" ROWS=5 cols=75 title="Descreva o objetivo superior projeto.">'.$w_objetivo_superior.'</TEXTAREA></td>');
    if (f($RS_Menu,'descricao')=='S')     ShowHTML('      <tr><td><b>Objetivos <u>E</u>spec�ficos:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os objetivos espec�ficos esperados ap�s a execu��o do projeto.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>E<u>x</u>clus�es espec�ficas:</b><br><textarea '.$w_Disabled.' accesskey="X" name="w_exclusoes" class="STI" ROWS=5 cols=75 title="Descreva as exclus�es espec�ficas esperadas ap�s a execu��o do projeto.">'.$w_exclusoes.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b><u>P</u>remissas:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_premissas" class="STI" ROWS=5 cols=75 title="Descreva as premissas esperadas ap�s a execu��o do projeto.">'.$w_premissas.'</TEXTAREA></td>'); 
    ShowHTML('      <tr><td><b>R<u>e</u>stri��es:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_restricoes" class="STI" ROWS=5 cols=75 title="Descreva as restri��es esperadas ap�s a execu��o do projeto.">'.$w_restricoes.'</TEXTAREA></td>');
    if (f($RS_Menu,'justificativa')=='S') ShowHTML('      <tr><td><b>Obse<u>r</u>va��es:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Relacione recomenda��es e observa��es a serem seguidas na execu��o do projeto.">'.$w_justificativa.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    if ($O=='I'){
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado 
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','T�tulo','1','1','1','255','1','1');
      Validate('w_descricao','Descri��o','1','1','1','1000','1','1');
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
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem 
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>T�tulo</td>');
    ShowHTML('          <td><b>Descri��o</td>');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>KB</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem 
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
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
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</font></b>.</td>');
      ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    }  
    ShowHTML('      <tr><td><b><u>T</u>�tulo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nome" class="STI" SIZE="75" MAXLENGTH="255" VALUE="'.$w_nome.'" title="OBRIGAT�RIO. Informe um t�tulo para o arquivo."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=65 title="OBRIGAT�RIO. Descreva a finalidade do arquivo.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo. Ele ser� transferido automaticamente para o servidor.">');
    if ($w_caminho>'') {
      ShowHTML('              <b>'.LinkArquivo('SS',$w_cliente,$w_caminho,'_blank','Clique para exibir o arquivo atual.','Exibir',null).'</b>');
    } 
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir" onClick="return confirm(\'Confirma a exclus�o do registro?\');">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);' 
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de rubrica do projeto
// -------------------------------------------------------------------------
function Rubrica() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_sq_cc                = $_REQUEST['w_sq_cc'];
    $w_codigo               = $_REQUEST['w_codigo'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_ativo                = $_REQUEST['w_ativo'];
    $w_aplicacao_financeira = $_REQUEST['w_aplicacao_financeira'];    
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicRubrica::getInstanceOf($dbms,$w_chave,null,null,null,null,null);
    $RS = SortArray($RS,'codigo','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getsolicRubrica::getInstanceOf($dbms,$w_chave,$w_chave_aux,null,null,null,null);
    foreach ($RS as $row) { $RS = $row; break; }
    $w_sq_cc                = f($RS,'sq_cc');
    $w_codigo               = f($RS,'codigo');
    $w_nome                 = f($RS,'nome');
    $w_descricao            = f($RS,'descricao');
    $w_ativo                = f($RS,'ativo');
    $w_aplicacao_financeira = f($RS,'aplicacao_financeira');
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se a etapa n�o tiver respons�vel atribu�do, recupera o respons�vel pelo projeto
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
    $w_sq_pessoa    = f($RS,'solicitante');
    $w_sq_unidade   = f($RS,'sq_unidade_resp');
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_sq_cc','Classifica��o','SELECT',1,1,18,'','0123456789');
      Validate('w_codigo','C�digo','','1','2','20','1','1');
      Validate('w_nome','Nome','','1','2','60','1','1');      
      Validate('w_descricao','Descricao','','1','2','500','1','1');     
      //CompData('w_inicio','In�cio previsto','<=','w_fim','Fim previsto');     
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='L' || $O=='E') BodyOpenClean('onLoad=\'this.focus()\';');
  else BodyOpen('onLoad=\'document.Form.w_sq_cc.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Codigo</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Aplic. financeira</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'codigo').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_aplicacao_financeira').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_rubrica').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_rubrica').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    else       MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
    //Aqui come�a a manipula��o de registros
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    SelecaoCC('C<u>l</u>assifica��o:','L','Selecione a classifica��o desejada.',$w_sq_cc,null,'w_sq_cc','SIWSOLIC');
    ShowHTML('      <tr><td><b><u>C</u>�digo:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="STI" SIZE="30" MAXLENGTH="20" VALUE="'.$w_codigo.'" title="Informe um c�digo para a rubrica."></td>'); 
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="30" MAXLENGTH="60" VALUE="'.$w_nome.'" title="Informe um nome para a rubrica."></td>');
    ShowHTML('      <tr><td><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os objetivos da etapa e os resultados esperados ap�s sua execu��o.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Aplica��o financeira</b>?',$w_aplicacao_financeira,'w_aplicacao_financeira');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de etapas do projeto
// -------------------------------------------------------------------------
function Etapas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_pacote     = 'N'; // Garante default N para a vari�vel

  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_ordem                = $_REQUEST['w_ordem'];
    $w_titulo               = $_REQUEST['w_titulo'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
    $w_inicio_real          = $_REQUEST['w_inicio_real'];
    $w_fim_real             = $_REQUEST['w_fim_real'];
    $w_perc_conclusao       = $_REQUEST['w_perc_conclusao'];
    $w_orcamento            = $_REQUEST['w_orcamento'];
    $w_sq_pessoa            = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_vincula_atividade    = $_REQUEST['w_vincula_atividade'];
    $w_vincula_contrato     = $_REQUEST['w_vincula_contrato'];
    $w_pais                 = $_REQUEST['w_pais'];
    $w_regiao               = $_REQUEST['w_regiao'];
    $w_uf                   = $_REQUEST['w_uf'];
    $w_cidade               = $_REQUEST['w_cidade'];
    $w_base                 = $_REQUEST['w_base'];
    $w_pacote               = $_REQUEST['w_pacote'];
    $w_filhos               = $_REQUEST['w_filhos'];
    $w_peso                 = $_REQUEST['w_peso'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
    $RS = SortArray($RS,'ordem','asc');
    foreach($RS as $row){$RS=$row; break;}
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null);
    foreach ($RS as $row) { $RS = $row; break; }
    $w_chave_pai            = f($RS,'sq_etapa_pai');
    $w_titulo               = f($RS,'titulo');
    $w_ordem                = f($RS,'ordem');
    $w_descricao            = f($RS,'descricao');
    $w_inicio               = f($RS,'inicio_previsto');
    $w_fim                  = f($RS,'fim_previsto');
    $w_inicio_real          = f($RS,'inicio_real');
    $w_fim_real             = f($RS,'fim_real');
    $w_perc_conclusao       = f($RS,'perc_conclusao');
    $w_orcamento            = formatNumber(f($RS,'orcamento'));
    $w_sq_pessoa            = f($RS,'sq_pessoa');
    $w_sq_unidade           = f($RS,'sq_unidade');
    $w_vincula_atividade    = f($RS,'vincula_atividade');
    $w_vincula_contrato     = f($RS,'vincula_contrato');
    $w_pais                 = f($RS,'sq_pais');
    $w_regiao               = f($RS,'sq_regiao');
    $w_uf                   = f($RS,'co_uf');
    $w_cidade               = f($RS,'sq_cidade');
    $w_base                 = f($RS,'base_geografica');
    $w_pacote               = f($RS,'pacote_trabalho');
    $w_filhos               = f($RS,'qt_filhos');
    $w_peso                 = f($RS,'peso');
  }
  
  // Define o valor default do campo "Base geogr�fica" como "Organizacional"
  if ($w_pacote=='S' && $O=='I' && nvl($w_base,'')=='') $w_base = 5;
  
  $RS_Projeto = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
  $w_inicio_pai = formataDataEdicao(f($RS_Projeto,'inicio'));
  $w_fim_pai    = formataDataEdicao(f($RS_Projeto,'fim'));
  $w_valor_pai  = formatNumber(f($RS_Projeto,'valor'));
  if (Nvl($w_sq_pessoa,'')=='') {
    // Se a etapa n�o tiver respons�vel atribu�do, recupera o respons�vel pelo projeto
    $w_sq_pessoa    = f($RS_Projeto,'solicitante');
    $w_sq_unidade   = f($RS_Projeto,'sq_unidade_resp');
  } 

  // Recupera o n�mero de ordem das outras op��es irm�s � selecionada
  $RS = db_getEtapaOrder::getInstanceOf($dbms, $w_chave, $w_chave_aux, $w_chave_pai);
  $RS = SortArray($RS,'ordena','asc');
  if (!count($RS)<=0) {
    $w_texto_titulo = '<b>Dados das etapas de mesma subordina��o:</b>:<br>';
    $w_texto = '<table border=1 bgcolor="#FAEBD7">'.
               '<tr valign="top" align=center><td><b>Ordem<td><b>T�tulo<td><b>In�cio<td><b>Fim<td><b>Or�amento<td><b>Peso';
    foreach ($RS as $row) {
      if (f($row,'ordena')=='0') {
        $w_texto .= '<tr valign=top>';
        $w_texto .= '  <td>';
        $w_texto .= '  <td><b>'.f($row,'titulo');
        $w_texto .= '  <td align="center"><b>'.formataDataEdicao(f($row,'inicio_previsto'));
        $w_texto .= '  <td align="center"><b>'.formataDataEdicao(f($row,'fim_previsto'));
        $w_texto .= '  <td align="right"><b>'.formatNumber(f($row,'orcamento'));
        $w_texto .= '  <td align="center"><b>'.f($row,'peso');
        $w_texto_titulo = '<b>Dados da etapa superior e das etapas de mesma subordina��o:</b>:<br>';
        $w_inicio_pai = formataDataEdicao(f($row,'inicio_previsto'));
        $w_fim_pai    = formataDataEdicao(f($row,'fim_previsto'));
        if (nvl($w_troca,'nulo')=='nulo') {
          $w_valor_pai  = formatNumber(f($row,'saldo_pai') - f($row,'alocado'));
        } else {
          $w_valor_pai  = formatNumber(f($row,'orcamento'));
        }
      } else {
        $w_texto .= '<tr valign=top>';
        $w_texto .= '  <td align=center>'.f($row,'ordem');
        $w_texto .= '  <td>'.f($row,'titulo');
        $w_texto .= '  <td align="center">'.formataDataEdicao(f($row,'inicio_previsto'));
        $w_texto .= '  <td align="center">'.formataDataEdicao(f($row,'fim_previsto'));
        $w_texto .= '  <td align="right">'.formatNumber(f($row,'orcamento'));
        $w_valor_pai  = formatNumber(f($row,'saldo_pai') - f($row,'alocado'));
        $w_texto .= '  <td align="center">'.f($row,'peso');
      }
    } 
    $w_texto .= '</table>';
    $w_texto = $w_texto_titulo.$w_texto;
  } else {
    $w_texto='N�o h� outros n�meros de ordem subordinados a esta etapa.';
  }

  cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_peso','Peso','1','1','1','2','','0123456789');
      CompValor('w_peso','Peso da etapa','>=',1,'1');
      Validate('w_titulo','T�tulo','','1','2','100','1','1');
      Validate('w_descricao','Descricao','','1','2','2000','1','1');
      Validate('w_ordem','Ordem','1','1','1','3','','0123456789');
      Validate('w_chave_pai','Subordina��o','SELECT','','1','10','','1');
      Validate('w_inicio','In�cio previsto','DATA','1','10','10','','0123456789/');
      Validate('w_fim','Fim previsto','DATA','1','10','10','','0123456789/');
      CompData('w_inicio','In�cio previsto','<=','w_fim','Fim previsto');
      CompData('w_inicio','In�cio previsto','>=',$w_inicio_pai,$w_inicio_pai);
      CompData('w_fim','Fim previsto','<=',$w_fim_pai,$w_fim_pai);
      Validate('w_orcamento','Or�amento dispon�vel','VALOR','1','4','18','','0123456789.,');
      CompValor('w_orcamento','Or�amento dispon�vel','<=',$w_valor_pai,$w_valor_pai);
      Validate('w_sq_pessoa','Respons�vel','HIDDEN','1','1','10','','1');
      Validate('w_sq_unidade','Setor respons�vel','HIDDEN','1','1','10','','1');
      if ($w_pacote=='S') {
        Validate('w_base','Base geogr�fica','SELECT','1','1','18','','1');
        if (nvl($w_base,5)!=5) {
          Validate('w_pais','Pa�s','SELECT','1','1','18','','1');
          if ($w_base==2) Validate('w_regiao','Regi�o','SELECT','1','1','18','','1');
          if ($w_base==3 || $w_base==4) Validate('w_uf','Estado','SELECT','1','1','18','1','1');
          if ($w_base==4) Validate('w_cidade','Cidade','SELECT','1','1','18','','1');
        }
      }
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='L' || $O=='E') BodyOpenClean('onLoad=\'this.focus()\';');
  else BodyOpen('onLoad=\'document.Form.w_titulo.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    $RS1 = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>Etapa</td>');
    ShowHTML('          <td rowspan=2><b>T�tulo</td>');
    ShowHTML('          <td rowspan=2><b>Respons�vel</td>');
    ShowHTML('          <td rowspan=2><b>Setor</td>');
    ShowHTML('          <td colspan=2><b>Execu��o prevista</td>');
    ShowHTML('          <td colspan=2><b>Execu��o real</td>');
    ShowHTML('          <td rowspan=2><b>Or�amento</td>');
    ShowHTML('          <td rowspan=2><b>Conc.</td>');
    ShowHTML('          <td rowspan=2><b>Peso</td>');
    ShowHTML('          <td rowspan=2><b>Tar.</td>');
    if(f($RS1,'vincula_contrato')=='S') ShowHTML('          <td rowspan=2><b>Contr.</td>');
    ShowHTML('          <td rowspan=2><b>Opera��es</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>De</td>');
    ShowHTML('          <td><b>At�</td>');
    ShowHTML('          <td><b>De</td>');
    ShowHTML('          <td><b>At�</td>');
    ShowHTML('        </tr>');
    // Recupera as etapas principais
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'ARVORE',null);
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=13 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Etapas do projeto
      // Recupera todos os registros para a listagem
      $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
      $RS1 = SortArray($RS1,'ordem','asc');
      // Recupera o c�digo da op��o de menu  a ser usada para listar as tarefas
      $w_p2 = '';
      $w_p3 = '';
      foreach ($RS1 as $row1) {
        if (Nvl(f($row1,'P2'),0) > 0) $w_p2 = f($row1,'P2');
        if (Nvl(f($row1,'P3'),0) > 0) $w_p3 = f($row1,'P3');
      } 
      reset($RS1);
      // Se n�o foram selecionados registros, exibe mensagem
      // Monta fun��o JAVASCRIPT para fazer a chamada para a lista de tarefas
      if ($w_p2 > '') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (projeto, etapa) {');
        ShowHTML('    document.Form.p_projeto.value=projeto;');
        ShowHTML('    document.Form.p_atividade.value=etapa;');
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p3);
        ShowHTML('    document.Form.action=\''.f($RS1,'link').'\';');
        ShowHTML('    document.Form.P2.value=\''.w_p2.'\';');
        ShowHTML('    document.Form.SG.value=\''.f($RS1,'sigla').'\';');
        ShowHTML('    document.Form.p_agrega.value=\'GRDMETAPA\';');
        $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p2,null,null);
        $RS1 = SortArray($RS1,'ordem','asc');
        ShowHTML('    document.Form.p_fase.value=\'\';');
        $w_fases='';
        foreach($RS1 as $row1) {
          if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
        } 
        ShowHTML('    document.Form.p_fase.value=\''.substr($w_fases,1,100).'\';');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
      }
      // Monta fun��o JAVASCRIPT para fazer a chamada para a lista de contratos
      if ($w_p3 > '') {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function listac (projeto, etapa) {');
        ShowHTML('    document.Form.p_projeto.value=projeto;');
        ShowHTML('    document.Form.p_atividade.value=etapa;');
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p3);
        ShowHTML('    document.Form.action=\''.f($RS1,'link').'\';');
        ShowHTML('    document.Form.P2.value=\''.w_p3.'\';');
        ShowHTML('    document.Form.SG.value=\''.f($RS1,'sigla').'\';');
        ShowHTML('    document.Form.p_agrega.value=\''.substr(f($RS1,'sigla'),0,3).'ETAPA\';');
        $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p3,null,null);
        $RS1 = SortArray($RS1,'ordem','asc');
        ShowHTML('    document.Form.p_fase.value=\'\';');
        $w_fases='';
        foreach($RS1 as $row1) {
          if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
        } 
        ShowHTML('    document.Form.p_fase.value=\''.substr($w_fases,1,100).'\';');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
      }      
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
      AbreForm('Form',f($RS1,'link'),'POST','return(Validacao(this));','Tarefas',3,$w_p2,1,null,$w_TP,f($RS1,'sigla'),$w_pagina.$par,'L');
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('<input type="Hidden" name="p_projeto" value="">');
      ShowHTML('<input type="Hidden" name="p_atividade" value="">');
      ShowHTML('<input type="Hidden" name="p_agrega" value="">');
      ShowHTML('<input type="Hidden" name="p_fase" value="">');
      foreach($RS as $row) {
        ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'perc_conclusao'),f($row,'qt_ativ'),((f($row,'pacote_trabalho')=='S') ? '<b>' : ''),'S','PROJETO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),(f($row,'level')-1),f($row,'restricao'),f($row,'peso')));
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td colspan=9><b>Observa��o: Pacotes de trabalho destacados em negrito.');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('IA',$O)!==false) {
      ShowHTML('      <tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('        ATEN��O:<ul>');
      ShowHTML('        <li>O per�odo previsto para execu��o desta etapa deve estar contido no per�odo de '.$w_inicio_pai.' a '.$w_fim_pai.'.');
      ShowHTML('        <li>O or�amento previsto para execu��o desta etapa n�o pode ser superior a '.$w_valor_pai.'.');
      ShowHTML('        </ul></b></font></td>');
      ShowHTML('      </tr>');

      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      if ($w_pais=='')   $w_pais     = f($RS,'sq_pais');
      if ($w_regiao=='') $w_regiao   = f($RS,'sq_regiao');
      if ($w_uf=='')     $w_uf       = f($RS,'co_uf');
      if ($w_cidade=='') $w_cidade   = f($RS,'sq_cidade_padrao');
    }
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_programada" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_cumulativa" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_quantidade" value="0">');
    ShowHTML('<INPUT type="hidden" name="w_filhos" value="'.$w_filhos.'">');
    ShowHTML('<INPUT type="hidden" name="w_perc_conclusao" value="'.$w_perc_conclusao.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    if ($w_filhos==0) {
      MontaRadioNS('<b>� pacote de trabalho?</b>',$w_pacote,'w_pacote','Marque SIM para indicar que a etapa tem entrega de produto/servi�o.',null,'onClick="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_titulo\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_pacote" value="N">');
    }
    ShowHTML('        <td colspan=2><b><u>P</u>eso da etapa:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="STI" NAME="w_peso" SIZE=2 MAXLENGTH=2 VALUE="'.nvl($w_peso,1).'" '.$w_Disabled.' title="Informe o peso da etapa no c�lculo do percentual de execu��o."></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan=3><b>T�t<u>u</u>lo:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_titulo" class="STI" SIZE="90" MAXLENGTH="90" VALUE="'.$w_titulo.'" title="Informe um t�tulo para a etapa."></td>');
    ShowHTML('      <tr><td colspan=3><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva os objetivos da etapa e os resultados esperados ap�s sua execu��o.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoEtapa('Eta<u>p</u>a superior:','P','Se necess�rio, indique a etapa superior a esta.',$w_chave_pai,$w_chave,$w_chave_aux,'w_chave_pai','Pesquisa','onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_ordem\'; document.Form.submit();"');
    ShowHTML('      </table>');
    ShowHTML('      <tr valign="top"><td colspan=3>'.$w_texto);
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>O</u>rdem:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="STI" NAME="w_ordem" SIZE=3 MAXLENGTH=3 VALUE="'.$w_ordem.'" '.$w_Disabled.' title="Confira abaixo os outros n�meros de ordem desse n�vel."></td>');
    ShowHTML('        <td><b>In�<u>c</u>io previsto:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao(Nvl($w_inicio,time())).'" onKeyDown="FormataData(this,event);" title="Data prevista para in�cio da etapa.">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('        <td><b><u>T</u>�rmino previsto:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim).'" onKeyDown="FormataData(this,event);" title="Data prevista para t�rmino da etapa.">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b>Or�a<u>m</u>ento previsto:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_orcamento" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_orcamento.'" onKeyDown="FormataValor(this,18,2,event);" title="Or�amento previsto para execu��o desta etapa."></td>');
    MontaRadioSN('<b>Permite vincula��o de tarefas?</b>',$w_vincula_atividade,'w_vincula_atividade','Marque SIM se desejar que tarefas sejam vinculadas a esta etapa.');
    MontaRadioNS('<b>Permite vincula��o de contratos?</b>',$w_vincula_contrato,'w_vincula_contrato','Marque SIM se desejar que contratos sejam vinculados a esta etapa.');
    if ($w_pacote=='N') {
      ShowHTML('<INPUT type="hidden" name="w_perc_conclusao" value="0">');
    } else {
      ShowHTML('      <tr valign="top">');
      selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da atua��o, execu��o, entrega ou impacto.',$w_base,null,null,'w_base',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_base\'; document.Form.submit();"');
      if (nvl($w_base,-1)!=5) {
        ShowHTML('      <tr valign="top">');
        if ($w_base==1) SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,null);
        if ($w_base==2) {
          SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_regiao\'; document.Form.submit();"');
          SelecaoRegiao('<u>R</u>egi�o:','R',null,$w_regiao,$w_pais,'w_regiao',null,null);
        }
        if ($w_base==3) {
          SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
          SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,null);
        }
        if ($w_base==4) {
          SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
          SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
          SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
        }
      }
    }
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('Respo<u>n</u>s�vel pela etapa:','N','Selecione o respons�vel pela etapa na rela��o.',$w_sq_pessoa,null,'w_sq_pessoa','USUARIOS');
    ShowHTML('              <td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr>');
    SelecaoUnidade('<U>S</U>etor respons�vel pela etapa:','S','Selecione o setor respons�vel pela execu��o da etapa',$w_sq_unidade,null,'w_sq_unidade',null,null);
    ShowHTML('                  </table>');
    ShowHTML('          <tr>');
    ShowHTML('      <tr>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de atualiza��o das etapas do projeto
// -------------------------------------------------------------------------
function AtualizaEtapa() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
  $w_cabecalho  = f($RS,'titulo').' ('.$w_chave.')';
  // Configura uma vari�vel para testar se as etapas podem ser atualizadas.
  // Projetos conclu�dos ou cancelados n�o podem ter permitir a atualiza��o.
  if (Nvl(f($RS,'sg_tramite'),'--') == 'EE') $w_fase = 'S'; else $w_fase = 'N';
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_ordem             = $_REQUEST['w_ordem'];
    $w_titulo            = $_REQUEST['w_titulo'];
    $w_descricao         = $_REQUEST['w_descricao'];
    $w_inicio            = $_REQUEST['w_inicio'];
    $w_fim               = $_REQUEST['w_fim'];
    $w_inicio_real       = $_REQUEST['w_inicio_real'];
    $w_fim_real          = $_REQUEST['w_fim_real'];
    $w_perc_conclusao    = $_REQUEST['w_perc_conclusao'];
    $w_orcamento         = $_REQUEST['w_orcamento'];
    $w_sq_pessoa         = $_REQUEST['w_sq_pessoa'];
    $w_sq_unidade        = $_REQUEST['w_sq_unidade'];
    $w_vincula_atividade = $_REQUEST['w_vincula_atividade'];
    $w_vincula_contrato  = $_REQUEST['w_vincula_contrato'];
    $w_pacote            = $_REQUEST['w_pacote'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
    $RS = SortArray($RS,'ordem','asc');
    // Recupera o c�digo da op��o de menu  a ser usada para listar as tarefas
    $w_p2 = '';
    if (count($RS)>0) {
      foreach ($RS as $row) { if (Nvl(f($row,'P2'),0) > 0) $w_p2 = f($row,'P2'); } 
      reset($RS);
    } 
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null);
    foreach ($RS as $row) {
      $w_chave_pai                = f($row,'sq_etapa_pai');
      $w_titulo                   = f($row,'titulo');
      $w_ordem                    = f($row,'ordem');
      $w_descricao                = f($row,'descricao');
      $w_inicio                   = FormataDataEdicao(f($row,'inicio_previsto'));
      $w_fim                      = FormataDataEdicao(f($row,'fim_previsto'));
      $w_inicio_real              = FormataDataEdicao(f($row,'inicio_real'));
      $w_fim_real                 = FormataDataEdicao(f($row,'fim_real'));
      $w_perc_conclusao           = f($row,'perc_conclusao');
      $w_orcamento                = f($row,'orcamento');
      $w_sq_pessoa                = f($row,'sq_pessoa');
      $w_sq_unidade               = f($row,'sq_unidade');
      $w_vincula_atividade        = f($row,'vincula_atividade');
      $w_vincula_contrato         = f($row,'vincula_contrato');
      $w_ultima_atualizacao       = f($row,'phpdt_data');
      $w_sq_pessoa_atualizacao    = f($row,'sq_pessoa_atualizacao');
      $w_situacao_atual           = f($row,'situacao_atual');
      $w_pacote                   = f($row,'pacote_trabalho');
      $w_peso                     = f($row,'peso');
      $w_nm_base_geografica       = f($row,'nm_base_geografica');
      break;
    }
  } elseif (Nvl($w_sq_pessoa,'')=='') {
    // Se a etapa n�o tiver respons�vel atribu�do, recupera o respons�vel pelo projeto
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
    $w_sq_pessoa    = f($RS,'solicitante');
    $w_sq_unidade   = f($RS,'sq_unidade_resp');
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Etapas de projeto</TITLE>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      if ($w_pacote=='S') {
        Validate('w_perc_conclusao','Percentual de conclus�o','','1','1','3','','0123456789');
        CompValor('w_perc_conclusao','Percentual de conclus�o','<=',100,'100');
        ShowHTML('  if ((theForm.w_perc_conclusao.value == 100 )){');
        Validate('w_inicio_real','In�cio real','DATA','1','10','10','','0123456789/');
        Validate('w_fim_real','T�rmino real','DATA','1','10','10','','0123456789/');
        ShowHTML('  } else {');
        Validate('w_inicio_real','In�cio real','DATA','1','10','10','','0123456789/');
        Validate('w_fim_real','T�rmino real','DATA','','10','10','','0123456789/');
        ShowHTML('    }');
        CompData('w_inicio_real','In�cio real','<=', 'w_fim_real','T�rmino real');
      }
      Validate('w_situacao_atual','Situa��o atual','','','5','4000','1','1');
    }
    if ($P1==2) {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    }     
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    if ($w_pacote=='N') {
      BodyOpenClean('onLoad=\'document.Form.w_situacao_atual.focus()\';');
    } else {
      BodyOpenClean('onLoad=\'document.Form.w_perc_conclusao.focus()\';');
    }
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.substr($w_TP,0,(strpos($w_TP,'-')-1)).'- Etapas'.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.$w_cabecalho.'</b></font></div></td></tr>');
  ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');

  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td>');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2><b>Etapa</td>');
    ShowHTML('          <td rowspan=2><b>T�tulo</td>');
    ShowHTML('          <td rowspan=2><b>Respons�vel</td>');
    ShowHTML('          <td rowspan=2><b>Setor</td>');
    ShowHTML('          <td colspan=2><b>Execu��o Prevista</td>');
    ShowHTML('          <td colspan=2><b>Execu��o Real</td>');
    ShowHTML('          <td rowspan=2><b>Or�amento</td>');
    ShowHTML('          <td rowspan=2><b>Conc.</td>');
    ShowHTML('          <td rowspan=2><b>Peso</td>');
    ShowHTML('          <td rowspan=2><b>Tar.</td>');   
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
    if (f($RS,'vincula_contrato')==S)  ShowHTML('          <td rowspan=2><b>Contr.</td>');    
    ShowHTML('          <td rowspan=2><b>Opera��es</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>De</td>');
    ShowHTML('          <td><b>At�</td>');
    ShowHTML('          <td><b>De</td>');
    ShowHTML('          <td><b>At�</td>');
    ShowHTML('        </tr>');
    // Recupera as etapas principais
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'ARVORE',null);
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Monta fun��o JAVASCRIPT para fazer a chamada para a lista de tarefas
      if (Nvl($w_p2,0) > 0) {
        ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
        ShowHTML('  function lista (projeto, etapa) {');
        ShowHTML('    document.Form.p_projeto.value=projeto;');
        ShowHTML('    document.Form.p_atividade.value=etapa;');
        ShowHTML('    document.Form.p_agrega.value=\'GRDMETAPA\';');
        $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p2,null,null);
        $RS1 = SortArray($RS1,'ordem','asc');
        ShowHTML('    document.Form.p_fase.value=\'\';');
        $w_fases = '';
        foreach ($RS1 as $row1) {
          if (f($row1,'sigla')!='CA') $w_fases = $w_fases.','.f($row1,'sq_siw_tramite');
        } 
        ShowHTML('    document.Form.p_fase.value=\''.substr($w_fases,1,100).'\';');
        ShowHTML('    document.Form.submit();');
        ShowHTML('  }');
        ShowHTML('</SCRIPT>');
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
        AbreForm('Form',f($RS1,'link'),'POST','return(Validacao(this));','Tarefas',3,$w_p2,1,null,$w_TP,f($RS1,'sigla'),$w_pagina.$par,'L');
        ShowHTML('<input type="Hidden" name="p_projeto" value="">');
        ShowHTML('<input type="Hidden" name="p_atividade" value="">');
        ShowHTML('<input type="Hidden" name="p_agrega" value="">');
        ShowHTML('<input type="Hidden" name="p_fase" value="">');
      } 
      foreach ($RS as $row) {
        if (Nvl(f($row,'tit_exec'),0)   == $w_usuario || 
            Nvl(f($row,'sub_exec'),0)   == $w_usuario || 
            Nvl(f($row,'titular'),0)    == $w_usuario || 
            Nvl(f($row,'substituto'),0) == $w_usuario || 
            Nvl(f($row,'solicitante'),0)== $w_usuario ||  
            Nvl(f($row,'sq_pessoa'),0)  == $w_usuario) {
          ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'perc_conclusao'),f($row,'qt_ativ'),((f($row,'pacote_trabalho')=='S') ? '<b>' : ''),$w_fase,'ETAPA',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),(f($row,'level')-1),f($row,'restricao'),f($row,'peso')));
        } else {
          ShowHtml(EtapaLinha($w_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'perc_conclusao'),f($row,'qt_ativ'),((f($row,'pacote_trabalho')=='S') ? '<b>' : ''),'N','ETAPA',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),(f($row,'level')-1),f($row,'restricao'),f($row,'peso')));
        } 
      } 
      ShowHTML('      </FORM>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_perc_ant" value="'.$w_perc_conclusao.'">');
    ShowHTML('<INPUT type="hidden" name="w_pacote" value="'.$w_pacote.'">');
    ShowHTML('<INPUT type="hidden" name="w_exequivel" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr><td align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%">');
    ShowHTML('          <tr valign="top">');
    ShowHTML('              <td>Pacote de trabalho:<b><br>'.retornaSimNao($w_pacote).'</td>');
    ShowHTML('              <td>Peso:<b><br>'.$w_peso.'</td>');
    if ($w_pacote=='S') ShowHTML('              <td>Base geogr�fica:<b><br>'.$w_nm_base_geografica.'</td>');
    ShowHTML('          <tr><td colspan="3">Etapa:<b><br>'.MontaOrdemEtapa($w_chave_aux).'. '.$w_titulo.'</td>');
    ShowHTML('          <tr><td colspan="3">Descri��o:<b><br>'.$w_descricao.'</td>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('              <td>Previs�o in�cio:<b><br>'.FormataDataEdicao(Nvl($w_inicio,time())).'</td>');
    ShowHTML('              <td>Previs�o t�rmino:<b><br>'.FormataDataEdicao($w_fim).'</td>');
    ShowHTML('              <td>Or�amento previsto:<b><br>'.number_format($w_orcamento,2,',','.').'</td>');
    ShowHTML('          <tr valign="top">');
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,null);
    ShowHTML('              <td>Respons�vel pela etapa:<b><br>'.f($RS,'nome_resumido').'</td>');
    $RS = db_getUorgData::getInstanceOf($dbms,$w_sq_unidade);
    ShowHTML('              <td colspan=2>Setor respons�vel pela etapa:<b><br>'.f($RS,'nome').' ('.f($RS,'sigla').')</td>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('              <td>Permite vincula��o de tarefas:<b><br>');
    if ($w_vincula_atividade=='S') ShowHTML('                  Sim'); else ShowHTML('                  N�o');
    ShowHTML('              <td>Permite vincula��o de contratos:<b><br>');
    if ($w_vincula_contrato=='S') ShowHTML('                  Sim'); else ShowHTML('                  N�o');    
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa_atualizacao,null,null);
    ShowHTML('      <tr><td colspan=3>Cria��o/�ltima atualiza��o:<b><br>'.FormataDataEdicao($w_ultima_atualizacao,3).'</b>, feita por <b>'.f($RS,'nome_resumido').' ('.f($RS,'sigla').')</b></td>');
    ShowHTML('      </table>');
    ShowHTML('    </TABLE>');
    ShowHTML('</table>');
    ShowHTML('<tr><td align="center" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('    <table width="100%" border="0">');
    if ($O=='V') {
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td>Percentual de conlus�o:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
      ShowHTML('        <td>In�cio real:<br><b>'.nvl(formataDataEdicao($w_inicio_real),'---').'</b></td>');
      ShowHTML('        <td>T�rmino real:<br><b>'.nvl(formataDataEdicao($w_fim_real),'---').'</b></td>'); 
      ShowHTML('      <tr><td colspan=3>Situa��o atual da etapa:<b><br>'.crlf2br(Nvl($w_situacao_atual,'---')).'</td>');
    } else {
      if ($w_pacote=='N') {
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td>Percentual de conlus�o:<br><b>'.nvl($w_perc_conclusao,0).'%</b></td>');
        ShowHTML('        <td>In�cio real:<br><b>'.nvl(formataDataEdicao($w_inicio_real),'---').'</b></td>');
        ShowHTML('        <td>T�rmino real:<br><b>'.nvl(formataDataEdicao($w_fim_real),'---').'</b></td>');
      } else {
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td><b>Percentual de co<u>n</u>clus�o:<br><INPUT ACCESSKEY="N" TYPE="TEXT" CLASS="STI" NAME="w_perc_conclusao" SIZE=3 MAXLENGTH=3 VALUE="'.nvl($w_perc_conclusao,0).'" '.$w_Disabled.' title="Indique o percentual de conclus�o j� atingido por essa etapa."></td>');
        ShowHTML('        <td><b>In�<u>c</u>io real:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" title="Informe a data/hora de in�cio previsto do projeto.">'.ExibeCalendario('Form','w_inicio_real').'</td>');
        ShowHTML('        <td><b><u>T</u>�rmino real:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de t�rmino previsto do projeto.">'.ExibeCalendario('Form','w_fim_real').'</td>');
      }
      ShowHTML('      <tr><td colspan=3><b><u>S</u>itua��o atual da etapa:</b><br><textarea '.$w_Disabled.' accesskey="S" name="w_situacao_atual" class="STI" ROWS=5 cols=75 title="Descreva a situa��o em a etapa encontra-se.">'.$w_situacao_atual.'</TEXTAREA></td>');
    } 
    ShowHTML('      <tr>');
    if ($P1!=1 && $O!='V'){
      ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    }     
    if ($O!='V') {
      ShowHTML('      <tr><td align="center" colspan=4><hr>');
      if ($O=='A') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Voltar">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');

    // Exibe quest�es associadas
    $RS = db_getSolicRestricao::getInstanceOf($dbms,$w_chave, $w_chave_aux, null, null,null,null,'ETAPA');
    if (count($RS) > 0) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><table width="100%" border="1">');
      ShowHTML('  <tr><td bgcolor="#D0D0D0"><b>'.count($RS).' risco(s)/problema(s) associado(s)</b>');
      ShowHTML('  <tr><td align="center"><table width=100%  border="1" bordercolor="#00000">');     
      ShowHTML('    <tr align="center" valign="top" bgColor="#f0f0f0">');
      ShowHTML('      <td><b>Tipo</b></td>');
      ShowHTML('      <td><b>Classifica��o</b></td>');
      ShowHTML('      <td><b>Descri��o</b></td>');
      ShowHTML('      <td><b>Respons�vel</b></td>');                   
      ShowHTML('      <td><b>Estrat�gia</b></td>');
      ShowHTML('      <td><b>A��o de Resposta</b></td>');
      ShowHTML('      <td><b>Fase atual</b></td>');
      ShowHTML('    </tr>');
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        ShowHtml(QuestoesLinhaAtiv($w_chave_aux, f($row,'chave'),f($row,'chave_aux'),f($row,'risco'),f($row,'fase_atual'),f($row,'criticidade'),f($row,'nm_tipo_restricao'),f($row,'descricao'),f($row,'sq_pessoa'),f($row,'nm_resp'),f($row,'nm_estrategia'),f($row,'acao_resposta'),f($row,'nm_fase_atual'),f($row,'qt_ativ'),f($row,'nm_tipo')));
      } 
      ShowHTML('  </table>');
      ShowHTML('</table>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br></td>'); 
    }

    // Exibe tarefas vinculadas
    $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'GDPCAD');
    $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'GDPCAD',4,
           null,null,null,null,null,null,null,null,null,null,
           null,null,null,null,null,null,null,null,null,null,null,null,null,$w_chave_aux,null,null);

    if (count($RS1) > 0) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><table width="100%" border="1">');
      ShowHTML('  <tr><td bgcolor="#D0D0D0"><b>'.count($RS1).' tarefa(s) vinculada(s)</b>');
      ShowHTML('  <tr><td align="center"><table width=100%  border="1" bordercolor="#00000">');     
      ShowHTML('    <tr align="center" bgColor="#f0f0f0">');
      ShowHTML('      <td rowspan=2><b>N�</td>');
      ShowHTML('      <td rowspan=2><b>Detalhamento</td>');
      ShowHTML('      <td rowspan=2><b>Respons�vel</td>');
      ShowHTML('      <td rowspan=2><b>Setor</td>');
      ShowHTML('      <td colspan=2><b>Execu��o</td>');
      ShowHTML('      <td rowspan=2><b>Fase</td>');
      ShowHTML('    </tr>');
      ShowHTML('    <tr align="center" bgColor="#f0f0f0">');
      ShowHTML('      <td><b>De</td>');
      ShowHTML('      <td><b>At�</td>');
      ShowHTML('    </tr>');
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('    <tr bgColor="'.$w_cor.'">');
        ShowHTML('     <td nowrap width="1%">');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        ShowHTML('  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.f($RS,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>');
        ShowHTML('     <td>'.Nvl(f($row,'assunto'),'-'));
        ShowHTML('     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>');
        ShowHTML('     <td>'.f($row,'sg_unidade_resp').'</td>');
        ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>');
        ShowHTML('     <td align="center">'.Nvl(FormataDataEdicao(  f($row,'fim')),'-').'</td>');
        ShowHTML('     <td colspan=2 nowrap>'.f($row,'nm_tramite').'</td>');
      } 
      ShowHTML('      </td></tr></table>');
    } 
    ShowHTML('      </td></tr></table>');
    ShowHTML('      </tr>');
    if ($O=='V') {
      ShowHTML('      <tr><td align="center" colspan=4><hr>');
      ShowHTML('            <input class="STB" type="button" onClick="window.close(); opener.focus();" name="Botao" value="Fechar">');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de recursos do projeto
// -------------------------------------------------------------------------
function Recursos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome         = $_REQUEST['w_nome'];
    $w_tipo         = $_REQUEST['w_tipo'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_finalidade   = $_REQUEST['w_finalidade'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'tipo', 'asc', 'nome', 'asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {
      $w_nome         = f($row,'nome');
      $w_tipo         = f($row,'tipo');
      $w_descricao    = f($row,'descricao');
      $w_finalidade   = f($row,'finalidade');
    }
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_nome','Nome','','1','2','100','1','1');
      Validate('w_tipo','Tipo do recurso','SELECT','1','1','10','','1');
      Validate('w_descricao','Descricao','','','2','2000','1','1');
      Validate('w_finalidade','Finalidade','','','2','2000','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='I' || $O=='A') BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  else BodyOpenClean('onLoad=\'this.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>'); 
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Tipo</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Finalidade</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.RetornaTipoRecurso(f($row,'tipo')).'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_recurso').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_projeto_recurso').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="90" MAXLENGTH="100" VALUE="'.$w_nome.'" title="Informe o nome do recurso."></td>');
    ShowHTML('      <tr>');
    SelecaoTipoRecurso('<u>T</u>ipo:','T','Selecione o tipo deste recurso.',$w_tipo,null,'w_tipo',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descreva, se necess�rio, caracter�sticas deste recurso (conhecimentos, habilidades, perfil, capacidade etc).">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td valign="top"><b><u>F</u>inalidade:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_finalidade" class="STI" ROWS=5 cols=75 title="Descreva, se necess�rio, a finalidade deste recurso para o projeto (fun��es desempenhadas, papel, objetivos etc).">'.$w_finalidade.'</TEXTAREA></td>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de altera��o dos recursos da etapa
// -------------------------------------------------------------------------
function EtapaRecursos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_chave_pai  = $_REQUEST['w_chave_pai'];
  cabecalho();
  ShowHTML('<HEAD>');
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO',null);
  BodyOpenClean('onLoad=this.focus();');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr valign="top">');
  foreach ($RS as $row) {
    ShowHTML('          <td>Etapa:<br><b>'.MontaOrdemEtapa($w_chave_aux).' - '.f($row,'titulo').'</td>');
    ShowHTML('          <td>In�cio:<br> <b>'.FormataDataEdicao(f($row,'inicio_previsto')).'</td>');
    ShowHTML('          <td>T�rmino:<br><b>'.FormataDataEdicao(f($row,'fim_previsto')).'</td>');
    ShowHTML('        <tr><td colspan=3>Descri��o:<br><b>'.CRLF2BR(f($row,'descricao')).'</td></tr>');
  }
  ShowHTML('    </TABLE>');
  ShowHTML('</table>');
  $RS = db_getSolicEtpRec::getInstanceOf($dbms,$w_chave_aux,null,null);
  $RS = SortArray($RS,'tipo','asc','nome','asc');
  ShowHTML('<tr><td align="right">&nbsp;');
  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'ETAPAREC',$R,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
  ShowHTML('<INPUT type="hidden" name="w_sg" value="'.$_REQUEST['w_sg'].'">');
  ShowHTML('<INPUT type="hidden" name="w_recurso" value="">');
  ShowHTML('<tr><td><ul><b>Informa��es:</b><li>Indique abaixo quais recursos estar�o alocados a esta etapa do projeto.<li>A princ�pio, uma etapa n�o tem nenhum recurso alocado.<li>Para remover um recurso, desmarque o quadrado ao seu lado.</ul>');
  ShowHTML('<tr><td align="center" colspan=3>');
  ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
  ShowHTML('          <td><b>&nbsp;</td>');
  ShowHTML('          <td><b>Tipo</td>');
  ShowHTML('          <td><b>Recurso</td>');
  ShowHTML('          <td><b>Finalidade</td>');
  ShowHTML('        </tr>');
  if (count($RS)<=0) {
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
  } else {
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      if (Nvl(f($row,'existe'),0) > 0) ShowHTML('        <td align="center"><input type="checkbox" name="w_recurso[]" value="'.f($row,'sq_projeto_recurso').'" checked></td>');
      else                             ShowHTML('        <td align="center"><input type="checkbox" name="w_recurso[]" value="'.f($row,'sq_projeto_recurso').'"></td>');
      ShowHTML('        <td align="left">'.RetornaTipoRecurso(f($row,'tipo')).'</td>');
      ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
      ShowHTML('        <td align="left">'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>');
      ShowHTML('      </tr>');
    } 
  } 
  ShowHTML('      </center>');
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
  ShowHTML('      <tr><td align="center">&nbsp;');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  ShowHTML('</FORM>');
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
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_tipo_visao   = $_REQUEST['w_tipo_visao'];
    $w_envia_email  = $_REQUEST['w_envia_email'];
  } elseif ($O == 'L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome_resumido','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {
      $w_nome         = f($row,'nome');
      $w_tipo_visao   = f($row,'tipo_visao');
      $w_envia_email  = f($row,'envia_email');
    }
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_chave_aux','Pessoa','HIDDEN','1','1','10','','1');
      Validate('w_tipo_visao','Tipo de vis�o','SELECT','1','1','10','','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif ($O=='I')   BodyOpenClean('onLoad=\'document.Form.w_chave_aux.focus()\';');
  else                BodyOpenClean('onLoad=\'this.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Pessoa</td>');
    ShowHTML('          <td><b>Visao</td>');
    ShowHTML('          <td><b>Envia e-mail</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>');
        ShowHTML('        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>');
        ShowHTML('        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'envia_email'))).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoPessoa('<u>P</u>essoa:','N','Selecione o interessado na rela��o.',$w_chave_aux,$w_chave,'w_chave_aux','INTERES');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Pessoa:</b><br>'.$w_nome.'</td>');
    } 
    SelecaoTipoVisao('<u>T</u>ipo de vis�o:','T','Selecione o tipo de vis�o que o interessado ter� deste projeto.',$w_tipo_visao,null,'w_tipo_visao',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Envia e-mail ao interessado quando houver encaminhamento?</b>',$w_envia_email,'w_envia_email');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de �reas envolvidas
// -------------------------------------------------------------------------
function Areas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_interesse    = $_REQUEST['w_interesse'];
    $w_influencia   = $_REQUEST['w_influencia'];
    $w_papel        = $_REQUEST['w_papel'];        
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,$w_chave_aux,'REGISTRO');
    foreach ($RS as $row) {
      $w_nome       = f($row,'nome');
      $w_interesse  = f($row,'interesse_positivo');
      $w_influencia = f($row,'influencia');            
      $w_papel      = f($row,'papel');
    }
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    FormataCEP();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_chave_aux','Parte interessada','HIDDEN','1','1','10','','1');
      Validate('w_interesse','Interesse','SELECT',1,1,18,'','');
      Validate('w_influencia','Influ�ncia','SELECT',1,1,18,'','0123456789');
      Validate('w_papel','Papel desempenhado','','1','1','2000','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpenClean('onLoad=\'this.focus()\';'); 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Parte interessada</td>');
    ShowHTML('          <td><b>Interesse</td>');
    ShowHTML('          <td><b>Influ�ncia</td>');    
    ShowHTML('          <td><b>Papel</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_interesse').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_influencia').'</td>');                
        ShowHTML('        <td>'.crlf2br(f($row,'papel')).'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<u>P</u>arte interessada:','P',null,$w_chave_aux,null,'w_chave_aux','EXTERNO',null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
      ShowHTML('      <tr><td valign="top"><b>Parte interessada:</b><br>'.$w_nome.'</td>');
    } 
    ShowHTML('      <tr valign="top">');
    SelecaoInteresse('<U>I</U>nteresse:','I','Selecione de interesse.',$w_interesse,'w_interesse',null,null);
    ShowHTML('      <tr valign="top">');
    SelecaoInfluencia('In<U>f</U>lu�ncia:','F','Selecione de influ�ncia.',$w_influencia,'w_influencia',null,null);
    ShowHTML('      <tr><td valign="top"><b><u>P</u>apel desempenhado:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_papel" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela �rea ou institui��o na execu��o do projeto.">'.$w_papel.'</TEXTAREA></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      else         ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
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
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = strtoupper(trim($_REQUEST['w_tipo']));
  // Recupera o logo do cliente a ser usado nas listagens
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS,'logo')>'') {
    $w_logo='img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30);
  } 
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de '.f($RS_Menu,'nome').'</TITLE>');
  ShowHTML('</HEAD>');
  BodyOpenClean(null);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$w_cliente,$w_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">Visualiza��o de '.f($RS_Menu,'nome').'</font>');
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">'.DataHora().'</B></TD></TR>');
  ShowHTML('</B></TD></TR></TABLE>');
  if ($w_tipo > '') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualProjeto($w_chave,$O,$w_usuario));
  if ($w_tipo > '') ShowHTML('<center><font size="1"><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</b></center>');
  Rodape();
}  

// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  // Se for recarga da p�gina  
  if ($w_troca > '' && $O!='E') $w_observacao = $_REQUEST['w_observacao'];
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PJGERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_tramite      = $_REQUEST['w_tramite'];
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho     = $_REQUEST['w_despacho'];
  } else {
    $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
    $w_tramite      = f($RS,'sq_siw_tramite');
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  } 
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
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
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinat�rio','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1 != 1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpenClean('onLoad=\'document.Form.w_destinatario.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  if($SG=='PJCADBOLSA') AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PJBENVIO',$w_pagina.$par,$O);
  else                  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PJENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se n�o for cadastramento
    SelecaoFase('<u>F</u>ase do projeto:','F','Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja envi�-lo.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
    if ($w_sg_tramite=='CI') SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o projeto na rela��o.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    else                     SelecaoPessoa('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o projeto na rela��o.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } else {
    SelecaoFase('<u>F</u>ase do projeto:','F','Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja envi�-lo.',$w_novo_tramite,$w_menu,'w_novo_tramite',null,null);
    SelecaoPessoa('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o projeto na rela��o.',$w_destinatario,null,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td valign="top" colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Descreva o papel desempenhado pela �rea ou institui��o na execu��o do projeto.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
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
// Rotina de anota��o
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  // Se for recarga da p�gina
  if ($w_troca > '' && $O!='E') $w_observacao = $_REQUEST['w_observacao'];
  cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  if($SG=='PJCADBOLSA')  ShowHTML('<FORM  name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_pagina.'Grava&SG=PJBENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  else                   ShowHTML('<FORM  name="Form" method="POST" enctype="multipart/form-data" onSubmit="return(Validacao(this));" action="'.$w_pagina.'Grava&SG=PJENVIO&O='.$O.'&w_menu='.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">'); 
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td valign="top"><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="STI" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="STI" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca > '' && $O!='E') {
    // Se for recarga da p�gina
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_concluida        = $_REQUEST['w_concluida'];
    $w_data_conclusao   = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 
  cabecalho();
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
      case 1: Validate('w_fim_real','T�rmino previsto','DATA',1,10,10,'','0123456789/'); break;
      case 2: Validate('w_fim_real','T�rmino previsto','DATAHORA',1,17,17,'','0123456789/'); break;
      case 3: 
        Validate('w_inicio_real','In�cio previsto','DATA',1,10,10,'','0123456789/');
        Validate('w_fim_real','T�rmino previsto','DATA',1,10,10,'','0123456789/');
        CompData('w_inicio_real','In�cio previsto','<=','w_fim_real','T�rmino previsto');
        CompData('w_fim_real','T�rmino previsto','<=',FormataDataEdicao(time()),'data atual'); break;
      case 4: 
        Validate('w_inicio_real','In�cio previsto','DATAHORA',1,17,17,'','0123456789/,: ');
        Validate('w_fim_real','T�rmino previsto','DATAHORA',1,17,17,'','0123456789/,: ');
        CompData('w_inicio_real','In�cio previsto','<=','w_fim_real','T�rmino previsto'); break;
    } 
    Validate('w_custo_real','Custo real','VALOR','1',4,18,'','0123456789.,');
    Validate('w_nota_conclusao','Nota de conclus�o','','1','1','2000','1','1');
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca > '') BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else               BodyOpenClean('onLoad=\'document.Form.w_inicio_real.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualProjeto($w_chave,'V',$w_usuario));
  ShowHTML('<HR>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr>');
  // Verifica se o projeto tem etapas em aberto e avisa o usu�rio caso isso ocorra.
  $RS = db_getSolicEtapa::getInstanceOf($dbms,$w_chave,null,'LISTA',null);
  $w_cont = 0;
  foreach ($RS as $row) {
    if (f($row,'perc_conclusao') != 100) $w_cont += 1;
  } 
  if ($w_cont > 0) {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATEN��O: das '.count($RS).' etapas deste projeto, '.$w_cont.' n�o t�m 100% de conclus�o!\n\nAinda assim voc� poder� concluir este projeto.\');');
    ScriptClose();
  } 
  if($SG=='PJCADBOLSA') AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PJBCONC',$w_pagina.$par,$O);
  else                  AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'PJCONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'PJGERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  switch (f($RS_Menu,'data_hora')) {
    case 1: ShowHTML('              <td valign="top"><b><u>T</u>�rmino real:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de t�rmino previsto do projeto.">'.ExibeCalendario('Form','w_fim_real').'</td>'); break;
    case 2: ShowHTML('              <td valign="top"><b><u>T</u>�rmino real:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data/hora de t�rmino previsto do projeto."></td>'); break;
    case 3: ShowHTML('              <td valign="top"><b>In�<u>c</u>io real:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio_real.'" onKeyDown="FormataData(this,event);" title="Informe a data/hora de in�cio previsto do projeto.">'.ExibeCalendario('Form','w_inicio_real').'</td>');
            ShowHTML('              <td valign="top"><b><u>T</u>�rmino real:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim_real.'" onKeyDown="FormataData(this,event);" title="Informe a data de t�rmino previsto do projeto.">'.ExibeCalendario('Form','w_fim_real').'</td>'); break;
    case 4: ShowHTML('              <td valign="top"><b>In�<u>c</u>io real:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_inicio_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data/hora de in�cio previsto do projeto."></td>');
            ShowHTML('              <td valign="top"><b><u>T</u>�rmino real:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="STI" SIZE="17" MAXLENGTH="17" VALUE="'.$w_fim_real.'" onKeyDown="FormataDataHora(this,event);" title="Informe a data de t�rmino previsto do projeto."></td>'); break;
  } 
  ShowHTML('              <td valign="top"><b>Custo <u>r</u>eal:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_custo_real" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_custo_real.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o or�amento dispon�vel para execu��o do projeto, ou zero se n�o for o caso."></td>');
  ShowHTML('          </table>');
  ShowHTML('    <tr><td valign="top"><b>Nota d<u>e</u> conclus�o:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="STI" ROWS=5 cols=75 title="Descreva o quanto o projeto atendeu aos resultados esperados.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
// Gera uma linha de apresenta��o da tabela de etapas
// -------------------------------------------------------------------------
function EtapaLinha($l_chave,$l_chave_aux,$l_titulo,$l_resp,$l_setor,$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,$l_perc,$l_ativ,$l_destaque,$l_oper,$l_tipo,$l_sq_resp,$l_sq_setor,$l_vincula_contrato,$l_contr, $l_valor=null,$l_nivel=0,$l_restricao='N',$l_peso='1') {
  extract($GLOBALS);
  global $w_cor;
  $l_recurso = '';
  $l_img = '';
  if (nvl($l_destaque,'')!='' || substr(nvl($l_restricao,'-'),0,1)=='S') {
    $l_img = exibeImagemRestricao($l_restricao);
  }
  $RS_Query = db_getSolicEtpRec::getInstanceOf($dbms,$l_chave_aux,null,'EXISTE');
  if (count($RS_Query) > 0) {
    $l_recurso = $l_recurso.chr(13).'      <tr valign="top"><td colspan=8>Recurso(s): ';
    foreach($RS_Query as $row) {
      $l_recurso = $l_recurso.chr(13).f($row,'nome').'; ';
    } 
    $l_recurso = $l_recurso.chr(13).'      </tr></td>';
  } 
  if ($l_recurso > '') $l_row = 'rowspan=2'; else $l_row = '';
  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
  $l_html .= chr(13).'        <td width="1%" nowrap '.$l_row.'>'; 
  $l_html .= chr(13).ExibeImagemSolic('ETAPA',$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,null,null,null,$l_perc);
  $l_html .= chr(13).' '.ExibeEtapa('V',$l_chave,$l_chave_aux,'Volta',10,MontaOrdemEtapa($l_chave_aux),$TP,$SG).$l_img.'</td>';
  if (nvl($l_nivel,0)==0) {
    $l_html .= chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  } else {
    $l_html .= chr(13).'        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',($l_nivel)).'<td>'.$l_destaque.$l_titulo.' '.'</b></tr></table>';
  }
  $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</b>';
  $l_html .= chr(13).'        <td>'.ExibeUnidade(null,$w_cliente,$l_setor,$l_sq_setor,$TP).'</b>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.formataDataEdicao($l_inicio,5).'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.formataDataEdicao($l_fim,5).'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.nvl(formataDataEdicao($l_inicio_real,5),'---').'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.nvl(formataDataEdicao($l_fim_real,5),'---').'</td>';
  if (nvl($l_valor,'')!='') $l_html .= chr(13).'        <td nowrap align="right" width="1%" nowrap>'.formatNumber($l_valor).'</td>';
  $l_html .= chr(13).'        <td align="right" width="1%" nowrap>'.$l_perc.' %</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.$l_peso.'</td>';
  if ($l_ativ > 0) $l_html = $l_html.chr(13).'        <td width="1%" nowrap align="center" title="N�mero de tarefas ligadas a esta estapa. Clique sobre o n�mero para exibir APENAS as tarefas que voc� tem acesso."><a class="HL" href="javascript:lista(\''.$l_chave.'\',\''.$l_chave_aux.'\');" onMouseOver="window.status=\'Exibe APENAS as tarefas que voc� tem acesso.\'; return true;" onMouseOut="window.status=\'\'; return true;">'.$l_ativ.'</a></td>';
  else             $l_html = $l_html.chr(13).'        <td width="1%" nowrap align="center">'.$l_ativ.'</td>';
  if($l_vincula_contrato=='S') {
    if ($l_contr > 0) $l_html = $l_html.chr(13).'        <td width="1%" nowrap align="center" title="N�mero de contratos ligados a esta etapa. Clique sobre o n�mero para exibir APENAS os contratos que voc� tem acesso."><a class="HL" href="javascript:listac(\''.$l_chave.'\',\''.$l_chave_aux.'\');" onMouseOver="window.status=\'Exibe APENAS os contratos que voc� tem acesso.\'; return true;" onMouseOut="window.status=\'\'; return true;">'.$l_contr.'</a></td>';
    else              $l_html = $l_html.chr(13).'        <td width="1%" nowrap align="center">'.$l_contr.'</td>';    
  }
  if ($l_oper == 'S') {
    $l_html .= chr(13).'        <td align="top" nowrap '.$l_row.'>';
    // Se for listagem de etapas no cadastramento do projeto, exibe opera��es de altera��o, exclus�o e recursos
    if ($l_tipo == 'PROJETO') {
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');" title="Excluir">Excl</A>&nbsp';
      // A linha abaixo foi comentada por Alexandre, at� que se ache uma solu��o adequada para vincular
      // os recursos �s etapas.
      //if($SG!='PJBETAPA')   $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.'EtapaRecurso&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_menu='.$w_menu.'&w_sg='.$SG.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Recursos&SG='.$SG.'" title="Recursos da etapa">Rec</A>&nbsp';
      // Caso contr�rio, � listagem de atualiza��o de etapas. Neste caso, coloca apenas a op��o de altera��o
    } else {
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Atualizar</A>&nbsp';
    } 
    $l_html .= chr(13).'        </td>';
  } else {
    if ($l_tipo == 'ETAPA') {
      $l_html .= chr(13).'        <td align="top" nowrap '.$l_row.'>';
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Exibir</A>&nbsp';
      $l_html .= chr(13).'        </td>';
    } 
  } 
  $l_html .= chr(13).'      </tr>';
  if ($l_recurso > '') $l_html .= chr(13).str_replace('w_cor',$w_cor,$l_recurso);
  return $l_html;
} 
// =========================================================================
// Gera uma linha de apresenta��o da tabela de etapas
// -------------------------------------------------------------------------
function EtapaLinhaAtiv($l_chave,$l_chave_aux,$l_titulo,$l_resp,$l_setor,$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,$l_perc,$l_ativ1,$l_destaque,$l_oper,$l_tipo,$l_assunto,$l_sq_resp, $l_sq_setor,$l_vincula_contrato,$l_contr,$l_valor=null,$l_nivel=0,$l_restricao='N',$l_peso='1') {
  extract($GLOBALS);
  global $w_cor;
  $l_recurso = '';
  $l_ativ    = '';
  $l_row     = 1;
  $l_col     = 1;
  $l_img = '';
  if (nvl($l_destaque,'')!='' || substr(nvl($l_restricao,'-'),0,1)=='S') {
    $l_img = exibeImagemRestricao($l_restricao);
  }
  $RS_Query = db_getSolicEtpRec::getInstanceOf($dbms,$l_chave_aux,null,'EXISTE');
  if (count($RS_Query)>0) {
    $l_recurso = $l_recurso.chr(13).'      <tr valign="top"><td colspan=8>Recurso(s): ';
    foreach($RS_Query as $row) {
      $l_recurso = $l_recurso.chr(13).f($row,'nome').'; ';
    } 
  } 
  // Recupera os contratos que o usu�rio pode ver
  $l_rs = db_getLinkData::getInstanceOf($dbms, $w_cliente, 'GCBCAD');
  $RS_Contr = db_getSolicList::getInstanceOf($dbms,f($l_rs,'sq_menu'),$w_usuario,f($l_rs,'sigla'),3,
              null,null,null,null,null,null,
              null,null,null,null,
              null,null,null,null,null,null,null,
              null,null,null,null,null,$l_chave,$l_chave_aux,null,null);
  $l_row += count($RS_Contr);

  // Recupera as tarefas que o usu�rio pode ver
  $l_rs = db_getLinkData::getInstanceOf($dbms, $w_cliente, 'GDPCAD');
  $RS_Ativ = db_getSolicList::getInstanceOf($dbms,f($l_rs,'sq_menu'),$w_usuario,f($l_rs,'sigla'),3,
              null,null,null,null,null,null,
              null,null,null,null,
              null,null,null,null,null,null,null,
              null,null,null,null,null,$l_chave,$l_chave_aux,null,null);

  if ($l_recurso > '') $l_row += 1;
  if ($l_ativ1 > '') $l_row += count($RS_Ativ);

  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
  $l_html .= chr(13).'        <td width="1%" nowrap rowspan='.$l_row.'>';
  $l_html .= chr(13).ExibeImagemSolic('ETAPA',$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,null,null,null,$l_perc);
  $l_html .= chr(13).' '.ExibeEtapa('V',$l_chave,$l_chave_aux,'Volta',10,MontaOrdemEtapa($l_chave_aux),$TP,$SG).$l_img.'</td>';
  if (nvl($l_nivel,0)==0) {
    $l_html .= chr(13).'        <td>'.$l_destaque.$l_titulo.'</b>';
  } else {
    $l_html .= chr(13).'        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',($l_nivel)).'<td>'.$l_destaque.$l_titulo.'</b></tr></table>';
  }
  $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</b>';
  $l_html .= chr(13).'        <td>'.ExibeUnidade(null,$w_cliente,$l_setor,$l_sq_setor,$TP).'</b>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.formataDataEdicao($l_inicio,5).'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.formataDataEdicao($l_fim,5).'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.nvl(formataDataEdicao($l_inicio_real,5),'---').'</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.nvl(formataDataEdicao($l_fim_real,5),'---').'</td>';
  if (nvl($l_valor,'')!='') $l_html .= chr(13).'        <td width="1%" nowrap align="right">'.formatNumber($l_valor).'</td>';
  $l_html .= chr(13).'        <td width="1%" nowrap align="right" >'.$l_perc.' %</td>';
  $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.$l_peso.'</td>';
  $l_html .= chr(13).'        <td width="1%" nowrap align="center" >'.$l_ativ1.'</td>';
  if ($l_vincula_contrato=='S') $l_html .= chr(13).'        <td align="center" width="1%" nowrap>'.$l_contr.'</td>';
  if ($l_oper == 'S') {
    $l_html .= chr(13).'        <td align="top" nowrap rowspan='.$l_row.'>';
    // Se for listagem de etapas no cadastramento do projeto, exibe opera��es de altera��o, exclus�o e recursos
    if ($l_tipo == 'PROJETO') {
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">Alt</A>&nbsp';
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');" title="Excluir">Excl</A>&nbsp';
      if($SG!='PJBETAPA') $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.'EtapaRecurso&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&w_menu='.$w_menu.'&w_sg='.$SG.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Recursos&SG='.$SG.'" title="Recursos da etapa">Rec</A>&nbsp';
      // Caso contr�rio, � listagem de atualiza��o de etapas. Neste caso, coloca apenas a op��o de altera��o
    } else {
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Atualizar</A>&nbsp';
    } 
    $l_html .= chr(13).'        </td>';
  } else {
    if ($l_tipo == 'ETAPA') {
      $l_html .= chr(13).'        <td align="top" nowrap rowspan='.$l_row.'>';
      $l_html .= chr(13).'          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$l_chave.'&w_chave_aux='.$l_chave_aux.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Atualiza dados da etapa">Exibir</A>&nbsp';
      $l_html .= chr(13).'        </td>';
    } 
  }
  //Listagem dos contratos da etapa 
  if (count($RS_Contr)>0) {
    foreach ($RS_Contr as $row) {
      $l_contr1 .= chr(13).'<tr valign="top">';
      $l_contr1 .= chr(13).'  <td>';
      ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
      $l_contr1 = $l_contr1.chr(13).'  <A class="HL" HREF="'.$conRootSIW.'mod_ac/contratos.php?par=Visual&R=contratos.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.f($row,'p1').'&P2='.f($row,'p2').'&P3='.f($row,'p3').'&P4='.f($row,'p4').'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
      $l_contr1 = $l_contr1.chr(13).' - '.Nvl(f($row,'titulo'),'-');
      $l_contr1 .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>';
      $l_contr1 .= chr(13).'     <td>'.ExibeUnidade(null,$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade_resp'),$TP).'</td>';
      $l_contr1 .= chr(13).'     <td align="center">'.Nvl(date(d.'/'.m.'/'.y,f($row,'inicio')),'-').'</td>';
      $l_contr1 .= chr(13).'     <td align="center">'.Nvl(date(d.'/'.m.'/'.y,f($row,'fim')),'-').'</td>';
      if (nvl($l_valor,'')!='') $l_contr1 .= chr(13).'        <td width="1%" nowrap align="right">'.formatNumber($l_valor).'</td>';
      if (nvl($l_valor,'')!='') {
         $l_contr1 .= chr(13).'     <td colspan=6 nowrap>'.f($row,'nm_tramite').'</td>';
      } else {
         $l_contr1 .= chr(13).'     <td colspan=5 nowrap>'.f($row,'nm_tramite').'</td>';
      }
    }
    $l_contr1    = $l_contr1.chr(13).'            </td></tr>';
  }   
  //Listagem das tarefas da etapa  
  if (count($RS_Ativ)>0) {
    foreach ($RS_Ativ as $row) {
      $l_ativ .= chr(13).'<tr valign="top">';
      $l_ativ .= chr(13).'  <td>';
      $l_ativ .= chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      $l_ativ .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=projetoativ.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
      if (strlen(Nvl(f($row,'assunto'),'-'))>50 && strtoupper($l_assunto)!='COMPLETO') $l_ativ .= ' - '.substr(Nvl(f($row,'assunto'),'-'),0,50).'...';
      else                                                                             $l_ativ .= ' - '.Nvl(f($row,'assunto'),'-');
      $l_ativ .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>';
      $l_ativ .= chr(13).'     <td>'.ExibeUnidade(null,$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade_resp'),$TP).'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(date(d.'/'.m.'/'.y,f($row,'inicio')),'-').'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.Nvl(date(d.'/'.m.'/'.y,f($row,'fim')),'-').'</td>';
      if (nvl($l_valor,'')!='') {
        $l_ativ .= chr(13).'     <td colspan=7 nowrap>'.f($row,'nm_tramite').'</td>';
      } else {
        $l_ativ .= chr(13).'     <td colspan=6 nowrap>'.f($row,'nm_tramite').'</td>';
      }
    }
  } 
  if ($l_ativ1 > '') {
    $l_recurso = $l_recurso.chr(13).'      </tr></td>';
    $l_ativ    = $l_ativ.chr(13).'            </td></tr>';
  } elseif ($l_recurso > '') {
    $l_recurso = $l_recurso.chr(13).'      </tr></td></table></td></tr>';
  } 
  $l_html = $l_html.chr(13).'      </tr>';
  if ($l_recurso > '') $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_recurso);
  if ($l_ativ>'')      $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_ativ);
  if ($l_contr1>'')    $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_contr1);
  return $l_html;
} 

// =========================================================================
// Gera uma linha de apresenta��o da tabela de quest�es
// -------------------------------------------------------------------------
function QuestoesLinhaAtiv($l_siw_solicitacao, $l_chave, $l_chave_aux, $l_risco, $l_fase_atual,$l_criticidade, 
    $l_tipo_restricao,$l_descricao,$l_sq_resp, $l_resp,$l_estrategia,$l_acao_resposta,$l_fase_atual, $l_qtd, $l_tipo ){
  extract($GLOBALS);
  global $w_cor;
  $l_recurso = '';
  $l_ativ    = '';
  $l_row     = 1;
  $l_col     = 1;

  // Recupera as tarefas que o usu�rio pode ver
  $RS_Ativ = db_getSolicRestricao::getInstanceOf($dbms,$l_chave_aux, null, null, null, null, null, 'TAREFA');
  foreach($RS as $row){$RS = $row; Break;}

  if ($l_qtd > '') $l_row += count($RS_Ativ);

  $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
  $l_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
  $l_html .= chr(13).'        <td width="10%" nowrap rowspan='.$l_row.'>';
  if ($l_risco=='S') {
    if ($l_fase_atual<>'C') {
      if ($l_criticidade==1)       $l_html .= chr(13).'          <img title="Risco de baixa criticidade" src="'.$conRootSIW.$conImgRiskLow.'" border=0 align="middle">&nbsp';
        elseif ($l_criticidade==2) $l_html .= chr(13).'          <img title="Risco de m�dia criticidade" src="'.$conRootSIW.$conImgRiskMed.'" border=0 align="middle">&nbsp';
        else                       $l_html .= chr(13).'          <img title="Risco de alta criticidade" src="'.$conRootSIW.$conImgRiskHig.'" border=0 align="middle">&nbsp';
      }
    } else {
      if ($l_fase_atual<>'C') {
      if ($l_criticidade==1)     $l_html .= chr(13).'          <img title="Problema de baixa criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
      elseif ($l_criticidade==2) $l_html .= chr(13).'          <img title="Problema de m�dia criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
      else                       $l_html .= chr(13).'          <img title="Problema de alta criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp';
    }
  }
  $l_html .= chr(13).'    '.$l_tipo_restricao.'</td>';
  $l_html .= chr(13).'     <td align="center">'.$l_tipo.'</td>';
  $l_html .= chr(13).'     <td>'.CRLF2BR($l_descricao).'</td>';
  $l_html .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,$l_sq_resp,$TP,$l_resp).'</td>';
  $l_html .= chr(13).'     <td align="center">'.$l_estrategia.'</td>';  
  $l_html .= chr(13).'     <td>'.$l_acao_resposta.'</td>';
  $l_html .= chr(13).'     <td>'.$l_fase_atual.'</td>';
  $l_html .= chr(13).'   </tr>';

  //Listagem das tarefas da quest�o
  if (count($RS_Ativ)>0) {
    foreach ($RS_Ativ as $row) {
      $l_ativ .= chr(13).'      <tr bgcolor="'.$w_cor.'"><td>';
      $l_ativ .= chr(13).ExibeImagemSolic(f($row,'sg_servico'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      $l_ativ .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.f($row,'sq_menu').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
      $l_ativ .= chr(13).'     <td>'.Nvl(f($row,'assunto'),'-');
      $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'inicio_real'),f($row,'inicio'))).'</td>';
      $l_ativ .= chr(13).'     <td align="center">'.formataDataEdicao(nvl(f($row,'fim_real'),f($row,'fim'))).'</td>';
      $l_ativ .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp_tarefa')).'</td>';
      $l_ativ .= chr(13).'     <td>'.f($row,'nm_tramite').'</td>';
    } 
    $l_ativ .= chr(13).'      </td></tr>';
  } 
  if ($l_qt_ativ > '') {
    $l_ativ    = $l_ativ.chr(13).'            </td></tr>';
  } 
  $l_html = $l_html.chr(13).'      </tr>';
  if ($l_ativ>'')      $l_html = $l_html.chr(13).str_replace('w_cor',$w_cor,$l_ativ);
  return $l_html;
} 

// =========================================================================
// Rotina de prepara��o para envio de e-mail relativo a projetos
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  $l_solic          = $p_solic;
  $w_destinatarios  = '';
  $w_resultado      = ''; 
  $w_html='<HTML>'.$crlf;
  $w_html .= BodyOpenMail(null).$crlf;
  $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
  $w_html .= '<tr><td align="center">'.$crlf;
  $w_html .= '    <table width="97%" border="0">'.$crlf;
  if ($p_tipo==1)       $w_html .= '      <tr valign="top"><td align="center"><b>INCLUS�O DE '.strtoupper(f($RS_Menu,'nome')).'</b><br><br><td></tr>'.$crlf;
  elseif ($p_tipo==2)   $w_html .= '      <tr valign="top"><td align="center"><b>TRAMITA��O DE '.strtoupper(f($RS_Menu,'nome')).'</b><br><br><td></tr>'.$crlf;
  elseif ($p_tipo==3)   $w_html .= '      <tr valign="top"><td align="center"><b>CONCLUS�O DE '.strtoupper(f($RS_Menu,'nome')).'</b><br><br><td></tr>'.$crlf;
  $w_html .= '      <tr valign="top"><td><b><font color="#BC3131">ATEN��O: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</font></b><br><br><td></tr>'.$crlf;
  // Recupera os dados do projeto
  $RSM = db_getSolicData::getInstanceOf($dbms,$p_solic,'PJGERAL');
  $w_nome ='Projeto '.f($RSM,'titulo').' ('.f($RSM,'sq_siw_solicitacao').')';
  $w_html .= $crlf.'<tr><td align="center">';
  $w_html .= $crlf.'    <table width="99%" border="0">';
  $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.f($RSM,'titulo').' ('.f($RSM,'sq_siw_solicitacao').')</b></font></div></td></tr>';
  $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identifica��o do projeto
  $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>EXTRATO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
  // Se a classifica��o foi informada, exibe.
  if (nvl(f($RSM,'sq_cc'),'')>'') { 
    $w_html .= $crlf.'    <tr><td valign="top"><b>Classifica��o:</b></td>';
    $w_html .= $crlf.'      <td>'.f($RSM,'cc_nome').' </b></td>';
  } 
  $w_html .= $crlf.'      <tr><td valign="top" colspan="2">';
  $w_html .= $crlf.'      <tr><td><b>Respons�vel:</b></td>';
  $w_html .= $crlf.'        <td>'.f($RSM,'nm_sol').'</td></tr>';
  $w_html .= $crlf.'      <tr><td><b>Unidade respons�vel:</b></td>';
  $w_html .= $crlf.'        <td>'.f($RSM,'nm_unidade_resp').'</td></tr>';
  $w_html .= $crlf.'      <tr><td><b>In�cio previsto:</b></td>';
  $w_html .= $crlf.'        <td>'.FormataDataEdicao(f($RSM,'inicio')).' </td></tr>';
  $w_html .= $crlf.'      <tr><td><b>T�rmino previsto:</b></td>';
  $w_html .= $crlf.'        <td>'.FormataDataEdicao(f($RSM,'fim')).' </td></tr>';
  $w_html .= $crlf.'      <tr><td><b>Prioridade:</b></td>';
  $w_html .= $crlf.'        <td>'.RetornaPrioridade(f($RSM,'prioridade')).' </td></tr>';
  // Informa��es adicionais
  if (Nvl(f($RSM,'descricao'),'') > '') {
    $w_html .= $crlf.'      <tr><td valign="top"><b>Resultados do projeto:</b></td>';
    $w_html .=$crlf.'        <td>'.CRLF2BR(f($RSM,'descricao')).' </b></td>';
  }
  $w_html .= $crlf.'</tr>';
  // Dados da conclus�o do projeto, se ela estiver nessa situa��o
  if (f($RSM,'concluida') == 'S' && Nvl(f($RSM,'data_conclusao'),'') > '') {
    $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUS�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2">';
    $w_html .= $crlf.'      <tr><td>In�cio previsto:</b></td>';
    $w_html .=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'inicio_real')).' </td></tr>';
    $w_html .= $crlf.'      <tr><td>T�rmino previsto:</b></td>';
    $w_html .=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'fim_real')).' </td></tr>';
    $w_html .= $crlf.'      <tr><td valign="top">Nota de conclus�o:</b></td>';
    $w_html .=$crlf.'        <td>'.CRLF2BR(f($RSM,'nota_conclusao')).' </td></tr>';
  } 

  //Recupera o �ltimo log
  $RS = db_getSolicLog::getInstanceOf($dbms,$p_solic,null,'LISTA');
  $RS = SortArray($RS,'phpdt_data','desc');
  foreach ($RS as $row) { $RS = $row; break; }
  $w_data_encaminhamento = f($RS,'phpdt_data');
  if ($p_tipo == 2) { // Se for tramita��o
    // Encaminhamento
    $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>�LTIMO ENCAMINHAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html .= $crlf.'      <tr><td valign="top" colspan="2">';
    $w_html .= $crlf.'      <tr><td><b>De:</b></td>';
    $w_html .= $crlf.'        <td>'.f($RS,'responsavel').'</td></tr>';
    $w_html .= $crlf.'      <tr><td><b>Para:</b></td>';
    $w_html .= $crlf.'        <td>'.f($RS,'destinatario').'</td></tr>';
    $w_html .= $crlf.'      <tr><td><b>Despacho:</b></td>';
    $w_html .= $crlf.'        <td>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </td></tr>';

    // Configura o destinat�rio da tramita��o como destinat�rio da mensagem
    $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
    $w_destinatarios = f($RS,'email').'; ';
  } 
  $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFORMA��ES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
  $RS = db_getCustomerSite::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $w_html .= '      <tr valign="top"><td colspan="2">'.$crlf;
  $w_html .= '         Para acessar o sistema use o endere�o: <b><a class="SS" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
  $w_html .= '      </td></tr>'.$crlf;
  $w_html .= '      <tr valign="top"><td colspan="2">'.$crlf;
  $w_html .= '         Dados da ocorr�ncia:<br>'.$crlf;
  $w_html .= '         <ul>'.$crlf;
  $w_html .= '         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
  $w_html .= '         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
  $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
  $w_html .= '         </ul>'.$crlf;
  $w_html .= '      </td></tr>'.$crlf;
  $w_html .= '    </table>'.$crlf;
  $w_html .= '</td></tr>'.$crlf;
  $w_html .= '</table>'.$crlf;
  $w_html .= '</BODY>'.$crlf;
  $w_html .= '</HTML>'.$crlf;
  // Recupera o e-mail do respons�vel
  $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
  if (strpos($w_destinatarios,f($RS,'email').'; ')===false) $w_destinatarios=$w_destinatarios.f($RS,'email').'; ';
  // Recupera o e-mail do titular e do substituto pelo setor respons�vel
  $RS = db_getUorgResp::getInstanceOf($dbms,f($RSM,'sq_unidade'));
  foreach($RS as $row){$RS=$row; break;}
  if ((strpos($w_destinatarios,f($RS,'email_titular').'; ')===false)    && Nvl(f($RS,'email_titular'),'nulo')!='nulo')    $w_destinatarios=$w_destinatarios.f($RS,'email_titular').'; ';
  if ((strpos($w_destinatarios,f($RS,'email_substituto').'; ')===false) && Nvl(f($RS,'email_substituto'),'nulo')!='nulo') $w_destinatarios=$w_destinatarios.f($RS,'email_substituto').'; ';
  // Recuperar o e-mail dos interessados
  $RS = db_getSolicInter::getInstanceOf($dbms,$p_solic,null,'LISTA');
  foreach($RS as $row) {
    if ((strpos($w_destinatarios,f($row,'email').'; ')===false) && Nvl(f($row,'email'),'nulo')!='nulo' && f($row,'envia_email') =='S')    $w_destinatarios=$w_destinatarios.f($row,'email').'; ';
  }
  // Recuperar o e-mail do titular e substituto das �reas envolvidas
  $RS = db_getSolicAreas::getInstanceOf($dbms,$p_solic,null,'LISTA');
  foreach($RS as $row) {
    $RS1 = db_getUorgResp::getInstanceOf($dbms,f($row,'sq_unidade'));
    foreach($RS1 as $row1){$RS1=$row1; break;}
    if ((strpos($w_destinatarios,f($RS1,'email_titular').'; ')===false)    && Nvl(f($RS1,'email_titular'),'nulo')!='nulo')    $w_destinatarios=$w_destinatarios.f($RS1,'email_titular').'; ';
    if ((strpos($w_destinatarios,f($RS1,'email_substituto').'; ')===false) && Nvl(f($RS1,'email_substituto'),'nulo')!='nulo') $w_destinatarios=$w_destinatarios.f($RS1,'email_substituto').'; ';    
  }
  // Prepara os dados necess�rios ao envio
  $RS = db_getCustomerData::getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  if ($p_tipo == 1 || $p_tipo == 3) {
    // Inclus�o ou Conclus�o
    if ($p_tipo == 1) $w_assunto='Inclus�o - '.$w_nome; else $w_assunto='Conclus�o - '.$w_nome;
  } elseif ($p_tipo == 2) {
    // Tramita��o
    $w_assunto='Tramita��o - '.$w_nome;
  } 
  // Executa o envio do e-mail
  if ($w_destinatarios > '') $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
  // Se ocorreu algum erro, avisa da impossibilidade de envio
  if ($w_resultado > '') {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
    ScriptClose();
  } 
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       ='';
  $w_tamanho    ='';
  $w_tipo       ='';
  $w_nome       ='';
  cabecalho();
  ShowHTML('</HEAD>');
  BodyOpenClean('onLoad=this.focus();');
  if($SG=='PJGERAL' || $SG=='PJBGERAL') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Se for opera��o de exclus�o, verifica se � necess�rio excluir os arquivos f�sicos
      if ($O=='E') {
        $RS = db_getSolicLog::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e n�o exclu�da.
        // Nessa situa��o, n�o � necess�rio excluir os arquivos.
        if (count($RS)<=1) {
          $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      dml_putProjetoGeral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_menu'],$_SESSION['LOTACAO'],
          $_REQUEST['w_solicitante'],$_REQUEST['w_proponente'],$_SESSION['SQ_PESSOA'],null,$_REQUEST['w_objetivo'],$_REQUEST['w_sqcc'],
          Nvl($_REQUEST['w_solic_pai'],$_REQUEST['w_chave_pai']),$_REQUEST['w_descricao'],$_REQUEST['w_justificativa'],$_REQUEST['w_inicio'],
          $_REQUEST['w_fim'],$_REQUEST['w_valor'],$_REQUEST['w_data_hora'],$_REQUEST['w_sq_unidade_resp'],
          $_REQUEST['w_titulo'],$_REQUEST['w_prioridade'],$_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],
          $_REQUEST['w_palavra_chave'],$_REQUEST['w_vincula_contrato'],$_REQUEST['w_vincula_viagem'],null,null,
          null,null,null,&$w_chave_nova,$_REQUEST['w_copia']);
      if ($O == 'I') {
        // Recupera os dados para montagem correta do menu
        $RS1 = db_getMenuData::getInstanceOf($dbms,$w_menu);
        ScriptOpen('JavaScript');
        ShowHTML('  parent.menu.location=\'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_documento=Nr. '.$w_chave_nova.'&w_menu='.$w_menu.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.$TP.MontaFiltro('GET').'\';');
      }elseif ($O=='E') {
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
        $RS1 = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      } 
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJQUALIT' || $SG=='PJBQUALIT') {   
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {   
      // Se for opera��o de exclus�o, verifica se � necess�rio excluir os arquivos f�sicos
      dml_putProjetoDescritivo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_objetivo_superior'],$_REQUEST['w_descricao'],
          $_REQUEST['w_exclusoes'],$_REQUEST['w_premissas'], $_REQUEST['w_restricoes'],$_REQUEST['w_justificativa']);
     // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
     $RS1 = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
     ScriptOpen('JavaScript');
     ShowHTML('  location.href=\''.f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
     ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJETAPA' || $SG=='PJBETAPA') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],'REGISTRO',null);
      if ($O=='E'){
        foreach($RS as $row) { $RS = $row; break;}
        if(f($row,'qt_filhos')>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Exite EAP vinculada a est� EAP!\');');
          ShowHTML(' history.back(1);');
          ScriptClose();
          exit();          
        }
      }
      dml_putProjetoEtapa::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_titulo'],$_REQUEST['w_descricao'],$_REQUEST['w_ordem'],$_REQUEST['w_inicio'],
          $_REQUEST['w_fim'],$_REQUEST['w_perc_conclusao'],$_REQUEST['w_orcamento'],$_REQUEST['w_sq_pessoa'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_vincula_atividade'],$_REQUEST['w_vincula_contrato'],$w_usuario,$_REQUEST['w_programada'],
          $_REQUEST['w_cumulativa'],$_REQUEST['w_quantidade'],null,$_REQUEST['w_pacote'],$_REQUEST['w_base'],
          $_REQUEST['w_pais'],$_REQUEST['w_regiao'],$_REQUEST['w_uf'],$_REQUEST['w_cidade'],$_REQUEST['w_peso']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJRUBRICA'){  
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if($_REQUEST['w_aplicacao_financeira']=='S') {
        $RS = db_getsolicRubrica::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'S',$_REQUEST['w_chave_aux'],'S',null);
        if(count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Cada projeto n�o pode ter mais de uma rubrica de aplica��o financeira!\');');
          ScriptClose();
          retornaFormulario('w_descricao');
          exit();
        }
      }
      dml_putProjetoRubrica::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],
          $_REQUEST['w_sq_cc'], $_REQUEST['w_codigo'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],
          $_REQUEST['w_ativo'],$_REQUEST['w_aplicacao_financeira']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
   } 
  } elseif($SG=='PJCAD' || $SG=='PJCADBOLSA') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putAtualizaEtapa::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$w_usuario,
          $_REQUEST['w_perc_conclusao'],$_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],
          $_REQUEST['w_situacao_atual'],$_REQUEST['w_exequivel'],null,null);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      ShowHTML('  location.href=\''.$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJRECURSO') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProjetoRec::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],
          $_REQUEST['w_tipo'],$_REQUEST['w_descricao'],$_REQUEST['w_finalidade']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='ETAPAREC') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Inicialmente, desativa a op��o em todos os endere�os
      dml_putSolicEtpRec::getInstanceOf($dbms,'E',$_REQUEST['w_chave_aux'],null);
      // Em seguida, ativa apenas para os endere�os selecionados
      for ($i=0; $i<=count($_POST['w_recurso'])-1; $i=$i+1) {
        if ($_REQUEST['w_recurso'][$i]>'') {
          dml_putSolicEtpRec::getInstanceOf($dbms,'I',$_REQUEST['w_chave_aux'],$_REQUEST['w_recurso'][$i]);
        } 
      } 
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$_REQUEST['w_sg']);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJINTERESS' || $SG=='PJBINTERES') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putProjetoInter::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],
          $_REQUEST['w_tipo_visao'],$_REQUEST['w_envia_email']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJAREAS' || $SG=='PJBAREAS') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if($O=='I') {
        $RS = db_getSolicAreas::getInstanceOf($dbms,$_REQUEST['w_chave'],null,'LISTA');
        foreach ($RS as $row) {
          if (f($row,'sq_unidade')== $_REQUEST['w_chave_aux']) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'�rea/Institui��o j� cadastrada!\');');
            ScriptClose();
            retornaFormulario('w_chave_aux');
            exit;
          }
        }
      }
      dml_putProjetoAreas::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],
      $_REQUEST['w_interesse'],$_REQUEST['w_influencia'],$_REQUEST['w_papel']);
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJANEXO' || $SG=='PJBANEXO') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (UPLOAD_ERR_OK==0) {
        $w_maximo = $_REQUEST['w_upload_maximo'];
        foreach ($_FILES as $Chv => $Field) {
          if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
            // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
            ScriptClose();
            retornaFormulario('w_observacao');
            exit();
          }
          if ($Field['size'] > 0) {
            // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
            if ($Field['size'] > $w_maximo) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            } 
            // Se j� h� um nome para o arquivo, mant�m 
            if ($_REQUEST['w_atual']>'') {
              $RS = db_getSolicAnexo::getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (strpos(f($row,'caminho'),'.')!==false) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,30);
                } else {
                  $w_file = basename(f($row,'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
              if (strpos($Field['name'],'.')!==false) {
                $w_file = $w_file.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
              }
            } 
            $w_tamanho = $Field['size'];
            $w_tipo    = $Field['type'];
            $w_nome    = $Field['name'];
            if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
          } 
        } 
        // Se for exclus�o e houver um arquivo f�sico, deve remover o arquivo do disco.  
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
        ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
        ScriptClose();
        exit();
      } 
      ScriptOpen('JavaScript');
      // Recupera a sigla do servi�o pai, para fazer a chamada ao menu 
      $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJENVIO' || $SG=='PJBENVIO') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ((false!==(strpos(strtoupper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(strtoupper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_file = basename($Field['tmp_name']);
              if (strpos($Field['name'],'.')!==false) {
                $w_file = $w_file.substr($Field['name'],(strpos($Field['name'],'.') ? strpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          dml_putProjetoEnvio::getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_tramite'],'N',$_REQUEST['w_observacao'],Tvl($_REQUEST['w_destinatario']),$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        if(substr($SG,0,3)==='PJB') $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PJBGERAL');
        else                        $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PJGERAL');
        if (f($RS,'sq_siw_tramite') != $_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou este projeto para outra fase de execu��o!\');');
          ScriptClose();
        } else {
          dml_putProjetoEnvio::getInstanceOf($dbms, $_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                                             $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],
                                             $_REQUEST['w_despacho'],null,null,null,null);
          // Envia e-mail comunicando a tramita��o
          if ($_REQUEST['w_novo_tramite'] > '') SolicMail($_REQUEST['w_chave'],2);
          if ($P1==1) {
            // Se for envio da fase de cadastramento, remonta o menu principal
            // Recupera os dados para montagem correta do menu
            $RS = db_getMenuData::getInstanceOf($dbms,$w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\'menu.php?par=ExibeDocs&O=L&R='.$R.'&SG='.f($RS,'sigla').'&TP='.RemoveTP(RemoveTP($TP)).MontaFiltro('GET').'\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
            ScriptClose();
          } 
        } 
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif($SG=='PJCONC' || $SG=='PJBCONC') {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      $RS = db_getSolicData::getInstanceOf($dbms,$_REQUEST['w_chave'],'PJGERAL');
      if (f($RS,'sq_siw_tramite') != $_REQUEST['w_tramite']){
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� encaminhou este projeto para outra fase de execu��o!\');');
        ScriptClose();
      } else {
        dml_putProjetoConc::getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
                                          $_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],
                                          $_REQUEST['w_custo_real']);
        // Envia e-mail comunicando a conclus�o
        SolicMail ($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
    break;
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL':         Inicial();           break;
    case 'GERAL':           Geral();             break;
    case 'DESCRITIVO':      Descritivo();        break;   
    case 'RUBRICA':         Rubrica();           break;    
    case 'ANEXO':           Anexos();            break;
    case 'ETAPA':           Etapas();            break;
    case 'RECURSO':         Recursos();          break;
    case 'ETAPARECURSO':    EtapaRecursos();     break;
    case 'INTERESS':        Interessados();      break;
    case 'AREAS':           Areas();             break;
    case 'VISUAL':          Visual();            break;
    case 'EXCLUIR':         Excluir();           break;
    case 'ENVIO':           Encaminhamento();    break;
    case 'ANOTACAO':        Anotar();            break;
    case 'CONCLUIR':        Concluir();          break;
    case 'ATUALIZAETAPA':   AtualizaEtapa();     break;
    case 'GRAVA':           Grava();             break;
    default:
      cabecalho();
      BodyOpen('onLoad=this.focus();');
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
