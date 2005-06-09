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
REM  /rel_fluxo.asp
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
Dim w_troca,w_cor, w_cor1, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
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

w_Pagina     = "rel_fluxo.asp?par="
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
  Dim p_mes_fluxo, p_ano_fluxo, p_dt_ini, p_dt_fim
  Dim w_valor, w_valor_total
  Dim w_atual, w_logo, w_titulo
  Dim w_tipo_rel, w_linha, w_pag
  Dim w_celula(31,3,2), w_coluna(3,2), w_total(2), i, j, k, w_mes, w_tipo
  
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  p_mes_fluxo       = uCase(trim(Request("p_mes_fluxo")))
  p_ano_fluxo       = uCase(trim(Request("p_ano_fluxo")))
  
  
  If O = "L" Then
     p_dt_ini = First_Day(cDate("01/"&p_mes_fluxo&"/"&p_ano_fluxo))
     p_dt_fim = Last_Day(cDate("27/"&p_mes_fluxo&"/"&p_ano_fluxo))
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "files\" & w_cliente & "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetLancamento RS, w_cliente, SG, p_dt_ini, p_dt_fim, null, "EE,ER"
     RS.Sort = "vencimento, tipo"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord "Portrait"
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     If Instr(SG,"FLUXOPR") > 0 Then
        ShowHTML "Fluxo de Caixa Previsto"     
     ElseIf Instr(SG,"FLUXORE") > 0 Then
        ShowHTML "Fluxo de Caixa Realizado"
     End If
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório do Fluxo de Caixa</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        Validate "p_ano_fluxo", "Ano", "1", "1", "4", "4", "", "0123456789"
        CompValor "p_ano_fluxo", "Ano", ">", "1754", "1754"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        If Instr(SG,"FLUXOPR") > 0 Then
           ShowHTML "Fluxo de Caixa Previsto"     
        ElseIf Instr(SG,"FLUXORE") > 0 Then
           ShowHTML "Fluxo de Caixa Realizado"
        End If
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.Botao[0].focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Dia</font></td>"
    ShowHTML "          <td colspan=2><font size=""2""><b>" & Mid(FormataDataEdicao(FormatDateTime(cDate(p_dt_ini)-50,2)),4,7) & "</font></td>"
    ShowHTML "          <td colspan=2><font size=""2""><b>" & Mid(FormataDataEdicao(FormatDateTime(cDate(p_dt_ini)-1,2)),4,7) & "</font></td>"
    ShowHTML "          <td colspan=2><font size=""2""><b>" & Mid(FormataDataEdicao(FormatDateTime(cDate(p_dt_ini),2)),4,7) & "</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>A pagar</font></td>"
    ShowHTML "          <td><font size=""1""><b>A receber</font></td>"
    ShowHTML "          <td><font size=""1""><b>A pagar</font></td>"
    ShowHTML "          <td><font size=""1""><b>A receber</font></td>"
    ShowHTML "          <td><font size=""1""><b>A pagar</font></td>"
    ShowHTML "          <td><font size=""1""><b>A receber</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_valor        = 0.00
      w_valor_total  = 0.00
      w_atual        = ""
      w_tipo         = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_atual = "" or w_atual <> FormataDataEdicao(FormatDateTime(RS("vencimento"),2)) or (w_tipo = 1 and RS("tipo") = "R") or  (w_tipo = 2 and RS("tipo") = "D") Then
           If w_atual > "" Then
              w_celula(Mid(w_atual,1,2), w_mes, w_tipo) = w_valor
              w_coluna(w_mes, w_tipo) = w_coluna(w_mes, w_tipo) + w_valor
              w_total(w_tipo) = w_total(w_tipo) + w_valor
              w_valor_total = w_valor_total + w_valor
           End If
           
           w_atual = FormataDataEdicao(FormatDateTime(RS("vencimento"),2))
           ' Configura o 2º indice do array
           If cInt(Mid(FormataDataEdicao(FormatDateTime(RS("vencimento"),2)),4,2)) = cInt(Mid(p_dt_fim,4,2)) Then
              w_mes = 3
           Elseif cInt(Mid(FormataDataEdicao(FormatDateTime(RS("vencimento"),2)),4,2)) = cInt(Mid(p_dt_fim,4,2)-1) Then
              w_mes = 2
           Else
              w_mes = 1
           End If
           ' Configura o 3º indice do array
           If RS("tipo") = "D" Then w_tipo = 1 Else w_tipo = 2 End If
           w_valor = 0
        End If
        w_valor = w_valor + cDbl(RS("valor"))
        RS.MoveNext
      Wend
      ' Configura o 2º indice do array
      If Mid(w_atual,4,2) = Mid(p_dt_fim,4,2) Then
         w_mes = 3
      Elseif Mid(w_atual,4,2) = Mid(p_dt_fim,4,2)-1 Then
         w_mes = 2
      Else
         w_mes = 1
      End If
           
      w_celula(Mid(w_atual,1,2), w_mes, w_tipo) = w_valor
      w_coluna(w_mes, w_tipo) = w_coluna(w_mes, w_tipo) + w_valor
      w_total(w_tipo) = w_total(w_tipo) + w_valor
      w_valor_total = w_valor_total + w_valor
      
      for i = 1 to 31
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=1>" & Mid(100+i,2,2)
        for j = 1 to 3
          for k = 1 to 2
             If w_cor = conTrAlternateBgColor Then 
                If k = 1 Then w_cor1 = w_cor Else w_cor1 = "#D5D5D5" End If
             Else
                w_cor1 = w_cor
             End If
             If w_celula(i, j, k) <> 0 Then
                ShowHTML "        <td bgcolor=""" & w_cor1 & """ align=""right""><font size=""1"">" & Nvl(FormatNumber(Abs(w_celula(i, j, k)),2),"&nbsp;") & "</td>"
             Else
                ShowHTML "        <td bgcolor=""" & w_cor1 & """ align=""right""><font size=""1"">&nbsp;</td>"
             End If
          next
        next
      next
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
      ShowHTML "        <td align=""center""><font size=1><b>Totais</b>"
      for j = 1 to 3
        for k = 1 to 2
           ShowHTML "        <td align=""right""><font size=""1"">" & Nvl(FormatNumber(Abs(w_coluna( j, k)),2),"&nbsp;") & "</td>"
        next
      next
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
      ShowHTML "        <td colspan=6 align=""right"" height=18><font size=""1""><b>Total a pagar no período </td>"
      ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(Abs(w_total(1)),2) & "</b></td>"
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
      ShowHTML "        <td colspan=6 align=""right"" height=18><font size=""1""><b>Total a receber no período </td>"
      ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(Abs(w_total(2)),2) & "</b></td>"
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
      ShowHTML "        <td colspan=6 align=""right"" height=18><font size=""1""><b>Saldo do período</td>"
      ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(Abs(w_valor_total),2) & "</b></td>"
      ShowHTML "      </tr>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If w_tipo_rel = "WORD" Then
      ShowHTML "    <br style=""page-break-after:always"">"
    End If
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Fluxo",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table border=""0"">"
    ShowHTML "      <tr>"
    MontaRadioMes "<b>Mês de referência:</b>", Nvl(p_mes_fluxo,Mid(FormataDataEdicao(Date()),4,2)), "p_mes_fluxo"
    ShowHTML "      <td valign=""top""><font size=""1""><b><u>A</u>no:</b><br><input accesskey=""A"" type=""text"" name=""p_ano_fluxo"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & Nvl(p_ano_fluxo,Mid(FormataDataEdicao(Date()),7,4)) & """></td>"
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
 
  Set p_mes_fluxo               = Nothing 
  Set p_ano_fluxo               = Nothing 
  Set p_dt_ini                  = Nothing 
  Set p_dt_fim                  = Nothing 
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