package ejb.adAdministration;

import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;
import java.util.List;

import javax.ejb.Stateless;
import javax.faces.bean.ManagedProperty;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.Query;

import jpa.AdJPA;
import jpa.LabelJPA;
import jpa.UserJPA;
import managedbean.systemAdministration.Login;

/**
 * EJB Session Bean Class
 */
@Stateless
public class AdAdministrationFacadeBean implements AdAdministrationFacadeRemote {

	// Persistence Unit Context
	@PersistenceContext(unitName = "practicapds")
	private EntityManager entman;

	@ManagedProperty(value = "#{login}")
	private Login login;

	public AdAdministrationFacadeBean() {
	}

	@Override
	public int addAd(String title, String description, Date date, float price, String email) {
		UserJPA user = (UserJPA) entman.createQuery("FROM UserJPA b WHERE b.email = ?1").setParameter(1, email).getSingleResult();

		Query query = entman.createNativeQuery("INSERT INTO practicapds.ad (title, description, submitted_date, price, user_id)  VALUES(?1, ?2, ?3, ?4, ?5) RETURNING id");

		query.setParameter(1, title);
		query.setParameter(2, description);
		query.setParameter(3, date);
		query.setParameter(4, price);
		query.setParameter(5, user.getId());

		int id = (int) query.getSingleResult();
		return id;
	}

	@Override
	public void markAdAsSold(int adId) {
		AdJPA ad = entman.find(AdJPA.class, adId);
		if (ad != null) {
			ad.setStatus(false);
		}
	}

	@Override
	public boolean addImageToAd(int adId, byte[] image) {
		AdJPA ad = entman.find(AdJPA.class, adId);
		if (ad != null) {
			ad.setPicture(image);
		}
		return Boolean.TRUE;
	}

	@Override
	public boolean removeImageFromAd(int adId) {
		AdJPA ad = entman.find(AdJPA.class, adId);
		if (ad != null) {
			ad.setPicture(null);
		}
		return Boolean.TRUE;
	}

	@Override
	public boolean removeLabelFromAd(int adId, int labelId) {
		AdJPA adJPA = entman.find(AdJPA.class, adId);
		LabelJPA labelJPA = entman.find(LabelJPA.class, labelId);

		adJPA.removeLabel(labelJPA);
		
		return Boolean.TRUE;
	}

	@Override
	public boolean addLabelToAd(int adId, LabelJPA label) {
		AdJPA adJPA = entman.find(AdJPA.class, adId);
		List<LabelJPA> labelList =  new ArrayList<LabelJPA>();
		labelList.addAll(adJPA.getLabels());
		boolean appears = false;
		
		for (int i = 0; i < labelList.size(); i++) {
			if(labelList.get(i).getId() == label.getId()) {
				appears = true;
				break;
			}
		}
		
	    if (adJPA != null && !appears) {
	    	adJPA.addLabel(label);
	    	entman.persist(adJPA);
	    }

		return Boolean.TRUE;
	
	}

	@Override
	public boolean lockAd(int adId) {
		AdJPA adJPA = entman.find(AdJPA.class, adId);
		if (adJPA != null) {
			adJPA.setLocked(true);
		}
		return adJPA.isLocked();
	}

	@Override
	public boolean unlockAd(int adId) {
		AdJPA adJPA = entman.find(AdJPA.class, adId);
		if (adJPA != null) {
			adJPA.setLocked(false);
		}
		return !adJPA.isLocked();
	}

	@Override
	public Collection<LabelJPA> getAllLabels() {
		@SuppressWarnings("unchecked")
		Collection<LabelJPA> allLabels = entman.createQuery("from LabelJPA").getResultList();
		return allLabels;
	}

}
