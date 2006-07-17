<?
// =========================================================================
// Funчуo que formata dias, horas, minutos e segundos a partir dos segundos
// -------------------------------------------------------------------------
function formataDataXML($w_dt_grade) {
  extract($GLOBALS);
  $l_dt_grade=Nvl($cDate[$w_dt_grade],'');
  if ($l_dt_grade>'') {
    $l_dt_final=strftime('%Y',($l_dt_grade)).'-';
    if (strlen(strftime('%m',($l_dt_grade)))==2)
      $l_dt_final=$l_dt_final.strftime('%m',($l_dt_grade)).'-';
    else
      $l_dt_final=$l_dt_final.'0'.strftime('%m',($l_dt_grade)).'-';
    if (strlen($Day[$l_dt_grade])==2)
      $l_dt_final=$l_dt_final.$Day[$l_dt_grade].'-';
    else
      $l_dt_final=$l_dt_final.'0'.$Day[$l_dt_grade].'-';
    if (strlen(strftime('%H',($l_dt_grade)))==2)
      $l_dt_final=$l_dt_final.'T'0x$our[$l_dt_grade].':';
    else
      $l_dt_final=$l_dt_final.'T0'0x$our[$l_dt_grade].':';
    if (strlen(strftime('%M',($l_dt_grade)))==2)
      $l_dt_final=$l_dt_final.strftime('%M',($l_dt_grade)).':';
    else
      $l_dt_final=$l_dt_final.'0'.strftime('%M',($l_dt_grade)).':';
    if (strlen(strftime('%S',($l_dt_grade)))==2)
      $l_dt_final=$l_dt_final.strftime('%S',($l_dt_grade));
    else
      $l_dt_final=$l_dt_final.'0'.strftime('%S',($l_dt_grade));
  } else {
    $l_dt_final='';
  } 
  return $l_dt_final;
} 
?>