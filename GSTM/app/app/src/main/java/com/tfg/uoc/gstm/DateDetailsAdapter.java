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

public class DateDetailsAdapter extends BaseAdapter {
    protected Activity activity;
    protected ArrayList<String> items;

    public DateDetailsAdapter (Activity activity, ArrayList<String> items) {
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

    public void addAll(ArrayList<String> items) {
        for (int i = 0; i < items.size(); i++) {
            items.add(items.get(i));
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
            v = inf.inflate(R.layout.date_details_list, null);
        }

        String item = items.get(position);
        String[] parts = item.split("###");

        TextView time = (TextView) v.findViewById(R.id.time);
        time.setText(parts[0]);

        TextView name = (TextView) v.findViewById(R.id.description);
        name.setText(parts[1]);

        ImageView mAddMedicationEdit = (ImageView) v.findViewById(R.id.medication_edit);
        mAddMedicationEdit.setTag(position);
        mAddMedicationEdit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((DateDetailsActivity)activity).notifyEditElement(position);
            }
        });

        return v;
    }
}
