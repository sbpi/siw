<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DB_EO.asp" -->
<!-- #INCLUDE FILE="DML_Seguranca.asp" -->
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS4
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_classe, w_cliente, w_menu
Dim w_Assinatura, w_cor, w_filter
Dim p_gestor, p_lotacao, p_localizacao, p_nome, p_ordena
Dim w_dir, w_dir_volta, w_submenu
Private Par
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = nvl(Request("P1"),0)
P2           = nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "seguranca.asp?par="
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
If (SG="CLMENU" or SG="MENU") and Request("p_modulo") = "" and Request("p_menu") = "" Then O = "P" End If

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
w_menu    = RetornaMenu(w_cliente, SG) 
  
Main

FechaSessao

Set w_dir         = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set w_cliente     = Nothing
Set w_menu        = Nothing
Set p_localizacao = Nothing
Set p_lotacao     = Nothing
Set p_gestor      = Nothing
Set p_nome        = Nothing
Set p_ordena      = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set RS3           = Nothing
Set RS4           = Nothing
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
  Dim w_libera_edicao
  
  w_troca = Request("w_troca")

  p_localizacao      = uCase(Request("p_localizacao"))
  p_lotacao          = uCase(Request("p_lotacao"))
  p_nome             = uCase(Request("p_nome"))
  p_gestor           = uCase(Request("p_gestor"))
  p_ordena           = uCase(Request("p_ordena"))
  p_uf               = uCase(Request("p_uf"))
  p_modulo           = uCase(Request("p_modulo"))
  p_ativo            = uCase(Request("p_ativo"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "L" Then
     DB_GetUserList RS, w_cliente, p_localizacao, p_lotacao, p_gestor, p_nome, p_modulo, p_uf, p_ativo
     If p_ordena > "" Then RS.sort = p_ordena & ", nome_indice" Else RS.sort = "nome_resumido_ind" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "Javascript"
  ValidateOpen "Validacao"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
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
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_libera_edicao = "S" Then
       ShowHTML "                         <a accesskey=""N"" class=""ss"" href=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u>N</u>ovo acesso</a>&nbsp;"
    End If
    If p_localizacao & p_lotacao & p_nome & p_gestor & p_ativo > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_localizacao=" & p_localizacao & "&p_lotacao=" & p_lotacao & "&p_gestor=" & p_gestor & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Username","username") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome_resumido") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Lotação","lotacao") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ramal","ramal") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Vínculo","vinculo") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("username") & ""
        Else
           ShowHTML "        <td align=""center"" nowrap><font color=""#BC3131"" size=""1""><b>" & RS("username") & "</b>"
        End If
        ShowHTML "        <td align=""left"" title=""" & RS("nome") & """><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("lotacao") & "&nbsp;(" & RS("localizacao") & ")</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(RS("ramal"),"---") & "</td>"
        ShowHTML "        <td align=""left"" title=""" & RS("vinculo") & """><font size=""1"">" & Nvl(RS("vinculo"),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If w_libera_edicao = "S" Then
           ShowHTML "          <A class=""hl"" HREF=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=A&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do usuário"">Alterar</A>&nbsp"
           ShowHTML "          <A class=""hl"" HREF=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=E&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclui o usuário do banco de dados"">Excluir</A>&nbsp"
           If RS("ativo") = "S" Then
              ShowHTML "          <A class=""hl"" HREF=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=D&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Bloqueia o acesso do usuário ao sistema"">Bloquear</A>&nbsp"
           Else
              ShowHTML "          <A class=""hl"" HREF=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=T&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Ativa o acesso do usuário ao sistema"">Ativar</A>&nbsp"
           End If
        End If
        ShowHTML "          <A class=""hl"" HREF=""#"" onClick=""window.open('Seguranca.asp?par=ACESSOS&R=" & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOS" & MontaFiltro("GET") & "','Gestao','width=630,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=yes');"" title=""Gestão de módulos"">Gestão</A>&nbsp"
        If w_libera_edicao = "S" Then
           ShowHTML "          <A class=""hl"" HREF=""#"" onClick="" if (confirm('Este procedimento irá reinicializar a senha de acesso e sua assinatura eletrônica do usuário.\nConfirma?')) window.open('" & w_pagina & "NovaSenha&R=" & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOS" & MontaFiltro("GET") & "','NovaSenha','width=630,height=500,top=30,left=30,status=yes,resizable=yes,toolbar=yes');"" title=""Reinicializa a senha do usuário"">Senha</A>&nbsp"
        End If
        ShowHTML "          <A class=""hl"" HREF=""#"" onClick=""window.open('Seguranca.asp?par=VISAO&R=" & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=VISAO" & MontaFiltro("GET") & "','Gestao','width=630,height=500,top=30,left=30,status=yes,resizable=yes,toolbar=yes,scrollbars=yes');"" title=""Gestão de módulos"">Visão</A>&nbsp"
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
    AbreForm "Form", w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,1,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center""><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    
    ShowHTML "      <tr>"
    SelecaoLocalizacao "Lo<U>c</U>alização:", "C", null, p_localizacao, null, "p_localizacao", null
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr>"
    SelecaoUnidade "<U>L</U>otação:", "L", null, p_lotacao, null, "p_lotacao", null, null
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr><td><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr>"
    DB_GetCustomerData RS1, w_cliente
    SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, RS1("sq_pais"), "N", "p_uf", null, null
    RS1.Close
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Usuários:</b><br>"
    If Nvl(p_ativo,"S") = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S"" checked> Apenas ativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Apenas inativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    ElseIf p_ativo = "N" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Apenas ativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N"" checked> Apenas inativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Apenas ativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Apenas inativos<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          <td><font size=""1""><b>Gestores:</b><br>"
    If p_gestor  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""S""> Apenas gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""N""> Apenas não gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value="""" checked> Tanto faz"
    ElseIf p_gestor = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""S"" checked> Apenas gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""N""> Apenas não gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""S""> Apenas gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""N"" checked> Apenas não gestores<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""p_gestor"" value=""""> Tanto faz"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="LOCALIZACAO" Then
       ShowHTML "          <option value=""localizacao"" SELECTED>Localização<option value=""lotacao"">Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="SQ_UNIDADE_LOTACAO" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""lotacao"" SELECTED>Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="USERNAME" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""lotacao"">Lotação<option value="""">Nome<option value=""username"" SELECTED>Username"
    Else
       ShowHTML "          <option value=""localizacao"">Localização<option value=""lotacao"">Lotação<option value="""" SELECTED>Nome<option value=""username"">Username"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If w_libera_edicao = "S" Then
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Novo acesso"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
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
REM Rotina de manipulação do menu
REM -------------------------------------------------------------------------
Sub Menu
  Dim w_texto
  Dim w_sq_pessoa
  Dim w_username
  Dim w_troca
  Dim w_nome
  Dim w_ContOut
  Dim w_Titulo
  Dim w_Imagem
  Dim w_ImagemPadrao
  Dim RS1, RS2, RS3
  Dim w_marcado
  Dim w_null
  
  Dim w_sq_menu, w_sq_menu_pai, w_descricao, w_link, w_sq_servico
  Dim w_tramite, w_ordem, w_ultimo_nivel, w_p1, w_p2, w_p3, w_p4
  Dim w_sigla, w_ativo, w_acesso_geral, w_modulo, w_descentralizado, w_externo, w_target
  Dim w_sq_unidade_executora, w_como_funciona, w_controla_ano, w_libera_edicao
  Dim w_emite_os, w_consulta_opiniao, w_acompanha_fases, w_envia_email, w_exibe_relatorio
  Dim w_vinculacao, w_finalidade, w_workflow, w_arquivo_procedimentos, w_data_hora, w_envia_dia_util
  Dim w_pede_descricao, w_pede_justificativa, w_envio
  Dim w_heranca
  
  Dim p_sq_endereco_unidade, p_modulo, p_menu
  
  p_sq_endereco_unidade  = Request("p_sq_endereco_unidade")
  p_modulo               = Request("p_modulo")
  p_menu                 = Request("p_menu")
  
  w_ImagemPadrao         = "images/folder/SheetLittle.gif"
  w_troca                = Request("w_troca")
  w_heranca              = Request("w_heranca")
  
  w_sq_menu            = Request("w_sq_menu")
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If O <> "L" Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If O <> "P" and O <> "H" Then
        If w_heranca > "" or (O <> "I" and w_troca = "") Then
        
           ' Se for herança, atribui a chave da opção selecionada para w_sq_menu
           If w_heranca > "" Then w_sq_menu = w_heranca End If
        
           DB_GetMenuData RS, w_sq_menu
           w_sq_menu_pai              = RS("sq_menu_pai")
           w_descricao                = RS("nome")
           w_link                     = RS("link")
           w_imagem                   = RS("imagem")
           w_tramite                  = RS("tramite")
           w_ordem                    = RS("ordem")
           w_ultimo_nivel             = RS("ultimo_nivel")
           w_p1                       = RS("p1")
           w_p2                       = RS("p2")
           w_p3                       = RS("p3")
           w_p4                       = RS("p4")
           w_ativo                    = RS("ativo")
           w_envio                    = RS("destinatario")
           w_acesso_geral             = RS("acesso_geral")
           w_modulo                   = RS("sq_modulo")
           w_descentralizado          = RS("descentralizado")
           w_externo                  = RS("externo")
           w_target                   = RS("target")
           w_finalidade               = RS("finalidade")
           w_emite_os                 = RS("emite_os")
           w_consulta_opiniao         = RS("consulta_opiniao")
           w_acompanha_fases          = RS("acompanha_fases")
           w_envia_email              = RS("envia_email")
           w_exibe_relatorio          = RS("exibe_relatorio")
           w_como_funciona            = RS("como_funciona")
           w_controla_ano             = RS("controla_ano")
           w_libera_edicao            = RS("libera_edicao")
           w_arquivo_procedimentos    = RS("arquivo_proced")
           w_sq_unidade_executora     = RS("sq_unid_executora")
           w_vinculacao               = RS("vinculacao")
           w_envia_dia_util           = RS("envia_dia_util")
           w_data_hora                = RS("data_hora")
           w_pede_descricao           = RS("descricao")
           w_pede_justificativa       = RS("justificativa")
           w_sigla                    = RS("sigla")
        
           DesconectaBD
        ElseIf w_troca > "" Then
           w_sq_menu_pai              = Request("w_sq_menu_pai")
           w_sq_servico               = Request("w_sq_servico")
           w_descricao                = Request("w_descricao")
           w_link                     = Request("w_link")
           w_imagem                   = Request("w_imagem")
           w_tramite                  = Request("w_tramite")
           w_ordem                    = Request("w_ordem")
           w_ultimo_nivel             = Request("w_ultimo_nivel")
           w_cliente                  = Request("w_cliente")
           w_p1                       = Request("w_p1")
           w_p2                       = Request("w_p2")
           w_p3                       = Request("w_p3")
           w_p4                       = Request("w_p4")
           w_sigla                    = Request("w_sigla")
           w_ativo                    = Request("w_ativo")
           w_envio                    = Request("w_envio")
           w_acesso_geral             = Request("w_acesso_geral")
           w_modulo                   = Request("w_modulo")
           w_descentralizado          = Request("w_descentralizado")
           w_externo                  = Request("w_externo")
           w_target                   = Request("w_target")
           w_finalidade               = Request("w_finalidade")
           w_emite_os                 = Request("w_emite_os")
           w_consulta_opiniao         = Request("w_consulta_opiniao")
           w_acompanha_fases          = Request("w_acompanha_fases")
           w_envia_email              = Request("w_envia_email")
           w_exibe_relatorio          = Request("w_exibe_relatorio")
           w_como_funciona            = Request("w_como_funciona")
           w_controla_ano             = Request("w_controla_ano")
           w_libera_edicao             = Request("w_libera_edicao")
           w_arquivo_procedimentos    = Request("w_arquivo_procedimentos")
           w_sq_unidade_executora     = Request("w_sq_unidade_executora")
           w_vinculacao               = Request("w_vinculacao")
           w_data_hora                = Request("w_data_hora")
           w_envia_dia_util           = Request("w_envia_dia_util")
           w_pede_descricao           = Request("w_pede_descricao")
           w_pede_justificativa       = Request("w_pede_justificativa")
        End If
        If O = "I" or O = "A" Then
           Validate "w_descricao", "Descrição", "1", "1", "2", "40", "1", "1"
           ShowHTML "  if (theForm.w_externo[0].checked && theForm.w_tramite[0].checked) { "
           ShowHTML "     alert('Opções que apontem para links externos não podem ter vinculação a serviço.\nVerifique os campos \""Link externo\"" e \""Vinculada a serviço\""!'); "
           ShowHTML "     return false; "
           ShowHTML "  }"
           Validate "w_link", "Link", "1", "", "5", "60", "1", "1"
           Validate "w_target", "Target", "1", "", "1", "15", "1", "1"
           Validate "w_imagem", "Imagem", "1", "", "5", "60", "1", "1"
           Validate "w_ordem", "Ordem", "1", "1", "1", "6", "", "0123456789"
           Validate "w_finalidade", "Finalidade", "1", "1", "4", "200", "1", "1"
           Validate "w_modulo", "Módulo", "SELECT", "1", "1", "10", "", "0123456789"
           ShowHTML "  if (theForm.w_tramite[0].checked && theForm.w_sigla.value == '') { "
           ShowHTML "     alert('Opções vinculadas a serviço devem ter, obrigatoriamente, sigla informada.\nVerifique os campos \""Sigla\"" e \""Vinculada a serviço\""!'); "
           ShowHTML "     theForm.w_sigla.focus(); "
           ShowHTML "     return false; "
           ShowHTML "  }"
           Validate "w_sigla", "Sigla", "1", "", "4", "10", "1", "1"
           Validate "w_p1", "P1", "1", "", "1", "18", "", "0123456789"
           Validate "w_p2", "P2", "1", "", "1", "18", "", "0123456789"
           Validate "w_p3", "P3", "1", "", "1", "18", "", "0123456789"
           Validate "w_p4", "P4", "1", "", "1", "18", "", "0123456789"
           ShowHTML "  if (theForm.w_tramite[0].checked) { "
           Validate "w_sq_unidade_executora", "Unidade executora", "HIDDEN", "1", "1", "10", "", "0123456789"
           Validate "w_como_funciona", "Como funciona", "", "1", "10", "1000", "1", "1"
           ShowHTML "  }"
        End If
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "H" Then
        Validate "w_heranca", "Origem dos dados", "SELECT", "1", "1", "10", "", "1"
        ShowHTML "  if (confirm('Confirma herança dos dados da opção selecionada?')) {"
        ShowHTML "     window.close(); "
        ShowHTML "     opener.focus(); "
        ShowHTML "     return true; "
        ShowHTML "  } "
        ShowHTML "  else { return false; } "
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ShowHTML "function servico() {"
     ShowHTML "  if (document.Form.w_tramite[1].checked) {"
     ShowHTML "     document.Form.w_sq_unidade_executora.selectedIndex=0;"
     ShowHTML "     document.Form.w_emite_os[0].checked=false;"
     ShowHTML "     document.Form.w_emite_os[1].checked=false;"
     ShowHTML "     document.Form.w_envio[0].checked=false;"
     ShowHTML "     document.Form.w_envio[1].checked=false;"
     ShowHTML "     document.Form.w_consulta_opiniao[0].checked=false;"
     ShowHTML "     document.Form.w_consulta_opiniao[1].checked=false;"
     ShowHTML "     document.Form.w_envia_email[0].checked=false;"
     ShowHTML "     document.Form.w_envia_email[1].checked=false;"
     ShowHTML "     document.Form.w_exibe_relatorio[0].checked=false;"
     ShowHTML "     document.Form.w_exibe_relatorio[1].checked=false;"
     ShowHTML "     document.Form.w_vinculacao[0].checked=false;"
     ShowHTML "     document.Form.w_vinculacao[1].checked=false;"
     ShowHTML "     document.Form.w_data_hora[0].checked=false;"
     ShowHTML "     document.Form.w_data_hora[1].checked=false;"
     ShowHTML "     document.Form.w_data_hora[2].checked=false;"
     ShowHTML "     document.Form.w_data_hora[3].checked=false;"
     ShowHTML "     document.Form.w_data_hora[4].checked=false;"
     ShowHTML "     document.Form.w_envia_dia_util[0].checked=false;"
     ShowHTML "     document.Form.w_envia_dia_util[1].checked=false;"
     ShowHTML "     document.Form.w_pede_descricao[0].checked=false;"
     ShowHTML "     document.Form.w_pede_descricao[1].checked=false;"
     ShowHTML "     document.Form.w_pede_justificativa[0].checked=false;"
     ShowHTML "     document.Form.w_pede_justificativa[1].checked=false;"
     ShowHTML "     document.Form.w_como_funciona.value='';"
     ShowHTML "     document.Form.w_controla_ano[0].checked=false;"
     ShowHTML "     document.Form.w_controla_ano[1].checked=false;"
     ShowHTML "     document.Form.w_sq_unidade_executora.disabled=true;"
     ShowHTML "     document.Form.w_emite_os[0].disabled=true;"
     ShowHTML "     document.Form.w_emite_os[1].disabled=true;"
     ShowHTML "     document.Form.w_envio[0].disabled=true;"
     ShowHTML "     document.Form.w_envio[1].disabled=true;"
     ShowHTML "     document.Form.w_consulta_opiniao[0].disabled=true;"
     ShowHTML "     document.Form.w_consulta_opiniao[1].disabled=true;"
     ShowHTML "     document.Form.w_envia_email[0].disabled=true;"
     ShowHTML "     document.Form.w_envia_email[1].disabled=true;"
     ShowHTML "     document.Form.w_exibe_relatorio[0].disabled=true;"
     ShowHTML "     document.Form.w_exibe_relatorio[1].disabled=true;"
     ShowHTML "     document.Form.w_vinculacao[0].disabled=true;"
     ShowHTML "     document.Form.w_vinculacao[1].disabled=true;"
     ShowHTML "     document.Form.w_data_hora[0].disabled=true;"
     ShowHTML "     document.Form.w_data_hora[1].disabled=true;"
     ShowHTML "     document.Form.w_data_hora[2].disabled=true;"
     ShowHTML "     document.Form.w_data_hora[3].disabled=true;"
     ShowHTML "     document.Form.w_data_hora[4].disabled=true;"
     ShowHTML "     document.Form.w_envia_dia_util[0].disabled=true;"
     ShowHTML "     document.Form.w_envia_dia_util[1].disabled=true;"
     ShowHTML "     document.Form.w_pede_descricao[0].disabled=true;"
     ShowHTML "     document.Form.w_pede_descricao[1].disabled=true;"
     ShowHTML "     document.Form.w_pede_justificativa[0].disabled=true;"
     ShowHTML "     document.Form.w_pede_justificativa[1].disabled=true;"
     ShowHTML "     document.Form.w_controla_ano[0].disabled=true;"
     ShowHTML "     document.Form.w_controla_ano[1].disabled=true;"     
     ShowHTML "     document.Form.w_como_funciona.disabled=true;"
     ShowHTML "  }"
     ShowHTML "  else if (document.Form.w_tramite[0].checked && document.Form.w_emite_os[0].disabled) {"
     ShowHTML "     document.Form.w_sq_unidade_executora.disabled=false;"
     ShowHTML "     document.Form.w_emite_os[0].disabled=false;"
     ShowHTML "     document.Form.w_emite_os[1].disabled=false;"
     ShowHTML "     document.Form.w_envio[0].disabled=false;"
     ShowHTML "     document.Form.w_envio[1].disabled=false;"
     ShowHTML "     document.Form.w_consulta_opiniao[0].disabled=false;"
     ShowHTML "     document.Form.w_consulta_opiniao[1].disabled=false;"
     ShowHTML "     document.Form.w_envia_email[0].disabled=false;"
     ShowHTML "     document.Form.w_envia_email[1].disabled=false;"
     ShowHTML "     document.Form.w_exibe_relatorio[0].disabled=false;"
     ShowHTML "     document.Form.w_exibe_relatorio[1].disabled=false;"
     ShowHTML "     document.Form.w_vinculacao[0].disabled=false;"
     ShowHTML "     document.Form.w_vinculacao[1].disabled=false;"
     ShowHTML "     document.Form.w_data_hora[0].disabled=false;"
     ShowHTML "     document.Form.w_data_hora[1].disabled=false;"
     ShowHTML "     document.Form.w_data_hora[2].disabled=false;"
     ShowHTML "     document.Form.w_data_hora[3].disabled=false;"
     ShowHTML "     document.Form.w_data_hora[4].disabled=false;"
     ShowHTML "     document.Form.w_envia_dia_util[0].disabled=false;"
     ShowHTML "     document.Form.w_envia_dia_util[1].disabled=false;"
     ShowHTML "     document.Form.w_pede_descricao[0].disabled=false;"
     ShowHTML "     document.Form.w_pede_descricao[1].disabled=false;"
     ShowHTML "     document.Form.w_pede_justificativa[0].disabled=false;"
     ShowHTML "     document.Form.w_pede_justificativa[1].disabled=false;"
     ShowHTML "     document.Form.w_como_funciona.disabled=false;"
     ShowHTML "     document.Form.w_controla_ano[0].disabled=false;"
     ShowHTML "     document.Form.w_controla_ano[1].disabled=false;"
     ShowHTML "     document.Form.w_sq_unidade_executora.selectedIndex=0;"
     ShowHTML "     document.Form.w_emite_os[1].checked=true;"
     ShowHTML "     document.Form.w_envio[0].checked=true;"
     ShowHTML "     document.Form.w_consulta_opiniao[1].checked=true;"
     ShowHTML "     document.Form.w_envia_email[1].checked=true;"
     ShowHTML "     document.Form.w_exibe_relatorio[1].checked=true;"
     ShowHTML "     document.Form.w_vinculacao[1].checked=true;"
     ShowHTML "     document.Form.w_data_hora[2].checked=true;"
     ShowHTML "     document.Form.w_envia_dia_util[0].checked=true;"
     ShowHTML "     document.Form.w_pede_descricao[0].checked=true;"
     ShowHTML "     document.Form.w_pede_justificativa[0].checked=true;"
     ShowHTML "     document.Form.w_como_funciona.value='';"
     ShowHTML "     document.Form.w_controla_ano[1].checked=true;"
     ShowHTML "  }"
     ShowHTML "}"
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh {text-decoration:none;font:Arial;color=""#FF0000""}"
  ShowHTML " .lh:HOVER {text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_descricao.focus();"
  ElseIf O = "H" Then
     BodyOpen "onLoad=document.Form.w_heranca.focus();"
  ElseIf O = "P" Then
     BodyOpen "onLoad=document.Form.p_sq_endereco_unidade.focus();"
  ElseIf O = "L" Then
     BodyOpen "onLoad=document.focus();"
  Else
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  End If
  If O <> "H" Then
     Estrutura_Topo_Limpo
     Estrutura_Menu
     Estrutura_Corpo_Abre
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""99%"" border=""0"">"
  If O = "L" Then
     ShowHTML "      <tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
     ' Trata a cor e o texto da string Filtrar, dependendo do filtro estar ativo ou não
     If p_sq_endereco_unidade & p_modulo & p_menu > "" Then
        ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """><font color=""#BC5100""><u>F</u>iltrar (Ativo)</font></a>"
     Else
        ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
     End If
     ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td><font size=2><b>"

     DB_GetMenuLink RS, w_cliente, p_sq_endereco_unidade, p_modulo, nvl(p_menu,"IS NULL")
     w_ContOut = 0
     While Not RS.EOF
        w_Titulo = RS("nome")
        w_ContOut = w_ContOut + 1
        If cDbl(RS("Filho")) > 0 Then
           ShowHTML "<A HREF=#""" & RS("sq_menu") & """></A>"
           ShowHTML "<font size=2><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS("nome") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
           ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
           If RS("ultimo_nivel") <> "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
              If RS("tramite") = "S" Then 
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
              Else
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&w_sq_menu=" & RS("sq_menu") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
              End If
           End If
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
           ShowHTML "       </div></span></font></font>"
           ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
           DB_GetMenuLink RS1, w_cliente, p_sq_endereco_unidade, null, RS("sq_menu")
           While Not RS1.EOF
              w_Titulo = w_Titulo & " - " & RS1("nome")
              If cDbl(RS1("Filho")) > 0 Then
                 w_ContOut = w_ContOut + 1
                 ShowHTML "<A HREF=#""" & RS1("sq_menu") & """></A>"
                 ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS1("nome") & "<font size=1>"
                 If RS1("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
                 ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                 If RS1("ultimo_nivel") <> "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
                    If RS1("tramite") = "S" Then 
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
                    Else
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
                    End If
                 End If
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
                 ShowHTML "       </div></span></font></font>"
                 ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                 DB_GetMenuLink RS2, w_cliente, p_sq_endereco_unidade, null, RS1("sq_menu")
                 While Not RS2.EOF
                    w_Titulo = w_Titulo & " - " & RS2("nome")
                    If cDbl(RS2("Filho")) > 0 Then
                       w_ContOut = w_ContOut + 1
                       ShowHTML "<A HREF=#""" & RS2("sq_menu") & """></A>"
                       ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS2("nome") & "<font size=1>"
                       If RS2("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
                       ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                       If RS2("ultimo_nivel") <> "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
                          If RS2("tramite") = "S" Then 
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
                          Else
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
                          End If
                       End If
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
                       ShowHTML "       </div></span></font></font>"
                       ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                       DB_GetMenuLink RS3, w_cliente, p_sq_endereco_unidade, null, RS2("sq_menu")
                       While Not RS3.EOF
                          w_Titulo = w_Titulo & " - " & RS3("nome")
                          If RS3("IMAGEM") > "" Then
                             w_Imagem = RS3("IMAGEM")
                          Else
                             w_Imagem = w_ImagemPadrao
                          End If
                          ShowHTML "<A HREF=#""" & RS3("sq_menu") & """></A>"
                          ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS3("nome")
                          If RS3("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
                          ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                          If RS3("ultimo_nivel") <> "S" Then
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS3("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS3("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
                             If RS3("tramite") = "S" Then 
                                ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS3("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
                             Else
                                ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS3("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
                             End If
                          End If
                          If RS3("ativo") = "S" Then
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                          Else
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                          End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS3("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
                          ShowHTML "    <BR>"
                          w_Titulo = Replace(w_Titulo, " - "&RS3("nome"), "")
                          RS3.MoveNext
                       Wend
                       ShowHTML "   </font></div>"
                    Else
                       If RS2("IMAGEM") > "" Then
                          w_Imagem = RS2("IMAGEM")
                       Else
                          w_Imagem = w_ImagemPadrao
                       End If
                       ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS2("nome")
                       If RS2("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
                       ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                       If RS2("ultimo_nivel") <> "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
                          If RS2("tramite") = "S" Then 
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
                          Else
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS2("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
                          End If
                       End If
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS2("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
                       ShowHTML "    <BR>"
                    End If
                    w_Titulo = Replace(w_Titulo, " - "&RS2("nome"), "")
                    RS2.MoveNext
                 Wend
                 ShowHTML "   </font></div>"
              Else
                 If RS1("IMAGEM") > "" Then
                    w_Imagem = RS1("IMAGEM")
                 Else
                    w_Imagem = w_ImagemPadrao
                 End If
                 ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS1("nome")
                 If RS1("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
                 ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
                 If RS1("ultimo_nivel") <> "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
                    If RS1("tramite") = "S" Then 
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
                    Else
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS1("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
                    End If
                 End If
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS1("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
                 ShowHTML "    <BR>"
              End If
              w_Titulo = Replace(w_Titulo, " - "&RS1("nome"), "")
              RS1.MoveNext
           Wend
           ShowHTML "   </font></div>"
        Else
           If RS("IMAGEM") > "" Then
              w_Imagem = RS("IMAGEM")
           Else
              w_Imagem = w_ImagemPadrao
           End If
           ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""><font size=2> " & RS("nome") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Altera as informações desta opção do menu"">Alterar</A>&nbsp"
           ' A configuração de endereços e serviço/acessos não estão disponíveis para sub-menus
           If RS("ultimo_nivel") <> "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Endereco&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS("sq_menu") & "&TP=" & TP & " - Endereços" & "&SG=ENDERECO','endereco','top=10,left=10,width=780,height=500,toolbar=no,status=no,scrollbars=yes,resizable=yes');"" title=""Indica quais endereços terão esta opção no menu. A princípio, todas as opções do menu aparecem para os usuários de todos os endereços."">Endereços</A>&nbsp"
              If RS("tramite") = "S" Then 
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=Tramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Trâmites" & "&SG=SIWTRAMITE" & MontaFiltro("GET") & "','Tramite','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura os trâmites vinculados a esta opção."">Trâmites</A>&nbsp" 
              Else
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""#" & RS("sq_menu") & """ onClick=""window.open('seguranca1.asp?par=AcessoMenu&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOMENU','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
              End If
           End If
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Impede que esta opção apareça no menu"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & RS("sq_menu") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=MENU" & MontaFiltro("GET") & """ title=""Exclui o link do menu"">Excluir</A>&nbsp"
           ShowHTML "    <BR>"
        End If
        RS.MoveNext
     Wend
     If w_contOut = 0 Then ' Se não achou registros
        ShowHTML "<font size=2>Não foram encontrados registros."
     End If
  ElseIf O <> "P" and O <> "H" Then
     If O <> "I" and O <> "A" Then w_Disabled = "disabled" End If
     ' Se for inclusão de nova opção, permite a herança dos dados de outra, já existente.
     If O = "I" Then
        ShowHTML "      <tr><td><font size=""2""><a accesskey=""H"" class=""ss"" href=""#"" onClick=""window.open('" & w_pagina & par & "&R=" & w_Pagina & "MENU&O=H&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu="&w_sq_menu&"" & MontaFiltro("GET") & "','heranca','top=70,left=10,width=780,height=200,toolbar=no,status=no,scrollbars=no');""><u>H</u>erdar dados de outra opção</a>&nbsp;"
        ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     End If
     AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
     ShowHTML MontaFiltro("POST")
     ShowHTML "      <tr><td><table width=""100%"" border=0>"
     ShowHTML "          <tr><td colspan=4 height=""30""><font size=""2""><b>Identificação</td>"
     ShowHTML "          <tr><td width=""5%"">"
     ShowHTML "              <td align=""left""><font size=""1""><b><u>D</u>escrição:<br><INPUT ACCESSKEY=""D"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_descricao"" SIZE=40 MAXLENGTH=40 VALUE=""" & w_descricao & """ " & w_Disabled & " title=""Nome a ser apresentado no menu.""></td>"
     SelecaoMenu "<u>S</u>ubordinação:", "S", "Se esta opção estiver subordinada a outra já existente, informe qual.", w_sq_menu_pai, w_sq_menu, "w_sq_menu_pai", "Pesquisa", "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_link'; document.Form.submit();"""
     ShowHTML "              <td title=""Existem formulários com várias telas. Neste caso você pode criar sub-menus. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Sub-menu?</b><br>"
     If w_ultimo_nivel = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ultimo_nivel"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ultimo_nivel"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ultimo_nivel"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ultimo_nivel"" value=""N"" checked> Não"    
     End If
     ShowHTML "          <tr><td width=""5%"">"
     ShowHTML "              <td><font size=""1""><b><u>L</u>ink:<br><INPUT ACCESSKEY=""L"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_link"" SIZE=40 MAXLENGTH=60 VALUE=""" & w_link & """ " & w_Disabled & " title=""Informe o link a ser chamado quando esta opção for clicada. Se esta opção tiver opções subordinadas, não informe este campo.""></td>"
     ShowHTML "              <td><font size=""1""><b><u>T</u>arget:<br><INPUT ACCESSKEY=""T"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_target"" SIZE=15 MAXLENGTH=15 VALUE=""" & w_target & """ " & w_Disabled & " title=""Se desejar que a opção seja aberta em outra janela, diferente do padrão, informe \'_blank\' ou o nome da janela desejada.""></td>"
     ShowHTML "              <td title=""Informe \'Sim\' para opções que chamarão links externos ao SIW. Links para sites de busca, de bancos etc são exemplos onde este campo deve ter valor \'Sim\'.""><font size=""1""><b>Link externo?</b><br>"
     If w_externo = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_externo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_externo"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_externo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_externo"" value=""N"" checked> Não"    
     End If
     ShowHTML "          <tr><td width=""5%"">"
     ShowHTML "              <td align=""left"" colspan=""2""><font size=""1""><b><u>I</u>magem:<br><INPUT ACCESSKEY=""I"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_imagem"" SIZE=60 MAXLENGTH=60 VALUE=""" & w_imagem & """ " & w_Disabled & " title=""O SIW apresenta ícones padrão na montagem do menu. Se desejar outro ícone, informe o caminho onde está localizado.""></td>"
     ' Recupera o número de ordem das outras opções irmãs à selecionada
     DB_GetMenuOrder RS, w_cliente, w_sq_menu_pai, null, null
     If Not RS.EOF Then
        w_texto = "<b>Nºs de ordem em uso para esta subordinação:</b>:<br>" & _
                  "<table border=1 width=100% cellpadding=0 cellspacing=0>" & _
                  "<tr><td align=center><b><font size=1>Ordem" & _
                  "    <td><b><font size=1>Descrição"
        While Not RS.EOF
           w_texto = w_texto & "<tr><td valign=top align=center><font size=1>" & RS("ordem") & "<td valign=top><font size=1>" & RS("nome")
           RS.MoveNext
        Wend
        w_texto = w_texto & "</table>"
     Else
        w_texto = "Não há outros números de ordem vinculados à subordinação desta opção"
     End If
     ShowHTML "              <td align=""left""><font size=""1""><b><u>O</u>rdem:<br><INPUT ACCESSKEY=""O"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_ordem"" SIZE=4 MAXLENGTH=4 VALUE=""" & w_ordem & """ " & w_Disabled & " TITLE=""" & Replace(w_texto,CHR(13)&CHR(10),"<BR>") & """></td>"
     ShowHTML "          <tr><td width=""5%"">"
     ShowHTML "              <td colspan=3><font size=""1""><b><U>F</U>inalidade:<br><TEXTAREA ACCESSKEY=""F"" class=""sti"" name=""w_finalidade"" rows=3 cols=80 title=""Descreva sucintamente a finalidade desta opção. Esta informação será apresentada quando o usuário passar o mouse em cima da opção, no menu."">" & w_finalidade & "</textarea></td>"
     
     ShowHTML "          <tr><td colspan=4 height=""30""><font size=""2""><b>Parâmetros de acesso</td>"
     ShowHTML "          <tr><td width=""5%""><td colspan=""3"" valign=""top""><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr>"
     SelecaoModulo "<u>M</u>ódulo:", "M", "Informe a que módulo do SIW esta opção está vinculada. Caso não esteja vinculado a nenhum, selecione \'Opções gerais\'.", w_modulo, w_cliente, "w_modulo", null, null

     ShowHTML "              <td title=""Opções de acesso geral aparecem para qualquer usuário, sem nenhuma restrição. \'Troca senha\' e \'Troca assinatura\' são exemplos onde este campo tem valor \'Sim\'.""><font size=""1""><b>Acesso geral?</b><br>"
     If w_acesso_geral = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_acesso_geral"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_acesso_geral"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_acesso_geral"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_acesso_geral"" value=""N"" checked> Não"    
     End If
     ShowHTML "              <td title=""Existem opções que estarão disponíveis para apenas alguns endereços da organização. Neste caso informe \'Sim\'.""><font size=""1""><b>Acesso descentralizado?</b><br>"
     If w_descentralizado = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_descentralizado"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_descentralizado"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_descentralizado"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_descentralizado"" value=""N"" checked> Não"    
     End If
     ShowHTML "              <td title=""Existem opções que não permitirão a inclusão, alteração e exclusão de registros. Neste caso informe \'Não\'.""><font size=""1""><b>Libera edição?</b><br>"
     If w_libera_edicao = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_libera_edicao"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_libera_edicao"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_libera_edicao"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_libera_edicao"" value=""N"" checked> Não"    
     End If
     ShowHTML "          </table>"

     ShowHTML "          <tr><td colspan=4 height=""30""><font size=""2""><b>Parâmetros de programação</td>"
     ShowHTML "          <tr><td width=""5%""><td colspan=""3"" valign=""top""><table border=""0"" cellpadding=""0"" cellspacing=""0""><tr>"
     ShowHTML "              <td width=""10%""><font size=""1""><b>Si<u>g</u>la:<br><INPUT ACCESSKEY=""G"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_sigla"" SIZE=10 MAXLENGTH=10 VALUE=""" & w_sigla & """ " & w_Disabled & " title=""Este campo é usado para implementar particularidades da opção no código-fonte. Não é possível informar a mesma sigla para duas opcões."">&nbsp;</td>"
     ShowHTML "              <td width=""5%""><font size=""1""><b>P<u>1</u>:<br><INPUT ACCESSKEY=""1"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_p1"" SIZE=6 MAXLENGTH=18 VALUE=""" & w_p1 & """ " & w_Disabled & " title=""Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções."">&nbsp;</td>"
     ShowHTML "              <td width=""5%""><font size=""1""><b>P<u>2</u>:<br><INPUT ACCESSKEY=""2"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_p2"" SIZE=6 MAXLENGTH=18 VALUE=""" & w_p2 & """ " & w_Disabled & " title=""Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções."">&nbsp;</td>"
     ShowHTML "              <td width=""5%""><font size=""1""><b>P<u>3</u>:<br><INPUT ACCESSKEY=""3"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_p3"" SIZE=6 MAXLENGTH=18 VALUE=""" & w_p3 & """ " & w_Disabled & " title=""Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções."">&nbsp;</td>"
     ShowHTML "              <td width=""5%""><font size=""1""><b>P<u>4</u>:<br><INPUT ACCESSKEY=""4"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_p4"" SIZE=6 MAXLENGTH=18 VALUE=""" & w_p4 & """ " & w_Disabled & " title=""Parâmetro de uso geral, usado para implementar particularidades da opção no código-fonte. Pode ser repetido em outras opções."">&nbsp;</td>"
     ShowHTML "              <td width=""20%"" title=""Se uma opção tem controle de tramitação (work-flow), informe \'Sim\' e preencha os dados referentes à \'Configuração do serviço\'. Caso contrário, informe \'Não\'.""><font size=""1""><b>Vinculada a serviço?</b><br>"
     If w_tramite = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_tramite"" value=""S"" checked onClick=""servico();""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_tramite"" value=""N"" onClick=""servico();""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_tramite"" value=""S"" onClick=""servico();""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_tramite"" value=""N"" checked onClick=""servico();""> Não"    
     End If
     ShowHTML "          </table>"
     'ShowHTML "          <tr><td width=""5%""><td colspan=""4""><font size=""1""><b><u>P</u>róximo link:<br><INPUT ACCESSKEY=""P"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_proximo_link"" SIZE=60 MAXLENGTH=60 VALUE=""" & w_proximo_link & """ " & w_Disabled & ">&nbsp;</td>"
     'ShowHTML "          <tr><td width=""5%""><td colspan=""4""><font size=""1""><b>A<u>n</u>terior link:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_anterior_link"" SIZE=60 MAXLENGTH=60 VALUE=""" & w_anterior_link & """ " & w_Disabled & ">&nbsp;</td>"

     ShowHTML "          <tr><td colspan=4 height=""30""><font size=""2""><b>Configuração do serviço<br></font><font size=1 color=""#FF0000"">(informe os campos abaixo apenas se o campo ""Vinculada a serviço"" for igual a ""Sim"")</font></td>"
     ShowHTML "          <tr><td width=""5%""><td colspan=""3"" valign=""top""><table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%""><tr>"
     ' Recupera a lista de unidades ativas
     SelecaoUnidade "<u>U</u>nidade responsável pela execução do serviço:", "U", "Informe a unidade organizacional responsável pela execução deste serviço. Se a organização tiver mais de um endereço e o serviço for descentralizado, informe a unidade responsável pela execução na sede.", w_sq_unidade_executora, null, "w_sq_unidade_executora", null, null
     ShowHTML "          </table>"
     ShowHTML "          <tr><td width=""5%""><td colspan=""3"" valign=""top""><table width=""100%"" border=""0"" cellpadding=""0"" cellspacing=""0""><tr align=""left"">"
     ShowHTML "              <td title=""Existem serviços que necessitam de uma Ordem de Serviço. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Emite OS?</b><br>"
     If w_emite_os = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""N""> Não"
     ElseIf w_emite_os = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_emite_os"" value=""N"" > Não"
     End If
     ShowHTML "              <td title=""Existem serviços que deseja-se a opinião do solicitante com relação ao atendimento. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Consulta opinião?</b><br>"
     If w_consulta_opiniao = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""N""> Não"
     ElseIf w_consulta_opiniao = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_consulta_opiniao"" value=""N"" > Não"    
     End If
     ShowHTML "              <td title=""Existem serviços que deseja-se o envio de e-mail a cada tramitação do atendimento. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Envia e-mail?</b><br>"
     If w_envia_email = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""N""> Não"
     ElseIf w_envia_email = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_email"" value=""N"" > Não"    
     End If
     ShowHTML "          <tr align=""left"">"
     ShowHTML "              <td title=""Existem serviços que deseja-se um resumo quantitativo periódico (atendimentos, opiniões, custos etc). Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Consta do relatório gerencial?</b><br>"
     If w_exibe_relatorio = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""N""> Não"
     ElseIf w_exibe_relatorio = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_exibe_relatorio"" value=""N"" > Não"    
     End If
     ShowHTML "              <td title=""Existem serviços que são vinculados à unidade (eletricista, transporte etc) e outros que são vinculados ao solicitante (adiantamentos salariais, férias etc). Se a vinculação for à unidade, usuários lotados na unidade do solicitante podem ver as solicitações; caso contrário, apenas o solicitante. Indique o tipo de vinculação deste serviço.""><font size=""1""><b>Tipo de vinculação:</b><br>"
     If w_vinculacao = "P" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""P"" checked> Solicitante <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""U""> Unidade"
     ElseIf w_vinculacao = "U" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""P""> Solicitante <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""U"" checked> Unidade"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""P""> Solicitante <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_vinculacao"" value=""U"" > Unidade"
     End If
     ShowHTML "              <td title=""Alguns serviços necessitam da indicação do destinatário e outros não. Se a indicação do destinatário for necessária, uma caixa com o nome das pessoas que podem receber a solicitação será apresentada sempre que for feito um encaminhamento.""><font size=""1""><b>Indica destinatário?</b><br>"
     If w_envio = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""N""> Não"
     ElseIf w_envio = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""N"" checked> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envio"" value=""N"" > Não"
     End If
     ShowHTML "          <tr><td colspan=3 title=""Existem serviços que exigem um controle de solicitações por ano. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Controla solicitações por ano?</b><br>"
     If w_controla_ano = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""N""> Não"
     ElseIf w_controla_ano = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_controla_ano"" value=""N"" > Não"    
     End If
     ShowHTML "          <tr align=""left"">"
     ShowHTML "              <td colspan=3 title=""Informe se esta opção pede data limite de atendimento e, se pedir, como a data deve ser informada.""><font size=""1""><b>Pede data limite?</b><br>"
     If w_data_hora = "0" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0"" checked> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1""> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2""> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3""> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4""> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     ElseIf w_data_hora = "1" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0""> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1"" checked> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2""> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3""> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4""> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     ElseIf w_data_hora = "2" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0""> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1""> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2"" checked> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3""> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4""> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     ElseIf w_data_hora = "3" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0""> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1""> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2""> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3"" checked> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4""> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     ElseIf w_data_hora = "4" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0""> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1""> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2""> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3""> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4"" checked> Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""0""> Não<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""1""> Sim, apenas uma data (dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""2""> Sim, apenas uma data/hora (dd/mm/aaaa, hh:mi)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""3""> Sim, período de datas (dd/mm/aaaa a dd/mm/aaaa)<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_data_hora"" value=""4"" > Sim, período de datas/horas (dd/mm/aaaa, hh:mi a dd/mm/aaaa, hh:mi)"
     End If
     ShowHTML "          <tr align=""left"">"
     ShowHTML "              <td title=""Existem serviços que não podem ser atendidos aos sábados, domingos e feriados. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Apenas dias úteis?</b><br>"
     If w_envia_dia_util = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""N""> Não"
     ElseIf w_envia_dia_util = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_envia_dia_util"" value=""N"" > Não"    
     End If
     ShowHTML "              <td title=""Existem serviços em que deseja-se uma descrição da solicitação. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Pede descrição da solicitação?</b><br>"
     If w_pede_descricao = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""N""> Não"
     ElseIf w_pede_descricao = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_descricao"" value=""N"" > Não"    
     End If
     ShowHTML "              <td title=""Existem serviços que exigem uma justificativa da solicitação. Informe \'Sim\' se for o caso desta opção.""><font size=""1""><b>Pede justificativa da solicitação?</b><br>"
     If w_pede_justificativa = "S" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""N""> Não"
     ElseIf w_pede_justificativa = "N" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""N"" checked> Não"    
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pede_justificativa"" value=""N"" > Não"    
     End If
     ShowHTML "          <tr><td colspan=3><font size=""1""><b><U>C</U>omo funciona:<br><TEXTAREA ACCESSKEY=""C"" class=""sti"" name=""w_como_funciona"" rows=5 cols=80 title=""Descreva sucintamente o funcionamento do serviço. Você pode entrar com as regras mais evidentes. Esta informação será apresentada em todas as solicitações deste serviço."">" & w_como_funciona & "</textarea></td>"
     ScriptOpen "JavaScript"
     ShowHTML "  servico();"
     ScriptClose
     ShowHTML "          </table>"

     If O = "I" Then
        ShowHTML "          <tr><td colspan=4 height=""30""><font size=""1""><b>Ativo?</b><br>"
        If w_ativo = "S" Then
           ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""N""> Não"
        Else
           ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""N"" checked> Não"    
        End If
     End If
     
     ShowHTML "      </table>"
     ShowHTML "      </td></tr>"
     ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3""><input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">&nbsp;"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&O=L&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "</FORM>"
  ElseIf O = "H" Then
    AbreForm "Form", R, "POST", "return(Validacao(this));", "content",P1,P2,P3,P4,TP,SG,R,"I"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_endereco_unidade"" value=""" & p_sq_endereco_unidade &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_modulo"" value=""" & p_modulo &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center""><div align=""justify""><font size=2>Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td width=""100%"" align=""left"">"
    ShowHTML "    <table align=""center"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "      <tr valign=""top""><td><font size=""1""><b><U>O</U>rigem:<br> <SELECT READONLY ACCESSKEY=""O"" class=""sts"" name=""w_heranca"" size=""1"">"
    ShowHTML "          <OPTION VALUE="""">---"
    ' Recupera as opções existentes
    DB_GetMenuList RS, w_cliente, O, null
    While Not RS.EOF
      ShowHTML "          <OPTION VALUE=" & RS("sq_menu") & ">" & RS("nome")
      RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
    ShowHTML "      <tr><td align=""center""><font size=1>&nbsp;"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Herdar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""window.close(); opener.focus();"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf O = "P" Then
    AbreForm "Form", w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"">"
    ShowHTML "      <tr><td align=""left""><table width=""100%"" border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoEndereco "<U>E</U>ndereço:", "E", null, p_sq_endereco_unidade, null, "p_sq_endereco_unidade", "FISICO"
    ShowHTML "      </tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoModulo "<u>M</u>ódulo:", "M", null, p_modulo, w_cliente, "p_modulo", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoMenu "<u>O</u>pção do menu principal:", "O", null, p_menu, null, "p_menu", "Pesquisa", null
    ShowHTML "      </tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3""><font size=1>&nbsp;"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "      </table>"
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
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
  If O <> "H" Then
       Estrutura_Fecha
     Estrutura_Fecha
     Estrutura_Fecha
     Rodape
  End If

  Set w_envio                   = Nothing 
  Set w_sq_servico              = Nothing 
  Set w_envia_dia_util          = Nothing 
  Set w_pede_justificativa      = Nothing 
  Set w_pede_descricao          = Nothing 
  Set w_data_hora               = Nothing 
  Set w_arquivo_procedimentos   = Nothing 
  Set w_finalidade              = Nothing 
  Set w_workflow                = Nothing 
  Set w_sq_unidade_executora    = Nothing 
  Set w_sigla                   = Nothing 
  Set w_como_funciona           = Nothing 
  Set w_controla_ano            = Nothing 
  Set w_libera_edicao           = Nothing 
  Set w_emite_os                = Nothing 
  Set w_consulta_opiniao        = Nothing 
  Set w_acompanha_fases         = Nothing 
  Set w_envia_email             = Nothing 
  Set w_exibe_relatorio         = Nothing 
  Set w_null                    = Nothing 
  Set w_heranca                 = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_sq_menu_pai             = Nothing 
  Set w_descricao               = Nothing 
  Set w_link                    = Nothing 
  Set w_tramite                 = Nothing 
  Set w_ordem                   = Nothing 
  Set w_ultimo_nivel            = Nothing 
  Set w_sigla                   = Nothing 
  Set w_ativo                   = Nothing 
  Set w_acesso_geral            = Nothing 
  Set w_modulo                  = Nothing 
  Set w_descentralizado         = Nothing 
  Set w_externo                 = Nothing 
  Set w_target                  = Nothing 

  Set p_sq_endereco_unidade     = Nothing
  Set p_modulo                  = Nothing
  Set p_menu                    = Nothing
  Set RS1                       = Nothing
  Set RS2                       = Nothing
  Set RS3                       = Nothing
  Set w_ImagemPadrao            = Nothing
  Set w_Imagem                  = Nothing
  Set w_Titulo                  = Nothing
  Set w_ContOut                 = Nothing
  Set w_troca                   = Nothing
  Set w_nome                    = Nothing
  Set w_sq_pessoa               = Nothing
  Set w_username                = Nothing
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de manipulação do menu
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de controle de acessos
REM -------------------------------------------------------------------------
Sub Acessos
  Dim w_troca
  Dim w_sq_pessoa
  Dim w_username
  Dim w_nome
  Dim w_sq_modulo, w_sq_pessoa_endereco
  
  w_troca = Request("w_troca")
  
  w_sq_pessoa          = Request("w_sq_pessoa")
  w_sq_modulo          = Request("w_sq_modulo")
  w_sq_pessoa_endereco = Request("w_sq_pessoa_endereco")
  
  DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
  w_username            = RS("username")
  w_nome                = RS("nome")
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>" & conSgSistema & " - Usuários</TITLE>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  If Instr("IAE",O) > 0 Then
     If O = "I" Then
        Validate "w_sq_modulo", "Módulo", "SELECT", 1, 1, 18, "", 1
        Validate "w_sq_pessoa_endereco", "Endereço", "SELECT", 1, 1, 18, "", 1
     End If
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End IF
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad=document.Form.w_sq_modulo.focus();"
  ElseIf O = "E" Then
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""99%"" border=""0"">"
  ShowHTML "      <tr><td><font size=""1"">Nome:<br><font size=2><b>" & RS("nome") & " </b></td>"
  ShowHTML "          <td><font size=""1"">Username:<br><font size=2><b>" & RS("username") & "</b></td>"
  ShowHTML "          </b></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Lotação</td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td><font size=""1"">Unidade:<br><b>" & RS("unidade") & " (" & RS("sigla") & ")</b></td>"
  ShowHTML "          <td><font size=""1"">e-Mail da unidade:<br><b>" & Nvl(RS("email_unidade"),"---") & "</b></td>"
  ShowHTML "      <tr><td colspan=""2""><font size=""1"">Localização:<br><b>" & RS("localizacao") & " </b></td>"
  ShowHTML "      <tr><td><font size=""1"">Endereço:<br><b>" & RS("endereco") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Cidade:<br><b>" & RS("cidade") & "</b></td>"
  ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr><td><font size=""1"">Telefone:<br><b>" & RS("telefone") & " </b></td>"
  ShowHTML "              <td><font size=""1"">Ramal:<br><b>" & RS("ramal") & "</b></td>"
  ShowHTML "              <td><font size=""1"">Telefone 2:<br><b>" & RS("telefone2") & "</b></td>"
  ShowHTML "              <td><font size=""1"">Fax:<br><b>" & RS("fax") & "</b></td>"
  ShowHTML "          </table>"
  If O = "L" Then
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Módulos que gere</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2""><font size=2><b>"
     DesconectaBD
     DB_GetUserModule RS, w_cliente, w_sq_pessoa
     ShowHTML "<tr><td><font size=""2"">"    
     ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <a class=""ss"" href=""#"" onClick=""opener.focus(); window.close();"">Fechar</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=2>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
     ShowHTML "          <td><font size=""1""><b>Módulo</font></td>"
     ShowHTML "          <td><font size=""1""><b>Endereço</font></td>"
     ShowHTML "          <td><font size=""1""><b>Operações</font></td>"    
     ShowHTML "        </tr>"
     w_cont = ""
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       While Not RS.EOF
         ' Se for quebra de endereço, exibe uma linha com o endereço
         If w_cont <> RS("Modulo") Then
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("modulo") & "</td>"
            w_cont = RS("modulo")
         Else
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td align=""center""></td>"
         End If
         ShowHTML "        <td><font size=""1"">" & RS("endereco") & "</td>"
         ShowHTML "        <td><font size=""1"">"
         ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_modulo=" & RS("sq_modulo") & "&w_sq_pessoa_endereco=" & RS("sq_pessoa_endereco") & """>Excluir</A>&nbsp"
         ShowHTML "&nbsp"
         ShowHTML "        </td>"          
         ShowHTML "      </tr>"
         RS.MoveNext
       wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </table>"
  Else
     If O = "E" Then w_Disabled = "DISABLED" End If
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Gestão de Módulo</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""justify"" colspan=""2""><font size=2>Informe o módulo e o endereço que deseja indicar o usuário acima como gestor.</font></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2""><font size=2><b>"
     AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
     If O = "E" Then
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_modulo"" value=""" & w_sq_modulo &""">"
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa_endereco"" value=""" & w_sq_pessoa_endereco &""">"
     End If

     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""90%"" border=""0"">"
     ShowHTML "      <tr>"
     SelecaoModulo "<u>M</u>ódulo:", "M", null, w_sq_modulo, w_cliente, "w_sq_modulo", null, null
     SelecaoEndereco "<U>E</U>ndereço:", "E", null, w_sq_pessoa_endereco, null, "w_sq_pessoa_endereco", "FISICO"
     ShowHTML "      </tr>"
     ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
     ShowHTML "      </table>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3"">"
     If O = "E" Then
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
     Else
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</FORM>"
  End If
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  DesConectaBD
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_troca               = Nothing
  Set w_sq_pessoa           = Nothing
  Set w_nome                = Nothing
  Set w_username            = Nothing
  Set w_sq_modulo           = Nothing
  Set w_sq_pessoa_endereco  = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de acessos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de controle da visão de usuário a centros de custo
REM -------------------------------------------------------------------------
Sub Visao
  Dim w_troca
  Dim w_sq_pessoa
  Dim w_username
  Dim w_nome
  Dim w_sq_cc, w_sq_menu
  Dim w_contOut, RS1, RS2, RS3
  
  w_troca = Request("w_troca")
  
  w_sq_pessoa          = Request("w_sq_pessoa")
  w_sq_cc              = Request("w_sq_cc")
  w_sq_menu            = Request("w_sq_menu")
  
  DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
  w_username            = RS("username")
  w_nome                = RS("nome")
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>" & conSgSistema & " - Usuários</TITLE>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  If Instr("IAE",O) > 0 Then
     If O = "I" Then
        Validate "w_sq_menu", "Serviço", "SELECT", 1, 1, 18, "", 1
        ShowHTML "  var i; "
        ShowHTML "  var w_erro=true; "
        ShowHTML "  if (theForm.w_sq_cc.value==undefined) {"
        ShowHTML "     for (i=0; i < theForm.w_sq_cc.length; i++) {"
        ShowHTML "       if (theForm.w_sq_cc[i].checked) w_erro=false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_sq_cc.checked) w_erro=false;"
        ShowHTML "  }"
        ShowHTML "  if (w_erro) {"
        ShowHTML "    alert('Você deve informar pelo menos uma classificação!'); "
        ShowHTML "    return false;"
        ShowHTML "  }"
     End If
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End IF
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad=document.Form.w_sq_menu.focus();"
  ElseIf O = "E" Then
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""99%"" border=""0"">"
  ShowHTML "      <tr><td><font size=""1"">Nome:<br><font size=2><b>" & RS("nome") & " </b></td>"
  ShowHTML "          <td><font size=""1"">Username:<br><font size=2><b>" & RS("username") & "</b></td>"
  ShowHTML "          </b></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Lotação</td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td><font size=""1"">Unidade:<br><b>" & RS("unidade") & " (" & RS("sigla") & ")</b></td>"
  ShowHTML "          <td><font size=""1"">e-Mail da unidade:<br><b>" & Nvl(RS("email_unidade"),"---") & "</b></td>"
  ShowHTML "      <tr><td colspan=""2""><font size=""1"">Localização:<br><b>" & RS("localizacao") & " </b></td>"
  ShowHTML "      <tr><td><font size=""1"">Endereço:<br><b>" & RS("endereco") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Cidade:<br><b>" & RS("cidade") & "</b></td>"
  ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr><td><font size=""1"">Telefone:<br><b>" & RS("telefone") & " </b></td>"
  ShowHTML "              <td><font size=""1"">Ramal:<br><b>" & RS("ramal") & "</b></td>"
  ShowHTML "              <td><font size=""1"">Telefone 2:<br><b>" & RS("telefone2") & "</b></td>"
  ShowHTML "              <td><font size=""1"">Fax:<br><b>" & RS("fax") & "</b></td>"
  ShowHTML "          </table>"
  If O = "L" Then
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Visão por serviço</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2""><font size=2><b>"
     DesconectaBD
     DB_GetUserVision RS, null, w_sq_pessoa
     ShowHTML "<tr><td><font size=""2"">"    
     ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <a class=""ss"" href=""#"" onClick=""opener.focus(); window.close();"">Fechar</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=2>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
     ShowHTML "          <td><font size=""1""><b>Serviço</font></td>"
     ShowHTML "          <td><font size=""1""><b>Operações</font></td>"    
     ShowHTML "          <td><font size=""1""><b>Configuração atual</font></td>"
     ShowHTML "        </tr>"
     w_cont = ""
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       While Not RS.EOF
         ' Se for quebra de endereço, exibe uma linha com o endereço
         If w_cont <> RS("nm_servico") Then
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("nm_servico") & "(" & RS("nm_modulo") & ")</td>"
            w_cont = RS("nm_servico")
            ShowHTML "        <td><font size=""1"">"
            ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_cc=" & RS("sq_cc") & "&w_sq_menu=" & RS("sq_menu") & """>Alterar</A>&nbsp"
            ShowHTML "&nbsp"
            ShowHTML "        </td>"          
         Else
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td align=""center""></td>"
            ShowHTML "        <td align=""center""></td>"
         End If
         ShowHTML "        <td><font size=""1"">" & RS("nm_cc") & "</td>"
         ShowHTML "      </tr>"
         RS.MoveNext
       wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </table>"
  Else
     If O = "A" Then w_Disabled = "DISABLED" End If
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Visão por serviço</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""justify"" colspan=""2""><font size=2>Informe o serviço e as classificações nas quais o usuário deve ter visão geral.</font></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2""><font size=2><b>"
     AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
     If O = "A" Then
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu &""">"
     End If

     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""90%"" border=""0"">"
     ShowHTML "      <tr valign=""top"">"
     SelecaoServico "<U>S</U>erviço:", "S", null, w_sq_menu, null, "w_sq_menu", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_menu'; document.Form.submit();"""
     ShowHTML "         <td><font size=1><b>Classificações</b>:<br>"

     ' Apresenta a seleção de centros de custo apenas se tiver sido escolhido o serviço
     w_ContOut = 0
     If w_sq_menu > "" Then
        DB_GetCCTreeVision RS, w_cliente, w_sq_pessoa, w_sq_menu, "IS NULL"
        While Not RS.EOF
           w_ContOut = w_ContOut + 1
           If cDbl(RS("Filho")) > 0 Then
              ShowHTML "<font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS("sigla") & "</font>"
              ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
              DB_GetCCTreeVision RS1, w_cliente, w_sq_pessoa, w_sq_menu, RS("sq_cc")
              While Not RS1.EOF
                 If cDbl(RS1("Filho")) > 0 Then
                    w_ContOut = w_ContOut + 1
                    ShowHTML "<font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS1("sigla") & "</font>"
                    ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                    DB_GetCCTreeVision RS2, w_cliente, w_sq_pessoa, w_sq_menu, RS1("sq_cc")
                    While Not RS2.EOF
                       If cDbl(RS2("Filho")) > 0 Then
                          w_ContOut = w_ContOut + 1
                          ShowHTML "<font size=1><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS2("sigla") & "</font>"
                          ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                          DB_GetCCTreeVision RS3, w_cliente, w_sq_pessoa, w_sq_menu, RS2("sq_cc")
                          While Not RS3.EOF
                             If cDbl(RS3("existe")) > 0 Then
                                ShowHTML "    <input checked type=""checkbox"" name=""w_sq_cc"" value=""" & RS3("sq_cc") & """> " & RS3("sigla") & "<br>"
                             Else
                                ShowHTML "    <input type=""checkbox"" name=""w_sq_cc"" value=""" & RS3("sq_cc") & """> " & RS3("sigla") & "<br>"
                             End If
                             RS3.MoveNext
                          Wend
                          ShowHTML "   </font></div>"
                       Else
                          If cDbl(RS2("existe")) > 0 Then
                             ShowHTML "    <input checked type=""checkbox"" name=""w_sq_cc"" value=""" & RS2("sq_cc") & """> " & RS2("sigla") & "<br>"
                          Else
                             ShowHTML "    <input type=""checkbox"" name=""w_sq_cc"" value=""" & RS2("sq_cc") & """> " & RS2("sigla") & "<br>"
                          End If
                       End If
                       RS2.MoveNext
                    Wend
                    ShowHTML "   </font></div>"
                  Else
                    If cDbl(RS1("existe")) > 0 Then
                       ShowHTML "    <input checked type=""checkbox"" name=""w_sq_cc"" value=""" & RS1("sq_cc") & """> " & RS1("sigla") & "<br>"
                    Else
                       ShowHTML "    <input type=""checkbox"" name=""w_sq_cc"" value=""" & RS1("sq_cc") & """> " & RS1("sigla") & "<br>"
                    End If
                 End If
                 RS1.MoveNext
              Wend
              ShowHTML "   </font></div>"
           Else
              If cDbl(RS("existe")) > 0 Then
                 ShowHTML "    <input checked type=""checkbox"" name=""w_sq_cc"" value=""" & RS("sq_cc") & """> " & RS("sigla") & "<br>"
              Else
                 ShowHTML "    <input type=""checkbox"" name=""w_sq_cc"" value=""" & RS("sq_cc") & """> " & RS("sigla") & "<br>"
              End If
           End If
           RS.MoveNext
        Wend
     End If
     If w_contOut = 0 Then ' Se não achou registros
        ShowHTML "<font size=1>Não foram encontrados registros."
     End If

     ShowHTML "      </tr>"
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
     ShowHTML "      </table>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"">"
     If O = "E" Then
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
     Else
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & w_sq_pessoa & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</FORM>"
  End If
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  DesConectaBD
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_troca               = Nothing
  Set w_sq_pessoa           = Nothing
  Set w_nome                = Nothing
  Set w_username            = Nothing
  Set w_sq_cc           = Nothing
  Set w_sq_menu  = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de visão de usuário a centros de custo
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de reinicialização da senha de usuários
REM -------------------------------------------------------------------------
Sub NovaSenha

  Dim w_resultado
  Dim w_senha
  Dim w_html

  ' Cria a nova senha, pegando a hora e o minuto correntes
  w_senha = "nova" & mid(replace(time(),":",""),3,4)

  ' Atualiza a senha de acesso e a assinatura eletrônica, igualando as duas
  DB_UpdatePassword w_cliente, Request("w_sq_pessoa"), w_senha, "PASSWORD"
  DB_UpdatePassword w_cliente, Request("w_sq_pessoa"), w_senha, "SIGNATURE"
            
  ' Configura a mensagem automática comunicando ao usuário sua nova senha de acesso e assinatura eletrônica
  w_html = "<HTML><HEAD><TITLE>Reinicialização de senha</TITLE></HEAD>" & VbCrLf
  w_html = w_html & BodyOpenMail(null) & VbCrLf
  w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>REINICIALIZAÇÃO DE SENHA</b></font><br><br><td></tr>" & VbCrLf
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf
  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Sua senha e assinatura eletrônica foram reinicializadas. A partir de agora, utilize os dados informados abaixo:<br>" & VbCrLf
  w_html = w_html & "         <ul>" & VbCrLf
  DB_GetCustomerSite RS, w_cliente
  w_html = w_html & "         <li>Endereço de acesso ao sistema: <b><a class=""ss"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  DesconectaBD
  DB_GetUserData rs, w_cliente, Request("w_username")
  w_html = w_html & "         <li>CPF: <b>" & RS("username") & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>Nome: <b>" & RS("nome") & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>e-Mail: <b>" & RS("email") & "</b></li>" & VbCrLf
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
  DB_GetCustomerData RS, w_cliente
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
  w_html = w_html & "         <li>Usuário responsável: <b>" & Session("nome") & " (" & Session("email") & ")</b></li>" & VbCrLf
  w_html = w_html & "         </ul>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  w_html = w_html & "    </table>" & VbCrLf
  w_html = w_html & "</td></tr>" & VbCrLf
  w_html = w_html & "</table>" & VbCrLf
  w_html = w_html & "</BODY>" & VbCrLf
  w_html = w_html & "</HTML>" & VbCrLf
  Response.Write w_html
        
  Set w_html      = Nothing
  Set w_Senha     = Nothing
  Set w_resultado = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de autenticação de usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tela de exibição do usuário
REM -------------------------------------------------------------------------
Sub TelaUsuario
  Dim w_sq_pessoa
    
  w_sq_pessoa          = Request("w_sq_pessoa")
  
  DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If RS("interno") = "S" Then
     ShowHTML "<TITLE>Usuário</TITLE>"
     ShowHTML "</HEAD>"
     BodyOpen "onLoad=document.focus();"
     TP = "Dados usuário"
     Estrutura_Texto_Abre
     ShowHTML "<table border=""0"" width=""100%"">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""99%"" border=""0"">"
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "          <td><font size=""1"">Nome:<br><font size=2><b>" & RS("nome") & " </b></td>"
     ShowHTML "          <td><font size=""1"">Nome resumido:<br><font size=2><b>" & RS("nome_resumido") & "</b></td>"
     If Nvl(RS("email"),"") > "" Then
        ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><b><A class=""hl"" HREF=""mailto:" & RS("email") & """>" & RS("email") & "</a></b></td>"
     Else
        ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><b>---</b></td>"
     End If
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Lotação</td>"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td><font size=""1"">Unidade:<br><b>" & RS("unidade") & " (" & RS("sigla") & ")</b></td>"
     If Nvl(RS("email_unidade"),"") > "" Then
        ShowHTML "          <td><font size=""1"">e-Mail da unidade:<br><b><A class=""hl"" HREF=""mailto:" & RS("email_unidade") & """>" & RS("email_unidade") & "</a></b></td>"
     Else
        ShowHTML "          <td><font size=""1"">e-Mail da unidade:<br><b>---</b></td>"
     End If
     ShowHTML "      <tr><td colspan=""2""><font size=""1"">Localização:<br><b>" & RS("localizacao") & " </b></td>"
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "          <td><font size=""1"">Endereço:<br><b>" & RS("endereco") & "</b></td>"
     ShowHTML "          <td><font size=""1"">Cidade:<br><b>" & RS("cidade") & "</b></td>"
     ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
     ShowHTML "          <td><font size=""1"">Telefone:<br><b>" & Nvl(RS("telefone"),"---") & " </b></td>"
     ShowHTML "          <td><font size=""1"">Ramal:<br><b>" & Nvl(RS("ramal"),"---") & "</b></td>"
     ShowHTML "          <td><font size=""1"">Telefone 2:<br><b>" & Nvl(RS("telefone2"),"---") & "</b></td>"
     ShowHTML "          <td><font size=""1"">Fax:<br><b>" & Nvl(RS("fax"),"---") & "</b></td>"
     ShowHTML "          </table>"
     ShowHTML "  </td>"
     ShowHTML "</tr>"
     ShowHTML "</table>"
     DesConectaBD
  ElseIf Instr("Cliente,Fornecedor", RS("nome_vinculo")) > 0 Then
     ShowHTML "<TITLE>Pessoa externa</TITLE>"
     ShowHTML "</HEAD>"
     BodyOpen "onLoad=document.focus();"
     TP = "Dados pessoa externa"
     Estrutura_Texto_Abre
     ShowHTML "<table border=""0"" width=""100%"">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""99%"" border=""0"">"
     ' Outra parte
     DB_GetBenef RS1, w_cliente, w_sq_pessoa, null, null, null, null, null, null
     If RS1.EOF Then
        ShowHTML "      <tr><td colspan=2><font size=2><b>Outra parte não informada"
     Else
        ShowHTML "      <tr><td><font size=1>Nome:</font><br><font size=2><b>" & RS1("nm_pessoa")
        ShowHTML "          <td><font size=1>Nome resumido:</font><br><font size=2><b>" & RS1("nome_resumido")
        If Nvl(RS1("email"),"nulo") <> "nulo" Then
           ShowHTML "      <tr><td><font size=""1"">e-Mail:<b><br><a class=""hl"" href=""mailto:" & RS1("email") & """>" & RS1("email") & "</a></td>"
        Else
           ShowHTML "      <tr><td><font size=""1"">e-Mail:<b><br>---</td>"
        End If
        If cDbl(RS1("sq_tipo_pessoa")) = 1 Then
           ShowHTML "          <td colspan=""2""><font size=""1"">Sexo:<b><br>" & RS1("nm_sexo") & "</td>"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Endereço comercial, Telefones e e-Mail</td>"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        Else
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Endereço principal, Telefones e e-Mail</td>"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
           ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
        End If
        ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
        ShowHTML "          <tr valign=""top"">"
        If Nvl(RS1("ddd"),"") > "" Then
           ShowHTML "          <td><font size=""1"">Telefone:<b><br>(" & RS1("ddd") & ") " & RS1("nr_telefone") & "</td>"
        Else
           ShowHTML "          <td><font size=""1"">Telefone:<b><br>---</td>"
        End If
        ShowHTML "          <td><font size=""1"">Fax:<b><br>" & Nvl(RS1("nr_fax"),"---") & "</td>"
        ShowHTML "          <td><font size=""1"">Celular:<b><br>" & Nvl(RS1("nr_celular"),"---") & "</td>"
        ShowHTML "          <tr valign=""top"">"
        ShowHTML "          <td><font size=""1"">Endereço:<b><br>" & Nvl(RS1("logradouro"),"---") & "</td>"
        ShowHTML "          <td><font size=""1"">Complemento:<b><br>" & Nvl(RS1("complemento"),"---") & "</td>"
        ShowHTML "          <td><font size=""1"">Bairro:<b><br>" & Nvl(RS1("bairro"),"---") & "</td>"
        ShowHTML "          <tr valign=""top"">"
        If Nvl(RS1("pd_pais"),"") > "" Then
           If RS1("pd_pais") = "S" Then
              ShowHTML "          <td><font size=""1"">Cidade:<b><br>" & RS1("nm_cidade") & "-" & RS1("co_uf") & "</td>"
           Else
              ShowHTML "          <td><font size=""1"">Cidade:<b><br>" & RS1("nm_cidade") & "-" & RS1("nm_pais") & "</td>"
           End If
        Else
           ShowHTML "          <td><font size=""1"">Cidade:<b><br>---</td>"
        End IF
        ShowHTML "          <td><font size=""1"">CEP:<b><br>" & Nvl(RS1("cep"),"---") & "</td>"
        ShowHTML "          </table>"
     End If
     ShowHTML "  </td>"
     ShowHTML "</tr>"
     ShowHTML "</table>"
     DesConectaBD
  End If
  Estrutura_Texto_Fecha

  Set w_sq_pessoa           = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de visão de usuário a centros de custo
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tela de exibição do usuário
REM -------------------------------------------------------------------------
Sub TelaUnidade
  Dim w_sq_unidade
    
  w_sq_unidade          = Request("w_sq_unidade")
  
  DB_GetUorgData RS, w_sq_unidade
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>Unidade</TITLE>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  TP = "Dados de unidade"
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""99%"" border=""0"">"
  ShowHTML "      <tr><td><font size=""1"">Unidade: <br><font size=2><b>" & RS("nome") & "("&RS("sigla")&")</b></td>"
  ShowHTML "          <td><font size=""1"">Tipo: <br><b>" & RS("nm_tipo_unidade") & "</b></td>"
  If Nvl(RS("email"),"") > "" Then
     ShowHTML "      <tr><td><font size=""1"">e-Mail:<br><b><A class=""hl"" HREF=""mailto:" & RS("email") & """>" & RS("email") & "</a></b></td>"
  Else
     ShowHTML "      <tr><td><font size=""1"">e-Mail:<br><b>---</b></td>"
  End If
  ShowHTML "          </b></td>"
  If Nvl(RS("codigo"),"") > "" Then
     ShowHTML "      <tr><td><font size=""1"">Código:<br><b>" & RS("codigo") & " </b></td>"
  Else
     ShowHTML "          <td><font size=""1"">Código:<br><b>---</b></td>"
  End If
  DesConectaBD
  ShowHTML "          </b></td>"
  
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2""     bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1""     bgcolor=""#000000"">"
  ShowHTML "      <tr><td   colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Responsáveis</td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1""     bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  DB_GetUorgResp RS, w_sq_unidade
  If RS.EOF Then
    ShowHTML "      <tr><td align=""center"" colspan=2><font size=""2""><b>Não informados</b></b></td>"
  Else
    If cDbl(Nvl(RS("titular2"),0)) = 0 and cDbl(Nvl(RS("substituto2"),0)) = 0 Then
       ShowHTML "      <tr><td align=""center"" colspan=2><font size=""2""><b>Não informados</b></b></td>"
    Else
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td><font size=""1"">Titular: <br><font size=1><b>" & RS("nm_titular") &"</b></td>"
       ShowHTML "          <td><font size=""1"">Desde: <br><font size=1><b>" & FormataDataEdicao(RS("inicio_titular")) & "</b></td>"
       ShowHTML "      <tr><td colspan=2><font size=""1"">Localização: <br><font size=1><b>" & RS("tit_sala") &" ( " &  RS("tit_logradouro")& " )</b><td>"
       If Nvl(RS("email_titular"),"") > "" Then
          ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><b><A class=""hl"" HREF=""mailto:" & RS("email_titular") & """>" & RS("email_titular") & "</a></b></td>"
       Else
          ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><b>---</b></td>"
       End If
       ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
       If Nvl(RS("nm_substituto"),"") > "" Then
         ShowHTML "      <tr valign=""top"">"
         ShowHTML "          <td><font size=""1"">Substituto: <br><font size=1><b>" & RS("nm_substituto") &"</b></td>"
         ShowHTML "          <td><font size=""1"">Desde: <br><font size=1><b>" & FormataDataEdicao(RS("inicio_substituto")) & "</b></td>"
         If Nvl(RS("sub_sala"),"") > "" Then
            ShowHTML "      <tr><td colspan=2><font size=""1"">Localização: <br><font size=1><b>" & RS("sub_sala") &" ( " &  RS("sub_logradouro")& " )</b><td>"
         Else
            ShowHTML "      <tr><td colspan=2><font size=""1"">Localização:<br><font size=1><b>---</b></td>"
         End If
         If Nvl(RS("email_substituto"),"") > "" Then
          ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><font size=1><b><A class=""hl"" HREF=""mailto:" & RS("email_substituto") & """>" & RS("email_substituto") & "</a></b></td>"
         Else
          ShowHTML "      <tr><td colspan=2><font size=""1"">e-Mail:<br><font size=1><b>---</b></td>"
         End If
       Else
         ShowHTML "      <tr><td colspan=2><font size=""1"">Substituto:<br><font size=1><b>Não indicado</b></td>"
       End If
     End If
  End If

  ShowHTML "          </b></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Localizações da Unidade</td>"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""2"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=2>"
  ShowHTML "          <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "            <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "              <td><font size=""1""><b>Localização</font></td>"
  ShowHTML "              <td><font size=""1""><b>Telefone</font></td>"
  ShowHTML "              <td><font size=""1""><b>Ramal</font></td>"
  ShowHTML "              <td><font size=""1""><b>Fax</font></td>"
  ShowHTML "              <td><font size=""1""><b>Endereço</font></td>"
  ShowHTML "            </tr>"
  DB_GetaddressList RS, w_cliente, w_sq_unidade, "LISTALOCALIZACAO"
  While Not RS.EOF
    ShowHTML "            <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
    ShowHTML "              <td><font size=""1"">" & RS("nome") &"</td>"
    ShowHTML "              <td><font size=""1"">" & Nvl(RS("telefone"),"---")
    If Nvl(RS("telefone2"),"") > "" Then ShowHTML "/ " & RS("telefone2") & "" End If
    ShowHTML "              <td align=""center""><font size=""1"">"    & Nvl(RS("ramal"),"---")      & "</td>"
    ShowHTML "              <td align=""center""><font size=""1"">" & Nvl(RS("fax"),"---")        &"</td>"
    ShowHTML "              <td><font size=1>" & RS("logradouro") & " (" & RS("cidade") & ")</td>"
    ShowHTML "      </tr>"
    RS.MoveNext
  wend
  ShowHTML "    </table>"
  ShowHTML "</table>"
  DesConectaBD
  Estrutura_Texto_Fecha

  Set w_sq_unidade           = Nothing
  
End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_Chave
  Dim i

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "MENU"
       p_sq_endereco_unidade = uCase(Request("p_sq_endereco_unidade"))
       p_modulo              = uCase(Request("p_modulo"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_SiwMenu O, _
                Request("w_sq_menu"), Request("w_sq_menu_pai"), Request("w_link"), _
                Request("w_p1"), Request("w_p2"), Request("w_p3"), Request("w_p4"), Request("w_sigla"), _
                Request("w_imagem"), Request("w_target"), Request("w_emite_os"), Request("w_consulta_opiniao"), _
                Request("w_envia_email"), Request("w_exibe_relatorio"), Request("w_como_funciona"), _
                Request("w_vinculacao"), Request("w_data_hora"), Request("w_envia_dia_util"), _
                Request("w_pede_descricao"), Request("w_pede_justificativa"), Request("w_finalidade"), _
                w_cliente, Request("w_descricao"), Request("w_acesso_geral"), Request("w_modulo"), Request("w_sq_unidade_executora"),_
                Request("w_tramite"), Request("w_ultimo_nivel"), Request("w_descentralizado"), _
                Request("w_externo"), Request("w_ativo"), Request("w_ordem"), Request("w_envio"), Request("w_controla_ano"), _
                Request("w_libera_edicao")


          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_cliente=" & w_cliente & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ACESSOS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_SgPesMod O, Request("w_sq_pessoa"), w_cliente, Request("w_sq_modulo"), Request("w_sq_pessoa_endereco")

          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & Request("w_sq_pessoa") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "VISAO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          If O = "A" Then ' Se for alteração, elimina todas as permissões existentes para depois incluir
             DML_PutSiwPesCC "E",  Request("w_sq_pessoa"), Request("w_sq_menu"), null
          End If

          For i = 1 To Request.Form("w_sq_cc").Count
             DML_PutSiwPesCC "I",  Request("w_sq_pessoa"), Request("w_sq_menu"), Request.Form("w_sq_cc")(i)
          Next
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_pessoa=" & Request("w_sq_pessoa") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set i                     = Nothing
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
    Case "USUARIOS"     Usuarios
    Case "MENU"         Menu
    Case "ACESSOS"      Acessos
    Case "VISAO"        Visao
    Case "TELAUSUARIO"  TelaUsuario
    Case "TELAUNIDADE"  TelaUnidade       
    Case "NOVASENHA"    NovaSenha
    Case "GRAVA"        Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       Estrutura_Topo_Limpo
       Estrutura_Menu
       Estrutura_Corpo_Abre
       Estrutura_Texto_Abre
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
         Estrutura_Texto_Fecha
         Estrutura_Fecha
       Estrutura_Fecha
       Estrutura_Fecha
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

