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
include_once($w_dir_volta.'classes/sp/db_getOpiniao.php');
include_once($w_dir_volta.'classes/sp/db_getGrupoVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getTipoVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getVeiculo.php');
include_once($w_dir_volta.'classes/sp/db_getCelular.php');
include_once($w_dir_volta.'classes/sp/db_getAbastecimento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putOpiniao.php');
include_once($w_dir_volta.'classes/sp/dml_putGrupoVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putVeiculo.php');
include_once($w_dir_volta.'classes/sp/dml_putCelular.php');
include_once($w_dir_volta.'classes/sp/dml_putAbastecimento.php');
include_once($w_dir_volta.'funcoes/selecaoGrupoVeiculo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVeiculo.php');
include_once($w_dir_volta.'funcoes/selecaoVeiculo.php');

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas b�sicas do m�dulo de recursos log�sticos
// Mail     : billy@sbpi.com.br
// Criacao  : 28/11/2006 09:00
// Versao   : 1.0.0.0
// Local    : Bras�lia - DF
// -------------------------------------------------------------------------
// 
// Par�metros recebidos:
//    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
//    O (opera��o)   = I   : Inclus�o
//                   = A   : Altera��o
//                   = E   : Exclus�o
//                   = L   : Listagem

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par          = upper($_REQUEST['par']);
$P1           = Nvl($_REQUEST['P1'],0);
$P2           = Nvl($_REQUEST['P2'],0);
$P3           = Nvl($_REQUEST['P3'],1);
$P4           = nvl($_REQUEST['P4'],$conPageSize);
$TP           = $_REQUEST['TP'];
$SG           = upper($_REQUEST['SG']);
$R            = $_REQUEST['R'];
$O            = upper($_REQUEST['O']);
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina     = 'tabelas.php?par=';
$w_dir        = 'mod_sr/';
$w_dir_volta  = '../';
$w_Disabled   = 'ENABLED';

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON'] !='Sim') EncerraSessao();

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O=='') $O='L';

switch ($O) {
  case 'I':     $w_TP=$TP.' - Inclus�o';        break;
  case 'A':     $w_TP=$TP.' - Altera��o';       break;
  case 'E':     $w_TP=$TP.' - Exclus�o';        break;
  default:      $w_TP=$TP.' - Listagem';        break;
}
 
// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);


if(nvl($w_menu,'')!=''){
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
  $w_libera_edicao = f($RS_Menu,'libera_edicao');
  
  if ($w_libera_edicao=='N' && strpos('LP',$O)===false) {
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    ShowHTML('</head>');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><b>Opera��o n�o permitida!</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    exit();
  }
}

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

  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_chave_aux          = $_REQUEST['w_chave_aux'];    
    $w_data               = FormataDataEdicao($_REQUEST['w_data']);
    $w_hodometro          = $_REQUEST['w_hodometro'];       
    $w_litros             = $_REQUEST['w_litros'];
    $w_valor              = $_REQUEST['w_valor']; 
    $w_local              = $_REQUEST['w_local'];    
  } elseif ($O=='L') {
    $sql = new db_getAbastecimento; $RS = $sql->getInstanceOf($dbms, null, $w_chave_aux, $w_cliente);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'data','desc');
    } else {
      $RS = SortArray($RS,'data','desc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $sql = new db_getAbastecimento; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave              = f($RS,'chave');
    $w_chave_aux          = f($RS,'sq_veiculo');    
    $w_data               = FormataDataEdicao(f($RS,'data'));
    $w_hodometro          = f($RS,'hodometro');       
    $w_litros             = f($RS,'litros');
    $w_valor              = f($RS,'valor'); 
    $w_local              = f($RS,'local');    
  }
  
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    CheckBranco();    
    FormataData();
    SaltaCampo();
    FormataValor();  
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Ve�culo','SELECT',1,1,18,'','0123456789');               
      Validate('w_data','Data','DATA',1,10,10,'','0123456789/');      
      Validate('w_hodometro','Hod�mentro','1',1,2,7,'','0123456789/');       
      Validate('w_litros','Litros','VALOR','1',4,18,'','0123456789.,');
      Validate('w_valor','Valor','VALOR','1',4,18,'','0123456789.,');
      Validate('w_local','Local','1','1','5','60','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I') {
    BodyOpen('onLoad="document.Form.w_chave_aux.focus()";');
  } elseif ($O=='A') {
    BodyOpen('onLoad="document.Form.w_data.focus()";');    
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Marca','marca').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modelo','modelo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ano','ano_fabricacao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Placa','placa').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Data','data').'</td>');    
    ShowHTML('          <td><b>'.LinkOrdena('Litros','litros').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Valor','valor').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Local','local').'</td>');    
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
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
    SelecaoVeiculo('<u>V</u>e�culo:','V','Selecione o ve�culo',$w_cliente,$w_chave_aux,null,'w_chave_aux',null);
    ShowHTML('      <td><b><u>D</u>ata:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data.'"onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data do abastecimento.">'.ExibeCalendario('Form','w_data').'</td>');
    ShowHTML('      <td><b><u>H</u>od�metro:</b><br><input '.$w_Disabled.' accesskey="H" type="text" name="w_hodometro" class="STI" SIZE="7" MAXLENGTH="7" VALUE="'.$w_hodometro.'"></td>');
    ShowHTML('      <tr><td ><b><u>L</u>itros:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_litros" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.number_format($w_litros,2,',','.').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe a quantidade de litro do abastecimento do ve�culo."></td>');
    ShowHTML('      <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.number_format($w_valor,2,',','.').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor do abastecimento do ve�culo."></td>');
    ShowHTML('      <td><b>Lo<u>c</u>al:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_local" class="STI" SIZE="40" MAXLENGTH="60" VALUE="'.$w_local.'"></td>');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de Grupo de Ve�culo
// -------------------------------------------------------------------------
function Grupo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opini�o</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_nome         = $_REQUEST['w_nome'];
    $w_sigla        = $_REQUEST['w_sigla'];
    $w_descricao    = $_REQUEST['w_descricao']; 
    $w_ativo        = $_REQUEST['w_ativo'];    
  } elseif ($O=='L') {
    $sql = new db_getGrupoVeiculo; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getGrupoVeiculo; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, $w_nome, $w_sigla, null);
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
      Validate('w_descricao','descri��o','1','1','3','255','1','1');       
      Validate('w_sigla','Sigla','1','1','1','10','1',''); 
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.f($row,'cliente').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
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
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de celulares
// -------------------------------------------------------------------------
function Celular() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    extract($_POST);
  } elseif ($O=='L') {
    $sql = new db_getCelular; $RS = $sql->getInstanceOf($dbms, $w_cliente, null,null,null,null,null,null,null, null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'numero_linha','asc');
    } else {
      $RS = SortArray($RS,'numero_linha','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getCelular; $RS = $sql->getInstanceOf($dbms, $w_cliente, $w_chave,null,null,null,null,null,null, null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_numero         = f($RS,'numero_linha');
    $w_marca          = f($RS,'marca');
    $w_modelo         = f($RS,'modelo'); 
    $w_card           = f($RS,'sim_card');
    $w_imei           = f($RS,'imei');
    $w_ativo          = f($RS,'ativo');
    $w_acessorios     = f($RS,'acessorios');
    $w_bloqueio       = f($RS,'bloqueado');
    $w_motivo         = f($RS,'motivo_bloqueio');
    $w_inicio         = ($w_bloqueio=='N') ? '' : formataDataEdicao(f($RS,'inicio_bloqueio'));
    $w_fim            = ($w_bloqueio=='S') ? '' : formataDataEdicao(f($RS,'fim_bloqueio'));
  } 
  
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de celulares</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    checkBranco();
    modulo();
    saltaCampo();
    FormataData();
    FormataValor();
    ShowHTML('  function bloqueio() {');
    ShowHTML('    var theForm = document.Form;');
    ShowHTML('    if (theForm.w_bloqueio[0].checked) {');
    ShowHTML('      theForm.w_inicio.className="STIO";');
    ShowHTML('      theForm.w_motivo.className="STIO";');
    ShowHTML('    } else {');
    ShowHTML('      theForm.w_inicio.className="STI";');
    ShowHTML('      theForm.w_motivo.className="STI";');
    ShowHTML('    }');
    ShowHTML('  } ');
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_numero','N�mero da linha','1','1','8','20','1','1');
      Validate('w_marca','Marca','1','1','2','40','1',''); 
      Validate('w_modelo','Modelo','1','1','2','40','1','1');       
      Validate('w_card','SIM CARD','1','1','1','25','1',''); 
      Validate('w_imei','IMEI','1','1','1','25','1','1');       
      Validate('w_acessorios','Acess�rios','1','1','5','1000','1','1');
      Validate('w_inicio','Data de in�cio do bloqueio','DATA','','10','10','','0123456789/'); 
      ShowHTML('  if (theForm.w_bloqueio[0].checked && theForm.w_inicio.value=="") {');
      ShowHTML('    alert("Indique o in�cio do bloqueio!"); ');
      ShowHTML('    theForm.w_inicio.focus(); ');
      ShowHTML('    return false; ');
      ShowHTML('  } else if (theForm.w_bloqueio[1].checked && theForm.w_inicio.value!="") {');
      ShowHTML('    alert("Indique o in�cio do bloqueio somente se o aparelho estiver bloqueado para empr�stimo!"); ');
      ShowHTML('    theForm.w_inicio.focus(); ');
      ShowHTML('    return false; ');
      ShowHTML('  } ');
      Validate('w_motivo','Motivo do bloqueio','1','','5','1000','1','1');
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
      ShowHTML('  if (confirm("Confirma a exclus�o deste registro?")) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ShowHTML('  theForm.Botao[0].disabled=true;');
    ShowHTML('  theForm.Botao[1].disabled=true;');
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();  bloqueio();"');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_numero.focus(); bloqueio();"');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus(); bloqueio();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    ShowHTML('    [<a accesskey="I" class="ss" href="javascript:window.status.value;" onClick="window.open(\''.$conRootSIW.$w_dir.'geral.php?par=DispCelular&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Mapa de Disponibilidade de Celular&SG='.$SG.'\',\'Indicador\',\'width=730,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=no\');">MAPA DE DISPONIBILIDADE DE CELULAR</a>]');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('N�mero','numero_linha').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Marca','marca').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Modelo','modelo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('SIM CARD','sim_card').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('IMEI','imei').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Acess�rios','acessorios').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Bloqueado','bloqueado').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('In�cio bloqueio','inicio_bloqueio').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>'.f($row,'numero_linha').'</td>');
        ShowHTML('        <td>'.f($row,'marca').'</td>');
        ShowHTML('        <td>'.f($row,'modelo').'</td>');
        ShowHTML('        <td>'.f($row,'sim_card').'</td>');
        ShowHTML('        <td>'.f($row,'imei').'</td>');
        ShowHTML('        <td>'.f($row,'acessorios').'</td>');
        ShowHTML('        <td align="center">'.retornaSimNao(f($row,'bloqueado')).'</td>');
        ShowHTML('        <td align="center">'.((f($row,'bloqueado')=='N') ? '&nbsp;' : formataDataEdicao(f($row,'inicio_bloqueio'),5)).'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.f($row,'cliente').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="numero">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if ($O=='E') $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_numero_ant"  value="'.$w_numero.'">'); 
    ShowHTML('<INPUT type="hidden" name="w_marca_ant" value="'.$w_marca.'">');       
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><table width="97%" border="0">');
    ShowHTML('  <tr><td colspan=2><table width="100%" border="0">');
    ShowHTML('      <tr><td colspan="2"><b><u>N</u>�mero da linha:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_numero" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_numero.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>M</u>arca:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_marca" class="STI" SIZE="10" MAXLENGTH="40" VALUE="'.$w_marca.'"></td>');
    ShowHTML('        <td><b>M<u>o</u>delo:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_modelo" class="STI" SIZE="10" MAXLENGTH="40" VALUE="'.$w_modelo.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('        <td><b><u>S</u>IM CARD:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_card" class="STI" SIZE="25" MAXLENGTH="25" VALUE="'.$w_card.'"></td>');
    ShowHTML('        <td><b><u>I</u>MEI:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_imei" class="STI" SIZE="25" MAXLENGTH="25" VALUE="'.$w_imei.'"></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><b>A<U>c</U>ess�rios:<br><TEXTAREA ACCESSKEY="C" '.$w_Disabled.' class="sti" name="w_acessorios" rows="5" cols=75>'.$w_acessorios.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Bloqueado?</b>',$w_bloqueio,'w_bloqueio',null,null,'onclick="bloqueio();"');
    ShowHTML('        <td><b><u>D</u>esde:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_inicio" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$w_inicio.'"onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);" title="Data do abastecimento.">'.ExibeCalendario('Form','w_inicio').'</td>');
    ShowHTML('      <tr><td><b><U>M</U>otivo do bloqueio:<br><TEXTAREA ACCESSKEY="M" '.$w_Disabled.' class="sti" name="w_motivo" rows="5" cols=75>'.$w_motivo.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('           </table>');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</table>');
  Rodape();
} 

// =========================================================================
// Rotina de Opini�es
// -------------------------------------------------------------------------
function Opiniao() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opini�o</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina  
    $w_cliente      = $_REQUEST['w_cliente'];
    $w_chave        = $_REQUEST['w_chave'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_sigla        = $_REQUEST['w_sigla']; 
    $w_ordem        = $_REQUEST['w_ordem'];  
  } elseif ($O=='L') {
    $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'ordem','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,  $w_chave, $w_cliente, $w_nome, $_sigla, null);
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
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orienta��o:<ul>');
    ShowHTML('    <li>Insira cada uma das opini�es que estar�o dispon�veis para os usu�rios, n�o sendo permitida a repeti��o de nomes e siglas.');
    ShowHTML('    <li>A opini�o que tiver a sigla "IN" indicar� ao sistema que trata-se de uma insatisfa��o quanto ao atendimento. Neste caso, exigir� do usu�rio o motivo da insatisfa��o e enviar� um e-mail comunicando essa opini�o.');
//    ShowHTML('    <li>Se a sigla for IN o usu�rio deve colocar o motivo da insatisfa��o.');
    ShowHTML('    </ul></b></font></td>');    
    ShowHTML('<tr>');
    if ($w_libera_edicao=='S') {
      ShowHTML('<td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ordem','ordem').'</td>');
    if ($w_libera_edicao=='S') {
      ShowHTML('          <td><b> Opera��es </td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS,(($P3-1)*$P4),$P4);
      foreach($RS1 as $row) { 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'ordem').'</td>');
        if ($w_libera_edicao=='S') {
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&w_cliente='.f($row,'cliente').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
          ShowHTML('        </td>');
        }
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
    //Aqui come�a a manipula��o de registros
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
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de Tipo de Ve�culo
// -------------------------------------------------------------------------
function TipoVeiculo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opini�o</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_chave_aux          = $_REQUEST['w_chave_aux'];    
    $w_nome               = $_REQUEST['w_nome'];
    $w_sigla              = $_REQUEST['w_sigla'];
    $w_descricao          = $_REQUEST['w_descricao']; 
    $w_ativo              = $_REQUEST['w_ativo'];  
  } elseif ($O=='L') {
    $sql = new db_getTipoVeiculo; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getTipoVeiculo; $RS = $sql->getInstanceOf($dbms, $w_chave, $w_cliente, null, null, null, null);
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
      Validate('w_descricao','descri��o','1','1','3','255','1','1');
      Validate('w_sigla','Sigla','1','1','1','10','1','');  
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_chave_aux.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Grupo','nm_grupo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</td>');
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
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
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Rotina de Ve�culo
// -------------------------------------------------------------------------
function Veiculo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de opini�o</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
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
    $sql = new db_getVeiculo; $RS = $sql->getInstanceOf($dbms, null, null, $w_cliente, null, null, null, null, null, null, null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'modelo','asc'); 
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getVeiculo; $RS = $sql->getInstanceOf($dbms, $w_chave, null, $w_cliente, null, null, null, null, null, null, null);
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
    SaltaCampo();
    FormataDataHora();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_chave_aux','Tipo Veiculo','SELECT',1,1,18,'','0123456789');     
      Validate('w_placa','Placa','1','1','6','7','1','1');      
      Validate('w_marca','Marca','1','1','2','20','1','1'); 
      Validate('w_modelo','Modelo','1','1','3','20','1','1'); 
      Validate('w_renavam','Renavam','1','1','2','20','1','1'); 
      Validate('w_chassi','Chassi','1','1','2','20','1','1');                                                                     
      Validate('w_combustivel','Combust�vel','1','1','5','8','1','1'); 
      Validate('w_tipo','Tipo','1','1','4','20','1','1'); 
      Validate('w_ano_modelo','Ano Modelo','1','1','2','4','4','1'); 
      Validate('w_ano_fabricacao','Ano Fabrica��o','1','1','4','4','1','1'); 
      Validate('w_potencia','Pot�ncia','1','1','2','6','1','1'); 
      Validate('w_cilindrada','Cilindrada','1','1','2','6','1','1'); 
      Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    } elseif ($O=='E') {
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_chave_aux.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else{
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
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
    ShowHTML('          <td><b> Opera��es </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
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
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui come�a a manipula��o de registros
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
    SelecaoTipoVeiculo('<u>T</u>ipo ve�culo:','T','Selecione o ve�culo desejado',$w_cliente,$w_chave_aux,null,'w_chave_aux',null);
    ShowHTML('      <td><b><u>P</u>laca:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_placa" class="STI" SIZE="7" MAXLENGTH="7" VALUE="'.$w_placa.'"></td>');
    ShowHTML('      <td title="Marca do ve�culo, conforme documento."><b><u>M</u>arca:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_marca" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_marca.'"></td>');
    ShowHTML('      <td title="Modelo do ve�culo, conforme documento."><b>M<u>o</u>delo:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_modelo" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_modelo.'"></td>');
    ShowHTML('    <tr valign="top">');   
    ShowHTML('      <td title="C�digo RENAVAM, conforme documento do ve�culo."><b><u>R</u>enavam:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_renavam" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_renavam.'"></td>');
    ShowHTML('      <td title="N�mero do chassi, conforme documento do ve�culo."><b>Cha<u>s</u>si:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_chassi" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_chassi.'"></td>');
    ShowHTML('      <td title="Combust�vel, conforme documento do ve�culo."><b><u>C</u>ombust�vel:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="w_combustivel" class="STI" SIZE="8" MAXLENGTH="8" VALUE="'.$w_combustivel.'"></td>');
    ShowHTML('      <td title="Tipo do ve�culo, conforme documento."><b>Tipo:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_tipo" class="STI" SIZE="20" MAXLENGTH="20" VALUE="'.$w_tipo.'"></td>');
    ShowHTML('    <tr valign="top">');   
    ShowHTML('      <td title="Ano do modelo do veiculo, conforme documento."><b><u>A</u>no modelo:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano_modelo" class="STI" SIZE="18" MAXLENGTH="4" VALUE="'.$w_ano_modelo.'"></td>'); 
    ShowHTML('      <td title="Ano de fabrica��o, conforme documento do ve�culo."><b>Ano <u>F</u>abrica��o:</b><br><input '.$w_Disabled.' accesskey="A" type="text" name="w_ano_fabricacao" class="STI" SIZE="18" MAXLENGTH="4" VALUE="'.$w_ano_fabricacao.'"></td>');
    ShowHTML('      <td title="Pot�ncia do ve�culo, conforme documento."><b>Po<u>t</u>�ncia:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_potencia" class="STI" SIZE="6" MAXLENGTH="6" VALUE="'.$w_potencia.'"></td>');
    ShowHTML('      <td title="Cilindrada do ve�culo, conforme documento."><b>C<u>i</u>lindrada:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_cilindrada" class="STI" SIZE="6" MAXLENGTH="6" VALUE="'.$w_cilindrada.'"></td>');
    ShowHTML('    <tr valign="top">');
    MontaRadioNS('<b>Alugado?</b>',$w_alugado,'w_alugado');
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr>');
    ShowHTML('      <tr><td colspan=5><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
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
    ShowHTML('  </table>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
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
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'SRABAST':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putAbastecimento; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), Nvl($_REQUEST['w_chave_aux'],''), $_REQUEST['w_data'], $_REQUEST['w_hodometro'], $_REQUEST['w_litros'],
          $_REQUEST['w_valor'], $_REQUEST['w_local']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;        
    case 'SRCEL':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O!='E') {
          // Impede dois celulares com o mesmo n�mero
          $sql = new db_getCelular; $RS = $sql->getInstanceOf($dbms, $w_cliente, null,null,null,null,null,null,null, null,null);
          $erro = false;
          foreach($RS as $row) {
            if (($O=='I' && $_REQUEST['w_numero']==f($row,'numero_linha')) ||
                ($O=='A' && $_REQUEST['w_numero']==f($row,'numero_linha') && $_REQUEST['w_chave']!=f($row,'chave'))
               ) {
              $erro = true;
            }
          }
          if ($erro) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert("J� existe celular cadastrado com o n�mero de linha informado!");');
            ScriptClose();
            RetornaFormulario('w_numero');
          }
        }
        $SQL = new dml_putCelular; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_numero'],$_REQUEST['w_marca'],
                $_REQUEST['w_modelo'], $_REQUEST['w_card'],$_REQUEST['w_imei'], $_REQUEST['w_acessorios'], $_REQUEST['w_bloqueio'],
                $_REQUEST['w_inicio'], $_REQUEST['w_motivo'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;        
    case 'SRGRUPO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (upper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {           
            $sql = new db_getGrupoVeiculo; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, upper($_REQUEST['w_sigla']),'S');
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'J� existe grupo de ve�culo com esta sigla!\');');
              ScriptClose();
              retornaFormulario('w_nome');
              exit();
            }
          } 
        } elseif ($O=='E') {
          $sql = new db_getTipoVeiculo; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, Nvl($_REQUEST['w_chave'],''), null, null, null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe tipo de ve�culo associado a este grupo, n�o sendo poss�vel sua exclus�o!\');');
            ScriptClose(); 
            retornaFormulario('w_assinatura');
            exit();
          }  
        }
        $SQL = new dml_putGrupoVeiculo; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente,$_REQUEST['w_nome'],upper($_REQUEST['w_sigla']),
          $_REQUEST['w_descricao'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'SROPINIAO':
      // Verifica se a Assinatura Eletr�nica � v�lida 
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if ($_REQUEST['w_nome']!= $_REQUEST['w_nome_ant']) {  
            $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, $_REQUEST['w_nome'],null,null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'J� existe opini�o com este nome!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          }          
          if (upper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {  
            $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, upper($_REQUEST['w_sigla']),null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'J� existe sigla com este nome!\');');
              ScriptClose();
              retornaFormulario('w_sigla');
              exit();
            }
          } 
        /*} elseif ($O=='E') {
          $sql = new db_getOpiniao; $RS = $sql->getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,'RESTRICAO');                                               
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe servi�os associado a este opini�o, n�o sendo poss�vel sua exclus�o!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          } */
        } 
        $SQL = new dml_putOpiniao; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$w_cliente, $_REQUEST['w_nome'],upper($_REQUEST['w_sigla']),$_REQUEST['w_ordem']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'SRTIPOVEI':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (upper($_REQUEST['w_sigla'])!= $_REQUEST['w_sigla_ant']) {           
            $sql = new db_getTipoVeiculo; $RS = $sql->getInstanceOf($dbms, null, $w_cliente, null, null, upper($_REQUEST['w_sigla']),null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'J� existe sigla com este nome!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          } 
        } elseif ($O=='E') {
          $sql = new db_getVeiculo; $RS = $sql->getInstanceOf($dbms, null, Nvl($_REQUEST['w_chave'],''), $w_cliente,  null, null, null, null, null, null, null);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe ve�culo associado a este tipo de ve�culo, n�o sendo poss�vel sua exclus�o!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          }  
        }
        $SQL = new dml_putTipoVeiculo; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), $w_cliente, Nvl($_REQUEST['w_chave_aux'],''), $_REQUEST['w_nome'],$_REQUEST['w_sigla'],
          $_REQUEST['w_descricao'], $_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;    
    case 'SRVEICULO':
      // Verifica se a Assinatura Eletr�nica � v�lida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          if (upper($_REQUEST['w_placa'])!= $_REQUEST['w_placa_ant']) {           
            $sql = new db_getVeiculo; $RS = $sql->getInstanceOf($dbms, null, null, $w_cliente, upper($_REQUEST['w_placa']), null, null, null, null, null, null);
            if (count($RS)>0) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'J� existe placa com este n�mero!\');');
              ShowHTML('  history.go(-1);');
              ScriptClose();
              exit();
            }
          } 
        } elseif ($O=='E') {
          $sql = new db_getAbastecimento; $RS = $sql->getInstanceOf($dbms, null, Nvl($_REQUEST['w_chave'],''),$w_cliente);
          if (count($RS)>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe abastecimento associado a este ve�culo, n�o sendo poss�vel sua exclus�o!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          }  
        }
        $SQL = new dml_putVeiculo; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''), Nvl($_REQUEST['w_chave_aux'],''), $w_cliente, upper($_REQUEST['w_placa']),$_REQUEST['w_marca'],
          $_REQUEST['w_modelo'],$_REQUEST['w_combustivel'],$_REQUEST['w_tipo'],$_REQUEST['w_potencia'],$_REQUEST['w_cilindrada'],$_REQUEST['w_ano_modelo'],$_REQUEST['w_ano_fabricacao'],
          $_REQUEST['w_renavam'],$_REQUEST['w_chassi'],$_REQUEST['w_alugado'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
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
    case 'ABASTECIMENTO':     Abastecimento();    break;    
    case 'GRUPO':             Grupo();            break;
    case 'TIPOVEICULO':       TipoVeiculo();      break;
    case 'OPINIAO':           Opiniao();          break;
    case 'VEICULO':           Veiculo();          break;    
    case 'CELULAR':           Celular();          break;    
    case 'GRAVA':             Grava();            break;
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