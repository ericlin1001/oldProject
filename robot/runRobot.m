function runRobot(initState,alltime,isAnimation,speed)
T=.02;%means the dT,means every step cost the time.
s=[0 0 0];
f=[0 0;0 0 ];
if(nargin==1)
    s=initState;
end
pauseT=T*0.5;
if(nargin>3)
pauseT=T/speed*0.5;
end
disp(pauseT);
figure;
if(nargin<2)
    alltime=1000;
end
for e=1:alltime;
    s=nextState(s,f);
    f=nextF(s,f);
end
if(nargin>=2 && isAnimation~=0)
   animationDraw(s,20,pauseT);
else
    drawState(s,20);
end

return ;
function animationDraw(states,drawOrientationPer,pauseT)
n=size(states,1);
for e=2:n;
   cg=states(e-1:e,:);
   drawState(cg,(mod(e,drawOrientationPer)==1)*(drawOrientationPer+1));
   pause(pauseT);
end
return ;