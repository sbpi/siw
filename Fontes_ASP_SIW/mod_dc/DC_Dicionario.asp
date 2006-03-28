<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Dicionario.asp" -->
<!-- #INCLUDE FILE="DML_Dicionario.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
Server.ScriptTimeout = conScriptTimeout
REM =========================================================================
REM  /DC_Dicionario.asp
REM ------------------------------------------------------------------------
REM Nome     : Egisberto Vicente da Silva   
REM Descricao: Gerenciar tabelas básicas do módulo    
REM Mail     : beto@sbpi.com.br
REM Criacao  : 21/04/2004 13:41
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

' VerIfica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If
' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca, w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave
Dim w_sq_pessoa
Dim ul,File, p_ordena

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
p_ordena           = uCase(Request("p_ordena"))
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "DC_Dicionario.asp?par="
w_Dir        = "mod_dc/"
w_Disabled   = "ENABLED"
If O = "" Then ' Mostra a opção de filtragem de acordo com os parâmetros abaixo
  If par = "TABELA" or par = "COLUNAS" or par ="SP" or par="PROC" or par="RELACIONAMENTOS" or par="INDICE" or par="ARQUIVOS" or par="TRIGGER" Then 
     O = "P"
  Else
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
   Case "C"
      w_TP = TP & " - Cópia"
   Case "V" 
      w_TP = TP & " - Geração automática"
   Case "H" 
      w_TP = TP & " - Herança"
   Case Else
      w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
If SG <> "TRIGEVENTO" and SG <> "DCSPTAB" and SG <> "DCSPSP" and SG <> "DCSPPARAM" Then
   w_menu         = RetornaMenu(w_cliente, SG) 
Else
   w_menu         = RetornaMenu(w_cliente, Request("w_SG")) 
End If

' VerIfica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
If SG <> "TRIGEVENTO" and SG <> "DCSPTAB" and SG <> "DCSPSP" and SG <> "DCSPPARAM" Then 
   DB_GetLinkSubMenu RS, Session("p_cliente"), SG
Else
   DB_GetLinkSubMenu RS, Session("p_cliente"), Request("w_SG")
End If
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

REM =========================================================================
REM Rotina de arquivos
REM -------------------------------------------------------------------------
Sub Arquivos
   Dim w_sq_sistema, w_nome, w_descricao, w_tipo, w_diretorio, w_sq_arquivo
   Dim p_sq_sistema, p_nome, p_tipo_arquivo
       
   w_Chave        = Request("w_Chave")
   w_troca        = Request("w_troca")
   w_sq_sistema   = Request("w_sq_sistema")
   w_tipo         = Request("w_tipo")
   p_sq_sistema   = Request("p_sq_sistema")
   p_tipo_arquivo = Request("p_tipo_arquivo")
   p_nome         = uCase(Request("p_nome"))
       
   If w_troca > "" Then ' Se for recarga da página
      w_sq_sistema  = Request("w_sq_sistema")
      w_nome        = Request("w_nome")
      w_descricao   = Request("w_descricao")
      w_tipo        = Request("w_tipo")
      w_diretorio   = Request("w_diretorio")
      w_sq_arquivo  = Request("w_sq_arquivo")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetArquivo RS, w_cliente, null, p_sq_sistema, p_nome, p_tipo_arquivo
      If p_ordena > "" Then RS.Sort = p_ordena & ", nm_arquivo"  Else RS.Sort = "nm_arquivo" End If
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
       ' Recupera os dados do Endereço informado
      DB_GetArquivo RS, w_cliente, w_chave, null, null, null 
      w_sq_sistema  = RS("sq_sistema")
      w_nome        = RS("nm_arquivo")
      w_descricao   = RS("descricao")
      w_tipo        = RS("tipo")
      w_diretorio   = RS("diretorio")
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema", "Sistema"              , "SELECT"    , "1", "1"  , "18"  , ""    , "1"
         Validate "w_tipo"      , "Tipo"                 , "SELECT"    , "1", "1"  , "1"   , "CGRI", ""
         Validate "w_nome"      , "Nome do arquivo"      , "1"         , "1", "1"  , "30"  , "1"   , "1"
         Validate "w_diretorio" , "Diretório"            , "1"         , "1", "1"  , "100" , "1"   , "1"
         Validate "w_descricao" , "Descrição"            , "1"         , "1", "2"  , "4000", "1"   , "1"
         Validate "w_assinatura", "Assinatura Eletrônica", "1"         , "1", "6"  , "30"  , "1"   , "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "30", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_tipo_arquivo.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"      
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_tipo_arquivo > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Sistema","sg_sistema") & "</font></td>"
      ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Arquivo","nm_arquivo") & "</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Diretório","diretorio") & "</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Tipo","tipo") & "</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Descrição","descricao") & "</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações      </font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=ARQUIVO&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_arquivo=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"" target="""&RS("nm_arquivo")&""">"&lCase(RS("nm_arquivo"))&"</A>&nbsp"
            If RS("diretorio")<>"" Then 
               ShowHTML " <td align=""center""><font size=""1"">" & RS("diretorio")  & "</td>" 
            Else 
               ShowHTML    "<td align=""center""><font size=""1"">---</td>" 
            End If
            ShowHTML "        <td><font size=""1"">" & ExibeTipoArquivo(RS("tipo")) & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("descricao")              & "</td>"
            If P1 = 0 Then 
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
        w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0""><tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, null
      SelecaoTipoArquivo  "<u>T</u>ipo:", "T", null, w_tipo, null,"w_tipo", null, null
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>N</u>ome do arquivo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>iretório:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_diretorio"" class=""sti"" SIZE=""30"" MAXLENGTH=""100"" VALUE=""" & w_diretorio & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
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
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:",   "S", null, p_sq_sistema  , w_cliente, "p_sq_sistema", null, null
      SelecaoTipoArquivo  "<u>T</u>ipo:", "T", null, p_tipo_arquivo, null,"p_tipo_arquivo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td></tr>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"    
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set w_tipo       = Nothing
   Set w_diretorio  = Nothing
   Set w_chave      = Nothing 
   Set w_nome       = Nothing
   Set w_descricao  = Nothing 
   Set w_sq_sistema = Nothing 
   Set w_troca      = Nothing
End Sub         
REM =========================================================================
REM Fim da rotina de arquivos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Colunas
REM -------------------------------------------------------------------------
Sub Colunas
   Dim w_sq_tabela, w_sq_dado_tipo, w_nome, w_descricao, w_ordem, w_tamanho, w_precisao, w_escala, w_obrigatorio, w_valor_padrao, w_sq_sistema, w_sq_usuario', w_sq_dado_tipo, w_sq_tabela_tipo
   Dim p_sq_sistema, p_sq_usuario, p_nome, p_sq_dado_tipo, p_sq_tabela, p_ordem_nome
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_sq_sistema      = Request("p_sq_sistema")
   p_sq_usuario      = Request("p_sq_usuario")
   p_nome            = uCase(Request("p_nome"))
   p_sq_dado_tipo    = Request("p_sq_dado_tipo")
   p_sq_tabela       = Request("p_sq_tabela")
   p_ordem_nome      = Request("p_ordem_nome")
   If w_troca > "" Then ' Se for recarga da página
      w_sq_tabela        = Request("w_sq_tabela")
      w_sq_dado_tipo     = Request("w_sq_dado_tipo")
      w_nome             = Request("w_nome")
      w_descricao        = Request("w_descricao")
      w_ordem            = Request("w_ordem")
      w_tamanho          = Request("w_tamanho")
      w_precisao         = Request("w_precisao")
      w_escala           = Request("w_escala")
      w_obrigatorio      = Request("w_obrigatorio")
      w_valor_padrao     = Request("w_valor_padrao")
      w_sq_sistema       = Request("w_sq_sistema")
      w_sq_usuario       = Request("w_sq_usuario")
        
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetColuna RS, w_cliente, null, p_sq_tabela, p_sq_dado_tipo, p_sq_sistema, p_sq_usuario, p_nome, null
      If p_sq_tabela = "" Then
         RS.Sort = "nm_coluna"
      Else
         RS.Sort = "ordem"
      End If
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetColuna RS, w_cliente, w_chave, w_sq_tabela, w_sq_dado_tipo, null, null, null, null
      w_sq_tabela        = RS("sq_tabela")
      w_sq_dado_tipo     = RS("sq_dado_tipo")
      w_nome             = RS("nm_coluna")
      w_descricao        = RS("descricao")
      w_ordem            = RS("ordem")
      w_tamanho          = RS("tamanho")
      w_precisao         = RS("precisao")
      w_escala           = RS("escala")
      w_obrigatorio      = RS("obrigatorio")
      w_valor_padrao     = RS("valor_padrao")
      w_sq_sistema       = RS("sq_sistema")
      w_sq_usuario       = RS("sq_usuario")   
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"     , "Sistema"               , "SELECT", "1", "1", "18"  , ""    , "1"
         Validate "w_sq_usuario"     , "Usuário"               , "SELECT", "1", "1", "18"  , "1"   , "1"
         Validate "w_sq_tabela"      , "Tabela"                , "SELECT", "1", "1", "18"  , "1"   , "1"
         Validate "w_nome"           , "Nome"                  , "1"     , "1", "3", "30"  , "1"   , "1"
         Validate "w_sq_dado_tipo"   , "Tipo Dado"             , "SELECT", "1", "1", "18"  , ""    , "1"
         Validate "w_ordem"          , "Ordem"                 , "1"     , "1", "1", "18"  , ""    , "1"
         Validate "w_tamanho"        , "Tamanho"               , "1"     , "1", "1", "18"  , ""    , "1"
         Validate "w_precisao"       , "Precisão"              , "1"     , "1", "2", "30"  , "1"   , "1"
         Validate "w_escala"         , "Escala"                , "1"     , "1", "1", "18"  , ""    , "1"
         Validate "w_obrigatorio"    , "Obrigatório"           , "SELECT", "1", "1", "1"   , "1"   , "1"
         Validate "w_valor_padrao"   , "Valor Padrão"          , ""      , "1", "1", "255" , "1"   , "1"
         Validate "w_descricao"      , "Descrição"             , "1"     , "1", "5", "4000", "1"   , "1"
         Validate "w_assinatura"     , "Assinatura Eletrônica" , "1"     , "1", "6", "30"  , "1"   , "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
         ShowHTML "  theForm.Botao[0].disabled=true;"
         ShowHTML "  theForm.Botao[1].disabled=true;"
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_tabela.selectedIndex==0 && theForm.p_sq_dado_tipo.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_sq_usuario & p_sq_dado_tipo & p_sq_tabela & p_nome > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Coluna</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Obrig.</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Default</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela")&""">"&lcase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A></td>"
            ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=COLUNA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_coluna=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"" target=""coluna"">"&lcase(RS("nm_coluna"))
            If Nvl(RS("sq_relacionamento"),"nulo") <> "nulo" Then
               ShowHTML "          (FK)"
            End If
            ShowHTML "          </A>&nbsp"
            ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_coluna_tipo") & " ("
            If uCase(RS("nm_coluna_tipo")) = "NUMERIC" Then
               ShowHTML Nvl(RS("precisao"), RS("tamanho")) & "," & Nvl(RS("escala"),0)
            Else
               ShowHTML RS("tamanho")
            End If
            ShowHTML ")</td>"
            ShowHTML "        <td align=""center""><font size=""1"">" & RS("obrigatorio") & "</td>"
            if RS("valor_padrao") <> "" then 
               ShowHTML "      <td><font size=""1"">" & RS("valor_padrao") &"</td>"
            else
               ShowHTML "      <td><font size=""1"">---</td>" 
            End If
            ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
            If P1 = 0 Then
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
    ' Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, w_sq_usuario, Nvl(w_sq_sistema,0), "w_sq_usuario", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_tabela'; document.Form.submit();"""
      SelecaoTabela   "Ta<u>b</u>ela:", "B", null, w_cliente, w_sq_tabela , Nvl(w_sq_usuario,0), null, "w_sq_tabela", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      SelecaoDadoTipo "Tipo <u>D</u>ado:", "D", null, w_sq_dado_tipo, null, "w_sq_dado_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>O</u>rdem:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_ordem & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>amanho:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_tamanho"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_tamanho & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>recisao:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_precisao"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_precisao & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>E</u>scala:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_escala"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_escala & """></td>"
      SelecaoObrigatorio "Obr<u>i</u>gatório:", "I", null, w_obrigatorio, null, "w_obrigatorio", null, null
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>V</u>alor padrão:</b><br><textarea " & w_Disabled & " accesskey=""V"" name=""w_valor_padrao"" class=""sti"" ROWS=5 COLS=75>" & w_valor_padrao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.p_sq_usuario.value=''; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_sistema'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_usuario", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_tabela'; document.Form.submit();"""
      SelecaoTabela   "Ta<u>b</u>ela:", "B", null, w_cliente, p_sq_tabela , p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_tabela", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td></tr>"
      SelecaoDadoTipo "<u>T</u>ipo:", "T", null, p_sq_dado_tipo, null, "p_sq_dado_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"    
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set w_sq_tabela         = Nothing
   Set w_sq_dado_tipo      = Nothing
   Set w_nome              = Nothing
   Set w_descricao         = Nothing
   Set w_ordem             = Nothing
   Set w_tamanho           = Nothing
   Set w_precisao          = Nothing
   Set w_escala            = Nothing
   Set w_obrigatorio       = Nothing
   Set w_valor_padrao      = Nothing
   Set p_sq_sistema        = Nothing
   Set p_sq_usuario        = Nothing
   Set p_nome              = Nothing
   Set p_sq_dado_tipo      = Nothing
   Set p_sq_tabela         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Colunas
REM ------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Procedures
REM -------------------------------------------------------------------------
Sub Procedure
   Dim w_sq_arquivo, w_sq_sistema, w_sq_sp_tipo, w_nome, w_descricao 
   Dim p_sq_arquivo, p_sq_sistema, p_sq_sp_tipo, p_nome 
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_sq_arquivo      = Request("p_sq_arquivo")
   p_sq_sistema      = Request("p_sq_sistema")
   p_sq_sp_tipo      = Request("p_sq_sp_tipo")
   p_nome            = uCase(Request("p_nome"))
   If w_troca > "" Then ' Se for recarga da página
      w_sq_arquivo           = Request("w_sq_arquivo")
      w_sq_sistema           = Request("w_sq_sistema")
      w_sq_sp_tipo           = Request("w_sq_sp_tipo")
      w_nome                 = Request("w_nome")
      w_descricao            = Request("w_descricao")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetProcedure RS, w_cliente, null, p_sq_arquivo, p_sq_sistema, p_sq_sp_tipo, p_nome
      RS.Sort = "nm_procedure"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetProcedure RS, w_cliente, w_chave, null, null, null, null
      w_sq_arquivo           = RS("sq_arquivo")
      w_sq_sistema           = RS("sq_sistema")
      w_sq_sp_tipo           = RS("sq_sp_tipo")
      w_nome                 = RS("nm_procedure")
      w_descricao            = RS("ds_procedure")    
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"    , "Sistema"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_arquivo"    , "Arquivo"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_sp_tipo"    , "Tipo SP"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_nome"          , "Nome Procedure"       , "1"     , "1", "2"   , "30"  , "1"  , "1"
         Validate "w_descricao"     , "Descrição"            , "1"     , "1", "5"   , "4000", "1"  , "1"
         Validate "w_assinatura"    , "Assinatura Eletrônica", "1"     , "1", "6"   , "30"  , "1"  , "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_arquivo.selectedIndex==0 && theForm.p_sq_sp_tipo.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
         ShowHTML "  theForm.Botao[0].disabled=true;"
         ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_sq_arquivo & p_sq_sp_tipo > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Arquivo</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo SP</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Nome Procedure</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>"
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=ARQUIVO&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_arquivo=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"" target="""&RS("nm_arquivo")&""">"&lCase(RS("nm_arquivo"))&"</A>&nbsp"
            ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=PROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_procedure=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"" target="""&RS("nm_procedure")&""">"&lCase(RS("nm_procedure"))&"</A>&nbsp"
            ShowHTML "        <td><font size=""1"">" & RS("ds_procedure") & "</td>"
            ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
            If P1 = 0 Then
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
            End If
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "ProcTab&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & " - Tabelas&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Definir ligação com tabelas"">Tab</A>&nbsp"
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "ProcSP&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & " - SP&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Definir ligação com SP"">SP</A>&nbsp"
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "ProcParam&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & " - Parâmetros&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Manipular os parâmetros desta Procedure"">Par</A>&nbsp"
            ShowHTML "        </td>"
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema     "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_arquivo'; document.Form.submit();"""
      SelecaoArquivo     "<u>A</u>rquivo:", "A", null, w_cliente, w_sq_arquivo, w_sq_sistema, "w_sq_arquivo", null, null
      SelecaoTipoSP      "<u>T</u>ipo SP:", "T", null, w_sq_sp_tipo, null, "w_sq_sp_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_arquivo'; document.Form.submit();"""
      SelecaoArquivo "<u>A</u>rquivo:", "A", null, w_cliente, p_sq_arquivo, Nvl(p_sq_sistema,0), "p_sq_arquivo", null, null
      SelecaoTipoSP  "<u>T</u>ipo SP:", "T", null, p_sq_sp_tipo, null, "p_sq_sp_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"    
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set p_sq_sp_tipo           = Nothing 
   Set p_sq_arquivo           = Nothing 
   Set p_sq_sistema           = Nothing 
   Set p_nome                 = Nothing
   Set w_sq_sp_tipo           = Nothing 
   Set w_sq_arquivo           = Nothing 
   Set w_sq_sistema           = Nothing 
   Set w_nome                 = Nothing 
   Set w_descricao            = Nothing 
   Set w_chave                = Nothing 
   Set w_troca                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Procedures
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Relacionamentos
REM -------------------------------------------------------------------------
Sub Relacionamento
   Dim w_nome, w_descricao, w_sq_tabela_pai, w_sq_tabela_filha, w_sq_sistema 
   Dim w_destaque
   Dim p_nome, p_sq_tabela, p_sq_sistema, p_sq_usuario
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_nome            = uCase(Request("p_nome"))
   p_sq_tabela       = Request("p_sq_tabela")
   p_sq_sistema      = Request("p_sq_sistema")
   p_sq_usuario      = Request("p_sq_usuario")
   If w_troca > "" Then ' Se for recarga da página
      w_nome            = Request("w_nome")
      w_descricao       = Request("w_descricao")
      w_sq_tabela_pai   = Request("w_sq_tabela_pai")
      w_sq_tabela_filha = Request("w_sq_tabela_filha")
      w_sq_sistema      = Request("w_sq_sistema")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetRelacionamento RS, w_cliente, null, p_nome, p_sq_tabela, p_sq_sistema, p_sq_usuario
      RS.Sort = "nm_relacionamento, nm_usuario_tab_filha, nm_tabela_filha, nm_usuario_tab_pai, nm_tabela_pai"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      'Recupera os dados do Endereço informado
      DB_GetRelacionamento RS, w_cliente, w_chave, null, null, null
      w_nome                 = RS("nm_relacionamento")
      w_descricao            = RS("ds_relacionamento")
      w_sq_tabela_pai        = RS("tabela_pai")
      w_sq_tabela_filha      = RS("tabela_filha")
      w_sq_sistema           = RS("sq_sistema")   
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"      , "Sistema"               , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_tabela_pai"   , "Tabela Pai"            , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_tabela_filha" , "Tabela Tilha"          , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_nome"            , "Nome Procedure"        , "1"     , "1", "2"   , "30"  , "1"  , "1"
         Validate "w_descricao"       , "Descrição"             , "1"     , "1", "5"   , "4000", "1"  , "1"
         Validate "w_assinatura"      , "Assinatura Eletrônica" , "1"     , "1", "6"   , "30"  , "1"  , "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_tabela.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome & p_sq_tabela & p_sq_sistema & p_sq_usuario > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=5>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Relacionamento</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Referenciada</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td nowrap><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=RELACIONAMENTO&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("tabela_filha") & "&w_sq_relacionamento="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"">"&lCase(RS("nm_relacionamento"))&"</A>&nbsp"
            If cDbl(RS("sq_tabela_filha")) = p_sq_tabela Then w_destaque = "<b>" Else w_destaque = "" End If
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela_filha") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela_filha")&""">" & w_destaque & lcase(RS("nm_usuario_tab_filha") & "." & RS("nm_tabela_filha")) & "</A></td>"
            If cDbl(RS("sq_tabela_pai")) = p_sq_tabela Then w_destaque = "<b>" Else w_destaque = "" End If
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela_pai") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela_pai")&""">" & w_destaque & lcase(RS("nm_usuario_tab_pai") & "." & RS("nm_tabela_pai"))&"</A></td>"
            If P1 = 0 Then
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
      End If
      ShowHTML "      </center>"
      ShowHTML "    </table>"
      ShowHTML "  </td>"
      ShowHTML "<tr><td align=""center"" colspan=5>"
      If R > "" Then
         MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
      Else
         MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
      End If
      ShowHTML "</tr>"
      DesconectaBD
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null,           "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.p_sq_usuario.value=''; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_sistema'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_usuario", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.p_sq_usuario.value=''; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_tabela';  document.Form.submit();"""
      SelecaoTabela   "Ta<u>b</u>ela:", "B", null, w_cliente, p_sq_tabela , p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_tabela", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema  , w_cliente          , "p_sq_sistema", null         , "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, p_sq_usuario  , Nvl(p_sq_sistema,0), "p_sq_usuario", null         , "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_tabela';  document.Form.submit();"""
      Selecaotabela  "T<u>a</u>bela:" , "A", null, w_cliente, p_sq_tabela   , p_sq_usuario,  Nvl(p_sq_sistema,0), "p_sq_tabela", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"    
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
     
   Set w_nome                 = Nothing 
   Set w_descricao            = Nothing 
   Set w_sq_tabela_pai        = Nothing 
   Set w_sq_tabela_filha      = Nothing 
   Set w_sq_sistema           = Nothing 
   Set p_nome                 = Nothing
   Set p_sq_tabela            = Nothing 
   Set p_sq_sistema           = Nothing 
   Set w_chave                = Nothing 
   Set w_troca                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Relacionamentos
REM -------------------------------------------------------------------------

REM ==========================================================================
REM Rotina da tabela de sistema
REM --------------------------------------------------------------------------
Sub Sistema
   Dim w_nome, w_descricao, w_sigla
  
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
  
   If w_troca > "" Then ' Se for recarga da página
      w_nome                 = Request("w_nome")
      w_descricao            = Request("w_descricao")
      w_sigla                = Request("w_sigla")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetSistema RS, null, w_cliente
      RS.Sort = "nome"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetSistema RS, w_chave, w_cliente
      w_nome                 = RS("nome")
      w_sigla                = RS("sigla")
      w_descricao            = RS("descricao")
      DesconectaBD
   End If
  
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_nome", "Nome", "1", "1", "2", "30", "1", "1"
         Validate "w_sigla", "Sigla", "1", "1", "2", "10", "1", "1"
         Validate "w_descricao", "Descrição", "1", "1", "5", "4000", "1", "1"
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
      If P1 = 0 Then ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=4>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sigla</font></td>"
      ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         While Not RS.EOF
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=USUARIO&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Sistema"" target="""&RS("nome")&""">"&lCase(RS("sigla"))&"</A>&nbsp"
            ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
            If P1 = 0 Then 
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_cliente & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>S</u>igla:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_sigla"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_sigla & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center""><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
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
   Set w_chave     = Nothing 
   Set w_nome      = Nothing
   Set w_descricao = Nothing 
   Set w_sigla     = Nothing 
   Set w_troca     = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina da tabela de sistema
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Stored Procedures
REM -------------------------------------------------------------------------
Sub StoredProcedure
   Dim w_sq_sp_tipo, w_sq_usuario, w_sq_sistema, w_nome, w_descricao 
   Dim p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome 
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_sq_sp_tipo      = Request("p_sq_sp_tipo")
   p_sq_usuario      = Request("p_sq_usuario")
   p_sq_sistema      = Request("p_sq_sistema")
   p_nome            = uCase(Request("p_nome"))
   If w_troca > "" Then ' Se for recarga da página
      w_sq_sp_tipo  = Request("w_sq_sp_tipo")
      w_sq_usuario  = Request("w_sq_usuario")
      w_sq_sistema  = Request("w_sq_sistema")
      w_nome        = Request("w_nome")
      w_descricao   = Request("w_descricao")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetStoredProcedure RS, w_cliente, null, null, p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome, null
      RS.Sort = "nm_usuario, nm_sp"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetStoredProcedure RS, w_cliente, w_chave, null, p_sq_sp_tipo, p_sq_usuario, p_sq_sistema, p_nome, null
      w_sq_sp_tipo           = RS("sq_sp_tipo")
      w_sq_usuario           = RS("sq_usuario")
      w_sq_sistema           = RS("sq_sistema")
      w_nome                 = RS("nm_sp")
      w_descricao            = RS("ds_sp")    
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"    , "Sistema"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_usuario"    , "Usuário"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_sq_sp_tipo"    , "Tipo SP"              , "SELECT", "1", "1"   , "18"  , "1"  , "1"
         Validate "w_nome"          , "Nome"                 , "1"     , "1", "2"   , "30"  , "1"  , "1"
         Validate "w_descricao"     , "Descrição"            , "1"     , "1", "5"   , "4000", "1"  , "1"
         Validate "w_assinatura"    , "Assinatura Eletrônica", "1"     , "1", "6"   , "30"  , "1"  , "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_sp_tipo.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_sq_usuario & p_sq_sp_tipo & p_nome > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Stored Procedure</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>"
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=STOREDPROCEDURE&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_sp=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Nome"" target=""" &  RS("nm_sp") & """>"&lCase(RS("nm_usuario")&"."&RS("nm_sp"))&"</A>&nbsp"
            ShowHTML "        <td><font size=""1"">" & RS("nm_sp_tipo") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("ds_sp") & "</td>"
            ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
            If P1 = 0 Then
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R="  & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ Title=""Alterar"">Alt</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R="  & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ Title=""Excluir"">Exc</A>&nbsp"
            End If
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "SPTabs&R="  & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & " - Tabelas&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Definir ligação com tabelas"">Tab</A>&nbsp"
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "SPSP&R="    & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & " - SP&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Definir ligação com outras SP"">SP</A>&nbsp"
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "SPParam&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & " - Parâmetros&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Manipular os parâmetros desta SP"">Par</A>&nbsp"
            ShowHTML "        </td>"
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, w_sq_usuario, Nvl(w_sq_sistema,0), "w_sq_usuario", null, null
      SelecaoTipoSP "<u>T</u>ipo:", "T", null, w_sq_sp_tipo, null, "w_sq_sp_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML " <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_usuario", null, null
      SelecaoTipoSP  "<u>T</u>ipo SP:", "T", null, p_sq_sp_tipo, null, "p_sq_sp_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set p_sq_sp_tipo           = Nothing 
   Set p_sq_usuario           = Nothing 
   Set p_sq_sistema           = Nothing 
   Set p_nome                 = Nothing
   Set w_sq_sp_tipo           = Nothing 
   Set w_sq_usuario           = Nothing 
   Set w_sq_sistema           = Nothing 
   Set w_nome                 = Nothing 
   Set w_descricao            = Nothing 
   Set w_chave                = Nothing 
   Set w_troca                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Stored Procedures
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de tabelas
REM -------------------------------------------------------------------------
Sub Tabela
   Dim w_sq_tabela_tipo, w_sq_usuario, w_sq_sistema, w_nome, w_descricao 
   Dim p_sq_sistema, p_sq_usuario, p_nome, p_sq_tabela_tipo
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_sq_tabela_tipo  = Request("p_sq_tabela_tipo")
   p_sq_usuario      = Request("p_sq_usuario")
   p_nome            = uCase(Request("p_nome"))
   p_sq_sistema      = Request("p_sq_sistema")
   If w_troca > "" Then ' Se for recarga da página
      w_sq_tabela_tipo       = Request("w_sq_tabela_tipo")
      w_sq_usuario           = Request("w_sq_usuario")
      w_nome                 = Request("w_nome")
      w_descricao            = Request("w_descricao")
      w_sq_sistema           = Request("w_sq_sistema")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetTabela RS, w_cliente, null, null, p_sq_sistema, p_sq_usuario, p_sq_tabela_tipo, p_nome, null
      RS.Sort = "sg_sistema, nm_usuario, nome"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetTabela RS, w_cliente, w_chave, null, null, null, null, null, null
      w_nome                 = RS("nome")
      w_descricao            = RS("descricao")
      w_sq_sistema           = RS("sq_sistema")
      w_sq_tabela_tipo       = RS("sq_tabela_tipo")
      w_sq_usuario           = RS("sq_usuario")
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema", "Sistema", "SELECT", "1", "1", "18", "", "1"
         Validate "w_sq_usuario", "Usuário", "SELECT", "1", "1", "18", "", "1"
         Validate "w_nome", "Nome", "1", "1", "2", "30", "1", "1"
         Validate "w_sq_tabela_tipo", "Tipo", "SELECT", "1", "1", "18", "", "1"
         Validate "w_descricao", "Descrição", "1", "1", "5", "4000", "1", "1"
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_tabela_tipo.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_sq_usuario & p_sq_tabela_tipo > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nome")&""">"&lCase(RS("nm_usuario")&"."&RS("nome"))&"</A></td>"
            ShowHTML "        <td><font size=""1"">" & RS("nm_tipo") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
            If P1 = 0 Then
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, w_sq_usuario, Nvl(w_sq_sistema,0), "w_sq_usuario", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      SelecaoTipoTabela "<u>T</u>ipo:", "T", null, w_sq_tabela_tipo, null, "w_sq_tabela_tipo", null, null
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "S", null, w_cliente, p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_usuario", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      SelecaoTipoTabela "<u>T</u>ipo:", "T", null, p_sq_tabela_tipo, null, "p_sq_tabela_tipo", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set p_sq_sistema      = Nothing 
   Set p_sq_usuario      = Nothing 
   Set p_nome            = Nothing
   Set p_sq_tabela_tipo  = Nothing 
   Set w_chave           = Nothing 
   Set w_nome            = Nothing
   Set w_descricao       = Nothing 
   Set w_sq_sistema      = Nothing 
   Set w_troca           = Nothing
   Set w_sq_tabela_tipo  = Nothing 
   Set w_sq_usuario      = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina tabela de tabelas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Triggers
REM -------------------------------------------------------------------------
Sub Trigger
   Dim w_sq_tabela, w_sq_usuario, w_sq_sistema, w_nome, w_descricao
   Dim p_chave, p_sq_sistema, p_sq_usuario, p_sq_tabela, p_nome
   w_Chave            = Request("w_Chave")
   w_troca            = Request("w_troca")
   p_chave          = Request("p_chave")
   p_sq_sistema     = Request("p_sq_sistema")
   p_sq_usuario     = Request("p_sq_usuario")
   p_sq_tabela      = Request("p_sq_tabela")
   p_nome           = uCase(Request("p_nome"))
   If w_troca > "" Then ' Se for recarga da página
      w_sq_tabela      = Request("w_sq_tabela")
      w_sq_usuario     = Request("w_sq_usuario")
      w_sq_sistema     = Request("w_sq_sistema")
      w_nome           = Request("w_nome")
      w_descricao      = Request("w_descricao")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetTrigger RS, w_cliente, p_chave, p_sq_tabela, p_sq_usuario, p_sq_sistema
      RS.Sort = "nm_trigger, nm_tabela"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetTrigger RS, w_cliente, w_chave, w_sq_tabela, w_sq_usuario,w_sq_sistema
      w_sq_tabela       = RS("sq_tabela")
      w_sq_usuario      = RS("sq_usuario")
      w_sq_sistema      = RS("sq_sistema")
      w_nome            = RS("nm_trigger")
      w_descricao       = RS("ds_trigger")
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"     , "Sistema"                , "1", "1", "1", "18"  , "1", "1"
         Validate "w_sq_usuario"     , "Usuário"                , "1", "1", "1", "18"  , "1", "1"
         Validate "w_sq_tabela"      , "Tabela"                 , "1", "1", "1", "18"  , "1", "1"
         Validate "w_nome"           , "Trigger"                , "1", "1", "3", "30"  , "1", "1"
         Validate "w_descricao"      , "Descrição"              , "1", "1", "5", "4000", "1", "1"
         Validate "w_assinatura"     , "Assinatura Eletrônica"  , "1", "1", "6", "30"  , "1", "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "18", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_chave.selectedIndex==0 && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_tabela.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
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
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_chave & p_sq_sistema & p_sq_usuario & p_sq_tabela & p_nome > "" Then
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Trigger</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Eventos de disparo</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>"
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td title=""" & RS("nm_sistema") &"""><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TRIGGER&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_sistema") & "&w_sq_trigger="& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""TRIGGERS"" target="""&RS("nm_trigger")&""">"&lcase(RS("nm_trigger"))&"</A></tr>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela")  & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela")&""">"&lcase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A></td>"
            ShowHTML "        <td><font size=""1"">" & Nvl(RS("eventos"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("ds_trigger") & "</td>"
            ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
            If P1 = 0 Then
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
            End If
            ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "Evento&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1 & "&P4=" & P4 & "&TP=" & TP & " - Tabelas&SG=" & SG & MontaFiltro("GET") & """ Target=""_blank"" Title=""Eventos de Trigger"">Eventos</A>&nbsp"
            ShowHTML "        </td>"
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "     <INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "     <INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "     <tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "     <table width=""97%"" border=""0"">"
      ShowHTML "     <tr>"
      SelecaoSistema "<u>S</u>istema:", "T", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, w_sq_usuario, Nvl(w_sq_sistema,0), "w_sq_usuario", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_tabela'; document.Form.submit();"""
      SelecaoTabela  "Ta<u>b</u>ela:" , "B", null, w_cliente, w_sq_tabela , Nvl(w_sq_usuario,0), null, "w_sq_tabela" , null, null
      ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>T</u>rigger:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "     <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "     <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            If P1 = 0 then
               ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
            End If
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema  , w_cliente          , "p_sq_sistema"      , null         ,            "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario "<u>U</u>suário:", "U", null, w_cliente, p_sq_usuario  , Nvl(p_sq_sistema,0), "p_sq_usuario"      , null         ,            "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_tabela';  document.Form.submit();"""
      SelecaoTabela  "T<u>a</u>bela:" , "A", null, w_cliente, p_sq_tabela   , p_sq_usuario       ,  Nvl(p_sq_sistema,0), "p_sq_tabela", null     , "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_chave'; document.Form.submit();"""
      SelecaoTrigger "<u>T</u>rigger:", "T", null, w_cliente, p_chave       , Nvl(p_sq_sistema,0),  p_sq_usuario       ,  p_sq_tabela , "p_chave", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      If P1 = 0 Then
         ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"    
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set w_sq_tabela  = Nothing
   Set w_sq_usuario = Nothing
   Set w_nome       = Nothing
   Set w_descricao  = Nothing
   Set w_sq_sistema = Nothing
   Set p_chave      = Nothing
   Set p_sq_sistema = Nothing
   Set p_sq_usuario = Nothing
   Set p_sq_tabela  = Nothing
   Set p_nome       = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Triggers
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Usuários
REM -------------------------------------------------------------------------
Sub Usuario
   Dim  w_sq_sistema, w_nome, w_descricao
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   If w_troca > "" Then ' Se for recarga da página
      w_nome                 = Request("w_nome")
      w_descricao            = Request("w_descricao")
      w_sq_sistema           = Request("w_sq_sistema")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetUsuario RS, w_cliente, null, w_sq_sistema
      RS.Sort = "sg_sistema,nome"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do usuário informado
      DB_GetUsuario RS, w_cliente, w_chave, w_sq_sistema
      w_nome                 = RS("nome")
      w_descricao            = RS("descricao")
      w_sq_sistema           = RS("sq_sistema")
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_nome", "Nome", "1", "1", "2", "30", "1", "1"
         Validate "w_sq_sistema", "Sistema", "SELECT", "1", "1", "18", "", "1"
         Validate "w_descricao", "Descrição", "1", "1", "5", "4000", "1", "1"
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
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      If P1 = 0 Then ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</font></td>"
      ShowHTML "          <td><font size=""1""><b>Usuário</font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         ' Lista os registros selecionados para listagem
         While Not RS.EOF
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=USUARIO&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_sistema") & "&w_sq_usuario= "& RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Usuario"" target="""&RS("nome")&""">"&lCase(RS("nome"))&"</A></td>"
            ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
            If P1 = 0 Then
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & "Grava&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return(confirm('Confirma a atualização automática do dicionário de dados desse usuário?'));"">Atualizar</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
      End If
      ShowHTML "      </center>"
      ShowHTML "    </table>"
      ShowHTML "  </td>"
      ShowHTML "</tr>"
      DesconectaBD
    ' Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then w_Disabled = " DISABLED " End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema "<u>S</u>istema:", "S", null, w_sq_sistema, w_cliente, "w_sq_sistema", null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
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
   Set w_chave                = Nothing 
   Set w_nome                 = Nothing
   Set w_descricao            = Nothing 
   Set w_sq_sistema           = Nothing 
   Set w_troca                = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina da tabela de Usuários
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de Índice
REM -------------------------------------------------------------------------
Sub Indice
   Dim w_sq_indice_tipo, w_sq_usuario, w_sq_sistema, w_nome, w_descricao 
   Dim p_sq_sistema, p_sq_usuario, p_sq_indice_tipo, p_nome, p_sq_tabela
   w_Chave           = Request("w_Chave")
   w_troca           = Request("w_troca")
   p_sq_sistema      = Request("p_sq_sistema")
   p_sq_usuario      = Request("p_sq_usuario")
   p_sq_indice_tipo  = Request("p_sq_indice_tipo")
   p_sq_tabela       = Request("p_sq_tabela")
   p_nome            = uCase(Request("p_nome"))
   If w_troca > "" Then ' Se for recarga da página
      w_sq_indice_tipo       = Request("w_sq_indice_tipo")
      w_sq_usuario           = Request("w_sq_usuario")
      w_sq_sistema           = Request("w_sq_sistema")
      w_descricao            = Request("w_descricao")
      w_nome                 = Request("w_nome")
   ElseIf O = "L" Then
      ' Recupera todos os registros para a listagem
      DB_GetIndice RS, w_cliente, null, p_sq_indice_tipo, p_sq_usuario, p_sq_sistema, p_nome, p_sq_tabela
      RS.Sort = "nm_indice, nm_usuario, nm_tabela"
   ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
      ' Recupera os dados do Endereço informado
      DB_GetIndice RS, w_cliente, w_chave, null, null, null, null, null
      w_sq_indice_tipo       = RS("sq_indice_tipo")
      w_sq_usuario           = RS("sq_usuario")
      w_sq_sistema           = RS("sq_sistema")
      w_nome                 = RS("nm_indice")
      w_descricao            = RS("ds_indice")
      DesconectaBD
   End If
   Cabecalho
   ShowHTML "<HEAD>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_sistema"     , "Sistema"               , "SELECT", "1", "1", "18"  , "" , "1"
         Validate "w_sq_usuario"     , "Usuário"               , "SELECT", "1", "1", "18"  , "" , "1"
         Validate "w_sq_indice_tipo" , "Índice Tipo"           , "SELECT", "1", "1", "18"  , "" , "1"
         Validate "w_nome"           , "Nome do índice"        , "1"     , "1", "2", "30"  , "1", "1"
         Validate "w_descricao"      , "Descrição"             , "1"     , "1", "5", "4000", "1", "1"
         Validate "w_assinatura"     , "Assinatura Eletrônica" , "1"     , "1", "6", "30"  , "1", "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      ElseIf O="P" Then
         Validate "p_nome", "Nome", "1", "", "3", "15", "1", "1"
         ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_indice_tipo.selectedIndex==0 && theForm.p_sq_tabela.selectedIndex==0) {"
         ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
         ShowHTML "     return false;"
         ShowHTML "  }"
         Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      If O = "P" Then ShowHTML "  theForm.Botao[2].disabled=true;" End If
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   If w_troca > "" Then
      BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
   ElseIf Instr("IA",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_sistema.focus()';"
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
      ShowHTML "<tr><td><font size=""1"">"
      If P1 = 0 Then ShowHTML "                         <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      If p_nome  & p_sq_sistema & p_sq_usuario & p_sq_indice_tipo & p_sq_tabela > "" Then
         ShowHTML "  <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
      Else
         ShowHTML "  <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
      End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Nome</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo Índice</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         'Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("sg_sistema")     & "</td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=INDICE&R=" & w_Pagina & par & "&O=l&w_chave=" & RS("sq_sistema") & "&w_sq_indice="&RS("chave")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_indice")&""">"&lCase(RS("nm_indice"))&"</A></td>"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela")&""">"&lcase(RS("nm_usuario")&"."&RS("nm_tabela"))&"</A></td>"
            ShowHTML "        <td><font size=""1"">" & RS("nm_indice_tipo") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("ds_indice")      & "</td>"
            If P1 = 0 Then
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
    ' Aqui começa a manipulação de registros
   ElseIf Instr("IAEV",O) > 0 Then
      If InStr("EV",O) Then w_Disabled = " DISABLED " End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & Par,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema    "<u>S</u>istema:", "S", null, w_sq_sistema       , w_cliente            , "w_sq_sistema"      , null         , "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario    "<u>U</u>suário:", "U", null, w_cliente, w_sq_usuario       , Nvl(w_sq_sistema,0)  , "w_sq_usuario"      , null         , "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_indice_tipo'; document.Form.submit();"""
      SelecaoTipoIndice "<u>T</u>ipo:"   , "T", null, w_sq_indice_tipo   , Nvl(w_sq_usuario,0)  , "w_sq_indice_tipo"  , null, null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome Índice:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nome & """></td>"
      ShowHTML "      <tr><td valign=""top"" colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>" & w_descricao & "</TEXTAREA></td>"
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   ElseIf Instr("P",O) > 0 Then ' filtragem de fluxo de dados
      AbreForm "Form", w_dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justIfy""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""70%"" border=""0"">"
      ShowHTML "      <tr>"
      SelecaoSistema    "<u>S</u>istema:"     , "S", null, p_sq_sistema     , w_cliente           , "p_sq_sistema"      , null         ,            "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
      SelecaoUsuario    "<u>U</u>suário:"     , "U", null, w_cliente, p_sq_usuario     , Nvl(p_sq_sistema,0) , "p_sq_usuario"      , null         ,            "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_tabela' ; document.Form.submit();"""
      SelecaoTabela     "T<u>a</u>bela:"      , "A", null, w_cliente, p_sq_tabela      , p_sq_usuario        ,  Nvl(p_sq_sistema,0), "p_sq_tabela", null     , null
      ShowHTML "      <tr>"
      SelecaoTipoIndice "<u>T</u>ipo Índice:" , "T", null, p_sq_indice_tipo , Nvl(p_sq_usuario,0) , "p_sq_indice_tipo"  , null         , null
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
      ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=I&SG=" & SG & "';"" name=""Botao"" value=""Incluir"">"
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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
   Set p_sq_sistema           = Nothing 
   Set p_sq_usuario           = Nothing 
   Set p_nome                 = Nothing
   Set p_sq_indice_tipo       = Nothing 
   Set w_sq_indice_tipo       = Nothing
   Set w_sq_usuario           = Nothing 
   Set w_sq_sistema           = Nothing 
   Set w_nome                 = Nothing
   Set w_descricao            = Nothing 
   Set w_chave                = Nothing 
   Set w_troca                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina tabela de Índice
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de associação de triggers a eventos
REM -------------------------------------------------------------------------
Sub Evento
   Dim w_chave, w_chave_pai, w_chave_aux
   Dim w_troca
   Dim w_texto
   Dim w_cont, w_contaux
   w_troca       = Request("w_troca")
   w_chave       = Request("w_chave")
   w_chave_aux   = Request("w_chave_aux")
   w_chave_pai   = Request("w_chave_pai")
  
   DB_GetTrigEvento RS, w_chave, null
   RS.Sort = "nm_evento"
   Cabecalho
   ShowHTML "<HEAD>"
   ScriptOpen "JavaScript"
   ValidateOpen "Validacao"
   ShowHTML "  for (i = 0; i < theForm.w_evento.length; i++) {"
   ShowHTML "      if (theForm.w_evento[i].checked) break;"
   ShowHTML "      if (i == theForm.w_evento.length-1) {"
   ShowHTML "         alert('Você deve selecionar pelo menos um evento!');"
   ShowHTML "         return false;"
   ShowHTML "      }"
   ShowHTML "  }"
   ShowHTML "  theForm.Botao[0].disabled=true;"
   ShowHTML "  theForm.Botao[1].disabled=true;"
   ValidateClose
   ScriptClose
   ShowHTML "<BASE HREF=""" & conRootSIW & """>"
   ShowHTML "</HEAD>"
   BodyOpen "onLoad=document.focus();"
   ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
   ShowHTML "<HR>"
   ShowHTML "<div align=center><center>"
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
   ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
   ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   ShowHTML "        <tr valign=""top"">"
   ShowHTML "          <td><font size=""1"">Sistema:<br><b>" & RS("nm_sistema") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Usuário:<br> <b>" & RS("nm_usuario") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Tabela:<br><b>" & RS("nm_tabela") & "</font></td>"
   ShowHTML "        <tr colspan=3>"
   ShowHTML "          <td><font size=""1"">Trigger:<br><b>" & RS("nome") & "</font></td>"
   ShowHTML "          <td colspan=2><font size=""1"">Descrição:<br><b>" & CRLF2BR(RS("descricao")) & "</font></td>"
   ShowHTML "    </TABLE>"
   ShowHTML "</table>"
   ShowHTML "<tr><td align=""right""><font size=""1"">&nbsp;"
   AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"TRIGEVENTO",R,O
   ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
   ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
   ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
   ShowHTML "<INPUT type=""hidden"" name=""w_evento"" value="""">"
   ShowHTML "<tr><td><font size=""1""><ul><b>Informações:</b><li>Indique abaixo quais eventos farão o disparo da trigger.<li>A princípio, uma trigger não tem nenhum evento associado.<li>Para remover um evento, desmarque o quadrado ao seu lado.</ul>"
   ShowHTML "<tr><td align=""center"" colspan=3>"
   ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
   ShowHTML "          <td><font size=""1""><b>&nbsp;</font></td>"
   ShowHTML "          <td><font size=""1""><b>Evento</font></td>"
   ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"
   ShowHTML "        </tr>"
   If RS.EOF Then
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
   Else
      While Not RS.EOF
         If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
         ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
         If cDbl(Nvl(RS("existe"),0)) > 0 Then
            ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_evento"" value=""" & RS("sq_evento") & """ checked></td>"
         Else
            ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_evento"" value=""" & RS("sq_evento") & """></td>"
         End If
         ShowHTML "        <td align=""left""><font  size=""1"">" & RS("nm_evento") & "</td>"
         ShowHTML "        <td align=""left""><font  size=""1"">" & CRLF2BR(Nvl(RS("descricao"),"---")) & "</td>"
         ShowHTML "      </tr>"
         RS.MoveNext
      Wend
   End If
   ShowHTML "      </center>"
   ShowHTML "    </table>"
   ShowHTML "  </td>"
   ShowHTML "</tr>"
   DesConectaBD
   ShowHTML "      <tr><td align=""center""><font size=1>&nbsp;"
   ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000"">"
   ShowHTML "      <tr><td align=""center"">"
   If P1 = 0 Then ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">" End If
   ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
   ShowHTML "          </td>"
   ShowHTML "      </tr>"
   ShowHTML "</table>"
   ShowHTML "</center>"
   ShowHTML "</FORM>"
   Rodape
   Set w_chave           = Nothing 
   Set w_chave_pai       = Nothing 
   Set w_chave_aux       = Nothing 
   Set w_troca           = Nothing
   Set w_texto           = Nothing
   Set w_cont            = Nothing
   Set w_contaux         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de associação entre storage procedures e tabelas
REM -------------------------------------------------------------------------
Sub SPTabs
   Dim w_chave_aux
   w_Chave           = Request("w_Chave")
   w_Chave_aux       = Request("w_Chave_aux")
   w_troca           = Request("w_troca")
   ' Recupera as tabelas vinculadas à SP informada
   DB_GetSPTabs RS, w_chave, null
   RS.Sort = "nm_usuario_tabela,nm_tabela"
   Cabecalho
   ShowHTML "<HEAD>"
   ShowHTML "<TITLE>" & conSgSistema & " - Associação entre SP e Tabelas</TITLE>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_chave_aux"      , "Tabela"                , "SELECT", "1", "1", "18"  , "" , "1"
         Validate "w_assinatura"     , "Assinatura Eletrônica" , "1"     , "1", "6", "30"  , "1", "1"
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
   If Instr("I",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_chave_aux.focus()';"
   ElseIf O = "E" Then
      BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
   Else
      BodyOpen "onLoad='document.focus()';"
   End If
   ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
   ShowHTML "<HR>"
   ShowHTML "<div align=center><center>"
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

   ' Exibe os dados da SP
   DB_GetStoredProcedure RS1, w_cliente, w_chave, null, null, null, null, null, null
   RS1.Sort = "chave"
   ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=2><table border=1 width=""100%""><tr><td>"
   ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   ShowHTML "        <tr valign=""top"">"
   ShowHTML "          <td><font size=""1"">Sistema:<br><b>" & RS1("nm_sistema") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Usuário:<br> <b>" & RS1("nm_usuario") & "</font></td>"
   ShowHTML "        <tr colspan=3>"
   ShowHTML "          <td><font size=""1"">Stored procedure:<br><b>" & RS1("nm_sp") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Descrição:<br><b>" & CRLF2BR(RS1("ds_sp")) & "</font></td>"
   ShowHTML "    </TABLE>"
   ShowHTML "</table>"
   RS1.Close
  
   If O = "L" Then
      ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      ShowHTML "<tr><td><hr>"
      ShowHTML "<tr>"
      If P1 = 0 Then ShowHTML "    <td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b> Operações </b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         'Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""" & w_dir & "dc_consulta.asp?par=TABELA&R=" & w_Pagina & par & "&O=NIVEL2&w_chave=" & RS("sq_sistema") & "&w_sq_tabela="& RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & 1  & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """  Title=""Tabela"" target="""&RS("nm_tabela")&""">"&lcase(RS("nm_usuario_tabela")&"."&RS("nm_tabela"))&"</A></td>"
            ShowHTML "        <td><font size=""1"">" & RS("ds_tabela")     & "</td>"
            If P1 = 0 Then 
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IEV",O) > 0 Then
      If InStr("EV",O) Then w_Disabled = " DISABLED " End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"DCSPTAB",R,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
      'Se for exclusão, passa o código da tabela por variável hidden
      If O = "E" Then
         ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
         ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
         ShowHTML "    <table width=""97%"" border=""0"">"
         ShowHTML "      <tr>"
         SelecaoTabela "<u>T</u>abela:", "T", null, w_cliente, w_chave_aux, RS("sq_sistema"), null, "w_chave_aux", SG, null
      Else
      ShowHTML "      <tr>"
      SelecaoTabela "<u>T</u>abela:", "T", null, w_cliente, w_chave_aux, RS("sq_sistema"), w_chave, "w_chave_aux", SG, null
      End If
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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
   Set w_chave_aux           = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e tabelas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de associação entre storage procedures e SP
REM -------------------------------------------------------------------------
Sub SPSP
   Dim w_chave_aux, w_tipo
   w_Chave           = Request("w_Chave")
   w_Chave_aux       = Request("w_Chave_aux")
   w_troca           = Request("w_troca")
   'Recupera sempre todos os registros
   DB_GetSPSP RS, w_chave, w_chave_aux
   RS.sort="nm_pai, nm_filha"
   If Not RS.EOF Then w_tipo = RS("tipo") End If
   Cabecalho
   If O = "E" Then w_tipo = RS("tipo") Else w_tipo = "" End If
   ShowHTML "<HEAD>"
   ShowHTML "<TITLE>" & conSgSistema & " - Associação entre SP e SP</TITLE>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_chave_aux"  , "Outra SP"              , "SELECT", "1", "1", "18"  , "" , "1"
         Validate "w_assinatura" , "Assinatura Eletrônica" , "1"     , "1", "6", "30"  , "1", "1"
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
   If Instr("I",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_chave_aux.focus()';"
   ElseIf O = "E" Then
      BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
   Else
      BodyOpen "onLoad='document.focus()';"
   End If
   ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
   ShowHTML "<HR>"
   ShowHTML "<div align=center><center>"
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
   ' Exibe os dados da SP
   DB_GetStoredProcedure RS1, w_cliente, w_chave, null, null, null, null, null, null
   RS1.Sort = "chave"
   ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=2><table border=1 width=""100%""><tr><td>"
   ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   ShowHTML "        <tr valign=""top"">"
   ShowHTML "          <td><font size=""1"">Sistema:<br><b>" & RS1("nm_sistema") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Usuário:<br> <b>" & RS1("nm_usuario") & "</font></td>"
   ShowHTML "        <tr colspan=3>"
   ShowHTML "          <td><font size=""1"">Stored procedure:<br><b>" & RS1("nm_sp") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Descrição:<br><b>" & CRLF2BR(RS1("ds_sp")) & "</font></td>"
   ShowHTML "    </TABLE>"
   ShowHTML "</table>"
   RS1.Close
   If O = "L" Then
      ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      ShowHTML "<tr><td><hr>"
      ShowHTML "<tr>"
      If P1 = 0 Then ShowHTML "    <td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>SP Pai</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>SP Filha</b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição outra SP</b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações</b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não esistirem registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         'Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            If Nvl(RS("nm_filha"),"") > "" and Nvl(RS("nm_pai"),"") > "" Then
               ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
               If RS("tipo") = "PAI" Then
                  ShowHTML "        <td><font size=""1""><b>" & RS("nm_pai")           & "</td>"
                  ShowHTML "        <td><font size=""1"">"    & RS("nm_usuario_filha") & "." & RS("nm_filha") & "</td>"
                  ShowHTML "        <td><font size=""1"">"    & RS("ds_filha")         & "</td>"
               Else
                  ShowHTML "        <td><font size=""1"">"    & RS("nm_usuario_filha") & "." & RS("nm_filha") & "</td>"
                  ShowHTML "        <td><font size=""1""><b>" & RS("nm_pai")           & "</b></td>"
                  ShowHTML "        <td><font size=""1"">"    & RS("ds_filha")         & "</td>"
               End If
               If P1 = 0 Then
                  ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
                  ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("chave_filha") & "&w_tipo=" & RS("tipo") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
                  ShowHTML "        </td>"
               End If
               ShowHTML "      </tr>"
            End If
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"DCSPSP",R,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      'Se for exclusão, passa o código da tabela por variável hidden
      If O = "E" Then
         ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
         SelecaoSP "<u>O</u>utra SP:", "O", null, w_cliente, null, w_chave_aux, RS("sq_sistema_pai"), "w_chave_aux", SG, null
      Else
         SelecaoSP "<u>O</u>utra SP:", "O", null, w_cliente, w_chave, w_chave_aux, RS("sq_sistema_pai"), "w_chave_aux", SG, null
      End If
      If nvl(w_tipo,"PAI") = "PAI" Then
         If O = "E" Then ShowHTML "<INPUT type=""hidden"" name=""w_filha"" value=""S"">" End If
         MontaRadioSN "Outra SP é filha?", "S", "w_filha"
      Else
         If O = "E" Then ShowHTML "<INPUT type=""hidden"" name=""w_filha"" value=""N"">" End If
         MontaRadioSN "Outra SP é filha?", "N", "w_filha"
      End If
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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
   Set w_tipo                = Nothing 
   Set w_chave_aux           = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e SP
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de associação entre storage procedures e SP
REM -------------------------------------------------------------------------
Sub SPParam
   Dim w_sq_dado_tipo, w_nome, w_descricao, w_tipo, w_ordem, w_chave_aux
   w_Chave      = Request("w_Chave")
   w_troca      = Request("w_troca")
   w_chave_aux  = Request("w_chave_aux")
   'Recupera sempre todos os registros
   If O="L" Then
   DB_GetSPParametro RS, w_chave, null, null
   Else
   DB_GetSPParametro RS, null, w_chave_aux, null
   End If 
   RS.sort="ord_sp_param"
   Cabecalho
   ShowHTML "<HEAD>"
   ShowHTML "<TITLE>" & conSgSistema & " - Associação entre SP e Parâmetros</TITLE>"
   If InStr("IAEP",O) > 0 Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_sq_dado_tipo"	 , "Tipo Dado"				, "SELECT",	"1", "1", "18"  ,	""	, "1"
	         Validate "w_nome"			 , "Nome"					, "1"	  ,	"1", "1", "18"  ,	"1"	, "1"
	         Validate "w_tipo"			 , "Tipo Parâmetro"		    , "SELECT",	"1", "1", "18"  ,	"1"	, "1"
	         Validate "w_ordem"		 , "Ordem"					, "1"	  ,	"1", "1", "18"  ,	""	, "1"
	         Validate "w_descricao"	 , "Descrição"				, "1"	  ,	"1", "1", "4000",	"1"	, "1"
	         Validate "w_assinatura"	 , "Assinatura	Eletrônica"	, "1"	  ,	"1", "6", "30"  ,	"1"	, "1"
      ElseIf O = "E" Then
         w_sq_dado_tipo = RS("sq_dado_tipo")
         w_nome         = RS("nm_sp_param")
         w_descricao    = RS("ds_sp_param")
         w_tipo         = RS("tp_sp_param")
         w_ordem        = RS("ord_sp_param")
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
   If Instr("I",O) > 0 Then
      BodyOpen "onLoad='document.Form.w_sq_dado_tipo.focus()';"
   ElseIf O = "E" Then
      BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
   Else
      BodyOpen "onLoad='document.focus()';"
   End If
   ShowHTML "<HR>"
   ShowHTML "<div align=center><center>"
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
   ' Exibe os dados da SP
   DB_GetStoredProcedure RS1, w_cliente, w_chave, null, null, null, null, null, null
   RS1.Sort = "chave"
   ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=2><table border=1 width=""100%""><tr><td>"
   ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
   ShowHTML "        <tr valign=""top"">"
   ShowHTML "          <td><font size=""1"">Sistema:<br><b>" & RS1("nm_sistema") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Usuário:<br> <b>" & RS1("nm_usuario") & "</font></td>"
   ShowHTML "        <tr colspan=3>"
   ShowHTML "          <td><font size=""1"">Stored procedure:<br><b>" & RS1("nm_sp") & "</font></td>"
   ShowHTML "          <td><font size=""1"">Descrição:<br><b>" & CRLF2BR(RS1("ds_sp")) & "</font></td>"
   ShowHTML "    </TABLE>"
   ShowHTML "</table>"
   RS1.Close
   If O = "L" Then
      ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
      ShowHTML "<tr><td><hr>"
      ShowHTML "<tr>"
      If P1 = 0 Then ShowHTML "    <td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;" End If
      ShowHTML "    <td  align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
      ShowHTML "<tr><td align=""center"" colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
      ShowHTML "          <td><font size=""1""><b>Parâmetro </b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Tipo      </b></font></td>"
      ShowHTML "          <td><font size=""1""><b>IN OUT    </b></font></td>"
      ShowHTML "          <td><font size=""1""><b>Descrição </b></font></td>"
      If P1 = 0 Then ShowHTML "          <td><font size=""1""><b>Operações     </b></font></td>" End If
      ShowHTML "        </tr>"
      If RS.EOF Then ' Se não esistirem registros, exibe mensagem
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
      Else
         'Lista os registros selecionados para listagem
         rs.PageSize     = P4
         rs.AbsolutePage = P3
         While Not RS.EOF and RS.AbsolutePage = P3
            If w_cor = conTrBgColor or w_cor = "" Then
               w_cor = conTrAlternateBgColor 
            Else 
               w_cor = conTrBgColor 
            End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td><font size=""1"">" & RS("nm_sp_param") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("nm_dado_tipo") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("nm_tipo_param") & "</td>"
            ShowHTML "        <td><font size=""1"">" & RS("ds_sp_param") & "</td>"
            If P1 = 0 Then 
               ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
               ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
               ShowHTML "        </td>"
            End If
            ShowHTML "      </tr>"
            RS.MoveNext
         Wend
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
      'Aqui começa a manipulação de registros
   ElseIf Instr("IEV",O) > 0 Then
      If InStr("EV",O) Then
         w_Disabled = " DISABLED "
      End If
      AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"DCSPPARAM",R,O
      ShowHTML MontaFiltro("POST")
      ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
      ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
      ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & SG & """>"
      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
      ShowHTML "    <table width=""97%"" border=""0"">"
      ShowHTML "      <tr>"
      'Se for exclusão, passa o código da tabela por variável hidden
      If O = "E" Then
         ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
         SelecaoTipoDado  "Ti<u>p</u>o Dado:", "T", null, w_sq_dado_tipo, null, "w_sq_dado_tipo", null, null               
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE="""& w_nome &"""></td>"
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b>Ord<u>e</u>m:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""30"" MAXLENGTH=""30""VALUE="""& w_ordem &"""></td>"                  
         SelecaoTipoParam "<u>T</u>ipo Parâmetro:", "T", null, w_tipo, null,"w_tipo", null, null
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>"& w_descricao &"</TEXTAREA></td>"
      Else
         SelecaoTipoDado  "Ti<u>p</u>o Dado:", "T", null, w_sq_dado_tipo, null, "w_sq_dado_tipo", null, null               
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE="""& w_nome &"""></td>"
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b>Ord<u>e</u>m:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""30"" MAXLENGTH=""30""VALUE="""& w_ordem &"""></td>"                  
         SelecaoTipoParam "<u>T</u>ipo Parâmetro:", "T", null, w_tipo, null,"w_tipo", null, null
         ShowHTML "       <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 COLS=75>"& w_descricao &"</TEXTAREA></td>"
      End If
      ShowHTML "      <tr><td align=""LEFT"" colspan=2><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
      ShowHTML "      <tr><td align=""center"" colspan=2><hr>"
      If O = "E" Then
         ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         If O = "I" Then
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
         Else
            ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
         End If
      End If
      ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L" & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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
   Set w_Chave         = Nothing
   Set w_troca         = Nothing
   Set w_sq_dado_tipo  = Nothing
   Set w_nome          = Nothing 
   Set w_descricao     = Nothing 
   Set w_tipo          = Nothing 
   Set w_ordem         = Nothing  
End Sub
REM =========================================================================
REM Fim da rotina de associação entre storage procedures e SP
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
   Dim p_sq_Endereco_unidade
   Dim p_modulo
   Dim w_Null
   Dim w_mensagem
   Dim FS, F1
   Dim w_chave_nova
   Cabecalho
   ShowHTML "</HEAD>"
   BodyOpen "onLoad=document.focus();"
   Select Case SG
      Case "DCCDSIST"
         ' VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutSistema O, _
            Request("w_chave"), Request("w_chave_aux"), Request("w_nome"), Request("w_sigla"), Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "DCCDUSU"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            
            If O = "V" Then
               Cabecalho
               ShowHTML "<BASE HREF=""" & conRootSIW & """>"
               BodyOpenClean "onLoad=document.focus();"
               ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
               ShowHTML "<HR>"
               Response.Flush
               ' Recupera os dados do usuário informado
               DB_GetUsuario RS, w_cliente, Request("w_chave"), null
               ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/relogio.gif"" align=""center""> <b>Aguarde: dicionarização automática do usuário " & RS("nome") & " do sistema " & RS("sg_sistema") & " em andamento...</b><br><br><br><br><br><br><br><br><br><br></center></div>"
               Rodape
               Response.Flush
               DML_PutDicionario w_cliente, RS("sg_sistema"), RS("nome")
            Else
               DML_PutUsuario O, _
                  Request("w_chave"), Request("w_sq_sistema"), Request("w_nome"), Request("w_descricao")
            End If
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "DCCDARQV"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutArquivo O, _
            Request("w_chave"), Request("w_sq_sistema"), Request("w_nome"), Request("w_descricao"), Request("w_tipo"), Request("w_diretorio")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "DCCDTAB"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutTabela O, _
            Request("w_chave"), Request("w_sq_tabela_tipo"),Request("w_sq_usuario"), Request("w_sq_sistema"),Request("w_nome"),Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "DCCDCOL"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutColuna O, _
            Request("w_chave"), Request("w_sq_tabela"),Request("w_sq_dado_tipo"), Request("w_nome"),Request("w_descricao"),Request("w_ordem"),Request("w_tamanho"),Request("w_precisao"),Request("w_escala"),Request("w_obrigatorio"),Request("w_valor_padrao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCCDPROC"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutProcedure O, _
            Request("w_chave"), Request("w_sq_arquivo"),Request("w_sq_sistema"), Request("w_sq_sp_tipo"),Request("w_nome"),Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCCDTRIG"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutTrigger O, _
            Request("w_chave"), Request("w_sq_tabela"),Request("w_sq_usuario"), Request("w_sq_sistema"),Request("w_nome"),Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCCDSP"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutStoredProcedure O, _
            Request("w_chave"), Request("w_sq_sp_tipo"),Request("w_sq_usuario"), Request("w_sq_sistema"),Request("w_nome"),Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCSPTAB"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutSPTabs O, _
            Request("w_chave"), Request("w_chave_aux")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCSPSP"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            If Request("w_filha") = "S" Then
               DML_PutSPSP O, Request("w_chave"), Request("w_chave_aux")
            Else
               DML_PutSPSP O, Request("w_chave_aux"), Request("w_chave")
            End If
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If      
      Case "DCSPPARAM"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutSPParametro O, Request("w_chave"),Request("w_chave_aux"), Request("w_sq_dado_tipo"), Request("w_nome"), Request("w_descricao"), Request("w_tipo"), Request("w_ordem")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If    
      Case "DCCDREL"
         'VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutRelacionamento O, _
            Request("w_chave"), Request("w_nome"),Request("w_descricao"), Request("w_sq_tabela_pai"),Request("w_sq_tabela_filha"),Request("w_sq_sistema")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If        
      Case "DCCDIND"
         ' VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            DML_PutIndice O, _
            Request("w_chave"), Request("w_sq_indice_tipo"),Request("w_sq_usuario"), Request("w_sq_sistema"),Request("w_nome"),Request("w_descricao")
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
            ScriptClose
         Else
            ScriptOpen "JavaScript"
            ShowHTML "  alert('Assinatura Eletrônica inválida!');"
            ShowHTML "  history.back(1);"
            ScriptClose
         End If
      Case "TRIGEVENTO"
         ' VerIfica se a Assinatura Eletrônica é válida
         If (VerIficaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
            w_assinatura = "" Then
            'Inicialmente, desativa a opção em todos os Endereços
            DML_PutTrigEvento "E", Request("w_chave"), null
            'Em seguida, ativa apenas para os Endereços selecionados
            For w_cont = 1 To Request.Form("w_evento").Count
               If Request("w_evento")(w_cont) > "" Then
                  DML_PutTrigEvento "I", Request("w_chave"), Request("w_evento")(w_cont)
               End If
            Next
            ScriptOpen "JavaScript"
            ShowHTML "  location.href='" & R & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & Request("w_sg") & MontaFiltro("GET") & "';"
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
   Set w_chave_nova          = Nothing
   Set FS                    = Nothing
   Set w_Mensagem            = Nothing
   Set p_sq_Endereco_unidade = Nothing
   Set p_modulo              = Nothing
   Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento
REM =========================================================================


REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
   'VerIfica se o usuário tem lotação e localização
   If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
      ScriptOpen "JavaScript"
      ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
      ShowHTML " top.location.href='Default.asp'; "
      ScriptClose
      Exit Sub
   End If
   Select Case Par
      Case "ARQUIVOS"        Arquivos
      Case "COLUNAS"         Colunas
      Case "PROC"            Procedure
      Case "RELACIONAMENTOS" Relacionamento
      Case "SISTEMA"         Sistema
      Case "SP"              StoredProcedure
      Case "TABELA"          Tabela
      Case "TRIGGER"         Trigger
      Case "USUARIO"         Usuario
      Case "INDICE"          Indice
      Case "EVENTO"          Evento
      Case "SPTABS"          Sptabs
      Case "SPSP"            Spsp
      Case "SPPARAM"         Spparam
      Case "GRAVA"           Grava
   Case Else
      Cabecalho
      ShowHTML "<BASE HREF=""" & conRootSIW & """>"
      BodyOpenClean "onLoad=document.focus();"
      ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
      ShowHTML "<HR>"
      ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gIf"" align=""center""> <b>Esta opção está sEndo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
      Rodape
   End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>