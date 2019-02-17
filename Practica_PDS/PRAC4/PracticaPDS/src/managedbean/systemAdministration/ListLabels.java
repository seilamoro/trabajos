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
import jpa.LabelJPA;

@ManagedBean(name = "listLabels")
@SessionScoped
public class ListLabels implements Serializable{

	private static final long serialVersionUID = 1L;	

	@EJB
	private SystemAdministrationFacadeRemote labelsRemote;
	
	private Collection<LabelJPA> labelsList;
	//stores the screen number where the user is 
	private int screen = 0;
	protected Collection<LabelJPA> labelsListView;
	protected int numberLabels = 0;

	
	/**
	 * Constructor method
	 * @throws Exception
	 */
	public ListLabels() throws Exception
	{
	
	}
	
	/**
	 * Method that returns an instance Collection of 10 or less PetJPA according screen 
	 * where the user is.
	 * @return Collection Label
	 * @throws Exception 
	 */
	public Collection<LabelJPA> getLabelListView() throws Exception
	{
		this.labelList();
		int n =0;
		labelsListView = new ArrayList<LabelJPA>();
		for (Iterator<LabelJPA> iter2 = labelsList.iterator(); iter2.hasNext();)
		{
			LabelJPA l = (LabelJPA) iter2.next();
			if (n >= screen*10 && n< (screen*10+10))
			{				
				this.labelsListView.add(l);
			}
			n +=1;
		}
		this.numberLabels = n;
		return labelsListView;
	}
	
	/**
	 * Returns the total number of instances of Label
	 * @return Label number
	 */
	public int getNumberLabels()
	{ 
		return this.numberLabels;
	}
	
	/**
	 * allows forward or backward in user screens
	 */
	public void nextScreen()
	{
		if (((screen+1)*10 < labelsList.size()))
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
	 * Method used for Facelet to call listPetsView Facelet
	 * @return Facelet name
	 * @throws Exception
	 */
	public String listLabels() throws Exception
	{
		labelList();
		return "listLabelsView";
	}
	
	/**
	 * Method used for Facelet to call showLabelView Facelet
	 * @return Facelet name 
	 */
	public String setUpdateLabel(){
		return "updateLabelView";
	}
	/*
	public String setAddLabel(){
		return "addLabelView";
	}*/
		
	
	/**
	 * Method that gets a list of instances
	 * @throws Exception
	 */
	@SuppressWarnings("unchecked")
	private void labelList() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		screen = 0;
		labelsRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		labelsList = (Collection<LabelJPA>)labelsRemote.listAllLabels();
	}
}
