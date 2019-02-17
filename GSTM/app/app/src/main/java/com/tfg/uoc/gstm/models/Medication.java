package com.tfg.uoc.gstm.models;

import android.support.annotation.NonNull;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class Medication implements Comparable {
    public int id;
    public String date;
    public String time;
    public String medication;
    public boolean confirmed;
    public int treatment;

    public Medication() {
        // Default constructor required for calls to DataSnapshot.getValue(Medication.class)
    }

    public Medication(int id, String date, String time, String medication, boolean confirmed, int treatment) {
        this.id = id;
        this.date = date;
        this.time = time;
        this.medication = medication;
        this.confirmed = confirmed;
        this.treatment = treatment;
    }

    public void confirmMedication() {
        this.confirmed = true;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("id", id);
        result.put("date", date);
        result.put("time", time);
        result.put("medication", medication);
        result.put("confirmed", confirmed);
        result.put("treatment", treatment);
        return result;
    }

    @Override
    public int compareTo(@NonNull Object o) {
        Medication med = (Medication)o;
        return this.date.compareTo(med.date);
    }
}
