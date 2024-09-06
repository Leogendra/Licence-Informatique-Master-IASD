package pizzas;

import static org.junit.jupiter.api.Assertions.*;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

class TestPizzas {

	private BasePizzas base=new BasePizzas();
	
	@BeforeEach
	public void init() {
		base.addPizzaToMenu(base.createSurpriseWhitePizza());
	}
	
	@Test
	private void testAjoutPizza() {
		Pizza p=new Pizza("fromages", 10);
		p.ajoutIngredient(new Ingredient("Mozzarelle", true));
		p.ajoutIngredient(new Ingredient("Talegio", true));
		
		base.addPizzaToMenu(p);
	}
	
	@Test
	 void testAjoutIng1() {
		Pizza p=base.getPizzaFromMenu("Surprise blanche");
		System.out.println(p.formattedIngredients());
		var oldSize=p.ingredients().length;
		p.ajoutIngredient(new Ingredient("brocolis", true));
		assertTrue(oldSize+1==p.ingredients().length);
	}

}
