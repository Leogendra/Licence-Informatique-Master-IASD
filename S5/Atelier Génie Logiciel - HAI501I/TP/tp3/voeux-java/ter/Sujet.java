package ter;
import java.lang.String;

public class Sujet {
  private int idSujet;
  private String titreSujet;

  public int getIdSujet() {return this.idSujet;};
  public String getTitreSujet() {return this.titreSujet;};

  public void setIdSujet(int id) {this.idSujet = id;};
  public void setIdSujet(String titre) {this.titreSujet = titre;};

  public String dispSujet() {return "Titre : " + getTitreSujet() + "Id : " + getIdSujet();};

  public Sujet() {
    this.idSujet = 0;
    this.titreSujet = "Nom_sujet";
  }

  public Sujet(int id, String titre) {
    this.idSujet = id;
    this.titreSujet = titre;
  }

}