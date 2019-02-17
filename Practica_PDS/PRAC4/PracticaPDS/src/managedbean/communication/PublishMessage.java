package managedbean.communication;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.ManagedProperty;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.ad.AdFacadeRemote;
import ejb.communication.CommunicationFacadeRemote;
import jpa.AdJPA;
import jpa.MessageJPA;
import managedbean.ad.ShowAd;

@ManagedBean(name = "publishMessage")
@SessionScoped
public class PublishMessage implements Serializable {

	/**
	 * 
	 */
	private static final long serialVersionUID = -4016067809651809991L;
	@EJB
	private CommunicationFacadeRemote facadeRemote;
	@EJB
	private AdFacadeRemote AdRemote;

	private MessageJPA msg;

	// stores Label instance
	public AdJPA dataAd;

	public int getAdId() {
		if(dataAd == null)
			return 0;
		else
			return dataAd.getId();
	}

	public AdJPA getDataAd()
	{
		return dataAd;
	}
	
	public MessageJPA getMessage() {
		return msg;
	}
	
	public void setAdId(int adId) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		this.dataAd = (AdJPA) AdRemote.showAd(adId);
	}

	public PublishMessage() throws Exception {
		msg = new MessageJPA();
	}

	public PublishMessage(int ad_id) throws Exception {
		setAdId(ad_id);
		msg = new MessageJPA();
	}

	
//	@ManagedProperty(value="#{adShow}")
//	private ShowAd adShow;
	   
	public String publish(int ad_id, String nif, String title, String text) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");

		if (facadeRemote.publishMessage(ad_id, nif, title, text)) {
//			ShowAd adShow = new ShowAd();
////			adShow = new ShowAd();
//			adShow.setAdId(ad_id);
//			adShow.setDataAd();
			return "showAdView?faces-redirect=true&idAd=" + ad_id;
		}

		return "indexView";
	}

}
