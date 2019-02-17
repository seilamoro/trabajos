package com.tfg.uoc.gstm.models;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class Request {
    public int id;
    public String date;
    public int status; //0 for new, 1 for pending, 2 for accepted, 3 for canceled
    public String user;
    public String userRequest; //user who send the request
    public String relationship;
    public int level;  //0 for only alarms, 1 for all

    public Request() {
        // Default constructor required for calls to DataSnapshot.getValue(Request.class)
    }

    public Request(int id, String date, int status, String user, String userRequest, String relationship, int level) {
        this.id = id;
        this.date = date;
        this.status = status;
        this.user = user;
        this.userRequest = userRequest;
        this.relationship = relationship;
        this.level = level;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("id", id);
        result.put("date", date);
        result.put("status", status);
        result.put("user", user);
        result.put("userRequest", userRequest);
        result.put("relationship", relationship);
        result.put("level", level);
        return result;
    }
}
