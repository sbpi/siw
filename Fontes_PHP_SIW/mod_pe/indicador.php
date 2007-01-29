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
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putIndicador.php');
include_once($w_dir_volta.'funcoes/selecaoUnidadeMedida.php');

// =========================================================================
//  /indicador.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar a tabela de indicadores
// Mail     : alex@sbpi.com.br
// Criacao  : 29/01/2007, 17:14
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

if ($O=='') $O='L';

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'M': $w_TP=$TP.' - Serviços';        break;
  case 'H': $w_TP=$TP.' - Herança';         break;
  case 'T': $w_TP=$TP.' - Ativar';          break;
  case 'D': $w_TP=$TP.' - Desativar';       break;
  default:  $w_TP=$TP.' - Listagem';        break;
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
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
    // Se for recarga da página
    $w_nome              = $_REQUEST['w_nome'];
    $w_sigla             = $_REQUEST['w_sigla'];
    $w_tipo_indicador    = $_REQUEST['w_tipo_indicador'];
    $w_unidade_medida    = $_REQUEST['w_unidade_medida'];
    $w_descricao         = $_REQUEST['w_descricao'];
    $w_forma_afericao    = $_REQUEST['w_forma_afericao'];
    $w_fonte_comprovacao = $_REQUEST['w_fonte_comprovacao'];
    $w_ciclo_afericao    = $_REQUEST['w_ciclo_afericao'];
    $w_ativo             = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'nome','asc');
  } elseif (!(strpos('AEV',$O)===false)) {
    // Recupera os dados do endereço informado
    $RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_nome              = f($RS,'nome');
    $w_sigla             = f($RS,'sigla');
    $w_tipo_indicador    = f($RS,'sq_tipo_indicador');
    $w_unidade_medida    = f($RS,'sq_unidade_medida');
    $w_descricao         = f($RS,'descricao');
    $w_forma_afericao    = f($RS,'forma_afericao');
    $w_fonte_comprovacao = f($RS,'fonte_comprovacao');
    $w_ciclo_afericao    = f($RS,'gestao_ciclo_afericao');
    $w_ativo             = f($RS,'ativo');
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    FormataCNPJ();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') {
        Validate('w_chave','Unidade','1','1','1','50','1','1');
      } 
      Validate('w_nome','Nome','1','1','1','60','1','1');
      Validate('w_sigla','Sigla','1','1','1','15','1','1');
      Validate('w_tipo_indicador','Tipo do indicador','SELECT','1','1','18','','1');
      Validate('w_unidade_medida','Unidade de medida','SELECT','1','1','18','','1');
      Validate('w_descricao','Descrição','1','1','1','2000','1','1');
      Validate('w_forma_afericao','Forma de aferição','1','1','1','2000','1','1');
      Validate('w_fonte_commprovacao','Fonte de comprovação','1','1','1','2000','1','1');
      Validate('w_ciclo_afericao','Ciclo de afericao','1','1','1','2000','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
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
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Sigla</td>');
    ShowHTML('          <td><b>Nome</td>');
    ShowHTML('          <td><b>Última aferição</td>');
    ShowHTML('          <td><b>Ativo</td>');
    ShowHTML('          <td><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'phpdt_afericao')).'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
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
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('          <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_sigla.'"></td>');
    selecaoUnidadeMedida('Unidade de <U>m</U>edida:','M','Selecione a unidade de medida do indicador',$w_unidade_medida,null,'w_unidade_medida','REGISTROS','S');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td><b><U>D</U>efinição:<br><TEXTAREA ACCESSKEY="D" class="sti" name="w_descricao" rows=5 cols=80 title="Descreva o que o indicador pretende medir." '.$w_Disabled.'>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><b><U>F</U>orma de aferição:<br><TEXTAREA ACCESSKEY="F" class="sti" name="w_forma_afericao" rows=5 cols=80 title="Descreva como o indicador deve ser aferido." '.$w_Disabled.'>'.$w_forma_afericao.'</textarea></td>');
    ShowHTML('      <tr><td><b>F<U>o</U>nte de comprovação:<br><TEXTAREA ACCESSKEY="O" class="sti" name="w_fonte_comprovacao" rows=5 cols=80 title="Indique a(s) fonte(s) de comprovação dos valores aferidos para o indicador." '.$w_Disabled.'>'.$w_fonte_comprovacao.'</textarea></td>');
    ShowHTML('      <tr><td><b><U>C</U>iclo de aferição sugerido:<br><TEXTAREA ACCESSKEY="C" class="sti" name="w_ciclo_afericao" rows=5 cols=80 title="Informe o ciclo de aferição sugerido para o indicador." '.$w_Disabled.'>'.$w_ciclo_afericao.'</textarea></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert(\'Opção não disponível\');');
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
  global $w_Disabled;
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'PEUNIDADE':
      // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I') {
          $RS = db_getUnidade_PE::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],null,null,null);
          if (count($RS)==0) {
            dml_putUnidade_PE::getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_descricao'],$_REQUEST['w_forma_afericao'],$_REQUEST['w_fonte_comprovacao'],$_REQUEST['w_ciclo_afericao'],$_REQUEST['w_ativo']);
            ScriptOpen('JavaScript');
            ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
            ScriptClose();
          } else {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Unidade já cadastrada!\');');
            ScriptClose();
            RetornaFormulario('w_assinatura');
          } 
        } else {
          dml_putUnidade_PE::getInstanceOf($dbms,$O,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_nome'],$_REQUEST['w_sigla'],
                $_REQUEST['w_tipo_indicador'],$_REQUEST['w_unidade_medida'],$_REQUEST['w_descricao'],
                $_REQUEST['w_forma_afericao'],$_REQUEST['w_fonte_comprovacao'],$_REQUEST['w_ciclo_afericao'],
                $_REQUEST['w_ativo']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
          ScriptClose();
        }
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
    case 'GRAVA':              Grava();             break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exibevariaveis();
  break;
  } 
} 
?>