<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_CV.asp" -->
<!-- #INCLUDE FILE="DML_CV.asp" -->
<!-- #INCLUDE FILE="VisualCurriculo.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /CV.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia telas do currículo do colaborador
REM Mail     : alex@sbpi.com.br
REM Criacao  : 25/03/2004 13:30
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

If nvl(Request("p_cliente"),"nulo") <> "nulo" Then Session("p_cliente") = Request("p_cliente")  End If
If nvl(Request("p_portal"),"nulo") <> "nulo"  Then Session("p_portal")  = Request("p_portal")   End If
If nvl(Request("p_logon"),"nulo") <> "nulo"   Then Session("LogOn")     = Request("p_LogOn")    End If
If nvl(Request("p_dbms"),"nulo") <> "nulo"    Then Session("dbms")      = Request("p_dbms")     End If

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta, UploadID
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
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
w_Pagina     = "cv.asp?par="
w_Dir        = "mod_rh_pub/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = uCase(Request("O"))

w_cliente = RetornaCliente()
w_usuario = RetornaUsuario()
If Session("p_portal") > "" Then
   Session("sq_pessoa") = w_usuario
End If

If nvl(SG,"nulo") <> "nulo" and nvl(SG,"nulo") <> "CVCARGOS"  Then 
   w_menu = RetornaMenu(w_cliente, SG) 
End If

Set ul            = New ASPForm

If Request("UploadID") > "" Then
   UploadID = Request("UploadID")
Else
   UploadID = ul.NewUploadID
End If
   
If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   Server.ScriptTimeout = 2000
   ul.SizeLimit = &HA00000
   If UploadID > 0 then
      ul.UploadID = UploadID
   End If
   P1           = ul.Texts.Item("P1")
   P2           = ul.Texts.Item("P2")
   P3           = ul.Texts.Item("P3")
   P4           = ul.Texts.Item("P4")
   TP           = ul.Texts.Item("TP")
   R            = uCase(ul.Texts.Item("R"))
   w_Assinatura = uCase(ul.Texts.Item("w_Assinatura"))
   w_troca      = ul.Texts.Item("w_troca")
Else
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = cDbl(Nvl(Request("P3"),1))
   P4           = cDbl(Nvl(Request("P4"),conPagesize))
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   
   If SG = "GDPINTERES" or SG = "GDPAREAS" Then
      If O <> "I" and Request("w_chave_aux") = "" Then 
         O = "L" 
      End If
   ElseIf SG = "GDPENVIO" Then 
      O = "V" 
   ElseIf O = "" Then 
      ' Se for acompanhamento, entra na filtragem
      If P1 = 3 Then O = "P" Else O = "L" End If
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

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configuração do serviço
If P2 > 0 and SG <> "CVVISUAL" Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If Not RS_menu.EOF Then
    If RS_menu("ultimo_nivel") = "S" Then
       ' Se for sub-menu, pega a configuração do pai
       DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
    End If
End If
Main

FechaSessao

Set UploadID      = Nothing
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
REM Rotina de visualização resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial
   
  Dim p_sq_area_conhecimento, p_sq_formacao, p_sq_idioma, p_sexo, p_nome
  
  p_sq_idioma   = Request("p_sq_idioma")
  p_sexo        = Request("p_sexo")
  p_nome        = UCase(Request("p_nome"))
  p_sq_formacao = Request("p_sq_formacao")
  
  If O = "L" Then
     ' Recupera os currículos existentes na base de dados
     DB_GetCVList RS, w_cliente, p_sq_formacao, p_sq_idioma, p_sexo, p_nome
     RS.Sort = "nome_resumido"
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de atividades</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("P",O) > 0 Then
     Validate "p_nome", "Nome", "1", "", "3", "40", "1", "1"
     Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.P4.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    If MontaFiltro("GET") > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Sexo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Formação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_sexo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
        ShowHTML "        <td><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "Visualizar&R=" & w_Pagina & par & "&O=L&w_usuario=" & RS("sq_pessoa") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe o CV deste colaborador."" target=""_blank"">Exibir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&MontaFiltro("GET"), RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&MontaFiltro("GET"), RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    
    ShowHTML "      <tr><td valign=""top"" width=""50%""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr>"
    SelecaoFormacao "F<u>o</u>rmação acadêmica:", "O", null, p_sq_formacao, "Acadêmica", "p_sq_formacao", null, null
    ShowHTML "      <tr>"
    SelecaoIdioma "I<u>d</u>ioma:", "D", null, p_sq_idioma, null, "p_sq_idioma", null, null
    SelecaoSexo "Se<u>x</u>o:", "X", null, p_sexo, null, "p_sexo", null, null
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
  Rodape

End Sub

REM =========================================================================
REM Rotina dos dados de identificação
REM -------------------------------------------------------------------------
Sub Identificacao

  Dim w_sq_estado_civil, w_nome, w_nome_resumido, w_nascimento, w_rg_numero, w_rg_emissor, w_rg_emissao, w_cpf
  Dim w_pais, w_uf, w_cidade, w_passaporte_numero, w_sq_pais_passaporte
  Dim w_sexo, w_sq_formacao
  Dim w_foto
  
  Dim i, w_erro, w_como_funciona, w_cor, w_readonly
  
  w_chave           = w_usuario
  w_readonly        = ""
  w_erro            = ""

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sq_estado_civil      = ul.Texts.Item("w_sq_estado_civil") 
     w_nome                 = ul.Texts.Item("w_nome") 
     w_nome_resumido        = ul.Texts.Item("w_nome_resumido") 
     w_foto                 = ul.Texts.Item("w_foto") 
     w_nascimento           = ul.Texts.Item("w_nascimento") 
     w_rg_numero            = ul.Texts.Item("w_rg_numero") 
     w_rg_emissor           = ul.Texts.Item("w_rg_emissor") 
     w_rg_emissao           = ul.Texts.Item("w_rg_emissao") 
     w_cpf                  = ul.Texts.Item("w_cpf")
     w_pais                 = ul.Texts.Item("w_pais") 
     w_uf                   = ul.Texts.Item("w_uf") 
     w_cidade               = ul.Texts.Item("w_cidade") 
     w_passaporte_numero    = ul.Texts.Item("w_passaporte_numero") 
     w_sq_pais_passaporte   = ul.Texts.Item("w_passaporte_numero") 
     w_sexo                 = ul.Texts.Item("w_sexo") 
     w_sq_formacao          = ul.Texts.Item("w_sq_formacao")
  Else
     ' Recupera os dados do currículo a partir da chave
     DB_GetCV RS, w_cliente, nvl(w_chave,0), SG, "DADOS"
     If RS.RecordCount > 0 Then 
        w_sq_estado_civil      = RS("sq_estado_civil") 
        w_nome                 = RS("nome") 
        w_nome_resumido        = RS("nome_resumido") 
        w_foto                 = RS("sq_siw_arquivo") 
        w_nascimento           = FormataDataEdicao(RS("nascimento"))
        w_rg_numero            = RS("rg_numero") 
        w_rg_emissor           = RS("rg_emissor") 
        w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
        w_cpf                  = RS("cpf")
        w_pais                 = RS("pais") 
        w_uf                   = RS("uf") 
        w_cidade               = RS("sq_cidade_nasc") 
        w_passaporte_numero    = RS("passaporte_numero") 
        w_sq_pais_passaporte   = RS("sq_pais_passaporte") 
        w_sexo                 = RS("sexo")
        w_sq_formacao          = RS("sq_formacao")
        O                      = "A"
        DesconectaBD
     Else
        w_nome                 = null
        O                      = "I"
     End If
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara.
  ScriptOpen "JavaScript"
  CheckBranco
  Modulo
  FormataData
  FormataCPF
  ProgressBar w_dir, UploadID  
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", ""
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", ""
     Validate "w_nascimento", "Data de nascimento", "DATA", 1, 10, 10, "", 1
     Validate "w_sexo", "Sexo", "SELECT", "1", "1", "10", "1", ""
     Validate "w_sq_estado_civil", "Estado civil", "SELECT", "1", "1", "10", "", "1"
     Validate "w_sq_formacao", "Formação acadêmica", "SELECT", "1", "1", "10", "", "1"
     Validate "w_foto", "Foto", "", "", "4", "200", "1", "1"
     ShowHTML "  if (theForm.w_foto.value != '') {"
     ShowHTML "     if (theForm.w_foto.value.toUpperCase().indexOf('.JPG') < 0 && theForm.w_foto.value.toUpperCase().indexOf('.GIF') < 0) {"
     ShowHTML "        alert('A foto informada deve ter extensão JPG ou GIF!');"
     ShowHTML "        theForm.w_foto.focus();"
     ShowHTML "        return false;"
     ShowHTML "     }"
     ShowHTML "  }"
     Validate "w_pais", "País nascimento", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_uf", "Estado nascimento", "SELECT", 1, 1, 3, "1", "1"
     Validate "w_cidade", "Cidade nascimento", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_rg_numero", "RG", "1", "1", "5", "18", "1", "1"
     Validate "w_rg_emissor", "Emissor", "1", "1", "5", "80", "1", "1"
     Validate "w_rg_emissao", "Data de emissão", "DATA", "1", "10", "10", "", "0123456789/"
     CompData "w_nascimento", "Data de nascimento", "<", "w_rg_emissao", "Data de emissão"
     Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789.-"
     Validate "w_passaporte_numero", "Passaporte", "1", "", 1, 40, "1", "1"
     ShowHTML "  if (theForm.w_passaporte_numero.value != '') {"
     ShowHTML "     if (theForm.w_sq_pais_passaporte.selectedIndex == 0) {"
     ShowHTML "        alert('Indique o país emissor do passaporte!');"
     ShowHTML "        theForm.w_sq_pais_passaporte.focus();"
     ShowHTML "        return false;"
     ShowHTML "     }"
     ShowHTML "  } else {"
     ShowHTML "     if (theForm.w_sq_pais_passaporte.selectedIndex != 0) {"
     ShowHTML "        theForm.w_sq_pais_passaporte.selectedIndex = 0;"
     ShowHTML "     }"
     ShowHTML "  }"
     If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura eletrônica", "1", "1", "3", "14", "1", "1" End If
  End If
  ShowHTML "if (theForm.w_foto.value != '') {return ProgressBar();}"  
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  If Session("p_portal") = "" Then
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
  End If
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If w_pais = "" Then
       ' Carrega os valores padrão para país, estado e cidade
       DB_GetCustomerData RS, w_cliente
       w_pais   = RS("sq_pais")
       w_uf     = RS("co_uf")
       w_cidade = RS("sq_cidade_padrao")
       DesconectaBD
    End If
  
    ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG="&SG&"&O="&O&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    If Session("p_portal") > "" and O = "I" Then
       ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & Request.ServerVariables("HTTP_REFERER") & """>"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina&par & """>"
    End If
    ShowHTML MontaFiltro("UL")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_usuario &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_atual"" value=""" & w_foto &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"">"

    ShowHTML "        <tr><td colspan=3 align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3><font size=1>Este bloco deve ser preenchido com dados de identificação e características pessoais.</font></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td title=""Informe seu nome completo, sem abreviações.""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & Nvl(w_nome,Session("nome")) & """></td>"
    ShowHTML "          <td title=""Informe o nome pelo qual você prefere ser chamado ou pelo qual é mais conhecido.""><font size=""1""><b>Nome <u>r</u>esumido:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & Nvl(w_nome_resumido,Session("nome_resumido")) & """></td>"
    ShowHTML "          <td title=""Informe a data do seu nascimento, conforme consta da carteira de identidade.""><font size=""1""><b>Data <u>n</u>ascimento:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nascimento"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_nascimento & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoSexo "<u>S</u>exo:", "S", null, w_sexo, null, "w_sexo", null, null
    ShowHTML "          <td colspan=2><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"
    SelecaoEstadoCivil "Estado ci<u>v</u>il:", "V", null, w_sq_estado_civil, null, "w_sq_estado_civil", null, null
    ShowHTML "          </table>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoFormacao "F<u>o</u>rmação acadêmica:", "O", "Selecione a formação acadêmica mais alta que você tem como comprovar a conclusão.", w_sq_formacao, "Acadêmica", "w_sq_formacao", null, null
    ShowHTML "          <td colspan=2 title=""Selecione o arquivo que contém sua foto. Deve ser um arquivo com a extensão JPG ou GIF, com até 50KB.""><font size=""1""><b><u>F</u>oto:</b><br><input " & w_Disabled & " accesskey=""N"" type=""file"" name=""w_foto"" class=""sti"" SIZE=""40"" MAXLENGTH=""200"" VALUE="""">&nbsp;"
    If w_foto > "" Then ShowHTML LinkArquivo("SS", w_cliente, w_foto, "_blank", null, "Exibir", null) End If
                        
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Local nascimento</td></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3><font size=1>Selecione nos campos abaixo o país, o estado e a cidade de nascimento.</font></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", "Selecione o país de nascimento e aguarde a tela carregar os estados.", w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_dir&w_pagina&par&"&SG="&SG&"&O="&O & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", "Selecione o estado de nascimento e aguarde a tela carregar as cidades.", w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_dir&w_pagina&par&"&SG="&SG&"&O="&O & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", "Selecine a cidade de nascimento.", w_cidade, w_pais, w_uf, "w_cidade", null, null

    ShowHTML "        <tr><td colspan=3 align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3 valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Documentação</td></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr><td colspan=3><font size=1>Informe, nos campos a seguir, os dados relativos à sua documentação.</font></td></tr>"
    ShowHTML "        <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td title=""Informe o número da sua carteira de identidade (registro geral).""><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""30"" VALUE=""" & w_rg_numero & """></td>"
    ShowHTML "          <td title=""Informe o nome do órgão expedidor de sua carteira de identidade.""><font size=""1""><b><u>E</u>missor:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""10"" MAXLENGTH=""15"" VALUE=""" & w_rg_emissor & """></td>"
    ShowHTML "          <td title=""Informe a data de emissão de sua carteira de identidade.""><font size=""1""><b><u>D</u>ata emissão:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "        <tr valign=""top"">"
    If O = "I" Then
       ShowHTML "          <td title=""Informe seu número no Cadastro de Pessoas Físicas - CPF.""><font size=""1""><b>CP<u>F</u>:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_cpf"" class=""sti"" SIZE=""14"" MAXLENGTH=""14"" VALUE=""" & w_cpf & """ onKeyDown=""FormataCPF(this,event);""></td>"
    Else
       ShowHTML "          <td title=""Seu CPF não pode ser alterado.""><font size=""1""><b>CP<u>F</u>:</b><br><input " & w_Disabled & " readonly accesskey=""F"" type=""text"" name=""w_cpf"" class=""sti"" SIZE=""14"" MAXLENGTH=""14"" VALUE=""" & w_cpf & """ onKeyDown=""FormataCPF(this,event);""></td>"
    End If
    ShowHTML "          <td title=""Se possuir um passaporte, informe o número.""><font size=""1""><b>Número passapo<u>r</u>te:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_passaporte_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_passaporte_numero & """></td>"
    SelecaoPais "<u>P</u>aís passaporte:", "P", "Se possuir um passaporte, selecione o país de emissão.", w_sq_pais_passaporte, null, "w_sq_pais_passaporte", null, null
    ShowHTML "      </table>"

    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=3><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
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

  Set w_foto                = Nothing 
  Set w_sq_estado_civil     = Nothing 
  Set w_nome                = Nothing 
  Set w_nome_resumido       = Nothing 
  Set w_nascimento          = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_cpf                 = Nothing
  Set w_pais                = Nothing 
  Set w_uf                  = Nothing 
  Set w_cidade              = Nothing 
  Set w_passaporte_numero   = Nothing 
  Set w_sq_pais_passaporte  = Nothing 
  Set w_sexo                = Nothing 
  Set w_sq_formacao         = Nothing
  
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina dos dados de identificação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de idiomas
REM -------------------------------------------------------------------------
Sub Idiomas
  Dim w_chave, w_sq_idioma, w_leitura, w_escrita, w_compreensao, w_conversacao
  Dim w_nm_idioma
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_leitura              = Request("w_leitura")
     w_escrita              = Request("w_escrita")
     w_compreensao          = Request("w_compreensao")
     w_conversacao          = Request("w_conversacao")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCVIdioma RS, w_usuario, null
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do registro informado
     DB_GetCVIdioma RS, w_usuario, w_chave
     w_nm_idioma        = RS("nome")
     w_chave            = RS("sq_idioma")
     w_leitura          = RS("leitura")
     w_escrita          = RS("escrita")
     w_compreensao      = RS("compreensao")
     w_conversacao      = RS("conversacao")    
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("I",O) > 0 Then
        Validate "w_chave", "Idioma", "SELECT", "1", "1", "10", "", "1"
     ElseIf O = "E" and Session("p_portal") = "" Then
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
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_chave.focus()';"
  ElseIf O = "E" and Session("p_portal") = "" Then
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
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Idioma</font></td>"
    ShowHTML "          <td><font size=""1""><b>Leitura</font></td>"
    ShowHTML "          <td><font size=""1""><b>Escrita</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conversação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Compreensão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_leitura") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_escrita") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_conversacao") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_compreensao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_idioma") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_idioma") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If O = "I" Then
       ShowHTML "      <tr>"
       SelecaoIdioma "I<u>d</u>dioma:", "D", "Selecione o idioma que você deseja informar os dados.", w_chave, null, "w_chave", null, null
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
       ShowHTML "      <tr><td valign=""top""><font size=""1"">Idioma:</b><br><b>" & w_nm_idioma
    End If
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Você lê com facilidade textos escritos no idioma selecionado acima?</b>", w_leitura, "w_leitura"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Você escreve textos com facilidade no idioma selecionado acima?</b>", w_escrita, "w_escrita"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Você compreende com facilidade pessoas conversando no idioma selecionado acima?</b>", w_compreensao, "w_compreensao"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Você conversa fluentemente no idioma selecionado acima?</b>", w_conversacao, "w_conversacao"
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_chave                = Nothing 
  Set w_leitura              = Nothing
  Set w_escrita              = Nothing 
  Set w_compreensao          = Nothing 
  Set w_conversacao          = Nothing 
  
  Set w_troca                = Nothing 
  Set i                      = Nothing 
  Set w_erro                 = Nothing
End Sub
REM =========================================================================
REM Fim da tela de idiomas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de experiencia profissional
REM -------------------------------------------------------------------------
Sub Experiencia
  
  Dim w_sq_cvpessoa, w_sq_cvpesexp, w_sq_area_conhecimento, w_sq_pais, w_co_uf
  Dim w_sq_cidade, w_sq_eo_tipo_posto, w_sq_tipo_vinculo, w_empregador, w_entrada, w_saida, w_duracao_mes
  Dim w_duracao_ano, w_motivo_saida, w_nm_area
  Dim w_nome, w_atividades

  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")

  If w_troca > "" then
     w_sq_area_conhecimento     = Request("w_sq_area_conhecimento")
     w_nm_area                  = Request("w_nm_area")
     w_sq_pais                  = Request("w_sq_pais")
     w_co_uf                    = Request("w_co_uf")
     w_sq_cidade                = Request("w_sq_cidade")
     w_sq_eo_tipo_posto         = Request("w_sq_eo_tipo_posto")
     w_sq_tipo_vinculo          = Request("w_sq_tipo_vinculo")
     w_atividades               = Request("w_atividades")
     w_empregador               = Request("w_empregador")
     w_entrada                  = Request("w_entrada")
     w_saida                    = Request("w_saida")
     w_duracao_mes              = Request("w_duracao_mes")
     w_duracao_ano              = Request("w_duracao_ano")
     w_motivo_saida             = Request("w_motivo_saida")
  end if   

  If O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCVAcadForm RS, w_usuario, null, "EXPERIENCIA"
     RS.Sort = "entrada desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     DB_GetCVAcadForm RS, w_usuario, w_chave, "EXPERIENCIA"

     w_sq_area_conhecimento     = RS("sq_area_conhecimento")
     If IsNull(RS("nm_area")) Then w_nm_area = "" Else w_nm_area = RS("nm_area") & " (" & RS("codigo_cnpq") & ")" End If
     w_sq_pais                  = RS("sq_pais")
     w_co_uf                    = RS("co_uf")
     w_sq_cidade                = RS("sq_cidade")
     w_sq_eo_tipo_posto         = RS("sq_eo_tipo_posto")
     w_sq_tipo_vinculo          = RS("sq_tipo_vinculo")
     w_empregador               = RS("empregador")
     w_atividades               = RS("atividades")
     w_entrada                  = FormataDataEdicao(RS("entrada"))
     w_saida                    = FormataDataEdicao(RS("saida"))
     w_duracao_mes              = RS("duracao_mes")
     w_duracao_ano              = RS("duracao_ano")
     w_motivo_saida             = RS("motivo_saida")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_empregador", "Empregador", "1", "1", "1", "60", "1", "1"
        Validate "w_nm_area", "Área do conhecimento", "", "1", "1", "80", "1", "1"
        Validate "w_entrada", "Data entrada", "DATA", "1", "10", "10", "", "1"
        Validate "w_saida", "Data saída", "DATA", "", "10", "10", "", "1"
        CompData "w_entrada", "Data entrada", "<", "w_saida", "Data saída"
        ShowHTML "  if (theForm.w_saida.value != '' && theForm.w_motivo_saida.value == '') {"
        ShowHTML "     alert('Informe o motivo da saída!');"
        ShowHTML "     theForm.w_motivo_saida.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        Validate "w_motivo_saida", "Motivo saída", "1", "", "1", "255", "1", "1"
        Validate "w_sq_pais", "Pais", "SELECT", "1", "1", "10", "", "1"
        Validate "w_co_uf", "Estado", "SELECT", "1", "1", "10", "1", ""
        Validate "w_sq_cidade", "Cidade", "SELECT", "1", "1", "10", "", "1"
        ShowHTML "  var i; "
        ShowHTML "  var w_erro=true; "
        ShowHTML "  if (theForm.w_sq_eo_tipo_posto.value==undefined) {"
        ShowHTML "     for (i=0; i < theForm.w_sq_eo_tipo_posto.length; i++) {"
        ShowHTML "       if (theForm.w_sq_eo_tipo_posto[i].checked) w_erro=false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_sq_eo_tipo_posto.checked) w_erro=false;"
        ShowHTML "  }"
        ShowHTML "  if (w_erro) {"
        ShowHTML "    alert('Informe a principal atividade desempenhada!'); "
        ShowHTML "    return false;"
        ShowHTML "  }"
        Validate "w_atividades", "Atividades desempenhadas", "", "1", "4", "4000", "1", "1"
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
     ElseIf O = "E" Then
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
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
  If O = "L" Then
     BodyOpen "onLoad='document.focus();'"
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"   
  Else
     BodyOpen "onLoad='document.Form.w_empregador.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir&  w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_cvpessoa=" & w_sq_cvpessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Área</font></td>"
    ShowHTML "          <td><font size=""1""><b>Empregador</font></td>"
    ShowHTML "          <td><font size=""1""><b>Entrada</font></td>"
    ShowHTML "          <td><font size=""1""><b>Saída</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontradas experiências profissionais cadastradas.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_area") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("empregador") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("entrada")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("saida")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cvpessoa=" & w_sq_cvpessoa & "&w_chave="& RS("sq_cvpesexp") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_cvpessoa=" & w_sq_cvpessoa & "&w_chave="& RS("sq_cvpesexp") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ onClick=""return confirm('Confirma a exclusão do emprego?');"">Excluir</A>&nbsp"
        ShowHTML "          <u class=""HL"" style=""cursor:hand;"" onclick=""javascript:window.open('" & w_Pagina &"CARGOS&R=" & w_Pagina &"CARGOS&O=L&w_sq_cvpessoa=" & w_sq_cvpessoa & "&w_sq_cvpesexp="& RS("sq_cvpesexp") & "&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Cargos&SG=CVCARGOS" & MontaFiltro("GET") & "','Cargos','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Cargos</u>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td colspan=3><font size=1><br><b>Instruções:</b>"
    ShowHTML "   <ul>"
    ShowHTML "   <li>A finalidade desta tela é registrar toda a sua experiência profissional;"
    ShowHTML "   <li>Para cada experiência profissional, informe os cargos que desempenhou na organização;"
    ShowHTML "   <li>Indique sempre a que área do conhecimento a experiência está vinculada (Ex: contabilidade, administração etc);"
    ShowHTML "   <li>Se a área do conhecimento ou o cargo desempenhado não forem localizados, busque por um nome mais abrangente ou entre em contato com o gestor do sistema."
    ShowHTML "   </ul>"
    DesConectaBD
  ElseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm  "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, w_pagina & par, O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value=""" & w_troca & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cvpessoa"" value=""" & w_sq_cvpessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento &""">"
    ShowHTML MontaFiltro("POST")

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>E</u>mpregador:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_empregador"" class=""sti"" SIZE=""60"" MAXLENGTH=""60"" VALUE=""" & w_empregador & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área do conhecimento relacionada:</b><br>"
    ShowHTML "              <input READONLY type=""text"" name=""w_nm_area"" class=""sti"" SIZE=""50"" VALUE=""" & w_nm_area & """>"
    If O <> "E" Then
       ShowHTML "              [<u onMouseOver=""this.style.cursor='Hand'"" onMouseOut=""this.style.cursor='Pointer'"" onClick=""window.open('" & w_pagina & "BuscaAreaConhecimento&TP=" & TP & "&SG=" & SG & "&P1=1','AreaConhecimento','top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes');""><b><font color=""#0000FF"">Procurar</font></b></u>]"
    End If
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b>E<U>n</U>trada:</b></br><INPUT ACCESSKEY=""n"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_entrada"" size=""10"" maxlength=""10"" value=""" & w_entrada & """ onKeyDown=""FormataData(this, event)"">"
    ShowHTML "              <td valign=""top""><font size=""1""><b><U>S</U>aída:</b></br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_saida"" size=""10"" maxlength=""10"" value=""" & w_saida & """ onKeyDown=""FormataData(this, event)"">"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Mo<u>t</u>ivo saída:</b><br><textarea " & w_Disabled & " accesskey=""t""  name=""w_motivo_saida"" class=""sti"" cols=""80"" rows=""4"">" & w_motivo_saida & "</textarea></td>"
    ShowHTML "      <tr valign=""top""><td colspan=""2"">"
    ShowHTML "         <table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "           <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", "Selecione o país da experiência profissional e aguarde a tela carregar os estados.", w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_dir&w_pagina&par & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", "Selecione o estado da experiência profissional e aguarde a tela carregar as cidades.", w_co_uf, w_sq_pais, "N", "w_co_uf", null, "onChange=""document.Form.action='" & w_dir&w_pagina&par & "'; document.Form.w_troca.value='w_sq_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", "Selecine a cidade de nascimento.", w_sq_cidade, w_sq_pais, w_co_uf, "w_sq_cidade", null, null
    ShowHTML "         </table></td></tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoTipoPosto "Informe a principal atividade desempenhada:", "T", null, w_sq_eo_tipo_posto, null, "w_sq_eo_tipo_posto", "S"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>At<u>i</u>vidades desempenhadas:</b><br><textarea " & w_Disabled & " accesskey=""i""  name=""w_atividades"" class=""sti"" cols=""80"" rows=""4"">" & w_atividades & "</textarea></td>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Excluir"">"
       ShowHTML "            <input class=""sti"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""sti"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
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

  Set w_sq_cvpessoa             = Nothing
  Set w_sq_cvpesexp             = Nothing    
  Set w_sq_area_conhecimento    = Nothing    
  Set w_nm_area    = Nothing    
  Set w_sq_pais                 = Nothing
  Set w_co_uf                   = Nothing
  Set w_sq_cidade               = Nothing
  Set w_sq_eo_tipo_posto        = Nothing
  Set w_sq_tipo_vinculo         = Nothing
  Set w_empregador              = Nothing
  Set w_atividades              = Nothing
  Set w_entrada                 = Nothing
  Set w_saida                   = Nothing
  Set w_duracao_mes             = Nothing
  Set w_duracao_ano             = Nothing
  Set w_motivo_saida            = Nothing    
  Set w_nome                    = Nothing

End Sub
REM =========================================================================
REM Fim da tela de experiencia profissional
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cargos
REM -------------------------------------------------------------------------
Sub Cargos
  
  Dim w_sq_cvpessoa, w_sq_cvpescargo, w_sq_area_conhecimento 
  Dim w_especialidades, w_fim,w_inicio, w_nome, w_sq_cvpesexp
  Dim w_nome_empregador
  Dim w_nm_area
    
  w_sq_cvpescargo        = Request("w_sq_cvpescargo")
  w_sq_cvpesexp          = Request("w_sq_cvpesexp")  
  
  DB_GetCVAcadForm RS, w_usuario, w_sq_cvpesexp, "EXPERIENCIA"
 
  w_nome_empregador  = RS("empregador")
  
  DesconectaBD

  If O = "L" Then
     
     DB_GetCVAcadForm RS, w_sq_cvpesexp, null, "CARGO"
     
  ElseIf InStr("AEV",O) > 0 Then
  
     ' Recupera o conjunto de informações comum a todos os serviços
     DB_GetCVAcadForm RS, w_sq_cvpesexp, w_sq_cvpescargo, "CARGO"
     
     w_sq_area_conhecimento         = RS("sq_area_conhecimento")
     w_nm_area                      = RS("nm_area")
     w_especialidades               = RS("especialidades")
     w_inicio                       = FormataDataEdicao(RS("inicio"))
     w_fim                          = FormataDataEdicao(RS("fim"))
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<title>Cargos de uma experiência profissional</title>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formataData
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_area_conhecimento", "Área do conhecimento", "SELECT", "1", "1", "10", "", "1"
        Validate "w_especialidades","Especialidades", "1", "1", "1", "255", "QWERTYUIOPASDFGHJKLZXCVBNM; ", "1"
        ShowHTML " if (document.Form.w_especialidades.value.indexOf(';')==-1){"
        ShowHTML "   alert('Digite apenas palavras maisculas não acentuadas e separados por ponto-virgula.'); "
        ShowHTML "   document.Form.w_especialidades.focus();"
        ShowHTML "   return (false);"
        ShowHTML " }"
        Validate "w_inicio", "Início", "Data", "1", "10", "10", "", "1"
        Validate "w_fim", "Fim", "Data", "", "10", "10", "", "1"
        Validate "w_nm_area", "Área do conhecimento", "", "1", "1", "80", "1", "1"
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
     ElseIf O = "E" Then
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
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
  If InStr("IA",O) > 0 Then
    BodyOpen "onLoad='document.Form.w_especialidades.focus()';"
  Else
    BodyOpen "onLoad='document.focus()';"
  end if
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1"">Empregador:<b> " & w_nome_empregador & "</b>"
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&w_sq_cvpesexp= " & w_sq_cvpesexp & "&R=" & w_Pagina & par & "&O=I&w_sq_cvpessoa=" & w_sq_cvpessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.location.reload();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Cargo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Fim</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados cargos cadastrados.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_area") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("fim")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cvpesexp="& Rs("sq_cvpesexp") &"&w_sq_cvpescargo="& Rs("sq_cvpescargo") &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_cvpesexp="& Rs("sq_cvpesexp") &"&w_sq_cvpescargo="& Rs("sq_cvpescargo") &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ onClick=""return confirm('Confirma a exclusão do cargo?');"">Excluir</A>&nbsp"
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
  elseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm  "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cvpessoa"" value=""" & w_sq_cvpessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cvpescargo"" value=""" & w_sq_cvpescargo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cvpesexp"" value=""" & w_sq_cvpesexp &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento &""">"
    ShowHTML MontaFiltro("POST")

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1"">Empregador:<br><b>" & w_nome_empregador & "</b></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>E</u>specialidades(Digite apenas palavras maisculas não acentuadas e separados por ponto-virgula.):</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_especialidades"" class=""sti"" SIZE=""255"" MAXLENGTH=""255"" COLS = ""90"" ROWS=""5"">" & w_especialidades & "</TEXTAREA></td>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "            <td valign=""top""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "            <td valign=""top""><font size=""1""><b><u>F</u>im:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Cargo desempenhado:</b><br>"
    ShowHTML "          <input READONLY type=""text"" name=""w_nm_area"" class=""sti"" SIZE=""50"" VALUE=""" & w_nm_area & """>"
    ShowHTML "          [<u onMouseOver=""this.style.cursor='Hand'"" onMouseOut=""this.style.cursor='Pointer'"" onClick=""window.open('" & w_pagina & "BuscaAreaConhecimento&TP=" & TP & "&P1=2','SelecaoCargo','top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes');""><b><font color=""#0000FF"">Procurar</font></b></u>]"
    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
	ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""sti"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""sti"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_cvpesexp= " & w_sq_cvpesexp & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_cvpessoa                 = Nothing
  Set w_sq_cvpescargo               = Nothing
  Set w_sq_cvpesexp                 = Nothing
  Set w_sq_area_conhecimento        = Nothing
  Set w_nm_area                     = Nothing
  Set w_especialidades              = Nothing
  Set w_inicio                      = Nothing
  Set w_fim                         = Nothing
  Set w_nome                        = Nothing
  Set w_nome_empregador             = Nothing
  
End Sub
REM =========================================================================
REM Fim da tela de cargos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de formação acadêmica
REM -------------------------------------------------------------------------
Sub Escolaridade
  Dim w_chave, w_sq_area_conhecimento, w_sq_pais, w_sq_formacao, w_nome, w_instituicao, w_inicio, w_fim
  Dim w_nm_area
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_area_conhecimento = Request("w_sq_area_conhecimento")
     w_sq_pais              = Request("w_sq_pais")
     w_sq_formacao          = Request("w_sq_formacao")
     w_nome                 = Request("w_nome")
     w_instituicao          = Request("w_instituicao")
     w_inicio               = Request("w_inicio")
     w_fim                  = Request("w_fim")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCVAcadForm RS, w_usuario, null, "ACADEMICA"
     RS.Sort = "ordem desc, inicio desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetCVAcadForm RS, w_usuario, w_chave, "ACADEMICA"
     w_sq_area_conhecimento = RS("sq_area_conhecimento")
     If IsNull(RS("nm_area")) Then w_nm_area = "" Else w_nm_area = RS("nm_area") & " (" & RS("codigo_cnpq") & ")" End If
     w_sq_pais              = RS("sq_pais")
     w_sq_formacao          = RS("sq_formacao")
     w_nome                 = RS("nome")
     w_instituicao          = RS("instituicao")
     w_inicio               = RS("inicio")
     w_fim                  = RS("fim")    
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     checkbranco
     formatadatama
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_formacao", "Formação", "SELECT", "1", "1", "10", "", "1"
        Validate "w_nm_area", "Área do conhecimento", "", "", "1", "80", "1", "1"
        ShowHTML "  if (theForm.w_sq_formacao.selectedIndex > 3 && (theForm.w_sq_area_conhecimento.value=='' || theForm.w_nome.value=='')) { "
        ShowHTML "     alert('Se formação acadêmica for graduação ou acima, informe a área do conhecimento e o nome do curso'); "
        ShowHTML "     return false; "
        ShowHTML "  } "
        Validate "w_nome", "Nome", "1", "", "3", "80", "1", "1"
        Validate "w_instituicao", "Instituição", "1", "1", "1", "100", "1", "1"
        Validate "w_inicio", "Início", "DATAMA", "1", "7", "7", "", "0123456789/"
        Validate "w_fim", "Fim", "DATAMA", "", "7", "7", "", "0123456789/"
        Validate "w_sq_pais", "País conclusão", "SELECT", "1", "1", "10", "", "1"
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
     ElseIf O = "E" and Session("p_portal") = "" Then
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
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_formacao.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" and Session("p_portal") = "" Then
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
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nível</font></td>"
    ShowHTML "          <td><font size=""1""><b>Área</font></td>"
    ShowHTML "          <td><font size=""1""><b>Curso</font></td>"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Término</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("nm_area"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("nome"),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("inicio") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("fim"),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_cvpessoa_escol") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_cvpessoa_escol") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoFormacao "F<u>o</u>rmação acadêmica:", "O", "Selecione a formação acadêmica que você deseja informar os dados.", w_sq_formacao, "Acadêmica", "w_sq_formacao", null, null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Se formação for graduação ou maior, indique a área do conhecimento:</b><br>"
    ShowHTML "              <input READONLY type=""text"" name=""w_nm_area"" class=""sti"" SIZE=""50"" VALUE=""" & w_nm_area & """>"
    If O <> "E" Then
       ShowHTML "              [<u onMouseOver=""this.style.cursor='Hand'"" onMouseOut=""this.style.cursor='Pointer'"" onClick=""window.open('" & w_pagina & "BuscaAreaConhecimento&TP=" & TP & "&SG=" & SG & "&P1=1','AreaConhecimento','top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes');""><b><font color=""#0000FF"">Procurar</font></b></u>]"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome curso:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""80"" MAXLENGTH=""80"" VALUE=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>I</u>nstituição:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_instituicao"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE=""" & w_instituicao & """></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Íni<u>c</u>io: (mm/aaaa)</b><br><input " & w_Disabled & " accesskey=""c"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""7"" MAXLENGTH=""7"" VALUE=""" & w_inicio & """ onKeyDown=""FormataDataMA(this,event);""></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b>Fi<u>m</u>: (mm/aaaa)</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""7"" MAXLENGTH=""7"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataMA(this,event);""></td>"
    SelecaoPais "<u>P</u>aís de conclusão:", "P", "Selecione o país onde concluiu esta formação.", Nvl(w_sq_pais,2), null, "w_sq_pais", null, null
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_chave                = Nothing 
  Set w_sq_area_conhecimento = Nothing 
  Set w_sq_formacao          = Nothing
  Set w_sq_pais              = Nothing
  Set w_nome                 = Nothing
  Set w_instituicao          = Nothing 
  Set w_inicio               = Nothing 
  Set w_fim                  = Nothing 
  
  Set w_troca                = Nothing 
  Set i                      = Nothing 
  Set w_erro                 = Nothing
End Sub
REM =========================================================================
REM Fim da tela de formação acadêmica
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cursos técnicos
REM -------------------------------------------------------------------------
Sub Extensao
  Dim w_chave, w_sq_area_conhecimento, w_sq_formacao, w_nome, w_instituicao, w_carga_horaria, w_conclusao
  Dim w_nm_area
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_area_conhecimento = Request("w_sq_area_conhecimento")
     w_sq_formacao          = Request("w_sq_formacao")
     w_nome                 = Request("w_nome")
     w_instituicao          = Request("w_instituicao")
     w_carga_horaria        = Request("w_carga_horaria")
     w_conclusao            = Request("w_conclusao")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCVAcadForm RS, w_usuario, null, "CURSO"
     RS.Sort = "ordem desc, carga_horaria desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetCVAcadForm RS, w_usuario, w_chave, "CURSO"
     w_sq_area_conhecimento = RS("sq_area_conhecimento")
     w_nm_area              = RS("nm_area") & " (" & RS("codigo_cnpq") & ")"
     w_sq_formacao          = RS("sq_formacao")
     w_nome                 = RS("nome")
     w_instituicao          = RS("instituicao")
     w_carga_horaria        = RS("carga_horaria")
     w_conclusao            = FormataDataEdicao(RS("conclusao"))
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     checkbranco
     formatadata
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_formacao", "Tipo de extensão", "SELECT", "1", "1", "10", "", "1"
        Validate "w_nm_area", "Área do conhecimento", "", "1", "1", "80", "1", "1"
        Validate "w_nome", "Nome", "1", "1", "5", "80", "1", "1"
        Validate "w_instituicao", "Instituição", "1", "1", "1", "100", "1", "1"
        Validate "w_carga_horaria", "Carga horária", "", "1", "2", "4", "", "0123456789"
        Validate "w_conclusao", "conclusao", "DATA", "", "10", "10", "", "0123456789/"
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
     ElseIf O = "E" and Session("p_portal") = "" Then
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
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_formacao.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" and Session("p_portal") = "" Then
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
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nível</font></td>"
    ShowHTML "          <td><font size=""1""><b>Área</font></td>"
    ShowHTML "          <td><font size=""1""><b>Curso</font></td>"
    ShowHTML "          <td><font size=""1""><b>C.H.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conclusão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_area") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("carga_horaria") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("conclusao")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_cvpescurtec") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_cvpescurtec") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoFormacao "T<u>i</u>po de extensão:", "O", "Selecione o tipo mais adequado para a extensão acadêmica.", w_sq_formacao, "Técnica", "w_sq_formacao", null, null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área do conhecimento relacionada:</b><br>"
    ShowHTML "              <input READONLY type=""text"" name=""w_nm_area"" class=""sti"" SIZE=""50"" VALUE=""" & w_nm_area & """>"
    If O <> "E" Then
       ShowHTML "              [<u onMouseOver=""this.style.cursor='Hand'"" onMouseOut=""this.style.cursor='Pointer'"" onClick=""window.open('" & w_pagina & "BuscaAreaConhecimento&TP=" & TP & "&SG=" & SG & "&P1=1','AreaConhecimento','top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes');""><b><font color=""#0000FF"">Procurar</font></b></u>]"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome curso:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""80"" MAXLENGTH=""80"" VALUE=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>I</u>nstituição:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_instituicao"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE=""" & w_instituicao & """></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>C</u>arga horária:</b><br><input " & w_Disabled & " accesskey=""c"" type=""text"" name=""w_carga_horaria"" class=""sti"" SIZE=""7"" MAXLENGTH=""7"" VALUE=""" & w_carga_horaria & """></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b>C<u>o</u>nclusão: (dd/mm/aaaa)</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_conclusao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_conclusao & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_chave                = Nothing 
  Set w_sq_area_conhecimento = Nothing 
  Set w_sq_formacao          = Nothing
  Set w_nome                 = Nothing
  Set w_instituicao          = Nothing 
  Set w_carga_horaria        = Nothing 
  Set w_conclusao            = Nothing 
  
  Set w_troca                = Nothing 
  Set i                      = Nothing 
  Set w_erro                 = Nothing
End Sub
REM =========================================================================
REM Fim da tela de cursos técnicos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de produção técnica
REM -------------------------------------------------------------------------
Sub Producao
  Dim w_chave, w_sq_area_conhecimento, w_sq_formacao, w_nome, w_meio, w_data
  Dim w_nm_area
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_area_conhecimento = Request("w_sq_area_conhecimento")
     w_sq_formacao          = Request("w_sq_formacao")
     w_nome                 = Request("w_nome")
     w_meio                 = Request("w_meio")
     w_data                 = Request("w_data")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCVAcadForm RS, w_usuario, null, "PRODUCAO"
     RS.Sort = "ordem desc, data desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetCVAcadForm RS, w_usuario, w_chave, "PRODUCAO"
     w_sq_area_conhecimento = RS("sq_area_conhecimento")
     w_nm_area              = RS("nm_area") & " (" & RS("codigo_cnpq") & ")"
     w_sq_formacao          = RS("sq_formacao")
     w_nome                 = RS("nome")
     w_meio                 = RS("meio")
     w_data                 = FormataDataEdicao(RS("data"))
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     checkbranco
     formatadata
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_formacao", "Tipo da produção", "SELECT", "1", "1", "10", "", "1"
        Validate "w_nm_area", "Área do conhecimento", "", "1", "5", "80", "1", "1"
        Validate "w_nome", "Nome", "1", "1", "1", "80", "1", "1"
        Validate "w_meio", "Meio de publicação", "", "1", "2", "100", "1", "1"
        Validate "w_data", "Data", "DATA", "1", "10", "10", "", "0123456789/"
        If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1" End If
     ElseIf O = "E" and Session("p_portal") = "" Then
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
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_formacao.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" and Session("p_portal") = "" Then
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
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo da produção</font></td>"
    ShowHTML "          <td><font size=""1""><b>Área</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Meio</font></td>"
    ShowHTML "          <td><font size=""1""><b>Data</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_formacao") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_area") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("meio") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("data")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_cvpessoa_prod") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_cvpessoa_prod") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoFormacao "T<u>i</u>po da produção:", "O", "Selecione o tipo mais adequado para a produção técnica.", w_sq_formacao, "Prod.Cient.", "w_sq_formacao", null, null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área do conhecimento relacionada:</b><br>"
    ShowHTML "              <input READONLY type=""text"" name=""w_nm_area"" class=""sti"" SIZE=""50"" VALUE=""" & w_nm_area & """>"
    If O <> "E" Then
       ShowHTML "              [<u onMouseOver=""this.style.cursor='Hand'"" onMouseOut=""this.style.cursor='Pointer'"" onClick=""window.open('" & w_pagina & "BuscaAreaConhecimento&TP=" & TP & "&SG=" & SG & "&P1=1','AreaConhecimento','top=70,left=100,width=600,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes');""><b><font color=""#0000FF"">Procurar</font></b></u>]"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""80"" MAXLENGTH=""80"" VALUE=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>M</u>eio de divulgação:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_meio"" class=""sti"" SIZE=""50"" MAXLENGTH=""80"" VALUE=""" & w_meio & """></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b><u>D</u>ata de publicação: (dd/mm/aaaa)</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_data"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_chave                = Nothing 
  Set w_sq_area_conhecimento = Nothing 
  Set w_sq_formacao          = Nothing
  Set w_nome                 = Nothing
  Set w_meio                 = Nothing 
  Set w_data                 = Nothing 
  
  Set w_troca                = Nothing 
  Set i                      = Nothing 
  Set w_erro                 = Nothing
End Sub
REM =========================================================================
REM Fim da tela de produção técnica
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de busca da área do conhecimento
REM -------------------------------------------------------------------------
Sub BuscaAreaConhecimento
 
  Dim w_nome, sql
    
  If P1 = "" Then P1 = 1 End If
  
  w_nome    = Request("w_nome")

  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  Validate "w_nome", "Nome", "1", "1", "4", "30", "1", "1"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_nome.focus()';"
  If P1 = 1 Then
     ShowHTML "<B><FONT COLOR=""#000000"">" & RemoveTP(w_TP) & " - Procura Área do Conhecimento</FONT></B>"
  Else
     ShowHTML "<B><FONT COLOR=""#000000"">" & RemoveTP(w_TP) & " - Procura Cargo</FONT></B>"
  End If
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""90%"" border=""0"">"
  AbreForm  "Form", w_dir & w_Pagina & "BuscaAreaConhecimento", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da área de conhecimento desejada.<li>Quando a relação for exibida, selecione a área desejada clicando sobre a caixa ao seu lado.<li>Após informar o nome da área, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""90%"" border=""0"">"
  If P1 = 1 Then
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome da área do conhecimento:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """>"
  Else
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome do cargo:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """>"
  End if
  
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""sti"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
  ShowHTML "            <input class=""sti"" type=""button"" name=""Botao"" value=""Cancelar"" onClick=""window.close(); opener.focus();"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</form>"
  If w_nome > "" Then
     If P1 = 1 Then 
        DB_GetKnowArea RS, null, w_nome, "A"
     Else
        DB_GetKnowArea RS, null, w_nome, "C"
     End If
     RS.Sort = "nome"
     ShowHTML "<tr><td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=6>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
       If P1 = 1 Then
          ShowHTML "          <td><font size=""1""><b>Clique sobre a área do conhecimento desejada</font></td>"
       Else
          ShowHTML "          <td><font size=""1""><b>Clique sobre o cargo desejado</font></td>"
       End If
       ShowHTML "        </tr>"
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td><ul><font size=1>"
       While Not RS.EOF
         ShowHTML "        <li><a class=""SS"" href=""#"" onClick=""opener.Form.w_nm_area.value='" & RS("nome") & " (" & RS("codigo_cnpq") & ")'; opener.Form.w_sq_area_conhecimento.value='" & RS("sq_area_conhecimento") & "'; window.close(); opener.focus();"">" & RS("nome") & " (" & RS("codigo_cnpq") & ")</a>"
         RS.MoveNext
       wend
       ShowHTML "      </ul></tr>"
       ShowHTML "      </center>"
       ShowHTML "    </table>"
       ShowHTML "  </td>"
       ShowHTML "</tr>"
     End If
     DesConectaBD	 
  End If
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_nome                = Nothing
      
End Sub
REM =========================================================================
REM Fim da rotina de busca de área do conhecimento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visualizar

  Dim w_erro, w_logo

  If P2 = 1 Then
     Response.ContentType = "application/msword"
  Else 
     cabecalho
  End If
  
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Curriculum Vitae</TITLE>"
  ShowHTML "</HEAD>" 
  If P2 = 0 Then 
     BodyOpen "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR>"
  If P2 = 0 Then
     DB_GetCustomerData RS, w_cliente
        ShowHTML "  <TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30), null, null, null, "EMBED") & """>"                                              
     DesconectaBD
  End If
  ShowHTML "  <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "Curriculum Vitae"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B>"
  If P2 = 0 Then
     ShowHTML "&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""../images/word.gif"" onClick=""window.open('" & w_pagina & "Visualizar&P2=1&SG=CVVISUAL&w_usuario=" & w_usuario & "','VisualCurriculoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  If P2 = 0 Then
     ShowHTML "<HR>"
  End If
  
  ' Chama a função de visualização dos dados do usuário, na opção "Listagem"
  
  VisualCurriculo w_cliente, w_usuario, "L"
  
  If P2 = 0 Then
     Rodape
  End If
  
  Set w_erro            = Nothing 
  Set w_logo            = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  Dim w_chave_nova
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "CVIDENT"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          ' Recupera os dados do currículo a partir da chave
          DB_GetCV_Pessoa RS, w_cliente, ul.Texts.Item("w_cpf")
          If O = "I" and RS.RecordCount > 0 Then
             ScriptOpen "JavaScript"
             ShowHTML "alert('CPF já cadastrado. Acesse seu currículo usando a opção ""Seu currículo"" no menu principal.');"
             ShowHTML "history.back(1);"
             ScriptClose
             Exit Sub
          End If
          
          ' Se foi feito o upload de um arquivo
          If ul.State = 0 Then
             w_maximo     = 51200
             For Each Field in ul.Files.Items
                If Field.Length > 0 Then
                   ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                   If cDbl(Field.Length) > cDbl(w_maximo) Then 
                      ScriptOpen("JavaScript") 
                      ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & cDbl(w_maximo)/1024 & " KBytes!');" 
                      ShowHTML "  history.back(1);" 
                      ScriptClose 
                      Response.End() 
                      exit sub 
                    End If 
                    ' Se já há um nome para o arquivo, mantém 
                    Set FS = CreateObject("Scripting.FileSystemObject")
                    w_file    = nvl(ul.Texts.Item("w_atual"),replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30)))
                    w_tamanho = Field.Length
                    w_tipo    = Field.ContentType
                    w_nome    = Field.FileName
                    Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
                End If
             Next
             DML_PutCVIdent O, _
                 w_cliente, ul.Texts.Item("w_chave"), ul.Texts.Item("w_nome"), ul.Texts.Item("w_nome_resumido"), ul.Texts.Item("w_nascimento"), _
                 ul.Texts.Item("w_sexo"), ul.Texts.Item("w_sq_estado_civil"), ul.Texts.Item("w_sq_formacao"), ul.Texts.Item("w_cidade"), ul.Texts.Item("w_rg_numero"),_
                 ul.Texts.Item("w_rg_emissor"), ul.Texts.Item("w_rg_emissao"), ul.Texts.Item("w_cpf"), ul.Texts.Item("w_passaporte_numero"),_
                 ul.Texts.Item("w_sq_pais_passaporte"), w_file, w_tamanho, w_tipo, w_nome, w_chave_nova

          Else
             ScriptOpen "JavaScript" 
             ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
             ScriptClose 
          End If
          
          ScriptOpen "JavaScript"
          If Session("p_portal") > "" and O = "I" Then
             ShowHTML "  top.location.href='" & R & "';"
          Else
             ShowHTML "  location.href='" & R & "&w_usuario=" & ul.Texts.Item("w_chave") & "&w_chave=" & ul.Texts.Item("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("UL") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CVIDIOMA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVIdioma O, w_usuario, _
              Request("w_chave"), Request("w_leitura"), Request("w_escrita"), _
              Request("w_compreensao"), Request("w_conversacao")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
     Case "CVEXPPER"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVExperiencia O, w_usuario, _
              Request("w_chave"), Request("w_sq_area_conhecimento"), Request("w_sq_cidade"), Request("w_sq_eo_tipo_posto"), _
              Request("w_sq_tipo_vinculo"), Request("w_empregador"), Request("w_entrada"), Request("w_saida"), _
              Request("w_duracao_mes"), Request("w_duracao_ano"), Request("w_motivo_saida"), null, Request("w_atividades")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
     Case "CVCARGOS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVCargo O, Request("w_sq_cvpescargo"), _
              Request("w_sq_cvpesexp"), Request("w_sq_area_conhecimento"), Request("w_especialidades"), _
              Request("w_inicio"), Request("w_fim")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_cvpesexp=" & Request("w_sq_cvpesexp") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CVESCOLA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVEscola O, w_usuario, _
              Request("w_chave"), Request("w_sq_area_conhecimento"), Request("w_sq_pais"), Request("w_sq_formacao"), _
              Request("w_nome"), Request("w_instituicao"), Request("w_inicio"), Request("w_fim")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CVCURSO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVCurso O, w_usuario, _
              Request("w_chave"), Request("w_sq_area_conhecimento"), Request("w_sq_formacao"), _
              Request("w_nome"), Request("w_instituicao"), Request("w_carga_horaria"), Request("w_conclusao")
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CVTECNICA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCVProducao O, w_usuario, _
              Request("w_chave"), Request("w_sq_area_conhecimento"), Request("w_sq_formacao"), _
              Request("w_nome"), Request("w_meio"), Request("w_data")
          
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

  Set w_file                = Nothing
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
    Case "INICIAL"
       Inicial
    Case "IDENTIFICACAO"
       Identificacao
    Case "IDIOMAS"
       Idiomas
    Case "ESCOLARIDADE"
       Escolaridade
    Case "CURSOS"
       Extensao
    Case "EXPPROF"
       Experiencia
    Case "DESPESA"
       Despesa
    Case "PRODUCAO"
       Producao
    Case "CARGOS"
       Cargos 
    Case "VISUALIZAR"
       Visualizar
    Case "BUSCAAREACONHECIMENTO"
       BuscaAreaConhecimento
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

