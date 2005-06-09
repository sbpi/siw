<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Gerencial.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE FILE="DB_Lancamento.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/DB_Contrato.asp" -->
<!-- #INCLUDE FILE="DML_Lancamento.asp" -->
<!-- #INCLUDE FILE="ValidaLancamento.asp" -->
<!-- #INCLUDE FILE="VisualLancamento.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_contas.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
REM Mail     : celso@sbpi.com.br
REM Criacao  : 21/04/2004 11:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa, w_ano
Dim ul,File
Dim w_pag, w_linha
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

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
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Rel_contas.asp?par="
w_Dir        = "mod_fn/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then 
   If par= "INICIAL" Then
      O = "P"
   Else 
      O = "L"
   End If
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
  Case "C"
     w_TP = TP & " - Cópia"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)
w_ano             = 2005

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
Set w_ano         = Nothing

REM =========================================================================
REM Relatório de contas a pagar e contas a receber
REM -------------------------------------------------------------------------
Sub Inicial
  Dim p_dt_ini, p_dt_fim, p_ordena, p_nome, w_sq_pessoa
  Dim w_valor, w_valor_total
  Dim w_atual, w_logo, w_titulo
  Dim w_tipo_rel, w_linha, w_pag
  
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  p_dt_ini          = uCase(trim(Request("p_dt_ini")))
  p_dt_fim          = uCase(trim(Request("p_dt_fim")))
  p_nome            = uCase(trim(Request("p_nome")))
  w_sq_pessoa       = uCase(trim(Request("w_sq_pessoa")))
  p_ordena          = uCase(trim(Request("p_ordena")))
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "files\" & w_cliente & "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetLancamento RS, w_cliente, Mid(SG, 1, 3), p_dt_ini, p_dt_fim, w_sq_pessoa, "EE,ER"
     RS.Sort = p_ordena
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord "Portrait"
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     If Mid(SG, 3, 1) = "R" Then
        ShowHTML "Contas a receber"     
     ElseIf Mid(SG, 3, 1) = "D" Then
        ShowHTML "Contas a pagar"
     End If
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório de contas</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
        Validate "p_nome", "Nome", "", "1", "3", "20", "1", ""
        ShowHTML "  theForm.Botao.value = ""Procurar"";"
        ShowHTML "}"
        ShowHTML "else {"
        Validate "p_dt_ini", "Vencimento inicial", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "p_dt_fim", "Vencimento final", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "p_dt_ini", "Vencimento inicial", "<=", "p_dt_fim", "Vencimento final"
        ShowHTML "  if (theForm.p_dt_ini.value == '' && theForm.w_sq_pessoa.value == '') {"
        ShowHTML "     alert ('Informe pelo menos um criterio de filtragem!');"
        ShowHTML "     theForm.p_dt_ini.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        Validate "p_ordena", "Agregar por", "SELECT", "1", "1", "30", "1", "1"
        ShowHTML "}"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        If Mid(SG, 3, 1) = "R" Then
           ShowHTML "Contas a receber"     
        ElseIf Mid(SG, 3, 1) = "D" Then
           ShowHTML "Contas a pagar"
        End If
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_dt_ini.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    w_filtro = ""
    If p_dt_ini                > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Vencimento de <td><font size=1><b>" & p_dt_ini & "</b> até <b>" & p_dt_fim & "</b>"End If
    If w_sq_pessoa             > "" Then 
       If Mid(SG, 3, 1) = "R" Then
          w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Cliente<td><font size=1>: <b>" & p_nome  & "</b>"    
       ElseIf Mid(SG, 3, 1) = "D" Then
          w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Fornecedor<td><font size=1>: <b>" & p_nome  & "</b>"    
       End If
    End If
    If p_ordena                > "" Then 
       w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Agregado por<td><font size=1>: <b>"
       Select Case p_ordena
          Case "VENCIMENTO"
              w_filtro = w_filtro & "Vencimento" 
          Case "SQ_PESSOA"
              If Mid(SG, 3, 1) = "R" Then
                 w_filtro = w_filtro & "Cliente"
              ElseIf Mid(SG, 3, 1) = "D" Then
                 w_filtro = w_filtro & "Fornecedor"
              End If
          Case "NM_TRAMITE"
             w_filtro = w_filtro & "Situação"
       End Select
       w_filtro = w_filtro & "</b>"                 
    End If
    ShowHTML "<tr><td align=""left"" colspan=2>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"        End If
    ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Vencto.</font></td>"
    If Mid(SG, 3, 1) = "R" Then
       ShowHTML "       <td><font size=""1""><b>Cliente</font></td>"
    ElseIf Mid(SG, 3, 1) = "D" Then
       ShowHTML "       <td><font size=""1""><b>Fornecedor</font></td>"
    End If
    ShowHTML "          <td><font size=""1""><b>Histórico</font></td>"
    ShowHTML "          <td><font size=""1""><b>Prazo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_valor        = 0.00
      w_valor_total  = 0.00
      w_atual        = ""
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_linha > 22 and w_tipo_rel = "WORD" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 5
           w_pag   = w_pag + 1
           ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
           If Mid(SG, 3, 1) = "R" Then
              ShowHTML "Contas a receber"     
           ElseIf Mid(SG, 3, 1) = "D" Then
              ShowHTML "Contas a pagar"
           End If
           ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
           ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
           ShowHTML "</TD></TR>"
           ShowHTML "</FONT></B></TD></TR></TABLE>"
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
           w_filtro = ""
           If p_dt_ini                > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Vencimento de <td><font size=1><b>" & p_dt_ini & "</b> até " & p_dt_fim End If
           If p_sq_pessoa             > "" Then 
              If Mid(SG, 3, 1) = "R" Then
                 w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Cliente<td><font size=1>: <b>" & RS("nome_resumido")  & "</b>"    
              ElseIf Mid(SG, 3, 1) = "D" Then
                 w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Fornecedor<td><font size=1>: <b>" & RS("nome_resumido")  & "</b>"    
              End If
           End If
           If p_ordena                > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Agregado por <td><font size=1>: <b>" & p_ordena & "</b>"                 End If
           ShowHTML "<tr><td align=""left"" colspan=2>"
           If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
           ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
           ShowHTML "<tr><td align=""center"" colspan=3>"
           ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           ShowHTML "          <td><font size=""1""><b>Código</font></td>"
           ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
           ShowHTML "          <td><font size=""1""><b>Vencto.</font></td>"
           If Mid(SG, 3, 1) = "R" Then
              ShowHTML "       <td><font size=""1""><b>Cliente</font></td>"
           ElseIf Mid(SG, 3, 1) = "D" Then
              ShowHTML "       <td><font size=""1""><b>Fornecedor</font></td>"
           End If
           ShowHTML "          <td><font size=""1""><b>Histórico</font></td>"
           ShowHTML "          <td><font size=""1""><b>Prazo</font></td>"
           ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
           ShowHTML "        </tr>"
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If Nvl(w_atual,"") > "" Then 
           Select Case p_ordena
              Case "VENCIMENTO"
                 If Nvl(w_atual,"") <> RS("vencimento") Then
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                    ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do dia </td>"
                    ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
                    ShowHTML "      </tr>"
                    w_valor = 0.00
                    w_linha = w_linha + 1
                 End If
              Case "SQ_PESSOA"
                 If cDbl(Nvl(w_atual,0)) <> cDbl(Nvl(RS("sq_pessoa"),0)) Then
                    If Mid(SG, 3, 1) = "R" Then
                       ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do cliente </td>"
                    ElseIf Mid(SG, 3, 1) = "D" Then
                       ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do fornecedor </td>"
                    End If
                    ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
                    ShowHTML "      </tr>"
                    w_valor = 0.00
                    w_linha = w_linha + 1
                 End If
              Case "NM_TRAMITE"
                 If Nvl(w_atual,"") <> RS("nm_tramite") Then
                    ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total da situação <b>" & w_atual & "</b></td>"
                    ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
                    ShowHTML "      </tr>"
                    w_valor = 0.00
                    w_linha = w_linha + 1                                  
                 End If
           End Select
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=1>"
        If Nvl(RS("conclusao"),"nulo") = "nulo" Then
           If RS("fim") < Date() Then
              ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
              ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        Else
           If RS("vencimento") < Nvl(RS("quitacao"),RS("vencimento")) Then
              ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        End If
        If Nvl(w_tipo_rel,"") = "WORD" Then
           ShowHTML "        " & RS("codigo_interno") & "&nbsp;"
        Else
           ShowHTML "        <A class=""hl"" HREF=""" & w_dir & "Lancamento.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""" & RS("descricao") & """>" & RS("codigo_interno") & "&nbsp;</a>"
        End If
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("vencimento")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_pessoa_resumido") & "</td>"
        If Nvl(RS("cd_acordo"),"") > "" Then
           ShowHTML "        <td><font size=""1""><A class=""hl"" HREF=""" & "mod_ac/Contratos.asp?par=Visual&O=L&w_chave=" & RS("sq_acordo") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=GC" & Mid(SG,3,1) & "CAD"" title=""" & RS("objeto") & """ target=""_blank"">" & RS("cd_acordo") & "</a> - " & RS("descricao") & "</td>"
        Else
           ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
        End If
        ShowHTML "        <td align=""right""><font size=""1"">" & RS("prazo") & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "</td>"
        ShowHTML "</tr>"
        w_valor       = w_valor       + cDbl(RS("valor"))        
        w_valor_total = w_valor_total + cDbl(RS("valor"))

        w_linha = w_linha + 1        
        Select Case p_ordena
           Case "VENCIMENTO" 
              w_atual = RS("vencimento")
           Case "SQ_PESSOA"
              w_atual = RS("sq_pessoa")
           Case "NM_TRAMITE"
              w_atual = RS("nm_tramite")
        End Select
        
        RS.MoveNext
      wend
      Select Case p_ordena
         Case "VENCIMENTO"
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do dia </td>"
            ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
            ShowHTML "      </tr>"
            w_valor = 0.00
            w_linha = w_linha + 1
        Case "SQ_PESSOA"
           If Mid(SG, 3, 1) = "R" Then
              ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do cliente </td>"
           ElseIf Mid(SG, 3, 1) = "D" Then
              ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total do fornecedor </td>"
           End If
           ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
           ShowHTML "      </tr>"
           w_valor = 0.00
           w_linha = w_linha + 1
        Case "NM_TRAMITE"
           ShowHTML "        <td colspan=5 align=""right"" height=18><font size=""1""><b>Total da situação <b>" & w_atual & "</b></td>"
           ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor,2) & "</b></td>"
           ShowHTML "      </tr>"
           w_valor = 0.00
           w_linha = w_linha + 1                                  
        End Select
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=6><font size=1>&nbsp;</td></tr>"
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""center"" height=30>"
      ShowHTML "        <td colspan=5 align=""right""><font size=""2""><b>Totais do relatório</td>"
      ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_valor_total,2) & "</b></td>"
      w_linha = w_linha + 1
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Contas",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>V</u>encimento entre:</b><br><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""p_dt_ini"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(p_dt_ini,First_Day(Date())) & """ onKeyDown=""FormataData(this,event);"">" & ExibeCalendario("Form", "p_dt_ini") & " e <input " & w_Disabled & " type=""text"" name=""p_dt_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(p_dt_fim,Last_Day(Date())) & """ onKeyDown=""FormataData(this,event);"">" & ExibeCalendario("Form", "p_dt_fim") & "</td>"
    ShowHTML "      <tr><td valign=""top""><font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""p_nome"" VALUE=""" & p_nome & """ SIZE=""20"" MaxLength=""20"">"
    ShowHTML "              <INPUT class=""stb"" TYPE=""button"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.O.value='P'; document.Form.target=''; if (Validacao(document.Form)) {document.Form.submit();}"">"
    If p_nome > "" Then
       DB_GetBenef RS, w_cliente, null, null, null, p_nome, null, null, null
       RS.Sort = "nm_pessoa"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>essoa:</b><br><SELECT ACCESSKEY=""P"" CLASS=""STS"" NAME=""w_sq_pessoa"">"
       ShowHTML "          <option value="""">---"
       While Not RS.EOF
          If cDbl(RS("sq_tipo_pessoa")) = 1 Then
             ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("nome_resumido") & " (" & Nvl(RS("cpf"),"---") & ")"
          Else
             ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("nome_resumido") & " (" & Nvl(RS("cnpj"),"---") & ")"
          End If
          RS.MoveNext
       Wend
       ShowHTML "          </select>"
    End If
    ShowHTML "      <tr>"
    SelecaoOrdenaRel "<u>A</u>gregado por:", "A", null, w_cliente, p_ordena,  "p_ordena", SG, null
    ShowHTML "      </table>"
    ShowHTML "    <table width=""99%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
 
  Set p_dt_ini                  = Nothing 
  Set p_dt_fim                  = Nothing 
  Set p_nome                    = Nothing 
  Set w_sq_pessoa               = Nothing 
  Set p_ordena                  = Nothing 
  Set w_atual                   = Nothing 
  Set w_valor                   = Nothing 
  Set w_valor_total             = Nothing 
  Set w_logo                    = Nothing 
  Set w_titulo                  = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIAL"
       Inicial
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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