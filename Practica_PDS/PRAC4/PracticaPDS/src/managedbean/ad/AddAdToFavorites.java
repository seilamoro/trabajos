package managedbean.ad;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
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

//import ejb.UserFacadeRemote;
//import jpa.Label;

/**
 * Managed Bean AddAdToFavorites
 */
@ManagedBean(name = "addAdToFavorites")
@SessionScoped
public class AddAdToFavorites implements Serializable {

	/**
	 * 
	 */

	private static final long serialVersionUID = 1L;
	@EJB
	private AdFacadeRemote addAdToFavoritesRemote;
	// stores Ad,User instances
	protected AdJPA dataAd;
	protected UserJPA dataUser;
	// stores adId
	protected int adId = 1;

	protected Collection<AdJPA> adFavoritesListView;

	public AddAdToFavorites() throws Exception {

	}

	@SuppressWarnings("unchecked")
	public String addAdToFavorites() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addAdToFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = addAdToFavoritesRemote.getUser(loginBean.getEmail());
		dataAd = addAdToFavoritesRemote.showAd(adId);
		adFavoritesListView = (Collection<AdJPA>) addAdToFavoritesRemote.findMyFavorites(dataUser.getId());
		if (addAdToFavoritesRemote.addAdToFavorites(dataUser.getId(), dataAd.getId())) {
			adFavoritesListView.add(dataAd);
		}

		return "listAllAds";
	}

	@SuppressWarnings("rawtypes")
	public String addAdToMyFavorites(int ad_id) throws Exception {
		this.adId = ad_id;
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addAdToFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = addAdToFavoritesRemote.getUser(loginBean.getEmail());
		dataAd = addAdToFavoritesRemote.showAd(adId);
		if (addAdToFavoritesRemote.addAdToFavorites(dataUser.getId(), dataAd.getId())) {
			return "showAdView?faces-redirect=true&idAd=" + dataAd.getId();
		}

		return "adListView";
	}

	public AdJPA getDataAd() {
		return dataAd;
	}

	public void setDataAd(AdJPA dataAd) {
		this.dataAd = dataAd;
	}

	public UserJPA getDataUser() {
		return dataUser;
	}

	public void setDataUser(UserJPA dataUser) {
		this.dataUser = dataUser;
	}

	public int getAdId() {
		return adId;
	}

	public void setAdId(int adId) {
		this.adId = adId;
	}

	public Collection<AdJPA> getAdFavoritesListView() {
		return adFavoritesListView;
	}

	public void setAdFavoritesListView(Collection<AdJPA> adFavoritesListView) {
		this.adFavoritesListView = adFavoritesListView;
	}
}
