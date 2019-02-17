package managedbean.ad;

import java.io.Serializable;
import java.sql.Time;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;
import java.util.Iterator;
import java.util.Map;
import java.util.Properties;

import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.ad.AdFacadeRemote;
import jpa.AdJPA;
import jpa.LabelJPA;
import jpa.UserJPA;
import managedbean.systemAdministration.Login;


/**
 * Managed Bean FindAds
 */
@ManagedBean(name = "findAds")
@SessionScoped
public class FindAds implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	/**
	 * 
	 */
	private AdFacadeRemote findAdsRemote;
	// stores AdJPA instance
	protected AdJPA dataAd;
	//stores UserJPA instance
	protected UserJPA dataUser;
	// stores adId
	protected int adId = 1;
	// stores all the instances of AdJPA
	private Collection<AdJPA> adsList;
	private Collection<UserJPA> usersList;
	// stores the screen number where the user is
	private int screen = 0;
	// stores ten or fewer AdJPA instances that the user can see on a screen
	protected Collection<AdJPA> adsListView;
	protected Collection<UserJPA> usersListView;
	protected Collection<LabelJPA> labelList;
	// stores the total number of instances of AdJPA
	protected int numberAds = 0;
	private int idLocation;
	private String title;
	private String description;
	private Date date;
	//private Time time;
	private float price;
	private String address;

	public FindAds() throws Exception {
		this.adList();
	}

	/**
	 * Get/set the id number and Ad instance
	 * 
	 * @return Label Id
	 */

	public int getAdId() {
		return adId;
	}

	public void setAdId(int adId) throws Exception {
		this.adId = adId;
		// setDataAd();
	}

	public AdJPA getDataAd() {
		return dataAd;
	}

	public String listLabels() throws Exception {
		adList();

		return "indexView";

	}

	@SuppressWarnings("unchecked")
	public void adList() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		screen = 0;
		findAdsRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		adsList = (Collection<AdJPA>) findAdsRemote.findAds(title, description, date, price, address);
		usersList = (Collection<UserJPA>) findAdsRemote.listAllUsers();
		adsListView = adsList;
	}
	
	/*@SuppressWarnings("unchecked")
	public void adList() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		screen = 0;
		findAdsRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
		Map sMap = tmpEc.getSessionMap();
		Login loginBean = (Login) sMap.get("login");
		dataUser = findAdsRemote.getUser(loginBean.getEmail());
		if (dataUser == null){
			adsList = (Collection<AdJPA>) findAdsRemote.findAds(title, description, date, price, address);
		}else{
			adsList = (Collection<AdJPA>) findAdsRemote.findAds(dataUser.getId(),title, description, date, price, address);
		}
		usersList = (Collection<UserJPA>) findAdsRemote.listAllUsers();
		adsListView = adsList;
	}*/

	public Collection<LabelJPA> getAdLabels(int adId) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote adf = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		AdJPA ad = adf.showAd(adId);
		labelList = new ArrayList<LabelJPA>();
		labelList.addAll(ad.getLabels());
		
		return labelList;

	}

	public boolean hasLabels(int adId) throws Exception {
		Collection<LabelJPA> labelList = getAdLabels(adId);

		if (labelList == null)
			return false;
		else {
			if (labelList.size() == 0)
				return false;
			else
				return true;
		}
	}

	public boolean isAdFavorite(int adId, int user_id) throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		AdFacadeRemote afr = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		return afr.isAdFavorite(adId, user_id);
	}

	public Collection<AdJPA> getAdsListView() throws Exception {
		adList();
		return adsList;
	}

	/**
	 * Method that returns an instance Collection of 10 or less AdJPA according
	 * screen where the user is.
	 * 
	 * @return Collection AdJPA
	 */
	public Collection<UserJPA> getUsersListView() {
		int n = 0;
		usersListView = new ArrayList<UserJPA>();
		for (Iterator<UserJPA> iter2 = usersList.iterator(); iter2.hasNext();) {
			UserJPA lbl2 = (UserJPA) iter2.next();
			if (n >= screen * 10 && n < (screen * 10 + 10)) {
				this.usersListView.add(lbl2);
			}
			n += 1;
		}
		this.numberAds = n;
		return usersListView;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public Date getDate() {
		return date;
	}

	public void setDate(Date date) {
		this.date = date;
	}

	/*public Time getTime() {
		return time;
	}

	public void setTime(Time time) {
		this.time = time;
	}*/

	public float getPrice() {
		return price;
	}

	public void setPrice(float price) {
		this.price = price;
	}
	
	public int getIdLocation() {
		return idLocation;
	}

	public void setIdLocation(int idLocation) {
		this.idLocation = idLocation;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

}
