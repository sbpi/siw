<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /IS_Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva
REM Descricao: Gerenciar tabelas básicas do módulo	
REM Mail     : beto@sbpi.com.br
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

w_Pagina     = "IS_Tabelas.asp?par="
w_Dir        = "mod_is/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then 
   O = "L"
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
w_ano             = RetornaAno()

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
REM Manter Tabela básica "Natureza"
REM -------------------------------------------------------------------------
Sub Natureza

  Dim w_chave, w_nome, w_ativo
  
  w_Chave          = Request("w_Chave")
  w_troca          = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_chave       = Request("w_chave")
     w_nome        = Request("w_nome")
     w_ativo       = Request("w_ativo")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetNatureza_IS RS, null, w_cliente, null, null
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados chave informada
     DB_GetNatureza_IS RS, w_chave, Session("p_cliente"), null, null
     w_chave       = RS("chave")
     w_nome        = RS("nome")
     w_ativo       = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome"      , "Nome"                 , "1", "1", "4", "30", "1", "1"
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
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
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
    ShowHTML "           <td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
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

  Set w_chave   = Nothing
  Set w_nome    = Nothing
  Set w_ativo   = Nothing 
  Set w_troca   = Nothing 
End Sub

REM =========================================================================
REM Manter Tabela básica "Horizonte"
REM -------------------------------------------------------------------------
Sub Horizonte


  Dim w_chave, w_nome, w_ativo
  
  w_Chave          = Request("w_Chave")
  w_troca          = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_chave       = Request("w_chave")
     w_nome        = Request("w_nome")
     w_ativo       = Request("w_ativo")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetHorizonte_IS RS, null, w_cliente, null, null
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetHorizonte_IS RS, w_chave, Session("p_cliente"), null, null
      w_chave       = RS("chave")
      w_cliente     = RS("cliente")
      w_nome        = RS("nome")
      w_ativo       = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome"      , "Nome"                 , "1", "1", "4", "30", "1", "1"
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
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
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
    ShowHTML "           <td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
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
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave   = Nothing
  Set w_nome    = Nothing
  Set w_ativo   = Nothing 
  Set w_troca   = Nothing 
End Sub

REM =========================================================================
REM Manter Tabela básica "Projetos"
REM -------------------------------------------------------------------------
Sub Projetos

  Dim w_chave, w_codigo, w_nome, w_responsavel, w_telefone, w_email, w_ordem, w_ativo, w_padrao
  
  w_Chave          = Request("w_Chave")
  w_troca          = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_chave       = Request("w_chave")
     w_codigo      = Request("w_codigo")
     w_nome        = Request("w_nome")
     w_responsavel = Request("w_responsavel")
     w_telefone    = Request("w_telefone")
     w_email       = Request("w_email")
     w_ordem       = Request("w_ordem")
     w_ativo       = Request("w_ativo")
     w_padrao      = Request("w_padrao")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetProjeto_IS RS, null, w_cliente, null, null, null, null, null, null, null, null, null, null, "CADASTRAMENTO", null
     RS.Sort = "Nome" 
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados chave informada
     DB_GetProjeto_IS RS, w_chave, w_cliente, null, null, null, null, null, null, null, null, null, null, "CADASTRAMENTO", null
     w_chave       = RS("chave")
     w_codigo      = RS("codigo")
     w_nome        = RS("nome")
     w_responsavel = RS("responsavel")
     w_telefone    = RS("telefone")
     w_email       = RS("email")
     w_ordem       = RS("ordem")
     w_ativo       = RS("ativo")
     w_padrao      = RS("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_codigo"     , "Codigo"               , "1", "",   "1",  "50", "1", "1"
        Validate "w_nome"       , "Nome"                 , "1", "1",  "3", "100", "1", "1"
        Validate "w_responsavel", "Responsavel"          , "1", "",   "3",  "60", "1", "1"
        Validate "w_telefone"   , "Telefone"             , "1", "",   "2",  "20",  "", "0123456789- "
        Validate "w_email"      , "Email"                , "1", "",   "6",  "60", "1", "1"
        Validate "w_ordem"      , "Ordem"                , "1", "1",  "1",   "4",  "", "1"
        Validate "w_assinatura" , "Assinatura Eletrônica", "1", "1",  "6",  "30", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_codigo.focus()';"
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
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
    ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"            
    ShowHTML "          <td><font size=""1""><b>email</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ordem</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("Codigo"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ordem") & "</td>"
        If RS("ativo")="S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("padrao")="S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
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
    ShowHTML "           <td colspan=3><font size=""1""><b><u>C</u>ódigo:</b><br><input      " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_codigo""      class=""sti"" SIZE=""12"" MAXLENGTH=""50""  VALUE=""" & w_codigo      & """></td>"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>N</u>ome:</b><br><input        " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome""        class=""sti"" SIZE=""30"" MAXLENGTH=""100"" VALUE=""" & w_nome        & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_responsavel"" class=""sti"" SIZE=""30"" MAXLENGTH=""60""  VALUE=""" & w_responsavel & """></td>"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>T</u>elefone:</b><br><input    " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_telefone""    class=""sti"" SIZE=""12"" MAXLENGTH=""20""  VALUE=""" & w_telefone    & """></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>E</u>mail:</b><br><input       " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_email""       class=""sti"" SIZE=""20"" MAXLENGTH=""60""  VALUE=""" & w_email       & """></td>"
    ShowHTML "           <td colspan=3><font size=""1""><b><u>O</u>rdem:</b><br><input       " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_ordem""       class=""sti"" SIZE=""4"" MAXLENGTH=""4""    VALUE=""" & w_ordem       & """></td>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Ativo?</b>" , w_ativo , "w_ativo"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Padrão?</b>", w_padrao, "w_padrao"
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

  Set w_chave       = Nothing
  Set w_codigo      = Nothing
  Set w_nome        = Nothing
  Set w_responsavel = Nothing
  Set w_telefone    = Nothing
  Set w_email       = Nothing
  Set w_ordem       = Nothing  
  Set w_ativo       = Nothing
  Set w_padrao      = Nothing
  Set w_troca       = Nothing
End Sub

REM =========================================================================
REM Rotina de unidade
REM -------------------------------------------------------------------------
Sub Unidade

  Dim w_nome, w_sigla, w_chave1, w_administrativa, w_planejamento
  
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  
  If w_troca > "" Then ' Se for recarga da página
     w_nome                 = Request("nome")
     w_sigla                = Request("sigla")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetIsUnidade_IS RS, null, w_cliente, null, null
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetIsUnidade_IS RS, w_chave, w_cliente, null, null
     w_nome                 = RS("nome")
     w_sigla                = RS("sigla")
     w_administrativa       = RS("administrativa")
     w_planejamento         = RS("planejamento")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataCNPJ
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        If O = "I" Then
           Validate "w_chave", "Unidade", "1", "1", "1", "50", "1", "1"
        End If
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
  ElseIf Instr("A",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
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
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Planejamento</font></td>"
    ShowHTML "          <td><font size=""1""><b>Administrativa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") &" (" &RS("sigla") & ")</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("planejamento")) &"</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RetornaSimNao(RS("administrativa")) &"</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        If RS("planejamento") = "S" Then
           ShowHTML "          <A class=""hl"" HREF=""javascript:document.focus();"" onClick=""window.open('" & w_Pagina & "Limites&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ISUNILIMITE','Gestao','width=630,height=500,top=30,left=30,status=yes,resizable=yes,scrollbars=yes,toolbar=yes');"" title=""Configura limites orçamentários anuais para a unidade."">Limites</A>&nbsp"
        End If
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
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave1"" value=""" &w_chave1& """>"
    If O <> "I" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" &w_chave& """>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoUnidade "<U>U</U>nidade:", "S", null, w_chave, null, "w_chave", null, null
    Else
       ShowHTML "        <tr><td><font size=1>Unidade:<br><b>" & w_nome & " (" & w_sigla & ")</b><br><br>"
    End If
    ShowHTML "           </table>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    MontaRadioNS "<b>Planejamento?</b>", w_planejamento, "w_planejamento"
    MontaRadioNS "<b>Administrativa?</b>", w_administrativa, "w_administrativa"
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_nome                = Nothing
  Set w_sigla               = Nothing 
  Set w_chave               = Nothing
End Sub

REM =========================================================================
REM Rotina de limites orçamentários
REM -------------------------------------------------------------------------
Sub Limites

  Dim w_limite, w_ano
  
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  
  ' Recupera os dados da unidade selecionada
  DB_GetIsUnidade_IS RS1, w_chave, w_cliente, null, null
  
  If w_troca > "" Then ' Se for recarga da página
     w_ano                  = Request("w_ano")
     w_limite               = Request("w_limite")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetIsUnidadeLimite_IS RS, w_chave, null, w_cliente
     RS.Sort = "ano, nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetIsUnidadeLimite_IS RS, w_chave, w_ano, w_cliente
     w_ano                  = RS("ano")
     w_limite               = FormatNumber(RS("limite_orcamento"),2)
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_ano", "Ano", "SELECT", "1", "4", "4", "", "0123456789"
        Validate "w_limite", "Limite financeiro para passagens", "VALOR", "1", 4, 18, "", "0123456789.,"
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
  ElseIf Instr("A",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" height=""1"" bgcolor=""#000000"" colspan=""2""></td></tr>"
  ShowHTML "<tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0"" colspan=""2""><font size=""2""><b>" & RS1("nome") & " - Limites orçamentários</td></td></tr>"
  ShowHTML "<tr><td align=""center"" height=""1"" bgcolor=""#000000"" colspan=""2""></td></tr>"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "            <a accesskey=""F"" class=""SS"" href=""javascript:window.close(); opener.focus();""><u>F</u>echar</a>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ano","ano") & "</font></td>"    
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Limite","limite") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ano") & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("limite_orcamento"),2) &"</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&w_ano=" & RS("ano") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&w_ano=" & RS("ano") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr valign=""top"">"
    If O = "I" Then
       SelecaoAno "<U>A</U>no:", "A", null, w_ano, null, "w_ano", null, null
    Else
       ShowHTML "          <td><font size=""1""><b>Ano:<br>" & w_ano & "</b></td>"
       ShowHTML "<INPUT type=""hidden"" name=""w_ano"" value=""" & w_ano & """>"
    End If
    ShowHTML "          <td><font size=""1""><b><u>L</u>imite:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_limite"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_limite & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o limite orçamentário para a unidade selecionada.""></td>"
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2""><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_limite      = Nothing
  Set w_ano         = Nothing
End Sub

REM =========================================================================
REM Rotina de seleção do ano
REM -------------------------------------------------------------------------
Sub SelecionarAno
  
  Dim w_ano_escolhido
    
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  Validate "w_ano_escolhido", "Ano", "SELECT", "1", "4", "4", "", "1"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_ano_escolhido.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina&par,O
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
  SelecaoAno "<U>A</U>no:", "A", null, w_ano, null, "w_ano_escolhido", null, null
  ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_ano_escolhido   = Nothing
End Sub

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
    Case "ISNATUREZA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutNatureza_IS O, Request("w_chave"), Session("p_cliente"), Request("w_nome"), _
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
    Case "ISHORIZONT"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutHorizonte_IS O, _
          Request("w_chave"), Session("p_cliente"), Request("w_nome"), Request("w_ativo")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISTBPROJ"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutProjeto_IS O, Request("w_chave")      , Session("p_cliente") , Request("w_codigo"), Request("w_nome") , _
                               Request("w_responsavel"), Request("w_telefone"), Request("w_email") , Request("w_ordem"), _
                               Request("w_ativo")      , Request("w_padrao")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISUNIDADE"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          If O = "I" Then
             DB_GetIsUnidade_IS RS, Request("w_Chave"), w_cliente, null, null
             If RS.RecordCount = 0 Then
                DML_PutIsUnidade_IS O, Request("w_chave"), Request("w_administrativa"), Request("w_planejamento")
                ScriptOpen "JavaScript"
                ShowHTML "  location.href='" & R & "&w_chave=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
                ScriptClose
             Else
                ScriptOpen "JavaScript"
                ShowHTML "  alert('Unidade já cadastrada!');"
                ShowHTML "  history.back(1);"
                ScriptClose
            End If
         Else
            DML_PutIsUnidade_IS O, Request("w_chave"), Request("w_administrativa"), Request("w_planejamento")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         End If
         
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISUNILIMITE"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          'ExibeVariaveis
          If O = "I" Then
             DB_GetIsUnidadeLimite_IS RS, Request("w_Chave"), Request("w_ano"), w_cliente
             If RS.RecordCount = 0 Then
                DML_PutIsUnidadeLimite_IS O, Request("w_chave"), Request("w_ano"), Request("w_limite")
                ScriptOpen "JavaScript"
                ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
                ScriptClose
             Else
                ScriptOpen "JavaScript"
                ShowHTML "  alert('Exercício já cadastrado para a unidade informada!');"
                ShowHTML "  history.back(1);"
                ScriptClose
            End If
         Else
            DML_PutIsUnidadeLimite_IS O, Request("w_chave"), Request("w_ano"), Request("w_limite")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         End If
         
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISANO"

       Session("ANO") = Request("w_ano_escolhido")
       ScriptOpen "JavaScript"
       ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs';"
       ScriptClose
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
    Case "NATUREZA"     Natureza
    Case "HORIZONTE"    Horizonte
    Case "PROJETOS"     Projetos
    Case "UNIDADE"      Unidade
    Case "LIMITES"      Limites
    Case "SELECIONARANO" SelecionarAno
    Case "GRAVA"        Grava
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
%>