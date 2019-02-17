package com.tfg.uoc.gstm;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

public class ReceiverBoot extends BroadcastReceiver {
    @Override
    public void onReceive(Context context, Intent intent) {
        // Lanzar Servicio
        Intent serviceIntent = new Intent();
        serviceIntent.setAction("com.tfg.uoc.gstm.AlertsService");
        context.startService(serviceIntent);
    }
}