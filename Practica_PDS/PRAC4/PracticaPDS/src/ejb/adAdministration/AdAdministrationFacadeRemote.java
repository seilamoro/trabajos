package ejb.adAdministration;

import java.util.Collection;
import java.util.Date;

import javax.ejb.Remote;

import jpa.LabelJPA;

@Remote
public interface AdAdministrationFacadeRemote {
	
	public int addAd(String title, String description, Date date, float price, String email);
	
	public void markAdAsSold(int adId);
	
	public boolean addImageToAd(int adId, byte[] image);

	public boolean addLabelToAd(int adId, LabelJPA label);
	
	
	public boolean removeImageFromAd(int adId);
	
	public boolean removeLabelFromAd(int adId, int labelId);
	
	public boolean lockAd(int adId);
	
	public boolean unlockAd(int adId);

	public Collection<LabelJPA> getAllLabels();
}
