package com.tfg.uoc.gstm;

import java.util.ArrayList;

public class AddMedications {
    public String mInitDate;
    public String mEndDate;
    public String mMedication;
    public ArrayList<String> mHoursList;

    public AddMedications(String initDate, String endDate, String medication, ArrayList<String> hoursList){
        this.mInitDate = initDate;
        this.mEndDate = endDate;
        this.mMedication = medication;
        this.mHoursList = hoursList;
    }
}
