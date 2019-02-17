package com.tfg.uoc.gstm;

import android.app.Service;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;

import com.tfg.uoc.gstm.models.MedicalDate;
import com.tfg.uoc.gstm.models.Medication;
import com.tfg.uoc.gstm.models.Treatment;
import com.tfg.uoc.gstm.models.User;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.concurrent.TimeUnit;

public class AlertsService extends Service {
    private String mUser;

    private ArrayList<MedicalDate> mUserMedicalDate;
    private ArrayList<Medication> mUserMedication;

    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    @Override
    public void onCreate() {
        super.onCreate();

        Globals g = (Globals)getApplication();
        g.readDataBase();

        InputStreamReader file = null;
        BufferedReader read = null;
        try
        {
            file = new InputStreamReader(openFileInput("gstmUser"));
            read = new BufferedReader(file);
            mUser = read.readLine();
        }
        catch (Exception ex)
        {
            mUser = "null"; //not exist user
        }

        if(!(mUser == null || mUser.equals("null"))) {
            LoadDBWait loadDb = new LoadDBWait(mUser); //Load DB
            loadDb.execute((Void) null);
        }

        checkForAlarms();
    }

    public void checkForAlarms() {
        Globals g = (Globals)getApplication();
        User user = g.getCurrentUser();
        if(user != null)
            mUser = g.getCurrentUser().email;

        if(user != null && !user.equals("null")) {
            DateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");
            DateFormat timeFormat = new SimpleDateFormat("HH:mm");
            Date currentDate = new Date();

            String date = dateFormat.format(currentDate);
            String time = timeFormat.format(currentDate);

            String names = "";
            String ids = "";
            String times = "";

            //Next Warnings
            mUserMedicalDate = g.getUserNextMedicalDate(mUser, date, time);
            mUserMedication = g.getUserNextMedication(mUser, date, time);

            if(mUserMedication.size() != 0) { //more that one medication same time
                for(int i = 0; i < mUserMedication.size(); i++) {
                    Medication medication = mUserMedication.get(i);
                    names = names + medication.medication + "###";
                    ids = ids + medication.id + "###";
                    times = times + medication.time + "###";
                }

                Intent intent = new Intent(this, NotificationActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); //TODO: SI LA APP ESTÁ ABIERTA, LA CIERRA
                Bundle b = new Bundle();
                b.putInt("type", 1); //for medication
                b.putString("name", names);
                b.putString("id", ids);
                b.putString("time", times);
                b.putInt("Num", mUserMedication.size());
                intent.putExtras(b);
                startActivity(intent);
            }

            names = "";
            ids = "";
            times = "";
            if(mUserMedicalDate.size() != 0) { //more that one medical date in this time
                for(int i = 0; i < mUserMedicalDate.size(); i++) {
                    MedicalDate medicalDate = mUserMedicalDate.get(i);
                    names = names + medicalDate.place + "###";
                    ids = ids + medicalDate.id + "###";
                    times = times + medicalDate.date + " " + medicalDate.time + "###";
                }

                Intent intent = new Intent(this, NotificationActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); //TODO: SI LA APP ESTÁ ABIERTA, LA CIERRA
                Bundle b = new Bundle();
                b.putInt("type", 2); //for medical dates
                b.putString("name", names);
                b.putString("id", ids);
                b.putString("time", times);
                b.putInt("Num", mUserMedicalDate.size());
                intent.putExtras(b);
                startActivity(intent);
            }

            //Confirm events
            mUserMedicalDate = g.getUserConfirmPreviousMedicalDate(mUser, date, time);
            mUserMedication = g.getUserConfirmPreviousMedication(mUser, date, time);

            names = "";
            ids = "";
            times = "";
            if(mUserMedication.size() != 0) { //more that one medication same time
                for(int i = 0; i < mUserMedication.size(); i++) {
                    Medication medication = mUserMedication.get(i);
                    names = names + medication.medication + "###";
                    ids = ids + medication.id + "###";
                    times = times + medication.time + "###";
                }

                Intent intent = new Intent(this, NotificationActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); //TODO: SI LA APP ESTÁ ABIERTA, LA CIERRA
                Bundle b = new Bundle();
                b.putInt("type", 3); //for medication confirm
                b.putString("name", names);
                b.putString("id", ids);
                b.putString("time", times);
                b.putInt("Num", mUserMedication.size());
                intent.putExtras(b);
                startActivity(intent);
            }

            names = "";
            ids = "";
            times = "";
            if(mUserMedicalDate.size() != 0) { //more that one medical date in this time
                for(int i = 0; i < mUserMedicalDate.size(); i++) {
                    MedicalDate medicalDate = mUserMedicalDate.get(i);
                    names = names + medicalDate.place + "###";
                    ids = ids + medicalDate.id + "###";
                    times = times + medicalDate.time + "###";
                }

                Intent intent = new Intent(this, NotificationActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); //TODO: SI LA APP ESTÁ ABIERTA, LA CIERRA
                Bundle b = new Bundle();
                b.putInt("type", 4); //for medical dates confirm
                b.putString("name", names);
                b.putString("id", ids);
                b.putString("time", times);
                b.putInt("Num", mUserMedicalDate.size());
                intent.putExtras(b);
                startActivity(intent);
            }

            //Alert and confirmations
            names = "";
            ids = "";
            times = "";
            String types = "";
            String users = "";
            mUserMedicalDate = g.getUserSupervisionNotConfirmMedicalDate(mUser, date, time);
            mUserMedication = g.getUserSupervisionNotConfirmMedication(mUser, date, time);

            if(mUserMedicalDate.size() != 0) { //more that one medical date in this time
                for(int i = 0; i < mUserMedicalDate.size(); i++) {
                    MedicalDate medicalDate = mUserMedicalDate.get(i);
                    names = names + getString(R.string.medical_date) + " -- " + medicalDate.place + "###";
                    ids = ids + medicalDate.id + "###";
                    times = times + medicalDate.time + "###";
                    types = types + g.getSupervisionLevel(medicalDate.user) + "###";
                    users = users + medicalDate.user + "###";
                }
            }

            if(mUserMedication.size() != 0) { //more that one medication same time
                for (int i = 0; i < mUserMedication.size(); i++) {
                    Medication medication = mUserMedication.get(i);
                    names = names + getString(R.string.medicine) + medication.medication + "###";
                    ids = ids + medication.id + "###";
                    times = times + medication.time + "###";
                    Treatment treatment = g.getTreatmentById(medication.treatment);
                    types = types + g.getSupervisionLevel(treatment.user) + "###";
                    users = users + treatment.user + "###";
                }
            }

            if(mUserMedicalDate.size() != 0 || mUserMedication.size() != 0) {
                Intent intent = new Intent(this, AlarmsActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); //TODO: SI LA APP ESTÁ ABIERTA, LA CIERRA
                Bundle b = new Bundle();
                b.putString("types", types);
                b.putString("name", names);
                b.putString("id", ids);
                b.putString("time", times);
                b.putString("users", users);
                b.putInt("Num", mUserMedicalDate.size()+mUserMedication.size());
                intent.putExtras(b);
                startActivity(intent);
            }

        }

        Handler handler=new Handler();
        Runnable r=new Runnable() {
            public void run() {
                checkForAlarms();
            }
        };
        handler.postDelayed(r, 60000); //each minute
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
    }


    public class LoadDBWait extends AsyncTask<Void, Void, Boolean> {
        private final String user;

        LoadDBWait(String user) {
            this.user = user;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            Globals g = (Globals)getApplication();
            try {
                while(!g.isLoadDb()) { //Wait for load DB
                    TimeUnit.MILLISECONDS.sleep(200);
                }
            } catch (Exception e ){ e.printStackTrace(); }

            g.setCurrentUser(this.user);
            return true;
        }

        @Override
        protected void onPostExecute(final Boolean success) {

        }

        @Override
        protected void onCancelled() {
        }
    }
}
