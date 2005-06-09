<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_lc_pub\DB_Portal.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_lc_pub\Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_lc_pub\DB_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Default.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o portal de licita��es
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM


' Declara��o de vari�veis
Dim dbms, sp, RS, RS1
Dim P1, P2, P3, P4, TP, SG, w_cliente
Dim w_pagina, w_chave
  
Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
   
Par          = Ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))



If par = "" Then
   Par = "INICIAL"
End If

w_pagina     = "Default.asp?par="    

w_cliente     = RetornaCliente()


Main

FechaSessao

Set RS            = Nothing
Set RS1           = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set w_cliente     = Nothing


REM =========================================================================
REM Rotina de visualiza��o da p�gina inicial do portal
REM -------------------------------------------------------------------------
Sub Inicial

   Dim w_hoje, w_hdia, w_hmes, w_hano
   Dim w_nomemes(12)
   
   w_hoje = Date
   w_hdia = Day(w_hoje)
   w_hmes = Month(w_hoje)
   w_hano = Year(w_hoje)
   If w_hdia < 10 Then
      w_hdia = "0" & w_hdia
   End If
        
   w_nomemes(0) = "Janeiro"
   w_nomemes(1) = "Fevereiro"
   w_nomemes(2) = "Mar�o"
   w_nomemes(3) = "Abril"
   w_nomemes(4) = "Maio"
   w_nomemes(5) = "Junho"
   w_nomemes(6) = "Julho"
   w_nomemes(7) = "Agosto"
   w_nomemes(8) = "Setembro"
   w_nomemes(9) = "Outubro"
   w_nomemes(10) = "Novembro"
   w_nomemes(11) = "Dezembro"

   ShowHTML "<HTML>"
   ShowHTML "<HEAD>"
   ShowHTML "<TITLE>PORTAL DE LICITA��ES E CONTRATOS ADMINISTRATIVOS :: </TITLE>"
   ShowHTML "<META HTTP-EQUIV=""Content-Type"" CONTENT=""text/html; charset=iso-8859-1"">"
   ShowHTML "<style>"
   ShowHTML "    a:link{color:""#FFFFFF"";text-decoration:none}"
   ShowHTML "    a:visited{color:""#FFFFFF"";text-decoration:none}"
   ShowHTML "    a:hover{color:""#25687E"";text-decoration:none}"
   ShowHTML "</style>"
   ShowHTML "<base target=""principal"">"
   ShowHTML "</HEAD>"
   ShowHTML "<BODY BGCOLOR=#FFFFFF leftmargin=""0"" topmargin=""5"" link=""#FFFFFF"" vlink=""#FFFFFF"" alink=""#FFFFFF"">"
      
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
   ShowHTML "               <p align=""left""><b><font face=""Verdana"" color=""#FFFFFF"" size=""4"">Portal de Licita��es e Contratos Administrativos</font></b></p>"
   ShowHTML "            </td>"
   ShowHTML "            <td width=""14"" height=""62"">"
   ShowHTML "              <p align=""right""><img border=""0"" src=""images/topo_borda.jpg"" width=""14"" height=""62""></td>"
   ShowHTML "          </tr>"
   ShowHTML "        </table>"
   ShowHTML "      </td>"
   ShowHTML "    </tr>"
   ShowHTML "   <center>"
   ShowHTML "   <tr>"
   ShowHTML "     <td width=""100%"" height=""20"">"
   ShowHTML "       <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" background=""images/borda_fora.jpg"" height=""20"">&nbsp;</td>"
   ShowHTML "           <td height=""20"">"
   ShowHTML "             <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" bgcolor=""#FFFFFF"" height=""20"">"
   ShowHTML "               <tr>"
   ShowHTML "                 <td height=""22"" width=""140"" bgcolor=""#FFFFFF"">&nbsp;</td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                       <tr>"
   ShowHTML "                         <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         <td bgcolor=""#3591B3"">"
   ShowHTML "                           <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" &w_pagina& "home"">HOME</a></b></font></p>"
   ShowHTML "                         </td>"
   ShowHTML "                         <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       </tr>"
   ShowHTML "                     </table>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                       <tr>"
   ShowHTML "                         <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         <td bgcolor=""#3591B3"">"
   ShowHTML "                           <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" &w_pagina& "oque"">O QUE �</a></b></font></p>"
   ShowHTML "                         </td>"
   ShowHTML "                         <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       </tr>"
   ShowHTML "                     </table>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                       <tr>"
   ShowHTML "                         <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         <td bgcolor=""#3591B3"">"
   ShowHTML "                           <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" &w_pagina& "licitacoes"">LICITA��ES</a></b></font></td>"
   ShowHTML "                         <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       </tr>"
   ShowHTML "                     </table>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                       <tr>"
   ShowHTML "                         <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         <td bgcolor=""#3591B3"">"
   ShowHTML "                           <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" &w_pagina& "FinalidadeCont"">CONTRATOS</a></b></font></td>"
   ShowHTML "                         <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       </tr>"
   ShowHTML "                     </table>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                       <tr>"
   ShowHTML "                         <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         <td bgcolor=""#3591B3"">"
   ShowHTML "                           <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" &w_pagina& "legis"">LEGISLA��O</a></b></font></td>"
   ShowHTML "                         <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       </tr>"
   ShowHTML "                     </table>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td height=""22"" align=""center"">"
   ShowHTML "                   <div align=""center"">"
   ShowHTML "                     <center>"
   ShowHTML "                       <table border=""0"" cellspacing=""0"" cellpadding=""0"" width=""100%"">"
   ShowHTML "                         <tr>"
   ShowHTML "                           <td width=""8""><img border=""0"" src=""images/menu_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                           <td bgcolor=""#3591B3"">"
   ShowHTML "                             <p align=""center""><font face=""Verdana"" size=""1"" color=""#FFFFFF""><b><a href=""" & w_pagina & "links"">LINKS</a></b></font></td>"
   ShowHTML "                           <td><img border=""0"" src=""images/menu_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                         </tr>"
   ShowHTML "                       </table>"
   ShowHTML "                     </center>"
   ShowHTML "                   </div>"
   ShowHTML "                 </td>"
   ShowHTML "               </tr>"
   ShowHTML "             </table>"
   ShowHTML "           </td>"
   ShowHTML "           <td width=""19"" background=""images/borda_fora_d.jpg"" height=""22""><img border=""0"" src=""images/borda_fora_d.jpg"" width=""19"" height=""1""></td>"
   ShowHTML "         </tr>"
   ShowHTML "       </table>"
   ShowHTML "     </td>"
   ShowHTML "   </tr>"
   ShowHTML "   <tr>"
   ShowHTML "     <td width=""100%"" height=""5"">"
   ShowHTML "       <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""5"">"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" background=""images/borda_fora.jpg"" height=""5"">&nbsp;</td>"
   ShowHTML "           <td height=""5"" bgcolor=""#FFFFFF"">"
   ShowHTML "             &nbsp;"
   ShowHTML "           </td>"
   ShowHTML "           <td width=""19"" background=""images/borda_fora_d.jpg"" height=""5""><img border=""0"" src=""images/borda_fora_d.jpg"" width=""19"" height=""1""></td>"
   ShowHTML "         </tr>"
   ShowHTML "       </table>"
   ShowHTML "     </td>"
   ShowHTML "   </tr>"
   ShowHTML "   <tr>"
   ShowHTML "     <td width=""100%"" height=""22"">"
   ShowHTML "       <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""22"">"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" height=""22"" background=""images/borda_fora.jpg"">&nbsp;</td>"
   ShowHTML "           <td height=""22"">"
   ShowHTML "             <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "               <tr>"
   ShowHTML "                 <td width=""40""></td>"
   ShowHTML "                 <td>"
   ShowHTML "                   <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"">"
   ShowHTML "                      <tr>"
   ShowHTML "                       <td width=""8""><img border=""0"" src=""images/borda_data_e.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                       <td bgcolor=""#3591B3"">"
   ShowHTML "             <p align=""center""><font size=""1"" face=""Verdana"" color=""#ffffff"">"
	ShowHTML "        <p align=""center"">Bras�lia-DF, " & w_hdia & " de " & w_nomemes(w_hmes-1) & " de " & w_hano & "</p>"
   ShowHTML "                       </td>"
   ShowHTML "                       <td width=""8""><img border=""0"" src=""images/borda_data_d.jpg"" width=""8"" height=""22""></td>"
   ShowHTML "                     </tr>"
   ShowHTML "                   </table>"
   ShowHTML "                 </td>"
   ShowHTML "                 <td width=""450""></td>"
   ShowHTML "               </tr>"
   ShowHTML "             </table>"
   ShowHTML "           </td>"
   ShowHTML "           <td background=""images/borda_fora_d.jpg"" width=""19"" height=""22"">&nbsp;</td>"
   ShowHTML "         </tr>"
   ShowHTML "       </table>"
   ShowHTML "     </td>"
   ShowHTML "   </tr>"
   ShowHTML "   <tr>"
   ShowHTML "     <td width=""100%"">"
   ShowHTML "       <table border=""0"" width=""100%"" cellspacing=""0"" cellpadding=""0"" height=""100%"">"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" height=""15"" background=""images/borda_fora.jpg""><img border=""0"" src=""images/15h.gif"" width=""1"" height=""15""></td>"
   ShowHTML "           <td width=""18"" height=""15"" valign=""top""><img border=""0"" src=""images/dentro_canto_sup_e.jpg"" width=""18"" height=""15""></td>"
   ShowHTML "           <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
   ShowHTML "           <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
   ShowHTML "           <td height=""15"" background=""images/dentro_topo.jpg""><img border=""0"" src=""images/dentro_topo.jpg"" width=""2"" height=""15""></td>"
   ShowHTML "           <td height=""15"" width=""18"" valign=""top""><img border=""0"" src=""images/dentro_canto_sup_d.jpg"" width=""18"" height=""15""></td>"
   ShowHTML "           <td width=""19"" height=""15"" background=""images/borda_fora_d.jpg""><img border=""0"" src=""images/15h.gif"" width=""1"" height=""15""></td>"
   ShowHTML "         </tr>"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" background=""images/borda_fora.jpg"" height=""100%"">&nbsp;</td>"
   ShowHTML "           <td width=""18"" background=""images/dentro_borda_e.jpg"" height=""100%"">&nbsp;</td>"
   ShowHTML "           <td colspan=""3"" height=""100%""><div style=""OVERFLOW: auto; Z-INDEX: -300""> "
   ShowHTML "      <p align=""center""> "
   ShowHTML "      <iframe src=""" & w_pagina & "home"" width=""100%"" height=""100%"" border=""0"" leftMargin=""0"" topMargin=""0"" marginheight=""0"" marginwidth=""0"" frameborder=""0"" framespacing=""0"" name=""principal"" noresize oncontextmenu=""return false"" allowtransparency> "
   ShowHTML "      </p></iframe></td>"
   ShowHTML "           <td width=""18"" background=""images/dentro_borda_d.jpg"" height=""100%"">&nbsp;</td>"
   ShowHTML "           <td width=""19"" background=""images/borda_fora_d.jpg"" height=""100%"">&nbsp;</td>"
   ShowHTML "         </tr>"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" background=""images/borda_fora.jpg"" height=""17"">&nbsp;</td>"
   ShowHTML "           <td width=""18"" valign=""top"" height=""17""><img border=""0"" src=""images/dentro_canto_inf_e.jpg"" width=""18"" height=""17""></td>"
   ShowHTML "           <td colspan=""3"" background=""images/dentro_fundo.jpg"" height=""17"">&nbsp;</td>"
   ShowHTML "           <td width=""18"" valign=""top"" height=""17""><img border=""0"" src=""images/dentro_canto_inf_d.jpg"" width=""18"" height=""17""></td>"
   ShowHTML "           <td width=""19"" background=""images/borda_fora_d.jpg"" height=""17"">&nbsp;</td>"
   ShowHTML "         </tr>"
   ShowHTML "         <tr>"
   ShowHTML "           <td width=""21"" height=""23""><img border=""0"" src=""images/canto_inf_e.jpg"" width=""21"" height=""23""></td>"
   ShowHTML "           <td width=""18"" background=""images/borda_fora_fundo.jpg"" height=""23"">&nbsp;</td>"
   ShowHTML "           <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
   ShowHTML "           <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
   ShowHTML "           <td background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
   ShowHTML "           <td width=""18"" background=""images/borda_fora_fundo.jpg"" height=""23""></td>"
   ShowHTML "           <td width=""19"" height=""23""><img border=""0"" src=""images/canto_inf_d.jpg"" width=""19"" height=""23""></td>"
   ShowHTML "         </tr>"
   ShowHTML "       </table>"
   ShowHTML "     </td>"
   ShowHTML "   </tr>"
   ShowHTML " </table>"
   ShowHTML "</center>"
   ShowHTML "</div>"
   ShowHTML "</BODY>"
   ShowHTML "</HTML>"
End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visulaliza��o da p�gina home do portal
REM -------------------------------------------------------------------------
Sub Home

ShowHTML "<html>"

ShowHTML "<head>"
ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
ShowHTML "<title>HOME</title>"
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
ShowHTML "<body bgcolor=""#FFFFFF"" topmargin=""2"" leftmargin=""10"" text=""#266882"">"

ShowHTML "<div align=""center"">"
ShowHTML "  <table border=""0"" cellspacing=""0"" width=""100%"" cellpadding=""10"" height=""177"">"
ShowHTML "  <tr>"
ShowHTML "    <center>"
ShowHTML "      <td width=""560"" height=""157"">"
ShowHTML "          <p align=""center""><img border=""0"" src=""images/imagem_licitacao2.jpg"" width=""209"" height=""190""><br>"
ShowHTML "          <font size=""1"" face=""Verdana"">Regras b�sicas sobre&nbsp;Licita��o e&nbsp;<br>"
ShowHTML " Contratos Administrativos. <a href=""arquivos/pdf/licitacao_regras.pdf"" target=""_blank"">Leia mais..</a></font></p> "
ShowHTML "        </td>"
ShowHTML "  </center>"
ShowHTML "  <td width=""164"" align=""center"" valign=""middle"" height=""153"">"
ShowHTML "      <center>"
ShowHTML "        </center>"
ShowHTML "      <p align=""center""><img border=""0"" src=""images/banner_mini-comprasnet2.gif"" width=""150"" height=""77""></p>"
ShowHTML "      <p align=""center""><img border=""0"" src=""images/planejamento2.jpg"" width=""150"" height=""76""></p>"
ShowHTML "      <p align=""right""><font size=""1"" face=""Verdana""><a href=""links.htm""><font color=""#266882"">Mais links.</font></a></font>"
ShowHTML "      </td>"
ShowHTML "    </tr>"
ShowHTML "  <center>"
ShowHTML "  </center></table>"
  
ShowHTML "</div>"

ShowHTML "</body>"

ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da p�gina de links
REM -------------------------------------------------------------------------
Sub Links
   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Links do Preg�o</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  <base target=""_blank"">"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "          <font face=""Verdana"" size=""2""><b>Links</b></font>"
   ShowHTML "          <ul>"
   ShowHTML "            <li>"
   ShowHTML "              <p align=""justify""><font size=""1"" face=""Verdana""><a href=""http://www.comprasnet.gov.br"" target=""_blank"">Portal de Compras do Governo Federal - Comprasnet</a></font></li>"
   ShowHTML "            <li>"
   ShowHTML "              <p align=""justify""><font size=""1"" face=""Verdana""><a href=""http://www.planejamento.gov.br"" target=""_blank"">Minist�rio do Planejamento, Or�amento e Gest�o  - MPOG</a></font></li>"
   ShowHTML "            <li>"
   ShowHTML "              <p align=""justify""><font size=""1"" face=""Verdana""><a href=""" &w_pagina & "Links_secre_esta_sau"" target=""_self"">Secretarias Estaduais de Sa�de</a></font></li>"
   ShowHTML "          </ul>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"
  
  
End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o dos links da secret�ria da sa�de
REM -------------------------------------------------------------------------
Sub Links_secre_esta_sau
   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Links do Preg�o</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  <base target=""_blank"">"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "          <font face=""Verdana"" size=""2""><b>Links<br>"
   ShowHTML "          <font color=""#FFFFFF"">.</font></b></font>"
   ShowHTML "          <table border=""1"" cellspacing=""0"" width=""100%"" bordercolor=""#3591B3"">"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#3591B3""><font face=""Verdana"" size=""2"" color=""#EAF4F7""><b>Secretarias Estaduais de Sa�de</b></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.sesab.ba.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de da Bahia</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.pb.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de da Para�ba</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.cidades.mg.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de de Minas Gerais</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.pe.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de de Pernambuco</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.saude.sc.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de de Santa Catarina</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.sp.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de de S�o Paulo</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.saude.to.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de de Tocantins</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.df.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Distrito Federal</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.saude.es.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Esp�rito Santo</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.mt.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Mato Grosso</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.saude.pr.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Paran�</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.pi.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Piau�</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""http://www.ses.rj.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Rio de Janeiro</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "            <tr>"
   ShowHTML "              <td width=""100%""><font size=""1"" face=""Verdana""><a href=""http://www.saude.rs.gov.br"" target=""_blank"">Secretaria Estadual de Sa�de do Rio Grande do Sul</a></font></td>"
   ShowHTML "            </tr>"
   ShowHTML "          </table>"
   ShowHTML "          <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()"" target=""_self""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "          <b>)</b></font></font></p>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da tela de contratos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o das not�cias
REM -------------------------------------------------------------------------
Sub Noticias
   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>O que � o Preg�o Eletr�nico</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"

   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Not�cias</font></b></p>"
   ShowHTML "        <ul>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""noticias.asp?par=treinamento_ms2806"">Treinamento com servidores das Unidades Hospitalares do MS come�a dia 28/06.</a>"
   ShowHTML "            <i>21/06/04</i></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""noticias.asp?par=servidores_ne"">Servidores dos N�cleos Estaduais participam do treinamento sobre Preg�o Eletr�nico.</a>"
   ShowHTML "            <i>21/06/04</i></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""noticias.asp?par=cursos_enap"">Cursos da ENAP est�o com inscri��es abertas para turmas em setembro e outubro.</a>"
   ShowHTML "            <i>21/06/04</i></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""noticias.asp?par=ms_contabiliza_fun"">MS contabiliza mais de 140 funcion�rios capacitados em cursos sobre Preg�o.</a>"
   ShowHTML "            <i> 21/06/04</i></font></li>"
   ShowHTML "        </ul>"
   ShowHTML "            <p align=""justify"">&nbsp;"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><b>"
   ShowHTML "            Outros treinamentos</b></font>"
   ShowHTML "        <ul>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""arquivos/noticias/Rela��o%20de%20Nomes%20para%20treinamento%20dia%2004.pdf"" target=""_blank"">Assunto -"
   ShowHTML "            Comprasnet/ Dia - 04 de maio.</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""arquivos/noticias/Curso%20Pregoeiro%20ENAP.pdf"" target=""_blank"">Assunto - Forma��o de Pregoeiro/ Dias 11, 12 e 13 de maio.</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""arquivos/noticias/Curso%20Pregoeiro%20510%20n.pdf"" target=""_blank"">Assunto - Preg�o Eletr�nico -"
   ShowHTML "            Comprasnet/ Dia 14 de maio.</a><br>"
   ShowHTML "            </font></li>"
   ShowHTML "        </ul>"
   ShowHTML "        </center>"
   ShowHTML "        <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em"
   ShowHTML "  <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16""> PDF, voc� precisa do programa"
   ShowHTML "  <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u> Adobe Acrobat"
   ShowHTML "  Reader</u></a>.)</font></p>"
   ShowHTML "            <p align=""justify""><b><font size=""1"" face=""Verdana""><a href=""" &w_pagina& "oque"">Saiba mais sobre as Fases do Preg�o</a></font>"
   ShowHTML "        </b>"
   ShowHTML "  <p align=""right"">&nbsp;</p>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o do link O que
REM -------------------------------------------------------------------------
Sub Oque

   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>O que � o Preg�o Eletr�nico</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  <script language=""JavaScript"">"
   ShowHTML "  <!--"
   ShowHTML "  function MM_openBrWindow(theURL,winName,features) { //v2.0"
   ShowHTML "    window.open(theURL,winName,features);"
   ShowHTML "  }"
   ShowHTML "  //-->"
   ShowHTML "  </script>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"

   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">O que � Licita��o?</font></b></p>"
   ShowHTML "  <p align=""justify""><font size=""1"" face=""Verdana"">Licitar � adotar procedimentos"
   ShowHTML "  por meio do qual o Governo, em todas as suas esferas, opta pela proposta mais"
   ShowHTML "  vantajosa para o futuro contrato ou aquisi��o de bens e servi�os, respeitando"
   ShowHTML "  as imposi��es legais que regem tal processo e as instru��es dos editais."
   ShowHTML "  Isso porque a Administra��o P�blica deve zelar pelo bom emprego e utiliza��o"
   ShowHTML "  dos seus recursos em prol da efici�ncia administrativa e do interesse p�blico.<br>"
   ShowHTML "  <br>"
   ShowHTML "  Os procedimentos licitat�rios (link com a p�gina Licita��o) podem ocorrer"
   ShowHTML "  nas seguintes modalidades:</font></p>"
   ShowHTML "        <ul>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Preg�o"">Preg�o</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Convite"">Convite</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Tomada"">Tomada"
   ShowHTML "            de Pre�os&nbsp;</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Concorr�ncia"">Concorr�ncia</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Registro"">Registro"
   ShowHTML "            de Pre�os</a></font></li>"
   ShowHTML "          <li>"
   ShowHTML "            <p align=""justify""><font size=""1"" face=""Verdana""><a href=""#Conv�nio"">Conv�nio</a>&nbsp;</font></li>"
   ShowHTML "        </ul>"
          
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1"">� importante destacar ainda que "
   ShowHTML "    a lei regulamentadora dos tr�mites licitat�rios governamentais esclarece sobre "
   ShowHTML "    as aquisi��es e contrata��es efetuadas por institui��es p�blicas que n�o necessitam "
   ShowHTML "    de licita��o. S�o os casos de <b onClick=""MM_openBrWindow('" & w_pagina & "dispensa','dispensa','scrollbars=yes,width=400,height=200')""><u><a href=""#"">Dispensa</a></u></b>&nbsp; "
   ShowHTML "    e <u><b onClick=""MM_openBrWindow('" & w_pagina & "inexigibilidade','inexegibilidade','scrollbars=yes,width=300,height=150')""><a href=""#"">Inexegibilidade "
   ShowHTML "    de Licita��o</a></b></u>.<br>"
   ShowHTML "            <br>"
   ShowHTML "            Todos as modalidades acima explicitadas s�o firmadas entres entes p�blicos"
   ShowHTML "            e privados atrav�s de Contratos Administrativos (link para p�gina"
   ShowHTML "            Contratos). Este instrumento regulamenta a vincula��o entre o"
   ShowHTML "            licitante vencedor e o �rg�o estatal. O Contrato Administrativo pode"
   ShowHTML "            ser prorrogado, respeitando os dizeres da lei, por�m nunca"
   ShowHTML "            indeterminado. Um Contrato Administrativo s� ser� efetivado se sua"
   ShowHTML "            respectiva despesa estiver enquadrada na previs�o or�ament�ria no"
   ShowHTML "            exerc�cio financeiro do ano.&nbsp;<br>"
   ShowHTML "            <br>"
   ShowHTML "            Fonte - Regras Gerais sobre Licita��o e Contratos Administrativos -"
   ShowHTML "            Consultoria Jur�dica /MS. (com adapta��es)</font>"
   ShowHTML "            <p align=""justify""><font face=""Verdana"" size=""1""><a href=""#Edital"">Leia mais sobre"
   ShowHTML "            Edital</a></font><br>"
   ShowHTML "            <br>"
   ShowHTML "            <br>"
   ShowHTML "  <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify""><font size=""1"" face=""Verdana""><b><a name=""Preg�o"">Preg�o</a></b></font></p>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1"">� a modalidade de licita��o"
   ShowHTML "  cuja disputa pelo fornecimento ou presta��o de servi�o se d� atrav�s de"
   ShowHTML "  sess�o p�blica, presencial ou eletr�nica, por meio de propostas e lances,"
   ShowHTML "  para a classifica��o e habilita��o do licitante que ofertou o menor pre�o."
   ShowHTML "  O Preg�o pode ser empregado em aquisi��es de qualquer valor.</font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo"
   ShowHTML "        da p�gina</a><b>)</b></font></p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify""><font size=""1"" face=""Verdana""><b><a name=""Convite"">Convite</a></b></font></p>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1"">Essa modalidade consiste na solicita��o dirigida aos interessados e empresas que atuam no ramo do servi�o ou bem a ser adquirido pelo �rg�o p�blico. Na modalidade Convite, � necess�ria a participa��o m�nima de tr�s convidados. Tal procedimento � empregado em obras e servi�os de engenharia estimados em at� R$ 150.000,00 (cento e cinq�enta mil reais) e para compras e outros servi�os de at� R$ 80.000,00 (oitenta mil reais).</font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b></font></p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""justify""><a name=""Tomada""><font size=""1"" face=""Verdana""><b>Tomada</b></font></a><b><font size=""1"" face=""Verdana""> de Pre�os</font></b></p>"
   ShowHTML "  <p align=""justify""><font size=""1"" face=""Verdana"">Nesse processo licitat�rio empresas e interessados cadastrados pelo �rg�o p�blico devem obedecer e se enquadrar nas condi��es exigidas pelo mesmo para interpor propostas. Tal modalidade � aplicada em obras e servi�os com custo estimado em at� R$ 1.500.000,00 (um milh�o e quinhentos mil reais) e em compras e outros servi�os de at� R$ 650.000,00 (seiscentos e cinq�enta mil reais).&nbsp;</font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b></font></p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify"">&nbsp;</p>"
   ShowHTML "        <p align=""justify""><br>"
   ShowHTML "        <br>"
   ShowHTML "        <br>"
   ShowHTML "  </p>"
   ShowHTML "        <p align=""justify""><font face=""Verdana"" size=""1""><b><a name=""Concorr�ncia"">Concorr�ncia</a></b></font></p>"
   ShowHTML "        <p align=""justify""><font face=""Verdana"" size=""1"">Quaisquer interessados podem participar e enviar propostas ao �rg�o gerenciador nessa modalidade de licita��o, desde que tais empresas respeitem"
   ShowHTML "        os requisitos constantes no edital. Por esse motivo, a Concorr�ncia � um procedimento acompanhado por ampla divulga��o. Destina-se a obras e servi�os de engenharia com valor superior a R$ 1.150.000,00 (um milh�o e cento e cinq�enta mil reais) e compras e servi�os acima de R$ 650.000,00 (seiscentos e cinq�enta mil reais).<br>"
   ShowHTML "        </font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b></font></p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "        <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1""><b><a name=""Registro"">Registro</a> de Pre�os</b></font></p>"
   ShowHTML "        <p align=""justify""><font face=""Verdana"" size=""1"">Para facilitar a aquisi��o de bens, contrata��o de servi�os e entre outros mecanismos de compra que s�o efetuados com freq��ncia pelo ente p�blico, utiliza-se o Sistema de Registro de Pre�o -SRP. Tal procedimento consiste em um registro formal de pre�os que pode ser consultado pela gest�o p�blica, sempre quando for necess�rio constituir um arquivo de bens, cujas aquisi��es s�o freq�entes pelo �rg�o gerenciador. O SRP permite � Administra��o o monitoramento dos pre�os praticados no mercado.</font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b></font></p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""center"">&nbsp;</p>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1""><b><a name=""Conv�nio"">Conv�nio</a></b></font></p>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1"">Esta modalidade possibilita o acordo entre entidades p�blicas e organiza��es privadas para a execu��o de um objetivo ou projeto de interesse m�tuo. A proposta � feita pelo interessado que ter� que obedecer �s normas estabelecidas pelo �rg�o gerenciador, comprovar regularidade jur�dica e apresentar um Plano de"
   ShowHTML "  Trabalho. Um Conv�nio assemelha-se a um contrato pois a entidade p�blica se compromete em repassar o valor determinado para que o benefici�rio realize as a��es discriminadas no conv�nio. A Presta��o de Contas Final, documento comprobat�rio da despesa, deve ser entregue ao t�rmino no conv�nio para ser  posteriormente verificada pelo Tribunal de Contas da Uni�o (TCU).</font></p>"
   ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b></font></p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left"">&nbsp;</p>"
   ShowHTML "  <p align=""left""><font size=""1"" face=""Verdana""><b><a name=""Edital"">Edital</a></b><br>"
   ShowHTML "  <br>"
   ShowHTML "  No edital constam informa��es como: a legisla��o aplicada, objeto da licita��o, regras para recebimento e abertura dos envelopes, as exig�ncias de habilita��o, os crit�rios de aceita��o das propostas, as san��es por inadimplemento, as cl�usulas do contrato,"
   ShowHTML "  fixa��o dos prazos para fornecimento, local, dia e hora da realiza��o das sess�es e formas de comunica��o das decis�es.<br>"
   ShowHTML "  <br>"
   ShowHTML "  O edital trar� outras especifica��es como:</font></p>"
   ShowHTML "  <ul>"
   ShowHTML "    <li>"
   ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">	A responsabilidade do licitante por todas as transa��es que forem efetuadas em seu nome no sistema eletr�nico;</font></li>"
   ShowHTML "    <li>"
   ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">A responsabilidade do licitante pelo �nus decorrente de perda de neg�cios diante da inobserv�ncia de quaisquer mensagens emitidas pelo sistema ou de desconex�o;</font></li>"
   ShowHTML "    <li>"
   ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">As refer�ncias de tempo no edital, no aviso e durante a sess�o p�blica observar�o o hor�rio de Bras�lia � DF;</font></li>"
   ShowHTML "    <li>"
   ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">O prazo para os interessados apresentarem suas propostas.&nbsp;</font></li>"
   ShowHTML "  </ul>"
   ShowHTML "  <p align=""center""><font size=""1"" face=""Verdana""><b>(</b><a href=""#topo"">topo da p�gina</a><b>)</b><br>"
   ShowHTML "  </font></p>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da Legisla��o
REM -------------------------------------------------------------------------
Sub Legis

ShowHTML "<html>"

ShowHTML "<head>"
ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
ShowHTML "<title>LEGISLA��O</title>"
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
ShowHTML "      <p align=""justify""><b><font face=""Verdana"" size=""2"">Legisla��o</font></b>"
ShowHTML "      <p align=""justify""><font size=""1"" face=""Verdana"">Veja a portaria n� 125, de 27 de maio de"
ShowHTML "      2004, do Minist�rio da Sa�de, que determina a cria��o do Portal de Licita��o e Contratos Administrativos."
ShowHTML "      Leia tamb�m, na �ntegra, a Lei de Licita��es do Governo Federal.</font></p>"
ShowHTML "<table border=""1"" cellpadding=""3"" cellspacing=""0"" width=""100%"" bordercolor=""#3591B3"">"
ShowHTML "  <tr>"
ShowHTML "    <td width=""120"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""arquivos/pdf/port125_dou_portalcomprasMS.pdf"" target=""_blank"">Portaria n�"
ShowHTML "      125, de 27 de maio de 2004</a></font></td>"
ShowHTML "    <td bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana"">Disp�e sobre a cria��o no, portal do Minist�rio da Sa�de, de uma p�gina para divulga��o dos dados e informa��es sobre licita��es e contratos administrativos, pertinentes a obras, servi�os, inclusive de publicidade, compras, aliena��es e loca��es, realizados pelos �rg�os do Minist�rio da Sa�de, e d� outras provid�ncias.</font></td>"
ShowHTML "    <td width=""75"" bgcolor=""#EAF4F7"">"
ShowHTML "      <p align=""center"">"
ShowHTML "      <font size=""1"" face=""Verdana"">Minist�rio<br>"
ShowHTML "      da Sa�de</font></p>"
ShowHTML "    </td>"
ShowHTML "  </tr>"
ShowHTML "  <tr>"
ShowHTML "    <td width=""120"" bgcolor=""#FFFFFF""><font size=""1"" face=""Verdana""><a href=""arquivos/pdf/port110_dou_pregaoMS.pdf"" target=""_blank"">Portaria n� 110, de 29 de abril de 2004</a></font></td>"
ShowHTML "    <td bgcolor=""#FFFFFF""><font size=""1"" face=""Verdana"">O Secret�rio-Executivo do Minist�rio da Sa�de, no uso de suas atribui��es, e tendo em vista o disposto na Lei n� 10.520, de 17 de julho de 2002, no Decreto n� 3.555, de 8 de agosto de 2000 e no Decreto n� 3.697, de 21 de dezembro de 2000, e Considerando a necessidade de implementa��o de mecanismos que possibilitem a redu��o de custos nas compras de bens e servi�os governamentais, resolve:</font></td>"
ShowHTML "    <td width=""75"" bgcolor=""#FFFFFF"">"
ShowHTML "      <p align=""center""><font size=""1"" face=""Verdana"">Minist�rio<br>"
ShowHTML "      da Sa�de</font></td>"
ShowHTML "  </tr>"
ShowHTML "  <tr>"
ShowHTML "      <td width=""120"" bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana""><a href=""https://www.planalto.gov.br/ccivil_03/Leis/L8666orig.htm"" target=""_blank"">Lei"
ShowHTML "        n� 8.666, de 21 de"
ShowHTML "        junho de 1993</a></font></td>"
ShowHTML "      <td bgcolor=""#EAF4F7""><font size=""1"" face=""Verdana"">Regulamenta o art. 37, inciso XXI, da Constitui��o Federal, institui normas para licita��es e contratos da Administra��o P�blica e d� outras provid�ncias.</font></td>"
ShowHTML "      <td width=""75"" bgcolor=""#EAF4F7"">"
ShowHTML "        <p align=""center""><font size=""1"" face=""Verdana"">Presid�ncia da"
ShowHTML "        Rep�blica</font></td>"
ShowHTML "  </tr>"
ShowHTML "</table>"

ShowHTML "</body>"

ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da dispensa
REM -------------------------------------------------------------------------
Sub Dispensa

ShowHTML "<html>"

ShowHTML "<head>"
ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
ShowHTML "<title>Dispensa</title>"
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

ShowHTML "<p align=""left""><font face=""Verdana"" size=""1""><b>Dispensa de Licita��o</b></font></p>"

ShowHTML "<p align=""left""><font face=""Verdana"" size=""1""> A Lei n� 8666/93 enumera vinte "
ShowHTML "  e quatro situa��es que justificam a dispensa do processo licitat�rio pelos �rg�os "
ShowHTML "  p�blicos. Destacam-se as aquisi��es de baixo valor e as circunst�ncias "
ShowHTML "  de urg�ncia de compra ou contrata��o. Apesar da dispensa do tr�mite licitat�rio, "
ShowHTML "  � necess�rio recorrer a uma breve pesquisa de mercado com a apresenta��o de "
ShowHTML "  tr�s or�amentos, sendo que o de menor valor vencer�. Em alguns momentos, a Consultoria "
ShowHTML "  Jur�dica ser� acionada para analisar o processo. A legisla��o zela, mesmo no "
ShowHTML "  caso de Dispensa, pelas formalidades exigidas nas demais modalidades, como publica��o "
ShowHTML "  na imprensa oficial, por�m acrescido da caracteriza��o da situa��o emergencial, "
ShowHTML "  justificativa da escolha do fornecedor e raz�o do pre�o.</font></p>"

ShowHTML "</body>"
ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o da inexigibilidade
REM -------------------------------------------------------------------------
Sub Inexigibilidade

ShowHTML "<html>"

ShowHTML "<head>"
ShowHTML "<meta http-equiv=""Content-Language"" content=""pt-br"">"
ShowHTML "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
ShowHTML "<title>Dispensa</title>"
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

ShowHTML "<p align=""left""><font face=""Verdana"" size=""1""><b>Inexigibilidade de Licita��o</b> "
ShowHTML "  </font></p>"
ShowHTML "<p align=""left""><font face=""Verdana"" size=""1"">Esse procedimento se d� quando ocorre "
ShowHTML "  a inviabilidade de competi��o, ou seja, quando o objeto de licita��o s� pode "
ShowHTML "  ser atendido ou fornecido por uma organiza��o isolada, �nica no mercado. O �rg�o "
ShowHTML "  gerenciador deve apurar se tal empresa possui habilita��o e compet�ncia para "
ShowHTML "  a realiza��o do servi�o ou fornecimento.<br>"
ShowHTML "  <br>"
ShowHTML "  </font></p>"

ShowHTML "</body>"
ShowHTML "</html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o das modalidades das licita��es
REM -------------------------------------------------------------------------
Sub Licitacoes

   Dim w_modadalidade, w_nm_modalidade

   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Modalidade das licita��es</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"

   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Licita��es</font></b></p>"
   ShowHTML "        </center>"
   ShowHTML "        <p align=""left""><font face=""Verdana"" size=""1"">Nesta sess�o s�o disponibilizadas informa��es referentes �s licita��es promovidas pelo Minist�rio da Sa�de.</font></p>"
   
   ShowHTML "  <ul>"
   DB_GetLcmodalidade RS, null, w_cliente
   RS.Sort = "nome"
   If RS.EOF Then
      ShowHTML "    <li>"
      ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhuma modalidade cadastrada</font></li>"
   Else
      While Not RS.EOF
         ShowHTML "    <li>"
         ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana""><a href=""" &w_pagina& "LicitacoesMod&w_modalidade=" &RS("chave")& "&w_nm_modalidade=" & RS("nome") & """>" &RS("sigla")& " - " &RS("nome")& "</a></font></li>"
         RS.MoveNext
      wend
   End If
   DesconectaBD
   ShowHTML "  </ul>"
   
   ShowHTML "        </center>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"


End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o das licita��es por modalidade
REM -------------------------------------------------------------------------
Sub LicitacoesMod

   Dim w_modalidade, w_nm_modalidade
   
   w_modalidade    = Request("w_modalidade")
   w_nm_modalidade = Request("w_nm_modalidade")
   
   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Licita��es</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"

   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Modalidade: " & w_nm_modalidade& "</font></b></center></p>"
   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Certames</font></b></center></p>"
   ShowHTML "  <ul>"
   DB_GetLcPortalLic RS, w_cliente, null, null, w_chave, null, null, null, _
      w_modalidade, null, null, null, null, null, null, null, null, "S", null, null, null, null
   RS.Sort = "abertura desc"
   If RS.EOF Then
      'ShowHTML "    <li>"
      ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhuma licita��o cadastrada para esta modalidade</font></li>"
   Else
      While Not RS.EOF
         ShowHTML "    <li>"
         ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">"
         ShowHTML "         <a href=""" &w_pagina& "DadosLicitacao&w_sq_portal_lic=" &RS("sq_portal_lic")& """>" & RS("sg_modalidade") & "-" & RS("edital") & "</a>"
         ShowHTML "         <br><b>Unidade licitante</b>: " & RS("nm_unid") & " (" & RS("sg_unid") & ")"
         ShowHTML "         <br><b>Objeto</b>: " &RS("objeto")
         ShowHTML "         <br><b>Situa��o atual</b>: " &RS("nm_situacao")
         ShowHTML "         <br><b>Abertura</b>: " & FormatDateTime(RS("abertura"), 1)
         ShowHTML "      </font></p>"
         ShowHTML "    </li>"
         RS.MoveNext
      wend
   End If
   DesconectaBD
   ShowHTML "  </ul>"
   ShowHTML "  <p align=""justify"">&nbsp;</p>"
   ShowHTML "  <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "  <b>)</b></font></font></p>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o dos dados da licita��o
REM -------------------------------------------------------------------------
Sub DadosLicitacao

   Dim w_sq_portal_lic
   
   w_sq_portal_lic = Request("w_sq_portal_lic")

   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Licita��o</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   DB_GetLcPortalLic RS, w_cliente, null, null, w_sq_portal_lic, null, null, null, _
   null, null, null, null, null, null, null, null, null, null, null, null, null, null
   
   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"
   ShowHTML "    <center>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""2"">Unidade licitante: <b>" & RS("nm_unid")& " (" & RS("sg_unid")& ")</b></font></li>"
   
   ShowHTML "  <p align=""justify""><b><font face=""Verdana"" size=""2"">" & RS("nm_modalidade") & " " & RS("edital")& "</font></b></center></p>"
   ShowHTML "  <ul>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Objeto -</b> " & RS("objeto")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Situa��o atual -</b> " & RS("nm_situacao")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Abertura -</b> " & FormatDateTime(RS("abertura"),1) & " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Finalidade -</b> " & RS("nm_finalidade")& "  </font></li>"      
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Crit�rio de julgamento -</b> " & RS("nm_criterio")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� processo -</b> " & Nvl(RS("processo"),"N�o informado")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� empenho -</b> " & Nvl(RS("empenho"),"N�o informado")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Fundamenta��o -</b> " & Nvl(RS("fundamentacao"), "N�o informado")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Observa��o -</b> " & Nvl(RS("observacao"),"N�o informado")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Fonte de recurso -</b> " & RS("nm_fonte")& "  </font></li>"      
   ShowHTML "  </ul>"   
   DesconectaBD
   
   'Anexos da licita��o
   DB_GetLcAnexo RS, w_sq_portal_lic, null, w_cliente
   RS.Sort = "nome"
   If Not RS.EOF Then
      ShowHTML "  <DL><font face=""Verdana"" size=""1""><b>Anexos</b></font>"
      ShowHTML "    <BLOCKQUOTE>"
      While Not RS.EOF  
         ShowHTML "    <DT><font face=""Verdana"" size=""1""><b><a href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o arquivo em outra janela."">" & RS("nome") & "</a></b></font>" 
         ShowHTML "        <DD><font face=""Verdana"" size=""1"">"
         ShowHTML "          <b>Descri��o -</b> " & Nvl(RS("descricao"),"---")
         ShowHTML "          <br><b>Tipo -</b> " & RS("tipo")
         ShowHTML "          <br><b>Tamanho -</b> " & Round(cDbl(RS("tamanho"))/1024,1) & " KB"
         ShowHTML "         <br><br>"
         ShowHTML "        </DD>"
         ShowHTML "    </DT>" 
         RS.MoveNext
      wend
      ShowHTML "  </BLOCKQUOTE></DL>"
   End If
   DesconectaBD
   
   'Contratos
   DB_GetLcPortalCont RS, w_cliente, w_sq_portal_lic, null, null
   RS.Sort = "vigencia_inicio desc"
   RS.Filter = "publicar = 'S' and pub_lic = 'S'"
   If Not RS.EOF Then
      ShowHTML "  <DL><font face=""Verdana"" size=""1""><b>Contratos</b></font>"
      ShowHTML "    <BLOCKQUOTE>"
      While Not RS.EOF
         ShowHTML "    <DT><font face=""Verdana"" size=""1""><b><a href=""" &w_pagina& "DadosContrato&w_sq_portal_contrato=" &RS("sq_portal_contrato")& "&w_sq_portal_lic=" &RS("chave")& """ title=""Clique para ver detalhes do contrato."">Contrato n� " & RS("numero") & "</a></b></font>" 
         ShowHTML "        <DD><font face=""Verdana"" size=""1"">"
         ShowHTML "         <b>Unidade contratante</b>: " & RS("nm_unid") & " (" & RS("sg_unid") & ")"
         ShowHTML "         <br><b>Objeto</b>: " &RS("objeto")
         ShowHTML "         <br><b>Vig�ncia</b>: " & FormataDataEdicao(RS("vigencia_inicio")) & " a " & FormataDataEdicao(RS("vigencia_fim"))
         ShowHTML "         <br><br>"
         ShowHTML "        </DD>"
         ShowHTML "    </DT>" 
         RS.MoveNext
      wend
      ShowHTML "  </BLOCKQUOTE></DL>"
   End If
   DesconectaBD

   'Itens da licita��o
   DB_GetLcPortalLicItem RS, w_cliente, w_sq_portal_lic, null, null, "N"
   ShowHTML "  <font face=""Verdana"" size=""1""><b>Itens</b></font>"
   If RS.EOF Then
            ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhum item cadastrado para esta licita��o</font></li>"
   Else
      ShowHTML "    <BLOCKQUOTE><DL>"
      While Not RS.EOF  
         ShowHTML "    <DT><font face=""Verdana"" size=""1""><b>" & RS("Ordem") & " - " & RS("Nome") & "</b></font>" 
         ShowHTML "      <DD><font face=""Verdana"" size=""1"">"
         ShowHTML "          <b>Quantidade -</b> " & FormatNumber(RS("quantidade"),1)
         ShowHTML "          <br><b>Unidade de fornecimento -</b> " & Nvl(RS("nm_unidade_fornec"),"N�o informado")
         If Nvl(RS("nm_unidade_fornec"),"N�o informado") <> "N�o informado" Then
            ShowHTML "(" &RS("sg_unidade_fornec")& ")"
         End If
         ShowHTML "          <br><b>Descricao -</b> " & Nvl(RS("descricao"),"N�o informado")
         ShowHTML "          <br><b>Observa��es -</b> " & Nvl(RS("situacao"),"N�o informado")
         ShowHTML "         <br><br>"
         ShowHTML "    </DT>" 
         RS.MoveNext
      wend
      ShowHTML "  </DL></BLOCKQUOTE>"
   End If
   DesconectaBD
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1"">*Para mais informa��es, acesse o Portal de Compras do Governo Federal - Comprasnet"
   ShowHTML "  (<a href=""http://www.comprasnet.gov.br"" target=""_blank"">www.comprasnet.gov.br</a>)</font></p>"
   ShowHTML "  <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "  <b>)</b></font></font></p>"
   
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o das finalidade dos contratos
REM -------------------------------------------------------------------------
Sub FinalidadeCont

   Dim w_sq_lcfinalidade, w_nm_finalidade

   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Modalidade das licita��es</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"

   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Contratos</font></b></p>"
   ShowHTML "        </center>"
   ShowHTML "        <p align=""left""><font face=""Verdana"" size=""1"">Nesta sess�o s�o disponibilizadas informa��es referentes aos contratos administrativos do Minist�rio da Sa�de.</font></p>"
   ShowHTML "  <ul>"
   DB_GetLcFinalidade RS, null, w_cliente
   RS.Filter = "ativo = 'S'"
   RS.Sort = "nome"
   If RS.EOF Then
      ShowHTML "    <li>"
      ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhuma finalidade cadastrada</font></li>"
   Else
      While Not RS.EOF
         ShowHTML "    <li>"
         ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana""><a href=""" &w_pagina& "Contratos&w_sq_lcfinalidade=" &RS("chave")& "&w_nm_finalidade=" & RS("nome") & """>" &RS("nome")& "</a></font></li>"
         RS.MoveNext
      wend
   End If
   DesconectaBD
   ShowHTML "  </ul>"
   
   'ShowHTML "        <p align=""left""><font face=""Verdana"" size=""1""><b>Manuais</b><o:p>"
   'ShowHTML "        </o:p>"
   'ShowHTML "        </font></p>"
   'ShowHTML "        <ul>"
   'ShowHTML "          <li>"
   'ShowHTML "            <p align=""left""><font face=""Verdana"" size=""1""><a href=""arquivos/pdf/manual_pregoeiro_presencial.pdf"" target=""_blank"">Manual do Pregoeiro/Presencial</a></font></li>"
   'ShowHTML "          <li>"
   'ShowHTML "            <p align=""left""><font face=""Verdana"" size=""1""><a href=""arquivos/pdf/Manual_Pregoeiro_Eletronico_.pdf"" target=""_blank"">Manual do Pregoeiro/Eletr�nico</a></font></li>"
   'ShowHTML "          <li>"
   'ShowHTML "            <p align=""left""><font face=""Verdana"" size=""1""><a href=""arquivos/pdf/Manual_Pregao_Eletronico_Fornecedor.pdf"" target=""_blank"">Manual do Fornecedor</a></font></li>"
   'ShowHTML "        </ul>"
   'ShowHTML "        <p align=""right""><font face=""Verdana"" size=""1"">(Para ler arquivos em <img border=""0"" src=""images/pdf_icone.gif"" width=""15"" height=""16"">PDF, voc� precisa do programa <a href=""http://www.adobe.com.br/products/acrobat/readstep2.html"" target=""_blank""><u>Adobe Acrobat Reader</u></a>.)</font></p>"
   ShowHTML "    <center>"
   ShowHTML "        </center>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"


End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o das licita��es por modalidade
REM -------------------------------------------------------------------------
Sub Contratos

   Dim w_sq_lcfinalidade, w_nm_finalidade, w_sq_portal_lic
   
   w_sq_lcfinalidade = Request("w_sq_lcfinalidade")
   w_nm_finalidade   = Request("w_nm_finalidade")
   w_sq_portal_lic   = Request("w_sq_portal_lic")
   
   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Licita��es</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"
   
   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Finalidade: " & w_nm_finalidade& "</font></b></center></p>"
   ShowHTML "    <center>"
   ShowHTML "        <p align=""justify""><b><font face=""Verdana"" size=""2"">Contratos</font></b></center></p>"

   ShowHTML "  <ul>"
   DB_GetLcPortalCont RS, w_cliente, null, null, w_sq_lcfinalidade
   RS.Sort = "vigencia_inicio desc"
   RS.Filter = "publicar = 'S' and pub_lic = 'S'"
   If RS.EOF Then
      ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhum contrato cadastrado para esta finalidade</font></li>"
   Else
      While Not RS.EOF
         ShowHTML "    <li>"
         ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">"
         ShowHTML "         <a href=""" &w_pagina& "DadosContrato&w_sq_portal_contrato=" &RS("sq_portal_contrato")& "&w_sq_portal_lic=" &RS("chave")& """>Contrato n� " & RS("numero") & "</a>"
         ShowHTML "         <br><b>Unidade contratante</b>: " & RS("nm_unid") & " (" & RS("sg_unid") & ")"
         ShowHTML "         <br><b>Objeto</b>: " &RS("objeto")
         ShowHTML "         <br><b>Vig�ncia</b>: " & FormataDataEdicao(RS("vigencia_inicio")) & " a " & FormataDataEdicao(RS("vigencia_fim"))
         ShowHTML "      </font></p>"
         ShowHTML "    </li>"
         RS.MoveNext
      wend
   End If
   DesconectaBD
   ShowHTML "  </ul>"
   ShowHTML "  <p align=""justify"">&nbsp;</p>"
   ShowHTML "  <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "  <b>)</b></font></font></p>"
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o dos dados da licita��o
REM -------------------------------------------------------------------------
Sub DadosContrato

   Dim w_sq_portal_contrato, w_sq_portal_lic
   
   w_sq_portal_contrato = Request("w_sq_portal_contrato")
   w_sq_portal_lic      = Request("w_sq_portal_lic")

   ShowHTML "  <html>"

   ShowHTML "  <head>"
   ShowHTML "  <meta http-equiv=""Content-Language"" content=""pt-br"">"
   ShowHTML "  <meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1252"">"
   ShowHTML "  <title>Licita��o</title>"
   ShowHTML "  <style>"
   ShowHTML "  BODY {"
   ShowHTML "  	SCROLLBAR-FACE-COLOR: #E9F4F6;"
   ShowHTML "  	SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-SHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-ARROW-COLOR: #3591B3;"
   ShowHTML "  	SCROLLBAR-DARKSHADOW-COLOR: #FFFFFF;"
   ShowHTML "  	SCROLLBAR-TRACK-COLOR:#FFFFFF;"
   ShowHTML "  	SCROLLBAR-BASE-COLOR: #FFFFFF;"
   ShowHTML "  }"
   ShowHTML "  	a:link{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:visited{color:""#0033CC"";text-decoration:underline}"
   ShowHTML "  	a:hover{color:""#3591B3"";text-decoration:none}"
   ShowHTML "  </style>"
   ShowHTML "  </head>"
   ShowHTML "  <body bgcolor=""#FFFFFF"" topmargin=""5"" leftmargin=""5"" text=""#266882"">"

   DB_GetLcPortalCont RS, w_cliente, null, w_sq_portal_contrato, null
   
   ShowHTML "  <p align=""center""><a name=""topo""></a></p>"
   ShowHTML "    <center>"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""2"">Unidade contratante: <b>" & RS("nm_unid")& " (" & RS("sg_unid")& ")</b></font></li>"
   
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""2""><b>Contrato n� " & RS("numero") & "</b></font></p>"
   ShowHTML "  <ul>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Objeto -</b> " & RS("objeto")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Contratado(a) -</b> " & RS("nome")& " - "
   If RS("pessoa_juridica") = "S" Then ShowHTML RS("cnpj") Else ShowHTML RS("cpf") End If
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Vig�ncia -</b> " & FormataDataEdicao(RS("vigencia_inicio")) & " a " & FormataDataEdicao(RS("vigencia_fim")) & " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Valor total -</b> " & FormatNumber(RS("valor"),2) & "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Data de assinatura -</b> " & FormataDataEdicao(RS("assinatura")) & " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Data de publica��o D.O.U. -</b> " & FormataDataEdicao(RS("publicacao")) & " </font></li>"   
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� processo -</b> " & RS("processo")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� empenho -</b> " & RS("empenho")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Observa��o -</b> " & RS("observacao")& "  </font></li>"   
   ShowHTML "  </ul>"   
   
   DB_GetLcPortalContItem RS1, w_cliente, w_sq_portal_lic, w_sq_portal_contrato, null, null
   RS1.Filter = "Existe <> null"
   ShowHTML "  <p align=""justify""><font face=""Verdana"" size=""1""><b>Itens</b></font></p>"
   If RS1.EOF Then
            ShowHTML "      <p align=""left""><font size=""1"" face=""Verdana"">N�o existe nenhum item cadastrado para este contrato</font></li>"
   Else
      ShowHTML "    <BLOCKQUOTE><DL>"
      While Not RS1.EOF  
         ShowHTML "    <DT><p align=""justify""><font face=""Verdana"" size=""1""><b>" & RS1("Ordem") & " - " & RS1("Nome") & "</b></font>" 
         ShowHTML "      <DD><p align=""justify""><font face=""Verdana"" size=""1"">"
         ShowHTML "          <b>Quantidade -</b> " & FormatNumber(RS1("quantidade"),1)
         ShowHTML "          <br><b>Unidade de fornecimento -</b> " & Nvl(RS1("nm_unidade_fornec"),"N�o informado")
         If Nvl(RS1("nm_unidade_fornec"),"N�o informado") <> "N�o informado" Then
            ShowHTML "(" &RS1("sg_unidade_fornec")& ")"
         End If
         ShowHTML "          <br><b>Valor unit�rio -</b> " & FormatNumber(RS1("valor_unitario"),2)
         ShowHTML "          <br><b>Valor total -</b> " & FormatNumber(RS1("valor_total"),2)
         ShowHTML "          <br><b>Descricao -</b> " & Nvl(RS1("descricao"),"N�o informado")
         ShowHTML "          <br><b>Observa��es -</b> " & Nvl(RS1("situacao"),"N�o informado")
         ShowHTML "    </DT>" 
         RS1.MoveNext
      wend
      ShowHTML "  </DL></BLOCKQUOTE>"
   End If
   
   ShowHTML "  <p align=""justify""><b><font face=""Verdana"" size=""2"">Certame de origem do contrato - " & RS("nm_modalidade") & " " & RS("edital")& "</font></b></center></p>"
   ShowHTML "  <ul>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Unidade licitante -</b> " & RS("nm_unid_lic")& " (" & RS("sg_unid_lic")& ")</font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Objeto -</b> " & RS("objeto")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Situa��o atual -</b> " & RS("nm_situacao")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Abertura -</b> " & FormatDateTime(RS("abertura"),1) & " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Finalidade -</b> " & RS("nm_finalidade")& "  </font></li>"      
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Crit�rio de julgamento -</b> " & RS("nm_criterio")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� processo -</b> " & Nvl(RS("processo"),"N�o informado")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>N� empenho -</b> " & Nvl(RS("empenho"),"N�o informado")& " </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Fundamenta��o -</b> " & Nvl(RS("fundamentacao"),"N�o informado")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Observa��o -</b> " & Nvl(RS("observacao"),"N�o informado")& "  </font></li>"
   ShowHTML "    <li><p align=""justify""><font face=""Verdana"" size=""1""><b>Fonte de recurso -</b> " & RS("nm_fonte")& "  </font></li>"      
   ShowHTML "  </ul>"   

   ShowHTML "  <p align=""center""><font face=""Verdana""><font size=""1""><b>(</b> </font><a href=""javascript:history.back()""><font size=""1"">Voltar</font></a><font size=""1"">"
   ShowHTML "  <b>)</b></font></font></p>"
   
   ShowHTML "  </body>"
   ShowHTML "  </html>"

End Sub
REM =========================================================================
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  
  Select Case Par
    Case "INICIAL"
       Inicial
    Case "HOME"
       Home
    Case "LINKS"
       Links
    Case "LINKS_SECRE_ESTA_SAU"
       Links_secre_esta_sau
    Case "NOTICIAS"
        Noticias
    Case "OQUE"
       Oque
    Case "LEGIS"
       Legis
    Case "DISPENSA"
       Dispensa
    Case "INEXIGIBILIDADE"
       Inexigibilidade
    Case "LICITACOES"
       Licitacoes
    Case "LICITACOESMOD"
       LicitacoesMod
    Case "DADOSLICITACAO"
       DadosLicitacao
    Case "FINALIDADECONT"
       FinalidadeCont
    Case "CONTRATOS"
       Contratos
    Case "DADOSCONTRATO"
       DadosContrato
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

