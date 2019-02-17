package com.tfg.uoc.gstm;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.MedicalDate;
import com.tfg.uoc.gstm.models.Medication;
import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.User;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Date;

public class DateDetailsActivity extends AppCompatActivity {
    private String mDate;

    private TextView mDateView;
    private ListView mDateDetailsView;
    private DateDetailsAdapter mDateDetailsAdapter;

    private ArrayList<MedicalDate> mUserMedicalDate;
    private ArrayList<Medication> mUserMedication;
    private ArrayList<String> mHoursList;

    private Spinner mUser;
    private ArrayList<String> mUsersEmails;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_date_details);

        mUser = findViewById(R.id.user);
        Globals g = (Globals)getApplication();
        changeUsersList();

        String user = g.getCurrentUser().email;
        Bundle b = getIntent().getExtras();
        if(b != null) {
            mDate = b.getString("date");
            user = b.getString("user");
        }

        for(int i = 0; i < mUsersEmails.size(); i++) {
            String email = mUsersEmails.get(i);
            if(email.equals(user)) {
                mUser.setSelection(i);
            }
        }

        mDateView = findViewById(R.id.date);
        mDateView.setText(mDate);

        mHoursList = new ArrayList<String>();
        mDateDetailsView = findViewById(R.id.date_details);
        mDateDetailsAdapter = new DateDetailsAdapter(this, mHoursList);
        mDateDetailsView.setAdapter(mDateDetailsAdapter);

        showUserDateDetails(user);

        Button mNewTreatmentButton = findViewById(R.id.new_treatment);
        mNewTreatmentButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openNewTreatmentActivity();
            }
        });

        Button mNewMedicalDateButton = findViewById(R.id.new_medical_date);
        mNewMedicalDateButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openNewDateActivity();
            }
        });

        mUser.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id) {
                String user = mUsersEmails.get(position);
                showUserDateDetails(user);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parentView) { }
        });
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

    public void showUserDateDetails(String user) {
        Globals g = (Globals)getApplication();

        if(g.existEmail(user)) {
            SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
            Date dayFind = null;
            try {
                dayFind = formatter.parse(mDate);
            } catch (ParseException e) {
                e.printStackTrace();
            }

            Calendar cal = Calendar.getInstance();
            cal.setTime(dayFind);
            int year = cal.get(Calendar.YEAR);
            int month = cal.get(Calendar.MONTH) + 1;
            int day = cal.get(Calendar.DAY_OF_MONTH);
            mUserMedicalDate = g.getUserMedicalDateByDay(user, day, month, year);
            mUserMedication = g.getUserMedicationByDay(user, day, month, year);

            mHoursList.clear();

            for (int i = 0; i < mUserMedicalDate.size(); i++) {
                MedicalDate medicalDate = mUserMedicalDate.get(i);
                mHoursList.add(medicalDate.time + "### Cita médica: " + medicalDate.place + "###0###" + medicalDate.id);
            }

            for (int i = 0; i < mUserMedication.size(); i++) {
                Medication medication = mUserMedication.get(i);
                mHoursList.add(medication.time + "### Medicamento: " + medication.medication + "###1###" + medication.treatment);
            }

            Collections.sort(mHoursList);
            mDateDetailsAdapter.notifyDataSetChanged();
        }
        else {
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
    }

    public void openNewTreatmentActivity() {
        Intent intent = new Intent(this, NewTreatmentActivity.class);
        Bundle b = new Bundle();
        b.putInt("key", -1); //for create
        intent.putExtras(b);
        startActivity(intent);
    }

    public void openNewDateActivity() {
        Intent intent = new Intent(this, NewDateActivity.class);
        Bundle b = new Bundle();
        b.putInt("key", -1); //for create
        b.putString("date", mDate); //for create
        intent.putExtras(b);
        startActivity(intent);
    }

    public void notifyEditElement(int position) {
        String item = mHoursList.get(position);
        String[] parts = item.split("###");
        int id = Integer.parseInt(parts[3]);
        if(parts[2].equals("0")) {  //edit medical date
            Intent intent = new Intent(this, NewDateActivity.class);
            Bundle b = new Bundle();
            b.putInt("key", id); //for edit
            intent.putExtras(b);
            startActivity(intent);
        }
        else if(parts[2].equals("1")) { //edit treatment
            Intent intent = new Intent(this, NewTreatmentActivity.class);
            Bundle b = new Bundle();
            b.putInt("key", id); //for edit
            intent.putExtras(b);
            startActivity(intent);
        }
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
