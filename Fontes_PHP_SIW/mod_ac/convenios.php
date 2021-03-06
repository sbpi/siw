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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getAgreeType.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getUorgData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoParcela.php');
include_once($w_dir_volta.'classes/sp/db_getFormaPagamento.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/db_getConvOutroRep.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getParametro.php');
include_once($w_dir_volta.'classes/sp/db_getConvPreposto.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoTermo.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoParc.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoPreposto.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoRep.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicArquivo.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putAcordoConc.php');
include_once($w_dir_volta.'classes/sp/dml_putConvOutraParte.php');
include_once($w_dir_volta.'classes/sp/dml_putConvPreposto.php');
include_once($w_dir_volta.'classes/sp/dml_putConvOutroRep.php');
include_once($w_dir_volta.'classes/sp/dml_putConvDadosBancario.php');
include_once($w_dir_volta.'funcoes/selecaoTipoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoEtapa.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoFaseCheck.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoConclusao.php');
include_once($w_dir_volta.'funcoes/selecaoTipoOutraParte.php');
include_once('visualconvenio.php');
include_once('validaconvenio.php');
// =========================================================================
//  /convenios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas relativas a controle de conv�nios
// Mail     : celso@sbpi.com.br
// Criacao  : 16/10/2006 14:20
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

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par            = upper($_REQUEST['par']);
$P1             = nvl($_REQUEST['P1'],0);
$P2             = nvl($_REQUEST['P2'],0);
$P3             = nvl($_REQUEST['P3'],1);
$P4             = nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = upper($_REQUEST['SG']);
$R              = $_REQUEST['R'];
$O              = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'convenios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_ac/';
$w_troca        = $_REQUEST['w_troca'];

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ANEXO')!==false || strpos($SG,'OUTRA')!==false|| strpos($SG,'DADOS')!==false|| strpos($SG,'PREPOSTO')!==false || strpos($SG,'PARC')!==false || strpos($SG,'REPR')!==false) {
  if ((strpos('IG',$O)===false) && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'G': $w_TP=$TP.' - Gerar';       break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente       = RetornaCliente();
$w_usuario       = RetornaUsuario();
$w_menu          = RetornaMenu($w_cliente,$SG);
$w_copia         = $_REQUEST['w_copia'];
$p_sq_menu_relac = $_REQUEST['p_sq_menu_relac'];
$p_chave_pai     = upper($_REQUEST['p_chave_pai']);
$p_ativo         = upper($_REQUEST['p_ativo']);
$p_solicitante   = upper($_REQUEST['p_solicitante']);
$p_prioridade    = upper($_REQUEST['p_prioridade']);
$p_unidade       = upper($_REQUEST['p_unidade']);
$p_proponente    = upper($_REQUEST['p_proponente']);
$p_ordena        = lower($_REQUEST['p_ordena']);
$p_ini_i         = upper($_REQUEST['p_ini_i']);
$p_ini_f         = upper($_REQUEST['p_ini_f']);
$p_fim_i         = upper($_REQUEST['p_fim_i']);
$p_fim_f         = upper($_REQUEST['p_fim_f']);
$p_atraso        = upper($_REQUEST['p_atraso']);
$p_chave         = upper($_REQUEST['p_chave']);
$p_objeto        = upper($_REQUEST['p_objeto']);
$p_pais          = upper($_REQUEST['p_pais']);
$p_regiao        = upper($_REQUEST['p_regiao']);
$p_uf            = upper($_REQUEST['p_uf']);
$p_cidade        = upper($_REQUEST['p_cidade']);
$p_usu_resp      = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp     = upper($_REQUEST['p_uorg_resp']);
$p_palavra       = upper($_REQUEST['p_palavra']);
$p_prazo         = upper($_REQUEST['p_prazo']);
$p_fase          = explodeArray($_REQUEST['p_fase']);
$p_sqcc          = upper($_REQUEST['p_sqcc']);
$p_empenho       = upper($_REQUEST['p_empenho']);
$p_processo      = upper($_REQUEST['p_processo']);
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
} 
// Recupera a configura��o do servi�o
if ($P2>0) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
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
  if (is_array($_REQUEST['w_chave'])) {
    $itens = $_REQUEST['w_chave'];
  } else {
    $itens = explode(',', $_REQUEST['w_chave']);
  }
  $w_tipo     = $_REQUEST['w_tipo'];
  $w_envio    = $_REQUEST['w_envio'];
  $w_despacho = $_REQUEST['w_despacho'];

  if ($O=='L') {
    if (strpos(upper($R),'GR_')!==false || strpos(upper($R),'PROJETO')!==false || $w_tipo=='WORD') {
      $w_filtro='';
      if (nvl($p_chave_pai,'')>'') {
        if($w_tipo=='WORD') {
          $w_filtro.='<tr valign="top"><td align="right">Vincula��o<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S','S').']</td></tr>';
        } else {
          $w_filtro.='<tr valign="top"><td align="right">Vincula��o<td>['.exibeSolic($w_dir,$p_chave_pai,null,'S').']</td></tr>';
        }
      }
      if ($p_atividade>'') {
        $sql = new db_getSolicEtapa; $RS = $sql->getInstanceOf($dbms,$p_chave_pai,$p_atividade,'REGISTRO',null);
        $w_filtro = $w_filtro.'<tr valign="top"><td align="right">Etapa <td>[<b>'.f($RS,'titulo').'</b>]';
      }     
      if ($p_sqcc>'') {
        $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
        $w_filtro=$w_filtro.'<tr valign="top"><td align="right"><font size=1>Classifica��o <td><font size=1>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Contrato n� <td>[<b>'.$p_chave.'</b>]';
      if ($p_prazo>'') $w_filtro .= ' <tr valign="top"><td align="right">Prazo para conclus�o at�<td>[<b>'.FormataDataEdicao(addDays(time(),$p_prazo)).'</b>]';
      if ($p_solicitante>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_solicitante,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Respons�vel <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_unidade>'') {
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_unidade);
        $w_filtro .= '<tr valign="top"><td align="right">Unidade respons�vel <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_usu_resp>'') {
        $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_usu_resp,null,null);
        $w_filtro .= '<tr valign="top"><td align="right">Executor <td>[<b>'.f($RS,'nome_resumido').'</b>]';
      } 
      if ($p_uorg_resp>''){
        $sql = new db_getUorgData; $RS = $sql->getInstanceOf($dbms,$p_uorg_resp);
        $w_filtro .= '<tr valign="top"><td align="right">Unidade atual <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_pais>'') {
        $sql = new db_getCountryData; $RS = $sql->getInstanceOf($dbms,$p_pais);
        $w_filtro .= '<tr valign="top"><td align="right">Pa�s <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_regiao>'') {
        $sql = new db_getRegionData; $RS = $sql->getInstanceOf($dbms,$p_regiao);
        $w_filtro .= '<tr valign="top"><td align="right">Regi�o <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_uf>'') {
        $sql = new db_getStateData; $RS = $sql->getInstanceOf($dbms,$p_pais,$p_uf);
        $w_filtro .= '<tr valign="top"><td align="right">Estado <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_cidade>'') {
        $sql = new db_getCityData; $RS = $sql->getInstanceOf($dbms,$p_cidade);
        $w_filtro .= '<tr valign="top"><td align="right">Cidade <td>[<b>'.f($RS,'nome').'</b>]';
      } 
      if ($p_prioridade>'') $w_filtro .= '<tr valign="top"><td align="right">Prioridade <td>[<b>'.RetornaPrioridade($p_prioridade).'</b>]';
      if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Outra parte <td>[<b>'.$p_proponente.'</b>]';
      if ($p_objeto>'')     $w_filtro .= '<tr valign="top"><td align="right">Objeto <td>[<b>'.$p_objeto.'</b>]';
      if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Respons�vel <td>[<b>'.$p_palavra.'</b>]';
      if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
      if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Limite conclus�o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
      if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
      if ($p_empenho>'')    $w_filtro .= '<tr valign="top"><td align="right">N�mero do empenho<td>[<b>'.$p_empenho.'</b>]';
      if ($p_processo>'')   $w_filtro .= '<tr valign="top"><td align="right">N�mero do processo<td>[<b>'.$p_processo.'</b>]';
      if ($w_filtro>'')     $w_filtro  = '<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$SG);
    if ($w_copia>'') {
      // Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } else {
      $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_chave_pai, $p_atividade, 
          null, null, $p_empenho, $p_processo);
    } 
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'inicio','asc');
    } else {
      $RS = SortArray($RS,'nm_outra_parte','asc','inicio','desc');
    }
  }
  
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de conv�nios</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    if ($O=='L' && count($RS) && $P1==2) {
      ShowHTML('  $(document).ready(function() {');
      ShowHTML('    $("#marca_todos").click(function() {');
      ShowHTML('      var checked = this.checked;');
      ShowHTML('      $(".item").each(function() {');
      ShowHTML('        this.checked = checked;');
      ShowHTML('      });');
      ShowHTML('    });');
      ShowHTML('  });');
    }
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O=='L') {
      if (count($RS) && $P1==2) {
        ShowHTML('  var i; ');
        ShowHTML('  var w_erro=true; ');
        ShowHTML('  for (i=1; i < theForm["w_chave[]"].length; i++) {');
        ShowHTML('    if (theForm["w_chave[]"][i].checked) {');
        ShowHTML('       w_erro=false; ');
        ShowHTML('       break; ');
        ShowHTML('    }');
        ShowHTML('  }');
        ShowHTML('  if (w_erro) {');
        ShowHTML('    alert("Voc� deve selecionar pelo menos um registro!"); ');
        ShowHTML('    return false;');
        ShowHTML('  }');
        Validate('w_despacho','Despacho','','','1','2000','1','1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert("Informe o despacho apenas se for devolu��o para a fase anterior!");');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert("Informe um despacho descrevendo o motivo da devolu��o!");');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      }
    } elseif (strpos('CP',$O)!==false) {
      if ($P1!=1 || $O=='C') {
        // Se n�o for cadastramento ou se for c�pia
        if(nvl($p_sq_menu_relac,'')>'') {
          if ($p_sq_menu_relac=='CLASSIF') {
            ShowHTML('  if (theForm.p_sqcc.selectedIndex==0) {');
            ShowHTML('    alert(\'Voc� deve indicar a classifica��o!\');');
            ShowHTML('    theForm.p_sqcc.focus();');
            ShowHTML('    return false;');
            ShowHTML('  }');
          } else {
            ShowHTML('  if (theForm.p_chave_pai.selectedIndex==0) {');
            ShowHTML('    alert(\'Voc� deve indicar a vincula��o!\');');
            ShowHTML('    theForm.p_chave_pai.focus();');
            ShowHTML('    return false;');
            ShowHTML('  }');
          }
        }                          
        Validate('p_chave','N�mero','','','1','18','','0123456789');
        Validate('p_proponente','Outra parte','','','2','90','1','');
        Validate('p_palavra','C�digo interno','','','3','90','1','1');
        Validate('p_atraso','C�digo externo','','','1','90','1','1');
        Validate('p_objeto','Objeto','','','2','90','1','1');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da p�gina
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif ($O=='I') {
    BodyOpenClean('onLoad=\'document.Form.w_smtp_server.focus();\'');
  } elseif ($O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus();\'');
  } elseif ($O=='E') {
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } elseif (strpos('CP',$O)!==false) {
    BodyOpenClean('onLoad=\'document.Form.p_sq_menu_relac.focus()\';');
  } else {
    BodyOpenClean('onLoad=this.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  if($w_tipo!='WORD') {
    if ((strpos(upper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
    }
  }
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if ($P1==1 && $w_copia=='') {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      if ($w_tipo!='WORD') { 
        ShowHTML('<tr><td>');    
        if ($w_submenu>'') {
          $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
          foreach($RS1 as $row) {
            ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
            ShowHTML('    <a accesskey="C" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>C</u>opiar&nbsp;</a>');
          break;
          }
        } else {
         ShowHTML('<a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        } 
      }
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'ACORDO')===false) && $P1!=6) {
      if ($w_tipo!='WORD') {
        if ($w_copia>'') {
          // Se for c�pia
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          } else {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
          } 
        } else {
          if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
          } else {
            ShowHTML('                         <a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
          } 
        }
      } 
    }
    ShowHTML('    <td align="right">');
    ShowHTML('    <b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    if ($w_tipo!='WORD') {
      if (count($RS) && $P1==2) {
        $colspan++; ShowHTML('          <td rowspan=2 align="center" width="15"><span class="remover"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></span></td>');
      }
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('C�digo','codigo_interno').'</font></td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Titulo','titulo').'</font></td>');
      //$colspan++; ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Outra parte','nm_outra_parte_resumido').'</font></td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td rowspan=2><b>'.LinkOrdena('Vincula��o','dados_pai').'</td>'); }
      ShowHTML('          <td colspan=2><b>Vig�ncia</font></td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('$ Previsto','valor').'</font></td>');
      if (strpos(upper($R),'GR_')!==false) {
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('$ Real','valor_atual').'</font></td>');
      }  
      if ($P1!=1) {
        // Se for cadastramento ou mesa de trabalho
        ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fase atual','nm_tramite').'</font></td>');
      } 
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td rowspan=2><b>Opera��es</font></td>');
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</font></td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Fim','fim').'</font></td>');
      ShowHTML('        </tr>');
    } else {
      $colspan++; ShowHTML('          <td rowspan=2><b>C�digo</font></td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>T�tulo</font></td>');
      //$colspan++; ShowHTML('          <td rowspan=2><b>Outra parte</font></td>');
      if ($_SESSION['INTERNO']=='S') { $colspan++; ShowHTML ('          <td rowspan=2><b>Vincula��o</td>'); }
      ShowHTML('          <td colspan=2><b>Vig�ncia</font></td>');
      $colspan++; ShowHTML('          <td rowspan=2><b>$ Previsto</font></td>');
      if (strpos(upper($R),'GR_')!==false) {
        $colspan++; ShowHTML('          <td rowspan=2><b>$ Real</font></td>');
      }  
      if ($P1!=1) {
        // Se for cadastramento ou mesa de trabalho
        $colspan++; ShowHTML('          <td rowspan=2><b>Fase atual</font></td>');
      } 
      if ($_SESSION['INTERNO']=='S') { 
        if ($w_tipo!='WORD') ShowHTML('          <td rowspan=2><b>Opera��es</font></td>');
      }
      ShowHTML('        </tr>');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      $colspan++; ShowHTML('          <td><b>In�cio</font></td>');
      $colspan++; ShowHTML('          <td><b>Fim</font></td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.($colspan+3).' align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = array();
      $w_atual   = array();
      if($w_tipo!='WORD') {
        $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
        if ($P1==2) {
          ShowHTML('<span class="remover">');
          AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, 'LOTE', $w_pagina . $par, $O);
          ShowHTML('<INPUT type="hidden" name="p_agrega" value="'.$SG.'">');
          ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
          ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
          ShowHTML('<input type="hidden" name="w_chave[]" value=""></td>');
          ShowHTML('<input type="hidden" name="w_lista[]" value=""></td>');
          if (nvl($_REQUEST['p_ordena'], '') == '') ShowHTML('<INPUT type="hidden" name="p_ordena" value="">');
          ShowHTML(MontaFiltro('POST'));
          ShowHTML('</span>');
        }
      } else {
        $RS1=$RS;
      }
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        if ($P1==2) {
          ShowHTML('        <td align="center"><span class="remover">');
          ShowHTML('          <INPUT type="hidden" name="w_tramite[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'sq_siw_tramite') . '">');
          ShowHTML('          <INPUT type="hidden" name="w_lista[' . f($row, 'sq_siw_solicitacao') . ']" value="' . f($row, 'codigo_interno') . '">');
          if (in_array(f($row, 'sq_siw_solicitacao'), $itens)) {
            ShowHTML('          <input class="item" type="CHECKBOX" CHECKED  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
          } else {
            ShowHTML('          <input class="item" type="CHECKBOX"  name="w_chave[]" value="' . f($row, 'sq_siw_solicitacao') . '">');
          }
          ShowHTML('        </span></td>');
        }
        ShowHTML('        <td nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio'),f($row,'fim'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4=0&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'objeto').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'codigo_interno').'');
        ShowHTML('        <td>'.f($row,'titulo').'');
        ///if (Nvl(f($row,'outra_parte'),'nulo')!='nulo') {
        //  if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'outra_parte'),$TP,f($row,'nm_outra_parte_resumido')).'</td>');
        //  else                 ShowHTML('        <td>'.f($row,'nm_outra_parte_resumido').'</td>');
        //} else {
        //  ShowHTML('        <td align="center">---</td>');
        //} 
        if ($_SESSION['INTERNO']=='S') {
          if (Nvl(f($row,'dados_pai'),'')!='') ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'S',$w_tipo).'</td>');
          else                                 ShowHTML('        <td>---</td>');
        } 
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>');
        if (Nvl(f($row,'fim'),'')>'') {
          ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>');
        } else {
          ShowHTML('        <td align="center">&nbsp;');
        } 
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
        $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'valor');
        if (strpos(upper($R),'GR_')!==false) {
          if (Nvl(f($row,'valor_atual'),0)>0 && Nvl(f($row,'valor_atual'),0)!=f($row,'valor')) {
            ShowHTML('        <td align="right"><font color="#BC3131"><b>'.number_format(Nvl(f($row,'valor_atual'),0),2,',','.').'&nbsp;</td>');
          } else {
            ShowHTML('        <td align="right">'.number_format(Nvl(f($row,'valor_atual'),0),2,',','.').'&nbsp;</td>');
          } 
          $w_atual[f($row,'sb_moeda')] = nvl($w_atual[f($row,'sb_moeda')],0) + Nvl(f($row,'valor_atual'),0);
        } 
        if ($P1!=1) {
          // Se for cadastramento ou mesa de trabalho
          ShowHTML('        <td nowrap>'.f($row,'nm_tramite').'</td>');
        } 
        if ($_SESSION['INTERNO']=='S') {
          if ($w_tipo!='WORD'){ 
            ShowHTML('        <td align="top" nowrap>'); 
            if ($P1!=3) {
              // Se n�o for acompanhamento
              if ($w_copia>'') {
                // Se for listagem para c�pia
                $sql = new db_getLinkSubMenu; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['SG']);
                foreach($RS1 as $row1) {
                  ShowHTML('          <a accesskey="I" class="hl" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.f($row1,'sigla').'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_copia='.f($row,'sq_siw_solicitacao').MontaFiltro('GET').'">Copiar</a>&nbsp;');
                  break;
                }
              } elseif ($P1==1) {
                // Se for cadastramento
                if ($w_submenu>'') {
                  ShowHTML('          <A class="hl" HREF="menu.php?par=ExibeDocs&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.'&w_documento='.f($row,'codigo_interno').MontaFiltro('GET').'" title="Altera as informa��es gerais" TARGET="menu">AL</a>&nbsp;');
                } else {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es gerais">AL</A>&nbsp');
                } 
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o.">EX</A>&nbsp');
              } elseif ($P1==2 || $P1==6) {
                // Se for execu��o
                if ($w_usuario==f($row,'executor')) {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es, sem envi�-la.">AN</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro respons�vel.">EN</A>&nbsp');
                  if (f($row,'sg_tramite')=='EE') {
                    ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Concluir&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Conclui a execu��o.">CO</A>&nbsp');
                  } 
                } else {
                  if (RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                   ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro respons�vel.">EN</A>&nbsp');
                  } else {
                    ShowHTML('          ---&nbsp');
                  }
                } 
              } 
            } else {
              if (Nvl(f($row,'solicitante'),0)==$w_usuario || 
                  Nvl(f($row,'titular'),0)     ==$w_usuario || 
                  Nvl(f($row,'substituto'),0)  ==$w_usuario || 
                  Nvl(f($row,'tit_exec'),0)    ==$w_usuario || 
                  Nvl(f($row,'subst_exec'),0)  ==$w_usuario
                 ) {
                ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia para outro respons�vel.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              } 
            } 
          } 
          ShowHTML('        </td>');
        } 
        ShowHTML('      </tr>');
      } 
      if ($P1!=1) {
        // Se n�o for cadastramento
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
        if (ceil(count($RS)/$P4)>1) { 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').' desta p�gina&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_parcial);
          foreach($w_parcial as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          if (strpos(upper($R),'GR_')!==false) {
            ShowHTML('          <td align="right" nowrap><b>');
            $i = 0;
            ksort($w_atual);
            foreach($w_atual as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
            echo('</td>');
          } 
          if ($w_tipo!='WORD') ShowHTML('          <td colspan=2 class="remover">&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a �ltima p�gina da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          $w_real  = array();
          $w_total = array();
          foreach($RS as $row) {
            $w_real[f($row,'sb_moeda')]  = nvl($w_real[f($row,'sb_moeda')],0)  + Nvl(f($row,'valor_atual'),0);
            $w_total[f($row,'sb_moeda')] = nvl($w_total[f($row,'sb_moeda')],0) + f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').' da listagem&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_total);
          foreach($w_total as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          if (strpos(upper($R),'GR_')!==false) {
            ShowHTML('          <td align="right" nowrap><b>');
            $i = 0;
            ksort($w_real);
            foreach($w_real as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
            echo('</td>');
          } 
          if ($w_tipo!='WORD') ShowHTML('          <td colspan=2 class="remover">&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if (count($RS) && $w_tipo!='WORD') {
      if ($P1==2) {
        ShowHTML('<span class="remover">');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan=3>');
        ShowHTML('  <table width="97%" border="0">');
        ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
        ShowHTML('      <tr><td><b>Tipo do Encaminhamento</b><br>');
        ShowHTML('        <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"'.((Nvl($w_envio,'N')=='N') ? ' checked' : '').'> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"'.((Nvl($w_envio,'N')=='S') ? ' checked' : '').'> Devolver para a fase anterior');
        ShowHTML('      <tr>');
        ShowHTML('      <tr><td><b>D<u>e</u>spacho (informar apenas se for devolu��o):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinat�rio deve fazer quando receber a solicita��o.">'.$w_despacho.'</TEXTAREA></td>');
        ShowHTML('    </table>');
        ShowHTML('    <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr><input class="STB" type="submit" name="Botao" value="Enviar"></td></tr>');
        ShowHTML('  </table>');
        ShowHTML('  </TD>');
        ShowHTML('</tr>');
        ShowHTML('</FORM>');
      }
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif (strpos('CP',$O)!==false) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Para selecionar o que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    ShowHTML('      <tr><td colspan=2><table border=0 width="100%" cellspacing=0><tr valign="top">');
    selecaoServico('<U>R</U>estringir a:', 'S', null, $p_sq_menu_relac, $P2, null, 'p_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($p_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($p_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classifica��o',null,null,$w_cliente,$p_sqcc,$p_sq_menu_relac,null,'p_sqcc','SIWSOLIC',null);
      } else {
        $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$p_sq_menu_relac);
        if(f($RS_Relac,'sg_modulo')=='PR') {
          SelecaoSolic('Vincula��o:',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_etapa\'; document.Form.submit();"');
          if(nvl($p_chave_pai,'')!='') {
            ShowHTML('      <tr>');
            SelecaoEtapa('<u>T</u>ema e modalidade:','T','Se necess�rio, indique a etapa desejada para a vincula��o.',$p_etapa,$p_chave_pai,null,'p_etapa','CONTRATO',null);
            ShowHTML('      </tr>');
          }
        } else {
          SelecaoSolic('Vincula��o:',null,null,$w_cliente,$p_chave_pai,$p_sq_menu_relac,f($RS_Menu,'sq_menu'),'p_chave_pai',f($RS_Relac,'sigla'),null);
        }
      }
    }
    if ($P1!=1 || $O=='C') {
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>N�mero do conv�nio:<br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td><b>O<U>u</U>tra parte:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>C�<U>d</U>igo interno:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
      ShowHTML('          <td><b>C�<U>d</U>igo externo:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo monitoramento na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respons�vel atua<u>l</u>:','L','Selecione o respons�vel atual na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade desejada na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr valign="top">');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('          <td><b>Dias para <U>t</U>�rmino da vig�ncia:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>In�<u>c</u>io vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        if($P2>0)SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if ($p_Ordena=='ASSUNTO') {
      ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="inicio">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='INICIO') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio" SELECTED>In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='FIM') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite" SELECTED>Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='NM_OUTRA_PARTE') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="prioridade" SELECTED>Outra parte<option value="proponente">Projeto');
    } elseif ($p_Ordena=='NM_PROJETO') {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente" SELECTED>Projeto');
    } else {
      ShowHTML('          <option value="assunto">Objeto<option value="inicio">In�cio vig�ncia<option value="" SELECTED>T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="prioridade">Outra parte<option value="proponente">Projeto');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar c�pia">');
    } else {
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
  $w_chave          = $_REQUEST['w_chave'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  $w_readonly       = '';
  $w_erro           = '';
  
  // Verifica se o cliente tem o m�dulo de acordos contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N'; 

  // Verifica se o cliente tem o m�dulo viagens contratado
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N'; 

  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N'; 

  // Verifica se o cliente tem o m�dulo de planejamento estrat�gico
  $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PE');
  if (count($RS)>0) $w_pe='S'; else $w_pe='N'; 
    
  // Verifica se a gera��o do c�digo ser� autom�tica ou n�o
  $sql = new db_getParametro; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RS_Menu,'sg_modulo'),null);
  foreach($RS as $row){$RS=$row; break;}
  $w_numeracao_automatica = f($RS,'numeracao_automatica');
  // Carrega os valores padr�o para pa�s, estado e cidade 
  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  if ($w_pais=='') {
    $w_pais   = f($RS,'sq_pais');
    $w_uf     = f($RS,'co_uf');
    $w_cidade = f($RS,'sq_cidade_padrao');
  } 
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_sq_unidade_resp      = $_REQUEST['w_sq_unidade_resp'];
    $w_objeto               = $_REQUEST['w_objeto'];
    $w_aviso                = $_REQUEST['w_aviso'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_inicio_atual         = $_REQUEST['w_inicio_real'];
    $w_inicio_real          = $_REQUEST['w_inicio_real'];
    $w_fim_real             = $_REQUEST['w_fim_real'];
    $w_concluida            = $_REQUEST['w_concluida'];
    $w_data_conclusao       = $_REQUEST['w_data_conclusao'];
    $w_nota_conclusao       = $_REQUEST['w_nota_conclusao'];
    $w_custo_real           = $_REQUEST['w_custo_real'];
    $w_projeto              = $_REQUEST['w_projeto'];
    $w_sq_tipo_acordo       = $_REQUEST['w_sq_tipo_acordo'];
    $w_sq_tipo_pessoa       = $_REQUEST['w_sq_tipo_pessoa'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_pai            = $_REQUEST['w_chave_pai'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_sq_menu              = $_REQUEST['w_sq_menu'];
    $w_sq_unidade           = $_REQUEST['w_sq_unidade'];
    $w_sq_tramite           = $_REQUEST['w_sq_tramite'];
    $w_solicitante          = $_REQUEST['w_solicitante'];
    $w_cadastrador          = $_REQUEST['w_cadastrador'];
    $w_executor             = $_REQUEST['w_executor'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_justificativa        = $_REQUEST['w_justificativa'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
    $w_inclusao             = $_REQUEST['w_inclusao'];
    $w_ultima_alteracao     = $_REQUEST['w_ultima_alteracao'];
    $w_conclusao            = $_REQUEST['w_conclusao'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_opiniao              = $_REQUEST['w_opiniao'];
    $w_data_hora            = $_REQUEST['w_data_hora'];
    $w_pais                 = $_REQUEST['w_pais'];
    $w_uf                   = $_REQUEST['w_uf'];
    $w_cidade               = $_REQUEST['w_cidade'];
    $w_palavra_chave        = $_REQUEST['w_palavra_chave'];
    $w_sqcc                 = $_REQUEST['w_sqcc'];
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_titulo               = $_REQUEST['w_titulo'];
    $w_numero_empenho       = $_REQUEST['w_numero_empenho'];
    $w_numero_processo      = $_REQUEST['w_numero_processo'];
    $w_data_assinatura      = $_REQUEST['w_data_assinatura'];
    $w_data_publicacao      = $_REQUEST['w_data_publicacao'];
    $w_sq_menu_relac        = $_REQUEST['w_sq_menu_relac'];
  } else {
    if (strpos('AEV',$O)!==false || $w_copia>'') {
      // Recupera os dados do contrato
      if ($w_copia>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_copia,$SG);
      } else {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
      } 
      if (count($RS)>0) {
        $w_codigo_interno       = f($RS,'codigo_interno');
        $w_titulo               = f($RS,'titulo');
        $w_sq_unidade_resp      = f($RS,'sq_unidade');
        $w_objeto               = f($RS,'objeto');
        $w_aviso                = f($RS,'aviso_prox_conc');
        $w_dias                 = f($RS,'dias_aviso');
        $w_inicio_real          = f($RS,'inicio');
        $w_fim_real             = f($RS,'fim');
        $w_custo_real           = f($RS,'valor');
        $w_projeto              = f($RS,'sq_solic_pai');
        $w_etapa                = f($RS,'sq_projeto_etapa');
        $w_sq_tipo_acordo       = f($RS,'sq_tipo_acordo');
        $w_sq_tipo_pessoa       = f($RS,'sq_tipo_pessoa');
        $w_sq_forma_pagamento   = f($RS,'sq_forma_pagamento');
        $w_forma_atual          = f($RS,'sq_forma_pagamento');
        $w_chave_pai            = f($RS,'sq_solic_pai');
        $w_chave_aux            = null;
        $w_sq_menu              = f($RS,'sq_menu');
        $w_sq_unidade           = f($RS,'sq_unidade');
        $w_sq_tramite           = f($RS,'sq_siw_tramite');
        $w_solicitante          = f($RS,'solicitante');
        $w_cadastrador          = f($RS,'cadastrador');
        $w_executor             = f($RS,'executor');
        $w_descricao            = f($RS,'descricao');
        $w_justificativa        = f($RS,'justificativa');
        $w_inicio               = FormataDataEdicao(f($RS,'inicio'));
        if (strpos('AEV',$O)!==false) {
          $w_inicio_atual       = FormataDataEdicao(f($RS,'inicio'));
        } 
        $w_fim                  = FormataDataEdicao(f($RS,'fim'));
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
        $w_numero_empenho       = f($RS,'empenho');
        $w_numero_processo      = f($RS,'processo');
        $w_opiniao              = f($RS,'opiniao');
        $w_data_assinatura      = FormataDataEdicao(f($RS,'assinatura'));
        $w_data_publicacao      = FormataDataEdicao(f($RS,'publicacao'));
        $w_dados_pai            = explode('|@|',f($RS,'dados_pai'));
        $w_sq_menu_relac        = $w_dados_pai[3];
        if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';        
      } 
    } 
  } 
  if (Nvl($w_sq_tipo_acordo,0)>0) {
    $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,null,null,$SG);
    foreach($RS as $row) {
      $w_cd_modalidade    = f($row,'modalidade');
      $w_prazo_indeterm   = f($row,'prazo_indeterm');
      $w_pessoa_fisica    = f($row,'pessoa_fisica');
      $w_pessoa_juridica  = f($row,'pessoa_juridica'); 
      break;
    }
  } 
  if(nvl($w_sq_menu_relac,0)>0) { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    if($w_numeracao_automatica=='N') Validate('w_codigo_interno','C�digo interno','1',1,1,60,'1','1');
    Validate('w_titulo','T�tulo','1',1,5,100,'1','1');
    Validate('w_sq_tipo_acordo','Modalidade de contrata��o','SELECT',1,1,18,'','0123456789');
    Validate('w_objeto','Objeto','1',1,5,2000,'1','1');
    if ($w_pessoa_fisica=='S' && $w_pessoa_juridica=='S') {
      Validate('w_sq_tipo_pessoa','Pessoa a ser contratada','SELECT',1,1,18,'','0123456789');
    } 
    Validate('w_sq_forma_pagamento','Forma de recebimento','SELECT',1,1,18,'','0123456789');
    if($w_segmento=='P�blico') {
      //Validate('w_numero_empenho','N�mero do empenho','1',1,1,30,'1','1');
      Validate('w_numero_processo','N�mero do processo','1',1,1,30,'1','1');
      Validate('w_data_assinatura','Assinatura','DATA',1,10,10,'','0123456789/');
      Validate('w_data_publicacao','Publica��o','DATA',1,10,10,'','0123456789/'); 
    }
    Validate('w_inicio','In�cio vig�ncia','DATA',1,10,10,'','0123456789/');
    if ($w_prazo_indeterm=='N') {
      Validate('w_fim','T�rmino vig�ncia','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio','In�cio vig�ncia','<=','w_fim','T�rmino vig�ncia');
    } 
    Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
    Validate('w_solicitante','Respons�vel','',1,1,18,'','0123456789');
    Validate('w_sq_unidade_resp','Setor respons�vel','HIDDEN',1,1,18,'','0123456789');
    if(nvl($w_sq_menu_relac,'')>'') {
      Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
      if ($w_sq_menu_relac=='CLASSIF') {
        Validate('w_sqcc','Classifica��o','SELECT',1,1,18,1,1);
      } else {
        Validate('w_chave_pai','Vincula��o','SELECT',1,1,18,1,1);
      }
    } else {
      Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
    }
    
/**
*     if (f($RS_Menu,'solicita_cc')=='S') {
*       if ($w_cd_modalidade!='F') {
*         Validate('w_sqcc','Classifica��o','SELECT','',1,18,'','0123456789');
*       } else {
*         Validate('w_sqcc','Classifica��o','SELECT','1',1,18,'','0123456789');
*       } 
*     } 
*/
    Validate('w_pais','Pa�s','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade','SELECT',1,1,18,'','0123456789');
    if (f($RS_Menu,'descricao')=='S') {
      Validate('w_descricao','Resultados esperados','1',1,5,2000,'1','1');
    } 
    if (f($RS_Menu,'justificativa')=='S') {
      Validate('w_justificativa','Observa��es','1','',5,2000,'1','1');
    } 
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">'); 
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } else {
    if($w_numeracao_automatica=='N')    BodyOpenClean('onLoad=\'document.Form.w_codigo_interno.focus()\';');
    else                                BodyOpenClean('onLoad=\'document.Form.w_titulo.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled = ' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_inicio_atual" value="'.$w_inicio_atual.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco ser�o utilizados para identifica��o, bem como para o controle de sua execu��o.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    if($w_numeracao_automatica=='N') {
      ShowHTML('      <tr>');
      ShowHTML('          <td colspan=2><b><U>C</U>�digo interno:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="STI" type="text" name="w_codigo_interno" size="18" maxlength="60" value="'.$w_codigo_interno.'"></td>');
    }
    ShowHTML('      <tr><td valign="top"><b><u>T</u>�tulo:</b><br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" title="Informe um t�tulo para o conv�nio."></td>');
    ShowHTML('      <tr>');
    SelecaoTipoAcordo('<u>T</u>ipo:','T','Selecione na lista o tipo adequado.',$w_sq_tipo_acordo,null,$w_cliente,'w_sq_tipo_acordo',$SG,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_objeto\'; document.Form.submit();"');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan=2><b>O<u>b</u>jeto:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_objeto" class="sti" ROWS=5 cols=75 title="Descreva o objeto da contrata��o.">'.$w_objeto.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        <tr valign="top">');
    if ($w_pessoa_fisica=='S' && $w_pessoa_juridica=='S') {
      SelecaoTipoPessoa('O<u>u</u>tra parte � pessoa:','T','Selecione na lista o tipo de pessoa que ser� indicada como a outra parte.',$w_sq_tipo_pessoa,$w_cliente,'w_sq_tipo_pessoa',null,null);
    } elseif ($w_pessoa_fisica=='S') {
      $sql = new db_getKindPersonList; $RS = $sql->getInstanceOf($dbms,'F�sica');
      foreach($RS as $row) {
        $w_sq_tipo_pessoa = f($row,'sq_tipo_pessoa');
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
        break;
      }
    } else {
      $sql = new db_getKindPersonList; $RS = $sql->getInstanceOf($dbms,'Jur�dica');
      foreach($RS as $row) {
        $w_sq_tipo_pessoa = f($row,'sq_tipo_pessoa');
        ShowHTML('<INPUT type="hidden" name="w_sq_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
        break;
      }
    }
    SelecaoFormaPagamento('<u>F</u>orma de recebimento:','F','Selecione na lista a forma de recebimento para este acordo.',$w_sq_forma_pagamento,substr($SG,0,3).'CAD','w_sq_forma_pagamento',null);
    if($w_segmento=='P�blico') {
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <td><b>N<U>�</U>mero do processo:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="w_numero_processo" size="20" maxlength="30" value="'.$w_numero_processo.'"></td>');
      ShowHTML('          <td><b><u>A</u>ssinatura:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_data_assinatura" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_assinatura.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_assinatura').'</td>');
      ShowHTML('          <td><b><u>P</u>ublica��o D.O.:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_data_publicacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_publicacao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_publicacao').'</td>');
    }
    ShowHTML('        <tr valign="top">');
    ShowHTML('              <td><b>In�<u>c</u>io vig�ncia:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_inicio,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_inicio').'</td>');
    if ($w_prazo_indeterm=='N') {
      ShowHTML('              <td><b><u>F</u>im vig�ncia:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    } 
    ShowHTML('              <td><b>Valo<u>r</u>:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total real ou estimado."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    SelecaoPessoa('Respo<u>n</u>s�vel monitoramento:','N','Selecione o respons�vel pelo monitoramento.',$w_solicitante,null,'w_solicitante','USUARIOS');
    SelecaoUnidade('<U>S</U>etor respons�vel monitoramento:','S','Selecione o setor respons�vel pelo monitoramento.',$w_sq_unidade_resp,null,'w_sq_unidade_resp',null,null);
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Vincula��o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Selecione uma forma de vincula��o.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('          <tr valign="top">');
    selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
    if(Nvl($w_sq_menu_relac,'')!='') {
      ShowHTML('          <tr valign="top">');
      if ($w_sq_menu_relac=='CLASSIF') {
        SelecaoSolic('Classifica��o:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
      } else {
        SelecaoSolic('Vincula��o:',null,null,$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),null);
      }
    }    
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Local do Fornecimento ou Presta��o do Servi�o</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Selecione pa�s, estado e cidade onde os servi�os ser�o prestados ou onde dever� ocorrer a entrega de produtos. Se mais de uma cidade, selecione a cidade que controlar� os servi�os ou fornecimentos.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    SelecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
    ShowHTML('          </table>');
    if (f($RS_Menu,'descricao')=='S' || f($RS_Menu,'justificativa')=='S') {
      ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Informa��es adicionais</td></td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td>Os dados deste bloco visam orientar os respons�veis pelo monitoramento.</td></tr>');
      ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
      if (f($RS_Menu,'descricao')=='S') {
        ShowHTML('      <tr><td><b>Res<u>u</u>ltados esperados:</b><br><textarea '.$w_Disabled.' accesskey="U" name="w_descricao" class="sti" ROWS=5 cols=75 title="Descreva os resultados esperados com a contrata��o.">'.$w_descricao.'</TEXTAREA></td>');
      } 
      if (f($RS_Menu,'justificativa')=='S') {
        ShowHTML('      <tr><td><b>Obse<u>r</u>va��es:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_justificativa" class="sti" ROWS=5 cols=75 >'.$w_justificativa.'</TEXTAREA></td>');
      } 
    } 
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Alerta de proximidade da data de t�rmino</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados abaixo indicam como deve ser tratada a proximidade do final da vig�ncia.</font></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><table border="0" width="100%">');
    ShowHTML('          <tr>');
    MontaRadioNS('<b>Emite alerta?</b>',$w_aviso,'w_aviso');
    ShowHTML('              <td><b>Quantos <U>d</U>ias antes do fim da vig�ncia?<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="w_dias" size="3" maxlength="3" value="'.$w_dias.'" title="N�mero de dias para emiss�o do alerta de proximidade do final da vig�ncia."></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    //ShowHTML ' history.back(1);'
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
// Rotina de termo de refer�ncia
// -------------------------------------------------------------------------
function Termo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  $w_sq_tipo_acordo = $_REQUEST['w_sq_tipo_acordo'];
  $w_readonly       = '';
  $w_erro           = '';
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_codigo_externo   = $_REQUEST['w_codigo_externo'];
    $w_atividades       = $_REQUEST['w_atividades'];
    $w_produtos         = $_REQUEST['w_produtos'];
    $w_requisitos       = $_REQUEST['w_requisitos'];
    $w_vincula_projeto  = $_REQUEST['w_vincula_projeto'];
    $w_vincula_demanda  = $_REQUEST['w_vincula_demanda'];
    $w_vincula_viagem   = $_REQUEST['w_vincula_viagem'];
    $w_prestacao_contas = $_REQUEST['w_prestacao_contas'];
    $w_chave            = $_REQUEST['w_chave'];
    $w_sq_menu          = $_REQUEST['w_sq_menu'];
  } else {
    if (strpos('AEV',$O)!==false) {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
      if (count($RS)>0) {
        $w_codigo_externo   = f($RS,'codigo_externo');
        $w_atividades       = f($RS,'atividades');
        $w_produtos         = f($RS,'produtos');
        $w_requisitos       = f($RS,'requisitos');
        $w_vincula_projeto  = f($RS,'vincula_projeto');
        $w_vincula_demanda  = f($RS,'vincula_demanda');
        $w_vincula_viagem   = f($RS,'vincula_viagem');
        $w_prestacao_contas = f($RS,'prestacao_contas');
        $w_sq_tipo_acordo   = f($RS,'sq_tipo_acordo');
        $w_sq_menu          = f($RS,'sq_menu');
      } 
    } 
  } 
  if (Nvl($w_sq_tipo_acordo,0)>0) {
    $sql = new db_getAgreeType; $RS = $sql->getInstanceOf($dbms,$w_sq_tipo_acordo,null,$w_cliente,null,null,substr($SG,0,3).'GERAL');
    foreach($RS as $row) {
      $w_cd_modalidade = f($row,'modalidade');
      break;
    }
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_atividades','Atividades','1',1,5,2000,'1','1');
    Validate('w_produtos','Produtos','1',1,5,2000,'1','1');
    Validate('w_codigo_externo','conv�nio','1','',2,60,'1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (strpos('EV',$O)!==false) {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_atividades.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Especifica��o dos produtos ou servi�os</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco ser�o utilizados para especifica��o dos produtos ou servi�os acordados com a outra parte.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><b><u>A</u>tividades a serem desenvolvidas:</b><br><textarea '.$w_Disabled.' accesskey="A" name="w_atividades" class="sti" ROWS=5 cols=75 title="Descreva as atividades a serem desenvolvidas.">'.$w_atividades.'</TEXTAREA></td>');
    ShowHTML('      <tr><td><b><u>P</u>rodutos a serem entregues:</b><br><textarea '.$w_Disabled.' accesskey="P" name="w_produtos" class="sti" ROWS=5 cols=75 title="Relacione os produtos a serem entregues.">'.$w_produtos.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Informa��es adicionais</td></td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td>Os dados deste bloco permitem a identifica��o pela outra parte e configuram as possibilidades de vincula��o com outros tipos de documento.</td></tr>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td><b><u>C</u>�digo do conv�nio para a outra parte:</b><br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_codigo_externo" size="60" maxlength="60" value="'.$w_codigo_externo.'" title="Informe, se desejar, o c�digo pelo qual este acordo � reconhecido pela outra parte."></td>');
    ShowHTML('<tr valign="top">');
    MontaRadioNS('<b>Pemite presta��o de contas?</b>',$w_prestacao_contas,'w_prestacao_contas');    
    if (Nvl($w_cd_modalidade,'')=='F' || Nvl($w_cd_modalidade,'')=='I') {
      ShowHTML('          <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
      MontaRadioNS('<b>Pemite vincula��o de projetos?</b>',$w_vincula_projeto,'w_vincula_projeto');
      if (Nvl($w_cd_modalidade,'')=='F') {
        MontaRadioNS('<b>Pemite vincula��o de demandas?</b>',$w_vincula_demanda,'w_vincula_demanda');
        MontaRadioNS('<b>Pemite vincula��o de viagem?</b>',$w_vincula_viagem,'w_vincula_viagem');
      }
      ShowHTML('      </tr></table>');
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($O=='I') {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } 
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de cadastramento da outra parte
// -------------------------------------------------------------------------
function OutraParte() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='') $O = 'P';
  $w_erro           = '';
  $w_troca          = $_REQUEST['w_troca'];
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_cpf            = $_REQUEST['w_cpf'];
  $w_cnpj           = $_REQUEST['w_cnpj'];
  $w_pessoa_atual   = $_REQUEST['w_pessoa_atual'];
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  if ($w_sq_pessoa=='' && (strpos($_REQUEST['Botao'],'Selecionar')===false)) {
    $w_pessoa_atual     = f($RS,'outra_parte');
  } elseif (strpos($_REQUEST['Botao'],'Selecionar')===false) {
    $w_sq_banco         = f($RS,'sq_banco');
    $w_sq_agencia       = f($RS,'sq_agencia');
    $w_operacao         = f($RS,'operacao_conta');
    $w_nr_conta         = f($RS,'numero_conta');
    $w_sq_pais_estrang  = f($RS,'sq_pais_estrang');
    $w_aba_code         = f($RS,'aba_code');
    $w_swift_code       = f($RS,'swift_code');
    $w_endereco_estrang = f($RS,'endereco_estrang');
    $w_banco_estrang    = f($RS,'banco_estrang');
    $w_agencia_estrang  = f($RS,'agencia_estrang');
    $w_cidade_estrang   = f($RS,'cidade_estrang');
    $w_informacoes      = f($RS,'informacoes');
    $w_codigo_deposito  = f($RS,'codigo_deposito');
  } 
  $w_forma_pagamento    = f($RS,'sg_forma_pagamento');
  $w_sq_tipo_pessoa     = f($RS,'sq_tipo_pessoa');

  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave                 = $_REQUEST['w_chave'];
    $w_chave_aux             = $_REQUEST['w_chave_aux'];
    $w_nome                  = $_REQUEST['w_nome'];
    $w_nome_resumido         = $_REQUEST['w_nome_resumido'];
    $w_sq_pessoa_pai         = $_REQUEST['w_sq_pessoa_pai'];
    $w_nm_tipo_pessoa        = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_tipo_vinculo       = $_REQUEST['w_sq_tipo_vinculo'];
    $w_nm_tipo_vinculo       = $_REQUEST['w_nm_tipo_vinculo'];
    $w_sq_banco              = $_REQUEST['w_sq_banco'];
    $w_sq_agencia            = $_REQUEST['w_sq_agencia'];
    $w_operacao              = $_REQUEST['w_operacao'];
    $w_nr_conta              = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang       = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code              = $_REQUEST['w_aba_code'];
    $w_swift_code            = $_REQUEST['w_swift_code'];
    $w_endereco_estrang      = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang         = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang       = $_REQUEST['w_agencia_estrang'];
    $w_cidade_estrang        = $_REQUEST['w_cidade_estrang'];
    $w_informacoes           = $_REQUEST['w_informacoes'];
    $w_codigo_deposito       = $_REQUEST['w_codigo_deposito'];
    $w_interno               = $_REQUEST['w_interno'];
    $w_vinculo_ativo         = $_REQUEST['w_vinculo_ativo'];
    $w_sq_pessoa_telefone    = $_REQUEST['w_sq_pessoa_telefone'];
    $w_ddd                   = $_REQUEST['w_ddd'];
    $w_nr_telefone           = $_REQUEST['w_nr_telefone'];
    $w_sq_pessoa_celular     = $_REQUEST['w_sq_pessoa_celular'];
    $w_nr_celular            = $_REQUEST['w_nr_celular'];
    $w_sq_pessoa_fax         = $_REQUEST['w_sq_pessoa_fax'];
    $w_nr_fax                = $_REQUEST['w_nr_fax'];
    $w_email                 = $_REQUEST['w_email'];
    $w_sq_pessoa_endereco    = $_REQUEST['w_sq_pessoa_endereco'];
    $w_logradouro            = $_REQUEST['w_logradouro'];
    $w_complemento           = $_REQUEST['w_complemento'];
    $w_bairro                = $_REQUEST['w_bairro'];
    $w_cep                   = $_REQUEST['w_cep'];
    $w_sq_cidade             = $_REQUEST['w_sq_cidade'];
    $w_co_uf                 = $_REQUEST['w_co_uf'];
    $w_sq_pais               = $_REQUEST['w_sq_pais'];
    $w_pd_pais               = $_REQUEST['w_pd_pais'];
    $w_cpf                   = $_REQUEST['w_cpf'];
    $w_nascimento            = $_REQUEST['w_nascimento'];
    $w_rg_numero             = $_REQUEST['w_rg_numero'];
    $w_rg_emissor            = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao            = $_REQUEST['w_rg_emissao'];
    $w_passaporte_numero     = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte    = $_REQUEST['w_sq_pais_passaporte'];
    $w_sexo                  = $_REQUEST['w_sexo'];
    $w_cnpj                  = $_REQUEST['w_cnpj'];
    $w_inscricao_estadual    = $_REQUEST['w_inscricao_estadual'];
    $w_sexo                  = $_REQUEST['w_sexo']; 
    $w_sq_acordo_outra_parte = $_REQUEST['w_sq_acordo_outra_parte']; 
    $w_tipo                  = $_REQUEST['w_tipo'];    

  } else {
    if ($O=='L') {
      // Recupera os representantes pela outra parte
      $sql = new db_getConvOutraParte; $RS1 = $sql->getInstanceOf($dbms,null,$w_chave,null,null);
      if (nvl($p_ordena,'')>'') {
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS1 = SortArray($RS1,$lista[0],$lista[1],'inicio','asc');
      } else {
        $RS1 = SortArray($RS1,'outra_parte','asc','inicio','desc');
      }
    } elseif ((strpos($_REQUEST['Botao'],'Alterar')===false) && (strpos($_REQUEST['Botao'],'Procurar')===false) && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
      // Recupera os dados do benefici�rio em co_pessoa
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null, null, null, null, null);
        if (count($RS)>0) {
        foreach($RS as $row) {
          $w_sq_pessoa            = f($row,'sq_pessoa');
          $w_nome                 = f($row,'nm_pessoa');
          $w_nome_resumido        = f($row,'nome_resumido');
          $w_sq_pessoa_pai        = f($row,'sq_pessoa_pai');
          $w_nm_tipo_pessoa       = f($row,'nm_tipo_pessoa');
          $w_sq_tipo_vinculo      = f($row,'sq_tipo_vinculo');
          $w_nm_tipo_vinculo      = f($row,'nm_tipo_vinculo');
          $w_interno              = f($row,'interno');
          $w_vinculo_ativo        = f($row,'vinculo_ativo');
          $w_sq_pessoa_telefone   = f($row,'sq_pessoa_telefone');
          $w_ddd                  = f($row,'ddd');
          $w_nr_telefone          = f($row,'nr_telefone');
          $w_sq_pessoa_celular    = f($row,'sq_pessoa_celular');
          $w_nr_celular           = f($row,'nr_celular');
          $w_sq_pessoa_fax        = f($row,'sq_pessoa_fax');
          $w_nr_fax               = f($row,'nr_fax');
          $w_email                = f($row,'email');
          $w_sq_pessoa_endereco   = f($row,'sq_pessoa_endereco');
          $w_logradouro           = f($row,'logradouro');
          $w_complemento          = f($row,'complemento');
          $w_bairro               = f($row,'bairro');
          $w_cep                  = f($row,'cep');
          $w_sq_cidade            = f($row,'sq_cidade');
          $w_co_uf                = f($row,'co_uf');
          $w_sq_pais              = f($row,'sq_pais');
          $w_pd_pais              = f($row,'pd_pais');
          $w_cpf                  = f($row,'cpf');
          $w_nascimento           = FormataDataEdicao(f($row,'nascimento'));
          $w_rg_numero            = f($row,'rg_numero');
          $w_rg_emissor           = f($row,'rg_emissor');
          $w_rg_emissao           = FormataDataEdicao(f($row,'rg_emissao'));
          $w_passaporte_numero    = f($row,'passaporte_numero');
          $w_sq_pais_passaporte   = f($row,'sq_pais_passaporte');
          $w_sexo                 = f($row,'sexo');
          $w_cnpj                 = f($row,'cnpj');
          $w_inscricao_estadual   = f($row,'inscricao_estadual');
          if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
            if (Nvl($w_nr_conta,'')=='') {
              $w_sq_banco     = f($row,'sq_banco');
              $w_sq_agencia   = f($row,'sq_agencia');
              $w_operacao     = f($row,'operacao');
              $w_nr_conta     = f($row,'nr_conta');
            } 
          } 
          break;
        }
      } 
    } 
  }
  
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCNPJ();
  FormataCEP();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['Botao'],'Procurar')===false) || !(strpos($_REQUEST['Botao'],'Alterar')===false)) {
    // Se o benefici�rio ainda n�o foi selecionado
    ShowHTML('  if (theForm.Botao.value == "Procurar") {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = "Procurar";');
    ShowHTML('} else {');
    if ($w_sq_tipo_pessoa==1) {
      Validate('w_cpf','CPF','CPF','1','14','14','','0123456789-.');
    } else {
      Validate('w_cnpj','CNPJ','CNPJ','1','18','18','','0123456789/-.');
    } 
    ShowHTML('  theForm.w_sq_pessoa.value = \'\';');
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.w_troca.value.indexOf(\'Alterar\') >= 0) { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','1');
    Validate('w_tipo','Tipo','SELECT',1,1,10,'1','1');
    if ($w_sq_tipo_pessoa==1) {
      Validate('w_nascimento','Data de Nascimento','DATA','',10,10,'',1);
      Validate('w_sexo','Sexo','SELECT',1,1,1,'MF','');
      Validate('w_rg_numero','Identidade','1',1,2,30,'1','1');
      Validate('w_rg_emissao','Data de emiss�o','DATA','',10,10,'','0123456789/');
      Validate('w_rg_emissor','�rg�o expedidor','1',1,2,30,'1','1');
      Validate('w_passaporte_numero','Passaporte','1','',1,20,'1','1');
      Validate('w_sq_pais_passaporte','Pa�s emissor','SELECT','',1,10,'1','1');
    } else {
      Validate('w_inscricao_estadual','Inscri��o estadual','1','',2,20,'1','1');
    } 
    Validate('w_ddd','DDD','1','1',2,4,'','0123456789');
    Validate('w_nr_telefone','Telefone','1',1,7,25,'1','1');
    Validate('w_nr_fax','Fax','1','',7,25,'1','1');
    Validate('w_nr_celular','Celular','1','',7,25,'1','1');
    Validate('w_logradouro','Logradouro','1',1,4,60,'1','1');
    Validate('w_complemento','Complemento','1','',2,20,'1','1');
    Validate('w_bairro','Bairro','1','',2,30,'1','1');
    Validate('w_sq_pais','Pa�s','SELECT',1,1,10,'1','1');
    Validate('w_co_uf','UF','SELECT',1,1,10,'1','1');
    Validate('w_sq_cidade','Cidade','SELECT',1,1,10,'','1');
    if (Nvl($w_pd_pais,'S')=='S') {
      Validate('w_cep','CEP','1','',9,9,'','0123456789-');
    } else {
      Validate('w_cep','CEP','1',1,5,9,'','0123456789');
    } 
    Validate('w_email','E-Mail','1','',4,60,'1','1');
    ShowHTML('  theForm.Botao.disabled=true;');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='L') {
    BodyOpen('null');
  } elseif (($w_cpf=='' && $w_cnpj=='') || (strpos($_REQUEST['Botao'],'Alterar')!==false) || (strpos($_REQUEST['Botao'],'Procurar')!==false)) {
    // Se o benefici�rio ainda n�o foi selecionado
    if (strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se est� sendo feita busca por nome
      BodyOpenClean('onLoad=\'this.focus()\';');
    } else {
      if ($w_sq_tipo_pessoa==1) {
        BodyOpenClean('onLoad=\'document.Form.w_cpf.focus()\';');
      } else {
        BodyOpenClean('onLoad=\'document.Form.w_cnpj.focus()\';');
      } 
    } 
  } elseif ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_nome.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L'){
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS1));
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>'.LinkOrdena('Nome','nm_pessoa').'</font></td>');
        ShowHTML('          <td><b>'.LinkOrdena('Nome resumido','nome_resumido').'</font></td>');
        if ($w_sq_tipo_pessoa==1) {
          ShowHTML('          <td><b>'.LinkOrdena('CPF','cpf').'</font></td>');
          ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo').'</font></td>');
        } else {
          ShowHTML('          <td><b>'.LinkOrdena('CNPJ','cnpj').'</font></td>');
          ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo').'</font></td>');
        } 
        ShowHTML('          <td class="remover"><b>Opera��es</font></td>');
        ShowHTML('        </tr>');
        if (count($RS1)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS1 as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            } else {
              ShowHTML('        <td align="center" nowrap>'.Nvl(f($row,'cnpj'),'---').'</td>');
              ShowHTML('        <td>'.Nvl(f($row,'nm_tipo'),'---').'</td>');
            } 

            ShowHTML('        <td class="remover" nowrap>');
           
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=A&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'representante.php?par=inicial&R='.$R.'&O=L&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_outra_parte='.f($row,'outra_parte').'&w_tipo=1&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Representante legal').'&SG=GCCPREP\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');"Botao=Selecionar">Rep. Legal</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.'representante.php?par=inicial&R='.$R.'&O=L&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_outra_parte='.f($row,'outra_parte').'&w_tipo=2&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Contato').'&SG=GCCREPRES\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');"Botao=Selecionar">Contatos</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_sq_acordo_outra_parte='.f($row,'sq_acordo_outra_parte').'&w_chave_aux='.$w_cliente.'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">EX</A>&nbsp');
            } 
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');   

  } elseif (strpos('IA',$O)!==false) {
    if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['Botao'],'Alterar')===false) || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      // Se o benefici�rio ainda n�o foi selecionado
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } else {
      ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    } 
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if (($w_cpf=='' && $w_cnpj=='') || !(strpos($_REQUEST['Botao'],'Alterar')===false) || strpos($_REQUEST['Botao'],'Procurar')!==false) {
      $w_nome=$_REQUEST['w_nome'];
      if (strpos($_REQUEST['Botao'],'Alterar')!==false) {
        $w_cpf  = '';
        $w_cnpj = '';
        $w_nome = '';
      } 
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=4>Informe os dados abaixo e clique no bot�o "Selecionar" para continuar.</TD>');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cpf" VALUE="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      } else {
        ShowHTML('        <tr><td colspan=4><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="w_cnpj" VALUE="'.$w_cnpj.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
      } 
      ShowHTML('            <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('        <tr><td colspan=4><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=4 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=4>');
      ShowHTML('             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($w_nome>'') {
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null,null,null,$w_nome,$w_sq_tipo_pessoa,null,null,null,null,null,null,null, null, null, null, null);
        ShowHTML('<tr><td colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Nome</font></td>');
        ShowHTML('          <td><b>Nome resumido</font></td>');
        if ($w_sq_tipo_pessoa==1) {
          ShowHTML('          <td><b>CPF</font></td>');
        } else {
          ShowHTML('          <td><b>CNPJ</font></td>');
        } 

        ShowHTML('          <td><b>Opera��es</font></td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
            ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cpf'),'---').'</td>');
            } else {
              ShowHTML('        <td align="center">'.Nvl(f($row,'cnpj'),'---').'</td>');
            } 
            ShowHTML('        <td nowrap>');            
            if ($w_sq_tipo_pessoa==1) {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=I&w_chave_aux='.$w_chave_aux.'&w_cpf='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            } else {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O=I&w_chave_aux='.$w_chave_aux.'&w_cnpj='.f($row,'cnpj').'&w_sq_pessoa='.f($row,'sq_pessoa').'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&Botao=Selecionar">Selecionar</A>&nbsp');
            } 
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
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border="0" width="100%">');
      ShowHTML('          <tr valign="top">');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('          <td>CPF:</font><br><b><font size=2>'.$w_cpf);
        ShowHTML('              <INPUT type="hidden" name="w_cpf" value="'.$w_cpf.'">');
      } else {
        ShowHTML('          <td colspan="2">CNPJ:</font><br><b><font size=2>'.$w_cnpj);
        ShowHTML('              <INPUT type="hidden" name="w_cnpj" value="'.$w_cnpj.'">');
      } 
      ShowHTML('          <tr valign="top">');
      ShowHTML('             <td><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('             <td><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.$w_nome_resumido.'"></td>');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr valign="top">');
        SelecaoSexo('Se<u>x</u>o:','X',null,$w_sexo,null,'w_sexo',null,null);
        ShowHTML('          <td><b>Da<u>t</u>a de nascimento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
        ShowHTML('          <tr valign="top">');
        ShowHTML('          <td><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg_numero.'"></td>');
        ShowHTML('          <td><b>Data de <u>e</u>miss�o:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
        ShowHTML('          <td><b>�r<u>g</u>�o emissor:</b><br><input '.$w_Disabled.' accesskey="G" type="text" name="w_rg_emissor" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_rg_emissor.'"></td>');
        ShowHTML('          <tr valign="top">');
        ShowHTML('          <td><b>Passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
        SelecaoPais('<u>P</u>a�s emissor do passaporte:','P',null,$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
        ShowHTML('          </table>');
      } else {
        ShowHTML('      <tr><td><b><u>I</u>nscri��o estadual:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inscricao_estadual" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_inscricao_estadual.'"></td>');
        SelecaoTipoOutraParte('<u>T</u>ipo:','T','Selecione de acordo.',$w_tipo,null,'w_tipo',null,null); 
      } 
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      if ($w_sq_tipo_pessoa==1) {
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endere�o comercial, Telefones e e-Mail</td></td></tr>');
      } else {
        ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Endere�o principal, Telefones e e-Mail</td></td></tr>');
      } 
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td><b><u>D</u>DD:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ddd.'"></td>');
      ShowHTML('          <td><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_nr_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_nr_telefone.'"> '.consultaTelefone($w_cliente).'</td>');
      ShowHTML('          <td title="Se informar um n�mero de fax, informe-o neste campo."><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_nr_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_fax.'"></td>');
      ShowHTML('          <td title="Se informar um celular institucional, informe-o neste campo."><b>C<u>e</u>lular:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_nr_celular" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_nr_celular.'"></td>');
      ShowHTML('          <tr valign="top">');
      ShowHTML('          <td colspan=2><b>En<u>d</u>ere�o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_logradouro" class="sti" SIZE="50" MAXLENGTH="50" VALUE="'.$w_logradouro.'"></td>');
      ShowHTML('          <td><b>C<u>o</u>mplemento:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_complemento" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_complemento.'"></td>');
      ShowHTML('          <td><b><u>B</u>airro:</b><br><input '.$w_Disabled.' accesskey="B" type="text" name="w_bairro" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_bairro.'"></td>');
      ShowHTML('          <tr valign="top">');
      SelecaoPais('<u>P</u>a�s:','P',null,$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
      ShowHTML('          <td>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$w_co_uf,$w_sq_pais,null,'w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
      ShowHTML('          <tr valign="top">');
      if (Nvl($w_pd_pais,'S')=='S') {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'" onKeyDown="FormataCEP(this,event);"></td>');
      } else {
        ShowHTML('              <td><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="9" MAXLENGTH="9" VALUE="'.$w_cep.'"></td>');
      } 
      ShowHTML('              <td colspan=3 title="Se informar um e-mail institucional, informe-o neste campo."><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="50" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
      ShowHTML('          </table>');

      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      //ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de dados banc�rio
// -------------------------------------------------------------------------
function DadosBancario() {
  extract($GLOBALS);
  global $w_Disabled;
  if ($O=='L') $O = 'I';
  $w_erro           = '';
  $w_troca          = $_REQUEST['w_troca'];
  $w_chave          = $_REQUEST['w_chave'];
  $w_chave_aux      = $_REQUEST['w_chave_aux'];
  $w_cnpj           = $_REQUEST['w_cnpj'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_pessoa_atual   = $_REQUEST['w_pessoa_atual'];

  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_sq_banco         = f($RS,'sq_banco');
  $w_sq_agencia       = f($RS,'sq_agencia');
  $w_operacao         = f($RS,'operacao_conta');
  $w_nr_conta         = f($RS,'numero_conta');
  $w_sq_pais_estrang  = f($RS,'sq_pais_estrang');
  $w_aba_code         = f($RS,'aba_code');
  $w_swift_code       = f($RS,'swift_code');
  $w_endereco_estrang = f($RS,'endereco_estrang');
  $w_banco_estrang    = f($RS,'banco_estrang');
  $w_agencia_estrang  = f($RS,'agencia_estrang');
  $w_cidade_estrang   = f($RS,'cidade_estrang');
  $w_informacoes      = f($RS,'informacoes');
  $w_codigo_deposito  = f($RS,'codigo_deposito');
  $w_forma_pagamento    = f($RS,'sg_forma_pagamento');
  $w_sq_tipo_pessoa     = f($RS,'sq_tipo_pessoa');

  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_chave                = $_REQUEST['w_chave'];
    $w_chave_aux            = $_REQUEST['w_chave_aux'];
    $w_sq_banco             = $_REQUEST['w_sq_banco'];
    $w_sq_agencia           = $_REQUEST['w_sq_agencia'];
    $w_operacao             = $_REQUEST['w_operacao'];
    $w_nr_conta             = $_REQUEST['w_nr_conta'];
    $w_sq_pais_estrang      = $_REQUEST['w_sq_pais_estrang'];
    $w_aba_code             = $_REQUEST['w_aba_code'];
    $w_swift_code           = $_REQUEST['w_swift_code'];
    $w_endereco_estrang     = $_REQUEST['w_endereco_estrang'];
    $w_banco_estrang        = $_REQUEST['w_banco_estrang'];
    $w_agencia_estrang      = $_REQUEST['w_agencia_estrang'];
  } else {
    if ((strpos($_REQUEST['Botao'],'Alterar')===false) && (strpos($_REQUEST['Botao'],'Procurar')===false) && ($O=='A' || $w_sq_pessoa>'' || $w_cpf>'' || $w_cnpj>'')) {
      // Recupera os dados do benefici�rio em co_pessoa
      $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,$w_cpf,$w_cnpj,null,null,null,null,null,null,null,null,null, null, null, null, null);
      if (count($RS)>0) {
        foreach($RS as $row) {
          $w_sq_pessoa            = f($row,'sq_pessoa');
          $w_nome                 = f($row,'nm_pessoa');
          $w_nome_resumido        = f($row,'nome_resumido');
          $w_sq_pessoa_pai        = f($row,'sq_pessoa_pai');
          if (strpos('CREDITO,DEPOSITO',$w_forma_pagamento)!==false) {
            if (Nvl($w_nr_conta,'')=='') {
              $w_sq_banco     = f($row,'sq_banco');
              $w_sq_agencia   = f($row,'sq_agencia');
              $w_operacao     = f($row,'operacao');
              $w_nr_conta     = f($row,'nr_conta');
            } 
          } 
          break;
        }
      } 
    } 
  } 

  // Recupera informa��o do campo opera��o do banco selecionado
  if (nvl($w_sq_banco,'')>'') {
    $sql = new db_getBankData; $RS_Banco = $sql->getInstanceOf($dbms, $w_sq_banco);
    $w_exige_operacao = f($RS_Banco,'exige_operacao');
  }
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_sq_banco','Banco','SELECT',1,1,10,'1','1');
    Validate('w_sq_agencia','Agencia','SELECT',1,1,10,'1','1');
    if ($w_exige_operacao=='S') Validate('w_operacao','Opera��o','1','1',1,6,'','0123456789');
    Validate('w_nr_conta','N�mero da conta','1','1',2,30,'ZXAzxa','0123456789-.');      
  } 
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (strpos('IA',$O)!==false) {
    ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_pessoa_atual" value="'.$w_pessoa_atual.'">');
    if (strpos('IA',$O)!==false) {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td colspan="2" align="center" height="2" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" bgcolor="#D0D0D0"><b>Dados banc�rios</td></td></tr>');
      ShowHTML('      <tr><td colspan="2" align="center" height="1" bgcolor="#000000"></td></tr>');
      ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr valign="top">');
      SelecaoBanco('<u>B</u>anco:','B','Selecione o banco onde dever�o ser feitos os pagamentos referentes ao acordo.',$w_sq_banco,null,'w_sq_banco',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&O=I\'; document.Form.w_troca.value=\'w_sq_agencia\'; document.Form.submit();"');
      SelecaoAgencia('A<u>g</u>�ncia:','A','Selecione a ag�ncia onde dever�o ser feitos os pagamentos referentes ao acordo.',$w_sq_agencia,Nvl($w_sq_banco,-1),'w_sq_agencia',null,null);
      ShowHTML('      <tr valign="top">');
      if ($w_exige_operacao=='S') ShowHTML('          <td title="Alguns bancos trabalham com o campo "Opera��o", al�m do n�mero da conta. A Caixa Econ�mica Federal � um exemplo. Se for o caso,informe a opera��o neste campo; caso contr�rio, deixe-o em branco."><b>O<u>p</u>era��o:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_operacao" class="sti" SIZE="6" MAXLENGTH="6" VALUE="'.$w_operacao.'"></td>');
      ShowHTML('          <td title="Informe o n�mero da conta banc�ria, colocando o d�gito verificador, se existir, separado por um h�fen. Exemplo: 11214-3. Se o banco n�o trabalhar com d�gito verificador, informe apenas n�meros. Exemplo: 10845550."><b>N�mero da con<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nr_conta" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nr_conta.'"></td>');
      ShowHTML('          </table>');
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar" onClick="Botao.value=this.value;">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
  ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de parcelas
// -------------------------------------------------------------------------
function Parcelas() {
  extract($GLOBALS);
  global $w_Disabled;
  
  $w_chave             = $_REQUEST['w_chave'];
  $w_chave_aux         = $_REQUEST['w_chave_aux'];
  $w_sq_acordo_aditivo = $_REQUEST['w_sq_acordo_aditivo'];

  // Recupera dados do contrato
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_inicio           = f($RS_Solic,'inicio');
  $w_fim              = f($RS_Solic,'fim');
  $w_prazo_indeterm   = f($RS_Solic,'prazo_indeterm');
  $w_valor_acordo     = f($RS_Solic,'valor_inicial');
  $w_texto            = '';
  $w_edita            = true;
  
  // Bloqueia a inclus�o, gera��o e exclus�o de parcelas ligadas ao contrato se j� existir algum aditivo
  if(nvl($w_sq_acordo_aditivo,'')=='' && (nvl(f($RS_Solic,'aditivo_prorrogacao'),'')!='' || nvl(f($RS_Solic,'aditivo_excedente'),'')!='')) {
    $w_edita           = false;
  }

  // Recupera dados do aditivo, caso tenha sido informado
  if (nvl($w_sq_acordo_aditivo,'')!='') {
    $sql = new db_getAcordoAditivo; $RS_Adit = $sql->getInstanceOf($dbms,$w_cliente,$w_sq_acordo_aditivo,$w_chave,null,null,null,null,null,null,null,null,null);
    foreach($RS_Adit as $row) { $RS_Adit = $row; break; }
    $w_inicio           = f($RS_Adit,'inicio');
    $w_fim              = f($RS_Adit,'fim');
    $w_prazo_indeterm   = f($RS_Adit,'prazo_indeterm');
    $w_valor_acordo     = f($RS_Adit,'valor_aditivo');
    $w_prorrogacao      = f($RS_Adit,'prorrogacao');
    $w_acrescimo        = f($RS_Adit,'acrescimo');
    $w_supressao        = f($RS_Adit,'supressao');
    $w_valor_parcela    = f($RS_Adit,'vl_parcela');
    $w_texto            = '<tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
    $w_texto            .= chr(13).'<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="1"><b>';
    $w_texto            .= chr(13).'Aditivo: '.f($RS_Adit,'codigo').' abrangendo o per�odo de '.formataDataEdicao(f($RS_Adit,'inicio')).' a '.formataDataEdicao(f($RS_Adit,'fim'));
    $w_texto            .= chr(13).'  <br>[Prorroga��o: '.f($RS_Adit,'nm_prorrogacao').'] [Reajuste: '.f($RS_Adit,'nm_revisao').'] [Tipo: '.f($RS_Adit,'nm_tipo').']';
  }
    
  //if((nvl($w_sq_acordo_aditivo,'')!='')&&($w_acrescimo=='S' || $w_supressao=='S')) {
  //  $w_edita           = false;
  // }
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_ordem           = $_REQUEST['w_ordem'];
    $w_data            = $_REQUEST['w_data'];
    $w_observacao      = $_REQUEST['w_observacao'];
    $w_valor           = $_REQUEST['w_valor'];
    $w_per_ini         = $_REQUEST['w_per_ini'];
    $w_per_fim         = $_REQUEST['w_per_fim'];
    $w_valor_inicial   = $_REQUEST['w_valor_inicial'];
    $w_valor_excedente = $_REQUEST['w_valor_excedente'];
    $w_valor_reajuste  = $_REQUEST['w_valor_reajuste'];
  } elseif ($O=='L') {
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$w_chave,null,'NOTA',null,null,null,null,null,null,$w_sq_acordo_aditivo);
    $RS = SortArray($RS,'ordem','asc');
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endere�o informado
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,'NOTA',null,null,null,null,null,null,$w_sq_acordo_aditivo);
    foreach($RS as $row) {
      $w_ordem           = f($row,'ordem');
      $w_data            = FormataDataEdicao(f($row,'vencimento'));
      $w_observacao      = f($row,'observacao');
      $w_valor           = number_format(f($row,'valor'),2,',','.'); 
      $w_per_ini         = FormataDataEdicao(f($row,'inicio'));
      $w_per_fim         = FormataDataEdicao(f($row,'fim'));
      $w_valor_inicial   = formatNumber(f($row,'valor_inicial'));
      $w_valor_excedente = formatNumber(f($row,'valor_excedente'));
      $w_valor_reajuste  = formatNumber(f($row,'valor_reajuste'));
      break;
    }
  } 

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  if (strpos('IAEGCPV',$O)!==false) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataValor();
    ShowHTML('function trataUnica() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {');
    ShowHTML('     document.Form.w_vencimento[0].checked = false;');
    ShowHTML('     document.Form.w_vencimento[1].checked = false;');
    ShowHTML('     document.Form.w_vencimento[2].checked = false;');
    ShowHTML('     document.Form.w_dia_vencimento.value = \'\';');
    ShowHTML('     document.Form.w_valor_parcela[0].checked = false;');
    ShowHTML('     document.Form.w_valor_parcela[1].checked = false;');
    ShowHTML('     document.Form.w_valor_parcela[2].checked = false;');
    ShowHTML('     document.Form.w_valor_parcela[3].checked = false;');
    ShowHTML('     document.Form.w_valor_diferente.value = \'\';');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataVencimento() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_vencimento[0].checked || document.Form.w_vencimento[1].checked) {');
    ShowHTML('     document.Form.w_dia_vencimento.value = \'\';');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataValor() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_valor_parcela[0].checked) {');
    ShowHTML('     document.Form.w_valor_diferente.value = \'\';');
    ShowHTML('   }');
    ShowHTML('}');
    ShowHTML('function trataDiaVencimento() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('   }');
    ShowHTML('   document.Form.w_vencimento[2].checked = true;');
    ShowHTML('}');
    ShowHTML('function trataValorDiferente() {');
    ShowHTML('  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {');
    ShowHTML('     document.Form.w_tipo_geracao[0].checked = false;');
    ShowHTML('     document.Form.w_tipo_geracao[1].checked = false;');
    ShowHTML('   }');
    ShowHTML('  if (document.Form.w_valor_parcela[0].checked) {');
    ShowHTML('     document.Form.w_valor_parcela[0].checked = false;');
    ShowHTML('   }');
    ShowHTML('}');
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_ordem','N�mero de ordem da parcela','1','1','1','4','','0123456789');
      Validate('w_data','Data de vencimento da parcela','DATA','1','10','10','','0123456789/');
      CompData('w_data','Data de vencimento','>=','w_inicio','Data de in�cio de vig�ncia');
      CompData('w_data','Data de vencimento','<=','w_fim','Data de t�rmino de vig�ncia');
      if(nvl($w_sq_acordo_aditivo,'')=='') {
        Validate('w_valor','Valor da parcela','VALOR','1',4,18,'','0123456789.,');
      } else {
        if(f($RS_Adit,'prorrogacao')=='S') Validate('w_valor_inicial','Valor inicial','VALOR','1',4,18,'','0123456789.,');
        if(f($RS_Adit,'acrescimo')=='S'||f($RS_Adit,'supressao')=='S') Validate('w_valor_excedente','Valor do acr�scimo/supress�o','VALOR','1',4,18,'','-0123456789.,');
        if(f($RS_Adit,'revisao')=='S')     Validate('w_valor_reajuste','Valor reajuste','VALOR','1',4,18,'','0123456789.,');
      }
      Validate('w_per_ini','In�cio do per�odo de realiza��o','DATA','1','10','10','','0123456789/');
      CompData('w_per_ini','In�cio do per�odo de realiza��o','>=','w_inicio','Data de in�cio de vig�ncia');
      CompData('w_per_ini','In�cio do per�odo de realiza��o','<=','w_fim','Data de t�rmino de vig�ncia');
      Validate('w_per_fim','Fim do per�odo de realiza��o','DATA','1','10','10','','0123456789/');
      CompData('w_per_fim','Fim do per�odo de realiza��o','>=','w_inicio','Data de in�cio de vig�ncia');
      CompData('w_per_fim','Fim do per�odo de realiza��o','<=','w_fim','Data de t�rmino de vig�ncia');
      Validate('w_observacao','Observa��o','1','','3','200','1','1');
    } elseif ($O=='G') {
      Validate('w_dia_vencimento','Dia de vencimento','1','',1,2,'','0123456789');
      if(nvl($w_sq_acordo_aditivo,'')=='') {
        Validate('w_valor_diferente','Valor da parcela','VALOR','',4,18,'','0123456789.,');
      }
      ShowHTML('  for (i = 0; i < theForm.w_tipo_geracao.length; i++) {');
      ShowHTML('      if (theForm.w_tipo_geracao[i].checked) break;');
      ShowHTML('      if (i == theForm.w_tipo_geracao.length-1) {');
      ShowHTML('         alert(\'Voc� deve selecionar uma das op��es apresentadas!\');');
      ShowHTML('         return false;');
      ShowHTML('      }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_tipo_geracao[2].checked || theForm.w_tipo_geracao[3].checked ) {');
      ShowHTML('     for (i = 0; i < theForm.w_vencimento.length; i++) {');
      ShowHTML('         if (theForm.w_vencimento[i].checked) break;');
      ShowHTML('         if (i == theForm.w_vencimento.length-1) {');
      ShowHTML('            alert(\'Voc� deve selecionar um dia para vencimento das parcelas!\');');
      ShowHTML('            return false;');
      ShowHTML('         }');
      ShowHTML('     }');
      ShowHTML('     for (i = 0; i < theForm.w_valor_parcela.length; i++) {');
      ShowHTML('         if (theForm.w_valor_parcela[i].checked) break;');
      ShowHTML('         if (i == theForm.w_valor_parcela.length-1) {');
      ShowHTML('            alert(\'Voc� deve selecionar uma das op��es para c�lculo do valor das parcelas!\');');
      ShowHTML('            return false;');
      ShowHTML('         }');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_vencimento[2].checked) {');
      ShowHTML('     if (theForm.w_dia_vencimento.value == \'\') {');
      ShowHTML('        alert(\'Voc� deve informar o dia de vencimento das parcelas!\');');
      ShowHTML('        theForm.w_dia_vencimento.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('     if (theForm.w_dia_vencimento.value > 28) {');
      ShowHTML('        alert(\'Para vencimento ap�s o dia 28, utilize a op��o de vencimento no �ltimo dia do m�s!\');');
      ShowHTML('        theForm.w_dia_vencimento.focus();');
      ShowHTML('        return false;');
      ShowHTML('     }');
      ShowHTML('  }');
      if(nvl($w_sq_acordo_aditivo,'')=='') {      
        ShowHTML('  if (theForm.w_valor_parcela[2].checked || theForm.w_valor_parcela[3].checked) {');
        ShowHTML('     if (theForm.w_valor_diferente.value == \'\') {');
        ShowHTML('        alert(\'Voc� deve informar o valor para a parcela diferente das demais!\');');
        ShowHTML('        theForm.w_valor_diferente.focus();');
        ShowHTML('        return false;');
        ShowHTML('     }');
        ShowHTML('  }');
      }
      Validate('w_observacao','Observa��o','1','','3','200','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' || $O=='A') {
    BodyOpenClean('onLoad=\'document.Form.w_ordem.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if (nvl($w_sq_acordo_aditivo,'')!='') {
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>');
    ShowHTML('  '.f($RS_Solic,'nome').': '.f($RS_Solic,'codigo_interno').' - '.f($RS_Solic,'titulo'));
    ShowHTML('  <br>Vig�ncia: '.formataDataEdicao(f($RS_Solic,'inicio')).' a '.formataDataEdicao(f($RS_Solic,'fim')));
    ShowHTML('  '.$w_texto.'</b></font></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  }
  ShowHTML('<center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="97%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr valign="top"><td>');
    if($w_edita) {
      if (nvl($w_sq_acordo_aditivo,'')=='' || w_prorrogacao=='N') {
        ShowHTML('        <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      }
      if($w_prorrogacao=='N') {
        ShowHTML('        <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>G</u>erar</a>&nbsp;');
      } else {
        ShowHTML('        <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=G&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>G</u>erar</a>&nbsp;');
      }
    }
    if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('                         <a accesskey="F" class="ss" href="javascript:window.close(); opener.location.reload(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=2>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if (nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('          <td><b>Ordem</font></td>');
      ShowHTML('          <td><b>Per�odo</font></td>');
      ShowHTML('          <td><b>Vencimento</font></td>');
      ShowHTML('          <td><b>Valor</font></td>');
    } else {
      ShowHTML('          <td rowspan=2><b>Ordem</font></td>');
      ShowHTML('          <td rowspan=2><b>Per�odo</font></td>');
      ShowHTML('          <td rowspan=2><b>Vencimento</font></td>');
      ShowHTML('          <td colspan=4><b>Valor</font></td>');
    } 
    ShowHTML('          <td><b>Observa��o</font></td>');
    ShowHTML('          <td class="remover"><b>Opera��es</font></td>');
    ShowHTML('        </tr>');
    if (nvl($w_sq_acordo_aditivo,'')>'') {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>Inicial</font></td>');
      ShowHTML('          <td><b>Acres./Supr.</font></td>');
      ShowHTML('          <td><b>Reajuste</font></td>');
      ShowHTML('          <td><b>Total</font></td>');
      ShowHTML('          <td colspan=2><b>&nbsp;</font></td>');
      ShowHTML('        </tr>');
    }
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_total   = 0;
      $w_total_i = 0;
      $w_total_e = 0;
      $w_total_r = 0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>');
        if (nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">');
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } else {
          if (f($row,'quitacao')<f($row,'vencimento')) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } 
        ShowHTML('        '.f($row,'ordem').'</td>');
        if(nvl(f($row,'inicio'),'')!='') ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).' a '.FormataDataEdicao(f($row,'fim')).'</td>');
        else                             ShowHTML('        <td align="center">---</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        if (nvl($w_sq_acordo_aditivo,'')>'') {
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_inicial'),2,',','.').'&nbsp;&nbsp;</td>');
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_excedente'),2,',','.').'&nbsp;&nbsp;</td>');
          ShowHTML('        <td align="right">'.number_format(f($row,'valor_reajuste'),2,',','.').'&nbsp;&nbsp;</td>');
        } 
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>');
        ShowHTML('        <td>'.crlf2br(nvl(f($row,'observacao'),'---')).'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if(nvl(f($row,'quitacao'),'')!='') {
          ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert(\'Parcelas pagas n�o podem ser alteradas.\')";>AL</A>&nbsp');
          ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert(\'Parcelas pagas n�o podem ser exclu�das.\')";>EX</A>&nbsp');
        } else {
          if(nvl(f($row,'sq_acordo_aditivo'),'')!='' && $P1==1) {
            ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="alert(\'Parcelas ligada a aditivo!\nUse a opera��o parcelas do aditivo.\')";>AL</A>&nbsp');
          } else {
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_parcela').'&w_sq_acordo_aditivo='.f($row,'sq_acordo_aditivo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
          }
          if($w_edita) {
            ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_acordo_parcela').'&w_sq_acordo_aditivo='.f($row,'sq_acordo_aditivo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclus�o do registro?\');">EX</A>&nbsp');
          }
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        if(f($row,'prorrogacao')=='N' && (f($row,'acrescimo')=='S'||f($row,'supressao')=='S')) {
          $w_total += f($row,'valor_excedente');
        } else {
          $w_total   += f($row,'valor');
          $w_total_i += f($row,'valor_inicial');
          $w_total_e += f($row,'valor_excedente');
          $w_total_r += f($row,'valor_reajuste');
        }
      } 
    } 
    if ($w_total>0) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('        <td align="center" colspan=3><b>Total</b></td>');
      if (nvl($w_sq_acordo_aditivo,'')>'') {
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_i).'</b>&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_e).'</b>&nbsp;&nbsp;</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber($w_total_r).'</b>&nbsp;&nbsp;</td>');
      }
      ShowHTML('        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>');
      ShowHTML('        <td colspan=3>');
      if (round($w_valor_acordo-$w_total,2)!=0) {
        ShowHTML('<b>O valor das parcelas difere do valor contratado ('.formatNumber(round($w_valor_acordo-$w_total,2)).')</b></td>');
      } else {
        ShowHTML('        &nbsp;</td>');
      }  
      ShowHTML('      </tr>');
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAE',$O)!==false) {
    if(!$w_edita) {
       $w_Disabled=' READONLY ';
    } else {  
      if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
    }
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao($w_inicio).'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.FormataDataEdicao($w_fim).'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>ATEN��O</b>: a data de vencimento deve estar contida dentro da vig�ncia, de <b>'.FormataDataEdicao($w_inicio).'</b> e <b>'.FormataDataEdicao($w_fim).'</b>.<br>&nbsp;</td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('          <td><b>N�mero de <u>o</u>rdem da parcela:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_ordem" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_ordem.'" title="Informe o n�mero de ordem da parcela, que indica a seq��ncia de pagamento."></td>');
    ShowHTML('          <td><b><u>D</u>ata de vencimento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de vencimento da parcela.">'.ExibeCalendario('Form','w_data').'</td>');
    if(nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('          <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da parcela."></td>');
    } else {
      if(f($RS_Adit,'prorrogacao')=='S') {
        ShowHTML('          <td><b><u>V</u>alor inicial:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_inicial" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_inicial.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor inicial da parcela."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_inicial" value="'.$w_valor_inicial.'">');
      }
      if(f($RS_Adit,'acrescimo')=='S'||f($RS_Adit,'supressao')=='S') {
        ShowHTML('          <td><b><u>V</u>alor excedente:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_excedente" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_excedente.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor de excedente da parcela."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_excedente" value="'.$w_valor_excedente.'">');
      }
      if(f($RS_Adit,'revisao')=='S') {
        ShowHTML('          <td><b><u>V</u>alor reajuste:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_reajuste" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_reajuste.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor de reajuste da parcela."></td>');
      } else {
        ShowHTML('<INPUT type="hidden" name="w_valor_reajuste" value="'.$w_valor_reajuste.'">');
      }
    }
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><b><u>P</u>er�odo de realiza��o:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_per_ini" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_per_ini.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de in�cio do periodo de realiza��o da parcela.">'.ExibeCalendario('Form','w_per_ini').' a '.'<input '.$w_Disabled.' accesskey="P" type="text" name="w_per_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_per_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de fim do periodo de realiza��o da parcela.">'.ExibeCalendario('Form','w_per_fim').'</td>');
    if(!$w_edita) {
      $w_Disabled=' ';
    }
    ShowHTML('      <tr><td colspan=4><b>Obse<u>r</u>va��es:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_observacao" class="sti" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='G') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio" value="'.FormataDataEdicao($w_inicio).'">');
    ShowHTML('<INPUT type="hidden" name="w_fim" value="'.FormataDataEdicao($w_fim).'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><font size="2"><b>ATEN��O</b>: as parcelas existentes, se existirem, ser�o exclu�das.<br>&nbsp;</td>');
    ShowHTML('      <tr><td><b>Dados:</b><ul>');
    ShowHTML('              <li>Vig�ncia: <b>'.FormataDataEdicao($w_inicio).'</b> a <b>'.FormataDataEdicao($w_fim).'</b>');
    ShowHTML('              <li>Valor: <b>'.formatNumber($w_valor_acordo).'</b>');
    ShowHTML('              </ul>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr valign="top"><td colspan=2><b>Dados necess�rios � gera��o de parcelas �nicas:</b>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=11 onClick="trataUnica();"><td>Gerar uma �nica parcela, paga no in�cio da vig�ncia</td>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=12 onClick="trataUnica();"><td>Gerar uma �nica parcela, paga no fim da vig�ncia</td>');
    ShowHTML('          <tr valign="top"><td colspan=2><b>Dados necess�rios � gera��o de parcelas mensais:</b>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=21 onClick="trataUnica();"><td>Gerar parcelas mensais, a cada trinta dias ap�s o in�cio da vig�ncia</td>');
    ShowHTML('          <tr valign="top"><td><input '.$w_Disabled.' type="radio" name="w_tipo_geracao" value=22 onClick="trataUnica();"><td>Gerar parcelas mensais, a cada trinta dias a partir do in�cio da vig�ncia</td>');
    ShowHTML('          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('              <tr valign="top"><td colspan=3><b>Dia de vencimento das parcelas:</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="P" onClick="trataVencimento();"><td>Sempre no primeiro dia do m�s</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="U" onClick="trataVencimento();"><td>Sempre no �ltimo dia do m�s</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_vencimento" value="D" onClick="trataVencimento();"><td>Sempre no dia <input '.$w_Disabled.' type="text" name="w_dia_vencimento" class="sti" SIZE="2" MAXLENGTH="2" VALUE="" onKeyDown="trataDiaVencimento();" title="Informe o dia de vencimento da parcela."> de cada m�s.</td>');
    ShowHTML('              </table>');
    ShowHTML('          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>');
    ShowHTML('              <tr valign="top"><td colspan=3><b>Valores das parcelas:</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="I" onClick="trataValor();"><td>As parcelas t�m valores iguais</td>');
    ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="C" onClick="trataValor();"><td>Primeira e �ltima parcelas proporcionais aos dias</td>');
    if(nvl($w_sq_acordo_aditivo,'')=='') {
      ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="P" onClick="trataValor();"><td>A primeira parcela tem valor diferente das demais</td>');
      ShowHTML('              <tr valign="top"><td><td><input '.$w_Disabled.' type="radio" name="w_valor_parcela" value="U" onClick="trataValor();"><td>A �ltima parcela tem valor diferente das demais</td>');
      ShowHTML('              <tr valign="top"><td colspan=2><td><b>Valor da parcela diferente das demais:</b> <input '.$w_Disabled.' type="text" name="w_valor_diferente" class="sti" SIZE="18" MAXLENGTH="18" style="text-align:right;" onKeyDown="FormataValor(this, 18, 2, event); trataValorDiferente();" VALUE="" title="Informe o valor da primeira parcela. As demais ter�o valores iguais."></td>');
    }
    ShowHTML('              </table>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan=4><b>Obse<u>r</u>va��es gerais a serem gravadas em todas as parcelas:</b><br><textarea '.$w_Disabled.' accesskey="R" name="w_observacao" class="sti" ROWS=5 cols=75 >'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gerar">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='V') {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_aditivo" value="'.$w_sq_acordo_aditivo.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_inicio[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_fim[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela[]" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'" align="center"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b>Dados:</b><ul>');
    ShowHTML('              <li>Vig�ncia: <b>'.FormataDataEdicao($w_inicio).'</b> a <b>'.FormataDataEdicao($w_fim).'</b>');
    ShowHTML('              <li>Valor: <b>'.formatNumber($w_valor_acordo).'</b>');
    ShowHTML('              </ul>');
    $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,'GCDCAD');
    $sql = new db_getAcordoParcela; $RS_Parc = $sql->getInstanceOf($dbms,$w_chave,null,'PERIODO',null,FormataDataEdicao($w_inicio),FormataDataEdicao($w_fim),null,null,null,null);
    $RS_Parc = SortArray($RS_Parc,'ordem','asc');
    ShowHTML('  <tr><td colspan="2">');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td rowspan=2><b>&nbsp;</b></td>');
    ShowHTML('            <td rowspan=2><b>N�</b></td>');
    ShowHTML('            <td rowspan=2><b>Per�odo</b></td>');
    ShowHTML('            <td rowspan=2><b>Venc.</b></td>');
    ShowHTML('            <td colspan=4><b>Valores</b></td>');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    ShowHTML('            <td><b>Inicial</b></td>');
    ShowHTML('            <td><b>Reajuste</b></td>');
    ShowHTML('            <td><b>Acr.Supr.</b></td>');
    ShowHTML('            <td><b>Total</b></td>');
    if (count($RS_Parc)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      if(nvl($w_sq_acordo_aditivo,'')>'') ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do aditivo.</b></font></td></tr>');
      else                                ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="2"><b>Cadastre antes as parcelas do contrato.</b></font></td></tr>');
    } else {
      foreach($RS_Parc as $row) {
        $w_cont+= 1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td width="1%" nowrap align="center"><input type="checkbox" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'" DISABLED CHECKED>');
        ShowHTML('<INPUT type="hidden" name="w_sq_acordo_parcela[]" value="'.f($row,'sq_acordo_parcela').'">');
        ShowHTML('<INPUT type="hidden" name="w_inicio[]" value="'.FormataDataEdicao(f($row,'inicio')).'">');
        ShowHTML('<INPUT type="hidden" name="w_fim[]" value="'.FormataDataEdicao(f($row,'fim')).'">');
        ShowHTML('        <td>');
        if (nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1)) {
            ShowHTML('           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">');
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            ShowHTML('           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } else {
          if (f($row,'quitacao')<f($row,'vencimento')) {
            ShowHTML('           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">');
          } else {
            ShowHTML('           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">');
          } 
        } 
        ShowHTML('        '.substr(1000+f($row,'ordem'),1,3).'</td>');
        if(nvl(f($row,'inicio'),'')!='') ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio')).' a '.FormataDataEdicao(f($row,'fim')).'</td>');
        else                             ShowHTML('        <td align="center">---</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'vencimento')).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_inicial'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_reajuste'),2).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor_excedente'),2).'</td>');
        ShowHTML('        <td align="right"><b>'.formatNumber(f($row,'valor'),2).'</b></td>');
        ShowHTML('      </tr>');
      } 
    }     
    ShowHTML('    </table>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('        <input class="stb" type="submit" name="Botao" value="Gerar">');
    ShowHTML('        <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_sq_acordo_aditivo='.$w_sq_acordo_aditivo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('      </td>');
    ShowHTML('  </tr>');
    ShowHTML('</table>');
    ShowHTML('</TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    //ShowHTML ' history.back(1);'
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
 
// ------------------------------------------------------------------------- 
// Rotina de anexos 
// ------------------------------------------------------------------------- 
function Anexo() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_troca      = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina 
    $w_nome      = $_REQUEST['w_nome'];
    $w_descricao = $_REQUEST['w_descricao'];
    $w_caminho   = $_REQUEST['w_caminho'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endere�o informado 
    $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,$w_cliente);
    foreach ($RS as $row) {
      $w_nome      = f($row,'nome');
      $w_descricao = f($row,'descricao');
      $w_caminho   = f($row,'chave_aux');
      break;
    }
  } 
  Cabecalho();
  head();
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
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
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled=' DISABLED ';
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
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b></font>.</td>');
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
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L').'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    //ShowHTML ' history.go(-1);' 
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
  global $w_Disabled;
  global $w_TP;

  $w_chave = $_REQUEST['w_chave'];
  $w_tipo  = upper(trim($_REQUEST['w_tipo']));

  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\';');
    $w_embed = 'WORD';
  } elseif ($w_tipo=='PDF') {
    headerpdf('Visualiza��o de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean('onLoad=\'this.focus()\';');
    $w_embed = 'HTML';
  } 
  if ($w_embed!='WORD') CabecalhoRelatorio($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),4,$w_chave);
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><FONT SIZE=1>Clique <a class="HL" href="javascript:history.go(-1);">aqui</a> para voltar � tela anterior</b></center>');
  } 
  // Chama a rotina de visualiza��o dos dados da atividade, na op��o 'Listagem'
  ShowHTML(VisualConvenio($w_chave,'L',$w_usuario,'4',$P4));
  if ($w_embed!='WORD') {
    ShowHTML('<center><B><FONT SIZE=1>Clique <a class="HL" href="javascript:history.go(-1);">aqui</a> para voltar � tela anterior</b></center>');
  } 
  if ($w_tipo=='PDF') RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
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
  if ($w_troca>'') {
    $w_observacao=$_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    if ($P1!=1) {
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
    BodyOpenClean('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados, na op��o 'Listagem'
  ShowHTML(VisualConvenio($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST', 'return(Validacao(this));', null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'GERAL',$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
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
  $w_erro       = '';
  $w_tramite = $_REQUEST['w_tramite'];
  
  if ($w_troca > '') {
    // Se for recarga da p�gina
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_novo_tramite = $_REQUEST['w_novo_tramite'];
    $w_despacho = $_REQUEST['w_despacho'];
  } else {
    $sql = new db_getSolicData;
    $RS = $sql->getInstanceOf($dbms, $w_chave, substr($SG, 0, 3) . 'GERAL');
    if (f($RS, 'sg_tramite') == 'CI') {
      $w_tramite = f($RS, 'sq_siw_tramite');
    }
    $w_novo_tramite = f($RS, 'sq_siw_tramite');
  } 
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');
  if ($w_ativo == 'N') {
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) {
      $w_novo_tramite = f($row,'sq_siw_tramite');
      $w_sg_tramite   = f($row,'sigla');
      break;
    }   
  }
  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaConvenio($w_cliente,$w_chave,substr($SG,0,3).'GERAL',null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinat�rio','HIDDEN','1','1','10','','1');
    Validate('w_despacho','Despacho','','1','1','2000','1','1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center" colspan=2>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualConvenio($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" colspan=6>');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="6"><table border=0 width="96%" cellspacing=0><tr valign="top">');
  if ($P1!=1) {
    // Se n�o for cadastramento
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja envi�-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja envi�-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 
    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
    if ($w_sg_tramite=='CI') {
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione, na rela��o, um destinat�rio.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o projeto na rela��o.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
    } 
  } else {
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja envi�-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja envi�-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 
    SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o projeto na rela��o.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
  } 
  ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a a��o esperada pelo destinat�rio.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
  if ($P1!=1) {
    // Se n�o for cadastramento, olta para a listagem
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
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
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o','','1','1','2000','1','1');
    Validate('w_caminho','Arquivo','','','5','255','1','1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_observacao.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do contrato, na op��o 'Listagem'
  ShowHTML(VisualConvenio($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'ENVIO&O='.$O.'&w_menu='.$w_menu.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.(f($RS,'upload_maximo')/1024).' KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
  ShowHTML('      <tr><td><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_tipo_conc  = Nvl($_REQUEST['w_tipo_conc'],-1);
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_inicio_real      = $_REQUEST['w_inicio_real'];
    $w_fim_real         = $_REQUEST['w_fim_real'];
    $w_nota_conclusao   = $_REQUEST['w_nota_conclusao'];
    $w_custo_real       = $_REQUEST['w_custo_real'];
  } 
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_inicio_real    = f($RS,'inicio');
  $w_fim_real       = f($RS,'fim');
  $w_custo_real     = number_format(f($RS,'valor'),2,',','.');
  $w_duracao        = f($RS,'duracao');
  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaConvenio($w_cliente,$w_chave,substr($SG,0,3).'GERAL',null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    Validate('w_tipo_conc','Tipo da conclus�o','SELECT',1,1,1,'','1');
    if ($w_tipo_conc==2) {
      Validate('w_fim_real','Data da rescis�o','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio_real','In�cio da execu��o','<=','w_fim_real','T�rmino da execu��o');
      Validate('w_nota_conclusao','Motivo da rescis�o','','1','1','2000','1','1');
    } else {
      Validate('w_nota_conclusao','Observa��o','','','1','2000','1','1');
    } 
    if ($w_tipo_conc==0) {
      Validate('w_inicio','In�cio da vig�ncia','DATA',1,10,10,'','0123456789/');
      Validate('w_fim','T�rmino da vig�ncia','DATA',1,10,10,'','0123456789/');
      CompData('w_inicio','In�cio da vig�ncia','<=','w_fim','T�rmino da vig�ncia');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
    } 
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_tipo_conc==2) {
    BodyOpenClean('onLoad=\'document.Form.w_fim_real.focus()\';');
  } elseif ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_tipo_conc.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do contrato, na op��o 'Listagem'
  ShowHTML(VisualConvenio($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'CONC',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_concluida" value="S">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<INPUT type="hidden" name="w_inicio_real" value="'.FormataDataEdicao($w_inicio_real).'">');
  ShowHTML('<INPUT type="hidden" name="w_custo_real" value="'.$w_custo_real.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
  ShowHTML('          <tr valign="top">');
  SelecaoTipoConclusao('Tip<u>o</u>:','O','Selecione o tipo de conclus�o.',$w_tipo_conc,'w_tipo_conc',$w_menu,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nota_conclusao\'; document.Form.submit();"');
  if ($w_tipo_conc==2) {
    ShowHTML('              <td><b>Da<u>t</u>a da rescis�o:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim_real" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim_real).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data da rescis�o.">'.ExibeCalendario('Form','w_fim_real').'</td>');
  } else {
    ShowHTML('<INPUT type="hidden" name="w_fim_real" value="'.FormataDataEdicao($w_fim_real).'">');
  } 
  ShowHTML('          </table>');
  if ($w_tipo_conc==2) {
    ShowHTML('      <tr><td><b>Motivo da r<u>e</u>scis�o:</b><br>');
  } else {
    ShowHTML('      <tr><td><b>Obs<u>e</u>rva��o:</b><br>');
  } 
  ShowHTML('          <textarea '.$w_Disabled.' accesskey="E" name="w_nota_conclusao" class="sti" ROWS=5 cols=75 title="Se desejar, insira observa��es que julgar relevantes.">'.$w_nota_conclusao.'</TEXTAREA></td>');
  if ($w_tipo_conc==0) {
    $w_inicio   = addDays($w_fim_real,1);
    $w_fim      = FormataDataEdicao(addDays($w_inicio,$w_duracao));
    $w_inicio   = FormataDataEdicao($w_inicio);
    $w_valor    = $w_custo_real;
    ShowHTML('      <tr><td align="center" bgcolor="'.$conTrBgColor.'" style="border: 1px solid rgb(0,0,0);"><b>Dados para a renova��o</font></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr valign="top">');
    ShowHTML('              <td><b><u>I</u>n�cio da vig�ncia:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe o novo in�cio da vig�ncia."></td>');
    ShowHTML('              <td><b><u>T</u>�rmino da vig�ncia:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe o novo termino da vig�ncia."></td>');
    ShowHTML('              <td><b>Valo<u>r</u>:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" TITLE="Informe o novo valor total real ou estimado."></td>');
    ShowHTML('          </table>');
  } 

  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Concluir">');
  if ($P1!=1) {
    // Se n�o for cadastramento, volta para a listagem
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de prepara��o para envio de e-mail relativo a contratos
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms,$p_solic,substr(f($RS_Menu,'sigla'),0,3).'GERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    // Recupera os dados da tarefa
    $w_html = '<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1)       $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUS�O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==2)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITA��O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    elseif ($p_tipo==3)   $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUS�O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td><b><font size=2 color="#BC3131">ATEN��O: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</font></b><br><br><td></tr>'.$crlf;
    $w_nome = f($RSM,'nome').' '.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')';
    $w_html.=$crlf.'<tr><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>'.f($RSM,'nome').': '.f($RSM,'codigo_interno').' '.CRLF2BR(f($RSM,'objeto')).' ('.f($RSM,'sq_siw_solicitacao').')</b></font></div></td></tr>';
    $w_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identifica��o do convenio
    $w_html.=$crlf.'      <tr><td width="30%"><td>';
    if (nvl(f($RSM,'nm_projeto'),'')>'') {
      $w_html.=$crlf.'      <tr><td>Projeto: <br><b>'.f($RSM,'nm_projeto').'  ('.f($RSM,'sq_solic_pai').')</b></td>';
    } 
    // Se a classifica��o foi informada, exibe.
    if (nvl(f($RSM,'sq_cc'),'')>'') {
      $w_html.=$crlf.'      <tr><td><b>Classifica��o:</b></td>';
      $w_html.=$crlf.'        <td>'.f($RSM,'nm_cc').' </b></td>';
    } 
    $w_html.=$crlf.'        <tr><td><b>Respons�vel pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_solic').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>Unidade respons�vel pelo monitoramento:</b></td>';
    $w_html.=$crlf.'          <td>'.f($RSM,'nm_unidade_resp').'</td></tr>';
    $w_html.=$crlf.'        <tr><td><b>In�cio vig�ncia:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'inicio')).' </td></tr>';
    $w_html.=$crlf.'        <tr><td><b>T�rmino vig�ncia:</b></td>';
    $w_html.=$crlf.'          <td>'.FormataDataEdicao(f($RSM,'fim')).' </td></tr>';
    // Outra parte
    $sql = new db_getBenef; $RSM1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RSM,'outra_parte'),0),null,null,null,null,Nvl(f($RSM,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null, null, null, null, null);
    if (count($RSM1)>0) {
      foreach($RSM1 as $row) {
        $w_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'.$crlf;
        $w_html.=$crlf.'      <tr><td colspan=2>';
        $w_html.=$crlf.'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        if (Nvl(f($RSM,'sq_tipo_pessoa'),0)==1) {
          $w_html.=$crlf.'          - '.f($row,'cpf');
        } else {
          $w_html.=$crlf.'          - '.f($row,'cnpj');
        } 
      }
    } 
    $w_html.=$crlf.'</tr>';
    //Recupera o �ltimo log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
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
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
      $w_destinatarios = f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html .= $crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INFORMA��ES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'.$crlf;
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
    $w_html .='      <tr valign="top"><td colspan="2">';
    $w_html .='         Para acessar o sistema use o endere�o: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html .='      </td></tr>'.$crlf;
    $w_html .= '      <tr valign="top"><td colspan="2">'.$crlf;
    $w_html .='         Dados da ocorr�ncia:<br>'.$crlf;
    $w_html .='         <ul>'.$crlf;
    $w_html .='         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html .='         <li>Data: <b>'.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html .='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html .='         </ul>'.$crlf;
    $w_html .='      </td></tr>'.$crlf;
    $w_html .='    </table>'.$crlf;
    $w_html .='</td></tr>'.$crlf;
    $w_html .='</table>'.$crlf;
    $w_html .='</BODY>'.$crlf;
    $w_html .='</HTML>'.$crlf;
    if(f($RSM,'st_sol')=='S') {
      // Recupera o e-mail do respons�vel
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RSM,'solicitante'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    }
    // Recupera o e-mail do titular e do substituto pelo setor respons�vel
    $sql = new db_getUorgResp; $RS = $sql->getInstanceOf($dbms,f($RSM,'sq_unidade'));
    foreach($RS as $row){$RS=$row; break;}
    if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
    if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
    // Prepara os dados necess�rios ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclus�o ou Conclus�o
      if ($p_tipo==1) $w_assunto='Inclus�o - '.$w_nome; else $w_assunto='Conclus�o - '.$w_nome;
    } elseif ($p_tipo==2) {
      // Tramita��o
      $w_assunto='Tramita��o - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado=EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
      ScriptClose();
    } 
  } 
}
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file    = '';
  $w_tamanho = '';
  $w_tipo    = '';
  $w_nome    = '';
  Cabecalho();
  head();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=this.focus();');
  if (strpos($SG,'GERAL')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      // Se for opera��o de exclus�o, verifica se � necess�rio excluir os arquivos f�sicos
      if ($O=='E' && f($RS_Menu,'cancela_sem_tramite')=='N') {
        $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null,null,'LISTA');
        // Mais de um registro de log significa que deve ser cancelada, e n�o exclu�da.
        // Nessa situa��o, n�o � necess�rio excluir os arquivos.
        if (count($RS)<=1) {
          $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],null,$w_cliente);
          foreach($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          } 
        } 
      } 
      $SQL = new dml_putAcordoGeral; $SQL->getInstanceOf($dbms,$O, $w_cliente, 
          $_REQUEST['w_chave'], $_REQUEST['w_menu'], $_REQUEST['w_sq_unidade_resp'], $_REQUEST['w_solicitante'], 
          $_SESSION['SQ_PESSOA'], $_REQUEST['w_sqcc'], $_REQUEST['w_descricao'], $_REQUEST['w_justificativa'], 
          $_REQUEST['w_inicio'], $_REQUEST['w_fim'], nvl($_REQUEST['w_valor'],0), $_REQUEST['w_data_hora'], 
          $_REQUEST['w_aviso'], $_REQUEST['w_dias'], $_REQUEST['w_cidade'],  $_REQUEST['w_chave_pai'], 
          $_REQUEST['w_sq_tipo_acordo'], $_REQUEST['w_objeto'], $_REQUEST['w_sq_tipo_pessoa'], 
          $_REQUEST['w_sq_forma_pagamento'], $_REQUEST['w_forma_atual'], $_REQUEST['w_inicio_atual'], $_REQUEST['w_etapa'],
          $_REQUEST['w_codigo_interno'],$_REQUEST['w_titulo'], $_REQUEST['w_numero_empenho'], $_REQUEST['w_numero_processo'], 
          $_REQUEST['w_data_assinatura'], $_REQUEST['w_data_publicacao'], $_REQUEST['w_moeda'],
          $w_chave_nova, $w_copia, null, $_REQUEST['w_pessoa'], $w_codigo);
      if ($O=='I') {
        // Recupera os dados para montagem correta do menu
        $sql = new db_getMenuData; $RS1 = $sql->getInstanceOf($dbms,$w_menu);
        ScriptOpen('JavaScript');
        ShowHTML('  parent.menu.location=\''.montaURL_JS(null,$conRootSIW.'menu.php?par=ExibeDocs&O=A&w_chave='.$w_chave_nova.'&w_menu='.$w_menu.'&w_documento='.$w_codigo.'&R='.$R.'&SG='.f($RS1,'sigla').'&TP='.RemoveTP($TP)).'\';');
      } elseif ($O=='E') {
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&w_menu='.$w_menu.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      } else {
        // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
        $sql = new db_getLinkData; $RS1 = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS1,'link').'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      } 
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'TERMO')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putAcordoTermo; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],
        $_REQUEST['w_atividades'],$_REQUEST['w_produtos'],$_REQUEST['w_requisitos'],
        $_REQUEST['w_codigo_externo'],$_REQUEST['w_vincula_projeto'],
        $_REQUEST['w_vincula_demanda'],$_REQUEST['w_vincula_viagem'],$_REQUEST['w_prestacao_contas']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'PARC')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putAcordoParc;
      if($O=='V') {
        for ($i=0; $i<=count($_POST['w_sq_acordo_parcela'])-1; $i=$i+1) {
          if (Nvl($_REQUEST['w_sq_acordo_parcela'][$i],'')>'') {
            $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_acordo_parcela'][$i],
              $_REQUEST['w_sq_acordo_aditivo'], null,null,null,null,null,null,null,
              null,null,null,$_REQUEST['w_inicio'][$i],$_REQUEST['w_fim'][$i],null,null,null);
           }
        }
      } else {
        $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'], 
          $_REQUEST['w_sq_acordo_aditivo'], $_REQUEST['w_ordem'],$_REQUEST['w_data'],$_REQUEST['w_valor'],
          $_REQUEST['w_observacao'], $_REQUEST['w_tipo_geracao'],$_REQUEST['w_tipo_mes'],
          $_REQUEST['w_vencimento'], $_REQUEST['w_dia_vencimento'], $_REQUEST['w_valor_parcela'],
          $_REQUEST['w_valor_diferente'], $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_valor_inicial'],
          $_REQUEST['w_valor_excedente'],$_REQUEST['w_valor_reajuste']);
      }
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&w_sq_acordo_aditivo='.$_REQUEST['w_sq_acordo_aditivo'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'OUTRA')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if ($O=='I'){
        $sql = new db_getConvOutraParte; $RS = $sql->getInstanceOf($dbms,null,$_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],null);
        foreach ($RS as $row) {
          if (f($row,'outra_parte')==Nvl($_REQUEST['w_sq_pessoa'],'-1')) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("ATEN��O: Outra parte j� cadastrada no conv�nio!");');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
            ScriptClose();
            exit();
          }  
        }
      }
      $SQL = new dml_putConvOutraParte; $SQL->getInstanceOf($dbms,$_REQUEST['O'],$SG,$_REQUEST['w_sq_acordo_outra_parte'],
        $_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa'],
        $_REQUEST['w_tipo'],$_REQUEST['w_chave_aux'],
        $_REQUEST['w_cpf'],$_REQUEST['w_cnpj'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
        $_REQUEST['w_sexo'],$_REQUEST['w_nascimento'],$_REQUEST['w_rg_numero'],$_REQUEST['w_rg_emissao'],
        $_REQUEST['w_rg_emissor'],$_REQUEST['w_passaporte'],$_REQUEST['w_sq_pais_passaporte'],        
        $_REQUEST['w_inscricao_estadual'],$_REQUEST['w_logradouro'],
        $_REQUEST['w_complemento'],$_REQUEST['w_bairro'],$_REQUEST['w_sq_cidade'],
        $_REQUEST['w_cep'],$_REQUEST['w_ddd'],$_REQUEST['w_nr_telefone'],
        $_REQUEST['w_nr_fax'],$_REQUEST['w_nr_celular'],$_REQUEST['w_email'],
        $_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],$_REQUEST['w_nr_conta'],
        $_REQUEST['w_sq_pais_estrang'],$_REQUEST['w_aba_code'],$_REQUEST['w_swift_code'],
        $_REQUEST['w_endereco_estrang'],$_REQUEST['w_banco_estrang'],$_REQUEST['w_agencia_estrang'],
        $_REQUEST['w_cidade_estrang'],$_REQUEST['w_informacoes'],$_REQUEST['w_codigo_deposito'],
        $_REQUEST['w_pessoa_atual']);        
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos(substr($SG,3),'DADOS')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $SQL = new dml_putConvDadosBancario; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],
        $_REQUEST['w_sq_banco'],$_REQUEST['w_sq_agencia'],$_REQUEST['w_operacao'],
        $_REQUEST['w_nr_conta']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }    
  } elseif (strpos($SG,'ANEXO')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      if (UPLOAD_ERR_OK===0) {
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
          $w_tamanho = $Field['size'];
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
              $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
              foreach ($RS as $row) {
                if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
                if (strpos(f($row,'caminho'),'.')!==false) {
                  $w_file = substr(basename(f($row,'caminho')),0,(strpos(basename(f($row,'caminho')),'.') ? strpos(basename(f($row,'caminho')),'.')+1 : 0)-1).substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,30);
                } else {
                  $w_file = basename(f($row,'caminho'));
                }
              }
            } else {
              $w_file = str_replace('.tmp','',basename($Field['tmp_name']));
              if (strpos($Field['name'],'.')!==false) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
            } 
            $w_tipo    = $Field['type'];
            $w_nome    = $Field['name'];
            if ($w_file>'') {
              move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            }
          } elseif(nvl($Field['name'],'')!='') {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Aten��o: o tamanho do arquivo deve ser maior que 0 KBytes!\');');
            ScriptClose();
            retornaFormulario('w_caminho');
            exit();
          }
        } 
        // Se for exclus�o e houver um arquivo f�sico, deve remover o arquivo do disco.  
        if ($O=='E' && $_REQUEST['w_atual']>'') {
          $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_atual'],$w_cliente);
          foreach ($RS as $row) {
            if (file_exists($conFilePhysical.$w_cliente.'/'.f($row,'caminho'))) unlink($conFilePhysical.$w_cliente.'/'.f($row,'caminho'));
          }
        } 
        $SQL = new dml_putSolicArquivo; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],$w_file,$w_tamanho,$w_tipo,$w_nome, $w_chave_arquivo);
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
        ScriptClose();
        exit();
      } 
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG,'ENVIO')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ((false!==(strpos(upper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(upper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
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
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          $SQL = new dml_putAcordoEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_tipo_log'],$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualConvenio($_REQUEST['w_chave'],'L',$w_usuario,'4','1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }   
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], substr($SG, 0, 3) . 'GERAL');
        if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� fez o encaminhamento para outra fase!\');');
          exit();
          ScriptClose();
        } else {
          $SQL = new dml_putAcordoEnvio;
          $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                  $_REQUEST['w_novo_tramite'], 'N', $_REQUEST['w_tipo_log'], $_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],
                  null, null, null, null);
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData;
            $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS, 'sigla');
            if ($w_sg_tramite == 'CI') {
              $w_html = VisualConvenio($_REQUEST['w_chave'], 'L', $w_usuario, '4', '1');
              CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
            }
          }
          // Envia e-mail comunicando a inclus�o
          SolicMail($_REQUEST['w_chave'], 2);
          // Se for envio da fase de cadastramento, remonta o menu principal
          if ($P1 == 1) {
            // Recupera os dados para montagem correta do menu
            $sql = new db_getMenuData;
            $RS = $sql->getInstanceOf($dbms, $w_menu);
            ScriptOpen('JavaScript');
            ShowHTML('  parent.menu.location=\'' . montaURL_JS(null, $conRootSIW . 'menu.php?par=ExibeDocs&O=L&R=' . $R . '&SG=' . f($RS, 'sigla') . '&TP=' . RemoveTP(RemoveTP($TP)) . MontaFiltro('GET')) . '\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
            ScriptClose();
          }
        }
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (strpos($SG, 'LOTE')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
      ShowHTML('<b>Resultado do envio:</b>');
      for ($i = 1; $i < count($_POST['w_chave']); $i++) {
        if (Nvl($_POST['w_chave'][$i], '') > '') {
          $w_tramite = $_POST['w_tramite'][$_POST['w_chave'][$i]];
          $w_chave   = $_POST['w_chave'][$i];
          $w_codigo  = $_POST['w_lista'][$_POST['w_chave'][$i]];

          // Recupera dados do tr�mite atual
          $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
          $w_sg_tramite = f($RS,'sigla');
          $w_nm_tramite = f($RS,'nome');
          
          ShowHTML('<table border="1" width="100%"><tr valign="top"><td width="15%"><b>'.$w_codigo.'</b></td>');
          if ($_POST['w_envio']=='N') {

            if ($w_sg_tramite=='EE') {
              // Se n�o h� fase posterior, n�o pode haver envio.
              echo '<td>Fase atual ja � a �ltima.</td>';
            } else {
              //Verifica a fase imediatamente anterior � atual.
              $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'PROXIMO',null);
              $w_novo_tramite = null;
              foreach($RS as $row) {
                $w_novo_tramite = f($row,'sq_siw_tramite');
                break;
              }
              if (nvl($w_novo_tramite,'')=='') {
                echo '<td>N�o h� fase posterior � atual ("'.$w_nm_tramite.'").</td>';
              } else {
                // Verifica se a solicita��o atende �s exig�ncias para envio
                $w_erro = ValidaConvenio($w_cliente,$w_chave,$_POST['p_agrega'],null,null,null,$w_tramite);
                if (substr(Nvl($w_erro,'nulo'),0,1)=='0') {
                  echo '<td>'.substr($w_erro,1).'</td>';
                } else {
                  // Envia a solicita��o
                  $SQL = new dml_putAcordoEnvio;
                  $SQL->getInstanceOf($dbms, $_POST['w_menu'], $w_chave, $w_usuario,$w_tramite,
                          $w_novo_tramite, $_POST['w_envio'], $_POST['w_tipo_log'], $_POST['w_observacao'], $_POST['w_destinatario'], $_POST['w_despacho'],
                          null, null, null, null);

                  // Envia e-mail comunicando o envio
                  SolicMail($w_chave,2);

                  echo '<td>Enviado</td>';
                }
              }
            }
          } else {
            //Verifica a fase imediatamente anterior � atual.
            $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'DEVFLUXO',null);
            $RS = SortArray($RS,'ordem','desc');
            foreach($RS as $row) { $RS = $row; break; }
            $w_novo_tramite = f($RS,'sq_siw_tramite');
            if (nvl($w_novo_tramite,'')=='') {
              echo '<td>N�o h� fase anterior � atual ("'.$w_nm_tramite.'").</td>';
            } else {
              // Devolve a solicita��o
              $SQL = new dml_putAcordoEnvio;
              $SQL->getInstanceOf($dbms, $_POST['w_menu'], $w_chave, $w_usuario,$w_tramite,
                      $w_novo_tramite, $_POST['w_envio'], $_POST['w_tipo_log'], $_POST['w_observacao'], $_POST['w_destinatario'], $_POST['w_despacho'],
                      null, null, null, null);

              // Envia e-mail comunicando a devolu��o
              SolicMail($w_chave,2);

              echo '<td>Devolvido</td>';
            }
          } 
          echo '</table>';
          flush();
        }
      }
      ShowHTML('<p>Clique <a class="HL" href="'.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'">aqui</a> para voltar � tela anterior.</p>');
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } elseif (strpos($SG,'CONC')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],substr($SG,0,3).'GERAL');
      if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'ATEN��O: Outro usu�rio j� fez o encaminhamento para outra fase!\');');
        exit();
        ScriptClose();
      } else {
        $SQL = new dml_putAcordoConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_inicio_real'],$_REQUEST['w_fim_real'],$_REQUEST['w_nota_conclusao'],
          $_REQUEST['w_custo_real'],$_REQUEST['w_tipo_conc']);
        // Se for renova��o, grava os registros a partir do contrato atual
        if ($_REQUEST['w_tipo_conc']==0) {
          // Grava dados gerais
          $SQL = new dml_putAcordoGeral; $SQL->getInstanceOf($dbms,'I',$w_cliente,
            null,f($RS,'sq_menu'),f($RS,'sq_unidade'),f($RS,'solicitante'),
            $_SESSION['SQ_PESSOA'],f($RS,'sq_cc'),f($RS,'descricao'),f($RS,'justificativa'),
            $_REQUEST['w_inicio'],$_REQUEST['w_fim'],nvl($_REQUEST['w_valor'],0),f($RS,'data_hora'),
            f($RS,'aviso_prox_conc'),f($RS,'dias_aviso'),f($RS,'sq_cidade_origem'),f($RS,'sq_solic_pai'),
            f($RS,'sq_tipo_acordo'),f($RS,'objeto'),f($RS,'sq_tipo_pessoa'),
            f($RS,'sq_forma_pagamento'), null, null, f($RS,'sq_projeto_etapa'),
            f($RS,'codigo_interno'), f($RS,'titulo'), f($RS,'empenho'), f($RS,'processo'), 
            FormataDataEdicao(f($RS,'assinatura')), FormataDataEdicao(f($RS,'publicacao')),f($RS,'sq_moeda'),
            $w_chave_nova, $_REQUEST['w_chave'], null, null, $w_codigo);
        } 
        // Envia e-mail comunicando a conclus�o
        SolicMail($_REQUEST['w_chave'],3);
        ScriptOpen('JavaScript');
        // Volta para a listagem
        if ($_REQUEST['w_tipo_conc']==0) {
          ShowHTML('  alert(\'ATEN��O: a renova��o foi gerada com o c�digo '.$w_codigo.' e est� dispon�vel na tela de cadastramento!\');');
        } 
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados n�o encontrado: '.$SG.'");');
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
  case 'INICIAL':           Inicial();          break;
  case 'GERAL':             Geral();            break;
  case 'TERMO':             Termo();            break;
  case 'OUTRAPARTE':        OutraParte();       break;
  case 'PREPOSTO':          Preposto();         break;
  case 'DADOS':             DadosBancario();    break;
  case 'ANEXO':             Anexo();            break;
  case 'PARCELAS':          Parcelas();         break;
  case 'AREAS':             Areas();            break;
  case 'VISUAL':            Visual();           break;
  case 'EXCLUIR':           Excluir();          break;
  case 'ENVIO':             Encaminhamento();   break;
  case 'TRAMITE':           Tramitacao;         break;
  case 'ANOTACAO':          Anotar();           break;
  case 'CONCLUIR':          Concluir();         break;
  case 'GRAVA':             Grava();            break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
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
    break;
  } 
} 
?>