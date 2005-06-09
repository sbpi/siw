<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/CV_Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualPosto.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /Selecao.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Permite a vinculação do CV a um processo seletivo em andamento
REM Mail     : alex@sbpi.com.br
REM Criacao  : 23/07/2004 08:04
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
REM                   = V   : Envio
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
  ScriptOpen "JavaScript"
  ShowHTML " alert('Você precisa autenticar-se para utilizar o sistema!'); "
  ShowHTML " top.location.href='Default.htm'; "
  ScriptClose
End If

' Declaração de variáveis
Dim OraDatabase, RS, SQL
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura, w_troca, w_atual
Public w_Data_Banco, w_vinculo, w_nm_vinculo
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
R            = trim(uCase(Request("R")))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Selecao.asp?par="
w_Disabled   = " ENABLED "
w_troca      = uCase(Request("w_troca"))
w_vinculo    = uCase(Request("w_vinculo"))
w_nm_vinculo = uCase(Request("w_nm_vinculo"))

If O = "" Then O = "L" End If
If par="PONTUACAO"  and O = "A" and Request("w_sq_perfil_pontuacao") = "" Then O = "L" End If ' Configura o valor de O se for a tela de listagem
If par="CIVIL" and O = "A" and Request("w_sq_perfil") = "" and Request("w_sq_estado_civil") ="" Then O = "L" End If ' Configura o valor de O se for a tela de listagem
If par="VISUAL"  Then O = "L" End If ' Configura o valor de O se for a tela de listagem

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

'DATA DO BANCO DE DADOS
SQL = "SELECT TO_CHAR(SYSDATE,'DD/MM/YYYY,HH24:MM') DATA FROM DUAL"
ConectaBD
w_data_banco = RS("DATA")
DesconectaBD

Main

OraDatabase.close

Set w_atual     = Nothing
Set w_nm_vinculo= Nothing
Set w_vinculo   = Nothing
Set w_troca     = Nothing
Set w_Data_Banco= Nothing
Set OraDatabase = Nothing
Set RS          = Nothing
Set SQL         = Nothing
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
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visualizacao
  Dim p_numero

  p_numero                      = Request("p_numero")
  
  If P1 > "" Then
     Response.ContentType = "application/msword"
  Else 
     cabecalho
  End If
  
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>Posto de trabalho</TITLE>"
  ShowHTML "</HEAD>" 
  If P1 = "" Then 
     BodyOpen "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR>"
  If P1 = "" Then
     ShowHTML "  <TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""../" & conImgLogo & """>"
  End If
  ShowHTML "  <TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "Visualização de processo seletivo</FONT></B>"
  ShowHTML "<TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & w_data_banco & "</font></B>"
  If P1 = "" Then
     ShowHTML "&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""../images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&P1=1&p_numero=" & p_numero & "','VisualCurriculoWord','toolbar=yes menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</TABLE>"
  ShowHTML "<HR>"
  
  ' Chama a função de visualização dos dados do usuário, na opção "Listagem"
  
  VisualPerfil p_numero
  
  If P1 = "" Then
     Rodape
  End If
  
  Set p_numero          = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------



REM =========================================================================
REM Rotina de inclusão de novas solicitações
REM -------------------------------------------------------------------------
Sub Inicial

  Dim RS1, RS2
  Dim w_submenu, w_documento, w_sq_pagina
  Dim p_ordena
  
  Dim p_nome, p_projeto, p_numero, p_numero_pontuacao
  Dim w_nome,w_co_grupo_atividade,w_co_faixa,w_username_cadastro,w_handle_projeto
  Dim w_atribuicoes, w_habilidades,w_remuneracao_base,w_remuneracao_minima,w_remuneracao_maxima
  Dim w_simulacao, w_pontuacao_minima,w_trocaFaixa
  Dim w_nome_atividade, w_nome_faixa,  w_codigo
  
  p_numero           = Request("p_numero")  
  p_ordena           = uCase(Request("p_ordena"))
  p_nome             = uCase(Request("p_nome"))
  p_projeto          = uCase(Request("p_projeto"))
  w_trocaFaixa       = trim(Request("w_trocaFaixa"))
  p_numero_pontuacao = trim(Request("p_numero_pontuacao"))
  
  If w_troca > "" or w_trocaFaixa > "" then
     w_nome                 = Request("w_nome")
     w_codigo               = Request("w_codigo")
     w_co_grupo_atividade   = Request("w_co_grupo_atividade")
     w_co_faixa             = Request("w_co_faixa")
     w_handle_projeto       = Request("w_handle_projeto")
     w_remuneracao_base     = Request("w_remuneracao_base")
     w_remuneracao_minima   = Request("w_remuneracao_minima")       
     w_remuneracao_maxima   = Request("w_remuneracao_maxima")
     w_simulacao            = Request("w_simulacao")
     w_pontuacao_minima     = Request("w_pontuacao_minima")
     If Instr("I",O) > 0 Then
         SQL = "select g.atribuicoes,g.habilidades,seguranca.fvalor(g.remuneracao_base) b_base, " & VbCrLf & _
               "       seguranca.fvalor(g.remuneracao_minima) b_minima, " & VbCrLf & _
               "       seguranca.fvalor(g.remuneracao_maxima) b_maxima, " & VbCrLf & _
               "       seguranca.fvalor(g.pontuacao_minima,1) b_pontuacao_minima " & VbCrLf & _
               " from rh_grupo_faixa g " & VbCrLf & _
               " where g.co_grupo_atividade = '" & w_co_grupo_atividade & "' " & VbCrLf &_
              "   and g.co_faixa           = '" & w_co_faixa & "' " 
         ConectaBD
         w_atribuicoes          = RS("atribuicoes")
         w_habilidades          = RS("habilidades")
         w_remuneracao_base     = RS("b_base")
         w_remuneracao_minima   = RS("b_minima")       
         w_remuneracao_maxima   = RS("b_maxima")
         w_pontuacao_minima     = RS("b_pontuacao_minima")
         DesconectaBD
     End If
  ElseIf O = "L" Then
     SQL = "select a.sq_perfil,a.sq_solicitacao,a.nome, b.nome projeto,Nvl(a.codigo,'---') codigo " & VbCrLf & _
           "from rh_perfil a, " & VbCrLf & _
           "     corporativo.ct_cc b " & VbCrLf & _
           "where a.handle_projeto    = b.handle " & VbCrLf
     ' Verifica se há um filtro ativo
     If p_nome                        > ""   Then SQL = SQL & "  and seguranca.acentos(upper(a.nome)) like seguranca.acentos(upper('%" & p_nome & "%')) " & VbCrLf End If
     If p_projeto                     > ""   Then SQL = SQL & "  and seguranca.acentos(upper(b.nome)) like seguranca.acentos(upper('%" & p_projeto & "%')) " & VbCrLf End If
     If p_Ordena = "" Then 
        SQL = SQL & "order by a.nome " & VbCrLf 
     Else
        SQL = SQL & "order by " & p_Ordena & " " & VbCrLf 
     End If
     ConectaBD
  ElseIf InStr("AE",O) > 0 Then
     ' Recupera a pontuação 
     SQL = "select * from rh_perfil_pontuacao where sq_perfil = " & p_numero
     ConectaBD
     p_numero_pontuacao = Rs("sq_perfil_pontuacao")
     DesconectaBD

     'Recupera o conjunto de informações comum a todos os serviços
     SQL = "select a.nome,a.codigo, a.co_grupo_atividade,a.co_faixa,a.handle_projeto, " & VbCrLf & _
           "       seguranca.fvalor(a.remuneracao_base) b_base, " & VbCrLf & _
           "       seguranca.fvalor(a.remuneracao_minima) b_minima, " & VbCrLf & _
           "       seguranca.fvalor(a.remuneracao_maxima) b_maxima, " & VbCrLf & _
           "       a.simulacao,seguranca.fvalor(a.pontuacao_minima,1) b_pontuacao_minima, " & VbCrLf & _
           "       c.nome nm_vinculo, v.nome b_atividade, g.nome b_faixa " & VbCrLf & _
           " from rh_perfil                           a " & VbCrLf & _
           "      inner      join rh_grupo_atividade  v on (a.sq_tipo_vinculo    = v.sq_tipo_vinculo and " & VbCrLf & _
           "                                                a.co_grupo_atividade = v.co_grupo_atividade) " & VbCrLf & _
           "      inner      join corporativo.ct_cc   b on (a.handle_projeto     = b.handle) " & VbCrLf & _
           "      inner      join sg_tipo_vinculo     c on (a.sq_tipo_vinculo    = c.sq_tipo_vinculo) " & VbCrLf & _
           "      inner      join rh_grupo_faixa      g on (a.sq_tipo_vinculo    = g.sq_tipo_vinculo and " & VbCrLf & _
           "                                                a.co_grupo_atividade = g.co_grupo_atividade and " & VbCrLf & _
           "                                                a.co_faixa           = g.co_faixa) " & VbCrLf & _
           " where a.sq_perfil = " & p_numero & " " & VbCrLf
     ConectaBD
     w_nome_atividade       = RS("b_atividade")
     w_nm_vinculo           = RS("nm_vinculo")
     w_nome_faixa           = RS("b_faixa")  
     w_nome                 = RS("nome")
     w_co_grupo_atividade   = RS("co_grupo_atividade")
     w_co_faixa             = RS("co_faixa")
     w_handle_projeto       = RS("handle_projeto")
     w_remuneracao_base     = RS("b_base")
     w_remuneracao_minima   = RS("b_minima")       
     w_remuneracao_maxima   = RS("b_maxima")
     w_simulacao            = RS("simulacao")
     w_pontuacao_minima     = RS("b_pontuacao_minima")
     w_codigo               = RS("codigo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
     ' tratando as particularidades de cada serviço
     ScriptOpen "JavaScript"
     CheckBranco
     FormataValor
     FormataData
     FormataDataHora
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_handle_projeto", "Projeto", "SELECT", "1", "1", "10", "", "1"
        Validate "w_nome", "Nome do posto", "1", "1", "1", "60", "1", "1"
        Validate "w_codigo", "Código do processo seletivo", "1", "", "1", "50", "1", "1"
        If InStr("I",O) > 0 Then
           Validate "w_vinculo", "Modalidade", "SELECT", "1", "1", "10", "", "1"
           Validate "w_co_grupo_atividade", "Grupo atividade", "SELECT", "1", "1", "3", "1", "1"
           Validate "w_co_faixa", "Faixa", "SELECT", "1", "1", "3", "1", "1"
        End If
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão desta solicitação?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "60", "1", "1"
        Validate "p_projeto", "Projeto", "1", "", "1", "60", "1", "1"
     End If
     If O = "A" Then
        ShowHTML "  theForm.Botao.disabled=true;"
     Else
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_handle_projeto.focus()';"
  ElseIf InStr("E",O) > 0 Then
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
    AbreSessao
    ' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
    SQL = "select count(b.sq_pagina) Existe from sg_menu a, sg_menu b where a.sq_pagina = b.sq_pagina_pai and b.ultimo_nivel = 'S' and a.sigla = '" & SG & "'"
    Set RS1 = OraDatabase.CreateDynaset(SQL,0)
    If RS1("Existe") > 0 Then
       w_submenu = "Existe"
    Else
       w_submenu = ""
    End If
    'Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome do posto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Código proc. seletivo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Projeto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        ShowHTML "        <td align=""left""><font size=""1"" >" & RS("nome") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"" >" & RS("codigo") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"" >" & RS("projeto") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "visual&R=CV_Perfil.asp?par=INICIAL&O=L&p_numero=" & RS("sq_perfil") & "&P1=&P2=&P3=&P4=&TP=<img src=../images/folder/SheetLittle.gif BORDER=0>Exibir posto&SG=RHPERVIS"" TARGET=""_blank"">Exibir</a>&nbsp;"
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
    If InStr("E",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, "CVPERGER", w_pagina&par, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value=""" & w_troca &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_trocaFaixa"" value=""" & w_trocaFaixa &""">"
        
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr valign=""top""><td><font size=""1""><b>Simulação:</b><br>"
    If w_simulacao  =  "" or w_simulacao = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_simulacao"" value=""S"" > Sim <input " & w_Disabled & " type=""radio"" name=""w_simulacao"" value=""N"" checked> Não"
    ElseIf w_simulacao = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_simulacao"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_simulacao"" value=""N""> Não"    
    End If
    ShowHTML "      <tr valign=""top"">"
    SelecaoCC "<U>P</U>rojeto:", "p", null, w_handle_projeto, null, "w_handle_projeto", "fitoca='S' or substr(nome,1,1)='9'", null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>N</U>ome do posto:</b><br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_nome"" size=""30"" maxlength=""60"" value=""" & w_nome & """>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>C</U>ódigo do processo seletivo:</b><br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_codigo"" size=""30"" maxlength=""50"" value=""" & w_codigo & """>"
    If Instr("I",O) > 0 Then
       ShowHTML "      <tr valign=""top"">"
       SelecaoVinculo "<u>M</u>odalidade:", "M", null, w_vinculo, null, "w_vinculo", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_co_grupo_atividade'; document.Form.submit();"""
       ShowHTML "      <tr valign=""top"">"
       SQL = "select * from rh_grupo_atividade where sq_tipo_vinculo = '" & w_vinculo & "' order by nome "
       ConectaBD
       ShowHTML "          <td valign=""top""><font size=""1""><b><U>G</U>rupo:<br><SELECT " & w_Disabled & " ACCESSKEY=""G"" class=""BTM"" name=""w_co_grupo_atividade"" size=""1"" onChange=""document.Form.w_troca.value='troca';document.Form.action='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "'; document.Form.submit();"">"
       ShowHTML "          <OPTION VALUE=""""> --- "
       While Not RS.EOF
          If w_co_grupo_atividade = rs("co_grupo_atividade") Then
              ShowHTML "          <OPTION VALUE=" & RS("co_grupo_atividade") & " selected>" & RS("nome")      
          Else
              ShowHTML "          <OPTION VALUE=" & RS("co_grupo_atividade") & ">" & RS("nome")
          End If
          RS.MoveNext
       Wend
       DesconectaBD
       ShowHTML "          </SELECT></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b><U>F</U>aixa:<br><SELECT " & w_Disabled & " ACCESSKEY=""F"" class=""BTM"" name=""w_co_faixa"" size=""1"" onChange=""document.Form.w_trocaFaixa.value='troca';document.Form.action='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "'; document.Form.submit();"">"
       SQL = "select * from rh_grupo_faixa  where sq_tipo_vinculo = '" & w_vinculo & "' and co_grupo_atividade = '" & w_co_grupo_atividade & "' order by nome "
       ConectaBD
       ShowHTML "          <OPTION VALUE=""""> --- "
       While Not RS.EOF      
          If w_co_faixa = rs("co_faixa") then
             ShowHTML "          <OPTION VALUE=" & RS("co_faixa") & " selected>" & RS("nome")      
          Else
              ShowHTML "          <OPTION VALUE=" & RS("co_faixa") & ">" & RS("nome")
          End if
          RS.MoveNext
       Wend
       DesconectaBD
       ShowHTML "          </SELECT></td>"
    Else
       ShowHTML "      <tr valign=""top"" colspan=2><td valign=""top""><font size=""1""><b>Modalidade:<br></b>" & w_nm_vinculo
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Grupo:<br></b>" & w_nome_atividade
       ShowHTML "          <td valign=""top""><font size=""1""><b>Faixa:<br></b>" & w_nome_faixa
    End If
    ShowHTML "      </table>"
    ShowHTML "      <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Pontuação minima:<br><INPUT DISABLED class=""BTM"" type=""text"" name=""w_pontuacao_minima"" size=""6"" maxlength=""6"" value=""" & w_pontuacao_minima & """ onKeyDown=""FormataValor(this,5,1, event)""></td>" 
    ShowHTML "          <td valign=""top""><font size=""1""><b>Remuneração Base:<br><INPUT DISABLED class=""BTM"" type=""text"" name=""w_remuneracao_base"" size=""18"" maxlength=""20"" value=""" & w_remuneracao_base & """ onKeyDown=""FormataValor(this,10,2, event)""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Remuneração Minima:<br><INPUT DISABLED class=""BTM"" type=""text"" name=""w_remuneracao_minima"" size=""18"" maxlength=""20"" value=""" & w_remuneracao_minima & """ onKeyDown=""FormataValor(this,10,2, event)""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Remuneração Maxima:<br><INPUT DISABLED class=""BTM"" type=""text"" name=""w_remuneracao_maxima"" size=""18"" maxlength=""20"" value=""" & w_remuneracao_maxima & """ onKeyDown=""FormataValor(this,10,2, event)""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    If O = "I" Then
       ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    End If
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm  "Form", w_Pagina & par, "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, "L"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""BTM"" type=""text"" name=""p_nome"" size=""30"" maxlength=""30"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>P</U>rojeto:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""BTM"" type=""text"" name=""p_projeto"" size=""30"" maxlength=""30"" value=""" & p_projeto & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdena:<br><SELECT READONLY ACCESSKEY=""O"" class=""BTM"" name=""p_ordena"" size=""1"">"
    If p_Ordena="a.nome" Then
       ShowHTML "          <option value="""">Nome<option value=""a.nome"" SELECTED>Nome<option value=""b.nome"">Projeto"
    ElseIf p_Ordena="b.nome" Then
       ShowHTML "          <option value="""">Nome<option value=""a.nome"">Nome<option value=""b.nome"" SELECTED>Projeto"
    Else
       ShowHTML "          <option value="""" SELECTED>--<option value=""a.nome"">Nome<option value=""b.nome"">Projeto"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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

  Set p_nome                    = Nothing
  Set p_projeto                 = Nothing
  Set p_numero                  = Nothing
  Set w_nome                    = Nothing
  Set w_codigo                  = Nothing
  Set w_co_grupo_atividade      = Nothing
  Set w_co_faixa                = Nothing
  Set w_handle_projeto          = Nothing
  Set w_remuneracao_base        = Nothing    
  Set w_remuneracao_minima      = Nothing
  Set w_remuneracao_maxima      = Nothing
  Set w_simulacao               = Nothing
  Set w_pontuacao_minima        = Nothing    
  Set w_submenu                 = Nothing
  Set w_sq_pagina               = Nothing
  Set RS1                       = Nothing
  Set RS2                       = Nothing
  Set p_ordena                  = Nothing
  Set p_numero_pontuacao        = Nothing
  Set w_trocaFaixa              = Nothing
  
End Sub
REM =========================================================================
REM Fim da tela de inclusão de solicitações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabelas faixa etária
REM -------------------------------------------------------------------------
Sub etaria

  Dim p_numero,w_minimo,w_maximo,w_obrigatorio_faixa
  Dim w_exige_conta_bancaria, w_exige_beneficios, w_exige_dependentes, w_exige_historico
  Dim w_atribuicoes, w_habilidades, w_sexo, w_obrigatorio_sexo
  
  p_numero                      = Request("p_numero")
    
  If O = "" Then O="A" end if
  
  'Recupera o conjunto de informações comum a todos os serviços
  SQL = "select a.atribuicoes, a.habilidades, b.sexo, b.obrigatorio, " & VbCrLf & _
        "       a.exige_conta_bancaria, a.exige_beneficios, a.exige_dependentes, a.exige_historico, " & VbCrLf & _
        "       c.minimo, c.maximo, c.obrigatorio obriga_faixa " & VbCrLf & _
        " from rh_perfil                              a " & VbCrLf & _
        "      left outer join rh_perfil_sexo         b on (a.sq_perfil = b.sq_perfil) " & VbCrLf & _
        "      left outer join rh_perfil_faixa_etaria c on (a.sq_perfil = b.sq_perfil) " & VbCrLf & _
        " where a.sq_perfil          = " & p_numero
  ConectaBD
  w_atribuicoes          = RS("atribuicoes")
  w_habilidades          = RS("habilidades")
  w_exige_conta_bancaria = RS("exige_conta_bancaria")
  w_exige_beneficios     = RS("exige_beneficios")
  w_exige_dependentes    = RS("exige_dependentes")    
  w_exige_historico      = RS("exige_historico")
  w_sexo                 = RS("sexo")
  w_obrigatorio_sexo     = RS("obrigatorio") 
  w_minimo              = RS("minimo")
  w_maximo              = RS("maximo")
  w_obrigatorio_faixa   = RS("obriga_faixa")
  DesconectaBD
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
        Validate "w_atribuicoes", "Atribuições", "1", "1", "5", "4000", "1", "1"
        Validate "w_habilidades", "Habilidades", "1", "1", "5", "4000", "1", "1"
        Validate "w_minimo", "Faixa mínimo", "1", "", "1", "3", "", "0123456789"
        Validate "w_maximo", "Faixa maximo", "1", "", "1", "3", "", "0123456789"
        CompValor "w_minimo", "Faixa mínimo", "<=", "w_maximo", "Faixa maximo"
        ShowHTML "  if (parseFloat(theForm.w_minimo.value)>parseFloat(theForm.w_maximo.value)) {"
        ShowHTML "     alert('A faixa etária miníma não pode ser maior que a maxima.');"
        ShowHTML "     theForm.w_minimo.focus();"
        ShowHTML "     return (false);" 
        ShowHTML "  }; "
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ShowHTML "  theForm.Botao.disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     BodyOpen "onLoad=document.Form.w_atribuicoes.focus();"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, w_pagina&par, O
    ShowHTML MontaFiltro("POST")
        
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Atribuições:<br><TEXTAREA CLASS=""STI"" rows=5 cols=70 name=w_atribuicoes>" & w_atribuicoes & "</TEXTAREA>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Habilidades:<br><TEXTAREA CLASS=""STI"" rows=5 cols=70 name=w_habilidades>" & w_habilidades & "</TEXTAREA>"
    ShowHTML "      <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr valign=""top""><td valign=""top""><font size=""1""><b>Faixa etária:<br>"
    ShowHTML "              <INPUT ACCESSKEY=""I"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_minimo"" size=""3"" maxlength=""3"" value=""" & w_minimo & """ >"
    ShowHTML "              a <INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_maximo"" size=""3"" maxlength=""3"" value=""" & w_maximo & """ ></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Se faixa etaria indicada, recupera apenas candidatos nela contidos?</b><br>"
    If w_obrigatorio_faixa = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_faixa"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_faixa"" value=""S"" checked> Sim "
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_faixa"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_faixa"" value=""S""> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Sexo:</b><br>"    
    if w_sexo = "F" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F"" checked> Feminino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M""> Masculino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""N""> Tanto faz"
    ElseIf w_sexo = "M" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F""> Feminino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M"" checked> Masculino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""N""> Tanto faz"
    Else 
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""F""> Feminino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""M""> Masculino<br><input " & w_Disabled & " type=""radio"" name=""w_sexo"" value=""N"" checked> Tanto faz"
    End If
    ShowHTML "          <td valign=""top""><font size=""1""><b>Se o sexo foi indicado, recupera apenas candidados desse sexo?</b><br>"
    if w_obrigatorio_sexo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""N"" checked> Não<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""S""> Sim<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""M""> Não se aplica"
    ElseIf w_obrigatorio_sexo = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""N""> Não<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""S"" checked> Sim<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""M""> Não se aplica"
    else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""N""> Não<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""S""> Sim<br><input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_sexo"" value=""M"" checked> Não se aplica"
    End If
    ShowHTML "      <tr valign=""top""><td valign=""top""><font size=""1""><b>Exige conta bancária:</b><br>"    
    If w_exige_conta_bancaria  =  "" or w_exige_conta_bancaria = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_conta_bancaria"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_conta_bancaria"" value=""N""> Não"
    ElseIf w_exige_conta_bancaria = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_conta_bancaria"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_conta_bancaria"" value=""N"" checked> Não"    
    End If
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exige benefícios:</b><br>"    
    If w_exige_beneficios  =  "" or w_exige_beneficios = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_beneficios"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_beneficios"" value=""N""> Não"
    ElseIf w_exige_beneficios = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_beneficios"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_beneficios"" value=""N"" checked> Não"    
    End If
    ShowHTML "      <tr valign=""top""><td valign=""top""><font size=""1""><b>Exige dependentes:</b><br>"    
    If w_exige_dependentes  =  "" or w_exige_dependentes = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_dependentes"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_dependentes"" value=""N""> Não"
    ElseIf w_exige_dependentes = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_dependentes"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_dependentes"" value=""N"" checked> Não"    
    End If
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exige históricos:</b><br>"
    If w_exige_historico  =  "" or w_exige_historico = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_historico"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_historico"" value=""N""> Não"
    ElseIf w_exige_historico = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_exige_historico"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""w_exige_historico"" value=""N"" checked> Não"    
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    'ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
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

  Set p_numero               = Nothing
  Set w_atribuicoes          = Nothing
  Set w_habilidades          = Nothing
  Set w_exige_conta_bancaria = Nothing
  Set w_exige_beneficios     = Nothing
  Set w_exige_dependentes    = Nothing
  Set w_exige_historico      = Nothing
  Set w_minimo               = Nothing
  Set w_maximo               = Nothing    
  Set w_obrigatorio_faixa    = Nothing

End Sub
REM =========================================================================
REM Fim da tabela faixa etária
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabelas estado civil
REM -------------------------------------------------------------------------
Sub civil

  Dim p_numero,w_sq_perfil,w_nome
  Dim w_sq_estado_civil,w_obrigatorio_civil
  
  p_numero              = Request("p_numero")
  w_sq_estado_civil     = Request("w_sq_estado_civil")     
  w_sq_perfil           = Request("w_sq_perfil")
  
  If O = "" Then O="L" end if
  
  If InStr("L",O) > 0 Then
    SQL = "select e.nome,decode(c.obrigatorio,'S','SIM','NÃO') obriga_civil, " &_
          "       c.sq_estado_civil,p.sq_perfil " &_
          "  from rh_perfil_estado_civil c, " &_
          "       co_estado_civil e,rh_perfil p " &_
          " where p.sq_perfil = c.sq_perfil " &_
          "   and e.sq_estado_civil = c.sq_estado_civil " &_
          "   and p.sq_perfil = " & p_numero
     ConectaBD      
  elseIf InStr("AE",O) > 0 Then
    SQL = "select e.nome,c.obrigatorio obriga_civil, " &_
          "       c.sq_estado_civil,p.sq_perfil " &_
          "  from rh_perfil_estado_civil c, " &_
          "       co_estado_civil e,rh_perfil p " &_
          " where p.sq_perfil = c.sq_perfil " &_
          "   and e.sq_estado_civil = c.sq_estado_civil " &_
          "   and p.sq_perfil = " & p_numero &_ 
          "   and c.sq_estado_civil = " & w_sq_estado_civil
    ConectaBD
    w_nome                = RS("nome")
    w_sq_estado_civil     = RS("sq_estado_civil")
    w_obrigatorio_civil   = RS("obriga_civil")
    w_sq_perfil           = RS("sq_perfil")
  end if
  DesconectaBD
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
        If InStr("I",O) > 0 Then 
            Validate "w_sq_estado_civil", "Estado civil", "SELECT", "1", "1", "10", "", "1"
        end if
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     End If
     ShowHTML "  theForm.Botao.disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("E",O) > 0 Then
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_numero=" & p_numero & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Estado civil</font></td>"
    ShowHTML "          <td><font size=""1""><b>Obrigatório</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("obriga_civil") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_perfil=" & rs("sq_perfil") & "&p_numero=" & p_numero & "&w_sq_estado_civil=" &  rs("sq_estado_civil") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_perfil=" & rs("sq_perfil") & "&p_numero=" & p_numero & "&w_sq_estado_civil=" &  rs("sq_estado_civil") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil"" value=""" & w_sq_perfil &""">"
        
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    If Instr("I",O) > 0 Then
        sql = ""
        SQL = "select * from co_estado_civil " &_
              " where sq_estado_civil not in (select sq_estado_civil from rh_perfil_estado_civil " &_
              "                                where sq_perfil = " & p_numero & ") "
        ConectaBD                
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>E</U>stado civil:<br><SELECT READONLY ACCESSKEY=""E"" class=""BTM"" name=""w_sq_estado_civil"" size=""1"">"
        ShowHTML "          <OPTION VALUE=""""> --- "
        While Not RS.EOF      
          if w_sq_estado_civil = rs("sq_estado_civil") then
            ShowHTML "          <OPTION VALUE=" & RS("sq_estado_civil") & " selected>" & RS("nome")      
          else
            ShowHTML "          <OPTION VALUE=" & RS("sq_estado_civil") & ">" & RS("nome")
          end if
          RS.MoveNext
        Wend
        DesconectaBD
        ShowHTML "          </SELECT></td>"
        ShowHTML "      </tr>"
    else
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Estado civil:</b><br>" & w_nome
        ShowHTML "      <INPUT type=""hidden"" name=""w_sq_estado_civil"" value=""" & w_sq_estado_civil &""">"
    end if 
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obrigatório estado civil:</b><br>"    
    if w_obrigatorio_civil = "" or w_obrigatorio_civil = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_civil"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_civil"" value=""S""> Sim"
    ElseIf w_obrigatorio_civil = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_civil"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio_civil"" value=""S"" checked> Sim"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_perfil=" & w_sq_perfil & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_numero=" &  p_numero & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set p_numero              = Nothing
  Set w_sq_estado_civil     = Nothing
  Set w_obrigatorio_civil   = Nothing
  Set w_sq_perfil           = Nothing
  Set w_nome                = Nothing
         
End Sub
REM =========================================================================
REM Fim da tabela estado civil
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de pontuação
REM -------------------------------------------------------------------------
Sub pontuacao

  Dim w_sq_perfil_pontuacao,p_numero,w_sq_requisito,w_co_grupo_atividade
  Dim w_co_faixa,w_pontuacao_perfil,w_pontuacao_final,w_obrigatorio,w_nome_requisito
  Dim w_experiencia,w_tempo_minimo_anos,w_curso_tecnico,w_readonly
   
  p_numero                      = Trim(Request("p_numero"))
  w_sq_perfil_pontuacao         = Request("w_sq_perfil_pontuacao")
     
  If O = "" Then O="L" end if
  
  If InStr("L",O) Then 
     SQL = "select a.co_grupo_categoria, a.co_categoria, a.nome nm_categoria, " & VbCrLf & _
           "       c.sq_tipo_vinculo, c.co_grupo_atividade, seguranca.fvalor(c.pontuacao_minima,1) pt_min, " & VbCrLf & _
           "       d.requisito_basico, " & VbCrLf & _
           "       p.sq_perfil_pontuacao,p.sq_perfil,p.sq_requisito, " & VbCrLf & _
           "       p.co_grupo_atividade,p.co_faixa,nvl(seguranca.fvalor(p.pontuacao_perfil,1),0) b_perfil, " & VbCrLf & _
           "       nvl(seguranca.fvalor(p.pontuacao_final,1),0) b_final,p.obrigatorio, " & VbCrLf & _
           "       r.nome, r.curso_tecnico, r.experiencia, r.tempo_minimo_anos, r.idioma, r.escolaridade, r.conhecimento_especifico " & VbCrLf & _
           "    from rh_perfil_pontuacao              p " & VbCrLf & _
           "         inner   join rh_requisito        r on (p.sq_requisito = r.sq_requisito) " & VbCrLf & _
           "           inner join rh_categoria        a on (r.co_grupo_categoria = a.co_grupo_categoria and " & VbCrLf & _
           "                                                r.co_categoria       = a.co_categoria) " & VbCrLf & _
           "           inner join rh_perfil           b on (p.sq_perfil          = b.sq_perfil) " & VbCrLf & _
           "           inner join rh_grupo_faixa      c on (b.sq_tipo_vinculo    = c.sq_tipo_vinculo and " & VbCrLf & _
           "                                                b.co_grupo_atividade = c.co_grupo_atividade and " & VbCrLf & _
           "                                                b.co_faixa           = c.co_faixa) " & VbCrLf & _
           "           inner join rh_requisito_pontos d on (r.sq_requisito       = d.sq_requisito and " & VbCrLf & _
           "                                                b.sq_tipo_vinculo    = d.sq_tipo_vinculo and " & VbCrLf & _
           "                                                b.co_grupo_atividade = d.co_grupo_atividade and " & VbCrLf & _
           "                                                b.co_faixa           = d.co_faixa) " & VbCrLf & _
           " where p.sq_perfil = " & p_numero & " " & VbCrLf & _
           "order by a.co_grupo_categoria, a.co_categoria, a.nome, r.nome" & VbCrLf
     ConectaBD
  ElseIf (O = "A" or O = "E") Then               
     SQL = "select p.sq_perfil_pontuacao,p.sq_perfil,p.sq_requisito, "&_
           "       decode(p.experiencia,'N','Não exigido',decode(p.experiencia,'T','Técnica',decode(p.experiencia,'G','Gerêncial'))) b_experiencia, " &_
           "       p.co_grupo_atividade,p.co_faixa,seguranca.fvalor(p.pontuacao_perfil,1) b_perfil, " &_
           "       seguranca.fvalor(p.pontuacao_final,1) b_final,p.obrigatorio,r.nome,p.tempo_minimo_anos, " &_
           "       decode(p.curso_tecnico,'0','Não exigido',decode(p.curso_tecnico,'1','Até 40 horas',decode(p.curso_tecnico,'2','Acima de 40 horas'))) b_curso_tecnico " &_
           "  from rh_perfil_pontuacao p,rh_requisito r " &_
           " where p.sq_requisito = r.sq_requisito " &_
           "   and p.sq_perfil    = " & p_numero &_
           "   and p.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao
     ConectaBD
     w_sq_perfil_pontuacao  = RS("sq_perfil_pontuacao")
     w_sq_requisito         = RS("sq_requisito")
     w_co_grupo_atividade   = RS("co_grupo_atividade")    
     w_co_faixa             = RS("co_faixa")
     w_pontuacao_perfil     = RS("b_perfil")
     w_pontuacao_final      = RS("b_final")
     w_obrigatorio          = RS("obrigatorio")
     w_nome_requisito       = RS("nome")
     w_experiencia          = RS("b_experiencia")
     w_tempo_minimo_anos    = RS("tempo_minimo_anos")
     w_curso_tecnico        = RS("b_curso_tecnico")
     DesconectaBD
     SQL = ""
     SQL = "select seguranca.fvalor(pontuacao_perfil_basico,1) b_pontuacao_perfil_basico "&_
           "  from rh_requisito_pontos r "&_
           " where r.co_grupo_atividade = '" & w_co_grupo_atividade & "' " &_
           "   and r.co_faixa           = '" & w_co_faixa & "' " &_
           "   and r.sq_requisito       = " & w_sq_requisito
     ConectaBD
     if rs("b_pontuacao_perfil_basico") > "" then
        w_pontuacao_perfil = rs("b_pontuacao_perfil_basico")
     end if   
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
        Validate "w_pontuacao_perfil", "Pontuação perfil", "1", "", "3", "12", "", "1"
        Validate "w_pontuacao_final", "Pontuação final", "1", "", "3", "12", "", "1"
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
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr valign=""top"">"
  If O = "L" Then
    ShowHTML "    <td><font size=""1"">Clique <a class=""HL"" href=""RHCV_grupo.asp?par=ExibeGrupo&O=L&w_vinculo=" & RS("sq_tipo_vinculo") & "&w_co_grupo_atividade=" & RS("co_grupo_atividade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>aqui</a> para detalhar os dados do grupo."
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=2>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Requisito</font></td>"
    ShowHTML "          <td><font size=""1""><b>Pontuação<br>Requisito</font></td>"
    ShowHTML "          <td width=""20%""><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_atual = ""
      While Not RS.EOF
        If w_atual <> RS("co_categoria") Then
           ShowHTML "      <tr bgcolor=""#C0C0C0""><td colspan=4><font size=""1""><b>" & RS("nm_categoria") & "</b></td>"
           w_atual = RS("co_categoria")
        End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("b_final")
        If RS("requisito_basico") = "S" or RS("obrigatorio") = "S" Then ShowHTML "*" End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If RS("requisito_basico") = "S" Then
           ShowHTML "          ---&nbsp"
        Else
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&p_numero=" & p_numero & "&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        End If
        If RS("experiencia") <> "N" Then
           ShowHTML "          <u class=""HL"" titlle=""Seleção de cargos"" style=""cursor:hand;"" onclick=""javascript:window.open('" & w_Pagina &"EP&R=" & w_Pagina &"EP&O=L&w_sq_perfil_pontuacao=" & RS("sq_perfil_pontuacao") & "&p_numero=" & p_numero &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Experiência profissional&SG=CVEXPPROF','Experiência','toolbars=no,width=600,height=350,top=30,left=50,scrollbars=yes,resizable=yes')"">EP</u>&nbsp"
        End If
        If RS("co_grupo_categoria") <> "F" Then
           '   ShowHTML "          <u class=""HL"" titlle=""Idioma"" style=""cursor:hand;"" onclick=""javascript:window.open('" & w_Pagina &"ID&R=" & w_Pagina &"ID&O=L&w_sq_perfil_pontuacao=" & RS("sq_perfil_pontuacao") & "&p_numero=" & p_numero &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Idioma&SG=CVIDIOMA1','Idioma','toolbars=no,width=600,height=350,top=30,left=50,scrollbars=yes,resizable=yes')"">ID</u>&nbsp"
           ShowHTML "          <u class=""HL"" title=""Seleção de conhecimentos"" style=""cursor:hand;"" onclick=""javascript:window.open('" & w_Pagina &"CO&R=" & w_Pagina &"CO&O=L&w_sq_perfil_pontuacao=" & RS("sq_perfil_pontuacao") & "&p_numero=" & p_numero &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Área conhecimento&SG=CVAREA1','Conhecimento','toolbars=no,width=600,height=350,top=30,left=50,scrollbars=yes,resizable=yes')"">CO</u>&nbsp"
        End If
        'ShowHTML "          <u class=""HL"" titlle=""Curso técnico"" style=""cursor:hand;"" onclick=""javascript:window.open('" & w_Pagina &"CT&R=" & w_Pagina &"CT&O=L&w_sq_perfil_pontuacao=" & RS("sq_perfil_pontuacao") & "&p_numero=" & p_numero &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Curso técnico&SG=CVCURSO1','Técnico','toolbars=no,width=600,height=350,top=30,left=50,scrollbars=yes,resizable=yes')"">CT</u>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=2><font size=""1""><b>(*) Requisito obrigatório - o candidato deve cumpri-lo para ser classificado.</b></td>"
    DesConectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_requisito"" value=""" & w_sq_requisito &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_co_grupo_atividade"" value=""" & w_co_grupo_atividade &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br> " & w_nome_requisito & "</td></tr>"
    if w_pontuacao_perfil > "" then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pontuação Perfil básico:<br></b>"& w_pontuacao_perfil &"<INPUT  type=""hidden"" name=""w_pontuacao_perfil""  value=""" & w_pontuacao_perfil & """ ></td></tr>"
    Else
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pontuação Per<U>f</U>il básico:<br><INPUT ACCESSKEY=""F"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_pontuacao_perfil"" size=""7"" maxlength=""7"" value=""" & w_pontuacao_perfil & """ onKeyDown=""FormataValor(this,5,1, event)""></td></tr>"
    end if
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pontuação final:<br></b>"& w_pontuacao_final & "<INPUT type=""hidden"" name=""w_pontuacao_final"" value=""" & w_pontuacao_final & """></td></tr>" 
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Tempo mínimo de anos:<br></b>" & w_tempo_minimo_anos & "</td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Experiência profissional:</b><br>" & w_experiencia    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Curso técnico:</b><br>" & w_curso_tecnico    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obrigatório:</b><br>"    
    if w_obrigatorio = "" or w_obrigatorio = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S""> Sim"
    ElseIf w_obrigatorio = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S"" checked> Sim"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_numero=" &  p_numero & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_perfil_pontuacao = Nothing
  Set p_numero              = Nothing
  Set w_sq_requisito        = Nothing    
  Set w_co_grupo_atividade  = Nothing
  Set w_co_faixa            = Nothing
  Set w_pontuacao_perfil    = Nothing    
  Set w_pontuacao_final     = Nothing
  Set w_obrigatorio         = Nothing
  Set w_nome_requisito      = Nothing
  Set w_experiencia         = Nothing
  Set w_tempo_minimo_anos   = Nothing
  Set w_curso_tecnico       = Nothing
  Set w_readonly            = Nothing

End Sub
REM =========================================================================
REM Fim da tabela pontuação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de experiência profissional
REM -------------------------------------------------------------------------
Sub ep

  Dim w_sq_perfil_pontuacao,w_sq_area_conhecimento
  Dim w_obrigatorio,w_nome
   
  w_sq_perfil_pontuacao         = Request("w_sq_perfil_pontuacao")
  w_sq_area_conhecimento        = Request("w_sq_area_conhecimento")
      
  If O = "" Then O="L" end if
  
  If InStr("L",O) Then 
     SQL = "select e.*,a.nome " &_
           "  from rh_perfil_experiencia_prof e,co_area_conhecimento a " &_
           " where e.sq_area_conhecimento = a.sq_area_conhecimento " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao
     ConectaBD
  ElseIf (O = "A" or O = "E") Then               
     SQL = "select e.*,a.nome " &_
           "  from rh_perfil_experiencia_prof e,co_area_conhecimento a " &_
           " where e.sq_area_conhecimento = a.sq_area_conhecimento " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao &_
           "   and e.sq_area_conhecimento = " & w_sq_area_conhecimento 
     ConectaBD
     w_sq_perfil_pontuacao  = RS("sq_perfil_pontuacao")
     w_sq_area_conhecimento = RS("sq_area_conhecimento")
     w_obrigatorio          = RS("obrigatorio")
     w_nome                 = RS("nome")
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
        If InStr("I",O) > 0 Then        
            Validate "w_sq_area_conhecimento", "Área conhecimento", "1", "1", "1", "10", "", "1"
        end if
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
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_perfil_pontuacao=" & w_sq_perfil_pontuacao & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.focus();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Cargos selecionados</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_area_conhecimento=" &  rs("sq_area_conhecimento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_area_conhecimento=" &  rs("sq_area_conhecimento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    If Instr("I",O) > 0 Then
        sql = ""
        SQL = "select * from co_area_conhecimento " & VbCrLf & _
              " where especializacao       = 'C' " & VbCrLf & _
              "   and sq_area_conhecimento not in ( select sq_area_conhecimento from rh_perfil_experiencia_prof " & VbCrLf & _
              "                                      where sq_perfil_pontuacao = " & w_sq_perfil_pontuacao & " ) " & VbCrLf & _
              " order by nome " & VbCrLf
        ConectaBD                
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>argo:<br><SELECT READONLY ACCESSKEY=""C"" class=""BTM"" name=""w_sq_area_conhecimento"" size=""1"">"
        ShowHTML "          <OPTION VALUE=""""> --- "
        While Not RS.EOF      
          ShowHTML "          <OPTION VALUE=" & RS("sq_area_conhecimento") & ">" & RS("nome")
          RS.MoveNext
        Wend
        DesconectaBD
        ShowHTML "          </SELECT></td>"
        ShowHTML "      </tr>"    
    else
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento &""">"
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área conhecimento:</b><br>" & w_nome
    end if
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obrigatório:</b><br>"  
    if w_obrigatorio = "" or w_obrigatorio = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S""> Sim "
    ElseIf w_obrigatorio = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_perfil_pontuacao=" &  w_sq_perfil_pontuacao & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_perfil_pontuacao     = Nothing
  Set w_sq_area_conhecimento    = Nothing
  Set w_obrigatorio             = Nothing
  Set w_nome                    = Nothing
  
End Sub
REM =========================================================================
REM Fim da tabela experiência profissional
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de idioma
REM -------------------------------------------------------------------------
Sub id

  Dim w_sq_perfil_pontuacao,w_sq_idioma,w_leitura,w_conversacao,w_compreensao
  Dim w_obrigatorio,w_nome
   
  w_sq_perfil_pontuacao         = Request("w_sq_perfil_pontuacao")
  w_sq_idioma                   = Request("w_sq_idioma")
      
  If O = "" Then O="L" end if
  
  If InStr("L",O) Then 
     SQL = "select e.*,i.nome " &_
           "  from rh_perfil_idioma e,co_idioma i " &_
           " where e.sq_idioma = i.sq_idioma " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao
     ConectaBD
  ElseIf (O = "A" or O = "E") Then               
     SQL = "select e.*,i.nome " &_
           "  from rh_perfil_idioma e,co_idioma i " &_
           " where e.sq_idioma = i.sq_idioma " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao &_
           "   and e.sq_idioma = " & w_sq_idioma 
     ConectaBD
     w_sq_perfil_pontuacao  = RS("sq_perfil_pontuacao")
     w_sq_idioma            = RS("sq_idioma")
     w_leitura              = RS("leitura")  
     w_conversacao          = RS("conversacao")
     w_obrigatorio          = RS("obrigatorio")
     w_compreensao          = RS("compreensao")
     w_nome                 = RS("nome")
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
        If InStr("I",O) > 0 Then
            Validate "w_sq_idioma", "Idioma", "SELECT", "1", "1", "10", "", "1"
        end if
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
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_perfil_pontuacao=" & w_sq_perfil_pontuacao & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.focus();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Idioma</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_idioma=" &  rs("sq_idioma") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_idioma=" &  rs("sq_idioma") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    If Instr("I",O) > 0 Then
        sql = ""
        SQL = "select * from co_idioma " &_
              " where sq_idioma not in ( select sq_idioma from rh_perfil_idioma " &_
              "                                      where sq_perfil_pontuacao = " & w_sq_perfil_pontuacao & " ) " &_
              " order by nome "
        ConectaBD                
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>I</U>dioma:<br><SELECT READONLY ACCESSKEY=""I"" class=""BTM"" name=""w_sq_idioma"" size=""1"">"
        ShowHTML "          <OPTION VALUE=""""> --- "
        While Not RS.EOF      
          ShowHTML "          <OPTION VALUE=" & RS("sq_idioma") & ">" & RS("nome")
          RS.MoveNext
        Wend
        DesconectaBD
        ShowHTML "          </SELECT></td>"
        ShowHTML "      </tr>"    
    else
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_idioma"" value=""" & w_sq_idioma &""">"
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Idioma:</b><br>" & w_nome
    end if
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Leitura:</b><br>"    
    if w_leitura = "" or w_leitura = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_leitura"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_leitura"" value=""S""> Sim "
    ElseIf w_leitura = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_leitura"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_leitura"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Conversação:</b><br>"    
    if w_conversacao = "" or w_conversacao = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_conversacao"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_conversacao"" value=""S""> Sim "
    ElseIf w_conversacao = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_conversacao"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_conversacao"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Compreensão:</b><br>"    
    if w_compreensao = "" or w_compreensao = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_compreensao"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_compreensao"" value=""S""> Sim "
    ElseIf w_compreensao = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_compreensao"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_compreensao"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obrigatório:</b><br>"  
    if w_obrigatorio = "" or w_obrigatorio = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S""> Sim "
    ElseIf w_obrigatorio = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_perfil_pontuacao=" &  w_sq_perfil_pontuacao & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_perfil_pontuacao     = Nothing
  Set w_sq_idioma               = Nothing
  Set w_leitura                 = Nothing
  Set w_conversacao             = Nothing    
  Set w_compreensao             = Nothing
  Set w_obrigatorio             = Nothing
  Set w_nome                    = Nothing
  
End Sub
REM =========================================================================
REM Fim da tabela idioma
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de perfil conhecimento
REM -------------------------------------------------------------------------
Sub co
 
  Dim w_sq_perfil_pontuacao,w_sq_area_conhecimento,w_grau_conhecimento
  Dim w_obrigatorio,w_nome, p_nome

  w_sq_perfil_pontuacao  = Request("w_sq_perfil_pontuacao")
  w_sq_area_conhecimento = Request("w_sq_area_conhecimento")
  p_nome                 = trim(Request("p_nome"))
   
  If InStr("L",O) Then
     SQL = "select e.*,a.nome " & VbCrLf & _
           "  from rh_perfil_conhecimentos         e " & VbCrLf & _
           "       inner join co_area_conhecimento a on (e.sq_area_conhecimento = a.sq_area_conhecimento) " & VbCrLf & _
           " where e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao & " " & VbCrLf & _
           "order by seguranca.acentos(a.nome) " & VbCrLf
     ConectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     If O = "I" and p_nome > "" Then
        ShowHTML "  function MarcaTodos() {"
        ShowHTML "    if (document.Form1.w_sq_area_conhecimento.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form1.w_sq_area_conhecimento.length; i++) "
        ShowHTML "         document.Form1.w_sq_area_conhecimento[i].checked=true;"
        ShowHTML "    else document.Form1.w_sq_area_conhecimento.checked=true;"
        ShowHTML "  }"
        ShowHTML "  function DesmarcaTodos() {"
        ShowHTML "    if (document.Form1.w_sq_area_conhecimento.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form1.w_sq_area_conhecimento.length; i++) "
        ShowHTML "         document.Form1.w_sq_area_conhecimento[i].checked=false;"
        ShowHTML "    "
        ShowHTML "    else document.Form1.w_sq_area_conhecimento.checked=false;"
        ShowHTML "  }"
     End If
     ValidateOpen "Validacao"
     If O = "I" Then
        Validate "p_nome", "Nome", "1", "", "4", "40", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     If p_nome > "" Then
        ValidateOpen "Validacao1"
        If InStr("I",O) > 0 Then
           ShowHTML "  var i; "
           ShowHTML "  var w_erro=true; "
           ShowHTML "  if (theForm.w_sq_area_conhecimento.value==undefined) {"
           ShowHTML "     for (i=0; i < theForm.w_sq_area_conhecimento.length; i++) {"
           ShowHTML "       if (theForm.w_sq_area_conhecimento[i].checked) w_erro=false;"
           ShowHTML "     }"
           ShowHTML "  }"
           ShowHTML "  else {"
           ShowHTML "     if (theForm.w_sq_area_conhecimento.checked) w_erro=false;"
           ShowHTML "  }"
           ShowHTML "  if (w_erro) {"
           ShowHTML "    alert('Você deve informar pelo menos uma das opções!'); "
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
  ShowHTML " .LH{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .LH:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If

  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_perfil_pontuacao=" & w_sq_perfil_pontuacao & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.focus();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
     ShowHTML "          <td><font size=""1""><b>Área conhecimento</font></td>"
     ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
     ShowHTML "        </tr>"
     If RS.EOF Then
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        While Not RS.EOF
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
           ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Grava&R=" & w_Pagina & par & "&O=E&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_area_conhecimento=" &  rs("sq_area_conhecimento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return(confirm('Confirma a exclusão deste registro?'));"">Excluir</A>&nbsp"
           ShowHTML "        </td>"
           ShowHTML "      </tr>"
           RS.MoveNext
        Wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "  </td>"
     ShowHTML "</tr>"
     DesConectaBD	 
  ElseIf O = "I" Then
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""90%"" border=""0"">"
     AbreForm  "Form", R, "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao & """>"

    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da área do conhecimento desejada e clique no botão ""Aplicar filtro"".<li>Quando a relação for exibida, selecione as opções desejadas clicando sobre a caixa ao lado do nome.</ul><hr><b>Filtro</b></div>"
    ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=2>"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font  size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""BTM"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Cancelar"" onClick=""document.Form.O.value='L';"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</form>"
    If p_nome > "" Then
       SQL = "Select b.sq_area_conhecimento codigo, b.nome || ' (' ||b.codigo_cnpq||')' descricao " & VbCrLf &_
             " from co_area_conhecimento b " & VbCrLf &_
             "where seguranca.acentos(upper(b.nome)) like '%'||seguranca.acentos(upper('" & p_nome & "'))||'%' " & VbCrLf & _
             "  and especializacao = 'A' " & VbCrLf & _
             " order by seguranca.acentos(b.nome) "
       ConectaBD
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=2><font size=2><hr>"
       AbreForm  "Form1", w_Pagina & "Grava", "POST", "return(Validacao1(this))", null, P1, P2, P3, P4, TP, SG, R, O
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao & """>"
       ShowHTML "  <tr><td valign=""top"">"
       ShowHTML "      <td nowrap valign=""bottom"" align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
       ShowHTML "  <tr><td align=""center"" colspan=2>"
       ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       If RS.EOF Then
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
         ShowHTML "            <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""HL"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""../images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
         ShowHTML "                                      <U STYLE=""cursor:hand;"" CLASS=""HL"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""../images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
         ShowHTML "            <td><font size=""2""><b>Área do conhecimento</font></td>"
         ShowHTML "          </tr>"
         While Not RS.EOF
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "          <td align=""center""><input type=""checkbox"" name=""w_sq_area_conhecimento"" value=""" & RS("codigo") & """>"
           ShowHTML "          <td><font size=""1"">" & RS("descricao") & "</td>"
           ShowHTML "        </tr>"
           RS.MoveNext
         wend
         ShowHTML "      </center>"
         ShowHTML "    </table>"
         ShowHTML "    </td>"
         ShowHTML "  </tr>"
         ShowHTML "  <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
         ShowHTML "  <tr><td align=""center"" colspan=""2"">"
         ShowHTML "      <input class=""BTM"" type=""submit"" name=""Botao"" value=""Incluir"">"
         ShowHTML "      <input class=""BTM"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_perfil_pontuacao=" & w_sq_perfil_pontuacao & "&O=L';"" name=""Botao"" value=""Cancelar"">"
         ShowHTML "      </td>"
         ShowHTML "  </tr>"
         ShowHTML "</FORM>"
       End If
       DesConectaBD	 
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

  Set p_nome                   = Nothing
  Set w_sq_perfil_pontuacao    = Nothing
  Set w_sq_area_conhecimento   = Nothing
        
End Sub
REM =========================================================================
REM Fim da rotina de acessos do menu
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de curso técnico
REM -------------------------------------------------------------------------
Sub ct

  Dim w_sq_perfil_pontuacao,w_sq_area_conhecimento,w_sq_formacao
  Dim w_carga_horaria_minima
  Dim w_obrigatorio,w_nome,w_nome_formacao
   
  w_sq_perfil_pontuacao         = Request("w_sq_perfil_pontuacao")
  w_sq_area_conhecimento        = Request("w_sq_area_conhecimento")
  w_sq_formacao                 = Request("w_sq_formacao")
      
  If O = "" Then O="L" end if
  
  If InStr("L",O) Then 
     SQL = "select e.*,f.nome formacao,c.nome area" &_
           "  from rh_perfil_curso_tecnico e,co_formacao f,co_area_conhecimento c  " &_
           " where e.sq_area_conhecimento = c.sq_area_conhecimento  " &_
           "   and e.sq_formacao = f.sq_formacao " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao
     ConectaBD
  ElseIf (O = "A" or O = "E") Then               
     SQL = "select e.*,f.nome formacao,c.nome area" &_
           "  from rh_perfil_curso_tecnico e,co_formacao f,co_area_conhecimento c  " &_
           " where e.sq_area_conhecimento = c.sq_area_conhecimento  " &_
           "   and e.sq_formacao = f.sq_formacao " &_
           "   and e.sq_perfil_pontuacao = " & w_sq_perfil_pontuacao &_
           "   and e.sq_area_conhecimento = " & w_sq_area_conhecimento &_
           "   and e.sq_formacao          = " & w_sq_formacao
     ConectaBD
     w_obrigatorio          = RS("obrigatorio")
     w_nome                 = RS("area")
     w_nome_formacao        = RS("formacao")
     w_carga_horaria_minima = RS("carga_horaria_minima")
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
        If InStr("I",O) > 0 Then        
            Validate "w_sq_area_conhecimento", "Área conhecimento", "SELECT", "1", "1", "10", "", "1"
            Validate "w_sq_formacao", "Formação", "SELECT", "1", "1", "10", "", "1"
        end if
        Validate "w_carga_horaria_minima", "Carga horária minima", "1", "1", "1", "4", "", "1"
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
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_perfil_pontuacao=" & w_sq_perfil_pontuacao & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.focus();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Área conhecimento</font></td>"
    ShowHTML "          <td><font size=""1""><b>Formação</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("area") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("formacao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_formacao=" &  rs("sq_formacao") & "&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_area_conhecimento=" &  rs("sq_area_conhecimento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_formacao=" &  rs("sq_formacao") & "&w_sq_perfil_pontuacao=" &  rs("sq_perfil_pontuacao") & "&w_sq_area_conhecimento=" &  rs("sq_area_conhecimento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_perfil_pontuacao"" value=""" & w_sq_perfil_pontuacao &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    If Instr("I",O) > 0 Then
        sql = ""
        SQL = "select * from co_area_conhecimento "&_
              " where sq_area_conhecimento not in ( select sq_area_conhecimento from rh_perfil_curso_tecnico " &_
              "                                      where sq_perfil_pontuacao = " & w_sq_perfil_pontuacao & " ) " &_
              " order by nome "
        ConectaBD                
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área <U>c</U>onhecimento:<br><SELECT READONLY ACCESSKEY=""C"" class=""BTM"" name=""w_sq_area_conhecimento"" size=""1"">"
        ShowHTML "          <OPTION VALUE=""""> --- "
        While Not RS.EOF      
          ShowHTML "          <OPTION VALUE=" & RS("sq_area_conhecimento") & ">" & RS("nome")
          RS.MoveNext
        Wend
        DesconectaBD
        ShowHTML "          </SELECT></td>"
        ShowHTML "      </tr>"        
    else
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_conhecimento"" value=""" & w_sq_area_conhecimento &""">"
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área conhecimento:</b><br>" & w_nome
    end if
    If Instr("I",O) > 0 Then
        sql = ""
        SQL = "select * from co_formacao "&_
              " where sq_formacao not in ( select sq_formacao from rh_perfil_curso_tecnico " &_
              "                                      where sq_perfil_pontuacao = " & w_sq_perfil_pontuacao & ") " &_
              " order by nome "
        ConectaBD                
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>F</U>ormação:<br><SELECT READONLY ACCESSKEY=""F"" class=""BTM"" name=""w_sq_formacao"" size=""1"">"
        ShowHTML "          <OPTION VALUE=""""> --- "
        While Not RS.EOF      
          ShowHTML "          <OPTION VALUE=" & RS("sq_formacao") & ">" & RS("nome")
          RS.MoveNext
        Wend
        DesconectaBD
        ShowHTML "          </SELECT></td>"
        ShowHTML "      </tr>"        
    else
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_formacao"" value=""" & w_sq_formacao &""">"
        ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Formação:</b><br>" & w_nome_formacao
    end if
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>arga horária minima:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_carga_horaria_minima"" size=""4"" maxlength=""4"" value=""" & w_carga_horaria_minima & """ ></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obrigatório:</b><br>"    
    if w_obrigatorio = "" or w_obrigatorio = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S""> Sim "
    ElseIf w_obrigatorio = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""N""  > Não <input " & w_Disabled & " type=""radio"" name=""w_obrigatorio"" value=""S"" checked> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_perfil_pontuacao=" &  w_sq_perfil_pontuacao & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_perfil_pontuacao     = Nothing
  Set w_sq_area_conhecimento    = Nothing    
  Set w_sq_formacao             = Nothing
  Set w_obrigatorio             = Nothing
  Set w_nome                    = Nothing
  Set w_nome_formacao           = Nothing
  Set w_carga_horaria_minima    = Nothing
  
End Sub
REM =========================================================================
REM Fim da tabela curso tecnico
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela palavras chaves
REM -------------------------------------------------------------------------
Sub palavras

  Dim w_sq_palavra_chave,w_area_atuacao,w_curso_tecnico
  Dim w_experiencia_profissional,w_palavra_chave
  Dim p_numero
   
  w_sq_palavra_chave            = Request("w_sq_palavra_chave")
  p_numero                      = Request("p_numero")
      
  If O = "" Then O="L" end if
  
  If InStr("L",O) Then 
     SQL = "select * " &_
           "  from rh_perfil_palavra_chave " &_
           " where sq_perfil = " & p_numero
     ConectaBD
  ElseIf (O = "A" or O = "E") Then               
     SQL = "select * " &_
           "  from rh_perfil_palavra_chave " &_
           " where sq_perfil        = " & p_numero &_
           "   and sq_palavra_chave = " & w_sq_palavra_chave 
     ConectaBD
     w_area_atuacao             = RS("area_atuacao")
     w_curso_tecnico            = RS("curso_tecnico")  
     w_experiencia_profissional = RS("experiencia_profissional")
     w_palavra_chave            = RS("palavra_chave")
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
        Validate "w_palavra_chave", "Palavra chave", "1", "1", "1", "20", "1", "1"
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
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&p_numero=" & p_numero & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <font size=""1""><a accesskey=""F"" class=""SS"" href=""javascript:opener.focus();javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Palavra chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("palavra_chave") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_palavra_chave=" &  rs("sq_palavra_chave") & "&p_numero=" &  p_numero & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_palavra_chave=" &  rs("sq_palavra_chave") & "&p_numero=" &  p_numero & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm  "Form", w_Pagina & "Grava", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, R, O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_palavra_chave"" value=""" & w_sq_palavra_chave &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>P</U>alavra chave:</b><br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""BTM"" type=""text"" name=""w_palavra_chave"" size=""20"" maxlength=""20"" value=""" & w_palavra_chave & """>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Curso tecnico:</b><br>"    
    if w_curso_tecnico = "" or w_curso_tecnico = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_curso_tecnico"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_curso_tecnico"" value=""S"" checked> Sim "
    ElseIf w_curso_tecnico = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_curso_tecnico"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_curso_tecnico"" value=""S"" > Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área de atuação:</b><br>"    
    if w_area_atuacao = "" or w_area_atuacao = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_area_atuacao"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_area_atuacao"" value=""S"" checked> Sim "
    ElseIf w_area_atuacao = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_area_atuacao"" value=""N""  checked> Não <input " & w_Disabled & " type=""radio"" name=""w_area_atuacao"" value=""S""> Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Experiência profissional:</b><br>"    
    if w_experiencia_profissional = "" or w_experiencia_profissional = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_experiencia_profissional"" value=""N"" > Não <input " & w_Disabled & " type=""radio"" name=""w_experiencia_profissional"" value=""S"" checked> Sim "
    ElseIf w_experiencia_profissional = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_experiencia_profissional"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""w_experiencia_profissional"" value=""S"" > Sim "
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""BTM"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&p_numero=" & p_numero & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_palavra_chave=" &  w_sq_palavra_chave & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_sq_palavra_chave            = Nothing
  Set w_area_atuacao                = Nothing
  Set w_curso_tecnico               = Nothing
  Set w_experiencia_profissional    = Nothing    
  Set w_palavra_chave               = Nothing
  
End Sub
REM =========================================================================
REM Fim da tabela palavras chaves
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  
  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  Dim w_max_sq_perfil, w_peso, i
  
  AbreSessao
  Select Case SG 
             
    Case "CVPERGER"  
        'Verifica se a Assinatura Eletrônica é válida
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
           w_assinatura = "" Then
           OraDatabase.AutoCommit = False
           OraDatabase.BeginTrans
           If O = "I" Then
              SQL = "select sq_perfil.nextval max_perfil from dual"
              ConectaBD
              w_max_sq_perfil = RS("max_perfil")
              DesconectaBD
              SQL = "insert into rh_perfil " & _
                    "   (sq_perfil,   nome,             sq_tipo_vinculo,    codigo,             co_grupo_atividade, " &  VbCrLf & _
                    "    co_faixa,    sq_solicitacao,   username_cadastro,  handle_projeto,     atribuicoes, " &  VbCrLf & _
                    "    habilidades, remuneracao_base, remuneracao_minima, remuneracao_maxima, pontuacao_minima, " &  VbCrLf & _
                    "    simulacao,   exige_conta_bancaria, " & VbCrLf & _
                    "    exige_beneficios,exige_dependentes,exige_historico) " & VbCrLf & _
                    " (select "& w_max_sq_perfil & " , " & VbCrLf & _
                    "         '" & UCase(Request("w_nome")) & "', " & VbCrLf & _
                    "         sq_tipo_vinculo, " & VbCrLf
              If request("w_codigo")             > "" Then SQL = SQL & "'" & Request("w_codigo") & "', "             & VbCrLf Else SQL = SQL & " null, " & VbCrLf End If
              If request("w_co_grupo_atividade") > "" Then SQL = SQL & "'" & Request("w_co_grupo_atividade") & "', " & VbCrLf Else SQL = SQL & " null, " & VbCrLf End If
              If request("w_co_faixa")           > "" Then SQL = SQL & "'" & Request("w_co_faixa") & "', "           & VbCrLf Else SQL = SQL & " null, " & VbCrLf End If
              If request("w_sq_solicitacao")     > "" Then SQL = SQL & " " & Request("w_sq_solicitacao") & ", "      & VbCrLf Else SQL = SQL & " null, " & VbCrLf End If
              SQL = SQL & _
                    "         '" & Session("USERNAME") & "', " & VbCrLf & _
                    "         " & request("w_handle_projeto") & ", " & VbCrLf & _
                    "         atribuicoes,habilidades,remuneracao_base,remuneracao_minima,remuneracao_maxima,pontuacao_minima, " & VbCrLf & _
                    "         '" & request("w_simulacao") & "', " & VbCrLf & _
                    "         'N', 'N', 'N', 'N' " & VbCrLf & _
                    "   from rh_grupo_faixa  " & VbCrLf & _
                    "  where sq_tipo_vinculo    = '" & Request("w_vinculo") & "'" & VbCrLf & _
                    "    and co_grupo_atividade = '" & Request("w_co_grupo_atividade") & "'" & VbCrLf & _
                    "    and co_faixa           = '" & Request("w_co_faixa") & "') " & VbCrLf
              ExecutaSQL(SQL)

              SQL = "insert into rh_perfil_pontuacao (sq_perfil_pontuacao,sq_perfil,sq_requisito, " & _
                    "                                co_grupo_atividade,co_faixa,pontuacao_perfil, " &_
                    "                                pontuacao_final,obrigatorio,experiencia,tempo_minimo_anos,curso_tecnico ) " &_
                    " (select sq_perfil_pontuacao.nextval, " & w_max_sq_perfil & ",p.sq_requisito, " &_
                    "         p.co_grupo_atividade,p.co_faixa,p.pontuacao_perfil_basico,p.pontuacao_final, " &_
                    "         p.requisito_basico,r.experiencia,r.tempo_minimo_anos,r.curso_tecnico " &_ 
                    "    from rh_requisito_pontos p,rh_requisito r " &_
                    "   where p.co_grupo_atividade = '" & request("w_co_grupo_atividade") & "' " &_
                    "     and p.co_faixa           = '" & request("w_co_faixa") & "' " &_
                    "     and p.sq_requisito       = r.sq_requisito )"
              R = R & "&p_numero=" & w_max_sq_perfil
           ElseIf O = "A" Then
              'Atualiza os dados das tabelas de especialização da solicitação
              SQL = "update rh_perfil set " & _
                    "    nome                 = trim('" & UCase(request("w_nome")) & "'), " & _
                    "    codigo               = trim('" & UCase(request("w_codigo")) & "'), " & _
                    "    username_cadastro    = '" & Session("USERNAME") & "', " &_
                    "    handle_projeto       = " & request("w_handle_projeto") & ", " & _
                    "    simulacao            = '" & request("w_simulacao") & "' " &_
                    " where sq_perfil = " & request("p_numero")
           ElseIf O = "E" Then
             ' Apaga os registros da tabela de sexo do perfil
             SQL = "delete  from rh_perfil_sexo where sq_perfil = " & request("p_numero")
             ExecutaSQL(SQL)

             ' Apaga os registros da tabela de faixa etária do perfil
             SQL = "delete  from rh_perfil_faixa_etaria where sq_perfil = " & request("p_numero")
             ExecutaSQL(SQL)

             ' Apaga os registros da tabela de estados civis do perfil
             SQL = "delete  from rh_perfil_estado_civil where sq_perfil = " & request("p_numero")
             ExecutaSQL(SQL)

             ' Apaga os candidatos vinculados ao perfil
             SQL = "delete  from rh_perfil_candidato where sq_perfil = " & request("p_numero")
             ExecutaSQL(SQL)

             ' Apaga a pontuação do perfil
             SQL = " delete from rh_perfil_escolaridade where sq_perfil_pontuacao in (select distinct sq_perfil_pontuacao from rh_perfil_pontuacao where sq_perfil = " & request("p_numero") & ")"
             ExecutaSQL(SQL)
             SQL = " delete from rh_perfil_experiencia_prof where sq_perfil_pontuacao in (select distinct sq_perfil_pontuacao from rh_perfil_pontuacao where sq_perfil = " & request("p_numero") & ")"
             ExecutaSQL(SQL)
             SQL = " delete from rh_perfil_idioma where sq_perfil_pontuacao in (select distinct sq_perfil_pontuacao from rh_perfil_pontuacao where sq_perfil = " & request("p_numero") & ")"
             ExecutaSQL(SQL)
             SQL = " delete from rh_perfil_conhecimentos where sq_perfil_pontuacao in (select distinct sq_perfil_pontuacao from rh_perfil_pontuacao where sq_perfil = " & request("p_numero") & ")"
             ExecutaSQL(SQL)
             SQL = " delete from rh_perfil_curso_tecnico where sq_perfil_pontuacao in (select distinct sq_perfil_pontuacao from rh_perfil_pontuacao where sq_perfil = " & request("p_numero") & ")"
             ExecutaSQL(SQL)
             SQL = " delete from rh_perfil_pontuacao where sq_perfil = " & request("p_numero")
             ExecutaSQL(SQL)

             ' Apaga o registro da tabela de perfis
             SQL = "delete  from rh_perfil where sq_perfil = " & request("p_numero")
           End If
           ExecutaSQL(SQL)
           OraDatabase.CommitTrans
           OraDatabase.AutoCommit = True     
           ScriptOpen "JavaScript"
           ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
           SQL = "select b.link,a.sigla from sg_menu a, sg_menu b where a.sq_pagina = b.sq_pagina_pai and b.sigla = '" & SG & "'"
           ConectaBD
           If O = "I" Then
              ShowHTML "  parent.menu.location='Menu.asp?par=ExibeDocs&p_numero=" & w_max_sq_perfil & "&O=A&SG=" & rs("sigla") & "&TP=" & RemoveTP(TP) & MontaFiltro("GET") & "';"
           ElseIf O = "E" Then
              ShowHTML "  location.href='" & R & "&R=" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
           Else
              ShowHTML "  location.href='" & R & "&R=" & R & "&O=" & O &"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
           End If
           ScriptClose
        Else
           ScriptOpen "JavaScript"
           ShowHTML "  alert('Assinatura Eletrônica inválida!');"
           ShowHTML "  history.back(1);"
           ScriptClose
        End If
        
    Case "CVETARIA"        
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans

          ' Faixa etária
          SQL = "select * from rh_perfil_faixa_etaria where sq_perfil = " & request("p_numero") & VbCrLf
          ConectaBD
          If Not RS.EOF Then
             If Request("w_minimo") = "" and Request("w_maximo") = "" Then
                SQL = "delete from rh_perfil_faixa_etaria where sq_perfil = " & request("p_numero") & VbCrLf
                ExecutaSQL(SQL)
             Else
                SQL = "update rh_perfil_faixa_etaria set " & VbCrLf & _
                      "   minimo        = '" & Request("w_minimo") & "', " & VbCrLf & _
                      "   maximo        = '" & Request("w_maximo") & "', " & VbCrLf & _
                      "   obrigatorio   = '" & Request("w_obrigatorio_faixa") & "' " & VbCrLf & _
                      " where sq_perfil = " & Request("p_numero") & VbCrLf
                ExecutaSQL(SQL)
             End If
          ElseIf Request("w_minimo") > "" and Request("w_maximo") > "" Then
             SQL = "insert into rh_perfil_faixa_etaria (sq_perfil, obrigatorio, minimo, maximo) " & VbCrLf & _
                   " values ( " & request("p_numero") & ",'" & request("w_obrigatorio_faixa") & "', " & VbCrLf & _
                   "         '" & request("w_minimo") & "', '" & request("w_maximo") & "') " & VbCrLf
             ExecutaSQL(SQL)
          End if              

          ' Sexo
          SQL = "select * from rh_perfil_sexo where sq_perfil = " & request("p_numero") & VbCrLf
          ConectaBD
          If Not RS.EOF Then
             If request("w_sexo") = "N" Then
                SQL = "delete from rh_perfil_sexo where sq_perfil = " & request("p_numero") & VbCrLf
                ExecutaSQL(SQL)
             Else
                SQL = "update rh_perfil_sexo set " & VbCrLf & _
                      " sexo            = '" & request("w_sexo") & "', " & VbCrLf & _
                      " obrigatorio     = '" & request("w_obrigatorio_sexo") & "' " & VbCrLf & _
                      " where sq_perfil = " & request("p_numero") & VbCrLf
                ExecutaSQL(SQL)
             End If
          ElseIf request("w_sexo") <> "N" Then
             SQL = "insert into rh_perfil_sexo(sq_perfil,sexo,obrigatorio) " & VbCrLf & _
                   " values ( " & request("p_numero") & ",'" & request("w_sexo") & "', " & VbCrLf & _
                   "         '" & request("w_obrigatorio_sexo") & "') " & VbCrLf
             ExecutaSQL(SQL)
          End if              

          SQL = "update rh_perfil set " & _
                "    atribuicoes          = '" & request("w_atribuicoes") & "', " & VbCrLf & _
                "    habilidades          = '" & request("w_habilidades") & "', " & VbCrLf & _
                "    exige_conta_bancaria = '" & request("w_exige_conta_bancaria") & "', " & VbCrLf & _
                "    exige_beneficios     = '" & request("w_exige_beneficios") & "', " & VbCrLf & _
                "    exige_dependentes    = '" & request("w_exige_dependentes") & "', " & VbCrLf & _
                "    exige_historico      = '" & request("w_exige_historico") & "' " & VbCrLf & _
                " where sq_perfil = " & request("p_numero") & VbCrLf
          ExecutaSQL(SQL)

          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&p_numero="& request("p_numero") &"&O=A&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
       
    Case "CVPERPON" 'PERFIL PONTUAÇÃO
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          if Request("w_pontuacao_perfil")>"" then 
            SQL = ""
            SQL = "select seguranca.fvalor(peso*seguranca.fvalor('" & Request("w_pontuacao_perfil") & "',1),1) b_peso "&_
                  "  from rh_categoria_peso p,rh_requisito r "&_
                  " where p.co_grupo_atividade = '" & request("w_co_grupo_atividade") & "' " &_
                  "   and r.co_grupo_categoria = p.co_grupo_categoria " &_
                  "   and r.co_categoria       = p.co_categoria " &_ 
                  "   and r.sq_requisito       = " & request("w_sq_requisito")
            Set Rs = OraDatabase.CreateDynaset(SQL,0)
            w_peso = rs("b_peso")
            rs.close
          else
            w_peso = ""
          end if
          SQL = ""
          SQL = "update rh_perfil_pontuacao set "
          SQL = SQL & " obrigatorio = '" & request("w_obrigatorio") & "', "
          If request("w_pontuacao_perfil") > "" then SQL = SQL & " pontuacao_perfil = seguranca.fvalor('" & request("w_pontuacao_perfil") & "',1), " else SQL = SQL & " pontuacao_perfil = NULL, " end if
          If w_peso > "" then SQL = SQL & " pontuacao_final = seguranca.fvalor('" & w_peso & "',1) " else SQL = SQL & " pontuacao_final = NULL " end if
          SQL = SQL & " where sq_perfil  = " & request("p_numero") &_
                      "   and sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao")
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&R=" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  
  Case "CVEXPPROF"  'EXPERIÊNCIA PROFISSIONAL
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_experiencia_prof (sq_perfil_pontuacao,sq_area_conhecimento, " &_
                   "                                        obrigatorio) " &_
                   " values ( " & request("w_sq_perfil_pontuacao") & ", " & request("w_sq_area_conhecimento") & ", " &_
                   "          '" & request("w_obrigatorio") & "')"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_experiencia_prof set " &_                                
                   "   obrigatorio       = '" & request("w_obrigatorio") & "' " &_
                   " where sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_experiencia_prof " &_
                   " where sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_perfil_pontuacao="& request("w_sq_perfil_pontuacao") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
       
   Case "CVESCOLA1"  'ESCOLARIDADE
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_escolaridade (sq_perfil_pontuacao,sq_area_conhecimento, " &_
                   "                                        sq_formacao,obrigatorio) " &_
                   " values ( " & request("w_sq_perfil_pontuacao") & ", " & request("w_sq_area_conhecimento") & ", " &_
                   "          " & request("w_sq_formacao") & ", '" & request("w_obrigatorio") & "')"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_escolaridade set " &_                                
                   "   obrigatorio       = '" & request("w_obrigatorio") & "' " &_
                   " where sq_perfil_pontuacao    = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento") &_
                   "   and sq_formacao            = " & request("w_sq_formacao")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_escolaridade " &_
                   " where sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento") &_
                   "   and sq_formacao            = " & request("w_sq_formacao")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_perfil_pontuacao="& request("w_sq_perfil_pontuacao") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
 
    Case "CVIDIOMA1"  'IDIOMA
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_idioma (sq_perfil_pontuacao,sq_idioma, " &_
                   "                              leitura,conversacao,compreensao,obrigatorio) " &_
                   " values ( " & request("w_sq_perfil_pontuacao") & ", " & request("w_sq_idioma") & ", " &_
                   "          '" & request("w_leitura") & "','" & request("w_conversacao") & "','" & request("w_compreensao") & "','" & request("w_obrigatorio") & "')"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_idioma set " &_                                
                   "   leitura              = '" & request("w_leitura") & "', " &_ 
                   "   conversacao          = '" & request("w_conversacao") & "', "&_
                   "   compreensao          = '" & request("w_compreensao") & "', "&_
                   "   obrigatorio          = '" & request("w_obrigatorio") & "' " &_
                   " where sq_perfil_pontuacao  = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_idioma            = " & request("w_sq_idioma")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_idioma " &_
                   " where sq_perfil_pontuacao  = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_idioma            = " & request("w_sq_idioma")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_perfil_pontuacao="& request("w_sq_perfil_pontuacao") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  
  Case "CVAREA1"  'ÁREA DE CONHECIMENTO
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             For i = 1 To Request.Form("w_sq_area_conhecimento").Count
                SQL = "select count(*) existe from rh_perfil_conhecimentos " & VbCrLf & _
                      " where sq_perfil_pontuacao  = " & request("w_sq_perfil_pontuacao") & " " & VbCrLf & _
                      "   and sq_area_conhecimento = " & Request("w_sq_area_conhecimento")(i) & " " & VbCrLf
                ConectaBD
                If RS("existe") = 0 Then
                   SQL = "insert into rh_perfil_conhecimentos (sq_perfil_pontuacao,sq_area_conhecimento, " & VbCrLf & _
                          "                                     grau_conhecimento,obrigatorio) " & VbCrLf & _
                          " values (" & Request("w_sq_perfil_pontuacao") & ", " & VbCrLf & _
                          "         " & Request("w_sq_area_conhecimento")(i) & ", " & VbCrLf & _
                          "         'D', " & VbCrLf & _
                          "         'S'" & VbCrLf & _
                         "        )" & VbCrLf
                End If
                ExecutaSQL(SQL)
             Next
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_conhecimentos " & VbCrLf & _
                   " where sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao") & VbCrLf & _
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento") & VbCrLf
             ExecutaSQL(SQL)
          End If
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_perfil_pontuacao="& request("w_sq_perfil_pontuacao") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  
  Case "CVCURSO1"  'CURSO TÉCNICO
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_curso_tecnico (sq_perfil_pontuacao,sq_area_conhecimento, " &_
                   "                                     sq_formacao,carga_horaria_minima,obrigatorio) " &_
                   " values ( " & request("w_sq_perfil_pontuacao") & ", " & request("w_sq_area_conhecimento") & ", " &_
                   "          " & request("w_sq_formacao") & ", " & request("w_carga_horaria_minima") & ",'" & request("w_obrigatorio") & "')"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_curso_tecnico set " &_
                   "   carga_horaria_minima = " & request("w_carga_horaria_minima") & ", " &_                          
                   "   obrigatorio          = '" & request("w_obrigatorio") & "' " &_
                   " where sq_perfil_pontuacao    = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento") &_
                   "   and sq_formacao            = " & request("w_sq_formacao")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_curso_tecnico " &_
                   " where sq_perfil_pontuacao = " & request("w_sq_perfil_pontuacao") &_
                   "   and sq_area_conhecimento   = " & request("w_sq_area_conhecimento") &_
                   "   and sq_formacao            = " & request("w_sq_formacao")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_sq_perfil_pontuacao="& request("w_sq_perfil_pontuacao") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  
  Case "CVCIVIL"  'ESTADO CIVIL
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_estado_civil (sq_perfil,sq_estado_civil, " &_
                   "                                    obrigatorio) " &_
                   " values ( " & request("p_numero") & ", " & request("w_sq_estado_civil") & ", " &_
                   "          '" & request("w_obrigatorio_civil") & "')"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_estado_civil set " &_                                
                   "   obrigatorio       = '" & request("w_obrigatorio_civil") & "' " &_
                   " where sq_perfil         = " & request("p_numero") &_
                   "   and sq_estado_civil   = " & request("w_sq_estado_civil")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_estado_civil " &_
                   " where sq_perfil         = " & request("p_numero") &_
                   "   and sq_estado_civil   = " & request("w_sq_estado_civil")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&p_numero="& request("p_numero") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If

    Case "CVPALAVRAS"  'PALAVRAS CHAVES
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          OraDatabase.AutoCommit = False
          OraDatabase.BeginTrans
          If O = "I" Then
             SQL = "insert into rh_perfil_palavra_chave (sq_palavra_chave,sq_perfil, " &_
                   "                              palavra_chave,area_atuacao,curso_tecnico,experiencia_profissional) " &_
                   " (select sq_palavra_chave.nextval, " &_
                   " " & Request("p_numero") & ", " &_
                   " '" & Request("w_palavra_chave") & "', " &_
                   " '" & Request("w_area_atuacao") & "'," &_
                   " '" & Request("w_curso_tecnico") & "'," &_
                   " '" & Request("w_experiencia_profissional") & "'" &_
                   " from dual)"
          ElseIf O = "A" Then             
             SQL = "update rh_perfil_palavra_chave set " &_                                
                   " palavra_chave = '" & Request("w_palavra_chave") & "', " &_
                   " area_atuacao  = '" & Request("w_area_atuacao") & "'," &_
                   " curso_tecnico = '" & Request("w_curso_tecnico") & "'," &_
                   " experiencia_profissional = '" & Request("w_experiencia_profissional") & "'" &_
                   " where sq_perfil        = " & request("p_numero") &_
                   "   and sq_palavra_chave = " & request("w_sq_palavra_chave")
          ElseIf O = "E" Then
             SQL = "delete rh_perfil_palavra_chave " &_
                   " where sq_perfil        = " & request("p_numero") &_
                   "   and sq_palavra_chave = " & request("w_sq_palavra_chave")
          End If
          ExecutaSQL(SQL)
          OraDatabase.CommitTrans
          OraDatabase.AutoCommit = True
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&p_numero="& request("p_numero") &"&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select
  
  Set i               = Nothing
  Set w_max_sq_perfil = Nothing
  Set w_peso          = Nothing
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
    Case "ADICIONAL"
       adicional 
    Case "IDIOMA"
       idioma
    Case "CIVIL"
       civil
    Case "ETARIA"
       etaria      
    Case "PONTUACAO"
       pontuacao         
    Case "GRAVA"
       Grava
    Case "EP"    
       ep
    Case "ID"    
       id
    Case "CO"    
       co
    Case "CT"    
       ct
    Case "PALAVRAS"    
       palavras      
    Case "VISUAL"
       Visualizacao
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""../images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>