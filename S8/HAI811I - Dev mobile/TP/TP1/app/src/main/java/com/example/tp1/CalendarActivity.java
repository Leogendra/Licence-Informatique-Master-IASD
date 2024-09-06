package com.example.tp1;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CalendarView;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;

public class CalendarActivity extends AppCompatActivity {

    CalendarView calendar;
    ListView events_list_view;
    LinearLayout ll;
    EditText text_view_add;
    Button button_add;
    String selected_date;

    ArrayAdapter<String> adapter;
    HashMap<String, List<String>> events_hashmap;
    List<String> events_list_string;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_calendar);

        calendar = findViewById(R.id.calendar);
        events_list_view = findViewById(R.id.list_events);
        ll = findViewById(R.id.ll);
        text_view_add = findViewById(R.id.text_view_add);
        button_add = findViewById(R.id.button_add);

        adapter = new ArrayAdapter<>(CalendarActivity.this, android.R.layout.simple_spinner_item);
        events_list_view.setAdapter(adapter);
        events_hashmap = new HashMap<>();
        events_list_string = new ArrayList<>();

        //date courrante
        Calendar today = Calendar.getInstance();
        int yearToday = today.get(Calendar.YEAR);
        int monthToday = today.get(Calendar.MONTH);
        int dayOfMonthToday = today.get(Calendar.DAY_OF_MONTH);
        selected_date = Integer.toString(yearToday)+Integer.toString(monthToday)+Integer.toString(dayOfMonthToday);

        //set de dates aléatoires
        events_list_string.add("Bonjour ! nous sommes le "+String.format("%02d", dayOfMonthToday)+" "+String.format("%02d", monthToday)+" "+yearToday);
        events_hashmap.put(selected_date, events_list_string);

        adapter.addAll(events_list_string);
        adapter.notifyDataSetChanged();

        calendar.setOnDateChangeListener((view, year, month, dayOfMonth) -> {
            selected_date = Integer.toString(year)+Integer.toString(month)+Integer.toString(dayOfMonth);
            Toast.makeText(CalendarActivity.this, Integer.toString(year)+"-"+Integer.toString(month+1)+"-"+Integer.toString(dayOfMonth), Toast.LENGTH_SHORT).show();
            events_list_string = events_hashmap.get(selected_date);  //récupère les éléments à la date sélectionnée
            if (events_list_string == null) {
                events_list_string = new ArrayList<>();
            }
            adapter.clear();
            adapter.addAll(events_list_string);
            adapter.notifyDataSetChanged();
        });

        button_add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String event_msg = text_view_add.getText().toString();
                if (event_msg.length() > 0) {
                    events_list_string = events_hashmap.get(selected_date);
                    if (events_list_string == null) {
                        events_list_string = new ArrayList<>();
                    }
                    events_list_string.add(event_msg);
                    adapter.add(event_msg);
                    adapter.notifyDataSetChanged();
                    events_hashmap.put(selected_date, events_list_string);
                    text_view_add.setText("");
                }
            }
        });
    }
}