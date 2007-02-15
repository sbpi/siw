<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_getUserData.php');
include_once('classes/sp/db_getPersonData.php');
include_once('classes/sp/db_getPersonList.php');
include_once('classes/sp/db_getCustomerSite.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getLinkData.php');
include_once('classes/sp/db_verificaAssinatura.php');
include_once('classes/sp/dml_putSiwUsuario.php');
include_once('funcoes/selecaoPais.php');
include_once('funcoes/selecaoEstado.php');
include_once('funcoes/selecaoCidade.php');
include_once('funcoes/selecaoUnidade.php');
include_once('funcoes/selecaoLocalizacao.php');
include_once('funcoes/selecaoVinculo.php');

// =========================================================================
//  /pessoa.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papad�polis
// Descricao: Gerencia o m�dulo de formul�rios do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 25/11/2002 16:17
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
//                   = V   : Envio
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicita��o de envio

// Verifica se o usu�rio est� autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declara��o de vari�veis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega vari�veis locais com os dados dos par�metros recebidos
$par        = strtoupper($_REQUEST['par']);
$P1         = nvl($_REQUEST['P1'],0);
$P2         = nvl($_REQUEST['P2'],0);
$P3         = nvl($_REQUEST['P3'],0);
$P4         = nvl($_REQUEST['P4'],0);
$TP         = $_REQUEST['TP'];
$SG         = strtoupper($_REQUEST['SG']);
$R          = $_REQUEST['R'];
$O          = strtoupper($_REQUEST['O']);
$w_assinatura   = strtoupper($_REQUEST['w_assinatura']);
$w_pagina       = 'pessoa.php?par=';
$w_Disabled     = 'ENABLED';
$w_dir_volta    = '';
if (($par=='DESPESA' || $par=='TRECHO' || $par=='VISUAL') && $O=='A' && $_REQUEST['w_Handle']=='') $O='L';

// Configura o valor de O se for a tela de listagem
switch ($O) {
  case 'I': 
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') $w_TP=$TP.' - Novo Acesso';
    elseif ($SG=='RHUSU') $w_TP=$TP.' - Nova Pessoa';
    else$w_TP=$TP.' - Inclus�o';
    break;
  case 'A': // Se a chamada for para as rotinas de visualiza��o, n�o concatena nada
    if ($par=='VISUAL' || $par=='ENVIAR') $w_TP=$TP;
    else $w_TP=$TP.' - Altera��o';
    break;
  case 'D':
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') $w_TP=$TP.' - Bloqueio de Acesso';
    elseif ($SG=='RHUSU') $w_TP=$TP.' - Desligamento';
    break;
  case 'T': $w_TP=$TP.' - Ativa��o';  break;
  case 'E': $w_TP=$TP.' - Exclusao';  break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default:
    if ($par=='BUSCAUSUARIO') $w_TP=$TP.' - Busca usu�rio';
    else $w_TP=$TP.' - Listagem';
    break;
} 
$w_data_banco = time();

// Se for acesso do m�dulo de gerenciamento de clientes do SIW, o cliente ser� determinado por par�metro;
// caso contr�rio, o cliente ser� a empresa ao qual o usu�rio logado est� vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();

Main();
FechaSessao($dbms);

// =========================================================================
// Rotina de benefici�rio
// -------------------------------------------------------------------------
function Benef() {
  extract($GLOBALS);
  global $w_Disabled;
  // Nesta rotina, P1 = 0 indica que n�o pode haver troca do benefici�rio
  //                  = 1 indica que pode haver troca de benefici�rio
  //               P2 = 0 indica que n�o pegar� os dados banc�rios, nem da forma de pagamento
  //                  = 1 indica que pegar� os dados banc�rios, mas n�o da forma de pagamento
  //                  = 2 indica que pegar� os dados banc�rios e tamb�m da forma de pagamento
  $w_readonly       = '';
  $w_erro           = '';
  $w_troca          = $_REQUEST['w_troca'];
  $w_sq_pessoa      = $_REQUEST['w_sq_pessoa'];
  $p_data_inicio    = strtoupper($_REQUEST['p_data_inicio']);
  $p_data_fim       = strtoupper($_REQUEST['p_data_fim']);
  $p_solicitante    = strtoupper($_REQUEST['p_solicitante']);
  $p_numero         = strtoupper($_REQUEST['p_numero']);
  $p_ordena         = strtoupper($_REQUEST['p_ordena']);
  $p_localizacao    = strtoupper($_REQUEST['p_localizacao']);
  $p_lotacao        = strtoupper($_REQUEST['p_lotacao']);
  $p_nome           = strtoupper($_REQUEST['p_nome']);
  $p_gestor         = strtoupper($_REQUEST['p_gestor']);
  $w_sq_solicitacao = $_REQUEST['w_sq_solicitacao'];
  $w_username       = $_REQUEST['w_username'];
  // Verifica se h� necessidade de recarregar os dados da tela a partir
  // da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da p�gina
    $w_username             = $_REQUEST['w_username'];
    $w_nome                 = $_REQUEST['w_nome'];
    $w_nome_resumido        = $_REQUEST['w_nome_resumido'];
    $w_rg                   = $_REQUEST['w_rg'];
    $w_passaporte           = $_REQUEST['w_passaporte'];
    $w_nascimento           = $_REQUEST['w_nascimento'];
    $w_end                  = $_REQUEST['w_end'];
    $w_comple               = $_REQUEST['w_comple'];
    $w_pais                 = $_REQUEST['w_pais'];
    $w_uf                   = $_REQUEST['w_uf'];
    $w_cidade               = $_REQUEST['w_cidade'];
    $w_cep                  = $_REQUEST['w_cep'];
    $w_telefone             = $_REQUEST['w_telefone'];
    $w_fax                  = $_REQUEST['w_fax'];
    $w_email                = $_REQUEST['w_email'];
    $w_sq_unidade_lotacao   = $_REQUEST['w_sq_unidade_lotacao'];
    $w_sq_localizacao       = $_REQUEST['w_sq_localizacao'];
    $w_projeto              = $_REQUEST['w_projeto'];
    $w_entrada              = $_REQUEST['w_entrada'];
    $w_saldo_ferias         = $_REQUEST['w_saldo_ferias'];
    $w_limite_emprestimo    = $_REQUEST['w_limite_emprestimo'];
    $w_sq_tipo_vinculo      = $_REQUEST['w_sq_tipo_vinculo'];
    $w_gestor_seguranca     = $_REQUEST['w_gestor_seguranca'];
    $w_gestor_sistema       = $_REQUEST['w_gestor_sistema'];
  } else {
    if ($O=='I' && $w_sq_pessoa=='' && $w_username>'' && $SG=='SGUSU') {
      $RS = db_getUserData::getInstanceOf($dbms,$w_cliente,$w_username);
      if (count($RS)) {
        ScriptOpen('JavaScript');
        ShowHTML('  alert(\'Usu�rio j� existente!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
        exit;
      } 
    } 
    if (strpos('IATDEV',$O)!==false) {
      if (nvl($w_sq_pessoa,'')!='') {
        // Recupera os dados do benefici�rio em co_pessoa
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,$w_sq_pessoa,null,null);
        if (count($RS)) {
          $w_nome                 = f($RS,'Nome');
          $w_nome_resumido        = f($RS,'Nome_Resumido');
          $w_email                = f($RS,'Email');
          $w_sq_unidade_lotacao   = f($RS,'sq_unidade');
          $w_sq_localizacao       = f($RS,'sq_localizacao');
          $w_sq_tipo_vinculo      = f($RS,'sq_tipo_vinculo');
          $w_gestor_seguranca     = f($RS,'gestor_seguranca');
          $w_gestor_sistema       = f($RS,'gestor_sistema');
        } 
      } elseif (nvl($w_username,'')>'') {
        // Recupera os dados do benefici�rio em co_pessoa
        $RS = db_getPersonData::getInstanceOf($dbms,$w_cliente,null,$w_username,null);
        if (count($RS)) {
          $w_sq_pessoa            = f($RS,'sq_pessoa');
          $w_nome                 = f($RS,'Nome');
          $w_nome_resumido        = f($RS,'Nome_Resumido');
          $w_email                = f($RS,'Email');
          $w_sq_unidade_lotacao   = f($RS,'sq_unidade');
          $w_sq_localizacao       = f($RS,'sq_localizacao');
          $w_sq_tipo_vinculo      = f($RS,'sq_tipo_vinculo');
          $w_gestor_seguranca     = f($RS,'gestor_seguranca');
          $w_gestor_sistema       = f($RS,'gestor_sistema');
        }
      }
    } 
    // O bloco abaixo recupera os dados banc�rios e a forma de pagamento,
    // dependendo do valor de P1 e se n�o for inclus�o
    // O local onde os dados banc�rios e a forma de pagamento ser�o recuperados
    // depende do tipo de documento.
    if ($O!='I' && ($P2==1 || $P2==2)) {
      // Vide finalidade do par�metro no cabe�alho da rotina
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  // tratando as particularidades de cada servi�o
  ScriptOpen('JavaScript');
  Modulo();
  FormataCPF();
  FormataCEP();
  CheckBranco();
  FormataValor();
  FormataData();
  FormataDataHora();
  ValidateOpen('Validacao');
  if ($w_username=="" || (!(strpos($_REQUEST['botao'],"Procurar")===false)) || (!(strpos($_REQUEST['botao'],"Troca")===false))) {
    // Se o benefici�rio ainda n�o foi selecionado
    ShowHTML('  if (theForm.Botao.value == \'Procurar\') {');
    Validate('w_nome','Nome','','1','4','20','1','');
    ShowHTML('  theForm.Botao.value = \'Procurar\';');
    ShowHTML('}');
    ShowHTML('else {');
    Validate('w_username','CPF','CPF','1','14','14','','0123456789-.');
    if ($P2==2) {
      Validate('w_frm_pag','Forma de pagamento','SELECT','1','1','10','','1');
    } 
    ShowHTML('}');
  } elseif ($O=='I' || $O=='A') {
    ShowHTML('  if (theForm.Botao.value == \'Troca\') { return true; }');
    Validate('w_nome','Nome','1',1,5,60,'1','1');
    Validate('w_nome_resumido','Nome resumido','1',1,2,15,'1','1');
    if ($SG=='RHUSU') {
      if (strlen($w_username)!=10) {
        Validate('w_rg','RG','1',1,5,80,'1','1');
        Validate('w_passaporte','Passaporte','1','',1,15,'1','1');
      } else {
        Validate('w_passaporte','Passaporte','1',1,1,15,'1','1');
      } 
      Validate('w_nascimento','Data de Nascimento','DATA',1,10,10,'',1);
      Validate('w_end','Endere�o','1',1,4,50,'1','1');
      Validate('w_pais','Pa�s','SELECT',1,1,10,'1','1');
      Validate('w_uf','UF','SELECT',1,1,10,'1','1');
      Validate('w_cidade','Cidade','SELECT',1,1,10,'','1');
      if ($w_pais=='' || $w_pais==1) {
        Validate('w_cep','CEP','1','',1,10,'','1');
      } else {
        Validate('w_cep','CEP','1',1,6,10,'','1');
      } 
      Validate('w_telefone','Telefone','1',1,7,40,'1','1');
      Validate('w_fax','Fax','1','',4,20,'1','1');
    } else if ($SG=='SGUSU' || $SG=='CLUSUARIO') {
      Validate('w_email','E-Mail','1','1',4,50,'1','1');
    } 
    Validate('w_sq_unidade_lotacao','Unidade de lota��o','HIDDEN',1,1,10,'','1');
    Validate('w_sq_localizacao','Localiza��o','SELECT',1,1,10,'','1');
    Validate('w_sq_tipo_vinculo','V�nculo com a organiza��o','SELECT',1,1,10,'','1');
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') {
      Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
    } 
  } elseif ($O=='E' || $O=='T' || $O=='D') {
    Validate('w_assinatura','Assinatura Eletr�nica','1','1','6','30','1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($P1!=0 && ($w_username=='' || (isset($_REQUEST['Botao']) && ((!(strpos($_REQUEST['Botao'],'Troca')===false)) || (!(strpos($_REQUEST['Botao'],'Procurar')===false)))))) {
    // Se o benefici�rio ainda n�o foi selecionado
    if (isset($_REQUEST['Botao']) && (!(strpos($_REQUEST['botao'],'Procurar'))===false)) {
      // Se est� sendo feita busca por nome
      if ($w_troca!='w_sq_localizacao') { BodyOpen('onLoad=\'this.focus()\';'); }
    } else {
      BodyOpen('onLoad=\'document.Form.w_username.focus()\';');
    }
  } elseif ($w_troca>'') {
    BodyOpen('onLoad=\'document.Form.'.$w_troca.'.focus()\';');
  } elseif (!(strpos('ETDV',$O)===false)) {
    BodyOpen('onLoad=\'document.Form.w_assinatura.focus()\';');
  } else {
    BodyOpen('onLoad=\'document.Form.w_nome.focus()\';');
  } 
  Estrutura_Topo_Limpo();
  Estrutura_Menu();
  Estrutura_Corpo_Abre();
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  if (!(strpos('IAETDV',$O)===false)) {
     if (!(strpos('ETDV',$O)===false)) {
      $w_Disabled=' DISABLED ';
      if ($O=='V') $w_Erro = Validacao($w_sq_solicitacao, $SG);
    } 
    if ($w_username=='' || (isset($_REQUEST['Botao']) && ((!(strpos($_REQUEST['Botao'],'Troca')===false)) || (!(strpos($_REQUEST['Botao'],'Procurar')===false))))) {
      // Se o benefici�rio ainda n�o foi selecionado
      AbreForm('Form',$w_pagina.$par,'POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$R,$O);
    } else {
      AbreForm('Form',$w_pagina.'Grava','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,$w_pagina.$par,$O);
    } 
    ShowHTML('<INPUT type="hidden" name="w_troca" value="">');
    ShowHTML('<INPUT type="hidden" name="w_sq_solicitacao" value="'.$w_sq_solicitacao.'">');
    ShowHTML('<INPUT type="hidden" name="w_sq_pessoa" value="'.$w_sq_pessoa.'">');
    ShowHTML('<INPUT type="hidden" name="w_cliente" value="'.$w_cliente.'">');
    ShowHTML(MontaFiltro('POST'));
    if ($P1!=0 && ($w_username=='' || (isset($_REQUEST['Botao']) && ((!(strpos($_REQUEST['Botao'],'Troca')===false)) || (!(strpos($_REQUEST['Botao'],'Procurar')===false)))))) {
      $w_frm_pag = $_REQUEST['w_frm_pag'];
      $w_nome    = $_REQUEST['w_nome'];
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table border="0">');
      ShowHTML('        <tr><td colspan=3><font size=2>Informe os dados abaixo e clique no bot�o "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr><td><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" Class="sti" NAME="w_username" VALUE="'.$w_username.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('            <td valign="bottom"><INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_pagina.$par.'\'">');
      if ($SG=='SGUSU' || $SG=='RHUSU' || $SG=='CLUSUARIO') { // Tela de usu�rios do SG ou RH 
        ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.$R.'&w_cliente='.$_REQUEST['w_cliente'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';" name="Botao" value="Cancelar">');
      } 
      ShowHTML('        <tr><td colspan=3><p>&nbsp</p>');
      ShowHTML('        <tr><td colspan=3 heigth=1 bgcolor="#000000">');
      ShowHTML('        <tr><td colspan=3>');
      ShowHTML('             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY="P" TYPE="text" Class="sti" NAME="w_nome" VALUE="'.$w_nome.'" SIZE="20" MaxLength="20">');
      ShowHTML('              <INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Procurar" onClick="Botao.value=this.value; document.Form.action=\''.$w_pagina.$par.'\'">');
      ShowHTML('      </table>');
      if ($_REQUEST['w_nome']>"") {
        $RS = db_getPersonList::getInstanceOf($dbms,$w_cliente,null,"PESSOA",$_REQUEST['w_nome'],null,null,null);
        ShowHTML('<tr><td align="center" colspan=3>');
        ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
        ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
        ShowHTML('          <td><font size="1"><b>Nome</font></td>');
        ShowHTML('          <td><font size="1"><b>Nome resumido</font></td>');
        ShowHTML('          <td><font size="1"><b>CPF</font></td>');
        ShowHTML('          <td><font size="1"><b>Opera��es</font></td>');
        ShowHTML('        </tr>');
        if (count($RS)==0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font  size="2"><b>N�o h� pessoas (n�o usu�rias) que contenham o texto informado.</b></td></tr>');
        } else {
          foreach($RS as $row) {
            ShowHTML('      <tr bgcolor="'.$conTrBgColor.'" valign="top">');
            ShowHTML('        <td><font  size="1">'.f($row,'nome').'</td>');
            ShowHTML('        <td><font  size="1">'.f($row,'nome_resumido').'</td>');
            ShowHTML('        <td align="center"><font  size="1">'.nvl(f($row,'cpf'),"---").'</td>');
            ShowHTML('        <td nowrap><font size="1">');
            ShowHTML('          <A class="hl" HREF="pessoa.php?par=BENEF&R='.$R.'&O=I&w_username='.f($row,'cpf').'&w_sq_pessoa='.f($row,'sq_pessoa').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'">Selecionar</A>&nbsp');
            ShowHTML('        </td>');
            ShowHTML('      </tr>');
          } 
        } 
        ShowHTML('      </center>');
        ShowHTML('    </table>');
        ShowHTML('  </td>');
        ShowHTML('</tr>');
        DesConectaBD();
      } 
    } else {
      ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('    <table width="97%" border="0">');
      ShowHTML('      <tr><td><table border="0" width="100%">');
      ShowHTML('			 <tr><td valign="top"><font size=1>CPF:</font><br><b><font size=2>'.$w_username);
      ShowHTML('                   <INPUT type="hidden" name="w_username" value="'.$w_username.'">');
      ShowHTML('			 <tr><td valign="top"><font size="1"><b><u>N</u>ome completo:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome" class="sti" SIZE="45" MAXLENGTH="60" VALUE="'.$w_nome.'"></td>');
      ShowHTML('                <td valign="top"><font size="1"><b><u>N</u>ome resumido:</b><br><input '.$w_Disabled.' accesskey="N" type="text" name="w_nome_resumido" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_nome_resumido.'"></td>');
      ShowHTML('          </table>');
      if ($SG=="RHUSU") {
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b><u>I</u>dentidade:</b><br><input '.$w_Disabled.' accesskey="I" type="text" name="w_rg" class="sti" SIZE="14" MAXLENGTH="80" VALUE="'.$w_rg.'"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>Passapo<u>r</u>te:</b><br><input '.$w_Disabled.' accesskey="R" type="text" name="w_passaporte" class="sti" SIZE="15" MAXLENGTH="15" VALUE="'.$w_passaporte.'"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>Da<u>t</u>a de nascimento:</b><br><input '.$w_Disabled.' accesskey="T" type="text" name="w_nascimento" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_nascimento.'" onKeyDown="FormataData(this,event);"></td>');
        ShowHTML('          </table>');
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>En<u>d</u>ere�o:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_end" class="sti" SIZE="35" MAXLENGTH="50" VALUE="'.$w_end.'"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>C<u>o</u>mplemento:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_comple" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_comple.'"></td>');
        ShowHTML('          </table>');
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr>');
        selecaoPais('<u>P</u>a�s:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
        selecaoEstado('E<u>s</u>tado:','S',null,$w_uf,$w_pais,null,'w_uf',null,'onChange=\'document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_cidade\'; document.Form.submit();"');
        selecaoCidade('<u>C</u>idade:','C',null,$w_cidade,$w_pais,$w_uf,'w_cidade',null,null);
        ShowHTML('          </table>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>C<u>E</u>P:</b><br><input '.$w_Disabled.' accesskey="E" type="text" name="w_cep" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_cep.'" onKeyDown="FormataCEP(this,event);"></td>');
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>Te<u>l</u>efone:</b><br><input '.$w_Disabled.' accesskey="L" type="text" name="w_telefone" class="sti" SIZE="20" MAXLENGTH="40" VALUE="'.$w_telefone.'"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>Fa<u>x</u>:</b><br><input '.$w_Disabled.' accesskey="X" type="text" name="w_fax" class="sti" SIZE="20" MAXLENGTH="20" VALUE="'.$w_fax.'"></td>');
        if ($w_Disabled==' DISABLED ') {
          ShowHTML('              <td valign="top"><font size="1"><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email1" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
          ShowHTML('                   <INPUT type="hidden" name="w_email" value="'.$w_email.'">');
        } else {
          ShowHTML('              <td valign="top"><font size="1"><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
        } 
        ShowHTML('          </table>');
      } elseif ($SG=='SGUSU' || $SG=='CLUSUARIO') {
        if ($w_Disabled==' DISABLED ') {
          ShowHTML('          <tr><td valign="top"><font size="1"><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email1" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
          ShowHTML('                   <INPUT type="hidden" name="w_email" value="'.$w_email.'">');
        } else {
          ShowHTML('          <tr><td valign="top"><font size="1"><b>e-<u>M</u>ail:</b><br><input '.$w_Disabled.' accesskey="M" type="text" name="w_email" class="sti" SIZE="40" MAXLENGTH="50" VALUE="'.$w_email.'"></td>');
        } 
      } 
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('          <tr>');
      selecaoUnidade('<U>U</U>nidade de lota��o:','U','Selecione a unidade de lota��o e aguarde a recarga da p�gina para selecionar sua localiza��o.',$w_sq_unidade_lotacao,null,'w_sq_unidade_lotacao',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_localizacao\'; document.Form.submit();"');
      ShowHTML('          <tr>');
      selecaoLocalizacao('Locali<u>z</u>a��o:','Z',null,$w_sq_localizacao,nvl($w_sq_unidade_lotacao,0),'w_sq_localizacao',null);
      ShowHTML('          </table>');
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr>');
      if ($SG=='RHUSU') {
        selecaoVinculo('<u>M</u>odalidade de contrata��o:','M',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','F�sica','S');
      } else {
        selecaoVinculo('<u>V</u>�nculo com a organiza��o:','V',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','F�sica',null);
      } 

      ShowHTML('      </tr>');
      ShowHTML('          </table>');
      if ($SG=='RHUSU') { // Tela de usu�rios do RH
        if ($O=='A') $w_readonly='READONLY'; // Se for altera��o, bloqueia a edi��o dos campos
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>Da<u>t</u>a de entrada:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="T" type="text" name="w_entrada" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_entrada.'" onKeyDown="FormataData(this,event);"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b><u>L</u>imite para empr�stimo:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="L" type="text" name="w_limite_emprestimo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_limite_emprestimo.'" onKeyDown="FormataValor(this,11,2,event)"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>Saldo de <u>f</u>�rias:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="F" type="text" name="w_saldo_ferias" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saldo_ferias.'" onKeyDown="FormataValor(this,6,1,event)"></td>');
        if ($O=='I') { // Se for inclus�o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Enviar mensagem comunicando admiss�o de novo funcion�rio.</td>');
        } elseif ($O=='E') { // Se for remo��o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Enviar mensagem comunicando rescis�o do contrato de funcion�rio.</td>');
        } 
        ShowHTML('          </table>');
      } elseif ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de cadastramento de usu�rios
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>Gestor seguran�a?</b><br>');
        if ($w_gestor_seguranca=='S') {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="S" CHECKED> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="N"> N�o</td>');
        } else {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="S"> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="N" CHECKED> N�o</td>');
        } 
        ShowHTML('              <td valign="top"><font size="1"><b>Gestor sistema?</b><br>');
        if ($w_gestor_sistema=='S') {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="S" CHECKED> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="N"> N�o</td>');
        } else {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="S"> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="N" CHECKED> N�o</td>');
        } 

        if ($O=='I') { // Se for inclus�o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usu�rio a cria��o do acesso</td>');
        } elseif ($O=='E') { // Se for remo��o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usu�rio sua exclus�o</td>');
        } elseif ($O=='T') { // Se for remo��o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usu�rio a ativa��o do seu acesso</td>');
        } elseif ($O=='D') { // Se for remo��o de funcion�rio, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usu�rio o bloqueio do seu acesso</td>');
        } 
        ShowHTML('          </table>');
      } 
      if ($SG=='RHUSU' || $SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de usu�rios do RH e do SG
        ShowHTML('      <tr><td valign="top"><font size="1"><b><U>A</U>ssinatura Eletr�nica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      // Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
      ShowHTML('      <tr><td align="center" colspan="3">');
      if ($O=='E') { 
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
      } elseif ($O=='T') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Desbloquear Acesso" onClick="return(confirm(\'Confirma a ativa��o do acesso ao sistema para este usu�rio?\'));">');
      } elseif ($O=='D') {
        if ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de usu�rios do SG
          ShowHTML('            <input class="stb" type="submit" name="Botao" value="Bloquear Acesso" onClick="return(confirm(\'Confirma bloqueio do acesso ao sistema para este usu�rio?\'));">');
        } elseif ($SG=='RHUSU') { // Tela de usu�rios do RH
          ShowHTML('            <input class="stb" type="submit" name="Botao" value="Remover do quadro" onClick="return(confirm(\'Confirma remo��o do quadro de funcion�rios e bloqueio do acesso ao sistema para esta pessoa?\'));">');
        } else {
          ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
        } 
      } else {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Gravar">');
      } 
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'SGUSU');
      ShowHTML('            <input class="stb" type="button" onClick="location.href=\''.f($RS,'link').'&w_cliente='.$_REQUEST['w_cliente'].'&P1='.f($RS,'P1').'&P2='.f($RS,'P1').'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($RS,'sigla').MontaFiltro('GET').'\';" name="Botao" value="Cancelar">');
      ShowHTML('          </td>');
      ShowHTML('      </tr>');
      ShowHTML('    </table>');
      ShowHTML('    </TD>');
      ShowHTML('</tr>');
    } 
    ShowHTML('</FORM>');
  } else {
    ScriptOpen('JavaScript');
    ShowHTML(' alert(\'Op��o n�o dispon�vel\');');
    ShowHTML(' history.back(1);');
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
// Rotina de busca dos usu�rios
// -------------------------------------------------------------------------
function BuscaUsuario() {
  extract($GLOBALS);
  global $w_Disabled;
  $w_nome       = strtoupper($_REQUEST['w_nome']);
  $w_sg_unidade = strtoupper($_REQUEST['w_sg_unidade']);
  $w_cliente    = $_REQUEST['w_cliente'];
  $ChaveAux     = $_REQUEST['ChaveAux'];
  $restricao    = $_REQUEST['restricao'];
  $campo        = $_REQUEST['campo'];
  $RS = db_getPersonList::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$ChaveAux,$restricao,$w_nome,$w_sg_unidade,null,null);
  Cabecalho();
  ShowHTML('<TITLE>Sele��o de pessoa</TITLE>');
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  ScriptOpen('JavaScript');
  ShowHTML('  function volta(l_chave) {');
  ShowHTML('     opener.document.Form.'.$campo.'.value=l_chave;');
  ShowHTML('     opener.document.Form.'.$campo.'.focus();');
  ShowHTML('     window.close();');
  ShowHTML('     opener.focus();');
  ShowHTML('   }');
  ValidateOpen('Validacao');
  Validate('w_nome','Nome','1','','4','100','1','1');
  Validate('w_sg_unidade','Sigla da unidade de lota��o','1','','2','20','1','1');
  ShowHTML('  if (theForm.w_nome.value == \'' && theForm.w_sg_unidade.value == '\') {');
  ShowHTML('     alert (\'Informe um valor para o nome ou para a sigla da unidade!\');');
  ShowHTML('     theForm.w_nome.focus();');
  ShowHTML('     return false;');
  ShowHTML('  }');
  ShowHTML('  theForm.Botao[0].disabled=true;');
  ShowHTML('  theForm.Botao[1].disabled=true;');
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  ShowHTML('<BASE HREF="//'.$_SERVER['server_name'].'/siw/">');
  BodyOpen('onLoad=\'document.Form.w_nome.focus();\'');
  Estrutura_Texto_Abre();
  ShowHTML('<table border="0" cellpadding="0" cellspacing="0" width="100%">');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  AbreForm('Form',$w_dir.$w_pagina.'BuscaUsuario','POST','return(Validacao(this))',null,$P1,$P2,$P3,$P4,$TP,$SG,null,null);
  ShowHTML('<INPUT type="hidden" name="restricao" value="'.$restricao.'">');
  ShowHTML('<INPUT type="hidden" name="campo" value="'.$campo.'">');
  ShowHTML(MontaFiltro('POST'));
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instru��es</b>:<li>Informe parte do nome da a��o ou o c�digo da a��o.<li>Quando a rela��o for exibida, selecione a a��o desejada clicando sobre o link <i>Selecionar</i>.<li>Ap�s informar o nome da a��o ou o c�digo da a��o, clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Cancelar</i>, a procura � cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top"><font size="1"><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="100" value="'.$w_nome.'">');
  ShowHTML('      <tr><td valign="top"><font size="1"><b><U>S</U>igla da unidade de lota��o:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_sg_unidade" size="6" maxlength="20" value="'.$w_sg_unidade.'">');
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
  if ($w_nome>'' || $w_sg_unidade>'') {
    ShowHTML('<tr><td align="right"><font size="1"><b>Registros: '.$RS->RecordCount);
    ShowHTML('<tr><td>');
    ShowHTML('    <TABLE WIDTH="100%" border=0>');
    if (count($RS)==0) {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>N�o foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><font size="1"><b>Nome resumido</font></td>');
      ShowHTML('            <td><font size="1"><b>Nome</font></td>');
      ShowHTML('            <td><font size="1"><b>Lota��o</font></td>');
      ShowHTML('            <td><font size="1"><b>Opera��es</font></td>');
      ShowHTML('          </tr>');
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        ShowHTML('      <tr bgcolor="'.$w_cor.'" valign="top">');
        ShowHTML('            <td><font size="1">'.f($row,'nome_resumido').'</td>');
        ShowHTML('            <td><font size="1">'.f($row,'nome').'</td>');
        ShowHTML('            <td><font size="1">'.f($row,'sg_unidade').' ('.f($row,'nm_local').')</td>');
        ShowHTML('            <td><font size="1"><a class="ss" href="#" onClick="javascript:volta(\''.f($row,'sq_pessoa').'\');">Selecionar</a>');
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
// Procedimento que executa as opera��es de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  // Verifica se a Assinatura Eletr�nica � v�lida
  if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Identifica, a partir do tamanho da vari�vel w_username, se � pessoa f�sica, jur�dica ou estrangeiro
      if (strlen($_REQUEST['w_username'])<=14) $w_tipo='F�sica'; else $w_tipo='Jur�dica';
      dml_putSiwUsuario::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_pessoa'],$_REQUEST['w_cliente'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
            $_REQUEST['w_sq_tipo_vinculo'],$w_tipo,$_REQUEST['w_sq_unidade_lotacao'],$_REQUEST['w_sq_localizacao'],
            $_REQUEST['w_username'],$_REQUEST['w_email'],$_REQUEST['w_gestor_seguranca'],$_REQUEST['w_gestor_sistema']);

      // Se o usu�rio deseja comunicar a ocorr�ncia ao usu�rio, configura e envia mensagem autom�tica.
      if ($_REQUEST['w_envia_mail']>'') { // Configura��o do texto da mensagem
        $w_html = '<HTML>'.$crlf;
        $w_html = $w_html.BodyOpenMail().$crlf;
        $w_html = $w_html.'<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
        $w_html = $w_html.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
        $w_html = $w_html.'    <table width="97%" border="0">'.$crlf;
        $w_html = $w_html.'      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
        if (!(strpos('IT',$O)===false)) {
          if ($O=='I') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>CRIA��O DE USU�RIO</b></font><br><br><td></tr>'.$crlf;
          } elseif ($O=='T') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>DESBLOQUEIO DE USU�RIO</b></font><br><br><td></tr>'.$crlf;
          } 
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          if ($O=='I') {
            $w_html = $w_html.'         Sua senha e assinatura eletr�nica para acesso ao sistema foram criadas. Utilize os dados informados abaixo:<br>'.$crlf;
          } elseif ($O=='T') {
            $w_html = $w_html.'         Sua senha e assinatura eletr�nica para acesso ao sistema foram desbloqueadas. Utilize os dados informados abaixo:<br>'.$crlf;
          }
          $w_html = $w_html.'         <ul>'.$crlf;
          $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
          $w_html = $w_html.'         <li>Endere�o de acesso ao sistema: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
          $w_html = $w_html.'         <li>CPF: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>Senha de acesso: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>Assinatura eletr�nica: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         </ul>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          $w_html = $w_html.'         Orienta��es e observa��es:<br>'.$crlf;
          $w_html = $w_html.'         <ol>'.$crlf;
          $w_html = $w_html.'         <li>Troque sua senha de acesso e assinatura no primeiro acesso que fizer ao sistema.</li>'.$crlf;
          $w_html = $w_html.'         <li>Para trocar sua senha de acesso, localize no menu a op��o <b>Troca senha</b> e clique sobre ela, seguindo as orienta��es apresentadas.</li>'.$crlf;
          $w_html = $w_html.'         <li>Para trocar sua assinatura eletr�nica, localize no menu a op��o <b>Assinatura eletr�nica</b> e clique sobre ela, seguindo as orienta��es apresentadas.</li>'.$crlf;
          $w_html = $w_html.'         <li>Voc� pode fazer com que a senha de acesso e a assinatura eletr�nica tenham o mesmo valor ou valores diferentes. A decis�o � sua.</li>'.$crlf;
          $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
          $w_html = $w_html.'         <li>Tanto a senha quanto a assinatura eletr�nica t�m tempo de vida m�ximo de <b>'.f($RS,'dias_vig_senha').'</b> dias. O sistema ir� recomendar a troca <b>'.f($RS,'dias_aviso_expir').'</b> dias antes da expira��o do tempo de vida.</li>'.$crlf;
          $w_html = $w_html.'         <li>O sistema ir� bloquear seu acesso se voc� errar sua senha de acesso ou sua senha de acesso <b>'.f($RS,'maximo_tentativas').'</b> vezes consecutivas. Se voc� tiver d�vidas ou n�o lembrar sua senha de acesso ou assinatura de acesso, utilize a op��o "Lembrar senha" na tela de autentica��o do sistema.</li>'.$crlf;
          $w_html = $w_html.'         <li>Acessos bloqueados por expira��o do tempo de vida da senha de acesso ou assinaturas eletr�nicas, ou por exceder o m�ximo de erros consecutivos, s� podem ser desbloqueados pelo gestor de seguran�a do sistema.</li>'.$crlf;
          $w_html = $w_html.'         </ol>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
        } elseif (!(strpos("ED",$O)===false)) {
          if ($O=='E') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>EXCLUS�O DE USU�RIO</b></font><br><br><td></tr>'.$crlf;
          } elseif ($O=='D') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>BLOQUEIO DE USU�RIO</b></font><br><br><td></tr>'.$crlf;
          } 
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
          if ($O=='E') {
            $w_html = $w_html.'         Seus dados foram exclu�dos do sistema existente no endere�o '.f($RS,'logradouro').'. A partir de agora voc� n�o poder� mais acess�-lo.<br>'.$crlf;
          } elseif ($O=='D') {
            $w_html = $w_html.'         Sua senha e assinatura eletr�nica para acesso ao sistema existente no endere�o '.f($RS,'logradouro').' foram bloqueadas pelo gestor de seguran�a. A partir de agora voc� n�o poder� mais acess�-lo.<br>'.$crlf;
          } 
          $w_html = $w_html.'         Em caso de d�vidas, entre em contato com o gestor:'.$crlf;
          $w_html = $w_html.'         <ul>'.$crlf;
          $w_html = $w_html.'         <li>Nome: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>e-Mail: <b><a class="ss" href="mailto:'.$_SESSION['EMAIL'].'">'.$_SESSION['EMAIL'].'</a></b></li>'.$crlf;
          $w_html = $w_html.'         </ul>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
        } 
        $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
        $w_html = $w_html.'         Dados da ocorr�ncia:<br>'.$crlf;
        $w_html = $w_html.'         <ul>'.$crlf;
        $w_html = $w_html.'         <li>Data do servidor: <b>'.date('d/m/Y, H:i:s').'</b></li>'.$crlf;
        $w_html = $w_html.'         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
        $w_html = $w_html.'         </ul>'.$crlf;
        $w_html = $w_html.'      </font></td></tr>'.$crlf;
        $w_html = $w_html.'    </table>'.$crlf;
        $w_html = $w_html.'</td></tr>'.$crlf;
        $w_html = $w_html.'</table>'.$crlf;
        $w_html = $w_html.'</BODY>'.$crlf;
        $w_html = $w_html.'</HTML>'.$crlf;
        // Executa a fun��o de envio de e-mail
        if ($O=='I') {
          $w_resultado=EnviaMail('Aviso de cria��o de usu�rio',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='E') {
          $w_resultado=EnviaMail('Aviso de exclus�o de usu�rio',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='D') {
          $w_resultado=EnviaMail('Aviso de bloqueio de acesso',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='T') {
          $w_resultado=EnviaMail('Aviso de desbloqueio de acesso',$w_html,$_REQUEST['w_email']);
        } 
      } 
    } 
    // Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
    $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
    ScriptOpen('JavaScript');
    if ($SG=='SGUSU' || $SG=='RHUSU' || $SG=='CLUSUARIO') {
      if (!(strpos('IAD',$O)===false)) {
        if ($w_resultado>'') {
          ShowHTML('  alert(\'ATEN��O: opera��o executada mas n�o foi poss�vel proceder o envio do e-mail.\n'.$w_resultado.'\');');
        } else {
          ShowHTML('  alert(\'Opera��o executada!\');');
        } 
      } 
      ShowHTML('  location.href=\''.f($RS,'link').'&O=L&w_cliente='.$_REQUEST['w_cliente'].'&w_sq_solicitacao='.$_REQUEST['w_sq_solicitacao'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
    } else {
      ShowHTML('  location.href=\''.f($RS,'link').'&O='.$O.'&w_cliente='.$_REQUEST['w_cliente'].'&w_sq_solicitacao='.$_REQUEST['w_sq_solicitacao'].'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'\';');
    } 
    ScriptClose();
    DesconectaBD();
  } else {
    ScriptOpen('JavaScript');
    ShowHTML('  alert(\'Assinatura Eletr�nica inv�lida!\');');
    ScriptClose();
    retornaFormulario('w_assinatura');
  } 
} 
// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  switch ($par) {
  case "BENEF":         Benef();        break;
  case "BUSCAUSUARIO":  BuscaUsuario(); break;
  case "GRAVA":         Grava();        break;
  default:
    Cabecalho();
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