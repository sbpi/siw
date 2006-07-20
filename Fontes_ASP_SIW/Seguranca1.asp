<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Link.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_Seguranca.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /Seguranca.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Complementa Seguranca.asp
REM Mail     : alex@sbpi.com.br
REM Criacao  : 03/12/2002 17:27
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
Dim dbms, sp, RS, RS1, w_ano
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_ContAux, w_Texto, w_Null
Dim w_Assinatura, w_Heranca, w_cliente, w_filter
Dim p_gestor, p_lotacao, p_localizacao, p_nome, p_ordena, p_pessoa
Dim w_dir_volta, w_submenu
Private Par

AbreSessao

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
w_Pagina     = "seguranca1.asp?par="
w_Disabled   = "ENABLED"
p_localizacao= uCase(Request("p_localizacao"))
p_lotacao    = uCase(Request("p_lotacao"))
p_pessoa     = uCase(Request("p_pessoa"))
p_nome       = uCase(Request("p_nome"))
p_gestor     = uCase(Request("p_gestor"))
p_ordena     = uCase(Request("p_ordena"))

If O = "" and SG = "CIDADE" Then O = "P" End If
If O = "" and SG <> "CIDADE" Then O = "L" End If

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
     w_TP = TP & " - Acessos"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente = RetornaCliente()
  
Main

FechaSessao

Set w_submenu     = Nothing
Set w_filter      = Nothing
Set w_cliente     = Nothing
Set w_ContAux     = Nothing
Set w_Heranca     = Nothing
Set w_Texto       = Nothing
Set w_Null        = Nothing
Set p_localizacao = Nothing
Set p_lotacao     = Nothing
Set p_gestor      = Nothing
Set p_pessoa      = Nothing
Set p_nome        = Nothing
Set p_ordena      = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Cont        = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing

REM =========================================================================
REM Trata os acessos a trâmites do serviço
REM -------------------------------------------------------------------------
Sub AcessoTramite
 
  Dim w_acesso_geral
  Dim w_sq_menu
  Dim w_sq_siw_tramite
  Dim w_sq_pessoa
  Dim w_sq_unidade
  Dim w_sq_unidade_endereco
  Dim p_sq_menu
  Dim p_sq_unidade
  Dim RS1
   
  w_sq_menu           = Request("w_sq_menu")
  w_sq_siw_tramite    = Request("w_sq_siw_tramite")
  w_sq_pessoa         = Request("w_sq_pessoa")
  p_nome              = Request("p_nome")
  p_sq_menu           = Request("p_sq_menu")
  p_sq_unidade        = Request("p_sq_unidade")
  
  If O = "" Then O="L" end if
  
  ' Monta uma string para indicar a opção selecionada
  w_texto = OpcaoMenu(w_sq_menu)

  ' Complementa a string com o nome do trâmite
  DB_GetTramiteData RS1, w_sq_siw_tramite
  w_texto = w_texto & "<font color=""#FF0000"">" & RS1("nome") & "</font>"
  DesconectaBD

  If InStr("L",O) Then
     DB_GetTramiteUser RS, w_cliente, w_sq_menu, w_sq_siw_tramite, "USUARIO", null, null, null
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>" & conSgSistema & " - Acessos</TITLE>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     If O = "I" Then
        If (p_nome & p_sq_unidade & p_sq_menu) > "" Then
           ShowHTML "  function MarcaTodos() {"
           ShowHTML "    if (document.Form1.w_sq_pessoa.value==undefined) "
           ShowHTML "       for (i=0; i < document.Form1.w_sq_pessoa.length; i++) "
           ShowHTML "         document.Form1.w_sq_pessoa[i].checked=true;"
           ShowHTML "    else document.Form1.w_sq_pessoa.checked=true;"
           ShowHTML "  }"
           ShowHTML "  function DesmarcaTodos() {"
           ShowHTML "    if (document.Form1.w_sq_pessoa.value==undefined) "
           ShowHTML "       for (i=0; i < document.Form1.w_sq_pessoa.length; i++) "
           ShowHTML "         document.Form1.w_sq_pessoa[i].checked=false;"
           ShowHTML "    "
           ShowHTML "    else document.Form1.w_sq_pessoa.checked=false;"
           ShowHTML "  }"
        End If
     End If
     ValidateOpen "Validacao"
     If O = "I" Then
        Validate "p_nome", "Nome", "1", "", "4", "40", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     If (p_nome & p_sq_unidade & p_sq_menu) > "" Then
        ValidateOpen "Validacao1"
        If InStr("I",O) > 0 Then
           ShowHTML "  var i; "
           ShowHTML "  var w_erro=true; "
           ShowHTML "  if (theForm.w_sq_pessoa.value==undefined) {"
           ShowHTML "     for (i=0; i < theForm.w_sq_pessoa.length; i++) {"
           ShowHTML "       if (theForm.w_sq_pessoa[i].checked) w_erro=false;"
           ShowHTML "     }"
           ShowHTML "  }"
           ShowHTML "  else {"
           ShowHTML "     if (theForm.w_sq_pessoa.checked) w_erro=false;"
           ShowHTML "  }"
           ShowHTML "  if (w_erro) {"
           ShowHTML "    alert('Você deve informar pelo menos um usuário!'); "
           ShowHTML "    return false;"
           ShowHTML "  }"
        End If
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
        ValidateClose
     End If
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .lh:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Texto_Abre

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Opção:<br><b><font size=1 class=""hl"">" & Mid(w_texto,1,Len(w_texto)-4) & "</font></b></td>"
  If w_sq_pessoa > "" Then
     ' Recupera o nome do usuário selecionado
     DB_GetPersonData RS1, w_cliente, w_sq_pessoa, null, null
     ShowHTML "          <td align=""right""><font size=""1"">Usuário:<br><b>" & RS1("NOME") & " (" & uCase(RS1("USERNAME")) & ")</font></td>"
  End If
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  If O = "L" Then
    ShowHTML "<tr><td><font size=2>&nbsp;</font></td></tr>"
    ShowHTML "<tr><td><font size=""2"">"    
    ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & "&w_sq_siw_tramite=" & w_sq_siw_tramite & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""2""><a accesskey=""F"" class=""ss"" href=""#"" onClick=""window.close(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=2>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Username</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"    
    ShowHTML "        </tr>"
    w_contaux = ""
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ' Se for quebra de endereço, exibe uma linha com o endereço
        If w_contaux <> RS("logradouro") Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "        <td valign=""top"" colspan=3><b><font size=""1"">" & RS("logradouro") & "</td>"
           w_contaux = RS("logradouro")
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td valign=""top"" align=""center""><font size=""1"">" & RS("username") & "</td>"
        ShowHTML "        <td valign=""top""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_sq_siw_tramite=" & w_sq_siw_tramite & "&w_sq_pessoa_endereco=" & RS("sq_pessoa_endereco") & """ onClick=""return(confirm('Confirma exclusão do acesso deste usuário para esta opção?'));"">Excluir</A>&nbsp"
        ShowHTML "&nbsp"
        ShowHTML "        </td>"          
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBd
  ElseIf Instr("I",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    AbreForm "Form", R, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_siw_tramite"" value=""" & w_sq_siw_tramite & """>"

    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe os parâmetros desejados para recuperar a lista de usuários.<li>Quando a relação de nomes for exibida, selecione os usuários desejados clicando sobre a caixa ao lado do nome.<li>Você pode informar o nome de uma pessoa (ou apenas o início do nome), selecionar as pessoas de uma unidade, ou ainda as pessoas com acesso a uma outra opção.<li>Após informar os parâmetros desejados, clique sobre o botão <i>Aplicar filtro</i>.</ul><hr><b>Filtro</b></div>"
    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font  size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """>"
    SelecaoMenu "<u>O</u>pção:", "O", null, p_sq_menu, w_sq_menu, "p_sq_menu", "Pesquisa", null
    ShowHTML "      <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr>"
    SelecaoUnidade "<U>U</U>nidade:", "U", null, p_sq_unidade, null, "p_sq_unidade", null, null
    ShowHTML "      </table></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Cancelar"" onClick=""document.Form.O.value='L';"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</form>"
    If (p_nome & p_sq_menu & p_sq_unidade) > "" Then
       
       DB_GetTramiteUser RS, w_cliente, w_sq_menu, Nvl(p_sq_menu,w_sq_siw_tramite), "PESQUISA", p_nome, p_sq_unidade, Nvl(p_sq_menu,"")

       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=2><font size=2><hr>"
       AbreForm "Form1", w_Pagina & "Grava", "POST", "return(Validacao1(this));", null,P1,P2,P3,P4,TP,SG,R,O
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_siw_tramite"" value=""" & w_sq_siw_tramite & """>"
       ShowHTML "  <tr><td valign=""top""><font size=2><b>Usuários que ainda não têm acesso a esta opção</b>"
       ShowHTML "      <td nowrap valign=""bottom"" align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
       ShowHTML "  <tr><td align=""center"" colspan=2>"
       ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       If RS.EOF Then
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
         ShowHTML "            <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
         ShowHTML "                                      <U STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
         ShowHTML "            <td><font size=""2""><b>Nome</font></td>"
         ShowHTML "          </tr>"
         While Not RS.EOF
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "          <td align=""center""><input type=""checkbox"" name=""w_sq_pessoa"" value=""" & RS("sq_pessoa") & """>"
           ShowHTML "          <td><font size=""1"">" & RS("nome") & "</td>"
           ShowHTML "        </tr>"
           RS.MoveNext
         wend
         ShowHTML "      </center>"
         ShowHTML "    </table>"
         ShowHTML "    </td>"
         ShowHTML "  </tr>"
         ShowHTML "  <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
         ShowHTML "  <tr><td align=""center"" colspan=""2"">"
         ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
         ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & "&w_sq_siw_tramite=" & w_sq_siw_tramite & "&O=L';"" name=""Botao"" value=""Cancelar"">"
         ShowHTML "      </td>"
         ShowHTML "  </tr>"
         ShowHTML "</FORM>"
       End If
       DesconectaBd	 
    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set p_sq_menu                = Nothing
  Set p_sq_unidade             = Nothing
  Set w_sq_menu                = Nothing
  Set w_sq_siw_tramite         = Nothing
  Set w_sq_pessoa              = Nothing
  Set w_sq_unidade             = Nothing
  Set w_acesso_geral           = Nothing
  Set RS1                      = Nothing
        
End Sub
REM =========================================================================
REM Fim da rotina de acessos do menu
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento de trâmites
REM -------------------------------------------------------------------------
Sub Tramite

  Dim w_sq_siw_tramite, w_sq_menu, w_nome, w_ordem
  Dim w_sigla, w_ativo, w_descricao, w_chefia_imediata, w_acesso_geral
  Dim w_envia_mail, w_solicita_cc
  
  Dim w_texto

  w_sq_menu = Request("w_sq_menu")
  
  ' Monta uma string para indicar a opção selecionada
  w_texto = OpcaoMenu(w_sq_menu)
       
  If O = "L" Then
     DB_GetTramiteList RS, w_sq_menu, null, null
     RS.Sort = "Ordem"
  ElseIf O = "A" or O = "E" Then
     w_sq_siw_tramite = Request("w_sq_siw_tramite")
     DB_GetTramiteData RS, w_sq_siw_tramite
     w_nome             = RS("nome")
     w_envia_mail       = RS("envia_mail")
     w_solicita_cc      = RS("solicita_cc")
     w_ordem            = RS("ordem")
     w_sigla            = RS("sigla")
     w_ativo            = RS("ativo")
     w_descricao        = RS("descricao")
     w_chefia_imediata  = RS("chefia_imediata")
     If cDbl(RS("primeiro"))  = cDbl(w_sq_siw_tramite) and RS("acesso_geral") = "S" Then
        w_acesso_geral  = "S"
     Else
        w_acesso_geral  = "N"
     End If
     DesconectaBd
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Configuração dos trâmites</TITLE>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "50", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "2", "", "0123456789"
        Validate "w_sigla", "Sigla", "1", "", "2", "2", "1", "1"
        Validate "w_descricao", "Descrição", "1", "", "5", "500", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "  theForm.Botao[2].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .lh:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     ElseIf O = "A" or O = "I" Then
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
     End If
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Texto_Abre

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Opção:<br><b><font size=1 class=""hl"">" & Mid(w_texto,1,Len(w_texto)-4) & "</font></b></td>"
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "  <tr><td>&nbsp;"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_menu=" & w_sq_menu & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "        <a accesskey=""F"" class=""ss"" href=""#"" onClick=""window.close(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Ordem</font></td>"    
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"    
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ' Se a situação tiver uma descrição informada, monta o comando para exibi-lo quando o mouse passa por cima.
        If RS("descricao") > "" Then
           w_texto = "title=""" & Replace(RS("descricao"),CHR(13)&CHR(10),"<BR>") & """"
        Else
           w_texto = ""
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ " & w_texto & ">"
        ShowHTML "        <td align=""center""><font  size=""1"">" & RS("ordem") & "</td>"        
        ShowHTML "        <td align=""left""><font  size=""1"">" & RS("nome") & "</td>"        
        ShowHTML "        <td align=""center""><font  size=""1"">" & RS("sigla") & "</td>"
        ShowHTML "        <td align=""center""><font  size=""1"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_menu=" & w_sq_menu & "&w_sq_siw_tramite=" & RS("sq_siw_tramite") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_menu=" & w_sq_menu & "&w_sq_siw_tramite=" & RS("sq_siw_tramite") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ' Permite a configuração dos acessos apenas para trâmites ativos
        If RS("ativo") = "S" Then
           ShowHTML "          <A class=""hl"" HREF=""#" & RS("sq_siw_tramite") & """ onClick=""window.open('" & w_pagina & "AcessoTramite&R=" & w_Pagina & par & "&O=L&w_sq_menu=" & w_sq_menu & "&w_sq_siw_tramite=" & RS("sq_siw_tramite") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Acessos" & "&SG=ACESSOTRAMITE','AcessoMenu','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Configura as permissões de acesso."">Acessos</A>&nbsp" 
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
    DesConectaBD
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_siw_tramite"" value=""" & w_sq_siw_tramite &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"    
    ShowHTML "      <tr><td valign=""top"" colspan=3><font  size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""50"" value=""" & w_nome & """></td></tr>"
    ShowHTML "      <tr><td valign=""top"" width=""33%""><font  size=""1""><b><U>O</U>rdem:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_ordem"" size=""2"" maxlength=""2"" value=""" & w_ordem & """></td>"
    ShowHTML "          <td valign=""top"" width=""33%""><font  size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_sigla"" size=""2"" maxlength=""2"" value=""" & w_sigla & """></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=3><font  size=""1""><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_descricao"" ROWS=5 COLS=80>" & w_descricao & "</TEXTAREA></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=3><font  size=""1""><b>Quem cumprirá este trâmite?</b><br>"
    If w_acesso_geral = "S" Then
       ShowHTML "          <font color=""#FF0000""><b>Este serviço é de acesso geral. Neste caso, o primeiro trâmite (cadastramento), sempre será gerenciado pela segurança do sistema.</b></font>"
       ShowHTML "          <input type=""hidden"" name=""w_chefia_imediata"" value=""N"">"
    Else
       If w_chefia_imediata = "N" or w_chefia_imediata  =  "" Then
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""S""> Titular/substituto da unidade solicitante<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""U""> Titular/substituto da unidade executora e usuários que tenham permissão<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""N"" checked> Apenas os usuários que tenham permissão"
       ElseIf w_chefia_imediata = "S" Then
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""S"" checked> Titular/substituto da unidade solicitante<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""U""> Titular/substituto da unidade executora e usuários que tenham permissão<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""N""> Apenas os usuários que tenham permissão"
       Else
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""S""> Titular/substituto da unidade solicitante<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""U"" checked> Titular/substituto da unidade executora e usuários que tenham permissão<br>"
          ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_chefia_imediata"" value=""N""> Apenas os usuários que tenham permissão"
       End If
    End If
    ShowHTML "      <tr><td valign=""top""><font  size=""1""><b>Envia e-mail ao responsável?</b><br>"
    If w_envia_mail = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_envia_mail"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_envia_mail"" value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_envia_mail"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_envia_mail"" value=""N"" checked> Não"
    End If
    ShowHTML "          <td valign=""top""><font  size=""1""><b>Solicita projeto?</b><br>"
    If w_solicita_cc = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_solicita_cc"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_solicita_cc"" value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_solicita_cc"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_solicita_cc"" value=""N"" checked> Não"
    End If
    ShowHTML "      <tr><td valign=""top"" colspan=3><font  size=""1""><b>Ativo?</b><br>"
    If w_ativo = "S" or w_ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_ativo"" value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_ativo"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_ativo"" value=""N"" checked> Não"
    End If
    ShowHTML "      <tr><td valign=""top"" colspan=3><font  size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""history.back()"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""window.close(); opener.focus();"" name=""Botao"" value=""Fechar"">"
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
  Estrutura_Texto_Fecha
  
  Set w_envia_mail          = Nothing 
  Set w_solicita_cc         = Nothing 
  Set w_sq_siw_tramite      = Nothing 
  Set w_sq_menu             = Nothing 
  Set w_nome                = Nothing 
  Set w_ordem               = Nothing 
  Set w_sigla               = Nothing 
  Set w_ativo               = Nothing 
  Set w_descricao           = Nothing
  Set w_chefia_imediata     = Nothing
  Set w_texto               = Nothing 

End Sub
REM =========================================================================
REM Fim da tela de cadastramento de trâmites
REM -------------------------------------------------------------------------

REM =========================================================================
REM Trata os acessos do menu
REM -------------------------------------------------------------------------
Sub AcessoMenu
 
  Dim w_acesso_geral
  Dim w_sq_menu
  Dim w_sq_pessoa
  Dim w_sq_unidade
  Dim w_sq_unidade_endereco
  Dim p_sq_menu
  Dim p_sq_unidade
  Dim RS1
   
  w_sq_menu           = Request("w_sq_menu")
  w_sq_pessoa         = Request("w_sq_pessoa")
  p_nome              = Request("p_nome")
  p_sq_menu           = Request("p_sq_menu")
  p_sq_unidade        = Request("p_sq_unidade")
  
  If O = "" Then O="L" end if
  
  ' Monta uma string para indicar a opção selecionada
  w_texto = OpcaoMenu(w_sq_menu)

  If InStr("L",O) Then
     DB_GetMenuUser RS, w_cliente, w_sq_menu, null, "USUARIO", null, null, null
     DB_GetMenuUser RS1, w_cliente, w_sq_menu, null, "VINCULO", null, null, null
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>" & conSgSistema & " - Acessos</TITLE>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     If O = "I" Then
        If (p_nome & p_sq_unidade & p_sq_menu) > "" Then
           ShowHTML "  function MarcaTodos() {"
           ShowHTML "    if (document.Form1.w_sq_pessoa.value==undefined) "
           ShowHTML "       for (i=0; i < document.Form1.w_sq_pessoa.length; i++) "
           ShowHTML "         document.Form1.w_sq_pessoa[i].checked=true;"
           ShowHTML "    else document.Form1.w_sq_pessoa.checked=true;"
           ShowHTML "  }"
           ShowHTML "  function DesmarcaTodos() {"
           ShowHTML "    if (document.Form1.w_sq_pessoa.value==undefined) "
           ShowHTML "       for (i=0; i < document.Form1.w_sq_pessoa.length; i++) "
           ShowHTML "         document.Form1.w_sq_pessoa[i].checked=false;"
           ShowHTML "    "
           ShowHTML "    else document.Form1.w_sq_pessoa.checked=false;"
           ShowHTML "  }"
        End If
     End If
     ValidateOpen "Validacao"
     If O = "I" Then
        Validate "p_nome", "Nome", "1", "", "4", "40", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     If (p_nome & p_sq_unidade & p_sq_menu) > "" Then
        ValidateOpen "Validacao1"
        If InStr("I",O) > 0 Then
           ShowHTML "  var i; "
           ShowHTML "  var w_erro=true; "
           ShowHTML "  if (theForm.w_sq_pessoa.value==undefined) {"
           ShowHTML "     for (i=0; i < theForm.w_sq_pessoa.length; i++) {"
           ShowHTML "       if (theForm.w_sq_pessoa[i].checked) w_erro=false;"
           ShowHTML "     }"
           ShowHTML "  }"
           ShowHTML "  else {"
           ShowHTML "     if (theForm.w_sq_pessoa.checked) w_erro=false;"
           ShowHTML "  }"
           ShowHTML "  if (w_erro) {"
           ShowHTML "    alert('Você deve informar pelo menos um usuário!'); "
           ShowHTML "    return false;"
           ShowHTML "  }"
        End If
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
        ValidateClose
     End If
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .lh:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If O = "I" and p_nome = "" Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If

  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Opção:<br><b><font size=1 class=""hl"">" & Mid(w_texto,1,Len(w_texto)-4) & "</font></b></td>"
  If w_sq_pessoa > "" Then
     ' Recupera o nome do usuário selecionado
     DB_GetPersonData RS1, w_cliente, w_sq_pessoa, null, null
     ShowHTML "          <td align=""right""><font size=""1"">Usuário:<br><b>" & RS1("NOME") & " (" & uCase(RS1("USERNAME")) & ")</font></td>"
  End If
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  If O = "L" Then
    ShowHTML "<tr><td><font size=2>&nbsp;</font></td></tr>"
    ShowHTML "<tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Acessos a tipos de vínculo</td>"
    ShowHTML "<tr><td><font size=""2"">"    
    ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & "AcessoMenuPerfil&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOMENUPERFIL&w_sq_menu=" & w_sq_menu & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""2""><a accesskey=""F"" class=""ss"" href=""#"" onClick=""window.close(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS1.RecordCount
    ShowHTML "<tr><td colspan=2>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Tipo de vínculo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"    
    ShowHTML "        </tr>"
    w_contaux = ""
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS1.EOF
        ' Se for quebra de endereço, exibe uma linha com o endereço
        If w_contaux <> RS1("logradouro") Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "        <td valign=""top"" colspan=3><b><font size=""1"">" & RS1("nm_cidade") & " - " & RS1("logradouro") & "</td>"
           w_contaux = RS1("logradouro")
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td valign=""top""><font size=""1"">" & RS1("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOMENUPERFIL&w_sq_menu=" & w_sq_menu & "&w_sq_tipo_vinculo=" & RS1("sq_tipo_vinculo") & "&w_sq_pessoa_endereco=" & RS1("sq_pessoa_endereco") & """ onClick=""return(confirm('Confirma exclusão do acesso deste usuário para esta opção?'));"">Excluir</A>&nbsp"
        ShowHTML "&nbsp"
        ShowHTML "        </td>"          
        ShowHTML "      </tr>"
        RS1.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    RS1.Close

    ShowHTML "<tr><td><font size=2>&nbsp;</font></td></tr>"
    ShowHTML "<tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Acessos a usuários</td>"
    ShowHTML "<tr><td><font size=""2"">"    
    ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""2""><a accesskey=""F"" class=""ss"" href=""#"" onClick=""window.close(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=2>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Username</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"    
    ShowHTML "        </tr>"
    w_contaux = ""
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ' Se for quebra de endereço, exibe uma linha com o endereço
        If w_contaux <> RS("logradouro") Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "        <td valign=""top"" colspan=3><b><font size=""1"">" & RS("nm_cidade") & " - " & RS("logradouro") & "</td>"
           w_contaux = RS("logradouro")
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td valign=""top"" align=""center""><font size=""1"">" & RS("username") & "</td>"
        ShowHTML "        <td valign=""top""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_username=" & RS("username") & "&w_sq_pessoa_endereco=" & RS("sq_pessoa_endereco") & """ onClick=""return(confirm('Confirma exclusão do acesso deste usuário para esta opção?'));"">Excluir</A>&nbsp"
        ShowHTML "&nbsp"
        ShowHTML "        </td>"          
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBd
  ElseIf Instr("I",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    AbreForm "Form", R, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"

    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe os parâmetros desejados para recuperar a lista de usuários.<li>Quando a relação de nomes for exibida, selecione os usuários desejados clicando sobre a caixa ao lado do nome.<li>Você pode informar o nome de uma pessoa (ou apenas o início do nome), selecionar as pessoas de uma unidade, ou ainda as pessoas com acesso a uma outra opção.<li>Após informar os parâmetros desejados, clique sobre o botão <i>Aplicar filtro</i>.</ul><hr><b>Filtro</b></div>"
    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font  size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """>"
    SelecaoMenu "<u>O</u>pção:", "O", null, p_sq_menu, w_sq_menu, "p_sq_menu", "Pesquisa", null
    ShowHTML "      <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr>"
    SelecaoUnidade "<U>U</U>nidade:", "U", null, p_sq_unidade, null, "p_sq_unidade", null, null
    ShowHTML "      </table></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Cancelar"" onClick=""document.Form.O.value='L';"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</form>"
    If (p_nome & p_sq_menu & p_sq_unidade) > "" Then

       DB_GetMenuUser RS, w_cliente, w_sq_menu, p_sq_menu, "PESQUISA", p_nome, p_sq_unidade, p_sq_menu

       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=2><font size=2><hr>"
       AbreForm "Form1", w_Pagina & "Grava", "POST", "return(Validacao1(this));", null,P1,P2,P3,P4,TP,SG,R,O
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
       ShowHTML "  <tr><td valign=""top""><font size=2><b>Usuários que ainda não têm acesso a esta opção</b>"
       ShowHTML "      <td nowrap valign=""bottom"" align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
       ShowHTML "  <tr><td align=""center"" colspan=2>"
       ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       If RS.EOF Then
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
         ShowHTML "            <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
         ShowHTML "                                      <U STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
         ShowHTML "            <td><font size=""2""><b>Nome</font></td>"
         ShowHTML "          </tr>"
         While Not RS.EOF
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "          <td align=""center""><input type=""checkbox"" name=""w_sq_pessoa"" value=""" & RS("sq_pessoa") & """>"
           ShowHTML "          <td><font size=""1"">" & RS("nome") & "</td>"
           ShowHTML "        </tr>"
           RS.MoveNext
         wend
         ShowHTML "      </center>"
         ShowHTML "    </table>"
         ShowHTML "    </td>"
         ShowHTML "  </tr>"
         ShowHTML "  <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
         ShowHTML "  <tr><td align=""center"" colspan=""2"">"
         ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
         ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & w_sq_menu & "&O=L';"" name=""Botao"" value=""Cancelar"">"
         ShowHTML "      </td>"
         ShowHTML "  </tr>"
         ShowHTML "</FORM>"
       End If
       DesconectaBd	 
    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set p_nome                   = Nothing
  Set p_sq_menu                = Nothing
  Set p_sq_unidade             = Nothing
  Set w_sq_menu                = Nothing
  Set w_sq_pessoa              = Nothing
  Set w_sq_unidade             = Nothing
  Set w_acesso_geral           = Nothing
  Set RS1                      = Nothing
        
End Sub
REM =========================================================================
REM Fim da rotina de acessos do menu
REM -------------------------------------------------------------------------

REM =========================================================================
REM Trata os acessos do menu a tipos de vínculo
REM -------------------------------------------------------------------------
Sub AcessoMenuPerfil
 
  Dim w_acesso_geral
  Dim w_sq_menu
  Dim w_sq_pessoa
  Dim w_sq_unidade
  Dim w_sq_unidade_endereco
  Dim p_nome
  Dim p_sq_menu
  Dim p_sq_unidade
  Dim RS1
   
  w_sq_menu           = Request("w_sq_menu")
  w_sq_pessoa         = Request("w_sq_pessoa")
  p_nome              = Request("p_nome")
  p_sq_menu           = Request("p_sq_menu")
  p_sq_unidade        = Request("p_sq_unidade")
  
  ' Monta uma string para indicar a opção selecionada
  w_texto = OpcaoMenu(w_sq_menu)
       
  Cabecalho
  Estrutura_CSS w_cliente
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Acessos</TITLE>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If O = "I" Then
        ShowHTML "  var i; "
        ShowHTML "  var w_erro=true; "
        ShowHTML "  if (theForm.w_sq_tipo_vinculo.value==undefined) {"
        ShowHTML "     for (i=0; i < theForm.w_sq_tipo_vinculo.length; i++) {"
        ShowHTML "       if (theForm.w_sq_tipo_vinculo[i].checked) w_erro=false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_sq_tipo_vinculo.checked) w_erro=false;"
        ShowHTML "  }"
        ShowHTML "  if (w_erro) {"
        ShowHTML "    alert('Você deve informar pelo menos um tipo de vínculo!'); "
        ShowHTML "    return false;"
        ShowHTML "  }"
        ShowHTML "  var w_erro=true; "
        ShowHTML "  if (theForm.w_sq_pessoa_endereco.value==undefined) {"
        ShowHTML "     for (i=0; i < theForm.w_sq_pessoa_endereco.length; i++) {"
        ShowHTML "       if (theForm.w_sq_pessoa_endereco[i].checked) w_erro=false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_sq_pessoa_endereco.checked) w_erro=false;"
        ShowHTML "  }"
        ShowHTML "  if (w_erro) {"
        ShowHTML "    alert('Você deve informar pelo menos um endereço!'); "
        ShowHTML "    return false;"
        ShowHTML "  }"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .lh:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  Estrutura_Texto_Abre

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Opção:<br><b><font size=1 class=""hl"">" & Mid(w_texto,1,Len(w_texto)-4) & "</font></b></td>"
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  If Instr("I",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"

    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Marque os típos de vínculo e os endereços desejados, informe sua assinatura eletrônica e clique no botão <i>Gravar</i>.</ul><hr></div>"
    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2>"
    ShowHTML "    <table width=""100%"" border=""0"">"

    DB_GetVincKindList RS, w_cliente, "S", "Física", null, null
    ShowHTML "      <tr valign=""top""><td><font  size=""1""><b>Tipos de vínculo</b>:"
    While NOT RS.EOF
       ShowHTML "          <br><INPUT TYPE=""CHECKBOX"" CLASS=""STC"" NAME=""w_sq_tipo_vinculo"" VALUE=""" & RS("sq_tipo_vinculo") & """>" & RS("nome")
       RS.MoveNext
    Wend
    DesconectaBD

    DB_GetaddressMenu RS, w_cliente, w_sq_menu, null
    ShowHTML "          <td><font  size=""1""><b>Endereços</b>:"
    While NOT RS.EOF
       ShowHTML "          <br><INPUT TYPE=""CHECKBOX"" CLASS=""STC"" NAME=""w_sq_pessoa_endereco"" VALUE=""" & RS("sq_pessoa_endereco") & """>" & RS("endereco")
       RS.MoveNext
    Wend
    DesconectaBD

    ShowHTML "      <tr><td colspan=2><font  size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    ShowHTML "            <input class=""stb"" type=""button"" name=""Botao"" value=""Cancelar"" onClick=""history.back(1)"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</form>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set p_nome                   = Nothing
  Set p_sq_menu                = Nothing
  Set p_sq_unidade             = Nothing
  Set w_sq_menu                = Nothing
  Set w_sq_pessoa              = Nothing
  Set w_sq_unidade             = Nothing
  Set w_acesso_geral           = Nothing
  Set RS1                      = Nothing
        
End Sub
REM =========================================================================
REM Fim da rotina de acessos do menu para tipos de vínculo
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de controle dos endereços de uma opção
REM -------------------------------------------------------------------------
Sub Endereco

  Dim w_troca
  Dim w_texto
  Dim w_cont, w_contaux
  Dim w_sq_menu
  Dim RS1
  
  
  w_troca     = Request("w_troca")
  w_sq_menu = Request("w_sq_menu")

  DB_GetaddressList RS, w_cliente, null, "FISICO", null
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Endereços</TITLE>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  ShowHTML "  for (i = 0; i < theForm.w_sq_pessoa_endereco.length; i++) {"
  ShowHTML "      if (theForm.w_sq_pessoa_endereco[i].checked) break;"
  ShowHTML "      if (i == theForm.w_sq_pessoa_endereco.length-1) {"
  ShowHTML "         alert('Você deve selecionar pelo menos um endereço!');"
  ShowHTML "         return false;"
  ShowHTML "      }"
  ShowHTML "  }"
  Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa_endereco"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
  ShowHTML "<tr><td><b><font size=1 class=""hl"">" & MontaStringOpcao(w_sq_menu) & "</font></b>"
  ShowHTML "<tr><td><p>&nbsp;</p>"
  ShowHTML "<tr><td><font size=""1""><div align=""justify""><ul><b>Informações:</b><li>Você pode indicar em quais endereços uma determinada opção do menu estará disponível.<li>A princípio, todas as opções estão disponíveis em todos os endereços.<li>Para remover a opção de um endereço específico, desmarque o quadrado ao lado do endereço.<li>A opção deve estar disponível em pelo menos um dos endereços.</ul></div></p>"
  ShowHTML "<tr><td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
  ShowHTML "<tr><td align=""center"" colspan=3>"
  ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "          <td><font size=""2""><b>Habilitado</font></td>"
  ShowHTML "          <td><font size=""2""><b>Endereço</font></td>"
  ShowHTML "        </tr>"
  If RS.EOF Then
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
  Else
    While Not RS.EOF
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
      If cDbl(RS("checked")) > 0 Then
         ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_sq_pessoa_endereco"" value=""" & RS("sq_pessoa_endereco") & """ checked></td>"
      Else
         ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_sq_pessoa_endereco"" value=""" & RS("sq_pessoa_endereco") & """></td>"
      End If
      ShowHTML "        <td align=""left""><font  size=""1"">" & RS("endereco") & "</td>"
      ShowHTML "      </tr>"
      RS.MoveNext
    wend
  End If
  ShowHTML "      </center>"
  ShowHTML "    </table>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  DesConectaBD	 
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
  ShowHTML "      <tr><td align=""center""><font size=1>&nbsp;"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"">"
  ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "            <input class=""stb"" type=""button"" onClick=""window.close(); opener.focus();"" name=""Botao"" value=""Cancelar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</FORM>"
  Rodape

  Set RS1                    = Nothing
  Set w_sq_menu              = Nothing
  Set w_troca                = Nothing
  Set w_texto                = Nothing
  Set w_cont                 = Nothing
  Set w_contaux              = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de endereços
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_pessoa_endereco
  Dim p_modulo
  Dim w_Null
  Dim w_Chave
  Dim i, j

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  Select Case SG
    Case "ACESSOTRAMITE"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          If O = "I" Then
             For i = 1 To Request.Form("w_sq_pessoa").Count
                 DML_PutSgTraPes O,  Request.Form("w_sq_pessoa")(i), Request("w_sq_siw_tramite"), null
             Next
          ElseIf O = "E" Then
             DML_PutSgTraPes O,  Request("w_sq_pessoa"), Request("w_sq_siw_tramite"), Request("w_sq_pessoa_endereco")
          End If
          R = R & "&w_sq_menu=" & Request("w_sq_menu")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_siw_tramite=" & Request("w_sq_siw_tramite") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "SIWTRAMITE"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_SiwTramite O,  Request("w_sq_siw_tramite"), Request("w_sq_menu"), _
             Request("w_nome"), Request("w_ordem"), Request("w_sigla"), Request("w_descricao"), _
             Request("w_chefia_imediata"),  Request("w_ativo"), Request("w_solicita_cc"), Request("w_envia_mail") 

          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_menu=" & Request("w_sq_menu") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ACESSOMENU"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          If O = "I" Then
             For i = 1 To Request.Form("w_sq_pessoa").Count
                 DML_SgPesMen O,  Request.Form("w_sq_pessoa")(i), Request("w_sq_menu"), null
             Next
          ElseIf O = "E" Then
             DML_SgPesMen O,  Request("w_sq_pessoa"), Request("w_sq_menu"), Request("w_sq_pessoa_endereco")
          End If
          R = R & "&w_sq_menu=" & Request("w_sq_menu")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_servico=" & Request("w_sq_servico") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ACESSOMENUPERFIL"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          If O = "I" Then
             For i = 1 To Request.Form("w_sq_pessoa_endereco").Count
                For j = 1 To Request.Form("w_sq_tipo_vinculo").Count
                   DML_SgPerMen O,  Request.Form("w_sq_tipo_vinculo")(j), Request("w_sq_menu"), Request.Form("w_sq_pessoa_endereco")(i)
                Next
             Next
          ElseIf O = "E" Then
             DML_SgPerMen O,  Request("w_sq_tipo_vinculo"), Request("w_sq_menu"), Request("w_sq_pessoa_endereco")
          End If
          R = R & "&w_sq_menu=" & Request("w_sq_menu")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=ACESSOMENU';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ENDERECO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

           ' Inicialmente, desativa a opção em todos os endereços
           DML_SiwMenEnd "E", Request("w_sq_menu"), null
           
           ' Em seguida, ativa apenas para os endereços selecionados
           For i = 1 To Request.Form("w_sq_pessoa_endereco").Count
              If Request("w_sq_pessoa_endereco")(i) > "" Then
                 DML_SiwMenEnd "I", Request("w_sq_menu"), Request("w_sq_pessoa_endereco")(i)
              End If
           Next

           ScriptOpen "JavaScript"
           ShowHTML "  alert('Gravação efetivada com sucesso!');"
           ShowHTML "  window.close();"
           ShowHTML "  opener.focus();"
           ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set w_Chave               = Nothing
  Set p_sq_pessoa_endereco = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
  Set i                     = Nothing
  Set j                     = Nothing
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
    Case "ACESSOTRAMITE"
       AcessoTramite
    Case "TRAMITE"
       Tramite
    Case "ACESSOMENU"
       AcessoMenu
    Case "ACESSOMENUPERFIL"
       AcessoMenuPerfil
    Case "ENDERECO"
       Endereco
    Case "GRAVA"
       Grava
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

