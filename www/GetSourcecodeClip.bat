@echo off
rem 复制全部到一个文本文件中，并保存为GetSourceCode.bat即可
rem ****************************Created by Eric.****************************
echo ******************GetSourceCode******************
echo 仅供学习之用！！
echo 请按提示一步一步做
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
echo 找到登录后的soj.me设定（setting）界面中的Signature的框，粘贴，点提交，signature中会产生一些内容，将其复制出来即可！
echo 继续?
pause>nul
echo ',signature=(select concat('/*\nlang:',language,'\ncodelen:',codelength,'\nsourcecodelen:',length(sourcecode^),'\nrun_time:',run_time,'\nrun_memory:',run_memory,'\nheadCode:',substr(sourcecode,1,50^),'\n*/'^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^) ^>0 order by codelength limit %nth%,1^) where username='%username%'# >%systemroot%\temp\mess.txt 
rem notepad %systemroot%\temp\mess.txt
clip<%systemroot%\temp\mess.txt 
echo 已复制代码到剪切板，在signature中粘贴即可。
echo.
for /l %%i in (1,100,10000) do (


pause>nul
echo ',signature=(select substr(sourcecode,%%i,100^) from status where pid=%pid% and status='Accepted' and language='%lang%' and length(sourcecode^)^>0 order by length(sourcecode^) limit %nth%,1^) where username='%username%'#>%systemroot%\temp\mess.txt 
rem notepad %systemroot%\temp\mess.txt 
clip<%systemroot%\temp\mess.txt 
echo Index:%%i
echo 已复制代码到剪切板，在signature中粘贴即可。
echo 继续下次...
echo.

)
rem ****************************Created by Eric.****************************