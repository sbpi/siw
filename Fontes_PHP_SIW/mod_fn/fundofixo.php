<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getFNParametro.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoIncid.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_getCronograma.php'); 
include_once($w_dir_volta.'classes/sp/db_getSolicCotacao.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putContaContabil.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicCotacao.php');
include_once($w_dir_volta.'funcoes/retornaContasContabeis.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAcordoParcela.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoRubricaApoio.php');
include_once('visualfundofixo.php');
include_once('validafundofixo.php');
// =========================================================================
//  /fundofixo.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas relativas ao controle de lan�amentos financeiros
// Mail     : celso@sbpi.com.br
// Criacao  : 14/07/2006 13:30
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
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'fundofixo.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_ativo        = upper($_REQUEST['p_ativo']);
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_objeto       = upper($_REQUEST['p_objeto']);
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
$p_sq_orprior   = $_REQUEST['p_sq_orprior'];

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ANEXO')!==false || strpos($SG,'PARC')!==false || strpos($SG,'REPR')!==false) {
  if (strpos('IG',$O)===false && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,'FNDFIXO');

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Recupera os par�metros de funcionamento do m�dulo
$sql = new db_getFNParametro; $RS_Parametro = $sql->getInstanceOf($dbms,$w_cliente,null,null);
foreach($RS_Parametro as $row) { $RS_Parametro = $row; break; }

// Verifica se o cliente tem o m�dulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

// Verifica se o cliente tem o m�dulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'CO');
if (count($RS)>0) $w_mod_cl='S'; else $w_mod_cl='N';

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configura��o do servi�o
if ($P2>10) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
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
    if ((!(strpos(upper($R),'GR_')===false)) || (!(strpos(upper($R),'PROJETO')===false)) || ($w_tipo=='WORD')) {
      $w_filtro='';
      if ($p_projeto>'') {
        $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');
        if ($w_tipo=='WORD') {
          $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b>'.f($RS,'titulo').'</b>]';
        } else {
          $w_filtro .= '<tr valign="top"><td align="right">Projeto <td>[<b><A class="HL" HREF="projeto.php?par=Visual&O=L&w_chave='.$p_projeto.'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informa��es do projeto." target="_blank">'.f($RS,'titulo').'</a></b>]';
        }
      } 
    }
    if ($p_sqcc>'') {
      $sql = new db_getCCData; $RS = $sql->getInstanceOf($dbms,$p_sqcc);
      $w_filtro .= '<tr valign="top"><td align="right">Classifica��o <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_chave>'') $w_filtro .= '<tr valign="top"><td align="right">Atividade n� <td>[<b>'.$p_chave.'</b>]';
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
    if ($p_sq_orprior>''){
      $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$p_sq_orprior,null,$w_cliente,null);
      foreach($RS as $row) {$RS = $row; break; }
      $w_filtro .= '<tr valign="top"><td align="right">Tipo do lan�amento <td>[<b>'.f($RS,'nome').'</b>]';
    } 
    if ($p_uorg_resp>'') {
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
    if ($p_proponente>'') $w_filtro .= '<tr valign="top"><td align="right">Parceria externa <td>[<b>'.$p_proponente.'</b>]';
    if ($p_objeto>'')     $w_filtro .= '<tr valign="top"><td align="right">Detalhamento <td>[<b>'.$p_objeto.'</b>]';
    if ($p_palavra>'')    $w_filtro .= '<tr valign="top"><td align="right">Respons�vel <td>[<b>'.$p_palavra.'</b>]';
    if ($p_ini_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Data recebimento <td>[<b>'.$p_ini_i.'-'.$p_ini_f.'</b>]';
    if ($p_fim_i>'')      $w_filtro .= '<tr valign="top"><td align="right">Limite conclus�o <td>[<b>'.$p_fim_i.'-'.$p_fim_f.'</b>]';
    if ($p_atraso=='S')   $w_filtro .= '<tr valign="top"><td align="right">Situa��o <td>[<b>Apenas atrasadas</b>]';
    if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr><td><b>Filtro:</b></td>'.$w_filtro.'</table></div>';
  }
  if ($w_copia>'') {
    // Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  } else {
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  }
  if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS = SortArray($RS,$lista[0],$lista[1],'vencimento','asc');
  } else {
    $RS = SortArray($RS,'nm_pessoa','asc','vencimento','desc');
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']); 
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
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
        Validate('p_chave','N�mero do lan�amento','','','1','18','','0123456789');
        Validate('p_prazo','Dias para a data limite','','','1','2','','0123456789');
        Validate('p_proponente','Parcerias externas','','','2','90','1','');
        Validate('p_objeto','Assunto','','','2','90','1','1');
        Validate('p_palavra','Palavras-chave','','','2','90','1','1');
        Validate('p_ini_i','Recebimento inicial','DATA','','10','10','','0123456789/');
        Validate('p_ini_f','Recebimento final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_ini_i.value != \'\' && theForm.p_ini_f.value == \'\') || (theForm.p_ini_i.value == \'\' && theForm.p_ini_f.value != \'\')) {');
        ShowHTML('     alert ("Informe ambas as datas de recebimento ou nenhuma delas!");');
        ShowHTML('     theForm.p_ini_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_ini_i','Recebimento inicial','<=','p_ini_f','Recebimento final');
        Validate('p_fim_i','Conclus�o inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim_f','Conclus�o final','DATA','','10','10','','0123456789/');
        ShowHTML('  if ((theForm.p_fim_i.value != \'\' && theForm.p_fim_f.value == \'\') || (theForm.p_fim_i.value == \'\' && theForm.p_fim_f.value != \'\')) {');
        ShowHTML('     alert ("Informe ambas as datas de conclus�o ou nenhuma delas!");');
        ShowHTML('     theForm.p_fim_i.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        CompData('p_fim_i','Conclus�o inicial','<=','p_fim_f','Conclus�o final');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (strpos('CP',$O)!==false) BodyOpen('onLoad=\'document.Form.p_projeto.focus();\'');
  else             BodyOpenClean(null);
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
    ShowHTML('<tr>');
    if ($P1==1) {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      ShowHTML('<tr>');
      if ($w_tipo!='WORD') {
        ShowHTML('    <td>');
        if (!(strpos($SG,'CONT')===false)) ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Buscaparcela&R='.$w_pagina.$par.'&O=P&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        else                               ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      }
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'LANCAMENTO')===false) && (Nvl($R,'')>'')) {
      if ($w_copia>'') {
        // Se for c�pia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    }
    ShowHTML('    <td align="right">');
    //if ($w_tipo!='WORD') {
      //ShowHTML('     <IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
      //ShowHTML('     &nbsp;&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.count($RS).'&TP='.$TP.'&SG='.$SG.'&w_tipo=WORD'.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.gif"></a>');
    //}      
    ShowHTML('    '.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    if ($w_tipo!='WORD') {    
      if (count($RS) && $P1==2) {
        $colspan++; ShowHTML('          <td align="center" width="15"><span class="remover"><input type="checkbox" id="marca_todos" name="marca_todos" value="" /></span></td>');
      }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('C�digo','ord_codigo_interno').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_pessoa_resumido').'</td>');
      $colspan++;
      if (!(strpos($SG,'CONT')===false))  ShowHTML('          <td><b>'.LinkOrdena('Contrato (Parcela)','cd_acordo').'</td>');
      else                                ShowHTML ('          <td><b>'.LinkOrdena('Vincula��o','dados_pai').'</td>');
      if (strpos('CONT',substr($SG,3))!==false) {
        $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Refer�ncia','referencia_fim').'</td>');
      }
      if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
        $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Projeto','dados_avo').'</td>');
      }
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Vencimento','vencimento').'</td>');
      $colspan++; ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
      if ($_SESSION['INTERNO']=='S') ShowHTML('          <td class="remover"><b>Opera��es</td>');
      ShowHTML('        </tr>');
    } else {
      $colspan++; ShowHTML('          <td><b>C�digo</td>');
      $colspan++; ShowHTML('          <td><b>Pessoa</td>');
      $colspan++; 
      if (!(strpos($SG,'CONT')===false))  ShowHTML('          <td><b>Contrato (Parcela)</td>');
      else                                ShowHTML('          <td><b>Vincula��o</td>');
      if (strpos('CONT',substr($SG,3))!==false) {
        $colspan++; ShowHTML('          <td><b>Refer�ncia</td>');
      }
      if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
        $colspan++; ShowHTML('          <td><b>Projeto</td>');
      }
      $colspan++; ShowHTML('          <td><b>Vencimento</td>');
      $colspan++; ShowHTML('          <td><b>Valor</td>');
      ShowHTML('        </tr>');
    }  
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.($colspan+2).' align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = array();
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
        $RS1 = $RS;
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
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') ShowHTML('        <A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'codigo_interno').''); 
        if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
          if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>');
          else                 ShowHTML('        <td>'.f($row,'nm_pessoa_resumido').'</td>');
        } else {
          ShowHTML('        <td align="center">---</td>');
        }
        if (strpos($SG,'CONT')!==false) {
          if ($w_tipo!='WORD') ShowHTML('        <td><A class="hl" HREF="'.'mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($row,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informa��es do acordo." target="_blank">'.f($row,'cd_acordo').' ('.f($row,'or_parcela').')</a></td>');
          else                 ShowHTML('        <td>'.f($row,'cd_acordo').' ('.f($row,'or_parcela').')</td>');
        } else {
          if (Nvl(f($row,'dados_pai'),'')!='') {
            ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
          } else {
            ShowHTML('        <td>---</td>');
          }
        } 
        if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
          if (Nvl(f($row,'dados_avo'),'')!='') {
            ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_avo'),f($row,'dados_avo'),'N',$w_tipo).'</td>');
          } else {
            ShowHTML('        <td>---</td>');
          }
        }
        if (strpos('CONT',substr($SG,3))!==false) {
          if (nvl(f($row,'referencia_inicio'),'')!='') {
            ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'referencia_inicio'),5).' a '.FormataDataEdicao(f($row,'referencia_fim'),5).'</td>');
          } else {
            ShowHTML('        <td align="center">-</td>');
          }
        }
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'vencimento'),5),'-').'</td>');
        ShowHTML('        <td align="right">'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').number_format(f($row,'valor'),2,',','.').'&nbsp;</td>');
        $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + f($row,'valor');
        if ($w_tipo!='WORD') {
          if ($_SESSION['INTERNO']=='S') {
            ShowHTML('        <td class="remover" align="top" nowrap>');
            if ($P1!=3) {
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do lan�amento">AL</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do lan�amento.">EX</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Listagem&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outra fase.">EN</A>&nbsp');
              ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Anotacao&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Registra anota��es para o lan�amento, sem envi�-la.">AN</A>&nbsp');
            } else {
              if (Nvl(f($row,'solicitante'),0)    == $w_usuario || 
                  Nvl(f($row,'titular'),0)        == $w_usuario || 
                  Nvl(f($row,'substituto'),0)     == $w_usuario || 
                  Nvl(f($row,'tit_exec'),0)       == $w_usuario || 
                  Nvl(f($row,'subst_exec'),0)     == $w_usuario ||
                  RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
            ShowHTML('        </td>');
          } 
        }
        ShowHTML('      </tr>');
      } 
      if ($P1!=1) {
        // Se n�o for cadastramento
        
        // Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
        if (ceil(count($RS)/$P4)>1) {
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          if (strpos('CONT',substr($SG,3))===false && f($RS_Menu,'sigla')!='FNDVIA' && f($RS_Menu,'sigla')!='FNREVENT') {
            $colspan = 4;
          } else {
            $colspan = 5;
          }
          ShowHTML('          <td align="right"><b>'.formatNumber($w_parcial,2).'&nbsp;</td>');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').' desta p�gina&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_parcial);
          foreach($w_parcial as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
          ShowHTML('          <td colspan=2>&nbsp;</td>');
          ShowHTML('        </tr>');
        } 
        // Se for a �ltima p�gina da listagem, soma e exibe o valor total
        if ($P3==ceil(count($RS)/$P4)) {
          foreach($RS as $row) {
            if (f($row,'sg_tramite')=='AT') $w_total += f($row,'valor_atual');
            else                            $w_total += f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          if (strpos('CONT',substr($SG,3))===false && f($RS_Menu,'sigla')!='FNDVIA' && f($RS_Menu,'sigla')!='FNREVENT') {
            $colspan = 4;
          } else {
            $colspan = 5;
          }
          $w_total = array();
          foreach($RS as $row) {
            $w_total[f($row,'sb_moeda')] = nvl($w_total[f($row,'sb_moeda')],0) + f($row,'valor');
          } 
          ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
          ShowHTML('          <td colspan="'.$colspan.'" align="right"><b>Tota'.((count($w_total)==1) ? 'l' : 'is').' da listagem&nbsp;</td>');
          ShowHTML('          <td align="right" nowrap><b>');
          $i = 0;
          ksort($w_total);
          foreach($w_total as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber($v,2)); $i++; }
          echo('</td>');
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
    if (count($RS) && $w_tipo!='WORD') {
      if ($P1==2) {
        ShowHTML('<span class="remover">');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
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
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif(strpos('CP',$O)!==false) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar o lan�amento que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    // Recupera dados da op��o Projetos
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto do lan�amento na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto',$w_menu,null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assifica��o:','L','Selecione a classifica��o desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>N�mero da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo monitoramento do lan�amento na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respons�vel atua<u>l</u>:','L','Selecione o respons�vel atual pelo lan�amento na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o lan�amento se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>In�<u>c</u>io vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td><b>Exibe lan�amentos vencidos?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N"> N�o');
        else                ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N" checked> N�o');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if     ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='vencimento')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento" SELECTED>In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='FIM')           ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite" SELECTED>Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_pessoa')     ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa" SELECTED>Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_PROJETO')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente" SELECTED>Projeto');
    else                                ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="" SELECTED>T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
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
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_tipo_lancamento = $_REQUEST['w_sq_tipo_lancamento'];
  $w_readonly           = '';
  $w_erro               = '';
  
  $w_segmento = f($RS_Cliente,'segmento');
  
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_pessoa               = $_REQUEST['w_pessoa'];
    $w_tipo_pessoa          = $_REQUEST['w_tipo_pessoa'];
    $w_nm_tipo_pessoa       = $_REQUEST['w_nm_tipo_pessoa'];
    $w_sq_acordo_parcela    = $_REQUEST['w_sq_acordo_parcela'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];
    $w_forma_atual          = $_REQUEST['w_forma_atual'];
    $w_vencimento_atual     = $_REQUEST['w_vencimento_atual'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_observacao           = $_REQUEST['w_observacao'];
    $w_aviso                = $_REQUEST['w_aviso'];
    $w_dias                 = $_REQUEST['w_dias'];
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
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
    $w_emissao              = $_REQUEST['w_emissao'];
    $w_vencimento           = $_REQUEST['w_vencimento'];
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
    $w_tipo_rubrica         = $_REQUEST['w_tipo_rubrica'];
    $w_numero_processo      = $_REQUEST['w_numero_processo'];
    $w_protocolo            = $_REQUEST['w_protocolo'];
    $w_protocolo_nm         = $_REQUEST['w_protocolo_nm'];
    $w_qtd_nota             = $_REQUEST['w_qtd_nota'];
    $w_per_ini              = $_REQUEST['w_per_ini'];
    $w_per_fim              = $_REQUEST['w_per_fim'];
    $w_texto_pagamento      = $_REQUEST['w_texto_pagamento']; 
    $w_conta                = $_REQUEST['w_conta'];
    $w_sq_projeto_rubrica   = $_REQUEST['w_sq_projeto_rubrica'];
    $w_solic_apoio          = $_REQUEST['w_solic_apoio'];
    $w_data_autorizacao     = $_REQUEST['w_data_autorizacao'];
    $w_texto_autorizacao    = $_REQUEST['w_texto_autorizacao'];
    $w_cc_debito            = $_REQUEST['w_cc_debito'];
    $w_cc_credito           = $_REQUEST['w_cc_credito'];
    
    // Recarrega dados do comprovante
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_numero               = $_REQUEST['w_numero'];
    $w_data                 = $_REQUEST['w_data'];
    $w_serie                = $_REQUEST['w_serie'];
    $w_valor_doc            = $_REQUEST['w_valor_doc'];
    $w_patrimonio           = $_REQUEST['w_patrimonio'];
    $w_tipo                 = $_REQUEST['w_tipo'];
  } elseif(strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados do lan�amento

    $sql = new db_getSolicData; 
    $RS_Solic = $sql->getInstanceOf($dbms,nvl($w_copia,$w_chave),$SG);
    if (count($RS_Solic)>0) {
      $w_sq_unidade           = f($RS_Solic,'sq_unidade');
      $w_observacao           = f($RS_Solic,'observacao');
      $w_aviso                = f($RS_Solic,'aviso_prox_conc');
      $w_dias                 = f($RS_Solic,'dias_aviso');
      $w_sq_acordo_parcela    = f($RS_Solic,'sq_acordo_parcela');
      $w_sq_tipo_lancamento   = f($RS_Solic,'sq_tipo_lancamento');
      $w_pessoa               = f($RS_Solic,'pessoa');
      $w_tipo_pessoa          = f($RS_Solic,'sq_tipo_pessoa');
      $w_nm_tipo_pessoa       = f($RS_Solic,'nm_tipo_pessoa');
      $w_sq_forma_pagamento   = f($RS_Solic,'sq_forma_pagamento');
      $w_forma_atual          = f($RS_Solic,'sq_forma_pagamento');
      $w_codigo_interno       = f($RS_Solic,'codigo_interno');
      $w_chave_pai            = f($RS_Solic,'sq_solic_pai');
      $w_chave_aux            = null;
      $w_sq_menu              = f($RS_Solic,'sq_menu');
      $w_sq_unidade           = f($RS_Solic,'sq_unidade');
      $w_sq_tramite           = f($RS_Solic,'sq_siw_tramite');
      $w_solicitante          = f($RS_Solic,'solicitante');
      $w_cadastrador          = f($RS_Solic,'cadastrador');
      $w_executor             = f($RS_Solic,'executor');
      $w_descricao            = f($RS_Solic,'descricao');
      $w_justificativa        = f($RS_Solic,'justificativa');
      $w_vencimento           = FormataDataEdicao(f($RS_Solic,'fim'));
      $w_vencimento_atual     = FormataDataEdicao(f($RS_Solic,'fim'));
      $w_inclusao             = f($RS_Solic,'inclusao');
      $w_ultima_alteracao     = f($RS_Solic,'ultima_alteracao');
      $w_conclusao            = f($RS_Solic,'conclusao');
      $w_opiniao              = f($RS_Solic,'opiniao');
      $w_data_hora            = f($RS_Solic,'data_hora');
      $w_sqcc                 = f($RS_Solic,'sq_cc');
      $w_pais                 = f($RS_Solic,'sq_pais');
      $w_uf                   = f($RS_Solic,'co_uf');
      $w_cidade               = f($RS_Solic,'sq_cidade_origem');
      $w_palavra_chave        = f($RS_Solic,'palavra_chave');
      $w_valor                = number_format(f($RS_Solic,'valor'),2,',','.');
      $w_tipo_rubrica         = f($RS_Solic,'tipo_rubrica');
      $w_numero_processo      = f($RS_Solic,'processo');      
      $w_protocolo            = f($RS_Solic,'processo');
      $w_protocolo_nm         = f($RS_Solic,'processo');
      $w_nm_tipo_rubrica      = f($RS_Solic,'nm_tipo_rubrica');
      $w_qtd_nota             = f($RS_Solic,'qtd_nota');
      $w_per_ini              = FormataDataEdicao(f($RS_Solic,'referencia_inicio'));
      $w_per_fim              = FormataDataEdicao(f($RS_Solic,'referencia_fim'));
      $w_texto_pagamento      = f($RS_Solic,'condicoes_pagamento');
      $w_conta                = f($RS_Solic,'sq_pessoa_conta');
      $w_sq_projeto_rubrica   = f($RS_Solic,'sq_projeto_rubrica');
      $w_solic_apoio          = f($RS_Solic,'sq_solic_apoio');
      $w_data_autorizacao     = FormataDataEdicao(f($RS_Solic,'data_autorizacao'));
      $w_texto_autorizacao    = f($RS_Solic,'texto_autorizacao');
      $w_cc_debito            = f($RS_Solic,'cc_debito');
      $w_cc_credito           = f($RS_Solic,'cc_credito');
    } 

    // Recupera dados do comprovante
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,nvl($w_copia,$w_chave),null,null,null,null,null,null,'DOCS');
    $RS = SortArray($RS,'sq_tipo_documento','asc');
    foreach ($RS as $row) {$RS=$row; break;}
    if (nvl($w_copia,'')=='') $w_chave_doc           =  f($RS,'sq_lancamento_doc');
    $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
    $w_numero               = f($RS,'numero');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
    $w_serie                = f($RS,'serie');
    $w_valor_doc            = formatNumber(f($RS,'valor'));
    $w_patrimonio           = f($RS,'patrimonio');
    $w_tributo              = f($RS,'calcula_tributo');
    $w_retencao             = f($RS,'calcula_retencao');
  }
  
  if (addDays(time(),f($RS_Parametro,'fundo_fixo_dias_utilizacao')) > toDate('31/12/'.date('Y',time()))) {
    $w_limite = formataDataEdicao(toDate('31/12/'.date('Y',time())));
  } else {
    $w_limite = formataDataEdicao(addDays(time(),f($RS_Parametro,'fundo_fixo_dias_utilizacao')));
  }

  // Verifica as formas de pagamento poss�veis. Se apenas uma, atribui direto
  $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, $SG, null,'S',null);
  $w_exibe_fp = true;
  if (count($RS)==1 || nvl($w_sq_forma_pagamento,'')!='') {
    foreach($RS as $row) { 
      if (nvl($w_sq_forma_pagamento,f($row,'sq_forma_pagamento'))==f($row,'sq_forma_pagamento')) {
        $w_sq_forma_pagamento = f($row,'sq_forma_pagamento'); 
        $w_forma_pagamento    = f($row,'sigla'); 
        $w_nm_forma_pagamento = f($row,'nome'); 
        break; 
      }
    }
    if (count($RS)==1) $w_exibe_fp = false;
  }
  
  if ($w_conta){
    $sql = new db_getContaBancoData; $RS_Conta = $sql->getInstanceOf($dbms,$w_conta);
    $w_moeda_conta = f($RS_Conta,'sq_moeda');
  }
  
  $sql = new db_getContaBancoList; $RS_Contas = $sql->getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
  if (count($RS_Contas)>1) { 
    $w_exige_conta = true; 
  } else {
    $w_exige_conta = false;
    if (count($RS_Contas)==1) {
      foreach($RS_Contas as $row) $RS_Contas = $row;
      $w_conta       = f($RS_Contas,'sq_pessoa_conta');
      $w_moeda_conta = f($RS_Contas,'sq_moeda');
    }
  }
  
  if (nvl($w_solic_vinculo,'')!='' || nvl($w_chave_pai,'')!='') {
    // Recupera dados do projeto
    $sql = new db_getSolicData; $RS_Pai = $sql->getInstanceOf($dbms,$w_chave_pai,'PJCAD');
    $w_moeda_pai  = f($RS_Pai,'sq_moeda');
  
    // Prepara array com os valores das moedas a serem gravadas
    if ($w_moeda_pai!=$w_moeda_conta) {
      unset($w_moedas);
      if ($w_moeda_conta!=$w_moeda_pai && nvl($w_moeda_pai,'')!='')     $w_moedas[f($RS_Pai,'sq_moeda')]   = f($RS_Pai,'sb_moeda');
      if (is_array($w_moedas)) asort($w_moedas);

      $sql = new db_getSolicCotacao; $RS_Moeda_Cot = $sql->getInstanceOf($dbms,$w_cliente, $w_chave,null,null,null,null);
      foreach($RS_Moeda_Cot as $row) {
        if ($w_moeda_conta!=f($row,'sq_moeda_cot') && array_key_exists(f($row,'sq_moeda_cot'),$w_moedas)) {
          //echo '['.f($row,'sq_moeda_cot').'] ['.f($row,'sq_moeda_cot').'] ['.w_valor_.f($row,'sq_moeda_cot').'] ['.f($row,'sq_moeda_cot').'] ['.$_REQUEST[w_valor_.f($row,'sq_moeda_cot')].'] ['.formatNumber(f($row,'vl_cotacao')).']';
          $linha = '$w_valor_'.f($row,'sq_moeda_cot').' = nvl($_REQUEST[\'w_valor_'.f($row,'sq_moeda_cot').'\'],\''.formatNumber(f($row,'vl_cotacao')).'\');';
          eval($linha);
        }
      }
    }

    // Se ligado a projeto, recupera rubricas
    $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,nvl($w_solic_vinculo,$w_chave_pai),null,'S',null,null,null,null,null,'SELECAO');

    if (count($RS_Rub)>0) {
      if (nvl($w_sq_projeto_rubrica,'')=='') {
        // Recupera os documentos do lan�amento
        $sql = new db_getLancamentoDoc; $RS_Doc = $sql->getInstanceOf($dbms,nvl($w_chave,0),null,null,null,null,null,null,'DOCS');
        if (count($RS_Doc)>0) {
          foreach($RS_Doc as $row) {
            $sql = new db_getLancamentoItem; $RS_Item = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
            foreach($RS_Item as $row1) {
              $w_sq_projeto_rubrica = f($row1,'sq_projeto_rubrica');
              break;
            }
            break;
          }
        }
      }
      
      if (nvl($w_sq_projeto_rubrica,'')!='') {
        // Recupera dados da rubrica
        $sql = new db_getSolicRubrica; $RS = $sql->getInstanceOf($dbms,nvl($w_solic_vinculo,$w_chave_pai),$w_sq_projeto_rubrica,null,null,null,null,null,null,null);
        foreach($RS as $row) { 
          $w_exige_autorizacao = f($row,'exige_autorizacao'); 
        }
        
        
        // Verificar fontes de financiamento poss�veis. Se apenas uma, atribui direto.
        $sql = new db_getCronograma; $RS_Fonte = $sql->getInstanceOf($dbms,$w_sq_projeto_rubrica,$w_chave_aux,null,null,null,'RUBFONTES');
        if (count($RS_Fonte)==0) {
          $w_exibe_ff = false;
        } else {
          $w_exibe_ff = true;
          if (count($RS_Fonte)==1 || nvl($w_solic_apoio,'')!='') {
            foreach($RS_Fonte as $row) { 
              if (nvl($w_solic_apoio,f($row,'sq_solic_apoio'))==f($row,'sq_solic_apoio')) {
                $w_solic_apoio = f($row,'sq_solic_apoio'); 
                break; 
              }
            }
            if (count($RS_Fonte)==1) $w_exibe_ff = false;
          }
        }
      }
    }
  }

  if (nvl($w_troca,'')=='' && nvl($w_chave,'')!='') {
    // Recupera os documentos do lan�amento
    $sql = new db_getLancamentoDoc; $RS_Doc = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
    if (count($RS_Doc)>0) {
      foreach($RS_Doc as $row) {
        $sql = new db_getLancamentoItem; $RS_Item = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
        foreach($RS_Item as $row1) {
          $w_sq_projeto_rubrica = f($row1,'sq_projeto_rubrica');
          break;
        }
        break;
      }
    }
  }
  
  // Retorna as contas cont�beis do lan�amento
  retornaContasContabeis($RS_Menu, $w_cliente, $w_sq_tipo_lancamento, $w_sq_forma_pagamento, $w_conta, $w_cc_debito, $w_cc_credito);

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
    Validate('w_descricao','Finalidade','1',1,5,2000,'1','1');
    Validate('w_chave_pai','Projeto','SELECT','1',1,18,'','0123456789');
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    if ($w_exibe_ff) Validate('w_solic_apoio','Fonte de financiamento','SELECT',1,1,18,'','0123456789');
    if ($w_exige_autorizacao=='S') {
      Validate('w_data_autorizacao','Data "No objection"','DATA',1,10,10,'','0123456789/');
      Validate('w_texto_autorizacao','Texto "No objection"','1','','2','500','1','0123456789');
    }
    if ($w_mod_pa=='S') {
      Validate('w_protocolo_nm','N�mero do processo','hidden','1','20','20','','0123456789./-');
    } elseif($w_segmento=='P�blico') {
      Validate('w_numero_processo','N�mero do processo','1','',1,30,'1','1');
    }
    Validate('w_sq_tipo_lancamento','Tipo do lan�amento','SELECT',1,1,18,'','0123456789');
    Validate('w_solicitante','Suprido','SELECT',1,1,18,'','0123456789');
    Validate('w_vencimento','Limite para utiliza��o','DATA',1,10,10,'','0123456789/');
    CompData('w_vencimento','Limite para utiliza��o','<=',$w_limite,f($RS_Parametro,'fundo_fixo_dias_utilizacao').' dias da data corrente ou at� o final do ano!');
    if ($w_exibe_fp) Validate('w_sq_forma_pagamento','Forma de recebimento','SELECT',1,1,18,'','0123456789');       
    //Validate('w_valor','Valor do documento','VALOR','1',4,18,'','0123456789.,');
    Validate('w_descricao','Finalidade','1','',5,2000,'1','1');
    
    Validate('w_cc_debito','Conta D�bito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    Validate('w_cc_credito','Conta Cr�dito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    ShowHTML('  if ((theForm.w_cc_debito.value != "" && theForm.w_cc_credito.value == "") || (theForm.w_cc_debito.value == "" && theForm.w_cc_credito.value != "")) {');
    ShowHTML('     alert ("Informe ambas as contas cont�beis ou nenhuma delas!");');
    ShowHTML('     theForm.w_cc_debito.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    
    Validate('w_sq_tipo_documento','Tipo do documento', '1', '1', '1', '18', '', '0123456789');
    if ($w_exige_conta) Validate('w_conta','Conta banc�ria', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_numero','N�mero do documento', '1', '1', '1', '30', '1', '1');
    Validate('w_data','Data de saque', 'DATA', '1', '10', '10', '', '0123456789/');
    CompData('w_data','Data de saque','<=','w_vencimento','Limite para utiliza��o');
    Validate('w_valor','Valor total do documento','VALOR','1',4,18,'','0123456789.,');
    CompValor('w_valor','Valor total do documento','>','0,00','zero');
    CompValor('w_valor','Valor total do documento','<=',formatNumber(f($RS_Parametro,'fundo_fixo_valor')),formatNumber(f($RS_Parametro,'fundo_fixo_valor')));
    if (is_array($w_moedas)) {
      foreach($w_moedas as $k => $v) {
        Validate('w_valor_'.$k,'Valor ('.str_replace('&eur;','EURO',$v).')','VALOR','1', 4, 18, '', '0123456789.,-');
      }        
    }
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'')                               BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('EV',$O)===false))            BodyOpen('onLoad=\'this.focus()\';');
  else                                           BodyOpen('onLoad=\'document.Form.w_descricao.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($w_chave>'') ShowHTML('      <tr><td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></td>');
  if (strpos('IAEV',$O)!==false) {
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    }  
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_unidade" value="'.f($RS_Menu,'sq_unid_executora').'">');
    ShowHTML('<INPUT type="hidden" name="w_forma_atual" value="'.$w_forma_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_vencimento_atual" value="'.$w_vencimento_atual.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="S">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="3">');
    ShowHTML('<INPUT type="hidden" name="w_codigo_interno" value="'.$w_codigo_interno.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_doc" value="'.$w_chave_doc.'">');
    ShowHTML('<INPUT type="hidden" name="w_moeda_pai" value="'.$w_moeda_pai.'">');
    
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5">Os dados deste bloco ser�o utilizados para identifica��o do lan�amento, bem como para o controle de sua execu��o.</td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    // Recupera dados da op��o Projetos
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoSolic('Pro<u>j</u>eto:','J','Selecione o projeto ao qual o lan�amento est� vinculado.',$w_cliente,$w_chave_pai,$w_sq_menu_relac,f($RS_Menu,'sq_menu'),'w_chave_pai',f($RS_Relac,'sigla'),'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_descricao\'; document.Form.submit();"',$w_chave_pai,'<BR />',5);

    if(count($RS_Rub)>0) {
      ShowHTML('      <tr>');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,nvl($w_solic_vinculo,$w_chave_pai),null,'w_sq_projeto_rubrica','SELECAO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_projeto_rubrica\'; document.Form.submit();"',5);
      ShowHTML('      </tr>');
      
      // Trata fonte de financiamento
      if ($w_exibe_ff) {
        ShowHTML('      <tr>');
        SelecaoRubricaApoio('<u>F</u>onte de financiamento:','F', 'Selecione a fonte de financiamento que dar� suporte ao lan�amento.', $w_solic_apoio,$w_sq_projeto_rubrica,'w_solic_apoio','RUBFONTE',null);
        ShowHTML('      </tr>');
      } else {
        ShowHTML('          <INPUT type="hidden" name="w_solic_apoio" value="'.$w_solic_apoio.'">');
      }

      // Trata autoriza��o da despesa
      if ($w_exige_autorizacao=='S') {
        ShowHTML('      <tr><td colspan="3"><b><u>D</u>ata <i>No objection</i>:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data_autorizacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_data_autorizacao,FormataDataEdicao(time())).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_data_autorizacao').'</td>');
        ShowHTML('      <tr><td colspan="3"><b><u>T</u>exto <i>No objection</i>:</b><br><textarea '.$w_Disabled.' accesskey="T" name="w_texto_autorizacao" class="sti" ROWS=3 cols=75 title="Texto de autoriza��o da despesa">'.$w_texto_autorizacao.'</TEXTAREA></td>');
      }
    }
    ShowHTML('      <tr valign="top">');
    SelecaoTipoLancamento('<u>T</u>ipo de pagamento:','T','Selecione na lista o tipo de pagamento adequado.',$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',substr($SG,0,3).'VINC','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_solicitante\'; document.Form.submit();"',5);
    ShowHTML('        <tr valign="top">');
    if ($w_mod_pa=='S') {
      SelecaoProtocolo('N<u>�</u>mero do processo:','U','Selecione o processo da compra/licita��o.',$w_protocolo,null,'w_protocolo','JUNTADA',null);
    } elseif($w_segmento=='P�blico') {
      ShowHTML('         <td><b>N<U>�</U>mero do processo:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="w_numero_processo" size="20" maxlength="30" value="'.$w_numero_processo.'" title="OPCIONAL. Informe o n�mero do processo ao qual este lan�amento est� ligado."></td>');
    }
    $sql = new db_getKindPersonList; $RS_TipoPessoa = $sql->getInstanceOf($dbms, null);
    foreach($RS_TipoPessoa as $row) {
      if (substr(f($row,'nome'),0,1)=='F') {
        ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.f($row,'sq_tipo_pessoa').'">');
        break;
      }
    }
    SelecaoPessoa('<u>S</u>uprido:','N','Colaborador respons�vel pelo controle dos recursos do fundo fixo.',nvl($w_solicitante,$w_usuario),null,'w_solicitante','USUARIOS');
    if ($w_exibe_fp) {
      SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento para este lan�amento.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_forma_pagamento" value="'.$w_sq_forma_pagamento.'">');
    }
    ShowHTML('          <td><b><u>L</u>imite para utiliza��o:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_vencimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_vencimento,$w_limite).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_vencimento').'</td>');

    ShowHTML('      <tr><td colspan="5"><b><u>F</u>inalidade:</b><br><textarea '.$w_Disabled.' accesskey="F" name="w_descricao" class="sti" ROWS=3 cols=75 title="Finalidade do lan�amento.">'.$w_descricao.'</TEXTAREA></td>');

    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>C</u>onta cont�bil de d�bito:</b></br><input type="text" name="w_cc_debito" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.$w_cc_debito.'"></td>');
    ShowHTML('        <td><b><u>C</u>onta cont�bil de cr�dito:</b></br><input type="text" name="w_cc_credito" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.$w_cc_credito.'"></td>');

    ShowHTML('      <tr><td colspan="5" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="5" valign="top" align="center" bgcolor="#D0D0D0"><b>Documento de despesa</td></td></tr>');
    ShowHTML('      <tr><td colspan="5" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoDocumento('<u>T</u>ipo:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,$w_menu,'w_sq_tipo_documento',null,null);
    if ($w_exige_conta) {
      SelecaoContaBAnco('C<u>o</u>nta banc�ria:','O','Selecione a conta banc�ria envolvida no lan�amento.',$w_conta,null,'w_conta',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_conta\'; document.Form.submit();"');
    } else {
      ShowHTML('<INPUT type="hidden" name="w_conta" value="'.$w_conta.'">');
    }
    ShowHTML('          <td><b><u>N</u>�mero:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_numero.'" title="Informe o n�mero do documento."></td>');
    ShowHTML('          <td><b><u>S</u>aque:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data do saque.">'.ExibeCalendario('Form','w_data').'</td>');
    
    ShowHTML('          <td><b><u>V</u>alor'.((f($RS_Conta,'sb_moeda')) ? ' ('.f($RS_Conta,'sb_moeda').')' : '').':</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('<INPUT type="hidden" name="w_valor_doc" value="'.$w_valor_doc.'">');
    
    // Exige valor nas moedas da solicita��o pai e da conta banc�ria, se forem diferentes da moeda da solicita��o
    if (is_array($w_moedas) && f($RS_Conta,'sb_moeda')) {
      foreach($w_moedas as $k => $v) {
        ShowHTML('<INPUT type="hidden" name="w_moeda[]" value="'.$k.'">');
        eval('$valor = $w_valor_'.$k.';');
        ShowHTML('        <tr><td colspan=4><td><b>Valo<u>r</u> ('.$v.'):</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_valor_'.$k.'" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do lan�amento na moeda informada.">'.converteMoeda('Form','w_data','w_valor_'.$k,'w_valor',f($RS_Conta,'sq_moeda'),$k).'</td>');
      }
    }

    ShowHTML('      <tr><td align="center" colspan="5" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="5">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    }
    if (($O!='I') && (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.$w_chave.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Enviar">');
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
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));
  if ($w_tipo=='PDF') {
    headerpdf('Visualiza��o de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpenClean(null); 
  if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),4,$w_chave);
  $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if ($w_tipo=='PDF') RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  if ($P1==0) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  // Se envio de reembolso, chama a rotina de envio sem indica��o de destinat�rio
  if (f($RS_Menu,'destinatario')=='N') {
    EncAutomatico();
    exit();
  }
  
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_erro       = '';
  $w_tramite    = $_REQUEST['w_tramite'];
  
  // Recupera os dados da solicita��o
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_destinatario=$_REQUEST['w_destinatario'];
    $w_novo_tramite=$_REQUEST['w_novo_tramite'];
    $w_despacho=$_REQUEST['w_despacho'];
  } else {
    $w_sg_tramite_ant = f($RS_Solic,'sg_tramite');
    $w_novo_tramite   = f($RS_Solic,'sq_siw_tramite');
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
  if ($O=='V') $w_erro = ValidaFundoFixo($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinat�rio', 'HIDDEN', '1', '1', '10', '', '1');
    Validate('w_despacho','Despacho', '', '1', '1', '2000', '1', '1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if(($w_sg_tramite_ant=='AT')&&($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0')) {
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>N�o � poss�vel o envio do lan�amento enquanto as corre��es listadas n�o forem feitas.</b></font></td>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  } else {  
    if (nvl(f($RS_Solic,'condicoes_pagamento'),'')!='') {
      ShowHTML('      <tr><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);" colspan=4><b><font color="#BC3131">');
      ShowHTML('        VERIFIQUE AS CONDI��ES ABAIXO ANTES DE EXECUTAR O ENVIO:<ul>');
      ShowHTML('        <li>'.str_replace($crlf,'<li>',f($RS_Solic,'condicoes_pagamento')));
      ShowHTML('        </b></font></td>');
      ShowHTML('      </tr>');
      ShowHTML('<tr><td>&nbsp;');
    }
    if ($P1!=1) {
      // Se n�o for cadastramento
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      else
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
      if ($w_sg_tramite=='CI')
        SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione, na rela��o, um destinat�rio para o lan�amento.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'CADASTRAMENTO');
      else
        SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    } else {
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      } else {
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', null);
      }
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    }     
    ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a a��o esperada pelo destinat�rio na execu��o do lan�amento.">'.$w_despacho.'</TEXTAREA></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function EncAutomatico() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_justif_dia_util  = $_REQUEST['w_justif_dia_util'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio           = f($RS,'inicio');
    $w_tramite          = f($RS,'sq_siw_tramite');
    $w_justificativa    = f($RS,'justificativa');
    $w_prazo            = f($RS,'limite_envio');
    $w_antecedencia     = f($RS,'dias_antecedencia');
    $w_justif_dia_util  = f($RS,'justificativa_dia_util');
    $w_fim_semana       = f($RS,'fim_semana');
  }

  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');

  $w_ativo = f($RS, 'ativo');
  

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de sele��o da fase.
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'DEVFLUXO',null);
    $RS = SortArray($RS,'ordem','desc');
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  }

  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaFundoFixo($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0' && (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE') {
        Validate('w_despacho','Despacho','1','1','1','2000','1','1');
      } else {
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
      }
    }
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se n�o for encaminhamento e nem o sub-menu do cadastramento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVAUT',$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
    if ($w_sg_tramite=='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
        // Se cadastramento inicial
        ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
        ShowHTML('      </table>');
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      } else {
        ShowHTML('      </table>');
        ShowHTML('    <tr><td align="center" colspan=4>');
      }
    } else {
      ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');

      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE' || $w_ativo == 'N') {
        ShowHTML('              <input DISABLED class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        ShowHTML('<INPUT type="hidden" name="w_envio" value="S">');
      } else {
        if (Nvl($w_envio,'N')=='N') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N" checked> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"> Devolver para a fase anterior');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        }
      }
      ShowHTML('    <tr>');
      SelecaoFase('<u>F</u>ase: (v�lido apenas se for devolu��o)','F','Se deseja devolver a solicita��o, selecione a fase para a qual deseja devolv�-la.',$w_novo_tramite,$w_tramite,$w_chave,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolu��o):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinat�rio deve fazer quando receber a solicita��o.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para n�o cumprimento do prazo regulamentar de '.$w_antecedencia.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o in�cio da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do n�o cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          }
        }
      }
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    }
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1!=1) {
      // Se n�o for cadastramento, volta para a listagem
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P2==1) {
      ShowHTML('      <INPUT class="stb" type="button" onClick="parent.$.fancybox.close();" name="Botao" value="Cancelar">');
    } elseif ($P1==1) {
      if ($w_tipo=='Volta') {
        ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'Geral&O=A&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
      } elseif ($w_tipo=='Listagem') {
        ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.'Inicial&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
      }
    }
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Rotina de anota��o
// -------------------------------------------------------------------------
function Anotar() {
  extract($GLOBALS);
  $w_chave=$_REQUEST['w_chave'];
  $w_chave_aux=$_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_observacao','Anota��o', '', '1', '1', '2000', '1', '1');
    Validate('w_caminho','Arquivo', '', '', '5', '255', '1', '1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'')
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  else
    BodyOpen('onLoad=\'document.Form.w_observacao.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'ENVIO&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.(f($RS_Cliente,'upload_maximo')/1024).'KBytes</b>.</font></td>');
  ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS_Cliente,'upload_maximo').'">');
  ShowHTML('      <tr><td><b>A<u>n</u>ota��o:</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_observacao" class="sti" ROWS=5 cols=75 title="Redija a anota��o desejada.">'.$w_observacao.'</TEXTAREA></td>');
  ShowHTML('      <tr><td><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
  if ($P1==0) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
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
// Rotina de conclus�o
// -------------------------------------------------------------------------
function Concluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_quitacao           = $_REQUEST['w_quitacao'];
    $w_valor_real         = $_REQUEST['w_valor_real'];
    $w_codigo_deposito    = $_REQUEST['w_codigo_deposito'];
    $w_observacao         = $_REQUEST['w_observacao'];
    $w_conta              = $_REQUEST['w_conta'];
    $w_sq_tipo_lancamento = $_REQUEST['$w_sq_tipo_lancamento'];
    $w_cc_debito          = $_REQUEST['w_cc_debito'];
    $w_cc_credito         = $_REQUEST['w_cc_credito'];
  } 

  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
  $w_quitacao           = FormataDataEdicao(f($RS_Solic,'quitacao'));
  $w_observacao         = f($RS_Solic,'observacao');
  $w_tramite            = f($RS_Solic,'sq_siw_tramite');
  $w_conta              = f($RS_Solic,'sq_pessoa_conta');
  $w_valor_real         = formatNumber(f($RS_Solic,'valor'));
  $w_saldo              = f($RS_Solic,'valor');
  $w_sg_forma_pagamento = f($RS_Solic,'sg_forma_pagamento');
  $w_sq_tipo_lancamento = f($RS_Solic,'sq_tipo_lancamento');
  $w_inicio             = FormataDataEdicao(time());
  $w_sq_projeto_rubrica = f($RS_Solic,'sq_projeto_rubrica');
  $w_cc_debito          = f($RS_Solic,'cc_debito');
  $w_cc_credito         = f($RS_Solic,'cc_credito');

  $sql = new db_getContaBancoList; $RS_Conta = $sql->getInstanceOf($dbms,$w_cliente,null,'FINANCEIRO');
  if (count($RS_Conta)>1) { 
    $w_exige_conta = true; 
  } else {
    $w_exige_conta = false;
    if (count($RS_Conta)==1) {
      foreach($RS_Conta as $row) $RS_Conta = $row;
      $w_conta = f($RS_Conta,'sq_pessoa_conta');
    }
  }
  // Fundo fixo n�o pode ter altera��o da conta banc�ria no encerramento
  $w_exige_conta = false;
  
  // Se ligado a projeto, recupera rubricas
  $sql = new db_getSolicRubrica; $RS_Rub = $sql->getInstanceOf($dbms,f($RS_Solic,'sq_solic_pai'),null,'S',null,null,null,null,null,'SELECAO');

  // Recupera os documentos do lan�amento
  $sql = new db_getLancamentoDoc; $RS_Doc = $sql->getInstanceOf($dbms,$w_chave,null,null,null,null,null,null,'DOCS');
    
  if (count($RS_Doc)>0) {
    foreach($RS_Doc as $row) {
      $sql = new db_getLancamentoItem; $RS_Item = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
      foreach($RS_Item as $row1) {
        $w_sq_projeto_rubrica = f($row1,'sq_projeto_rubrica');
        break;
      }
      break;
    }
  }
  
  // Recupera o saldo do petty cash
  $sql = new db_getLinkData; $RSL = $sql->getInstanceOf($dbms,$w_cliente,'FNDFUNDO');
  $sql = new db_getSolicFN; $RS1 = $sql->getInstanceOf($dbms,f($RSL,'sq_menu'),$w_usuario,null,3,
            null,null,null,null,null,null, null,null,null,null, null, null, null, null, null, null, null,
            null, null, null, null, null, $w_chave, null, null, null, null);
  foreach ($RS1 as $row)  $w_saldo -= Nvl(f($row,'valor'),0);
  
  // Retorna as contas cont�beis do lan�amento
  retornaContasContabeis($RS_Menu, $w_cliente, $w_sq_tipo_lancamento, f($RS_Solic,'sq_forma_pagamento'), $w_conta, $w_cc_debito, $w_cc_credito);
  
  // Se for envio, executa verifica��es nos dados da solicita��o
  $w_erro = ValidaFundoFixo($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($w_erro=='' || substr(Nvl($w_erro,'-'),0,1)!='0') {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    FormataDataHora();
    FormataValor();
    ValidateOpen('Validacao');
    if (count($RS_Rub)>0) Validate('w_sq_projeto_rubrica','Rubrica', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_quitacao','Data do encerramento', 'DATA', 1, 10, 10, '', '0123456789/');
    CompData('w_quitacao','Data do encerramento','<=',FormataDataEdicao(time()),'data atual');
    if (w_sg_forma_pagamento=='DEPOSITO') Validate('w_codigo_deposito','C�digo do dep�sito', '1', '1', 1, 50, '1', '1');
    if ($w_exige_conta) Validate('w_conta','Conta banc�ria', 'SELECT', 1, 1, 18, '', '0123456789');
    Validate('w_observacao','Observa��o', '', '', '1', '500', '1', '1');
    
    Validate('w_cc_debito','Conta D�bito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    Validate('w_cc_credito','Conta Cr�dito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    ShowHTML('  if ((theForm.w_cc_debito.value != "" && theForm.w_cc_credito.value == "") || (theForm.w_cc_debito.value == "" && theForm.w_cc_credito.value != "")) {');
    ShowHTML('     alert ("Informe ambas as contas cont�beis ou nenhuma delas!");');
    ShowHTML('     theForm.w_cc_debito.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
    BodyOpen('onLoad=\'document.Form.Botao.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualFundoFixo($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.substr($SG,0,3).'CONC&O='.$O.'&w_menu='.$w_menu.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
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
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS_Solic,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  if ($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0') {
     ShowHTML('    <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>N�o � poss�vel registrar o pagamento enquanto as corre��es listadas n�o forem feitas.</b></font></td>');
     ShowHTML('    <tr><td colspan="4" align="center" colspan=4><hr>');
     ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    ShowHTML('      <tr><td colspan="4" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b><font color="#BC3131">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de '.formatNumber((f($RS,'upload_maximo')/1024),0).' KBytes</b>.</font></td>');
    ShowHTML('<INPUT type="hidden" name="w_upload_maximo" value="'.f($RS,'upload_maximo').'">');
    // Fundo fixo n�o � classificado. Somente suas despesas.
    $sql = new db_getTipoLancamento; $l_RS = $sql->getInstanceOf($dbms,null,$chaveAux,$w_cliente,'REEMBOLSO');
    $l_RS = SortArray($l_RS,'nm_tipo','asc');
    foreach($l_RS as $row) {
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_lancamento" value="'.f($row,'chave').'">');
      break;
    }
    if(count($RS_Rub)>0) {
      ShowHTML('      <tr>');
      SelecaoRubrica('<u>R</u>ubrica:','R', 'Selecione a rubrica do projeto.', $w_sq_projeto_rubrica,f($RS_Solic,'sq_solic_pai'),null,'w_sq_projeto_rubrica','SELECAO',null,3);
      ShowHTML('      </tr>');
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>D</u>ata do encerramento:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_quitacao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.Nvl($w_quitacao,$w_inicio).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Informe a data de pagamento deste lan�amento.">'.ExibeCalendario('Form','w_quitacao').'</td>');
    ShowHTML('        <td><b>Valor sacado:<br>'.formatNumber(f($RS_Solic,'valor')).'</b></td>');
    ShowHTML('        <td><b>Valor utilizado:<br>'.formatNumber(f($RS_Solic,'valor')-$w_saldo).'</b></td>');
    ShowHTML('        <td><b>Saldo a depositar:<br>'.formatNumber($w_saldo).'</b></td>');
    ShowHTML('<INPUT type="hidden" name="w_valor_real" value="'.$w_valor_real.'">');
    if ($w_sg_forma_pagamento=='DEPOSITO') {
      ShowHTML('        <td><b><u>C</u>�digo do dep�sito:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo_deposito" class="sti" SIZE="20" MAXLENGTH="50" VALUE="'.$w_codigo_deposito.'" title="Informe o c�digo do dep�sito identificado."></td>');
    }
    if ($w_exige_conta) {
      SelecaoContaBAnco('C<u>o</u>nta sacada:','O','Selecione a conta banc�ria utilizada para o saque do petty cash .',$w_conta,null,'w_conta',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_conta" value="'.$w_conta.'">');
    }
    ShowHTML('      <tr><td colspan="4"><b>Obs<u>e</u>rva��o:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_observacao" class="sti" ROWS=5 cols=75 title="Descreva o quanto a demanda atendeu aos resultados esperados.">'.$w_observacao.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="4"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="file" name="w_caminho" class="sti" SIZE="80" MAXLENGTH="100" VALUE="" title="OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor.">');

    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>C</u>onta cont�bil de d�bito:</b></br><input type="text" name="w_cc_debito" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.$w_cc_debito.'"></td>');
    ShowHTML('        <td><b><u>C</u>onta cont�bil de cr�dito:</b></br><input type="text" name="w_cc_credito" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.$w_cc_credito.'"></td>');

    ShowHTML('      <tr colspan="4"><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
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
// Rotina de prepara��o para envio de e-mail relativo a lan�amentos
// Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
// Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
//            p_tipo:  1 - Inclus�o
//                     2 - Tramita��o
//                     3 - Conclus�o
// -------------------------------------------------------------------------
function SolicMail($p_solic,$p_tipo) {
  extract($GLOBALS);
  global $w_Disabled;
  //Verifica se o cliente est� configurado para receber email na tramita�ao de solicitacao
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  $sql = new db_getSolicData; $RSM = $sql->getInstanceOf($dbms,$p_solic,substr(f($RS_Menu,'sigla'),0,3).'GERAL');
  if(f($RS,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RSM,'envia_mail')=='S')) {
    $l_solic          = $p_solic;
    $w_destinatarios  = '';
    $w_resultado      = '';
    // Recupera os dados da tarefa
    $w_html='<HTML>'.$crlf;
    $w_html.=BodyOpenMail(null).$crlf;
    $w_html.='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
    $w_html.='<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
    $w_html.='    <table width="97%" border="0">'.$crlf;
    if ($p_tipo==1) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>INCLUS�O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==2) {
      $w_html.='      <tr valign="top"><td align="center"><font size=2><b>TRAMITA��O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } elseif ($p_tipo==3) {
     $w_html.='      <tr valign="top"><td align="center"><font size=2><b>CONCLUS�O DE '.upper(f($RSM,'nome')).'</b></font><br><br><td></tr>'.$crlf;
    } 
    $w_html.='      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></td>'.$crlf;
    $w_nome=f($RSM,'nome').' '.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')';
    $w_html.=$crlf.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html.=$crlf.'    <table width="99%" border="0">';
    $w_html.=$crlf.'      <tr><td>Tipo de lan�amento: <b>'.f($RSM,'nm_tipo_lancamento').' </b></td>';
    $w_html.=$crlf.'      <tr><td>Finalidade: <b>'.f($RSM,'codigo_interno').' ('.f($RSM,'sq_siw_solicitacao').')<br>'.CRLF2BR(f($RSM,'descricao')).'</b></td></tr>';
    // Identifica��o do contrato
    $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>EXTRATO DO LAN�AMENTO</td>';
    if (Nvl(f($RSM,'nm_projeto'),'')>'') $w_html.=$crlf.'      <tr><td>Projeto: <br><b>'.f($RSM,'nm_projeto').'  ('.f($RSM,'sq_solic_pai').')</b></td>';
    // Se a classifica��o foi informada, exibe.
    if (Nvl(f($RSM,'sq_cc'),'')>'') {
      $w_html.=$crlf.'      <tr><td>Classifica��o:<br><b>'.f($RSM,'nm_cc').' </b></td>';
    } 
    $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
    $w_html.=$crlf.'          <tr valign="top">';
    $w_html.=$crlf.'          <td>Forma de pagamento:<br><b>'.f($RSM,'nm_forma_pagamento').' </b></td>';
    $w_html.=$crlf.'          <td>Vencimento:<br><b>'.FormataDataEdicao(f($RSM,'vencimento')).' </b></td>';
    $w_html.=$crlf.'          <td>Valor:<br><b>'.formatNumber(Nvl(f($RSM,'valor'),0)).' </b></td>';
    $w_html.=$crlf.'          </table>';
    // Outra parte
    $sql = new db_getBenef; $RSM1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RSM,'pessoa'),0),null,null,null,null,Nvl(f($RSM,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null, null, null, null, null);
    if (count($RSM1) > 0) {
      foreach ($RSM1 as $row)
      $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRA PARTE</td>';
      $w_html.=$crlf.'      <tr><td><b>';
      $w_html.=$crlf.'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
      if (Nvl(f($RSM,'sq_tipo_pessoa'),0)==1) {
        $w_html.=$crlf.'          - '.f($row,'cpf'); 
      } else {
        $w_html.=$crlf.'          - '.f($row,'cnpj');
      } 
    } 
    if ($p_tipo==3) {
      // Se for conclus�o
      // Dados da conclus�o do lan�amento, se ela estiver nessa situa��o
      if (Nvl(f($RSM,'conclusao'),'')>'' && Nvl(f($RSM,'quitacao'),'')>'') {
        $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>DADOS DO PAGAMENTO</td>';
        $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
        $w_html.=$crlf.'          <tr valign="top">';
        $w_html.=$crlf.'          <td>Data:<br><b>'.FormataDataEdicao(f($RSM,'quitacao')).' </b></td>';
        if (Nvl(f($RSM,'codigo_deposito'),'')>'') $w_html.=$crlf.'          <td>C�digo do dep�sito:<br><b>'.f($RSM,'codigo_deposito').' </b></td>';
        $w_html.=$crlf.'          </table>';
        $w_html.=$crlf.'      <tr><td>Observa��o:<br><b>'.CRLF2BR(Nvl(f($RSM,'observacao'),'---')).' </b></td>';
      } 
      if (Nvl(f($RSM,'nm_cc'),'')>'') {
        // Se for vinculado a classifica��o, envia aos que participaram da tramita��o
        $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
        $RS = SortArray($RS,'phpdt_data','desc');
        foreach($RS as $row) {
          if (f($row,'sq_pessoa_destinatario')>'') {
            // Configura os destinat�rios da mensagem
            $sql = new db_getPersonData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,nvl(f($RS,'sq_pessoa_destinatario'),0),null,null);
            $w_destinatarios .= f($RS1,'email').'|'.f($RS1,'nome').'; ';          } 
        } 
      } else {
        // Caso contr�rio envia para o respons�vel pelo projeto 
        $sql = new db_getUorgResp; $RS = $sql->getInstanceOf($dbms,f($RSM,'sq_unidade'));
        foreach($RS as $row){$RS=$row; break;}
        if(f($RS,'st_titular')=='S')    $w_destinatarios .= f($RS,'email_titular').'|'.f($RS,'nm_titular').'; ';
        if(f($RS,'st_substituto')=='S') $w_destinatarios .= f($RS,'email_substituto').'|'.f($RS,'nm_substituto').'; ';
      }
    }
    //Recupera o �ltimo log
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$p_solic,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','desc');
    foreach ($RS as $row) { $RS = $row; if(nvl(f($row,'destinatario'),'')!='') break; }
    $w_data_encaminhamento = f($RS,'phpdt_data');
    if ($p_tipo==2) {
      $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>�LTIMO ENCAMINHAMENTO</td>';
      $w_html.=$crlf.'      <tr><td><table border=0 width="100%" cellspacing=0>';
      $w_html.=$crlf.'          <tr valign="top">';
      $w_html.=$crlf.'          <td>De:<br><b>'.f($RS,'responsavel').'</b></td>';
      $w_html.=$crlf.'          <td>Para:<br><b>'.f($RS,'destinatario').'</b></td>';
      $w_html.=$crlf.'          <tr valign="top"><td colspan=2>Despacho:<br><b>'.CRLF2BR(Nvl(f($RS,'despacho'),'---')).' </b></td>';
      $w_html.=$crlf.'          </table>';
      // Configura o destinat�rio da tramita��o como destinat�rio da mensagem
      $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,f($RS,'sq_pessoa_destinatario'),null,null);
      $w_destinatarios .= f($RS,'email').'|'.f($RS,'nome').'; ';
    } 
    $w_html.=$crlf.'      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>OUTRAS INFORMA��ES</td>';
    $sql = new db_getCustomerSite; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Para acessar o sistema use o endere�o: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='      <tr valign="top"><td>'.$crlf;
    $w_html.='         Dados da ocorr�ncia:<br>'.$crlf;
    $w_html.='         <ul>'.$crlf;
    $w_html.='         <li>Respons�vel: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
    $w_html.='         <li>Data<b> '.date('d/m/Y, H:i:s',$w_data_encaminhamento).'</b></li>'.$crlf;
    $w_html.='         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
    $w_html.='         </ul>'.$crlf;
    $w_html.='      </td></tr>'.$crlf;
    $w_html.='    </table>'.$crlf;
    $w_html.='</td></tr>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</table>'.$crlf;
    $w_html.='</BODY>'.$crlf;
    $w_html.='</HTML>'.$crlf;
    // Prepara os dados necess�rios ao envio
    if ($p_tipo==1 || $p_tipo==3) {
      // Inclus�o ou Conclus�o
      if ($p_tipo==1) {
        $w_assunto = 'Inclus�o - '.$w_nome;
      } else {
        $w_assunto = 'Conclus�o - '.$w_nome;
      }
    } elseif ($p_tipo==2) {
      // Tramita��o
      $w_assunto = ' Tramita��o - '.$w_nome;
    } 
    if ($w_destinatarios>'') {
      // Executa o envio do e-mail
      $w_resultado = EnviaMail($w_assunto,$w_html,$w_destinatarios,null);
    } 
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if (Nvl($w_resultado,'')>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'");');
      ScriptClose();
    } 
  }
}
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  if (strpos($SG,'FIXO')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

      if (nvl($_REQUEST['w_conta'],'')!='') {
        // Recupera os dados da conta
        $sql = new db_getContaBancoList; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_conta'],'FINANCEIRO');
        foreach($RS2 as $row) { $RS2 = $row; break; }
        $w_moeda            = f($RS2,'sq_moeda');
      }

      $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_REQUEST['w_sq_unidade'],$_REQUEST['w_solicitante'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_vencimento'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          Nvl($_REQUEST['w_sq_forma_pagamento'],''),$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_forma_atual'],
          $_REQUEST['w_vencimento_atual'],$_REQUEST['w_tipo_rubrica'],nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
          $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_texto_pagamento'],null,$_REQUEST['w_sq_projeto_rubrica'],
          $_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],$_REQUEST['w_texto_autorizacao'],$w_moeda,
          $w_chave_nova, $w_codigo);
        
      if ($O!='E') {
        // Grava contas cont�beis
        $sql = new dml_putContaContabil; $sql->getInstanceOf($dbms,$w_usuario,$w_chave_nova,$_REQUEST['w_cc_debito'],$_REQUEST['w_cc_credito']);

        // Recupera o benefici�rio do fundo fixo (chamado de "Suprido")
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_solicitante'],null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
        foreach ($RS as $row) {$RS=$row; break;}

        if (count($RS)) {
          // Se o benefici�rio j� existe, recupera os dados banc�rios
          $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
          $w_pessoa_atual     = f($RS1,'pessoa');
          $w_conta            = f($RS1,'sq_pessoa_conta');
          $w_sq_banco         = f($RS1,'sq_banco');
          $w_sq_agencia       = f($RS1,'sq_agencia');
          $w_operacao         = f($RS1,'operacao_conta');
          $w_nr_conta         = f($RS1,'numero_conta');
          $w_sq_pais_estrang  = f($RS1,'sq_pais_estrang');
          $w_aba_code         = f($RS1,'aba_code');
          $w_swift_code       = f($RS1,'swift_code');
          $w_endereco_estrang = f($RS1,'endereco_estrang');
          $w_banco_estrang    = f($RS1,'banco_estrang');
          $w_agencia_estrang  = f($RS1,'agencia_estrang');
          $w_cidade_estrang   = f($RS1,'cidade_estrang');
          $w_informacoes      = f($RS1,'informacoes');
          $w_codigo_deposito  = f($RS1,'codigo_deposito');

          //Grava os dados da pessoa
          $SQL = new dml_putLancamentoOutra; $SQL->getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,$_REQUEST['w_solicitante'],f($RS,'cpf'),f($RS,'cnpj'),
              null,null,null,null,null,null,null,null,null,null,f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),f($RS,'sq_cidade'),
              f($RS,'cep'),f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'), $w_sq_agencia, $w_operacao, 
              $w_nr_conta, $w_sq_pais_estrang, $w_aba_code, $w_swift_code, $w_endereco_estrang, $w_banco_estrang, $w_agencia_estrang, 
              $w_cidade_estrang, $w_informacoes, $w_codigo_deposito, $w_pessoa_atual, $_REQUEST['w_conta']);
        }

        //Grava os dados do comprovante de despesa
        $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$w_chave_nova,$_REQUEST['w_chave_doc'],$_REQUEST['w_sq_tipo_documento'],
          $_REQUEST['w_numero'],$_REQUEST['w_data'],$_REQUEST['w_serie'],$w_moeda,$_REQUEST['w_valor'],
          'N','N','N',null,null,null,null, $w_chave_doc);

        if (is_array($_REQUEST['w_moeda'])) {
          // Remove as cota��es existentes
          $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'E',$w_chave_nova,null,null);

          // Insere a cota��o da moeda da solicita��o
          $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'I',$w_chave_nova,$w_moeda,$_REQUEST['w_valor']);

          // Insere as cota��es das moedas da solicita��o pai e da conta banc�ria, desde que sejam diferentes da moeda da solicita��o
          foreach($_REQUEST['w_moeda'] as $k=>$v) {
            $SQL = new dml_putSolicCotacao; $SQL->getInstanceOf($dbms,'I',$w_chave_nova,$v,$_REQUEST['w_valor'.'_'.$v]);
          }
        }
        
        // Verifica o n�mero de tr�mites ativos. Se houver somente um, recupera o tr�mite de conclus�o
        $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null,null,null);
        $RS = SortArray($RS,'ordem','asc');
        $w_cont = 0;
        foreach ($RS as $row) {
          // Recupera os tr�mites de cadastramento inicial, de execu��o e de conclus�o
          if (f($row,'ativo')=='S')  $w_cont++;
          if     (f($row,'sigla')=='AT') $w_tramite_conc = f($row,'sq_siw_tramite');
          elseif (f($row,'sigla')=='CI') $w_ci = f($row,'sq_siw_tramite');
          elseif (f($row,'sigla')=='EE') $w_ee = f($row,'sq_siw_tramite');
        }   

        if ($w_cont==1 || $P1==0) {
          // Grava vers�o da solicita��o
          $w_html = VisualFundoFixo($w_chave_nova,'L',$w_usuario,2,'1');
          CriaBaseLine($w_chave_nova,$w_html,f($RS_Menu,'nome'),$w_ee);

          if ($w_cont==1) {
            // Encerra a solicita��o se houver apenas um tr�mite ativo
            $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$w_chave_nova,$w_usuario,$w_tramite_conc,
              formataDataEdicao(time()),Nvl($_REQUEST['w_valor'],0),null,$_REQUEST['w_conta'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
              $_REQUEST['w_sq_projeto_rubrica'],'Conclus�o autom�tica de pagamento por fundo fixo.',$_REQUEST['w_cc_debito'],$_REQUEST['w_cc_credito'],
              null,null,null,null);
          } else {
            if ($P1==0) {
              // Envia a solicita��o para execu��o   
              $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$w_chave_nova,$w_usuario,$w_ci,
                      $w_ee,'N',null,$w_usuario,'Envio autom�tico de lan�amento financeiro.',null,null,null,null);
            }
          }
        }
      }
      ScriptOpen('JavaScript');
      if ($P1==0) {
        if ($P2==1) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'geral&O=A&w_chave='.nvl($_REQUEST['w_chave'],$w_chave_nova).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').'&R='.$R.MontaFiltro('GET')).'\';');
        } else {
          // Volta para o m�dulo tesouraria
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        }
      } elseif ($P1==1 && $O!='E') {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$w_pagina.'geral&O=A&w_chave='.nvl($_REQUEST['w_chave'],$w_chave_nova).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').'&R='.$R.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'ENVIO')===false)) {
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
              ShowHTML('  alert("Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!");');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!");');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
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
          $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if($P1!=1 && nvl($_REQUEST['w_tramite'],0)!=nvl($_REQUEST['w_novo_tramite'],0)) {
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualFundoFixo($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!");');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        if ($P1==0) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        } elseif ($P2==1) {
          ShowHTML('  parent.location.reload();');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      } else {
        $sql = new db_getSolicData;
        $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_chave'], $SG);
        if (f($RS, 'sq_siw_tramite') != $_REQUEST['w_tramite']) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATEN��O: Outro usu�rio j� encaminhou este contrato para outra fase!");');
          ScriptClose();
        } else {
          $SQL = new dml_putLancamentoEnvio;
          $SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, $_REQUEST['w_tramite'],
                  $_REQUEST['w_novo_tramite'], 'N', $_REQUEST['w_observacao'], $_REQUEST['w_destinatario'], $_REQUEST['w_despacho'],
                  null, null, null, null);
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if ($_REQUEST['w_tramite'] != $_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData;
            $RS = $sql->getInstanceOf($dbms, $_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS, 'sigla');
            if ($w_sg_tramite == 'CI') {
              $w_html = VisualFundoFixo($_REQUEST['w_chave'], 'L', $w_usuario, $P1, '1');
              CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
            }
          }
          ScriptOpen('JavaScript');
          if ($P1 == 0) {
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, 'tesouraria.php?par=inicial&O=L&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . MontaFiltro('GET')) . '\';');
          } else {
            ShowHTML('  location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '\';');
          }
          ScriptClose();
        }
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'ENVAUT')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      $SQL = new dml_putSolicEnvio;
      if ($_REQUEST['w_envio']=='N') {
        $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } else {
        $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } 
      //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
      if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
        $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
        $w_sg_tramite = f($RS,'sigla');
        if($w_sg_tramite=='CI') {
          ShowHTML(VisualFundoFixo($_REQUEST['w_chave'],'V',$w_usuario,$P1,$P4));
          CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
        }
      }  
      // Envia e-mail comunicando o envio
      SolicMail($_REQUEST['w_chave'],2);
      // Se for envio da fase de cadastramento, remonta o menu principal
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
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
              // Verifica se a solicita��o atende �s exig�ncias para envio
              $w_erro = ValidaFundoFixo($w_cliente,$w_chave,$_POST['p_agrega'],null,null,null,$w_tramite);
              if (substr(Nvl($w_erro,'nulo'),0,1)=='0') {
                echo '<td>'.substr($w_erro,1).'</td>';
              } else {
                // Envia a solicita��o
                $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_POST['w_menu'],$w_chave,$w_usuario,
                  $w_tramite,null,$_POST['w_envio'],$_POST['w_despacho'],null,null,null,null);

                // Envia e-mail comunicando o envio
                SolicMail($w_chave,2);

                echo '<td>Enviado</td>';
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
              $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_POST['w_menu'],$w_chave,$w_usuario,
                $w_tramite,$w_novo_tramite,$_POST['w_envio'],$_POST['w_despacho'],null,null,null,null);

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
  } elseif (!(strpos($SG,'CONC')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$SG);
      if (f($RS,'sq_siw_tramite')!=$_REQUEST['w_tramite']) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("ATEN��O: Outro usu�rio j� encaminhou este contrato para outra fase!");');
        ScriptClose();
      } else {
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert("Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!");');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert("Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!");');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
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
          $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
            $_REQUEST['w_quitacao'],$_REQUEST['w_valor_real'],$_REQUEST['w_codigo_deposito'],$_REQUEST['w_conta'],$_REQUEST['w_sq_tipo_lancamento'],
            $_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_observacao'],$_REQUEST['w_cc_debito'],$_REQUEST['w_cc_credito'],
            $w_file,$w_tamanho,$w_tipo,$w_nome);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert("ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!");');
          ScriptClose();
        } 
      } 
      // Volta para a listagem
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
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
    case 'INICIAL':         Inicial();          break;
    case 'GERAL':           Geral();            break;
    case 'VISUAL':          Visual();           break;
    case 'EXCLUIR':         Excluir();          break;
    case 'ENVIO':           Encaminhamento();   break;
    case 'ANOTACAO':        Anotar();           break;
    case 'CONCLUIR':        Concluir();         break;
    case 'GRAVA':           Grava();            break;
    default:
      cabecalho();
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
  } 
}
?>