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
include_once($w_dir_volta.'classes/sp/db_getUserList.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getCodigo.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicDados.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
// =========================================================================
//  /alteracao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Altera dados b�sicos de uma solicita��o
// Mail     : alex@sbpi.com.br
// Criacao  : 09/09/2013, 11:44
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
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$p_ordena   = $_REQUEST['p_ordena'];
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'alteracao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_sg/';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O="P";
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();

FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de altera��o de dados
// -------------------------------------------------------------------------
function Geral() {
  extract($GLOBALS);
  $w_servico          = $_REQUEST['w_servico'];
  $w_interno          = $_REQUEST['w_interno'];
  
  if (nvl($w_servico,'')!='') {
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_servico,null, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) { $w_tramite = f($row,'sq_siw_tramite'); break; }   
  }
  
  if (nvl($w_interno,'')!='') {
    // Se recebeu c�digo de solicita��o, verifica se existe no banco de dados.
    $sql = new db_getCodigo; $RS = $sql->getInstanceOf($dbms,$w_cliente,'SOLICITACAO',$w_interno,$w_servico);
    if (count($RS)==1) {
      foreach ($RS as $row) { $RS_Solic = $row; break; }
      // Guarda o tipo da solicita��o e do pai 
      $w_sg_reg = piece(f($RS_Solic,dados_reg),null,'|@|',6);
      $w_sg_pai = piece(f($RS_Solic,dados_pai),null,'|@|',6);
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('alert("'.((count($RS)==0) ? 'Nenhum registro encontrado!' : 'ATEN��O: foi encontrado mais de um registro com o c�digo informado!').'!");');
      ShowHTML('history.back(1);');
      ScriptClose();
      exit;
    } 
  }

  if ($w_troca!='') {
    $w_chave                = $_REQUEST['w_chave'];
    $w_externo              = $_REQUEST['w_externo'];
    $w_cadastrador          = $_REQUEST['w_cadastrador'];
    $w_observacao           = $_REQUEST['w_observacao'];
    $w_sq_projeto_rubrica   = $_REQUEST['w_sq_projeto_rubrica'];
  } elseif (nvl($w_interno,'')!='') {
    $w_chave       = f($RS_Solic,'chave');
    $w_externo     = f($RS_Solic,'codigo_externo');
    $w_cadastrador = f($RS_Solic,'cadastrador');
    $w_nm_menu     = f($RS_Solic,'nm_menu');
  }

  Cabecalho();
  head();
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  if ($O == 'P') {
    Validate('w_servico', 'Servi�o', 'SELECT', 1, 1, 18, '1', '1');
    Validate('w_interno', 'C�digo interno', '1', 1, 1, 30, '1', '1');
    ShowHTML('  document.Form.Botao.disabled = true;');
  } else {
    Validate('w_externo', 'C�digo externo', '1', '', 1, 30, '1', '1');
    Validate('w_cadastrador', 'Cadastrador', 'SELECT', 1, 1, 18, '1', '1');
    Validate('w_observacao', 'Observa��o', '', '', 5, 2000, '1', '1');
    Validate('w_assinatura', $_SESSION['LABEL_ALERTA'], '1', '1', '6', '30', '1', '1');
    ShowHTML('  document.Form.Botao[0].disabled = true;');
    ShowHTML('  document.Form.Botao[1].disabled = true;');
  }
  ValidateClose();
  ScriptClose();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  if ($w_troca > '') {
    BodyOpen('onLoad="this.focus();"');
  } elseif ($O == 'P') {
    BodyOpen('onLoad="document.Form.w_servico.focus();"');
  } else {
    BodyOpen('onLoad="document.Form.w_externo.focus();"');
  }
  ShowHTML('<b><FONT COLOR="#000000">' . $w_TP . '</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, 'A');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4">Informe a solicita��o a ser alterada.</td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    selecaoServico('<U>S</U>ervi�o:', 'S', null, $w_servico, $w_usuario, f($RS_Menu, 'sq_modulo'), 'w_servico', 'LISTA', null, 'S', 'S', 'S');
    ShowHTML('        <td><b>C�digo <u>i</u>nterno:</b><br><input ' . $w_Disabled . ' accesskey="I" type="text" name="w_interno" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_interno . '" title="Indique o c�digo da solicita��o que ter� seus dados alterados."></td>');
    ShowHTML('      <tr><td align="center" colspan="4" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="4"><input class="STB" type="submit" name="Botao" value="Procurar"></td></tr>');
  } else {
    AbreForm('Form', $w_dir . $w_pagina . 'Grava', 'POST', 'return(Validacao(this));', null, $P1, $P2, $P3, $P4, $TP, $SG, $w_pagina . $par, $O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="' . $w_chave . '">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="' . $w_menu . '">');
    ShowHTML('<INPUT type="hidden" name="w_servico" value="' . $w_servico . '">');
    ShowHTML('<INPUT type="hidden" name="w_interno" value="' . $w_interno . '">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4">Solicita��o a ser alterada.</td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td>Tipo:<br><b>'.$w_nm_menu.'</b></td>');
    ShowHTML('        <td>Codigo interno:<br><b>'.$w_interno.' ('.$w_chave.')</b></td>');
    If (nvl(f($RS_Solic,'dados_pai'),'')!='') ShowHTML('        <td>Vincula��o:<br><b>'.piece(f($RS_Solic,'dados_pai'),null,'|@|',2).' </b></td>');
    ShowHTML('      </tr>');

    ShowHTML('      <tr><td colspan="4" align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4" valign="top" align="center" bgcolor="#D0D0D0"><b>Ajuste de dados</td></td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan="4">Informe os novos valores desejados para os campos abaixo. Ser� gravado log das altera��es nos dados atuais.</td></tr>');
    ShowHTML('      <tr><td colspan="4" align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b>C�digo <u>e</u>xterno:</b><br><input ' . $w_Disabled . ' accesskey="E" type="text" name="w_externo" class="sti" SIZE="30" MAXLENGTH="30" VALUE="' . $w_externo . '" title="Indique o c�digo da solicita��o em um sistema externo."></td>');
    SelecaoSolicResp('<u>C</u>adastrador:','C','Selecione, na rela��o, o respons�vel pelo cumprimento do tr�mite de cadastramento.', $w_cadastrador, $w_chave, $w_tramite, $w_tramite, 'w_cadastrador', 'CADASTRAMENTO');
    ShowHTML('      <tr><td colspan="4"><b><u>O</u>bserva��o:</b><br><textarea class="STI" accesskey="O" ' . $w_Disabled . ' name="w_observacao" class="STI" ROWS=5 cols=75 title="Se desejar, informe uma observa��o para o ajuste de dados.">' . $w_observacao . '</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="4" height="1" bgcolor="#000000"></TD></TR>');

    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="4">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=P&SG=' . f($RS_Menu, 'sigla') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
  }
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('<base HREF="' . $conRootSIW . '">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'AJUSTE' :
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura == '') {
        // Grava o novo respons�vel pela presta��o de contas
        $SQL = new dml_putSolicDados; $SQL->getInstanceOf($dbms,$O,$w_cliente,
                $_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_cadastrador'],$_REQUEST['w_externo'],$_REQUEST['w_observacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Altera��o efetivada!");');
        ShowHTML('  location.href="' . montaURL_JS($w_dir, f($RS_Menu, 'link') . '&O=P&SG=' . f($RS_Menu, 'sigla') . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . MontaFiltro('GET')) . '";');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      }
      break;
  }
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'GERAL':               Geral();    break;
    case 'GRAVA':               Grava();   break;
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