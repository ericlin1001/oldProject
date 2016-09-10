#include<iostream>
#include<cstdlib>
/* 
 * author:ericlin
 * finsihed:2012/8/12
 */
using namespace std;
struct Node{
        unsigned char x,y;
       Node*next;
       Node(unsigned char tx,unsigned char ty,Node *p=NULL){
                 x=tx;y=ty;
                 next=p;
                 }
};
struct Dir{
       unsigned char dx,dy;
};
/*
;left,up,right ,down 1,2,3,4
;Y
;^
;|
;|
;0-------->X
*/
Dir dirs[5]={{0,0},{-1,0},{0,1},{1,0},{0,-1}};
Node *head,*food,*pivot;
unsigned char idir=3;
unsigned char len;
const unsigned char size=8;
unsigned char winLen=5;
void init(){
     head=new Node(2,4);
     head->next=head;
     head->next=new Node(1,4,head->next);
     head->next=new Node(0,4,head->next);
     len=3;
     food=new Node(0,0);
     ;
     pivot=new Node(0,0,head->next);
}
void lose(){
     cout<<"lose"<<endl;
     }
     void win(){
     cout<<"win"<<endl;
     }
     
unsigned char graph[size][size];
void createGraph(){
     unsigned char i;
     unsigned char *pg=&graph[0][0];
     for(i=0;i<size*size;i++)*pg++=0;
     
     Node *p=head;
     do{
                    graph[p->x][p->y]=1;
                    p=p->next;
       }while(p!=head);
       graph[food->x][food->y]=2;
     }
void move(unsigned char tdir){
     if(tdir==0){
                 tdir=idir;
                 }
     Dir dir=dirs[tdir];
     Node*p=head;
     pivot->x=(head->x+dir.dx+size)%size;
     pivot->y=(head->y+dir.dy+size)%size;
     if(food->x==pivot->x && food->y==pivot->y){
                          //eating food.
                        //  cout<<"eating food."<<endl;
            head=head->next=pivot;
            len++;
            if(len>=winLen)win();
            ;
            pivot=new Node(0,0,head->next);                 
            //place food to other places.
           
                    unsigned char index=rand()%(size*size-len);
                    index++;
                    createGraph();
                    unsigned char *pg=&graph[0][0];
                    unsigned char ipg=0;
                    while(index){if(*(pg+ipg++)==0)index--;};
                    ipg--;
                    
                    food->x=ipg/size;
                    food->y=ipg%size;
                    
            return ;
     }else{
           p=head;
           while((p=p->next)!=head)if(p->x==pivot->x && p->y ==pivot->y)break;
           if(p->next==head){//move back
           //cout<<"Moving back"<<endl;
                                    tdir=idir;
                                    dir=dirs[tdir];
                pivot->x=(head->x+dir.dx)%size;
                pivot->y=(head->y+dir.dy)%size;
           }else if(p!=head){
                       lose();
                       return;
           }
                             idir=tdir;
                            // cout<<"noramlly move"<<endl;
                             //noramlly move the snack.
                             head=head->next=pivot;
                             pivot=pivot->next;
                             head->next=pivot->next;
                             
  }
        
}
unsigned char outputData[8];
void print(){
     unsigned char i,j;
     createGraph();
     for(i=0;i<size;i++){
                      for(j=0;j<size;j++){
                                       cout<<(unsigned char)(graph[j][size-1-i]+'0');
                                       }
                                       cout<<endl;
                      }
     }
     unsigned char converToHex(unsigned char ch){
       
     if(0<=ch && ch<=9)return ch+'0';
     else return ch+'A'-10;
     
}
void createOutputData(){
     unsigned char i,j;
     createGraph();
     for(i=0;i<size;i++){
                         outputData[i]=0;
                      for(j=0;j<size;j++){
                                          outputData[i]<<=1;
                                          outputData[i]+=graph[i][size-1-j]?1:0;
                                           }
                                         //  cout<<"("<<outputData[i]<<")";
                                   cout<<converToHex(outputData[i]/16)<<converToHex(outputData[i]%16)<<"("<<(int)outputData[i]<<")"<<",";
                                 }
                                 cout<<endl;
                               
}

unsigned char convert(unsigned char ch){

     switch(ch){
               case 1:return 1;
                case 2:return 4;
                 case 3:return 3;
                 case 5:return 2;
                }
                return 0;
                }
int main(){

    cout<<"left:1\tup:2\tright:3\tdown:4"<<endl;
    init();
    ;
    unsigned char idir=0;
    while(1){
              system("cls");
              move(idir);
              print();
               createOutputData();
              cout<<"left:1\tup:2\tright:3\tdown:4"<<endl;
    cin>>idir;
    idir-='0';
    idir=convert(idir);
    //idir=1;
}
    system("pause");
    return 0;
} 
