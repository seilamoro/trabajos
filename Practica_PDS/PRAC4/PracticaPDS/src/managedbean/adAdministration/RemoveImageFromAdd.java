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
 * Managed Bean RemoveImageFromAdd
 */
@ManagedBean(name = "removeImageFromAdd")
@SessionScoped
public class RemoveImageFromAdd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote removeImageFromAddRemote;

	public RemoveImageFromAdd() throws Exception 
	{
		
	}
	
	public String removeImageFromAd(int adId) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeImageFromAddRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		removeImageFromAddRemote.removeImageFromAd(adId);
		
		return "showAdView?faces-redirect=true&idAd=" + adId;

	}
	
}
