<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Noticias.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o portal de licitações
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM

Private Par
Par          = ucase(Request("Par"))

Main

Set Par           = Nothing

REM =========================================================================
REM Rotina de visualização da noticia treminamento_ms2806
REM -------------------------------------------------------------------------
Sub Treinamento_ms2806

   ShowHTML "<html>"
   
   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Notícias</title>"
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

   ShowHTML "      <p align=""left""><b><font face=""Verdana"" size=""2"">Treinamento com servidores das Unidades Hospitalares do MS começa dia 28/06.</font></b></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">Entre os dias 28 de junho e 2 de julho de 2004, funcionários de diferentes instituições de saúde integrantes e/ou vinculadas ao MS participarão do treinamento em Pregão Eletrônico e Comprasnet na capital fluminense.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">Mais de 40 servidores serão capacitados para atuarem na modalidade Pregão. Unidades hospitalares como o Hospital Geral de Bonsucesso (HGB) e o Instituto Nacional de Câncer (INCA) são algumas dos órgãos que estarão participando do treinamento através da capacitação de seus servidores.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1"">A Agência Nacional de Saúde Suplementar (ANS) e o Programa DST/Aids do Ministério da Saúde também terão alguns de seus funcionários participando no treinamento.</font></p>"
   ShowHTML "      <p align=""left""><font face=""Verdana"" size=""1""><a href=""file:///F:/Webs_MS/pregao/nova/arquivos/noticias/Tabela%20de%20participantes%20por%20Unidade%20Hospitalar%20do%20MS.pdf"" target=""_blank"">Veja a lista dos inscritos para o Treinamento em Pregão Eletrônico e Comprasnet.</a></font></p>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, você precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font>&nbsp;</p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "<p align=""right"">&nbsp;</p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualização da noticia treminamento_ms2806
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização da noticia ms_contabiliza_fun
REM -------------------------------------------------------------------------
Sub Ms_contabiliza_fun

   ShowHTML "<html>"
   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Notícia</title>"
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

   ShowHTML "<p align=""left""><font face=""Verdana"" size=""2""><b>MS contabiliza mais de 140 funcionários capacitados em cursos sobre Pregão.</b></font></p>"
   ShowHTML "<p align=""left""><font face=""Verdana"" size=""1"">Durante os meses de abril e maio de 2004, vários órgãos do Ministério da Saúde capacitaram grupos de servidores para atuarem na modalidade de compras institucionais Pregão. Nesse período, somam-se 140 funcionários treinados em cursos temáticos sobre Comprasnet (Portal de Compras do Governo Federal), Pregão Eletrônico e Presencial. Outros 12 servidores da Secretaria de Saúde do Estado do Ceará, órgão não vinculado ao MS, ampliam a lista de funcionários da área da saúde habilitados para trabalharem no setor de compras.</font></p>"
   ShowHTML "<p align=""left""><font face=""Verdana"" size=""1"">No gráfico abaixo, é possível visualizar o quantitativo total de pessoal capacitado classificado por órgãos participantes dos treinamentos.  As Divisões de Convênios (Dicon's) dos Núcleos Estaduais representam o maior parcela do quantitativo.</font></p>"
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
REM Fim da rotina de visualização da noticia ms_contabiliza_fun
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização da noticia pregao_040615
REM -------------------------------------------------------------------------
Sub Pregao_040615

   ShowHTML "<html>"

   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Notícias</title>"
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

   ShowHTML "<p><font size=""2"" face=""Verdana""><b>Pregão Eletrônico realizado dia 15/06 seleciona empresa para prestação de serviço</b></font>"
   ShowHTML "</p>"
   ShowHTML "<table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "  <tr>"
   ShowHTML "    <td width=""50%"">"
   ShowHTML "            <font face=""Verdana"" size=""1"">A equipe de apoio coordenada pelo Pregoeiro Luiz Antônio Patta Melão finalizaram (????) o pregão Eletrônico nº ???? promovido pelo Ministério da Saúde. O processo licitatório teve como objetivo contratar uma empresa especializada em promoção de eventos para a organização do Congresso Brasileiro de Centros de Atenção Psicossocial. O evento acontece entre os dias 28 de junho e 1º de julho em São Paulo. As CAPS são entidades públicas que prestam o serviço comunitário de assistência às pessoas que sofrem com transtornos mentais. Os Centros estão sob a tutela da Coordenação Geral de Saúde Mental/DAPE/SAS.</font>"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">XXXX empresas fizeram lances para a proposta descrita no Edital nº ????. A empresa vencedora foi a ??????. A equipe de apoi foi composta por ????/</font></p></td>"
   ShowHTML "    <td width=""50%""><img border=""1"" src=""images/pregao-1506.jpg"" hspace=""5"" width=""378"" height=""250""></td>"
   ShowHTML "  </tr>"
   ShowHTML "</table>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, você precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font></p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualização da noticia pregao_040615
REM -------------------------------------------------------------------------


REM =========================================================================
REM Rotina de visualização da noticia Serivdores_ne
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

   ShowHTML "<p><font size=""2"" face=""Verdana""><b>Servidores dos Núcleos Estaduais participam do treinamento sobre Pregão Eletrônico</b></font></p>"
   ShowHTML "<table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "  <tr>"
   ShowHTML "    <td width=""50%"">"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">Durante os dias 29 e 30 de abril, funcionários das Dicon's (Divisão de Convênios) dos Núcleos Estaduais do Ministério da Saúde participaram do treinamento em Pregão Eletrônico, em Brasília, no Edifício sede do Ministério do Planejamento, Orçamento e Gestão. Dezoito estados da federação foram representados pelos 99 servidores capacitados durante o curso, cujo instrutor foi Cleber Bueno, analista em O&amp;M do MPOG.</font></p>"
   ShowHTML "            <p><font face=""Verdana"" size=""1"">Ao final do treinamento, cada Dicon designou um pregoeiro, um ordenador de despesas e montou a equipe de apoio. O Ministério da Saúde também capacitou outros 6 servidores para atuarem na modalidade Pregão Eletrônico.</font></p>"
   ShowHTML "            <p><font face=""Verdana"" size=""1""><a href=""file:///F:/Webs_MS/pregao/nova/arquivos/noticias/Pregão%20Eletrônio-%20Nucleos.pdf"" target=""_blank"">Veja a lista dos participantes das Dicon's para o Treinamento em Pregão Eletrônico.</a></font></p></td>"
   ShowHTML "    <td width=""50%""><img border=""1"" src=""images/servidores-n_e.jpg"" hspace=""5"" width=""372"" height=""250""></td>"
   ShowHTML "  </tr>"
   ShowHTML "</table>"
   ShowHTML "      <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">  PDF, você precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat Reader</u></a>.)</font>"
   ShowHTML "      </p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font>"
   ShowHTML "      </p>"
   ShowHTML "</body>"
   ShowHTML "</html>"


End Sub
REM =========================================================================
REM Fim da rotina de visualização da noticia Serivdores_ne
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização da noticia Serivdores-ne
REM -------------------------------------------------------------------------
Sub Cursos_enap

   ShowHTML "<html>"

   ShowHTML "<head>"
   ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "<title>Notícias</title>"
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

   ShowHTML "      <p><b><font face=""Verdana"" size=""2"">Cursos da ENAP estão com inscrições abertas para turmas em setembro e outubro.</font></b></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Os cursos Formação de Pregoeiro e Licitações e contratos na administração Pública ministrados pela Escola Nacional de Administração Pública (ENAP) estão com inscrições abertas. O primeiro tem a carga de horária de 24h, com aulas em tempo integral, com data de início programada para 27/09. Esta turma será a décima formada pela ENAP.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">No treinamento em Licitações e contratos na administração Pública, os alunos iniciarão as aulas em outubro. A carga horária é de 35h, que devem ser cumpridos em tempo integral.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Em ambos os cursos, o público-alvo abrange servidores e funcionários públicos com conhecimento ou experiência na área de compras para o setor público.</font></p>"
   ShowHTML "      <p><font face=""Verdana"" size=""1"">Mais informações sobre custos, cronograma e grade curricular podem ser obtidas no"
   ShowHTML "      <a href=""http://www.enap.gov.br/catalogo/cursos/lista_curso_area.asp"" target=""_blank""> site da ENAP</a> ou no e-mail"
   ShowHTML "      <a href=""mailto:desenvolvimentogerencial@enap.gov.br"">desenvolvimentogerencial@enap.gov.br</a>.</font></p>"
   ShowHTML "      <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "      <b>)</b></font></font></p>"
   ShowHTML "<p align=""right"">&nbsp;</p>"
   ShowHTML "</body>"
   ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualização da noticia Cursos_enap
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
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>
