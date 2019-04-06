

:: ---------- 配置项 ----------?

:: 备份放置的路径，加 \?
set BACKUP_PATH=D:\sql_bak\

:: 要备份的数据库名称，多个用空格分隔?
set DATABASES=landsystem

:: MySQL 用户名?
set USERNAME=root

:: MySQL 密码?
set PASSWORD=

:: MySQL Bin 目录，加 \?
:: 如果可以直接使用 mysqldump（安装时添加 MySQL Bin 目录到了环境变量），此处留空即可?
set MYSQL=D:\wamp\bin\mysql\mysql5.6.17\bin\

:: WinRAR 自带命令行工具的可执行文件路径，长文件名注意用 Dos 长文件名书写方式?
set WINRAR=C:\Progra~1\360\360zip

:: ---------- 以下请勿修改 ----------?

set YEAR=%date:~0,4%
set MONTH=%date:~5,2%
set DAY=%date:~8,2%
:: 如果在 dos 下输入 time 返回的不是 24 小时制（没有 0 填充），请自行修改此处?
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
