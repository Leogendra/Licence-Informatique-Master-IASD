package Location;

public class Test {

	public static void main(String[] args) {
		Produit lgv = new Produit("La grande vadrouille", 10.0);
		Client cl = new Client("Dupont");
		Compte cmt = new Compte(cl);
		cmt.prixLocation(lgv);

	}

}