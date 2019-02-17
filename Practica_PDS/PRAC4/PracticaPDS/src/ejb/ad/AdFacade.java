package ejb.ad;

import java.sql.Time;
import java.util.Collection;
import java.util.Date;

import javax.ejb.Local;

import jpa.AdJPA;
import jpa.UserJPA;

/**
 * Session EJB Local Interfaces
 */
@Local
public interface AdFacade {

	public Collection<?> findAds(String title, String description, Date date, Time time, float price, String address);

	public AdJPA showAd(int adId);

	public Collection<?> findMyFavorites(int userId);
	// public Collection<AdJPA> findMyFavorites(String nif);

	public Collection<?> listsAllAds();

	public Collection<?> listMyAds(int userId);

	public UserJPA getUser(int userId);
	// public UserJPA getUser(String nif);

	public Collection<?> listAllUsers();

	public Collection<?> listFavoritos();

	public boolean addAdToFavorites(int userId, int adId);
	// public boolean addAdToFavorites(String nif, int adId);

	public boolean removeAdFromFavorites(int userId, int adId);
	// public boolean removeAdFromFavorites(String nif, int adId);
	
	public String getImage(int adId);
}
