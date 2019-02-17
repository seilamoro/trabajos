package managedbean.systemAdministration;

import java.io.Serializable;

import javax.ejb.EJB;
import javax.faces.bean.ManagedBean;
import javax.faces.bean.SessionScoped;

import ejb.systemAdministration.SystemAdministrationFacadeRemote;

@ManagedBean (name = "labelUpdate")
@SessionScoped
public class UpdateLabel implements Serializable {

	private static final long serialVersionUID = 1L;
	@EJB
	private SystemAdministrationFacadeRemote updateLabelRemote;
	
	public UpdateLabel() throws Exception {
		
	}
	
	public String listLabels(int id, String text, String description) throws Exception
	{
		updateLabel(id, text, description);
		return "listLabelsView";
	}

	public void updateLabel(int id, String text, String description) throws Exception{
		
		updateLabelRemote.updateLabel(id, text, description);
	}

}
