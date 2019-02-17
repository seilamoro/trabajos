package managedbean.systemAdministration;


import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;
import jpa.LabelJPA;

@ManagedBean(name = "labelshow")
@SessionScoped
public class ShowLabel implements Serializable{

	private static final long serialVersionUID = 1L;

	@EJB
	private SystemAdministrationFacadeRemote showLabelRemote;
	//stores Label instance
	protected LabelJPA dataLabel;
	//stores Label number id
	protected int id =1;
	
	public ShowLabel() throws Exception 
	{
		setDataLabel();
	}
	
	/**
	 * Get/set the id number and Label instance
	 * @return Label Id
	 */
	public int getId()
	{
		return id;
	}
	public void setId(int id) throws Exception
	{
		this.id = id;
		setDataLabel();
	}
	public LabelJPA getDataLabel()
	{
		return dataLabel;
	}	
	public void setDataLabel() throws Exception
	{	
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		showLabelRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		dataLabel = (LabelJPA) showLabelRemote.showLabel(id);
	}
}
