package com.tfg.uoc.gstm;

import android.content.Context;
import android.os.Build;
import android.os.Handler;
import android.os.VibrationEffect;
import android.os.Vibrator;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.User;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class AlarmsActivity extends AppCompatActivity {
    private String mTypes; //0 alarms, 1 confirms
    private String mNames;
    private String mTimes;
    private String mUsers;
    private int mCount;

    private TextView mHourView;
    private TextView mMessageAlarmsView;
    private TextView mMessageConfirmView;
    private LinearLayout mAlarmsView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_alarms);

        Window window = this.getWindow();
        window.addFlags(WindowManager.LayoutParams.FLAG_DISMISS_KEYGUARD);
        window.addFlags(WindowManager.LayoutParams.FLAG_SHOW_WHEN_LOCKED);
        window.addFlags(WindowManager.LayoutParams.FLAG_TURN_SCREEN_ON);

        Globals g = (Globals)getApplication();
        Bundle b = getIntent().getExtras();
        if(b != null) {
            mTypes = b.getString("types");
            mNames = b.getString("name");
            mTimes = b.getString("time");
            mUsers = b.getString("users");
            mCount = b.getInt("Num");
        }

        DateFormat timeFormat = new SimpleDateFormat("HH:mm");
        Date currentDate = new Date();
        String time = timeFormat.format(currentDate);

        mHourView = findViewById(R.id.hour);
        mAlarmsView = findViewById(R.id.layoutAlarms);
        mMessageAlarmsView = findViewById(R.id.messageAlarm);
        mMessageConfirmView = findViewById(R.id.messageConfirm);
        mHourView.setText(time);

        String[] typesList = mTypes.split("###");
        String[] namesList = mNames.split("###");
        String[] timesList = mTimes.split("###");
        String[] usersList = mUsers.split("###");

        String mensAlarm = getString(R.string.alarm_mens) + "\r\n";
        String mensConfirm = getString(R.string.confirm_mens) + "\r\n";

        int countAlarms = 0;
        int countConfirm = 0;
        for (int i = 0; i < mCount; i++) {
            User user = g.getUser(usersList[i]);
            String mens = "  - " + user.name + " " + user.surname + " | " + namesList[i] + " | " + timesList[i] + "\r\n";
            if(typesList[i].equals("0")) {
                countAlarms++;
                mensAlarm = mensAlarm + mens;
            }
            else {
                countConfirm++;
                mensConfirm = mensConfirm + mens;
            }
        }

        mMessageAlarmsView.setText(mensAlarm);
        mMessageConfirmView.setText(mensConfirm);
        if(countAlarms == 0) {
            LinearLayout.LayoutParams params = (LinearLayout.LayoutParams) mAlarmsView.getLayoutParams();
            params.height = 0;
            mAlarmsView.setLayoutParams(params);
        }
        if(countConfirm == 0) {
            mMessageConfirmView.setVisibility(View.INVISIBLE);
        }

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

        Handler handler=new Handler();
        Runnable r=new Runnable() {
            public void run() {
                finish();
            }
        };
        handler.postDelayed(r, 60000*5); //this activity only show 5 minute

        Vibrator v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
        // Vibrate for 500 milliseconds
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            v.vibrate(VibrationEffect.createOneShot(500, VibrationEffect.DEFAULT_AMPLITUDE));
        }else{
            //deprecated in API 26
            v.vibrate(500);
        }
    }
}
