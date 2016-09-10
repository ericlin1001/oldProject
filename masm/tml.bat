@echo off
set cp=%~dp0
set startPath=%cd%
if "%1"=="" (
echo tml.bat
echo Used to masm and link xxx.asm
echo Usage:
echo tml xxx.asm
echo will build a build.exe in %cp%
pause
) else (
copy %1 %cp%build.asm
set name=%1
cd %cp%
call myml %cp%build.asm
copy %cp%build.exe %name:~-3%.exe
rem del %cp%build.asm 
cd %startPath%
)