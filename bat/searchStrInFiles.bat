@echo off
set findStr=%~1
set files=%2
if "%files%" == "" set files=*.bat
echo ******Follwoing files contain "%findStr%"******
for /r %%i in (%files%) do (
find /i "%findStr%" %%i >nul && echo %%i
)
