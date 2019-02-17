package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean (name="removeComment")
@SessionScoped
public class RemoveComment implements Serializable{

	private static final long serialVersionUID = 1L;
	
	@EJB
	private SystemAdministrationFacadeRemote removeCommentRemote;
	public RemoveComment() throws Exception{
		// TODO Auto-generated constructor stub
	}
	
	@SuppressWarnings("unchecked")
	public void removeComment(int id) throws Exception{
		Properties props = System.getProperties();
	    Context ctx = new InitialContext(props);
	    System.out.println(id);
	    removeCommentRemote =  (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
	    removeCommentRemote.removeComment(id);
		}

}
