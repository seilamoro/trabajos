package ejb.communication;

import java.util.Collection;

import javax.ejb.Local;

import jpa.MessageJPA;
import jpa.UserRatingJPA;

/**
 * Session EJB Local Interfaces
 */
@Local
public interface CommunicationFacade {
	
	public Collection<MessageJPA> showUserComments(String nif);

	public Collection<UserRatingJPA> showUserRating(String nif);

	public boolean publishMessage(int ad_id, String nif, String title, String text);

	public boolean replyMessage(int ad_id, int message_id, String nif, String title, String text);

	public boolean rateUser(String nif_from_user, String nif_to_user, String comment, int rate);

	public MessageJPA showMessage(int id);
	public Collection<MessageJPA> getMessages(int ad_id);
}
