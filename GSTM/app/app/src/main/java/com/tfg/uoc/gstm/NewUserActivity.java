package com.tfg.uoc.gstm;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import android.app.AlertDialog;
import android.content.DialogInterface;
import com.tfg.uoc.gstm.models.User;

public class NewUserActivity extends AppCompatActivity {
    private TextView mEmailView;
    private EditText mNameView;
    private EditText mSurnameView;
    private EditText mPasswordView;
    private EditText mConfirPasswordView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_new_user);

        mEmailView = findViewById(R.id.email);
        mNameView = findViewById(R.id.name);
        mSurnameView = findViewById(R.id.surname);
        mPasswordView = findViewById(R.id.password);
        mConfirPasswordView = findViewById(R.id.confirPassword);

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
        mNameView.setError(null);
        mSurnameView.setError(null);
        mPasswordView.setError(null);
        mConfirPasswordView.setError(null);

        // Store values.
        String email = mEmailView.getText().toString();
        String name = mNameView.getText().toString();
        String surname = mSurnameView.getText().toString();
        String password = mPasswordView.getText().toString();
        String confirPassword = mConfirPasswordView.getText().toString();

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

        //No se puede repetir el email.
        Globals g = (Globals)getApplication();
        if(g.existEmail(email)) {
            mEmailView.setError(getString(R.string.email_exists));
            focusView = mEmailView;
            cancel = true;
        }

        // Check for a valid name.
        if (TextUtils.isEmpty(name)) {
            mNameView.setError(getString(R.string.error_field_required));
            focusView = mNameView;
            cancel = true;
        }

        // Check for a valid surname.
        if (TextUtils.isEmpty(surname)) {
            mSurnameView.setError(getString(R.string.error_field_required));
            focusView = mSurnameView;
            cancel = true;
        }

        // Check for a valid password, if the user entered one.
        if (TextUtils.isEmpty(password) || !isPasswordValid(password)) {
            mPasswordView.setError(getString(R.string.error_invalid_password));
            focusView = mPasswordView;
            cancel = true;
        }

        if (TextUtils.isEmpty(confirPassword) || !confirPassword.equals(password)) {
            mConfirPasswordView.setError(getString(R.string.password_confirm_error));
            focusView = mConfirPasswordView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt and focus the first form field with an error.
            focusView.requestFocus();
        } else {
            addNewUser(email, password, name, surname);
        }
    }

    private void addNewUser(String email, String password, String name, String surname) {
        User user = new User(email, password, name, surname);
        Globals g = (Globals)getApplication();
        g.addNewUser(user);

        //mensaje cuenta creada y volver a login
        AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.new_user));
        dialogo1.setMessage(getString(R.string.new_user_create));
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                goLoginActivity();
            }
        });

        dialogo1.show();
    }

    private boolean isPasswordValid(String password) {
        return password.length() > 4;
    }

    private boolean isEmailValid(String email) {
        return email.contains("@");
    }
}
