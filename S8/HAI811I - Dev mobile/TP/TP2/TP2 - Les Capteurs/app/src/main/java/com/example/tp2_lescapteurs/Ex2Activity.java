package com.example.tp2_lescapteurs;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.content.Intent;
import android.hardware.Sensor;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;

import java.util.List;


//Classe pour les informations sur les capteurs
class SensorInfoDisponible {
    private final String name, vendeur;
    private final int type, version;
    private final boolean isDisponible;

    //Constructeur des informations sur les capteurs
    public SensorInfoDisponible(String name, int type, String vendeur, int version, boolean isDisponible) {
        this.name = name;
        this.type = type;
        this.vendeur = vendeur;
        this.version = version;
        this.isDisponible = isDisponible;
    }

    // Accesseur en lecture
    public String getName() {
        return name;
    }

    public int getType() {
        return type;
    }

    public String getVendeur() {
        return vendeur;
    }

    public int getVersion() {
        return version;
    }

    public boolean getIsDisponible() {
        return isDisponible;
    }

    // Reecriture de tostring
    @NonNull
    @Override
    public String toString() {
        if (!isDisponible) {
            return "\nNom : " + getName() + "\n\n" + "Type : " + getType() + "\n\n" + "Vendeur : " + getVendeur() + "\n\n" + "Version : " + getVersion() + "\n";
        }
        return "";
    }

}

public class Ex2Activity extends AppCompatActivity {

    private ListView listViewSensors;
    private ArrayAdapter<SensorInfoDisponible> adapter;
    boolean False;
    SensorManager sensorManager;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ex2);


        // Recuperer id de la liste view
        listViewSensors = findViewById(R.id.listview_sensors);
        //pour affichage
        adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1);
        listViewSensors.setAdapter(adapter);

        // Recuperation des capteurs
        sensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        List<Sensor> sensorList = sensorManager.getSensorList(Sensor.TYPE_ALL);

        //pour chaque capteur
        for (Sensor sensor : sensorList) {

            // recuperation des éléméents du capteur
            String name = sensor.getName();
            int type = sensor.getType();
            String vendeur = sensor.getVendor();
            int version = sensor.getVersion();

            if (sensorManager.getDefaultSensor(sensor.getType()) == null) {
                //creation SensorInfo
                SensorInfoDisponible sensorInfoDisponible = new SensorInfoDisponible(name, type, vendeur, version, False);
                //affichage sur l'ecran
                adapter.add(sensorInfoDisponible);
            }
        }


        // Aller a l'activité suivante
        Button buttonSuivant2 = findViewById(R.id.bouton_suivant_ex2);
        buttonSuivant2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(Ex2Activity.this, MainActivity.class);
                startActivity(iCal);
            }
        });
    }

    @Override
    protected void onPause() {
        super.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
    }
}