
#include <iostream>

struct cellule
{
  int contenu;
  cellule * suivant;
};

struct file
{
  cellule * premier;
  cellule * dernier;
};


file creer()
{
  file p;
  p.premier = NULL;
  p.dernier=NULL;
  return p;
}

int compter(cellule * c)
{
  if (c->suivant ==NULL)
    return 1;
  else
    return (1+ compter(c->suivant));
}

int cardinal(file * p)
{
  if (p->premier == NULL)
    return 0;
  else
    return compter(p->premier);

}

int estVide( file * p)
{
  return (cardinal(p)==0);
}


void afficherPile1(file * p)
{
  std::cout<<"affichage à partir du sommet\n";
  cellule * c = p->premier;
  while (c!= NULL)
    {
      std::cout<<c->contenu<<" ";
      c=c->suivant;
    }

  std::cout<<"\n";
}

void afficher1(cellule * c)
{
  if (c->suivant ==NULL)
    std::cout<<c->contenu<<" ";
  else
    {
      std::cout<<c->contenu<<" ";
      afficher1(c->suivant);
    }
}

void afficher2(cellule * c)
{
  if (c->suivant ==NULL)
    std::cout<<c->contenu<<" ";
  else
    {
      afficher2(c->suivant);
      std::cout<<c->contenu<<" ";
    }
}

void afficherPile2(file * p)
{
  std::cout<<"affichage à partir du fond de la pile\n";
  cellule * c = p->premier;
  afficher2 (c);
  std::cout<<"\n";
}

cellule creerCellule (int n)
{
  cellule c ;
  c.contenu = n;
  c.suivant = NULL;
  return c;
}

void empiler(cellule * c, file * p)

{
  if (estVide(p))
    {
      p->premier = c;
      p->dernier = c;
    }

  else
    {
      c->suivant = p->premier;
      p->premier = c;
    }
}

int retirer(file * p)
{
  cellule * c=p->dernier;
  cellule * prec = p->premier;
  while (prec->suivant != c)
    prec= prec->suivant;
  float r = c-> contenu;
  p->dernier= prec;
  prec->suivant = NULL;
  return r;
}


void liberer(cellule * c)
{
  if (c->suivant ==NULL)
    {
      std::cout<<"liberer "<<c->contenu<<" \n";
      c=NULL;
    }
  else
    {
      liberer(c->suivant);
      std::cout<<"liberer "<<c->contenu<<" \n";
    }
}

void libererPile(file * p)
{
  liberer(p->premier);
  p->premier = NULL;
  p->dernier = NULL;
}



int main()
{
  file p = creer();
  cellule c1 = creerCellule(2);
  empiler(&c1,&p);
  cellule c2 = creerCellule(3);
  empiler(&c2,&p);
  cellule c3 = creerCellule(4);
  empiler(&c3,&p);
  afficherPile1(&p);
  afficherPile2(&p);
  // libererPile(&p);
  std::cout<<retirer(&p)<<"\n";
  afficherPile1(&p);
  return 0;
}
