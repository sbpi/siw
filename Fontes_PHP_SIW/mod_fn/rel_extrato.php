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
include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoData.php');
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
include_once($w_dir_volta.'funcoes/selecaoProjeto.php');
include_once($w_dir_volta.'funcoes/selecaoContaBanco.php');

// =========================================================================
//  /rel_extrato.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Relat�rio para concilia��o entre o sistema e o extrato de uma conta banc�ria
// Mail     : alex@sbpi.com.br
// Criacao  : 26/02/2015 10:54
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
if ($_SESSION['LOGON'] != 'Sim') { EncerraSessao(); }

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par = upper($_REQUEST['par']);
$P1 = nvl($_REQUEST['P1'], 0);
$P2 = nvl($_REQUEST['P2'], 0);
$P3 = nvl($_REQUEST['P3'], 1);
$P4 = nvl($_REQUEST['P4'], $conPageSize);
$TP = $_REQUEST['TP'];
$SG = upper($_REQUEST['SG']);
$R = $_REQUEST['R'];
$O = upper($_REQUEST['O']);
$w_assinatura = $_REQUEST['w_assinatura'];
$w_pagina = 'rel_extrato.php?par=';
$w_dir = 'mod_fn/';
$w_troca = $_REQUEST['w_troca'];
$w_tipo = $_REQUEST['w_tipo'];

$p_projeto = $_REQUEST['p_projeto'];
$p_inicio = $_REQUEST['p_inicio'];
$p_fim = $_REQUEST['p_fim'];
$p_conta = upper(trim($_REQUEST['p_conta']));
$p_abertura = $_REQUEST['p_abertura'];

// Declara��o de vari�veis
$dbms = new abreSessao; $dbms = $dbms->getInstanceOf($_SESSION['DBMS']);

if ($O == '') {
  if ($par == 'INICIAL') {
    $O = 'P';
  } else {
    $O = 'L';
  }
}

// Se receber o c�digo do cliente do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente = RetornaCliente();
$w_usuario = RetornaUsuario();
$w_menu    = RetornaMenu($w_cliente, $SG);
$w_TP      = RetornaTitulo($TP, $O);

// Recupera a configura��o do servi�o
$sql = new db_getMenuData; $RS_Menu = $sql->getInstanceOf($dbms,$w_menu);

Main();
FechaSessao($dbms);
exit;

// =========================================================================
// Relat�rio de detalhamento  das despesas de projeto.
// -------------------------------------------------------------------------
function Inicial() {
  extract($GLOBALS);
  global $w_embed;
  
  if ($O == 'L') {
    // Recupera as rubricas do projeto
    $sql = new db_getSolicFN; $RSQuery = $sql->getInstanceOf($dbms,null, $w_usuario, 'EXTRATO', 7, 
        $p_ini_i, $p_ini_f, $p_inicio,$p_fim, $p_atraso, $p_solicitante, 
        $p_unidade, $p_prioridade, $p_ativo, $p_proponente, 
        $p_chave, $p_assunto, $p_conta, $p_regiao, $p_uf, $p_cidade, $p_usu_resp, 
        $p_uorg_resp, $p_palavra, $p_prazo, $p_fase, $p_sqcc, $p_projeto, $p_atividade, 
        $p_acao_ppa, $p_orprior, $p_empenho, $p_processo);
    $RSQuery = SortArray($RSQuery,'nm_banco_debito','asc','cd_agencia_debito','asc','conta_debito','asc','dt_pagamento','asc','cb_valor','asc','nm_pessoa','asc');
  }

  headerGeral('P', $w_tipo, $w_chave, f($RS_Menu,'nome'), $w_embed, null, null, $w_linha_pag,$w_filtro);
  if ($w_embed!='WORD') {
    Cabecalho();
    head();
    ShowHTML('<TITLE>'.$conSgSistema.' - Relat�rio</TITLE>');
    if ($O == 'P') {
      ScriptOpen('JavaScript');
      CheckBranco();
      FormataData();
      FormataValor();
      SaltaCampo();
      ValidateOpen('Validacao');
      Validate('p_projeto', 'Projeto', 'SELECT', '', '1', '18', '', '0123456789');
      Validate('p_conta', 'Conta banc�ria', 'SELECT', '', '1', '18', '', '0123456789');
      Validate('p_inicio', 'Pagamento inicial', 'DATA', '1', '10', '10', '', '0123456789/');
      Validate('p_fim', 'Pagamento final', 'DATA', '1', '10', '10', '', '0123456789/');
      CompData('p_inicio', 'Pagamento inicial', '<=', 'p_fim', 'Pagamento final');
      Validate('p_abertura','Saldo de abertura','VALOR','1',4,18,'','0123456789.,-');
      ValidateClose();
      ScriptClose();
    }
    ShowHTML('<BASE HREF="' . $conRootSIW . '">');
    ShowHTML('</HEAD>');
    if ($O == 'L') {
      BodyOpenClean('onLoad="this.focus()";');
      CabecalhoRelatorio($w_cliente, f($RS_Menu,'nome'), 4, $w_chave);
    } else {
      BodyOpen('onLoad="document.focus()";');
      ShowHTML('<B><FONT COLOR="#000000">' . $w_TP . '</font></B>');
    }
    ShowHTML('<HR>');
  }
  ShowHTML('<div align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if ($O == 'L') {
    // Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    $w_filtro = '';
    if ($p_inicio!='')     $w_filtro = $w_filtro . '<tr valign="top"><td align="right">Pagamento realizado de <td><b>' . $p_inicio . '</b> at� <b>' . $p_fim . '</b>';
    ShowHTML('<tr><td align="left" colspan=2>');
    if ($w_filtro > '') {
      ShowHTML('<table border=0>');
      ShowHTML('  <tr valign="top"><td><b>Filtro:</b><td nowrap><ul>' . $w_filtro . '</ul></tr>');
      ShowHTML('</table>');
      ShowHTML('<hr NOSHADE color=#000000 size=4>');
    }

    $l_html = '';

    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="99%" align="center">';
    $w_conta = 0;
    foreach ($RSQuery as $row) {
      if ($w_conta!==f($row,'sq_conta_debito')) {
        $w_sg_moeda = ((f($row,'sg_moeda_cc')!='') ? ' ('.f($row,'sg_moeda_cc').')' : '');
        if ($w_conta!==0) {
          $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$conTrBgColor.'">';
          $l_html.=chr(13).'        <td align="right" colspan="'.$cs.'"><b>Totais em '.$p_fim.$w_sg_moeda.'&nbsp;</b></td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_credito).'</b></td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_debito).'</b></td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_atual).'</b></td>';
          $l_html.=chr(13).'      </tr>';
          $l_html.=chr(13).'      </table></td></tr>';
        }
        $l_html.=chr(13).'    <tr><td colspan="2"><table width="100%" border="0">';
        if (nvl($p_projeto,'')!='' && $w_conta===0) {
          // Recupera os dados do projeto selecionado
          $sql = new db_getSolicData; $RS_Projeto = $sql->getInstanceOf($dbms,$p_projeto,'PJGERAL');

          if (nvl(f($RS_Projeto,'sq_plano'),'')!='') {
            $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PLANO ESTRAT�GICO: '.(($w_embed=='WORD') ? upper(f($RS_Projeto,'nm_plano')) : ExibePlano('../',$w_cliente,f($RS_Projeto,'sq_plano'),$TP,upper(f($RS_Projeto,'nm_plano')))).'</b></font></td></tr>';
          }
          $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2">PROJETO: <b>'.f($RS_Projeto,'codigo_interno').' - '.f($RS_Projeto,'titulo').' ('.f($RS_Projeto,'sq_siw_solicitacao').')</b></font></td></tr>';
          if ($w_tipo!='EXCEL') {
            $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';

            // Exibe a vincula��o
            $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vincula��o: </b></td>';
            if($w_embed!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S').'</td></tr>';
            else                 $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Projeto,'sq_solic_pai'),f($RS_Projeto,'dados_pai'),'S','S').'</td></tr>';

            $l_html .= chr(13).'      <tr><td><b>In�cio previsto:</b></td>';
            $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'inicio')).' </td></tr>';
            $l_html .= chr(13).'      <tr><td><b>T�rmino previsto:</b></td>';
            $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS_Projeto,'fim')).' </td></tr>';
            $l_html .= chr(13).'      <tr><td><b>Moeda do projeto:</b></td>';
            $l_html .= chr(13).'        <td>'.f($RS_Projeto,'nm_moeda').' </td></tr>';
            $l_html.=chr(13).'        <tr><td><b>Fase atual:</b></td>';
            $l_html.=chr(13).'          <td>'.Nvl(f($RS_Projeto,'nm_tramite'),'-').'</td></tr>';
          }
          $l_html.=chr(13).'      <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=2></br></td></tr>'; 
        }
        if ($w_conta!==0) $l_html.=chr(13).'      <tr><td colspan="2"><br><hr NOSHADE color=#000000 size=2></br></td></tr>'; 
        $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2">CONTA <b>'.f($row,'nm_banco_debito').' AG. '.f($row,'cd_agencia_debito').' C/C '.f($row,'conta_debito').((nvl(f($row,'cb_sg_moeda'),'')!='') ? ' ('.f($row,'cb_sg_moeda').')' : '').'</b></font></td></tr>';
        $l_html .= chr(13).'</table>';
        $l_html.=chr(13).'      <tr><td colspan=2><br><font size="2"><b>LAN�AMENTOS</b></font></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="2" align="center"><table class="tudo" width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $cs = 0;
        $cs++; $l_html.=chr(13).'            <td rowspan="2"><b>C�digo</td>';
        if (nvl($p_projeto,'')=='') { $cs++; $l_html.=chr(13).'            <td rowspan="2"><b>Projeto</td>';}
        $l_html.=chr(13).'            <td colspan="2"><b>Documento</td>';
        $cs++; $l_html.=chr(13).'            <td rowspan="2" width="25%"><b>Pessoa</td>';
        $cs++; $l_html.=chr(13).'            <td rowspan="2" width="40%"><b>Finalidade</td>';
        $l_html.=chr(13).'            <td rowspan="2" width="6%"><b>Cr�dito'.$w_sg_moeda.'</td>';
        $l_html.=chr(13).'            <td rowspan="2" width="6%"><b>D�bito'.$w_sg_moeda.'</td>';
        $l_html.=chr(13).'            <td rowspan="2" width="6%"><b>Saldo'.$w_sg_moeda.'</td>';
        $l_html.=chr(13).'          </tr>';
        $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $cs++; $l_html.=chr(13).'            <td><b>Data</td>';
        $cs++; $l_html.=chr(13).'            <td><b>Tipo e N�mero</td>';
        $l_html.=chr(13).'          </tr>';
        
        $w_conta   = f($row,'sq_conta_debito');
        $w_atual   = floatVal(str_replace(',','.',str_replace('.','',$p_abertura)));
        $w_credito = 0;
        $w_debito  = 0;
        $i         = 0;
      }

      if ($i==0) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="'.($cs+3).'"><b>Saldo de abertura em '.$p_inicio.': &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>'.$p_abertura.'</b></td>';
      }
      $i++;
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td width="1%" nowrap>'.(($w_tipo=='EXCEL') ? '' : ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null));
      $l_html.=chr(13).'          '.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'codigo_interno'),'N',$w_tipo).'</td>';
      if (nvl($p_projeto,'')=='') {
        $w_pai_projeto = false;
        if (Nvl(f($row,'dados_pai'),'')!='') {
          $w_pai = explode('|@|',f($row,'dados_pai'));
          if ($w_pai[0]=='???') {
            $l_html.=chr(13).('        <td>&nbsp;</td>');
          } else {
            if ($w_pai[11]=='PR') {
              $w_pai_projeto = true;
              $l_html.=chr(13).('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N',$w_tipo).'</td>');
            } else {
              if (Nvl(f($row,'dados_avo'),'')!='') {
                $w_avo = explode('|@|',f($row,'dados_avo'));
                if ($w_avo[11]=='PR') {
                  $l_html.=chr(13).('        <td nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_avo'),f($row,'dados_avo'),'N',$w_tipo).'</td>');
                } else {
                  $l_html.=chr(13).('        <td>&nbsp;</td>');
                }
              }
            }
          }
        } else {
          $l_html.=chr(13).('        <td>&nbsp;</td>');
        }
      }
      $l_html.=chr(13).'        <td>&nbsp;'.Nvl(FormataDataEdicao(f($row,'dt_pagamento'),5),'-').'&nbsp;</td>';
      $l_html.=chr(13).'        <td nowrap title="'.f($row,'nm_doc').'">'.f($row,'sg_doc').' '.f($row,'nr_doc').'</td>';
      if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
        if ($w_tipo!='WORD') $l_html.=chr(13).'        <td width="25%">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa')).'</td>';
        else                 $l_html.=chr(13).'        <td width="25%">'.f($row,'nm_pessoa_resumido').'</td>';
      } else {
        $l_html.=chr(13).'        <td align="center" width="25%">---</td>';
      }
      $l_html.=chr(13).'        <td width="40%">'.f($row,'descricao').'</td>';
      if (f($row,'tipo')=='C') {
        $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row,'cb_valor')).'</td>';
        $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
        $w_credito += Nvl(f($row,'cb_valor'),0);
        $w_atual   += Nvl(f($row,'cb_valor'),0);
      } else {
        $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
        $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row,'cb_valor')).'</td>';
        $w_debito += Nvl(f($row,'cb_valor'),0);
        $w_atual  -= Nvl(f($row,'cb_valor'),0);
      }
      $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber($w_atual).'</td>';
    } 
    $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'        <td align="right" colspan="'.$cs.'"><b>Totais em '.$p_fim.$w_sg_moeda.'&nbsp;</b></td>';
    $l_html.=chr(13).'        <td align="right" nowrap><b>'.formatNumber($w_credito).'</b></td>';
    $l_html.=chr(13).'        <td align="right" nowrap><b>'.formatNumber($w_debito).'</b></td>';
    $l_html.=chr(13).'        <td align="right" nowrap><b>'.formatNumber($w_atual).'</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      </table></td></tr>';
    ShowHTML($l_html);
    ShowHTML('    </table>');
    ShowHTML('  </td>');
    ShowHTML('</tr>');
  } elseif ($O == 'P') {
    AbreForm('Form', $w_dir . $w_pagina . $par, 'POST', 'return(Validacao(this));', 'Contas', $P1, $P2, $P3, $P4, $TP, $SG, $R, 'L');
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<tr bgcolor="' . $conTrBgColor . '"><td>');
    ShowHTML('    <table width="99%" border="0">');
    ShowHTML('      <tr valign="top">');
    SelecaoContaBanco('C<u>o</u>nta banc�ria:','O','Selecione a conta banc�ria envolvida no lan�amento.',$p_conta,null,'p_conta',null,null);
    ShowHTML('      </tr>');
    ShowHTML('      <tr>');
    $sql = new db_getLinkData; $RS = $sql->getInstanceOf($dbms,$w_cliente,'PJCAD');
    SelecaoProjeto('Se preferir emtir todas as contas de um pro<u>j</u>eto, deixe o campo acima em branco e selecione o projeto desejado:','J','Selecione o projeto do contrato na rela��o.',$p_projeto,$w_usuario,f($RS,'sq_menu'),null,null,null,'p_projeto','PJLIST',$w_atributo);
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td><b><u>P</u>agamento entre:</b><br><input accesskey="P" type="text" name="p_inicio" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_inicio . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_inicio') . ' e <input ' . $w_Disabled . ' type="text" name="p_fim" class="sti" SIZE="10" MAXLENGTH="10" VALUE="' . $p_fim . '" onKeyDown="FormataData(this,event);" onKeyUp="SaltaCampo(this.form.name,this,10,event);">' . ExibeCalendario('Form', 'p_fim') . '</td>');    ShowHTML('      <tr>');
    ShowHTML('      <tr valign="top">');
    ShowHTML('          <td><b><u>S</u>aldo de abertura do per�odo:</b><br><input accesskey="S" type="text" name="p_abertura" class="sti" SIZE="18" MAXLENGTH="18" VALUE="'.nvl($p_abertura,'0,00').'" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);" title="Informe o saldo de abertura da conta."></td>');
    ShowHTML('      </tr>');
    ShowHTML('      <tr><td align="center"><hr>');
    ShowHTML('            <input class="STB" type="submit" name="Botao" value="Exibir">');
    ShowHTML('            <input class="STB" type="button" onClick="location.href=\'' . montaURL_JS($w_dir, $w_pagina . $par . '&R=' . $R . '&P1=' . $P1 . '&P2=' . $P2 . '&P3=' . $P3 . '&P4=' . $P4 . '&TP=' . $TP . '&O=P&SG=' . $SG) . '\';" name="Botao" value="Limpar campos">');
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
  ShowHTML('</div>');

  if($w_tipo=='PDF') RodapePdf();
  else               Rodape();
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
    case 'INICIAL': Inicial(); break;
    default:
      cabecalho();
      ShowHTML('<BASE HREF="' . $conRootSIW . '">');
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
