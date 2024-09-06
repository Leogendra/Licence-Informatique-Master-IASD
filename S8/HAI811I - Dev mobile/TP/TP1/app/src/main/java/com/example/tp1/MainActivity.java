package com.example.tp1;

import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.util.Locale;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //XML
        setContentView(R.layout.activity_main);
        //ConstraintLayout layout = new ConstraintLayout(this);
        // setContentView(layout);

        Button button_language = findViewById(R.id.button_language);
        button_language.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String languageToLoad;
                if (Locale.getDefault().getLanguage() == "en") {
                    languageToLoad= "fr";
                    Toast.makeText(MainActivity.this, R.string.changed_language, Toast.LENGTH_LONG).show();
                } else {
                    languageToLoad= "en";
                    Toast.makeText(MainActivity.this, R.string.changed_language, Toast.LENGTH_LONG).show();
                }
                Locale locale = new Locale(languageToLoad);
                Locale.setDefault(locale);
                Configuration config = new Configuration();
                config.locale = locale;
                getBaseContext().getResources().updateConfiguration(config, getBaseContext().getResources().getDisplayMetrics());
                recreate();
            }
        });

        Button button_validate = findViewById(R.id.button_validate);
        button_validate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                onValidate(view);
            }
        });

        Button trainButton = findViewById(R.id.train);
        trainButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(MainActivity.this, TrainActivity.class);
                startActivity(i);
            }
        });

        Button calendarButton = findViewById(R.id.calendar);
        calendarButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(MainActivity.this, CalendarActivity.class);
                startActivity(i);
            }
        });
    }

    public void onValidate(View view){
        EditText phone = findViewById(R.id.editTextPhone);
        if(phone.getText().toString().length() <= 1) {
            Toast.makeText(MainActivity.this,R.string.invalid_phone,Toast.LENGTH_SHORT).show();
        }
        else if (false) {
            //autres checks
        }
        else {
            AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
            alertDialogBuilder.setTitle(R.string.validation_title);
            alertDialogBuilder.setMessage(R.string.validation_msg);
            alertDialogBuilder.setCancelable(false);

            alertDialogBuilder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {

                @Override
                public void onClick(DialogInterface arg0, int arg1) {
                    Intent i = new Intent(MainActivity.this, ValidationActivity.class);
                    EditText name = findViewById(R.id.editTextTextPersonName);
                    EditText firstname = findViewById(R.id.editTextTextPersonFirstname);
                    EditText age = findViewById(R.id.editTextNumber);
                    EditText phone = findViewById(R.id.editTextPhone);
                    i.putExtra("name", name.getText().toString());
                    i.putExtra("firstname", firstname.getText().toString());
                    i.putExtra("age", age.getText().toString());
                    i.putExtra("phone", phone.getText().toString());
                    Toast.makeText(MainActivity.this, R.string.toast_validated, Toast.LENGTH_SHORT).show();
                    startActivity(i);
                }
            });

            alertDialogBuilder.setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    Toast.makeText(MainActivity.this, R.string.toast_canceled, Toast.LENGTH_SHORT).show();
                }
            });

            AlertDialog dialogueValider = alertDialogBuilder.create();
            dialogueValider.show();
        }

    }
}