package com.tfg.uoc.gstm;

import javax.mail.Authenticator;
import javax.mail.PasswordAuthentication;

//code for MyAuthenticator class

public class MyAuthenticator extends Authenticator
{
    public static final String EMAIL = "seila.uoc.tfg@gmail.com";
    public static final String PASSWORD = "Galatea96";

    @Override
    protected PasswordAuthentication getPasswordAuthentication()
    {
        return new PasswordAuthentication(EMAIL, PASSWORD);
    }
}
