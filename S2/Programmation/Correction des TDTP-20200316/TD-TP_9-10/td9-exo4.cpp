#include <iostream>
#include <iomanip>

int main(){
  std::cout<<"donnez deux entiers"<<std::endl;
  int m,n;
  std::cin>>m>>n;
  float **p = new float *[m];
  for (int i=0;i<m;i++)
    p[i] = new float[n];
  int i,j;
  for(i=0;i<m;i++)
    for(j=0;j<n;j++)
      p[i][j]= 1.0/(i+j+1);

  for(i=0;i<m;i++){
    for(j=0;j<n;j++)
      std::cout<<std::left<<std::setprecision(3)<<std::setw(8)<<p[i][j];
    std::cout<<std::endl;
  }

  for (int i=0;i<m;i++)
    delete[] p[i];
  delete [] p;
  return 0;

}
  
