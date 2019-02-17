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
import static android.support.test.espresso.action.ViewActions.typeText;
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.hasErrorText;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;
import static android.support.test.espresso.matcher.ViewMatchers.withText;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class NewTreatmentTest {
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
    public ActivityTestRule<NewTreatmentActivity> activityRule =
            new ActivityTestRule<>(NewTreatmentActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() {
        onView(withId(R.id.user)).check(matches(isDisplayed()));
        onView(withId(R.id.name)).check(matches(isDisplayed()));
        onView(withId(R.id.addMedication)).check(matches(isDisplayed()));
        onView(withId(R.id.medications)).check(matches(isDisplayed()));
        onView(withId(R.id.accept)).check(matches(isDisplayed()));
        onView(withId(R.id.cancel)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_DataWrong() {
        onView(withId(R.id.name)).perform(typeText(""));
        onView(withId(R.id.name)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.name)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.medicines_error))));
    }

    @Test
    public void test3_DataWrong1() {
        onView(withId(R.id.name)).perform(typeText("test"));
        onView(withId(R.id.name)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.name)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.medicines_error))));
    }

    @Test
    public void test4_DataWrong2() {
        onView(withId(R.id.name)).perform(typeText("test"));
        onView(withId(R.id.name)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.addMedication)).perform(click());

        onView(withId(R.id.med_name)).perform(typeText(""));
        onView(withId(R.id.init_date)).perform(typeText("90/15/2018"));
        onView(withId(R.id.end_date)).perform(typeText("96/16/2018"));
        onView(withId(R.id.hour0)).perform(typeText("85:86"));

        onView(withId(R.id.med_name)).perform(ViewActions.closeSoftKeyboard());
        onView(withText("Aceptar")).perform(click());

        onView(withId(R.id.med_name)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
        onView(withId(R.id.init_date)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.bad_date_format))));
        onView(withId(R.id.end_date)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.bad_date_format))));
        onView(withId(R.id.hour0)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.bad_hour_format))));
    }

    @Test
    public void test5_DataSuccess() {
        onView(withId(R.id.name)).perform(typeText("test"));
        onView(withId(R.id.name)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.addMedication)).perform(click());

        onView(withId(R.id.med_name)).perform(typeText("prueba"));
        onView(withId(R.id.init_date)).perform(typeText("12/06/2018")); //día superior actual
        onView(withId(R.id.end_date)).perform(typeText("12/06/2018"));
        onView(withId(R.id.hour0)).perform(typeText("12:00"));

        onView(withId(R.id.med_name)).perform(ViewActions.closeSoftKeyboard());
        onView(withText("Aceptar")).perform(click());

        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
    }
}
