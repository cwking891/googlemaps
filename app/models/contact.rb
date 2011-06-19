class Contact < ActiveRecord::Base

  belongs_to :user

  validates :first_name, :presence => true,
    		       	 :length   => {:maximum => 20}
									 
  validates :last_name, :presence => true,
    		       	:length   => {:maximum => 20}
									 
  validates :address, :presence => true,
    		          :length   => {:maximum => 40}
									 
  validates :city, :presence => true,
    		       :length   => {:maximum => 30}
									 
  validates :state, :presence => true,
    		        :length   => {:maximum => 2}
									 
  validates :zip,  :presence => true,
    		       :length   => {:maximum => 5}
									 
  validates :country, :presence => true,
    		          :length   => {:maximum => 40}

  validates :user_id, :presence => true

  default_scope :order => 'contacts.last_name, contacts.first_name '

  def name
	first_name + " " + last_name
  end

  def full_address
     address + "\n" + city + ", " + state + "  " + zip
  end	 
									 

end
