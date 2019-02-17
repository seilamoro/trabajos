package managedbean.ad;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Map;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.faces.model.SelectItem;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.ad.AdFacadeRemote;
import jpa.AdJPA;
import jpa.UserJPA;
import managedbean.systemAdministration.Login;


/**
 * Managed Bean RemoveAdFromFavorites
 */
@ManagedBean(name = "removeAdFromFavorites")
@SessionScoped
public class RemoveAdFromFavorites implements Serializable{
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	@EJB
	private AdFacadeRemote removeAdFromFavoritesRemote;
	//stores Ad,User instances
	protected AdJPA dataAd;
	protected UserJPA dataUser;
	//stores userId,adId 
	protected int adId;
	protected int userId;
	protected Collection<AdJPA> adFavoritesListView;

	public RemoveAdFromFavorites() throws Exception{
		// TODO Auto-generated constructor stub
		//this.adFavoritesListView = adFavoritesListView();
		//setDataAd();
	}
	
	@SuppressWarnings("unchecked")
	public String removeAdFromFavorites() throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeAdFromFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = removeAdFromFavoritesRemote.getUser(loginBean.getEmail());
		dataAd = removeAdFromFavoritesRemote.showAd(adId);
		adFavoritesListView = (Collection<AdJPA>) removeAdFromFavoritesRemote.findMyFavorites(dataUser.getId());
		if (removeAdFromFavoritesRemote.removeAdFromFavorites(dataUser.getId(), dataAd.getId())){
			adFavoritesListView.remove(dataAd);			
		}
		
		return "adFavoriteListView";
	}

	public String removeAdFromMyFavorites(int ad_id) throws Exception{
		this.adId = ad_id;
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeAdFromFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = removeAdFromFavoritesRemote.getUser(loginBean.getEmail());
		dataAd = removeAdFromFavoritesRemote.showAd(adId);
		adFavoritesListView = (Collection<AdJPA>) removeAdFromFavoritesRemote.findMyFavorites(dataUser.getId());
		if (removeAdFromFavoritesRemote.removeAdFromFavorites(dataUser.getId(), dataAd.getId())){
			adFavoritesListView.remove(dataAd);		
			return "showAdView?faces-redirect=true&idAd=" + dataAd.getId();	
		}
		
		return "adListView";
	}
	
	public AdJPA getdataAd()
	{
		return dataAd;
	}

	public UserJPA getdataUser()
	{
		return dataUser;
	}
	
	public int getIdAd()
	{
		return adId;
	}

	public void setIdAd(int adId) throws Exception
	{
		this.adId = adId;
		setDataAd();
	}
	
	@SuppressWarnings("unchecked")
	public Collection<AdJPA> adFavoritesListView() throws Exception
	{
		adFavoritesListView = new ArrayList<AdJPA>();
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeAdFromFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = removeAdFromFavoritesRemote.getUser(loginBean.getEmail());
		adFavoritesListView = (Collection<AdJPA>) removeAdFromFavoritesRemote.findMyFavorites(dataUser.getId());
		return adFavoritesListView;
	}
	
	public void setDataAd() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		removeAdFromFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataAd = removeAdFromFavoritesRemote.showAd(adId);
		dataUser = removeAdFromFavoritesRemote.getUser(loginBean.getEmail());
		if (dataUser!= null){
			if (removeAdFromFavoritesRemote.removeAdFromFavorites(dataUser.getId(), dataAd.getId())){
				adFavoritesListView.remove(dataAd);
			};
			
		}		
	}
}
