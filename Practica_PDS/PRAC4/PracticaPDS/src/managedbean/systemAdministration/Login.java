package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.*;
import javax.ejb.EJB;
import javax.faces.application.FacesMessage;
import javax.faces.bean.*;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;
import jpa.UserJPA;

import javax.faces.context.*;

@ManagedBean(name = "login")
@SessionScoped

public class Login implements Serializable {
	private static final long serialVersionUID = 1L;

	private int id;
	private String nif;
	private String email;
	private String pwd;
	private int role;
	private boolean logged = false;
	@EJB
	private SystemAdministrationFacadeRemote loginRemote;

	public void login() {

		// TODO Auto-generated constructor stub
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPwd() {
		return pwd;
	}

	public void setPwd(String pwd) {
		this.pwd = pwd;
	}

	public String doLogin(String email, String pwd) throws Exception {
		System.out.println(email + pwd);
		UserJPA v = validation(email, pwd);
		if (v != null) {
			role = v.getRole();
			this.id = v.getId();
			this.nif = v.getNif();
			setLogged(true);
			return "indexView";
		} else {
			FacesContext.getCurrentInstance().addMessage(null, new FacesMessage(FacesMessage.SEVERITY_WARN, "Incorrect Username or Password. Please enter correct username and Password", "Please enter correct username and Password"));
			return "loginView";
		}
	}

	public String registerUser() {
		return "RegisterUserView";
	}

	public void logout() {
		FacesContext.getCurrentInstance().getExternalContext().invalidateSession();
		FacesContext.getCurrentInstance().getApplication().getNavigationHandler().handleNavigation(FacesContext.getCurrentInstance(), null, "/loginView.xhtml");
		setLogged(false);
	}

	public UserJPA validation(String email, String pwd) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		loginRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		UserJPA u = loginRemote.login(email, pwd);
		if (u != null){
			this.id = u.getId();
			this.nif = u.getNif();
			return u;
		}else{
			return u=null;
		}
	}

	public int getRole() {
		return role;
	}

	public boolean isLogged() {
		return logged;
	}

	public void setLogged(boolean logged) {
		this.logged = logged;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getNif() {
		return nif;
	}

	public void setNif(String nif) {
		this.nif = nif;
	}

}
