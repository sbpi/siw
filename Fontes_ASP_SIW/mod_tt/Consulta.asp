<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Tarifacao.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Consulta.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualListaTel.asp" -->
<!-- #INCLUDE FILE="VisualResumoLigacaoParticular.asp" -->

<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Gerenciar tabelas básicas do módulo	
REM Mail     : Beto@sbpi.com.br
REM Criacao  : 07/07/2004 10:40
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
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_chaveAux, w_sq_usuario_central
Dim w_sq_pessoa
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
O = "R"  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cInt(Nvl(Request("P3"),1))
P4           = cInt(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Consulta.asp?par="
w_Dir        = "mod_tt/"
w_Disabled   = "ENABLED"

If O = "" then
  If par="PARTICULAR" Then 
    O = "R"
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
  Case "F"
    w_TP = TP & " - Finalizar"
  Case "V" 
    w_TP = TP & " - Envio"
  Case "H" 
    w_TP = TP & " - Herança"
  Case "R" 
    w_TP = TP & " - Resumo"
  Case Else
    w_TP = TP & " - Listagem"
End Select

w_cliente            = RetornaCliente()
w_usuario            = RetornaUsuario()
w_sq_usuario_central = RetornaUsuarioCentral()

If SG <> "TTUSUCTRL" and SG <> "TTTRONCO" then
  w_menu         = RetornaMenu(w_cliente, SG) 
Else
  w_menu         = RetornaMenu(w_cliente, Request("w_SG")) 
End If

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
If SG <> "TTUSUCTRL" and SG <> "TTTRONCO" then
  DB_GetLinkSubMenu RS, Session("p_cliente"), SG
Else
  DB_GetLinkSubMenu RS, Session("p_cliente"), Request("w_SG")
End IF

If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD


' Recupera a configuração do serviço
DB_GetMenuData RS_menu, w_menu
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set w_chave              = Nothing
Set w_copia              = Nothing
Set w_filtro             = Nothing
Set w_menu               = Nothing
Set w_usuario            = Nothing
Set w_cliente            = Nothing
Set w_filter             = Nothing
Set w_cor                = Nothing
Set ul                   = Nothing
Set File                 = Nothing
Set w_sq_pessoa          = Nothing
Set w_troca              = Nothing
Set w_submenu            = Nothing
Set w_reg                = Nothing

Set RS                   = Nothing
Set RS1                  = Nothing
Set RS2                  = Nothing
Set RS3                  = Nothing
Set RS4                  = Nothing
Set RS_menu              = Nothing
Set Par                  = Nothing
Set P1                   = Nothing
Set P2                   = Nothing
Set P3                   = Nothing
Set P4                   = Nothing
Set TP                   = Nothing
Set SG                   = Nothing
Set R                    = Nothing
Set O                    = Nothing
Set w_Classe             = Nothing
Set w_Cont               = Nothing
Set w_Pagina             = Nothing
Set w_Disabled           = Nothing
Set w_TP                 = Nothing
Set w_Assinatura         = Nothing
Set w_sq_usuario_central = Nothing


REM =========================================================================
REM Rotina de informação de ligações
REM -------------------------------------------------------------------------
Sub LigacaoParticular

  Dim w_sq_ligacao, w_sq_cc, w_sq_acordo, w_assunto, w_imagem, w_ativo, w_trabalhow_sq_central_telefonica, w_nome_usuario
  Dim w_outra_parte_contato, w_fax, w_soma, w_destino, w_responsavel, w_recebida, w_entrante, w_cor_fonte, w_negrito
  Dim p_sq_cc, p_sq_acordo, p_ativo, p_outra_parte_contato, p_Ordena, p_numero, p_inicio, p_fim, w_sq_central_telefonica
  
  w_sq_ligacao            = Request("w_sq_ligacao")
  w_nome_usuario          = Request("w_nome_usuario")
  p_sq_cc                 = uCase(Request("p_sq_cc"))
  p_outra_parte_contato   = uCase(Request("p_outra_parte_contato"))
  p_ativo                 = uCase(Request("p_ativo"))
  p_inicio                = uCase(Request("p_inicio"))
  p_fim                   = uCase(Request("p_fim"))
  p_numero                = uCase(Request("p_numero"))
  p_ordena                = uCase(Request("p_ordena"))
  
  If w_troca > "" Then
    w_sq_ligacao          = Request("w_sq_ligacao")
    w_sq_cc               = Request("w_sq_cc")
    w_sq_acordo           = Request("w_sq_acordo")
    w_assunto             = Request("w_assunto")
    w_ativo               = Request("w_ativo")
    w_imagem              = Request("w_imagem")
    w_fax                 = Request("w_fax")
    w_trabalho            = Request("w_trabalho")
    w_outra_parte_contato = Request("w_outra_parte_contato")
    If p_Ordena = "" Then 
      If P1 = 3 Then RS.Sort = "ordem desc" Else RS.Sort = "ordem" End If
    Else
      If P1 = 3 Then RS.Sort = p_ordena & ", ordem desc" Else RS.Sort = p_ordena & ", ordem" End If
    End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("R",O) > 0 Then
    ScriptOpen "JavaScript"
    CheckBranco
    FormataData
    ValidateOpen "Validacao"
    If O = "R" Then
      ShowHTML "  if (theForm.p_fim.value.length > 0 && theForm.p_inicio.value.length == 0) {"
      ShowHTML "     alert('Não é permitido informar apenas a data final!');"
      ShowHTML "     theForm.p_fim.focus();"
      ShowHTML "     return false;"
      ShowHTML "   }"
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
    Else
      Validate "p_inicio", "Data inicial", "DATA", "", "10", "10", "", "0123456789/"
      Validate "p_fim", "Data final", "DATA", "", "10", "10", "", "0123456789/"
      CompData "p_inicio", "Data inicial", "<=", "p_fim", "Data final"
    End If
    If P1 = 3 Then ' Se for arquivo
      ShowHTML "  if (theForm.p_fim.value == '' && theForm.p_inicio.value == '') {"
      ShowHTML "     alert('É necessário informar um critério de filtragem!');"
      ShowHTML "     return false;"
      ShowHTML "   }"
    End If
    Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
  End If
  If O = "R" Then
    ShowHTML "  theForm.Botao.disabled=true;"
  Else
    ShowHTML "  theForm.Botao[0].disabled=true;"
    ShowHTML "  theForm.Botao[1].disabled=true;"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
    BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("R",O) > 0 Then
    BodyOpen "onLoad='document.Form.p_inicio.focus()';"
  Else
    BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("R",O) > 0 Then
    ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O & """>"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td><table width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left""><td><table cellpadding=0 cellspacing=0><tr valign=""center"">"
    ShowHTML "          <td><font size=""1""><b>Período</b>(formato DD/MM/AAAA):&nbsp;&nbsp;</td>"
    If p_inicio = "" Then
      ShowHTML "          <td><font size=""1""><b><U>D</U>e: <INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inicio"" size=""10"" maxlength=""10"" value=""01/" & Mid(100+DatePart("m",Date()),2,2) & "/" & DatePart("yyyy",Date()) & """ onKeyDown=""FormataData(this,event)"">&nbsp;</td>"
      ShowHTML "          <td><font size=""1""><b>A<U>t</U>é: <INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_fim"" size=""10"" maxlength=""10"" value=""" & FormataDataEdicao(Date()) & """ onKeyDown=""FormataData(this,event)""></td>"
    Else
      ShowHTML "          <td><font size=""1""><b><U>D</U>e: <INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_inicio"" size=""10"" maxlength=""10"" value=""" & p_inicio & """ onKeyDown=""FormataData(this,event)"">&nbsp;</td>"
      ShowHTML "          <td><font size=""1""><b>A<U>t</U>é: <INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_fim"" size=""10"" maxlength=""10"" value=""" & p_fim & """ onKeyDown=""FormataData(this,event)""></td>"
    End If
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"

    If p_inicio > "" Then
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><hr>"
      If P1 <> 3 Then ' Se não for arquivo
        DB_GetCall RS, null, null, P1, "PESSOAS", null, null, null, p_inicio, p_fim, p_ativo
        RS.Sort="dura_tot desc"
        ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2><b>Resumo comparativo por ligações particulares</b>&nbsp;&nbsp;&nbsp;"
        ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
        ShowHTML "    <TABLE WIDTH=""90%"" align=""center"" BORDER=1 CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "        <tr align=""center"">"
        ShowHTML "          <td rowspan=2><font size=""1""><b>Pessoa     </font></td>"
        ShowHTML "          <td colspan=4><font size=""1""><b>Quantidade </font></td>"
        ShowHTML "          <td colspan=4><font size=""1""><b>Duração    </font></td>"
        ShowHTML "        <tr align=""center"">"
        ShowHTML "          <td><font size=""1""><b>ORI</font></td>"
        ShowHTML "          <td><font size=""1""><b>REC</font></td>"
        ShowHTML "          <td><font size=""1""><b>NAT</font></td>"
        ShowHTML "          <td><font size=""1""><b>TOT</font></td>"
        ShowHTML "          <td><font size=""1""><b>ORI</font></td>"
        ShowHTML "          <td><font size=""1""><b>REC</font></td>"
        ShowHTML "          <td><font size=""1""><b>NAT</font></td>"
        ShowHTML "          <td><font size=""1""><b>TOT</font></td>"
        If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
        Else
          w_cor = conTrAlternateBgColor
          While Not RS.EOF
            If RS("trabalho") = "Particular" Then
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              ShowHTML "      <tr bgcolor=""" & w_cor & """>"
              ShowHTML "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & RS("ori_qtd") & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & RS("rec_qtd") & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & RS("nat_qtd") & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & RS("qtd_tot") & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & FormataTempo(cDbl(RS("ori_dura"))) & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & FormataTempo(cDbl(RS("rec_dura"))) & "&nbsp;</td>"
              ShowHTML "        <td align=""right""><font size=""1"">" & FormataTempo(cDbl(RS("nat_dura"))) & "&nbsp;</td>"
              ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "RESUMPART&R=" & w_Pagina & par & "&O=L&w_sq_usuario=" & RS("usuario") & "&p_inicio=" & p_inicio & "&p_fim=" & p_fim & "&p_ativo=S&w_nome_usuario=" & RS("nm_completo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ Target=""_blank"" Title=""Exibe resumo detalhado"">" & FormataTempo(cDbl(RS("dura_tot"))) & "</A>&nbsp"
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

  Set w_responsavel           = Nothing
  Set w_recebida              = Nothing
  Set w_entrante              = Nothing
  Set w_sq_central_telefonica = Nothing
  Set w_destino               = Nothing
  Set w_soma                  = Nothing
  Set w_fax                   = Nothing
  Set w_imagem                = Nothing
  Set w_sq_ligacao            = Nothing
  Set w_sq_cc                 = Nothing
  Set w_sq_acordo             = Nothing
  Set w_assunto               = Nothing
  Set w_ativo                 = Nothing
  Set w_outra_parte_contato   = Nothing

  Set p_inicio                = Nothing
  Set p_fim                   = Nothing
  Set p_numero                = Nothing
  Set p_ativo                 = Nothing
  Set p_sq_cc                 = Nothing
  Set p_sq_acordo             = Nothing
  Set p_outra_parte_contato   = Nothing
  Set p_Ordena                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de informação de ligações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub listaTelefonica

  Dim w_erro, w_logo

  If P2 = 1 Then
    Response.ContentType = "application/msword"
  Else 
    cabecalho
  End If
  
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Lista Telefônica</TITLE>"
  ShowHTML "</HEAD>" 
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If P2 = 0 Then 
    BodyOpen "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR>"
  If P2 = 0 Then
    DB_GetCustomerData RS, w_cliente
    ShowHTML "  <TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""files/" & w_cliente & "/img/logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30) & """>"
    DesconectaBD
  End If
  ShowHTML "  <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "Lista Telefônica"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B>"
  If P2 = 0 Then
    ShowHTML "&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&P2=1&SG=" & SG & "','VisualListaTelWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  If P2 = 0 Then
    ShowHTML "<HR>"
  End If
  
  ' Chama a função de visualização dos dados do usuário, na opção "Listagem"
  
  VisualListaTel w_cliente
  
  If P2 = 0 Then
    Rodape
  End If
  
  Set w_erro = Nothing 
  Set w_logo = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub ResumoLigacaoParticular

  Dim w_erro, w_logo

  If P2 = 1 Then
    Response.ContentType = "application/msword"
  Else 
    cabecalho
  End If
  
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Lista Telefônica</TITLE>"
  ShowHTML "</HEAD>" 
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If P2 = 0 Then 
     BodyOpen "onLoad='document.focus()'; "
  End If
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR>"
  If P2 = 0 Then
     DB_GetCustomerData RS, w_cliente
     ShowHTML "  <TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""files/" & w_cliente & "/img/logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30) & """>"
     DesconectaBD
  End If
  ShowHTML "  <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "Resumo de Ligações Particulares"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B>"
  If P2 = 0 Then
    ShowHTML "&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&P2=1&SG=" & SG & "','VisualResumoLigacaoParticularWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  If P2 = 0 Then
    ShowHTML "<HR>"
  End If
  
  ' Chama a função de visualização dos dados das ligações particulares efetuadas pelo usuário, na opção "Listagem"

  ResumLigPart Request("w_sq_usuario"), Request("p_inicio"), Request("p_fim"), Request("p_ativo"), Request("O")
  
  If P2 = 0 Then
    Rodape
  End If
  
  Set w_erro = Nothing 
  Set w_logo = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------


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
    Case "LISTATEL"   ListaTelefonica
    Case "PARTICULAR" LigacaoParticular
    Case "RESUMPART"  ResumoLigacaoParticular
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