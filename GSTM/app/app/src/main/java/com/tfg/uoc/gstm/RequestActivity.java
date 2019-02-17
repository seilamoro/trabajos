package com.tfg.uoc.gstm;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ListView;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.Request;
import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.User;

import java.util.ArrayList;

public class RequestActivity extends AppCompatActivity {
    private RequestListAdapter mResultAdapter;
    private TextView mNotRequests;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_request);

        Globals g = (Globals)getApplication();
        ArrayList<Request> allRequest = g.getCurrentUserRequest();

        ListView resultsView = findViewById(R.id.results);
        mResultAdapter = new RequestListAdapter(this, allRequest);
        resultsView.setAdapter(mResultAdapter);

        mNotRequests = findViewById(R.id.not_requests);
        if(mResultAdapter.getCount() != 0) {
            mNotRequests.setVisibility(View.GONE);
        }
    }

    @Override
    protected void onStop() {
        super.onStop();  // Always call the superclass method first

        Globals g = (Globals)getApplication();
        if(g.getCurrentUser() == null) {
            return;
        }
        ArrayList<Request> allRequest = g.getCurrentUserRequest();
        for(int i = 0; i < allRequest.size(); i++) {
            Request request = allRequest.get(i);
            if(request.status == 0) {
                notifyChangeRequestStatus(request, 1, false); //Update new request to pending
            }
        }
    }

    public void notifyChangeRequestStatus(Request request, int status, boolean updateAdapter) {
        Globals g = (Globals)getApplication();
        if(g.existEmail(request.userRequest)) {
            request.status = status;
            g.updateRequests(request);

            if(updateAdapter) {
                Handler handler=new Handler();
                Runnable r=new Runnable() {
                    public void run() {
                        mResultAdapter.clear();
                        mResultAdapter.notifyDataSetChanged();

                        Globals g = (Globals)getApplication();
                        ArrayList<Request> allRequest = g.getCurrentUserRequest();
                        if(allRequest.size() != 0) {
                            mResultAdapter.addAll(allRequest);
                            mResultAdapter.notifyDataSetChanged();
                            mNotRequests.setVisibility(View.GONE);
                        } else {
                            mNotRequests.setVisibility(View.VISIBLE);
                        }
                    }
                };
                handler.postDelayed(r, 300); //Wait for update data
            }

            if(status == 2) {
                addNewSupervision(request);
            }
        }
        else {
            AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
            dialogo1.setTitle(getString(R.string.alarm_title));
            dialogo1.setMessage(getString(R.string.user_not_exist));
            dialogo1.setCancelable(false);
            dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialogo1, int id) {
                    mResultAdapter.clear();
                    mResultAdapter.notifyDataSetChanged();

                    Globals g = (Globals)getApplication();
                    ArrayList<Request> allRequest = g.getCurrentUserRequest();
                    if(allRequest.size() != 0) {
                        mResultAdapter.addAll(allRequest);
                        mResultAdapter.notifyDataSetChanged();
                        mNotRequests.setVisibility(View.GONE);
                    } else {
                        mNotRequests.setVisibility(View.VISIBLE);
                    }
                }
            });

            dialogo1.show();
        }

    }

    public String getUserNameByEmail(String email) {
        Globals g = (Globals)getApplication();
        User user = g.getUser(email);
        return user.name + " " + user.surname;
    }

    public void addNewSupervision(Request request) {
        Globals g = (Globals)getApplication();
        int id = g.getLastSupervisionsId() + 1;

        Supervision supervision = new Supervision(id, request.relationship, request.level, request.userRequest, request.user);
        g.addNewSupervision(supervision);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch(item.getItemId()) {
            case R.id.action_calendar:
                Handler handler=new Handler();
                Runnable r=new Runnable() {
                    public void run() {
                        Intent calendar = new Intent(getApplicationContext(), CalendarActivity.class);
                        startActivity(calendar);
                    }
                };
                handler.postDelayed(r, 300);
                break;
            case R.id.action_profile :
                Intent profile = new Intent(getApplicationContext(), UserDataActivity.class);
                startActivity(profile);
                break;
            case R.id.action_supervisions :
                Intent supervisions = new Intent(getApplicationContext(), SupervisionsActivity.class);
                startActivity(supervisions);
                break;
            case R.id.action_requests :
                Intent requests = new Intent(getApplicationContext(), RequestActivity.class);
                startActivity(requests);
                break;
            case R.id.action_supervisions_request :
                Intent supervisions_request = new Intent(getApplicationContext(), SupervisionsRequestActivity.class);
                startActivity(supervisions_request);
                break;
            case R.id.action_close_session :
                Globals g = (Globals)getApplication();
                g.closeSession();
                Intent login = new Intent(getApplicationContext(), LoginActivity.class);
                login.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                startActivity(login);
                break;
        }
        return super.onOptionsItemSelected(item);
    }
}
