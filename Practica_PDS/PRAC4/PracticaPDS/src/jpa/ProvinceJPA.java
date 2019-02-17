package jpa;

import java.io.Serializable;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.Table;

/**
 * JPA Class ProvinceJPA
 */
@Entity
@Table(name = "practicapds.province")
public class ProvinceJPA implements Serializable {

	private static final long serialVersionUID = 1L;
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	@JoinColumn(name = "location", referencedColumnName = "id_province")
	private int id;
	private String province;
	private String province_seo;
	private String province3;

	public String getProvince_seo() {
		return province_seo;
	}

	public void setProvince_seo(String province_seo) {
		this.province_seo = province_seo;
	}

	public String getProvince3() {
		return province3;
	}

	public void setProvince3(String province3) {
		this.province3 = province3;
	}

	public ProvinceJPA() {
		super();
	}

	public ProvinceJPA(int id, String province, String province_seo, String province3) {
		this.id = id;
		this.province = province;
		this.province_seo = province_seo;
		this.province3 = province3;
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

	public String getProvince() {
		return province;
	}

	public void setProvince(String province) {
		this.province = province;
	}

	@Override
	public String toString() {
		return "ProvinceJPA [id=" + id + ", province=" + province + ", province_seo=" + province_seo + ", province3="
				+ province3 + "]";
	}

}
