class SessionsController < ApplicationController

  def new
	@onload = "onload = setFocus('session_email')"
    @title = "Sign In"
  end

  def create

    user = User.authenticate(params[:session][:email],
                             params[:session][:password])
    if user.nil?
      flash.now[:error] = "Invalid email/password combination."
      @title = "Sign In"
      render 'new'
    else
      # Sign the user in and redirect to the user's show page.
      flash[:success] = "Welcome #{user.name}, enjoy Google Maps!"
      sign_in user
      redirect_back_or user
    end

  end

  def destroy
    sign_out
    redirect_to root_path

  end

end
