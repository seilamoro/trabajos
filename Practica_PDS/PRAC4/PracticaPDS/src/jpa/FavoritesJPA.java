package jpa;

import java.io.Serializable;

import javax.persistence.*;

/**
 * JPA Class FavoritosJPA
 */
@Entity
@Table(name = "practicapds.favorites")
public class FavoritesJPA implements Serializable{

	private static final long serialVersionUID = 1L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;

	@ManyToOne(optional = false, targetEntity = AdJPA.class)
	@JoinColumn(name = "ad_id", referencedColumnName = "id")
	private AdJPA ad_id;

	@ManyToOne(optional = false, targetEntity = UserJPA.class)
	@JoinColumn(name = "user_id", referencedColumnName = "id")
	private UserJPA user_id;

	public FavoritesJPA() {
		super();
	}

	public FavoritesJPA(UserJPA user , AdJPA ad) {
		this.ad_id = ad;
		this.user_id = user;
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

	public UserJPA getUser_id() {
		return user_id;
	}

	public void setUser_id(UserJPA user_id) {
		this.user_id = user_id;
	}

	@Override
	public String toString() {
		return "FavoritesJPA{" + "ad=" + ad_id.getId() + ", user=" + user_id + '}';
	}
}
