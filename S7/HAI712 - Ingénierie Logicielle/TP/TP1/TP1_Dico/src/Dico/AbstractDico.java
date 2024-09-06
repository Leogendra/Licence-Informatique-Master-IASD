package Dico;

public abstract class AbstractDico implements IDico {

	protected Object[] keyDico;
	protected Object[] valueDico;

	public abstract int indexOf(Object key);
	public abstract int newIndexOf(Object key);
    
	
    public int size() {
    	return this.valueDico.length;
    }
	
    
    public boolean isEmpty() {
    	return this.size() == 0;
    }
    
    
    public boolean containsKey(Object key) {
    	return !(this.indexOf(key) == -1); // so long gay bower
    }
    
	
	public IDico put(Object key, Object value) {
		if(!this.containsKey(key)) {
			int newIndice = this.newIndexOf(key);
			keyDico[newIndice] = key;
			valueDico[newIndice] = value;
		}
		else {
			System.out.println("La clef est déjà utilisée.");
		}
		return this;
	}
	
	
	public Object get(Object key) {
		try {return valueDico[this.indexOf(key)];}
		catch(Exception e) {return "Clef non existante";}
	}

}
