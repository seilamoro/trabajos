package jpa;

import javax.persistence.*;
import java.io.Serializable;
import java.util.List;

/**
 * JPA Class UserJPA
 */
@Entity
@Table(name = "practicapds.user")
public class UserJPA implements Serializable {

	private static final long serialVersionUID = 1L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;
	private String nif;
	private String name;
	private String surName;
	private String phoneNumber;
	private String password;
	private String email;
	private int role=0;

	private float userRatting;

	@ManyToOne(optional = false, targetEntity = LocationJPA.class)
	@JoinColumn(name = "location_id", referencedColumnName = "id")
	private LocationJPA location_id; // sustituye a codPostal

	@OneToMany(mappedBy = "user_id", cascade = CascadeType.REMOVE, targetEntity = FavoritesJPA.class)
	private List<FavoritesJPA> favorites;

	@OneToMany(mappedBy = "user", cascade = CascadeType.REMOVE, targetEntity = AdJPA.class)
	private List<AdJPA> ads;

	@OneToMany(mappedBy = "user_id", cascade = CascadeType.REMOVE, targetEntity = MessageJPA.class)
	private List<MessageJPA> messages;

	public UserJPA() {
		super();
	}

	public UserJPA(String nif, String name, String surName, String phoneNumber, String password, String email) {
		super();
		this.nif = nif;
		this.name = name;
		this.surName = surName;
		this.phoneNumber = phoneNumber;
		this.password = password;
		this.email = email;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public UserJPA(String nif) {
		this.nif = nif;
	}

	public String getNif() {
		return nif;
	}

	public void setNif(String nif) {
		this.nif = nif;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getSurName() {
		return surName;
	}

	public void setSurName(String surName) {
		this.surName = surName;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPhoneNumber() {
		return phoneNumber;
	}

	public void setPhoneNumber(String phoneNumber) {
		this.phoneNumber = phoneNumber;
	}

	@Override
	public String toString() {
		return "UserJPA [nif=" + nif + ", name=" + name + ", surName=" + surName + ", phoneNumber=" + phoneNumber
				+ ", password=" + password + ", email=" + email + ", location="+ this.location_id.getPopulation() +" ]";
	}

	public float getUserRatting() {
		return userRatting;
	}

	public void setUserRatting(float userRatting) {
		this.userRatting = userRatting;
	}

	public int getRole() {
		return role;
	}

	public void setRole(int role) {
		this.role = role;
	}

	public int getIdProvince(){
		return this.location_id.getProvince_id();
	}
	
	public int getIdLocation(){
		return this.location_id.getId();
	}
		
	public LocationJPA getLocation_id() {
		return location_id;
	}

	public void setLocation_id(LocationJPA location_id) {
		this.location_id = location_id;
	}

	public void setIdLocation(int idLocation) {
		
	}

}
