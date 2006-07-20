<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCiaTrans.php');
include_once($w_dir_volta.'classes/sp/db_getPDParametro.php');
include_once($w_dir_volta.'classes/sp/db_getUorgList.php');
include_once($w_dir_volta.'classes/sp/db_getPersonList.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCiaTrans.php');
include_once($w_dir_volta.'classes/sp/dml_putPDParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidade.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUnidLimite.php');
include_once($w_dir_volta.'classes/sp/dml_putPDUsuario.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoAno.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerencia as rotinas de tabelas básicas do módulo de passagens e diárias
// Mail     : celso@sbpi.com.br
// Criacao  : 04/10/2005 11:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 
// Parâmetros recebidos:
//    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
//    O (operação)   = I   : Inclusão
//                   = A   : Alteração
//                   = E   : Exclusão
//                   = L   : Listagem
//                   = P   : Filtragem

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
$w_dir          = 'mod_pd/';
$w_troca        = $_REQUEST['w_troca'];
$w_copia        = $_REQUEST['w_copia'];

if ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';  
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';  break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão';  break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'C': $w_TP=$TP.' - Cópia';     break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'H': $w_TP=$TP.' - Herança';   break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

$p_ordena       = strtolower($_REQUEST['p_ordena']);

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Manter Tabela básica 'PD_CIA_TRANSPORTE'
// -------------------------------------------------------------------------
function CiaTrans() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave=$_REQUEST['w_chave'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_aereo        = $_REQUEST['w_aereo'];
    $w_rodoviario   = $_REQUEST['w_rodoviario'];
    $w_aquaviario   = $_REQUEST['w_aquaviario'];
    $w_padrao       = $_REQUEST['w_padrao'];
    $w_ativo        = $_REQUEST['w_ativo'];
  }elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'padrao','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_Troca=='') {
    // Recupera os dados chave informada
    $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_aereo        = f($RS,'aereo');
    $w_rodoviario   = f($RS,'rodoviario');
    $w_aquaviario   = f($RS,'aquaviario');
    $w_padrao       = f($RS,'padrao');
    $w_ativo        = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','2','30','1','1');
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
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Aéreo','nm_aereo').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Rodoviário','nm_rodoviario').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Aquaviário','nm_aquaviario').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Padrão','nm_padrao').'</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_aereo').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_rodoviario').'</td>');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_aquaviario').'</td>');
        if (Nvl(f($row,'ativo'),'')=='S') {
          ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        } else {
          ShowHTML('        <td align="center"><font color="red" size="1">'.f($row,'nm_ativo').'</td>');
        } 
        ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_padrao').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
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
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');

    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('           <td colspan=3><font size="1"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Aéreo?</b>',$w_aereo,'w_aereo');
    MontaRadioNS('<b>Rodoviário?</b>',$w_rodoviario,'w_rodoviario');
    MontaRadioNS('<b>Aquaviário?</b>',$w_aquaviario,'w_aquaviario');
    ShowHTML('        <tr valign="top">');
    MontaRadioNS('<b>Padrão?</b>',$w_padrao,'w_padrao');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
} 

// =========================================================================
// Rotina dos parâmetros
// -------------------------------------------------------------------------
function Parametros() {
  extract($GLOBALS);
  global $w_Disabled;

  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'') {
    // Se for recarga da página
    $w_sequencial        = $_REQUEST['w_sequencial'];
    $w_sequencial_atual  = $_REQUEST['w_sequencial_atual'];
    $w_ano_corrente      = $_REQUEST['w_ano_corrente'];
    $w_prefixo           = $_REQUEST['w_prefixo'];
    $w_sufixo            = $_REQUEST['w_sufixo'];
    $w_dias_antecedencia = $_REQUEST['w_dias_antecedencia'];
    $w_dias_prest_contas = $_REQUEST['w_dias_prest_contas'];
    $w_limite_unidade    = $_REQUEST['w_limite_unidade'];
  } else {
    // Recupera os dados do parâmetro
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_sequencial         = f($RS,'sequencial');
      $w_sequencial_atual   = f($RS,'sequencial');
      $w_ano_corrente       = f($RS,'ano_corrente');
      $w_prefixo            = f($RS,'prefixo');
      $w_sufixo             = f($RS,'sufixo');
      $w_dias_antecedencia  = f($RS,'dias_antecedencia');
      $w_dias_prest_contas  = f($RS,'dias_prestacao_contas');
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  ShowHTML('  if (theForm.w_sequencial_atual.value > \'\'){ ');
  ShowHTML('    if (theForm.w_sequencial.value <  theForm.w_sequencial_atual.value){ ');
  ShowHTML('      alert(\'O número sequencial atual nao pode ser menor que ' + theForm.w_sequencial_atual.value + '!\');');
  ShowHTML('      return false;');
  ShowHTML('    };');
  ShowHTML('  };');
  Validate('w_sequencial','Sequencial','1',1,1,18,'','0123456789');
  //Validate 'w_ano_corrente', 'Ano corrente', '1', 1, 4, 4, '', '0123456789'
  Validate('w_prefixo','Prefixo','1','',1,10,'1','1');
  Validate('w_sufixo','Sufixo','1','',1,10,'1','1');
  Validate('w_dias_antecedencia','Dias de antecedência','1',1,1,3,'','0123456789');
  Validate('w_dias_prest_contas','Dias para prestação de contas','1',1,1,3,'','0123456789');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpenClean('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpenClean('onLoad=\'document.Form.w_sequencial.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');

  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_sequencial_atual" value="'.$w_sequencial_atual.'">');
  ShowHTML('<INPUT type="hidden" name="w_ano_corrente" value="'.strftime('%Y',(time())).'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0"><tr><td>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><font size="1"><b>Parâmetros</td></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  //ShowHTML '      <tr><td><font size=1>Falta definir a explicação.</font></td></tr>'
  //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
  ShowHTML('      </table>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td><font size="1"><b><u>S</u>equencial:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sequencial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sequencial.'"></td>');
  //ShowHTML '          <td><font size=''1''><b><u>A</u>no corrente:</b><br><input ' & w_Disabled & ' accesskey=''A'' type=''text'' name=''w_ano_corrente'' class=''sti'' SIZE=''10'' MAXLENGTH=''10'' VALUE=''' & w_ano_corrente & '''></td>'
  ShowHTML('      <tr><td><font size="1"><b><u>P</u>refixo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prefixo.'"></td>');
  ShowHTML('          <td><font size="1"><b><u>S</u>ufixo:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sufixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sufixo.'"></td>');
  ShowHTML('      <tr><td><font size="1"><b><u>D</u>ias de antecedência:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_dias_antecedencia" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dias_antecedencia.'"></td>');
  ShowHTML('          <td><font size="1"><b>D<u>i</u>as para prestação de contas:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_dias_prest_contas" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_dias_prest_contas.'"></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr>');
  MontaRadioNS('<b>Controla limite orçamentário de passagens e diárias por unidade e ano?</b>',$w_limite_unidade,'w_limite_unidade');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
  // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
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
// Rotina de unidade
// -------------------------------------------------------------------------
function Unidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];

  if ($w_troca>'') {
    // Se for recarga da página
    $w_nome             = $_REQUEST['w_nome'];
    $w_sigla            = $_REQUEST['w_sigla'];
    $w_ativo            = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    // Recupera os parâmetros do módulo
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 

    // Recupera todos os registros para a listagem
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,null,'VIAGEM',null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_Troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'VIAGEM',null,null,$w_ano);
    foreach($RS as $row) { $RS = $row; break; }
    $w_nome             = f($RS,'nome');
    $w_sigla            = f($RS,'sigla');
    $w_ativo            = f($RS,'ativo');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') {
        Validate('w_chave','Unidade','HIDDEN','1','1','50','1','1');
      } 
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
  } elseif (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'document.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen(null);
  } 

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Unidade','nome').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ativo','ativo').'</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.f($row,'nome').' ('.f($row,'sigla').')</td>');
        if (f($row,'ativo')=='S') {
          ShowHTML('        <td align="center"><font size="1">'.f($row,'nm_ativo').'</td>');
        } else {
          ShowHTML('        <td align="center"><font size="1" color="red">'.f($row,'nm_ativo').'</td>');
        } 
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        if ($w_limite_unidade=='S') {
          ShowHTML('          <a class="HL" href="javascript:location.href=this.location.href" onclick="window.open(\''.$w_pagina.'LIMUNIDADE&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_unidade').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PDUNIDLIM\',\'Limites\',\'toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes\');">Limites</a>');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O!='I') {
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    if ($O=='I') {
      SelecaoUnidade('<U>U</U>nidade:','S',null,$w_chave,null,'w_chave',null,null);
    } else {
      ShowHTML('        <tr><td><font size=1><b>Unidade:<br>'.$w_nome.' ('.$w_sigla.')</b>');
    } 
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('         </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
// Rotina de unidade
// -------------------------------------------------------------------------
function LimiteUnidade() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];
  $w_ano    = $_REQUEST['w_ano'];

  // Recupera os dados da unidade
  $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null);
  foreach($RS as $row) { $RS = $row; break; }
  $w_nome  = f($RS,'nome');
  $w_sigla = f($RS,'sigla');
  $w_ativo = f($RS,'ativo');

  if ($w_troca>'') {
    // Se for recarga da página
    $w_limite_passagem  = $_REQUEST['w_limite_passagem'];
    $w_limite_diaria    = $_REQUEST['w_limite_diaria'];
    $w_ano              = $_REQUEST['w_ano'];
  } elseif ($O=='L') {
    // Recupera os parâmetros do módulo
    $RS = db_getPDParametro::getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_limite_unidade     = f($RS,'limite_unidade');
    } 

    // Recupera todos os registros para a listagem
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'PDUNIDLIM',null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome','asc');
    } else {
      $RS = SortArray($RS,'ano','desc','nome','asc');
    }
  } elseif (!(strpos('AE',$O)===false) && $w_Troca=='') {
    // Recupera os dados do endereço informado
    $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$w_chave,'PDUNIDLIM',null,null,$w_ano);
    foreach($RS as $row) { $RS = $row; break; }
    $w_limite_passagem  = number_format(f($RS,'limite_passagem'),2,',','.');
    $w_limite_diaria    = number_format(f($RS,'limite_diaria'),2,',','.');
    $w_ano              = f($RS,'ano');
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Limites de passagens e diárias por unidade</TITLE>');
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      if ($O=='I') {
        Validate('w_ano','Ano','SELECT','1','4','4','','0123456789');
      } 
      Validate('w_limite_passagem','Limite financeiro para passagens','VALOR','1',4,18,'','0123456789.,');
      Validate('w_limite_diaria','Limite financeiro para diárias','VALOR','1',4,18,'','0123456789.,');
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
  } elseif ($O=='I') {
    BodyOpen('onLoad=\'document.Form.w_ano.focus()\';');
  } elseif (!(strpos('A',$O)===false)) {
    BodyOpen('onLoad=\'document.focus()\';');
  } elseif ($O=='E') {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.focus()\';');
  } 

  ShowHTML('<div align=center><center>');
  ShowHTML('<table border=1 width="100%" bgcolor="#FAEBD7"><tr><td>');
  ShowHTML('  <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
  ShowHTML('    <tr valign="top"><td>Limites orçamentários da unidade:<b> '.$w_nome.' ('.$w_sigla.')</td>');
  ShowHTML('  </TABLE>');
  ShowHTML('</table>');
  ShowHTML('<HR>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <a accesskey="F" class="ss" href="#" onClick="opener.focus(); window.close();"><u>F</u>echar</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ano','ano').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Limite passagens','limite_passagem').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Limite diárias','limite_diaria').'</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center"><font size="1">'.f($row,'ano').'</td>');
        ShowHTML('        <td align="right"><font size="1">'.number_format(f($row,'limite_passagem'),2,',','.').'</td>');
        ShowHTML('        <td align="right"><font size="1">'.number_format(f($row,'limite_diaria'),2,',','.').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_unidade').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Alterar</A>&nbsp');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_unidade').'&w_ano='.f($row,'ano').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><table border="0" width="100%" cellspacing=0 cellpadding=0><tr valign="top">');
    ShowHTML('      <tr valign="top">');
    if ($O=='I') {
      SelecaoAno('<U>A</U>no:','A',null,$w_ano,null,'w_ano',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_ano" value="'.$w_ano.'">');
      ShowHTML('          <td valign="top"><font size="1"><b>Ano:<br>'.$w_ano.'</b></td>');
    } 
    ShowHTML('          <td valign="top"><font size="1"><b><u>L</u>imite para passagens:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_limite_passagem" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_passagem.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o limite financeiro para passagens para a unidade selecionada."></td>');
    ShowHTML('          <td valign="top"><font size="1"><b>L<u>i</u>mite para diárias:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_limite_diaria" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_limite_diaria.'" onKeyDown="FormataValor(this,18,2,event);" title="Informe o limite financeiro para diárias para a unidade selecionada."></td>');
    ShowHTML('         </table>');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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
// Rotina de usuário
// -------------------------------------------------------------------------
function Usuario() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave  = $_REQUEST['w_chave'];

  if ($O=='L') {
    // Recupera todos os registros para a listagem
    $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,$w_chave,$SG,null,null,null,null);
    if (nvl($p_ordena,'')>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'nome_resumido','asc');
    } else {
      $RS = SortArray($RS,'nome_resumido','asc');
    }
  } 

  Cabecalho();
  ShowHTML('<HEAD>');
  if ($O=='I') {
    ScriptOpen('JavaScript');
    CheckBranco();
    ValidateOpen('Validacao');
    Validate('w_chave','Pessoa','HIDDEN','1','1','50','1','1');
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 

  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.focus()\';');
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML('<tr><td><font size="1"><a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><font size="1"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Nome','nome_resumido').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Lotação','sg_unidade').'</font></td>');
    ShowHTML('          <td><font size="1"><b>'.LinkOrdena('Ramal','ramal').'</font></td>');
    ShowHTML('          <td><font size="1"><b>Operações</font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'chave'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td><font size="1">'.ExibeUnidade($w_dir_volta,$w_cliente,f($row,'nm_local'),f($row,'sq_unidade'),$TP).'</td>');
        ShowHTML('        <td align="center"><font size="1">'.Nvl(f($row,'ramal'),'---').'</td>');
        ShowHTML('        <td align="top" nowrap><font size="1">');
        ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.'GRAVA&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" onClick="return confirm(\'Confirma a exclusão do registro?\');">Excluir</A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoPessoa('<u>P</u>essoa:','p','Selecione a pessoa.',$w_chave,null,'w_chave','USUARIOS');
    ShowHTML('      <tr><td align="LEFT"><font size="1"><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Incluir">');
    ShowHTML('            <input class="STB" type="button" onClick="history.back(1);" name="Botao" value="Cancelar">');
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

  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpenClean('onLoad=document.focus();');

  if (!(strpos($SG,'PDCIA')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if (!(strpos('IA',$O)===false)) {
        if ($_REQUEST['w_padrao']=='S') {
          $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,'S',null,$_REQUEST['w_chave'],null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Somente pode existir uma companhia padrão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
            exit;
          } 
        } 
        $RS = db_getCiaTrans::getInstanceOf($dbms,$w_cliente,null,$_REQUEST['w_nome'],null,null,null,null,null,$_REQUEST['w_chave'],null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Companhia já cadastrada!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
          exit;
        } 
      } 
      dml_putCiaTrans::getInstanceOf($dbms,$O,$w_cliente,
          $_REQUEST['w_chave'],$_REQUEST['w_nome'],$_REQUEST['w_aereo'],$_REQUEST['w_rodoviario'],
          $_REQUEST['w_aquaviario'],$_REQUEST['w_padrao'],$_REQUEST['w_ativo']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'PDPARAM')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      dml_putPDParametro::getInstanceOf($dbms,$w_cliente,
          $_REQUEST['w_sequencial'],$_REQUEST['w_ano_corrente'],$_REQUEST['w_prefixo'],
          $_REQUEST['w_sufixo'],$_REQUEST['w_dias_antecedencia'],$_REQUEST['w_dias_prest_contas'],
          $_REQUEST['w_limite_unidade']);

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'PDUNIDADE')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],'VIAGEM',null,null,null);
        if (count($RS)==0) {
          dml_putPDUnidade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_ativo']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Unidade já cadastrada!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } 
      } else {
        dml_putPDUnidade::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'PDUNIDLIM')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getUorgList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],'PDUNIDLIM',null,null,$_REQUEST['w_ano']);
        if (count($RS)==0) {
          dml_putPDUnidLimite::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_limite_passagem'],$_REQUEST['w_limite_diaria'],$_REQUEST['w_ano']);
          ScriptOpen('JavaScript');
          ShowHTML('  location.href=\''.$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
          ScriptClose();
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Limite da unidade já cadastrado para o ano de '.$_REQUEST['w_ano'].'!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
        } 
      } else {
        dml_putPDUnidLimite::getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$_REQUEST['w_limite_passagem'],$_REQUEST['w_limite_diaria'],$_REQUEST['w_ano']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } 
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } elseif (!(strpos($SG,'PDUSUARIO')===false)) {
    // Verifica se a Assinatura Eletrônica é válida
    if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
      if ($O=='I') {
        $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_chave'],$SG,null,null,null,null);
        if (count($RS)>0) {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Usuário já cadastrado!\');');
          ShowHTML('  history.back(1);');
          ScriptClose();
          exit;
        } 
      } 
      dml_putPDUsuario::getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave']);
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.$R.'&w_chave=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
  case 'CIATRANS':      CiaTrans();     break;
  case 'PARAMETROS':    Parametros();   break;
  case 'UNIDADE':       Unidade();      break;
  case 'LIMUNIDADE':    LimiteUnidade();      break;
  case 'USUARIO':       Usuario();      break;
  case 'GRAVA':         Grava();        break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=document.focus();');
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
