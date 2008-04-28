<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getUserData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCountryList.php');
include_once($w_dir_volta.'classes/sp/db_getRegionList.php');
include_once($w_dir_volta.'classes/sp/db_getStateList.php');
include_once($w_dir_volta.'classes/sp/db_getCityList.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador_Aferidor.php');
include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php');
include_once($w_dir_volta.'classes/sp/db_getTipoIndicador.php');
include_once($w_dir_volta.'classes/sp/db_getSolicIndicador.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador_Aferidor.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador_Afericao.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicIndicador.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador_Meta.php');
include_once($w_dir_volta.'funcoes/selecaoUnidadeMedida.php');
include_once($w_dir_volta.'funcoes/selecaoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoBaseGeografica.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoTipoIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoUsuUnid.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');

// =========================================================================
//  /indicador.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerenciar a tabela de indicadores
// Mail     : alex@sbpi.com.br
// Criacao  : 29/01/2007, 17:14
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
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);

$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina     = 'indicador.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_pe/';
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];

if ($SG=='METASOLIC') {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif ($O=='') {
  $O='L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';        break;
  case 'A': $w_TP=$TP.' - Altera��o';       break;
  case 'E': $w_TP=$TP.' - Exclus�o';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - C�pia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'M': $w_TP=$TP.' - Servi�os';        break;
  case 'H': $w_TP=$TP.' - Heran�a';         break;
  case 'T': $w_TP=$TP.' - Ativar';          break;
  case 'D': $w_TP=$TP.' - Desativar';       break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();

// Recupera os dados da op��o selecionada
$RS_Menu = db_getMenuData::getInstanceOf($dbms,$w_menu);
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de indicador
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome              = $_REQUEST['w_nome'];
    $w_sigla             = $_REQUEST['w_sigla'];
    $w_tipo_indicador    = $_REQUEST['w_tipo_indicador'];
    $w_unidade_medida    = $_REQUEST['w_unidade_medida'];
    $w_descricao         = $_REQUEST['w_descricao'];
    $w_forma_afericao    = $_REQUEST['w_forma_afericao'];
    $w_fonte_comprovacao = $_REQUEST['w_fonte_comprovacao'];
    $w_ciclo_afericao    = $_REQUEST['w_ciclo_afericao'];
    $w_vincula_meta      = $_REQUEST['w_vincula_meta'];
    $w_exibe_mesa        = $_REQUEST['w_exibe_mesa'];
    $w_ativo             = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nm_tipo_indicador','asc','sigla','asc','nome','asc');
    } else {
      $RS = SortArray($RS,'nm_tipo_indicador','asc','sigla','asc','nome','asc');
    }
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endere�o informado
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_nome              = f($RS,'nome');
    $w_sigla             = f($RS,'sigla');
    $w_tipo_indicador    = f($RS,'sq_tipo_indicador');
    $w_unidade_medida    = f($RS,'sq_unidade_medida');
    $w_descricao         = f($RS,'descricao');
    $w_forma_afericao    = f($RS,'forma_afericao');
    $w_fonte_comprovacao = f($RS,'fonte_comprovacao');
    $w_ciclo_afericao    = f($RS,'ciclo_afericao');
    $w_vincula_meta      = f($RS,'vincula_meta');
    $w_exibe_mesa        = f($RS,'exibe_mesa');
    $w_ativo             = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','1','60','1','1');
      Validate('w_sigla','Sigla','1','1','1','15','1','1');
      Validate('w_tipo_indicador','Tipo do indicador','SELECT','1','1','18','','1');
      Validate('w_unidade_medida','Unidade de medida','SELECT','1','1','18','','1');
      Validate('w_descricao','Descri��o','1','1','1','2000','1','1');
      Validate('w_forma_afericao','Forma de aferi��o','1','1','1','2000','1','1');
      Validate('w_fonte_comprovacao','Fonte de comprova��o','1','1','1','2000','1','1');
      Validate('w_ciclo_afericao','Ciclo de afericao','1','1','1','2000','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
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
  } elseif ((strpos('IA',$O)!==false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>'); 
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.linkOrdena('Tipo','nm_tipo_indicador').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('�ltima aferi��o','phpdt_data_afericao').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Vincula meta','nm_vincula_meta').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Exibe mesa','nm_exibe_mesa').'</td>');
    ShowHTML('          <td><b>'.linkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_indicador').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.nvl(formataDataEdicao(f($row,'phpdt_afericao')),'---').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_vincula_meta').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_exibe_mesa').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.$w_dir.$w_pagina.'Aferidor&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Aferidores&SG=EOINDAFR'.'\',\'Indicador\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');" title="Indica os respons�veis pela aferi��o do indicador.">Aferidores</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled   = ' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='I') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('          <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('        <tr valign="top">');
    selecaoTipoIndicador('<U>T</U>ipo:','M','Selecione o tipo do indicador',$w_tipo_indicador,null,'w_tipo_indicador','REGISTROS','S');
    selecaoUnidadeMedida('Unidade de <U>m</U>edida:','M','Selecione a unidade de medida do indicador',$w_unidade_medida,null,'w_unidade_medida','REGISTROS','S');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td><b><U>D</U>efini��o:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Descreva o que o indicador pretende medir." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><b><U>F</U>orma de aferi��o:<br><TEXTAREA ACCESSKEY="F" class="sti" name="w_forma_afericao" rows=5 cols=80 title="Descreva como o indicador deve ser aferido." '.$w_Disabled.'>'.$w_forma_afericao.'</textarea></td>');
    ShowHTML('      <tr><td><b>F<U>o</U>nte de comprova��o:<br><TEXTAREA ACCESSKEY="O" class="sti" name="w_fonte_comprovacao" rows=5 cols=80 title="Indique a(s) fonte(s) de comprova��o dos valores aferidos para o indicador." '.$w_Disabled.'>'.$w_fonte_comprovacao.'</textarea></td>');
    ShowHTML('      <tr><td><b><U>C</U>iclo de aferi��o sugerido:<br><TEXTAREA ACCESSKEY="C" class="sti" name="w_ciclo_afericao" rows=5 cols=80 title="Informe o ciclo de aferi��o sugerido para o indicador." '.$w_Disabled.'>'.$w_ciclo_afericao.'</textarea></td>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Este indicador pode ser vinculado a metas</b>?',$w_vincula_meta,'w_vincula_meta');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Este indicador deve ser exibido na mesa de trabalho</b>?',$w_exibe_mesa,'w_exibe_mesa');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('</center>');
  Rodape();
} 
// =========================================================================
// Rotina de montagem da estrutura de frames para visualiza��o das aferi��es de indicador
// -------------------------------------------------------------------------
function FramesAfericao() {
  extract($GLOBALS);
  ShowHTML('<HTML> ');
  ShowHTML('  <HEAD> ');
  ShowHTML('  <link rel="shortcut icon" href="'.$conRootSIW.'favicon.ico" type="image/ico" />');
  Estrutura_CSS($w_cliente);
  ShowHTML('  <TITLE>'.$conSgSistema.' - Indicadores</TITLE> ');
  ShowHTML('  </HEAD> ');
  ShowHTML('    <FRAMESET ROWS="130,*"> ');
  ShowHTML('     <FRAME SRC="'.$w_pagina.'VisualAfericao&'.substr($_SERVER['QUERY_STRING'],strpos($_SERVER['QUERY_STRING'],'&')).'" SCROLLING="NO" FRAMEBORDER="0" FRAMESPACING=0 NAME="pesquisa"> ');
  ShowHTML('     <FRAME SRC="'.$w_pagina.'VisualDados&'.substr($_SERVER['QUERY_STRING'],strpos($_SERVER['QUERY_STRING'],'&')).'" SCROLLING="AUTO" FRAMEBORDER="0" FRAMESPACING=0 NAME="resultado"> ');
  ShowHTML('    </FRAMESET> ');
  ShowHTML('</HTML> ');
}
// =========================================================================
// Rotina de visualiza��o das aferi��es de indicador
// -------------------------------------------------------------------------
function VisualAfericao() {
  extract($GLOBALS);
  Global $p_Disabled;
  $p_pesquisa       = strtoupper($_REQUEST['p_pesquisa']);
  $p_volta          = strtoupper($_REQUEST['p_volta']);
  $p_tipo_indicador = $_REQUEST['p_tipo_indicador'];
  $p_indicador      = $_REQUEST['p_indicador'];
  $p_base           = $_REQUEST['p_base'];
  $p_pais           = $_REQUEST['p_pais'];
  $p_regiao         = $_REQUEST['p_regiao'];
  $p_uf             = $_REQUEST['p_uf'];
  $p_cidade         = $_REQUEST['p_cidade'];

  if (nvl($p_tipo_indicador,'nulo')!=nulo && nvl($p_indicador,'nulo')=='nulo') {
    // Se h� apenas um indicador com aferi��o, seleciona automaticamente.
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$usuario,null,null,null,null,$p_tipo_indicador,'S',null,null,null,null,null,null,null,null,null,'VS'.$p_volta);
    if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_indicador = f($RS,'chave'); $w_troca = 'p_base'; }
  }
  if (nvl($p_indicador,'nulo')!='nulo') {
    // Se h� apenas uma base geogr�fica do indicador com aferi��o, seleciona automaticamente.
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$usuario,$p_indicador,null,null,null,null,'S',null,null,null,null,null,null,null,null,null,'VISUALBASE');
    if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_base = f($RS,'chave'); $w_troca = ''; }
  }
  if (nvl($p_base,'nulo')!='nulo') {
    // Se n�o for base organizacional.
    if ($p_base!=5) {
      // Se h� apenas um pa�s na base geogr�fica do indicador com aferi��o, seleciona automaticamente.
      $RS = db_getCountryList::getInstanceOf($dbms, 'INDICADOR', $w_cliente, 'S', null);
      if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_pais = f($RS,'sq_pais'); $w_troca = ''; }
  
      // Trata a recupera��o autom�tica de regi�o, estado e cidade.
      if ($p_base>1 && nvl($p_pais,'')!='') {
        $RS = db_getRegionList::getInstanceOf($dbms, $p_pais, 'INDICADOR', $w_cliente);
        if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_regiao = f($RS,'sq_regiao'); $w_troca = ''; }
      }
      if ($p_base>2 && (nvl($p_pais,'')!='' || nvl($p_regiao,'')!='')) {
        $RS = db_getStateList::getInstanceOf($dbms, $p_pais, $p_regiao, 'S', $w_cliente);
        if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_uf = f($RS,'co_uf'); $w_troca = ''; }
        if ($p_base==4) {
          $RS = db_getCityList::getInstanceOf($dbms, $p_pais, $p_uf, $w_cliente, 'INDICADOR');
          if (count($RS)==1) { foreach($RS as $row) { $RS = $row; break; } $p_cidade = f($RS,'sq_cidade'); $w_troca = ''; }
        }
      }
    }
  }

  // Recupera os nomes 
  if ($p_pesquisa!='LIVRE') {
    if (nvl($p_tipo_indicador,'nulo')!=nulo) {
      $RS = db_getTipoIndicador::getInstanceOf($dbms,$w_cliente,$p_tipo_indicador,null,null,'REGISTROS');
      foreach ($RS as $row) {$RS = $row; break;}
      $w_nm_tipo_indicador = f($RS,'nome');
    }
    if (nvl($p_indicador,'nulo')!=nulo) {
      $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$p_indicador,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) { $RS = $row; break; }
      $w_nm_indicador = f($RS,'nome');
    }
    $w_nm_base_geografica = retornaBaseGeografica($p_base);
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Aferidores</TITLE>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  switch ($p_pesquisa) {
    case 'LIVRE':
      Validate('p_tipo_indicador','Tipo do indicador','SELECT','1','1','18','','1');
      Validate('p_indicador','Indicador','SELECT','1','1','18','','1');
      Validate('p_base','Base geogr�fica','SELECT','1','1','18','','1');
      break;
    case 'INDICADOR':
      Validate('p_indicador','Indicador','SELECT','1','1','18','','1');
      Validate('p_base','Base geogr�fica','SELECT','1','1','18','','1');
      break;
    case 'BASE':
      Validate('p_base','Base geogr�fica','SELECT','1','1','18','','1');
      break;
  } 
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } else {
    BodyOpen(null);
  } 
  ShowHTML('<table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
  ShowHTML('  <td><font size=2><b>Consulta a indicadores</b></font>');
  if ($p_volta=='MESA') {
    $RS_Volta = db_getLinkData::getInstanceOf($dbms,$w_cliente,$p_volta);
    ShowHTML('  <td align="right"><a class="SS" href="'.$conRootSIW.f($RS_Volta,'link').'&P1='.f($RS_Volta,'p1').'&P2='.f($RS_Volta,'p2').'&P3='.f($RS_Volta,'p3').'&P4='.f($RS_Volta,'p4').'&TP=<img src='.f($RS_Volta,'imagem').' BORDER=0>'.f($RS_Volta,'nome').'&SG='.f($RS_Volta,'sigla').'" target="content">Voltar para '.f($RS_Volta,'nome').'</a>');
  } 
  ShowHTML('</table>');
  ShowHTML('<HR>');
  ShowHTML('<table border=1 width="100%"><tr><td bgcolor="#FAEBD7" align="center">');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  AbreForm('Form',$w_dir.$w_pagina.'VisualDados','POST','return(Validacao(this));','resultado',$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
  ShowHTML('<INPUT type="hidden" name="p_pesquisa" value="'.$p_pesquisa.'">');
  ShowHTML('<INPUT type="hidden" name="p_volta" value="'.$p_volta.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('        <tr><td width="25%"><td width="25%"><td width="25%"><td width="25%"></tr>');
  ShowHTML('        <tr valign="top">');
  switch ($p_pesquisa) {
    case 'LIVRE':
      selecaoTipoIndicador('<U>T</U>ipo do indicador:','M','Selecione o tipo do indicador',$p_tipo_indicador,null,'p_tipo_indicador','VS'.$p_volta,'onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.p_indicador.value=\'\'; document.Form.p_base.value=\'\'; document.Form.w_troca.value=\'p_indicador\'; document.Form.submit();"');
      selecaoIndicador('<U>I</U>ndicador:','I','Selecione o indicador',$p_indicador,null,$w_usuario,$p_tipo_indicador,'p_indicador','VS'.$p_volta,'onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.p_base.value=\'\'; document.Form.w_troca.value=\'p_base\'; document.Form.submit();"');
      selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da aferi�ao',$p_base,$w_usuario,$p_indicador,'p_base','VISUALBASE','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_base\'; document.Form.submit();"');
      break;
    case 'INDICADOR':
      ShowHTML('<INPUT type="hidden" name="p_tipo_indicador" value="'.$p_tipo_indicador.'">');
      ShowHTML('          <td>Tipo do indicador:<br><b>'.$w_nm_tipo_indicador.'</b>');
      selecaoIndicador('<U>I</U>ndicador:','I','Selecione o indicador',$p_indicador,null,$w_usuario,$p_tipo_indicador,'p_indicador','VS'.$p_volta,'onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.p_base.value=\'\'; document.Form.w_troca.value=\'p_base\'; document.Form.submit();"');
      selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da aferi�ao',$p_base,$w_usuario,$p_indicador,'p_base','VISUALBASE','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_base\'; document.Form.submit();"');
      break;
    case 'BASE':
      ShowHTML('<INPUT type="hidden" name="p_tipo_indicador" value="'.$p_tipo_indicador.'">');
      ShowHTML('<INPUT type="hidden" name="p_indicador" value="'.$p_indicador.'">');
      ShowHTML('          <td>Tipo do indicador:<br><b>'.$w_nm_tipo_indicador.'</b>');
      ShowHTML('          <td>Indicador:<br><b>'.$w_nm_indicador.'</b>');
      selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da aferi�ao',$p_base,$w_usuario,$p_indicador,'p_base','VISUALBASE','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_base\'; document.Form.submit();"');
      break;
    default:
      ShowHTML('<INPUT type="hidden" name="p_tipo_indicador" value="'.$p_tipo_indicador.'">');
      ShowHTML('<INPUT type="hidden" name="p_indicador" value="'.$p_indicador.'">');
      ShowHTML('<INPUT type="hidden" name="p_base" value="'.$p_base.'">');
      ShowHTML('          <td>Tipo do indicador:<br><b>'.$w_nm_tipo_indicador.'</b>');
      ShowHTML('          <td>Indicador:<br><b>'.$w_nm_indicador.'</b>');
      ShowHTML('          <td>Base geogr�fica:<br><b>'.$w_nm_base_geografica.'</b>');
      break;
  } 
  if (nvl($p_tipo_indicador,'nulo')!='nulo' && nvl($p_indicador,'nulo')!='nulo' && nvl($p_base,'nulo')!='nulo') {
    ShowHTML('          <td valign="bottom"><input class="STB" type="submit" name="Botao" value="Atualizar listagem">');
  }
  if (nvl($p_base,-1)!=5 && nvl($p_base,-1)!=-1) {
    ShowHTML('      <tr valign="top">');
    SelecaoPais('<u>P</u>a�s: (opcional)','P',null,$p_pais,$w_cliente,'p_pais','INDICADOR','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_pais\'; document.Form.submit();"');
    if ($p_base>1) {
      SelecaoRegiao('<u>R</u>egi�o: (opcional)','R',null,$p_regiao,$p_pais,'p_regiao','INDICADOR','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      if ($p_base>2) {
        SelecaoEstado('E<u>s</u>tado: (opcional)','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',$w_cliente,'onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
        if ($p_base==4) {
          SelecaoCidade('<u>C</u>idade: (opcional)','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade','INDICADOR','onChange="document.Form.target=\'pesquisa\'; document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
        }
      }
    }
  }
  ShowHTML('    </FORM>');
  if (nvl($p_tipo_indicador,'nulo')!='nulo' && nvl($p_indicador,'nulo')!='nulo' && nvl($p_base,'nulo')!='nulo') {
    ScriptOpen('JavaScript');
    ShowHTML('  document.Form.submit();');
    ScriptClose();
  }
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE><BR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('</table>');
  ShowHTML('</center>');
  ShowHTML('</DIV>');
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
} 
// =========================================================================
// Rotina de visualiza�ao das aferi��es de indicadores
// -------------------------------------------------------------------------
function VisualDados() {
  extract($GLOBALS);
  global $p_Disabled;
  $p_pesquisa       = $_REQUEST['p_pesquisa'];
  $p_volta          = $_REQUEST['p_volta'];
  $p_tipo_indicador = $_REQUEST['p_tipo_indicador'];
  $p_indicador      = $_REQUEST['p_indicador'];
  $p_base           = $_REQUEST['p_base'];
  $p_inicio         = $_REQUEST['p_inicio'];
  $p_fim            = $_REQUEST['p_fim'];
  $p_pais           = $_REQUEST['p_pais'];
  $p_regiao         = $_REQUEST['p_regiao'];
  $p_uf             = $_REQUEST['p_uf'];
  $p_cidade         = $_REQUEST['p_cidade'];

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen(null);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (nvl($p_tipo_indicador,'nulo')=='nulo' || nvl($p_indicador,'nulo')=='nulo' || nvl($p_base,'nulo')=='nulo') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Quando o indicador e a base geogr�fica forem selecionados, ser� exibido o bot�o "Atualizar listagem". Clique nele para ver as aferi��es.');
    ShowHTML('  <li>As caixas de sele��o exibem apenas as op�oes que t�m pelo menos uma aferi��o registrada.');
    ShowHTML('  <li>Dependendo da base geogr�fica selecionada, ser�o exibidas caixas de sele��o opcionais para maior refinamento da pesquisa.');
    ShowHTML('  </b></font></td>');
    ShowHTML('<tr><td colspan=3>&nbsp;');
  } else {
    // Recupera todos os registros para a listagem
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$p_indicador,null,null,null,$p_tipo_indicador,'S',$p_base,$p_pais,$p_regiao,$p_uf,$p_cidade,null,null,$p_inicio,$p_fim,'AFERICAO');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    } else {
      $RS = SortArray($RS,'base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    }
  
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td rowspan=2>'.linkOrdena('Base','nm_base_geografica').'</td>');
    ShowHTML('          <td rowspan=2>'.linkOrdena('Refer�ncia','phpdt_fim').'</td>');
    ShowHTML('          <td rowspan=2>'.linkOrdena('Data da aferi��o','phpdt_afericao').'</td>');
    ShowHTML('          <td rowspan=2>'.linkOrdena('Valor aferido','valor').'</td>');
    ShowHTML('          <td width="1%" nowrap rowspan=2><b>U.M.</b></td>');
    ShowHTML('          <td rowspan=2><b>Fonte</b></td>');
    ShowHTML('          <td colspan=2><b>Dados da aferi��o</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td>'.linkOrdena('Respons�vel','nm_cadastrador').'</td>');
    ShowHTML('          <td align="center">'.linkOrdena('Data','inclusao').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $p_cont = 0;
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        // Tratamento para aferi��es com alguma observa��o
        if (nvl(f($row,'observacao'),'nulo')!='nulo') {
          $p_exibe  = true;
          $p_cont  +=1;
          $p_observacao[$p_cont] = f($row,'observacao');
        } else {
          $p_exibe  = false;
        }
        $p_cor = ($p_cor==$conTrBgColor || $p_cor=='') ? $p_cor=$conTrAlternateBgColor : $p_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$p_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_base_geografica').'</td>');
        $p_array = retornaNomePeriodo(f($row,'referencia_inicio'), f($row,'referencia_fim'));
        ShowHTML('        <td align="center">');
        if ($p_array['TIPO']=='DIA') {
          ShowHTML('        '.date(d.'/'.m.'/'.y,$p_array['VALOR']));
        } elseif ($p_array['TIPO']=='MES') {
          ShowHTML('        '.$p_array['VALOR']);
        } elseif ($p_array['TIPO']=='ANO') {
          ShowHTML('        '.$p_array['VALOR']);
        } else {
          ShowHTML('        '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_inicio')),'---').' a '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_fim')),'---'));
        }
        ShowHTML('        <td align="center">'.nvl(date(d.'/'.m.'/'.y,f($row,'phpdt_afericao')),'---').'</td>');
        ShowHTML('        <td align="right">'.((f($row,'previsao')=='S') ? '* ' : '').(($p_exibe) ? '<sup>('.$p_cont.')</sup> ' : '').nvl(formatNumber(f($row,'valor'),4),'---').'</td>');
        ShowHTML('        <td nowrap align="center">'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td>'.f($row,'fonte').'</td>');
        ShowHTML('        <td>'.ExibePessoa(null,$w_cliente,f($row,'cadastrador'),$TP,f($row,'nm_cadastrador')).'</td>');
        ShowHTML('        <td align="center">'.nvl(date(d.'/'.m.'/'.y,nvl(f($row,'phpdt_alteracao'),f($row,'phpdt_inclusao'))),'---').'</td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td colspan=3><table border=0>');
    ShowHTML('  <tr><td align="right">(U.M.)<td>Unidade de medida');
    ShowHTML('  <tr><td align="right">(*)<td>Proje��o');
    if ($p_cont>0) {
      for ($i=1;$i<=$p_cont;$i++) ShowHTML('  <tr valign="top"><td align="right">('.$i.')<td>'.$p_observacao[$i]);
    }
    ShowHTML('  </table>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('<p>&nbsp;</p></tr>');
  }

  if (nvl($p_indicador,'nulo')!='nulo') {
    // Recupera os dados do indicador para exibi��o no cabe�alho
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$p_indicador,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) { $RS = $row; break; }
    ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7">');
    ShowHTML('  <tr valign="top">');
    ShowHTML('    <td valign="middle"><font size="1"><b><font class="SS">'.strtoupper(f($RS,'nome')).'</font></b></td>');
    ShowHTML('    <td nowrap>Sigla:<br><b><font size=1 class="hl">'.f($RS,'sigla').'</font></b></td>');
    ShowHTML('    <td nowrap>Tipo:<br><b><font size=1 class="hl">'.f($RS,'nm_tipo_indicador').'</font></b></td>');
    ShowHTML('    <td nowrap>Unidade de medida:<br><b><font size=1 class="hl">'.f($RS,'sg_unidade_medida').' ('.f($RS,'nm_unidade_medida').')'.'</font></b></td>');
    ShowHTML('  <tr><td colspan=4><b>Defini��o:</b><br>'.nvl(crlf2br(f($RS,'descricao')),'---'));
    ShowHTML('  <tr><td colspan=4><b>Forma de aferi��o:</b><br>'.nvl(crlf2br(f($RS,'forma_afericao')),'---'));
    ShowHTML('  <tr><td colspan=4><b>Fonte de comprova��o:</b><br>'.nvl(crlf2br(f($RS,'fonte_comprovacao')),'---'));
    ShowHTML('  <tr><td colspan=4><b>Ciclo de aferi��o sugerido:</b><br>'.nvl(crlf2br(f($RS,'ciclo_afericao')),'---'));
    ShowHTML('  <tr valign="top"><td colspan=4><b>Controles associados ao indicador:</b><ul>');
    ShowHTML('      <li>Este indicador '.((f($RS,'vincula_meta')=='N') ? '<b>n�o</b> ' : '').'pode ser associado a metas.');
    ShowHTML('      <li>Este indicador '.((f($RS,'exibe_mesa')=='N') ? '<b>n�o</b> ' : '').'� exibido na mesa de trabalho.');
    ShowHTML('    </td>');
    ShowHTML('  <tr><td colspan=4><b>Respons�veis pelo registro das aferi��es:</b><ul>');
    $w_menu_indicador = retornaMenu($w_cliente,'PEINDIC');
    $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,$p_indicador,$w_menu_indicador,null,formataDataEdicao(time()),formataDataEdicao(time()),'PERMISSAO');
    $RS = SortArray($RS,'nm_pessoa','asc');
    if (count($RS)==0) {
      ShowHTML('    <li>ATEN��O: n�o h� pessoas com permiss�o para registrar as aferi��es deste indicador!');
    } else {
      foreach($RS as $row) {
        if (f($row,'gestor_sistema')=='S' || f($row,'gestor_modulo')=='S') $w_texto = ', a qualquer tempo.';
        elseif (nvl(f($row,'fim'),'nulo')!='nulo') $w_texto = ', de '.date(d.'/'.m.'/'.y,f($row,'inicio')).' a '.date(d.'/'.m.'/'.y,f($row,'fim')).'.';
        else $w_texto = ', a partir de '.date(d.'/'.m.'/'.y,f($row,'inicio')).', sem t�rmino previsto.';
        ShowHTML('    <li>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nm_pessoa')).$w_texto);
      }
    }
    ShowHTML('    </ul>');
    ShowHTML('</table>');
  }
  ShowHTML('</table>');
  Rodape();
} 
// =========================================================================
// Rotina de cadastramento dos aferidores de um indicador
// -------------------------------------------------------------------------
function Aferidor() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];

  // Recupera os dados do indicador para exibi��o no cabe�alho
  $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach ($RS as $row) { $RS = $row; break; }
  $w_nome             = f($RS,'nome');
  $w_sigla            = f($RS,'sigla');
  $w_tipo             = f($RS,'nm_tipo_indicador');
  $w_unidade_medida   = f($RS,'sg_unidade_medida').' ('.f($RS,'nm_unidade_medida').')';

  if ($w_troca>'' && $O <> 'E') {
    $w_inicio       = $_REQUEST['w_inicio'];
    $w_fim          = $_REQUEST['w_fim'];
    $w_pessoa       = $_REQUEST['w_pessoa'];
    $w_prazo        = $_REQUEST['w_prazo'];
  } elseif ($O=='L') {
    $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,'REGISTROS');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'inicio','desc','fim','desc');
    } else {
      $RS = SortArray($RS,'nm_pessoa','asc','inicio','desc','fim','desc'); 
    }
  } elseif (!(strpos('CAEV',$O)===false)) {
    $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,$w_chave,$w_chave_aux,null,null,null,'REGISTROS');
    foreach ($RS as $row) {$RS = $row; break;}
    $w_inicio       = formataDataEdicao(f($RS,'inicio'));
    $w_pessoa       = f($RS,'sq_pessoa');
    $w_prazo        = f($RS,'prazo_definido');
    if ($w_prazo=='S') $w_fim = formataDataEdicao(f($RS,'fim'));
    
  } 
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Aferidores</TITLE>');
  Estrutura_CSS($w_cliente);
  if (!(strpos('CIAE',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('CIA',$O)===false)) {
      Validate('w_pessoa','Pessoa','VALOR','1',4,18,'','0123456789,.');
      Validate('w_inicio','In�cio da responsabilidade','DATA','1','10','10','','0123456789/');
      ShowHTML('  if (theForm.w_prazo[0].checked) {');
        Validate('w_fim','T�rmino da responsabilidade','DATA','1','10','10','','0123456789/');
        CompData('w_inicio','In�cio da responsabilidade','<=','w_fim','T�rmino da responsabilidade');
      ShowHTML('  } else {');
      ShowHTML('    theForm.w_fim.value=\'\';');
      ShowHTML('  }');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclus�o deste registro?\'));');
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif (!(strpos('CIA',$O)===false)) {
    BodyOpen('onLoad=document.Form.w_pessoa.focus();');
  } elseif ($O=='L'){
    BodyOpen('onLoad="javascript:this.focus();"');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border=1 width="100%"><tr><td bgcolor="#FAEBD7">');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=3><font size="1">Indicador:<br><b><font size=1 class="hl">'.$w_nome.'</font></b></td>');
  ShowHTML('        <tr valign="top">');
  ShowHTML('          <td><font size="1">Sigla:<br><b><font size=1 class="hl">'.$w_sigla.'</font></b></td>');
  ShowHTML('          <td><font size="1">Tipo:<br><b><font size=1 class="hl">'.$w_tipo.'</font></b></td>');
  ShowHTML('          <td><font size="1">Unidade de medida:<br><b><font size=1 class="hl">'.$w_unidade_medida.'</font></b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE><BR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orienta��o:<ul><li>Insira cada uma das pessoas que ter�o a responsabilidade de registrar a aferi��o deste indicador.</ul></b></font></td>');
    ShowHTML('<tr><td>');
    ShowHTML('        <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('        <a accesskey="F" class="ss" href="#" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="middle">');
    ShowHTML('          <td><b>'.LinkOrdena('Pessoa','nm_pessoa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Per�odo','nm_prazo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('T�rmino','fim').'</td>');
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_prazo').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'inicio')).'</td>');
        if (f($row,'prazo_definido')=='S') {
          ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'fim')).'</td>');
        } else {
          ShowHTML('        <td align="center">---</td>');
        }
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave_pai').'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Altera os dados deste registro.">AL</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave_pai').'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'" Title="Exclui deste registro.">EX</A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&w_chave='.f($row,'chave_pai').'&w_chave_aux='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Inclui um novo per�odo a partir dos dados deste registro.">Copiar</A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('CIAEV',$O)===false)) {
    if (strpos('CIA',$O)!==false) {
      ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orienta��o:<ul><li>Informe os dados solicitados e execute a grava��o.<li>N�o � permitida a sobreposi��o de per�odos para uma mesma pessoa.</ul></b></font></td>');
    }
    if ($O=='C') {
      ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">ATEN��O: Dados importados de outro registro. Altere os dados necess�rios antes de executar a inclus�o.</b></font>.</td>');
    } 
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED '; 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    // Se for c�pia, n�o coloca a chave do registro para procurar corretamente sobreposi��o de per�odos
    if ($O!='C') ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0"><tr>');
    ShowHTML('      <tr valign="top">');
    SelecaoUsuUnid('<u>P</u>essoa:','P',null,$w_pessoa,null,'w_pessoa',$O);
    MontaRadioSN('<b>O prazo de responsabilidade pela aferi��o do indicador � definido?</b>',$w_prazo,'w_prazo');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td title="Informe a data inicial do per�odo de responsabilidade."><b>In�<u>c</u>io da responsabilidade:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_inicio',$w_dir_volta).'</td>');
    ShowHTML('          <td title="DEIXE EM BRANCO SE O PRAZO FOR INDEFINIDO."><b><u>T</u>�rmino da responsabilidade (apenas para prazo definido):</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_fim',$w_dir_volta).'</td>');
    ShowHTML('      <tr><td colspan=3><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I' || $O=='C') {
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$w_chave.'&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de exibi��o das permiss�es de aferi��o de um usu�rio
// -------------------------------------------------------------------------
function AferidorPerm() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  $w_chave_aux       = $_REQUEST['w_chave_aux'];

  // Verifica se o usu�rio � gestor do sistema ou do m�dulo
  $RS = db_GetUserData::getInstanceOf($dbms, $w_cliente, $_SESSION['USERNAME']);
  $w_gestor_sistema = f($RS,'gestor_sistema');
  $w_gestor_modulo  = retornaModMaster($w_cliente, $w_usuario, $w_menu);
  
  // Retorna as permiss�es se o usu�rio n�o � gestor
  //if ($w_gestor_sistema=='N' && $w_gestor_modulo='N') {
    $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,null,null,$w_usuario,null,null,'REGISTROS');
    $RS = SortArray($RS,'nm_indicador','asc','inicio','desc','fim','desc'); 
  //} 
  
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Aferidores</TITLE>');
  Estrutura_CSS($w_cliente);
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad="javascript:this.focus();"');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border=1 width="100%"><tr><td bgcolor="#FAEBD7">');
  ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('        <tr><td colspan=2><font size="1">Usu�rio:<br><b><font size=1 class="hl">'.$_SESSION['NOME'].'</font></b></td>');
  ShowHTML('        <tr valign="top">');
  ShowHTML('          <td><font size="1">Gestor do Sistema:<br><b><font size=1 class="hl">'.retornaSimNao($w_gestor_sistema).'</font></b></td>');
  ShowHTML('          <td><font size="1">Gestor do m�dulo de '.strtolower(f($RS_Menu,'nm_modulo')).':<br><b><font size=1 class="hl">'.retornaSimNao($w_gestor_modulo).'</font></b></td>');
  ShowHTML('    </TABLE>');
  ShowHTML('</TABLE><BR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($w_gestor_sistema=='S' || $w_gestor_modulo='S') {
    ShowHTML('<tr><td colspan=3><a accesskey="F" class="ss" href="#" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orienta��o:<ul><li>Voc� tem permiss�o para registrar e alterar quaisquer aferi��es de todos os indicadores.</ul></b></font></td>');
  } else {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">Orienta��o:<ul><li>Voc� s� pode registrar e alterar aferi��es de indicadores cujos per�odos de permiss�o abranjam a data de hoje.<li>As aferi�oes que voc� inserir ou alterar devem ter per�odo de refer�ncia contido em um dos per�odos listados abaixo.</ul></b></font></td>');
    ShowHTML('<tr><td>');
    ShowHTML('        <a accesskey="F" class="ss" href="#" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="middle">');
    ShowHTML('          <td><b>'.LinkOrdena('Indicador','nm_indicador').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('T�rmino','fim').'</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_indicador').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'inicio')).'</td>');
        ShowHTML('        <td align="center">'.((f($row,'prazo_definido')=='S') ? formataDataEdicao(f($row,'fim')) : '&rarr;').'</td>');
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
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 
// =========================================================================
// Rotina de cadastramento das aferi��es de indicadores
// -------------------------------------------------------------------------
function Afericao() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_indicador        = $_REQUEST['w_indicador'];
    $w_afericao         = $_REQUEST['w_afericao'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_pais             = $_REQUEST['w_pais'];
    $w_regiao           = $_REQUEST['w_regiao'];
    $w_uf               = $_REQUEST['w_uf'];
    $w_cidade           = $_REQUEST['w_cidade'];
    $w_base             = $_REQUEST['w_base'];
    $w_fonte            = $_REQUEST['w_fonte'];
    $w_valor            = $_REQUEST['w_valor'];
    $w_previsao         = $_REQUEST['w_previsao'];
    $w_observacao       = $_REQUEST['w_observacao'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,null,null,'S',null,null,null,null,null,null,null,null,null,'EDICAO');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc','base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    } else {
      $RS = SortArray($RS,'nome','asc','base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    }
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endere�o informado
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,'EDICAO');
    foreach ($RS as $row) {$RS = $row; break;}
    $w_indicador        = f($RS,'sq_eoindicador');
    $w_afericao         = formataDataEdicao(f($RS,'phpdt_afericao'));
    $w_inicio           = formataDataEdicao(f($RS,'phpdt_inicio'));
    $w_fim              = formataDataEdicao(f($RS,'phpdt_fim'));
    $w_pais             = f($RS,'sq_pais');
    $w_regiao           = f($RS,'sq_regiao');
    $w_uf               = f($RS,'co_uf');
    $w_cidade           = f($RS,'sq_cidade');
    $w_base             = f($RS,'base_geografica');
    $w_fonte            = f($RS,'fonte');
    $w_valor            = formatNumber(f($RS,'valor'),4);
    $w_previsao         = f($RS,'previsao');
    $w_observacao       = f($RS,'observacao');
  } 
  
  if ($O=='I') {
    // Recupera os dados da �ltima aferi��o do indicador, na base indicada, para sugerir como padr�o
    if (nvl($w_indicador,'nulo')!='nulo' && nvl($w_base,'nulo')!='nulo') {
      $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_indicador,null,null,null,null,null,$w_base,null,null,null,null,null,null,null,null,'INCLUSAO');
      if (count($RS)>0) {
        foreach ($RS as $row) {$RS = $row; break;}
        if (nvl($w_pais,'')=='') $w_pais = f($RS,'sq_pais');
        if (nvl($w_regiao,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
        if (nvl($w_uf,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
        if (nvl($w_cidade,'')=='' && $w_base==4) $w_cidade = f($RS,'sq_cidade');
        $w_fonte          = f($RS,'fonte');
        $w_observacao     = f($RS,'observacao');
      }
    }
    if (nvl($w_base,5)!=5) {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      if (nvl($w_pais,'')=='') $w_pais = f($RS,'sq_pais');
      if (nvl($w_uf,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
      if (nvl($w_cidade,'')=='' && $w_base==4) $w_cidade = f($RS,'sq_cidade_padrao');
    }
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    SaltaCampo();
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_indicador','Indicador','SELECT','1','1','18','','1');
      Validate('w_base','Base geogr�fica','SELECT','1','1','18','','1');
      Validate('w_afericao','Data de aferi��o','DATA','1','10','10','','0123456789/');
      Validate('w_valor','Valor aferido','VALOR','1',6,18,'','0123456789,.');
      Validate('w_inicio','In�cio do per�odo de refer�ncia','DATA','1','10','10','','0123456789/');
      Validate('w_fim','T�rmino do per�odo de refer�ncia','DATA','1','10','10','','0123456789/');
      CompData('w_inicio','In�cio do per�odo','<=','w_fim','T�rmino do per�odo');
      if (nvl($w_base,5)!=5) {
        Validate('w_pais','Pa�s','SELECT','1','1','18','','1');
        if ($w_base==2) Validate('w_regiao','Regi�o','SELECT','1','1','18','','1');
        if ($w_base==3 || $w_base==4) Validate('w_uf','Estado','SELECT','1','1','18','1','1');
        if ($w_base==4) Validate('w_cidade','Cidade','SELECT','1','1','18','','1');
      }
      Validate('w_fonte','Fonte da informa��o','1','1','1','60','1','1');
      Validate('w_observacao','Observa��o','1','','1','255','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ((strpos('IA',$O)!==false)) {
    BodyOpen('onLoad=\'document.Form.w_indicador.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('    <li>Se voc� � gestor do sistema ou gestor do m�dulo de '.strtolower(f($RS_Menu,'nm_modulo')).', a listagem exibir� todas as aferi��es de todos os indicadores.');
    ShowHTML('    <li>Caso contr�rio, a listagem estar� restrita aos per�odos em que voc� foi definido como aferidor.');
    ShowHTML('    <li>Clique <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.$w_dir.$w_pagina.'AferidorPerm&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Permiss�es&SG='.$SG.'\',\'AferidorPerm\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe suas permiss�es de aferi��o de indicadores.">aqui</A> para verificar suas permiss�es de aferi��o.');
    ShowHTML('    </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td>'.linkOrdena('Indicador','nome').'</td>');
    ShowHTML('          <td>'.linkOrdena('Base','base_geografica').'</td>');
    ShowHTML('          <td>'.linkOrdena('Refer�ncia','phpdt_fim').'</td>');
    ShowHTML('          <td>'.linkOrdena('Data da aferi��o','phpdt_afericao').'</td>');
    ShowHTML('          <td>'.linkOrdena('Valor aferido','valor').'</td>');
    ShowHTML('          <td width="1%" nowrap>'.linkOrdena('U.M.','valor').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'nm_base_geografica').'</td>');
        $w_array = retornaNomePeriodo(f($row,'referencia_inicio'), f($row,'referencia_fim'));
        ShowHTML('        <td align="center">');
        if ($w_array['TIPO']=='DIA') {
          ShowHTML('        '.date(d.'/'.m.'/'.y,$w_array['VALOR']));
        } elseif ($w_array['TIPO']=='MES') {
          ShowHTML('        '.$w_array['VALOR']);
        } elseif ($w_array['TIPO']=='ANO') {
          ShowHTML('        '.$w_array['VALOR']);
        } else {
          ShowHTML('        '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_inicio')),'---').' a '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_fim')),'---'));
        }
        ShowHTML('        <td align="center">'.nvl(date(d.'/'.m.'/'.y,f($row,'phpdt_afericao')),'---').'</td>');
        ShowHTML('        <td align="right">'.((f($row,'previsao')=='S') ? '* ' : '').nvl(formatNumber(f($row,'valor'),4),'---').'</td>');
        ShowHTML('        <td nowrap>'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td colspan=3><table border=0>');
    ShowHTML('  <tr><td align="right">(U.M.)<td>Unidade de medida');
    ShowHTML('  <tr><td align="right">(*)<td>Proje��o');
    ShowHTML('  </table>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&p_chave='.$p_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('<p>&nbsp;</p></tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (strpos('IA',$O)!==false) {
      ShowHTML('      <tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('        ATEN��O:<ul>');
      ShowHTML('        <li>Se voc� � gestor do sistema ou gestor do m�dulo de '.strtolower(f($RS_Menu,'nm_modulo')).', � permitido o registro da aferi��o de qualquer indicador, em qualquer per�odo de refer�ncia.');
      ShowHTML('        <li>Caso contr�rio, os indicadores dispon�veis ser�o aqueles nos quais voc� tem permiss�o na data de hoje. Al�m disso, o per�odo de refer�ncia deve estar contido em um dos per�odos nos quais voc� foi indicado como aferidor.');
      ShowHTML('        <li>Clique <A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.$w_dir.$w_pagina.'AferidorPerm&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Permiss�es&SG='.$SG.'\',\'AferidorPerm\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe suas permiss�es de aferi��o de indicadores.">aqui</A> para verificar suas permiss�es de aferi��o.');
      ShowHTML('        </ul></b></font></td>');
    }
    if (!(strpos('EV',$O)===false)) $w_Disabled   = ' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='I') ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    selecaoIndicador('<U>I</U>ndicador:','I','Selecione o indicador',$w_indicador,null,$w_usuario,null,'w_indicador','AFERIDOR','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_base\'; document.Form.submit();"');
    selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da aferi�ao',$w_base,null,null,'w_base',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_afericao\'; document.Form.submit();"');
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>� proje��o</b>?',$w_previsao,'w_previsao');
    ShowHTML('          <td title="Informe a data em que foi feita a aferi��o."><b><u>D</u>ata de aferi��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_afericao" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_afericao.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_afericao',$w_dir_volta).'</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td title="Informe o valor aferido."><b><u>V</u>alor aferido:</b><br><input '.$w_Disabled.' accesskey="U" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" onKeyDown="FormataValor(this,18,4,event);"></td>');
    ShowHTML('          <td coslpan=2 title="Informe o per�odo de refer�ncia."><b><u>P</u>er�odo de refer�ncia:</b><br>');
    ShowHTML('            <input '.$w_Disabled.' accesskey="P" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_inicio',$w_dir_volta));
    ShowHTML('            a <input '.$w_Disabled.' type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyUp="SaltaCampo(this.form.name,this,10,event);" onKeyDown="FormataData(this,event);">'.ExibeCalendario('Form','w_fim',$w_dir_volta).'</td>');
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
    ShowHTML('      <tr><td colspan=3 title="Informe a fonte utilizada para obter a aferi��o."><b><u>F</u>onte da informa��o:</b><br><input '.$w_Disabled.' accesskey="F" type="text" name="w_fonte" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_fonte.'"></td>');
    ShowHTML('      <tr><td colspan=3><b>Ob<U>s</U>erva��o:<br><TEXTAREA ACCESSKEY="S" class="sti" name="w_observacao" rows=5 cols=80 title="Se desejar, insira observa��es que julgar relevantes sobre esta aferi��o." '.$w_Disabled.'>'.$w_observacao.'</textarea></td>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=3 align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=3><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
  Rodape();
} 
// =========================================================================
// Rotina de vincula��o de indicadores a solicita��es
// -------------------------------------------------------------------------
function Solic() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_erro       = '';
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_indicador  = $_REQUEST['w_indicador'];
  $w_operacao   = $_REQUEST['w_operacao'];

  $p_tipo       = $_REQUEST['p_tipo'];
  $p_nome       = $_REQUEST['p_nome'];
  
  if ($O=='A') $O = 'L';
  
  if ($O=='L') {
    $RS = db_getSolicIndicador::getInstanceOf($dbms,$w_chave,null,null,null);
    $RS = SortArray($RS,'nm_tipo_indicador','asc','nome','asc');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  if ($O=='I') {
    CheckBranco();
    FormataData();
    FormataHora();
    ValidateOpen('Validacao');
    Validate('p_nome','Nome','','','2','60','1','1');
    ShowHTML('  if (theForm.p_tipo.selectedIndex==0 && theForm.p_nome.value==\'\') {');
    ShowHTML('     alert (\'Voc� deve informar algum crit�rio de busca!\');');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  theForm.w_operacao.value=\'LISTA\';');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    if (Nvl($w_operacao,'')>'') {
      ValidateOpen('Validacao1');
      ShowHTML('  if (theForm.Botao.value==\'Procurar\') {');
      Validate('p_nome','Nome','','1','2','60','1','1');
      ShowHTML('  } else {');
      ShowHTML('  var i; ');
      ShowHTML('  var w_erro=true; ');
      ShowHTML('  if (theForm["w_indicador[]"].value==undefined) {');
      ShowHTML('     for (i=0; i < theForm["w_indicador[]"].length; i++) {');
      ShowHTML('       if (theForm["w_indicador[]"][i].checked) w_erro=false;');
      ShowHTML('     }');
      ShowHTML('  }');
      ShowHTML('  else {');
      ShowHTML('     if (theForm["w_indicador[]"].checked) w_erro=false;');
      ShowHTML('  }');
      ShowHTML('  if (w_erro) {');
      ShowHTML('    alert(\'Voc� deve selecionar pelo menos um indicador!\'); ');
      ShowHTML('    return false;');
      ShowHTML('  }');
      ShowHTML('  }');
      ShowHTML('  theForm.Botao.disabled=true;');
      ValidateClose();
    } 
  } 
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ($O=='I' && Nvl($p_nome,'')=='') {
    BodyOpenClean('onLoad=\'document.Form.p_nome.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'this.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  if ($O=='L') {
    ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Cadastre todos os indicadores relevantes para o projeto.');
    ShowHTML('  </ul></b></font></td>');    
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_indicador='.$w_indicador.'&&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td width="10%" nowrap><b>Tipo</td>');
    ShowHTML('          <td><b>Indicador</td>');
    ShowHTML('          <td width="10%" nowrap><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_tipo_indicador').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        if(f($row,'qtd_meta')>0) {
          ShowHTML('          <A class="hl" HREF="javascript:location.href=this.location.href;" onClick="alert(\'N�o � poss�vel desvincular indicador ligado a meta.\')";>Desvincular</A>&nbsp');
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_chave_aux='.f($row,'sq_solic_indicador').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Desvincula��o do indicador." onClick="return(confirm(\'Confirma desvincula��o?\'));">Desvincular</A>&nbsp');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
  } elseif ($O=='I') {
    ShowHTML('<table align="center" border="0" width="100%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_indicador" value="'.$w_indicador.'">');
    ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('  <li>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.');
    ShowHTML('  <li>Voc� pode fazer diversas procuras ou ainda clicar sobre o bot�o <i>Remover filtro</i> para retornar � listagem dos indicadores j� vinculados.');
    ShowHTML('  </ul></b></font></td>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><table border=0 width="100%">');
    ShowHTML('         <tr><td valign="top" colspan="3" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Crit�rios de Busca</td>');
    ShowHTML('      <tr valign="top">');
    selecaoTipoIndicador('<U>T</U>ipo:','M','Selecione o tipo do indicador',$p_tipo,null,'p_tipo','REGISTROS','S');
    ShowHTML('        <td><b><u>N</u>ome:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$p_nome.'"></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('</FORM>');
    if (Nvl($w_operacao,'')>'') {
      AbreForm('Form1',$w_dir.$w_pagina.'GRAVA','POST','return(Validacao1(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_indicador[]" value="">');
      ShowHTML('<INPUT type="hidden" name="w_operacao" value="">');
      ShowHTML(MontaFiltro('POST'));
      // Recupera os registros
      $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,$p_nome,null,$p_tipo,'S',null,null,null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'nm_tipo_indicador','asc','nome','asc');
      ShowHTML('<tr><td colspan=3><br>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
      ShowHTML('          <td width="1%"><b>&nbsp;</td>');
      ShowHTML('          <td width="10%" nowrap><b>Tipo</td>');
      ShowHTML('          <td><b>Indicador</td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
      } else {
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="middle">');
          ShowHTML('        <td align="center"><input type="checkbox" name="w_indicador[]" value="'.f($row,'chave').'">');
          ShowHTML('        <td nowrap>'.f($row,'nm_tipo_indicador').'</td>');
          ShowHTML('        <td>'.f($row,'nome').'</td>');
          ShowHTML('      </tr>');
        } 
      } 
      ShowHTML('    </table>');
      ShowHTML('  </td>');
      ShowHTML('</tr>');
      ShowHTML('  <tr><td align="center" colspan=3><input class="stb" type="submit" name="Botao" value="Vincular"></td></tr>');
      ShowHTML('</FORM>');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
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
// Rotina de cadastramento de metas
// -------------------------------------------------------------------------
function Meta() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_indicador        = $_REQUEST['w_indicador'];
    $w_pessoa           = $_REQUEST['w_pessoa'];
    $w_unidade          = $_REQUEST['w_unidade'];
    $w_titulo           = $_REQUEST['w_titulo'];
    $w_descricao        = $_REQUEST['w_descricao'];
    $w_ordem            = $_REQUEST['w_ordem'];
    $w_inicio           = $_REQUEST['w_inicio'];
    $w_fim              = $_REQUEST['w_fim'];
    $w_valor_inicial    = $_REQUEST['w_valor_inicial'];
    $w_quantidade       = $_REQUEST['w_quantidade'];
    $w_base             = $_REQUEST['w_base'];
    $w_pais             = $_REQUEST['w_pais'];
    $w_regiao           = $_REQUEST['w_regiao'];
    $w_uf               = $_REQUEST['w_uf'];
    $w_cidade           = $_REQUEST['w_cidade'];
    $w_cumulativa       = $_REQUEST['w_cumulativa'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'ordem','asc','nome','asc','base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    } else {
      $RS = SortArray($RS,'ordem','asc','nome','asc','base_geografica','asc','nm_base_geografica','asc','phpdt_afericao','desc');
    }
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endere�o informado
    $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_chave,$w_chave_aux,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_indicador        = f($RS,'sq_eoindicador');
    $w_pessoa           = f($RS,'sq_pessoa');
    $w_unidade          = f($RS,'sq_unidade');
    $w_titulo           = f($RS,'titulo');
    $w_descricao        = f($RS,'descricao');
    $w_ordem            = f($RS,'ordem');    
    $w_inicio           = formataDataEdicao(f($RS,'inicio'));
    $w_fim              = formataDataEdicao(f($RS,'fim'));
    $w_quantidade       = f($RS,'quantidade');
    $w_pais             = f($RS,'sq_pais');
    $w_regiao           = f($RS,'sq_regiao');
    $w_uf               = f($RS,'co_uf');
    $w_cidade           = f($RS,'sq_cidade');
    $w_base             = f($RS,'base_geografica');
    $w_valor_inicial    = formatNumber(f($RS,'valor_inicial'),4);
    $w_quantidade       = formatNumber(f($RS,'quantidade'),4);
    $w_cumulativa       = f($RS,'cumulativa');
  } 
  
  if ($O=='I') {
    // Recupera os dados da �ltima aferi��o do indicador, na base indicada, para sugerir como padr�o
    if (nvl($w_indicador,'nulo')!='nulo' && nvl($w_base,'nulo')!='nulo') {
      $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_indicador,null,null,null,null,null,$w_base,null,null,null,null,null,null,null,null,'INCLUSAO');
      if (count($RS)>0) {
        foreach ($RS as $row) {$RS = $row; break;}
        if (nvl($w_pais,'')=='') $w_pais = f($RS,'sq_pais');
        if (nvl($w_regiao,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
        if (nvl($w_uf,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
        if (nvl($w_cidade,'')=='' && $w_base==4) $w_cidade = f($RS,'sq_cidade');
      }
    }
    if (nvl($w_base,5)!=5) {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
      if (nvl($w_pais,'')=='') $w_pais = f($RS,'sq_pais');
      if (nvl($w_uf,'')=='' && ($w_base==3 || $w_base==4)) $w_uf = f($RS,'co_uf');
      if (nvl($w_cidade,'')=='' && $w_base==4) $w_cidade = f($RS,'sq_cidade_padrao');
    }
  }

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_titulo','Meta','','1','2','100','1','1');
      Validate('w_descricao','Descricao','','1','2','2000','1','1');
      Validate('w_ordem','Ordem','1','1','1','3','','0123456789');
      Validate('w_inicio','In�cio do per�odo','DATA','1','10','10','','0123456789/');
      Validate('w_fim','T�rmino do per�odo','DATA','1','10','10','','0123456789/');
      CompData('w_inicio','In�cio do per�odo','<=','w_fim','T�rmino do per�odo');
      Validate('w_indicador','Indicador','SELECT','1','1','18','','1');
      Validate('w_base','Base geogr�fica','SELECT','1','1','18','','1');
      if (nvl($w_base,5)!=5) {
        Validate('w_pais','Pa�s','SELECT','1','1','18','','1');
        if ($w_base==2) Validate('w_regiao','Regi�o','SELECT','1','1','18','','1');
        if ($w_base==3 || $w_base==4) Validate('w_uf','Estado','SELECT','1','1','18','1','1');
        if ($w_base==4) Validate('w_cidade','Cidade','SELECT','1','1','18','','1');
      }
      Validate('w_valor_inicial','Valor base','VALOR','1',6,18,'','0123456789,.');
      Validate('w_quantidade','Resultado','VALOR','1',6,18,'','0123456789,.');
      Validate('w_pessoa','Respons�vel pela meta','SELECT','1','1','10','','1');
      Validate('w_unidade','Setor respons�vel pela meta','SELECT','1','1','10','','1');
      if ($P1==1) Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O=='E' && $P1==1) {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
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
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif ((strpos('IA',$O)!==false)) {
    BodyOpen('onLoad=\'document.Form.w_titulo.focus()\';');
  } elseif ($O=='E' && $P1==1) {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('    <li>Registre as metas necess�rias ao acompanhamento.');
    ShowHTML('    <li>Antes de cadastrar as metas, informe os indicadores do projeto.');
    ShowHTML('    <li>Somente indicadores cadastrado no projeto, poder�o ser associados as metas.');
    ShowHTML('    <li>N�o � permitida a sobreposi��o de per�odos em metas que tenham o mesmo indicador e base geogr�fica.');
    ShowHTML('    </ul></b></font></td>');
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td>'.linkOrdena('Indicador','nome').'</td>');
    ShowHTML('          <td>'.linkOrdena('Meta','titulo').'</td>');
    ShowHTML('          <td>'.linkOrdena('Base geogr�fica','base_geografica').'</td>');
    ShowHTML('          <td>'.linkOrdena('In�cio','inicio').'</td>');
    ShowHTML('          <td>'.linkOrdena('Fim','fim').'</td>');
    ShowHTML('          <td>'.linkOrdena('Valor base','valor_inicial').'</td>');
    ShowHTML('          <td>'.linkOrdena('Resultado','quantidade').'</td>');
    ShowHTML('          <td width="1%" nowrap>'.linkOrdena('U.M.','sg_unidade_medida').'</td>');
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_indicador').'</td>');
        ShowHTML('        <td>'.f($row,'titulo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_base_geografica').'</td>');
        ShowHTML('        <td align="center">'.nvl(date(d.'/'.m.'/'.y,f($row,'inicio')),'---').'</td>');
        ShowHTML('        <td align="center">'.nvl(date(d.'/'.m.'/'.y,f($row,'fim')),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'valor_inicial'),4),'---').'</td>');
        ShowHTML('        <td align="right">'.nvl(formatNumber(f($row,'quantidade'),4),'---').'</td>');
        ShowHTML('        <td nowrap>'.f($row,'sg_unidade_medida').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Alterar">AL</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&w_chave_aux='.f($row,'chave_aux').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"title="Excluir">EX</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td colspan=3><table border=0>');
    ShowHTML('  <tr><td align="right">U.M.<td>Unidade de medida do indicador');
    ShowHTML('  </table>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('IA',$O)===false)) {
      ShowHTML('      <tr><td colspan=3 bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b><font color="#BC3131">');
      ShowHTML('        ATEN��O:<ul>');
      ShowHTML('        <li>N�o � permitida a sobreposi��o de per�odos em metas que tenham o mesmo indicador e base geogr�fica.');
      ShowHTML('        </ul></b></font></td>');
    }
    if (!(strpos('EV',$O)===false)) $w_Disabled   = ' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_afericao" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');

    ShowHTML('      <tr><td valign="top"colspan="3"><b><u>M</u>eta:</b><br><INPUT ACCESSKEY="O" '.$w_Disabled.' class="STI" type="text" name="w_titulo" size="90" maxlength="100" value="'.$w_titulo.'" title="Informe o objetivo da meta."></td>');
    ShowHTML('      <tr><td colspan="3"><b><u>D</u>escri��o:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descri��o da meta.">'.$w_descricao.'</TEXTAREA></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('              <td align="left"><b><u>O</u>rdem:<br><INPUT ACCESSKEY="O" TYPE="TEXT" CLASS="STI" NAME="w_ordem" SIZE=3 MAXLENGTH=3 VALUE="'.$w_ordem.'" '.$w_Disabled.' title="Confira abaixo os outros n�meros de ordem desse n�vel."></td>');
    ShowHTML('              <td><b>Previs�o in�<u>c</u>io:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao(Nvl($w_inicio,time())).'" onKeyDown="FormataData(this,event);" title="Data prevista para in�cio da etapa.">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('              <td><b>Previs�o <u>t</u>�rmino:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_fim" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim).'" onKeyDown="FormataData(this,event);" title="Data prevista para t�rmino da etapa.">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('      <tr valign="top">');
    selecaoIndicador('<U>I</U>ndicador:','I','Selecione o indicador',$w_indicador,$w_chave,$w_usuario,null,'w_indicador','META','onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_base\'; document.Form.submit();"');
    selecaoBaseGeografica('<U>B</U>ase geogr�fica:','B','Selecione a base geogr�fica da aferi�ao',$w_base,null,null,'w_base',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_quantidade\'; document.Form.submit();"');
    ShowHTML('      <tr valign="top">');
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
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td title="Informe o valor do indicador no in�cio do per�odo."><b><u>V</u>alor base: (use 4 casas decimais)</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor_inicial" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor_inicial.'" onKeyDown="FormataValor(this,18,4,event);"></td>');
    ShowHTML('        <td title="Informe o valor a ser alcan�ado."><b><u>R</u>esultado: (use 4 casas decimais)</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_quantidade" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_quantidade.'" onKeyDown="FormataValor(this,18,4,event);"></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>� cumulativa</b>?',$w_cumulativa,'w_cumulativa');
    SelecaoPessoa('<u>R</u>espons�vel:','N','Selecione o respons�vel pelo acompanhamento da meta.',$w_pessoa,$w_chave,'w_pessoa','INTERNOS');
    SelecaoUnidade('<U>S</U>etor respons�vel:','S','Selecione o setor respons�vel pelo acompanhamento da meta.',$w_unidade,null,'w_unidade',null,null);
    if ($P1==1) ShowHTML('      <tr><td colspan=3 align="LEFT"><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=3><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="STB" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="STB" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
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
  Rodape();
}
// =========================================================================
// Rotina de visualiza�ao das aferi��es de indicadores
// -------------------------------------------------------------------------
function TelaIndicador() {
  extract($GLOBALS);
  global $p_Disabled;
  $p_sigla          = $_REQUEST['w_sigla'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen(null);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Recupera os dados do indicador para exibi��o no cabe�alho
  $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,$p_sigla,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach ($RS as $row) { $RS = $row; break; }
  ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7">');
  ShowHTML('  <tr valign="top">');
  ShowHTML('    <td valign="middle"><font size="1"><b><font class="SS">'.strtoupper(f($RS,'nome')).'</font></b></td>');
  ShowHTML('    <td nowrap>Sigla:<br><b><font size=1 class="hl">'.f($RS,'sigla').'</font></b></td>');
  ShowHTML('    <td nowrap>Tipo:<br><b><font size=1 class="hl">'.f($RS,'nm_tipo_indicador').'</font></b></td>');
  ShowHTML('    <td nowrap>Unidade de medida:<br><b><font size=1 class="hl">'.f($RS,'sg_unidade_medida').' ('.f($RS,'nm_unidade_medida').')'.'</font></b></td>');
  ShowHTML('  <tr><td colspan=4><b>Defini��o:</b><br>'.nvl(crlf2br(f($RS,'descricao')),'---'));
  ShowHTML('  <tr><td colspan=4><b>Forma de aferi��o:</b><br>'.nvl(crlf2br(f($RS,'forma_afericao')),'---'));
  ShowHTML('  <tr><td colspan=4><b>Fonte de comprova��o:</b><br>'.nvl(crlf2br(f($RS,'fonte_comprovacao')),'---'));
  ShowHTML('  <tr><td colspan=4><b>Ciclo de aferi��o sugerido:</b><br>'.nvl(crlf2br(f($RS,'ciclo_afericao')),'---'));
  ShowHTML('    </ul>');
  ShowHTML('</table>');
  ShowHTML('</table>');
  Rodape();
} 
// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'PEINDIC':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Verifica se j� existe indicador com o nome informado
          $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,$_REQUEST['w_nome'],null,null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'J� existe indicador com este nome!\');');
            ScriptClose();
            RetornaFormulario('w_nome');
            exit();
          } 

          // Verifica se j� existe indicador com o nome informado
          $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],null,null,$_REQUEST['w_sigla'],null,null,null,null,null,null,null,null,null,null,null,'EXISTE');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'J� existe indicador com esta sigla!\');');
            ScriptClose();
            RetornaFormulario('w_sigla');
            exit();
          } 
        }
        dml_putIndicador::getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_nome'],$_REQUEST['w_sigla'],
              $_REQUEST['w_tipo_indicador'],$_REQUEST['w_unidade_medida'],$_REQUEST['w_descricao'],
              $_REQUEST['w_forma_afericao'],$_REQUEST['w_fonte_comprovacao'],$_REQUEST['w_ciclo_afericao'],
              $_REQUEST['w_vincula_meta'],$_REQUEST['w_exibe_mesa'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'EOINDAFR':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          // Verifica se j� existe indicador com o nome informado
          $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],$_REQUEST['w_pessoa'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],'EXISTE');
          if (count($RS)>0) {
            foreach ($RS as $row) {$RS = $row; break; }
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Per�odo j� cadastrado para esta pessoa ('.formataDataEdicao(f($RS,'inicio')).' a '.formataDataEdicao(f($RS,'fim')).')!\');');
            ScriptClose();
            RetornaFormulario('w_nome');
            exit();
          } 
        }
        dml_putIndicador_Aferidor::getInstanceOf($dbms,$O,$w_usuario,Nvl($_REQUEST['w_chave'],''),Nvl($_REQUEST['w_chave_aux'],''),
              $_REQUEST['w_pessoa'],$_REQUEST['w_prazo'],$_REQUEST['w_inicio'],$_REQUEST['w_fim']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'EOINDAFC':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        //exibevariaveis();
        if ($O=='I' || $O=='A') {
          // Verifica se o usu�rio pode registrar aferi��es no per�odo de referencia informado
          $RS = db_getIndicador_Aferidor::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_indicador'],null,$w_usuario,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],'PERMISSAO');
          if (count($RS)<=0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Suas permiss�es n�o abrangem o per�odo de refer�ncia informado. Consulte suas permiss�es!\');');
            ScriptClose();
            RetornaFormulario('w_inicio');
            exit();
          }

          // Verifica se j� existe aferi��o para indicador, base geogr�fica e per�odo de refer�ncia informado
          $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_indicador'],$_REQUEST['w_chave'],
                null,null,null,null,$_REQUEST['w_base'],$_REQUEST['w_pais'],$_REQUEST['w_regiao'],$_REQUEST['w_uf'],
                $_REQUEST['w_cidade'],null,null,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],'EXISTEAF');
          if (count($RS)>0) {
            foreach ($RS as $row) {$RS = $row; break; }
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'J� existe aferi��o para o indicador no per�odo e base geogr�fica informada!\');');
            ScriptClose();
            RetornaFormulario('w_inicio');
            exit();
          } 

          // Verifica se j� existe aferi��o para indicador, base geogr�fica e data de aferi��o informada
          $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_indicador'],$_REQUEST['w_chave'],
                null,null,null,null,$_REQUEST['w_base'],$_REQUEST['w_pais'],$_REQUEST['w_regiao'],$_REQUEST['w_uf'],
                $_REQUEST['w_cidade'],$_REQUEST['w_afericao'],$_REQUEST['w_afericao'],null,null,'EXISTEAF');
          if (count($RS)>0) {
            foreach ($RS as $row) {$RS = $row; break; }
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Data de aferi��o j� registrada para o indicador e base geogr�fica informada!\');');
            ScriptClose();
            RetornaFormulario('w_afericao');
            exit();
          } 
        }
        dml_putIndicador_Afericao::getInstanceOf($dbms,$O,$w_usuario,Nvl($_REQUEST['w_chave'],''),Nvl($_REQUEST['w_indicador'],''),
              $_REQUEST['w_afericao'],$_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_pais'],$_REQUEST['w_regiao'],
              $_REQUEST['w_uf'],$_REQUEST['w_cidade'],$_REQUEST['w_base'],$_REQUEST['w_fonte'],$_REQUEST['w_valor'],
              $_REQUEST['w_previsao'],$_REQUEST['w_observacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    case 'INDSOLIC':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          for ($i=0; $i<=count($_POST['w_indicador'])-1; $i=$i+1) {
            if (Nvl($_POST['w_indicador'][$i],'')>'') {
              dml_putSolicIndicador::getInstanceOf($dbms,$O,null,$_REQUEST['w_chave'],$_POST['w_indicador'][$i]);
            } 
          } 
        } elseif ($O=='E') {
          dml_putSolicIndicador::getInstanceOf($dbms,$O,$_REQUEST['w_chave_aux'],null,null);
        } 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$_REQUEST['w_chave']).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
      } 
      break;
    case 'METASOLIC':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$w_usuario,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],null,null,null,$_REQUEST['w_indicador'],null,null,$_REQUEST['w_base'],$_REQUEST['w_pais'],$_REQUEST['w_regiao'],$_REQUEST['w_uf'], $_REQUEST['w_cidade'],null,null,$_REQUEST['w_inicio'],$_REQUEST['w_fim'],'EXISTEMETA');
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'N�o � permitida a sobreposi��o de per�odos em metas que tenham o mesmo indicador e base geogr�fica!\');');
            ScriptClose();
            RetornaFormulario('w_titulo');
            exit();                                    
          }
        }
        dml_putIndicador_Meta::getInstanceOf($dbms,$O,$w_usuario,$_REQUEST['w_chave'],Nvl($_REQUEST['w_chave_aux'],''),
              $_REQUEST['w_indicador'],$_REQUEST['w_titulo'], $_REQUEST['w_descricao'], $_REQUEST['w_ordem'],
              $_REQUEST['w_inicio'],$_REQUEST['w_fim'],$_REQUEST['w_base'], $_REQUEST['w_pais'],$_REQUEST['w_regiao'],
              $_REQUEST['w_uf'], $_REQUEST['w_cidade'],$_REQUEST['w_valor_inicial'],$_REQUEST['w_quantidade'],
              $_REQUEST['w_cumulativa'], $_REQUEST['w_pessoa'],$_REQUEST['w_unidade']); 
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
      ScriptClose();
      break;
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
    case 'FRAMESAFERICAO':     FramesAfericao();    break;
    case 'VISUALAFERICAO':     VisualAfericao();    break;
    case 'VISUALDADOS':        VisualDados();       break;
    case 'TELAINDICADOR':      TelaIndicador();     break;
    case 'AFERIDOR':           Aferidor();          break;
    case 'AFERIDORPERM':       AferidorPerm();      break;
    case 'AFERICAO':           Afericao();          break;
    case 'SOLIC':              Solic();             break;
    case 'META':               Meta();              break;
    case 'GRAVA':              Grava();             break;
    default:
    Cabecalho();
    ShowHTML('<HEAD><BASE HREF="'.$conRootSIW.'"></HEAD>');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    exibevariaveis();
    break;
  } 
} 
?>