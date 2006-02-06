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
<!-- #INCLUDE FILE="ValidaAfastamento.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Afastamento.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerenciar tabelas básicas do módulo	de gestão de pessoal
REM Mail     : celso@sbpi.com.br
REM Criacao  : 02/08/2005 10:00
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

w_Pagina     = "Afastamento.asp?par="
w_Dir        = "mod_rh/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If SG = "GPAFAST" and O = "" Then
   O = "P"
End If

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
REM Rotina de afastamentos
REM -------------------------------------------------------------------------
Sub Afastamento
  Dim w_chave, w_sq_tipo_afastamento, w_sq_contrato_colaborador, w_inicio_data, w_inicio_periodo
  Dim w_fim_data, w_fim_periodo, w_dias, w_observacao 
  Dim p_sq_tipo_afastamento, p_sq_contrato_colaborador, p_inicio_data, p_fim_data
  Dim w_modalidades
  
  w_chave                   = Request("w_chave")
  
  If w_troca > "" Then
     w_sq_tipo_afastamento     = Request("w_sq_tipo_afastamento")
     w_sq_contrato_colaborador = Request("w_sq_contrato_colaborador")
     w_inicio_data             = Request("w_inicio_data")
     w_inicio_periodo          = Request("w_inicio_periodo")
     w_fim_data                = Request("w_fim_data")
     w_fim_periodo             = Request("w_fim_periodo")
     w_dias                    = Request("w_dias")
     w_observacao              = Request("w_observacao")
  Else  
     If O = "L" Then
        DB_GetAfastamento RS, w_cliente, null, Request("p_sq_tipo_afastamento"), Request("p_sq_contrato_colaborador"), Request("p_inicio_data"), Request("p_fim_data"), null, null, null, null
        RS.Sort = "inicio_data desc, inicio_periodo, nome_resumido"
     ElseIf InStr("AEV",O) > 0 Then
        DB_GetAfastamento RS, w_cliente, w_chave, null, null, null, null, null, null, null, null
        w_chave                   = RS("chave")
        w_sq_tipo_afastamento     = RS("sq_tipo_afastamento")
        w_sq_contrato_colaborador = RS("sq_contrato_colaborador")
        w_inicio_data             = RS("inicio_data")
        w_inicio_periodo          = RS("inicio_periodo")
        w_fim_data                = RS("fim_data")
        w_fim_periodo             = RS("fim_periodo")
        w_dias                    = RS("dias")
        w_observacao              = RS("observacao")
        DesconectaBD
     End If
  End If
  If Nvl(w_sq_tipo_afastamento,"") > "" and InStr("IA",O) > 0 Then
     DB_GetGPTipoAfast RS1, w_cliente, w_sq_tipo_afastamento, null, null, null, null, "MODALIDADES"
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de afastamentos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_tipo_afastamento", "Tipo de afastamento", "SELECT", "1", "1", "18", "", "0123456789"
        Validate "w_sq_contrato_colaborador", "Colaborador", "SELECT", "1", "1", "18", "", "0123456789"
        Validate "w_inicio_data", "Início", "DATA", "1", "10", "10", "", "0123456789/"
        If Nvl(w_sq_tipo_afastamento,"") > "" Then 
           If RS1("periodo") = "A" Then
              Validate "w_fim_data", "Término", "DATA", "1", "10", "10", "", "0123456789/"
              CompData "w_inicio_data", "Início", "<=", "w_fim_data", "Término"
              ShowHTML "  if (theForm.w_inicio_data.value == theForm.w_fim_data.value) {"
              ShowHTML "     if (theForm.w_inicio_periodo[1].checked && theForm.w_fim_periodo[0].checked) {"
              ShowHTML "        alert('Período de término do afastamento deve ser igual ou posterior ao de início!');"
              ShowHTML "        return false;"
              ShowHTML "     }"
              ShowHTML "  }"
              If RS1("contagem_dias") = "C" Then
                 ShowHTML "  var w_data, w_data1, w_data2;"
                 ShowHTML "  w_data = theForm.w_inicio_data.value;"
                 ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
                 ShowHTML "  w_data1  = new Date(Date.parse(w_data));"
                 ShowHTML "  w_data = theForm.w_fim_data.value;"
                 ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
                 ShowHTML "  w_data2= new Date(Date.parse(w_data));"
                 ShowHTML "  var MinMilli = 1000 * 60;"
                 ShowHTML "  var HrMilli = MinMilli * 60;"
                 ShowHTML "  var DyMilli = HrMilli * 24;"
                 ShowHTML "  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));"
                 ShowHTML "  if (theForm.w_inicio_periodo[0].checked) {"
                 ShowHTML "     if (theForm.w_fim_periodo[0].checked) Days = Days + 0.5; "
                 ShowHTML "     else Days = Days + 1; "
                 ShowHTML "  }"
                 ShowHTML "  else {"
                 ShowHTML "     if (theForm.w_fim_periodo[1].checked) Days = Days + 0.5; "
                 ShowHTML "  }"
                 ShowHTML "  if (Days > " & RS1("limite_dias") & ") {"
                 ShowHTML "     alert('" & RS1("nome") & " tem limite de " & RS1("limite_dias") & " dias " & lCase(RS1("nm_contagem_dias")) & "!');"
                 ShowHTML "     theForm.w_inicio_data.focus();"
                 ShowHTML "     return false;"
                 ShowHTML "  }"
              End If
           ElseIf RS1("periodo") = "D" Then
              Validate "w_dias", "Dias", "", "1", "1", "4", "", "0123456789"
              CompValor "w_dias", "Dias", ">", 0, "zero"
              ShowHTML "  if (parseInt(theForm.w_dias.value) > " & RS1("limite_dias") & ") {"
              ShowHTML "     alert('" & RS1("nome") & " tem limite de " & RS1("limite_dias") & " dias " & lCase(RS1("nm_contagem_dias")) & "!');"
              ShowHTML "     theForm.w_dias.focus();"
              ShowHTML "     return false;"
              ShowHTML "  }"
           ElseIf RS1("periodo") = "H" Then
              If RS1("contagem_dias") = "C" Then
                 ShowHTML "  var w_data, w_data1, w_data2;"
                 ShowHTML "  w_data = theForm.w_inicio_data.value;"
                 ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
                 ShowHTML "  w_data1  = new Date(Date.parse(w_data));"
                 ShowHTML "  w_data = theForm.w_fim_data.value;"
                 ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
                 ShowHTML "  w_data2= new Date(Date.parse(w_data));"
                 ShowHTML "  var MinMilli = 1000 * 60;"
                 ShowHTML "  var HrMilli = MinMilli * 60;"
                 ShowHTML "  var DyMilli = HrMilli * 24;"
                 ShowHTML "  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));"
                 ShowHTML "  if (theForm.w_inicio_periodo[0].checked) {"
                 ShowHTML "     if (theForm.w_fim_periodo[0].checked) Days = Days + 0.5; "
                 ShowHTML "     else Days = Days + 1; "
                 ShowHTML "  }"
                 ShowHTML "  else {"
                 ShowHTML "     if (theForm.w_fim_periodo[1].checked) Days = Days + 0.5; "
                 ShowHTML "  }"
                 ShowHTML "  if (Days > " & RS1("limite_dias") & ") {"
                 ShowHTML "     alert('" & RS1("nome") & " tem limite de " & RS1("limite_dias") & " dias " & lCase(RS1("nm_contagem_dias")) & "!');"
                 ShowHTML "     theForm.w_inicio_data.focus();"
                 ShowHTML "     return false;"
                 ShowHTML "  }"
              End If
              ShowHTML "theForm.w_fim_data = theForm.w_inicio_data;"
              ShowHTML "theForm.w_fim_periodo = theForm.w_inicio_periodo;"
           End If
        End If
        
        Validate "w_observacao", "Observação", "", "1", "1", "300", "1", "1"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
     ElseIf O = "P" Then
        Validate "p_sq_tipo_afastamento", "Tipo de afastamento", "SELECT", "", "1", "18", "", "0123456789"
        Validate "p_sq_contrato_colaborador", "Colaborador", "SELECT", "", "1", "18", "", "0123456789"
        Validate "p_inicio_data", "Início", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_data", "Término", "DATA", "", "10", "10", "", "0123456789/"
        CompData "p_inicio_data", "Início", "<=", "p_fim_data", "Término"
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
  ElseIf InStr("IA",O) > 0 Then
     BodyOpen "onLoad=document.Form.w_sq_tipo_afastamento.focus();"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad=document.Form.p_sq_tipo_afastamento.focus();"
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
     ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Colaborador","nome_resumido") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Localização","local") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Tipo do afastamento","nm_tipo_afastamento") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Início","inicio_data") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Término","fim_data") & "</font></td>"
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
         ShowHTML "        <td align=""left""><font size=""1"">" & ExibeColaborador("", w_cliente, RS("sq_pessoa"), TP, RS("nome_resumido")) & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("local"), RS("sq_unidade"), TP) & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nm_tipo_afastamento") & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio_data")) & " - " & RS("inicio_periodo") & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim_data")) & " - " & RS("fim_periodo") & "</td>"
         ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """>Alterar </A>&nbsp"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & " &P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """>Excluir </A>&nbsp"
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
     SelecaoTipoAfastamento "<u>T</u>ipo do afastamento:", "T", null, w_sq_tipo_afastamento, null, "w_sq_tipo_afastamento", "ativo = 'S'", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_contrato_colaborador'; document.Form.submit();"""
     If w_sq_tipo_afastamento > "" and O <> "E" Then
        ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=""1"">Afastamento informado em"
        If RS1("periodo") = "A" Then
           ShowHTML " <b>datas</b>."
        ElseIf RS1("periodo") = "D" Then
           ShowHTML " <b>dias</b>."
        ElseIf RS1("periodo") = "H" Then
           ShowHTML " <b>horas</b>."
        End If
        ShowHTML " Limitado a <b>" & RS1("limite_dias") & "</b> dias " 
        If RS1("contagem_dias") = "U" Then
           ShowHTML " <b>úteis</b>."
        ElseIf RS1("contagem_dias") = "C" Then
           ShowHTML " <b>corridos</b>."
        End If
        ShowHTML " Aplica-se" 
        If RS1("sexo") = "A" Then
           ShowHTML "<b>a ambos os sexos</b>,"
        ElseIf RS1("sexo") = "M" Then
           ShowHTML "<b>apenas ao sexo masculino</b>,"
        ElseIf RS1("sexo") = "F" Then
           ShowHTML "<b>apenas ao sexo feminino</b>,"
        End If
        ShowHTML " contratado nas modalidades "
        If Nvl(RS1("nm_modalidade"),"") > "" Then
           w_modalidades = w_modalidades & "<b>" & trim(RS1("nm_modalidade")) & "</b>"
           RS1.MoveNext
           If Not RS1.EOF Then
              While Not RS1.EOF 
                 w_modalidades = w_modalidades & ", <b>" & RS1("nm_modalidade") & "</b>"
                 RS1.MoveNext
              Wend
           End If
           ShowHTML w_modalidades
           RS1.MoveFirst
        End If
        If cDbl(RS1("percentual_pagamento")) = 100 Then
           ShowHTML " tendo <b>remuneração integral</b> durante o afastamento.</div>"
        Else
           ShowHTML " tendo <b>" & RS1("percentual_pagamento") & "% da remuneração</b> durante o afastamento.</div>"
        End If
     End If
     ShowHTML "      <tr>"
     SelecaoColaborador "<u>C</u>olaborador:", "C", null, w_sq_contrato_colaborador, w_sq_tipo_afastamento, "w_sq_contrato_colaborador", "SELAFAST", null
     ShowHTML "      <tr><td><table width=""100%"" border=""0"">"
     ShowHTML "      <tr><td width=""10%"" valign=""top""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio_data"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(w_inicio_data) & """ onKeyDown=""FormataData(this,event);""></td>"
     ShowHTML "          <td valign=""top""><font size=""1""><b>Período?</b><br>"
     If w_inicio_periodo = "T" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_inicio_periodo"" value=""M""> Manhã <input " & w_Disabled & " type=""radio"" name=""w_inicio_periodo"" value=""T"" checked> Tarde"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_inicio_periodo"" value=""M"" checked> Manhã <input " & w_Disabled & " type=""radio"" name=""w_inicio_periodo"" value=""T""> Tarde"
     End If     
     ShowHTML "</table></td></tr>"
     If Nvl(w_sq_tipo_afastamento,"") > "" and O <> "E" Then 
        If RS1("periodo") = "A" Then
           ShowHTML "      <tr><td><table width=""100%"" border=""0"">"
           ShowHTML "      <tr><td width=""10%"" valign=""top""><font size=""1""><b>Té<u>r</u>mino:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_fim_data"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(w_fim_data) & """ onKeyDown=""FormataData(this,event);""></td>"
           ShowHTML "          <td valign=""top""><font size=""1""><b>Período?</b><br>"
           If w_fim_periodo = "M" Then
              ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_fim_periodo"" value=""M"" checked> Manhã <input " & w_Disabled & " type=""radio"" name=""w_fim_periodo"" value=""T""> Tarde"
           Else
              ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_fim_periodo"" value=""M""> Manhã <input " & w_Disabled & " type=""radio"" name=""w_fim_periodo"" value=""T"" checked> Tarde"
           End If     
           ShowHTML "</table></td></tr>"
        ElseIf RS1("periodo") = "D" Then
           ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>úmero de dias:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_dias"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_dias & """></td>"
           ShowHTML "<INPUT type=""hidden"" name=""w_fim_periodo"" value=""T"">"
        ElseIf RS1("periodo") = "H" Then
           ShowHTML "<INPUT type=""hidden"" name=""w_fim_data"" value=""T"">"
           ShowHTML "<INPUT type=""hidden"" name=""w_fim_periodo"" value=""T"">"
        End If
     End If
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><u>O</u>bservação:<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" name=""w_observacao"" rows=""5"" cols=""75"">" & w_observacao & "</textarea></td>"
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
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=P" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
     AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,"L"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os critérios que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     SelecaoTipoAfastamento "<u>T</u>ipo do afastamento:", "T", null, p_sq_tipo_afastamento, null, "p_sq_tipo_afastamento", "AFASTAMENTO", null
     ShowHTML "      <tr>"
     SelecaoColaborador "<u>C</u>olaborador:", "C", null, p_sq_contrato_colaborador, null, "p_sq_contrato_colaborador", "AFASTAMENTO", null
     ShowHTML "      <tr><td><font size=""1""><b><u>P</u>eríodo de busca:</b><br> De: <input accesskey=""P"" type=""text"" name=""p_inicio_data"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_inicio_data & """ onKeyDown=""FormataData(this,event);""> a <input accesskey=""P"" type=""text"" name=""p_fim_data"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_data & """ onKeyDown=""FormataData(this,event);""></td>"
     ShowHTML "      <tr><td align=""center"" colspan=5><hr>"
     ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"     
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
  Set w_sq_tipo_afastamento     = Nothing 
  Set w_sq_contrato_colaborador = Nothing
  Set w_inicio_data             = Nothing 
  Set w_inicio_periodo          = Nothing
  Set w_fim_data                = Nothing 
  Set w_fim_periodo             = Nothing
  Set w_dias                    = Nothing  
  Set w_observacao              = Nothing    
  Set p_sq_tipo_afastamento     = Nothing
  Set p_sq_contrato_colaborador = Nothing
  Set p_inicio_data             = Nothing
  Set p_fim_data                = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de modalidades de contratação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de busca dos colaboradores
REM -------------------------------------------------------------------------
Sub BuscaColaborador
 
  Dim w_nome, w_cliente, w_chave, chaveAux, restricao, campo
  
  w_nome     = UCase(Request("w_nome"))
  w_cliente  = Request("w_cliente")
  w_chave    = Request("w_chave")
  chaveAux   = Request("ChaveAux")
  restricao  = Request("restricao")
  campo      = Request("campo")
  
  DB_GetGPColaborador RS, w_cliente, null, w_nome, null, null, null, null, null, null, null, null, null, null, null, chaveAux, restricao
  RS.Sort = "nome_resumido"
    
  Cabecalho
  ShowHTML "<TITLE>Seleção de colaborador</TITLE>"
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "JavaScript"
  ShowHTML "  function volta(l_chave) {"
  ShowHTML "     opener.Form." & campo & ".value=l_chave;"
  ShowHTML "     opener.Form." & campo & ".focus();"
  ShowHTML "     window.close();"
  ShowHTML "     opener.focus();"
  ShowHTML "   }"
  ValidateOpen "Validacao"
  Validate "w_nome", "Nome", "1", "1", "3", "60", "1", "1"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_nome.focus();'"
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  AbreForm  "Form", w_dir&w_Pagina&"BuscaColaborador", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null
  ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"
  ShowHTML "<INPUT type=""hidden"" name=""chaveAux"" value=""" & chaveAux &""">"
  ShowHTML "<INPUT type=""hidden"" name=""restricao"" value=""" & restricao &""">"
  ShowHTML "<INPUT type=""hidden"" name=""campo"" value=""" & campo &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu &""">"
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome do colaborador.<li>Quando a relação for exibida, selecione o colaborador desejado clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome do colaborador, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome do colaborador:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""60"" value=""" & w_nome & """>"   
  
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
  ShowHTML "            <input class=""stb"" type=""button"" name=""Botao"" value=""Cancelar"" onClick=""window.close(); opener.focus();"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</form>"
  If w_nome > "" Then
     ShowHTML "<tr><td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td>"
     ShowHTML "    <TABLE WIDTH=""100%"" border=0>"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""1""><b>Nome resumido</font></td>"
        ShowHTML "            <td><font size=""1""><b>Localização</font></td>"
        ShowHTML "            <td><font size=""1""><b>Operações</font></td>"
        ShowHTML "          </tr>"
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "            <td><font size=""1"">" & RS("nome_resumido") & "</td>"
           ShowHTML "            <td><font size=""1"">" & RS("local") & "</td>"
           ShowHTML "            <td><font size=""1""><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("sq_contrato_colaborador") & "');"">Selecionar</a>"
           RS.MoveNext
        wend
        ShowHTML "        </table></tr>"
        ShowHTML "      </center>"
        ShowHTML "    </table>"
        ShowHTML "  </td>"
        ShowHTML "</tr>"
     End If
     DesConectaBD	 
  End If
  DesConectaBD	 
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set w_nome                = Nothing
  Set chaveAux              = Nothing
      
End Sub
REM =========================================================================
REM Fim da rotina de busca de área do conhecimento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tela de exibição do colaborador
REM -------------------------------------------------------------------------
Sub TelaColaborador
  Dim w_sq_pessoa
    
  w_sq_pessoa          = Request("w_sq_pessoa")
  
  DB_GetGPColaborador RS, w_cliente, w_sq_pessoa, null, null, null, null, null, null, null, null, null, null, null, null, null, null
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>Colaborador</TITLE>"
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  TP = "Dados coloborador"
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
  Estrutura_Texto_Fecha

  Set w_sq_pessoa           = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de visão de usuário a centros de custo
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
   Dim w_erro
  
   Cabecalho
   BodyOpen "onLoad=document.focus();"
   
   AbreSessao    
   Select Case SG
      Case "GPAFAST"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            If Instr("AI",O) > 0 Then
               w_erro = ValidaAfastamento(w_cliente, Request("w_chave"), Request("w_sq_contrato_colaborador"), Request("w_inicio_data"), Request("w_fim_data"), Request("w_inicio_periodo"), Request("w_fim_periodo"), Request("w_dias"))
               If w_erro > "" Then
                  ShowHTML "<HR>"
                  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
                  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2>"
                  ShowHTML "<font color=""#BC3131""><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível a conclusão da operação."
                  ShowHTML "<UL>" & w_erro & "</UL>"
                  ShowHTML "</font></td></tr></table>"
                  ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
                  Rodape
                  Exit Sub
               End If 
            End If
            DML_PutAfastamento O, Request("w_chave"), w_cliente, Request("w_sq_tipo_afastamento"), Request("w_sq_contrato_colaborador"), _
                               Request("w_inicio_data"), Request("w_inicio_periodo"), Request("w_fim_data"), Request("w_fim_periodo"), _
                               Request("w_dias"), Request("w_observacao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
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
    Case "AFASTAMENTO"       Afastamento
    Case "BUSCACOLABORADOR"  BuscaColaborador
    Case "TELACOLABORADOR"   TelaColaborador
    Case "GRAVA"             Grava
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