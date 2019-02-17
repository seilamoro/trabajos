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
import jpa.ProvinceJPA;

@ManagedBean (name="listprovinces")
@SessionScoped
public class ListProvinces implements Serializable{

	private static final long serialVersionUID = 1L;	

	@EJB
	private AdFacadeRemote provincesRemote;
	
	private Collection<ProvinceJPA> provincesList;


	
	public ListProvinces() {
	}

	public Collection<ProvinceJPA> getProvincesList() throws Exception
	{
		provinceList();
		return provincesList;
	}
	@SuppressWarnings("unchecked")
	private void provinceList() throws Exception {
		Properties props = System.getProperties();
		Context ctx = new InitialContext(props);
		provincesRemote = (AdFacadeRemote) ctx.lookup("java:app/PracticaPDS.jar/AdFacadeBean!ejb.ad.AdFacadeRemote");
		provincesList = (Collection<ProvinceJPA>)provincesRemote.listAllProvinces();
	}
}
