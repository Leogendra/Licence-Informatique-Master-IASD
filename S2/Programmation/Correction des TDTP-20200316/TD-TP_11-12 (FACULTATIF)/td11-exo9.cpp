#include <iostream>
#include <cmath>

struct vecteur
{
  int taille;
  char nom;
  float * contenu;

};

vecteur creer(int taille, char nom)
{
  vecteur v;
  v.nom = nom;
  v.taille=taille;
  float * t = new float[taille];
  int i ;
  for (i=0;i<taille; i++)
    t[i]=0;
  v.contenu=t;
  return v;
}

void saisie(vecteur *v)
{
  int n = v->taille;
  float * t = v->contenu;
  int i;
  std::cout<<"donnez les "<<n<< " valeurs du vecteur "<<v->nom<<"\n";
  for(i=0;i<n;i++)
    std::cin>>t[i];
}

void afficher(vecteur * v)
{
  std::cout<<"vecteur "<<v->nom<<" \n";
  int n = v->taille;
  int i;
  for (i=0;i<n;i++)
    std::cout<< v->contenu[i]<<" ";
  std::cout<<"\n";
}

vecteur oppose(vecteur *v)
{
  char c =  'Z'+'A'-v->nom;
  vecteur u = creer(v->taille,c);
  float * t1 = v-> contenu;
  float * t2 = u.contenu;
  int i;
  for (i=0;i<v->taille;i++)
    t2[i]=-t1[i];
  return u;
}


vecteur somme( vecteur *v1 , vecteur *v2)
{   
  if (v1-> taille == v2->taille)
    {
      char  c='A' + ((v1->nom+v2->nom)+1 -'A')%26 ;
      vecteur s= creer(v1->taille,c);       
      float * tab1 = v1->contenu;
      float * tab2 = v2->contenu;
      float * res = s.contenu;
      int i ;
      for (i=0;i<v1->taille;i++)
	res[i]=tab1[i]+tab2[i];
      return s;
    }
}

float scalaire(vecteur *v1, vecteur * v2)
{
  float r = 0;
  if (v1-> taille == v2->taille)
    {
      float * tab1 = v1->contenu;
      float * tab2 = v2->contenu;
      int i ;
      for (i=0;i<v1->taille;i++)
	r= r+ tab1[i]*tab2[i];
    }
  return r;
}


float norme (vecteur *v)

{
  return sqrt (scalaire(v,v));
}


int main()
{
  vecteur v1 = creer(5,'V');
  vecteur v2 = creer(5,'W');
  saisie(&v1);
  saisie(&v2);
  afficher(&v1);
  afficher(&v2);
  vecteur o = oppose(&v1);
  afficher(&o);
  vecteur s = somme(&v1,&v2);
  afficher (&s);
  float r = scalaire(&v1,&v2);
  std::cout<<"produit scalaire : "<<r<<" \n";
  float no = norme(&v1);
  std::cout<<"norme du vecteur "<<v1.nom<<" ="<<no<<"\n";
  return 0;
}
