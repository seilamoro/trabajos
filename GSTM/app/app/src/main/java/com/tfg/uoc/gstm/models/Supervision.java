package com.tfg.uoc.gstm.models;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class Supervision {
    public int id;
    public String relationship;
    public int level; //0 for only alarms, 1 for all
    public String supervisor;
    public String supervised;

    public Supervision() {
        // Default constructor required for calls to DataSnapshot.getValue(Supervision.class)
    }

    public Supervision(int id, String relationship, int level, String supervisor, String supervised) {
        this.id = id;
        this.relationship = relationship;
        this.level = level;
        this.supervisor = supervisor;
        this.supervised = supervised;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("id", id);
        result.put("relationship", relationship);
        result.put("level", level);
        result.put("supervisor", supervisor);
        result.put("supervised", supervised);
        return result;
    }
}
