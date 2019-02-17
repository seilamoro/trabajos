package managedbean.communication;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.naming.NamingException;

import ejb.ad.AdFacadeRemote;
import ejb.communication.CommunicationFacadeRemote;
import jpa.AdJPA;
import jpa.UserJPA;

@ManagedBean(name = "rateUser")
@SessionScoped
public class RateUser implements Serializable {
	private static final long serialVersionUID = -4016067809651809991L;
	@EJB
	private CommunicationFacadeRemote facadeRemote;

	public int ad_id;

	public AdJPA dataAd;

	public int user_id;
	public UserJPA user;

	public String text;
	public int rate;

	public RateUser() {

	}

	public int getUser_id() {
		return user_id;
	}

	public void setUser_id(int user_id) {
		this.user_id = user_id;
	}

	public UserJPA getUser() {
		return user;
	}

	public void setUser(UserJPA user) {
		this.user = user;
	}

	public AdJPA getDataAd() {
		return dataAd;
	}

	public void setDataAd(AdJPA dataAd) {
		this.dataAd = dataAd;
	}

	public String getText() {
		return text;
	}

	public void setText(String text) {
		this.text = text;
	}

	public int getRate() {
		return rate;
	}

	public void setRate(int rate) {
		this.rate = rate;
	}

	public int getAd_id() {
		return ad_id;
	}

	public void setAd_id(int ad_id) throws Exception {
		this.ad_id = ad_id;
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote facadeRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		this.dataAd = facadeRemote.showAd(ad_id);
	}

	public void setUserId() throws NamingException {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote facadeRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		this.user = facadeRemote.getUser(user_id);
	}


	public String rate(String nifFromUser, String nifToUser, String comment, int rate) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		if (facadeRemote.rateUser(nifFromUser, nifToUser, comment, rate)) {
			return "showAdView?faces-redirect=true&idAd=" + this.ad_id;
		}

		return "indexView";
	}

}
