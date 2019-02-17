package jpa;

import javax.persistence.*;
import java.io.Serializable;
import java.util.List;
import java.util.Set;

/**
 * JPA Class LabelJPA
 */
@Entity
@Table(name = "practicapds.label")
public class LabelJPA implements Serializable {

	private static final long serialVersionUID = 1L;

	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;
	private String text;
	private String description;
	
	@ManyToMany(fetch=FetchType.EAGER,mappedBy = "labels")
    private Set<AdJPA> ads;


	public LabelJPA() {
		super();
	}

	/**
	 * Constructor con todos los parametros
	 */
	public LabelJPA(int id, String text, String description) {
		this.id = id;
		this.text = text;
		this.description = description;
	}

	/**
	 * Constructor con un parametro
	 */
	public LabelJPA(int id) {
		this.id = id;
	}

	/**
	 * Methods get/set the fields of database Id Primary Key field
	 */

	public String getText() {
		return text;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public void setText(String text) {
		this.text = text;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}
	
	@Override
	public String toString() {
		return "LabelJPA [labelId=" + id + ", text=" + text + ", description=" + description + "]";
	}

	public Set<AdJPA> getAds() {
		return ads;
	}

	public void setAds(Set<AdJPA> ads) {
		this.ads = ads;
	}

}
