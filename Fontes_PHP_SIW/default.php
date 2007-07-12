<?
session_start();
$w_dir_volta = '';
include_once('constants.inc');
include_once('jscript.php');
include_once('funcoes.php');
include_once('classes/db/abreSessao.php');
include_once('classes/sp/db_verificausuario.php');
include_once('classes/sp/db_verificasenha.php');
include_once('classes/sp/db_updatePassword.php');
include_once('classes/sp/db_getCustomerData.php');
include_once('classes/sp/db_getUserData.php');
include_once('classes/sp/db_getLinkData.php');
include_once('classes/sp/db_getCustomerSite.php');
// =========================================================================
//  /default.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Autenticação
// Mail     : alex@sbpi.com.br
// Criacao  : 16/03/2005 16:14PM
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
//
// Declaração de variáveis

if ($_SESSION['DBMS']=='' || isset($_POST['p_dbms'])) {
  if (!isset($_POST['p_dbms'])) {
    if (isset($_POST['p_cliente'])) {
      if ($_POST['p_cliente']!=1) {
         print '*** Erro';
         exit();
      }
    }
  }
  else { $_SESSION['DBMS']=$_POST['p_dbms']; }
}

// Carrega variáveis locais com os dados dos parâmetros recebidos

if (count($_POST) > 0) {
   $wNoUsuario  = strtoupper($_POST['Login']);
   $wDsSenha    = strtoupper($_POST['Password']);
   $wBotao      = strtoupper($_POST['Botao']);
   $par         = $_POST['par'];
}

$RS=null;

// Abre conexão com o banco de dados
if (isset($_SESSION['DBMS'])) $dbms = abreSessao::getInstanceOf($_SESSION['DBMS']);

Main();

// Fecha conexão com o banco de dados
if (isset($_SESSION['DBMS'])) FechaSessao($dbms);

exit;

// =========================================================================
// Rotina de autenticação dos usuários
// -------------------------------------------------------------------------
function Valida() {
  extract($GLOBALS);
  $w_erro=0;

  if (db_verificaUsuario::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $wNoUsuario)==0) {
    $w_erro=1;
  } else {
    if ($wDsSenha>'') { $w_erro=db_verificaSenha::getInstanceOf($dbms, $_SESSION['P_CLIENTE'],$wNoUsuario,$wDsSenha); }
  }

  if ($w_erro>0) {
    ScriptOpen('JavaScript');
    if ($w_erro==1) { ShowHTML('  alert(\'Usuário inexistente!\');'); }
    else
       if ($w_erro==2) { ShowHTML('  alert(\'Senha inválida!\');'); }
       else
          if ($w_erro==3) { ShowHTML('  alert(\'Usuário com acesso bloqueado pelo gestor de segurança!\');'); }
    // Se for SBPI e senha inválida, devolve a username, dispensando sua redigitação.
    if ($_SESSION['P_CLIENTE']==1 && $w_erro=2) {
      $w_retorno = $_SERVER['HTTP_REFERER'];
      $w_pos = strpos($w_retorno,'?');
      if ($w_pos!==false) $w_retorno = substr($w_retorno,0,$w_pos);
      ShowHTML('  location.href=\''.$w_retorno.'?Login='.$wNoUsuario.'\';');
    } else {
       ShowHTML('  location.href=\''.$_SERVER['HTTP_REFERER'].'\';');
    }
    ScriptClose();
  } else {
    // Recupera informações do cliente, relativas ao envio de e-mail
    $RS = db_getCustomerData::getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
    $_SESSION['SMTP_SERVER']     = f($RS, 'smtp_server');
    $_SESSION['SIW_EMAIL_CONTA'] = f($RS, 'siw_email_conta');
    $_SESSION['SIW_EMAIL_SENHA'] = f($RS,'siw_email_senha');
    
    // Recupera informações a serem usadas na montagem das telas para o usuário
    $RS = DB_GetUserData::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $wNoUsuario);
    $_SESSION['USERNAME']        = f($RS,'USERNAME');
    $_SESSION['SQ_PESSOA']       = f($RS,'SQ_PESSOA');
    $_SESSION['NOME']            = f($RS,'NOME');
    $_SESSION['EMAIL']           = f($RS,'EMAIL');
    $_SESSION['NOME_RESUMIDO']   = f($RS,'NOME_RESUMIDO');
    $_SESSION['LOTACAO']         = f($RS,'SQ_UNIDADE');
    $_SESSION['LOCALIZACAO']     = f($RS,'SQ_LOCALIZACAO');
    $_SESSION['INTERNO']         = f($RS,'INTERNO');
    $_SESSION['LOGON']           = 'Sim';
    $_SESSION['ENDERECO']        = f($RS,'SQ_PESSOA_ENDERECO');
    $_SESSION['ANO']             = Date('Y');

    // Se a geração de log estiver ativada, registra.
    if ($conLog) {
      // Define o caminho fisico do diretório e do arquivo de log
      $l_caminho = $conLogPath;
      $l_arquivo = $l_caminho.$_SESSION['P_CLIENTE'].'/'.date(Ymd).'.log';

      // Verifica a necessidade de criação dos diretórios de log
      if (!file_exists($l_caminho)) mkdir($l_caminho);
      if (!file_exists($l_caminho.$_SESSION['P_CLIENTE'])) mkdir($l_caminho.$_SESSION['P_CLIENTE']);
      
      // Abre o arquivo de log
      $l_log = @fopen($l_arquivo, 'a');
      
      fwrite($l_log, '['.date(ymd.'_'.Gis.'_'.time()).']'.$crlf);
      fwrite($l_log, 'Usuário: '.$_SESSION['NOME_RESUMIDO'].' ('.$_SESSION['SQ_PESSOA'].')'.$crlf);
      fwrite($l_log, 'IP     : '.$_SERVER['REMOTE_ADDR'].$crlf);
      fwrite($l_log, 'Ação   : LOGIN'.$crlf.$crlf);

      // Fecha o arquivo e o diretório de log
      @fclose($l_log);
      @closedir($l_caminho); 
    }

    if ($par=='Log') {
      ScriptOpen('JavaScript');
      if ($_POST['p_cliente']==6761 && $_POST['p_versao']==2) {
        if ($RS['interno']=='S') {
          ShowHTML('  top.location.href=\'cl_cespe/trabalho.php?par=mesa&TP=Acompanhamento\';');
        } else {
          $RS = db_getLinkData::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], 'PJCADP');
          ShowHTML('  location.href=\''.$RS['link'].'&O=&P1='.$RS['P1'].'&P2='.$RS['P2'].'&P3='.$RS['P3'].'&P4='.$RS['P4'].'&TP='.$RS['nome'].'&SG='.$RS['sigla'].'\';');
        }
      } else {
        if ($_POST['p_cliente']==1) ShowHTML('  top.location.href=\'menu.php?par=Frames\';');
        else                        ShowHTML('  location.href=\'menu.php?par=Frames\';');
      }
      ScriptClose();
    } else {
       // Cria a nova senha, pegando a hora e o minuto correntes
      $w_senha='nova'.date('is');

      // Configura a mensagem automática comunicando ao usuário sua nova senha de acesso e assinatura eletrônica
      $w_html='<HTML>'.$crlf;
      $w_html .= BodyOpenMail(null).$crlf;
      $w_html .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">'.$crlf;
      $w_html .= '<tr bgcolor="'.$conTrBgcolor.'"><td align="center">'.$crlf;
      $w_html .= '    <table width="97%" border="0">'.$crlf;
      $w_html .= '      <tr valign="top"><td align="center"><font size=2><b>REINICIALIZAÇÃO DE SENHA</b></font><br><br><td></tr>'.$crlf;
      $w_html .= '      <tr valign="top"><td><font size=2><b><font color="#BC3131">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>'.$crlf;
      $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
      $w_html .= '         Sua senha e assinatura eletrônica foram reinicializadas. A partir de agora, utilize os dados informados abaixo:<br>'.$crlf;
      $w_html .= '         <ul>'.$crlf;
      $RS = db_getCustomerSite::getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
      $w_html .= '         <li>Endereço de acesso ao sistema: <b><a class="SS" href="'.$RS['LOGRADOURO'].'" target="_blank">'.$RS['LOGRADOURO'].'</a></b></li>'.$crlf;
      DesconectaBD();
      $w_html .= '         <li>CPF: <b>'.$_SESSION['USERNAME'].'</b></li>'.$crlf;
      $w_html .= '         <li>Senha de acesso: <b>'.$w_senha.'</b></li>'.$crlf;
      $w_html .= '         <li>Assinatura eletrônica: <b>'.$w_senha.'</b></li>'.$crlf;
      $w_html .= '         </ul>'.$crlf;
      $w_html .= '      </font></td></tr>'.$crlf;
      $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
      $w_html .= '         Orientações e observações:<br>'.$crlf;
      $w_html .= '         <ol>'.$crlf;
      $w_html .= '         <li>Troque sua senha de acesso e assinatura no primeiro acesso que fizer ao sistema.</li>'.$crlf;
      $w_html .= '         <li>Para trocar sua senha de acesso, localize no menu a opção <b>Troca senha</b> e clique sobre ela, seguindo as orientações apresentadas.</li>'.$crlf;
      $w_html .= '         <li>Para trocar sua assinatura eletrônica, localize no menu a opção <b>Assinatura eletrônica</b> e clique sobre ela, seguindo as orientações apresentadas.</li>'.$crlf;
      $w_html .= '         <li>Você pode fazer com que a senha de acesso e a assinatura eletrônica tenham o mesmo valor ou valores diferentes. A decisão é sua.</li>'.$crlf;
      $RS = db_getCustomerData::getInstanceOf($dbms, $_SESSION['P_CLIENTE']);
      $w_html .= '         <li>Tanto a senha quanto a assinatura eletrônica têm tempo de vida máximo de <b>'.$RS['DIAS_VIG_SENHA'].'</b> dias. O sistema irá recomendar a troca <b>'.$RS['DIAS_AVISO_EXPIR'].'</b> dias antes da expiração do tempo de vida.</li>'.$crlf;
      $w_html .= '         <li>O sistema irá bloquear seu acesso se você errar sua senha de acesso ou sua senha de acesso <b>'.$RS['MAXIMO_TENTATIVAS'].'</b> vezes consecutivas. Se você tiver dúvidas ou não lembrar sua senha de acesso ou assinatura de acesso, utilize a opção "Lembrar senha" na tela de autenticação do sistema.</li>'.$crlf;
      DesconectaBD();
      $w_html .= '         <li>Acessos bloqueados por expiração do tempo de vida da senha de acesso ou assinaturas eletrônicas, ou por exceder o máximo de erros consecutivos, só podem ser desbloqueados pelo gestor de segurança do sistema.</li>'.$crlf;
      $w_html .= '         </ol>'.$crlf;
      $w_html .= '      </font></td></tr>'.$crlf;
      $w_html .= '      <tr valign="top"><td><font size=2>'.$crlf;
      $w_html .= '         Dados da ocorrência:<br>'.$crlf;
      $w_html .= '         <ul>'.$crlf;
      $w_html .= '         <li>Data do servidor: <b>'.DataHora().'</b></li>'.$crlf;
      $w_html .= '         <li>IP de origem: <b>'.$_SERVER['REMOTE_ADDR'].'</b></li>'.$crlf;
      $w_html .= '         </ul>'.$crlf;
      $w_html .= '      </font></td></tr>'.$crlf;
      $w_html .= '    </table>'.$crlf;
      $w_html .= '</td></tr>'.$crlf;
      $w_html .= '</table>'.$crlf;
      $w_html .= '</BODY>'.$crlf;
      $w_html .= '</HTML>'.$crlf;

      // Executa a função de envio de e-mail
      $w_resultado=EnviaMail('Aviso de reinicialização de senha',$w_html,$_SESSION['EMAIL']);

      ScriptOpen('JavaScript');
    // Se ocorreu algum erro, avisa da impossibilidade de envio do e-mail,
      // caso contrário, avisa que o e-mail foi enviado para o usuário.
      if (nvl($w_resultado,'')!='') { 
         ShowHTML('  alert(\'ATENÇÃO: sua senha NÃO FOI recriada pois não foi possível proceder o envio do e-mail\n'.$w_resultado.'\');'); 
      } else {
         // Atualiza a senha de acesso e a assinatura eletrônica, igualando as duas
         db_updatePassword::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], $w_senha, 'PASSWORD');
         db_updatePassword::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], $w_senha, 'SIGNATURE');

         ShowHTML('  alert(\'Sua senha foi recriada e enviada para '.$_SESSION['EMAIL'].'!\');');
      }

      ShowHTML('  location.href=\''.$_SERVER['HTTP_REFERER'].'\';');
      ScriptClose();
    }
    DesconectaBD();
  }
}

// =========================================================================
// Rotina de criação da tela de logon
// -------------------------------------------------------------------------
function LogOn() {
  extract($GLOBALS);

  $w_username = $_REQUEST['Login'];
  ShowHTML('<HTML>');
  ShowHTML('<HEAD>');
  ShowHTML('<link rel="shortcut icon" href="'.$conRootSIW.'favicon.ico" type="image/ico" />');
  ShowHTML('<TITLE>'.$conSgSistema.' - Autenticação</TITLE>');
  ScriptOpen('JavaScript');
  ShowHTML('function Ajuda() ');
  ShowHTML('{ ');
  ShowHTML('  document.Form.Botao.value = \'Ajuda\'; ');
  ShowHTML('} ');
  FormataCPF();
  Modulo();
  SaltaCampo(); 
  ValidateOpen('Validacao');
  Validate('Login1','CPF','CPF','1','14','14','','1');
  ShowHTML('  if (theForm.par.value == \'Senha\') {');
  ShowHTML('     if (confirm(\'Este procedimento irá reinicializar sua senha de acesso e sua assinatura eletrônica, enviando os dados para seu e-mail.\\nConfirma?\')) {');
  ShowHTML('     } else {');
  ShowHTML('       return false;');
  ShowHTML('     }');
  ShowHTML('  } else {');
  Validate('Password1','Senha','1','1','3','19','1','1');
  ShowHTML('  }');
  ShowHTML('  theForm.Login.value = theForm.Login1.value; ');
  ShowHTML('  theForm.Password.value = theForm.Password1.value; ');
  ShowHTML('  theForm.Login1.value = \'\'; ');
  ShowHTML('  theForm.Password1.value = \'\'; ');
  ValidateClose();
  ScriptClose();
  ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  ShowHTML('<style>');
  ShowHTML(' .cText {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}');
  ShowHTML(' .cButton {font-size: 8pt; color: #FFFFFF; border: 1px solid #000000; background-color: #669966; }');
  ShowHTML('</style>');
  ShowHTML('</HEAD>');
  // Se receber a username, dá foco na senha
  if (nvl($w_username,'nulo')=='nulo') {
    ShowHTML('<body topmargin=0 leftmargin=10 onLoad=\'document.Form.Login1.focus();\'>');
  } else {
    ShowHTML('<body topmargin=0 leftmargin=10 onLoad=\'document.Form.Password1.focus();\'>');
  }
  ShowHTML('<form method="post" action="default.php" onsubmit="return(Validacao(this));" name="Form"> ');
  ShowHTML('<INPUT TYPE="HIDDEN" NAME="Login" VALUE=""> ');
  ShowHTML('<INPUT TYPE="HIDDEN" NAME="Password" VALUE=""> ');
  ShowHTML('<INPUT TYPE="HIDDEN" NAME="par" VALUE="Log"> ');
  ShowHTML('<INPUT TYPE="HIDDEN" NAME="p_dbms" VALUE="1"> ');
  ShowHTML('<INPUT TYPE="HIDDEN" NAME="p_cliente" VALUE="1"> ');
  ShowHTML('<table width="770" height="31" border="0" cellpadding=0 cellspacing=0>');
  ShowHTML('  <tr><td valign="middle" width="100%" height="100%">');
  ShowHTML('      <table width="100%" height="100%" border="0" cellpadding=0 cellspacing=0> ');
  ShowHTML('        <tr><td bgcolor="#003300" width="100%" height="100%" valign="middle"><font size="2" color="#FFFFFF">&nbsp;');
  ShowHTML('            Usuário: <input class="cText" name="Login1" size="14" maxlength="14" value="'.$w_username.'" onkeyDown="FormataCPF(this,event)" onKeyUp="SaltaCampo(this.form.name,this,14,event);">');
  ShowHTML('            Senha: <input class="cText" type="Password" name="Password1" size="19">');
  ShowHTML('            <input class="cButton" type="submit" value="OK" name="Botao" onClick="document.Form.par.value=\'Log\';"> ');
  ShowHTML('            <input class="cButton" type="submit" value="Lembrar senha" name="Botao" onClick="document.Form.par.value=\'Senha\';" title="Informe seu CPF e clique aqui para receber por e-mail sua senha e assinatura eletrônica!"> ');
  ShowHTML('        </font></td> </tr> ');
  ShowHTML('      </table> ');
  ShowHTML('  </tr> ');
  ShowHTML('</table>');
  ShowHTML('</form> ');
  ShowHTML('</body>');
  ShowHTML('</html>');
}

// =========================================================================
// Rotina principal
// -------------------------------------------------------------------------
function Main() {
  extract($GLOBALS);
  // Monta o formulário de autenticação apenas para a SBPI
  if (!isset($_POST['p_cliente'])) { LogOn(); }
  else {
    $_SESSION['P_CLIENTE']=$_POST['p_cliente'];
    Valida();
  }
}
?>