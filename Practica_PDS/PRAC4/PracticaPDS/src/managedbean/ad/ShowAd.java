package managedbean.ad;

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
import jpa.AdJPA;
import jpa.MessageJPA;



/**
 * Managed Bean ShowAd
 */
@ManagedBean(name = "adShow")
@SessionScoped
public class ShowAd implements Serializable{

	/**
	 * 
	 */
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdFacadeRemote showAdRemote;
	//stores Label instance
	protected AdJPA dataAd;
	//stores labelId 
	protected int adId = 1;
	
	
	public ShowAd()  throws Exception {
		setDataAd();
	}

	
	/**
	 * Get/set the id number and Ad instance
	 * @return Label Id
	 */
	
	public int getAdId()
	{
		return adId;
	}
	public void setAdId(int adId) throws Exception
	{
		this.adId = adId;
		setDataAd();
		//System.out.println(this.dataAd);
	}
	public AdJPA getDataAd()
	{
		return dataAd;
	}	
	public void setDataAd() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		showAdRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		dataAd = (AdJPA) showAdRemote.showAd(adId);
	}
	
	public boolean isAdFavorite(int user_id) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote afr = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		return afr.isAdFavorite(adId, user_id);
	}
	
	public Collection<MessageJPA> getMessages() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		CommunicationFacadeRemote facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		return facadeRemote.getMessages(this.adId);
		
	}
	
	public String getImage(int idAd) throws Exception {
		
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote afr = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		String imagen = afr.getImage(idAd);
		return imagen;
	}
}
