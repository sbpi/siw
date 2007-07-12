CREATE OR REPLACE FUNCTION to_number(bpchar)
  RETURNS int8 AS
$BODY$
 DECLARE
 pCAMPO ALIAS FOR $1;
 cCAMPO bpchar;
 vCAMPO int8;
BEGIN
 cCAMPO := trim(translate(upper(pCAMPO),'⁄¡…Õ”‘€Œ¬ √’‹Ÿ¿»Ã“ 
QWERTYUIOP[]ASDFGHJKL;ZXCVBNM,./<>?|{}:"-_=+)(*&[EMAIL PROTECTED]',''));
 if (cCAMPO='') then
   cCAMPO='0';
 end if;
 vCAMPO := CAST (cCAMPO as int8);
 --vCAMPO := cCAMPO;
 return vCAMPO;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;