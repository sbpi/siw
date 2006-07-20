<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Viagem.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Colaborador.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DML_CV.asp" -->
<!-- #INCLUDE FILE="DB_CV.asp" -->
<!-- #INCLUDE FILE="ValidaColaborador.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Colaborador.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerenciar o cadastramento de colaboradores
REM Mail     : celso@sbpi.com.br
REM Criacao  : 15/08/2005 10:00
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = I   : Inclus�o
REM                   = A   : Altera��o
REM                   = E   : Exclus�o
REM                   = L   : Listagem

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu, w_ano
Dim P1, P2, P3, P4, TP, SG, p_ordena
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa
Dim ul,File
Dim w_pag, w_linha

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
p_ordena     = uCase(Request("p_ordena"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Colaborador.asp?par="
w_Dir        = "mod_rh/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then
   O = "P"
End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclus�o"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "A" 
     w_TP = TP & " - Altera��o"
  Case "E" 
     If par = "CONTRATO" Then
        w_TP = TP & " - Encerramento"
     Else
        w_TP = TP & " - Exclus�o"
     End If
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configura��o do servi�o
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configura��o do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set p_ordena      = Nothing
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
REM Rotina da tabela de colaborador
REM -------------------------------------------------------------------------
Sub Inicial

  Dim p_contrato_colaborador, p_modalidade_contrato, p_unidade_lotacao, p_filhos_lotacao
  Dim p_unidade_exercicio, p_filhos_exercicio, p_afastamento, p_dt_ini, p_dt_fim
  Dim p_ferias, p_viagem
  Dim w_troca, w_sq_pessoa, w_nome, w_cpf, w_botao
  Dim w_erro
  
  w_troca = Request("w_troca")

  p_contrato_colaborador = uCase(Request("p_contrato_colaborador"))
  p_modalidade_contrato  = uCase(Request("p_modalidade_contrato"))
  p_unidade_lotacao      = uCase(Request("p_unidade_lotacao"))
  p_filhos_lotacao       = uCase(Request("p_filhos_lotacao"))
  p_unidade_exercicio    = uCase(Request("p_unidade_exercicio"))
  p_filhos_exercicio     = uCase(Request("p_filhos_exercicio"))
  p_afastamento          = uCase(Request("p_afastamento"))
  p_dt_ini               = uCase(Request("p_dt_ini"))
  p_dt_fim               = uCase(Request("p_dt_fim"))
  p_ferias               = uCase(Request("p_ferias"))
  p_viagem               = uCase(Request("p_viagem"))
  w_sq_pessoa            = uCase(Request("w_sq_pessoa"))
  w_nome                 = uCase(Request("w_nome"))
  w_cpf                  = uCase(Request("w_cpf"))
  w_botao                = uCase(Request("w_botao"))
  
  If O = "L" Then
     DB_GetGPColaborador RS, w_cliente, p_contrato_colaborador, null, null, p_modalidade_contrato, p_unidade_lotacao, p_filhos_lotacao, p_unidade_exercicio, p_filhos_exercicio, p_afastamento, p_dt_ini, p_dt_fim, p_ferias, p_viagem, null, "COLABORADOR"
     RS.sort = "nome_resumido"
  ElseIf O = "E" Then
    DB_GetCV RS, w_cliente, w_sq_pessoa, "CVIDENT", "DADOS"
    DB_GetGPContrato RS1, w_cliente, null, w_sq_pessoa, null, null, null, null, null, null, null, null, null, null
    RS1.Filter = "fim = null"
    w_erro = ValidaColaborador(w_cliente, w_sq_pessoa, RS1("chave"), null)
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "Javascript"
  modulo
  CheckBranco
  FormataData
  FormataCPF
  ValidateOpen "Validacao"
  If InStr("P",O) > 0 Then
     ShowHTML "  var cont = 0;"   
     ShowHTML "  for (i=0;i<theForm.p_afastamento.length;i++) {"
     ShowHTML "    if (theForm.p_afastamento[i].checked) {"
     ShowHTML "      cont = cont+1;"
     ShowHTML "    }"   
     ShowHTML "  }"  
     ShowHTML "if (theForm.p_contrato_colaborador.value == '' && theForm.p_modalidade_contrato.value == '' && theForm.p_unidade_lotacao.value == '' && theForm.p_unidade_exercicio.value == '' && theForm.p_ferias.checked == false && theForm.p_viagem.checked == false) { "
     ShowHTML "  if (cont == 0) { "
     ShowHTML "    alert('Pelo menos um crit�rio de filtragem deve ser informado!');"
     ShowHTML "    return false;"
     ShowHTML "  }"
     ShowHTML "}"     
     ShowHTML "if (theForm.p_filhos_lotacao.checked && theForm.p_unidade_lotacao.value == '') {"
     ShowHTML "  alert('Os campos """"Exibir colaboradores das unidades subordinadas"""" somente podem ser marcados se os respectivos campos de unidade forem selecionados!');"
     ShowHTML "  return false;"
     ShowHTML "}"
     ShowHTML "if (theForm.p_filhos_exercicio.checked && theForm.p_unidade_exercicio.value == '') {"
     ShowHTML "  alert('Os campos """"Exibir colaboradores das unidades subordinadas"""" somente podem ser marcados se os respectivos campos de unidade forem selecionados!');"
     ShowHTML "  return false;"
     ShowHTML "}"     
     ShowHTML "if (cont == 0 && theForm.p_dt_ini.value != '' && theForm.p_ferias.checked == false && theForm.p_viagem.checked == false) { "
     ShowHTML "  alert('Se nenhum dos itens indicados no campo """"Afastado por"""" for selecionado, ent�o o per�odo de busca n�o pode ser informado!');"
     ShowHTML "  return false;"
     ShowHTML "} else { "
     ShowHTML "  if ((cont > 0 || theForm.p_ferias.checked == true || theForm.p_viagem.checked == true) && (theForm.p_dt_ini.value == '' && theForm.p_dt_fim.value == '')) {"
     Validate "p_dt_ini", "Periodo de busca", "DATA", "1", "10", "10", "", "0123456789/"
     Validate "p_dt_fim", "Periodo de busca", "DATA", "1", "10", "10", "", "0123456789/"
     CompData "p_dt_ini", "In�cio", "<=", "p_dt_fim", "T�rmino"
'     ShowHTML "    alert('Se um dos itens indicados no campo """"Afastado por"""" for selecionado, ent�o o per�odo de busca � obrigat�rio!');"
'     ShowHTML "    return false;"
     ShowHTML "  } else { "
     ShowHTML "  }"
     ShowHTML "}"      
  ElseIf InStr("I",O) > 0 Then
     ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
     Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
     ShowHTML "  theForm.Botao.value = ""Procurar"";"
     ShowHTML "}"
     ShowHTML "else if (theForm.Botao.value == ""Selecionar"") {"
     Validate "w_cpf", "CPF", "CPF", "1", "10", "14", "", "0123456789-."
     ShowHTML "  theForm.w_sq_pessoa.value = '';"
     ShowHTML "}"
     ShowHTML "else { theForm.w_cpf.value = 'GERAR'; }"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "  theForm.Botao[2].disabled=true;"
     ShowHTML "  theForm.Botao[3].disabled=true;"
  ElseIf InStr("E",O) > 0 and w_erro = "" Then
     Validate "w_assinatura", "Assinatura eletr�nica", "1", "1", "3", "14", "1", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then ' Se for recarga da p�gina
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_contrato_colaborador.focus()';"
  ElseIf InStr("I",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_cpf.focus()';"
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
    ShowHTML "                         <a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar</a>"
    ShowHTML "    <td align=""right""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><b>" & LinkOrdena("Matricula","matricula") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Nome","nome_resumido") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Modalidade","nm_modalidade_contrato") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Exerc�cio","nm_exercicio") & "</td>"
    ShowHTML "          <td><b>" & LinkOrdena("Ramal","ramal") & "</td>"
    ShowHTML "          <td><b>Opera��es</td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center"">" & Nvl(RS("matricula"),"---") & "</td>"
        ShowHTML "        <td align=""left"">" & ExibeColaborador("", w_cliente, RS("chave"), TP, RS("nome_resumido")) & "</td>"
        ShowHTML "        <td align=""left"">" & RS("nm_modalidade_contrato") & "</td>"
        ShowHTML "        <td align=""left"">" & ExibeUnidade("../", w_cliente, RS("local"), RS("sq_unidade_exercicio"), TP) & "</td>"
        ShowHTML "        <td align=""center"">" & Nvl(RS("ramal"),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap>"
        ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_usuario=" & RS("chave") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("nome_resumido") & MontaFiltro("GET") & """ title=""Altera as informa��es cadastrais do colaborador"" TARGET=""menu"">Alterar</a>&nbsp;"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_cliente=" & w_cliente & "&w_sq_pessoa=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclui o colaborador do banco de dados"">Excluir</A>&nbsp"
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
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,1,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center""><div align=""justify"">Informe nos campos abaixo os crit�rios que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Limpar campos</i>, o filtro existente ser� apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    
    ShowHTML "      <tr>"
    SelecaoColaborador "<u>C</u>olaborador:", "C", null, p_contrato_colaborador, null, "p_contrato_colaborador", "COLABORADOR", null
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr>"
    SelecaoModalidade "<u>M</u>odalidade de contrata��o:", "C", null, p_modalidade_contrato, null, "p_modalidade_contrato", null, null
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr>"
    SelecaoUnidade "Unidade de <U>l</U>ota��o:", "L", null, p_unidade_lotacao, null, "p_unidade_lotacao", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    ShowHTML "        <td><input type=""checkbox"" name=""p_filhos_lotacao"" value=""S"">Exibir colaboradores das unidades subordinadas</td>"
    ShowHTML "      </tr>"

    ShowHTML "      <tr>"
    SelecaoUnidade "Unidade de <U>e</U>xerc�cio:", "E", null, p_unidade_exercicio, null, "p_unidade_exercicio", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    ShowHTML "        <td><input type=""checkbox"" name=""p_filhos_exercicio"" value=""S"">Exibir colaboradores das unidades subordinadas</td>"
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr><td><b>Afastado por:</b><br>"
    DB_GetGPTipoAfast RS1, w_cliente, null, null, null, "S", null, null
    RS1.Sort = "nome"
    ShowHTML "      <tr><td><table width=""100%"" border=""0"">"
    ShowHTML "        <tr>"
    ShowHTML "          <td><input type=""checkbox"" name=""p_ferias"" value=""S"">F�rias"
    ShowHTML "          <td><input type=""checkbox"" name=""p_viagem"" value=""S"">Viagem a servi�o"
    If Not RS1.EOF Then
       While Not RS1.EOF
          ShowHTML "        <tr>"
          ShowHTML "          <td><input type=""checkbox"" name=""p_afastamento"" value=""" & RS1("chave") & """>" & RS1("nome") & "<br>"
          RS1.MoveNext
          If Not RS1.EOF Then
             ShowHTML "          <td><input type=""checkbox"" name=""p_afastamento"" value=""" & RS1("chave") & """>" & RS1("nome") & "<br>"
             RS1.MoveNext
          End If
       Wend
    End If
    ShowHTML "       </table></td></tr>"
    RS1.Close
    ShowHTML "      <tr><td><b><u>P</u>er�odo de busca:</b><br> De: <input accesskey=""P"" type=""text"" name=""p_dt_ini"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_dt_ini & """ onKeyDown=""FormataData(this,event);""> a <input accesskey=""P"" type=""text"" name=""p_dt_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_dt_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("I",O) > 0 Then
    ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_botao"" value=""" & w_botao & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table border=""0"">"
    ShowHTML "        <tr><td colspan=4><font size=2>Informe o CPF e clique no bot�o ""Selecionar"" para continuar.</font></TD>"
    ShowHTML "        <tr><td colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
    ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"" onClick=""Botao.value=this.value; w_botao.value=Botao.value;document.Form.action='" & w_dir & "cv.asp?par=Identificacao';document.Form.SG.value='CVIDENT';document.Form.P1.value='1';"">"
    ShowHTML "            <INPUT class=""stb"" TYPE=""button"" NAME=""Botao"" VALUE=""Cancelar"" onClick=""location.href='" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"">"
    ShowHTML "        <tr><td colspan=4><font size=2>Se a pessoa n�o tem CPF e o sistema ainda n�o gerou um c�digo para ela, clique no bot�o abaixo. Menores, ind�genas e estrangeiros sem CPF, que ainda n�o tenham seu c�digo gerado pelo sistema enquadram-se nesta situa��o. Se o sistema j� gerou um c�digo para a pessoa, informe-o no campo CPF, acima.</font></TD>"
    ShowHTML "        <tr><td colspan=4><INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Pessoa sem CPF nem c�digo gerado pelo sistema"" onClick=""Botao.value=this.value; w_botao.value=Botao.value;document.Form.action='" & w_dir & "cv.asp?par=Identificacao';document.Form.SG.value='CVIDENT';document.Form.P1.value='1';"">"
    ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
    ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
    ShowHTML "        <tr><td colspan=4>"
    ShowHTML "             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
    ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; w_botao.value=Botao.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
    ShowHTML "      </table>"
    If w_nome > "" Then
       DB_GetPersonList RS, w_cliente, null, "PESSOA", w_nome, null, null, null
       RS.Sort = "nome"
       ShowHTML "<tr><td colspan=3>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><b>Nome</td>"
       ShowHTML "          <td><b>Nome resumido</td>"
       ShowHTML "          <td><b>CPF</td>"
       ShowHTML "          <td><b>Opera��es</td>"
       ShowHTML "        </tr>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><b>N�o h� pessoas que contenham o texto informado.</b></td></tr>"
       Else
          While Not RS.EOF
             ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
             ShowHTML "        <td>" & RS("nome") & "</td>"
             ShowHTML "        <td>" & RS("nome_resumido") & "</td>"
             ShowHTML "        <td align=""center"">" & Nvl(RS("cpf"),"---") & "</td>"
             ShowHTML "        <td nowrap>"
             ShowHTML "          <A class=""hl"" HREF=""" & w_dir & "cv.asp?par=Identificacao&R=" & R & "&O=I&w_cpf=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&P1=1&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CVIDENT"">Selecionar</A>&nbsp"
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
    ShowHTML "</FORM>"
  ElseIf Instr("E",O) > 0 Then
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O  
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML"<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML"    <table width=""99%"" border=""0"">"
    ShowHTML"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>Identifica��o</td>"
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>Nome:<br><b>" & RS("nome") & " </b></td>"
    ShowHTML"          <td>Nome resumido:<br><b>" & RS("nome_resumido") & " </b></td>"
    ShowHTML"          <td>Data nascimento:<br><b>" & FormataDataEdicao(RS("nascimento")) & " </b></td>"
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>Sexo:<br><b>" & RS("nm_sexo") & " </b></td>"
    ShowHTML"          <td>Estado civil:<br><b>" & RS("nm_estado_civil") & " </b></td>"
    If nvl(RS("sq_siw_arquivo"),"nulo") <> "nulo" and P2 = 0 Then
       ShowHTML"          <td rowspan=3>" & LinkArquivo(null, w_cliente, RS("sq_siw_arquivo"), "_blank", null, "<img title=""clique para ver em tamanho original."" border=1 width=100 length=80 src=""" & LinkArquivo(null, w_cliente, RS("sq_siw_arquivo"), null, null, null, "EMBED")& """>", null)& "</td>"
    Else
       ShowHTML"          <td rowspan=3></td>"
    End If
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>Forma��o acad�mica:<br><b>" & RS("nm_formacao") & " </b></td>"
    ShowHTML"          <td>Etnia:<br><b>" & RS("nm_etnia") & " </b></td>"
    ShowHTML"      <tr><td>Defici�ncia:<br><b>" & Nvl(RS("nm_deficiencia"),"---") & " </b></td>"
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>Identidade:<br><b>" & RS("rg_numero") & " </b></td>"
    ShowHTML"          <td>Emissor:<br><b>" & RS("rg_emissor") & " </b></td>"
    ShowHTML"          <td>Data de emiss�o:<br><b>" & FormataDataEdicao(RS("rg_emissao")) & " </b></td>"
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>CPF:<br><b>" & RS("cpf")  & "</b></td>"
    ShowHTML"          <td>Passaporte:<br><b>" & Nvl(RS("passaporte_numero"),"---") & " </b></td>"    
    ShowHTML"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>Local de nascimento</td>"
    ShowHTML"      <tr valign=""top"">"
    ShowHTML"          <td>Pa�s:<br><b>" & RS("nm_pais_nascimento") & " </b></td>"
    ShowHTML"          <td>Estado:<br><b>" & RS("nm_uf_nascimento") & " </b></td>"
    ShowHTML"          <td>Cidade:<br><b>" & RS("nm_cidade_nascimento") & " </b></td>"
    DesconectaBD
    ShowHTML"      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>Dados do contrato</td>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_contrato_colaborador"" value=""" & RS1("chave") & """>"    
    ShowHTML"      <tr valign=""top"">"       
    ShowHTML"          <td>Cargo:<br><b>" & RS1("nm_posto_trabalho")  & "</b></td>"
    ShowHTML"          <td>Modalidade de contrata��o:<br><b>" & Nvl(RS1("nm_modalidade_contrato"),"---") & " </b></td>"
    ShowHTML "     </tr>"
    ShowHTML "     <tr valign=""top"">" 
    ShowHTML "        <td valign=""top"">Unidade de lota��o:<br><b>" & RS1("nm_unidade_lotacao")  & "(" & RS1("sg_unidade_lotacao") & ")</b></td>"
    ShowHTML "        <td valign=""top"">Unidade de exerc�cio:<br><b>" & RS1("nm_unidade_exercicio")  & "(" & RS1("sg_unidade_exercicio") & ")</b></td>"
    ShowHTML "        <td valign=""top"">Localiza��o:<br><b>" & RS1("local") & "</b></td>"
    ShowHTML "     </tr>"
    ShowHTML "     <tr valign=""top"">" 
    ShowHTML "        <td><b>Matr�cula:</b><br>" & Nvl(RS1("matricula"),"---") & "</td>"
    ShowHTML "        <td><b>In�cio da vig�ncia:</b><br>" & FormataDataEdicao(RS1("inicio"))
    ShowHTML "        <td><b>Fim da vig�ncia:</b><br>" & Nvl(FormataDataEdicao(RS1("fim")),"---")    
    RS1.Close
    If w_erro > "" Then
       ShowHTML "<tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=""3"">"
       ShowHTML "<font color=""#BC3131""><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel a conclus�o da opera��o.</font>"
       ShowHTML "<UL>" & w_erro & "</UL>"
       ShowHTML "</td></tr>"
    End If
    If w_erro = "" Then
       ShowHTML "     <tr><td align=""LEFT"" colspan=""3""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If w_erro = "" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=L&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
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

  Set p_contrato_colaborador = Nothing
  Set p_modalidade_contrato  = Nothing
  Set p_unidade_lotacao      = Nothing
  Set p_filhos_lotacao       = Nothing
  Set p_unidade_exercicio    = Nothing
  Set p_filhos_exercicio     = Nothing
  Set p_afastamento          = Nothing
  Set p_dt_ini               = Nothing
  Set p_dt_fim               = Nothing
  Set p_ferias               = Nothing
  Set p_viagem               = Nothing
  Set w_sq_pessoa            = Nothing
  Set w_nome                 = Nothing
  Set w_cpf                  = Nothing
  Set w_erro                    = Nothing

End Sub
REM =========================================================================
REM Fim da rotina inicial
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados de documenta��o do colaborador
REM -------------------------------------------------------------------------
Sub Documentacao

  Dim w_ctps_numero, w_ctps_serie, w_ctps_emissor, w_ctps_emissao, w_pis_pasep, w_pispasep_numero, w_pispasep_cadastr
  Dim w_te_numero, w_te_zona, w_te_secao, w_reservista_numero, w_reservista_csm, w_tipo_sangue, w_doador_sangue
  Dim w_doador_orgaos, w_observacoes

  ' Verifica se h� necessidade de recarregar os dados da tela a partir
  ' da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  If w_troca > "" Then ' Se for recarga da p�gina
     w_ctps_numero          = Request("w_ctps_numero") 
     w_ctps_serie           = Request("w_ctps_serie") 
     w_ctps_emissor         = Request("w_ctps_emissor") 
     w_ctps_emissao         = Request("w_ctps_emissao") 
     w_pis_pasep            = Request("w_pis_pasep") 
     w_pispasep_numero      = Request("w_pispasep_numero") 
     w_pispasep_cadastr     = Request("w_pispasep_cadastr") 
     w_te_numero            = Request("w_te_numero") 
     w_te_zona              = Request("w_te_zona")
     w_te_secao             = Request("w_te_secao") 
     w_reservista_numero    = Request("w_reservista_numero") 
     w_reservista_csm       = Request("w_reservista_csm") 
     w_tipo_sangue          = Request("w_tipo_sangue") 
     w_doador_sangue        = Request("w_doador_sangue") 
     w_doador_orgaos        = Request("w_doador_orgao") 
     w_observacoes          = Request("w_observacoes")
  Else
     ' Recupera os dados do colaborador a partir do c�digo da pessoa
     DB_GetGPColaborador RS, w_cliente, w_usuario, null, null, null, null, null, null, null, null, null, null, null, null, null, null
     If RS.RecordCount > 0 Then 
        w_ctps_numero          = RS("ctps_numero") 
        w_ctps_serie           = RS("ctps_serie") 
        w_ctps_emissor         = RS("ctps_emissor") 
        w_ctps_emissao         = FormataDataEdicao(RS("ctps_emissao_data"))
        w_pis_pasep            = RS("pis_pasep") 
        w_pispasep_numero      = RS("pispasep_numero") 
        w_pispasep_cadastr     = FormataDataEdicao(RS("pispasep_cadastr"))
        w_te_numero            = RS("te_numero") 
        w_te_zona              = RS("te_zona")
        w_te_secao             = RS("te_secao") 
        w_reservista_numero    = RS("reservista_numero") 
        w_reservista_csm       = RS("reservista_csm") 
        w_tipo_sangue          = RS("tipo_sangue") 
        w_doador_sangue        = RS("doador_sangue") 
        w_doador_orgaos        = RS("doador_orgaos") 
        w_observacoes          = RS("observacoes")
        DesconectaBD
     End If
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara.
  ScriptOpen "JavaScript"
  CheckBranco
  Modulo
  FormataData
  ValidateOpen "Validacao"
  ShowHTML "if ((theForm.w_ctps_numero.value != '') && (theForm.w_ctps_serie.value == '' || theForm.w_ctps_emissor.value == '' || theForm.w_ctps_emissao.value == '')) {"
  ShowHTML "  alert ('Se o n�mero da CTPS for informado, todos os campos relativos a CTPS s�o obrigat�rios!');"
  ShowHTML "  return false;"
  ShowHTML "} else { "
  ShowHTML "  if ((theForm.w_ctps_numero.value == '') && (theForm.w_ctps_serie.value != '' || theForm.w_ctps_emissor.value != '' || theForm.w_ctps_emissao.value != '')) {"
  ShowHTML "    alert ('Se o n�mero da CTPS n�o for informado, todos os campos relativos a CTPS devem estar em branco!');"
  ShowHTML "    return false;"
  ShowHTML "  }"
  ShowHTML "}"
  ShowHTML "if ((theForm.w_pispasep_numero.value != '') && (theForm.w_pispasep_cadastr.value == '' )) {"
  ShowHTML "  alert ('Se o n�mero do PIS/PASEP for informado, a data de emiss�o PIS/PASEP � obrigat�rio!');"
  ShowHTML "  return false;"
  ShowHTML "} else { "
  ShowHTML "  if ((theForm.w_pispasep_numero.value == '') && (theForm.w_pispasep_cadastr.value != '' )) {"
  ShowHTML "    alert ('Se o n�mero do PIS/PASEP n�o for informado, a data de emiss�o PIS/PASEP deve estar em branco!');"
  ShowHTML "    return false;"
  ShowHTML "  }"
  ShowHTML "}"
  ShowHTML "if ((theForm.w_te_numero.value != '') && (theForm.w_te_zona.value == '' || theForm.w_te_secao.value == '')) {"
  ShowHTML "  alert ('Se o n�mero do t�tulo de eleitor for informado, todos os campos relativos ao t�tulo s�o obrigat�rios!');"
  ShowHTML "  return false;"
  ShowHTML "} else { "
  ShowHTML "  if ((theForm.w_te_numero.value == '') && (theForm.w_te_zona.value != '' || theForm.w_te_secao.value != '')) {"
  ShowHTML "    alert ('Se o n�mero do t�tulo de eleitor n�o for informado, todos os campos relativos ao t�tulo devem estar em branco!');"
  ShowHTML "    return false;"
  ShowHTML "  }"
  ShowHTML "}"
  Validate "w_ctps_numero", "N�mero CTPS", "1", "", "2", "20", "1", "1"
  Validate "w_ctps_serie", "S�rie CTPS", "1", "", "2", "5", "1", "1"
  Validate "w_ctps_emissor", "Emissor CTPS", "1", "", "3", "30", "1", "1"
  Validate "w_ctps_emissao", "Emiss�o CTPS", "DATA", "", "10", "10", "", "1"
  Validate "w_pispasep_numero", "N�mero PIS/PASEP", "1", "", "2", "20", "1", "1"
  Validate "w_pispasep_cadastr", "Emiss�o PIS/PASEP", "DATA", "", "10", "10", "", "1"
  Validate "w_te_numero", "N�mero t�tulo eleitor", "1", "", "3", "20", "1", "1"
  Validate "w_te_zona", "Zona", "1", "", "1", "3", "1", "1"
  Validate "w_te_secao", "Se��o", "1", "", "1", "4", "1", "1"
  Validate "w_reservista_numero", "Certificado reservista", "1", "", "2", "15", "1", "1"
  Validate "w_reservista_csm", "CSM", "1", "", "1", "4", "1", "1"
  Validate "w_observacoes", "Observa��es", "1", "", "3", "2000", "1", "1"
  Validate "w_assinatura", "Assinatura eletr�nica", "1", "1", "3", "14", "1", "1"
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_ctps_numero.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,"A"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_usuario"" value=""" & w_usuario &""">"
  
  ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "   <table width=""97%"" border=""0"">"
  ShowHTML "     <tr valign=""top"">"    
  ShowHTML "       <td valign=""top""><b><u>N</u>�mero CTPS:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_ctps_numero"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_ctps_numero & """></td>"
  ShowHTML "       <td valign=""top""><b><u>S</u>�rie:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_ctps_serie"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_ctps_serie & """></td>"
  ShowHTML "       <td valign=""top""><b><u>E</u>missor:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_ctps_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_ctps_emissor & """></td>"
  ShowHTML "       <td valign=""top""><b>E<u>m</u>iss�o CTPS:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_ctps_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_ctps_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
  ShowHTML "     </tr>"
  ShowHTML "     <tr valign=""top"">"
  ShowHTML "       <td valign=""top"" colspan=""2""><b>Optante pelo:</b><br>"
  If w_pis_pasep = "A" Then
     ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_pis_pasep"" value=""I""> PIS <input " & w_Disabled & " type=""radio"" name=""w_pis_pasep"" value=""A"" checked> PASEP"
  Else
     ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""w_pis_pasep"" value=""I"" checked> PIS <input " & w_Disabled & " type=""radio"" name=""w_pis_pasep"" value=""A""> PASEP"
  End If
  ShowHTML "       <td valign=""top""><b>N<u>�</u>mero PIS/PASEP:</b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""w_pispasep_numero"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_pispasep_numero & """></td>"
  ShowHTML "       <td valign=""top""><b>Em<u>i</u>ss�o PIS/PASEP:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_pispasep_cadastr"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_pispasep_cadastr & """ onKeyDown=""FormataData(this,event);""></td>"
  ShowHTML "     </tr>"
  ShowHTML "     <tr valign=""top"">"
  ShowHTML "       <td valign=""top""><b>N�mero <u>t</u>�tulo eleitor:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_te_numero"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_te_numero & """></td>"
  ShowHTML "       <td valign=""top""><b><u>Z</u>ona:</b><br><input " & w_Disabled & " accesskey=""Z"" type=""text"" name=""w_te_zona"" class=""sti"" SIZE=""3"" MAXLENGTH=""3"" VALUE=""" & w_te_zona & """></td>"
  ShowHTML "       <td valign=""top""><b>Se�a<u>o</u>:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_te_secao"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_te_secao & """></td>"    
  ShowHTML "     </tr>"
  ShowHTML "     <tr valign=""top"">"
  ShowHTML "       <td valign=""top""><b>Certificado <u>r</u>eservista:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_reservista_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_reservista_numero & """></td>"
  ShowHTML "       <td valign=""top""><b><u>C</u>SM:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_reservista_csm"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_reservista_csm & """></td>"
  ShowHTML "     </tr>"  
  ShowHTML "     <tr valign=""top"">"
  ShowHTML "       <td valign=""top""><b>Ti<u>p</u>agem sang��nea:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_tipo_sangue"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_tipo_sangue & """></td>"
  MontaRadioNS "<b>Doador de sangue?</b>", w_doador_sangue, "w_doador_sangue"
  MontaRadioNS "<b>Doador de �rg�os?</b>", w_doador_orgaos, "w_doador_orgaos"
  ShowHTML "     </tr>"  
  ShowHTML "     <tr valign=""top"">"
  ShowHTML "       <td colspan=""4""><b>O<U>b</U>serva��es:<br><TEXTAREA ACCESSKEY=""B"" " & w_Disabled & " class=""sti"" name=""w_observacoes"" rows=""5"" cols=75>" & w_observacoes & "</textarea></td>"
  ShowHTML "     </tr>"  
  ShowHTML "     <tr><td align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "      <tr><td align=""center"" colspan=""4"" height=""1"" bgcolor=""#000000""></TD></TR>"
  ShowHTML "      <tr><td align=""center"" colspan=""4"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_ctps_numero         = Nothing 
  Set w_ctps_serie          = Nothing 
  Set w_ctps_emissor        = Nothing 
  Set w_ctps_emissao        = Nothing 
  Set w_pis_pasep           = Nothing 
  Set w_pispasep_numero     = Nothing 
  Set w_pispasep_cadastr    = Nothing 
  Set w_te_numero           = Nothing
  Set w_te_zona             = Nothing 
  Set w_te_secao            = Nothing 
  Set w_reservista_numero   = Nothing 
  Set w_reservista_csm      = Nothing 
  Set w_tipo_sangue         = Nothing 
  Set w_doador_sangue       = Nothing 
  Set w_doador_orgaos       = Nothing
  Set w_observacoes         = Nothing 
  
End Sub
REM =========================================================================
REM Fim da rotina dos dados de documentacao do colaborador
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de contratos do colaborador
REM -------------------------------------------------------------------------
Sub Contrato
  Dim w_chave, w_posto_trabalho, w_modalidade_contrato, w_unidade_lotacao
  Dim w_unidade_exercicio, w_localizacao, w_matricula, w_dt_ini, w_dt_fim, w_ativo
  Dim w_erro, w_sq_tipo_vinculo, w_username_pessoa
  
  w_chave               = Request("w_chave")
  w_posto_trabalho      = Request("w_posto_trabalho")
  w_modalidade_contrato = Request("w_modalidade_contrato")
  w_unidade_lotacao     = Request("w_unidade_lotacao")
  w_unidade_exercicio   = Request("w_unidade_exercicio")
  w_localizacao         = Request("w_localizacao")
  w_matricula           = Request("w_matricula")
  w_dt_ini              = Request("w_dt_ini")
  w_dt_fim              = Request("w_dt_fim")
  w_ativo               = Request("w_ativo")
  w_sq_tipo_vinculo     = Request("w_sq_tipo_vinculo")
  w_username_pessoa     = Request("w_username_pessoa")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem dos contratos do colaborador</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  
  If O = "L" Then
    DB_GetGPContrato RS, w_cliente, null, w_usuario, null, null, null, null, null, null, null, null, null, null
    RS.Sort = "fim"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
    DB_GetGPContrato RS, w_cliente, w_chave, w_usuario, null, null, null, null, null, null, null, null, null, null
    If Not RS.EOF Then
       w_chave                = RS("chave")
       w_posto_trabalho       = RS("sq_posto_trabalho")
       w_modalidade_contrato  = RS("sq_modalidade_contrato")
       w_unidade_lotacao      = RS("sq_unidade_lotacao")
       w_unidade_exercicio    = RS("sq_unidade_exercicio")
       w_localizacao          = RS("sq_localizacao")
       w_matricula            = RS("matricula")
       w_dt_ini               = FormataDataEdicao(RS("inicio"))
       w_dt_fim               = FormataDataEdicao(RS("fim"))
       w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
    End If 
    DesconectaBD
    w_erro = ValidaColaborador(w_cliente, w_usuario, w_chave, null)
  End If
  If InStr("IAE",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_posto_trabalho", "Cargo", "SELECT", 1, 1, 18, "", "0123456789"
        Validate "w_modalidade_contrato", "Modalidade de contrata��o", "SELECT", 1, 1, 18, "", "0123456789"
        Validate "w_unidade_lotacao", "Unidade de lota��o", "SELECT", 1, 1, 18, "", "0123456789"
        Validate "w_unidade_exercicio", "Unidade de exerc�cio", "SELECT", 1, 1, 18, "", "0123456789"
        Validate "w_localizacao", "Localiza��o", "SELECT", 1, 1, 18, "", "0123456789"
        Validate "w_sq_tipo_vinculo", "V�nculo com a organiza��o", "SELECT", 1, 1, 10, "", "1"
        Validate "w_matricula", "Matr�cula", "1", "1", "5", "18", "1", "1"
        Validate "w_dt_ini", "In�cio da vig�ncia", "DATA", "1", "10", "10", "", "0123456789/"
        If O = "A" and Nvl(w_dt_fim,"") > "" Then
           Validate "w_dt_fim", "Fim da vig�ncia", "DATA", "1", "10", "10", "", "0123456789/"
        ElseIf O = "I" Then
           Validate "w_dt_fim", "Fim da vig�ncia", "DATA", "", "10", "10", "", "0123456789/"
        End If
        If Not (O = "A" and Nvl(w_dt_fim,"") = "") Then
           CompData "w_dt_ini", "In�cio da vig�ncia", "<=", "w_dt_fim", "Fim da vig�ncia"
        End If
        Validate "w_assinatura",    "Assinatura Eletr�nica",   "1", "1", "6", "30",  "1", "1"
     ElseIf O = "E" and w_erro = "" Then
        Validate "w_dt_fim", "Fim da vig�ncia", "DATA", "", "10", "10", "", "0123456789/"
        CompData "w_dt_fim", "Fim da vig�ncia", ">=", "w_dt_ini", "In�cio da vig�ncia"
        Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma o encerramento deste contrato?')) "
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
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_posto_trabalho.focus();"
  ElseIf O = "E" Then
     BodyOpen "onLoad=document.Form.w_dt_fim.focus();"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "<tr><td><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&w_usuario=" & w_usuario & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     'ShowHTML "    <td><a accesskey=""E"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&w_usuario=" & w_usuario & "&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>E</u>ncerrar</a>&nbsp;"
     ShowHTML "    <td align=""right""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td><b>" & LinkOrdena("Matr�cula","matricula") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("Nome","nome_resumido") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("Modalidade","nm_modalidade_contrato") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("Exerc�cio","local") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("Ramal","ramal") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("In�cio","inicio") & "</td>"
     ShowHTML "          <td><b>" & LinkOrdena("Fim","fim") & "</td>"
     ShowHTML "          <td><b> Opera��es </td>"
     ShowHTML "        </tr>"
     If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=""8"" align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
     Else
       ' Lista os registros selecionados para listagem
       rs.PageSize     = P4
       rs.AbsolutePage = P3
       While Not RS.EOF and RS.AbsolutePage = P3
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         ShowHTML "        <td align=""center"">" & Nvl(RS("matricula"),"---")   & "</td>"
         ShowHTML "        <td align=""left"">" & ExibeColaborador("", w_cliente, RS("sq_pessoa"), TP, RS("nome_resumido")) & "</td>"
         ShowHTML "        <td align=""left"">" & RS("nm_modalidade_contrato")   & "</td>"
         ShowHTML "        <td align=""left"">" & ExibeUnidade("../", w_cliente, RS("local"), RS("sq_unidade_exercicio"), TP) & "</td>"
         ShowHTML "        <td align=""center"">" & Nvl(RS("ramal"),"---") & "</td>"
         ShowHTML "        <td align=""center"">" & FormataDataEdicao(RS("inicio")) & "</td>"
         ShowHTML "        <td align=""center"">" & Nvl(FormataDataEdicao(RS("fim")),"---") & "</td>"
         ShowHTML "        <td align=""top"" nowrap>"
         ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&w_usuario=" & w_usuario & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """ Title=""Alterar registro"">Alterar</A>&nbsp"
         If Nvl(RS("fim"),"") = "" Then
            ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R= " & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&w_usuario=" & w_usuario & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " &SG=" & SG & MontaFiltro("GET") & """ Title=""Encerrar contrato"">Encerrar</A>&nbsp"
         End If
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
     'Aqui come�a a manipula��o de registros
  ElseIf Instr("IAV",O) > 0 Then
     If InStr("V",O) Then 
        w_Disabled = " DISABLED "
     ElseIf InStr("IA",O) and w_troca = "" Then
        w_ativo = 0
        DB_GetGPContrato RS, w_cliente, null, w_usuario, null, null, null, null, null, null, null, null, null, null
        If w_chave > "" tHEN
           RS.Filter = "chave <> " & w_chave
        End If
        If Not RS.EOF Then
           While Not RS.EOF 
              If Nvl(RS("fim"),"") = "" Then
                 w_ativo = w_ativo + 1
              End If
              RS.MoveNext
           wend
        End If
        RS.Close
        If w_ativo > 0 and O = "I" Then
        ScriptOpen "JavaScript"
        ShowHTML "alert('J� existe contrato ativo para este colaborador, n�o sendo poss�vel inclus�o de outro contrato ativo!');"
        ScriptClose
        End If
     End If
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_usuario"" value=""" & w_usuario & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_ativo"" value=""" & w_ativo & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     ShowHTML "        <tr valign=""top"">" 
     ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"       
     SelecaoCargo "<u>C</u>argo:", "C", "Selecione o cargo.", w_posto_trabalho, null, "w_posto_trabalho", null, null
     SelecaoModalidade "M<u>o</u>dalidade de contrata��o:", "O", null, w_modalidade_contrato, null, "w_modalidade_contrato", null, "onChange=""document.Form.action='" & w_dir&w_pagina&par&"&SG="&SG&"&O="&O & "'; document.Form.w_troca.value='w_modalidade_contrato'; document.Form.submit();"""
     If Nvl(w_modalidade_contrato,"") > "" Then
        DB_GetGPModalidade RS, w_cliente, w_modalidade_contrato, null, null, null, null, null
        If RS("username") = "P" Then
           w_username_pessoa = "S"
        End If
     End If
     ShowHTML "        </table></td></tr>"
     ShowHTML "        <tr valign=""top"">" 
     ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"
     SelecaoUnidade "Unidade de <U>l</U>ota��o:", "L", null, w_unidade_lotacao, null, "w_unidade_lotacao", null, null
     ShowHTML "        </table></td></tr>"
     ShowHTML "        <tr valign=""top"">" 
     ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"
     SelecaoUnidade "Unidade de <U>e</U>xerc�cio:", "E", null, w_unidade_exercicio, null, "w_unidade_exercicio", null, "onBlur=""document.Form.action='" & w_dir&w_pagina&par&"&SG="&SG&"&O="&O&"&w_usuario="&w_usuario& "'; document.Form.w_troca.value='w_localizacao'; document.Form.submit();"""
     ShowHTML "        </table></td></tr>"
     ShowHTML "        <tr valign=""top"">" 
     ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"
     SelecaoLocalizacao "Locali<u>z</u>a��o:", "Z", null, w_localizacao, Nvl(w_unidade_exercicio,0), "w_localizacao", null
     ShowHTML "        </table></td></tr>"
     If Nvl(w_dt_fim,"") > "" Then
        ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_vinculo"" value=""" & w_sq_tipo_vinculo & """>"
     Else
        ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0><tr>"
        SelecaoVinculo "<u>T</u>ipo de v�nculo:", "T", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "S", "F�sica", "S"
        ShowHTML "        </table></td></tr>"                    
     End If
     ShowHTML "        <tr valign=""top"">" 
     ShowHTML "        <td colspan=""3"" valign=""top""><table border=""0"" width=""100%"" cellpadding=0 cellspacing=0>"
     ShowHTML "          <tr><td valign=""top""><b><u>M</u>atr�cula:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_matricula"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_matricula & """></td>"
     ShowHTML "              <td><b><u>I</u>n�cio da vig�ncia:</b><br><input accesskey=""I"" type=""text"" name=""w_dt_ini"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_dt_ini & """ onKeyDown=""FormataData(this,event);"">"
     If Not (O = "A" and Nvl(w_dt_fim,"") = "") Then
        ShowHTML "              <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey=""F"" type=""text"" name=""w_dt_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_dt_fim & """ onKeyDown=""FormataData(this,event);"">"
     End If
     ShowHTML "        </table></td></tr>"
     If w_username_pessoa = "S" Then
        ShowHTML "        <tr valign=""top"">" 
        ShowHTML "        <td colspan=""3"" valign=""top""><input type=""checkbox"" name=""w_username_pessoa"" value=""S""><b>Criar username para este colaborador?</b>"
     End If     
     ShowHTML "      <tr><td colspan=5><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=5><hr>"
     If O = "I" Then
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
     Else
        ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
     End If
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_usuario=" & w_usuario & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</FORM>"
  ElseIf Instr("E",O) > 0 Then
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_usuario"" value=""" & w_usuario & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_dt_ini"" value=""" & w_dt_ini & """>"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center""><div align=""justify"">Para efetivar o encerramento do contrato, informe os dados abaixo e clique no bot�o <i>Encerrar contrato</i>. ATEN��O: a reativa��o de um contrato s� � poss�vel se n�o houve nenhum outro contrato ativo.</div><hr>"     
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""97%"" border=""0""><tr>"
     ShowHTML "      <tr valign=""top"">" 
     ShowHTML "        <td><b><u>F</u>im da vig�ncia:</b><br><input accesskey=""F"" type=""text"" name=""w_dt_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_dt_fim & """ onKeyDown=""FormataData(this,event);""></td></tr>"
     ShowHTML "      <tr valign=""top"">" 
     ShowHTML "        <td><input type=""checkbox"" name=""w_envio_email"" value=""S""><b>Enviar e-mail comunicando o encerramento do contrato.</b></td>"
     ShowHTML "      <tr valign=""top""><td><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
     ShowHTML "      <tr><td align=""center""><hr>"
     ShowHTML "          <input class=""stb"" type=""submit"" name=""Botao"" value=""Encerrar contrato"">"
     ShowHTML "          <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_usuario=" & w_usuario & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "        </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "  </TD>"
     ShowHTML "</tr>"
     ShowHTML "</FORM>"
  Else
     ScriptOpen "JavaScript"
     ShowHTML " alert('Op��o n�o dispon�vel');"
     'ShowHTML " history.back(1);"
     ScriptClose
  End If
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave                   = Nothing 
  Set w_posto_trabalho          = Nothing 
  Set w_modalidade_contrato     = Nothing
  Set w_unidade_lotacao         = Nothing 
  Set w_unidade_exercicio       = Nothing      
  Set w_localizacao             = Nothing
  Set w_matricula               = Nothing 
  Set w_dt_ini                  = Nothing
  Set w_ativo                   = Nothing
  Set w_troca                   = Nothing
  Set w_erro                    = Nothing
  Set w_sq_tipo_vinculo         = Nothing
  Set w_username_pessoa         = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de contratos dos colaboradores
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as opera��es de BD
REM -------------------------------------------------------------------------
Public Sub Grava
   Dim p_sq_endereco_unidade
   Dim p_modulo
   Dim w_Null
   Dim w_mensagem
   Dim FS, F1
   Dim w_chave_nova
   Dim w_erro
  
   Cabecalho
   BodyOpen "onLoad=document.focus();"
   
   AbreSessao    
   Select Case SG
      Case "COINICIAL"
         ' Verifica se a Assinatura Eletr�nica � v�lida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            DML_PutGPColaborador O, w_cliente, Request("w_sq_pessoa"), null, null, null, _
                                 null, null, null, null, _
                                 null, null, null, null, _
                                 null, null, null, null, _
                                 null
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If      
      Case "CODOCUM"
         ' Verifica se a Assinatura Eletr�nica � v�lida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            DML_PutGPColaborador O, w_cliente, w_usuario, Request("w_ctps_numero"), Request("w_ctps_serie"), Request("w_ctps_emissor"), _
                                 Request("w_ctps_emissao"), Request("w_pis_pasep"), Request("w_pispasep_numero"), Request("w_pispasep_cadastr"), _
                                 Request("w_te_numero"), Request("w_te_zona"), Request("w_te_secao"), Request("w_reservista_numero"), _
                                 Request("w_reservista_csm"), Request("w_tipo_sangue"), Request("w_doador_sangue"), Request("w_doador_orgaos"), _
                                 Request("w_observacoes")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "COCONTR"
         ' Verifica se a Assinatura Eletr�nica � v�lida
         If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or w_assinatura = "" Then
            If cDbl(Nvl(Request("w_ativo"),0)) > 0 and Nvl(Request("w_dt_fim"),"") = "" Then
               ScriptOpen "JavaScript"
               ShowHTML "alert('J� existe contrato ativo para este colaborador, n�o sendo poss�vel uma nova inclus�o');" 
               ShowHTML "history.back(1);"
               ScriptClose
               Exit Sub
            Else
               If O = "E" Then
                  w_erro = ValidaColaborador(w_cliente, w_usuario, Request("w_chave"), Request("w_dt_fim"))
                  If w_erro > "" Then
                     ShowHTML "<HR>"
                     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
                     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
                     ShowHTML "<font color=""#BC3131""><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel a conclus�o da opera��o.</font>"
                     ShowHTML "<UL>" & w_erro & "</UL>"
                     ShowHTML "</td></tr></table>"
                     ShowHTML "<center><B>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</b></center>"
                     Rodape
                     Exit Sub
                  End If
               End If 
               DML_PutGPContrato O, _
                  w_cliente, Request("w_chave"), w_usuario, Request("w_posto_trabalho"), Request("w_modalidade_contrato"), _
                  Request("w_unidade_lotacao"), Request("w_unidade_exercicio"), Request("w_localizacao"), Request("w_matricula"), _
                  Request("w_dt_ini"), Request("w_dt_fim"), Request("w_sq_tipo_vinculo")
               
               If Instr("I",O) > 0 Then
                  DB_GetGPModalidade RS, w_cliente, Request("w_modalidade_contrato"), null, null, null, null, null
                  If (Nvl(RS("username"),"") = "S") or (Nvl(RS("username"),"") = "P" and Request("w_username_pessoa") = "S")  Then
                     DB_GetPersonData RS, w_cliente, w_usuario, null, null
                     DML_PutSiwUsuario "I", _
                         w_usuario, w_cliente, RS("nome"), RS("nome_resumido"), _
                         RS("sq_tipo_vinculo"), "F�sica", Request("w_unidade_lotacao"), Request("w_localizacao"), _
                         RS("cpf"), RS("email"), null, null
                     DML_PutSiwUsuario "T", _
                         w_usuario, null, null, null, _
                         null, null, null, null, _
                         null, null, null, null
                  End If
               End If
            End If
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&O=L&w_usuario=" & w_usuario & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case Else
         ScriptOpen "JavaScript"
         ShowHTML "  alert('Bloco de dados n�o encontrado: " & SG & "');"
         ShowHTML "  history.back(1);"
         ScriptClose
   End Select

   Set w_chave_nova          = Nothing
   Set FS                    = Nothing
   Set w_Mensagem            = Nothing
   Set p_sq_endereco_unidade = Nothing
   Set p_modulo              = Nothing
   Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as opera��es de BD
REM =========================================================================


REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIAL"           Inicial
    Case "DOCUMENTACAO"      Documentacao
    Case "CONTRATO"          Contrato
    Case "GRAVA"             Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</font></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>