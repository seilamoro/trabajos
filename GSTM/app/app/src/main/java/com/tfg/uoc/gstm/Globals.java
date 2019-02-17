package com.tfg.uoc.gstm;

import android.app.Application;
import android.content.Context;

import java.io.FileOutputStream;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Map;
import java.util.HashMap;

import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;
import com.google.firebase.database.Query;
import com.tfg.uoc.gstm.models.MedicalDate;
import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.Treatment;
import com.tfg.uoc.gstm.models.Medication;
import com.tfg.uoc.gstm.models.User;
import com.tfg.uoc.gstm.models.Request;

public class Globals extends Application {

    private DatabaseReference mDatabase;
    private User mCurrentUser = null;
    private String mCurrentUserKey;

    private Map<String, User> mUsersDataset = null;
    private Map<String, MedicalDate> mMedicalDatesDataset = null;
    private Map<String, Request> mRequestsDataset = null;
    private Map<String, Supervision> mSupervisionsDataset = null;
    private Map<String, Treatment> mTreatmentsDataset = null;
    private Map<String, Medication> mMedicationsDataset = null;

    public Globals() {

    }

    public void closeSession() {
        mCurrentUser = null;

        String filename = "gstmUser"; //Remove current user from memory
        String user = "null";
        FileOutputStream outputStream;
        try {
            outputStream = openFileOutput(filename, Context.MODE_PRIVATE);
            outputStream.write(user.getBytes());
            outputStream.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void readDataBase() {
        mDatabase = FirebaseDatabase.getInstance().getReference();

        Query queryUser = mDatabase.child("users");
        queryUser.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mUsersDataset = new HashMap<String, User>();
                for (DataSnapshot userSnapshot : snapshot.getChildren()) {
                    mUsersDataset.put(userSnapshot.getKey(), userSnapshot.getValue(User.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });

        Query queryMedicalDates = mDatabase.child("medicalDates");
        queryMedicalDates.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mMedicalDatesDataset = new HashMap<String, MedicalDate>();
                for (DataSnapshot dateSnapshot : snapshot.getChildren()) {
                    mMedicalDatesDataset.put(dateSnapshot.getKey(), dateSnapshot.getValue(MedicalDate.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });

        Query queryRequests = mDatabase.child("requests");
        queryRequests.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mRequestsDataset = new HashMap<String, Request>();
                for (DataSnapshot dateSnapshot : snapshot.getChildren()) {
                    mRequestsDataset.put(dateSnapshot.getKey(), dateSnapshot.getValue(Request.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });

        Query querySupervisions = mDatabase.child("supervisions");
        querySupervisions.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mSupervisionsDataset = new HashMap<String, Supervision>();
                for (DataSnapshot dateSnapshot : snapshot.getChildren()) {
                    mSupervisionsDataset.put(dateSnapshot.getKey(), dateSnapshot.getValue(Supervision.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });

        Query queryTreatments = mDatabase.child("treatments");
        queryTreatments.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mTreatmentsDataset = new HashMap<String, Treatment>();
                for (DataSnapshot dateSnapshot : snapshot.getChildren()) {
                    mTreatmentsDataset.put(dateSnapshot.getKey(), dateSnapshot.getValue(Treatment.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });

        Query queryMedications = mDatabase.child("medications");
        queryMedications.addValueEventListener(new ValueEventListener() {
            @Override
            public synchronized void onDataChange(DataSnapshot snapshot) {
                mMedicationsDataset = new HashMap<String, Medication>();
                for (DataSnapshot dateSnapshot : snapshot.getChildren()) {
                    mMedicationsDataset.put(dateSnapshot.getKey(), dateSnapshot.getValue(Medication.class));
                }
            }
            @Override
            public void onCancelled(DatabaseError databaseError) {
            }
        });
    }

    public User getCurrentUser() { return mCurrentUser; }
    public String getCurrentUserKey() { return mCurrentUserKey; }

    public synchronized boolean isLoadDb(){
        boolean bRes = false;
        if(mUsersDataset != null && mMedicalDatesDataset != null && mRequestsDataset != null &&
                mSupervisionsDataset != null && mTreatmentsDataset != null && mMedicationsDataset != null) {
            bRes = true;
        }
        return bRes;
    }

    public synchronized boolean setCurrentUser(String email) {
        boolean res = false;
        for(Map.Entry<String, User> entry : mUsersDataset.entrySet()) {
            User userList = entry.getValue();
            if(userList.email.equals(email)) {
                res = true;
                mCurrentUser = userList;
                mCurrentUserKey = entry.getKey();
            }
        }
        return res;
    }

    public synchronized boolean existEmail(String email) {
        boolean res = false;
        for(Map.Entry<String, User> entry : mUsersDataset.entrySet()) {
            User userList = entry.getValue();
            if(userList.email.equals(email))
                res = true;
        }
        return res;
    }

    //Validate user for login
    public synchronized boolean existUser(String email, String pass) {
        boolean res = false;
        for(Map.Entry<String, User> entry : mUsersDataset.entrySet()) {
            User userList = entry.getValue();
            if(userList.email.equals(email) && userList.password.equals(pass)) {
                res = true;
                mCurrentUser = userList;
                mCurrentUserKey = entry.getKey();
            }
        }
        return res;
    }

    public synchronized User getUser(String email) {
        User user = null;
        for(Map.Entry<String, User> entry : mUsersDataset.entrySet()) {
            User userList = entry.getValue();
            if(userList.email.equals(email))
                user = userList;
        }
        return user;
    }

    //Find the the highest ID
    public synchronized int getLastMedicalDateId() {
        int lastId = 0;
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            if(dateList.id > lastId)
                lastId = dateList.id;
        }
        return lastId;
    }

    public synchronized MedicalDate getMedicalDateById(int id) {
        MedicalDate date = null;
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            if(dateList.id == id)
                date = dateList;
        }
        return date;
    }

    public synchronized String getMedicalDateKeyById(int id) {
        String key = null;
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            if(dateList.id == id)
                key = entry.getKey();
        }
        return key;
    }

    public synchronized ArrayList<User> getFindUsers(String find) {
        ArrayList<User> userList = new ArrayList<User>();
        for(Map.Entry<String, User> entry : mUsersDataset.entrySet()) {
            User user = entry.getValue();
            if(user.email.contains(find)){
                userList.add(user);
            }
        }
        return userList;
    }

    //Find the the highest ID
    public synchronized int getLastRequestsId() {
        int lastId = 0;
        for(Map.Entry<String, Request> entry : mRequestsDataset.entrySet()) {
            Request request = entry.getValue();
            if(request.id > lastId)
                lastId = request.id;
        }
        return lastId;
    }

    public synchronized ArrayList<Request> getCurrentUserRequest() {
        ArrayList<Request> requestList = new ArrayList<Request>();
        for(Map.Entry<String, Request> entry : mRequestsDataset.entrySet()) {
            Request request = entry.getValue();
            if(request.user.equals(mCurrentUser.email) && (request.status == 0 || request.status == 1)){ //Only request new o pending
                requestList.add(request);
            }
        }
        return requestList;
    }

    public synchronized int existPendingOrAcceptSupervisionRequest(String email) { //0 not pending o accept, 1 pending or new exist, 2 accept exist
        int nRes = 0;
        for(Map.Entry<String, Request> entry : mRequestsDataset.entrySet()) {
            Request request = entry.getValue();
            if(request.user.equals(email) && request.userRequest.equals(mCurrentUser.email) && request.status != 3){ //
                if(request.status == 0 || request.status == 1) {
                    nRes = 1;
                }
                else if (request.status == 2) {
                    nRes = 2;
                }
            }
        }
        return  nRes;
    }

    public synchronized String getRequestKeyById(int id) {
        String key = null;
        for(Map.Entry<String, Request> entry : mRequestsDataset.entrySet()) {
            Request request = entry.getValue();
            if(request.id == id)
                key = entry.getKey();
        }
        return key;
    }

    //Find the the highest ID
    public synchronized int getLastSupervisionsId() {
        int lastId = 0;
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.id > lastId)
                lastId = supervision.id;
        }
        return lastId;
    }

    public synchronized ArrayList<Supervision> getCurrentUserSupervisions() {
        ArrayList<Supervision> supervisionsList = new ArrayList<Supervision>();
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.supervisor.equals(mCurrentUser.email) && existEmail(supervision.supervised)) {
                supervisionsList.add(supervision);
            }
        }
        return supervisionsList;
    }

    public synchronized ArrayList<Supervision> getCurrentUserSupervisors() {
        ArrayList<Supervision> supervisionsList = new ArrayList<Supervision>();
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.supervised.equals(mCurrentUser.email)) {
                supervisionsList.add(supervision);
            }
        }
        return supervisionsList;
    }

    public synchronized String getSupervisioKeyById(int id) {
        String key = null;
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.id == id)
                key = entry.getKey();
        }
        return key;
    }

    public synchronized int getSupervisionLevel(String supervised) {
        int res = -1;
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.supervisor.equals(mCurrentUser.email) && supervision.supervised.equals(supervised))
                res = supervision.level;
        }
        return res;
    }

    //Find the the highest ID
    public synchronized int getLastTreatmentsId() {
        int lastId = 0;
        for(Map.Entry<String, Treatment> entry : mTreatmentsDataset.entrySet()) {
            Treatment treatment = entry.getValue();
            if(treatment.id > lastId)
                lastId = treatment.id;
        }
        return lastId;
    }

    public synchronized String getTreatmentKeyById(int id) {
        String key = null;
        for(Map.Entry<String, Treatment> entry : mTreatmentsDataset.entrySet()) {
            Treatment treatment = entry.getValue();
            if(treatment.id == id)
                key = entry.getKey();
        }
        return key;
    }

    public synchronized Treatment getTreatmentById(int id) {
        Treatment treatment = null;
        for(Map.Entry<String, Treatment> entry : mTreatmentsDataset.entrySet()) {
            Treatment treatmentList = entry.getValue();
            if(treatmentList.id == id)
                treatment = treatmentList;
        }
        return treatment;
    }

    //Find the the highest ID
    public synchronized int getLastMedicationsId() {
        int lastId = 0;
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            if(medication.id > lastId)
                lastId = medication.id;
        }
        return lastId;
    }

    public synchronized String getMedicationKeyById(int id) {
        String key = null;
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            if(medication.id == id)
                key = entry.getKey();
        }
        return key;
    }

    public synchronized Medication getMedicationById(int id) {
        Medication medication = null;
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medicationList = entry.getValue();
            if(medicationList.id == id)
                medication = medicationList;
        }
        return medication;
    }

    //Return all medication to the one treatment
    public synchronized ArrayList<Medication> getAllTreatmentMedication(int idTreatment) {
        ArrayList<Medication> medicationList = new ArrayList<Medication>();
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            if(medication.treatment == idTreatment) {
                medicationList.add(medication);
            }
        }
        return medicationList;
    }

    public synchronized ArrayList<String> getUserMedicalDateByMonth(String email, int month, int year) {
        ArrayList<String> medicalDateList = new ArrayList<String>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            Date date = null;
            try {
                date = formatter.parse(dateList.date);
            } catch (ParseException e) { e.printStackTrace(); }
            if(dateList.user.equals(email) && date.getMonth() == month && date.getYear() == year)
                medicalDateList.add(dateList.date);
        }
        return medicalDateList;
    }

    public synchronized ArrayList<String> getUserMedicationByMonth(String email, int month, int year) {
        ArrayList<String> medicationList = new ArrayList<String>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            Treatment treatment = getTreatmentById(medication.treatment);
            Date date = null;
            try {
                date = formatter.parse(medication.date);
            } catch (ParseException e) { e.printStackTrace(); }
            if(treatment != null && treatment.user.equals(email) && date.getMonth() == month && date.getYear() == year)
                medicationList.add(medication.date);
        }
        return medicationList;
    }

    public synchronized ArrayList<MedicalDate> getUserMedicalDateByDay(String email, int day, int month, int year) {
        ArrayList<MedicalDate> medicalDateList = new ArrayList<MedicalDate>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            Date date = null;
            try {
                date = formatter.parse(dateList.date);
            } catch (ParseException e) { e.printStackTrace(); }

            Calendar cal = Calendar.getInstance();
            cal.setTime(date);
            int yearItem = cal.get(Calendar.YEAR);
            int monthItem = cal.get(Calendar.MONTH)+1;
            int dayItem = cal.get(Calendar.DAY_OF_MONTH);

            if(dateList.user.equals(email) && dayItem == day && monthItem == month && yearItem == year)
                medicalDateList.add(dateList);
        }
        return medicalDateList;
    }

    public synchronized ArrayList<Medication> getUserMedicationByDay(String email, int day, int month, int year) {
        ArrayList<Medication> medicationList = new ArrayList<Medication>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            Treatment treatment = getTreatmentById(medication.treatment);
            Date date = null;
            try {
                date = formatter.parse(medication.date);
            } catch (ParseException e) { e.printStackTrace(); }

            Calendar cal = Calendar.getInstance();
            cal.setTime(date);
            int yearItem = cal.get(Calendar.YEAR);
            int monthItem = cal.get(Calendar.MONTH)+1;
            int dayItem = cal.get(Calendar.DAY_OF_MONTH);

            if(treatment != null && treatment.user.equals(email) && dayItem == day && monthItem == month && yearItem == year)
                medicationList.add(medication);
        }
        return medicationList;
    }

    public synchronized ArrayList<MedicalDate> getUserNextMedicalDate(String email, String date, String time) {
        ArrayList<MedicalDate> medicalDateList = new ArrayList<MedicalDate>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            Date timeMedicalDate = null;
            try {
                timeMedicalDate = formatter.parse(dateList.date + " " + dateList.time);
            } catch (ParseException e) { e.printStackTrace(); }

            long minutes = (timeMedicalDate.getTime()/(1000*60)) - (timeFind.getTime()/(1000*60));
            if(dateList.user.equals(email) && (minutes == 60*24 || minutes == 60)) //One day or one hour
                medicalDateList.add(dateList);
        }
        return medicalDateList;
    }

    public synchronized ArrayList<Medication> getUserNextMedication(String email, String date, String time) {
        ArrayList<Medication> medicationList = new ArrayList<Medication>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            Treatment treatment = getTreatmentById(medication.treatment);

            Date timeMedication = null;
            try {
                timeMedication = formatter.parse(medication.date + " " + medication.time);
            } catch (ParseException e) { e.printStackTrace(); }

            long minutes = (timeMedication.getTime()/(1000*60)) - (timeFind.getTime()/(1000*60)); //diff in minutes
            if(treatment != null && treatment.user.equals(email) && minutes == 5.0)
                medicationList.add(medication);
        }
        return medicationList;
    }

    public synchronized ArrayList<MedicalDate> getUserConfirmPreviousMedicalDate(String email, String date, String time) {
        ArrayList<MedicalDate> medicalDateList = new ArrayList<MedicalDate>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate dateList = entry.getValue();
            Date timeMedicalDate = null;
            try {
                timeMedicalDate = formatter.parse(dateList.date + " " + dateList.time);
            } catch (ParseException e) { e.printStackTrace(); }

            long hours = (timeFind.getTime()/(1000*60*60)) - (timeMedicalDate.getTime()/(1000*60*60)); //diff in hours
            if(dateList.user.equals(email) && hours == dateList.hoursToConfirmed)
                medicalDateList.add(dateList);
        }
        return medicalDateList;
    }

    public synchronized ArrayList<Medication> getUserConfirmPreviousMedication(String email, String date, String time) {
        ArrayList<Medication> medicationList = new ArrayList<Medication>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, Medication> entry : mMedicationsDataset.entrySet()) {
            Medication medication = entry.getValue();
            Treatment treatment = getTreatmentById(medication.treatment);

            Date timeMedication = null;
            try {
                timeMedication = formatter.parse(medication.date + " " + medication.time);
            } catch (ParseException e) { e.printStackTrace(); }

            long minutes = (timeFind.getTime()/(1000*60)) - (timeMedication.getTime()/(1000*60)); //diff in minutes
            if(treatment != null && treatment.user.equals(email) && minutes == 5.0)
                medicationList.add(medication);
        }
        return medicationList;
    }

    public synchronized ArrayList<MedicalDate> getUserSupervisionNotConfirmMedicalDate(String email, String date, String time) {
        ArrayList<MedicalDate> medicalDateList = new ArrayList<MedicalDate>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.supervisor.equals(email)) { //For all user supervised by user

                for(Map.Entry<String, MedicalDate> entryMedicalDate : mMedicalDatesDataset.entrySet()) {
                    MedicalDate dateList = entryMedicalDate.getValue();
                    Date timeMedicalDate = null;
                    try {
                        timeMedicalDate = formatter.parse(dateList.date + " " + dateList.time);
                    } catch (ParseException e) { e.printStackTrace(); }

                    long hours = (timeFind.getTime() / (1000 * 60)) - (timeMedicalDate.getTime() / (1000 * 60)); //diff in minutes
                    if (dateList.user.equals(supervision.supervised) && hours == dateList.hoursToConfirmed * 60 + 5) //Past 5 minutes in time of confirmed
                        medicalDateList.add(dateList);

                }
            }
        }
        return medicalDateList;
    }

    public synchronized ArrayList<Medication> getUserSupervisionNotConfirmMedication(String email, String date, String time) {
        ArrayList<Medication> medicationList = new ArrayList<Medication>();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy HH:mm");
        Date timeFind = null;
        try {
            timeFind = formatter.parse(date + " " + time);
        } catch (ParseException e) { e.printStackTrace(); }

        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if (supervision.supervisor.equals(email)) { //For all user supervised by user
                for (Map.Entry<String, Medication> entryMedicalDate : mMedicationsDataset.entrySet()) {
                    Medication medication = entryMedicalDate.getValue();
                    Treatment treatment = getTreatmentById(medication.treatment);

                    Date timeMedication = null;
                    try {
                        timeMedication = formatter.parse(medication.date + " " + medication.time);
                    } catch (ParseException e) { e.printStackTrace(); }

                    long minutes = (timeFind.getTime() / (1000 * 60)) - (timeMedication.getTime() / (1000 * 60)); //diff in minutes
                    if (treatment != null && treatment.user.equals(supervision.supervised) && minutes == 10.0) //Past 5 minutes in time of confirmed
                        medicationList.add(medication);
                }
            }
        }
        return medicationList;
    }

    public synchronized void addNewUser(User user) {
        String key = mDatabase.child("users").push().getKey();
        Map<String, Object> values = user.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/users/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void addMedicalDate(MedicalDate medicalDate) {
        String key = mDatabase.child("medicalDates").push().getKey();
        Map<String, Object> values = medicalDate.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/medicalDates/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void addTreatment(Treatment treatment) {
        String key = mDatabase.child("treatments").push().getKey();
        Map<String, Object> values = treatment.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/treatments/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void addMedication(Medication medication) {
        String keyMed = mDatabase.child("medications").push().getKey();
        Map<String, Object> valuesMed = medication.toMap();
        Map<String, Object> childUpdatesMed = new HashMap<>();
        childUpdatesMed.put("/medications/" + keyMed, valuesMed);
        mDatabase.updateChildren(childUpdatesMed);
    }

    public synchronized void addNewRequest(Request request) {
        String key = mDatabase.child("requests").push().getKey();
        Map<String, Object> values = request.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/requests/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void addNewSupervision(Supervision supervision) {
        String key = mDatabase.child("supervisions").push().getKey();
        Map<String, Object> values = supervision.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/supervisions/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void updateCurrentUser() {
        String currentUserKey = getCurrentUserKey();

        Map<String, Object> values = mCurrentUser.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/users/" + currentUserKey, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void updateRequests(Request request) {
        String key = getRequestKeyById(request.id);

        Map<String, Object> values = request.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/requests/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void updateMedication(Medication medication) {
        String key = getMedicationKeyById(medication.id);

        Map<String, Object> values = medication.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/medications/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void updateMedicalDate(MedicalDate medicalDate) {
        String key = getMedicalDateKeyById(medicalDate.id);

        Map<String, Object> values = medicalDate.toMap();
        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/medicalDates/" + key, values);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void removeCurrentUser() {
        String key = getCurrentUserKey();
        ArrayList<Integer> itemDeleteAuxList = new ArrayList<Integer>();

        //Delete current user medical data and mark null in creates
        for(Map.Entry<String, MedicalDate> entry : mMedicalDatesDataset.entrySet()) {
            MedicalDate medicalDate = entry.getValue();
            if(medicalDate.user.equals(mCurrentUser.email)) {
                itemDeleteAuxList.add(medicalDate.id);
            } else if (medicalDate.creator.equals(mCurrentUser.email)) {
                medicalDate.creator = "null";
                updateMedicalDate(medicalDate);
            }
        }
        for(Integer i = 0; i < itemDeleteAuxList.size(); i++) {
            removeMedicalDate(itemDeleteAuxList.get(i));
        }
        itemDeleteAuxList.clear();

        //Delete current user treatment and mark null in creates
        for(Map.Entry<String, Treatment> entry : mTreatmentsDataset.entrySet()) {
            Treatment treatment = entry.getValue();
            if(treatment != null && treatment.user.equals(mCurrentUser.email)) {
                itemDeleteAuxList.add(treatment.id);
            }
            else if(treatment != null && treatment.creator.equals(mCurrentUser.email)) {
                treatment.creator = "null";
                String keyTreatment = getTreatmentKeyById(treatment.id);

                Map<String, Object> values = treatment.toMap();
                Map<String, Object> childUpdates = new HashMap<>();
                childUpdates.put("/treatments/" + keyTreatment, values);
                mDatabase.updateChildren(childUpdates);
            }
        }
        for(Integer i = 0; i < itemDeleteAuxList.size(); i++) {
            removeTreatment(itemDeleteAuxList.get(i));
        }
        itemDeleteAuxList.clear();

        //Delete request send and received
        ArrayList<String> deleteRequestList = new ArrayList<String>();
        for(Map.Entry<String, Request> entry : mRequestsDataset.entrySet()) {
            Request request = entry.getValue();
            if(request.user.equals(mCurrentUser.email) || request.userRequest.equals(mCurrentUser.email)) {
                deleteRequestList.add(getRequestKeyById(request.id));
            }
        }
        for(Integer i = 0; i < deleteRequestList.size(); i++) {
            Map<String, Object> childUpdates = new HashMap<>();
            childUpdates.put("/requests/" + deleteRequestList.get(i), null);
            mDatabase.updateChildren(childUpdates);
        }

        //Delete supervision relations
        for(Map.Entry<String, Supervision> entry : mSupervisionsDataset.entrySet()) {
            Supervision supervision = entry.getValue();
            if(supervision.supervised.equals(mCurrentUser.email) || supervision.supervisor.equals(mCurrentUser.email)) {
                itemDeleteAuxList.add(supervision.id);
            }
        }
        for(Integer i = 0; i < itemDeleteAuxList.size(); i++) {
            removeSupervision(itemDeleteAuxList.get(i));
        }
        itemDeleteAuxList.clear();

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/users/" + key, null);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void removeSupervision(int id) {
        String key = getSupervisioKeyById(id);

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/supervisions/" + key, null);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void removeTreatment(int id) {
        String key = getTreatmentKeyById(id);

        ArrayList<Integer> listToRemove = new ArrayList<Integer>();
        ArrayList<Medication> medications = getAllTreatmentMedication(id);
        for(int i = 0; i < medications.size(); i++) {
            listToRemove.add(medications.get(i).id);
        }

        for(int i = 0; i < listToRemove.size(); i++) {
            removeMedication(listToRemove.get(i));
        }

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/treatments/" + key, null);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void removeMedication(int id) {
        String key = getMedicationKeyById(id);

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/medications/" + key, null);
        mDatabase.updateChildren(childUpdates);
    }

    public synchronized void removeMedicalDate(int id) {
        String key = getMedicalDateKeyById(id);

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/medicalDates/" + key, null);
        mDatabase.updateChildren(childUpdates);
    }
}
