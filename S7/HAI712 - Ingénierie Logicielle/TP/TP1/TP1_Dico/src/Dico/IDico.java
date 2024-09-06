package Dico;

public interface IDico {
	
	public Object get(Object key);
	public IDico put(Object key, Object value);
    public boolean isEmpty();
    public boolean containsKey(Object key);
    public int size();

}
