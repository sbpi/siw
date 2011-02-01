CREATE OR REPLACE FUNCTION last_day(date) RETURNS DATE AS $$
DECLARE
    stdata      ALIAS FOR $1;
    stSQL       VARCHAR='';
    retorno     DATE;
    crCursor    REFCURSOR;
BEGIN
    stSQL := 'select cast(date_trunc(''month'', '''||stdata||'''::date) + ''1 month''::interval as date) - 1';
    raise notice '%', stSql;

    OPEN crCursor FOR EXECUTE stSQL;
        FETCH crCursor INTO retorno;
    CLOSE crCursor;

    RETURN retorno;
END; $$ LANGUAGE 'plpgsql' VOLATILE;
