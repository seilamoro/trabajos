package com.tfg.uoc.gstm.models;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class MedicalDate {
    public int id;
    public String date;
    public String time;
    public String place;
    public String observations;
    public int hoursToConfirmed;
    public boolean confirmed;
    public String comments;
    public String user;
    public String creator;

    public MedicalDate() {
        // Default constructor required for calls to DataSnapshot.getValue(MedicalDate.class)
    }

    public MedicalDate(int id, String date, String time, String place, String observations, int hoursToConfirmed, String user, String creator) {
        this.id = id;
        this.date = date;
        this.time = time;
        this.place = place;
        this.observations = observations;
        this.hoursToConfirmed = hoursToConfirmed;
        this.confirmed = false;
        this.comments = "";
        this.user = user;
        this.creator = creator;
    }

    public MedicalDate(int id, String date, String time, String place, String observations, int hoursToConfirmed, boolean confirmed, String comments, String user, String creator) {
        this.id = id;
        this.date = date;
        this.time = time;
        this.place = place;
        this.observations = observations;
        this.hoursToConfirmed = hoursToConfirmed;
        this.confirmed = confirmed;
        this.comments = comments;
        this.user = user;
        this.creator = creator;
    }

    public void updateData(String date, String time, String place, String observations, int hoursToConfirmed, String user) {
        this.date = date;
        this.time = time;
        this.place = place;
        this.observations = observations;
        this.hoursToConfirmed = hoursToConfirmed;
        this.user = user;
    }

    public void confirmMedicalDate(String comments) {
        this.confirmed = true;
        this.comments = comments;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("id", id);
        result.put("date", date);
        result.put("time", time);
        result.put("place", place);
        result.put("observations", observations);
        result.put("hoursToConfirmed", hoursToConfirmed);
        result.put("confirmed", confirmed);
        result.put("comments", comments);
        result.put("user", user);
        result.put("creator", creator);
        return result;
    }
}
