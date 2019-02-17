package com.tfg.uoc.gstm.models;

import com.google.firebase.database.Exclude;
import com.google.firebase.database.IgnoreExtraProperties;

import java.util.HashMap;
import java.util.Map;

@IgnoreExtraProperties
public class User {

    public String email;
    public String password;
    public String name;
    public String surname;

    public User() {
        // Default constructor required for calls to DataSnapshot.getValue(User.class)
    }

    public User(String email, String password, String name, String surname) {
        this.email = email;
        this.password = password;
        this.name = name;
        this.surname = surname;
    }

    public void updateData(String name, String surname) {
        this.name = name;
        this.surname = surname;
    }

    public void updatePassword(String password) {
        this.password = password;
    }

    @Exclude
    public Map<String, Object> toMap() {
        HashMap<String, Object> result = new HashMap<>();
        result.put("email", email);
        result.put("password", password);
        result.put("name", name);
        result.put("surname", surname);
        return result;
    }
}