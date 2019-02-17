package com.tfg.uoc.gstm;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.Medication;
import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.Treatment;
import com.tfg.uoc.gstm.models.User;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.Date;

public class NewTreatmentActivity extends AppCompatActivity {
    private int mAction; //-1 for new, other value for edit id medical date
    private ListView mMedicationsView;
    private MedicationListAdapter mMedicationsAdapter;
    ArrayList<AddMedications> mAllMedication;

    private TextView mNameView;
    private Spinner mUser;
    private ArrayList<String> mUsersEmails;

    private int mHour;
    private int mIndex;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_new_treatment);

        mHour = 0;
        mIndex = -1;
        mAllMedication = new ArrayList<AddMedications>();

        mMedicationsView = findViewById(R.id.medications);
        mMedicationsAdapter = new MedicationListAdapter(this, mAllMedication);
        mMedicationsView.setAdapter(mMedicationsAdapter);

        mNameView = findViewById(R.id.name);
        mUser = findViewById(R.id.user);

        changeUsersList();
        Globals g = (Globals)getApplication();

        ImageView mAddMedicationButton = findViewById(R.id.addMedication);
        mAddMedicationButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mIndex = -1;
                addMedication();
            }
        });

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                accept();
            }
        });

        Button mCancelButton = findViewById(R.id.cancel);
        mCancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(mAction == -1)
                    goCalendarActivity();
                else {
                    removeTreatment();
                }
            }
        });

        Bundle b = getIntent().getExtras();
        mAction = -1;
        if(b != null) {
            mAction = b.getInt("key"); //-1 for new, other value for edit id treatment
        }

        if(mAction != -1) {
            mCancelButton.setText(getString(R.string.remove));
            TextView title = findViewById(R.id.title);
            title.setText(getString(R.string.treatment));

            Treatment treatment = g.getTreatmentById(mAction);
            mNameView.setText(treatment.name);

            for(int i = 0; i < mUsersEmails.size(); i++) {
                String email = mUsersEmails.get(i);
                if(email.equals(treatment.user)) {
                    mUser.setSelection(i);
                }
            }

            ArrayList<Medication> medications = g.getAllTreatmentMedication(mAction);
            Collections.sort(medications);

            ArrayList<String> hoursList = new ArrayList<String>();
            int idGroup = -1;
            String medicationName = "";
            String dateInitMedication = "";
            String dateEndMedication = "";
            if(medications.size() != 0) { //First medication group
                idGroup = medications.get(0).id;
                medicationName = medications.get(0).medication;
                dateInitMedication = medications.get(0).date;
                dateEndMedication = medications.get(0).date;
            }

            for(int i = 0; i < medications.size(); i++) {
                Medication medication = medications.get(i);
                if(medication.id == idGroup) {
                    if (medication.date.equals(dateInitMedication)) //Only one day to get the hours lists
                        hoursList.add(medication.time);
                    dateEndMedication = medication.date;
                }
                else {
                    //Save the previous medication
                    AddMedications addMedications = new AddMedications(dateInitMedication, dateEndMedication, medicationName, hoursList);
                    mAllMedication.add(addMedications);

                    //Next medication group
                    idGroup = medication.id;
                    medicationName = medication.medication;
                    dateInitMedication = medication.date;
                    dateEndMedication = medication.date;
                }
            }
            //Save the last medication group
            AddMedications addMedications = new AddMedications(dateInitMedication, dateEndMedication, medicationName, hoursList);
            mAllMedication.add(addMedications);
            mMedicationsAdapter.notifyDataSetChanged();
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

    public void removeTreatment() {
        android.app.AlertDialog.Builder dialogo1 = new android.app.AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.treatment));
        dialogo1.setMessage("¿Desea eliminar el tratamiento médico?");
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                Globals g = (Globals)getApplication();
                g.removeTreatment(mAction);
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

    public void notifyChangeAddMedication(int position) {
        mIndex = position;
        addMedication();
    }

    public void notifyDeleteAddMedication(int position) {
        mIndex = position;

        //mensaje de conformación
        android.app.AlertDialog.Builder dialogo1 = new android.app.AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.medicines));
        dialogo1.setMessage("¿Desea eliminar la medicación?");
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                mAllMedication.remove(mIndex);
                mMedicationsAdapter.notifyDataSetChanged();
                mIndex = -1;
            }
        });
        dialogo1.setNegativeButton(getString(R.string.cancel), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                //close message
            }
        });

        dialogo1.show();
    }

    public void addMedication() {
        LayoutInflater inflater = getLayoutInflater();
        View alertLayout = inflater.inflate(R.layout.add_medication, null);

        final EditText name = alertLayout.findViewById(R.id.med_name);
        final EditText initDate = alertLayout.findViewById(R.id.init_date);
        final EditText endDate = alertLayout.findViewById(R.id.end_date);

        final EditText hour0 = alertLayout.findViewById(R.id.hour0);
        final EditText hour1 = alertLayout.findViewById(R.id.hour1);
        final EditText hour2 = alertLayout.findViewById(R.id.hour2);
        final EditText hour3 = alertLayout.findViewById(R.id.hour3);

        final LinearLayout hour1Layout = alertLayout.findViewById(R.id.hour1_layout);
        final LinearLayout hour2Layout = alertLayout.findViewById(R.id.hour2_layout);
        final LinearLayout hour3Layout = alertLayout.findViewById(R.id.hour3_layout);

        if (mIndex == -1) { //New medication
            mHour = 0;
        } else {
            AddMedications addMedication = mAllMedication.get(mIndex);
            name.setText(addMedication.mMedication);
            initDate.setText(addMedication.mInitDate);
            endDate.setText(addMedication.mEndDate);
            if(addMedication.mHoursList.size() >= 1)
                hour0.setText(addMedication.mHoursList.get(0));
            if(addMedication.mHoursList.size() >= 2) {
                hour1Layout.setVisibility(View.VISIBLE);
                hour1.setText(addMedication.mHoursList.get(1));
            }
            if(addMedication.mHoursList.size() >= 3){
                hour2Layout.setVisibility(View.VISIBLE);
                hour2.setText(addMedication.mHoursList.get(2));
            }
            if(addMedication.mHoursList.size() >= 4){
                hour3Layout.setVisibility(View.VISIBLE);
                hour3.setText(addMedication.mHoursList.get(3));
            }
        }

        final ImageView mAddHourButton = alertLayout.findViewById(R.id.addHour);
        mAddHourButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mHour++;
                if(mHour == 1) {
                    hour1Layout.setVisibility(View.VISIBLE);
                    hour1.requestFocus();
                }
                else if(mHour == 2) {
                    hour2Layout.setVisibility(View.VISIBLE);
                    hour2.requestFocus();
                }
                else if(mHour == 3) {
                    hour3Layout.setVisibility(View.VISIBLE);
                    hour3.requestFocus();
                }
            }
        });

        AlertDialog.Builder alert = new AlertDialog.Builder(this);
        alert.setTitle(getString(R.string.medicines));
        alert.setView(alertLayout);
        alert.setCancelable(false);
        alert.setNegativeButton(getString(R.string.cancel), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

            }
        });

        alert.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

            }
        });
        final AlertDialog dialog = alert.create();
        dialog.show();

        dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new   View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                //Check data
                // Reset errors.
                name.setError(null);
                initDate.setError(null);
                endDate.setError(null);
                hour0.setError(null);
                hour1.setError(null);
                hour2.setError(null);
                hour3.setError(null);

                // Store values.
                String nameVal = name.getText().toString();
                String initDateVal = initDate.getText().toString();
                String endDateVal = endDate.getText().toString();
                String hour0Val = hour0.getText().toString();
                String hour1Val = hour1.getText().toString();
                String hour2Val = hour2.getText().toString();
                String hour3Val = hour3.getText().toString();

                boolean cancel = false;
                View focusView = null;

                // Check for a valid name.
                if (TextUtils.isEmpty(nameVal)) {
                    name.setError(getString(R.string.error_field_required));
                    focusView = name;
                    cancel = true;
                }

                SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
                formatter.setLenient(false);
                SimpleDateFormat formatterHour = new SimpleDateFormat("HH:mm");
                formatterHour.setLenient(false);
                Date initDateConv = null;
                Date endDateConv = null;
                Date hour0Conv;
                Date hour1Conv ;
                Date hour2Conv;
                Date hour3Conv;

                // Check for a valid init date.
                if (TextUtils.isEmpty(initDateVal)) {
                    initDate.setError(getString(R.string.error_field_required));
                    focusView = initDate;
                    cancel = true;
                }
                try {
                    initDateConv = formatter.parse(initDateVal);
                    if(initDateConv == null) {
                        initDate.setError(getString(R.string.bad_date_format));
                        focusView = initDate;
                        cancel = true;
                    }
                } catch (ParseException e) {
                    initDate.setError(getString(R.string.bad_date_format));
                    focusView = initDate;
                    cancel = true;
                }

                // Check for a valid end date.
                if (TextUtils.isEmpty(endDateVal)) {
                    endDate.setError(getString(R.string.error_field_required));
                    focusView = endDate;
                    cancel = true;
                }
                try {
                    endDateConv = formatter.parse(endDateVal);
                    if(endDateConv == null) {
                        endDate.setError(getString(R.string.bad_date_format));
                        focusView = endDate;
                        cancel = true;
                    }
                } catch (ParseException e) {
                    endDate.setError(getString(R.string.bad_date_format));
                    focusView = endDate;
                    cancel = true;
                }
                if((endDateConv != null && initDateConv != null) && endDateConv.compareTo(initDateConv) < 0) {
                    endDate.setError(getString(R.string.bad_end_date));
                    focusView = endDate;
                    cancel = true;
                }

                // Check for a valid hour 0.
                if (TextUtils.isEmpty(hour0Val)) {
                    hour0.setError(getString(R.string.error_field_required));
                    focusView = hour0;
                    cancel = true;
                }
                try {
                    hour0Conv = formatterHour.parse(hour0Val);
                    if(hour0Conv == null) {
                        hour0.setError(getString(R.string.bad_hour_format));
                        focusView = hour0;
                        cancel = true;
                    }
                } catch (ParseException e) {
                    hour0.setError(getString(R.string.bad_hour_format));
                    focusView = hour0;
                    cancel = true;
                }

                // Check for a valid hour 1.
                if(hour1Layout.getVisibility() == View.VISIBLE) {
                    if (TextUtils.isEmpty(hour1Val)) {
                        hour1.setError(getString(R.string.error_field_required));
                        focusView = hour1;
                        cancel = true;
                    }
                    try {
                        hour1Conv = formatterHour.parse(hour1Val);
                        if(hour1Conv == null) {
                            hour1.setError(getString(R.string.bad_hour_format));
                            focusView = hour1;
                            cancel = true;
                        }
                    } catch (ParseException e) {
                        hour1.setError(getString(R.string.bad_hour_format));
                        focusView = hour1;
                        cancel = true;
                    }
                }

                // Check for a valid hour 2.
                if(hour2Layout.getVisibility() == View.VISIBLE) {
                    if (TextUtils.isEmpty(hour2Val)) {
                        hour2.setError(getString(R.string.error_field_required));
                        focusView = hour2;
                        cancel = true;
                    }
                    try {
                        hour2Conv = formatterHour.parse(hour2Val);
                        if(hour2Conv == null) {
                            hour2.setError(getString(R.string.bad_hour_format));
                            focusView = hour2;
                            cancel = true;
                        }
                    } catch (ParseException e) {
                        hour2.setError(getString(R.string.bad_hour_format));
                        focusView = hour2;
                        cancel = true;
                    }
                }

                // Check for a valid hour 3.
                if(hour3Layout.getVisibility() == View.VISIBLE) {
                    if (TextUtils.isEmpty(hour3Val)) {
                        hour3.setError(getString(R.string.error_field_required));
                        focusView = hour3;
                        cancel = true;
                    }
                    try {
                        hour3Conv = formatterHour.parse(hour3Val);
                        if(hour3Conv == null) {
                            hour3.setError(getString(R.string.bad_hour_format));
                            focusView = hour3;
                            cancel = true;
                        }
                    } catch (ParseException e) {
                        hour3.setError(getString(R.string.bad_hour_format));
                        focusView = hour3;
                        cancel = true;
                    }
                }

                //The start date can not be less than the current date
                SimpleDateFormat formatterFull = new SimpleDateFormat("dd/MM/yyyy HH:mm");
                Date initDateConvFull;
                try {
                    Date currentDate = new Date();
                    initDateConvFull = formatterFull.parse(initDateVal + " " + hour0Val);
                    if(initDateConvFull != null) {
                        if (currentDate.compareTo(initDateConvFull) > 0) {
                            endDate.setError(getString(R.string.date_not_valid));
                            focusView = endDate;
                            cancel = true;
                        }
                    }
                } catch (ParseException e) { e.printStackTrace(); }


                if (cancel) {
                    // There was an error; don't attempt and focus the first form field with an error.
                    focusView.requestFocus();
                } else {
                    ArrayList<String> hoursList = new ArrayList<String>();
                    if(!hour0.getText().toString().equals(""))
                        hoursList.add(hour0.getText().toString());
                    if(!hour1.getText().toString().equals(""))
                        hoursList.add(hour1.getText().toString());
                    if(!hour2.getText().toString().equals(""))
                        hoursList.add(hour2.getText().toString());
                    if(!hour3.getText().toString().equals(""))
                        hoursList.add(hour3.getText().toString());
                    if(mIndex == -1)
                        NewAddMedication(initDate.getText().toString(), endDate.getText().toString(), name.getText().toString(), hoursList);
                    else
                        updateAddNewMedication(initDate.getText().toString(), endDate.getText().toString(), name.getText().toString(), hoursList);
                    dialog.dismiss();
                }
            }
        });
    }

    public void updateAddNewMedication(String initDate, String endDate, String medication, ArrayList<String> hoursList) {
        AddMedications medications = mAllMedication.get(mIndex);
        medications.mInitDate = initDate;
        medications.mEndDate = endDate;
        medications.mMedication = medication;
        medications.mHoursList = hoursList;
        mMedicationsAdapter.notifyDataSetChanged();
    }

    public void NewAddMedication(String initDate, String endDate, String medication, ArrayList<String> hoursList) {
        AddMedications addMedications = new AddMedications(initDate, endDate, medication, hoursList);
        mAllMedication.add(addMedications);
        mMedicationsAdapter.notifyDataSetChanged();
    }

    public void accept() {
        //Check data

        // Reset errors.
        mNameView.setError(null);

        // Store values.
        int userId = mUser.getSelectedItemPosition();
        String name = mNameView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        String user = mUsersEmails.get(userId);
        Globals g = (Globals)getApplication();
        if(!g.existEmail(user)) {
            android.app.AlertDialog.Builder dialogo1 = new android.app.AlertDialog.Builder(this);
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
            // Check for a valid name.
            if (TextUtils.isEmpty(name)) {
                mNameView.setError(getString(R.string.error_field_required));
                focusView = mNameView;
                cancel = true;
            }
            if (mAllMedication.size() == 0) {
                mNameView.setError(getString(R.string.medicines_error));
                focusView = mNameView;
                cancel = true;
            }

            if (cancel) {
                // There was an error; don't attempt and focus the first form field with an error.
                focusView.requestFocus();
            } else {
                if (mAction != -1) { //For edit treatment, first delete treatment and add new
                    g.removeTreatment(mAction);
                }
                addNewTreatment(name, user);
            }
        }
    }

    private void addNewTreatment(String name, String user) {
        Globals g = (Globals)getApplication();
        User currentUser = g.getCurrentUser();
        int id = g.getLastTreatmentsId() + 1;

        Treatment treatment = new Treatment(id, name, user, currentUser.email);
        g.addTreatment(treatment);

        //Add the medications
        for(int i = 0; i < mAllMedication.size(); i++) {
            AddMedications medicationToAdd = mAllMedication.get(i);

            SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
            Date initDate = null;
            Date endDate = null;
            try {
                initDate = formatter.parse(medicationToAdd.mInitDate);
                endDate = formatter.parse(medicationToAdd.mEndDate);
            } catch (ParseException e) { e.printStackTrace(); }

            Calendar current = Calendar.getInstance();
            Calendar end = Calendar.getInstance();
            current.setTime(initDate);
            end.setTime(endDate);

            int idMed = g.getLastMedicationsId()+1;
            while(!current.after(end)) {
                //idMed = idMed + 1;
                for(int h = 0; h < medicationToAdd.mHoursList.size(); h++) {
                    String hour = medicationToAdd.mHoursList.get(h);
                    String day = formatter.format(current.getTime());
                    Medication medication = new Medication(idMed, day, hour, medicationToAdd.mMedication, false, id);
                    g.addMedication(medication);
                }
                current.add(Calendar.DATE, 1);
            }
        }

        Handler handler=new Handler();
        Runnable r=new Runnable() {
            public void run() {
                //Wait for udpdate
            }
        };
        handler.postDelayed(r, 300); //Wait for update data

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
