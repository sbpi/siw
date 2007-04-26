<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getCentralTel.php');
include_once($w_dir_volta.'classes/sp/db_getTTRamal.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getPrefixo.php');
include_once($w_dir_volta.'classes/sp/db_getTTUsuario.php');
include_once($w_dir_volta.'classes/sp/db_getRamalUsuarioAtivo.php');
include_once($w_dir_volta.'classes/sp/db_getPessoaTel.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putTTCentral.php');
include_once($w_dir_volta.'classes/sp/dml_putTTPrefixo.php');
include_once($w_dir_volta.'classes/sp/dml_putTTRamal.php');
include_once($w_dir_volta.'classes/sp/dml_putTTRamalUsuario.php');
include_once($w_dir_volta.'classes/sp/dml_putTTTronco.php');
include_once($w_dir_volta.'classes/sp/dml_putTTUsuarioCentral.php');
include_once($w_dir_volta.'funcoes/selecaoEndereco.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaTT.php');
include_once($w_dir_volta.'funcoes/selecaoCidadeCentral.php');
include_once($w_dir_volta.'funcoes/selecaoTelefoneTT.php');
include_once($w_dir_volta.'funcoes/selecaoCentralFone.php');
include_once($w_dir_volta.'funcoes/selecaoTTUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');

// =========================================================================
//  tabelas.PHP
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar tabelas básicas do módulo	
// Mail     : Beto@sbpi.com.br
// Criacao  : 13/06/2006 10:40
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

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
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
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_tt/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

if ($O=='') {
  if ($SG=='TTPREFIXO') $O='P';
  else $O = 'L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();

if ($SG!='TTUSUCTRL' && $SG!='TTTRONCO' && $SG!='RAMUSR') {
  $w_menu=RetornaMenu($w_cliente,$SG);
} else {
  $w_menu=RetornaMenu($w_cliente,$_REQUEST['w_SG']);
} 

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
if ($SG!='TTUSUCTRL' && $SG!='TTTRONCO' && $SG!='RAMUSR') {
  $RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
} else {
  $RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$_REQUEST['w_SG']);
} 

if (count($RS)>0) {
  $w_submenu='Existe';
} else {
  $w_submenu='';
} 

// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
}

// Se for sub-menu, pega a configuração do pai
if ($RS_Menu['ultimo_nivel']=='S') {
  $RS_Menu = db_getMenuData::getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();

FechaSessao($dbms);

exit;


// =========================================================================
// Rotina da Central Telefônica
// -------------------------------------------------------------------------
function centralTel() {
  extract($GLOBALS);
  global $w_Disabled;


  $w_chave              = $_REQUEST['w_chave'];
  $w_troca              = $_REQUEST['w_troca'];
  $w_sq_pessoa_endereco = $_REQUEST['w_sq_pessoa_endereco'];
  $w_arquivo_bilhetes   = $_REQUEST['w_arquivo_bilhetes'];
  $w_recupera_bilhetes  = $_REQUEST['w_recupera_bilhetes'];
  $p_sq_pessoa_endereco = $_REQUEST['p_sq_pessoa_endereco'];
  if ($w_troca>'') {
    $w_sq_pessoa_endereco = $_REQUEST['w_sq_pessoa_endereco'];
    $w_arquivo_bilhetes   = $_REQUEST['w_arquivo_bilhetes'];
    $w_recupera_bilhetes  = $_REQUEST['w_recupera_bilhetes'];
  } elseif ($O=='L') {
    $RS = db_getCentralTel::getInstanceOf($dbms,null,null,$p_sq_pessoa_endereco,null,null);
    $RS = SortArray($RS,strtolower($_REQUEST['p_ordena']),'asc');
  } elseif ((!(strpos('AEV',$O)===false)) && $w_Troca=='') {
    $RS = db_getCentralTel::getInstanceOf($dbms,$w_chave,$w_cliente,$w_sq_pessoa_endereco,null,null);
    foreach ($RS as $row) {
      $w_sq_pessoa_endereco   = f($row,'sq_pessoa_endereco');
      $w_arquivo_bilhetes     = f($row,'arquivo');
      $w_recupera_bilhetes    = f($row,'recupera');
    }
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_pessoa_endereco','Endereço','SELECT','1','1','18','1','1');
      Validate('w_arquivo_bilhetes','Arquivo','1','1','1','60','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_sq_pessoa_endereco','Cidade','SELECT','1','1','18','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='E') {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    if ($p_sq_pessoa_endereco>'') {
      ShowHTML(' <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    } else {
      ShowHTML(' <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($P2>0) {
      ShowHTML('          <td><font size="1"><b> Cidade    </font></td>');
      ShowHTML('          <td><font size="1"><b> Endereço  </font></td>');
    } else {
      if (count($RS)>1) {
        ShowHTML('           <td><font size="1"><b><a class="SS" href="'.$w_dir.$w_pagina.$par.'&p_ordena=nm_cidade,logradouro&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Cidade</a></font></td>');
        ShowHTML('           <td><font size="1"><b><a class="SS" href="'.$w_dir.$w_pagina.$par.'&p_ordena=logradouro,nm_cidade&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Endereço</a></font></td>');
      } else {
        ShowHTML('          <td><font size="1"><b> Cidade    </font></td>');
        ShowHTML('          <td><font size="1"><b> Endereço  </font></td>');
      } 
    } 
    ShowHTML('          <td><font size="1"><b> Arquivo   </font></td>');
    ShowHTML('          <td><font size="1"><b> Recupera  </font></td>');
    ShowHTML('          <td><font size="1"><b> Operações </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nm_cidade').' - '.f($row,'uf').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'logradouro').'                   </td>');
        ShowHTML('        <td><font size="1">'.f($row,'arquivo').'                   </td>');
        if (f($row,'recupera')=='S') {
          ShowHTML(' <td align="center"><font size="1">Sim</td>');
        } else {
          ShowHTML(' <td align="center"><font size="1">Não</td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_sq_pessoa_endereco='.f($row,'sq_pessoa_endereco').'&w_arquivo_bilhetes='.f($row,'arquivo').'&w_recupera_bilhetes='.f($row,'recupera').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_sq_pessoa_endereco='.f($row,'sq_pessoa_endereco').'&w_arquivo_bilhetes='.f($row,'arquivo').'&w_recupera_bilhetes='.f($row,'recupera').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'TRONCOS&R='.$w_pagina.$par.'&O=L&w_sq_central_fone='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.' - Parâmetros&SG='.$SG.MontaFiltro('GET').'" Target="_blank" Title="Visualizar e manipular os trocos desta central  ">Troncos</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'USUARIOCENTRAL&R='.$w_pagina.$par.'&O=L&w_sq_central_fone='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.' - Parâmetros&SG='.$SG.MontaFiltro('GET').'" Target="_blank" Title="Visualizar e manipular os usuários desta central">Usuários</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0"><tr>');
    SelecaoEndereco('End<u>e</u>reço:','E',null,$w_sq_pessoa_endereco,$w_sq_pessoa_endereco,'w_sq_pessoa_endereco','FISICO');
    ShowHTML('      <tr><td valign="top"><font size="1"><b>A<u>r</u>quivo:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_arquivo_bilhetes" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_arquivo_bilhetes.'"></td>');
    MontaRadioSN('Recupera Bilhetes',$w_recupera_bilhetes,'w_recupera_bilhetes');
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    // filtragem de fluxo de dados
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr>');
    SelecaoCidadeCentral('<u>C</u>idade:','C',null,$p_sq_pessoa_endereco,'p_sq_pessoa_endereco',null);
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG).'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de Troncos da Central
// -------------------------------------------------------------------------
function Troncos() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave              = $_REQUEST['w_chave'];
  $w_troca              = $_REQUEST['w_troca'];
  $w_sq_central_fone    = $_REQUEST['w_sq_central_fone'];
  $w_sq_pessoa_telefone = $_REQUEST['w_sq_pessoa_telefone'];
  $w_codigo             = $_REQUEST['w_codigo'];
  $w_ativo              = $_REQUEST['w_ativo'];
  $w_chaveAux           = $_REQUEST['w_chaveAux'];

  // Recupera sempre todos os registros
  $RS = db_getCentralTel::getInstanceOf($dbms,$w_chave,null,null,null,null);
  $RS = SortArray($RS,strtolower($_REQUEST['p_ordena']),'asc');
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>SIW - Troncos da Central</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_codigo','Codigo','1','1','1','10','1','1');
      Validate('w_sq_pessoa_telefone','Telefone','SELECT','1','1','18','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
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
  if ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  foreach ($RS as $row) {
    ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=4><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><font size="1">Cidade-UF: <br><b>'.f($row,'nm_cidade').' - '.f($row,'uf').'</font></td>');
    ShowHTML('          <td><font size="1">Endereço:  <br><b>'.f($row,'logradouro').'</font></td>');
    ShowHTML('        <tr colspan=3>');
    ShowHTML('          <td><font size="1">Arquivo:   <br><b>'.f($row,'arquivo').'</font></td>');
    if (f($row,'recupera')=='S') {
      ShowHTML('        <td><font size="1">Recupera:  <br><b>Sim                                        </font></td>');
    } else {
      ShowHTML('        <td><font size="1">Recupera:  <br><b>Não                                        </font></td>');
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('</table>');
  }
  if ($O=='L') {
    $RS = db_getCentralTel::getInstanceOf($dbms,$w_chave,$w_cliente,$w_sq_central_fone,$w_sq_pessoa_telefone,'TRONCO');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($P2>0) {
      ShowHTML('          <td><font size="1"><b>Telefone  </b></font></td>');
      ShowHTML('          <td><font size="1"><b>Código    </b></font></td>');
      ShowHTML('          <td><font size="1"><b>Tipo      </b></font></td>');
      ShowHTML('          <td><font size="1"><b>Ativo     </b></font></td>');
    } else {
      ShowHTML('          <td><font size="1"><b><a class="SS" href="'.$w_dir.$w_pagina.$par.'&p_ordena=num_tel, codigo, ativo&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Prefixo</a></font></td>');
      ShowHTML('          <td><font size="1"><b><a class="SS" href="'.$w_pagina.$par.'&p_ordena=codigo, num_tel, ativo&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Prefixo</a></font></td>');
      ShowHTML('          <td><font size="1"><b>Tipo      </b></font></td>');
      ShowHTML('          <td><font size="1"><b><a class="SS" href="'.$w_pagina.$par.'&p_ordena=ativo, num_tel, codigo&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Prefixo</a></font></td>');
    } 
    ShowHTML('          <td><font size="1"><b>Operações </b></font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      //Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">('.f($row,'ddd').') '.f($row,'num_tel').' </td>');
        ShowHTML('        <td><font size="1"> '.f($row,'codigo').' </td>');
        ShowHTML('        <td><font size="1"> '.f($row,'nm_tipo').' </td>');
        if (f($row,'ativo')=='S') {
          ShowHTML('        <td><font size="1">Sim                               </font> </td>');
        } else {
          ShowHTML('        <td><font size="1">Não                               </font> </td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chaveAux='.f($row,'chave').'&w_sq_pessoa_telefone='.f($row,'sq_pessoa_telefone').'&w_codigo='.f($row,'codigo').'&w_ativo='.f($row,'ativo').'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chaveAux='.f($row,'chave').'&w_sq_pessoa_telefone='.f($row,'sq_pessoa_telefone').'&w_codigo='.f($row,'codigo').'&w_ativo='.f($row,'ativo').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Exluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('AIEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'TTTRONCO',$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<tr><td align="center" colspan=4>');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_central_fone" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chaveAux" value="'.$w_chaveAux.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sg" value="'.$SG.'">');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>C</u>odigo:   </b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="8"  MAXLENGTH="4"  VALUE="'.$w_codigo.'"></td>');
    MontaRadioSN('Ativo',$w_ativo,'w_ativo');
    if ($O=='I') {
      selecaoTelefoneTT('<u>T</u>elefône:','T',null,$w_sq_pessoa_telefone,$w_cliente,'w_sq_pessoa_telefone',null,'TRONCO');
    } else {
      selecaoTelefoneTT('<u>T</u>elefône:','T',null,$w_sq_pessoa_telefone,$w_cliente,'w_sq_pessoa_telefone','A',null);
    } 
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de Ramais
// -------------------------------------------------------------------------
function Ramais() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave           = $_REQUEST['w_chave'];
  $w_troca           = $_REQUEST['w_troca'];
  $w_sq_central_fone = $_REQUEST['w_sq_central_fone'];
  $w_codigo          = $_REQUEST['w_codigo'];

  if ($w_troca>'') {
    $w_sq_central_fone  = $_REQUEST['w_sq_central_fone'];
    $w_codigo           = $_REQUEST['w_codigo'];
  } elseif ($O=='L') {
    $RS = db_getTTRamal::getInstanceOf($dbms,null,null,null,null);
  } elseif ((!(strpos('AEV',$O)===false)) && $w_Troca=='') {
    $RS = db_getTTRamal::getInstanceOf($dbms,$w_chave,$w_sq_central_fone,$w_codigo,null);
    foreach ($RS as $row) {
      $w_chave            = f($row,'chave');
      $w_sq_central_fone  = f($row,'sq_central_fone');
      $w_codigo           = f($row,'codigo');
    }
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_central_fone','Central Telefônica','SELECT','1','1','18','1','1');
      Validate('w_codigo','Código','1','1','1','4','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
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
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='E') {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b> Cidade    </font></td>');
    ShowHTML('          <td><font size="1"><b> Endereço  </font></td>');
    ShowHTML('          <td><font size="1"><b> Ramal     </font></td>');
    ShowHTML('          <td><font size="1"><b> Operações </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nm_cidade').' - '.f($row,'uf').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'logradouro').'                   </td>');
        ShowHTML('        <td><font size="1">'.f($row,'codigo').'                   </td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"  Title="Nome">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'RAMALUSR&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.' - Parâmetros&SG='.$SG.MontaFiltro('GET').'" Target="_blank" Title="Visualizar e manipular os usuários deste ramal">Usuários</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0"><tr>');
    SelecaoCentralFone('Central Tele<u>f</u>ônica:','F',null,$w_sq_central_fone,'w_sq_central_fone',null);
    ShowHTML('      <tr><td valign="top"><font size="1"><b>Có<u>d</u>igo</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_codigo" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_codigo.'"></td>');
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina dos Usuarios da Central Telefônica
// -------------------------------------------------------------------------
function UsuarioCentral() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave           = $_REQUEST['w_chave']; 
  $w_chaveAux        = $_REQUEST['w_chaveAux'];
  $w_troca           = $_REQUEST['w_troca'];
  $w_usuario         = $_REQUEST['w_usuario'];
  $w_sq_central_fone = $_REQUEST['w_sq_central_fone'];
  $w_codigo          = $_REQUEST['w_codigo'];

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>SIW - Associação entre Central telefônica e Usuários</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_usuario','Usuário','SELECT','1','1','18','1','1');
      Validate('w_codigo','código','1','1','2','2','1','');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
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
  if ($O=='I') {
    BodyOpen('onLoad="document.Form.w_usuario.focus()";');
  } elseif ($O=='A') {
    BodyOpen('onLoad="document.Form.w_codigo.focus()";');
  } elseif ($O=='E') {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('          <td><br><b>Central Telefônica</td>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Recupera sempre todos os registros
  $RS = db_getCentralTel::getInstanceOf($dbms,$w_chave,null,null,null,null);
  foreach ($RS as $row) {
    ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><font size="1">Cidade-UF: <br><b>'.f($row,'nm_cidade').' - '.f($row,'uf').'</font></td>');
    ShowHTML('          <td><font size="1">Endereço:  <br><b>'.f($row,'logradouro').'</font></td>');
    ShowHTML('        <tr colspan=3>');
    ShowHTML('          <td><font size="1">Arquivo:   <br><b>'.f($row,'arquivo').'</font></td>');
    if (f($row,'recupera')=='S') {
      ShowHTML('        <td><font size="1">Recupera:  <br><b>Sim                                        </font></td>');
    } else {
      ShowHTML('        <td><font size="1">Recupera:  <br><b>Não                                        </font></td>');
    } 
    ShowHTML('    </TABLE>');
  }
  ShowHTML('</table>');
  ShowHTML('</table>');
  if ($O=='L') {
    $RS = db_getCentralTel::getInstanceOf($dbms,$w_chave,$w_cliente,null,null,'USER');
    $RS = SortArray($RS,'nm_usuario','asc');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<div align=center><center>');
    ShowHTML('          <td><br><b>Usuários</td>');
    ShowHTML('<div align=left><left>');
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_central_fone='.$w_sq_central_fone.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Código    </b></font></td>');
    ShowHTML('          <td><font size="1"><b>Nome      </b></font></td>');
    ShowHTML('          <td><font size="1"><b>Operações </b></font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      //Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'codigo').'</td>');
        ShowHTML('        <td><font size="1">'.f($row,'nm_usuario').' ('.f($row,'nm_usuario_res').')</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_usuario_central').'&w_sq_central_fone='.$w_sq_central_fone.'&w_codigo='.f($row,'codigo').'&w_usuario='.f($row,'usuario').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_usuario_central').'&w_sq_central_fone='.$w_sq_central_fone.'&w_codigo='.f($row,'codigo').'&w_usuario='.f($row,'usuario').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('AIEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'TTUSUCTRL',$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_central_fone" value="'.$w_sq_central_fone.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sg" value="'.$SG.'">');
    if ($O!='I') ShowHTML('<INPUT type="hidden" name="w_usuario" value="'.$w_usuario.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I') {
      selecaoPessoaTT('Usuá<u>r</u>io:','R',null,$w_sq_central_fone,$w_cliente,'w_usuario','TTUSUCENTRAL');
    } else {
      $RS = db_getPersonData::getInstanceOf($dbms, $w_cliente, $w_usuario, null, null);
      ShowHTML('      <tr><td valign="top"><font size="1">Usuário: <br><b>'.f($RS,'nome').'</b>');
    }
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>C</u>odigo:   </b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_codigo" class="sti" SIZE="8"  MAXLENGTH="2"  VALUE="'.$w_codigo.'"></td>');
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    if ($O=='E') {
      ShowHTML('      <td>');
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('      <td>');
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('      <td>');
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_sq_central_fone='.$w_sq_central_fone.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina dos Usuarios da Central Telefônica
// -------------------------------------------------------------------------
function RamalUsr() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chaveAux   = $_REQUEST['w_chaveAux'];
  $w_chaveAux2  = $_REQUEST['w_inicio'];
  $w_troca      = $_REQUEST['w_troca'];
  $w_inicio     = $_REQUEST['w_inicio'];
  $w_fim        = $_REQUEST['w_fim'];

  // Recupera sempre todos os registros
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>SIW - Associação entre Central telefônica e Usuários</TITLE>');
  if (!(strpos('IAEF',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    Modulo();
    FormataData();
    ValidateOpen('Validacao');
    if ($O=='I') {
      Validate('w_chaveAux','Usuário','SELECT','1','1','18','1','1');
      Validate('w_inicio','Início','DATA','1','8','10','','0123456789/');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='A') {
      Validate('w_inicio','Início','DATA','1','10','10','','0123456789/');
      if (Nvl($w_fim,'')!='') {
        Validate('w_fim','Fim','DATA','1','10','10','','0123456789/');
        CompData('w_inicio','Início','<=','w_fim','Fim');
      } 
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='F') {
      Validate('w_fim','Fim','DATA','1','10','10','','0123456789/');
      CompData('w_fim','Fim','>=','w_inicio','Início');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    }  
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='E') {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } elseif ($O=='F') {
    BodyOpen('onLoad="document.Form.w_fim.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('          <td><br><b>Ramal</td>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  $RS = db_getTTRamal::getInstanceOf($dbms,$w_chave,null,null,null);
  foreach ($RS as $row) {
    ShowHTML('<tr><td align="center" bgcolor="#FAEBD7" colspan=3><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><font size="1">Cidade-UF: <br><b>'.f($row,'nm_cidade').' - '.f($row,'uf').'</font></td>');
    ShowHTML('          <td><font size="1">Endereço:  <br><b>'.f($row,'logradouro').'</font></td>');
    ShowHTML('          <td><font size="1">Ramal:     <br><b>'.f($row,'codigo').'</font></td>');
    ShowHTML('    </TABLE>');
  }
  ShowHTML('</table>');
  ShowHTML('</table>');
  if ($O=='L') {
    $RS = db_getTTRamal::getInstanceOf($dbms,$w_chave,null,null,'USER');
    if (nvl($_REQUEST['p_ordena'],'')>'') {
      $lista = explode(',',str_replace(' ',',',strtolower($_REQUEST['p_ordena'])));
      $RS = SortArray($RS,$lista[0],$lista[1],'inicio','desc','dt_fim','desc');
    } else {
      $RS = SortArray($RS,'inicio','desc','dt_fim','desc', 'nm_usuario', 'desc');
    }
    ShowHTML('<div align=center><center>');
    ShowHTML('          <td><br><b>Usuários</td>');
    ShowHTML('<div align=left><left>');
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.linkOrdena('Usuário','nm_usuario').'</b></font></td>');
    ShowHTML('          <td><font size="1"><b>'.linkOrdena('De','inicio').'</b></font></td>');
    ShowHTML('          <td><font size="1"><b>'.linkOrdena('Até','fim').'</b></font></td>');
    ShowHTML('          <td><font size="1"><b>Operações </b></font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nm_usuario').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'inicio')).'</td>');
        if (f($row,'fim')!='') {
          ShowHTML('      <td align="center"><font size="1">'.FormataDataEdicao(f($row,'fim')).' </td>');
        } else {
          ShowHTML('      <td align="CENTER"><font size="1"> ---                    </td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chaveAux='.f($row,'usuario').'&w_inicio='.FormataDataEdicao(f($row,'inicio')).'&w_fim='.FormataDataEdicao(f($row,'fim')).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">AL</A>&nbsp');
        if (Nvl(f($row,'fim'),'')=='') {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=F&w_chave='.$w_chave.'&w_chaveAux='.f($row,'usuario').'&w_inicio='.FormataDataEdicao(f($row,'inicio')).'&w_fim='.FormataDataEdicao(f($row,'fim')).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Finalizar</A>&nbsp');
        } 
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chaveAux='.f($row,'usuario').'&w_inicio='.FormataDataEdicao(f($row,'inicio')).'&w_fim='.FormataDataEdicao(f($row,'fim')).'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </table></center>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('AIFEV',$O)===false)) {
    if (!(strpos('EVF',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,'RAMUSR',$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chaveAux2" value="'.$w_chaveAux2.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sg" value="'.$SG.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    if ($O=='I') {
      SelecaoPessoa('Usua<u>r</u>io:','R',null,null,$w_chave,'w_chaveAux','TTUSURAMAL');
      ShowHTML('      <td title="Informe a data de início de uso deste ramal."><font size="1"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);"></td>');
    } elseif ($O=='A') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_chaveAux,null,null);
      ShowHTML('      <td><font size="1">Usuário:<br><b>'.f($RS,'nome'));
      ShowHTML('<INPUT type="hidden" name="w_chaveAux" value="'.$w_chaveAux.'">');
      ShowHTML('      <td title="Informe a data de início de uso deste ramal."><font size="1"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);"></td>');
      if ($w_fim!='') {
        ShowHTML('      <td title="Informe a data de fim de uso deste ramal."><font size="1"><b><u>F</u>im:   </b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);"></td>');
      }
    } elseif ($O=='F') {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_chaveAux,null,null);
      ShowHTML('      <td><font size="1">Usuário:<br><b>'.f($RS,'nome'));
      ShowHTML('<INPUT type="hidden" name="w_chaveAux" value="'.$w_chaveAux.'">');
      ShowHTML('      <td title="Informe a data de início de uso deste ramal."><font size="1"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);"></td>');
      ShowHTML('      <td title="Informe a data de fim de uso deste ramal."><font size="1"><b><u>F</u>im:   </b><br><input accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);"></td>');
    } else {
      $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_chaveAux,null,null);
      ShowHTML('      <td><font size="1">Usuário:<br><b>'.f($RS,'nome'));
      ShowHTML('<INPUT type="hidden" name="w_chaveAux" value="'.$w_chaveAux.'">');
      ShowHTML('      <td title="Informe a data de início de uso deste ramal."><font size="1"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);"></td>');
      if (Nvl($w_fim,'')!='') {
        ShowHTML('      <td title="Informe a data de fim de uso deste ramal."><font size="1"><b><u>F</u>im:   </b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);"></td>');
      } 
    } 
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    if ($O=='E') {
      ShowHTML('      <td>');
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('      <td>');
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } elseif ($O=='F') {
        ShowHTML('      <td>');
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Finalizar">');
      } else {
        ShowHTML('      <td>');
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chaveAux='.$w_chaveAux.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=L'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina de Prefíxos
// -------------------------------------------------------------------------
function prefixo() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_troca      = $_REQUEST['w_troca'];
  $w_prefixo    = $_REQUEST['w_prefixo'];
  $w_localidade = $_REQUEST['w_localidade'];
  $w_sigla      = $_REQUEST['w_sigla'];
  $w_uf         = $_REQUEST['w_uf'];
  $w_ddd        = $_REQUEST['w_ddd'];
  $w_controle   = $_REQUEST['w_controle'];
  $w_degrau     = $_REQUEST['w_degrau'];
  $p_prefixo    = $_REQUEST['p_prefixo'];
  $p_uf         = $_REQUEST['p_uf'];
  if ($w_troca>'') {
    $w_prefixo      = $_REQUEST['w_prefixo'];
    $w_localidade   = $_REQUEST['w_localidade'];
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_uf           = $_REQUEST['w_uf'];
    $w_ddd          = $_REQUEST['w_ddd'];
    $w_controle     = $_REQUEST['w_controle'];
    $w_degrau       = $_REQUEST['w_degrau'];
  } elseif ($O=='L') {
    $RS = db_getPrefixo::getInstanceOf($dbms,null,$p_prefixo,$p_uf);
    if ($_REQUEST['p_ordena']>'') {
       $RS = SortArray($RS,strtolower($_REQUEST['p_ordena']),'asc','prefixo','asc');
    } else {
       $RS = SortArray($RS,'prefixo','asc','localidade','asc');
    }
  } elseif ((!(strpos('AEV',$O)===false)) && $w_Troca=='') {
    $RS = db_getPrefixo::getInstanceOf($dbms,$w_chave,null,null);
    foreach ($RS as $row) {
      $w_prefixo      = f($row,'prefixo');
      $w_localidade   = f($row,'localidade');
      $w_sigla        = f($row,'sigla');
      $w_uf           = f($row,'uf');
      $w_ddd          = f($row,'ddd');
      $w_controle     = f($row,'controle');
      $w_degrau       = f($row,'degrau'); 
    }
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_prefixo','Prefixo','1','1','5','15','1','1');
      Validate('w_localidade','Localidade','1','1','5','25','1','1');
      Validate('w_sigla','sigla','1','','4','4','1','1');
      Validate('w_uf','uf','1','','2','2','1','1');
      Validate('w_ddd','ddd','1','','3','4','1','1');
      Validate('w_controle','controle','1','','12','16','1','1');
      Validate('w_degrau','degrau','1','','3','3','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclusão deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } elseif ($O=='P') {
      Validate('p_prefixo','Prefixo','1','','1','15','1','1');
      Validate('p_uf','UF','1','','2','2','1','1');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='E') {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } elseif ($O=='P') {
    BodyOpen('onLoad="document.Form.p_prefixo.focus()";');
  } else {
    BodyOpen('onLoad="this.focus()";');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    if ($p_prefixo>'' || $p_uf>'') {
      ShowHTML(' <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a></font>');
    } else {
      ShowHTML(' <a accesskey="F" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
    } 
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    if ($P2>0) {
      ShowHTML('          <td><font size="1"><b>Prefixo    </font></td>');
      ShowHTML('          <td><font size="1"><b>Localidade </font></td>');
    } else {
      ShowHTML('          <td><font size="1"><b><a class="SS" href="'.$w_dir.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Prefixo    </a></font></td>');
      ShowHTML('          <td><font size="1"><b><a class="SS" href="'.$w_dir.$w_pagina.$par.'&p_ordena=localidade&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Localidade </a></font></td>');
    } 
    ShowHTML('          <td><font size="1"><b> Sigla      </font></td>');
    ShowHTML('          <td><font size="1"><b> UF         </font></td>');
    ShowHTML('          <td><font size="1"><b> DDD        </font></td>');
    ShowHTML('          <td><font size="1"><b> Controle   </font></td>');
    ShowHTML('          <td><font size="1"><b> Degrau     </font></td>');
    ShowHTML('          <td><font size="1"><b> Operações  </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'prefixo').' </td>');
        ShowHTML('        <td><font size="1">'.f($row,'localidade').' </td>');
        if (f($row,'sigla')!='') {
          ShowHTML('      <td><font size="1">'.f($row,'sigla').' </td>');
        } else {
          ShowHTML('      <td><font size="1"> ---                     </td>');
        } 
        if (f($row,'uf')!='') {
          ShowHTML('      <td><font size="1">'.f($row,'uf').' </td>');
        } else {
          ShowHTML('      <td><font size="1"> ---                     </td>');
        } 
        if (f($row,'ddd')!='') {
          ShowHTML('      <td><font size="1">'.f($row,'ddd').' </td>');
        } else {
          ShowHTML('      <td><font size="1"> ---                     </td>');
        } 
        if (f($row,'controle')!='') {
          ShowHTML('      <td><font size="1">'.f($row,'controle').' </td>');
        } else {
          ShowHTML('      <td><font size="1"> ---                     </td>');
        } 
        if (f($row,'degrau')!='') {
          ShowHTML('      <td><font size="1">'.f($row,'degrau').' </td>');
        } else {
          ShowHTML('      <td><font size="1"> ---                     </td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"  Title="Nome">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca"   value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>P</u>refixo:    </b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo"    class="sti" SIZE="20" MAXLENGTH="15" VALUE="'.$w_prefixo.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><u>L</u>ocalidade: </b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_localidade" class="sti" SIZE="30" MAXLENGTH="25" VALUE="'.$w_localidade.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><u>S</u>igla:      </b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla"      class="sti" SIZE="8"  MAXLENGTH="4"  VALUE="'.$w_sigla.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><u>U</u>F:         </b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_uf"         class="sti" SIZE="6"  MAXLENGTH="2"  VALUE="'.$w_uf.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>D</u>DD:        </b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_ddd"        class="sti" SIZE="8"  MAXLENGTH="4"  VALUE="'.$w_ddd.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b><u>C</u>ontrole:   </b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_controle"   class="sti" SIZE="20" MAXLENGTH="16" VALUE="'.$w_controle.'"></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>D<u>e</u>grau:     </b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_degrau"     class="sti" SIZE="6"  MAXLENGTH="3"  VALUE="'.$w_degrau.'"></td>');
    ShowHTML('      <tr><td align="LEFT" colspan=2><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif ($O=='P') {
    // filtragem de fluxo de dados
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="70%" border="0">');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>P</u>refixo: </b><br><input '.$w_Disabled.' accesskey="P" type="text" name="p_prefixo" class="sti" SIZE="20" MAXLENGTH="15" VALUE="'.$p_prefixo.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><u>U</u>F:      </b><br><input '.$w_Disabled.' accesskey="U" type="text" name="p_uf"      class="sti" SIZE="6"  MAXLENGTH="2"  VALUE="'.$p_uf.'"></td>');
    ShowHTML('      <tr><td valign="top"><font size="1"><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG).'\';" name="Botao" value="Limpar campos">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  switch ($SG) {
    case 'TTCENTRAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTCentral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
            $_REQUEST['w_sq_pessoa_endereco'],$_REQUEST['w_arquivo_bilhetes'],$_REQUEST['w_recupera_bilhetes']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'TTTRONCO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTTronco::getInstanceOf($dbms,$O,$_REQUEST['w_chaveAux'],$_REQUEST['w_cliente'],
            $_REQUEST['w_chave'],$_REQUEST['w_sq_pessoa_telefone'],$_REQUEST['w_codigo'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$_REQUEST['w_sg'].MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'TTRAMAL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTRamal::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_sq_central_fone'],$_REQUEST['w_codigo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'TTPREFIXO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTPrefixo::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_prefixo'],
            $_REQUEST['w_localidade'],$_REQUEST['w_sigla'],$_REQUEST['w_uf'],$_REQUEST['w_ddd'],$_REQUEST['w_controle'],
            $_REQUEST['w_degrau']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'TTUSUCTRL':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTUsuarioCentral::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,
            $_REQUEST['w_usuario'],$_REQUEST['w_sq_central_fone'],$_REQUEST['w_codigo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_sq_central_fone='.$_REQUEST['w_sq_central_fone'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$_REQUEST['w_sg'].MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'RAMUSR':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putTTRamalUsuario::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],
            $_REQUEST['w_chaveAux'],$_REQUEST['w_chaveAux2'],$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$_REQUEST['w_sg'].MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
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
  case 'CENTRAL':           CentralTel();       break;
  case 'TRONCOS':           Troncos();          break;
  case 'RAMAL':             Ramais();           break;
  case 'USUARIOCENTRAL':    UsuarioCentral();   break;
  case 'RAMALUSR':          RamalUsr();         break;
  case 'PREFIXO':           Prefixo();          break;
  case 'GRAVA':             Grava();            break;
  default:
    Cabecalho();
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