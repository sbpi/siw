<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /menu.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Monta a estrutura de frames e o menu da aplicação
REM Mail     : alex@sbpi.com.br
REM Criacao  : 12/07/2001 17:15PM
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
  ScriptOpen "JavaScript"
  ShowHTML " alert('Você precisa autenticar-se para utilizar o sistema!'); "
  ShowHTML " top.location.href='Default.asp'; "
  ScriptClose
End If

' Declaração de variáveis
Dim OraDatabase, RS, SQL, Par, dbms, sp, p_Logon, p_dbms, p_cliente, p_portal, w_cliente
Dim P1, P2, P3, P4, TP, SG, R, O, w_TP, w_Pagina
Dim w_ContOut
Dim SQL1, RS1, SQL2, RS2, SQL3, RS3
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao

p_LogOn    = Request("p_LogOn")
p_dbms     = Request("p_dbms")
p_cliente  = Request("p_cliente")
p_portal   = Request("p_portal")

w_cliente  = p_cliente

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par        = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Pagina     = "Menu.asp?par="
w_ImagemPadrao = "images/folder/SheetLittle.gif"

If O = "" and par = "TROCASENHA" Then O = "A" End If

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
     w_TP = TP
End Select
Main

FechaSessao

Set p_Logon     = Nothing
Set p_Cliente   = Nothing
Set p_dbms      = Nothing
Set p_portal    = Nothing
Set w_cliente   = Nothing
Set RS          = Nothing
Set SQL         = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set w_TP        = Nothing
Set SG          = Nothing
Set R           = Nothing
Set O           = Nothing
Set w_Pagina    = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set RS1             = Nothing
Set SQL1            = Nothing
Set RS2             = Nothing
Set SQL2            = Nothing
Set RS3             = Nothing
Set SQL3            = Nothing

REM =========================================================================
REM Rotina de montagem da estrutura de frames
REM -------------------------------------------------------------------------
Sub Frames
  ShowHTML "<HTML>"
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>PORTAL DE CURRÍCULOS DO MS :: </TITLE>"
  ShowHTML "<META HTTP-EQUIV=""Content-Type"" CONTENT=""text/html; charset=iso-8859-1"">"
  ShowHTML "<SCRIPT language=javascript src=""javas/hoje.js""></SCRIPT>"
  ShowHTML "<style>"
  ShowHTML "	a:link{color:""#FFFFFF"";text-decoration:none}"
  ShowHTML "	a:visited{color:""#FFFFFF"";text-decoration:none}"
  ShowHTML "	a:hover{color:""#25687E"";text-decoration:none}"
  ShowHTML "</style>"
  ShowHTML "<base target=""principal"">"
  ShowHTML "</HEAD>"
  ShowHTML "<BODY BGCOLOR=#FFFFFF leftmargin=""0"" topmargin=""5"" link=""#FFFFFF"" vlink=""#FFFFFF"" alink=""#FFFFFF"">"
  ShowHTML ""
  ShowHTML "  <div align=""center"">"
  ShowHTML "    <center>"
  ShowHTML "    <table border=""0"" width=""98%"" cellspacing=""0"" cellpadding=""0"" height=""100%"">"
  ShowHTML "      <tr>"
  ShowHTML "        <td width=""100%"" bgcolor=""#FFCC00"" height=""21""><img border=""0"" src=""images/barra_governo_esq_nova.gif"" align=""left"" vspace=""2"" width=""430"" height=""21""><img border=""0"" src=""images/barra_governo_direita_nova.gif"" align=""right"" hspace=""19"" vspace=""2"" width=""74"" height=""21""></td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr>"
  ShowHTML "        <td width=""100%"" height=""10""></td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr>"
  ShowHTML "        <td width=""100%"" height=""62"">"
  ShowHTML "          <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
  ShowHTML "            <tr>"
  ShowHTML "              <td width=""187"" height=""62""><img border=""0"" src=""images/topo_foto.jpg"" width=""187"" height=""62""></td>"
  ShowHTML "              <td width=""1"" height=""62""><img border=""0"" src=""images/topo_bg.jpg"" width=""1"" height=""62""></td>"
  ShowHTML "            </center>"
  ShowHTML "              <td width=""100%"" background=""images/topo_bg.jpg"" height=""62"">"
  ShowHTML "                <p align=""left""><b><font face=""Verdana"" color=""#FFFFFF"" size=""4"">Portal de currículos do MS</font></b></p>"
  ShowHTML "            </td>"
  ShowHTML "            <td width=""14"" height=""62"">"
  ShowHTML "              <p align=""right""><img border=""0"" src=""images/topo_borda.jpg"" width=""14"" height=""62""></td>"
  ShowHTML "          </tr>"
  ShowHTML "        </table>"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "    <center>"
  ShowHTML "    <tr>"
  ShowHTML "      <td width=""100%"" height=""20"">"
  ShowHTML "        <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" background=""images/borda_fora.jpg"" height=""20"">&nbsp;</td>"
  ShowHTML "            <td height=""20"">"
  ShowHTML "              <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" bgcolor=""#FFFFFF"" height=""20"">"
  ShowHTML "                <tr>"
  ShowHTML "                  <td height=""22"" width=""140"" bgcolor=""#FFFFFF"">&nbsp;</td>"
  ShowHTML "                  <td height=""22"" align=""center"">"
  ShowHTML "                    <div align=""center"">"
  ShowHTML "                      <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
  ShowHTML "                        <tr>"
  ShowHTML "                          <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
  ShowHTML "                          <td bgcolor=""#3591B3"">"
  ShowHTML "                            <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF"">"
  ShowHTML "                            [<a href=""ajuda.htm"" title=""Apresenta a tela de ajuda."">Ajuda</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=Identificacao&sg=cvident&p_portal=" & p_portal & "&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & "&O=A&P1=&P2=&P3=&P4="" title=""formulário onde você pode atualizar dados gerais de identificação."">Identificação</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/Cliente.asp?par=Endereco&p_portal=" & p_portal & "&P1=1&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Endereços&SG=CLENDER&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá atualizar seus endereços físicos e virtuais"">Endereços</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/Cliente.asp?par=Telefone&p_portal=" & p_portal & "&P1=1&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Telefones&SG=CLFONE&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá atualizar seus telefones de contato"">Telefones</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=Escolaridade&p_portal=" & p_portal & "&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Escolaridade&SG=CVESCOLA&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá registrar e manter sua formação acadêmica formal"">Escolaridade</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=Cursos&p_portal=" & p_portal & "&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Cursos técnicos&SG=CVCURSO&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá registrar e manter dados dos cursos técnicos que já concluiu ou esteja cursando"">Cursos</a>]&nbsp;"
  ShowHTML "                            <br>"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=Idiomas&p_portal=" & p_portal & "&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Idiomas&SG=CVIDIOMA&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá registrar e manter dados dos idiomas que domina ou tem alguma fluência"">Idiomas</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=ExpProf&p_portal=" & p_portal & "&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Experiência profissional&SG=CVEXPPER&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá registrar e manter dados da sua experiência profissional"">Exp. profissional</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=Producao&p_portal=" & p_portal & "&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Produção técnica&SG=CVTECNICA&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""nesta tela você poderá registrar e manter dados da sua produção técnico científica, caso tenha alguma"">Produção técnica</a>]&nbsp;"
  ShowHTML "                            [<a href=""/siw/mod_rh_pub/cv.asp?par=visualizar&P1=&P2=&P3=&P4=&TP=<img src=images/folder/SheetLittle.gif BORDER=0>Visualizar&SG=CVVISUAL&p_LogOn=Sim&p_dbms=" & p_dbms & "&p_cliente=" & p_cliente & "&w_usuario=" & Session("sq_pessoa") & """ title=""este link apresenta os dados que você informou"">Visualizar</a>]&nbsp;"
  ShowHTML "                            [<a href=""Menu.asp?par=Sair"" TARGET=""_top"" title=""este link permite a saída segura deste ambiente, voltando à tela do portal de recrutamento de colaboradores da UNESCO"" onClick=""return(confirm('Confirma saída deste ambiente e retorno ao portal?'));"">Sair</a>]"
  ShowHTML "                          <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
  ShowHTML "                        </tr>"
  ShowHTML "                      </table>"
  ShowHTML "                    </div>"
  ShowHTML "                  </td>"
  ShowHTML "                </tr>"
  ShowHTML "              </table>"
  ShowHTML "            </td>"
  ShowHTML "            <td width=""19"" background=""images/borda_fora_d.jpg"" height=""22""><img border=""0"" src=""images/borda_fora_d.jpg"" width=""19"" height=""1""></td>"
  ShowHTML "          </tr>"
  ShowHTML "        </table>"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "    <tr>"
  ShowHTML "      <td width=""100%"" height=""5"">"
  ShowHTML "        <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""5"">"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" background=""images/borda_fora.jpg"" height=""5"">&nbsp;</td>"
  ShowHTML "            <td height=""5"" bgcolor=""#FFFFFF"">"
  ShowHTML "              &nbsp;"
  ShowHTML "            </td>"
  ShowHTML "            <td width=""19"" background=""images/borda_fora_d.jpg"" height=""5""><img border=""0"" src=""images/borda_fora_d.jpg"" width=""19"" height=""1""></td>"
  ShowHTML "          </tr>"
  ShowHTML "        </table>"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "    <tr>"
  ShowHTML "      <td width=""100%"" height=""22"">"
  ShowHTML "        <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""22"">"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" height=""22"" background=""images/borda_fora.jpg"">&nbsp;</td>"
  ShowHTML "            <td height=""22"">"
  ShowHTML "              <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
  ShowHTML "                <tr>"
  ShowHTML "                  <td width=""40""></td>"
  ShowHTML "                  <td>"
  ShowHTML "                    <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
  ShowHTML "                      <tr>"
  ShowHTML "                        <td width=""8""><img border=""0"" src=""images/borda_data_e.jpg"" width=""8"" height=""22""></td>"
  ShowHTML "                        <td bgcolor=""#3591B3"">"
  ShowHTML "              <p align=""center""><font size=""1"" face=""Verdana"" color=""#ffffff"">"
  ShowHTML "		<p align=""center""><SCRIPT>DiaDeHoje()</SCRIPT></p>"
  ShowHTML "                        </td>"
  ShowHTML "                        <td width=""8""><img border=""0"" src=""images/borda_data_d.jpg"" width=""8"" height=""22""></td>"
  ShowHTML "                      </tr>"
  ShowHTML "                    </table>"
  ShowHTML "                  </td>"
  ShowHTML "                  <td width=""450""></td>"
  ShowHTML "                </tr>"
  ShowHTML "              </table>"
  ShowHTML "            </td>"
  ShowHTML "            <td background=""images/borda_fora_d.jpg"" width=""19"" height=""22"">&nbsp;</td>"
  ShowHTML "          </tr>"
  ShowHTML "        </table>"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "    <tr>"
  ShowHTML "      <td width=""100%"">"
  ShowHTML "        <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""100%"">"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" height=""15"" background=""images/borda_fora.jpg""><img border=""0"" src=""images/15h.gif"" width=""1"" height=""15""></td>"
  ShowHTML "            <td width=""18"" height=""15"" valign=""top""><img border=""0"" src=""images/dentro_canto_sup_e.jpg"" width=""18"" height=""15""></td>"
  ShowHTML "            <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
  ShowHTML "            <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
  ShowHTML "            <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
  ShowHTML "            <td height=""15"" width=""18"" valign=""top""><img border=""0"" src=""images/dentro_canto_sup_d.jpg"" width=""18"" height=""15""></td>"
  ShowHTML "            <td width=""19"" height=""15"" background=""images/borda_fora_d.jpg""><img border=""0"" src=""images/15h.gif"" width=""1"" height=""15""></td>"
  ShowHTML "          </tr>"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" background=""images/borda_fora.jpg"" height=""100%"">&nbsp;</td>"
  ShowHTML "            <td width=""18"" background=""images/dentro_borda_e.jpg"" height=""100%"">&nbsp;</td>"
  ShowHTML "            <td colspan=""3"" height=""100%""><div style=""OVERFLOW: auto; Z-INDEX: -300""> "
  ShowHTML "       <p align=""center""> "
  ShowHTML "       <iframe src=""ajuda.htm"" width=""100%"" height=""100%"" border=""0"" leftMargin=""0"" topMargin=""0"" marginheight=""0"" marginwidth=""0"" frameborder=""0"" framespacing=""0"" name=""principal"" noresize oncontextmenu=""return false"" allowtransparency> "
  ShowHTML "       </p></iframe></td>"
  ShowHTML "            <td width=""18"" background=""images/dentro_borda_d.jpg"" height=""100%"">&nbsp;</td>"
  ShowHTML "            <td width=""19"" background=""images/borda_fora_d.jpg"" height=""100%"">&nbsp;</td>"
  ShowHTML "          </tr>"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" background=""images/borda_fora.jpg"" height=""17"">&nbsp;</td>"
  ShowHTML "            <td width=""18"" valign=""top"" height=""17""><img border=""0"" src=""images/dentro_canto_inf_e.jpg"" width=""18"" height=""17""></td>"
  ShowHTML "            <td colspan=""3"" background=""images/dentro_fundo.jpg"" height=""17"">&nbsp;</td>"
  ShowHTML "            <td width=""18"" valign=""top"" height=""17""><img border=""0"" src=""images/dentro_canto_inf_d.jpg"" width=""18"" height=""17""></td>"
  ShowHTML "            <td width=""19"" background=""images/borda_fora_d.jpg"" height=""17"">&nbsp;</td>"
  ShowHTML "          </tr>"
  ShowHTML "          <tr>"
  ShowHTML "            <td width=""21"" height=""23""><img border=""0"" src=""images/canto_inf_e.jpg"" width=""21"" height=""23""></td>"
  ShowHTML "            <td width=""18"" background=""images/borda_fora_fundo.jpg"" height=""23"">&nbsp;</td>"
  ShowHTML "            <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
  ShowHTML "            <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
  ShowHTML "            <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
  ShowHTML "            <td width=""18"" background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
  ShowHTML "            <td width=""19"" height=""23""><img border=""0"" src=""images/canto_inf_d.jpg"" width=""19"" height=""23""></td>"
  ShowHTML "          </tr>"
  ShowHTML "        </table>"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </center>"
  ShowHTML "  </div>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"
End Sub
REM =========================================================================
REM Fim da rotina de montagem da estrutura de frames
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de troca de senha ou assinatura eletrônica
REM -------------------------------------------------------------------------
Sub TrocaSenha
  Dim w_texto
  Dim w_minimo
  Dim w_maximo
  Dim w_vigencia
  Dim w_aviso
  
  SQL = "select * from sg_parametros_seguranca"
  ConectaBD
  w_minimo   = RS("tamanho_minimo_senha")
  w_maximo   = RS("tamanho_maximo_senha")
  w_vigencia = RS("dias_vigencia_senha")
  w_aviso    = RS("dias_aviso_expiracao")
  DesconectaBD
  
  If P1 = 1 Then  w_texto = "Senha de Acesso" Else w_texto = "Assinatura Eletrônica" End If
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  
  Validate "w_atual", w_texto & " atual", "1", "1", w_minimo, w_maximo, "1", "1"
  Validate "w_nova", "Nova " & w_texto, "1", "1", w_minimo, w_maximo, "1", "1"
  Validate "w_conf", "Confirmação da " & w_texto & " atual", "1", "1", w_minimo, w_maximo, "1", "1"
  ShowHTML "  if (theForm.w_atual.value == theForm.w_nova.value) { "
  ShowHTML "     alert('A nova " & w_texto & " deve ser diferente da atual!');"
  ShowHTML "     theForm.w_nova.value='';"
  ShowHTML "     theForm.w_conf.value='';"
  ShowHTML "     theForm.w_nova.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  if (theForm.w_nova.value != theForm.w_conf.value) { "
  ShowHTML "     alert('Favor informar dois valores iguais para a nova " & w_texto & "!');"
  ShowHTML "     theForm.w_nova.value='';"
  ShowHTML "     theForm.w_conf.value='';"
  ShowHTML "     theForm.w_nova.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  var checkStr = theForm.w_nova.value;"
  ShowHTML "  var temLetra = false;"
  ShowHTML "  var temNumero = false;"
  ShowHTML "  var checkOK = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';"
  ShowHTML "  for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "  {"
  ShowHTML "    ch = checkStr.charAt(i);"
  ShowHTML "    for (j = 0;  j < checkOK.length;  j++)"
  ShowHTML "      if (ch == checkOK.charAt(j)) temLetra = true;"
  ShowHTML "  }"
  ShowHTML "  var checkOK = '0123456789';"
  ShowHTML "  for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "  {"
  ShowHTML "    ch = checkStr.charAt(i);"
  ShowHTML "    for (j = 0;  j < checkOK.length;  j++)"
  ShowHTML "      if (ch == checkOK.charAt(j)) temNumero = true;"
  ShowHTML "  }"
  ShowHTML "  if (!(temLetra && temNumero))"
  ShowHTML "  {"
  ShowHTML "    alert('A nova " & w_texto & " deve conter letras e números.');"
  ShowHTML "    theForm.w_nova.value='';"
  ShowHTML "    theForm.w_conf.value='';"
  ShowHTML "    theForm.w_nova.focus();"
  ShowHTML "    return (false);"
  ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpen "onLoad='document.Form.w_atual.focus();'"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<FORM action=""" & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
  ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
  ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""80%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""2"" color=""#FF0000""><p align=""justify""><b>ATENÇÃO: Sua senha e assinatura eletrônica no SIW é a mesma do SICOF-WEB. Se você alterá-la em um dos sistemas, o outro a assume imediatamente.</b></p><font></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1"">Usuário:<br><b>" & Session("NOME") & " (" & Session("USERNAME") & ")</b></td>"
  If P1 = 1 Then ' Se for troca de senha de acesso
     SQL = "select to_char(dt_ultima_troca_senha,'dd/mm/yyyy, hh24:mi:ss') dt_ultima, " & _
           "       to_char(dt_ultima_troca_senha + " & w_vigencia & ",'dd/mm/yyyy, hh24:mi:ss') dt_proxima, " & _
           "       to_char(dt_ultima_troca_senha + " & w_vigencia - w_aviso & ",'dd/mm/yyyy, hh24:mi:ss') dt_aviso " & _
           "from corporativo.z_grupousuarios " & _
           "where pessoa=" & Session("SQ_PESSOA")
  ElseIf P1 = 2 Then ' Se for troca de assinatura eletrônica
     SQL = "select to_char(dt_ultima_troca_senha,'dd/mm/yyyy, hh24:mi:ss') dt_ultima, " & _
           "       to_char(dt_ultima_troca_senha + " & w_vigencia & ",'dd/mm/yyyy, hh24:mi:ss') dt_proxima, " & _
           "       to_char(dt_ultima_troca_senha + " & w_vigencia - w_aviso & ",'dd/mm/yyyy, hh24:mi:ss') dt_aviso " & _
           "from corporativo.un_certificacoes a " & _
           "where pessoa=" & Session("SQ_PESSOA")
  End If
  ConectaBD
  ShowHTML "      <tr><td valign=""top""><font size=""1"">Ultima troca de " & w_texto & ":<br><b>" & RS("dt_ultima") & "</b></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1"">Expiração da " & w_texto & " atual ocorrerá em:<br><b>" & RS("dt_proxima") & "</b></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1"">Você será convidado a trocar sua " & w_texto & " a partir de:<br><b>" & RS("dt_aviso") & "</b></td>"
  DesconectaBD
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>" & w_texto & " <U>a</U>tual:<br><INPUT ACCESSKEY=""A"" class=""BTM"" type=""password"" name=""w_atual"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ova " & w_texto & ":<br><INPUT ACCESSKEY=""N"" class=""BTM"" type=""password"" name=""w_nova"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>R</U>edigite nova " & w_texto & ":<br><INPUT ACCESSKEY=""R"" class=""BTM"" type=""password"" name=""w_conf"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""BTM"" type=""submit"" name=""Botao"" value=""Grava nova " & w_texto & """>"
  ShowHTML "            <input class=""BTM"" type=""reset"" name=""Botao"" value=""Limpar campos"" onClick='document.Form.w_atual.focus();'>"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_texto   = Nothing
  Set w_minimo  = Nothing
  Set w_maximo  = Nothing
  Set w_vigencia= Nothing
  Set w_aviso   = Nothing
End Sub
REM =========================================================================
REM Fim de troca de senha ou assinatura eletrônica
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao
  If P1 = 1 and P2 = "1" Then ' Se for atualização de dados do usuário
    If VerificaAssinaturaEletronica(Request("w_username"),uCase(Request("w_assinatura"))) Then
       SQL = "update corporativo.gn_pessoas " & _
             "set nome                     = '" & Request("w_nome") & "', " & _
             "    email                    = '" & Request("w_email") & "', " & _
             "    sexo                     = '" & Request("w_sexo") & "', " & _
             "    nascimento               = to_date('" & Request("w_nascimento") & "','dd/mm/yyyy'), " & _
             "    identidade               = '" & Request("w_identidade") & "', " & _
             "    orgaoemissor             = '" & Request("w_orgaoemissor") & "', " & _
             "    passaporte               = '" & Request("w_passaporte") & "', " & _
             "    logradouro               = '" & Request("w_logradouro") & "', " & _
             "    complemento              = '" & Request("w_complemento") & "', " & _
             "    bairro                   = '" & Request("w_bairro") & "', " & _
             "    pais                     = " & Request("w_pais") & ", " & _
             "    estado                   = " & Request("w_estado") & ", " & _
             "    municipio                = " & Request("w_municipio") & ", " & _
             "    cep                      = '" & Request("w_cep") & "', " & _
             "    paisnascimento           = " & Request("w_paisnascimento") & ", " & _
             "    estadonascimento         = " & Request("w_estadonascimento") & ", " & _
             "    municipionascimento      = " & Request("w_municipionascimento") & " " & _
             "where handle                 =" & Session("SQ_PESSOA")
       ExecutaSQL(SQL)
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Dados alterados com sucesso!');"
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica atual inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
  ElseIf P1 = 1 and P2 = "" Then ' Se for troca de senha de acesso
    SQL = "select count(*) Inicial from vw_pessoa_username a where senha = seguranca.criptografia(upper('" & Request("w_atual") & "')) and upper(a.username) = upper('" & Session("USERNAME") & "')"
    ConectaBD
    If RS("Inicial") > 0 Then
       SQL = "update sg_pessoa " & _
             "set ultima_troca_senha       = sysdate, " & _
             "    tentativas_senha         = 0 " & _
             "where sq_pessoa              =" & Session("SQ_PESSOA")
       ExecutaSQL(SQL)
       SQL = "update corporativo.z_grupousuarios " & _
             "set senhaweb                 = seguranca.criptografia(upper('" & Request("w_nova") & "')), " & _
             "    dt_ultima_troca_senha    = sysdate, " & _
             "    nu_tentativas_acesso_web = 0 " & _
             "where pessoa                 =" & Session("SQ_PESSOA")
       ExecutaSQL(SQL)
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Senha de Acesso alterada com sucesso!');"
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Senha de Acesso atual inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
    DesconectaBD
  ElseIf P1 = 2 Then ' Se for troca de assinatura eletrônica
    If VerificaAssinaturaEletronica(Session("Username"),uCase(Request("w_atual"))) Then
       SQL = "update sg_pessoa " & _
             "set ultima_troca_asinatura   = sysdate, " & _
             "    tentativas_assinatura    = 0 " & _
             "where sq_pessoa              =" & Session("SQ_PESSOA")
       ExecutaSQL(SQL)
       SQL = "update corporativo.un_certificacoes " & _
             "set assinaturaweb            = seguranca.criptografia(upper('" & Request("w_nova") & "')), " & _
             "    dt_ultima_troca_senha    = sysdate, " & _
             "    nu_tentativas_acesso_web = 0 " & _
             "where pessoa                 =" & Session("SQ_PESSOA")
       ExecutaSQL(SQL)
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica alterada com sucesso!');"
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica atual inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
  End If
  ScriptOpen "JavaScript"
  ShowHTML "  location.href='" & R & "&O=A&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
  ScriptClose

End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina de encerramento da sessão
REM -------------------------------------------------------------------------
Sub Sair
  Session.Abandon
  Response.Redirect "Default.asp"
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main

  Select Case Par
    Case "GRAVA"
       Grava
    Case "TROCASENHA"
       TrocaSenha
    Case "FRAMES"
       Frames
    Case "SAIR"
       Sair
    Case Else
       Cabecalho
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

