package managedbean.communication;

import java.io.Serializable;
import java.util.Collection;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;

import ejb.ad.AdFacadeRemote;
import ejb.communication.CommunicationFacadeRemote;
import jpa.MessageJPA;
import jpa.UserJPA;
import jpa.UserRatingJPA;

@ManagedBean(name = "showUserRating")
@SessionScoped
public class ShowUserRating implements Serializable {
	private static final long serialVersionUID = -4016067809651809991L;
	@EJB
	private CommunicationFacadeRemote facadeRemote;

	public UserJPA user;

	public int user_id;

	public Collection<UserRatingJPA> userRating;

	public ShowUserRating() throws Exception {
	}

	public int getUser_id() {
		return user_id;
	}

	public void setUser_id(int user_id) throws NamingException {
		this.user_id = user_id;
		setUserId();
	}

	public Collection<UserRatingJPA> getUserRating() throws Exception {
		return getRating();
	}

	public void setUserRating(Collection<UserRatingJPA> userRating) {
		this.userRating = userRating;
	}

	public UserJPA getUser() {
		return user;
	}

	public void setUser(UserJPA user) {
		this.user = user;
	}

	public void setUserId() throws NamingException {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote facadeRemote = (AdFacadeRemote) ctx
				.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		this.user = facadeRemote.getUser(user_id);
	}

	public Collection<UserRatingJPA> getRating() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx
				.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		this.userRating = (Collection<UserRatingJPA>) facadeRemote.showUserRating(user.getNif());
		return this.userRating;
	}

}
