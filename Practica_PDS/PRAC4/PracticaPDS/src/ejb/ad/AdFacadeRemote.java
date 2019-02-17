package ejb.ad;

import java.sql.Time;
import java.util.Collection;
import java.util.Date;

import javax.ejb.Remote;


import jpa.AdJPA;
import jpa.FavoritesJPA;
import jpa.LocationJPA;
import jpa.ProvinceJPA;
import jpa.UserJPA;

/**AdAdministrationFacadeRemote
 * Session EJB Remote Interfaces
 */
@Remote
public interface AdFacadeRemote {

	
	public Collection<?> findAds(String title,String description,Date date, float price, String address);

	public Collection<?> findAds(int id, String title, String description, Date date, float price, String address);
	
	public AdJPA showAd(int adId);
		
	public Collection<?> findMyFavorites(int userId);
	
	public Collection<?> listsAllAds();
	
	public Collection<?> listMyAds(int userId);
	
	public UserJPA getUser(int userId);
		
	public UserJPA getUser(String email);
	
	public Collection<?> listAllUsers();

	public Collection<?> listFavoritos();

	public boolean addAdToFavorites(int userId, int adId);
	
	public boolean removeAdFromFavorites(int userId, int adId);
		
	public boolean isAdFavorite(int adId, int userId);

	public Collection<?> listAllProvinces();
	
	public String getImage(int adId);

	public Collection<?> listAllLocations();


}
