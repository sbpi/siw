CREATE OR REPLACE FUNCTION nvl(varchar, int4) RETURNS int4 AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION nvl(int4, int4) RETURNS int4 AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION nvl("numeric", int4)   RETURNS int4 AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION nvl("numeric", "numeric")   RETURNS int4 AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION nvl(bpchar, bpchar)  RETURNS bpchar AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;

CREATE OR REPLACE FUNCTION nvl(date, date)  RETURNS date AS $$
DECLARE
  pCAMPO ALIAS FOR $1;
  pSUBST ALIAS FOR $2;
BEGIN
  if (pCAMPO isnull) then
    return pSUBST;
  else
    return pCAMPO;
  end if;
end; $$ LANGUAGE 'plpgsql' VOLATILE;
