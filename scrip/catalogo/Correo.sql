DROP PROCEDURE IF EXISTS stpInsertarBitCorreo;
CREATE PROCEDURE `stpInsertarBitCorreo` (`uuid2uuiecodCorreo` VARCHAR(50), `tCorreov` VARCHAR(100), `tContraseñav` VARCHAR(250) )

BEGIN

declare existe int;
set existe = (select count(*) from  bitcorreo where ecodCorreo  = uuid2uuiecodCorreo);
if existe = 0
	then
	insert into bitcorreo(`ecodCorreo`, `tCorreo`, `tpassword` )
	values (uuid2uuiecodCorreo,tCorreov,tContraseñav);
	SELECT uuid2uuiecodCorreo AS Codigo;
else
    SELECT uuid2uuiecodCorreo AS Codigo;	
END if;
end

