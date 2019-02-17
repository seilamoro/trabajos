package managedbean.ad;


	import java.awt.Point;
	import java.awt.Rectangle;
	import java.io.Serializable;
	import java.sql.Time;
	import java.util.ArrayList;
	import java.util.Collection;
	import java.util.Date;
	import java.util.Iterator;
	import java.util.Map;
	import java.util.Properties;
	import java.util.logging.Logger;

	import javax.ejb.EJB;
	import javax.faces.bean.ManagedBean;
	import javax.faces.bean.SessionScoped;
	import javax.faces.context.ExternalContext;
	import javax.faces.context.FacesContext;
	import javax.faces.event.ActionEvent;
	import javax.faces.event.ValueChangeEvent;
	import javax.faces.model.SelectItem;
	import javax.naming.Context;
	import javax.naming.InitialContext;
	import ejb.ad.AdFacadeRemote;
	import jpa.AdJPA;
	import jpa.UserJPA;
	import managedbean.systemAdministration.Login;

	
	/**
	 * Managed Bean ListAds
	 */
	@ManagedBean(name = "listAds")
	@SessionScoped
	public class ListAds implements Serializable{
		/**
		 * 
		 */
		private AdFacadeRemote listAdsRemote;
		//stores AdJPA instance
		protected AdJPA dataAd;
		//stores UserJPA instance
		protected UserJPA dataUser;
		//stores userId,adId 
		protected int adId = 1;
		protected int userId = 1;
		//stores all the instances of AdJPA
		private Collection<AdJPA> adsList;
		//stores all the instances of AdJPA
		private Collection<UserJPA> usersList;
		//stores the screen number where the user is 
		private int screen = 0;
		//stores ten or fewer AdJPA instances that the user can see on a screen
		protected Collection<AdJPA> adsListView;
		//stores the total number of instances of AdJPA
		protected int numberAds = 0;
		
		
		public ListAds()  throws Exception {
			this.adList();
			//this.adList();
		}
		
		@SuppressWarnings("unchecked")
		private void adList() throws Exception {
			
			Properties props = System.getProperties();
			Context ctx = new InitialContext(props);
			screen = 0;
			listAdsRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
			ExternalContext tmpEc = FacesContext.getCurrentInstance().getExternalContext();
			Map sMap = tmpEc.getSessionMap();
			Login loginBean = (Login) sMap.get("login");
			dataUser = listAdsRemote.getUser(loginBean.getEmail());
			adsList = (Collection<AdJPA>)listAdsRemote.listMyAds(dataUser.getId());
					
		}
		
		/**
		 * Method that returns an instance Collection of 10 or less AdJPA according screen 
		 * where the user is.
		 * @return Collection AdJPA
		 * @throws Exception 
		 */
		public Collection<AdJPA> getAdsListView() throws Exception
		{
			this.adList();
			int n =0;
			adsListView = new ArrayList<AdJPA>();
			for (Iterator<AdJPA> iter2 = adsList.iterator(); iter2.hasNext();)
			{
				AdJPA lbl2 = (AdJPA) iter2.next();
				if (n >= screen*10 && n< (screen*10+10))
				{				
					this.adsListView.add(lbl2);
				}
				n +=1;
			}
			this.numberAds = n;
			return adsListView;
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
			//setDataAd();
		}
		public AdJPA getDataAd()
		{
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
		
					
		/**
		 * allows forward or backward in user screens
		 */
		public void nextScreen()
		{
			if (((screen+1)*10 < adsList.size()))
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
		 * Returns the total number of instances of AdJPA
		 * @return ad number
		 */
		public int getNumberAds()
		{ 
			return this.numberAds;
		}
		
		
		
		
		
		
		
		
	}


