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
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /OR_Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Gerenciar tabelas básicas do módulo	
REM Mail     : Beto@sbpi.com.br
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
Dim w_sq_pessoa
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

w_Pagina     = "OR_Tabelas.asp?par="
w_Dir        = "mod_or_pub/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then 
   If par ="REL_PPA" _
      or par = "REL_INICIATIVA" _ 
      or par = "REL_SINTETICO_IP" _
      or par = "REL_SINTETICO_PPA" Then
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

REM =========================================================================
REM Rotina de iniciativas prioritárias do Governo
REM -------------------------------------------------------------------------
Sub Iniciativa
  Dim w_codigo, w_nome, w_responsavel, w_telefone, w_email, w_ordem, w_ativo, w_padrao
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_codigo               = Request("w_codigo")
     w_nome                 = Request("w_nome")
     w_responsavel          = Request("w_responsavel")
     w_telefone             = Request("w_telefone")
     w_email                = Request("w_email")
     w_ordem                = Request("w_ordem")
     w_ativo                = Request("w_ativo")
     w_padrao               = Request("w_padrao")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetOrPrioridadeList RS, null, w_cliente, null
     RS.Sort = "ordem"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetOrPrioridade RS, null, w_cliente, w_chave,null,null, null
     w_codigo               = RS("codigo")
     w_nome                 = RS("nome")
     w_responsavel          = RS("responsavel")
     w_telefone             = RS("telefone")
     w_email                = RS("email")
     w_ordem                = RS("ordem")
     w_ativo                = RS("ativo")
     w_padrao               = RS("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "2", "100", "1", "1"
        Validate "w_codigo", "Código externo", "1", "", "1", "50", "", "0123456789"
        Validate "w_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        Validate "w_telefone", "Telefone", "1", "", "2", "20", "1", "1"
        Validate "w_email", "e-Mail", "1", "", "5", "60", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "4", "", "0123456789"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
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
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""90"" MAXLENGTH=""100"" VALUE=""" & w_nome & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "           <td><font size=""1""><b><u>C</u>ódigo externo:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_codigo"" class=""sti"" SIZE=""10"" MAXLENGTH=""50"" VALUE=""" & w_codigo & """></td>"
    ShowHTML "           <td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """></td>"
    ShowHTML "           <td><font size=""1""><b><u>T</u>elefone:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_telefone & """></td>"
    ShowHTML "        <tr><td colspan=3><font size=""1""><b><u>e</u>-Mail:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_email"" class=""sti"" SIZE=""60"" MAXLENGTH=""60"" VALUE=""" & w_email & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "           <td><font size=""1""><b><u>O</u>rdem:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ordem & """></td>"
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
    MontaRadioNS "<b>Padrão?</b>", w_padrao, "w_padrao"
    ShowHTML "           </table>"
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center""><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave               = Nothing 
  Set w_codigo              = Nothing
  Set w_nome                = Nothing
  Set w_responsavel         = Nothing 
  Set w_telefone            = Nothing 
  Set w_email               = Nothing 
  Set w_ordem               = Nothing 
  Set w_ativo               = Nothing 
  Set w_padrao              = Nothing 
  Set w_troca               = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de ações do PPA
REM -------------------------------------------------------------------------
Sub PPA
  Dim w_codigo, w_nome, w_responsavel, w_telefone, w_email, w_ativo, w_padrao
  Dim w_sq_acao_ppa_pai, w_selecionada_mpog, w_selecionada_relevante
  Dim w_aprovado, w_empenhado, w_liquidado, w_liquidar, w_saldo
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_codigo                   = Request("w_codigo")
     w_nome                     = Request("w_nome")
     w_responsavel              = Request("w_responsavel")
     w_telefone                 = Request("w_telefone")
     w_email                    = Request("w_email")
     w_ativo                    = Request("w_ativo")
     w_padrao                   = Request("w_padrao")
     w_aprovado                 = Request("w_aprovado")
     w_saldo                    = Request("w_saldo")
     w_empenhado                = Request("w_empenhado")
     w_liquidado                = Request("w_liquidado")
     w_liquidar                 = Request("w_liquidar")
     w_sq_acao_ppa_pai          = Request("w_sq_acao_ppa_pai")
     w_selecionada_mpog         = Request("w_selecionada_mpog")
     w_selecionada_relevante    = Request("w_selecionada_relevante")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetAcaoPPA RS, null, w_cliente, null, null, null, null, null, null, null, null
     RS.Sort = "ordena"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetAcaoPPA RS, w_chave, w_cliente, null, null, null, null, null, null, null, null
     w_codigo                   = RS("codigo")
     w_nome                     = RS("nome")
     w_responsavel              = RS("responsavel")
     w_telefone                 = RS("telefone")
     w_email                    = RS("email")
     w_ativo                    = RS("ativo")
     w_padrao                   = RS("padrao")
     w_aprovado                 = FormatNumber(RS("aprovado"),2)
     w_saldo                    = FormatNumber(RS("saldo"),2)
     w_empenhado                = FormatNumber(RS("empenhado"),2)
     w_liquidado                = FormatNumber(RS("liquidado"),2)
     w_liquidar                 = FormatNumber(RS("liquidar"),2)
     w_sq_acao_ppa_pai          = RS("sq_acao_ppa_pai")
     w_selecionada_mpog         = RS("selecionada_mpog")
     w_selecionada_relevante    = RS("selecionada_relevante")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "2", "100", "1", "1"
        Validate "w_sq_acao_ppa_pai", "Vinculação", "SELECT", "", "1", "18", "", "1"
        Validate "w_codigo", "Código externo", "1", "1", "1", "50", "1", "1"
        Validate "w_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        Validate "w_telefone", "Telefone", "1", "", "2", "20", "1", "1"
        Validate "w_email", "e-Mail", "1", "", "5", "60", "1", "1"
        Validate "w_aprovado", "Aprovado", "VALOR", "1", "4", "18", "", "0123456789,."
        Validate "w_empenhado", "Empenhado", "VALOR", "1", "4", "18", "", "0123456789,."
        Validate "w_liquidado", "Liquidado", "VALOR", "1", "4", "18", "", "0123456789,."
        Validate "w_liquidar", "Liquidar", "VALOR", "1", "4", "18", "", "0123456789,."
        Validate "w_saldo", "Saldo", "1", "VALOR", "4", "18", "", "0123456789,."
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
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
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If Nvl(RS("sq_acao_ppa_pai"),"") = "" Then
           ShowHTML "        <td><font size=""1""><b>" & RS("codigo") & "</td>"
        Else
           ShowHTML "        <td><font size=""1"">&nbsp;&nbsp;" & RS("codigo") & "</td>"
        End If
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""90"" MAXLENGTH=""100"" VALUE=""" & w_nome & """></td>"
    ShowHTML "           <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    SelecaoAcaoPPA "<u>S</u>ubordinação:", "S", null, w_sq_acao_ppa_pai, w_chave, "w_sq_acao_ppa_pai", "CADASTRO", null
    ShowHTML "           </table>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "           <td><font size=""1""><b><u>C</u>ódigo externo:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_codigo"" class=""sti"" SIZE=""10"" MAXLENGTH=""50"" VALUE=""" & w_codigo & """></td>"
    ShowHTML "           <td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """></td>"
    ShowHTML "           <td><font size=""1""><b><u>T</u>elefone:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_telefone & """></td>"
    ShowHTML "        <tr><td colspan=3><font size=""1""><b><u>e</u>-Mail:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_email"" class=""sti"" SIZE=""60"" MAXLENGTH=""60"" VALUE=""" & w_email & """></td>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioNS "<b>Selecionada MP?</b>", w_selecionada_mpog, "w_selecionada_mpog"
    MontaRadioNS "<b>Selecionada Relevante?</b>", w_selecionada_relevante, "w_selecionada_relevante"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0>"
    ShowHTML "         <tr valign=""top"">"
    ShowHTML "           <td><font size=""1""><b>A<u>p</u>rovado:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_aprovado"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & Nvl(w_aprovado,"0,00") & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "           <td><font size=""1""><b><u>S</u>aldo:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_saldo"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & Nvl(w_saldo,"0,00") & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "         <tr valign=""top"">"
    ShowHTML "           <td><font size=""1""><b><u>E</u>mpenhado:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_empenhado"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & Nvl(w_empenhado,"0,00") & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "           <td><font size=""1""><b><u>L</u>iquidado:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_liquidado"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & Nvl(w_liquidado,"0,00") & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "           <td><font size=""1""><b>A l<u>i</u>quidar:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_liquidar"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & Nvl(w_liquidar,"0,00") & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "           </table>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
    MontaRadioNS "<b>Padrão?</b>", w_padrao, "w_padrao"
    ShowHTML "           </table>"
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center""><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_aprovado                = Nothing 
  Set w_empenhado               = Nothing 
  Set w_liquidado               = Nothing 
  Set w_liquidar                = Nothing 
  Set w_saldo                   = Nothing 
  Set w_sq_acao_ppa_pai         = Nothing 
  Set w_selecionada_mpog        = Nothing 
  Set w_selecionada_relevante   = Nothing 
  Set w_chave                   = Nothing 
  Set w_chave                   = Nothing 
  Set w_codigo                  = Nothing
  Set w_nome                    = Nothing
  Set w_responsavel             = Nothing 
  Set w_telefone                = Nothing 
  Set w_email                   = Nothing 
  Set w_ativo                   = Nothing 
  Set w_padrao                  = Nothing 
  Set w_troca                   = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório da tabela do PPA
REM -------------------------------------------------------------------------
Sub Rel_PPA
  Dim p_sq_acao_ppa, p_sq_acao_ppa_pai, p_selecionada_mpog, p_selecionada_relevante
  Dim w_acao_aprovado, w_acao_saldo, w_acao_empenhado, w_acao_liquidado, w_acao_liquidar
  Dim w_tot_aprovado, w_tot_saldo, w_tot_empenhado, w_tot_liquidado, w_tot_liquidar
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade, p_tarefas_atraso
  Dim w_atual, w_col, w_col_word, p_campos, p_metas, p_tarefas, w_logo, w_titulo 
  Dim w_tipo_rel, w_linha, w_pag
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_acao_ppa_pai          = ucase(Trim(Request("p_sq_acao_ppa_pai")))
  p_sq_acao_ppa              = ucase(Trim(Request("p_sq_acao_ppa")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_selecionada_mpog         = ucase(Trim(Request("p_selecionada_mpog")))
  p_selecionada_relevante    = ucase(Trim(Request("p_selecionada_relevante")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  p_campos                   = Request("p_campos")
  p_tarefas                  = Request("p_tarefas")
  p_metas                    = Request("p_metas")
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetAcaoPPA RS, null, w_cliente, p_sq_acao_ppa_pai, p_sq_acao_ppa, p_responsavel, p_selecionada_mpog, p_selecionada_relevante, null, null, null
     RS.Sort = "ordena"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Tabela PPA"
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Tabela PPA</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_acao_ppa_pai", "Programa", "SELECT", "", "1", "18", "", "1"
        Validate "p_sq_acao_ppa", "Ação", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ShowHTML "  if (theForm.p_tarefas.checked == false) {"
        ShowHTML "     theForm.p_prioridade.value = ''"
        ShowHTML "  }"
        ShowHTML "  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {"
        ShowHTML "      alert('Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa ');"
        ShowHTML "      return (false);"
        ShowHTML "  }"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Tabela PPA"
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_acao_ppa_pai.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_col      = 2
    w_col_word = 2
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & p_selecionada_mpog & "</b>]"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & p_selecionada_relevante & "</b>]" End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=2>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    If Instr(p_campos,"responsavel") Then
       ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"email") Then
       ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"telefone") Then
       ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"aprovado")   Then 
       ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"empenhado")  Then 
       ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"saldo")      Then 
       ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidado")  Then 
       ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidar")   Then 
       ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col & " align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_acao_aprovado = 0.00
      w_acao_saldo    = 0.00
      w_acao_empenhado= 0.00
      w_acao_liquidado= 0.00
      w_acao_liquidar = 0.00
        
      w_tot_aprovado  = 0.00
      w_tot_saldo     = 0.00
      w_tot_empenhado = 0.00
      w_tot_liquidado = 0.00
      w_tot_liquidar  = 0.00
      w_atual         = ""
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
           ShowHTML "Tabela PPA"
           ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
           ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
           ShowHTML "</TD></TR>"
           ShowHTML "</FONT></B></TD></TR></TABLE>"
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
           w_filtro = ""
           If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
           If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
           If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & p_selecionada_mpog & "</b>]"             End If
           If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & p_selecionada_relevante & "</b>]" End If
           If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
           ShowHTML "<tr><td align=""left"" colspan=2>"
           If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
           ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
           ShowHTML "<tr><td align=""center"" colspan=3>"
           ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           ShowHTML "          <td><font size=""1""><b>Código</font></td>"
           ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
           If Instr(p_campos,"responsavel") Then ShowHTML "          <td><font size=""1""><b>Responsável</font></td>" End If
           If Instr(p_campos,"email")       Then ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"      End If
           If Instr(p_campos,"telefone")    Then ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"    End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"    End If
           If Instr(p_campos,"empenhado")   Then ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>"   End If
           If Instr(p_campos,"saldo")       Then ShowHTML "          <td><font size=""1""><b>Saldo</font></td>"       End If
           If Instr(p_campos,"liquidado")   Then ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>"   End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>"  End If
           ShowHTML "        </tr>"
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If Nvl(RS("sq_acao_ppa_pai"),"") = "" Then
           If w_atual > "" Then
              If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                 ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa <b>" & w_atual & "</b></td>"
                 If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
                 If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
                 If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
                 If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
                 If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
                 ShowHTML "      </tr>"
                 'ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=10></td></tr>"
              End If
              w_acao_aprovado = 0.00
              w_acao_saldo    = 0.00
              w_acao_empenhado= 0.00
              w_acao_liquidado= 0.00
              w_acao_liquidar = 0.00
           End If
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
           ShowHTML "        <td><font size=""1""><b>" & RS("codigo") & "</td>"
           ShowHTML "        <td><font size=""1""><b>" & RS("nome") & "</td>"
           If Instr(p_campos,"responsavel")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")             Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"  End If
           If Instr(p_campos,"telefone")          Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
              ShowHTML "        <td colspan=" & w_col_word - w_col & "><font size=""1"">&nbsp;</td>"
           End If
           w_atual = RS("codigo")
           w_linha = w_linha + 1
        Else
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "        <td><font size=""1"">&nbsp;&nbsp;" & RS("codigo") & "</td>"
           ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
           If Instr(p_campos,"responsavel") Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>" End If
           If Instr(p_campos,"telefone")    Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("aprovado"),2) & "</td>" End If
           If Instr(p_campos,"empenhado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("empenhado"),2) & "</td>" End If
           If Instr(p_campos,"saldo")       Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(RS("aprovado"))-cDbl(RS("empenhado")),2) & "</td>" End If
           If Instr(p_campos,"liquidado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("liquidado"),2) & "</td>" End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(RS("empenhado"))-cDbl(RS("liquidado")),2) & "</td>" End If
           w_linha = w_linha + 1
           ShowHTML "</tr>"
           If p_metas > "" Then
              ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 RS("chave"), null
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetSolicEtapa RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL"
                 RS3.Sort = "ordem"
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Meta LOA</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                       ShowHtml EtapaLinha(RS2("sq_siw_solicitacao"), Rs3("sq_projeto_etapa"), Rs3("titulo"), w_tipo_rel, Rs3("programada"), RS3("unidade_medida"), Rs3("quantidade"), Rs3("fim_previsto"), Rs3("perc_conclusao"), "S", "PROJETO")
                       RS3.MoveNext
                       w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "        </table>"
           End If
           
           If p_tarefas > "" Then
              ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 RS("chave"), null
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetLinkData RS1, w_cliente, "ORPCAD"
                 DB_GetSolicList RS3, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                    null, null, null, null, null, null, null, p_prioridade, null, null, null, null, _
                    null, null, null, null, null, null, null, null, null, null, RS2("sq_siw_solicitacao"), null, _
                    null, null
                 If p_tarefas_atraso > "" Then
                    RS3.Filter = "fim < " & Date()
                 End If
                 RS3.sort = "fim, prioridade" 
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Tarefas</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Detalhamento</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Parcerias</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Executado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fase atual</font></td>"
                    If p_prioridade = "" Then ShowHTML "<td><font size=""1""><b>Prioridade</font></td>" End If
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                      'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                      w_cor = conTrBgColor
                      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
                      ShowHTML "        <td nowrap><font size=""1"">"
                      If RS3("concluida") = "N" Then
                         If RS3("fim") < Date() Then
                            ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         ElseIf RS3("aviso_prox_conc") = "S" and (RS3("aviso") <= Date()) Then
                            ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      Else
                         If RS3("fim") < Nvl(RS3("fim_real"),RS3("fim")) Then
                            ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      End If
                      ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "ProjetoAtiv.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS3("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ TARGET=""VisualTarefa"" title=""Exibe as informações desta tarefa."">" & RS3("sq_siw_solicitacao") & "&nbsp;</a>"
                      If Len(Nvl(RS3("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS3("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS3("assunto"),"-") End If
                      ShowHTML "        <td><font size=""1"">" & w_titulo & "</td>"
                      ShowHTML "        <td><font size=""1"">" & RS3("nm_solic") & "</td>"
                      ShowHTML "        <td><font size=""1"">" & Nvl(RS3("proponente"),"---") & "</td>"
                      ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS3("fim"),2),"-") & "</td>"
                      ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS3("valor"),2) & "&nbsp;</td>"
                      ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS3("custo_real"),2) & "&nbsp;</td>"
                      ShowHTML "        <td nowrap><font size=""1"">" & RS3("nm_tramite") & "</td>"
                      If p_prioridade = "" Then ShowHTML "<td nowrap><font size=""1"">" & RetornaPrioridade(RS3("prioridade")) & "</td>" End If
                      ShowHTML "        </td>"
                      ShowHTML "      </tr>"
                      RS3.MoveNext
                      w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "        </table>"
           End If
        If p_sq_unidade_resp > "" Then
              ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 RS("chave"), null
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 w_linha = w_linha + 1
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""left""><font size=""1""><b>Não foi informado o setor responsável.</b></td></tr>"
              Else
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td align=""left""><font size=""1""><b>Setor responsável</font></td>"
                 ShowHTML "        </tr>"
                 w_linha = w_linha + 1
                 While Not RS2.EOF
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td align=""left""><font size=""1""><b>"& RS2("nm_unidade_resp") & "</font></td>"
                    ShowHTML "        </tr>"
                    RS2.MoveNext
                    w_linha = w_linha + 1
                  Wend
              End If
              ShowHTML "        </table>"
           End If
        End If
        ShowHTML "      </tr>"
        w_acao_aprovado = w_acao_aprovado   + cDbl(RS("aprovado"))
        w_acao_saldo    = w_acao_saldo      + cDbl(RS("aprovado"))-cDbl(RS("empenhado"))
        w_acao_empenhado= w_acao_empenhado  + cDbl(RS("empenhado"))
        w_acao_liquidado= w_acao_liquidado  + cDbl(RS("liquidado"))
        w_acao_liquidar = w_acao_liquidar   + cDbl(RS("empenhado"))-cDbl(RS("liquidado"))
        
        w_tot_aprovado  = w_tot_aprovado   + cDbl(RS("aprovado"))
        w_tot_saldo     = w_tot_saldo      + cDbl(RS("aprovado"))-cDbl(RS("empenhado"))
        w_tot_empenhado = w_tot_empenhado  + cDbl(RS("empenhado"))
        w_tot_liquidado = w_tot_liquidado  + cDbl(RS("liquidado"))
        w_tot_liquidar  = w_tot_liquidar   + cDbl(RS("empenhado"))-cDbl(RS("liquidado"))
        RS.MoveNext
      wend
      If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
         If Not p_sq_acao_ppa > " " Then
            ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa <b>" & w_atual & "</b></td>"
            If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
            If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
            If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
            If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
            If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
            ShowHTML "      </tr>"
            w_linha = w_linha + 1
         End If
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=" & w_col &  "></td></tr>"
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""center"" height=30>"
         ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""2""><b>Totais do relatório</td>"
         If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_aprovado,2) & "</td>" End If
         If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_empenhado,2) & "</td>" End If
         If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_saldo,2) & "</td>" End If
         If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidado,2) & "</td>" End If
         If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidar,2) & "</td>" End If
         ShowHTML "      </tr>"
         w_linha = w_linha + 1
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tabela PPA",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>P</u>rograma:", "S", null, p_sq_acao_ppa_pai, w_chave, "p_sq_acao_ppa_pai", "CADASTRO", null
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>A</u>ção:", "A", null, p_sq_acao_ppa, w_chave, "p_sq_acao_ppa", "IDENTIFICACAO", null
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "          <td><font size=""1""><b>Exibir somente tarefas em atraso?</b><br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"    
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada MP?</b><br>"
    If p_selecionada_mpog = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_mpog & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""""> Tanto faz"
    ElseIf p_selecionada_mpog = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/MS?</b><br>"
    If p_selecionada_relevante = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_relevante & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""""> Tanto faz"
    ElseIf p_selecionada_relevante = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Campos a serem exibidos"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""responsavel""> Responsável</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""aprovado""> Aprovado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""email""> e-Mail</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""saldo""> Saldo</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""telefone""> Telefone</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""liquidado""> Liquidado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""liquidar""> A liquidar</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""empenhado""> Empenhado</td>"    
    ShowHTML "      <tr><td colspan=2><font size=1><b>Blocos adicionais"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_metas"" value=""metas""> Metas físicas</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_tarefas"" value=""tarefas""> Tarefas</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_sq_unidade_resp"" value=""unidade""> Setor responsável</td>"
    ShowHTML "     </table>"    
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set p_campos                  = Nothing 
  Set p_metas                   = Nothing 
  Set p_tarefas                 = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set w_acao_aprovado           = Nothing 
  Set w_acao_saldo              = Nothing 
  Set w_acao_empenhado          = Nothing 
  Set w_acao_liquidado          = Nothing 
  Set w_acao_liquidar           = Nothing
  Set w_tot_aprovado            = Nothing 
  Set w_tot_saldo               = Nothing 
  Set w_tot_empenhado           = Nothing 
  Set w_tot_liquidado           = Nothing 
  Set w_tot_liquidar            = Nothing
  Set p_sq_acao_ppa             = Nothing 
  Set p_sq_acao_ppa_pai         = Nothing 
  Set p_selecionada_mpog        = Nothing 
  Set p_selecionada_relevante   = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório da tabela de Iniciativas
REM -------------------------------------------------------------------------
Sub Rel_Iniciativa
  Dim p_sq_orprioridade, p_selecionada_mpog, p_selecionada_relevante
  Dim w_acao_aprovado, w_acao_saldo, w_acao_empenhado, w_acao_liquidado, w_acao_liquidar
  Dim w_tot_aprovado, w_tot_saldo, w_tot_empenhado, w_tot_liquidado, w_tot_liquidar
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade, p_tarefas_atraso
  Dim w_atual, w_col, w_col_word, p_campos, p_metas, p_tarefas, w_logo, w_titulo, w_sq_siw_solicitacao
  Dim w_tipo_rel, w_pag, w_linha
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_orprioridade          = ucase(Trim(Request("p_sq_orprioridade")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_selecionada_mpog         = ucase(Trim(Request("p_selecionada_mpog")))
  p_selecionada_relevante    = ucase(Trim(Request("p_selecionada_relevante")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  p_campos                   = Request("p_campos")
  p_tarefas                  = Request("p_tarefas")
  p_metas                    = Request("p_metas")
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetOrPrioridade RS, null, w_cliente, p_sq_orprioridade, p_responsavel, p_selecionada_mpog, p_selecionada_relevante
     RS.Sort = "ordem"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 5
     CabecalhoWordOR "Iniciativa Prioritária", w_pag, LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED")
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Iniciativas Prioritárias</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_orprioridade", "Iniciativa prioritária", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ShowHTML "  if (theForm.p_tarefas.checked == false) {"
        ShowHTML "     theForm.p_prioridade.value = '';"
        ShowHTML "  }"
        ShowHTML "  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {"
        ShowHTML "      alert('Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa ');"
        ShowHTML "      return (false);"
        ShowHTML "  }"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Iniciativa Prioritária"
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_orprioridade.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_col      = 1
    w_col_word = 1
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    'ShowHTML "<tr><td colspan=2><font size=""1""><font size=""1""><b>Filtro:"
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & RetornaSimNao(p_responsavel) & "</b>]"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & RetornaSimNao(p_selecionada_mpog) & "</b>]"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & RetornaSimNao(p_selecionada_relevante) & "</b>]" End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tarefas em atraso&nbsp;<font size=1>[<b>" & RetornaSimNao(p_tarefas_atraso) & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=3>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></td></tr></table></td></tr>"         End If    
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "        <td><font size=""1""><b>Nome</font></td>"
    If Instr(p_campos,"responsavel") Then
       ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"email") Then
       ShowHTML "     <td><font size=""1""><b>e-Mail</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"telefone") Then
       ShowHTML "     <td><font size=""1""><b>Telefone</font></td>"
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"aprovado")   Then 
       ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"empenhado")  Then 
       ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"saldo")      Then 
       ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidado")  Then 
       ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidar")   Then 
       ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    ShowHTML "      </tr>"
    w_linha = w_linha + 1
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
        w_linha = w_linha + 1
    Else
      w_acao_aprovado = 0.00
      w_acao_saldo    = 0.00
      w_acao_empenhado= 0.00
      w_acao_liquidado= 0.00
      w_acao_liquidar = 0.00
        
      w_tot_aprovado  = 0.00
      w_tot_saldo     = 0.00
      w_tot_empenhado = 0.00
      w_tot_liquidado = 0.00
      w_tot_liquidar  = 0.00
      w_atual         = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_linha > 20 and w_tipo_rel = "WORD" Then
           CabecalhoWordRel w_logo, w_pag, w_linha, p_responsavel, p_prioridade, _
                            p_selecionada_mpog, p_selecionada_relevante, p_tarefas_atraso, w_filtro, _
                            p_campos
           w_linha = w_linha + 1
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If cDbl(w_atual) <> cDbl(RS("chave")) Then
           If (Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar")) and p_sq_orprioridade = "" and w_sq_siw_solicitacao > "" Then
              ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "     <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais da iniciativa</td>"
              If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
              If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
              If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
              If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
              If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
              ShowHTML "   </tr>"
              'ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=10></td></tr>"
              w_linha = w_linha + 1
           End If
           w_acao_aprovado = 0.00
           w_acao_saldo    = 0.00
           w_acao_empenhado= 0.00
           w_acao_liquidado= 0.00
           w_acao_liquidar = 0.00
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
           ShowHTML "        <td><font size=""1""><b>Iniciativa: " & RS("nome") & "</td>"
           If Instr(p_campos,"responsavel")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")             Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"  End If
           If Instr(p_campos,"telefone")          Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
              ShowHTML "        <td colspan=" & w_col_word - w_col & "><font size=""1"">&nbsp;</td>"
           End If
           ShowHTML "         </tr>"  
           w_linha = w_linha + 1 
        End If
        w_sq_siw_solicitacao = Nvl(RS("sq_siw_solicitacao"),"")
        w_atual = RS("chave")
        If w_sq_siw_solicitacao > "" Then
           ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           If w_tipo_rel = "WORD" Then
              ShowHTML "        <td colspan=" &  w_col & "><font size=""1""><b>Ação:</b>" & RS("titulo") & "</td>"
           Else
              ShowHTML "        <td colspan=" &  w_col & "><font size=""1""><b>Ação:</b> <A class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("titulo") & "</a></td>"
           End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("aprovado"),0),2) & "</td>" End If
           If Instr(p_campos,"empenhado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("empenhado"),0),2) & "</td>" End If
           If Instr(p_campos,"saldo")       Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("aprovado"),0))-cDbl(Nvl(RS("empenhado"),0)),2) & "</td>" End If
           If Instr(p_campos,"liquidado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("liquidado"),0),2) & "</td>" End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("empenhado"),0))-cDbl(Nvl(RS("liquidado"),0)),2) & "</td>" End If
           w_linha = w_linha + 1
           ShowHTML "         </tr>"
        Else
          ' ShowHTML "        <td><font size=""1""></td>"
          ' ShowHTML "        <td colspan=" &  w_col & "><font size=""1"">" & RS("titulo") & "</td>"
          ' w_linha = w_linha + 1
        End If
        If w_sq_siw_solicitacao > "" Then
           If p_metas > "" Then
              ShowHTML "   <tr><td colspan=" & w_col_word & ">"
              ShowHTML "     <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, w_sq_siw_solicitacao, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, RS("chave")
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetSolicEtapa RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL"
                 RS3.Sort = "ordem"
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML " <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Meta LOA</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                       ShowHtml EtapaLinha(RS2("sq_siw_solicitacao"), Rs3("sq_projeto_etapa"), Rs3("titulo"), w_tipo_rel, Rs3("programada"), RS3("unidade_medida"), Rs3("quantidade"), Rs3("fim_previsto"), Rs3("perc_conclusao"), "S", "PROJETO")
                       RS3.MoveNext
                       w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "     </table>"
           End If
           If p_tarefas > "" Then
              ShowHTML "     <tr><td colspan=" & w_col_word & ">"
              ShowHTML "       <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, w_sq_siw_solicitacao, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, RS("chave")
              RS2.sort = "fim, prioridade"    
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetLinkData RS1, w_cliente, "ORPCAD"
                 DB_GetSolicList RS3, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                    null, null, null, null, null, null, null, p_prioridade, null, null, null, null, _
                    null, null, null, null, null, null, null, null, null, null, RS2("sq_siw_solicitacao"), null, _
                    null, null
                 If p_tarefas_atraso > "" Then
                    RS3.Filter = "fim < " & Date()
                 End If
                 RS3.sort = "fim, prioridade" 
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "     <td><font size=""1""><b>Tarefas</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Detalhamento</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Parcerias</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Programado</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Executado</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Fase atual</font></td>"
                    If p_prioridade = "" Then ShowHTML "<td><font size=""1""><b>Prioridade</font></td>" End If
                    ShowHTML "   </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                      'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                      w_cor = conTrBgColor
                      ShowHTML " <tr bgcolor=""" & w_cor & """ valign=""top"">"
                      ShowHTML "   <td nowrap><font size=""1"">"
                      If RS3("concluida") = "N" Then
                         If RS3("fim") < Date() Then
                            ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         ElseIf RS3("aviso_prox_conc") = "S" and (RS3("aviso") <= Date()) Then
                            ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      Else
                         If RS3("fim") < Nvl(RS3("fim_real"),RS3("fim")) Then
                            ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      End If
                      If w_tipo_rel = "WORD" Then
                         ShowHTML "" & RS3("sq_siw_solicitacao") & "&nbsp;"
                      Else
                         ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "ProjetoAtiv.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS3("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ TARGET=""VisualTarefa"" title=""Exibe as informações desta tarefa."">" & RS3("sq_siw_solicitacao") & "&nbsp;</a>"
                      End If
                      ShowHTML "   </font></td>"  
                      ShowHTML "   <td><font size=""1"">" & Nvl(RS3("assunto"),"-") & "</td>"
                      ShowHTML "   <td><font size=""1"">" & RS3("nm_solic") & "</td>"
                      ShowHTML "   <td><font size=""1"">" & Nvl(RS3("proponente"),"---") & "</td>"
                      ShowHTML "   <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS3("fim"),2),"-") & "</td>"
                      ShowHTML "   <td align=""right""><font size=""1"">" & FormatNumber(RS3("valor"),2) & "&nbsp;</td>"
                      ShowHTML "   <td align=""right""><font size=""1"">" & FormatNumber(RS3("custo_real"),2) & "&nbsp;</td>"
                      ShowHTML "   <td nowrap><font size=""1"">" & RS3("nm_tramite") & "</td>"
                      If p_prioridade = "" Then ShowHTML "<td nowrap><font size=""1"">" & RetornaPrioridade(RS3("prioridade")) & "</td>" End If
                      ShowHTML " </tr>"
                      w_linha = w_linha + 1
                      RS3.MoveNext
                    Wend
                 End If
              End If
              ShowHTML "       </table>"
           End If
           If p_sq_unidade_resp > "" Then
              ShowHTML "     <tr><td colspan=" & w_col_word & ">"
              ShowHTML "       <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ORCAD"
              DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
                 null, null, null, null, null, null, null, null, null, null, RS("sq_siw_solicitacao"), null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, RS("chave")
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""left""><font size=""1""><b>Não foi informado o setor responsável.</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "        <td align=""left""><font size=""1""><b>Setor responsável</font></td>"
                 ShowHTML "      </tr>"
                 w_linha = w_linha + 1
                 While Not RS2.EOF
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "     <td align=""left""><font size=""1""><b>"& RS2("nm_unidade_resp") &"</font></td>"
                    ShowHTML "   </tr>"
                    w_linha = w_linha + 1
                    RS2.MoveNext
                  Wend
              End If
              ShowHTML "       </table>"
           End If
        Else
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "          <td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros.</td>"
           w_linha = w_linha + 1
        End If
        ShowHTML "           </tr>"
        w_acao_aprovado = w_acao_aprovado   + cDbl(Nvl(RS("aprovado"),0.00))
        w_acao_saldo    = w_acao_saldo      + cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00))
        w_acao_empenhado= w_acao_empenhado  + cDbl(Nvl(RS("empenhado"),0.00))
        w_acao_liquidado= w_acao_liquidado  + cDbl(Nvl(RS("liquidado"),0.00))
        w_acao_liquidar = w_acao_liquidar   + cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))
        
        w_tot_aprovado  = w_tot_aprovado   + cDbl(Nvl(RS("aprovado"),0.00))
        w_tot_saldo     = w_tot_saldo      + cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00))
        w_tot_empenhado = w_tot_empenhado  + cDbl(Nvl(RS("empenhado"),0.00))
        w_tot_liquidado = w_tot_liquidado  + cDbl(Nvl(RS("liquidado"),0.00))
        w_tot_liquidar  = w_tot_liquidar   + cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))
        RS.MoveNext
      wend
      If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
         If p_sq_orprioridade > " " Then
            ShowHTML "       <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "         <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais da iniciativa </b></td>"
            If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
            If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
            If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
            If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
            If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
            ShowHTML "       </tr>"
            w_linha = w_linha + 1
         End If
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=" &  w_col & "></td></tr>"
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ valign=""center"" height=30>"
         ShowHTML "            <td colspan=" & w_col & " align=""right""><font size=""2""><b>Totais do relatório</td>"
         If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_aprovado,2) & "</td>" End If
         If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_empenhado,2) & "</td>" End If
         If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_saldo,2) & "</td>" End If
         If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidado,2) & "</td>" End If
         If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidar,2) & "</td>" End If
         ShowHTML "          </tr>"
         w_linha = w_linha + 1
      End If
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "    </table>"
    ShowHTML "</center></div>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tabela PPA",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "          <tr>"
    SelecaoOrPrioridade "<u>I</u>niciativa prioritária:", "I", null, p_sq_orprioridade, null, "p_sq_orprioridade", "VINCULACAO", null
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "          <td><font size=""1""><b>Exibir somente tarefas em atraso?</b><br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada MP?</b><br>"
    If p_selecionada_mpog = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_mpog & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""""> Tanto faz"
    ElseIf p_selecionada_mpog = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/MS?</b><br>"
    If p_selecionada_relevante = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_relevante & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""""> Tanto faz"
    ElseIf p_selecionada_relevante = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Campos a serem exibidos"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""responsavel""> Responsável</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""aprovado""> Aprovado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""email""> e-Mail</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""saldo""> Saldo</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""telefone""> Telefone</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""liquidado""> Liquidado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""liquidar""> A liquidar</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_campos"" value=""empenhado""> Empenhado</td>"    
    ShowHTML "      <tr><td colspan=2><font size=1><b>Blocos adicionais"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_metas"" value=""metas""> Metas físicas</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_tarefas"" value=""tarefas""> Tarefas</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""sti"" type=""CHECKBOX"" name=""p_sq_unidade_resp"" value=""unidade""> Setor responsável</td>"
    ShowHTML "     </table>"    
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If

  Set w_titulo                  = Nothing 
  Set p_campos                  = Nothing 
  Set p_metas                   = Nothing 
  Set p_tarefas                 = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set w_acao_aprovado           = Nothing 
  Set w_acao_saldo              = Nothing 
  Set w_acao_empenhado          = Nothing 
  Set w_acao_liquidado          = Nothing 
  Set w_acao_liquidar           = Nothing
  Set w_tot_aprovado            = Nothing 
  Set w_tot_saldo               = Nothing 
  Set w_tot_empenhado           = Nothing 
  Set w_tot_liquidado           = Nothing 
  Set w_tot_liquidar            = Nothing
  Set p_sq_orprioridade         = Nothing 
  Set p_selecionada_mpog        = Nothing 
  Set p_selecionada_relevante   = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório Sintético de Iniciativas Prioritárias
REM -------------------------------------------------------------------------
Sub Rel_Sintetico_IP
  Dim p_sq_orprioridade, p_selecionada_mpog, p_selecionada_relevante
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade
  Dim w_atual, w_logo, w_titulo, w_sq_siw_solicitacao
  Dim w_tipo_rel, w_quantitativo_total
  Dim p_programada, p_exequivel, p_fim_previsto, p_atraso, p_tarefas_atraso
  Dim w_teste_metas, w_teste_acoes, w_visao, RSquery, w_cont
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_orprioridade          = ucase(Trim(Request("p_sq_orprioridade")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_selecionada_mpog         = ucase(Trim(Request("p_selecionada_mpog")))
  p_selecionada_relevante    = ucase(Trim(Request("p_selecionada_relevante")))
  p_programada               = ucase(Trim(Request("p_programada")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  p_fim_previsto             = ucase(Trim(Request("p_fim_previsto")))
  p_atraso                   = ucase(Trim(Request("p_atraso")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  
  w_cont = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetOrPrioridade RS, null, w_cliente, p_sq_orprioridade, p_responsavel, p_selecionada_mpog, p_selecionada_relevante
     RS.Sort = "ordem"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Iniciativa Prioritária"
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Sintético das Iniciativas Prioritárias</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_orprioridade", "Iniciativa prioritária", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Iniciativa Prioritária"
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_orprioridade.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
     w_filtro = "<tr valign=""top"">"
    If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecionada_mpog & "</b>]&nbsp;"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecionada_relevante & "</b>]&nbsp;" End If
    If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta LOA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
    If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
    If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
    If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""center"">"
    If w_filtro                > "" Then ShowHTML "<table border=0 width=""100%""><tr><td width=""25%""><font size=1><b>Filtro:</b><td><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "<tr><td align=""center"" colspan=""2"">"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""2""><font size=""1""><b>Iniciativa Prioritária</font></td>"
    ShowHTML "          <td rowspan=""2""><font size=""1""><b>Ações Cadastradas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Metas</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       w_cont = w_cont + 1
       w_linha = w_linha + 1
       ShowHTML "      <tr><td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
       w_atual = 0
       ' Lista os registros selecionados para listagem
       While Not RS.EOF 
          If w_linha > 30 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</center></div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "Iniciativa Prioritária"
             ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
             ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
             ShowHTML "</TD></TR>"
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<div align=center><center>"
             ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
             w_filtro = "<tr valign=""top"">"
             If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
             If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
             If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecionada_mpog & "</b>]&nbsp;"             End If
             If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecionada_relevante & "</b>]&nbsp;" End If
             If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta LOA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
             If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
             If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
             If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
             If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If                    
             ShowHTML "<tr><td align=""center"">"
             If w_filtro                > "" Then ShowHTML "<table border=0 width=""100%""><tr><td width=""25%""><font size=1><b>Filtro:</b><td><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
             ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
             ShowHTML "<tr><td align=""center"" colspan=""2"">"
             ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td rowspan=""2""><font size=""1""><b>Iniciativa Prioritária</font></td>"
             ShowHTML "          <td rowspan=""2""><font size=""1""><b>Ações Cadastradas</font></td>"
             ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Metas</font></td>"
             ShowHTML "        </tr>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
             ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
             ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
             ShowHTML "          <td><font size=""1""><b>Quantitativo<br>realizado</font></td>"
             ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
             ShowHTML "        </tr>"
          End If
          'Montagem da lista das ações
          DB_GetLinkData RS1, w_cliente, "ORCAD"
          DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
             null, null, null, null, p_atraso, null, null, null, null, null, RS("sq_siw_solicitacao"), null, _
             null, null, null, null, null, null, null, null, null, null, null, null, _
             null, RS("chave")
          RS2.sort = "fim, prioridade" 
          
          'Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          w_teste_metas = 0
          w_teste_acoes = 0 
  
          'Recuperação e verificação das metas das ações de acordo com a visão do usuário
          If Not RS2.EOF Then
             w_teste_acoes = 1
             'Verificaçao da visao das ação do usuario
             'If cDbl(Nvl(RS2("solicitante"),0)) = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("executor"),0))    = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("cadastrador"),0)) = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("titular"),0))     = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("substituto"),0))  = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("tit_exec"),0))    = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("subst_exec"),0))  = cDbl(w_usuario) Then
             '   ' Se for solicitante, executor ou cadastrador, tem visão completa
             '   w_visao = 0
             'Else
             '   DB_GetSolicInter Rsquery, RS("sq_siw_solicitacao"), w_usuario, "REGISTRO"
             '   If Not RSquery.EOF Then
             '      ' Se for interessado, verifica a visão cadastrada para ele.
             '      w_visao = cDbl(RSquery("tipo_visao"))
             '      RSquery.Close
             '   Else
             '      DB_GetSolicAreas Rsquery, RS("sq_siw_solicitacao"), Session("sq_lotacao"), "REGISTRO"
             '      If Not RSquery.EOF Then
             '         ' Se for de uma das unidades envolvidas, tem visão parcial
             '         w_visao = 1
             '         RSquery.Close
             '      Else
             '         ' Caso contrário, tem visão resumida
             '         w_visao = 2
             '      End If
             '   End If
             'End If
             w_visao = 0 
             If w_visao < 2 Then               
                DB_GetSolicEtapa RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL"
                If p_programada       > "" and p_exequivel    > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_programada   > "" and p_exequivel    > "" Then   
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "'"
                ElseIf p_programada   > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_fim_previsto > "" and p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_programada   > "" Then
                   RS3.Filter = "programada = '" & p_programada & "'"
                ElseIf p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "'"
                ElseIf p_fim_previsto > "" Then
                   RS3.Filter = "fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                End If
                RS3.Sort = "ordem"
                If Not RS3.EOF Then
                   w_teste_metas = 1
                ElseIf p_programada = "" and p_exequivel = "" and p_fim_previsto = "" Then
                   w_teste_metas = 3
                End If
             Else
                w_teste_metas = 0
             End If
          Else
             If RS("sq_siw_solicitacao") > "" Then
                w_teste_acoes = 1
                w_teste_metas = 0
             Else
                w_teste_acoes = 0
             End If
          End If
          
          If w_teste_metas = 1 or w_teste_metas = 3 Then
             'Inicio da montagem da lista das ações e metas de acordo com o filtro
             w_cont = w_cont + 1
             If cDbl(w_atual) <> cDbl(RS("chave")) or p_programada > "" or p_exequivel > "" or p_fim_previsto > "" or p_atraso > ""Then
                ShowHTML "      <tr valign=""top"">"
                ShowHTML "        <td><font size=""1""><b>" & RS("nome") & "</td>"
             Else
                ShowHTML "      <tr valign=""top"">"
                ShowHTML "        <td><font size=""1""><b>&nbsp;</td>"
             End If
             w_linha = w_linha + 1
             w_sq_siw_solicitacao = Nvl(RS("sq_siw_solicitacao"),"")
             If w_sq_siw_solicitacao > "" Then
                If w_tipo_rel = "WORD" Then
                   ShowHTML "        <td><font size=""1""><b>" & RS("titulo") & "</td>"
                Else
                   ShowHTML "        <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=1&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("titulo") & "</a></td>"
                End If
                If RS2.EOF Then
                   ShowHTML "      <td colspan=""5"" align=""center""><font size=""1""><b>Não foram encontrados registros.<b></td>"
                Else
                   If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                      ShowHTML "      <td colspan=""5"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
                   Else
                      If w_tipo_rel = "WORD" Then
                         ShowHTML "      <td><font size=""1"">" & RS3("titulo") & "</td>"
                      Else
                         ShowHTML "      <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_projeto_etapa")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & RS3("titulo") & "</A></td>"
                      End If
                      ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(RS3("unidade_medida"),"---") & "</td>"
                      ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(RS3("quantidade"),0) & "</td>"
                      DB_GetEtapaMensal RS4, RS3("sq_projeto_etapa")
                      RS4.Sort = "referencia desc"
                      If Not RS4.EOF Then
                         If RS3("cumulativa") = "S" Then
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(Nvl(RS4("execucao_fisica"),0),0) & "</td>"
                         Else
                            w_quantitativo_total = 0
                            While Not RS4.EOF
                               w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                               RS4.MoveNext
                            Wend
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(w_quantitativo_total,0) & "</td>"
                         End If
                      Else
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                      End If
                      ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                      RS3.MoveNext
                      If Not RS3.EOF Then
                         While Not RS3.EOF
                            ShowHTML "      <tr><td colspan=""2"">&nbsp;"
                            If w_tipo_rel = "WORD" Then
                               ShowHTML "      <td><font size=""1"">" & Rs3("titulo") & "</td>"
                            Else
                               ShowHTML "      <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_projeto_etapa")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                            End If
                            ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(Rs3("quantidade"),0) & "</td>"
                            DB_GetEtapaMensal RS4, RS3("sq_projeto_etapa")
                            RS4.Sort = "referencia desc"
                            If Not RS4.EOF Then
                               If RS3("cumulativa") = "S" Then
                                  ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(Nvl(RS4("execucao_fisica"),0),0) & "</td>"
                               Else
                                  w_quantitativo_total = 0
                                  While Not RS4.EOF
                                     w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                                     RS4.MoveNext
                                  Wend
                                  ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(w_quantitativo_total,0) & "</td>"
                               End If
                            Else
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                            End If
                            ShowHTML "           <td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                            ShowHTML "        </tr>"
                            w_linha = w_linha + 1
                            RS3.MoveNext
                         Wend
                      End If
                   End If
                End If
             Else
                ShowHTML "        <td colspan=""6"" align=""middle""><font size=""1""><b>Não foram encontrados registros.</b></td>"
             End If            
          Else
             If p_programada = "" and p_exequivel = "" and p_fim_previsto = "" and p_atraso = "" Then
                w_cont = w_cont + 1
                If cDbl(w_atual) <> cDbl(RS("chave")) Then
                   ShowHTML "      <tr valign=""top"">"
                   ShowHTML "        <td><font size=""1""><b>" & RS("nome") & "</td>"
                Else
                   ShowHTML "      <tr valign=""top"">"
                   ShowHTML "        <td><font size=""1""><b>&nbsp;</td>"
                End If
                w_linha = w_linha + 1
                If w_teste_acoes = 1 Then
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("titulo") & "</b></td>"
                   ShowHTML "        <td colspan=""5"" align=""center""><font size=""1""><b>Não há permissão para visualização da ação<b></td>"
                Else
                   ShowHTML "        <td colspan=""6"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
                End If
             End If
          End If
          w_atual = RS("chave")
          RS.MoveNext
       wend
    End If
    If w_cont = 0 Then
       ShowHTML "        <td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tabela PPA",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "          <tr>"
    SelecaoOrPrioridade "<u>I</u>niciativa prioritária:", "I", null, p_sq_orprioridade, null, "p_sq_orprioridade", "VINCULACAO", null
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    'ShowHTML "      <tr>"
    'SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada MP?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value="""" checked> Tanto faz"
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/MS?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value="""" checked> Tanto faz"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas da LOA?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_programada"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_programada"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas que não serão cumpridas?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value=""N""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value="""" checked> Não"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente ações em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_atraso"" value="""" checked> Não"
    'ShowHTML "      <tr valign=""top"">"
    'ShowHTML "          <td><font size=""1""><b>Exibir ações com tarefas em atraso?</b><br>"
    'ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If

  Set w_titulo                  = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set p_sq_orprioridade         = Nothing 
  Set p_selecionada_mpog        = Nothing 
  Set p_selecionada_relevante   = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório Sintético das Ações do PPA
REM -------------------------------------------------------------------------
Sub Rel_Sintetico_PPA
  Dim p_sq_acao_ppa, p_sq_acao_ppa_pai, p_selecionada_mpog, p_selecionada_relevante
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade
  Dim w_atual, w_logo, w_titulo 
  Dim w_tipo_rel, w_quantitativo_total
  Dim p_programada, p_exequivel, p_fim_previsto, p_atraso, p_tarefas_atraso
  Dim w_teste_metas, w_teste_acoes, w_visao, RSquery, w_cont, w_teste_pai
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_acao_ppa_pai          = ucase(Trim(Request("p_sq_acao_ppa_pai")))
  p_sq_acao_ppa              = ucase(Trim(Request("p_sq_acao_ppa")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_selecionada_mpog         = ucase(Trim(Request("p_selecionada_mpog")))
  p_selecionada_relevante    = ucase(Trim(Request("p_selecionada_relevante")))
  p_programada               = ucase(Trim(Request("p_programada")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  p_fim_previsto             = ucase(Trim(Request("p_fim_previsto")))
  p_atraso                   = ucase(Trim(Request("p_atraso")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  
  w_cont = 0 
  w_teste_pai = 0
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetAcaoPPA RS, null, w_cliente, p_sq_acao_ppa_pai, p_sq_acao_ppa, p_responsavel, p_selecionada_mpog, p_selecionada_relevante, null, null, null
     RS.Sort = "ordena"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Ações do PPA"
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Sintético das Ações do PPA</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_acao_ppa_pai", "Programa", "SELECT", "", "1", "18", "", "1"
        Validate "p_sq_acao_ppa", "Ação", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Ações do PPA"
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_acao_ppa_pai.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
     w_filtro = "<tr valign=""top"">"
    If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecionada_mpog & "</b>]&nbsp;"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecionada_relevante & "</b>]&nbsp;" End If
    If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta LOA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
    If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
    If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
    If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"">"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "<tr><td align=""center"" colspan=""2"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</font></td>"
    DB_GetOrImport RS1, null, w_cliente, null, null, null, null, null
    RS1.Sort ="data_arquivo desc"
    ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS1("data_arquivo")),"-") & "</font></td>"
    RS1.Close
    ShowHTML "          <td rowspan=""1"" colspan=""6""><font size=""1""><b>Metas</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Saldo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>"
    ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>"
    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quatintativo<br>realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Meta<br>LOA</font></td>"
    ShowHTML "        </tr>"    
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       w_cont = w_cont + 1
       w_linha = w_linha + 1
       ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=16 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_atual = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
         If w_linha > 19 and w_tipo_rel = "WORD" Then
            ShowHTML "    </table>"
            ShowHTML "  </td>"
            ShowHTML "</tr>"
            ShowHTML "</table>"
            ShowHTML "</center></div>"
            ShowHTML "    <br style=""page-break-after:always"">"
            w_linha = 6
            w_pag   = w_pag + 1
            ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
            ShowHTML "Iniciativa Prioritária"
            ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
            ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
            ShowHTML "</TD></TR>"
            ShowHTML "</FONT></B></TD></TR></TABLE>"
            ShowHTML "<div align=center><center>"
            ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
            w_filtro = "<tr valign=""top"">"
            If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
            If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
            If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecionada_mpog & "</b>]&nbsp;"             End If
            If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecionada_relevante & "</b>]&nbsp;" End If
            If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta LOA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
            If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
            If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
            If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
            If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If                    
            ShowHTML "<tr><td align=""left"">"
            If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
            ShowHTML "<tr><td align=""center"" colspan=""2"">"
            ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
            ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</font></td>"
            DB_GetOrImport RS1, null, w_cliente, null, null, null, null, null
            RS1.Sort ="data_arquivo desc"
            ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS1("data_arquivo")),"-") & "</font></td>"
            RS1.Close
            ShowHTML "          <td rowspan=""1"" colspan=""6""><font size=""1""><b>Metas</font></td>"
            ShowHTML "        </tr>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Saldo</font></td>"
            ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>"
            ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>"
            ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
            ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
            ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Quatintativo<br>realizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Meta<br>LOA</font></td>"
            ShowHTML "        </tr>"    
          End If
          If Nvl(RS("sq_acao_ppa_pai"),"") = "" Then
             RS.MoveNext
             w_teste_pai = 1
          End If
          'Montagem da lista das ações
          DB_GetLinkData RS1, w_cliente, "ORCAD"
          DB_GetSolicList RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 5, _
             null, null, null, null, p_atraso, null, null, null, null, null, RS("sq_siw_solicitacao"), null, _
             null, null, null, null, null, null, null, null, null, null, null, null, _
             RS("chave"), null
          RS2.sort = "fim, prioridade" 

          'Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          w_teste_metas = 0
          w_teste_acoes = 0 
             
          'Recuperação e verificação das metas das ações de acordo com a visão do usuário
          If Not RS2.EOF Then
             w_teste_acoes = 1
             'Verificaçao da visao das ação do usuario
             'If cDbl(Nvl(RS2("solicitante"),0)) = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("executor"),0))    = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("cadastrador"),0)) = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("titular"),0))     = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("substituto"),0))  = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("tit_exec"),0))    = cDbl(w_usuario) or _
             '   cDbl(Nvl(RS2("subst_exec"),0))  = cDbl(w_usuario) Then
             '   ' Se for solicitante, executor ou cadastrador, tem visão completa
             '   w_visao = 0
             'Else
             '   DB_GetSolicInter Rsquery, RS("sq_siw_solicitacao"), w_usuario, "REGISTRO"
             '   If Not RSquery.EOF Then
             '      ' Se for interessado, verifica a visão cadastrada para ele.
             '      w_visao = cDbl(RSquery("tipo_visao"))
             '      RSquery.Close
             '   Else
             '      DB_GetSolicAreas Rsquery, RS("sq_siw_solicitacao"), Session("sq_lotacao"), "REGISTRO"
             '      If Not RSquery.EOF Then
             '         ' Se for de uma das unidades envolvidas, tem visão parcial
             '         w_visao = 1
             '         RSquery.Close
             '      Else
             '         ' Caso contrário, tem visão resumida
             '         w_visao = 2
             '      End If
             '   End If
             'End If
             w_visao = 0
             If w_visao < 2 Then               
                DB_GetSolicEtapa RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL"
                If p_programada       > "" and p_exequivel    > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "'"
                ElseIf p_programada   > "" and p_exequivel    > "" Then   
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "'"
                ElseIf p_programada   > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and fim_previsto < '" & Date() & "'"
                ElseIf p_fim_previsto > "" and p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "'"
                ElseIf p_programada   > "" Then
                   RS3.Filter = "programada = '" & p_programada & "'"
                ElseIf p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "'"
                ElseIf p_fim_previsto > "" Then
                   RS3.Filter = "fim_previsto < '" & Date() & "'"
                End If
                RS3.Sort = "ordem"
                If Not RS3.EOF Then
                   w_teste_metas = 1
                ElseIf p_programada = "" and p_exequivel = "" and p_fim_previsto = "" Then
                   w_teste_metas = 3
                End If
             Else
                w_teste_metas = 0
             End If
          Else
             If RS("sq_siw_solicitacao") > "" Then
                w_teste_acoes = 1
                w_teste_metas = 0
             Else
                w_teste_acoes = 0
             End If
          End If
          If w_teste_pai = 1 Then
             RS.MovePrevious
             w_teste_pai = 0
          End If
            
          If w_teste_metas = 1 or w_teste_metas = 3 Then
             'Inicio da montagem da lista das ações e metas de acordo com o filtro
             w_cont = w_cont + 1
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             If Nvl(RS("sq_acao_ppa_pai"),"") = "" or p_programada > "" or p_exequivel > "" or p_fim_previsto > "" or p_atraso > "" Then
                ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                ShowHTML "   <td><font size=""1""><b>" & RS("codigo") & "</td>"
                ShowHTML "   <td><font size=""1""><b>" & RS("nome") & "</td>"
                RS.MoveNext
                w_atual = 1
             Else
                ShowHTML " <tr valign=""top"">"
                ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
             End If
             w_linha = w_linha + 1
             ShowHTML "      <td><font size=""1""><b>" & RS("codigo") & "</td>"
             If w_tipo_rel = "WORD" or cDbl(RS("acao")) = 0 Then
                ShowHTML "   <td><font size=""1""><b>" & RS("nome") & "</td>"
             Else
                ShowHTML "   <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("nome") & "</a></td>"
             End If
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(RS("aprovado"),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(RS("empenhado"),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00)),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(RS("liquidado"),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber((cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))),2) & "</td>" 
             If RS2.EOF Then
                ShowHTML "   <td colspan=""6"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
             Else
                If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                   ShowHTML "<td colspan=""6"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
                Else
                   If w_tipo_rel = "WORD" Then
                      ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                   Else
                      ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_projeto_etapa")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                   End If
                   ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                   ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("quantidade") & "</td>"
                   DB_GetEtapaMensal RS4, RS3("sq_projeto_etapa")
                   RS4.Sort = "referencia desc"
                   If Not RS4.EOF Then
                      If RS3("cumulativa") = "S" Then
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Nvl(RS4("execucao_fisica"),0) & "</td>"
                      Else
                         w_quantitativo_total = 0
                         While Not RS4.EOF
                            w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                            RS4.MoveNext
                         Wend
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & w_quantitativo_total & "</td>"
                      End If
                   Else
                      ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                   End If
                   ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                   ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("nm_programada") & "</td>"
                   RS3.MoveNext
                   If Not RS3.EOF Then
                      While Not RS3.EOF
                         ShowHTML "<tr><td colspan=""9"">&nbsp;"
                         If w_tipo_rel = "WORD" Then
                            ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                         Else
                            ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_projeto_etapa")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                         End If
                         ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("quantidade") & "</td>"
                         DB_GetEtapaMensal RS4, RS3("sq_projeto_etapa")
                         RS4.Sort = "referencia desc"
                         If Not RS4.EOF Then
                            If RS3("cumulativa") = "S" Then
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Nvl(RS4("execucao_fisica"),0) & "</td>"
                            Else
                               w_quantitativo_total = 0
                               While Not RS4.EOF
                                  w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                                  RS4.MoveNext
                               Wend
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & w_quantitativo_total & "</td>"
                            End If
                         Else
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                         End If
                         ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                         ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("nm_programada") & "</td>"
                         w_linha = w_linha + 1
                         RS3.MoveNext
                      Wend
                   End If
                End If
             End If
          Else
             If p_programada = "" and p_exequivel = "" and p_fim_previsto = "" and p_atraso = "" Then
                w_cont = w_cont + 1
                If Nvl(RS("sq_acao_ppa_pai"),"") = "" Then
                   ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                   ShowHTML "   <td><font size=""1""><b>" & RS("codigo") & "</td>"
                   ShowHTML "   <td><font size=""1""><b>" & RS("nome") & "</td>"
                   RS.MoveNext
                   w_atual = 1
                Else
                   ShowHTML " <tr valign=""top"">"
                   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                End If
                w_linha = w_linha + 1
                If w_teste_acoes = 1 Then
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("codigo") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("nome") & "</b></td>"
                   If w_teste_metas = 3 Then
                      ShowHTML "        <td colspan=""11"" align=""center""><font size=""1""><b>Não foram encontrados registros.<b></td>"
                   Else
                      ShowHTML "        <td colspan=""11"" align=""center""><font size=""1""><b>Não há permissão para visualização da ação<b></td>"
                   End If
                Else
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("codigo") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("nome") & "</b></td>"
                   ShowHTML "        <td colspan=""11"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
                End If
             End If
          End If
          RS.MoveNext
       wend
    End If
    If w_cont = 0 Then
       ShowHTML "        <td colspan=""17"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tabela PPA",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>P</u>rograma:", "S", null, p_sq_acao_ppa_pai, w_chave, "p_sq_acao_ppa_pai", "CADASTRO", null
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>A</u>ção:", "A", null, p_sq_acao_ppa, w_chave, "p_sq_acao_ppa", "IDENTIFICACAO", null
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr>"
    'SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada MP?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mpog"" value="""" checked> Tanto faz"
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/MS?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_relevante"" value="""" checked> Tanto faz"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas da LOA?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_programada"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_programada"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas que não serão cumpridas?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value=""N""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value="""" checked> Não"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente ações em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_atraso"" value="""" checked> Não"
    'ShowHTML "      <tr valign=""top"">"
    'ShowHTML "          <td><font size=""1""><b>Exibir ações com tarefas em atraso?</b><br>"
    'ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set p_sq_acao_ppa             = Nothing 
  Set p_sq_acao_ppa_pai         = Nothing 
  Set p_selecionada_mpog        = Nothing 
  Set p_selecionada_relevante   = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
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
    Case "ORTBINIC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutOrPrioridade O, _
              Request("w_chave"), w_cliente, Request("w_codigo"), Request("w_nome"), _
              Request("w_responsavel"), Request("w_telefone"), Request("w_email"), _
              Request("w_ordem"), Request("w_ativo"), Request("w_padrao")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORTBPPA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutAcaoPPA O, _
              Request("w_chave"), w_cliente, Request("w_sq_acao_ppa_pai"), _
              Request("w_codigo"), Request("w_nome"), _
              Request("w_responsavel"), Request("w_telefone"), Request("w_email"), _
              Request("w_ativo"), Request("w_padrao"), Request("w_aprovado"), Request("w_saldo"), _
              Request("w_empenhado"), Request("w_liquidado"), Request("w_liquidar"), _
              Request("w_selecionada_mpog"), Request("w_selecionada_relevante"), null, null

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
REM Gera uma linha de apresentação da tabela de etapas
REM -------------------------------------------------------------------------
Function EtapaLinha (p_chave, p_chave_aux, p_titulo, p_word, p_programada, _
                     p_unidade_medida, p_quantidade, p_fim, p_perc, p_oper, p_tipo)
  Dim l_html, RsQuery, l_recurso, l_row

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">"
  If p_fim < Date() and cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
  ElseIf cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
  Else
     l_html = l_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
  End IF
  If p_word <> "WORD" Then
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_titulo & "</A>"
  Else
     l_html = l_html & VbCrLf & "        " & p_titulo & "</td>"
  End if
  l_html = l_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RetornaSimNao(p_programada) & "</b>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">" & Nvl(p_unidade_medida, "---") & " </td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_quantidade & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  l_html = l_html & VbCrLf &  "      </tr>"

  EtapaLinha = l_html

  Set RsQuery   = Nothing
  Set l_row     = Nothing
  Set l_recurso = Nothing
  Set l_html    = Nothing
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIATIVA"
       Iniciativa
    Case "PPA"
       PPA
    Case "REL_PPA"
       Rel_PPA
    Case "REL_INICIATIVA"
       Rel_Iniciativa
    Case "REL_SINTETICO_IP"
       Rel_Sintetico_IP
    Case "REL_SINTETICO_PPA"
       Rel_Sintetico_PPA
    Case "GRAVA"
       Grava
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