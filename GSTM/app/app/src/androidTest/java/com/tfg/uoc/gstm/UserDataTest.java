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
public class UserDataTest {
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
    public ActivityTestRule<UserDataActivity> activityRule =
            new ActivityTestRule<>(UserDataActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() {
        onView(withId(R.id.email)).check(matches(isDisplayed()));
        onView(withId(R.id.name)).check(matches(isDisplayed()));
        onView(withId(R.id.surname)).check(matches(isDisplayed()));
        onView(withId(R.id.change_pass)).check(matches(isDisplayed()));
        onView(withId(R.id.password)).check(matches(isDisplayed()));
        onView(withId(R.id.confirPassword)).check(matches(isDisplayed()));
        onView(withId(R.id.remove_user_button)).check(matches(isDisplayed()));
        onView(withId(R.id.accept)).check(matches(isDisplayed()));
        onView(withId(R.id.cancel)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_ChangeDataWrong() {
        onView(withId(R.id.name)).perform(replaceText(""));
        onView(withId(R.id.surname)).perform(replaceText(""));
        onView(withId(R.id.surname)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.name)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
        onView(withId(R.id.surname)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
    }

    @Test
    public void test3_ChangeDataSuccess() {
        onView(withId(R.id.name)).perform(typeText("_1"));
        onView(withId(R.id.surname)).perform(typeText("_1"));
        onView(withId(R.id.surname)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.accept)).perform(click());
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
    }
}
