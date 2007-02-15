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
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Gerencia o módulo de formulários do sistema
// Mail     : alex@sbpi.com.br
// Criacao  : 25/11/2002 16:17
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
//                   = V   : Envio
//                   = L   : Listagem
//                   = P   : Pesquisa
//                   = D   : Detalhes
//                   = N   : Nova solicitação de envio

// Verifica se o usuário está autenticado
if ($_SESSION['LOGON']!='Sim') { EncerraSessao(); }

// Declaração de variáveis
$dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

// Carrega variáveis locais com os dados dos parâmetros recebidos
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
    else$w_TP=$TP.' - Inclusão';
    break;
  case 'A': // Se a chamada for para as rotinas de visualização, não concatena nada
    if ($par=='VISUAL' || $par=='ENVIAR') $w_TP=$TP;
    else $w_TP=$TP.' - Alteração';
    break;
  case 'D':
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') $w_TP=$TP.' - Bloqueio de Acesso';
    elseif ($SG=='RHUSU') $w_TP=$TP.' - Desligamento';
    break;
  case 'T': $w_TP=$TP.' - Ativação';  break;
  case 'E': $w_TP=$TP.' - Exclusao';  break;
  case 'V': $w_TP=$TP.' - Envio';     break;
  case 'P': $w_TP=$TP.' - Filtragem'; break;
  default:
    if ($par=='BUSCAUSUARIO') $w_TP=$TP.' - Busca usuário';
    else $w_TP=$TP.' - Listagem';
    break;
} 
$w_data_banco = time();

// Se for acesso do módulo de gerenciamento de clientes do SIW, o cliente será determinado por parâmetro;
// caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
$w_cliente  = RetornaCliente();
$w_usuario  = RetornaUsuario();

Main();
FechaSessao($dbms);

// =========================================================================
// Rotina de beneficiário
// -------------------------------------------------------------------------
function Benef() {
  extract($GLOBALS);
  global $w_Disabled;
  // Nesta rotina, P1 = 0 indica que não pode haver troca do beneficiário
  //                  = 1 indica que pode haver troca de beneficiário
  //               P2 = 0 indica que não pegará os dados bancários, nem da forma de pagamento
  //                  = 1 indica que pegará os dados bancários, mas não da forma de pagamento
  //                  = 2 indica que pegará os dados bancários e também da forma de pagamento
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
  // Verifica se há necessidade de recarregar os dados da tela a partir
  // da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  if ($w_troca>'' && $O!='E') {
    // Se for recarga da página
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
        ShowHTML('  alert(\'Usuário já existente!\');');
        ShowHTML('  history.back(1);');
        ScriptClose();
        exit;
      } 
    } 
    if (strpos('IATDEV',$O)!==false) {
      if (nvl($w_sq_pessoa,'')!='') {
        // Recupera os dados do beneficiário em co_pessoa
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
        // Recupera os dados do beneficiário em co_pessoa
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
    // O bloco abaixo recupera os dados bancários e a forma de pagamento,
    // dependendo do valor de P1 e se não for inclusão
    // O local onde os dados bancários e a forma de pagamento serão recuperados
    // depende do tipo de documento.
    if ($O!='I' && ($P2==1 || $P2==2)) {
      // Vide finalidade do parâmetro no cabeçalho da rotina
    } 
  } 
  Cabecalho();
  ShowHTML('<HEAD>');
  Estrutura_CSS($w_cliente);
  // Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  // tratando as particularidades de cada serviço
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
    // Se o beneficiário ainda não foi selecionado
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
      Validate('w_end','Endereço','1',1,4,50,'1','1');
      Validate('w_pais','País','SELECT',1,1,10,'1','1');
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
    Validate('w_sq_unidade_lotacao','Unidade de lotação','HIDDEN',1,1,10,'','1');
    Validate('w_sq_localizacao','Localização','SELECT',1,1,10,'','1');
    Validate('w_sq_tipo_vinculo','Vínculo com a organização','SELECT',1,1,10,'','1');
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') {
      Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
    } 
  } elseif ($O=='E' || $O=='T' || $O=='D') {
    Validate('w_assinatura','Assinatura Eletrônica','1','1','6','30','1','1');
  } 
  ValidateClose();
  ScriptClose();
  ShowHTML('</HEAD>');
  if ($P1!=0 && ($w_username=='' || (isset($_REQUEST['Botao']) && ((!(strpos($_REQUEST['Botao'],'Troca')===false)) || (!(strpos($_REQUEST['Botao'],'Procurar')===false)))))) {
    // Se o beneficiário ainda não foi selecionado
    if (isset($_REQUEST['Botao']) && (!(strpos($_REQUEST['botao'],'Procurar'))===false)) {
      // Se está sendo feita busca por nome
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
      // Se o beneficiário ainda não foi selecionado
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
      ShowHTML('        <tr><td colspan=3><font size=2>Informe os dados abaixo e clique no botão "Selecionar" para continuar.</TD>');
      ShowHTML('        <tr><td><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY="C" TYPE="text" Class="sti" NAME="w_username" VALUE="'.$w_username.'" SIZE="14" MaxLength="14" onKeyDown="FormataCPF(this, event);">');
      ShowHTML('            <td valign="bottom"><INPUT class="stb" TYPE="submit" NAME="Botao" VALUE="Selecionar" onClick="Botao.value=this.value; document.Form.action=\''.$w_pagina.$par.'\'">');
      if ($SG=='SGUSU' || $SG=='RHUSU' || $SG=='CLUSUARIO') { // Tela de usuários do SG ou RH 
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
        ShowHTML('          <td><font size="1"><b>Operações</font></td>');
        ShowHTML('        </tr>');
        if (count($RS)==0) {
          ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><font  size="2"><b>Não há pessoas (não usuárias) que contenham o texto informado.</b></td></tr>');
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
        ShowHTML('          <tr><td valign="top"><font size="1"><b>En<u>d</u>ereço:</b><br><input '.$w_Disabled.' accesskey="D" type="text" name="w_end" class="sti" SIZE="35" MAXLENGTH="50" VALUE="'.$w_end.'"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>C<u>o</u>mplemento:</b><br><input '.$w_Disabled.' accesskey="O" type="text" name="w_comple" class="sti" SIZE="30" MAXLENGTH="50" VALUE="'.$w_comple.'"></td>');
        ShowHTML('          </table>');
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('      <tr>');
        selecaoPais('<u>P</u>aís:','P',null,$w_pais,null,'w_pais',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_uf\'; document.Form.submit();"');
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
      selecaoUnidade('<U>U</U>nidade de lotação:','U','Selecione a unidade de lotação e aguarde a recarga da página para selecionar sua localização.',$w_sq_unidade_lotacao,null,'w_sq_unidade_lotacao',null,'onChange="document.Form.action=\''.$w_pagina.$par.'\'; document.Form.w_troca.value=\'w_sq_localizacao\'; document.Form.submit();"');
      ShowHTML('          <tr>');
      selecaoLocalizacao('Locali<u>z</u>ação:','Z',null,$w_sq_localizacao,nvl($w_sq_unidade_lotacao,0),'w_sq_localizacao',null);
      ShowHTML('          </table>');
      ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
      ShowHTML('      <tr>');
      if ($SG=='RHUSU') {
        selecaoVinculo('<u>M</u>odalidade de contratação:','M',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','Física','S');
      } else {
        selecaoVinculo('<u>V</u>ínculo com a organização:','V',null,$w_sq_tipo_vinculo,null,'w_sq_tipo_vinculo','S','Física',null);
      } 

      ShowHTML('      </tr>');
      ShowHTML('          </table>');
      if ($SG=='RHUSU') { // Tela de usuários do RH
        if ($O=='A') $w_readonly='READONLY'; // Se for alteração, bloqueia a edição dos campos
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>Da<u>t</u>a de entrada:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="T" type="text" name="w_entrada" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_entrada.'" onKeyDown="FormataData(this,event);"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b><u>L</u>imite para empréstimo:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="L" type="text" name="w_limite_emprestimo" class="sti" SIZE="10" MAXLENGTH="10" VALUE="'.$w_limite_emprestimo.'" onKeyDown="FormataValor(this,11,2,event)"></td>');
        ShowHTML('              <td valign="top"><font size="1"><b>Saldo de <u>f</u>érias:</b><br><input '.$w_Disabled.' '.$w_readonly.' accesskey="F" type="text" name="w_saldo_ferias" class="sti" SIZE="5" MAXLENGTH="5" VALUE="'.$w_saldo_ferias.'" onKeyDown="FormataValor(this,6,1,event)"></td>');
        if ($O=='I') { // Se for inclusão de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Enviar mensagem comunicando admissão de novo funcionário.</td>');
        } elseif ($O=='E') { // Se for remoção de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Enviar mensagem comunicando rescisão do contrato de funcionário.</td>');
        } 
        ShowHTML('          </table>');
      } elseif ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de cadastramento de usuários
        ShowHTML('      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>');
        ShowHTML('          <tr><td valign="top"><font size="1"><b>Gestor segurança?</b><br>');
        if ($w_gestor_seguranca=='S') {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="S" CHECKED> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="N"> Não</td>');
        } else {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="S"> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_seguranca" class="str" VALUE="N" CHECKED> Não</td>');
        } 
        ShowHTML('              <td valign="top"><font size="1"><b>Gestor sistema?</b><br>');
        if ($w_gestor_sistema=='S') {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="S" CHECKED> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="N"> Não</td>');
        } else {
          ShowHTML('              <input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="S"> Sim<input '.$w_Disabled.' type="RADIO" name="w_gestor_sistema" class="str" VALUE="N" CHECKED> Não</td>');
        } 

        if ($O=='I') { // Se for inclusão de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usuário a criação do acesso</td>');
        } elseif ($O=='E') { // Se for remoção de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usuário sua exclusão</td>');
        } elseif ($O=='T') { // Se for remoção de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usuário a ativação do seu acesso</td>');
        } elseif ($O=='D') { // Se for remoção de funcionário, pergunta se deseja enviar e-mail
          ShowHTML('          <tr><td valign="top"><font size="1"><input type="checkbox" name="w_envia_mail" class="STC" VALUE="S" CHECKED> Comunica ao usuário o bloqueio do seu acesso</td>');
        } 
        ShowHTML('          </table>');
      } 
      if ($SG=='RHUSU' || $SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de usuários do RH e do SG
        ShowHTML('      <tr><td valign="top"><font size="1"><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY="A" class="sti" type="PASSWORD" name="w_assinatura" size="30" maxlength="30" value=""></td>');
      } 
      ShowHTML('      <tr><td align="center" colspan="3" height="1" bgcolor="#000000"></TD></TR>');
      // Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
      ShowHTML('      <tr><td align="center" colspan="3">');
      if ($O=='E') { 
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Excluir">');
      } elseif ($O=='T') {
        ShowHTML('            <input class="stb" type="submit" name="Botao" value="Desbloquear Acesso" onClick="return(confirm(\'Confirma a ativação do acesso ao sistema para este usuário?\'));">');
      } elseif ($O=='D') {
        if ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Tela de usuários do SG
          ShowHTML('            <input class="stb" type="submit" name="Botao" value="Bloquear Acesso" onClick="return(confirm(\'Confirma bloqueio do acesso ao sistema para este usuário?\'));">');
        } elseif ($SG=='RHUSU') { // Tela de usuários do RH
          ShowHTML('            <input class="stb" type="submit" name="Botao" value="Remover do quadro" onClick="return(confirm(\'Confirma remoção do quadro de funcionários e bloqueio do acesso ao sistema para esta pessoa?\'));">');
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
    ShowHTML(' alert(\'Opção não disponível\');');
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
// Rotina de busca dos usuários
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
  ShowHTML('<TITLE>Seleção de pessoa</TITLE>');
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
  Validate('w_sg_unidade','Sigla da unidade de lotação','1','','2','20','1','1');
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
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td><div align="justify"><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da ação ou o código da ação.<li>Quando a relação for exibida, selecione a ação desejada clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome da ação ou o código da ação, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>');
  ShowHTML('<tr bgcolor="'.$conTrBgColor.'"><td>');
  ShowHTML('    <table width="100%" border="0">');
  ShowHTML('      <tr><td valign="top"><font size="1"><b>Parte do <U>n</U>ome da pessoa:<br><INPUT ACCESSKEY="N" '.$w_Disabled.' class="sti" type="text" name="w_nome" size="50" maxlength="100" value="'.$w_nome.'">');
  ShowHTML('      <tr><td valign="top"><font size="1"><b><U>S</U>igla da unidade de lotação:<br><INPUT ACCESSKEY="S" '.$w_Disabled.' class="sti" type="text" name="w_sg_unidade" size="6" maxlength="20" value="'.$w_sg_unidade.'">');
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
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan=5 align="center"><font size="1"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td>');
      ShowHTML('        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
      ShowHTML('          <tr bgcolor="'.$conTrBgColor.'" align="center">');
      ShowHTML('            <td><font size="1"><b>Nome resumido</font></td>');
      ShowHTML('            <td><font size="1"><b>Nome</font></td>');
      ShowHTML('            <td><font size="1"><b>Lotação</font></td>');
      ShowHTML('            <td><font size="1"><b>Operações</font></td>');
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
// Procedimento que executa as operações de BD
// -------------------------------------------------------------------------
function Grava() {
  extract($GLOBALS);
  Cabecalho();
  ShowHTML('</HEAD>');
  BodyOpen('onLoad=this.focus();');
  // Verifica se a Assinatura Eletrônica é válida
  if (VerificaAssinaturaEletronica($_SESSION['USERNAME'],strtoupper($_REQUEST['w_assinatura'])) || $w_assinatura=='') {
    if ($SG=='SGUSU' || $SG=='CLUSUARIO') { // Identifica, a partir do tamanho da variável w_username, se é pessoa física, jurídica ou estrangeiro
      if (strlen($_REQUEST['w_username'])<=14) $w_tipo='Física'; else $w_tipo='Jurídica';
      dml_putSiwUsuario::getInstanceOf($dbms, $O,
            $_REQUEST['w_sq_pessoa'],$_REQUEST['w_cliente'],$_REQUEST['w_nome'],$_REQUEST['w_nome_resumido'],
            $_REQUEST['w_sq_tipo_vinculo'],$w_tipo,$_REQUEST['w_sq_unidade_lotacao'],$_REQUEST['w_sq_localizacao'],
            $_REQUEST['w_username'],$_REQUEST['w_email'],$_REQUEST['w_gestor_seguranca'],$_REQUEST['w_gestor_sistema']);

      // Se o usuário deseja comunicar a ocorrência ao usuário, configura e envia mensagem automática.
      if ($_REQUEST['w_envia_mail']>'') { // Configuração do texto da mensagem
        $w_html = '<HTML>'.$crlf;
        $w_html = $w_html.BodyOpenMail().$crlf;
        $w_html = $w_html.'<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
        $w_html = $w_html.'<tr bgcolor="'.$conTrBgColor.'"><td align="center">'.$crlf;
        $w_html = $w_html.'    <table width="97%" border="0">'.$crlf;
        $w_html = $w_html.'      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
        if (!(strpos('IT',$O)===false)) {
          if ($O=='I') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>CRIAÇÃO DE USUÁRIO</b></font><br><br><td></tr>'.$crlf;
          } elseif ($O=='T') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>DESBLOQUEIO DE USUÁRIO</b></font><br><br><td></tr>'.$crlf;
          } 
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          if ($O=='I') {
            $w_html = $w_html.'         Sua senha e assinatura eletrônica para acesso ao sistema foram criadas. Utilize os dados informados abaixo:<br>'.$crlf;
          } elseif ($O=='T') {
            $w_html = $w_html.'         Sua senha e assinatura eletrônica para acesso ao sistema foram desbloqueadas. Utilize os dados informados abaixo:<br>'.$crlf;
          }
          $w_html = $w_html.'         <ul>'.$crlf;
          $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
          $w_html = $w_html.'         <li>Endereço de acesso ao sistema: <b><a class="ss" href="'.f($RS,'logradouro').'" target="_blank">'.f($RS,'Logradouro').'</a></b></li>'.$crlf;
          $w_html = $w_html.'         <li>CPF: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>Senha de acesso: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>Assinatura eletrônica: <b>'.$_REQUEST['w_username'].'</b></li>'.$crlf;
          $w_html = $w_html.'         </ul>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          $w_html = $w_html.'         Orientações e observações:<br>'.$crlf;
          $w_html = $w_html.'         <ol>'.$crlf;
          $w_html = $w_html.'         <li>Troque sua senha de acesso e assinatura no primeiro acesso que fizer ao sistema.</li>'.$crlf;
          $w_html = $w_html.'         <li>Para trocar sua senha de acesso, localize no menu a opção <b>Troca senha</b> e clique sobre ela, seguindo as orientações apresentadas.</li>'.$crlf;
          $w_html = $w_html.'         <li>Para trocar sua assinatura eletrônica, localize no menu a opção <b>Assinatura eletrônica</b> e clique sobre ela, seguindo as orientações apresentadas.</li>'.$crlf;
          $w_html = $w_html.'         <li>Você pode fazer com que a senha de acesso e a assinatura eletrônica tenham o mesmo valor ou valores diferentes. A decisão é sua.</li>'.$crlf;
          $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
          $w_html = $w_html.'         <li>Tanto a senha quanto a assinatura eletrônica têm tempo de vida máximo de <b>'.f($RS,'dias_vig_senha').'</b> dias. O sistema irá recomendar a troca <b>'.f($RS,'dias_aviso_expir').'</b> dias antes da expiração do tempo de vida.</li>'.$crlf;
          $w_html = $w_html.'         <li>O sistema irá bloquear seu acesso se você errar sua senha de acesso ou sua senha de acesso <b>'.f($RS,'maximo_tentativas').'</b> vezes consecutivas. Se você tiver dúvidas ou não lembrar sua senha de acesso ou assinatura de acesso, utilize a opção "Lembrar senha" na tela de autenticação do sistema.</li>'.$crlf;
          $w_html = $w_html.'         <li>Acessos bloqueados por expiração do tempo de vida da senha de acesso ou assinaturas eletrônicas, ou por exceder o máximo de erros consecutivos, só podem ser desbloqueados pelo gestor de segurança do sistema.</li>'.$crlf;
          $w_html = $w_html.'         </ol>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
        } elseif (!(strpos("ED",$O)===false)) {
          if ($O=='E') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>EXCLUSÃO DE USUÁRIO</b></font><br><br><td></tr>'.$crlf;
          } elseif ($O=='D') {
            $w_html = $w_html.'      <tr valign="top"><td align="center"><font size=2><b>BLOQUEIO DE USUÁRIO</b></font><br><br><td></tr>'.$crlf;
          } 
          $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
          $RS = db_getCustomerSite::getInstanceOf($dbms,$w_cliente);
          if ($O=='E') {
            $w_html = $w_html.'         Seus dados foram excluídos do sistema existente no endereço '.f($RS,'logradouro').'. A partir de agora você não poderá mais acessá-lo.<br>'.$crlf;
          } elseif ($O=='D') {
            $w_html = $w_html.'         Sua senha e assinatura eletrônica para acesso ao sistema existente no endereço '.f($RS,'logradouro').' foram bloqueadas pelo gestor de segurança. A partir de agora você não poderá mais acessá-lo.<br>'.$crlf;
          } 
          $w_html = $w_html.'         Em caso de dúvidas, entre em contato com o gestor:'.$crlf;
          $w_html = $w_html.'         <ul>'.$crlf;
          $w_html = $w_html.'         <li>Nome: <b>'.$_SESSION['NOME'].'</b></li>'.$crlf;
          $w_html = $w_html.'         <li>e-Mail: <b><a class="ss" href="mailto:'.$_SESSION['EMAIL'].'">'.$_SESSION['EMAIL'].'</a></b></li>'.$crlf;
          $w_html = $w_html.'         </ul>'.$crlf;
          $w_html = $w_html.'      </font></td></tr>'.$crlf;
        } 
        $w_html = $w_html.'      <tr valign="top"><td><font size=2>'.$crlf;
        $w_html = $w_html.'         Dados da ocorrência:<br>'.$crlf;
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
        // Executa a função de envio de e-mail
        if ($O=='I') {
          $w_resultado=EnviaMail('Aviso de criação de usuário',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='E') {
          $w_resultado=EnviaMail('Aviso de exclusão de usuário',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='D') {
          $w_resultado=EnviaMail('Aviso de bloqueio de acesso',$w_html,$_REQUEST['w_email']);
        } elseif ($O=='T') {
          $w_resultado=EnviaMail('Aviso de desbloqueio de acesso',$w_html,$_REQUEST['w_email']);
        } 
      } 
    } 
    // Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
    $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],$SG);
    ScriptOpen('JavaScript');
    if ($SG=='SGUSU' || $SG=='RHUSU' || $SG=='CLUSUARIO') {
      if (!(strpos('IAD',$O)===false)) {
        if ($w_resultado>'') {
          ShowHTML('  alert(\'ATENÇÃO: operação executada mas não foi possível proceder o envio do e-mail.\n'.$w_resultado.'\');');
        } else {
          ShowHTML('  alert(\'Operação executada!\');');
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
    ShowHTML('  alert(\'Assinatura Eletrônica inválida!\');');
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
    ShowHTML('<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>');
    Estrutura_Texto_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Estrutura_Fecha();
    Rodape();
  }
} 
?>