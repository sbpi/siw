<?
header('Expires: '.-1500);
session_start();
$w_dir_volta = '../';
session_register('dbms_session');
session_register('schema_session');
session_register('p_cliente_session');
session_register('sq_pessoa_session');
session_register('ano_session');
session_register('siw_email_conta_session');
session_register('siw_email_nome_session');
session_register('siw_email_senha_session');
session_register('smtp_server_session');
session_register('schema_is_session');

// =========================================================================
//  visualCurriculoWord.php
// ------------------------------------------------------------------------
// Nome     : Alexandre Vinhadelli Papadópolis
// Descricao: Recebe os dados do currículo
// Mail     : alex@sbpi.com.br
// Criacao  : 22/07/2003 16:00
// Versao   : 1.0.0.0
// Local    : Brasília - DF
// -------------------------------------------------------------------------
// 

//DATA DO BANCO DE DADOS
$SQL='SELECT TO_CHAR(SYSDATE,'DD/MM/YYYY,HH24:MM') DATA FROM DUAL';
ConectaBD();
$w_data_banco = f($RS,'DATA');
$w_cpf        = $_REQUEST['w_cpf'];
header('Content-type: '.'application/msword');
ShowHTML('<HEAD>');
ShowHTML('<TITLE>Curriculum Vitae</TITLE>');
ShowHTML('</HEAD>');
ShowHTML('<TABLE WIDTH=\'100%\' BORDER=0><TD ALIGN=\'RIGHT\'><B><FONT SIZE=5 COLOR=\'#000000\'>');
ShowHTML('Curriculum Vitae');
ShowHTML('</FONT><TR><TD ALIGN=\'RIGHT\'><B><FONT SIZE=2 COLOR=\'#000000\'>'.$w_data_banco.'</B></TD></TR>');
ShowHTML('</FONT></B></TD></TR></TABLE>');
ShowHTML('<HR>');
$HTML = VisualCurriculo($w_cpf,'L');
ShowHTML(''.$HTML);
$OraDatabase->close;
?>
