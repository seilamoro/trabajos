package jpa;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Date;
import java.util.List;
import java.util.Set;

/**
 * JPA Class AdJPA
 */
@Entity
@Table(name = "practicapds.ad")
public class AdJPA implements Serializable {

	private static final long serialVersionUID = 1L;

	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	private int id;
	private String title;
	private String description;
	private Date submitted_date;
	
	private byte[] picture;
	private boolean status;
	private float price;
	private boolean locked;

	@ManyToOne(optional = false, targetEntity = UserJPA.class)
	@JoinColumn(name = "user_id", referencedColumnName = "id")
	private UserJPA user;

	@OneToMany(mappedBy = "ad_id", targetEntity = MessageJPA.class, cascade = CascadeType.REMOVE)
	private List<MessageJPA> messages;

    @ManyToMany(fetch=FetchType.EAGER,targetEntity=LabelJPA.class ,cascade = CascadeType.MERGE)
    @JoinTable(name = "ad_label",
        joinColumns = @JoinColumn(name = "ad_id"),
        inverseJoinColumns = @JoinColumn(name = "label_id")
    )
    private Set<LabelJPA> labels;


	public AdJPA(String title, String description, Date submitted_date, byte[] picture, boolean status, boolean locked,
			float price, List<MessageJPA> messages) {
		this.title = title;
		this.description = description;
		this.submitted_date = submitted_date;
		this.picture = picture;
		this.status = status;
		this.price = price;
		this.locked = locked;
		this.messages = messages;
	}

	public boolean getStatus() {
		return status;
	}

	public AdJPA() {
		super();
	}

	public List<MessageJPA> getMessages() {
		return messages;
	}

	public void setMessages(List<MessageJPA> messages) {
		this.messages = messages;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
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

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public byte[] getPicture() {
		return picture;
	}

	public void setPicture(byte[] picture) {
		this.picture = picture;
	}

	public void setStatus(boolean status) {
		this.status = status;
	}

	public float getPrice() {
		return price;
	}

	public void setPrice(float price) {
		this.price = price;
	}
	
	public Set<LabelJPA> getLabels() {
		return labels;
	}

	public void setLabels(Set<LabelJPA> labels) {
		this.labels = labels;
	}
	
    public void addLabel(LabelJPA label) {
        labels.add(label);
        label.getAds().add(this);
    }
 
    public void removeLabel(LabelJPA label) {
    	labels.remove(label);
        label.getAds().remove(this);
    }
	
	public boolean isStatus() {
		return status;
	}

	public boolean isLocked() {
		return locked;
	}

	public void setLocked(boolean locked) {
		this.locked = locked;
	}

	public UserJPA getUser() {
		return user;
	}

	public void setUser(UserJPA user) {
		this.user = user;
	}

	@Override
	public String toString() {
		return "AdJPA [title=" + title + ", description=" + description + ", submitted_date=" + submitted_date
				+ ", picture=" + picture + ", status=" + status + ", price=" + price + "]";
	}

}
