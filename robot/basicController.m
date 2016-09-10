function [v w]=basicController(cs)
persistent css;
css=[css;cs];
%
x=cs(1);
y=cs(2);
t=cs(3);
[xd yd td vd wd]=desireState(cs);
xe=(xd-x)*cos(t)-(yd-y)*sin(t);
ye=(xd-x)*sin(t)+(yd-y)*cos(t);
disp({'ye,xe=' ye xe});
td=atan2(ye,xe);
te=td;
%
td=atan2(yd-y,xd-x);
te=td-t;
%te=mod(td-t,2*pi);
te=pi-abs(te-pi);
persistent dists;
dist=(xe^2+ye^2)^0.5;
dists=[dists dist];
%dist=xe+ye;
v=min(5,tanh(abs(dist*0.0002-4))*5)*2/pi^2*(abs(te)*(pi-abs(te)));
disp({'v=' v});
%v=0;
if (abs(te)<pi/6)
    w=0.2*te^3;
else
    w=sign(te)*tanh(abs(abs(te*2.7*0.7)-4))*pi/2;
end

disp({'w=' w});
disp({'xe ye te=' xe ye te});

return;

function [v w]=trackFollow(cstate)
cs=cstate;
x=cs(1);
y=cs(2);
t=cs(3);
%%
[xd yd td vd wd]=desireState(cs);
%in robot pos error:
xe=(xd-x)*cos(t)-(yd-y)*sin(t);
ye=(xd-x)*sin(t)+(yd-y)*cos(t);
te=td-t;
%
[kx ky kt]=parameters();
v=vd*cos(t)+kx*xe;
w=wd+2*vd*ky*ye*cos(t/2)+vd*kt*sin(t/2);
v=2;
w=1*0.5;
return ;

function [xd yd td vd wd]=desireState(cs)
T=.02;%means the dT,means every step cost the time.
%n=size(states,1);
%n=n*T;
%xd=1+2*sin(n);
%yd=1-2*cos(n);
%td=n;
vd=0;
wd=0;
%
xd=5;yd=5;td=0;
return;

function [kx ky kt]=parameters()
kx=8;
ky=5;
kt=0.8;
return ;