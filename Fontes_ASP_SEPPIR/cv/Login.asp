<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_rh/DB_CV.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /login.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Autenticação
REM Mail     : alex@sbpi.com.br
REM Criacao  : 12/07/2001 14:15PM
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
' Declaração de variáveis
Dim OraDatabase, RS, SQL, dbms, sp, p_Logon, p_dbms, p_cliente, p_portal, w_cliente
Dim wNoUsuario, wDsSenha, wBotao, wSID
Private Par, w_dir

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
wNoUsuario = uCase(Request("Login"))
wDsSenha   = uCase(Request("Password"))
wBotao     = uCase(Request("Botao"))
Par        = Request("Par")
w_Dir      = "cv/"

p_LogOn    = Request("p_LogOn")
p_dbms     = Request("p_dbms")
p_cliente  = Request("p_cliente")
p_portal   = Request("p_portal")

w_cliente  = p_cliente

Main

FechaSessao

Set p_Logon     = Nothing
Set p_Cliente   = Nothing
Set p_dbms      = Nothing
Set p_portal    = Nothing
Set w_cliente   = Nothing
Set sp          = Nothing
Set dbms        = Nothing
Set w_dir       = Nothing
Set OraDatabase = Nothing
Set RS          = Nothing
Set SQL         = Nothing
Set Par         = Nothing
Set wNoUsuario  = Nothing
Set wDsSenha    = Nothing
Set wBotao      = Nothing
Set wSID        = Nothing

REM =========================================================================
REM Rotina de autenticação dos usuários
REM -------------------------------------------------------------------------
Sub Valida

  Dim w_Erro
  Dim w_Existe
  Dim w_Inicial

  w_Erro = 0
  ' Recupera os dados do currículo a partir da chave
  DB_GetCV_Pessoa RS, w_cliente, Request("Login")
  
  If RS.EOF Then
     ShowHTML "  <SCRIPT LANGUAGE=""JAVASCRIPT"">alert('Colaborador não cadastrado!'); history.back(1);</SCRIPT>"
  Else
     ' Recupera informações a serem usadas na montagem das telas para o usuário
     Session("USERNAME")    = RS("cpf")
     Session("SQ_PESSOA")   = RS("sq_pessoa")
     Session("NOME")        = RS("nome")
     Session("LogOn")       = "Sim"
     DesconectaBD
     Response.Redirect "menu.asp?par=Frames&p_portal=" & p_portal & "&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente
  End If

  Set w_Inicial= Nothing
  Set w_Erro   = Nothing
  Set w_Existe = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de autenticação de usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de criação da tela de logon
REM -------------------------------------------------------------------------
Sub LogOn
  ShowHTML "<HTML>"
  ShowHTML "<HEAD>"
  ShowHTML "<link rel=""shortcut icon"" href=""favicon.ico"">"
  ScriptOpen "JavaScript"
  ShowHTML "function Ajuda() "
  ShowHTML "{ "
  ShowHTML "  document.Form.Botao.value = ""Ajuda""; "
  ShowHTML "} "
  FormataCPF
  Modulo
  ValidateOpen "Validacao"
  Validate "Login1", "CPF", "CPF", "1", "14", "14", "", "1"
  ShowHTML "  if (theForm.par.value == 'Senha') {"
  ShowHTML "     if (confirm('Este procedimento irá reinicializar sua senha de acesso, enviando-a para seu e-mail.\nConfirma?')) {"
  ShowHTML "     } else {"
  ShowHTML "       return false;"
  ShowHTML "     }"
  ShowHTML "  } else {"
  Validate "Password1", "Senha", "1", "", "3", "19", "1", "1"
  ShowHTML "  }"
  ShowHTML "  theForm.Login.value = theForm.Login1.value; "
  ShowHTML "  theForm.Password.value = theForm.Password1.value; "
  ShowHTML "  theForm.Login1.value = """"; "
  ShowHTML "  theForm.Password1.value = """"; "
  ValidateClose
  ScriptClose
  ShowHTML "<BASEFONT FACE=""Verdana"" SIZE=""2""> "
  ShowHTML "<style> "
  ShowHTML " .SS{text-decoration:none;font:bold 8pt} "
  ShowHTML " .SS:HOVER{text-decoration: underline;} "
  ShowHTML " .HL{text-decoration:none;font:Arial;color=""#0000FF""} "
  ShowHTML " .HL:HOVER{text-decoration: underline;} "
  ShowHTML " .TTM{font: 10pt Arial}"
  ShowHTML " .BTM{font: 8pt Verdana}"
  ShowHTML " .XTM{font: 12pt Verdana}"
  ShowHtml " .STI {font-size: 8pt; border: 1px solid #000000; background-color: #F5F5F5}"  & VbCrLf
  ShowHtml " .STB {font-size: 8pt; color: #FFFFFF; border: 1px solid #000000; background-color: #3591B3; }"  & VbCrLf
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  ShowHTML "<body topmargin=0 leftmargin=10 onLoad=""document.Form.Login1.focus();"">"
  ShowHTML "<form method=""post"" action=""login.asp"" onsubmit=""return(Validacao(this));"" name=""Form"" target=""_top""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Login"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Password"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""par"" VALUE=""Log""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_LogOn"" VALUE=""" & p_Logon & """> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_dbms"" VALUE=""" & p_dbms & """> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_cliente"" VALUE=""" & p_cliente & """> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_portal"" VALUE=""" & p_portal & """> "
  ShowHTML "<center><br><br><br><table width=""100%"" border=""0"" cellpadding=0 cellspacing=0>"
  ShowHTML "  <tr><td valign=""top"" align=""center"" width=""100%"" height=""100%"">"
  ShowHTML "      <table border=""0"">"
  ShowHTML "        <tr><td align=""right""><font size=""2""><B>CPF:<td><input class=""sti"" name=""Login1"" size=""14"" maxlength=""14"" onkeyDown=""FormataCPF(this,event)""></td></tr>"
  ShowHTML "        <tr><td align=""right""><font size=""2""><B>Senha:<td><input class=""sti"" type=""Password"" name=""Password1"" size=""19""></td></tr>"
  ShowHTML "        <tr><td><td><font size=""2"">"
  ShowHTML "            <input class=""stb"" type=""submit"" value=""OK"" name=""Botao"" onClick=""document.Form.par.value='Log';""> "
  ShowHTML "            <input class=""stb"" type=""submit"" value=""Recriar senha"" name=""Botao"" onClick=""document.Form.par.value='Senha';"" title=""Informe seu CPF e clique aqui para receber por e-mail sua senha!""> "
  ShowHTML "        </font></td> </tr> "
  ShowHTML "      </table> "
  ShowHTML "  <tr><td valign=""top"" align=""center"" width=""100%"" height=""100%"">"
  ShowHTML "      <table border=""0"">"
  ShowHTML "        <tr><td height=30>"
  ShowHTML "        <TR><TD><IMG height=37 src=""images/ajuda.jpg"" width=629> "
  ShowHTML "            <ul><FONT face=""Verdana, Arial, Helvetica, sans-serif"" size=1> "
  ShowHTML "            <li>CPF - Informe apenas os números do seu CPF. O sistema colocará automaticamente pontos e traço.<BR> "
  ShowHTML "            <li>Senha - Informe sua senha de acesso. Não se preocupe com maiúsculas e minúsculas.<br> "
  ShowHTML "            <li>Se você não lembra sua senha de acesso, informe seu CPF e clique no botão ""Recriar senha"" para que o sistema recrie e envie a nova senha para seu e-mail.<br> "
  ShowHTML "            </ul></FONT></P></TD></TR> "
  ShowHTML "        <TR><TD height=19><DIV align=center><IMG height=2 src=""images/linha.jpg"" width=551></DIV></TD></TR> "
  ShowHTML "      </table> "
  ShowHTML "  </tr> "
  ShowHTML "</table></table>"
  ShowHTML "</form> "
  ScriptOpen "Javascript"
  ShowHTML "  if (navigator.appVersion.search('MSIE 6.0') == -1) {"
  ShowHTML "    alert('Este sistema exige a utilização do MS-Internet Explorer 6, Service Pack 1, ou superior.\nAtualize a versão antes de iniciar sua utilização.'); "
  ShowHTML "    document.Form.Login1.readonly=true; "
  ShowHTML "    document.Form.Password1.disabled=true; "
  ShowHTML "    document.Form.Botao[0].disabled=true; "
  ShowHTML "    document.Form.Botao[1].disabled=true; "
  ShowHTML "  }"
  'ShowHTML "  else if (navigator.appMinorVersion.search('SP') == -1) {"
  'ShowHTML "    alert('Este sistema exige a utilização do MS-Internet Explorer 6, Service Pack 1, ou superior.\nAtualize a versão antes de iniciar sua utilização.'); "
  'ShowHTML "    document.Form.Login1.readonly=true; "
  'ShowHTML "    document.Form.Password1.disabled=true; "
  'ShowHTML "    document.Form.Botao[0].disabled=true; "
  'ShowHTML "    document.Form.Botao[1].disabled=true; "
  'ShowHTML "  }"
  ScriptClose
  ShowHTML "</body>"
  ShowHTML "</html>"
End Sub
REM =========================================================================
REM Fim da rotina de criação da tela de logon
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  If Par = "Log" Then
    If Request("Botao") <> "Ajuda" Then
      Valida
    Else
        Cabecalho
		ShowHTML "<div align=""center""><center> "
		ShowHTML "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""90%"" height=""100"" style=""border-left: 1px solid rgb(0,0,0); border-right: 3px solid rgb(0,0,0); border-top: 1px solid rgb(0,0,0); border-bottom: 2px solid rgb(0,0,0)""> "
		ShowHTML "    <tr> "
		ShowHTML "  	<td width=""100%""  height=""30"" colspan=""2""><strong><font size=""2"">Ajuda</font></strong></td> "
		ShowHTML "    </tr> "
		ShowHTML "    <tr> "
		ShowHTML "      <td width=""9%""  height=""30""><font size=""2""></font></td> "
		ShowHTML "      <td width=""91%""  height=""30""><font size=""2"">Esta tela serve para verificar seu acesso à base de dados. Informe os campos solicitados e clique no botão com a função desejada, conforme descrito abaixo:</font> "
		ShowHTML "  	    <div align=""center""><center> "
		ShowHTML "  	    <br><table border=""0"" cellpadding=""0"" cellspacing=""3"" width=""98%""> "
		ShowHTML "  	      <tr> "
		ShowHTML "  	        <td width=""16%"" bgcolor=""#000000"" align=""center""><font color=""#FFFFFF"" size=""2""><strong>Acessa</strong></font></td> "
		ShowHTML "  	        <td width=""2%""><font size=""2""></font></td> "
		ShowHTML "  	        <td width=""82%""><font size=""2"">Exibe os serviços disponíveis para sua senha.</font></td> "
		ShowHTML "  	      </tr> "
		ShowHTML "  	      <tr> "
		ShowHTML "  	        <td width=""16%"" bgcolor=""#000000"" align=""center""><font color=""#FFFFFF"" size=""2""><strong>Troca Senha</strong></font></td> "
		ShowHTML "  	        <td width=""2%""><font size=""2""></font></td> "
		ShowHTML "  	        <td width=""82%""><font size=""2"">Permite a troca da sua senha de acesso. Você deve informar seu CPF e a senha atual para entrar nesta opção.&nbsp; </font></td> "
		ShowHTML "  	      </tr> "
		ShowHTML "  	      <tr> "
		ShowHTML "  	        <td width=""16%"" bgcolor=""#000000"" align=""center""><font color=""#FFFFFF"" size=""2""><strong>Ajuda</strong></font></td> "
		ShowHTML "  	        <td width=""2%""><font size=""2""></font></td> "
		ShowHTML "  	        <td width=""82%""><font size=""2"">Chama esta tela.</font></td> "
		ShowHTML "  	      </tr> "
		ShowHTML "  	    </table> "
		ShowHTML "  	    </center></div> "
		ShowHTML "  	</td> "
		ShowHTML "    </tr> "
		ShowHTML "    <tr> "
		ShowHTML "      <td width=""100%""  height=""29"" style=""border-top: 1px solid rgb(0,0,0)"" colspan=""2""> "
		ShowHTML "  		<img src=""/images/arrow33b.gif"" width=""54"" height=""18"" alt=""Volta"" onClick=""history.back(1)""> "
		ShowHTML "  	</td> "
		ShowHTML "    </tr> "
		ShowHTML "  </table> "
		ShowHTML "  <p>&nbsp.</p> "
		ShowHTML "  </center></div> "
		Rodape
    End If
  Else
    LogOn
  End If
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------

%>

