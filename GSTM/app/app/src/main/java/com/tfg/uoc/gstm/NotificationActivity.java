package com.tfg.uoc.gstm;

import android.content.Context;
import android.os.Build;
import android.os.Handler;
import android.os.VibrationEffect;
import android.os.Vibrator;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.tfg.uoc.gstm.models.MedicalDate;
import com.tfg.uoc.gstm.models.Medication;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class NotificationActivity extends AppCompatActivity {
    private int mType;
    private String mNames;
    private String mIds;
    private String mTimes;
    private int mCount;

    private TextView mHourView;
    private TextView mMessageView;
    private TextView mNotesTitleView;
    private EditText mNotesView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_notification);

        Window window = this.getWindow();
        window.addFlags(WindowManager.LayoutParams.FLAG_DISMISS_KEYGUARD);
        window.addFlags(WindowManager.LayoutParams.FLAG_SHOW_WHEN_LOCKED);
        window.addFlags(WindowManager.LayoutParams.FLAG_TURN_SCREEN_ON);

        Globals g = (Globals)getApplication();
        Bundle b = getIntent().getExtras();
        if(b != null) {
            mType = b.getInt("type");
            mNames = b.getString("name");
            mIds = b.getString("id");
            mTimes = b.getString("time");
            mCount = b.getInt("Num");
        }

        DateFormat timeFormat = new SimpleDateFormat("HH:mm");
        Date currentDate = new Date();
        String time = timeFormat.format(currentDate);

        mHourView = findViewById(R.id.hour);
        mMessageView = findViewById(R.id.message);
        mNotesTitleView = findViewById(R.id.note_title);
        mNotesView = findViewById(R.id.notes);

        mHourView.setText(time);

        if(mType != 4) {
            mNotesTitleView.setVisibility(View.INVISIBLE);
            mNotesView.setVisibility(View.INVISIBLE);
        }

        String[] namesList = mNames.split("###");
        String[] timesList = mTimes.split("###");

        String mens = g.getCurrentUser().email + " ";
        if (mType == 1) { //Notification medications
            mens = mens + getString(R.string.notify_medications_title) + "\r\n";
            for (int i = 0; i < mCount; i++) {
                mens = mens + "  - " + namesList[i] + "\r\n";
            }
        }
        else if (mType == 2) { //Notification medical dates
            mens = mens + getString(R.string.notify_medical_date_title) + "\r\n";
            for (int i = 0; i < mCount; i++) {
                mens = mens + "  - " + namesList[i] + " -- " + timesList[i] + "\r\n";
            }
        }
        if (mType == 3) { //Confirm medications
            mens = mens + getString(R.string.confirm_medications_title) + "\r\n";
            for (int i = 0; i < mCount; i++) {
                mens = mens + "  - " + namesList[i] + " -- " + timesList[i] + "\r\n";
            }
        }
        if (mType == 4) { //Confirm medical dates
            mens = mens + getString(R.string.confirm_medical_date_title) + "\r\n";
            for (int i = 0; i < mCount; i++) {
                mens = mens + "  - " + namesList[i] + " -- " + timesList[i] + "\r\n";
            }
        }
        mMessageView.setText(mens);

        Button mAcceptButton = findViewById(R.id.accept);
        mAcceptButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                acceptAlert();
            }
        });

        Handler handler=new Handler();
        Runnable r=new Runnable() {
            public void run() {
                finish();
            }
        };
        handler.postDelayed(r, 60000); //this activity only show one minute

        Vibrator v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
        // Vibrate for 500 milliseconds
        //deprecated in API 26
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            v.vibrate(VibrationEffect.createOneShot(500, VibrationEffect.DEFAULT_AMPLITUDE));
        }else v.vibrate(500);
    }

    public void acceptAlert() {
        String[] idsList = mIds.split("###");
        Globals g = (Globals)getApplication();
        if (mType == 3) { //Confirm medication
            for (int i = 0; i < mCount; i++) {
                Medication medication = g.getMedicationById(Integer.parseInt(idsList[i]));
                medication.confirmMedication();
                g.updateMedication(medication);
            }

        }
        else if (mType == 4) { //Confirm medical date, same comments for all
            //Check data

            mNotesView.setError(null); // Reset errors.
            String notes = mNotesView.getText().toString(); // Store value

            boolean cancel = false;
            View focusView = null;

            if (TextUtils.isEmpty(notes)) {
                mNotesView.setError(getString(R.string.error_field_required));
                focusView = mNotesView;
                cancel = true;
            }

            if (cancel) {
                // There was an error; don't attempt and focus the first form field with an error.
                focusView.requestFocus();
            } else {
                for (int i = 0; i < mCount; i++) {
                    MedicalDate medicalDate = g.getMedicalDateById(Integer.parseInt(idsList[i]));
                    medicalDate.confirmMedicalDate(notes);
                    g.updateMedicalDate(medicalDate);

                }
            }
        }

        finish(); //For warning medication and medical date only close activity
    }
}
