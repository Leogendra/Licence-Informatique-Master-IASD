package com.example.tp2_lescapteurs;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class Ex4Activity extends AppCompatActivity implements SensorEventListener {

    TextView directionValue, xValue, yValue, zValue;
    ImageView image_left, image_right, image_up, image_down;
    SensorManager sensorManager;
    Sensor accelerometer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ex4);

        //Récuperation des id des xml
        xValue = findViewById(R.id.tvX);
        yValue = findViewById(R.id.tvY);
        zValue = findViewById(R.id.tvZ);
        directionValue = findViewById(R.id.text_direction);
        image_left = findViewById(R.id.image_left);
        image_right = findViewById(R.id.image_right);
        image_up = findViewById(R.id.image_up);
        image_down = findViewById(R.id.image_down);



        //Recuperer les capteurs et notamment celui de l'acceleromettre
        sensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        if (sensorManager != null) {
            accelerometer = sensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
            if (accelerometer != null) {
                sensorManager.registerListener(this, accelerometer, SensorManager.SENSOR_DELAY_NORMAL);
            }
            else {
                Toast.makeText(this, "Accelerometer not available", Toast.LENGTH_SHORT).show();
            }
        }


        // Aller a l'activité suivante avec le bouton suviant
        Button buttonSuivant4 = findViewById(R.id.bouton_suivant_ex4);
        buttonSuivant4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(Ex4Activity.this, MainActivity.class);
                startActivity(iCal);
            }
        });


    }

    @Override
    @SuppressLint("DefaultLocale")
    public void onSensorChanged(SensorEvent sensorEvent) {

        // Recuperer les valeurs des capteurs
        float x = sensorEvent.values[0];
        float y = sensorEvent.values[1];
        float z = sensorEvent.values[2];

        //modifie les indications
        updateDirection(x, y, z);
    }

    @SuppressLint({"DefaultLocale", "SetTextI18n"})
    private void updateDirection(float x, float y, float z) {

        // Calcul de l'angle d'inclinaison du téléphone
        double angle = Math.atan2(x, y) * 180 / Math.PI;
        String direction = "";

        // Choix de la direction en fonction de l'angle
        if (angle >= -45 && angle <= 45) {
            direction = "Haut";
            image_right.setVisibility(View.GONE);
            image_up.setVisibility(View.VISIBLE);
            image_left.setVisibility(View.GONE);
            image_down.setVisibility(View.GONE);
        }
        else if (angle > 45 && angle < 135) {
            direction = "Droite";
            image_up.setVisibility(View.GONE);
            image_down.setVisibility(View.GONE);
            image_left.setVisibility(View.GONE);
            image_right.setVisibility(View.VISIBLE);
        }
        else if (angle >= 135 || angle <= -135) {
            direction = "Bas";
            image_left.setVisibility(View.GONE);
            image_up.setVisibility(View.GONE);
            image_down.setVisibility(View.VISIBLE);
            image_right.setVisibility(View.GONE);
        }
        else if (angle < -45 && angle > -135) {
            direction = "Gauche";
            image_down.setVisibility(View.GONE);
            image_up.setVisibility(View.GONE);
            image_left.setVisibility(View.VISIBLE);
            image_right.setVisibility(View.GONE);
        }

        // Affichage de la direction sur le TextView
        directionValue.setText("Direction : " + direction);

        // Affichage des valeurs de l'accéléromètre sur les TextViews correspondants
        xValue.setText("X : " + String.format("%.2f", x));
        yValue.setText("Y : " + String.format("%.2f", y));
        zValue.setText("Z : " + String.format("%.2f", z));
    }

    @Override
    public void onAccuracyChanged(Sensor sensor, int i) {}

    @Override
    protected void onPause() {
        super.onPause();
        sensorManager.unregisterListener(this);
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (accelerometer != null) {
            sensorManager.registerListener(this, accelerometer, SensorManager.SENSOR_DELAY_NORMAL);
        }
    }
}