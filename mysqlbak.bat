

:: ---------- ������ ----------?

:: ���ݷ��õ�·������ \?
set BACKUP_PATH=D:\sql_bak\

:: Ҫ���ݵ����ݿ����ƣ�����ÿո�ָ�?
set DATABASES=landsystem

:: MySQL �û���?
set USERNAME=root

:: MySQL ����?
set PASSWORD=

:: MySQL Bin Ŀ¼���� \?
:: �������ֱ��ʹ�� mysqldump����װʱ��� MySQL Bin Ŀ¼���˻������������˴����ռ���?
set MYSQL=D:\wamp\bin\mysql\mysql5.6.17\bin\

:: WinRAR �Դ������й��ߵĿ�ִ���ļ�·�������ļ���ע���� Dos ���ļ�����д��ʽ?
set WINRAR=C:\Progra~1\360\360zip

:: ---------- ���������޸� ----------?

set YEAR=%date:~0,4%
set MONTH=%date:~5,2%
set DAY=%date:~8,2%
:: ����� dos ������ time ���صĲ��� 24 Сʱ�ƣ�û�� 0 ��䣩���������޸Ĵ˴�?
set HOUR=%time:~0,2%
if /i %HOUR% LSS 10 (set HOUR=0%time:~1,1%)
set MINUTE=%time:~3,2%
set SECOND=%time:~6,2%


set DIR=%BACKUP_PATH%
set ADDON=%YEAR%%MONTH%%DAY%_%HOUR%%MINUTE%%SECOND%

:: backup
echo Start dump databases...

echo Dumping database %%DATABASES% ...
%MYSQL%mysqldump -u%USERNAME% %DATABASES% > %BACKUP_PATH%%DATABASES%_%ADDON%.sql

echo Done

:exit
