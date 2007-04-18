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
include_once($w_dir_volta.'classes/sp/db_getCargo.php');
include_once($w_dir_volta.'classes/sp/db_getDataEspecial.php');
include_once($w_dir_volta.'classes/sp/db_getGPParametro.php');
include_once($w_dir_volta.'classes/sp/db_getGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/db_getGPModalidade.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putCargo.php');
include_once($w_dir_volta.'classes/sp/dml_putGPModalidade.php');
include_once($w_dir_volta.'classes/sp/dml_putGPTipoAfast.php');
include_once($w_dir_volta.'classes/sp/dml_putDataEspecial.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPosto2.php');
include_once($w_dir_volta.'funcoes/selecaoTipoData.php');
include_once($w_dir_volta.'funcoes/selecaoFormacao.php');
include_once($w_dir_volta.'funcoes/selecaoUnidade.php');
include_once($w_dir_volta.'funcoes/selecaoAbrangData.php'); 
include_once($w_dir_volta.'funcoes/selecaoPais.php'); 
include_once($w_dir_volta.'funcoes/selecaoEstado.php'); 
include_once($w_dir_volta.'funcoes/selecaoCidade.php'); 

// =========================================================================
//  /tabelas.php
// ------------------------------------------------------------------------
// Nome     : Billy Jones Leal dos Santos
// Descricao: Gerenciar tabelas básicas do módulo de gestão de pessoal
// Mail     : billy@sbpi.com.br
// Criacao  : 04/08/2006 16:00
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
$w_dir        = 'mod_rh/';
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
// Rotina de modalidade de contratacao 
// -------------------------------------------------------------------------

function ModalidadeCont() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem de modalidades de contratação</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');   
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') { 
    $w_nome       = $_REQUEST['w_nome'];
    $w_descricao  = $_REQUEST['w_descricao'];
    $w_sigla      = $_REQUEST['w_sigla'];
    $w_ferias     = $_REQUEST['w_ferias'];
    $w_username   = $_REQUEST['w_username'];
    $w_passagem   = $_REQUEST['w_passagem'];
    $w_diaria     = $_REQUEST['w_diaria'];
    $w_ativo      = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,null,$w_sigla,$w_nome,$w_ativo,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave      = f($RS,'chave');
    $w_nome       = f($RS,'nome');
    $w_descricao  = f($RS,'descricao');
    $w_sigla      = f($RS,'sigla');
    $w_ferias     = f($RS,'ferias');
    $w_username   = f($RS,'username');
    $w_passagem   = f($RS,'passagem');
    $w_diaria     = f($RS,'diaria');
    $w_ativo      = f($RS,'ativo');
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sigla','Sigla','1','1','2','10','1','');
      Validate('w_nome','Nome','1','1','3','50','1','1');
      Validate('w_descricao','Descrição','1','1','3','500','1','1');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      if ($O=='A') {
        ShowHTML('  if (theForm.w_ativo[1].checked) {');
        ShowHTML('  if (confirm(\'Modalidades inativas não podem ter vinculação com tipos de afastamento. Se existir algum vínculo, ele será removido. Confirma?\'))');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
        ShowHTML('  }');
      } 
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
  } elseif ($O=='I' || $O=='A'){
    BodyOpen('onLoad=document.Form.w_sigla.focus();');
  } elseif ($O=='L') {
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
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Username','username').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Férias','ferias').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Passagem','passagem').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Diária','diaria').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        if (f($row,'username')=='S') {
          ShowHTML('        <td align="center">Sempre</td>');
        } elseif (f($row,'username')=='N') {
          ShowHTML('        <td align="center">Nunca</td>');
        } else {
          ShowHTML('        <td align="center">Controlar por pessoa</td>');
        } if (f($row,'ferias')=='S') {
          ShowHTML('        <td align="center">Sempre</td>');
        } elseif (f($row,'ferias')=='N') {
          ShowHTML('        <td align="center">Nunca</td>');
        } else {
          ShowHTML('        <td align="center">Controlar por pessoa</td>');
        }
        ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'passagem')).'</td>');
        ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'diaria')).'</td>');
        if (f($row,'ativo')=='N') {
          ShowHTML('        <td align="center"><font color="red">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } else {
          ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } 
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
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
      $w_Disabled = ' DISABLED ';
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td colspan=2><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY="D" '.$w_Disabled.' class="sti" name="w_descricao" rows="5" cols=75>'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td><b>Cria e bloqueia username na entrada e saida do colaborador?</b><br>');
    if ($w_username=='N') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_username" value="S"> Sempre <br><input '.$w_Disabled.' type="radio" name="w_username" value="N" checked> Nunca <br><input '.$w_Disabled.' type="radio" name="w_username" value="P"> Controlar por pessoa');
    } elseif ($w_username=='P') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_username" value="S"> Sempre <br><input '.$w_Disabled.' type="radio" name="w_username" value="N"> Nunca <br><input '.$w_Disabled.' type="radio" name="w_username" value="P" checked> Controlar por pessoa');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_username" value="S" checked> Sempre <br><input '.$w_Disabled.' type="radio" name="w_username" value="N"> Nunca <br><input '.$w_Disabled.' type="radio" name="w_username" value="P"> Controlar por pessoa');
    }
    ShowHTML('          <td><b>Esta modalidade permite gozo de férias?</b><br>');
    if ($w_ferias=='N') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_ferias" value="S"> Sempre <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="N" checked> Nunca <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="P"> Controlar por pessoa');
    } elseif ($w_ferias=='S') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_ferias" value="S" checked> Sempre <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="N"> Nunca <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="P"> Controlar por pessoa');
    } else { 
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_ferias" value="S"> Sempre <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="N"> Nunca <br><input '.$w_Disabled.' type="radio" name="w_ferias" value="P"  checked> Controlar por pessoa');
    }   
    ShowHTML('      <tr valign="top">');
    MontaRadioSN('<b>Modalidade permite concessão de passagem?</b>',$w_passagem,'w_passagem');
    MontaRadioSN('<b>Modalidade permite pagamento de diárias?</b>',$w_diaria,'w_diaria');
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
Estrutura_Texto_Fecha();
Estrutura_Fecha(); 
Estrutura_Fecha();
Estrutura_Fecha();
Rodape();
} 

// =========================================================================
// Rotina de tipos de afastamento
// -------------------------------------------------------------------------
function Tipoafast() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave           = $_REQUEST['w_chave'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos tipos de afastamento</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">'); 
  }  
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') { 
    $w_nome            = $_REQUEST['w_nome'];
    $w_sigla           = $_REQUEST['w_sigla'];
    $w_limite_dias     = $_REQUEST['w_limite_dias'];
    $w_perc_pag        = $_REQUEST['w_perc_pag'];
    $w_sexo            = $_REQUEST['w_sexo'];
    $w_contagem_dias   = $_REQUEST['w_contagem_dias'];
    $w_periodo         = $_REQUEST['w_periodo'];
    $w_sobrepoe_ferias = $_REQUEST['w_sobrepoe_ferias'];
    $w_ativo           = $_REQUEST['w_ativo']; 
  } elseif ($O=='L') {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,null,$w_sigla,$w_nome,$w_ativo,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave            = f($RS,'chave');
    $w_nome             = f($RS,'nome');
    $w_sigla            = f($RS,'sigla');
    $w_limite_dias      = f($RS,'limite_dias');
    $w_perc_pag         = number_format(f($RS,'percentual_pagamento'),2,',','.');
    $w_sexo             = f($RS,'sexo');
    $w_contagem_dias    = f($RS,'contagem_dias');
    $w_periodo          = f($RS,'periodo');
    $w_sobrepoe_ferias  = f($RS,'sobrepoe_ferias');
    $w_ativo            = f($RS,'ativo');
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sigla','Sigla','1','1','2','10','1','');
      Validate('w_nome','Nome','1','1','3','50','1','1');
      Validate('w_limite_dias','Limite de dias','VALOR','1',1,6,'','0123456789');
      Validate('w_perc_pag','Percentual da remuneração','VALOR','1',4,18,'','0123456789.,');
      ShowHTML('  var cont = 0;');
      ShowHTML('  for (i=0;i<theForm.w_sq_modalidade.length;i++) {');
      ShowHTML('    if (theForm.w_sq_modalidade[i].checked) {');
      ShowHTML('      cont = cont+1;');
      ShowHTML('    }');
      ShowHTML('   }');
      ShowHTML('  if (theForm.w_ativo[0].checked && cont == 0) {');
      ShowHTML('    alert(\'Selecione pelo menos uma modalidade para o tipo de afastamento!\');');
      ShowHTML('    return false; ');
      ShowHTML('  } else { if (theForm.w_ativo[1].checked && cont > 0) {');
      ShowHTML('     alert(\'Não selecione nenhuma modalidade para tipos de afastamento inativos!\');');
      ShowHTML('     return false; }; ');
      ShowHTML('  }');
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } elseif ($O=='E') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
      ShowHTML('  if (confirm(\'Confirma a exclusão deste registro?\'));');
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
    BodyOpen('onLoad=document.Form.w_sigla.focus();');
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
    ShowHTML('<tr><td><font size="2"><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.LinkOrdena('Sigla','sigla').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Limite dias','limite_dias').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Sexo','nm_sexo').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('% Pagamento','percentual_pagamento').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
    ShowHTML('          <td><b> Operações </td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
    ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      foreach($RS1 as $row){ 
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td align="center">'.f($row,'sigla').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="right">'.f($row,'limite_dias').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_sexo').'</td>');
        ShowHTML('        <td align="right">'.number_format(f($row,'percentual_pagamento'),2,',','.').'</td>');
        if (f($row,'ativo')=='N') {
          ShowHTML('        <td align="center"><font color="red">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } else{
          ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } 
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
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
    if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED '; 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0"><tr>');
    ShowHTML('      <tr><td><b><u>S</u>igla:</b><br><input '.$w_Disabled.' accesskey="S" type="text" name="w_sigla" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_sigla.'"></td>');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('      <tr><td><b><u>L</u>imite de dias:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_limite_dias" class="STI" SIZE="6" MAXLENGTH="6" VALUE="'.$w_limite_dias.'"></td>');
    ShowHTML('          <td><b><u>P</u>ercentual da remuneração a ser pago quando afastado por este tipo:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_perc_pag" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_perc_pag.'" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('      <tr><td><b>Aplica-se ao sexo:</b><br>');
    if ($w_sexo=='M') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_sexo" value="F"> Feminino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="M" checked> Masculino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="A"> Ambos');
    } elseif ($w_sexo=='F') {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_sexo" value="F" checked> Feminino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="M"> Masculino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="A"> Ambos');
    } else {
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_sexo" value="F"> Feminino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="M"> Masculino <br><input '.$w_Disabled.' type="radio" name="w_sexo" value="A" checked> Ambos');
    } 
    ShowHTML('          <td><b>Contagem dos dias:</b><br>');
    if ($w_contagem_dias=='U') {  
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_contagem_dias" value="C"> Corridos <br><input '.$w_Disabled.' type="radio" name="w_contagem_dias" value="U" checked> Úteis');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_contagem_dias" value="C" checked> Corridos <br><input '.$w_Disabled.' type="radio" name="w_contagem_dias" value="U"> Úteis');
    } 
    ShowHTML('      <tr><td><b>Informar afastamento em:</b><br>');
    if ($w_periodo=='D') {  
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_periodo" value="A"> Datas <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="D" checked> Dias <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="H"> Horas');
    } elseif ($w_periodo=='H'){
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_periodo" value="A"> Datas <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="D"> Dias <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="H" checked> Horas');
    } else {
      ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_periodo" value="A" checked> Datas <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="D"> Dias <br><input '.$w_Disabled.' type="radio" name="w_periodo" value="H"> Horas');
    } 
    if ($O=='I') {
      $RS1 = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,null,null,null,'S',null,'TPAFASTAMENTO');
      ShowHTML('          <td rowspan=2><b>Modalidades de contratação vinculadas:</b><br>');
      if (count($RS1)> 0) {
        foreach($RS1 as $row) {
          ShowHTML('       <input type="checkbox" name="w_sq_modalidade" value="'.f($row,'chave').'">'.f($row,'nome').'<br>');
        } 
      } 
    } elseif ($O=='A' || $O=='E') {
      $RS1 = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,null,null,null,'S',$w_chave,'TPAFASTAMENTO');     
      ShowHTML('          <td rowspan=2><b>Modalidades de contratação vinculadas:</b><br>');
      if (count($RS1)> 0) {
        foreach($RS1 as $row) {
          if (Nvl(f($row,'sq_tipo_afastamento'),'')>'') {
            ShowHTML('       <input '.$w_disabled.' type="checkbox" name="w_sq_modalidade" value="'.f($row,'chave').'" checked>'.f($row,'nome').'<br>');
          } else { 
            ShowHTML('       <input '.$w_disabled.' type="checkbox" name="w_sq_modalidade" value="'.f($row,'chave').'">'.f($row,'nome').'<br>');
          } 
        } 
      } 
    } 
    ShowHTML('      <tr valign="top">');
    MontaRadioNS('<b>Sobrepõe gozo de férias?</b>',$w_sobrepoe_ferias,'w_sobrepoe_ferias');
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
// Rotina de modalidade de contratacao
// -------------------------------------------------------------------------
function DataEspecial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave = $_REQUEST['w_chave'];
  Cabecalho();
  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem das datas especiais</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') { 
    $w_sq_pais        = $_REQUEST['w_sq_pais'];
    $w_co_uf          = $_REQUEST['w_co_uf'];
    $w_sq_cidade      = $_REQUEST['w_sq_cidade'];
    $w_tipo           = $_REQUEST['w_tipo'];
    $w_data_especial  = $_REQUEST['w_data_especial'];
    $w_nome           = $_REQUEST['w_nome'];
    $w_abrangencia    = $_REQUEST['w_abrangencia'];
    $w_expediente     = $_REQUEST['w_expediente'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $RS = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'data_formatada','asc');
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null);
    foreach($RS as $row) { $RS = $row; break; }
    $w_chave         = f($RS,'chave');
    $w_sq_pais       = f($RS,'sq_pais');
    $w_co_uf         = f($RS,'co_uf');
    $w_sq_cidade     = f($RS,'sq_cidade');
    $w_tipo          = f($RS,'tipo');
    $w_data_especial = f($RS,'data_especial');
    $w_nome          = f($RS,'nome');
    $w_abrangencia   = f($RS,'abrangencia');
    $w_expediente    = f($RS,'expediente');
    $w_ativo         = f($RS,'ativo');
  } if (!(strpos('IAE',$O)===false)) {
      ScriptOpen('JavaScript');
      modulo();
      CheckBranco();
      FormataDataMA();
      FormataData();
      ValidateOpen('Validacao');
      if (!(strpos('IA',$O)===false)) {
        Validate('w_tipo','Tipo','SELECT','1','1','1','1','');
        if ($w_tipo=='I') {
          Validate('w_data_especial','Data','DATADM',1,5,5,'','0123456789/');
        } elseif ($w_tipo=='E') {
          Validate('w_data_especial','Data','DATA',1,10,10,'','0123456789/');
        } 
        Validate('w_nome','Descrição','1','1','3','60','1','1');
        ShowHTML('  if (theForm.w_tipo.value == \'I\' && theForm.w_tipo.value == \'E\'){ ');
        Validate('w_abrangencia','Abrangência','SELECT','1','1','1','1','');
        ShowHTML('  };');
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
    if ($w_troca>'' && $w_troca!='w_data_especial') {
      BodyOpen('onLoad=document.Form.'.$w_troca.'.focus();');
    } elseif ($O=='I' || $O=='A') {
      BodyOpen('onLoad=document.Form.w_tipo.focus();');
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
      ShowHTML('<tr><td><font size="2">');
      ShowHTML('    <a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
      ShowHTML('    <a accesskey="G" class="ss" href="'.$w_dir.$w_pagina.'Grava&R='.$w_pagina.$par.'&O=G&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" onClick="return(confirm(\'Confirma geração ou atualização do arquivo de calendário?\'))"><u>G</u>erar arquivo</a>&nbsp;');
      ShowHTML('    <td align="right"><b>Registros existentes: '.count($RS));
      ShowHTML('<tr><td align="center" colspan=3>');
      ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('          <td><b>'.LinkOrdena('Data','data_especial').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Descricao','nome').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Tipo','tipo').'</td>');
      ShowHTML('          <td><b>Abrangência</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Expediente','expediente').'</td>');
      ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
      ShowHTML('          <td><b> Operações </td>');
      ShowHTML('        </tr>');
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>');
      } else {
        // Lista os registros selecionados para listagem
        $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
        foreach($RS1 as $row){ 
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
          ShowHTML('        <td align="center">'.Nvl(f($row,'data_especial'),'---').'</td>');
          ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
          ShowHTML('        <td align="left">'.RetornaTipoData(f($row,'tipo')).'</td>');
          if (Nvl(f($row,'sq_cidade'),'')>'') {
            $RS1 = db_getCountryData::getInstanceOf($dbms,f($row,'sq_pais'));
            if (f($RS1,'padrao')=='S') {
              $RS2 = db_getCityData::getInstanceOf($dbms,f($row,'sq_cidade'));
              ShowHTML('        <td align="left">'.f($RS2,'nome').' - '.f($RS2,'co_uf').'</td>');
            } else {
              $RS2 = db_getCityData::getInstanceOf($dbms,f($row,'sq_cidade'));
              ShowHTML('        <td align="left">'.f($RS2,'nome').' - '.f($RS1,'nome').'</td>');
            } 
          } elseif (Nvl(f($row,'co_uf'),'')>''){  
            $RS1 = db_getCountryData::getInstanceOf($dbms,f($row,'sq_pais'));
            if (f($RS1,'padrao')=='S') {
              $RS2 = db_getStateData::getInstanceOf($dbms,f($row,'sq_pais'),f($row,'co_uf'));
              ShowHTML('        <td align="left">'.f($RS2,'co_uf').'</td>');
            } else {
              $RS2 = db_getStateData::getInstanceOf($dbms,f($row,'sq_pais'),f($row,'co_uf'));
              ShowHTML('        <td align="left">'.f($RS2,'co_uf').' - '.f($RS1,'nome').'</td>');
            } 
          } elseif (Nvl(f($row,'sq_pais'),'')>'') {
            $RS1 = db_getCountryData::getInstanceOf($dbms,f($row,'sq_pais'));
            ShowHTML('        <td align="left">'.f($RS1,'nome').'</td>');
          }  elseif (f($row,'abrangencia')=='O') {
            ShowHTML('        <td align="left">Organização</td>');
          } else {
            ShowHTML('        <td align="left">Internacional</td>');
          } 
          ShowHTML('        <td align="left">'.RetornaExpedienteData(f($row,'expediente')).'</td>');
          if (f($row,'ativo')=='N'){
            ShowHTML('        <td align="center"><font color="red">'.RetornaSimNao(f($row,'ativo')).'</td>');
          } else {
            ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'ativo')).'</td>');
          } 
          ShowHTML('        <td align="top" nowrap>');
          ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
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
      if (!(strpos('EV',$O)===false)) $w_Disabled=' DISABLED ';
      AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
      ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
      ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
      ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0"><tr>');
      ShowHTML('      <tr>');
      SelecaoTipoData('<u>T</u>ipo:','T',null,$w_tipo,null,'w_tipo',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_data_especial\'; document.Form.submit();"');
      if ($w_tipo=='I') {
        ShowHTML('          <td><b>Da<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_data_especial" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_data_especial.'" onKeyDown="FormataDataMA(this,event);"></td>');
      } elseif ($w_tipo=='E') {
        ShowHTML('          <td><b>Da<u>t</u>a:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_data_especial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_especial.'" onKeyDown="FormataData(this,event);"></td>');
      } else {
        ShowHTML('          <td><b>Da<u>t</u>a:</b><br><input Disabled accesskey="T" type="text" name="w_data_especial" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_data_especial.'"></td>');
      } 
      ShowHTML('          <td><b><u>D</u>escrição:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('      <tr>');
      if ($O!='E' && (strpos('IE',$w_tipo)===false)) {
         $w_abrangencia    = 'N';
         $w_Disabled       = 'DISABLED';
         SelecaoAbrangData('<u>A</u>brangência:','A',null,$w_abrangencia,null,'w_abrangencia',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_abrangencia\'; document.Form.submit();"');
         $w_Disabled       = 'ENABLE';
         ShowHTML('<INPUT type="hidden" name="w_abrangencia" value="'.$w_abrangencia.'">');
      } else {
        SelecaoAbrangData('<u>A</u>brangência:','A',null,$w_abrangencia,null,'w_abrangencia',null,'onchange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_abrangencia\'; document.Form.submit();"');
      } if (strpos('IO',$w_abrangencia)===false) {
        if ($w_abrangencia=='N') {
          SelecaoPais('<u>P</u>aís:','P',null,$w_sq_pais,null,'w_sq_pais',null,null);
        } elseif ($w_abrangencia=='E') {
          SelecaoPais('<u>P</u>aís:','P',null,$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
          SelecaoEstado('E<u>s</u>tado:','S',null,$w_co_uf,$w_sq_pais,null,'w_co_uf',null,null);
        } elseif ($w_abrangencia=='M') {
          SelecaoPais('<u>P</u>aís:','P',null,$w_sq_pais,null,'w_sq_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_co_uf\'; document.Form.submit();"');
          SelecaoEstado('E<u>s</u>tado:','S',null,$w_co_uf,$w_sq_pais,null,'w_co_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_sq_cidade\'; document.Form.submit();"');
          SelecaoCidade('<u>C</u>idade:','C',null,$w_sq_cidade,$w_sq_pais,$w_co_uf,'w_sq_cidade',null,null);
        } 
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('        <td><b>Expediente?</b><br>');
      if ($w_expediente=='N') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_expediente" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="N" checked> Não <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="M"> Somente manhã <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="T"> Somente tarde');
      } elseif ($w_expediente=='M') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_expediente" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="N"> Não <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="M" checked> Somente manhã <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="T"> Somente tarde');
      } elseif ($w_expediente=='T') {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_expediente" value="S"> Sim <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="N"> Não <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="M"> Somente manhã <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="T" checked> Somente tarde');
      } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="w_expediente" value="S" checked> Sim <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="N"> Não <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="M"> Somente manhã <br><input '.$w_Disabled.' type="radio" name="w_expediente" value="T"> Somente tarde');
      } 
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
// Rotina de parâmetros
// -------------------------------------------------------------------------
function Parametros() {
  extract($GLOBALS);
  Global $w_Disabled;
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {  
    // Se for recarga da página
    $w_sq_unidade_gestao    = $_REQUEST['w_sq_unidade_gestao'];
    $w_admissao_texto       = $_REQUEST['w_admissao_texto'];
    $w_admissao_destino     = $_REQUEST['w_admissao_destino'];
    $w_rescisao_texto       = $_REQUEST['w_rescisao_texto'];
    $w_rescisao_destino     = $_REQUEST['w_rescisao_destino'];
    $w_feriado_legenda      = $_REQUEST['w_feriado_legenda'];
    $w_feriado_nome         = $_REQUEST['w_feriado_nome'];
    $w_ferias_legenda       = $_REQUEST['w_ferias_legenda'];
    $w_ferias_nome          = $_REQUEST['w_ferias_nome'];
    $w_viagem_legenda       = $_REQUEST['w_viagem_legenda'];
    $w_viagem_nome          = $_REQUEST['w_viagem_nome'];
  } else {
    $RS = db_getGPParametro::getInstanceOf($dbms,$w_cliente,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    if (count($RS)>0) {
      $w_sq_unidade_gestao  = f($RS,'sq_unidade_gestao');
      $w_admissao_texto     = f($RS,'admissao_texto');
      $w_admissao_destino   = f($RS,'admissao_destino');
      $w_rescisao_texto     = f($RS,'rescisao_texto');
      $w_rescisao_destino   = f($RS,'rescisao_destino');
      $w_feriado_legenda    = f($RS,'feriado_legenda');
      $w_feriado_nome       = f($RS,'feriado_nome');
      $w_ferias_legenda     = f($RS,'ferias_legenda');
      $w_ferias_nome        = f($RS,'ferias_nome');
      $w_viagem_legenda     = f($RS,'viagem_legenda');
      $w_viagem_nome        = f($RS,'viagem_nome');
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
  ScriptOpen('JavaScript');
  ValidateOpen('Validacao');
  Validate('w_sq_unidade_gestao','Unidade gestora de colaboradores','SELECT',1,1,18,'','0123456789');
  Validate('w_admissao_destino','Destinatários da mensagem de entrada','1','1','5','100','1','1');
  Validate('w_admissao_texto','Texto comunicando a entrada de coloborador','1','1','3','1000','1','1');
  Validate('w_rescisao_destino','Destinatários da mensagem de saída','1','1','5','100','1','1');
  Validate('w_rescisao_texto','Texto comunicando a saída de coloborador','1','1','3','1000','1','1');
  Validate('w_feriado_legenda','Legenda do feriado','1','1','1','2','ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz','');
  Validate('w_feriado_nome','Nome do feriado','1','1','3','30','1','1');
  Validate('w_ferias_legenda','Legenda do ferias','1','1','1','2','ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz','');
  Validate('w_ferias_nome','Nome do ferias','1','1','3','30','1','1');
  Validate('w_viagem_legenda','Legenda do viagem','1','1','1','2','ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz','');
  Validate('w_viagem_nome','Nome do viagem','1','1','3','30','1','1');
  Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  ShowHTML('  theForm.Botao.disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.\'\.$w_troca.\'\.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.focus()\';');
  } 
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
  ShowHTML('<HR>');
  ShowHTML('<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('    <table width="97%" border="0">');
  SelecaoUnidade('<U>U</U>nidade gestora de colaboradores:','U',null,$w_sq_unidade_gestao,null,'w_sq_unidade_gestao',null,null);
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Texto de aviso</td></td></tr>');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr><td valign="top"><b><u>D</u>estinatários da mensagem de entrada (separar por ponto-e-vírgula):<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="STI" type="text" name="w_admissao_destino" size="90" maxlength="100" value="'.$w_admissao_destino.'"></td>');
  ShowHTML('      <tr><td valign="top"><b><u>T</u>exto comunicando a entrada de colaborador:</b><br><textarea '.$w_Disabled.' accesskey="T" name="w_admissao_texto" class="STI" ROWS=5 cols=75 >'.$w_admissao_texto.'</TEXTAREA></td>');
  ShowHTML('      <tr><td valign="top"><b>D<u>e</u>stinatários da mensagem de saída (separar por ponto-e-vírgula):<br><INPUT ACCESSKEY="E" '.$w_Disabled.' class="STI" type="text" name="w_rescisao_destino" size="90" maxlength="100" value="'.$w_rescisao_destino.'"></td>');
  ShowHTML('      <tr><td valign="top"><b>Te<u>x</u>to comunicando a saída de colaborador:</b><br><textarea '.$w_Disabled.' accesskey="X" name="w_rescisao_texto" class="STI" ROWS=5 cols=75 >'.$w_rescisao_texto.'</TEXTAREA></td>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td align="center" height="2" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td valign="top" align="center" bgcolor="#D0D0D0"><font size="2"><b>Dados para o mapa de frequência</td></td></tr>');
  ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  ShowHTML('      <tr><td valign="top"><b>Evento</td>');
  ShowHTML('          <td valign="top"><b>Legenda</td>');
  ShowHTML('          <td valign="top"><b>Nome</td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td valign="top"><b>Feriado</td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_feriado_legenda" size="4" maxlength="2" value="'.$w_feriado_legenda.'"></td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_feriado_nome" size="20" maxlength="20" value="'.$w_feriado_nome.'"></td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td valign="top"><b>Férias</td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_ferias_legenda" size="4" maxlength="2" value="'.$w_ferias_legenda.'"></td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_ferias_nome" size="20" maxlength="20" value="'.$w_ferias_nome.'"></td>');
  ShowHTML('      </tr>');
  ShowHTML('      <tr><td valign="top"><b>Viagem</td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_viagem_legenda" size="4" maxlength="2" value="'.$w_viagem_legenda.'"></td>');
  ShowHTML('          <td valign="top"><INPUT class="STI" type="text" name="w_viagem_nome" size="20" maxlength="20" value="'.$w_viagem_nome.'"></td>');
  ShowHTML('      </tr>');
  ShowHTML('      </table>');
  ShowHTML('      <tr><td><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>');
  ShowHTML('      <tr><td align="center" colspan="3">');
  ShowHTML('            <input class="STB" type="submit" name="Botao" value="Gravar">');
  ShowHTML('          </td>');
  ShowHTML('      </tr>');
  ShowHTML('    </table>');
  ShowHTML('    </TD>');
  ShowHTML('</tr>');
  ShowHTML('</FORM>');
  ShowHTML('</table>');
  ShowHTML('</center>');
} 
// =========================================================================
// Rotina de Cargos
// -------------------------------------------------------------------------
function Cargo() {
  extract($GLOBALS);
  Global $w_Disabled;
  $w_chave          = $_REQUEST['w_chave'];

  ShowHTML('<HEAD>');
  ShowHTML('<TITLE>'.$conSgSistema.' - Listagem dos tipos de afastamento</TITLE>');
  if ($P1==2) {
    ShowHTML('<meta http-equiv="Refresh" content="300; URL='.str_replace($w_dir,'',MontaURL('MESA')).'">');
  }
  Estrutura_CSS($w_cliente);
  if ($O=='') $O='L';
  if ($w_troca>'' && $O!='E') {  
    $w_sq_tipo        = $_REQUEST['w_sq_tipo'];
    $w_sq_formacao    = $_REQUEST['w_sq_formacao'];
    $w_nome           = $_REQUEST['w_nome'];
    $w_descricao      = $_REQUEST['w_descricao'];
    $w_atividades     = $_REQUEST['w_atividades'];
    $w_competencias   = $_REQUEST['w_competencias'];
    $w_salario_piso   = $_REQUEST['w_salario_piso'];
    $w_salario_teto   = $_REQUEST['w_salario_teto'];
    $w_ativo          = $_REQUEST['w_ativo'];
  } elseif ($O=='L') {
    $RS = db_getCargo::getInstanceOf($dbms,$w_cliente,null,$w_sq_tipo,$w_nome,$w_sq_formacao,$w_ativo,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1]);
    } else {
      $RS = SortArray($RS,'nome','asc'); 
    }
  } elseif (!(strpos('AEV',$O)===false) && $w_troca=='') {
    $RS = db_getCargo::getInstanceOf($dbms,$w_cliente,$w_chave,null,null,null,null,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_chave        = f($RS,'chave');
    $w_sq_tipo      = f($RS,'sq_tipo_posto');
    $w_sq_formacao  = f($RS,'sq_formacao');
    $w_nome         = f($RS,'nome');
    $w_descricao    = f($RS,'descricao');
    $w_atividades   = f($RS,'atividades');
    $w_competencias = f($RS,'competencias');
    if (Nvl(f($RS,'salario_piso'),'')!='' && Nvl(f($RS,'salario_teto'),'')!='') {
      $w_salario_piso = number_format(f($RS,'salario_piso'),2,',','.');
      $w_salario_teto = number_Format(f($RS,'salario_teto'),2,',','.');
    } 
    $w_ativo = f($RS,'ativo');
  } if (!(strpos('IAE',$O)===false)) {
    ScriptOpen('JavaScript');
    modulo();
    FormataValor();
    ValidateOpen('Validacao');
    if (!(strpos('IA',$O)===false)) {
      Validate('w_sq_tipo','Tipo','SELECT','1','1','18','','1');
      Validate('w_sq_formacao','Formacao Acadêmica','SELECT','1','1','1000','1','');
      Validate('w_nome','Nome','1','1','3','30','1','1');
      Validate('w_descricao','Descrição','1','','5','1000','1','1');
      Validate('w_atividades','Atividades','1','','5','1000','1','1');
      Validate('w_competencias','Competência','1','','5','1000','1','1');
      Validate('w_salario_piso','Salário Piso','VALOR','',4,18,'','0123456789,.');
      Validate('w_salario_teto','Salário Teto','VALOR','',4,18,'','0123456789,.');
      ShowHTML('  if (theForm.w_salario_piso.value != \'\' && theForm.w_salario_teto.value == \'\') {');
      ShowHTML('     alert(\'Informe o teto salarial!\');');
      ShowHTML('     theForm.w_salario_teto.focus();');
      ShowHTML('     return (false);');
      ShowHTML('  }');
      ShowHTML('  if (theForm.w_salario_piso.value == \'\' && theForm.w_salario_teto.value != \'\') {');
      ShowHTML('     alert(\'Informe o piso salarial!\');');
      ShowHTML('     theForm.w_salario_piso.focus();');
      ShowHTML('     return (false);');
      ShowHTML('  }');
      CompValor('w_salario_piso','Piso salarial','<','w_salario_teto','teto salarial');
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
    BodyOpen('onLoad=document.Form.w_sq_tipo.focus();');
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
    ShowHTML('          <td><b>'.LinkOrdena('Tipo','nm_tipo_posto').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Nome','nome').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Formação','nm_formacao').'</td>');
    ShowHTML('          <td><b>'.LinkOrdena('Ativo','ativo').'</td>');
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
        ShowHTML('        <td align="left" title="'.f($row,'ds_tipo_posto').'">'.f($row,'nm_tipo_posto').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nome').'</td>');
        ShowHTML('        <td align="left">'.f($row,'nm_formacao').'</td>');
        if (f($row,'ativo')=='N') {
          ShowHTML('        <td align="center"><font color="red">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } else {
          ShowHTML('        <td align="center">'.RetornaSimNao(f($row,'ativo')).'</td>');
        } 
        ShowHTML('        <td align="top" nowrap>');
        ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'chave').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' &SG='.$SG.MontaFiltro('GET').'" Title="Nome">Alterar </A>&nbsp');
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
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    SelecaoTipoPosto2('<u>T</u>ipo:','T','Selecione o tipo de cargo.',$w_sq_tipo,null,'w_sq_tipo',null);
    ShowHTML('           </table>');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    ShowHTML('         <td colspan=2 width="100%"><table width="100%" border="0">');
    SelecaoFormacao('F<u>o</u>rmação acadêmica:','O','Selecione a formação acadêmica mínima, exigida para a ocupação do cargo.',$w_sq_formacao,'Acadêmica','w_sq_formacao',null,null);
    ShowHTML('            <td><b><u>N</u>ome:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="30" MAXLENGTH="30" VALUE="'.$w_nome.'"></td>');
    ShowHTML('           </table>');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td colspan=2><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D"  name="w_descricao" class="sti" cols="80" rows="4">'.$w_descricao.'</textarea></td>');
    ShowHTML('      <tr><td colspan=2><b><u>A</u>tividades:</b><br><textarea '.$w_Disabled.' accesskey="A"  name="w_atividades" class="sti" cols="80" rows="4">'.$w_atividades.'</textarea></td>');
    ShowHTML('      <tr><td colspan=2><b><u>C</u>ompetências:</b><br><textarea '.$w_Disabled.' accesskey="C"  name="w_competencias" class="sti" cols="80" rows="4">'.$w_competencias.'</textarea></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('         <tr><td colspan=2><table width="100%" border="0">');
    ShowHTML('          <td width="10%"><b><u>P</u>iso salarial:</b><br><input '.$w_Disabled.' accesskey="P" type="text" name="w_salario_piso" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_salario_piso.'" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('          <td><b><u>T</u>eto salarial:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_salario_teto" class="STI" SIZE="18" MAXLENGTH="18" VALUE="'.$w_salario_teto.'" onKeyDown="FormataValor(this,18,2,event);"></td>');
    ShowHTML('           </table>');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    MontaRadioSN('<b>Ativo?</b>',$w_ativo,'w_ativo');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');  
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'GPMODALCON':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          $RS = db_getGPModalidade::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_sigla'],$_REQUEST['w_nome'],null,null,'VERIFICASIGLANOME');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe modalidade com este nome ou sigla!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();  
          } 
        } elseif ($O=='E') {
          $RS = db_getGPModalidade::getInstanceOf($dbms,null,Nvl($_REQUEST['w_chave'],''),null,null,null,null,'VERIFICAMODALIDADES');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe contrato associado a esta modalidade, não sendo possível sua exclusão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();     
          } 
        } 
        echo nvl($_REQUEST['w_passagem'],'nulo');
        dml_putGPModalidade::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_nome'],$_REQUEST['w_descricao'],
        $_REQUEST['w_sigla'],$_REQUEST['w_ferias'],$_REQUEST['w_username'],$_REQUEST['w_passagem'],$_REQUEST['w_diaria'],
        $_REQUEST['w_ativo']);
                                
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
    case 'GPTPAFAST':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_sigla'],$_REQUEST['w_nome'],null,null,'VERIFICASIGLANOME');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe tipo de afastamento com este nome ou sigla!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          } 
        } elseif ($O=='E') {
          $RS = db_getGPTipoAfast::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,null,null,'VERIFICAAFASTAMENTO');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe afastamento cadastrado para este tipo!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
          } 
        } 
        dml_putGPTipoAfast::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_nome'],$_REQUEST['w_sigla'],$_REQUEST['w_limite_dias'],
        $_REQUEST['w_sexo'],$_REQUEST['w_perc_pag'],$_REQUEST['w_contagem_dias'],$_REQUEST['w_periodo'],$_REQUEST['w_sobrepoe_ferias'],
        $_REQUEST['w_ativo'],$_REQUEST['w_sq_modalidade']);
        ScriptOpen('JavaScript');
        
        //($dbms, $operacao, $p_chave, $p_cliente, $p_nome, $p_sigla, $p_limite_dias, $p_sexo, $p_percentual_pagamento, $p_contagem_dias, $p_periodo, $p_sobrepoe_ferias, $p_ativo, $p_fase) {
        
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
        } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'EODTESP':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='G') {
          // Instancia os arquivos
          for ($w_ano=strftime('%Y',(time()))-2; $w_ano<=strftime('%Y',(time()))+3; $w_ano += 1) {
            // Configura o caminho para gravação física de arquivos
            $w_caminho    = $conFilePhysical.$w_cliente.'/';
            $w_arq_evento = $w_ano.'.evt';
            $w_arq_texto  = $w_ano.'.txt';

            // Recupera as datas especiais do ano informado
            $RS = db_getDataEspecial::getInstanceOf($dbms,$w_cliente,null,$w_ano,'S',null,null,null);
            $RS = SortArray($RS,'data_formatada','asc');
            if (count($RS)>0) {
              $w_lista='';
              // Gera o arquivo que descreve as datas especiais
              if (!is_writable($w_caminho)) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'ATENÇÃO: não há permissão de escrita no diretório.\\n'.$w_caminho.'\');');
                ScriptClose();
                exit;
              } else {
                if (!$handle = fopen($w_caminho.$w_arq_evento,'w')) {
                  ScriptOpen('JavaScript');
                  ShowHTML('  alert(\'ATENÇÃO: não foi possível abrir o arquivo para escrita.\\n'.$w_caminho.$w_arq_evento.'\');');
                  ScriptClose();
                  exit;
                } else {
                  // Gera o conteúdo do arquivo
                  $w_texto = '';
                  foreach ($RS as $row) {
                    $w_data   = FormataDataEdicao(f($row,'data_formatada'));
                    $w_dia    = substr($w_data,0,2);
                    $w_mes    = substr($w_data,3,2);
                    $w_texto .= $w_mes.' '.$w_dia.' "'.f($row,'nome').f($row,'nm_expediente').'"'.chr(10).chr(13);
                    if (f($row,'expediente')!='S') $w_lista .= ', '.substr($w_data,0,5);
                  } 
                  $w_lista = substr($w_lista,2,strlen($w_lista));
  
                  // Insere o conteúdo no arquivo
                  if (!fwrite($handle, $w_texto)) {
                    ScriptOpen('JavaScript');
                    ShowHTML('  alert(\'ATENÇÃO: não foi possível inserir o conteúdo do arquivo.\\n'.$w_caminho.$w_arq_evento.'\');');
                    ScriptClose();
                    fclose($handle);
                    exit;
                  } else {
                    fclose($handle);
                  }
                }
              }
            } 
            // Gera o arquivo que indica os dias úteis e não úteis
            if (!$handle = fopen($w_caminho.$w_arq_texto,'w')) {
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'ATENÇÃO: não foi possível abrir o arquivo para escrita.\\n'.$w_caminho.$w_arq_texto.'\');');
              ScriptClose();
              exit;
            } else {
              // Gera o conteúdo do arquivo
              $w_texto = '';
              for ($w_mes=1; $w_mes<=12; $w_mes += 1) {
                $w_linha='';
                for ($w_dia=1; $w_dia<=31; $w_dia += 1) {
                  $w_data = substr(100+$w_dia,1,2).'/'.substr(100+$w_mes,1,2).'/'.$w_ano;
                  $w_date = mktime(0,0,0,$w_mes,$w_dia,$w_ano);
                  if (formataDataEdicao($w_date)==$w_data) {
                    if (date('w',$w_date)==0 || date('w',$w_date)==6 || (!(strpos($w_lista,substr($w_data,0,5))===false))) {
                      $w_linha .= '1';
                    } else {
                      $w_linha .= '0';
                    }         
                  } else {
                    $w_dia = 32;
                  } 
                } 
                $w_texto .= $w_linha.chr(10).chr(13);
              } 

              // Insere o conteúdo no arquivo
              if (!fwrite($handle, $w_texto)) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'ATENÇÃO: não foi possível inserir o conteúdo do arquivo.\\n'.$w_caminho.$w_arq_texto.'\');');
                ScriptClose();
                fclose($handle);
                exit;
              } else {
                fclose($handle);
              }
            }
          } 
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'Arquivos de calendário gerados com sucesso!\');');
          ScriptClose();
        } else {
          dml_putDataEspecial::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_sq_pais'],$_REQUEST['w_co_uf'],$_REQUEST['w_sq_cidade'],
          $_REQUEST['w_tipo'],$_REQUEST['w_data_especial'],$_REQUEST['w_nome'],$_REQUEST['w_abrangencia'],$_REQUEST['w_expediente'],
          $_REQUEST['w_ativo']);
        } 
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
    case 'GPPARAM':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        dml_putGPParametro::getInstanceOf($dbms,$w_cliente,$_REQUEST['w_sq_unidade_gestao'],$_REQUEST['w_admissao_texto'],$_REQUEST['w_admissao_destino'],$_REQUEST['w_rescisao_texto'],
        $_REQUEST['w_rescisao_destino'],$_REQUEST['w_feriado_legenda'],$_REQUEST['w_feriado_nome'],$_REQUEST['w_ferias_legenda'],$_REQUEST['w_ferias_nome'],
        $_REQUEST['w_viagem_legenda'],$_REQUEST['w_viagem_nome']);
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
        ScriptClose();
        RetornaFormulario('w_assinatura');
      } 
      break;
    case 'EOTIPPOS':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
        if ($O=='I' || $O=='A') {
          $RS = db_getCargo::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,$_REQUEST['w_nome'],null,null,'VERIFICANOME');
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Já existe cargo com este nome!\');');
            ShowHTML('  history.back(1);');
            ScriptClose();
          } 
        } elseif ($O=='E') {
          $RS = db_getCargo::getInstanceOf($dbms,$w_cliente,Nvl($_REQUEST['w_chave'],''),null,null,null,null,'VERIFICACONTRATO');                                               
          if (f($RS,'existe')>0) {
            ScriptOpen('JavaScript');
            ShowHTML('  alert(\'Existe contrato de colaborador associado a este cargo, não sendo possível sua exclusão!\');');
            ShowHTML('  history.back(1);');
            ScriptClose(); 
          } 
        } 
        dml_putCargo::getInstanceOf($dbms,$O,Nvl($_REQUEST['w_chave'],''),$_REQUEST['w_cliente'],$_REQUEST['w_sq_tipo'],$_REQUEST['w_sq_formacao'],$_REQUEST['w_nome'],
        $_REQUEST['w_descricao'],$_REQUEST['w_atividades'],$_REQUEST['w_competencias'],$_REQUEST['w_salario_piso'],$_REQUEST['w_salario_teto'],
        $_REQUEST['w_ativo']);
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
    case 'MODALIDADECONT':    ModalidadeCont();   break;
    case 'TIPOAFAST':         TipoAfast();        break;
    case 'DATAESPECIAL':      DataEspecial();     break;
    case 'PARAMETROS':        Parametros();       break;
    case 'CARGOS':            Cargo();            break;
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