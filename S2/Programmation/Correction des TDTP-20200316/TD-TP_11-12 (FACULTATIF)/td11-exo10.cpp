#include <iostream>


struct cellule
{
  int contenu;
  cellule * suivant;
};

struct pile
{
  int cardinal ;
  cellule * premier;
};


pile creer()
{
  pile p;
  p.cardinal = 0;
  p.premier = NULL;
  return p;
}

int cardinal(pile * p)
{
  return p->cardinal;
}

int estVide( pile * p)
{
  return (p->cardinal == 0);
}
void afficherPile1Ite(pile * p)
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

void afficherPile2Rec(pile * p)
{
  std::cout<<"affichage à partir du fond de la pile\n";
  cellule * c = p->premier;
  afficher2 (c);
  std::cout<<"\n";
}
void afficherPile1Rec(pile * p)
{
  std::cout<<"affichage à partir du sommet de la pile\n";
  cellule * c = p->premier;
  afficher1 (c);
  std::cout<<"\n";
}

cellule creerCellule (int n)
{
  cellule c ;
  c.contenu = n;
  c.suivant = NULL;
  return c;
}

void empiler(cellule * c, pile * p)

{
  c->suivant = p->premier;
  (p->cardinal )++;
  p->premier = c;
}

int depiler(pile * p)
{
  cellule * c=p->premier;
  cellule * s= (*c).suivant;
  ( p->cardinal) --;
  p->premier = s;
  return (*c).contenu;
}


void liberer(cellule * c)
{
  if (c->suivant ==NULL)
    {
      std::cout<<"liberer "<<c->contenu<<"\n";
      c=NULL;
    }
  else
    {
      liberer(c->suivant);
      std::cout<<"liberer "<<c->contenu<<" \n";
    }
}

void libererPile(pile * p)
{
  liberer(p->premier);
}

pile creation(int n)
{
  pile p = creer();
  int i; cellule * t = new cellule[n];
  for (i=0;i<n; i++)
    {
      t[i]= creerCellule(i);
      empiler(&t[i],&p);
    }
  return p;
}


int main()
{
  pile p = creer();
  cellule c1 = creerCellule(2);
  empiler(&c1,&p);
  cellule c2 = creerCellule(3);
  empiler(&c2,&p);
  cellule c3 = creerCellule(4);
  empiler(&c3,&p);
  afficherPile1Ite(&p);
  afficherPile2Rec(&p);
  libererPile(&p);
  pile p1  = creation(10);
  afficherPile1Rec(&p1);
  afficherPile2Rec(&p1);
  return 0;
}
