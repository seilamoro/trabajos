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
import static android.support.test.espresso.assertion.ViewAssertions.matches;
import static android.support.test.espresso.matcher.ViewMatchers.isDisplayed;
import static android.support.test.espresso.matcher.ViewMatchers.withId;

@RunWith(AndroidJUnit4.class)
@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class NotificationTest {
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
    public ActivityTestRule<NotificationActivity> activityRule =
            new ActivityTestRule<>(NotificationActivity.class, true,     // initialTouchMode
                    false);   // launchActivity

    @Test
    public void test1_Components() {
        Context targetContext = InstrumentationRegistry.getInstrumentation()
                .getTargetContext();
        Intent intent = new Intent(targetContext, NotificationActivity.class);
        intent.putExtra("name", "Aspirina" + "###");
        intent.putExtra("type", 1);
        intent.putExtra("time", "10:00" + "###");
        intent.putExtra("id", "0" + "###");
        intent.putExtra("Num", 1);

        activityRule.launchActivity(intent);

        try {
            Thread.sleep(1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        onView(withId(R.id.hour)).check(matches(isDisplayed()));
        onView(withId(R.id.message)).check(matches(isDisplayed()));
        onView(withId(R.id.accept)).check(matches(isDisplayed()));
    }
}
