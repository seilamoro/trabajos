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
import jpa.UserRatingJPA;

@ManagedBean(name = "listComments")
@SessionScoped
public class ListComments implements Serializable{
	private static final long serialVersionUID = 1L;	

	@EJB
	private SystemAdministrationFacadeRemote commentsRemote;
	
	private Collection<UserRatingJPA> commentsList;
	//stores the screen number where the user is 
	private int screen = 0;
	protected Collection<UserRatingJPA> commentsListView;
	protected int numberComments = 0;

	
	/**
	 * Constructor method
	 * @throws Exception
	 */
	public ListComments() throws Exception
	{
	
	}
	
	/**
	 * Method that returns an instance Collection of MessageJPA
	 * @return Collection Message
	 * @throws Exception 
	 */
	public Collection<UserRatingJPA> getCommentsListView() throws Exception
	{
		this.commentList();
		int n =0;
		commentsListView = new ArrayList<UserRatingJPA>();
		for (Iterator<UserRatingJPA> iter2 = commentsList.iterator(); iter2.hasNext();)
		{
			UserRatingJPA m = (UserRatingJPA) iter2.next();
			if (n >= screen*10 && n< (screen*10+10))
			{				
				this.commentsListView.add(m);
			}
			n +=1;
		}
		this.numberComments = n;
		return commentsListView;
	}
	
	/**
	 * Returns the total number of instances of Label
	 * @return Label number
	 */
	public int getNumberComments()
	{ 
		return this.numberComments;
	}
	
	/**
	 * allows forward or backward in user screens
	 */
	public void nextScreen()
	{
		if (((screen+1)*10 < commentsList.size()))
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
	 * Method used for Facelet to call listCommentsView Facelet
	 * @return Facelet name
	 * @throws Exception
	 */
	public String listComments() throws Exception
	{
		commentList();
		return "listCommentsView";
	}
		
	
	/**
	 * Method that gets a list of instances
	 * @throws Exception
	 */
	@SuppressWarnings("unchecked")
	private void commentList() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		screen = 0;
		commentsRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		commentsList = (Collection<UserRatingJPA>)commentsRemote.listAllComments();
	}
}
