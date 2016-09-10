@echo off
rem 复制全部到一个文本文件中，并保存为GetSourceCode.bat即可
rem ****************************Created by Eric.****************************
echo ******************GetSourceCode******************
echo 请按提示一步一步做,错了就要重来...
rem goto :start
set /p username=你的用户名（登录时用的):
set /p pid=题目ID:
:getlang
set /p lang=语言类型（C++、C)?
if not "%lang%"=="C++" if not "%lang%"=="C" goto :getlang
:getnth
set /p nth=第几个(默认为0,找不到答案时，再取其它数字，10以内）?
if "%nth%"=="" set nth=0
if %nth% GTR 10 goto :getnth
if %nth% LSS 0 goto :getnth
:start
echo.
echo 注意！！
pause>nul
echo 复制将打开的记事本上的内容（不要包含回车）到登录后的soj.me设定（setting）界面中的Signature的框内，点提交，signature中会产生一些内容，将其复制到一个新地方即可！
echo 然后关闭记事本，再重复即可。
echo 记住了吗？
echo Press any key to continue...
pause>nul
echo ',signature=(select concat('/*\nlang:',language,'\ncodelen:',codelength,'\nsourcecodelen:',length(sourcecode^),'\nrun_time:',run_time,'\nrun_memory:',run_memory,'\nheadCode:',substr(sourcecode,1,50^),'\n*/'^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^) ^>0 order by codelength limit %nth%,1^) where username='%username%'# >%systemroot%\temp\mess.txt 
rem notepad %systemroot%\temp\mess.txt 
%systemroot%\temp\mess.txt 
for /l %%i in (1,100,10000) do (
echo Again...
pause>nul
echo ',signature=(select substr(sourcecode,%%i,100^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^)^>0 order by length(sourcecode^) limit %nth%,1^) where username='%username%'#>%systemroot%\temp\mess.txt 
rem notepad %systemroot%\temp\mess.txt 
%systemroot%\temp\mess.txt 
)
rem ****************************Created by Eric.****************************