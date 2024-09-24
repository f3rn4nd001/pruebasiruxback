DROP PROCEDURE IF EXISTS stpInsertarLogin;
CREATE PROCEDURE `stpInsertarLogin`(ecodCorreov VARCHAR(150),tokenv VARCHAR(500), ipv VARCHAR(30))

BEGIN
declare existe int;

set existe = (select count(*) from bitcorreo bc where bc.ecodCorreo = ecodCorreov);
if existe = 1
then

UPDATE bitcorreo set tToken = tokenv, tIp = ipv where ecodCorreo = ecodCorreov;
SELECT 'Se ha editado un registro en la tabla bitcorreo' rel;

else
SELECT 'codigo no encontrado' rel;

END if;
end
