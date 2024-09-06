package Dico;

public class OrderedDico extends AbstractDico {
	
	public OrderedDico() {
		keyDico = new Object[0];
		valueDico = new Object[0];
	}
	
	@Override
	public int indexOf(Object key) {
	    for (int i = 0; i < this.size(); i++) {
	        if (keyDico[i].equals(key)) {
	            return i;
	        }
	    }
	    return -1;
	}

	@Override
	public int newIndexOf(Object key) {
		int size = this.size();
		Object[] NewKeyDico = new Object[size+1];
		Object[] NewValueDico = new Object[size+1];
		
		for (int i=0; i<size; i++) {
			NewKeyDico[i] = this.keyDico[i];
			NewValueDico[i] = this.valueDico[i];
		}

		this.keyDico = NewKeyDico;
		this.valueDico = NewValueDico;
		
		return size;
	}
}