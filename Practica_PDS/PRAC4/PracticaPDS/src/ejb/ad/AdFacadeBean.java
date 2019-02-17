/*
 * Copyright FUOC.  All rights reserved.
 * @author Vicenc Font Sagrista, 2012
 */
package ejb.ad;

import java.sql.Time;
import java.util.ArrayList;
import java.util.Base64;
import java.util.Collection;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.stream.Collectors;

import javax.ejb.Stateless;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.PersistenceException;
import javax.persistence.Query;

import jpa.AdJPA;
import jpa.FavoritesJPA;
import jpa.LabelJPA;
import jpa.ProvinceJPA;
import jpa.UserJPA;

/**
 * EJB Session Bean Class
 */
@Stateless
public class AdFacadeBean implements AdFacadeRemote {

	// Persistence Unit Context
	@PersistenceContext(unitName = "practicapds")
	private EntityManager entman;

	public AdFacadeBean() {
		// TODO Auto-generated constructor stub
	}

	/**
	 * Method that returns Collection of AdJPA
	 */
	@Override
	public Collection<AdJPA> findAds(String title, String description, Date date, float price, String address) {
		title = title == null ? "" : title;
		description = description == null ? "" : description;
		String qTitle = title != "" ? " AND a.title like CONCAT(?1, '%')" : "";
		String qDescription = description != "" ? " AND a.description like CONCAT(?2, '%') " : "";

		Query q = entman.createQuery("FROM AdJPA a WHERE 1=1 " + qTitle + qDescription, AdJPA.class);
		// + date != null ? " OR a.date = " + date : ""
		// + time != null ? " OR a.time = " + time : ""
		// + " OR a.price = " + price);
		// + " OR a.address = ?6");
		if (title != "")
			q.setParameter(1, title);
		if (description != "")
			q.setParameter(2, description);

		// q.setParameter(3, date);
		// q.setParameter(4, time);
		// q.setParameter(5, price);
		// q.setParameter(6, address);

		@SuppressWarnings("unchecked")
		Collection<AdJPA> findAds = (Collection<AdJPA>) q.getResultList();
		// Collection<AdJPA> findAds = entman.createQuery("FROM AdJPA a WHERE
		// a.title="+title+"AND a.description="+description+"AND
		// a.date="+date+"AND a.time="+time+"AND a.price="+price+"AND (select
		// b.codpostal from UserJPA b where
		// b.codpostal="+address).getResultList();
		return findAds;
	}
	
	
	/*@SuppressWarnings("unchecked")
	@Override
	public Collection<AdJPA> findAds(String title, String description, Date date, float price, String address) {
		
		
		title = title == null ? "" : title;
		description = description == null ? "" : description;
		Collection<AdJPA> findAds = null;
		int location_id = (int) entman.createQuery("SELECT l.id FROM LocationJPA l WHERE l.postal_code= ?1").setParameter(1, address).getSingleResult();
		Collection<UserJPA> users = listAllUsers();
		String qTitle = title != "" ? " AND a.title like CONCAT(?1, '%')" : "";
		String qDescription = description != "" ? " AND a.description like CONCAT(?2, '%') " : "";
		String qDate = date.toString() != "" ? "AND a.date like CONCAT(?3,'%')" : "";
		String qPrice = toString().valueOf(price) != "" ? "AND a.price like CONCAT(?4,'%')" : "";
		String qAddress = address != "" ? " AND a.user_id IN (SELECT b.id FROM UserJPA WHERE b.location_id like CONCAT(?5,'%') ORDER BY a.id" : "";
		Query q = entman.createQuery("FROM AdJPA a WHERE 1=1 " + qTitle + qDescription + qDate + qPrice + qAddress, AdJPA.class);		
		if ((title != "") && (description != "") && (date.toString() != "") && (price != Float.parseFloat("")) && (address.equals("") == false) ){
			
			location_id = (int) entman.createQuery("SELECT l.id FROM LocationJPA l WHERE l.postal_code= ?1").setParameter(1, address).getSingleResult();
			qAddress = address != "" ? " AND a.user_id IN (SELECT b.id FROM UserJPA WHERE b.location_id like CONCAT(?5,'%') ORDER BY a.id" : ""; 
			q = entman.createQuery("FROM AdJPA a WHERE 1=1 " + qTitle + qDescription + qDate + qPrice + qAddress, AdJPA.class);
			q.setParameter(1, title);
			q.setParameter(2, description);
			q.setParameter(3, date);
			q.setParameter(4, price);
			q.setParameter(5,location_id);
			findAds = (Collection<AdJPA>) q.getResultList();
			return findAds;
		}
		findAds = (Collection<AdJPA>) q.getResultList();
		return findAds;
	}*/
	
	@SuppressWarnings("unchecked")
	@Override
	public Collection<AdJPA> findAds(int id, String title, String description, Date date, float price, String address) {
		int location_id;
		int radio = 6378;
		Collection<UserJPA> users = null;
		Collection<AdJPA> listaAdsOrdenada = null;
		UserJPA user = getUser(id); 
		
		title = title == null ? "" : title;
		description = description == null ? "" : description;		
		
		String qTitle = title != "" ? " AND a.title like CONCAT(?1, '%')" : "";
		String qDescription = description != "" ? " AND a.description like CONCAT(?2, '%') " : "";
		//String qDate = date.toString() != "" ? "AND a.date like CONCAT(?3,'%')" : "";
		//String qDate = "AND a.date like CONCAT(?3,'%') " ;
		String qDate = "AND a.date = ?3 " ;
		String qPrice = toString().valueOf(price) != "" ? "AND a.price like CONCAT(?4,'%') " : "";
		String qAddress = address != "" ? " AND a.user_id IN (SELECT b.id FROM UserJPA WHERE b.location_id like CONCAT(?5,'%')" : ""; 
		
		
		if ((title != "") && (description != "") && (date.toString() != "") && (price != Float.parseFloat("")) && (address.equals("") == false) ){
			
			location_id = (int) entman.createQuery("SELECT l.id FROM LocationJPA l WHERE l.postal_code= ?1").setParameter(1, address).getSingleResult();
			Query q = entman.createQuery("FROM AdJPA a WHERE 1=1 " + qTitle + qDescription + qDate + qPrice + qAddress, AdJPA.class);
			q.setParameter(1, title);
			q.setParameter(2, description);
			q.setParameter(3, date);
			q.setParameter(4, price);
			q.setParameter(5,location_id);
			Collection<AdJPA> findAds = (Collection<AdJPA>) q.getResultList();
			
			return findAds;
			
		}else{
			location_id = (int) entman.createQuery("SELECT l.id FROM LocationJPA l WHERE l.id= ?1").setParameter(1, user.getLocation_id().getId()).getSingleResult();		
			users = (Collection<UserJPA>) entman.createQuery("FROM UserJPA a WHERE a.location_id = ?1").setParameter(1, location_id).getResultList();
			Collection<AdJPA> ads = listsAllAds();
			Collection<AdJPA> searchAds = null;
			for (AdJPA a : ads){
				for (UserJPA u : users){
					if (a.getUser().getId() == u.getId()){
						AdJPA ad = showAd(a.getId());
						searchAds.add(ad);
					}		
				}
			}			
			Map<Integer,Double> ma = new HashMap<Integer, Double>();
			//List listaAds = new ArrayList<AdJPA>();
			
			for (Iterator<AdJPA> iter = searchAds.iterator(); iter.hasNext();) {
				if ((iter.next().getTitle() == title) || (iter.next().getDescription() == description) || (iter.next().getPrice() == price)) {
					UserJPA us = iter.next().getUser();
					Double lat1 = Double.valueOf(us.getLocation_id().getLatitude());
					Double lat2 = Double.valueOf(user.getLocation_id().getLatitude());
					Double lon1 = Double.valueOf(us.getLocation_id().getLongitude());
					Double lon2 = Double.valueOf(user.getLocation_id().getLongitude());
					Double latDistance = toRad(lat2-lat1);
					Double lonDistance = toRad(lon2-lon1);
					Double a = Math.sin(latDistance / 2) * Math.sin(latDistance / 2) + 
					           Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * 
					           Math.sin(lonDistance / 2) * Math.sin(lonDistance / 2);
					Double c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
					Double distance = radio * c;					     
					ma.put(iter.next().getId(), distance);
				}			
			}
			
			Map<Integer,Double> sortedMap = ma.entrySet().stream().sorted(Entry.comparingByValue()).collect(Collectors.toMap(Entry::getKey,Entry::getValue,(e1,e2)->e1,LinkedHashMap::new));
			List <Integer> sorted = new ArrayList<>(sortedMap.keySet());
			for(int i = 1; i <= sorted.size(); i++){
				listaAdsOrdenada.add(showAd(i));
			}
			return listaAdsOrdenada;
		}		
	}
	
	
	private Double toRad(double value) {
		 return value * Math.PI / 180;
	}

	/**
	 * Method that returns instance of the class AdJPA
	 */
	@Override
	public AdJPA showAd(int adId) {
		AdJPA ad = entman.find(AdJPA.class, adId);
		return ad;

	}

	/**
	 * Method that add a new ad to the favorite ad list of the user and returns
	 * a boolean
	 */
	@Override
	public boolean addAdToFavorites(int userId, int adId) {
		Query query = entman.createNativeQuery("INSERT INTO practicapds.favorites (user_id, ad_id) VALUES(?1, ?2) RETURNING id");

		query.setParameter(1, userId);
		query.setParameter(2, adId);

		int id = (int) query.getSingleResult();
		return id != 0;
	}

	/**
	 * Method that returns the user adList MODIFICADO: String nif pasa a int
	 * userid
	 */

	@SuppressWarnings({ "unchecked", "null" })
	@Override
	public Collection<AdJPA> findMyFavorites(int userId) {
		Collection<AdJPA> favoritos = entman.createQuery("SELECT a FROM FavoritesJPA f, AdJPA a WHERE a.id = f.ad_id.id and f.user_id.id= ?1").setParameter(1, userId).getResultList();
		return favoritos;
	}

	/**
	 * Method that remove an ad of the favorite ad list of the user and returns
	 * a boolean
	 */
	@Override
	public boolean removeAdFromFavorites(int userId, int adId) {
		AdJPA ad = showAd(adId);
		UserJPA user = getUser(userId);
		try {
			entman.createQuery("delete from FavoritesJPA f where f.user_id.id = ?1 and f.ad_id.id = ?2").setParameter(1, userId).setParameter(2, adId).executeUpdate();
			return true;
		} catch (PersistenceException e) {
			System.out.println(e);
			return false;
		}
	}

	/**
	 * Method that returns instance of the class UserJPA
	 */
	public UserJPA getUser(int userId) {
		// UserJPA user = entman.find(UserJPA.class, userId);
		UserJPA user = (UserJPA) entman.createQuery("FROM UserJPA u WHERE u.id=?1").setParameter(1, userId).getSingleResult();
		return user;
	}

	/**
	 * Method that returns instance of the class UserJPA
	 */
	public UserJPA getUser(String email) {
		UserJPA user = (UserJPA) entman.createQuery("FROM UserJPA u WHERE u.email=?1").setParameter(1, new String(email)).getSingleResult();
		return user;
	}

	/**
	 * Method that returns instance of the class AdJPA
	 */
	public java.util.Collection<AdJPA> listsAllAds() {
		@SuppressWarnings("unchecked")
		Collection<AdJPA> allAds = entman.createQuery("FROM AdJPA b WHERE b.status= true and locked = false ORDER BY b.id").getResultList();
		return allAds;
	}

	@SuppressWarnings("unchecked")
	@Override
	public Collection<UserJPA> listAllUsers() {
		Collection<UserJPA> allUsers = entman.createQuery("FROM UserJPA b ").getResultList();
		return allUsers;
	}

	@SuppressWarnings("unchecked")
	@Override
	public Collection<FavoritesJPA> listFavoritos() {
		Collection<FavoritesJPA> allFavUsers = entman.createQuery("FROM FavoritesJPA b ORDER BY b.user_id.id, b.ad_id ").getResultList();
		return allFavUsers;
	}

	@Override
	public Collection<?> listMyAds(int userId) {
		@SuppressWarnings("unchecked")
		Collection<AdJPA> allAds = entman.createQuery("FROM AdJPA b WHERE b.user.id = ?1 ORDER BY b.id").setParameter(1, userId).getResultList();
		return allAds;
	}

	public boolean isAdFavorite(int adId, int userId) {
		long cuenta = (long) entman.createQuery("Select count(*) FROM FavoritesJPA b WHERE b.ad_id.id = ?1 and b.user_id.id = ?2").setParameter(1, adId).setParameter(2, userId).getSingleResult();
		return cuenta > 0;
	}

	@Override
	public Collection<?> listAllProvinces() {
		@SuppressWarnings("unchecked")
		Collection<ProvinceJPA> allProvinces = entman.createQuery("from ProvinceJPA order by id").getResultList();
		return allProvinces;
	}
	
	@Override
	public String getImage(int adId) {
		AdJPA ad = showAd(adId);
		
		if(ad.getPicture() != null)
			return Base64.getEncoder().encodeToString(ad.getPicture());
		
		return null;
		
	}

	@Override
	public Collection<?> listAllLocations() {
		@SuppressWarnings("unchecked")
		Collection<ProvinceJPA> allLocations = entman.createQuery("from LocationJPA order by id").getResultList();
		return allLocations;
	}

}
