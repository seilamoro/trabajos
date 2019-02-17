package managedbean.adAdministration;

import java.io.ByteArrayOutputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.Serializable;
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



/**
 * Managed Bean AddImageToAd
 */
@ManagedBean(name = "addImageToAd")
@SessionScoped
public class AddImageToAd implements Serializable{
	
	private static final long serialVersionUID = 1L;
	@EJB
	private AdAdministrationFacadeRemote addImageToAdRemote;
	
	@EJB
	private AdFacadeRemote showAdRemote;
	
	protected AdJPA dataAd;
	protected Part image;
	protected int adId;
	protected Locale locale;
	
	public AddImageToAd() throws Exception 
	{
		locale = new Locale(FacesContext.getCurrentInstance().getExternalContext().getRequestParameterMap().get("idAdImage"));
		this.adId = Integer.parseInt(locale.getLanguage());
		setDataAd();
	}
	
	public void setAdId(int adId) throws Exception
	{
		this.adId = adId;
		setDataAd();
	}
	
	public void setDataAd() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		showAdRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		dataAd = (AdJPA) showAdRemote.showAd(adId);
	}
	
	public int getAdId()
	{
		return adId;
	}
	
	public Part getImage() {
		return image;
	}
	
	public void setImage(Part image) {
		this.image = image;
	}
	
	public String addImageToAd() throws Exception{
		InputStream inputStream = image.getInputStream();  
		ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
		byte[] buffer = new byte[10240];
		for (int length = 0; (length = inputStream.read(buffer)) > 0;) 
			outputStream.write(buffer, 0, length);
		
		byte[] imageSend = outputStream.toByteArray();
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addImageToAdRemote = (AdAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdAdministrationFacadeBean!ejb.adAdministration.AdAdministrationFacadeRemote");
		addImageToAdRemote.addImageToAd(this.adId, imageSend);
		
		return "showAdView?faces-redirect=true&idAd=" + this.adId;
	}	

}
