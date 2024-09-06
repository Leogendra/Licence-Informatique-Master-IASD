package com.example.tp2_lescapteurs;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;



public class Ex3Activity extends AppCompatActivity implements SensorEventListener {

    TextView xValue, yValue, zValue, accelerationValue;
    View background;
    SensorManager sensorManager;
    Sensor accelerometer;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ex3);


        //Récuperation des id des xml
        xValue = findViewById(R.id.x_value);
        yValue = findViewById(R.id.y_value);
        zValue = findViewById(R.id.z_value);
        background = findViewById(R.id.background);


        //Recuperer les capteurs et notamment celui de l'acceleromettre
        sensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        if (sensorManager != null) {
            accelerometer = sensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
            if (accelerometer != null) {
                sensorManager.registerListener(this, accelerometer, SensorManager.SENSOR_DELAY_NORMAL);
            }
            else {
                Toast.makeText(this, getString(R.string.unavailable_acc), Toast.LENGTH_SHORT).show();
            }
        }


        Button buttonSuivant3 = findViewById(R.id.bouton_suivant_ex3);
        buttonSuivant3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(Ex3Activity.this, MainActivity.class);
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

        // Modification des données
        xValue.setText(String.format("X : %.2f", x));
        yValue.setText(String.format("Y : %.2f", y));
        zValue.setText(String.format("Z : %.2f", z));

        float acceleration = (float) Math.sqrt(x*x + y*y + z*z);

        accelerationValue = findViewById(R.id.acceleration_value);
        accelerationValue.setText(String.format("Accélération : %.2f", acceleration));

        // Calculer les couleurs de fond et lui attribuer des valeurs
        int color;
        if (acceleration < 9.8) {color = Color.GREEN;}
        else if (acceleration < 12) {color = Color.BLACK;}
        else {color = Color.RED;}

        background.setBackgroundColor(color);
    }


    // Détermine la catégorie (0 = fuchsia, 1 = vert, 2 = bleu électrique) d'une valeur donnée
    private int getCategoryForValue(float value, float minValue, float maxValue) {
        float thresholdLow = 2.5f;
        float thresholdHigh = 7.5f;
        if (value < (minValue + thresholdLow)) {
            return 0;
        } else if (value > (maxValue - thresholdHigh)) {
            return 2;
        } else {
            return 1;
        }
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





