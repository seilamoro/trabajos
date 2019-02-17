package managedbean.communication;

import java.io.Serializable;
import java.util.Collection;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.communication.CommunicationFacadeRemote;
import jpa.MessageJPA;

@ManagedBean(name = "showUserComments")
@SessionScoped
public class ShowUserComments implements Serializable {
	private static final long serialVersionUID = -4016067809651809991L;
	@EJB
	private CommunicationFacadeRemote facadeRemote;

	private Collection<MessageJPA> msgs;

	public Collection<MessageJPA> getMsgs() {
		return msgs;
	}

	public void setMsgs(Collection<MessageJPA> msgs) {
		this.msgs = msgs;
	}

	public String nif;

	public String getNif() {
		return nif;
	}

	public void setNif(String nif) {
		this.nif = nif;
	}

	public ShowUserComments() throws Exception {
	}

	public ShowUserComments(String nif) throws Exception {
		this.nif = nif;
	}

	public Collection<MessageJPA> getMessages(String nif) throws Exception {
		this.nif = nif;
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		this.msgs = (Collection<MessageJPA>) facadeRemote.showUserComments(nif);
		return this.msgs;
	}

}
