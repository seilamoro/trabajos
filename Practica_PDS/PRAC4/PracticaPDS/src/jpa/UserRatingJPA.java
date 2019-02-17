package jpa;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;

/**
 * JPA Class AdJPA
 */
@Entity
@Table(name = "practicapds.user_rating")
public class UserRatingJPA implements Serializable {

	private static final long serialVersionUID = 1L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;
	private int rating;
	private String text;
	private Date submitted_date;

	@ManyToOne(optional = false)
	@JoinColumn(name = "user_from_id", referencedColumnName = "id")
	private UserJPA user_from_id;

	@ManyToOne(optional = false)
	@JoinColumn(name = "user_to_id", referencedColumnName = "id")
	private UserJPA user_to_id;

	public UserRatingJPA() {
		super();
	}

	public UserRatingJPA(int rating, String text, Date submitted_date, UserJPA user_from_id, UserJPA user_to_id) {
		super();
		this.rating = rating;
		this.text = text;
		this.submitted_date = submitted_date;
		this.user_from_id = user_from_id;
		this.user_to_id = user_to_id;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public int getRating() {
		return rating;
	}

	public void setRating(int rating) {
		this.rating = rating;
	}

	public String getText() {
		return text;
	}

	public void setText(String text) {
		this.text = text;
	}

	public Date getSubmitted_date() {
		return submitted_date;
	}

	public void setSubmitted_date(Date submitted_date) {
		this.submitted_date = submitted_date;
	}

	public UserJPA getUser_from_id() {
		return user_from_id;
	}

	public void setUser_from_id(UserJPA user_from_id) {
		this.user_from_id = user_from_id;
	}

	public UserJPA getUser_to_id() {
		return user_to_id;
	}

	public void setUser_to_id(UserJPA user_to_id) {
		this.user_to_id = user_to_id;
	}

}
