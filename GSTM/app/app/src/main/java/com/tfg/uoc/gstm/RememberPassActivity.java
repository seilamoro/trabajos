package com.tfg.uoc.gstm;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.User;

public class RememberPassActivity extends AppCompatActivity {
    private TextView mEmailView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_remember_pass);

        mEmailView = findViewById(R.id.email);

        Button mCancelButton = findViewById(R.id.cancel);
        mCancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                goLoginActivity();
            }
        });

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                accept();
            }
        });
    }

    public void goLoginActivity() {
        finish();
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
    }

    public void accept() {
        //Check data

        // Reset errors.
        mEmailView.setError(null);

        // Store values at the time of the login attempt.
        String email = mEmailView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            mEmailView.setError(getString(R.string.error_field_required));
            focusView = mEmailView;
            cancel = true;
        } else if (!isEmailValid(email)) {
            mEmailView.setError(getString(R.string.error_invalid_email));
            focusView = mEmailView;
            cancel = true;
        }

        //Buscar si existe el email
        Globals g = (Globals)getApplication();
        if(!g.existEmail(email)) {
            mEmailView.setError(getString(R.string.email_not_found));
            focusView = mEmailView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first form field with an error.
            focusView.requestFocus();
        } else {
            User user = g.getUser(email);
            if(user != null)
                sendEmail(user);
        }
    }

    private void sendEmail(User user) {
        //Creating SendMail object
        SendMail sm = new SendMail(this, user.email, getString(R.string.email_title), getString(R.string.email_mens) + " '" + user.password + "'");

        //Executing sendmail to send email
        sm.execute();

        goLoginActivity();
    }

    private boolean isEmailValid(String email) {
        return email.contains("@");
    }
}
