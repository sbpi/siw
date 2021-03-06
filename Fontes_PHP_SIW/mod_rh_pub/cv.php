<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getKnowArea.php');
include_once($w_dir_volta.'classes/sp/db_getCVAcadForm.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoCargo.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoEstadoCivil.php');
include_once($w_dir_volta.'funcoes/selecaoFormacao.php');
include_once($w_dir_volta.'funcoes/selecaoEtnia.php');
include_once($w_dir_volta.'funcoes/selecaoDeficiencia.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoIdioma.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPosto.php');
include_once($w_dir_volta.'funcoes/selecaoModalidade.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoLocalizacao.php');
include_once($w_dir_volta.'funcoes/selecaoVinculo.php');
include_once('visualcurriculo.php');

// =========================================================================
//  /cv.php
// ------------------------------------------------------------------------

// Nome     : Billy Jones Leal dos Santos   
// Descricao: Gerencia telas do curr�culo do colaborador
// Mail     : billy@sbpi.com.br
// Criacao  : 26/10/2006 10:30
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

if(nvl($_REQUEST['p_cliente'],'nulo')!='nulo') $_SESSION['CLIENTE'] = $_REQUEST['p_cliente'];
if(nvl($_REQUEST['p_portal'],'nulo')!='nulo')  $_SESSION['PORTAL']  = $_REQUEST['p_portal'];
if(nvl($_REQUEST['p_logon'],'nulo')!='nulo')   $_SESSION['LOGON']   = $_REQUEST['p_LogOn'];
if(nvl($_REQUEST['p_dbms'],'nulo')!='nulo')    $_SESSION['DBMS']    = $_REQUEST['p_dbms'];

$w_troca=$_REQUEST['w_troca'];
$w_copia=$_REQUEST['w_copia'];

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par          = upper($_REQUEST['par']);
$w_pagina     = 'cv.php?par=';
$w_dir        = 'mod_rh_pub/';
$w_dir_volta  = '../';
$w_Disabled   = 'ENABLED';
$SG           = upper($_REQUEST['SG']);
$O            = upper($_REQUEST['O']);
$w_cliente    = RetornaCliente();
$w_usuario    = RetornaUsuario();
$P1           = Nvl($_REQUEST['P1'],0);
$P2           = Nvl($_REQUEST['P2'],0);
$P3           = Nvl($_REQUEST['P3'],1);
$P4           = Nvl($_REQUEST['P4'],$conPageSize);
$TP           = $_REQUEST['TP'];
$R            = upper($_REQUEST['R']);
$w_assinatura = $_REQUEST['w_assinatura'];
  
// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($_SESSION['PORTAL'] >'') $_SESSION['SQ_PESSOA'] = $w_usuario;

if (nvl($SG,'nulo')!='nulo' && nvl($SG,'nulo')!='CVCARGOS') $w_menu = RetornaMenu($w_cliente,$SG);

if ($SG=='GDPINTERES' || $SG=='GDPAREAS') {
  if ($O!='I' && nvl($_REQUEST['w_chave_aux'],'')=='') $O='L';
} elseif ($SG=='GDPENVIO') {
  $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P';  else  $O='L';
} 
switch ($O) {
case 'I':   $w_TP=$TP.' - Inclus�o';    break;
case 'A':   $w_TP=$TP.' - Altera��o';   break;
case 'E':   $w_TP=$TP.' - Exclus�o';    break;
case 'P':   $w_TP=$TP.' - Filtragem';   break;
case 'C':   $w_TP=$TP.' - C�pia';       break;
case 'V':   $w_TP=$TP.' - Envio';       break;
case 'H':   $w_TP=$TP.' - Heran�a';     break;
default :   $w_TP=$TP.' - Listagem';    break;
} 

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.

$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (mysql_num_rows($RS_query)>0) $w_submenu='Existe';
else  $w_submenu='';
 
// Recupera a configura��o do servi�o
if ($P2>0 && $SG!='CVVISUAL') {
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
  $p_sq_idioma      = $_REQUEST['p_sq_idioma'];
  $p_sexo           = $_REQUEST['p_sexo'];
  $p_nome           = upper($_REQUEST['p_nome']);
  $p_sq_formacao    = $_REQUEST['p_sq_formacao'];
  if ($O=='L') {
    // Recupera os curr�culos existentes na base de dados
    $sql = new db_getCVList; $RS = $sql->getInstanceOf($dbms,$w_cliente,$p_sq_formacao,$p_sq_idioma,$p_sexo,$p_nome);
    $RS = SortArray($RS,'nome_resumido','asc');
  } 
  Cabecalho();
  head();
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.$w_dir_volta.MontaURL('MESA').'">'); 
  }
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de atividades</TITLE>');
  ScriptOpen('Javascript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  ValidateOpen('Validacao');
  if ((strpos('P',$O) ? strpos('P',$O)+1 : 0)>0) {
    Validate('p_nome','Nome','1','','3','40','1','1');
    Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    // Se for recarga da p�gina
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  } elseif (strpos('P',$O)!==false) {
    BodyOpen('onLoad=\'document.Form.P4.focus()\';');
  } else {
    BodyOpen('onLoad=this.focus();');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  if ($w_filtro>'') {
    ShowHTML ($w_filtro);
  }
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td>');
    if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Sexo</td>');
    ShowHTML('          <td><b>Forma��o</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_sexo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
        ShowHTML('        <td>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Visualizar&R='.$w_pagina.$par.'&O=L&w_usuario='.f($row,'sq_pessoa').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe o CV deste colaborador." target="_blank">Exibir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET'),$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('P',$O)===false)){
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center" valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td valign="top" width="50%"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_nome" size="40" maxlength="40" value="'.$p_nome.'"></td>');
    ShowHTML('      <tr>');
    SelecaoFormacao('F<u>o</u>rma��o acad�mica:','O',null,$p_sq_formacao,'Acad�mica','p_sq_formacao',null,null);
    ShowHTML('      <tr>');
    SelecaoIdioma('I<u>d</u>ioma:','D',null,$p_sq_idioma,null,'p_sq_idioma',null,null);
    SelecaoSexo('Se<u>x</u>o:','X',null,$p_sexo,null,'p_sexo',null,null);
    ShowHTML('      <tr>');
    ShowHTML('          <td valign="top"><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
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
  Rodape();
} 

// =========================================================================
// Rotina dos dados de identifica��o
// -------------------------------------------------------------------------

function Identificacao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $w_usuario;
  $w_readonly   = '';
  $w_erro       = '';
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_sq_estado_civil    = $_REQUEST['w_sq_estado_civil'];
    $w_nome               = $_REQUEST['w_nome'];
    $w_nome_resumido      = $_REQUEST['w_nome_resumido'];
    $w_foto               = $_REQUEST['w_foto'];
    $w_nascimento         = $_REQUEST['w_nascimento'];
    $w_rg_numero          = $_REQUEST['w_rg_numero'];
    $w_rg_emissor         = $_REQUEST['w_rg_emissor'];
    $w_rg_emissao         = $_REQUEST['w_rg_emissao'];
    $w_cpf                = $_REQUEST['w_cpf'];
    $w_pais               = $_REQUEST['w_pais'];
    $w_uf                 = $_REQUEST['w_uf'];
    $w_cidade             = $_REQUEST['w_cidade'];
    $w_passaporte_numero  = $_REQUEST['w_passaporte_numero'];
    $w_sq_pais_passaporte = $_REQUEST['w_passaporte_numero'];
    $w_sexo               = $_REQUEST['w_sexo'];
    $w_sq_formacao        = $_REQUEST['w_sq_formacao'];
  } else {
    // Recupera os dados do curr�culo a partir da chave
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$w_cliente,nvl($w_chave,0),$SG,'DADOS');
    if (count($RS)>0) {
      $w_sq_estado_civil      = f($RS,'sq_estado_civil');
      $w_nome                 = f($RS,'nome');
      $w_nome_resumido        = f($RS,'nome_resumido');
      $w_foto                 = f($RS,'sq_siw_arquivo');
      $w_nascimento           = FormataDataEdicao(f($RS,'nascimento'));
      $w_rg_numero            = f($RS,'rg_numero');
      $w_rg_emissor           = f($RS,'rg_emissor');
      $w_rg_emissao           = FormataDataEdicao(f($RS,'rg_emissao'));
      $w_cpf                  = f($RS,'cpf');
      $w_pais                 = f($RS,'pais');
      $w_uf                   = f($RS,'uf');
      $w_cidade               = f($RS,'sq_cidade_nasc');
      $w_passaporte_numero    = f($RS,'passaporte_numero');
      $w_sq_pais_passaporte   = f($RS,'sq_pais_passaporte');
      $w_sexo                 = f($RS,'sexo');
      $w_sq_formacao          = f($RS,'sq_formacao');
      $O                      = 'A';
    } else {
      $w_nome = null;
      $O      = 'I';
    } 
  } 
  Cabecalho();
  head();
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara.
  ScriptOpen('JavaScript');
  CheckBranco();
  Modulo();
  FormataData();
  SaltaCampo();
  FormataCPF();
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == "Troca") { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','');
    Validate('w_nome_resumido','Nome resumido','1',1,2,21,'1','');
    Validate('w_nascimento','Data de nascimento','DATA',1,10,10,'',1);
    Validate('w_sexo','Sexo','SELECT','1','1','10','1','');
    Validate('w_sq_estado_civil','Estado civil','SELECT','1','1','10','','1');
    Validate('w_sq_formacao','Forma��o acad�mica','SELECT','1','1','10','','1');
    Validate('w_foto','Foto','','','4','200','1','1');
    ShowHTML('  if (theForm.w_foto.value != "") {');
    ShowHTML('     if (theForm.w_foto.value.toUpperCase().indexOf(".JPG") < 0 && theForm.w_foto.value.toUpperCase().indexOf(".GIF") < 0) {');
    ShowHTML('        alert(\'A foto informada deve ter extens�o JPG ou GIF!\');');
    ShowHTML('        theForm.w_foto.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  }');
    Validate('w_pais','Pa�s nascimento','SELECT',1,1,18,'','0123456789');
    Validate('w_uf','Estado nascimento','SELECT',1,1,3,'1','1');
    Validate('w_cidade','Cidade nascimento','SELECT',1,1,18,'','0123456789');
    Validate('w_rg_numero','RG','1','1','5','18','1','1');
    Validate('w_rg_emissor','Emissor','1','1','5','80','1','1');
    Validate('w_rg_emissao','Data de emiss�o','DATA','1','10','10','','0123456789/');
    CompData('w_nascimento','Data de nascimento','<','w_rg_emissao','Data de emiss�o');
    Validate('w_cpf','CPF','CPF','1','14','14','','0123456789.-');
    Validate('w_passaporte_numero','Passaporte','1','',1,40,'1','1');
    ShowHTML('  if (theForm.w_passaporte_numero.value != "") {');
    ShowHTML('     if (theForm.w_sq_pais_passaporte.selectedIndex == 0) {');
    ShowHTML('        alert(\'Indique o pa�s emissor do passaporte!\');');
    ShowHTML('        theForm.w_sq_pais_passaporte.focus();');
    ShowHTML('        return false;');
    ShowHTML('     }');
    ShowHTML('  } else {');
    ShowHTML('     if (theForm.w_sq_pais_passaporte.selectedIndex != 0) {');
    ShowHTML('        theForm.w_sq_pais_passaporte.selectedIndex = 0;');
    ShowHTML('     }');
    ShowHTML('  }');
    if ($_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
     }
  } 
  ShowHTML('if (theForm.w_foto.value != "") {return ProgressBar();}');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } if ($_SESSION['PORTAL']=='') {
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
  } 
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAEV',$O)===false)) {
    if ($w_pais=='') {
    // Carrega os valores padr�o para pa�s, estado e cidade
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    $w_pais   = f($RS,'sq_pais');
    $w_uf     = f($RS,'co_uf');
    $w_cidade = f($RS,'sq_cidade_padrao');
  } 
  ShowHTML('<FORM action="'.$w_dir.$w_pagina.'Grava&SG='.$SG.'&O='.$O.'&UploadID='.$UploadID.'" name="Form" onSubmit="return(Validacao(this));" enctype="multipart/form-data" method="POST">');
  ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
  ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
  ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
  if ($_SESSION['PORTAL'] >'' && $O=='I') {
    ShowHTML('<INPUT type="hidden" name="R" value="'.$_SERVER['HTTP_REFERER'].'">');
  } else {
    ShowHTML('<INPUT type="hidden" name="R" value="'.$w_pagina.$par.'">');
  } 
  ShowHTML(MontaFiltro('UL'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_usuario.'">');
  ShowHTML('<INPUT type="hidden" name="w_atual" value="'.$w_foto.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  ShowHTML('      <tr><td><table border="0" width="100%">');
  ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3>Este bloco deve ser preenchido com dados de identifica��o e caracter�sticas pessoais.</td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr valign="top">');
  ShowHTML('          <td title="Informe seu nome completo, sem abrevia��es."><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="40" MAXLENGTH="60" VALUE="'.Nvl($w_nome,$nome_session).'"></td>');
  ShowHTML('          <td title="Informe o nome pelo qual voc� prefere ser chamado ou pelo qual � mais conhecido."><b>Nome <u>r</u>esumido:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="21" VALUE="'.Nvl($w_nome_resumido,$nome_resumido_session).'"></td>');
  ShowHTML('          <td title="Informe a data do seu nascimento, conforme consta da carteira de identidade."><b>Data <u>n</u>ascimento:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
  ShowHTML('        <tr valign="top">');
  SelecaoSexo('<u>S</u>exo:','S',null,$w_sexo,null,'w_sexo',null,null);
  ShowHTML('          <td colspan=2><table border="0" width="100%" cellpadding=0 cellspacing=0><tr>');
  SelecaoEstadoCivil('Estado ci<u>v</u>il:','V',null,$w_sq_estado_civil,null,'w_sq_estado_civil',null,null);
  ShowHTML('          </table>');
  ShowHTML('        <tr valign="top">');
  SelecaoFormacao('F<u>o</u>rma��o acad�mica:','O','Selecione a forma��o acad�mica mais alta que voc� tem como comprovar a conclus�o.',$w_sq_formacao,'Acad�mica','w_sq_formacao',null,null);
  ShowHTML('          <td colspan=2 title="Selecione o arquivo que cont�m sua foto. Deve ser um arquivo com a extens�o JPG ou GIF, com at� 50KB."><b><u>F</u>oto:</b><br><input '.$w_Disabled.' accesskey="N" type="file" name="w_foto" class="sti" SIZE="40" MAXLENGTH="200" VALUE="">&nbsp;');
  if ($w_foto>''){
    ShowHTML(LinkArquivo('SS',$w_cliente,$w_foto,'_blank',null,'Exibir',null));
  }
  ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Local nascimento</td></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3>Selecione nos campos abaixo o pa�s, o estado e a cidade de nascimento.</td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr valign="top">');
  SelecaoPais('<u>P</u>a�s:','P','Selecione o pa�s de nascimento e aguarde a tela carregar os estados.',$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
  SelecaoEstado('E<u>s</u>tado:','S','Selecione o estado de nascimento e aguarde a tela carregar as cidades.',$w_uf,$w_pais,null,'w_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'&SG='.$SG.'&O='.$O.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
  SelecaoCidade('<u>C</u>idade:','C','Selecine a cidade de nascimento.',$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
  ShowHTML('        <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3 valign="top" align="center" bgcolor="#D0D0D0"><b>Documenta��o</td></td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr><td colspan=3>Informe, nos campos a seguir, os dados relativos � sua documenta��o.</td></tr>');
  ShowHTML('        <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('        <tr valign="top">');
  ShowHTML('          <td title="Informe o n�mero da sua carteira de identidade (registro geral)."><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg_numero" class="sti" SIZE="15" MAXLENGTH="30" VALUE="'.$w_rg_numero.'"></td>');
  ShowHTML('          <td title="Informe o nome do �rg�o expedidor de sua carteira de identidade."><b><u>E</u>missor:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_rg_emissor" class="sti" SIZE="10" MAXLENGTH="15" VALUE="'.$w_rg_emissor.'"></td>');
  ShowHTML('          <td title="Informe a data de emiss�o de sua carteira de identidade."><b><u>D</u>ata emiss�o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_rg_emissao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_rg_emissao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
  ShowHTML('        <tr valign="top">');
  if ($O=='I') {
    ShowHTML('          <td title="Informe seu n�mero no Cadastro de Pessoas F�sicas - CPF."><b>CP<u>F</u>:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_cpf" class="sti" SIZE="14" MAXLENGTH="14" VALUE="'.$w_cpf.'" onKeyDown="FormataCPF(this,event);"></td>');
  } else {
    ShowHTML('          <td title="Seu CPF n�o pode ser alterado."><b>CP<u>F</u>:</b><br><input '.$w_Disabled.' readonly accesskey="F" type="text" name="w_cpf" class="sti" SIZE="14" MAXLENGTH="14" VALUE="'.$w_cpf.'" onKeyDown="FormataCPF(this,event);"></td>');
  } 
  ShowHTML('          <td title="Se possuir um passaporte, informe o n�mero."><b>N�mero passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte_numero" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte_numero.'"></td>');
  SelecaoPais('<u>P</u>a�s passaporte:','P','Se possuir um passaporte, selecione o pa�s de emiss�o.',$w_sq_pais_passaporte,null,'w_sq_pais_passaporte',null,null);
  ShowHTML('      </table>');
  if ($_SESSION['PORTAL']=='') {
    ShowHTML('      <tr><td align="LEFT" colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
  }
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de idiomas
// -------------------------------------------------------------------------

function Idiomas() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  $w_troca  = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_leitura     = $_REQUEST['w_leitura'];
    $w_escrita     = $_REQUEST['w_escrita'];
    $w_compreensao = $_REQUEST['w_compreensao'];
    $w_conversacao = $_REQUEST['w_conversacao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVIdioma; $RS = $sql->getInstanceOf($dbms,$w_usuario,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do registro informado
    $sql = new db_getCVIdioma; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_nm_idioma   = f($RS,'nome');
    $w_chave       = f($RS,'sq_idioma');
    $w_leitura     = f($RS,'leitura');
    $w_escrita     = f($RS,'escrita');
    $w_compreensao = f($RS,'compreensao');
    $w_conversacao = f($RS,'conversacao');
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('I',$O)===false)) {
      Validate('w_chave','Idioma','SELECT','1','1','10','','1');
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
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
  } elseif ($O=='I'){
    BodyOpen('onLoad=\'document.Form.w_chave.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Idioma</td>');
    ShowHTML('          <td><b>Leitura</td>');
    ShowHTML('          <td><b>Escrita</td>');
    ShowHTML('          <td><b>Conversa��o</td>');
    ShowHTML('          <td><b>Compreens�o</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_leitura').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_escrita').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_conversacao').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_compreensao').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_idioma').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_idioma').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I') {
      ShowHTML('      <tr>');
      SelecaoIdioma('I<u>d</u>dioma:','D','Selecione o idioma que voc� deseja informar os dados.',$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('      <tr><td valign="top">Idioma:</b><br><b>'.$w_nm_idioma);
    } 
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Voc� l� com facilidade textos escritos no idioma selecionado acima?</b>',$w_leitura,'w_leitura');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Voc� escreve textos com facilidade no idioma selecionado acima?</b>',$w_escrita,'w_escrita');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Voc� compreende com facilidade pessoas conversando no idioma selecionado acima?</b>',$w_compreensao,'w_compreensao');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Voc� conversa fluentemente no idioma selecionado acima?</b>',$w_conversacao,'w_conversacao');
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML ('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de experiencia profissional
// -------------------------------------------------------------------------

function Experiencia() {
  extract($GLOBALS);
  global $w_disabled;
  $w_chave  = $_REQUEST['w_chave'];
  $w_troca  = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_nm_area              = $_REQUEST['w_nm_area'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_co_uf                = $_REQUEST['w_co_uf'];
    $w_sq_cidade            = $_REQUEST['w_sq_cidade'];
    $w_sq_eo_tipo_posto     = $_REQUEST['w_sq_eo_tipo_posto'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_atividades           = $_REQUEST['w_atividades'];
    $w_empregador           = $_REQUEST['w_empregador'];
    $w_entrada              = $_REQUEST['w_entrada'];
    $w_saida                = $_REQUEST['w_saida'];
    $w_duracao_mes          = $_REQUEST['w_duracao_mes'];
    $w_duracao_ano          = $_REQUEST['w_duracao_ano'];
    $w_motivo_saida         = $_REQUEST['w_motivo_saida'];
  } if ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'EXPERIENCIA');
    $RS = SortArray($RS,'entrada','desc');
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'EXPERIENCIA');
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    if (nvl(f($RS,'nm_area'),'')=='') {
      $w_nm_area = '';
    } else {
      $w_nm_area = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    }
    $w_sq_pais          = f($RS,'sq_pais');
    $w_co_uf            = f($RS,'co_uf');
    $w_sq_cidade        = f($RS,'sq_cidade');
    $w_sq_eo_tipo_posto = f($RS,'sq_eo_tipo_posto');
    $w_sq_tipo_vinculo  = f($RS,'sq_tipo_vinculo');
    $w_empregador       = f($RS,'empregador');
    $w_atividades       = f($RS,'atividades');
    $w_entrada          = FormataDataEdicao(f($RS,'entrada'));
    $w_saida            = FormataDataEdicao(f($RS,'saida'));
    $w_duracao_mes      = f($RS,'duracao_mes');
    $w_duracao_ano      = f($RS,'duracao_ano');
    $w_motivo_saida     = f($RS,'motivo_saida');
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formatadata();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_empregador','Empregador','1','1','1','60','1','1');
      Validate('w_nm_area','�rea do conhecimento','','1','1','80','1','1');
      Validate('w_entrada','Data entrada','DATA','1','10','10','','1');
      Validate('w_saida','Data sa�da','DATA','','10','10','','1');
      CompData('w_entrada','Data entrada','<','w_saida','Data sa�da');
      ShowHTML('  if (theForm.w_saida.value != "" && theForm.w_motivo_saida.value == "") {');
      ShowHTML('     alert(\'Informe o motivo da sa�da!\');');
      ShowHTML('     theForm.w_motivo_saida.focus();');
      ShowHTML('     return false;');
      ShowHTML('  }');
      Validate('w_motivo_saida','Motivo sa�da','1','','1','255','1','1');
      Validate('w_sq_pais','Pais','SELECT','1','1','10','','1');
      Validate('w_co_uf','Estado','SELECT','1','1','10','1','');
      Validate('w_sq_cidade','Cidade','SELECT','1','1','10','','1');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm.w_sq_eo_tipo_posto.value==undefined) {');
      ShowHTML('     for (i=0; i < theForm.w_sq_eo_tipo_posto.length; i++) {');
      ShowHTML('       if (theForm.w_sq_eo_tipo_posto[i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm.w_sq_eo_tipo_posto.checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Informe a principal atividade desempenhada!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      Validate('w_atividades','Atividades desempenhadas','','1','4','4000','1','1');
      if ($_SESSION['PORTAL']=='') {
        Validate ('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
      }
    } elseif ($O=='E') {
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
        ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
      } 
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      ValidateClose();
      ScriptClose();
    } 
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($O=='L') {
    BodyOpen('onLoad=\'this.focus();\'');
  } elseif ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_empregador.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_cvpessoa='.$w_sq_cvpessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>�rea</td>');
    ShowHTML('          <td><b>Empregador</td>');
    ShowHTML('          <td><b>Entrada</td>');
    ShowHTML('          <td><b>Sa�da</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontradas experi�ncias profissionais cadastradas.</b></td></tr>');
    } else {
        // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_area').'</td>');
        ShowHTML('        <td>'.f($row,'empregador').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'entrada')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'saida')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_chave='.f($row,'sq_cvpesexp').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_chave='.f($row,'sq_cvpesexp').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" onClick="return confirm(\'Confirma a exclus�o do emprego?\');">EX</A>&nbsp');
        ShowHTML('          <u class="HL" onclick="javascript:window.open(\''.montaURL_JS($w_dir,$w_pagina.'CARGOS&R='.$w_pagina.'CARGOS&O=L&w_sq_cvpessoa='.$w_sq_cvpessoa.'&w_sq_cvpesexp='.f($row,'sq_cvpesexp').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Cargos&SG=CVCARGOS'.MontaFiltro('GET').'\',\'Cargos').'\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\')">Cargos</u>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td colspan=3><br><b>Instru��es:</b>');
    ShowHTML('   <ul>');
    ShowHTML('   <li>A finalidade desta tela � registrar toda a sua experi�ncia profissional;');
    ShowHTML('   <li>Para cada experi�ncia profissional, informe os cargos que desempenhou na organiza��o;');
    ShowHTML('   <li>Indique sempre a que �rea do conhecimento a experi�ncia est� vinculada (Ex: contabilidade, administra��o etc);');
    ShowHTML('   <li>Se a �rea do conhecimento ou o cargo desempenhado n�o forem localizados, busque por um nome mais abrangente ou entre em contato com o gestor do sistema.');
    ShowHTML('   </ul>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
        $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="'.$w_troca.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpessoa" value="'.$w_sq_cvpessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top"><b><u>E</u>mpregador:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_empregador" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_empregador.'"></td>');
    ShowHTML('      <tr><td valign="top"><b>�rea do conhecimento relacionada:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b>E<U>n</U>trada:</b></br><INPUT ACCESSKEY="n" '.$w_Disabled.' class="sti" type="text" name="w_entrada" size="10" maxlength="10" value="'.$w_entrada.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('              <td valign="top"><b><U>S</U>a�da:</b></br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_saida" size="10" maxlength="10" value="'.$w_saida.'" onKeyDown="FormataData(this, event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Mo<u>t</u>ivo sa�da:</b><br><textarea '.$w_Disabled.' accesskey="t"  name="w_motivo_saida" class="sti" cols="80" rows="4">'.$w_motivo_saida.'</textarea></td>');
    ShowHTML('      <tr valign="top"><td colspan="2">');
    ShowHTML('         <table border=0 width="100%" cellspacing=0>');
    ShowHTML('           <tr valign="top">');
    SelecaoPais('<u>P</u>a�s:','P','Selecione o pa�s da experi�ncia profissional e aguarde a tela carregar os estados.',$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
    SelecaoEstado('E<u>s</u>tado:','S','Selecione o estado da experi�ncia profissional e aguarde a tela carregar as cidades.',$w_co_uf,$w_sq_pais,null,'w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
    SelecaoCidade('<u>C</u>idade:','C','Selecine a cidade de nascimento.',$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
    ShowHTML('         </table></td></tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoPosto('Informe a principal atividade desempenhada:','T',null,$w_sq_eo_tipo_posto,null,'w_sq_eo_tipo_posto','S');
    ShowHTML('      <tr><td valign="top"><b>At<u>i</u>vidades desempenhadas:</b><br><textarea '.$w_Disabled.' accesskey="i"  name="w_atividades" class="sti" cols="80" rows="4">'.$w_atividades.'</textarea></td>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E') {
      ShowHTML('            <input class="sti" type="submit" name="Botao" value="Excluir">');
      ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</table>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de cargos
// -------------------------------------------------------------------------
function Cargos() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_sq_cvpescargo = $_REQUEST['w_sq_cvpescargo'];
  $w_sq_cvpesexp   = $_REQUEST['w_sq_cvpesexp'];
  $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_sq_cvpesexp,'EXPERIENCIA');
  $w_nome_empregador = f($RS,'empregador');
  if ($O=='L') {
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_sq_cvpesexp,null,'CARGO');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera o conjunto de informa��es comum a todos os servi�os
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_sq_cvpesexp,$w_sq_cvpescargo,'CARGO');
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area');
    $w_especialidades       = f($RS,'especialidades');
    $w_inicio               = FormataDataEdicao(f($RS,'inicio'));
    $w_fim                  = FormataDataEdicao(f($RS,'fim'));
  } 
  Cabecalho();
  head();
  ShowHTML('<title>Cargos de uma experi�ncia profissional</title>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    checkbranco();
    formataData();
    SaltaCampo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_area_conhecimento','�rea do conhecimento','SELECT','1','1','10','','1');
      Validate('w_especialidades','Especialidades','1','1','1','255','QWERTYUIOPASDFGHJKLZXCVBNM; ','1');
      ShowHTML(' if (document.Form.w_especialidades.value.indexOf(";")==-1){');
      ShowHTML('   alert("Digite apenas palavras maisculas n�o acentuadas e separados por ponto-virgula."); ');
      ShowHTML('   document.Form.w_especialidades.focus();');
      ShowHTML('   return (false);');
      ShowHTML(' }');
      Validate('w_inicio','In�cio','Data','1','10','10','','1');
      Validate('w_fim','Fim','Data','','10','10','','1');
      Validate('w_nm_area','�rea do conhecimento','','1','1','80','1','1');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      } elseif ($O=='E') {
        if ($_SESSION['PORTAL']=='') {
          Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1'); 
        }
        ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
      } 
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
      ValidateClose();
      ScriptClose();
    } 
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_especialidades.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td>Empregador:<b> '.$w_nome_empregador.'</b>');
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&w_sq_cvpesexp= '.$w_sq_cvpesexp.'&R='.$w_pagina.$par.'&O=I&w_sq_cvpessoa='.$w_sq_cvpessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('     <a accesskey="F" class="SS" href="javascript:opener.location.reload();javascript:window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Cargo</td>');
    ShowHTML('          <td><b>In�cio</td>');
    ShowHTML('          <td><b>Fim</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados cargos cadastrados.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('        <td>'.f($RS,'nm_area').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($RS,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($RS,'fim')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_cvpesexp='.f($RS,'sq_cvpesexp').'&w_sq_cvpescargo='.f($RS,'sq_cvpescargo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_sq_cvpesexp='.f($RS,'sq_cvpesexp').'&w_sq_cvpescargo='.f($RS,'sq_cvpescargo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" onClick="return confirm(\'Confirma a exclus�o do cargo?\');">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpessoa" value="'.$w_sq_cvpessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpescargo" value="'.$w_sq_cvpescargo.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_cvpesexp" value="'.$w_sq_cvpesexp.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td valign="top">Empregador:<br><b>'.$w_nome_empregador.'</b></td>');
    ShowHTML('      <tr><td><b><u>E</u>specialidades(Digite apenas palavras maisculas n�o acentuadas e separados por ponto-virgula.):</b><br><textarea '.$w_Disabled.' accesskey="N" name="w_especialidades" class="sti" SIZE="255" MAXLENGTH="255" COLS = "90" ROWS="5">'.$w_especialidades.'</TEXTAREA></td>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
    ShowHTML('            <td valign="top"><b><u>I</u>n�cio:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('            <td valign="top"><b><u>F</u>im:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td valign="top"><b>Cargo desempenhado:</b><br>');
    ShowHTML('          <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    ShowHTML('          [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&P1=2').'\',\'SelecaoCargo\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center" colspan=4><hr>');
    if ($O=='E') {
     ShowHTML('   <input class="sti" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
       ShowHTML('            <input class="sti" type="submit" name="Botao" value="Incluir">');
     } else {
        ShowHTML('            <input class="sti" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="sti" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_cvpesexp= '.$w_sq_cvpesexp.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de forma��o acad�mica
// -------------------------------------------------------------------------

function Escolaridade() {
  extract($GLOBALS);
  global $w_disabled; 
  $w_chave   = $_REQUEST['w_chave'];
  $w_troca   = $_REQUEST['w_troca'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_pais              = $_REQUEST['w_sq_pais'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_instituicao          = $_REQUEST['w_instituicao'];
    $w_inicio               = $_REQUEST['w_inicio'];
    $w_fim                  = $_REQUEST['w_fim'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'ACADEMICA');
    $RS = SortArray($RS,'ordem','desc','inicio','desc');
    } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
      // Recupera os dados do endere�o informado
      $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'ACADEMICA');
      $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
      foreach ($RS as $row) {$RS=$row; break;}
      if (nvl(f($RS,'nm_area'),'')=='') {
        $w_nm_area='';
      } else {
        $w_nm_area = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
      }
      $w_sq_pais     = f($RS,'sq_pais');
      $w_sq_formacao = f($RS,'sq_formacao');
      $w_nome        = f($RS,'nome');
      $w_instituicao = f($RS,'instituicao');
      $w_inicio      = f($RS,'inicio');
      $w_fim         = f($RS,'fim');
    } 
    Cabecalho();
    head();
    if (strpos('IAEP',$O)!==false) {
      ScriptOpen('JavaScript');
      checkbranco();
      SaltaCampo();
      formatadatama();
      ValidateOpen('Validacao');
      if (strpos('IA',$O)!==false) {
        Validate('w_sq_formacao','Forma��o','SELECT','1','1','10','','1');
        Validate('w_nm_area','�rea do conhecimento','','','1','80','1','1');
        ShowHTML('  if (theForm.w_sq_formacao.selectedIndex > 3 && (theForm.w_sq_area_conhecimento.value=="" || theForm.w_nome.value=="")) { ');
        ShowHTML('     alert(\'Se forma��o acad�mica for gradua��o ou acima, informe a �rea do conhecimento e o nome do curso\'); ');
        ShowHTML('     return false; ');
        ShowHTML('  } ');
        Validate('w_nome','Nome','1','','3','80','1','1');
        Validate('w_instituicao','Institui��o','1','1','1','100','1','1');
        Validate('w_inicio','In�cio','DATAMA','1','7','7','','0123456789/');
        Validate('w_fim','Fim','DATAMA','','7','7','','0123456789/');
        Validate('w_sq_pais','Pa�s conclus�o','SELECT','1','1','10','','1');
        if ($_SESSION['PORTAL']=='') {
          Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
        }
      } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
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
    BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else{
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>N�vel</td>');
    ShowHTML('          <td><b>�rea</td>');
    ShowHTML('          <td><b>Curso</td>');
    ShowHTML('          <td><b>In�cio</td>');
    ShowHTML('          <td><b>T�rmino</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($RS,'nm_formacao').'</td>');
        ShowHTML('        <td>'.Nvl(f($RS,'nm_area'),'---').'</td>');
        ShowHTML('        <td>'.Nvl(f($RS,'nome'),'---').'</td>');
        ShowHTML('        <td align="center">'.f($RS,'inicio').'</td>');
        ShowHTML('        <td align="center">'.Nvl(f($RS,'fim'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($RS,'sq_cvpessoa_escol').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($RS,'sq_cvpessoa_escol').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoFormacao('F<u>o</u>rma��o acad�mica:','O','Selecione a forma��o acad�mica que voc� deseja informar os dados.',$w_sq_formacao,'Acad�mica','w_sq_formacao',null,null);
    ShowHTML('      <tr><td valign="top"><b>Se forma��o for gradua��o ou maior, indique a �rea do conhecimento:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome curso:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><u>I</u>nstitui��o:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_instituicao" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_instituicao.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b>�ni<u>c</u>io: (mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="c" type="text" name="w_inicio" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_inicio.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);"></td>');
    ShowHTML('              <td valign="top"><b>Fi<u>m</u>: (mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_fim" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_fim.'" onKeyDown="FormataDataMA(this,event);" onKeyUp="SaltaCampo(this.form.name,this,7,event);"></td>');
    SelecaoPais('<u>P</u>a�s de conclus�o:','P','Selecione o pa�s onde concluiu esta forma��o.',Nvl($w_sq_pais,2),null,'w_sq_pais',null,null);
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
ShowHTML('</table>');
ShowHTML('</center>');
Rodape();
} 
// =========================================================================
// Rotina de cursos t�cnicos
// -------------------------------------------------------------------------

function Extensao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  $w_troca = $_REQUEST['w_troca'];
  if ($w_troca>''){
    // Se for recarga da p�gina
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_instituicao          = $_REQUEST['w_instituicao'];
    $w_carga_horaria        = $_REQUEST['w_carga_horaria'];
    $w_conclusao            = $_REQUEST['w_conclusao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'CURSO');
    $RS = SortArray($RS,'ordem','desc','carga_horaria','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endere�o informado
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'CURSO');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    $w_sq_formacao          = f($RS,'sq_formacao');
    $w_nome                 = f($RS,'nome');
    $w_instituicao          = f($RS,'instituicao');
    $w_carga_horaria        = f($RS,'carga_horaria');
    $w_conclusao            = FormataDataEdicao(f($RS,'conclusao'));
  } 
  Cabecalho();
  head();
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    checkbranco();
    formatadata();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_formacao','Tipo de extens�o','SELECT','1','1','10','','1');
      Validate('w_nm_area','�rea do conhecimento','','1','1','80','1','1');
      Validate('w_nome','Nome','1','1','5','80','1','1');
      Validate('w_instituicao','Institui��o','1','1','1','100','1','1');
      Validate('w_carga_horaria','Carga hor�ria','','1','2','4','','0123456789');
      Validate('w_conclusao','conclusao','DATA','','10','10','','0123456789/');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      }
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
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
    BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
  } elseif ($O=='A') {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>N�vel</td>');
    ShowHTML('          <td><b>�rea</td>');
    ShowHTML('          <td><b>Curso</td>');
    ShowHTML('          <td><b>C.H.</td>');
    ShowHTML('          <td><b>Conclus�o</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($RS,'nm_formacao').'</td>');
        ShowHTML('        <td>'.f($RS,'nm_area').'</td>');
        ShowHTML('        <td>'.f($RS,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($RS,'carga_horaria').'</td>');
        ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($RS,'conclusao')),'---').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($RS,'sq_cvpescurtec').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($RS,'sq_cvpescurtec').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    DesconectaBD();
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr>');
    SelecaoFormacao('T<u>i</u>po de extens�o:','O','Selecione o tipo mais adequado para a extens�o acad�mica.',$w_sq_formacao,'T�cnica','w_sq_formacao',null,null);
    ShowHTML('      <tr><td valign="top"><b>�rea do conhecimento relacionada:</b><br>');
    ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
    if ($O!='E') {
      ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
    } 
    ShowHTML('      <tr><td valign="top"><b><u>N</u>ome curso:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td valign="top"><b><u>I</u>nstitui��o:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_instituicao" class="sti" SIZE="80" MAXLENGTH="100" VALUE="'.$w_instituicao.'"></td>');
    ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('          <tr><td valign="top"><b><u>C</u>arga hor�ria:</b><br><input '.$w_Disabled.' accesskey="c" type="text" name="w_carga_horaria" class="sti" SIZE="7" MAXLENGTH="7" VALUE="'.$w_carga_horaria.'"></td>');
    ShowHTML('              <td valign="top"><b>C<u>o</u>nclus�o: (dd/mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_conclusao" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_conclusao.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          </table>');
    if ($_SESSION['PORTAL']=='') {
      ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    }
    ShowHTML('      <tr><td align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de produ��o t�cnica
// -------------------------------------------------------------------------
function Producao(){
  extract($GLOBALS);
  $w_chave = $_REQUEST['w_chave'];
  $w_troca = $_REQUEST['w_troca'];  
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_sq_area_conhecimento = $_REQUEST['w_sq_area_conhecimento'];
    $w_sq_formacao          = $_REQUEST['w_sq_formacao'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_meio                 = $_REQUEST['w_meio'];
    $w_data                 = $_REQUEST['w_data'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,null,'PRODUCAO');
    $RS = SortArray($RS,'ordem','desc','data','desc');
  } elseif (strpos('AEV',$O)!==false && $w_troca=='') {
    // Recupera os dados do endere�o informado
    $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$w_usuario,$w_chave,'PRODUCAO');
    foreach ($RS as $row) {$RS=$row; break;}
    $w_sq_area_conhecimento = f($RS,'sq_area_conhecimento');
    $w_nm_area              = f($RS,'nm_area').' ('.f($RS,'codigo_cnpq').')';
    $w_sq_formacao          = f($RS,'sq_formacao');
    $w_nome                 = f($RS,'nome');
    $w_meio                 = f($RS,'meio');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
  } 
  Cabecalho();
  head();
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    checkbranco();
    formatadata();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_formacao','Tipo da produ��o','SELECT','1','1','10','','1');
      Validate('w_nm_area','�rea do conhecimento','','1','5','80','1','1');
      Validate('w_nome','Nome','1','1','1','80','1','1');
      Validate('w_meio','Meio de publica��o','','1','2','100','1','1');
      Validate('w_data','Data','DATA','1','10','10','','0123456789/');
      if ($_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
        ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\')) ');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
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
      BodyOpen('onLoad=\'document.Form.w_sq_formacao.focus()\';');
    } elseif ($O=='A') {
      BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
    } elseif ($O=='E' && $_SESSION['PORTAL']=='') {
      BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
    } else {
      BodyOpen('onLoad=\'this.focus()\';');
    } 
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center>');
      ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
      if ($O=='L') {
        // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
        ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
        ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
        ShowHTML('<tr><td align="center" colspan=3>');
        ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><b>Tipo da produ��o</td>');
        ShowHTML('          <td><b>�rea</td>');
        ShowHTML('          <td><b>Nome</td>');
        ShowHTML('          <td><b>Meio</td>');
        ShowHTML('          <td><b>Data</td>');
        ShowHTML('          <td><b>Opera��es</td>');
        ShowHTML('        </tr>');
        if (count($RS)<=0) {
          // Se n�o foram selecionados registros, exibe mensagem
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
        } else {
          // Lista os registros selecionados para listagem
          foreach ($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
            ShowHTML('        <td>'.f($row,'nm_formacao').'</td>');
            ShowHTML('        <td>'.f($row,'nm_area').'</td>');
            ShowHTML('        <td>'.f($row,'nome').'</td>');
            ShowHTML('        <td>'.f($row,'meio').'</td>');
            ShowHTML('        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data')),'---').'</td>');
            ShowHTML('        <td align="top" nowrap>');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_cvpessoa_prod').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">AL</A>&nbsp');
            ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_cvpessoa_prod').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
        DesconectaBD();
      } elseif (!(strpos('IAEV',$O)===false)){
        if (!(strpos('EV',$O)===false)) {
          $w_Disabled=' DISABLED ';
        } 
        AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
        ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
        ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
        ShowHTML('<INPUT type="hidden" name="w_sq_area_conhecimento" value="'.$w_sq_area_conhecimento.'">');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
        ShowHTML('    <table width="97%" border="0">');
        ShowHTML('      <tr>');
        SelecaoFormacao('T<u>i</u>po da produ��o:','O','Selecione o tipo mais adequado para a produ��o t�cnica.',$w_sq_formacao,'Prod.Cient.','w_sq_formacao',null,null);
        ShowHTML('      <tr><td valign="top"><b>�rea do conhecimento relacionada:</b><br>');
        ShowHTML('              <input READONLY type="text" name="w_nm_area" class="sti" SIZE="50" VALUE="'.$w_nm_area.'">');
        if ($O!='E') {
          ShowHTML('              [<u onMouseOver="this.style.cursor=\'Hand\'" onMouseOut="this.style.cursor=\'Pointer\'" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'BuscaAreaConhecimento&TP='.$TP.'&SG='.$SG.'&P1=1').'\',\'AreaConhecimento\',\'top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\');"><b><font color="#0000FF">Procurar</font></b></u>]');
        } 
          ShowHTML('      <tr><td valign="top"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="80" MAXLENGTH="80" VALUE="'.$w_nome.'"></td>');
          ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
          ShowHTML('          <tr><td valign="top"><b><u>M</u>eio de divulga��o:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_meio" class="sti" SIZE="50" MAXLENGTH="80" VALUE="'.$w_meio.'"></td>');
          ShowHTML('              <td valign="top"><b><u>D</u>ata de publica��o: (dd/mm/aaaa)</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
          ShowHTML('          </table>');
          if ($_SESSION['PORTAL']=='') {
            ShowHTML('      <tr><td align="LEFT"><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');} 
          }
          ShowHTML('      <tr><td align="center"><hr>');
          if ($O=='E') {
            ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
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
        //ShowHTML ' history.back(1);'
        ScriptClose();
      } 
      ShowHTML('</table>');
      ShowHTML('</center>');
      Rodape();
} 
// =========================================================================
// Rotina de busca da �rea do conhecimento
// -------------------------------------------------------------------------

function BuscaAreaConhecimento() {
  extract($GLOBALS);
  if ($P1=='') {
    $P1=1;
  }
  $w_nome  =  $_REQUEST['w_nome'];
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_nome','Nome','1','1','4','30','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  if ($P1==1) {
    ShowHTML('<B><FONT COLOR="#000000">'.RemoveTP($w_TP).' - Procura �rea do Conhecimento</FONT></B>');
  } else {
    ShowHTML('<B><FONT COLOR="#000000">'.RemoveTP($w_TP).' - Procura Cargo</FONT></B>');
  } 
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="90%" border="0">');
  AbreForm('Form',$w_dir.$w_pagina.'BuscaAreaConhecimento','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instru��es</b>:<li>Informe parte do nome da �rea de conhecimento desejada.<li>Quando a rela��o for exibida, selecione a �rea desejada clicando sobre a caixa ao seu lado.<li>Ap�s informar o nome da �rea, clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Cancelar</i>, a procura � cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="90%" border="0">');
  if ($P1==1) {
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome da �rea do conhecimento:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
  } else {
    ShowHTML('      <tr><td valign="top"><b>Parte do <U>n</U>ome do cargo:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="30" maxlength="30" value="'.$w_nome.'">');
  } 
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="sti" type="submit" name="Botao" value="Aplicar filtro">');
  ShowHTML('            <input class="sti" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</form>');
  if ($w_nome>'') {
    if ($P1==1) {
      $sql = new db_getKnowArea; $RS = $sql->getInstanceOf($dbms,null,$w_nome,'A');
    } else {
      $sql = new db_getKnowArea; $RS = $sql->getInstanceOf($dbms,null,$w_nome,'C');
    } 
    $RS = SortArray($RS,'nome','asc');
    ShowHTML('<tr><td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=6>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">');
    if ($P1==1) {
      ShowHTML('          <td><b>Clique sobre a �rea do conhecimento desejada</td>');
    } else {
      ShowHTML('          <td><b>Clique sobre o cargo desejado</td>');
    } 
    ShowHTML('        </tr>');
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td><ul>');
    foreach ($RS as $row ){
      ShowHTML('        <li><a class="SS" HREF="javascript:this.status.value;" onClick="opener.document.Form.w_nm_area.value=\''.f($row,'nome').' ('.f($row,'codigo_cnpq').')\'; opener.document.Form.w_sq_area_conhecimento.value=\''.f($row,'sq_area_conhecimento').'\'; window.close(); opener.focus();">'.f($row,'nome').' ('.f($row,'codigo_cnpq').')</a>');
    } 
    ShowHTML('      </ul></tr>');
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    } 
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de visualiza��o
// -------------------------------------------------------------------------

function Visualizar() {
  extract($GLOBALS);
  if ($P2==1) {
    header('Content-type: '.'application/msword');
  } else {
    cabecalho();
  } 
  head();
  ShowHTML('<TITLE>Curriculum Vitae</TITLE>');
  ShowHTML('</HEAD>');
  if ($P2==0) {
    BodyOpen('onLoad=\'this.focus()\'; ');
  } 
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR>');
  if ($P2==0) {
    $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
    ShowHTML('  <TD ROWSPAN=2><IMG ALIGN="LEFT" src="'.LinkArquivo(null,$w_cliente,'/img/logo'.substr(f($RS,'logo'),(strpos(f($RS,'logo'),'.') ? strpos(f($RS,'logo'),'.')+1 : 0)-1,30),null,null,null,'EMBED').'">');
  } 
  ShowHTML('  <TD ALIGN="RIGHT"><B><FONT SIZE=5 COLOR="#000000">');
  ShowHTML('Curriculum Vitae');
  ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.DataHora().'</B>');
  if ($P2==0) {
    ShowHTML('&nbsp;&nbsp;&nbsp;<IMG ALIGN="CENTER" TITLE="Gerar word" SRC="../images/word.gif" onClick="window.open(\''.montaURL_JS($w_dir,$w_pagina.'Visualizar&P2=1&SG=CVVISUAL&w_usuario='.$w_usuario).'\',\'VisualCurriculoWord\',\'menubar=yes resizable=yes scrollbars=yes\');">');
  } 
  ShowHTML('</TD></TR>');
  ShowHTML('</FONT></B></TD></TR></TABLE>');
  if ($P2==0) {
    ShowHTML('<HR>');
  } 
  // Chama a fun��o de visualiza��o dos dados do usu�rio, na op��o 'Listagem'
  VisualCurriculo($w_cliente,$w_usuario,'L');
  if ($P2==0) {
    Rodape();
  } 
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------

function Grava() {
  extract($GLOBALS);
  $w_file    ='';
  $w_tamanho ='';
  $w_tipo    ='';
  $w_nome    ='';
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  AbreSessao();
  switch ($SG) {
    case 'CVIDENT':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        // Recupera os dados do curr�culo a partir da chave
        $sql = new db_getCV_Pessoa; $RS = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_cpf']);
        if ($O=='I' && count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('alert(\'CPF j� cadastrado. Acesse seu curr�culo usando a op��o "Seu curr�culo" no menu principal.\');');
          ShowHTML('history.back(1);');
          ScriptClose();
        } 
        // Se foi feito o upload de um arquivo
        if (UPLOAD_ERR_OK==0) {
          $w_maximo=51200;
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              if ($Field['size'] > 0) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ShowHTML('  history.back(1);');
                ScriptClose();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_file = basename($Field['tmp_name']);
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.')===false));
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file >'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          $SQL = new dml_putCVIdent; $SQL->getInstanceOf($dbms,$O,
              $w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],$_REQUEST['w_nascimento'],
              $_REQUEST['w_sexo'],$_REQUEST['w_sq_estado_civil'],$_REQUEST['w_sq_formacao'],$_REQUEST['w_cidade'],$_REQUEST['w_rg_numero'],
              $_REQUEST['w_rg_emissor'],$_REQUEST['w_rg_emissao'],$_REQUEST['w_cpf'],$_REQUEST['w_passaporte_numero']);
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        if ($_SESSION['PORTAL']>'' && $O=='I') {
          ShowHTML('  top.location.href=\''.montaURL_JS($w_dir,$R).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_usuario='.$_REQUEST['w_chave'].'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        } 
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVIDIOMA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVIdioma; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_leitura'],$_REQUEST['w_escrita'],
          $_REQUEST['w_compreensao'],$_REQUEST['w_conversacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVEXPPER':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVExperiencia; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_cidade'],$_REQUEST['w_sq_eo_tipo_posto'],
          $_REQUEST['w_sq_tipo_vinculo'],$_REQUEST['w_empregador'],$_REQUEST['w_entrada'],$_REQUEST['w_saida'],
          $_REQUEST['w_duracao_mes'],$_REQUEST['w_duracao_ano'],$_REQUEST['w_motivo_saida'],null,$_REQUEST['w_atividades']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVCARGOS':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVCargo; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_sq_cvpescargo'],
          $_REQUEST['w_sq_cvpesexp'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_especialidades'],
          $_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_sq_cvpesexp='.$_REQUEST['w_sq_cvpesexp'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVESCOLA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVEscola; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_pais'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_instituicao'],$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVCURSO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVCurso; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_instituicao'],$_REQUEST['w_carga_horaria'],$_REQUEST['w_conclusao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'CVTECNICA':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putCVProducao; $SQL->getInstanceOf($dbms,$O,$w_usuario,
          $_REQUEST['w_chave'],$_REQUEST['w_sq_area_conhecimento'],$_REQUEST['w_sq_formacao'],
          $_REQUEST['w_nome'],$_REQUEST['w_meio'],$_REQUEST['w_data']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
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
    case 'INICIAL':               Inicial();              break;
    case 'IDENTIFICACAO':         Identificacao();        break;
    case 'IDIOMAS':               Idiomas();              break;
    case 'ESCOLARIDADE':          Escolaridade();         break;
    case 'CURSOS':                $Extensao;              break;
    case 'EXPPROF':               Experiencia();          break;
    case 'DESPESA':               $Despesa;               break;
    case 'PRODUCAO':              $Producao;              break;
    case 'CARGOS':                Cargos();               break;
    case 'VISUALIZAR':            $Visualizar;            break;
    case 'BUSCAAREACONHECIMENTO': $BuscaAreaConhecimento; break;
    case 'GRAVA':                 $Grava;                 break;
    default:
      Cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');
      BodyOpen('onLoad=this.focus();');
      ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
      ShowHTML('<HR>');
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Rodape();
    break;
  } 
} 
?>