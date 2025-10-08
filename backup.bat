@echo off
echo Iniciando backup do banco MAP 4 PLAY...
set BACKUP_DIR=backups
if not exist %BACKUP_DIR% mkdir %BACKUP_DIR%
pg_dump -U postgres -h localhost map4play > %BACKUP_DIR%\backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql
echo Backup concluído: %BACKUP_DIR%\backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql
pause