#include<iostream>
#include<iomanip>

int combi1(int p, int n){
  if (n<p) return 0;
  if (p==n || p==0){
    return 1;
  }
  else {
    return combi1(p,n-1)+combi1(p-1,n-1);
  }
}
int combi2(int p, int n){
  if (n<p) {return 0;}
  if (p==n || p==0){
    return 1;
  }
  else {
    return (n*combi2(p-1,n-1))/p;
  }
}

void triangle(int k){
  for(int i=0;i<=k;i++){
    for(int j=0;j<=i;j++){
      std::cout<<combi2(j,i)<<" ";
    }
    std::cout<<std::endl;
  }
}
































int comb1(int n, int p)
{ if (n<p)
    return 0;
  else
    if (p==0 || n==p)
      return 1;
    else return comb1(n-1,p) + comb1(n-1,p-1);
}

int comb2(int n, int p)
{if (n<p)
    return 0;
  else
    if (p==0 || n==p)
      return 1;
    else return  (comb2(n-1,p-1)*n)/p;
}

int saisieEntier(){
  std::cout<<"Donnez un entier\n";
  int x;
  std::cin>>x;
  return x;
}


void triangle(int k){
  int i,j;
  for(i=0;i<=k;i++)
    { for(j=0;j<=i;j++)
	std::cout<<std::setw(5)<<comb2(i,j);
      std::cout<<std::endl;
    }
}


int main()
{
  int i= saisieEntier();
  int j= saisieEntier();
  std::cout<<"première formule pour C("<<i<<","<<j<<")="<<comb1(i,j)<<"\n";
  std::cout<<"deuxième formule pour C("<<i<<","<<j<<")="<<comb2(i,j)<<"\n";
  triangle(10);
  return 0;
}
