<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getImposto.php');
include_once($w_dir_volta.'classes/sp/db_getPlanoEstrategico.php');
include_once($w_dir_volta.'classes/sp/db_getTipoDocumento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_getFNParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putFNParametro.php');
include_once($w_dir_volta.'classes/sp/dml_putImposto.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoDocumento.php');
include_once($w_dir_volta.'classes/sp/dml_putTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoTipoBeneficiario.php');
include_once($w_dir_volta.'funcoes/selecaoTipoVinculo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoEsfera.php');
include_once($w_dir_volta.'funcoes/selecaoCalculo.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoPessoaOrigem.php');
// =========================================================================
//  /Tabelas.asp
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Gerenciar tabelas básicas do módulo  
// Mail     : celso@sbpi.com.br
// Criacao  : 21/07/2006 10:00
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

// Carrega variáveis locais com os dados dos parâmetros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'tabelas.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];
$p_ordena       = $_REQUEST['p_ordena'];
$w_copia        = $_REQUEST['w_copia'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  default:  $w_TP=$TP.' - Listagem';    break;
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
// Rotina de impostos
// -------------------------------------------------------------------------
function Imposto() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_nome           = $_REQUEST['w_nome'];
    $w_descricao      = $_REQUEST['w_descricao'];
    $w_sigla          = $_REQUEST['w_sigla'];
    $w_esfera         = $_REQUEST['w_esfera'];
    $w_calculo        = $_REQUEST['w_calculo'];
    $w_dia_pagamento  = $_REQUEST['w_dia_pagamento'];
    $w_ativo          = $_REQUEST['w_ativo'];
    $w_lancamento     = $_REQUEST['w_lancamento'];
    $w_documento      = $_REQUEST['w_documento'];
    $w_tipo_benef     = $_REQUEST['w_tipo_benef'];
    $w_sq_benef       = $_REQUEST['w_sq_benef'];
    $w_tipo_vinc      = $_REQUEST['w_tipo_vinc'];
    $w_sq_cc          = $_REQUEST['w_sq_cc'];
    $w_sq_solic       = $_REQUEST['w_sq_solic'];
    $w_sq_menu_relac  = $_REQUEST['w_sq_menu_relac'];
    $w_sq_forma_pag   =  $_REQUEST['w_sq_forma_pag'];
    if($w_sq_menu_relac=='CLASSIF') {
      $w_chave_pai    = '';
    } else {
      $w_chave_pai    = $_REQUEST['w_chave_pai'];
    }
  } elseif ($O=='L') {
    $sql = new db_getImposto; $RS = $sql->getInstanceOf($dbms,null,$w_cliente);
    if ($p_ordena>'') { 
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'fim','asc','prioridade','asc');
    } else {
      $RS = SortArray($RS,'nome','asc');
    }
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getImposto; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_cliente);
    foreach($RS as $row) {$RS = $row; break;}
    $w_chave          = f($RS,'chave');
    $w_nome           = f($RS,'nome');
    $w_descricao      = f($RS,'descricao');
    $w_sigla          = f($RS,'sigla');
    $w_esfera         = f($RS,'esfera');
    $w_calculo        = f($RS,'calculo');
    $w_dia_pagamento  = f($RS,'dia_pagamento');
    $w_ativo          = f($RS,'nm_ativo');
    $w_lancamento     = f($RS,'sq_tipo_lancamento');
    $w_documento      = f($RS,'sq_tipo_documento');
    $w_tipo_benef     = f($RS,'tipo_beneficiario');
    $w_sq_benef       = f($RS,'sq_beneficiario');
    $w_tipo_vinc      = f($RS,'tipo_vinculo');
    $w_sq_cc          = f($RS,'sq_cc_vinculo');
    $w_sq_solic       = f($RS,'sq_solic_vinculo');
    $w_sq_forma_pag   = f($RS,'sq_forma_pagamento');
    
    $w_vinculo        = explode('|@|',f($RS,'nm_tipo_vinculo'));
    $w_sq_menu_relac  = $w_vinculo[3];
    if (nvl($w_sqcc,'')!='') $w_sq_menu_relac='CLASSIF';
  } 
  if(nvl($w_sq_menu_relac,0)>0) { $sql = new db_getMenuData; $RS_Relac  = $sql->getInstanceOf($dbms,$w_sq_menu_relac); }
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de impostos</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','50','1','1');
      Validate('w_descricao','descrição','1','1','3','500','1','1');
      Validate('w_sigla','Sigla','1','1','2','15','1','1');
      Validate('w_esfera','Esfera','SELECT','1','1','1','1','1');
      Validate('w_calculo','Calculo','SELECT','1','1','1','1','1');
      Validate('w_dia_pagamento','Dia do Pagamento','1','1','1','2','','1');
      Validate('w_tipo_benef','Beneficiário padrão','SELECT',1,1,18,1,1);
      if ($w_tipo_benef==2) {
        Validate('w_sq_benef_nm','Beneficiário padrão','1','1','2','80','1','1');
      }
      Validate('w_tipo_vinc','Vinculação padrão','SELECT',1,1,18,1,1);
      if ($w_tipo_vinc==1) {
        Validate('w_sq_menu_relac','Vincular a','SELECT',1,1,18,1,1);
        if(nvl($w_sq_menu_relac,'')!='') {
          if ($w_sq_menu_relac=='CLASSIF') {
            Validate('w_sqcc','Classificação','SELECT',1,1,18,1,1);
          } else {
            Validate('w_sq_solic','Vinculação','SELECT',1,1,18,1,1);
          }
        }
      }
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else {
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
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</font></td>');
    //ShowHTML('          <td><b>'.LinkOrdena('Descrição','descricao').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Tipo de lançamento','nm_tipo_lancamento').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Documento','nm_tipo_documento').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Beneficiário padrão','nm_tipo_beneficiario').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Centro Custo padrão','nm_tipo_vinculo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Forma Pag.','nm_forma_pagamento').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Cálculo','nm_calculo').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td class="remover"><b> Operações </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'sigla').'</td>');
        //ShowHTML('        <td align="left">'.f($row,'descricao').'</td>');
        ShowHTML('        <td>'.nvl(f($row,'nm_tipo_lancamento'),'---').'</td>');
        ShowHTML('        <td '.((nvl(f($row,'nm_tipo_documento'),'')!='') ? ' title="'.f($row,'nm_tipo_documento').'"' : '').'>'.nvl(f($row,'sg_tipo_documento'),'---').'</td>');
        if (nvl(f($row,'sq_beneficiario'),'')!='') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_beneficiario'),$TP,f($row,'nm_benef_res')).'</td>');
        else                                       ShowHTML('        <td>'.f($row,'nm_tipo_beneficiario').'</td>');
        if (f($row,'tipo_vinculo')==1) ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_vinculo'),f($row,'dados_vinculo'),'N',$w_tipo).'</td>');
        else                           ShowHTML('        <td>'.f($row,'nm_tipo_vinculo').'</td>');
        ShowHTML('        <td>'.f($row,'nm_forma_pagamento').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_calculo').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('<tr><td align="center" colspan=3>');
    MontaBarra($w_dir.$w_pagina.$par.'&R='.(($R>'') ? $R : $w_pagina.$par).'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_chave='.$w_chave,ceil(count($RS)/$P4),$P3,$P4,count($RS));
    ShowHTML('</tr>');
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td colspan="5"><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td colspan="5"><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_descricao" rows="5" cols=75>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoLancamento('Tipo de <u>l</u>ançamento:','L','Selecione o tipo de lançamento padrão a ser usado neste registro.',$w_lancamento,null,$w_cliente,'w_lancamento','FNDEVENT',null,5);
    ShowHTML('      <tr valign="top">');
    SelecaoTipoDocumento('Tipo de <u>d</u>ocumento:','D', 'Selecione o tipo de documento padrão usado a ser usado neste registro.', $w_documento,$w_cliente,'w_documento',null,null);
    SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma de pagamento padrão para este registro.',$w_sq_forma_pag,'FNDEVENT','w_sq_forma_pag',null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_sigla.'"></td>');
    SelecaoEsfera('<u>E</u>sfera:','E','Selecione a esfera desejada',$w_chave,$w_esfera,$w_cliente,'w_esfera',null,null);
    SelecaoCalculo('<u>C</u>álculo:','C','Selecione a base de calculo',$w_chave,$w_calculo,$w_cliente,'w_calculo',null,null);
    ShowHTML('          <td><b>D<u>i</u>a de pagamento:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_dia_pagamento" class="sti" SIZE="2" MAXLENGTH="2" VALUE="'.$w_dia_pagamento.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoBeneficiario('<u>B</u>eneficiário padrão:','B','Selecione o tipo de beneficiário padrão a ser usado neste registro.',$w_tipo_benef,$w_cliente,'w_tipo_benef',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_tipo_benef\'; document.Form.submit();"');
    if ($w_tipo_benef==2) {
      SelecaoPessoaOrigem('<u>P</u>adrão:', 'P', 'Clique na lupa para selecionar o beneficiário padrão.', $w_sq_benef, null, 'w_sq_benef', null, null, null,4);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_benef" value="">');
    }
    ShowHTML('          <tr valign="top">');
    SelecaoTipoVinculo('<u>C</u>entro de custo padrão:','V','Selecione o centro de custo padrão a ser usado neste registro.',$w_tipo_vinc,$w_cliente,'w_tipo_vinc',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_tipo_vinc\'; document.Form.submit();"',1);
    if ($w_tipo_vinc==1) {
      selecaoServico('<U>V</U>incular a:', 'S', null, $w_sq_menu_relac, $w_menu, null, 'w_sq_menu_relac', 'MENURELAC', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_menu_relac\'; document.Form.submit();"', $w_acordo, $w_acao, $w_viagem);
      if(nvl($w_sq_menu_relac,'')!='') {
        if ($w_sq_menu_relac=='CLASSIF') {
          SelecaoSolic('Classificação:',null,null,$w_cliente,$w_sqcc,$w_sq_menu_relac,null,'w_sqcc','SIWSOLIC',null);
          ShowHTML('<INPUT type="hidden" name="w_sq_solic" value="">');
        } else {
          SelecaoSolic('Vinculação:',null,null,$w_cliente,$w_sq_solic,$w_sq_menu_relac,0,'w_sq_solic',f($RS_Relac,'sigla'),null,$w_sq_solic,'<BR />',3);
          ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="">');
        }
      }
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_cc" value="">');
      ShowHTML('<INPUT type="hidden" name="w_sq_solic" value="">');
    }
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
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
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_cliente='.$w_cliente.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.montaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  }
  else {
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
// Rotina de tipos de documentos
// -------------------------------------------------------------------------
function Documento(){
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave  = $_REQUEST['w_chave'];
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de documentos</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_nome           = $_REQUEST['w_nome'];
    $w_sigla          = $_REQUEST['w_sigla'];
    $w_item           = $_REQUEST['w_item'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $sql = new db_getTipoDocumento; $RS = $sql->getInstanceOf($dbms,null,$w_cliente);
    if ($p_ordena>'') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'receita','desc');
    } else {
      $RS = SortArray($RS,'receita','desc','nome','asc');
    }
  } elseif (!(strpos('AEV',$O)===false && $w_troca=='')) {
    $sql = new db_getTipoDocumento; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_cliente);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave    = f($RS,'chave');
    $w_nome     = f($RS,'nome');
    $w_sigla    = f($RS,'sigla');
    $w_item     = f($RS,'detalha_item');
    $w_ativo    = f($RS,'nm_ativo');
  } 
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_sigla','Sigla','1','1','2','10','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\')) ');
      ShowHTML('     { return (true); }; ');
      ShowHTML('     { return (false); }; ');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus();"');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Detalha itens','detalha_item').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td class="remover"><b> Operações </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach ($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_detalha_item').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
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
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Detalha itens</b>?',$w_item,'w_item');
    MontaRadioSN('<b>Ativo</b>?',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT" colspan=2><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=2><hr>');
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de Tipos de lancamento
// -------------------------------------------------------------------------
function Lancamento() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  Cabecalho();
  head();
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de tipos de lançamento</TITLE>');
  if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E')  {
    // Se for recarga da página
    $w_pai          = $_REQUEST['w_pai'];
    $w_nome         = $_REQUEST['w_nome'];
    $w_descricao    = $_REQUEST['w_descricao'];
    $w_receita      = $_REQUEST['w_receita'];
    $w_despesa      = $_REQUEST['w_despesa'];
    $w_reembolso    = $_REQUEST['w_reembolso'];
    $w_ativo        = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,null,null,$w_cliente,'ARVORE');
  } elseif (strpos('AEV',$O)!==false) {
    $sql = new db_getTipoLancamento; $RS = $sql->getInstanceOf($dbms,$w_chave,null,$w_cliente,null);
    foreach ($RS as $row) {$RS=$row; break;}
    $w_chave        = f($RS,'chave');
    $w_pai          = f($RS,'sq_tipo_lancamento_pai');
    $w_nome         = f($RS,'nome');
    $w_descricao    = f($RS,'descricao');
    $w_receita      = f($RS,'receita');
    $w_despesa      = f($RS,'despesa');
    $w_reembolso    = f($RS,'reembolso');
    $w_ativo        = f($RS,'ativo');
  } 
  if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_nome','Nome','1','1','5','200','1','1');
      Validate('w_descricao','descrição','1','1','5','200','1','1');
      ShowHTML('  if (theForm.w_receita[1].checked && theForm.w_despesa[1].checked) {');
      ShowHTML('     alert ("Não pode existir tipo de lançamento com valores negativos para os campos recebimento e pagamento ao mesmo tempo!");');
      ShowHTML('     return false;');
      ShowHTML('  }');
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
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'') {
    BodyOpen('onLoad="document.Form.'.$w_troca.'.focus()";');
  } elseif ($O=='I' || $O=='A') {
    BodyOpen('onLoad="document.Form.w_nome.focus()";');
  } elseif ($O=='L') {
    BodyOpen('onLoad="this.focus()";');
  } else {
    BodyOpen('onLoad="document.Form.w_assinatura.focus()";');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr><td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Descrição','descricao').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Recebimento','nm_receita').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Pagamento','nm_despesa').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Reembolso','nm_reembolso').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Lançamentos','qt_lancamentos').'</font></td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','nm_ativo').'</font></td>');
    ShowHTML('          <td class="remover"><b> Operações </font></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        $l_destaque = ((f($row,'qt_filhos')>0) ? '<b>' : '');
        if (nvl(f($row,'level'),0)==1) {
          ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.$imagem.'<td>'.$l_destaque.f($row,'nome').'</b></td></tr></table>');
        } else {
          ShowHTML('        <td><table border=0 width="100%" cellpadding=0 cellspacing=0><tr valign="top">'.str_repeat('<td width="3%"></td>',(f($row,'level')-1)).$imagem.'<td>'.$l_destaque.f($row,'nome').' '.'</b></td></tr></table>');
        }
        ShowHTML('        <td align="left">'.f($row,'descricao').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_receita').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_despesa').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_reembolso').'</td>');
        ShowHTML('        <td align="center">'.f($row,'qt_lancamentos').'</td>');
        ShowHTML('        <td align="center">'.f($row,'nm_ativo').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">AL </A>&nbsp');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'chave').' &P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.'">EX </A>&nbsp');
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    //Aqui começa a manipulação de registros
  } elseif (!(strpos('IAEV',$O)===false)) {
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td colspan=3><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="75" MAXLENGTH="200" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr>');
    SelecaoTipoLancamento('<u>S</u>ubordinação:','S',null,$w_pai,$w_chave,$w_cliente,'w_pai',(($O=='A') ? 'SUBPARTE' : 'SUBTODOS'),null,3);
    ShowHTML('      <tr><td colspan=3><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_descricao" rows="5" cols=75>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr>');
    MontaRadioNS('<b>Recebimento?</b>',$w_receita,'w_receita');
    MontaRadioNS('<b>Pagamento?</b>',$w_despesa,'w_despesa');
    MontaRadioNS('<b>Reembolso?</b>',$w_reembolso,'w_reembolso');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
    ShowHTML('      <tr><td align="LEFT" colspan=3><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan=3><hr>');
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
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
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
    $w_devolucao         = $_REQUEST['w_devolucao'];
    $w_fundo_valor       = $_REQUEST['w_fundo_valor'];
    $w_fundo_qtd         = $_REQUEST['w_fundo_qtd'];
    $w_fundo_util        = $_REQUEST['w_fundo_util'];
    $w_fundo_contas      = $_REQUEST['w_fundo_contas'];
    $w_fundo_data        = $_REQUEST['w_fundo_data'];
  } else {
    // Recupera os dados do parâmetro
    $sql = new db_getFNParametro; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,null);
    if (count($RS)>0) {
      foreach($RS as $row) { $RS = $row; break; }
      $w_sequencial         = f($RS,'sequencial');
      $w_sequencial_atual   = f($RS,'sequencial');
      $w_ano_corrente       = f($RS,'ano_corrente');
      $w_prefixo            = f($RS,'prefixo');
      $w_sufixo             = f($RS,'sufixo');
      $w_devolucao          = f($RS,'texto_devolucao');
      $w_fundo_valor        = formatNumber(f($RS,'fundo_fixo_valor'));
      $w_fundo_qtd          = f($RS,'fundo_fixo_qtd');
      $w_fundo_util         = f($RS,'fundo_fixo_dias_utilizacao');
      $w_fundo_contas       = f($RS,'fundo_fixo_dias_contas');
      $w_fundo_data         = f($RS,'fundo_fixo_data_contas');
    } 
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataValor();
  FormataDataDM();
  ValidateOpen('Validacao');
  Validate('w_sequencial','Sequencial','1',1,1,18,'','0123456789');
  CompValor('w_sequencial','Sequencial','>=',$w_sequencial_atual,$w_sequencial_atual);
  Validate('w_ano_corrente', 'Ano corrente', '1', 1, 4, 4, '', '0123456789');
  Validate('w_prefixo','Prefixo','1','',1,10,'1','1');
  Validate('w_sufixo','Sufixo','1','',1,10,'1','1');
  Validate('w_fundo_valor','Valor limite para cada fundo fixo','VALOR','1',4,18,'','0123456789.,');
  Validate('w_fundo_qtd','Valor limite para cada fundo fixo','VALOR','1',1,4,'','0123456789');
  Validate('w_fundo_util','Máximo de dias para utilização do fundo fixo','VALOR','1',1,4,'','0123456789');
  Validate('w_fundo_contas','Máximo de dias para prestação de contas da utilização do fundo fixo','VALOR','1',1,4,'','0123456789');
  Validate('w_fundo_data','Limite para prestação de contas de fundo fixo utilizado até o fim do exercício anterior','DATADM','1',5,5,'','0123456789/');
  Validate('w_devolucao','Devolução de valores','1','',2,4000,'1','1');  
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
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0"><tr><td>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0"><b>Parâmetros</td></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  //ShowHTML '      <tr><td><font size=1>Falta definir a explicação.</font></td></tr>'
  //ShowHTML '      <tr><td align=''center'' height=''1'' bgcolor=''#000000''></td></tr>'
  ShowHTML('      </table>');
  ShowHTML('      <table width="100%" border="0">');
  ShowHTML('      <tr><td><b><u>S</u>equencial:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sequencial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sequencial.'"></td>');
  ShowHTML('      <td><b>Ano <U>c</U>orrente:<br><INPUT ACCESSKEY="C" '.$w_Disabled.' class="sti" type="text" name="w_ano_corrente" size="4" maxlength="4" value="'.$w_ano_corrente.'"></td>');
  ShowHTML('      <tr><td><b><u>P</u>refixo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_prefixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_prefixo.'"></td>');
  ShowHTML('          <td><b><u>S</u>ufixo:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sufixo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sufixo.'"></td>');
  ShowHTML('      <tr><td><b><u>V</u>alor limite para fundo fixo:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_fundo_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_fundo_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor máximo para cada fundo fixo."></td>');
  ShowHTML('          <td><b><u>Q</u>uantidade de fundos fixos:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_fundo_qtd" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_fundo_qtd.'" title="Informe o número máximo de fundos fixo simultâneos."></td>');
  ShowHTML('      <tr><td><b><u>M</u>áximo de dias para utilização do fundo fixo:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_fundo_util" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_fundo_util.'" title="Informe o número máximo de dias para utilização do fundos fixo."></td>');
  ShowHTML('          <td><b><u>L</u>imite de dias para prestação de contas do fundo fixo:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_fundo_contas" class="sti" SIZE="4" MAXLENGTH="4" VALUE="'.$w_fundo_contas.'" title="Informe o prazo em dias para prestação de contas do fundos fixo."></td>');
  ShowHTML('      <tr><td colspan=2><b><u>D</u>ata limite para prestação de contas do fundo fixo quando for utilizado até o fim do exercício anterior:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_fundo_data" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_fundo_data.'" onKeyDown="FormataDataDM(this,event);" title="Quando a importância do suprimento for utilizada até o final do exercício, a prestação de contas será feita até o dia indicado do exercício seguinte."></td>');
  ShowHTML('      </table>');
  ShowHTML('        <tr><td colspan=2><b><u>T</u>exto padrão para devolução de valores:</b><br><textarea '.$w_Disabled.'accesskey="T" name="w_devolucao" class="sti" ROWS="3" COLS="75">'.$w_devolucao.'</textarea></td>');  
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
    case 'FNIMPOSTO':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putImposto; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],
          $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_lancamento'],$_REQUEST['w_documento'],$_REQUEST['w_sigla'],
          $_REQUEST['w_esfera'],$_REQUEST['w_calculo'],$_REQUEST['w_dia_pagamento'],$_REQUEST['w_ativo'],$_REQUEST['w_tipo_benef'],
          $_REQUEST['w_sq_benef'],$_REQUEST['w_tipo_vinc'],$_REQUEST['w_sq_cc'],$_REQUEST['w_sq_solic'],$_REQUEST['w_sq_forma_pag']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'FNTPDOC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putTipoDocumento; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_nome'],
          $_REQUEST['w_sigla'],$_REQUEST['w_item'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'FNTPLANC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putTipoLancamento; $SQL->getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_pai'],$_REQUEST['w_cliente'],
          $_REQUEST['w_nome'],$_REQUEST['w_descricao'],$_REQUEST['w_receita'],$_REQUEST['w_despesa'],$_REQUEST['w_reembolso'],$_REQUEST['w_ativo']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'FNPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],upper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        $SQL = new dml_putFNParametro; $SQL->getInstanceOf($dbms,$w_cliente,
           $_REQUEST['w_sequencial'],$_REQUEST['w_ano_corrente'],$_REQUEST['w_prefixo'],$_REQUEST['w_sufixo'],$_REQUEST['w_fundo_valor'],
           $_REQUEST['w_fundo_qtd'],$_REQUEST['w_fundo_util'],$_REQUEST['w_fundo_contas'],$_REQUEST['w_fundo_data'],
           $_REQUEST['w_devolucao']);     
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert("Assinatura Eletrônica inválida!");');
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
    case 'IMPOSTO':     Imposto();      break;
    case 'DOCUMENTO':   Documento();    break;
    case 'LANCAMENTO':  Lancamento();   break;
    case 'PARAMETROS':  Parametros();   break;    
    case 'GRAVA':       Grava();        break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
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