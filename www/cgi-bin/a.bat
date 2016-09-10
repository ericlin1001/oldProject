@echo off
set oldName=cgitest.exe
set name=test-post.cgi
rem set /p name=New name:
del %name%
ren %oldName% %name%
start cmd