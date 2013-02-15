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
include_once($w_dir_volta.'classes/sp/db_getUserData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicApoioList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getCronograma.php'); 
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');  
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');  
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicApoio.php');
include_once($w_dir_volta.'classes/sp/dml_putCronogramaApoio.php');
include_once($w_dir_volta.'funcoes/selecaoTipoApoio.php');

// =========================================================================
//  /fonte.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerenciar as fontes de financiamento
// Mail     : alex@sbpi.com.br
// Criacao  : 10/10/2012, 10:48
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
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);

$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina     = 'fonte.php?par=';
$w_Disabled   = 'ENABLED';
$w_dir        = 'mod_pr/';
$w_troca      = $_REQUEST['w_troca'];
$p_ordena     = $_REQUEST['p_ordena'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') EncerraSessao();

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($SG=='APOIOSOLIC') {
  if ($O!='I' && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif ($O=='') {
  $O='L';
}


switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';        break;
  case 'A': $w_TP=$TP.' - Alteração';       break;
  case 'E': $w_TP=$TP.' - Exclusão';        break;
  case 'P': $w_TP=$TP.' - Filtragem';       break;
  case 'C': $w_TP=$TP.' - Cópia';           break;
  case 'V': $w_TP=$TP.' - Envio';           break;
  case 'M': $w_TP=$TP.' - Pacotes';         break;
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

// Recupera os dados da opção selecionada
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
Main();
FechaSessao($dbms); 
exit;

// =========================================================================
// Rotina de cadastramento de fontes de financiamento
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,'PJGERAL');
  $w_cabecalho   = f($RS_Solic,'titulo').' ('.$w_chave.')';  
  $w_solicitante = f($RS_Solic,'solicitante');
  $w_titular     = f($RS_Solic,'titular');
  $w_substituto  = f($RS_Solic,'substituto');
  $w_executor    = f($RS_Solic,'executor');
  $w_tit_exec    = f($RS_Solic,'tit_exec');
  $w_subst_exec  = f($RS_Solic,'subst_exec');
  
  if ($P1==1 || $P1==2) {
    $w_edita = true;
  } else {
    $w_edita = false;
  }

  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
    $w_pessoa_atualizacao  = $_REQUEST['w_pessoa_atualizacao'];
    $w_tipo_apoio          = $_REQUEST['w_tipo_apoio'];
    $w_entidade            = $_REQUEST['w_entidade'];
    $w_descricao           = $_REQUEST['w_descricao'];
    $w_valor               = $_REQUEST['w_valor'];
  } elseif ($O=='L') {
    // Recupera todos os registros para a listagem
    $sql = new db_getSolicApoioList; $RS = $sql->getInstanceOf($dbms,$w_chave,null,null);
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'entidade','asc','sq_solic_apoio','asc');
    } else {
      $RS = SortArray($RS,'entidade','asc','sq_solic_apoio','asc');
    }
  } elseif (strpos('AEV',$O)!==false) {
    // Recupera os dados do endereço informado
    $sql = new db_getSolicApoioList; $RS = $sql->getInstanceOf($dbms,$w_chave,$w_chave_aux,null);
    foreach ($RS as $row) {$RS = $row; break;}
    $w_pessoa_atualizacao   = f($RS,'sq_pessoa_atualizacao');
    $w_tipo_apoio           = f($RS,'sq_tipo_apoio');
    $w_entidade             = f($RS,'entidade');
    $w_descricao            = f($RS,'descricao');
    $w_valor                = formatNumber(f($RS,'valor'));
  }  

  // Indicador para detalhamento do cronograma desembolso
  $w_cronograma = false;
  // Recupera as rubricas do projeto
  $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,$w_chave,null,'S',null,null,null,null,null,null);
  $w_total = 0;
  foreach ($RSQuery as $row) {
    if (f($row,'ultimo_nivel')=='N') continue;
    $w_total += f($row,'total_previsto');
    $sql = new db_getCronograma; $RSQuery_Cronograma = $sql->getInstanceOf($dbms,f($row,'sq_projeto_rubrica'),null,null,null,$w_chave_aux,'CADFONTE');
    if (count($RSQuery_Cronograma)) {
      $w_cronograma = true;
      if ($O!='L') break;
    }
    if ($O!='L' && $w_cronograma) break;
  }

  Cabecalho();
  head();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if (strpos('IAEP',$O)!==false) {
    ScriptOpen('JavaScript');
    ShowHTML('function igualar(ind) {');
    ShowHTML('  var obj = document.Form;');
    ShowHTML('  if (ind>0) {');
    ShowHTML('    obj["w_valor[]"][ind].value = obj["w_limite[]"][ind].value;');
    ShowHTML('  } else {');
    ShowHTML('    var i;');
    ShowHTML('    for (i=1; i < obj["w_cronograma[]"].length; i++) {');
    ShowHTML('      obj["w_valor[]"][i].value = obj["w_limite[]"][i].value;');
    ShowHTML('    }');
    ShowHTML('  }');
    ShowHTML('}');
    FormataValor();
    ValidateOpen('Validacao');
    if (strpos('IA',$O)!==false) {
      Validate('w_entidade','Entidade','','1','2','50','1','1');
      Validate('w_tipo_apoio','Classificação','SELECT','1','1','18','','1');
      Validate('w_descricao','Descrição','','','2','200','1','1');
      if ($w_cronograma) {
        // Se o projeto tem cronograma desembolso, executa verificações
        ShowHTML('  for (ind=1; ind < theForm["w_cronograma[]"].length; ind++) {');
        Validate('["w_valor[]"][ind]','Valor','VALOR','1',4,18,'','0123456789.,');
        CompValor('["w_valor[]"][ind]','Valor','>=','0','zero');
        CompValor('["w_valor[]"][ind]','Valor da fonte','<=','["w_limite[]"][ind]','orçamento disponível');
        ShowHTML('  }');
      }
    } elseif ($O=='E') {
      if ($P1==2) {
        Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','6','30','1','1');
        ShowHTML('  if (confirm("Confirma a exclusão deste registro?"));');
        ShowHTML('     { return (true); }; ');
        ShowHTML('     { return (false); }; ');
      }
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
    BodyOpen('onLoad=\'document.Form.w_entidade.focus()\';');
  } else {
    BodyOpenClean(null);
  } 
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($P1!=1) {
    ShowHTML('<div align=center><center>');
    ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
    ShowHTML('<tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.$w_cabecalho.'</b></font></div></td></tr>');
    ShowHTML('<tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>');
  }  
  if ($O=='L') {
    ShowHTML('<tr><td colspan=3 bgcolor="'.$conTrBgColorLightBlue2.'"" style="border: 2px solid rgb(0,0,0);">');
    ShowHTML('  Orientação:');
    // Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    if ($w_edita) {
      ShowHTML('    <ul>');
      ShowHTML('    <li>Insira cada uma das fontes de financiamento associadas ao projeto, usando a operação "Alterar" para atualizar sua situação.');
      ShowHTML('    </ul></b></font></td>');
      ShowHTML('<tr><td>');
      ShowHTML('  <a accesskey="I" class="SS" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=I&w_chave='.$w_chave.'&w_problema='.$w_problema.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'"><u>I</u>ncluir</a>&nbsp;');
    } else {
      ShowHTML('<tr><td>');
    }
    if ($P1==2) {
      ShowHTML('        <a accesskey="F" class="ss" HREF="javascript:this.status.value;" onClick="window.close(); opener.focus();"><u>F</u>echar</a>&nbsp;');
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    $cs = 0;
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $cs++; ShowHTML('          <td>'.linkOrdena('Entidade','entidade').'</td>');
    $cs++; ShowHTML('          <td>'.linkOrdena('Tipo','nm_tipo_apoio').'</td>');
    $cs++; ShowHTML('          <td>'.linkOrdena('Descrição','descricao').'</td>');
    $cs++; ShowHTML('          <td>'.linkOrdena('Valor','valor').'</td>');
    $cs++; ShowHTML('          <td>'.linkOrdena('%','valor').'</td>');
    $cs++; ShowHTML('          <td class="remover"><b>Operações</td>');
    ShowHTML('        </tr>');
    if (count($RS)==0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan='.$cs.' align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      $w_soma = 0;
      foreach ($RS as $row) {
        $w_soma += f($row,'valor');
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>'.f($row,'entidade').'</td>');
        ShowHTML('        <td>'.f($row,'nm_tipo_apoio').'</td>');
        ShowHTML('        <td>'.crlf2br(f($row,'descricao')).'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'</td>');
        ShowHTML('        <td align="center">'.(($w_total>0) ? formatNumber((f($row,'valor')/$w_total*100),1) : '&nbsp;').'</td>');
        ShowHTML('        <td class="remover" align="top" nowrap>');
        if ($w_edita || f($row,'sq_pessoa')==$w_usuario) {
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=A&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_solic_apoio').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Alterar">AL</A>&nbsp');
          ShowHTML('          <A class="HL" HREF="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=E&w_chave='.$w_chave.'&w_chave_aux='.f($row,'sq_solic_apoio').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Excluir">EX</A>&nbsp');
        } else {
          ShowHTML('          ---');
        }
        ShowHTML('        </td>');
        ShowHTML('      </tr>');
      }
      if (count($RS)) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td colspan="3" align="right"><b>Total</b></td>');
        ShowHTML('        <td align="right"><b>'.formatNumber($w_soma).'</b></td>');
        ShowHTML('        <td align="center"><b>'.(($w_total>0) ? formatNumber(($w_soma/$w_total*100),1) : '&nbsp;').'</b></td>');
        ShowHTML('        <td class="remover">&nbsp;</td>');
        ShowHTML('      </tr>');
      }
    } 
    ShowHTML('      </center>');
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) $w_Disabled   = ' DISABLED ';
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_aux" value="'.$w_chave_aux.'">');
    ShowHTML('<INPUT type="hidden" name="w_vl_fonte" value="0,00">');
    ShowHTML('<INPUT type="hidden" name="w_cronograma[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_previsto[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_limite[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_valor[]" value="">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('    <table width="97%" border="0">');
    ShowHTML('      <tr><td><b><u>E</u>ntidade:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_entidade" class="sti" SIZE="50" MAXLENGTH="50" VALUE="'.$w_entidade.'"></td>');
    ShowHTML('      <tr>');
    SelecaoTipoApoio('<U>C</U>lassificação:','C','Selecione a classificação da fonte de financiamento.',$w_tipo_apoio,$w_cliente,'w_tipo_apoio',null,null);
    ShowHTML('      <tr><td colspan="3"><b><u>D</u>escrição:</b><br><textarea '.$w_Disabled.' accesskey="D" name="w_descricao" class="STI" ROWS=5 cols=75 title="Descrição da questão.">'.$w_descricao.'</TEXTAREA></td>');
    // Participação da fonte, por item do cronograma desembolso
    if (count($RSQuery)) {
      ShowHTML('      <tr><td colspan="3"><b>Participação:</b></td></tr>');
      ShowHTML('      <tr><td align="center" colspan="3">');
      ShowHTML('        <table border="1" bordercolor="#00000">');
      ShowHTML('          <tr align="center">');
      ShowHTML('            <td rowspan=2 bgColor="#f0f0f0" width="1%" nowrap><b>Código</td>');
      ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>Nome</td>');
      ShowHTML('            <td rowspan=2 bgColor="#f0f0f0"><b>Período</td>');
      ShowHTML('            <td colspan=5 bgColor="#f0f0f0"><b>Valor'.((nvl(f($RS_Solic,'sb_moeda'),'')!='') ? ' ('.f($RS_Solic,'sb_moeda').')' : '').'</td>');
      ShowHTML('          </tr>');  
      ShowHTML('          <tr align="center">');
      ShowHTML('            <td bgColor="#f0f0f0"><b>Orçamento</td>');
      ShowHTML('            <td bgColor="#f0f0f0"><b>Outras fontes</td>');
      ShowHTML('            <td bgColor="#f0f0f0"><b>Dísponível</td>');
      ShowHTML('            <td bgColor="#f0f0f0"><b><input type="button" class="BTM" value="=" onClick="javascript:igualar(0);"></td>');
      ShowHTML('            <td bgColor="#f0f0f0"><b>Esta fonte</td>');
      ShowHTML('          </tr>');
      $w_cor=$conTrBgColor;
      $RSQuery = SortArray($RSQuery,'codigo','asc');
      $i = 1;
      foreach ($RSQuery as $row) {
        if (f($row,'ultimo_nivel')=='N') continue;
        $sql = new db_getCronograma; $RSQuery_Cronograma = $sql->getInstanceOf($dbms,f($row,'sq_projeto_rubrica'),null,null,null,$w_chave_aux,'CADFONTE');
        $RSQuery_Cronograma = SortArray($RSQuery_Cronograma,'inicio', 'asc', 'fim', 'asc');
        if (count($RSQuery_Cronograma)) $w_rowspan = 'rowspan="'.count($RSQuery_Cronograma).'"'; else $w_rowspan = '';
        ShowHTML('      <tr valign="top">');
        ShowHTML('        <td '.$w_rowspan.'>'.f($row,'codigo').'&nbsp;');
        ShowHTML('        <td '.$w_rowspan.'>'.f($row,'nome').' </td>');
        if (count($RSQuery_Cronograma)) {
          foreach ($RSQuery_Cronograma as $row1) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            ShowHTML('        <INPUT type="hidden" name="w_cronograma[]" value="'.f($row1,'sq_rubrica_cronograma').'">');
            ShowHTML('        <INPUT type="hidden" name="w_previsto[]" value="'.formatNumber(f($row1,'valor_previsto')).'">');
            ShowHTML('        <INPUT type="hidden" name="w_limite[]" value="'.formatNumber(f($row1,'valor_previsto')-f($row1,'vl_prev_outras')).'">');
            ShowHTML('        <td align="center" bgcolor="'.$w_cor.'">'.FormataDataEdicao(f($row1,'inicio'),5).' a '.FormataDataEdicao(f($row1,'fim'),5).'</td>');
            ShowHTML('        <td align="right" bgcolor="'.$w_cor.'">'.formatNumber(f($row1,'valor_previsto')).'</td>');
            ShowHTML('        <td align="right" bgcolor="'.$w_cor.'">'.formatNumber(f($row1,'vl_prev_outras')).'</td>');
            ShowHTML('        <td align="right" bgcolor="'.$w_cor.'">'.formatNumber(f($row1,'valor_previsto')-f($row1,'vl_prev_outras')).'</td>');
            ShowHTML('        <td align="center"><b><input type="button" class="BTM" value="=" onClick="javascript:igualar('.$i.');"></td>');
            ShowHTML('        <td align="center" bgcolor="'.$w_cor.'"><input type="text" '.$w_Disabled.' name="w_valor[]" class="sti" SIZE="15" MAXLENGTH="18" VALUE="'.formatNumber(f($row1,'vl_fonte_prev')).'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor da participação da fonte."></td>');
            ShowHTML('      </tr>');
            $i++;
          }
        }
      } 
      ShowHTML('        </table>');
    }
    if ($P1!=1){
      ShowHTML('      <tr valign="top">');
      ShowHTML('      <tr><td align="LEFT" colspan=3><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>'); 
    }
    ShowHTML('      <tr><td align="center" colspan=3><hr>');
    switch ($O) {
      case 'I':  $label = 'Incluir';   break;
      case 'A':  $label = 'Atualizar'; break;
      case 'E':  $label = 'Excluir';   break;
    } 
    ShowHTML('   <input class="STB" type="submit" name="Botao" value="'.$label.'">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$w_chave.'&w_problema='.$w_problema.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Cancelar">');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  } 
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Rodape();
} 

// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  global $w_Disabled;
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  switch ($SG) {
    case 'APOIOSOLIC':
      // Verifica se a Assinatura Eletrônica é válida
      if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
        $SQL = new dml_putSolicApoio; $SQL->getInstanceOf($dbms,$O,$_REQUEST['w_chave'],Nvl($_REQUEST['w_chave_aux'],''), 
              $_REQUEST['w_tipo_apoio'], $_REQUEST['w_entidade'],$_REQUEST['w_descricao'],$_REQUEST['w_vl_fonte'],$w_usuario); 
        
        if ($O!='E') {
          if ($O=='I') {
            // Recupera a chave da entidade inserida a partir do nome
            $sql = new db_getSolicApoioList; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],$_REQUEST['w_chave_aux'],null);
            foreach($RS as $row) {
              if (f($row,'entidade')==$_REQUEST['w_entidade']) {
                $w_chave = f($row,'sq_solic_apoio');
                break;
              }
            }
          } else {
            $w_chave = $_REQUEST['w_chave_aux'];
          }
          // Grava o cronograma desembolso da fonte de financiamento
          $SQL = new dml_putCronogramaApoio; 
          for ($i=0; $i<=count($_POST['w_cronograma'])-1; $i=$i+1) {
            if ($_REQUEST['w_cronograma'][$i]>'') {
              $SQL->getInstanceOf($dbms,'I',$_POST['w_cronograma'][$i],$w_chave,$_POST['w_valor'][$i],null);
            }
          } 
        }
        ScriptOpen('JavaScript');
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
        ScriptClose();
      } else {
        ScriptOpen('JavaScript');
        fonte.phpShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
        ScriptClose();
        retornaFormulario('w_assinatura');
      } 
      break;
    default:
      exibevariaveis();
      ScriptOpen('JavaScript');
      ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
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
    case 'INICIAL':            Inicial();          break;
    case 'GRAVA':              Grava();            break;
    default:
    Cabecalho();
    head();
    ShowHTML('<BASE HREF="'.$conRootSIW.'"></HEAD>');
    BodyOpen('onLoad=this.focus();');
    Estrutura_Texto_Abre();
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    exibevariaveis();
    break;
  } 
} 
?>