package ter;
import java.lang.String;

public class Groupe {
  private int idGroupe;
  private String nomGroupe;

  public int getIdGroupe() {return this.idGroupe;};
  public String getNomGroupe() {return this.nomGroupe;};

  public void setIdGroupe(int id) {this.idGroupe = id;};
  public void setNomGroupe(String nom) {this.nomGroupe = nom;};

  public String dispGroupe() {return "Nom : " + getNomGroupe() + "\nId : " + getIdGroupe();};

  public Groupe() {
    this.idGroupe = 0000;
    this.nomGroupe = "Nom_groupe";
  }

  public Groupe(int id, String nom) {
    this.idGroupe = id;
    this.nomGroupe = nom;
  }
}