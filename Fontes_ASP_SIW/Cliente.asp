<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Cliente.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DB_Tabela_SIW.asp" -->
<!-- #INCLUDE FILE="DB_Link.asp" -->
<!-- #INCLUDE FILE="DML_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_Cliente.asp" -->
<!-- #INCLUDE FILE="DML_Tabela1.asp" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualCliente.asp" -->
<!-- #INCLUDE FILE="cp_upload/_upload.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Cliente.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o cadastro de clientes do produto
REM Mail     : alex@sbpi.com.br
REM Criacao  : 31/12/2001 12:25
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
REM                   = N   : Nova solicitação de envio.

If nvl(Request("p_cliente"),"nulo") <> "nulo" Then Session("p_cliente") = Request("p_cliente")  End If
If nvl(Request("p_portal"),"nulo") <> "nulo"  Then Session("p_portal")  = Request("p_portal")   End If
If nvl(Request("p_logon"),"nulo") <> "nulo"   Then Session("LogOn")     = Request("p_LogOn")    End If
If nvl(Request("p_dbms"),"nulo") <> "nulo"    Then Session("dbms")      = Request("p_dbms")     End If
If nvl(Request("w_usuario"),"nulo") <> "nulo" Then w_sq_pessoa          = Request("w_usuario")  End If

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu
Dim w_Assinatura
Dim p_ativo, p_pais, p_cidade, p_uf, p_nome, p_ordena
Dim w_troca,w_cor, w_filter, w_dir_volta, UploadID
Dim w_sq_pessoa
Dim ul,File, w_cliente, w_dir
Set RS  = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
p_uf               = uCase(Request("p_uf"))
p_cidade           = uCase(Request("p_cidade"))
p_pais             = uCase(Request("p_pais"))
p_nome             = uCase(Request("p_nome"))
p_ativo            = uCase(Request("p_ativo"))
p_ordena           = uCase(Request("p_ordena"))
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
w_dir        = ""
w_Pagina     = "cliente.asp?par="
w_Disabled   = "ENABLED"
SG           = ucase(Request("SG"))
O            = uCase(Request("O"))
' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente         = RetornaCliente()

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
Else
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = Request("P3")
   P4           = Request("P4")
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   
   If O = "L" and (ucase(Request("par")) = "GERAL" or ucase(Request("par")) = "CONFIGURACAO") Then
      O = "A"
   ElseIf O = "" and ucase(Request("par")) = "CONFIGURACAO" Then
      O = "A"
   ElseIf O = "" Then
      O = "L"
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

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

Main

FechaSessao

Set UploadID      = Nothing
Set w_cliente     = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
Set w_submenu     = Nothing
Set w_reg         = Nothing
Set p_uf          = Nothing
Set p_cidade      = Nothing
Set p_pais        = Nothing
Set p_ativo       = Nothing
Set p_nome        = Nothing
Set p_ordena      = Nothing

Set RS            = Nothing
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

REM =========================================================================
REM Rotina da tabela de Clientes
REM -------------------------------------------------------------------------
Sub Inicial

  If O = "L" Then
     DB_GetSiwCliList RS, p_pais, p_uf, p_cidade, p_ativo, p_nome
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "nome_indice" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "Javascript"
  ValidateOpen "Validacao"
  Validate "p_nome", "Nome", "", "", "4", "50", "1", ""
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_smtp_server.focus();'"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_pais.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_submenu > "" Then
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""Menu.asp?par=ExibeDocs&O=I&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & MontaFiltro("GET") & """ TARGET=""menu""><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       'ShowHTML "                         <a accesskey=""N"" class=""ss"" href=""pessoa.asp?par=BENEF&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>N</u>ovo cliente</a>&nbsp;"
    End If
    If p_pais & p_uf & p_cidade & p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>CNPJ</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Cidade</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativação</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("sq_pessoa") & "</td>"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & Nvl(RS("cnpj"),"-") & "</td>"
        ShowHTML "        <td align=""left"" title=""" & RS("nome") & """><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("cidade") & "&nbsp;(" & RS("uf") & ")</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("ativacao"),2),"-") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If w_submenu > "" Then
           ShowHTML "          <A class=""hl"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_cgccpf=" & RS("cnpj") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("nome_resumido") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do cliente"" TARGET=""menu"">Alterar</a>&nbsp;"
        Else
           ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pessoa=" & RS("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do cliente"">Alterar</A>&nbsp"
        End If
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "Grava&R=" & w_Pagina & par & "&O=E&w_sq_pessoa=" & RS("sq_pessoa") & "&w_cgccpf=" & RS("cnpj") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Bloqueia o acesso do usuário ao sistema"" onClick=""return(confirm('Confirma exclusão do cliente?'));"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='P'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='P'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Clientes ativos?</b><br>"
    If p_ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value="""" checked> Tanto faz"
    ElseIf p_ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N"" checked> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Tanto faz"
    End If
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

End Sub

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral

  Dim w_cgccpf
  Dim w_nome, w_nome_resumido, w_inicio_atividade, w_sq_segmento
  Dim w_sede, w_inscricao_estadual, w_sq_tipo_vinculo
  Dim w_pais, w_uf, w_cidade
  Dim w_tamanho_minimo_senha
  Dim w_tamanho_maximo_senha
  Dim w_maximo_tentativas
  Dim w_dias_vigencia_senha
  Dim w_dias_aviso_expiracao
  Dim w_sq_banco, w_sq_agencia
  
  Dim w_troca
  
  Dim i
  Dim w_erro
  Dim w_como_funciona
  Dim w_cor

  Dim p_gestor, p_lotacao, p_localizacao, p_nome
  Dim p_data_inicio
  Dim p_data_fim
  Dim p_solicitante
  Dim p_numero
  Dim p_ordena

  Dim w_readonly
  
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")
  w_sq_pessoa       = Request("w_sq_pessoa")
  p_data_inicio     = uCase(Request("p_data_inicio"))
  p_data_fim        = uCase(Request("p_data_fim"))
  p_solicitante     = uCase(Request("p_solicitante"))
  p_numero          = uCase(Request("p_numero"))
  p_ordena          = uCase(Request("p_ordena"))
  p_localizacao     = uCase(Request("p_localizacao"))
  p_lotacao         = uCase(Request("p_lotacao"))
  p_nome            = uCase(Request("p_nome"))
  p_gestor          = uCase(Request("p_gestor"))
  w_cgccpf          = Request("w_cgccpf")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_cgccpf               = Request("w_cgccpf")
     w_nome                 = Request("w_nome")
     w_nome_resumido        = Request("w_nome_resumido")
     w_inicio_atividade     = Request("w_inicio_atividade")
     w_sede                 = Request("w_sede")
     w_inscricao_estadual   = Request("w_inscricao_estadual")
     w_sq_tipo_vinculo      = Request("w_sq_tipo_vinculo")
     w_pais                 = Request("w_pais")
     w_uf                   = Request("w_uf")
     w_cidade               = Request("w_cidade")
     w_tamanho_minimo_senha = Request("w_tamanho_minimo_senha")
     w_tamanho_maximo_senha = Request("w_tamanho_maximo_senha")
     w_maximo_tentativas    = Request("w_maximo_tentativas")
     w_dias_vigencia_senha  = Request("w_dias_vigencia_senha")
     w_dias_aviso_expiracao = Request("w_dias_aviso_expiracao")
     w_sq_banco             = Request("w_sq_banco")
     w_sq_agencia           = Request("w_sq_agencia")
     w_sq_segmento          = Request("w_sq_segmento")
  Else
     If InStr("IAEV",O) > 0 and w_cgccpf > "" Then
        ' Recupera os dados do cliente a partir do CNPJ
        DB_GetSiwCliData RS, w_cgccpf
        If RS.RecordCount > 0 Then 
           If O = "I" Then
              ' Se o cliente informado para inclusão já existir, apresenta mensagem de erro
              ScriptOpen "JavaScript"
              ShowHTML "  alert('Cliente já existente!');"
              ShowHTML "  history.back(1);"
              ScriptClose
              DesconectaBD
              Response.End()
           Else
              w_sq_pessoa            = RS("sq_pessoa")
              w_nome                 = RS("Nome")
              w_nome_resumido        = RS("Nome_Resumido")
              w_inscricao_estadual   = RS("inscricao_estadual")
              w_inicio_atividade     = FormataDataEdicao(RS("inicio_atividade"))
              w_sede                 = RS("sede")
              w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
              w_pais                 = RS("sq_pais")
              w_uf                   = RS("co_uf")
              w_cidade               = RS("sq_cidade")
              w_tamanho_minimo_senha = RS("TAMANHO_MIN_SENHA")
              w_tamanho_maximo_senha = RS("TAMANHO_MAX_SENHA")
              w_maximo_tentativas    = RS("maximo_tentativas")
              w_dias_vigencia_senha  = RS("DIAS_VIG_SENHA")
              w_dias_aviso_expiracao = RS("DIAS_AVISO_EXPIR")
              w_sq_banco             = RS("sq_banco")
              w_sq_agencia           = RS("sq_agencia")
              w_sq_segmento          = RS("sq_segmento")
           End If
           DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCNPJ
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If w_cgccpf = "" or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Troca") > 0 Then ' Se o beneficiário ainda não foi selecionado
     ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
     Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
     ShowHTML "  theForm.Botao.value = ""Procurar"";"
     ShowHTML "}"
     ShowHTML "else {"
     Validate "w_cgccpf", "CNPJ/Cód. Estrangeiro", "CNPJ", "1", "7", "18", "", "1"
     ShowHTML "}"
  ElseIf O <> "E" and O <> "V" Then ' Se o beneficiário já foi selecionado
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     'If v_Desabilita = "" or v_Desabilita <> 1 Then
     '   Validate "p_Nome", "Nome", "1", 1, 5, 80, "1", "1"
     'End If
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     Validate "w_sq_segmento", "Segmento", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_inscricao_estadual", "Inscrição estadual", "1", "", 3, 20, "1", "1"
     Validate "w_inicio_atividade", "Início de atividade", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_pais", "País", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_uf", "UF", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_cidade", "Cidade", "SELECT", 1, 1, 10, "", "1"
     Validate "w_sq_banco", "Banco", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_sq_agencia", "Agencia", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_tamanho_minimo_senha", "Tamanho mínimo", "1", "1", "1", "2", "", "1"
     Validate "w_tamanho_maximo_senha", "Tamanho máximo", "1", "1", "1", "2", "", "1"
     Validate "w_maximo_tentativas", "Máximo tentativas", "1", "1", "1", "2", "", "1"
     Validate "w_dias_vigencia_senha", "Dias vigência", "1", "1", "1", "2", "", "1"
     Validate "w_dias_aviso_expiracao", "Aviso expiração", "1", "1", "1", "2", "", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If (w_cgccpf = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0) Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpen "onLoad='document.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_cgccpf.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If
    If w_cgccpf = "" or Instr(Request("botao"),"Troca") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    Else
       AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    End If
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"

    If (w_cgccpf = "" or InStr(Request("botao"), "Troca") > 0 or Instr(Request("botao"),"Procurar") > 0) Then
       w_nome    = Request("w_nome")
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=3><font size=2>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td><font size=1><b><u>C</u>NPJ/Cód.Estrangeiro:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" Class=""sti"" NAME=""w_cgccpf"" VALUE=""" & w_cgccpf & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this,event);"">"
       ShowHTML "            <td valign=""bottom""><INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "    </table>"
       ShowHTML "  </td>"
       ShowHTML "</tr>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Identificação Civil e Localização do Cliente</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação do cliente, bem como para faturamento.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
       If Len(w_cgccpf) = 18 Then
          ShowHTML "             <tr><td valign=""top""><font size=1>CNPJ:</font><br><b><font size=2>" & w_cgccpf
       Else
          ShowHTML "             <tr><td valign=""top""><font size=1>CPF:</font><br><b><font size=2>" & w_cgccpf
       End If
       ShowHTML "                   <INPUT type=""hidden"" name=""w_cgccpf"" value=""" & w_cgccpf & """>"
       ShowHTML "             <tr><td valign=""top""><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """ title=""Razão social do cliente, preferencialmente sem abreviações.""></td>"
       ShowHTML "                <td valign=""top""><font size=""1""><b>Nome <u>r</u>esumido:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """ title=""Nome resumido do cliente, a ser exibido nas listagens.""></td>"
       SelecaoSegMercado "Se<u>g</u>mento:", "G", "Informe a que segmento a organização está vinculada.", w_sq_segmento, null, "w_sq_segmento", null, null
       ShowHTML "          </table>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>I</u>nscrição estadual:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inscricao_estadual"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_inscricao_estadual & """ title=""Inscrição estadual do cliente.""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b>Início da a<u>t</u>ividade:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_inicio_atividade"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_atividade & """ onKeyDown=""FormataData(this,event);"" title=""Data de início das atividades do cliente, conforme contrato social.""></td>"
       ShowHTML "              <td valign=""top"" title=""Marcar \'Sim\' se o CNPJ for o principal do cliente.""><font size=""1""><b>Sede?</b><br>"
       If w_sede = "S" or w_sede = "" Then
          ShowHTML "              <input class=""str"" type=""RADIO"" name=""w_sede"" value=""S"" CHECKED> Sim <input class=""str"" type=""RADIO"" name=""w_sede"" value=""N""> Não "
       Else
          ShowHTML "              <input class=""str"" type=""RADIO"" name=""w_sede"" value=""S""> Sim <input class=""str"" type=""RADIO"" name=""w_sede"" value=""N"" CHECKED> Não "
       End If
       ShowHTML "              </td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Cidade e agência padrão</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Os dados abaixo serão automaticamente selecionados na criação de registros onde sejam solicitados. Se uma tela da aplicação solicitar os campos abaixo, eles serão automaticamente posicionados nos valores padrão.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", "Informe o valor padrão para o campo \'País\'.", w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
       SelecaoEstado "E<u>s</u>tado:", "S", "Informe o valor padrão para o campo \'Estado\'", w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", "Informe o valor padrão para o campo \'Cidade\'", w_cidade, w_pais, w_uf, "w_cidade", null, null
       ShowHTML "          </table>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr>"
       SelecaoBanco "<u>B</u>anco:", "B", "Informe o valor padrão para o campo \'Banco\'.", w_sq_banco, null, "w_sq_banco", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_sq_agencia'; document.Form.submit();"""
       SelecaoAgencia "A<u>g</u>ência:", "A", "Informe o valor padrão para o campo \'Agência\'", w_sq_agencia, Nvl(w_sq_banco,-1), "w_sq_agencia", null, null
       ShowHTML "          </table>"
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Parâmetros de Segurança</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para configuração dos parâmetros de segurança da aplicação, sendo aplicados na tela de autenticação e nas telas onde a assinatura eletrônica for exigida.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
       ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Tamanho mín<U>i</U>mo:<br><INPUT ACCESSKEY=""I"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_tamanho_minimo_senha"" size=""2"" maxlength=""2"" value=""" & w_tamanho_minimo_senha & """ title=""Tamanho mínimo da senha de acesso e assinatura eletrônica""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b>Tamanho má<U>x</U>imo:<br><INPUT ACCESSKEY=""X"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_tamanho_maximo_senha"" size=""2"" maxlength=""2"" value=""" & w_tamanho_maximo_senha & """ title=""Tamanho máximo da senha de acesso e assinatura eletrônica""></td>"
       ShowHTML "              <td valign=""top"" colspan=2><font size=""1""><b>Máximo <U>t</U>entativas:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_maximo_tentativas"" size=""2"" maxlength=""2"" value=""" & w_maximo_tentativas & """ title=""Máximo de tentativas inválidas antes de bloquear o acesso do usuário""></td>"
       ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Dias <U>v</U>igência:<br><INPUT ACCESSKEY=""V"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias_vigencia_senha"" size=""2"" maxlength=""2"" value=""" & w_dias_vigencia_senha & """ title=""Número de dias de vigência da senha de acesso""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b><U>D</U>ias de aviso:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias_aviso_expiracao"" size=""2"" maxlength=""2"" value=""" & w_dias_aviso_expiracao & """ title=""Dias de aviso para o usuário antes que sua senha de acesso tenha sua vigência expirada""></td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

       ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_pagina & par & "&w_sq_pessoa=" & Request("w_sq_pessoa") & "&O=" & O & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
       End If
       ShowHTML "          </td>"
       ShowHTML "      </tr>"
       ShowHTML "    </table>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
    End If
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_segmento           = Nothing
  Set w_sq_banco              = Nothing
  Set w_sq_agencia            = Nothing
  Set w_tamanho_minimo_senha  = Nothing
  Set w_tamanho_maximo_senha  = Nothing
  Set w_maximo_tentativas     = Nothing 
  Set w_dias_vigencia_senha   = Nothing
  Set w_dias_aviso_expiracao  = Nothing

  Set w_readonly              = Nothing
  Set w_sq_tipo_vinculo       = Nothing
  Set w_pais                  = Nothing 
  Set w_uf                    = Nothing 
  Set w_cidade                = Nothing 
  Set w_nome                  = Nothing 
  Set w_nome_resumido         = Nothing 
  Set w_cgccpf                = Nothing 
  Set w_inicio_atividade      = Nothing
  Set w_sede                  = Nothing 
  Set w_inscricao_estadual    = Nothing

  Set i                       = Nothing
  Set w_troca                 = Nothing
  Set w_erro                  = Nothing
  
  Set w_cor                   = Nothing
  Set w_sq_pessoa             = Nothing
  Set p_localizacao           = Nothing
  Set p_lotacao               = Nothing
  Set p_gestor                = Nothing
  Set p_nome                  = Nothing
  Set p_ordena                = Nothing
  Set p_numero                = Nothing
  Set p_data_inicio           = Nothing
  Set p_data_fim              = Nothing
  Set p_solicitante           = Nothing

End Sub

REM =========================================================================
REM Rotina de endereços
REM -------------------------------------------------------------------------
Sub Enderecos
  Dim w_cpf, w_logradouro, w_cep, w_padrao, w_bairro, w_complemento
  Dim w_cidade, w_uf, w_pais,w_sq_tipo_endereco,w_sq_pessoa_endereco,w_nome, w_tipo_pessoa
  Dim w_cgccpf
  
  Dim w_troca
  
  Dim i
  Dim w_erro
  
  w_cgccpf          = Request("w_cgccpf") 
  

  If P1 = 1 Then
     If Request("w_sq_pessoa") > "" Then
        w_sq_pessoa = Request("w_sq_pessoa")
     ElseIf w_cgccpf > "" Then
        DB_GetSiwCliData RS, w_cgccpf
        w_sq_pessoa         = Rs("sq_pessoa")
        DesConectaBD
     ElseIf Request("w_usuario") > "" Then
        w_sq_pessoa         = Request("w_usuario")
     Else
        w_sq_pessoa = Session("sq_pessoa")
     End If
  ElseIf P1 = 2 Then
     w_sq_pessoa = Session("p_cliente")
  End If
  
  w_sq_pessoa_endereco  = Request("w_sq_pessoa_endereco")
  w_troca               = Request("w_troca")
  w_nome                = Session("NOME")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_pessoa            = Request("w_sq_pessoa")
     w_logradouro           = Request("w_logradouro")    
     w_cep                  = Request("w_cep")
     w_padrao               = Request("w_padrao")    
     w_bairro               = Request("w_bairro")    
     w_complemento          = Request("w_complemento")
     w_cidade               = Request("w_cidade")
     w_uf                   = Request("w_uf")
     w_pais                 = Request("w_pais")
     w_sq_tipo_endereco     = Request("w_sq_tipo_endereco")    
     w_sq_pessoa_endereco   = Request("w_sq_pessoa_endereco")
     w_nome                 = Request("w_nome")
  End If
  
  If O = "L" Then
     ' Recupera todos os endereços do cliente, independente do tipo
     DB_GetAddressList RS, w_sq_pessoa, null, null, null
     RS.Sort = "padrao desc, tipo_endereco, endereco"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetAddressData RS, w_sq_pessoa_endereco
     w_logradouro           = RS("logradouro")    
     w_cep                  = RS("cep")
     w_padrao               = RS("padrao")    
     w_bairro               = RS("bairro")    
     w_complemento          = RS("complemento")
     w_cidade               = RS("sq_cidade")
     w_uf                   = RS("co_uf")
     w_pais                 = RS("sq_pais")
     w_sq_tipo_endereco     = RS("sq_tipo_endereco")    
     w_sq_pessoa_endereco   = RS("sq_pessoa_endereco")
     w_nome                 = RS("pessoa")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataCEP
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_logradouro", "Logradouro", "1", "1", "1", "60", "1", "1"
        Validate "w_complemento", "complemento", "1", "", "1", "20", "1", "1"
        Validate "w_bairro", "Bairro", "1", "", "1", "30", "1", "1"
        Validate "w_cep", "Cep", "1", "", "9", "9", "", "0123456789-"
        Validate "w_pais", "Pais", "SELECT", "", "1", "10", "", "1"
        Validate "w_uf", "UF", "SELECT", "", "1", "10", "1", "1"
        Validate "w_cidade", "Cidade", "SELECT", "1", "1", "10", "", "1"
        Validate "w_sq_tipo_endereco", "Tipo", "SELECT", "1", "1", "10", "", "1"
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
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "L" Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_logradouro.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Endereço</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados endereços cadastrados.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("tipo_endereco") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("endereco") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_pessoa_endereco=" & Rs("sq_pessoa_endereco") & "&w_handle=" & RS("sq_pessoa_endereco") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_pessoa_endereco=" & Rs("sq_pessoa_endereco") & "&w_handle=" & RS("sq_pessoa_endereco") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do endereço?');"">Excluir</A>&nbsp"
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
    ' Recupera o tipo de pessoa
    DB_GetBenef RS, w_cliente, w_sq_pessoa, null, null, null, null, null, null
    w_tipo_pessoa = RS("nm_tipo_pessoa")
     
    If w_pais = "" Then
       ' Carrega os valores padrão para país, estado e cidade
       DB_GetCustomerData RS, w_sq_pessoa
       If Not RS.EOF Then
          w_pais   = RS("sq_pais")
          w_uf     = RS("co_uf")
          w_cidade = RS("sq_cidade_padrao")
          DesconectaBD
       End If
    End If
  
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa_endereco"" value=""" & w_sq_pessoa_endereco &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>L</u>ogradouro:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_logradouro"" class=""sti"" SIZE=""60"" MAXLENGTH=""60"" VALUE=""" & w_logradouro & """ title=""Informe o logradouro de funcionamento do cliente.""></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>C</u>omplemento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_complemento"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_complemento & """ title=""Se necessário, informe o complemento do logradouro de funcionamento do cliente.""></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b><u>B</u>airro:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_bairro"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_bairro & """ title=""Informe o bairro onde este endereço localiza-se.""></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b>C<u>e</u>p:</b><br><input " & w_Disabled & " accesskey=""e"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """ onKeyDown=""FormataCEP(this,event)"" title=""Informe o CEP deste endereço.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", "Selecione na lista o país onde o endereço localiza-se.", w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", "Selecione na lista o estado deste endereço.", w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", "Selecione na lista a cidade deste endereço.", w_cidade, w_pais, w_uf, "w_cidade", null, null
    ShowHTML "          <tr><td valign=""top"" title=""O cliente pode ter vários endereços, mas apenas um pode ser o principal. Marque \'Sim\' se for o caso deste endereço.""><font size=""1""><b>Padrão:</b><br>"
    If w_padrao = "" or w_padrao = "N" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""N"" checked>Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""S"">Sim"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""N"">Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""S"" checked>Sim"
    End If  
    SelecaoTipoEndereco "<u>T</u>ipo:", "T", "Selecione na lista o tipo deste endereço.", w_sq_tipo_endereco, w_tipo_pessoa, "w_sq_tipo_endereco", null, null
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape


  Set w_tipo_pessoa         = Nothing
  Set w_cgccpf              = Nothing
  Set i                     = Nothing
  Set w_troca               = Nothing
  Set w_erro                = Nothing
  Set w_cpf                 = Nothing 
  Set w_cpf                 = Nothing
  Set w_logradouro          = Nothing    
  Set w_cep                 = Nothing    
  Set w_padrao              = Nothing
  Set w_bairro              = Nothing
  Set w_complemento         = Nothing
  Set w_cidade              = Nothing
  Set w_uf                  = Nothing
  Set w_pais                = Nothing
  Set w_sq_tipo_endereco    = Nothing    
  Set w_sq_pessoa_endereco  = Nothing
  Set w_nome                = Nothing
  

End Sub

REM =========================================================================
REM Rotina de telefones
REM -------------------------------------------------------------------------
Sub Telefones
  Dim w_sq_pessoa, w_cgccpf, w_sq_pessoa_telefone, w_sq_tipo_telefone, w_ddd
  Dim w_numero,w_padrao,w_nome, w_pais, w_uf, w_cidade, w_tipo_pessoa
  
  Dim i

  w_cgccpf          = Request("w_cgccpf") 

  If P1 = 1 Then
     If Request("w_sq_pessoa") > "" Then
        w_sq_pessoa = Request("w_sq_pessoa")
     ElseIf w_cgccpf > "" Then
        DB_GetSiwCliData RS, w_cgccpf
        w_sq_pessoa         = Rs("sq_pessoa")
        DesConectaBD
     ElseIf Request("w_usuario") > "" Then
        w_sq_pessoa = Request("w_usuario")
     Else
        w_sq_pessoa = Session("sq_pessoa")
     End If
  Elseif P1 = 2 Then
     w_sq_pessoa = Session("p_cliente")
  End If
  
  w_sq_pessoa_telefone  = Request("w_sq_pessoa_telefone")
  w_troca               = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_tipo_telefone     = Request("w_sq_tipo_telefone")
     w_cidade               = Request("w_cidade")
     w_uf                   = Request("w_uf")
     w_pais                 = Request("w_pais")
     w_ddd                  = Request("w_ddd")
     w_numero               = Request("w_numero")
     w_padrao               = Request("w_padrao")
  ElseIf O = "L" Then
     DB_GetFoneList RS, w_sq_pessoa, null, null, null
     RS.Sort = "tipo_telefone, numero"
  ElseIf InStr("AEV",O) > 0  Then
     ' Recupera os dados para edição
     DB_GetFoneData RS, w_sq_pessoa_telefone
     w_sq_pessoa            = Rs("sq_pessoa")
     w_sq_pessoa_telefone   = Rs("sq_pessoa_telefone")
     w_sq_tipo_telefone     = Rs("sq_tipo_telefone")
     w_cidade               = Rs("sq_cidade")
     w_uf                   = Rs("co_uf")
     w_pais                 = Rs("sq_pais")
     w_ddd                  = Rs("ddd")
     w_numero               = Rs("numero")
     w_padrao               = Rs("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_ddd", "DDD", "1", "1", "3", "4", "", "0123456789"
        Validate "w_numero", "Número", "1", "", "1", "25", "", "0123456789-"
        Validate "w_sq_tipo_telefone", "Tipo", "SELECT", "1", "1", "10", "", "1"
        Validate "w_pais", "Pais", "SELECT", "", "1", "10", "", "1"
        Validate "w_uf", "UF", "SELECT", "", "1", "10", "1", "1"
        Validate "w_cidade", "Cidade", "SELECT", "1", "1", "10", "", "1"
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
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("IAE",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_ddd.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""2""><b>DDD</font></td>"
    ShowHTML "          <td><font size=""2""><b>Número</font></td>"
    ShowHTML "          <td><font size=""2""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontradas despesas adicionais cadastradas.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("tipo_telefone") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ddd") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("numero") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pessoa=" & w_sq_pessoa & "&w_handle=" & RS("sq_pessoa_telefone") & "&w_sq_pessoa_telefone=" & Rs("sq_pessoa_telefone") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_pessoa=" & w_sq_pessoa & "&w_handle=" & RS("sq_pessoa_telefone") & "&w_sq_pessoa_telefone=" & Rs("sq_pessoa_telefone") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do telefone?');"">Excluir</A>&nbsp"
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
    ' Recupera o tipo de pessoa
    DB_GetBenef RS, w_cliente, w_sq_pessoa, null, null, null, null, null, null
    w_tipo_pessoa = RS("nm_tipo_pessoa")

    If w_pais = "" Then
       ' Carrega os valores padrão para país, estado e cidade
       DB_GetCustomerData RS, w_sq_pessoa
       If Not RS.EOF Then
          w_pais   = RS("sq_pais")
          w_uf     = RS("co_uf")
          w_cidade = RS("sq_cidade_padrao")
          DesconectaBD
       End If
    End If

    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa_telefone"" value=""" & w_sq_pessoa_telefone &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """ title=""Informe o DDD deste número.""></td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b><u>N</u>úmero:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_numero"" class=""sti"" SIZE=""25"" MAXLENGTH=""25"" VALUE=""" & w_numero & """ title=""Informe o número do telefone.""></td>"
    SelecaoTipoFone "<u>T</u>ipo:", "T", "Selecione na lista o tipo deste telefone.", w_sq_tipo_telefone, w_tipo_pessoa, "w_sq_tipo_telefone", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", "Selecione na lista o país onde o endereço localiza-se.", w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", "Selecione na lista o estado deste endereço.", w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", "Selecione na lista a cidade deste endereço.", w_cidade, w_pais, w_uf, "w_cidade", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top"" title=""O cliente pode ter vários telefones, mas apenas um pode ser o principal. Marque \'Sim\' se for o caso deste endereço.""><font size=""1""><b>Padrão:</b><br>"
    If w_padrao = "" or w_padrao = "N" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""N"" checked>Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""S"">Sim"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""N"">Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_padrao"" VALUE=""S"" checked>Sim"
    End If
    ShowHTML "          </table>"
    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_tipo_pessoa         = Nothing
  Set w_cidade              = Nothing
  Set w_uf                  = Nothing
  Set w_pais                = Nothing
  Set w_sq_pessoa           = Nothing
  Set w_sq_pessoa_telefone  = Nothing
  Set w_sq_tipo_telefone    = Nothing    
  Set w_ddd                 = Nothing
  Set w_numero              = Nothing
  Set w_padrao              = Nothing
  Set w_nome                = Nothing
  Set i                     = Nothing
  Set w_sq_pessoa           = Nothing 
  Set w_cgccpf              = Nothing 
  
End Sub

REM =========================================================================
REM Rotina de Contas Bancárias
REM -------------------------------------------------------------------------
Sub ContasBancarias
  Dim w_sq_pessoa, w_sq_pessoa_conta, w_banco, w_agencia, w_operacao, w_numero_conta
  Dim w_tipo_conta, w_ativo, w_padrao

  Dim w_troca, w_cgccpf
  
  Dim i
  Dim w_erro
  
  w_cgccpf          = Request("w_cgccpf")

  If P1 = 1 Then
     If Request("w_sq_pessoa") > "" Then
        w_sq_pessoa = Request("w_sq_pessoa")
     ElseIf w_cgccpf > "" Then
        DB_GetSiwCliData RS, w_cgccpf
        w_sq_pessoa         = Rs("sq_pessoa")
        DesConectaBD
     ElseIf Request("w_usuario") > "" Then
        w_sq_pessoa = Request("w_usuario")
     Else
        w_sq_pessoa = Session("sq_pessoa")
     End If
  ElseIf P1 = 2 Then
     w_sq_pessoa = Session("p_cliente")
  End If

  w_troca             = Request("w_troca")
  w_sq_pessoa_conta   = Request("w_sq_pessoa_conta")

  If O = "L" Then
     ' Recupera as contas bancárias do cliente
     DB_GetContaBancoList RS, w_sq_pessoa, null, null
     RS.Sort = "tipo_conta, banco, numero"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados da conta bancária informada
     DB_GetContaBancoData RS, w_sq_pessoa_conta
     w_banco                = RS("sq_banco")
     w_agencia              = RS("agencia")
     w_numero_conta         = RS("numero")
     w_operacao             = RS("operacao")
     w_tipo_conta           = RS("tipo_conta")
     w_ativo                = RS("ativo")
     w_padrao               = RS("padrao")
     DesconectaBD
  ElseIf w_Troca > "" Then
     w_banco                = Request("w_banco")
     w_agencia              = Request("w_agencia")
     w_numero_conta         = Request("w_numero_conta")
     w_operacao             = Request("w_operacao")
     w_tipo_conta           = Request("w_tipo_conta")
     w_ativo                = Request("w_ativo")
     w_padrao               = Request("w_padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  If Instr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataValor
     FormataData
     FormataDataHora
     ValidateOpen "Validacao"
     If O = "I" Then
        Validate "w_banco", "Banco", "SELECT", "1", "1", "10", "", "1"
        Validate "w_agencia", "Agência", "1", "1", "4", "4", "", "0123456789"
        Validate "w_operacao", "Operacao", "1", "", "1", "3", "1", "1"
        Validate "w_numero_conta", "Conta corrente", "1", "1", "3", "12", "", "0123456789-XP"
     End If
     If Session("p_portal") = "" Then Validate "w_assinatura", "Assinatura eletrônica", "1", "1", "3", "14", "1", "1" End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_banco.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Banco</font></td>"
    ShowHTML "          <td><font size=""1""><b>Agência</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conta</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Não foram encontradas despesas adicionais cadastradas.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("tipo_conta") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("banco") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("agencia") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("numero") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_pessoa_conta=" & RS("sq_pessoa_conta") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_pessoa_conta=" & RS("sq_pessoa_conta") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão da conta?');"">Excluir</A>&nbsp"
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
  ElseIf Instr("IAEV",O) > 0 Then

    If w_banco = "" Then
       ' Carrega os valores padrão para banco e agência
       DB_GetCustomerData RS, w_sq_pessoa
       If Not RS.EOF Then
          w_banco   = RS("sq_banco")
          w_agencia = RS("codigo")
          DesconectaBD
       End If
    End If

    If O = "A" Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa_conta"" value=""" & w_sq_pessoa_conta &""">"
    If O = "A" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_banco"" value=""" & w_banco &""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_agencia"" value=""" & w_agencia &""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_operacao"" value=""" & w_operacao &""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_numero_conta"" value=""" & w_numero_conta &""">"
    End If

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoBanco "<u>B</u>anco:", "B", "Informe o valor padrão para o campo \'Banco\'.", w_banco, null, "w_banco", null, null
    ShowHTML "              <td><font size=""1""><b><u>A</u>gência:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_agencia"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_agencia & """ title=""Informe o número da agência, com quatro posições, sem dígito verificador. Preencha com zeros à esquerda, se necessário. Exempo: para agência 3592-0, informe 3592; para agência 206, informe 0206.""></td>"
    ShowHTML "              <td><font size=""1""><b><u>O</u>peração:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_operacao"" class=""sti"" SIZE=""3"" MAXLENGTH=""3"" VALUE=""" & w_operacao & """ title=""Informe um valor apenas se o seu banco trabalhar com o campo Operação.""></td>"
    ShowHTML "              <td><font size=""1""><b><u>C</u>onta corrente:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_numero_conta"" class=""sti"" SIZE=""12"" MAXLENGTH=""12"" VALUE=""" & w_numero_conta & """ title=""Informe o número da conta corrente. Se a conta tiver dígito verificador (DV), informe-o separado por hífen (-). Exemplo sem DV: 0391039. Exemplos com DV: 9301-3, 91093-X, 01934-P.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td title=""Informe se a conta é corrente ou de poupança.""><font size=""1""><b>Tipo conta</b><br>"
    If w_tipo_conta = "" or w_tipo_conta = "1" Then
       ShowHTML "              <input class=""str"" type=""radio"" name=""w_tipo_conta"" VALUE=""1"" checked>Corrente <input class=""str"" type=""radio"" name=""w_tipo_conta"" VALUE=""2"">Poupança"
    Else
       ShowHTML "              <input class=""str"" type=""radio"" name=""w_tipo_conta"" VALUE=""1"">Corrente <input class=""str"" type=""radio"" name=""w_tipo_conta"" VALUE=""2"" checked>Poupança"
    End If
    ShowHTML "          <td title=""Indique se esta conta está ativa, clicando sobre a opção \'Sim\'.""><font size=""1""><b>Ativa?</b><br>"
    If w_ativo = "" or w_ativo = "N" Then
       ShowHTML "              <input class=""str"" type=""radio"" name=""w_ativo"" VALUE=""N"" checked>Não <input class=""str"" type=""radio"" name=""w_ativo"" VALUE=""S"">Sim"
    Else
       ShowHTML "              <input class=""str"" type=""radio"" name=""w_ativo"" VALUE=""N"">Não <input class=""str"" type=""radio"" name=""w_ativo"" VALUE=""S"" checked>Sim"
    End If
    ShowHTML "          <td valign=""top"" title=""Indique se esta conta é a padrão da organização, clicando sobre a opção \'Sim\'.<br>Somente pode haver uma conta padrão.""><font size=""1""><b>Conta padrão?</b><br>"
    If w_padrao = "" or w_padrao = "N" Then
       ShowHTML "              <input type=""radio"" name=""w_padrao"" class=""str"" VALUE=""N"" checked>Não <input type=""radio"" name=""w_padrao"" class=""str"" VALUE=""S"">Sim"
    Else
       ShowHTML "              <input type=""radio"" name=""w_padrao"" class=""str"" VALUE=""N"">Não <input type=""radio"" name=""w_padrao"" class=""str"" VALUE=""S"" checked>Sim"
    End If
    ShowHTML "          </table>"
    If Session("p_portal") = "" Then ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>" End If
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_pessoa=" & Request("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set i                     = Nothing
  Set w_troca               = Nothing
  Set w_erro                = Nothing
  Set w_cgccpf              = Nothing
  Set w_sq_pessoa           = Nothing 
  Set w_sq_pessoa_conta     = Nothing 
  Set w_banco               = Nothing 
  Set w_agencia             = Nothing 
  Set w_operacao            = Nothing 
  Set w_numero_conta        = Nothing
  Set w_tipo_conta          = Nothing 
  Set w_ativo               = Nothing 
  Set w_padrao              = Nothing 

End Sub

REM =========================================================================
REM Rotina de módulos contratados
REM -------------------------------------------------------------------------
Sub Modulos
  Dim w_sq_pessoa, w_cgccpf, w_sq_modulo, w_nome, w_sigla, w_objetivo_geral
  
  Dim i
  
  w_cgccpf          = Request("w_cgccpf")

  If Request("w_sq_pessoa") > "" Then
     w_sq_pessoa = Request("w_sq_pessoa")
  Else
     DB_GetSiwCliData RS, w_cgccpf
     w_sq_pessoa         = Rs("sq_pessoa")
     DesConectaBD
  End If
  
  w_sq_modulo           = Request("w_sq_modulo")
  w_troca               = Request("w_troca")
  
  If w_Troca > "" Then
     w_sq_modulo = Request("w_sq_modulo")
  ElseIf O = "L" Then
     ' Recupera os módulos contratados pelo cliente
     DB_GetSiwCliModLis RS, w_sq_pessoa, null, null
  End If
  
  If w_sq_modulo > "" Then
     ' Recupera os dados para edição
     DB_GetModData RS, w_sq_modulo
     w_nome            = Rs("nome")
     w_sigla           = Rs("sigla")
     w_objetivo_geral  = Rs("objetivo_geral")
     DesconectaBD
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_modulo", "Módulo", "SELECT", "1", "1", "10", "", "1"
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
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("I",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_sq_modulo.focus()';"
  ElseIf InStr("AE",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Módulo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Objetivo geral</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontradas despesas adicionais cadastradas.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("objetivo_geral") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        'ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_modulo=" & Rs("sq_modulo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_sq_pessoa=" & w_sq_pessoa & "&w_sq_modulo=" & Rs("sq_modulo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do modulo?');"">Excluir</A>&nbsp"
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

    If InStr("AEV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    If O <> "I" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_modulo"" value=""" & w_sq_modulo &""">"
    End If

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    SelecaoModulo "<u>M</u>ódulo:", "M", null, w_sq_modulo, w_sq_pessoa, "w_sq_modulo", "DISPONIVEL", "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_assinatura'; document.Form.submit();"" name=""w_sq_modulo"" title=""Selecione na lista o módulo desejado. Módulos já selecionados não serão exibidos.','white')"""
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1"">Sigla:<br><b>" & w_sigla & "</b>"
    ShowHTML "              <td valign=""top""><font size=""1"">Objetivo:<br><b>" & w_objetivo_geral & "</b>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_pessoa=" & w_sq_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_modulo           = Nothing
  Set w_nome                = Nothing
  Set w_sigla               = Nothing
  Set w_objetivo_geral      = Nothing
  Set i                     = Nothing
  Set w_sq_pessoa           = Nothing 
  Set w_cgccpf              = Nothing 
  
End Sub

REM =========================================================================
REM Rotina de configuração
REM -------------------------------------------------------------------------
Sub Configuracao
  Dim w_cgccpf
  Dim w_smtp_server, w_siw_email_nome, w_siw_email_conta, w_siw_email_senha
  Dim w_siw_email_senha1, w_logo, w_logo1, w_fundo
  Dim w_upload_maximo
  
  Dim w_troca
  
  Dim i
  Dim w_erro
  Dim w_como_funciona
  Dim w_cor

  Dim w_readonly
  
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_cgccpf          = Request("w_cgccpf")

  If P1 = 1 Then
     If Request("w_sq_pessoa") > "" Then
        w_sq_pessoa = Request("w_sq_pessoa")
     Else
        DB_GetSiwCliData RS, w_cgccpf
        w_sq_pessoa         = Rs("sq_pessoa")
        DesConectaBD
     End If
  ElseIf P1 = 2 Then
     w_sq_pessoa = Session("p_cliente")
  End IF
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_cgccpf               = Request("w_cgccpf")
     w_smtp_server          = Request("w_smtp_server")
     w_siw_email_nome       = Request("w_siw_email_nome")
     w_siw_email_conta      = Request("w_siw_email_conta")
     w_siw_email_senha      = Request("w_siw_email_senha")
     w_siw_email_senha1     = Request("w_siw_email_senha1")
     w_logo                 = Request("w_logo")
     w_logo1                = Request("w_logo1")
     w_fundo                = Request("w_fundo")
     w_upload_maximo        = Request("w_upload_maximo")
  Else
     If InStr("IAEV",O) > 0 Then
        ' Recupera a configuração do site do cliente
        DB_GetCustomerData RS, w_sq_pessoa
        w_smtp_server            = RS("smtp_server")
        w_siw_email_nome         = RS("siw_email_nome")
        w_siw_email_conta        = RS("siw_email_conta")
        w_siw_email_senha        = RS("siw_email_senha")
        w_logo                   = RS("logo")
        w_logo1                  = RS("logo1")
        w_fundo                  = RS("fundo")
        w_upload_maximo          = RS("upload_maximo")
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCNPJ
  CheckBranco
  FormataData
  ProgressBar w_dir, UploadID
  ValidateOpen "Validacao"
  Validate "w_smtp_server", "Servidor SMTP", "1", 1, 3, 60, "1", "1"
  Validate "w_siw_email_nome", "Nome", "1", 1, 3, 60, "1", "1"
  Validate "w_siw_email_conta", "Conta", "1", 1, 3, 60, "1", "1"
  Validate "w_siw_email_senha", "Senha", "1", "", 3, 60, "1", "1"
  Validate "w_siw_email_senha1", "Senha", "1", "", 3, 60, "1", "1"
  ShowHTML "  if (theForm.w_siw_email_senha.value != theForm.w_siw_email_senha1.value) { "
  ShowHTML "     alert('Favor informar dois valores iguais para a senha!');"
  ShowHTML "     theForm.w_siw_email_senha.value='';"
  ShowHTML "     theForm.w_siw_email_senha1.value='';"
  ShowHTML "     theForm.w_siw_email_senha.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  Validate "w_upload_maximo", "Limite para upload", "1", "1", 1, 18, "", "0123456789"
  Validate "w_logo", "Logo telas e relatórios", "1", "", 3, 100, "1", "1"
  Validate "w_logo1", "Logo menu", "1", "", 3, 100, "1", "1"
  Validate "w_fundo", "Fundo menu", "1", "", 3, 100, "1", "1"
  Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  ShowHTML "if (theForm.w_logo.value != '') {return ProgressBar();}"
  ShowHTML "if (theForm.w_logo1.value != '') {return ProgressBar();}"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_smtp_server.focus()';"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    ShowHTML "<FORM action=""" & w_Pagina & "Grava&O=" & O & "&UploadID=" & UploadID & "&SG=" & SG & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"" ENCTYPE=""multipart/form-data"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Configuração dos serviços de e-Mail e Upload</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados do bloco abaixo são utilizados pelo mecanismo de upload e de envio de mensagens automáticas da aplicação. A incorreção nos dados impossibilitará o envio de e-mail e o recebimento de arquivos.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
    ShowHTML "          <tr valign=""top"">"
    ShowHTML "             <td><font size=""1""><b><u>S</u>ervidor SMTP:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_smtp_server"" class=""sti"" SIZE=""30"" MAXLENGTH=""60"" VALUE=""" & w_smtp_server & """ title=""Nome do servidor SMTP.""></td>"
    ShowHTML "             <td colspan=2><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_siw_email_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_siw_email_nome & """ title=""Nome a ser exibido como remetente da mensagem automática.""></td>"
    ShowHTML "          <tr valign=""top"">"
    ShowHTML "             <td><font size=""1""><b><u>C</u>onta de e-mail:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_siw_email_conta"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_siw_email_conta & """ title=""Conta de e-mail a ser usada quando o remetente for a aplicação.""></td>"
    ShowHTML "             <td><font size=""1""><b><u>S</u>enha da conta:</b><br><input " & w_Disabled & " accesskey=""S"" type=""password"" name=""w_siw_email_senha"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE="""" title=""Senha da conta de e-mail a ser usada quando o remetente for a aplicação.""></td>"
    ShowHTML "             <td><font size=""1""><b><u>R</u>edigite a senha:</b><br><input " & w_Disabled & " accesskey=""R"" type=""password"" name=""w_siw_email_senha1"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE="""" title=""Redigite a senha da conta de e-mail.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td><font size=""1""><b><u>L</u>imite para upload (em bytes):</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_upload_maximo"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_upload_maximo & """ title=""Informe o tamanho máximo, em bytes, a ser aceito nas rotinas de upload de arquivos.""></td>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Logomarca</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Indique abaixo os arquivos que contém as logomarcas da organização, a serem usados no cabeçalho dos relatórios e nas telas da aplicação. O arquivo deve ser uma imagem no formato JPG ou GIF, com tamanho máximo de 150x150pixels. Você pode indicar o mesmo arquivo nos dois campos.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b>L<u>o</u>gomarca telas e relatórios:</b><br><input " & w_Disabled & " accesskey=""O"" type=""FILE"" name=""w_logo"" class=""sti"" SIZE=""45"" MAXLENGTH=""100"" VALUE="""" title=""Localize o arquivo da logomarca a ser utilizada nas telas e relatórios da aplicação. Uma cópia dele será transferida para o servidor da aplicação por \'upload\'.""></td>"
    If w_logo > "" Then
       ShowHTML "              <td valign=""top""><font size=""1""><b>Imagem atual:</b><br>"
       ShowHTML "              <img src=""" & LinkArquivo(null, w_sq_pessoa, "img\logo" & Mid(w_logo,Instr(w_logo,"."),30), null, null, null, "EMBED") & """ border=1>"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Lo<u>g</u>omarca menu:</b><br><input " & w_Disabled & " accesskey=""G"" type=""FILE"" name=""w_logo1"" class=""sti"" SIZE=""45"" MAXLENGTH=""100"" VALUE="""" title=""Localize o arquivo da logomarca a ser utilizada no menu da aplicação. Uma cópia dele será transferida para o servidor da aplicação por \'upload\'.""></td>"
    If w_logo1 > "" Then
       ShowHTML "              <td valign=""top""><font size=""1""><b>Imagem atual:</b><br>"
       ShowHTML "              <img src=""" & LinkArquivo(null, w_sq_pessoa, "img\logo1" & Mid(w_logo1,Instr(w_logo1,"."),30), null, null, null, "EMBED") & """ border=1>"
    End If
    ShowHTML "          </table>"

    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Imagem de fundo do menu</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Indique abaixo o arquivo que contém a imagem de fundo a ser aplicada no menu. O arquivo deve ser uma imagem no formato JPG ou GIF, com tamanho máximo de 10x10pixels.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1""><b>Imagem de <u>f</u>undo do menu:</b><br><input " & w_Disabled & " accesskey=""F"" type=""FILE"" name=""w_fundo"" class=""sti"" SIZE=""45"" MAXLENGTH=""100"" VALUE="""" title=""Localize o arquivo a ser usado como fundo do menu. Uma cópia dele será transferida para o servidor da aplicação por \'upload\'.""></td>"
    If w_fundo > "" Then
       ShowHTML "              <td valign=""top""><font size=""1""><b>Imagem atual:</b><br>"
       ShowHTML "              <img src=""" & LinkArquivo(null, w_sq_pessoa, "img\fundo" & Mid(w_fundo,Instr(w_fundo,"."),30), null, null, null, "EMBED") & """ border=1>"
    End If
    ShowHTML "          </table>"

    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Caminho físico da aplicação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Utilize o caminho abaixo na configuração das constantes <b>conDiretorio</b> e <b>conFilePhysical</b> do arquivo <b>constants.inc</b>.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr><td valign=""top""><font size=""1"">Caminho físico: <b>" & Request.ServerVariables("APPL_PHYSICAL_PATH") & "</b></td>"
    ShowHTML "          </table>"

    ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_upload_maximo         = Nothing
  Set w_smtp_server           = Nothing
  Set w_siw_email_nome        = Nothing
  Set w_siw_email_conta       = Nothing
  Set w_siw_email_senha       = Nothing
  Set w_logo                  = Nothing
  Set w_logo1                 = Nothing
  Set w_fundo                 = Nothing

  Set w_readonly              = Nothing
  Set w_cgccpf                = Nothing 

  Set i                       = Nothing
  Set w_troca                 = Nothing
  Set w_erro                  = Nothing
  
  Set w_cor                   = Nothing
  Set w_sq_pessoa             = Nothing

End Sub

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visual

  Dim w_sq_pessoa, w_cgccpf, w_Erro, w_logo

  w_sq_pessoa       = Request("w_sq_pessoa")
  w_cgccpf          = Request("w_cgccpf")

  If Request("w_sq_pessoa") > "" Then
     w_sq_pessoa = Request("w_sq_pessoa")
  Else
     DB_GetSiwCliData RS, w_cgccpf
     w_sq_pessoa         = Rs("sq_pessoa")
     DesConectaBD
  End If
  
  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_sq_pessoa
  If RS("logo") > "" Then
     w_logo = "img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  cabecalho

  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ShowHTML "<TITLE>Cliente</TITLE>"
  ShowHTML "</HEAD>"  
  BodyOpen "onLoad='document.focus()'; "
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_sq_pessoa, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=5 COLOR=""#000000"">"
  ShowHTML "CLIENTE"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"

  ' Chama a rotina de visualização dos dados do cliente, na opção "Listagem"
  VisualCliente w_sq_pessoa, "L" 

  Rodape

  Set w_erro                = Nothing 
  Set w_cgccpf              = Nothing 
  Set w_logo                = Nothing 
  Set w_sq_pessoa           = Nothing

End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_Chave, w_chave1, w_chave2, w_chave3
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  Select Case SG
    Case "CLGERAL"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutSiwCliente O, _
             Request("w_sq_pessoa"), Session("p_cliente"), Request("w_Nome"), Request("w_Nome_Resumido"), _
             Request("w_inicio_atividade"), Request("w_cgccpf"), Request("w_sede"), Request("w_inscricao_estadual"), _
             Request("w_cidade"), Request("w_tamanho_minimo_senha"), Request("w_tamanho_maximo_senha"), Request("w_dias_vigencia_senha"), _
             Request("w_dias_aviso_expiracao"), Request("w_maximo_tentativas"), Request("w_sq_agencia"), Request("w_sq_segmento")
          
          ScriptOpen "JavaScript"
          If O = "I" Then
             ShowHTML "  parent.menu.location='Menu.asp?par=ExibeDocs&O=A&w_cgccpf=" & Request("w_cgccpf") & "&w_documento=" & Request("w_nome_resumido") & "&R=cliente.asp?par=INICIAL&SG=CLIENTE&TP=" & RemoveTP(TP) & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS, Session("p_cliente"), SG
             ShowHTML "  location.href='" & RS("link") & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&w_cgccpf=" & Request("w_cgccpf") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
             DesconectaBD
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CLIENTE"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutSiwCliente O, _
             Request("w_sq_pessoa"), Session("p_cliente"), Request("w_Nome"), Request("w_Nome_Resumido"), _
             Request("w_inicio_atividade"), Request("w_cgccpf"), Request("w_sede"), Request("w_inscricao_estadual"), _
             Request("w_cidade"), Request("w_tamanho_minimo_senha"), Request("w_tamanho_maximo_senha"), Request("w_dias_vigencia_senha"), _
             Request("w_dias_aviso_expiracao"), Request("w_maximo_tentativas"), Request("w_sq_agencia"), Request("w_sq_segmento")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&w_cgccpf=" & Request("w_cgccpf") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "CLENDER"  'ENDEREÇO
        
        If O = "I" or O = "A" Then
           ' Se o endereço a ser gravado foi indicado como padrão, verifica se não existe algum outro
           ' nesta situação. Só pode haver um endereço padrão para a pessoa dentro de cada tipo de endereço.
           If Request("w_padrao") = "S" Then
              DB_GetAddressList RS, Request("w_sq_pessoa"), Request("w_sq_pessoa_endereco"), "ENDERECO", Request("w_sq_tipo_endereco")
              If Not RS.EOF Then
                If cDbl(RS("sq_pessoa_endereco")) <> cDbl(Nvl(Request("w_sq_pessoa_endereco"),0)) Then
                   ScriptOpen "JavaScript"
                   ShowHTML "  alert('ATENÇÃO: Só pode haver um valor padrão em cada tipo de endereço. Favor verificar!');"
                   ShowHTML "  history.back(1);"
                   ScriptClose
                   Exit Sub
                End If
              End If
           End If
        End If
        
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then

            DML_PutCoPesEnd O, _
               Request("w_sq_pessoa_endereco"), Request("w_sq_pessoa"), Request("w_sq_tipo_endereco"), Request("w_logradouro"), _
               Request("w_complemento"), Request("w_cidade"), Request("w_bairro"), Request("w_cep"), Request("w_padrao")

            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
            ScriptClose          
        Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
        End If
    Case "CLFONE"  'TELEFONE
        If O = "I" or O = "A" Then
           ' Se o telefone a ser gravado foi indicado como padrão, verifica se não existe algum outro
           ' nesta situação. Só pode haver um telefone padrão para a pessoa.
           If Request("w_padrao") = "S" Then
              DB_GetFoneList RS, Request("w_sq_pessoa"), Request("w_sq_pessoa_telefone"), "TELEFONE", Request("w_sq_tipo_telefone")
              If RS.RecordCount > 0 Then
                If cDbl(RS("sq_pessoa_telefone")) <> cDbl(Nvl(Request("w_sq_pessoa_telefone"),0)) Then
                 ScriptOpen "JavaScript"
                 ShowHTML "  alert('ATENÇÃO: Só pode haver um valor padrão em cada tipo de telefone. Favor verificar.!');"
                 ShowHTML "  history.back(1);"
                 ScriptClose
                 Exit Sub
                End If
              End If
           End If
        End If
        
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then

            DML_PutCoPesTel O, _
               Request("w_sq_pessoa_telefone"), Request("w_sq_pessoa"), Request("w_sq_tipo_telefone"), _
               Request("w_cidade"), Request("w_ddd"), Request("w_numero"), Request("w_padrao")

            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
            ScriptClose          
        Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
        End If
    Case "CLCONTA"  'Contas bancárias
        If O = "I" or O = "A" Then
           w_mensagem = ""
           
           ' Só pode haver uma conta padrão para a pessoa
           If Request("w_padrao") = "S" Then
              DB_GetContaBancoList RS, Request("w_sq_pessoa"), Request("w_sq_pessoa_conta"), "CONTASBANCARIAS"
              If RS.RecordCount > 0 Then
                If cDbl(RS("sq_pessoa_conta")) <> cDbl(Nvl(Request("w_sq_pessoa_conta"),0)) Then
                 w_mensagem = "ATENÇÃO: Só pode haver uma conta padrão. Favor verificar."
                End If
              End If
              DesconectaBD
           End If
           
           ' Verifica se a agência informada existe para o banco selecionado
           DB_GetBankHouseList RS, Request("w_banco"), null, null, Request("w_agencia")
           If RS.RecordCount = 0 Then
              w_mensagem = "Agência inexistente para o banco informado. Favor verificar."
           Else
              w_chave = RS("sq_agencia")
           End If
           DesconectaBD

           ' Se algum erro for detectado, apresenta mensagem e aborta a gravação
           If w_mensagem > "" Then
              ScriptOpen "JavaScript"
              ShowHTML "  alert('" & w_mensagem & "');"
              ShowHTML "  history.back(1);"
              ScriptClose
              exit sub
           End If
        End If
        
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then

            DML_PutCoPesConBan O, _
               Request("w_sq_pessoa_conta"), Request("w_sq_pessoa"), Request("w_tipo_conta"), _
               w_Chave, Request("w_operacao"), Request("w_numero_conta"), Request("w_ativo"), _
               Request("w_padrao")

            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
            ScriptClose
        Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
        End If

    Case "CLMODULO"  'Módulos
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then

            DML_PutSiwCliMod O, Request("w_sq_modulo"), Request("w_sq_pessoa")

            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=L&w_sq_pessoa=" & Request("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
            ScriptClose
        Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
        End If
    Case "CLCONFIG"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          ' O tratamento deste tipo de gravação é diferenciado, em função do uso do objeto upload
          Dim w_logo, w_logo1, w_fundo
          Set FS = CreateObject("Scripting.FileSystemObject")
          If ul.State = 0 Then
             w_maximo     = ul.Texts.Item("w_upload_maximo")
             For Each Field in ul.Files.Items
                If Field.Length > 0 Then
                   ' Verifica a necessidade de criação dos diretórios do cliente
                   Set FS = CreateObject("Scripting.FileSystemObject")
                   If Not (FS.FolderExists (DiretorioCliente(ul.Texts.Item("w_sq_pessoa")))) Then
                      Set F1 = FS.CreateFolder(DiretorioCliente(ul.Texts.Item("w_sq_pessoa")))
                      Set F1 = FS.CreateFolder(DiretorioCliente(ul.Texts.Item("w_sq_pessoa")) & "\img")
                      Set F1 = Nothing
                   End If
                   Set FS = Nothing
                   ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                   If cDbl(Field.Length) > cDbl(w_maximo) Then 
                      ScriptOpen("JavaScript") 
                      ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & cDbl(w_maximo)/1024 & " KBytes!');" 
                      ShowHTML "  history.back(1);" 
                      ScriptClose 
                      Response.End() 
                      exit sub 
                    End If 
     
                   Set FS = CreateObject("Scripting.FileSystemObject")
                   If Field.Name = "w_logo" Then
                      w_file = "logo" & Mid(Field.FileName,Instr(Field.FileName,"."),10)
                      w_logo = "logo" & Mid(Field.FileName,Instr(Field.FileName,"."),10)
                   Else
                      w_logo = null
                   End If
                   If Field.Name = "w_logo1" Then
                      w_file  = "logo1" & Mid(Field.FileName,Instr(Field.FileName,"."),10)
                      w_logo1 = "logo1" & Mid(Field.FileName,Instr(Field.FileName,"."),10)
                   Else
                      w_logo1 = null
                   End If
                   If Field.Name = "w_fundo" Then
                      w_file  = "fundo" & Mid(Field.FileName,Instr(Field.FileName,"."),10)
                      w_fundo = "fundo" & Mid(Field.FileName,Instr(Field.FileName,"."),10)                   
                   Else
                      w_fundo = null
                   End If
                   If w_file > "" Then
                      Field.SaveAs conFilePhysical & w_cliente & "\img\" & w_file
                   End If
                End If
             Next
          Else
             ScriptOpen "JavaScript" 
             ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
             ScriptClose 
             Response.End()
             Exit Sub
          End If
          
          DML_SIWCliConf _
                   ul.Texts.Item("w_sq_pessoa"), null, null, null, null, null, ul.Texts.Item("w_smtp_server"), _
                   ul.Texts.Item("w_siw_email_nome"), ul.Texts.Item("w_siw_email_conta"), _
                   ul.Texts.Item("w_siw_email_senha"), w_logo, w_logo1, w_fundo, "SERVIDOR", _
                   ul.Texts.Item("w_upload_maximo")

          Session("smtp_server")     = ul.Texts.Item("w_smtp_server")
          Session("siw_email_nome")  = ul.Texts.Item("w_siw_email_nome")
          Session("siw_email_conta") = ul.Texts.Item("w_siw_email_conta")
          If ul.Texts.Item("w_siw_email_senha") > "" Then Session("siw_email_senha") = ul.Texts.Item("w_siw_email_senha")End If
          Set w_logo   = Nothing
          Set w_logo1  = Nothing
          Set w_fundo  = Nothing
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=A&w_sq_pessoa=" & ul.Texts.Item("w_sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
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
  Set w_Chave               = Nothing
  Set w_Chave1              = Nothing
  Set w_Chave2              = Nothing
  Set w_Chave3              = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  If Session("p_portal") = "" Then
     If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
        ScriptOpen "JavaScript"
        ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
        ShowHTML " top.location.href='Default.asp'; "
        ScriptClose
        Exit Sub
     End If
  End If

  Select Case Par
    Case "INICIAL"
       Inicial
    Case "GERAL"
       Geral
    Case "ENDERECO"
       Enderecos
    Case "TELEFONE"
       Telefones
    Case "CONTABANCARIA"
       ContasBancarias
    Case "MODULO"
       Modulos
    Case "CONFIGURACAO"
       Configuracao
    Case "VISUAL"
       Visual
    Case "GRAVA"
       Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
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

