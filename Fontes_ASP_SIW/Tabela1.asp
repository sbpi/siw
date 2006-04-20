<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_Tabela1.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /Tabela1.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas do sistema
REM Mail     : alex@sbpi.com.br
REM Criacao  : 26/11/2002, 19:07
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
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG, w_cliente, w_usuario
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_filter, w_cor
Dim w_Assinatura
Dim w_dir, w_dir_volta, w_submenu, w_menu
Private Par
Set RS  = Server.CreateObject("ADODB.RecordSet")

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
w_Pagina     = "Tabela1.asp?par="
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
  Case Else
     w_TP = TP & " - Listagem"
End Select

' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG) 

Main

FechaSessao

Set w_dir       = Nothing
Set w_dir_volta = Nothing
Set w_usuario   = Nothing
Set w_cliente   = Nothing
Set w_menu      = Nothing

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
REM Rotina da tabela de tipo de vínculo
REM -------------------------------------------------------------------------
Sub TipoVinculo

  Dim w_sq_tipo_vinculo,w_nome,w_ativo
  Dim w_padrao
  Dim w_sq_tipo_pessoa
  Dim w_interno, w_contratado
  Dim p_nome,p_ativo
  Dim w_libera_edicao
  
  p_nome                        = Trim(uCase(Request("p_nome")))
  p_ativo                       = Trim(Request("p_ativo"))
  w_sq_tipo_vinculo             = Request("w_sq_tipo_vinculo")
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "" Then O="L" end if
  
  If InStr("LP",O) Then 
    DB_GetVincKindList RS, w_cliente, p_ativo, null, p_nome, null
     RS.sort = "sq_tipo_pessoa, padrao desc, nome"      
  ElseIf (O = "A" or O = "E") Then               
     DB_GetVincKindData RS, w_sq_tipo_vinculo  
     w_nome             = RS("nome")
     w_sq_tipo_pessoa   = RS("sq_tipo_pessoa")
     w_interno          = RS("interno")
     w_contratado       = RS("contratado")
     w_ativo            = RS("ativo")
     w_padrao           = RS("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_tipo_pessoa", "Aplicação", "SELECT", "1", "1", "18", "", "1"
        Validate "w_nome", "Nome", "1", "1", "1", "20", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "10", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_sq_tipo_pessoa.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If w_libera_edicao = "S" Then
       ShowHTML "<font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_nome  & p_ativo > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aplicação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Interno</font></td>"
    ShowHTML "          <td><font size=""1""><b>Contratado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    If w_libera_edicao = "S" Then
       ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_tipo_vinculo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("sq_tipo_pessoa") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        If RS("interno") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("contratado") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("padrao") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_vinculo=" & RS("sq_tipo_vinculo") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_vinculo=" & RS("sq_tipo_vinculo") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
           ShowHTML "        </td>"
        End If
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
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O   
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_vinculo"" value=""" & w_sq_tipo_vinculo &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr>"
    SelecaoTipoPessoa "<u>A</u>plicação:", "A", "Selecione o tipo de pessoa na relação.", w_sq_tipo_pessoa, null, "w_sq_tipo_pessoa", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""20"" maxlength=""20"" value=""" & w_nome & """></td></tr>"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr>"
    MontaRadioNS "<b>Interno?</b>", w_interno, "w_interno"
    MontaRadioNS "<b>Contratado?</b>", w_contratado, "w_contratado"
    MontaRadioNS "<b>Padrão?</b>", w_padrao, "w_padrao"
    MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"  

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""10"" maxlength=""10"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ativo:</b><br>"
    If p_Ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value="""" checked> Todos"
    ElseIf p_Ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N"" checked> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_tipo_vinculo         = Nothing
  Set w_sq_tipo_pessoa          = Nothing
  Set w_nome                    = Nothing
  Set w_ativo                   = Nothing 
  Set p_nome                    = Nothing
  Set p_ativo                   = Nothing
  Set w_padrao                  = Nothing
  Set w_libera_edicao           = Nothing 
End Sub

REM =========================================================================
REM Rotina da tabela de parâmetros de segurança
REM -------------------------------------------------------------------------
Sub ParSeguranca

  Dim w_tamanho_minimo_senha
  Dim w_tamanho_maximo_senha
  Dim w_maximo_tentativas
  Dim w_dias_vigencia_senha
  Dim w_dias_aviso_expiracao
  
  DB_GetCustomerData RS, w_cliente
  w_tamanho_minimo_senha = RS("TAMANHO_MIN_SENHA")
  w_tamanho_maximo_senha = RS("TAMANHO_MAX_SENHA")
  w_maximo_tentativas    = RS("maximo_tentativas")
  w_dias_vigencia_senha  = RS("DIAS_VIG_SENHA")
  w_dias_aviso_expiracao = RS("DIAS_AVISO_EXPIR")
  DesconectaBD
  
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  Validate "w_tamanho_minimo_senha", "Tamanho mínimo", "1", "1", "1", "2", "", "1"
  Validate "w_tamanho_maximo_senha", "Tamanho máximo", "1", "1", "1", "2", "", "1"
  Validate "w_maximo_tentativas", "Máximo tentativas", "1", "1", "1", "2", "", "1"
  Validate "w_dias_vigencia_senha", "Dias vigência", "1", "1", "1", "2", "", "1"
  Validate "w_dias_aviso_expiracao", "Aviso expiração", "1", "1", "1", "2", "", "1"
  Validate "w_assinatura", "Assinatura eletrônica", "1", "1", "6", "15", "1", "1"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad='document.Form.w_tamanho_minimo_senha.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & Replace(w_TP,"Listagem","Alteração") & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,w_Pagina & par,O   

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""70%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font  size=""1""><b>Tamanho mín<U>i</U>mo:<br><INPUT ACCESSKEY=""I"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_tamanho_minimo_senha"" size=""2"" maxlength=""2"" value=""" & w_tamanho_minimo_senha & """ title=""Tamanho mínimo da senha de acesso e assinatura eletrônica""></td>"
  ShowHTML "          <td valign=""top""><font  size=""1""><b>Tamanho má<U>x</U>imo:<br><INPUT ACCESSKEY=""X"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_tamanho_maximo_senha"" size=""2"" maxlength=""2"" value=""" & w_tamanho_maximo_senha & """ title=""Tamanho máximo da senha de acesso e assinatura eletrônica""></td>"
  ShowHTML "      <tr><td valign=""top"" colspan=2><font  size=""1""><b>Máximo <U>t</U>entativas:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_maximo_tentativas"" size=""2"" maxlength=""2"" value=""" & w_maximo_tentativas & """ title=""Máximo de tentativas inválidas antes de bloquear o acesso do usuário""></td>"
  ShowHTML "      <tr><td valign=""top""><font  size=""1""><b>Dias <U>v</U>igência:<br><INPUT ACCESSKEY=""V"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias_vigencia_senha"" size=""2"" maxlength=""2"" value=""" & w_dias_vigencia_senha & """ title=""Número de dias de vigência da senha de acesso""></td>"
  ShowHTML "          <td valign=""top""><font  size=""1""><b><U>D</U>ias de aviso:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias_aviso_expiracao"" size=""2"" maxlength=""2"" value=""" & w_dias_aviso_expiracao & """ title=""Dias de aviso para o usuário antes que sua senha de acesso tenha sua vigência expirada""></td>"
  ShowHTML "      <tr><td valign=""top""><font  size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3""><input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar""></td></tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_tamanho_minimo_senha = Nothing
  Set w_tamanho_maximo_senha = Nothing
  Set w_maximo_tentativas    = Nothing 
  Set w_dias_vigencia_senha  = Nothing
  Set w_dias_aviso_expiracao = Nothing

End Sub

REM =========================================================================
REM Rotina de integração
REM -------------------------------------------------------------------------
Sub Integracao

  Dim w_tabela, w_codigo_interno, w_codigo_externo
  Dim w_troca
  
  w_troca         = Request("w_troca")
  If w_troca > "" Then
     w_tabela         = Request("w_tabela")
     w_codigo_interno = Request("w_codigo_interno")
  End If
  
  If w_codigo_interno > "" Then
     DB_GetCodigo RS, w_cliente, w_tabela, w_codigo_interno, null
     If Not RS.EOF Then
        w_codigo_externo = Nvl(RS("codigo_externo"),"")
     Else
        ScriptOpen "JavaScript"
        ShowHTML "alert('Código interno inexistente!');"
        ShowHTML "history.back(1);"
        ScriptClose
     End If
     DesconectaBD
  End If 
  
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  Validate "w_tabela", "Tabela", "SELECT", "1", "1", "20", "1", "1"
  Validate "w_codigo_interno", "Código interno", "1", "1", "1", "255", "1", "1"
  Validate "w_codigo_externo", "Código externo", "1", "1", "1", "255", "1", "1"
  Validate "w_assinatura", "Assinatura eletrônica", "1", "1", "6", "15", "1", "1"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form.w_codigo_externo.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_tabela.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & Replace(w_TP,"Listagem","Inclusão") & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,w_Pagina & par,O   
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value=""" & w_troca & """>"
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""70%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top"" nowrap title=""Selecione a tabela desejada""><font size=""1""><b><U>T</U>abela</b><br><SELECT ACCESSKEY=""T"" CLASS=""sts"" NAME=""w_tabela"" " & w_Disabled & ">"
  ShowHTML "          <option value="""">---"
  If w_tabela = "UNIDADE" Then
     ShowHTML "          <option value=""UNIDADE"" SELECTED>Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "PAIS" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"" SELECTED>País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "CIDADE" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"" SELECTED>Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "TIPO_UNIDADE" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"" SELECTED>Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "AREA_ATUACAO" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"" SELECTED>Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "LOCALIZACAO" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"" SELECTED>Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "PESSOA" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"" SELECTED>Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "TIPO_VINCULO" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"" SELECTED>Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "TIPO_ENDERECO" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"" SELECTED>Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  ElseIf w_tabela = "ENDERECO" Then
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"" SELECTED>Endereço"
  Else
     ShowHTML "          <option value=""UNIDADE"">Unidade"
     ShowHTML "          <option value=""PAIS"">País"
     ShowHTML "          <option value=""CIDADE"">Cidade"
     ShowHTML "          <option value=""TIPO_UNIDADE"">Tipo de unidade"
     ShowHTML "          <option value=""AREA_ATUACAO"">Área de atuação"
     ShowHTML "          <option value=""LOCALIZACAO"">Localização"
     ShowHTML "          <option value=""PESSOA"">Usuários"
     ShowHTML "          <option value=""TIPO_VINCULO"">Tipo de vínculo"
     ShowHTML "          <option value=""TIPO_ENDERECO"">Tipo de endereço"
     ShowHTML "          <option value=""ENDERECO"">Endereço"
  End If
  ShowHTML "          </select>"
  ShowHTML "          <td valign=""top""><font  size=""1""><b><U>C</U>ódigo interno:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo_interno"" size=""10"" maxlength=""255"" value=""" & w_codigo_interno & """ title=""Código interno do registro no sistema"" ONBLUR=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_codigo_interno'; document.Form.submit();""></td>"
  ShowHTML "          <td valign=""top""><font  size=""1""><b>C<U>ó</U>digo externo:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo_externo"" size=""10"" maxlength=""255"" value=""" & w_codigo_externo & """ title=""Código externo do registro no sistema""></td>"
  ShowHTML "      <tr><td valign=""top""><font  size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3""><input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar""></td></tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_tabela          = Nothing
  Set w_codigo_interno  = Nothing
  Set w_codigo_externo  = Nothing 
  Set w_troca           = Nothing 

End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_codigo_siape
  Dim p_nome
  Dim p_ativo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  Select Case SG
   Case "COTPVINC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COTipoVinc O, _
                   Request("w_sq_tipo_vinculo"), Request("w_sq_tipo_pessoa"), w_cliente, _
                   Request("w_nome"), Request("w_interno"), Request("w_contratado"), Request("w_padrao"), _
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
    Case "PARSEG"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SIWCliConf _
                   w_cliente, Request("w_tamanho_minimo_senha"), Request("w_tamanho_maximo_senha"), _
                   Request("w_maximo_tentativas"), Request("w_dias_vigencia_senha"), _
                   Request("w_dias_aviso_expiracao"), null, null, null, null, null, null, null, _
                   "AUTENTICACAO"
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "INTEGR"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutCodigoExterno _
                   w_cliente, Request("w_tabela"), Request("w_codigo_interno"), _
                   Request("w_codigo_externo"), null
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If       
  End Select

  Set p_codigo          = Nothing
  Set p_codigo_siape    = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_Null            = Nothing
End Sub

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
    Case "VINCULO"       TipoVinculo
    Case "PARSEGURANCA"  ParSeguranca
    Case "INTEGRACAO"    Integracao
    Case "GRAVA"         Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
%>

