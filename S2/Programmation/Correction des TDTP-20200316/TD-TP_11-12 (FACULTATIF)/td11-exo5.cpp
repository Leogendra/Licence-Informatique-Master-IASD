#include <iostream>

struct temps{
  int  h,m,s;};

struct CD{
  int nb;
  temps * durees;
};

temps conversion(int n){
  temps t;
  t.h= n/3600;
  t.m= (n%3600)/60;
  t.s = (n%3600)%60;
  return t;
}

void  addtime(temps t1,temps t2, temps * res){
  int aux = t1.s+t2.s;
  res->s = aux%60;
  aux = aux/60+t1.m+t2.m;
  res->m= aux%60;
  res->h = aux/60+t1.h+t2.h;
}

int  comparetime(temps t1, temps t2){
  if (t1.h>t2.h)
    return 1;
  else if (t1.h < t2.h)
    return -1;
  else if (t1.m >t2.m)
    return 1;
  else if (t1.m<t2.m)
    return -1;
  else if (t1.s > t2.s)
    return 1;
  else if (t1.s < t2.s)
    return -1;
  else return 0;
}

void  saisie(temps * t){
  int hh,mm,ss; char c1,c2;
  do{ std::cout<<"donnez la duree sous la forme hh:mm:ss\n";
      std::cin>>hh>>c1>>mm>>c2>>ss;}
  while(c1!=':'||c2!=':'||mm<0||mm>=60||ss<0||ss>=60);
  t->h=hh; t->m=mm; t->s=ss;
}

  void affiche(temps * t){
    std::cout<<(t->h)<<':'<<(t->m)<<':'<<(t->s)<<"\n";
  }

  CD cree(int n){
    CD c;
    c.nb=n;
    c.durees= new temps[n];
    return c;
  }

  void init (CD * c){
    for(int i = 0;i<c->nb;i++){
      std::cout<<"donnez la duree de la plage  "<<i<< " du CD \n";
      saisie(& c->durees[i]);
    }
  }

  void afficheCD (CD c){
    std::cout<<"plages du cd \n";
    for(int i= 0;i<c.nb;i++)
      affiche(&c.durees[i]);
  }
  temps dureeTotale(CD c){
    temps t={0,0,0};
    for(int i =0;i<c.nb;i++)
      addtime(t, c.durees[i],&t);
    return t;
  }

  int nbPistesSup(int ns, CD c){
    int res = 0;
    temps t= conversion (ns);
    for(int i = 0;i<c.nb;i++)
      if (comparetime(c.durees[i], t)==1)
	res++;
    return res;
  }


  int main(){
    temps t1 = {5,37,54}, t2 = {6,29,13};
    temps t3 = conversion(7540);temps t4;
    affiche(&t1);
    affiche(&t2);
    affiche(&t3);
    addtime(t1,t2,&t4);
    affiche(&t4);
    std::cout<<"comparaison : "<<comparetime(t1,t2)<<"\n";
    CD c = cree(3);
    init(&c);
    afficheCD(c);
    temps t5= dureeTotale(c);
    affiche(&t5);
    int n;
    std::cout<<"donnez une duree en secondes\n";
    std::cin>>n;
    std::cout<<"nb de plages de duree sup a "<<n<<" =  "<< nbPistesSup(n,c)<<"\n";
    return 0;
  }
