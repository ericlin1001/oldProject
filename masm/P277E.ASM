CSEG SEGMENT
ASSUME CS:CSEG
START:
MOV AX,0B800H
MOV DS,AX
MOV CX,26
MOV BX,(10*80+00)*2
MOV AL,"A"
MOV AH,74H
AGAIN:
MOV DS:[BX],AX
INC BX
INC BX
INC AL
LOOP AGAIN
 MOV AH,4CH
 INT 21H
 CSEG ENDS
 END START
