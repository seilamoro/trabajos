package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean (name="registerUser")
@SessionScoped

public class RegisterUser implements Serializable{
	
	private static final long serialVersionUID = 1L;
	private String nif;
	private String name;
	private String surname;
	private String phone;
	private String password;
	private String email;
	private int idProvince;
	private int idLocation;
	
	@EJB
	private SystemAdministrationFacadeRemote registerUser;
	
	public RegisterUser() {
		
	}

	public String getNif() {
		return nif;
	}

	public void setNif(String nif) {
		this.nif = nif;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getSurname() {
		return surname;
	}

	public void setSurname(String surname) {
		this.surname = surname;
	}

	public String getPhone() {
		return phone;
	}

	public void setPhone(String phone) {
		this.phone = phone;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}
	
	@SuppressWarnings("unchecked")
	public String registerUser() throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		registerUser = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		registerUser.registerUser(nif, name, surname, phone, password, email, idLocation);
		return "loginView";
	}

	public int getIdProvince() {
		return idProvince;
	}

	public void setIdProvince(int idProvince) {
		this.idProvince = idProvince;
	}

	public int getIdLocation() {
		return idLocation;
	}

	public void setIdLocation(int idLocation) {
		this.idLocation = idLocation;
	}
}
