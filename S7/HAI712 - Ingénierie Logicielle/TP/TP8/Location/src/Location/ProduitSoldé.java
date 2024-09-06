package Location;

public class ProduitSoldé extends Produit {
	
  private String nom;
  private double prixAchat;
  
  
  public ProduitSoldé(String n, double p) {
	  super(n, p);
  }

  public double prixLocation() {
	  return super.prixLocation()* 0.5;
  }
  
}
