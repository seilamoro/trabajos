package com.tfg.uoc.gstm;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.User;

public class UserDataActivity extends AppCompatActivity {
    private TextView mEmailView;
    private EditText mNameView;
    private EditText mSurnameView;

    private CheckBox mChangePassButton;
    private EditText mPasswordView;
    private EditText mConfirPasswordView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_data);

        mEmailView = findViewById(R.id.email);
        mNameView = findViewById(R.id.name);
        mSurnameView = findViewById(R.id.surname);
        mPasswordView = findViewById(R.id.password);
        mConfirPasswordView = findViewById(R.id.confirPassword);

        mPasswordView.setEnabled(false);
        mConfirPasswordView.setEnabled(false);

        Button mCancelButton = findViewById(R.id.cancel);
        mCancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                goCalendarActivity();
            }
        });

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                accept();
            }
        });

        Button mUnsubscribeButton = findViewById(R.id.remove_user_button);
        mUnsubscribeButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                unsubscribeUser();
            }
        });

        mChangePassButton = findViewById(R.id.change_pass);
        mChangePassButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                boolean checked = ((CheckBox) v).isChecked();
                mPasswordView.setText("");
                mConfirPasswordView.setText("");
                if(checked) {
                    mPasswordView.setEnabled(true);
                    mConfirPasswordView.setEnabled(true);
                }
                else {
                    mPasswordView.setEnabled(false);
                    mConfirPasswordView.setEnabled(false);
                }
            }
        });

        Globals g = (Globals)getApplication();
        User user = g.getCurrentUser();

        mEmailView.setText(user.email);
        mNameView.setText(user.name);
        mSurnameView.setText(user.surname);
    }

    public void goCalendarActivity() {
        finish();
        Intent intent = new Intent(this, CalendarActivity.class);
        startActivity(intent);
    }

    public void unsubscribeUser() {
        //mensaje de conformación
        AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.my_profile));
        dialogo1.setMessage("¿Desea eliminar la cuenta de usuario?.");
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                Globals g = (Globals)getApplication();
                g.removeCurrentUser();

                Handler handler=new Handler();
                Runnable r=new Runnable() {
                    public void run() {
                        Globals g = (Globals)getApplication();
                        g.closeSession();
                        goLoginActivity();
                    }
                };
                handler.postDelayed(r, 600); //Wait for update data
            }
        });
        dialogo1.setNegativeButton(getString(R.string.cancel), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                //close message
            }
        });

        dialogo1.show();
    }

    public void goLoginActivity() {
        finish();
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
    }

    public void accept() {
        //Check data

        // Reset errors.
        mNameView.setError(null);
        mSurnameView.setError(null);
        mPasswordView.setError(null);
        mConfirPasswordView.setError(null);

        // Store values.
        String name = mNameView.getText().toString();
        String surname = mSurnameView.getText().toString();
        String password = mPasswordView.getText().toString();
        String confirPassword = mConfirPasswordView.getText().toString();

        boolean cancel = false;
        View focusView = null;

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

        boolean checked = mChangePassButton.isChecked();
        if(checked) {
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
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first form field with an error.
            focusView.requestFocus();
        } else {
            updateUser(name, surname);
            if(checked)
                updatePassword(password);
            goCalendarActivity();
        }
    }

    private void updateUser(String name, String surname) {
        Globals g = (Globals)getApplication();
        User currentUser = g.getCurrentUser();

        currentUser.updateData(name, surname);
        g.updateCurrentUser();
    }

    private void updatePassword(String password) {
        Globals g = (Globals)getApplication();
        User currentUser = g.getCurrentUser();

        currentUser.updatePassword(password);
        g.updateCurrentUser();
    }

    private boolean isPasswordValid(String password) {
        return password.length() > 4;
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()) {
            case R.id.action_calendar:
                Handler handler=new Handler();
                Runnable r=new Runnable() {
                    public void run() {
                        Intent calendar = new Intent(getApplicationContext(), CalendarActivity.class);
                        startActivity(calendar);
                    }
                };
                handler.postDelayed(r, 300);
                break;
            case R.id.action_profile :
                Intent profile = new Intent(getApplicationContext(), UserDataActivity.class);
                startActivity(profile);
                break;
            case R.id.action_supervisions :
                Intent supervisions = new Intent(getApplicationContext(), SupervisionsActivity.class);
                startActivity(supervisions);
                break;
            case R.id.action_requests :
                Intent requests = new Intent(getApplicationContext(), RequestActivity.class);
                startActivity(requests);
                break;
            case R.id.action_supervisions_request :
                Intent supervisions_request = new Intent(getApplicationContext(), SupervisionsRequestActivity.class);
                startActivity(supervisions_request);
                break;
            case R.id.action_close_session :
                Globals g = (Globals)getApplication();
                g.closeSession();
                Intent login = new Intent(getApplicationContext(), LoginActivity.class);
                login.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                startActivity(login);
                break;
        }
        return super.onOptionsItemSelected(item);
    }
}
