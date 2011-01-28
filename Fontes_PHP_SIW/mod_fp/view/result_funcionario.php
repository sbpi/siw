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
    <td colspan="5"><div id="apoio_institucional">
      <div>
        <p class="style2" id="endereco_pe"><form action="../negocio/funcionario.php?action=buscar" method="post" name="form" >
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
    <td height="26" colspan="2"><div align="left" style="margin-left:15px;">DEPARTAMENTO:
        <select name="id_funcionario" id="id_funcionario">
          <option value="">Departamentos</option>
		  <?php do{ ?>
          <option value="<?php echo $dataDepartamento->Lista['id_departamento']; ?>"><?php echo $dataDepartamento->Lista['departamento'];  }while( $dataDepartamento->ViewDatabase() ); ?> </option>

        </select>            
        <label></label>
    </div></td>
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
  <?php if( $data->Lista['nome'] ){?>
  <tr>
    <td colspan="5" bgcolor="#E9E9E9">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><div id="apoio_institucional">
        <div>
          <table width="750" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#F3F3F3">
  <tr>
    <td height="26" bgcolor="#D7D7D7"><div align="center"><strong>FUNCION&Aacute;RIOS</strong></div></td>
  </tr>
  <tr>
    <td height="26" valign="top"><div align="center">
      <table width="750" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#F3F3F3">
        <tr>
          <td width="119" height="19" bgcolor="#EAEAEA"><div align="center">MATR&Iacute;CULA </div></td>
          <td width="316" bgcolor="#EAEAEA"><div align="center">NOME</div></td>
          <td width="155" bgcolor="#EAEAEA"><div align="center">DEPARTAMENTOS</div></td>
          <td width="142" bgcolor="#EAEAEA"><div align="center">CPF</div></td>
        </tr>
		<?php do{?>
        <tr>
          <td height="19"><div align="center"><a href="../negocio/folha.php?action=gerarprevia&id=<?php echo $data->Lista['id_funcionario'];?>" ><?php echo $data->Lista['matricula'];?></a></div></td>
          <td><div align="left" style=""><?php echo $data->Lista['nome'];?></div></td>
          <td><div align="center"><?php echo $data->Lista['departamento'];?></div></td>
          <td><div align="center"><?php echo $data->Lista['cpf'];?></div></td>
        </tr>
		<?php }while( $data->ViewDatabase() );?>
      </table>
      <p>&nbsp;</p>
    </div>      </td>
  </tr>
</table></p>
        </div>
    </div></td>
  </tr>
  <?php }?>
</table>
</body>
</html>
