package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean(name = "removeMessage")
@SessionScoped
public class RemoveMessage {
	private static final long serialVersionUID = 1L;
	
	@EJB
	private SystemAdministrationFacadeRemote removeMessageRemote;
	
	public RemoveMessage() throws Exception{
		
	}

	@SuppressWarnings("unchecked")
	public String RemoveMessage(int messageId) throws Exception{
		try{
			Properties props = System.getProperties();
		    Context ctx = new InitialContext(props);
		    System.out.println(messageId);
		    removeMessageRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		    removeMessageRemote.removeMessage(messageId);
		    return "listMessagesView";
		} catch (Exception e){
			return "errorView";
		}
	}
}
