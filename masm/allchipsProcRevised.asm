;***************************************;
;*    A project used up all chips      *;
;***************************************;

ledport equ 280h

counterControl equ 293h
counter0 equ 290h
counter1 equ 291h
counter2 equ 292h

PA equ 2a0h
PB equ 2a1h
PC equ 2a2h
chip8255Control equ 2a3h

;data segment
data  segment
;basic data
mess db 'TPCA interrupt!',0dh,0ah,'$'
messConsult db 'consulting!',0dh,0ah,'$'
messEndCounter db 'reachEndCounter !',0dh,0ah,'$'
led      db   3fh,06h,5bh,4fh,66h,6dh,7dh,07h,7fh,6fh ;seg code.
ledCount db -1;indicate the number the led shows.
;music's data
soundTable1 dw 524,588,660,698,784,880,988,1048;high voice
soundTable2 dw 262,294,330,347,392,440,494,524;low voice
music1 db 1,2,3,1,1,2,3,1,3,4,5,5,3,4,5,5,5,6,5,4,3,1,5,6,5,4,3,1,1,5,1,1,1,5,1,1,0
music2 db 3,3,5,5,3,3,5,5,3,5,1,7,6,6,5,3,4,1,2,2,3,4,1,0
data ends

;macro definition
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

in2 macro reg,port
;return reg.
push dx
mov dx,port
in reg,dx
pop dx
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

CR macro
push ax
push dx
mov ah,02h
mov dl,09h
int 21h
mov dl,0ah
int 21h
pop ax
pop dx
endm

getBit macro reg,n ;n=0 means get B0
;change and return reg.
;had better use al as reg.
push cx
mov cl,n
shr reg,cl
and reg,1
pop cx
endm

setBit macro reg,n; n start from 0
;change and return reg.
;had better use al as reg.
push cx
mov cl,n
mov ch,1h
shl ch,cl
or reg,cl
pop cx
endm

resetBit macro reg,n; n start from 0
;change and return reg.
;had better use al as reg.
push cx
mov cl,n
mov ch,1h
shl ch,cl
not cl
and reg,cl
pop cx
endm

inverseBit macro reg,n; n start from 0
;change and return reg.
;had better use al as reg.
push cx
mov cl,n
mov ch,1h
shl ch,cl
xor reg,cl
pop cx
endm


;**********************code segment*********************
code segment
assume cs:code,ds:data
start:
mov ax,data
mov ds,ax
;
call init
out2 PC,00000001b ;a test.
;let al as PC.
mov al,1h
loop1:
call hardDelay
inverseBit al,7
out2 PC,al
jmp loop1

waitPress: jmp waitPress;just wait.
jmp start     ;jump to start

intMain:
mov al,20h
out 20h,al ;send EOI.
;*************main program*******
showMess mess
call showAndIncLedCount
;****************end main********************
loop next ;base on cx,when cx is 0,exit.
exitIntProm:
in al,21h
or al,08h ;disable the IRQ3.
out 21h,al 
sti
mov ah,4ch
int 21h
next:    iret
;end intMain


jmp exit
;*******************************some procedures*************************
softDelay proc near          ;
    push cx
        push ax
            mov ax,130
            x1: mov cx,0ffffh
            x2: loop x2
                dec ax
                    jnz x1
                        pop ax
                            pop cx
                                ret
                                softDelay endp

singSong proc near
mov bx,0h
mov al,[music1+bx]
cmp al,0
jz endSing
call playOneSound
inc bx
endSing:
ret
singSong endp

playOneSound proc near
;change ax,input al
push bx
push dx

;start translate
sub al,1
shl al,1             ;al=al*2
mov bl,al            ;save offset to bx
mov bh,0
;
mov ax,4240H         ;start cal initN = 1000000 / frequency, save in AX
mov dx,0FH
;now dx,ax =1000,000
div word ptr[soundTable1+bx]
;mov bx,ax

out2 counterControl,11110110B        ; 1 counter ,11 read H,L ,3 method,binary.
;start initialize the N.
mov dx,counter2        
;mov ax,bx
out dx,al            ;write low byte
mov al,ah
out dx,al            ;write high byte
;

;start sounding
mov dx,counter2            
mov al,03h
out dx,al            ;set PA1PA0 = 11(opne sound player)
call softDelay           ;
mov al,0h
out dx,al            ;set PA1PA0 = 00(close sound player)
;end sounding
pop dx
pop bx
ret
playOneSound endp

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

init proc near;change everything
;set interrupt vector.
mov ax,cs
mov ds,ax
mov dx,offset intMain
mov ax,250bh
int 21h
mov ax,data
mov ds,ax
;end set int vector.
;begin set IRQ3 enabled.
in al,21h
and al,0f7h ;set IRQ3 enabled.
out 21h,al
mov cx,12 ;set the amount of key press before exit.
sti
;end

;begin init chip 8253
;set the countercontrol
mov dx,counterControl
mov al,00110110b ;operate in method 3,square wave continuously
out dx,al
mov al,01110000b ;operate in method 0,when reach,keep high until init again
out dx,al
;initialize the 1000 as counter0,counter1
mov ax,1000
mov dx,counter0 
out dx,al
mov al,ah
out dx,al
;end init chip 8253

;begin init chip 8255
;init control port.  PA=> internal =>PC
out2 chip8255Control,10010000b ;PC as ouput. PA as input.
;end init chip 8255
ret 
init endp

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

showled proc near
;change and only change bx.
;input bl as num
;input bh 0 as led0,bh=1 as led1
push si
push ax
push dx
mov si,offset led            ;set led's segCode in SI
mov al,bl
mov ah,0
add si,ax                    ;cal led's code.
mov al,byte ptr ds:[si]
;test bh
cmp bh,0h
jz setLed0
jmp outputToLed
setLed0:
or al,80h
outputToLed:
mov dx,ledport
out dx,al
pop dx
pop ax
pop si
ret
showled endp

exit: mov ah,4ch    ;exit and return to dos.
int 21h

code ends
end start
