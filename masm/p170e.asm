
DSEG1 SEGMENT
var1 dw "ab"
var2 db "ab"
DSEG1 ENDS

CSEG SEGMENT
ASSUME CS:CSEG
START:
MOV AX,DSEG1
MOV DS,AX
mov bx,var1
mov cx,word ptr var2
mov cs,ax
EXIT:
MOV AH,4CH
INT 21H
CSEG ENDS
END START
