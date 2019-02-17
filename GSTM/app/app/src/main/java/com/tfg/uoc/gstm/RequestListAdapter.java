package com.tfg.uoc.gstm;

import android.widget.BaseAdapter;
import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import java.util.ArrayList;
import com.tfg.uoc.gstm.models.Request;

public class RequestListAdapter extends BaseAdapter {
    protected Activity activity;
    protected ArrayList<Request> items;

    public RequestListAdapter (Activity activity, ArrayList<Request> items) {
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

    public void addAll(ArrayList<Request> request) {
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
            v = inf.inflate(R.layout.user_request_pending, null);
        }

        Request request = items.get(position);

        TextView email = (TextView) v.findViewById(R.id.email);
        email.setText(request.userRequest);

        TextView status = (TextView) v.findViewById(R.id.status_request);
        if(request.status == 0)
            status.setText("nueva");
        else
            status.setText("pendiente");

        TextView name = (TextView) v.findViewById(R.id.name);
        name.setText(((RequestActivity)activity).getUserNameByEmail(request.userRequest));

        TextView relationship = (TextView) v.findViewById(R.id.relationship);
        relationship.setText(request.relationship);

        Button mSupervisionAccept = (Button) v.findViewById(R.id.accept);
        mSupervisionAccept.setTag(position);
        mSupervisionAccept.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((RequestActivity)activity).notifyChangeRequestStatus(items.get(position), 2, true);
            }
        });

        Button mSupervisionCancel = (Button) v.findViewById(R.id.cancel);
        mSupervisionCancel.setTag(position);
        mSupervisionCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int position = (Integer)v.getTag();
                ((RequestActivity)activity).notifyChangeRequestStatus(items.get(position), 3, true);
            }
        });

        return v;
    }
}
