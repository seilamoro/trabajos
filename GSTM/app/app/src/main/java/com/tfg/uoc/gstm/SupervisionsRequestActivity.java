package com.tfg.uoc.gstm;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Spinner;

import com.tfg.uoc.gstm.models.Request;
import com.tfg.uoc.gstm.models.User;

import java.text.DateFormat;
import java.util.ArrayList;
import java.util.Date;

public class SupervisionsRequestActivity extends AppCompatActivity {
    private EditText mEmailView;
    private ListView mResultsView;
    private Button mSupervisionsRequestButton;
    private EditText mRelationshipView;
    private ArrayAdapter<String> mResultAdapter;
    private ArrayList<User> mResultUsersList;
    private int mSelectUserPos;
    private Spinner mNotificationLevel;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_supervisions_request);

        mEmailView = findViewById(R.id.email);
        mResultsView = findViewById(R.id.results);
        mRelationshipView = findViewById(R.id.relationship);
        mNotificationLevel = findViewById(R.id.notification_level);

        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        mNotificationLevel.setAdapter(adapter);
        adapter.add(getString(R.string.alarms_notification));
        adapter.add(getString(R.string.all_notification));

        mResultAdapter = new ArrayAdapter<String>(this,android.R.layout.simple_list_item_1);
        mResultsView.setAdapter(mResultAdapter);
        mSelectUserPos = -1;

        mResultsView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                 mSelectUserPos = position;
            }
        });

        Button mFindUserButton = findViewById(R.id.find_user);
        mFindUserButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                findUsers();
            }
        });

        mSupervisionsRequestButton = findViewById(R.id.supervisions_request);
        mSupervisionsRequestButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendSupervisionsRequest();
            }
        });
        mSupervisionsRequestButton.setEnabled(false);
    }

    public void findUsers() {
        //Check data

        // Reset errors.
        mEmailView.setError(null);
        // Store values.
        String email = mEmailView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            mEmailView.setError(getString(R.string.error_field_required));
            focusView = mEmailView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt and focus the first form field with an error.
            focusView.requestFocus();
        } else {
            mSelectUserPos = -1;
            mResultsView.requestFocus();

            mResultUsersList = new ArrayList<User>();
            mResultAdapter.clear();
            mResultAdapter.notifyDataSetChanged();

            Globals g = (Globals)getApplication();
            ArrayList<User> userList = g.getFindUsers(email);

            mSupervisionsRequestButton.setEnabled(false);
            if(userList.size() != 0) {
                mSupervisionsRequestButton.setEnabled(true);
            }

            for(int i = 0; i < userList.size(); i++) {
                User user = userList.get(i);
                if(!user.email.equals(g.getCurrentUser().email)) { //not add the current user in this list
                    mResultAdapter.add(user.email + " - " + user.name + " " + user.surname);
                    mResultUsersList.add(user);
                }
            }
            mResultAdapter.notifyDataSetChanged();
        }
    }

    public void sendSupervisionsRequest() {
        //Check data

        // Reset errors.
        mRelationshipView.setError(null);

        // Store values.
        User user = null;
        String relationship = mRelationshipView.getText().toString();
        int notificationLevel = mNotificationLevel.getSelectedItemPosition();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid user selected
        if(mSelectUserPos == -1) {
            //mensaje cuenta creada y volver a login
            AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
            dialogo1.setTitle(getString(R.string.supervisions_request));
            dialogo1.setMessage("Debe seleccionar un usuario.");
            dialogo1.setCancelable(false);
            dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialogo1, int id) {

                }
            });
            dialogo1.show();

            focusView = mResultsView;
            cancel = true;
        }
        else {
            user = mResultUsersList.get(mSelectUserPos);
            Globals g = (Globals)getApplication();
            if(!g.existEmail(user.email)) {
                android.app.AlertDialog.Builder dialogo1 = new android.app.AlertDialog.Builder(this);
                dialogo1.setTitle(getString(R.string.alarm_title));
                dialogo1.setMessage(getString(R.string.user_not_exist));
                dialogo1.setCancelable(false);
                dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialogo1, int id) {
                        mResultUsersList = new ArrayList<User>();
                        mResultAdapter.clear();
                        mResultAdapter.notifyDataSetChanged();
                    }
                });
                dialogo1.show();
                cancel = true;
            }
            else {
                //You can not send requests again, to the same user if you already have another one that is not canceled
                int chechRequest = g.existPendingOrAcceptSupervisionRequest(user.email);

                if (chechRequest == 1) {
                    AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
                    dialogo1.setTitle(getString(R.string.supervisions_request));
                    dialogo1.setMessage(getString(R.string.supervisions_request_pending_error));
                    dialogo1.setCancelable(false);
                    dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialogo1, int id) {
                        }
                    });
                    dialogo1.show();
                    cancel = true;
                }

                int supervionLevel = g.getSupervisionLevel(user.email);
                if (supervionLevel != -1) {
                    AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
                    dialogo1.setTitle(getString(R.string.supervisions_request));
                    dialogo1.setMessage(getString(R.string.supervisions_request_accept_error));
                    dialogo1.setCancelable(false);
                    dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialogo1, int id) {
                        }
                    });
                    dialogo1.show();
                    cancel = true;
                }
            }
        }
        // Check for a valid relationship
        if (TextUtils.isEmpty(relationship)) {
            mRelationshipView.setError(getString(R.string.error_field_required));
            focusView = mRelationshipView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt and focus the first form field with an error.
            if(focusView != null)
                focusView.requestFocus();
        } else {
            String currentDate = DateFormat.getDateTimeInstance().format(new Date());
            addNewSupervisionsRequest(currentDate, 0, user.email, relationship, notificationLevel);
        }
    }

    public void addNewSupervisionsRequest(String date, int status, String user, String relationship, int level) {
        Globals g = (Globals)getApplication();
        User currentUser = g.getCurrentUser();
        int id = g.getLastRequestsId() + 1;

        Request request = new Request(id, date, status, user, currentUser.email, relationship, level);
        g.addNewRequest(request);

        goCalendarActivity();
    }

    public void goCalendarActivity() {
        finish();
        Intent intent = new Intent(this, CalendarActivity.class);
        startActivity(intent);
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
