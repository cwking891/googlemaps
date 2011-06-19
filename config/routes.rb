Googlemaps::Application.routes.draw do
  resources :users
  resources :sessions,   :only => [:new, :create, :destroy]
# resources :microposts, :only => [:create, :destroy]
  resources :contacts

  match "/home",	 :to => "pages#home"
  match "/contact",  :to => "pages#contact"
  match "/help",	 :to => "pages#help"
  match "/about",	 :to => "pages#about"
  root				 :to => "pages#home"

  match '/signup',   :to => 'users#new'
  match '/signin',   :to => 'sessions#new'
  match '/signout',  :to => 'sessions#destroy'

# paths made available by resources :contacts
#  contacts_path           returns    /contacts
#  new_contact_path 	   returns    /contacts/new
#  edit_contact_path(id)   returns    /contacts/:id/edit 
#  contact_path(id) 	   returns    /contacts/:id      

# Home page will have a link to show all Contacts: contacts_path
# Home page has a partial listing all user's contacts.
# Each of those contacts can be clicked to edit: edit_contact_path(contact)
# The Contacts page also has the edit_contact_path(contact) link
# The Contacts page also has the create and destroy actions

end
