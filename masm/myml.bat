@echo off
set name=%1
if "%name:~-4%"==".asm" set name=%name:~0,-4%
echo **********masming %name%.asm**********
masm %name%.asm<E:\masm\3CR
echo **********linking %name%.obj*************
link %name%<E:\masm\3CR