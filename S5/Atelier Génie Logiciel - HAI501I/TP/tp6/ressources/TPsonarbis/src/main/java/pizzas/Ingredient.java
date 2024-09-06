package pizzas;

public class Ingredient {

	private String NOM;
	private boolean vegetarien;
	public String getNom() {
		return NOM;
	}
	public boolean isVegetarien() {
		return vegetarien;
	}
	public Ingredient(String nom, boolean vegetarien) {
		this.NOM = nom;
		this.vegetarien = vegetarien;
	}

	public boolean equals(Ingredient i) {
		return NOM.equals(i.getNom());
	}
	
}
