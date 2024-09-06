package com.example.tp1;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

public class ValidationActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_validation);
        Intent intent = getIntent();
        TextView firstname = findViewById(R.id.firstname);
        TextView name = findViewById(R.id.name);
        TextView age = findViewById(R.id.age);
        TextView phone = findViewById(R.id.phone);
        firstname.setText(getResources().getString(R.string.firstname)+" : "+intent.getStringExtra("firstname"));
        name.setText(getResources().getString(R.string.name)+" : "+intent.getStringExtra("name"));
        age.setText(getResources().getString(R.string.age)+" : "+intent.getStringExtra("age"));
        phone.setText(getResources().getString(R.string.phone)+" : "+intent.getStringExtra("phone"));

        Button okButton = findViewById(R.id.button_ok);
        okButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view)
            {
                okFunc(view);
            }

            private void okFunc(View view) {
                Intent i = new Intent(ValidationActivity.this, AppelActivity.class);
                TextView phone = findViewById(R.id.phone);
                i.putExtra("phone", phone.getText().toString());
                startActivity(i);
            }
        });
        Button ButtonBack = findViewById(R.id.button_back);
        ButtonBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view)
            {
                backFunc(view);
            }

            private void backFunc(View view) {
                finish();
            }
        });
    }
}