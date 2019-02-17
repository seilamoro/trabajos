package com.tfg.uoc.gstm;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.graphics.Color;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.content.Intent;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;

import com.stacktips.view.CalendarListener;
import com.stacktips.view.CustomCalendarView;
import com.stacktips.view.DayDecorator;
import com.stacktips.view.DayView;
import com.tfg.uoc.gstm.models.Supervision;
import com.tfg.uoc.gstm.models.User;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

/*
Referencia del control del calendario: https://github.com/npanigrahy/Custom-Calendar-View
 */
public class CalendarActivity extends AppCompatActivity {
    private CustomCalendarView calendarView;

    private ArrayList<String> mUserMedicalDate;
    private ArrayList<String> mUserMedication;

    private Spinner mUser;
    private ArrayList<String> mUsersEmails;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_calendar);

        mUser = findViewById(R.id.user);
        Globals g = (Globals)getApplication();
        changeUsersList();

        calendarView = findViewById(R.id.calendar_view);
        Calendar currentCalendar = Calendar.getInstance(Locale.getDefault());//Initialize calendar with date
        calendarView.setFirstDayOfWeek(Calendar.MONDAY);//Show Monday as first date of week
        calendarView.setShowOverflowDate(false);//Show/hide overflow days of a month
        calendarView.refreshCalendar(currentCalendar);//call refreshCalendar to update calendar the view

        calendarView.setCalendarListener(new CalendarListener() {
            @Override
            public void onDateSelected(Date date) {
                SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
                String dayString = formatter.format(date);
                openDateDetailsActivity(dayString);
            }

            @Override
            public void onMonthChanged(Date date) {
                Calendar currentCalendar = Calendar.getInstance();//Initialize calendar with date
                currentCalendar.setTime(date);
                calendarView.refreshCalendar(currentCalendar);

                String user = mUsersEmails.get(mUser.getSelectedItemPosition());
                showUserMonth(user);
            }
        });

        showUserMonth(g.getCurrentUser().email);

        Button mCurrentMonthButton = findViewById(R.id.today);
        mCurrentMonthButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Date date = new Date();
                Calendar currentCalendar = Calendar.getInstance();//Initialize calendar with date
                currentCalendar.setTime(date);
                calendarView.refreshCalendar(currentCalendar);

                String user = mUsersEmails.get(mUser.getSelectedItemPosition());
                showUserMonth(user);
            }
        });

        Button mNewTreatmentButton = findViewById(R.id.new_treatment);
        mNewTreatmentButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openNewTreatmentActivity();
            }
        });

        Button mNewMedicalDateButton = findViewById(R.id.new_medical_date);
        mNewMedicalDateButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openNewDateActivity();
            }
        });

        mUser.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parentView, View selectedItemView, int position, long id) {
                String user = mUsersEmails.get(position);
                showUserMonth(user);
            }

            @Override
            public void onNothingSelected(AdapterView<?> parentView) { }

        });
    }

    public void changeUsersList() {
        Globals g = (Globals)getApplication();
        ArrayList<Supervision> supervisions = g.getCurrentUserSupervisions();
        User currentUser = g.getCurrentUser();
        String[] items = new String[supervisions.size()+1];
        items[0] = currentUser.name + " " + currentUser.surname;

        mUsersEmails = new ArrayList<>();
        mUsersEmails.add(currentUser.email);
        for(int i = 0; i < supervisions.size(); i++) {
            Supervision supervision = supervisions.get(i);
            mUsersEmails.add(supervision.supervised);
            User user = g.getUser(supervision.supervised);
            items[i+1] = user.name + " " + user.surname;
        }
        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, items);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        mUser.setAdapter(adapter);
    }

    public void showUserMonth(String user) {
        Date date = calendarView.getCurrentCalendar().getTime();
        Globals g = (Globals)getApplication();

        if(g.existEmail(user)) {
            mUserMedicalDate = g.getUserMedicalDateByMonth(user, date.getMonth(), date.getYear());
            mUserMedication = g.getUserMedicationByMonth(user, date.getMonth(), date.getYear());
            updateCalendar();
        }
        else {
            AlertDialog.Builder dialogo1 = new AlertDialog.Builder(this);
            dialogo1.setTitle(getString(R.string.alarm_title));
            dialogo1.setMessage(getString(R.string.user_not_exist));
            dialogo1.setCancelable(false);
            dialogo1.setPositiveButton(getString(R.string.accept), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialogo1, int id) {
                    changeUsersList();
                }
            });

            dialogo1.show();
        }
    }

    public void openDateDetailsActivity (String date) {
        Intent intent = new Intent(this, DateDetailsActivity.class);
        int userId = mUser.getSelectedItemPosition();
        Bundle b = new Bundle();
        b.putString("date", date);
        b.putString("user", mUsersEmails.get(userId));
        intent.putExtras(b);
        startActivity(intent);
    }

    public void openNewTreatmentActivity() {
        Intent intent = new Intent(this, NewTreatmentActivity.class);
        Bundle b = new Bundle();
        b.putInt("key", -1); //for create
        intent.putExtras(b);
        startActivity(intent);
    }

    public void openNewDateActivity() {
        Intent intent = new Intent(this, NewDateActivity.class);
        Bundle b = new Bundle();
        b.putInt("key", -1); //for create
        intent.putExtras(b);
        startActivity(intent);
    }

    public void updateCalendar() {
        //adding calendar day decorators
        List<DayDecorator> decorators = new ArrayList<>();
        decorators.add(new DisabledColorDecorator());
        calendarView.setDecorators(decorators);
        calendarView.refreshCalendar(calendarView.getCurrentCalendar());
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

    private class DisabledColorDecorator implements DayDecorator {
        @Override
        public void decorate(DayView dayView) {
            SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
            String dayString = formatter.format(dayView.getDate());
            Date dayFind = null;
            try {
                dayFind = formatter.parse(dayString);
            } catch (ParseException e) { e.printStackTrace(); }

            boolean isMedicalDate = false;
            boolean isMedication = false;

            for(int i = 0; i < mUserMedicalDate.size(); i++) {
                Date date = null;
                try {
                    date = formatter.parse(mUserMedicalDate.get(i));
                } catch (ParseException e) { e.printStackTrace(); }
                if(dayFind !=  null && dayFind.equals(date)) {
                    isMedicalDate = true;
                }
            }

            for(int i = 0; i < mUserMedication.size(); i++) {
                Date date = null;
                try {
                    date = formatter.parse(mUserMedication.get(i));
                } catch (ParseException e) { e.printStackTrace(); }
                if(dayFind != null && dayFind.equals(date)) {
                    isMedication = true;
                }
            }

            if(isMedicalDate && isMedication) {
                int color = Color.parseColor("#3949AB");
                dayView.setBackgroundColor(color);
            }
            else if(isMedicalDate) {
                int color = Color.parseColor("#607D8B");
                dayView.setBackgroundColor(color);
            }
            else if(isMedication) {
                int color = Color.parseColor("#A1887F");
                dayView.setBackgroundColor(color);
            }

        }
    }
}