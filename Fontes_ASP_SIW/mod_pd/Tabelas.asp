<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Gerencial.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<!-- #INCLUDE FILE="DB_Viagem.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->

<%
Response.Expires = -1500
REM =========================================================================
REM  /tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerencia as rotinas de tabelas básicas do módulo de passagens e diárias
REM Mail     : celso@sbpi.com.br
REM Criacao  : 04/10/2005 11:00
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Filtragem

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta, UploadID
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
w_pagina     = "tabelas.asp?par="
w_Dir        = "mod_pd/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = ucase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG)

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
   w_troca          = ul.Texts.Item("w_troca")  
   w_copia          = ul.Texts.Item("w_copia")  
    
   P1               = ul.Texts.Item("P1")  
   P2               = ul.Texts.Item("P2")  
   P3               = ul.Texts.Item("P3")  
   P4               = ul.Texts.Item("P4")  
   TP               = ul.Texts.Item("TP")  
   R                = uCase(ul.Texts.Item("R"))  
   w_Assinatura     = uCase(ul.Texts.Item("w_Assinatura"))  
Else  
   w_troca          = Request("w_troca")  
   w_copia          = Request("w_copia")  

   P1               = Nvl(Request("P1"),0)  
   P2               = Nvl(Request("P2"),0)  
   P3               = cDbl(Nvl(Request("P3"),1))  
   P4               = cDbl(Nvl(Request("P4"),conPagesize))  
   TP               = Request("TP")  
   R                = uCase(Request("R"))  
   w_Assinatura     = uCase(Request("w_Assinatura"))  
    
   If O = "" Then
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
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set w_copia       = Nothing
Set w_filtro      = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
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
Set w_pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing

REM =========================================================================
REM Manter Tabela básica "PD_CIA_TRANSPORTE"
REM -------------------------------------------------------------------------
Sub CiaTrans

  Dim w_chave, w_nome, w_aereo, w_rodoviario, w_aquaviario, w_padrao, w_ativo, p_ordena
  
  w_Chave          = Request("w_Chave")
  p_ordena         = Request("p_ordena")
  
  If w_troca > "" Then ' Se for recarga da página
     w_chave       = Request("w_chave")
     w_nome        = Request("w_nome")
     w_aereo       = Request("w_aereo")
     w_rodoviario  = Request("w_rodoviario")
     w_aquaviario  = Request("w_aquaviario")
     w_padrao      = Request("w_padrao")
     w_ativo       = Request("w_ativo")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetCiaTrans RS, w_cliente, null, null, null, null, null, null, null, null, null
     If Nvl(p_ordena,"") > "" Then
        RS.Sort = p_ordena
     Else
        RS.Sort = "aereo desc, rodoviario desc, aquaviario desc, nome"
     End If
  ElseIf InStr("AE",O) > 0 and w_Troca = "" Then
     ' Recupera os dados chave informada
     DB_GetCiaTrans RS, w_cliente, w_chave, null, null, null, null, null, null, null, null
     w_chave        = RS("chave")
     w_nome         = RS("nome")
     w_aereo        = RS("aereo")
     w_rodoviario   = RS("rodoviario")
     w_aquaviario   = RS("aquaviario")
     w_padrao       = RS("padrao")
     w_ativo        = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome"      , "Nome"                 , "1", "1", "2", "30", "1", "1"
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
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Aéreo","nm_aereo") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Rodoviário","nm_rodoviario") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Aquaviário","nm_aquaviario") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","nm_ativo") & "</font></td>"
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
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_aereo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_rodoviario") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_aquaviario") & "</td>"
        If Nvl(RS("ativo"),"") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
        Else
           ShowHTML "        <td align=""center""><font color=""red"" size=""1"">" & RS("nm_ativo") & "</td>"
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
  ElseIf Instr("IAE",O) > 0 Then
    If InStr("E",O) Then
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
    MontaRadioNS "<b>Aéreo?</b>", w_aereo, "w_aereo"
    MontaRadioNS "<b>Rodoviário?</b>", w_rodoviario, "w_rodoviario"
    MontaRadioNS "<b>Aquaviário?</b>", w_aquaviario, "w_aquaviario"
    ShowHTML "        <tr valign=""top"">"
    MontaRadioNS "<b>Padrão?</b>", w_padrao, "w_padrao"
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

  Set w_chave        = Nothing
  Set w_nome         = Nothing
  Set w_aereo        = Nothing
  Set w_rodoviario   = Nothing
  Set w_aquaviario   = Nothing
  Set w_padrao       = Nothing 
  Set w_ativo        = Nothing 
  Set w_troca        = Nothing
  Set p_ordena       = Nothing
End Sub

REM =========================================================================
REM Rotina dos parâmetros
REM -------------------------------------------------------------------------
Sub Parametros
  Dim w_sequencial, w_ano_corrente, w_prefixo, w_sufixo, w_dias_antecedencia
  Dim w_dias_prest_contas, w_sequencial_atual
   

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sequencial          = Request("w_sequencial")
     w_sequencial_atual    = Request("w_sequencial_atual")  
     w_ano_corrente        = Request("w_ano_corrente") 
     w_prefixo             = Request("w_prefixo") 
     w_sufixo              = Request("w_sufixo") 
     w_dias_antecedencia   = Request("w_dias_antecedencia") 
     w_dias_prest_contas   = Request("w_dias_prest_contas") 
  Else
     ' Recupera os dados do parâmetro
     DB_GetPDParametro RS, w_cliente, null, null
     If RS.RecordCount > 0 Then 
        w_sequencial         = RS("sequencial")
        w_sequencial_atual   = RS("sequencial")
        w_ano_corrente       = RS("ano_corrente") 
        w_prefixo            = RS("prefixo") 
        w_sufixo             = RS("sufixo") 
        w_dias_antecedencia  = RS("dias_antecedencia") 
        w_dias_prest_contas  = RS("dias_prestacao_contas") 
        DesconectaBD
     End If
  End If  
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  ShowHTML "  if (theForm.w_sequencial_atual.value > ''){ "
  ShowHTML "    if (theForm.w_sequencial.value <  theForm.w_sequencial_atual.value){ "
  ShowHTML "      alert('O número sequencial atual nao pode ser menor que ' + theForm.w_sequencial_atual.value + '!');"
  ShowHTML "      return false;"
  ShowHTML "    };"
  ShowHTML "  };"
  Validate "w_sequencial", "Sequencial", "1", 1, 1, 18, "", "0123456789"
  'Validate "w_ano_corrente", "Ano corrente", "1", 1, 4, 4, "", "0123456789"
  Validate "w_prefixo", "Prefixo", "1", "", 1, 10, "1", "1"
  Validate "w_sufixo", "Sufixo", "1", "", 1, 10, "1", "1"
  Validate "w_dias_antecedencia", "Dias de antecedência", "1", 1, 1, 3, "", "0123456789"
  Validate "w_dias_prest_contas", "Dias para prestação de contas", "1", 1, 1, 3, "", "0123456789"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_sequencial.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina&par,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_sequencial_atual"" value=""" & w_sequencial_atual & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_ano_corrente"" value=""" & Year(Date()) & """>"  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0""><tr><td>"
  ShowHTML "      <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Parâmetros</td></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  'ShowHTML "      <tr><td><font size=1>Falta definir a explicação.</font></td></tr>"
  'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
   ShowHTML "      </table>"
  ShowHTML "      <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td><font size=""1""><b><u>S</u>equencial:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sequencial"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_sequencial & """></td>"
  'ShowHTML "          <td><font size=""1""><b><u>A</u>no corrente:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_ano_corrente"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_ano_corrente & """></td>"
  ShowHTML "      <tr><td><font size=""1""><b><u>P</u>refixo:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_prefixo"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_prefixo & """></td>"
  ShowHTML "          <td><font size=""1""><b><u>S</u>ufixo:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sufixo"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_sufixo & """></td>"
  ShowHTML "      <tr><td><font size=""1""><b><u>D</u>ias de antecedência:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_dias_antecedencia"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_dias_antecedencia & """></td>"
  ShowHTML "          <td><font size=""1""><b>D<u>i</u>as para prestação de contas:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_dias_prest_contas"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_dias_prest_contas & """></td>"  
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
  
  ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sequencial          = Nothing
  Set w_ano_corrente        = Nothing
  Set w_prefixo             = Nothing
  Set w_sufixo              = Nothing
  Set w_dias_antecedencia   = Nothing
  Set w_dias_prest_contas   = Nothing

End Sub

REM =========================================================================
REM Rotina de unidade
REM -------------------------------------------------------------------------
Sub Unidade

  Dim w_nome, w_sigla, w_chave, w_chave1, w_limite_passagem, w_limite_diaria
  Dim w_ativo, w_ano, p_ordena
  
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_ano             = Request("w_ano")
  p_ordena          = Request("p_ordena")
  
  If w_troca > "" Then ' Se for recarga da página
     w_nome                 = Request("w_nome")
     w_sigla                = Request("w_sigla")
     w_limite_passagem      = Request("w_limite_passagem")
     w_limite_diaria        = Request("w_limite_diaria")
     w_ativo                = Request("w_ativo")
     w_ano                  = Request("w_ano")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetUorgList RS, w_cliente, null, "VIAGEM", null, null, null
     If Nvl(p_ordena,"") > "" Then
        RS.Sort = p_ordena
     Else
        RS.Sort = "ano, nome"
     End If
  ElseIf InStr("AE",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetUorgList RS, w_cliente, w_chave, "VIAGEM", null, null, w_ano
     w_nome                 = RS("nome")
     w_sigla                = RS("sigla")
     w_limite_passagem      = FormatNumber(RS("limite_passagem"),2)
     w_limite_diaria        = FormatNumber(RS("limite_diaria"),2)
     w_ativo                = RS("ativo")
     w_ano                  = RS("ano")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        If O = "I" Then
           Validate "w_chave", "Unidade", "HIDDEN", "1", "1", "50", "1", "1"
        End If
        Validate "w_limite_passagem", "Limite financeiro para passagens", "VALOR", "1", 4, 18, "", "0123456789.,"
        Validate "w_limite_diaria", "Limite financeiro para diárias", "VALOR", "1", 4, 18, "", "0123456789.,"
        Validate "w_ano", "Ano", "SELECT", "1", "4", "4", "", "0123456789"
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
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ano","ano") & "</font></td>"    
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Unidade","nome") & "</font></td>"    
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Limite passagens","limite_passagem") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Limite diárias","limite_diaria") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo","ativo") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ano") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") &" (" &RS("sigla") & ")</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("limite_passagem"),2) &"</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("limite_diaria"),2) &"</td>"
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"" color=""red"">" & RS("nm_ativo") & "</td>"
        End If 
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
    ShowHTML "<INPUT type=""hidden"" name=""w_chave1"" value=""" &w_chave1& """>"
    If O <> "I" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_ano"" value=""" & w_ano & """>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoUnidade "<U>U</U>nidade:", "S", null, w_chave, null, "w_chave", null, null
    Else
       ShowHTML "        <tr><td><font size=1><b>Unidade:<br>" & w_nome & " (" & w_sigla & ")</b>"
    End If
    ShowHTML "           </table>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><u>L</u>imite financeiro para passagens:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_limite_passagem"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_limite_passagem & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o limite financeiro para passagens para a unidade selecionada.""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>L<u>i</u>mite financeiro para diárias:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_limite_diaria"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_limite_diaria & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o limite financeiro para diárias para a unidade selecionada.""></td>"
    ShowHTML "      <tr valign=""top"">"
    If O = "I" Then
       SelecaoAno "<U>A</U>no:", "A", null, w_ano, null, "w_ano", null, null
    Else
       ShowHTML "          <td valign=""top""><font size=""1""><b>Ano:<br>" & w_ano & "</b></td>"
    End If
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
    ShowHTML "         </table>"    
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
  Set w_chave1              = Nothing
  Set w_limite_passagem     = Nothing
  Set w_limite_diaria       = Nothing
  Set w_ativo               = Nothing
  Set w_ano                 = Nothing
  Set p_ordena              = Nothing
End Sub

REM =========================================================================
REM Rotina de usuário
REM -------------------------------------------------------------------------
Sub Usuario

  Dim w_chave, p_ordena
  
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  p_ordena          = Request("p_ordena")
  
  If O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetPersonList RS, w_cliente, w_chave, SG, null, null, null, null
     If p_ordena > "" Then
        RS.Sort = p_ordena
     Else
        RS.Sort = "nome_resumido"
     End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("I",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     ValidateOpen "Validacao"
     Validate "w_chave", "Pessoa", "HIDDEN", "1", "1", "50", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
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
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","nome_resumido") & "</font></td>"    
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Lotação","sg_unidade") & "</font></td>"    
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ramal","ramal") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("chave"), TP, RS("nome_resumido")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("nm_local"), RS("sq_unidade"), TP) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("ramal"),"---") &"</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "<u>P</u>essoa:", "p", "Selecione a pessoa.", w_chave, null, "w_chave", "USUARIOS"
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
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

  Set w_chave               = Nothing
  Set p_ordena              = Nothing
End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo, w_codigo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1, w_file
  Dim w_chave_nova, w_item, w_data, w_valor

  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpenClean "onLoad=document.focus();"
  
  If Instr(SG, "PDCIA") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        
        If Instr("IA",O) > 0 Then
           If Request("w_padrao") = "S" Then
              DB_GetCiaTrans RS, w_cliente, null, null, null, null, null, "S", null, Request("w_chave"), null
              If RS.RecordCount > 0 Then
                 ScriptOpen "JavaScript"
                 ShowHTML "  alert('Somente pode existir uma companhia padrão!');"
                 ShowHTML "  history.back(1);"
                 ScriptClose
                 Exit Sub
              End If           
           End If
           DB_GetCiaTrans RS, w_cliente, null, Request("w_nome"), null, null, null, null, null, Request("w_chave"), null
           If RS.RecordCount > 0 Then
              ScriptOpen "JavaScript"
              ShowHTML "  alert('Companhia já cadastrada!');"
              ShowHTML "  history.back(1);"
              ScriptClose
              Exit Sub
           End If
        End If
        
        DML_PutCiaTrans O, w_cliente, _
            Request("w_chave"), Request("w_nome"), Request("w_aereo"), Request("w_rodoviario"), _
            Request("w_aquaviario"), Request("w_padrao"), Request("w_ativo")
          
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "PDPARAM") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        DML_PutPDParametro w_cliente, _
            Request("w_sequencial"), Request("w_ano_corrente"), Request("w_prefixo"), _
            Request("w_sufixo"), Request("w_dias_antecedencia"), Request("w_dias_prest_contas")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=" & O & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "PDUNIDADE") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        If O = "I" Then
           DB_GetUorgList RS, w_cliente, Request("w_Chave"), "VIAGEM", null, null, Request("w_ano")
           If RS.RecordCount = 0 Then
              DML_PutPDUnidade O, Request("w_chave"), Request("w_limite_passagem"), Request("w_limite_diaria"), Request("w_ativo"), Request("w_ano")
              ScriptOpen "JavaScript"
              ShowHTML "  location.href='" & R & "&w_chave=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
              ScriptClose
           Else
              ScriptOpen "JavaScript"
              ShowHTML "  alert('Unidade já cadastrada no ano de " & Request("w_ano") & "!');"
              ShowHTML "  history.back(1);"
              ScriptClose
           End If
        Else
           DML_PutPDUnidade O, Request("w_chave"), Request("w_limite_passagem"), Request("w_limite_diaria"), Request("w_ativo"), Request("w_ano")
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
  ElseIf Instr(SG, "PDUSUARIO") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        If O = "I" Then
           DB_GetPersonList RS, w_cliente, Request("w_chave"), SG, null, null, null, null
           If RS.RecordCount > 0 Then
              ScriptOpen "JavaScript"
              ShowHTML "  alert('Usuário já cadastro!');"
              ShowHTML "  history.back(1);"
              ScriptClose
              Exit Sub
           End If
        End If
        DML_PutPDUsuario O, w_cliente, Request("w_chave")
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&w_chave=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If 
  Else
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
     ShowHTML "  history.back(1);"
     ScriptClose
  End If

  Set w_data                = Nothing
  Set w_valor               = Nothing
  Set w_chave_nova          = Nothing
  Set w_file                = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
  Set w_codigo              = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "CIATRANS"      CiaTrans
    Case "PARAMETROS"    Parametros
    Case "UNIDADE"       Unidade
    Case "USUARIO"       Usuario
    Case "GRAVA"         Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpenClean "onLoad=document.focus();"
         Estrutura_Topo_Limpo
       Estrutura_Menu
       Estrutura_Corpo_Abre
       Estrutura_Texto_Abre
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Estrutura_Texto_Fecha
         Estrutura_Fecha
       Estrutura_Fecha
       Estrutura_Fecha
       Rodape
  End Select
End Sub
%>