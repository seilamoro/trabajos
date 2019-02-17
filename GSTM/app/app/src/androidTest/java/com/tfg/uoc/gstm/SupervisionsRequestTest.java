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

import static android.support.test.espresso.Espresso.onData;
import static android.support.test.espresso.Espresso.onView;
import static android.support.test.espresso.action.ViewActions.click;
import static android.support.test.espresso.action.ViewActions.typeText;
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.hasErrorText;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;
import static android.support.test.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.CoreMatchers.anything;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class SupervisionsRequestTest {
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
    public ActivityTestRule<SupervisionsRequestActivity> activityRule =
            new ActivityTestRule<>(SupervisionsRequestActivity.class, true,     // initialTouchMode
                    true);   // launchActivity

    @Test
    public void test1_Components() {
        onView(withId(R.id.email)).check(matches(isDisplayed()));
        onView(withId(R.id.find_user)).check(matches(isDisplayed()));
        onView(withId(R.id.results)).check(matches(isDisplayed()));
        onView(withId(R.id.relationship)).check(matches(isDisplayed()));
        onView(withId(R.id.notification_level)).check(matches(isDisplayed()));
        onView(withId(R.id.supervisions_request)).check(matches(isDisplayed()));
    }

    @Test
    public void test2_DataWrong() {
        onView(withId(R.id.email)).perform(typeText(""));
        onView(withId(R.id.email)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.find_user)).perform(click());
        onView(withId(R.id.email)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
    }

    @Test
    public void test3_DataWrong1() {
        onView(withId(R.id.email)).perform(typeText("a@a.a")); //usuario al que se le pueda enviar una solicitud
        onView(withId(R.id.email)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.find_user)).perform(click());

        onView(withId(R.id.supervisions_request)).perform(click());
        onView(withText("Debe seleccionar un usuario.")).check(matches(isDisplayed()));
    }

    @Test
    public void test4_DataWrong1() {
        onView(withId(R.id.email)).perform(typeText("a@a.a")); //usuario al que se le pueda enviar una solicitud
        onView(withId(R.id.email)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.find_user)).perform(click());
        onData(anything()).inAdapterView(withId(R.id.results)).atPosition(0).perform(click());

        onView(withId(R.id.supervisions_request)).perform(click());
        onView(withId(R.id.relationship)).check(
                matches(hasErrorText(activityRule.getActivity().getString(R.string.error_field_required))));
    }

    @Test
    public void test5_DataSuccess() {
        onView(withId(R.id.email)).perform(typeText("a@a.a")); //usuario al que se le pueda enviar una solicitud
        onView(withId(R.id.email)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.find_user)).perform(click());
        onData(anything()).inAdapterView(withId(R.id.results)).atPosition(0).perform(click());
        onView(withId(R.id.relationship)).perform(typeText("prueba"));
        onView(withId(R.id.relationship)).perform(ViewActions.closeSoftKeyboard());
        onView(withId(R.id.supervisions_request)).perform(click());
        onView(withId(R.id.calendar_view)).check(matches(isDisplayed()));
    }
}
