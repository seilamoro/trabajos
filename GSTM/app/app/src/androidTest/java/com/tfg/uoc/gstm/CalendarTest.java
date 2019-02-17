package com.tfg.uoc.gstm;

import android.support.test.rule.ActivityTestRule;
import android.support.test.runner.AndroidJUnit4;

import org.junit.BeforeClass;
import org.junit.ClassRule;
import org.junit.FixMethodOrder;
import org.junit.Rule;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.junit.runners.MethodSorters;

import static android.support.test.espresso.Espresso.onView;
import static android.support.test.espresso.action.ViewActions.click;
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class CalendarTest {
    @ClassRule
    public static ActivityTestRule<LoginActivity> activityRule1 =
            new ActivityTestRule<>(LoginActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @BeforeClass
    public static void before() {
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        Globals g = (Globals)activityRule1.getActivity().getApplication();
        g.readDataBase();
        g.setCurrentUser("test@test.com");

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }

    @Rule
    public ActivityTestRule<CalendarActivity> activityRule =
            new ActivityTestRule<>(CalendarActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() {
        onView(withId(R.id.user)).check(matches(isDisplayed()));
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
        onView(withId(R.id.today)).check(matches(isDisplayed()));
        onView(withId(R.id.new_treatment)).check(matches(isDisplayed()));
        onView(withId(R.id.new_medical_date)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_NewTreatment() {
        onView(withId(R.id.new_treatment)).perform(click());
        onView(withId(R.id.medications)).check(matches(isDisplayed()));
    }

    @Test
    public void test3_NewMedicalDate() {
        onView(withId(R.id.new_medical_date)).perform(click());
        onView(withId(R.id.observations)).check(matches(isDisplayed()));
    }
}
