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
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/sp/db_getMatServ.php');
include_once($w_dir_volta.'funcoes/selecaoTipoMatServ.php');

// =========================================================================
//  /relatorios.php
// ------------------------------------------------------------------------
// Nome     : Celso Miguel Lago Filho
// Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial do módulo de compras e licitações
// Mail     : celso@sbpi.com.br
// Criacao  : 11/10/2007 17:00
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
// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }
// Declaração de variáveis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);
// Carrega variáveis locais com os dados dos parâmetros recebidos
$w_troca    = $_REQUEST['w_troca'];
$w_copia    = $_REQUEST['w_copia'];
$par        = upper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],1);
$P4         = nvl($_REQUEST['P4'],$conPageSize);
$TP         = $_REQUEST['TP'];
$SG         = upper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = upper($_REQUEST['O']);
$p_ordena   = lower($_REQUEST['p_ordena']);
$w_tipo     = $_REQUEST['w_tipo'];
$w_assinatura   = upper($_REQUEST['w_assinatura']);
$w_pagina       = 'relatorios.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir          = 'mod_cl/';
if ($O=='') $O='P';
switch ($O) {
  case 'I': $w_TP=$TP.' - Inclusão';    break;
  case 'A': $w_TP=$TP.' - Alteração';   break;
  case 'E': $w_TP=$TP.' - Exclusão';    break;
  case 'P': $w_TP=$TP.' - Filtragem';   break;
  case 'C': $w_TP=$TP.' - Cópia';       break;
  case 'V': $w_TP=$TP.' - Envio';       break;
  case 'H': $w_TP=$TP.' - Herança';     break;
  default:  $w_TP=$TP.' - Listagem';    break;
} 
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();
$w_menu     = RetornaMenu($w_cliente,$SG);
$w_ano      = RetornaAno();
// Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
$RS = db_getLinkSubMenu::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
if (count($RS)>0) {
  $w_submenu = 'Existe';
} else {
  $w_submenu = '';
}
// Recupera a configuração do serviço
if ($P2>0) {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$P2);
} else {
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,$w_menu);
}
// Se for sub-menu, pega a configuração do pai
if (f($RS_Menu,'ultimo_nivel')=='S') { 
  $RS_Menu = new db_getMenuData; $RS_Menu = $RS_Menu->getInstanceOf($dbms,f($RS_Menu,'sq_menu_pai'));
} 

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relatório de itens de ata (ARP)
// -------------------------------------------------------------------------
function Rel_ItensAta() {
  extract($GLOBALS);
  Global $w_Disabled;

  $p_tipo_material      = $_REQUEST['p_tipo_material'];
  $p_codigo             = $_REQUEST['p_codigo'];
  $p_nome               = $_REQUEST['p_nome'];
  $p_ordena             = $_REQUEST['p_ordena'];
  $p_ata_aviso          = $_REQUEST['p_ata_aviso'];
  $p_ata_invalida       = $_REQUEST['p_ata_invalida'];
  $p_ata_valida         = $_REQUEST['p_ata_valida']; 
  $p_aviso              = $_REQUEST['p_aviso'];
  $p_invalida           = $_REQUEST['p_invalida'];
  $p_valida             = $_REQUEST['p_valida']; 
  $p_branco             = $_REQUEST['p_branco']; 
  $p_numero_ata         = $_REQUEST['p_numero_ata']; 
  $p_acrescimo          = $_REQUEST['p_acrescimo']; 
  if (strpos('L',$O)!==false) {
    if (montaFiltro('GET')!='') {
      $w_filtro='';
      if ($p_codigo>'')  $w_filtro.='<tr valign="top"><td align="right">Código <td>[<b>'.$p_codigo.'</b>] em qualquer parte';
      if ($p_nome>'')    $w_filtro.='<tr valign="top"><td align="right">Nome <td>[<b>'.$p_nome.'</b>] em qualquer parte';
      if ($p_tipo_material>'') {
        $RS = db_getTipoMatServ::getInstanceOf($dbms,$w_cliente,$p_tipo_material,null,null,null,null,null,null,'REGISTROS');
        foreach ($RS as $row) { $RS = $row; break; }
        $w_filtro.='<tr valign="top"><td align="right">Tipo <td>[<b>'.f($RS,'nome_completo').'</b>]';
      } 
      if ($w_filtro>'')     $w_filtro='<div align="left"><table border=0><tr valign="top"><td><b>Filtro:</b><td nowrap><ul>'.$w_filtro.'</ul></tr></table></div>';
    } 
    $RS = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,null,$p_tipo_material,$p_codigo,$p_nome,'S',null,$p_ata_aviso,$p_ata_invalida,$p_ata_valida,$p_aviso,$p_invalida,$p_valida,$p_branco,'S',null,$p_numero_ata,$p_acrescimo,'RELATORIO');
    if (Nvl($p_ordena,'') > '') {
      $lista = explode(',',str_replace(' ',',',$p_ordena));
      $RS = SortArray($RS,$lista[0],$lista[1],'numero_ata','asc','nr_item_ata','asc');
    } else {
      $RS = SortArray($RS,'numero_ata','asc','nr_item_ata','asc'); 
    }
  } 
  if ($w_tipo=='WORD') {
    HeaderWord($_REQUEST['orientacao']);
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    CabecalhoWord($w_cliente,'ITENS DE ARP',$w_pag);
  } else {
    if($O=='P') Cabecalho();
    head();
    Estrutura_CSS($w_cliente);
    ShowHTML('<TITLE>Itens de ARP</TITLE>');
    Estrutura_CSS($w_cliente);
    if (strpos('P',$O)!==false) {
      ScriptOpen('JavaScript');
      ValidateOpen('Validacao');
      Validate('p_nome','Nome','1','','3','30','1','1');
      Validate('p_codigo','Código interno','1','','2','30','1','1');
      Validate('p_tipo_material','Tipo do material ou serviço','SELECT','','1','18','','1');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('</HEAD>');
    ShowHTML('<BASE HREF="'.$conRootSIW.'">');
    if ($w_troca>'') {
      BodyOpen('onLoad="document.Form.'.$w_troca.'.focus();"');
    } elseif ($O=='P'){
      BodyOpen('onLoad="document.Form.p_nome.focus();"');
      Estrutura_Topo_Limpo();
      Estrutura_Menu();
      Estrutura_Corpo_Abre();
      Estrutura_Texto_Abre();      
    } elseif ($O=='L') {
      BodyOpenClean('onLoad=\'this.focus()\'; ');
      CabecalhoRelatorio($w_cliente,'ITENS DE ARP',4,null);
    }
  } 
  if ($w_filtro > '') ShowHTML($w_filtro);
  ShowHTML('<div align="center">');
  ShowHTML('<table width="100%" border="0" cellspacing="3">');
  if ($O=='L') {
    ShowHTML('<tr valign="top">');
    ShowHTML('    <td align="right" colspan=3><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('      <table border="1" bordercolor="#00000" width="100%">');
    ShowHTML('        <tr align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Ata','numero_ata').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Fim Vigência','fim').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Item','nr_item_ata').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Detentor','nm_detentor_ata').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Código','codigo_interno').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Nome','nome').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('CMM','quantidade').'</td>');
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('Preço','valor_unidade').'</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>Ata</td>');
      ShowHTML('          <td rowspan=2><b>Fim vigência</td>');
      ShowHTML('          <td rowspan=2><b>Item</td>');
      ShowHTML('          <td rowspan=2><b>Detentor</td>');
      ShowHTML('          <td rowspan=2><b>Código</td>');
      ShowHTML('          <td rowspan=2><b>Nome</td>');
      ShowHTML('          <td rowspan=2><b>Qtd</td>');
      ShowHTML('          <td rowspan=2><b>Preço</td>');
    }
    ShowHTML('          <td colspan=3><b>Última pesquisa</b></td>');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td rowspan=2><b>'.LinkOrdena('% Dif.','variacao_valor').'</td>');
    } else {
      ShowHTML('          <td rowspan=2><b>% Dif.</td>');
    }
    ShowHTML('        <tr align="center">');
    if ($w_tipo!='WORD') {
      ShowHTML('          <td colspan=2><b>'.LinkOrdena('Validade','pesquisa_validade').'</td>');
      ShowHTML('          <td nowrap><b>'.LinkOrdena('$ Médio','pesquisa_preco_medio').'</td>');
    } else {
      ShowHTML('          <td colspan=2><b>Validade</b></td>');
      ShowHTML('          <td nowrap><b>$ Médio</b></td>');
    }
    ShowHTML('        </tr>');
    if (count($RS)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      ShowHTML('      <tr><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS as $row){
        ShowHTML('      <tr valign="top">');
        // Se a validade da proposta for menor que o exigido, destaca em vermelho
        $w_percentual_acrescimo = f($row,'percentual_acrescimo');
        if (f($row,'variacao_valor')>f($row,'percentual_acrescimo')) {
          $w_destaque = ' BGCOLOR="'.$conTrBgColorLightRed2.'"';
        } else {
          $w_destaque = '';
        }
        ShowHTML('        <td align="center" width="1%" nowrap>');
        ShowHTML(ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio'),f($row,'fim'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
        if ($w_tipo!='WORD') ShowHTML('        <A class="hl" HREF="mod_ac/contratos.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4=0&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="'.f($row,'objeto').'">'.f($row,'numero_ata').'&nbsp;</a>');
        else                 ShowHTML('        '.f($row,'numero_ata').'');
        ShowHTML('        <td align="center">'.formataDataEdicao(f($row,'fim'),5).'</td>');
        ShowHTML('        <td align="center">'.f($row,'nr_item_ata').'</td>');
        if($w_tipo=='WORD') ShowHTML('        <td nowrap>'.f($row,'nm_detentor_ata').'</td>');
        else                ShowHTML('        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_detentor_ata'),$TP,f($row,'nm_detentor_ata')).'</td>');
        ShowHTML('        <td align="center" width="1%" nowrap>'.f($row,'codigo_interno').'</td>');
        if ($w_tipo!='WORD') {
          ShowHTML('        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'chave'),$TP,null).'</td>');
        } else {
          ShowHTML('        <td>'.f($row,'nome').'</td>');
        }
        ShowHTML('        <td align="right">'.formatNumber(f($row,'quantidade'),2).'</td>');
        ShowHTML('        <td align="right" '.$w_destaque.'>'.nvl(formatNumber(f($row,'valor_unidade'),4),'---').'</td>');
        if (nvl(f($row,'pesquisa_data'),'')=='') {
          ShowHTML('        <td colspan=3 align="center" nowrap>Sem pesquisa de preço</td>');
        } else {
          ShowHTML('        <td align="center" width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row,'pesquisa_data'),f($row,'pesquisa_validade'),f($row,'pesquisa_aviso')).'</td>');
          ShowHTML('        <td align="center" width="1%" nowrap>'.nvl(formataDataEdicao(f($row,'pesquisa_validade'),5),'---').'</td>');
          if (nvl(f($row,'pesquisa_preco_medio'),'')=='') {
            ShowHTML('        <td align="right" width="1%" nowrap>&nbsp;</td>');
          } else {
            ShowHTML('        <td align="right" '.$w_destaque.' width="1%" nowrap>'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),4),'---').'</td>');
          }
        }                
        if (nvl(f($row,'pesquisa_preco_medio'),'')=='') {
          ShowHTML('        <td align="right" width="1%" nowrap>&nbsp;</td>');
        } else {
          ShowHTML('        <td align="right" '.$w_destaque.' width="1%" nowrap>'.nvl(formatNumber(f($row,'variacao_valor'),2),'---').'</td>');
        }
        ShowHTML('        </tr>');
      }
    } 
    ShowHTML('    </table>');
    ShowHTML('<tr><td colspan="2"><b>Observação: linhas com fundo vermelho indicam valor de compra fora da faixa aceitável ($ médio + '.$w_percentual_acrescimo.'%).');
    ShowHTML('      <tr><table border=0 width="100%"><tr><td colspan=3><b>Legenda:</b><tr><td>'.ExibeSinalPesquisa(true,null,null,null).'</td></tr></table>');
    ShowHTML('  </td>');
    ShowHTML('      </center>');
  } elseif ($O=='P') {
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>');
    ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td valign="top"><table border=0 width="100%" cellspacing=0>');
    AbreForm('Form',$w_dir.$w_pagina.$par,'POST','return(Validacao(this));','Relatorio',$P1,$P2,$P3,null,$TP,$SG,$R,'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_chave" value="'.$w_chave.'">');
    ShowHTML('<INPUT type="hidden" name="w_menu" value="'.$w_menu.'">');
    ShowHTML('      <tr><td><table border=0 width="100%" cellspacing=0>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>N</u>ome:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="p_nome" class="sti" SIZE="40" MAXLENGTH="100" VALUE="'.$p_nome.'"></td>');
    ShowHTML('          <td><b><u>C</u>ódigo:</b><br><input '.$p_Disabled.' accesskey="C" type="text" name="p_codigo" class="sti" SIZE="20" MAXLENGTH="30" VALUE="'.$p_codigo.'"></td>');
    ShowHTML('      <tr valign="top">');
    selecaoTipoMatServ('T<U>i</U>po:','I',null,$p_tipo_material,null,'p_tipo_material','FOLHA',null);
    ShowHTML('          <td><b>Número da <u>A</u>RP:</b><br><input '.$p_Disabled.' accesskey="N" type="text" name="p_numero_ata" class="sti" SIZE="20" MAXLENGTH="60" VALUE="'.$p_numero_ata.'"></td>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>Validade da ARP:</b>');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_ata_aviso" value="S" CHECKED>Aviso');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_ata_valida" value="S" CHECKED>Válida');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_ata_invalida" value="S">Expirada');
    ShowHTML('          <td valign="top"><b>Validade da pesquisa:</b>');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_aviso" value="S" CHECKED>Aviso');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_invalida" value="S" CHECKED>Inválida');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_valida" value="S" CHECKED>Válida');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_branco" value="S" CHECKED>Sem pesquisa');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td valign="top"><b>Critérios adicionais:</b>');
    ShowHTML('          <BR><input type="CHECKBOX" name="p_acrescimo" value="S"><b>Somente itens acima do limite</b>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY="L" '.$w_Disabled.' class="sti" type="text" name="P4" size="4" maxlength="4" value="'.$P4.'"></td></tr>');
    ShowHTML('      </table>');
    ShowHTML('      <tr><td align="center" height="1" bgcolor="#000000">');
    ShowHTML('      <tr><td align="center">');
    ShowHTML('            <input class="stb" type="submit" name="Botao" value="Aplicar filtro">');
    ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.montaURL_JS($w_dir,$w_pagina.$par.'&w_chave='.$w_chave.'&w_menu='.$w_menu.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\';" name="Botao" value="Remover filtro">');
    ShowHTML('          </td>');
    ShowHTML('      </tr>');
    ShowHTML('    </table>');
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
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'REL_ITENSATA':   Rel_ItensAta();    break;
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