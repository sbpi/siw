<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tarifacao.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tarifacao.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de localização
REM Mail     : alex@sbpi.com.br
REM Criacao  : 10/06/2003, 15:20
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
Dim dbms, sp, RS, RS2
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor, w_ano
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao, w_negrito, w_cor_fonte
Dim w_Assinatura, w_Cliente, w_Classe, w_Usuario, w_sq_usuario_central
Dim w_dir, w_dir_volta
Private Par

AbreSessao
Set RS = Server.CreateObject("ADODB.RecordSet")
Set RS2= Server.CreateObject("ADODB.RecordSet")

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),1)
P2           = Request("P2")
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Tarifacao.asp?par="
w_Disabled   = "ENABLED"
w_cor_fonte  = "color=""#000000"""

If O = "" Then 
   If P1 = 3 Then O = "P" Else O = "L" End If
End If

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
     w_TP = TP & " - Resumo"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente            = RetornaCliente()
w_usuario            = RetornaUsuario()
w_sq_usuario_central = RetornaUsuarioCentral()

Main

FechaSessao

Set w_dir                = Nothing
Set w_sq_usuario_central = Nothing
Set w_cor_fonte          = Nothing
Set w_negrito            = Nothing
Set w_cor                = Nothing
Set w_classe             = Nothing
Set w_usuario            = Nothing
Set w_cliente            = Nothing
Set RS                   = Nothing
Set RS2                  = Nothing
Set Par                  = Nothing
Set P1                   = Nothing
Set P2                   = Nothing
Set P3                   = Nothing
Set P4                   = Nothing
Set TP                   = Nothing
Set SG                   = Nothing
Set R                    = Nothing
Set O                    = Nothing
Set w_ImagemPadrao       = Nothing
Set w_Imagem             = Nothing
Set w_Titulo             = Nothing
Set w_ContOut            = Nothing
Set w_Cont               = Nothing
Set w_Pagina             = Nothing
Set w_Disabled           = Nothing
Set w_TP                 = Nothing
Set w_troca              = Nothing
Set w_Assinatura         = Nothing

REM =========================================================================
REM Rotina de informação de ligações
REM -------------------------------------------------------------------------
Sub Informar

  Dim w_sq_ligacao
  Dim w_sq_cc, p_sq_cc
  Dim w_assunto, w_imagem
  Dim w_ativo, p_ativo
  Dim w_trabalho
  Dim w_outra_parte_contato, p_outra_parte_contato
  Dim p_Ordena
  Dim w_fax
  Dim w_soma
  Dim w_destino, w_responsavel
  Dim w_sq_central_telefonica, w_recebida, w_entrante
  Dim p_numero, p_inicio, p_fim
  Dim p_assunto, w_texto

  w_titulo              = ""
  w_sq_ligacao          = Request("w_sq_ligacao")
  p_sq_cc               = uCase(Request("p_sq_cc"))
  p_outra_parte_contato = uCase(Request("p_outra_parte_contato"))
  p_ativo               = uCase(Request("p_ativo"))
  p_inicio              = uCase(Request("p_inicio"))
  p_fim                 = uCase(Request("p_fim"))
  p_numero              = uCase(Request("p_numero"))
  p_ordena              = uCase(Request("p_ordena"))
  p_assunto             = uCase(Request("p_assunto"))
  
  If O = "P" and P1 = 3 Then
     ' Se for a tela de pesquisa do módulo gerencial, configura a busca inicial para os últimos trinta dias
     If p_inicio = "" Then
        p_inicio = FormataDataEdicao(Date()-30)
        p_fim    = FormataDataEdicao(Date())
     End If
  End If
  
  If w_troca > "" Then
     w_sq_ligacao           = Request("w_sq_ligacao")
     w_sq_cc                = Request("w_sq_cc")
     w_assunto              = Request("w_assunto")
     w_ativo                = Request("w_ativo")
     w_imagem               = Request("w_imagem")
     w_fax                  = Request("w_fax")
     w_trabalho             = Request("w_trabalho")
     w_outra_parte_contato  = Request("w_outra_parte_contato")
  ElseIf O = "L" Then
     DB_GetCall RS, null, w_usuario, P1, null, p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
     If p_Ordena = "" Then 
        If P1 = 3 Then RS.Sort = "ordem desc" Else RS.Sort = "ordem" End If
     Else
        If P1 = 3 Then RS.Sort = p_ordena & ", ordem desc" Else RS.Sort = p_ordena & ", ordem" End If
     End If
  Else
     If O = "I" or O = "A" or O = "E" Then
        ' Recupera os dados da ligação
        DB_GetCall RS, w_sq_ligacao, w_usuario, P1, "REGISTRO", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo

        w_sq_cc                 = cDbl(Nvl(RS("sq_cc"),0))
        w_assunto               = RS("assunto")
        w_imagem                = RS("imagem")
        w_fax                   = RS("fax")
        w_trabalho              = RS("trabalho")
        w_outra_parte_contato   = RS("outra_parte_cont")
        w_responsavel           = RS("responsavel")
        w_sq_central_telefonica = cDbl(Nvl(RS("SQ_CENTRAL_FONE"),0))
     
        If O = "A" Then
           w_titulo = "Selecione a pessoa responsável pela ligação e informe alguma observação para orientá-lo."
        ElseIf IsNull(w_trabalho) Then
           DB_GetCall RS, null, w_usuario, P1, "HERANCA", p_sq_cc, p_outra_parte_contato, RS("numero"), p_inicio, p_fim, p_ativo

           If Not RS.EOF Then
              w_sq_cc                = cDbl(Nvl(RS("sq_cc"),0))
              w_assunto              = RS("assunto")
              w_imagem               = RS("imagem")
              w_fax                  = RS("fax")
              w_trabalho             = RS("trabalho")
              w_outra_parte_contato  = RS("outra_parte_cont")
              w_titulo               = "ATENÇÃO: Dados importados da última ligação informada! Você pode editá-los ou mantê-los como estão.<br>Não se esqueça de gravá-los para efetivar as informações."
           End If
        End If
        DesconectaBD
     End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("IAEPR",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("I",O) > 0 Then
        ShowHTML "  if (theForm.w_trabalho[0].checked) {"
        Validate "w_sq_cc", "Classificação", "SELECT", "1", "1", "18", "1", "1"
        Validate "w_outra_parte_contato", "Pessoa de contato", "1", "1", "3", "60", "1", "1"
        Validate "w_assunto", "Assunto", "1", "1", "4", "1000", "1", "1"
        ShowHTML "   }"
        ShowHTML "   else {"
        ShowHTML "      theForm.w_sq_cc.selectedIndex=0;"
        Validate "w_outra_parte_contato", "Outra parte", "1", "", "3", "60", "1", "1"
        Validate "w_assunto", "Assunto", "1", "", "4", "1000", "1", "1"
        ShowHTML "   }"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf InStr("A",O) > 0 Then
        Validate "w_destino", "Pessoa", "HIDDEN", "1", "1", "18", "1", "1"
        Validate "w_assunto", "Observação", "1", "1", "4", "500", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O="P" or O = "R" Then
        Validate "p_sq_cc", "Classificação", "SELECT", "", "1", "3", "1", "1"
        Validate "p_outra_parte_contato", "Nome da outra parte", "1", "", "2", "50", "1", "1"
        Validate "p_numero", "Número", "1", "", "2", "20", "", "0123456789"
        ShowHTML "  if (theForm.p_fim.value.length > 0 && theForm.p_inicio.value.length == 0) {"
        ShowHTML "     alert('Não é permitido informar apenas a data final!');"
        ShowHTML "     theForm.p_fim.focus();"
        ShowHTML "     return false;"
        ShowHTML "   }"
        If O = "R" Then
           Validate "p_inicio", "Data inicial", "DATA", "1", "10", "10", "", "0123456789/"
           Validate "p_fim", "Data final", "DATA", "1", "10", "10", "", "0123456789/"
           CompData "p_inicio", "Data inicial", "<=", "p_fim", "Data final"
           ShowHTML "  var w_data, w_data1, w_data2;"
           ShowHTML "  w_data = theForm.p_inicio.value;"
           ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
           ShowHTML "  w_data1  = new Date(Date.parse(w_data));"
           ShowHTML "  w_data = theForm.p_fim.value;"
           ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
           ShowHTML "  w_data2= new Date(Date.parse(w_data));"
           ShowHTML "  var MinMilli = 1000 * 60;"
           ShowHTML "  var HrMilli = MinMilli * 60;"
           ShowHTML "  var DyMilli = HrMilli * 24;"
           ShowHTML "  var Days = Math.round(Math.abs((w_data2 - w_data1) / DyMilli));"
           'ShowHTML "  if (Days > 60) {"
           'ShowHTML "     alert('O intervalo não pode ser superior a 60 dias!');"
           'ShowHTML "     theForm.p_inicio.focus();"
           'ShowHTML "     return false;"
           'ShowHTML "  }"
        Else
           Validate "p_inicio", "Data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_fim", "Data final", "DATA", "", "10", "10", "", "0123456789/"
           CompData "p_inicio", "Data inicial", "<=", "p_fim", "Data final"
        End If
        If P1 = 3 Then ' Se for arquivo
           ShowHTML "  if (theForm.p_sq_cc.selectedIndex == 0 && theForm.p_outra_parte_contato.value == '' && theForm.p_numero.value == '' && theForm.p_fim.value == '' && theForm.p_inicio.value == '') {"
           ShowHTML "     alert('É necessário informar um critério de filtragem!');"
           ShowHTML "     return false;"
           ShowHTML "   }"
        End If
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     If O = "E" or O = "R" Then
        ShowHTML "  theForm.Botao.disabled=true;"
     Else
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("A",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_destino.focus()';"
  ElseIf InStr("PR",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_sq_cc.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If P1 <> 3 Then ' Se não for inclusão
       ShowHTML "  <a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_sq_cc & p_outra_parte_contato & p_ativo & p_numero & p_inicio & p_fim & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</font></a>"
    End If
    If P1 <> 3 Then ' Se não for inclusão
       ShowHTML "                         <a accesskey=""R"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=R&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>R</u>esumo</a>&nbsp;"
    End If
    ShowHTML "    <td align=""right""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><b>" & LinkOrdena("Tipo","tipo") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Data","data") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Número","numero") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Duração","duracao") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("RM","sq_ramal") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Local","localidade") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Trab","d_trabalho") & "</td>"
    If P1 = 3 Then ' Se for arquivo
       ShowHTML "          <td><b>" & LinkOrdena("Resp.","responsavel") & "</td>"
    End If
    ShowHTML "          <td><b>" & LinkOrdena("De","d_nome") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Classificação","d_cc") & "</td>"
    If Nvl(p_assunto,"N") = "S" Then ' Se for selecionada a visualização do assunto
       ShowHTML "          <td><b>" & LinkOrdena("Assunto","assunto") & "</td>"
    End If
    ShowHTML "          <td><b>Operações</td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=12 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        w_cor_fonte = "color=""#000000"""
        If IsNull(RS("trabalho")) and RS("sq_usuario_central") > "" Then 
           w_negrito = "<b>"
           If cDbl(Nvl(RS("sq_usuario_central"),0)) <> cDbl(Nvl(w_sq_usuario_central,0)) Then w_cor_fonte = "color=""#0011FF""" End If
        Else 
           w_negrito = "" 
        End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font " & w_cor_fonte & ">" & RS("tipo") & "</td>"
        ShowHTML "        <td nowrap align=""center""><font " & w_cor_fonte & ">" & w_negrito & RS("data") & "</td>"
        ShowHTML "        <td><font " & w_cor_fonte & ">" & RS("numero") & "</td>"
        ShowHTML "        <td align=""center""><font " & w_cor_fonte & ">" & FormataTempo(cDbl(RS("duracao"))) & "&nbsp;</td>"
        ShowHTML "        <td align=""center""><font " & w_cor_fonte & ">" & RS("sq_ramal") & "</td>"
        ShowHTML "        <td><font " & w_cor_fonte & ">" & RS("localidade") & "</td>"
        ShowHTML "        <td align=""center""><font " & w_cor_fonte & ">" & RS("d_trabalho") & "</td>"
        If P1 = 3 Then ' Se for arquivo
           ShowHTML "        <td><font " & w_cor_fonte & ">" & nvl(RS("responsavel"),"---") & "</td>"
        End If
        ShowHTML "        <td><font " & w_cor_fonte & ">" & RS("d_nome") & "</td>"
        ShowHTML "        <td><font " & w_cor_fonte & ">" & RS("d_cc") & "</td>"
        If Nvl(p_assunto,"N") = "S" Then
           If Nvl(RS("trabalho"),"N") = "S" Then
              ShowHTML "        <td><font " & w_cor_fonte & ">" & nvl(RS("assunto"),"---") & "</td>"
           Else
              ShowHTML "        <td><font " & w_cor_fonte & ">*** Privativo</td>"
           End If
        End If
        ShowHTML "        <td align=""top"" nowrap>"
        If P1 = 3 and Nvl(RS("trabalho"),"N") = "N" Then
           ShowHTML "          ---&nbsp"
        ElseIf RS("trabalho") > "" Then
		   ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_ligacao=" & RS("sq_ligacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Exibir</A>&nbsp"
        Else
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_ligacao=" & RS("sq_ligacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Informar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_ligacao=" & RS("sq_ligacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Transferir</A>&nbsp"
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        w_soma  = w_soma + Int(cDbl(RS("duracao")))
        RS.MoveNext
      wend
      If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
      ShowHTML "      <tr bgcolor=""" & w_cor & """>"
      ShowHTML "        <td align=""right"" colspan=3><b>Duração total:</td>"
      ShowHTML "        <td align=""center""><b>" & FormataTempo(w_soma) & "&nbsp;</td>"
      If Nvl(p_assunto,"N") = "N" Then
         ShowHTML "        <td colspan=7>&nbsp;</td>"
      Else
         ShowHTML "        <td colspan=8>&nbsp;</td>"
      End If
      ShowHTML "      </tr>"
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
    DesconectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If
    DB_GetCall RS, w_sq_ligacao, w_usuario, P1, "DADOS", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr valign=""top""><td colspan=3>Tipo da ligação: <b>" & RS("tipo") & "</td></tr>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td>Nº:<br><b>" & RS("numero") & "</td>"
    ShowHTML "          <td>Data:<br> <b>" & FormatDateTime(RS("data"),1) & ", " & FormatDateTime(RS("data"),3) & "</td>"
    ShowHTML "          <td align=""right"">Duração:<br><b>" & FormataTempo(cDbl(RS("duracao"))) & "</td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td>Ramal:<br><b>" & RS("sq_ramal") & "</td>"
    ShowHTML "          <td>Tronco:<br> <b>" & RS("sq_tronco") & "</td>"
    ShowHTML "          <td align=""right"">Valor:<br><b>" & FormatNumber(RS("valor"),2) & "</td>"
    ShowHTML "    </TABLE>"
    ' Verifica se houve transferências da ligação, exibindo-as se existirem
    DB_GetCall RS2, w_sq_ligacao, w_usuario, P1, "LOG", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
    If Not RS2.EOF Then
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center""><td colspan=4><b>Transferências da ligação</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>Data</td>"
       ShowHTML "          <td><b>Origem</td>"
       ShowHTML "          <td><b>Destino</td>"
       ShowHTML "          <td><b>Observação</td>"
       While Not RS2.EOF
          ShowHTML "        <tr valign=""top"">"
          ShowHTML "          <td  align=""center"" nowrap> " & FormatDateTime(RS("data"),2) & "</td>"
          ShowHTML "          <td>" & RS2("origem") & "</td>"
          ShowHTML "          <td>" & RS2("destino") & "</td>"
          ShowHTML "          <td>" & RS2("observacao") & "</td>"
          RS2.MoveNext
       Wend
       RS2.Close
       ShowHTML "    </TABLE>"
    End If
    'DesconectaBD
    ShowHTML "</table>"
    ShowHTML "<FORM action=""" & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_cc"" value=""" & p_sq_cc &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_outra_parte_contato"" value=""" & p_outra_parte_contato &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_numero"" value=""" & p_numero &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_inicio"" value=""" & p_inicio &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_fim"" value=""" & p_fim &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_ligacao"" value=""" & w_sq_ligacao &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""95%"" border=""0"">"
    ShowHTML "      <tr><td align=""center""><font color=""#FF0000""><b>" & w_titulo & "</b></td></tr>"
    If O = "A" Then ' Se for transferência de ligação
       ShowHTML "      <tr>"
       SelecaoPessoa "Pe<u>s</u>soa:", "S", "Selecione a pessoa na relação.", w_destino, w_sq_central_telefonica, "w_destino", "TTTRANSFERE"
       ShowHTML "      </tr>"
       ShowHTML "      <tr><td><b><U>O</U>bservação:<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" name=""w_assunto"" rows=""5"" cols=75>" & w_assunto & "</textarea></td>"
    Else ' Outras operações
       ShowHTML "      <tr align=""left""><td><table width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
       MontaRadioNS "<b>Ligação a trabalho?</b>", w_trabalho, "w_trabalho"
       ' Recupera o nome da pessoa de contato e o responsável pela ligação no caso de tarifação telefônica 
       'dentro do mês anterior independente do usuário logado.
       w_texto = ""
       w_texto = "<b>Relação de nomes para este número no mês passado</b>:<br>" & _
                    "<table border=1 width=100% cellpadding=0 cellspacing=0>" & _
                    "<tr><td align=left><b>Nome" & _
                    "    <td><b>Responsável"
       DB_GetCall RS2, null, w_usuario, P1, "HINT", null, null, RS("numero"), "01/" & Mid(100+DatePart("m",Date()),2,2) & "/" & DatePart("yyyy",Date()) & "", FormataDataEdicao(Date()) , "N"
       If Not RS2.EOF Then
          While Not RS2.EOF
             If Instr(w_texto,RS2("d_nome")) = 0 and Nvl(RS2("d_nome"),"nulo") <> "nulo" Then w_texto = w_texto & "<tr><td valign=top align=left>" & RS2("d_nome") & "<td valign=top>" & RS2("responsavel") End If
             RS2.MoveNext
          Wend
          RS2.Close
       End If
       DB_GetCall RS2, null, w_usuario, P1, "HINT", null, null, RS("numero"), "01/" & Mid(100+DatePart("m",Date()),2,2) & "/" & DatePart("yyyy",Date()) & "", FormataDataEdicao(Date()) , "S"
       If Not RS2.EOF Then
          While Not RS2.EOF
              If Instr(w_texto,RS2("d_nome")) = 0 and Nvl(RS2("d_nome"),"nulo") <> "nulo" Then w_texto = w_texto & "<tr><td valign=top align=left>" & RS2("d_nome") & "<td valign=top>" & RS2("responsavel") End If
             RS2.MoveNext
          Wend
          RS2.Close
       End If
       w_texto = w_texto & "</table>"
       MontaRadioNS "<b>Fax?</b>", w_fax, "w_fax"
       ShowHTML "          <td><b>A<U>r</U>quivo:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""STI"" type=""file"" name=""w_imagem"" size=""30"" maxlength=""80""></td>"
       ShowHTML "      </tr></table></td></tr>"
       ShowHTML "      <tr>"
       SelecaoCC "<u>C</u>entro de custo:", "C", "Selecione na lista a classificação à qual a ligação está vinculada.", w_sq_cc, w_sq_central_telefonica, "w_sq_cc", "TTCENTRAL"
       ShowHTML "      </tr>"
       If w_responsavel > "" Then
          ShowHTML "      <tr><td><b>Responsável pela ligação:<br><font size=2>" & w_responsavel & "</td>"
       End If
       ShowHTML "      <tr><td><b><U>P</U>essoa de contato:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_outra_parte_contato"" size=""60"" maxlength=""60"" value=""" & w_outra_parte_contato & """ " & w_Disabled & " TITLE=""" & Replace(w_texto,CHR(13)&CHR(10),"<BR>") & """></td>"
       'ShowHTML "      <tr><td><b><U>P</U>essoa de contato:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_outra_parte_contato"" size=""60"" maxlength=""60"" value=""" & w_outra_parte_contato & """></td>"
       ShowHTML "      <tr><td><b>Assu<U>n</U>to:<br><TEXTAREA ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" name=""w_assunto"" rows=""5"" cols=75>" & w_assunto & "</textarea></td>"
    End If
    If O <> "E" Then
       ShowHTML "      <tr><td valign=""top""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""button"" name=""Botao"" value=""Voltar"" onClick=""document.Form.action='" & R & "'; document.Form.O.value='L'; document.Form.submit();"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "&O=L&p_sq_cc=" & p_sq_cc & "';"" name=""Botao"" value=""Cancelar"">"
    End If
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<FORM action=""" & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""1"">"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""L"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td><table width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    SelecaoCC "<u>C</u>entro de custo:", "C", "Selecione na lista a classificação desejada.", p_sq_cc, w_sq_usuario_central, "p_sq_cc", "TTUSUARIO"
    ShowHTML "          <td valign=""top""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_outra_parte_contato"" size=""40"" maxlength=""40"" value=""" & p_outra_parte_contato & """></td>"
    ShowHTML "          <td valign=""top""><b>N<U>ú</U>mero:<br><INPUT ACCESSKEY=""U"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_numero"" size=""20"" maxlength=""20"" value=""" & p_numero & """></td>"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left""><td><table cellpadding=0 cellspacing=0><tr valign=""center"">"
    ShowHTML "          <td><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>"
    ShowHTML "          <td><b><U>D</U>e: <INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inicio"" size=""10"" maxlength=""10"" value=""" & p_inicio & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_inicio") & "&nbsp;</td>"
    ShowHTML "          <td><b>A<U>t</U>é: <INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_fim"" size=""10"" maxlength=""10"" value=""" & p_fim & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_fim") & "</td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td valign=""top""><b>Ligações:</b><br>"
    If p_Ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S"" checked> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N""> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A""> Ambas <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""""> Não informadas"
    ElseIf p_Ativo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S""> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N"" checked> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A""> Ambas <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""""> Não informadas"
    ElseIf p_Ativo = "A" or (p_ativo = "" and P1 = 3) Then ' Se for arquivo, seleciona ambas como valor inicial
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S""> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N""> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A"" checked> Ambas <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""""> Não informadas"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S""> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N""> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A""> Ambas <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value="""" checked> Não informadas"
    End If
    If Nvl(p_assunto,"N") = "N" and Nvl(P1,3) <> 3 Then
       ShowHTML "      <tr><td><input " & w_Disabled & " type=""checkbox"" name=""p_assunto"" value=""S""> Exibir o assunto das ligações a trabalho</td></tr>"
    Else
       ShowHTML "      <tr><td><input " & w_Disabled & " type=""checkbox"" name=""p_assunto"" value=""S"" checked> Exibir o assunto das ligações a trabalho</td></tr>"
    End If
    ShowHTML "      <tr><td><table cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="D_CC" Then
       ShowHTML "          <option value=""D_CC"" SELECTED>Classificação<option value="""">Data<option value=""LOCALIDADE"">Local<option value=""d_nome"">Nome<option value=""numero"">Número<option value=""SQ_RAMAL"">Ramal"
    ElseIf p_Ordena="LOCALIDADE" Then
       ShowHTML "          <option value=""D_CC"">Classificação<option value="""">Data<option value=""LOCALIDADE"" SELECTED>Local<option value=""d_nome"">Nome<option value=""numero"">Número<option value=""SQ_RAMAL"">Ramal"
    ElseIf p_Ordena="OUTRA_PARTE_CONT" Then
       ShowHTML "          <option value=""D_CC"">Classificação<option value="""">Data<option value=""LOCALIDADE"">Local<option value=""d_nome"" SELECTED>Nome<option value=""numero"">Número<option value=""SQ_RAMAL"">Ramal"
    ElseIf p_Ordena="NUMERO" Then
       ShowHTML "          <option value=""D_CC"">Classificação<option value="""">Data<option value=""LOCALIDADE"">Local<option value=""d_nome"">Nome<option value=""numero"" SELECTED>Número<option value=""SQ_RAMAL"">Ramal"
    ElseIf p_Ordena="SQ_RAMAL" Then
       ShowHTML "          <option value=""D_CC"">Classificação<option value="""">Data<option value=""LOCALIDADE"">Local<option value=""d_nome"">Nome<option value=""numero"">Número<option value=""SQ_RAMAL"" SELECTED>Ramal"
    Else
       ShowHTML "          <option value=""D_CC"">Classificação<option value="""" SELECTED>Data<option value=""LOCALIDADE"">Local<option value=""d_nome"">Nome<option value=""numero"">Número<option value=""SQ_RAMAL"">Ramal"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&p_sq_cc=" & p_sq_cc & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("R",O) > 0 Then
    ShowHTML "<FORM action=""" & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Exibir resumo</i>. Clicando sobre o botão <i>Voltar a informar</i>, o filtro existente será apagado e será exibida a tela com as ligações a informar.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td><table width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    SelecaoCC "<u>C</u>entro de custo:", "C", "Selecione na lista a classificação desejada.", p_sq_cc, w_sq_usuario_central, "p_sq_cc", "TTUSUARIO"
    ShowHTML "          <td valign=""top""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_outra_parte_contato"" size=""40"" maxlength=""40"" value=""" & p_outra_parte_contato & """></td>"
    ShowHTML "          <td valign=""top""><b>N<U>ú</U>mero:<br><INPUT ACCESSKEY=""U"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_numero"" size=""20"" maxlength=""20"" value=""" & p_numero & """></td>"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left""><td><table cellpadding=0 cellspacing=0><tr valign=""center"">"
    ShowHTML "          <td><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>"
    If p_inicio = "" Then
       ShowHTML "          <td><b><U>D</U>e: <INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inicio"" size=""10"" maxlength=""10"" value=""01/" & Mid(100+DatePart("m",Date()),2,2) & "/" & DatePart("yyyy",Date()) & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_inicio") & "&nbsp;</td>"
       ShowHTML "          <td><b>A<U>t</U>é: <INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_fim"" size=""10"" maxlength=""10"" value=""" & FormataDataEdicao(Date()) & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_fim") & "</td>"
    Else
       ShowHTML "          <td><b><U>D</U>e: <INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inicio"" size=""10"" maxlength=""10"" value=""" & p_inicio & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_inicio") & "&nbsp;</td>"
       ShowHTML "          <td><b>A<U>t</U>é: <INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_fim"" size=""10"" maxlength=""10"" value=""" & p_fim & """ onKeyDown=""FormataData(this,event)"">" & ExibeCalendario("Form", "p_fim") & "</td>"
    End If
    ShowHTML "      </table>"
    If P1 = 3 Then ' Se for arquivo
       ShowHTML "      <tr><td valign=""top""><b>Ligações: apenas a trabalho"
       ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""S"">"
    Else
       ShowHTML "      <tr><td valign=""top""><b>Ligações:</b><br>"
       If p_Ativo = "S" Then
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S"" checked> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N""> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A""> Ambas"
       ElseIf p_Ativo = "N" Then
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S""> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N"" checked> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A""> Ambas"
       Else
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""S""> A trabalho <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""N""> Particulares <input " & w_Disabled & " type=""radio"" name=""p_ativo"" value=""A"" checked> Ambas"
       End If
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir resumo"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&p_sq_cc=" & p_sq_cc & "&SG=" & SG & "';"" name=""Botao"" value=""Voltar a informar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"

    If p_inicio > "" Then
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><hr>"

       If P1 <> 3 Then ' Se não for arquivo
            DB_GetCall RS, null, w_usuario, P1, "PESSOAS", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
            RS.Sort="dura_tot desc"
            ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><b>Resumo comparativo por ligações particulares</b>&nbsp;&nbsp;&nbsp;"
            ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
            ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "        <tr align=""center"">"
            ShowHTML "          <td rowspan=2><b>Pessoa</td>"
            ShowHTML "          <td colspan=4><b>Quantidade</td>"
            ShowHTML "          <td colspan=4><b>Duração</td>"
            ShowHTML "        <tr align=""center"">"
            ShowHTML "          <td><b>ORI</td>"
            ShowHTML "          <td><b>REC</td>"
            ShowHTML "          <td><b>NAT</td>"
            ShowHTML "          <td><b>TOT</td>"
            ShowHTML "          <td><b>ORI</td>"
            ShowHTML "          <td><b>REC</td>"
            ShowHTML "          <td><b>NAT</td>"
            ShowHTML "          <td><b>TOT</td>"
            If RS.EOF Then
               ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
            Else
               w_cor = conTrAlternateBgColor
               While Not RS.EOF
                  If RS("trabalho") = "Particular" Then
                     If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                     ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                     ShowHTML "        <td>" & RS("nome_resumido") & "</td>"
                     ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
                     ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("dura_tot"))) & "&nbsp;</td>"
                     ShowHTML "        </td>"
                     ShowHTML "      </tr>"
                  End If
                  RS.MoveNext
               wend
            End If
            ShowHTML "    </TABLE>"
            ShowHTML "    </TD>"
            ShowHTML "</tr>"
            ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><br><br></td></tr>"
            DesconectaBD
       End IF

       DB_GetCall RS, null, w_sq_usuario_central, P1, "GERAL", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><b>Resumo geral</b>&nbsp;&nbsp;&nbsp;<a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>[Exibir ligações]</a>"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td rowspan=2><b>Tipo</td>"
       ShowHTML "          <td colspan=4><b>Quantidade</td>"
       ShowHTML "          <td colspan=4><b>Duração</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          w_cor = conTrBgColor
          While Not RS.EOF
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             ShowHTML "      <tr bgcolor=""" & w_cor & """>"
             ShowHTML "        <td><font " & w_cor_fonte & ">" & RS("trabalho") & "</td>"
             ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
             ShowHTML "        <td align=""right"">" & FormataTempo(cDBl(Nvl(RS("dura_tot"),0))) & "&nbsp;</td>"
             ShowHTML "        </td>"
             ShowHTML "      </tr>"
             RS.MoveNext
          wend
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
       DesconectaBD

       DB_GetCall RS, null, w_sq_usuario_central, P1, "CTCC", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><br><br><b>Resumo por Classificação</b>&nbsp;&nbsp;&nbsp;<a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>[Exibir ligações]</a>"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td rowspan=2><b>Classificação</td>"
       ShowHTML "          <td colspan=4><b>Quantidade</td>"
       ShowHTML "          <td colspan=4><b>Duração</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          w_cor = conTrBgColor
          While Not RS.EOF
             If RS("trabalho") = "Total" Then
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                ShowHTML "        <td>" & RS("sigla") & "</td>"
                ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("dura_tot"))) & "&nbsp;</td>"
                ShowHTML "        </td>"
                ShowHTML "      </tr>"
             End If
             RS.MoveNext
          wend
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
       DesconectaBD

       DB_GetCall RS, null, w_sq_usuario_central, P1, "MES", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><br><br><b>Resumo por mês</b>&nbsp;&nbsp;&nbsp;<a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>[Exibir ligações]</a>"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td rowspan=2><b>Mês/Ano</td>"
       ShowHTML "          <td colspan=4><b>Quantidade</td>"
       ShowHTML "          <td colspan=4><b>Duração</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          w_cor = conTrBgColor
          While Not RS.EOF
             If RS("trabalho") = "Total" Then
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                ShowHTML "        <td align=""center"">" & Mid(RS("mes"),5,2) & "/" & Mid(RS("mes"),1,4) & "</td>"
                ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("dura_tot"))) & "&nbsp;</td>"
                ShowHTML "        </td>"
                ShowHTML "      </tr>"
             End If
             RS.MoveNext
          wend
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
       DesconectaBD

       DB_GetCall RS, null, w_sq_usuario_central, P1, "DIASEMANA", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><br><br><b>Resumo por dia da semana</b>&nbsp;&nbsp;&nbsp;<a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>[Exibir ligações]</a>"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td rowspan=2><b>Dia</td>"
       ShowHTML "          <td colspan=4><b>Quantidade</td>"
       ShowHTML "          <td colspan=4><b>Duração</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          w_cor = conTrBgColor
          While Not RS.EOF
             If RS("trabalho") = "Total" Then
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                ShowHTML "        <td align=""center"">" & RS("dia") & "</td>"
                ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("dura_tot"))) & "&nbsp;</td>"
                ShowHTML "        </td>"
                ShowHTML "      </tr>"
             End If
             RS.MoveNext
          wend
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
       DesconectaBD

       DB_GetCall RS, null, w_sq_usuario_central, P1, "DIAMES", p_sq_cc, p_outra_parte_contato, p_numero, p_inicio, p_fim, p_ativo
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><br><br><b>Resumo por dia do mês</b>&nbsp;&nbsp;&nbsp;<a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_outra_parte_contato=" & p_outra_parte_contato & "&p_sq_cc=" & p_sq_cc & "&p_numero=" & p_numero & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>[Exibir ligações]</a>"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td rowspan=2><b>Dia</td>"
       ShowHTML "          <td colspan=4><b>Quantidade</td>"
       ShowHTML "          <td colspan=4><b>Duração</td>"
       ShowHTML "        <tr align=""center"">"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       ShowHTML "          <td><b>ORI</td>"
       ShowHTML "          <td><b>REC</td>"
       ShowHTML "          <td><b>NAT</td>"
       ShowHTML "          <td><b>TOT</td>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          w_cor = conTrBgColor
          While Not RS.EOF
             If RS("trabalho") = "Total" Then
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                ShowHTML "        <td align=""center"">" & RS("mes") & "</td>"
                ShowHTML "        <td align=""right"">" & RS("ori_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("rec_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("nat_qtd") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & RS("qtd_tot") & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
                ShowHTML "        <td align=""right"">" & FormataTempo(cDbl(RS("dura_tot"))) & "&nbsp;</td>"
                ShowHTML "        </td>"
                ShowHTML "      </tr>"
             End If
             RS.MoveNext
          wend
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
       DesconectaBD

    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_responsavel             = Nothing
  Set w_recebida                = Nothing
  Set w_entrante                = Nothing
  Set w_sq_central_telefonica   = Nothing
  Set w_destino                 = Nothing
  Set w_soma                    = Nothing
  Set w_fax                     = Nothing
  Set w_imagem                  = Nothing
  Set w_sq_ligacao              = Nothing
  Set w_sq_cc                   = Nothing
  Set w_assunto                 = Nothing
  Set w_ativo                   = Nothing
  Set w_trabalho                = Nothing
  Set w_outra_parte_contato     = Nothing
  Set w_texto                   = Nothing
  
  Set p_inicio                  = Nothing
  Set p_fim                     = Nothing
  Set p_numero                  = Nothing
  Set p_ativo                   = Nothing
  Set p_sq_cc                   = Nothing
  Set p_outra_parte_contato     = Nothing
  Set p_Ordena                  = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de informação de ligações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "LIGACAO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_PutCall O, Request("w_sq_ligacao"), Request("w_destino"), Request("w_sq_cc"), Request("w_outra_parte_contato"), _
             Request("w_assunto"), w_usuario, Request("w_fax"), Request("w_trabalho")

          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_cc=" & Request("p_sq_cc") & "&p_outra_parte_contato=" & Request("p_outra_parte_contato") & "&p_numero=" & Request("p_numero") & "&p_inicio=" & Request("p_inicio") & "&p_fim=" & Request("p_fim") & "&p_ativo=" & Request("p_ativo") & "&p_ordena=" & Request("p_ordena") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set w_Null            = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "INFORMAR" Informar
    Case "GRAVA"     Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

