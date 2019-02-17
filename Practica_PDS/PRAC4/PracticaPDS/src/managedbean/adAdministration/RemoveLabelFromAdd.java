package managedbean.adAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.adAdministration.AdAdministrationFacadeRemote;

/**
 * Managed Bean RemoveLabelFromAdd
 */
@ManagedBean(name = "removeLabelFromAdd")
@SessionScoped
public class RemoveLabelFromAdd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote removeLabelFromAddRemote;

	public RemoveLabelFromAdd() throws Exception 
	{
		
	}
	
	public String removeLabelFromAd(int adId, int labelId) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeLabelFromAddRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		removeLabelFromAddRemote.removeLabelFromAd(adId, labelId);
		
		return "adLabelToAdView?faces-redirect=true&idAdLabel=" + adId;
	}
	
}
