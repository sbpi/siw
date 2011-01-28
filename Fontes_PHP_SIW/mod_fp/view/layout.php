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
    <td height="130" colspan="5" bgcolor="#E9E9E9"><img src="img/abdi.png" /></td>
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
        <p class="style2" id="endereco_pe"><strong><form action="result_funcionario.php" method="post" name="form" >
<table width="504" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="26" colspan="2"><div align="center"><strong>BUSCA DE FUNCION&Aacute;RIOS </strong></div></td>
  </tr>
  <tr>
    <td width="267" height="26"><div align="left" style="margin-left:15px;">MATR&Iacute;CULA:
        <input name="matricula" type="text" id="matricula" />
    </div></td>
    <td width="237"><p align="left">CPF:
        <input name="cpf" type="text" id="cpf" />
    </p>    </td>
  </tr>
  <tr>
    <td height="26" colspan="2"><div align="left" style="margin-left:15px;">NOME: 
        <input name="nome" type="text" id="nome" size="50" />
          <label>
            <input type="submit" name="Submit" value="Buscar" />
          </label>
        </div></td>
  </tr>
</table>
</form></p>
        </div>
    </div></td>
  </tr>
</table>
</body>
</html>
