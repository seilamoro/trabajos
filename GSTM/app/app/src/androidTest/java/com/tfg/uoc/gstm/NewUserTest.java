package com.tfg.uoc.gstm;

import android.support.test.espresso.ViewInteraction;
import android.support.test.espresso.action.ViewActions;
import android.support.test.rule.ActivityTestRule;
import android.support.test.runner.AndroidJUnit4;
import android.test.suitebuilder.annotation.SmallTest;

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
public class NewUserTest {
    @Rule
    public ActivityTestRule<NewUserActivity> activityRule =
            new ActivityTestRule<>(NewUserActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_DataWrong() {
        Globals g = (Globals)activityRule.getActivity().getApplication();
        g.readDataBase();

        onView(withId(R.id.email)).perform(typeText("emailtest"));
        onView(withId(R.id.name)).perform(typeText(""));
        onView(withId(R.id.surname)).perform(typeText(""));
        onView(withId(R.id.password)).perform(typeText(""));
        onView(withId(R.id.confirPassword)).perform(typeText(""));

        onView(withId(R.id.confirPassword)).perform(ViewActions.closeSoftKeyboard());
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        onView(withId(R.id.accept)).perform(click());

        onView(withId(R.id.email)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_invalid_email))));
        onView(withId(R.id.name)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
        onView(withId(R.id.surname)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
        onView(withId(R.id.password)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_invalid_password))));
        onView(withId(R.id.confirPassword)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.password_confirm_error))));
    }

    @Test
    public void test2_DataSuccess() {
        Globals g = (Globals)activityRule.getActivity().getApplication();
        g.readDataBase();

        onView(withId(R.id.email)).perform(typeText("email1000@test.com")); //Cambiar en cada prueba
        onView(withId(R.id.name)).perform(typeText("test"));
        onView(withId(R.id.surname)).perform(typeText("test"));
        onView(withId(R.id.password)).perform(typeText("testtest"));
        onView(withId(R.id.confirPassword)).perform(typeText("testtest"));

        onView(withId(R.id.confirPassword)).perform(ViewActions.closeSoftKeyboard());
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        onView(withId(R.id.accept)).perform(click());

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        onView(withText(R.string.new_user_create)).check(matches(isDisplayed()));
    }

    @Test
    public void test3_Cancel() {
        onView(withId(R.id.email)).perform(typeText("seila.uoc.tfg@gmail.com"));

        onView(withId(R.id.confirPassword)).perform(ViewActions.closeSoftKeyboard());
        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        onView(withId(R.id.cancel)).perform(click());
        onView(withId(R.id.email_sign_in_button)).check(matches(isDisplayed()));
    }
}
