package com.example.tp1;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;

import androidx.appcompat.app.AppCompatActivity;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class TrainActivity extends AppCompatActivity {

    private ListView trainList;
    private Button searchButton;
    private EditText departure;
    private EditText arrival;
    private ArrayList<String> tripList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_train);

        trainList = findViewById(R.id.train_list);
        searchButton = findViewById(R.id.search);
        departure = findViewById(R.id.departure);
        arrival = findViewById(R.id.arrival);
        tripList = new ArrayList<String>();

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, tripList);

        trainList.setAdapter(adapter);

        searchButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                tripList.removeAll(tripList);
                String villeDepart = departure.getText().toString();
                String villeArrivee = arrival.getText().toString();

                Random rand = new Random();
                int nbVoyages = rand.nextInt(5);
                for (int i = 0; i < nbVoyages; i++) {
                    String heureVoyage = Integer.toString(rand.nextInt(17)+6) + "h";
                    int minutesVoyage = rand.nextInt(60);
                    if (minutesVoyage < 10) {heureVoyage += "0";}
                    heureVoyage += Integer.toString(minutesVoyage);
                    tripList.add(villeDepart + " -> " + villeArrivee + " : " + heureVoyage);
                }

                if(nbVoyages == 0) {
                    tripList.add(getResources().getString(R.string.no_result));
                }
                adapter.notifyDataSetChanged();
            }
        });

        Button backButton = findViewById(R.id.home);
        backButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(TrainActivity.this, MainActivity.class);
                startActivity(i);
            }
        });

    }
}
