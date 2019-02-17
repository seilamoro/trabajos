package ejb.systemAdministration;

import java.util.Collection;

import javax.ejb.Local;

import jpa.LabelJPA;

@Local
public interface SystemAdministrationFacade {
	public void login(String email, String pwd);
	public void registerUser(String nif, String name, String surname, String phone, String password, String email, String address);
	public void updatePersonalData (int id, String nif, String name, String surname, String phone, String password, String email, String address);
	public void addLabel(String text, String description);
	public void deleteLabel(int labelId);
	public Collection<?> listAllLabels();
	public void updateLabel (int labelId, String text, String description);
	public boolean registerAdministrator(String password, String email);
	public boolean removeComment (int commentId);
	public boolean removeMessage (int messageId);
	public LabelJPA showLabel(int labelId);
	public Collection<?> listAllMessages();
}
