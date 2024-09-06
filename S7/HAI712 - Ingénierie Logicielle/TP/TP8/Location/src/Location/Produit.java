package Location;

public class Produit {
	
  private String nom;
  private double prixAchat;
  
  public Produit(String n, double p) {
	  this.nom = n;
	  this.prixAchat = p;
  }

  public String getNom() {
	  return this.nom;
  }
  
  public double getPrix() {
	  return this.prixAchat;
  }
  
  public void setNom(String n) {
	  this.nom = n;
  }
  
  public void setPrix(int p) {
	  this.prixAchat = p;
  }
  
  public double prixLocation() {
	  return prixAchat * 1.1;
  }
  
}
