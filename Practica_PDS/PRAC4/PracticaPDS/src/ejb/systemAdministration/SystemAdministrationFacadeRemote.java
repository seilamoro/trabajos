package ejb.systemAdministration;

import java.util.Collection;
import java.util.Iterator;

import javax.ejb.Remote;
import javax.persistence.PersistenceException;

import jpa.*;

@Remote
public interface SystemAdministrationFacadeRemote {
	public UserJPA login(String email, String pwd);
	public void registerUser(String nif, String name, String surname, String phone, String password, String email, int idLocation);
	public void updatePersonalData (int id, String nif, String name, String surname, String phone, String password, String email, int idLocatio);
	public void addLabel(String text, String description);
	public void deleteLabel(int idd);
	public Collection<?> listAllLabels();
	public void updateLabel (int id, String text, String description);
	public boolean registerAdministrator(String password, String email);
	public boolean removeComment (int commentId);
	public boolean removeMessage (int messageId);
	public LabelJPA showLabel(int id);
	public Collection<?> listAllMessages();
	public Collection<?> listAllComments();
	public UserJPA getPersonalData(int id);
	
}

