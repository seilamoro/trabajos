package jpa;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;

/**
 * JPA Class AdJPA
 */
@Entity
@Table(name = "practicapds.message")
public class MessageJPA implements Serializable {

	private static final long serialVersionUID = 1L;

	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;

	@OneToOne(optional = true, targetEntity = MessageJPA.class)
	@JoinColumn(name = "reply_message_id", referencedColumnName = "id")
	private MessageJPA reply_message_id;

	private String title;
	private String text;
	private Date submitted_date;

	@ManyToOne(optional = false, targetEntity = UserJPA.class)
	@JoinColumn(name = "user_id", referencedColumnName = "id")
	private UserJPA user_id;

	@ManyToOne(optional = false, targetEntity = AdJPA.class)
	@JoinColumn(name = "ad_id", referencedColumnName = "id")
	private AdJPA ad_id;

	public MessageJPA() {
		super();
	}

	public MessageJPA(String title, String text, Date submitted_date, UserJPA user_id, AdJPA ad_id) {
		super();
		this.title = title;
		this.text = text;
		this.submitted_date = submitted_date;
		this.user_id = user_id;
		this.ad_id = ad_id;
	}

	public MessageJPA(MessageJPA reply_message_id, String title, String text, Date submitted_date, UserJPA user_id,
			AdJPA ad_id) {
		super();
		this.reply_message_id = reply_message_id;
		this.title = title;
		this.text = text;
		this.submitted_date = submitted_date;
		this.user_id = user_id;
		this.ad_id = ad_id;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getText() {
		return text;
	}

	public void setText(String text) {
		this.text = text;
	}

	public UserJPA getUser_id() {
		return user_id;
	}

	public void setUser_id(UserJPA user_id) {
		this.user_id = user_id;
	}

	public Date getSubmitted_date() {
		return submitted_date;
	}

	public void setSubmitted_date(Date submitted_date) {
		this.submitted_date = submitted_date;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}

	public MessageJPA getReply_message_id() {
		return reply_message_id;
	}

	public void setReply_message_id(MessageJPA reply_message_id) {
		this.reply_message_id = reply_message_id;
	}

	public AdJPA getAd_id() {
		return ad_id;
	}

	public void setAd_id(AdJPA ad_id) {
		this.ad_id = ad_id;
	}

}
