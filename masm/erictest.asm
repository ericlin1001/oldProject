DSEG SEGMENT
ADD1 DB 100,102 DUP("$")
ADD2 DB 100,102 DUP("$")
MESS1 DB "Please input two number(separator is \n):$"
MESS2 DB "The sum is:$"
SUM  DB 105 DUP('$')
MESSENTER DB 0AH,0DH,"$"
DSEG ENDS
EXTRA1 SEGMENT

EXTRA1 ENDS
CSEG SEGMENT
ASSUME CS:CSEG,DS:DSEG,ES:EXTRA1
START:
MOV AX,DSEG
MOV DS,AX
MOV AX,EXTRA1
MOV ES,AX
				;start coding...
				;ask user to input.
LEA DX,MESS1
CALL DISP
CALL ENTER			;output a CR
				;read two number.
MOV DX,OFFSET ADD1
CALL READSTR
CALL ENTER			;output a CR
MOV DX,OFFSET ADD2
CALL READSTR
CALL ENTER			;output a CR
		;start cal
;set SI as the end address of ADD1
LEA BX,ADD1
MOV SI,BX
INC SI
MOV AL,DS:[SI]
MOV AH,0H
ADD SI,AX

MOV CX,AX	;CX is the len of ADD1	

;set DI as the end address of ADD2
LEA BX,ADD2
MOV DI,BX
INC DI
MOV AL,DS:[DI]
MOV AH,0H
ADD DI,AX

LEA BX,SUM
CMP CX,AX
JBE LESS
LEA DX,ADD1	;if add1>add2
ADD BX,CX
PUSH SI		;exchange and let len [SI] < len [DI]
PUSH DI
POP SI
POP DI
MOV CX,AX
JMP CAL
LESS: 		;if add1<add2
LEA DX,ADD2
ADD BX,AX
CAL:
PUSH DX 	;no DX is the longer one's address

;start doing the main calculating...
MOV DL,0	;as the carry
LOOP1:
MOV AL,DS:[SI]
MOV AH,DS:[DI]
CALL ADDDEMICAL
MOV BYTE PTR [BX],AL
DEC SI
DEC DI
DEC BX
DEC CX
JNZ LOOP1

MOV AL,DL	;store the carry
		;get CX as the remaining len of the longer one addent
POP DX
INC DX
MOV CX,DI
SUB CX,DX

MOV DL,AL	;restore the carry
CMP CX,0H
JZ ENDLOOP2
LOOP2:
MOV AH,30H
MOV AL,DS:[DI]
CALL ADDDEMICAL
MOV BYTE PTR [BX],AL
DEC DI
DEC BX
DEC CX
JNZ LOOP2 
ENDLOOP2:

ADD DL,30H
MOV BYTE PTR [BX],DL
;end cal

LEA DX,MESS2
CALL DISP

LEA BX,SUM
DEC BX
			;delete leading 0
LOOP3:
INC BX
MOV AL,DS:[BX]
CMP AL,30H
JE LOOP3
CMP AL,'$'
JNZ DISPSUM 
DEC BX

DISPSUM:
MOV DX,BX
CALL DISP
CALL ENTER

EXIT:
MOV AH,4CH
INT 21H

DISP PROC NEAR
;display
PUSH AX
MOV AH,09H
INT 21H
POP AX
RET
DISP ENDP

ADDDEMICAL PROC NEAR
;input AL,AH as two addent
;and DL as carry
;output AL as sum,DL as carry
PUSH BX
MOV BL,AH
ADD AL,BL
ADD AL,DL
SUB AL,30H
CMP AL,39H
JA OVERNINE
MOV DL,0
JMP ENDADD
OVERNINE:
SUB AL,10D
MOV DL,1
ENDADD:
POP BX
RET
ADDDEMICAL ENDP

ENTER PROC NEAR
;output a \n
PUSH DX
LEA DX,MESSENTER
CALL DISP
POP DX
RET
ENTER ENDP

READSTR PROC NEAR
;read sth except \n
;input DX
PUSH AX
PUSH BX
READ:
MOV AH,0AH
INT 21H
MOV BX,DX
MOV AL,[BX+1]
CMP AL,0H
JNZ READCOMPLETE
JMP READ
READCOMPLETE:
POP BX
POP AX
RET
READSTR ENDP

CSEG ENDS
END START