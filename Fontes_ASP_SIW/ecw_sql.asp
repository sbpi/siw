<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="ecw/DB_Geral.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /ecw_sql.asp
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

  If Request("regional") = "" Then
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
           ShowHTML "  alert('Usuário com acesso bloqueado pelo gestor do sistema!');"
        End If
        ShowHTML "  history.back(1);"
     Else
        DB_GetUserData rs, Session("p_cliente"), wNoUsuario
        If par = "Log" Then
           DB_GetUserData rs, Session("p_cliente"), wNoUsuario
           ' Recupera informações a serem usadas na montagem das telas para o usuário
           Session("USERNAME")         = RS("USERNAME")
           Session("SQ_PESSOA")        = RS("SQ_PESSOA")
           Session("NOME")             = RS("NOME")
           Session("NOME_RESUMIDO")    = RS("NOME_RESUMIDO")
           Session("LOTACAO")          = RS("SQ_UNIDADE")
           Session("CODIGO")           = RS("CODIGO")
           Session("LOCALIZACAO")      = RS("SQ_LOCALIZACAO")
           Session("INTERNO")          = RS("INTERNO")
           Session("LogOn")            = "Sim"
           Session("ENDERECO")         = RS("SQ_PESSOA_ENDERECO")
           ShowHTML "  location.href='ecw_sql.asp?par=Frames&Login=" & wNoUsuario &  "';"
        Else
           w_resultado = EnviaMail("SIW - Lembrança de senha", "Senha: " & RS("SENHA") & "<br>Assinatura:" & RS("ASSINATURA"),RS("email"))
           If w_resultado > "" Then
              ShowHTML "  alert('ATENÇÃO: não foi possível proceder o envio do e-mail.\n" & w_resultado & "');"
           Else
              ShowHTML "  alert('Sua senha foi enviada para " & RS("EMAIL") & "!');"
           End If
           ShowHTML "  history.back(1);"
        End If
        DesconectaBD
     End If
     ScriptClose
  Else
     Session("regional")         = Request("regional")
     Session("periodo")          = Request("periodo")
     ScriptOpen "JavaScript"
     ShowHTML "  location.href='menu.asp?par=Frames';"
     ScriptClose
  End If

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
  Dim p_cliente
  p_cliente = 168
  ShowHTML "<HTML>"
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Autenticação</TITLE>"
  ScriptOpen "JavaScript"
  ShowHTML "function Ajuda() "
  ShowHTML "{ "
  ShowHTML "  document.Form.Botao.value = ""Ajuda""; "
  ShowHTML "} "
  FormataCPF
  Modulo
  ValidateOpen "Validacao"
  If wNoUsuario = "" Then
    Validate "Login1", "CPF", "CPF", "1", "14", "14", "", "1"
    ShowHTML "  if (theForm.par.value == 'Senha') {"
    ShowHTML "     if (confirm('A senha será enviada para seu e-mail. Confirma?')) {"
    ShowHTML "     } else {"
    ShowHTML "       return false;"
    ShowHTML "     }"
    ShowHTML "  } else {"
    Validate "Password1", "Senha", "1", "1", "3", "19", "1", "1"
    ShowHTML "  }"
    ShowHTML "  theForm.Password.value = theForm.Password1.value; "
    ShowHTML "  theForm.Password1.value = """"; "
  End If
  ShowHTML "  theForm.Botao[0].disabled = true; "
  ShowHTML "  theForm.Botao[1].disabled = true; "
  ShowHTML "  theForm.Login.value = theForm.Login1.value; "
  ShowHTML "  theForm.Login1.value = """"; "
  ValidateClose
  ScriptClose
  ShowHTML "  <link rel=""stylesheet"" type=""text/css"" href=""" & conRootSIW & "cp_menu/xPandMenu.css"">"
  ShowHTML "</HEAD>"
  If wNoUsuario = "" Then
     ShowHTML "<body topmargin=0 leftmargin=10 onLoad=""document.Form.Login1.focus();"">"
  Else
     ShowHTML "<body topmargin=0 leftmargin=10 onLoad=""document.focus();"">"
  End If
  ShowHTML "<CENTER>"
  ShowHTML "<form method=""post"" action=""ecw_sql.asp"" onsubmit=""return(Validacao(this));"" name=""Form""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Login"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Password"" VALUE=""""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""par"" VALUE=""Log""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_dbms"" VALUE=""2""> "
  ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_cliente"" VALUE=""" & p_cliente & """> "
  ShowHTML "<TABLE cellSpacing=0 cellPadding=0 width=""780"" height=550 border=1  background=""" & LinkArquivo(null, p_cliente, "img\logo_sge.jpg", null, null, null, "WORD") & """ bgproperties=""fixed""><tr><td width=""100%"" valign=""top"">"
  ShowHTML "  <TABLE cellSpacing=0 cellPadding=0 width=""100%"" border=0>"
  ShowHTML "    <TR>"
  ShowHTML "      <TD vAlign=center align=middle><IMG border=1 src=""" & LinkArquivo(null, p_cliente, "img\logo1.gif", null, null, null, "WORD") & """></TD>"
  ShowHTML "      <TD vAlign=TOP align=middle width=""65%"">"
  ShowHTML "        <B><FONT face=Arial size=5 color=#000088>Secretaria de Estado de Educação"
  ShowHTML "        <br><br>SGE - Corporativo</B></FONT>"
  ShowHTML "     </TD>"
  ShowHTML "      <TD vAlign=center align=middle><IMG border=1 src=""" & LinkArquivo(null, p_cliente, "img\logo.gif", null, null, null, "WORD") & """ ></TD></TR>"
  ShowHTML "    <TR><TD colspan=3 borderColor=#ffffff height=22><HR align=center color=#808080></TD></TR>"
  ShowHTML "  </TABLE>"
  ShowHTML "  <table width=""100%"" border=""0"">"
  ShowHTML "    <tr><td valign=""middle"" width=""100%"" height=""100%"">"
  ShowHTML "        <table width=""100%"" height=""100%"" border=""0"">"
  If wNoUsuario = "" Then
    ShowHTML "          <tr><td align=""center"" colspan=2><font size=""1"" color=""#990000""><b>Esta aplicação é de uso interno da Secretaria de Estado de Educação.<br>As informações contidas nesta aplicação são restritas e de uso exclusivo.<br>O uso indevido acarretará ao infrator penalidades de acordo com a legislação em vigor.<br><br>Informe seu CPF, senha de acesso e clique no botão <i>OK</i> para ser autenticado pela aplicação.<br>Informe seu CPF e clique no botão <i>Lembrar senha</i> para receber sua senha por e-mail.</b></font>"
    ShowHTML "          <tr><td align=""right"" width=""43%""><font size=""2""><b>CPF:<td><input class=""sti"" name=""Login1"" size=""14"" maxlength=""14"" onkeyDown=""FormataCPF(this,event)"">"
    ShowHTML "          <tr><td align=""right""><font size=""2""><b>Senha:<td><input class=""sti"" type=""Password"" name=""Password1"" size=""19"">"
    ShowHTML "          <tr><td align=""right""><td><font size=""2""><b><input class=""stb"" type=""submit"" value=""OK"" name=""Botao"" onClick=""document.Form.par.value='Log';""> "
    ShowHTML "              <input class=""stb"" type=""submit"" value=""Lembrar senha"" name=""Botao"" onClick=""document.Form.par.value='Senha';"" title=""Informe seu CPF e clique aqui para receber por e-mail sua senha e assinatura eletrônica!""> "
    ShowHTML "          </font></td> </tr> "
  Else
    ShowHTML "          <tr><td align=""center"" colspan=2><font size=""1"" color=""#990000""><b>Selecione o período letivo, a regional de ensino e clique no botão <i>OK</i> para acessar a aplicação.<br>Para voltar à tela de autenticação, clique no botão <i>Voltar</i>.</b></font>"
    ShowHTML "          <tr><td align=""right"" width=""43%""><font size=""2""><b>CPF:<td><input class=""sti"" name=""Login2"" size=""60"" maxlength=""14"" onkeyDown=""FormataCPF(this,event)"" value=""" & wNoUsuario & " - " & Session("Nome") & """ disabled>"
    ShowHTML "          <INPUT TYPE=""HIDDEN"" NAME=""Login1"" VALUE=""" & wNoUsuario & """> "
    ShowHTML "          <tr valign=""top"">"
    DB_GetPeriodoList RS
    RS.Sort = "periodo desc"
    ShowHTML "            <td align=""right""><font size=""2""><b>Período Letivo:</b><td><SELECT CLASS=""STI"" NAME=""periodo"">"
    While Not RS.EOF
       ShowHTML "          <option value=""" & RS("ano_sem") & """>" & RS("periodo")
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    ShowHTML "          </tr>"
    ShowHTML "          <tr valign=""top"">"
    ShowHTML "            <td align=""right""><font size=""2""><b>Regional de Ensino:</b><td><SELECT CLASS=""STI"" NAME=""regional"">"
    DB_GetUorgList RS, P_cliente, null, null, null, null
    If Nvl(Session("codigo"),"00") = "00" Then
       RS.Filter = "informal='N' and codigo <> '00'"
       ShowHTML "          <option value=""00"">Todas"
    Else
       RS.Filter = "informal='N' and codigo = '" & Session("codigo") & "'"
    End If
    RS.Sort = "codigo"
    While Not RS.EOF
       ShowHTML "          <option value=""" & RS("codigo") & """>" & RS("nome")
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    ShowHTML "          </tr>"
    ShowHTML "          <tr><td align=""right""><td><font size=""2""><b>"
    ShowHTML "              <input class=""stb"" type=""submit"" value=""OK"" name=""Botao"" onClick=""document.Form.par.value='Log';""> "
    DB_GetCustomerSite RS, Session("p_cliente")
    ShowHTML "              <input class=""stb"" type=""button"" value=""Voltar"" name=""Botao"" onClick=""location.href='" & RS("logradouro") & "?p_dbms=" & Session("dbms") & "';"">"
    DesconectaBD
    ShowHTML "              </td> </tr> "
  End If
  ShowHTML "        </table> "
  ShowHTML "    </tr> "
  ShowHTML "  </table>"
  ShowHTML "</table>"
  ShowHTML "</form> "
  ScriptOpen "Javascript"
  ShowHTML "  if (navigator.appVersion.search('MSIE 6.0') == -1) {"
  ShowHTML "    alert('Este sistema exige a utilização do MS-Internet Explorer 6, Service Pack 1, ou superior.\nAtualize a versão antes de iniciar sua utilização.'); "
  ShowHTML "    document.Form.Login1.readonly=true; "
  ShowHTML "    document.Form.Password1.disabled=true; "
  ShowHTML "    document.Form.Botao[0].disabled=true; "
  ShowHTML "    document.Form.Botao[1].disabled=true; "
  ShowHTML "  } else if (navigator.appMinorVersion.search('SP') == -1) {"
  ShowHTML "    alert('Este sistema exige a utilização do MS-Internet Explorer 6, Service Pack 1, ou superior.\nAtualize a versão antes de iniciar sua utilização.'); "
  ShowHTML "    document.Form.Login1.readonly=true; "
  ShowHTML "    document.Form.Password1.disabled=true; "
  ShowHTML "    document.Form.Botao[0].disabled=true; "
  ShowHTML "    document.Form.Botao[1].disabled=true; "
  ShowHTML "  }"
  ShowHTML "</CENTER>"
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
