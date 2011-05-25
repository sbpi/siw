<?php
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getCall.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getUserList.php');
include_once('classes/sp/db_getAddressList.php');
include_once('classes/sp/db_getSiwCliModLis.php');
include_once('classes/sp/db_getLinkData.php');
include_once('classes/sp/db_getMenuOrder.php');
include_once('classes/sp/db_getMenuLink.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getUserModule.php');
include_once('classes/sp/db_getUserVision.php');
include_once('classes/sp/db_getMenuList.php');
include_once('classes/sp/db_getCCTreeVision.php');
include_once('classes/sp/db_updatePassword.php');
include_once('classes/sp/db_getCustomerSite.php');
include_once('classes/sp/db_getUserData.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/db_putCall.php');
include_once('funcoes/selecaoLocalizacao.php');
include_once('funcoes/selecaoUnidade.php');
include_once('funcoes/selecaoModulo.php');
include_once('funcoes/selecaoEndereco.php');
include_once('funcoes/selecaoMenu.php');
include_once('funcoes/selecaoCC.php');
include_once('funcoes/selecaoPessoa.php');

// =========================================================================
//  /tarifacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia a atualização das tabelas de localização
// Mail     : alex@sbpi.com.br
// Criacao  : 10/06/2003, 15:20
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

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],1);
$P2         = $_REQUEST['P2'];
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_troca    = $_REQUEST['w_troca'];

$w_assinatura    = upper($_REQUEST['w_assinatura']);
$w_pagina        = 'tarifacao.php?par=';
$w_Disabled      = 'ENABLED';
$w_cor_fonte     = 'color="#000000';

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == ''){
  if ($P1 == 3)  $O = 'P'; 
  else           $O = 'L';
}
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão'; break;
  case 'A': $w_TP=$TP.' - Alteração'; break;
  case 'E': $w_TP=$TP.' - Exclusão'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'R': $w_TP=$TP.' - Resumo'; break;
  case 'D': $w_TP=$TP.' - Desativar'; break;
  case 'T': $w_TP=$TP.' - Ativar'; break;
  case 'H': $w_TP=$TP.' - Herança'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_sq_usuario_central=RetornaUsuarioCentral();

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de informação de ligações
// -------------------------------------------------------------------------
function Informar(){
  extract($GLOBALS);
  global $w_Disabled;

  $w_titulo              = '';
  $w_sq_ligacao          = upper($_REQUEST['w_sq_ligacao']);
  $p_sq_cc               = upper($_REQUEST['p_sq_cc']);
  $p_outra_parte_contato = upper($_REQUEST['p_outra_parte_contato']);
  $p_ativo               = upper($_REQUEST['p_ativo']);
  $p_inicio              = upper($_REQUEST['p_inicio']);
  $p_fim                 = upper($_REQUEST['p_fim']);
  $p_numero              = upper($_REQUEST['p_numero']);
  $p_ordena              = lower($_REQUEST['p_ordena']);
  $p_assunto             = upper($_REQUEST['p_assunto']);
  // Se for a tela de pesquisa do módulo gerencial, configura a busca inicial para os últimos trinta dias
  if ($O =='P' && $P1 == 3){
    if ($p_inicio==''){
      $p_inicio = FormataDataEdicao(addDays(time(),-90));
      $p_fim    = FormataDataEdicao(time());
    } 
  } 
  if ($w_troca>'' && $O!='E') {
    $w_sq_ligacao            = $_REQUEST['w_sq_ligacao'];
    $w_sq_cc                 = $_REQUEST['w_sq_cc'];
    $w_assunto               = $_REQUEST['w_assunto'];
    $w_ativo                 = $_REQUEST['w_ativo'];
    $w_imagem                = $_REQUEST['w_imagem'];
    $w_fax                   = $_REQUEST['w_fax'];
    $w_trabalho              = $_REQUEST['w_trabalho'];
    $w_outra_parte_contato   = $_REQUEST['w_outra_parte_contato'];
    $w_sq_central_telefonica = $_REQUEST['w_sq_central_telefonica'];
  } elseif ($O=='L'){
    $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_usuario,$P1,null,$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    if ($p_ordena==''){ 
      if ($P1==3) $RS = SortArray($RS,'phpdt_ordem','desc'); else $RS = SortArray($RS,'phpdt_ordem','asc'); 
    } else {
      $lista = explode(',',str_replace(' ',',',lower($_REQUEST['p_ordena'])));
      if ($P1==3) $RS = SortArray($RS,$lista[0],$lista[1],'phpdt_ordem','desc'); else $RS = SortArray($RS,$lista[0],$lista[1],'phpdt_ordem','asc'); 
    }
  } elseif ($O=='I' || $O=='A' || $O=='E'){
    // Recupera os dados da ligação
    $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,$w_sq_ligacao,$w_usuario,$P1,'REGISTRO',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    foreach($RS as $row) {
      $w_sq_cc                  = nvl(f($row,'sq_cc'),0);
      $w_assunto                = f($row,'assunto');
      $w_imagem                 = f($row,'imagem');
      $w_fax                    = f($row,'fax');
      $w_trabalho               = f($row,'trabalho');
      $w_outra_parte_contato    = f($row,'outra_parte_cont');
      $w_responsavel            = f($row,'responsavel');
      $w_sq_central_telefonica  = nvl(f($row,'sq_central_fone'),0);
    }
    if ($O=='A'){
      $w_titulo='Selecione a pessoa responsável pela ligação e informe alguma observação para orientá-lo.';
    } elseif (nvl($w_trabalho,'')=='') {
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_usuario,$P1,'HERANCA',$p_sq_cc,$p_outra_parte_contato,f($row,'numero'),$p_inicio,$p_fim,$p_ativo);
      if (count($RS) >= 0){
        foreach ($RS as $row) {
          $w_sq_cc                  = nvl(f($row,'sq_cc'),0);
          $w_assunto                = f($row,'assunto');
          $w_imagem                 = f($row,'imagem');
          $w_fax                    = f($row,'fax');
          $w_trabalho               = f($row,'trabalho');
          $w_outra_parte_contato    = f($row,'outra_parte_cont');
          $w_titulo='ATENÇÃO: Dados importados da última ligação informada! Você pode editá-los ou mantê-los como estão.<br>Não se esqueça de gravá-los para efetivar as informações.';
        }
      } 
    } 
  }
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.MontaURL('MESA').'">');
  if (!(strpos('IAEPR',$O)===false)){
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if ($O=='I') {
      ShowHTML('  if (theForm.w_trabalho[0].checked) {');
      Validate('w_sq_cc','Classificação','SELECT','1','1','18','1','1');
      Validate('w_outra_parte_contato','Pessoa de contato','1','1','3','60','1','1');
      Validate('w_assunto','Assunto','1','1','4','1000','1','1');
      ShowHTML('   }');
      ShowHTML('   else {');
      ShowHTML('      theForm.w_sq_cc.selectedIndex=0;');
      Validate('w_outra_parte_contato','Outra parte','1','','3','60','1','1');
      Validate('w_assunto','Assunto','1','','4','1000','1','1');
      ShowHTML('   }');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='A') {
      Validate('w_destino','Pessoa','HIDDEN','1','1','18','1','1');
      Validate('w_assunto','Observação','1','1','4','500','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O =='P' || $O =='R'){
      Validate('p_sq_cc','Classificação','SELECT','','1','3','1','1');
      Validate('p_outra_parte_contato','Nome da outra parte','1','','2','50','1','1');
      Validate('p_numero','Número','1','','2','20','','0123456789');
      ShowHTML('  if (theForm.p_fim.value.length > 0 && theForm.p_inicio.value.length == 0) {');
      ShowHTML('     alert(\'Não é permitido informar apenas a data final!\');');
      ShowHTML('     theForm.p_fim.focus();');
      ShowHTML('     return false;');
      ShowHTML('   }');
      if ($O =='R'){
        Validate('p_inicio','Data inicial','DATA','1','10','10','','0123456789/');
        Validate('p_fim','Data final','DATA','1','10','10','','0123456789/');
        CompData('p_inicio','Data inicial','<=','p_fim','Data final');
        ShowHTML('  var w_data, w_data1, w_data2;');
        ShowHTML('  w_data = theForm.p_inicio.value;');
        ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
        ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
        ShowHTML('  w_data = theForm.p_fim.value;');
        ShowHTML('  w_data = w_data.substr(3,2) + \'/\' + w_data.substr(0,2) + \'/\' + w_data.substr(6,4);');
        ShowHTML('  w_data2= new Date(Date.parse(w_data));');
        ShowHTML('  var MinMilli = 1000 * 60;');
        ShowHTML('  var HrMilli = MinMilli * 60;');
        ShowHTML('  var DyMilli = HrMilli * 24;');
        ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
      } else {
        Validate('p_inicio','Data inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim','Data final','DATA','','10','10','','0123456789/');
        CompData('p_inicio','Data inicial','<=','p_fim','Data final');
      } 
      if ($P1==3){
        // Se for arquivo
        ShowHTML('  if (theForm.p_sq_cc.selectedIndex == 0 && theForm.p_outra_parte_contato.value == \'\' && theForm.p_numero.value == \'\' && theForm.p_fim.value == \'\' && theForm.p_inicio.value == \'\') {');
        ShowHTML('     alert("É necessário informar um critério de filtragem!");');
        ShowHTML('     return false;');
        ShowHTML('   }');
      } 
      Validate('P4','Linhas por página','1','1','1','4','','0123456789');
    } 
    if ($O=='E' || $O=='R'){
      ShowHTML('  theForm.Botao.disabled=true;');
    } else {
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  if ($w_troca>''){
    BodyOpen('onload=document.Form.'.$w_troca.'.focus();');
  } elseif ($O=='I'){
    BodyOpen('onload=document.Form.w_assinatura.focus();');
  } elseif ($O=='A'){
    BodyOpen('onload=document.Form.w_destino.focus();');
  } elseif (!(strpos('PR',$O)===false)){
    BodyOpen('onload=document.Form.p_sq_cc.focus();');
  } else {
    BodyOpen('onload=this.focus();');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L'){
    ShowHTML('<tr><td>');
    if ($P1!=3){
      // Se não for inclusão
      ShowHTML('      <tr><td><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_sq_cc.$p_outra_parte_contato.$p_ativo.$p_numero.$p_inicio.$p_fim.$p_ordena>''){
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</font></a>');
    } 
    if ($P1!=3){    
      ShowHTML('                         <a accesskey="R" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=R&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>R</u>esumo</a>&nbsp;');
    } 
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','tipo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Data','phpdt_ordem').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Número','numero').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Duração','duracao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('RM','sq_ramal').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Local','localidade').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Trab','d_trabalho').'</td>');
    if ($P1==3){
      // Se for arquivo
      ShowHTML('          <td><b>'.LinkOrdena('Resp.','responsavel').'</td>');
    } 
    ShowHTML('          <td><b>'.LinkOrdena('De','d_nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Classificação','d_cc').'</td>');
    if (nvl($p_assunto,'N')=='S'){
      // Se for selecionada a visualização do assunto
      ShowHTML('          <td><b>'.LinkOrdena('Assunto','assunto').'</td>');
    } 
    ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=12 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_cor_fonte='color="#000000"';
        if (nvl(f($row,'trabalho'),'')=='' && f($row,'sq_usuario_central')>''){
          $w_negrito='<b>';
        if (nvl(f($row,'sq_usuario_central'),0)!=nvl($w_sq_usuario_central,0)) {
            $w_cor_fonte='color="#0011FF"';
          }
        } else {
          $w_negrito='';
        } 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.f($row,'tipo').'</td>');
        ShowHTML('      <td nowrap align="center"><font '.$w_cor_fonte.'>'.$w_negrito.FormataDataEdicao(f($row,'phpdt_ordem'),3).'</td>');
        ShowHTML('      <td><font '.$w_cor_fonte.'>'.f($row,'numero').'</td>');
        ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.FormataTempo(f($row,'duracao')).'&nbsp;</td>');
        ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.f($row,'sq_ramal').'</td>');
        ShowHTML('      <td><font '.$w_cor_fonte.'>'.f($row,'localidade').'</td>');
        ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.f($row,'d_trabalho').'</td>');
        if ($P1==3){
          // Se for arquivo
          ShowHTML('        <td><font '.$w_cor_fonte.'>'.nvl(f($row,'responsavel'),'---').'</td>');
        } 
        ShowHTML('        <td><font '.$w_cor_fonte.'>'.f($row,'d_nome').'</td>');
        ShowHTML('        <td><font '.$w_cor_fonte.'>'.f($row,'d_cc').'</td>');
        if (nvl($p_assunto,'N')=='S'){
          if (nvl(f($row,'trabalho'),'N')=='S'){
            ShowHTML('        <td><font '.$w_cor_fonte.'>'.nvl(f($row,'assunto'),'---').'</td>');
          } else {
            ShowHTML('        <td><font '.$w_cor_fonte.'>*** Privativo</td>');
          } 
        }  
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if ($P1==3 && nvl(f($row,'trabalho'),'N')=='N'){
          ShowHTML('          ---&nbsp');
        } elseif (f($row,'trabalho')>'') {
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_ligacao='.f($row,'sq_ligacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Exibir</A>&nbsp');
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_ligacao='.f($row,'sq_ligacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Informar</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_ligacao='.f($row,'sq_ligacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Transferir</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_soma=$w_soma+f($row,'duracao');
      } 
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'">');
      ShowHTML('        <td align="right" colspan=3><b>Duração total:</td>');
      ShowHTML('        <td align="center"><b>'.FormataTempo($w_soma).'&nbsp;</td>');
      if (nvl($p_assunto,'N')=='N'){
        ShowHTML('        <td colspan=7>&nbsp;</td>');
      } else {
        ShowHTML('        <td colspan=8>&nbsp;</td>');
      } 
      ShowHTML('      </tr>');
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($R>''){
      MontaBarra($w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } else {
      MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E') $w_Disabled='DISABLED';
    $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,$w_sq_ligacao,$w_usuario,$P1,'DADOS',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    foreach ($RS as $row) {
      ShowHTML('<tr><td align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
      ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr valign="top"><td colspan=3>Tipo da ligação: <b>'.f($row,'tipo').'</td></tr>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td>Nº:<br><b>'.f($row,'numero').'</td>');
      ShowHTML('          <td>Data:<br> <b>'.FormataDataEdicao(f($row,'phpdt_ordem'),4).'</td>');
      ShowHTML('          <td align="right">Duração:<br><b>'.FormataTempo(f($row,'duracao')).'</td>');
      ShowHTML('        <tr valign="top">');
      ShowHTML('          <td>Ramal:<br><b>'.f($row,'sq_ramal').'</td>');
      ShowHTML('          <td>Tronco:<br> <b>'.f($row,'sq_tronco').'</td>');
      ShowHTML('          <td align="right">Valor:<br><b>'.number_format(f($row,'valor'),2,',','.').'</td>');
      ShowHTML('    </TABLE>');
      // Verifica se houve transferências da ligação, exibindo-as se existirem
      $SQL = new db_getCall; $RS2 = $SQL->getInstanceOf($dbms,$w_cliente,$w_sq_ligacao,$w_usuario,$P1,'LOG',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      if (count($RS2)>0) {
        ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr align="center"><td colspan=4><b>Transferências da ligação</td>');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>Data</td>');
        ShowHTML('          <td><b>Origem</td>');
        ShowHTML('          <td><b>Destino</td>');
        ShowHTML('          <td><b>Observação</td>');
        foreach($RS2 as $row2) {
          ShowHTML('        <tr valign="top">');
          ShowHTML('          <td  align="center" nowrap> '.FormataDataEdicao(f($row2,'phpdt_ordem'),3).'</td>');
          ShowHTML('          <td>'.f($row2,'origem').'</td>');
          ShowHTML('          <td>'.f($row2,'destino').'</td>');
          ShowHTML('          <td>'.f($row2,'observacao').'</td>');
        } 
      }
      ShowHTML('    </TABLE>');
      ShowHTML('</table>');
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
      ShowHTML(MontaFiltro('POST'));
      ShowHTML('<INPUT type="hidden" name="w_sq_ligacao" value="'.$w_sq_ligacao.'">');
      ShowHTML('<INPUT type="hidden" name="w_sq_central_telefonica" value="'.$w_sq_central_telefonica.'">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <table width="95%" border="0">');
      ShowHTML('      <tr><td align="center"><font color="#FF0000"><b>'.$w_titulo.'</b></td></tr>');
      if ($O=='A'){
        // Se for transferência de ligação
        ShowHTML('      <tr>');
        SelecaoPessoa('Pe<u>s</u>soa:','S','Selecione a pessoa na relação.',$w_destino,$w_sq_central_telefonica,'w_destino','TTTRANSFERE');
        ShowHTML('      </tr>');
        ShowHTML('      <tr><td colspan=3><b><U>O</U>bservação:<br><TEXTAREA ACCESSKEY="O" '.$w_Disabled.' class="sti" name="w_assunto" rows="5" cols=75>'.$w_assunto.'</textarea></td>');
      } else {
        // Outras operações
        ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
        MontaRadioNS('<b>Ligação a trabalho?</b>',$w_trabalho,'w_trabalho');
        // Recupera as 3 últimas ligações para o número, independente do usuário logado.
        $w_texto='<b>Últimos registros para este número</b>:<br>'.chr(13).
                 '<table border=1>'.chr(13).
                 '<tr align="center">'.chr(13).
                 '  <td><b>Data'.chr(13).
                 '  <td><b>Responsável'.chr(13).
                 '  <td><b>Contato'.chr(13).
                 '  <td><b>Tipo'.chr(13);
        $SQL = new db_getCall; $RS2 = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_usuario,$P1,'HINT',null,null,f($row,'numero'),null,null,null);
        $RS2 = SortArray($RS2,'phpdt_ordem','desc');
        $l_count = 0;
        if (count($RS2)==0){
          $w_texto .= '<tr><td colspan=4>Não foram encontrados registros.';
        } else {
          foreach($RS2 as $row2) {  
            if ((strpos($w_texto,f($row2,'d_nome'))===false) && nvl(f($row2,'d_nome'),'nulo')!='nulo'){
              $l_count += 1;
              $w_texto .= '<tr valign=top>';
              $w_texto .= '  <td>'.FormataDataEdicao(f($row2,'phpdt_ordem'),3);
              $w_texto .= '  <td>'.f($row2,'responsavel');
              $w_texto .= '  <td>'.f($row2,'d_nome');
              $w_texto .= '  <td>'.f($row2,'tipo').chr(13);
            }
            if ($l_count > 2) break;
          } 
        } 
        $w_texto=$w_texto.'</table>';
        MontaRadioNS('<b>Fax?</b>',$w_fax,'w_fax');
        ShowHTML('          <td><b>A<U>r</U>quivo:<br><INPUT ACCESSKEY="R" '.$w_Disabled.' class="sti" type="file" name="w_imagem" size="30" maxlength="80"></td>');
        ShowHTML('      </tr></table></td></tr>');
        ShowHTML('      <tr>');
        SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classificação à qual a ligação está vinculada.',$w_sq_cc,$w_sq_central_telefonica,'w_sq_cc','TTCENTRAL');
        ShowHTML('      </tr>');
        if ($w_responsavel>''){
          ShowHTML('      <tr><td><b>Responsável pela ligação:<br>'.$w_responsavel.'</td>');
        } 
        ShowHTML('      <tr><td><b><U>P</U>essoa de contato:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="sti" type="text" name="w_outra_parte_contato" size="60" maxlength="60" value="'.$w_outra_parte_contato.'" '.$w_Disabled.'></td>');
        ShowHTML('      <tr><td>'.str_replace(chr(13).chr(10),'<BR>',$w_texto));
        ShowHTML('      <tr><td><b>Assu<U>n</U>to:<br><TEXTAREA ACCESSKEY="N" '.$w_Disabled.' class="sti" name="w_assunto" rows="5" cols=75>'.$w_assunto.'</textarea></td>');
      } 
      if ($O!='E'){
        ShowHTML('      <tr><td valign="top"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
      ShowHTML('      <tr><td align="center" colspan="3">');
      if ($O=='E'){
        ShowHTML('            <input class="stb" type="button" name="Botao" value="Voltar" onClick="document.Form.action=\''.$R.'\'; document.Form.O.value=\'L\'; document.Form.submit();">');
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
        ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'&O=L\';" name="Botao" value="Cancelar">');
      }
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      ShowHTML('</FORM>');
      break;
    }
  } elseif ($O=='P') {
    ShowHTML('<FORM action="'.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="1">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R"  value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="O"  value="L">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i> Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classificação desejada.',$p_sq_cc,$w_sq_usuario_central,'p_sq_cc','TTUSUARIO');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_outra_parte_contato" size="40" maxlength="40" value="'.$p_outra_parte_contato.'"></td>');
    ShowHTML('          <td valign="top"><b>N<U>ú</U>mero:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="p_numero" size="20" maxlength="20" value="'.$p_numero.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr align="left"><td><table cellpadding=0 cellspacing=0><tr valign="center">');
    ShowHTML('          <td><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>');
    ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_inicio" size="10" maxlength="10" value="'.$p_inicio.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
    ShowHTML('          <td><b>A<U>t</U>é: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_fim" size="10" maxlength="10" value="'.$p_fim.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td valign="top"><b>Ligações:</b><br>');
    if ($p_ativo=='S'){                      
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S" checked> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> Não informadas');
    } elseif ($p_ativo=='N'){
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N" checked> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> Não informadas');
    } elseif ($p_ativo=='A' || ($p_ativo=='' && $P1==3)){
      // Se for arquivo, seleciona ambas como valor inicial
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A" checked> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> Não informadas');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value="" checked> Não informadas');
    } 
    if (nvl($p_assunto,'N')=='N' && nvl($P1,3)!=3){
      ShowHTML('      <tr><td><input '.$w_Disabled.' type="checkbox" name="p_assunto" value="S"> Exibir o assunto das ligações a trabalho</td></tr>');
    } else{
      ShowHTML('      <tr><td><input '.$w_Disabled.' type="checkbox" name="p_assunto" value="S" checked> Exibir o assunto das ligações a trabalho</td></tr>');
    } 
    ShowHTML('      <tr><td><table cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    ShowHTML('          <td><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_ordena=='D_CC') {
      ShowHTML('          <option value="D_CC" SELECTED>Classificação<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">Número<option value="SQ_RAMAL">Ramal');
    } elseif ($p_ordena=='LOCALIDADE'){
      ShowHTML('          <option value="D_CC">Classificação<option value="">Data<option value="LOCALIDADE" SELECTED>Local<option value="d_nome">Nome<option value="numero">Número<option value="SQ_RAMAL">Ramal');
    } elseif ($p_ordena=='OUTRA_PARTE_CONT'){
      ShowHTML('          <option value="D_CC">Classificação<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome" SELECTED>Nome<option value="numero">Número<option value="SQ_RAMAL">Ramal');
    } elseif ($p_ordena=='NUMERO'){
      ShowHTML('          <option value="D_CC">Classificação<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero" SELECTED>Número<option value="SQ_RAMAL">Ramal');
    } elseif ($p_ordena=='SQ_RAMAL'){
      ShowHTML('          <option value="D_CC">Classificação<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">Número<option value="SQ_RAMAL" SELECTED>Ramal');
    } else {
      ShowHTML('          <option value="D_CC">Classificação<option value="" SELECTED>Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">Número<option value="SQ_RAMAL">Ramal');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('R',$O)===false))  {
    ShowHTML('<FORM action="'.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Exibir resumo</i>. Clicando sobre o botão <i>Voltar a informar</i>, o filtro existente será apagado e será exibida a tela com as ligações a informar.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classificação desejada.',$p_sq_cc,$w_sq_usuario_central,'p_sq_cc','TTUSUARIO');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="p_outra_parte_contato" size="40" maxlength="40" value="'.$p_outra_parte_contato.'"></td>');
    ShowHTML('          <td valign="top"><b>N<U>ú</U>mero:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="sti" type="text" name="p_numero" size="20" maxlength="20" value="'.$p_numero.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr align="left"><td><table cellpadding=0 cellspacing=0><tr valign="center">');
    ShowHTML('          <td><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>');
    if ($p_inicio==''){
      ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_inicio" size="10" maxlength="10" value="01/'.Date('m/Y', Time()).'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
      ShowHTML('          <td><b>A<U>t</U>é: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_fim" size="10" maxlength="10" value="'.FormataDataEdicao(time()).'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    } else {
      ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_inicio" size="10" maxlength="10" value="'.$p_inicio.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
      ShowHTML('          <td><b>A<U>t</U>é: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_fim" size="10" maxlength="10" value="'.$p_fim.'" onKeyDown="FormataData(this,event)" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim').'</td>');
    } 
    ShowHTML('      </table>');
    if ($P1==3){
      // Se for arquivo
      ShowHTML('      <tr><td valign="top"><b>Ligações: apenas a trabalho');
      ShowHTML('<INPUT type="hidden" name="p_ativo" value="S">');
    } else {
      ShowHTML('      <tr><td valign="top"><b>Ligações:</b><br>');
      if ($p_ativo=='S'){
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S" checked> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas');
      } elseif ($p_ativo=='N'){
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N" checked> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas');
      } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A" checked> Ambas');
      } 
    } 
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Exibir resumo">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\';" name="Botao" value="Voltar a informar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    if (nvl($p_inicio,'nulo')!='nulo') {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><hr>');
      if ($P1!=3){ 
            // Se não for arquivo
        $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_usuario,$P1,'PESSOAS',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
        $RS = SortArray($RS,'dura_tot','desc');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><b>Resumo comparativo por ligações particulares</b>&nbsp;&nbsp;&nbsp;');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
        ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td rowspan=2><b>Pessoa</td>');
        ShowHTML('          <td colspan=4><b>Quantidade</td>');
        ShowHTML('          <td colspan=4><b>Duração</td>');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>ORI</td>');
        ShowHTML('          <td><b>REC</td>');
        ShowHTML('          <td><b>NAT</td>');
        ShowHTML('          <td><b>TOT</td>');
        ShowHTML('          <td><b>ORI</td>');
        ShowHTML('          <td><b>REC</td>');
        ShowHTML('          <td><b>NAT</td>');
        ShowHTML('          <td><b>TOT</td>');
        if (count($RS)<=0){
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
        } else {
          $w_cor=$conTrAlternateBgColor;
          foreach($RS as $row) {
            if (f($row,'trabalho')=='Particular') {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              ShowHTML('      <tr bgcolor="'.$w_cor.'">');
              ShowHTML('        <td>'.f($row,'nome_resumido').'</td>');
              ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
              ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
              ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
              ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
              ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
              ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
              ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
              ShowHTML('        <td align="right">'.FormataTempo(f($row,'dura_tot')).'&nbsp;</td>');
              ShowHTML('        </td>');
              ShowHTML('      </tr>');
            }    
          } 
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><br></td></tr>');
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_sq_usuario_central,$P1,'GERAL',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><b>Resumo geral</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">[Exibir ligações]</a>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td rowspan=2><b>Tipo</td>');
      ShowHTML('          <td colspan=4><b>Quantidade</td>');
      ShowHTML('          <td colspan=4><b>Duração</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      if (count($RS)<=0){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'">');
          ShowHTML('        <td><font '.$w_cor_fonte.'">'.f($row,'trabalho').'</td>');
          ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
          ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
          ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
          ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
          ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
          ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
          ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
          ShowHTML('        <td align="right">'.FormataTempo(nvl(f($row,'dura_tot'),0)).'&nbsp;</td>');
          ShowHTML('        </td>');
          ShowHTML('      </tr>');
        }  
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_sq_usuario_central,$P1,'CTCC',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><br><b>Resumo por Classificação</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">[Exibir ligações]</a>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td rowspan=2><b>Classificação</td>');
      ShowHTML('          <td colspan=4><b>Quantidade</td>');
      ShowHTML('          <td colspan=4><b>Duração</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      if (count($RS)<=0){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          if (f($row,'trabalho')=='Total') {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td>'.f($row,'sigla').'</td>');
            ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'dura_tot')).'&nbsp;</td>');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }  
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_sq_usuario_central,$P1,'MES',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><br><b>Resumo por mês</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">[Exibir ligações]</a>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td rowspan=2><b>Mês/Ano</td>');
      ShowHTML('          <td colspan=4><b>Quantidade</td>');
      ShowHTML('          <td colspan=4><b>Duração</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      if (count($RS)<=0){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          if (f($row,'trabalho')=='Total') {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td align="center">'.substr(f($row,'mes'),4,2).'/'.substr(f($row,'mes'),0,4).'</td>');
            ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'dura_tot')).'&nbsp;</td>');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_sq_usuario_central,$P1,'DIASEMANA',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><br><b>Resumo por dia da semana</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">[Exibir ligações]</a>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td rowspan=2><b>Dia</td>');
      ShowHTML('          <td colspan=4><b>Quantidade</td>');
      ShowHTML('          <td colspan=4><b>Duração</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      if (count($RS)<=0){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          if (f($row,'trabalho')=='Total') {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td align="center">'.f($row,'dia').'</td>');
            ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'dura_tot')).'&nbsp;</td>');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }  
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      $SQL = new db_getCall; $RS = $SQL->getInstanceOf($dbms,$w_cliente,null,$w_sq_usuario_central,$P1,'DIAMES',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><br><br><b>Resumo por dia do mês</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">[Exibir ligações]</a>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td rowspan=2><b>Dia</td>');
      ShowHTML('          <td colspan=4><b>Quantidade</td>');
      ShowHTML('          <td colspan=4><b>Duração</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      ShowHTML('          <td><b>ORI</td>');
      ShowHTML('          <td><b>REC</td>');
      ShowHTML('          <td><b>NAT</td>');
      ShowHTML('          <td><b>TOT</td>');
      if (count($RS)<=0){
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          if (f($row,'trabalho')=='Total') {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td align="center">'.f($row,'mes').'</td>');
            ShowHTML('        <td align="right">'.f($row,'ori_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'rec_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'nat_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($row,'qtd_tot').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'ori_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'rec_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'nat_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($row,'dura_tot')).'&nbsp;</td>');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          }     
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('     alert(\'Opção não disponível!\');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
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
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'LIGACAO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new db_putCall; $SQL->getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_ligacao'],$_REQUEST['w_destino'], $_REQUEST['w_sq_cc'], $_REQUEST['w_outra_parte_contato'],
            $_REQUEST['w_assunto'], $w_usuario, $_REQUEST['w_fax'], $_REQUEST['w_trabalho']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        retornaFormulario('w_assinatura');
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
function Main(){
  extract($GLOBALS);
  switch ($par){
    case 'INFORMAR': Informar();  break;
    case 'GRAVA'   : Grava();     break;
    default:
    Cabecalho();
    BodyOpen('onload=this.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
  } 
} 
?>