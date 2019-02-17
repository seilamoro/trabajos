package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.application.FacesMessage;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.faces.context.FacesContext;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.persistence.PersistenceException;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean (name = "registerAdministrator")
@SessionScoped
public class RegisterAdministrator {
	private static final long serialVersionUID = 1L;

	@EJB
	private SystemAdministrationFacadeRemote registerAdministratorRemote;
	
	protected String email;
	protected String password;
	
	
	public RegisterAdministrator() throws Exception {
		this.password = "";
		this.email = "";
	}
	
	public String getEmail(){
		return email;
	}
	public void setEmail(String email){
		this.email = email;
	}
	
	public String getPassword(){
		return password;
	}
	public void setPassword(String password){
		this.password = password;
	}

	@SuppressWarnings("unchecked")
	public String registerAdministrator() throws Exception{
		try{
			Properties props = System.getProperties();
			Context ctx = new InitialContext(props);
			registerAdministratorRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
			boolean res =registerAdministratorRemote.registerAdministrator(password, email);
			this.password = ""; //clear previous data
			this.email = "";
			if(res)
				FacesContext.getCurrentInstance().addMessage(null, new FacesMessage(FacesMessage.SEVERITY_INFO, "Administrator registered correctly", "Administrator registered correctly"));
			else
				FacesContext.getCurrentInstance().addMessage(null, new FacesMessage(FacesMessage.SEVERITY_WARN, "Incorrect Email or Password. Please enter correct Email and Password", "Please enter correct Email and Password"));
			return "registerAdministratorView";
		} catch (Exception e){
			return "errorView";
		}
	}
}
