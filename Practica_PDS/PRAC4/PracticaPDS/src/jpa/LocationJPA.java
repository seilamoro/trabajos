package jpa;

import java.io.Serializable;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToMany;
import javax.persistence.Table;

/**
 * JPA Class LocationJPA
 */
@Entity
@Table(name = "practicapds.location")
public class LocationJPA implements Serializable {

	private static final long serialVersionUID = 1L;

	
	@Id
	@Column(name = "id")
	private int id;
	private int province_id;
	private String population;
	private String population_seo;
	private String postal_code;
	private float latitude;
	private float longitude;

	public LocationJPA() {
		super();
	}

	public LocationJPA(int province_id, String population, String population_seo, String postal_code, float latitude,
			float longitude) {
		super();
		this.province_id = province_id;
		this.population = population;
		this.population_seo = population_seo;
		this.postal_code = postal_code;
		this.latitude = latitude;
		this.longitude = longitude;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getPopulation() {
		return population;
	}

	public void setPopulation(String population) {
		this.population = population;
	}

	public String getPopulation_seo() {
		return population_seo;
	}

	public void setPopulation_seo(String population_seo) {
		this.population_seo = population_seo;
	}

	public String getPostal_code() {
		return postal_code;
	}

	public void setPostal_code(String postal_code) {
		this.postal_code = postal_code;
	}

	public float getLatitude() {
		return latitude;
	}

	public void setLatitude(float latitude) {
		this.latitude = latitude;
	}

	public float getLongitude() {
		return longitude;
	}

	public void setLongitude(float longitude) {
		this.longitude = longitude;
	}

	public int getProvince_id() {
		return province_id;
	}

	public void setProvince_id(int province_id) {
		this.province_id = province_id;
	}

}
