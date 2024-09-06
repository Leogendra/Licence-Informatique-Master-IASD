package Location;

public class Compte {

	private Client client;
  
	public Compte(Client c) {
		this.client = c;
	}
  
	public double prixLocation(Produit p) {
		return 1.1*p.getPrix();
	}
	
	public Client getClient() {
		return client;
	}

	public void setClient(Client c) {
		this.client = c;
	}
  
  
  
}
