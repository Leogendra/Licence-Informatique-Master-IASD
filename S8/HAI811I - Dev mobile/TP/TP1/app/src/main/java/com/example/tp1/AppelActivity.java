package com.example.tp1;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

public class AppelActivity extends AppCompatActivity {

    TextView phone;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_appel);

        Intent intent = getIntent();
        phone = findViewById(R.id.phone);
        phone.setText(intent.getStringExtra("phone"));

        Button callButton = findViewById(R.id.call);
        callButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i;
                if (ContextCompat.checkSelfPermission(AppelActivity.this, Manifest.permission.CALL_PHONE) == PackageManager.PERMISSION_GRANTED) {
                    i = new Intent(Intent.ACTION_CALL);
                }
                else {
                    i = new Intent(Intent.ACTION_DIAL);
                }
                i.setData(Uri.parse("tel:" + intent.getStringExtra("phone")));
                Toast.makeText(AppelActivity.this, intent.getStringExtra("phone"), Toast.LENGTH_LONG).show();
                startActivity(i);
            }
        });



        Button backButton = findViewById(R.id.home);
        backButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent i = new Intent(AppelActivity.this, MainActivity.class);
                startActivity(i);
            }
        });

    }
}