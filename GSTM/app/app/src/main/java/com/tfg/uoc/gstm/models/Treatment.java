package com.tfg.uoc.gstm.models;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class Treatment {
    public int id;
    public String name;
    public String user;
    public String creator;

    public Treatment() {
        // Default constructor required for calls to DataSnapshot.getValue(Treatment.class)
    }

    public Treatment(int id, String name, String user, String creator) {
        this.id = id;
        this.name = name;
        this.user = user;
        this.creator = creator;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("id", id);
        result.put("name", name);
        result.put("user", user);
        result.put("creator", creator);
        return result;
    }
}
