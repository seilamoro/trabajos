package managedbean.systemAdministration;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;
import jpa.MessageJPA;

@ManagedBean(name = "listMessages")
@SessionScoped
public class ListMessages implements Serializable{
	private static final long serialVersionUID = 1L;	

	@EJB
	private SystemAdministrationFacadeRemote messagesRemote;
	
	private Collection<MessageJPA> messagesList;
	//stores the screen number where the user is 
	private int screen = 0;
	protected Collection<MessageJPA> messagesListView;
	protected int numberMessages = 0;

	
	/**
	 * Constructor method
	 * @throws Exception
	 */
	public ListMessages() throws Exception
	{
	
	}
	
	/**
	 * Method that returns an instance Collection of MessageJPA
	 * @return Collection Message
	 * @throws Exception 
	 */
	public Collection<MessageJPA> getMessageListView() throws Exception
	{
		this.messageList();
		int n =0;
		messagesListView = new ArrayList<MessageJPA>();
		for (Iterator<MessageJPA> iter2 = messagesList.iterator(); iter2.hasNext();)
		{
			MessageJPA m = (MessageJPA) iter2.next();
			if (n >= screen*10 && n< (screen*10+10))
			{				
				this.messagesListView.add(m);
			}
			n +=1;
		}
		this.numberMessages = n;
		return messagesListView;
	}
	
	/**
	 * Returns the total number of instances of Label
	 * @return Label number
	 */
	public int getNumberMessages()
	{ 
		return this.numberMessages;
	}
	
	/**
	 * allows forward or backward in user screens
	 */
	public void nextScreen()
	{
		if (((screen+1)*10 < messagesList.size()))
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
	 * Method used for Facelet to call listmessagesView Facelet
	 * @return Facelet name
	 * @throws Exception
	 */
	public String listMessages() throws Exception
	{
		messageList();
		return "listMessagesView";
	}
		
	
	/**
	 * Method that gets a list of instances
	 * @throws Exception
	 */
	@SuppressWarnings("unchecked")
	private void messageList() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		screen = 0;
		messagesRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		messagesList = (Collection<MessageJPA>)messagesRemote.listAllMessages();
	}
}
