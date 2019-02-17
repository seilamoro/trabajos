package managedbean.ad;

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
import javax.naming.NamingException;

import ejb.ad.AdFacadeRemote;
import jpa.LocationJPA;
import jpa.ProvinceJPA;

@ManagedBean (name="listlocations")
@SessionScoped
public class ListLocations implements Serializable{
	
	private static final long serialVersionUID = 1L;	

	@EJB
	private AdFacadeRemote locationsRemote;
	
	private Collection<LocationJPA> locationsList;
	
	public ListLocations() {
		
	}

	public Collection<LocationJPA> getLocationsList() throws Exception
	{
		locationList();
		return locationsList;
	}
	@SuppressWarnings("unchecked")
	private void locationList() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		locationsRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		locationsList = (Collection<LocationJPA>)locationsRemote.listAllLocations();
	}
}
