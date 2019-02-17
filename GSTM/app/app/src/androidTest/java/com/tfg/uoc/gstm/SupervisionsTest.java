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
public class SupervisionsTest {
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
    public ActivityTestRule<SupervisionsActivity> activityRule =
            new ActivityTestRule<>(SupervisionsActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() { //Con alguna solicitud
        onView(withId(R.id.my_supervisions)).check(matches(isDisplayed()));
        onView(withId(R.id.my_supervisors)).check(matches(isDisplayed()));
        onView(withId(R.id.supervisions_request)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_Request() {
        onView(withId(R.id.supervisions_request)).perform(click());
        onView(withId(R.id.find_user)).check(matches(isDisplayed()));
    }
}
