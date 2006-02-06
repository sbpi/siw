<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Gerenciar tabelas básicas do módulo	
REM Mail     : Beto@sbpi.com.br
REM Criacao  : 23/01/2005 11:00
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = E   : Exclusão
REM                   = L   : Listagem

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG, p_ordena
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa
Dim ul,File
Dim w_pag, w_linha

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
p_ordena     = uCase(Request("p_ordena"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Tabelas.asp?par="
w_Dir        = "mod_fn/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configuração do serviço
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set p_ordena      = Nothing
Set w_chave       = Nothing
Set w_copia       = Nothing
Set w_filtro      = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
Set w_submenu     = Nothing
Set w_reg         = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set RS3           = Nothing
Set RS4           = Nothing
Set RS_menu       = Nothing
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
Set w_dir         = Nothing
Set w_dir_volta   = Nothing

REM =========================================================================
REM Rotina de impostos
REM -------------------------------------------------------------------------
Sub Imposto
  Dim w_chave, w_nome, w_descricao, w_sigla, w_esfera, w_calculo, w_dia_pagamento, w_ativo
  
  w_chave         = Request("w_chave")
  w_nome          = Request("w_nome")
  w_descricao     = Request("w_descricao")
  w_sigla         = Request("w_sigla")
  w_esfera        = Request("w_esfera")
  w_calculo       = Request("w_calculo")
  w_dia_pagamento = Request("w_dia_pagamento")
  w_ativo         = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de impostos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If
  If O = "L" Then
    DB_GetImposto RS, null, w_cliente
    RS.Sort = Nvl(p_ordena,"nome")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetImposto RS, w_chave, w_cliente
    w_chave         = RS("chave")
    w_nome          = RS("nome")
    w_descricao     = RS("descricao")
    w_sigla         = RS("sigla")
    w_esfera        = RS("nm_esfera")
    w_calculo       = RS("nm_calculo")
    w_dia_pagamento = RS("dia_pagamento")
    w_ativo         = RS("nm_ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome",          "Nome",                    "1", "1", "3", "50",  "1", "1"
        Validate "w_descricao",     "descrição",               "1", "1", "3", "500", "1", "1"
        Validate "w_sigla",         "Sigla",                   "1", "1", "2", "15",  "1", "1"
        Validate "w_esfera",        "Esfera",             "SELECT", "1", "1", "1",   "1", "1"
        Validate "w_calculo",       "Calculo",            "SELECT", "1", "1", "1",   "1", "1"
        Validate "w_dia_pagamento", "Dia do Pagamento",        "1", "1", "1", "2",   "", "1"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_nome.focus();"
  ElseIf O = "L" Then
     BodyOpen "onLoad=document.focus();"
  Else
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  End If
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Sigla","sigla") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Descrição","descricao") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Esfera","nm_esfera") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Cálculo","nm_calculo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","nm_ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td><font size=""1"">" & RS("sigla")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("descricao")   & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_esfera")  & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_calculo")  & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo")  & "</td>"
         ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """ Title=""Nome"">Alterar </A>&nbsp"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & """>Excluir </A>&nbsp"
         ShowHTML "        </td>"
         ShowHTML "      </tr>"
         RS.MoveNext
       wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </td>"
     ShowHTML "<tr><td align=""center"" colspan=3>"
     If R > "" Then
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     End If
     ShowHTML "</tr>"
     DesconectaBD
     'Aqui começa a manipulação de registros
  ElseIf Instr("IAEV",O) > 0 Then
     If InStr("EV",O) Then w_Disabled = " DISABLED " End If
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     ShowHTML "      <tr><td colspan=""5""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""50"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr><td colspan=""5""><font size=""1""><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" name=""w_descricao"" rows=""5"" cols=75>" & w_descricao & "</textarea></td>"
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "          <td><font size=""1""><b><u>S</u>igla:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_sigla & """></td>"
     SelecaoEsfera  "<u>E</u>sfera:",  "E", "Selecione a esfera desejada", w_chave, w_esfera, w_cliente, "w_esfera", null, null
     SelecaoCalculo "<u>C</u>alculo:", "C", "Selecione a base de calculo", w_chave, w_calculo, w_cliente, "w_calculo", null, null
     ShowHTML "          <td><font size=""1""><b>D<u>i</u>a de pagamento:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_dia_pagamento"" class=""sti"" SIZE=""2"" MAXLENGTH=""2"" VALUE=""" & w_dia_pagamento & """></td>"
     ShowHTML "      <tr valign=""top"">"
     MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
     ShowHTML "      <tr><td colspan=5><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=5><hr>"
     If O = "E" Then
        ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
     Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
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
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set RS                        = Nothing 
  Set w_chave                   = Nothing 
  Set w_nome                    = Nothing 
  Set w_descricao               = Nothing
  Set w_sigla                   = Nothing 
  Set w_esfera                  = Nothing
  Set w_calculo                 = Nothing 
  Set w_dia_pagamento           = Nothing 
  Set w_ativo                   = Nothing 
  Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de impostos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tipos de documentos
REM -------------------------------------------------------------------------
Sub Documento
  Dim w_chave, w_nome, w_sigla, w_ativo
  
  w_chave         = Request("w_chave")
  w_nome          = Request("w_nome")
  w_sigla         = Request("w_sigla")
  w_ativo         = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de tipos de documentos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  If O = "" Then O="L" End If
  If O = "L" Then
    DB_GetTipoDocumento RS, null, w_cliente
    RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetTipoDocumento RS, w_chave, w_cliente
    w_chave         = RS("chave")
    w_nome          = RS("nome")
    w_sigla         = RS("sigla")
    w_ativo         = RS("nm_ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome",          "Nome",                    "1", "1", "3", "30",  "1", "1"
        Validate "w_sigla",         "Sigla",                   "1", "1", "2", "10",  "1", "1"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_nome.focus();"
  ElseIf O = "L" Then
     BodyOpen "onLoad=document.focus();"
  Else
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  End If
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     DB_GetTipoDocumento RS, null, w_cliente
     ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Sigla","sigla") & "</font></td>"    
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","nm_ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ' Lista os registros selecionados para listagem
        rs.PageSize     = P4
        rs.AbsolutePage = P3
        While Not RS.EOF and RS.AbsolutePage = P3
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td align=""left""><font size=""1"">"   & RS("nome")     & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla")    & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """ Title=""Nome"">Alterar </A>&nbsp"
          ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & """>Excluir </A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
          RS.MoveNext
        wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </td>"
     ShowHTML "<tr><td align=""center"" colspan=3>"
     If R > "" Then
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     End If
     ShowHTML "</tr>"
     DesconectaBD
     'Aqui começa a manipulação de registros
  ElseIf Instr("IAEV",O) > 0 Then
     If InStr("EV",O) Then w_Disabled = " DISABLED " End If
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     ShowHTML "      <tr><td><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr><td><font size=""1""><b><u>S</u>igla:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_sigla & """></td>"
     ShowHTML "      <tr>"
     MontaRadioSN "<b>Ativo</b>?", w_ativo, "w_ativo"
     ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
     If O = "E" Then
        ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
     Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
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
   ShowHTML "    </table>"
   ShowHTML "    </TD>"
   ShowHTML "</tr>"
   ShowHTML "</table>"
   ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
   Estrutura_Fecha
   Estrutura_Fecha
   Rodape

   Set RS                        = Nothing 
   Set w_chave                   = Nothing 
   Set w_nome                    = Nothing 
   Set w_ativo                   = Nothing 
   Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de tipos de documento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Tipos de lancamento
REM -------------------------------------------------------------------------
Sub Lancamento
  Dim w_troca, w_chave, w_nome, w_descricao, w_receita, w_despesa, w_ativo
  
  w_chave         = Request("w_chave")
  w_nome          = Request("w_nome")
  w_descricao     = Request("w_descricao")
  w_receita       = Request("w_receita")
  w_despesa       = Request("w_despesa")
  w_ativo         = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de tipos de lançamento</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  If O = "" Then O="L" End If
  If O = "L" Then
    DB_GetTipoLancamento RS, null, w_cliente, null
    RS.Sort = "receita desc, nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetTipoLancamento RS, w_chave, w_cliente, null
    w_chave         = RS("chave")
    w_nome          = RS("nome")
    w_descricao     = RS("descricao")
    w_receita       = RS("nm_receita")
    w_despesa       = RS("nm_despesa")
    w_ativo         = RS("nm_ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome",       "Nome",                  "1", "1", "5", "200", "1", "1"
        Validate "w_descricao",  "descrição",             "1", "1", "5", "200", "1", "1"
        ShowHTML "  if (theForm.w_receita[1].checked == true && theForm.w_despesa[1].checked == true) {"
        ShowHTML "     alert ('Não pode existir tipo de lançamento com valores negativos para o campo receita e despesa ao mesmo tempo!');"
        ShowHTML "     return false;"
        ShowHTML "  }"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6",  "30", "1", "1"
        
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_nome.focus();"
  ElseIf O = "L" Then
     BodyOpen "onLoad=document.focus();"
  Else
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  End If
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Descrição","descricao") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Receita","nm_receita") & "</font></td>"    
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","nm_ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome")      & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("descricao") & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_receita")   & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo")     & "</td>"
         ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """ Title=""Nome"">Alterar </A>&nbsp"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & """>Excluir </A>&nbsp"
         ShowHTML "        </td>"
         ShowHTML "      </tr>"
         RS.MoveNext
       wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </td>"
     ShowHTML "<tr><td align=""center"" colspan=3>"
     If R > "" Then
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     End If
     ShowHTML "</tr>"
     DesconectaBD
     'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
     If InStr("EV",O) Then w_Disabled = " DISABLED " End If
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     ShowHTML "      <tr><td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""75"" MAXLENGTH=""200"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr><td colspan=3><font size=""1""><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" name=""w_descricao"" rows=""5"" cols=75>" & w_descricao & "</textarea></td>"
     ShowHTML "      <tr>"
     MontaRadioNS "<b>Receita?</b>", w_receita, "w_receita"
     MontaRadioNS "<b>Despesa?</b>", w_despesa, "w_despesa"
     MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
     ShowHTML "      <tr><td align=""LEFT"" colspan=3><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=3><hr>"
     If O = "E" Then
        ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
     Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
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
   ShowHTML "    </table>"
   ShowHTML "    </TD>"
   ShowHTML "</tr>"
   ShowHTML "</table>"
   ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
   Estrutura_Fecha
   Estrutura_Fecha
   Rodape

   Set RS                        = Nothing 
   Set w_chave                   = Nothing 
   Set w_nome                    = Nothing 
   Set w_descricao               = Nothing
   Set w_ativo                   = Nothing 
   Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de tipos de lançamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
   Dim p_sq_endereco_unidade
   Dim p_modulo
   Dim w_Null
   Dim w_mensagem
   Dim FS, F1
   Dim w_chave_nova
  
   Cabecalho
   ShowHTML "</HEAD>"
   BodyOpen "onLoad=document.focus();"
   
   AbreSessao    
   Select Case SG
      Case "FNIMPOSTO"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            DML_PutImposto O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_nome"), Request("w_descricao") , _
                           Request("w_sigla"), Request("w_esfera"), Request("w_calculo"), Request("w_dia_pagamento"),_
                           Request("w_ativo")
           
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "FNTPDOC"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            DML_PutTipoDocumento O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_nome"), _
                                 Request("w_sigla"), Request("w_ativo")
           
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose            
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "FNTPLANC"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            DML_PutTipoLancamento O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_nome"), Request("w_descricao") , _
                                  Request("w_receita"), Request("w_despesa"), Request("w_ativo")
           
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case Else
         ScriptOpen "JavaScript"
         ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
         ShowHTML "  history.back(1);"
         ScriptClose
   End Select

   Set w_chave_nova          = Nothing
   Set FS                    = Nothing
   Set w_Mensagem            = Nothing
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
  Select Case Par
    Case "IMPOSTO"    Imposto
    Case "DOCUMENTO"  Documento
    Case "LANCAMENTO" Lancamento
    Case "GRAVA"      Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
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