package ejb.systemAdministration;

import java.util.Collection;
import java.util.Iterator;

import javax.ejb.Stateless;
import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;
import javax.persistence.PersistenceContextType;
import javax.persistence.PersistenceException;
import javax.persistence.Query;

import jpa.*;
import managedbean.systemAdministration.RegisterUser;
import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@Stateless
public class SystemAdministrationFacadeBean implements SystemAdministrationFacadeRemote{
	
	//Persistence Unit Context
			@PersistenceContext(unitName="practicapds")
			private EntityManager entman;

			public SystemAdministrationFacadeBean(){
				
			}
			
			public UserJPA login(String email, String pwd) {
				UserJPA u = null;
				System.out.println(email + pwd);
				try{
					@SuppressWarnings("unchecked")
					Collection<UserJPA> users = entman.createQuery("FROM UserJPA u WHERE u.email = ?1 and u.password = ?2").setParameter(1, new String(email)).setParameter(2, new String(pwd)).getResultList();
					
					if(!users.isEmpty() || users.size() == 1){
						Iterator<UserJPA> iter = users.iterator();
						u = (UserJPA) iter.next();
					}
				}catch (PersistenceException e) {
					System.out.println(e);
				} 
			    return u;
										
			}

			public void registerUser(String nif, String name, String surname, String phone, String password,
					String email, int idLocation) {
				
				Query query = entman
						.createNativeQuery("INSERT INTO practicapds.user (nif, name, surname, phonenumber, password, email, location_id, userratting, role) "
								+ " VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
				query.setParameter(1, nif);
				query.setParameter(2, name);
				query.setParameter(3, surname);
				query.setParameter(4, phone);
				query.setParameter(5, password);
				query.setParameter(6, email);
				query.setParameter(7, idLocation);
				query.setParameter(8, 0);
				query.setParameter(9, 0);
				query.executeUpdate();
					
			}

			public void updatePersonalData(int id, String nif, String name, String surname, String phone, String password,
					String email, int idLocation) {
				UserJPA u = entman.find(UserJPA.class, id);
				try{
					u.setNif(nif);
					u.setName(name);
					u.setSurName(surname);
					u.setPhoneNumber(phone);
					u.setPassword(password);
					u.setEmail(email);
					u.setIdLocation(idLocation);
					entman.merge(u);
						
					}catch (PersistenceException e){
						System.out.print(e);
					}
			}
			@Override
			public void addLabel(String text, String description) {
				
				Query query = entman
						.createNativeQuery("INSERT INTO practicapds.label (text, description) "
								+ " VALUES(?, ?)");
				query.setParameter(1, text);
				query.setParameter(2, description);
				query.executeUpdate();
			}
		

			public void deleteLabel(int id) {
				try{
					LabelJPA l = entman.find(LabelJPA.class, id);
					entman.remove(l);
				}catch (PersistenceException e){
					System.out.println(e);
				}	
			}

			public Collection<?> listAllLabels() {
				@SuppressWarnings("unchecked")
				Collection<LabelJPA> allLabels = entman.createQuery("from LabelJPA order by id").getResultList();
				return allLabels;
			}

			public void updateLabel(int id, String text, String description) {
				// TODO Auto-generated method stub
				LabelJPA l = entman.find(LabelJPA.class, id);
				try{
					
						l.setText(text);
						l.setDescription(description);
						entman.merge(l);
						
					}catch (PersistenceException e){
						System.out.print(e);
					}
				}
				
			public boolean registerAdministrator(String password, String email) {
				try{
			    	UserJPA admin = null;
			    	Collection<UserJPA> users = entman.createQuery("FROM UserJPA u WHERE u.email = ?1 and u.password = ?2").setParameter(1, new String(email)).setParameter(2, new String(password)).getResultList();
					if(!users.isEmpty() || users.size() == 1){
						Iterator<UserJPA> iter = users.iterator();
						admin = (UserJPA) iter.next();
					}
			    	if(admin != null) {
			    		admin.setRole(1);
			    		entman.merge(admin);
			    		return true;
			    	} else {
			    		return false;
			    	}
			    }catch (PersistenceException e){
					System.out.print(e);
					return false;
				}
			}

			public boolean removeComment(int commentId) {
				try{
					UserRatingJPA r = entman.find(UserRatingJPA.class, commentId);
					entman.remove(r);
					return true;
				}catch (PersistenceException e){
					System.out.println(e);
					return false;
				}
			}

			public boolean removeMessage(int messageId) {
				try{
					MessageJPA m = entman.find(MessageJPA.class, messageId);
					entman.remove(m);
					return true;
				}catch (PersistenceException e){
					System.out.println(e);
					return false;
				}
			}

			public LabelJPA showLabel(int id) {
				LabelJPA label = null;
				try
				{
					@SuppressWarnings("unchecked")
					Collection<LabelJPA> labels = entman.createQuery("FROM LabelJPA b WHERE b.id = ?1").setParameter(1, new Integer(id)).getResultList();
					if (!labels.isEmpty() || labels.size()==1)
					{
						Iterator<LabelJPA> iter =labels.iterator();
						label = (LabelJPA) iter.next();	
						System.out.println(label);
					}
				}catch (PersistenceException e) {
					System.out.println(e);
				} 
			    return label;
			}
			
			public Collection<?> listAllMessages() {
				@SuppressWarnings("unchecked")
				Collection<MessageJPA> allMessage = entman.createQuery("from MessageJPA order by id DESC").getResultList();
				return allMessage;
			}
			
			public Collection<?> listAllComments() {
				@SuppressWarnings("unchecked")
				Collection<UserRatingJPA> allComments = entman.createQuery("from UserRatingJPA order by id").getResultList();
				return allComments;
			}

			@Override
			public UserJPA getPersonalData(int id) {
				UserJPA user = null;
				try
				{
					@SuppressWarnings("unchecked")
					Collection<UserJPA> users = entman.createQuery("FROM UserJPA b WHERE b.id = ?1").setParameter(1, new Integer(id)).getResultList();
					if (!users.isEmpty() || users.size()==1)
					{
						Iterator<UserJPA> iter =users.iterator();
						user = (UserJPA) iter.next();	
						System.out.println(user);
					}
				}catch (PersistenceException e) {
					System.out.println(e);
				} 
			    return user;
			}


}
