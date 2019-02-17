package managedbean.adAdministration;

import java.io.Serializable;
import java.util.Collection;
import java.util.Locale;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.faces.context.FacesContext;
import javax.naming.Context;
import javax.naming.InitialContext;
import javax.servlet.http.Part;

import ejb.ad.AdFacadeRemote;
import ejb.adAdministration.AdAdministrationFacadeRemote;
import jpa.AdJPA;
import jpa.LabelJPA;



/**
 * Managed Bean AddLabelToAd
 */
@ManagedBean(name = "addLabelToAd")
@SessionScoped
public class AddLabelToAd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote addLabelToAdRemote;
	
	@EJB
	private AdFacadeRemote showAdRemote;
	
	protected AdJPA dataAd;
	protected Part image;
	protected int adId;
	protected Locale locale;
	
	public AddLabelToAd() throws Exception 
	{
		locale = new Locale(FacesContext.getCurrentInstance().getExternalContext().getRequestParameterMap().get("idAdLabel"));
		this.adId = Integer.parseInt(locale.getLanguage());
		setDataAd();
	}
	
	public void setAdId(int adId) throws Exception
	{
		this.adId = adId;
		setDataAd();
	}
	
	public int getAdId()
	{
		return adId;
	}
	
	public void setDataAd() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		showAdRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		dataAd = (AdJPA) showAdRemote.showAd(adId);
	}
	
	public Collection<LabelJPA> getAllLabels() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addLabelToAdRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		Collection<LabelJPA> labelList = addLabelToAdRemote.getAllLabels();

		return labelList;

	}
	
	public String addLabelToAd(int adId, LabelJPA label) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addLabelToAdRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		addLabelToAdRemote.addLabelToAd(adId, label);
		
		return "adLabelToAdView?faces-redirect=true&idAdLabel=" + label.getId();
	}
	
}
