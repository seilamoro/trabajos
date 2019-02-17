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
 * Managed Bean LockAdd
 */
@ManagedBean(name = "lockAdd")
@SessionScoped
public class LockAdd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote lockAddRemote;

	public LockAdd() throws Exception 
	{
		
	}
	
	public String lockAd(int adId) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		lockAddRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		lockAddRemote.lockAd(adId);

		return "showAdView?faces-redirect=true&idAd=" + adId;
	}
	
}
