class Comment < ActiveRecord::Base
  belongs_to :user
  attr_accessible :subject, :body, :created_at

  validates :subject, :presence => true,
    		       	  :length   => {:maximum => 100}
									 
  validates :body, :presence => true
									 
  default_scope :order => 'created_at DESC'
  
end
