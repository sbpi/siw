<?php
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'jscript.php');
include_once($w_dir_volta.'funcoes.php');
include_once($w_dir_volta.'classes/db/abreSessao.php');
include_once($w_dir_volta.'classes/sp/db_getCcData.php');
include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuData.php');
include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerSite.php');
include_once($w_dir_volta.'classes/sp/db_getSolicList.php');
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getBankData.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
include_once($w_dir_volta.'classes/sp/db_getBenef.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoValor.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAnexo.php');
include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
include_once($w_dir_volta.'classes/sp/db_getSolicAcesso.php');
include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
include_once($w_dir_volta.'classes/sp/db_getTramiteData.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoIncid.php');
include_once($w_dir_volta.'classes/sp/db_getImpostoDoc.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getCountryData.php');
include_once($w_dir_volta.'classes/sp/db_getRegionData.php');
include_once($w_dir_volta.'classes/sp/db_getStateData.php');
include_once($w_dir_volta.'classes/sp/db_getCityData.php');
include_once($w_dir_volta.'classes/sp/db_getPersonData.php');
include_once($w_dir_volta.'classes/sp/db_getUorgResp.php');
include_once($w_dir_volta.'classes/sp/db_getVincKindList.php');
include_once($w_dir_volta.'classes/sp/db_getKindPersonList.php');
include_once($w_dir_volta.'classes/sp/db_getLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/db_getAcordoNota.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroGeral.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoOutra.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoDoc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putSolicEnvio.php');
include_once($w_dir_volta.'classes/sp/dml_putFinanceiroConc.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoItem.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoRubrica.php');
include_once($w_dir_volta.'classes/sp/dml_putLancamentoValor.php');
include_once($w_dir_volta.'classes/sp/db_getTipoLancamento.php');
include_once($w_dir_volta.'classes/sp/db_verificaAssinatura.php');
include_once($w_dir_volta.'funcoes/selecaoTipoLancamento.php');
include_once($w_dir_volta.'funcoes/selecaoFormaPagamento.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAcordoParcela.php');
include_once($w_dir_volta.'funcoes/selecaoServico.php');
include_once($w_dir_volta.'funcoes/selecaoSolic.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoAcordo.php');
include_once($w_dir_volta.'funcoes/selecaoTipoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoCC.php');
include_once($w_dir_volta.'funcoes/selecaoFase.php');
include_once($w_dir_volta.'funcoes/selecaoSolicResp.php');
include_once($w_dir_volta.'funcoes/selecaoTipoDocumento.php');
include_once($w_dir_volta.'funcoes/selecaoProtocolo.php');
include_once($w_dir_volta.'funcoes/selecaoPais.php');
include_once($w_dir_volta.'funcoes/selecaoRegiao.php');
include_once($w_dir_volta.'funcoes/selecaoEstado.php');
include_once($w_dir_volta.'funcoes/selecaoCidade.php');
include_once($w_dir_volta.'funcoes/selecaoBanco.php');
include_once($w_dir_volta.'funcoes/selecaoAgencia.php');
include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
include_once($w_dir_volta.'funcoes/selecaoSexo.php');
include_once($w_dir_volta.'funcoes/selecaoRubrica.php');
include_once($w_dir_volta.'funcoes/selecaoTipoRubrica.php');
include_once('visualaplicacao.php');
include_once('validaaplicacao.php');
// =========================================================================
//  /aplicacao.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia as rotinas relativas ao controle de aplica��es
// Mail     : alex@sbpi.com.br
// Criacao  : 31/08/2011 09:00
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

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$w_assinatura   = $_REQUEST['w_assinatura'];
$w_pagina       = 'aplicacao.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_fn/';
$w_troca        = $_REQUEST['w_troca'];

$w_copia        = $_REQUEST['w_copia'];
$p_projeto      = upper($_REQUEST['p_projeto']);
$p_ativo        = upper($_REQUEST['p_ativo']);
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

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if (strpos($SG,'ANEXO')!==false || strpos($SG,'PARC')!==false || strpos($SG,'REPR')!==false) {
  if (strpos('IG',$O)===false && $_REQUEST['w_chave_aux']=='') $O='L';
} elseif (strpos($SG,'ENVIO')!==false) {
    $O='V';
} elseif ($O=='') {
  // Se for acompanhamento, entra na filtragem
  if ($P1==3) $O='P'; else $O='L';
} 

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);

// Recupera os dados do cliente
$sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente );

// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$sql = new db_getLinkSubMenu; $RS = $sql->getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configura��o do servi�o
if ($P2>10) {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$P2);
} else {
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configura��o do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

switch ($O) {
  case 'I': $w_TP=$TP.' - Inclus�o';    break;
  case 'A': $w_TP=$TP.' - Altera��o';   break;
  case 'E': $w_TP=$TP.' - Exclus�o';    break;
  case 'G': $w_TP=$TP.' - Gerar';       break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - C�pia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Heran�a';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
}

Main();
FechaSessao($dbms);
exit;
// =========================================================================
// Rotina de visualiza��o resumida dos registros
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_Disabled;

  if ($w_copia>'') {
    // Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),3,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  } else {
    $sql = new db_getSolicList; $RS = $sql->getInstanceOf($dbms,f($RS_Menu,'sq_menu'),$w_usuario,Nvl($_REQUEST['p_agrega'],$SG),$P1,
          $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
          $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
          $p_chave, $p_objeto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
          $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, null, null, $p_sq_orprior);
  }
  if ($p_ordena>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS = SortArray($RS,$lista[0],$lista[1],'vencimento','asc');
  } else {
    $RS = SortArray($RS,'nm_pessoa','asc','vencimento','desc');
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
    if ($P1==2) ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
    ShowHTML('<TITLE>'.$conSgSistema.' - Listagem</TITLE>');
    ScriptOpen('Javascript');
    CheckBranco();
    FormataData();
    SaltaCampo();
    ValidateOpen('Validacao');
    if (strpos('CP',$O)!==false) {
      Validate('P4','Linhas por p�gina','1','1','1','4','','0123456789');
    } 
    ValidateClose();
    ScriptClose();
    ShowHTML('</HEAD>');
  }
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus();\'');
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
  if ($w_filtro>'') ShowHTML($w_filtro);
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O=='L') {
    ShowHTML('<tr>');
    if ($P1==1) {
      // Se for cadastramento e n�o for resultado de busca para c�pia
      ShowHTML('<tr>');
      if ($w_tipo!='WORD') {
        ShowHTML('    <td><a accesskey="I" class="ss" href="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=I&SG='.$SG.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET').'"><u>I</u>ncluir</a>&nbsp;');
      }
    } 
    if ((strpos(upper($R),'GR_')===false) && (strpos(upper($R),'LANCAMENTO')===false) && (Nvl($R,'')>'')) {
      if ($w_copia>'') {
        // Se for c�pia
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=C&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } else {
        if (strpos(str_replace('p_ordena','w_ordena',MontaFiltro('GET')),'p_')) ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u><font color="#BC5100">F</u>iltrar (Ativo)</font></a>');
        else                       ShowHTML('                         <td><a accesskey="F" class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=P&P1='.$P1.'&P2='.$P2.'&P3=1&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><u>F</u>iltrar (Inativo)</a>');
      } 
    }
    ShowHTML('    <td align="right">'.exportaOffice().'<b>Registros: '.count($RS));
    ShowHTML('<tr><td colspan=3>');
    ShowHTML('    <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('C�digo','codigo_inerno') : 'C�digo').'</b></td>');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('Tipo de lan�amento','nm_tipo_lancamento') : 'Tipo de lan�amento').'</b></td>');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('Conta','ds_conta_debito') : 'Conta').'</b></td>');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('Data opera��o','fim') : 'Data opera��o').'</b></td>');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('Valor','valor') : 'Valor').'</b></td>');
    ShowHTML('          <td><b>'.(($w_tipo!='WORD') ? LinkOrdena('Observa��o','observacao') : 'Observa��o').'</b></td>');
    if ($_SESSION['INTERNO']=='S') ShowHTML('          <td><b>Opera��es</b></td>');
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=7 align="center"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      if($w_tipo!='WORD') $RS1 = array_slice($RS, (($P3-1)*$P4), $P4);
      else                $RS1 = $RS;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('        <td nowrap>'.(($w_tipo=='WORD')? f($row,'codigo_interno') : '<A class="hl" HREF="'.$w_dir.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>'));
        ShowHTML('        <td>'.f($row,'nm_tipo_lancamento').'</td>');
        ShowHTML('        <td>'.f($row,'ds_conta_debito').'</td>');
        ShowHTML('        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim'),5),'-').'</td>');
        ShowHTML('        <td align="right">'.formatNumber(f($row,'valor')).'</td>');
        ShowHTML('        <td>'.crlf2br(f($row,'observacao')).'</td>');
        if ($w_tipo!='WORD') {
          if ($_SESSION['INTERNO']=='S') {
            ShowHTML('        <td align="top" nowrap class="remover">');
            if ($P1!=3) {
              // Se n�o for acompanhamento
              if ($P1==1) {
                  // Se for cadastramento
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Geral&R='.$w_pagina.$par.'&O=A&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2=1&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Altera as informa��es cadastrais do lan�amento">AL</A>&nbsp');
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'Excluir&R='.$w_pagina.$par.'&O=E&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exclus�o do lan�amento.">EX</A>&nbsp');
                  if(nvl(f($row,'qtd_nota'),0)!=0) ShowHTML('          <A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.$w_dir.$w_pagina.'Notas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Notas'.'&SG=NOTA').'\',\'Nota\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Informa os valores espec�ficos para cada nota de empenho ligado a parcela.">NE</A>&nbsp');
              } 
            } else {
              if (Nvl(f($row,'solicitante'),0)    == $w_usuario || 
                  Nvl(f($row,'titular'),0)        == $w_usuario || 
                  Nvl(f($row,'substituto'),0)     == $w_usuario || 
                  Nvl(f($row,'tit_exec'),0)       == $w_usuario || 
                  Nvl(f($row,'subst_exec'),0)     == $w_usuario ||
                  RetornaGestor(f($row,'sq_siw_solicitacao'),$w_usuario)=='S') {
                  ShowHTML('          <A class="hl" HREF="'.$w_dir.$w_pagina.'envio&R='.$w_pagina.$par.'&O=V&w_chave='.f($row,'sq_siw_solicitacao').'&w_tramite='.f($row,'sq_siw_tramite').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Envia o lan�amento para outro respons�vel.">EN</A>&nbsp');
              } else {
                ShowHTML('          ---&nbsp');
              }
            } 
            ShowHTML('        </td>');
          } 
        }
        ShowHTML('      </tr>');
      } 
    } 
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    if ($w_tipo!='WORD') {
      ShowHTML('<tr><td align="center" colspan=3>');
      if ($R>'') {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } else {
        MontaBarra($w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&TP='.$TP.'&SG='.$SG.'&w_copia='.$w_copia,ceil(count($RS)/$P4),$P3,$P4,count($RS));
      } 
      ShowHTML('</tr>');
    } 
  } elseif(strpos('CP',$O)!==false) {
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Para selecionar o lan�amento que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>');
    } 
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="90%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));',null,$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    if ($O=='C') {
      // Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
      ShowHTML('<INPUT type="hidden" name="w_copia" value="OK">');
    } 
    // Recupera dados da op��o Projetos
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Pr<u>o</u>jeto:','O','Selecione o projeto do lan�amento na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto',$w_menu,null);
    ShowHTML('      </tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>');
    if ($P1!=1 || $O=='C') {
      // Se n�o for cadastramento ou se for c�pia
      if (f($RS_Menu,'solicita_cc')=='S') {
        ShowHTML('      <tr>');
        SelecaoCC('C<u>l</u>assifica��o:','L','Selecione a classifica��o desejada.',$p_sqcc,null,'p_sqcc','SIWSOLIC');
        ShowHTML('      </tr>');
      } 
      ShowHTML('      <tr valign="top">');
      ShowHTML('          <td><b>N�mero da <U>d</U>emanda:<br><INPUT ACCESSKEY="D" '.$w_Disabled.' class="sti" type="text" name="p_chave" size="18" maxlength="18" value="'.$p_chave.'"></td>');
      ShowHTML('          <td><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY="T" '.$w_Disabled.' class="sti" type="text" name="p_prazo" size="2" maxlength="2" value="'.$p_prazo.'"></td>');
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respo<u>n</u>s�vel:','N','Selecione o respons�vel pelo monitoramento do lan�amento na rela��o.',$p_solicitante,null,'p_solicitante','USUARIOS');
      SelecaoUnidade('<U>S</U>etor respons�vel:','S',null,$p_unidade,null,'p_unidade',null,null);
      ShowHTML('      <tr valign="top">');
      SelecaoPessoa('Respons�vel atua<u>l</u>:','L','Selecione o respons�vel atual pelo lan�amento na rela��o.',$p_usu_resp,null,'p_usu_resp','USUARIOS');
      SelecaoUnidade('<U>S</U>etor atual:','S','Selecione a unidade onde o lan�amento se encontra na rela��o.',$p_uorg_resp,null,'p_uorg_resp',null,null);
      ShowHTML('      <tr>');
      SelecaoPais('<u>P</u>a�s:','P',null,$p_pais,null,'p_pais',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_regiao\'; document.Form.submit();"');
      SelecaoRegiao('<u>R</u>egi�o:','R',null,$p_regiao,$p_pais,'p_regiao',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_uf\'; document.Form.submit();"');
      ShowHTML('      <tr>');
      SelecaoEstado('E<u>s</u>tado:','S',null,$p_uf,$p_pais,$p_regiao,'p_uf',null,'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'p_cidade\'; document.Form.submit();"');
      SelecaoCidade('<u>C</u>idade:','C',null,$p_cidade,$p_pais,$p_uf,'p_cidade',null,null);
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY="B" '.$w_Disabled.' class="sti" type="text" name="p_objeto" size="25" maxlength="90" value="'.$p_objeto.'"></td>');
      ShowHTML('      <tr>');
      ShowHTML('          <td><b>In�<u>c</u>io vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="C" type="text" name="p_ini_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_ini_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      ShowHTML('          <td><b>Fi<u>m</u> vig�ncia entre:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_i" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_i.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"> e <input '.$w_Disabled.' accesskey="T" type="text" name="p_fim_f" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$p_fim_f.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);"></td>');
      if ($O!='C') {
        // Se n�o for c�pia
        ShowHTML('      <tr>');
        ShowHTML('          <td><b>Exibe lan�amentos vencidos?</b><br>');
        if ($p_atraso=='S') ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S" checked> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N"> N�o');
        else                ShowHTML('              <input '.$w_Disabled.' class="str" type="radio" name="p_atraso" value="S"> Sim <br><input '.$w_Disabled.' class="str" class="str" type="radio" name="p_atraso" value="N" checked> N�o');
        SelecaoFaseCheck('Recuperar fases:','S',null,$p_fase,$P2,'p_fase',null,null);
      } 
    } 
    ShowHTML('      <tr>');
    ShowHTML('          <td><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY="O" '.$w_Disabled.' class="sts" name="p_ordena" size="1">');
    if     ($p_ordena=='ASSUNTO')       ShowHTML('          <option value="assunto" SELECTED>Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='vencimento')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento" SELECTED>In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='FIM')           ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite" SELECTED>Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_pessoa')     ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa" SELECTED>Pessoa<option value="proponente">Projeto');
    elseif ($p_ordena=='NM_PROJETO')    ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="">T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente" SELECTED>Projeto');
    else                                ShowHTML('          <option value="assunto">Objeto<option value="vencimento">In�cio vig�ncia<option value="" SELECTED>T�rmino vig�ncia<option value="nm_tramite">Fase atual<option value="pessoa">Pessoa<option value="proponente">Projeto');
    ShowHTML('          </select></td>');
    ShowHTML('          <td><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('          </table>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    if ($O=='C') {
      // Se for c�pia
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Abandonar c�pia">');
    } else {
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    }
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
  ShowHTML('</table>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina dos dados gerais
// -------------------------------------------------------------------------
function Geral() {
    
  extract($GLOBALS);
  global $w_Disabled;
  $w_chave              = $_REQUEST['w_chave'];
  $w_sq_tipo_lancamento = $_REQUEST['w_sq_tipo_lancamento'];
  $w_readonly           = '';
  $w_erro               = '';

  // Carrega o segmento cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_codigo_interno       = $_REQUEST['w_codigo_interno'];
    $w_sq_tipo_lancamento   = $_REQUEST['w_sq_tipo_lancamento'];
    $w_sq_tipo_documento    = $_REQUEST['w_sq_tipo_documento'];
    $w_descricao            = $_REQUEST['w_descricao'];
    $w_valor                = $_REQUEST['w_valor'];
    $w_fim                  = $_REQUEST['w_fim'];
    $w_conta_debito         = $_REQUEST['w_conta_debito'];
    $w_sq_forma_pagamento   = $_REQUEST['w_sq_forma_pagamento'];

  } elseif(strpos('AEV',$O)!==false || $w_copia>'') {
    // Recupera os dados do lan�amento
    $sql = new db_getSolicData; 
    $RS = $sql->getInstanceOf($dbms,nvl($w_copia,$w_chave),$SG);
    if (count($RS)>0) {
      $w_codigo_interno       = f($RS,'codigo_interno');
      $w_sq_tipo_lancamento   = f($RS,'sq_tipo_lancamento');
      $w_conta_debito         = f($RS,'sq_pessoa_conta');
      $w_descricao            = f($RS,'descricao');
      $w_fim                  = formataDataEdicao(f($RS,'fim'));
      $w_valor                = formatNumber(f($RS,'valor'));
      $w_sq_forma_pagamento   = f($RS,'sq_forma_pagamento');
    }
  }

  // Recupera dados do comprovante
  if (nvl($w_troca,'')=='' && (nvl($w_copia,'')!='' || nvl($w_chave,'')!='')) {
    $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,nvl($w_copia,$w_chave),null,null,null,null,null,null,'DOCS');
    $RS = SortArray($RS,'sq_tipo_documento','asc');
    foreach ($RS as $row) {$RS=$row; break;}
    if (nvl($w_copia,'')=='') $w_chave_doc =  f($RS,'sq_lancamento_doc'); // Se c�pia, n�o copia a chave do documento 
    $w_sq_tipo_documento    = f($RS,'sq_tipo_documento');
    $w_numero               = f($RS,'numero');
    $w_data                 = FormataDataEdicao(f($RS,'data'));
    $w_serie                = f($RS,'serie');
    $w_valor_doc            = formatNumber(f($RS,'valor'));
    $w_patrimonio           = f($RS,'patrimonio');
    $w_tributo              = f($RS,'calcula_tributo');
    $w_retencao             = f($RS,'calcula_retencao');    
  }

  // Default: pagamento para pessoa jur�dica
  $w_tipo_pessoa = nvl($w_tipo_pessoa,2);
  
  // Recupera o tr�mite de conclus�o
  $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null,null,null);
  $RS = SortArray($RS,'ordem','asc');
  foreach ($RS as $row) {
    if (f($row,'sigla')=='AT') {
      $w_tramite_conc = f($row,'sq_siw_tramite');
      break;
    }
  }   
  
  // Verifica as formas de pagamento poss�veis. Se apenas uma, atribui direto
  $sql = new db_getFormaPagamento; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, $SG, null,'S',null);
  $w_exibe_fp = true;
  if (count($RS)==1 || nvl($w_sq_forma_pagamento,'')!='') {
    foreach($RS as $row) { 
      if (nvl($w_sq_forma_pagamento,f($row,'sq_forma_pagamento'))==f($row,'sq_forma_pagamento')) {
        $w_sq_forma_pagamento = f($row,'sq_forma_pagamento'); 
        $w_forma_pagamento    = f($row,'sigla'); 
        $w_nm_forma_pagamento = f($row,'nome'); 
        break; 
      }
    }
    if (count($RS)==1) $w_exibe_fp = false;
  }
  
  // Verifica os tipos de documento poss�veis. Se apenas um, atribui direto
  $sql = new db_getTipoDocumento; $RS = $sql->getInstanceOf($dbms,null,$w_cliente,$w_menu,null);
  $w_exibe_dc = true;
  if (count($RS)==1) {
    foreach($RS as $row) { 
      $w_sq_tipo_documento = f($row,'chave'); 
      $w_tipo_documento    = f($row,'sigla'); 
      $w_nm_tipo_documento = f($row,'nome'); 
      break; 
    }
    $w_exibe_dc = false;
  }

  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  CheckBranco();
  FormataData();
  SaltaCampo();
  FormataDataHora();
  FormataValor();
  ShowHTML('function botoes() {');
  ShowHTML('  document.Form.Botao[0].disabled = true;');
  ShowHTML('  document.Form.Botao[1].disabled = true;');
  ShowHTML('}');
  ValidateOpen('Validacao');
  if ($O=='I' || $O=='A') {
    Validate('w_sq_tipo_lancamento','Tipo do lan�amento','SELECT',1,1,18,'','0123456789');
    if ($w_exibe_fp) Validate('w_sq_forma_pagamento','Forma de pagamento','SELECT',1,1,18,'','0123456789');
    Validate('w_conta_debito','Conta banc�ria', 'SELECT', 1, 1, 18, '', '0123456789');
    if ($w_exibe_dc) Validate('w_sq_tipo_documento','Tipo de documento','SELECT',1,1,18,'','0123456789');
    Validate('w_fim','Data da opera��o', 'DATA', '1', '10', '10', '', '0123456789/');
    Validate('w_valor','Valor total do documento','VALOR','1',4,18,'','0123456789.,-');
    CompValor('w_valor','Valor total do documento','>','0,00','zero');
    Validate('w_descricao','Observa��o','1','',5,2000,'1','1');
  } 
  Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
  ValidateClose();
  ScriptClose();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  if ($w_troca>'')                               BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  elseif (!(strpos('EV',$O)===false))            BodyOpen('onLoad=\'this.focus()\';');
  else                                           BodyOpen('onLoad=\'document.Form.w_sq_tipo_lancamento.focus()\';');
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($w_chave>'') ShowHTML('      <tr><td><font size="2"><b>'.$w_codigo_interno.' ('.$w_chave.')</b></td>');
  if (strpos('IAEV',$O)!==false) {
    if (strpos('EV',$O)!==false) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao,$SG);
    }  
    if (Nvl($w_pais,'')=='') {
      // Carrega os valores padr�o para pa�s, estado e cidade
      $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
      $w_pais   = f($RS,'sq_pais');
      $w_uf     = f($RS,'co_uf');
      $w_cidade = Nvl(f($RS_Menu,'sq_cidade'),f($RS,'sq_cidade_padrao'));
    } 
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_copia" value="'.$w_copia.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_codigo_interno" value="'.$w_codigo_interno.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.f($RS_Menu,'sq_menu').'">');
    ShowHTML('<INPUT type="hidden" name="w_data_hora" value="'.f($RS_Menu,'data_hora').'">');
    ShowHTML('<INPUT type="hidden" name="w_cidade" value="'.$w_cidade.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite_conc.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo" value="'.$w_tipo.'">');
    ShowHTML('<INPUT type="hidden" name="w_chave_doc" value="'.$w_chave_doc.'">');
    ShowHTML('<INPUT type="hidden" name="w_tipo_pessoa" value="'.$w_tipo_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_aviso" value="N">');
    ShowHTML('<INPUT type="hidden" name="w_dias" value="0">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
    ShowHTML('    <table width="100%" border="0">');
    ShowHTML('      <tr><td colspan=3 align="center" height="2" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" bgcolor="#D0D0D0"><b>Identifica��o</td></td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');
    ShowHTML('      <tr><td colspan=3>Os dados deste bloco ser�o utilizados para identifica��o do lan�amento, bem como para o controle de sua execu��o.</td></tr>');
    ShowHTML('      <tr><td colspan=3 align="center" height="1" bgcolor="#000000"></td></tr>');

    ShowHTML('          <tr valign="top">');
    selecaoTipoLancamento('Tipo de lan�amento:',null,null,$w_sq_tipo_lancamento,$w_menu,$w_cliente,'w_sq_tipo_lancamento',$SG,null,3);
    ShowHTML('      <tr valign="top">');
    if ($w_exibe_fp) {
      SelecaoFormaPagamento('<u>F</u>orma de pagamento:','F','Selecione na lista a forma desejada para esta aplica��o.',$w_sq_forma_pagamento,$SG,'w_sq_forma_pagamento',null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_forma_pagamento" value="'.$w_sq_forma_pagamento.'">');
    }
    SelecaoContaBanco('<u>C</u>onta banc�ria:','C','Selecione a conta banc�ria envolvida no lan�amento.',$w_conta_debito,null,'w_conta_debito',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr valign="top">');
    if ($w_exibe_dc) {
      SelecaoTipoDocumento('<u>T</u>ipo do documento:','T', 'Selecione o tipo de documento.', $w_sq_tipo_documento,$w_cliente,$w_menu,'w_sq_tipo_documento',null,null);
    } else {
      ShowHTML('<INPUT type="hidden" name="w_sq_tipo_documento" value="'.$w_sq_tipo_documento.'">');
    }
    ShowHTML('        <td><b><u>D</u>ata da opera��o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_fim.'" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">'.ExibeCalendario('Form','w_fim').'</td>');
    ShowHTML('        <td><b><u>V</u>alor:</b><br><input '.$w_Disabled.' accesskey="V" type="text" name="w_valor" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.$w_valor.'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o valor total do documento."></td>');
    ShowHTML('      <tr><td colspan=3><b><u>O</u>bserva��o:</b><br><textarea '.$w_Disabled.' accesskey="O" name="w_descricao" class="sti" ROWS=3 cols=75 title="Observa��o sobre a aplica��o.">'.$w_descricao.'</TEXTAREA></td>');
    
    ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
    ShowHTML('      <tr><td align="center" colspan="3">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
     $sql = new db_getMenuData; $RS = $sql->getInstanceOf($dbms,$w_menu);
     ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&w_copia='.$w_copia.'&O=L&SG='.f($RS,'sigla').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.MontaFiltro('GET')).'\';" name="Botao" value="Cancelar">');
    }
    ShowHTML('          </td>');
    
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
    ShowHTML('    </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Op��o n�o dispon�vel");');
    //ShowHTML ' history.back(1);'
    ScriptClose();
  } 
  ShowHTML('</table>');
  ShowHTML('</center>');
  Estrutura_Texto_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Estrutura_Fecha();
  Rodape();
} 
// =========================================================================
// Rotina de visualiza��o
// -------------------------------------------------------------------------
function Visual() {
  extract($GLOBALS);
  $w_chave  = $_REQUEST['w_chave'];
  $w_tipo   = upper(trim($_REQUEST['w_tipo']));
  if ($w_tipo=='PDF') {
    headerpdf('Visualiza��o de '.f($RS_Menu,'nome'),$w_pag);
    $w_embed = 'WORD';
  } elseif ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    CabecalhoWord($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),0);
    $w_embed = 'WORD';
  } else {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Visualiza��o de '.f($RS_Menu,'nome').'</TITLE>');
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    BodyOpenClean(null); 
  if ($w_tipo!='WORD') CabecalhoRelatorio($w_cliente,'Visualiza��o de '.f($RS_Menu,'nome'),4,$w_chave);
  $w_embed = 'HTML';
  }
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualAplicacao($w_chave,'L',$w_usuario,$P1,$w_embed));
  if ($w_embed!='WORD') ShowHTML('<center><B><font size=1>Clique <span class="lk"><a class="hl" href="javascript:history.back(1);">aqui</a> para voltar � tela anterior</span></font></b></center>');
  ScriptOpen('JavaScript');
  ShowHTML('  var comando, texto;');
  ShowHTML('  if (window.name!="content") {');
  ShowHTML('    $(".lk").html(\'<a class="hl" href="javascript:window.close(); opener.focus();">aqui</a> fechar esta janela\');');
  ShowHTML('  }');
  ScriptClose();
  if ($w_tipo=='PDF') RodapePDF();
  elseif ($w_tipo!='WORD') Rodape();
} 
// =========================================================================
// Rotina de exclus�o
// -------------------------------------------------------------------------
function Excluir() {
  extract($GLOBALS);
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_observacao = $_REQUEST['w_observacao'];
  } 
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='E') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados do lan�amento, na op��o 'Listagem'
  ShowHTML(VisualAplicacao($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.f($RS,'sq_siw_tramite').'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
  ShowHTML('    <tr><td align="center" colspan=4><hr>');
  ShowHTML('      <input class="stb" type="submit" name="Botao" value="Excluir">');
  if ($P1==0) {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  } else {
    ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function Encaminhamento() {
  extract($GLOBALS);
  // Se envio de reembolso, chama a rotina de envio sem indica��o de destinat�rio
  if ($SG=='FNDREEMB') {
    EncAutomatico();
    exit();
  }
  
  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_erro       = '';
  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_tramite=$_REQUEST['w_tramite'];
    $w_destinatario=$_REQUEST['w_destinatario'];
    $w_novo_tramite=$_REQUEST['w_novo_tramite'];
    $w_despacho=$_REQUEST['w_despacho'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_tramite        = f($RS,'sq_siw_tramite');
    $w_sg_tramite_ant = f($RS,'sg_tramite');
    $w_novo_tramite   = f($RS,'sq_siw_tramite');
  }
   
  // Recupera os dados da solicita��o
  $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,$w_chave,f($RS_Menu,'sigla'));
  
  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_novo_tramite);
  $w_sg_tramite = f($RS,'sigla');
  $w_ativo      = f($RS,'ativo');
  if ($w_ativo == 'N') {
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms, $w_menu,null, null,'S');
    $RS = SortArray($RS,'ordem','asc');
    foreach ($RS as $row) {
      $w_novo_tramite = f($row,'sq_siw_tramite');
      $w_sg_tramite   = f($row,'sigla');
      break;
    }   
  }
  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaAplicacao($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  Cabecalho();
  head();
  Estrutura_CSS($w_cliente);
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if ($O=='V') {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    Validate('w_destinatario','Destinat�rio', 'HIDDEN', '1', '1', '10', '', '1');
    Validate('w_despacho','Despacho', '', '1', '1', '2000', '1', '1');
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'], '1', '1', '3', '30', '1', '1');
    if ($P1!=1) {
      // Se n�o for encaminhamento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    } 
    ValidateClose();
    ScriptClose();
  } 
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_destinatario.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  // Chama a rotina de visualiza��o dos dados do projeto, na op��o 'Listagem'
  ShowHTML(VisualAplicacao($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVIO',$w_pagina.$par,$O);
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
  ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
  ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
  ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td colspan=2>');
  ShowHTML('  <table width="97%" border="0">');
  ShowHTML('    <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">');
  if(($w_sg_tramite_ant=='AT')&&($w_erro>'' && substr(Nvl($w_erro,'-'),0,1)=='0')) {
    ShowHTML('      <tr><td align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>N�o � poss�vel o envio do lan�amento enquanto as corre��es listadas n�o forem feitas.</b></font></td>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="button" onClick="history.back(1);" name="Botao" value="Abandonar">');
  } else {  
    if (nvl(f($RS_Solic,'condicoes_pagamento'),'')!='') {
      ShowHTML('      <tr><td bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);" colspan=4><b><font color="#BC3131">');
      ShowHTML('        VERIFIQUE AS CONDI��ES ABAIXO ANTES DE EXECUTAR O ENVIO:<ul>');
      ShowHTML('        <li>'.str_replace($crlf,'<li>',f($RS_Solic,'condicoes_pagamento')));
      ShowHTML('        </b></font></td>');
      ShowHTML('      </tr>');
      ShowHTML('<tr><td>&nbsp;');
    }
    if ($P1!=1) {
      // Se n�o for cadastramento
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S'))
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      else
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      // Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
      if ($w_sg_tramite=='CI')
        SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione, na rela��o, um destinat�rio para o lan�amento.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'CADASTRAMENTO');
      else
        SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    } else {
      if (Nvl($w_erro,'')=='' || (Nvl($w_erro,'')>'' && substr($w_erro,0,1)!='0' && RetornaGestor($w_chave,$w_usuario)=='S')) {
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_menu, null,'w_novo_tramite', null, 'onChange="document.Form.action=\''.$w_dir.$w_pagina.$par.'\'; document.Form.O.value=\''.$O.'\'; document.Form.w_troca.value=\'w_destinatario\'; document.Form.submit();"');
      } else {
        SelecaoFase('<u>F</u>ase do lan�amento:','F','Se deseja alterar a fase atual do lan�amento, selecione a fase para a qual deseja envi�-la.', $w_novo_tramite, $w_tramite, null,'w_novo_tramite', 'ERRO', null);
      }
      SelecaoSolicResp('<u>D</u>estinat�rio:','D','Selecione um destinat�rio para o lan�amento na rela��o.', $w_destinatario, $w_chave, $w_novo_tramite, $w_novo_tramite, 'w_destinatario', 'USUARIOS');
    }     
    ShowHTML('    <tr><td colspan=2><b>D<u>e</u>spacho:</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="sti" ROWS=5 cols=75 title="Descreva a a��o esperada pelo destinat�rio na execu��o do lan�amento.">'.$w_despacho.'</TEXTAREA></td>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
    ShowHTML('    <tr><td align="center" colspan=4><hr>');
    ShowHTML('      <input class="stb" type="submit" name="Botao" value="Enviar">');
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } else {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
  }
  ShowHTML('      </td>');
  ShowHTML('    </tr>');
  ShowHTML('  </table>');
  ShowHTML('  </TD>');
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
// Rotina de tramita��o
// -------------------------------------------------------------------------
function EncAutomatico() {
  extract($GLOBALS);
  global $w_Disabled;

  $w_chave      = $_REQUEST['w_chave'];
  $w_chave_aux  = $_REQUEST['w_chave_aux'];
  $w_tipo       = Nvl($_REQUEST['w_tipo'],'');

  if ($w_troca>'') {
    // Se for recarga da p�gina
    $w_tramite          = $_REQUEST['w_tramite'];
    $w_sg_tramite       = $_REQUEST['w_sg_tramite'];
    $w_sg_novo_tramite  = $_REQUEST['w_tramite'];
    $w_destinatario     = $_REQUEST['w_destinatario'];
    $w_envio            = $_REQUEST['w_envio'];
    $w_despacho         = $_REQUEST['w_despacho'];
    $w_justificativa    = $_REQUEST['w_justificativa'];
    $w_justif_dia_util  = $_REQUEST['w_justif_dia_util'];
  } else {
    $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$w_chave,$SG);
    $w_inicio           = f($RS,'inicio');
    $w_tramite          = f($RS,'sq_siw_tramite');
    $w_justificativa    = f($RS,'justificativa');
    $w_prazo            = f($RS,'limite_envio');
    $w_antecedencia     = f($RS,'dias_antecedencia');
    $w_justif_dia_util  = f($RS,'justificativa_dia_util');
    $w_fim_semana       = f($RS,'fim_semana');
  }

  // Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$w_tramite);
  $w_sg_tramite = f($RS,'sigla');

  if ($w_sg_tramite!='CI') {
    //Verifica a fase anterior para a caixa de sele��o da fase.
    $sql = new db_getTramiteList; $RS = $sql->getInstanceOf($dbms,$w_tramite,$w_chave,'DEVFLUXO',null);
    $RS = SortArray($RS,'ordem','desc');
    foreach($RS as $row) { $RS = $row; break; }
    $w_novo_tramite = f($RS,'sq_siw_tramite');
  }

  // Se for envio, executa verifica��es nos dados da solicita��o
  if ($O=='V') $w_erro = ValidaAplicacao($w_cliente,$w_chave,$SG,null,null,null,$w_tramite);
  
  Cabecalho();
  head();
  ShowHTML('<meta http-equiv="Refresh" content="'.$conRefreshSec.'; URL=../'.MontaURL('MESA').'">');
  if (substr(Nvl($w_erro,'nulo'),0,1)!='0' && (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S'))) {
    ScriptOpen('JavaScript');
    ValidateOpen('Validacao');
    if ($w_sg_tramite!='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE') {
        Validate('w_despacho','Despacho','1','1','1','2000','1','1');
      } else {
        Validate('w_despacho','Despacho','','','1','2000','1','1');
        ShowHTML('  if (theForm.w_envio[0].checked && theForm.w_despacho.value != \'\') {');
        ShowHTML('     alert(\'Informe o despacho apenas se for devolu��o para a fase anterior!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
        ShowHTML('  if (theForm.w_envio[1].checked && theForm.w_despacho.value==\'\') {');
        ShowHTML('     alert(\'Informe um despacho descrevendo o motivo da devolu��o!\');');
        ShowHTML('     theForm.w_despacho.focus();');
        ShowHTML('     return false;');
        ShowHTML('  }');
      }
    }
    Validate('w_assinatura',$_SESSION['LABEL_ALERTA'],'1','1','3','30','1','1');
    if ($P1!=1 || ($P1==1 && $w_tipo=='Volta')) {
      // Se n�o for encaminhamento e nem o sub-menu do cadastramento
      ShowHTML('  theForm.Botao[0].disabled=true;');
      ShowHTML('  theForm.Botao[1].disabled=true;');
    } else {
      ShowHTML('  theForm.Botao.disabled=true;');
    }
    ValidateClose();
    ScriptClose();
  }
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  if ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } else {
    BodyOpen('onLoad=\'this.focus()\';');
  }
  ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</font></B>');
  ShowHTML('<HR>');
  ShowHTML('<div align=center><center>');
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  // Chama a rotina de visualiza��o dos dados da solicita��o, na op��o 'Listagem'
  ShowHTML(VisualAplicacao($w_chave,'V',$w_usuario,$P1,$P4));
  ShowHTML('<HR>');
  if (Nvl($w_erro,'')=='' || $w_sg_tramite=='EE' || (substr(Nvl($w_erro,'nulo'),0,1)=='2' && $w_sg_tramite=='CI') || (Nvl($w_erro,'')>'' && RetornaGestor($w_chave,$w_usuario)=='S')) {
    AbreForm('Form',$w_dir.$w_pagina.'Grava','POST','return(Validacao(this));',null,$P1,$P2,$P3,$P4,$TP,substr($SG,0,3).'ENVAUT',$w_pagina.$par,$O);
    ShowHTML(MontaFiltro('POST'));
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('<INPUT type="hidden" name="w_tramite" value="'.$w_tramite.'">');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td align="center">');
    ShowHTML('  <table width="97%" border="0">');
    ShowHTML('    <tr><td valign="top" colspan="2"><table border=0 width="100%">');
    if ($w_sg_tramite=='CI') {
      if (substr(Nvl($w_erro,'nulo'),0,1)!='0') {
        // Se cadastramento inicial
        ShowHTML('<INPUT type="hidden" name="w_envio" value="N">');
        ShowHTML('      </table>');
        ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
        ShowHTML('    <tr><td align="center" colspan=4><hr>');
        ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
      }
    } else {
      ShowHTML('    <tr><td><b>Tipo do Encaminhamento</b><br>');
      if (substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE') {
        ShowHTML('              <input DISABLED class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input DISABLED class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        ShowHTML('<INPUT type="hidden" name="w_envio" value="S">');
      } else {
        if (Nvl($w_envio,'N')=='N') {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N" checked> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S"> Devolver para a fase anterior');
        } else {
          ShowHTML('              <input '.$w_Disabled.' class="STR" type="radio" name="w_envio" value="N"> Enviar para a pr�xima fase <br><input '.$w_Disabled.' class="STR" class="STR" type="radio" name="w_envio" value="S" checked> Devolver para a fase anterior');
        }
      }
      ShowHTML('    <tr>');
      SelecaoFase('<u>F</u>ase: (v�lido apenas se for devolu��o)','F','Se deseja devolver a solicita��o, selecione a fase para a qual deseja devolv�-la.',$w_novo_tramite,$w_tramite,$w_chave,'w_novo_tramite','DEVFLUXO',null);
      ShowHTML('    <tr><td><b>D<u>e</u>spacho (informar apenas se for devolu��o):</b><br><textarea '.$w_Disabled.' accesskey="E" name="w_despacho" class="STI" ROWS=5 cols=75 title="Informe o que o destinat�rio deve fazer quando receber a solicita��o.">'.$w_despacho.'</TEXTAREA></td>');
      if (!(substr(Nvl($w_erro,'nulo'),0,1)=='0' || $w_sg_tramite=='EE')) {
        if (substr(Nvl($w_erro,'nulo'),0,1)=='1' || substr(Nvl($w_erro,'nulo'),0,1)=='2') {
          if (mktime(0,0,0,date(m),date(d),date(Y))>$w_prazo) {
            ShowHTML('    <tr><td><b><u>J</u>ustificativa para n�o cumprimento do prazo regulamentar de '.$w_antecedencia.' dias:</b><br><textarea '.$w_Disabled.' accesskey="J" name="w_justificativa" class="STI" ROWS=5 cols=75 title="Se o in�cio da viagem for anterior a '.FormataDataEdicao(addDays(time(),$w_prazo)).', justifique o motivo do n�o cumprimento do prazo regulamentar para o pedido.">'.$w_justificativa.'</TEXTAREA></td>');
          }
        }
      }
      ShowHTML('      </table>');
      ShowHTML('      <tr><td align="LEFT" colspan=4><b>'.$_SESSION['LABEL_CAMPO'].':<BR> <INPUT ACCESSKEY="A" class="STI" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td></tr>');
      ShowHTML('    <tr><td align="center" colspan=4><hr>');
      ShowHTML('      <input class="STB" type="submit" name="Botao" value="Enviar">');
    }
    if ($P1==0) {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=Inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1!=1) {
      // Se n�o for cadastramento, volta para a listagem
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    } elseif ($P1==1 && $w_tipo=='Volta') {
      ShowHTML('      <input class="STB" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$R.'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET')).'\';" name="Botao" value="Abandonar">');
    }
    ShowHTML('      </td>');
    ShowHTML('    </tr>');
    ShowHTML('  </table>');
    ShowHTML('  </TD>');
    ShowHTML('</tr>');
    ShowHTML('</FORM>');
  }
  ShowHTML('</table>');
  ShowHTML('</center>');
  Rodape();
}

// =========================================================================
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  $w_file       = '';
  $w_tamanho    = '';
  $w_tipo       = '';
  $w_nome       = '';
  Cabecalho();
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');

  if (strpos($SG,'FNAAPLICA')!==false) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {

        if (nvl($_REQUEST['w_conta_debito'],'')!='') {
          // Recupera os dados da conta cr�dito
          $sql = new db_getContaBancoList; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,$_REQUEST['w_conta_debito'],'FINANCEIRO');
          foreach($RS2 as $row) { $RS2 = $row; break; }
          $w_conta            = null;
          $w_sq_banco         = f($RS2,'sq_banco');
          $w_sq_agencia       = f($RS2,'sq_agencia');
          $w_operacao         = f($RS2,'operacao');
          $w_nr_conta         = f($RS2,'numero');
          $w_moeda            = f($RS2,'sq_moeda');
        }

        $SQL = new dml_putFinanceiroGeral; $SQL->getInstanceOf($dbms,$O,$w_cliente,$_REQUEST['w_chave'],$_REQUEST['w_menu'],
          $_SESSION['LOTACAO'],$_SESSION['SQ_PESSOA'],$_SESSION['SQ_PESSOA'],$_REQUEST['w_sqcc'],
          $_REQUEST['w_descricao'],$_REQUEST['w_fim'],Nvl($_REQUEST['w_valor'],0),$_REQUEST['w_data_hora'],
          $_REQUEST['w_aviso'],$_REQUEST['w_dias'],$_REQUEST['w_cidade'],$_REQUEST['w_chave_pai'],
          $_REQUEST['w_sq_acordo_parcela'],$_REQUEST['w_observacao'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          Nvl($_REQUEST['w_sq_forma_pagamento'],''),$_REQUEST['w_tipo_pessoa'],$_REQUEST['w_sq_forma_pagamento'],
          $_REQUEST['w_fim'],$_REQUEST['w_tipo_rubrica'],nvl($_REQUEST['w_protocolo'],$_REQUEST['w_numero_processo']),
          $_REQUEST['w_per_ini'],$_REQUEST['w_per_fim'],$_REQUEST['w_texto_pagamento'],$_REQUEST['w_solic_vinculo'],
          $_REQUEST['w_sq_projeto_rubrica'],$_REQUEST['w_solic_apoio'],$_REQUEST['w_data_autorizacao'],
          $_REQUEST['w_texto_autorizacao'],$w_moeda,$w_chave_nova, $w_codigo);
      
      if ($O!='E') {
        // Reembolso sempre � para o usu�rio logado
        $sql = new db_getBenef; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_cliente,null,null,null,null,null,null,null,null,null,null,null,null, null, null, null, null);
        foreach ($RS as $row) {$RS=$row; break;}
  
        if (nvl($_REQUEST['w_chave'],'')!='') {
          // Se a solicita��o j� existe, recupera os dados banc�rios
          $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,$_REQUEST['w_chave'],f($RS_Menu,'sigla'));
          $w_pessoa_atual     = f($RS1,'pessoa');
          $w_sq_pais_estrang  = f($RS1,'sq_pais_estrang');
          $w_aba_code         = f($RS1,'aba_code');
          $w_swift_code       = f($RS1,'swift_code');
          $w_endereco_estrang = f($RS1,'endereco_estrang');
          $w_banco_estrang    = f($RS1,'banco_estrang');
          $w_agencia_estrang  = f($RS1,'agencia_estrang');
          $w_cidade_estrang   = f($RS1,'cidade_estrang');
          $w_informacoes      = f($RS1,'informacoes');
          $w_codigo_deposito  = f($RS1,'codigo_deposito');
        } 
  
        //Grava os dados da pessoa
        $SQL = new dml_putLancamentoOutra; $SQL->getInstanceOf($dbms,$O,$SG,$w_chave_nova,$w_cliente,$w_cliente,f($RS,'cpf'),f($RS,'cnpj'),
            f($RS,'nm_pessoa'),f($RS,'nm_resumido'),$_REQUEST['w_sexo'],null,null,null,null,null,null,null,
            f($RS,'logradouro'),f($RS,'complemento'),f($RS,'bairro'),f($RS,'sq_cidade'),f($RS,'cep'),
            f($RS,'ddd'),f($RS,'nr_telefone'),f($RS,'nr_fax'),f($RS,'nr_celular'),f($RS,'email'), $w_sq_agencia, $w_operacao, 
            $w_nr_conta, $w_sq_pais_estrang, $w_aba_code, $w_swift_code, $w_endereco_estrang, $w_banco_estrang, $w_agencia_estrang, 
            $w_cidade_estrang, $w_informacoes, $w_codigo_deposito, $w_pessoa_atual, $w_conta);
            
        //Grava os dados do comprovante de despesa
        $SQL = new dml_putLancamentoDoc; $SQL->getInstanceOf($dbms,$O,$w_chave_nova,$_REQUEST['w_chave_doc'],$_REQUEST['w_sq_tipo_documento'],
          nvl($_REQUEST['w_numero'],'s/n'),$_REQUEST['w_fim'],$_REQUEST['w_serie'],$w_moeda,$_REQUEST['w_valor'],
          'N','N','N',null,null,null,null, $w_chave_doc);

        // Grava acr�scimos e supress�es
        $SQL = new dml_putLancamentoValor;  $SQL->getInstanceOf($dbms,'E',$w_chave_doc,null,null);

        // Insere os registros com valor maior que zero
        for ($i=0; $i<=count($_POST['w_valores'])-1; $i=$i+1) {
          if (Nvl($_REQUEST['w_valores'][$i],'0,00')!='0,00') {
            $SQL->getInstanceOf($dbms,'I',$w_chave_doc,$_REQUEST['w_sq_valores'][$i],$_REQUEST['w_valores'][$i]);
          } 
        }

        $SQL = new dml_putFinanceiroConc; $SQL->getInstanceOf($dbms,$w_menu,$w_chave_nova,$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_fim'],Nvl($_REQUEST['w_valor'],0),null,$_REQUEST['w_conta_debito'],Nvl($_REQUEST['w_sq_tipo_lancamento'],''),
          null,'Conclus�o autom�tica de aplica��o.',null,null,null,null);
      }
    
      $w_html = VisualAplicacao(nvl($_REQUEST['w_chave'],$w_chave_nova),'L',$w_usuario,1,'1');
      CriaBaseLine(nvl($_REQUEST['w_chave'],$w_chave_nova),$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
      
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
    // Envio de lan�amentos.
  } elseif (!(strpos($SG,'ENVIO')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida 
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ((false!==(strpos(upper($_SERVER['HTTP_CONTENT_TYPE']),'MULTIPART/FORM-DATA'))) || (false!==(strpos(upper($_SERVER['CONTENT_TYPE']),'MULTIPART/FORM-DATA')))) {
        // Se foi feito o upload de um arquivo 
        if (UPLOAD_ERR_OK==0) {
          $w_maximo = $_REQUEST['w_upload_maximo'];
          foreach ($_FILES as $Chv => $Field) {
            if (!($Field['error']==UPLOAD_ERR_OK || $Field['error']==UPLOAD_ERR_NO_FILE)) {
              // Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
              ScriptOpen('JavaScript');
              ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
              ScriptClose();
              retornaFormulario('w_observacao');
              exit();
            }
            if ($Field['size'] > 0) {
              if ($Field['size'] > $w_maximo) {
                ScriptOpen('JavaScript');
                ShowHTML('  alert(\'Aten��o: o tamanho m�ximo do arquivo n�o pode exceder '.($w_maximo/1024).' KBytes!\');');
                ScriptClose();
                retornaFormulario('w_observacao');
                exit();
              } 
              // Se j� h� um nome para o arquivo, mant�m 
              $w_file = basename($Field['tmp_name']);
              if (!(strpos($Field['name'],'.')===false)) {
                $w_file = $w_file.substr($Field['name'],(strrpos($Field['name'],'.') ? strrpos($Field['name'],'.')+1 : 0)-1,10);
              }
              $w_tamanho = $Field['size'];
              $w_tipo    = $Field['type'];
              $w_nome    = $Field['name'];
              if ($w_file>'') move_uploaded_file($Field['tmp_name'],DiretorioCliente($w_cliente).'/'.$w_file);
            } 
          } 
          $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$w_menu,$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
              $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
              $w_file,$w_tamanho,$w_tipo,$w_nome);
          //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
          if($_REQUEST['w_tramite']!=$_REQUEST['w_novo_tramite']) {
            $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
            $w_sg_tramite = f($RS,'sigla');
            if($w_sg_tramite=='CI') {
              $w_html = VisualAplicacao($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
              CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
            }
          }
        } else {
          ScriptOpen('JavaScript');
          ShowHTML('  alert(\'ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!\');');
          ScriptClose();
        } 
        ScriptOpen('JavaScript');
        if ($P1==0) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      } else {
        $SQL = new dml_putLancamentoEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],
          $_REQUEST['w_novo_tramite'],'N',$_REQUEST['w_observacao'],$_REQUEST['w_destinatario'],$_REQUEST['w_despacho'],
          null,null,null,null);

        $w_html = VisualAplicacao($_REQUEST['w_chave'],'L',$w_usuario,$P1,'1');
        CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);
        
        ScriptOpen('JavaScript');
        if ($P1==0) {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
        } else {
          ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
        }
        ScriptClose();
      }
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } elseif (!(strpos($SG,'ENVAUT')===false)) {
    // Verifica se a Assinatura Eletr�nica � v�lida
    if (verificaAssinaturaEletronica($_SESSION['USERNAME'],$w_assinatura) || $w_assinatura=='') {
      // Trata o recebimento de upload ou dados 
      if ($_REQUEST['w_envio']=='N') {
        $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],null,
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } else {
        $SQL = new dml_putSolicEnvio; $SQL->getInstanceOf($dbms,$_REQUEST['w_menu'],$_REQUEST['w_chave'],$w_usuario,$_REQUEST['w_tramite'],$_REQUEST['w_novo_tramite'],
          $_REQUEST['w_envio'],$_REQUEST['w_despacho'],null,null,null,null);
      } 
      //Rotina para grava��o da imagem da vers�o da solicitac�o no log.
      $sql = new db_getTramiteData; $RS = $sql->getInstanceOf($dbms,$_REQUEST['w_tramite']);
      $w_sg_tramite = f($RS,'sigla');
        ShowHTML(VisualAplicacao($_REQUEST['w_chave'],'V',$w_usuario,$P1,$P4));
        CriaBaseLine($_REQUEST['w_chave'],$w_html,f($RS_Menu,'nome'),$_REQUEST['w_tramite']);

      // Se for envio da fase de cadastramento, remonta o menu principal
      ScriptOpen('JavaScript');
      if ($P1==0) {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,'tesouraria.php?par=inicial&O=L&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.MontaFiltro('GET')).'\';');
      } else {
        ShowHTML('  location.href=\''.montaURL_JS($w_dir,f($RS_Menu,'link').'&O=L&w_chave='.$_REQUEST['w_chave'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS_Menu,'sigla').MontaFiltro('GET')).'\';');
      }
      ScriptClose();
    } else {
      ScriptOpen('JavaScript');
      ShowHTML('  alert("'.$_SESSION['LABEL_ALERTA'].' inv�lida!");');
      ScriptClose();
      retornaFormulario('w_assinatura');
    } 
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Bloco de dados n�o encontrado: '.$SG.'\');');
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
    case 'INICIAL':         Inicial();          break;
    case 'GERAL':           Geral();            break;
    case 'VISUAL':          Visual();           break;
    case 'EXCLUIR':         Excluir();          break;
    case 'ENVIO':           Encaminhamento();   break;
    case 'CONCLUIR':        Concluir();         break;
    case 'GRAVA':           Grava();            break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="'.$conRootSIW.'">');      
      ShowHTML('</head>');
      BodyOpen('onLoad=this.focus();');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();
      ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
      Estrutura_Texto_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Estrutura_Fecha();
      Rodape();
  } 
}
?>