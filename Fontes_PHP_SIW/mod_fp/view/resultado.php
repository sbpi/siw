<?php
session_start();
if (!isset ($_SESSION['SQ_PESSOA'])) { // (está definida a sessão nome_usuario) 
	echo "<script language='javascript'>";
	echo "alert('Você precisa está logado para acessar essa página !!!');";
	echo "window.location='login.html';";
	echo "</script>";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ABDI - Ag&ecirc;ncia Nacional de Desenvolvimento Industrial</title>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	background-color: #E9E9E9;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.style2 {font-size: 12px}
-->
</style></head>

<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="130" colspan="5" bgcolor="#E9E9E9"><img src="../view/img/abdi.png" width="230" height="98" /></td>
  </tr>
  <tr>
    <td width="160" height="20" bgcolor="#CCCCCC"><div align="center"><a href="../view/painel.php">P&aacute;gina Inicial</a></div></td>
    <td width="205" height="20" bgcolor="#CCCCCC"> <div align="center"><a href="../negocio/funcionario.php?action=buscar">Folha de pagamentos</a></div></td>
	<td width="158" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/funcionario.php?action=listar">Funcion&aacute;rios</a></div></td>
	<td width="140" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/usuario.php?action=listar">Usuários</a></div></td>
    <td width="107" height="20" bgcolor="#CCCCCC"> <div align="center"><a href="../negocio/usuario.php?action=logout">Sair</a></div></td>
  </tr>
  
  <tr>
    <td colspan="5" bgcolor="#E9E9E9">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><div id="apoio_institucional" style="margin-left:25px;">
      <div>
        <p class="style2" id="endereco_pe"><form name="form" action="resultado.php">
	<table width="227" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="26"><div align="center"><strong>DIGITE OS DADOS</strong></div></td>
  </tr>
  <tr>
    <td height="26"><div align="left">
      <div align="center">M&Ecirc;S / ANO:
        <input name="mesano" type="text" id="mesano" size="7" maxlength="7" />
      </div>
    </div>      <div align="left"></div></td>
  </tr>
  <tr>
    <td height="26"><div align="center">
      <input type="submit" name="Submit" value="Calcular" />
    </div></td>
  </tr>
</table>
</form></p>
        </div>
    </div></td>
  </tr>
  <tr>
    <td colspan="5" bgcolor="#E9E9E9">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><div id="apoio_institucional" style="margin-left:25px;">
        <div>
          <p class="style2" id="endereco_pe"><table width="600" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#F3F3F3">
  <tr>
    <td height="34" bgcolor="#D7D7D7"><div align="center"><strong> DEMONSTRATIVO DE PAGAMENTO</strong></div></td>
  </tr>
  <tr>
    <td height="26" valign="bottom">
      <table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#F3F3F3">
        <tr>
          <td width="232" valign="top" bgcolor="#FFFFFF"><div align="left"><span class="style1">NOME</span><br />
                  <br />
            <?php echo $data->Lista['nome'];?></div></td>
          <td width="94" valign="top" bgcolor="#FFFFFF"><div align="left" class="style2">MATR&Iacute;CULA<br />
                  <br />
            </div>
              <div align="right"><?php echo $data->Lista['matricula'];?></div>
            <div align="left" style=""></div></td>
          <td width="64" valign="top" bgcolor="#FFFFFF"><div align="left" class="style2">BANCO</div>
              <br />
              <div align="right">XXX</div></td>
          <td width="57" valign="top" bgcolor="#FFFFFF"><div align="left"><span class="style2">AG&Ecirc;NCIA</span><br />
                  <br />
                  <div align="right">XXX</div>
          </div></td>
          <td width="125" valign="top" bgcolor="#FFFFFF"><div align="left" class="style2">CONTA<br />
                  <br />
            </div>
              <div align="right">XXXXXX-X</div></td>
        </tr>
        <tr>
          <td height="19" colspan="5">&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#F3F3F3">
        <tr>
          <td width="36" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">C&Oacute;D</div></td>
          <td width="149" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">DESCRI&Ccedil;&Atilde;O</div>
          </div></td>
          <td width="120" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">BASE DE C&Aacute;LCULO </div>
          </div></td>
          <td width="41" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">REF</div></td>
          <td width="119" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">VENCIMENTOS</div></td>
          <td width="103" valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">DESCONTOS</div></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#FFFFFF"><div align="center"><span class="style2">001</span></div></td>
          <td valign="top" bgcolor="#FFFFFF"><span class="style2">DIAS TRABALHADOS</span></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">1.639,31</div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">30,00</div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">1.639,31</div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2"></div></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#FFFFFF"><div align="center"><span class="style2">002</span></div></td>
          <td valign="top" bgcolor="#FFFFFF"><span class="style2">HORAS EXTRAS DIURNAS </span></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="center" class="style2">13,00</div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">88,79</div></td>
          <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#FFFFFF"><div align="center"><span class="style2">003</span></div></td>
          <td valign="top" bgcolor="#FFFFFF"><span class="style2">INSS SOBRE SALARIOS </span></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">1.328,25</div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="center" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">146,11</div></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#FFFFFF"><div align="center"><span class="style2">004</span></div></td>
          <td valign="top" bgcolor="#FFFFFF"><span class="style2">ADIANTAMENTO SALARIAL </span></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="center" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2"></div></td>
          <td valign="top" bgcolor="#FFFFFF"><div align="right" class="style2">656,00</div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" valign="middle" bgcolor="#FFFFFF"><div align="center"></div>            <div align="right" class="style2">TOTAL</div></td>
          <td valign="middle" bgcolor="#FFFFFF"><div align="right" class="style2">R$ 1.728,10</div></td>
          <td valign="middle" bgcolor="#FFFFFF"><div align="right" class="style2">R$ 802,11</div></td>
        </tr>
      </table>
      <form id="form1" name="form1" method="post" action="">
        <div align="right">
          <input type="password" name="textfield" id="textfield" />
          <input type="submit" name="button" id="button" value="Aprovar" />
</div>
      </form>      </td>
</table></p>
        </div>
    </div></td>
  </tr>
</table>
</body>
</html>
