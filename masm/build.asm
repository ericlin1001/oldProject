
;***************************************;
;*            A final project          *;
;***************************************;
;********************macro definition***********************

registerCallBackFun macro fun,frequency
;you should use this as entry point,for avoid critical condition.
;f=frequency/100
push ax
push cx
mov ax,offset fun
mov cx,frequency;3.5Hz,f=cx/100
call registerCallHz
pop cx
pop ax
endm

registerStage macro fun,leaveFun,stage
;use index as stage's index.
push bx
mov bx,numStage
shl bx,1
mov [stageEnterAddress+bx],offset fun
mov [stageLeaveAddress+bx],offset leaveFun
shr bx,1
inc bx
mov numStage,bx
pop bx
endm

enterStage macro stage
push bx
mov bx,offset stageEnterAddress+stage*2
mov currentStage,stage
call [bx]
pop bx
endm

leaveStage macro stage
local noLeaveFun
push bx
mov bx,offset stageLeaveAddress+stage*2
cmp word ptr [bx],0
je noLeaveFun
call [bx]
noLeaveFun:
pop bx
endm

popCallBack macro
push ax
mov ax,callBackNum         
dec ax
mov callBackNum,ax
pop ax
endm

showNumInLeds macro num
push ax
mov ax,num
aam
call output7segLeds
pop ax
endm

mapkey macro reg,up,down,left,right
local turnL,turnR,turnU,turnD,endMap
cmp reg,left
jz turnL
cmp  reg,right
jz turnR
cmp  reg,up
jz turnU
cmp  reg,down
jz turnD
;default ,set reg=0
mov reg,0
jmp endMap
turnL:mov reg,1
jmp endMap
turnR:mov reg,3
jmp endMap
turnU:mov reg,2
jmp endMap
turnD:mov reg,4
jmp endMap
endMap:
endm

showMess macro messAddress
push dx
push ax
lea dx,messAddress
mov ah,09h
int 21h
pop ax
pop dx
endm
showChar macro char
push dx
push ax
mov dl,char
mov ah,02h
int 21h
pop ax
pop dx
endm
CR macro
push ax
push dx
mov ah,02h
mov dl,0ah
int 21h
mov dl,0dh
int 21h
pop dx
pop ax
endm
showReg macro reg
push ax
mov ax,reg
add al,'0'
add ah,'0'
showChar al
showChar ah
pop ax
endm

in2 macro reg,port
;return reg.
push dx
mov dx,port
in reg,dx
pop dx
endm
out2 macro port, value
;change nothing.
push dx
push ax
mov dx,port
mov al,value
out dx,al
pop ax
pop dx
endm

pushi macro imm
;will change dx.
mov dx,imm
push dx
endm
pusha macro 
push ax
push bx
push cx
push dx
push si
push di
push ds
push es
endm
popa macro
pop es
pop ds
pop di
pop si
pop dx
pop cx
pop bx
pop ax
endm


getBit macro reg,n ;n start from 0
;change and return reg.
push cx
mov cl,n
shr reg,cl
and reg,1
pop cx
endm
setBit macro reg,n; 
push cx
mov cl,n
mov ch,1h
shl ch,cl
or reg,ch
pop cx
endm
resetBit macro reg,n
push cx
mov cl,n
mov ch,1h
shl ch,cl
not ch
and reg,ch
pop cx
endm
inverseBit macro reg,n
push cx
mov cl,n
mov ch,1h
shl ch,cl
xor reg,ch
pop cx
endm
reverseBits macro reg;srcReg can't be ah,change ax
push cx
mov cx,8
local loop1
loop1:
rcr reg
rcl ah
loop loop1
mov reg,ah
pop cx
endm
;***********************end macro definiction***********************
testIfExit macro
call testEndAll
mov al,exitAll
cmp al,1
jz exit1
endm
;************************basic configuration***********************
;pc0,pc1 control music,PA0 as counter1.out
;PA1,PA2 as left and right (single pluse,and reset key)
;LS273 control led
;counter0,counter1 as the hardDelay
;counter2 control the frequency of music
ledport equ 280h
;chip8253
counterControl equ 293h
counter0 equ 290h
counter1 equ 291h
counter2 equ 292h
;chip8255
PA equ 2a0h
PB equ 2a1h
PC equ 2a2h
chip8255Control equ 2a3h
;
ledArrh equ 2a8h ;CS1
ledArrlr equ 2b0h;CS2
ledArrly equ 2b8h;CS3
;
leftBtn equ PA1
rightBtn equ PA2
;*********************for snack data*************************
DATA    SEGMENT
head@@3PAUNode@@A     DD     01H     DUP (0)            ; head
pivot@@3PAUNode@@A    DD    01H     DUP (0)            ; pivot
food@@3PAUNode@@A     DD     01H     DUP (0)            ; food
isLose@@3DA           DB    01H     DUP (0)            ; isLose
isWin@@3DA        DB    01H     DUP (0)            ; isWin
len@@3EA        DB    01H     DUP (0)            ; len
heap@@3UHeap@@A     DB     0208H     DUP (0)            ; heap
graph@@3PAY07EA     DB    040H     DUP (0)            ; graph
outputData@@3PAEA     DB    08H     DUP (0)            ; outputData
foodLedData         DB 8 DUP(0)
;constant:
winMess db "You wins!",0ah,0dh,"$"
loseMess db "You lost!",0ah,0dh,"$"
dirs@@3PAUDir@@A DB 00H,00H,0ffH, 00H,00H,01H, 01H,00H, 00H, 0ffH
;
idir@@3EA     DB    03H    ; idir
winLen@@3EA     DB    5    ; winLen
;for rand.
c@@3HA        DW    07H    ; c
an@@3HA        DW    07H    ; an
DATA    ENDS
;*********************end for snack data*************************

;************************simple data***********************
;*********prompt message's data***********
data  segment 
foodAtMess       db "Food is at $"
doing           db "*$"
doing1          db "#$"
exitMess        db "exiting...",0dh,0ah,"$"
restartMess     db "restarting!",0dh,0ah,"$"
mess            db 'TPCA interrupt!',0dh,0ah,'$'
messConsult     db 'consulting!',0dh,0ah,'$'
messEndCounter  db 'reachEndCounter !',0dh,0ah,'$'
initSuccess db "init successfully!$"
beginWait db "begin wait...!$"
num0123 db "0123456789$"
beginMess db "Now,begin this program...",0dh,0ah,"$"
testCount db '0'
curDir dw 0
youPressKey db "You press key:$"
data ends

;
;**********ledArr's data**********
data  segment 
;ledArr encoding:
;high->low
;|
;low
arrDataEmpty0 db 0,0,0,0,0,0,0,0,0h
arrData1 db 0,0,0,20h,7fh,0,0,0
arrData11 db 0,0,0,20h,7fh,0,0,0
arrData3 db 0h,0h,22h,41h,49h,36h,0h,0h 
arrData4 db 0h,0ch,14h,24h,7fh,4h,0h,0h
arrData8 db 0,0,36h,49h,49h,36h,0,0
arrData0 db 0,0,3eh,41h,41h,3eh,0,0
arrData7 db 0,0,40h,4eh,50h,60h,0,0
arrData6 db 0,0,3eh,49h,49h,26h,0,0
arrDataLin db 28h,30h,7fh,30h,28h,28h,30h,7fh,30h,28h
arrDataJun db 10h,20h,7fh,80h,54h,0edh,5ah,0edh,50h,0h,0h
arrDataHao db 0h,4h,52h,54h,0h,0d7h,55h,0f5h,55h,17h,0h
arrDataEmpty1 db 0,0,0,0,0,0,0,0
endArrData equ $-8
;you,lost,win arrData
arrDataLostBegin equ $
snackLoseBuffer db 8 dup(0)
arrDataY  db 40h,20h,1fh,20h,40h,0h
arrDataO  db 00h,3eh,41h,41h,3eh,0h
arrDataU  db 7eh,01h,01h,01h,7eh,0h
arrDataL  db 00h,7fh,01h,01h,01h,0h
arrDataO0 db 00h,3eh,41h,41h,3eh,0h
arrDataS  db 00h,32h,49h,49h,26h,0h
arrDataT  db 40h,40h,7fh,40h,40h,0h
arrDataEx db 00h,30h,7dh,30h,00h,0h;!
arrDataLostEnd equ arrDataEx-8

arrDataWinBegin equ $
snackWinBuffer db 8 dup(0)
arrDataY1  db 40h,20h,1fh,20h,40h,0h
arrDataO1  db 00h,3eh,41h,41h,3eh,0h
arrDataU1  db 7eh,01h,01h,01h,7eh,0h
arrDataW   db 7eh,01h,1eh,01h,7eh,0h
arrDataI   db 41h,41h,7fh,41h,41h,0h
arrDataN   db 7fh,20h,1ch,02h,7fh,0h
arrDataEx1  db 00h,30h,7dh,30h,00h,0h;!
arrDataWinEnd equ arrDataEx1-8


;
arrData equ arrDataEmpty0
ledArrDataEmpty db 8 DUP(0)
testLedArrData db 0ffh,91h,91h,0ffh,91h,91h,91h,0ffh
testRegLedArr db 3eh,22h,22h,22h,22h,22h,22h,3eh
testGreenLedArr db 0h,0h,8h,8h,8h,0h,0h,0h
;used by callBack,as buffer,just like video buffer.
ledArrRedDataBuff db 0h,7ch,44h,44h,44h,44h,7ch,0h
ledArrGreenDataBuff db 0h,0h,0h,10h,10h,0h,0h,0h
data ends
;
;**********music's data**********
data  segment 
soundTable1 dw 524,588,660,698,784,880,988,1048;high voice
soundTable2 dw 262,294,330,347,392,440,494,524;low voice
music1 db 1,2,3,1,1,2,3,1,3,4,5,5,3,4,5,5,5,6,5,4,3,1,5,6,5,4,3,1,1,5,1,1,1,5,1,1,0
music2 db 3,3,5,5,3,3,5,5,3,5,1,7,6,6,5,3,4,1,2,2,3,4,1,0
musicBuff dw 100 dup(0);store the translated music's frequency.
data ends
;**********basic variables's data***********
data  segment
;****for 7-seg led****
led0Buff    db 0;used by refresh7segled
led1Buff    db 0
led0        db 0
led1        db 0
led         db 3fh,06h,5bh,4fh,66h,6dh,7dh,07h,7fh,6fh ;7-seg translating code.
ledCount    db -1;the number 7-seg led shows.
;*****for other procedures.*****
pause       db 1
exitAll     db 0
PA1         db 0 ;left
PA2         db 0 ;right
vector1ch   dw 0,0 ;for save the CS:IP origionally stores.
;***for finite-state-machine***
currentStage dw 0
numStage dw 0
stageEnterAddress dw 10h DUP(0)
stageLeaveAddress dw 10h DUP(0)
;********for call back**********
callBackFuns        DW 10h DUP(0)
callBackCountAmount DW 10h DUP(0)
callBackCountUp     DW 10h DUP(0)
callBackNum         DW 0 
;control
isCallBack          DB 1
isShowLedArr        DB 0
isShowLed           DB 0
data ends
;
;***********new added data***********
data segment
pressKey db 0;0 means no key.
stage1P dw 0
;for  stage3Music
currentTuneP dw 0
;for flowShow
startFlowRed         DW 0
startFlowGreen         DW 0
endFlowRed        DW 0
currentFlowRedP        DW 0
currentFlowGreenP    DW 0
onFlowComplete     DW 0
isIncGreen         DB 0
data ENDS
;************************end simple data***********************


;**********************code segment*********************
_TEXT segment
assume cs:_TEXT ,ds:data
start:
mov ax,data
mov ds,ax
mov es,ax
mov isShowLedArr,0
mov isCallBack ,0
mov isShowLed ,0
mov isIncGreen,0
cli
;call init
call initRandSeed
call initInt
call init@@YAXXZ
showMess initSuccess
CR
;*************begin snack*******************
;register some procedures:
registerStage begin,0,0
registerStage stage1,leaveStage1,1
registerStage stage2,leaveStage2,2
registerStage stage3,leaveStage3,3
registerStage stage4,leaveStage4,4
registerStage stage5,leaveStage5,5
;פ������
;registerCallBackFun refresh7segLed0 ,482*100 ;
;registerCallBackFun refreshLedArr ,80*100 ;
registerCallBackFun checkIfChangeStage ,482*100 ;
;registerCallBackFun refresh7segLed1 482*100 ;482Hz
jmp beginMainInit
beginMainInit:
showMess beginWait
CR
enterStage 5
mov isCallBack ,1
mov isShowLedArr,1
mov isIncGreen,1;used by flowShow
mov isShowLed ,1
mov winLen@@3EA,4
sti
waiting:jmp waiting
;;;;;;;;;;;;;;;;

begin proc
showChar 's'
showChar ':'
showChar '0'
CR
showMess beginMess
ret
begin endp


;**************stage1*********
stage1  proc
pusha
showChar 's'
showChar ':'
showChar '1'
CR
lea si,arrData
mov stage1P,si
registerCallBackFun stage1FlowShowNumberName,15*100;1Hz
popa
ret
stage1  endp

leaveStage1 proc
call popCallBackProc
showChar 'l'
showChar ':'
showChar '1'

CR
ret
leaveStage1 endp

;**************stage2*********
stage2  proc
pusha 
showChar 's'
showChar ':'
showChar '2'
CR


call init@@YAXXZ
mov ah,0
mov al,len@@3EA
showNumInLeds ax
mov curDir,1
call dynamicMove
popa
ret
stage2  endp

leaveStage2 proc
showChar 'l'
showChar ':'
showChar '2'
CR
ret
leaveStage2 endp
;**************stage3*********
stage3  proc
showChar 's'
showChar ':'
showChar '3'
CR
call init@@YAXXZ
registerCallBackFun dynamicMove,7*100
push si 
lea si,music1
call selectMusic
mov si,offset musicBuff
mov currentTuneP,si
registerCallBackFun stage3Music,1*100
pop si
ret
stage3  endp

leaveStage3 proc
call popCallBackProc    ;end musci
call popCallBackProc    ;end snack move.
showChar 'l'
showChar ':'
showChar '3'
CR
ret
leaveStage3 endp

;**************stage4*********
stage4  proc
pusha
showChar 's'
showChar ':'
showChar '4'
CR
registerCallBackFun twinkle,10*100;1Hz
;TODO://test iswin ,to dertermine which to show.
;copy the final situation to snackBuffer.
mov cx,8
lea si,outputData@@3PAEA
cmp isLose@@3DA,1
je showLose
lea di,snackWinBuffer 
rep movsb
lea si,arrDataWinBegin
lea bx,arrDataWinEnd
jmp endLostOrWin
showLose:
lea di,snackLoseBuffer
rep movsb
lea si,arrDataLostBegin
lea bx,arrDataLostEnd
endLostOrWin:
lea di,ledArrDataEmpty
mov ax,offset complelteFlowShow
mov isIncGreen,0
call configureFlowShow;will register a callBack.
popa
ret
stage4  endp

leaveStage4 proc
call popCallBackProc;end flowShow
call popCallBackProc;end twinkle.
showChar 'l'
showChar ':'
showChar '4'
CR
ret
leaveStage4 endp

complelteFlowShow proc
push ax
mov ax,startFlowRed
mov currentFlowRedP,ax
mov ax,startFlowGreen
mov currentFlowGreenP,ax
registerCallBackFun flowShow,3*100;1Hz
showChar 'a'
showChar 'g'
showChar 'a'
showChar 'i'
showChar 'n'
CR
pop ax
ret
complelteFlowShow endp
;**************stage5*********
stage5  proc
pusha
showChar 's'
showChar ':'
showChar '5'
CR

popa
ret
stage5  endp

leaveStage5 proc
showChar 'l'
showChar ':'
showChar '5'
CR
ret
leaveStage5 endp

;**************stage2*********
;;**************end stage definition*********

stage3Music proc
;playing music.
push ax
push si
call closeSound
mov si,currentTuneP
mov ax,ds:[si]
call openSoundWithCounter 
add si,2
mov currentTuneP,si
pop si
pop ax
ret
stage3Music  endp

twinkle proc near
showChar 't'
push ax
mov al,isShowLedArr 
xor al,1
mov isShowLedArr,al
mov al,isShowLed 
xor al,1
mov isShowLed,al
pop ax
ret
twinkle endp

configureFlowShow proc
;input startFlowRed,startFlowGreen,endFlowRed
;keep track of currentFlowRedP,currentFlowGreenP
push bx
push si
push di
push ax
mov startFlowRed,si
mov startFlowGreen,di
mov endFlowRed,bx
mov currentFlowRedP,si
mov currentFlowGreenP,di
mov onFlowComplete,ax
mov onFlowComplete,offset complelteFlowShow 
registerCallBackFun flowShow,3*100;1Hz
pop ax
pop di
pop si
pop bx
ret
configureFlowShow endp

flowShow proc
;showChar 'f'
push ax
push si
push di
mov di,currentFlowGreenP
mov si,currentFlowRedP
mov ax,endFlowRed
cmp si,ax
jbe flowShowing
popCallBack
push bx
mov bx,onFlowComplete
call bx
pop bx
jmp endFlowShow
flowShowing:
call ouputLedArr
add si,2
add di,2
mov currentFlowRedP,si
cmp isIncGreen,0
je endFlowShow
mov currentFlowGreenP,di
endFlowShow:
pop di
pop si
pop ax
ret
flowShow endp

stage1FlowShowNumberName proc
pusha
lea di,ledArrDataEmpty
mov si,stage1P
lea ax,endArrData
cmp si,ax
jbe conintueShow
leaveStage 1
enterStage 2
jmp endstage1FlowShowNumberName 
conintueShow:
call ouputLedArr
add si,2
mov stage1P,si
endstage1FlowShowNumberName:
popa
ret
stage1FlowShowNumberName endp

popCallBackProc proc
popCallBack
ret
popCallBackProc endp

dynamicMove proc 
push ax
mov ax,curDir
call moveSnack 
pop ax
ret
dynamicMove endp



jmp exit


;*************end main*******************

jmp enddoNothing
doNothing:
jmp doNothing
enddoNothing:

jmp endtestDemo
testDemo:
agn:
in2 al,PA
mov bx,0
push ax
getBit al,1

jz test2
;PA1 is high
showChar 'L'
jmp agn

test2:
pop ax
getBit al,2
jz agn
;PA2 is high
showChar 'R'
jmp testDemo
endtestDemo:


jmp emdflowShowArrDemo
flowShowArrDemo:
call flowShowArr
jmp flowShowArrDemo
emdflowShowArrDemo:

jmp endledDemo
ledDemo:
mov bl,led0
mov bh,led1
;call softShowLed 
call hardShowLed
call incLed01
showMess doing
jmp ledDemo
endledDemo:



jmp exit

;;testIfExit
out2 PC,00000001b ;a test.
;let al as PC.
mov al,1h
loop1:
call hardDelay
inverseBit al,7
out2 PC,al
jmp loop1



exit1:jmp exit

;*************intMain program*******
intMain:
mov al,20h
out 20h,al ;send EOI.

sti
mov al,pause
inverseBit al,0
mov pause,al
cmp al,1
jz next
showMess mess
call singSong
call showAndIncLedCount
sti
;****************end main********************
jmp next ;base on cx,when cx is 0,exit.
exitIntProm:
in al,21h
or al,08h ;disable the IRQ3.
out 21h,al 
sti
mov ah,4ch
int 21h
next:   
iret
;end intMain

jmp exit
exit: 
;reset 1ch interrupt vector.
mov dx,vector1ch
mov ds,vector1ch+2
mov ax,251ch
int 21h
;exiting...
mov ah,4ch    ;exit and return to dos.
int 21h
_TEXT  ends
;************************for basic procedure***************
_TEXT    SEGMENT

registerCallHz proc near
;input ax,as proc's address,cx/1000 as frequency.
;483*1000=75EB8h
push dx
push cx
push ax
mov dx,0
;here puzzle me,
;it should let ax=real frequency*100
;mov ax,482*100
mov ax,182*10
div cx
mov dx,0
mov cx,ax
pop ax
push ax
call registerCallBack
pop ax
pop cx
pop dx
ret
registerCallHz endp


registerCallBack proc near;??why it's frequency is 483Hz.
;input: ax as proc's address,cx as the count down times
;so,it's frequency:483Hz/cx
push ax
push cx
push si
mov si,callBackNum
shl si,1                ;attention: si=si*2,because they are word,say,2byte.
mov ds:[callBackFuns+si],ax
mov ds:[callBackCountAmount+si],cx
mov ds:[callBackCountUp+si],word ptr -1;this make the callBack is invoked immediately.
shr si,1
inc si
mov callBackNum,si
pop si
pop cx
pop ax
ret 
registerCallBack endp

;new version callBack:
callBacknew proc near
pusha
cmp byte ptr isCallBack,0
je endReenterCallBack
mov isCallBack,0
mov cx,callBackNum
cmp cx,0
je endAllCallBack
lea bx,callBackFuns
lea si,callBackCountUp
lea di,callBackCountAmount
callEveryFuns:
;test if countUp reach amount,call that function.
mov ax,ds:[si]
inc ax
mov ds:[si],ax
mov dx,ds:[di]
cmp ax,dx
jb notReachAmount
call word ptr [bx]
mov word ptr ds:[si],0
notReachAmount:
;end test
add bx,2    ;attention: it's add bx,2,rather than inc bx.!!!
add si,2
add di,2
loop callEveryFuns
endAllCallBack:
mov isCallBack,1
endReenterCallBack:
popa
ret
callBacknew endp



callBack proc near
push ax
push ds
push es
cli
;init
mov ax,data
mov ds,ax;must set ds,because ds will be changed while int occurs.
mov es,ax
;check if call back.
cmp isCallBack ,0
je  notCallBack
call callBacknew
notCallBack:
sti
pop es
pop ds
pop ax
iret
callBack endp


testEndAll proc near
push dx
push ax
mov dl,0ffh
mov ah,06h
int 21h
cmp al,01bh
jnz notExit
mov exitAll,1
notExit:
pop ax
pop dx
ret
testEndAll endp

;*******************fun:delay****************
softDelay proc near          ;
push cx
push ax
mov ax,130
xx1: mov cx,0ffffh
xx2: loop xx2
dec ax
jnz xx1
pop ax
pop cx
ret
softDelay endp
littleDelay proc near
;input al. and al<=15
push cx
push ax
push bx
mov cl,al
;cl is the times
mov ax,01h
shl ax,cl
mov cx,ax
;cx is the times
mov ax,1
mov bx,0
rrr:
rol ax,1
rcl bx,1
loop rrr
mov cx,ax
little:
loop little
cmp bx,0
jz endLittle
dec bx
mov cx,0ffffh
jmp little
endLittle:
pop bx
pop ax
pop cx
ret
littleDelay endp

hardDelay proc near;change nothing
push ax
push dx
initCounter1:
mov ax,1000;1000 ms,say, 1 seconds
mov dx,counter1 
out dx,al
mov al,ah
out dx,al
;end init
consultCounter1:
in2 al,PA
getBit al,0
cmp al,1h
jz counterEnd
showMess messConsult
jmp consultCounter1
counterEnd:
showMess messEndCounter
pop dx
pop ax
ret
hardDelay endp
softShowLed proc near
;input bx
push cx
mov cx,0ffh
ll:
push bx
call showLeds
pop bx
loop ll
pop cx
ret
softShowLed endp

hardShowLed proc near;change nothing
push ax
push dx
;init 
mov ax,1000;1000 ms,say, 1 seconds
mov dx,counter1 
out dx,al
mov al,ah
out dx,al
;end init
consultCounter1Led:
;show led
mov bl,led0
mov bh,led1
call showLeds
;end show led
in2 al,PA
getBit al,0
cmp al,1h
jz counterEndLed
showMess messConsult
jmp consultCounter1Led
counterEndLed:
showMess messEndCounter
pop dx
pop ax
ret
hardShowLed endp

;*******************fun:init****************
init proc near;change everything
pusha
cli
call initIRQ3
call init8253
call init8255
call initInt
call initRandSeed
popa
ret 
init endp

initIRQ3 proc near
;set interrupt vector.
mov ax,cs
mov ds,ax
mov dx,offset intMain
mov ax,250bh
int 21h
;end set int vector.

;begin set IRQ3 enabled.
in al,21h
and al,0f7h ;set IRQ3 enabled.
out 21h,al
mov cx,12 ;set the amount of key press before exit.
;end
ret
initIRQ3 endp

init8255 proc near
;init chip 8255,init control port.  PA=> internal =>PC
out2 chip8255Control,10010000b ;PA as input.PC as ouput. 
ret
init8255 endp

initRandSeed proc near
pusha
;read seconds in al,minutes in ah.
mov al,0;read second
out 70h,al
in al,71h
mov cl,al
mov al,2;read minutes.
out 70h,al
in al,71h
mov ch,al
mov an@@3HA,cx
popa
ret
initRandSeed endp

init8253 proc near
pusha
;begin init chip 8253
mov dx,counterControl;set the countercontrol
mov al,00110110b ;counter0 ,read/write H,L,operate in method 3,square wave continuously,binary
out dx,al
mov al,01110000b ;counter 1 ,read/write H,L,operate in method 0,(when reach,keep high until init again),binary
out dx,al
mov al,10110110B  ;counter2,read/write H,L ,3 method,binary.
out dx,al
      
;initialize the 1000 as counter0,counter1
mov ax,1000
mov dx,counter0 
out dx,al
mov al,ah
out dx,al
popa
ret
init8253 endp

initInt proc near
pusha
;set interupter vector 1ch,whose frequenzy 18.2HZ
mov ax,351ch
int 21h
mov vector1ch,bx
mov vector1ch+2,es
mov dx,offset callBack 
mov ax,seg callBack 
mov ds,ax
mov ax,251ch
int 21h
popa
ret
initInt endp
;*******************fun:music****************
selectMusic proc 
;input music in si
push ax
push bx
push si
push di
lea di,musicBuff;word
mov bh,0
loopTranslate:
mov al,ds:[si]
cmp al,0
je endLoopTranslate
sub al,1
shl al,1             ;al=al*2
mov bl,al            ;save offset to bx
mov ax,4240H         ;start cal initN = 1000000 / frequency, save in AX
mov dx,0FH     ;now dx,ax =1000,000
div word ptr ds:[soundTable1+bx]
mov ds:[di],ax
inc si
add di,2
jmp loopTranslate
endLoopTranslate:
mov ds:[di],word ptr 0
pop di
pop si
pop bx
pop ax
ret
selectMusic endp


openSoundWithCounter proc near
;input ax,will be transport to counter.
push ax
push dx
;start set counter frequency
mov dx,counter2        
out dx,al            ;write low byte
mov al,ah
out dx,al            ;write high byte
;start sounding
out2 PC,03h          ;set PA1PA0 = 11(opne sound player)
pop dx
pop ax
ret
openSoundWithCounter endp

closeSound proc
out2 PC,0h          ;set PA1PA0 = 00(close sound player)
ret 
closeSound  endp

playOneSound proc near
;input al as 1,2,3,45,6
push ax
push bx
push dx
;start translate
sub al,1
shl al,1             ;al=al*2
mov bl,al            ;save offset to bx
mov bh,0
mov ax,4240H         ;start cal initN = 1000000 / frequency, save in AX
mov dx,0FH  ;now dx,ax =1000,000
div word ptr[soundTable1+bx]
;start set counter frequency
mov dx,counter2        
out dx,al            ;write low byte
mov al,ah
out dx,al            ;write high byte
;start sounding
out2 PC,03h          ;set PA1PA0 = 11(opne sound player)
call softDelay
out2 PC,0h          ;set PA1PA0 = 00(close sound player)
pop dx
pop bx
pop ax
ret
playOneSound endp

singSong proc near
mov bx,0h
singing:
mov al,pause
cmp al,1
jz endSing
mov al,[music1+bx]
cmp al,0
jz endSing
push bx
mov bh,bl
and bh,1
mov bl,al
mov bh,0
call showled
pop bx
call playOneSound
inc bx
jmp singing
endSing:
ret
singSong endp

;*******************fun:7-seg led****************
refresh7segLed0 proc near
push ax
push dx
cmp isShowLed,0
je notShow7segLed0
mov al,led0Buff
or al,80h
mov dx,ledport
out dx,al
jmp endRefresh7segLed0
notShow7segLed0:
call close7segLed 
endRefresh7segLed0:
pop dx
pop ax
ret
refresh7segLed0 endp

close7segLed proc 
out2 ledport,80h
ret
close7segLed endp

refresh7segLed1 proc near
;input bx
push ax
push dx
cmp isShowLed,0
je endRefresh7segLed1
mov al,led1Buff
cmp al,led
je endRefresh7segLed1
and al,7fh
mov dx,ledport
out dx,al
endRefresh7segLed1 :
pop dx
pop ax
ret
refresh7segLed1 endp

output7segLeds proc near
;input ax.
push ax
push si
push bx
;set led0
mov si,offset led   
mov bl,al
mov bh,0
mov al,byte ptr ds:[si+bx]
mov led0Buff,al
;set led1
mov bl,ah
mov bh,0
mov al,byte ptr ds:[si+bx]
mov led1Buff,al
pop bx
pop si
pop ax
ret
output7segLeds endp

showled proc near
;change and only change bx.
;input bl as num
;input bh 0 as led0,bh=1 as led1
push si
push ax
push dx
push bx

mov si,offset led            ;set led's segCode in SI
mov al,bl
mov ah,0
add si,ax                    ;cal led's code.
mov al,byte ptr ds:[si]
cmp bh,0h
jz setLed0
jmp outputToLed
setLed0:
or al,80h
outputToLed:
mov dx,ledport
out dx,al

pop bx
pop dx
pop ax
pop si
ret
showled endp

showAndIncLedCount proc near
push bx
mov bl,ledCount
inc bl
mov ledCount,bl ;inc ledCount
cmp bl,09h;test whether ledCount reach the limit.
ja reachCountLimit
jmp outputAndShowLed
reachCountLimit:
;donothing or just start from 0 again
mov bl,0
mov ledCount,bl 
outputAndShowLed:
mov bh,0h ;select led0,rather than led1
call showled
pop bx
ret
showAndIncLedCount endp

showLeds proc near
;input bx
push ax
mov ax,bx
mov bh,0
call showLed
mov bl,ah
cmp bl,0
jz endTemp
mov bh,1
call showLed
endTemp:
showMess doing1
pop ax
ret
showLeds endp

incLed01 proc near
push ax
mov al,led0
mov ah,led1
;
;lastest version
add al,1
aaa
cmp ah,09h
jbe endInc
sub ah,0ah
jmp endInc
;former version:
inc al
cmp al,10
jb highBit
overTen:
sub al,10
inc ah
highBit:
cmp ah,10
jb endInc
sub ah,10
;
endInc:
mov led0,al
mov led1,ah
pop ax
ret
incLed01 endp

;*******************fun:ledArr****************
refreshLedArr proc near
push di
push si
cmp byte ptr isShowLedArr,0
je notShowLedArr
lea si,ledArrRedDataBuff
lea di,ledArrGreenDataBuff
call showLedArr
jmp endRefreshLedArr
notShowLedArr:
call closeLedArr
endRefreshLedArr:
pop si
pop di
ret
refreshLedArr  endp

closeLedArr proc near
out2 ledArrh,0;
ret
closeLedArr endp

showLedArr proc near
;input si as red,di as green.
pusha
mov cx,8
everyL:
push cx
mov ax,1
dec cl
shl al,cl
mov cl,al
;store in cx
ouputRed:
cmp ds:[si],byte ptr 0
jz endOuputRed
mov dx,ledArrh
mov al,0
out dx,al
mov dx,ledArrly
mov al,0
out dx,al
mov dx,ledArrlr
mov al,cl
out dx,al
mov dx,ledArrh
mov al,ds:[si]
out dx,al
mov al,1
endOuputRed:
ouputGreen:
cmp ds:[di],byte ptr 0
jz endOuputGreen
mov dx,ledArrh
mov al,0
out dx,al
mov dx,ledArrlr
mov al,0
out dx,al
mov dx,ledArrly
mov al,cl
out dx,al
mov dx,ledArrh
mov al,ds:[di]
out dx,al
endOuputGreen:
inc si
inc di
pop cx
loop everyL
mov dx,ledArrh
mov al,0
out dx,al
popa
ret
showLedArr endp

ouputLedArr proc near 
;red in si,green in di
cld
push bx
push cx
push si
push di
mov bx,di;save di.
mov cx,08h
lea di,ledArrRedDataBuff
rep movsb
mov si,bx
mov cx,08h
lea di,ledArrGreenDataBuff
rep movsb
pop di
pop si
pop cx
pop bx
ret
ouputLedArr endp

flowShowArr proc near
initAgain:
lea ax,endArrData
lea di,ledArrDataEmpty
lea si,arrData
incShow:
mov cx,0ah
showingArr:call showLedArr
loop showingArr
inc si
cmp si,ax
jae initAgain
jmp incShow
ret
flowShowArr endp
_TEXT  ends
;************************end for basic***************
;*********************for snack code*************************
_TEXT    SEGMENT
$_node =4
printNode proc near
push bp
mov bp,sp
showChar ' '
showChar 'x'
showChar ':'
mov bx,$_node[bp]
mov al,[bx]
add al,'0'
showChar al
showChar 'y'
showChar ':'
mov al,[bx+1]
add al,'0'
showChar al
pop bp
ret
printNode endp
_TEXT  ends

_TEXT    SEGMENT
_ipg$2662 = -17                        ; size = 1
_pg$2661 = -16                        ; size = 4
_index$2660 = -9                    ; size = 1
_dir$ = -8                        ; size = 2
_p$ = -4                        ; size = 4
_tdir$ = 4                        ; size = 1
move@@YAXE@Z PROC                    ; move
; Line 118
push    bp
mov    bp, sp
sub    sp, 20                    ; 00000014H
push    si
; Line 119
mov    al, BYTE PTR _tdir$[bp]
test    al, al
jle    SHORT $LN13@move
mov    cl, BYTE PTR _tdir$[bp]
cmp    cl, 5
jl    SHORT $LN14@move
$LN13@move:
; Line 120
mov    dl, BYTE PTR idir@@3EA            ; idir
mov    BYTE PTR _tdir$[bp], dl
$LN14@move:
; Line 122
mov    bl, BYTE PTR _tdir$[bp]
mov    bh,0
shl     bx,1
mov    cx, WORD PTR dirs@@3PAUDir@@A[bx]
mov    WORD PTR _dir$[bp], cx
; Line 123
mov    dx, WORD PTR head@@3PAUNode@@A    ; head
mov    WORD PTR _p$[bp], dx
; Line 125
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    cl, BYTE PTR [bx]
mov    dl, BYTE PTR _dir$[bp]
mov    ch,0
mov    dh,0
add    cx, dx
and    cx, 8007H            ; 80000007H
jns    SHORT $LN17@move
dec    cx
or    cx, -8                    ; fffffff8H
inc    cx
$LN17@move:
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    BYTE PTR [bx], cl
; Line 126
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    dl, BYTE PTR [bx+1]
mov    al, BYTE PTR _dir$[bp+1]
mov    dh,0
mov    ah,0
add    dx, ax
and    dx, 8007H            ; 80000007H
jns    SHORT $LN18@move
dec    dx
or    dx, -8                    ; fffffff8H
inc    dx
$LN18@move:
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    BYTE PTR [bx+1], dl
; Line 128
mov    bx, WORD PTR food@@3PAUNode@@A    ; food
mov    al, BYTE PTR [bx]
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    dl, BYTE PTR [bx]
cmp    al, dl
jne    $LN12@move_more
mov    bx, WORD PTR food@@3PAUNode@@A    ; food
mov    cl, BYTE PTR [bx+1]
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    al, BYTE PTR [bx+1]
cmp    cl, al
jne    $LN12@move_more
; Line 131
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    dx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    WORD PTR [bx+4], dx
mov    ax, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    WORD PTR head@@3PAUNode@@A, ax    ; head
; Line 132
mov    cl, BYTE PTR len@@3EA            ; len
add    cl, 1
mov    BYTE PTR len@@3EA, cl            ; len
;showLen
mov ah,0
mov al,len@@3EA
showNumInLeds ax
;end showLen
; Line 133
mov    dl, BYTE PTR len@@3EA            ; len
mov    al, BYTE PTR winLen@@3EA        ; winLen
cmp    dl, al
jl    SHORT $LN11@move
call    win@@YAXXZ                ; win
$LN11@move:
; Line 135

jmp end$LN12@move_more
$LN12@move_more:jmp $LN12@move
end$LN12@move_more:

mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    dx, WORD PTR [bx+4]
push    dx
pushi    0
pushi    0
call    newNode@@YAPAUNode@@EEPAU1@@Z        ; newNode
add    sp, 6                    ; 0000000cH
mov    WORD PTR pivot@@3PAUNode@@A, ax    ; pivot
; Line 137
call    getRand@@YAHXZ                ; getRand
mov    cl, BYTE PTR len@@3EA            ; len
mov    ch,0
mov    dx, 64                    ; 00000040H
sub    dx, cx
mov cl,dl
mov ch,0
mov dx,0
div cx
mov    BYTE PTR _index$2660[bp], dl
; Line 138
add    dl, 1
mov    BYTE PTR _index$2660[bp], dl

; Line 139
call    createGraph@@YAXXZ            ; createGraph
; Line 140
mov    WORD PTR _pg$2661[bp], OFFSET graph@@3PAY07EA ; graph
; Line 141
mov    BYTE PTR _ipg$2662[bp], 0
$LN10@move:
; Line 142
mov    al, BYTE PTR _index$2660[bp]
test    al, al
je    SHORT $LN9@move
mov    cl, BYTE PTR _ipg$2662[bp]
mov    ch,0
mov     si,cx
mov    bx, WORD PTR _pg$2661[bp]
mov    al, BYTE PTR [bx+si]
mov    cl, BYTE PTR _ipg$2662[bp]
add    cl, 1
mov    BYTE PTR _ipg$2662[bp], cl
test    al, al
jne    SHORT $LN8@move
mov    dl, BYTE PTR _index$2660[bp]
sub    dl, 1
mov    BYTE PTR _index$2660[bp], dl
$LN8@move:
jmp    SHORT $LN10@move
$LN9@move:
; Line 143
mov    al, BYTE PTR _ipg$2662[bp]
sub    al, 1
mov    BYTE PTR _ipg$2662[bp], al
; Line 145
mov    al, BYTE PTR _ipg$2662[bp]
mov    cl,3
shr    al,cl
mov    bx, WORD PTR food@@3PAUNode@@A    ; food
;a radom deal with this error.
;and     al,0111b
mov    BYTE PTR [bx], al
; Line 146
mov    dl, BYTE PTR _ipg$2662[bp]
and     dl,0111b
mov    bx, WORD PTR food@@3PAUNode@@A    ; food
mov    BYTE PTR [bx+1], dl
; Line 148
jmp    $LN15@move
; Line 149
jmp    $LN15@move
$LN12@move:
; Line 150
mov    cx, WORD PTR head@@3PAUNode@@A    ; head
mov    WORD PTR _p$[bp], cx
$LN6@move:
; Line 151
mov    bx, WORD PTR _p$[bp]
mov    ax, WORD PTR [bx+4]
mov    WORD PTR _p$[bp], ax
mov    cx, WORD PTR _p$[bp]
cmp    cx, WORD PTR head@@3PAUNode@@A    ; head
je    SHORT $LN5@move
mov    bx, WORD PTR _p$[bp]
mov    al, BYTE PTR [bx]
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    dl, BYTE PTR [bx]
cmp    al, dl
jne    SHORT $LN4@move
mov    bx, WORD PTR _p$[bp]
mov    cl, BYTE PTR [bx+1]
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    al, BYTE PTR [bx+1]
cmp    cl, al
jne    SHORT $LN4@move
jmp    SHORT $LN5@move
$LN4@move:
; Line 152
jmp    SHORT $LN6@move
$LN5@move:
mov    bx, WORD PTR _p$[bp]
mov    dx, WORD PTR [bx+4]
cmp    dx, WORD PTR head@@3PAUNode@@A    ; head
jne    SHORT $LN3@move
; Line 154
mov    al, BYTE PTR idir@@3EA            ; idir
mov    BYTE PTR _tdir$[bp], al
; Line 155
mov    bl, BYTE PTR _tdir$[bp]
mov    bh,0
shl     bx,1
mov    dx, WORD PTR dirs@@3PAUDir@@A[bx]
mov    WORD PTR _dir$[bp], dx
; Line 156
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    cl, BYTE PTR [bx]
mov    dl, BYTE PTR _dir$[bp]
add    cl, dl
mov    ch,0
and    cx, 8007H            ; 80000007H
jns    SHORT $LN20@move
dec    cx
or    cx, -8                    ; fffffff8H
inc    cx
$LN20@move:
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    BYTE PTR [bx], cl
; Line 157
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    dl, BYTE PTR [bx+1]
mov    al, BYTE PTR _dir$[bp+1]
add    dl, al
mov    dh,0
and    dx, 8007H            ; 80000007H
jns    SHORT $LN21@move
dec    dx
or    dx, -8                    ; fffffff8H
inc    dx
$LN21@move:
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    BYTE PTR [bx+1], dl
jmp    SHORT $LN2@move
$LN3@move:
; Line 158
mov    dx, WORD PTR _p$[bp]
cmp    dx, WORD PTR head@@3PAUNode@@A    ; head
je    SHORT $LN2@move
; Line 159
call    lose@@YAXXZ                ; lose
; Line 160
jmp    SHORT $LN15@move
$LN2@move:
; Line 162
mov    al, BYTE PTR _tdir$[bp]
mov    BYTE PTR idir@@3EA, al            ; idir
; Line 165
mov    bx, WORD PTR head@@3PAUNode@@A    ; head
mov    dx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    WORD PTR [bx+4], dx
mov    ax, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    WORD PTR head@@3PAUNode@@A, ax    ; head
; Line 166
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    dx, WORD PTR [bx+4]
mov    WORD PTR pivot@@3PAUNode@@A, dx    ; pivot
; Line 167
mov    ax, WORD PTR head@@3PAUNode@@A    ; head
mov    bx, WORD PTR pivot@@3PAUNode@@A    ; pivot
mov    dx, WORD PTR [bx+4]
mov    bx,ax
mov    WORD PTR [bx+4], dx
$LN15@move:
; Line 171
pop    si
mov    sp, bp
pop    bp
ret    0
move@@YAXE@Z ENDP                    ; move
_TEXT    ENDS

_TEXT    SEGMENT
;input 2.
_num$ = 4                        ; size = 2
myalloc@@YAPAXG@Z PROC                    ; myalloc
; Line 58
push    bp
mov    bp, sp
; Line 59
mov    ax, WORD PTR heap@@3UHeap@@A+200h    ;next,2byte.
add    ax, OFFSET heap@@3UHeap@@A        ; heap,
    mov    WORD PTR heap@@3UHeap@@A+204h, ax    ;lastp,2byte.
    ; Line 60
    mov    cx, WORD PTR _num$[bp]
    mov    dx, WORD PTR heap@@3UHeap@@A+200h
    add    dx, cx
    mov    WORD PTR heap@@3UHeap@@A+200h, dx
    ; Line 61
    mov    ax, WORD PTR heap@@3UHeap@@A+204h
    ; Line 62
    pop    bp
    ret    0
    myalloc@@YAPAXG@Z ENDP                    ; myalloc
    _TEXT ends

    _TEXT    SEGMENT
    _node$ = -4    ; size = 4
    _tx$ = 4    ; size = 1,count for ip,and bp.
    _ty$ = 6    ; size = 1
    _p$ = 8        ; size = 4
    newNode@@YAPAUNode@@EEPAU1@@Z PROC ; newNode
    ; Line 63
    push    bp
    mov    bp, sp
    ; Line 64
    mov     ax,8
    push    ax
    call    myalloc@@YAPAXG@Z            ; myalloc
    add    sp, 4
    mov    WORD PTR _node$[bp], ax
    ; Line 65
    mov    bx, WORD PTR _node$[bp]
    mov    cl, BYTE PTR _tx$[bp]
    mov    BYTE PTR [bx], cl
    ; Line 66
    mov    cl, BYTE PTR _ty$[bp]
    mov    BYTE PTR [bx+1], cl
    ; Line 67
    mov    dx, WORD PTR _p$[bp]
    mov    WORD PTR [bx+4], dx
    ; Line 68
    mov    ax, WORD PTR _node$[bp]
    ; Line 69
    mov    sp, bp
    pop    bp
    ret    0
    newNode@@YAPAUNode@@EEPAU1@@Z ENDP            ; newNode
    _TEXT    ENDS

    _TEXT    SEGMENT
    getRand@@YAHXZ PROC    ; getRand
    ;why has it been truly random.
    ; Line 75
    push    bp
    mov    bp, sp
    ; Line 76
    mov     dx,0
    mov    ax, WORD PTR c@@3HA    ; c
    mov    bx, WORD PTR an@@3HA    ; an
    mul    bx
    add    ax, 1
    adc     dx,0
    mov    cx, 65535    
    div    cx
    mov     ax,dx
    mov    WORD PTR an@@3HA, dx    ; an
    ; Line 77
    mov    ax,dx    
    ; Line 78
    pop    bp
    ret    0
    getRand@@YAHXZ ENDP        ; getRand

    _TEXT    ENDS

    _TEXT    SEGMENT
    _t$ = -4        ; size = 4
    _k$ = 4            ; size = 4,2byte.
    setSeed@@YAXH@Z PROC    ; setSeed
    ;some error exists.
    ; Line 79
    push    bp
    mov    bp, sp
    push    cx
    ; Line 81
    mov    ax, WORD PTR _k$[bp]
    mov    WORD PTR _t$[bp], ax
    ; Line 82
    cmp    WORD PTR _k$[bp], 6
    je    SHORT $LN4@setSeed
    mov    cx, WORD PTR _k$[bp]
    mov     dx,cx
    add     dx,dx
    inc     dx
    mov    WORD PTR _k$[bp], dx
    $LN4@setSeed:
    ; Line 83
    mov    ax, WORD PTR _k$[bp]
    and    ax, 8007H        ; 8007H
    jns    SHORT $LN7@setSeed
    dec    ax
    or    ax, -8        ; fff8H
    inc    ax
    $LN7@setSeed:
    mov    WORD PTR _k$[bp], ax
    ; Line 84
    cmp    WORD PTR _k$[bp], 1
    jne    SHORT $LN3@setSeed
    mov    WORD PTR _k$[bp], 7
    $LN3@setSeed:
    ; Line 85
    mov    cx, WORD PTR _k$[bp]
    mov    WORD PTR c@@3HA, cx    ; c
    mov    WORD PTR an@@3HA, cx    ; an
    $LN2@setSeed:
    ; Line 86
    mov    ax, WORD PTR _t$[bp]
    mov    cx, WORD PTR _t$[bp]
    sub    cx, 1
    mov    WORD PTR _t$[bp], cx
    test    ax, ax
    je    SHORT $LN5@setSeed
    call    getRand@@YAHXZ        ; getRand
    jmp    SHORT $LN2@setSeed
    $LN5@setSeed:
    ; Line 87
    mov    sp, bp
    pop    bp
    ret    0
    setSeed@@YAXH@Z ENDP        ; setSeed
    _TEXT    ends 

    _TEXT    SEGMENT
    init@@YAXXZ PROC    ; init
    ; Line 90
    push    bp
    mov    bp, sp
    ; Line 91
    pushi    0
    pushi    1
    pushi    2
    call    newNode@@YAPAUNode@@EEPAU1@@Z    ; newNode
    add    sp,6                 ; 0000000cH
    mov    WORD PTR head@@3PAUNode@@A, ax    ; head
    mov    bx,ax
    mov    WORD PTR [bx+4], ax
    ; Line 92
    ; Line 93
    push    ax
    pushi    1
    pushi    1
    call    newNode@@YAPAUNode@@EEPAU1@@Z        ; newNode
    add    sp, 6                    ; 0000000cH
    mov    bx, WORD PTR head@@3PAUNode@@A    ; head
    mov    WORD PTR [bx+4], ax
    ; Line 94
    push    ax
    pushi    1
    pushi    0
    call    newNode@@YAPAUNode@@EEPAU1@@Z        ; newNode
    add    sp, 6                    ; 0000000cH
    mov    bx, WORD PTR head@@3PAUNode@@A    ; head
    mov    WORD PTR [bx+4], ax
    ; Line 95
    ;mov    BYTE PTR len@@3EA, 3            ; len
    mov    BYTE PTR len@@3EA, 3            ; len
    ; Line 96
    pushi    0
    pushi    0
    pushi    0
    call    newNode@@YAPAUNode@@EEPAU1@@Z        ; newNode
    add    sp, 6                    ; 0000000cH
    mov    WORD PTR food@@3PAUNode@@A, ax    ; food
    ; Line 98
    mov    bx, WORD PTR head@@3PAUNode@@A    ; head
    mov    ax, WORD PTR [bx+4]
    push    ax
    pushi    0
    pushi    0
    call    newNode@@YAPAUNode@@EEPAU1@@Z        ; newNode
    add    sp, 6                    ; 0000000cH
    mov    WORD PTR pivot@@3PAUNode@@A, ax    ; pivot
    ; Line 99
    mov    BYTE PTR isWin@@3DA, 0            ; isWin
    mov    BYTE PTR isLose@@3DA,0        ; isLose
    ; Line 100
    ;init myalloc,heap
    lea bx,heap@@3UHeap@@A
    mov WORD PTR [bx],0;next=0.
    mov WORD PTR [bx+4],0;lastp=0
    

    pop    bp
    ret    0
    init@@YAXXZ ENDP                    ; init
    _TEXT    ENDS


    _TEXT    SEGMENT
    lose@@YAXXZ PROC                    ; lose
    ; Line 102
    push    bp
    mov    bp, sp
    mov    al, BYTE PTR isWin@@3DA        ; isWin
    test    al, al
    jne    SHORT $LN2@lose
    mov    BYTE PTR isLose@@3DA, 1        ; isLose
    showMess loseMess
    leaveStage 3 
    enterStage 4
    ;is lose.
    $LN2@lose:
    pop    bp
    ret    0
    lose@@YAXXZ ENDP                    ; lose
    _TEXT    ENDS

    _TEXT    SEGMENT
    win@@YAXXZ PROC                    ; win
    ; Line 103
    push    bp
    mov    bp, sp
    ; Line 104
    mov    al, BYTE PTR isLose@@3DA        ; isLose
    test    al, al
    jne    SHORT $LN2@win
    mov    BYTE PTR isWin@@3DA, 1            ; isWin
showMess winMess

 leaveStage 3
    enterStage 4
    ;is win.
    $LN2@win:
    ; Line 105
    pop    bp
    ret    0
    win@@YAXXZ ENDP                    ; win
    _TEXT    ENDS


    _TEXT    SEGMENT
    _pg$ = -12                        ; size = 4
    _i$ = -5                        ; size = 1
    _p$ = -4                        ; size = 4
    createGraph@@YAXXZ PROC                ; createGraph
    ; Line 106
    push    bp
    mov    bp, sp
    sub    sp, 12                    ; 0000000cH
    ; Line 108
    mov    WORD PTR _pg$[bp], OFFSET graph@@3PAY07EA ; graph
    ; Line 109
    mov    BYTE PTR _i$[bp], 0
    jmp    SHORT $LN6@createGrap
    $LN5@createGrap:
    mov    al, BYTE PTR _i$[bp]
    add    al, 1
    mov    BYTE PTR _i$[bp], al
    $LN6@createGrap:
    mov    cl, BYTE PTR _i$[bp]
    cmp    cl, 64                    ; 00000040H
    jge    SHORT $LN4@createGrap
    mov    bx, WORD PTR _pg$[bp]
    mov    BYTE PTR [bx], 0
    mov    ax, WORD PTR _pg$[bp]
    add    ax, 1
    mov    WORD PTR _pg$[bp], ax
    jmp    SHORT $LN5@createGrap

    $LN4@createGrap:
    ; Line 111
    mov    cx, WORD PTR head@@3PAUNode@@A    ; head
    mov    WORD PTR _p$[bp], cx

    $LN3@createGrap:
    ; Line 113
    mov    bx, WORD PTR _p$[bp]
    mov    al, BYTE PTR [bx]
    mov    ch, BYTE PTR [bx+1]
    mov    ah,0
    mov    bx,ax
    mov     cl,3
    shl    bx,cl
    mov    cl,ch
    mov     ch,0
    add    bx,cx
    mov    BYTE PTR graph@@3PAY07EA[bx], 1
    ; Line 114
    mov    bx, WORD PTR _p$[bp]
    mov    cx, WORD PTR [bx+4]
    mov    WORD PTR _p$[bp], cx
    ; Line 115
    mov    dx, WORD PTR _p$[bp]
    cmp    dx, WORD PTR head@@3PAUNode@@A    ; head
    jne    SHORT $LN3@createGrap
    ; Line 116
    ;set food.
    jmp noSetFood
    mov    bx, WORD PTR food@@3PAUNode@@A    ; food
    mov    al, BYTE PTR [bx]
    mov    dx, WORD PTR food@@3PAUNode@@A    ; food
    mov    ch, BYTE PTR [bx+1]
    
    mov    ah,0
    mov     bx,ax
    mov     cl,3
    shl    bx,cl
    mov     cl,ch
    mov    ch,0
    add    bx,cx
    mov    BYTE PTR graph@@3PAY07EA[bx], 2
    noSetFood:
    ; Line 117
    mov    sp, bp
    pop    bp
    ret    0
    createGraph@@YAXXZ ENDP                ; createGraph
    _TEXT ends
    
    _TEXT    SEGMENT
    _ch$ = 4                        ; size = 1
    printChar@@YAXD@Z PROC                    ; printChar
    ; Line 172
    push    bp
    mov    bp, sp

    showChar _ch$[bp]
    ; Line 174
    pop    bp
    ret    0
    printChar@@YAXD@Z ENDP                    ; printChar
    _TEXT    ENDS


    _TEXT    SEGMENT
    _j$ = -2                        ; size = 1
    _i$ = -1                        ; size = 1
    print@@YAXXZ PROC                    ; print
    ; Line 175
    push    bp
    mov    bp, sp
    push    cx
    ; Line 177
    call    createGraph@@YAXXZ            ; createGraph
    pusha
    mov    bx, WORD PTR food@@3PAUNode@@A    ; food
    mov    al, BYTE PTR [bx]
    mov    dx, WORD PTR food@@3PAUNode@@A    ; food
    mov    ch, BYTE PTR [bx+1]
    
    mov    ah,0
    mov     bx,ax
    mov     cl,3
    shl    bx,cl
    mov     cl,ch
    mov    ch,0
    add    bx,cx
    mov    BYTE PTR graph@@3PAY07EA[bx], 2
    popa
    ; Line 178
    mov    BYTE PTR _i$[bp], 0
    jmp    SHORT $LN6@print
    $LN5@print:
    mov    al, BYTE PTR _i$[bp]
    add    al, 1
    mov    BYTE PTR _i$[bp], al
    $LN6@print:
    mov    cl, BYTE PTR _i$[bp]
    cmp    cl, 8
    jge    SHORT $LN7@print
    ; Line 179
    mov    BYTE PTR _j$[bp], 0
    jmp    SHORT $LN3@print
    $LN2@print:
    mov    dl, BYTE PTR _j$[bp]
    add    dl, 1
    mov    BYTE PTR _j$[bp], dl
    $LN3@print:
    mov    al, BYTE PTR _j$[bp]
    cmp    al, 8
    jge    SHORT $LN1@print
    ; Line 180
    mov    al, BYTE PTR _j$[bp]
    mov    ch, BYTE PTR _i$[bp]
    mov    cl,3
    mov    ah,0
    mov     bx,ax
    shl     bx,cl
    mov    cl,ch
    mov    ch,0
    add    bx,7
    sub    bx,cx
    mov    cl, BYTE PTR graph@@3PAY07EA[bx]
    add    cl, 48                    ; 00000030H
    mov    ch,0
    push    cx
    call    printChar@@YAXD@Z            ; printChar
    add    sp, 2

    ; Line 182
    jmp    SHORT $LN2@print
    $LN1@print:
    ; Line 184
    CR
    ; Line 186
    jmp    SHORT $LN5@print
    $LN7@print:
    ; Line 187
    mov    sp, bp
    pop    bp
    ret    0
    print@@YAXXZ ENDP                    ; print
    _TEXT    ENDS

    _TEXT    SEGMENT
    _j$ = -2                        ; size = 1
    _i$ = -1                        ; size = 1
    createOutputData@@YAXXZ PROC                ; createOutputData
    ; Line 189
    push    bp
    mov    bp, sp
    push    cx
    push    si
    ; Line 191
    call    createGraph@@YAXXZ            ; createGraph
    ; Line 192
    mov    BYTE PTR _i$[bp], 0
    jmp    SHORT $LN6@createOutp
    $LN5@createOutp:
    mov    al, BYTE PTR _i$[bp]
    add    al, 1
    mov    BYTE PTR _i$[bp], al
    $LN6@createOutp:
    mov    cl, BYTE PTR _i$[bp]
    cmp    cl, 8
    jge    SHORT $LN7@createOutp
    ; Line 193
    mov    bl, BYTE PTR _i$[bp]
    mov    bh,0
    mov    BYTE PTR outputData@@3PAEA[bx], 0
    ; Line 194
    mov    BYTE PTR _j$[bp], 0
    jmp    SHORT $LN3@createOutp
    $LN2@createOutp:
    mov    al, BYTE PTR _j$[bp]
    add    al, 1
    mov    BYTE PTR _j$[bp], al
    $LN3@createOutp:
    mov    cl, BYTE PTR _j$[bp]
    cmp    cl, 8
    jge    SHORT $LN1@createOutp
    ; Line 195
    mov    bl, BYTE PTR _i$[bp]
    mov    bh,0
    mov    al, BYTE PTR outputData@@3PAEA[bx]
    shl    al, 1
    mov    bl, BYTE PTR _i$[bp]
    mov    bh,0
    mov    BYTE PTR outputData@@3PAEA[bx], al
    ; Line 196
    mov    dl, BYTE PTR _i$[bp]
    mov    dh,0
    mov    al, BYTE PTR _i$[bp]
    mov    ch, BYTE PTR _j$[bp]
    ;cal bx
    mov    ah,0
    mov    bx,ax
    mov    cl,3
    shl    bx,cl    
    mov    cl,ch
    mov    ch,0
    add    bx,7
    sub    bx,cx
    ;end cal bx
    mov    al, BYTE PTR graph@@3PAY07EA[bx]
    ;why can ,if al==0,then al=0;otherwise,al=1
    neg    al
    sbb    al, al
    neg    al

    mov    bx,dx
    mov    cl, BYTE PTR outputData@@3PAEA[bx]
    add    cl, al
    mov    bh,0
    mov    bl, BYTE PTR _i$[bp]
    mov    BYTE PTR outputData@@3PAEA[bx], cl
    ; Line 197
    jmp    SHORT $LN2@createOutp
    $LN1@createOutp:
    ; Line 200
    jmp    $LN5@createOutp
    $LN7@createOutp:
    ; Line 203
    pop    si
    mov    sp, bp
    pop    bp
    ret    0
    createOutputData@@YAXXZ ENDP                ; createOutputData
    _TEXT    ENDS
        _TEXT   segment
setFoodData proc near
;will change some regs.
mov cx,8
lea si,ledArrDataEmpty
lea di,foodLedData
rep movsb
;set food in foodLedData.
mov bx,word ptr food@@3PAUNode@@A
mov cl,[bx+1]   ;get food->y
mov dl,1
shl dl,cl       ;dl=1<<(food->y)
mov al,[bx]     ;al=food->x
mov ah,0       
lea bx,foodLedData 
add bx,ax
mov [bx],dl
;set head in foodLedData.
mov bx,word ptr head@@3PAUNode@@A
mov cl,[bx+1]   ;get head->y
mov dl,1
shl dl,cl       ;dl=1<<(head->y)
mov al,[bx]     ;al=head->x
mov ah,0       
lea bx,foodLedData 
add bx,ax
mov al,[bx]
or al,dl
mov [bx],al
ret
setFoodData endp

moveSnack proc near
;protect all regs,input al,as direction.
pusha
mov ah,0
push ax 
call move@@YAXE@Z
add sp,2
call setFoodData
call createOutputData@@YAXXZ
lea si,outputData@@3PAEA
lea di,foodLedData
call ouputLedArr
call print@@YAXXZ
showMess foodAtMess
mov ax,word ptr food@@3PAUNode@@A
push ax
call printNode
add sp,2
CR
CR
popa
ret
moveSnack endp

    _TEXT    ENDS
;*********************end for snack*************************
    _TEXT    SEGMENT
checkIfChangeStage proc 
pusha
;chekcing key.
mov ah,06h
mov dl,0ffh
int 21h
jz noKeyPress
mov pressKey,al
jmp aKeyPressed
noKeyPress:
mov pressKey,0
jmp endChange
aKeyPressed:

showMess youPressKey 
showChar pressKey
CR
mov bx,currentStage

checkStage0:
cmp bx,0
jne checkStage1
;doing
cmp pressKey,'0'
jne endChangeTransfer
leaveStage 0
enterStage 1
jmp endChangeTransfer

checkStage1:
cmp bx,1
jne checkStage2
;doing
jmp endChangeTransfer

checkStage2:
cmp bx,2
jne checkStage3
;doing
cmp pressKey,'2'
jne endChangeTransfer
leaveStage 2
enterStage 3
jmp endChangeTransfer
;;;;
jmp endendChangeTransfer
endChangeTransfer:jmp endChange
endendChangeTransfer:
;;;;;
checkStage3:
cmp bx,3
jne checkStage4
;doing
mov al,pressKey
mapkey al,'w','s','a','d';to 1,2,3,4
mov ah,0
mov curDir,ax
;//doing some special thing,to react immediately
push ax
push bx
mov ax,offset dynamicMove
call findFun
shl bx,1
mov [callBackCountUp+bx],7fffh
pop bx
pop ax
;end 

jmp endChange

checkStage4:
cmp bx,4
jne checkStage5
;doing
cmp pressKey,'4'
jne endChange
leaveStage 4
enterStage 0
jmp endChange

checkStage5:
cmp bx,5
jne checkStage6
;doing
cmp pressKey,'5'
jne endChange
leaveStage 5
enterStage 0
jmp endChange

checkStage6:

endChange:
popa
ret
checkIfChangeStage endp

findFun proc
;input ax
;output bx
push si
mov bx,0
lea si,callBackFuns
findingFun:
cmp [si],ax
je foundFun
add si,2
inc bx
jmp findingFun
foundFun:
pop si
ret
findFun endp






    _TEXT    ENDS
end start




