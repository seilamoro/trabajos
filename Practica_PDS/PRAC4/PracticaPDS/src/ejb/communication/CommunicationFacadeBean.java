package ejb.communication;

import java.util.Collection;
import java.util.Date;
import java.util.Iterator;

import javax.ejb.Stateless;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.PersistenceException;
import javax.persistence.Query;

import jpa.AdJPA;
import jpa.MessageJPA;
import jpa.UserJPA;
import jpa.UserRatingJPA;

/**
 * EJB Session Bean Class
 */
@Stateless
public class CommunicationFacadeBean implements CommunicationFacadeRemote {

	// Persistence Unit Context
	@PersistenceContext(unitName = "practicapds")
	private EntityManager entman;

	public CommunicationFacadeBean() {
		// TODO Auto-generated constructor stub
	}

	public Collection<MessageJPA> showUserComments(String nif) {
		@SuppressWarnings("unchecked")
		Collection<MessageJPA> messages = entman.createQuery("FROM MessageJPA b WHERE b.user_id.nif= ?1 ORDER BY id desc").setParameter(1, new String(nif)).getResultList();
		return messages;
	}

	@Override
	public Collection<UserRatingJPA> showUserRating(String nif) {
		@SuppressWarnings("unchecked")
		Collection<UserRatingJPA> userRatings = entman.createQuery("FROM UserRatingJPA b WHERE b.user_to_id.nif = ?1").setParameter(1, new String(nif)).getResultList();
		return userRatings;
	}

	@Override
	public boolean publishMessage(int ad_id, String nif, String title, String text) {
		UserJPA user = getUserByNif(nif);
		AdJPA ad = entman.find(AdJPA.class, ad_id);

		Query query = entman.createNativeQuery("INSERT INTO practicapds.message (title, text, submitted_date, user_id, ad_id) VALUES(?1, ?2, ?3, ?4, ?5) RETURNING id");

		query.setParameter(1, title);
		query.setParameter(2, text);
		query.setParameter(3, new Date());
		query.setParameter(4, user.getId());
		query.setParameter(5, ad.getId());

		int id = (int) query.getSingleResult();
		return id != 0;
	}

	@Override
	public boolean replyMessage(int ad_id, int message_id, String nif, String title, String text) {
		UserJPA user = getUserByNif(nif);
		AdJPA ad = entman.find(AdJPA.class, ad_id);

		Query query = entman.createNativeQuery("INSERT INTO practicapds.message (title, text, submitted_date, user_id, ad_id, reply_message_id) VALUES(?1, ?2, ?3, ?4, ?5, ?6) RETURNING id");

		query.setParameter(1, title);
		query.setParameter(2, text);
		query.setParameter(3, new Date());
		query.setParameter(4, user.getId());
		query.setParameter(5, ad.getId());
		query.setParameter(6, message_id);

		int id = (int) query.getSingleResult();
		return id != 0;
	}

	public boolean rateUser(String nif_from_user, String nif_to_user, String comment, int rate) {
		UserJPA user_from = getUserByNif(nif_from_user);
		UserJPA user_to = getUserByNif(nif_to_user);

		Query query = entman.createNativeQuery("INSERT INTO practicapds.user_rating (rating, text, submitted_date, user_from_id, user_to_id) VALUES(?1, ?2, ?3, ?4, ?5) RETURNING id");

		query.setParameter(1, rate);
		query.setParameter(2, comment);
		query.setParameter(3, new Date());
		query.setParameter(4, user_from.getId());
		query.setParameter(5, user_to.getId());

		int id = (int) query.getSingleResult();
		return id != 0;
	}

	public MessageJPA showMessage(int id) {
		MessageJPA msg = entman.find(MessageJPA.class, id);
		return msg;
	}

	public UserJPA getUserByNif(String nif) {
		UserJPA user = (UserJPA) entman.createQuery("FROM UserJPA u WHERE u.nif = ?1").setParameter(1, new String(nif)).getSingleResult();
		return user;
	}

	@SuppressWarnings("unchecked")
	public Collection<MessageJPA> getMessages(int ad_id) {
		Collection<MessageJPA> messages = (Collection<MessageJPA>) entman.createQuery("FROM MessageJPA b WHERE b.ad_id.id = ?1 ORDER BY b.ad_id.id, b.submitted_date asc, id asc").setParameter(1, ad_id).getResultList();
		return messages;
	}

}
