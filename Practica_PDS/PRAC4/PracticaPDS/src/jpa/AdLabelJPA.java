package jpa;

import java.io.Serializable;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

/**
 * JPA Class AdLabelJPA
 */
@Entity
@Table(name = "practicapds.ad_label")
public class AdLabelJPA implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;

	@ManyToOne(optional = false, targetEntity = AdJPA.class)
	@JoinColumn(name = "ad_id", referencedColumnName = "id")
	private AdJPA ad_id;

	@ManyToOne(optional = false, targetEntity = LabelJPA.class)
	@JoinColumn(name = "label_id", referencedColumnName = "id")
	private LabelJPA label_id;

	public AdLabelJPA() {
		super();
	}

	public AdLabelJPA(AdJPA ad_id, LabelJPA label_id) {
		super();
		this.ad_id = ad_id;
		this.label_id = label_id;
	}

	/**
	 * Methods get/set the fields of database Id Primary Key field
	 */

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public AdJPA getAd_id() {
		return ad_id;
	}

	public void setAd_id(AdJPA ad_id) {
		this.ad_id = ad_id;
	}

	public LabelJPA getLabel_id() {
		return label_id;
	}

	public void setLabel_id(LabelJPA label_id) {
		this.label_id = label_id;
	}

	@Override
	public String toString() {
		return "AdLabelJPA [id=" + id + ", ad_id=" + ad_id + ", label_id=" + label_id + "]";
	}

}
