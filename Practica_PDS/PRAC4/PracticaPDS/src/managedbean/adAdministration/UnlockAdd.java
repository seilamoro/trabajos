package managedbean.adAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.adAdministration.AdAdministrationFacadeRemote;
import jpa.LabelJPA;



/**
 * Managed Bean UnlockAdd
 */
@ManagedBean(name = "unlockAdd")
@SessionScoped
public class UnlockAdd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote unlockAddRemote;

	public UnlockAdd() throws Exception 
	{
		
	}
	
	public String unlockAd(int adId) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		unlockAddRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		unlockAddRemote.unlockAd(adId);
		
		return "showAdView?faces-redirect=true&idAd=" + adId;
	}
	
}
