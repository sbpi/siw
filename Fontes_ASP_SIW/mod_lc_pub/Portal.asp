<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<!-- #INCLUDE FILE="DB_Portal.asp" -->
<!-- #INCLUDE FILE="DML_Portal.asp" -->
<!-- #INCLUDE FILE="VisualLicitacao.asp" -->
<!-- #INCLUDE FILE="VisualContrato.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Portal.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o portal de licitações
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
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
Dim dbms, sp, RS, RS1, RS_menu, w_ano
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura, w_chave, w_chave_aux
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta, UploadID
Dim w_sq_pessoa, w_gestor_sistema, w_gestor_modulo
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

Dim p_ordena, p_chave
Dim p_restricao, p_endereco, p_unidade, p_fonte
Dim p_modalidade, p_fundamentacao, p_finalidade, p_criterio, p_situacao, p_aber_i, p_aber_f
Dim p_objeto, p_processo, p_empenho, p_publicar, p_pais, p_regiao, p_uf, p_cidade
  
Private Par

AbreSessao

'Carrega as variáveis de sessão
Par          = ucase(Request("Par"))
w_pagina     = "Portal.asp?par="
w_Dir        = "mod_lc_pub/"    
w_dir_volta  = "../"
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = ucase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG) 

DB_GetUserData rs, w_cliente, Session("username")
w_gestor_sistema = RS("gestor_sistema")
DesconectaBD

DB_GetModMaster RS, w_cliente, w_usuario, w_menu
w_gestor_modulo = RS("gestor_modulo")
DesconectaBD

Set ul            = New ASPForm

If Request("UploadID") > "" Then
   UploadID = Request("UploadID")
Else
   UploadID = ul.NewUploadID
End If

' Carrega variáveis locais com os dados dos parâmetros recebidos
If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   Server.ScriptTimeout = 2000
   ul.SizeLimit = &HA00000
   If UploadID > 0 then
      ul.UploadID = UploadID
   End If    
   w_troca          = ul.Texts.Item("w_troca")  
   w_copia          = ul.Texts.Item("w_copia")  
   p_ordena         = uCase(ul.Texts.Item("p_ordena"))  
   p_chave          = uCase(ul.Texts.Item("p_chave"))  
   p_restricao      = uCase(ul.Texts.Item("p_restricao"))  
   p_endereco       = uCase(ul.Texts.Item("p_endereco"))  
   p_unidade        = uCase(ul.Texts.Item("p_unidade"))  
   p_modalidade     = uCase(ul.Texts.Item("p_modalidade"))  
   p_fundamentacao  = uCase(ul.Texts.Item("p_fundamentacao"))  
   p_finalidade     = uCase(ul.Texts.Item("p_finalidade"))  
   p_criterio       = uCase(ul.Texts.Item("p_criterio"))  
   p_situacao       = uCase(ul.Texts.Item("p_situacao"))  
   p_aber_i         = uCase(ul.Texts.Item("p_aber_i"))
   p_aber_f         = uCase(ul.Texts.Item("p_aber_f"))    
   p_objeto         = uCase(ul.Texts.Item("p_objeto"))  
   p_processo       = uCase(ul.Texts.Item("p_processo"))  
   p_empenho        = uCase(ul.Texts.Item("p_empenho"))  
   p_publicar       = uCase(ul.Texts.Item("p_publicar"))  
   p_pais           = uCase(ul.Texts.Item("p_pais"))  
   p_regiao         = uCase(ul.Texts.Item("p_regiao"))  
   p_uf             = uCase(ul.Texts.Item("p_uf"))  
   p_cidade         = uCase(ul.Texts.Item("p_cidade"))  
   p_fonte          = uCase(ul.Texts.Item("p_fonte"))
    
   P1               = ul.Texts.Item("P1")  
   P2               = ul.Texts.Item("P2")  
   P3               = ul.Texts.Item("P3")  
   P4               = ul.Texts.Item("P4")  
   TP               = ul.Texts.Item("TP")  
   R                = uCase(ul.Texts.Item("R"))  
   w_Assinatura     = uCase(ul.Texts.Item("w_Assinatura"))  
Else  
   w_troca         = Request("w_troca")
   w_copia         = Request("w_copia")
   p_ordena        = uCase(Request("p_ordena"))
   p_chave         = uCase(Request("p_chave"))
   p_restricao     = uCase(Request("p_restricao"))
   p_endereco      = uCase(Request("p_endereco"))
   p_unidade       = uCase(Request("p_unidade"))
   p_modalidade    = uCase(Request("p_modalidade"))
   p_fundamentacao = uCase(Request("p_fundamentacao"))
   p_finalidade    = uCase(Request("p_finalidade"))
   p_criterio      = uCase(Request("p_criterio"))
   p_situacao      = uCase(Request("p_situacao"))
   p_aber_i        = uCase(Request("p_aber_i"))
   p_aber_f        = uCase(Request("p_aber_f"))
   p_objeto        = uCase(Request("p_objeto"))
   p_processo      = uCase(Request("p_processo"))
   p_empenho       = uCase(Request("p_empenho"))
   p_publicar      = uCase(Request("p_publicar"))
   p_pais          = uCase(Request("p_pais"))
   p_regiao        = uCase(Request("p_regiao"))
   p_uf            = uCase(Request("p_uf"))
   p_cidade        = uCase(Request("p_cidade"))
   p_fonte         = uCase(Request("p_fonte"))
      
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = cDbl(Nvl(Request("P3"),1))
   P4           = cDbl(Nvl(Request("P4"),conPagesize))
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   w_chave      = uCase(Request("w_chave"))
   w_chave_aux  = uCase(Request("w_chave_aux"))
   
   If SG="LCPTITEM" or SG="LCPTCONT" or SG = "LCPTANEXO" Then
      If O <> "I" and O <> "E" and Request("w_chave_aux") = "" Then 
         O = "L" 
      End If
   ElseIf O = "" Then 
      O = "L" 
   End If
End If

If p_endereco = "" Then
   DB_GetUorgData RS, Session("lotacao")
   p_endereco = RS("sq_pessoa_endereco")
   DesconectaBD
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
  Case "IT" 
     w_TP = TP & " - Itens"
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
DB_GetMenuData RS_menu, w_menu
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If
Main

FechaSessao

Set UploadID        = Nothing
Set w_gestor_sistema= Nothing
Set w_gestor_modulo = Nothing
Set w_dir           = Nothing
Set w_dir_volta     = Nothing
Set w_copia         = Nothing
Set w_filtro        = Nothing
Set w_menu          = Nothing
Set w_usuario       = Nothing
Set w_cliente       = Nothing
Set w_filter        = Nothing
Set w_cor           = Nothing
Set ul              = Nothing
Set File            = Nothing
Set w_sq_pessoa     = Nothing
Set w_troca         = Nothing
Set w_submenu       = Nothing
Set w_reg           = Nothing

Set p_restricao     = Nothing 
Set p_endereco      = Nothing 
Set p_unidade       = Nothing
Set p_modalidade    = Nothing 
Set p_fundamentacao = Nothing 
Set p_finalidade    = Nothing 
Set p_criterio      = Nothing 
Set p_situacao      = Nothing 
Set p_aber_i        = Nothing 
Set p_aber_f        = Nothing
Set p_objeto        = Nothing 
Set p_processo      = Nothing 
Set p_empenho       = Nothing 
Set p_publicar      = Nothing 
Set p_pais          = Nothing 
Set p_regiao        = Nothing 
Set p_uf            = Nothing 
Set p_cidade        = Nothing
Set p_fonte         = Nothing
Set p_ordena        = Nothing

Set RS              = Nothing
Set RS1             = Nothing
Set RS_menu         = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_Classe        = Nothing
Set w_Cont          = Nothing
Set w_pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de visualização resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_titulo, w_total, w_parcial
  
  If O = "L" Then
     DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, p_chave, p_restricao, p_unidade, p_fonte, _
        p_modalidade, p_finalidade, p_criterio, p_situacao, p_aber_i, p_aber_f, _
        p_objeto, p_processo, p_empenho, p_publicar, p_pais, p_regiao, p_uf, p_cidade
        
     If p_ordena > "" Then RS.sort = p_ordena & ", abertura desc" Else RS.sort = "abertura desc" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Listagem de Licitações</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número da licitação", "", "", "1", "18", "", "0123456789"
        Validate "p_processo", "Número do processo", "", "", "2", "30", "1", ""
        Validate "p_objeto", "Objeto", "", "", "2", "90", "1", "1"
        Validate "p_empenho", "Empenho", "", "", "2", "30", "1", "1"
        Validate "p_aber_i", "Abertura de", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_aber_f", "Abertura até", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_aber_i.value != '' && theForm.p_aber_f.value == '') || (theForm.p_aber_i.value == '' && theForm.p_aber_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de recebimento ou nenhuma delas!');"
        ShowHTML "     theForm.p_aber_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_aber_i", "Recebimento inicial", "<=", "p_aber_f", "Recebimento final"
     End If
     Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_smtp_server.focus();'"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se for cadastramento
        BodyOpen "onLoad='document.Form.p_chave.focus()';"
     Else
        BodyOpen "onLoad='document.Form.p_ordena.focus()';"
     End if
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_submenu > "" Then
       DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
       ShowHTML "<tr><td><font size=""1"">"
       ShowHTML "    <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
    Else
       ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    End If

    If w_copia > "" Then ' Se for cópia
       If MontaFiltro("GET") > "" Then
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
       Else
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
       End If
    Else
       If MontaFiltro("GET") > "" Then
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
       Else
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
       End If
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Edital</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Objeto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Situação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "VisualLic&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_portal_lic") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações desta licitação."">" & RS("sg_modalidade") & "-" & RS("edital") & "&nbsp;</a>"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("sg_unid") & "</td>"
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("objeto"),"-") & "</td>"
        Else
           If Len(Nvl(RS("objeto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("objeto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("objeto"),"-") End If
           ShowHTML "        <td title=""" & replace(replace(replace(RS("objeto"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
        End If
        ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_situacao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If w_copia > "" Then ' Se for listagem para cópia
           ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & SG & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_portal_lic") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
        Else
           If w_submenu > "" Then
              ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_portal_lic") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("sg_modalidade") & "-" & RS("edital") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da licitação"" TARGET=""menu"">Alterar</a>&nbsp;"
           Else
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_portal_lic") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da licitação"">Alterar</A>&nbsp"
           End If
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_portal_lic") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão da licitação."">Excluir</A>&nbsp"
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
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("CP",O) > 0 Then
    If O = "C" Then ' Se for cópia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar a licitação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If

    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Número da <U>l</U>icitação:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>P</U>rocesso:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_processo"" size=""30"" maxlength=""30"" value=""" & p_processo & """></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>bjeto:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_objeto"" size=""45"" maxlength=""90"" value=""" & p_objeto & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>E</U>mpenho:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_empenho"" size=""30"" maxlength=""30"" value=""" & p_empenho & """></td>"

    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Data de aber<u>t</u>ura entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_aber_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_aber_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_aber_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_aber_f & """ onKeyDown=""FormataData(this,event);""></td>"
    SelecaoLcFonte "<u>F</u>onte de recursos:", "F", null, p_fonte, null, "p_fonte", null, null
    
    ShowHTML "      <tr>"
    SelecaoLcModalidade "<u>M</u>odalidade da licitação:", "M", null, p_modalidade, null, "p_modalidade", null, null

    ShowHTML "      <tr>"
    SelecaoLcFinalidade "<u>F</u>inalidade da licitação:", "P", null, p_finalidade, null, "p_finalidade", null, null
    SelecaoLcCriterio "<u>C</u>ritério de julgamento:", "C", null, p_criterio, null, "p_criterio", null, null

    ShowHTML "      <tr>"
    SelecaoLcSituacao "<u>S</u>ituação da licitação:", "S", null, p_situacao, null, "p_situacao", null, null
    If w_gestor_sistema = "S" or w_gestor_modulo = "S" Then
       SelecaoUnidade "<u>U</u>nidade licitante:", "U", null, p_unidade, null, "p_unidade", "LICITACAO", null
    Else
       SelecaoUnidade "<u>U</u>nidade licitante:", "U", null, p_unidade, p_endereco, "p_unidade", "LICITACAOEND", null
    End If

    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
    SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    ShowHTML "      <tr>"
    SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null

    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente licitações marcadas para publicacao?</b><br>"
    If p_publicar = "" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_publicar"" value=""S""> Sim  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value=""N""> Não  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value="""" checked> Tanto faz"
    ElseIf p_publicar = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_publicar"" value=""S"" checked> Sim  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value=""N""> Não  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_publicar"" value=""S""> Sim  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value=""N"" checked> Não  <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_publicar"" value=""""> Tanto faz"
    End If

    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NM_SITUACAO" Then
       ShowHTML "          <option value="""">Data de abertura<option value=""sg_modalidade"">Modalidade da licitação<option value=""nm_situacao"" SELECTED>Situação<option value=""nm_unidade"">Unidade licitante"
    ElseIf p_Ordena="NM_UNIDADE" Then
       ShowHTML "          <option value="""">Data de abertura<option value=""sg_modalidade"">Modalidade da licitação<option value=""nm_situacao"">Situação<option value=""nm_unidade"" SELECTED>Unidade licitante"
    ElseIf p_Ordena="SG_MODALIDADE" Then
       ShowHTML "          <option value="""">Data de abertura<option value=""sg_modalidade"" SELECTED>Modalidade da licitação<option value=""nm_situacao"">Situação<option value=""nm_unidade"">Unidade licitante"
    Else
       ShowHTML "          <option value="""" SELECTED>Data de abertura<option value=""sg_modalidade"">Modalidade da licitação<option value=""nm_situacao"">Situação<option value=""nm_unidade"">Unidade licitante"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for cópia
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar cópia"">"
    Else
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & SG & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"" name=""Botao"" value=""Incluir"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    End If
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

  Set w_titulo = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de ações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral

  Dim w_sq_unidade, w_sq_lcmodalidade, w_fundamentacao, w_sq_lcfinalidade, w_sq_lcjulgamento
  Dim w_sq_lcsituacao, w_sq_lcfonte_recurso, w_abertura, w_objeto, w_edital, w_processo, w_empenho
  Dim w_publicar, w_observacao, w_chave, w_chave_aux
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
      w_sq_unidade          = Request("w_sq_unidade")
      w_sq_lcmodalidade     = Request("w_sq_lcmodalidade")
      DB_GetLcModalidade RS, w_sq_lcmodalidade, w_cliente
      w_fundamentacao       = RS("fundamentacao")  
      DesconectaBD
      w_sq_lcfinalidade     = Request("w_sq_lcfinalidade") 
      w_sq_lcjulgamento     = Request("w_sq_lcjulgamento") 
      w_sq_lcsituacao       = Request("w_sq_lcsituacao") 
      w_sq_lcfonte_recurso  = Request("w_sq_lcfonte_recurso") 
      w_abertura            = Request("w_abertura") 
      w_objeto              = Request("w_objeto") 
      w_edital              = Request("w_edital") 
      w_processo            = Request("w_processo") 
      w_empenho             = Request("w_empenho")
      w_publicar            = Request("w_publicar")
      w_observacao          = Request("w_observacao") 
      w_chave               = Request("w_chave") 
      w_chave_aux           = Request("w_chave_aux")
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da licitação
        If w_copia > "" Then
           DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_copia, null, null, null, _
              null, null, null, null, null, null, null, null, null, null, null, null, null, null
        Else
           DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_chave, null, null, null, _
              null, null, null, null, null, null, null, null, null, null, null, null, null, null
        End If
        If RS.RecordCount > 0 Then 
           w_sq_unidade          = RS("sq_unidade") 
           w_sq_lcmodalidade     = RS("sq_lcmodalidade")
           w_fundamentacao       = RS("fundamentacao")  
           w_sq_lcfinalidade     = RS("sq_lcfinalidade") 
           w_sq_lcjulgamento     = RS("sq_lcjulgamento") 
           w_sq_lcsituacao       = RS("sq_lcsituacao") 
           w_sq_lcfonte_recurso  = RS("sq_lcfonte_recurso") 
           w_abertura            = FormataDataEdicao(RS("abertura"))
           w_objeto              = RS("objeto") 
           w_edital              = RS("edital") 
           w_processo            = RS("processo") 
           w_empenho             = RS("empenho")
           w_publicar            = RS("publicar")
           w_observacao          = RS("observacao") 
           w_chave               = RS("sq_portal_lic")
           w_chave_aux           = null
           DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_edital", "Edital", "1", 1, 6, 15, "", "0123456789/"
     Validate "w_objeto", "Objeto", "1", 1, 5, 2000, "1", "1"
     Validate "w_processo", "Número do processo", "1", "", "2", "30", "1", ""
     Validate "w_empenho", "Empenho", "", "", "2", "30", "1", "1"
     Validate "w_abertura", "Data de abertura", "DATA", "1", "10", "10", "", "0123456789/"
     Validate "w_observacao", "Observação", "1", "", 2, 1000, "1", "1"
     Validate "w_sq_lcmodalidade", "Modalidade da licitação", "SELECT", "1", "1", "18", "", "0123456789"
     Validate "w_fundamentacao", "Fundamentação", "1", "", "5", "250", "1", "1"
     Validate "w_sq_lcfonte_recurso", "Fonte de recursos", "SELECT", "1", "1", "18", "", "0123456789"
     Validate "w_sq_lcfinalidade", "Finalidade da licitação", "SELECT", "1", "1", "18", "", "0123456789"
     Validate "w_sq_lcjulgamento", "Critério de julgamento", "SELECT", "1", "1", "18", "", "0123456789"
     Validate "w_sq_lcsituacao", "Situação da licitação", "SELECT", "1", "1", "18", "", "0123456789"
     Validate "w_sq_unidade", "Unidade licitante", "SELECT", "1", "1", "18", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_edital.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""1""><b>E<u>d</u>ital:</b><br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_edital"" size=""15"" maxlength=""15"" value=""" & w_edital & """ title=""Informe o número do edital no formato 999/AAAA. NÃO COLOQUE A SIGLA DA MODALIDADE DA LICITAÇÃO. O sistema fará isto automaticamente. Exemplos: 1/2004, 302/2003 etc.""></td></tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>O</u>bjeto:</b><br><textarea ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_objeto"" ROWS=""5"" COLS=""75"" title=""Informe o objeto desta licitação, conforme o edital."">" & w_objeto & "</textarea></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><u>P</u>rocesso de licitação:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_processo"" size=""30"" maxlength=""30"" value=""" & w_processo & """ title=""Informe o número do processo ao qual a licitação está vinculada.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>E</u>mpenho:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_empenho"" size=""30"" maxlength=""30"" value=""" & w_empenho & """ title=""Informe o número do empenho que dá suporte financeiro à licitação.""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>A</u>bertura:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_abertura"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_abertura & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data prevista para abertura das propostas desta licitação.""></td>"
    ShowHTML "        <tr><td colspan=3><font size=""1""><b>O<u>b</u>servações:</b><br><textarea ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_observacao"" ROWS=""5"" COLS=""75"" title=""Insira, se necessário, observações relevantes sobre esta licitação, para publicação no portal."">" & w_observacao & "</textarea></td></tr>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoLcModalidade "<u>M</u>odalidade da licitação:", "M", null, w_sq_lcmodalidade, null, "w_sq_lcmodalidade", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_fundamentacao'; document.Form.submit();"""
    ShowHTML "        <tr><td colspan=3><font size=""1""><b><u>F</u>undamentação:</b><br><textarea  " & w_Disabled & " accesskey=""F"" name=""w_fundamentacao"" class=""STI"" ROWS=""3"" COLS=""75"">" & w_fundamentacao & "</textarea></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoLcFonte "<u>F</u>onte de recursos:", "F", null, w_sq_lcfonte_recurso, null, "w_sq_lcfonte_recurso", null, null
    ShowHTML "          </table>"

    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoLcFinalidade "<u>F</u>inalidade da licitação:", "P", null, w_sq_lcfinalidade, null, "w_sq_lcfinalidade", null, null
    SelecaoLcCriterio "<u>C</u>ritério de julgamento:", "C", null, w_sq_lcjulgamento, null, "w_sq_lcjulgamento", null, null

    ShowHTML "        <tr valign=""top"">"
    SelecaoLcSituacao "<u>S</u>ituação da licitação:", "S", null, w_sq_lcsituacao, null, "w_sq_lcsituacao", null, null
    If w_gestor_sistema = "S" or w_gestor_modulo = "S" Then
       SelecaoUnidade "<u>U</u>nidade licitante:", "U", "Selecione a unidade responsável pela contratação", w_sq_unidade, null, "w_sq_unidade", "LICITACAO", null
    Else
       SelecaoUnidade "<u>U</u>nidade licitante:", "U", "Selecione a unidade responsável pela contratação", w_sq_unidade, p_endereco, "w_sq_unidade", "LICITACAOEND", null
    End If
    ShowHTML "          </table>"

    ShowHTML "        <tr>"
    MontaRadioSN "<b>Publica esta licitação no portal?</b>", w_publicar, "w_publicar"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    If O = "I" Then
       DB_GetMenuData RS, w_menu
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_copia=" & w_copia & "&O=L&SG=" & RS("sigla") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    End If
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

  Set w_sq_unidade          = Nothing 
  Set w_sq_lcmodalidade     = Nothing
  Set w_fundamentacao       = Nothing 
  Set w_sq_lcfinalidade     = Nothing 
  Set w_sq_lcjulgamento     = Nothing 
  Set w_sq_lcsituacao       = Nothing 
  Set w_sq_lcfonte_recurso  = Nothing 
  Set w_abertura            = Nothing 
  Set w_objeto              = Nothing 
  Set w_edital              = Nothing 
  Set w_processo            = Nothing 
  Set w_empenho             = Nothing
  Set w_publicar            = Nothing
  Set w_observacao          = Nothing 
  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_readonly            = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de dados gerais
REM -------------------------------------------------------------------------

REM ------------------------------------------------------------------------- 
REM Rotina de anexos 
REM ------------------------------------------------------------------------- 
Sub Anexos
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_descricao, w_caminho 
    
  Dim w_troca, i, w_erro 
    
  w_Chave           = Request("w_Chave") 
  w_chave_aux       = Request("w_chave_aux") 
  w_troca           = Request("w_troca") 
    
  If w_troca > "" Then ' Se for recarga da página 
     w_nome                = Request("w_nome") 
     w_descricao           = Request("w_descricao") 
     w_caminho             = Request("w_caminho") 
  ElseIf O = "L" Then 
     ' Recupera todos os registros para a listagem 
     DB_GetLcAnexo RS, w_chave, null, w_cliente 
     RS.Sort = "nome" 
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then 
     ' Recupera os dados do endereço informado 
     DB_GetLcAnexo RS, w_chave, w_chave_aux, w_cliente 
     w_nome                 = RS("nome") 
     w_descricao            = RS("descricao") 
     w_caminho              = RS("chave_aux") 
     DesconectaBD 
  End If 
    
  Cabecalho 
  ShowHTML "<HEAD>" 
  If InStr("IAEP",O) > 0 Then 
     ScriptOpen "JavaScript" 
     ProgressBar w_dir_volta, UploadID     
     ValidateOpen "Validacao" 
     If InStr("IA",O) > 0 Then 
        Validate "w_nome", "Título", "1", "1", "1", "255", "1", "1" 
        Validate "w_descricao", "Descrição", "1", "1", "1", "1000", "1", "1" 
        If O = "I" Then 
           Validate "w_caminho", "Arquivo", "", "1", "5", "255", "1", "1" 
        End If 
     End If 
     ShowHTML "  theForm.Botao[0].disabled=true;" 
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "if (theForm.w_caminho.value != '') {return ProgressBar();}"      
     ValidateClose 
     ScriptClose 
  End If 
  ShowHTML "</HEAD>" 
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then 
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';" 
  ElseIf O = "I" Then 
     BodyOpen "onLoad='document.Form.w_nome.focus()';" 
  ElseIf O = "A" Then 
     BodyOpen "onLoad='document.Form.w_descricao.focus()';" 
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
    ShowHTML "          <td><font size=""1""><b>Título</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Descrição</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>" 
    ShowHTML "          <td><font size=""1""><b>KB</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>" 
    ShowHTML "        </tr>" 
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem 
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>" 
    Else 
      ' Lista os registros selecionados para listagem 
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If 
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">" 
        ShowHTML "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>" 
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>" 
        ShowHTML "        <td><font size=""1"">" & RS("tipo") & "</td>" 
        ShowHTML "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>" 
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp" 
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
    ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG="&SG&"&O="&O&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">" 
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>" 
    
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_atual"" value=""" & w_caminho & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">" 
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" 
    ShowHTML "    <table width=""97%"" border=""0"">" 
    
    If O = "I" or O = "A" Then 
       DB_GetCustomerData RS, w_cliente 
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>ítulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""OBRIGATÓRIO. Informe um título para o arquivo.""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=65 title=""OBRIGATÓRIO. Descreva a finalidade do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor."">" 
    If w_caminho > "" Then 
       ShowHTML "              <b>" & LinkArquivo("SS", w_cliente, w_caminho, "_blank", "Clique para exibir o arquivo atual.", "Exibir", null) & "</b>" 
    End If 
    ShowHTML "      <tr><td align=""center""><hr>" 
    If O = "E" Then 
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"" onClick=""return confirm('Confirma a exclusão do registro?');"">" 
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
    
  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_nome            = Nothing 
  Set w_descricao       = Nothing 
  Set w_caminho         = Nothing 
    
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing 
End Sub 
REM ========================================================================= 
REM Fim da tela de anexos 
REM ------------------------------------------------------------------------- 

REM =========================================================================
REM Rotina de itens da licitacao
REM -------------------------------------------------------------------------
Sub ItemLicitacao
  Dim w_ordem, w_nome, w_descricao, w_sq_unidade_fornec, w_quantidade, w_cancelado, w_situacao
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_ordem               = Request("w_ordem")
     w_nome                = Request("w_nome")
     w_w_sq_unidade_fornec = Request("w_sq_unidade_fornec")
     w_descricao           = Request("w_descricao")
     w_quantidade          = Request("w_quantidade")
     w_cancelado           = Request("w_cancelado")
     w_situacao            = Request("w_situacao")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetLcPortalLicItem RS, w_cliente, w_chave, null, null, null
     RS.Sort = "ordem, nome"
  ElseIf InStr("AEV",O) > 0 Then
     ' Recupera o registro informado
     DB_GetLcPortalLicItem RS, w_cliente, w_chave, w_chave_aux, null, null
     w_ordem               = RS("ordem")
     w_nome                = RS("nome")
     w_descricao           = RS("descricao")
     w_sq_unidade_fornec   = RS("sq_unidade_fornec")
     w_quantidade          = FormatNumber(RS("quantidade"),1)
     w_cancelado           = RS("cancelado")
     w_situacao            = RS("situacao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_ordem", "Ordem", "", "1", "1", "4", "", "0123456789"
        Validate "w_nome", "Nome do item", "", "1", "2", "60", "1", "1"
        Validate "w_descricao", "descricao desempenhado", "", "", "1", "2000", "1", "1"
        Validate "w_quantidade", "Quantidade", "VALOR", "1", "3", "18", "", "0123456789,."
        Validate "w_sq_unidade_fornec", "Unidade", "SELECT", "1", "1", "18", "", "0123456789"
        Validate "w_situacao", "Observações", "", "", "1", "500", "1", "1"
     Else
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_ordem.focus()';"
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
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Ordem</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cancelado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ordem") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("quantidade"),1) & "&nbsp;&nbsp;</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_cancelado") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_portal_lic_item") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_portal_lic_item") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><u>O</u>rdem:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ordem"" size=""4"" maxlength=""4"" value=""" & w_ordem & """ title=""Informe o número de ordem deste item, conforme edital.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>N</u>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""40"" maxlength=""60"" value=""" & w_nome & """ title=""Informe o nome do material ou serviço, conforme edital.""></td>"
    ShowHTML "        </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva o item, se desejar."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "        <tr valign=""top""><td valign=""top"" colspan=""2"">"
    ShowHTML "      <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>Q</u>uantidade:</b><br><input " & w_Disabled & " accesskey=""Q"" type=""text"" name=""w_quantidade"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_quantidade & """ onKeyDown=""FormataValor(this,17,1,event);"" title=""Informe a quantidade a ser licitada, com uma casa decimal. Se necessário, informe 0,0.""></td>"
    SelecaoUnidadeFornec "<u>U</u>nidade de fornecimento:", "U", "Selecione a unidade de fornecimento do item", w_sq_unidade_fornec, null, "w_sq_unidade_fornec", null, null
    ShowHTML "          </table>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioNS "<b>Item cancelado?</b>", w_cancelado, "w_cancelado"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>O<u>b</u>servações:</b><br><textarea " & w_Disabled & " accesskey=""B"" name=""w_situacao"" class=""STI"" ROWS=5 cols=75 title=""Informe observações sobre este item, que julgar relevante para publicação na Internet."">" & w_situacao & "</TEXTAREA></td>"
    If O = "E" Then
       ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
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
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_nome            = Nothing 
  Set w_descricao       = Nothing
  Set w_quantidade      = Nothing  
  Set w_cancelado       = Nothing  
  Set w_situacao        = Nothing  
  
End Sub
REM =========================================================================
REM Fim da tela de itens da licitacao
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de contratos
REM -------------------------------------------------------------------------
Sub Contrato

  Dim w_sq_unidade, w_sq_contrato_pai, w_sq_pessoa, w_vigencia_inicio, w_vigencia_fim
  Dim w_publicacao, w_valor, w_assinatura_form, w_objeto, w_numero, w_processo, w_empenho
  Dim w_publicar, w_observacao, w_chave, w_chave_aux
  Dim w_nome, w_sexo, w_nome_resumido, w_pessoa_juridica, w_cpf, w_cnpj, w_titulo
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
      w_sq_unidade          = Request("w_sq_unidade") 
      w_sq_contrato_pai     = Request("w_sq_contrato_pai") 
      w_sq_pessoa           = Request("w_sq_pessoa") 
      w_vigencia_inicio     = Request("w_vigencia_inicio") 
      w_vigencia_fim        = Request("w_vigencia_fim") 
      w_publicacao          = Request("w_publicacao") 
      w_valor               = Request("w_valor") 
      w_assinatura_form     = Request("w_assinatura_fom") 
      w_objeto              = Request("w_objeto") 
      w_numero              = Request("w_numero") 
      w_processo            = Request("w_processo") 
      w_empenho             = Request("w_empenho")
      w_publicar            = Request("w_publicar")
      w_observacao          = Request("w_observacao") 
      w_chave               = Request("w_chave") 
      w_chave_aux           = Request("w_chave_aux")
      w_pessoa_juridica     = Request("w_pessoa_juridica")
      w_nome                = Request("w_nome")
      w_nome_resumido       = Request("w_nome_resumido")
      w_cpf                 = Request("w_cpf")
      w_cnpj                = Request("w_cnpj")
  ElseIf O = "L" Then
      ' Recupera os contratos da licitação
      DB_GetLcPortalCont RS, w_cliente, w_chave, null, null
  ElseIf O = "I" Then
      ' Recupera os dados do contrato
      DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, p_chave, p_restricao, p_unidade, p_fonte, _
         p_modalidade, p_finalidade, p_criterio, p_situacao, p_aber_i, p_aber_f, _
         p_objeto, p_processo, p_empenho, p_publicar, p_pais, p_regiao, p_uf, p_cidade

      If RS.RecordCount > 0 Then 
         w_sq_unidade          = RS("sq_unidade") 
      End If
  ElseIf InStr("AEV",O) > 0 Then
      ' Recupera os dados do contrato
      DB_GetLcPortalCont RS, w_cliente, w_chave, w_chave_aux, null

      If RS.RecordCount > 0 Then 
         w_sq_unidade          = RS("sq_unidade") 
         w_sq_pessoa           = RS("sq_pessoa") 
         w_sq_contrato_pai     = RS("sq_contrato_pai") 
         w_vigencia_inicio     = FormataDataEdicao(RS("vigencia_inicio"))
         w_vigencia_fim        = FormataDataEdicao(RS("vigencia_fim"))
         w_assinatura_form     = FormataDataEdicao(RS("assinatura"))
         w_publicacao          = FormataDataEdicao(RS("publicacao"))
         w_valor               = FormatNumber(RS("valor"),2)
         w_numero              = RS("numero") 
         w_processo            = RS("processo") 
         w_objeto              = RS("objeto") 
         w_publicar            = RS("publicar")
         w_empenho             = RS("empenho")
         w_observacao          = RS("observacao") 
         w_pessoa_juridica     = RS("pessoa_juridica")
         w_cpf                 = RS("cpf")
         w_cnpj                = RS("cnpj")
         w_nome                = RS("nome") 
         w_nome_resumido       = RS("nome_resumido")
         w_sexo                = RS("sexo") 
         w_chave               = RS("chave")
         DesconectaBD
      End If
  End If  
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     Modulo
     CheckBranco
     FormataCNPJ
     FormataCPF
     FormataValor
     FormataData
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
        Validate "w_numero", "Número do contrato", "1", 1, 6, 15, "", "0123456789/.-NE"
        Validate "w_objeto", "Objeto", "1", 1, 5, 2000, "1", "1"
        Validate "w_processo", "Número do processo", "1", "", "2", "30", "1", ""
        Validate "w_empenho", "Empenho", "", "", "2", "30", "1", "1"
        Validate "w_assinatura_form", "Data de assinatura", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_vigencia_inicio", "Data de início da vigência", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_vigencia_fim", "Data de término da vigência", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_vigencia_inicio", "Data de início da vigência", "<=", "w_vigencia_fim", "Data de término da vigência"
        Validate "w_publicacao", "Data de publicação no D.O.U.", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_assinatura_form", "Data de assinatura", "<=", "w_publicacao", "Data de publicação no D.O.U."
        Validate "w_valor", "Valor do contrato", "VALOR", "1", "4", "18", "", "0123456789,."
        Validate "w_cnpj", "CNPJ", "CNPJ", "", "18", "18", "", "0123456789/-."
        Validate "w_cpf", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "w_nome", "Nome", "", "1", "2", "60", "1", "1"
        Validate "w_nome_resumido", "Nome resumido", "", "1", "2", "15", "1", "1"
        ShowHTML "  if (theForm.w_pessoa_juridica[0].checked) {"
        ShowHTML "     if (theForm.w_cnpj.value=='') {"
        ShowHTML "        alert('Para pessoa jurídica, informe o CNPJ!');"
        ShowHTML "        theForm.w_cnpj.focus();"
        ShowHTML "        return false;"
        ShowHTML "     } else { "
        ShowHTML "        theForm.w_cpf.value='';"
        ShowHTML "        theForm.w_sexo.selectedIndex=0;"
        ShowHTML "     }"
        ShowHTML "  } else {"
        ShowHTML "     if (theForm.w_cpf.value=='' || theForm.w_sexo.selectedIndex==0) {"
        ShowHTML "        alert('Para pessoa física, informe o CPF e o sexo!');"
        ShowHTML "        theForm.w_cpf.focus();"
        ShowHTML "        return false;"
        ShowHTML "     } else { theForm.w_cnpj.value=''; }"
        ShowHTML "  }"
        Validate "w_sq_unidade", "Unidade licitante", "SELECT", "1", "1", "18", "", "0123456789"
        Validate "w_observacao", "Observação", "1", "", 2, 1000, "1", "1"
     Else
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_numero.focus()';"
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
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Número</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Objeto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Vigência</font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "VisualCont&R=" & w_pagina & par & "&O=L&w_chave_aux=" & RS("sq_portal_contrato") & "&w_chave=" & RS("chave") &   "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste contrato."">" & RS("numero") & "&nbsp;</a>"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("sg_unid") & "</td>"
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("objeto"),"-") & "</td>"
        Else
           If Len(Nvl(RS("objeto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("objeto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("objeto"),"-") End If
           ShowHTML "        <td title=""" & replace(replace(replace(RS("objeto"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
        End If
        ShowHTML "        <td nowrap align=""center""><font size=""1"">" & FormatDateTime(RS("vigencia_inicio"),2)&"-"&FormatDateTime(RS("vigencia_fim"),2) & "</td>"
        ShowHTML "        <td nowrap align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&w_chave_aux=" & RS("sq_portal_contrato") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & RS("chave") & "&w_chave_aux=" & RS("sq_portal_contrato") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "ItemContrato" & "&R=" & w_pagina & par & "&O=IT&w_chave=" & RS("chave") & "&w_chave_aux=" & RS("sq_portal_contrato") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Itens</A>&nbsp"
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

    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""1""><b>N<u>ú</u>mero do contrato/empenho:</b><br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_numero"" size=""15"" maxlength=""15"" value=""" & w_numero & """ title=""Informe o número do contrato, preferencialmente. Se a modalidade dispensar o contrato, informe o número do empenho, iniciando por NE-""></td></tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>O</u>bjeto:</b><br><textarea ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_objeto"" ROWS=""5"" COLS=""75"" title=""Informe o objeto deste contrato."">" & w_objeto & "</textarea></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><u>P</u>rocesso de contratação:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_processo"" size=""30"" maxlength=""30"" value=""" & w_processo & """ title=""Informe o número do processo ao qual o contrato está vinculado.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>E</u>mpenho:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_empenho"" size=""30"" maxlength=""30"" value=""" & w_empenho & """ title=""Informe o número do empenho que dá suporte financeiro ao contrato.""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Data de <u>a</u>ssinatura:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_assinatura_form"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_assinatura_form & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de assinatura deste contrato.""></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>I</u>nício vigência:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_vigencia_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_vigencia_inicio & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de início da vigência deste contrato.""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>F</u>im vigência:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_vigencia_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_vigencia_fim & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da vigência deste contrato.""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>P</u>ublicação D.O.U.:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_publicacao"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_publicacao & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de publicação deste contrato no Diário Oficial.""></td>"
    ShowHTML "        <tr valign=""top""><td><font size=""1""><b><u>V</u>alor do contrato:</b><br><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor deste contrato, ou 0,00 se não houver.""></td>"
    ShowHTML "        </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioSN "<b>Contratado é pessoa jurídica?</b>", w_pessoa_juridica, "w_pessoa_juridica"
    ShowHTML "        <tr><td><font size=1><b><u>C</u>NPJ:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " TYPE=""text"" Class=""STI"" NAME=""w_cnpj"" VALUE=""" & w_cnpj & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this, event);"">"
    ShowHTML "            <td><font size=1><b>C<u>P</u>F:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " TYPE=""text"" Class=""STI"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>N</u>ome contratado:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Nome <u>r</u>esumido:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_nome_resumido"" class=""STI"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoSexo "<u>S</u>exo (apenas para pessoa física):", "S", null, w_sexo, null, "w_sexo", null, null
    ShowHTML "        </table>"
    'ShowHTML "      <tr valign=""top"">"
    'SelecaoPessoa "<u>C</u>ontratado:", "C", "Selecione o contratado.", w_sq_pessoa, null, "w_sq_pessoa", "TODOS"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_cliente & """>"
    ShowHTML "      <tr valign=""top"">"
    If w_gestor_sistema = "S" or w_gestor_modulo = "S" Then
       SelecaoUnidade "<u>U</u>nidade contratante:", "U", "Selecione a unidade responsável pela contratação", w_sq_unidade, null, "w_sq_unidade", "LICITACAO", null
    Else
       SelecaoUnidade "<u>U</u>nidade contratante:", "U", "Selecione a unidade responsável pela contratação", w_sq_unidade, p_endereco, "w_sq_unidade", "LICITACAOEND", null
    End If
    ShowHTML "      <tr><td><font size=""1""><b>O<u>b</u>servações:</b><br><textarea ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_observacao"" ROWS=""5"" COLS=""75"" title=""Insira, se necessário, observações relevantes sobre este contrato, para publicação no portal."">" & w_observacao & "</textarea></td></tr>"
    ShowHTML "        <tr>"
    MontaRadioSN "<b>Publica este contrato no portal?</b>", w_publicar, "w_publicar"
    If O = "E" Then
       ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
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
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_titulo          = Nothing 
  Set w_sq_unidade      = Nothing 
  Set w_sq_contrato_pai = Nothing 
  Set w_sq_pessoa       = Nothing 
  Set w_vigencia_inicio = Nothing 
  Set w_vigencia_fim    = Nothing 
  Set w_publicacao      = Nothing 
  Set w_valor           = Nothing 
  Set w_assinatura_form = Nothing 
  Set w_objeto          = Nothing 
  Set w_numero          = Nothing 
  Set w_processo        = Nothing 
  Set w_empenho         = Nothing
  Set w_publicar        = Nothing 
  Set w_observacao      = Nothing 
  Set w_pessoa_juridica = Nothing
  Set w_cnpj            = Nothing
  Set w_cpf             = Nothing
  Set w_nome            = Nothing
  Set w_nome_resumido   = Nothing
  Set w_sexo            = Nothing
  Set w_chave           = Nothing 
  Set w_chave_aux       = Nothing
  
End Sub
REM =========================================================================
REM Fim da tela de contratos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de contratos
REM -------------------------------------------------------------------------
Sub Contrato1

  Dim w_sq_unidade, w_sq_contrato_pai, w_sq_pessoa, w_vigencia_inicio, w_vigencia_fim
  Dim w_publicacao, w_valor, w_assinatura, w_objeto, w_numero, w_processo, w_empenho
  Dim w_cnpj, w_cpf, w_nome, w_nome_resumido, w_sexo
  Dim w_publicar, w_observacao, w_chave, w_chave_aux
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_cpf             = Request("w_cpf")
  w_cnpj            = Request("w_cnpj")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
      w_sq_unidade          = Request("w_sq_unidade") 
      w_sq_contrato_pai     = Request("w_sq_contrato_pai") 
      w_sq_pessoa           = Request("w_sq_pessoa") 
      w_vigencia_inicio     = Request("w_vigencia_inicio") 
      w_vigencia_fim        = Request("w_vigencia_fim") 
      w_publicacao          = Request("w_publicacao") 
      w_valor               = Request("w_valor") 
      w_assinatura          = Request("w_assinatura") 
      w_objeto              = Request("w_objeto") 
      w_numero              = Request("w_numero") 
      w_processo            = Request("w_processo") 
      w_empenho             = Request("w_empenho")
      w_publicar            = Request("w_publicar")
      w_observacao          = Request("w_observacao") 
      w_chave               = Request("w_chave") 
      w_cpf                 = Request("w_cpf") 
      w_cnpj                = Request("w_cnpj") 
      w_nome                = Request("w_nome") 
      w_nome_resumido       = Request("w_nome_resumido") 
      w_sexo                = Request("w_sexo") 
      w_chave_aux           = Request("w_chave_aux")
  ElseIf O = "L" Then
      ' Recupera os contratos da licitação
      DB_GetLcPortalCont RS, w_cliente, w_chave, null, null
  ElseIf InStr("IAEV",O) > 0 Then
      If w_sq_pessoa = "" and (Nvl(w_cpf,"") > "" or Nvl(w_cnpj,"") > "") Then
         ' Recupera os dados da pessoa
         DB_GetPersonData RS, w_cliente, w_sq_pessoa, Request("w_cpf"), Request("w_cnpj")
         If RS.RecordCount > 0 Then 
            w_sq_pessoa          = RS("sq_pessoa")
            w_cpf                = RS("cpf")
            w_cnpj               = RS("cnpj")
            w_nome               = RS("Nome")
            w_sexo               = RS("sexo")
            w_nome_resumido      = RS("Nome_Resumido")
         End If
         DesconectaBD
      End If

      If InStr("AEV",O) > 0 Then
         ' Recupera os dados do contrato
         DB_GetLcPortalCont RS, w_cliente, w_chave, w_chave_aux, null

         If RS.RecordCount > 0 Then 
            w_sq_unidade          = RS("sq_unidade") 
            w_sq_pessoa           = RS("sq_pessoa") 
            w_sq_contrato_pai     = RS("sq_contrato_pai") 
            w_vigencia_inicio     = FormataDataEdicao(RS("vigencia_inicio"))
            w_vigencia_fim        = FormataDataEdicao(RS("vigencia_fim"))
            w_assinatura          = FormataDataEdicao(RS("assinatura"))
            w_publicacao          = FormataDataEdicao(RS("publicacao"))
            w_valor               = FormatNumber(RS("valor"),2)
            w_numero              = RS("numero") 
            w_processo            = RS("processo") 
            w_objeto              = RS("objeto") 
            w_publicar            = RS("publicar")
            w_empenho             = RS("empenho")
            w_observacao          = RS("observacao") 
            If RS("tp_pessoa") = "F" Then
               w_cpf              = RS("cd_pessoa") 
            Else
               w_cnpj             = RS("cd_pessoa") 
            End If
            w_nome                = RS("nome") 
            w_nome_resumido       = RS("nome_resumido")
            w_sexo                = RS("sexo") 
            w_chave               = RS("sq_portal_contrato")
            w_chave_aux           = null
            DesconectaBD
         End If
      End If
  End If  
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     Modulo
     FormataValor
     FormataCPF
     FormataCNPJ
     ValidateOpen "Validacao"
     If (w_cpf = "" and w_cnpj = "" and w_sq_pessoa = "") or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Troca") > 0 Then ' Se o beneficiário ainda não foi selecionado
        ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
        Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
        ShowHTML "  theForm.Botao.value = ""Procurar"";"
        ShowHTML "}"
        ShowHTML "else {"
        Validate "w_cpf", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "w_cnpj", "CNPJ", "CNPJ", "", "18", "18", "", "0123456789/-."
        ShowHTML "  if (theForm.w_cpf.value=='' && theForm.w_cnpj.value=='') {"
        ShowHTML "     alert('Você deve informar o CPF ou o CNPJ do contratado!');"
        ShowHTML "     theForm.w_cnpj.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_cpf.value!='' && theForm.w_cnpj.value!='') {"
        ShowHTML "     alert('Não é permitido informar o CPF e o CNPJ. Informe um ou outro!');"
        ShowHTML "     theForm.w_cnpj.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        ShowHTML "}"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
        ShowHTML "  theForm.Botao[2].disabled=true;"
     ElseIf InStr("IA",O) > 0 Then
        Validate "w_ordem", "Ordem", "", "1", "1", "4", "", "0123456789"
        Validate "w_nome", "Nome do item", "", "1", "2", "60", "1", "1"
        Validate "w_descricao", "descricao desempenhado", "", "", "1", "2000", "1", "1"
        Validate "w_quantidade", "Quantidade", "VALOR", "1", "3", "18", "", "0123456789,."
        Validate "w_situacao", "Observações", "", "", "1", "500", "1", "1"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" Then
     If (w_cpf = "" and w_cnpj = "" and w_sq_pessoa = "") Then
        BodyOpen "onLoad='document.Form.w_cnpj.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_numero.focus()';"
     End If
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
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Número</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Objeto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Vigência</font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & w_pagina & "VisualPortalCont&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_portal_contrato") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste contrato."">" & RS("numero") & "&nbsp;</a>"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("sg_unid") & "</td>"
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("objeto"),"-") & "</td>"
        Else
           If Len(Nvl(RS("objeto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("objeto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("objeto"),"-") End If
           ShowHTML "        <td title=""" & replace(replace(replace(RS("objeto"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
        End If
        ShowHTML "        <td nowrap><font size=""1"">" & FormatDateTime(RS("vigencia_inicio"),2)&"-"&FormatDateTime(RS("vigencia_fim"),2) & "</td>"
        ShowHTML "        <td nowrap><font size=""1"">" & FormatNumber(RS("valor"),2) & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_portal_contrato") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_portal_contrato") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
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

    If (w_cpf = "" and w_cnpj = "" and w_sq_pessoa = "") or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
       ShowHTML "<FORM action=""" & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
       ShowHTML "<FORM action=""" & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    If (w_cpf = "" and w_cnpj = "" and w_sq_pessoa = "") or InStr(Request("botao"), "Troca") > 0 or Instr(Request("botao"),"Procurar") > 0 Then
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=3><font size=2>Informe o CPF ou o CNPJ do contratado e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td><font size=1><b><u>C</u>NPJ:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" Class=""STI"" NAME=""w_cnpj"" VALUE=""" & w_cnpj & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this, event);"">"
       ShowHTML "            <td><font size=1><b>C<u>P</u>F:<br><INPUT ACCESSKEY=""P"" TYPE=""text"" Class=""STI"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <td valign=""bottom""><INPUT class=""STB"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
       ShowHTML "        <tr><td colspan=3><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=3 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=3>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" Class=""STI"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""STB"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If Request("w_nome") > "" Then
          DB_GetPersonList RS, w_cliente, null, "TODOS", null, null, null, null
          RS.Filter = "nome_indice like '*" & Request("w_nome") & "*' or nome_resumido_ind like '*" & Request("w_nome") & "*'"
          ShowHTML "<tr><td align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome resumido</font></td>"
          ShowHTML "          <td><font size=""1""><b>CNPJ/CPF</font></td>"
          ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""2""><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              ShowHTML "        <td><font  size=""1"">" & RS("nome") & "</td>"
              ShowHTML "        <td><font  size=""1"">" & RS("nome_resumido") & "</td>"
              ShowHTML "        <td align=""center""><font  size=""1"">" & Nvl(RS("codigo"),"---") & "</td>"
              ShowHTML "        <td nowrap><font size=""1"">"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=I&w_sq_pessoa=" & RS("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Selecionar</A>&nbsp"
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
       End If
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "        <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>O</u>rdem:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ordem"" size=""4"" maxlength=""4"" value=""" & w_ordem & """ title=""Informe o número de ordem deste item, conforme edital.""></td>"
       ShowHTML "          <td><font size=""1""><b><u>N</u>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""40"" maxlength=""60"" value=""" & w_nome & """ title=""Informe o nome do material ou serviço, conforme edital.""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b><u>Q</u>uantidade:</b><br><input " & w_Disabled & " accesskey=""Q"" type=""text"" name=""w_quantidade"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_quantidade & """ onKeyDown=""FormataValor(this,17,1,event);"" title=""Informe a quantidade a ser licitada, com uma casa decimal. Se necessário, informe 0,0.""></td>"
       ShowHTML "        </table>"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva o item, se desejar."">" & w_descricao & "</TEXTAREA></td>"
       ShowHTML "        <tr valign=""top"">"
       MontaRadioNS "<b>Item cancelado?</b>", w_cancelado, "w_cancelado"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>O<u>b</u>servações:</b><br><textarea " & w_Disabled & " accesskey=""B"" name=""w_situacao"" class=""STI"" ROWS=5 cols=75 title=""Informe observações sobre este item, que julgar relevante para publicação na Internet."">" & w_situacao & "</TEXTAREA></td>"
       If O = "E" Then
          ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
       End If
       ShowHTML "          </table>"
       ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
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
    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_unidade      = Nothing 
  Set w_sq_contrato_pai = Nothing 
  Set w_sq_pessoa       = Nothing 
  Set w_vigencia_inicio = Nothing 
  Set w_vigencia_fim    = Nothing 
  Set w_publicacao      = Nothing 
  Set w_valor           = Nothing 
  Set w_assinatura      = Nothing 
  Set w_objeto          = Nothing 
  Set w_numero          = Nothing 
  Set w_processo        = Nothing 
  Set w_empenho         = Nothing
  Set w_publicar        = Nothing 
  Set w_observacao      = Nothing 
  Set w_chave           = Nothing 
  Set w_chave_aux       = Nothing
  
End Sub
REM =========================================================================
REM Fim da tela de contratos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de itens do contrato
REM -------------------------------------------------------------------------
Sub ItemContrato
  
  Dim w_chave, w_chave_aux
  
  Dim w_sq_portal_lic_item, w_valor, w_valor_contrato
  
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")


  
  DB_GetLcPortalCont RS, w_cliente, w_chave, w_chave_aux, null
  If RS.RecordCount = 0 Then 
     ScriptOpen "JavaScript"
     ShowHTML " alert('Opção não disponível');"
     ShowHTML " history.back(1);"
     ScriptClose
  Else
     w_valor_contrato = FormatNumber(RS("valor"),2)
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  FormataValor
  FormataData
  ShowHTML "  function MarcaTodos() {"
  ShowHTML "    if (document.Form.w_sq_portal_lic_item.value==undefined) "
  ShowHTML "       for (i=0; i < document.Form.w_sq_portal_lic_item.length; i++) "
  ShowHTML "         document.Form.w_sq_portal_lic_item[i].checked=true;"
  ShowHTML "    else document.Form.w_sq_portal_lic_item.checked=true;"
  ShowHTML "  }"
  ShowHTML "  function DesmarcaTodos() {"
  ShowHTML "    if (document.Form.w_sq_portal_lic_item.value==undefined) "
  ShowHTML "       for (i=0; i < document.Form.w_sq_portal_lic_item.length; i++) "
  ShowHTML "         document.Form.w_sq_portal_lic_item[i].checked=false;"
  ShowHTML "    "
  ShowHTML "    else document.Form.w_sq_portal_lic_item.checked=false;"
  ShowHTML "  }"
  ValidateOpen "Validacao"
  ShowHTML "  if(theForm.w_sq_portal_lic_item.value == undefined){"
  ShowHTML "     for(i=0; i<=theForm.w_sq_portal_lic_item.length - 1; i++){"
  ShowHTML "       if(theForm.w_sq_portal_lic_item[i].checked && eval('theForm.w_valor_'+theForm.w_sq_portal_lic_item[i].value+'.value') == ''){"
  ShowHTML "         alert('Informe o valor unitário dos itens escolhidos!');"
  ShowHTML "         return false;"
  ShowHTML "       }"
  ShowHTML "     }"
  ShowHTML "  }"
  ShowHTML "  else {"
  ShowHTML "     if(eval('theForm.w_valor_'+theForm.w_sq_portal_lic_item.value+'.value') == ''){"
  ShowHTML "       alert('Informe o valor unitário dos itens escolhidos!');"
  ShowHTML "       return false;"
  ShowHTML "     }"
  ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
 
  BodyOpen "onLoad='document.Form.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
 
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td><font size=1>Os dados deste bloco visa informar o itens do contrato.</font></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  'ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7""><table width=""100%"" border=""1"">"
  ShowHTML "<tr><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td colspan=3 align=""center""><font size=""2""><b>Contrato " & RS("numero") & "</td></tr>"
  ShowHTML "      <tr><td colspan=3><font size=""1"">Objeto:<b><br>" & Nvl(RS("objeto"),"---") & "</td></tr>"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td valign=""top""><font size=""1"">Processo:<b><br>" & Nvl(RS("processo"),"---") & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Empenho:<b><br>" & Nvl(RS("empenho"),"---") & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Abertura:<b><br>" & FormataDataEdicao(RS("Assinatura")) & "</td>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td valign=""top""><font size=""1"">Início vigência:<b><br>" & FormataDataEdicao(RS("vigencia_inicio")) & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Fim vigência:<b><br>" & FormataDataEdicao(RS("vigencia_fim")) & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Publicação D.O.U:<b><br>" & FormataDataEdicao(RS("publicacao")) & "</td>"
  ShowHTML "        <tr><td colspan=3><font size=""1"">Valor:<b><br>" & FormatNumber(RS("valor"),2) & "</td></tr>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  If RS("pessoa_juridica") = "S" Then
      ShowHTML "        <tr valign=""top"">"
      ShowHTML "          <td valign=""top""><font size=""1"">CNPJ contratado:<b><br>" & Nvl(RS("cnpj"),"---") & "</td>"
      ShowHTML "          <td valign=""top""><font size=""1"">Nome contratado:<b><br>" & RS("nome") & "</td>"
      ShowHTML "          <td valign=""top""><font size=""1"">Nome resumido:<b><br>" & RS("nome_resumido") & "</td>"
  Else
      ShowHTML "        <tr valign=""top"">"
      ShowHTML "          <td valign=""top""><font size=""1"">CPF contratado:<b><br>" & Nvl(RS("cpf"),"---") & "</td>"
      ShowHTML "          <td valign=""top""><font size=""1"">Nome contratado:<b><br>" & RS("nome") & "</td>"
      ShowHTML "          <td valign=""top""><font size=""1"">Nome resumido:<b><br>" & RS("nome_resumido") & "</td>"
      ShowHTML "        <tr><td colspan=3><font size=""1"">Sexo:<b><br>" & RS("nm_sexo") & "</td></tr>"
  End If
  ShowHTML "        <tr><td colspan=3><font size=""1"">Unidade contratante:<b><br>" & Nvl(RS("nm_unid"),"---") & " (" & RS("sg_unid")& ")</td></tr>"
  ShowHTML "        <tr><td colspan=3><font size=""1"">Observações:<b><br>" & Nvl(RS("observacao"),"---") & "</td></tr>"
  ShowHTML "      <tr><td><font size=""1"">Publica esta licitação no portal?<br><b>" & RS("nm_publicar") & "</b></td></tr>"
  ShowHTML "    </table>"
  ShowHTML "</table>"
  ShowHTML "</table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,"IT"
  ShowHTML MontaFiltro("POST")
 
  ShowHTML "<INPUT type=""hidden"" name=""w_valor_contrato"" value=""" & w_valor_contrato &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_valor"" value="""">"

  ShowHTML "      <tr><td valign=""top""><br>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Selecione os itens os quais este contrato está relacionado:</b>"  
  ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "          <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""HL"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
  ShowHTML "                                 <U STYLE=""cursor:hand;"" CLASS=""HL"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
  ShowHTML "          <td valign=""center""><font size=""1""><b>Item</font></td>"
  ShowHTML "          <td width=""10%""valign=""center""><font size=""1""><b>Quantidade</font></td>"
  ShowHTML "          <td width=""10%""valign=""center""><font size=""1""><b>Valor Unitário</font></td>"
  ShowHTML "        </tr>"
  
  DB_GetLcPortalContItem RS, w_cliente, w_chave, null, w_chave_aux, null
  If Not RS.EOF Then 
     While Not RS.EOF  
        If RS("valor_unitario") > "" Then
           w_valor = FormatNumber(RS("valor_unitario"),2)
        Else
           w_valor = ""
        End If
        If cDbl(Nvl(RS("Existe"),0)) = 0 Then 
           ShowHTML "<INPUT type=""hidden"" name=""w_quantidade_" & RS("sq_portal_lic_item") & """ value=""" & RS("quantidade") &""">"
           ShowHTML "      <tr><td valign=""center""><font size=""1""><input type=""checkbox"" name=""w_sq_portal_lic_item"" value="""&RS("sq_portal_lic_item")&"""></td>"
           ShowHTML "          <td valign=""top""><font size=""1"">" &RS("ordem") & " - " & RS("Nome")& "</font></td>"
           ShowHTML "          <td align=""right""><font size=""1"">" &FormatNumber(RS("Quantidade"),1)& "</font></tr>"
           ShowHTML "          <td valign=""top""><font size=""1""><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_valor_" & RS("sq_portal_lic_item") & """ class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor unitário do item, ou 0,00 se não houver.""></td>"
        Else
           If cDbl(Nvl(RS("existe"),0)) = cDbl(Nvl(w_chave_aux,0)) Then
              ShowHTML "<INPUT type=""hidden"" name=""w_quantidade_" & RS("sq_portal_lic_item") & """ value=""" & RS("quantidade") &""">"
              ShowHTML "      <tr><td valign=""center""><font size=""1""><input type=""checkbox"" name=""w_sq_portal_lic_item"" value="""&RS("sq_portal_lic_item")&""" checked></td>" 
              ShowHTML "          <td valign=""top""><font size=""1"">" & RS("ordem") & " - " & RS("Nome")& "</font></td>"
              ShowHTML "          <td align=""right""><font size=""1"">" &FormatNumber(RS("Quantidade"),1)& "</font></td>"
              ShowHTML "          <td valign=""top""><font size=""1""><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_valor_" & RS("sq_portal_lic_item") & """ class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor unitário do item, ou 0,00 se não houver.""></td>"
              ShowHTML "      </tr>"
           End If
        End If
        RS.MoveNext
     wend  
  Else
     ShowHTML "      <tr><td align=""center"" colspan=4><font size=""1"">Não foi encontrado nenhum item.</td>" 
  End If
  ShowHTML "</table>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & "Contrato" & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave                   = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_portal_lic_item      = Nothing
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de outras iniciativas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub VisualLic

  Dim w_chave, w_Erro, w_logo, w_tipo

  w_chave           = Request("w_chave")
  w_tipo            = uCase(Trim(Request("w_tipo")))

  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_cliente
  If RS("logo") > "" Then
     w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  Cabecalho

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Licitação</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpenClean "onLoad='document.focus()'; "
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualização da Licitação"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados da licitação, na opção "Listagem"
  ShowHTML VisualLicitacao(w_chave, "L", w_usuario)

  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  Rodape

  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub VisualCont

  Dim w_chave, w_chave_aux, w_Erro, w_logo, w_tipo

  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_tipo            = uCase(Trim(Request("w_tipo")))

  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_cliente
  If RS("logo") > "" Then
     w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  Cabecalho

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização do Contrato</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpenClean "onLoad='document.focus()'; "
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualização do Contrato"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados da licitação, na opção "Listagem"
  ShowHTML VisualContrato(w_chave_aux, w_chave, "L", w_usuario)

  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  Rodape

  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing
  Set w_chave_aux           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exclusão
REM -------------------------------------------------------------------------
Sub Excluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("E",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_chave, null, null, null, _
     null, null, null, null, null, null, null, null, null, null, null, null, null, null
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td colspan=3 align=""center""><font size=""2""><b>" & RS("nm_modalidade") & " " & RS("edital") & "</td></tr>"
  ShowHTML "      <tr><td colspan=3><hr></td></tr>"
  ShowHTML "      <tr><td colspan=3><font size=""1"">Objeto:<b><br>" & Nvl(RS("objeto"),"---") & "</td></tr>"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td valign=""top""><font size=""1"">Processo:<b><br>" & Nvl(RS("processo"),"---") & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Empenho:<b><br>" & Nvl(RS("empenho"),"---") & "</td>"
  ShowHTML "          <td valign=""top""><font size=""1"">Abertura:<b><br>" & RS("abertura") & "</td>"
  ShowHTML "        <tr><td colspan=3><font size=""1"">Observações:<b><br>" & Nvl(RS("observacao"),"---") & "</textarea></td></tr>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "            <td><font size=""1"">Modalidade da licitação<br><b>" & RS("nm_modalidade") & "</b></td>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "            <td><font size=""1"">Fundamentação<br><b>" & Nvl(RS("fundamentacao"),"---") & "</b></td>"
  ShowHTML "          </table>"

  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "            <td><font size=""1"">Fonte de recursos<br><b>" & RS("nm_fonte") & "</b></td>"
  ShowHTML "            <td><font size=""1"">Finalidade da licitacao<br><b>" & RS("nm_finalidade") & "</b></td>"
  ShowHTML "            <td><font size=""1"">Critério de julgamento<br><b>" & RS("nm_criterio") & "</b></td>"

  ShowHTML "        <tr valign=""top"">"
  ShowHTML "            <td><font size=""1"">Situação da licitação<br><b>" & RS("nm_situacao") & "</b></td>"
  ShowHTML "            <td><font size=""1"">Unidade licitante<br><b>" & RS("nm_unid") & "</b></td>"
  ShowHTML "          </table>"

  ShowHTML "      <tr><td><font size=""1"">Publica esta licitação no portal?<br><b>" & RS("nm_publicar") & "</b></td></tr>"

  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"LCPTGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
  ShowHTML "      <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_observacao      = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de exclusão
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_chave_nova
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  Dim w_valor_total
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "LCPTGERAL"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutLcPortalLic O, _
              w_cliente, Request("w_chave"), Request("w_objeto"), Request("w_edital"), Request("w_processo"), Request("w_empenho"), _
              Request("w_abertura"), Request("w_fundamentacao"), Request("w_observacao"), Request("w_sq_lcmodalidade"), _
              Request("w_sq_lcfonte_recurso"), Request("w_sq_lcfinalidade"), Request("w_sq_lcjulgamento"), _
              Request("w_sq_lcsituacao"), Request("w_sq_unidade"), Request("w_publicar"), _
              w_chave_nova, w_copia
          
          ScriptOpen "JavaScript"
          If O = "I" Then
             ' Recupera os dados para montagem correta do menu
             DB_GetLcPortalLic RS, w_cliente, w_usuario, w_menu, w_chave_nova, null, null, null, _
                null, null, null, null, null, null, null, null, null, null, null, null, null, null
             
             DB_GetLinkData RS1, Session("p_cliente"), "LCPORTAL"
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=" & RS("sg_modalidade") & "-" & RS("edital") & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
          ElseIf O = "E" Then
             ' Recupera os dados para montagem correta do menu
             DB_GetLinkData RS1, Session("p_cliente"), "LCPORTAL"
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=" & RS1("sigla") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "LCPTANEXO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          ' Se foi feito o upload de um arquivo  
          Set FS = CreateObject("Scripting.FileSystemObject")
          If ul.State = 0 Then
             w_maximo     = ul.Texts.Item("w_upload_maximo")
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
                   If ul.Texts.Item("w_atual") > "" Then
                      DB_GetSolicAnexo RS, ul.Texts.Item("w_chave"), ul.Texts.Item("w_atual"), w_cliente 
                      FS.DeleteFile conFilePhysical & w_cliente & "\" & RS("caminho")
                      w_file = Mid(RS("caminho"),1,Instr(RS("caminho"),".")-1) & Mid(Field.FileName,Instr(Field.FileName,"."),30)
                   Else
                      w_file = replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30))
                   End If
                   w_tamanho = Field.Length
                   w_tipo    = Field.ContentType
                   w_nome    = Field.FileName
                   Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
                End If
             Next
    
             'Response.Write UploadID & "w_file: " & w_file & "<br> " & "w_tamanho: " & w_tamanho & "<br> " & "w_tipo: " & w_tipo & "<br> " & "w_nome: " & w_nome
             'Response.End()

             ' Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
             If O = "E" and ul.Texts.Item("w_atual") > "" Then  
                DB_GetLcAnexo RS, ul.Texts.Item("w_chave"), ul.Texts.Item("w_atual"), w_cliente 
                FS.DeleteFile conFilePhysical & w_cliente & "\" & RS("caminho")
                DesconectaBD
             End If  
    
             'Response.Write O& ", " &w_cliente& ", " &ul.Texts.Item("w_chave")& ", " &ul.Texts.Item("w_chave_aux")& ", " &ul.Texts.Item("w_nome")& ", " &ul.Texts.Item("w_descricao")
             'Response.End()
             DML_PutLcArquivo O, _  
                 w_cliente, ul.Texts.Item("w_chave"), ul.Texts.Item("w_chave_aux"), ul.Texts.Item("w_nome"), ul.Texts.Item("w_descricao"), _  
                 w_file, w_tamanho, w_tipo, w_nome
                 
          Else
             ScriptOpen "JavaScript" 
             ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
             ScriptClose 
             Response.End()
             Exit Sub
          End If
                    
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu 
          DB_GetLinkData RS, Session("p_cliente"), SG 
          ShowHTML "  location.href='"  & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "LCPTITEM"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutLcPortalLicItem O, _
              w_cliente, Request("w_chave"), Request("w_chave_aux"), Request("w_ordem"), Request("w_nome"), Request("w_quantidade"), _
              Request("w_descricao"), Request("w_sq_unidade_fornec"), Request("w_cancelado"), Request("w_situacao")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "LCPTCONT"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          If O = "IT" Then
             w_valor_total = 0
             DML_PutLcPortalContItem "E", Request("w_chave_aux"), null, null, null
             For w_cont = 1 to Request.Form("w_sq_portal_lic_item").Count
                If Request("w_sq_portal_lic_item")(w_cont) > "" Then
                   DML_PutLcPortalContItem "I", Request("w_chave_aux"), Request("w_sq_portal_lic_item")(w_cont), Nvl(Request("w_valor_" & Request("w_sq_portal_lic_item")(w_cont)),0), Nvl(Request("w_quantidade_"&Request("w_sq_portal_lic_item")(w_cont)),0)
                   w_valor_total = w_valor_total + ( Nvl(Request("w_valor_"&Request("w_sq_portal_lic_item")(w_cont)),0)*Nvl(Request("w_quantidade_"&Request("w_sq_portal_lic_item")(w_cont)),0))
                End If
             Next
          Else
             DML_PutLcPortalCont O, _
                 w_cliente, Request("w_chave"),  Request("w_chave_aux"),  Request("w_numero"), Request("w_objeto"), Request("w_processo"), Request("w_empenho"), _
                 Request("w_assinatura_form"), Request("w_vigencia_inicio"), Request("w_vigencia_fim"), Request("w_publicacao"), _
                 Request("w_valor"), Request("w_pessoa_juridica"), Request("w_cnpj"), Request("w_cpf"), _
                 Request("w_nome"), Request("w_nome_resumido"), Request("w_sexo"), Request("w_sq_pessoa"), _
                 Request("w_sq_unidade"), Request("w_observacao"), Request("w_publicar")
          End If
          ScriptOpen "JavaScript"
          If O = "IT" and (FormatNumber(w_valor_total,2) <> Request("w_valor_contrato")) Then
             ShowHTML "alert('AVISO: o valor total dos itens selecionados difere do valor do contrato!');"
          End If
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
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

  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set w_chave_nova          = Nothing
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
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "INICIAL"
       Inicial
    Case "GERAL"
       Geral
    Case "ANEXOS"
       Anexos
    Case "ITEM"
       ItemLicitacao
    Case "CONTRATO"
       Contrato
    Case "ITEMCONTRATO"
       ItemContrato
    Case "VISUALLIC"
       VisualLic
    Case "VISUALCONT"
       VisualCont
    Case "EXCLUIR"
       Excluir
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

