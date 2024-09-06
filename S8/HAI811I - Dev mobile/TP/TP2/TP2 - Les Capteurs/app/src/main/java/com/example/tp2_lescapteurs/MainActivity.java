package com.example.tp2_lescapteurs;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class MainActivity extends AppCompatActivity {

    private Button bEx1, bEx2, bEx3, bEx4, bEx5, bEx6, bEx7;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //boutons pour lancer les 7 activités
        bEx1 = findViewById(R.id.button1);
        bEx1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex1Activity.class);
                startActivity(iCal);
            }
        });

        bEx2 = findViewById(R.id.button2);
        bEx2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex2Activity.class);
                startActivity(iCal);
            }
        });

        bEx3 = findViewById(R.id.button3);
        bEx3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex3Activity.class);
                startActivity(iCal);
            }
        });

        bEx4 = findViewById(R.id.button4);
        bEx4.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex4Activity.class);
                startActivity(iCal);
            }
        });

        bEx5 = findViewById(R.id.button5);
        bEx5.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex5Activity.class);
                startActivity(iCal);
            }
        });

        bEx6 = findViewById(R.id.button6);
        bEx6.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex6Activity.class);
                startActivity(iCal);
            }
        });

        bEx7 = findViewById(R.id.button7);
        bEx7.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Création d'un intent pour récuperer les informations
                Intent iCal = new Intent(MainActivity.this, Ex7Activity.class);
                startActivity(iCal);
            }
        });
    }


}