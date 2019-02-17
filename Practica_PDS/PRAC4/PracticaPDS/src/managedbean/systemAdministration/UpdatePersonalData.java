package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;
import jpa.UserJPA;

@ManagedBean (name="updatepPersonalData")
@SessionScoped
public class UpdatePersonalData implements Serializable{

	private static final long serialVersionUID = 1L;
	@EJB
	private SystemAdministrationFacadeRemote updatePersonalDataRemote;
	
	@EJB
	private SystemAdministrationFacadeRemote getPersonalDataRemote;
	
	protected UserJPA dataUser;
	protected int id=0;
	
	public UpdatePersonalData() {
		// TODO Auto-generated constructor stub
	}
	
	public int getId(){
		return id;
	}
	
	public void setId(int id) throws Exception{
		this.id = id;
		getPersonalData();
	}
	
	public UserJPA getDataUser(){
		return dataUser;
	}
	
	public void updatePersonalData(int id, String nif, String name, String surname, String phone, String email, String pwd, int idLocation){
		updatePersonalDataRemote.updatePersonalData(id, nif, name, surname, phone, pwd, email, idLocation);
	}
	
	public void getPersonalData() throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		getPersonalDataRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		dataUser = (UserJPA) getPersonalDataRemote.getPersonalData(id);
		System.out.println(dataUser);
	}
}
