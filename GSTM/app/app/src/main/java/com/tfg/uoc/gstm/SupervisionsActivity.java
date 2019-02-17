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
import android.widget.Button;
import android.widget.ListView;

import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.User;

import java.util.ArrayList;

public class SupervisionsActivity extends AppCompatActivity {
    private ListView mMySupervisionsView;
    private SupervisionListAdapter mMySupervisionsAdapter;
    private ListView mMySupervisorsView;
    private SupervisionListAdapter mMySupervisorsAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_supervisions);

        Globals g = (Globals)getApplication();
        ArrayList<Supervision> allSupervisions = g.getCurrentUserSupervisions();
        ArrayList<Supervision> allSupervisors = g.getCurrentUserSupervisors();

        mMySupervisionsView = findViewById(R.id.my_supervisions);
        mMySupervisionsAdapter = new SupervisionListAdapter(this, allSupervisions, 0);
        mMySupervisionsView.setAdapter(mMySupervisionsAdapter);

        mMySupervisorsView = findViewById(R.id.my_supervisors);
        mMySupervisorsAdapter = new SupervisionListAdapter(this, allSupervisors, 1);
        mMySupervisorsView.setAdapter(mMySupervisorsAdapter);

        Button mSupervisionsRequestButton = findViewById(R.id.supervisions_request);
        mSupervisionsRequestButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent supervisions_request = new Intent(getApplicationContext(), SupervisionsRequestActivity.class);
                startActivity(supervisions_request);
            }
        });
    }

    public void notifyDeleteSupervision(final Supervision supervision) {
        //Confirm message
        AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
        dialogo1.setTitle(getString(R.string.supervisions));
        dialogo1.setMessage("¿Desea eliminar la supervisión?");
        dialogo1.setCancelable(false);
        dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                Globals g = (Globals)getApplication();
                g.removeSupervision(supervision.id);

                Handler handler=new Handler();
                Runnable r=new Runnable() {
                    public void run() {
                        mMySupervisionsAdapter.clear();
                        mMySupervisionsAdapter.notifyDataSetChanged();

                        Globals g = (Globals)getApplication();
                        ArrayList<Supervision> allSupervisions = g.getCurrentUserSupervisions();
                        mMySupervisionsAdapter.addAll(allSupervisions);
                        mMySupervisionsAdapter.notifyDataSetChanged();

                        mMySupervisorsAdapter.clear();
                        mMySupervisorsAdapter.notifyDataSetChanged();

                        ArrayList<Supervision> allSupervisors = g.getCurrentUserSupervisors();
                        mMySupervisorsAdapter.addAll(allSupervisors);
                        mMySupervisorsAdapter.notifyDataSetChanged();
                    }
                };
                handler.postDelayed(r, 300); //Wait for update data

            }
        });
        dialogo1.setNegativeButton(getString(R.string.cancel), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialogo1, int id) {
                //close message
            }
        });

        dialogo1.show();
    }

    public String getUserNameByEmail(String email) {
        Globals g = (Globals)getApplication();
        User user = g.getUser(email);
        return user.name + " " + user.surname;
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
