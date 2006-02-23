<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes_Valida.asp" -->
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
<!-- #INCLUDE FILE="DML_SIAFI.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /SIAFI.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Rotinas de importa��o de dados financeiros a partir do SIAFI
REM Mail     : celso@sbpi.com.br
REM Criacao  : 16/03/2005, 17:00
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = I   : Inclus�o
REM                   = A   : Altera��o
REM                   = C   : Cancelamento
REM                   = E   : Exclus�o
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicita��o de envio

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura, w_caminho
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa, w_ano
Dim ul,File
Dim p_responsavel, p_dt_ini, p_dt_fim, p_imp_ini, p_imp_fim
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

' Carrega vari�veis locais com os dados dos par�metros recebidos
Par           = ucase(Request("Par"))
w_Pagina      = "SIAFI.asp?par="
w_Dir         = "mod_is/"
w_dir_volta   = "../"  
w_Disabled    = "ENABLED"

SG               = ucase(Request("SG"))
O                = uCase(Request("O"))
w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)
w_ano             = RetornaAno()

' Configura o caminho para grava��o f�sica de arquivos
w_caminho = conFilePhysical & w_cliente & "\"

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then
   ' Cria o objeto de upload
   Set ul       = Nothing
   Set ul       = Server.CreateObject("Dundas.Upload.2")
   ul.SaveToMemory

   w_troca          = ul.Form("w_troca")
   p_responsavel    = uCase(ul.Form("p_responsavel"))
   p_dt_ini         = ul.Form("p_dt_ini")
   p_dt_fim         = ul.Form("p_dt_fim")
   p_imp_ini        = ul.Form("p_imp_ini")
   p_imp_fim        = ul.Form("p_imp_fim")

   P1               = ul.Form("P1")
   P2               = ul.Form("P2")
   P3               = ul.Form("P3")
   P4               = ul.Form("P4")
   TP               = ul.Form("TP")
   R                = uCase(ul.Form("R"))
   w_Assinatura     = uCase(ul.Form("w_Assinatura"))
Else
   w_troca          = Request("w_troca")
   p_responsavel    = uCase(Request("p_responsavel"))
   p_dt_ini         = Request("p_dt_ini")
   p_dt_fim         = Request("p_dt_fim")
   p_imp_ini        = Request("p_imp_ini")
   p_imp_fim        = Request("p_imp_fim")

   P1               = Nvl(Request("P1"),0)
   P2               = Nvl(Request("P2"),0)
   P3               = cDbl(Nvl(Request("P3"),1))
   P4               = cDbl(Nvl(Request("P4"),conPagesize))
   TP               = Request("TP")
   R                = uCase(Request("R"))
   w_Assinatura     = uCase(Request("w_Assinatura"))
End If

If O = "" Then 
   If par ="REL_PPA" or par = "REL_INICIATIVA" Then
      O = "P"
   Else 
      O = "L"
   End If
End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclus�o"
  Case "A" 
     w_TP = TP & " - Altera��o"
  Case "E" 
     w_TP = TP & " - Exclus�o"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "C"
     w_TP = TP & " - C�pia"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "H" 
     w_TP = TP & " - Heran�a"
  Case "O" 
     w_TP = TP & " - Orienta��es"
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

' Recupera a configura��o do servi�o
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configura��o do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set p_responsavel = Nothing
Set p_dt_ini      = Nothing
Set p_dt_fim      = Nothing
Set p_imp_ini     = Nothing
Set p_imp_fim     = Nothing
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
Set w_caminho     = Nothing

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
REM Rotina de importa��o de arquivos f�sicos para atualiza��o de dados financeiros
REM -------------------------------------------------------------------------
Sub Inicial
  Dim w_data, w_sq_pessoa, w_data_arquivo, w_arquivo_recebido, w_arquivo_registro 
  Dim w_registros, w_importados, w_rejeitados, w_situacao
  
  w_Chave           = Request("w_Chave")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_data                = Request("w_data") 
     w_sq_pessoa           = Request("w_sq_pessoa") 
     w_data_arquivo        = Request("w_data_arquivo") 
     w_arquivo_recebido    = Request("w_arquivo_recebido") 
     w_arquivo_registro    = Request("w_arquivo_registro")
     w_registros           = Request("w_registros") 
     w_importados          = Request("w_importados") 
     w_rejeitados          = Request("w_rejeitados") 
     w_situacao            = Request("w_situacao")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetORImport RS, w_chave, w_cliente, p_responsavel, p_dt_ini, p_dt_fim, p_imp_ini, p_imp_fim
     RS.Sort = "data_arquivo desc"
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataDataHora
     ValidateOpen "Validacao"
     If InStr("I",O) > 0 Then
        Validate "w_data_arquivo", "Data e hora", "DATAHORA", "1", "17", "17", "", "0123456789 /:,"
        Validate "w_arquivo_recebido", "Arquivo de dados", "1", "1", "1", "255", "1", "1"
        Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
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
  ElseIf Instr("I",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_data_arquivo.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "        <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "        <a accesskey=""O"" class=""SS"" href=""" & w_dir & w_Pagina & "Help&R=" & w_Pagina & par & "&O=O&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""help""><u>O</u>rienta��es</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Data</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Executado em</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Respons�vel</font></td>"
    ShowHTML "          <td colspan=3><font size=""1""><b>Registros</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Total</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aceitos</font></td>"
    ShowHTML "          <td><font size=""1""><b>Rejeitados</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("data_arquivo")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("data")) & "</td>"
        ShowHTML "        <td title=""" & RS("nm_resp") & """><font size=""1"">" & RS("nm_resumido_resp") & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & RS("registros") & "&nbsp;</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & RS("importados") & "&nbsp;</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & RS("rejeitados") & "&nbsp;</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          " & LinkArquivo("HL", w_cliente, RS("chave_recebido"), "_blank", "Exibe os dados do arquivo importado.", "Arquivo", null) & "&nbsp"
        ShowHTML "          " & LinkArquivo("HL", w_cliente, RS("chave_result"), "_blank", "Exibe o registro da importa��o.", "Registro", null) & "&nbsp"
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
  ElseIf Instr("IV",O) > 0 Then
    If InStr("EV",O) Then w_Disabled = " DISABLED " End If
    ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG="&SG&"&O="&O&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"

    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If O = "I" or O = "A" Then
       DB_GetCustomerData RS, w_cliente
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"
    End If
    
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>ata/hora extra��o:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_data_arquivo"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_data_arquivo & """  onKeyDown=""FormataDataHora(this, event);"" title=""OBRIGAT�RIO. Informe a data e hora da extra��o do aquivo. Digite apenas n�meros. O sistema colocar� os separadores automaticamente.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_arquivo_recebido"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo (sua extens�o deve ser .TXT). Ele ser� transferido automaticamente para o servidor."">"
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center""><hr>"
    If O = "E" Then
       ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
    End If
    ShowHTML "          <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_data                = Nothing 
  Set w_sq_pessoa           = Nothing 
  Set w_data_arquivo        = Nothing 
  Set w_arquivo_recebido    = Nothing 
  Set w_arquivo_registro    = Nothing
  Set w_registros           = Nothing 
  Set w_importados          = Nothing 
  Set w_rejeitados          = Nothing 
  Set w_situacao            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Exibe orienta��es sobre o processo de importa��o
REM -------------------------------------------------------------------------
Sub Help
  Cabecalho
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""90%"">"
  ShowHTML "<tr valign=""top"">"
  ShowHTML "  <td><font size=2>"
  ShowHTML "    <p align=""justify"">Esta tela tem o objetivo de atualizar os dados or�ament�rios e financeiros"
  ShowHTML "        da tabela de programas e a��es do PPA, atrav�s da importa��o de arquivo extra�do do SIAFI."
  ShowHTML "    <p align=""justify"">A atualiza��o est� restrita aos dados sobre dota��o autorizada, total empenhado e total liquidado."
  ShowHTML "    <p align=""justify"">Para ser executada corretamente, a importa��o deve cumprir os passos abaixo."
  ShowHTML "    <ol>"
  ShowHTML "    <p align=""justify""><b>FASE 1 - Prepara��o do arquivo a ser importado:</b><br></p>"
  ShowHTML "      <li>Use o m�dulo extrator do SIAFI para obter uma planilha Excel (extens�o XLS), <u>exatamente igual</u> � exibida neste"
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_exemplo.xls", "ExemploSIAFI", "Exibe o registro da importa��o.", "exemplo", null) & ";"  
  ShowHTML "      <li>Abra a planilha gerada no passo anterior com o Excel e use a op��o ""Arquivo -> Salvar como"". Escolha o nome que desejar"
  ShowHTML "          para o arquivo e, na lista ""Salvar como tipo"", escolha a op��o ""<b>CSV (Separado por v�rgulas) (*.csv)</b>""; "
  ShowHTML "      <li> Feche o "
  ShowHTML "          Excel e renomeie a extens�o do arquivo, de CSV para TXT. Ap�s cumprir este passo, voc� dever� ter um arquivo com extens�o TXT, como o deste "
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_exemplo.TXT", "ExemploSIAFI", "Exibe o registro da importa��o.", "exemplo", null) & ";"
  ShowHTML "    <p align=""justify""><b>FASE 2 - Importa��o do arquivo e atualiza��o dos dados:</b><br></p>"
  ShowHTML "      <li>Na tela anterior, clique sobre a opera��o ""Incluir"";"
  ShowHTML "      <li>Quando a tela de inclus�o for apresentada, preencha o formul�rio seguindo as instru��es dispon�veis em cada campo "
  ShowHTML "          (passe o mouse sobre o campo desejado para o sistema exibir a instru��o de preenchimento);"
  ShowHTML "      <li>Aguarde o t�rmino da importa��o e atualiza��o dos dados. O sistema ir�, numa �nica execu��o, transferir o arquivo "
  ShowHTML "          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados est�o corretos e, em caso positivo, "
  ShowHTML "          atualizar os campos. Este processamento pode demorar alguns minutos. N�o clique em nenhum bot�o at� o sistema voltar para "
  ShowHTML "          para a listagem das importa��es j� executadas;"
  ShowHTML "    <p align=""justify""><b>FASE 3 - Verifica��o do arquivo de registro:</b><br></p>"
  ShowHTML "      <li>Verifique se ocorreu erro na importa��o de alguma linha do arquivo de origem. Na lista de importa��es, existem tr�s colunas: "
  ShowHTML "          ""Registros"" indica o n�mero total de linhas do arquivo, ""Importados"" indica o n�mero de linhas que atendeu �s condi��es de importa��o "
  ShowHTML "          e que geraram atualiza��o nos dados existentes, ""Rejeitados"" indica o n�mero de linhas que foram descartadas pela valida��o; "
  ShowHTML "      <li>Verifique cada linha descartada pela rotina de importa��o. Clique sobre a opera��o ""Registro"" na coluna ""Opera��es"" e verifique "
  ShowHTML "          os erros detectados em cada uma das linhas descartadas. O conte�do do arquivo � similar ao deste "
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_registro.TXT", "ExemploSIAFI", "Exibe o registro da importa��o.", "exemplo", null) & ";"
  ShowHTML "      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e fa�a uma nova importa��o."
  ShowHTML "    </ol>"
  ShowHTML "    <p align=""justify""><b>Observa��es:</b><br></p>"
  ShowHTML "    <ul>"
  ShowHTML "      <li>Para restringir a importa��o �s linhas que realmente s�o �teis, abra o arquivo obtido no passo (3) com o Bloco de Notas (Notepad) "
  ShowHTML "          e remova as linhas que n�o disserem respeito aos programas e a��es do PPA, n�o esquecendo de salv�-lo;"
  ShowHTML "      <li>Uma vez conclu�da uma importa��o, n�o h� necessidade de voc� manter em seu computador/disquete o arquivo utilizado. O sistema "
  ShowHTML "          grava no servidor uma c�pia do arquivo usado pela importa��o e uma c�pia do arquivo de registro;"
  ShowHTML "      <li>Toda importa��o registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; "
  ShowHTML "      <li>N�o h� como cancelar uma importa��o, nem de reverter os valores existentes antes da sua execu��o. Assim, certifique-se de que o "
  ShowHTML "          arquivo de origem est� correto e que a importa��o deve realmente ser executada."
  ShowHTML "    </ul>"
  ShowHTML "    <p align=""justify""><b>Verifica��es dos dados:</b><br></p>"
  ShowHTML "    <ul>"
  ShowHTML "      <p align=""justify"">Uma linha do arquivo origem s� gera atualiza��o da tabela de programas e a��es do PPA se atender aos seguintes crit�rios:</p>"
  ShowHTML "      <li>O c�digo do programa deve estar na segunda posi��o da linha e deve conter 4 posi��es n�mericas;"
  ShowHTML "      <li>A c�digo da a��o deve estar na quarta posi��o da linha e deve conter entre 4 e 5 posi��es, sendo que as quatro primeiras s�o n�meros;"
  ShowHTML "           e a quinta posi��o deve ser uma letra mai�scula "
  ShowHTML "      <li>A dota��o autorizada deve estar na sexta posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);"
  ShowHTML "      <li>O total empenhado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);"
  ShowHTML "      <li>O total liquidado deve estar na s�tima posi��o da linha e deve estar na nota��o brasileira de valor (separador de milhar = ponto e de decimal = v�rgula);"
  ShowHTML "      <li>O sistema s� atualizar� a tabela se encontrar um, e apenas um registro com o mesmo c�digo de a��o e programa;"
  ShowHTML "      <li>Cada posi��o da linha � separada pelo caracter ponto-e-v�rgula;"
  ShowHTML "      <li>Os valores de cada posi��o <u>n�o</u> devem estar entre aspas simples nem duplas. Ex: <b>;1606;...</b> � v�lido, mas <b>;""1606"";...</b> e <b>;'1606';...</b> s�o inv�lidos; "
  ShowHTML "      <p align=""justify"">Qualquer situa��o diferente das relacionadas acima causar� a rejei��o da linha.</p>"
  ShowHTML "    <ul>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as opera��es de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  CONST ForReading = 1, ForWriting = 2, ForAppend = 8
  CONST TristateUsedefault = -2 'Abre o arquivo usando o sistema default
  CONST TristateTrue = -1 'Abre o arquivo como Unicode
  CONST TristateFalse = 0 'Abre o arquivo como ASCII

  Dim w_Null, w_mensagem, FS, F1, F2, w_linha, w_chave_nova
  Dim w_caminho_recebido, w_tamanho_recebido, w_tipo_recebido
  Dim w_arquivo_registro, w_caminho_registro, w_tamanho_registro, w_tipo_registro
  Dim w_registros, w_importados, w_rejeitados, w_situacao, w_erro, w_result
  
  Dim w_unidade, w_programa, w_acao, w_dotacao, w_empenhado, w_liquidado

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "ISIMPSIAFI"
       ' Verifica se a Assinatura Eletr�nica � v�lida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          ' Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB.
          If ul.Files("w_arquivo_recebido").Size > ul.Form("w_upload_maximo") Then
             ScriptOpen("JavaScript")
             ShowHTML "  alert('Aten��o: o tamanho m�ximo do arquivo n�o pode exceder " & ul.Form("w_upload_maximo")/1024 & " KBytes!');"
             ShowHTML "  history.back(1);"
             ScriptClose
             Response.End()
             exit sub
          End If
          
          ' Configura o nome dos arquivo recebido e do arquivo registro
          w_caminho_recebido = ul.GetUniqueName()
          w_caminho_registro = ul.GetUniqueName()
          
          ul.Files("w_arquivo_recebido").SaveAs(w_caminho & w_caminho_recebido)
          
          ' Gera o arquivo registro da importa��o
          Set FS = CreateObject("Scripting.FileSystemObject")
          Set F1 = FS.CreateTextFile(w_caminho & w_caminho_registro)
          
          'Abre o arquivo recebido para gerar o arquivo registro
          Set F2 = FS.OpenTextFile(w_caminho & w_caminho_recebido)
          
          ' Varre o arquivo recebido, linha a linha
          w_registros  = 0
          w_importados = 0
          w_rejeitados = 0
          w_cont       = 0
          Do While Not F2.AtEndOfStream 
             w_linha = F2.ReadLine
             w_cont  = w_cont + 1
             F1.WriteLine "[Linha " & w_cont & "] " & w_linha
             w_unidade  = Nvl(trim(Piece(w_linha,"",";",1)),w_unidade)
             w_programa = Nvl(trim(Piece(w_linha,"",";",3)),w_programa)
             w_acao     = trim(Piece(w_linha,"",";",5))
             w_dotacao  = trim(Piece(w_linha,"",";",8))
             w_empenhado= trim(Piece(w_linha,"",";",9))
             w_liquidado= trim(Piece(w_linha,"",";",10))

             w_erro = 0
             
             ' Valida o campo Unidade
             w_result = fValidate(1, w_unidade, "Unidade", "", 1, 5, 5, "", "0123456789")
             If w_result > "" Then F1.WriteLine "=== Erro campo Unidade: " & w_result : w_erro = 1 End If
             
             ' Valida o campo Programa
             w_result = fValidate(1, w_programa, "Programa", "", 1, 4, 4, "", "0123456789")
             If w_result > "" Then F1.WriteLine "=== Erro campo Programa: " & w_result : w_erro = 1 End If

             ' Valida o campo A��o
             w_result = fValidate(1, w_acao, "A��o", "", 1, 4, 8, "", "0123456789ABCDEFGHIJKLMNOPQRSTUWVXYZ")
             If w_result > "" Then F1.WriteLine "=== Erro campo A��o: " & w_result : w_erro = 1 End If

             ' Valida o campo Dota��o
             w_result = fValidate(1, w_dotacao, "Dota��o Autorizada", "VALOR", 1, 3, 18, "", "0123456789,.")
             If w_result > "" Then F1.WriteLine "=== Erro campo Dota��o Autorizada: " & w_result : w_erro = 1 End If

             ' Valida o campo Empenhado
             w_result = fValidate(1, w_empenhado, "Total Empenhado", "VALOR", 1, 3, 18, "", "0123456789,.")
             If w_result > "" Then F1.WriteLine "=== Erro campo Total Empenhado: " & w_result : w_erro = 1 End If

             ' Valida o campo Liquidado
             w_result = fValidate(1, w_liquidado, "Total Liquidado", "VALOR", 1, 3, 18, "", "0123456789,.")
             If w_result > "" Then F1.WriteLine "=== Erro campo Total Liquidado: " & w_result : w_erro = 1 End If
             
             If w_erro = 0 Then
                ' Verifica se o programa/a��o existe para o cliente
                DB_GetAcaoPPA_IS RS, w_cliente, w_ano, w_programa, Mid(w_acao,1,4), Mid(w_acao,5,4), w_unidade, null, null, null
                If RS.EOF Then 
                   F1.WriteLine "=== A��o n�o encontrada"
                   w_erro = 1
                Else
                   ' Se existir, atualiza os dados financeiros
                   DML_PutDadosAcaoPPA_IS w_cliente, w_ano, w_unidade, _
                       w_programa, Mid(w_acao,1,4), Mid(w_acao,5,4), w_dotacao, _
                       w_empenhado, w_liquidado
                End If
             End If
             
             w_registros = w_registros + 1
             If w_erro = 0 Then
                w_importados = w_importados + 1
             Else
                w_rejeitados = w_rejeitados + 1
             End If
          Loop
          F2.Close
          F1.Close
          
          ' Configura o valor dos campos necess�rios para grava��o
          If w_rejeitados = 0 Then w_situacao = 0 Else w_situacao = 1 End If
          
          w_tamanho_recebido = ul.Files("w_arquivo_recebido").Size
          w_tipo_recebido    = ul.Files("w_arquivo_recebido").ContentType
          w_arquivo_registro = "Arquivo registro"
          Set F1 = FS.GetFile(w_caminho & w_caminho_registro)
          w_tamanho_registro = F1.size
          w_tipo_registro    = w_tipo_recebido
          
          ' Grava o resultado da importa��o no banco de dados
          DML_PutOrImport O, _
              ul.Form("w_chave"), w_cliente,   w_usuario,          ul.Form("w_data_arquivo"), _
              ul.Files("w_arquivo_recebido").OriginalPath, _
              w_caminho_recebido, w_tamanho_recebido,  w_tipo_recebido, _
              w_arquivo_registro,              w_caminho_registro, w_tamanho_registro, w_tipo_registro, _
              w_registros,                     w_importados,       w_rejeitados,       w_situacao, _
              ExtractFileName(ul.Files("w_arquivo_recebido").OriginalPath), w_arquivo_registro
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & ul.Form("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("UL") & "';"
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

  Set w_result              = Nothing 
  Set w_erro                = Nothing 
  Set w_caminho_recebido    = Nothing 
  Set w_tamanho_recebido    = Nothing 
  Set w_tipo_recebido       = Nothing
  Set w_arquivo_registro    = Nothing 
  Set w_caminho_registro    = Nothing 
  Set w_tamanho_registro    = Nothing 
  Set w_tipo_registro       = Nothing
  Set w_registros           = Nothing
  Set w_importados          = Nothing
  Set w_rejeitados          = Nothing
  Set w_situacao            = Nothing
  Set w_chave_nova          = Nothing
  Set F1                    = Nothing
  Set F2                    = Nothing
  Set w_linha               = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
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
    Case "INICIAL"
       Inicial
    Case "HELP"
       Help
    Case "GRAVA"
       Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>