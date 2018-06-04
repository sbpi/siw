<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putNovoTramite.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');

// =========================================================================
//  /enviaSolic.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Rotina de envio de solicitações
// Mail     : alex@sbpi.com.br
// Criacao  : 07/08/2017, 08:42
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
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina     = 'enviaSolic.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'funcoes/';
$w_troca      = $_REQUEST['w_troca'];

$w_chave  = $_REQUEST['w_chave'];

if (nvl($w_chave,0)==0) {
  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'"></HEAD>');
  BodyOpen('onLoad=this.focus();');
  Estrutura_Texto_Abre();
  ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Chave da solicitação não informada!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
  Estrutura_Texto_Fecha();
  exibevariaveis();
  die();
}

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

$w_TP=$TP.' - Envio';

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_ano      = RetornaAno();

// Identifica o tipo da solicitacao
$sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave);
$RS_SolicTipo = explode('|@|', f($RS,'dados_solic'));

$w_menu       = $RS_SolicTipo[3]  ;
$SG           = $RS_SolicTipo[5];
$w_visual     = $RS_SolicTipo[10];
$w_modulo     = $RS_SolicTipo[11];

//ExibeArray($w_menu);
//ExibeArray($w_visual);
//exibearray($RS);
//exibearray($RS_SolicTipo);
// Retorna os dados da solicitação
if (substr($RS_SolicTipo[5],0,2)=='CL') {
  include_once($w_dir_volta.'classes/sp/db_getSolicCL.php');  
  $sql = new db_getSolicCL; $RS_Solic = $sql->getInstanceOf($dbms,null,$_SESSION['SQ_PESSOA'],$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $w_chave,null,null,null,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null,
          null,null, null,null,null,null);
  $RS_Solic = $RS_Solic[0];
  
  if (substr($SG,0,4)=='CLLC') {
    include_once($w_dir_volta.'mod_cl/visualcertame.php');
    include_once($w_dir_volta.'mod_cl/validacertame.php');
    $w_erro = ValidaCertame($w_cliente,$w_chave,$SG,null,null,null,$w_sq_tramite_atual);
    $w_texto = VisualCertame($w_chave,'L',$w_usuario,$p1,'HTML');
  }
} else {
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,$SG);
}
//exibearray($RS_Solic);

$w_valor_projeto  = f($RS,'valor');
switch (f($RS_Solic,'sigla')) {
  case 'PJCAD':      $w_label = 'Projeto';       break;
  case 'PEPROCAD':   $w_label = 'Programa';      break;
  default:           $w_label = '???';
} 

$w_sq_tramite_atual     = f($RS_Solic,'sq_siw_tramite');
$w_sg_tramite_atual     = f($RS_Solic,'sg_tramite');

$w_novo_tramite         = $_REQUEST['w_novo_tramite'];


// Recupera os dados da opção selecionada
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
//exibearray($RS_Menu);

$sql = new db_getTramiteList; $RS_TramiteLista = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
$RS_TramiteLista = SortArray($RS_TramiteLista,'ordem','asc');
//exibearray($RS_TramiteLista);

// Recupera a sigla do trâmite atual e do desejado, para verificar a lista de possíveis destinatários.
$sql = new db_getTramiteData; $RS_TramiteAtual = $sql->getInstanceOf($dbms,$w_sq_tramite_atual);
//exibearray($RS_TramiteAtual);

if ($w_novo_tramite) {
  $sql = new db_getTramiteData; $RS_TramiteNovo = $sql->getInstanceOf($dbms,$w_novo_tramite);
}
  //exibearray($RS_TramiteNovo);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de envio
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  
  // Configura validação e visualização da solicitação
  $p1 = (($w_sg_tramite_atual=='CI') ? '1' : '2');
  
  if ($w_troca>'') {
    // Se for recarga da página
    $w_destinatario = $_REQUEST['w_destinatario'];
    $w_despacho     = $_REQUEST['w_despacho'];
  }
  
  //exibearray($RS_TramiteAtual);
  //exibearray($RS_TramiteNovo);
  
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_novo_tramite','Fase','HIDDEN','1','1','18','','1');
    Validate('w_destinatario','Destinatário','HIDDEN','1','1','10','','1');
    
    Validate('w_despacho','Despacho','',((f($RS_TramiteAtual,'ordem')>=nvl(f($RS_TramiteNovo,'ordem'),0)) ? '1' : ''),'1','2000','1','1');
    
    // Insere código específico da solicitação
    codigoEnvio('javascript');
    
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_novo_tramite.focus()\';');
  } 
  
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr><td align="center" colspan=2>');

  // Chama a rotina de visualização dos dados do projeto, na opção 'Listagem'
  ShowHTML($w_texto);
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');

  ShowHTML('<tr><td align="center" colspan=2>');
  ShowHTML('  <table width="97%" border="0" bgcolor="'.$conTrBgColor.'">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');

  // Insere código específico da solicitação
  codigoEnvio('corpo',1);

  ShowHTML('    <tr bgcolor="'.$conTrAlternateBgColor.'"><td colspan=2 style="border: 1px solid black;"><p><b>Fase atual:<b> '.f($RS_TramiteAtual,'ordem').' - '.f($RS_TramiteAtual,'nome').'</p></td>');
  ShowHTML('    <tr valign="top">');
  if ($P1!=1) {
    // Se não for cadastramento
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase destino:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_menu,null,'w_novo_tramite','FLUXO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase destino:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_sq_tramite_atual,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 

    // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
    if ($w_sg_tramite_atual=='CI') {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione, na relação, um destinatário.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','CADASTRAMENTO');
    } else {
      SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o projeto na relação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
    } 
  } else {
    if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
      SelecaoFase('<u>F</u>ase destino:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_sq_tramite_atual,null,'w_novo_tramite','FLUXO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } else {
      SelecaoFase('<u>F</u>ase destino:','F','Se deseja alterar a fase atual, selecione a fase para a qual deseja enviá-la.',$w_novo_tramite,$w_tramite,null,'w_novo_tramite','ERRO','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
    } 
    SelecaoSolicResp('<u>D</u>estinatário:','D','Selecione um destinatário para o projeto na relação.',$w_destinatario,$w_chave,$w_novo_tramite,$w_novo_tramite,'w_destinatario','USUARIOS');
  } 
  if (count($RS_TramiteNovo) && f($RS_TramiteNovo,'descricao')) {
    ShowHTML('    <tr><td colspan=2><img src="'.$conImgTurnRight.'"> '.f($RS_TramiteNovo,'descricao').'</td>');
    }
  ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho: (obrigatório se envio para fase anterior ou igual à atual)</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a ação esperada pelo destinatário.">'.$w_despacho.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
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

function codigoEnvio($bloco,$trecho='') {
  extract($GLOBALS);

  switch ($SG) {
    case 'CLLCCAD':
      if ($bloco=='javascript') javascriptLicitacao($trecho); else corpoLicitacao($trecho);  break;
  } 

}
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------

function javascriptLicitacao() {
  extract($GLOBALS);
  
  if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
    if (strpos($w_erro,'pesquisa')!==false) {
      Validate('w_just_pesquisa', 'Justificativa para o não cumprimento do número mínimo de pesquisas de preço', '', '1', '1', '2000', '1', '1');
    } 
    if (strpos($w_erro,'proposta')!==false) {
      Validate('w_just_proposta', 'Justificativa para o não cumprimento do número mínimo de propostas', '', '1', '1', '2000', '1', '1');
    } 
  }
  
}

function corpoLicitacao($trecho) {
  extract($GLOBALS);

  if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
    if (strpos($w_erro,'pesquisa')!==false) {
      ShowHTML('    <tr><td><b>Justificativa para o não cumprimento do número mínimo de pesquisas de preço:</b><br><textarea '.$w_Disabled.' name="w_just_pesquisa" class="STI" ROWS=5 cols=75>'.$w_just_pesquisa.'</TEXTAREA></td>');
    } 
    if (strpos($w_erro,'proposta')!==false) {
      ShowHTML('    <tr><td><b>Justificativa para o não cumprimento do número mínimo de propostas:</b><br><textarea '.$w_Disabled.' name="w_just_proposta" class="STI" ROWS=5 cols=75>'.$w_just_proposta.'</TEXTAREA></td>');
    } 
  } 
  
}



// =========================================================================
// Rotina de envio de e-mail comunicando ao destinatário o envio da solicitação
// Finalidade: preparar os dados necessários ao envio automático de e-mail
// Parâmetros: p_solic: número de identificação da solicitação. 
//             p_html:  visualização da solicitação
// -------------------------------------------------------------------------
function SolicMail($p_tipo, $p_destinatario, $p_html) {
  extract($GLOBALS);
  global $w_Disabled;
  //Verifica se o cliente está configurado para receber email na tramitaçao de solicitacao
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE']);
  
  if (1==1 || f($RS_TramiteNovo,'envia_mail_tramite')=='S' && (f($RS_Menu,'envia_email')=='S') && (f($RS_Solic,'envia_mail')=='S')) {
    $w_assunto = f($RS_Menu,'nome').' - '.f($RS_TramiteNovo,'nome');
    
    // Recupera o e-mail do destinatário do envio
    $sql = new db_getPersonData; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_destinatario,null,null);
    $w_destinatario = f($RS,'email').'|'.f($RS,'nome').'; ';
      
    $w_html.='<html>'.chr(13).chr(10);
    $w_html.='<head>'.chr(13).chr(10);
    $w_html.='<meta NAME="author" CONTENT="SBPI Consultoria Ltda" />'.chr(13).chr(10);
    $w_html.='<meta HTTP-EQUIV="CONTENT-LANGUAGE" CONTENT="pt-BR" />'.chr(13).chr(10);
    $w_html.='<meta HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=ISO-8859-1" />'.chr(13).chr(10);
    $w_html.='<base HREF="'.$conRootSIW.'">'.chr(13).chr(10);
    $w_html.='</head>'.chr(13).chr(10);
    $w_html.='<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css"/>'.chr(13).chr(10);
    $w_html.='<body>'.chr(13).chr(10);
    $w_html.='<div align="center">'.chr(13).chr(10);
    $w_html.='<table width="95%" border="0" cellspacing="3">'.chr(13).chr(10);
    $w_html.='<tr><td colspan="2">'.chr(13).chr(10);
    $w_html.=$p_html;
    $w_html.='</table>'.chr(13).chr(10);
    $w_html.='</div>'.chr(13).chr(10);
    $w_html.='</body>'.chr(13).chr(10);
    $w_html.='</html>'.chr(13).chr(10);
    $w_html = str_replace("display:none","",$w_html);
    $w_html = str_replace("mais.jpg","menos.jpg",$w_html);
    
    var_dump($w_html);
    ExibeVariaveis();
    
    // Executa o envio do e-mail
    $w_resultado = '';
    if ($w_destinatario>'') $w_resultado = EnviaMail($w_assunto,$p_html,$w_destinatario,$w_anexos);
    
    // Se ocorreu algum erro, avisa da impossibilidade de envio
    if ($w_resultado>'') {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: não foi possível proceder o envio do e-mail.\\n'.$w_resultado.'");');
      ScriptClose();
    } 
  } 
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  // Verifica se a Assinatura Eletrônica é válida
  if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
    if (f($RS_Solic, 'sq_siw_tramite') != $_REQUEST['w_novo_tramite']) {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("ATENÇÃO: Outro usuário já fez o encaminhamento para outra fase ou usuário!");');
      ShowHTML('  location.href="' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=L&w_chave=' . $_REQUEST['w_chave'] . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&SG=' . f($RS_Menu, 'sigla') . MontaFiltro('GET')) . '";');
      ScriptClose();
    } else {
      $SQL = new dml_putNovoTramite();
      //$SQL->getInstanceOf($dbms, $_REQUEST['w_menu'], $_REQUEST['w_chave'], $w_usuario, f($RS_Solic, 'sq_siw_tramite'), 
      //        $_REQUEST['w_novo_tramite'], $_REQUEST['w_destinatario'], $_REQUEST['w_tipo_log'], $_REQUEST['w_despacho'], 
      //        $_REQUEST['w_observacao'], $_REQUEST['w_justificativa1'], $_REQUEST['w_justificativa2']);
      
      // Grava imagem da versão da solicitacão no log.
      if (f($RS_Menu,'sg_modulo')=='CO') {
        $w_html = VisualCertame($_REQUEST['w_chave'], 'L', $w_usuario, null, '1');
      } elseif (f($RS_Menu,'sg_modulo')=='AC') {
        $w_html = VisualAcordo($_REQUEST['w_chave'], 'L', $w_usuario, '4', '1');
      }
      
      //CriaBaseLine($_REQUEST['w_chave'], $w_html, f($RS_Menu, 'nome'), $_REQUEST['w_tramite']);
      
      // Envia e-mail comunicando a tramitação
      SolicMail(2, $_REQUEST['w_destinatario'], $w_html);
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
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
    ScriptClose();
    retornaFormulario('w_assinatura');
  } 
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  global $w_Disabled;
  switch ($par) {
    case 'INICIAL':            Inicial();           break;
    case 'GRAVA':              Grava();             break;
    default:
    Cabecalho();
    head();
    ShowHTML('<BASE HREF="'.$conRootSIW.'"></HEAD>');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    exibevariaveis();
    break;
  } 
} 
?>