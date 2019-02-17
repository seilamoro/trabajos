package managedbean.systemAdministration;

import java.io.Serializable;
import java.util.Properties;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;
import javax.naming.Context;
import javax.naming.InitialContext;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean (name = "labelAdd")
@SessionScoped
public class AddLabel implements Serializable {

	private static final long serialVersionUID = 1L;

	@EJB
	private SystemAdministrationFacadeRemote addLabelRemote;
	
	protected String text;
	protected String description;
	
	
	public AddLabel() throws Exception {
		
	}
	
	public String getText(){
		return text;
	}
	
	public void setText(String text){
		this.text = text;
	
	}
	public String getDescription(){
		return description;
	}
	
	public void setDescription(String description){
		this.description = description;
	
	}
	
	public String listLabels() throws Exception
	{
		System.out.println("1 - NEW LABEL TO ADD: "+ this.text + this.description);
		addLabel();
		return "listLabelsView";
	}

	@SuppressWarnings("unchecked")
	public void addLabel() throws Exception{
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		addLabelRemote = (SystemAdministrationFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/SystemAdministrationFacadeBean!ejb.systemAdministration.SystemAdministrationFacadeRemote");
		addLabelRemote.addLabel(this.text, this.description);
		
	}


}
