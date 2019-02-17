package com.tfg.uoc.gstm;

import android.support.test.espresso.action.ViewActions;
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
import static android.support.test.espresso.action.ViewActions.replaceText;
import static android.support.test.espresso.action.ViewActions.typeText;
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.hasErrorText;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class NewMedicalDateTest {
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
    public ActivityTestRule<NewDateActivity> activityRule =
            new ActivityTestRule<>(NewDateActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() {
        onView(withId(R.id.user)).check(matches(isDisplayed()));
        onView(withId(R.id.date)).check(matches(isDisplayed()));
        onView(withId(R.id.hour)).check(matches(isDisplayed()));
        onView(withId(R.id.place)).check(matches(isDisplayed()));
        onView(withId(R.id.observations)).check(matches(isDisplayed()));
        onView(withId(R.id.hours_to_confirmed)).check(matches(isDisplayed()));
        onView(withId(R.id.accept)).check(matches(isDisplayed()));
        onView(withId(R.id.cancel)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_DataWrong() {
        onView(withId(R.id.date)).perform(typeText("66/15/2018"));
        onView(withId(R.id.hour)).perform(typeText("44:66"));
        onView(withId(R.id.place)).perform(typeText(""));
        onView(withId(R.id.hours_to_confirmed)).perform(replaceText(""));

        onView(withId(R.id.hours_to_confirmed)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.date)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.bad_date_format))));
        onView(withId(R.id.hour)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.bad_hour_format))));
        onView(withId(R.id.place)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
        onView(withId(R.id.hours_to_confirmed)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
    }

    @Test
    public void test3_DataSuccess() {
        onView(withId(R.id.date)).perform(typeText("12/06/2018")); //Cambiar por el día siguiente al actual
        onView(withId(R.id.hour)).perform(typeText("04:15"));
        onView(withId(R.id.place)).perform(typeText("Hospital"));
        onView(withId(R.id.hours_to_confirmed)).perform(replaceText("2"));

        onView(withId(R.id.hours_to_confirmed)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
    }
}
