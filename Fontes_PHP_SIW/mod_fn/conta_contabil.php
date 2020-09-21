<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'classes/sp/dml_putContaContabil.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');

// conta_contabil.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Integração com sistema de contabilidade
// Mail     : alex@sbpi.com.br
// Criacao  : 16/01/2020 16:12
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
$P2         = nvl($_REQUEST['P2'],3);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);

$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'conta_contabil.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

$w_copia        = $_REQUEST['w_copia'];
if ((strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) || $P2==3) {
  $p_fechado = 'all';
} else {
  $p_fechado = 'none';
}
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_ativo        = 'N';
$p_solicitante  = upper($_REQUEST['p_solicitante']);
$p_prioridade   = upper($_REQUEST['p_prioridade']);
$p_unidade      = upper($_REQUEST['p_unidade']);
$p_proponente   = upper($_REQUEST['p_proponente']);
$p_ordena       = lower($_REQUEST['p_ordena']);
$p_ini_i        = upper($_REQUEST['p_ini_i']);
$p_ini_f        = upper($_REQUEST['p_ini_f']);
$p_fim_i        = upper($_REQUEST['p_fim_i']);
$p_fim_f        = upper($_REQUEST['p_fim_f']);
$p_atraso       = upper($_REQUEST['p_atraso']);
$p_chave        = upper($_REQUEST['p_chave']);
$p_objeto       = upper($_REQUEST['p_objeto']);
$p_pais         = upper($_REQUEST['p_pais']);
$p_regiao       = upper($_REQUEST['p_regiao']);
$p_uf           = upper($_REQUEST['p_uf']);
$p_cidade       = upper($_REQUEST['p_cidade']);
$p_usu_resp     = upper($_REQUEST['p_usu_resp']);
$p_uorg_resp    = upper($_REQUEST['p_uorg_resp']);
$p_palavra      = upper($_REQUEST['p_palavra']);
$p_prazo        = upper($_REQUEST['p_prazo']);
$p_fase         = explodeArray($_REQUEST['p_fase']);
$p_sqcc         = upper($_REQUEST['p_sqcc']);
$p_sq_orprior   = $_REQUEST['p_sq_orprior'];
$p_sq_acao_ppa  = $_REQUEST['p_sq_acao_ppa'];
$p_empenho      = $_REQUEST['p_empenho'];
$p_semConta      = $_REQUEST['p_semConta'];

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
}

// Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
if (nvl($SG,'')!='') $w_menu = RetornaMenu($w_cliente,$SG);
$w_TP       = RetornaTitulo($TP, $O);

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o cliente tem o módulo de protocolo contratado
$sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,'PA');
if (count($RS)>0) $w_mod_pa='S'; else $w_mod_pa='N';

if (nvl($SG,'')!='') {
  // Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
  $sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
  if (count($RS)>0) {
    $w_submenu = 'Existe';
  } else {
    $w_submenu = '';
  }
  // Recupera a configuração do serviço
 $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de classificação dos lançamentos financeiros
// -------------------------------------------------------------------------
function Classif() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_SG = $SG;
  $w_tipo = $_REQUEST['w_tipo'];
  // Ajusta parâmetro de busca se foi indicada recuperação apenas de lançamentos sem valores por país
  if ($p_semConta=='S') $w_SG = 'FNSEMCONTA';
  
  if ($p_fim_i) {
    $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,null,$w_usuario,Nvl($_REQUEST['p_agrega'],$w_SG),$P2,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);

    $RS = SortArray($RS,'dt_pagamento','asc','ord_codigo_interno','asc');
  }
  
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    FormataCPF();
    FormataCNPJ();
    Modulo();
    SaltaCampo();
    openBox('reload');
    ValidateOpen('Validacao');
    Validate('p_sq_acao_ppa','CPF','CPF','','14','14','','0123456789-.');
    Validate('p_empenho','CNPJ','CNPJ','','18','18','','0123456789/-.');
    Validate('p_atraso','Vinculação','','','2','90','1','1');
    //Validate('p_uf','Projeto','','','2','90','1','1');    
    Validate('p_proponente','Beneficiário','','','2','90','1','');
    ShowHTML('  if ((theForm.p_fim_i.value != "" && theForm.p_fim_f.value == "") || (theForm.p_fim_i.value == "" && theForm.p_fim_f.value != "")) {');
    ShowHTML('     alert("Informe ambas as datas de conclusão ou nenhuma delas!");');
    ShowHTML('     theForm.p_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('p_fim_i','Pagamento inicial','DATA','1','10','10','','0123456789/');
    Validate('p_fim_f','Pagamento final','DATA','1','10','10','','0123456789/');
    CompData('p_fim_i','Pagamento inicial','<=','p_fim_f','Pagamento final');
    ShowHTML('  if ((theForm.p_ini_i.value != "" && theForm.p_ini_f.value == "") || (theForm.p_ini_i.value == "" && theForm.p_ini_f.value != "")) {');
    ShowHTML('     alert("Informe ambas as datas de vencimento ou nenhuma delas!");');
    ShowHTML('     theForm.p_ini_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('p_ini_i','Vencimento inicial','DATA','','10','10','','0123456789/');
    Validate('p_ini_f','Vencimento final','DATA','','10','10','','0123456789/');
    CompData('p_ini_i','Vencimento inicial','<=','p_ini_f','Vencimento final');
    Validate('p_palavra','Código lançamento','','','2','90','1','1');
    Validate('p_objeto','Finalidade/justificativa','','','2','90','1','1');
    ShowHTML('  disAll();');
    ValidateClose();
    
    ValidateOpen('Validacao1');
    
    ShowHTML('  var ind;');
    ShowHTML('  for (ind=1; ind < theForm["w_conta_debito[]"].length; ind++) {');
    Validate('["w_conta_debito[]"][ind]','Conta Débito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    Validate('["w_conta_credito[]"][ind]','Conta Crédito','','','2','25','ABCDEFGHIJKLMNOPQRSTUVWXYZ','0123456789');
    
    ShowHTML('  if ((theForm["w_conta_debito[]"][ind].value != "" && theForm["w_conta_credito[]"][ind].value == "") || (theForm["w_conta_debito[]"][ind].value == "" && theForm["w_conta_credito[]"][ind].value != "")) {');
    ShowHTML('     alert("Informe ambas as contas contábeis ou nenhuma delas!");');
    ShowHTML('     theForm["w_conta_debito[]"][ind].focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    ShowHTML('  }');
    ValidateClose();
      
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (strpos('CP',$O)!==false) BodyOpen('onLoad=\'document.focus();\'');
  else             BodyOpenClean(null);
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  if($w_tipo!='WORD') {
    if ((strpos(upper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
   }
  }

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Filtro
    ShowHTML('<tr><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('  <tr id="tr-0" bgcolor="'.$conTrBgColor.'"><td>'.colapsar(0,$p_fechado).'<b>Filtro</b>');
    ShowHTML('  <tr style="display:'.$p_fechado.'" id="tr-0-1_1" class="arvore"><td width="100%"><table border="0" width="100%" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%"><table border=0 cellspacing=0 align="center" width="97%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="p_fechado" value="'.$p_fechado.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td><table border=0 width="100%">');
    ShowHTML('      <tr valign="top">');
    SelecaoTipoLancamento('<u>T</u>ipo de lancamento:','T','Selecione na lista o tipo de lançamento adequado.',$p_sq_orprior,null,$w_cliente,'p_sq_orprior',f($RS_Menu_Origem,'sigla'),null,2);
    MontaRadioNS('<b>Exibe apenas lançamentos sem conta contábil?',$p_semConta,'p_semConta',null,null,null,1);
    ShowHTML('      <tr valign="top">');
    if (f($RS_Menu,'solicita_cc')=='S') {
      SelecaoCC('C<u>l</u>assificação:','C','Selecione um dos itens relacionados.',$p_sqcc,null,'p_sqcc','SIWSOLIC',null,3);
    }
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_sq_acao_ppa" VALUE="'.$p_sq_acao_ppa.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
    ShowHTML('          <td><b><u>C</u>NPJ:<br><INPUT ACCESSKEY="C" TYPE="text" class="sti" NAME="p_empenho" VALUE="'.$p_empenho.'" SIZE="18" MaxLength="18" onKeyDown="FormataCNPJ(this, event);">');
    ShowHTML('      <tr valign="top">');
    ShowHTML('      <tr valign="top">');
    $sql = new db_getLinkData; $l_rs = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pro<u>j</u>eto:','J','Selecione o projeto na relação.',$p_projeto,$w_usuario,f($l_rs,'sq_menu'),null,null,null,'p_projeto','PJLIST',null,2,3);
    //ShowHTML('          <td><b><U>P</U>rojeto:<br><INPUT ACCESSKEY="P" '.$w_Disabled.' class="sti" type="text" name="p_uf" size="18" maxlength="18" value="'.$p_uf.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>V</U>inculação:<br><INPUT ACCESSKEY="V" '.$w_Disabled.' class="sti" type="text" name="p_atraso" size="18" maxlength="18" value="'.$p_atraso.'"></td>');
    ShowHTML('          <td><b><U>B</U>eneficiário:<br><INPUT ACCESSKEY="U" '.$w_Disabled.' class="STI" type="text" name="p_proponente" size="25" maxlength="90" value="'.$p_proponente.'"></td>');
    ShowHTML('      <tr valign="top">');
    SelecaoContaBanco('C<u>o</u>nta bancária:','O','Selecione a conta bancária envolvida no lançamento.',$p_pais,null,'p_pais',null,null);
    ShowHTML('          <td><b>Paga<u>m</u>ento entre:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    if ($w_segmento=='Público' || $w_mod_pa=='S') {
      ShowHTML('          <td><b>Protocolo:<br><INPUT class="STI" type="text" name="p_regiao" style="text-align:right;" size="7" maxlength="6" value="' . $p_regiao . '">/<INPUT class="STI" type="text" name="p_cidade" size="4" maxlength="4" value="' . $p_cidade . '"></td>');
      ShowHTML('      <tr valign="top">');
    }
    ShowHTML('          <td><b>Ven<u>c</u>imento entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_i').' e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_ini_f').'</td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Có<U>d</U>igo lançamento:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_palavra" size="18" maxlength="18" value="'.$p_palavra.'"></td>');
    ShowHTML('          <td><b><U>F</U>inalidade/justificativa:<br><INPUT ACCESSKEY="F" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('  </td></tr></table>');
    ShowHTML('</td></tr></table>');

    ShowHTML('<tr><td colspan=3 align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');

    $item = 0; // Variável para controle da conta contábil de cada registro
    
    AbreForm('Form1',$w_dir.$w_pagina.'Grava','POST','return(Validacao1(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);     
    ShowHTML(MontaFiltro('POST')); 
    ShowHTML('<INPUT type="hidden" name="w_chave[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_conta_debito[]" value="">');
    ShowHTML('<INPUT type="hidden" name="w_conta_credito[]" value="">');

    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan = 0;
    $colspan++; ShowHTML('          <td rowspan="2"><b>Código</td>');
    //$colspan++; ShowHTML('          <td rowspan="2"><b>Lançamento</td>');
    if ($w_segmento=='Público' || $w_mod_pa=='S') {
      $colspan++; ShowHTML('          <td rowspan="2"><b>Protocolo</font></td>');
    }
    $colspan++; ShowHTML('          <td rowspan="2"><b>Dt. Pag.</td>');
    $colspan++; ShowHTML('          <td rowspan="2"><b>Beneficiário</td>');
    ShowHTML('          <td colspan="3"><b>Documento</td>');
    ShowHTML('          <td rowspan=2 width="1%">&nbsp;</td>');
    //ShowHTML('          <td rowspan="2"><b>Vinculação</td>');
    //ShowHTML('          <td rowspan="2"><b>Projeto</td>');
    //ShowHTML('          <td rowspan="2"><b>Conta Débito</td>');
    ShowHTML('          <td rowspan="2"><b>Histórico</td>');
    ShowHTML('          <td colspan="2"><b>Conta Contábil</td>');
    ShowHTML('        </tr>');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    $colspan++; ShowHTML('          <td><b>Tipo</td>');
    $colspan++; ShowHTML('          <td><b>Número</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Débito</td>');
    ShowHTML('          <td><b>Crédito</td>');
    ShowHTML('        </tr>');
    if (!count($RS)) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=15 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $w_parcial = array();
      
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if (f($row,'sg_tramite')=='PP') {
          ShowHTML('      <tr bgcolor="'.$conTrBgColorLightRed1.'" valign="top">');
          $w_alerta = true;
        } else {
          ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        }
        ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno'),'N',$w_tipo).'</td>');
        //ShowHTML('        <td>'.f($row,'nome'));
        if ($w_mod_pa=='S') {
          if ($w_embed!='WORD' && nvl(f($row,'protocolo_siw'),'')!='') {
            ShowHTML('        <td align="right"><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($row,'protocolo').'&nbsp;</a>');
          } else {
            ShowHTML('        <td align="right">'.f($row,'protocolo'));
          }
        }
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'dt_pagamento'),5),'-').'</td>');
        if (f($row,'sigla')=='FNATRANSF') {
          // Transferência entre contas
          ShowHTML('        <td colspan="3">'.f($row,'nm_banco').' '.f($row,'numero_conta').'</td>');
        } elseif (substr(f($row,'sigla'),0,3)=='FNA' || f($row,'sigla')=='FNDTARIFA') {
          ShowHTML('        <td colspan="3">'.f($row,'nm_banco_debito').'</td>');
        } else {
          if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
            if ($w_tipo!='WORD') ShowHTML('        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>');
            else                 ShowHTML('        <td>'.f($row,'nm_pessoa').'</td>');
          } else {
            ShowHTML('        <td align="center">---</td>');
          }
          ShowHTML('        <td title="'.f($row,'nm_doc').'">'.f($row,'sg_doc').'</td>');
          ShowHTML('        <td>'.f($row,'nr_doc').'</td>');
        }
        ShowHTML('        <td align="right" nowrap>'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor')).'&nbsp;</td>');
        $w_valor = nvl(((f($row,'sg_tramite')=='AT') ? f($row,'valor_atual') : f($row,'valor')),0);
        if     (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') $w_valor == $w_valor;
        elseif (substr(f($row,'sigla'),2,1)=='D') $w_valor = -1 * $w_valor;
        $w_parcial[f($row,'sb_moeda')] = nvl($w_parcial[f($row,'sb_moeda')],0) + $w_valor;

        if ($w_valor==0 || f($row,'sigla')=='FNATRANSF')                          { ShowHTML('          <td width="1%" nowrap>&nbsp;</td>'); }
        elseif (substr(f($row,'sigla'),2,1)=='R' || f($row,'sigla')=='FNAAPLICA') { ShowHTML('          <td width="1%" nowrap align="center"><b>+</b></td>'); }
        elseif (substr(f($row,'sigla'),2,1)=='D') { ShowHTML('          <td width="1%" nowrap align="center"><b>-</b></td>'); }

        /*
        $w_pai_projeto = false;
        if (Nvl(f($row,'dados_pai'),'')!='') {
          $w_pai = explode('|@|',f($row,'dados_pai'));
          if ($w_pai[0]=='???') {
            //ShowHTML('        <td>&nbsp;</td>');
          } else {
            if ($w_pai[11]=='PR') {
              $w_pai_projeto = true;
              ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
            }
            ShowHTML('        <td>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
          }
        } else {
          ShowHTML('        <td>&nbsp;</td>');
        }

        if (!$w_pai_projeto && Nvl(f($row,'dados_avo'),'')!='') {
          $w_avo = explode('|@|',f($row,'dados_avo'));
          if ($w_avo[11]=='PR') {
            ShowHTML('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_avo'),f($row,'dados_avo'),'N',$w_tipo).'</td>');
          } else {
            ShowHTML('        <td nowrap>&nbsp;</td>');
          }
        }
        */
        
        //if (nvl(f($row,'conta_debito'),'')!='') {
        //  ShowHTML('        <td>'.f($row,'nm_banco_debito').' '.f($row,'conta_debito').((nvl(f($row,'sg_moeda_cc'),'')=='') ? '' : ' ('.f($row,'sg_moeda_cc').')').'</td>');
        //} else {
        //  ShowHTML('        <td>&nbsp;</td>');
        //}
        
        ShowHTML('        <td>'.f($row,'descricao'));
      
        $item++;
        ShowHTML('        <td><INPUT type="hidden" name="w_chave[]" value="'.f($row,'sq_siw_solicitacao').'">');
        ShowHTML('            <input type="text" name="w_conta_debito[]" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.nvl($_POST['w_conta_debito'][$item],f($row,'cc_debito')).'"></td>');
        ShowHTML('        <td><input type="text" name="w_conta_credito[]" class="sti" SIZE="11" MAXLENGTH="25" VALUE="'.nvl($_POST['w_conta_credito'][$item],f($row,'cc_credito')).'"></td>');
        
        
        ShowHTML('      </tr>');
      }
      
      // Exibe totais
      if (ceil(count($RS)/$P4)>1) {
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" valign="top">');
        ShowHTML('          <td align="right" colspan="'.$colspan.'"><b>Tota'.((count($w_parcial)==1) ? 'l' : 'is').'</td>');
        ShowHTML('          <td align="right" nowrap><b>');
        $i = 0;
        ksort($w_parcial);
        foreach($w_parcial as $k => $v) { echo((($i) ? '<div></div>' : '').$k.' '.formatNumber(abs($v),2)); $i++; }
        echo('</td>');

        ShowHTML('          <td align="center"><b>');
        $i = 0;
        foreach($w_parcial as $k => $v) { echo((($i) ? '<br>' : '').(($v>0) ? '+' : (($v<0) ? '-' : ''))); $i++; }
        echo('</td>');

        ShowHTML('          <td colspan="3">&nbsp;</td>');
        ShowHTML('        </tr>');
      }

      ShowHTML('      <tr><td align="center" colspan="10" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="10">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      ShowHTML('          </td>');

    }
    
    ShowHTML('</FORM>');

    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Rotina de geração  de arquivo para o sistema Dexion (OTCA)
// -------------------------------------------------------------------------
function Dexion() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_SG = $SG;
  $w_tipo = $_REQUEST['w_tipo'];
  // Ajusta parâmetro de busca se foi indicada recuperação apenas de lançamentos sem valores por país
  if ($p_semConta=='S') $w_SG = 'FNSEMCONTA';
  
  if ($p_fim_i) {
    $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,'CONTABIL',$P2,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);

    $RS = SortArray($RS,'quitacao','asc','ord_codigo_interno','asc','ordem','asc');
  }
  
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Consulta de '.f($RS_Menu,'nome'),0);
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ShowHTML('</HEAD>');
  } else {
    Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    FormataCPF();
    FormataCNPJ();
    Modulo();
    SaltaCampo();
    openBox('reload');
    ValidateOpen('Validacao');
    ShowHTML('  if ((theForm.p_fim_i.value != "" && theForm.p_fim_f.value == "") || (theForm.p_fim_i.value == "" && theForm.p_fim_f.value != "")) {');
    ShowHTML('     alert("Informe ambas as datas de conclusão ou nenhuma delas!");');
    ShowHTML('     theForm.p_fim_i.focus();');
    ShowHTML('     return false;');
    ShowHTML('  }');
    Validate('p_fim_i','Pagamento inicial','DATA','1','10','10','','0123456789/');
    Validate('p_fim_f','Pagamento final','DATA','1','10','10','','0123456789/');
    CompData('p_fim_i','Pagamento inicial','<=','p_fim_f','Pagamento final');
    ShowHTML('  disAll();');
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
  elseif ($O=='I') BodyOpen('onLoad=\'document.Form.w_smtp_server.focus();\'');
  elseif ($O=='A') BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  elseif ($O=='E') BodyOpen('onLoad=\'document.Form.w_assinatura.focus();\'');
  elseif (strpos('CP',$O)!==false) BodyOpen('onLoad=\'document.focus();\'');
  else             BodyOpenClean(null);
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  if($w_tipo!='WORD') {
    if ((strpos(upper($R),'GR_'))===false) {
      Estrutura_Texto_Abre();
    } else {
      CabecalhoRelatorio($w_cliente,'Consulta de '.f($RS_Menu,'nome'),4);
   }
  }

  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    // Filtro
    ShowHTML('<tr><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
    ShowHTML('  <tr id="tr-0" bgcolor="'.$conTrBgColor.'"><td>'.colapsar(0,$p_fechado).'<b>Filtro</b>');
    ShowHTML('  <tr style="display:'.$p_fechado.'" id="tr-0-1_1" class="arvore"><td width="100%"><table border="0" width="100%" bgcolor="'.$conTrBgColor.'">');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td width="100%"><table border=0 cellspacing=0 align="center" width="97%">');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="p_fechado" value="'.$p_fechado.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('      <tr><td><table border=0 width="100%">');
    ShowHTML('      <tr valign="top">');
    SelecaoContaBanco('C<u>o</u>nta bancária:','O','Selecione a conta bancária envolvida no lançamento.',$p_pais,null,'p_pais',null,null);
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b>Paga<u>m</u>ento entre:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_i').' e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','p_fim_f').'</td>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
    ShowHTML('    </table>');
    ShowHTML('    <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('  </td></tr></table>');
    ShowHTML('</td></tr></table>');

    ShowHTML('<tr><td colspan=3 align="right"><b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');

    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>Centro de Custo</td>');
    ShowHTML('          <td><b>Localização do Documento</td>');
    ShowHTML('          <td><b>Parceiro</td>');
    ShowHTML('          <td><b>Conta de Débito</td>');
    ShowHTML('          <td><b>Conta de Crédito</td>');
    ShowHTML('          <td><b>Histórico Padrão</td>');
    ShowHTML('          <td><b>Débito Conciliado</td>');
    ShowHTML('          <td><b>Crédito Conciliado</td>');
    ShowHTML('          <td><b>Complemento de Histórico</td>');
    ShowHTML('          <td><b>Data do Lançamento</td>');
    ShowHTML('          <td><b>Valor</td>');
    ShowHTML('          <td><b>Observação</td>');
    ShowHTML('          <td><b>Pendente</td>');
    ShowHTML('          <td><b>Registro</td>');
    ShowHTML('        </tr>');
    if (!count($RS)) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=14 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      $i = 0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>'.f($row,'cc_debito'));
        ShowHTML('        <td>'.f($row,'cc_credito'));
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>'.f($row,'ds_financeiro'));
        ShowHTML('        <td align="center">'.str_replace('/','',formataDataEdicao(f($row,'quitacao'),5)).'</td>');
        ShowHTML('        <td align="right">'.str_replace('.','',formatNumber(f($row,'brl_valor_compra'))).'</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td>&nbsp;</td>');
        ShowHTML('        <td align="center" nowrap>'.exibeSolic($w_dir,f($row,'sq_financeiro'),f($row,'cd_financeiro'),'N',$w_tipo).'-'.f($row,'ordem').'</td>');
        ShowHTML('      </tr>');
        if (nvl(f($row,'cc_debito'),'')=='') $i++;
      }
      
      AbreForm('Form1',$w_dir.$w_pagina.'GeraDexion','POST',null,null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);     
      ShowHTML(MontaFiltro('POST')); 

      ShowHTML('      <tr><td align="center" colspan="14" height="1" bgcolor="#000000"></TD></TR>');
      ShowHTML('      <tr><td align="center" colspan="14">');
      ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gerar Arquivo" onClick="'.(($i) ? 'alert(\'ATENÇÃO: EXISTEM LANÇAMENTOS SEM AS CONTAS CONTÁBEIS!\'); ' : '').'return(confirm(\'Confirma geração do arquivo?\'));">');
      ShowHTML('          </td>');
    
      ShowHTML('</FORM>');

    }

    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Opção não disponível");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
}

// =========================================================================
// Gera arquivo de exportação para o Dexion
// -------------------------------------------------------------------------
function GeraDexion() {
  extract($GLOBALS);
  
  $p_fim_i = $_REQUEST['p_fim_i'];
  $p_fim_f = $_REQUEST['p_fim_f'];


  $sql = new db_getSolicFN; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,'CONTABIL',$P2,
        $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
        $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
        $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, $p_sq_acao_ppa, $p_sq_orprior, $p_empenho);

  $RS = SortArray($RS,'quitacao','asc','ord_codigo_interno','asc','ordem','asc');
  
  
  header('Content-type: plain/text',false);
  header('Content-Disposition: attachment; filename=arquivo.txt');
  header('Cache-Control: no-cache, must-revalidate',false);

  foreach($RS as $row) {
    echo "|";
    echo "|";
    echo "|";
    echo "|";
    echo f($row,'cc_debito')."|";
    echo f($row,'cc_credito')."|";
    echo "|";
    echo "|";
    echo "|";
    echo str_replace(chr(13).chr(10),' ',f($row,'ds_financeiro'))."|";
    echo str_replace('/','',formataDataEdicao(f($row,'quitacao'),5))."|";
    echo str_replace('.','',formatNumber(f($row,'brl_valor_compra')))."|";
    echo "|";
    echo "|";
    ShowHTML(f($row,'cd_financeiro').'-'.f($row,'ordem')."|"); // Usa ShowHTML para quebrar a linha
  }
  
} 


// =========================================================================
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</head>');
  BodyOpen('onLoad=this.focus();');
  if ($SG=='FNCONTAB') {
    // Verifica se a Assinatura Eletrônica é válida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      
      // Grava contas contábeis
      $SQL = new dml_putContaContabil;
      for ($i=1; $i<count($_POST['w_conta_debito']); $i++) {
        $SQL->getInstanceOf($dbms,$w_usuario,$_REQUEST['w_chave'][$i],$_REQUEST['w_conta_debito'][$i],$_REQUEST['w_conta_credito'][$i]);
      }

      ScriptOpen('JavaScript');
      ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inválida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    }
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert("Bloco de dados não encontrado: '.$SG.'");');
    ShowHTML('  location.href=\''.montaURL_JS($w_dir,$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';');
    ScriptClose();
  }
}
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'CLASSIF':         Classif();          break;
    case 'DEXION':          Dexion();           break;
    case 'GERADEXION':      GeraDexion();       break;
    case 'GRAVA':           Grava();            break;
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