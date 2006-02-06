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
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta, w_chave, w_chaveAux
Dim w_sq_pessoa
Dim ul,File

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
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

w_Pagina     = "Tabelas.asp?par="
w_Dir        = "mod_tt/"
w_Dir_volta  = "../"
w_Disabled   = "ENABLED"

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
  Case "C"
     w_TP = TP & " - Cópia"
  Case "F"
     w_TP = TP & " - Finalizar"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()

If SG <> "TTUSUCTRL" and SG <> "TTTRONCO" and SG <> "RAMUSR" then
  w_menu         = RetornaMenu(w_cliente, SG) 
Else
  w_menu         = RetornaMenu(w_cliente, Request("w_SG")) 
End If

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
If SG <> "TTUSUCTRL" and SG <> "TTTRONCO" and SG <> "RAMUSR" Then 
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
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set w_chave      = Nothing
Set w_copia      = Nothing
Set w_filtro     = Nothing
Set w_menu       = Nothing
Set w_usuario    = Nothing
Set w_cliente    = Nothing
Set w_filter     = Nothing
Set w_cor        = Nothing
Set ul           = Nothing
Set File         = Nothing
Set w_sq_pessoa  = Nothing
Set w_troca      = Nothing
Set w_submenu    = Nothing
Set w_reg        = Nothing

Set RS           = Nothing
Set RS1          = Nothing
Set RS2          = Nothing
Set RS3          = Nothing
Set RS4          = Nothing
Set RS_menu      = Nothing
Set Par          = Nothing
Set P1           = Nothing
Set P2           = Nothing
Set P3           = Nothing
Set P4           = Nothing
Set TP           = Nothing
Set SG           = Nothing
Set R            = Nothing
Set O            = Nothing
Set w_Classe     = Nothing
Set w_Cont       = Nothing
Set w_Pagina     = Nothing
Set w_Disabled   = Nothing
Set w_TP         = Nothing
Set w_Assinatura = Nothing

REM =========================================================================
REM Rotina da Central Telefônica
REM -------------------------------------------------------------------------
Sub centralTel

  Dim w_sq_pessoa_endereco, w_arquivo_bilhetes, w_recupera_bilhetes
  Dim p_sq_pessoa_endereco
  w_Chave              = Request("w_Chave")
  w_troca              = Request("w_troca")
  w_sq_pessoa_endereco = Request("w_sq_pessoa_endereco")
  w_arquivo_bilhetes   = Request("w_arquivo_bilhetes")
  w_recupera_bilhetes  = Request("w_recupera_bilhetes")
  p_sq_pessoa_endereco = Request("p_sq_pessoa_endereco")
  If w_troca > "" Then
  w_sq_pessoa_endereco = Request("w_sq_pessoa_endereco")
  w_arquivo_bilhetes   = Request("w_arquivo_bilhetes")
  w_recupera_bilhetes  = Request("w_recupera_bilhetes")
  ElseIf O = "L" Then
    DB_GetCentralTel RS, null, null, p_sq_pessoa_endereco, null, null
    RS.Sort = Request("p_ordena")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetCentralTel RS, w_chave, w_cliente, w_sq_pessoa_endereco, null, null
    w_sq_pessoa_endereco = RS("sq_pessoa_endereco")
    w_arquivo_bilhetes   = RS("arquivo")
    w_recupera_bilhetes  = RS("recupera")
    DesconectaBD
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    If InStr("IA",O) > 0 Then
      Validate "w_sq_pessoa_endereco" , "Endereço"              , "SELECT" , "1" , "1" , "18" , "1" , "1"
      Validate "w_arquivo_bilhetes"   , "Arquivo"               , "1"      , "1" , "1" , "60" , "1" , "1"
      Validate "w_assinatura"         , "Assinatura Eletrônica" , "1"      , "1" , "6" , "30" , "1" , "1"
    ElseIf O = "E" Then
      Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
      ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
      ShowHTML "     { return (true); }; "
      ShowHTML "     { return (false); }; "
    ElseIf O="P" Then
      Validate "p_sq_pessoa_endereco"          , "Cidade"                , "SELECT" , "1" , "1" , "18" , "1" , "1"
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
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    If p_sq_pessoa_endereco > "" Then
      ShowHTML " <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
      ShowHTML " <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If P2 > 0 Then
       ShowHTML "          <td><font size=""1""><b> Cidade    </font></td>"
       ShowHTML "          <td><font size=""1""><b> Endereço  </font></td>"
    Else 
       IF RS.RecordCount > 1 then
          ShowHTML "           <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=nm_cidade,logradouro&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Cidade</a></font></td>"
          ShowHTML "           <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=logradouro,nm_cidade&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Endereço</a></font></td>"
       Else
          ShowHTML "          <td><font size=""1""><b> Cidade    </font></td>"
          ShowHTML "          <td><font size=""1""><b> Endereço  </font></td>"
       End If
    End If
    ShowHTML "          <td><font size=""1""><b> Arquivo   </font></td>"    
    ShowHTML "          <td><font size=""1""><b> Recupera  </font></td>"
    ShowHTML "          <td><font size=""1""><b> Operações </font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_cidade")  & " - " & RS("uf") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("logradouro") & "                   </td>"
        ShowHTML "        <td><font size=""1"">" & RS("arquivo")    & "                   </td>" 
        if RS("recupera") = "S" then 
          ShowHTML " <td align=""center""><font size=""1"">Sim</td>" 
        else 
          ShowHTML " <td align=""center""><font size=""1"">Não</td>" 
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina &            par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&w_sq_pessoa_endereco="& RS("sq_pessoa_endereco") & "&w_arquivo_bilhetes=" &  RS("arquivo") & "&w_recupera_bilhetes=" &  RS("recupera") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "             &SG=" & SG & MontaFiltro("GET") & """                   Title=""Nome                                            "">Alterar </A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina &            par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&w_sq_pessoa_endereco="& RS("sq_pessoa_endereco") & "&w_arquivo_bilhetes=" &  RS("arquivo") & "&w_recupera_bilhetes=" &  RS("recupera") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "             &SG=" & SG &                                                                                                   """>Excluir </A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "TRONCOS          &R= " & w_Pagina & par & "&O=L&w_chave=" & RS("chave") &                                                                                                                                            "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & " - Parâmetros&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Visualizar e manipular os trocos desta central  "">Tronco  </A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "USUARIOCENTRAL   &R= " & w_Pagina & par & "&O=L&w_chave=" & RS("chave") &                                                                                                                                            "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & " - Parâmetros&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Visualizar e manipular os usuários desta central"">Usuários</A>&nbsp"
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
    If InStr("EV",O) Then
      w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0""><tr>"
    SelecaoEndereco "End<u>e</u>reço:", "E", null, w_sq_pessoa_endereco, w_sq_pessoa_endereco, "w_sq_pessoa_endereco", "FISICO"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_arquivo_bilhetes"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_arquivo_bilhetes & """></td>"
    MontaRadioSN "Recupera Bilhetes", w_recupera_bilhetes, "w_recupera_bilhetes"
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
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
  ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
    AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoCidadeCentral "<u>C</u>idade:", "C", null, p_sq_pessoa_endereco, "p_sq_pessoa_endereco", null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_cliente            = Nothing
  Set w_sq_pessoa_endereco = Nothing
  Set w_arquivo_bilhetes   = Nothing 
  Set w_recupera_bilhetes  = Nothing 
  Set w_sq_pessoa_endereco = Nothing
  Set w_troca              = Nothing
  Set w_chave              = Nothing
  Set p_sq_pessoa_endereco = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de arquivos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Troncos da Central
REM -------------------------------------------------------------------------
Sub Troncos
  Dim w_sq_central_fone, w_sq_pessoa_telefone, w_codigo, w_ativo, w_chaveAux
  
  w_Chave              = Request("w_Chave")
  w_troca              = Request("w_troca")
  w_sq_central_fone    = Request("w_sq_central_fone")
  w_sq_pessoa_telefone = Request("w_sq_pessoa_telefone")
  w_codigo             = Request("w_codigo")
  w_ativo              = Request("w_ativo")
  w_chaveAux           = Request("w_chaveAux")
  ' Recupera sempre todos os registros
  DB_GetCentralTel RS, w_chave, null, null, null, null
  RS.Sort = Request("p_ordena")
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>SIW - Troncos da Central</TITLE>"
  If InStr("IAEP",O) > 0 Then
    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    If InStr("IA",O) > 0 Then
      Validate "w_codigo"             , "Codigo"                , "1"     , "1", "1", "10"  , "1" , "1"
      Validate "w_sq_pessoa_telefone" , "Telefone"              , "SELECT", "1", "1", "18"  , "1" , "1"
      Validate "w_assinatura"         , "Assinatura Eletrônica" , "1"     , "1", "6", "30"  , "1" , "1"
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
  If O = "E" Then
    BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
    BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=4><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Cidade-UF: <br><b>" & RS("nm_cidade")  & " - " & RS("uf") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Endereço:  <br><b>" & RS("logradouro")                    & "</font></td>"
  ShowHTML "        <tr colspan=3>"
  ShowHTML "          <td><font size=""1"">Arquivo:   <br><b>"  & RS("arquivo")                      & "</font></td>"
  if RS("recupera") = "S" then 
    ShowHTML "        <td><font size=""1"">Recupera:  <br><b>Sim                                        </font></td>"
  else 
    ShowHTML "        <td><font size=""1"">Recupera:  <br><b>Não                                        </font></td>"
  End If
  ShowHTML "    </TABLE>"
  ShowHTML "</table>"
  DesconectaBD
  If O = "L" Then
    DB_GetCentralTel RS, w_chave, w_cliente, w_sq_central_fone, w_sq_pessoa_telefone, "TRONCO"
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If P2 > 0 then
       ShowHTML "          <td><font size=""1""><b>Telefone  </b></font></td>"
       ShowHTML "          <td><font size=""1""><b>Código    </b></font></td>"
       ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
       ShowHTML "          <td><font size=""1""><b>Ativo     </b></font></td>"
    Else
       ShowHTML "          <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=num_tel, codigo, ativo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Prefixo</a></font></td>"
       ShowHTML "          <td><font size=""1""><b><a class=""SS"" href=""" & w_pagina & par & "&p_ordena=codigo, num_tel, ativo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Prefixo</a></font></td>"
       ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
       ShowHTML "          <td><font size=""1""><b><a class=""SS"" href=""" & w_pagina & par & "&p_ordena=ativo, num_tel, codigo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Prefixo</a></font></td>"
    End If
    ShowHTML "          <td><font size=""1""><b>Operações </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      'Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">(" & RS("ddd")   & ") " & RS("num_tel")&" </td>"
        ShowHTML "        <td><font size=""1""> " & RS("codigo")       &               " </td>"
        ShowHTML "        <td><font size=""1""> " & RS("nm_tipo"  )    &               " </td>"
        if RS("ativo") = "S" then 
        ShowHTML "        <td><font size=""1"">Sim                               </font> </td>"
          else 
        ShowHTML "        <td><font size=""1"">Não                               </font> </td>"
          End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_pagina & par & "&O=A&w_chaveAux=" & RS("chave") &"&w_sq_pessoa_telefone=" &  RS("sq_pessoa_telefone") & "&w_codigo=" & RS("codigo") & "&w_ativo=" & RS("ativo") & "&w_chave="& Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_pagina & par & "&O=E&w_chaveAux=" & RS("chave") &"&w_sq_pessoa_telefone=" &  RS("sq_pessoa_telefone") & "&w_codigo=" & RS("codigo") & "&w_ativo=" & RS("ativo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Exluir</A>&nbsp"
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
   'Aqui começa a manipulação de registros
  ElseIf Instr("AIEV",O) > 0 Then
    If InStr("EV",O) Then w_Disabled = " DISABLED " End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"TTTRONCO",R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<tr><td align=""center"" colspan=4>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_central_fone"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chaveAux"" value=""" & w_chaveAux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>C</u>odigo:   </b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_codigo"" class=""sti"" SIZE=""8""  MAXLENGTH=""4""  VALUE=""" & w_codigo & """></td>"
    MontaRadioSN    "Ativo", w_ativo, "w_ativo"
    If O = "I" then
      SelecaoTelefone2 "<u>T</u>elefône:", "T", null, w_sq_pessoa_telefone, w_cliente, "w_sq_pessoa_telefone", null, "TRONCO"
    else
      SelecaoTelefone2 "<u>T</u>elefône:", "T", null, w_sq_pessoa_telefone, w_cliente, "w_sq_pessoa_telefone", "A", null
    End If
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
    If O = "E" Then
      ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
      If O = "I" Then
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
      Else
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
      End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
    DesconectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
      
  Set w_chave              = Nothing
  Set w_sq_central_fone    = Nothing 
  Set w_sq_pessoa_telefone = Nothing 
  Set w_codigo             = Nothing 
  Set w_ativo              = Nothing 
  Set w_chaveAux           = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e tabelas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Ramais
REM -------------------------------------------------------------------------
Sub Ramais

  Dim w_sq_central_fone, w_codigo
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  w_sq_central_fone = Request("w_sq_central_fone")
  w_codigo          = Request("w_codigo")
  
  If w_troca > "" Then
  w_sq_central_fone = Request("w_sq_central_fone")
  w_codigo          = Request("w_codigo")
  
  ElseIf O = "L" Then
    DB_GetTTRamal RS, null, null, null, null
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetTTRamal RS, w_chave, w_sq_central_fone, w_codigo, null
    w_chave           = RS("chave")
    w_sq_central_fone = RS("sq_central_fone")
    w_codigo          = RS("codigo")
    DesconectaBD
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    If InStr("IA",O) > 0 Then
      Validate "w_sq_central_fone" , "Central Telefônica"    , "SELECT" , "1" , "1" , "18" , "1" , "1"
      Validate "w_codigo"          , "Código"                , "1"      , "1" , "1" , "4"  , "1" , "1"
      Validate "w_assinatura"      , "Assinatura Eletrônica" , "1"      , "1" , "6" , "30" , "1" , "1"
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
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b> Cidade    </font></td>"
    ShowHTML "          <td><font size=""1""><b> Endereço  </font></td>"
    ShowHTML "          <td><font size=""1""><b> Ramal     </font></td>"    
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
        ShowHTML "        <td><font size=""1"">" & RS("nm_cidade")  & " - " & RS("uf") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("logradouro") & "                   </td>"
        ShowHTML "        <td><font size=""1"">" & RS("codigo")     & "                   </td>" 
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R= " & W_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "RAMALUSR&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & " - Parâmetros&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Visualizar e manipular os usuários deste ramal"">Usuários</A>&nbsp"
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
    If InStr("EV",O) Then
      w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0""><tr>"
    SelecaoCentralFone "Central Tele<u>f</u>ônica:", "F", null, w_sq_central_fone, "w_sq_central_fone", null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Có<u>d</u>igo</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_codigo"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_codigo & """></td>"
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
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

  Set w_chave           = Nothing
  Set w_sq_central_fone = Nothing
  Set w_codigo          = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de arquivos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos Usuarios da Central Telefônica
REM -------------------------------------------------------------------------
Sub UsuarioCentral
  Dim w_sq_central_fone, w_codigo, w_usuario
  
  w_Chave           = Request("w_Chave")
  w_ChaveAux        = Request("w_ChaveAux")
  w_troca           = Request("w_troca")
  w_usuario         = Request("w_usuario")
  w_sq_central_fone = Request("w_sq_central_fone")
  w_codigo          = Request("w_codigo")
  
  ' Recupera sempre todos os registros
  DB_GetCentralTel RS, w_chave, null, null, null, null
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>SIW - Associação entre Central telefônica e Usuários</TITLE>"
  If InStr("IAEP",O) > 0 Then
    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    If InStr("IA",O) > 0 Then
      Validate "w_usuario"    , "Usuário"               , "SELECT", "1", "1", "18" , "1" , "1"
      Validate "w_codigo"     , "código"                , "1"     , "1", "2", "2"  , "1" , ""
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6", "30" , "1" , "1"
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
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "          <td><br><b>Central Telefônica</td>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Cidade-UF: <br><b>" & RS("nm_cidade")  & " - " & RS("uf") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Endereço:  <br><b>" & RS("logradouro")                    & "</font></td>"
  ShowHTML "        <tr colspan=3>"
  ShowHTML "          <td><font size=""1"">Arquivo:   <br><b>"  & RS("arquivo")                      & "</font></td>"
  if RS("recupera") = "S" then 
    ShowHTML "        <td><font size=""1"">Recupera:  <br><b>Sim                                        </font></td>"
  else 
    ShowHTML "        <td><font size=""1"">Recupera:  <br><b>Não                                        </font></td>"
  End If
  ShowHTML "    </TABLE>"
  ShowHTML "</table>"
  ShowHTML "</table>"
  DesconectaBD
  If O = "L" Then
    DB_GetCentralTel RS, w_chave, w_cliente, null, null, "USER"
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<div align=center><center>"
    ShowHTML "          <td><br><b>Usuários</td>"
    ShowHTML "<div align=left><left>"
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_central_fone=" &  RS("sq_central_fone") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Código    </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome      </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      'Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("codigo")     & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_usuario") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chaveAux=" & RS("sq_usuario_central") &"&w_sq_central_fone=" &  RS("sq_central_fone") & "&w_codigo=" &  RS("codigo") & "&w_usuario=" &  RS("usuario") &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chaveAux=" & RS("sq_usuario_central") &"&w_sq_central_fone=" &  RS("sq_central_fone") & "&w_codigo=" &  RS("codigo") & "&w_usuario=" &  RS("usuario") &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
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
  ElseIf Instr("AIEV",O) > 0 Then
    If InStr("EV",O) Then w_Disabled = " DISABLED " End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"TTUSUCTRL",R,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chaveAux"" value=""" & w_chaveAux & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_sq_central_fone"" value=""" & w_sq_central_fone & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
    If O = "I" Then
      SelecaoPessoa2 "Usua<u>r</u>io:", "R", null, null, w_sq_central_fone, "w_usuario", null, "TTUSUCENTRAL"
    Else
      SelecaoPessoa2 "Usua<u>r</u>io:", "R", null, w_usuario, w_sq_central_fone, "w_usuario", "A", "PESSOA"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>C</u>odigo:   </b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_codigo"" class=""sti"" SIZE=""8""  MAXLENGTH=""2""  VALUE=""" & w_codigo & """></td>"
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"    
    If O = "E" Then
    ShowHTML "      <td>"
      ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
      If O = "I" Then
        ShowHTML "      <td>"
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
      Else
        ShowHTML "      <td>"
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
      End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
    DesconectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_sq_central_fone  = Nothing
  Set w_codigo           = Nothing
  Set w_usuario          = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e tabelas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos Usuarios da Central Telefônica
REM -------------------------------------------------------------------------
Sub RamalUsr
  Dim w_inicio, w_fim, w_chaveAux2
  
  w_Chave     = Request("w_Chave")
  w_chaveAux  = Request("w_chaveAux")
  w_chaveAux2 = Request("w_inicio")
  w_troca     = Request("w_troca")
  w_inicio    = Request("w_inicio")
  w_fim       = Request("w_fim")
  
  ' Recupera sempre todos os registros
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>SIW - Associação entre Central telefônica e Usuários</TITLE>"
  If InStr("IAEF",O) > 0 Then
    ScriptOpen "JavaScript"
    CheckBranco
    Modulo
    FormataData
    ValidateOpen "Validacao"
    If O = "I" Then
      Validate "w_chaveaux"   , "Usuário"               , "SELECT", "1", "1" , "18", "1", "1"
      Validate "w_inicio"     , "Início"                , "DATA"  , "1", "8", "10", "" , "0123456789/"
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6" , "30", "1", "1"
    ElseIf O = "A" then
      Validate "w_inicio"     , "Início"                , "DATA"  , "1", "10", "10", "" , "0123456789/"
      If Nvl(w_fim,"") <> "" then
        Validate "w_fim"      , "Fim"                   , "DATA"  , "1", "10", "10", "" , "0123456789/"
        CompData "w_inicio"   , "Início"                , "<="    , "w_fim" , "Fim"
      End If
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6" , "30", "1", "1"
    ElseIf O = "E" then
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6" , "30", "1", "1"
      ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
      ShowHTML "     { return (true); }; "
      ShowHTML "     { return (false); }; "
    ElseIf O = "F" then
      Validate "w_fim"        , "Fim"                   , "DATA"  , "1", "10", "10", "" , "0123456789/"
      CompData "w_fim"        , "Fim"                   , ">="    , "w_inicio" , "Início"
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6" , "30", "1", "1"
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
  ElseIf O = "E" Then
    BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf O = "F" then
    BodyOpen "onLoad='document.Form.w_fim.focus()';"
  Else
    BodyOpen "onLoad='document.focus()';"
  End If
  DB_GetTTRamal RS, w_chave, null, null, null
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "          <td><br><b>Ramal</td>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=3><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Cidade-UF: <br><b>" & RS("nm_cidade")  & " - " & RS("uf") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Endereço:  <br><b>" & RS("logradouro")                    & "</font></td>"
  ShowHTML "          <td><font size=""1"">Ramal:     <br><b>" & RS("codigo")                        & "</font></td>"
  ShowHTML "    </TABLE>"
  ShowHTML "</table>"
  ShowHTML "</table>"
  DesconectaBD  
  If O = "L" Then
    DB_GetTTRamal RS, w_chave, null, null, "USER"
    RS.sort = "inicio desc, dt_fim desc, nm_usuario"
    ShowHTML "<div align=center><center>"
    ShowHTML "          <td><br><b>Usuários</td>"
    ShowHTML "<div align=left><left>"
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Usuário   </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>De        </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Até       </b></font></td>"
    ShowHTML "          <td><font size=""1""><b>Opereções </b></font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      'Lista os registros selecionados para listagem
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">"                 & RS("nm_usuario") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">"                 & FormataDataEdicao(RS("inicio"))     & "</td>"
        if RS("fim") <> "" then 
          ShowHTML "      <td align=""center""><font size=""1"">"                 & FormataDataEdicao(RS("fim"))        & " </td>"
        else 
          ShowHTML "      <td align=""CENTER""><font size=""1""> ---                    </td>" 
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chaveAux=" & RS("usuario") &"&w_inicio=" & FormataDataEdicao(RS("inicio")) & "&w_fim=" & FormataDataEdicao(RS("fim")) & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        If Nvl(RS("fim"),"") = "" then
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=F&w_chave=" & w_chave & "&w_chaveAux=" & RS("usuario") &"&w_inicio=" & FormataDataEdicao(RS("inicio")) & "&w_fim=" & FormataDataEdicao(RS("fim")) & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Finalizar</A>&nbsp"
        End If
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chaveAux=" & RS("usuario") &"&w_inicio=" & FormataDataEdicao(RS("inicio")) & "&w_fim=" & FormataDataEdicao(RS("fim")) & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </table></center>"
    ShowHTML "  </td>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
      MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    Else
      MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"
    'Aqui começa a manipulação de registros
  ElseIf Instr("AIFEV",O) > 0 Then
    If InStr("EVF",O) Then w_Disabled = " DISABLED " End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"RAMUSR",R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chaveAux2"" value=""" & w_chaveAux2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If O = "I" then
      SelecaoPessoa "Usua<u>r</u>io:", "R", null, null, w_chave, "w_chaveaux", "TTUSURAMAL"
      ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de início de uso deste ramal.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
    ElseIf O = "A" then
      DB_GetPersonData RS, w_cliente, w_chaveAux, null, null
      ShowHTML "      <td><font size=""1"">Usuário:<br><b>" & RS("nome")
      ShowHTML "<INPUT type=""hidden"" name=""w_chaveaux"" value=""" & w_chaveaux & """>"
      ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de início de uso deste ramal.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
      If w_fim <> "" then 
        ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de fim de uso deste ramal.','white')"";    ONMOUSEOUT=""kill()""><font size=""1""><b><u>F</u>im:   </b><br><input " & w_Disabled  & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
      End If
    ElseIf O = "F" then
      DB_GetPersonData RS, w_cliente, w_chaveAux, null, null
      ShowHTML "      <td><font size=""1"">Usuário:<br><b>" & RS("nome")
      ShowHTML "<INPUT type=""hidden"" name=""w_chaveaux"" value=""" & w_chaveaux & """>"
      ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de início de uso deste ramal.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
      ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de fim de uso deste ramal.','white')"";    ONMOUSEOUT=""kill()""><font size=""1""><b><u>F</u>im:   </b><br><input accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    Else
      DB_GetPersonData RS, w_cliente, w_chaveAux, null, null
      ShowHTML "      <td><font size=""1"">Usuário:<br><b>" & RS("nome")
      ShowHTML "<INPUT type=""hidden"" name=""w_chaveaux"" value=""" & w_chaveaux & """>"
      ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de início de uso deste ramal.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
      If Nvl(w_fim,"") <> "" then
        ShowHTML "      <td ONMOUSEOVER=""popup('Informe a data de fim de uso deste ramal.','white')"";    ONMOUSEOUT=""kill()""><font size=""1""><b><u>F</u>im:   </b><br><input " & w_Disabled  & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
      End If
    End If
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"    
    If O = "E" Then
      ShowHTML "      <td>"
      ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
      If O = "I" Then
        ShowHTML "      <td>"
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
      ElseIf O = "F" then
        ShowHTML "      <td>"
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Finalizar"">"
      Else
        ShowHTML "      <td>"
        ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
      End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chaveAux=" & w_chaveAux & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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
  'DesconectaBD
  
  Set w_inicio           = Nothing
  Set w_fim              = Nothing
  Set w_chaveAux2        = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e tabelas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de Prefíxos
REM -------------------------------------------------------------------------
Sub prefixo

  Dim w_prefixo, w_localidade, w_sigla, w_uf, w_ddd, w_controle, w_degrau
  Dim p_prefixo, p_uf
  
  w_Chave      = Request("w_Chave")
  w_troca      = Request("w_troca")
  w_prefixo    = Request("w_prefixo")
  w_localidade = Request("w_localidade")
  w_sigla      = Request("w_sigla")
  w_uf         = Request("w_uf")
  w_ddd        = Request("w_ddd")
  w_controle   = Request("w_controle")
  w_degrau     = Request("w_degrau")
  p_prefixo    = Request("p_prefixo")
  p_uf         = Request("p_uf")
  If w_troca > "" Then
  w_prefixo    = Request("w_prefixo")
  w_localidade = Request("w_localidade")
  w_sigla      = Request("w_sigla")
  w_uf         = Request("w_uf")
  w_ddd        = Request("w_ddd")
  w_controle   = Request("w_controle")
  w_degrau     = Request("w_degrau")
  ElseIf O = "L" Then
    DB_GetPrefixo RS, null, p_prefixo, p_uf
    RS.Sort = Request("p_ordena")
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetPrefixo RS, w_chave, null, null
    w_prefixo    = RS("prefixo")
    w_localidade = RS("localidade")
    w_sigla      = RS("sigla")
    w_uf         = RS("uf")
    w_ddd        = RS("ddd")
    w_controle   = RS("controle")
    w_degrau     = RS("degrau")
    DesconectaBD
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    If InStr("IA",O) > 0 Then
      Validate "w_prefixo"    , "Prefixo"               , "1" , "1" , "5" , "15" , "1" , "1"
      Validate "w_localidade" , "Localidade"            , "1" , "1" , "5" , "25" , "1" , "1"
      Validate "w_sigla"      , "sigla"                 , "1" , ""  , "4" , "4"  , "1" , "1"
      Validate "w_uf"         , "uf"                    , "1" , ""  , "2" , "2"  , "1" , "1"
      Validate "w_ddd"        , "ddd"                   , "1" , ""  , "3" , "4"  , "1" , "1"
      Validate "w_controle"   , "controle"              , "1" , ""  , "12" , "16" , "1" , "1"
      Validate "w_degrau"     , "degrau"                , "1" , ""  , "3" , "3"  , "1" , "1"
      Validate "w_assinatura" , "Assinatura Eletrônica" , "1" , "1" , "6" , "30" , "1" , "1"
    ElseIf O = "E" Then
      Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
      ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
      ShowHTML "     { return (true); }; "
      ShowHTML "     { return (false); }; "
    ElseIf O="P" Then
      Validate "p_prefixo" , "Prefixo" , "1" , "" , "1" , "15" , "1" , "1"
      Validate "p_uf"      , "UF"      , "1" , "" , "2" , "2" , "1" , "1"
    End If
    ShowHTML "  theForm.Botao[0].disabled=true;"
    ShowHTML "  theForm.Botao[1].disabled=true;"
    'if O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
    ValidateClose
    ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
    BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "E" Then
    BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf O = "P" Then
    BodyOpen "onLoad='document.Form.p_prefixo.focus()';"
  Else
    BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    If p_prefixo > "" or p_uf > "" Then
      ShowHTML " <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
      ShowHTML " <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If P2 > 0 Then
      ShowHTML "          <td><font size=""1""><b>Prefixo    </font></td>"
      ShowHTML "          <td><font size=""1""><b>Localidade </font></td>"
    Else
      ShowHTML "          <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=prefixo, localidade&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Prefixo    </a></font></td>"
      ShowHTML "          <td><font size=""1""><b><a class=""SS"" href=""" & w_dir & w_pagina & par & "&p_ordena=localidade, prefixo&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Localidade </a></font></td>"
    End If
    ShowHTML "          <td><font size=""1""><b> Sigla      </font></td>"    
    ShowHTML "          <td><font size=""1""><b> UF         </font></td>"
    ShowHTML "          <td><font size=""1""><b> DDD        </font></td>"
    ShowHTML "          <td><font size=""1""><b> Controle   </font></td>"
    ShowHTML "          <td><font size=""1""><b> Degrau     </font></td>"
    ShowHTML "          <td><font size=""1""><b> Operações  </font></td>"
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
        ShowHTML "        <td><font size=""1"">" & RS("prefixo")    & " </td>"
        ShowHTML "        <td><font size=""1"">" & RS("localidade") & " </td>"
        if RS("sigla") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("sigla")      & " </td>" 
        else 
          ShowHTML "      <td><font size=""1""> ---                     </td>" 
        End If
        if RS("uf") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("uf")         & " </td>" 
        else 
          ShowHTML "      <td><font size=""1""> ---                     </td>" 
        End If
        if RS("ddd") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("ddd")        & " </td>" 
        else 
          ShowHTML "      <td><font size=""1""> ---                     </td>" 
        End If
        if RS("controle") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("controle")   & " </td>" 
        else 
          ShowHTML "      <td><font size=""1""> ---                     </td>" 
        End If
        if RS("degrau") <> "" then 
          ShowHTML "      <td><font size=""1"">" & RS("degrau")     & " </td>" 
        else 
          ShowHTML "      <td><font size=""1""> ---                     </td>" 
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R= " & W_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    If InStr("EV",O) Then
      w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
    'ShowHTML "<INPUT type=""hidden"" name=""w_chave""   value=""" & w_chave   & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca""   value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0""><tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>refixo:    </b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_prefixo""    class=""sti"" SIZE=""20"" MAXLENGTH=""15"" VALUE=""" & w_prefixo    & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>L</u>ocalidade: </b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_localidade"" class=""sti"" SIZE=""30"" MAXLENGTH=""25"" VALUE=""" & w_localidade & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>S</u>igla:      </b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla""      class=""sti"" SIZE=""8""  MAXLENGTH=""4""  VALUE=""" & w_sigla      & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>U</u>F:         </b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""w_uf""         class=""sti"" SIZE=""6""  MAXLENGTH=""2""  VALUE=""" & w_uf         & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>DD:        </b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd""        class=""sti"" SIZE=""8""  MAXLENGTH=""4""  VALUE=""" & w_ddd        & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>C</u>ontrole:   </b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_controle""   class=""sti"" SIZE=""20"" MAXLENGTH=""16"" VALUE=""" & w_controle   & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>D<u>e</u>grau:     </b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_degrau""     class=""sti"" SIZE=""6""  MAXLENGTH=""3""  VALUE=""" & w_degrau      & """></td>"
    ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
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
  ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
    AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>refixo: </b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""p_prefixo"" class=""sti"" SIZE=""20"" MAXLENGTH=""15"" VALUE=""" & p_prefixo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>U</u>F:      </b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""p_uf""      class=""sti"" SIZE=""6""  MAXLENGTH=""2""  VALUE=""" & p_uf      & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  
  Set w_prefixo    = Nothing
  Set w_localidade = Nothing 
  Set w_sigla      = Nothing 
  Set w_uf         = Nothing
  Set w_ddd        = Nothing
  Set w_controle   = Nothing
  Set w_degrau     = Nothing
  Set w_chave      = Nothing
  Set w_cliente    = Nothing
  Set w_troca      = Nothing
  Set p_prefixo    = Nothing
  Set p_uf         = Nothing
  
End Sub
REM =========================================================================
REM Fim da rotina de arquivos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  
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
    Case "TTCENTRAL"
      ' Verifica se a Assinatura Eletrônica é válida
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTCentral O, Request("w_chave"), w_cliente,_
        Request("w_sq_pessoa_endereco"), Request("w_arquivo_bilhetes"), Request("w_recupera_bilhetes")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
      Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
      End If         
    Case "TTTRONCO"
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTTronco O, Request("w_chaveAux"), Request("w_cliente"),_
        Request("w_chave"), Request("w_sq_pessoa_telefone"), Request("w_codigo"), Request("w_ativo")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
        ScriptClose
      Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
      End If         
    Case "TTRAMAL"
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTRamal O, Request("w_chave"), Request("w_sq_central_fone"), Request("w_codigo")
          
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
      Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
      End If             
    Case "TTPREFIXO"
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTPrefixo O, Request("w_chave"), Request("w_prefixo"),_
        Request("w_localidade"), Request("w_sigla"), Request("w_uf"), Request("w_ddd"), Request("w_controle"),_
        Request("w_degrau")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
      Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
      End If           
    Case "TTUSUCTRL"
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTUsuarioCentral O, Request("w_chaveAux"), w_cliente,_
        Request("w_usuario"), Request("w_sq_central_fone"), Request("w_codigo")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
        ScriptClose          
      Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
      End If                   
    Case "RAMUSR"
      
      If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutTTRamalUsuario O, Request("w_chave"),_
        Request("w_chaveAux"), Request("w_chaveAux2"), Request("w_inicio"), Request("w_fim")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
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

  Set w_chave_nova = Nothing
  Set FS           = Nothing
  Set w_Mensagem   = Nothing
  Set p_modulo     = Nothing
  Set w_Null       = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================


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
    Case "CENTRAL"        CentralTel
    Case "TRONCOS"        Troncos
    Case "RAMAL"          Ramais
    Case "USUARIOCENTRAL" UsuarioCentral
    Case "RAMALUSR"       RamalUsr
    Case "PREFIXO"        Prefixo
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