alter FUNCTION dbo.LastDay ( @pInputDate    DATETIME )
RETURNS DATETIME
BEGIN

    RETURN dateadd(day,-1* day(dateadd(month,1,@pInputDate)),dateadd(month,1,@pInputDate))

END
