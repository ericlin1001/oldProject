#include<iostream.h>
void fn(){
}
int main(int argc, char *argv[]){
	char *pstr=argv[1];
	while(*pstr && *pstr!='.') cout<<*pstr++;
	return 0;
}