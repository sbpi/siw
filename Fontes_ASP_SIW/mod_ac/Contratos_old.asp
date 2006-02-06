<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Contrato.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Contrato.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Contratos.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia as rotinas relativas a controle de contratos e convênios
REM Mail     : alex@sbpi.com.br
REM Criacao  : 28/05/2003 15:00
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
Dim dbms, sp, RS, RS1
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura, w_Cliente, w_Classe, w_submenu, w_reg
Dim p_ativo, p_cidade, p_uf, p_nome, p_ordena
Dim ul,File
Dim w_troca, w_cor, w_usuario, w_dir, w_menu
Dim w_sq_acordo
Dim w_dir_volta
Private Par

w_troca            = Request("w_troca")
p_uf               = uCase(Request("p_uf"))
p_cidade           = uCase(Request("p_cidade"))
p_nome             = uCase(Request("p_nome"))
p_ativo            = uCase(Request("p_ativo"))
p_ordena           = uCase(Request("p_ordena"))
  

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
w_Pagina     = "Contratos.asp?par="
w_Dir        = "mod_ac/"
w_Disabled   = "ENABLED"

p_uf        = uCase(Request("p_uf"))
p_cidade    = uCase(Request("p_cidade"))
p_nome      = uCase(Request("p_nome"))
p_ativo     = uCase(Request("p_ativo"))
p_ordena    = uCase(Request("p_ordena"))
w_troca     = uCase(Request("w_troca"))
w_sq_acordo = uCase(Request("w_sq_acordo"))

If O = "L" and InStr("GCDGERAL,GCRGERAL,GDPGERAL,GCDTERMO,GCRTERMO,GCPTERMO,GCDOUTRA,GCROUTRA,GCPOUTRA,GCDVISUAL,GCRVISUAL,GCPVISUAL",SG) > 0 Then
   O = "A"
ElseIf O = "" Then
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
  Case "V" 
     w_TP = TP & " - Envio"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente = RetornaCliente()
w_usuario = RetornaUsuario()
w_menu    = RetornaMenu(w_cliente, SG)

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   ' Cria o objeto de upload
   Set ul       = Nothing
   Set ul       = Server.CreateObject("Dundas.Upload.2")
   ul.SaveToMemory  
   P1           = ul.Form("P1")
   P2           = ul.Form("P2")
   P3           = ul.Form("P3")
   P4           = ul.Form("P4")
   TP           = ul.Form("TP")
   R            = uCase(ul.Form("R"))
   w_Assinatura = uCase(ul.Form("w_Assinatura"))
End If

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, w_cliente, SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configuração do serviço
DB_GetMenuData RS1, w_menu
If RS1("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS1, RS1("sq_menu_pai")
End If

Main

FechaSessao

Set w_menu      = Nothing
Set w_usuario   = Nothing
Set w_cor       = Nothing
Set ul          = Nothing
Set File        = Nothing
Set w_sq_acordo = Nothing
Set w_troca     = Nothing
Set w_submenu   = Nothing
Set w_reg       = Nothing
Set p_uf        = Nothing
Set p_cidade    = Nothing
Set p_ativo     = Nothing
Set p_nome      = Nothing
Set p_ordena    = Nothing

Set w_classe    = Nothing
Set w_cliente   = Nothing

Set RS1         = Nothing
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
REM Rotina da tabela de Acordos
REM -------------------------------------------------------------------------
Sub Inicial

  If O = "L" Then
     DB_GetAgree RS, null, w_cliente, SG
     If p_nome > "" Then
        w_filter = ""
        If p_nome        > ""  Then w_filter = w_filter & "  and nome             like '*" & p_nome & "*'"  End If
        RS.Filter = Mid(w_filter,6,255)
     End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "Javascript"
  ValidateOpen "Validacao"
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
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_submenu > "" Then
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""Menu.asp?par=ExibeDocs&O=I&R=" & w_dir & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """ TARGET=""menu""><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
       'ShowHTML "                         <a accesskey=""N"" class=""ss"" href=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>N</u>ovo cliente</a>&nbsp;"
    End If
    If p_uf & p_cidade & p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td colspan=2><font size=""1""><b>Vigência</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Outra parte</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Modalidade</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Centro de custo</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Fim</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("codigo_interno") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim")) & "</td>"
        ShowHTML "        <td title=""" & RS("nome") & """><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("sq_tipo_acordo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("sq_cc") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_sq_acordo=" & RS("sq_acordo") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("codigo_interno") & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """ title=""Altera as informações cadastrais do acordo"" TARGET=""menu"">Alterar</a>&nbsp;"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_sq_acordo=" & RS("sq_acordo") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclui o acordo."">Excluir</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&O=V&w_sq_acordo=" & RS("sq_acordo") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&p_nome=" & p_nome & "&p_uf=" & p_uf & "&p_cidade=" & p_cidade & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """ title=""Exclui o acordo"" TARGET=""menu"">Enviar</a>&nbsp;"
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
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="LOCALIZACAO" Then
       ShowHTML "          <option value=""localizacao"" SELECTED>Localização<option value=""sigla"">Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="SQ_UNIDADE_LOTACAO" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"" SELECTED>Lotação<option value="""">Nome<option value=""username"">Username"
    ElseIf p_Ordena="USERNAME" Then
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"">Lotação<option value="""">Nome<option value=""username"" SELECTED>Username"
    Else
       ShowHTML "          <option value=""localizacao"">Localização<option value=""sigla"">Lotação<option value="""" SELECTED>Nome<option value=""username"">Username"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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

End Sub
REM =========================================================================
REM Fim da tabela de acordos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral

  Dim w_sq_acordo_pai, w_outra_parte, w_sq_tipo_acordo
  Dim w_sq_cc, w_inicio, w_fim, w_codigo_externo, w_objeto
  Dim w_observacao, w_dia_vencimento
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sq_acordo_pai   = Request("w_sq_acordo_pai")
     w_outra_parte     = Request("w_outra_parte")
     w_sq_tipo_acordo  = Request("w_sq_tipo_acordo")
     w_sq_cc           = Request("w_sq_cc")
     w_inicio          = Request("w_inicio")
     w_fim             = Request("w_fim")
     w_codigo_externo  = Request("w_codigo_externo")
     w_objeto          = Request("w_objeto")
     w_observacao      = Request("w_observacao")
     w_dia_vencimento  = Request("w_dia_vencimento")
  Else
     If InStr("AEV",O) > 0 Then
        DB_GetAgree RS, w_sq_acordo, w_cliente, SG
        w_sq_acordo_pai   = RS("sq_acordo_pai")
        w_outra_parte     = RS("outra_parte")
        w_sq_tipo_acordo  = RS("sq_tipo_acordo")
        w_sq_cc           = RS("sq_cc")
        w_inicio          = FormataDataEdicao(RS("inicio"))
        w_fim             = FormataDataEdicao(RS("fim"))
        w_codigo_externo  = RS("codigo_externo")
        w_objeto          = RS("objeto")
        w_observacao      = RS("observacao")
        w_dia_vencimento  = RS("dia_vencimento")
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCNPJ
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If instr("IA",O) > 0 Then
     Validate "w_sq_tipo_acordo", "Tipo do acordo", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_sq_cc", "Centro de custo", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_inicio", "Início da vigência", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim", "Fim da vigência", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_dia_vencimento", "Dia de vencimento", "1", "", "1", "2", "", "1"
     Validate "w_codigo_externo", "Código externo", "1", "", 3, 60, "1", "1"
     Validate "w_observacao", "Observação", "1", 1, 10, 1000, "1", "1"
     Validate "w_objeto", "Objeto", "1", 1, 10, 2000, "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_sq_tipo_acordo.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If
    AbreForm "Form", w_dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS1("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_acordo"" value=""" & w_sq_acordo &""">"
    ' Depois é necessário fazer com que o campo abaixo seja recebido na tela
    ShowHTML "<INPUT type=""hidden"" name=""w_valor_inicial"" value=""0"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Enquadramento</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para enquadramento orçamentário e contábil do acordo.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr>"
    SelecaoTipoAcordo "<u>T</u>ipo de acordo:", "T", "Selecione na lista o tipo de acordo adequado.", w_sq_tipo_acordo, null, w_cliente, "w_sq_tipo_acordo", SG, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoCC "<u>C</u>entro de custo:", "C", "Selecione na lista o centro de custo ao qual o contrato está vinculado.", w_sq_cc, null, "w_sq_cc", SG
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Vigência</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Informe a vigência do acordo. Apenas a data de início é obrigatória.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Data de início da vigência do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Data de término da vigência do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>F</u>im:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "              </td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Outras informações</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Este bloco contém dados complementares sobre o acordo, necessários ao seu entendimento.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0>"
    ShowHTML "          <tr><td valign=""top"" ONMOUSEOVER=""popup('Dia padrão de vencimento das parcelas do acordo. Não informe se o contrato não possuir esta característica.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><U>D</U>ia de vencimento:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dia_vencimento"" size=""2"" maxlength=""2"" value=""" & w_dia_vencimento & """></td>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Código, número ou nome pelo qual o acordo é reconhecido pela outra parte.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>Código e<U>x</U>terno:<br><INPUT ACCESSKEY=""X"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo_externo"" size=""40"" maxlength=""60"" value=""" & w_codigo_externo & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" ONMOUSEOVER=""popup('Descreva o objeto do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><U>O</U>bjeto:<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_objeto"" cols=75 rows=""5"">" & w_objeto & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top"" ONMOUSEOVER=""popup('Observações gerais relevantes sobre o acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>O<U>b</U>servações:<br><TEXTAREA ACCESSKEY=""B"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_observacao"" cols=75 rows=""5"">" & w_observacao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    If O = "I" Then
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_acordo_pai   = Nothing
  Set w_outra_parte     = Nothing
  Set w_sq_tipo_acordo  = Nothing
  Set w_sq_cc           = Nothing
  Set w_inicio          = Nothing
  Set w_fim             = Nothing
  Set w_codigo_externo  = Nothing
  Set w_objeto          = Nothing
  Set w_observacao      = Nothing
  Set w_dia_vencimento  = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de dados gerais
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exclusão do acordo
REM -------------------------------------------------------------------------
Sub Excluir

  Dim w_sq_acordo_pai, w_outra_parte, w_sq_tipo_acordo
  Dim w_sq_cc, w_inicio, w_fim, w_codigo_externo, w_objeto
  Dim w_observacao, w_dia_vencimento
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sq_acordo_pai   = Request("w_sq_acordo_pai")
     w_outra_parte     = Request("w_outra_parte")
     w_sq_tipo_acordo  = Request("w_sq_tipo_acordo")
     w_sq_cc           = Request("w_sq_cc")
     w_inicio          = Request("w_inicio")
     w_fim             = Request("w_fim")
     w_codigo_externo  = Request("w_codigo_externo")
     w_objeto          = Request("w_objeto")
     w_observacao      = Request("w_observacao")
     w_dia_vencimento  = Request("w_dia_vencimento")
  Else
     If InStr("AEV",O) > 0 Then
        DB_GetAgree RS, w_sq_acordo, w_cliente, replace(SG,"CAD","GERAL")
        w_sq_acordo_pai   = RS("sq_acordo_pai")
        w_outra_parte     = RS("outra_parte")
        w_sq_tipo_acordo  = RS("sq_tipo_acordo")
        w_sq_cc           = RS("sq_cc")
        w_inicio          = FormataDataEdicao(RS("inicio"))
        w_fim             = FormataDataEdicao(RS("fim"))
        w_codigo_externo  = RS("codigo_externo")
        w_objeto          = RS("objeto")
        w_observacao      = RS("observacao")
        w_dia_vencimento  = RS("dia_vencimento")
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCNPJ
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If instr("E",O) > 0 Then
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_sq_tipo_acordo.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, replace(SG,"CAD","GERAL"))
       End If
    End If
    AbreForm "Form", w_dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,replace(SG,"CAD","GERAL"),R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS1("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_acordo"" value=""" & w_sq_acordo &""">"
    ' Depois é necessário fazer com que o campo abaixo seja recebido na tela
    ShowHTML "<INPUT type=""hidden"" name=""w_valor_inicial"" value=""0"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Enquadramento</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para enquadramento orçamentário e contábil do acordo.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr>"
    SelecaoTipoAcordo "<u>T</u>ipo de acordo:", "T", "Selecione na lista o tipo de acordo adequado.", w_sq_tipo_acordo, null, w_cliente, "w_sq_tipo_acordo", replace(SG,"CAD","GERAL"), null
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoCC "<u>C</u>entro de custo:", "C", "Selecione na lista o centro de custo ao qual o contrato está vinculado.", w_sq_cc, null, "w_sq_cc", replace(SG,"CAD","GERAL")
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Vigência</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Informe a vigência do acordo. Apenas a data de início é obrigatória.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Data de início da vigência do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>I</u>nício:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Data de término da vigência do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><u>F</u>im:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "              </td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Outras informações</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Este bloco contém dados complementares sobre o acordo, necessários ao seu entendimento.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0>"
    ShowHTML "          <tr><td valign=""top"" ONMOUSEOVER=""popup('Dia padrão de vencimento das parcelas do acordo. Não informe se o contrato não possuir esta característica.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><U>D</U>ia de vencimento:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dia_vencimento"" size=""2"" maxlength=""2"" value=""" & w_dia_vencimento & """></td>"
    ShowHTML "              <td valign=""top"" ONMOUSEOVER=""popup('Código, número ou nome pelo qual o acordo é reconhecido pela outra parte.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>Código e<U>x</U>terno:<br><INPUT ACCESSKEY=""X"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo_externo"" size=""40"" maxlength=""60"" value=""" & w_codigo_externo & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" ONMOUSEOVER=""popup('Descreva o objeto do acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b><U>O</U>bjeto:<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_objeto"" cols=75 rows=""5"">" & w_objeto & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top"" ONMOUSEOVER=""popup('Observações gerais relevantes sobre o acordo.','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>O<U>b</U>servações:<br><TEXTAREA ACCESSKEY=""B"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_observacao"" cols=75 rows=""5"">" & w_observacao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td colspan=""3""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=3>"
    ShowHTML "          <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    ShowHTML "          <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
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

  Set w_sq_acordo_pai   = Nothing
  Set w_outra_parte     = Nothing
  Set w_sq_tipo_acordo  = Nothing
  Set w_sq_cc           = Nothing
  Set w_inicio          = Nothing
  Set w_fim             = Nothing
  Set w_codigo_externo  = Nothing
  Set w_objeto          = Nothing
  Set w_observacao      = Nothing
  Set w_dia_vencimento  = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de exclusão de acordo
REM -------------------------------------------------------------------------


REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim w_Null, w_chave, w_codigo

  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  If Instr("GCDGERAL,GCRGERAL,GCPGERAL", SG) > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        ' Configura variável para receber o código interno do acordo, gerado por trigger
        w_chave  = null
        w_codigo = null
          
        DML_PutAgree O, _
           Request("w_sq_acordo"),            w_cliente,                       Request("w_sq_tipo_acordo"), _
           Request("w_sq_cc"),                Request("w_inicio"),             Request("w_fim"), _
           Request("w_valor_inicial"),        Request("w_codigo_externo"),     Request("w_objeto"), _
           Request("w_observacao"),           Request("w_dia_vencimento"),     w_chave, w_codigo

        ScriptOpen "JavaScript"
        If O = "I" Then
           ' Recupera os dados para montagem correta do menu
           DB_GetMenuData RS1, w_menu
           ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_sq_acordo=" & w_chave & "&w_documento=" & w_codigo & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & RemoveTP(TP) & "';"
        ElseIf O = "E" Then
           ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=" & replace(SG,"GERAL","CAD") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
        Else
           ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
           DB_GetLinkData RS1, Session("p_cliente"), SG
           ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=A&w_sq_acordo=" & Request("w_sq_acordo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        End If
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "TIPOACORDO" Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DML_PutAgreeType O, _
           Request("w_sq_tipo_acordo"),       Request("w_sq_tipo_acordo_pai"), Request("w_cliente"), _
           Request("w_Nome"),                 Request("w_sigla"),              Request("w_modalidade"), _
           Request("w_prazo_indeterminado"),  Request("w_pessoa_juridica"),    Request("w_pessoa_fisica"), _
           Request("w_ativo")

        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  End If

  Set w_Null            = Nothing
  Set w_chave           = Nothing
  Set w_codigo          = Nothing
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
    Case "EXCLUIR"
       Excluir
    Case "GERAL"
       Geral
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

