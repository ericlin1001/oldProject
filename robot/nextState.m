function news=nextState(states,f)
T=.02;%means the dT,means every step cost the time.
n=size(states,1);
cs=states(n,:); %current state.
%x=cs(1);
%y=cs(2);
t=cs(3);
v=f(n,1);
w=f(n,2);
v1=f(n+1,1);
w1=f(n+1,2);
dx=T/6*(v*cos(t)+4*v*cos(t+T/2*w)+v1*cos(t+T*w));
dy=T/6*(v*sin(t)+4*v*sin(t+T/2*w)+v1*sin(t+T*w));
dt=T/6*(5*w+w1);
newstate=cs+[dx dy dt];
news=[states;newstate];
return ;
