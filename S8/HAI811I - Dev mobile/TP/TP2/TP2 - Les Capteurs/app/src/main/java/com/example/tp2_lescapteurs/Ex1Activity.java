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
class SensorInfo {
    private final String name;
    private final int type;
    private final String vendeur;
    private final int version;

    //Constructeur des informations sur les capteurs
    public SensorInfo(String name, int type, String vendeur, int version, boolean aFalse) {
        this.name = name;
        this.type = type;
        this.vendeur = vendeur;
        this.version = version;
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

    // Reecriture de tostring
    @NonNull
    @Override
    public String toString() {
        return "\nNom : " + getName() + "\n\n"+ "Type : " + getType() + "\n\n"+ "Vendeur : " + getVendeur() + "\n\n" + "Version : " + getVersion() + "\n";
    }
}

public class Ex1Activity extends AppCompatActivity {

    private ListView listViewSensors;
    private ArrayAdapter<SensorInfo> adapter;
    private boolean False;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ex1);


        // Recuperer id de la liste view
        listViewSensors = findViewById(R.id.listview_sensors);
        //pour affichage
        adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1);
        listViewSensors.setAdapter(adapter);

        // Recuperation des capteurs
        SensorManager sensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        List<Sensor> sensorList = sensorManager.getSensorList(Sensor.TYPE_ALL);

        //pour chaque capteur
        for (Sensor sensor : sensorList) {

            // recuperation des éléméents du capteur
            String name = sensor.getName();
            int type = sensor.getType();
            String vendeur = sensor.getVendor();
            int version = sensor.getVersion();

            //creation SensorInfo
            SensorInfo sensorInfo = new SensorInfo(name, type, vendeur, version, False);
            //affichage sur l'ecran
            adapter.add(sensorInfo);
        }


        // Aller a l'activité suivante
        Button buttonSuivant1 = findViewById(R.id.bouton_suivant_ex1);
        buttonSuivant1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(Ex1Activity.this, MainActivity.class);
                startActivity(iCal);
            }
        });
    }
}