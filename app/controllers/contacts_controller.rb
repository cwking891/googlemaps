class ContactsController < ApplicationController

  before_filter :authenticate, :only => [:index, :new, :create, :destroy]
  before_filter :authorized_user, :only => [:edit, :destroy]
  
# Show all contacts for the user
  def index
    @title = "Your Contacts"
  end

# Add a new contact to your list
  def new
    @contact = Contact.new #leave out id so form-for knows it is a new record
    @title = "Add a Contact"
	@onload = "onload = setFocus('contact_first_name')"
  end

  def edit
    @title = "Contact Info"
  end
  
  def update
    @contact = Contact.find(params[:id])
    if @contact.update_attributes(params[:contact])
      flash[:success] = "Contact updated."
      redirect_to edit_contact_path
    else
      @title = "Contact Info"
      render 'edit'
    end
  end
	  
  def create
    @contact  = current_user.contacts.build(params[:contact])
    if @contact.save
      flash[:success] = "Contact #{@contact.name} created!"
      redirect_to new_contact_path
    else
      @title = "Add a Contact"
      @onload = "onload = setFocus('contact_first_name')"
      render 'new'
    end
  end

  def destroy
    @contact.destroy
    redirect_to contacts_path
### redirect_back_or root_path
  end

  private

    def authorized_user
      @contact = Contact.find(params[:id])
      redirect_to root_path unless current_user?(@contact.user)
    end

end
