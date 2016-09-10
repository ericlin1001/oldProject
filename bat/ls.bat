@echo off
goto :s3

:s1
@dir /W|find /v "Dir(s)"
goto :exit

:s2
@dir /W|find /v "File(s)"
goto :exit

:s3
@dir /W|find /v "Dir(s)"|find /v "File(s)"
goto :exit


:exit
