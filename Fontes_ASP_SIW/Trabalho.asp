<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Trabalho.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas do sistema
REM Mail     : alex@sbpi.com.br
REM Criacao  : 24/03/2003 16:55
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
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura, w_cliente, w_usuario, w_cor, w_ano
Dim w_dir_volta
Private Par

AbreSessao
Set RS = Server.CreateObject("ADODB.RecordSet")

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Trabalho.asp?par="
w_Disabled   = "ENABLED"
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_ano        = RetornaAno()

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select
Main

FechaSessao

Set w_ano       = Nothing
Set w_cor       = Nothing
Set w_cliente   = Nothing
Set w_usuario   = Nothing

Set RS          = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set SG          = Nothing
Set R           = Nothing
Set O           = Nothing
Set w_Cont      = Nothing
Set w_Pagina    = Nothing
Set w_Disabled  = Nothing
Set w_TP        = Nothing
Set w_Assinatura= Nothing

REM =========================================================================
REM Controle da mesa de trabalho
REM -------------------------------------------------------------------------
Sub Mesa
  Dim w_workflow, w_telefonia, w_demandas, w_agenda, w_negrito, w_nm_modulo
  Dim w_workflow_qtd, w_telefonia_qtd, w_demandas_qtd, w_agenda_qtd
  
  If O = "L" Then
     ' Verifica se o cliente tem o módulo de telefonia contratado
     DB_GetSiwCliModLis RS, w_cliente, null
     RS.Filter = "sigla='TT'"
     If Not RS.EOF Then w_telefonia = RS("nome") End If
     DesconectaBD

     ' Verifica se o usuário tem acesso ao módulo de telefonia
     DB_GetPersonData RS, w_cliente, w_usuario, null, null
     If IsNull(Tvl(RS("sq_usuario_central"))) Then w_telefonia = "" End If
     DesconectaBD
     

  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300;"">"
  ScriptOpen "JavaScript"
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><font size=""1""><b>Módulo</font></td>"
     ShowHTML "          <td><font size=""1""><b>Serviço</font></td>"
     ShowHTML "          <td><font size=""1""><b>Em aberto</font></td>"
     ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
     ShowHTML "        </tr>"
     If w_workflow & w_telefonia & w_demandas & w_agenda = "" Then
     Else
        If w_telefonia > "" Then
           DB_GetDeskTop_TT RS, w_usuario
           w_telefonia_qtd = RS("existe")
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           If cDbl(RS("existe")) > 0 Then w_negrito = "<b>" Else w_negrito = "" End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """>"
           ShowHTML "        <td><font size=""1"">" & w_telefonia & "</td>"
           ShowHTML "        <td><font size=""1"">" & "Ligações</td>"
           ShowHTML "        <td align=""right""><font size=""1"">" & w_negrito & RS("existe") & "&nbsp;</td>"
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""Tarifacao.asp?par=Informar&R=" & w_Pagina & par & "&O=L&P1=1&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "- Ligações&SG=LIGACAO"">Exibir</A> "
           ShowHTML "        </td>"
           ShowHTML "      </tr>"
           DesconectaBD
        End If

    End If
    
    ' Monta a mesa de trabalho para os outros serviços do SIW
    DB_GetDeskTop RS, w_cliente, w_usuario, w_ano
    If Not RS.EOF Then
       w_nm_modulo = ""
       While Not RS.Eof
          If cDbl(RS("qtd")) > 0 Then w_negrito = "<b>" End If
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          If cDbl(RS("qtd")) > 0 Then w_negrito = "<b>" Else w_negrito = "" End If
          ShowHTML "    <tr bgcolor=""" & w_cor & """>"
          ' Evita que o nome do  módulo seja repetido
          If w_nm_modulo = RS("nm_modulo") Then
             ShowHTML "      <td><font size=""1"">&nbsp;</td>"
          Else
             ShowHTML "      <td><font size=""1"">" & RS("nm_modulo") & "</td>"
             w_nm_modulo = RS("nm_modulo")
          End If
          ShowHTML "      <td><font size=""1"">" & RS("nm_servico") & "</td>"
          ShowHTML "      <td align=""right""><font size=""1"">" & w_negrito & RS("qtd") & "&nbsp;</td>"
          ShowHTML "      <td align=""top"" nowrap><font size=""1"">"
          If Session("interno") = "S" Then
             ' Se for interno, usa P1=2 para indicar mesa de trabalho
             ShowHTML "        <A CLASS=""HL"" HREF=""" & RS("link") & "&P1=2&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&TP&" - "&RS("nm_servico")&"&SG="&RS("sg_servico")&""">Exibir</A>"
          Else
             ' Caso contrário, usa P1=3 para indicar consulta
             ShowHTML "        <A CLASS=""HL"" HREF=""" & RS("link") & "&O=L&P1=6&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&TP&" - "&RS("nm_servico")&"&SG="&RS("sg_servico")&""">Exibir</A>"
          End If
          ShowHTML "      </td>"
          ShowHTML "    </tr>"
          RS.MoveNext
       Wend
    End If
    DesConectaBD	 
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
  ElseIf Instr("AEV",O) > 0 Then
  ElseIf Instr("P",O) > 0 Then
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape 

  Set w_nm_modulo       = Nothing
  Set w_negrito         = Nothing
  Set w_workflow        = Nothing
  Set w_telefonia       = Nothing
  Set w_demandas        = Nothing
  Set w_agenda          = Nothing
  Set w_workflow_qtd    = Nothing
  Set w_telefonia_qtd   = Nothing
  Set w_demandas_qtd    = Nothing
  Set w_agenda_qtd      = Nothing
End Sub
REM =========================================================================
REM Controle da mesa de trabalho
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main

  Select Case Par
    Case "MESA"
       Mesa
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

