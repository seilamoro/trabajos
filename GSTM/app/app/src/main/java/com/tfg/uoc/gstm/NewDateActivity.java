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
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.User;
import com.tfg.uoc.gstm.models.MedicalDate;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

public class NewDateActivity extends AppCompatActivity {
    private int mAction; //-1 for new, other value for edit id medical date
    private Spinner mUser;
    private TextView mDateView;
    private EditText mHourView;
    private EditText mPlaceView;
    private EditText mObservationsView;
    private EditText mHoursToConfirmed;

    private ArrayList<String> mUsersEmails;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_new_date);

        mDateView = findViewById(R.id.date);
        mHourView = findViewById(R.id.hour);
        mPlaceView = findViewById(R.id.place);
        mObservationsView = findViewById(R.id.observations);
        mHoursToConfirmed = findViewById(R.id.hours_to_confirmed);
        mUser = findViewById(R.id.user);

        Globals g = (Globals)getApplication();
        changeUsersList();

        Bundle b = getIntent().getExtras();
        mAction = -1;
        String date = "";
        if(b != null) {
            mAction = b.getInt("key"); //-1 for new, other value for edit id medical date
            date = b.getString("date");
            if(date != null && !date.equals("")) {
                mDateView.setText(date);
                mHourView.requestFocus();
            }
        }

        Button mCancelButton = findViewById(R.id.cancel);
        mCancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(mAction == -1)
                    goCalendarActivity();
                else {
                    removeMedicalDate();
                }
            }
        });

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                accept();
            }
        });

        mHoursToConfirmed.setText("5"); //Default value

        if(mAction != -1) {
            mCancelButton.setText(getString(R.string.remove));
            TextView title = findViewById(R.id.title);
            title.setText(getString(R.string.medical_date));

            MedicalDate currentMedicalDate = g.getMedicalDateById(mAction);
            if(currentMedicalDate != null) {
                mDateView.setText(currentMedicalDate.date);
                mHourView.setText(currentMedicalDate.time);
                mPlaceView.setText(currentMedicalDate.place);
                mObservationsView.setText(currentMedicalDate.observations);
                mHoursToConfirmed.setText(String.valueOf(currentMedicalDate.hoursToConfirmed));

                for(int i = 0; i < mUsersEmails.size(); i++) {
                    String email = mUsersEmails.get(i);
                    if(email.equals(currentMedicalDate.user)) {
                        mUser.setSelection(i);
                    }
                }
            }
        }
    }

    public void changeUsersList() {
        Globals g = (Globals)getApplication();
        ArrayList<Supervision> supervisions = g.getCurrentUserSupervisions();
        User currentUser = g.getCurrentUser();
        String[] items = new String[supervisions.size()+1];
        items[0] = currentUser.name + " " + currentUser.surname;

        mUsersEmails = new ArrayList<>();
        mUsersEmails.add(currentUser.email);
        for(int i = 0; i < supervisions.size(); i++) {
            Supervision supervision = supervisions.get(i);
            mUsersEmails.add(supervision.supervised);
            User user = g.getUser(supervision.supervised);
            items[i+1] = user.name + " " + user.surname;
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, items);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        mUser.setAdapter(adapter);
    }

    public void goCalendarActivity() {
        finish();
        Intent intent = new Intent(this, CalendarActivity.class);
        startActivity(intent);
    }

    public void removeMedicalDate() {
        AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.medical_date));
        dialogo1.setMessage("¿Desea eliminar la cita médica?.");
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                Globals g = (Globals)getApplication();
                g.removeMedicalDate(mAction);
                goCalendarActivity();
            }
        });
        dialogo1.setNegativeButton(getString(R.string.cancel), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                //close message
            }
        });

        dialogo1.show();
    }

    public void accept() {
        //Check data
        // Reset errors.
        mDateView.setError(null);
        mHourView.setError(null);
        mPlaceView.setError(null);
        mObservationsView.setError(null);
        mHoursToConfirmed.setError(null);

        // Store values.
        int userId = mUser.getSelectedItemPosition();
        String date = mDateView.getText().toString();
        String time = mHourView.getText().toString();
        String place = mPlaceView.getText().toString();
        String observations = mObservationsView.getText().toString();
        String hoursToConfirmed = mHoursToConfirmed.getText().toString();

        boolean cancel = false;
        View focusView = null;

        String user = mUsersEmails.get(userId);
        Globals g = (Globals)getApplication();
        if(!g.existEmail(user)) {
            AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
            dialogo1.setTitle(getString(R.string.alarm_title));
            dialogo1.setMessage(getString(R.string.user_not_exist));
            dialogo1.setCancelable(false);
            dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialogo1, int id) {
                    changeUsersList();
                }
            });

            dialogo1.show();
        }
        else {
            SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
            formatter.setLenient(false);
            SimpleDateFormat formatterHour = new SimpleDateFormat("HH:mm");
            formatterHour.setLenient(false);
            Date dateConv;
            Date hourConv;

            // Check for a valid name.
            if (TextUtils.isEmpty(date)) {
                mDateView.setError(getString(R.string.error_field_required));
                focusView = mDateView;
                cancel = true;
            }
            try {
                dateConv = formatter.parse(date);
                if (dateConv == null) {
                    mDateView.setError(getString(R.string.bad_date_format));
                    focusView = mDateView;
                    cancel = true;
                }
            } catch (ParseException e) {
                mDateView.setError(getString(R.string.bad_date_format));
                focusView = mDateView;
                cancel = true;
            }

            if (TextUtils.isEmpty(time)) {
                mHourView.setError(getString(R.string.error_field_required));
                focusView = mHourView;
                cancel = true;
            }
            try {
                hourConv = formatterHour.parse(time);
                if (hourConv == null) {
                    mHourView.setError(getString(R.string.bad_hour_format));
                    focusView = mHourView;
                    cancel = true;
                }
            } catch (ParseException e) {
                mHourView.setError(getString(R.string.bad_hour_format));
                focusView = mHourView;
                cancel = true;
            }

            //The start date can not be less than the current date
            SimpleDateFormat formatterFull = new SimpleDateFormat("dd/MM/yyyy HH:mm");
            Date initDateConvFull;
            try {
                Date currentDate = new Date();
                initDateConvFull = formatterFull.parse(date + " " + time);
                if (initDateConvFull != null) {
                    if (currentDate.compareTo(initDateConvFull) > 0) {
                        mDateView.setError(getString(R.string.date_not_valid));
                        focusView = mDateView;
                        cancel = true;
                    }
                }
            } catch (ParseException e) {
                e.printStackTrace();
            }

            if (TextUtils.isEmpty(place)) {
                mPlaceView.setError(getString(R.string.error_field_required));
                focusView = mPlaceView;
                cancel = true;
            }
            if (TextUtils.isEmpty(hoursToConfirmed)) {
                mHoursToConfirmed.setError(getString(R.string.error_field_required));
                focusView = mHoursToConfirmed;
                cancel = true;
            }

            if (cancel) {
                // There was an error; don't attempt and focus the first form field with an error.
                focusView.requestFocus();
            } else {
                if (mAction == -1)
                    addNewDate(date, time, place, observations, Integer.parseInt(hoursToConfirmed), user);
                else
                    updateDate(date, time, place, observations, Integer.parseInt(hoursToConfirmed), user);
            }
        }
    }

    private void addNewDate(String date, String time, String place, String observations, int hoursToConfirmed, String user) {
        Globals g = (Globals)getApplication();
        User currentUser = g.getCurrentUser();
        int id = g.getLastMedicalDateId() + 1;

        MedicalDate medicalDate = new MedicalDate(id, date, time, place, observations, hoursToConfirmed, user, currentUser.email);
        g.addMedicalDate(medicalDate);

        goCalendarActivity();
    }

    private void updateDate(String date, String time, String place, String observations, int hoursToConfirmed, String user) {
        Globals g = (Globals)getApplication();
        MedicalDate currentMedicalDate = g.getMedicalDateById(mAction);
        currentMedicalDate.updateData(date, time, place, observations, hoursToConfirmed, user);
        g.updateMedicalDate(currentMedicalDate);

        goCalendarActivity();
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
