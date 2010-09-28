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
include_once($w_dir_volta.'classes/sp/db_getAfastamento.php');
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/db_getGPColaborador.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putAfastamento.php');
include_once($w_dir_volta.'funcoes/selecaoTipoAfastamento.php');
include_once($w_dir_volta.'funcoes/selecaoColaborador.php');
include_once($w_dir_volta.'funcoes/exibeColaborador.php');
include_once('validaafastamento.php');
// =========================================================================
//  /afastamento.php
// ------------------------------------------------------------------------

// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas básicas do módulo de gestão de pessoal
// Mail     : billly@sbpi.com.br
// Criacao  : 02/08/2005 10:00
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
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par            = upper($_REQUEST['par']);
$P1             = Nvl($_REQUEST['P1'],0);
$P2             = Nvl($_REQUEST['P2'],0);
$P3             = Nvl($_REQUEST['P3'],1);
$P4             = Nvl($_REQUEST['P4'],$conPageSize);
$TP             = $_REQUEST['TP'];
$SG             = upper($_REQUEST['SG']);
$R              = lower($_REQUEST['R']);
$O              = upper($_REQUEST['O']);
$p_ordena       = $_REQUEST['p_ordena'];
$w_troca        = $_REQUEST['w_troca'];
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'afastamento.php?par=';
$w_dir          = 'mod_rh/';
$w_dir_volta    = '../';
$w_Disabled     = 'ENABLED';

if ($SG=='GPAFAST'&& $O == '') $O = 'P';
switch ($O) {
  case 'I':    $w_TP=$TP.' - Inclusão';     break;
  case 'A':    $w_TP=$TP.' - Alteração';    break;
  case 'E':    $w_TP=$TP.' - Exclusão';     break;
  default:     $w_TP=$TP.' - Listagem';     break;
} 

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu    = RetornaMenu($w_cliente,$SG);
Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Rotina de afastamentos
// -------------------------------------------------------------------------
function Afastamento() {
  extract($GLOBALS);
  Global $w_Disabled;
  Global $w_sg_afast;
  $w_chave  = $_REQUEST['w_chave'];
  if ($w_troca>'') {
    $w_sq_tipo_afastamento      = $_REQUEST['w_sq_tipo_afastamento'];
    $w_sq_contrato_colaborador  = $_REQUEST['w_sq_contrato_colaborador'];
    $w_inicio_data              = $_REQUEST['w_inicio_data'];
    $w_inicio_periodo           = $_REQUEST['w_inicio_periodo'];
    $w_fim_data                 = $_REQUEST['w_fim_data'];
    $w_fim_periodo              = $_REQUEST['w_fim_periodo'];
    $w_dias                     = $_REQUEST['w_dias'];
    $w_observacao               = $_REQUEST['w_observacao'];
  } else {
    if ($O=='L') {
      $RS = db_getAfastamento::getInstanceOf($dbms,$w_cliente,null,null,$_REQUEST['p_sq_tipo_afastamento'],$_REQUEST['p_sq_contrato_colaborador'],$_REQUEST['p_inicio_data'],$_REQUEST['p_fim_data'],null,null,null,null);
      if (Nvl($p_ordena,'') > '') {
        $lista = explode(',',str_replace(' ',',',$p_ordena));
        $RS = SortArray($RS,$lista[0],$lista[1]);
      } else {
        $RS = SortArray($RS,'nome','asc'); 
      }    
    } elseif (!(strpos('AEV',$O)===false)) {
      $RS = db_getAfastamento::getInstanceOf($dbms,$w_cliente,null,$w_chave,null,null,null,null,null,null,null,null);
      foreach ($RS as $row) {$RS = $row; break;}
      $w_chave                   = f($RS,'chave');
      $w_sq_tipo_afastamento     = f($RS,'sq_tipo_afastamento');
      $w_sq_contrato_colaborador = f($RS,'sq_contrato_colaborador');
      $w_inicio_data             = f($RS,'inicio_data');
      $w_inicio_periodo          = f($RS,'inicio_periodo');
      $w_fim_data                = f($RS,'fim_data');
      $w_fim_periodo             = f($RS,'fim_periodo');
      $w_dias                    = f($RS,'dias');
      $w_observacao              = f($RS,'observacao');
    } 
  } 
  if (Nvl($w_sq_tipo_afastamento,'')>'' && (!(strpos('IA',$O)===false))) {
      $RS1 = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,$w_sq_tipo_afastamento,null,null,null,null,'MODALIDADES');
      foreach($RS1 as $row){$RS1=$row; break;}
      if(f($RS1,'abate_banco_horas') != 'N'){
        $w_sg_afast = true;
      }else{
        $w_sg_afast = false;
      }
      
  } 
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de afastamentos</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">'); 
  }
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAEP',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_tipo_afastamento','Tipo de afastamento','SELECT','1','1','18','','0123456789');
      Validate('w_sq_contrato_colaborador','Colaborador','SELECT','1','1','18','','0123456789');
      Validate('w_inicio_data','Início','DATA','1','10','10','','0123456789/');
      if (Nvl($w_sq_tipo_afastamento,'')>'') {
        if (f($RS1,'periodo')=='A') {
          Validate('w_fim_data','Término','DATA','1','10','10','','0123456789/');
          CompData('w_inicio_data','Início','<=','w_fim_data','Término');
          ShowHTML('  if (theForm.w_inicio_data.value == theForm.w_fim_data.value) {');
          ShowHTML('     if (theForm.w_inicio_periodo[1].checked && theForm.w_fim_periodo[0].checked) {');
          ShowHTML('        alert(\'Período de término do afastamento deve ser igual ou posterior ao de início!\');');
          ShowHTML('        return false;');
          ShowHTML('     }');
          ShowHTML('  }');
          if (f($RS1,'contagem_dias')=='C') {
            ShowHTML('  var w_data, w_data1, w_data2;');
            ShowHTML('  w_data = theForm.w_inicio_data.value;');
            ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
            ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
            ShowHTML('  w_data = theForm.w_fim_data.value;');
            ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
            ShowHTML('  w_data2= new Date(Date.parse(w_data));');
            ShowHTML('  var MinMilli = 1000 * 60;');
            ShowHTML('  var HrMilli = MinMilli * 60;');
            ShowHTML('  var DyMilli = HrMilli * 24;');
            ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
            ShowHTML('  if (theForm.w_inicio_periodo[0].checked) {');
            ShowHTML('     if (theForm.w_fim_periodo[0].checked) Days = Days + 0.5; ');
            ShowHTML('     else Days = Days + 1; ');
            ShowHTML('  }');
            ShowHTML('  else {');
            ShowHTML('     if (theForm.w_fim_periodo[1].checked) Days = Days + 0.5; ');
            ShowHTML('  }');
            ShowHTML('  if (Days > '.f($RS1,'limite_dias').') {');
            ShowHTML('     alert(\''.f($RS1,'nome').' tem limite de '.f($RS1,'limite_dias').' dias '.lower(f($RS1,'nm_contagem_dias')).'!\');');
            ShowHTML('     theForm.w_inicio_data.focus();');
            ShowHTML('     return false;');
            ShowHTML('  }');
          } 
        } elseif (f($RS1,'periodo')=='D') {
          Validate('w_dias','Dias','','1','1','4','','0123456789');
          CompValor('w_dias','Dias','>',0,'zero');
          ShowHTML('  if (parseInt(theForm.w_dias.value) > '.f($RS1,'limite_dias').') {');
          ShowHTML('     alert(\''.f($RS1,'nome').' tem limite de '.f($RS1,'limite_dias').' dias '.lower(f($RS1,'nm_contagem_dias')).'!\');');
          ShowHTML('     theForm.w_dias.focus();');
          ShowHTML('     return false;');
          ShowHTML('  }');
        } elseif (f($RS1,'periodo')=='H') {
          if (f($RS1,'contagem_dias')=='C') {
            ShowHTML('  var w_data, w_data1, w_data2;');
            ShowHTML('  w_data = theForm.w_inicio_data.value;');
            ShowHTML('  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);');
            ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
            ShowHTML('  w_data = theForm.w_fim_data.value;');
            ShowHTML('  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);');
            ShowHTML('  w_data2= new Date(Date.parse(w_data));');
            ShowHTML('  var MinMilli = 1000 * 60;');
            ShowHTML('  var HrMilli = MinMilli * 60;');
            ShowHTML('  var DyMilli = HrMilli * 24;');
            ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
            ShowHTML('  if (theForm.w_inicio_periodo[0].checked) {');
            ShowHTML('     if (theForm.w_fim_periodo[0].checked) Days = Days + 0.5; ');
            ShowHTML('     else Days = Days + 1; ');
            ShowHTML('  }');
            ShowHTML('  else {');
            ShowHTML('     if (theForm.w_fim_periodo[1].checked) Days = Days + 0.5; ');
            ShowHTML('  }');
            ShowHTML('  if (Days > '.f($RS1,'limite_dias').') {');
            ShowHTML('     alert(\''.f($RS1,'nome').' tem limite de '.f($RS1,'limite_dias').' dias '.lower(f($RS1,'nm_contagem_dias')).'\');');
            ShowHTML('     theForm.w_inicio_data.focus();');
            ShowHTML('     return false;');
            ShowHTML('  }');
          } 
          ShowHTML('theForm.w_fim_data = theForm.w_inicio_data;');
          ShowHTML('theForm.w_fim_periodo = theForm.w_inicio_periodo;');
        } 
      } 
      Validate('w_observacao','Observação','','1','1','300','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='P') {
      Validate('p_sq_tipo_afastamento','Tipo de afastamento','SELECT','','1','18','','0123456789');
      Validate('p_sq_contrato_colaborador','Colaborador','SELECT','','1','18','','0123456789');
      Validate('p_inicio_data','Início','DATA','','10','10','','0123456789/');
      Validate('p_fim_data','Término','DATA','','10','10','','0123456789/');
      CompData('p_inicio_data','Início','<=','p_fim_data','Término');
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
  } elseif (!(strpos('IA',$O)===false)) {
    BodyOpen('onLoad=document.Form.w_sq_tipo_afastamento.focus();');
  } elseif (!(strpos('P',$O)===false)) {
    BodyOpen('onLoad=document.Form.p_sq_tipo_afastamento.focus();');
  } elseif ($O=='L'){
    BodyOpen('onLoad=this.focus();');
  } else {
    BodyOpen('onLoad=document.Form.w_assinatura.focus();');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Colaborador','nome_resumido').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Localização','local').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo do afastamento','nm_tipo_afastamento').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Início','inicio_data').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Término','fim_data').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem 
      $RS1 = array_slice($RS,(($P3-1)*$P4), $P4);  
      foreach($RS1 as $row){
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;      
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.exibeColaborador('',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome_resumido')).'</td>');
        ShowHTML('        <td align="left">'.exibeUnidade('../',$w_cliente,f($row,'local'),f($row,'sq_unidade'),$TP).'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_tipo_afastamento').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'inicio_data')).' - '.f($row,'inicio_periodo').'</td>');
        ShowHTML('        <td align="center">'.FormataDataEdicao(f($row,'fim_data')).' - '.f($row,'fim_periodo').'</td>');
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'">EX </A>&nbsp');
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
  } elseif (!(strpos('IAEV',$O)===false)){
    if (!(strpos('EV',$O)===false)) $w_Disabled = ' DISABLED';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    SelecaoTipoAfastamento('<u>T</u>ipo do afastamento:','T',null,$w_sq_tipo_afastamento,null,'w_sq_tipo_afastamento','ativo = "S"','onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_contrato_colaborador\'; document.Form.submit();"');
    if ($w_sq_tipo_afastamento > '' && $O!='E') {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Afastamento informado em');
      if (f($RS1,'periodo')=='A') {
        ShowHTML(' <b>datas</b>.');
      } elseif (f($RS1,'periodo')=='D') {
        ShowHTML(' <b>dias</b>.');
      } elseif (f($RS1,'periodo')=='H') {
        ShowHTML(' <b>horas</b>.');
      } 
      ShowHTML(' Limitado a <b>'.f($RS1,'limite_dias').'</b> dias ');
      if (f($RS1,'contagem_dias')=='U') {
        ShowHTML(' <b>úteis</b>.');
      } elseif (f($RS1,'contagem_dias')=='C') {
        ShowHTML(' <b>corridos</b>.');
      } 
      ShowHTML(' Aplica-se');
      if (f($RS1,'sexo')=='A') {
        ShowHTML('<b>a ambos os sexos</b>,');
      } elseif (f($RS1,'sexo')=='M') {
        ShowHTML('<b>apenas ao sexo masculino</b>,');
      } elseif (f($RS1,'sexo')=='F') {
          ShowHTML('<b>apenas ao sexo feminino</b>,');
      } 
      ShowHTML(' contratado nas modalidades ');
      if (Nvl(f($RS1,'nm_modalidade'),'')>'') {
        $w_modalidades = $w_modalidades.'<b>'.trim(f($RS1,'nm_modalidade')).'</b>';
        $RS2 = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,$w_sq_tipo_afastamento,null,null,null,null,'MODALIDADES');
        if (count($RS2)>0) {
          $i=0;
          foreach ($RS2 as $row2) {
            if($i!=0) $w_modalidades = $w_modalidades.', <b>'.f($row2,'nm_modalidade').'</b>';
            $i+=1;
          } 
        } 
        ShowHTML($w_modalidades);
      } if (f($RS1,'percentual_pagamento')==100) {
          ShowHTML(' tendo <b>remuneração integral</b> durante o afastamento.</div>');
      } else {
        ShowHTML(' tendo <b>'.f($RS1,'percentual_pagamento').'% da remuneração</b> durante o afastamento.</div>');
      } 
    } 
    ShowHTML('      <tr>');
    SelecaoColaborador('<u>C</u>olaborador:','C',null,$w_sq_contrato_colaborador,$w_sq_tipo_afastamento,'w_sq_contrato_colaborador','SELAFAST',null);
    ShowHTML('      <tr><td><table width="100%" border="0">');
    ShowHTML('      <tr><td width="10%" valign="top"><b><u>I</u>nício:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_inicio_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_inicio_data).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
    ShowHTML('          <td valign="top"><b>Período?</b><br>');
    if ($w_inicio_periodo=='T') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_inicio_periodo" value="M"> Manhã <input '.$w_Disabled.' type="radio" name="w_inicio_periodo" value="T" checked> Tarde');
    } else{
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_inicio_periodo" value="M" checked> Manhã <input '.$w_Disabled.' type="radio" name="w_inicio_periodo" value="T"> Tarde');
    } 
    ShowHTML('</table></td></tr>');
    if (Nvl($w_sq_tipo_afastamento,'')>'' && $O!='E') {
      if (f($RS1,'periodo')=='A') {
        ShowHTML('      <tr><td><table width="100%" border="0">');
        ShowHTML('      <tr><td width="10%" valign="top"><b>Té<u>r</u>mino:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_fim_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.FormataDataEdicao($w_fim_data).'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
        ShowHTML('          <td valign="top"><b>Período?</b><br>');
        if ($w_fim_periodo=='M') {
          ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_fim_periodo" value="M" checked> Manhã <input '.$w_Disabled.' type="radio" name="w_fim_periodo" value="T"> Tarde');
        } else {
          ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_fim_periodo" value="M"> Manhã <input '.$w_Disabled.' type="radio" name="w_fim_periodo" value="T" checked> Tarde');
        } 
        ShowHTML('</table></td></tr>');
      } elseif (f($RS1,'periodo')=='D') {
        ShowHTML('      <tr><td valign="top"><b><u>N</u>úmero de dias:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_dias" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_dias.'"></td>');
        ShowHTML('<INPUT type="hidden" name="w_fim_periodo" value="T">');
      } elseif (f($RS1,'periodo')=='H') {
        ShowHTML('<INPUT type="hidden" name="w_fim_data" value="T">');
        ShowHTML('<INPUT type="hidden" name="w_fim_periodo" value="T">');
      } 
    } 
    ShowHTML('      <tr><td colspan=2><b><u>O</u>bservação:<br><TEXTAREA ACCESSKEY="O" '.$w_Disabled.' class="sti" name="w_observacao" rows="5" cols="75">'.$w_observacao.'</textarea></td>');
    ShowHTML('      <tr><td colspan=5><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    if ($O=='E') {
      ShowHTML('   <input class="stb" type="submit" name="Botao" value="Excluir">');
    } else {
      if ($O=='I') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Incluir">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Atualizar">');
      } 
    } 
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&O=P'.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false)) {
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os critérios que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    SelecaoTipoAfastamento('<u>T</u>ipo do afastamento:','T',null,$p_sq_tipo_afastamento,null,'p_sq_tipo_afastamento','AFASTAMENTO',null);
    ShowHTML('      <tr>');
    SelecaoColaborador('<u>C</u>olaborador:','C',null,$p_sq_contrato_colaborador,null,'p_sq_contrato_colaborador','AFASTAMENTO',null);
    ShowHTML('      <tr><td><b><u>P</u>eríodo de busca:</b><br> De: <input accesskey="P" type="text" name="p_inicio_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_inicio_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio_data').' a <input accesskey="P" type="text" name="p_fim_data" class="STI" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_data.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_data').'</td>');
    ShowHTML('      <tr><td align="center" colspan=5><hr>');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=I&SG='.$SG).'\';" name="Botao" value="Incluir">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&O=P&SG='.$SG).'\';" name="Botao" value="Limpar campos">');
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de busca dos colaboradores
// -------------------------------------------------------------------------
function BuscaColaborador() {
  extract($GLOBALS);
  Global $w_disabled;
  $w_nome       = upper($_REQUEST['w_nome']);
  $w_cliente    = $_REQUEST['w_cliente'];
  $w_chave      = $_REQUEST['w_chave'];
  $chaveAux     = $_REQUEST['ChaveAux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];
  $RS  = db_getGPColaborador::getInstanceOf($dbms,$w_cliente,null,$w_nome,null,null,null,null,null,null,null,null,null,null,null,null,null); 
  $RS = SortArray($RS,'nome_resumido','asc');
  //foreach ($RS as $row) {$RS = $row; break;}
  Cabecalho();
  ShowHTML('<TITLE>Seleção de colaborador</TITLE>');
  head();
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_chave) {');
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  ValidateOpen('Validacao');
  Validate('w_nome','Nome','1','1','3','60','1','1');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  AbreForm('Form',$w_dir.$w_pagina.'BuscaColaborador','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
  ShowHTML('<INPUT type="hidden" name="chaveAux" value="'.$chaveAux.'">');
  ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
  ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome do colaborador.<li>Quando a relação for exibida, selecione o colaborador desejado clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome do colaborador, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top"><b>parte do <U>n</U>ome do colaborador:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="60" value="'.$w_nome.'">');
  ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
  ShowHTML('            <input class="stb" type="button" name="Botao" value="Cancelar" onClick="window.close(); opener.focus();">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</form>');
  if ($w_nome >'') {
    ShowHTML('<tr><td align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><b>Nome resumido</td>');
      ShowHTML('            <td><b>Localização</td>');
      ShowHTML('            <td><b>Operações</td>');
      ShowHTML('          </tr>');
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('            <td>'.f($row,'nome_resumido').'</td>');
        ShowHTML('            <td>'.f($row,'local').'</td>');
        ShowHTML('            <td><a class="ss" HREF="javascript:this.status.value;" onClick="javascript:volta(\''.f($row,'sq_contrato_colaborador').'\');">Selecionar</a>');
      } 
      ShowHTML('        </table></tr>');
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
  Estrutura_Texto_Fecha();
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
  AbreSessao();
  switch ($SG) {
    case 'GPAFAST':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if (!(strpos('AI',$O)===false)) {
          $w_erro = ValidaAfastamento($w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_sq_contrato_colaborador'],$_REQUEST['w_inicio_data'],$_REQUEST['w_fim_data'],$_REQUEST['w_inicio_periodo'],$_REQUEST['w_fim_periodo'],$_REQUEST['w_dias']);
          if ($w_erro >'') {
            ShowHTML('<HR>');
            ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
            ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2>');
            ShowHTML('<font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível a conclusão da operação.');
            ShowHTML('<UL>'.$w_erro.'</UL>');
            ShowHTML('</font></td></tr></table>');
            ShowHTML('<center><B>Clique <a class="HL" href="javascript:history.back(1);">aqui</a> para voltar à tela anterior</b></center>');
            Rodape();
            exit;
          }  
        } 
        $SQL = new dml_putAfastamento; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],$w_cliente,$_REQUEST['w_sq_tipo_afastamento'],$_REQUEST['w_sq_contrato_colaborador'],
        $_REQUEST['w_inicio_data'],$_REQUEST['w_inicio_periodo'],$_REQUEST['w_fim_data'],$_REQUEST['w_fim_periodo'],
        $_REQUEST['w_dias'],$_REQUEST['w_observacao']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
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
    case 'AFASTAMENTO':        Afastamento();       break;
    case 'BUSCACOLABORADOR':   BuscaColaborador();  break;
    case 'GRAVA':              Grava();             break;
  default:
    Cabecalho();
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpen('onLoad=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
    ShowHTML('<HR>');
echo nvl($par,'NULO');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    break;
  } 
} 
?>
