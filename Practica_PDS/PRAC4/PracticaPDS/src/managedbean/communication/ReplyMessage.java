package managedbean.communication;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.ad.AdFacadeRemote;
import ejb.communication.CommunicationFacadeRemote;
import jpa.AdJPA;
import jpa.MessageJPA;

@ManagedBean(name = "replyMessage")
@SessionScoped
public class ReplyMessage implements Serializable {
	private static final long serialVersionUID = -4016067809651809991L;
	@EJB
	private CommunicationFacadeRemote facadeRemote;
	@EJB
	private AdFacadeRemote AdRemote;

	private MessageJPA msg;
	public int message_id;

	public String title;
	public String text;
	
	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getText() {
		return text;
	}

	public void setText(String text) {
		this.text = text;
	}

	public int getMessage_id() {
		return message_id;
	}

	public void setMessage_id(int message_id) throws Exception {
		this.message_id = message_id;
		setId();
	}

	// stores Label instance
	public AdJPA dataAd;

	public int getAdId() {
		if (dataAd == null)
			return 0;
		else
			return dataAd.getId();
	}

	public AdJPA getDataAd() {
		return dataAd;
	}

	public MessageJPA getMessage() {
		return msg;
	}

	public ReplyMessage() throws Exception {
		msg = new MessageJPA();
	}

	public ReplyMessage(int message_id) throws Exception {
		this.message_id = message_id;
		setId();
	}

	public void setId() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		this.msg = (MessageJPA) facadeRemote.showMessage(message_id);
		int adId = this.msg.getAd_id().getId();
		AdRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		this.dataAd = (AdJPA) AdRemote.showAd(adId);
	}

	public String reply(int ad_id, int message_id, String nif, String title, String text) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		facadeRemote = (CommunicationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/CommunicationFacadeBean!ejb.communication.CommunicationFacadeRemote");
		if (facadeRemote.replyMessage(ad_id, message_id, nif, title, text)) {
			return "showAdView?faces-redirect=true&idAd=" + ad_id;
		}

		return "indexView";
	}

}
