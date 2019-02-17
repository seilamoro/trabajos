package com.tfg.uoc.gstm;

import android.content.Context;
import android.content.Intent;
import android.support.test.InstrumentationRegistry;
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

public class DateDetailsTest {
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
    public ActivityTestRule<DateDetailsActivity> activityRule =
            new ActivityTestRule<>(DateDetailsActivity.class, true,     // initialTouchMode
                    false);   // launchActivity

    @Test
    public void test1_Components() {
        Context targetContext = InstrumentationRegistry.getInstrumentation()
                .getTargetContext();
        Intent intent = new Intent(targetContext, DateDetailsActivity.class);
        intent.putExtra("date", "06/06/2018");
        intent.putExtra("user", "test@test.com");

        activityRule.launchActivity(intent);

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        onView(withId(R.id.user)).check(matches(isDisplayed()));
        onView(withId(R.id.date)).check(matches(isDisplayed()));
        onView(withId(R.id.date_details)).check(matches(isDisplayed()));
        onView(withId(R.id.new_treatment)).check(matches(isDisplayed()));
        onView(withId(R.id.new_medical_date)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_NewTreatment() {
        Context targetContext = InstrumentationRegistry.getInstrumentation()
                .getTargetContext();
        Intent intent = new Intent(targetContext, DateDetailsActivity.class);
        intent.putExtra("date", "06/06/2018");
        intent.putExtra("user", "test@test.com");

        activityRule.launchActivity(intent);

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        onView(withId(R.id.new_treatment)).perform(click());
        onView(withId(R.id.medications)).check(matches(isDisplayed()));
    }

    @Test
    public void test3_NewMedicalDate() {
        Context targetContext = InstrumentationRegistry.getInstrumentation()
                .getTargetContext();
        Intent intent = new Intent(targetContext, DateDetailsActivity.class);
        intent.putExtra("date", "06/06/2018");
        intent.putExtra("user", "test@test.com");

        activityRule.launchActivity(intent);

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        onView(withId(R.id.new_medical_date)).perform(click());
        onView(withId(R.id.observations)).check(matches(isDisplayed()));
    }
}
