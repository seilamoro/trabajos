package com.tfg.uoc.gstm;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import java.util.ArrayList;

public class MedicationListAdapter extends BaseAdapter {
    protected Activity activity;
    protected ArrayList<AddMedications> items;

    public MedicationListAdapter (Activity activity, ArrayList<AddMedications> items) {
        this.activity = activity;
        this.items = items;
    }

    @Override
    public int getCount() {
        return items.size();
    }

    public void clear() {
        items.clear();
    }

    public void addAll(ArrayList<AddMedications> medications) {
        for (int i = 0; i < medications.size(); i++) {
            items.add(medications.get(i));
        }
    }

    @Override
    public Object getItem(int arg0) {
        return items.get(arg0);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View v = convertView;

        if (convertView == null) {
            LayoutInflater inf = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            v = inf.inflate(R.layout.medication_list, null);
        }

        AddMedications medication = items.get(position);

        TextView name = (TextView) v.findViewById(R.id.medication);
        name.setText(medication.mMedication);

        ImageView mAddMedicationEdit = (ImageView) v.findViewById(R.id.medication_edit);
        mAddMedicationEdit.setTag(position);
        mAddMedicationEdit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((NewTreatmentActivity)activity).notifyChangeAddMedication(position);
            }
        });

        ImageView mAddMedicationDelete = (ImageView) v.findViewById(R.id.medication_delete);
        mAddMedicationDelete.setTag(position);
        mAddMedicationDelete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((NewTreatmentActivity)activity).notifyDeleteAddMedication(position);
            }
        });

        return v;
    }
}
