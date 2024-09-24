DROP PROCEDURE IF EXISTS stpInsertarRelUsuarioCorreo;
CREATE PROCEDURE `stpInsertarRelUsuarioCorreo` (`uuid2uuiecodRelUsuarioCorreov` VARCHAR(50), `uuid2uuiecodCorreov` VARCHAR(50), `uuid2ecodUsuariov` VARCHAR(50) )

BEGIN

declare existe int;
set existe = (select count(*) from  relusuariocorreo where ecodRelUsuarioCorreo   = uuid2uuiecodRelUsuarioCorreov);
if existe = 0
	then
	insert into relusuariocorreo(`ecodRelUsuarioCorreo`, `ecodCorreo`, `ecodUsuario` )
	values (uuid2uuiecodRelUsuarioCorreov,uuid2uuiecodCorreov,uuid2ecodUsuariov);
	SELECT uuid2uuiecodRelUsuarioCorreov AS Codigo;
else
    SELECT uuid2uuiecodRelUsuarioCorreov AS Codigo;	
END if;
end