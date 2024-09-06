package ter;

public class GestionTER {

  public static void main(String[] args) {
    Sujet Sujet1 = new Sujet(01, "La coorélation des étoiles");
    Sujet Sujet2 = new Sujet(02, "Lorem ipsum idolre sit amet");
    Sujet Sujet3 = new Sujet(03, "gcc -wall");
    Sujet Sujet4 = new Sujet(04, "daude");
    Sujet Sujet5 = new Sujet(05, "Gnelf");

    Groupe Groupe1 = new Groupe(1, "Grp1");
    Groupe Groupe2 = new Groupe(2, "Grp2");
    Groupe Groupe3 = new Groupe(3, "Grp3");

    System.out.println(Groupe1.dispGroupe());
  }
}