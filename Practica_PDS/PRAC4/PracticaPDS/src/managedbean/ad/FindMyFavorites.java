package managedbean.ad;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.ad.AdFacadeRemote;
import jpa.AdJPA;
import jpa.FavoritesJPA;
import jpa.UserJPA;
import managedbean.systemAdministration.Login;


/**
 * Managed Bean FindMyFavorites
 */
@ManagedBean(name = "findMyFavorites")
@SessionScoped
public class FindMyFavorites implements Serializable {

	/**
	 * 
	 */

	private static final long serialVersionUID = 1L;
	@EJB
	private AdFacadeRemote findMyFavoritesRemote;
	// stores Ad,User instances
	protected AdJPA dataAd;
	protected UserJPA dataUser;
	// stores the screen number where the user is
	private int screen = 0;
	// stores the total number of instances of AdJPA
	protected int numberAds = 0;
	protected Collection<AdJPA> adFavoritesListView;
	private Collection<AdJPA> adsList;
	protected Collection<FavoritesJPA> favoritesList;
	private Collection<UserJPA> usersList;

	protected Collection<FavoritesJPA> favoritesListView;

	public FindMyFavorites() throws Exception {
		this.adFavList();
		//setAdFavoritesListView();
	}
	
	@SuppressWarnings("unchecked")
	public void adFavList() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		findMyFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		screen = 0;
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		
		dataUser = findMyFavoritesRemote.getUser(loginBean.getEmail());
		adsList = (Collection<AdJPA>) findMyFavoritesRemote.findMyFavorites(dataUser.getId());//Opcion1,2,3
		//adsList = (Collection<AdJPA>) findMyFavoritesRemote.findMyFavorites(dataUser); //Opcion4
		usersList = (Collection<UserJPA>) findMyFavoritesRemote.listAllUsers();
	}

	/*public void setAdFavoritesListView() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		findMyFavoritesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		//adsList = (Collection<AdJPA>) findMyFavoritesRemote.listsAllAds();
		//usersList = (Collection<UserJPA>) findMyFavoritesRemote.listAllUsers();
		favoritesList = (Collection<FavoritesJPA>) findMyFavoritesRemote.listFavoritos();
		dataUser = findMyFavoritesRemote.getUser(userId);
		adFavoritesListView = (Collection<AdJPA>) findMyFavoritesRemote.findMyFavorites(dataUser.getId());
	}*/

	public Collection<AdJPA> getAdFavoritesListView() throws Exception {
		this.adFavList();
		int n = 0;
		adFavoritesListView = new ArrayList<AdJPA>();
		for (Iterator<AdJPA> iter2 = adsList.iterator(); iter2.hasNext();)
		{
			AdJPA l = (AdJPA) iter2.next();
			if (n >= screen*10 && n < (screen*10+10)){
				AdJPA ad = findMyFavoritesRemote.showAd(l.getId());
				adFavoritesListView.add(ad);
			}	
			n +=1;
		}
		this.numberAds = n;
		return adFavoritesListView;
	}

	public UserJPA getDataUser() {
		return dataUser;
	}

	/*public int getUserId() {
		return userId;
	}

	public void setUserId(int userId) throws Exception {
		this.userId = userId;
		// setDataAd();
	}

	public void setDataUser(int userId) throws Exception {
		this.userId = userId;
		// setDataAd();
	}*/
	
	/**
	 * allows forward or backward in user screens
	 */
	public void nextScreen()
	{
		if (((screen+1)*10 < favoritesList.size()))
		{
			screen +=1;
		}
	}
	
	public void previousScreen()
	{
		if ((screen > 0))
		{
			screen -=1;
		}
	}
	
	/**
	 * Method used for Facelet to call adFavoritesListView Facelet
	 * @return Facelet name
	 * @throws Exception
	 */
	public String listFavorites() throws Exception
	{
		
		this.adFavoritesListView = getAdFavoritesListView();
		return "adFavoritesListView";
	}
	
}
