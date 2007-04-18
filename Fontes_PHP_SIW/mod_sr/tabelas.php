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
include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
include_once($w_dir_volta.'classes/sp/db_getGrupoVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getTipoVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getAbastecimento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putOpiniao.php');
include_once($w_dir_volta.'classes/sp/dml_putGrupoVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putAbastecimento.php');
include_once($w_dir_volta.'funcoes/selecaoGrupoVeiculo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVeiculo.php');
include_once($w_dir_volta.'funcoes/selecaoVeiculo.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas básicas do módulo de recursos logísticos
// Mail     : billy@sbpi.com.br
// Criacao  : 28/11/2006 09:00
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
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par          = strtoupper($_REQUEST['par']);
$P1           = Nvl($_REQUEST['P1'],0);
$P2           = Nvl($_REQUEST['P2'],0);
$P3           = Nvl($_REQUEST['P3'],1);
$P4           = nvl($_REQUEST['P4'],$conPageSize);
$TP           = $_REQUEST['TP'];
$SG           = strtoupper($_REQUEST['SG']);
$R            = $_REQUEST['R'];
$O            = strtoupper($_REQUEST['O']);
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];
$w_assinatura = strtoupper($_REQUEST['w_assinatura']);
$w_pagina     = 'tabelas.php?par=';
$w_dir        = 'mod_sr/';
$w_dir_volta  = '../';
$w_Disabled   = 'ENABLED';

switch ($O) {
  case 'I':     $w_TP=$TP.' - Inclusão';        break;
  case 'A':     $w_TP=$TP.' - Alteração';       break;
  case 'E':     $w_TP=$TP.' - Exclusão';        break;
  default:      $w_TP=$TP.' - Listagem';        break;
}
 
// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);


Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de Abastecimento
// -------------------------------------------------------------------------
function Abastecimento() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opinião</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave_aux          = $_REQUEST['w_chave_aux'];    
    $w_data               = FormataDataEdicao($_REQUEST['w_data']);
    $w_hodometro          = $_REQUEST['w_hodometro'];       
    $w_litros             = $_REQUEST['w_litros'];
    $w_valor              = $_REQUEST['w_valor']; 
    $w_local              = $_REQUEST['w_local'];    
  } elseif ($O=='L') {
    $RS = db_getAbastecimento::getInstanceOf($dbms, null, $w_chave_aux, $w_cliente);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'data','desc');
    } else {
      $RS = SortArray($RS,'data','desc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getAbastecimento::getInstanceOf($dbms, $w_chave, null, $w_cliente);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave              = f($RS,'chave');
    $w_chave_aux          = f($RS,'sq_veiculo');    
    $w_data               = FormataDataEdicao(f($RS,'data'));
    $w_hodometro          = f($RS,'hodometro');       
    $w_litros             = f($RS,'litros');
    $w_valor              = f($RS,'valor'); 
    $w_local              = f($RS,'local');    
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    CheckBranco();    
    FormataData();
    FormataValor();  
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Veículo','SELECT',1,1,18,'','0123456789');               
      Validate('w_data','Data','DATA',1,10,10,'','0123456789/');      
      Validate('w_hodometro','Hodômentro','1',1,2,7,'','0123456789/');       
      Validate('w_litros','Litros','VALOR','1',4,18,'','0123456789.,');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      Validate('w_local','Local','1','1','5','60','1','1');
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I') {
    BodyOpen('onLoad=document.Form.w_chave_aux.focus();');
  } elseif ($O=='A') {
    BodyOpen('onLoad=document.Form.w_data.focus();');    
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Marca','marca').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modelo','modelo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ano','ano_fabricacao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Placa','placa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Data','data').'</td>');    
    ShowHTML('          <td><b>'.LinkOrdena('Litros','litros').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Local','local').'</td>');    
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'marca').'</td>');
        ShowHTML('        <td>'.f($row,'modelo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ano_fabricacao').'/'.f($row,'ano_modelo').'</td>');
        ShowHTML('        <td>'.substr(f($row,'nm_veiculo'),0,8).'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'data')).'</td>');              
        ShowHTML('        <td align="right">'.number_format(f($row,'litros'),2,',','.').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'valor'),2,',','.').'</td>');
        ShowHTML('        <td>'.f($row,'local').'</td>');        
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');   
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    SelecaoVeiculo('<u>V</u>eículo:','V','Selecione o veículo',$w_cliente,$w_chave_aux,null,'w_chave_aux',null);
    ShowHTML('      <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'"onKeyDown="FormataData(this,event);" title="Data do abastecimento.">'.ExibeCalendario('Form','w_data').'</td>');
    ShowHTML('      <td><b><u>H</u>odômetro:</b><br><input '.$w_Disabled.' accesskey="H" type="text" name="w_hodometro" class="STI" SIZE="7" MAXLENGTH="7" VALUE="'.$w_hodometro.'"></td>');
    ShowHTML('      <tr><td ><b><u>L</u>itros:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_litros" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.number_format($w_litros,2,',','.').'"onKeyDown="FormataValor(this,18,2,event);" title="Informe a quantidade de litro do abastecimento do veículo."></td>');
    ShowHTML('      <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.number_format($w_valor,2,',','.').'"onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do abastecimento do veículo."></td>');
    ShowHTML('      <td><b>Lo<u>c</u>al:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_local" class="STI" SIZE="40" MAXLENGTH="60" VALUE="'.$w_local.'"></td>');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
}
// =========================================================================
// Rotina de Grupo de Veículo
// -------------------------------------------------------------------------
function Grupo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opinião</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_nome         = $_REQUEST['w_nome'];
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_descricao    = $_REQUEST['w_descricao']; 
    $w_ativo        = $_REQUEST['w_ativo'];    
  } elseif ($O=='L') {
    $RS = db_getGrupoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getGrupoVeiculo::getInstanceOf($dbms, $w_chave, $w_cliente, $w_nome, $w_sigla, null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_cliente      = f($RS,'cliente');
    $w_nome         = f($RS,'nome');
    $w_sigla        = f($RS,'sigla');
    $w_descricao    = f($RS,'descricao'); 
    $w_ativo        = f($RS,'ativo');       
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','60','1','1');
      Validate('w_descricao','descrição','1','1','3','255','1','1');       
      Validate('w_sigla','Sigla','1','1','1','10','1',''); 
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_nome.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.f($row,'cliente').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_nome_ant"  value="'.$w_nome.'">'); 
    ShowHTML('<INPUT type="hidden" name="w_sigla_ant" value="'.$w_sigla.'">');       
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="68" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_descricao" rows="5" cols=75>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Rotina de Opiniões
// -------------------------------------------------------------------------
function Opiniao() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opinião</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página  
    $w_cliente      = $_REQUEST['w_cliente'];
    $w_chave        = $_REQUEST['w_chave'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_sigla        = $_REQUEST['w_sigla']; 
    $w_ordem        = $_REQUEST['w_ordem'];  
  } elseif ($O=='L') {
    $RS = db_getOpiniao::getInstanceOf($dbms, null, $w_cliente, null, null, null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'ordem','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getOpiniao::getInstanceOf($dbms,  $w_chave, $w_cliente, $w_nome, $_sigla, null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_cliente      = f($RS,'cliente');
    $w_chave        = f($RS,'chave');
    $w_nome         = f($RS,'nome');
    $w_sigla        = f($RS,'sigla'); 
    $w_ordem        = f($RS,'ordem');        
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_sigla','Sigla','1','1','1','3','1','');   
      Validate('w_ordem','Ordem','1','1','1','2','','1');  
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_nome.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:<ul>');
    ShowHTML('    <li>Insira cada uma das opiniões que estarão disponíveis para os usuários, não sendo permitida a repetição de nomes e siglas.');
    ShowHTML('    <li>A opinião que tiver a sigla "IN" indicará ao sistema que trata-se de uma insatisfação quanto ao atendimento. Neste caso, exigirá do usuário o motivo da insatisfação e enviará um e-mail comunicando essa opinião.');
//    ShowHTML('    <li>Se a sigla for IN o usuário deve colocar o motivo da insatisfação.');
    ShowHTML('    </ul></b></font></td>');    
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ordem','ordem').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.f($row,'cliente').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_nome_ant"  value="'.$w_nome.'">'); 
    ShowHTML('<INPUT type="hidden" name="w_sigla_ant" value="'.$w_sigla.'">');       
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('          <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="STI" SIZE="18" MAXLENGTH="3" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('          <td><b><u>O</u>rdem:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_ordem" class="STI" SIZE="2" MAXLENGTH="2" VALUE="'.$w_ordem.'"></td>');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Rotina de Tipo de Veículo
// -------------------------------------------------------------------------
function TipoVeiculo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opinião</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_chave_aux          = $_REQUEST['w_chave_aux'];    
    $w_nome               = $_REQUEST['w_nome'];
    $w_sigla              = $_REQUEST['w_sigla'];
    $w_descricao          = $_REQUEST['w_descricao']; 
    $w_ativo              = $_REQUEST['w_ativo'];  
  } elseif ($O=='L') {
    $RS = db_getTipoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, null, null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getTipoVeiculo::getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_cliente            = f($RS,'cliente');
    $w_chave_aux          = f($RS,'sq_grupo_veiculo');    
    $w_nome               = f($RS,'nome');
    $w_sigla              = f($RS,'sigla');
    $w_descricao          = f($RS,'descricao'); 
    $w_ativo              = f($RS,'ativo');       
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Grupo','SELECT',1,1,18,'','0123456789');        
      Validate('w_nome','Nome','1','1','3','60','1','1');
      Validate('w_descricao','descrição','1','1','3','255','1','1');
      Validate('w_sigla','Sigla','1','1','1','10','1','');  
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_chave_aux.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Grupo','nm_grupo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nome').'</td>');
        ShowHTML('        <td>'.f($row,'nm_grupo').'</td>');        
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_sigla_ant" value="'.$w_sigla.'">');       
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    SelecaoGrupoVeiculo('<u>G</u>rupo:','G','Selecione o grupo desejado',$w_cliente,$w_chave_aux,null,'w_chave_aux',null);
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="STI" SIZE="60" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_descricao" rows="5" cols=75>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';" name="Botao" value="Cancelar">');
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
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 
// =========================================================================
// Rotina de Veículo
// -------------------------------------------------------------------------
function Veiculo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opinião</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    $w_chave_aux          = $_REQUEST['w_chave_aux'];
    $w_placa              = $_REQUEST['w_placa'];
    $w_marca              = $_REQUEST['w_marca'];
    $w_modelo             = $_REQUEST['w_modelo'];
    $w_combustivel        = $_REQUEST['w_combustivel'];
    $w_tipo               = $_REQUEST['w_tipo'];    
    $w_potencia           = $_REQUEST['w_potencia'];
    $w_cilindrada         = $_REQUEST['w_cilindrada'];
    $w_ano_modelo         = $_REQUEST['w_ano_modelo']; 
    $w_ano_fabricacao     = $_REQUEST['w_ano_fabricacao'];                        
    $w_renavam            = $_REQUEST['w_renavam']; 
    $w_chassi             = $_REQUEST['w_chassi'];                 
    $w_alugado            = $_REQUEST['w_alugado'];
    $w_ativo              = $_REQUEST['w_ativo'];    
  } elseif ($O=='L') {
    $RS = db_getVeiculo::getInstanceOf($dbms, null, null, $w_cliente, null, null, null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'modelo','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $RS = db_getVeiculo::getInstanceOf($dbms, $w_chave, null, $w_cliente, null, null, null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_cliente            = f($RS,'cliente');
    $w_chave_aux          = f($RS,'sq_tipo_veiculo');    
    $w_placa              = f($RS,'placa');
    $w_marca              = f($RS,'marca');
    $w_modelo             = f($RS,'modelo');
    $w_combustivel        = f($RS,'combustivel');
    $w_tipo               = f($RS,'tipo');    
    $w_potencia           = f($RS,'potencia');
    $w_cilindrada         = f($RS,'cilindrada');
    $w_ano_modelo         = f($RS,'ano_modelo'); 
    $w_ano_fabricacao     = f($RS,'ano_fabricacao');                        
    $w_renavam            = f($RS,'renavam'); 
    $w_chassi             = f($RS,'chassi');                 
    $w_alugado            = f($RS,'alugado');
    $w_ativo              = f($RS,'ativo');    
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    FormataData();
    FormataDataHora();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Tipo Veiculo','SELECT',1,1,18,'','0123456789');     
      Validate('w_placa','Placa','1','1','6','7','1','1');      
      Validate('w_marca','Marca','1','1','2','20','1','1'); 
      Validate('w_modelo','Modelo','1','1','3','20','1','1'); 
      Validate('w_renavam','Renavam','1','1','2','20','1','1'); 
      Validate('w_chassi','Chassi','1','1','2','20','1','1');                                                                     
      Validate('w_combustivel','Combustível','1','1','5','8','1','1'); 
      Validate('w_tipo','Tipo','1','1','4','20','1','1'); 
      Validate('w_ano_modelo','Ano Modelo','1','1','2','4','4','1'); 
      Validate('w_ano_fabricacao','Ano Fabricação','1','1','4','4','1','1'); 
      Validate('w_potencia','Potência','1','1','2','6','1','1'); 
      Validate('w_cilindrada','Cilindrada','1','1','2','6','1','1'); 
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
    BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad=document.Form.w_chave_aux.focus();');
  } elseif ($O=='L') {
    BodyOpen('onLoad=this.focus();');
  } else{
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Grupo','nm_grupo_veiculo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_veiculo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Marca','marca').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modelo','modelo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ano','ano_fabricacao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Combustivel','combustivel').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Placa','nm_veiculo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Alugado','alugado').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'nm_grupo_veiculo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo_veiculo').'</td>');
        ShowHTML('        <td>'.f($row,'marca').'</td>');
        ShowHTML('        <td>'.f($row,'modelo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ano_fabricacao').'/'.f($row,'ano_modelo').'</td>');
        ShowHTML('        <td>'.f($row,'combustivel').'</td>');
        ShowHTML('        <td align="center">'.substr(f($row,'nm_veiculo'),0,8).'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_alugado').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">Alterar </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">Excluir </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>'') {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } else {
      MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,$RS->PageCount,$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) {
      $w_Disabled=' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');  
    ShowHTML('<INPUT type="hidden" name="w_placa_ant" value="'.$w_placa.'">');        
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr valign="top">');   
    SelecaoTipoVeiculo('<u>T</u>ipo veículo:','T','Selecione o veículo desejado',$w_cliente,$w_chave_aux,null,'w_chave_aux',null);
    ShowHTML('      <td><b><u>P</u>laca:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_placa" class="STI" SIZE="7" MAXLENGTH="7" VALUE="'.$w_placa.'"></td>');
    ShowHTML('      <td title="Marca do veículo, conforme documento."><b><u>M</u>arca:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_marca" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_marca.'"></td>');
    ShowHTML('      <td title="Modelo do veículo, conforme documento."><b>M<u>o</u>delo:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_modelo" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_modelo.'"></td>');
    ShowHTML('    <tr valign="top">');   
    ShowHTML('      <td title="Código RENAVAM, conforme documento do veículo."><b><u>R</u>enavam:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_renavam" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_renavam.'"></td>');
    ShowHTML('      <td title="Número do chassi, conforme documento do veículo."><b>Cha<u>s</u>si:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_chassi" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_chassi.'"></td>');
    ShowHTML('      <td title="Combustível, conforme documento do veículo."><b><u>C</u>ombustível:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_combustivel" class="STI" SIZE="8" MAXLENGTH="8" VALUE="'.$w_combustivel.'"></td>');
    ShowHTML('      <td title="Tipo do veículo, conforme documento."><b>Tipo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_tipo" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_tipo.'"></td>');
    ShowHTML('    <tr valign="top">');   
    ShowHTML('      <td title="Ano do modelo do veiculo, conforme documento."><b><u>A</u>no modelo:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano_modelo" class="STI" SIZE="18" MAXLENGTH="4" VALUE="'.$w_ano_modelo.'"></td>'); 
    ShowHTML('      <td title="Ano de fabricação, conforme documento do veículo."><b>Ano <u>F</u>abricação:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano_fabricacao" class="STI" SIZE="18" MAXLENGTH="4" VALUE="'.$w_ano_fabricacao.'"></td>');
    ShowHTML('      <td title="Potência do veículo, conforme documento."><b>Po<u>t</u>ência:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_potencia" class="STI" SIZE="6" MAXLENGTH="6" VALUE="'.$w_potencia.'"></td>');
    ShowHTML('      <td title="Cilindrada do veículo, conforme documento."><b>C<u>i</u>lindrada:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_cilindrada" class="STI" SIZE="6" MAXLENGTH="6" VALUE="'.$w_cilindrada.'"></td>');
    ShowHTML('    <tr valign="top">');
    MontaRadioNS('<b>Alugado?</b>',$w_alugado,'w_alugado');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td colspan=5 align="center"><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_nome='.$p_nome.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('  </table>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Opção não disponível\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 
// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'SRABAST':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putAbastecimento::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), Nvl($_REQUEST['w_chave_aux'],''), $_REQUEST['w_data'], $_REQUEST['w_hodometro'], $_REQUEST['w_litros'],
          $_REQUEST['w_valor'], $_REQUEST['w_local']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;        
    case 'SRGRUPO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (strtoupper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {           
            $RS = db_getGrupoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, strtoupper($_REQUEST['w_sigla']),'S');
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe grupo de veículo com esta sigla!\');');
              ScriptClose();
              retornaFormulario('w_nome');
              exit();
            }
          } 
        } elseif ($O=='E') {
          $RS = db_getTipoVeiculo::getInstanceOf($dbms, null, $w_cliente, Nvl($_REQUEST['w_chave'],''), null, null, null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe tipo de veículo associado a este grupo, não sendo possível sua exclusão!\');');
            ScriptClose(); 
            retornaFormulario('w_assinatura');
            exit();
          }  
        }
        dml_putGrupoVeiculo::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_nome'],strtoupper($_REQUEST['w_sigla']),
          $_REQUEST['w_descricao'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'SROPINIAO':
      // Verifica se a Assinatura Eletrônica é válida 
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if ($_REQUEST['w_nome']!= $_REQUEST['w_nome_ant']) {  
            $RS = db_getOpiniao::getInstanceOf($dbms, null, $w_cliente, $_REQUEST['w_nome'],null,null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe opinião com este nome!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          }          
          if (strtoupper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {  
            $RS = db_getOpiniao::getInstanceOf($dbms, null, $w_cliente, null, strtoupper($_REQUEST['w_sigla']),null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe sigla com este nome!\');');
              ScriptClose();
              retornaFormulario('w_sigla');
              exit();
            }
          } 
        /*} elseif ($O=='E') {
          $RS = db_getOpiniao::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,'RESTRICAO');                                               
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe serviços associado a este opinião, não sendo possível sua exclusão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          } */
        } 
        dml_putOpiniao::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente, $_REQUEST['w_nome'],strtoupper($_REQUEST['w_sigla']),$_REQUEST['w_ordem']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'SRTIPOVEI':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (strtoupper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {           
            $RS = db_getTipoVeiculo::getInstanceOf($dbms, null, $w_cliente, null, null, strtoupper($_REQUEST['w_sigla']),null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe sigla com este nome!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          } 
        } elseif ($O=='E') {
          $RS = db_getVeiculo::getInstanceOf($dbms, null, Nvl($_REQUEST['w_chave'],''), $w_cliente,  null, null, null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe veículo associado a este tipo de veículo, não sendo possível sua exclusão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          }  
        }
        dml_putTipoVeiculo::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), $w_cliente, Nvl($_REQUEST['w_chave_aux'],''), $_REQUEST['w_nome'],$_REQUEST['w_sigla'],
          $_REQUEST['w_descricao'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;    
    case 'SRVEICULO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (strtoupper($_REQUEST['w_placa'])!= $_REQUEST['w_placa_ant']) {           
            $RS = db_getVeiculo::getInstanceOf($dbms, null, null, $w_cliente, strtoupper($_REQUEST['w_placa']), null, null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Já existe placa com este número!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          } 
        } elseif ($O=='E') {
          $RS = db_getAbastecimento::getInstanceOf($dbms, null, Nvl($_REQUEST['w_chave'],''),$w_cliente);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe abastecimento associado a este veículo, não sendo possível sua exclusão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          }  
        }
        dml_putVeiculo::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), Nvl($_REQUEST['w_chave_aux'],''), $w_cliente, strtoupper($_REQUEST['w_placa']),$_REQUEST['w_marca'],
          $_REQUEST['w_modelo'],$_REQUEST['w_combustivel'],$_REQUEST['w_tipo'],$_REQUEST['w_potencia'],$_REQUEST['w_cilindrada'],$_REQUEST['w_ano_modelo'],$_REQUEST['w_ano_fabricacao'],
          $_REQUEST['w_renavam'],$_REQUEST['w_chassi'],$_REQUEST['w_alugado'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;  
       
    default:
      ScriptOpen('JavaScript');
      ShowHTML('  alert(\'Bloco de dados não encontrado: '.$SG.'\');');
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
    case 'ABASTECIMENTO':     Abastecimento();    break;    
    case 'GRUPO':             Grupo();            break;
    case 'TIPOVEICULO':       TipoVeiculo();      break;
    case 'OPINIAO':           Opiniao();          break;
    case 'VEICULO':           Veiculo();          break;    
    case 'GRAVA':             Grava();            break;
    default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    break;
  } 
} 
?>