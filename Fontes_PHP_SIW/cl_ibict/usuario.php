<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getUserData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getUsuarioTemp.php');
include_once($w_dir_volta.'classes/sp/dml_putUsuarioTemp.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
$_SESSION['DBMS']=1;
// =========================================================================
//  /usuario.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos 
// Descricao: Gerencia o m�dulo de formul�rios do sistema
// Mail     : billy@sbpi.com.br
// Criacao  : 19/09/2006 16:00
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
//                   = V   : Envio
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

// Verifica se o usu�rio est� autenticado
//if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],0);
$P4         = nvl($_REQUEST['P4'],0);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$w_cliente  = 9234;
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'usuario.php?par=';
$w_Disabled     = false;
if ($O =='') $O = 'I';

Main();
FechaSessao($dbms);
// =========================================================================
// Rotina de benefici�rio
// -------------------------------------------------------------------------
function Benef() {
  extract($GLOBALS);
  global $w_Disabled;
  // Nesta rotina, P1 = 0 indica que n�o pode haver troca do benefici�rio
  //                  = 1 indica que pode haver troca de benefici�rio
  //               P2 = 0 indica que n�o pegar� os dados banc�rios, nem da forma de pagamento
  //                  = 1 indica que pegar� os dados banc�rios, mas n�o da forma de pagamento
  //                  = 2 indica que pegar� os dados banc�rios e tamb�m da forma de pagamento
  $w_readonly       = '';
  $w_erro           = '';
  $w_troca          = $_REQUEST['w_troca'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $w_cpf            = $_REQUEST['w_cpf'];
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if (($w_troca>'') && ($w_cpf>'')) {
    // Se for recarga da p�gina
    $w_Disabled = true;
    $RS = db_getUsuarioTemp::getInstanceOf($dbms,$w_cliente,$w_cpf,null);
    foreach($RS as $row){$RS=$row; break;}
    if (count($RS)>0) {
      ScriptOpen('JavaScript');
      ShowHTML('alert(\'Usu�rio j� cadastrado, aguarde carga dos dados!\');'); 
      ScriptClose();
      $O = 'A';
      $w_nome                 = f($RS,'nome');
      $w_nome_resumido        = f($RS,'nome_resumido');
      $w_sexo                 = f($RS,'sexo');
      $w_email                = f($RS,'email');
      $w_vinculo              = f($RS,'vinculo');
      $w_unidade              = f($RS,'unidade');
      $w_sala                 = f($RS,'sala');
      $w_ramal                = f($RS,'ramal');           
    } else {
      $O = 'I';
    }    
  }   
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>SIW - Cadastramento provis�rio de usu�rios</TITLE>');
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCEP();
  CheckBranco();
  FormataValor();
  FormataData();
  FormataDataHora();
  ShowHTML('function Validacao1(w_cpf) {');
  ShowHTML('  if (w_cpf == "") {');
  ShowHTML('    alert("Favor informar um valor para o campo CPF");');
  ShowHTML('    document.Form.w_cpf.focus();');
  ShowHTML('    return (false);');
  ShowHTML('  }');
  ShowHTML('  if (w_cpf.length < 14 && w_cpf != "") {');
  ShowHTML('    alert("Favor digitar pelo menos 14 posi��es no campo CPF");');
  ShowHTML('    document.Form.w_cpf.focus();');
  ShowHTML('    return (false);');
  ShowHTML('  }');
  ShowHTML('  if (w_cpf.length > 14 && w_cpf != "")');
  ShowHTML('  {');
  ShowHTML('    alert("Favor digitar no m�ximo 14 posi��es no campo CPF");');
  ShowHTML('    document.Form.w_cpf.focus();');
  ShowHTML('    return (false);');
  ShowHTML('  }');
  ShowHTML('  var checkOK = "0123456789-.";');
  ShowHTML('  var checkStr = w_cpf;');
  ShowHTML('  var allValid = true;');
  ShowHTML('  for (i = 0;  i < checkStr.length;  i++){');
  ShowHTML('    ch = checkStr.charAt(i);');
  ShowHTML('    if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != "\\\\")) {');
  ShowHTML('       for (j = 0;  j < checkOK.length;  j++) {');
  ShowHTML('         if (ch == checkOK.charAt(j))');
  ShowHTML('           break;');
  ShowHTML('       } ');
  ShowHTML('       if (j == checkOK.length)');
  ShowHTML('       {');
  ShowHTML('         allValid = false;');
  ShowHTML('         break;');
  ShowHTML('       }');
  ShowHTML('    } ');
  ShowHTML('  }');
  ShowHTML('  if (!allValid)');
  ShowHTML('  {');
  ShowHTML('    alert("Favor digitar apenas n�meros no campo CPF.");');
  ShowHTML('    document.Form.w_cpf.focus();');
  ShowHTML('    return (false);');
  ShowHTML('  }');
  ShowHTML('    var igual = 0;');
  ShowHTML('    var allValid = true;');
  ShowHTML('    var soma = 0;');
  ShowHTML('    var D1 = 0;');
  ShowHTML('    var D2 = 0;');
  ShowHTML('    var checkStr = w_cpf;');
  ShowHTML('    checkStr = checkStr.replace(".","");');
  ShowHTML('    checkStr = checkStr.replace(".","");');
  ShowHTML('    checkStr = checkStr.replace("-","");');
  ShowHTML('    igual = 0;');
  ShowHTML('    for (i = 1;  i < 10;  i++)');
  ShowHTML('    {');
  ShowHTML('      soma = soma + (checkStr.charAt(i-1)*(11-i));');
  ShowHTML('      if (checkStr.charAt(i) != checkStr.charAt(i-1)) igual = 1');
  ShowHTML('    }');
  ShowHTML('    if (igual == 0 && checkStr > "") {');
  ShowHTML('       alert("CPF inv�lido.");');
  ShowHTML('       document.Form.w_cpf.focus();');
  ShowHTML('       return (false);');
  ShowHTML('    }');
  ShowHTML('    D1 = modulo(soma,11);');
  ShowHTML('    if (D1 > 9) { D1 = 0}');
  ShowHTML('    soma = 0;');
  ShowHTML('    for (i = 1;  i < 11;  i++)');
  ShowHTML('    {');
  ShowHTML('      soma = soma + (checkStr.charAt(i-1)*(12-i));');
  ShowHTML('    }');
  ShowHTML('    D2 = modulo(soma,11)');
  ShowHTML('    if (D2 > 9) { D2 = 0}');
  ShowHTML('    if ((D1 == checkStr.charAt(10-1)) && (D2 == checkStr.charAt(11-1))) { allValid = true}');
  ShowHTML('    else { allValid = false }');
  ShowHTML('    if (!allValid && checkStr > "") {');
  ShowHTML('       alert("CPF inv�lido.");');
  ShowHTML('       document.Form.w_cpf.focus();');
  ShowHTML('       return (false);');
  ShowHTML('    }');
  ShowHTML('    if (igual == 0 && checkStr > "") {');
  ShowHTML('       alert("CPF inv�lido.");');
  ShowHTML('       document.Form.w_cpf.focus();');
  ShowHTML('       return (false);');
  ShowHTML('    }');
  ShowHTML('    document.Form.w_nome.focus();');
  ShowHTML('    return true;');
  ShowHTML('}');
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,15,'1','1');
    Validate('w_sexo','Sexo','SELECT','1','1','10','1','');
    Validate('w_email','E-Mail','1','1',4,50,'1','1');
    Validate('w_vinculo','Tipo de vinculo','SELECT','1','1','10','1','1');
    Validate('w_unidade','Unidade de Exerc�cio','1',1,5,60,'1','1');
    Validate('w_sala','Sala','1',1,2,15,'1','1');
    Validate('w_ramal','Ramal','1',1,2,15,'1','1');   
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Teste!\');');
    ShowHTML('  history.back(1);');
    ScriptClose();
  }
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('<style type="text/css">');
  ShowHTML('<!--');
  ShowHTML('@import "ibict.css";');
  ShowHTML('-->');
  ShowHTML('</style>');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('ETDV',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_cpf.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAET',$O)===false)) { 
    AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<TABLE borderColor=#000000 cellSpacing=0 cellPadding=0 width=763 border=1 bgcolor="white">');
    ShowHTML('  <tr bgcolor="'.$conTrBgColor.'"><td>');  
    ShowHTML('    <table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">');
    ShowHTML('      <tr><td colspan="2"><table width="100%" cellpadding="0" cellspacing="0">');
    ShowHTML('         <tr><td><table width="100%" cellspacing="0" cellpadding="0" height="21">');
    ShowHTML('             <tr height="21">');
    ShowHTML('                 <td class="tdLine" align="left"><IMG height=21 src="logo_ct2.gif" width=229 border=0></td>');
    ShowHTML('                 <td class="tdLine"></td>');
    ShowHTML('                </table></td></tr>');
    ShowHTML('                <tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="ibictBar" background="topbg.jpg">');
    ShowHTML('                    <tr><td height="21" align="left" valign="center"><IMG hspace=2 src="logo.gif" align=absMiddle vspace=4 border=0></td>');
    ShowHTML('                        <td><a class="ibictTitulo">Instituto Brasileiro de Informa��o<br>em Ci�ncia e Tecnologia</a></td>');
    ShowHTML('                        <td height="21" align="right" valign="center">');
    ShowHTML('                           <table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td align="right">');
    ShowHTML('                           <table cellpadding=0 cellspacing=0 border=1 width="182"><tr><td align="right">');
    ShowHTML('                           <table width="182" cellpadding=0 cellspacing=0 bgcolor="white">');
    ShowHTML('                           <tr><td>&nbsp;<img src="logo_sgpa.gif" border=0>&nbsp;');
    ShowHTML('                           <tr><td>&nbsp;<img src="logo_sistema.gif" border=0>&nbsp;');
    ShowHTML('                           </table></table>');
    ShowHTML('                           <td width=5></td>');
    ShowHTML('                           </table>');
    ShowHTML('                        </td>');
    ShowHTML('                    </tr>');
    ShowHTML('                </table></td></tr>');
    ShowHTML('            </table></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('   <table border="0">');
    ShowHTML('     <tr><td colspan=3> <center><font size=2><b>Informe os dados abaixo e clique no bot�o "Gravar" para efetivar seu cadastramento.<br>Todos os campos s�o obrigat�rios.<b></td>');
    ShowHTML('     <tr><td><table border="0" width="70%">');
    if(!$w_Disabled) ShowHTML('     <tr><td valign="top" title="Informe apenas os n�meros do seu CPF." ><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" Class="sti" name="w_cpf" value="'.$w_cpf.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);" onBlur="if (Validacao1(document.Form.w_cpf.value)) {document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_nome\'; document.Form.Botao[0].disabled=true; document.Form.Botao[1].disabled=true; document.Form.submit();}">');
    else             ShowHTML('     <tr><td valign="top"><b><u>C</u>PF:<br><INPUT READONLY ACCESSKEY="C" TYPE="text" Class="sti" name="w_cpf" value="'.$w_cpf.'" SIZE="14" MaxLength="14">');
    ShowHTML('	   <tr><td valign="top" title="Informe seu nome completo, se poss�vel sem abrevia��es."><b><u>N</u>ome completo:</b><br><input accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('     <td valign="top" title="Informe o nome pelo qual voc� prefere ser chamado ou pelo qual � mais conhecido."><b><u>N</u>ome resumido:</b><br><input accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_nome_resumido.'"></td>');
    ShowHTML('     <tr>');
    selecaoSexo('<u>S</u>exo:','S',null,$w_sexo,null,'w_sexo',null,null);
    ShowHTML('		<tr><td valign="top" title="Informe seu e-mail do IBICT."><b><u>E</u>mail:</b><br><input accesskey="N" type="text" name="w_email" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_email.'"></td>');
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top" title="Selecione na lista abaixo o seu tipo de v�nculo."><b><u>T</u>ipo de vinculo:</b><br><SELECT accesskey="T "  class="sts" name="w_vinculo" VALUE="'.$w_vinculo.'">'); 
    ShowHTML('          <option value="">---');
    if (nvl($w_vinculo,0)==1) ShowHTML('          <option value="1" selected>Servidor/Dirigente');    else ShowHTML('          <option value="1">Servidor/Dirigente');
    if (nvl($w_vinculo,0)==2) ShowHTML('          <option value="2" selected>Tercerizado');           else ShowHTML('          <option value="2">Tercerizado');
    if (nvl($w_vinculo,0)==3) ShowHTML('          <option value="3" selected>Bolsista');              else ShowHTML('          <option value="3">Bolsista');
    if (nvl($w_vinculo,0)==4) ShowHTML('          <option value="4" selected>Estagi�rio');            else ShowHTML('          <option value="4">Estagi�rio');
    if (nvl($w_vinculo,0)==5) ShowHTML('          <option value="5" selected>Consultor');             else ShowHTML('          <option value="5">Consultor');
    ShowHTML('          </select>');          
    ShowHTML('          </table>');
    ShowHTML('          <tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('        <table width="100%" border="0">');
    ShowHTML('          <tr><td valign="top" title="Informe sua Coordena��o ou Unidade em que trabalha."><b><u>C</u>oordena��o ou Unidade em que trabalha:</b><br><input accesskey="U" type="text" name="w_unidade" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_unidade.'"></td>');
    ShowHTML('          <tr><td valign="top" title="Informe o n�mero da sua sala." ><b>S<u>a</u>la</b><br><input accesskey="s" type="text" name="w_sala" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_sala.'"></td>');
    ShowHTML('          <tr><td valign="top" title="Informe o n�mero do seu ramal. Se houver mais de um n�mero separar com /."><b><u>R</u>amal</b><br><input  accesskey="R" type="text" name="w_ramal" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_ramal.'"></td>');
    ShowHTML('          <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('        </table>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr valign="top">');
    ShowHTML('  <td>');
    ShowHTML('    <ul>');
    ShowHTML('    <p align="justify"><b><br>Orienta��es para preenchimento</b><br></p>');
    ShowHTML('      <li> Posicione o mouse sobre o campo desejado para obter instru��es de preenchimento;');  
    ShowHTML('      <li> Para ir de um campo a outro, clique sobre o campo desejado ou use a tecla TAB para avan�ar e SHIFT-TAB para voltar;');
    ShowHTML('      <li> Depois de preencher e gravar os dados do usu�rio desejado, feche o navegador; ');
    ShowHTML('      <li> Para ajustar os dados de um usu�rio, informe o CPF e clique em qualquer �rea de tela para recaregar a p�gina. ');
   
    ShowHTML('    </ul>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('</table>');
    ShowHTML('</center>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Gravar" onClick="if (Validacao(document.Form)) {document.Form.submit()}">');
    ShowHTML('            <input class="stb" type="button" name="Botao" value="Limpar formul�rio" onClick="location.href=\''.$w_pagina.'\';document.Form.Botao[0].disabled=true; document.Form.Botao[1].disabled=true;">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</TABLE>');
  } 
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
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
 // global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=document.focus();');
  // Verifica se a Assinatura Eletr�nica � v�lida
  if ($O !=='') { // Identifica, a partir do tamanho da vari�vel w_username, se � pessoa f�sica, jur�dica ou estrangeiro
    dml_putUsuarioTemp::getInstanceOf($dbms,$O,
         $_REQUEST['w_cliente'],$_REQUEST['w_cpf'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
         $_REQUEST['w_sexo'],$_REQUEST['w_email'],$_REQUEST['w_vinculo'],$_REQUEST['w_unidade'],
         $_REQUEST['w_sala'],$_REQUEST['w_ramal']);
    //Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Usu�rio cadastrado com sucesso!\nAp�s concluir o cadastramento feche o navegador.\');');
    ShowHTML('  location.href=\''.$w_pagina.'\';');
    ScriptClose();     
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Opera��o n�o executada!\');');
    ScriptClose();
  } 
}
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case null:            Benef();        break;
  case "GRAVA":         Grava();        break;
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