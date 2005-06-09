<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Noticias.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o portal de licita��es
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM

Private Par
Par          = ucase(Request("Par"))

Main

Set Par           = Nothing

REM =========================================================================
REM Rotina de visualiza��o da noticia treminamento_ms2806
REM -------------------------------------------------------------------------
Sub Treinamento_ms2806

   ShowHTML "<html>"
   
   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Not�cias</title>"
   ShowHTML "<style>"
   ShowHTML "BODY {"
   ShowHTML "	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "}	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "</head>"
   ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "<p align=""center""><a name=""topo""></a></p>"

   ShowHTML "      <p align=""left""><b><font face=""Verdana"" size=""2"">Treinamento com servidores das Unidades Hospitalares do MS come�a dia 28/06.</font></b></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">Entre os dias 28 de junho e 2 de julho de 2004, funcion�rios de diferentes institui��es de sa�de integrantes e/ou vinculadas ao MS participar�o do treinamento em Preg�o Eletr�nico e Comprasnet na capital fluminense.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">Mais de 40 servidores ser�o capacitados para atuarem na modalidade Preg�o. Unidades hospitalares como o Hospital Geral de Bonsucesso (HGB) e o Instituto Nacional de C�ncer (INCA) s�o algumas dos �rg�os que estar�o participando do treinamento atrav�s da capacita��o de seus servidores.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">A Ag�ncia Nacional de Sa�de Suplementar (ANS) e o Programa DST/Aids do Minist�rio da Sa�de tamb�m ter�o alguns de seus funcion�rios participando no treinamento.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1""><a href=""file:///F:/Webs_MS/pregao/nova/arquivos/noticias/Tabela%20de%20participantes%20por%20Unidade%20Hospitalar%20do%20MS.pdf"" target=""_blank"">Veja a lista dos inscritos para o Treinamento em Preg�o Eletr�nico e Comprasnet.</a></font></p>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, voc� precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font>&nbsp;</p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "<p align=""right"">&nbsp;</p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o da noticia treminamento_ms2806
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da noticia ms_contabiliza_fun
REM -------------------------------------------------------------------------
Sub Ms_contabiliza_fun

   ShowHTML "<html>"
   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Not�cia</title>"
   ShowHTML "<style>"
   ShowHTML "BODY {"
   ShowHTML "	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "}	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "</head>"
   ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "<p align=""center""><a name=""topo""></a></p>"

   ShowHTML "<p align=""left""><font face=""Verdana"" size=""2""><b>MS contabiliza mais de 140 funcion�rios capacitados em cursos sobre Preg�o.</b></font></p>"
   ShowHTML "<p align=""left""><font face=""Verdana"" size=""1"">Durante os meses de abril e maio de 2004, v�rios �rg�os do Minist�rio da Sa�de capacitaram grupos de servidores para atuarem na modalidade de compras institucionais Preg�o. Nesse per�odo, somam-se 140 funcion�rios treinados em cursos tem�ticos sobre Comprasnet (Portal de Compras do Governo Federal), Preg�o Eletr�nico e Presencial. Outros 12 servidores da Secretaria de Sa�de do Estado do Cear�, �rg�o n�o vinculado ao MS, ampliam a lista de funcion�rios da �rea da sa�de habilitados para trabalharem no setor de compras.</font></p>"
   ShowHTML "<p align=""left""><font face=""Verdana"" size=""1"">No gr�fico abaixo, � poss�vel visualizar o quantitativo total de pessoal capacitado classificado por �rg�os participantes dos treinamentos.  As Divis�es de Conv�nios (Dicon's) dos N�cleos Estaduais representam o maior parcela do quantitativo.</font></p>"
   ShowHTML "<div align=""center"">"
   ShowHTML "  <center>"
   ShowHTML "  <table border=""0"" cellspacing=""0"" width=""90%"">"
   ShowHTML "    <tr>"
   ShowHTML "      <td width=""300""><img border=""0"" src=""images/grafico-bola.gif"" width=""306"" height=""250""></td>"
   ShowHTML "      <td width=""250"" align=""right"">"
   ShowHTML "        <p align=""right""><img border=""0"" src=""images/grafico-legenda.gif"" align=""right"" width=""198"" height=""241""></td>"
   ShowHTML "    </tr>"
   ShowHTML "  </table>"
   ShowHTML "  </center>"
   ShowHTML "</div>"
   ShowHTML "<p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "<b>)</b></font></font></p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o da noticia ms_contabiliza_fun
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da noticia pregao_040615
REM -------------------------------------------------------------------------
Sub Pregao_040615

   ShowHTML "<html>"

   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Not�cias</title>"
   ShowHTML "<style>"
   ShowHTML "BODY {"
   ShowHTML "	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "}	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "</head>"
   ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "<p align=""center""><a name=""topo""></a></p>"

   ShowHTML "<p><font size=""2"" face=""Verdana""><b>Preg�o Eletr�nico realizado dia 15/06 seleciona empresa para presta��o de servi�o</b></font>"
   ShowHTML "</p>"
   ShowHTML "<table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "  <tr>"
   ShowHTML "    <td width=""50%"">"
   ShowHTML "            <font face=""Verdana"" size=""1"">A equipe de apoio coordenada pelo Pregoeiro Luiz Ant�nio Patta Mel�o finalizaram (????) o preg�o Eletr�nico n� ???? promovido pelo Minist�rio da Sa�de. O processo licitat�rio teve como objetivo contratar uma empresa especializada em promo��o de eventos para a organiza��o do Congresso Brasileiro de Centros de Aten��o Psicossocial. O evento acontece entre os dias 28 de junho e 1� de julho em S�o Paulo. As CAPS s�o entidades p�blicas que prestam o servi�o comunit�rio de assist�ncia �s pessoas que sofrem com transtornos mentais. Os Centros est�o sob a tutela da Coordena��o Geral de Sa�de Mental/DAPE/SAS.</font>"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">XXXX empresas fizeram lances para a proposta descrita no Edital n� ????. A empresa vencedora foi a ??????. A equipe de apoi foi composta por ????/</font></p></td>"
   ShowHTML "    <td width=""50%""><img border=""1"" src=""images/pregao-1506.jpg"" hspace=""5"" width=""378"" height=""250""></td>"
   ShowHTML "  </tr>"
   ShowHTML "</table>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, voc� precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font></p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o da noticia pregao_040615
REM -------------------------------------------------------------------------


REM =========================================================================
REM Rotina de visualiza��o da noticia Serivdores_ne
REM -------------------------------------------------------------------------
Sub Serivdores_ne

   ShowHTML "<html>"

   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Noticias</title>"
   ShowHTML "<style>"
   ShowHTML "BODY {"
   ShowHTML "	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "}	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "</head>"
   ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "<p align=""center""><a name=""topo""></a></p>"

   ShowHTML "<p><font size=""2"" face=""Verdana""><b>Servidores dos N�cleos Estaduais participam do treinamento sobre Preg�o Eletr�nico</b></font></p>"
   ShowHTML "<table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "  <tr>"
   ShowHTML "    <td width=""50%"">"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">Durante os dias 29 e 30 de abril, funcion�rios das Dicon's (Divis�o de Conv�nios) dos N�cleos Estaduais do Minist�rio da Sa�de participaram do treinamento em Preg�o Eletr�nico, em Bras�lia, no Edif�cio sede do Minist�rio do Planejamento, Or�amento e Gest�o. Dezoito estados da federa��o foram representados pelos 99 servidores capacitados durante o curso, cujo instrutor foi Cleber Bueno, analista em O&amp;M do MPOG.</font></p>"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">Ao final do treinamento, cada Dicon designou um pregoeiro, um ordenador de despesas e montou a equipe de apoio. O Minist�rio da Sa�de tamb�m capacitou outros 6 servidores para atuarem na modalidade Preg�o Eletr�nico.</font></p>"
   ShowHTML "            <p><font face=""Verdana"" size=""1""><a href=""file:///F:/Webs_MS/pregao/nova/arquivos/noticias/Preg�o%20Eletr�nio-%20Nucleos.pdf"" target=""_blank"">Veja a lista dos participantes das Dicon's para o Treinamento em Preg�o Eletr�nico.</a></font></p></td>"
   ShowHTML "    <td width=""50%""><img border=""1"" src=""images/servidores-n_e.jpg"" hspace=""5"" width=""372"" height=""250""></td>"
   ShowHTML "  </tr>"
   ShowHTML "</table>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, voc� precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font>"
   ShowHTML "      </p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "</body>"
   ShowHTML "</html>"


End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o da noticia Serivdores_ne
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da noticia Serivdores-ne
REM -------------------------------------------------------------------------
Sub Cursos_enap

   ShowHTML "<html>"

   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Not�cias</title>"
   ShowHTML "<style>"
   ShowHTML "BODY {"
   ShowHTML "	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "}	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "</head>"
   ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "<p align=""center""><a name=""topo""></a></p>"

   ShowHTML "      <p><b><font face=""Verdana"" size=""2"">Cursos da ENAP est�o com inscri��es abertas para turmas em setembro e outubro.</font></b></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Os cursos Forma��o de Pregoeiro e Licita��es e contratos na administra��o P�blica ministrados pela Escola Nacional de Administra��o P�blica (ENAP) est�o com inscri��es abertas. O primeiro tem a carga de hor�ria de 24h, com aulas em tempo integral, com data de in�cio programada para 27/09. Esta turma ser� a d�cima formada pela ENAP.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">No treinamento em Licita��es e contratos na administra��o P�blica, os alunos iniciar�o as aulas em outubro. A carga hor�ria � de 35h, que devem ser cumpridos em tempo integral.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Em ambos os cursos, o p�blico-alvo abrange servidores e funcion�rios p�blicos com conhecimento ou experi�ncia na �rea de compras para o setor p�blico.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Mais informa��es sobre custos, cronograma e grade curricular podem ser obtidas no"
   ShowHTML "      <a href=""http://www.enap.gov.br/catalogo/cursos/lista_curso_area.asp"" target=""_blank""> site da ENAP</a> ou no e-mail"
   ShowHTML "      <a href=""mailto:desenvolvimentogerencial@enap.gov.br"">desenvolvimentogerencial@enap.gov.br</a>.</font></p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font></p>"
   ShowHTML "<p align=""right"">&nbsp;</p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o da noticia Cursos_enap
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main

  Select Case Par
    Case "TREINAMENTO_MS2806"
       Treinamento_ms2806
    Case "MS_CONTABILIZA_FUN"
       Ms_contabiliza_fun
    Case "PREGAO_040615"
       Pregao_040615
    Case "SERVIDORES_NE"
       Serivdores_ne
    Case "CURSOS_ENAP"
       Cursos_enap
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>
