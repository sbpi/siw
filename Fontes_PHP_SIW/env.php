<html>
  <head>
    <title>PHP 5</title> 
  </head>
  <body>
<table border=0><tr><td>
<pre>
<?
echo 'Sistema operacional: '.PHP_OS;
echo '<br>Módulos instalados: ';
print_r(get_loaded_extensions());
?>
</pre>
</table>
    <? 
    phpinfo(); 
    ?>
  </body>
</html>