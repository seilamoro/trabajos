package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean(name ="labelDelete")
@SessionScoped
public class DeleteLabel implements Serializable{

	
	private static final long serialVersionUID = 1L;
	
	@EJB
	private SystemAdministrationFacadeRemote deleteLabelRemote;
	
	public DeleteLabel() throws Exception{
		
	}

	@SuppressWarnings("unchecked")
	public void deleteLabel(int id) throws Exception{
		Properties props = System.getProperties();
	    Context ctx = new InitialContext(props);
	    System.out.println(id);
	    deleteLabelRemote =  (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
	    deleteLabelRemote.deleteLabel(id);
		}

}
