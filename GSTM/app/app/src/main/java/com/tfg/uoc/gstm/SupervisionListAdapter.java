package com.tfg.uoc.gstm;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.Supervision;

import java.util.ArrayList;

public class SupervisionListAdapter extends BaseAdapter {
    protected Activity activity;
    protected ArrayList<Supervision> items;
    protected int mMode; //0 for Supervisions, 1 for Supervisors

    public SupervisionListAdapter (Activity activity, ArrayList<Supervision> items, int mode) {
        this.activity = activity;
        this.items = items;
        this.mMode = mode;
    }

    @Override
    public int getCount() {
        return items.size();
    }

    public void clear() {
        items.clear();
    }

    public void addAll(ArrayList<Supervision> request) {
        for (int i = 0; i < request.size(); i++) {
            items.add(request.get(i));
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
            v = inf.inflate(R.layout.supervision_list, null);
        }

        Supervision supervision = items.get(position);

        TextView name = v.findViewById(R.id.name);
        String textName = "";
        if(mMode == 0) {
            textName = supervision.supervised;
        } else {
            textName = supervision.supervisor;
        }
        textName = textName + " - " + ((SupervisionsActivity)activity).getUserNameByEmail(textName);
        name.setText(textName);


        ImageView mSupervisionRemove = v.findViewById(R.id.delete_supervision);
        mSupervisionRemove.setTag(position);
        mSupervisionRemove.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((SupervisionsActivity)activity).notifyDeleteSupervision(items.get(position));
            }
        });

        return v;
    }
}
