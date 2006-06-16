<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getMenuCode.php');
include_once('classes/sp/db_getMenuData.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getUserList.php');
include_once('classes/sp/db_getAddressList.php');
include_once('classes/sp/db_getSiwCliModLis.php');
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
include_once('classes/sp/dml_SiwMenu.php');
include_once('classes/sp/dml_putSgPesMod.php');
include_once('classes/sp/dml_putSiwPesCC.php');
include_once('funcoes/selecaoLocalizacao.php');
include_once('funcoes/selecaoUnidade.php');
include_once('funcoes/selecaoEstado.php');
include_once('funcoes/selecaoModulo.php');
include_once('funcoes/selecaoEndereco.php');
include_once('funcoes/selecaoServico.php');
include_once('funcoes/selecaoMenu.php');

session_register("p_cliente_session");
session_register("sq_pessoa_session");
session_register("ano_session");
session_register("siw_email_conta_session");
session_register("siw_email_nome_session");
session_register("siw_email_senha_session");
session_register("smtp_server_session");
session_register("dbms_session");
session_register("schema_session");
session_register("schema_is_session");
session_register("LogOn_session");
session_register("Username_session");

?>
<? // asp2php (vbscript) converted
?>
<? // Option $Explicit; ?>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.php" -->
<!-- #INCLUDE FILE="Funcoes.php" -->
<!-- #INCLUDE FILE="DB_Geral.php" -->
<!-- #INCLUDE FILE="DB_Tarifacao.php" -->
<? 
// =========================================================================
//  /Tarifacao.asp
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia a atualiza��o das tabelas de localiza��o
// Mail     : alex@sbpi.com.br
// Criacao  : 10/06/2003, 15:20
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
$par        = strtoupper($REQUEST['Par']);
$P1         = Nvl($REQUEST['P1'],1);
$P2         = $REQUEST['P'];
$P3         = $cDbl[Nvl($REQUEST['P3'],1)];
$P4         = $cDbl[Nvl($REQUEST['P4'],$conPagesize)];
$TP         = $REQUEST['TP'];
$SG         = strtoupper($REQUEST['SG']);
$R          = strtoupper($REQUEST['R']);
$O          = strtoupper($REQUEST['O']);
$w_troca    = $REQUEST['w_troca'];

$w_Assinatura    = strtoupper($REQUEST['w_Assinatura']);
$w_pagina        = 'Tarifacao.asp?par=';
$w_Disabled      = 'ENABLED';
$w_cor_fonte     = 'color="#000000';

if ($O == '') {
  if ($P1 == 3) $O = 'P'; else   $O='L';
}

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o'; break;
  case 'A': $w_TP=$TP.' - Altera��o'; break;
  case 'E': $w_TP=$TP.' - Exclus�o'; break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  case 'R': $w_TP=$TP.' - Resumo'; break;
  case 'D': $w_TP=$TP.' - Desativar'; break;
  case 'T': $w_TP=$TP.' - Ativar'; break;
  case 'H': $w_TP=$TP.' - Heran�a'; break;
  default : $w_TP=$TP.' - Listagem'; 
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_sq_usuario_central=RetornaUsuarioCentral();

Main();

FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de informa��o de liga��es
// -------------------------------------------------------------------------
function Informar(){
  extract($GLOBALS);
  global $w_Disabled;

  $w_titulo              = '';
  $w_sq_ligacao          = strtoupper('w_sq_ligacao');
  $p_sq_cc               = strtoupper($REQUEST['p_sq_cc']);
  $p_outra_parte_contato = strtoupper($REQUEST['p_outra_parte_contato']);
  $p_ativo               = strtoupper($REQUEST['p_ativo']);
  $p_inicio              = strtoupper($REQUEST['p_inicio']);
  $p_fim                 = strtoupper($REQUEST['p_fim']);
  $p_numero              = strtoupper($REQUEST['p_numero']);
  $p_ordena              = strtoupper($REQUEST['p_ordena']);
  $p_assunto             = strtoupper($REQUEST['p_assunto']);
  // Se for a tela de pesquisa do m�dulo gerencial, configura a busca inicial para os �ltimos trinta dias
  if ($O =='P' && $P1 == 3){
    if ($p_inicio==''){
      $p_inicio=FormataDataEdicao(time()-30);
      $p_fim=FormataDataEdicao(time());
    } 
  } 
  if ($w_troca>'') {
    $w_sq_ligacao=$REQUEST['w_sq_ligacao'];
    $w_sq_cc=$REQUEST['w_sq_cc'];
    $w_assunto=$REQUEST['w_assunto'];
    $w_ativo=$REQUEST['w_ativo'];
    $w_imagem=$REQUEST['w_imagem'];
    $w_fax=$REQUEST['w_fax'];
    $w_trabalho=$REQUEST['w_trabalho'];
    $w_outra_parte_contato=$REQUEST['w_outra_parte_contato'];
  } elseif ($O=='L'){
    db_GetCall($RS,null,$w_usuario,$P1,null,$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    if ($p_Ordena==''){ 
      if ($P1==3)$RS->Sort='ordem desc';              else $RS->Sort='ordem';
    } else {
      if ($P1==3) $RS->Sort=$p_ordena.', ordem desc'; else $RS->Sort=$p_ordena.', ordem';
    }
  } else {
    if ($O=='I' || $O=='A' || $O=='E'){
      // Recupera os dados da liga��o
      db_GetCall($RS,$w_sq_ligacao,$w_usuario,$P1,'REGISTRO',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
      $w_sq_cc=$cDbl[Nvl($RS['sq_cc'],0)];
      $w_assunto=$RS['assunto'];
      $w_imagem=$RS['imagem'];
      $w_fax=$RS['fax'];
      $w_trabalho=$RS['trabalho'];
      $w_outra_parte_contato=$RS['outra_parte_cont'];
      $w_responsavel=$RS['responsavel'];
      $w_sq_central_telefonica=$cDbl[Nvl($RS['SQ_CENTRAL_FONE'],0)];
      if ($O=='A'){
        $w_titulo='Selecione a pessoa respons�vel pela liga��o e informe alguma observa��o para orient�-lo.';
      } elseif (!isset($w_trabalho)) {
        db_GetCall($RS,null,$w_usuario,$P1,'HERANCA',$p_sq_cc,$p_outra_parte_contato,$RS['numero'],$p_inicio,$p_fim,$p_ativo);
        if (!$RS->EOF){
          $w_sq_cc=$cDbl[Nvl($RS['sq_cc'],0)];
          $w_assunto=$RS['assunto'];
          $w_imagem=$RS['imagem'];
          $w_fax=$RS['fax'];
          $w_trabalho=$RS['trabalho'];
          $w_outra_parte_contato=$RS['outra_parte_cont'];
          $w_titulo='ATEN��O: Dados importados da �ltima liga��o informada! Voc� pode edit�-los ou mant�-los como est�o.<br>N�o se esque�a de grav�-los para efetivar as informa��es.';
        } 
      } 
    } 
  }
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<meta http-equiv="Refresh" content="300; URL='.MontaURL('MESA').'">');
  if (!(strpos('IAEPR',$O)===false)){
    ScriptOpen('JavaScript');
    CheckBranco();
    FormataData();
    ValidateOpen('Validacao');
    if ((strpos('I',$O) ? strpos('I',$O)+1 : 0)>0){
      ShowHTML('  if (theForm.w_trabalho[0].checked) {');
      Validate('w_sq_cc','Classifica��o','SELECT','1','1','18','1','1');
      Validate('w_outra_parte_contato','Pessoa de contato','1','1','3','60','1','1');
      Validate('w_assunto','Assunto','1','1','4','1000','1','1');
      ShowHTML('   }');
      ShowHTML('   else {');
      ShowHTML('      theForm.w_sq_cc.selectedIndex=0;');
      Validate('w_outra_parte_contato','Outra parte','1','','3','60','1','1');
      Validate('w_assunto','Assunto','1','','4','1000','1','1');
      ShowHTML('   }');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ((strpos('A',$O) ? strpos('A',$O)+1 : 0)>0) {
      Validate('w_destino','Pessoa','HIDDEN','1','1','18','1','1');
      Validate('w_assunto','Observa��o','1','1','4','500','1','1');
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } elseif ($O =='P' || $O =='R'){
      Validate('p_sq_cc','Classifica��o','SELECT','','1','3','1','1');
      Validate('p_outra_parte_contato','Nome da outra parte','1','','2','50','1','1');
      Validate('p_numero','N�mero','1','','2','20','','0123456789');
      ShowHTML('  if (theForm.p_fim.value.length > 0 && theForm.p_inicio.value.length == 0) {');
      ShowHTML('     alert(\'N�o � permitido informar apenas a data final!\');');
      ShowHTML('     theForm.p_fim.focus();');
      ShowHTML('     return false;');
      ShowHTML('   }');
      if ($O =='R'){
        Validate('p_inicio','Data inicial','DATA','1','10','10','','0123456789/');
        Validate('p_fim','Data final','DATA','1','10','10','','0123456789/');
        CompData('p_inicio','Data inicial','<=','p_fim','Data final');
        ShowHTML('  var w_data, w_data1, w_data2;');
        ShowHTML('  w_data = theForm.p_inicio.value;');
        ShowHTML('  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);');
        ShowHTML('  w_data1  = new Date(Date.parse(w_data));');
        ShowHTML('  w_data = theForm.p_fim.value;');
        ShowHTML('  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);');
        ShowHTML('  w_data2= new Date(Date.parse(w_data));');
        ShowHTML('  var MinMilli = 1000 * 60;');
        ShowHTML('  var HrMilli = MinMilli * 60;');
        ShowHTML('  var DyMilli = HrMilli * 24;');
        ShowHTML('  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));');
        //ShowHTML '  if (Days > 60) {'
        //ShowHTML '     ("'('O intervalo n�o pode ser superior a 60 dias!');'
        //ShowHTML '     theForm.p_inicio.focus();'
        //ShowHTML '     return false;'
        //ShowHTML '  }'
      } else {
        Validate('p_inicio','Data inicial','DATA','','10','10','','0123456789/');
        Validate('p_fim','Data final','DATA','','10','10','','0123456789/');
        CompData('p_inicio','Data inicial','<=','p_fim','Data final');
      } 
      if ($P1==3){
        // Se for arquivo
        ShowHTML('  if (theForm.p_sq_cc.selectedIndex == 0 && theForm.p_outra_parte_contato.value == \'\' && theForm.p_numero.value == \'\' && theForm.p_fim.value == \'\' && theForm.p_inicio.value == \'\') {');
        ShowHTML('     alert("� necess�rio informar um crit�rio de filtragem!");');
        ShowHTML('     return false;');
        ShowHTML('   }');
      } 
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
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
 
  if ($w_troca>'')                                       BodyOpen('onload=document.Form.'.$w_troca.'.focus();');
  elseif ((strpos('A',$O) ? strpos('A',$O)+1 : 0)>0)     BodyOpen('onload=document.Form.w_destino.focus();');
  elseif ((strpos('PR',$O) ? strpos('PR',$O)+1 : 0)>0)   BodyOpen('onload=document.Form.p_sq_cc.focus();');
  else                                                   BodyOpen('onload=document.focus();');

  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L'){
    ShowHTML('<tr><td>');
    if ($P1!=3){
      // Se n�o for inclus�o
      ShowHTML('      <tr><td><font size="2"><a accesskey="I" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
    } 
    if ($p_sq_cc.$p_outra_parte_contato.$p_ativo.$p_numero.$p_inicio.$p_fim.$p_Ordena>''){
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
    } else {
      ShowHTML('                         <a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</font></a>');
    } 
    if ($P1!=3){
      // Se n�o for inclus�o
      ShowHTML('                         <a accesskey="R" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=R&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>R</u>esumo</a>&nbsp;');
    } 
    ShowHTML('    <td align="right"><b>Registros: '.$RS->Count($Rs));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','tipo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Data','data').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('N�mero','numero').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Dura��o','duracao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('RM','sq_ramal').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Local','localidade').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Trab','d_trabalho').'</td>');
    if ($P1==3){
      // Se for arquivo
      ShowHTML('          <td><b>'.LinkOrdena('Resp.','responsavel').'</td>');
    } 
    ShowHTML('          <td><b>'.LinkOrdena('De','d_nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Classifica��o','d_cc').'</td>');
    if (Nvl($p_assunto,'N')=='S'){
      // Se for selecionada a visualiza��o do assunto
      ShowHTML('          <td><b>'.LinkOrdena('Assunto','assunto').'</td>');
    } 
    ShowHTML('          <td><b>Opera��es</td>');
    ShowHTML('        </tr>');
    if ($RS->EOF) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=12 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $rs->PageSize=$P4;
      $rs->AbsolutePage=$P3;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_cor_fonte='color="#000000"';
        if (!isset($RS['trabalho']) && ($RS['sq_usuario_central'])>''){
          $w_negrito='<b>';
          if ($cDbl[Nvl($RS['sq_usuario_central'],0)]!=$cDbl[Nvl($w_sq_usuario_central,0)]) {
            $w_cor_fonte='color="#0011FF"';
          }
        } else {
        $w_negrito='';
      } 
      ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
      ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.$RS['tipo'].'</td>');
      ShowHTML('      <td nowrap align="center"><font '.$w_cor_fonte.'>'.$w_negrito.$RS['data'].'</td>');
      ShowHTML('      <td><font '.$w_cor_fonte.'>'.$RS['numero'].'</td>');
      ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.FormataTempo($cDbl[$RS['duracao']]).'&nbsp;</td>');
      ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.$RS['sq_ramal'].'</td>');
      ShowHTML('      <td><font '.$w_cor_fonte.'>'.$RS['localidade'].'</td>');
      ShowHTML('      <td align="center"><font '.$w_cor_fonte.'>'.$RS['d_trabalho'].'</td>');
        if ($P1==3){
          // Se for arquivo
          ShowHTML('        <td><font '.$w_cor_fonte.'>'.nvl($RS['responsavel'],'---').'</td>');
        } 
        ShowHTML('        <td><font '.$w_cor_fonte.'>'.$RS['d_nome'].'</td>');
        ShowHTML('        <td><font '.$w_cor_fonte.'>'.$RS['d_cc'].'</td>');
        if (Nvl($p_assunto,'N')=='S'){
          if (Nvl($RS['trabalho'],'N')=='S'){
            ShowHTML('        <td><font '.$w_cor_fonte.'>'.nvl($RS['assunto'],'---').'</td>');
           } else {
            ShowHTML('        <td><font '.$w_cor_fonte.'>*** Privativo</td>');
          } 
        }  
        ShowHTML('        <td align="top" nowrap>');
        if ($P1==3 && Nvl($RS['trabalho'],'N')=='N'){
          ShowHTML('          ---&nbsp');
        } elseif ($RS['trabalho']>'') {
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_sq_ligacao='.$RS['sq_ligacao'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">Exibir</A>&nbsp');
        } else {
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_sq_ligacao='.$RS['sq_ligacao'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">Informar</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_sq_ligacao='.$RS['sq_ligacao'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">Transferir</A>&nbsp');
        } 
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
        $w_soma=$w_soma+intval($cDbl[$RS['duracao']]);
      } 
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$w_cor.'">');
      ShowHTML('        <td align="right" colspan=3><b>Dura��o total:</td>');
      ShowHTML('        <td align="center"><b>'.FormataTempo($w_soma).'&nbsp;</td>');
      if (Nvl($p_assunto,'N')=='N'){
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
      MontaBarra($w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,$RS->PageCount,$P3,$P4,$RS->Count($Rs));
    } else {
      MontaBarra($w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG,$RS->PageCount,$P3,$P4,$RS->Count($Rs));
    } 
    ShowHTML('</tr>');
  } elseif (!(strpos('IAE',$O)===false)) {
    if ($O=='E')$w_Disabled='DISABLED';
    db_GetCall($RS,$w_sq_ligacao,$w_usuario,$P1,'DADOS',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr><td align="center" bgcolor="#FAEBD7"><table border=1 width="100%"><tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr valign="top"><td colspan=3>Tipo da liga��o: <b>'.$RS['tipo'].'</td></tr>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>N�:<br><b>'.$RS['numero'].'</td>');
    ShowHTML('          <td>Data:<br> <b>'.$FormatDateTime[$RS['data']][1].', '.$FormatDateTime[$RS['data']][3].'</td>');
    ShowHTML('          <td align="right">Dura��o:<br><b>'.FormataTempo($cDbl[$RS['duracao']]).'</td>');
    ShowHTML('        <tr valign="top">');
    ShowHTML('          <td>Ramal:<br><b>'.$RS['sq_ramal'].'</td>');
    ShowHTML('          <td>Tronco:<br> <b>'.$RS['sq_tronco'].'</td>');
    ShowHTML('          <td align="right">Valor:<br><b>'.$FormatNumber[$RS['valor']][2].'</td>');
    ShowHTML('    </TABLE>');
    // Verifica se houve transfer�ncias da liga��o, exibindo-as se existirem
    DB_GetCall($RS2,$w_sq_ligacao,$w_usuario,$P1,'LOG',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    if (!$RS2->EOF) {
      ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr align="center"><td colspan=4><b>Transfer�ncias da liga��o</td>');
      ShowHTML('        <tr align="center">');
      ShowHTML('          <td><b>Data</td>');
      ShowHTML('          <td><b>Origem</td>');
      ShowHTML('          <td><b>Destino</td>');
      ShowHTML('          <td><b>Observa��o</td>');
      foreach($RS as $row) {
        ShowHTML('        <tr valign="top">');
        ShowHTML('          <td  align="center" nowrap> '.$FormatDateTime[$RS['data']][2].'</td>');
        ShowHTML('          <td>'.$RS2['origem'].'</td>');
        ShowHTML('          <td>'.$RS2['destino'].'</td>');
        ShowHTML('          <td>'.$RS2['observacao'].'</td>');
      } 
      $RS2->Close;
      ShowHTML('    </TABLE>');
    } 
    ShowHTML('</table>');
    ShowHTML('<FORM action="'.$w_pagina.'Grava" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="p_sq_cc" value="'.$p_sq_cc.'">');
    ShowHTML('<INPUT type="hidden" name="p_outra_parte_contato" value="'.$p_outra_parte_contato.'">');
    ShowHTML('<INPUT type="hidden" name="p_numero" value="'.$p_numero.'">');
    ShowHTML('<INPUT type="hidden" name="p_inicio" value="'.$p_inicio.'">');
    ShowHTML('<INPUT type="hidden" name="p_fim" value="'.$p_fim.'">');
    ShowHTML('<INPUT type="hidden" name="p_ativo" value="'.$p_ativo.'">');
    ShowHTML('<INPUT type="hidden" name="p_ordena" value="'.$p_ordena.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_ligacao" value="'.$w_sq_ligacao.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="95%" border="0">');
    ShowHTML('      <tr><td align="center"><font color="#FF0000"><b>'.$w_titulo.'</b></td></tr>');
    if ($O=='A'){
      // Se for transfer�ncia de liga��o
      ShowHTML('      <tr>');
      SelecaoPessoa('Pe<u>s</u>soa:','S','Selecione a pessoa na rela��o.',$w_destino,$w_sq_central_telefonica,'w_destino','TTTRANSFERE');
      ShowHTML('      </tr>');
      ShowHTML('      <td colspan=3><b><U>O</U>bserva��o:<br><TEXTAREA ACCESSKEY="O" '.$w_Disabled.' class="STI" name="w_assunto" rows="5" cols=75>'.$w_assunto.'</textarea></td>');
    } else {
      // Outras opera��es
      ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
      MontaRadioNS('<b>Liga��o a trabalho?</b>',$w_trabalho,'w_trabalho');
      // Recupera o nome da pessoa de contato e o respons�vel pela liga��o no caso de tarifa��o telef�nica 
      //dentro do m�s anterior independente do usu�rio logado.
      $w_texto='';
      $w_texto='<b>Rela��o de nomes para este n�mero no m�s passado</b>:<br>'.
               '<table border=1 width=100% cellpadding=0 cellspacing=0>'.
               '<tr><td align=left><b>Nome'.
               '    <td><b>Respons�vel';
      db_GetCall($RS2,null,$w_usuario,$P1,'HINT',null,null,$RS['numero'],'01/'.date('m/Y',time()),FormataDataEdicao(time()),'N');
      if (!$RS2->EOF){
        foreach($RS2 as $row) {
          if ((strpos($w_texto,$RS2['d_nome']) ? strpos($w_texto,$RS2['d_nome'])+1 : 0)==0 && Nvl($RS2['d_nome'],'nulo')!='nulo'){
            $w_texto=$w_texto.'<tr><td valign=top align=left>'.$RS2['d_nome'].'<td valign=top>'.$RS2['responsavel'];
          }
        } 
      } 
      db_GetCall($RS2,null,$w_usuario,$P1,'HINT',null,null,$RS['numero'],'01/'.substr(100+$DatePart['m'][time()],1,2).'/'.$DatePart['yyyy'][time()].'',FormataDataEdicao(time()),'S');
      if (!$Rs2->EOF){
        foreach($RS2 as $row) {  
          if ((strpos($w_texto,$Rs2['d_nome']) ? strpos($w_texto,$Rs2['d_nome'])+1 : 0)==0 && Nvl($Rs2['d_nome'],'nulo')!='nulo'){
            $w_texto=$w_texto.'<tr><td valign=top align=left>'.$Rs2['d_nome'].'<td valign=top>'.$Rs2['responsavel'];
          }
        } 
      } 
      $w_texto=$w_texto.'</table>';
      MontaRadioNS('<b>Fax?</b>',$w_fax,'w_fax');
      ShowHTML('          <td><b>A<U>r</U>quivo:<br><INPUT ACCESSKEY="R" '.$w_Disabled.' class="STI" type="file" name="w_imagem" size="30" maxlength="80"></td>');
      ShowHTML('      </tr></table></td></tr>');
      ShowHTML('      <tr>');
      SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classifica��o � qual a liga��o est� vinculada.',$w_sq_cc,$w_sq_central_telefonica,'w_sq_cc','TTCENTRAL');
      ShowHTML('      </tr>');
      if ($w_responsavel>''){
        ShowHTML('      <tr><td><b>Respons�vel pela liga��o:<br><font size=2>'.$w_responsavel.'</td>');
      } 
      ShowHTML('      <tr><td><b><U>P</U>essoa de contato:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="STI" type="text" name="w_outra_parte_contato" size="60" maxlength="60" value="'.$w_outra_parte_contato.'\' '.$w_Disabled.' TITLE="'.str_replace(chr(13).chr(10),'<BR>',$w_texto).'"></td>');
      //ShowHTML '      <tr><td><b><U>P</U>essoa de contato:<br><INPUT ACCESSKEY=''P'' ' & w_Disabled & ' class=''STI'' type=''text'' name=''w_outra_parte_contato'' size=''60'' maxlength=''60'' value=''' & w_outra_parte_contato & '''></td>'
      ShowHTML('      <tr><td><b>Assu<U>n</U>to:<br><TEXTAREA ACCESSKEY="N" '.$w_Disabled.' class="STI" name="w_assunto" rows="5" cols=75>'.$w_assunto.'</textarea></td>');
    } 
    if ($O!='E'){
      ShowHTML('      <tr><td valign="top"><b><U>A</U>ssinatura Eletr�nica:<br><INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
    } 
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    if ($O=='E'){
      ShowHTML('            <input class="STB" type="button" name="Botao" value="Voltar" onClick="document.Form.action=\''.$R.'\'; document.Form.O.value=\'L\'; document.Form.submit();">');
    } else {
      ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
      ShowHTML('            <input class="STB" type="button" onClick="location.href=.\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'&O=L&p_sq_cc='.$p_sq_cc.'\';" name="Botao" value="Cancelar">');
    }
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P', $O)===false)) {
    ShowHTML('<FORM action="'.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="1">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R"  value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="O"  value="L">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classifica��o desejada.',$p_sq_cc,$w_sq_usuario_central,'p_sq_cc','TTUSUARIO');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_outra_parte_contato" size="40" maxlength="40" value=.\''.$p_outra_parte_contato.'"></td>');
    ShowHTML('          <td valign="top"><b>N<U>�</U>mero:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_numero" size="20" maxlength="20" value=.\''.$p_numero.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr align="left"><td><table cellpadding=0 cellspacing=0><tr valign="center">');
    ShowHTML('          <td><b>Per�odo</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>');
    ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_inicio" size="10" maxlength="10" value=.\''.$p_inicio.'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
    ShowHTML('          <td><b>A<U>t</U>�: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_fim" size="10" maxlength="10" value=.\''.$p_fim.'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_fim').'</td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td valign="top"><b>Liga��es:</b><br>');
    if ($p_Ativo=='S'){
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S" checked> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> N�o informadas');
    } elseif ($p_Ativo=='N'){
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N" checked> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> N�o informadas');
    } elseif ($p_Ativo=='A' || ($p_ativo=='' && $P1==3)){
      // Se for arquivo, seleciona ambas como valor inicial
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A" checked> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value=""> N�o informadas');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas <input '.$w_Disabled.' type="radio" name="p_ativo" value="" checked> N�o informadas');
    } 
    if (Nvl($p_assunto,'N')=='N' && Nvl($P1,3)!=3){
      ShowHTML('      <tr><td><input '.$w_Disabled.' type="checkbox" name="p_assunto" value="S"> Exibir o assunto das liga��es a trabalho</td></tr>');
    } else{
      ShowHTML('      <tr><td><input '.$w_Disabled.' type="checkbox" name="p_assunto" value="S" checked> Exibir o assunto das liga��es a trabalho</td></tr>');
    } 
    ShowHTML('      <tr><td><table cellpadding=0 cellspacing=0 width="100%"><tr valign="top">');
    ShowHTML('          <td><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="STS" name="p_ordena" size="1">');
    if ($p_Ordena=='D_CC') {
      ShowHTML('          <option value="D_CC" SELECTED>Classifica��o<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">N�mero<option value="SQ_RAMAL">Ramal');
    } elseif ($p_Ordena=='LOCALIDADE'){
      ShowHTML('          <option value="D_CC">Classifica��o<option value="">Data<option value="LOCALIDADE" SELECTED>Local<option value="d_nome">Nome<option value="numero">N�mero<option value="SQ_RAMAL">Ramal');
    } elseif ($p_Ordena=='OUTRA_PARTE_CONT'){
      ShowHTML('          <option value="D_CC">Classifica��o<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome" SELECTED>Nome<option value="numero">N�mero<option value="SQ_RAMAL">Ramal');
    } elseif ($p_Ordena=='NUMERO'){
      ShowHTML('          <option value="D_CC">Classifica��o<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero" SELECTED>N�mero<option value="SQ_RAMAL">Ramal');
    } elseif ($p_Ordena=='SQ_RAMAL'){
      ShowHTML('          <option value="D_CC">Classifica��o<option value="">Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">N�mero<option value="SQ_RAMAL" SELECTED>Ramal');
    } else {
      ShowHTML('          <option value="D_CC">Classifica��o<option value="" SELECTED>Data<option value="LOCALIDADE">Local<option value="d_nome">Nome<option value="numero">N�mero<option value="SQ_RAMAL">Ramal');
    } 
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="STI" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href="'.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_sq_cc='.$p_sq_cc.'&SG='.$SG.'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } elseif (!(strpos('P',$O)===false))  {
    ShowHTML('<FORM action="'.$w_pagina.$par.'" method="POST" name="Form" onSubmit="return(Validacao(this));">');
    ShowHTML('<INPUT type="hidden" name="P1" value="'.$P1.'">');
    ShowHTML('<INPUT type="hidden" name="P2" value="'.$P2.'">');
    ShowHTML('<INPUT type="hidden" name="P3" value="'.$P3.'">');
    ShowHTML('<INPUT type="hidden" name="P4" value="'.$P4.'">');
    ShowHTML('<INPUT type="hidden" name="TP" value="'.$TP.'">');
    ShowHTML('<INPUT type="hidden" name="SG" value="'.$SG.'">');
    ShowHTML('<INPUT type="hidden" name="R" value="'.$R.'">');
    ShowHTML('<INPUT type="hidden" name="O" value="'.$O.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Exibir resumo</i>. Clicando sobre o bot�o <i>Voltar a informar</i>, o filtro existente ser� apagado e ser� exibida a tela com as liga��es a informar.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="90%" border="0">');
    ShowHTML('      <tr align="left"><td><table width="100%" cellpadding=0 cellspacing=0><tr valign="top">');
    SelecaoCC('<u>C</u>entro de custo:','C','Selecione na lista a classifica��o desejada.',$p_sq_cc,$w_sq_usuario_central,'p_sq_cc','TTUSUARIO');
    ShowHTML('          <td valign="top"><b><U>N</U>ome:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="STI" type="text" name="p_outra_parte_contato" size="40" maxlength="40" value="'.$p_outra_parte_contato.'"></td>');
    ShowHTML('          <td valign="top"><b>N<U>�</U>mero:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_numero" size="20" maxlength="20" value="'.$p_numero.'"></td>');
    ShowHTML('      </tr></table></td></tr>');
    ShowHTML('      <tr align="left"><td><table cellpadding=0 cellspacing=0><tr valign="center">');
    ShowHTML('          <td><b>Per�odo</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>');
    if ($p_inicio==''){
      ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_inicio" size="10" maxlength="10" value="01/'.substr(100+$DatePart['m'][time()],1,2).'/'.$DatePart['yyyy'][time()].'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
      ShowHTML('          <td><b>A<U>t</U>�: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_fim" size="10" maxlength="10" value="'.FormataDataEdicao(time()).'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_fim').'</td>');
    } else {
      ShowHTML('          <td><b><U>D</U>e: <INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="p_inicio" size="10" maxlength="10" value="'.$p_inicio.'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_inicio').'&nbsp;</td>');
      ShowHTML('          <td><b>A<U>t</U>�: <INPUT ACCESSKEY="T" '.$w_Disabled.' class="STI" type="text" name="p_fim" size="10" maxlength="10" value="'.$p_fim.'" onKeyDown="FormataData(this,event)">'.ExibeCalendario('Form','p_fim').'</td>');
    } 
    ShowHTML('      </table>');
    if ($P1==3){
      // Se for arquivo
      ShowHTML('      <tr><td valign="top"><b>Liga��es: apenas a trabalho');
      ShowHTML('<INPUT type="hidden" name="p_ativo" value="S">');
    } else{
      ShowHTML('      <tr><td valign="top"><b>Liga��es:</b><br>');
      if ($p_Ativo=='S'){
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S" checked> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas');
      } elseif ($p_Ativo=='N'){
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N" checked> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A"> Ambas');
      } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="p_ativo" value="S"> A trabalho <input '.$w_Disabled.' type="radio" name="p_ativo" value="N"> Particulares <input '.$w_Disabled.' type="radio" name="p_ativo" value="A" checked> Ambas');
      } 
    } 
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir resumo">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\''.$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&p_sq_cc='.$p_sq_cc.'&SG='.$SG.'\';" name="Botao" value="Voltar a informar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    if ($p_inicio>''){
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><hr>');
      if ($P1!=3){
        // Se n�o for arquivo
        db_GetCall($Rs,null,$w_usuario,$P1,'PESSOAS',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
        $Rs->Sort='dura_tot desc';
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><b>Resumo comparativo por liga��es particulares</b>&nbsp;&nbsp;&nbsp;');
        ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
        ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td rowspan=2><b>Pessoa</td>');
        ShowHTML('          <td colspan=4><b>Quantidade</td>');
        ShowHTML('          <td colspan=4><b>Dura��o</td>');
        ShowHTML('        <tr align="center">');
        ShowHTML('          <td><b>ORI</td>');
        ShowHTML('          <td><b>REC</td>');
        ShowHTML('          <td><b>NAT</td>');
        ShowHTML('          <td><b>TOT</td>');
        ShowHTML('          <td><b>ORI</td>');
        ShowHTML('          <td><b>REC</td>');
        ShowHTML('          <td><b>NAT</td>');
        ShowHTML('          <td><b>TOT</td>');
        if ($Rs->EOF) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
        } else {
          $w_cor=$conTrAlternateBgColor;
          foreach($RS as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('      <tr bgcolor="'.$w_cor.'">');
            ShowHTML('        <td>'.f($Rs,'nome_resumido').'</td>');
            ShowHTML('        <td align="right">'.f($Rs,'ori_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($Rs,'rec_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($Rs,'nat_qtd').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.f($Rs,'qtd_tot').'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'ori_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'rec_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'nat_dura')).'&nbsp;</td>');
            ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'dura_tot')).'&nbsp;</td>');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
      } 
      ShowHTML('    </TABLE>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br></td></tr>');
    } 
    db_GetCall($Rs,null,$w_sq_usuario_central,$P1,'GERAL',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><b>Resumo geral</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">[Exibir liga��es]</a>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td rowspan=2><b>Tipo</td>');
    ShowHTML('          <td colspan=4><b>Quantidade</td>');
    ShowHTML('          <td colspan=4><b>Dura��o</td>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    if ($Rs->EOF){
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td><font '.$w_cor_fonte.'>'.f($Rs,'trabalho').'</td>');
        ShowHTML('        <td align="right">'.f($Rs,'ori_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'rec_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'nat_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'qtd_tot').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'ori_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'rec_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'nat_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(Nvl(f($Rs,'dura_tot'),0)).'&nbsp;</td>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    db_GetCall($Rs,null,$w_sq_usuario_central,$P1,'CTCC',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br><b>Resumo por Classifica��o</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">[Exibir liga��es]</a>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td rowspan=2><b>Classifica��o</td>');
    ShowHTML('          <td colspan=4><b>Quantidade</td>');
    ShowHTML('          <td colspan=4><b>Dura��o</td>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    if ($Rs->EOF){
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td>'.f($Rs,'sigla').'</td>');
        ShowHTML('        <td align="right">'.f($Rs,'ori_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'rec_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'nat_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'qtd_tot').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'ori_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'rec_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'nat_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'dura_tot')).'&nbsp;</td>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    db_GetCall($Rs,null,$w_sq_usuario_central,$P1,'MES',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br><b>Resumo por m�s</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">[Exibir liga��es]</a>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td rowspan=2><b>M�s/Ano</td>');
    ShowHTML('          <td colspan=4><b>Quantidade</td>');
    ShowHTML('          <td colspan=4><b>Dura��o</td>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    if ($Rs->EOF){
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.substr(f($Rs,'mes'),4,2).'/'.substr(f($Rs,'mes'),0,4).'</td>');
        ShowHTML('        <td align="right">'.f($Rs,'ori_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'rec_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'nat_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($Rs,'qtd_tot').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'ori_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'rec_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'nat_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($Rs,'dura_tot')).'&nbsp;</td>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    db_GetCall($Rs,null,$w_sq_usuario_central,$P1,'DIASEMANA',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br><b>Resumo por dia da semana</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">[Exibir liga��es]</a>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td rowspan=2><b>Dia</td>');
    ShowHTML('          <td colspan=4><b>Quantidade</td>');
    ShowHTML('          <td colspan=4><b>Dura��o</td>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    if ($Rs->EOF){
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($Rs,'dia').'</td>');
        ShowHTML('        <td align="right">'.f($RS,'ori_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'rec_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'nat_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'qtd_tot').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'ori_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'rec_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'nat_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'dura_tot')).'&nbsp;</td>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    db_GetCall($Rs,null,$w_sq_usuario_central,$P1,'DIAMES',$p_sq_cc,$p_outra_parte_contato,$p_numero,$p_inicio,$p_fim,$p_ativo);
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><font size=2><br><br><b>Resumo por dia do m�s</b>&nbsp;&nbsp;&nbsp;<a accesskey="F" class="SS" href="'.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_outra_parte_contato='.$p_outra_parte_contato.'&p_sq_cc='.$p_sq_cc.'&p_numero='.$p_numero.'&p_inicio='.$p_inicio.'&p_fim='.$p_fim.'&p_ativo='.$p_ativo.'&p_ordena='.$p_ordena.'">[Exibir liga��es]</a>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <TABLE WIDTH="90%" align="center" BORDER=1 CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td rowspan=2><b>Dia</td>');
    ShowHTML('          <td colspan=4><b>Quantidade</td>');
    ShowHTML('          <td colspan=4><b>Dura��o</td>');
    ShowHTML('        <tr align="center">');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    ShowHTML('          <td><b>ORI</td>');
    ShowHTML('          <td><b>REC</td>');
    ShowHTML('          <td><b>NAT</td>');
    ShowHTML('          <td><b>TOT</td>');
    if ($Rs->EOF) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><font size="2"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'">');
        ShowHTML('        <td align="center">'.f($RS,'mes').'</td>');
        ShowHTML('        <td align="right">'.f($RS,'ori_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'rec_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'nat_qtd').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.f($RS,'qtd_tot').'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'ori_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'rec_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'nat_dura')).'&nbsp;</td>');
        ShowHTML('        <td align="right">'.FormataTempo(f($RS,'dura_tot')).'&nbsp;</td>');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </TABLE>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' ("('Op��o n�o dispon�vel');');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
} 

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava(){
  extract($GLOBALS);

  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('("=document.focus();');
  AbreSessao();
  switch ($SG){
    case 'LIGACAO':
    // Verifica se a Assinatura Eletr�nica � v�lida
    if ((VerificaAssinaturaEletronica($Username_session,$w_assinatura) && $w_assinatura>'') || $w_assinatura==''){
      dml_PutCall($O,$REQUEST['w_sq_ligacao'},$REQUEST['w_destino'},$REQUEST['w_sq_cc'},$REQUEST['w_outra_parte_contato'},
      $REQUEST['w_assunto'},$w_usuario,$REQUEST['w_fax'},$REQUEST['w_trabalho'});
      ScriptOpen('JavaScript');
      ShowHTML('  location.href=''.$R.'&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'&p_sq_cc='.$REQUEST['p_sq_cc'}.'&p_outra_parte_contato='.$REQUEST['p_outra_parte_contato'}.'&p_numero='.$REQUEST['p_numero'}.'&p_inicio='.$REQUEST['p_inicio'}.'&p_fim='.$REQUEST['p_fim'}.'&p_ativo='.$REQUEST['p_ativo'}.'&p_ordena='.$REQUEST['p_ordena'}.'';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  ("('Assinatura Eletr�nica inv�lida!');');
      ShowHTML('  history.back(1);');
      ScriptClose();
    } 
    break;
  } 
  $w_Null=null;
  return $function_ret;
} 
// -------------------------------------------------------------------------

// Fim do procedimento que executa as opera��es de BD

// =========================================================================


// =========================================================================

// Rotina principal

// -------------------------------------------------------------------------

function Main(){
  extract($GLOBALS);
  // Verifica se o usu�rio tem lota��o e localiza��o
  switch ($par){
    case 'INFORMAR': Informar();  break;
    case 'GRAVA'   : Grava();     break;
    default:
    Cabecalho();
    BodyOpen('("=document.focus();');
    ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
    ShowHTML('<HR>');
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Rodape();
    break;
  } 
  return $function_ret;
} 
// =========================================================================

// Fim da rotina principal

// -------------------------------------------------------------------------

?>