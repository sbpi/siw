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
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerenciar tabelas básicas do módulo	de gestão de pessoal
REM Mail     : celso@sbpi.com.br
REM Criacao  : 25/07/2005 10:00
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
w_Dir        = "mod_rh/"
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
REM Rotina de modalidade de contratacao
REM -------------------------------------------------------------------------
Sub ModalidadeCont
  Dim w_chave, w_nome, w_descricao, w_sigla
  Dim w_ferias, w_username, w_passagem, w_diaria, w_ativo
  
  w_chave         = Request("w_chave")
  w_nome          = Request("w_nome")
  w_descricao     = Request("w_descricao")
  w_sigla         = Request("w_sigla")
  w_ferias        = Request("w_ferias")
  w_username      = Request("w_username")
  w_passagem      = Request("w_passagem")
  w_diaria        = Request("w_diaria")
  w_ativo         = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de modalidades de contratação</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If
  
  If O = "L" Then
    DB_GetGPModalidade RS, w_cliente, null, w_sigla, w_nome, w_ativo, null, null
    RS.Sort = Nvl(p_ordena,"nome")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetGPModalidade RS, w_cliente, w_chave, null, null, null, null, null
    w_chave         = RS("chave")
    w_nome          = RS("nome")
    w_descricao     = RS("descricao")
    w_sigla         = RS("sigla")
    w_ferias        = RS("ferias")
    w_username      = RS("username")
    w_passagem      = RS("passagem")
    w_diaria        = RS("diaria")
    w_ativo         = RS("ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sigla",         "Sigla",                   "1", "1", "2", "10",  "1", ""
        Validate "w_nome",          "Nome",                    "1", "1", "3", "50",  "1", "1"
        Validate "w_descricao",     "Descrição",               "1", "1", "3", "500", "1", "1"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
        If O = "A" Then
           ShowHTML "  if (theForm.w_ativo[1].checked) {"
           ShowHTML "  if (confirm('Modalidades inativas não podem ter vinculação com tipos de afastamento. Se existir algum vínculo, ele será removido. Confirma?'))"
           ShowHTML "     { return (true); }; "
           ShowHTML "     { return (false); }; "
           ShowHTML "  }"
        End If
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
     BodyOpen "onLoad=document.Form.w_sigla.focus();"
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
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Username","username") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Férias","ferias") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Passagem","passagem") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Diária","diaria") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome")   & "</td>"
         If RS("username") = "S" Then
            ShowHTML "        <td align=""center""><font size=""1"">Sempre</td>"
         ElseIf RS("username") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"">Nunca</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">Controlar por pessoa</td>"
         End If
         If RS("ferias") = "S" Then
            ShowHTML "        <td align=""center""><font size=""1"">Sempre</td>"
         ElseIf RS("ferias") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"">Nunca</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">Controlar por pessoa</td>"
         End If
         ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("passagem"))  & "</td>"
         ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("diaria"))  & "</td>"
         If RS("ativo") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"" color=""red"">" & RetornaSimNao(RS("ativo")) & "</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("ativo")) & "</td>"
         End If
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
     ShowHTML "      <tr><td><font size=""1""><b><u>S</u>igla:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_sigla & """></td>"
     ShowHTML "          <td><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" name=""w_descricao"" rows=""5"" cols=75>" & w_descricao & "</textarea></td>"
     ShowHTML "      <tr><td><font size=""1""><b>Cria e bloqueia username na entrada e saida do colaborador?</b><br>"
     If w_username = "N" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_username"" value=""S""> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""N"" checked> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""P""> Controlar por pessoa"
     ElseIf w_username = "P" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_username"" value=""S""> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""N""> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""P"" checked> Controlar por pessoa"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_username"" value=""S"" checked> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""N""> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_username"" value=""P""> Controlar por pessoa"
     End If
     ShowHTML "          <td><font size=""1""><b>Esta modalidade permite gozo de férias?</b><br>"
     If w_ferias = "N" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""S""> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""N"" checked> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""P""> Controlar por pessoa"
     ElseIf w_ferias = "S" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""S"" checked> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""N""> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""P""> Controlar por pessoa"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""S""> Sempre <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""N""> Nunca <br><input " & w_Disabled & " type=""radio"" name=""w_ferias"" value=""P""  checked> Controlar por pessoa"
     End If
     ShowHTML "      <tr valign=""top"">"
     MontaRadioSN "<b>Modalidade permite concessão de passagem?</b>", w_passagem, "w_passagem"
     MontaRadioSN "<b>Modalidade permite pagamento de diárias?</b>", w_diaria, "w_diaria"
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
  Set w_username                = Nothing
  Set w_ferias                  = Nothing 
  Set w_passagem                = Nothing
  Set w_diaria                  = Nothing  
  Set w_ativo                   = Nothing 
  Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de modalidades de contratação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tipos de afastamento
REM -------------------------------------------------------------------------
Sub Tipoafast
  Dim w_chave, w_nome, w_sigla, w_limite_dias
  Dim w_sexo, w_perc_pag, w_contagem_dias, w_periodo, w_sobrepoe_ferias, w_ativo
  
  w_chave            = Request("w_chave")
  w_nome             = Request("w_nome")
  w_sigla            = Request("w_sigla")
  w_limite_dias      = Request("w_limite_dias")
  w_perc_pag         = Request("w_perc_pag")
  w_sexo             = Request("w_sexo")
  w_contagem_dias    = Request("w_contagem_dias")
  w_periodo          = Request("w_periodo")
  w_sobrepoe_ferias  = Request("w_sobrepoe_ferias")
  w_ativo            = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem dos tipos de afastamento</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If
  
  If O = "L" Then
    DB_GetGPTipoAfast RS, w_cliente, null, w_sigla, w_nome, w_ativo, null, null
    RS.Sort = Nvl(p_ordena,"nome")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetGPTipoAfast RS, w_cliente, w_chave, null, null, null, null, null
    w_chave           = RS("chave")
    w_nome            = RS("nome")
    w_sigla           = RS("sigla")
    w_limite_dias     = RS("limite_dias")
    w_perc_pag        = FormatNumber(RS("percentual_pagamento"),2)
    w_sexo            = RS("sexo")
    w_contagem_dias   = RS("contagem_dias")
    w_periodo         = RS("periodo")
    w_sobrepoe_ferias = RS("sobrepoe_ferias")
    w_ativo           = RS("ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sigla",         "Sigla",                   "1", "1", "2", "10",  "1", ""
        Validate "w_nome",          "Nome",                    "1", "1", "3", "50",  "1", "1"
        Validate "w_limite_dias",   "Limite de dias", "VALOR", "1", 1, 6, "", "0123456789"
        Validate "w_perc_pag", "Percentual da remuneração", "VALOR", "1", 4, 18, "", "0123456789.,"
        ShowHTML "  var cont = 0;"   
        ShowHTML "  for (i=0;i<theForm.w_sq_modalidade.length;i++) {"
        ShowHTML "    if (theForm.w_sq_modalidade[i].checked) {"
        ShowHTML "      cont = cont+1;"
        ShowHTML "    }"   
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_ativo[0].checked && cont == 0) {"
        ShowHTML "    alert('Selecione pelo menos um modalidade para o tipo de afastamento!');"
        ShowHTML "    return false; "
        ShowHTML "  } else { if (theForm.w_ativo[1].checked && cont > 0) {"
        ShowHTML "     alert('Não selecione nenhuma modalidade para tipos de afastamento inativos!');"
        ShowHTML "     return false; }; "
        ShowHTML "  }"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
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
     BodyOpen "onLoad=document.Form.w_sigla.focus();"
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
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Limite dias","limite_dias") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Sexo","nm_sexo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("% Pagamento","percentual_pagamento") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome")   & "</td>"
         ShowHTML "        <td align=""right""><font size=""1"">" & RS("limite_dias")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nm_sexo")   & "</td>"
         ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("percentual_pagamento"),2) & "</td>"
         If RS("ativo") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"" color=""red"">" & RetornaSimNao(RS("ativo")) & "</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("ativo")) & "</td>"
         End If
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
     ShowHTML "      <tr><td><font size=""1""><b><u>S</u>igla:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_sigla & """></td>"
     ShowHTML "          <td><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr><td><font size=""1""><b><u>L</u>imite de dias:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_limite_dias"" class=""STI"" SIZE=""6"" MAXLENGTH=""6"" VALUE=""" & w_limite_dias & """></td>"
     ShowHTML "          <td><font size=""1""><b><u>P</u>ercentual da remuneração a ser pago quando afastado por este tipo:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_perc_pag"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_perc_pag & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
     ShowHTML "      <tr><td><font size=""1""><b>Aplica-se ao sexo:</b><br>"
     If w_sexo = "M" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F""> Feminino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M"" checked> Masculino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""A""> Ambos"
     ElseIf w_sexo = "F" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F"" checked> Feminino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M""> Masculino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""A""> Ambos"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F""> Feminino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M""> Masculino <br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""A"" checked> Ambos"
     End If
     ShowHTML "          <td><font size=""1""><b>Contagem dos dias:</b><br>"
     If w_contagem_dias = "U" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_contagem_dias"" value=""C""> Corridos <br><input " & w_Disabled & " type=""radio"" name=""w_contagem_dias"" value=""U"" checked> Úteis"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_contagem_dias"" value=""C"" checked> Corridos <br><input " & w_Disabled & " type=""radio"" name=""w_contagem_dias"" value=""U""> Úteis"
     End If
     ShowHTML "      <tr><td><font size=""1""><b>Informar afastamento em:</b><br>"
     If w_periodo = "D" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""A""> Datas <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""D"" checked> Dias <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""H""> Horas"
     ElseIf w_periodo = "H" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""A""> Datas <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""D""> Dias <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""H"" checked> Horas"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""A"" checked> Datas <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""D""> Dias <br><input " & w_Disabled & " type=""radio"" name=""w_periodo"" value=""H""> Horas"
     End If
     If O = "I" Then
        DB_GetGPModalidade RS1, w_cliente, null, null, null, "S", null, "TPAFASTAMENTO"
        ShowHTML "          <td rowspan=2><font size=""1""><b>Modalidades de contratação vinculadas:</b><br>"
        If Not RS1.EOF Then
           While Not RS1.EOF
              ShowHTML "       <input type=""checkbox"" name=""w_sq_modalidade"" value=""" & RS1("chave") & """>" & RS1("nome") & "<br>"
              RS1.MoveNext
           Wend
        End If
        RS1.Close
     ElseIf O = "A" or O = "E" Then
        DB_GetGPModalidade RS1, w_cliente, null, null, null, "S", w_chave, "TPAFASTAMENTO"
        ShowHTML "          <td rowspan=2><font size=""1""><b>Modalidades de contratação vinculadas:</b><br>"
        If Not RS1.EOF Then
           While Not RS1.EOF
              If Nvl(RS1("sq_tipo_afastamento"),"") > "" Then
                 ShowHTML "       <input " & w_disabled & " type=""checkbox"" name=""w_sq_modalidade"" value=""" & RS1("chave") & """ checked>" & RS1("nome") & "<br>"
              Else
                 ShowHTML "       <input " & w_disabled & " type=""checkbox"" name=""w_sq_modalidade"" value=""" & RS1("chave") & """>" & RS1("nome") & "<br>"
              End If
              RS1.MoveNext
           Wend
        End If
        RS1.Close
     End If
     ShowHTML "      <tr valign=""top"">"
     MontaRadioNS "<b>Sobrepõe gozo de férias?</b>", w_sobrepoe_ferias, "w_sobrepoe_ferias"
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
  Set w_sigla                   = Nothing
  Set w_limite_dias             = Nothing 
  Set w_perc_pag                = Nothing      
  Set w_sexo                    = Nothing
  Set w_contagem_dias           = Nothing 
  Set w_periodo                 = Nothing
  Set w_sobrepoe_ferias         = Nothing  
  Set w_ativo                   = Nothing 
  Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de tipos de afastamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de modalidade de contratacao
REM -------------------------------------------------------------------------
Sub DataEspecial
  Dim w_chave, w_sq_pais, w_co_uf, w_sq_cidade, w_tipo, w_data_especial, w_nome
  Dim w_abrangencia, w_expediente, w_ativo
  
  Dim w_troca
  
  w_chave         = Request("w_chave")
  w_troca         = Request("w_troca")
  
  If w_troca > "" Then
     w_sq_pais       = Request("w_sq_pais")
     w_co_uf         = Request("w_co_uf")
     w_sq_cidade     = Request("w_sq_cidade")
     w_tipo          = Request("w_tipo")
     w_data_especial = Request("w_data_especial")
     w_nome          = Request("w_nome")
     w_abrangencia   = Request("w_abrangencia")
     w_expediente    = Request("w_expediente")
     w_ativo         = Request("w_ativo")
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem das datas especiais</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If
  
  If O = "L" Then
    DB_GetDataEspecial RS, w_cliente, null, null, null, null, null, null
    RS.Sort = Nvl(p_ordena,"data_especial")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetDataEspecial RS, w_cliente, w_chave, null, null, null, null, null
    w_chave         = RS("chave")
    w_sq_pais       = RS("sq_pais")
    w_co_uf         = RS("co_uf")
    w_sq_cidade     = RS("sq_cidade")
    w_tipo          = RS("tipo")
    w_data_especial = RS("data_especial")
    w_nome          = RS("nome")
    w_abrangencia   = RS("abrangencia")
    w_expediente    = RS("expediente")
    w_ativo         = RS("ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     CheckBranco
     FormataDataMA
     FormataData
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_tipo",          "Tipo",                    "SELECT", "1", "1", "1",  "1", ""
        If w_tipo = "I" Then
           Validate "w_data_especial", "Data", "DATADM", 1, 5, 5, "", "0123456789/"
        ElseIf w_tipo = "E" Then
           Validate "w_data_especial", "Data", "DATA", 1, 10, 10, "", "0123456789/"
        End If 
        Validate "w_nome",          "Descrição",               "1", "1", "3", "60",  "1", "1"
        ShowHTML "  if (theForm.w_tipo.value == 'I' && theForm.w_tipo.value == 'E'){ "
           Validate "w_abrangencia",   "Abrangência",             "SELECT", "1", "1", "1",  "1", ""
        ShowHTML "  };"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
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
  If w_troca > "" and w_troca <> "w_data_especial" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_tipo.focus();"
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
     ShowHTML "<tr><td><font size=""2"">"
     ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <a accesskey=""G"" class=""ss"" href=""" & w_dir & w_Pagina & "Grava&R=" & w_Pagina & par & "&O=G&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ onClick=""return(confirm('Confirma geração ou atualização do arquivo de calendário?'))""><u>G</u>erar arquivo</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Data","data_especial") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Descricao","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Tipo","tipo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>Abrangência</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Expediente","expediente") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("data_especial"),"---")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RetornaTipoData(RS("tipo")) & "</td>"
         If Nvl(RS("sq_cidade"),"") > "" Then
            DB_GetCountryData RS1, RS("sq_pais")
            If RS1("padrao") = "S" Then
               DB_GetCityData RS2, RS("sq_cidade")
               ShowHTML "        <td align=""left""><font size=""1"">" & RS2("nome") & " - " & RS2("co_uf") & "</td>"
               RS2.Close
            Else
               DB_GetCityData RS2, RS("sq_cidade")
               ShowHTML "        <td align=""left""><font size=""1"">" & RS2("nome") & " - " & RS1("nome") & "</td>"
               RS2.Close
            End If
            RS1.Close
         ElseIf Nvl(RS("co_uf"),"") > "" Then
            DB_GetCountryData RS1, RS("sq_pais")
            If RS1("padrao") = "S" Then
               DB_GetStateData RS2, RS("sq_pais"), RS("co_uf")
               ShowHTML "        <td align=""left""><font size=""1"">" & RS2("co_uf") & "</td>"
               RS2.Close
            Else
               DB_GetStateData RS2, RS("sq_pais"), RS("co_uf")
               ShowHTML "        <td align=""left""><font size=""1"">" & RS2("co_uf") & " - " & RS1("nome") & "</td>"
               RS2.Close
            End If
            RS1.Close
         ElseIf Nvl(RS("sq_pais"),"") > "" Then
            DB_GetCountryData RS1, RS("sq_pais")
            ShowHTML "        <td align=""left""><font size=""1"">" & RS1("nome") & "</td>"
            RS1.Close
         Else
            ShowHTML "        <td align=""left""><font size=""1"">Internacional</td>"
         End If
         ShowHTML "        <td align=""left""><font size=""1"">" & RetornaExpedienteData(RS("expediente"))  & "</td>"
         If RS("ativo") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"" color=""red"">" & RetornaSimNao(RS("ativo")) & "</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("ativo")) & "</td>"
         End If
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
     ShowHTML "      <tr>" 
     SelecaoTipoData "<u>T</u>ipo:", "T", null, w_tipo, null, "w_tipo", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_data_especial'; document.Form.submit();"""
     If w_tipo = "I" Then
        ShowHTML "          <td><font size=""1""><b>Da<u>t</u>a:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_data_especial"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_data_especial & """ onKeyDown=""FormataDataMA(this,event);""></td>"
     ElseIf w_tipo = "E" Then
        ShowHTML "          <td><font size=""1""><b>Da<u>t</u>a:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_data_especial"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_especial & """ onKeyDown=""FormataData(this,event);""></td>"
     Else
        ShowHTML "          <td><font size=""1""><b>Da<u>t</u>a:</b><br><input Disabled accesskey=""T"" type=""text"" name=""w_data_especial"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_especial & """></td>"
     End If
     ShowHTML "          <td><font size=""1""><b><u>D</u>escrição:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
     ShowHTML "      <tr>" 
     If O <> "E" and Instr("IE",w_tipo) = 0 Then
        w_abrangencia = "N"
        w_Disabled = "DISABLED"
        SelecaoAbrangData "<u>A</u>brangência:", "A", null, w_abrangencia, null, "w_abrangencia", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_abrangencia'; document.Form.submit();"""
        w_Disabled = "ENABLE"
        ShowHTML "<INPUT type=""hidden"" name=""w_abrangencia"" value=""" & w_abrangencia & """>"
     Else     
        SelecaoAbrangData "<u>A</u>brangência:", "A", null, w_abrangencia, null, "w_abrangencia", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_abrangencia'; document.Form.submit();"""
     End If
     If Instr("IO",w_abrangencia) = 0 Then
        If w_abrangencia = "N" Then
           SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, null
        ElseIf w_abrangencia = "E" Then
           SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
           SelecaoEstado "E<u>s</u>tado:", "S", null, w_co_uf, w_sq_pais, "N", "w_co_uf", null, null
        ElseIf w_abrangencia = "M" Then
           SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
           SelecaoEstado "E<u>s</u>tado:", "S", null, w_co_uf, w_sq_pais, "N", "w_co_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_cidade'; document.Form.submit();"""
           SelecaoCidade "<u>C</u>idade:", "C", null, w_sq_cidade, w_sq_pais, w_co_uf, "w_sq_cidade", null, null
        End If
     End If
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "        <td><font size=""1""><b>Expediente?</b><br>"
     If w_expediente = "N" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""N"" checked> Não <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""M""> Somente manhã <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""T""> Somente tarde"
     ElseIf w_expediente = "M" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""N""> Não <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""M"" checked> Somente manhã <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""T""> Somente tarde"
     ElseIf w_expediente = "T" Then
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""N""> Não <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""M""> Somente manhã <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""T"" checked> Somente tarde"
     Else
        ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""S"" checked> Sim <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""N""> Não <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""M""> Somente manhã <br><input " & w_Disabled & " type=""radio"" name=""w_expediente"" value=""T""> Somente tarde"
     End If
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
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
  Set w_sq_pais                 = Nothing 
  Set w_co_uf                   = Nothing
  Set w_sq_cidade               = Nothing 
  Set w_tipo                    = Nothing
  Set w_data_especial           = Nothing 
  Set w_nome                    = Nothing
  Set w_abrangencia             = Nothing  
  Set w_expediente              = Nothing  
  Set w_ativo                   = Nothing 
  Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de datas especiais
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de parâmetros
REM -------------------------------------------------------------------------
Sub Parametros
  Dim w_sq_unidade_gestao, w_admissao_texto, w_admissao_destino, w_rescisao_texto, w_rescisao_destino
  Dim w_feriado_legenda, w_feriado_nome, w_ferias_legenda, w_ferias_nome
  Dim w_viagem_legenda, w_viagem_nome
  

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sq_unidade_gestao       = Request("w_sq_unidade_gestao") 
     w_admissao_texto          = Request("w_admissao_texto") 
     w_admissao_destino        = Request("w_admissao_destino") 
     w_rescisao_texto          = Request("w_rescisao_texto") 
     w_rescisao_destino        = Request("w_rescisao_destino") 
     w_feriado_legenda         = Request("w_feriado_legenda") 
     w_feriado_nome            = Request("w_feriado_nome") 
     w_ferias_legenda          = Request("w_ferias_legenda") 
     w_ferias_nome             = Request("w_ferias_nome") 
     w_viagem_legenda          = Request("w_viagem_legenda") 
     w_viagem_nome             = Request("w_viagem_nome") 
  Else
     DB_GetGPParametro RS, w_cliente, null, null
     If RS.RecordCount > 0 Then 
        w_sq_unidade_gestao         = RS("sq_unidade_gestao") 
        w_admissao_texto            = RS("admissao_texto") 
        w_admissao_destino          = RS("admissao_destino") 
        w_rescisao_texto            = RS("rescisao_texto") 
        w_rescisao_destino          = RS("rescisao_destino") 
        w_feriado_legenda           = RS("feriado_legenda") 
        w_feriado_nome              = RS("feriado_nome") 
        w_ferias_legenda            = RS("ferias_legenda") 
        w_ferias_nome               = RS("ferias_nome") 
        w_viagem_legenda            = RS("viagem_legenda") 
        w_viagem_nome               = RS("viagem_nome") 
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  Validate "w_sq_unidade_gestao", "Unidade gestora de colaboradores", "SELECT", 1, 1, 18, "", "0123456789"
  Validate "w_admissao_destino", "Destinatários da mensagem de entrada", "1", "1", "5", "100", "1", "1"  
  Validate "w_admissao_texto", "Texto comunicando a entrada de coloborador", "1", "1", "3", "1000", "1", "1"
  Validate "w_rescisao_destino", "Destinatários da mensagem de saída", "1", "1", "5", "100", "1", "1"  
  Validate "w_rescisao_texto", "Texto comunicando a saída de coloborador", "1", "1", "3", "1000", "1", "1"
  Validate "w_feriado_legenda", "Legenda do feriado", "1", "1", "1", "2", "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz", ""
  Validate "w_feriado_nome", "Nome do feriado", "1", "1", "3", "30", "1", "1"
  Validate "w_ferias_legenda", "Legenda do ferias", "1", "1", "1", "2", "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz", ""
  Validate "w_ferias_nome", "Nome do ferias", "1", "1", "3", "30", "1", "1"
  Validate "w_viagem_legenda", "Legenda do viagem", "1", "1", "1", "2", "ABCDEFGHIJKLMNOPQRSTUVXYWZabcdefghijklmnopqrstuvxywz", ""
  Validate "w_viagem_nome", "Nome do viagem", "1", "1", "3", "30", "1", "1"
  Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""97%"" border=""0"">"
  SelecaoUnidade "<U>U</U>nidade gestora de colaboradores:", "U", null, w_sq_unidade_gestao, null, "w_sq_unidade_gestao", null, null
  ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Texto de aviso</td></td></tr>"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>estinatários da mensagem de entrada (separar por ponto-e-vírgula):<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_admissao_destino"" size=""90"" maxlength=""100"" value=""" & w_admissao_destino & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>exto comunicando a entrada de colaborador:</b><br><textarea " & w_Disabled & " accesskey=""T"" name=""w_admissao_texto"" class=""STI"" ROWS=5 cols=75 >" & w_admissao_texto & "</TEXTAREA></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>D<u>e</u>stinatários da mensagem de saída (separar por ponto-e-vírgula):<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_rescisao_destino"" size=""90"" maxlength=""100"" value=""" & w_rescisao_destino & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Te<u>x</u>to comunicando a saída de colaborador:</b><br><textarea " & w_Disabled & " accesskey=""X"" name=""w_rescisao_texto"" class=""STI"" ROWS=5 cols=75 >" & w_rescisao_texto & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Dados para o mapa de frequência</td></td></tr>"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">" 
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Evento</td>"
  ShowHTML "          <td valign=""top""><font size=""1""><b>Legenda</td>"
  ShowHTML "          <td valign=""top""><font size=""1""><b>Nome</td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Feriado</td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_feriado_legenda"" size=""4"" maxlength=""2"" value=""" & w_feriado_legenda & """></td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_feriado_nome"" size=""20"" maxlength=""20"" value=""" & w_feriado_nome & """></td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Férias</td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_ferias_legenda"" size=""4"" maxlength=""2"" value=""" & w_ferias_legenda & """></td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_ferias_nome"" size=""20"" maxlength=""20"" value=""" & w_ferias_nome & """></td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Viagem</td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_viagem_legenda"" size=""4"" maxlength=""2"" value=""" & w_viagem_legenda & """></td>"
  ShowHTML "          <td valign=""top""><font size=""1""><INPUT class=""STI"" type=""text"" name=""w_viagem_nome"" size=""20"" maxlength=""20"" value=""" & w_viagem_nome & """></td>"
  ShowHTML "      </tr>" 
  ShowHTML "      </table>"
  ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_unidade_gestao       = Nothing 
  Set w_admissao_texto          = Nothing 
  Set w_admissao_destino        = Nothing 
  Set w_rescisao_texto          = Nothing 
  Set w_rescisao_destino        = Nothing 
  Set w_feriado_legenda         = Nothing 
  Set w_feriado_nome            = Nothing 
  Set w_ferias_legenda          = Nothing 
  Set w_ferias_nome             = Nothing 
  Set w_viagem_legenda          = Nothing 
  Set w_viagem_nome             = Nothing 

  Set w_troca                   = Nothing 
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de parâmetros
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Cargos
REM -------------------------------------------------------------------------
Sub Cargo
  Dim w_chave, w_sq_tipo, w_sq_formacao, w_nome
  Dim w_descricao, w_atividades, w_competencias, w_salario_piso, w_salario_teto, w_ativo
  
  
  w_chave         = Request("w_chave")
  w_sq_tipo       = Request("w_sq_tipo")
  w_sq_formacao   = Request("w_sq_formacao")
  w_nome          = Request("w_nome")
  w_descricao     = Request("w_descricao")
  w_atividades    = Request("w_atividades")
  w_competencias  = Request("w_competencias")
  w_salario_piso  = Request("w_salario_piso")
  w_salario_teto  = Request("w_salario_teto")
  w_ativo         = Request("w_ativo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem dos tipos de afastamento</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If
  
  If O = "L" Then
    DB_GetCargo RS, w_cliente, null, w_sq_tipo, w_nome, w_sq_formacao, w_ativo, null
    RS.Sort = Nvl(p_ordena,"nome")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetCargo RS, w_cliente, w_chave, null, null, null, null, null
    w_chave           = RS("chave")
    w_sq_tipo         = RS("sq_tipo_posto")
    w_sq_formacao     = RS("sq_formacao")
    w_nome            = RS("nome")
    w_descricao       = RS("descricao")
    w_atividades      = RS("atividades")
    w_competencias    = RS("competencias")
    If Nvl(RS("salario_piso"),"") <> "" and Nvl(RS("salario_teto"),"") <> "" then
       w_salario_piso    = FormatNumber(RS("salario_piso"),2)
       w_salario_teto    = FormatNumber(RS("salario_teto"),2)
    End If
    w_ativo           = RS("ativo")
    DesconectaBD
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_tipo",                   "Tipo",  "SELECT", "1", "1",    "1", "1", ""
        Validate "w_sq_formacao", "Formacao Acadêmica",  "SELECT", "1", "1", "1000", "1", ""
        Validate "w_nome",                      "Nome",       "1", "1", "3",   "30", "1", "1"
        Validate "w_descricao",            "Descrição",       "1",  "", "5", "1000", "1", "1"
        Validate "w_atividades",          "Atividades",       "1",  "", "5", "1000", "1", "1"
        Validate "w_competencias",        "Competência",       "1",  "", "5", "1000", "1", "1"
        Validate "w_salario_piso",      "Salário Piso",   "VALOR",  "",   4,     18,  "", "0123456789,."
        Validate "w_salario_teto",      "Salário Teto",   "VALOR",  "",   4,     18,  "", "0123456789,."
        ShowHTML "  if (theForm.w_salario_piso.value != '' && theForm.w_salario_teto.value == '') {"
        ShowHTML "     alert('Informe o teto salarial!');"
        ShowHTML "     theForm.w_salario_teto.focus();"
        ShowHTML "     return (false);"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_salario_piso.value == '' && theForm.w_salario_teto.value != '') {"
        ShowHTML "     alert('Informe o piso salarial!');"
        ShowHTML "     theForm.w_salario_piso.focus();"
        ShowHTML "     return (false);"
        ShowHTML "  }"
        CompValor "w_salario_piso",     "Piso salarial", "<", "w_salario_teto", "teto salarial"
        Validate "w_assinatura",    "Assinatura Eletrônica",   "1", "1", "6", "30",  "1", "1"
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
     BodyOpen "onLoad=document.Form.w_sq_tipo.focus();"
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
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Tipo","nm_tipo_posto") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Formação","nm_formacao") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","ativo") & "</font></td>"
     ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""left"" ONMOUSEOVER=""popup('"&RS("ds_tipo_posto")&"','white')""; ONMOUSEOUT=""kill()""><font size=""1"">" & RS("nm_tipo_posto")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome")   & "</td>"
         ShowHTML "        <td align=""left""><font size=""1"">" & RS("nm_formacao")   & "</td>"
         If RS("ativo") = "N" Then
            ShowHTML "        <td align=""center""><font size=""1"" color=""red"">" & RetornaSimNao(RS("ativo")) & "</td>"
         Else
            ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("ativo")) & "</td>"
         End If
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
     ShowHTML "    <table width=""97%"" border=""0"">"
     ShowHTML "         <tr><td colspan=2><table width=""100%"" border=""0"">"
     SelecaoTipoPosto2 "<u>T</u>ipo:", "T", "Selecione o tipo de cargo.", w_sq_tipo, null, "w_sq_tipo", null
     ShowHTML "           </table>"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "      <tr>"
     ShowHTML "         <td colspan=2 width=""100%""><table width=""100%"" border=""0"">"
     SelecaoFormacao "F<u>o</u>rmação acadêmica:", "O", "Selecione a formação acadêmica mínima, exigida para a ocupação do cargo.", w_sq_formacao, null, "w_sq_formacao", "tipo='Acadêmica'", null
     ShowHTML "            <td><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
     ShowHTML "           </table>"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D""  name=""w_descricao"" class=""sti"" cols=""80"" rows=""4"">" & w_descricao & "</textarea></td>"
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><u>A</u>tividades:</b><br><textarea " & w_Disabled & " accesskey=""A""  name=""w_atividades"" class=""sti"" cols=""80"" rows=""4"">" & w_atividades & "</textarea></td>"
     ShowHTML "      <tr><td colspan=2><font size=""1""><b><u>C</u>ompetências:</b><br><textarea " & w_Disabled & " accesskey=""C""  name=""w_competencias"" class=""sti"" cols=""80"" rows=""4"">" & w_competencias & "</textarea></td>"
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "         <tr><td colspan=2><table width=""100%"" border=""0"">"
     ShowHTML "          <td width=""10%""><font size=""1""><b><u>P</u>iso salarial:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_salario_piso"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_salario_piso & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
     ShowHTML "          <td><font size=""1""><b><u>T</u>eto salarial:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_salario_teto"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_salario_teto & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
     ShowHTML "           </table>"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "      <tr>"
     MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
     ShowHTML "      <tr><td colspan=5><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td colspan=5 align=""center""><hr>"
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
  Set w_sq_tipo                 = Nothing
  Set w_sq_formacao             = Nothing 
  Set w_descricao               = Nothing      
  Set w_atividades              = Nothing
  Set w_salario_piso            = Nothing 
  Set w_salario_teto            = Nothing
  Set w_competencias            = Nothing  
  Set w_ativo                   = Nothing 
  Set w_troca                   = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de Cargos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
   Dim p_sq_endereco_unidade
   Dim p_modulo
   Dim w_Null
   Dim w_mensagem
   Dim FS, F1, F2
   Dim w_chave_nova
  
   Cabecalho
   ShowHTML "</HEAD>"
   BodyOpen "onLoad=document.focus();"
   
   AbreSessao    
   Select Case SG
      Case "GPMODALCON"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            If O = "I" or O = "A" Then
               DB_GetGPModalidade RS, w_cliente,  Nvl(Request("w_chave"),""), Request("w_sigla"), Request("w_nome"), null, null, "VERIFICASIGLANOME"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Já existe modalidade com este nome ou sigla!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If
            ElseIf O = "E" Then
               DB_GetGPModalidade RS, null, Nvl(Request("w_chave"),""), null, null, null, null, "VERIFICAMODALIDADES"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Existe contrato associado a esta modalidade, não sendo possível sua exclusão!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If 
            End If
            DML_PutGPModalidade O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_nome"), Request("w_descricao") , _
                                Request("w_sigla"), Request("w_ferias"), Request("w_username"), Request("w_passagem"), Request("w_diaria"),_
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
      Case "GPTPAFAST"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            If O = "I" or O = "A" Then
               DB_GetGPTipoAfast RS, w_cliente,  Nvl(Request("w_chave"),""), Request("w_sigla"), Request("w_nome"), null, null, "VERIFICASIGLANOME"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Já existe tipo de afastamento com este nome ou sigla!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If
            ElseIf O = "E" Then
               DB_GetGPTipoAfast RS, w_cliente, Nvl(Request("w_chave"),""), null, null, null, null, "VERIFICAAFASTAMENTO"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Existe afastamento cadastrado para este tipo!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If
            End If
            DML_PutGPTipoAfast O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_nome"), Request("w_sigla"), Request("w_limite_dias"), _
                               Request("w_sexo"), Request("w_perc_pag"), Request("w_contagem_dias"), Request("w_periodo"), Request("w_sobrepoe_ferias"), _
                               Request("w_ativo"), Request("w_sq_modalidade")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "EODTESP"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then            
            If O = "G" Then
               Dim w_data, w_ano, w_mes, w_dia, w_lista, w_caminho, w_arq_evento, w_arq_texto

               ' Instancia os arquivos
               Set FS = CreateObject("Scripting.FileSystemObject")

               For w_ano = Year(Date())-2 to Year(Date())+3

                  ' Configura o caminho para gravação física de arquivos
                  w_caminho     = conFilePhysical & w_cliente & "\"
                  w_arq_evento  = w_ano & ".evt"
                  w_arq_texto   = w_ano & ".txt"
                  
                  ' Recupera as datas especiais do ano informado
                  DB_GetDataEspecial RS, w_cliente, null, w_ano, null, null, null, null
                  RS.Sort = "data_formatada"
                  
                  If Not RS.Eof Then
                     w_lista = ""
 
                     ' Gera o arquivo que descreve as datas especiais
                     Set F2 = FS.CreateTextFile(w_caminho & w_arq_evento,true)
                     While Not RS.Eof
                        w_data = FormataDataEdicao(FormatDateTime(RS("data_formatada"),2))
                        w_dia  = cInt(Mid(w_data,1,2))
                        w_mes  = cInt(Mid(w_data,4,2))
                        F2.WriteLine w_mes & " " & w_dia & " """ & RS("nome") & RS("nm_expediente") & """"
                        If RS("expediente") <> "S" Then 
                           w_lista = w_lista & ", " & Mid(w_data,1,5)
                        End If
                        RS.MoveNext
                     Wend
                     F2.Close
                     w_lista = Mid(w_lista,3,Len(w_lista))
                  End If

                  ' Gera o arquivo que indica os dias úteis e não úteis
                  Set F1 = FS.CreateTextFile(w_caminho & w_arq_texto,true)
                  For w_mes = 1 to 12
                     w_linha = ""
                     For w_dia = 1 to 31
                        w_data = Mid(100+w_dia,2,2) & "/" & Mid(100+w_mes,2,2) & "/" & w_ano
                        If IsDate(w_data) Then
                           If WeekDay(cDate(w_data)) = 1 or WeekDay(cDate(w_data)) = 7 or InStr(w_lista, Mid(w_data,1,5)) > 0 Then
                              w_linha = w_linha &  "1"
                           Else
                              w_linha = w_linha &  "0"
                           End If
                        Else
                           w_dia = 32
                        End If
                     Next
                     F1.WriteLine w_linha
                  Next
                  F1.Close
               Next

               Set w_data        = Nothing 
               Set w_ano         = Nothing 
               Set w_mes         = Nothing 
               Set w_dia         = Nothing 
               Set w_lista       = Nothing 
               Set w_caminho     = Nothing 
               Set w_arq_evento  = Nothing 
               Set w_arq_texto   = Nothing
               ScriptOpen "JavaScript"
               ShowHTML "  alert('Arquivos de calendário gerados com sucesso!');"
               ScriptClose
            Else
               DML_PutDataEspecial O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_sq_pais"), Request("w_co_uf"), Request("w_sq_cidade"), _
                                   Request("w_tipo"), Request("w_data_especial"), Request("w_nome"), Request("w_abrangencia"), Request("w_expediente"), _
                                   Request("w_ativo")
            End If
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "GPPARAM"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then            
            DML_PutGPParametro w_cliente, Request("w_sq_unidade_gestao"), Request("w_admissao_texto"), Request("w_admissao_destino"), Request("w_rescisao_texto"), _
                               Request("w_rescisao_destino"), Request("w_feriado_legenda"), Request("w_feriado_nome"), Request("w_ferias_legenda"), Request("w_ferias_nome"), _
                               Request("w_viagem_legenda"), Request("w_viagem_nome")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "EOTIPPOS"
         ' Verifica se a Assinatura Eletrônica é válida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            If O = "I" or O = "A" Then
               DB_GetCargo RS, w_cliente, Nvl(Request("w_chave"),""), null, Request("w_nome"), null, null, "VERIFICANOME"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Já existe cargo com este nome!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If
               DesconectaBD
            ElseIf O = "E" Then
               DB_GetCargo RS, w_cliente, Nvl(Request("w_chave"),""), null, null, null, null, "VERIFICACONTRATO"
               If cDbl(RS("existe")) > 0 Then
                  ScriptOpen "JavaScript"
                  ShowHTML "  alert('Existe contrato de colaborador associado a este cargo, não sendo possível sua exclusão!');"
                  ShowHTML "  history.back(1);"
                  ScriptClose
                  Exit Sub
               End If
            End If
            DML_PutCargo O, Nvl(Request("w_chave"),""), Request("w_cliente"), Request("w_sq_tipo"), Request("w_sq_formacao"), Request("w_nome"),  _
                             Request("w_descricao"), Request("w_atividades"), Request("w_competencias"), Request("w_salario_piso"), Request("w_salario_teto"), _
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
    Case "MODALIDADECONT" ModalidadeCont
    Case "TIPOAFAST"      TipoAfast
    Case "DATAESPECIAL"   DataEspecial
    Case "PARAMETROS"     Parametros
    Case "CARGOS"         Cargo
    Case "GRAVA"          Grava
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