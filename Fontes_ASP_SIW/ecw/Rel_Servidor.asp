<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Cargo.asp" -->
<!-- #INCLUDE FILE="DB_Area_Atuacao.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Servidor.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite lista de alunos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 29/08/2003, 08:48
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
Dim dbms, sp, RS, RS1, RS2, RS3
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor, w_Dir
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_Assinatura, w_Cliente, w_Classe, w_filter
Private Par, w_linha, w_pag

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
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Rel_Servidor.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"

If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

If O = "" Then O = "P" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
If Request("Regional") > "" Then Session("Regional") = Request("Regional") End If
If Request("Periodo") > ""  Then Session("Periodo") = Request("Periodo")   End If
VerificaParametros
Main

FechaSessao

Set w_pag           = Nothing
Set w_linha         = Nothing
Set w_filter        = Nothing
Set w_cor           = Nothing
Set w_classe        = Nothing
Set w_cliente       = Nothing

Set RS              = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set w_Cont          = Nothing
Set w_Pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_troca         = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de consulta de alunos
REM -------------------------------------------------------------------------
Sub Inicial

  Dim p_unidade, p_tipo
  Dim p_area_atuacao, p_cargo, p_escolaridade, p_local, p_local_trabalho
  Dim p_sexo, p_mat_ini, p_mat_fim
  Dim p_Ordena, w_regional, w_atual
  Dim w_tot1, w_tot2

  p_area_atuacao     = uCase(Request("p_area_atuacao")) 
  p_cargo            = uCase(Request("p_cargo")) 
  p_sexo             = uCase(Request("p_sexo")) 
  p_mat_ini          = uCase(Request("p_mat_ini")) 
  p_mat_fim          = uCase(Request("p_mat_fim")) 
  p_unidade          = uCase(Request("p_unidade"))
  p_tipo             = uCase(Request("p_tipo"))
  p_local            = uCase(Request("p_local"))
  p_escolaridade     = Request("p_escolaridade")
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" or O = "W" Then
     If Session("regional") = "" or Session("regional") = "00" Then
        DB_GetFuncRel RS1, Session("periodo"), null, p_local, p_unidade, p_area_atuacao, p_escolaridade, p_cargo, p_sexo, p_mat_ini, p_mat_fim
     Else
        DB_GetFuncRel RS1, Session("periodo"), Session("regional"), p_local, p_unidade, p_area_atuacao, p_escolaridade, p_cargo, p_sexo, p_mat_ini, p_mat_fim
     End If
     'If p_unidade & p_area_atuacao & p_cargo & p_sexo & p_mat_ini & p_mat_fim & p_escolaridade > "" Then
     '   w_filter = ""
     '   If p_unidade      > ""       Then w_filter = w_filter & " and co_unidade      = '" & p_unidade & "' "        End If
     '   If p_area_atuacao > ""       Then w_filter = w_filter & " and co_area_atuacao = '" & p_area_atuacao & "' "   End If
     '   If p_escolaridade > ""       Then w_filter = w_filter & " and ds_instrucao    = '" & p_escolaridade & "' "   End If
     '   If p_cargo        > ""       Then w_filter = w_filter & " and co_cargo        = '" & p_cargo & "' "          End If
     '   If p_sexo         > ""       Then w_filter = w_filter & " and tp_sexo         = '" & p_sexo & "' "           End If
     '   If p_mat_ini      > ""       Then w_filter = w_filter & " and dt_admissao     >= '" & cDate(p_mat_ini) & "' and dt_admissao <= '" & cDate(p_mat_fim) & "'"  End If
     '   RS1.Filter = Mid(w_filter,6,255)
     'End If
     If p_ordena = "" Then
        RS1.Sort = "regional, co_unidade, ds_funcionario, tp_sexo, dt_nascimento"
     Else
        RS1.Sort = "regional, co_unidade," & p_ordena
     End If
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     If p_Tipo = "S" Then
        CabecalhoWord w_cliente, "Quantitativo de Servidores", w_pag
     Else
        CabecalhoWord w_cliente, "Lista de Servidores", w_pag
     End If
     ExibeParametrosRel w_cliente
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        If O="P" Then
           Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
           Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
           ShowHTML "  if (theForm.regional[theForm.regional.selectedIndex].value == '00' && theForm.p_tipo[1].checked) { "
           ShowHTML "     alert('Para toda a rede de ensino não é possível listar os servidores. Escolha uma regional ou marque \""Apenas totais\"" no campo \""Exibir\""');"
           ShowHTML "     theForm.regional.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           Validate "p_mat_ini", "Matrícula - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_mat_fim", "Matrícula - data final", "DATA", "", "10", "10", "", "0123456789/"
           ShowHTML "  if ((theForm.p_mat_ini.value == '' && theForm.p_mat_fim.value != '') || (theForm.p_mat_ini.value != '' && theForm.p_mat_fim.value == '')) { "
           ShowHTML "     alert('Informe as datas de matrícula inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_mat_ini.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           CompData "p_mat_ini", "Matrícula - data inicial", "<=", "p_mat_fim", "Matrícula - data final"
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" or O = "W" Then
        BodyOpenClean "onLoad=document.focus();"
     Else
        BodyOpen "onLoad=document.focus();"
     End If
     If O = "L" Then
        If p_Tipo = "S" Then
           CabecalhoRelatorio w_cliente, "Quantitativo de Servidores"
        Else
           CabecalhoRelatorio w_cliente, "Lista de Servidores"
        End If
        ExibeParametrosRel w_cliente
        ShowHTML "<BR>"
     ElseIf O = "W" and p_tipo = "S" Then
        If p_Tipo = "S" Then
           CabecalhoWord w_cliente, "Quantitativo de Servidores", w_pag
        Else
           CabecalhoWord w_cliente, "Lista de Servidores", w_pag
        End If
        ExibeParametrosRel w_cliente
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    w_filter = ""  
    If p_area_atuacao > ""  Then 
       DB_GetAtuationAreaData RS, p_area_atuacao
       w_filter = w_filter & " [Área de atuação: <b>" & RS("ds_area_atuacao") & "</b>]&nbsp;" 
    End If
    If p_escolaridade > "" Then w_filter = w_filter & " [Escolaridade: <b>" & p_escolaridade & "</b>]&nbsp;" 
    If p_cargo > ""  Then 
       DB_GetPositionData RS, p_cargo
       w_filter = w_filter & " [Cargo: <b>" & RS("ds_cargo") & "</b>]&nbsp;" 
    End If
    If p_sexo > ""     Then w_filter = w_filter & " [Sexo: <b>" & p_sexo & "</b>]&nbsp;"                                 End If    
    If p_local = "S"   Then w_filter = w_filter & " [Local de trabalho: <b>Mesmo da unidade</b>]&nbsp;" 
    If p_local = "N"   Then w_filter = w_filter & " [Local de trabalho: <b>Diferente da unidade</b>]&nbsp;" 
    If p_mat_ini > ""  Then w_filter = w_filter & " [Dt.Matr: <b>" & p_mat_ini & "</b>-<b>" & p_mat_fim & "</b>]&nbsp;"  End If
    If w_filter > ""   Then ShowHTML "<tr><td colspan=7><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"  
    ShowHTML "<tr><td align=""center"" colspan=7>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      w_atual    = ""
      w_regional = "a"
      w_tot1     = 0
      w_tot2     = 0
      While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W" or p_tipo = "S")
         If w_regional <> RS1("regional") or w_atual <> RS1("co_unidade") Then
            If w_atual > "" Then
               If p_tipo = "S" or O = "W" Then ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>" End If
               w_tot2 = w_tot2 + w_tot1
               w_tot1 = 0
               If w_regional <> RS1("regional") Then
                  If p_tipo = "S" or O = "W" Then 
                     ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=7><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
                     ShowHTML "      <tr><td colspan=7><font size=""2""><b>&nbsp;</b></td></tr>"
                  End If
                  w_tot2 = 0
               End If
            End If
            If w_regional <> RS1("regional") Then
                w_linha = w_linha + 3
               ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=7><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
            End If
            ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><font size=""2""><b>Unidade: " & RS1("ds_unidade") & "</b></td></tr>"
            w_linha = w_linha + 2
            w_regional = RS1("regional")
            w_atual    = RS1("co_unidade")
            If p_tipo = "N" Then
               ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
               ShowHTML "          <td align=""center""><font size=""1""><b>Matrícula</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Nome do servidor</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Escolaridade</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Cargo Ocupado(SIGRH)</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Área de Atuação</font></td>"
               If p_mat_ini > "" Then ShowHTML "<td align=""center""><font size=""1""><b>Dt.Adm.</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Res/Trab</font></td>"
               ShowHTML "        </tr>"
            End If
         End If
         If w_linha > 30 and O = "W" Then
            If p_tipo = "N" Then
               ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=7><font size=""1""></b> Local de residência em relação ao local de trabalho</font></td>"
            End If
            ShowHTML "    </table>"
            ShowHTML "  </td>"
            ShowHTML "</tr>"
            ShowHTML "</table>"
            ShowHTML "</center></div>"
            If p_tipo = "N" Then 
               ShowHTML "    <br style=""page-break-after:always"">"
               w_linha = 9
               w_pag   = w_pag + 1
               If p_Tipo = "S" Then
                  CabecalhoWord w_cliente, "Quantitativo de Servidores", w_pag
               Else
                  CabecalhoWord w_cliente, "Lista de Servidores", w_pag
               End If
               ExibeParametrosRel w_cliente
            End If
            ShowHTML "<div align=center><center>"
            ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            If w_filter > ""  Then ShowHTML "<tr><td colspan=14><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>" End If
            ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><font size=""2""><b>Unidade: " & RS1("ds_unidade") & "</b></td></tr>"
            ShowHTML "</table>"
            If p_tipo = "N" Then
               ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
               ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
               ShowHTML "          <td align=""center""><font size=""1""><b>Matrícula</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Nome do servidor</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Escolaridade</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Cargo Ocupado(SIGRH)</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Área de Atuação</font></td>"
               If p_mat_ini > "" Then ShowHTML "<td align=""center""><font size=""1""><b>Dt.Adm.</font></td>"
               ShowHTML "          <td align=""center""><font size=""1""><b>Res/Trab<font></td>"
               ShowHTML "        </tr>"
            End If
         End If
         w_cor = conTrBgColor
         If p_tipo = "N" Then
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("nu_matricula_mec"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_funcionario"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_instrucao"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_cargo"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_area_atuacao"),"---") & "</td>"
            If p_mat_ini > "" Then ShowHTML "<td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("dt_admissao")),"---") & "</td>"
            If trim(RS1("bairro_func")) = trim(RS1("bairro_unidade")) Then
               p_local_trabalho = "Mesmo"
            Else
               p_local_trabalho = "Diferente"
            End If
            ShowHTML "        <td><font size=""1"">" & Nvl(p_local_trabalho,"---") & "</td>"
            w_linha = w_linha + 1
         End If
         RS1.MoveNext
         w_tot1  = w_tot1 + 1
      wend
    End If
    If (w_atual > "" and p_tipo = "S") or O = "W" Then
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>"
       w_tot2 = w_tot2 + w_tot1
       ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=7><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
       ShowHTML "      <tr bgcolor=""" & conTrBgcolor & """><td colspan=7><font size=""2""><b>TOTAL DE SERVIDORES: " & FormatNumber(RS1.RecordCount,0) & "</b></td></tr>"
    End If
    If p_tipo = "N" Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><font size=""1""><b>Res/Trab:</b> Local de residência em relação ao local de trabalho</font></td>" End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If O = "L" and p_tipo = "N" Then
       ShowHTML "<tr><td align=""center"" colspan=7>"
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
         
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelDuplic",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End IF
    SelecaoAtuacao "Ár<u>e</u>a Atuação:", "E", null, p_area_atuacao, null, "p_area_atuacao", null
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoEscolaridade "E<u>s</u>colaridade:", "S", null, p_escolaridade, null, "p_escolaridade", null
    SelecaoCargo "Car<u>g</u>o:", "T", null, p_cargo, null, "p_cargo", null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoSexo "Se<u>x</u>o:", "X", null, p_sexo, null, "p_sexo", null, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Local de trabalho:</b><br>"
    If p_local = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_local"" value=""""> Ambos<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""S""> Mesmo da residência<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""N"" checked> Diferente da residência"
    ElseIf p_local = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_local"" value=""""> Ambos<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""S"" checked> Mesmo da residência<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""N""> Diferente da residência"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_local"" value="""" checked> Ambos<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""S""> Mesmo da residência<input " & w_Disabled & " type=""radio"" name=""p_local"" value=""N""> Diferente da residência"
    End If
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Data de admissão: &nbsp;&nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_mat_ini"" value=""" & p_mat_ini & """ onKeyDown=""FormataData(this,event);""> a <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_mat_fim"" value=""" & p_mat_fim & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NU_MATRICULA_MEC" Then
       ShowHTML "          <option value=""NU_MATRICULA_MEC"" SELECTED>Matrícula<option value=""DT_ADMISSAO"">Data Admissão<option value=""TP_SEXO"">Sexo<option value="""">Nome"
    ElseIf p_Ordena="DS_FUNCIONARIO" Then
       ShowHTML "          <option value=""NU_MATRICULA_MEC"">Matrícula<option value=""DT_ADMISSAO"">Data Admissão<option value=""TP_SEXO"">Sexo<option value="""">Nome"
    ElseIf p_Ordena="DT_ADMISSAO" Then
       ShowHTML "          <option value=""NU_MATRICULA_MEC"">Matrícula<option value=""DT_ADMISSAO"" SELECTED>Data Admissão<option value=""TP_SEXO"">Sexo<option value="""">Nome"
    ElseIf p_Ordena="TP_SEXO" Then
       ShowHTML "          <option value=""NU_MATRICULA_MEC"">Matrícula<option value=""DT_ADMISSAO"">Data Admissão<option value=""TP_SEXO"" SELECTED>Sexo<option value="""">Nome"
    Else
       ShowHTML "          <option value=""NU_MATRICULA_MEC"">Matrícula<option value=""DT_ADMISSAO"">Data Admissão<option value=""TP_SEXO"">Sexo<option value="""" SELECTED>Nome"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir:</b><br>"
    If p_tipo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S""> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N"" checked> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S"" checked> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N""> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    End If
    ShowHTML "         <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Visualizar"" onClick=""document.Form.O.value='L'"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gerar Word"" onClick=""document.Form.O.value='W'"">"
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
  ShowHTML "</center>"
  Rodape

  Set w_regional         = Nothing 
  Set w_tot1             = Nothing 
  Set w_tot2             = Nothing 
  Set p_area_atuacao     = Nothing 
  Set p_cargo            = Nothing 
  Set p_sexo             = Nothing 
  Set p_local            = Nothing 
  Set p_escolaridade     = Nothing
  Set p_mat_ini          = Nothing 
  Set p_mat_fim          = Nothing 
  Set p_unidade          = Nothing
  Set p_tipo             = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "INICIAL"
       Inicial
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
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

