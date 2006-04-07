<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /default.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Autenticação
REM Mail     : alex@sbpi.com.br
REM Criacao  : 05/11/2002 16:14PM
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
' Declaração de variáveis
Dim dbms, sp, RS
Dim wNoUsuario, wDsSenha, wBotao, wSID
Dim w_dir_volta
Private Par
Set RS = Server.CreateObject("ADODB.RecordSet")

If Session("dbms") = "" or Request("p_dbms") > "" Then
   If Request("p_dbms") = "" Then
      If Request("p_cliente") <> 1 and Request("p_cliente") <> "" Then
         Response.Write "*** Erro"
         Response.End()
      End If
   Else
      Session("dbms") = Request("p_dbms")
   End If
End If

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
wNoUsuario = uCase(Request("Login"))
wDsSenha   = uCase(Request("Password"))
wBotao     = uCase(Request("Botao"))
Par        = Request("Par")

Main

FechaSessao


Set RS          = Nothing
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
  Dim w_resultado
  Dim w_senha
  Dim w_html

  w_Erro = 0
  If cDbl(DB_VerificaUsuario(Session("p_cliente"), wNoUsuario)) = 0 Then
     w_Erro = 1
  ElseIf wDsSenha > "" Then
     w_erro = DB_VerificaSenha(Session("p_cliente"), wNoUsuario, wDsSenha)
  End If
  ScriptOpen "JavaScript"
  If w_erro > 0 Then
     If w_Erro = 1 Then
        ShowHTML "  alert('Usuário inexistente!');"
     ElseIf w_Erro = 2 Then
        ShowHTML "  alert('Senha inválida!');"
     ElseIf w_Erro = 3 Then
        ShowHTML "  alert('Usuário com acesso bloqueado pelo gestor de segurança!');"
     End If
     ShowHTML "  history.back(1);"
  Else
     ' Recupera informações do cliente, relativas ao envio de e-mail
     DB_GetCustomerData RS, Session("p_cliente")
     Session("SMTP_SERVER")      = RS("SMTP_SERVER")
     Session("SIW_EMAIL_CONTA")  = RS("SIW_EMAIL_CONTA")
     Session("SIW_EMAIL_SENHA")  = RS("SIW_EMAIL_SENHA")
     DesconectaBD
     
     DB_GetUserData RS, Session("p_cliente"), wNoUsuario
     ' Recupera informações a serem usadas na montagem das telas para o usuário
     Session("USERNAME")         = RS("USERNAME")
     Session("SQ_PESSOA")        = RS("SQ_PESSOA")
     Session("NOME")             = RS("NOME")
     Session("EMAIL")            = RS("EMAIL")
     Session("NOME_RESUMIDO")    = RS("NOME_RESUMIDO")
     Session("LOTACAO")          = RS("SQ_UNIDADE")
     Session("LOCALIZACAO")      = RS("SQ_LOCALIZACAO")
     Session("INTERNO")          = RS("INTERNO")
     Session("LogOn")            = "Sim"
     Session("ENDERECO")         = RS("SQ_PESSOA_ENDERECO")
     Session("ANO")              = Year(Date())
     If par = "Log" Then
        If Request("p_cliente") = 6761 and Request("p_versao") = 2 Then
           If RS("interno") = "S" Then
              ShowHTML "  top.location.href='cl_cespe/trabalho.asp?par=mesa&sg=mesa&TP=Acompanhamento';"
           Else
              DB_GetLinkData RS, Session("p_cliente"), "PJCADA"
              ShowHTML "  location.href='" & RS("link") & "&O=&P1=" & RS("P1") & "&P2=" & RS("P2") & "&P3=" & RS("P3") & "&P4=" & RS("P4") & "&TP=" & RS("nome") & "&SG=" & RS("sigla") & "';"
           End If
        ElseIf Request("p_cliente") = 1 Then
           ShowHTML "  top.location.href='menu.asp?par=Frames';"
        Else
           ShowHTML "  location.href='menu.asp?par=Frames';"
        End If
     Else
        ' Cria a nova senha, pegando a hora e o minuto correntes
        w_senha = "nova" & mid(replace(time(),":",""),3,4)
        
        ' Atualiza a senha de acesso e a assinatura eletrônica, igualando as duas
        DB_UpdatePassword Session("p_cliente"), Session("sq_pessoa"), w_senha, "PASSWORD"
        DB_UpdatePassword Session("p_cliente"), Session("sq_pessoa"), w_senha, "SIGNATURE"
        
        ' Configura a mensagem automática comunicando ao usuário sua nova senha de acesso e assinatura eletrônica
        w_html = "<HTML>" & VbCrLf
        w_html = w_html & BodyOpenMail(null) & VbCrLf
        w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
        w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
        w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
        w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>REINICIALIZAÇÃO DE SENHA</b></font><br><br><td></tr>" & VbCrLf
        w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf
        w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
        w_html = w_html & "         Sua senha e assinatura eletrônica foram reinicializadas. A partir de agora, utilize os dados informados abaixo:<br>" & VbCrLf
        w_html = w_html & "         <ul>" & VbCrLf
        DB_GetCustomerSite RS, Session("p_cliente")
        w_html = w_html & "         <li>Endereço de acesso ao sistema: <b><a class=""SS"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
        DesconectaBD
        w_html = w_html & "         <li>CPF: <b>" & Session("username") & "</b></li>" & VbCrLf
        w_html = w_html & "         <li>Senha de acesso: <b>" & w_senha & "</b></li>" & VbCrLf
        w_html = w_html & "         <li>Assinatura eletrônica: <b>" & w_senha & "</b></li>" & VbCrLf
        w_html = w_html & "         </ul>" & VbCrLf
        w_html = w_html & "      </font></td></tr>" & VbCrLf
        w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
        w_html = w_html & "         Orientações e observações:<br>" & VbCrLf
        w_html = w_html & "         <ol>" & VbCrLf
        w_html = w_html & "         <li>Troque sua senha de acesso e assinatura no primeiro acesso que fizer ao sistema.</li>" & VbCrLf
        w_html = w_html & "         <li>Para trocar sua senha de acesso, localize no menu a opção <b>Troca senha</b> e clique sobre ela, seguindo as orientações apresentadas.</li>" & VbCrLf
        w_html = w_html & "         <li>Para trocar sua assinatura eletrônica, localize no menu a opção <b>Assinatura eletrônica</b> e clique sobre ela, seguindo as orientações apresentadas.</li>" & VbCrLf
        w_html = w_html & "         <li>Você pode fazer com que a senha de acesso e a assinatura eletrônica tenham o mesmo valor ou valores diferentes. A decisão é sua.</li>" & VbCrLf
        DB_GetCustomerData RS, Session("p_cliente")
        w_html = w_html & "         <li>Tanto a senha quanto a assinatura eletrônica têm tempo de vida máximo de <b>" & RS("dias_vig_senha") & "</b> dias. O sistema irá recomendar a troca <b>" & RS("dias_aviso_expir") & "</b> dias antes da expiração do tempo de vida.</li>" & VbCrLf
        w_html = w_html & "         <li>O sistema irá bloquear seu acesso se você errar sua senha de acesso ou sua senha de acesso <b>" & RS("maximo_tentativas") & "</b> vezes consecutivas. Se você tiver dúvidas ou não lembrar sua senha de acesso ou assinatura de acesso, utilize a opção ""Lembrar senha"" na tela de autenticação do sistema.</li>" & VbCrLf
        DesconectaBD
        w_html = w_html & "         <li>Acessos bloqueados por expiração do tempo de vida da senha de acesso ou assinaturas eletrônicas, ou por exceder o máximo de erros consecutivos, só podem ser desbloqueados pelo gestor de segurança do sistema.</li>" & VbCrLf
        w_html = w_html & "         </ol>" & VbCrLf
        w_html = w_html & "      </font></td></tr>" & VbCrLf
        w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
        w_html = w_html & "         Dados da ocorrência:<br>" & VbCrLf
        w_html = w_html & "         <ul>" & VbCrLf
        w_html = w_html & "         <li>Data do servidor: <b>" & FormatDateTime(Date(),1) & ", " & Time() & "</b></li>" & VbCrLf
        w_html = w_html & "         <li>IP de origem: <b>" & Request.ServerVariables("REMOTE_HOST") & "</b></li>" & VbCrLf
        w_html = w_html & "         </ul>" & VbCrLf
        w_html = w_html & "      </font></td></tr>" & VbCrLf
        w_html = w_html & "    </table>" & VbCrLf
        w_html = w_html & "</td></tr>" & VbCrLf
        w_html = w_html & "</table>" & VbCrLf
        w_html = w_html & "</BODY>" & VbCrLf
        w_html = w_html & "</HTML>" & VbCrLf

        ' Executa a função de envio de e-mail
        w_resultado = EnviaMail("Aviso de reinicialização de senha", w_html, Session("email"))
        
        ' Se ocorreu algum erro, avisa da impossibilidade de envio do e-mail,
        ' caso contrário, avisa que o e-mail foi enviado para o usuário.
        If w_resultado > "" Then
           ShowHTML "  alert('ATENÇÃO: não foi possível proceder o envio do e-mail.\n" & w_resultado & "');"
        Else
           ShowHTML "  alert('Sua senha foi enviada para " & Session("email") & "!');"
        End If
        ShowHTML "  history.back(1);"
     End If
  End If
  ScriptClose

  Set w_html      = Nothing
  Set w_Senha     = Nothing
  Set w_resultado = Nothing
  Set w_Inicial   = Nothing
  Set w_Erro      = Nothing
  Set w_Existe    = Nothing

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
  ShowHTML "<TITLE>" & conSgSistema & " - Autenticação</TITLE>"
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
  ShowHTML "     if (confirm('Este procedimento irá reinicializar sua senha de acesso e sua assinatura eletrônica, enviando os dados para seu e-mail.\nConfirma?')) {"
  ShowHTML "     } else {"
  ShowHTML "       return false;"
  ShowHTML "     }"
  ShowHTML "  } else {"
  Validate "Password1", "Senha", "1", "1", "3", "19", "1", "1"
  ShowHTML "  }"
  ShowHTML "  theForm.Login.value = theForm.Login1.value; "
  ShowHTML "  theForm.Password.value = theForm.Password1.value; "
  ShowHTML "  theForm.Login1.value = """"; "
  ShowHTML "  theForm.Password1.value = """"; "
  ValidateClose
  ScriptClose
  ShowHTML "  <link rel=""stylesheet"" type=""text/css"" href=""" & conRootSIW & "cp_menu/xPandMenu.css"">"
  ShowHTML "</HEAD>"
  ShowHTML "<body topmargin=0 leftmargin=10 onLoad=""document.Form.Login1.focus();"">"
  ShowHTML "<form method=""post"" action=""Default.asp"" onsubmit=""return(Validacao(this));"" name=""Form""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Login"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Password"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""par"" VALUE=""Log""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_dbms"" VALUE=""1""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_cliente"" VALUE=""1""> "
  ShowHTML "<table width=""770"" height=""31"" border=""0"" cellpadding=0 cellspacing=0>"
  ShowHTML "  <tr><td valign=""middle"" width=""100%"" height=""100%"">"
  ShowHTML "      <table width=""100%"" height=""100%"" border=""0"" cellpadding=0 cellspacing=0> "
  ShowHTML "        <tr><td bgcolor=""#003300"" width=""100%"" height=""100%"" valign=""middle""><font size=""2"" color=""#FFFFFF"">&nbsp;"
  ShowHTML "            Usuário: <input class=""sti"" name=""Login1"" size=""14"" maxlength=""14"" onkeyDown=""FormataCPF(this,event)"">"
  ShowHTML "            Senha: <input class=""sti"" type=""Password"" name=""Password1"" size=""19"">"
  ShowHTML "            <input class=""stb"" type=""submit"" value=""OK"" name=""Botao"" onClick=""document.Form.par.value='Log';""> "
  ShowHTML "            <input class=""stb"" type=""submit"" value=""Lembrar senha"" name=""Botao"" onClick=""document.Form.par.value='Senha';"" title=""Informe seu CPF e clique aqui para receber por e-mail sua senha e assinatura eletrônica!""> "
  ShowHTML "        </font></td> </tr> "
  ShowHTML "      </table> "
  ShowHTML "  </tr> "
  ShowHTML "</table>"
  ShowHTML "</form> "
  ScriptOpen "Javascript"
  ShowHTML "  if (navigator.appVersion.search('MSIE 6.0') == -1 && navigator.appName.search('Netscape') == -1) {"
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
  ShowHTML conEndPage
End Sub
REM =========================================================================
REM Fim da rotina de criação da tela de logon
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Monta o formulário de autenticação apenas para a SBPI
  If Request("p_cliente") = 1 or Request("p_cliente") = "" Then
     LogOn
  End If
  If Request("p_cliente") > "" Then
     Session("p_cliente") = Request("p_cliente")
     Valida
  End If
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------

%>
