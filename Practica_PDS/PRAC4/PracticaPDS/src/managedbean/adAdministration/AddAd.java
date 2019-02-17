package managedbean.adAdministration;

import java.io.Serializable;
import java.util.Date;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.ManagedProperty;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.adAdministration.AdAdministrationFacadeRemote;
import jpa.AdJPA;
import managedbean.systemAdministration.Login;


/**
 * Managed Bean AddAd
 */
@ManagedBean(name = "addAd")
@SessionScoped
public class AddAd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote addAdRemote;

	private AdJPA ad;
	protected String title;
	protected String description;
	protected Date submitted_date;
	protected String picture;
	protected boolean status;
	protected boolean locked;
	protected float price;
	
	@ManagedProperty(value="#{login}")
	private Login login;
	
	public AddAd() throws Exception 
	{
		ad = new AdJPA();
	}

	public AdJPA getAd() {
		return ad;
	}

	public void setAd(AdJPA ad) {
		this.ad = ad;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public Date getSubmitted_date() {
		return submitted_date;
	}

	public void setSubmitted_date(Date submitted_date) {
		this.submitted_date = submitted_date;
	}

	public String getPicture() {
		return picture;
	}

	public void setPicture(String picture) {
		this.picture = picture;
	}

	public boolean isStatus() {
		return status;
	}

	public void setStatus(boolean status) {
		this.status = status;
	}

	public boolean isLocked() {
		return locked;
	}

	public void setLocked(boolean locked) {
		this.locked = locked;
	}

	public float getPrice() {
		return price;
	}

	public void setPrice(float price) {
		this.price = price;
	}
	
	public Login getLogin() {
		return login;
	}

	public void setLogin(Login login) {
		this.login = login;
	}

	public String addAd() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addAdRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		addAdRemote.addAd(this.title, this.description, new Date(), this.price, this.login.getEmail());

		return "adListView";
	}
	
}
