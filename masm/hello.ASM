        ifndef  ??version
?debug	macro
	endm
	endif
	?debug	S "hello.c"
_TEXT	segment	byte public 'CODE'
DGROUP	group	_DATA,_BSS
	assume	cs:_TEXT,ds:DGROUP,ss:DGROUP
_TEXT	ends
_DATA	segment word public 'DATA'
d@	label	byte
d@w	label	word
_DATA	ends
_BSS	segment word public 'BSS'
b@	label	byte
b@w	label	word
	?debug	C E93BAA99420768656C6C6F2E63
	?debug	C E9A460A840122E2E5C494E434C5544455C737464696F2E68
	?debug	C E9A460A840132E2E5C494E434C5544455C7374646172672E68
_BSS	ends
_DATA	segment word public 'DATA'
_DATA	ends
_TEXT	segment	byte public 'CODE'
;	?debug	L 5
_main	proc	near
	push	si
;	?debug	L 7
	mov	si,4
;	?debug	L 8
	mov	ax,si
	mov	dx,6
	mul	dx
	mov	si,ax
;	?debug	L 9
	mov	ax,offset DGROUP:s@
	push	ax
	call	near ptr _printf
	pop	cx
;	?debug	L 10
	xor	ax,ax
	jmp	short @1
@1:
;	?debug	L 11
	pop	si
	ret	
_main	endp
_TEXT	ends
	?debug	C E9
_DATA	segment word public 'DATA'
s@	label	byte
	db	119
	db	111
	db	114
	db	108
	db	100
	db	10
	db	0
_DATA	ends
_TEXT	segment	byte public 'CODE'
	extrn	_printf:near
_TEXT	ends
	public	_main
	end
