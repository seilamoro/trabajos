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
 * Managed Bean MarkAdAsSold
 */
@ManagedBean(name = "markAdAsSold")
@SessionScoped
public class MarkAdAsSold implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote markAdAsSoldRemote;

	public MarkAdAsSold() throws Exception 
	{
		
	}
	
	public String markAdAsSold(int adId) throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		markAdAsSoldRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		markAdAsSoldRemote.markAdAsSold(adId);
		
		return "adListView";
	}
	
}
