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
import static android.support.test.espresso.action.ViewActions.typeText;
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.hasErrorText;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class RememberPassTest {
    @Rule
    public ActivityTestRule<RememberPassActivity> activityRule =
            new ActivityTestRule<>(RememberPassActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_UserWrong() {
        Globals g = (Globals)activityRule.getActivity().getApplication();
        g.readDataBase();

        onView(withId(R.id.email)).perform(typeText("email@dominio.com"));
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.email)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.email_not_found))));
    }

    @Test
    public void test2_UserSuccess() {
        Globals g = (Globals)activityRule.getActivity().getApplication();
        g.readDataBase();

        onView(withId(R.id.email)).perform(typeText("seila.uoc.tfg@gmail.com"));
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.password)).check(matches(isDisplayed()));
    }

    @Test
    public void test3_Cancel() {
        onView(withId(R.id.email)).perform(typeText("seila.uoc.tfg@gmail.com"));
        onView(withId(R.id.cancel)).perform(click());
        onView(withId(R.id.password)).check(matches(isDisplayed()));
    }
}
