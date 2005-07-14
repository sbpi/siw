<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/DB_Contrato.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Default.asp
REM ------------------------------------------------------------------------
REM Nome : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o portal do projeto Ano do Brasil na Fran�a
REM Mail : alex@sbpi.com.br
REM Criacao  : 14/02/2005, 19:40
REM Versao   : 1.0.0.0
REM Local: Bras�lia - DF
REM -------------------------------------------------------------------------
REM


' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4
Dim P1, P2, P3, P4, TP, SG, w_cliente, w_cont, w_cont_aux
Dim w_pagina, w_chave, w_erro, w_string
  
Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
   
Par  = Ucase(Request("Par"))
P1   = Nvl(Request("P1"),0)
P2   = Nvl(Request("P2"),0)
P3   = cDbl(Nvl(Request("P3"),1))
P4   = cDbl(Nvl(Request("P4"),conPagesize))
TP   = Request("TP")
SG   = ucase(Request("SG"))



If par = "" Then Par = "INICIAL" End If

w_pagina = "Default.asp?par="

w_cliente = RetornaCliente()


ShowHTML "<!DOCTYPE HTML PUBLIC ""-//W3C//DTD HTML 4.01 Transitional//EN"" ""http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd"">"
ShowHTML "<HTML xmlns=""http://www.w3.org/1999/xhtml"">"
ShowHTML "<HEAD>"
ShowHTML "<TITLE>Ano do Brasil na Fran�a</TITLE>"
ShowHTML "<!-- InstanceBegin template=""/Templates/Arquivo - 1 coluna.dwt"" codeOutsideHTMLIsLocked=""false"" -->"
ShowHTML ""
ShowHTML "<META http-equiv=Content-Type content=""text/html; charset=iso-8859-1"">"
ShowHTML "<!-- InstanceBeginEditable name=""EditRegion5"" -->"
ShowHTML "<!-- InstanceEndEditable -->"
ShowHTML "<LINK  media=screen href=""css/estilo.css"" type=text/css rel=stylesheet>"
ShowHTML "<LINK media=print href=""css/print.css"" type=text/css rel=stylesheet>"
ShowHTML "<LINK media=handheld href=""css/handheld.css"" type=text/css rel=stylesheet>"
ShowHTML "<!-- InstanceBeginEditable name=""head"" --><!-- InstanceEndEditable -->"
ShowHTML "<!-- InstanceParam name=""onload"" type=""boolean"" value=""true"" -->"
ShowHTML "<!-- InstanceParam name=""Scripts"" type=""boolean"" value=""true"" -->"
ShowHTML "<SCRIPT language=javascript src=""js/scripts.js"" type=text/javascript> "
ShowHTML "</SCRIPT>"
ShowHTML "<META content=""MSHTML 6.00.2800.1491"" name=GENERATOR></HEAD>"
ShowHTML "<BODY>"
ShowHTML "<center>"
ShowHTML "<DIV id=container>"
ShowHTML "  <DIV id=cab>"
ShowHTML "<DIV id=cabtopo>"
ShowHTML "  <DIV id=logoesq>"
ShowHTML "<H1>Minist�rio da Cultura</H1>"
ShowHTML "<br>"
ShowHTML "<select name=""opcoes"" onChange=""if(options[selectedIndex].value) window.location.href= (options[selectedIndex].value)"" class=""pr"">"
ShowHTML "  <option>Destaques do governo</option>"
ShowHTML "  <option value=""javascript:nova_jan('http://www.brasil.gov.br')"">Portal do Governo Federal</option>"
ShowHTML "  <option value=""javascript:nova_jan('http://www.e.gov.br')"">Portal de Servi&ccedil;os do Governo</option>"
ShowHTML "  <option value=""javascript:nova_jan('http://www.radiobras.gov.br')"">Portal da Ag&ecirc;ncia de Not&iacute;cias</option>"
ShowHTML "  <option value=""javascript:nova_jan('http://www.brasil.gov.br/emquestao')"">Em Quest�o</option>"
ShowHTML "  <option value=""javascript:nova_jan('http://www.fomezero.gov.br')"">Programa Fome Zero</option>"
ShowHTML "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
ShowHTML "  </DIV>"
ShowHTML "  <DIV id=logodir><H2>Projeto Ano do Brasil na Fran�a</H2></DIV>"
ShowHTML "</DIV>"
ShowHTML ""
ShowHTML "<DIV id=cabbase>"
ShowHTML ""
ShowHTML "  <form id=formbusca method=""post"" action=""/siw/Default.asp"" onsubmit=""return(ValidaLogin(this));"" name=""FormLogin""> "
ShowHTML "  <DIV id=busca>"
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Login"" VALUE=""""> "
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""Password"" VALUE=""""> "
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""par"" VALUE=""Log""> "
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_dbms"" VALUE=""1""> "
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_cliente"" VALUE=""6761""> "
ShowHTML "<INPUT TYPE=""HIDDEN"" NAME=""p_versao"" VALUE=""2""> "
ShowHTML ""
ShowHTML "<body topmargin=0 leftmargin=10 onLoad=""document.Form.Login1.focus();"">"
ShowHTML "CPF: <input class=""sti"" name=""Login1"" size=""14"" maxlength=""14"" onkeyDown=""FormataCPF(this,event)"">"
ShowHTML "Senha: <input class=""sti"" type=""Password"" name=""Password1"" size=""19"">"
ShowHTML "<input class=""stb"" type=""submit"" value=""OK"" name=""Botao"" onClick=""document.FormLogin.par.value='Log';""> "
ShowHTML "<input class=""stb"" type=""submit"" value=""Recriar senha"" name=""Botao"" onClick=""document.FormLogin.par.value='Senha';"" title=""Informe seu CPF e clique aqui para receber por e-mail sua senha e assinatura eletr�nica!""> "
ShowHTML "  </DIV>"
ShowHTML "  </FORM>"
ShowHTML ""
ShowHTML "  <DIV id=data>"
ShowHTML "  <SCRIPT src=""js/data.js"" type=text/javascript>"
ShowHTML "  /**********************************************************************************"
ShowHTML "  Exibedata"
ShowHTML "  *********************************************************************************/"
ShowHTML "  </SCRIPT>"
ShowHTML "  </DIV>"
ShowHTML "</DIV>"
ShowHTML "  </DIV>"
ShowHTML ""
ShowHTML "  <DIV id=corpo>"
ShowHTML ""
ShowHTML "<DIV id=menuesq>"
ShowHTML "  <DIV id=logomenuesq><H3>BresilBresils</H3></DIV>"
ShowHTML "  <DIV id=logomenuesq2><H3>Cespe</H3></DIV>"
ShowHTML ""
ShowHTML "  <DIV id=menusep><HR></DIV>"
ShowHTML "  <UL id=menugov>"
ShowHTML "<LI><b>Proponentes</b>"
ShowHTML "<LI><A title=""Cadastro de propostas"" href=""" & w_pagina & "proposta"">Cadastro de propostas</A>"
ShowHTML "<LI><A title=""Acompanhamento pelo proponente"" href=""" & w_pagina & "consulta"">Acompanhamento</A>"
ShowHTML "  </UL>"
ShowHTML ""
ShowHTML "  <DIV id=menusep><HR></DIV>"
ShowHTML ""
ShowHTML "  <DIV id=menunav>"
ShowHTML "<UL>"
ShowHTML "   <A class=starter title=""Fale conosco"" accessKey=5 href=""" & w_pagina & "fale"">Fale conosco</A>"
ShowHTML "   <A class=starter title=""Logomarcas""   accessKey=5 href=""" & w_pagina & "logo"">Logomarcas</A>"
'ShowHTML "  <LI><b>Patrocinadores</b>"
'ShowHTML "  <LI><A class=dir title=""Link para o site do Banco do Brasil"" href=""http://www.bb.com.br"" target=""_blank"">Banco do Brasil</A> "
'ShowHTML "  <LI><A class=dir title=""Link para o site dos Correios"" href=""http://www.correios.gov.br"" target=""_blank"">Correios</A> "
'ShowHTML "  <LI><A class=dir title=""Link para o site da Furnas"" href=""http://www.furnas.com.br"" target=""_blank"">Furnas</A> "
'ShowHTML "  <LI><A class=dir title=""Link para o site da Petrobr�s"" href=""http://www2.petrobras.com.br"" target=""_blank"">Petrobr�s</A> "
'ShowHTML "  <LI><A class=dir title=""Link para o site da Varig"" href=""http://www.varig.com.br"" target=_blank>Varig</A> "
'ShowHTML "</UL>"
'ShowHTML "<DIV id=menusep><HR></DIV>"
'ShowHTML "<UL>"
'ShowHTML "  <LI><b>Parceiros</b>"
'ShowHTML "  <LI><A class=dir title=""Link para o site do CESPE/Unb"" href=""http://www.cespe.unb.br"" target=""_blank"">CESPE</A> "
'ShowHTML "  <LI><A class=dir title=""Lista de estados parceiros"" href=""estados.htm"" target=""_blank"">Estados</A> "
'ShowHTML "  <LI><A class=dir title=""???"" href=""???"" target=""_blank"">Maire Paris</A> "
'ShowHTML "  <LI><A class=dir title=""Link para o site da Varig"" href=""http://www.varig.com.br"" target=_blank>Varig</A> "
'ShowHTML "</UL>"
ShowHTML "</UL>"
ShowHTML "  </DIV>"
ShowHTML "</DIV>"
ShowHTML "  "
ShowHTML "<DIV id=menutxt>"
ShowHTML "  <SCRIPT src=""js/newcssmenu.js"" type=text/javascript></SCRIPT>"
ShowHTML "  "
ShowHTML "  <DIV id=menutexto>"
ShowHTML "<DIV id=mainMenu>"
ShowHTML "  <UL id=menuList>"
ShowHTML "<LI class=menubar>::<A class=starter accessKey=1 href=""/cespe_novo/"">Inicial</A>"
ShowHTML "<LI class=menubar>::<A class=starter accessKey=2 href=""/cespe_novo/#"">Institucional</A> "
ShowHTML "<UL class=menu id=menu1>"
ShowHTML "  <LI><A title=""Apresenta��o do Ano do Brasil na Fran�a"" href=""" & w_pagina & "apresentacao"">Apresenta��o</A> "
ShowHTML "  <LI><A title=""Comissariado do Ano do Brasil na Fran�a"" href=""" & w_pagina & "comissao"">Comissariado</A> "
ShowHTML " </UL>"

DB_GetCCTree RS, w_cliente, "IS NULL"
RS.Sort = "sigla"
w_cont = 0
ShowHTML "<LI class=menubar>::<A class=starter accessKey=3 href=""/cespe_novo/#"">Programa��o</A> "
ShowHTML "<UL class=menu id=menu1>"
While Not RS.EOF
   w_cont = w_cont + 1
   If cDbl(RS("Filho")) > 0 Then
  ShowHTML "<LI><A title=""" & RS("descricao") & """ href=""#""><IMG height=12 alt="">"" src=""img/arrows.gif"" width=8> " & RS("sigla") & "</A> "
  ShowHTML "<UL class=menu id=menu2_" & w_cont & ">"
  w_cont_aux = 0
  DB_GetCCTree RS1, w_cliente, RS("sq_cc")
  RS1.Sort = "sigla"
  w_cont_aux = 0
  While Not RS1.EOF
 If cDbl(RS1("Filho")) > 0 Then
w_cont_aux = w_cont_aux + 1
ShowHTML "<LI><A title=""" & RS1("descricao") & """ href=""#""><IMG height=12 alt="">"" src=""img/arrows.gif"" width=8> " & RS1("sigla") & "</A> "
ShowHTML "<UL class=menu id=menu2_" & w_cont & "_" & w_cont_aux & ">"
DB_GetCCTree RS2, w_cliente, RS1("sq_cc")
RS2.Sort = "sigla"
If Not RS2.EOF Then
   While Not RS2.EOF
      ShowHTML "  <LI><A title=""" & RS2("descricao") & """ href=""" & w_pagina & "evento&w_chave=" & RS2("sq_cc") & """>" & RS2("sigla") & "</A> </LI>"
      RS2.MoveNext
   Wend
   RS2.Close
End If
ShowHTML "</UL></LI>"
 Else
ShowHTML "  <LI><A title=""" & RS1("descricao") & """ href=""" & w_pagina & "evento&w_chave=" & RS1("sq_cc") & """>" & RS1("sigla") & "</A> </LI>"
 End If
 RS1.MoveNext
  Wend
  ShowHTML "</UL></LI>"
   Else
  ShowHTML "  <LI><A title=""" & RS("descricao") & """ href=""" & w_pagina & "evento&w_chave=" & RS("sq_cc") & """>" & RS("sigla") & "</A> </LI>"
   End If
   RS.MoveNext
Wend
DesconectaBD
RS1.Close
ShowHTML "</UL></LI>"
'ShowHTML "<LI class=menubar>::<A class=starter title=""Logomarcas"" accessKey=4 href=""" & w_pagina & "logomarca"">Logomarcas</A> "
'ShowHTML "<LI class=menubar>::<A class=starter title=""Fale conosco"" accessKey=5 href=""" & w_pagina & "fale"">Fale conosco</A>::"
ShowHTML "  </UL>"
ShowHTML "</DIV>"
ShowHTML "  </DIV>"
ShowHTML "</DIV>"
ShowHTML ""
ShowHTML "<DIV id=texto><!-- Conte�do --><!-- InstanceBeginEditable name=""Texto"" -->"
ShowHTML "  <DIV>"

Main

ShowHTML "  </DIV>"
ShowHTML "</DIV>"
ShowHTML ""
ShowHTML "<DIV id=menudir>"
ShowHTML "  <DIV style=""BORDER-RIGHT: #505050 1px solid; BORDER-TOP: #505050 1px solid; BORDER-BOTTOM: #505050 1px solid; BORDER-LEFT: #505050 1px solid; WIDTH: 130px;"">"
ShowHTML "<MARQUEE onmouseover=this.scrollAmount=0 style=""PADDING-RIGHT: 1px; PADDING-LEFT: 3px; FONT-SIZE: 10px; BACKGROUND: none transparent scroll epeat 0% 0%; PADDING-BOTTOM: 3px; BORDER-TOP-STYLE: none; PADDING-TOP: 3px; FONT-FAMILY: Verdana, Arial, Helvetica; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; HEIGHT: 80px; BORDER-BOTTOM-STYLE: none"" onmouseout=this.scrollAmount=3 scrollAmount=3 direction=up>"
ShowHTML "  <DIV style=""WIDTH: 130px"" class=TituloPeq>"
ShowHTML "<IMG style=""BORDER-TOP-WIDTH: 0px; BORDER-LEFT-WIDTH: 0px; BORDER-BOTTOM-WIDTH: 0px; BORDER-RIGHT-WIDTH: 0px"" alt="""" src=""img/bullet.gif""> "
ShowHTML "<A href=""http://www.cultura.gov.br/documentos/Ano_Brasil_Franca_Lista_Aprovados_2004.pdf"" target=_blank>Projetos j� aprovados </A>"
ShowHTML "  </DIV><BR>"
ShowHTML "</MARQUEE>"
ShowHTML "  </DIV>"
ShowHTML "  "
ShowHTML "  <!--"
ShowHTML "  <P ALIGN=""CENTER""><b>PARCEIROS</B></P>"
ShowHTML "  <P><A href=""http://www.cespe.unb.br"" target=_blank>"
ShowHTML "   <IMG height=66 alt=""CESPE"" src=""img/logo_cespe.jpg"" width=130 border=0>"
ShowHTML " </A>"
ShowHTML "  </P>"
ShowHTML "  "
ShowHTML "  <P><A href=""http://www.se.df.gov.br/PROGRAMASPROJETOS/RendaMinhaPrograma.pdf"" target=_blank>"
ShowHTML "   <IMG height=80 alt=""Programa Renda Minha"" src=""img/banner_rendaminha.gif"" width=130 border=0>"
ShowHTML " </A>"
ShowHTML "   </P>"
ShowHTML "   "
ShowHTML "   <P><A href=""http://www.se.df.gov.br/mostranoticia.asp?id=714"" target=_blank>"
ShowHTML "<IMG height=80 alt=""Impressão de Documentos e Formulários"" src=""img/banner_impressaodoc.gif"" width=130 border=0>"
ShowHTML "  </A>"
ShowHTML "   </P>"
ShowHTML "   "
ShowHTML "   <P><A href=""http://www.se.df.gov.br/mural/logon.asp"" target=_blank>"
ShowHTML "<IMG height=80 alt=""Mural de Permutas"" src=""img/banner_muraldeperm.gif"" width=130 border=0>"
ShowHTML "  </A>"
ShowHTML "   </P>"
ShowHTML "   "
ShowHTML "   <P><A href=""http://www.gdfsige.df.gov.br/"" target=_blank>"
ShowHTML "<IMG height=80 src=""img/banner_sige.gif"" width=130>"
ShowHTML "  </A>"
ShowHTML "   </P>"
ShowHTML "   "
ShowHTML "   <P><A href=""http://www.se.df.gov.br/ResultTelemat/index.asp"" target=_blank>"
ShowHTML "<IMG height=80 src=""img/banner_tele.gif"" width=130 border=0>"
ShowHTML "  </A>"
ShowHTML "   </P>"
ShowHTML "   -->"
ShowHTML "   <BR><!-- InstanceBeginEditable name=""Barra Index"" --><!-- InstanceEndEditable -->"
ShowHTML "</DIV>"
ShowHTML "  </DIV>"
ShowHTML "   "
ShowHTML "  <BR clear=all>"
ShowHTML "</DIV>"
ShowHTML " "
ShowHTML "<DIV id=rodape>"
ShowHTML "  <!--"
ShowHTML "  <DIV id=menurodape>"
ShowHTML "<A title=""P�gina Inicial"" href=""http://www.se.df.gov.br/index.asp"">P�gina Inicial</A> | "
ShowHTML "<A title=""Fale conosco"" href=""http://www.se.df.gov.br/fale_conosco/index.asp"">Fale conosco</A> | "
ShowHTML "<A title=""Mapa do site"" href=""http://www.se.df.gov.br/mapa/index.asp"">Mapa do site</A> | "
ShowHTML "<A href=""http://www.se.df.gov.br/expediente.asp"">Expediente</A>"
ShowHTML "<BR>"
ShowHTML "  </DIV>"
ShowHTML "  -->"
ShowHTML "  <DIV id=endereco>"
ShowHTML "<P>Setor Comercial Sul, Ed. Denasa - Salas 901/902 - Bras�lia-DF <BR>Tel : (61) 3225-6302 (61) 321-8938 | Fax (61) 3225-7599| email: <A href=""mailto:pbf@cespe.unb.br"">bresil2005@minc.gov.br</A>"
ShowHTML "   <!--<BR><BR>Copyright � 2000/2005 - CESPE/UnB - Todos os Direitos Reservados-->"
ShowHTML "</P>"
ShowHTML "  </DIV>"
ShowHTML "</DIV><!-- InstanceEnd -->"
ShowHTML "</center>"
ShowHTML "</BODY>"
ShowHTML "</HTML>"

FechaSessao

Set w_erro      = Nothing
Set w_string    = Nothing
Set w_cont      = Nothing
Set w_cont_aux  = Nothing
Set RS          = Nothing
Set RS1         = Nothing
Set RS2         = Nothing
Set RS3         = Nothing
Set RS4         = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set SG          = Nothing
Set w_cliente   = Nothing

REM =========================================================================
REM Rotina de visualiza��o da p�gina inicial do portal
REM -------------------------------------------------------------------------
Sub Inicial

    ShowHTML "<DIV class=retranca>Not�cias em destaque</DIV>"
    ShowHTML ""
    ShowHTML "<SPAN class=titulo>(<SCRIPT type=text/javascript>fcDataDDMMYYYY('2/13/2005')</SCRIPT>) "
    ShowHTML "  <A href=""http://www2.cultura.gov.br/scripts/noticia.idc?codigo=67"" target=""_blank"">Temporada do Brasil na Fran�a come�a a ser discutida</A>"
    ShowHTML "</SPAN>"
    ShowHTML "<BR><BR>"
    ShowHTML "<SPAN class=titulo>(<SCRIPT type=text/javascript>fcDataDDMMYYYY('1/16/2005')</SCRIPT>) "
    ShowHTML "  <A href=""http://www2.cultura.gov.br/scripts/noticia.idc?codigo=192"" target=""_blank"">Cinema brasileiro na Fran�a</A>"
    ShowHTML "</SPAN>"
    ShowHTML "<BR><BR>"
    ShowHTML "<SPAN class=titulo>(<SCRIPT type=text/javascript>fcDataDDMMYYYY('1/7/2005')</SCRIPT>) "
    ShowHTML "  <A href=""http://www2.cultura.gov.br/scripts/noticia.idc?codigo=698"" target=""_blank"">Produ��o cultural brasileira vai ser apresentada em cidades francesas</A>"
    ShowHTML "</SPAN> "
    ShowHTML "<BR><BR>"
    ShowHTML ""
    ShowHTML "<P></P>"
    ShowHTML ""
    ShowHTML "<H5><STRONG>"
    ShowHTML "  <A href=""http://www.se.df.gov.br/imprensa/indexnew.asp"">"
    ShowHTML "<IMG height=14 alt=""Mat�rias Anteriores"" src=""img/ico_anteriores.gif"" width=16 align=absMiddle border=0>"
    ShowHTML "Not�cias anteriores"
    ShowHTML "  </A>"
    ShowHTML "  </STRONG>"
    ShowHTML "  <BR>"
    ShowHTML "</H5>"

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de apresenta��o
REM -------------------------------------------------------------------------
Sub Apresentacao

    ShowHTML "<DIV class=retranca>Apresenta��o</DIV>"

    ShowHTML "<center><h3>ANO BRASIL NA FRAN�A - 2005</h3></center>"
    ShowHTML "<p align=""justify"">Os Governos Brasileiro e Franc�s decidiram que o ano de 2005 ser� uma grande temporada brasileira na Fran�a.</p>"
    ShowHTML "<p align=""justify"">A iniciativa envolver� os setores p�blico e privado dos dois pa�ses e proporcionar� ao povo franc�s conhecer melhor a diversidade da cultura brasileira.</p>"
    ShowHTML "<p align=""justify"">Esta rela��o estreita e calorosa entre nossas na��es celebrar� uma antiga amizade onde h� muito a compartilhar.</p>"
    ShowHTML "<p align=""justify"">Ao Brasil ser� tamb�m permitido apresentar este ano, al�m da �rea essencial da cultura, a integra de suas experi�ncias e de seu potencial. Para tanto os setores do empresariado, de ci�ncia e da tecnologia e do turismo contribuir�o grandemente no sucesso dessa manifesta��o.</p>"

    ShowHTML "<center><h3>ESPA�O BRASIL</h3></center>"
    ShowHTML "<p align=""justify"">O Espa�o Brasil � o projeto piloto de uma grande estrat�gia de promo��o da imagem do pa�s no exterior.</p>"
    ShowHTML "<p align=""justify"">Por iniciativa do Governo Brasileiro em parceria com a prefeitura de Paris,ser� montado entre junho e setembro no Carreau du Temple, um centro multidisciplinar capaz de receber as diversas formas de manifesta��o da cultura brasileira. A programa��o contar� com shows de m�sica, mostras de artes pl�sticas e de artesanato, filmes, pe�as teatrais, espet�culos de dan�a, festival gastron�mico e apresenta��o de grupos folcl�ricos, al�m de semin�rios, f�runs, workshops e rodadas de neg�cios, dentre outras atividades de cunho cultural e comercial de acordo com os crit�rios de qualidade estabelecidos na proposta do projeto.</p>"
    ShowHTML "<p align=""justify"">O Espa�o ser� aberto ao p�blico no dia 11 de junho e funcionar� diariamente, com entrada franca.</p>"
    ShowHTML "<p align=""justify"">Coordenada pelo Iphan e pela Funarte, a Mostra Nacional abrir� a programa��o do Espa�o Brasil com um programa geral da apresenta��o da cultura do pa�s em seus diversos segmentos. Na seq��ncia, o projeto abrigar� mostras dos blocos da Regi�o Sul (representada pelos Estados do Paran�, Santa Catarina, Rio Grande do Sul), da Regi�o Norte (Amazonas, Par� e Tocantins), da Regi�o Sudeste (Minas Gerais, Rio de Janeiro e Esp�rito Santo) e da Regi�o Nordeste (Cear� e Pernambuco). (A programa��o completa destes blocos ainda est� sendo fechada).</p>"

    ShowHTML "<H4>Programa��o /Mostra Nacional:</H4>"
    ShowHTML "<p align=""justify"">A mostra nacional de arte contempor�nea ser� inaugurada com duas exposi��es. A primeira que ocupa todo o t�rreo da galeria, ser� uma grande mostra individual do artista pl�stico Am�lcar de Castro. Falecido em novembro de 2002, Am�lcar ter� suas obras reunidas pela primeira vez numa grande exposi��o internacional, com esculturas, desenhos e pinturas pertencentes � cole��o M�rcio Teixeira. A segunda, que ocupar� a parte superior da galeria, abrigar� a mostra ""Proj�teis de Arte Contempor�nea"", com parte da jovem produ��o de artes visuais selecionada a partir dos projetos de exposi��es realizadas pela Funarte durante 2003 e 2004, tra�ando um programa de diversidade de linguagens e tend�ncias exercidas hoje no Brasil.</p>"
    ShowHTML "<p align=""justify""><b>Galeria de Arte Popular</b> � Abrigar� mostras com o que h� de mais significativo na representa��o da arte popular brasileira. O Iphan traz para mostra nacional uma ""viagem pelo patrim�nio brasileiro"", numa exposi��o que apresentar� o patrim�nio cultural protegido pelo Minist�rio da Cultura, em sua diversidade social e abrang�ncia tipol�gica. A mostra ter� como pano de fundo os modos caracter�sticos do Brasil e sua inser��o em ecossistemas, assim como nas maiores concentra��es urbanas do pa�s. O tema ser� abordado a partir da met�fora das embarca��es e da navega��o mar�tima, fluvial e virtual, e ser� desenvolvido por meio de objetos, registros audiovisuais e t�cnicos de computa��o gr�fica, al�m de palestras e debates.</p>"
    ShowHTML "<p align=""justify""><b>Sala especial </b> � Uma sala especial var receber a mostra ""Le Br�sil de Portinari"", que ter� apresenta��o multim�dia feita em cinco telas de grandes propor��es que se comunicam em um �nico espa�o virtual, mostrando a evolu��o da tem�tica e da t�cnica de Portinari e reconstituindo uma narrativa e um novo olhar sobre sua obra. Os principais trabalhos do artista s�o apresentados passo a passo, revelando a constru��o das personagens e ""imensa aventura de pintor de uma P�tria"". Tamb�m poder�o ser vistos uma cronobiografia ilustrada do pintor e da parte do rico material documental do Projeto Portinari. Durante a Exposi��o ser� feito o lan�amento internacional do rec�m-publicado Cat�logo <i>Raisonn� de Candido Portinari</i>, o primeiro de grande dimens�o dedicado a um artista latino-americamo, totalmente produzido e impresso no Brasil.</p>"
    ShowHTML "<p align=""justify""><b>M�sica Popular</b> � C�SAR Camargo, Wagner Tiso, Milton Nascimento, Fernanda Abreu, Martin�lia, Zeca Baleiro e Marcelo D2 s�o algumas das atra��es confirmadas na Mostra Nacional. Oito vers�es do projeto Pixinguinha, apresentadas no Brasil em 2004, tamb�m far�o parte da programa��o musical do primeiro bloco.</p>"
    ShowHTML "<p align=""justify""><b>M�sica erudita</b> - Dois projetos do Iphan sobre o compositor Heitor Villa-Lobos comp�em a programa��o de m�sica erudita da Mostra Nacional. O primeiro, intitulado ""Confer�ncia/Concerto Villa-Lobos"", consiste na realiza��o de videoconfer�ncias com grandes especialistas na obra do compositor. Simultaneamente aos debates ser�o transmitidos concertos ao vivo, dirigidos pelo Maestro Tur�bio Santos. O segundo, intitulado ""O Imagin�rio de Villa Lobos em Paris"": Pens�es d�Enfant"", inclui nove concertos com a participa��o de importantes artistas convidados, sob a dire��o do maestro Gil Jardim, e espet�culo de dan�a dirigido por Mar�lia de Andrade, que incluir� a obra <i>A Prole do Beb�</i>, a qual incluir� Sergei Diaghilev, diretor do Ballet<span style='mso-spacerun:yes'>� </span>Russes, e o compositor planejaram coreografar em 1924.</p>"
    ShowHTML "<p align=""justify""><b>Teatro</b> - Grupo Galp�o, Giramundo, Ant�nio N�brega e Michel Melamed fazem parte da Programa��o teatral da Mostra Nacional.</p>"

    ShowHTML "<h4>SERVI�O:</h4>"
    ShowHTML "<p align=""justify""><b>Espa�o Brasil</b></p>"
    ShowHTML "<p align=""justify"">"
    ShowHTML "  De 11 de junho a 25 de setembro de 2005"
    ShowHTML "  <br>Diariamente de meio dia � meia � noite"
    ShowHTML "  <br>Carreau du Temple � Rue Eug�ne Spuller, 3 �me arrondissement � Marais"
    ShowHTML "  <br>Entrada Franca"
    ShowHTML "</p>"
    ShowHTML "<p align=""justify""><b>Datas das Mostras:</b></p>"
    ShowHTML "<p align=""justify"">"
    ShowHTML "  Mostra Nacional: de 11 de junho a 3 de julho"
    ShowHTML "  <br>Mostra da Regi�o CodeSul � S�o Paulo: de 4 a 24 de julho "
    ShowHTML "  <br>Mostra de Regi�o Norte: de 25 de julho a 14 de agosto "
    ShowHTML "  <br>Mostra da Regi�o Sudeste: de 15 de agosto a 4 de setembro"
    ShowHTML "  <br>Mostra da regi�o Nordeste: de 5 a 25 de setembro"
    ShowHTML "</p>"

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de exibi��o da comiss�o
REM -------------------------------------------------------------------------
Sub Comissao

    ShowHTML "<DIV class=retranca>Comissariado</DIV>"

    ShowHTML "<center><h3>BRASILEIRO</h3></center>"
    ShowHTML "<p><b>Luiz Ign�cio Lula da Silva</b><br>Presidente da Rep�blica Federativa do Brasil</p>"
    ShowHTML "<p><b>Gilberto Gil Moreira</b><br>Ministro de Estado da Cultura</p>"
    ShowHTML "<p><b>Andr� Midani</b><br>Comiss�rio Geral</p>"
    ShowHTML "<p><b>M�rcio Meira</b><br>Representante do Minist�rio da Cultura</p>"
    ShowHTML "<p><b>Edgardi Telles</b><br>Representante do Minist�rio das Rela��es Exteriores</p>"
    ShowHTML "<p><b>Antenor Bog�a</b><br>Coordenador de Rela��es Bilaterais</p>"
    ShowHTML "<p><b>Daiana Castilho Dias</b><br>Coordenadora do Comissariado / Espa�o Brasil</p>"
    ShowHTML "<p><b>Elisa Leonel</b><br>Coordenadora / Exposi��es, Literatura,Col�quios</p>"
    ShowHTML "<p><b>Mequita Andrade</b><br>Coordenador / M�sica e Artes C�nicas</p>"
    ShowHTML "<p><b>Moema Salgado</b><br>Coordenadora / Audiovisual</p>"

    ShowHTML "<center><h3>FRANC�S</h3></center>"
    ShowHTML "<p><b>Jean-Fran�ois CHOUGNET,</b><br>Commissaire G�n�ral<br>Directeur g�n�ral de l�Etablissement Public du Parc et de la Grande Halle de la Villette</p>"
    ShowHTML "<p><b>Rapha�l BELLO,</b><br>Commissaire G�n�ral Adjoint</p>"
    ShowHTML "<p><b>Monica SENDRA,</b><br>Charg�e de mission � art contemporain et architecture �</p>"
    ShowHTML "<p><b>Renata RODEL,</b><br>Charg�e de mission � art contemporain et photographie �<br>T�l. : 33 (0)1 53 69 33 31</p>"
    ShowHTML "<p><b>Marie-Dominique BLONDY,</b><br>Charg�e de mission � colloques et litt�rature �</p>"
    ShowHTML "<p><b>Anne-Laure FLEISCHEL,</b><br>Charg�e de mission � transdisciplinaire �</p>"
    ShowHTML "<p><b>Pierre TRIAPKINE,</b><br>Charg� de mission � audiovisuel, cin�ma et sport �</p>"
    ShowHTML "<p><b>David TURSZ,</b><br>Responsable adjoint du D�partement des arts de la sc�ne</p>"
    ShowHTML "<p><b>Marie-Claude VAYSSE,</b><br>Charg�e de mission � expositions patrimoniales �</p>"
    ShowHTML "<p><b>Contact:</b> <a href=""mailto:bresil@afaa.asso.fr"">bresil@afaa.asso.fr</a></p>"

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de eventos
REM -------------------------------------------------------------------------
Sub Eventos

    w_chave = Request("w_chave")

    ' Recupera os dados da classifica��o selecionada
    DB_GetCCList RS1, w_cliente, w_chave, "SIWSOLIC"
    RS1.Filter = "sq_cc=" & w_chave

    ' Recupera os dados do servi�o de projetos
    DB_GetLinkData RS2, w_cliente, "PJCAD"

    ' Recupera a chave do usu�rio de suporte
    DB_GetUserData RS, w_cliente, "000.000.001-91"

    ' Recupera os projetos
    DB_GetSolicList RS, RS2("sq_menu"), 6782, RS2("sigla"), 3, _
       null, null, null, null, null, null, null, null, null, null, _
       null, null, null, null, null, null, null, _
       null, null, null, null, w_chave, null, null, null, null
    RS.Sort = "titulo"
    RS.Filter = "or_tramite > 4 and sg_tramite<>'CA'"

    ShowHTML "<DIV class=retranca>Eventos - " & RS1("nome") & "</DIV>"
    If RS.EOF Then
       ShowHTML "<p>N�o h� eventos cadastrados nesta categoria."
    Else
       While not RS.EOF
       
      'ShowHTML "<p><b>" & RS("titulo") & " - " & RS("sq_siw_solicitacao") & "</b>"
      ShowHTML "<p class='titulo'>" & RS("titulo") & "</b>"
      ShowHTML "   <ul>"
      ShowHTML "   <li>Descritivo: " & CRLF2BR(RS("descricao")) & "</li>"
      ShowHTML "   <li>Contato: " & Nvl(RS("nm_prop"),"n�o informado") & "</li>"
      ShowHTML "   <li>Local: " & CRLF2BR(RS("justificativa")) & "</li>"
      ShowHTML "   <li>Per�odo: " & FormataDataEdicao(RS("inicio")) & " a " & FormataDataEdicao(RS("fim")) & "</li>"
      ShowHTML "   </ul>"
      ShowHTML "</p>"
      RS.MoveNext
       Wend
    End If

    DesconectaBD
    RS1.Close
    RS2.Close
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de fale conosco
REM -------------------------------------------------------------------------
Sub Fale

    ScriptOpen "JavaScript"
    ValidateOpen "Validacao"
    Validate "w_nome", "Nome", "", "1", 2, 80, "1", "1"
    Validate "w_email", "e-Mail", "", "1", 2, 80, "1", "1"
    ShowHTML "  var i; "
    ShowHTML "  var w_erro=true; "
    ShowHTML "  for (i=0; i < theForm.w_pessoa.length; i++) {"
    ShowHTML "if (theForm.w_pessoa[i].checked) w_erro=false;"
    ShowHTML "  }"
    ShowHTML "  if (w_erro) {"
    ShowHTML "alert('Informe se � pessoa f�sica ou jur�dica!'); "
    ShowHTML "return false;"
    ShowHTML "  }"
    ShowHTML "  var w_erro=true; "
    ShowHTML "  for (i=0; i < theForm.w_deseja.length; i++) {"
    ShowHTML "if (theForm.w_deseja[i].checked) w_erro=false;"
    ShowHTML "  }"
    ShowHTML "  if (w_erro) {"
    ShowHTML "alert('Informe o que deseja!'); "
    ShowHTML "return false;"
    ShowHTML "  }"
    ShowHTML "  var w_erro=true; "
    ShowHTML "  for (i=0; i < theForm.w_nucleo.length; i++) {"
    ShowHTML "if (theForm.w_nucleo[i].checked) w_erro=false;"
    ShowHTML "  }"
    ShowHTML "  if (w_erro) {"
    ShowHTML "alert('Informe a que n�cleo deve ser dirigida sua mensagem!'); "
    ShowHTML "return false;"
    ShowHTML "  }"
    Validate "w_mensagem", "Mensagem", "1", 1, 5, 2000, "1", "1"
    ValidateClose
    ScriptClose

    ShowHTML "<DIV class=retranca>Fale conosco</DIV>"
    ShowHTML "<p>Preencha o formul�rio abaixo para enviar sua mensagem.</p>"
    ShowHTML "<table border=0 width=""100%"" cellspacing=0><tr bgcolor=""" & conTrBgColor & """><td style=""border: 1px solid rgb(0,0,0);"">"
    ShowHTML "<table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_Pagina & "Envia", "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,w_pagina&"Fale","E"
    ShowHTML " <tr><td colspan=2><b>Informe seu nome:</b><br><INPUT class=""sti"" type=""text"" name=""w_nome"" size=""65"" maxlength=""80""></td>"
    ShowHTML " <tr><td colspan=2><b>Informe seu e-mail:</b><br><INPUT class=""sti"" type=""text"" name=""w_email"" size=""65"" maxlength=""80""></td>"
    ShowHTML " <tr valign=""top"" >"
    ShowHTML "   <td><b>Pessoa:</b><br>"
    ShowHTML " <input type=""radio"" name=""w_pessoa"" value=""F�sica""> F�sica<br>"
    ShowHTML " <input type=""radio"" name=""w_pessoa"" value=""Jur�dica""> Jur�dica"
    ShowHTML "   <td><b>O que deseja:</b>"
    ShowHTML " <table border=0 width=""90%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "   <td>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Perguntar""> Perguntar<br>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Reclamar""> Reclamar<br>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Reclamar""> Elogiar<br>"
    ShowHTML "   <td>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Criticar""> Criticar<br>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Solicitar""> Solicitar<br>"
    ShowHTML " <input type=""radio"" name=""w_deseja"" value=""Sugerir""> Sugerir"
    ShowHTML "   </table>"
    ShowHTML " <tr><td colspan=2><b>Encaminhar para:</b><br>"
    DB_GetUorgList RS, w_cliente, null, "IS NULL", null, null
    RS.Filter = "sigla='BR'"

    DB_GetUorgList RS1, w_cliente, RS("sq_unidade"), "NIVEL", null, null
    RS1.Sort = "nome"
    While Not RS1.EOF
       ShowHTML " <input type=""radio"" name=""w_nucleo"" value=""" & RS1("sq_unidade") & """>" & RS1("nome") & "<br>"
       RS1.MoveNext
    Wend
    DesconectaBD
    RS1.Close
    ShowHTML " <tr><td colspan=2><b>Mensagem:</b><br><textarea name=""w_mensagem"" class=""sti"" ROWS=5 cols=58></TEXTAREA></td>"
    ShowHTML " <tr><td colspan=2 align=center><input class=""stb"" type=""submit"" name=""Botao"" value=""Enviar mensagem"">"
    ShowHTML " </table>"
    ShowHTML "</FORM>"
    ShowHTML " </table>"

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de prepara��o para envio de e-mail
REM -------------------------------------------------------------------------
Sub Envia

     Dim w_cab, w_html, w_texto, w_resultado, w_destinatarios
     Dim w_assunto, w_assunto1, w_nome
      
     w_destinatarios = ""
     w_resultado = ""
     w_assunto   = conSgSistema & " - Mensagem vinda de Fale Conosco"
      
     w_html = ""
     w_html = w_html & VbCrLf & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
     w_html = w_html & VbCrLf & "<table width=""97%"" border=""0"">" & VbCrLf
     w_html = w_html & VbCrLf & "  <tr valign=""top""><td align=""center""><font size=2><b>Mensagem enviada atrav�s da se��o Fale Conosco</b></font><hr><td></tr>" & VbCrLf
     w_html = w_html & VbCrLf & "</table>"

     w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td>"
     w_html = w_html & VbCrLf & "<table width=""99%"" border=""0"">"
     w_html = w_html & VbCrLf & "  <tr valign=""top"">"
     w_html = w_html & VbCrLf & "<td><font size=2>Nome:<br><b>" & Request("w_nome") & "</b></font></td>"
     w_html = w_html & VbCrLf & "<td><font size=2>e-Mail:<br><b>" & Request("w_email") & "</b></font></td>"
     w_html = w_html & VbCrLf & "  <tr valign=""top"">"
     w_html = w_html & VbCrLf & "<td><font size=2>Tipo de pessoa:<br><b>" & Request("w_pessoa") & "</b></td>"
     w_html = w_html & VbCrLf & "<td><font size=2>Deseja:<br><b>" & Request("w_deseja") & "</b></td>"

     w_html = w_html & VbCrLf & "  <tr><td colspan=2><font size=2>Mensagem:<br><b>" & Request("w_mensagem") & " </b></td>"
     w_html = w_html & VbCrLf & "</table>" & VbCrLf
     w_html = w_html & VbCrLf & "</td></tr>" & VbCrLf
     w_html = w_html & VbCrLf & "</table>" & VbCrLf

     ' Configura os destinat�rios da mensagem
     w_destinatarios = "alexandrepapadopolis@yahoo.com.br" & "; "
     

     'Recupera o e-mail do titular e do substituto pelo setor respons�vel
     DB_GetUorgResp RS, Request("w_nucleo")
     If Instr(w_destinatarios,RS("email_titular") & "; ") = 0and Nvl(RS("email_titular"),"nulo") <> "nulo"Then w_destinatarios = w_destinatarios & RS("email_titular") & "; "End If
     If Instr(w_destinatarios,RS("email_substituto") & "; ") = 0 and Nvl(RS("email_substituto"),"nulo") <> "nulo" Then w_destinatarios = w_destinatarios & RS("email_substituto") & "; " End If
     DesconectaBD
      
     ShowHTML "<DIV class=retranca>Envio de mensagem</DIV>"
     ShowHTML "<p>A mensagem abaixo foi enviada aos respons�veis pelo N�cleo indicado. Obrigado.</p>"
     ShowHTML "<p>"
     ShowHTML w_html
     ShowHTML "</p>"
      
     w_html = "<HTML>" & VbCrLf & _
      BodyOpenMail(null) & VbCrLf & _
      w_html & _
      "</BODY>" & VbCrLf & _
      "</HTML>" & VbCrLf
     If w_destinatarios > "" Then
        ' Executa o envio do e-mail
        w_resultado = EnviaMailSender(w_assunto, w_html, w_destinatarios, Request("w_email"), Request("w_nome"))
     End If

     ' Se ocorreu algum erro, avisa da impossibilidade de envio
     'If w_resultado > "" Then 
     '   ScriptOpen "JavaScript"
     '   ShowHTML "  alert('ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n" & w_resultado & "');" 
     '   ScriptClose
     'End If

     Set w_html             = Nothing
     Set w_destinatarios    = Nothing
     Set w_assunto          = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da prepara��o para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de contatos
REM -------------------------------------------------------------------------
Sub Contatos

    ShowHTML "<DIV class=retranca>Contatos</DIV>"
    ShowHTML "<center><h3>N�CLEOS</h3></center>"
    ShowHTML "<p><b>a. Espa�o Brasil;"
    ShowHTML "  <br>b. Exposi��es, Literatura e Col�quios;"
    ShowHTML "  <br>c. M�sica e Artes C�nicas;"
    ShowHTML "  <br>d. Audiovisual;"
    ShowHTML "  <br>e. Administrativo � CESPE / Coordena��o."
    ShowHTML "</b></p>"


    ShowHTML "<center><h3>COORDENA��O</h3></center>"
    ShowHTML "<p><b>a.    Daiana Castilho Dias</b>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;i.    Gerente: Itanamara de Medeiros Mesquita"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;ii.    Assessoria: Cec�lia de Almeida Costa"
    ShowHTML "</p>"

    ShowHTML "<p><b>b.    Eliza Leonel</b>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;i.    2.1 Exposi��es:"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;ii.    Gerente: Nina do Valle"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;iii.    Assistente: Jo�o Luis Lopes"
    ShowHTML "   <br>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;iv.    2.2 Literatura e Col�quio"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;v.    Gerente: Gelly Saigg"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;vi.    Assistentes:  Margareth Beserra , Nath�lia Paiva "
    ShowHTML "</p>"

    ShowHTML "<p><b>c.    Antonieta Andrade</b>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;i.    Gerente: Gelly Saigg"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;ii.    Assistentes: Margareth Beserra, Nath�lia Paiva"
    ShowHTML "</p>"

    ShowHTML "<p><b>d.    Moema Salgado</b>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;i.    Gerente: Adriana Scorzelli Rattes"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;ii.    Assistente: Vikbirkbeck"
    ShowHTML "</p>"

    ShowHTML "<p><b>e.    Coordenadora Orieta Maria Porto</b>"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;i.    Cont�bil Financeiro: Adriana Fabiana Rodrigues"
    ShowHTML "   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Jur�dico: Peter Alexander Lange"
    ShowHTML "</p>"
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de proposta
REM -------------------------------------------------------------------------
Sub Proposta
    w_chave = Request("w_chave")

    ' Recupera os dados do servi�o de projetos
    DB_GetLinkData RS2, w_cliente, "PJCAD"

    ' Recupera os projetos
    DB_GetSolicList RS, RS2("sq_menu"), null, RS2("sigla"), 5, _
       null, null, null, null, null, null, null, null, null, null, _
       null, null, null, null, null, null, null, _
       null, null, null, null, null, null, null, null, null
    RS.Sort = "titulo"
    RS.Filter = "sg_tramite<>'CA' and outra_parte <> null"

    ShowHTML "<DIV class=retranca>Cadastro de propostas</DIV>"
    ShowHTML "<p align=""justify""><b>ATEN��O: Esta tela � de interesse exclusivo de proponentes e seus respons�veis. Ao p�blico em geral � poss�vel consultar os dados dos eventos usando a op��o ""Programa��o"", dispon�vel no menu horizontal, na parte de cima desta tela.</b></p>"
    ShowHTML "<p align=""justify"">Antes de cadastrar propostas ou acompanhar projetos do seu interesse voc� deve criar uma senha de acesso ao sistema, seguindo os passos abaixo:</p>"
    ShowHTML "<ol>"
    ShowHTML "  <li>Clique sobre o nome de um dos projetos constantes da lista abaixo;"
    ShowHTML "  <li>Ser�o solicitados alguns dados para confirma��o da senha, tais como CNPJ/CPF do proponente, CPF do respons�vel e n�mero do PRONAC. Tenha � m�o estes dados;"
    ShowHTML "  <li>Ap�s a cria��o da senha, voc� receber� um e-mail confirmando seu acesso e instruindo como acessar o sistema."
    ShowHTML "</ol>"

    ShowHTML "<h4>Lista de projetos com ativa��o de senha pendente</h4>"
    If RS.EOF Then
       ShowHTML "<p><b>Nenhum projeto localizado</b></p>"
    Else
       w_cont = 0
       While not RS.EOF
          DB_GetAcordoRep RS1, RS("sq_siw_solicitacao"), w_cliente, null, null
          If NOT RS1.EOF Then
             DB_GetBenef RS3, w_cliente, RS1("sq_pessoa"), null, null, null, null, null, null
             If Nvl(RS3("email"),"") = "" Then
                If w_cont = 0 Then
                   ShowHTML "<ul>"
                   w_cont = 1
                End If
                ShowHTML "<li><a class=""hl"" href=""" & w_pagina & "usuario&w_chave=" & RS("sq_siw_solicitacao") & """>" & RS("titulo") & "</a> (" & RS("nm_prop") & ")"
             End If
          End If
          RS.MoveNext
       Wend
       If w_cont = 1 Then
          ShowHTML "</ul>"
       Else
          ShowHTML "<p><b>Nenhum projeto pendente de ativa��o da senha de acesso.</b></p>"
       End If
    End If

    DesconectaBD
    RS2.Close
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de confirma��o de dados para ativa��o de senha de proponente
REM -------------------------------------------------------------------------
Sub Usuario
    w_chave = Request("w_chave")

    ' Recupera os dados do projeto informado
    DB_GetSolicData RS, w_chave, "PJGERAL"

    ScriptOpen "JavaScript"
    CheckBranco
    FormataData
    Modulo
    FormataCPF
    FormataCNPJ
    ValidateOpen "Validacao"
    If Nvl(RS("palavra_chave"),"") > "" Then
       Validate "w_palavra_chave", "N� do Pronac", "", "1", 2, 20, "", "0123456789"
    End If
    If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then ' Se pessoa f�sica
       Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
    ElseIf cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 Then ' Se pessoa jur�dica
       Validate "w_cnpj", "CNPJ", "CNPJ", "1", "18", "18", "", "0123456789/-."
       Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
    End If
    Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
    Validate "w_rg_emissao", "Data de emiss�o", "", "1", 10, 10, "", "0123456789/"
    Validate "w_rg_emissor", "�rg�o expedidor", "1", 1, 2, 30, "1", "1"
    Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
    Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
    Validate "w_nr_fax", "Fax", "1", "", 7, 25, "1", "1"
    Validate "w_nr_celular", "Celular", "1", "", 7, 25, "1", "1"
    Validate "w_email", "E-Mail", "1", "1", 4, 60, "1", "1"
    ValidateClose
    ScriptClose
    ShowHTML "<DIV class=retranca>Ativa��o de senha</DIV>"
    ShowHTML "<p align=""justify""><b>ATEN��O: Esta tela � de interesse exclusivo de proponentes e seus respons�veis. Ao p�blico em geral � poss�vel consultar os dados dos eventos usando a op��o ""Programa��o"", dispon�vel no menu horizontal, na parte de cima desta tela.</b></p>"
    ShowHTML "<p align=""justify"">Informe os dados solicitados abaixo e clique no bot�o ""Ativar senha"". Os campos em negrito s�o <b>obrigat�rios</b></p>"

    If RS.EOF Then
       ShowHTML "<p><b>Nenhum projeto localizado</b></p>"
    Else
       w_cont = 0
       While not RS.EOF
          DB_GetAcordoRep RS1, RS("sq_siw_solicitacao"), w_cliente, null, null
          If NOT RS1.EOF Then
             DB_GetBenef RS3, w_cliente, RS1("sq_pessoa"), null, null, null, null
             If Nvl(RS3("email"),"") = "" Then
                AbreForm "Form", w_Pagina & "Verifica", "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,w_pagina&"Fale","E"
                ShowHTML "<input type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
                ShowHTML "<table border=0 width=""100%"" cellspacing=0><tr bgcolor=""" & conTrBgColor & """><td style=""border: 1px solid rgb(0,0,0);"" align=""left"">"
                ShowHTML "<table border=0 width=""100%"" cellspacing=0>"
                ShowHTML " <tr><td colspan=2>Projeto:<b>" & RS("titulo") & "</td>"
                ShowHTML " <tr><td colspan=2>&nbsp;</td>"
                If Nvl(RS("palavra_chave"),"") > "" Then
                   ShowHTML " <tr><td colspan=2>Informe o n� do Pro<u>n</u>ac deste projeto:<br><INPUT class=""sti"" type=""text"" name=""w_palavra_chave"" size=""20"" maxlength=""20""></td>"
                End If
                If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then ' Se pessoa f�sica
                   ShowHTML " <tr><td colspan=2>Informe o CPF do proponente <b> (" & RS3("nm_pessoa") & ")</b>:</b><br><INPUT class=""sti"" type=""text"" name=""w_cpf"" size=""14"" maxlength=""14"" onKeyDown=""FormataCPF(this,event);""></td>"
                ElseIf cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 Then ' Se pessoa jur�dica
                   ShowHTML " <tr><td colspan=2><b>CNPJ do proponente</b>  (" & RS("nm_prop") & "):</b><br><INPUT class=""sti"" type=""text"" name=""w_cnpj"" size=""18"" maxlength=""18"" onKeyDown=""FormataCNPJ(this,event);""></td>"
                   ShowHTML " <tr><td colspan=2><b>CPF do respons�vel</b> (" & RS3("nm_pessoa") & "):</b><br><INPUT class=""sti"" type=""text"" name=""w_cpf"" size=""14"" maxlength=""14"" onKeyDown=""FormataCPF(this,event);""></td>"
                End If
                ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
                ShowHTML "          <tr valign=""top"">"
                ShowHTML "          <TD><b>Identidade:</b><br><input accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80""></td>"
                ShowHTML "          <TD><b>Data de emiss�o:</b><br><input accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" onKeyDown=""FormataData(this,event);""></td>"
                ShowHTML "          <TD><b>�rg�o emissor:</b><br><input accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30""></td>"
                ShowHTML "          </table>"

                ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
                ShowHTML "          <TD><b>DDD:</b><br><input accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4""></td>"
                ShowHTML "          <TD><b>Telefone:</b><br><input accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40""></td>"
                ShowHTML "          <TD title=""Opcional"">Fax:<br><input accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20""></td>"
                ShowHTML "          <TD title=""Opcional"">Celular:<br><input accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20""></td>"
                ShowHTML "          <tr><TD colspan=4><b>e-Mail:</b><br><input accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50""></td>"
                ShowHTML "          </table>"
                ShowHTML " <tr><TD colspan=2 align=""center"" height=""1"" bgcolor=""#000000""></TD></TR>"
                ShowHTML " <tr><td colspan=2 align=center><input class=""stb"" type=""submit"" name=""Botao"" value=""Ativar senha"">"
                ShowHTML " </table>"
                ShowHTML " </table>"
                ShowHTML "</FORM>"
             End If
          End If
          RS.MoveNext
       Wend
    End If
    ShowHTML "  <a href=""javascript:history.back(1);""><img border=0 src=""img/bt_voltar.gif""></a>"

    DesconectaBD
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da verifica��o de dados para ativa��o de senha de proponente
REM -------------------------------------------------------------------------
Sub Verifica
    w_chave  = Request("w_chave")
    w_erro   = 0
    w_string = ""

    DB_GetSolicData RS, w_chave, "PJGERAL"
    DB_GetBenef RS2, w_cliente, RS("outra_parte"), null, null, null, null
    DB_GetAcordoRep RS1, RS("sq_siw_solicitacao"), w_cliente, null, null
    DB_GetBenef RS3, w_cliente, RS1("sq_pessoa"), null, null, null, null
    If Nvl(RS("palavra_chave"),"") > "" Then
       If RS("palavra_chave") <> Nvl(Request("w_palavra_chave"),"00") Then
          w_string = w_string & "  <li>O n�mero do PRONAC n�o corresponde ao informado.</li>" & VbCrLf
       End If
    End If
    If cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 1 Then ' Se pessoa f�sica
       If RS3("cpf") <> Nvl(Request("w_cpf"),"00") Then
          w_string = w_string & "  <li>O CPF do proponente n�o corresponde ao informado.</li>" & VbCrLf
       End If
    ElseIf cDbl(Nvl(RS("sq_tipo_pessoa"),0)) = 2 Then ' Se pessoa jur�dica
       If RS2("cnpj") <> Nvl(Request("w_cnpj"),"00") Then
          w_string = w_string & "  <li>O CNPJ do proponente n�o corresponde ao informado.</li>" & VbCrLf
       End If
       If RS3("cpf") <> Nvl(Request("w_cpf"),"00") Then
          w_string = w_string & "  <li>O CPF do respons�vel n�o corresponde ao informado.</li>" & VbCrLf
       End If
    End If
    
    If w_string > "" Then
       ShowHTML "  <p class=""titulo"" align=""justify""><b>ATEN��O</b>: N�o ser� poss�vel ativar a senha para o respons�vel deste projeto em fun��o dos erros apontados abaixo. Volte � tela anterior e fa�a as corre��es necess�rias.</p>"
       ShowHTML "  <ul>"
       ShowHTML w_string
       ShowHTML "  </ul>"
       ShowHTML "  <a href=""javascript:history.back(1);""><img border=0 src=""img/bt_voltar.gif""></a>"
    Else
       ShowHTML "  <p class=""titulo"" align=""justify""><b>Senha ativada com sucesso</b>.<hr></p>"
       ShowHTML "  <p align=""justify"">Voc� receber� um e-mail nos pr�ximos minutos informando os dados para acesso.</p>"
       ShowHTML "  <p align=""justify"">O seu acesso permite cadastrar e acompanhar todos os projetos pelos quais responde, sem necessidade de criar uma senha para cada um deles.</p>"
    End If
    DesconectaBD
    RS1.Close
    RS2.Close
    RS3.Close
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de acompanhamento
REM -------------------------------------------------------------------------
Sub Consulta

    ShowHTML "<DIV class=retranca>Acompanhamento</DIV>"
    ShowHTML "<p align=""justify""><b>ATEN��O: Esta tela � de interesse exclusivo de proponentes e seus respons�veis. Ao p�blico em geral � poss�vel consultar os dados dos eventos usando a op��o ""Programa��o"", dispon�vel no menu horizontal, na parte de cima desta tela.</b></p>"
    ShowHTML "<p align=""justify"">Para os proponentes, o acompanhamento � feito informando o CPF do proponente e a senha de acesso ao sistema, localizados na parte superior da tela.</p>"
    ShowHTML "<p align=""justify"">Se voc� � um proponente, ou respons�vel por um deles, e n�o tem senha de acesso, use a op��o ""Cadastro de propostas"" no menu lateral � esquerda desta tela.</p>"
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da p�gina de logomarcas
REM -------------------------------------------------------------------------
Sub Logo

    ShowHTML "<DIV class=retranca>Logomarcas</DIV>"
    ShowHTML "<A href=""http://www.cespe.unb.br"" target=""_blank""><img src=""../cespe_novo/img/logo_cespe.jpg"" border=1>"
    ShowHTML "<A href=""http://www.varig.com.br"" target=""_blank""><img src=""../cespe_novo/img/logo_varig.jpg"" border=1>"
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  
    Select Case Par
      Case "INICIAL"      Inicial
      Case "APRESENTACAO" Apresentacao
      Case "COMISSAO"     Comissao
      Case "FALE"         Fale
      Case "ENVIA"        Envia
      Case "CONTATOS"     Contatos
      Case "EVENTO"       Eventos
      Case "PROPOSTA"     Proposta
      Case "CONSULTA"     Consulta
      Case "USUARIO"      Usuario
      Case "VERIFICA"     Verifica
      Case "LOGO"         Logo
      Case Else
         ShowHTML "<center><br><br><br><br><br><br><br><br><br><br><img src=""/siw/images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center>"
    End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

