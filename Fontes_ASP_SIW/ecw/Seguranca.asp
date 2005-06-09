<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Seguranca.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o módulo de segurança do sistema
REM Mail     : alex@sbpi.com.br
REM Criacao  : 17/01/2001 13:35
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = C   : Cancelamento
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_classe, w_cliente
Dim w_Assinatura, w_cor, w_filter, w_dir
Dim p_gestor, p_lotacao, p_localizacao, p_nome, p_ordena
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "seguranca.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"
p_localizacao= uCase(Request("p_localizacao"))
p_lotacao    = uCase(Request("p_lotacao"))
p_nome       = uCase(Request("p_nome"))
p_gestor     = uCase(Request("p_gestor"))
p_ordena     = uCase(Request("p_ordena"))

If O = "" Then
   If par="USUARIOS" Then O = "P" Else O = "L" End If
End If

' Configura a tela inicial quando for manipulação do menu do cliente
If (SG="CLMENU" or SG="MENU") and Request("p_modulo") = "" Then O = "P" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "R" 
     w_TP = TP & " - Acessos"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente = RetornaCliente()
  
Main

FechaSessao

Set w_dir         = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set w_cliente     = Nothing
Set p_localizacao = Nothing
Set p_lotacao     = Nothing
Set p_gestor      = Nothing
Set p_nome        = Nothing
Set p_ordena      = Nothing

Set RS            = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Classe      = Nothing
Set w_Cont        = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing

REM =========================================================================
REM Rotina da tabela de usuários
REM -------------------------------------------------------------------------
Sub Usuarios

  Dim p_gestor, p_lotacao, p_localizacao, p_nome, p_Ordena, p_modulo, p_uf, p_ativo
  Dim w_troca
  Dim w_username
  Dim w_sq_pessoa
  Dim w_nome
  Dim w_email
  
  w_troca = Request("w_troca")

  p_localizacao      = uCase(Request("p_localizacao"))
  p_lotacao          = uCase(Request("p_lotacao"))
  p_nome             = uCase(Request("p_nome"))
  p_gestor           = uCase(Request("p_gestor"))
  p_modulo           = uCase(Request("p_ordena"))
  p_ordena           = uCase(Request("p_ordena"))
  p_ativo            = uCase(Request("p_ativo"))
  
  If O = "L" Then
     DB_GetUserList RS, w_cliente, p_localizacao, p_lotacao, p_gestor, p_nome, p_modulo, p_uf, p_ativo
     w_filter = ""
     If Nvl(Session("codigo"),"00") <> "00" Then
        w_filter = w_filter & "  and codigo = '" & Session("codigo") & "'"
     End If
     If p_localizacao & p_lotacao & p_gestor & p_nome > "" Then
        If p_localizacao > ""  Then w_filter = w_filter & "  and sq_localizacao   = " & p_localizacao & " " End If
        If p_lotacao     > ""  Then w_filter = w_filter & "  and sq_unidade       = " & p_lotacao & " "     End If
        If p_gestor      > ""  Then w_filter = w_filter & "  and gestor_seguranca = '" & p_gestor & "' "    End If
        If p_nome        > ""  Then w_filter = w_filter & "  and nome             like '*" & p_nome & "*'"  End If
     End If
     If w_filter > "" Then
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "nome_indice" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "Javascript"
  ValidateOpen "Validacao"
  ValidateClose
  ScriptClose
  ScriptOpen "JavaScript"
  ShowHTML "function janela(p_sq_pessoa, p_username) {"
  ShowHTML "  window.open('Seguranca.asp?par=ACESSOS&R=" & w_dir & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&w_sq_pessoa='+p_sq_pessoa+'&w_username='+p_username+'&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOS&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & "','Gestao','width=630 height=500 top=30 left=30 status=yes resizable=yes toolbar=yes');"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_username.focus();'"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_localizacao.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    ShowHTML "                         <a accesskey=""N"" class=""SS"" href=""" & w_dir & "pessoa.asp?par=BENEF&R=" & w_dir & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u>N</u>ovo acesso</a>&nbsp;"
    If p_localizacao & p_lotacao & p_nome & p_gestor & p_ativo > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Username","username") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome_resumido") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Regional","lotacao") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ramal","ramal") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Perfil","vinculo") & "</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("username") & "</td>"
        ShowHTML "        <td align=""left"" title=""" & RS("nome") & """><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("lotacao") & "&nbsp;(" & RS("localizacao") & ")</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & RS("ramal") & "</td>"
        ShowHTML "        <td align=""left"" title=""" & RS("vinculo") & """><font size=""1"">" & RS("vinculo") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & "pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=A&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """ title=""Altera as informações cadastrais do usuário"">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & "pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=E&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """ title=""Exclui o usuário do banco de dados"">Excluir</A>&nbsp"
        If RS("ativo") = "S" Then
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & "pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=D&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """ title=""Bloqueia o acesso do usuário ao sistema"">Bloquear</A>&nbsp"
        Else
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & "pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=T&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """ title=""Ativa o acesso do usuário ao sistema"">Ativar</A>&nbsp"
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""L"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    
    ShowHTML "      <tr>"
    SelecaoLocalizacao "<b>Lo<U>c</U>alização:", "C", null, p_localizacao, null, "p_localizacao", null
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr>"
    ShowHTML "            <td><font size=""1""><b><u>R</u>egional de Ensino:</b><br><SELECT ACCESSKEY=""R"" CLASS=""STI"" NAME=""p_lotacao"">"
    DB_GetUorgList RS, w_cliente, null, null
    If Nvl(Session("codigo"),"00") = "00" Then
       RS.Filter = "informal='N' and codigo <> '00'"
       ShowHTML "          <option value=""00"">Todas"
    Else
       RS.Filter = "informal='N' and codigo = '" & Session("codigo") & "'"
    End If
    RS.Sort = "codigo"
    While Not RS.EOF
       ShowHTML "          <option value=""" & RS("sq_unidade") & """>" & RS("nome")
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Usuários:</b><br>"
    If Nvl(p_ativo,"S") = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S"" checked> Apenas ativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Apenas inativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    ElseIf p_ativo = "N" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Apenas ativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N"" checked> Apenas inativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Apenas ativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Apenas inativos<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          <td><font size=""1""><b>Gestores:</b><br>"
    If p_gestor  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""S""> Apenas gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""N""> Apenas não gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value="""" checked> Tanto faz"
    ElseIf p_gestor = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""S"" checked> Apenas gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""N""> Apenas não gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""S""> Apenas gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""N"" checked> Apenas não gestores<br><input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_gestor"" value=""""> Tanto faz"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="LOCALIZACAO" Then
       ShowHTML "          <option value=""localizacao"" SELECTED>Localização<option value=""sigla"">Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="SQ_UNIDADE_LOTACAO" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"" SELECTED>Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="USERNAME" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"">Lotação<option value="""">Nome<option value=""username"" SELECTED>Username"
    Else
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"">Lotação<option value="""" SELECTED>Nome<option value=""username"">Username"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_username             = Nothing
  Set w_sq_pessoa            = Nothing
  Set w_troca                = Nothing
  Set w_nome                 = Nothing
  Set w_email                = Nothing
  Set p_localizacao          = Nothing
  Set p_lotacao              = Nothing
  Set p_gestor               = Nothing
  Set p_nome                 = Nothing
  Set p_ordena               = Nothing
  Set p_modulo               = Nothing
  Set p_ativo                = Nothing
  Set p_uf                   = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_Chave

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
  End Select

  Set w_Chave               = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "USUARIOS"
       Usuarios
    Case "GRAVA"
       Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

