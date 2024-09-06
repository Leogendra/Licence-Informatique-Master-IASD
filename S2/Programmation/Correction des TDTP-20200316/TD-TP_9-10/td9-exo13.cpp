#include <iostream>
#include <iomanip>

float ** matMul(float ** t1, int m,int n, float ** t2, int k){
  float **p = new float *[m]; int i,j,l;
  for (i=0;i<m;i++)
    p[i] = new float[k];
 
  for(i=0;i<m;i++)
    for(j=0;j<k;j++){
      p[i][j]=0; 
      for(l=0;l<n;l++)
	p[i][j] += t1[i][l]*t2[l][j]; 
    }
  return p;
}
  
int main(){

  int m = 10,n =10;

  float **t1 = new float *[m];
  for (int i=0;i<m;i++)
    t1[i] = new float[n];
  int i,j;
  for(i=0;i<m;i++)
    for(j=0;j<n;j++)
      t1[i][j]= 1.0/(i+j+1);


  for(i=0;i<m;i++){
    for(j=0;j<n;j++)
      std::cout<<std::left<<std::setprecision(3)<<std::setw(8)<<t1[i][j];
    std::cout<<std::endl;
  }


  std::cout<<std::endl<<std::endl;

  float ** c= matMul(t1,m,n,t1,m);
  for(i=0;i<m;i++){
    for(j=0;j<n;j++)
      std::cout<<std::left<<std::setprecision(3)<<std::setw(8)<<c[i][j];
    std::cout<<std::endl;
  }



  for (int i=0;i<m;i++){
    delete[] t1[i];
    delete[] c[i];}
  delete [] t1;
  delete [] c;
  return 0;
}
  
