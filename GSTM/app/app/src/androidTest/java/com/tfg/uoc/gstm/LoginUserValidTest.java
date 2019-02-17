package com.tfg.uoc.gstm;

import android.support.test.rule.ActivityTestRule;
import android.support.test.runner.AndroidJUnit4;

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
public class LoginUserValidTest {
    @Rule
    public ActivityTestRule<LoginActivity> activityRule =
            new ActivityTestRule<>(LoginActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_LoginWrong() {
        onView(withId(R.id.email)).perform(typeText("email@dominio.com"));
        onView(withId(R.id.password)).perform(replaceText("contraseña"));
        onView(withId(R.id.email_sign_in_button)).perform(click());
        onView(withId(R.id.password)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_incorrect_password))));
    }

    @Test
    public void test2_OpenNewUser() {
        onView(withId(R.id.create_account_button)).perform(click());
        onView(withId(R.id.confirPassword)).check(matches(isDisplayed()));
    }

    @Test
    public void test3_RememberPass() {
        onView(withId(R.id.remember_pass_button)).perform(click());
        onView(withId(R.id.email)).check(matches(isDisplayed()));
    }

    @Test
    public void test4_LoginSuccess() {
        onView(withId(R.id.email)).perform(typeText("test@test.com"));
        onView(withId(R.id.password)).perform(replaceText("testtest"));
        onView(withId(R.id.email_sign_in_button)).perform(click());
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
    }
}
